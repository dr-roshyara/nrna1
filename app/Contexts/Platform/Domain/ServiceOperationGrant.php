<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * The organisation's acceptance of the service terms, recorded by Platform (D-166; D-193 §3.3). It permits only
 * technical operations (provisioning, migrations, backups, restores, health checks), never a human reading content.
 * Immutable once recorded; it ends only with service termination (a later release).
 *
 * Evidence kept (§3.3): terms version, date, the accepting person (a PersonRef once claimed, or the applicant's
 * account) and the founding documents' fingerprints.
 */
final readonly class ServiceOperationGrant
{
    /** @param list<string> $foundingDocumentFingerprints SHA-256, lower-case hex */
    public function __construct(
        public string $termsVersion,
        public \DateTimeImmutable $acceptedAt,
        public string $acceptedBy,
        public array $foundingDocumentFingerprints,
    ) {
        if (trim($termsVersion) === '') {
            throw new \InvalidArgumentException('A service-operation grant needs the terms version.');
        }
        if (trim($acceptedBy) === '') {
            throw new \InvalidArgumentException('A service-operation grant needs the accepting person.');
        }
        if ($foundingDocumentFingerprints === []) {
            throw new \InvalidArgumentException('A service-operation grant needs the founding documents\' fingerprints.');
        }
        foreach ($foundingDocumentFingerprints as $fp) {
            if (!is_string($fp) || preg_match('/^[0-9a-f]{64}$/', $fp) !== 1) {
                throw new \InvalidArgumentException('A founding-document fingerprint must be a SHA-256 hex digest.');
            }
        }
    }
}
