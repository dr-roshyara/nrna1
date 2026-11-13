<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * What one publication CONCERNS (G-5c; v3.11 §10 RevisionPublished: kinds, subjects, reconciliation mappings).
 * Sorted and distinct, so one publication always reads the same.
 */
final readonly class PublicationSummary
{
    /**
     * @param list<string> $kinds
     * @param list<string> $subjects unit ids
     * @param list<array{identity: string, canonical: ?string}> $merges canonical null = a reversal
     */
    private function __construct(public array $kinds, public array $subjects, public array $merges)
    {
    }

    /** @param list<array{0: SlotKey, 1: ?string}> $changes the publication's claims: slot and value (null = NONE) */
    public static function of(array $changes): self
    {
        $kinds = $subjects = $merges = [];
        foreach ($changes as [$slot, $value]) {
            $kinds[$slot->kind()->value] = true;
            $subject = $slot->subject();
            if ($subject !== null) {
                $subjects[$subject->value()] = true;
            }
            if ($slot->kind() === ClaimKind::Reconciliation && $subject !== null) {
                $merges[$subject->value()] = ['identity' => $subject->value(), 'canonical' => $value];
            }
        }
        ksort($kinds);
        ksort($subjects);
        ksort($merges);

        return new self(array_keys($kinds), array_keys($subjects), array_values($merges));
    }
}
