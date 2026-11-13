# 01: G-1, the Reference Geography domain kernel (PBDIGIT-346)

**Story:** `PBDIGIT-346` slice G-1 · **Design:** v3.11 §2 and §3 (`docs/publicdigit/architecture/reviews/2026-11-09-reference-geography-tactical-design-baseline-v3.11.md`, D-93), mapped by the standalone completion §3 (D-226)
**Status:** implemented with TDD (four RED → GREEN cycles plus a purity guard). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g1/`.

## Purpose

The building blocks of PublicDigit's trusted atlas, **in pure PHP**:
- what a geographical identity is;
- what a claim about it is;
- which kind of fact a claim asserts;
- when it is valid;
- how a newer claim replaces an older one;
- why two different kinds of fact can never overwrite each other.

**There is no workflow yet:** preparing, approving and publishing come in G-2 to G-4.

## Concepts (all in `app/Contexts/ReferenceGeography/Domain`, final, no framework)

| Concept | File | Meaning (v3.11) |
|---|---|---|
| Identity | `ReferenceUnitId` | PublicDigit's own id of a place: a lower-case UUID, minted once, **never a code or a name** (§2 item 1) |
| Claim id | `ClaimId` | identity of one insert-only claim; a dispute is keyed by it |
| Valid date | `ValidDate` | a real calendar date `YYYY-MM-DD`, received as a value (no clock) |
| Valid interval | `ValidInterval` | `[from, to)`: from inclusive, to exclusive; no `to` = until further notice; empty or reversed refused |
| Claim kind | `ClaimKind` | **exactly 21 kinds** (§3). `SOURCE_NAME` has two shapes, so **22 key shapes**. A withdrawal is not a kind |
| Slot key | `SlotKey` | the thing a claim is about. **One factory per shape;** the key starts with its kind and names exactly what holds one value at a time |
| Claim value | `ClaimValue` | a value, or **none** (a withdrawal, in the target's own slot) |
| Basis | `Basis`, `BasisKind` | the evidence reference. The kernel distinguishes only what I-4 needs (instrument · duplicate-search assessment · other); competence per kind is judged by the G-2 validator |
| Claim | `Claim` | (id, slot, value, interval, basis). Enforces **I-4** |
| Slot history | `SlotHistory` | a slot's published claims by position. **Supersession lives here** |
| I-1 check | `IdentifierAssignment`, `IdentifierInvariants` | one identifier value → at most one live subject at a time |
| I-3 | `IdentityLedger` | an id is minted once; retirement never frees it |

## Why 22 key shapes, and why the encoding is length-prefixed

**The danger:** two different facts mapping to one storage key would let one silently replace the other. v3.1 once gave containment and classification the same key.

**The guarantees:**
- **every key begins with its kind;**
- each component is written as `name=length:value|`, so a value containing `|`, `=` or `:` **cannot forge another key**;
- `SlotKeyCollisionTest` checks each of the 22 shapes, the v3.1 case, the single-vs-set source names, adversarial separators, and 3,000 random keys.

## How supersession works

```php
$h = SlotHistory::for(SlotKey::containment($unit, 'admin'))
    ->with($parentAFrom2015, 1)        // position 1
    ->with($parentBFrom2017, 2);       // position 2, covers [2017-03-10, ∞)

$h->resolve(ValidDate::fromString('2016-01-01'));                    // A: B does not cover 2016
$h->resolve(ValidDate::fromString('2018-01-01'));                    // B: the later position wins where it covers
$h->resolve(ValidDate::fromString('2018-01-01'), upToPosition: 1);   // A: as known at position 1, never rewritten
```

- A claim for **another slot is refused:** claims in different slots never interact.
- A **dispute** has its own slot (keyed by the target claim), so it cannot enter the disputed value's history.
- `with()` returns a new history; the old one is unchanged.

## Testing (executed 2026-11-13)

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography tests/Architecture/ReferenceGeographyDomainPurityTest.php
```

| File | Tests | Proves |
|---|---|---|
| `tests/Unit/Contexts/ReferenceGeography/Domain/IdentifiersAndIntervalsTest.php` | 6 | ids are opaque UUIDs; real dates; `[from, to)` boundaries; adjacency |
| `…/SlotKeyCollisionTest.php` | 9 | 21 kinds, 22 shapes, collision proofs (incl. randomised) |
| `…/SupersessionTest.php` | 9 | supersession by covered time and position; withdrawal; history; slot isolation; I-4 |
| `…/IdentityInvariantsTest.php` | 7 | I-1 (incl. ISO "CS" reuse), I-2 structural, I-3 |
| `tests/Architecture/ReferenceGeographyDomainPurityTest.php` | 6 | imports only itself; no framework, IO, clock or randomness; no personal data; final; planted violations |

## Pitfalls

- **Never use a code (ISO, M49, AGS) as an identity.** Codes are IDENTIFIER claims in a namespace and can be reused (ISO "CS").
- **Never build a `SlotKey` by hand.** Use the shape's factory.
- **I-2 needs no check; don't add one.** The IDENTIFIER slot is (subject, namespace), so one value per date is structural.
- **The basis kinds are deliberately minimal.** Don't add competence rules here; they belong to the G-2 validator.

**Traceability:** PBDIGIT-346 G-1 · v3.11 §2 (I-1 … I-4), §3 · D-93, D-226 · completion §3 and its correction note (21 kinds, 22 shapes).
