<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Exceptions;

use App\Contexts\Membership\Domain\Exceptions\DomainKernelException;

final class CommitteeEligibilityException extends DomainKernelException
{
    // Canonical string codes for domain layer
    private const MISSING_RESIDENCE_GEO = 'MEMBER_NO_RESIDENCE_GEO';
    private const GEO_OUT_OF_SCOPE = 'GEO_OUT_OF_SCOPE';

    private string $domainCode;

    public static function missingResidenceGeo(): self
    {
        $exception = new self('Member has no residence geography assigned');
        $exception->domainCode = self::MISSING_RESIDENCE_GEO;
        return $exception;
    }

    public static function geoOutOfScope(): self
    {
        $exception = new self('Member residence geography is not within the committee\'s operational geography');
        $exception->domainCode = self::GEO_OUT_OF_SCOPE;
        return $exception;
    }

    public function code(): string
    {
        return $this->domainCode;
    }
}
