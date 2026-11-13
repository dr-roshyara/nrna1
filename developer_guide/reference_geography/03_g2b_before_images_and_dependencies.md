# 03: G-2b, before-images and dependency predicates (PBDIGIT-346)

**Story:** `PBDIGIT-346` slice G-2, step b · **Design:** v3.11 §5.1 (before-images; two dependency forms; gains and losses), §5.2 (subject family); D-93, D-226
**Status:** implemented with TDD. Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g2b/`. **G-2c** (the dependency-recording validator and the invariant checks) follows; it attaches these to a `Revision`.

## In business terms

A revision is validated against the atlas **as it stood at one moment, p₀**. Others may publish in the meantime. The question at publication is: **did anything change that this validation relied on?**
- **A before-image** records, for each new claim, **what it replaced**, date range by date range:
  - a value;
  - a withdrawal ("none");
  - or "absent" (nothing was ever claimed there).

  So a later reader can tell exactly what a change took away.
- **A dependency** records what the validation looked at.
  - **"This slot over these dates"** is stale if anything at all is published there.
  - **"Every unit whose parent is A over these dates"** is stale if someone **gains** that parent (the new value is A) **or loses it** (the before-image shows A).

  **Catching losses is the point:** if C moves from A to B, the new claim says "B", and only the before-image shows that A lost a child.
- **Unrelated publications never make a revision stale:** another country, another scheme, other dates, or anything known at p₀.

## Where it fits (`app/Contexts/ReferenceGeography/Domain`, pure PHP)

| Element | Role |
|---|---|
| `PriorValue` | VALUE · NONE · ABSENT, kept apart |
| `BeforeImage::of($history, $interval, atPosition: $p0)` | the piecewise partition; boundaries where the prior value changes; equal neighbours merge |
| `SlotHistory::priorAt()`, `::boundariesWithin()` | added to G-1's history to compute it |
| `ValidInterval::intersect()` | the common part of two intervals |
| `PublishedClaim` | claim + stored before-image + position |
| `Dependency` (interface) | `isInvalidatedBy(PublishedClaim)`, `describe()` (for the digest) |
| `SlotDependency` | invalidated by **any** claim in the slot over an overlapping interval |
| `FamilyDependency::of($kind, $fixedComponents, $value, $interval)` | invalidated by a **gain** (new value = X) or a **loss** (before-image had X) over an overlapping interval |
| `SubjectFamilyDependency` | every slot about one unit, all time (reconciliation). Incoming mappings are a separate family |
| `DependencySet($p0, [...])` | `firstInvalidating($published)`: ignores positions ≤ p₀ |
| `SlotKey::component()`, `::subject()` | added to G-1's key, so families can match |

## Example (v3.11's own)

```php
// C belonged to A over [2016-01-01, 2016-07-01), to B from 2016-07-01 (positions 1, 2)
BeforeImage::of($history, new ValidInterval(d('2016-03-01'), d('2016-09-01')), atPosition: 2)->segments();
// [ [2016-03-01, 2016-07-01) => A ,  [2016-07-01, 2016-09-01) => B ]

$childrenOfA = FamilyDependency::of(ClaimKind::Containment, ['scheme' => 'admin'], $a, $v);
$childrenOfA->isInvalidatedBy($cMovesToB);   // true: the before-image shows A (a loss)
```

## Testing

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography
```

| File | Tests | Covers |
|---|---|---|
| `tests/Unit/Contexts/ReferenceGeography/Domain/BeforeImageTest.php` | 7 | the v3.11 example; VALUE/NONE/ABSENT; computed at p₀; merging; exact partition; intersect |
| `…/DependencyTest.php` | 11 | every row of §5.1's gains-and-losses table; "not stale" cases; subject family; `DependencySet` and p₀ |

## Pitfalls

- **Compute the before-image at p₀, never against a sibling claim.** R-1 (G-2a) makes that safe.
- **A family dependency without the before-image misses losses.** That was v3.2's defect, corrected in v3.3.
- **The staleness check itself runs under the publication lock in G-4,** using `DependencySet::firstInvalidating`. Stored before-images must equal the belief just before publication (a G-4 test obligation).
- **Loop variables named `$name` in the domain trip the personal-data guard.** Use a descriptive label (this happened again here and was fixed).

**Traceability:** PBDIGIT-346 G-2b · v3.11 §5.1, §5.2 · D-93, D-226.
