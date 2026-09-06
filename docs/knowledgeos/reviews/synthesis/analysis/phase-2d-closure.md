# PHASE 2D · Closure of the Two Critical Wiring Gaps

**Scope (as authorized):** 2D-1 authorization closure · 2D-2 determination closure · 2D-3 Ω closure ·
2D-4 chain re-run. Narrow; no new concepts invented; ⟪SUGGESTS⟫/⟪REQUIRES⟫ discipline maintained.

---

## 2D-2 · Determination closure — **OQ-03 VERIFIED: Step 008 IS the host** ⟦READ⟧

`step-008 — Epistemic Acceptance and Commitment` supplies exactly the structure R1 demanded:

**The status ladder:** ⟦C⟧ `Candidate → Supported → Accepted → Committed` (four epistemic statuses).

**The split R1 named, formalized:** ⟦C⟧ *"**The evidence tells us what the evidence supports. The
acceptance policy determines what KnowledgeOS is allowed to admit as accepted knowledge.**"*

⟦INT⟧ Vocabulary mapping, now evidence-backed:

| R1 (2026-08-25) | Step 008 (2026-08-27) |
|---|---|
| *extraction retrieves material* | evidence support → `Supported` |
| *determination establishes what it warrants asserting* | `AcceptancePolicy` → `Accepted` |
| (implicit) | authority → `Committed` |

**Verdict:** `Evidence → Determination` **is formally represented** as
`Supported → Accepted` under `AcceptancePolicy`. **GAP-2: CLOSED.**
DDD hosting question (service/aggregate/policy): per the gate, **not chosen** — but the corpus's own
term is *policy* (`AcceptancePolicy`), recorded as the ⟪SUGGESTS⟫-level answer.
⟦INT⟧ Note: Determination is **purpose-dependent** (⟦C⟧ *"commitment is purpose-dependent"*, §7) —
which wires it back to the Knower-owned purpose exactly as the dependency graph predicted.

---

## 2D-1 · Authorization closure — **the corpus contains the binding, distributed across three documents; no single source composes it**

Three ⟦READ⟧-verified components:

**(i) Epistemic level — Step 008, axiom A6:**
> ⟦C⟧ *"**Authority determines commitment, not evidential truth.**"*
The `Committed` status is authority-gated *by axiom*. ⟦INT⟧ This is the strongest single sentence in
the corpus for GAP-1: it states, at the formal layer, that the last epistemic transition belongs to
authority, not to evidence.

**(ii) Decision level — Step 042, the Decision Contract:**
⟦C⟧ `DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence)` with the governing triple non-collapse
⟦C⟧ *"**SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction**"*, the governance invariant
⟦C⟧ *"ProductionChange → RequiredApproval"*, mapped to a governance bounded context
(⟦C⟧ `Invariant_2 → BC_Governance`), and falsification experiment 12: ⟦C⟧ *"a decision passes
technical assurance but lacks governance authorization"* ⇒ blocked.
⟦INT⟧ So `Auth` is a **formal slot in the decision contract**, filled by governance rules — not a
free-floating predicate after all. Phase 2C read `025h` in isolation; `042` is where the slot is
structured.

**(iii) Authority apex — R3/Q18 (already established):** the Knower owns purpose, Ideal State and
final decision; governance approval requirements are the institutional carrier (and their *absence
in the real repository* is precisely Step 121's `CRITICAL GOVERNANCE GAP` — the conformance finding
and this closure corroborate each other).

**The composed binding the corpus supports:**
```
Authorized(x)  ⇐  DC(x).Auth  ⇐  Governance rules (BC_Governance)  ⇐  Knower authority
     (025h)          (042)              (042, 025f, 121)                 (151244, Q11/Q18, 008-A6)
```
⚠ **Epistemic status: SUPPORTED BY COMPOSITION** — every link is ⟦READ⟧-verified in its own
document, but **no single corpus document states the chain end-to-end**. This review assembled it.
That is a new evidence category, deliberately weaker than ESTABLISHED and stronger than hypothesis.
**GAP-1: CLOSED at composition level; the end-to-end statement remains for Phase 3 to formalize.**
⟪REQUIRES⟫: the intermediary structure (not the direct `Authorized(x) ⇐ Authority_Knower(x)`) is
what the corpus supports — the Knower authorizes **through** governance and the decision contract,
never by ad-hoc predicate.

---

## 2D-3 · Ω closure — classifications recorded; terminology recommendation

| Component | Final classification | Carrier in current model |
|---|---|---|
| Ω-a ideal reference | **ABSORBED** | IdealState → Epistemic Contract (EC) |
| Ω-b unboundedness | **COMPUTATIONALLY BOUNDED AWAY** (class B; adequacy question remains) | none — GN-07 residue |
| Ω-c whole-space observation | **TRANSFORMED INTO ITS COMPLEMENT** | `X_t ≠ Observed(X_t)` (Step 66) |

**Recommendation (HPA decides):** **Ω becomes historical terminology** — retained in the discovery
history with full lineage; the ubiquitous language carries `X_t`, `EC`, `IdealState` instead. The
foundational distinction to preserve in the eventual model is the gate's: ⟦C-gate⟧ `X_t ≠ Observed(X_t)`
— *model ≠ reality* held structurally.

---

## 2D-4 · Chain re-run

```
Authority ──► Reference ──► Intake ──► Evidence ──► Determination ──► State/Zero ──► Proposal ──► Decision/Auth ──► Action
 (Knower)      (G, EC)      (src≠sem)   (EXP-01)     (008: policy)      (049/025d)     (025g)       (025h+042 DC)     (025z)
```

| Transition | Status after 2D |
|---|---|
| Authority → Reference | supported (purpose→G; IdealState→EC; **008 §7 adds purpose-dependence of commitment**) |
| Reference → Intake → Evidence | supported (unchanged) |
| **Evidence → Determination** | **now formally supported** (`Supported → Accepted` under `AcceptancePolicy`) |
| Determination → State | supported (statuses are state components; Committed gated by A6) |
| State → Proposal → Decision | supported (unchanged) |
| **Decision → Action** | **now structurally supported** (DC.Auth slot; exp-12 blocks unauthorized) |
| Action → Execution | ⚠ remains soft (unchanged; low risk) |
| Authorization end-to-end | ⚠ **SUPPORTED BY COMPOSITION** — the one assembled link |

**Answer to the gate question:** after 2D, **no unsupported transitions remain.** Two qualified ones:
the action/execution joint (soft, low-risk) and the authorization chain (composition-level, assembled
by this review). Nothing requires a new concept.

---

## Governance state proposed for ratification

| Gate | Status |
|---|---|
| GAP-1 authorization binding | 🟡 **CLOSED-BY-COMPOSITION** (was 🔴) — end-to-end formalization is Phase-3 work |
| GAP-2 determination hosting | ✅ **CLOSED** (Step 008 verified; OQ-03 resolved) |
| Ω decomposition | ✅ closed; Ω-b adequacy question remains (GN-07 narrowed) |
| Phase 3 (architecture synthesis first, book later) | **decidable at next review** |
| Book | 🔒 not authorized |

**STOP. Awaiting the Phase-3 gate decision.**
