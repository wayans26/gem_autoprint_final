<?php

namespace App\Mail;

use App\Models\exhibitions;
use App\Models\registration_visitor;
use App\Models\sub_exhibitions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use RuntimeException;

class email_registration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $registration_id;
    public $url;
    // Legacy implementation — retained for reference; inactive.
    // public function __construct($registration_id, $url)
    // {
    //     //
    //     $this->registration_id = $registration_id;
    //     $this->url = $url;
    // }

    public function __construct($registration_id, $url = null)
    {
        //
        $this->registration_id = $registration_id;
        $this->url = $url;
    }

    public function build()
    {
        $registration_data = registration_visitor::join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->select(
                'exhibitions.full_name',
                'exhibitions.page',
                'exhibitions.banner_file',
                'exhibitions.opening_hours',
                'exhibitions.team',
                'exhibitions.date',
                'registration_visitors.name',
                'registration_visitors.company',
                'registration_visitors.job_title',
                'registration_visitors.city',
                'registration_visitors.country',
                'registration_visitors.email',
                'registration_visitors.barcode',
            )
            ->where('registration_visitors.id', $this->registration_id)->first();

        if (empty($registration_data)) {
            throw new RuntimeException('Registration data not found for confirmation email.');
        }
        if (trim((string) $registration_data->barcode) === '') {
            throw new RuntimeException('Registration barcode is missing for confirmation email.');
        }
        if (!view()->exists('Email.' . $registration_data->page)) {
            throw new RuntimeException('Registration email template is not available.');
        }

        // Generated only during delivery; no PNG is serialized in the initial queue payload.
        // BaconQrCode 2.x measures this margin in QR modules, not pixels.
        // Legacy implementation — retained for reference; inactive.
        // $qr_png = QrCode::format('png')
        //     ->size(300)
        //     ->margin(4)
        //     ->errorCorrection('M')
        //     ->generate((string) $registration_data->barcode);

        // Simple QrCode returns HtmlString under Laravel; MIME requires raw PNG bytes.
        $qr_png = (string) QrCode::format('png')
            ->size(300)
            ->margin(4)
            ->errorCorrection('H')
            ->generate((string) $registration_data->barcode);

        if (empty($qr_png)) {
            throw new RuntimeException('Failed to generate the registration email QR code.');
        }

        // Legacy implementation — retained for reference; inactive.
        // return $this->subject($registration_data->full_name . "(" . $registration_data->date . ")")
        //     ->from("no.reply@reg-gemindonesia.net", "GEM Indonesia")
        //     ->view('Email.' . $registration_data->page, [
        //         'data'              => $registration_data,
        //         'url'               => $this->url
        //     ]);

        return $this->subject($registration_data->full_name . "(" . $registration_data->date . ")")
            ->from("no.reply@reg-gemindonesia.net", "GEM Indonesia")
            ->view('Email.' . $registration_data->page, [
                'data'              => $registration_data,
                'url'               => $this->url,
                'qr_png'            => $qr_png,
            ]);
    }
}
