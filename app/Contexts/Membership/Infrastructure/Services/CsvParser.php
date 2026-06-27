<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use Illuminate\Http\UploadedFile;
use App\Contexts\Membership\Application\DTOs\CsvParseResult;
use InvalidArgumentException;
use RuntimeException;

/**
 * CSV Parser Infrastructure Service
 *
 * Handles CSV file parsing with robust error handling:
 * - Detects encoding (UTF-8, ISO-8859-1, Windows-1252)
 * - Handles different delimiters (comma, semicolon, tab, pipe)
 * - Handles quoted fields with commas
 * - Validates file size and row count
 * - Strips BOM (Byte Order Mark)
 *
 * Note: Not marked final to enable mocking in unit tests.
 * Infrastructure services can be mocked without interfaces.
 */
class CsvParser
{
    private const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50 MB
    private const MAX_ROWS = 100000; // Maximum rows to prevent memory issues
    private const SUPPORTED_ENCODINGS = ['UTF-8', 'ISO-8859-1', 'Windows-1252'];
    private const SUPPORTED_DELIMITERS = [',', ';', "\t", '|'];

    /**
     * Parse CSV file into array of rows
     *
     * @param UploadedFile $file CSV file
     * @param array<string> $expectedColumns Optional expected column names
     * @return CsvParseResult Parsed rows with metadata
     * @throws InvalidArgumentException If file is invalid
     * @throws RuntimeException If parsing fails
     */
    public function parse(UploadedFile $file, array $expectedColumns = []): CsvParseResult
    {
        $this->validateFile($file);

        $filePath = $file->getRealPath();
        if ($filePath === false) {
            throw new RuntimeException('Cannot read uploaded file');
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new RuntimeException('Failed to read file contents');
        }

        // Detect and convert encoding
        $encoding = $this->detectEncoding($content);
        if ($encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        // Remove BOM if present
        $content = $this->removeBom($content);

        // Detect delimiter
        $delimiter = $this->detectDelimiter($content);

        // Parse CSV using stream for proper handling of quoted fields
        $stream = fopen('php://temp', 'r+');
        if ($stream === false) {
            throw new RuntimeException('Failed to create temp stream');
        }

        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        $headers = [];
        $lineNumber = 0;

        while (($parsedLine = fgetcsv($stream, 0, $delimiter)) !== false) {
            $lineNumber++;

            if ($lineNumber === 1) {
                // First row is headers
                $headers = array_map('trim', $parsedLine);

                // Validate expected columns if provided
                if (!empty($expectedColumns)) {
                    $this->validateHeaders($headers, $expectedColumns);
                }

                continue;
            }

            // Skip empty lines
            if (empty(array_filter($parsedLine))) {
                continue;
            }

            // Combine headers with values
            if (count($headers) !== count($parsedLine)) {
                fclose($stream);
                throw new RuntimeException("Row $lineNumber has mismatched column count. Expected " . count($headers) . " columns, got " . count($parsedLine));
            }

            $row = array_combine($headers, $parsedLine);
            if ($row === false) {
                fclose($stream);
                throw new RuntimeException("Row $lineNumber has mismatched column count");
            }

            $rows[] = $row;

            // Safety check for large files
            if (count($rows) >= self::MAX_ROWS) {
                fclose($stream);
                throw new RuntimeException("File exceeds maximum row limit of " . self::MAX_ROWS);
            }
        }

        fclose($stream);

        return new CsvParseResult(
            rows: $rows,
            headers: $headers,
            totalRows: count($rows),
            encoding: $encoding,
            delimiter: $delimiter,
            fileSize: $file->getSize()
        );
    }

    /**
     * Validate uploaded file
     *
     * @throws InvalidArgumentException
     */
    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new InvalidArgumentException('Uploaded file is invalid');
        }

        $mimeType = $file->getMimeType();
        $allowedMimeTypes = [
            'text/csv',
            'text/plain',
            'application/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new InvalidArgumentException("File must be CSV format. Received: $mimeType");
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            $sizeMB = round(self::MAX_FILE_SIZE / 1024 / 1024);
            throw new InvalidArgumentException("File size exceeds maximum of {$sizeMB}MB");
        }
    }

    /**
     * Detect file encoding
     */
    private function detectEncoding(string $content): string
    {
        $encoding = mb_detect_encoding($content, self::SUPPORTED_ENCODINGS, true);

        if ($encoding === false) {
            // Default to UTF-8 if detection fails
            return 'UTF-8';
        }

        return $encoding;
    }

    /**
     * Remove UTF-8 BOM if present
     */
    private function removeBom(string $content): string
    {
        $bom = pack('H*','EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);

        return $content;
    }

    /**
     * Detect CSV delimiter by analyzing first few lines
     */
    private function detectDelimiter(string $content): string
    {
        $lines = array_slice(explode("\n", $content), 0, 5);

        $delimiterCounts = [];
        foreach (self::SUPPORTED_DELIMITERS as $delimiter) {
            $counts = [];
            foreach ($lines as $line) {
                $counts[] = substr_count($line, $delimiter);
            }

            // Delimiter should appear consistently across lines
            $avgCount = array_sum($counts) / count($counts);
            $variance = 0;
            foreach ($counts as $count) {
                $variance += pow($count - $avgCount, 2);
            }
            $variance = $variance / count($counts);

            // Lower variance = more consistent delimiter
            $delimiterCounts[$delimiter] = [
                'avg' => $avgCount,
                'variance' => $variance
            ];
        }

        // Find delimiter with highest average count and lowest variance
        $bestDelimiter = ',';
        $bestScore = -1;

        foreach ($delimiterCounts as $delimiter => $stats) {
            // Score: high average, low variance
            $score = $stats['avg'] - ($stats['variance'] * 0.5);

            if ($score > $bestScore && $stats['avg'] > 0) {
                $bestScore = $score;
                $bestDelimiter = $delimiter;
            }
        }

        return $bestDelimiter;
    }

    /**
     * Validate that required headers are present
     *
     * @param array<string> $actualHeaders
     * @param array<string> $expectedHeaders
     * @throws InvalidArgumentException
     */
    private function validateHeaders(array $actualHeaders, array $expectedHeaders): void
    {
        $missingHeaders = array_diff($expectedHeaders, $actualHeaders);

        if (!empty($missingHeaders)) {
            $missing = implode(', ', $missingHeaders);
            throw new InvalidArgumentException("Missing required columns: $missing");
        }
    }
}
