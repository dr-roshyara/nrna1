# KnowledgeOS Reference Architecture v1.1 — Second HPA Review & Semantic Invariance Refinement

> **Instrument class:** **architect-side second architectural review**, commissioned by the HPA (2026-08-22). It **recommends; it does not accept** (R-34 — engineering supplies evidence and never accepts its own work). The HPA's verdict *on this review* is a separate act. *(Naming note: the 2026-08-22 14:25 instrument is the record of the HPA's own verdict; this one is the architect's review the HPA commissioned. The commissioned title is kept verbatim; the authorship distinction is recorded here so the chain does not read a second HPA act where none occurred.)*
> **Object under review:** `docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md` (**r2**, PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE).
> **Prior act:** `docs/knowledgeos/reviews/20260822-1425-…-DDD-Refinement-HPA-Review.md` — **PASS CONDITIONALLY**; the one condition (aggregate-boundary altitude) **APPLIED** in r2, **awaiting HPA confirmation** to close.
> **Default assumption (commissioned):** *v1.1 is structurally sound unless evidence demonstrates otherwise.*
> **Verdict:** ✅ **PASS — CLARIFICATION ONLY.** No structural change. **v1.1 survives the new evidence.** The semantic-invariance family is **prepared** as Logical-Architecture mechanisms behind a named port contract; it is **not admitted** into the core.
> **Status:** ⭐ PROPOSED · NON-AUTHORITATIVE. Register **25+4 unchanged** · Constitution v1.0 **FROZEN** (satisfied, never extended) · research closure **unchanged** · P4 gate **unchanged** · Semantic Compiler **still not promoted**.

---

## 0 · Chain position (and one procedural finding)

```
Constitution v1.0            FROZEN
        ↓
Reference Architecture v1.0  PROPOSED
        ↓
DDD Refinement v1.1          PRODUCED
        ↓
HPA review                   PASS CONDITIONALLY → condition APPLIED (r2)
        ↓                    ⏳ awaiting HPA confirmation of the applied wording
        ↓
★ SECOND ARCHITECTURAL REVIEW ← this instrument (semantic invariance · compiler boundary · DDD consistency)
        ↓
Logical Architecture         (Expression↔Meaning Port Contract · Trustworthy AI Engine, BV-8)
        ↓
Implementation Architecture → Systems
```

**PROCEDURAL FINDING (P-1) — the first review is still open.** The HPA review's condition was applied in r2 and that instrument records *"awaiting HPA confirmation of the applied wording to close the review."* This second review **does not close it and must not be read as closing it**. Two open review instruments over one artifact is the failure mode the chain has already named elsewhere today: *if every acceptance requires another review, authority never closes.* **Recommended order: confirm (or adjust) the r2 wording as its own one-line HPA act → then apply this review's clarifications as r3.** Nothing in this review depends on that confirmation; nothing in it substitutes for it.

---

## 1 · Review Executive Summary

**What actually changed in our understanding since the v1.1 review — and what did not.**

The commission cites two developments: the Semantic Compiler simulation and the Sanskrit/Pāṇinian invariance insight. **An evidence audit shows most of that material was already an input to v1.1, not a development after it** (§3.1). The compiler simulation's headline number (~74.2%) is the *stated reason* for the non-promotion ruling in v1.1 §7.3 and is cited as such in the HPA review's own traceability; the Sanskrit lens already received a specific architectural home (candidate mechanism at the Expression↔Meaning port) in v1.1 §7.3 / HPA review §1.9; the Semantic Normal Form source predates v1.1 by ~72 minutes. **Re-reviewing those is re-running a review that already happened** — which the commission itself forbids.

**Four things are genuinely new** (all post-14:25): the **false-acceptance finding** (~30%, 6/20 — a mechanism asserting understanding where grounds were insufficient); the **benchmark split** (semantic compilation vs epistemic validation measured separately); the **semantic non-collapse** dimension (invariance without non-collapse is a lossy hash); and the **boundary refinement** that epistemic validation is *enforcement at the boundary*, not the kernel.

**The decisive result of this review is a falsification test the first review did not run — Test F.** *If natural language disappeared entirely and knowledge arrived only as structured submissions, would KnowledgeOS still be KnowledgeOS?* **Yes.** Identity, evidence, justification, agency, contradiction, history and the lifecycle are untouched. Therefore **no mechanism whose entire reason for existing is natural language can be core** — which settles the commission's central question in one move: semantic invariance is a mechanism that **protects an input boundary around** the core, not a part of it.

**The one collision worth the whole review.** SNF produces *canonical forms*, and a canonical form is still a **representation**. Any use of SNF-equivalence to decide *"these two are the same knowledge object"* would derive identity from a representation and turn similarity into identity — a direct strike at **INV-KOS-IDENTITY-001** (Article 1.2, 1.3). v1.1 already forbids this; what is new is the concrete, mechanism-shaped route to violating it. That earns an explicit non-admission note, not a new invariant.

**Where v1.1 is genuinely thin.** v1.1 says *what a mechanism may not do*; it does not yet say *what a mechanism must declare* when it crosses the Expression↔Meaning port. The false-acceptance evidence is precisely a mechanism failing to declare insufficiency. That gap is at **Logical-Architecture altitude**, and this review's constructive output is to name the deliverable that closes it: the **Expression↔Meaning Port Contract** (§6.3) — six obligations, all of them renders of existing invariants, none of them new law.

**Net:** v1.1 loses nothing, gains no member, no context, no event, no invariant, and no version bump. It gains **five clarifications and one refinement** (§11), all as r3 annotations.

---

## 2 · Existing v1.1 decisions that remain valid

Re-tested, not re-derived. Each is affirmed against the new evidence; the citation is where the record already carries it.

| v1.1 decision | Re-test against new evidence | Ruling |
|---|---|---|
| **Core Domain = Knowledge Identity** (identity-bearing justified epistemic state + justified lifecycle) — §3.1, §3.3 | Tests C and D were already answered (remove identity → claim-store; remove lifecycle → snapshot-store). Test F (new) removes natural language itself and the core is unharmed — **corroboration, not challenge** | **KEEP — unchanged** |
| **Epistemic Evolution folded in** as the aggregate's lifecycle — §3.1 | The new evidence concerns intake, not lifecycle | **KEEP** |
| **Meaning Preservation folded in** as identity's defining facet — §3.1 | The new evidence sharpens *who preserves what*: the core preserves the identity of **admitted** meaning; mechanisms may propose meaning. The fold-in stands and is strengthened (§8) | **KEEP** |
| **Wisdom Formation rejected** — §3.1, §8 | Nothing in the new evidence is a wisdom claim | **KEEP REJECTED** |
| **KnowledgeAggregate as the primary aggregate**, 12 members — §4.1 | Every member survives the ownership test (§5); one member (Confidence) needs a boundary rule | **KEEP** (+1 refinement) |
| **Eleven articles rendered as eleven aggregate invariants** — §6 | Four invariants are *touched* by the new evidence; all four are **corroborated** — the evidence supplies the failure modes they already forbid (§9) | **KEEP — none weakened, none added** |
| **Aggregate boundary = the authoritative domain boundary at which constitutional admissibility of a state transition is determined** (r2 wording) — §1, §4.1, §6 | The new material's "enforcement at the boundary, not the kernel" refinement **converges** with this wording independently (§6.2) | **KEEP — corroborated** |
| **Semantic Compiler NOT promoted** — candidate adapter at the Expression port, Logical-Architecture stage — §7.3 | The new evidence *lowers* the promotion case (a measured false-acceptance rate is a reason for a contract, not for admission) | **KEEP NOT PROMOTED** |
| **LLM = Language Cortex**, external adapter, does not own truth — §7.2 | Test E already answered; unchanged | **KEEP** |
| **Zero = meta-principle**, realized through INV-KOS-UNKNOWN-001 — §7.4 | Gains a sharper *operational reading* (mechanism abstention), not a new rule | **KEEP** (+1 clarification) |
| **Implementation decisions deferred** (commands · methods · domain services · policy evaluation) — §4.1, §6 Altitude note | This review adds no implementation decision; the port contract is named as a *Logical-Architecture deliverable*, not authored here | **KEEP** |
| **Rejected set — "Not KnowledgeOS"** (language engine · database · chatbot · LLM wrapper · ontology repository · truth oracle) — §8 | The semantic-invariance family is exactly the pressure toward **language engine**; the refusal holds and is now load-bearing (§6.1) | **KEEP** |

---

## 3 · New evidence — graded

### 3.1 Evidence audit — what is actually new (finding N-0)

| Material | Timestamp | Available to v1.1 (14:02) / HPA review (14:25)? | Consequence for this review |
|---|---|---|---|
| Pāṇinian ↔ compiler architecture parallel | 14:06 | Yes (before the review) | **Not new evidence.** Already homed — v1.1 §7.3, HPA §1.9 |
| Semantic Compiler architecture extraction (lexer · parser · Semantic AST · KIR) | 14:09 | Yes | **Not new.** Already the "candidate adapter" ruling |
| CPU-only latency benchmark (~1–2 µs/sentence, tiny grammar; EN/DE condition drop) | 14:15 | Yes | **Not new.** Cited by the HPA as *feasibility of computation, not semantic adequacy* |
| Semantic Normal Form / word-order lens | **12:50** | Yes — **predates v1.1 itself** | **Not new.** A candidate mechanism, as recorded |
| ~74.2% semantic accuracy | in the record by 14:25 | Yes — **it is the stated reason for non-promotion** | **Not new.** Re-litigating it would reopen a settled ruling |
| **False acceptance ≈30% (6/20)** — asserting understanding without grounds | 14:32 | **No** | **NEW — the one with architectural bite** (§9) |
| **Benchmark split** — semantic compilation measured separately from epistemic validation | 14:32–14:34 | **No** | **NEW — supports a port contract, not a boundary move** |
| **Semantic non-collapse** as a required dimension | 14:34 | **No** | **NEW — completes "invariance" into a two-sided property** |
| **"Enforcement mechanism at the boundary, not the kernel"** | 14:34 | **No** | **NEW — converges with r2 wording (§6.2)** |
| Sequencing recommendation (architecture first; experiment as parallel evidence) | 14:38 | **No** | **NEW — procedural, not architectural (§13 next steps)** |

*Sources: `docs/knowledgeos/brainstorming/20260822-14{0622,0939,1513,3217,3421,3844}-*.md` and `20260822-125015-*.md` (main tree, commit `72fae34f`).*

### 3.2 Grading (measured · observed · architectural inference · hypothesis)

| Grade | Claim | Honest bound |
|---|---|---|
| **MEASURED** | Deterministic parse latency ~1–2 µs/sentence (one environment, tiny grammar, unreproduced) | Measures **rule-engine overhead**, not a semantic compiler. Says nothing about meaning |
| **MEASURED** | ~74.2% on a small self-authored case set; **6/20 false accepts** | Small-n, author-designed corpus, no independent construction. Direction, not magnitude |
| **OBSERVED** | German rule dropped the causal condition while English preserved it → the two were "approximately the same" but not semantically identical | A single grammar's gap; it demonstrates the **failure class**, not its frequency |
| **OBSERVED** | Failure classes cluster by layer (language · meaning · knowledge · epistemic · contradiction) | Suggestive of the decomposition's usefulness as an *experimental* model |
| **ARCHITECTURAL INFERENCE** | Expression→meaning and meaning→epistemic-state are separable responsibilities | Consistent with v1.1's existing port structure — **it changes nothing there** |
| **ARCHITECTURAL INFERENCE** | Performance ≠ semantic correctness; semantic compilation ≠ epistemic validation | Adopted as review reasoning |
| **HYPOTHESIS** | Semantic Normal Form · Semantic Invariance Layer · KOS-SCB v0.2 pipeline · Trustworthy AI Engine | Candidate mechanisms. **None promoted here** |
| **⛔ NOT EVIDENCE** | The cumulative accuracy projection (74.2% → 85 → 88 → 90 → 92 → 94 → **95%+**) and "expected 93–95%" | **Projection, explicitly excluded.** It is refuted *within its own source document* by the later assessment in the same file, and projecting accuracy is a forbidden action of the v1.1 commission. Not cited as support anywhere in this review |
| **⛔ NOT EVIDENCE** | The "80%" simulated v0.2 result | Self-described *"architectural simulation — directional evidence only… does not validate 95% accuracy"* |

**Composite-source warning.** `20260822-143217-*.md` concatenates three passes with **conflicting conclusions** (a projection, then its refutation). Within that file the **later assessment supersedes the earlier projection**. Any downstream citation must name which pass it cites.

---

## 4 · Semantic boundary review — Expression · Meaning · Identity · Epistemic State

The four levels, kept distinct, with the owner of each and the invariant that forbids the collapse.

```
EXPRESSION            many surface forms, any language, any word order
   │                  owner: Expression context (mechanisms, external)
   │  ── may vary freely; carries no epistemic weight ──
   ▼
MEANING CANDIDATE     a proposal about what an expression means (+ its justification path,
   │                  + its declared insufficiency)
   │                  owner: the mechanism that produced it — OUTSIDE the boundary
   │  ══ Expression↔Meaning Port · ACL (Article 1.2) ══   ← the contract of §6.3
   ▼
ADMITTED MEANING      the intensional content the core holds (aggregate member "Meaning")
   │                  owner: KnowledgeCore
   │  ── identity is ASSIGNED here, never derived (Article 1.1/1.3) ──
   ▼
KNOWLEDGE IDENTITY    KnowledgeId — stable across representation, expression, context and
   │                  projection change
   │  ══ Verification Port (Article 6) — the only admission path ══
   ▼
EPISTEMIC STATE       VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE
                      owner: KnowledgeCore, determined at the aggregate boundary
```

The commission's four tests, answered:

| Question | Answer | Where it is already settled |
|---|---|---|
| Can many expressions refer to one meaning? | **Yes** — that is why `MeaningTranslated` exists as an event with **KnowledgeId unchanged** | v1.1 §5 |
| Can meaning exist independently of a sentence? | **Yes** — Meaning is an intensional value object, not a string | v1.1 §4.1 |
| What makes two meanings the same knowledge object? | **An assignment, never a computation.** Not similarity, not observable match, not coextension — and (new) **not canonical-form equality** | INV-KOS-IDENTITY-001 + this review's finding N-1 |
| What makes an identity-bearing meaning part of KnowledgeOS? | Passage through the **Verification Port** with a preserved justification path | INV-KOS-VERIFICATION-001 |
| What determines the epistemic state? | The aggregate boundary — *constitutional admissibility of a state transition* | v1.1 §6 (r2 wording) |

**Has v1.1 conflated them? No.** All four levels are already distinct objects with distinct owners. **But one thing was implicit and is now made explicit:** the level *between* expression and admitted meaning — the **meaning candidate** — had no stated obligations. That is the whole of this review's substantive contribution.

---

## 5 · KnowledgeAggregate review — every member challenged

Test applied to each member: *does the aggregate **own** this, or was it placed here because it is useful?* Plus the ownership test (§14 of the commission) and the replacement test (§15).

| Member | Who owns the state? | Challenge | Ruling |
|---|---|---|---|
| **KnowledgeId** | Core | Could identity be derived from SNF equivalence classes? **No** — that is representation becoming identity | **KEEP — root** |
| **Meaning** | Core | Is this the *compiler's output*? **No.** The core owns **admitted** meaning; a mechanism's output is a *candidate* outside the boundary. Identity is identity *of meaning*, so the preservation target must sit inside | **KEEP** + clarification C-2 |
| **ContextTuple** | Core | Delimits the claim; without it the claim is incomplete (Article 1.4). Not a mechanism artifact | **KEEP** |
| **EvidenceLinks** | Core owns the **links**; external systems own the artifacts | If the aggregate held evidence *content* the core would drift toward the Ch IV **database** refusal | **KEEP as references** + clarification C-3 |
| **JustificationPath** | Core owns the **record**; engines own the **process** | Already split correctly in §3.2 (Reasoning MODIFY). The record is what makes admission auditable | **KEEP** |
| **Authority** (reference) | **Authority context** — referenced, never held | Correct as-is; holding it would let the core self-authorize (Article 3) | **KEEP as reference** |
| **Agency** | Core | Lineage, not a person-binding. The only member whose absence **rejects creation** | **KEEP** |
| **EpistemicState** | Core | The one thing no mechanism may ever produce — *"a semantic compiler may propose meaning; it may never manufacture epistemic status"* | **KEEP — and this is the load-bearing member** |
| **TemporalValidity** | Core | Freshness never truth; expiry never absence | **KEEP** |
| **Confidence** | **Ambiguous — the weakest member** | The new false-acceptance evidence names the hazard exactly: a 74.2%-accurate mechanism would naturally hand over "0.74". Article 2.3 forbids a scalar surrogate for epistemic structure. The member survives **only** as structured epistemic metadata **assigned inside the boundary** | **KEEP — with refinement R-1** (a mechanism-supplied score must never cross the port as confidence) |
| **History** | Core | Immutable, forward-only; revision never deletes | **KEEP** |
| **Relations** | Core owns the **links** | Must be **by KnowledgeId**, like ConflictRecord — never containment, or two aggregates couple transactionally | **KEEP** + clarification C-4 |

**Result: 12 KEEP, 0 removals, 0 relocations, 0 additions** — with one refinement (Confidence) and three clarifications (Meaning · EvidenceLinks · Relations). The aggregate was not a dumping ground; the one member that was arguably there "because it is useful" (Confidence) survives only once its boundary rule is stated.

**ConflictRecord and the three supporting aggregates** (AuthorityGrant · DerivedView · DecisionRecord) are untouched by the new evidence. **No change.**

---

## 6 · Semantic Compiler review — its exact architectural altitude

### 6.1 The altitude question, settled by Test F

The commission asks which of two models is architecturally truthful: semantic invariance **inside** the core, or **as a mechanism protecting a boundary around** it.

**Test F decides it.** Remove natural language entirely — knowledge arrives as structured submissions from instruments, forms, or authorized agents. What is lost? Nothing in the core: identity is still assigned, evidence still linked, justification still preserved, agency still recorded, contradictions still coexist, history still forward-only, states still first-class. What is lost is **reach** — the system's ability to accept human expression. Reach is valuable; it is not constitutive.

```
                      KNOWLEDGEOS CORE  (unchanged by Test F)
                 ┌──────────────────────────────────────┐
                 │  Identity · Admitted Meaning         │
                 │  Evidence · Justification · Agency   │
                 │  Context · Temporal · History        │
                 │  Contradiction · Epistemic State     │
                 └──────────────────┬───────────────────┘
                                    ▲
        ══════ Expression↔Meaning Port (ACL) ═══════════   ← where invariance lives
                                    │
        Semantic Compiler · SNF · Semantic Invariance Layer · LLM · dependency parser ·
        symbolic parser · human interpretation      (interchangeable — replacement test)
```

**Ruling: the second model. Semantic invariance is a mechanism that protects an input boundary around the core.** Both commission tests agree: the **ownership test** — SNF state, grammars, parse trees and canonical forms are owned by the compiler, never by KnowledgeOS; and the **replacement test** — a Pāṇinian compiler, a dependency parser, an LLM parser, a symbolic parser and a human reader can all feed the same port, and KnowledgeOS's identity is unchanged by the swap. A component that is freely interchangeable is not the core.

### 6.2 The "enforcement at the boundary, not the kernel" refinement — already true

The new material argues that epistemic validation machinery (there called *KOS-EV*) should not be called the KnowledgeOS kernel; it is an enforcement mechanism operating at that boundary. **v1.1 r2 already says exactly this** — *the aggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined*, and the concrete realization is deferred. Two independent derivations reaching the same wording is corroboration of the r2 condition, and a small piece of evidence that the r2 phrasing was the right call.

**GUARD (finding N-2 — naming hygiene).** *KOS-EV* and *KOS-SCB* are **benchmark names** in the evidence record. They must not become architectural components by repetition. The enforcement locus in the architecture is the **aggregate boundary**; a benchmark measures a mechanism against it. This is the standing anti-pattern: an observation must never silently become architecture.

### 6.3 The gap v1.1 leaves — and the deliverable that closes it

v1.1 states what a mechanism **may not do**. The false-acceptance evidence is a mechanism failing to state what it **could not do** — and silence at a port is indistinguishable from success unless the contract requires the declaration. **v1.1 forbids the outcome; it does not yet require the declaration.**

**Named deliverable for the Logical Architecture stage: the Expression↔Meaning Port Contract.** Its obligations are **renders of existing invariants** — no new law, no new register row, and it is **named here, not authored here**:

| # | Obligation on any mechanism crossing the port | Invariant it renders |
|---|---|---|
| 1 | Submits a **meaning candidate** only — never an epistemic state, never a verdict | INV-KOS-VERIFICATION-001 · Article 6.3 |
| 2 | Carries its **justification path** (premises · rules · assumptions · inference rule) — no black box | INV-KOS-VERIFICATION-001 (6.4) |
| 3 | **Declares its own insufficiency.** Abstention is a first-class output; "I did not understand" is a valid, expected answer | INV-KOS-UNKNOWN-001 |
| 4 | **Never proposes or derives a KnowledgeId** — and canonical-form equality is not an identity claim | INV-KOS-IDENTITY-001 |
| 5 | **Never emits a scalar in place of epistemic structure** (no accuracy-as-confidence) | INV-KOS-DIMENSION-001 · Article 2.3 |
| 6 | Mechanism **failure or silence maps to UNKNOWN** — never ABSENT, never FALSE | INV-KOS-UNKNOWN-001 |

Obligation 3 is the direct answer to the false-acceptance finding: a 30% false-accept rate is a mechanism with **no way to abstain**. The architectural fix is not a better parser — it is a port that will not accept a candidate lacking a declared insufficiency.

---

## 7 · Semantic Normal Form review — domain concept, mechanism, representation, port contract, or hypothesis?

| Candidate classification | Verdict | Reason |
|---|---|---|
| Domain concept | ❌ | The core already owns **Meaning**. SNF adds no domain responsibility; it encodes one |
| Bounded context | ❌ | Fails all five context tests (§8) |
| **Representation** | ✅ **primary** | A canonical form of an expression. v1.1 §7 places representations at the third altitude — *projections, never sources*, freely changeable (V.1) |
| **Mechanism** | ✅ **secondary** | The *normalization* that produces it is a transformation, at the mechanism altitude |
| Port-contract vocabulary | ⚠️ **candidate** | SNF *may* become the encoding in which candidates cross the port — a **Logical-Architecture** decision, not decided here |
| Kernel / constitutional concept | ❌ **explicitly not** | Consistent with the research record's own classification: *candidate mechanism, not kernel* |

### 7.1 Finding N-1 — the SNF ↔ identity collision (the review's central finding)

> **A canonical form is still a representation. Canonical-form equality is therefore a similarity claim wearing a formal hat — and similarity never becomes identity.**

```
E1  "The architect approved the design because security evidence was sufficient."
E2  "The design received approval from the architect after security evidence validation."
E3  "Der Architekt genehmigte den Entwurf."
            ↓ normalization
SNF(E1) = SNF(E2) = SNF(E3)?        ← a claim about REPRESENTATION
            ↓
⛔ "therefore the same knowledge object"   ← FORBIDDEN: identity derived from representation
✅ "therefore a candidate for the same admitted meaning, offered to an authorized
    identity-assignment act, which may accept or refuse it"
```

The prohibition already exists (INV-KOS-IDENTITY-001 renders Article 1.2 *and* 1.3). What is new is the **specific mechanism-shaped route** into it — and it is an attractive route, because a canonical form *feels* like meaning in a way an embedding does not. It therefore earns an explicit non-admission note at §7.3 rather than trust in the general rule.

**The two-sided property (finding N-3).** Invariance alone is insufficient. A normalizer that maps *approved* and *acknowledged* — or *sees* and *believes he sees* — to one form has not preserved meaning; it has destroyed a distinction. **Semantic invariance without semantic non-collapse is a lossy hash.** Any future experiment must measure both directions, and the negative families (near-synonyms that must **not** collapse) belong in the corpus from the start. This is why obligation 4 above is a prohibition on identity claims rather than a quality bar on the normalizer: the architecture does not need the normalizer to be perfect if it can never assign identity.

---

## 8 · Bounded context review — no new context

Five DDD tests applied to the candidates (Semantic Compiler · Semantic Invariance Layer · SNF · Expression · Meaning):

| Test | Semantic Compiler / SIL / SNF |
|---|---|
| Independent domain responsibility? | ❌ It serves identity preservation; it owns no domain question of its own |
| Independent ubiquitous language? | ⚠️ Partly (lexer · AST · KIR · normal form) — but that is **mechanism** vocabulary, and the **Expression** context already exists to hold it |
| Independent invariants? | ❌ Its constraints are renders of Articles 1.2 and 6 |
| Independent lifecycle? | ❌ It has no state that lives between requests; it transforms |
| Independent ownership? | ❌ Owned by whoever supplies the mechanism — external by construction |

**Ruling: NO CHANGE.** They are adapters inside the existing **Expression (GENERIC / EXTERNAL — mechanisms)** context, exactly where v1.1 §2.3 puts them. Creating a "Semantic" bounded context would give a mechanism the dignity of a domain — architecture by accumulation.

**Domain events — no addition (finding N-4).** `MeaningTranslated` already covers this ground and already carries the right guarantee: **KnowledgeId unchanged**. Candidate events such as *MeaningNormalized* or *SNFComputed* must **not** be admitted: nothing in the aggregate changes when a mechanism normalizes an expression, and an event that records no domain state transition is a technical log line, not a domain event. The `WisdomDerived` discipline applies unchanged.

---

## 9 · Constitutional invariant review — which are affected

**No article is added, weakened, reinterpreted, or extended.** Four of the eleven invariants are *touched*; all four are **corroborated** — the new evidence supplies concrete failure modes for prohibitions that already existed.

| Invariant | Touched how | Effect |
|---|---|---|
| **INV-KOS-IDENTITY-001** | SNF equivalence is a route to deriving identity from representation / similarity | **STRENGTHENED** — gains an explicit non-admission note (C-1). No wording change |
| **INV-KOS-VERIFICATION-001** | Mechanism candidates must arrive with a preserved justification path; the port contract makes this a stated obligation rather than an implied one | **STRENGTHENED** — the contract renders it |
| **INV-KOS-UNKNOWN-001** | The false-acceptance finding is the empirical shape of "insufficient grounds converted into an assertion"; abstention becomes a required mechanism output | **STRENGTHENED** — Zero's operational reading extended (C-5) |
| **INV-KOS-DIMENSION-001** (Article 2.3) | Mechanism accuracy must never cross the port as confidence | **STRENGTHENED** — refinement R-1 |
| AUTHORITY-001 · DECISION-001 · PROJECTION-001 · FAILURE-001 · CONTRADICTION-001 · AGENCY-001 · HISTORY-001 | Not implicated by intake-side evidence | **UNAFFECTED** |

The commission's eight distinction-pairs, traced to existing articles — **no new invariant is created for any of them**:

| Distinction | Existing home |
|---|---|
| Representation ≠ Identity · Expression ≠ Meaning · Similarity ≠ Identity | INV-KOS-IDENTITY-001 (Article 1.2, 1.3) |
| Meaning ≠ Truth | INV-KOS-VERIFICATION-001 + EpistemicState (Articles 6, 9) |
| Evidence ≠ Authority | INV-KOS-AUTHORITY-001 (Article 3.1) |
| Revision ≠ Erasure | INV-KOS-HISTORY-001 (Article 11.1) |
| Inference ≠ Observation | INV-KOS-VERIFICATION-001 (Article 6) |
| Agent ≠ Truth | INV-KOS-AGENCY-001 (Article 10) + §7.2 (the LLM does not own truth) |

---

## 10 · DDD decision matrix

| Concept | Current v1.1 | This review | Decision |
|---|---|---|---|
| **Knowledge Identity** | Core | Test F corroborates; nothing displaces it | **KEEP** |
| **Epistemic Evolution** | Core lifecycle | Untouched by intake-side evidence | **KEEP** |
| **Meaning Preservation** | Core facet | Sharpened: the core preserves **admitted** meaning; mechanisms propose | **KEEP** (clarified) |
| **KnowledgeAggregate (12 members)** | Primary aggregate | All 12 survive the ownership test | **KEEP** (1 refinement, 3 clarifications) |
| **Aggregate boundary wording (r2)** | Authoritative domain boundary for admissibility | Independently re-derived by the new material | **KEEP — corroborated** |
| **Semantic Compiler** | Candidate adapter, Expression port, not promoted | Replacement + ownership tests both place it outside; the false-accept evidence lowers the promotion case | **KEEP — still NOT promoted** |
| **Semantic Normal Form** | (not in v1.1) | Representation (primary) / mechanism (secondary); candidate port encoding | **STAYS OUT of the core** — Logical-Architecture candidate |
| **Semantic Invariance Layer** | (not in v1.1) | Mechanism at the Expression↔Meaning port; requires non-collapse, not only invariance | **STAYS OUT of the core** — hypothesis |
| **Expression↔Meaning Port Contract** | port named, obligations unstated | The gap this review found | **NEW DELIVERABLE — named for Logical Architecture** (no v1.1 structural change) |
| **KOS-EV / KOS-SCB** | (not in v1.1) | **Benchmark names, not components** | **NOT ADMITTED as architecture** |
| **LLM** | External adapter, Language Cortex | Unchanged | **KEEP** |
| **Zero** | Meta-principle | Operational reading extended to mechanism abstention | **KEEP** (clarified) |
| **Wisdom** | Rejected | Nothing new | **KEEP REJECTED** |
| **Natural language itself** | (implicit) | Test F: removable without changing what the system is | **NOT CORE — recorded** |

---

## 11 · Minimal change set

**No structural change. No new version.** v1.1 stands; the changes below are **r3 annotations** in the r2 manner (the artifact is annotated, never rewritten), and they should be applied **after** the HPA confirms the r2 wording (P-1).

| # | Class | Change | Where |
|---|---|---|---|
| **C-1** | CLARIFICATION | Non-admission note: *canonical form is a representation; SNF/canonical-form equality is a candidate for same admitted meaning, never an identity determination* | §7.3 (+ pointer at §6, INV-KOS-IDENTITY-001 row) |
| **C-2** | CLARIFICATION | Member note on **Meaning**: the core holds **admitted** intensional content; a mechanism's output is a candidate outside the boundary | §4.1 |
| **C-3** | CLARIFICATION | Member note on **EvidenceLinks**: references + acquisition method + reliability conditions — never evidence content (guards the Ch IV database refusal) | §4.1 |
| **C-4** | CLARIFICATION | Member note on **Relations**: by KnowledgeId reference only, never containment | §4.1 |
| **C-5** | CLARIFICATION | Zero's operational reading extended: a mechanism's *"I did not understand"* is a first-class output that maps to UNKNOWN — never ABSENT, never FALSE, never a low-confidence accept | §7.4 |
| **R-1** | REFINEMENT | **Confidence** boundary rule: structured epistemic metadata **assigned inside** the boundary; a mechanism-supplied score (e.g. parser accuracy) must never cross the port as confidence | §4.1 (+ INV-KOS-DIMENSION-001 row) |
| **A-1** | ADDITION (record only) | Falsification results **A** (invariance removable), **B** (compilation removable), **F** (natural language removable → the decisive argument) recorded alongside the existing C/D/E answers | §3.3 / Final Quality Gates |
| **A-2** | ADDITION (forward pointer) | The **Expression↔Meaning Port Contract** named as a Logical-Architecture deliverable with its six obligations, each traced to an existing invariant | §7.3 |
| **A-3** | GUARD | *KOS-EV / KOS-SCB are benchmark names, not architectural components*; no event is added for normalization (mechanism steps are not domain events) | §5 · §7.3 |

**Explicitly NOT changed:** the core-domain decision · the context map · the aggregate member set · the eleven invariants' wording · the ten domain events · the rejected set · the Semantic Compiler ruling · the LLM boundary · Zero's placement · the register (**25+4**) · the Constitution (**FROZEN**).

---

## 12 · Updated architecture view

**Not required.** The context map (§2.2) and the three-altitude table (§7) already express the reviewed structure correctly; redrawing them would imply a change that did not occur. The only view this review adds is the **boundary detail** of §4 and §6.1 — the *meaning candidate* level made explicit between Expression and Admitted Meaning, with the port contract as its gate. That belongs at Logical-Architecture altitude, not as a replacement for a v1.1 diagram.

---

## 13 · Final verdict

> ## ✅ PASS — CLARIFICATION ONLY
>
> **v1.1 withstands the new evidence unchanged.** The Semantic Compiler, Semantic Normal Form and Semantic Invariance Layer are **prepared as Logical-Architecture mechanisms** behind a named port contract, and **none becomes the domain**. Five clarifications, one refinement, three record-additions — all r3 annotations. No structural revision. No v1.2.

**What this review does NOT do.** It does not close the first HPA review (P-1) · does not promote any mechanism (only the HPA promotes) · does not author the port contract · does not authorize the v0.2 experiment · does not add a context, aggregate, member, event, or invariant · does not change the register (**25+4 unchanged**) · does not touch the Constitution (**FROZEN**) · does not reopen research (**CLOSED**) · does not accept its own findings (**R-34** — the HPA decides).

**Open questions (recorded, not decided).**

| # | Question | Owner |
|---|---|---|
| **OQ-1** | Does a mechanism's *declared insufficiency* need its own vocabulary at the port, or does the existing verdict vocabulary cover it? | Logical Architecture |
| **OQ-2** | May SNF-equivalence be recorded as an EvidenceLink supporting an identity-assignment act, or must it stay entirely outside the aggregate? | Logical Architecture (invariant-adjacent — HPA may wish to rule) |
| **OQ-3** | Is cross-language sameness (EN/DE) a claim about meaning or about translation? The evidence conflates them | Logical Architecture / experiment design |
| **OQ-4** | Is the real (non-simulated) v0.2 semantic-invariance experiment authorized, with what corpus and by whom? Note the corpus must include **negative** families (non-collapse), or it measures a lossy hash | **HPA act required** |
| **OQ-5** | Should Confidence remain an aggregate member at all, or become a derived read-side attribute? R-1 makes it safe; it does not make it necessary | Logical Architecture |

**Recommended next steps, in order.** (1) **HPA confirms the r2 wording** → the first review closes. (2) HPA rules on this review → the r3 annotations are applied. (3) **Logical Architecture** opens with the **Expression↔Meaning Port Contract** as its first named deliverable and the Trustworthy AI Engine as material (BV-8, hypothesis). (4) The semantic-invariance experiment runs **in parallel as evidence**, never as an architecture layer — matching the sequencing recommendation in the record: *architecture first, simulation as evidence, refine only where an actual inconsistency is exposed.*

---

## Traceability

- **Object:** Reference Architecture v1.1 r2 (`docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md`) — §1 · §2.2 · §2.3 · §3.1 · §3.2 · §3.3 · §4.1 · §4.2 · §4.3 · §5 · §6 · §7.1 · §7.2 · §7.3 · §7.4 · §8 · Final Quality Gates all read.
- **Prior act:** HPA review (`docs/knowledgeos/reviews/20260822-1425-KOS-EP01-Reference-Architecture-v1.1-DDD-Refinement-HPA-Review.md`) — PASS CONDITIONALLY, condition applied in r2, **awaiting HPA confirmation**.
- **Commission:** HPA, 2026-08-22 — *"Produce: KnowledgeOS Reference Architecture v1.1 — Second HPA Review & Semantic Invariance Refinement"*, with the 20-section review brief (role · default assumption · new evidence A/B · the critical DDD question · aggregate challenge · falsification tests A–F · altitude question · SNF · LLM boundary · Zero · invariants · contexts · ownership test · replacement test · representation-invariance test · finding classification · required output · hard constraint · final mission).
- **Evidence read in full** (main tree, branch `election-review`, commit `72fae34f`): `docs/knowledgeos/brainstorming/20260822-140622-paninian-grammar-compiler-architecture-parallel.md` · `20260822-140939-knowledgeos-semantic-compiler-architecture-research-extraction.md` · `20260822-141513-semantic-compiler-cpu-prototype-benchmark-first-run.md` · `20260822-143217-benchmark-verification-reinterpreted-knowledgeos-v1-1-layers.md` (composite — later pass supersedes the earlier projection) · `20260822-143421-semantic-compiler-kernel-boundary-refinement-kos-ev.md` · `20260822-143844-next-steps-architecture-first-then-semantic-compiler-experiment.md` · `20260822-125015-vedic-sanskrit-grammar-v2-word-order-semantic-normal-form.md`.
- **Constraints honored:** no new research · no new philosophical source · no constitutional article added or modified · no register row · no database / technology / API / class / code decision · no accuracy projection cited as evidence (explicitly excluded, §3.2) · no mechanism promoted · no bounded context created · no domain event added · no v1.2 produced · the strongest statement never exceeds the evidence.
- **Status:** ⭐ **SECOND ARCHITECTURAL REVIEW DELIVERED — PASS · CLARIFICATION ONLY.** Register **25+4 unchanged** · Constitution **FROZEN** · research closure **unchanged** · P4 gate **unchanged** · Semantic Compiler **NOT promoted** · v1.1 remains **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE**. Next: HPA confirmation of r2 → HPA ruling on this review → r3 annotations → Logical Architecture.
