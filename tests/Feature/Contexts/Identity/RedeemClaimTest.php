<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Identity;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Application\RedeemClaim;
use App\Contexts\Identity\Application\RedeemClaimCommand;
use App\Contexts\Identity\Application\RedemptionOutcome;
use App\Contexts\Identity\Application\RedemptionRefusal;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\ClaimCode;
use App\Contexts\Identity\Domain\ClaimWindow;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Identity\Infrastructure\PostgresAccountDirectory;
use App\Contexts\Identity\Infrastructure\PostgresClaims;
use App\Contexts\Identity\Infrastructure\PostgresIssuedReferences;
use App\Contexts\Identity\Infrastructure\PostgresRedemptionThrottle;
use App\Contexts\Identity\Infrastructure\RandomClaimCodes;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * PBDIGIT-349 step I-3 on PostgreSQL (I-TDD-02 §4 INV-ID-6/9, INV-BND-1/2/3, INV-CLM-3; §6.2 redemption row; §12 C-I3,
 * C-I6; §14 "redeem while signed in" and cases 3, 5; §15; §17). A signed-in person redeems an ordinary claim code:
 * ONE shared redemption service, ClaimSlot (O, R) → claim row → one clock_timestamp() sample; the binding is created,
 * the claim becomes redeemed, and O is told ClaimRedeemed(O, R) through the central outbox (never the PersonId).
 * Covered: PI-1, 2, 3, 8, 9, 13, 15 (redeem half), 22, 28, 34, 44, 48; throttling per code and per client (INV-ID-9).
 */
final class RedeemClaimTest extends MembershipPostgresTestCase
{
    private TenantId $o;
    private OrganisationPersonRef $r;

    protected function setUp(): void
    {
        parent::setUp();
        $this->o = TenantId::fromString((string) Str::uuid());
        $this->r = (new PostgresIssuedReferences(DB::connection()))->issue($this->o, LocalEntryRef::fromString('entry-' . Str::random(8)));
    }

    /** An active legacy users row = an Account (Q-3: the users row, transitional). */
    private static function account(): AccountId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 'i3', 'slug' => 'i3-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $id = (string) Str::uuid();
        DB::table('users')->insert(['id' => $id, 'organisation_id' => $org, 'name' => 'I3 user', 'region' => 'x', 'email' => "i3-{$id}@example.test",
            'password' => 'x', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()]);

        return AccountId::fromString($id);
    }

    private static function signedIn(?AccountId $account): CurrentAccount
    {
        return new class ($account) implements CurrentAccount {
            public function __construct(private readonly ?AccountId $account)
            {
            }

            public function accountId(): ?AccountId
            {
                return $this->account;
            }
        };
    }

    private static function store(?\Closure $afterDecision = null): PostgresClaims
    {
        return new PostgresClaims(DB::connection(), $afterDecision);
    }

    private const SECRET = 'test-throttle-secret-0123456789abcdef';

    private static function throttle(string $secret = self::SECRET): PostgresRedemptionThrottle
    {
        return new PostgresRedemptionThrottle(DB::connection(), perCode: 5, perClient: 10, windowSeconds: 900, clientKeySecret: $secret);
    }

    private function redeemer(AccountId $account, ?PostgresClaims $store = null): RedeemClaim
    {
        return new RedeemClaim(self::signedIn($account), new PostgresAccountDirectory(DB::connection()), $store ?? self::store(), self::throttle());
    }

    /** Issues an ordinary claim for (O, R) straight through the store (I-2 is proven separately). */
    private function issued(?OrganisationPersonRef $r = null, ?TenantId $o = null): array
    {
        $code = (new RandomClaimCodes())->generate();
        $out = self::store()->issue($o ?? $this->o, $r ?? $this->r, 'iss-' . Str::uuid(), $code, ClaimWindow::fromRules(null), 'req-' . Str::uuid());

        return [$code, $out->claimId];
    }

    private function redeem(AccountId $account, ClaimCode|string $code, string $client = 'client-1', ?PostgresClaims $store = null): RedemptionOutcome
    {
        return $this->redeemer($account, $store)->redeem(new RedeemClaimCommand($code instanceof ClaimCode ? $code->value() : $code, $client));
    }

    private static function personOf(AccountId $account): string
    {
        return (new PostgresAccountDirectory(DB::connection()))->activePersonOf($account)->value();
    }

    public function test_redemption_binds_r_to_the_person_and_reports_only_r_to_the_organisation(): void
    {
        [$code, $claimId] = $this->issued();
        $account = self::account();

        $out = $this->redeem($account, $code);

        $this->assertSame(['bound', $this->o->value()], [$out->status, $out->organisationId]);
        $binding = DB::selectOne('SELECT person_id, claim_id, bound_at::text AS bound_at FROM identity_bindings WHERE organisation_id = ? AND person_ref = ?', [$this->o->value(), $this->r->value()]);
        $this->assertSame([self::personOf($account), $claimId], [$binding->person_id, $binding->claim_id]);
        $claim = DB::selectOne('SELECT state, ended_at::text AS ended_at FROM identity_claims WHERE claim_id = ?', [$claimId]);
        $this->assertSame(['redeemed', $binding->bound_at], [$claim->state, $claim->ended_at], 'PI-9: bound at the redemption\'s own t_check, never back-dated');

        $event = DB::selectOne("SELECT payload::text AS payload FROM identity_outbox_events WHERE event_type = 'ClaimRedeemed' AND organisation_id = ? ORDER BY created_at DESC LIMIT 1", [$this->o->value()]);
        $payload = json_decode($event->payload, true);
        $this->assertSame([$this->o->value(), $this->r->value(), $claimId], [$payload['organisationId'], $payload['personRef'], $payload['claimId']]);
        $this->assertStringNotContainsString(self::personOf($account), $event->payload, 'C-I3: the organisation learns R, never the PersonId');
        $this->assertStringNotContainsString($code->value(), $event->payload);
    }

    public function test_a_redeemed_code_never_binds_twice_and_the_same_person_gets_an_idempotent_answer(): void
    {
        [$code, $claimId] = $this->issued();
        $holder = self::account();
        $this->redeem($holder, $code);

        $this->assertSame('already-bound', $this->redeem($holder, $code)->status, '§14 case 3: same Account, while the redeemed row exists');
        $this->assertSame(RedemptionRefusal::NotRedeemable, $this->redeem(self::account(), $code, 'client-2')->refusal, 'PI-2 / case 5: anyone else → neutral');
        $this->assertSame(1, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->count());

        DB::delete('DELETE FROM identity_claims WHERE claim_id = ?', [$claimId]);   // legitimate cleanup of an ended row (§15)
        $this->assertSame(RedemptionRefusal::NotRedeemable, $this->redeem($holder, $code)->refusal, 'PI-48 (ordinary claims only; bootstrap claims are I-6): after cleanup the same Account gets the neutral failure');
        $this->assertSame(1, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->where('person_ref', $this->r->value())->count(), 'the binding survives cleanup');
    }

    public function test_a_person_already_bound_in_the_organisation_is_refused_and_the_organisation_told(): void
    {
        $holder = self::account();
        [$first] = $this->issued();
        $this->redeem($holder, $first);
        $other = (new PostgresIssuedReferences(DB::connection()))->issue($this->o, LocalEntryRef::fromString('entry-' . Str::random(8)));
        [$second, $secondId] = $this->issued($other);

        $out = $this->redeem($holder, $second);

        $this->assertSame(RedemptionRefusal::PersonAlreadyBound, $out->refusal, 'INV-BND-2 (PI-3)');
        $this->assertSame('issued', DB::selectOne('SELECT state FROM identity_claims WHERE claim_id = ?', [$secondId])->state, 'the claim is not consumed');
        $event = DB::selectOne("SELECT payload::text AS payload, aggregate_type, aggregate_id FROM identity_outbox_events WHERE event_type = 'BindingRefused' AND organisation_id = ? ORDER BY created_at DESC LIMIT 1", [$this->o->value()]);
        // jsonb keeps its own key order: compare content, exactly these two keys
        $this->assertEquals(['organisationId' => $this->o->value(), 'personRef' => $other->value()], json_decode($event->payload, true), 'C-I6: O learns of the refusal only');
        $this->assertSame(['IdentityClaim', $secondId], [$event->aggregate_type, $event->aggregate_id], 'review R-8: the aggregate of BindingRefused is the claim, as for ClaimRedeemed');
    }

    public function test_expired_revoked_unknown_and_malformed_codes_fail_with_one_neutral_message(): void
    {
        $account = self::account();
        [$expired, $expiredId] = $this->issued();
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '15 days', expires_at = clock_timestamp() - interval '1 second' WHERE claim_id = ?", [$expiredId]);
        $outcomes = [
            'expired (PI-13)' => $this->redeem($account, $expired),
            'unknown' => $this->redeem($account, (new RandomClaimCodes())->generate()),
            'malformed' => $this->redeem($account, 'not a code'),
        ];
        [$revoked, $revokedId] = $this->issued();
        DB::update("UPDATE identity_claims SET state = 'revoked', ended_at = clock_timestamp() WHERE claim_id = ?", [$revokedId]);
        $outcomes['revoked'] = $this->redeem($account, $revoked);

        foreach ($outcomes as $case => $out) {
            $this->assertSame('refused', $out->status, $case);
            $this->assertSame('The code could not be redeemed.', $out->refusal->publicMessage(), "{$case}: PI-8, one neutral message");
        }
        $this->assertSame(0, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->count());
        foreach ([$expired, $revoked] as $code) {
            $this->assertSame(0, DB::table('identity_claim_audit')->where('reason', 'like', '%' . $code->value() . '%')->count(), 'PI-8: no code in the audit');
        }
    }

    public function test_redemption_needs_a_signed_in_active_account(): void
    {
        [$code] = $this->issued();

        $out = (new RedeemClaim(self::signedIn(null), new PostgresAccountDirectory(DB::connection()), self::store(), self::throttle()))
            ->redeem(new RedeemClaimCommand($code->value(), 'client-1'));

        $this->assertSame(RedemptionRefusal::NotSignedIn, $out->refusal);
    }

    public function test_failed_attempts_are_throttled_per_client_and_per_code_and_the_claim_is_never_disabled(): void
    {
        [$code] = $this->issued();
        $account = self::account();
        for ($i = 0; $i < 10; $i++) {
            $this->redeem($account, (new RandomClaimCodes())->generate(), 'guessing-client');
        }
        $this->assertSame(RedemptionRefusal::Throttled, $this->redeem($account, $code, 'guessing-client')->refusal, 'per client (INV-ID-9)');

        DB::update("UPDATE identity_redemption_failures SET window_start = clock_timestamp() - interval '1 hour' WHERE scope = 'client'");
        DB::insert("INSERT INTO identity_redemption_failures (scope, key_hash, window_start, failures) VALUES ('code', ?, clock_timestamp(), 5)
            ON CONFLICT (scope, key_hash) DO UPDATE SET window_start = clock_timestamp(), failures = 5", [$code->hash()]);
        $this->assertSame(RedemptionRefusal::Throttled, $this->redeem($account, $code, 'fresh-client')->refusal, 'per code, from any client');

        DB::update("UPDATE identity_redemption_failures SET window_start = clock_timestamp() - interval '1 hour' WHERE scope = 'code'");
        $this->assertSame('bound', $this->redeem($account, $code, 'fresh-client')->status, 'throttling slows; it never disables the claim (D-197)');
        // R-7 / R-2: the client key is stored as a KEYED hash (HMAC with the server secret), never as a plain hash
        $this->assertSame(0, DB::table('identity_redemption_failures')->where('key_hash', hash('sha256', 'guessing-client'))->count(), 'no plain, enumerable hash');
        $this->assertSame(1, DB::table('identity_redemption_failures')->where('scope', 'client')->where('key_hash', hash_hmac('sha256', 'guessing-client', self::SECRET))->count());
    }

    // PI-44 (the single-writer guard) moved to tests/Architecture/IdentityBindingSingleWriterTest.php (review R-4).

    // ---- review corrections (R-1, R-2, R-3, R-6, F-3, PI-29, PI-15) ----

    public function test_already_bound_attempts_count_towards_throttling(): void
    {
        $account = self::account();
        [$first] = $this->issued();
        $this->redeem($account, $first);
        $other = (new PostgresIssuedReferences(DB::connection()))->issue($this->o, LocalEntryRef::fromString('entry-' . Str::random(8)));
        [$second] = $this->issued($other);

        for ($i = 1; $i <= 5; $i++) {
            $this->assertSame(RedemptionRefusal::PersonAlreadyBound, $this->redeem($account, $second, "client-{$i}")->refusal, "attempt {$i}");
        }

        $this->assertSame(RedemptionRefusal::Throttled, $this->redeem($account, $second, 'client-6')->refusal, 'R-6 (Principal): PersonAlreadyBound is a failed attempt; built up by real failures, per code');
        $this->assertSame(5, DB::table('identity_outbox_events')->where('event_type', 'BindingRefused')->where('organisation_id', $this->o->value())->count(), 'refusal events are bounded by the throttle');
    }

    public function test_expired_throttle_windows_are_purged_and_active_ones_kept(): void
    {
        $throttle = self::throttle();
        DB::insert("INSERT INTO identity_redemption_failures (scope, key_hash, window_start, failures) VALUES ('client', ?, clock_timestamp() - interval '1 hour', 3)", [hash('sha256', 'old-' . Str::uuid())]);
        $expired = DB::selectOne("SELECT count(*) AS n FROM identity_redemption_failures WHERE window_start <= clock_timestamp() - interval '900 seconds'")->n;
        $this->assertGreaterThan(0, $expired);

        $throttle->recordFailure(hash('sha256', 'code-' . Str::uuid()), 'client-' . Str::uuid());

        $this->assertSame(0, DB::selectOne("SELECT count(*) AS n FROM identity_redemption_failures WHERE window_start <= clock_timestamp() - interval '900 seconds'")->n, 'R-2: rows whose window has ended are deleted');
        $this->assertGreaterThan(0, DB::selectOne("SELECT count(*) AS n FROM identity_redemption_failures WHERE window_start > clock_timestamp() - interval '900 seconds'")->n, 'active windows are kept');
    }

    public function test_client_keys_are_always_keyed_hashes_and_the_secret_is_never_stored(): void
    {
        $hexLookingClient = str_repeat('ab', 32);   // 64 hex characters: must still be keyed, never stored as given
        self::throttle()->recordFailure(hash('sha256', 'code-' . Str::uuid()), $hexLookingClient);

        $this->assertSame(0, DB::table('identity_redemption_failures')->where('key_hash', $hexLookingClient)->count(), 'R-2: no inference from the 64-hex pattern');
        $this->assertSame(1, DB::table('identity_redemption_failures')->where('scope', 'client')->where('key_hash', hash_hmac('sha256', $hexLookingClient, self::SECRET))->count());
        foreach (['identity_redemption_failures', 'identity_outbox_events', 'identity_claim_audit'] as $table) {
            $this->assertStringNotContainsString(self::SECRET, json_encode(DB::table($table)->get()), "the secret never reaches {$table}");
        }

        $this->expectException(\InvalidArgumentException::class);
        self::throttle()->recordFailure('not-a-code-hash', 'client');   // a code key must be a code hash, stated explicitly
    }

    public function test_without_a_throttle_secret_redemption_is_refused_as_unavailable(): void
    {
        [$code] = $this->issued();
        $redeemer = new RedeemClaim(self::signedIn(self::account()), new PostgresAccountDirectory(DB::connection()), self::store(), self::throttle(''));

        $this->expectException(IdentityUnavailable::class);
        $redeemer->redeem(new RedeemClaimCommand($code->value(), 'client-1'));
    }

    public function test_the_three_claim_foreign_keys_are_absent_and_the_binding_keys_remain(): void
    {
        $fks = DB::select("SELECT conname FROM pg_constraint WHERE contype = 'f' AND confrelid = 'identity_claims'::regclass ORDER BY conname");
        $this->assertSame([], array_column($fks, 'conname'), 'F-3: nothing references identity_claims, so cleanup of ended claims is possible (§15)');
        $unique = array_column(DB::select("SELECT conname FROM pg_constraint WHERE conrelid = 'identity_bindings'::regclass AND contype IN ('p', 'u') ORDER BY conname"), 'conname');
        $this->assertSame(['identity_bindings_organisation_id_person_id_key', 'identity_bindings_pkey'], $unique, 'INV-BND-1 and INV-BND-2 stay enforced by the database');
    }

    public function test_the_redemption_migration_is_forward_only_and_never_drops_identitys_outbox(): void
    {
        $migration = require base_path('database/migrations/2026_11_15_630000_identity_redemption_and_claim_cleanup_safety.php');

        try {
            $migration->down();
            $this->fail('down() must refuse');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('forward-only', $e->getMessage());
        }
        $this->assertNotNull(DB::selectOne("SELECT to_regclass('identity_outbox_events') AS t")->t, 'R-3: undelivered events are never dropped by a rollback');
        $this->assertNotNull(DB::selectOne("SELECT to_regclass('identity_redemption_failures') AS t")->t);
    }

    public function test_one_person_redeeming_two_references_at_once_gets_a_reported_refusal(): void
    {
        [$first, $firstId] = $this->issued();
        $other = (new PostgresIssuedReferences(DB::connection()))->issue($this->o, LocalEntryRef::fromString('entry-' . Str::random(8)));
        [$second, $secondId] = $this->issued($other);
        $account = self::account();
        self::personOf($account);

        [$out] = $this->whileHolding(
            fn (string $dir) => $this->redeem($account, $first, 'client-a', self::holdingStore($dir, 800)),
            fn (): RedemptionOutcome => $this->redeem($account, $second, 'client-b'),
        );

        $this->assertSame(RedemptionRefusal::PersonAlreadyBound, $out->refusal, 'R-1: a refusal, not "unavailable"');
        $this->assertSame(1, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->where('person_id', self::personOf($account))->count());
        $this->assertSame('issued', DB::selectOne('SELECT state FROM identity_claims WHERE claim_id = ?', [$secondId])->state, 'the second claim stays usable');
        $this->assertSame('redeemed', DB::selectOne('SELECT state FROM identity_claims WHERE claim_id = ?', [$firstId])->state);
        $this->assertSame(1, DB::table('identity_outbox_events')->where('event_type', 'BindingRefused')->where('organisation_id', $this->o->value())->count(), 'INV-BND-2: reported to O exactly once, even under concurrency (the rolled-back attempt wrote nothing)');
        $this->assertSame(1, DB::table('identity_outbox_events')->where('event_type', 'ClaimRedeemed')->where('organisation_id', $this->o->value())->count());
        $event = DB::selectOne("SELECT payload::text AS payload FROM identity_outbox_events WHERE event_type = 'BindingRefused' AND organisation_id = ?", [$this->o->value()]);
        $this->assertEquals(['organisationId' => $this->o->value(), 'personRef' => $other->value()], json_decode($event->payload, true));
        $this->assertSame(1, DB::table('identity_claim_audit')->where('claim_id', $secondId)->where('event', 'binding-refused')->count());
    }

    public function test_pi29_an_issuance_after_expiry_holds_the_slot_and_the_waiting_redemption_of_the_old_code_is_refused(): void
    {
        [$old, $oldId] = $this->issued();
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '15 days', expires_at = clock_timestamp() - interval '1 second' WHERE claim_id = ?", [$oldId]);
        $account = self::account();
        self::personOf($account);
        $holder = function (string $dir): void {
            $out = self::holdingStore($dir, 800)->issue($this->o, $this->r, 'iss-new-' . Str::uuid(), (new RandomClaimCodes())->generate(), ClaimWindow::fromRules(null), 'req-' . Str::uuid());
            if ($out->status !== 'issued') {
                throw new \RuntimeException('the new claim was not issued: ' . $out->refusal?->value);
            }
        };

        [$late, $waited] = $this->whileHolding($holder, fn (): RedemptionOutcome => $this->redeem($account, $old));

        $this->assertGreaterThan(400, $waited, 'the redemption waited for the slot held by the issuance');
        $this->assertSame(RedemptionRefusal::NotRedeemable, $late->refusal, 'PI-29: the old code is expired at the redemption\'s own t_check');
        $this->assertSame(0, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->count());
        $active = DB::select("SELECT claim_id FROM identity_claims WHERE organisation_id = ? AND person_ref = ? AND state = 'issued' AND expires_at > clock_timestamp()", [$this->o->value(), $this->r->value()]);
        $this->assertCount(1, $active, 'PI-29: exactly one active claim for (O, R), the new one');
        $this->assertNotSame($oldId, $active[0]->claim_id);
    }

    public function test_pi15_replace_first_then_the_waiting_redemption_of_the_old_code_is_refused(): void
    {
        [$old, $oldId] = $this->issued();
        $account = self::account();
        self::personOf($account);
        $holder = function (string $dir) use ($oldId): void {
            self::holdingStore($dir, 800)->replace($this->o, $oldId, 'iss-repl-' . Str::uuid(), (new RandomClaimCodes())->generate(), ClaimWindow::fromRules(null), 'req-' . Str::uuid());
        };

        [$late] = $this->whileHolding($holder, fn (): RedemptionOutcome => $this->redeem($account, $old));

        $this->assertSame(RedemptionRefusal::NotRedeemable, $late->refusal, 'PI-15: replaced first → the old code is revoked when the redemption reads it');
        $this->assertSame('revoked', DB::selectOne('SELECT state FROM identity_claims WHERE claim_id = ?', [$oldId])->state);
    }

    public function test_pi15_redeem_first_then_a_waiting_replacement_is_refused(): void
    {
        [$code, $claimId] = $this->issued();
        $account = self::account();
        self::personOf($account);

        [$replace] = $this->whileHolding(
            fn (string $dir) => $this->redeem($account, $code, 'client-1', self::holdingStore($dir, 800)),
            fn () => self::store()->replace($this->o, $claimId, 'iss-repl-' . Str::uuid(), (new RandomClaimCodes())->generate(), ClaimWindow::fromRules(null), 'req-' . Str::uuid()),
        );

        $this->assertSame('not-active', $replace->refusal->value, 'PI-15: a redeemed claim is terminal');
    }

    // ---- races (forked holder) ----

    /** @return array{0: mixed, 1: int} */
    private function whileHolding(\Closure $holder, \Closure $contender): array
    {
        $dir = sys_get_temp_dir() . '/pbdigit349-i3-' . Str::random(10);
        mkdir($dir);
        DB::disconnect();
        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            try {
                $holder($dir);
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

    private static function holdingStore(string $dir, int $ms): PostgresClaims
    {
        return self::store(static function () use ($dir, $ms): void {
            touch("$dir/holding");
            usleep($ms * 1000);
        });
    }

    public function test_two_concurrent_redemptions_of_one_code_give_exactly_one_binding(): void
    {
        [$code] = $this->issued();
        [$a, $b] = [self::account(), self::account()];
        self::personOf($a);
        self::personOf($b);

        [$second, $waited] = $this->whileHolding(
            fn (string $dir) => $this->redeem($a, $code, 'client-a', self::holdingStore($dir, 800)),
            fn (): RedemptionOutcome => $this->redeem($b, $code, 'client-b'),
        );

        $this->assertGreaterThan(400, $waited, 'the second redemption waited for the slot');
        $this->assertSame(RedemptionRefusal::NotRedeemable, $second->refusal, 'PI-1');
        $this->assertSame(1, DB::table('identity_bindings')->where('organisation_id', $this->o->value())->count());
    }

    public function test_a_redemption_that_waits_across_expiry_is_refused(): void
    {
        [$code, $claimId] = $this->issued();
        DB::update("UPDATE identity_claims SET issued_at = clock_timestamp() - interval '14 days', expires_at = clock_timestamp() + interval '600 milliseconds' WHERE claim_id = ?", [$claimId]);
        $account = self::account();
        self::personOf($account);
        // the holder takes ClaimSlot (O, R) with an issuance that is refused (the claim is still active) and keeps it past expiry
        $holder = function (string $dir): void {
            self::holdingStore($dir, 1200)->issue($this->o, $this->r, 'iss-hold-' . Str::uuid(), (new RandomClaimCodes())->generate(), ClaimWindow::fromRules(null), 'req-' . Str::uuid());
        };

        [$late] = $this->whileHolding($holder, fn (): RedemptionOutcome => $this->redeem($account, $code));

        $this->assertSame(RedemptionRefusal::NotRedeemable, $late->refusal, 'PI-22: t_check after the lock sees the claim expired');
    }

    public function test_an_issuance_waiting_behind_a_redemption_sees_r_bound(): void
    {
        [$code] = $this->issued();
        $account = self::account();
        self::personOf($account);

        [$issuance] = $this->whileHolding(
            fn (string $dir) => $this->redeem($account, $code, 'client-1', self::holdingStore($dir, 800)),
            fn () => self::store()->issue($this->o, $this->r, 'iss-after-' . Str::uuid(), (new RandomClaimCodes())->generate(), ClaimWindow::fromRules(null), 'req-' . Str::uuid()),
        );

        $this->assertSame('reference-bound', $issuance->refusal->value, 'PI-28 (one forked interleaving; not a PI-34 load test): no active claim for a bound R');
    }

    public function test_a_revocation_waiting_behind_a_redemption_finds_nothing_to_revoke(): void
    {
        [$code, $claimId] = $this->issued();
        $account = self::account();
        self::personOf($account);

        [$revoke] = $this->whileHolding(
            fn (string $dir) => $this->redeem($account, $code, 'client-1', self::holdingStore($dir, 800)),
            fn () => self::store()->revoke($this->o, $claimId, 'req-' . Str::uuid()),
        );

        $this->assertSame('not-active', $revoke->refusal->value, 'PI-15, redeem-then-revoke order only: a redeemed claim is terminal');
        $this->assertSame('redeemed', DB::selectOne('SELECT state FROM identity_claims WHERE claim_id = ?', [$claimId])->state);
    }
}
