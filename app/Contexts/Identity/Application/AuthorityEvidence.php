<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §10.1: Governance's complete AuthorityCheck answer, passed BY VALUE in-process by the organisation's own
 * server-side application layer (ISO-1); never built from client input. Identity's own consumer-owned shape of the
 * answer (fields as Governance's ratified answer: outcome, evaluatedFor, competenceKind, basis; plus the request's
 * actor, act kind, subject (`requestRef`) and request identifier, and `checkedAt`).
 *
 * Identity verifies correspondence and trust only (AuthorityEvidenceCheck), never competence, and persists NOTHING of
 * it except the request identifier (ConsumedAuthorityRequest, PI-21). The organisation keeps the complete answer.
 */
final readonly class AuthorityEvidence
{
    /**
     * @param array<string, string> $subject  the requestRef subject identifiers (issue: entryRef, personRef, issuanceId;
     *                                        replace: claimId, newIssuanceId; revoke: claimId)
     * @param array<string, mixed>  $basis    Governance's basis, kept opaque and never stored centrally
     */
    public function __construct(
        public TenantId $organisation,
        public OrganisationPersonRef $actor,
        public string $actKind,
        public array $subject,
        public string $requestId,
        public string $outcome,
        public string $evaluatedFor,
        public \DateTimeImmutable $checkedAt,
        public ?string $competenceKind,
        public array $basis,
    ) {
        if (trim($requestId) === '') {
            throw new \InvalidArgumentException('Authority evidence needs its request identifier.');
        }
    }
}
