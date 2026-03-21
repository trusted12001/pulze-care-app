<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $this->scoped($user, $assignment);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Assignment $a): bool
    {
        if ((int) $user->id === (int) $a->assigned_to || (int) $user->id === (int) $a->created_by) {
            return true;
        }

        if ($user->can('assignments.update') && $this->scoped($user, $a)) {
            return true;
        }

        return false;
    }

    public function verify(User $user, Assignment $a): bool
    {
        if ((int) $user->tenant_id !== (int) $a->tenant_id) {
            return false;
        }

        return $user->hasRole('admin');
    }

    public function delete(User $user, Assignment $a): bool
    {
        if (in_array($a->status, ['closed', 'verified'])) {
            return false;
        }

        if ((int) $user->tenant_id !== (int) $a->tenant_id) {
            return false;
        }

        return (int) $user->id === (int) $a->created_by
            || (int) $user->id === (int) $a->assigned_to
            || $user->hasRole('admin');
    }

    private function scoped(User $user, Assignment $a): bool
    {
        if ((int) $user->tenant_id !== (int) $a->tenant_id) {
            return false;
        }

        return ($user->location_id !== null && (int) $user->location_id === (int) $a->location_id)
            || ((int) $user->id === (int) $a->assigned_to)
            || ((int) $user->id === (int) $a->created_by);
    }
}
