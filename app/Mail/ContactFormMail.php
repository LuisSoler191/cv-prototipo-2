<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $fromName;
    public string $fromEmail;
    public string $mailSubject;
    public string $body;

    public function __construct(string $fromName, string $fromEmail, string $mailSubject, string $body)
    {
        $this->fromName    = $fromName;
        $this->fromEmail   = $fromEmail;
        $this->mailSubject = $mailSubject;
        $this->body        = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[luissoler.dev] {$this->mailSubject}",
            replyTo: [$this->fromEmail],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'fromName'    => $this->fromName,
                'fromEmail'   => $this->fromEmail,
                'mailSubject' => $this->mailSubject,
                'body'        => $this->body,
            ],
        );
    }
}
