<?php

namespace App\Http\Controllers\Concerns;

trait ResolvesTenantContext
{
    protected function currentUser()
    {
        return auth()->user();
    }

    protected function isSuperAdmin(): bool
    {
        $user = $this->currentUser();

        return $user && method_exists($user, 'hasRole') && $user->hasRole('super-admin');
    }

    protected function effectiveTenantId(): ?int
    {
        $user = $this->currentUser();

        if (!$user) {
            return null;
        }

        if ($this->isSuperAdmin()) {
            return session('active_tenant_id');
        }

        return $user->tenant_id ? (int) $user->tenant_id : null;
    }

    protected function hasTenantContext(): bool
    {
        return !is_null($this->effectiveTenantId());
    }

    protected function tenantIdOrFail(): int
    {
        $tenantId = $this->effectiveTenantId();

        abort_if(is_null($tenantId), 403, 'No tenant context selected for this session.');

        return (int) $tenantId;
    }

    protected function authorizeTenantRecord(object $record, string $tenantField = 'tenant_id'): void
    {
        $tenantId = $this->tenantIdOrFail();

        abort_unless(
            isset($record->{$tenantField}) && (int) $record->{$tenantField} === $tenantId,
            403,
            'You are not authorized to access this tenant record.'
        );
    }
}
