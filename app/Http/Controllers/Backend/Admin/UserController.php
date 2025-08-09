<?php
namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)
                    ->latest()
                    ->paginate(10);

        $roles = Role::whereIn('name', ['admin', 'carer'])->get();

        return view('backend.admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status'   => 'required|in:active,inactive',
            'role'     => 'required|in:admin,carer',
        ]);

        $user = new User();
        $user->name      = $request->name;
        $user->email     = $request->email;
        $user->password  = bcrypt($request->password);
        $user->status    = $request->status;
        $user->tenant_id = auth()->user()->tenant_id; // ✅ Set tenant_id from logged-in admin

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('backend.admin.users.index')
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,carer',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('backend.admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAccess($user);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    
   public function trashed()
    {
        $users = User::onlyTrashed()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(10);

        return view('backend.admin.users.trashed', compact('users'));
    }
    
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('backend.admin.users.trashed')->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('backend.admin.users.trashed')->with('success', 'User permanently deleted.');
    }


    private function authorizeAccess(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized.');
        }
    }
}

