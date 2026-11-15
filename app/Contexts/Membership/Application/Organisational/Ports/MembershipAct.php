<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/**
 * The membership acts an actor may be authorised for (Slice 1a: the two Slice 0 facts; S2-1 adds Renew).
 * `renew` is Governance's act kind `renew-membership` (D-163).
 */
enum MembershipAct: string
{
    case Admit = 'admit';
    case End = 'end';
    case Renew = 'renew';
}
