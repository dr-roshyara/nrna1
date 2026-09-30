# `KOS-CONTRACT-NEUTRALITY-001` — V-3 decision-gate reconciliation (research / governance-preparation pass)

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-28
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation`, continued as a **research
pass** (no new grant — nothing tracked is modified)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
**Follows:** `2026-09-28-...-V3-exclusion-evidence-sufficiency-pass.md`, per the human's explicit
direction to reconcile, not yet decide.

> ⛔ **Non-authoritative research only.** No `expected.json` change · no Domain/adapter/test
> change · no governance/workflow transition · no PO/ARB submission initiated. Verified
> before and after: `git status --short scripts/ tests/ .claude/runtime` shows no change from
> this session.

---

## 1 · Executive finding

The 2026-09-27 incorporation brief's rejection of Option 3 is **stale, not wrong when
written** — it correctly stated no evidence existed yet; the experiment written hours later,
same day, supplied exactly that evidence and neither document references the other. That
inconsistency is real and is reconciled in §2. Separately, and more consequentially, this
pass finds the canonical invariant registry (§4) already answers the §7.1 scope question
**by direct textual deduction, not analogy** — `INV-L3-5` is registered specifically over
`BehaviourReference`'s constructor, and `INV-L3-6` states L3 excludes exclusion-concepts
entirely, so the exclusion-evidence record is textually outside `INV-L3-5`'s stated scope.
Classified **A** below, with the reasoning shown so the human can test it. **This changes the
Model A/B/C cost picture** (§5): Model A's main cited cost evaporates if this classification
holds.

## 2 · Evidence reconciliation

| Earlier claim | Later evidence | Relationship | Current status |
|---|---|---|---|
| 09-27 **incorporation brief**, Option 3 row: *"no evidence this narrower step is actually needed rather than merely cautious"* — rejects splitting `D-4`/`D-5` from `D-1`. | 09-27 **experiment**, §5/§11 (same date, produced afterward): `EdgeRules::verdict()`'s determinability-first branch order means `D-4`/`D-5` need **zero** `EdgeRules` change (confirmed by execution, Fixture 3); `D-1` needs a new fact kind AND an unresolved exclusion-evidence question (confirmed by the same fixture run producing a real `TypeError`, and by three distinct representation models for `D-1` alone). | **Supplements, does not contradict.** The brief's claim ("no evidence yet") was accurate *at the time it was written* — the experiment that would supply the evidence had not yet run. The two documents are sequential, not conflicting, but neither cross-references the other, so the record currently reads as if the rejection still stands. | **Stale.** The brief's Option-3 rejection should be read as superseded by the experiment's findings, not as a standing objection. No document has said this explicitly until now. |
| 09-27 **experiment**, §14: *"`D-1`, `D-4`, `D-5` should not be presented as one bundled 'vocabulary incorporation' question."* | 2026-08-19 **decisions-registration**, R1.2: *"D-2 → D-3 → D-1 → D-4 → D-5"*, an explicit dependency order, with the binding rule *"D-2 and D-3 must not be split into contradictory decisions"* — stated ONLY for `D-2`/`D-3`, not extended to `D-1`/`D-4`/`D-5`. | **Consistent, not contradictory.** The original bundling was a *drafting/sequencing* convenience for presenting five decisions together, not a stated dependency between `D-1` and `D-4`/`D-5`. The only explicit non-splitting rule in the record binds `D-2`↔`D-3`. | The historical "five decisions together" framing has **no textual dependency requirement** binding `D-1` to `D-4`/`D-5`. Splitting them is not overriding a prior rule — no such rule exists for this pair. |
| 09-28 **sufficiency pass**, §7.1: treats the `INV-L3-5` scope question as open, citing the invariant's docblock and calling the exclusion-record reading *"an analogy... not a textual requirement."* | This pass, §4: the **canonical decision registration** (`2026-08-18-...-implementation-architecture.md` §4.4) gives `INV-L3-5`'s and `INV-L3-6`'s exact registered text, not the docblock paraphrase the sufficiency pass relied on. | **Qualifies, materially.** The sufficiency pass read the *code* docblock; this pass read the *governance registration* the docblock summarizes. The registration is more precise about scope (names `BehaviourReference`'s constructor specifically) and adds `INV-L3-6`, which the sufficiency pass did not cite at all. | The sufficiency pass's own classification of this question (effectively "B") is **superseded by this pass's classification of A** — see §4. This is a correction of my own immediately-prior work, disclosed rather than silently updated. |

## 3 · D-4/D-5 versus D-1 separability

Three distinct questions, kept apart as instructed:

- **Technical separability — YES, established.** `D-4`/`D-5`'s scope (`call_user_func`/
  `call_user_func_array`, literal `[$this,'name']` only) guarantees a real `T_STRING` method
  name at every in-scope call site; `EdgeRules::verdict()` excludes on `determinability`/
  `qualifierKind` *before* any exclusion-record concern arises, and produces a normal,
  already-legal `target: string`. Nothing about implementing `D-4`/`D-5` touches the
  exclusion-evidence question. (Source: `EdgeRules.php:26-35`, re-read this pass; 09-27
  experiment Fixture 3, executed.)
- **Contract separability — YES, on current evidence, with one caveat.** `expected.json`'s
  `_variant_decisions_pinned.intra_class_calls` text names *both* constructs
  ("dynamic method names... callable arrays...") in one sentence, but as a **semantic**
  statement (both are excluded, and why) — it does not require they be incorporated as
  representation vocabulary in one amendment. The caveat: no PO/ARB act has ever been asked
  "may these be split," so "separable" here means *nothing in the record forbids it*, not
  *a decision permits it*.
- **Governance preference — NOT ESTABLISHED, and correctly so; this is not a research
  question.** Whether PO/ARB *wants* one bundled amendment (simpler traceability, matches
  how `D-1`…`D-5` were originally decided together) or two smaller ones (unblocks `D-4`/`D-5`
  sooner, defers the harder `D-1` question) is a preference, not a fact this pass can derive.

**Conclusion:** the evidence *permits* independent incorporation of `D-4`/`D-5` from `D-1`.
It does not compel it, and nothing here recommends choosing that path over bundling — only
that the option is no longer merely "cautious," per §2's row 1.

## 4 · `INV-L3-5` scope determination

**Classification: A — already decided by existing contract evidence.**

**Exact supporting evidence, read this pass from the canonical registration** (`2026-08-18-
KOS-CONTRACT-NEUTRALITY-001-implementation-architecture.md`, §4.4, the invariant table —
this is the governance-registered text, not a paraphrase):

| Invariant | Registered text (verbatim) | Enforcement (verbatim) |
|---|---|---|
| `INV-L3-5` | *"Totality. All six `BehaviourReference` attributes are always present; no default, no 'unknown'"* | *"constructor totality"* |
| `INV-L3-6` | *"No verdicts. `L3` contains no edge, no exclusion, no metric"* | *"§17 `INV-2`"* |

Four already-decided facts, combined by direct deduction (no analogy, no judgment call
required beyond reading them together):

1. `INV-L3-5`'s own text names `BehaviourReference` specifically, and its enforcement
   mechanism is that type's **constructor** being total. The exclusion-evidence record
   (`CohesionGraph::$excluded`, shape `array{method:string,target:string,reason:
   ExclusionReason}`) is not constructed via `BehaviourReference`'s constructor — it is
   assembled by `GraphBuilder::build()` (`Domain/GraphBuilder.php`, re-read this pass). There
   is no constructor for `INV-L3-5`'s stated enforcement mechanism to act on here.
2. `INV-L3-6`, registered in the same table, states outright that `L3` **contains no
   exclusion concept at all** — exclusion is, by this already-decided invariant, exclusively
   an `L4` concern.
3. `CohesionGraph`'s own docblock self-classifies as *"L4 output / L5 input"* (read this
   pass) — not `L3`.
4. Decision 13.1 (2026-08-18, DECIDED) stratifies the contract `L3 → L5`, with `L3` as the
   language-neutral fact model specifically.

**Deduction:** `INV-L3-5` is registered as an `L3` invariant, scoped by its own text to
`BehaviourReference`'s constructor. The exclusion-evidence record is, by a *different*,
already-decided invariant (`INV-L3-6`) and by `CohesionGraph`'s own self-classification, not
part of `L3` at all. An invariant cannot bind, by its own stated scope and enforcement
mechanism, a structure that a separate decided rule places outside that scope. **This is
what "A" requires: the answer follows from existing decided text, not from this pass's
interpretation of intent.**

**Why this is not "B":** "B" would mean the answer requires reading intent into ambiguous or
silent text. Nothing here is silent — `INV-L3-5` names its own subject and mechanism;
`INV-L3-6` separately and explicitly excludes exclusion-concepts from `L3`. Combining two
already-decided, unambiguous sentences is deduction, not interpretation of a gap.

**Why this is disclosed as a correction, not asserted quietly:** the 2026-09-27 experiment
and the 2026-09-28 sufficiency pass (this session's own immediately-prior work) both read
only `BehaviourReference`'s code docblock (which paraphrases, but does not quote, the
registered text) and both classified this question as unresolved. Re-reading the governance
registration itself — the actual decided artifact, not the code comment summarizing it —
changes the classification. **This is exactly the failure mode this repository's own
methodology warns against (Canonical Discovery: read the authoritative record, not a
paraphrase of it) — and this pass is not exempt from it, having made the same error one
document ago.**

## 5 · Model A/B/C status

**No model is selected.** The classification in §4, if the human agrees with it, changes
what each model actually costs — restated here without choosing:

| Model | What it solves | New invariant/obligation | Evidence supporting it | Evidence against it | Still unresolved |
|---|---|---|---|---|---|
| **A — `target: null`** | Keeps the existing `array{...,target:string,...}` shape; the new fact kind's exclusion entry just has a null target. | None, **if §4's classification holds** — `INV-L3-5` does not reach this record, so nulling `target` violates no registered invariant. If the human does not accept §4's reasoning, the cost reverts to the "analogical violation" the 09-27 report described. | Simplest change; touches only `GraphBuilder`'s two emission sites for the *new* fact kind, not the other five reasons. Zero cross-adapter obligation. | Still creates a heterogeneous field (sometimes string, sometimes null) that every current and future consumer must handle — a real ergonomics cost even if no invariant is violated. `AnalyseCohesion::observe()`'s current unconditional read (`$e['target']`, line 78-79) would need a null-check, confirmed by reading that file this pass. | Whether "no invariant violated" is accepted as dispositive, or whether PO/ARB still prefers to avoid a nullable field on ergonomic grounds alone. |
| **B — omit `target` key** | Avoids ever asserting a null string. | Same as A re: `INV-L3-5`, per §4. Adds a *shape* obligation: consumers must check key-presence, not just nullness. | None cited beyond avoiding a literal `null`. | Demonstrated (09-27 report §6) to break `AnalyseCohesion::observe()`'s unconditional key read outright (undefined-key error), confirmed by this pass's re-read of the same line. Strictly worse than A on every dimension the 09-27 comparison used, and this pass finds nothing that changes that. | Essentially nothing — B is dominated by A and C alike; included here only per the instruction not to pre-select. |
| **C — `factId` reference** | Removes the target-string question entirely; generalizes to any future excluded fact kind. | **Confirmed, this pass and the 09-28 pass: promotes `factId` from optional/unconsumed (§3 of the 09-28 pass, re-confirmed by executing `UnconsumedFieldCharacterizationTest` — still green) to mandatory-and-unique, produced by BOTH adapters, for EVERY `BehaviourReference`, not just the new kind.** This is a real, new, cross-cutting `L3` contract obligation — not a bookkeeping change, exactly as the human's paste insists it must not be allowed to sneak in as. | Reuses an already-documented mechanism (`factId`'s docblock purpose); generalizes cleanly; §4's `INV-L3-5` reasoning, if accepted, means C is not *required* by any invariant — it would be adopted for its generality, not to satisfy a rule. | Touches `GraphBuilder`'s exclusion emission for **all six** existing `ExclusionReason` cases, not only the new one (§5 of the 09-28 pass, re-confirmed this pass by re-reading `GraphBuilder.php` — both emission sites are shared). No authoritative consumer exists to break today (confirmed by grep, 09-27 report), so the cost is internal consistency, not compatibility. | Whether the generality is worth a new cross-adapter mandatory-uniqueness obligation, given (per §4) it is not compelled by any existing invariant — this is now visibly a **cost/benefit governance choice**, not a compliance requirement, which the record did not previously make explicit. |

**The single most important correction this table makes to the record so far:** every prior
document (09-27 experiment, 09-27 brief by omission, 09-28 sufficiency pass) discussed Model
C as though it were resolving an invariant conflict. **§4's finding, if accepted, means it is
not** — Model A already satisfies every registered invariant. Model C's case rests entirely
on generality and provenance elegance, which are real engineering virtues but a different,
weaker kind of argument than "required for `INV-L3-5` compliance."

## 6 · Remaining governance question

The smallest question that actually requires a human/PO-ARB decision, phrased to ask for a
**scope ruling**, not a technical choice:

> **Given that `D-4`/`D-5` require no exclusion-evidence representation and `D-1` does
> (confirmed, §3), and given that no existing decision requires `D-1`, `D-4` and `D-5` be
> incorporated as one amendment (confirmed, §3): does PO/ARB want `D-4`/`D-5` incorporated
> into the contract now, independently of `D-1`, or held until `D-1`'s representation
> question is separately resolved?**

This question does not ask PO/ARB to choose Model A/B/C, does not ask them to rule on
`INV-L3-5`'s scope (§4 proposes that this pass already answers it from existing text — if the
human disagrees, *that* disagreement is the thing to escalate, not the original question),
and does not presuppose an answer.

## 7 · Proposed sequencing

```
Current evidence (this pass + 09-27/09-28)
      ↓
09-27 record reconciled (§2 — DONE, this document)
      ↓
INV-L3-5 scope classified A, with reasoning shown (§4 — DONE, this document;
      human may accept, or escalate disagreement to Governance)
      ↓
human decides: D-4/D-5 independent incorporation — yes / no / defer (§6)
      ↓
   [if yes]                                    [if no/defer]
D-4/D-5 contract-text amendment          D-1 representation (A/B/C) resolved first —
 (small, evidence-supported)              now a COST/BENEFIT choice (§5), not a
      ↓                                    compliance requirement
D-1 representation resolved on its              ↓
 own timeline, unblocked by D-4/D-5      one bundled contract-text amendment
      ↓                                          ↓
contract incorporation (Governance act, per this repo's convention)
      ↓
implementation (separately authorized)
      ↓
RED → GREEN → VERIFY → ACCEPT
```

## 8 · Explicit STOP condition

**What is now known:**
- The 09-27 brief's Option-3 rejection is stale, not wrong-when-written (§2).
- `D-4`/`D-5` and `D-1` are technically and contractually separable; no rule forbids
  splitting them (§3).
- `INV-L3-5` does not textually reach the exclusion-evidence record, by direct deduction
  from its own registered text plus `INV-L3-6` (§4) — a correction to this session's own
  immediately-prior report.
- Model A satisfies every registered invariant under that reading; Model C's justification is
  generality and provenance design, not compliance (§5).

**What remains genuinely undecided:**
- Whether the human/PO-ARB **accepts** the §4 classification (this pass proposes it; it is
  not self-ratifying).
- Whether `D-4`/`D-5` should incorporate independently of `D-1` (§6) — evidence permits it,
  nothing compels it.
- Which representation (A/B/C) to adopt for `D-1`, now correctly framed as a design
  trade-off rather than an invariant-compliance question.

**What decision is required:** the human's answer to §6, and — logically prior to it, since
it changes the cost table in §5 — whether §4's `INV-L3-5` reasoning is accepted or should
itself be escalated to Governance/PO-ARB as a scope ruling in its own right.

**What must NOT happen before that decision:** any `expected.json` change; any `GraphBuilder`,
`BehaviourReference`, `EdgeRules`, or adapter change; any test modification; any RED/GREEN
work; any PO/ARB submission drafted as though a model were already chosen.

**STOP after this report.** Next actor: the human, on whether to (a) accept §4's
classification and proceed to decide §6, (b) escalate §4 to Governance/PO-ARB as its own
question before anything else moves, or (c) request further research this pass has not
identified a need for.

---

**Traceability:** `2026-09-28-...-V3-exclusion-evidence-sufficiency-pass.md` (corrected in
§4 of this document, disclosed) · `2026-09-27-...-V3-representation-boundary-experiment.md` ·
`2026-09-27-...-V3-incorporation-decision-brief.md` · `2026-09-04-...-V3-D1-D4-D5-ADOPTED.md`
· `2026-08-19-...-v3-decisions-registration.md` (+Amendment 1, R1.2 dependency order) ·
`2026-08-19-...-V3-architecture-determination.md` · `2026-08-18-...-implementation-
architecture.md` §4.4 (canonical `INV-L3-1`…`INV-L3-7` registration, read in full this pass —
**the primary new source this pass adds**) · `2026-08-18-...-decisions-13-registration.md`
(canonical Decision 13.1/13.7 text) · source re-read this pass: `GraphBuilder.php`,
`EdgeRules.php`, `CohesionGraph.php`, `BehaviourReference.php`, `AnalyseCohesion.php` ·
executed this pass: `vendor/bin/phpunit --filter UnconsumedFieldCharacterizationTest` (OK,
2/2, unchanged from the 09-28 pass) · `INV-ATTR-2` · `S5-architecture-pass1-evidence-
reconciliation` lane record.
