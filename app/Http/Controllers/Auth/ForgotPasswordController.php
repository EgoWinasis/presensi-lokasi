<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;


    
class ForgotPasswordController extends Controller
{

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // Adjust if using adminlte
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();

        dd($user);
        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->isActive == 0) {
            return back()->withErrors(['email' => 'Akun ini tidak aktif.']);
        }

        // Use Laravel's default reset password link logic
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    use SendsPasswordResetEmails;
}

