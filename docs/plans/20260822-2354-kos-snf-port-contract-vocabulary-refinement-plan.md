# KnowledgeOS — EP-01 Plan · Expression↔Meaning Port Contract Vocabulary Refinement (AH-1 · AH-3) — APPROVED (HPA · 2026-08-23 · option C — two sibling declarations) (2026-08-22)

> **Act:** the **first authorized planning act** after the AH-1…AH-5 decision gate (HPA confirmation `20260822-2346`, commit `1e6d076f`) — a **separate EP-01 plan** for the Expression↔Meaning **Port Contract vocabulary refinement** resulting from **AH-1** and **AH-3**.
> **Position:** `P5 research CLOSED → AH-1…AH-5 decided (AH-1 ACCEPT · AH-3 ACCEPT) → EP-01 plan → Q-B resolved (terminology review) → HPA plan approval (2026-08-23, option C) ← WE ARE HERE → (further explicit implementation authorization) → implementation → EP-02 → reassess v1.1`.
> **Authority:** HPA confirmation 2026-08-22 — acceptance of AH-1/AH-3 "authorizes **only the next planning act, not implementation**: prepare a separate **EP-01 plan** for the Expression↔Meaning Port Contract vocabulary refinement." **HPA approval 2026-08-23 (option C) approves this plan as revised below — it does NOT authorize implementation.** Implementation is a further, separate, explicit act.
> **Deliverable of THIS act:** the plan only. **No code · no contract edit · no v1.1/Constitution/aggregate/Kernel/SNF/corpus modification · no v1.2 · no experiment (OQ-4 unauthorized).**
> **Status:** ✅ **EP-01 PLAN APPROVED (HPA, 2026-08-23 · option C — two sibling declarations: `declared insufficiency` · `declared determination`) · PLANNING ONLY.** Implementation (T-2/T-3) **NOT authorized** — a further, separate, explicit HPA act is required. Register **25+4 unchanged** · Constitution **FROZEN** · Port Contract **unchanged (PROPOSED · NON-AUTHORITATIVE)** · research **CLOSED** · OQ-4 **unauthorized**.

---

## 1 · Objective

Design — and gain the HPA's approval of — the **Expression↔Meaning Port Contract vocabulary refinement** that AH-1 and AH-3 require: **split the port's single generic `declared insufficiency` declaration into two sibling candidate-side declarations** — **`declared insufficiency`** (what the mechanism could not determine: `FILLER_UNKNOWN` + a `reason` distinguishing (AH-3) *parse-level inability* from *reading-level underdetermination*) and **`declared determination`** (what the mechanism determined: `NO_FILLER`, the (AH-1) determinate negative *"no filler / determined absent in the candidate"*) — **entirely at the port boundary, as candidate-side published language**, with the domain, the Constitution, the aggregate, and the Reference Architecture untouched. **(Approved by the HPA on 2026-08-23, option C — per the Q-B terminology review; see §5.1 · §11.)**

The plan determines the vocabulary (it does not copy the HPA's sketch into the contract); identifies the exact contract sites a later authorized slice would touch; scopes the architecture/fitness gates that slice must pass; and stops for approval.

## 2 · Background

**The chain.** P5 research closed with a decisive negative-side finding: **no producer represents the epistemic/semantic absence distinction at all — 110/110 failures on `unknown_vs_not_expressed`, all producers** (P5 Established item 9); and **v0.1 collapsed NOT_EXPRESSED vs UNKNOWN at 0.247** under τ = 0.40, which v0.2's four-way gate (14/14) then separated (0.450) (Established item 2). The post-research architectural review returned **NO CHANGE**: the domain already distinguishes UNKNOWN ≠ ABSENT ≠ FALSE (v1.1 §9 · Constitution Article 9 · INV-KOS-UNKNOWN-001); the evidence points at the **boundary**, not the domain. The HPA then ruled: **AH-1 ACCEPT** (declared insufficiency must distinguish *"no filler"* from *"filler unknown"*) · **AH-3 ACCEPT** (parse-level vs reading-level, composable, separate dimension).

**The contract being refined.** The Expression↔Meaning Port Contract (`docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md`) is the port's published language. It is **DELIVERED · PROPOSED · NON-AUTHORITATIVE** — a first Logical-Architecture deliverable, not yet a ratified artifact. Its obligation 3 names a single flat **declared insufficiency** ("the mechanism's statement of what it could not determine," §4 table); Q2 carries it as a required candidate component; Q4 maps *abstention* to UNKNOWN (⟨C-5⟩). There is no vocabulary for **which** insufficiency or **why**. AH-1 and AH-3 fill exactly that gap.

**Why the refinement is boundary-only (HPA, recorded):** *"We are not refining the KnowledgeOS domain model because of SNF research. The domain already has the correct distinction between UNKNOWN, ABSENT and FALSE. The evidence points to a boundary/contract vocabulary refinement — not a new aggregate, invariant, or constitutional concept."*

## 3 · Scope

### In scope (this plan)
- The EP-01 architectural plan for the Port Contract vocabulary refinement (AH-1 + AH-3), including the derived vocabulary, the exact contract sites a later slice would touch, the impact analysis, and the gates that slice must pass.
- Read-only inspection and derivation from the repository's authoritative documents.

### Out of scope (explicitly NOT authorized)
- **No implementation** — no edit to the Port Contract, v1.1, Constitution, aggregate, Kernel, SNF mechanisms, research corpus, or register.
- **No v1.2.**
- **No experiment** — OQ-4 (KOS-SCB v0.2) and KOS-SNF-ME v0.4 remain **unauthorized**.
- **No Kernel** — not started; SNF-C is not its semantic engine; no Sanskrit/Pāṇinian grammar into the Kernel; no SNF-E choice; no simulation; no P5 reopen; no real-language corpus research.
- **Not resolved here:** OQ-2 (SNF-equivalence as EvidenceLink — Port §Q8 position stands, HPA rule separate) · OQ-3 (modality) · OQ-5 (Confidence in the aggregate) · **F-1…F-5** (LA Review 01 wording candidates) · **AH-5** (deferred, not by inference) · **AH-2** (corroboration only, no change).
- **AH-4** — the research-governance recording is a **separate, also-authorized artifact** (metric version · corpus/family scope · addressed failure modes on any future promotion claim), **not part of this plan**.

## 4 · Engineering Readiness Review (EP-03) — answers derived from the repository

| # | Readiness question | Answer (derived) |
|---|---|---|
| **1** | **Business capability / constitutional policy protected** | The port's published language must let a mechanism declare **precisely** what it could not determine, so the core can route honestly to UNKNOWN without rewarding assertion. Protected policy: Constitution Article 9 (structured uncertainty SHALL be preserved, never flattened) · INV-KOS-UNKNOWN-001 · Port obligation 3. The refinement renders an existing policy more precisely at the boundary — no new policy. |
| **2** | **Strategic DDD — owner** | The **Expression↔Meaning Port** (Logical Architecture, Article 1.2 ACL) owns the published vocabulary; the mechanism (Expression context) is the producer that honors it. The capability "declared-insufficiency vocabulary" is the boundary's published language. |
| **2a** | **Strategic DDD — other context affected?** | **No.** The core's epistemic states (§9 seven states), the aggregate, the Verification Port, and the Constitution are untouched. Mechanisms' obligations change only in the *declaration vocabulary* they must honor. |
| **2b** | **Published-Language interaction?** | **Yes** — the port vocabulary IS published language; this refinement extends it. |
| **2c/d** | **Ownership change? Context-map change?** | **No. No.** |
| **3** | **Canonical discovery (search before modelling)** | Domain-side distinction **already exists** — v1.1 §9 (unknown ≠ absent ≠ false), Article 9, INV-KOS-UNKNOWN-001: **consume, never recreate**. Port-side **does not exist** — obligation 3 has one flat term (§4 row), no structure. Research-side: the 2×2 abstention taxonomy is **measurement vocabulary** (OBS-PR-1), **not** to be ported into the port vocabulary. No ADR/standard already covers port-insufficiency structure. Verdict: **extend the existing port term; consume the existing domain distinction; introduce no second concept anywhere.** |
| **4** | **Tactical DDD shape** | The refinement structures an existing **port-level term** into (what × reason). Candidate-side metadata only — **never** aggregate members (r4-4), **never** domain events (⟨A-3⟩), **never** new epistemic states (§9, Article 9), **never** Confidence (⟨R-1⟩), **never** identity (obligation 4), **never** a scalar in place of epistemic structure (obligation 5). |
| **5** | **Stewardship — what must remain stable / may evolve / evidence for change** | **Stable:** the seven states + semantics · the single admission path (Verification Port) · identity assignment · the UNKNOWN mapping (⟨C-5⟩) · the six prohibitions (Q7) · the six obligations' invariant renderings. **Evolves:** the *declared insufficiency* vocabulary (obligation 3 content · §4 rows · Q2/Q4 wording). **Evidence justifying change:** P5 Established item 9 (110/110) + item 2 (0.247 collapse, four-way 14/14) + the HPA's AH-1/AH-3 rulings — concrete boundary failure modes, not an opportunistic observation. |
| **6** | **Impact classification** | **Tactical Architecture** — Logical-Architecture port-vocabulary refinement of a **PROPOSED, NON-AUTHORITATIVE** deliverable (a revision before authoritative status, not an amendment of a ratified doc). Not strategic · not governance (AH-4 is separate) · not constitutional · not engineering/repository. |
| **7** | **Readiness** | **Authorization:** HPA confirmation 2026-08-22 (AH-1/AH-3 accepted; next planning act authorized). **Scope:** this plan only. **Dependencies:** none blocking (all authoritative docs in repo). **Acceptance criteria:** the plan determines the vocabulary, honors the boundary constraints, scopes the future gates, stops for approval. **RED boundary:** no code, no contract edit, no implementation. |

## 5 · Design decisions — the vocabulary this plan determines

The HPA's sketch (`DeclaredInsufficiency · what_is_undetermined · FILLER/… · reason · PARSE_UNAVAILABLE/READING_UNDERDETERMINED`) is **guidance, not contract text** — the plan derives the vocabulary from the evidence and the existing contract, and the Q-B terminology review (`20260823-0015`) refined the grouping. **The plan determined the following; the HPA approved it on 2026-08-23 (option C — two sibling declarations).**

### 5.1 The two sibling port declarations — `declared insufficiency` · `declared determination`

The existing single flat term is **split into two sibling candidate-side declarations** — one for what the mechanism **could not determine** (`declared insufficiency`), one for what it **determined** about the candidate (`declared determination`). They remain **candidate-side port vocabulary — never an aggregate member, never a domain object, never a state** (r4-4 · ⟨A-3⟩ · §9).

The split is **approved by the HPA (2026-08-23, option C)** per the Q-B terminology review (`20260823-0015`): `FILLER_UNKNOWN` and `NO_FILLER` render **different invariants** (inability → UNKNOWN vs determinate candidate claim → core evaluates), so they are **two concepts, not two values under one label**. `declared insufficiency` is **reserved** for the inability act and is **not** an umbrella for both; its meaning is **not broadened**.

```
declared insufficiency        (candidate-side · port vocabulary · §4)
  └── FILLER_UNKNOWN          "a filler may exist, but the provider could not
        │                      determine which one"  — a DETERMINATE INSUFFICIENCY
        └── reason            (AH-3 — why the insufficiency arose; orthogonal)
              ├── PARSE_UNAVAILABLE        "cannot parse the relevant token/form"
              └── READING_UNDERDETERMINED  "can parse, but cannot decide the interpretation"

declared determination        (candidate-side · port vocabulary · §4)
  └── NO_FILLER               "the representation establishes that the role/slot is
                               not expressed"  — a DETERMINATE NEGATIVE
```

### 5.2 AH-1 — the two sibling declarations

| Value-case | Definition | Character | Routing consequence |
|---|---|---|---|
| **`NO_FILLER`** | *"The representation establishes that the role/slot is not expressed."* | **Determinate negative** — the mechanism determined that the candidate meaning (its intensional content) lacks a filler for the role. A claim **about the candidate meaning**, not about the world. **Not an abstention.** | Evidence toward the core's ABSENT-direction determination. **The mechanism never emits ABSENT** (obligations 1 · 6); the core evaluates (admissibility · justification) and decides. |
| **`FILLER_UNKNOWN`** | *"A filler may exist, but the provider could not determine which one."* | **Determinate insufficiency** — the mechanism declares its own inability. This is abstention. | Maps to **UNKNOWN** (⟨C-5⟩) — never ABSENT, never FALSE, never a low-confidence accept (obligation 6). Identical routing to today's abstention. |

**Derivation:** P5's `unknown_vs_not_expressed` failure is exactly the inability to state whether the *expression* leaves the role unfilled (`NOT_EXPRESSED` → port `NO_FILLER`) or the *mechanism* could not fill it (`UNKNOWN` → port `FILLER_UNKNOWN`). The domain's §9 ABSENT/UNKNOWN semantics are the **source**; the port now renders the distinction in the mechanism's own voice, without letting the mechanism mint the states.

**Two sibling declarations, not one structured term:** the single generic "declared insufficiency" is replaced by **two sibling port terms** — the port can tell a *determined absence in the candidate* (`declared determination` · `NO_FILLER`) from a *determined inability of the provider* (`declared insufficiency` · `FILLER_UNKNOWN`). This is the entire point of AH-1, and the option-C split makes the distinction **structurally visible** in the published language rather than a remembered sub-case of one umbrella term.

### 5.3 AH-3 — the `reason` dimension (orthogonal, composes)

| Value-case | Definition |
|---|---|
| **`PARSE_UNAVAILABLE`** | Cannot parse the relevant token/form — a **surface-level inability** (the word/segment could not be parsed). |
| **`READING_UNDERDETERMINED`** | Can parse, but cannot decide the interpretation/reading — a **semantic-level underdetermination** (multiple readings consistent). |

**Composition rule (the plan determines it):** `reason` applies **only to insufficiency declarations** — `FILLER_UNKNOWN`, under `declared insufficiency`. `NO_FILLER`, under `declared determination`, is a determinate declaration and carries **no** reason: establishing that a slot is not expressed requires a successful parse *and* a successful reading; *"cannot parse ⇒ absence"* is contradictory and **excluded**. The valid declaration space is therefore exactly:

```
NO_FILLER                                            (determinate negative · no reason)
FILLER_UNKNOWN × PARSE_UNAVAILABLE                   (insufficiency · parse-level)
FILLER_UNKNOWN × READING_UNDERDETERMINED             (insufficiency · reading-level)
```

The dimension is **general** (orthogonal by construction), so a future undetermined value may carry a reason; **no new value is admitted now** — the what-dimension is closed to `FILLER_*`, the reason-dimension to the two above, and new values enter only by the same HPA-ruled process that admitted these.

**Not a domain object, not an altitude:** the AH-3 distinction is a **port-vocabulary dimension**, never a domain object, never a state, and it must not be read through the altitude vocabulary — hence the names `PARSE_UNAVAILABLE` / `READING_UNDERDETERMINED` describe the *cause of the insufficiency*, not a "level" of the system (§16 altitudes are untouched). *(The HPA's alternative names `PARSE_LEVEL` / `READING_LEVEL` are recorded as synonyms, not adopted — "level" collides with the altitude vocabulary.)*

### 5.4 Reconciliation with the existing terms (no silent drift) — per the HPA's option-C decision

- **`declared insufficiency`** keeps its name **and its exact meaning** — *"the mechanism's statement of what it could not determine"* (obligation 3 · §4). **No broadening.** It is **reserved** for `FILLER_UNKNOWN` and its `reason` dimension. Per the HPA (2026-08-23): *"Do not broaden the existing meaning of `declared insufficiency`."*
- **`declared determination`** is the **new sibling port-level term** for `NO_FILLER` — the mechanism's statement of what it determined about the candidate representation. Per the HPA (2026-08-23): *"`declared determination` is the sibling port-level term for `NO_FILLER`."*
- **`declared insufficiency` is NOT an umbrella for both.** The two sibling terms each render their own invariant: `FILLER_UNKNOWN` → UNKNOWN (⟨C-5⟩ · obligation 3); `NO_FILLER` → evidence toward the core's ABSENT-direction (Q6 · obligation 2), evaluated by the core, never auto-mapped.
- **`abstention`** keeps its meaning (*"I did not determine this"* → UNKNOWN, Port §Q4) and becomes **exactly the `FILLER_UNKNOWN` family** (+ any future undetermined values), i.e. the `declared insufficiency` declaration. `NO_FILLER` is **not** an abstention.
- **Obligation 3** (renders INV-KOS-UNKNOWN-001) is unchanged in content; a later slice adds a pointer to the structured §4 vocabulary. **No new law.**
- **⟨C-5⟩** (mechanism inability → UNKNOWN) is unchanged and now distinguishes its own reason dimension.

### 5.5 Anti-laundering guard (a rendering of existing law, not new law)

The integrity risk AH-1 opens: a mechanism could **convert underdetermination into determination** by declaring `NO_FILLER` where it merely could not determine. The guard renders the existing "the core evaluates, the mechanism proposes" separation (Port §Q6 · §5 trust boundary · obligation 2):

1. `NO_FILLER` is admissible only with a **justification path** that establishes the absence (obligation 2 already requires the path);
2. the **core retains the right** to route an unjustified or contradicted absence claim to **UNKNOWN / QUESTIONABLE** rather than ABSENT (obligation 6 · Q6);
3. `reason` is present on every insufficiency, so *"I could not parse it"* cannot masquerade as *"I established its absence."*

This is a **rendering** — the kernel-trusts-form/core-evaluates rule already exists; the guard makes it testable at the vocabulary level. Gate **G-6**.

### 5.6 Where the changes land — the exact contract sites a later authorized slice would touch

The refinement is a revision of the **PROPOSED · NON-AUTHORITATIVE** Port Contract (a first deliverable, amended before authoritative status — not an amendment of a ratified document). Proposed touches, all in `20260822-1559-KOS-Expression-Meaning-Port-Contract.md`:

| Site | Proposed change |
|---|---|
| **§2 · obligation 3** | Content unchanged (renders INV-KOS-UNKNOWN-001); add a pointer to the structured vocabulary (§4) — the declaration is now one of **two sibling declarations**. |
| **§3 · Q2** | The candidate-component rows *Declared insufficiency* / *Declared determination* gain: "structured — `insufficiency` (what was not determined × why) vs `determination` (what was determined)." |
| **§3 · Q4** | Reconcile: abstention = the `FILLER_UNKNOWN` family (the `declared insufficiency` declaration) → UNKNOWN (⟨C-5⟩); `NO_FILLER` (the `declared determination` declaration) is **not** an abstention — a determinate negative the core evaluates. |
| **§4 · vocabulary table** | Replace the single flat *declared insufficiency* row with the **two sibling terms** + the value-cases (each with its rendered obligation): *declared insufficiency* → `FILLER_UNKNOWN` (+ `reason`: `PARSE_UNAVAILABLE` · `READING_UNDERDETERMINED`); *declared determination* → `NO_FILLER`. Reconcile the *abstention* row (→ `FILLER_UNKNOWN` family). |
| **§6 · OQ-1** | Record **OQ-1 resolved** by the AH-1 ruling: *declared insufficiency* has its own vocabulary — now the sibling pair *insufficiency / determination* (approved option C). (v1.1 §20 defers OQ-1 to the Logical Architecture; this is that answer.) |
| **§7 · gates** | **Structure** gate stays ✅ (the value-cases are port vocabulary — r4-4 · ⟨A-3⟩ · §9); **add** the No-domain-capture gate (G-4) and the Anti-laundering gate (G-6). |
| **§1 · purpose** | One clarifying sentence: a mechanism can now declare **which** statement — *"determined absent in the candidate"* (`declared determination`) or *"could not determine"* (`declared insufficiency`) — so the core distinguishes the two honestly. |

### 5.7 What does NOT change — anywhere

- The domain's **seven epistemic states** (§9) — exactly seven; no eighth state (`NO_FILLER` is port vocabulary, not a state).
- **Constitution Article 9 · V.3** — untouched; no new concept, no new row.
- The **aggregate / KnowledgeAggregate** — untouched.
- The **Verification Port · identity rule (obligation 4) · Confidence boundary (⟨R-1⟩) · the six prohibitions (Q7) · the six obligations' invariant renderings** — untouched.
- The **trust boundary table (§5)** — untouched (ownership unchanged).
- **Register 25+4 · no new law · no new invariant · no new domain event.**
- **v1.1 · Constitution · Kernel · SNF mechanisms · research corpus — NOT modified.**
- **No implementation form** — message formats, schemas, APIs, classes, storage (DEF-5) remain out of scope; the vocabulary stays at Logical-Architecture altitude.
- **OQ-2 · OQ-3 · OQ-5 · F-1…F-5 · AH-5 · AH-2** — unchanged; the refinement is orthogonal to them and must not preempt their rulings.

## 6 · Impact analysis

| Artifact | Impact |
|---|---|
| **Reference Architecture v1.1** | **None expected.** The refinement is at the Logical-Architecture port contract, which v1.1 explicitly named and deferred to (§10 ⟨A-2⟩ · §20 OQ-1). Post-implementation reassessment (per the chain) is expected to confirm **v1.1 remains** — that is **measured, not assumed**; only a material impact would open a controlled v1.2. |
| **Constitution v1.0** | **None** — Article 9 is the source the refinement renders; V.3 not engaged. |
| **Aggregate / members / events / states** | **None** — port vocabulary only (r4-4 · ⟨A-3⟩ · §9). |
| **Register** | **25+4 unchanged** — no new row. |
| **Mechanisms (future)** | The obligation on any mechanism gains structure: declare `NO_FILLER` vs `FILLER_UNKNOWN` and, on insufficiency, a reason. No mechanism exists yet; no implementation. The port stays mechanism-agnostic (Pāṇinian · dependency · symbolic · LLM · human — all honor the same declaration). |
| **OQ-1** | **Resolved in direction AH-1 ruled** (declared insufficiency has its own structured vocabulary) — recorded at implementation (§5.6), not in this planning act. |
| **OQ-2 · OQ-3 · OQ-5 · F-1…F-5 · AH-5** | **No impact here** — untouched, their rulings remain the HPA's, separately. |
| **AH-4** | Separate governance recording — **not this plan**. |

## 7 · Tests / gates — for the future implementation slice (specified here, NOT written now)

The later authorized slice must pass these **architecture/fitness gates** — property-based (they verify properties, not class names), RED first, GREEN minimal, mirroring the discipline that produced P5's four-way gate:

| Gate | Property it verifies | Rendered by |
|---|---|---|
| **G-1 · AH-1 distinction** | `NO_FILLER` and `FILLER_UNKNOWN` are **distinct** declarations, never collapsed — the port can tell a determined absence from a determined inability (analogous to P5's `NOT_EXPRESSED vs UNKNOWN` 0.247→0.450 fix). | AH-1 |
| **G-2 · AH-3 orthogonality** | `reason` is a **separate dimension**; `PARSE_UNAVAILABLE` ≠ `READING_UNDERDETERMINED`; each composes with `FILLER_UNKNOWN`. | AH-3 |
| **G-3 · Composition closure** | The valid declaration space is exactly `{NO_FILLER} ∪ {FILLER_UNKNOWN × (PARSE_UNAVAILABLE \| READING_UNDERDETERMINED)}` — no contradictory combos (no reason on `NO_FILLER`). | §5.3 |
| **G-4 · No-domain-capture** | None of the four value-cases becomes a domain state, member, event, or register row; §9 remains exactly seven states; register 25+4; Port §7 Structure gate still passes. | r4-4 · ⟨A-3⟩ · §9 |
| **G-5 · Routing** | `FILLER_UNKNOWN` → UNKNOWN (⟨C-5⟩), never ABSENT/FALSE/low-confidence accept; `NO_FILLER` never auto-maps to ABSENT (core evaluates); no declaration emits an epistemic state (obligations 1 · 6). | ⟨C-5⟩ · obligations 1, 6 |
| **G-6 · Anti-laundering** | A mechanism cannot convert underdetermination into determination: `NO_FILLER` requires justification; unjustified/contradicted absence claims route to UNKNOWN/QUESTIONABLE; insufficiency always carries a reason. | §5.5 · Q6 · obligation 2 |
| **G-7 · No-new-law** | Every vocabulary element traces to an existing invariant (obligation 3 → INV-KOS-UNKNOWN-001 · §9 ABSENT rendering · Article 9). | Port §7 No-new-law |
| **G-8 · Regression** | Obligations 1–6 invariant renderings unchanged; Q1–Q8 still hold (Q2/Q4 reconciled); §5 trust boundary unchanged. | Port §2 · §3 · §5 |

## 8 · Task checklist (implementation phase — NOT authorized by this act)

| # | Task | Status |
|---|---|---|
| **T-1** | **HPA approval of this EP-01 plan** | ✅ **APPROVED — HPA, 2026-08-23 · option C** (two sibling declarations: `declared insufficiency` · `declared determination`; the revised plan below is the approved form) |
| **T-2** | **Implementation slice** — edit the Port Contract per §5.6 (obligation-3 pointer · Q2/Q4 reconciliation · §4 vocabulary table · §6 OQ-1 resolution record · §7 gates) | ⬜ NOT AUTHORIZED — plan approval is planning-only; the slice requires a **further, separate, explicit HPA act** |
| **T-3** | **RED → GREEN** architecture/fitness gates **G-1…G-8** (property-based) | ⬜ deferred with T-2 — same separate authorization |
| **T-4** | **EP-02 completion review** — independent acceptance (R-34: engineering never accepts its own work) | ⬜ deferred |
| **T-5** | **Post-implementation reassessment** of v1.1 (expected: v1.1 remains; measured, not assumed) | ⬜ deferred |
| **T-6** | **AH-4 research-governance recording** (metric version · corpus/family scope · addressed failure modes) | ⬜ SEPARATE authorized artifact — not this plan |

## 9 · Progress

- **2026-08-22** — planning act executed: read-only inspection of the Port Contract, v1.1 (§9/§10/§14/§16/§20), Constitution Article 9, P5 evidence (items 2 · 9 · AH-3 row · Threats item 6), Post-Research review (OBS-PR-1…3 · F-1…F-5), HPA confirmation/decision-record. EP-03 readiness derived (§4). Vocabulary determined (§5). Gates specified (§7).
- **2026-08-23** — **Q-B resolved**: the HPA commissioned a read-only terminology review (`docs/knowledgeos/reviews/20260823-0015-...`, commit `e5cab7c9`); the review concluded `declared insufficiency` is the wrong parent for `NO_FILLER` (two renderings ⇒ two concepts; the `what_is_undetermined` label contradicted its own child) and recommended two sibling terms. **The HPA ruled option C — approve the plan with the structural split** (`declared insufficiency` = `FILLER_UNKNOWN` + `reason`; `declared determination` = `NO_FILLER`; no umbrella; no broadening of `declared insufficiency`). This plan was revised to the approved form (§5.1–§5.6 · §8 · §10 · §11).
- **Status:** ✅ **EP-01 PLAN APPROVED (HPA, 2026-08-23 · option C) · PLANNING ONLY.** No implementation — T-2/T-3 await a further, separate, explicit HPA act. Nothing else was touched.

## 10 · Risks

| Risk | Mitigation |
|---|---|
| **Naming drift** — plan's derived names vs the HPA's sketch | The plan adopts the HPA's names (`NO_FILLER` · `FILLER_UNKNOWN` · `PARSE_UNAVAILABLE` · `READING_UNDERDETERMINED`) and records the HPA's alternative reason names (`PARSE_LEVEL`/`READING_LEVEL`) as not-adopted synonyms (collision with altitude vocabulary). Confirmed at approval (§11). |
| **Terminological tension** — "insufficiency" semantically means inability, yet `NO_FILLER` (a determinate negative) sat under it | **Resolved by the structural split (HPA 2026-08-23, option C)** — `declared insufficiency` is reserved for the inability act (no broadening); `NO_FILLER` moves under the sibling `declared determination`; the `what_is_undetermined` dimension label (which contradicted its own `NO_FILLER` child) is removed. |
| **Laundering** — `NO_FILLER` used to escape abstention | The anti-laundering guard (§5.5) — justification requirement · core retains UNKNOWN/QUESTIONABLE routing · reason always present on insufficiency. Gate G-6. |
| **Over-structuring** — port vocabulary drifts into a domain/measurement taxonomy | The value-cases are strictly the AH-1/AH-3 distinction; the research 2×2 abstention taxonomy stays measurement-side (OBS-PR-1). No new values admitted now. |
| **Scope preemption** — of F-1…F-5 / OQ-2 / OQ-3 / OQ-5 | Explicit out-of-scope block (§3); the refinement is orthogonal to them; their rulings remain the HPA's. |
| **v1.2 temptation** — research produced interesting results, so "manufacture a v1.2" | The confirmation and this plan's impact analysis say the refinement is boundary-only; the reassessment (T-5) is a separate, measured act. |

## 11 · Open questions — resolved and remaining

- **Q-A. RESOLVED (HPA, 2026-08-23, option C).** The derived vocabulary is adopted **in the option-C form**: names (`declared insufficiency` · `declared determination` · `FILLER_UNKNOWN` · `NO_FILLER` · `PARSE_UNAVAILABLE` · `READING_UNDERDETERMINED`), composition rule, and routing are as §5 — with the structural split approved.
- **Q-B. RESOLVED (HPA, 2026-08-23, option C).** The §5.4 re-scope is **rejected** — *"do not broaden the existing meaning of `declared insufficiency`"* and *"do not use `declared insufficiency` as an umbrella for both."* The split per the terminology review (`20260823-0015`) stands: `declared insufficiency` = `FILLER_UNKNOWN` + `reason`; `declared determination` = `NO_FILLER`. The §5.2 position — the core evaluates `NO_FILLER` and never auto-maps it to ABSENT — is confirmed.
- **Q-C.** One implementation slice for AH-1+AH-3 together (recommended — the dimensions compose; splitting would leave a partially-structured vocabulary), or two sequential slices? → **deferred to the implementation authorization** (the HPA decides at that act).
- **Q-D.** Record **OQ-1 as resolved** by the AH-1 ruling in the same slice (§5.6), or leave OQ-1's record for a separate act? → **deferred to the implementation authorization**.

## 12 · Next actions — STOP for the implementation authorization

1. **STOP.** This planning act is **complete and APPROVED** (HPA, 2026-08-23, option C). **No code. No contract edit. No implementation.**
2. This revised plan is the **approved form** — the reference for the future implementation slice and for the EP-02 completion review.
3. **Implementation is NOT authorized.** Per the HPA (2026-08-23): *"Approval of the revised plan is approval of the planning act only. Stop again and await a separate explicit implementation authorization."* The **T-2/T-3 slice** is a **further, separate, explicit HPA act** — it may also decide Q-C (one slice vs two) and Q-D (OQ-1 record).
4. AH-4's governance recording is a separate authorized artifact.
5. After implementation: **EP-02** independent completion review (R-34) · **reassess v1.1** (T-5) · then the already-waiting OQ-2 · OQ-3 · OQ-5 · F-1…F-5 · AH-5 deferral.

```text
EP-01 PORT CONTRACT VOCABULARY REFINEMENT PLAN: APPROVED (HPA · 2026-08-23 · option C)

  Vocabulary determined   declared insufficiency  = FILLER_UNKNOWN
                                                     × reason (PARSE_UNAVAILABLE | READING_UNDERDETERMINED)
                          declared determination  = NO_FILLER
  Sibling split           no umbrella · no broadening of "declared insufficiency" (HPA decision)
  Routing                 FILLER_UNKNOWN → UNKNOWN → core evaluates
                          NO_FILLER → determinate candidate-side statement → core evaluates
                          → may contribute to ABSENT; neither mechanism emits a state or identity
  Boundary honored        port vocabulary only · never domain state/member/event/row (r4-4 · ⟨A-3⟩ · §9)
  Domain untouched        seven states (§9) · Constitution Article 9 · aggregate · Kernel · v1.1 — UNCHANGED
  Implementation         NOT authorized — separate explicit HPA act required
  Register 25+4          UNCHANGED · OQ-4 UNAUTHORIZED · research CLOSED · Constitution FROZEN
```

---

## Traceability

- **Act:** HPA confirmation 2026-08-22 (`docs/knowledgeos/reviews/20260822-2346-KOS-EP01-AH1-5-HPA-confirmation.md`, commit `1e6d076f`) — acceptance of AH-1/AH-3 authorizes **only the next planning act**: *"prepare a separate EP-01 plan for the Expression↔Meaning Port Contract vocabulary refinement."* HPA 2026-08-22 (the post-confirmation directive): *"Now we should execute the first authorized planning act: EP-01 plan for the Expression↔Meaning Port Contract vocabulary refinement. Not implementation."* — with the AH-1/AH-3 scope, the NOT-do list, and *"don't put this exact structure into the code yet — that's what the EP-01 architectural plan must determine."*
- **Rulings implemented-by-design (planning only):** AH-1 ACCEPT · AH-3 ACCEPT (confirmation §1) — the vocabulary above is the plan's determination of what those rulings require at the port.
- **Contract to be refined:** `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` (DELIVERED · PROPOSED · NON-AUTHORITATIVE) — obligation 3 · §3 Q2/Q4 · §4 vocabulary table · §6 OQ-1 · §7 gates.
- **Authoritative grounding:** Reference Architecture v1.1 (`20260822-1402`, §9 seven states · §10 ⟨A-2⟩ obligations · §14 ⟨C-5⟩ · §16 altitudes · §20 OQ-1…OQ-5) · Constitution v1.0 (Article 9 · V.3) · Post-Research review (`20260822-1658`, NO CHANGE · OBS-PR-1…3 · F-1…F-5 pending HPA rule).
- **Evidence:** P5 (`KOS-SNF-RESEARCH-P5-COMPETITION-001.md`) — Established item 9 (110/110, all producers) · Established item 2 (v0.1 0.247 collapse; v0.2 four-way 0.450–1.00, gate 14/14) · AH-3 row + Threats item 6 (definitional artifact) · the decision-support (`20260822-2334`) and decision-record (`20260822-2341`) analyses this plan's design decisions build on.
- **Predecessor plan:** `docs/plans/20260822-1856-kos-snf-p5-semantic-competition-refinement-plan.md` — executed, closed by EP-02 (commit `32554cbd`); this plan follows it, does not supersede it.
- **Q-B resolution:** terminology review `docs/knowledgeos/reviews/20260823-0015-KOS-EP01-QB-terminology-review-declared-insufficiency.md` (commit `e5cab7c9`) — read-only review concluding `declared insufficiency` is the wrong parent for `NO_FILLER` (two renderings ⇒ two concepts) and recommending two sibling terms · **HPA decision 2026-08-23 (option C): approve the plan with the structural split** — `declared insufficiency` reserved for `FILLER_UNKNOWN` + `reason` · `declared determination` the sibling term for `NO_FILLER` · no umbrella · no broadening of `declared insufficiency` · the routing preserved (FILLER_UNKNOWN → UNKNOWN → core evaluates; NO_FILLER → determinate candidate-side statement → core evaluates → may contribute to ABSENT) · neither mechanism emits a state or identity. This plan was revised to the approved form.
- **Discipline honored:** EP-01 planning stage — plan produced, **revised per the HPA's option-C decision, and APPROVED (2026-08-23)** · EP-03 readiness derived from the repository · no code · no contract edit · no v1.1/Constitution/aggregate/Kernel/SNF/corpus modification · no v1.2 · no experiment (OQ-4 unauthorized) · no new law, invariant, member, event, or register row · **acceptance and act-authorization strictly separated** — plan approval does NOT authorize implementation (T-2/T-3 remain a further, separate, explicit HPA act) · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · the strongest statement never exceeds the evidence (P5 is toy-world; no natural-language claim).
- **Status:** ✅ **EP-01 PLAN — APPROVED (HPA, 2026-08-23 · option C — two sibling declarations) · PLANNING ONLY.** Next: the **separate, explicit implementation authorization** for T-2/T-3 (§12) — then EP-02 · v1.1 reassessment · OQ-2/OQ-3/OQ-5/F-1…F-5. **The Kernel still waits.**
