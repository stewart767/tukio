<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:super_admin,event_admin,staff',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        SecurityAuditLog::record(
            'admin_created_user',
            [
                'created_user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ],
            auth()->id()
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:super_admin,event_admin,staff',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        // Prevent superadmin from changing their own role or deactivating themselves
        if ($user->id === auth()->id() && $user->isSuperAdmin()) {
            if ($request->role !== 'super_admin') {
                return back()->withErrors([
                    'role' => 'You cannot change your own role from super_admin.',
                ])->withInput();
            }
            
            if ($request->has('is_active') && !$request->boolean('is_active')) {
                return back()->withErrors([
                    'is_active' => 'You cannot deactivate your own account.',
                ])->withInput();
            }
        }

        $user->update($request->only(['name', 'email', 'role', 'phone', 'is_active']));

        SecurityAuditLog::record(
            'admin_updated_user',
            [
                'updated_user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
            auth()->id()
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent superadmin from deleting themselves
        if ($user->id === auth()->id() && $user->isSuperAdmin()) {
            return back()->withErrors([
                'user' => 'You cannot delete your own account.',
            ]);
        }

        $deletedDetails = [
            'deleted_user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $user->delete();

        SecurityAuditLog::record('admin_deleted_user', $deletedDetails, auth()->id());

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
