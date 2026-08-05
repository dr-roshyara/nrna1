<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Application;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\SeriesContentsReader;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\SeriesContents;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * CAP-001 application service.
 *
 * The reader is a PORT so infrastructure is replaceable (markdown today, YAML or a
 * graph later) with the domain unchanged.
 *
 * DR-1 / AP-2: the reader must read the GOVERNED REGISTERS — never a projection.
 * AP-4:        no central registry is created; the reader observes what exists.
 */
final class ValidateIdentifierTest extends TestCase
{
    private function readerReturning(SeriesContents $contents): SeriesContentsReader
    {
        return new class($contents) implements SeriesContentsReader
        {
            public function __construct(private readonly SeriesContents $contents) {}

            public function read(IdentifierSeries $series): SeriesContents
            {
                return $this->contents;
            }
        };
    }

    private function governedR(array $minted, array $cited = []): SeriesContents
    {
        return SeriesContents::governed(
            new IdentifierSeries('R'),
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $minted),
            array_map(static fn (string $v): Identifier => Identifier::fromString($v), $cited),
        );
    }

    public function test_it_returns_pass_for_a_free_identifier(): void
    {
        $result = (new ValidateIdentifier($this->readerReturning($this->governedR(['R-64']))))
            ->handle('R-72');

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    public function test_it_returns_fail_for_a_minted_identifier(): void
    {
        $result = (new ValidateIdentifier($this->readerReturning($this->governedR(['R-65']))))
            ->handle('R-65');

        self::assertSame(Verdict::FAIL, $result->verdict());
    }

    public function test_it_returns_warn_for_a_cited_but_unminted_identifier(): void
    {
        $result = (new ValidateIdentifier($this->readerReturning($this->governedR(['R-66'], ['R-70']))))
            ->handle('R-70');

        self::assertSame(Verdict::WARN, $result->verdict());
    }

    /** Fail-closed: an unreadable register must not yield PASS. */
    public function test_unreadable_series_is_inconclusive_not_pass(): void
    {
        $reader = new class implements SeriesContentsReader
        {
            public function read(IdentifierSeries $series): SeriesContents
            {
                throw new RuntimeException('register unreadable');
            }
        };

        $result = (new ValidateIdentifier($reader))->handle('R-72');

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    public function test_result_carries_evidence(): void
    {
        $result = (new ValidateIdentifier($this->readerReturning($this->governedR(['R-64']))))
            ->handle('R-72');

        self::assertNotSame('', $result->evidence());
    }

    public function test_a_malformed_proposed_identifier_is_rejected_before_any_read(): void
    {
        $reader = new class implements SeriesContentsReader
        {
            public function read(IdentifierSeries $series): SeriesContents
            {
                throw new RuntimeException('the reader must never be called');
            }
        };

        $this->expectException(\InvalidArgumentException::class);

        (new ValidateIdentifier($reader))->handle('nonsense');
    }
}
