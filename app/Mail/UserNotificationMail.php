<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $headline;
    public string $bodyMessage;

    public function __construct(string $subjectText, string $headline, string $bodyMessage)
    {
        $this->subjectText = $subjectText;
        $this->headline = $headline;
        $this->bodyMessage = $bodyMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-notification',
            with: [
                'subjectText' => $this->subjectText,
                'headline' => $this->headline,
                'bodyMessage' => $this->bodyMessage,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
