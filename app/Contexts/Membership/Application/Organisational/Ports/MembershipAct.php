<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/**
 * The membership acts an actor may be authorised for, by Governance's act-kind names (D-163, D-208 §2; PBDIGIT-354
 * S2-3a, D-238). Never stored: a fact records the answer's evidence, not the request.
 *
 * - End asks by its kind: `record-resignation` or `record-death`.
 * - **`exclude-member` is absent on purpose:** an exclusion is pushed by Governance with its Resolution part (C-1b,
 *   S2-6). No office exercises it, so Membership never asks for it.
 * - Admission's `place-member(unit)` belongs to Organisation's slice (D-210 note 3).
 */
enum MembershipAct: string
{
    case Admit = 'admit-member';
    case RecordResignation = 'record-resignation';
    case RecordDeath = 'record-death';
    case Renew = 'renew-membership';
    case Suspend = 'suspend-membership';
    case Restore = 'restore-membership';
}
