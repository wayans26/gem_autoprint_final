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

class email_registration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $registration_id;
    public $url;
    public function __construct($registration_id, $url)
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
            )
            ->where('registration_visitors.id', $this->registration_id)->first();

        return $this->subject($registration_data->full_name . "(" . $registration_data->date . ")")
            ->from("no.reply@reg-gemindonesia.net", "GEM Indonesia")
            ->view('Email.' . $registration_data->page, [
                'data'              => $registration_data,
                'url'               => $this->url
            ]);
    }
}
