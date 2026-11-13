<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The slot a claim asserts about (v3.11 §3). Claims in one slot supersede each other; claims in different slots
 * never interact.
 * - The key always begins with its kind (v3.2), so two kinds never share a slot.
 * - Within a kind, the key names exactly what can hold one value at a time (the §3 collision-proof table).
 * - Encoding: KIND, then the shape's fixed, named components, each length-prefixed, so no component content can
 *   forge another key. One factory per shape; nothing else constructs a key.
 */
final readonly class SlotKey
{
    private const SINGLE_NAME_TYPES = ['official', 'short'];
    private const SET_NAME_TYPES = ['alternative', 'former'];
    private const CLDR_FORMS = ['standard', 'short'];

    /** @param list<array{string, string}> $components [name, value] in the shape's fixed order */
    private function __construct(private ClaimKind $kind, private array $components)
    {
        foreach ($components as [$label, $value]) {
            if (trim($value) === '') {
                throw new \InvalidArgumentException("Slot key component '{$label}' cannot be empty.");
            }
        }
    }

    public static function existence(ReferenceUnitId $unit): self
    {
        return new self(ClaimKind::Existence, [['unit', $unit->value()]]);
    }

    public static function containment(ReferenceUnitId $unit, string $scheme): self
    {
        return new self(ClaimKind::Containment, [['unit', $unit->value()], ['scheme', $scheme]]);
    }

    public static function classification(ReferenceUnitId $unit, string $scheme): self
    {
        return new self(ClaimKind::Classification, [['unit', $unit->value()], ['scheme', $scheme]]);
    }

    public static function boundary(ReferenceUnitId $unit, string $source): self
    {
        return new self(ClaimKind::Boundary, [['unit', $unit->value()], ['source', $source]]);
    }

    public static function succession(ReferenceUnitId $predecessor, string $successionEvent): self
    {
        return new self(ClaimKind::Succession, [['predecessor', $predecessor->value()], ['succession_event', $successionEvent]]);
    }

    public static function identifier(ReferenceUnitId $subject, string $namespace): self
    {
        return new self(ClaimKind::Identifier, [['subject', $subject->value()], ['namespace', $namespace]]);
    }

    public static function grouping(ReferenceUnitId $entry, string $groupingScheme): self
    {
        return new self(ClaimKind::Grouping, [['entry', $entry->value()], ['grouping_scheme', $groupingScheme]]);
    }

    public static function admission(ReferenceUnitId $entry): self
    {
        return new self(ClaimKind::Admission, [['entry', $entry->value()]]);
    }

    /** Single-valued source name: one per subject, source, language and type (official | short). */
    public static function sourceName(ReferenceUnitId $subject, string $source, string $language, string $nameType): self
    {
        self::requireOneOf($nameType, self::SINGLE_NAME_TYPES, 'single-valued name type');

        return new self(ClaimKind::SourceName, [['subject', $subject->value()], ['source', $source], ['language', $language], ['name_type', $nameType]]);
    }

    /** Set-valued source name (alternative | former): each value is its own slot, so several coexist (v3.3). */
    public static function sourceNameValue(ReferenceUnitId $subject, string $source, string $language, string $nameType, string $value): self
    {
        self::requireOneOf($nameType, self::SET_NAME_TYPES, 'set-valued name type');

        return new self(ClaimKind::SourceName, [['subject', $subject->value()], ['source', $source], ['language', $language], ['name_type', $nameType], ['value', $value]]);
    }

    public static function cldrName(ReferenceUnitId $entry, string $locale, string $form): self
    {
        self::requireOneOf($form, self::CLDR_FORMS, 'CLDR form');

        return new self(ClaimKind::CldrName, [['entry', $entry->value()], ['locale', $locale], ['form', $form]]);
    }

    public static function cldrInheritance(string $release): self
    {
        return new self(ClaimKind::CldrInheritance, [['release', $release]]);
    }

    public static function issuer(string $issuer): self
    {
        return new self(ClaimKind::Issuer, [['issuer', $issuer]]);
    }

    public static function mandate(string $mandate): self
    {
        return new self(ClaimKind::Mandate, [['mandate', $mandate]]);
    }

    public static function competence(string $issuer, string $competenceKind): self
    {
        return new self(ClaimKind::Competence, [['issuer', $issuer], ['competence_kind', $competenceKind]]);
    }

    public static function precedence(string $rule): self
    {
        return new self(ClaimKind::Precedence, [['rule', $rule]]);
    }

    /** Keyed by the target CLAIM, not the target's slot, so a dispute can never supersede a value. */
    public static function dispute(ClaimId $targetClaim): self
    {
        return new self(ClaimKind::Dispute, [['target_claim', $targetClaim->value()]]);
    }

    public static function reconciliation(ReferenceUnitId $identity): self
    {
        return new self(ClaimKind::Reconciliation, [['identity', $identity->value()]]);
    }

    public static function profileScheme(ReferenceUnitId $countryEntry): self
    {
        return new self(ClaimKind::ProfileScheme, [['country_entry', $countryEntry->value()]]);
    }

    /** The meaning of one position in one scheme: "one meaning per position", structurally. */
    public static function profileTier(ReferenceUnitId $countryEntry, string $scheme, int $position): self
    {
        if ($position < 0) {
            throw new \InvalidArgumentException('A tier position cannot be negative.');
        }

        return new self(ClaimKind::ProfileTier, [['country_entry', $countryEntry->value()], ['scheme', $scheme], ['position', (string) $position]]);
    }

    public static function profileClass(ReferenceUnitId $countryEntry, string $scheme, string $class): self
    {
        return new self(ClaimKind::ProfileClass, [['country_entry', $countryEntry->value()], ['scheme', $scheme], ['class', $class]]);
    }

    public static function platformDefaultLocale(): self
    {
        return new self(ClaimKind::PlatformDefaultLocale, []);
    }

    public function kind(): ClaimKind
    {
        return $this->kind;
    }

    /** Injective: "KIND|", then each component as "name=length:value|". Every part is terminated, even with none. */
    public function encoded(): string
    {
        $out = $this->kind->value . '|';
        foreach ($this->components as [$label, $value]) {
            $out .= $label . '=' . strlen($value) . ':' . $value . '|';
        }

        return $out;
    }

    public function equals(self $other): bool
    {
        return $this->encoded() === $other->encoded();
    }

    /** @param list<string> $allowed */
    private static function requireOneOf(string $value, array $allowed, string $what): void
    {
        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException("Unknown {$what} '{$value}'.");
        }
    }
}
