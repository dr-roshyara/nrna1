<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/**
 * The membership acts an actor may be authorised for (Slice 1a: the two Slice 0 facts; S2-1 adds Renew; S2-2 adds
 * Suspend and Restore). Governance's act kinds (D-163): `renew-membership`, `suspend-membership`, `restore-membership`.
 */
enum MembershipAct: string
{
    case Admit = 'admit';
    case End = 'end';
    case Renew = 'renew';
    case Suspend = 'suspend';
    case Restore = 'restore';
}
