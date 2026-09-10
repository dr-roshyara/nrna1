---
artifact: G-00 · SUPPLEMENT — after the verification-lane register inventory
date: 2026-09-10
status: **THREE of my own G-00 rows amended. One closure upgraded, one premise REFUTED, one reason corrected. Plus: the estate holds ~46 registers and TWO prefixes are used by two different schemes.**
supersedes: rows `G-01`, `G-06`, `G-07` of `01-G-00-RECONCILIATION.md` §2
---

# G-00 supplement — what the systematic inventory changed

A bounded worker inventoried the `verification/` lane's registers: **484 files, 464 text.** It
returned the complete `TG-*`, `C-*` and `16-MASTER-GAP-REGISTER` item lists plus twelve targeted
lookups. **Three of my rows are amended below; all amendments re-verified by me at the quoted
source before adoption.**

---

## 1. ⛔ `G-01` — the premise is not "too strong". It is **REFUTED**, and it asked about the **wrong argument**

`G-01` asked: *"Why did `Satisfied(K,r,EC)` lose its `EC` argument, and by what argument did a
9/10-valued codomain become 3-valued?"* **Both halves fail at the source.**

### (a) `EC` was never dropped — **`G` was**

`verification/spec/STEP-VERIFY-025a-025g.md` records **six non-mutually-reduced forms of `Zero`
inside `025d` alone**:

| # | form | §  |
|---|---|---|
| 1 | `Zero(K_t,G,EC_G) = {(r_i,Z_i)}` | 25D.6 |
| 2 | `Zero(K,G,EC) = R_G ∖ Satisfied(K,R_G)` — *"admitted only a shorthand"* | 25D.9 |
| 3 | `Zero = (Z_world, Z_knowledge, Z_governance, Z_decision)` — ⭐ **a 4-tuple object, no longer a function of `(K,G,EC)` at all** | 25D.16 |
| 4 | ⭐ `Z_t = Zero(K_t, EC_t)` — **two-argument, `G` DROPPED** | 25D.24, 25D.34 |
| 5 | `Zero(K,K*,EC) = EpistemicDistance(K,K*∣EC)` — ⭐ **`K*` replaces `G`**; `K*` never constructed | 25D.31 |
| 6 | `Z_t = ⊕_{r∈R_G} Evaluate(K_t,r,EC_G)` — ⭐ `⊕` **collides** with `⊕` = evidence combination from `25C` | 25D.37 |

$$\boxed{\textbf{The argument that was dropped is } G \textbf{, not } EC. \textbf{ } EC \textbf{ survives in five of the six forms.}}$$

### (b) The codomain never went 9/10 → 3. It went **9 → 10 → 11 and was never re-declared**

> *"25D.4: `𝒮 = {Satisfied, PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid,
> Prohibited, NotApplicable}` — **9 statuses**. 25D.6's own example vector emits `Missing` — **a
> value outside the declared codomain, used one section before it is introduced**. 25D.7 then adds
> `Missing` → 10. 25D.23 introduces `Failed` → 11. **`𝒮` is never re-declared**; the final codomain
> of `Status(K,r,EC)` is nowhere fixed."*

And the worker found **no document anywhere** linking a 9/10-valued satisfaction codomain to a
3-valued one — the separately-recorded 3-value object is **`Σ = {Unknown, Supported, Refuted}`**
(`DECISION-SIGMA-EPISTEMIC-STATUS.md`), a **different object**. My gap had silently joined them.

### (c) And `Satisfied` is a three-way homonym in one document

> *"`Satisfied` is both **an element of `𝒮`** and (25D.9) **a set-valued function**
> `Satisfied(K,R_G)`, and (25D.12) **a predicate** `Satisfied(K,r,EC)` — **three types under one
> name**."*

⭐ **The same defect as `Ω-1 × Ω-2`, inside one document, on one object, and already recorded in the
verification lane.**

| | before | after |
|---|---|---|
| `G-01` action | RE-SCOPE (premise "too strong") | ⛔ **RE-SCOPE — premise REFUTED.** Re-posed as: *(i)* why does `Zero` carry **six irreconcilable forms** in `025d`; *(ii)* why is **`G`** dropped at 25D.24 and replaced by `K*` at 25D.31; *(iii)* what fixes `𝒮` after 11 |
| my `Γ(E,Q,C,EC)` relocation claim | `[PROPOSED]` | **superseded and withdrawn as the framing** — `EC` was never lost, so nothing needs relocating |

---

## 2. ⭐ `G-06` — upgrade **RE-SCOPE → CLOSE**. The `≡_sem` half is answered too.

I recorded the `KAID`↔semantic-equivalence half as unresolved. **It is resolved, by a cited
supersession.**

`verification/spec/STEP-VERIFY-025h-025z.md`, verified verbatim:

| | |
|---|---|
| `025i` §37 | `KAID = Identity(CanonicalAssertion, Context)` — ***"explicitly provisional"*** |
| ⭐ `025s` §43 | ***"025s §43 supersedes §37's KAID definition (cited — one of the few honest supersessions in the batch)"*** |
| `025s` §43 | *"**Knowledge Atma is the stable semantic identity of an epistemic meaning within a bounded context**"*, with **`KAID ≠ RecordID ≠ EntityID`** |
| `025s` §36 | the three-level split **`EntityIdentity / KnowledgeMeaningIdentity / RecordIdentity`** — *"RESOLVES the three-level identity split"* |
| `025i` §11 | `x ≡_exact y`, `x ≡_struct y`, **`x ≡_{sem,C} y`** — the equivalence family, context-indexed |

$$\boxed{KAID \textbf{ IS bounded-context-scoped SEMANTIC identity — that is the relation to } \equiv_{sem}.}$$

**Residue, recorded, not closed:** *"`Context` inside `KAID` is undefined (**its granularity
determines everything**)"* — which is `16-MASTER`'s `G-15`, *"`Context` has no type, domain or
equality anywhere"*. ⇒ **`G-06` closes into an existing open gap rather than remaining its own.**

---

## 3. ⚠️ `G-07` — the closure stands; **my stated reason was wrong**

I closed `G-07` because `30-P13` withdrew the *"six-component vector"* reading. Correct, but the
identification was loose. The inventory shows:

| | |
|---|---|
| **`TG-02` actually is** | *"**`Sufficient` has no signature.** The parametricity defence is untested for *relational* sufficiency"* — and its body: *"`C_criminal` needs '≥3 independent sources'; `Evidence` has `source` but **no independence relation**"* |
| **its status today** | 🔴 **OPEN**, `VERIFIER RECOMMENDS §2.2` — a proposed signature `Sufficient : Evidence × Context × Constitution → {True,False,Unknown}` plus `Independent ⊆ ℰᵥ × ℰᵥ` |
| **`TG-08`** | *"blocks `TG-02`'s independence relation"* |
| **the "six-component vector"** | ⭐ **a different object**: `step-001 §3`'s six-component independence vector `I(e_i,e_j)`, which `STEP-VERIFY-001-010` L28 records as ***"LOST/UNACCOUNTED — never cited or reused downstream"***, with later treatments (`25N` six-concepts, evidence-algebra `D_ij`) being ***"independent re-derivations"*** |
| **co-occurrence** | ⛔ **`TG-02` and "six-component" never co-occur in any of the 464 verification files** |

⇒ **`G-07`'s literal question — *"is 'six-component vector' a misreading?"* — is answered YES**, and
the misreading is now identified: `19-P09` inverted `TG-02` using a vector the verification lane had
recorded as **lost and never reused**. `30-P13` withdrew it. ⭐ **`TG-02` itself remains OPEN** — so
the closure is of *my* gap, **not** of the underlying sufficiency-signature question.

---

## 4. Rows **confirmed unchanged** by the inventory

| gap | confirming evidence |
|---|---|
| **`G-09`** / **`C-1`** | ⭐ **`ExpectedLoss`: 0 of 464 files. `Loss_{ℛ_req}`: 0 of 464.** Neither object appears in the verification lane at all ⇒ `NO RELEVANT MATCH`; my primary-source adjudication stands unchallenged. Corroborated: `STEP-TRACE-B4` §32 — *"the four metric axioms are stated and **Zero** fails them"* — the refusal is `Zero`-scoped, as I found |
| ⭐ **`C-1`, a third endpoint** | `STEP-TRACE-B3` Q19: *"DISTANCE_REJECTED / DISCREPANCY_ADOPTED … partial order `Δ₁⪯Δ₂` replaces distance; **scalar = policy decision instrument**"* — **the corpus already says a scalar is admissible *as a policy instrument*.** That is precisely the re-scoped question (*may a priority weighting be stipulated?*), and Q19 answers it in one direction. **Still not adjudicated** — Q19 is one endpoint of three |
| **`G-15`** | `FA-1…FA-9` fully resolved at `V0-theory-corpus-map` T-005, ratified `GN-31`. ⚠️ *"Foundational Traceability Matrix"* itself: **0 matches** — the name is B's own, not a corpus artifact |
| **`G-16`** | ⭐ *"**No file in the verification tree defines or constructs `v1.1`**"* — 2 hits, both inventory rows. Theory v1.1 exists as a **directory**, not as a definition. Closure-on-locatability stands; **`G-23` strengthened** |
| **`G-17`** | `0080`, `0094`, `resume.py` — **0 matches each across 464 files**; `dimension-registry.md` confirmed as *"another lane's governed artifact"* under the firewall. **`FIREWALL-LIMITED` confirmed** |
| **`G-05`** | both objects located; ⭐ **`ℛ_req(Q,Γ)` as a form is written nowhere in the verification lane** — a notation-provenance flag on my own register. `04-CONVERGENCES`: *"`ℛ_req`-adequacy **is** `Expressive(F,ℐ)` — contrapositives"*. Descent still unwitnessed. **RE-SCOPE stands** |
| **`G-08`** | *"**No document asserts or denies a common ancestor** of the 025-series, 272a–277, and a 09-01/09-02 math lane"* — my per-pair disposition remains the only treatment |

---

## 5. ⭐⭐ A challenge to `G-19`'s and `G-22`'s independence language

`16-MASTER-GAP-REGISTER` **`G-13`** (`EXECUTED`, CRITICAL):

> *"**Primary and secondary corpora are interleaved and mutually citing after ~Step 258**, within
> minutes; the corpus grew during this session. **Agreement between them is not independent
> corroboration.**"*
> Recommendation 11: *"**freeze one tree.** The two corpora can no longer check each other."*

**Every execution `G-19` found (20:47, 21:05, 21:13) is after Step 258.** My per-document check —
that none cites Step 268 and none is cited by the commissioning lane — **stands as stated**. But
`G-13` refutes the *general* inference from it.

| claim | standing after `G-13` |
|---|---|
| *"none of the executing documents cites the commission"* | ✅ **stands** — checked per document |
| *"convergence, not compliance"* | ⚠️ **qualified** — non-citation of *this* commission does **not** establish that the lanes were independent. They were interleaved and mutually citing within minutes |
| *"two independent passes reaching the same answer"* (`G-22` §12.7, `AF-003`) | ⚠️ **qualified for the same reason** — `AF-003` is 2026-08-28, before the ~258 interleaving, so this one is **less affected**; recorded, not resolved |

⛔ **Amended wording adopted:** *"executed without reference to the commission"* — a checkable
per-document fact — **replaces** *"independently"*, which `G-13` shows is not available for this
interval.

---

## 6. ⭐⭐ The register census — `EKS-52` is larger than I wrote it

**~46 registers in the `verification/` lane alone**, with ≥10 distinct ID prefixes:
`TG-` · `C-` · `G-` · `S-` · `TV-F-` (86) · `DV-` (29) · `X.` (32) · `I.` (29) · `D-R` (27) ·
`AS-R` (20) · `P-` (17) · `CE-` (15) · `T-K` (13) · `HA-S` (12) · `UL-` · `SG-` · `NG-` · `KG-` ·
`PL-` · `CB-` · `IR-` · `CS-` · `GR-` · `MT-` · `EG-` · `IE-` · `FR-` · `ON-` · `CR-` · `EXT-` ·
`MCS-` · `OQ-` · `N-` · `TC-` · `GC-` · `ND-` …

### And **two prefixes are used by two different, non-corresponding schemes**

| prefix | scheme A | scheme B |
|---|---|---|
| **`TG-`** | `THEORY-GAP-REGISTER.md` — **`TG-01…TG-21`**, 2-digit, adjudicated | `gap-discovery/09-TRANSFORMATION-GAP.md` — **`TG-1…TG-7`**, 1-digit, a *transformation*-gap scheme |
| **`C-`** | `independent/11-CONTRADICTION-REGISTRY.md` — **`C-01…C-17`**, 17 items, adjudicated | `spec/AC-contradiction-register.md` — **`C-001…C-097`**, 88 items, explicitly ***"None is reconciled; none is adjudicated"*** |

⭐⭐ **And the Ω overload is registered TWICE, under two schemes, as two IDs:**

| | |
|---|---|
| **`TG-15`** (2-digit) | *"`Ω` carries ≥4 global senses … **the most dangerous naming collision found**"* |
| **`TG-2`** (1-digit) | *"`ρ` and `Ω` … have no declared type; **`Ω` is overloaded across three readings**"* — `UNRESOLVED`, **CRITICAL** |

⇒ **`G-22` did not re-derive a finding once. It re-derived one that the estate itself already held
twice, under two colliding ID schemes.** `EKS-52` amended.

### The one register that already does the job

`gap-discovery/glyph-register/README.md`: **31 rows · *"glyphs carrying ≥2 definitions: 15 · total
distinct definitions across them: ≥45"*** · *"glyphs carrying ≥4: `𝒦/𝕂/K` (7) · `Π` (6) · `Θ` (5) ·
`Σ` (4) · `Γ` (4)"*. ⭐ **`Ω` is not in that top list** — the glyph register and `TG-15` disagree
about which glyph is worst. Recorded, not adjudicated.

---

## 7. Net effect on the `G-00` disposition

| | before the inventory | after |
|---|---|---|
| **CLOSE** | 4 (`G-07` `G-09` `G-15` `G-16`-half) | ⭐ **5** — **`G-06` upgraded**, its residue folding into `16-MASTER`'s `G-15` (`Context` untyped) |
| **RE-SCOPE** | 5 | **4** (`G-01` `G-05` `C-1` `G-21`) — and ⛔ **`G-01`'s premise is now REFUTED, not merely too strong** |
| **reasons corrected** | — | **`G-07`** — closure stands, identification was loose; **`TG-02` itself remains OPEN** |
| **new** | — | ⭐ **two `TG-` schemes · two `C-` schemes · Ω registered twice · ~46 registers** |
| **standing claims qualified** | — | ⭐ **`G-13` withdraws "independent" for anything after ~Step 258.** `G-19`/`G-22` wording amended to *"executed without reference to the commission"* |

**Nothing canonicalized. No earlier `TheoryState(t)` altered.** `C-1` remains unadjudicated with
both branches preserved — now with a **third** endpoint recorded (Q19's *"scalar = policy decision
instrument"*), which sharpens the question without answering it.
