<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A publication's approval status as known at a position (v3.11 §8; design revision 3 §10). */
enum ApprovalStatusKind: string
{
    case Approved = 'approved';
    case ApprovalDefective = 'approval-defective';
    case Confirmed = 'confirmed';
    case Corrected = 'corrected';
    case FindingClosed = 'finding-closed';
}
