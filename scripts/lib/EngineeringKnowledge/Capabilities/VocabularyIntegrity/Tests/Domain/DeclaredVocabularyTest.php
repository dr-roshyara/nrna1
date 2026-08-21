<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/** S3 — the declared vocabulary a config supplies: retired terms + collision label. */
final class DeclaredVocabularyTest extends TestCase
{
    public function test_it_carries_retired_terms_and_the_declaration_label(): void
    {
        $vocabulary = DeclaredVocabulary::of(['Phase 2b'], 'DI-7');

        self::assertSame(['Phase 2b'], $vocabulary->retiredTerms());
        self::assertSame('DI-7', $vocabulary->confusableDeclarationLabel());
    }

    public function test_the_declaration_label_is_required(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DeclaredVocabulary::of(['Phase 2b'], '');
    }

    public function test_empty_and_duplicate_retired_terms_are_normalised_away(): void
    {
        $vocabulary = DeclaredVocabulary::of(['Phase 2b', ' ', 'Phase 2b'], 'DI-7');

        self::assertSame(['Phase 2b'], $vocabulary->retiredTerms());
    }
}
