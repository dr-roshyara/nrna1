<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Identity;

use App\Contexts\Identity\Application\AuthorityEvidence;
use App\Contexts\Identity\Application\AuthorityEvidenceCheck;
use App\Contexts\Identity\Application\ClaimOutcome;
use App\Contexts\Identity\Application\ClaimRefusal;
use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\IssueClaim;
use App\Contexts\Identity\Application\IssueClaimCommand;
use App\Contexts\Identity\Application\Ports\RequestTime;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;
use App\Contexts\Identity\Application\ReplaceClaim;
use App\Contexts\Identity\Application\ReplaceClaimCommand;
use App\Contexts\Identity\Application\RevokeClaim;
use App\Contexts\Identity\Application\RevokeClaimCommand;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Identity\Infrastructure\PostgresClaims;
use App\Contexts\Identity\Infrastructure\PostgresIssuedReferences;
use App\Contexts\Identity\Infrastructure\RandomClaimCodes;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * PBDIGIT-349 step I-2 on PostgreSQL (I-TDD-02 §5, §6, §10.1, §12 C-I2; D-196 A1, D-197, D-199).
 * An empowered officer (Governance's `manage-claims` answer, passed by value) issues, replaces or revokes an ordinary
 * claim for a recorded person R of organisation O. A claim establishes an identity binding later (I-3); it grants
 * nothing. Covered here: PI-7, PI-12, PI-17a (with the unit test), PI-18, PI-20, PI-21, PI-23, PI-24, PI-27, PI-35,
 * and fail-closed production wiring (R-1 is not available before I-4).
 * The officer's own R in O comes from R-1 (I-4); here a test double stands in for it (the plan's seam).
 */
final class OrdinaryClaimsTest extends MembershipPostgresTestCase
{
    private TenantId $o;
    private OrganisationPersonRef $officer;
    private LocalEntryRef $entry;
    private OrganisationPersonRef $person;

    protected function setUp(): void
    {
        parent::setUp();
        $this->o = TenantId::fromString((string) Str::uuid());
        $refs = new PostgresIssuedReferences(DB::connection());
        $this->officer = $refs->issue($this->o, LocalEntryRef::fromString('officer-' . Str::random(8)));
        $this->entry = LocalEntryRef::fromString('entry-' . Str::random(8));
        $this->person = $refs->issue($this->o, $this->entry);
    }

    private function actors(?OrganisationPersonRef $as = null): SessionActorRefs
    {
        $as ??= $this->officer;

        return new class ($as) implements SessionActorRefs {
            public function __construct(private readonly ?OrganisationPersonRef $as)
            {
            }

            public function actingRef(TenantId $organisation): ?OrganisationPersonRef
            {
                return $this->as;
            }
        };
    }

    private static function time(): RequestTime
    {
        $start = new \DateTimeImmutable('-1 second');

        return new class ($start) implements RequestTime {
            public function __construct(private readonly \DateTimeImmutable $start)
            {
            }

            public function requestStart(): \DateTimeImmutable
            {
                return $this->start;
            }

            public function currentInstant(): \DateTimeImmutable
            {
                return new \DateTimeImmutable();
            }
        };
    }

    private function store(?\Closure $afterLocks = null): PostgresClaims
    {
        return new PostgresClaims(DB::connection(), $afterLocks);
    }

    private function issuer(?PostgresClaims $store = null, ?SessionActorRefs $actors = null): IssueClaim
    {
        return new IssueClaim($actors ?? $this->actors(), new PostgresIssuedReferences(DB::connection()), $store ?? $this->store(),
            new RandomClaimCodes(), self::time(), new AuthorityEvidenceCheck());
    }

    /** @param array<string, string> $subject */
    private function evidence(array $subject, ?string $requestId = null, string $outcome = 'authorised'): AuthorityEvidence
    {
        return new AuthorityEvidence($this->o, $this->officer, 'manage-claims', $subject, $requestId ?? 'req-' . Str::uuid(), $outcome,
            (new \DateTimeImmutable())->format('Y-m-d'), new \DateTimeImmutable(), 'asserted', ['ruleRef' => 'test']);
    }

    private function issue(string $issuanceId, ?int $window = null, ?string $requestId = null, ?IssueClaim $issuer = null): ClaimOutcome
    {
        $subject = ['entryRef' => $this->entry->value(), 'personRef' => $this->person->value(), 'issuanceId' => $issuanceId];

        return ($issuer ?? $this->issuer())->issue(new IssueClaimCommand($this->o, $this->entry, $issuanceId, $window, $this->evidence($subject, $requestId)));
    }

    private static function claimRow(string $claimId): object
    {
        return DB::selectOne('SELECT * FROM identity_claims WHERE claim_id = ?', [$claimId]);
    }

    public function test_an_officer_issues_a_claim_whose_code_is_shown_once_and_stored_only_as_a_hash(): void
    {
        $out = $this->issue('iss-1');

        $this->assertSame('issued', $out->status);
        $this->assertNotNull($out->code);
        $row = self::claimRow($out->claimId);
        $this->assertSame($out->code->hash(), $row->code_hash);
        $this->assertSame([$this->o->value(), $this->person->value(), 'ordinary', 'issued', 14, 'platform-default', false],
            [$row->organisation_id, trim($row->person_ref), $row->kind, $row->state, (int) $row->window_days, $row->window_source, (bool) $row->window_capped]);
        $this->assertSame(1, DB::selectOne("SELECT count(*) AS n FROM identity_claims WHERE claim_id = ? AND expires_at = issued_at + interval '14 days'", [$out->claimId])->n);
        $this->assertStringNotContainsString($out->code->value(), json_encode(DB::select('SELECT * FROM identity_claims WHERE claim_id = ?', [$out->claimId])));
    }

    public function test_the_window_follows_the_rules_and_is_capped_at_30_days(): void
    {
        $out = $this->issue('iss-1', 45);

        $row = self::claimRow($out->claimId);
        $this->assertSame([30, 'rules', true], [(int) $row->window_days, $row->window_source, (bool) $row->window_capped]);
    }

    public function test_a_retry_with_the_same_issuance_id_creates_nothing_and_returns_no_code(): void
    {
        $first = $this->issue('iss-1');
        $retry = $this->issue('iss-1');

        $this->assertSame(['already-issued', $first->claimId, null], [$retry->status, $retry->claimId, $retry->code]);
        $this->assertSame(1, DB::table('identity_claims')->where('organisation_id', $this->o->value())->count());
    }

    public function test_one_active_claim_per_person_and_a_new_one_once_it_expired(): void
    {
        $first = $this->issue('iss-1');
        $this->assertSame([ClaimRefusal::ActiveClaimExists, null], [$this->issue('iss-2')->refusal, null]);

        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '15 days', expires_at = clock_timestamp() - interval '1 day' WHERE claim_id = ?", [$first->claimId]);

        $this->assertSame('issued', $this->issue('iss-3')->status, 'an expired claim does not block a new one (PI-20)');
    }

    public function test_a_bound_or_unknown_person_gets_no_claim(): void
    {
        // a Person as G-0 creates it: always with its account mapping (G-0's tests check that no Person lacks one)
        $person = (string) Str::uuid();
        DB::table('identity_persons')->insert(['id' => $person]);
        DB::table('identity_account_persons')->insert(['account_id' => (string) Str::uuid(), 'person_id' => $person]);
        $holder = $this->issue('iss-hold');
        DB::insert('INSERT INTO identity_bindings (organisation_id, person_ref, person_id, bound_at, claim_id) VALUES (?, ?, ?, clock_timestamp(), ?)',
            [$this->o->value(), $this->person->value(), $person, $holder->claimId]);
        DB::update("UPDATE identity_claims SET state = 'redeemed', ended_at = clock_timestamp() WHERE claim_id = ?", [$holder->claimId]);
        $this->assertSame(ClaimRefusal::ReferenceBound, $this->issue('iss-2')->refusal, 'INV-ID-6: a bound R gets no new claim');

        $unknown = LocalEntryRef::fromString('never-recorded');
        $subject = ['entryRef' => 'never-recorded', 'personRef' => str_repeat('0', 32), 'issuanceId' => 'iss-x'];
        $out = $this->issuer()->issue(new IssueClaimCommand($this->o, $unknown, 'iss-x', null, $this->evidence($subject)));
        $this->assertSame(ClaimRefusal::UnknownEntry, $out->refusal);
    }

    public function test_refused_evidence_consumes_nothing_and_a_replayed_answer_is_refused(): void
    {
        $refused = $this->issue('iss-1', null, null, null);
        $this->assertSame('issued', $refused->status);

        $subject = ['entryRef' => $this->entry->value(), 'personRef' => $this->person->value(), 'issuanceId' => 'iss-9'];
        $notAuthorised = $this->issuer()->issue(new IssueClaimCommand($this->o, $this->entry, 'iss-9', null, $this->evidence($subject, 'req-no', 'not-authorised')));
        $this->assertSame(ClaimRefusal::EvidenceOutcome, $notAuthorised->refusal);
        $this->assertSame(0, DB::table('identity_consumed_authority_requests')->where('request_id', 'req-no')->count(), 'a refused answer is not consumed');

        DB::update("UPDATE identity_claims SET expires_at = clock_timestamp() - interval '1 second', issued_at = clock_timestamp() - interval '2 seconds' WHERE claim_id = ?", [$refused->claimId]);
        $used = DB::selectOne('SELECT authority_request_id FROM identity_claims WHERE claim_id = ?', [$refused->claimId])->authority_request_id;
        $this->assertSame(ClaimRefusal::EvidenceReplayed, $this->issue('iss-10', null, $used)->refusal, 'one Governance answer serves one act (single use)');
    }

    public function test_another_organisations_officer_or_a_session_without_r_gets_no_claim(): void
    {
        $stranger = OrganisationPersonRef::fromString(bin2hex(random_bytes(16)));
        $this->assertSame(ClaimRefusal::EvidenceActor, $this->issue('iss-1', null, null, $this->issuer(null, $this->actors($stranger)))->refusal);

        $nobody = new class implements SessionActorRefs {
            public function actingRef(TenantId $organisation): ?OrganisationPersonRef
            {
                return null;
            }
        };
        $this->assertSame(ClaimRefusal::NotActing, $this->issue('iss-1', null, null, $this->issuer(null, $nobody))->refusal);
        $this->assertSame(0, DB::table('identity_claims')->where('organisation_id', $this->o->value())->count());
    }

    public function test_replace_revokes_the_active_claim_and_issues_a_new_one_in_one_act(): void
    {
        $old = $this->issue('iss-1');
        $replacer = new ReplaceClaim($this->actors(), $this->store(), new RandomClaimCodes(), self::time(), new AuthorityEvidenceCheck());
        $subject = ['claimId' => $old->claimId, 'newIssuanceId' => 'iss-2'];

        $new = $replacer->replace(new ReplaceClaimCommand($this->o, $old->claimId, 'iss-2', null, $this->evidence($subject)));

        $this->assertSame('issued', $new->status);
        $this->assertSame('revoked', self::claimRow($old->claimId)->state);
        $this->assertSame($old->claimId, self::claimRow($new->claimId)->replaces_claim_id);
        $this->assertSame('already-issued', $replacer->replace(new ReplaceClaimCommand($this->o, $old->claimId, 'iss-2', null, $this->evidence($subject)))->status, 'a retry creates nothing');
    }

    public function test_replace_and_revoke_need_an_active_claim_then_issue_works_again(): void
    {
        $old = $this->issue('iss-1');
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '15 days', expires_at = clock_timestamp() - interval '1 day' WHERE claim_id = ?", [$old->claimId]);
        $replacer = new ReplaceClaim($this->actors(), $this->store(), new RandomClaimCodes(), self::time(), new AuthorityEvidenceCheck());
        $revoker = new RevokeClaim($this->actors(), $this->store(), self::time(), new AuthorityEvidenceCheck());

        $this->assertSame(ClaimRefusal::NotActive, $replacer->replace(new ReplaceClaimCommand($this->o, $old->claimId, 'iss-2', null,
            $this->evidence(['claimId' => $old->claimId, 'newIssuanceId' => 'iss-2'])))->refusal, 'PI-27: an expired claim is not replaced');
        $this->assertSame(ClaimRefusal::NotActive, $revoker->revoke(new RevokeClaimCommand($this->o, $old->claimId,
            $this->evidence(['claimId' => $old->claimId])))->refusal, 'PI-27: an expired claim is not revoked');
        $this->assertSame('issued', $this->issue('iss-3')->status, 'IssueClaim succeeds instead');
    }

    public function test_revoke_ends_an_active_claim_once_and_only_in_its_own_organisation(): void
    {
        $claim = $this->issue('iss-1');
        $revoker = new RevokeClaim($this->actors(), $this->store(), self::time(), new AuthorityEvidenceCheck());

        $other = TenantId::fromString((string) Str::uuid());
        $foreign = new AuthorityEvidence($other, $this->officer, 'manage-claims', ['claimId' => $claim->claimId], 'req-' . Str::uuid(), 'authorised',
            (new \DateTimeImmutable())->format('Y-m-d'), new \DateTimeImmutable(), 'asserted', []);
        $this->assertSame(ClaimRefusal::UnknownClaim, $revoker->revoke(new RevokeClaimCommand($other, $claim->claimId, $foreign))->refusal, 'ISO-1: no act on another organisation\'s claim');

        $this->assertSame('revoked', $revoker->revoke(new RevokeClaimCommand($this->o, $claim->claimId, $this->evidence(['claimId' => $claim->claimId])))->status);
        $this->assertSame(ClaimRefusal::NotActive, $revoker->revoke(new RevokeClaimCommand($this->o, $claim->claimId, $this->evidence(['claimId' => $claim->claimId])))->refusal);
    }

    public function test_central_storage_holds_no_governance_answer_content(): void
    {
        $claim = $this->issue('iss-1');

        $columns = array_column(DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'identity_consumed_authority_requests' ORDER BY column_name"), 'column_name');
        $this->assertSame(['act_kind', 'claim_id', 'consumed_at', 'organisation_id', 'request_id'], $columns, 'PI-21: identifiers and provenance only');
        $this->assertStringNotContainsString('ruleRef', json_encode(DB::select('SELECT * FROM identity_claims WHERE claim_id = ?', [$claim->claimId])));
    }

    public function test_the_claim_sql_never_uses_the_transaction_start_time(): void
    {
        $source = file_get_contents(base_path('app/Contexts/Identity/Infrastructure/PostgresClaims.php'));

        $this->assertDoesNotMatchRegularExpression('/\bnow\(\)|CURRENT_TIMESTAMP|transaction_timestamp/i', $source, 'PI-24: t_check is clock_timestamp() after the locks');
        $this->assertStringContainsString('clock_timestamp()', $source);
    }

    public function test_production_refuses_until_r1_exists(): void
    {
        $this->expectException(IdentityUnavailable::class);
        $subject = ['entryRef' => $this->entry->value(), 'personRef' => $this->person->value(), 'issuanceId' => 'iss-p'];
        app(IssueClaim::class)->issue(new IssueClaimCommand($this->o, $this->entry, 'iss-p', null, $this->evidence($subject)));
    }

    // ---- concurrency (forked holder; PI-18, PI-23, PI-35) ----

    /** @return array{0: mixed, 1: int} the contender's result and how long it waited (ms) */
    private function whileHolding(\Closure $holderIssue, \Closure $contender): array
    {
        $dir = sys_get_temp_dir() . '/pbdigit349-i2-' . Str::random(10);
        mkdir($dir);
        DB::disconnect();
        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            try {
                $holderIssue($dir);
            } catch (\Throwable $e) {
                file_put_contents("$dir/holder-error", $e::class . ': ' . $e->getMessage());
            } finally {
                touch("$dir/holding");
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
            }
        }
        DB::reconnect();
        $deadline = hrtime(true) + 60_000_000_000;
        while (!file_exists("$dir/holding")) {
            if (hrtime(true) > $deadline) {
                posix_kill($pid, SIGKILL);
                pcntl_waitpid($pid, $status);
                throw new \RuntimeException('the holder never took the lock within 60 s');
            }
            usleep(1000);
        }
        usleep(200_000);
        $start = hrtime(true);
        $result = $contender();
        $waited = (int) ((hrtime(true) - $start) / 1_000_000);
        pcntl_waitpid($pid, $status);
        if (file_exists("$dir/holder-error")) {
            throw new \RuntimeException('the holder failed: ' . file_get_contents("$dir/holder-error"));
        }

        return [$result, $waited];
    }

    /** A holder that issues under the slot lock and keeps it $ms before committing. */
    private function holdingIssue(string $issuanceId, int $ms): \Closure
    {
        return function (string $dir) use ($issuanceId, $ms): void {
            $store = $this->store(static function () use ($dir, $ms): void {
                touch("$dir/holding");
                usleep($ms * 1000);
            });
            $this->issue($issuanceId, null, null, $this->issuer($store));
        };
    }

    public function test_two_concurrent_first_issuances_give_one_active_claim(): void
    {
        [$second, $waited] = $this->whileHolding($this->holdingIssue('iss-a', 800), fn (): ClaimOutcome => $this->issue('iss-b'));

        $this->assertGreaterThan(400, $waited, 'the contender waited for the slot');
        $this->assertSame(ClaimRefusal::ActiveClaimExists, $second->refusal, 'PI-18');
        $this->assertSame(1, DB::table('identity_claims')->where('organisation_id', $this->o->value())->where('state', 'issued')->count());
    }

    public function test_two_concurrent_issuances_on_an_existing_slot_give_one_active_claim(): void
    {
        // a later issuance: the slot row already exists (committed), so only its FOR UPDATE serialises the two
        // (a first issuance is also serialised by the unique-key wait on inserting the slot; mutant M2 showed that)
        $earlier = $this->issue('iss-earlier');
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '15 days', expires_at = clock_timestamp() - interval '1 day' WHERE claim_id = ?", [$earlier->claimId]);

        [$second, $waited] = $this->whileHolding($this->holdingIssue('iss-a', 800), fn (): ClaimOutcome => $this->issue('iss-b'));

        $this->assertGreaterThan(400, $waited, 'the contender waited for the existing slot');
        $this->assertSame(ClaimRefusal::ActiveClaimExists, $second->refusal, 'INV-CLM-1 on a later issuance');
        $this->assertSame(1, DB::table('identity_claims')->where('organisation_id', $this->o->value())->where('state', 'issued')->where('expires_at', '>', DB::raw('clock_timestamp()'))->count());
    }

    public function test_a_retry_while_the_slot_is_held_creates_nothing(): void
    {
        [$retry] = $this->whileHolding($this->holdingIssue('iss-a', 800), fn (): ClaimOutcome => $this->issue('iss-a'));

        $this->assertSame(['already-issued', null], [$retry->status, $retry->code], 'PI-35');
        $this->assertSame(1, DB::table('identity_claims')->where('organisation_id', $this->o->value())->count());
    }

    public function test_an_issuance_that_waits_across_the_old_claims_expiry_treats_it_as_inactive(): void
    {
        $old = $this->issue('iss-old');
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '14 days', expires_at = clock_timestamp() + interval '600 milliseconds' WHERE claim_id = ?", [$old->claimId]);
        // the holder takes the slot (its own issuance is refused: the old claim is still active then) and keeps it past the expiry
        $holder = function (string $dir): void {
            $store = $this->store(static function () use ($dir): void {
                touch("$dir/holding");
                usleep(1_200_000);
            });
            $this->issue('iss-holder', null, null, $this->issuer($store));
        };

        [$late] = $this->whileHolding($holder, fn (): ClaimOutcome => $this->issue('iss-late'));

        $this->assertSame('issued', $late->status, 'PI-23: t_check is read after the lock, so the expired claim no longer blocks');
    }
}
