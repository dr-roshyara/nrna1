# ADR-AIP-03 — Recognize or Decline: Governed Session Orchestration as Bounded Context BC-7

| | |
|---|---|
| **Status** | ✅ **ACCEPTED — Option A (PO/ARB, 2026-08-17): BC-7 Governed Session Orchestration is recognized.** Primary decision signed in §7; **consequence decisions (a) CAP id and (b) role-model ownership remain UNDECIDED** — their block is still blank. Context-map update / Baseline v2 are the next architecture steps and require their own commissioning; nothing physical (folders, components, code) is authorized by this acceptance. |
| **Series** | ADR-AIP (AI Engineering Platform) |
| **Decision authority** | PO/ARB — a bounded-context recognition is a strategic model decision (`R-34`: engineering never accepts its own work) |
| **Drafted by** | Architecture terminal (`KOS-ATTR-ARCH-001` Stage 2), authority: generated — this draft decides nothing |
| **Source of the question** | Stage-2 Bounded Context Confirmation Report, ADR-C1 (`docs/knowledgeos/reviews/2026-08-17-KOS-ATTR-ARCH-001-stage2-bounded-context-confirmation.md`), reviewed by the Principal DDD Architect 2026-08-17 with the direction *"ADR-C1 first — every other boundary question touches it"* |
| **Evidence base** | Accepted Phase A v1.1 baseline (content `40026b12`, acceptance `378f6eaa`) — the sole measured evidence; Stage-2 judgments are `Inferred` and marked |
| **Related** | ADR-AIP-01 (frozen six-context model) · ADR-AIP-02 (Product Primacy) · `ES-001.1` (parsimony — the recorded counter-authority) · Stage-2 OQ-5, ADR-C2/C3/C7 |

---

## 1 · Context

The accepted current-state baseline established (`Observed`/`Declared` as marked there):

* A **de facto orchestration area** exists — work items, session assignments, roles, transitions, grants, handoffs, human acts, mutation ownership — whose language **appears in none of the six declared contexts' ubiquitous-language sections** (baseline §3.2).
* It has its **own aggregate** (Work Item/Workflow — baseline §7, `Inferred`/medium), **own invariants** (I-1 single mutation owner · I-2 HANDOFF ∧ human START · I-3 sticky STOPPED · I-10 record outranks prose), **own persistence** (one JSON per work item), and its **own interpretation authority** (`AST-015`).
* Its mechanism was **deliberately placed inside CMP-004** (`workflow_engine`, BC-2 Implementation Guidance) by the Increment-1 boundary decision under `ES-001.1` parsimony and the accepted "no new independent subsystem" direction (`Declared`).
* The platform's most consequential executable capability — **authority registration & audit** — has **no declared `CAP-` identifier and no declared context** (baseline §4, `Observed`).

Stage 2 (2026-08-17) weighed the strategic evidence and **promoted this area to candidate BC-7** (confidence `Inferred`/medium), noting the classic smell: **two models sharing one physical component** (CMP-004 hosts the BC-2 guidance language *and* the orchestration language). The Principal DDD Architect's review concurred that BC-7 is a serious candidate and that recognition **requires this ADR, not analysis** — because it would revise a recorded decision (the Increment-1 placement) and amend a frozen model (ADR-AIP-01's six contexts).

## 2 · Decision required

**The primary decision, and nothing else, is decided first** *(Principal-review refinement, 2026-08-17: establish the model boundary before assigning technical identifiers — otherwise the board debates CAP numbers before agreeing on the domain line)*:

> **The current six-context model is incomplete: the capability the platform is actually organized around has no declared boundary. Does the PO/ARB recognize the missing strategic boundary — *Governed Session Orchestration* — as bounded context BC-7?**

**Consequence decisions — sequenced strictly AFTER the primary decision, not bundled with it:**

* **(a) CAP id** — the executing orchestration capability receives a declared `CAP-` identifier. Taken after A (assigned under BC-7); under B it is taken as part of the BC-2 language extension; under C the measured undeclared-capability gap persists and must be named in the deferral.
* **(b) Role-model ownership (Stage-2 OQ-5)** — is the role model (`§9` AI Responsibility Model) BC-7's own language, or a governance-published language BC-7 consumes? Meaningful only after A; moot under B; deferred under C.

## 3 · Options

### Option A — RECOGNIZE BC-7 *(Stage-2 recommendation, `Inferred`/medium)*

The context map gains **BC-7 Governed Session Orchestration**: work items, session assignments, roles, transitions, grants, handoffs, human-act registration, mutation ownership. **This is not "adding a seventh box" — it is completing an incomplete model: the current six-context map lacks a boundary for a model that already exists, executes and holds its own invariants.** ADR-AIP-01's context table is amended accordingly (not silently — by this ADR's acceptance).

**Subdomain classification, stated explicitly to prevent future confusion:** BC-7 is a **Supporting Subdomain / platform capability**. It enables governance of engineering work; it does not become — and must never drift toward — the Product Core Domain, which remains the Election system (`AIP-14`, ADR-AIP-02).

* **For:** complete, self-consistent, disjoint ubiquitous language (`Observed` about the documents) · own aggregate/invariants/persistence/interpretation authority · distinct change-reason (governance-process changes, never tripwire changes) · it gives the platform's actual centre of gravity a first-class home · it dissolves the two-languages-in-one-component smell at the model level.
* **Against:** reverses the recorded `ES-001.1` parsimony placement · a seventh context for a mechanism that is two scripts and one record type may over-weight strategic structure relative to executable substance (the baseline's own thesis: rules dense, code thin).
* **Consequences if chosen:** the context map and ADR-AIP-01 table are updated; a `CAP-` id is assigned; CMP-004's contents are *re-labelled* in the model (mechanism assets belong to BC-7). **Expressly NOT authorized by choosing A:** no folder moves, no component splitting, no code changes, no new hooks, no re-registration of assets — every physical change is separate future work under its own authorization (`R-37`).

### Option B — DECLINE, and formally EXTEND BC-2's declared language

The orchestration vocabulary is added to BC-2 Implementation Guidance's ubiquitous-language section, making the Increment-1 placement linguistically honest instead of silent.

* **For:** preserves the parsimony ruling and the frozen six-context model · zero structural churn.
* **Against:** merges two languages the baseline measured as disjoint — "guidance" (advisory tripwires, Tier-2, non-binding) and "authority orchestration" (grants, human acts, binding record) would share one model. A context whose language spans *suggestions* and *authority* has two reasons to change, which is the defect bounded contexts exist to prevent (`Inferred`). The Stage-2 centre-of-gravity finding (T-1 homeless) would be answered by widening a boundary rather than drawing one.
* **Consequences if chosen:** ADR-AIP-01's BC-2 row is amended with the extended language; a `CAP-` id is still assigned (under BC-2); Stage-2's M-1 (misplacement) is re-classified *resolved by declaration*.

### Option C — DEFER

Record the candidate and decide after more evidence (e.g., after the next orchestration-mechanism change forces the seam).

* **For:** no evidence pressure demands a decision *today*; the mechanism is stable.
* **Against:** ADR-C2, C3 and C7 all touch this boundary — deferral cascades into three other pending decisions (the review's own reason for "ADR-C1 first"); and the undeclared-capability gap (§2a) persists, leaving the platform's capability model measurably incomplete.
* **Consequences if chosen:** sub-decision (a) — the CAP id — should still be taken now to close the measured gap; the boundary question re-enters at a named trigger, which this option must specify to avoid an unbounded deferral.

## 4 · Recorded positions *(positions, not adoptions)*

* **Stage-2 Architecture recommendation:** Option A, confidence medium — with the counter-authority stated rather than argued away.
* **Principal DDD Architect review (2026-08-17):** BC-7 "should become a serious candidate"; do **not** split physically yet; sequence = ADR decision → context-map update → domain model → implementation. Also recorded, expressly as a leaning and **not** decided here: on BC-4, *"BC-7 owns verification workflow; BC-4 provides review policy"* — that is ADR-C7's question and is untouched by this ADR.
* **Principal DDD Architect review of THIS DRAFT (2026-08-17, second pass):** verdict *"create ADR-AIP-03"*, with three refinements — **applied to this document before signature**: ① the primary boundary decision separated from the CAP-id/role-model consequence decisions (§2, §7) · ② "seventh context" reworded as *completing an incomplete model / a missing strategic boundary* (§3 Option A) · ③ BC-7's subdomain classification stated explicitly: Supporting Subdomain, never the Product Core (§3 Option A). The review also assessed **Option A as currently the strongest DDD choice** on the language/ownership/change-reason tests — recorded as the reviewer's assessment, not as the decision.

## 5 · What this ADR does NOT decide

BC-4's nature (ADR-C7) · the composition-root census and the declared-Core question (ADR-C2) · registry-first scope (ADR-C3) · knowledge-system boundary (ADR-C4) · artifact-store boundary (ADR-C5) · any hardening of the authority store or writer boundary (ADR-C6, `R-37`-gated) · **any implementation, folder structure, technology, or physical component change under any option.**

## 6 · Fitness check against standing principles

`ES-001.1` parsimony — Option A must justify a new declared structure: the justification offered is that the structure already *exists and executes*; recognition adds a name, not a subsystem. `R-37` — no mechanism is created by any option. `AIP-14` Product Primacy — unaffected; the platform remains a Supporting Subdomain either way. `I-4` (registry ≠ authority, never merged) — unaffected by all options.

## 7 · Decision *(PO/ARB — blank until signed)*

**Primary decision — the boundary, alone:**

> ☑ **Option A — recognize BC-7 (Supporting Subdomain)** · ⬜ Option B · ⬜ Option C
>
> Signed: **PO/ARB** · Date: **2026-08-17** *(decision delivered in the PO/ARB's own words; registered verbatim below)*

**The decision record, verbatim (PO/ARB, 2026-08-17):**

> *"☑ Option A — Recognize BC-7 Governed Session Orchestration. The PO/ARB recognizes Governed Session Orchestration as a distinct bounded context in the AI Engineering Platform context map. The decision is based on the following architectural evidence: It has its own ubiquitous language: work item · assignment · grant · handoff · human act · mutation ownership · lifecycle transition. It has distinct invariants: one active mutation owner · authorized handoff before transfer · human act required for authority transitions · recorded state overrides narrative state. It has a different change reason from BC-2 Implementation Guidance. Combining implementation guidance and authority orchestration creates two different models inside one boundary. **Therefore the previous BC-2 placement is recognized as a physical implementation placement, not the final strategic domain boundary.**"*

**Registration notes (Architecture terminal, 2026-08-17):** ① the act decides the **primary boundary question only** — the consequence block below remains blank, per this ADR's own sequencing; ② the closing clause is load-bearing and is read exactly as written: the Increment-1 placement of the mechanism inside CMP-004 **stands as a physical placement** — recognition changes the *model*, and any physical change remains future work under its own authorization (`R-37`, §3 Option A consequences); ③ BC-7 enters the context map as a **Supporting Subdomain** per §3 Option A's classification.

**Consequence decisions — taken only after the primary decision is signed, per its outcome (§2):**

> (a) CAP id: ⬜ assigned as ________ under ⬜ BC-7 / ⬜ BC-2 · (b) role-model ownership: ⬜ decided: ________ / ⬜ expressly deferred to: ________
>
> Signed: ________________ (PO/ARB) · Date: ________

---

**Traceability:** Stage-2 report ADR-C1/OQ-5 (`f4eb4f76`) · accepted baseline v1.1 §3.2/§4/§5.1/§7/§8/U-3 (`40026b12`, acceptance `378f6eaa`) · Principal DDD Architect review 2026-08-17 (this thread) · ADR-AIP-01 · `ES-001.1` · `R-34` · `R-37`.
