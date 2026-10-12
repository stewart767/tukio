<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Host;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Build phone normalization variants if numeric/phone characters are present
        $cleanedDigits = preg_replace('/[^0-9]/', '', $loginInput);
        $phoneVariants = [$loginInput];
        if (!empty($cleanedDigits)) {
            $phoneVariants[] = '+' . $cleanedDigits;
            $phoneVariants[] = $cleanedDigits;
            if (str_starts_with($cleanedDigits, '255') && strlen($cleanedDigits) >= 12) {
                $phoneVariants[] = '0' . substr($cleanedDigits, 3);
            } elseif (str_starts_with($cleanedDigits, '0') && strlen($cleanedDigits) >= 10) {
                $phoneVariants[] = '+255' . substr($cleanedDigits, 1);
                $phoneVariants[] = '255' . substr($cleanedDigits, 1);
            }
        }
        $phoneVariants = array_unique($phoneVariants);

        // 1. Attempt admin/staff User login
        $user = User::where('email', $loginInput)
            ->orWhereIn('phone', $phoneVariants)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            if (!$user->is_active) {
                return back()->withErrors([
                    'email' => 'Your account is currently inactive. Please contact support for assistance.',
                ])->onlyInput('email');
            }

            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 2. Attempt Host / Client login
        $host = Host::where('email', $loginInput)
            ->orWhereIn('phone', $phoneVariants)
            ->first();

        if ($host && Hash::check($password, $host->password)) {
            if ($host->hasFinishedEvent()) {
                return back()->withErrors([
                    'email' => 'This event has already been completed. Please create a new host account to start a new event.',
                ])->onlyInput('email');
            }

            if (!$host->is_active) {
                return back()->withErrors([
                    'email' => 'Your account is currently inactive. Please contact support for assistance.',
                ])->onlyInput('email');
            }

            Auth::guard('host')->login($host, $remember);
            $request->session()->regenerate();

            if ($host->hasConfiguredEvent()) {
                return redirect()->intended(route('host.dashboard'));
            }

            return redirect()->intended(route('host.event-config'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'event_admin', // Default role for new registrations
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /**
     * Show the superadmin login form.
     */
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle superadmin login request.
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // First check if user exists and is superadmin
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Check if user is superadmin before attempting authentication
        if (!$user->isSuperAdmin()) {
            return back()->withErrors([
                'email' => 'Access denied. This login is for superadmin only.',
            ])->onlyInput('email');
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is currently inactive. Please contact support for assistance.',
            ])->onlyInput('email');
        }

        // Attempt authentication with email and password only
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
