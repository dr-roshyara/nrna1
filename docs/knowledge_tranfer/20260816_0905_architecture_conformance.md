# Architecture Conformance — Adjudication and Staged Model

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — companion to `20260816_0841_target_architecture_v3.md` |
| **Input adjudicated** | The Architecture Conformance Mechanism proposal (conversation input, 2026-08-16, §§1–15) — recorded here as folder input, per the artifact-ingestion convention the SLR file establishes: *"this file is input; any governed use produces a new assessed artifact"* |
| **Adjudicated against** | `docs/knowledge_tranfer/` (14 files) + Target Architecture v3.0 |
| **Does not modify** | v3.0. That document's derivation claim — *exclusively from the folder's 14 files* — stays intact, so this adjudication is filed beside it rather than folded into it |
| **Date** | 2026-08-16 |

> **Verdict in one line.** The proposal's diagnosis is right, its ordering is wrong, and two of its twelve invariants are genuinely new — they are already open as `OQ-8`. The rest either exist in the corpus or enforce decisions the corpus has not made.

---

## 0. The ordering error

The proposal's own §12 states:

> **`EKS-ARCH-007` — Authority and execution are separate concepts.**

And its §7:

> **"Creation and authority are different operations."**

These are correct. v3 §9 and §10 already carry both — as `INV-A1`…`INV-A4` and as the lifecycle invariant *"no type's lifecycle may contain a direct edge from creation to authority."*

Applied to the proposal itself, they produce its central defect:

> **A conformance gate enforces an architecture. Enforcement is execution. An architecture that has not been decided cannot be executed — and encoding it into a blocking gate does not enforce a decision, it *manufactures* one.**

Once `EKS-ARCH-004` fails a build, the four bounded contexts in the proposal's §10 manifest are settled in practice, whatever governance later concludes. `OQ-4` in v3 would be closed by a linter rather than by a human act. That is `INV-A1` — *a report of an act is not the act* — inverted: a *mechanism* becomes the act.

**The rule this produces, and it governs everything below:**

> **A gate may only enforce an architecture that a recorded human act has decided. Gates follow ratification; they never precede it.**

The proposal is not wrong to want gates. It is wrong to want them *now*, for decisions that `OQ-1`, `OQ-4` and `OQ-7` leave open.

---

## 1. The diagnosis is correct and is adopted

The opening concern stands without qualification:

> *"If we define a beautiful architecture but developers can casually add `Rule.php`, `KnowledgeItem.php`, `rules.yaml`, `some-validator.sh` without following the architecture, the architecture will decay."*

And §13's principle is adopted as an architecture principle, because the folder states the same thing in its own words:

| Proposal §13 | `how_to_work_with_sessions` §22 |
|---|---|
| *"Don't merely tell the agent how to behave; make the environment make incorrect behavior difficult."* | *"The goal is to make each session **capable of refusing work outside its authority**."* |

Two independent arrivals at one principle. **Adopted as v3 principle 8 (proposed):**

> **`P-8` Environment over instruction.** *Where an invariant can be made mechanically difficult to violate, instruction is the weaker control. Mechanism is preferred — but only for invariants that are already decided.*

The second clause is not a hedge. It is `P-8`'s boundary, and §0 is why it exists.

---

## 2. The corpus already contains a working conformance gate — exactly one

`how_to_work_with_sessions` §8 and §22 record it in operation:

```
Session 4 was given a real work item but no assignment.
It used AST-016.
It returned:   UNASSIGNED
               operable: false
               STOP
It did not say: "I know what you probably want me to do."
```

And the document rules explicitly:

> *"is **success**, not failure. The capability prevented unauthorized architecture work."*

This matters more than any diagram in the folder. It is the corpus's **only** instance of an executing gate that refused work — and it is an **authority** gate, not an **architecture** gate. It checks *"is this actor permitted to act?"*, never *"is this concept correctly classified?"*

**What the folder's evidence therefore supports:** authority gating is demonstrated. Architecture gating is undemonstrated. The proposal treats them as one programme; the evidence separates them cleanly.

---

## 3. The twelve invariants, adjudicated

Each row is judged against the folder and v3 — never against outside authority.

| # | Proposed invariant | Status | Where it already lives, or what blocks it |
|---|---|---|---|
| **001** | Knowledge is the domain; documents are projections | **exists** | v3 principle 1 · `AD-1` `DR-1` (nothing may depend on `AC-2`) · `L4-8` (projections have no independent semantic identity) |
| **002** | Every authoritative knowledge item has an owner | **exists** | v3 §9 authority model · `G-17 Charter Grant` |
| **003** | Every authoritative assertion has provenance | **exists** | v3 §5.3 — the eight provenance attributes **+ `authored_by`** |
| **004** | Every rule has scope and applicability | ⚠️ **NEW** | **`OQ-8`, open.** `G-2 Rule` — *"a binding behavioral norm with a single canonical home"* — carries neither |
| **005** | Rules have explicit normative effects | ⚠️ **NEW** | **`OQ-8`, open.** No MUST / MUST_NOT / MAY field exists anywhere in the 17 concepts |
| **006** | Conflicting rules cannot silently become simultaneously authoritative | **partial** | `G-2`'s *single canonical home* covers same-topic collision. Scope-**intersection** conflict is new and depends entirely on 004+005 |
| **007** | Authority and execution are separate | **exists** | v3 §9 `INV-A1`…`INV-A4`; *"the mechanism records authority, it never grants it"* |
| **008** | AI cannot autonomously grant authority | **exists** | v3 principle 3 · `what_is_pks_v1` §7.3 · the whole of v3 §9 |
| **009** | Domain logic cannot depend on infrastructure | 🚫 **not adjudicable** | Presupposes **`OQ-1`** — see §4 |
| **010** | Projections cannot become sources of truth | **duplicate** | Same as 001. `DR-1` states it once; stating it twice is the duplication the corpus's *single canonical home* rule exists to prevent |
| **011** | Graph/search/vector are derived projections | **exists** | v3 §12, adopted from `eks_2.0` §8's own strongest sentence |
| **012** | Every bounded context has explicit ownership | **exists** | `pks_progress` §3.2 context model |

**Tally: eight exist · one duplicates another · one is blocked on `OQ-1` · two are genuinely new — and those two are precisely v3's `OQ-8`.**

That convergence is the most useful result in this adjudication. `OQ-8` was raised in v3 from the *knowledge-model* direction (`eks_2.0` §9's Scope model). The proposal arrives at the identical gap from the *enforcement* direction, and independently supplies the missing shape — §4's Rule Contract, with `subject.scope`, `applicability`, and `effect.type`. Two unrelated routes to one gap is the strongest evidence in the corpus for closing it.

---

## 4. §6 (DDD code gates) is blocked on `OQ-1`, entirely

The proposal's §6 reasons over:

```
app/Models/Rule.php
class Rule extends Model { public string $text; }
Domain → Laravel ❌   Domain → Eloquent ❌   Domain → HTTP ❌
```

Every line presupposes that the system is PHP software with a Laravel layering to police.

v3 `OQ-1` records that the corpus asserts **both** answers and has never adjudicated:

- `what_is_pks_v1` §5.1 and `pks_progress` §5.1: *"The PKS Is Not Software… YAML + Markdown… does not require PHP, Python, or any other programming language."*
- `C4 Level 4 — Code.md`: *"PHP 8.3+ / Laravel 12 · PostgreSQL (primary), Neo4j (optional graph) · Vue 3 / Inertia.js."*

**§6 is not rejected. It is unadjudicable until `OQ-1` is decided by a human.** If the answer is "software", §6 becomes buildable and largely correct. If the answer is "specifications", §6 has no subject matter — there is no `app/` to police.

This is the clearest illustration of §0's ordering rule in the corpus: the proposal's most concrete, most enforceable section depends on the corpus's least resolved question.

---

## 5. §10's manifest presupposes two open questions

The proposed registry hard-codes:

```yaml
architecture:
  bounded_contexts: [knowledge-governance, evidence-assessment, decision-rule, knowledge-model]
  layers:           [domain, application, infrastructure, interface]
```

- The four contexts are **`OQ-4`** — v3 §6.2 declined to re-partition the certified four (`CBC-1`, `CBC-2`, `CBC-3` seam, `CBC-4` external) because the corpus records the count moving 4 → 6 → 7 → 4 in three documents written the same day, with no new evidence for any move.
- The four layers are **`OQ-1`** again.

**A machine-readable manifest is a good idea whose content is not yet decidable.** Writing it now would make a linter the author of the context map.

---

## 6. §12's Constitution is a proposal to fill `AR-1`

This is the subtlest collision and the most important one.

`AD-1` (via the folder's C4 Level 2 and Level 3 documents) establishes:

- **`AP-7`** — *"`AC-1` cannot author its own criteria. Criteria are used here, owned elsewhere."*
- **`AR-1`** — the normative region supplying those criteria — is an **undefined architectural region**, a *candidate seam*, over which *"no component may be defined,"* **pending Authority disposition (`AFV-F4`)**.

An Architecture Constitution is a criteria corpus. Fitness functions evaluate against it. A conformance mechanism that both **authors** the criteria and **evaluates** them merges `AR-1` into `AC-1` — the exact merge `AP-7` forbids.

And v3 §7 records the standing warning, in `AD-1`'s own words: filling `AR-2` *"would be the elegant-partition error at one remove."* The same applies to `AR-1`.

**Ruling: the Constitution is `OQ-7`, not a deliverable.** Twelve invariants can be *registered as pointers* to where each already lives (§3's table is that register). Authoring them as a new normative text creates a second home for eight rules that already have one.

---

## 7. What the intake gate would mechanize already exists — as human practice

The proposal's §1, §3 and §11 propose an intake classifier. The corpus already contains its content, executed by people:

| Proposal | Already in the folder |
|---|---|
| §1 intake — *what is it? which context? which invariant? which lifecycle? which authority? which evidence?* | `how_to_work_with_sessions` §12, *How the Mentor Reviews Architecture* — nine ordered questions: **Problem · Context · Invariants · Alternatives · Recommendation · Boundary · Human decision · Consequences · Implementation independence** |
| §11 change classification (10 checkboxes) | `how_to_work_with_sessions` §16, *Findings Must Be Classified Before Being Fixed* — six classes: implementation defect · architecture defect · specification gap · governance/process gap · acceptable limitation · separate future work |
| §14 developer workflow | `how_to_work_with_sessions` §20, *Mentor Review Lifecycle* — Human → Governance → START → Architecture/Implementation → Verification → Governance → Human |
| §2 *"if the answer is 'it belongs everywhere' 🚨 architecture smell"* | `how_to_work_with_sessions` §18, the five questions — **Who decided? · Who was authorized? · What scope? · What proves they did only that? · Who independently challenged it?** |

**The intake gate is therefore not a new capability. It is the mechanization of an existing practice** — which is a materially cheaper and better-evidenced proposal than the one made, and it changes what must be built.

---

## 8. The SLR warned about this, and the folder commissioned that warning

`20260728_SLR_Strategic_DDD_Methodology_Benchmark.md`, *Contradictions and Risks*:

> **"Certification-bureaucracy risk:** the layer must stay tied to measurable evidence or it adds cost without boundary quality."
> **"Over-formalization risk:** governance-heavy process may lose DDD's workshop-driven exploratory character."
> **"Boundaries are provisional:** the literature treats them as revisable modeling choices, not validated facts."

And its Final Assessment: *"Needs empirical validation: whether these controls improve boundary quality, speed, stability, alignment."*

The proposal adds seven mechanical gates, a conflict engine, an intake router, a manifest and a constitution — with no measurement of whether any improves boundary quality. That is the named risk, taken.

The corpus also supplies the countervailing lesson, from its own retrospective (`pks_progress`):

> *"Confidence rose fastest when claims were withdrawn. **Subtraction is sometimes more valuable than addition.**"*

---

## 9. The staged model

Derived from §§0–8. Each stage names what unblocks the next.

### Stage 0 — Authority gating · **EXISTS, demonstrated**

`AST-015` (interpretation authority) · `AST-016` (resolver, delegates and never interprets independently) · `INV-A1`…`INV-A4` · the recorded `UNASSIGNED / operable: false` refusal.

Nothing to build. **What is missing is not a gate — it is the observation that this one already works and nothing else has earned the same status.**

### Stage 1 — The Rule Contract's two missing fields · **SUPPORTED NOW**

Close **`OQ-8`**: add `applicability` and `normative effect` to `G-2 Rule`.

The proposal's §4 supplies the shape, and it is adopted as the candidate form:

```yaml
rule:
  id:            EKS-RULE-001
  subject:       { type: API, scope: production }     # ← new (OQ-8)
  applicability: { environment: production }          # ← new (OQ-8)
  effect:        { type: MUST, assertion: {...} }     # ← new (OQ-8)
  authority:     { owner: SecurityArchitecture }      # exists — v3 §9
  lifecycle:     { state: approved }                  # exists — v3 §10
  validity:      { valid_from: 2026-01-01 }           # exists — 3-class lifecycle
  evidence:      [ { source: SEC-DEC-042 } ]          # exists — v3 §5.3
```

Four of the eight blocks already exist. **The delta is three fields.**

> **Superseded in scope, 2026-08-16:** `20260816_0914_rule_model_and_conflict_analysis.md` §3 shows the delta is **four elements plus a validity interval** — `subject` · `applicability` (a *predicate*, not a label) · `normativeEffect` (a value object) · `scope` · `temporalValidity`. That document also replaces the three-value outcome vocabulary below with a nine-value relationship taxonomy. Stage 1 is larger than recorded here; its ordering and its blockers are unchanged.

This is the only stage supported by present evidence, because it is the only one where two independent derivations found the same gap (v3 `OQ-8` from the knowledge-model side; this proposal from the enforcement side).

It also unlocks, in order: `EKS-ARCH-004`, `EKS-ARCH-005`, then `EKS-ARCH-006` and the §8 conflict analysis — none of which is computable without scope and effect. **Conflict detection is not a separate project; it is what these three fields make possible.**

And §9's refinement is adopted with it, because it is right: overlap is not automatically contradiction. A general rule plus a scoped exception is a legitimate shape, so the outcome vocabulary is

```
NO CONFLICT   ·   EXCEPTION RELATIONSHIP REQUIRED   ·   REQUIRES REVIEW
```

never a bare `❌ CONFLICT`. The corpus already carries the concept this needs: **`G-16 Exception Record`** — *"a recorded, approved deviation."*

### Stage 2 — Code-level DDD gates · **BLOCKED on `OQ-1`**

The proposal's §6. Buildable and largely correct *if* the answer is "the PKS is software." Without subject matter otherwise.

### Stage 3 — Manifest and Constitution · **BLOCKED on `OQ-4` and `OQ-7`**

The proposal's §10 and §12. The manifest hard-codes contexts that `OQ-4` leaves open; the Constitution authors criteria into `AR-1`, which `AFV-F4` reserves to the Authority.

### Stage 4 — Full intake routing · **AFTER Stages 1–3**

The proposal's §1, §3, §11, §14. It cannot route to contexts that are not decided, or classify against contracts that do not exist. Until then the practice in `how_to_work_with_sessions` §§12/16/18/20 **is** the intake gate, executed by people — which the corpus shows working.

---

## 10. What this adds to v3

| v3 element | Change |
|---|---|
| §4 Principles | **+ `P-8` Environment over instruction** (§1) — with its boundary clause |
| §4 Principles | **+ `P-9` Gates follow ratification** (§0): *a gate may only enforce an architecture a recorded human act has decided* |
| §5 Vocabulary | `G-16 Exception Record` promoted from listed concept to load-bearing — it is what makes conflict adjudication non-binary (§9, Stage 1) |
| §10 Lifecycle | unchanged — the proposal's §7 *"creation and authority are different operations"* was already adopted there verbatim |
| §14 `OQ-8` | **elevated to the next actionable item**, with the proposal's §4 as its candidate form and a second independent derivation behind it |
| §14 | **+ `OQ-11`** — should the intake practice in `how_to_work_with_sessions` §§12/16/18/20 be mechanized, and at what point does mechanizing it start costing more than it protects? (the SLR's certification-bureaucracy risk, made into a question) |

**No new bounded context. No new container. No new folder. No new constitution.**

---

## 11. Answer to the question the proposal asks

> *"How do we make sure that when we build a rule, or decide something should be part of EKS, we actually follow the architecture?"*

The proposal answers: *architecture → contracts → invariants → fitness functions → workflow gates → human authority.*

**That chain is right, and it is built in exactly that order — which is the part the proposal skips.** Today the corpus holds the two ends and almost nothing between them: `human authority` is fully modelled and demonstrated (Stage 0); `architecture` is partly decided and partly open (`OQ-1`, `OQ-4`, `OQ-7`). Contracts are three fields away. Fitness functions are computable the moment contracts exist. Workflow gates need the contexts decided.

So the honest answer:

> **Close `OQ-8` — that is one schema change and it makes the first real gate computable. Then decide `OQ-1`. Everything else in the proposal is correctly conceived and prematurely ordered.**

And the check that keeps this from recurring, taken from the proposal's own §12 and turned on itself:

> **Before building any gate, ask which recorded human act decided the thing it enforces. If none, the gate is not enforcement — it is an architectural decision wearing enforcement's clothes.**

---

*Adjudicated against `docs/knowledge_tranfer/` (14 files) and `20260816_0841_target_architecture_v3.md`. Input recorded per the artifact-ingestion convention: the conformance proposal is **input**; this document is the assessed artifact it produces. v3.0 is unmodified.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
