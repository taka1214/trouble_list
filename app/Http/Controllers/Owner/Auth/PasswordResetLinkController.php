<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Generate a new token
        $token = Str::random(60);

        // Delete any existing tokens for the email
        DB::table('owner_password_resets')->where('email', $request->email)->delete();

        // Insert the new token for the email
        DB::table('owner_password_resets')->insert([
            'email' => $request->email,
            'token' => $token, // Use the plain token instead of Hash::make($token)
            'created_at' => Carbon::now(),
        ]);

        // Send the custom OwnerPasswordMail with the plain token
        Mail::to($request->email)->send(new OwnerPasswordMail($token));

        // Return the success status
        return back()->with('status', __('passwords.sent'));
    }
}
