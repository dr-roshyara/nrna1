# KOS — `D-1` missingness-taxonomy resolution

**Date:** 2026-09-28. **Phase:** clarification/classification only. Follows
`2026-09-28-KOS-theory-integration-gate.md`, which left this open rather than proposing a
fifth missingness kind on incomplete evidence.

> ⛔ Read-only. No theory object, `Sat` definition, or missingness taxonomy modified. No
> production code, test, or `expected.json` change. No experiment executed. Not committed.
> The theory-construction programme's `⛔⛔ RESEARCH STOPPED` state is unchanged.

---

## 1 · Research question

Is `D-1` (`$this->$m()`/`getattr(self,name)()`: a relationship is observed, its concrete
target cannot be determined by the analysis method used) already representable by the
existing four-kind missingness taxonomy under its own actual definitions and worked examples
— or does it require a genuinely new distinction?

## 2 · Existing `UNOBSERVABLE` definition, found this pass

**Operational definition, from the taxonomy's own worked-example table**
(`docs/knowledgeos/research/theory-v1.1-simulation/D-scenario-catalog.md` §6):

> `UNOBSERVABLE` — **"no channel exists."**

Confirmed independently, at a higher level, by the theory-extraction programme's own
definition-evolution record
(`docs/knowledgeos/theory-extraction/105-READ-RECORD-2026-09-02-SAT-THREAD-AND-THE-APPROVED-
TODO-REGISTER.md` §2.3), which classifies **all** of `U`'s readings into exactly three kinds:

$$
\mathsf U \;=\; \underbrace{\text{epistemic }\mathsf U}_{\text{UNOBSERVED · UNINTERPRETED ·
UNDERDETERMINED · …}} \;\big|\; \underbrace{\text{theory }\mathsf U}_{\text{NO\_EVALUATOR ·
NO\_ORDERING · …}} \;\big|\; \underbrace{\text{world }\mathsf U}_{\text{UNOBSERVABLE}}
$$

**`UNOBSERVABLE` is not one member of a flat four-way list — it is its own, structurally
distinct category ("world `U`"), separate in kind from `UNDERDETERMINED`/`UNOBSERVED`/
`UNINTERPRETED` (all "epistemic `U`").** This is the single most decisive fact this search
found, and it was not visible from the material the prior gate had read.

## 3 · All relevant examples found

| Scenario | Family | Construction | Classification |
|---|---|---|---|
| **B** | Incomplete evidence | target `reachable`; **no channel for it at all** | `UNOBSERVABLE` |
| **J** | Unobservable property | `reachable` with no channel at all | `UNOBSERVABLE` |
| **C** | Competing hypotheses | evidence reads `rpm-family` → `{RHEL9.8, RHEL8.6}` | `UNDERDETERMINED` |
| **F** | Conflicting evidence | source A says `8081`, B says `8082` | `UNDERDETERMINED` |

Both `UNOBSERVABLE` instances (B, J) share the identical construction: a property for which
**no measurement instrument is defined in the model at all** — not "an instrument exists but
wasn't used," not "an instrument exists but this analysis can't run it." Both
`UNDERDETERMINED` instances (C, F) share: a **specific evidentiary artifact exists** and
narrows the target to a **finite, enumerable set** of more than one candidate, without
resolving further.

**Guard confirmed, not merely stated:** `P13` — *"confirms `UNOBSERVABLE ≠ UNDERDETERMINED` in
both the deterministic and randomized layers (guard active in 37.4% of 10,000 trials, 0
failures)"* — this boundary is load-bearing and automatically tested in the theory's own
simulation, not a loose convention.

## 4 · Observer model

Not agent-indexed in the material found. `UNOBSERVABLE`'s condition ("no channel exists") is
stated as a property of the **model's available instrumentation**, not of a specific querying
agent. `105-READ-RECORD...` §2.3 explicitly warns against the opposite conflation:
*"`Sat_governance = U` does not mean the system cannot determine it. It means there is
currently..."* [no authority supplied] — i.e., the theory already distinguishes "genuinely no
channel" from "a channel's precondition (e.g., an authority) is currently unsupplied," and
places the latter under a **different** category (`theory U` / `governance` class), not
`UNOBSERVABLE`.

## 5 · Remediability model

- `UNOBSERVABLE` (B/J): **not remediable within the model as specified** — there is no
  instrument to supply, at any point, without changing what the model measures.
- `UNDERDETERMINED` (C/F): remediable **in principle**, by additional evidence that narrows
  `A_t` further — the taxonomy does not require the remediation to actually happen, only that
  the *kind* of gap is "more evidence could narrow this," not "no evidence-gathering
  mechanism exists at all."

`D-1`: **remediable in principle** — execution/runtime tracing is a real, existing channel
that would determine the exact target. Static analysis simply does not open it. This places
`D-1` on the `UNDERDETERMINED` side of the remediability test, not the `UNOBSERVABLE` side.

## 6 · `D-1` characterization

The call site's own syntax (`$this->$m()`) is itself evidence: it establishes that (a) a
method invocation occurs, (b) the receiver denotes the analysed unit, and (c) the target is
drawn from the finite, statically-enumerable set of methods the receiver's class declares (or
inherits). This is a real evidentiary artifact narrowing the target to a **finite set with
more than one member** in the general case — structurally identical in shape to Scenario C's
`{RHEL9.8, RHEL8.6}`, not to Scenario B/J's "no instrument at all."

## 7 · Comparison with all four existing categories

| Category | `D-1` fits? | Why | Evidence |
|---|---|---|---|
| `UNOBSERVED` | ❌ No | This requires *"a channel exists, never queried."* The call site *is* queried — the extractor reads the token stream at that exact position; the gap is not a skipped query. | `D-scenario-catalog.md` §6 definition |
| `UNINTERPRETED` | ❌ No | This requires *"observation held, no semantic content assigned."* The site is assigned rich semantic content (it *is* recognised as a computed-method-name dispatch, precisely) — nothing is uninterpreted. | Same |
| **`UNDERDETERMINED`** | ✅ **Yes** | *"Evidence present, `\|A_t\| > 1`."* The receiver's declared method set is exactly this evidence; the target is one of several statically-enumerable candidates, unresolved by static means — the same shape as Scenario C. | Same; §6 |
| `UNOBSERVABLE` | ❌ No | Requires *"no channel exists"* — categorically, "world `U`." A channel (execution) genuinely exists; it is merely not the channel this static analysis opens. Conflating "unused channel" with "no channel" is precisely the confusion `105-READ-RECORD` §2.3 warns against for the adjacent `governance` case. | `D-scenario-catalog.md` §6; `105-READ-RECORD...` §2.3 |

## 8 · Logical analysis

The instruction's own framing question — is `D-1` a new global truth value, or structured
uncertainty about a field of an already-true proposition — resolves cleanly: **the latter.**
*"A behavioural dependency exists"* is not itself uncertain in `D-1` (unlike genuine
`UNOBSERVABLE` cases, where even existence is unresolved) — what's uncertain is a single
attribute (*which* method) of an otherwise-affirmed relation. This is exactly
`UNDERDETERMINED`'s own shape (Scenario C: the relation "the OS family is one of two RPM
distributions" is affirmed; only *which one* is undetermined) — not a new truth state.

## 9 · Mathematical analysis

Given §2–§8 already resolve the question from existing worked definitions, the
indexed-observability-predicate machinery (`Obs_A(x)`) proposed in the prior gate's framing
is **not required** — introducing it now would be exactly the "mathematical structure
proposed because it sounds appropriate" the instruction warns against, when a simpler,
already-established distinction (`UNDERDETERMINED` vs. `UNOBSERVABLE`, "world `U`" vs.
"epistemic `U`") already does the necessary work.

## 10 · Statistical considerations

Not applicable to this classification — it turns on a definitional/structural match found
deterministically (exact textual search + worked-example comparison), not on any sampling
question. No probability claim is made or needed.

## 11 · ML-assisted search

Not used. Deterministic search (`grep` across the full `docs/knowledgeos/` tree for
`UNOBSERVABLE`, cross-checked against `UNDERDETERMINED`'s own worked examples) located the
decisive evidence directly and cheaply; per the instruction's own ordering (deterministic
search first, ML only if that proves insufficient), no further tool was warranted.

## 12 · Falsification criteria (fixed before the classification above was finalized)

- **The `D-1`-fits-`UNDERDETERMINED` claim would be weakened** if any authoritative worked
  example showed `UNDERDETERMINED` applied only to cases with a small, *fixed* cardinality
  (e.g., always exactly 2, as in both found examples) and explicitly excluded larger or
  variable-sized candidate sets. **Not found** — the abstract definition (`\|A_t\| > 1`) states
  no upper bound, and nothing in the corpus searched this pass restricts it.
- **The `D-1`-is-`UNOBSERVABLE` claim (the prior gate's tentative lean) is falsified** by the
  existence of even one case where a channel exists in the world but the classification is
  `UNOBSERVABLE` anyway — **not found**; both located instances (B, J) are constructed
  specifically as "no channel at all," and the three-way `U` taxonomy (`105-READ-RECORD` §2.3)
  independently places `UNOBSERVABLE` in a different category from anything
  channel-exists-but-unused.

## 13 · Result

> ## **RESULT A — existing category sufficient.**

`D-1` is already representable by the existing taxonomy: it is `UNDERDETERMINED`, not
`UNOBSERVABLE`. No new category is needed. No clarification of `UNOBSERVABLE`'s wording is
even required — the definition, read correctly against its own worked examples, already
excludes `D-1`'s case cleanly.

## 14 · Evidence supporting the result

`D-scenario-catalog.md` §6 (the operational "channel exists" definition and both category's
worked examples, B/J vs. C/F) · the three-way `U` taxonomy separating "world `U`"
(`UNOBSERVABLE`) from "epistemic `U`" (`UNDERDETERMINED` among others) ·
`105-READ-RECORD-2026-09-02-SAT-THREAD-AND-THE-APPROVED-TODO-REGISTER.md` §2.3 (the explicit
warning against conflating "not currently supplied" with "cannot be determined," applied by
this pass to the channel-exists-but-unused case exactly as that document applies it to the
governance case) · the `P13` guard confirming this boundary is tested, not merely asserted.

## 15 · What remains unresolved

Whether the Cohesion capability's own implementation (`IndeterminateBehaviourReference`)
*should*, architecturally, be reshaped to reflect an `UNDERDETERMINED`-shaped representation
(e.g., carrying the enumerable candidate set) rather than its current zero-field, fully
opaque design — **not decided here, and explicitly out of this gate's authority** (no
implementation, no theory change authorized). This is a genuine, interesting implication
(§16), not a next action.

## 16 · Exact next gate, if any

None proposed by this pass as urgent. The theory-integration question this thread opened is
now closed with a clean result (A), removing the one open seam the prior gate found. Per
§16 of the instruction and the stop conditions below, this pass does not propose Zero,
canonicalization, provenance, or any further missingness work as a next gate — those remain
exactly where `2026-09-28-KOS-theory-integration-gate.md` left them (deferred, not
re-opened).

---

## Correction disclosed

The prior Theory Integration Gate (§5) leaned toward `D-1` sitting "closest to
`UNOBSERVABLE`," on a bounded search that had not yet found the operational "channel exists"
definition or the `UNDERDETERMINED` worked examples. That lean is **superseded** by this
pass's more complete search — `D-1` fits `UNDERDETERMINED` cleanly, and does not require
`UNOBSERVABLE`'s wording to be clarified at all. Disclosed here rather than silently revised,
matching this thread's own established convention.

---

**STOP after this report.** No fifth category proposed. No theory modified. No implementation
performed. Not committed.

**Traceability:** `2026-09-28-KOS-theory-integration-gate.md` (superseded in part, §5, by
this document) · `docs/knowledgeos/research/theory-v1.1-simulation/D-scenario-catalog.md` ·
`docs/knowledgeos/theory-extraction/105-READ-RECORD-2026-09-02-SAT-THREAD-AND-THE-APPROVED-
TODO-REGISTER.md` §2.3 · `docs/knowledgeos/theory-extraction/reconstruction/
05-DEFINITION-EVOLUTION-REGISTRY.tsv` (row `Just_c`, confirming the 9-reason-code vocabulary
including `UNOBSERVABLE` as one of them, class-indexed) ·
`2026-09-28-KOS-D1-implementation-results.md`.
