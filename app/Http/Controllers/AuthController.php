<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Host;
use App\Services\Security\AuthenticationGuardService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationGuardService $authGuard
    ) {}
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

        // Check if account / IP is currently rate-limited by AuthenticationGuard
        $this->authGuard->ensureNotRateLimited($request, $loginInput);

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

            $this->authGuard->clearFailedAttempts($request, $loginInput, $user->id);

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

            $this->authGuard->clearFailedAttempts($request, $loginInput, $host->id);

            Auth::guard('host')->login($host, $remember);
            $request->session()->regenerate();

            if ($host->hasConfiguredEvent()) {
                return redirect()->intended(route('host.dashboard'));
            }

            return redirect()->intended(route('host.event-config'));
        }

        // Record failed attempt and evaluate brute force / progressive delays
        $this->authGuard->recordFailedAttempt($request, $loginInput, 'web');

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
            'password' => ['required', 'confirmed', Password::defaults()],
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

        $this->authGuard->ensureNotRateLimited($request, $request->email);

        $genericError = 'The provided credentials do not match our records.';

        $user = User::where('email', $request->email)->first();

        // Constant-time password check if user exists; dummy check if not to mitigate timing attacks
        $dummyHash = '$2y$10$abcdefghijklmnopqrstuuABCDEFGHIJKLMNOPQRSTUVWXYZ012345';
        $passwordMatches = $user ? Hash::check($request->password, $user->password) : Hash::check($request->password, $dummyHash);

        if (!$user || !$passwordMatches || !$user->isSuperAdmin() || !$user->is_active) {
            $this->authGuard->recordFailedAttempt($request, $request->email, 'admin');

            return back()->withErrors([
                'email' => $genericError,
            ])->onlyInput('email');
        }

        $this->authGuard->clearFailedAttempts($request, $request->email, $user->id);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
