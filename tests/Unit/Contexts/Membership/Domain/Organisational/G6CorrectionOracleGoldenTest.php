<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6CorrectionOracle;

/**
 * Golden cross-check of the PHP port of the g6 correction oracle (PBDIGIT-268, plan step 10).
 *
 * The fixture was generated once by the independent Python oracle (its header records the
 * command and the oracle's SHA-256). Equal counts, listing and stream hash show transcription
 * fidelity of the port only: the oracle is differential evidence, not the contract (PO constraint 4).
 */
final class G6CorrectionOracleGoldenTest extends TestCase
{
    public function test_the_php_port_reproduces_the_python_oracle_stream(): void
    {
        $golden = json_decode((string) file_get_contents(__DIR__ . '/Fixtures/g6_c1_golden.json'), true, flags: JSON_THROW_ON_ERROR);

        $perDepth = [];
        $listing = [];
        $hash = hash_init('sha256');
        foreach (G6CorrectionOracle::enumerate($golden['H'], $golden['L']) as [$depth, $line, $refusal]) {
            hash_update($hash, $line . "\n");
            if ($depth <= $golden['listing_depth']) {
                $listing[] = $line;
            }
            $perDepth[$depth] ??= ['depth' => $depth, 'decisions' => 0, 'accepted' => 0, 'refusals' => []];
            $perDepth[$depth]['decisions']++;
            if ($refusal === null) {
                $perDepth[$depth]['accepted']++;
            } else {
                $perDepth[$depth]['refusals'][$refusal] = ($perDepth[$depth]['refusals'][$refusal] ?? 0) + 1;
            }
        }
        foreach ($perDepth as &$row) {
            ksort($row['refusals']);
        }

        $this->assertSame($golden['per_depth'], array_values($perDepth));
        $this->assertSame($golden['listing'], $listing);
        $this->assertSame($golden['stream_sha256'], hash_final($hash));
    }
}
