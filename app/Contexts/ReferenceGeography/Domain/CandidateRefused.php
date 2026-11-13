<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A Candidate act the lifecycle does not allow (v3.11 §16). */
final class CandidateRefused extends \DomainException
{
}
