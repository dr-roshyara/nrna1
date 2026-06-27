<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Exceptions;

use RuntimeException;

class MaxHierarchyDepthException extends RuntimeException
{
    public static function exceeded(int $currentDepth, int $maxDepth = 8): self
    {
        return new self(
            sprintf('Hierarchy depth %d exceeds maximum allowed depth of %d', $currentDepth, $maxDepth)
        );
    }
}