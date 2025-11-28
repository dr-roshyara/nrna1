<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VoterSlug;
use Carbon\Carbon;

class DebugVoterSlug extends Command
{
    protected $signature = 'debug:slug {slug : The voter slug to debug}';
    protected $description = 'Debug a voter slug to check its status and validity';

    public function handle()
    {
        $slugString = $this->argument('slug');

        $this->info("Debugging voter slug: {$slugString}");
        $this->line('');

        // Try to find the slug
        $vslug = VoterSlug::with('user')->where('slug', $slugString)->first();

        if (!$vslug) {
            $this->error('✗ Slug NOT FOUND in database');
            $this->line('This slug does not exist. It may have been deleted or never created.');
            return 1;
        }

        $this->info('✓ Slug found in database');
        $this->line('');

        // Display slug details
        $this->table(
            ['Property', 'Value', 'Status'],
            [
                ['ID', $vslug->id, ''],
                ['Slug', $vslug->slug, ''],
                ['User ID', $vslug->user_id, ''],
                ['User Name', $vslug->user->name ?? 'N/A', ''],
                ['User Email', $vslug->user->email ?? 'N/A', $vslug->user && $vslug->user->email && filter_var($vslug->user->email, FILTER_VALIDATE_EMAIL) ? '✓ Valid' : '✗ Invalid/Missing'],
                ['Is Active', $vslug->is_active ? 'Yes' : 'No', $vslug->is_active ? '✓' : '✗ INACTIVE'],
                ['Current Step', $vslug->current_step, ''],
                ['Created At', $vslug->created_at, ''],
                ['Expires At', $vslug->expires_at, $vslug->expires_at->isPast() ? '✗ EXPIRED' : '✓ Valid'],
                ['Time Until Expiry', $vslug->expires_at->diffForHumans(), ''],
                ['Last Accessed', $vslug->updated_at, ''],
            ]
        );

        $this->line('');

        // Check validation status
        $this->line('Validation Checks:');
        $checks = [
            'Slug exists' => true,
            'Is active' => $vslug->is_active,
            'Not expired' => !$vslug->expires_at->isPast(),
            'User exists' => $vslug->user !== null,
            'User can vote' => $vslug->user && $vslug->user->can_vote == 1,
            'User has valid email' => $vslug->user && $vslug->user->email && filter_var($vslug->user->email, FILTER_VALIDATE_EMAIL),
        ];

        foreach ($checks as $check => $passed) {
            $icon = $passed ? '✓' : '✗';
            $color = $passed ? 'info' : 'error';
            $this->$color("  {$icon} {$check}");
        }

        $this->line('');

        // Overall status
        $allPassed = array_reduce($checks, fn($carry, $item) => $carry && $item, true);

        if ($allPassed) {
            $this->info('✓ This slug should work correctly!');
            return 0;
        } else {
            $this->error('✗ This slug has issues that will prevent it from working.');
            $this->line('');
            $this->comment('Possible solutions:');

            if (!$vslug->is_active) {
                $this->line('  • Reactivate the slug using: UPDATE voter_slugs SET is_active = 1 WHERE slug = \'' . $slugString . '\';');
            }

            if ($vslug->expires_at->isPast()) {
                $newExpiry = now()->addHours(24);
                $this->line('  • Extend expiry using: UPDATE voter_slugs SET expires_at = \'' . $newExpiry . '\' WHERE slug = \'' . $slugString . '\';');
            }

            if ($vslug->user && !$vslug->user->can_vote) {
                $this->line('  • Enable voting for user: UPDATE users SET can_vote = 1 WHERE id = ' . $vslug->user_id . ';');
            }

            if (!($vslug->user && $vslug->user->email && filter_var($vslug->user->email, FILTER_VALIDATE_EMAIL))) {
                $this->line('  • Update user email: UPDATE users SET email = \'valid@email.com\' WHERE id = ' . $vslug->user_id . ';');
            }

            return 1;
        }
    }
}
