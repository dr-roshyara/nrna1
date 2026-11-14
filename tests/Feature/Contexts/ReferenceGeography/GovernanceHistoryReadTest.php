<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\ConfirmPublication;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Published\ApprovalHistory;
use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\Published\KnowledgeNotAvailable;
use App\Contexts\ReferenceGeography\Application\Published\PublicationEntry;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * PBDIGIT-346 G-6c-6 (design revision 3 §10, §11; B10): the published read side of D-90.
 * - GeographyReadModel::approvalStatus(position, Knowledge): the status of a revision publication AS KNOWN AT the
 *   knowledge, from entries ≤ its position only, through FindingLifecycle (the one definition); every episode kept;
 * - the feed carries noticeKind, concernsPosition and correctsPosition (provenance only, never persons);
 * - reading never writes (queries run inside a READ ONLY transaction).
 * Consumer checkpoint atomicity is a CONSUMER obligation (G-5c contract), not tested here.
 */
final class GovernanceHistoryReadTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private static function model(): GeographyReadModel
    {
        return new PublishedGeography(new PostgresGeographyReadSource(DB::connection()), new PostgresPublicationLedger(DB::connection()));
    }

    private static function consumer(): IdentityNoticeConsumer
    {
        return new IdentityNoticeConsumer(new PostgresPublicationLedger(DB::connection()), new PublicationGovernance());
    }

    private static function confirm(string $actor, int $finding, string $digest): ?int
    {
        return (new ConfirmPublication(new PostgresPublicationLedger(DB::connection()), new PublicationGovernance(), new class (self::p($actor)) implements ActingPerson {
            public function __construct(private readonly PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person;
            }
        }))->confirm($finding, $digest)->position;
    }

    /** @param list<array{string, ?string}> $intervals */
    private static function existence(string $assessor, string $approver, ReferenceUnitId $unit, array $intervals): RevisionId
    {
        self::ensureCompetence();
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint($assessor, $approver);
        self::declare($assessor, $id);
        self::declare($approver, $id);
        $r = Revision::prepare($id, self::p($assessor));
        foreach ($intervals as [$from, $to]) {
            $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence($unit), ClaimValue::of('exists'),
                new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act')), self::p($assessor));
        }
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p($assessor));
        self::submitAndApprove($r, $assessor, $approver);

        return $id;
    }

    /** @return array{ReferenceUnitId, RevisionId, int} a publication of a fresh unit (assessed by p, approved by x) */
    private static function published(string $t): array
    {
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $id = self::existence("p-$t", "x-$t", $unit, [['2016-01-01', null]]);

        return [$unit, $id, self::publisher("p-$t")->publish($id)->position];
    }

    private static function findingOf(int $p, int $after = 0): int
    {
        return (int) DB::selectOne("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ? AND position > ? ORDER BY position LIMIT 1", [$p, $after])->position;
    }

    private static function statusOf(int $p, int $k): ?ApprovalHistory
    {
        return self::model()->approvalStatus($p, self::model()->knowledgeAt($k));
    }

    public function test_status_at_each_knowledge_position_through_finding_and_confirmation(): void
    {
        $t = Str::random(8);
        [, $id, $p] = self::published($t);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = self::findingOf($p);
        self::appoint("c-$t");
        self::declare("c-$t", $id);
        $c = self::confirm("c-$t", $f, self::repo()->get($id)->revision->currentVersion()->digest);

        $this->assertSame(['approved', []], [self::statusOf($p, $f - 1)->status, self::statusOf($p, $f - 1)->events], 'before the finding');
        $this->assertSame('approval-defective', self::statusOf($p, $f)->status);
        $this->assertSame('approval-defective', self::statusOf($p, $c - 1)->status, 'open until the confirmation');
        $at = self::statusOf($p, $c);
        $this->assertSame(['confirmed', null, [['position' => $f, 'event' => 'finding'], ['position' => $c, 'event' => 'confirmation']], $p, $c],
            [$at->status, $at->correctedAt, $at->events, $at->publication, $at->knownAt]);
        $this->assertEquals(self::statusOf($p, $c), self::statusOf($p, $c), 'deterministic');
    }

    public function test_closure_correction_and_a_new_episode_never_rewrite_an_earlier_answer(): void
    {
        $t = Str::random(8);
        [$unit, , $p] = self::published($t);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f1 = self::findingOf($p);
        self::consumer()->reversed("m-$t");
        $closure = self::lastPosition();
        self::consumer()->merged("m2-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f2 = self::findingOf($p, $f1);
        $snapshot = [self::statusOf($p, $f1), self::statusOf($p, $closure), self::statusOf($p, $f2)];
        $q = self::publisher("q-$t")->publish(self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]), corrects: $p)->position;

        $this->assertSame(['approval-defective', 'finding-closed', 'approval-defective'], array_map(static fn (ApprovalHistory $h) => $h->status, $snapshot));
        $this->assertEquals($snapshot, [self::statusOf($p, $f1), self::statusOf($p, $closure), self::statusOf($p, $f2)], 'a later correction never changes an earlier answer');
        $now = self::statusOf($p, $q);
        $this->assertSame(['corrected', $q], [$now->status, $now->correctedAt]);
        $this->assertSame([['position' => $f1, 'event' => 'finding'], ['position' => $closure, 'event' => 'closure'], ['position' => $f2, 'event' => 'finding'], ['position' => $q, 'event' => 'correction']],
            $now->events, 'every episode kept, in position order');
    }

    public function test_only_revision_publications_known_at_the_knowledge_have_a_status(): void
    {
        $t = Str::random(8);
        [, , $p] = self::published($t);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = self::findingOf($p);

        $this->assertNull(self::statusOf($p, $p - 1), 'not yet published at that knowledge');
        $this->assertNull(self::statusOf($f, $f), 'a notice has no approval status');
        $this->expectException(KnowledgeNotAvailable::class);
        self::model()->approvalStatus($p, new \App\Contexts\ReferenceGeography\Application\Published\Knowledge(self::lastPosition() + 5));
    }

    public function test_the_feed_carries_the_governance_relations_and_no_persons(): void
    {
        $t = Str::random(8);
        [$unit, , $p] = self::published($t);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = self::findingOf($p);
        $q = self::publisher("q-$t")->publish(self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]), corrects: $p)->position;
        $k = self::model()->knowledgeAt($q);

        $entries = self::model()->publicationsSince($p - 1, $k, 500);
        $byPosition = [];
        foreach ($entries as $e) {
            $byPosition[$e->position] = [$e->kind, $e->noticeKind, $e->concernsPosition, $e->correctsPosition];
        }
        $this->assertSame(['revision', null, null, null], $byPosition[$p]);
        $this->assertSame(['notice', 'finding', $p, null], $byPosition[$f]);
        $this->assertSame(['revision', null, null, $p], $byPosition[$q]);
        $this->assertSame(range($p, $q), array_map(static fn (PublicationEntry $e) => $e->position, $entries), 'ordered and gap-free');
        $this->assertEquals($entries, self::model()->publicationsSince($p - 1, $k, 500), 'a replay reads the same');
        $this->assertSame(['position', 'recordedAt', 'kind', 'revisionId', 'contentVersion', 'governanceRule', 'claimKinds', 'subjects', 'merges', 'noticeKind', 'concernsPosition', 'correctsPosition'],
            array_map(static fn (\ReflectionProperty $r) => $r->getName(), (new \ReflectionClass(PublicationEntry::class))->getProperties()), 'additive and in order; no person, no merge basis');
    }

    public function test_reading_never_writes(): void
    {
        $t = Str::random(8);
        [, , $p] = self::published($t);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $k = self::lastPosition();

        DB::beginTransaction();
        try {
            DB::statement('SET TRANSACTION READ ONLY');
            $source = new PostgresGeographyReadSource(DB::connection());
            $this->assertSame('approval-defective', (new PublishedGeography($source, new PostgresPublicationLedger(DB::connection())))->approvalStatus($p, new \App\Contexts\ReferenceGeography\Application\Published\Knowledge($k))->status);
            $this->assertNotSame([], $source->publicationsBetween($p - 1, $k, 10));
        } finally {
            DB::rollBack();
        }
    }
}
