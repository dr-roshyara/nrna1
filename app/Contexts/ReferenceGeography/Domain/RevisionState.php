<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Revision lifecycle (v3.11 §5.3, §16). There is no "approved" state: approvals are records naming a content
 * version, counted at publication (§8). Publication itself (and its state) arrives with the ledger (G-4).
 */
enum RevisionState: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Stale = 'stale';
    case Withdrawn = 'withdrawn';
}
