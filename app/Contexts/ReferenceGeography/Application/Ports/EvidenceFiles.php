<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

/**
 * G-8: the files of an evidenced dataset (a directory holding manifest.json and the evidence documents it lists).
 * File names are plain names inside that directory; never a path.
 */
interface EvidenceFiles
{
    /** The manifest's text, or null if the directory has no manifest.json. */
    public function manifest(string $directory): ?string;

    /** The SHA-256 (lowercase hex) of a file in the directory, or null if it is missing. */
    public function sha256(string $directory, string $file): ?string;
}
