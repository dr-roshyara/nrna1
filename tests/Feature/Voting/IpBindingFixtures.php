<?php

namespace Tests\Feature\Voting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-247 fixtures — IP Binding. Synthetic data only.
 *
 * The binding switch and the captured IP are written with the query builder (test-only setup): the
 * PBDIGIT-244 model guard refuses security-policy writes on an election that is already voting.
 */
trait IpBindingFixtures
{
    /** The voter-facing refusal text (PO 2026-10-06, Q2). */
    protected const BINDING_MESSAGE = 'You can only vote from your verified IP address. Please contact the election committee.';

    protected function bindingOn(bool $on = true): void
    {
        DB::table('elections')->where('id', $this->election->id)
            ->update(['ip_verification_enabled' => true, 'ip_binding_enabled' => $on]);
        $this->election = $this->election->fresh();
    }

    /** An officer's capture of this voter's IP in an election (PBDIGIT-222 evidence). */
    protected function capturedIp(User $voter, string $ip, ?string $electionId = null): void
    {
        $electionId ??= $this->election->id;

        DB::table('voter_ip_verifications')->updateOrInsert(
            ['election_id' => $electionId, 'user_id' => $voter->id],
            [
                'id'              => (string) Str::uuid(),
                'organisation_id' => $this->org->id,
                'ip_address'      => $ip,
                'captured_by'     => User::factory()->create(['organisation_id' => $this->org->id])->id,
                'captured_at'     => now(),
                'notes'           => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
    }
}
