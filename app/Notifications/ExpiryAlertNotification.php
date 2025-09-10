<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiryAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $resourceType,      // visa|registration|registration_revalidation|employment_check
        public int $daysRemaining,        // 90|60|30
        public array $context             // ['staff_name','item_label','expires_on','tenant_name','url']
    ) {}

    public function via($notifiable): array
    {
        // Always store in DB; send email too (configure MAIL_* env to actually deliver)
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = match ($this->resourceType) {
            'visa' => "Visa / RTW expires in {$this->daysRemaining} days",
            'registration' => "Registration expires in {$this->daysRemaining} days",
            'registration_revalidation' => "Registration revalidation due in {$this->daysRemaining} days",
            'employment_check' => "Employment check expires in {$this->daysRemaining} days",
            default => "Compliance due in {$this->daysRemaining} days",
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Heads up 👋')
            ->line("**{$this->context['staff_name']}** — **{$this->context['item_label']}**")
            ->line("Due on **{$this->context['expires_on']}** ({$this->daysRemaining} days left).")
            ->action('Review now', $this->context['url'])
            ->line("Tenant: {$this->context['tenant_name']}");
    }

    public function toArray($notifiable): array
    {
        return [
            'resourceType' => $this->resourceType,
            'daysRemaining' => $this->daysRemaining,
            'context' => $this->context,
        ];
    }
}
