<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A second-person approval (v3.11 §8): it names the content version and the roster version it was given under.
 * Whether the approver was eligible and independent is G-3's question (PublicationGovernance), not the Revision's.
 */
final readonly class Approval
{
    public function __construct(public int $contentVersion, public int $rosterVersion, public PersonReference $by)
    {
        if ($contentVersion < 1 || $rosterVersion < 1) {
            throw new \InvalidArgumentException('Content and roster versions are positive.');
        }
    }
}
