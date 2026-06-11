<?php

namespace App\Notifications;

use App\Models\RotaPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RotaPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public RotaPeriod $rotaPeriod
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Shift Assignment',

            'message' => sprintf(
                'A rota has been published for %s.',
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
}
