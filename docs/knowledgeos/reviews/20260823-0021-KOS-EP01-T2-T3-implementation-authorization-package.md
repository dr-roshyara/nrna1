# KnowledgeOS — HPA Implementation Authorization Decision Package · EP-01 T-2/T-3 — Port Contract Vocabulary Refinement (2026-08-23)

> **Source:** Human Principal Architect (HPA), 2026-08-23 — the implementation-authorization preparation directive (recorded verbatim-in-substance in §0).
> **Role:** this is a **preparation / decision-support package** for the T-2/T-3 implementation slice of the **APPROVED** EP-01 plan (`docs/plans/20260822-2354-...-plan.md`, approval commit `664fb2da`, option C). It inspects the approved plan, states precisely what the slice will do and verify, answers the two remaining implementation questions (Q-C · Q-D) **from repository evidence**, and **stops**. **It does not authorize implementation.**
> **Position:** `P5 closed → AH-1…AH-5 decided → EP-01 planning → Q-B terminology review → HPA chooses C → EP-01 APPROVED → ← WE ARE HERE (authorization preparation) → HPA issues the formal T-2/T-3 implementation authorization → implementation → EP-02 → v1.1 reassessment`.
> **Status:** 📋 **IMPLEMENTATION-AUTHORIZATION DECISION PACKAGE — DELIVERED · PREPARATION ONLY · NON-AUTHORITATIVE.** The contract and all code remain **untouched**. The HPA's authorization fields are **⬜ OPEN**. Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized**.

---

## 0 · The act (HPA, verbatim-in-substance)

> *"The next artifact should be an HPA Implementation Authorization for EP-01, covering T-2/T-3. … The right next prompt is not an implementation prompt yet. It is an HPA Implementation Authorization preparation prompt that tells Claude to inspect the approved EP-01 plan, prepare the authorization decision package for T-2/T-3, answer Q-C/Q-D from repository evidence where possible, and stop without modifying the contract or code."*
>
> *"Do not start coding yet. … The repository itself now records this as the blocking condition: implementation requires a separate, explicit HPA act."*

**Reconciliation with the earlier message in this turn** (which read *"Authorize the T-2/T-3 implementation slice per the approved plan"*): the **later, explicit directive governs** — this package is prepared first; the contract and code stay untouched; the formal authorization is a **separate HPA act** that follows this package.

**The approved vocabulary this slice will implement (for reference):**

```text
Expression↔Meaning Port
│
├── declared insufficiency
│   └── FILLER_UNKNOWN
│       └── reason
│           ├── PARSE_UNAVAILABLE
│           └── READING_UNDERDETERMINED
│
└── declared determination
    └── NO_FILLER
```
```text
FILLER_UNKNOWN → UNKNOWN → core evaluates
NO_FILLER      → determinate candidate-side statement → core evaluates → may contribute toward ABSENT
Neither mechanism emits a domain state or identity.
```

---

## 1 · What the slice will do (T-2) — the exact contract sites (approved plan §5.6)

All edits land in the **PROPOSED · NON-AUTHORITATIVE** Port Contract (`docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md`) — a revision before authoritative status, not an amendment of a ratified document. Seven sites:

| Site | Change (per approved plan §5.6) |
|---|---|
| **§2 · obligation 3** | Content unchanged (renders INV-KOS-UNKNOWN-001); add a pointer to the structured vocabulary (§4) — the declaration is now one of **two sibling declarations**. |
| **§3 · Q2** | The candidate-component rows *Declared insufficiency* / *Declared determination* gain: "structured — `insufficiency` (what was not determined × why) vs `determination` (what was determined)." |
| **§3 · Q4** | Reconcile: abstention = the `FILLER_UNKNOWN` family (the `declared insufficiency` declaration) → UNKNOWN (⟨C-5⟩); `NO_FILLER` (the `declared determination` declaration) is **not** an abstention — a determinate negative the core evaluates. |
| **§4 · vocabulary table** | Replace the single flat *declared insufficiency* row with the **two sibling terms** + the value-cases (each with its rendered obligation): *declared insufficiency* → `FILLER_UNKNOWN` (+ `reason`: `PARSE_UNAVAILABLE` · `READING_UNDERDETERMINED`); *declared determination* → `NO_FILLER`. Reconcile the *abstention* row (→ `FILLER_UNKNOWN` family). |
| **§6 · OQ-1** | Record **OQ-1 resolved** by the AH-1 ruling: *declared insufficiency* has its own vocabulary — now the sibling pair *insufficiency / determination* (approved option C). (v1.1 §20 defers OQ-1 to the Logical Architecture; this is that answer.) |
| **§7 · gates** | **Structure** gate stays ✅ (the value-cases are port vocabulary — r4-4 · ⟨A-3⟩ · §9); **add** the No-domain-capture gate (G-4) and the Anti-laundering gate (G-6). |
| **§1 · purpose** | One clarifying sentence: a mechanism can now declare **which** statement — *"determined absent in the candidate"* (`declared determination`) or *"could not determine"* (`declared insufficiency`) — so the core distinguishes the two honestly. |

---

## 2 · What the slice will verify (T-3) — gates G-1…G-8, RED → GREEN (approved plan §7)

The gates are **property-based architecture/fitness gates** verified against the refined contract (document-appropriate form: the properties must hold on the contract text; RED = the current contract fails the property, GREEN = the refined contract satisfies it, each with exact citations). The slice produces this verification as committed evidence.

| Gate | Property | RED (current contract) | GREEN (refined contract — the property that must hold) |
|---|---|---|---|
| **G-1 · AH-1 distinction** | `NO_FILLER` and `FILLER_UNKNOWN` are **distinct** declarations, never collapsed — the port can tell a determined absence from a determined inability | Current §4 has one flat *declared insufficiency* row; **no** `NO_FILLER` / `FILLER_UNKNOWN` — G-1 **fails** | §4 carries **two sibling terms** with distinct definitions and distinct routing — G-1 **passes** |
| **G-2 · AH-3 orthogonality** | `reason` is a **separate dimension**; `PARSE_UNAVAILABLE` ≠ `READING_UNDERDETERMINED`; each composes with `FILLER_UNKNOWN` | Current contract has **no** `reason` dimension — G-2 **fails** | §4 carries `reason` with two distinct values under `FILLER_UNKNOWN` — G-2 **passes** |
| **G-3 · Composition closure** | Valid declaration space exactly `{NO_FILLER} ∪ {FILLER_UNKNOWN × (PARSE_UNAVAILABLE \| READING_UNDERDETERMINED)}` — no reason on `NO_FILLER` | Current contract has no structured space — G-3 **fails** | §4 states the closure explicitly; `NO_FILLER` carries **no** reason — G-3 **passes** |
| **G-4 · No-domain-capture** | None of the value-cases becomes a domain state, member, event, or register row; §9 remains exactly seven states; register 25+4; §7 **Structure** gate still passes | (holds today; must be re-verified after the edit) | §7 gains the **No-domain-capture** gate row; §9 / register unaffected — G-4 **passes** |
| **G-5 · Routing** | `FILLER_UNKNOWN` → UNKNOWN (⟨C-5⟩), never ABSENT/FALSE/low-confidence accept; `NO_FILLER` never auto-maps to ABSENT (core evaluates); no declaration emits an epistemic state (obligations 1 · 6) | Current §4 routing is single-value (abstention → UNKNOWN); **no** `NO_FILLER` routing exists — G-5 **fails** | §4 rows carry the routing (UNKNOWN vs core-evaluates); obligations 1 · 6 honored — G-5 **passes** |
| **G-6 · Anti-laundering** | A mechanism cannot convert underdetermination into determination: `NO_FILLER` requires justification; unjustified/contradicted absence claims route to UNKNOWN/QUESTIONABLE; insufficiency always carries a reason | Current contract has no `NO_FILLER` anti-laundering rule — G-6 **fails** | §7 gains the **Anti-laundering** gate row; the rule renders Q6 · obligation 2 (§5.5) — G-6 **passes** |
| **G-7 · No-new-law** | Every vocabulary element traces to an existing invariant (obligation 3 → INV-KOS-UNKNOWN-001 · §9 ABSENT rendering · Article 9) | holds; re-verified after the edit | Each new row cites its rendered invariant — G-7 **passes** |
| **G-8 · Regression** | Obligations 1–6 invariant renderings unchanged; Q1–Q8 still hold (Q2/Q4 reconciled); §5 trust boundary unchanged | holds; re-verified after the edit | Q2/Q4 reconciled; trust boundary intact — G-8 **passes** |

**RED→GREEN discipline:** the RED state (current text failing G-1·G-2·G-3·G-5·G-6) is the *motivation* — it is exactly the P5-measured conflation (`unknown_vs_not_expressed`, 110/110; v0.1 0.247 collapse). The slice records the RED baseline, performs the edit, then verifies GREEN with per-gate citations.

---

## 3 · The two remaining implementation questions — answered from repository evidence

### Q-C — one implementation slice (AH-1 + AH-3 together), or two sequential slices?

**Repository evidence:**
1. **The declaration space is a single composition closure** (approved plan §5.3 · gate G-3): `{NO_FILLER} ∪ {FILLER_UNKNOWN × (PARSE_UNAVAILABLE | READING_UNDERDETERMINED)}`. The `reason` dimension has **no standalone existence** — it qualifies the insufficiency case only. Two slices would land an intermediate state in which the vocabulary is **structurally incomplete** (e.g., after an AH-1-only slice: `declared insufficiency`/`declared determination` exist but `FILLER_UNKNOWN` lacks its `reason` dimension).
2. **The contract sites are shared** (approved plan §5.6): §3 Q2 · §4 vocabulary table · §6 OQ-1 are the same rows. Two slices = two passes over the same rows — churn, and drift risk between passes.
3. **Gates G-2 and G-3 are unverifiable on a partial vocabulary** — orthogonality and composition closure require both dimensions present.
4. **P5's own discipline** verified the four-way separation at once (v0.2 gate 14/14) — the complete separation, not a partial one.
5. The approved plan §11 Q-C already **recommends one slice** ("the dimensions compose; splitting would leave a partially-structured vocabulary").

**Evidence-derived recommendation (Q-C): ONE slice** — AH-1 + AH-3 together, exactly as the approved plan's §5.6/§7 scope.

### Q-D — record OQ-1 as resolved during that slice, or separately?

**Repository evidence:**
1. **v1.1 §20 defers OQ-1 "to the Logical Architecture."** The Port Contract **is** that Logical-Architecture deliverable — it is the artifact that answers OQ-1.
2. **The AH-1 ruling IS the HPA's rule on OQ-1's direction** ("declared insufficiency must distinguish no-filler from filler-unknown" → it has its own vocabulary). The slice records a **made ruling**, not a new one — zero extra authority.
3. **The contract currently reads** (OQ-1, §6): *"does declared insufficiency need its own vocabulary? This contract adopts 'declared insufficiency' as the port vocabulary (obligation 3). **Recorded; the HPA may rule.**"* After the §4 edit answers it, leaving this line stale would leave the **same document internally inconsistent** — §4 shows the sibling vocabulary, §6 still says "the HPA may rule."
4. The approved plan §5.6 **already includes** the §6 OQ-1 record in the slice.

**Evidence-derived recommendation (Q-D): RECORD OQ-1 in the same slice** — as the approved plan §5.6 specifies. It is an annotation of an already-made ruling; separating it creates a stale open-question marker in the document it answers.

---

## 4 · Scope boundaries (preserved — the slice touches nothing else)

- **No code** · no schemas · no APIs · no classes · no storage (DEF-5) — the refinement stays at Logical-Architecture altitude.
- **No v1.1** · no Constitution change · no aggregate change · no Kernel · no SNF implementation · no corpus/research reopening · no OQ-4 (experiment) · no mechanism implementation (no mechanism exists yet).
- Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · the six obligations' invariant renderings unchanged · §5 trust boundary unchanged · the six prohibitions (Q7) unchanged.
- **Not in the slice:** OQ-2 · OQ-3 · OQ-5 · F-1…F-5 · AH-5 (deferred) · AH-2 (corroboration) · AH-4 (separate governance recording). Their rulings remain the HPA's, separately.
- **After the slice (NOT this act):** EP-02 independent completion review (R-34 — engineering never accepts its own work) · v1.1 reassessment (expected: v1.1 remains; measured, not assumed).

---

## 5 · The decision (HPA) — ⬜ OPEN

```text
EP-01 T-2/T-3 IMPLEMENTATION AUTHORIZATION — decision fields OPEN

  ☐ Authorize T-2 (Port Contract edit per §5.6, seven sites)
  ☐ Authorize T-3 (gates G-1…G-8, RED → GREEN, committed verification)
  ☐ Q-C:  ONE slice (recommended)   /   two sequential slices
  ☐ Q-D:  record OQ-1 IN the slice (recommended)   /   separately
  ☐ Boundaries: preserve as §4

Issuing this authorization is a separate HPA act. On authorization, the slice is
executed per this package and the approved plan, then STOPS again — EP-02 (independent
completion review) and the v1.1 reassessment are subsequent, separately commissioned acts.
The Kernel still waits.
```

---

## 6 · STOP

This package is **preparation only**. **No contract edit. No code. No implementation.** The Port Contract and the repository's code are **untouched** by this act. The HPA issues the formal T-2/T-3 implementation authorization (§5), after which — and only after which — the slice is executed.

---

## Traceability

- **Commission:** HPA, 2026-08-23 — implementation-authorization preparation directive: *"inspect the approved EP-01 plan, prepare the authorization decision package for T-2/T-3, answer Q-C/Q-D from repository evidence where possible, and stop without modifying the contract or code."* Recorded verbatim-in-substance (§0). Supersedes, in this turn, the earlier message reading *"Authorize the T-2/T-3 implementation slice per the approved plan"* — the later explicit directive governs.
- **Basis:** the **APPROVED** EP-01 plan (`docs/plans/20260822-2354-...-plan.md`, approval commit `664fb2da`, HPA option C) — §5.1 (two sibling declarations) · §5.3 (composition closure) · §5.4 (no broadening) · §5.5 (anti-laundering) · §5.6 (seven contract sites) · §7 (gates G-1…G-8) · §8 (T-1✅ · T-2/T-3 ⬜) · §11 (Q-A/Q-B resolved; Q-C/Q-D deferred to the implementation authorization) · §12 (stop-for-authorization).
- **Terminology basis:** Q-B review `docs/knowledgeos/reviews/20260823-0015-KOS-EP01-QB-terminology-review-declared-insufficiency.md` (commit `e5cab7c9`) + HPA decision §6 (option C).
- **Contract to be edited (when authorized):** `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` (DELIVERED · PROPOSED · NON-AUTHORITATIVE) — §1 · §2 obligation 3 · §3 Q2/Q4 · §4 table · §6 OQ-1 · §7 gates.
- **Authoritative grounding:** Reference Architecture v1.1 (§9 seven states · §10 ⟨A-2⟩ · §14 ⟨C-5⟩ · §20 OQ-1…OQ-5) · Constitution v1.0 (Article 9) · P5 evidence (item 9 = 110/110 · item 2 = 0.247 collapse → four-way 14/14).
- **Discipline honored:** preparation-only — no contract edit, no code, no v1.1/Constitution/aggregate/Kernel/SNF/corpus change · **acceptance and act-authorization strictly separated** — plan approval ≠ implementation authorization; this package authorizes nothing · the strongest statement never exceeds the evidence (Q-C/Q-D answered from the repository, not inferred from preference) · register **25+4 unchanged** · Constitution **FROZEN** · **the Kernel still waits.**
- **Status:** 📋 **IMPLEMENTATION-AUTHORIZATION DECISION PACKAGE — DELIVERED · PREPARATION ONLY · NON-AUTHORITATIVE.** Authorization fields **⬜ OPEN (HPA)** · contract and code **untouched** · next: the HPA's formal T-2/T-3 authorization (§5).
