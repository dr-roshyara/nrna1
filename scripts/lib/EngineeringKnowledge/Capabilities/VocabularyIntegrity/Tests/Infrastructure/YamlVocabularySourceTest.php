<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\YamlVocabularySource;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S3 — the config adapter for the declared vocabulary.
 *
 * Reads the D-5 YAML shape through the SAME parser the governed doc-placement tooling
 * uses (`Symfony\Component\Yaml\Yaml` — ES-005.4, never a second parser).
 */
final class YamlVocabularySourceTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir().'/s3-config-'.bin2hex(random_bytes(6)).'.yaml';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    public function test_it_parses_retired_terms_and_the_declaration_label(): void
    {
        file_put_contents(
            $this->file,
            "retired_terms:\n  - \"Phase 2b\"\nconfusable_declaration_label: \"DI-7\"\n",
        );

        $vocabulary = (new YamlVocabularySource($this->file))->read();

        self::assertSame(['Phase 2b'], $vocabulary->retiredTerms());
        self::assertSame('DI-7', $vocabulary->confusableDeclarationLabel());
    }

    public function test_a_missing_config_throws(): void
    {
        $this->expectException(RuntimeException::class);

        (new YamlVocabularySource($this->file.'-absent'))->read();
    }

    public function test_a_config_without_the_label_throws(): void
    {
        file_put_contents($this->file, "retired_terms:\n  - \"Phase 2b\"\n");

        $this->expectException(RuntimeException::class);

        (new YamlVocabularySource($this->file))->read();
    }
}
