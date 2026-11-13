<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\RevisionAlreadyPublished;
use App\Contexts\ReferenceGeography\Application\RevisionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ContentVersion;
use App\Contexts\ReferenceGeography\Domain\Dependency;
use App\Contexts\ReferenceGeography\Domain\DependencySet;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\FamilyDependency;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PriorValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionSnapshot;
use App\Contexts\ReferenceGeography\Domain\RevisionState;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\SubjectFamilyDependency;
use App\Contexts\ReferenceGeography\Domain\ValidationResult;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Domain\Violation;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;

/**
 * The Revision aggregate in PostgreSQL (G-4d; completion §4; G-4 interpretation §3).
 *
 * - A save is ONE transaction: the revision row (optimistic, by row_version), the parts being prepared (replaced),
 *   the parts that only grow (appended; the stored ones must be a prefix of the aggregate's), and, for a submitted or
 *   stale version, its claims frozen in refgeo_claims (immutable, G-4a). Any failure rolls everything back.
 * - A load maps the rows to a RevisionSnapshot and rebuilds ONLY through Revision::fromSnapshot. Any failure there or
 *   in the mapping (an unknown value, a missing field, a broken digest) is StoredRevisionCorrupt: never repaired.
 */
final class PostgresRevisionRepository implements RevisionRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function add(Revision $revision): void
    {
        $s = $revision->snapshot();
        $this->db->transaction(function () use ($s): void {
            try {
                $this->db->table('refgeo_revisions')->insert(['id' => $s->id->value(), 'state' => $s->state->value, 'row_version' => 1]);
            } catch (QueryException $e) {
                if (($e->errorInfo[0] ?? '') === '23505') {
                    throw new RevisionChangedConcurrently($s->id->value());
                }
                throw $e;
            }
            $this->writeParts($s);
        });
    }

    public function save(Revision $revision, int $expectedRowVersion): int
    {
        $s = $revision->snapshot();

        return $this->db->transaction(function () use ($s, $expectedRowVersion): int {
            if ($this->isPublished($s->id->value())) {
                throw new RevisionAlreadyPublished($s->id->value());
            }
            try {
                $updated = $this->db->update(
                    'UPDATE refgeo_revisions SET state = ?, row_version = row_version + 1, updated_at = clock_timestamp() WHERE id = ? AND row_version = ?',
                    [$s->state->value, $s->id->value(), $expectedRowVersion],
                );
            } catch (QueryException $e) {
                // a publication committed while this save waited for the revision row (the G-4f trigger)
                if (str_contains($e->getMessage(), 'REVISION_ALREADY_PUBLISHED')) {
                    throw new RevisionAlreadyPublished($s->id->value());
                }
                throw $e;
            }
            if ($updated !== 1) {
                throw new RevisionChangedConcurrently($s->id->value());
            }
            $this->writeParts($s);

            return $expectedRowVersion + 1;
        });
    }

    public function get(RevisionId $id): StoredRevision
    {
        $row = $this->db->selectOne('SELECT state, row_version FROM refgeo_revisions WHERE id = ?', [$id->value()]);
        if ($row === null) {
            throw new \OutOfBoundsException("No revision {$id->value()}.");
        }
        try {
            $revision = Revision::fromSnapshot($this->snapshotOf($id, $row->state));
        } catch (\Throwable $e) {
            throw new StoredRevisionCorrupt($id->value(), $e);
        }

        return new StoredRevision($revision, (int) $row->row_version, $this->isPublished($id->value()));
    }

    private function isPublished(string $id): bool
    {
        return $this->db->table('refgeo_publications')->where('revision_id', $id)->exists();
    }

    // --- writing ---------------------------------------------------------------------------------------------

    private function writeParts(RevisionSnapshot $s): void
    {
        $id = $s->id->value();
        foreach (['refgeo_revision_claims', 'refgeo_revision_evidence', 'refgeo_assessments', 'refgeo_revision_validations', 'refgeo_dependency_predicates'] as $table) {
            $this->db->table($table)->where('revision_id', $id)->delete();
        }
        foreach ($s->claims as $i => $c) {
            $this->db->table('refgeo_revision_claims')->insert(['revision_id' => $id, 'claim_id' => $c->id->value(), 'seq' => $i, ...self::claimColumns($c)]);
        }
        foreach ($s->evidence as $i => $e) {
            $this->db->table('refgeo_revision_evidence')->insert([
                'revision_id' => $id, 'evidence_id' => $e->id, 'seq' => $i, 'fingerprint' => $e->fingerprint, 'issuer' => $e->issuer,
                'issued_on' => $e->issuedOn?->value(), 'mandates' => json_encode($e->mandates, JSON_THROW_ON_ERROR), 'precedence_rules' => json_encode($e->precedenceRules, JSON_THROW_ON_ERROR),
            ]);
        }
        foreach ($s->assessments as $i => $a) {
            $this->db->table('refgeo_assessments')->insert(['revision_id' => $id, 'seq' => $i, 'kind' => $a->kind->value, 'outcome' => $a->outcome, 'by_person' => $a->by->value()]);
        }
        if ($s->validation !== null) {
            $this->writeValidation($id, $s->validation);
        }
        $this->appendOnly('refgeo_approvals', $id, array_map(static fn (array $a): array => ['content_version' => $a[0], 'roster_version' => $a[1]->rosterVersion, 'by_person' => $a[1]->by->value()], $s->approvals));
        $this->appendOnly('refgeo_judgment_flags', $id, array_map(static fn (array $f): array => ['by_person' => $f[0]->value(), 'reason' => $f[1]->value], $s->judgmentFlags));
        foreach (['preparer' => $s->preparers, 'excluded' => $s->excluded] as $role => $persons) {
            $stored = array_map(static fn (object $r): string => trim($r->person), $this->db->select('SELECT person FROM refgeo_revision_persons WHERE revision_id = ? AND role = ? ORDER BY seq', [$id, $role]));
            $this->requirePrefix($stored, array_map(static fn (PersonReference $p): string => $p->value(), $persons), "refgeo_revision_persons ({$role})", $id);
            foreach (array_slice($persons, count($stored), null, true) as $i => $p) {
                $this->db->table('refgeo_revision_persons')->insert(['revision_id' => $id, 'role' => $role, 'seq' => $i, 'person' => $p->value()]);
            }
        }
        if ($s->current !== null && $s->assessor !== null
            && !$this->db->table('refgeo_revision_versions')->where('revision_id', $id)->where('content_version', $s->current->number)->exists()) {
            $this->db->table('refgeo_revision_versions')->insert(['revision_id' => $id, 'content_version' => $s->current->number, 'digest' => $s->current->digest, 'assessor' => $s->assessor->value()]);
        }
        if ($s->current !== null && in_array($s->state, [RevisionState::Submitted, RevisionState::Stale], true)
            && !$this->db->table('refgeo_claims')->where('revision_id', $id)->where('content_version', $s->current->number)->exists()) {
            foreach ($s->claims as $c) {
                $this->db->table('refgeo_claims')->insert(['claim_id' => $c->id->value(), 'revision_id' => $id, 'content_version' => $s->current->number, 'kind' => $c->slot->kind()->value,
                    ...self::claimColumns($c), 'before_image' => self::beforeImageJson($s->validation?->beforeImageOf($c->id) ?? throw new \LogicException('A submitted version is validated.'))]);
            }
        }
    }

    /** @param list<array<string, mixed>> $rows the aggregate's full list; the stored rows must be its prefix */
    private function appendOnly(string $table, string $id, array $rows): void
    {
        $columns = array_keys($rows[0] ?? []);
        $stored = array_map(static fn (object $r): array => array_map(static fn ($v) => is_string($v) ? trim($v) : $v, (array) $r),
            $columns === [] ? [] : $this->db->select('SELECT ' . implode(', ', $columns) . " FROM {$table} WHERE revision_id = ? ORDER BY seq", [$id]));
        $this->requirePrefix($stored, $rows, $table, $id);
        foreach (array_slice($rows, count($stored), null, true) as $i => $row) {
            $this->db->table($table)->insert(['revision_id' => $id, 'seq' => $i, ...$row]);
        }
    }

    private function requirePrefix(array $stored, array $all, string $what, string $id): void
    {
        if (array_slice($all, 0, count($stored)) != $stored) {
            throw new \RuntimeException("{$what} of {$id}: the stored history is not a prefix of the aggregate's; it only grows.");
        }
    }

    private function writeValidation(string $id, ValidationResult $v): void
    {
        $images = [];
        foreach ($v->beforeImages as $claimId => $image) {
            $images[$claimId] = self::segmentsOut($image);
        }
        $this->db->table('refgeo_revision_validations')->insert([
            'revision_id' => $id, 'p0' => $v->dependencies->p0, 'structural' => $v->structural,
            'violations' => json_encode(array_map(static fn (Violation $x): array => [$x->rule, $x->claim->value(), $x->detail], $v->violations), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'before_images' => json_encode((object) $images, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'disputed' => json_encode(array_map(static fn (ClaimId $c): string => $c->value(), $v->disputedReliances), JSON_THROW_ON_ERROR),
        ]);
        foreach ($v->dependencies->dependencies as $i => $d) {
            $this->db->table('refgeo_dependency_predicates')->insert(['revision_id' => $id, 'seq' => $i, ...self::dependencyColumns($d)]);
        }
    }

    /** @return array<string, mixed> */
    private static function dependencyColumns(Dependency $d): array
    {
        return match (true) {
            $d instanceof SlotDependency => ['form' => 'slot', 'slot_key' => $d->slot->encoded(), 'valid_from' => $d->interval->from->value(), 'valid_to' => $d->interval->to?->value()],
            $d instanceof FamilyDependency => ['form' => 'family', 'kind' => $d->kind->value, 'fixed' => json_encode((object) $d->fixed, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'value' => $d->value, 'valid_from' => $d->interval->from->value(), 'valid_to' => $d->interval->to?->value()],
            $d instanceof SubjectFamilyDependency => ['form' => 'subject-family', 'subject' => $d->subject->value()],
            default => throw new \LogicException('An unknown dependency form cannot be stored: ' . $d::class),
        };
    }

    /** @return array<string, mixed> */
    private static function claimColumns(Claim $c): array
    {
        return ['slot_key' => $c->slot->encoded(), 'value' => $c->value->isNone() ? null : $c->value->value(), 'valid_from' => $c->interval->from->value(),
            'valid_to' => $c->interval->to?->value(), 'basis_kind' => $c->basis->kind->value, 'basis_reference' => $c->basis->reference];
    }

    // --- reading (every failure here becomes StoredRevisionCorrupt in get()) --------------------------------------

    private function snapshotOf(RevisionId $id, string $state): RevisionSnapshot
    {
        $rid = $id->value();
        $person = static fn (string $v): PersonReference => PersonReference::fromString(trim($v));
        $versions = $this->db->select('SELECT content_version, digest, assessor FROM refgeo_revision_versions WHERE revision_id = ? ORDER BY content_version DESC LIMIT 1', [$rid]);
        $validation = $this->db->selectOne('SELECT p0, structural, violations::text AS violations, before_images::text AS before_images, disputed::text AS disputed FROM refgeo_revision_validations WHERE revision_id = ?', [$rid]);

        return new RevisionSnapshot(
            $id,
            RevisionState::from($state),
            array_map(static fn (object $r): Claim => self::claimOf($r), $this->db->select('SELECT * FROM refgeo_revision_claims WHERE revision_id = ? ORDER BY seq, claim_id', [$rid])),
            array_map(static fn (object $r): EvidenceVersion => new EvidenceVersion($r->evidence_id, trim($r->fingerprint), $r->issuer,
                $r->issued_on === null ? null : ValidDate::fromString($r->issued_on), self::strings($r->mandates), self::strings($r->precedence_rules)),
                $this->db->select('SELECT evidence_id, fingerprint, issuer, issued_on, mandates::text AS mandates, precedence_rules::text AS precedence_rules FROM refgeo_revision_evidence WHERE revision_id = ? ORDER BY seq', [$rid])),
            array_map(static fn (object $r): Assessment => new Assessment(AssessmentKind::from($r->kind), $r->outcome, $person($r->by_person)),
                $this->db->select('SELECT kind, outcome, by_person FROM refgeo_assessments WHERE revision_id = ? ORDER BY seq', [$rid])),
            array_map(static fn (object $r): array => [(int) $r->content_version, new Approval((int) $r->content_version, (int) $r->roster_version, $person($r->by_person))],
                $this->db->select('SELECT content_version, roster_version, by_person FROM refgeo_approvals WHERE revision_id = ? ORDER BY seq', [$rid])),
            array_map(static fn (object $r): PersonReference => $person($r->person), $this->db->select("SELECT person FROM refgeo_revision_persons WHERE revision_id = ? AND role = 'preparer' ORDER BY seq", [$rid])),
            array_map(static fn (object $r): PersonReference => $person($r->person), $this->db->select("SELECT person FROM refgeo_revision_persons WHERE revision_id = ? AND role = 'excluded' ORDER BY seq", [$rid])),
            $versions === [] ? null : new ContentVersion((int) $versions[0]->content_version, trim($versions[0]->digest)),
            $versions === [] ? null : $person($versions[0]->assessor),
            array_map(static fn (object $r): array => [$person($r->by_person), JudgmentReason::from($r->reason)],
                $this->db->select('SELECT by_person, reason FROM refgeo_judgment_flags WHERE revision_id = ? ORDER BY seq', [$rid])),
            $validation === null ? null : $this->validationOf($rid, $validation),
        );
    }

    private function validationOf(string $rid, object $row): ValidationResult
    {
        $images = [];
        foreach (json_decode($row->before_images, true, 8, JSON_THROW_ON_ERROR) as $claimId => $segments) {
            $images[(string) $claimId] = self::segmentsIn($segments);
        }

        return new ValidationResult(
            array_map(static fn (array $x): Violation => new Violation((string) $x[0], ClaimId::fromString((string) $x[1]), (string) $x[2]), json_decode($row->violations, true, 4, JSON_THROW_ON_ERROR)),
            new DependencySet((int) $row->p0, array_map(static fn (object $d): Dependency => self::dependencyOf($d),
                $this->db->select('SELECT form, slot_key, kind, fixed::text AS fixed, value, subject, valid_from, valid_to FROM refgeo_dependency_predicates WHERE revision_id = ? ORDER BY seq', [$rid]))),
            $images,
            (bool) $row->structural,
            array_map(static fn (string $c): ClaimId => ClaimId::fromString($c), json_decode($row->disputed, true, 2, JSON_THROW_ON_ERROR)),
        );
    }

    private static function dependencyOf(object $d): Dependency
    {
        $need = static fn (mixed $v, string $what): mixed => $v ?? throw new \InvalidArgumentException("A {$d->form} dependency record lacks its {$what}.");

        return match ($d->form) {
            'slot' => new SlotDependency(SlotKey::decode($need($d->slot_key, 'slot key')), self::interval($need($d->valid_from, 'interval'), $d->valid_to)),
            'family' => FamilyDependency::of(ClaimKind::from($need($d->kind, 'kind')), json_decode($need($d->fixed, 'fixed components'), true, 2, JSON_THROW_ON_ERROR),
                $need($d->value, 'value'), self::interval($need($d->valid_from, 'interval'), $d->valid_to)),
            'subject-family' => new SubjectFamilyDependency(ReferenceUnitId::fromString($need($d->subject, 'subject'))),
            default => throw new \InvalidArgumentException("Unknown dependency form '{$d->form}'."),
        };
    }

    public static function claimOf(object $r): Claim
    {
        return new Claim(ClaimId::fromString($r->claim_id), SlotKey::decode($r->slot_key), $r->value === null ? ClaimValue::none() : ClaimValue::of($r->value),
            self::interval($r->valid_from, $r->valid_to), new Basis(BasisKind::from($r->basis_kind), $r->basis_reference));
    }

    /** A before-image as stored (refgeo_claims.before_image; the validation's before_images): [[from, to, kind, value], …]. */
    public static function beforeImageJson(BeforeImage $image): string
    {
        return json_encode(self::segmentsOut($image), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

    public static function beforeImageFromJson(string $json): BeforeImage
    {
        return self::segmentsIn(json_decode($json, true, 4, JSON_THROW_ON_ERROR));
    }

    /** @return list<array{string, ?string, string, ?string}> */
    private static function segmentsOut(BeforeImage $image): array
    {
        return array_map(static fn (array $seg): array => [
            $seg[0]->from->value(), $seg[0]->to?->value(),
            $seg[1]->isAbsent() ? 'absent' : ($seg[1]->isNone() ? 'none' : 'value'),
            $seg[1]->isSomeValue() ? $seg[1]->describe() : null,
        ], $image->segments());
    }

    private static function segmentsIn(array $segments): BeforeImage
    {
        return BeforeImage::fromSegments(array_map(static fn (array $s): array => [
            self::interval($s[0], $s[1]),
            match ($s[2]) {
                'absent' => PriorValue::absent(),
                'none' => PriorValue::none(),
                'value' => PriorValue::of((string) ($s[3] ?? throw new \InvalidArgumentException('A value segment names its value.'))),
            },
        ], $segments));
    }

    private static function interval(string $from, ?string $to): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    /** @return list<string> */
    private static function strings(string $json): array
    {
        return array_map('strval', json_decode($json, true, 2, JSON_THROW_ON_ERROR));
    }
}
