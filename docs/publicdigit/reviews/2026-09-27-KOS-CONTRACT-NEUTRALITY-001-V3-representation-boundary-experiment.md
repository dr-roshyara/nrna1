# `KOS-CONTRACT-NEUTRALITY-001` — V-3 representation-boundary experiment (research report)

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-27
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation`, continued as a **research
experiment** (no new grant — nothing tracked is modified)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`

> ⛔ **Non-authoritative research only.** `expected.json` untouched · no governed contract
> file modified · no implementation authorized or accepted · nothing here is treated as
> conformance evidence · PO/ARB is **not** asked to act on this report. All code shown ran as
> throwaway scratch scripts outside the repository, requiring the real domain classes
> read-only. Verified before and after: `git status --short scripts/ tests/ .claude/runtime`
> shows no new changes from this session.

---

## 1 · Experiment objective

Determine whether the proposed `D-1` representation (`IndeterminateBehaviourReference`) can
integrate through the actual `L3→L4→L5` pipeline without an unresolved architectural
inconsistency, and — the specific unresolved question the prior probe surfaced —
what an exclusion-evidence record should look like for a fact with no target name.

## 2 · Experimental setup

Scratch PHP scripts in the session scratchpad, `require_once`-ing the real, unmodified
domain classes (`BehaviourReference`, `StateAccess`, `QualifierKind`, `Determinability`,
`EdgeRules`, `CohesionGraph`, `MethodFacts`, `DeclaredUnit`, `ExclusionReason`, `EdgeVerdict`)
and locally defining a scratch stand-in `IndeterminateBehaviourReference` (not added to the
real codebase). Consumer tracing done by direct grep/read of `CohesionGraph.php`,
`GraphBuilder.php`, `AnalyseCohesion.php`, and `CohesionSemanticsTest.php`.

## 3 · Fixtures

| # | Construct | Purpose |
|---|---|---|
| 2 (control) | `$this->realMethod()`, determinable, in-unit | confirm ordinary path unaffected |
| 3 (`D-4`) | proxy for `call_user_func([$this,'lit'])` | isolate whether `EdgeRules` branches on qualifier or determinability |
| 1 (`D-1`) | `$this->$m()` | the hard case — no legal `targetMethodName` |
| 4 (out-of-scope callable) | `call_user_func([$this->other, $var])` | see §9 — not run; reasoned about directly, see below |

**Fixture 4 was not executed as code.** Per the determination's own §12, an out-of-scope
dispatch is *"not observed by the binding at all"* — there is no L3 extraction logic for it
to test (the binding doesn't exist yet, and building it is implementation, out of this
grant). What *is* checked, empirically, against the real 2026-09-27 corpus:
`app/Services/Google.php:129` — `call_user_func_array([$this->client, $method], $args)` — is
exactly this case (non-`$this` receiver, non-literal method name). **Confirmed by direct
inspection**, not fixture execution.

## 4 · Observations

```
FIXTURE 2 (control):      verdict = INCLUDE                                   [ran; unmodified code]
FIXTURE 3 (D-4 proxy):     verdict = EXCLUDE(NotDeterminable)                  [ran; unmodified code]
FIXTURE 1 (D-1), Model A:  {"method":"caller","target":null,"reason":"NotDeterminable"}
FIXTURE 1 (D-1), Model B:  {"method":"caller","reason":"NotDeterminable"}
FIXTURE 1 (D-1), Model C:  {"method":"caller","factId":"scratch-fact-001","reason":"NotDeterminable"}
```

## 5 · Results

**`D-4`/`D-5` need materially less than `D-1`.** `EdgeRules::verdict()` (line 32-35) excludes
on `determinability===NotDeterminable` **before** it ever inspects `qualifierKind` — this was
read from the code and then **confirmed by execution** (Fixture 3, using an arbitrary
existing `QualifierKind` case paired with `NotDeterminable`, reached the identical branch a
future `ExplicitCallableDispatch` case would). **`D-4`/`D-5` require exactly one new
`QualifierKind` case (a closed-vocabulary amendment) and zero `EdgeRules` changes.** This
sharpens Question 4 of the prior review: I previously said "L4 needs only a trivial rule" for
`V-3` generally — that claim is **confirmed exactly as stated for `D-4`/`D-5`**, and **shown
to be insufficient as stated for `D-1`**, which needs new wiring (§6).

**`D-1` needs a new fact kind, and the fact kind alone is not sufficient — the exclusion
record needs a decision too.** `EdgeRules::verdict()` rejects `IndeterminateBehaviourReference`
with a real `TypeError` (confirmed in the prior probe). Assuming that's bridged with a new,
trivial always-exclude path (as previously proposed), the **evidence record itself** — not
just the L4 verdict — is where the actual open design question lives, per Fixture 1's three
models (§6/§7).

## 6 · Representation alternatives, compared on the requested dimensions

| | **A — `target: null`** | **B — omit `target` key** | **C — `factId` reference** |
|---|---|---|---|
| Invariant compatibility (`INV-L3-5`) | Violates it at the *evidence-record* layer (a documented, non-nullable `string` becomes null) — `INV-L3-5` governs L3 *facts*, not this record, so this is an analogical violation, not a literal one; still a real inconsistency of style | Preserves totality within each shape, but creates *two different shapes* keyed by fact kind — arguably a more subtle collapse-risk (a consumer that forgets to check which shape it has) | Cleanest — no field is ever null or absent; the record simply carries a different, uniformly-present key |
| Provenance implications | None beyond existing (`target` was never provenance; it's a domain field) | None | Reuses `BehaviourReference::$factId`'s **already-documented** purpose ("a separate provenance record joined one-way by `factId`") — the most provenance-consistent option |
| DDD meaning | "Every exclusion has a target, sometimes empty" — a weaker domain statement, arguably false (there IS no target) | "An exclusion sometimes doesn't have a target" — true, but expressed as absence-of-key rather than a modeled concept | "An exclusion is *about a fact*, and what that fact says about its target (if anything) is the fact's own business" — decouples exclusion from needing to know about targets at all; closer to your candidate model in the review |
| L3/L4 boundary implications | None new | None new | Requires `GraphBuilder` to thread `factId` through for **every** exclusion, not just the new kind — touches the existing path too |
| Backward compatibility | **Demonstrated inconsistent** with the documented type in 3 places (`CohesionGraph` ctor, `excludedReferences()`, `AnalyseCohesion::observe()`) — see §5/§8 | **Demonstrated to break** `AnalyseCohesion::observe()`'s current unconditional `$e['target']` read (line 78) — would throw on an undefined array key without a code change there | Also touches `AnalyseCohesion::observe()`, but as a **planned, uniform** shape change rather than a silent break |
| Serialization implications | `null` in JSON where a string was always documented | Variable JSON shape (`target` sometimes absent) — harder for any downstream consumer/schema | Uniform shape; consumer needs a lookup step (fact-by-`factId`) if it wants a human-readable "what was excluded", which nothing currently does (`CohesionSemanticsTest` only reads `reason`, confirmed by grep) |
| Effect on existing consumers | **Traced, not assumed:** only `CohesionGraph.php`, `GraphBuilder.php`, `AnalyseCohesion.php`, `CohesionSemanticsTest.php` reference this data anywhere in the tracked repo. The test reads only `reason` (line 200) — **no consumer anywhere reads `target` today**, and **`expected.json` has zero exclusion-evidence keys** (`G-KOS-CONTRACT-ARTIFACT-UPDATE` confirmed `UNEXERCISED`). **So no authoritative consumer exists to break, for any of A/B/C.** The only real cost is internal consistency/type-honesty within this codebase, not compatibility with any golden record. |
| Generalizes to future indeterminate facts? | Only if every future fact kind happens to have a nullable analogue to whatever `A` nulled out — ad hoc per kind | Same weakness, worse (a growing union of shapes) | **Yes, cleanly** — any future fact kind can be excluded and referenced by `factId` without the exclusion record needing to know its internal shape at all |

## 7 · Domain/DDD analysis — is exclusion a property of the target, or of the fact?

Your candidate model is the stronger one, and the evidence above supports it: **exclusion is
about the fact, not the target.** A `BehaviourReference` already knows its own
`determinability` and (when applicable) its `targetMethodName` — the current
`GraphBuilder::build()` *reconstructs* a redundant summary (`target`) into the exclusion
record instead of simply saying "this fact, identified by `factId`, was excluded, for this
reason." **Model C is the more general domain abstraction**, and it already has a foothold in
the codebase (`factId` exists on `BehaviourReference` today, unused for this purpose). This
generalizes past `D-1`: **any** future fact kind — not just an indeterminate behaviour
reference — could be excluded and reported this same way, with no exclusion-record schema
change required per new kind. That is a real, useful, transferable finding.

## 8 · Logic/invariant analysis

- **`FACT`** (demonstrated by execution or direct code reading, today, this session):
  `EdgeRules::verdict()` type-rejects the new fact kind; the determinability-first branch
  order means `D-4`/`D-5` need no `EdgeRules` change; `CohesionGraph`'s exclusion shape is
  documented `target:string` in three places; the only test touching this data reads `reason`
  only; `expected.json` carries zero exclusion-evidence entries; `G-KOS-CONTRACT-ARTIFACT-UPDATE`
  is `AUTHORIZED`/`UNEXERCISED`.
- **`DERIVED`** (follows deductively from the above, not independently executed): metric
  neutrality for any new always-excluded fact kind, *given correct wiring*; Model C requires
  touching the existing exclusion path (not just the new kind's), because `factId` isn't
  currently threaded through any exclusion at all.
- **`HYPOTHESIS`** (plausible, not tested here): that Model C's uniform shape would be
  adopted cleanly without surfacing a second-order issue once `GraphBuilder` is actually
  rewritten to populate `factId` everywhere; that no external tooling outside this repository
  (e.g. a CI report, a dashboard) depends on the current `target:string` shape — **I have no
  visibility outside this repository to check this**, and say so rather than assume it.
- **`UNRESOLVED`**: which of A/B/C should actually be adopted (a recommendation is offered in
  §13, not a decision); whether `INV-L3-5`'s literal text ("no default and no 'unknown'")
  should be read as applying to this evidence-record layer at all, or only to L3 facts
  themselves — the invariant's own docblock scopes it to `BehaviourReference`/L3 facts, so
  applying it to the exclusion-evidence record is **an analogy this report makes, not a
  textual requirement** — worth PO/ARB or Architecture explicitly confirming scope, not
  assuming it.

## 9 · Statistical/measurement implications

Re-measured today, not carried over from 2026-08-19: `call_user_func`/`call_user_func_array`
in `app/`: **1** occurrence (`Google.php:129`), and it is **not** a `D-4`-relevant construct
(non-`$this` receiver, non-literal name — confirmed by reading the line, not by running a
fixture). `$this->$name` pattern: **7** raw hits, **1** genuine `D-1` call-site
(`DebugVoterSlug.php:67`, `$this->$color(...)`, `$color ∈ {'info','error'}`), the rest
property access (`D-2`/`D-3`, out of scope, untouched). `app/` files changed since the
2026-08-19 analysis: **101** — re-measurement before any future authorization is warranted,
not a one-time check.

## 10 · What was falsified

- **"L4 needs only a trivial always-exclude rule" as a claim covering all of `V-3`** —
  falsified for `D-1` (a `TypeError` is real, not hypothetical; wiring is required beyond a
  rule); **confirmed, not falsified, for `D-4`/`D-5`** specifically.
- **The implicit assumption that `D-1`'s representation question is only about
  `targetMethodName`** — falsified; the exclusion-evidence record is a second, independent
  representation question the prior proposal and adoption did not address at all.

## 11 · What was confirmed

- `D-4`/`D-5` require one closed-vocabulary enum case and nothing else in `L4` — confirmed by
  execution.
- Metric neutrality holds by construction for any correctly-wired, always-excluded fact —
  confirmed by direct code reading of the control-flow (`continue` before edge-append).
- No authoritative consumer of the current exclusion-record shape exists anywhere in this
  repository today — confirmed by exhaustive grep, not assumed.
- The `factId`-reference model (`C`) reuses an existing, documented mechanism rather than
  inventing one — confirmed by reading `BehaviourReference`'s own docblock.

## 12 · What remains unresolved

- Which exclusion-record model to adopt (recommendation in §13, decision deferred).
- Whether threading `factId` through the *entire* existing exclusion path (required for
  Model C's uniformity) has any consequence this experiment didn't probe — it touches more
  surface than `D-1` alone, and that surface wasn't re-verified test-by-test here.
- Whether `INV-L3-5`'s text extends to the evidence-record layer by intent, not just by
  analogy (a question for whoever owns that invariant's scope, not decided here).
- The actual binding-level recognition logic for `D-1`/`D-4` (how `PhpFactExtractor` would
  detect these constructs) — entirely out of this experiment's scope, deliberately.

## 13 · Recommended minimal architecture, if one is justified

**Recommended, not decided:** adopt **Model C** (exclusion references `factId`, not
`target`) as the general shape for *all* exclusions going forward, not just `D-1`'s — because
(a) it's the only model with zero remaining representation gap for `D-1`, (b) it generalizes
to any future fact kind without further schema change, (c) it has zero backward-compatibility
cost today (no authoritative consumer exists to break), and (d) it reuses a mechanism the
codebase already committed to (`factId`) rather than adding a second, competing one. The cost
— touching the existing exclusion path, not just the new one — is real but bounded and
already unauthorized either way (this is implementation, deferred regardless of which model
is chosen).

## 14 · Exact implications for the future PO/ARB decision

- **`D-1`, `D-4`, `D-5` should not be presented as one bundled "vocabulary incorporation"
  question** — `D-4` is a real (narrow) semantic boundary decision; `D-1` and `D-5` are
  representation/naming, not semantics (per the prior review's correction, preserved here).
- **A fourth item now exists for PO/ARB's eventual attention, not yet asked**: the exclusion-
  evidence record's shape (target-based vs. fact-reference-based) — this is architecturally
  separable from `D-1`/`D-4`/`D-5` themselves and could be decided on its own timeline, but
  should not be silently decided by whoever implements first.
- **No contract-incorporation brief should go to PO/ARB yet.** The representation question
  this experiment was commissioned to resolve is **not fully resolved** — a recommendation
  exists (§13), not a demonstrated, accepted design.
- **The closed-vocabulary framing should be stated precisely if this ever reaches a decision
  document:** closed **under the current contract version**, amendable by a governed act
  (exactly how `QualifierKind`, `ExclusionReason`, `AccessMode` already work in this
  codebase) — not permanently closed against all future theory evolution. This report
  adopts that framing throughout and does not repeat the earlier, over-strong phrasing.
- **ML remains out of scope for implementation**, for the reason already given (a closed,
  deterministic semantic boundary, not a predicted one) — documented here as a standing
  architecture principle for any future, larger-corpus phase of this programme: ML may assist
  *candidate discovery, anomaly detection, clustering, and review prioritization*; it must
  never become the authority for semantic/ontological classification.

**Traceability:** `2026-09-27-...-V3-incorporation-decision-brief.md` (superseded in
sequence, not in content — its options remain valid, just premature) ·
`2026-09-04-...-V3-D1-D4-D5-ADOPTED.md` · `2026-09-04-...-V3-D1-D4-D5-proposal.md` ·
`2026-08-19-...-v3-decisions-registration.md` (+Amendment 1) ·
`2026-08-19-...-V3-architecture-determination.md` · source read directly: `BehaviourReference.php`,
`StateAccess.php`, `EdgeRules.php`, `QualifierKind.php`, `Determinability.php`,
`GraphBuilder.php`, `CohesionGraph.php`, `MethodFacts.php`, `DeclaredUnit.php`,
`AnalyseCohesion.php`, `CohesionSemanticsTest.php`, `PhpFactExtractor.php` ·
`G-KOS-CONTRACT-ARTIFACT-UPDATE` (+amendments, confirmed `UNEXERCISED`) · `INV-L3-5` ·
scratch probes (session scratchpad, not part of the repository).

**STOP after this report.** No `expected.json` change. No PO/ARB request. Next actor: this
research thread continues only on further direction — either refining the exclusion-record
question, or (separately) taking the now-corrected, still-pending incorporation question to
PO/ARB when that is actually wanted.
