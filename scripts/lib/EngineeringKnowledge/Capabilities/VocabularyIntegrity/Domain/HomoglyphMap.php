<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

/**
 * The glyph-collision facts behind DI-7 (S3).
 *
 * A Greek glyph whose rendered shape matches a Latin letter is visually CONFUSABLE
 * with an identifier that letter begins — `CASE β` reads as `CASE B`. The corpus's
 * collision classes are α→A and β→B (AMD5 introduced `CASE α`/`CASE β` beside §6's
 * `CASE A`/`CASE B`). Only glyphs with a verified Latin homoglyph belong here; a glyph
 * with no entry never collides. (NOT-CHECKED: other Greek letters are not in the map.)
 *
 * This is a DOMAIN fact — a property of the writing system, not of any document or
 * configuration — so it lives in the domain, not in the config source.
 */
final readonly class HomoglyphMap
{
    /** Greek lowercase → the Latin glyph whose shape it visually mimics. */
    private const GLYPHS = [
        'α' => 'A',
        'β' => 'B',
    ];

    public static function latinHomoglyph(string $glyph): ?string
    {
        return self::GLYPHS[$glyph] ?? null;
    }

    /**
     * Two DISTINCT glyphs collide when they normalize to the same glyph — at least one
     * has a homoglyph mapping that lands on the other. `β` and `B` collide; `A` and
     * `B` do not; `β` and `β` are the same glyph, not a collision.
     */
    public static function collides(string $first, string $second): bool
    {
        if ($first === $second) {
            return false;
        }

        $normalizedFirst = self::latinHomoglyph($first) ?? $first;
        $normalizedSecond = self::latinHomoglyph($second) ?? $second;

        return $normalizedFirst === $normalizedSecond;
    }
}
