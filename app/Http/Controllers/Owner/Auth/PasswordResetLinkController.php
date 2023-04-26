<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\Owner;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('owner.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        
        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );;
        
        // Validate the email address first
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        
        // Send the test email after the validation has passed
        Mail::to($request->email)
        ->send(new TestMail());
        
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
