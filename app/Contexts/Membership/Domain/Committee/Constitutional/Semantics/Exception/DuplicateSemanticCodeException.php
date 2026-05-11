<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception;

final class DuplicateSemanticCodeException extends \DomainException
{
    public function __construct(string $code)
    {
        parent::__construct("Semantic code '{$code}' is already registered. Semantic codes must be unique.");
    }
}
