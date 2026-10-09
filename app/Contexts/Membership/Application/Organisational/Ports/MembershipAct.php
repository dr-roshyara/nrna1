<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/** The membership acts an actor may be authorised for (Slice 1a: the two Slice 0 facts). */
enum MembershipAct: string
{
    case Admit = 'admit';
    case End = 'end';
}
