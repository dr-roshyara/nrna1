<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/** I-1: the Election Committee consists of at least three members (EM-GOV-033). */
final class CommitteeTooSmall extends DomainException
{
    public static function withSize(int $size): self
    {
        return new self(sprintf(
            'The Election Committee shall consist of at least three eligible, independent members (EM-GOV-033); %d given.',
            $size,
        ));
    }
}
