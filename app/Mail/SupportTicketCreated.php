<?php

namespace App\Mail;

use App\Models\Site;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly SupportTicket $ticket,
        public readonly User $user,
        public readonly Site $site,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'פנייה חדשה: ' . $this->ticket->subject . ' [' . $this->ticket->reference_code . ']',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.support-ticket-created',
            with: [
                'ticket' => $this->ticket,
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'siteName' => $this->site->site_name,
                'siteDomain' => $this->site->domain,
                'adminUrl' => route('dashboard.super-admin'),
            ],
        );
    }
}
