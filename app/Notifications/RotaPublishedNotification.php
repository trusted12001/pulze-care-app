<?php

namespace App\Notifications;

use App\Models\RotaPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;


class RotaPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public RotaPeriod $rotaPeriod
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Rota Published',

            'message' => sprintf(
                'Your rota is now available for viewing at %s.',
                $this->rotaPeriod->location?->name ?? 'your location'
            ),

            'type' => 'rota',

            'rota_period_id' => $this->rotaPeriod->id,

            'location_name' => $this->rotaPeriod->location?->name,

            'start_date' => $this->rotaPeriod->start_date?->format('d M Y'),

            'end_date' => $this->rotaPeriod->end_date?->format('d M Y'),
            'url' => route(
                'frontend.rota.show',
                $this->rotaPeriod
            ),


        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tenant = $this->rotaPeriod
            ->location
            ->tenant;

        $settings = $tenant?->settings;

        return (new MailMessage)
            ->subject(
                'New Rota Published – '
                    . $this->rotaPeriod->location->name
            )
            ->view(
                'emails.rota-published',
                [
                    'user'       => $notifiable,
                    'rotaPeriod' => $this->rotaPeriod,
                    'tenant'     => $tenant,
                    'settings'   => $settings,
                ]
            );
    }
}
