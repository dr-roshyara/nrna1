<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * PBDIGIT-274 S2: the exact shape of PostgreSQL unique violations that the S3 issuer will classify
 * (plan section 5): Laravel's UniqueConstraintViolationException, SQLSTATE 23505, and the violated rule's
 * name in the driver message (getPrevious()->errorInfo[2]).
 *
 * WRITTEN, NOT RUN: isolated database only (phpunit.pbdigit274.xml), after the PO authorises it.
 */
final class PgUniqueViolationShapeTest extends IsolatedDatabaseTestCase
{
    private function violation(callable $write): UniqueConstraintViolationException
    {
        try {
            DB::transaction(fn () => $write());   // savepoint: the test transaction stays usable
        } catch (UniqueConstraintViolationException $e) {
            return $e;
        }
        $this->fail('expected a unique violation');
    }

    /** @return array{Election, User} */
    private function voterInElection(): array
    {
        $org = Organisation::factory()->tenant()->create();

        return [Election::factory()->forOrganisation($org)->real()->create(), User::factory()->create()];
    }

    private function slug(Election $election, User $voter, array $overrides = []): VoterSlug
    {
        return VoterSlug::factory()->create($overrides + [
            'user_id' => $voter->id, 'organisation_id' => $election->organisation_id, 'election_id' => $election->id,
        ]);
    }

    public function test_a_duplicate_slug_string_names_the_slug_constraint(): void
    {
        [$election, $voter] = $this->voterInElection();
        $existing = $this->slug($election, $voter);
        [$otherElection, $otherVoter] = $this->voterInElection();

        $e = $this->violation(fn () => $this->slug($otherElection, $otherVoter, ['slug' => $existing->slug]));

        $this->assertSame('23505', $e->getPrevious()->getCode());
        $this->assertStringContainsString('voter_slugs_slug_unique', $e->getPrevious()->errorInfo[2]);
    }

    public function test_a_second_current_slug_names_the_active_index(): void
    {
        [$election, $voter] = $this->voterInElection();
        $this->slug($election, $voter);

        $e = $this->violation(fn () => $this->slug($election, $voter));

        $this->assertSame('23505', $e->getPrevious()->getCode());
        $this->assertStringContainsString('voter_slugs_active_election_user_unique', $e->getPrevious()->errorInfo[2]);
        $this->assertStringNotContainsString('voter_slugs_slug_unique', $e->getPrevious()->errorInfo[2]);
    }
}
