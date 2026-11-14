<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The D-90 governance notices (v3.11 §8; design revision 3 §4). */
enum NoticeKind: string
{
    case Finding = 'finding';
    case Confirmation = 'confirmation';
    case Closure = 'closure';
}
