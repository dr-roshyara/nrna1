# 03 — Operation and Transformation Readiness

Answers mandate **§7** (operational semantics) and **§8** (transformation semantics).
**No candidate registry is selected.** Substantially overlaps `analysis/OPERATION-CONTRACT-GAP.md`
and `analysis/TRANSFORMATION-CONTRACT-GAP.md` (GN-77) — this adds the provenance table §7 requires
and the verified §8 audit.

---

## §7 — Operational semantics

### The governing status — GN-84, four statements, never merged

| Question | Status |
|---|---|
| Does a minimal operation registry exist under the tested criterion? | **YES** |
| Is the minimal registry **unique**? | **NO — six found** |
| Can one be **selected as canonical** from current evidence? | **NO** |
| Is the operation registry **ratified**? | **NO** |

### Binding terminology — GN-84, permanent

$$\boxed{\text{“minimal under the chosen computational criterion”}\;\neq\;\text{“minimal canonical operation set of KnowledgeOS”}}$$

> The first has been shown **non-unique**. The second has **not been demonstrated at all.**
> They may never be substituted for one another.

**This document observes that separation throughout.**

### Candidate registries — provenance, per §7's required fields

| # | Candidate | Provenance | Status | Derivation method | Executable | Mathematical justification | Independent? | Governance | Dependencies |
|---|---|---|---|---|---|---|---|---|---|
| R1 | `O_sem` = 19 ops / 5 families `O_S∪O_E∪O_O∪O_H∪O_G` | Step 272A | **PROPOSED** | semantic decomposition by mathematical role | no | category separation (`Assert` vs `Query` vs `Authorize` vs `Serialize`) | — | none | — |
| R2 | `{Add,Remove,Revise,Transform,Supersede,Merge,Split,Reject,Withdraw}` | Step 256.2 | **PROPOSED**, 5 of 9 untyped | corpus vocabulary reconstruction | no | none — §256.2 calls it *"a reconstruction target"* | — | none | identity, equality |
| R3 | R2 + `{Validate,Assess,Promote,Reintroduce,Replay}` | Step 259.7 | **PROPOSED** | 5-class partition + congruence rule | partial | §259.8 restriction is derived | — | none | — |
| R4 | `𝒯_candidate = {Assert,Retract,Supersede,Merge,Split,LinkEvidence}` | Step 277 | **PROPOSED** — `𝒯_candidate ≠ 𝒯_minimal`, *"not yet proven minimal"* | transformation inventory | partial | Classification CLOSED / **Minimality OPEN** | — | none | `ℛ` policy for `Split` |
| R5 | **six rival minimal registries** | GN-83 derivation | **RECOMMENDATION PENDING FALSIFICATION** (GN-84) | minimality under a computational criterion | **yes** | minimal under *that* criterion; **non-unique** | falsification pass run (GN-86) | **NOT ratified** | ladder granularity · CONFLICTED entry · contradiction typing · `Reject` typing · 3× C-5 |
| R6 | 14-forced / 18-upper bound | this lane | **WITHDRAWN** | non-collapse-law counting | — | **AF-F-33: PROPOSED, not DERIVED — may not be cited** | — | none | — |

**Six candidates; none ratified; the newest is explicitly non-unique.**

### Two unreconciled operations (handoff/02, verified)

| Operation | Step 277 | Executed algebra | Conflict |
|---|---|---|---|
| **`Split`** | in `𝒯_candidate` | **LOSSY on `ℛ`** — `Split;Merge` does not recover relations | cannot be a state primitive without a declared `ℛ` policy |
| **`LinkEvidence`** | in `𝒯_candidate` | absent — `e` is fixed at creation | would **mutate** an assertion, contradicting `ReplayAssertion` immutability |

### The membership criterion exists and has never been run

$$o \text{ is primitive} \iff \exists r \in R_{\text{mandatory}} : r \notin \mathrm{Closure}(\mathcal T_{-o})$$

**Step 277 states it. GN-75: *"the necessity test has never been run by anyone."***
And it cannot be run yet — `R_mandatory` is the invariant register, which is **G-67, never enumerated.**

> **The operation-necessity test is blocked on the same missing object as `Sufficient(K,𝒪,ℐ)`: `ℐ`.**
> That is a dependency neither lane has recorded.

### Per-property status

| Property | Status | Evidence |
|---|---|---|
| `𝒪` universe | **PROPOSED ×6** | above |
| `𝒪_core` | **NOT FROZEN · NOT RATIFIED** | GN-75, GN-84, handoff/02 |
| membership | **OPEN** | three non-agreeing lists (GN-75) |
| minimality | **OPEN** — exists but non-unique | GN-84 |
| uniqueness | **REFUTED** | GN-84: six found |
| signatures | **0 in canon** (C2) · present in Lane B only | verified |
| preconditions | **1 in canon**, and not an operation's (C4) | verified |
| postconditions | **0 in canon** (C3) | verified |
| failure semantics | **ABSENT from canon** — a C-5 prerequisite | GN-77/84 |
| composition | **not demonstrated closed** | Step 256.18 |
| replay | formal only; no platform replay | registry #19 |

**Classification: `O` + `T`. `BLOCKED — REQUIRES GOVERNANCE` (registry) and `BLOCKED — REQUIRES
DERIVATION` (`ℐ`, then the necessity test).**

---

## §8 — Transformation semantics

**Audit result. `FD` formally derived · `RAT` ratified · `PROP` proposed · `ABS` absent.**

| Element | Lane B | **Ratified canon** | Prerequisite to define it |
|---|---|---|---|
| **`δ`** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` — **TG-09: no body for commit**, executed `K₁ is K₀` | **ABS** — 15 `transition` refs, **0 signatures** | `𝒪_core` + state identity |
| **preconditions** | in reference impls | **1 hit, not an operation's** (C4) | closed operation registry |
| **postconditions** | in reference impls | **ABS — 0 hits** (C3) | **state identity + equality rule** (C-5) |
| **partiality** | `⇀` used; §256.16–17 frames domain restriction | **ABS** | operation registry |
| **identity** | `id=H(P,e,c,t,Π)` — **TG-06: hashes mutable `e.state`** | **ABS** | resolve TG-06 |
| **equality** | 4 notions, none ruled | **ABS** | governance ruling |
| **lineage** | `Π ∘ ℛ_der*` — **FD**, implemented | **ABS** (C1) | — |
| **history** | external, `FD` | **ABS** (C1) | — |
| **evidence** | 9-field, no identity (TG-08) | **ABS** | evidence identity |
| **authority** | relation `Auth(a,r,c,p)` — `RAT` in a narrow sense | **PARTIAL** — 39 refs | `AuthorityAct` (TG-01) |
| **policy** | `(id,ver,Gates,Validity,Resolution)` | **✓ RAT** GN-19 | — *the only one* |
| **rejection** | **AF-F-31**: `Reject` reaches a ratified-required state **only by breaching I-12 and Art. 8** | **CONTRADICTORY** | **an architecture/governance ruling — explicitly not to be solved while defining operations** |
| **replay** | `fold(T,∅,H)`, `FD` | **ABS** | `δ` body |
| **serialization** | **excluded** from `O_sem` as representation (272A) | **ABS** | — |
| **composition** | not closed (256.18) | **ABS** | operation registry |

### The three C-5 prerequisites (GN-77/84) — they block the *contracts*, not the *test*

1. a **closed operation registry with a membership criterion**
2. **typed rejection / failure semantics**
3. a **state identity + equality rule** — *"postconditions are undecidable without it"*

### The sharpest single statement, verified

$$\boxed{\text{9 capabilities are canonically REQUIRED. 0 operations are canonically DEFINED.}}$$
$$\text{99 contract cells } (9\times11):\quad \textbf{2 fixed} \cdot \textbf{9 partial} \cdot \textbf{88 empty}$$

**`Reject` is the one element classified CONTRADICTORY rather than absent** — a ratified-required
state reachable only by an operation that breaches two ratified rules. **Not repaired here** (mandate
§2), and per GN-84 not to be repaired while defining operations.

---

## Where the implementation chain breaks (mandate §6, verified rather than assumed)

```
Objects        PARTIAL   25/25 formally defined; 16 not in the ratified surface
Types          PARTIAL   Proposition 2 rival types (D-6)
Identity       PARTIAL   defined; TG-06 hashes a mutable field
Equality       PARTIAL   4 notions, none canonical
State          PARTIAL   two rival K, disjoint vocabularies (C1)
Invariants     PARTIAL   41 in canon — but ℐ never ENUMERATED (G-67)
──────────────────────────────────────────────────────────────────────────
Operations     ✂ SEVERED   0 canonically defined; 6 rival registries; none ratified
Transitions    ✂ SEVERED   0 postconditions in canon (C3); δ has no body (TG-09)
──────────────────────────────────────────────────────────────────────────
Preconditions  ABSENT     1 canon hit, not an operation's
Postconditions ABSENT     0 canon hits
Failure        CONTRADICTORY  Reject vs I-12 + Art. 8 (AF-F-31)
Composition    ABSENT
Replay         formal only
Serialization  excluded by design
Evidence       PARTIAL    no identity (TG-08); Qualify has no body (TG-14)
Governance     PARTIAL    1/25 acts; GC-1 open
```

**The mandate warned against assuming the break is "operations". Verified: the break is at
operations AND transitions, and the transitions break is the harder one** — because a registry can be
ratified by an act, whereas **postconditions cannot be written until state identity and equality are
ruled** (C-5 #3), and identity is itself contested (TG-06).
