# Aggregate Boundaries and Invariant Ownership — Adjudication

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — companion to `..._0841_target_architecture_v3.md`, `..._0905_architecture_conformance.md`, `..._0914_rule_model_and_conflict_analysis.md` |
| **Input adjudicated** | The Aggregate Boundaries proposal (conversation input, 2026-08-16), incl. its external citation (`mkasperczyk.com`) — recorded as **input, evidence-graded by its author, not verified here**, per the convention the SLR file establishes |
| **Adjudicated against** | `docs/knowledge_tranfer/` (14 files) + the three 2026-08-16 documents |
| **Date** | 2026-08-16 |

> **Verdict.** The strongest of the three inputs, and the only one whose closing recommendation is adopted **as the next artifact**. Its central question is the corpus's own principle 7 descending one level. Its collision is not with any invariant — it is with **phase**: this is tactical modelling, and the corpus has not closed the strategic questions it depends on.

---

## 0. The line that earns the adoption

> **"The design is not suffering from too few classes. It is suffering from insufficient answers to: which object owns this invariant, which transaction changes it, and which facts may be eventually consistent?"**
>
> **"The next modeling step should therefore be an invariant-to-aggregate matrix, not another component diagram."**

This is the corpus's own diagnosis, reached independently and from a different direction. Across `targer_architecure`, `target_architecture` and `eks_2.0` the recurring failure was **another diagram**: contexts moved 4 → 6 → 7 → 4 and containers 12 → 10, with no new evidence recorded for any move. v3 §0 named that. The proposal names it again and supplies the correct replacement move.

**This is the fourth independent arrival at "stop drawing boxes."** It is adopted as the next modelling step — subject to the ordering in §9.

---

## 1. The methodological claim is principle 7, one level down

> *"An aggregate should be drawn around what must be consistent in one transaction — not around related nouns or database tables."*

The corpus already holds this at the architectural level. v3 principle 7, derived from `how_to_work_with_sessions` §11:

> **`P-7` One authoritative owner per invariant.** *"If two components calculate the same invariant independently, divergence becomes inevitable over time."*

The proposal asks the identical question of aggregates that `AST-016` answered of components when it delegated the workflow fold to `AST-015` rather than reproducing it. **Same rule, tactical scope.** No new doctrine is required to adopt it — which is the cheapest possible adoption and the strongest kind.

---

## 2. The collision is phase, not principle

The folder is explicit, in two places:

> `C4 Level 3 — Component View_Knowledge.md`: *"**AD-1 forbids tactical decomposition.** AD-1's boundary explicitly forbids tactical decomposition."* · *"Conventional L3 is NOT supplied because it would invent architecture."*

> `C4 Level 4 — Code.md`: *"**Tactical DDD not yet defined.** Aggregates, entities, repositories, services are not yet designed."* · *"When Phase II.D (Implementation) begins, the following will be defined: **Aggregates · Entities · Value Objects · Repositories · Domain Services**…"*

The proposal delivers precisely that list. `what_is_pks_v1` §6.2 and `pks_progress` §"What Remains" both record **Phase II.D — Implementation — ⏳ Pending**.

**The honest reading matters here.** "Forbids" in `AD-1` means *not derivable from the strategic model as it stands* — not *never*. `PKS_Bootstrapping_and_Location` §8 says the same in gentler words: these *"are architectural design decisions… These decisions will be made after Strategic Modeling stabilizes."*

So this is not a rejected proposal. It is a **correctly-shaped proposal arriving before the questions it depends on are closed** — and it depends on three of them: `OQ-1` (§3), `OQ-4` (§6), `OQ-8` (§9).

---

## 3. Does it presuppose `OQ-1`? Partly — and the part that matters survives

At first reading the proposal is database-shaped: *transaction*, *eventually consistent*, `content_hash`, `snapshot_id`, `ProjectionBuild`. `OQ-1` asks whether the PKS is software at all; if the answer is "specifications," none of that has a subject.

**But the aggregate question is not a database question.** Restate the consistency boundary abstractly —

> An aggregate is what must be consistent within **one atomic change**.

— and it holds in both worlds. If the PKS remains YAML + Markdown under git, the atomic change unit is **the commit**, and it is genuinely atomic.

Stronger: **git already realizes several of the proposal's aggregates natively.**

| Proposal field | Git equivalent, if the PKS stays specifications |
|---|---|
| `EvidenceRecord.content_hash` | blob SHA |
| `EvidenceRecord.source_version` | commit SHA |
| `AnalysisRun.input_snapshot_id` | commit SHA |
| `ProjectionBuild.source_snapshot` | commit SHA |
| `rule_a_version` / `rule_b_version` | commit SHA of each rule file |
| append-only audit stream (§11) | the commit log itself |

This is a non-obvious and useful result: **the reproducibility machinery the proposal asks for (§6) is free in the representation the corpus currently certifies.** It is an argument that `OQ-1` may matter less to this proposal than it first appears — and it removes the strongest practical reason to answer `OQ-1` with "database."

**What genuinely does depend on `OQ-1`:** repositories, adapters, ORM concerns, and "eventually consistent" as a *runtime* property rather than a *rebuild* property.

---

## 4. The five "most important" corrections, adjudicated

| # | Correction | Verdict |
|---|---|---|
| 1 | **Rule vs. rule analysis** — a rule is authoritative state; conflict reports are derived analysis | **Exists as principle, new as applied.** `DR-1` + `L4-8` + v3 §12 already rule that projections are derived and nothing may depend on them. Naming `ConflictReport` a **projection** is new and correct — and it means a conflict report may never be cited as authority for anything |
| 2 | **Evidence vs. assessment** — different lifecycles | **Mostly exists.** `G-10 Observation` ≠ `G-15 Verdict`; `AD-1` separates *Observation Collection* from *Verdict Issuance* as distinct `AC-1` responsibilities; v3 §10 already gave Evidence its own chain. **New and correct:** the relation is **many-to-many** and must not be an embedded object graph |
| 3 | **Conflict detection vs. resolution** — formal analysis cannot grant authority | **Exists, reinforced.** v3 §9: *"the mechanism records authority; it never grants it."* `0914` §8, §11. Third independent statement of the same invariant |
| 4 | **Rule vs. exception** — an exception is a governed deviation with its own lifecycle | ⚠️ **GENUINELY NEW, and the most important thing in the proposal.** `G-16 Exception Record` exists as a *concept* — *"a recorded, approved deviation"* — with **no lifecycle and no invariants anywhere in the corpus**. §5 below |
| 5 | **Transactional state vs. projections** | **Half exists.** v3 §12 rules projections rebuildable. **New:** projections need *build state* — the corpus has no way to say a projection is stale or that a failed build left an outdated one live |

---

## 5. `ExceptionGrant` — the highest-value gap

The proposal's §4 invariants are adopted in full, because each states something the corpus needs and does not have:

- the exception must identify the rule it overrides;
- **its scope cannot be broader than the base rule unless explicitly authorized**;
- **its validity cannot exceed the approver's authority**;
- an expired exception cannot satisfy a current conflict;
- **revocation must be distinguishable from expiration**;
- an exception cannot silently mutate the base rule.

Two of these are load-bearing beyond exceptions themselves.

**"Validity cannot exceed the approver's authority"** makes authority *temporal*. The corpus treats a grant as present-tense — *"which recorded authority permits you to proceed?"* (`how_to_work_with_sessions` §18). It has no way to ask the proposal's sharper question: **"was the authority valid at the time of approval?"** That question is what makes an audit trail reconstructable rather than merely stored, and it is new.

**"Revocation must be distinguishable from expiration"** is `INV-A3` — *absence is never permission* — in exception form. An exception that lapsed and an exception that was withdrawn look identical in any model that carries only a validity window, and they mean opposite things about the base rule.

Without this aggregate, §8's warning lands exactly: **conflict resolution degrades into an informal priority mechanism** — which `0914` §8 already rejected on separate grounds.

---

## 6. Where the proposal should not be followed

**A fifth context partition.** Its aggregate map proposes six: `Governance · Knowledge · Assessment · Analysis · Architecture · Delivery`. The corpus now reads **4 → 6 → 7 → 4 → 6**, five different partitions across five documents, none carrying the merge/split language analysis that `pks_progress` §"Discovery method" records for the certified four. v3 §6.2 declined re-partitioning for exactly this reason and that ruling stands. Recorded under `OQ-4`.

**`Architecture Context` (System · Component · Capability · Dependency) is a scope expansion.** These model the *product being described*, not knowledge about it. `AD-1` places the described system outside the boundary; `XD-1` is the precedent for how an external domain is treated — referenced, never owned, dependency inward only. Admitting `System`/`Component` as owned aggregates would make the knowledge system a system-of-record for the architecture it documents. Recorded as `OQ-16`.

**Fifteen candidate aggregates for a system with zero implemented.** The proposal opens by warning against aggregates drawn around nouns, then lists roughly fifteen, most named after nouns. It half-catches this — *"not every item necessarily needs to be an aggregate immediately"* — but the tension is real, and the SLR's recorded *over-formalization risk* applies. The corpus's own counter-lesson: *"Confidence rose fastest when claims were withdrawn. Subtraction is sometimes more valuable than addition."*

---

## 7. It corrects an ambiguity in `0914`

The proposal separates two vocabularies that `0914` §6 partly ran together, and it is right to:

| | Produced by | Vocabulary |
|---|---|---|
| **Relationship** — what the analyser *found* | `RuleAnalysisService` (domain) | `CONSISTENT · SPECIALIZES · REFINES · DUPLICATES · OVERLAPS · CONFLICTS · SUPERSEDES · EXCEPTION_TO · UNKNOWN` |
| **Disposition** — what the authority *decided* | human authority (`G-4 Ruling`) | `CONFIRMED_CONFLICT · NOT_A_CONFLICT · RULE_A_SUPERSEDES_RULE_B · RULE_B_SUPERSEDES_RULE_A · EXCEPTION_GRANTED · RULE_REFINED · INSUFFICIENT_INFORMATION · ACCEPTED_RISK` |

These are not two spellings of one list. `0914` §6 lets `SUPERSEDES` and `EXCEPTION_TO` appear as analyser outputs — but neither is detectable; **both are decisions**. An analyser can observe that R2's domain lies inside R1's with an opposing effect; only an authority can rule that this *is* an exception rather than a conflict.

**Correction adopted:** the analyser emits `OVERLAPS` or `CONFLICTS` and names the region. `SUPERSEDES` and `EXCEPTION_TO` move to the disposition vocabulary, where they belong. `ACCEPTED_RISK` has no analyser counterpart at all and is a pure governance outcome — a good sign the split is real.

---

## 8. `AnalysisRun` — reproducibility, and why it is the best technical idea here

> *"R1 conflicts with R2"* is not a result.
> *"R1@vX and R2@vY conflicted under context snapshot Z, analyzer version A, ontology version B, interval T"* is.

Adopted. Two reasons internal to the corpus:

1. **It is what makes an analysis evidence rather than opinion.** `how_to_work_with_sessions` §15 requires verification to show **Claim → Attack → Observed result → Evidence → Conclusion**, and distrusts *"all tests passed."* `AnalysisRun` is that structure for machine analysis: without the snapshot, a conflict report is the machine equivalent of "all tests passed."
2. **It bounds `confidence` correctly.** `0914` §11 ruled that a proved unsatisfiability carries no confidence value. `AnalysisRun` explains *why*: what varies between runs is the **inputs**, not the proof. Version the inputs and the result is reproducible; score the result and the reasoning is hidden.

---

## 9. Audit as an append-only stream is `INV-A1` at the tactical level

The proposal's §11:

> *"An audit event records that something happened; it does not necessarily own the state transition that caused it."*

The corpus states this as its most emphatic authority invariant — `how_to_work_with_sessions` §7, carried into v3 §9 as **`INV-A1`**:

> **"A report of an act is not the act."**

**These are the same rule.** An audit event is a *report*; the transition is the *act*. Modelling audit as a mutable aggregate that owns history would make the record the act — the exact inversion `INV-A1` exists to prevent, and the one v3 §0 already identified in a different guise.

Adopted. And it settles the shape: **domain state · domain events · audit events · provenance records · projection build records are five separate things**, and the corpus should stop treating "the record" as one undifferentiated concept.

---

## 10. Ordering — the matrix comes second, not first

The proposal's closing recommendation is adopted, with one correction: an **invariant-to-aggregate matrix cannot be written before `OQ-8`.**

You cannot state `Rule`'s invariants — *"no active rule without valid applicability"*, *"no invalid temporal interval"* — until `applicability`, `normativeEffect`, `scope` and `temporalValidity` exist. Those are `OQ-8`, and `0914` §3 defines them.

```
OQ-8               what a Rule IS               ← next actionable decision
    ↓
invariant-to-aggregate matrix                   ← this proposal's recommendation
    which object owns each invariant
    which atomic change may alter it
    which facts may lag
    ↓
aggregate boundaries                            ← the proposal's §§1–11
    ↓
OQ-1 / OQ-4                                     ← repositories, adapters, contexts
```

**Three things can be written now, before `OQ-1`, because they are pure invariant statements:** `Rule`'s invariant list (proposal §1), `ExceptionGrant`'s (§4), and `AnalysisRun`'s reproducibility fields (§6). None names a technology.

**The proposal's own strongest constraint is adopted verbatim** and belongs in the matrix's header:

> **`Rule` should not decide whether another rule conflicts with it. That is a cross-aggregate analysis.**

That single line prevents the most likely tactical error — a `Rule` aggregate that grows a `conflictsWith()` method and quietly becomes the analysis engine, violating `P-7` and `AP-7` at once.

---

## 11. What changes in the prior documents

| Document | Change |
|---|---|
| v3 §4 Principles | `P-7` gains an explicit tactical reading (§1) |
| v3 §5 Vocabulary | **`G-16 Exception Record` gains a lifecycle and six invariants** (§5) — the largest single addition |
| v3 §9 Authority | Authority becomes **temporal**: *was it valid at the time of approval?* (§5) |
| v3 §12 Persistence | Projections gain **build state** — staleness and failed builds are now representable (§4.5) |
| `0914` §6 | **Corrected:** `SUPERSEDES` and `EXCEPTION_TO` move from the analyser vocabulary to the disposition vocabulary (§7) |
| `0914` §11 | `ConflictReport` is explicitly a **projection**, not authoritative state (§4.1) |
| v3 §14 | `OQ-8` unchanged as next; the matrix is the step **after** it (§10) |

**Still no new bounded context, no new container, no new constitution.**

---

## 12. Open questions added

| # | Question | Settled by |
|---|---|---|
| **OQ-16** | Does the PKS own `System` / `Component` / `Capability` / `Dependency` aggregates, or are they external like `XD-1`? | Human authority — it decides whether the knowledge system becomes a system-of-record for the architecture it documents (§6) |
| **OQ-17** | Is the consistency boundary the **git commit** (specifications) or a **database transaction** (software)? | `OQ-1` — but §3 shows the aggregate question survives either answer |
| **OQ-18** | Does `AuthorityGrant` carry a delegation chain and as-of-time validity, or does the existing grant concept suffice? | §5 — the corpus has no as-of-time authority query today |
| **OQ-19** | How many aggregates does V1 actually need? Fifteen is a hypothesis, not a finding | Implementation evidence — the SLR's over-formalization caution (§6) |

---

## 13. Bottom line

Three inputs have now arrived from three directions — enforcement, analysis, tactical modelling — and all three terminate at the same place: **`OQ-8`, what a Rule is.** The conformance gate cannot check a rule it cannot parse; the conflict engine cannot intersect scopes that do not exist; the aggregate matrix cannot state invariants over absent fields.

**Four independent derivations of one gap. Nothing else in this corpus has that weight behind it.**

The proposal's own framing is the right one to close on, because it is what the corpus has been missing for three generations of diagrams:

> **Which object owns this invariant, which change may alter it, and which facts may lag?**

That is a modelling question, and it is answerable now for `Rule`, `ExceptionGrant` and `AnalysisRun` — none of which requires knowing whether the answer is stored in PostgreSQL or in git.

---

*Adjudicated against `docs/knowledge_tranfer/` (14 files) and the three 2026-08-16 documents. The proposal and its external citation are recorded as **input**; this document is the assessed artifact they produce. Prior documents are unmodified; §11 lists the corrections they require.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
