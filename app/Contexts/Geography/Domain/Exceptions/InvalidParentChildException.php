<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Exceptions;

use RuntimeException;

class InvalidParentChildException extends RuntimeException
{
    public static function invalidRelationship(int $childId, int $parentId, int $level): self
    {
        return new self(
            sprintf(
                'Geography unit ID %d at level %d is not a child of parent ID %d.',
                $childId,
                $level,
                $parentId
            )
        );
    }

    public static function sameUnitAsParent(int $unitId): self
    {
        return new self(
            sprintf(
                'Geography unit ID %d cannot be its own parent (circular reference).',
                $unitId
            )
        );
    }

    public static function circularReference(int $unitId, int $ancestorId): self
    {
        return new self(
            sprintf(
                'Geography unit ID %d creates a circular reference with ancestor ID %d.',
                $unitId,
                $ancestorId
            )
        );
    }
}