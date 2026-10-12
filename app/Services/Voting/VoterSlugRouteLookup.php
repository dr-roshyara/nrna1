<?php

namespace App\Services\Voting;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Resolves a {vslug} route parameter (PBDIGIT-274, plan section 7, L-13).
 *
 * - Model events are suppressed for this one lookup query only, so the VoterSlug / DemoVoterSlug `retrieved`
 *   hook cannot write expiry state before VerifyVoterSlug has checked ownership. Laravel restores the
 *   dispatcher in a `finally` block, also after an exception.
 * - Only the 'tenant' global scope is removed (a voting link resolves regardless of the session's tenant;
 *   ownership and organisation are enforced afterwards by VerifyVoterSlug V-3 / V-6). SoftDeletes stays, so a
 *   replaced or soft-deleted slug is not found, exactly like an unknown slug.
 * - No expiry is evaluated here; that is the guard's job, after ownership.
 */
class VoterSlugRouteLookup
{
    /** @param class-string<Model> $modelClass */
    public function find(string $modelClass, string $slug): ?Model
    {
        return $modelClass::withoutEvents(fn () => $this->fetch($modelClass, $slug));
    }

    /** @param class-string<Model> $modelClass */
    protected function fetch(string $modelClass, string $slug): ?Model
    {
        return $this->query($modelClass, $slug)->first();
    }

    /** @param class-string<Model> $modelClass */
    protected function query(string $modelClass, string $slug): Builder
    {
        return $modelClass::on(config('database.default'))
            ->withoutGlobalScope('tenant')
            ->where('slug', $slug);
    }
}
