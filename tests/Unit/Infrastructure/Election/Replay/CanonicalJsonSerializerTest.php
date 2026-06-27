<?php

namespace Tests\Unit\Infrastructure\Election\Replay;

use App\Infrastructure\Election\Replay\CanonicalJsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * CanonicalJsonSerializer Test
 *
 * Verifies deterministic JSON serialization for replay-grade hashing.
 * These are REPLAY CONTRACT TESTS — they prove the serializer produces
 * identical output across environments.
 */
class CanonicalJsonSerializerTest extends TestCase
{
    /**
     * REPLAY CONTRACT: Keys must be sorted alphabetically at every nesting level.
     */
    public function test_keys_are_sorted_alphabetically(): void
    {
        $input = ['z' => 1, 'a' => 2, 'm' => 3];
        $json = CanonicalJsonSerializer::serialize($input);

        $this->assertSame('{"a":2,"m":3,"z":1}', $json);
    }

    /**
     * REPLAY CONTRACT: Nested keys must also be sorted alphabetically.
     */
    public function test_nested_keys_are_sorted_alphabetically(): void
    {
        $input = [
            'outer' => ['z' => 1, 'a' => 2],
            'b' => 3,
        ];
        $json = CanonicalJsonSerializer::serialize($input);

        $this->assertSame('{"b":3,"outer":{"a":2,"z":1}}', $json);
    }

    /**
     * REPLAY CONTRACT: DateTime must be normalized to UTC ISO 8601 (Y-m-d\TH:i:s\Z).
     */
    public function test_datetime_normalized_to_utc_iso8601(): void
    {
        $input = new \DateTimeImmutable('2026-05-27 14:30:00', new \DateTimeZone('Europe/Berlin'));
        $json = CanonicalJsonSerializer::serialize($input);

        // Berlin is UTC+2 in summer, so 14:30 Berlin = 12:30 UTC
        $this->assertStringContainsString('2026-05-27T12:30:00Z', $json);
    }

    /**
     * REPLAY CONTRACT: DateTimeImmutable must be normalized to UTC.
     */
    public function test_datetime_immutable_normalized_to_utc(): void
    {
        $input = new \DateTimeImmutable('2026-01-01 00:00:00', new \DateTimeZone('America/New_York'));
        $json = CanonicalJsonSerializer::serialize($input);

        // New York is UTC-5 in winter, so 00:00 NY = 05:00 UTC
        $this->assertStringContainsString('2026-01-01T05:00:00Z', $json);
    }

    /**
     * REPLAY CONTRACT: Floats must throw RuntimeException.
     * Float math breaks determinism across environments.
     */
    public function test_floats_throw_runtime_exception(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Float values are not allowed');

        CanonicalJsonSerializer::serialize(3.14);
    }

    /**
     * REPLAY CONTRACT: Float in nested array must also throw.
     */
    public function test_float_in_nested_array_throws(): void
    {
        $this->expectException(\RuntimeException::class);

        CanonicalJsonSerializer::serialize(['a' => 1, 'b' => 2.5]);
    }

    /**
     * REPLAY CONTRACT: Same input must produce identical output every time.
     */
    public function test_deterministic_same_input_same_output(): void
    {
        $input = [
            'network' => [
                'ip_hash' => 'abc123',
                'votes' => 3,
            ],
            'device' => [
                'fingerprint' => 'def456',
            ],
            'constitution' => [
                'max_votes_per_ip' => 6,
                'strategy' => 'ip_count',
            ],
        ];

        $first = CanonicalJsonSerializer::serialize($input);

        for ($i = 0; $i < 5; $i++) {
            $this->assertSame(
                $first,
                CanonicalJsonSerializer::serialize($input),
                "Canonical JSON changed on run {$i}"
            );
        }
    }

    /**
     * REPLAY CONTRACT: Different inputs must produce different output.
     */
    public function test_different_input_different_output(): void
    {
        $input1 = ['a' => 1, 'b' => 2];
        $input2 = ['a' => 1, 'b' => 3];

        $this->assertNotSame(
            CanonicalJsonSerializer::serialize($input1),
            CanonicalJsonSerializer::serialize($input2)
        );
    }

    /**
     * REPLAY CONTRACT: JSON_UNESCAPED_SLASHES must be used.
     */
    public function test_slashes_are_not_escaped(): void
    {
        $input = ['path' => 'https://example.com/api'];
        $json = CanonicalJsonSerializer::serialize($input);

        $this->assertStringContainsString('https://', $json);
        $this->assertStringNotContainsString('https:\/\/', $json);
    }

    /**
     * REPLAY CONTRACT: Unicode must not be escaped.
     */
    public function test_unicode_is_not_escaped(): void
    {
        $input = ['name' => 'München'];
        $json = CanonicalJsonSerializer::serialize($input);

        $this->assertStringContainsString('München', $json);
        $this->assertStringNotContainsString('\\u00fc', $json);
    }

    /**
     * REPLAY CONTRACT: Object with public properties must serialize canonically.
     */
    public function test_object_serialization(): void
    {
        $obj = new \stdClass();
        $obj->z = 1;
        $obj->a = 2;
        $json = CanonicalJsonSerializer::serialize($obj);

        $this->assertSame('{"a":2,"z":1}', $json);
    }

    /**
     * REPLAY CONTRACT: Sequential arrays must not be reordered.
     */
    public function test_sequential_arrays_preserve_order(): void
    {
        $input = [3, 1, 2];
        $json = CanonicalJsonSerializer::serialize($input);

        $this->assertSame('[3,1,2]', $json);
    }
}
