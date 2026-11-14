<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Import;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;

/**
 * G-8 (D-226 Q-G-1, D-232): an evidenced dataset, parsed strictly from its manifest (JSON):
 *   { dataset, version, evidence: [ {id, authority, issued_on, url, publication, retrieved_at, file, sha256, reuse_terms} ],
 *     claims: [ {slot: {kind, <label>: <value>|"@<entry key>", …}, value|null, from, to?, basis, evidence} ] }
 * Every slot is built by the domain's own SlotKey rules (so an import can express nothing the domain would refuse);
 * "@key" names an entry (EntryKeys); every claim cites declared evidence. Claim and revision ids are deterministic,
 * so one dataset version is one revision. Nothing here judges the evidence: it is proposed.
 */
final readonly class EvidencedDataset
{
    private const EVIDENCE_FIELDS = ['id', 'authority', 'issued_on', 'url', 'publication', 'retrieved_at', 'file', 'sha256', 'reuse_terms'];

    /**
     * @param list<DatasetEvidence> $evidence
     * @param list<Claim> $claims
     */
    private function __construct(
        public string $dataset,
        public string $version,
        public RevisionId $revisionId,
        public array $evidence,
        public array $claims,
    ) {
    }

    public static function fromJson(string $json): self
    {
        try {
            $m = json_decode($json, true, 64, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new DatasetRefused('The manifest is not valid JSON: ' . $e->getMessage());
        }
        foreach (['dataset', 'version'] as $field) {
            if (!is_string($m[$field] ?? null) || trim($m[$field]) === '') {
                throw new DatasetRefused("The manifest names its {$field}.");
            }
        }
        $evidence = [];
        foreach ($m['evidence'] ?? [] as $i => $e) {
            foreach (self::EVIDENCE_FIELDS as $field) {
                if (!is_string($e[$field] ?? null) || trim($e[$field]) === '') {
                    throw new DatasetRefused('Evidence ' . ($i + 1) . " lacks {$field}: every source records its authority, url, publication, retrieval, file, sha256 and reuse_terms.");
                }
            }
            if (preg_match('/^[0-9a-f]{64}$/', $e['sha256']) !== 1) {
                throw new DatasetRefused('Evidence ' . ($i + 1) . ': sha256 is a lowercase 64-hex SHA-256.');
            }
            if (isset($evidence[$e['id']])) {
                throw new DatasetRefused("Evidence {$e['id']}: duplicate id.");
            }
            $evidence[$e['id']] = new DatasetEvidence($e['id'], $e['authority'], $e['issued_on'], $e['url'], $e['publication'], $e['retrieved_at'], $e['file'], $e['sha256'], $e['reuse_terms']);
        }
        $claims = [];
        foreach ($m['claims'] ?? [] as $i => $c) {
            $n = $i + 1;
            if (!isset($evidence[$c['evidence'] ?? ''])) {
                throw new DatasetRefused("Claim {$n} cites undeclared evidence '" . ($c['evidence'] ?? '') . "'.");
            }
            try {
                $basisKind = BasisKind::tryFrom((string) ($c['basis'] ?? '')) ?? throw new \InvalidArgumentException("unknown basis '" . ($c['basis'] ?? '') . "'");
                $claims[] = new Claim(
                    EntryKeys::claimId($m['dataset'], $m['version'], $n),
                    self::slot($c['slot'] ?? []),
                    ($c['value'] ?? null) === null ? ClaimValue::none() : ClaimValue::of((string) $c['value']),
                    new ValidInterval(ValidDate::fromString((string) ($c['from'] ?? '')), isset($c['to']) ? ValidDate::fromString((string) $c['to']) : null),
                    new Basis($basisKind, (string) $c['evidence']),
                );
            } catch (DatasetRefused $e) {
                throw new DatasetRefused("Claim {$n}: " . $e->getMessage());
            } catch (\InvalidArgumentException|\ValueError $e) {
                throw new DatasetRefused("Claim {$n}: " . $e->getMessage());
            }
        }

        return new self($m['dataset'], $m['version'], EntryKeys::revisionId($m['dataset'], $m['version']), array_values($evidence), $claims);
    }

    /** @param array<string, string> $slot {kind, label: value or "@key", …} → the domain's SlotKey (it validates) */
    private static function slot(array $slot): SlotKey
    {
        $kind = (string) ($slot['kind'] ?? '');
        unset($slot['kind']);
        $encoded = $kind . '|';
        foreach ($slot as $label => $value) {
            $value = (string) $value;
            if (str_starts_with($value, '@')) {
                $value = EntryKeys::unitId(substr($value, 1))->value();
            }
            $encoded .= $label . '=' . strlen($value) . ':' . $value . '|';
        }

        return SlotKey::decode($encoded);
    }
}
