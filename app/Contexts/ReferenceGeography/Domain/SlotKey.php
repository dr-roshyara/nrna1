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

    /** The value of a named component, or null if this shape has no such component (for family matching, §5.1). */
    public function component(string $label): ?string
    {
        foreach ($this->components as [$componentLabel, $value]) {
            if ($componentLabel === $label) {
                return $value;
            }
        }

        return null;
    }

    /** The geographical unit the slot is about, if any (for the subject family of reconciliation, §5.2). */
    public function subject(): ?ReferenceUnitId
    {
        foreach (['unit', 'subject', 'entry', 'predecessor', 'identity', 'country_entry'] as $label) {
            $value = $this->component($label);
            if ($value !== null) {
                return ReferenceUnitId::fromString($value);
            }
        }

        return null;
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

    /**
     * Rebuilds a key from its canonical encoding (G-4b, persistence). The key is rebuilt through its shape's own
     * factory, so every factory rule applies again, and refused unless it re-encodes to exactly the input: a stored
     * key can never be one the domain could not have built.
     */
    public static function decode(string $encoded): self
    {
        $bar = strpos($encoded, '|');
        $kind = $bar === false ? null : ClaimKind::tryFrom(substr($encoded, 0, $bar));
        if ($kind === null) {
            throw new \InvalidArgumentException('Not a slot key: unknown kind.');
        }
        $parts = [];
        $i = $bar + 1;
        while ($i < strlen($encoded)) {
            $eq = strpos($encoded, '=', $i);
            $colon = $eq === false ? false : strpos($encoded, ':', $eq);
            $length = $colon === false ? '' : substr($encoded, $eq + 1, $colon - $eq - 1);
            if ($colon === false || preg_match('/^(0|[1-9][0-9]*)$/', $length) !== 1) {
                throw new \InvalidArgumentException('Not a slot key: malformed component.');
            }
            $label = substr($encoded, $i, $eq - $i);
            $value = substr($encoded, $colon + 1, (int) $length);
            $end = $colon + 1 + (int) $length;
            if (strlen($value) !== (int) $length || ($encoded[$end] ?? '') !== '|' || array_key_exists($label, $parts)) {
                throw new \InvalidArgumentException('Not a slot key: malformed component.');
            }
            $parts[$label] = $value;
            $i = $end + 1;
        }
        $key = self::fromParts($kind, $parts);
        if ($key->encoded() !== $encoded) {
            throw new \InvalidArgumentException('Not a slot key: not canonical.');
        }

        return $key;
    }

    /** @param array<string, string> $parts */
    private static function fromParts(ClaimKind $kind, array $parts): self
    {
        $take = static function (string ...$labels) use ($parts): array {
            if (array_keys($parts) !== $labels) {
                throw new \InvalidArgumentException('Not a slot key: the components do not match the shape.');
            }

            return array_values($parts);
        };
        $u = static fn (string $v): ReferenceUnitId => ReferenceUnitId::fromString($v);

        return match ($kind) {
            ClaimKind::Existence => self::existence($u(...$take('unit'))),
            ClaimKind::Containment => (static fn (array $v) => self::containment($u($v[0]), $v[1]))($take('unit', 'scheme')),
            ClaimKind::Classification => (static fn (array $v) => self::classification($u($v[0]), $v[1]))($take('unit', 'scheme')),
            ClaimKind::Boundary => (static fn (array $v) => self::boundary($u($v[0]), $v[1]))($take('unit', 'source')),
            ClaimKind::Succession => (static fn (array $v) => self::succession($u($v[0]), $v[1]))($take('predecessor', 'succession_event')),
            ClaimKind::Identifier => (static fn (array $v) => self::identifier($u($v[0]), $v[1]))($take('subject', 'namespace')),
            ClaimKind::Grouping => (static fn (array $v) => self::grouping($u($v[0]), $v[1]))($take('entry', 'grouping_scheme')),
            ClaimKind::Admission => self::admission($u(...$take('entry'))),
            ClaimKind::SourceName => count($parts) === 5
                ? (static fn (array $v) => self::sourceNameValue($u($v[0]), $v[1], $v[2], $v[3], $v[4]))($take('subject', 'source', 'language', 'name_type', 'value'))
                : (static fn (array $v) => self::sourceName($u($v[0]), $v[1], $v[2], $v[3]))($take('subject', 'source', 'language', 'name_type')),
            ClaimKind::CldrName => (static fn (array $v) => self::cldrName($u($v[0]), $v[1], $v[2]))($take('entry', 'locale', 'form')),
            ClaimKind::CldrInheritance => self::cldrInheritance(...$take('release')),
            ClaimKind::Issuer => self::issuer(...$take('issuer')),
            ClaimKind::Mandate => self::mandate(...$take('mandate')),
            ClaimKind::Competence => self::competence(...$take('issuer', 'competence_kind')),
            ClaimKind::Precedence => self::precedence(...$take('rule')),
            ClaimKind::Dispute => self::dispute(ClaimId::fromString(...$take('target_claim'))),
            ClaimKind::Reconciliation => self::reconciliation($u(...$take('identity'))),
            ClaimKind::ProfileScheme => self::profileScheme($u(...$take('country_entry'))),
            ClaimKind::ProfileTier => (static fn (array $v) => self::profileTier($u($v[0]), $v[1], (int) $v[2]))($take('country_entry', 'scheme', 'position')),
            ClaimKind::ProfileClass => (static fn (array $v) => self::profileClass($u($v[0]), $v[1], $v[2]))($take('country_entry', 'scheme', 'class')),
            ClaimKind::PlatformDefaultLocale => (static fn (array $v) => self::platformDefaultLocale())($take()),
        };
    }

    /** @param list<string> $allowed */
    private static function requireOneOf(string $value, array $allowed, string $what): void
    {
        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException("Unknown {$what} '{$value}'.");
        }
    }
}
