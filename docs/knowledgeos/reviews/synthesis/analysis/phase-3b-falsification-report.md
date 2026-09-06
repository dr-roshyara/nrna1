# PHASE 3B · Formal Consistency Review — FALSIFICATION REPORT

**Mandate:** treat `model/canonical-architecture.md` as candidate, not truth; construct the seven
formal representations; **attempt to falsify**; classify every relation; propose smallest repairs
**without applying them**. This is a falsification report, not a confirmation report.
**Relation grades:** `EVIDENCE-DERIVED` · `COMPOSED` · `REQUIRED-BY-COHERENCE` · `HYPOTHESIS` ·
`CONTRADICTION` · `NOT ESTABLISHED`.

---

## 1 · Formal representations

### 1.1 Dependency graph vs temporal feedback graph (mandate test 1 & 7)

**Static dependency order (acyclic, verified):**
`Knower ≺ G ≺ IdealState ≺ EC ≺ ObservationRequirements ≺ Evidence ≺ Determination ≺ K_t ≺ Z_t ≺
Proposal ≺ Decision ≺ Authorization ≺ Action` — with `Authority(Knower)` entering twice: as **source**
(apex, static) and as **consumed constraint** (`DC.Auth`, runtime). ⟦INT⟧ Not a cycle: the apex
supplies a *standing structure*; the chain consumes an *instance*. Distinction formally kept:
`depends-on` (static) ≠ `feeds-back-to` (temporal). The loop `Action →(t+1)→ Observation` is
temporal-only. **PASS — with one exception, F-1 below.**

### 1.2 Type system (mandate test 2)

Types: `Reality(X_t) · Observation_src · Observation_sem · Evidence · Warrant · Status ∈ {Cand, Supp,
Acc, Comm} · K (state) · Z (typed discrepancy) · ProposalAction · Decision · Auth (constraint) ·
Action`. All canonical operations type-check except the two flagged in F-2 and F-3.

### 1.3 Status-transition algebra (mandate tests 3 & 4)

```
Cand → Supp   requires Evidence           (008)        EVIDENCE-DERIVED
Supp → Acc    requires AcceptancePolicy   (008)        EVIDENCE-DERIVED
Acc  → Comm   requires Authority          (008 A6)     EVIDENCE-DERIVED
Decision path: DC(d)=(Pre,Inv,Auth,Post,Temporal,Evidence)  (042)  EVIDENCE-DERIVED
Auth ⇐ BC_Governance ⇐ Knower                              COMPOSED (2D-1)
```
Shortcut attacks: *evidence alone → Committed* blocked by A6 ✅ · *authorization → Accepted* not
granted anywhere ✅ (but see F-1's policy loophole) · *valid decision → epistemic truth* blocked by
I-3/I-4 ✅ · *technically valid action bypassing governance* blocked by 042 exp-12 ✅ (within audit
scope) · ***Candidate → Committed* directly: see F-4.**

---

## 2 · FALSIFICATION FINDINGS — four defects

### F-1 · **Self-referential admission: `Policy` is both inside K_t and the governor of admission to K_t** ⚠ strongest finding

⟦OBS⟧ `Policy` is one of the eight M₄₉ primitives (⟦READ⟧ 050 §50.1) — policies are knowledge items
*inside* the state. ⟦OBS⟧ `AcceptancePolicy` governs what may *enter* the state (008). ⟦OBS⟧ **No
corpus document states who owns, versions or admits the AcceptancePolicy itself** (grep-verified: 0
ownership statements in 008).
⟦INT⟧ Consequence: a policy admitted under a policy — an unstratified self-reference. Attack: a
permissive policy could admit its own successor, silently weakening admission — **the epistemic twin
of Step 121's governance gap** (*"constitution can be silently weakened"*).
**Classification:** ARCHITECTURAL failure · relation `AcceptancePolicy → K_t` membership:
`NOT ESTABLISHED`.
**Smallest repair (NOT applied):** stratify — policy-change is a *governed decision* (routed through
DC + BC_Governance) with **versioning**, exactly what the corpus already demands for the constitution
(⟦C⟧ 121.47: *"the constitution therefore needs Version"*). Repair is corpus-adjacent, not invented.

### F-2 · **The G-vs-EC division of labour in `Zero(K,G,EC)` is not established**

⟦OBS⟧ 025d: ⟦C⟧ *"Zero cannot be computed from the goal alone"* (so EC adds content) — but EC is
*derived from* G (025e), and no document states what G contributes to Zero **beyond** EC. Proposal
(025g) likewise consumes G directly.
⟦INT⟧ Either the derivation G→EC loses information (unstated) or the signature is redundant
(unstated). **Classification:** MATHEMATICAL under-specification · `G → Zero` residual role:
`NOT ESTABLISHED`.
**Smallest repair (NOT applied):** either prove `EC = η(G)` total and reduce to `Zero(K,EC)`, or
document G's residual role (e.g. prioritization vs requirement).

### F-3 · **`Accepted → Committed` crosses the epistemic/decision boundary inside a ladder labelled "epistemic"**

⟦OBS⟧ 008 titles all four statuses ⟦C⟧ *"epistemic statuses"*; A6 says ⟦C⟧ *"authority determines
commitment, not evidential truth."* ⟦INT⟧ So the final transition is **decision-boundary work**
performed inside an epistemic-boundary construct — precisely the *crossing without explicit
transformation* the mandate's test 5 hunts. Not a contradiction (A6 itself marks the difference) but
a **typing blemish with drift risk**: future work could treat `Committed` as epistemically stronger
than `Accepted`, which A6 forbids.
**Classification:** SEMANTIC failure (mild) · **Smallest repair (NOT applied):** re-type `Committed`
as a decision-boundary status (or split the ladder: epistemic `Cand/Supp/Acc` × commitment flag),
keeping A6 as the explicit crossing rule.

### F-4 · **No-skip axiom absent from the status ladder**

⟦OBS⟧ 008 presents `Cand → Supp → Acc → Comm` as a progression; **no document forbids skipping**
(grep-verified: 0 skip/bypass statements). ⟦INT⟧ `Candidate → Committed` by fiat is not formally
excluded — it is only *implied* by the per-step requirements.
**Classification:** MATHEMATICAL (minor) · **Smallest repair (NOT applied):** state the transition
relation as a covering relation (each status reachable only from its predecessor), one axiom.

---

## 3 · Invariant-transition matrix (mandate test 3) — summary

| Invariant | Preserved through chain? | Weak point |
|---|---|---|
| I-1 Knower ownership | yes at G/IdealState/Auth | ⚠ **hole at Determination**: AcceptancePolicy ownership unbound (F-1) — an unowned policy could admit against the Knower's frame |
| I-2 proposal ≠ decision | yes (025g/h explicit) | — |
| I-3 triple non-collapse | yes; tested by 042 exp-12 within scope | — |
| I-4 authority ≠ truth | yes | F-3's drift risk is the future hazard |
| I-5/I-6 evidence invariants | yes at aggregation (TESTED) | re-checking at Determination `NOT ESTABLISHED` (low risk — layered) |
| I-7 X_t ≠ Observed | structural | — |
| I-8 assertion-needs-evidence | method invariant | partially executed (EG-05 residue) |
| I-9 four-way Zero typing | yes (025d) | — |
| I-10 constitution-change approval | stated | **violated in real repo (121)**; F-1 is its epistemic twin |

## 4 · Boundary-crossing audit (mandate test 5)

Explicit transformations exist for: reality→observation (partial-observability model), observation→
evidence (Option 3 + normalization), computational→decision (DC contract). **The one unexplicit
crossing is F-3.** No other silent crossings found.

## 5 · Relation classification register (the chain, graded)

`Knower→G` EVIDENCE-DERIVED · `G→IdealState` EVIDENCE-DERIVED · `IdealState→EC` EVIDENCE-DERIVED ·
`EC→Zero` EVIDENCE-DERIVED · `G→Zero (residual)` **NOT ESTABLISHED (F-2)** · `Obs_src→Obs_sem`
EVIDENCE-DERIVED · `Evidence→Supported` EVIDENCE-DERIVED (TESTED layer) · `Supported→Accepted`
EVIDENCE-DERIVED · `Accepted→Committed` EVIDENCE-DERIVED **with F-3 typing caveat** ·
`Authorized ⇐ DC.Auth ⇐ BC_Gov ⇐ Knower` **COMPOSED** · `AcceptancePolicy stratification`
**REQUIRED-BY-COHERENCE (F-1 repair)** · no-skip axiom **REQUIRED-BY-COHERENCE (F-4 repair)** ·
`Action→Obs(t+1)` EVIDENCE-DERIVED (temporal) · action/execution split `NOT ESTABLISHED` (carried).
**CONTRADICTION: none found at canonical level** — the historical contradictions (CON-01/02) live in
the lineage, not in the synthesized model, because the model carries the formalized meanings with
lineage caveats attached.

---

## 6 · Verdict

> **The canonical model survives the falsification attempt as a structure — no contradiction, no
> illegal cycle, no silent boundary crossing except F-3 — but it is NOT yet formally closed: one
> architectural defect (F-1, self-referential admission), one under-specification (F-2), one typing
> blemish (F-3), one missing axiom (F-4).**

Per mandate: **all four repairs are proposed and none applied.** F-1's repair is corpus-adjacent
(121.47 versioning); F-2/F-3/F-4 repairs are REQUIRED-BY-COHERENCE, one axiom each.
⟦INT⟧ Note the pattern: F-1 and I-10 are the *same defect* at two levels (epistemic admission /
constitutional change) — the system's recurring weakness is **ungoverned self-modification**, found
independently by the corpus (121) and by this review (F-1). That convergence is the report's most
valuable output.

**STOP. Awaiting governance ruling on the four repairs. 3C and the book remain NOT AUTHORIZED.**
