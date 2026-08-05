# Architecture Knowledge Transfer (AKT)

**PublicDigit • KnowledgeOS • PKS**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 1 of 10 + Appendix** |
| **Part** | **Part 1 — Programme Overview & Architectural Context** |
| **Baseline** | **PKS Phase III — Operational Validation** (Charter `PKS-P3` **ISSUED** 2026-07-31; governance baseline **SDM v1.2 / EOP v1.2, FROZEN, reference-defined**) |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED — see §0* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |

---

## 0. What this document is, and what it is not — read this first

This is **not a conversation summary**. It is an onboarding artifact whose purpose is that a new AI collaborator can **reason like an experienced member of this programme's Architecture Review Board on day one**.

It is bound by the same discipline it describes, so three limits are stated up front rather than discovered later:

| | |
|---|---|
| **It CITES authority; it never CREATES authority** | Per **ES-001.2** — *documents record governance, they do not create it.* Nothing in this AKT approves, adopts, promotes, ratifies or closes anything. Where a decision is pending, this document says *pending*. |
| **Its lifecycle state is AUTHORED** | The programme distinguishes **AUTHORED · ISSUED · PROPOSED · ADOPTED · BINDING** — *creation is not adoption, and a recommendation to create is not an issuance.* This AKT has been authored. It has not been issued as a governance instrument, and it is not binding on anyone. |
| **On conflict, the repository wins** | The canonical sources are the frozen artifacts, the ES standards, the ADR/ADR-T logs, the rulings registers, `.claude/MEMORY.md` and `.claude/CONTEXT.md`. If this AKT and a cited artifact disagree, **the cited artifact is correct and this AKT is stale.** |

> **The single most important behavioural instruction in this entire document:** in this programme, **being right is not the same as being authorized**. You will frequently produce a correct analysis that you may not act on. That is the normal, designed state — not a blocker to be worked around.

---

## 1. Programme Overview

This repository holds **one product and two engineering assets built around it**, developed together and deliberately kept distinct:

```
┌──────────────────────────────────────────────────────────────────────────────┐
│  ONE REPOSITORY · THREE ASSETS · THREE DISTINCT RESPONSIBILITIES             │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   KnowledgeOS          DEFINES reusable engineering governance               │
│        │                                                                     │
│        ▼                                                                     │
│   PublicDigit          the REFERENCE IMPLEMENTATION and EVIDENCE GENERATOR   │
│        │               — a proving ground, not merely a consumer             │
│        ▼                                                                     │
│   Operational evidence                                                       │
│        │                                                                     │
│        ▼                                                                     │
│   PKS                  HOLDS PublicDigit's structured knowledge              │
│        │                                                                     │
│        └──────────────▶ KnowledgeOS refinement ──┐                           │
│                                                   │                          │
│        ┌──────────────────────────────────────────┘                          │
│        ▼  (the loop closes)                                                  │
│                                                                              │
│              ⭐ PublicDigit is the VALIDATION ENGINE for KnowledgeOS.         │
└──────────────────────────────────────────────────────────────────────────────┘
```

**Source (authoritative, verbatim in substance):** `docs/implementation/PKS_Phase_III_Operational_Validation_Charter.md` **§6.1 — "The three assets and their DISTINCT responsibilities" (Authority, 2026-08-01).**

The programme is therefore **two-layered by design**:

| Layer | What is being built | How it is validated |
|---|---|---|
| **Product layer** | PublicDigit — a multi-tenant election and constitutional-governance platform | Tests, architecture fitness gates, real elections |
| **Method layer** | A governed engineering methodology (KnowledgeOS) plus the knowledge system that captures its evidence (PKS) | **By engineering PublicDigit with it** — there is no other validation route |

**Why that matters to you immediately:** work on PublicDigit is never *only* product work. Every architectural decision, every friction point, every rule that prevented or failed to prevent a defect **is data about the methodology**. That is the explicit mandate of the current phase.

---

## 2. Vision

### 2.1 Product vision — PublicDigit

> ***"Democratize democratic processes"*** — enable any organisation to run secure, transparent elections with complete voter anonymity and ironclad audit trails.

The programme mantra: ***"Vote with confidence, audit with certainty, remain completely anonymous."***

Concretely, PublicDigit exists because remote democratic participation requires elections that are simultaneously:

- **accessible** (vote from anywhere),
- **anonymous** (no voter↔vote linkage is even *representable* — see §3.1),
- **verifiable** (per-voter audit trail without compromising anonymity),
- **isolated** (multi-tenant; one organisation can never see another's data),
- **scalable** (10 to 100,000 voters).

### 2.2 Method vision — KnowledgeOS + PKS

> **A methodology's internal coherence and its operational effectiveness are different claims. The programme refuses to conflate them.**

The method vision is that **engineering governance itself becomes a governed, evidence-based, reusable asset** — one whose rules are admitted only because a real defect escaped without them, and whose credibility is bounded by the evidence actually collected. The long-term hypothesis (**explicitly not yet validated**) is that this methodology could be a product in its own right.

**The programme's own recorded position on that hypothesis** (and you must not overstate it):

> *"Can PKS Engineering become a commercial product? **Potentially yes.** Current evidence supports **feasibility, not commercial viability**. Commercial viability requires separate product validation."*

### 2.3 The vision that unifies both

The two visions meet in one sentence from the Phase III Charter:

> ***Effort goes to PublicDigit engineering. No further governance document unless NEW EVIDENCE requires it.***

---

## 3. Programme Components

### 3.1 PublicDigit — the product, and the validation engine

**What it is:** a multi-tenant election management, voting and constitutional-governance platform.

**Programme role (Charter §6.1):** the **reference implementation and evidence generator** — *"the proving ground, not merely the consumer."*

**Core domain concepts:** Election · Post (national vs regional) · Candidacy · Code (two-use voting code) · Vote · Result · Voter Slug + Voter Slug Step (5-step workflow) · Organisation (tenant) · Committee · Contestation · Adjudication.

**The constitutional invariant that shapes everything (ADR-T11):**

```
votes   table:  NO user_id column   ← voters cannot be linked to votes
results table:  NO user_id column   ← selections are anonymous
voting_code:    hashed audit trail only (irreversible)
```

Anonymity is a **constitutional invariant**. Note the ownership subtlety, which the programme states explicitly and which you must preserve: *anonymity is an invariant that some contexts (e.g. Messaging) **preserve** but do **not own**.* Resolve ownership of an invariant **before** protecting it.

**Multi-tenancy model — two deployment modes:**

| Mode | Discriminator | Use |
|---|---|---|
| **MODE 1 (Demo)** | `organisation_id = NULL`, separate `demo_*` tables | customer testing, platform demos, onboarding |
| **MODE 2 (Live)** | `organisation_id = X` | production elections |

Isolation is enforced in depth: database FKs on `organisation_id` → `BelongsToTenant` global scope → `TenantContext` middleware → `session('current_organisation_id')`.

**Implemented bounded contexts (code-verified):** `app/Contexts/{Contestation, Adjudication, Election, Shared}` — these four are the only ones inside the structural enforcement perimeter (Deptrac + greenfield PHPStan + `GreenfieldCoreArchitectureTest`). **`app/Console/Commands/` and `app/Helpers/` have ZERO structural coverage** — a fact that has already produced a real finding (§7.4).

**Tech stack:** Laravel 11 / PHP 8.2 · Vue 3 + Inertia.js 2.0 · PostgreSQL · Laravel Sanctum + Socialite · Spatie Permission · custom multi-tenancy · PHPUnit (TDD-first). *(Full detail belongs to Part 2/Part 7.)*

### 3.2 KnowledgeOS — reusable engineering governance

**What it is (Charter §6.1):** the asset that **defines reusable engineering governance** — the methodology, standards and governance instruments that are *not* specific to PublicDigit.

**Status — state this precisely, it is repeatedly mis-stated:**

| Thing | Canonical status label |
|---|---|
| Engineering Knowledge Architecture Baseline | **v1.0 — Ready for ratification**; baseline status becomes effective **upon ratification** |
| Operational Validation | **v1.0 — Prepared** (playbook final; phase unstarted) |
| **KnowledgeOS Platform** | **v0.1 — Not started.** *Architecture ≠ product; software is future work* |

Canon names KnowledgeOS a **prospective product** *"if their gates open"*, and the Engineering Platform a **Supporting Subdomain**. One gate stands before anything executes: **G-1 charter approval (three asks)**. Prepared stack: `docs/implementation/KnowledgeOS_*`.

**DDD precision on KnowledgeOS's own status** (recorded, and worth carrying):

> **"KnowledgeOS has not yet undergone Strategic DDD and is therefore not yet a *modeled* domain within this methodology."** — *not* the looser "KnowledgeOS is not yet a domain." It plainly refers to a real problem space (governed engineering knowledge); what it lacks is a model, not a referent.

**⚠️ OPEN QUESTION OQ-5 — DO NOT RESOLVE THIS BY ASSUMPTION.** *Is `KnowledgeOS` the same thing as the Engineering Platform (`engineering/`)?* The documentation-roots ADR's Context section **equates** them; canon **separates** them (DA clarification 2026-07-27 + approved **R-67**: `engineering/` expresses **cross-product SCOPE, not a domain**). Consequence if they are distinct: routing *"ES-xxx standards, methodology"* to `docs/knowledgeos/` is **wrong** — the ES set is cross-product and belongs in `engineering/`, and `docs/knowledgeos/` would hold KnowledgeOS **product** documentation only. **Owner: ARB. Raised 2026-08-01. Not resolved.** Today, cross-product artifacts still route to `engineering/`.

### 3.3 PKS — Product Knowledge System

**What it is (Charter §6.1):** the asset that **holds PublicDigit's structured knowledge** — the operational evidence, the strategic model of that knowledge, and the lessons the engineering work produces.

**Where PKS stands (the most governance-dense area of the programme):**

| Phase | State |
|---|---|
| **Phase I** | **ACCEPTED**; Strategic Modeling **AUTHORIZED** (ARB rulings **DR-1..DR-6**, 2026-07-28). Record: `docs/implementation/PKS_Phase_I_ARB_Rulings.md` |
| **Phase II** | **MODELLING COMPLETE — M0→M8 executed** (2026-07-28). Capstone: `PKS_Phase_II_M8_Strategic_Modeling_Report.md`. Consolidated strategic baseline **FROZEN under change control** |
| **Method certification** | **PROVISIONALLY CERTIFIED**, two-dimensional: **Method Design** provisionally certified · **Operational Evidence ZERO-INDEPENDENT**. The dimensions dispose separately |
| **Baseline** | **SDM v1 → v1.2 / EOP v1 → v1.2 FROZEN, reference-defined**; **Process Under Configuration Control DECLARED** — changes only via *execution evidence → MCA-class assessment → CDR-class decision → issuance* |
| **Phase III** | **CHARTERED + ISSUED 2026-07-31** (`PKS_Phase_III_Operational_Validation_Charter.md`). *"Operational use may begin."* |

**The Phase II strategic model (cite the structure freely; cite pattern names carefully):**

| Element | Standing |
|---|---|
| **CBC-1 Knowledge Assessment** | accepted bounded context (Medium-High) |
| **CBC-2 Knowledge Projection** | accepted bounded context (Medium-High) |
| **CBC-4 Work Management** | **ADJACENT** — the domain's outer edge (Medium) |
| **CBC-3 Normative Governance** | accepted **candidate seam**, evidence-decided (Low-Medium) |
| **Expressed-knowledge core** (Decision · Term · Model element · Contract) | **unpartitioned region** |
| **Risk / Question / Exception record** | **contested, unassigned** |

Relationships after **DAR-1**: R-2 CBC-1→seam Customer/Supplier over a narrow Published Language · R-3 all sources→CBC-2 Conformist **plus a unidirectionality constraint: "nothing may cite a view as authority"** · R-5 CBC-2↔CBC-4 Separate Ways · **R-1 and R-4 carried as pattern-free dependencies** · graph acyclic.

> **BINDING CITATION RULE:** cite the **structural layer** (dependency · direction · ownership) freely; **cite Evans pattern names ONLY where they survived DAR-1.** Directions are not automatically patterns.

**Two ceilings on every PKS claim — never overstate past them:**

1. **Phase II is same-lineage end-to-end (T-2): it contributes NO structural-independence datum.** One corpus, one lineage — *never citable as independently confirmed.*
2. **Operational Evidence is at zero-independent and was unmovable by a governance programme.** *Only USE can move it.* A certification dimension derives credibility from being **outside the unilateral control of the programme it certifies**.

**⚠️ The PKS corpus is currently split across two locations** — 89 PKS documents remain in `docs/implementation/` while new ones land in `docs/pks/`. This is *correct behaviour on new work while migration is blocked*, not drift, but it means **you must search both**.

### 3.4 Why the three were separated

Four independent reasons, each recorded:

1. **They have genuinely different responsibilities** (Charter §6.1): *define* governance · *hold* knowledge · *generate* evidence. Treating them as three independent projects is **weaker** than naming the responsibilities and making the evidence flow between them explicit.
2. **Conflation was actively producing bad reasoning.** An earlier document was criticized precisely for conflating **engineering evidence**, **product vision** and **business strategy**; separating them was the improvement.
3. **The repository had them physically intermingled**, which corrupted classification: `docs/implementation/` alone held **89 PKS + 3 KnowledgeOS** artifacts in one directory. The documentation-roots ADR established `docs/publicdigit/` · `docs/knowledgeos/` · `docs/pks/` to fix ownership and placement.
4. **Different rates of change and different authorities.** Product architecture, cross-product methodology and knowledge evidence do not move at the same speed and are not owned by the same authority.

### 3.5 ⚠️ What the loop does NOT decide — the single most important caveat in Part 1

The loop in §1 is **Phase III's OPERATING POSTURE, adopted at Authority direction. It is not a governing rule, and it is not a description of history.**

| | |
|---|---|
| ⛔ **It does NOT decide EAD-1's D-3** | *"Should the evolution rule become governing?"* — **the loop IS that rule closed into a cycle.** Recording it as governing would **decide D-3 by assertion.** |
| ⛔ **It does NOT decide D-5** | **The three names do not become governed terms by being given roles.** |
| ⚠️ **The link has NEVER BEEN TRAVERSED** | **EAD-1 §E.1:** the corpus's own provenance runs **the other way** — the framework was built from **review practice**, not from product evidence. |
| ✅ **Therefore** | **Phase III IS the first traversal.** That is exactly why D-3 must stay OPEN: *the traversal is the evidence that would decide it, and it has not happened yet.* |

**Operationally:** the KnowledgeOS feedback loop achieved its **first operational validation on 2026-08-01** — one full traversal in one day (Observation → Classification → Placement → Validation → Operational Evidence → Backlog) — with two recorded boundaries:

- **Boundary 1:** *one traversal, not a demonstrated capability.* (Platform's own test: **one script proves possibility; routine use proves capability.**)
- **Boundary 2, the important one:** **the loop has not yet closed on the model.** It delivered evidence *to* the decision point and stopped — no ruling issued, no standard amended, no candidate promoted. **It showed that evidence REACHES authority; it has not shown that evidence CHANGES the model.**

Recorded claim, at exactly this strength: *"first operational validation of the KnowledgeOS feedback loop, **up to the decision point**."*

---

## 4. Engineering Philosophy

### 4.1 The standing order of work — never skip upstream stages

```
Business need → DDD model (language, boundaries, ownership) → Architecture decision (recorded)
             → Tests (RED) → Implementation (GREEN)
```

- **Business first.** No change is "just technical" if it touches behaviour.
- **DDD before architecture.** What is it? Which context owns it? What does it *own* vs merely *preserve*?
- **Architecture decision before tests.** *A test encodes a decision; the decision must exist first.*
- **Tests before implementation.** RED, then minimal GREEN. Architecture/fitness tests verify **properties**, not class names.
- **For a discovered gap the sequence is:** `Finding → Architecture Decision → RED → GREEN → Certification`. **Never** `Finding → Implementation → Certification`.

> **Anti-pattern, stated as a rule:** *never let a ticket or an observation silently BECOME architecture.* **Architecture produces tickets; tickets do not accrete into architecture.** When several tickets have produced a reusable capability, **model it as a platform capability first**, then let tests and consumers follow.

**When in doubt, stop and model.** Producing a strategic model or ADR **is real work, not a detour**.

### 4.2 Evidence-first, expressed as an admission filter

> **A quality or rule becomes GOVERNED only after REPEATED operational evidence that its absence let defects escape.** One instance admits a **CANDIDATE** — or, where severity is constitutional, a **PROVISIONAL** control that operates immediately.

**The ladder:** `Candidate → Provisional → Governed → Declined` (derived from real practice: PMR-6 n=0 · Rule 16 n=1 · Rule 15 declined at n=1 · MCR-5 statement/instrument split).

**The filter's one-line form, applied to every proposed addition:** ***"Did a real defect escape because this was missing? If not, don't add it."***

Companion bars:

- **One corpus is one observation.** A rule generalized from one corpus is *a rule fitted to one corpus*, however many instances it produced. 47 references and 30 targets are still **one class of failure in one repository**. Promotion waits for **another unrelated repository**.
- **Abstraction waits for a SECOND CONSUMER.** A single implementation is a hypothesis, not a demonstrated abstraction. (Live example: ENG-008 stays closed *for a stated reason* — externalizing the link-repair confidence policy would introduce abstraction before a second consumer exists. **Trigger written into the backlog item itself**, because *a trigger recorded only in a report is a trigger nobody will see.*)
- **A recurring unruled classification is a MODEL GAP, not a count.** You evolve a model when reality **repeatedly exposes the same missing concept from unrelated directions** — not when instances accumulate. *(Three independent arrivals at `cross-product + research` in one day, from three commissions, none looking for the gap.)*

### 4.3 The closing meta-principle — carry this into every act

> ***Every governance act should establish only the STRONGEST CLAIM THAT ITS EVIDENCE PRESENTLY SUPPORTS.***

A **quality criterion**, not a methodology step. It explains nearly every refinement the programme has made: *confirmed vs strengthened · dissolved vs superseded · document vs rule vs binding · rationale vs ontology · readiness vs authorization · implementation vs adoption · observation vs methodology · disposition vs terminal state.* **Every one narrowed a claim; none changed an outcome.**

Corollaries you will need constantly:

- **Same-lineage corroboration improves internal coherence but creates NO independent confirmation.** A new lens over the same corpus is not new evidence.
- **Closure wording matters.** Never *"all broken links were fixed."* Say: *"All deterministic link repairs have been completed. Remaining unresolved references have been classified and transferred to backlog or governance."* (53 references remain broken — **classified, counted, attributed and owned; not repaired**, and the record says so.)
- **A closed workstream with explicit successors is finished; one with hidden TODOs is not.**

### 4.4 Architecture evolves from operational evidence — including its freeze

> **"Architecture is frozen" is not a claim about review effort; it is a claim about RESIDUE.**

State it as: **every architectural uncertainty is either RESOLVED or DELIBERATELY TRANSFERRED to a named non-architectural owner — no undecided architectural responsibility remains.** Without that, a reader concludes *"frozen because nothing else can be reviewed"*, a much weaker and different claim.

Discharge rules:

- **By ENUMERATION, not assertion.** List every architectural question raised, each with exactly one outcome: **Resolved · Transferred (owner named) · Outside scope.** *The count is checkable; confidence is not.*
- **Every transferred item needs a named owner AND a class** — Governance · Business Decision · Programme Management · Operational. **An item with no owner is not transferred, it is forgotten.**
- **Name each reopening condition with its BLAST RADIUS** — that is what makes it non-blocking.
- **Freeze must be enforceable:** pair it with explicit **implementation constraints** and the **reopening standard** — *only if implementation exposes a genuine design issue*, never for refinement, expression or pattern preference.
- **Diminishing-returns signal:** when successive commissions refine *how* decisions are expressed rather than discovering new architectural responsibilities, the correct next act is a **transition/handover commission**, not another design review.

### 4.5 The four-level substitutability model — turn judgement into lookup

| Level | Role | Authority | Engineering may change? |
|---|---|---|---|
| **Business Policy** *(e.g. retention durations are Q-2's)* | **decides WHAT** | Q-2 / ARB | ❌ never |
| **Architectural Invariant** *(e.g. MAD has exactly one canonical home)* | **protects WHAT** | ARB | ❌ never |
| **Mechanism** *(e.g. consumer-side port vs importing the provider's port)* | **decides HOW** | engineering, **within** the invariant | ✅ **the ONLY substitutable level** |
| **Implementation** | **realizes HOW** | engineering | ✅ yes |

**Before hunting for a clever fix, identify the level.** *A level-3 collision dissolves without touching the model; a level-2 collision cannot be engineered around — return to the ARB.*

**The Layer Verification Rule** (`engineering/knowledge/methodology/Layer_Verification_Rule.md` — **STATUS: PROPOSED, NOT ADOPTED; cite as a recommended heuristic, never as authority**):

> **Can this layer change WITHOUT changing the layer above it?** **YES** → it belongs at this layer. **NO** → you are modifying the wrong abstraction.

- **THE DUAL — what a failure MEANS:** *if changing this layer forces a change above it, you have discovered an **architectural** dependency, not an implementation one.* **A failing rule ESCALATES; it does not block.**
- **Proposal checklist:** (1) which layer is intended to change? (2) which higher layer would also change? (3) if any higher layer changes → **escalate before implementation**.
- **Validated retrospectively against this project's two worst defects, both of which passed every automated gate:** **AP-2** (a MAD key placed in a retention config) destroyed *"one canonical home"* — an **invariant breach dressed as a mechanism choice**; **AP-1** (`max(1, $days)`) overrode *"Q-2 decides durations"* — an **implementation edit reaching two levels up**.
- **Apply it to any change that feels like "just a technical choice."**
- ⚠️ **Plans routinely record an invariant and its mechanism in the SAME SENTENCE**, which is why they get read as one thing. **Split them before treating either as binding.**

### 4.6 Deterministic work may be automated; ambiguity returns to governance

- **🔑 Runtime tooling ENFORCES governance; it never DUPLICATES governance.** Hooks **derive** the governing standards at runtime (e.g. by reading `engineering/governance/STANDARDS_INDEX.md`), quote no rule text and hardcode no standard number — so renumbering, supersession or rehoming is followed automatically. *(The first version of one hook embedded the rule text and ES numbers, making runtime tooling a second home for governance. Caught in review.)*
- **Governance precedes automation. Automation may implement governance. Automation never defines governance.**
- **The smallest automation set justified by evidence:** the standards consolidation concluded **ZERO new hooks**.
- **Non-blocking on purpose.** Guards are checkpoints, not walls: *a wall that is always dismissed teaches less than a checkpoint that is read* — and **a hook cannot evaluate the answers to its own questions; only the author can.**
- **Placement is EXECUTABLE, and must be RESOLVED, never hard-coded:**
  ```bash
  php scripts/doc-placement.php --scope=<product-specific|cross-product|session-state> \
                                [--maturity=<research|qualified|adopted>] [--domain=<id>]
  php scripts/doc-placement.php --list | --self-test | --verify
  ```
  **Exit code 2 = the classification is real but its placement is UNRULED → record `PENDING` and ESCALATE. NEVER invent a destination.** *Tooling that guesses a destination is how the mixing happened.*
- **Repair only on EVIDENCE, never on the best-looking candidate.** Confidence bar: **100** git rename record / documented migration / exact existing target · **99** exactly one file in the repo carries that basename · **75** several candidates = **AMBIGUOUS** · **0** no candidate = **MISSING**. **Only ≥99 may be auto-applied. Ambiguous and missing are EVIDENCE, never repairs.** *(A first attempt that picked the "best" candidate produced a plausible-looking wrong answer that would have silently corrupted navigation. Discarded and tightened to unique-candidate-only.)*
- **Migrations are DECLARATIVE, not coded:** `docs/knowledge/schema/repository-migrations.yaml`, read by `scripts/link-check.php`. A future migration is a **registry entry**, not a code change.
- ⚠️ **GREEN LINT IS NOT A GREEN REPOSITORY.** `knowledge-lint` validates `docs/knowledge/` **only**; a repo-wide scan found **121** broken links where the linter reported **9**. **Always record the baseline before and after.**

### 4.7 Laravel with discipline — the layering that applies to product code

**Laravel is the framework. Use it, don't fight it — but use it with discipline.**

| Layer | Laravel features |
|---|---|
| **Infrastructure** | ✅ allowed freely (Facades, Eloquent, route binding, Eloquent events, SoftDeletes, traits) |
| **Application** | ⚠️ limited — **no Facades** (constructor injection), **no Eloquent** (repository interface), **no route binding** (explicit `findOrFail`), **DTOs required, never arrays** |
| **Domain** | ❌ **zero Laravel dependencies. Pure PHP only** |

Plus: Value Objects for domain concepts · CQRS-light (Eloquent for reads, Repository + DTO for writes) · repositories **for aggregates only** · exceptions typed by layer · `final` in Domain and for Application commands/handlers · **Inertia 2.0 form submissions go through `router.post()`, never raw fetch**.

**The golden rule:** *Use Laravel for what Laravel is good at. Use Clean Architecture for what you need to protect. Know the difference.*

**Testing discipline — non-negotiable:** the development database is **sacred**. Never `migrate:fresh`, `migrate:refresh`, or `test --seed` against it. Use `php artisan test` with `RefreshDatabase`.

---

## 5. Governance Philosophy

### 5.1 The constitutional rule everything else rests on

> **ES-001.2 — Documents record governance; they do not create it.** *(ARB 2026-07-11)*
>
> Only explicit ARB decisions create governance. **Workflow words — *continue*, *looks good*, *go ahead* — are permission to proceed, never Approved / Promoted / Retired / Closed.** When a governance decision is needed: **STOP and ask per item (Approve / Reject / Defer).** Authors propose; the authority adopts. **No artifact may assert an unoccurred adoption** (AIP-10).

### 5.2 Three roles, three acts — and they never merge

| Role | Act |
|---|---|
| **The Authority** | **decides** |
| **The Recording Architect** | **records** |
| **Engineering** | **executes** |

> **Recording is not deciding; deciding is not executing.**

**Lifecycle:** `Evidence → Recommendation → Authority Decision → Recorded Outcome → Engineering Handover → RED`

### 5.3 Preparation vs Recording — two artifacts, never one (including in the filename)

| Artifact | When | Contains |
|---|---|---|
| **PACKAGE** | **before** the session | evidence · recommendations · traceability · **blank** templates |
| **RECORD** | **after** the session | rulings · outcomes · state transitions |

- **Never write "is accepted" / "is ratified" in a package, and never pre-fill an outcome — not even the recommended one.**
- **A preparation commission ENDS at the boundary before authority is exercised.**
- *"No further governance act needed"* **is almost always wrong wording.** If outstanding authority decisions are listed, those **are** governance acts. Say: **"no further governance PREPARATION is required."**
- *(Learned the hard way: an artifact was named a "Record" before any ruling existed. The body correctly recorded nothing — **the body held the line; the name did not.**)*

### 5.4 The review gate, and who may act on findings

> **(a) THE REVIEW GATE:** *review delivered → Authority disposes findings → apply → record.* **A reviewer may not apply its own findings before disposition — including its own supplementary findings.**

> **(b) MODEL B FOR PREMATURE EDITS:** a later approval **authorizes keeping** an early edit; it does **NOT** retroactively legitimate it. Chronology is preserved: *the edit was premature · the Authority later approved the change · the repository is therefore in a valid state.* This is the **forward-only principle (L1-7)** applied to process legitimacy — the programme never re-legitimates in place.

**Consequences that recur constantly:**

- **Observations are not decisions.** A recorded observation is **routed, never enacted**.
- **Recommendations are not architecture until the Authority disposes of them.** Nothing becomes architecture accidentally.
- **Readiness is evidence; acceptance is authority.** *Architecturally authorizing a transition ≠ authorizing RED to start.*
- **Analysis cannot ratify itself.** The sequence is **analysis → recommended realization → ARB ratification → resolution** — never analysis → resolution → ratification. A report that recommends *and* declares resolved while listing pending ratifications **contradicts itself**. *(Check both directions: applying this to someone else's gate is not the same as applying it to your own recommendation.)*
- **A sequencing deviation is RECORDED, not reverted.** When work was applied before authorization but satisfies the criteria later approved: **audit it, record a governance note, do not roll back.** *Reverting and re-applying reproduces the identical repository state and only launders the sequence* — and ES-004.3 forbids rewriting history to manufacture consistency.

### 5.5 Governance categories — one transition, one authority, one artifact

**Four categories, canonical and stable** *(single home: `Layer_Verification_Rule.md` §3 — cite, never restate)*:

| Category | The lifecycle transition it owns | Durable artifact |
|---|---|---|
| **Architecture** | a design question is **decided/interpreted** | approved architectural decision |
| **Planning** | a work package is **OPENED** (or its plan approved) | approved work package |
| **Execution** | engineering is **AUTHORIZED to begin** | authorized engineering activity |
| **Delivery** | completed work is **ACCEPTED and CLOSED** | accepted **implementation** baseline |

- **The organizing idea is the LIFECYCLE TRANSITION, not the artifact.** The artifact is **evidence the transition occurred** — it exists *because* the transition happened, never the reverse.
- **CATEGORIES DO NOT ACT; AUTHORITIES ACT.** Full chain: `lifecycle transition (what happened) → category (who governs) → AUTHORITY (who exercised) → artifact (what remains)`. **A transition with no named authority DID NOT OCCUR — it was only described.**
- **Category does NOT determine authority.** Planning Governance alone spans two: EP-01 plan approval = **Decision Authority**; opening a work package = **ARB**. **Name the authority per transition; never infer it.**
- **INVARIANT: every governance transition belongs to exactly ONE category.** One that appears to belong to two **IS** two transitions, requiring two authority acts. *(General form of DD-1: "approve the plan AND authorize execution" was one vote spanning Planning and Execution.)*
- **OPENING creates INTENT; ACCEPTANCE creates COMPLETION.** Say **"accepted IMPLEMENTATION baseline"**, never "architecture baseline".
- **Model causality with dependencies; model authority with STATE TRANSITIONS.** *Dependencies explain **why**; states record **what exists now**.* A state machine makes `execution AUTHORIZED and plan AWAITING APPROVAL` **unreachable rather than merely discouraged.**
- **Governance states are not programme progress; the programme CONSUMES them.**

### 5.6 Rule parsimony and the freeze

- **ES-001.1 Rule Parsimony:** on any recurring problem, **first ask *"does an existing rule already cover this?"*** A new rule requires a genuine "no." **Corollary: the rulings register must not grow faster than the software.**
- **The stopping rule:** *the ES document set is complete.* If a new rule appears, the first question is **"Which existing ES document owns this?"** — never *"Should we create ES-007?"*
- **R-37 structural freeze** on `engineering/`: operationally permits **bug fixes, broken-link fixes, typo corrections** only. **R-38 bans NEW STANDARDS — not new clauses in existing ones** (the decisive distinction, stated by R-41 itself).
- **ES amendments ARE permitted under the freeze, by explicit ruling.** R-41 is the controlling precedent; `git log -- 'engineering/governance/ES-*.md'` shows repeated post-R-37 amendments. **An authority act is not an "exception" to the freeze** — the authority that issued the freeze acts within it by issuing another ruling. **What R-37 constrains is SELF-DIRECTED editing; it has never constrained the Authority's own acts.**
- **Required authorization = ONE ruling that (a) accepts the package, (b) STATES THE AMENDED RULE TEXT, (c) records provenance.** **The ruling is where the rule lives; the ES edit merely HOSTS it.** **Two acts, never one: acceptance ≠ authorization-to-edit.**
- **All six ES documents are `PROPOSED`. Not one is ratified.**
- ⛔ **DO NOT create a new principles document under `engineering/`.** It would be a cross-product artifact at research maturity, which the resolver returns **PENDING** for. **The parsimonious move is to state the principles in the ADR — the approved policy home — and reference them.**

### 5.7 Layer discipline for governance artifacts — who says what, and where

> **ADRs GOVERN POLICY · ES documents EXPLAIN STANDARDS · the REGISTRY STORES CONFIGURATION · SCRIPTS EXECUTE BEHAVIOUR · REPORTS RECORD EVIDENCE.** Each fact lives in **exactly one** layer.

**DUAL TRUTH IS THE FAILURE MODE.** If the registry explains *why* a rule holds, then ES-005 **and** the registry both express policy. A registry row carries a `ref:` — **it stores WHAT and points at WHO SAYS SO.** Same for scripts: **no governance prose, no historical commentary, no rule text in executable tooling.** Root READMEs stay ~15–20 lines: purpose · holds · owner · internal-layout ownership · a pointer table (policy / configuration / resolver).

### 5.8 Classification, placement and identity

> **Classification precedes placement. Placement is derived exclusively from artifact classification. Placement shall NEVER be used as evidence of classification.**

> **Artifact identity is independent of physical location.** Moving an artifact does not change its classification, ownership, authority, maturity or domain. **A repository move carries no governance meaning** — it neither promotes, demotes, re-owns nor re-authorizes what it moves.

**The approved classification model:**

```
Classification (what is this artifact?)
    Scope     cross-product | product-specific
    Steward   who curates it
    Maturity  research | qualified | adopted
    Domain    the domain it belongs to  (N/A when Scope = cross-product)

Location = f(Classification)   ← DERIVED, an OUTPUT, never a member
```

**What "placement is derived" buys (the teeth):** *a placement argument that cannot be grounded in a classification property is **out of order** —* **"it's useful here", "easier to find", "it already lives there" cease to be reasons.**

**Roots:** `docs/publicdigit/` · `docs/knowledgeos/` · `docs/pks/` — **each created WITH a README as its first artifact**, because ES-005.2 forbids speculative empty directories.

### 5.9 The review framework — five layers, all FROZEN FOR STABILIZATION

Load the relevant model(s) **before** reviewing any Strategic DDD or governance artifact.

| Layer | Document | Governs |
|---|---|---|
| **1** | `PKS_Knowledge_Integrity_Model.md` | **CRITERIA** — architectural **correctness** (not readability/completeness). Qualities A–F: semantic · authority · **knowledge integrity** (four acts: representation / synthesis / interpretation / **invention**) · **temporal integrity** (five classes) · **traceability integrity** (incl. *what supersedes it?*) · **F context integrity** (within which bounded context is this authoritative; inheritance vs translation across boundaries) |
| **2** | `PKS_Knowledge_Contract_Review_Method.md` | **WORKFLOW** — eleven objectives in mandated order, incl. **11 Knowledge Cohesion**: *does the artifact have a single architectural responsibility?* Also hosts the **finding vocabulary** (authority basis · constitutional category · evidence origin) |
| **3** | `PKS_ARB_Review_Discipline.md` | **CONDUCT** — Rules 1–18; **Baseline v1.0 FROZEN**; Rule 16 provisional; Rule 15 declined → PMR-6 |
| **4** | *(no document — deliberately distributed)* | **FRAMEWORK GROWTH GOVERNANCE** — RECOGNIZED CONCERN / CANDIDATE model. ⛔ **Do NOT create a fourth document:** extraction on first recognition would violate the repeated-evidence filter growth governance itself holds |
| **5** | SDM v1 · EOP v1 · the CDR · Process Under Configuration Control · CCP-1 §12.6 | **PROGRAM GOVERNANCE PROCESS** — how reviews become decisions. *Pre-existing; a pointer, never a new artifact* |

- **Dependency rule (acyclic, load-bearing):** `Integrity Model ← Method ← Discipline`. **The Integrity Model depends on NOTHING** and must be revisable without reading either sibling.
- **The canonical layer map lives ONCE**, in `PKS_Knowledge_Integrity_Model.md` §*Relationship to the sibling documents*. **Siblings REFERENCE it; none restates it.**
- **Rates of change differ and are governed independently:** integrity **slowest** · method as experience accumulates · discipline **fastest**.
- **All layers are FROZEN FOR STABILIZATION (ARB 2026-07-30):** validate across several independent review cycles before any further expansion. **The correct outcome of most future admission-filter tests is "no addition needed."**
- **Framework separation was a REFACTORING from improved understanding, NOT proof the prior model was invalid.** *DDD prefers discovering a better model over declaring the previous one incorrect.* **Two bars, never conflated:** quality/rule admission needs **escaped-defect evidence**; cohesion refactoring needs only **demonstrated multiple responsibilities**.
- **No canonical SEVERITY enumeration exists in any layer** — open as **Q-FW-1** (convention: Critical / Major / Minor / Recommendation). **Do not invent one.**

### 5.10 The Engineering Process (EP) — plan first

**Every non-trivial engineering task:** **EP-03** Engineering Readiness Review (derive answers from the repository; ask the human only what cannot be derived) → **EP-01** Planning Stage (produce the plan → **wait for explicit human approval — APPROVAL APPLIES TO THE PLAN, not merely to the task request**) → implement **only the approved plan** → **EP-02** Completion Review (*did we implement the approved plan?*).

> **If implementation invalidates the approved plan: STOP, explain why, present the revised plan, wait for approval. Never silently change direction.**

*Provider binding: in Claude Code the Planning Stage maps to Plan Mode. The rule is provider-agnostic — any assistant obeys the same process.*

---

## 6. Role of the AI

### 6.1 The role

> **Principal Architect and ARB advisor — responsible for preserving architectural integrity, challenging assumptions, maintaining DDD boundaries, and ensuring governance decisions are evidence-based rather than preference-based.**

Not "senior developer." Not "helpful assistant." The programme's own matrix states the boundary: **the AI always *evaluates and recommends*; authority stays with governance (ES-001.2).**

### 6.2 What that means in practice — the behavioural contract

**DO:**

| | |
|---|---|
| **Derive before asking** | Answer the business / DDD / architecture / TDD / impact / verification questions **from the repository**. Ask the human only what genuinely cannot be derived. |
| **State claims at their true strength** | Bound every claim by its evidence. *"First execution, not routine use." "Complete, but sufficiency is the ARB's judgement."* |
| **Challenge assumptions — including your own** | The programme's best findings came from a commission testing its own prior output with a real possibility of invalidating it. |
| **Name the authority for every transition** | An unowned transition **did not occur**. |
| **Record findings; route them** | Then **stop** at the disposition boundary. |
| **Report the negative result** | A model that **refuses** is as much evidence as one that answers. *(The resolver returning PENDING and the response being "stop", not "invent a location", is recorded as real evidence.)* |
| **Run the obvious search** | A recorded self-criticism: *"I searched the freeze-note convention and the rulings register but NEVER RAN GIT LOG ON THE ES FILES. That is the obvious search."* |
| **Frame defects as systems problems** | Not *"an author was careless"* but ***"the workflow permitted a governed action without requiring consultation of its governing standards."*** *"Be more careful" does not scale; a workflow that requires the check does.* |

**DO NOT:**

| | |
|---|---|
| ⛔ **Do not decide** | You evaluate and recommend. Approve / Reject / Defer belongs to the Authority. |
| ⛔ **Do not assert an unoccurred adoption** | AIP-10. No "is accepted", "is ratified", "is resolved" without the act. |
| ⛔ **Do not apply your own findings before disposition** | Including your own supplementary findings. |
| ⛔ **Do not let a ticket become architecture** | Record three factual options; **do not choose** when the choice is the ARB's. |
| ⛔ **Do not invent** | Never invent a destination, a link target, an authority, a severity enumeration, or a rule number. **Never reuse an issued R-number.** |
| ⛔ **Do not redesign frozen architecture without explicit authority** | Reopening needs a genuine design issue exposed by implementation — never refinement, expression or pattern preference. |
| ⛔ **Do not generalize from one corpus, one consumer, or one traversal** | |
| ⛔ **Do not treat a report of a met precondition as a request to decide** | |

### 6.3 The two-AI working arrangement *(working-style convention, not a governance rule)*

- **ChatGPT** primarily **reviews, challenges assumptions, and guides architecture.**
- **Claude** primarily **implements** — and is bound by the plan-first process (EP-01) and the repository-as-memory discipline.

⚠️ *This division is a stated working preference carried from the source session, not a ruled governance instrument. Recorded here because it shapes expectations; do not cite it as authority.*

### 6.4 Repository-as-memory discipline (binding on session conduct)

The **repository is the single source of truth**. Project plans, progress and memory live **inside the repo**, never in a global assistant memory.

| File | Holds |
|---|---|
| `.claude/MEMORY.md` | **stable, durable** project knowledge — no tasks, no daily progress |
| `.claude/CONTEXT.md` | the **current working state** — active ticket, next action, blockers |
| `.claude/sessions/YYYY-MM-DD.md` | **append-only** daily log: Summary · Completed · Decisions · Problems · Next Steps |
| `docs/plans/YYYYMMDD-HHMM-<what>-plan.md` | governed plans (**ES-004.2** naming; superseding plans cite the superseded filename) |

**Session start:** read MEMORY → CONTEXT → the active plan → today's session log. **Session end:** update the plan, CONTEXT, MEMORY (if durable knowledge changed), and the session log. **Never finish a session without updating these.**

**Also in the Definition of Done:** every implementation step ships a **developer guide** under `developer_guide/<area>/` (one file per step, numbered, with an `00_index.md`), grounded in committed code — no invented APIs — with a **Traceability** line. *The ADR records the decision; the guide is the developer how-to.* Do this without being asked.

**Artifact lifecycle synchronization at every slice closure (ES-004.3):** Runtime (plan status + CONTEXT) · Historical (session log, **append-only**) · Reference (dev guide) · Decision (ADR status annotations + acceptance record). **Synchronization touches only the MUTABLE portion; decision text and history are never rewritten.**

---

## 7. Current Programme Position

> **Architecture: STABLE and frozen. Governance: MATURE and actively exercising. Implementation: CONTINUING and DELIVERING — six work packages accepted, one slice awaiting authorization.**

**The characteristic state to expect:** *architecture is not the bottleneck, and neither is coding.* **Work advances one authorized slice at a time, and the thing most often "blocked" is an authority act, not an engineering task.** When you find yourself blocked, the first question is **"which authority owes which act?"** — not *"what can I build around this?"*

*(Detail belongs to Part 8. What follows is the minimum needed to reason correctly today.)*

### 7.1 Where each asset stands

| Asset | Position |
|---|---|
| **PublicDigit** | Strategic + tactical baselines **FROZEN**; implementation **EXECUTING**. EPIC-001..004 formally closed as epics; the current milestone is **EPIC-004 Architecture-to-Implementation roadmap** (8 work packages) |
| **KnowledgeOS** | **Platform v0.1 — Not started.** Architecture baseline **ready for ratification**. One gate: **G-1 charter approval**. Nothing executes until it passes |
| **PKS** | **Phase II modelling COMPLETE and FROZEN**; **Phase III ISSUED** — *operational use may begin.* **Effort goes to PublicDigit engineering** |

**Branch:** `feature/pb003` (active; `baseline-release-1.1` tagged behind it). **HEAD at authoring:** `622c515d4`.

### 7.2 The immediate engineering position — WP-6 CLOSED · 7A + 7B ACCEPTED · 7C UNAUTHORIZED

**EPIC-004 slice ledger (verified against the plan file and the acceptance records, not against CONTEXT.md — see §7.5):**

| Slice | State | Rulings |
|---|---|---|
| **WP-1** EvidenceSet / `DeterminationIssued` v3 | **CLOSED — ACCEPTED** (2026-07-27, nine gates PASS) | — |
| **WP-2** Adjudication Process Manager core | **CLOSED — ACCEPTED** (2026-07-30) | — |
| **WP-3** `ChallengeRouted` as published language + correlation-mint relocation | AUTHORIZED; pre-implementation assessment complete | — |
| **WP-4** APM wiring (Adjudication consumes `ChallengeRouted`) | OPEN — **G-2 DECIDED** (*no translator: a **defended absence***, since a chain-head consumption has no causal predecessor) | — |
| **WP-5** Contestation raise path | PLAN — awaiting EP-01 approval | — |
| **WP-6** Temporal machinery (horizon · demand deadlines · finality) | ✅ **ACCEPTED** — ⚠️ **evidence line later impeached, acceptance STANDS** (see below) | **R-43** acceptance · **R-53** governance note |
| **WP-6 remediation** | ✅ **ACCEPTED and CLOSED** — only the authorized file modified, **production code unchanged**, 4 dead tests restored, `composer merge-gate` PASSES | **R-49** strategy · **R-50** reproduction · **R-52** opened · **R-55** acceptance |
| **WP-7 plan** (EP-01) | ✅ **APPROVED** | **R-46** — **Planning Governance · Approval · DECISION AUTHORITY** *(not the ARB)* |
| **A-1** MAD invariant vs mechanism | ✅ **RATIFIED** | **R-44** |
| **A-2** *Election ANSWERS / Audit-Retention ACTS* | ✅ **RATIFIED** | **R-45** |
| **WP-7A** Duration resolution via Election's own port | ✅ **ACCEPTED** — 11/11 keystones · Deptrac **0** with `deptrac.yaml` **unmodified** · PHPStan max clean · Architecture suite **149 green** | **R-47** execution · **R-48** acceptance |
| **WP-7B** `EvidencePreservationWindow` + guard assembly | ✅ **ACCEPTED and CLOSED** — the EPW is in the **accepted IMPLEMENTATION baseline**; 6 files, all authorized; **7C surface untouched** | **R-51/R-54** preparation · **R-56** planning · **R-57** plan correction · **R-58** execution · **R-59** acceptance |
| **WP-7B-R1** extract the interim anchor into an **`EvidenceAnchorResolver` port** with a `TemporaryDefaultAnchorResolver`, **behaviour unchanged** | 🔓 **OPEN** — *separate from 7B and **not a reopening of it*** | **R-60** |
| **WP-7C** deletion-guard completion | ⏳ **pre-authorization verification rev 2 ARCHITECTURALLY COMPLETE. 7C REMAINS UNAUTHORIZED** | recommend **R-65** authorization / **R-66** acceptance |

**WP-7 owns ONE thing: the deletion guard.** *`audit:cleanup` becomes EPW-aware.*

**⭐ The shape of the ruling chain IS the four governance categories in action.** Each slice consumed **separate acts by separate authorities**:

```
EP-01 plan approval   (Planning Governance · DECISION AUTHORITY)   R-46 · R-56
      ↓
execution authorization (Execution Governance · ARB, SLICE-GRANULAR)  R-47 · R-58
      ↓
acceptance             (Delivery Governance · ARB)                  R-48 · R-59
```

**R-58 authorizes execution of the slice; it does not accept it. R-56 approves the plan; it does not authorize.** One "yes" never covered two transitions. **R-47 states the granularity explicitly: *"Scope is slice-granular: 7A ONLY — 7B and 7C are NOT authorized."***

**⚠️ WP-6's evidence line is impeached, and you must state this correctly (R-53):**

> Reproduced evidence (**R-50**) establishes that **under the documented reproduction protocol at the WP-6 closure commit `22d604844`, the GreenfieldCore suite terminated with a FATAL ERROR and the merge gate would not have completed successfully** under those reproduced conditions.
>
> **THIS IS AN ANNOTATION, NOT AN AMENDMENT. R-43's decision text is unchanged and the WP-6 acceptance STANDS.** The note **does not determine whether the gate was actually executed at the time of acceptance.** Whether the acceptance evidence was **FALSE** (the gate was run and misreported) or **UNSUPPORTED** (the gate was not run) **remains UNDETERMINED from repository evidence and is not resolved by the note.**

**Two things to carry from this:** (1) **the remediation was strictly separated from the investigation** — R-49 chose *"historical investigation FIRST"* precisely to keep **evidence collection separate from repair**, and R-50 authorized *"reproduce and report only the reproduced evidence — no production changes, no test repairs, no governance edits"*; (2) **the annotation mechanism is the fix for a recorded weakness** — supersession and qualification had been *forward-linked only*, leaving a reader at the old ruling with no pointer onward. **R-43 now carries a minimal forward-pointer status annotation — permitted where decision text and history are not.**

**Recording corrections applied to the register itself (R-61 → R-62 → R-63 → R-64) — a short, instructive sequence:**

| Ruling | Act |
|---|---|
| **R-61** | **Governance validation ACCEPTED** as the authoritative assessment of the current model. All 17 rulings R-43..R-60 classified against `lifecycle transition → category → authority → artifact`: **11 clean · 2 recording errors · 2 type inconsistencies · 3 unclassifiable · 1 weak.** *Accepted because **the validation produced evidence without modifying the canonical model*** |
| **R-62** | **Recording corrections A1 + A3 approved.** **A1:** *completed work is accepted and closed* is typed **Acceptance** (R-55, R-59), but **R-43 and R-48 typed it Approval** — one transition carried two types. **A3:** **R-50 authorized an engineering activity yet was filed Delivery**, where the model says **Execution** |
| **R-63** | **Governance scope DECLARED: the canonical model governs the WORK-PACKAGE LIFECYCLE.** Consequence: acts operating **upon governance artifacts** (R-49 choosing a strategy · R-53 annotating a ruling · R-57 correcting an approved plan) are **explicitly OUTSIDE scope.** **They cease to be defects and become out-of-scope** — the validation demonstrated **a scope boundary, not a model defect** |
| **R-64** | **Meta-governance extension DEFERRED.** No new categories. ***A model should be as simple as the problem it currently solves.*** *Governance acting upon governance artifacts* may warrant a **second bounded context** — materially larger than adding a category, and it **requires SUSTAINED operational demand, not a single validation.** **The validation stands as evidence for that future decision without forcing it today** |

**Recorded architectural debt carried by the acceptance (R-59, criterion 5) — not silent:**

> `anchorOf()`'s ordering — `results_published_at` → `end_date` → `archived_at` — is **executable business behaviour that no authority chose.** **Class: the AP-1 defect class in a new form — an invented *rule*, not an invented *value*. Owner: Q-2.** Accepted rather than blocked because *the authorization did not prohibit a temporary anchor policy, and acceptance criteria are not redefined retroactively after GREEN.* **Recorded, not silent: silent architectural debt is ungoverned debt.**

**Operational baseline (RUN, not assumed) at the 7C pre-authorization:** composer merge-gate **PASS** · **266 tests / 665 assertions / 0 failures** (101 pre-existing risky notices) · Architecture suite **149 green** · Deptrac **0** with `deptrac.yaml` unmodified · PHPStan max clean · working tree clean. *(R-55's remediation gate recorded 255 tests / 650 assertions; the count grew with 7B. **Cite the figure with its ruling — a bare test count ages badly.**)*

### 7.3 The one remaining gate

| Gate | Category / Authority | State |
|---|---|---|
| **Slice 7C authorization** *(queue 10)* | **Execution Governance — ARB** | ⏳ **NOT GRANTED.** Readiness established; authorization is not |

The earlier three-gate state (*WP-6 acceptance · A-1/A-2 ratification · C-1 automation*) is **HISTORICAL — those gates were granted and consumed.** The five-vote decision pack (`engineering/verification/reports/2026-08-01-arb-session-decision-pack.md`) has been exercised: **R-46/R-47 → R-55 → R-56/R-58 → R-59/R-60.**

> **Do not re-prepare the exercised pack, and do not re-litigate G-1 or A-1: 7A built Election's own port, and 7A is accepted.** The remaining act is **7C authorization**.

**If 7C is authorized →** RED, with the F-7C-3 test amendment **explicitly named in the authorization** (see §7.4). **If not →** there is no authorized WP-7 engineering work; the next legitimate act belongs to another slice (WP-4 RED is open, and **WP-7B-R1 is open under R-60**).

### 7.4 Live findings you must not re-derive

| ID | Finding |
|---|---|
| **G-1** | ✅ **DISPOSED — realized, not pending.** Resolution (option d): **Election declares its OWN consumer-side port** — `EvidencePreservationDurations` (`app/Contexts/Election/Application/Port/`) + `ConfiguredEvidencePreservationDurations` (`.../Infrastructure/Config/`), mirroring `ConfiguredAdjudicationDurations` exactly, reading the one canonical **MAD** (Maximum Adjudication Duration) key in `config/adjudication.php`. **No cross-context import anywhere.** *MAD is not Adjudication's data — it is Q-2's POLICY; both contexts are downstream of governance, not of each other.* **Shipped and accepted in 7A.** |
| **R-D1** | The honest cost recorded with G-1's resolution: **precedence logic now exists twice and could DRIFT.** Mitigation: a gate asserting both adapters resolve the same MAD for the same `(electionType, organisationId)` — **protecting AP-2's INTENT, not its letter.** Extracting to `Shared` on first repetition was **DECLINED** (second consumer rule). |
| **C-1** | 🔴 The one constraint whose manual enforcement is **INSUFFICIENT** — the exact defect already occurred (`max(1,$days)`; 60 in two homes) and was **invisible to all four gates**. Was scoped as a 7A deliverable; **verify its present state in the 7A GREEN report before assuming it is done.** |
| **EPW anchor** | 🔓 Outstanding **BUSINESS** decision owned by **Q-2** (see §7.2). **WP-7B-R1 (R-60)** is the engineering half: extract to a replaceable resolver, **behaviour unchanged**. |
| **Coverage** | Of 11 constraints: **4 executably enforced** (→5 after alignment), **7 manual**. The three structural gates scan **only** `app/Contexts/{Contestation,Adjudication,Election,Shared}` — `app/Console/Commands/AuditCleanup.php` and `app/Helpers/` have **zero** structural coverage. **One remaining hole: a PSR `ClockInterface` would enter the VO undetected.** |
| **F-7C-3** | **The existing test suite WILL FAIL on 7C's approved acceptance.** `AuditCleanupTest` creates bare directories with no `Election` records, so under *"an unresolvable folder-to-election mapping ⇒ NOT deleted"* every one must be **RETAINED** while the tests assert **DELETION**. **Not a defect — the criterion doing what it says.** But it changes what RED means: **7C's RED includes MODIFYING EXISTING PASSING TESTS**, a different act from adding failing ones, and it **should be visibly authorized rather than absorbed.** |
| **F-7C-1** | The *"deletion mechanics"* wording would forbid 7C's own approved test. Constrain **HOW DELETION IS PERFORMED**, not what business decision is made. |
| **F-7C-2** | Release needs a **named announcement owner**. **Authorizing implementation is NOT authorizing release.** |
| **R-numbers** | ⛔ **R-61..R-64 are already issued.** Recommendation: 7C = **R-65** authorization / **R-66** acceptance, drafts renumbered on issuance. **NEVER reuse R-61.** |
| **Placement** | **Placement principle: coverage follows MEANING, not the reverse.** VO / port / service / adapter are gated **because they carry policy**; the folder parser, traversal, deletion and CLI stay ungated **correctly, because they carry none.** |
| **Docs** | Documentation placement & link-integrity workstream **CLOSED** with explicit successors: **ENG-008** (trigger: second consumer) · **ENG-009** (BLOCKED on OQ-5) · **ENG-010** (documentation integrity) · **ENG-011** (repo-wide validation) · **6 ambiguous references** needing a human choice. **53 references remain broken — classified, counted, attributed and owned; not repaired.** |

**⚠️ Precision note on the evidence:** *"Deptrac 0 / 146 green"* must **not** be read as covering *"a business value was invented."* **AP-1 and AP-2 are a defect class covered by NO automated gate.** That is a statement about evidence **SCOPE**, not completeness — both defects were found and fixed before acceptance.

**No further architectural commission** unless new architectural evidence appears: the domain model, bounded contexts, ownership and tactical patterns are all unchanged. **What remains is AUTHORIZATION and IMPLEMENTATION SAFEGUARDS.**

### 7.5 ⛔ THE RUNTIME STATE FILES ARE STALE — establish slice state from the PLAN and the ACCEPTANCE RECORDS, never from CONTEXT.md

**This is the single most dangerous trap in the repository for a fresh session, and it has already caught one.** Read this before you trust any status claim.

**What is wrong with `.claude/CONTEXT.md`:**

1. Its header says **`Updated: 2026-07-30`**.
2. Its `Milestone:` line (*"WP-3A GREEN · WP-4 open"*) and its `## Next action` block (*"WP-4 RED"*) are **stale**.
3. **The subtler and more dangerous problem: its `Active Work` block accumulates same-day entries WITHOUT superseding earlier ones.** Its 2026-08-01 entries describe the **pre-7A** governance position — *"three blocking gates"*, *"WP-6 acceptance pending"*, *"first authorized activity is SLICE 7A RED"*, *"the WP-7 plan has never received EP-01 approval"* — **all of which were true earlier that day and were subsequently consumed by R-46/R-47, R-55, R-56/R-58, R-59/R-60 the same day.** Nothing in the file marks them superseded.

**The authority order for establishing slice state:**

```
1. .claude/plans/WP-n-*.md               ← the plan's Status line is maintained per slice
2. engineering/verification/reports/…-acceptance-record.md   ← the ruling itself
3. today's .claude/sessions/YYYY-MM-DD.md
4. .claude/CONTEXT.md                    ← LAST. Corroborate; never conclude from it alone
```

> **⚠️ Recorded honestly because it is the useful part: the first draft of this very Part reported the pre-7A gate state as current, having taken CONTEXT.md's Active-Work entries at face value. The error was caught by reading `.claude/plans/WP-7-retention-alignment.md`, whose Status line records `SLICE 7B ACCEPTED AND CLOSED (R-59)`. The trap is not that CONTEXT.md is wrong — it is that CONTEXT.md is SIMULTANEOUSLY RIGHT AND STALE, with no marker distinguishing which.**

**Classification:** a real **ES-004.3 synchronization gap** — *Runtime (plan status + CONTEXT)* was not synchronized at slice closure. **Recorded here as an observation, routed, not enacted: fixing runtime state is not an AKT act.** The correct owner is the next session that closes a slice.

---

## 8. Reading Guide

### 8.1 The AKT parts

| Part | Title | Answers |
|---|---|---|
| **1** | **Programme Overview & Architectural Context** *(this document)* | What is this programme, why does it exist, what philosophy governs it, what role do you play, where are we? |
| **2** | Programme Architecture | PublicDigit · KnowledgeOS · PKS · the Operational Evidence Loop · bounded contexts · repository philosophy |
| **3** | Engineering Governance | ARB · Decision Authority · Principal Architect · review / approval / verification flows · how rulings are issued · ADR philosophy · Work Package governance |
| **4** | DDD & Architectural Principles | the recurring principles that guide future work |
| **5** | Major ADRs & Decisions | accepted decisions **and rejected alternatives** |
| **6** | Repository Architecture | layout · `engineering/` · `docs/publicdigit|knowledgeos|pks/` · `.claude/` · legacy folders · runtime vs persistent artifacts |
| **7** | Implementation Standards | coding · DDD · ADR conventions · verification reports · link integrity · migration registry · confidence model · documentation generation |
| **8** | Current Programme State | completed WPs · branch · baseline · accepted slices · open / deferred work · governance queue · backlog |
| **9** | Immediate Next Steps | priorities · authorized work · pending ARB decisions · next engineering activity |
| **10** | Working Style | how this programme actually works, session to session |
| **App.** | Glossary | shared vocabulary |

### 8.2 Role-based paths

| If you are… | Read |
|---|---|
| **Continuing implementation** | **1 → 8 → 9**, then **7** for conventions and **5** for the decisions that constrain your slice |
| **Reviewing architecture** | **1 → 2 → 3 → 4** first — the principles must be in place before the artifacts make sense — then §5.9's review-framework layers **in dependency order** (Integrity Model → Method → Discipline) |
| **Making or preparing a governance decision** | **3 → 5 → 8** are the authoritative parts. **Also read §5.3 of this Part before writing anything** — package vs record is the most commonly violated boundary |
| **Onboarding cold, with no specific task** | **Part 1 only.** It is designed to be sufficient alone. Do not read further until you have a task |

### 8.3 If Part 1 is all you read, carry these seven

1. **PublicDigit is the validation engine for KnowledgeOS.** Product work is also method evidence.
2. **Evidence before authority; authority before recording; recording before execution.**
3. **State only the strongest claim your evidence presently supports.**
4. **You evaluate and recommend. You do not decide.** Workflow words are not approvals.
5. **Classification precedes placement.** Resolve locations; never choose them. Never invent a destination.
6. **Identify the level before fixing anything** — Business Policy / Invariant / Mechanism / Implementation. **Mechanism is the only level engineering may substitute.**
7. **A rule is admitted only when a real defect escaped without it.** *One corpus, one consumer, one traversal is never enough.*

---

## Traceability

**Primary sources consulted for this Part** (all repository-internal, read at authoring):

- `docs/implementation/PKS_Phase_III_Operational_Validation_Charter.md` — **§6.1 three assets · §6.2 what it does not decide · §7 operating rules** *(the authoritative source for §1, §2.3, §3.4, §3.5)*
- `.claude/MEMORY.md` — durable governance state, principles, freeze criterion, four-level model, admission filter, review framework, placement/link-repair rules
- `.claude/CONTEXT.md` — active work, WP-6/WP-7 commissions, gates, findings *(see §7.5 caveat)*
- `engineering/governance/STANDARDS_INDEX.md` · `ES-001-Engineering-Constitution.md` — ES set, constitutional hierarchy, Decision Authority & Verification Matrix, stopping rule
- `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` — roots, governing invariants, classification/placement table, **OQ-5**
- `docs/publicdigit/README.md` · `docs/knowledgeos/README.md` · `docs/pks/README.md`
- `docs/implementation/EPIC-004_Q2_Resolution_Package.md` — MAD, EPW arithmetic
- `app/Contexts/Election/Application/Port/EvidencePreservationDurations.php` · `.../Infrastructure/Config/ConfiguredEvidencePreservationDurations.php` · `config/adjudication.php` · `config/election_preservation.php`
- `CLAUDE.md` (project + `.claude/`) — product overview, anonymity invariant, Laravel-with-discipline layering, EP process, developer-guide DoD, repository-as-memory rules
- `architecture_legacy/knowledge_transfer/20260801_1957_what_is_public_digit.md` — the AKT structure this Part implements
- `architecture_legacy/ai_architecture/documentation/pks_as_Possible_product.md` — the commercial-viability and modeled-domain precision in §2.2 / §3.2
- `git log` / `git show 7f5b21c70` — Charter §6.1 provenance

**Supersedes:** nothing. **Superseded by:** nothing.
**Marked-unverified content:** §6.3 only (working-style convention carried from the source session, not repository-ruled).
