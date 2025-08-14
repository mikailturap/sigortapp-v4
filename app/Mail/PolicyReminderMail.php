<?php

namespace App\Mail;

use App\Models\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PolicyReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $policy;

    /**
     * Create a new message instance.
     */
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Poliçe Hatırlatıcısı: ' . $this->policy->policy_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.policy-reminder',
            with: [
                'policy' => $this->policy,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
