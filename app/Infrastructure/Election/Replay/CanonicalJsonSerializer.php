<?php

namespace App\Infrastructure\Election\Replay;

/**
 * CanonicalJsonSerializer
 *
 * Deterministic JSON serialization for replay-grade hashing.
 * Enforces canonical ordering rules to guarantee same input → same JSON → same hash.
 *
 * Rules:
 * 1. Keys sorted alphabetically at every nesting level
 * 2. Date/times normalized to UTC ISO 8601 (Y-m-d\TH:i:s\Z)
 * 3. No floats — \RuntimeException thrown on float encounter
 * 4. Consistent JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE options
 *
 * This is the foundational building block for all replay hashing in
 * the constitutional runtime. Every hash depends on this serializer
 * producing identical output across environments.
 */
final class CanonicalJsonSerializer
{
    /**
     * Serialize data to canonical JSON.
     *
     * @param mixed $data Data to serialize (must be JSON-serializable)
     * @return string Canonical JSON string
     * @throws \RuntimeException If data contains floats
     */
    public static function serialize(mixed $data): string
    {
        $normalized = self::normalize($data);
        return json_encode(
            $normalized,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Recursively normalize data for canonical serialization.
     */
    private static function normalize(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            // Normalize to UTC ISO 8601
            return $value->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
        }

        if (is_float($value)) {
            throw new \RuntimeException(
                'Float values are not allowed in canonical serialization: ' . var_export($value, true)
            );
        }

        if (is_array($value)) {
            // Check if associative (object) or sequential (array)
            if (self::isAssociative($value)) {
                $sorted = [];
                $keys = array_keys($value);
                sort($keys, SORT_STRING);
                foreach ($keys as $key) {
                    $sorted[$key] = self::normalize($value[$key]);
                }
                return (object) $sorted; // Force JSON object
            }

            return array_map([self::class, 'normalize'], $value);
        }

        if (is_object($value)) {
            // Convert object to sorted key-value pairs
            $ref = new \ReflectionObject($value);
            $props = $ref->getProperties();
            $data = [];
            foreach ($props as $prop) {
                $prop->setAccessible(true);
                $data[$prop->getName()] = $prop->getValue($value);
            }
            ksort($data, SORT_STRING);
            return self::normalize($data);
        }

        return $value;
    }

    /**
     * Determine if an array is associative (object-like) or sequential.
     */
    private static function isAssociative(array $array): bool
    {
        if (empty($array)) {
            return false;
        }
        $keys = array_keys($array);
        return $keys !== range(0, count($array) - 1);
    }
}
