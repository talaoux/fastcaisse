<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.Login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Veuillez entrer un email valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Store login time for cashiers in cache (shared across sessions)
            if ($user->role === 'cashier') {
                // Always update the cache with current login time
                $loginTime = now();
                \Cache::put('cashier_login_time', $loginTime, now()->addHours(24));
                \Cache::put('cashier_session_active', true, now()->addHours(24));
                // Store in session for cashier's own view
                $request->session()->put('cashier_login_time', $loginTime);
            }

            // Rediriger selon le rôle
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Bienvenue Admin!');
            } elseif ($user->role === 'cashier') {
                return redirect()->route('cashier.dashboard')->with('success', 'Bienvenue Caissier!');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Clear cashier login time from cache when cashier logs out
        if ($user && $user->role === 'cashier') {
            \Cache::forget('cashier_login_time');
            \Cache::forget('cashier_session_active');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté.');
    }
}
