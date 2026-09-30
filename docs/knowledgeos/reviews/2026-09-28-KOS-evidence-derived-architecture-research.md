# KOS — Evidence-Derived Architecture Research (Research Gate 2)

**Date:** 2026-09-28. **Phase:** derive the smallest theory/architecture the strongest evidence
actually forces — not catalogue the corpus, not resolve its governance history. Follows
`2026-09-28-KOS-architecture-reconstruction-report.md` (treated here as evidence, not as target
architecture) and the same-day Constitution-ratification resolution (treated as a disclosed,
closed side-finding — not re-opened, per the explicit instruction not to make contradiction
resolution the main track).

> ⛔ No new theory, capability, or code proposed. No implementation performed. The
> theory-construction programme's `⛔⛔ RESEARCH STOPPED` state is untouched. Existing tests were
> read, not re-run; no new experiment was executed.

**On ML:** no embedding/clustering tooling was available or invoked in this pass. Where the
brief calls for ML-assisted discovery, this report substitutes close, evidence-cited textual
comparison across independently-produced documents (the same method already used successfully
earlier this session to find term collisions) and says so plainly rather than claiming a
clustering run that didn't happen. This is disclosed as a real limitation, not smoothed over.

---

## 1 · Research question

> **What transformation architecture is required to explain the strongest empirical evidence
> while introducing the fewest unsupported assumptions?**

Not "which existing KnowledgeOS architecture should we adopt."

## 2 · Evidence sources (this pass)

- **Cohesion capability** — real PHP+Python code, implemented and tested this session
  (`scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/`). First-hand.
- **Governance/workflow engine** — real PHP code (`workflow-state.php`, `session-bootstrap.php`),
  verified via `EKS-07`'s own citations.
- **`RA v1.0`** (`docs/knowledgeos/architecture/20260822-0955-...md`) — read in full, primary
  source, this pass.
- **`v0.1`/`v0.2` canonical model** (`docs/knowledgeos/reviews/synthesis/model/canonical-architecture-v0.2.md`
  and its v0.1 predecessor) — read in full, primary source, this pass.
- **`FA-1`/`FA-3`/`FA-9`** — verified directly, prior pass, this session.
- Theory-inventory findings (`Sat`/`Zero`/`Γ`/`K_t` status) — verified directly, prior pass.

## 3 · Evidence classification (never collapsed)

| Claim | Class |
|---|---|
| Cohesion's `L3`/`L4`/`L5` facts are cross-language identical (11 mechanisms) | **EMPIRICAL, TESTED, this session** |
| `v0.2`'s `I-5`/`I-6` (no duplicate-amplification, dependency-before-aggregation) | **EMPIRICAL, TESTED** (`EXP-01`, cited in `v0.2` itself) |
| `v0.2`'s admission ladder (`Candidate⋖Supported⋖Accepted⋖Committed`, no-skip) | **SPECIFIED, FORMALLY AXIOMATIZED** (`I-12`), **zero implementation evidence found** |
| `DC(d)` decision-contract 6-tuple | **SPECIFIED (schema)**, never instantiated anywhere found |
| `K_t` canonical form | **CONTRADICTED** — corpus's own verdict: no supersession chain survives review |
| `I-10` (governance approval required for Constitution changes) | **SPECIFIED, and self-reportedly VIOLATED in the real repo** (v0.2's own text, citing "Step 121") |

## 4 · Transformation inventory — the hypothesized chain tested against both ratified text and real code

Hypothesis: `World → Observation → Interpretation → Canonical Representation → Relations →
Determination → Knowledge State → Decision → Action → New Observation`.

| Stage | `v0.2` (ratified, text) | Cohesion (real code) | Governance engine (real code) |
|---|---|---|---|
| World/Source | `X_t`, `I-7`: *"X_t ≠ Observed(X_t)"* — SPECIFIED | source file — REAL | a work item — REAL |
| Observation | partial observation of `X_t` — SPECIFIED | token stream read — REAL, TESTED | `REGISTER` — REAL, TESTED |
| Interpretation | *"SourceObs ≠ SemanticObs"* — SPECIFIED | PHP/Python AST semantic recognition — REAL, TESTED | n/a |
| Canonical representation | `Evidence` (dependency+role) — TESTED (`EXP-01`) | `L3` facts (`BehaviourReference`/`StateAccess`) — REAL, TESTED, cross-language | n/a |
| Relations/structure | `K_t` (8 primitives incl. `Relation`) — SPECIFIED, TESTED within scope | `L4` graph (`CohesionGraph`) — REAL, TESTED | n/a |
| Determination | `Candidate→Supported→Accepted` (`I-12`, no-skip) — SPECIFIED, **not implemented anywhere found** | **absent** — `EdgeRules` is a deterministic classifier, not an epistemic-status ladder | `HANDOFF` — REAL |
| Knowledge state / Commitment | `Committed` via authority `A6` — SPECIFIED, no instantiation found | `L5` metric (a number) — REAL but carries none of `Knower`/`Authority`/`Committed` | human-gated `START` — REAL, TESTED (this **is** an authority-commitment act) |
| Decision | `DC(d)` 6-tuple — SPECIFIED (schema only) | **absent** | verdicts (ACCEPT/REJECT) — REAL |
| Action | *"AUTHORIZED ACTION"*, explicitly **advisory-only, never self-executes** (RA v1.0's Decision Interlock service) | **absent** | governed transitions, deliberately manual (`EKS-07` follow-up #4 ruling) — REAL |
| New observation (feedback) | SPECIFIED as closing the loop | **absent** (one-shot analysis) | implicit (next work item) |

**The single most important finding of this pass:** the hypothesized chain is not implemented by
any one system — it is implemented in **two disconnected halves by two different, unrelated real
pieces of software.** Cohesion empirically, rigorously realizes the *front half* (source →
observation → interpretation → canonical representation → relations). The governance/workflow
engine empirically, rigorously realizes the *back half* (determination-like gating → authority
commitment → decision-verdict → deliberately-manual action). **The middle joint — canonical
structure becoming an epistemic determination — has never been bridged by any real code found in
this investigation.** This is a sharper, more falsifiable statement of the architecture report's
earlier "Cohesion and `FA-1` were never connected" finding — it locates *exactly where* the break
is, not just that one exists.

## 5 · Empirical invariants (evidence-supported, not asserted)

- **`INV-L3-5`** (Cohesion, real code): a `BehaviourReference`'s six attributes are always total —
  no default, no "unknown." Enforced by constructor totality, tested.
- **`I-9`** (`v0.2`, text): `Zero`'s discrepancy typology *"must not collapse to Boolean."*
  **Independently, empirically instantiated** by Cohesion's own `D-1` solution this session
  (`IndeterminateBehaviourReference` — a distinct, non-Boolean, preserved "can't-determine" state,
  arrived at without reference to `I-9`, converging on it anyway).
- **Sole-writer / single mutation owner** (`Inv A`, `Inv C`, `workflow-state.php`, real code,
  cited via `EKS-07`).
- **`INV-ATTR-1`/`INV-ATTR-2`** (real code + ruling): self-declared identity is evidential, never
  attested; no gate reads it as authority.
- **Kernel-never-reasons** (RA v1.0, text: *"a kernel service never generates a conclusion"*) —
  **independently matched** by Cohesion's own `EdgeRules`, which classifies/excludes but never
  interprets semantics (interpretation happens upstream, in the adapters) — a second,
  unplanned convergence between real code and ratified text.
- **Advisory-only, human-gated commitment** — both RA v1.0's Decision Interlock (*"terminates flow
  at recommendation, never executes"*) and the governance engine's mandatory human `START`
  (`G-3`, and `EKS-07`'s explicit follow-up ruling that automating `REGISTER`/`HANDOFF`/`START`
  would be "a new governance capability," deliberately not smuggled into any correction) —
  **the same principle, independently enforced by two unrelated real systems.**

## 6 · Candidate semantic kernel — status per object, using the required vocabulary only

| Candidate object | Empirical evidence | Logical necessity | Mathematical form | Implementation evidence | Status |
|---|---|---|---|---|---|
| Canonical semantic fact (`L3`-style) | Cross-language, 11 mechanisms, 0 divergences | Yes — any language-neutral analysis needs one | Typed product record, total fields | Strong (PHP+Python, tests) | **SUPPORTED** |
| Relationship graph (`L4`-style) | Real, tested | Yes, for any structural metric | Labelled digraph / total classification function | Strong | **SUPPORTED** |
| "Can't-determine" as a distinct preserved state (not a default) | `D-1`, real code, this session | Yes — forced by `INV-L3-5`'s totality once a naive nullable fix is ruled out | Sum/variant type (marker type disjoint from the main record) | Strong, cross-language | **SUPPORTED** — and the clearest convergence point found between empirical engineering and ratified theory |
| Epistemic-status ladder (`Candidate→Supported→Accepted→Committed`) | None implemented anywhere found | Plausible, formally axiomatized (`I-12`) | Covering relation / strict partial order, no-skip | **None** | **PLAUSIBLE** |
| `DecisionContract DC(d)` | None instantiated anywhere found | Plausible (standard contract-based-design shape) | 6-tuple `(Pre,Inv,Auth,Post,Temporal,Evidence)` | **None** | **REQUIRES_EXPERIMENT** |
| `K_t` (canonical knowledge state, concrete form) | Corpus's own verdict: no form retained as canonical | — | — | None | **CONTRADICTED** |
| `Sat` (concrete form) | ~336 forms found, incl. an unremarked arity regression | — | — | Partial (`satc_spec.py`, 3/8 classes executable) | **CONTRADICTED** |
| `Zero` (concrete `Zero(K,EC)` form) | ~440 forms, two incompatible families | — | — | 4 rival implementations exist side by side | **CONTRADICTED** |
| Kernel-as-classifier-never-reasoner (architectural principle) | Independently re-derived by Cohesion's `EdgeRules` **and** stated directly in RA v1.0's ratified text | Yes | Total/partial classification function, no inference | Strong (Cohesion) + textual (RA v1.0) | **SUPPORTED** |
| Advisory-only / human-gated commitment (architectural principle) | Independently enforced by RA v1.0's text **and** the governance engine's real code | Yes | Gate requiring an external authority token before state mutation | Strong (governance engine) + textual (RA v1.0) | **SUPPORTED** |

## 7 · Formal/logical analysis

No advanced structure (category theory, measure theory, topology, matroid theory) is required by
anything actually evidenced. What the evidence forces:
- **Total functions / classifiers** — Cohesion's `EdgeRules` (source-fact → edge-or-excluded, with
  a reason), a total function over a finite, enumerable domain.
- **Sum/variant types** — the `BehaviourReference | IndeterminateBehaviourReference` pattern; the
  same shape `v0.2`'s `Zero` typology implicitly requires (non-Boolean, so at minimum a small
  finite enum, not a bit).
- **A strict partial order / covering relation** — `I-12`'s no-skip admission ladder, *if* it is
  ever implemented (currently unimplemented anywhere found, so this stays a candidate requirement,
  not a demonstrated one).
- **A labelled graph** — `L4`, real and tested.
- **A 6-tuple contract schema** — `DC(d)`, specified but uninstantiated; nothing found requires
  more than a plain tuple/record for it.

No lattice, no category, no measure-theoretic structure is forced by anything actually observed —
consistent with the instruction's own caution against introducing structure the evidence doesn't
require.

## 8 · Statistical evidence quality

- Cohesion's cross-language-neutrality claim: **population** = every `L3` fact-emission mechanism
  exercised; **sample** = 11 mechanisms checked (3 rounds); **observed** = 9/11 byte-identical, 2/11
  legitimate spelling differences with identical decisional fields; **replication**: the "different
  spelling, same semantics" pattern confirmed independently 3 times before stopping (documented,
  disclosed stopping rule — not exhaustive).
- `v0.2`'s `I-5`/`I-6`: **tested** via one named experiment (`EXP-01`) — single replication, not
  independently repeated elsewhere found.
- `K_t`/`Sat`/`Zero` form-counts (~336, ~440, etc.): these are **census counts of a corpus**, not
  samples of a population in the statistical sense — treat as descriptive evidence of corpus
  disorder, not as a probability estimate of anything.
- **Never treat:** "not observed" as "impossible" (the missingness taxonomy's own `UNOBSERVABLE`
  vs. `UNDERDETERMINED` distinction, already resolved this session, exists precisely to prevent
  this collapse) — nor "observed once" (`EXP-01`, `D-1`) as a general law.

## 9 · ML-assisted discoveries

**None performed — no clustering/embedding tool was available in this environment for this pass.**
The term-collision findings reported in the architecture-reconstruction report (`Sat` ~336 forms,
`Zero` ~440 forms, "kernel" 5 senses) were produced by the corpus's *own* prior programme via
manual/lexical comparison, not by this pass, and are cited as such, not re-derived. This is stated
as a limitation, not silently worked around.

## 10 · Cohesion generalization analysis

What Cohesion demonstrates, generalized beyond its own PHP/Python specifics:

- **Genuinely canonical / reusable:** the *pattern* of separating "recognize this construct" (adapter-specific)
  from "classify this fact" (kernel, language-neutral) from "aggregate into a metric" (analysis) —
  proven to transfer across two structurally different languages without changing the kernel.
- **Genuinely canonical:** "can't-determine" as a first-class, preserved, non-defaulted state
  (§6) — this is the one piece of Cohesion that maps directly onto a ratified theoretical
  requirement (`I-9`) independently.
- **Language-specific / accidental:** the exact PHP/Python syntax recognizers (`_is_getattr_self_dispatch`,
  the `T_VARIABLE`-vs-`T_STRING` branch) — explicitly, by this capability's own developer guide,
  "bounded and deliberately incomplete," not meant to generalize further than what's measured.
- **Not demonstrated at all:** anything about `Knower`, authority, commitment, decision, or action
  — Cohesion never claimed this and doesn't evidence it either way.

## 11 · Historical/ratified architecture vs. empirical architecture (kept separate, per instruction)

| | Historical/ratified (`FA-1`/`RA v1.0`/`v0.2`) | Empirical (real, running code) |
|---|---|---|
| Front half (source→canonical→relations) | Specified abstractly (`Evidence`, `K_t`) | **Realized**, tested, cross-language (Cohesion) |
| Middle joint (relations→determination) | Specified (`I-12` covering relation) | **Not realized anywhere found** |
| Back half (commitment→decision→action) | Specified (`A6`, `DC(d)`, advisory-only) | **Realized**, tested (governance/workflow engine's `START`/verdicts) — though `DC(d)`'s formal schema itself is never instantiated |
| Kernel-never-reasons principle | Stated directly (RA v1.0) | **Independently re-derived** (Cohesion's `EdgeRules`) |

## 12 · Candidate DDD boundaries (derived from invariants found, not imposed)

Per instruction, only where an invariant actually requires transactional consistency:
- **A "canonicalization" boundary** — owns the source→`L3` transformation; invariant protected:
  totality (`INV-L3-5`); evidence: real, tested. This is the only boundary with **both** a named
  invariant **and** real enforcing code found in this pass.
- **A "commitment" boundary** — owns the authority-gated state mutation; invariant protected: `G-3`
  (mandatory human `START`), sole-writer (`Inv A`/`Inv C`); evidence: real, tested.
- **No boundary is proposed for "determination"** (`Candidate→Supported→Accepted`) — no invariant
  has an enforcing implementation; proposing an aggregate here would manufacture a boundary the
  evidence doesn't yet require, exactly what the instruction warns against.

**Not proposed:** `KnowledgeAggregate`, or any RA v1.1-style bounded-context set — no invariant
found in this pass requires them, and RA v1.1's own content remains unverifiable without opening
its unmerged branch (a boundary this investigation continues to respect).

## 13 · Candidate architecture center

Not `K_t` (contradicted, no canonical form). Not any single ratified document (object-level
correspondence to implementation is explicitly unestablished). Not Cohesion alone (never claims
the back half). **The strongest, most falsifiable candidate found this pass:**

> **A KnowledgeOS kernel is a classifier/gate — it partitions inputs into preserved, distinct
> states (including "not determinable" as its own first-class state) and never generates a
> conclusion or executes an action. Everything past classification requires a separate,
> externally-authorized act.**

This is not a component — it's a **principle**, independently instantiated by two unrelated real
systems (Cohesion's `EdgeRules`; the governance engine's authority gate) and stated directly in
the one ratified text that discusses kernels at all (RA v1.0). It is the only candidate "center"
with real, repeated, cross-system empirical support found in this investigation.

## 14 · Competing hypotheses

1. **The two half-chains are meant to be one system** and the missing middle joint is a real,
   currently-unbuilt gap — the next legitimate KnowledgeOS engineering step is building that
   bridge (canonical facts → epistemic-status ladder).
2. **The two half-chains are meant to stay separate** — Cohesion is a general-purpose
   "canonicalize-and-classify" capability usable by *any* future kernel, not specifically bound to
   the epistemic/`K_t` apparatus at all, and the apparent "gap" is not a defect but a boundary.
3. **The epistemic apparatus (`Candidate→Supported→Accepted→Committed`, `DC(d)`) is
   over-specified relative to any evidence that it's needed** — nothing found in this pass shows a
   real consumer requiring the full ladder; the governance engine's actual real states (`REGISTER`/
   `HANDOFF`/`START`/verdict) are coarser and already sufficient for what's actually enforced.

None of these is adopted here. (3) is weakly favored by the evidence (nothing implements the finer
ladder, and the coarser real states already do the enforced work) but this is not a conclusion,
only the most evidence-consistent of the three.

## 15 · Falsification tests

- **Hypothesis 1 falsified if:** a real, tested implementation of the `Candidate→Supported→Accepted`
  ladder is found anywhere in the corpus that this pass didn't locate.
- **Hypothesis 3 strengthened if:** a governance/workflow record is found where the coarser real
  states (`REGISTER`/`HANDOFF`/`START`/verdict) demonstrably fail to express a distinction the
  finer ladder would have caught — none found in this pass, but this pass did not search
  exhaustively for one.
- **The "kernel-as-classifier" candidate center (§13) is falsified if:** any real, governed
  KnowledgeOS code is found where a classification/gate component itself makes a determination or
  triggers an action without a separate authorization step — none found; not exhaustively
  searched.

## 16 · Highest-information next experiment

Per the instruction's own ranking (information gain, falsifiability, cost): **search specifically
and narrowly for any real implementation of the `Candidate→Supported→Accepted→Committed` ladder
or the `DC(d)` decision-contract schema anywhere in the repository's actual code** (not more
architecture documents) — a `grep`-bounded, cheap check that would directly falsify or confirm
Hypothesis 1 vs. Hypothesis 3 (§14), which is currently the single largest open branch in this
report's own findings.

## 17 · Explicit unknowns

- Whether the missing middle joint (§4) is a real gap to be built or a deliberate, evidence-supported
  boundary (§14, competing hypotheses 1 vs. 2/3) — genuinely unresolved by this pass.
- `RA v1.1`'s actual bounded-context content — still unverifiable without opening its unmerged
  branch, a boundary this pass continues to respect.
- Whether `DC(d)`'s schema has ever been instantiated anywhere outside what this pass searched.
- Whether Hypothesis 3's "coarser real states are already sufficient" holds under adversarial
  testing — not tested here.

## 18 · What must NOT yet be implemented

Per instruction, explicitly: no new aggregate, no new bounded context, no new kernel object, no
new mathematical structure, no new canonical registry, no new theory category. §16's search is a
`grep`, not a build.

---

## 19 · Phase 4 — Independent cross-domain validation against PKS (added same day, second pass)

**Framing correction, adopted from the human's redirect:** the actual research target is the
**KnowledgeOS Kernel** — a level distinct from both PKS (Product Knowledge System) and EKS
(Engineering Knowledge System, of which Cohesion is one capability). §§1–18 above used EKS/Cohesion
as the *first* empirical laboratory to derive kernel-candidate principles. This section runs the
recommended falsification step: **does the same theory survive an independent second domain (PKS)
that was never designed with EKS/Cohesion in mind?**

Five candidate principles from §6/§13, tested against PKS's own primary documents
(`what_is_pks_v0.md`, `20260728_1710_what_is_pks_v1.md`,
`PKS_Phase_III_Engineering_Knowledge_Model.md`), genuinely open to a "not found" result:

| Candidate principle (from EKS/Cohesion) | PKS result | Evidence |
|---|---|---|
| Totality of core semantic objects | **CONFIRMED** | M1 Concept Register: every one of 17 concepts carries the same fixed field set (origin, evidence, portability grade, confidence, routed questions); the completeness check grades every capability against the same 8 required elements, marking a genuine absence explicitly (`⛔ none`) rather than leaving it blank |
| Relational/graph structure | **CONFIRMED** | `what_is_pks_v0.md` gives typed, cardinality-annotated relationships (`Decision --recorded-in(1:1)--> ADR`); "M7 Strategic Relationship Model... graph acyclic"; a literal typed directed graph among the 6 capabilities |
| "Can't-determine" as a distinct, preserved, non-defaulted state | **CONFIRMED — richer than the EKS original** | Beyond the already-known `INCONCLUSIVE` verdict: **"G-6 Question \| An owned, routed unknown whose lifecycle outlives its carrier"** — unresolved-ness is a first-class object with its own identity and lifecycle, not just a status flag; separately, a 5-way non-mixing epistemic class (Observed/Measured/Derived/Synthesized/Recommendation) |
| Kernel-as-classifier-never-reasoner | **CONFIRMED** | *"Observed: no capability owns governance, identifiers, vocabulary, authority, architectural decisions, lifecycle or knowledge. Each owns execution only."* / *"AI surfaces governance questions; it does not resolve them."* — independent wording, not shared vocabulary with Cohesion or the governance engine |
| Advisory-only / human-gated commitment | **CONFIRMED** | *"No decision is final without human approval."* Concretely: even a capability graded fully "READY" still requires **two separate non-engineering acts** (Decision Authority plan approval, then ARB execution authorization) before it may activate |

**All five survive, with independent (not shared-vocabulary) wording — this is a genuine
falsification test passed, not a re-statement.** Per Hypothesis 3 (§14), this also strengthens the
reading that these five are real kernel-level candidates rather than EKS-specific accidents.

**One evidentiary caveat, disclosed precisely rather than smoothed over:** unlike Cohesion and the
governance engine (both real, independently-executing PHP/Python), **PKS's own Knowledge-layer
objects have no code-level enforcement found for any of these five principles** — PKS is explicitly
"knowledge specifications (YAML + Markdown)... not software" by its own definition. Every
confirmation above is a textual/governance-document match, not an independently-executing-system
match. This does not weaken the result (the pattern recurring, unprompted, in a genuinely
differently-built domain is real evidence) but it is evidentially a **weaker class of witness**
than Cohesion — it confirms the pattern is real in how the domain is *specified and governed*, not
yet that it is *enforced by running code* the way it is in EKS.

**Revised candidate architecture center (supersedes §13's single-domain framing):**

> A KnowledgeOS kernel is a classifier/gate — it partitions inputs into preserved, distinct states
> (including "not determinable" as its own first-class state) and never generates a conclusion or
> executes an action. Everything past classification requires a separate, externally-authorized
> act. **This principle now has independent, unprompted support from two structurally unrelated
> domains (EKS/Cohesion's real code; PKS's governance specifications) — the strongest
> cross-domain evidence found in this whole investigation for any single candidate.**

**What Phase 4 does not yet establish:** whether this holds under a *third*, adversarially-chosen
domain; whether PKS's specification-level confirmations would survive if PKS were ever
implemented as running code; and whether the still-unresolved "missing middle joint" (§4, the
determination/commitment ladder) has any PKS analogue at all — not tested this pass.

---

## 20 · The derived Kernel candidate set — concept × domain matrix (evidence-grounded, not hypothetical)

Per the human's corrected framing: **PKS and EKS are two independent application domains; the
Kernel is only what survives in both, never their union.** The matrix below replaces a
hypothetical version proposed in dialogue with the actual evidence gathered in §§1–19 — every
mark is cited, not assumed.

| Concept/transformation | EKS (Cohesion + governance engine) | PKS | Kernel candidate? |
|---|---|---|---|
| **Observation** | ✓ real (source read, `L3` extraction, tested) | ✓ real (`Observed` is one of 5 non-mixing epistemic classes) | **✓ CONFIRMED** |
| **Rule / classification** | ✓ real (`EdgeRules`, literally named, tested, 11 branches) | ✓ real (`Rule` is a named Knowledge-layer object) | **✓ CONFIRMED** |
| **Authorization / commitment gate** | ✓ real (`G-3` mandatory human `START`, tested) | ✓ real ("two separate acts... before activation") | **✓ CONFIRMED — strongest evidence of the set** |
| **Canonical representation** (vs. projection/format) | ✓ real (`L3` facts, language-neutral, cross-language tested) | ✓ real (the Knowledge→Representation→Presentation layer split — semantics kept distinct from its YAML/JSON encoding and its document projection) | **✓ CONFIRMED** — stronger than the dialogue's own "`?`" guess for PKS |
| **Provenance** | ✓ real (test citations; `EKS-07`'s own provenance findings) | ✓ real (M1's `origin` field on every concept) | **✓ CONFIRMED** |
| **Uncertainty / indeterminacy as a distinct, non-defaulted state** | ✓ real (`D-1`'s `IndeterminateBehaviourReference`, cross-language) | ✓ real (`INCONCLUSIVE`; `G-6 Question` as a first-class owned object) | **✓ CONFIRMED** (already established, §19) |
| **Governed state transition / lifecycle** | ~partial (`v0.2`'s admission ladder specified, unimplemented; governance engine's `REGISTER→HANDOFF→START` implemented, coarser) | ✓ real (Capability Lifecycle: `Candidate→Designed→Realized`, `Deferred`/`Rejected` terminal) | **✓ CONFIRMED**, shape only fully implemented in one domain (PKS/governance) at a time |
| **Evidence** (as its own typed object) | ~partial (`v0.2`'s `Evidence` object specified, `TESTED` once via `EXP-01`; **no analog in Cohesion's own running code**) | ✓ real (evidence fields throughout M1, Charter Grant, Exception Record) | **candidate, weaker EKS-code witness** |
| **Finding** (as its own typed object) | ~variant (no object literally named "Finding" in Cohesion; the structural analog is an excluded-edge verdict + `ExclusionReason`) | ✓ real (`Finding` is a named Knowledge-layer object) | **candidate, structural variant only in EKS** |
| **Decision** (as its own typed object) | ~partial (`v0.2`'s `DC(d)` 6-tuple specified, **never instantiated anywhere found**; governance engine implements only a coarser verdict) | ✓ real (`Decision` is a named object; "no decision is final without human approval") | **candidate — this is exactly Gate 2's "missing middle joint" (§4), now confirmed present in PKS but still unbuilt in EKS** |
| **Semantic equivalence** | ✓ real (the `L3` cross-language neutrality test — 11 mechanisms, a genuine tested equivalence relation) | untested this pass — no PKS document read here confirms or denies an equivalence test between differently-represented specs | **unresolved — genuinely open, not forced either way** |
| Domain-specific product object | — | ✓ (PKS-specific) | **not kernel** — correctly domain-local |
| Code semantic fact | ✓ (EKS-specific) | — | **not kernel** — correctly domain-local |

**Seven concepts survive independently in both domains with real (not merely specified) evidence:**
Observation · Rule/classification · Authorization/commitment · Canonical representation ·
Provenance · Uncertainty-as-distinct-state · Governed lifecycle. **This is the evidence-derived
Kernel candidate set** — deliberately smaller than the union of everything either domain contains,
per the human's own governing constraint ("the kernel is not simply the union of PKS + EKS").

Evidence/Finding/Decision remain real in PKS but are only specified-or-partial in EKS — this is not
a contradiction, it sharpens Gate 2's own finding (§4): the "missing middle joint" between
canonical representation and authorized decision is now confirmed to be a **real, implemented
structure in PKS** that EKS has only ever specified, never built. That is the single most
concrete, falsifiable target this whole investigation has produced for what EKS-side work would
actually close a confirmed cross-domain gap, if that work is ever authorized — **not proposed or
begun here.**

---

## 21 · Correction and prior-art disclosure (added same day, third pass — reading `docs/knowledgeos/architecture/`)

**A weakness in §19's "independent convergence" claim, found and disclosed, not buried.**
`PKS-Current-Architecture-Baseline-Stage-2.md` (2026-08-21) establishes that PKS's real,
implemented capability layer lives at `scripts/lib/EngineeringKnowledge/Capabilities/` —
**the same directory tree that holds Cohesion.** Its `IdentifierIntegrity` capability is
explicitly documented as *"the template for every future Engineering Knowledge capability."*
**This means Cohesion likely followed a shared engineering template rather than independently
arriving at the classifier/closed-verdict-vocabulary pattern from scratch.** Where §19 credited
Cohesion and PKS's capability layer as two unrelated domains converging by coincidence, that
specific piece of evidence is **weaker than claimed** — shared lineage, not independent discovery.
(PKS's own accounting also separately flags: Cohesion is not in its Capability Catalog, has no
dedicated test files by its count, and its "governance home and identity are UNKNOWN" — a gap this
session's own governed-contract work on `D-1` does not appear to have closed.)

**What survives this correction, undiminished:** the PKS *specification-level* confirmations in
§19 (Knowledge-layer objects: `Rule`, `Decision`, `Finding`, totality, provenance, the lifecycle)
are a different, textual/governance layer, not the capability-checker code layer — that evidence
is untouched by this caveat. And §20's "Authorization/commitment gate" principle now has a
**third, genuinely independent** witness, found this pass: `KnowledgeOS-Epistemic-Architecture-
Investigation.md` (2026-08-21, a separately-sourced "HPA-commissioned DeepSeek investigation,"
five weeks before this session's own work, using entirely different vocabulary — Observation/
Evidence/Claim/Confidence/Authority/Decision, no `Sat`/`Zero`/`K_t` notation at all) independently
reached: **"Confidence ≠ Authority is the strongest-supported external principle"** — the same
substance as this report's own top finding, arrived at earlier, separately, and by a different
method (testing two Bayesian/calibrated-confidence frameworks against evidence and rejecting the
continuous-confidence-signal approach they proposed). **This is prior art, and is recorded as
such, not re-claimed as this pass's own discovery.**

**A second, larger disclosure this document requires:** that Epistemic-Architecture-Investigation
document is a direct methodological precursor to this whole research gate — it independently
derived its own 8-item kernel-candidate set (`C-1`…`C-10`, all explicitly "none promoted"),
substantially overlapping with, but more granular than, §20's seven-item set (its own
*"state-as-fold"* and *"regenerable-non-authoritative-projection"* are not separately named
anywhere in this report). Its closing discipline — *"DISCOVER → COMPARE → VALIDATE → CLASSIFY →
ONLY THEN DESIGN"* — reads as a direct ancestor of the very next morning's `P4→P5→kernel-decision
→Constitution` sequence this session already verified. **And, tested directly: this document uses
none of `v0.2`'s later formal vocabulary (`Sat`, `Zero`, `K_t`, `DC(d)`, `Knower`, `EC`) — meaning
the ratified formal apparatus was invented independently afterward, not a formalization of this
investigation's own findings.** Two parallel kernel-theory lines — one empirical/evidence-driven,
one abstract/mathematical — ran in the same short window and were never explicitly connected to
each other by anything found in this investigation. This is the same "duplicate, unconnected work"
pattern the `EKS-*` backlog catalogues dozens of times over, now found operating on the kernel
question itself.

**One more corroborating, not-yet-adopted convention worth naming:** `AIP-Current-Architecture-
Reconstruction-Stage-3.md` identifies, by name, the real code this report has been calling "the
governance engine" throughout — `workflow-state.php`/`session-resolve.php` (`AST-015`/`016`) —
and independently states the same asymmetry this report's own §11 found: *"Assurance is strong
where executable, weak where prose"* (§4's "middle joint" finding, generalized beyond just the
epistemic ladder to nearly everything outside the workflow engine and the capability-checker
layer).

---

## Status (short form, as required)

- **Goal progress:** the transformation chain is now evidence-tested end to end, in both
  directions (ratified text and real code) — this is new since Gate 1.
- **What is established:** two real, independently-tested half-chains exist and have never been
  connected; the "kernel-as-classifier-never-reasoner, advisory-only" principle has genuine,
  repeated, cross-system empirical support; `K_t`/`Sat`/`Zero`'s *concrete* forms remain
  contradicted, but the *abstract requirement* behind `Zero` (non-Boolean, preserved distinctness)
  is independently validated by real code.
- **What remains uncertain:** whether the missing middle joint is a gap to close or a boundary to
  respect (three competing hypotheses, not adjudicated).
- **Highest-value next experiment:** a narrow, cheap code search for any real implementation of
  the epistemic-status ladder or `DC(d)` — directly falsifies the open branch.
- **Remaining major todos:** none authorized by this gate; §16's search, if pursued, stays
  read-only per this gate's own scope.

---

**Traceability:** `2026-09-28-KOS-architecture-reconstruction-report.md` (Gate 1, treated as
evidence here) · `RA v1.0` and `v0.1`/`v0.2` primary-source read, this pass · this session's own
`D-1` implementation and missingness-taxonomy resolution (first-hand) · `EKS-07` (governance-engine
citations) · `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md` and siblings.
