<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketResponded extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly SupportTicket $ticket,
        public readonly string $adminResponse,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'תגובה לפנייה שלך: ' . $this->ticket->subject . ' [' . $this->ticket->reference_code . ']',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.support-ticket-responded',
            with: [
                'ticket' => $this->ticket,
                'adminResponse' => $this->adminResponse,
                'supportUrl' => route('dashboard.support'),
            ],
        );
    }
}
