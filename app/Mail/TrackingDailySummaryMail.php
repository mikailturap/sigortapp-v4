<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrackingDailySummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $counts;

    public function __construct(array $counts)
    {
        $this->counts = $counts;
    }

    public function build()
    {
        return $this->subject('Günlük Poliçe Takip Özeti')
            ->view('emails.tracking-summary');
    }
}


