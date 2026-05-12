<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use RuntimeException;
use SplFileInfo;

final class PhpFileParser
{
    private readonly \PhpParser\Parser $parser;

    public function __construct(
        private readonly ParseFailurePolicy $failurePolicy = ParseFailurePolicy::WARN_AND_SKIP,
    ) {
        $this->parser = (new ParserFactory())->createForNewestSupportedVersion();
    }

    public function parse(SplFileInfo $file): ?PhpFile
    {
        $content = file_get_contents($file->getRealPath());

        if ($content === false || $content === '') {
            return null;
        }

        try {
            $stmts = $this->parser->parse($content);
        } catch (Error $e) {
            return match ($this->failurePolicy) {
                ParseFailurePolicy::FAIL_FAST => throw new RuntimeException(
                    sprintf('Parse error in %s: %s', $file->getRealPath(), $e->getMessage()),
                    0,
                    $e,
                ),
                ParseFailurePolicy::WARN_AND_SKIP => null,
                ParseFailurePolicy::LEGACY_FALLBACK => null,
            };
        }

        if ($stmts === null) {
            return null;
        }

        $visitor = new PhpFileVisitor();
        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($stmts);

        return $visitor->getPhpFile($file->getRealPath());
    }
}
