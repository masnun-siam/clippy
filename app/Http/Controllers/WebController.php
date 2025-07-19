<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Services\ClipService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function __construct(protected ClipService $clipService)
    {
    }

    /**
     * Show the dashboard with user's clips
     */
    public function dashboard(): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $clips = Clip::latest()->get();

        // Calculate stats
        $stats = [
            'total' => $clips->count(),
            'active' => $clips->filter(function ($clip) {
                return is_null($clip->expires_at) || $clip->expires_at > now();
            })->count(),
            'protected' => $clips->whereNotNull('password')->count(),
            'expired' => $clips->filter(function ($clip) {
                return !is_null($clip->expires_at) && $clip->expires_at < now();
            })->count(),
        ];

        return view('dashboard', compact('clips', 'stats'));
    }

    /**
     * Show the login form
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Show the register form
     */
    public function showRegister(): View|RedirectResponse
    {
        if (!config('app.registration_enabled')) {
            abort(404);
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle registration
     */
    public function register(Request $request): RedirectResponse
    {
        if (!config('app.registration_enabled')) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to Clippy! Your account has been created successfully.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show create clip form
     */
    public function showCreateClip(): View
    {
        return view('clips.create');
    }

    /**
     * Handle clip creation
     */
    public function createClip(Request $request): RedirectResponse
    {
        $request->validate([
            'url' => 'required|url',
            'password' => 'nullable|string|min:4',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            $this->clipService->saveClip($request);
            return redirect()->route('dashboard')->with('success', 'Short URL created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create short URL. Please try again.');
        }
    }

    /**
     * Show edit clip form
     */
    public function showEditClip(int $id): View
    {
        $clip = Clip::findOrFail($id);
        return view('clips.edit', compact('clip'));
    }

    /**
     * Handle clip update
     */
    public function updateClip(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'url' => 'required|url',
            'password' => 'nullable|string|min:4',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            $this->clipService->saveClip($request, $id);
            return redirect()->route('dashboard')->with('success', 'Short URL updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update short URL. Please try again.');
        }
    }

    /**
     * Handle clip deletion
     */
    public function deleteClip(int $id): RedirectResponse
    {
        try {
            $clip = Clip::findOrFail($id);
            $clip->delete();
            return redirect()->route('dashboard')->with('success', 'Short URL deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete short URL. Please try again.');
        }
    }
}
