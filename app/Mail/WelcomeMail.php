<?php

namespace App\Mail;

use App\Models\Site;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Site $site,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ברוכים הבאים ל-A11Y Bridge — קוד ההטמעה שלך מוכן',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome',
            with: [
                'userName' => $this->user->name,
                'siteName' => $this->site->site_name,
                'siteKey' => $this->site->public_key,
                'dashboardUrl' => route('dashboard', ['site' => $this->site->id]),
            ],
        );
    }
}
