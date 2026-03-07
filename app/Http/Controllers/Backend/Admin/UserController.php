<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeAccess(User $user): void
    {
        $this->authorizeTenantRecord($user);
    }

    public function index()
    {
        $tenantId = $this->tenantIdOrFail();

        $users = User::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(10);

        $roles = Role::whereIn('name', ['admin', 'carer'])->get();

        return view('backend.admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $tenantId = $this->tenantIdOrFail();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,carer',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = $request->status;
        $user->tenant_id = $tenantId;

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()
            ->route('backend.admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->authorizeAccess($user);

        return view('backend.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeAccess($user);

        $roles = Role::whereIn('name', ['admin', 'carer'])->get();

        return view('backend.admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAccess($user);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,carer',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()
            ->route('backend.admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAccess($user);

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function trashed()
    {
        $tenantId = $this->tenantIdOrFail();

        $users = User::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(10);

        return view('backend.admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorizeAccess($user);

        $user->restore();

        return redirect()
            ->route('backend.admin.users.trashed')
            ->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $this->authorizeAccess($user);

        $user->forceDelete();

        return redirect()
            ->route('backend.admin.users.trashed')
            ->with('success', 'User permanently deleted.');
    }
}
