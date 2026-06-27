<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class ConstitutionalReason
{
    public const SEVERITY_DETERMINATIVE = 'determinative';
    public const SEVERITY_BINDING       = 'binding';
    public const SEVERITY_PERSUASIVE    = 'persuasive';
    public const IMPACT_VALID           = 'valid';
    public const IMPACT_QUESTIONABLE    = 'questionable';
    public const IMPACT_INVALID         = 'invalid';

    public function __construct(
        public string $code,
        public string $summary,
        public string $explanation,
        public array  $articleCodes      = [],
        public ConstitutionalSeverity $severity          = ConstitutionalSeverity::BINDING,
        public LegitimacyImpact $legitimacyImpact  = LegitimacyImpact::VALID,
    ) {}

    public static function resolved(string $code, string $summary): self
    {
        return new self($code, $summary, 'Authority resolved via ' . $code);
    }

    public static function noAuthority(): self
    {
        return new self(
            code: 'no_authority',
            summary: 'No authority found',
            explanation: 'No authority could be identified at the given time',
            articleCodes: [],
            severity: ConstitutionalSeverity::DETERMINATIVE,
            legitimacyImpact: LegitimacyImpact::INVALID,
        );
    }
}
