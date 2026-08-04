<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\GovernedRegisterMap;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownSeriesContentsReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Infrastructure adapter tests — against a fixture repository, never the real one.
 *
 * ⛔ DR-1 / AP-2: the reader must read the GOVERNED REGISTER, never a projection.
 * ⛔ AP-4:        it OBSERVES an existing register; it creates no source of truth.
 *
 * These tests also pin the two behaviours the first live run depended on:
 * a register SPLIT ACROSS FILES, and an ungoverned series returning INCONCLUSIVE
 * rather than an empty PASS.
 */
final class MarkdownSeriesContentsReaderTest extends TestCase
{
    private string $root;

    protected function setUp(): void
    {
        $this->root = sys_get_temp_dir().'/ii-'.bin2hex(random_bytes(6));
        mkdir($this->root.'/regs', 0777, true);
        mkdir($this->root.'/corpus', 0777, true);
        mkdir($this->root.'/schema', 0777, true);
    }

    protected function tearDown(): void
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->root, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? rmdir($f->getPathname()) : unlink($f->getPathname());
        }
        rmdir($this->root);
    }

    private function writeConfig(array $files): void
    {
        $list = implode("\n", array_map(static fn (string $f): string => "      - {$f}", $files));
        file_put_contents($this->root.'/schema/governed-registers.yaml', <<<YAML
        version: 1
        registers:
          R:
            label: test
            ref: fixture
            files:
        {$list}
        YAML);
    }

    private function reader(): MarkdownSeriesContentsReader
    {
        return new MarkdownSeriesContentsReader(
            new GovernedRegisterMap($this->root, 'schema/governed-registers.yaml'),
            $this->root,
            ['corpus'],
        );
    }

    public function test_it_reads_minted_identifiers_from_register_table_rows(): void
    {
        file_put_contents($this->root.'/regs/a.md', "| # | Ruling |\n| R-30 | first |\n| R-31 | second |\n");
        $this->writeConfig(['regs/a.md']);

        $contents = $this->reader()->read(new IdentifierSeries('R'));

        self::assertTrue($contents->isGoverned());
        self::assertSame(2, $contents->mintedCount());
        self::assertTrue($contents->containsMinted(Identifier::fromString('R-30')));
    }

    public function test_it_reads_minted_identifiers_from_headings(): void
    {
        file_put_contents($this->root.'/regs/a.md', "## R-40 — a ruling\n### R-41 another\n");
        $this->writeConfig(['regs/a.md']);

        self::assertSame(2, $this->reader()->read(new IdentifierSeries('R'))->mintedCount());
    }

    /** The live finding: the R register is split across two files. */
    public function test_it_merges_a_register_split_across_files(): void
    {
        file_put_contents($this->root.'/regs/a.md', "| R-30 | new home |\n");
        file_put_contents($this->root.'/regs/b.md', "| R-1 | sealed |\n| R-2 | sealed |\n");
        $this->writeConfig(['regs/a.md', 'regs/b.md']);

        $contents = $this->reader()->read(new IdentifierSeries('R'));

        self::assertSame(3, $contents->mintedCount());
        self::assertTrue($contents->containsMinted(Identifier::fromString('R-1')));
        self::assertTrue($contents->containsMinted(Identifier::fromString('R-30')));
    }

    /** The R-70/R-71 case: cited in the corpus, absent from the register. */
    public function test_it_reports_cited_but_unminted_identifiers(): void
    {
        file_put_contents($this->root.'/regs/a.md', "| R-30 | minted |\n");
        file_put_contents($this->root.'/corpus/doc.md', "We rely on R-70 and R-71 and R-30.\n");
        $this->writeConfig(['regs/a.md']);

        $contents = $this->reader()->read(new IdentifierSeries('R'));

        self::assertSame(2, $contents->citedCount());
        self::assertTrue($contents->containsCitedButUnminted(Identifier::fromString('R-70')));
        self::assertFalse($contents->containsCitedButUnminted(Identifier::fromString('R-30')));
    }

    public function test_an_unconfigured_series_is_ungoverned_not_empty(): void
    {
        $this->writeConfig(['regs/a.md']);
        file_put_contents($this->root.'/regs/a.md', "| R-30 | x |\n");

        $contents = $this->reader()->read(new IdentifierSeries('ZZ'));

        self::assertFalse($contents->isGoverned());
    }

    public function test_a_missing_register_file_throws_rather_than_reporting_empty(): void
    {
        $this->writeConfig(['regs/absent.md']);

        $this->expectException(RuntimeException::class);

        $this->reader()->read(new IdentifierSeries('R'));
    }

    public function test_missing_configuration_throws(): void
    {
        $reader = new MarkdownSeriesContentsReader(
            new GovernedRegisterMap($this->root, 'schema/nope.yaml'),
            $this->root,
            ['corpus'],
        );

        $this->expectException(RuntimeException::class);

        $reader->read(new IdentifierSeries('R'));
    }

    /** Configuration is data: adding a register must not require a code change. */
    public function test_adding_a_register_is_a_configuration_edit(): void
    {
        file_put_contents($this->root.'/regs/a.md', "| R-30 | x |\n");
        $this->writeConfig(['regs/a.md']);

        self::assertSame(['R'], (new GovernedRegisterMap($this->root, 'schema/governed-registers.yaml'))->governedSeries());
    }
}
