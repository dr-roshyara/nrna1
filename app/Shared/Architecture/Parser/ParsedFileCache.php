<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

use SplFileInfo;

final class ParsedFileCache
{
    /** @var array<string, ?PhpFile> */
    private array $cache = [];

    public function get(SplFileInfo $file, PhpFileParser $parser, ParseFailurePolicy $policy = ParseFailurePolicy::WARN_AND_SKIP): ?PhpFile
    {
        $path = $file->getRealPath();

        if ($path === false) {
            return null;
        }

        if (! array_key_exists($path, $this->cache)) {
            $this->cache[$path] = $parser->parse($file);
        }

        return $this->cache[$path];
    }

    /**
     * @param SplFileInfo[] $files
     * @return PhpFile[]
     */
    public function getMultiple(array $files, PhpFileParser $parser, ParseFailurePolicy $policy = ParseFailurePolicy::WARN_AND_SKIP): array
    {
        $result = [];

        foreach ($files as $file) {
            $parsed = $this->get($file, $parser, $policy);

            if ($parsed !== null) {
                $result[] = $parsed;
            }
        }

        return $result;
    }

    public function clear(): void
    {
        $this->cache = [];
    }
}
