<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\EvidenceFiles;

/** G-8: dataset files on the local filesystem. A file name with a separator or ".." is refused (no path escape). */
final class LocalEvidenceFiles implements EvidenceFiles
{
    public function manifest(string $directory): ?string
    {
        $path = rtrim($directory, '/') . '/manifest.json';

        return is_file($path) ? (string) file_get_contents($path) : null;
    }

    public function sha256(string $directory, string $file): ?string
    {
        if ($file === '' || str_contains($file, '/') || str_contains($file, '\\') || str_contains($file, '..')) {
            throw new \InvalidArgumentException("An evidence file is a plain name inside the dataset directory, not '{$file}'.");
        }
        $path = rtrim($directory, '/') . '/' . $file;

        return is_file($path) ? (string) hash_file('sha256', $path) : null;
    }
}
