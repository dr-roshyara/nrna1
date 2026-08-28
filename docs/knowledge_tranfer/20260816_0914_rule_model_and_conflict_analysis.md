# The Rule Model and Conflict Analysis — Adjudication

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — deepens `OQ-8`; companion to `20260816_0841_target_architecture_v3.md` and `20260816_0905_architecture_conformance.md` |
| **Input adjudicated** | The Rule Conflict Detection / EKS Architecture proposal (conversation input, 2026-08-16, §§1–15) |
| **Adjudicated against** | `docs/knowledge_tranfer/` (14 files) · Target Architecture v3.0 · the Conformance adjudication |
| **Answers** | *"Was this included in the previous documents?"* — **Partially. Roughly a third.** §0 states exactly which third. |
| **Date** | 2026-08-16 |

---

## 0. What was already included, and what was not

The Conformance adjudication (`0905`) captured the **shape** of the Rule Contract and the **fact** that conflict analysis becomes computable from it. It did not capture the analysis model itself.

| Element of the proposal | Previously included? | Where |
|---|---|---|
| Rule needs `subject` / `scope` | ✅ yes | Conformance §9 Stage 1 |
| Rule needs `applicability` | ✅ yes | Stage 1 · v3 `OQ-8` |
| Rule needs `normative effect` | ✅ yes | Stage 1 · v3 `OQ-8` |
| Conflict analysis unlocked by those fields | ✅ yes | Stage 1 — *"conflict detection is not a separate project; it is what these three fields make possible"* |
| Overlap ≠ contradiction; exceptions are legitimate | ✅ yes | Conformance §9, via `G-16 Exception Record` |
| Graph is a projection, not the source of truth | ✅ yes | v3 §12 |
| AI proposes, never decides | ✅ yes | v3 §9 · principle 3 |
| Modular monolith; containers are responsibility not deployment boundaries | ✅ yes | v3 §11.2, quoting `targer_architecure` Step 3 |
| **Formal rule structure — 7 elements, not 3 fields** | ❌ **no** | §3 below |
| **`NormativeEffect` as a value object with strength ordering** | ❌ **no** | §4 |
| **The satisfiability test** | ❌ **no** | §5 |
| **The 8-value relationship taxonomy** | ❌ **no** — only 3 outcomes were recorded | §6 |
| **Specialization / refinement / strengthening are NOT conflicts** | ❌ **no** | §7 |
| **Authority hierarchy in resolution; priority numbers rejected** | ❌ **no** | §8 |
| **Temporal intersection as a third conflict axis** | ❌ **no** | §9 |
| **Candidate partitioning — pairwise comparison does not scale** | ❌ **no** | §10 |
| **`RuleAnalysisService` / `ConflictReport` as domain capability** | ❌ **no** | §11 |
| **Hexagonal / CQRS / Specification / Policy / PostgreSQL stack** | ❌ **no** | §13 — and it is blocked |

**Nine of seventeen elements are new.** The most important of them — §5, §6, §7 — are the ones that make conflict analysis a domain discipline rather than string comparison.

---

## 1. The split that governs this adjudication

The proposal contains two different kinds of claim, with different evidence status, and they must not be adopted together.

| | **Part A — the Rule domain model** | **Part B — the implementation stack** |
|---|---|---|
| Content | §§2–13: rule structure, normative effect, applicability, satisfiability, relationship taxonomy, authority, temporality, partitioning | §1, §14, §15: hexagonal, modular monolith, PostgreSQL, CQRS, Laravel-independence, adapters |
| Depends on `OQ-1`? | **No** — it is conceptual. `Subject`, `Applicability`, `NormativeEffect`, `Scope`, `Validity`, `Authority`, `Exceptions` are domain concepts in any representation, YAML or database alike | **Yes, entirely** — every pattern in §15's table presupposes *"the PKS is software"* |
| Verdict | **Adopted as candidate model; deepens `OQ-8`** | **Recorded; blocked on `OQ-1`** — except the negative rulings (§13) |

This split is the reason Part A can proceed today. A formal rule model expressed in YAML is exactly as formal as one expressed in PHP value objects. **The corpus's unresolved question is about technology, not about rigour** — and the proposal's own §2 makes that point without noticing it: *"Don't model `Rule.text = "All APIs must use OAuth2"` — that is useful for humans but almost useless for conflict analysis."* True of a Markdown corpus and a relational schema equally.

---

## 2. The Core Domain claim, and where it lands on the AD-1 seam

The proposal closes with a strategic claim:

> *"The really valuable part of EKS is not 'I can store rules.' It is: 'I can determine whether a rule applies here, whether it is still valid, what evidence supports it, who authorized it, what other rules constrain it, and whether it conflicts with something else.' That is a genuine **Core Domain**."*

**This is the strongest strategic argument anywhere in the 2026-08-15/16 inputs, and it is adopted as a Core Domain hypothesis.** It is also the first proposal in the corpus that describes value in terms of a *question answered* rather than a *box drawn*.

But it does not sit inside one context. Against `AD-1`, it splits across the seam:

```
 AR-1  (CBC-3 Normative Governance — CANDIDATE SEAM, undefined)
   │      owns: what a Rule IS — subject · applicability · normative
   │            effect · scope · validity · authority · exceptions
   │
   │  criteria, read-only  (AP-7: AC-1 cannot author its own criteria)
   ▼
 AC-1  (Knowledge Assessment — ACCEPTED)
        owns: evaluating rules against each other — intersection,
              satisfiability, relationship classification, verdicts
   │
   │  verdicts (closed vocabulary)
   ▼
 AC-2  (Knowledge Projection)  — conflict reports, rule catalogues
```

Two consequences, both load-bearing:

1. **The Rule model belongs to `AR-1`, which is architecturally undefined and pending Authority disposition (`AFV-F4`).** So this proposal is, in effect, the first substantive argument in the corpus for *defining* `AR-1`. That is `OQ-7`. It does not fill it by drawing boxes — it fills it by supplying a model with content, which is the legitimate route.
2. **`RuleAnalysisService` belongs to `AC-1`, and `AP-7` binds it: it may evaluate rules, but may never define what a valid rule is.** A conflict engine that also decides rule well-formedness has authored its own criteria. The separation is not bureaucratic — it is what stops the analyser from ruling its own inputs valid.

The corpus already carries the concepts this needs: `G-2 Rule` · `G-3 Invariant` · `G-4 Ruling` (*a recorded governance act by an authority*) · `G-15 Verdict` · `G-16 Exception Record` · `G-17 Charter Grant`. **Nothing new must be invented for §§2–11 except `Claim`, already `G-18 CANDIDATE`.**

---

## 3. The formal Rule model — `OQ-8` deepened from 3 fields to 7 elements

The Conformance adjudication recorded *"the delta is three fields."* **That was too small.** The proposal's §4 gives the real shape:

```
Rule =  Subject
      + Applicability Predicate
      + Normative Effect
      + Scope
      + Temporal Validity
      + Authority
      + Exceptions
```

Mapped against what the corpus already has:

| Element | Status in the corpus |
|---|---|
| `subject` | ❌ new |
| `applicability` (a **predicate**, not a label) | ❌ new |
| `normativeEffect` | ❌ new — see §4 |
| `scope` | ❌ new |
| `temporalValidity` | ⚠️ partial — the 3-class lifecycle has terminal states, but no `validFrom`/`validUntil` interval |
| `authority` | ✅ exists — v3 §9 · `G-4` · `G-17` |
| `exceptions` | ✅ exists — `G-16 Exception Record` |
| `evidence` | ✅ exists — v3 §5.3, incl. `authored_by` |
| `lifecycle` | ✅ exists — v3 §10 |

**Revised `OQ-8`: four new elements plus one interval, not three fields.** The proposal's §2 list (`identity · statement · subject · predicate · scope · applicability · obligation/prohibition · authority · priority · effectivePeriod · exceptions · evidence · lifecycle`) is adopted as the candidate long form, **with `priority` removed** — see §8.

**The design ruling that justifies all of it,** in the proposal's own words and adopted verbatim:

> **A Rule cannot be created as just prose.** Free text is adequate for humans and inert for analysis. Everything in §§5–10 is impossible without structure, and no amount of AI compensates for its absence.

---

## 4. `NormativeEffect` — a value object, with asymmetry

```
MUST · MUST_NOT · SHOULD · SHOULD_NOT · MAY
```

The proposal's key observation, adopted:

- `MUST(X)` and `MUST_NOT(X)` are **directly contradictory** where applicability overlaps.
- `SHOULD(X)` and `MUST_NOT(X)` are **not symmetric** — the stronger effect may dominate, and which one does is a **governance policy**, not a logical consequence.

**That asymmetry is the reason effect must be a value object rather than a string.** A comparison function over `{MUST, SHOULD, MAY}` is a domain rule with an owner; a string comparison is an accident.

**Deferred:** whether the vocabulary is the five above or the alternative set the proposal floats (`REQUIRED · PROHIBITED · RECOMMENDED · PERMITTED`). Under `G-7 Term` — *"a ubiquitous-language entry; changes are first-class governed events"* — picking the vocabulary is a governed act. Recorded as `OQ-12`.

---

## 5. The satisfiability test — the formal core

The proposal's §4 states the question the whole engine answers:

> **"Is there any possible state of the world in which both rules apply and their normative effects cannot simultaneously be satisfied?"**

Worked, from §5:

```
R1:  production(API)                    ⇒  authentication(API) = OAuth2
R2:  production(API) ∧ internal(API)    ⇒  authentication(API) ≠ OAuth2

Seek:  API = production ∧ internal
             ∧ authentication = OAuth2
             ∧ authentication ≠ OAuth2

No satisfying assignment exists
  ⇒ R1 and R2 conflict over the intersection of their applicability domains
```

**Adopted as the definition of conflict.** Two properties make it worth the structure it demands:

- It is **decidable** — a conflict is proved or it is not, and the answer does not depend on who asks.
- It is **explanatory** — the output is not a boolean but the *region* over which the contradiction holds. §8's report shape (`Production · Internal API · Payments`) is that region, and it is what a human needs in order to resolve anything.

This is what distinguishes a rule *system* from a rule *list*, and it is the single strongest argument in the corpus for structured rules.

---

## 6. Relationship taxonomy — replaces the three-outcome vocabulary

The Conformance adjudication recorded three outcomes: `NO CONFLICT · EXCEPTION RELATIONSHIP REQUIRED · REQUIRES REVIEW`. **Too coarse.** The proposal's §6 and §11 supply eight, adopted:

```
CONSISTENT     the rules coexist; no interaction
SPECIALIZES    B applies to a subset of A's domain, same direction
REFINES        B tightens A without contradicting it
DUPLICATES     same subject, scope and effect — a single-canonical-home violation
OVERLAPS       domains intersect; effects not yet compared
CONFLICTS      proven unsatisfiable over the intersection
SUPERSEDES     B replaces A by a recorded act
EXCEPTION_TO   B departs from A within A's domain, under recorded authority
```

Two of these already have homes in the corpus and must not be duplicated: **`SUPERSEDES`** is `G-4 Ruling` territory and appears in v3's lifecycle chains; **`EXCEPTION_TO`** is `G-16 Exception Record`. **`DUPLICATES`** is the machine-detectable form of `G-2 Rule`'s existing *"single canonical home"* clause — which is the first time that clause becomes enforceable rather than aspirational.

`UNKNOWN` is added as a ninth value, and it is not a failure state: under v3 principle 6 — *silence is an architectural decision* — an unanalysable pair must report that it was not analysed rather than default to `CONSISTENT`. **Absence of a detected conflict is not evidence of consistency.**

---

## 7. Most contradictions are not conflicts

The proposal's §6 is the correction that keeps the engine usable, and it is adopted:

```
A: Production systems SHOULD use PostgreSQL
B: Payment systems   MUST  use PostgreSQL      →  SPECIALIZES + strengthens.  Not a conflict.

A: All services      MUST  use TLS
B: Payment services  MUST  use TLS 1.3         →  REFINES.  Not a conflict.
```

A naive engine flags both. An engine that flags both gets switched off within a month — which is the practical form of the SLR's recorded *certification-bureaucracy risk*: *"the layer must stay tied to measurable evidence or it adds cost without boundary quality."*

**Design ruling adopted: `CONFLICTS` is the narrowest verdict, not the default.** The burden is on the analyser to prove unsatisfiability, never on the author to prove consistency. This is `AD-1`'s posture — *absence remains absence* — applied to rule analysis.

---

## 8. Authority resolves conflicts; priority numbers do not

Adopted verbatim from §8: **numeric priority is rejected as a conflict-resolution mechanism.**

> *"Do not solve conflicts simply with `priority = 10` / `priority = 5`. That hides the actual contradiction."*

This is why `priority` is struck from the §2 long form in §3 above. A number that silently picks a winner destroys exactly the information the analysis exists to produce.

Resolution instead evaluates: **authority hierarchy · scope · specificity · temporal validity · exception status · explicit supersession** — and then, per the proposal's §8, reports:

```
R1 and R2 conflict
Applicable intersection:  Production · Internal API · Payments
Contradiction:            R1 requires OAuth2 · R2 prohibits OAuth2
Resolution status:        UNRESOLVED
Potential resolution:     R2 may be an exception to R1
```

**And then a human decides.** This is v3 `INV-A3` — *absence is never permission* — in its rule-analysis form: an unresolved conflict is not a permission to proceed, and the engine must never manufacture a winner. The proposal's §9 example is exactly right: a Security Architecture rule versus a local team rule is **not** auto-resolved in favour of the higher authority; it is reported, unless a recorded exception (`G-16`, with `approvedBy` and `validUntil`) already disposes of it.

---

## 9. Temporal validity — the third intersection axis

Newly captured. The conflict test is a **three-way intersection**, not two:

```
applicability ∩ scope ∩ temporal
```

From §10:

```
R1 valid 2025-01 → 2026-06   ·   R2 valid 2026-07 → ∞      →  no overlap, no conflict
R1 valid 2025-01 → 2027-01   ·   R2 valid 2026-01 → 2027-01 →  conflict during 2026-01 → 2027-01
```

Two consequences:

- **A conflict has a time span, not just a region.** A report that omits it is incomplete.
- **`temporalValidity` is a domain concept, not metadata** — it changes verdicts. This closes a real gap: the corpus's 3-class lifecycle records *what state a rule is in*, never *over what interval it binds*.

---

## 10. Pairwise comparison does not scale — partition first

Newly captured, from §12. At n rules, exhaustive comparison is O(n²); at 10,000 rules that is 50 million comparisons, nearly all of them between rules that share no subject.

**Adopted: candidate partitioning precedes comparison.**

```
Rule → subject → scope → domain → technology → applicability
                    ↓
      compare only within a partition that can overlap
```

And the ruling that ties it to v3 §12, which the proposal states itself:

> *"The graph is a projection/query accelerator, not necessarily the transactional source of truth."*

**This is the first place in the corpus where a graph earns its existence from a stated need** — candidate selection over a partitioned rule space — rather than appearing in a storage list. It remains a projection: rebuildable, depended on by nothing (`DR-1`), carrying no independent semantic identity (`L4-8`).

---

## 11. `RuleAnalysisService` as a domain capability

Adopted from §11 and §13. The capability is a **domain service**, not a prompt:

```
RuleAnalysisService.compare(Rule A, Rule B) → RuleRelationship

ConflictReport
├── ruleA · ruleB
├── applicabilityIntersection
├── temporalIntersection
├── contradictoryEffects
├── authorityComparison
├── evidence
└── confidence
```

**The AI boundary, adopted as stated and already an invariant in v3 §9:**

```
Rule A + Rule B
      ↓
Candidate detection        ← AI / search MAY operate here
      ↓
Formal rule analysis       ← domain only
      ↓
  Proven | Unknown
      ↓
Human / Authority resolution
```

AI may emit `PotentialConflict`. It may never emit `Conflict = TRUE`. That is v3's *"the mechanism records authority; it never grants it"* applied one level down — and it is also the SLR's own recommendation: *"AI role: better as review aid than autonomous design authority."*

**`confidence` in the report is confined to the candidate-detection stage.** A proven unsatisfiability has no confidence value — it is proved or it is not. Attaching a confidence score to a formal result would reintroduce the *"scores hide the reasoning"* failure the corpus's evidence discipline exists to prevent.

---

## 12. What this adds to the two prior documents

| Document | Change |
|---|---|
| v3 §5 Vocabulary | `G-2 Rule` gains a candidate long form (§3) · `NormativeEffect` enters as a candidate value object (§4) · `G-16` and `G-4` become load-bearing (§6) |
| v3 §14 `OQ-8` | **Restated: four new elements plus a validity interval, not three fields.** Its candidate form is §3 |
| Conformance §9 Stage 1 | Stage 1 is larger than recorded, and its outcome vocabulary grows from 3 values to 9 (§6) |
| v3 `OQ-7` (`AR-1`) | **First substantive content proposed for the undefined normative region** (§2) — the legitimate route to filling it |
| v3 §12 Persistence | Unchanged. The graph earns a *stated purpose* (§10) but remains a projection |

**Still no new bounded context, no new container, no new constitution.**

---

## 13. Part B — the implementation stack

Recorded in full; **blocked on `OQ-1`**, which asks whether the PKS is software at all. `what_is_pks_v1` §5.1 and `pks_progress` §5.1 say no; `C4 Level 4 — Code.md` names PHP 8.3 / Laravel 12 / PostgreSQL / Neo4j. Until a human decides, §§1/14/15 have no adjudicable subject.

**Blocked (positive stack):** hexagonal boundary · application/domain/infrastructure layering · PostgreSQL · object/evidence store · CQRS · repositories/adapters · Laravel-independence checks.

**Two things survive `OQ-1` either way, and both are adopted:**

1. **The negative rulings**, because they subtract rather than add — and `pks_progress` records that *"subtraction is sometimes more valuable than addition"*:
   > *Do **not** start with microservices, event sourcing, or a generic AI agent architecture.* · *Do not make Event Sourcing, microservices, or Graph DB mandatory patterns for V1.*

   These are recorded as **standing constraints on any future V1**, whatever `OQ-1` decides. A "no" needs no technology to be true.

2. **Modular monolith / responsibility boundaries** — already in the corpus. `targer_architecure` Step 3: *"These are logical containers, not necessarily microservices… Initial implementation may be modular monolith; container boundaries are responsibility boundaries, not deployment boundaries. This avoids premature microservice design."* v3 §11.2 carries it. The proposal independently reaches the same position — **a second arrival, which strengthens it.**

**Not adopted:** §15's pattern table as an entry in an Architecture Constitution. Per Conformance §6, the Constitution is `OQ-7` — a proposal to author criteria into `AR-1` — and a pattern catalogue adopted before `OQ-1` would bind technology choices to an undecided question.

---

## 14. Open questions added

| # | Question | Settled by |
|---|---|---|
| **OQ-12** | Which `NormativeEffect` vocabulary — `MUST/MUST_NOT/SHOULD/SHOULD_NOT/MAY` or `REQUIRED/PROHIBITED/RECOMMENDED/PERMITTED`? | A governed act under `G-7 Term` (§4) |
| **OQ-13** | Does `SHOULD(X)` versus `MUST_NOT(X)` resolve by strength, or report as `REQUIRES REVIEW`? | Governance policy — it is not a logical consequence (§4) |
| **OQ-14** | Is Rule Analysis the Core Domain? If so, `CBC-3` moves from *candidate seam* to a defined context and `AFV-F4` is discharged | Human authority — this is `OQ-7` reached from a new direction (§2) |
| **OQ-15** | Does `temporalValidity` extend the certified 3-class lifecycle, or sit beside it as an independent interval? | §9 — the corpus has no validity interval today |

---

## 15. The bottom line

The proposal's §2 ruling is the one that matters most, and it is adopted without qualification:

> **A Rule modelled as text is inert.** Everything worth having — applicability, conflict, exception, supersession, temporal binding, authority resolution — requires structure, and no amount of AI substitutes for it.

That single ruling is what makes `OQ-8` the corpus's next actionable decision, and it now has **three independent derivations** behind it: the knowledge-model route (v3, from `eks_2.0` §9's Scope model), the enforcement route (Conformance, from the gate proposal's §4 Rule Contract), and the analysis route (this document, from conflict detection).

**Three independent arrivals at one gap is the strongest evidence this corpus contains for anything.**

And the Core Domain claim in §2 above is, on the evidence, correct: the value is not storing rules. It is answering *does this rule apply here, is it still valid, who authorised it, what constrains it, and does it contradict something else* — which is precisely `AR-1` plus `AC-1`, the one region `AD-1` left undefined and the one it accepted.

---

*Adjudicated against `docs/knowledge_tranfer/` (14 files), `20260816_0841_target_architecture_v3.md`, and `20260816_0905_architecture_conformance.md`. Input recorded per the artifact-ingestion convention: the proposal is **input**; this document is the assessed artifact it produces. Neither prior document is modified except by the cross-reference noted in the Conformance file.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
