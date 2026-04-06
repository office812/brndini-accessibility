<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceLeadReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $leadData,
        public readonly string $source = 'dashboard',
    ) {}

    public function envelope(): Envelope
    {
        $serviceLabel = $this->leadData['service_type'] ?? 'שירות לא ידוע';

        return new Envelope(
            subject: 'ליד חדש: ' . $serviceLabel . ' — ' . ($this->leadData['name'] ?? $this->leadData['user_name'] ?? 'לקוח'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.service-lead-received',
            with: [
                'lead' => $this->leadData,
                'source' => $this->source,
                'adminUrl' => route('dashboard.super-admin', ['tab' => 'leads']),
            ],
        );
    }
}
