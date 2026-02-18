<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkflowActionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $body;

    public function __construct(string $subjectLine, string $body)
    {
        $this->subjectLine = $subjectLine;
        $this->body = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.workflow',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

