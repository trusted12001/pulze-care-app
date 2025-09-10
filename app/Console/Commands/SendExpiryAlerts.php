<?php

namespace App\Console\Commands;

use App\Models\ExpiryAlert;
use App\Models\StaffEmploymentCheck;
use App\Models\StaffProfile;
use App\Models\StaffRegistration;
use App\Models\StaffVisa;
use App\Notifications\ExpiryAlertNotification;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SendExpiryAlerts extends Command
{
    protected $signature = 'alerts:expiries {--days=90,60,30} {--tz=Europe/London}';
    protected $description = 'Send 90/60/30-day expiry alerts for visas, registrations, revalidations, and employment checks';

    public function handle(): int
    {
        $tz = $this->option('tz') ?: 'Europe/London';
        $windows = collect(explode(',', (string)$this->option('days')))
            ->filter()->map(fn($d) => (int)trim($d))->sort()->values()->all();

        $today = CarbonImmutable::now($tz)->startOfDay();

        foreach ($windows as $days) {
            $target = $today->addDays($days)->toDateString();
            $this->info("Processing window {$days} days (target: {$target})");

            DB::transaction(function () use ($target, $days) {
                $this->processVisas($target, $days);
                $this->processRegistrations($target, $days);
                $this->processRevalidations($target, $days);
                $this->processEmploymentChecks($target, $days);
            });
        }

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function recipients(StaffProfile $profile): array
    {
        // Notify the staff member and their line manager if available.
        $recipients = [];
        if ($profile->relationLoaded('user')) {
            if ($profile->user) $recipients[] = $profile->user;
        } else {
            $profile->load('user');
            if ($profile->user) $recipients[] = $profile->user;
        }
        if ($profile->relationLoaded('lineManager')) {
            if ($profile->lineManager) $recipients[] = $profile->lineManager;
        } else {
            $profile->load('lineManager');
            if ($profile->lineManager) $recipients[] = $profile->lineManager;
        }
        return $recipients;
    }

    private function alreadySent(string $type, int $id, int $window): bool
    {
        return ExpiryAlert::where('resource_type', $type)
            ->where('resource_id', $id)
            ->where('window_days', $window)
            ->exists();
    }

    private function logSent(StaffProfile $profile, string $type, int $id, int $window, string $alertDate): void
    {
        ExpiryAlert::firstOrCreate(
            [
                'resource_type'   => $type,
                'resource_id'     => $id,
                'window_days'     => $window,
            ],
            [
                'tenant_id'       => $profile->tenant_id,
                'staff_profile_id'=> $profile->id,
                'alert_date'      => $alertDate,
            ]
        );
    }

    private function buildContext(StaffProfile $profile, string $label, string $dateStr, string $resourceType): array
    {
        // Build route URL for quick access
        $url = match ($resourceType) {
            'visa' => route('backend.admin.staff-profiles.visas.index', $profile),
            'registration', 'registration_revalidation' => route('backend.admin.staff-profiles.registrations.index', $profile),
            'employment_check' => route('backend.admin.staff-profiles.employment-checks.index', $profile),
            default => route('backend.admin.staff-profiles.show', $profile),
        };

        $staffName = optional($profile->user)->full_name
            ?? trim(($profile->user->first_name ?? '') . ' ' . ($profile->user->last_name ?? ''))
            ?: 'Staff Member';

        $tenantName = optional($profile->user?->tenant)->name ?? config('app.name');

        return [
            'staff_name'  => $staffName,
            'item_label'  => $label,
            'expires_on'  => CarbonImmutable::parse($dateStr)->format('d M Y'),
            'tenant_name' => $tenantName,
            'url'         => $url,
        ];
    }

    private function notify(StaffProfile $profile, string $resourceType, int $days, array $ctx, int $resourceId, string $alertDate): void
    {
        if ($this->alreadySent($resourceType, $resourceId, $days)) {
            return;
        }

        $recipients = $this->recipients($profile);
        if (empty($recipients)) {
            // No one to notify; still log to avoid repeated attempts
            $this->logSent($profile, $resourceType, $resourceId, $days, $alertDate);
            return;
        }

        Notification::send($recipients, new ExpiryAlertNotification($resourceType, $days, $ctx));
        $this->logSent($profile, $resourceType, $resourceId, $days, $alertDate);
    }

    private function processVisas(string $target, int $days): void
    {
        StaffVisa::query()
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', $target)
            ->with('staffProfile.user', 'staffProfile.lineManager')
            ->chunkById(500, function ($visas) use ($days, $target) {
                foreach ($visas as $visa) {
                    $profile = $visa->staffProfile;
                    if (!$profile) continue;

                    $label = 'Visa / Right-to-Work';
                    $ctx = $this->buildContext($profile, $label, $visa->expires_at->toDateString(), 'visa');
                    $this->notify($profile, 'visa', $days, $ctx, $visa->id, $visa->expires_at->toDateString());
                }
            });
    }

    private function processRegistrations(string $target, int $days): void
    {
        StaffRegistration::query()
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', $target)
            ->with('staffProfile.user', 'staffProfile.lineManager')
            ->chunkById(500, function ($regs) use ($days, $target) {
                foreach ($regs as $r) {
                    $profile = $r->staffProfile;
                    if (!$profile) continue;

                    $label = "{$r->body} Registration";
                    $ctx = $this->buildContext($profile, $label, $r->expires_at->toDateString(), 'registration');
                    $this->notify($profile, 'registration', $days, $ctx, $r->id, $r->expires_at->toDateString());
                }
            });
    }

    private function processRevalidations(string $target, int $days): void
    {
        StaffRegistration::query()
            ->whereNotNull('revalidation_due_at')
            ->whereDate('revalidation_due_at', $target)
            ->with('staffProfile.user', 'staffProfile.lineManager')
            ->chunkById(500, function ($regs) use ($days, $target) {
                foreach ($regs as $r) {
                    $profile = $r->staffProfile;
                    if (!$profile) continue;

                    $label = "{$r->body} Revalidation";
                    $ctx = $this->buildContext($profile, $label, $r->revalidation_due_at->toDateString(), 'registration_revalidation');
                    $this->notify($profile, 'registration_revalidation', $days, $ctx, $r->id, $r->revalidation_due_at->toDateString());
                }
            });
    }

    private function processEmploymentChecks(string $target, int $days): void
    {
        StaffEmploymentCheck::query()
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', $target)
            ->with('staffProfile.user', 'staffProfile.lineManager')
            ->chunkById(500, function ($checks) use ($days, $target) {
                foreach ($checks as $c) {
                    $profile = $c->staffProfile;
                    if (!$profile) continue;

                    $label = str(\Str::headline(str_replace('_',' ', $c->check_type)))->value();
                    $ctx = $this->buildContext($profile, $label, $c->expires_at->toDateString(), 'employment_check');
                    $this->notify($profile, 'employment_check', $days, $ctx, $c->id, $c->expires_at->toDateString());
                }
            });
    }
}
