# KOS port & Hexagonal Architecture — design proposal (no implementation)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ **Design only. Nothing implemented.** No production code touched, no `expected.json`
> change, no `PhpFactExtractor` change, no interface created in code, no Python adapter, no
> `D-1` implementation, no PO/ARB request, no unrelated file touched. This document proposes;
> it does not authorize itself. A separate, explicit act is required before any line of this
> is built — consistent with every prior act in this commission.

---

## A · Evidence basis

Four prior reports in this directory (adapter status reconstruction, `L3` language-
neutrality experiment, semantic-neutrality matrix + `@property` addendum) established, by
execution, not by argument: `L4`/`L5` accept hand-built facts regardless of origin language;
a genuine minimal PHP↔Python parity case matched at the representation level; ten semantic
categories passed; the one real gap (`D-1`) is language-general, not PHP-specific; and the
`@property` case proved that **adapter semantic interpretation, not the canonical model, is
where silent correctness risk actually lives** (naive syntax reading: `LCOM4=2`; correct
semantic reading: `LCOM4=1`; same unmodified core both times).

**Correction accepted from the prior review:** the *functional* boundary
(`PhpFactExtractor::extract()` → `FactSet` → `GraphBuilder::build()`) already exists; the
*architectural* port (a declared abstraction the core owns and infrastructure implements)
does not. This document designs the latter.

## B · Architectural boundary

The boundary is exactly where a `FactSet` is produced. Everything that decides *how source
semantics become facts* (tokenizing, resolving language idiom, the `@property`-style
classification judgment) is adapter. Everything from `FactSet` onward
(`GraphBuilder`/`EdgeRules`/`Lcom4`/`AnalyseCohesion::observe()`) is core, and — confirmed by
reading every one of these classes across this whole investigation — **none of them
reference PHP, Python, or any source-language concept.** This is not aspirational; it was
tested (four prior reports).

## C · Port design — the smallest semantically justified abstraction

Derived from the actual, already-existing call shape, not invented:

```php
interface SemanticFactProvider
{
    public function extract(string $source): FactSet;
}
```

**One method.** `string $source` is the correct input — every field `FactSet`/`DeclaredUnit`
requires is derivable from source text alone (confirmed: `FactSet`'s own `OQ-2` scopes
identity to "one source file," and nothing about that decision is language-specific in
substance, only its wording — already flagged in a prior report). No richer input type is
justified by the evidence.

**Every candidate field was challenged, not copied wholesale:** the port does *not* need to
expose `DeclaredUnit`/`MethodFacts`/`BehaviourReference` fields directly as separate port
methods (a chattier, more "enterprise" design) — `FactSet` already is the correct return
value, because that's the exact shape `GraphBuilder` and `AnalyseCohesion` already consume.
Adding anything more would be speculative abstraction (explicitly against the brief).

## D · Port ownership

**Domain owns it.** The core states what it needs (`FactSet`); infrastructure conforms. This
is the one Hexagonal choice that is not optional — putting the interface in `Infrastructure`
would make the core depend on infrastructure's declaration, inverting the rule the whole
design exists to establish.

## E · Adapter responsibility

Confirmed as **semantic interpretation, not parsing**, by the `@property` result specifically:
an adapter must resolve, for every source construct, what it *means* in the target closed
vocabulary — not transcribe its surface syntax. For a Python adapter this concretely includes
(at minimum, evidenced by the matrix): recognizing `@property`/`@classmethod`/`@staticmethod`
decorators and mapping them to the correct `QualifierKind`/fact-kind choice; resolving which
`self.X` references are stored attributes vs. computed methods; and — explicitly out of
current evidence, flagged `UNKNOWN`, not assumed — descriptor protocols, `__getattr__`,
multiple-inheritance MRO, none of which were tested.

## F · Static `PhpFactExtractor` — three alternatives, evaluated, one recommended

| | 1. Instance façade delegates to the static extractor | 2. Convert extractor itself to instance-based | 3. Separate adapter façade |
|---|---|---|---|
| Change to `PhpFactExtractor` | **None** | Removes `static`, changes every call site's convention | None (same shape as 1) |
| Consistency with existing convention | Preserves the codebase's established pattern — `EdgeRules`, `GraphBuilder`, `Lcom4`, and `PhpFactExtractor` are *all* private-constructor, static-only domain services today | Breaks that consistency for one class only, creating a mixed style with no other class to justify it | Preserves it |
| Dependency inversion achieved | ✅ Yes — the façade is the one thing that's instance-based, exactly where polymorphism is needed | ✅ Yes | ✅ Yes |
| Testability | Trivial — one delegation to assert | Requires re-verifying every existing call site (only found in tests today, §G) | Trivial |
| Risk / blast radius | **Lowest** — zero existing files edited | Real, if small — a public API convention change | Lowest (identical to 1) |

**Note: options 1 and 3 in the original framing are the same design**, described twice from
different angles ("delegates to the extractor" vs. "façade around the extractor"). Recorded
as found, not silently merged without saying so.

**Recommended: Option 1/3** — a small new class, e.g. `PhpSemanticFactProvider implements
SemanticFactProvider`, whose `extract()` body is exactly `return PhpFactExtractor::extract($source);`.
Zero change to tested, working code; the entire cost is one new, nearly trivial class.

## G · Dependency graph — actual, verified, not assumed

```
Infrastructure/Php/PhpFactExtractor  ──depends on──►  Domain (BehaviourReference, DeclaredUnit, FactSet, ...)
Application/AnalyseCohesion         ──depends on──►  Domain (GraphBuilder, Lcom4, CohesionGraph)
Domain (GraphBuilder/EdgeRules/Lcom4) ──depends on──►  nothing outside Domain
```

**The dependency rule is already respected today** — Domain never references Infrastructure.
**What's actually missing is not a violation to fix; it's a composition root that doesn't yet
exist.** Checked directly: `PhpFactExtractor::extract(` is called, in this entire repository,
**only from two test files** (`CohesionPipelineTest.php`, `PhpFactExtractionTest.php`) — no
CLI, script, or production orchestrator invokes this pipeline anywhere today. **There is
currently nothing in production code that would need to change to depend on a port instead
of the concrete class, because nothing in production wires this pipeline together yet.**
This is why designing the port *now*, before that wiring is written, is the right sequencing
— there is no legacy caller to migrate, only a future one to guide correctly from the start.

## H · Package structure (minimal, evidence-derived — not a template)

```
Capabilities/Cohesion/
├── Domain/            (unchanged: L3 facts, L4 rules, L5 metric — already correctly isolated)
├── Ports/              ← NEW: SemanticFactProvider interface only. One file.
├── Application/        (unchanged: AnalyseCohesion)
└── Infrastructure/
    ├── Php/            (unchanged: PhpFactExtractor)
    │   └── PhpSemanticFactProvider  ← NEW: the façade from §F
    └── Python/          (does not exist yet — not created by this document)
```

**`Ports` as its own top-level folder, not nested inside `Domain`**, because the interface is
conceptually part of the core's *contract with the outside*, distinct from the pure semantic
model — this mirrors the existing separation already present between `Domain` and
`Application`, extended by one category, not invented from a generic template.

## I · Adapter conformance (design, not built)

One shared fixture/assertion set, run against every adapter's *output* (not its internals),
comparing — in this order, matching the matrix's own finding that representation must be
checked before metric — units, methods, state facts, behaviour facts, qualifiers,
determinability, exclusions, graph, then metric last. The existing `CohesionSemanticsTest`
(19 cases, already in the repository) is the natural seed for this suite's PHP half; nothing
about it needs to change to serve this purpose.

## J · Differential parity (design, not built)

For a shared, small set of semantically-paired PHP/Python fixtures: run each through its own
adapter to a `FactSet`, assert `FactSet` equality field-by-field before asserting metric
equality — exactly the method the `@property` and true-parity experiments already used ad
hoc; this formalizes it as a standing test category once a real Python adapter exists.

## K · `D-1` placement

**Canonical `L3`, confirmed again by this investigation, not re-argued.** Ownership: the new
fact kind and its `EdgeRules` consequence belong in `Domain`, not in any adapter, and not
duplicated per language — consistent with every prior report. **Not implemented here.**

## L · Implementation slicing (recommended, not executed)

Smallest possible first slice, demonstrating real dependency inversion with zero behavior
change:
1. `Ports/SemanticFactProvider.php` — the one-method interface.
2. `Infrastructure/Php/PhpSemanticFactProvider.php` — the façade (§F).
3. One test asserting `(new PhpSemanticFactProvider())->extract($source)` equals
   `PhpFactExtractor::extract($source)` for an existing fixture — **RED first** (interface
   and façade don't exist yet), then the minimal two files make it **GREEN**.
4. Nothing else changes. `PhpFactExtractor`, `GraphBuilder`, `EdgeRules`, `Lcom4`,
   `AnalyseCohesion` are untouched.

This is deliberately smaller than "wrap the whole pipeline" — it proves the seam works before
anything depends on it.

## M · Risks

- **The façade could be seen as pure ceremony** if no second adapter ever materializes — the
  cost is genuinely small (one interface, one delegating class), but it is not zero, and the
  risk is real: this is the classic "add an interface, nothing implements it twice" hexagonal
  trap the second review message named. Mitigated only by evidence, not by assumption: this
  investigation has now produced real evidence (the `@property` case, the true-parity case)
  that a second implementation is a live, evidenced possibility, not a hypothetical one.
- **No production composition root exists yet (§G)** — meaning this slice, even once built,
  would still only be exercised by tests until something real wires it up. That is an honest
  gap, not hidden by this design.
- **`UnitKind`'s Python mapping (Enum/Protocol/ABC) remains an open interpretive question**
  (prior report) — not resolved by this design, deliberately deferred to whenever real Python
  adapter work is authorized.

## N · Recommendation

**Adopt the design above (Option A port, §F's façade, §H's package placement).** It is
derived entirely from what already exists and what the four prior experiments demonstrated —
no speculative abstraction, no generic framework, no premature Python work. **Authorization
for implementation Slice 1 (§L) is a separate act, not granted by this document.**

---

## Standing implementation rule (recorded now, binding only once implementation is authorized)

**TDD-first, non-negotiable, for every future slice:** write the smallest failing test
expressing the intended semantic behavior (`RED`) → minimal implementation (`GREEN`) →
refactor without changing behavior → re-run the full relevant suite. Hexagonal dependency
direction (`Infrastructure → Ports/Domain`, never reversed) and DDD ownership (Domain owns
identity/invariants; Infrastructure owns language-specific interpretation only) are
architectural *acceptance criteria* for each slice, checked at refactor time, not aspirations
enforced by naming alone. No canonical concept gets a language-specific variant
(`PhpBehaviourReference` etc.) unless a future experiment demonstrates that's unavoidable —
none has, so far. If implementation itself surfaces a semantic contradiction: stop, return to
research, do not silently resolve it inside the refactor.

**No implementation begins without a separate, explicit authorization act. This document is
not that act.**

**Traceability:** `2026-09-27-KOS-adapter-architecture-status-reconstruction.md` ·
`2026-09-27-KOS-L3-language-neutrality-experiment.md` ·
`2026-09-27-KOS-L3-semantic-neutrality-matrix.md` (all this directory) · source read/executed
across all four prior reports · `PhpFactExtractor::extract(` call-site search (this document,
§G) · `.claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json` (unchanged, not touched by
any of this design work).
