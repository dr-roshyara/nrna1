<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\SplitDeclaration;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * The value object that carries one observed split declaration (S5).
 *
 * A split declaration is a document line declaring a disposition to be TWO-BRANCH —
 * "THE MISMATCH DISPOSITION IS SPLIT … never one branch for both." The heuristic competes
 * disposition rows against these lines by shared trigger tokens.
 */
final class SplitDeclarationTest extends TestCase
{
    public function test_constructor_guards_an_empty_text(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SplitDeclaration::of('', 1, '4.4');
    }

    public function test_constructor_guards_a_non_positive_line(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SplitDeclaration::of('text', 0, '4.4');
    }

    public function test_constructor_guards_an_empty_section(): void
    {
        $this->expectException(InvalidArgumentException::class);

        SplitDeclaration::of('text', 1, '');
    }

    public function test_exposes_its_parts(): void
    {
        $declaration = SplitDeclaration::of('THE DISPOSITION IS SPLIT.', 11, '4.4');

        self::assertSame('THE DISPOSITION IS SPLIT.', $declaration->text());
        self::assertSame(11, $declaration->line());
        self::assertSame('4.4', $declaration->section());
    }
}
