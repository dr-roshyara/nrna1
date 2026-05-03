<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

/**
 * CSV Parse Result DTO
 *
 * Immutable data transfer object containing parsed CSV data
 * Used to pass CSV parsing results between Infrastructure and Application layers
 */
final readonly class CsvParseResult
{
    /**
     * @param array<int, array<string, string>> $rows Parsed data rows (header => value)
     * @param array<string> $headers Column headers from CSV
     * @param int $totalRows Total number of data rows (excluding header)
     * @param string $encoding Detected encoding (UTF-8, ISO-8859-1, etc.)
     * @param string $delimiter Detected delimiter (comma, semicolon, tab, etc.)
     * @param int $fileSize File size in bytes
     */
    public function __construct(
        public array $rows,
        public array $headers,
        public int $totalRows,
        public string $encoding,
        public string $delimiter,
        public int $fileSize
    ) {
    }
}
