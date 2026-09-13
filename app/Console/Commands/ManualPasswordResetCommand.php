<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Operational fallback for when outbound mail is not working: gives an admin
 * a way to get a working password reset to a user without email.
 *
 * Two modes:
 *  - default: print a real, valid password-reset link (same token mechanism
 *    the broken email would have sent) — the user still picks their own
 *    password, nothing sensitive needs to be read aloud/typed by an admin.
 *  - --set-password / --random-password: skip the link entirely and set the
 *    account's password directly, for a voter who can't click a link.
 */
class ManualPasswordResetCommand extends Command
{
    protected $signature = 'user:reset-link
        {email : The account\'s email address}
        {--set-password= : Set this exact password directly instead of printing a reset link}
        {--random-password : Set a random password directly instead of printing a reset link, and print the generated password}';

    protected $description = 'Manually issue a password-reset link, or set a password directly, when outbound mail is not working';

    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email \"{$email}\".");
            return 1;
        }

        $setPassword = $this->option('set-password');
        $randomPassword = $this->option('random-password');

        if ($setPassword || $randomPassword) {
            $newPassword = $setPassword ?: Str::password(12);

            $user->forceFill(['password' => Hash::make($newPassword)])->save();

            $this->info("Password set directly for {$user->name} ({$email}).");
            $this->info("New password: {$newPassword}");
            $this->comment('Give this to the voter directly (phone call, in person, etc.) and suggest they change it after logging in.');

            return 0;
        }

        $token = Password::broker()->createToken($user);
        $link = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        $this->info("Reset link for {$user->name} ({$email}):");
        $this->line($link);
        $this->comment('Send this link to the voter directly (WhatsApp, SMS, in person, etc.) — it works exactly like the one the broken email would have sent, and expires the same way.');

        return 0;
    }
}
