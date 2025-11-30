<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Optional: global override for Super Admin.
     * Adjust role names to match your own if needed.
     */
    public function before(User $user, string $ability): ?bool
    {
        // If you don't have hasRole or don't use "Super Admin", remove this block.
        if (method_exists($user, 'hasRole') && $user->hasRole('Super Admin')) {
            return true;
        }

        return null; // Let other methods decide
    }

    public function view(User $user, Assignment $assignment): bool
    {
        // Anyone in the same scope (or assignee/creator) can view
        return $this->scoped($user, $assignment);
    }

    public function create(User $user): bool
    {
        // For now: any authenticated user can create.
        // You can tighten later with $user->can('assignments.create')
        return true;
    }

    public function update(User $user, Assignment $a): bool
    {
        // ✅ Primary rule:
        // Assignee OR creator can always update (start/submit, edit fields, etc.)
        if ((int) $user->id === (int) $a->assigned_to || (int) $user->id === (int) $a->created_by) {
            return true;
        }

        // ✅ Fallback rule:
        // Users with explicit permission + within scope
        if ($user->can('assignments.update') && $this->scoped($user, $a)) {
            return true;
        }

        return false;
    }

    public function verify(User $user, Assignment $a): bool
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('admin')) {
            return true; // admins can do everything, including verify own assignments
        }

        return null;
    }

    public function delete(User $user, Assignment $a): bool
    {
        // Allow creators, assignees, and admins to delete if it's not closed
        if (in_array($a->status, ['closed', 'verified'])) {
            return false;
        }

        return $user->id === $a->created_by
            || $user->id === $a->assigned_to
            || $user->hasRole('Super Admin')
            || $user->hasRole('admin');
    }

    /**
     * Shared scoping logic – who is "connected" to this assignment.
     */
    private function scoped(User $user, Assignment $a): bool
    {
        return
            // same location
            ($user->location_id !== null && $user->location_id === $a->location_id)
            ||
            // is assignee
            ((int) $user->id === (int) $a->assigned_to)
            ||
            // is creator
            ((int) $user->id === (int) $a->created_by);
    }
}
