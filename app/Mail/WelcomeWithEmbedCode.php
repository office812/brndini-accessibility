<?php

namespace App\Mail;

use App\Models\Site;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeWithEmbedCode extends Mailable
{
    use Queueable, SerializesModels;

    public string $embedCode;
    public string $installUrl;

    public function __construct(
        public User $user,
        public Site $site,
    ) {
        $this->embedCode = sprintf(
            '<script async src="%s" data-a11y-bridge="%s"></script>',
            url('/widget.js'),
            $site->public_key
        );
        $this->installUrl = route('dashboard.install', ['site' => $site->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'קוד ההטמעה שלך מוכן — A11Y Bridge',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}
