<?php

namespace App\Mail;

use App\Models\RotaPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RotaPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RotaPeriod $rotaPeriod,
        public $user
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Rota Published – ' . $this->rotaPeriod->location->name,
        );
    }

    public function content(): Content
    {
        $rotaPeriod = \App\Models\RotaPeriod::with(
            'location.tenant.settings'
        )->findOrFail($this->rotaPeriod->id);

        $tenant = $rotaPeriod->location->tenant;
        $settings = $tenant?->settings;

        return new Content(
            view: 'emails.rota-published',
            with: [
                'user'       => $this->user,
                'rotaPeriod' => $rotaPeriod,
                'tenant'     => $tenant,
                'settings'   => $settings,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
