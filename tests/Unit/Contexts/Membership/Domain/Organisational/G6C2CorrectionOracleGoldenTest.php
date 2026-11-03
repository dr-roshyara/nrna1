<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6C2CorrectionOracle;

/**
 * Golden cross-check of the PHP port of the g6 correction oracle for the C-2 fragment (PBDIGIT-327, COR-2 plan
 * steps 1–2): Admit, End, Annul, Void and Correct, with consequences (g2), LiveCut, INV-COV and the decided
 * restrictions (D-COR-2, P-1, P-3, P-4, BD-REV-ER).
 *
 * The fixture was generated once by the independent Python oracle (its header records the command and the
 * oracle's SHA-256). Equal counts, listing and stream hash show transcription fidelity of the port only: the oracle
 * is differential evidence, not the contract.
 */
final class G6C2CorrectionOracleGoldenTest extends TestCase
{
    public function test_the_php_port_reproduces_the_python_c2_oracle_stream(): void
    {
        $golden = json_decode((string) file_get_contents(__DIR__ . '/Fixtures/g6_c2_golden.json'), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(5, $golden['L'], 'the C-2 bound is at least 5 facts (INV-EP reachable)');

        $perDepth = [];
        $listing = [];
        $hash = hash_init('sha256');
        foreach (G6C2CorrectionOracle::enumerate($golden['H'], $golden['L']) as [$depth, $line, $refusal]) {
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

        $this->assertSame($golden['listing'], $listing);
        $this->assertSame($golden['per_depth'], array_values($perDepth));
        $this->assertSame($golden['stream_sha256'], hash_final($hash));
    }
}
