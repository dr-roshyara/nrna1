<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\DecideAndAppend;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\InterleavingRepository;

/**
 * Slice 1a contract R-1a-2, INV-M, IDEM-3: the conflict path never reuses a stale
 * decision; creation races yield one Member; concurrent identical retries append once.
 * PBDIGIT-259.
 */
final class ConcurrencyAndRaceTest extends TestCase
{
    private InMemoryMemberRepository $store;
    private InterleavingRepository $repo;

    protected function setUp(): void
    {
        $this->store = new InMemoryMemberRepository(static fn (): int => 100);
        $this->repo = new InterleavingRepository($this->store);
    }

    private static function admit(string $key, string $factId, int $at, string $memberIdIfNew = 'M-P1'): AdmitMember
    {
        return new AdmitMember('O1', 'P1', $memberIdIfNew, $factId, $at, $at, null, true, 'registrar-1', 'decl-fixture', null, $key);
    }

    private static function end(string $key, string $factId, int $at): EndMembership
    {
        return new EndMembership('O1', 'M-P1', $factId, 'A1', $at, $at, 'resignation', 'registrar-1', null, $key);
    }

    private function member(): Member
    {
        return $this->store->findByHolder(TenantId::fromString('O1'), PersonRef::fromString('P1'));
    }

    public function test_a_conflict_is_re_decided_against_the_new_state(): void
    {
        $authority = new FakeAuthority();
        (new AdmitMemberHandler($this->store, $authority))->handle(self::admit('k1', 'A1', 1));
        // Another writer ends the period between our load and our append.
        $this->repo->beforeNextAppend(fn () => (new EndMembershipHandler($this->store, new FakeAuthority()))->handle(self::end('other', 'E-other', 4)));
        $callsBefore = $authority->calls;

        $outcome = (new EndMembershipHandler($this->repo, $authority))->handle(self::end('mine', 'E-mine', 6));

        $this->assertSame(RefusalReason::PeriodAlreadyEnded, $outcome->domainRefusal(), 're-guarded against the state after the other writer');
        $this->assertSame(['A1', 'E-other'], array_map(static fn ($r) => $r->fact()->id()->value(), $this->member()->log()));
        $this->assertSame(2, $authority->calls - $callsBefore, 're-authorised on the second attempt');
    }

    public function test_re_authorisation_is_real(): void
    {
        (new AdmitMemberHandler($this->store, new FakeAuthority()))->handle(self::admit('k1', 'A1', 1));
        $this->repo->beforeNextAppend(fn () => (new EndMembershipHandler($this->store, new FakeAuthority()))->handle(self::end('other', 'E-other', 4)));

        $outcome = (new EndMembershipHandler($this->repo, new FakeAuthority(1)))->handle(self::end('mine', 'E-mine', 6));

        $this->assertSame(ApplicationRefusal::NotAuthorised, $outcome->applicationRefusal(), 'the authority was withdrawn before the second attempt');
    }

    public function test_no_decision_is_ever_appended_at_a_version_it_was_not_based_on_or_reused(): void
    {
        (new AdmitMemberHandler($this->store, new FakeAuthority()))->handle(self::admit('k1', 'A1', 1));
        $this->repo->beforeNextAppend(fn () => (new EndMembershipHandler($this->store, new FakeAuthority()))->handle(self::end('other', 'E-other', 4)));

        (new EndMembershipHandler($this->repo, new FakeAuthority()))->handle(self::end('mine', 'E-mine', 6));

        $decisions = array_column($this->repo->appendedDecisions, 'decision');
        $this->assertSame(count($decisions), count(array_unique(array_map('spl_object_id', $decisions))), 'every attempt carries a fresh decision');
        foreach ($this->repo->appendedDecisions as $k => $attempt) {
            if ($k === array_key_last($this->repo->appendedDecisions)) {
                continue;
            }
            $this->assertNotSame($attempt['versionAtAppend'], $attempt['decision']->basedOnVersion(), 'only the conflicting attempt is stale');
        }
    }

    public function test_creation_race_yields_one_member_and_the_loser_continues_on_it(): void
    {
        // INV-M: the other process creates the Member for P1 between our lookup and our create.
        $this->repo->beforeNextCreate(fn () => $this->store->create(TenantId::fromString('O1'), MemberId::fromString('M-winner'), PersonRef::fromString('P1')));

        $outcome = (new AdmitMemberHandler($this->repo, new FakeAuthority()))->handle(self::admit('k1', 'A1', 1, 'M-loser'));

        $this->assertTrue($outcome->isAppended());
        $this->assertSame('M-winner', $outcome->appended()->memberId()->value());
        $this->assertNull($this->store->load(TenantId::fromString('O1'), MemberId::fromString('M-loser')));
    }

    public function test_concurrent_identical_retries_record_one_fact(): void
    {
        // IDEM-3: both pass the pre-check; the second is caught atomically inside append.
        $authority = new FakeAuthority();
        (new AdmitMemberHandler($this->store, $authority))->handle(self::admit('k1', 'A1', 1));
        $this->repo->beforeNextAppend(fn () => (new EndMembershipHandler($this->store, new FakeAuthority()))->handle(self::end('same-key', 'E1', 5)));

        $outcome = (new EndMembershipHandler($this->repo, $authority))->handle(self::end('same-key', 'E1', 5));

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(2, $outcome->appended()->seq(), 'the original outcome');
        $this->assertSame(2, $this->member()->version(), 'one End only');
    }

    public function test_an_identical_retry_committed_between_the_pre_check_and_the_load_returns_the_original_outcome(): void
    {
        // IDEM-1 / IDEM-3, interleaving B (found by the real PostgreSQL race, PBDIGIT-260 T12): the same
        // command commits after this caller's idempotency pre-check but before its load. This caller must
        // still answer with the original Appended, not with DuplicateFactId.
        (new AdmitMemberHandler($this->store, new FakeAuthority()))->handle(self::admit('k1', 'A1', 1));
        $this->repo->beforeNextLoad(fn () => (new EndMembershipHandler($this->store, new FakeAuthority()))->handle(self::end('same-key', 'E1', 5)));

        $outcome = (new EndMembershipHandler($this->repo, new FakeAuthority()))->handle(self::end('same-key', 'E1', 5));

        $this->assertTrue($outcome->isAppended(), 'got ' . ($outcome->domainRefusal()?->value ?? $outcome->applicationRefusal()?->value ?? '?'));
        $this->assertSame(2, $outcome->appended()->seq());
        $this->assertSame(2, $this->member()->version());
    }

    public function test_retries_are_bounded(): void
    {
        $alwaysConflicting = new class ($this->store) implements MemberRepositoryPort {
            public int $appends = 0;

            public function __construct(private readonly MemberRepositoryPort $inner)
            {
            }

            public function load(TenantId $t, MemberId $id): ?Member { return $this->inner->load($t, $id); }
            public function findByHolder(TenantId $t, PersonRef $h): ?Member { return $this->inner->findByHolder($t, $h); }
            public function create(TenantId $t, MemberId $id, PersonRef $h): CreateResult { return $this->inner->create($t, $id, $h); }
            public function appliedCommand(TenantId $t, IdempotencyKey $k): ?AppliedCommand { return $this->inner->appliedCommand($t, $k); }
            public function append(TenantId $t, MemberId $id, Decision $d, IdempotencyKey $k, string $fp): AppendResult
            {
                $this->appends++;

                return AppendResult::failed(AppendOutcome::ConcurrencyConflict);
            }
        };
        $authority = new FakeAuthority();

        $outcome = (new AdmitMemberHandler($alwaysConflicting, $authority))->handle(self::admit('k1', 'A1', 1));

        $this->assertSame(ApplicationRefusal::ConcurrentModification, $outcome->applicationRefusal());
        $this->assertSame(DecideAndAppend::MAX_ATTEMPTS, $alwaysConflicting->appends);
        $this->assertSame(DecideAndAppend::MAX_ATTEMPTS, $authority->calls);
    }
}
