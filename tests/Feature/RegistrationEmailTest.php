<?php

namespace Tests\Feature;

use App\Http\Utils\sendEmail;
use App\Mail\email_registration;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Mail\Message;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Mockery;
use RuntimeException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\Generator;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class RegistrationEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Models explicitly use mysql; redirect that connection to disposable memory only.
        config([
            'database.connections.mysql' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
            'mail.default' => 'array',
            'app.url' => 'https://registration.example.test',
        ]);
        DB::purge('mysql');

        Schema::connection('mysql')->create('exhibitions', function (Blueprint $table) {
            $table->id();
            foreach (['full_name', 'page', 'banner_file', 'opening_hours', 'team', 'date'] as $field) {
                $table->string($field);
            }
        });
        Schema::connection('mysql')->create('sub_exhibitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exhibitions_id');
        });
        Schema::connection('mysql')->create('registration_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_exhibitions_id');
            foreach (['barcode', 'name', 'company', 'job_title', 'city', 'country', 'email'] as $field) {
                $table->string($field);
            }
        });

        DB::connection('mysql')->table('exhibitions')->insert([
            'id' => 11,
            'full_name' => 'Agro, Foodtech, Water and Chemical',
            'page' => 'reguler',
            'banner_file' => 'banner-fixture',
            'opening_hours' => '10:00 - 18:00',
            'team' => 'GEM Indonesia Team',
            'date' => '20 - 22 October 2026',
        ]);
        DB::connection('mysql')->table('sub_exhibitions')->insert([
            'id' => 22,
            'exhibitions_id' => 11,
        ]);
        DB::connection('mysql')->table('registration_visitors')->insert([
            'id' => 33,
            'sub_exhibitions_id' => 22,
            'barcode' => '0000123456789',
            'name' => 'Alex Morgan',
            'company' => 'Example Industries',
            'job_title' => 'Operations Manager',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'email' => 'alex@example.test',
        ]);
    }

    public function test_queue_contains_identifiers_without_building_or_storing_qr_images(): void
    {
        Queue::fake();
        QrCode::shouldReceive('format')->never();
        File::shouldReceive('put')->never();

        sendEmail::sendEmailRegistration(33, null);

        Queue::assertPushed(SendQueuedMailable::class, 2);
        foreach (['alex@example.test', 'pt.globalexpomanagement@gmail.com'] as $recipient) {
            Queue::assertPushed(SendQueuedMailable::class, function ($job) use ($recipient) {
                $this->assertSame(33, $job->mailable->registration_id);
                $this->assertNull($job->mailable->url);
                $this->assertSame([], $job->mailable->viewData);
                $payload = serialize($job);
                $this->assertStringNotContainsString('qr_png', $payload);
                $this->assertStringNotContainsString('data:image', $payload);
                $this->assertStringNotContainsString("\x89PNG", $payload);
                $this->assertStringNotContainsString('api.qrserver.com', $payload);

                return $job->mailable->hasTo($recipient);
            });
        }
    }

    public function test_array_transport_embeds_png_bytes_as_cid_and_preserves_the_barcode_argument(): void
    {
        $this->mock_png_generation();
        $mail = new email_registration(33, 'https://legacy.example.test/old-qr.png');
        $mail = unserialize(serialize($mail));

        $sent = Mail::mailer('array')->to('recipient@example.test')->send($mail);
        $message = $sent->getOriginalMessage();
        $parts = $message->getAttachments();

        $this->assertCount(1, $parts);
        $this->assertSame('inline', $parts[0]->getDisposition());
        $this->assertSame('image', $parts[0]->getMediaType());
        $this->assertSame('png', $parts[0]->getMediaSubtype());
        $this->assertSame($this->png_fixture(), $parts[0]->getBody());
        $this->assertSame(IMAGETYPE_PNG, getimagesizefromstring($parts[0]->getBody())[2]);
        $this->assertStringContainsString('cid:' . $parts[0]->getContentId(), $message->getHtmlBody());
        $this->assertStringNotContainsString('https://legacy.example.test/old-qr.png', $message->getHtmlBody());
        $this->assertStringNotContainsString('api.qrserver.com', $message->getHtmlBody());
        $this->assertStringNotContainsString('data:image', $message->getHtmlBody());
        $this->assertStringContainsString('/registration/banner/exhibitions/banner-fixture', $message->getHtmlBody());
        $this->assertSame('Agro, Foodtech, Water and Chemical(20 - 22 October 2026)', $message->getSubject());
        $this->assertSame('no.reply@reg-gemindonesia.net', $message->getFrom()[0]->getAddress());
        $this->assertSame('GEM Indonesia', $message->getFrom()[0]->getName());
    }

    public function test_missing_registration_fails_before_qr_generation(): void
    {
        QrCode::shouldReceive('format')->never();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Registration data not found');

        (new email_registration(999))->build();
    }

    public function test_empty_barcode_fails_before_qr_generation(): void
    {
        DB::connection('mysql')->table('registration_visitors')->where('id', 33)->update(['barcode' => '']);
        QrCode::shouldReceive('format')->never();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Registration barcode is missing');

        (new email_registration(33))->build();
    }

    public function test_missing_vip_template_is_not_replaced_with_regular(): void
    {
        DB::connection('mysql')->table('exhibitions')->where('id', 11)->update(['page' => 'vip']);
        QrCode::shouldReceive('format')->never();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Registration email template is not available');

        (new email_registration(33))->build();
    }

    public function test_empty_generation_result_fails_without_a_remote_fallback(): void
    {
        $this->mock_png_generation('');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to generate');

        (new email_registration(33))->build();
    }

    public function test_current_busworld_legacy_data_dependency_remains_visible(): void
    {
        DB::connection('mysql')->table('exhibitions')->where('id', 11)->update(['page' => 'busworld']);
        $this->mock_png_generation();
        $mail = new email_registration(33);
        $mail->build();

        $this->assertSame('Email.busworld', $mail->view);
        $this->assertNull($mail->viewData['data']->getAttribute('first_name'));
        $this->assertNull($mail->viewData['data']->getAttribute('keterangan'));
        $this->assertNull($mail->viewData['data']->getAttribute('idexhibitions'));
    }

    public function test_redesigned_templates_preserve_original_visible_content_and_image_count(): void
    {
        foreach (['reguler', 'busworld'] as $page) {
            $source = file_get_contents(resource_path('views/Email/' . $page . '.blade.php'));
            preg_match_all('/\{\{-- Legacy implementation — retained for reference; inactive\.\n(.*?)\n--\}\}/s', $source, $legacy);
            $this->assertNotEmpty($legacy[1]);
            $data = $this->template_fixture($page);
            $old_html = Blade::render(implode('', $legacy[1]), $data, true);
            $new_html = view('Email.' . $page, $data)->render();

            $this->assertSame($this->visible_text($old_html), $this->visible_text($new_html), $page);
            $this->assertSame(substr_count($old_html, '<img '), substr_count($new_html, '<img '));
            $this->assertStringNotContainsString('Legacy implementation', $new_html);
            $this->assertStringNotContainsString('old-qr.png', $new_html);
            $this->assertStringNotContainsString('$req->', $new_html);
            $this->assertStringContainsString('cid:', $new_html);

            $long_data = $data;
            $attributes = (array) $long_data['data'];
            $attributes['name'] = 'Alexandria Morgan-Smith International Exhibition Visitor';
            $attributes['company'] = 'Example International Industrial Equipment and Manufacturing Solutions Corporation';
            $attributes['city'] = '';
            $attributes['email'] = 'alexandria.morgan.smith.international@example.test';
            $attributes['opening_hours'] = '<a href="https://event.example.test/hours">10:00 - 18:00</a>';
            $long_data['data'] = $page === 'reguler' ? (object) $attributes : $attributes;
            $old_long_html = Blade::render(implode('', $legacy[1]), $long_data, true);
            $new_long_html = view('Email.' . $page, $long_data)->render();
            $this->assertSame($this->visible_text($old_long_html), $this->visible_text($new_long_html));
            $this->assertStringContainsString('href="https://event.example.test/hours"', $new_long_html);
            $this->assertStringContainsString('City', $new_long_html);
        }
    }

    public function test_real_png_generation_when_imagick_is_available(): void
    {
        if (!extension_loaded('imagick')) {
            $this->markTestSkipped('Real Simple QrCode PNG generation requires PHP Imagick; MIME tests use a PNG fixture.');
        }

        $mail = new email_registration(33);
        $mail->build();

        $image = getimagesizefromstring($mail->viewData['qr_png']);
        $this->assertSame(IMAGETYPE_PNG, $image[2]);
        $this->assertSame(300, $image[0]);
        $this->assertSame(300, $image[1]);
    }

    protected function mock_png_generation($png = null): void
    {
        $generator = Mockery::mock(Generator::class);
        QrCode::shouldReceive('format')->once()->with('png')->andReturn($generator);
        $generator->shouldReceive('size')->once()->with(300)->andReturnSelf();
        $generator->shouldReceive('margin')->once()->with(4)->andReturnSelf();
        $generator->shouldReceive('errorCorrection')->once()->with('M')->andReturnSelf();
        $generator->shouldReceive('generate')->once()->with('0000123456789')
            ->andReturn($png ?? $this->png_fixture());
    }

    protected function png_fixture(): string
    {
        // Valid PNG for MIME construction only; this is not a QR-generation test.
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jp1sAAAAASUVORK5CYII=');
    }

    protected function template_fixture($page): array
    {
        $data = [
            'full_name' => 'Agro, Foodtech, Water and Chemical',
            'banner_file' => 'banner-fixture',
            'name' => 'Alex Morgan',
            'company' => 'Example Industries',
            'job_title' => 'Operations Manager',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'email' => 'alex@example.test',
            'opening_hours' => '10:00 - 18:00',
            'team' => 'GEM Indonesia Team',
            'idexhibitions' => 'banner-fixture',
            'first_name' => 'Alex',
            'last_name' => 'Morgan',
            'keterangan' => 'Agro, Foodtech, Water and Chemical',
            'url' => 'https://legacy.example.test/old-qr.png',
        ];

        return [
            'data' => $page === 'reguler' ? (object) $data : $data,
            'url' => 'https://legacy.example.test/old-qr.png',
            'qr_png' => $this->png_fixture(),
            'message' => new Message(new Email()),
        ];
    }

    protected function visible_text($html): string
    {
        $document = new DOMDocument();
        $previous_errors = libxml_use_internal_errors(true);
        $document->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous_errors);
        $xpath = new DOMXPath($document);
        $text = [];
        foreach ($xpath->query('//body//text()[not(ancestor::style) and not(ancestor::script)]') as $node) {
            $text[] = $node->nodeValue;
        }

        return preg_replace('/\s+/u', ' ', trim(implode(' ', $text)));
    }
}
