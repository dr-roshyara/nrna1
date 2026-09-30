<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserEmail extends Command
{
    protected $signature = 'email:verify
        {identifier : User ID (UUID) or email address}
        {--force : Re-stamp email_verified_at even if already verified}';

    protected $description = 'Mark a user\'s email address as verified (admin override, no email link needed)';

    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $user = str_contains($identifier, '@')
            ? User::where('email', $identifier)->first()
            : User::find($identifier);

        if (! $user) {
            $this->error("No user found matching \"{$identifier}\".");
            return self::FAILURE;
        }

        $this->info("User: {$user->name} <{$user->email}> ({$user->id})");

        if ($user->hasVerifiedEmail() && ! $this->option('force')) {
            $this->line("Already verified at {$user->email_verified_at}.");
            $this->line('Pass --force to re-stamp.');
            return self::SUCCESS;
        }

        $user->markEmailAsVerified();

        $this->info("✓ Email verified at {$user->fresh()->email_verified_at}");

        return self::SUCCESS;
    }
}
