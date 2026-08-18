<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\StateAccess;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;

/**
 * B3 — the PHP LANGUAGE BINDING. PHP source becomes L3 facts, and nothing else.
 *
 * ALL PHP-specific source understanding lives here and nowhere above: tokens,
 * attributes, heredoc/nowdoc, interpolation, PHP-mode boundaries, the
 * identifier charset, the five name kinds, the `use` alias map, enum/trait/
 * interface keywords and nullsafe syntax.
 *
 * ⛔ THIS CLASS MAKES NO COHESION DECISION. It never asks whether something is
 * an edge, whether a unit is eligible, or what the metric is. Those are L4/L5's,
 * and they receive only the closed-vocabulary facts produced here.
 *
 * Extraction is grammar-exact by construction: it reads the language's OWN
 * lexer (token_get_all) rather than approximating the grammar with patterns.
 * Every construct in the measured defect surface — K1–K3, NEW-1…NEW-9 — is
 * resolved at the token layer.
 *
 * SCOPE: exactly ONE PHP source file (OQ-2, decided).
 */
final class PhpFactExtractor
{
    /** Significant tokens: [kind, text]. */
    private array $sig = [];

    private string $namespace = '';

    /** @var array<string,string> lower-cased alias => fully-qualified target */
    private array $aliases = [];

    /** @var array<string,string> lower-cased imported short name => fully-qualified target */
    private array $imports = [];

    private function __construct()
    {
    }

    public static function extract(string $source): FactSet
    {
        $self = new self();
        $self->tokenize($source);
        $self->readNamespaceAndUses();

        $declarations = $self->findDeclarations();
        $identities = $self->assignIdentities($declarations);

        $units = [];
        foreach ($declarations as $index => $declaration) {
            $units[] = new DeclaredUnit(
                $declaration['kind'],
                $identities[$index],
                $declaration['name'],
                $self->methodsOf($declaration, $declarations),
            );
        }

        return new FactSet($units);
    }

    // -- 1 · tokenize -------------------------------------------------------

    /**
     * Drops whitespace, comments and inline HTML; skips attribute groups whole;
     * stops at __halt_compiler(). Nothing after the close tag is code (NEW-7).
     */
    private function tokenize(string $source): void
    {
        $tokens = @token_get_all($source);
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            if (is_array($token)) {
                $kind = $token[0];

                if ($kind === T_WHITESPACE || $kind === T_COMMENT || $kind === T_DOC_COMMENT
                    || $kind === T_OPEN_TAG || $kind === T_OPEN_TAG_WITH_ECHO || $kind === T_CLOSE_TAG
                    || $kind === T_INLINE_HTML) {
                    continue;
                }

                if ($kind === T_HALT_COMPILER) {
                    return; // everything after this is data, not code (NEW-7)
                }

                if ($kind === T_ATTRIBUTE) {
                    // `#[` … `]` — an ATTRIBUTE, never a line comment (K3, NEW-2, NEW-3).
                    $depth = 1;
                    for ($i++; $i < $count && $depth > 0; $i++) {
                        $inner = $tokens[$i];
                        if ($inner === '[' || (is_array($inner) && $inner[0] === T_ATTRIBUTE)) {
                            $depth++;
                        } elseif ($inner === ']') {
                            $depth--;
                        }
                    }
                    $i--;
                    continue;
                }

                $this->sig[] = [$kind, $token[1]];
                continue;
            }

            $this->sig[] = [$token, $token];
        }
    }

    // -- 2 · namespace and use-aliases (file-local resolution only) ----------

    private function readNamespaceAndUses(): void
    {
        $braceDepth = 0;
        $count = count($this->sig);

        for ($i = 0; $i < $count; $i++) {
            [$kind, $text] = $this->sig[$i];

            if ($kind === '{') {
                $braceDepth++;
                continue;
            }
            if ($kind === '}') {
                $braceDepth--;
                continue;
            }

            if ($kind === T_NAMESPACE && $this->namespace === '' && isset($this->sig[$i + 1])
                && $this->sig[$i + 1][0] !== T_NS_SEPARATOR) {
                $next = $this->sig[$i + 1];
                if (in_array($next[0], [T_STRING, T_NAME_QUALIFIED], true)) {
                    $this->namespace = $next[1];
                }
                continue;
            }

            // Only a TOP-LEVEL `use` is an import; inside a class body it is a trait use.
            if ($kind === T_USE && $braceDepth === 0) {
                $target = null;
                for ($j = $i + 1; $j < $count; $j++) {
                    [$k, $t] = $this->sig[$j];
                    if ($k === ';' || $k === '{') {
                        break;
                    }
                    if (in_array($k, [T_STRING, T_NAME_QUALIFIED, T_NAME_FULLY_QUALIFIED], true)) {
                        if ($target === null) {
                            $target = ltrim($t, '\\');
                        } else {
                            // the name after `as`
                            $this->aliases[self::fold($t)] = $target;
                            $target = null;
                            break;
                        }
                    }
                }
                if ($target !== null) {
                    $short = strrchr($target, '\\');
                    $this->imports[self::fold($short === false ? $target : substr($short, 1))] = $target;
                }
            }
        }
    }

    // -- 3 · declarations ---------------------------------------------------

    /** @return list<array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}> */
    private function findDeclarations(): array
    {
        $declarations = [];
        $count = count($this->sig);

        for ($i = 0; $i < $count; $i++) {
            [$kind] = $this->sig[$i];

            $unitKind = match ($kind) {
                T_CLASS => UnitKind::ClassUnit,
                T_ENUM => UnitKind::EnumUnit,
                T_TRAIT => UnitKind::TraitUnit,
                T_INTERFACE => UnitKind::InterfaceUnit,
                default => null,
            };
            if ($unitKind === null) {
                continue;
            }

            // `Foo::class` is a constant fetch, not a declaration.
            if ($i > 0 && $this->sig[$i - 1][0] === T_DOUBLE_COLON) {
                continue;
            }

            $anonymous = $unitKind === UnitKind::ClassUnit
                && $i > 0 && $this->sig[$i - 1][0] === T_NEW;

            $name = null;
            if (!$anonymous) {
                if (!isset($this->sig[$i + 1]) || $this->sig[$i + 1][0] !== T_STRING) {
                    continue; // e.g. `class` used in a context that declares nothing
                }
                $name = $this->sig[$i + 1][1];
            }

            $open = $this->findBodyBrace($i + 1);
            if ($open === null) {
                continue;
            }

            $declarations[] = [
                'kind' => $unitKind,
                'name' => $name,
                'at' => $i,
                'bodyStart' => $open,
                'bodyEnd' => $this->matchBrace($open),
            ];
        }

        return $declarations;
    }

    /** The `{` that opens a declaration body — past extends/implements and any argument list. */
    private function findBodyBrace(int $from): ?int
    {
        $parenDepth = 0;
        $count = count($this->sig);

        for ($i = $from; $i < $count; $i++) {
            $kind = $this->sig[$i][0];
            if ($kind === '(') {
                $parenDepth++;
            } elseif ($kind === ')') {
                $parenDepth--;
            } elseif ($kind === '{' && $parenDepth === 0) {
                return $i;
            } elseif ($kind === ';' && $parenDepth === 0) {
                return null;
            }
        }

        return null;
    }

    private function matchBrace(int $open): int
    {
        $depth = 0;
        $count = count($this->sig);

        for ($i = $open; $i < $count; $i++) {
            $kind = $this->sig[$i][0];
            if ($kind === '{' || $kind === T_CURLY_OPEN || $kind === T_DOLLAR_OPEN_CURLY_BRACES) {
                $depth++;
            } elseif ($kind === '}') {
                $depth--;
                if ($depth === 0) {
                    return $i;
                }
            }
        }

        return $count - 1;
    }

    // -- 4 · identity (OQ-1: deterministic declaration path) ----------------

    /**
     * @param  list<array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}> $declarations
     * @return array<int,UnitIdentity>
     */
    private function assignIdentities(array $declarations): array
    {
        $identities = [];
        $anonymousCounters = [];

        foreach ($declarations as $index => $declaration) {
            if ($declaration['name'] !== null) {
                $identities[$index] = UnitIdentity::named($declaration['name']);
                continue;
            }

            $enclosing = null;
            foreach ($declarations as $otherIndex => $other) {
                if ($otherIndex === $index) {
                    continue;
                }
                if ($other['bodyStart'] < $declaration['at'] && $declaration['at'] < $other['bodyEnd']) {
                    if ($enclosing === null || $other['bodyStart'] > $declarations[$enclosing]['bodyStart']) {
                        $enclosing = $otherIndex;
                    }
                }
            }

            $key = $enclosing ?? -1;
            $anonymousCounters[$key] = ($anonymousCounters[$key] ?? 0) + 1;

            $identities[$index] = $enclosing === null
                ? new UnitIdentity('anon#' . $anonymousCounters[$key])
                : $identities[$enclosing]->anonymousChild($anonymousCounters[$key]);
        }

        return $identities;
    }

    // -- 5 · methods and their facts ----------------------------------------

    /**
     * @param  array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}       $unit
     * @param  list<array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}> $all
     * @return list<MethodFacts>
     */
    private function methodsOf(array $unit, array $all): array
    {
        $methods = [];
        $depth = 0;

        for ($i = $unit['bodyStart']; $i <= $unit['bodyEnd']; $i++) {
            $kind = $this->sig[$i][0];

            if ($kind === '{' || $kind === T_CURLY_OPEN || $kind === T_DOLLAR_OPEN_CURLY_BRACES) {
                $depth++;
                continue;
            }
            if ($kind === '}') {
                $depth--;
                continue;
            }
            if ($kind !== T_FUNCTION || $depth !== 1) {
                continue; // closures and nested functions live deeper
            }
            if ($this->insideNestedUnit($i, $unit, $all)) {
                continue;
            }
            if (!isset($this->sig[$i + 1]) || $this->sig[$i + 1][0] !== T_STRING) {
                continue; // an anonymous closure, not a declared method
            }

            $name = $this->sig[$i + 1][1];
            $brace = $this->findBodyBrace($i + 1);
            $end = null;

            if ($brace !== null && $brace <= $unit['bodyEnd']) {
                $end = $this->matchBrace($brace);
            }

            [$stateAccesses, $behaviourReferences] = $end === null
                ? [[], []]
                : $this->factsIn($brace, $end, $unit, $all);

            $methods[] = new MethodFacts($name, $end !== null, $stateAccesses, $behaviourReferences);

            if ($end !== null) {
                // Skip the whole method body. BOTH its braces are skipped, so the
                // depth relative to the unit body is unchanged.
                $i = $end;
            }
        }

        return $methods;
    }

    /** @param list<array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}> $all */
    private function insideNestedUnit(int $index, array $unit, array $all): bool
    {
        foreach ($all as $other) {
            if ($other['at'] === $unit['at']) {
                continue;
            }
            if ($other['bodyStart'] > $unit['bodyStart'] && $other['bodyEnd'] <= $unit['bodyEnd']
                && $other['bodyStart'] < $index && $index < $other['bodyEnd']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  list<array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int}> $all
     * @return array{0:list<StateAccess>,1:list<BehaviourReference>}
     */
    private function factsIn(int $from, int $to, array $unit, array $all): array
    {
        $stateAccesses = [];
        $behaviourReferences = [];

        for ($i = $from; $i <= $to; $i++) {
            if ($this->insideNestedUnit($i, $unit, $all)) {
                continue; // an anonymous class's body belongs to that unit, not this method
            }

            [$kind, $text] = $this->sig[$i];

            // $this-> / $this?->  — also how interpolation surfaces (NEW-6)
            if ($kind === T_VARIABLE && self::fold($text) === '$this'
                && isset($this->sig[$i + 1], $this->sig[$i + 2])
                && in_array($this->sig[$i + 1][0], [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR], true)
                && $this->sig[$i + 2][0] === T_STRING) {
                $access = $this->sig[$i + 1][0] === T_NULLSAFE_OBJECT_OPERATOR
                    ? AccessMode::Nullsafe
                    : AccessMode::Direct;
                $member = $this->sig[$i + 2][1];

                if (($this->sig[$i + 3][0] ?? null) === '(') {
                    $behaviourReferences[] = new BehaviourReference(
                        $member,
                        QualifierKind::InstanceReceiver,
                        TargetUnitRelation::DenotesAnalysedUnit,
                        $this->isFirstClassCallable($i + 3) ? ReferenceMode::CallableReference : ReferenceMode::Invocation,
                        $access,
                        Determinability::Determinable,
                    );
                } else {
                    $stateAccesses[] = new StateAccess($member, $access);
                }

                $i += 2;
                continue;
            }

            // <qualifier> :: name (  — the same relationship reached through the class
            if (($this->sig[$i + 1][0] ?? null) === T_DOUBLE_COLON
                && ($this->sig[$i + 2][0] ?? null) === T_STRING
                && ($this->sig[$i + 3][0] ?? null) === '(') {
                $qualifier = $this->classifyQualifier($kind, $text, $unit);
                if ($qualifier !== null) {
                    [$qualifierKind, $relation, $determinability] = $qualifier;
                    $behaviourReferences[] = new BehaviourReference(
                        $this->sig[$i + 2][1],
                        $qualifierKind,
                        $relation,
                        $this->isFirstClassCallable($i + 3) ? ReferenceMode::CallableReference : ReferenceMode::Invocation,
                        AccessMode::Direct,
                        $determinability,
                    );
                    $i += 2;
                }
            }
        }

        return [$stateAccesses, $behaviourReferences];
    }

    /** `m(...)` REFERENCES a behaviour rather than invoking it. */
    private function isFirstClassCallable(int $openParen): bool
    {
        return ($this->sig[$openParen + 1][0] ?? null) === T_ELLIPSIS
            && ($this->sig[$openParen + 2][0] ?? null) === ')';
    }

    /**
     * The two orthogonal axes of Decision 13.3: HOW the target was spelled, and
     * WHETHER it denotes the analysed unit. Both are reported; neither is judged.
     *
     * @param  array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int} $unit
     * @return array{0:QualifierKind,1:TargetUnitRelation,2:Determinability}|null
     */
    private function classifyQualifier(int|string $kind, string $text, array $unit): ?array
    {
        if ($kind === T_VARIABLE) {
            return [QualifierKind::ComputedTarget, TargetUnitRelation::Undetermined, Determinability::NotDeterminable];
        }
        if ($kind === T_STATIC) {
            return [QualifierKind::StaticKeyword, TargetUnitRelation::DenotesAnalysedUnit, Determinability::Determinable];
        }

        if ($kind === T_STRING) {
            $folded = self::fold($text);
            if ($folded === 'self') {
                return [QualifierKind::SelfKeyword, TargetUnitRelation::DenotesAnalysedUnit, Determinability::Determinable];
            }
            if ($folded === 'static') {
                return [QualifierKind::StaticKeyword, TargetUnitRelation::DenotesAnalysedUnit, Determinability::Determinable];
            }
            if ($folded === 'parent') {
                return [QualifierKind::ParentKeyword, TargetUnitRelation::Undetermined, Determinability::Determinable];
            }
            if (isset($this->aliases[$folded])) {
                return [QualifierKind::AliasedName, $this->relationTo($this->aliases[$folded], $unit), Determinability::Determinable];
            }
            $resolved = $this->imports[$folded] ?? $this->qualify($text);

            return [QualifierKind::UnqualifiedName, $this->relationTo($resolved, $unit), Determinability::Determinable];
        }

        if ($kind === T_NAME_FULLY_QUALIFIED) {
            return [QualifierKind::FullyQualifiedName, $this->relationTo(ltrim($text, '\\'), $unit), Determinability::Determinable];
        }
        if ($kind === T_NAME_QUALIFIED) {
            return [QualifierKind::QualifiedName, $this->relationTo($this->qualify($text), $unit), Determinability::Determinable];
        }
        if ($kind === T_NAME_RELATIVE) {
            $tail = preg_replace('/^namespace\\\\/i', '', $text) ?? $text;

            return [QualifierKind::RelativeName, $this->relationTo($this->qualify($tail), $unit), Determinability::Determinable];
        }

        return null;
    }

    private function qualify(string $name): string
    {
        return $this->namespace === '' ? $name : $this->namespace . '\\' . $name;
    }

    /** @param array{kind:UnitKind,name:?string,at:int,bodyStart:int,bodyEnd:int} $unit */
    private function relationTo(string $fullyQualified, array $unit): TargetUnitRelation
    {
        if ($unit['name'] === null) {
            return TargetUnitRelation::DenotesOtherUnit; // an anonymous unit has no name to denote
        }

        return self::fold($fullyQualified) === self::fold($this->qualify($unit['name']))
            ? TargetUnitRelation::DenotesAnalysedUnit
            : TargetUnitRelation::DenotesOtherUnit;
    }

    /**
     * ASCII-only case folding. PHP folds ASCII case in type names and does NOT
     * fold non-ASCII, so a locale-sensitive lowercase would silently corrupt the
     * relation exactly where NEW-9 (non-ASCII identifiers) lives.
     */
    private static function fold(string $value): string
    {
        return strtr($value, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
    }
}
