# S — Frozen Results Register

**Purpose.** A result is **frozen** when supervisory review has adjudicated it and closed further
work on it. Freezing is **not** promotion: a frozen `[NEG]` stays `[NEG]`, and Theory v1.2 remains
unchanged. Freezing records that **the question has been answered well enough to stop asking it in
that form.**

**Rule.** A frozen entry may be *cited*, *superseded by an explicit act*, or *reopened by naming the
new evidence*. It may **not** be silently revised, and it may **not** be re-litigated by running the
same experiment again.

---

# FR-001 · `KR-DIST-2026-09-02` — Distinguishability cannot carry family-level complexity

**Frozen** 2026-09-02 · **Adjudicated by** supervisory review as a **RESEARCH-RESULT FREEZE, not a
theory or architecture decision** · **Artifact**
[`R-distinguishability-and-effective-complexity.md`](R-distinguishability-and-effective-complexity.md)

## The frozen finding

> **Pairwise semantic/evidential distinguishability cannot carry family-level epistemic complexity by
> itself.**

Established by **two independent failure mechanisms**, which is what makes it a boundary rather than a
failed fit:

| # | mechanism | evidence |
|---|---|---|
| 1 | `~_Λ` is **not transitive**, so `H/~_Λ` is not a legitimate quotient | sorites witness: `H₁ ~ … ~ H₁₂` pairwise, `H₁ ≁ H₁₂`, span 3.068 at δ=0.3099 |
| 2 | the transitivity-free repair (**δ-packing**) captures **redundancy** and fails on **coupling** | grouped 0.98–1.06 · **correlated up to 222×** |

## Register

| finding | status |
|---|---|
| `~_Λ` is not generally transitive | **`[NEG]`** |
| `H/~_Λ` cannot generally define complexity | **`[NEG]`** |
| Homogeneous spaces can **conceal** non-transitivity | `[EXP]` |
| δ-packing avoids the quotient/transitivity problem | `[EXP]` |
| Packing captures tested redundancy/grouping | `[EXP]` |
| Packing fails dramatically under strong correlation | **`[NEG]`** |
| Pairwise distinguishability cannot generally measure family-level burden | **`[EXP]` / `[NEG]`** |
| Family-level burden depends on joint behaviour | `[EXP]`, **scope-bounded** to the tested regimes |
| Redundancy and coupling are distinct **candidate** mechanisms | `[PROP]` — *"at least two"* |
| Semantic equivalence captures the whole `N_eff` problem | **`[NEG]`** |
| Semantic equivalence may capture the **redundancy** component | `[PROP]` |
| Explicit dependence structure is required in KnowledgeOS | **`[OPEN]`** |
| `N_eff` is a KnowledgeOS primitive | **`[OPEN]`** |
| `N_eff` belongs in the kernel | **`[OPEN]`, currently unsupported** |

## What the freeze closes

* **No third `N_eff` formula hunt.** Two have failed for the same reason — `n(1−ρ)²` (fitted, then
  refuted on a wider grid) and δ-packing (structural, but pairwise).
* **`N_eff` does not become the next central theory problem.** It is not established that effective
  complexity is a KnowledgeOS primitive at all; it may belong to an evidential regime, outside the
  kernel.
* **Theory v1.2 unchanged.** No amendment proposed, none accepted.

## What the freeze leaves open, and in what form

When revisited, the question is **not** *"what formula gives `N_eff`?"* but:

> **What is the minimal joint structure required to represent family-level epistemic dependence?**

`[PROP]` A candidate representation — **not** a proposed v1.3 — is
`𝔥_Λ = (𝓗, ℛ_sem, Λ, Σ_Λ)`.

## Why this was frozen rather than continued

The result **prevents a theoretical mistake** — collapsing family-level complexity into an
equivalence relation — and that value does not increase with further formula search. Adjudication
placed it alongside the kernel-reduction and Zero experiments in research value, **not because a
formula was found, but because a boundary was.**

---

# Standing consequences carried out of FR-001

These apply beyond the frozen experiment and are recorded here so they are not lost with it.

| # | consequence | status |
|---|---|---|
| **C-1** | **Do not put statistical mechanisms into the kernel merely because KnowledgeOS can use them.** Candidate generation, selection, multiplicity control, correlation modelling, validation, Bayesian updating may all be **capabilities** without being **irreducible kernel operators**. | `[EXP]` — a pattern seen repeatedly |
| **C-2** | **The three-level distinction:** Identity (`≡_sem`) → Pairwise distinguishability (`~_Λ`) → Family structure. Levels 1–2 are insufficient for level 3. | `[EXP]` |
| **C-3** | **`≡_sem` and family-level complexity remain separate concepts** unless a formal bridge is established. `≡_sem` is **not** refuted; it must simply not be asked to do a job it was not designed for. | `[PROP]` |
| **C-4** | **A homogeneous test space can conceal a relation-level defect.** Non-transitivity was invisible inside the equicorrelated space and appeared only where separations vary. | `[EXP]` — a methodology caution |
| **C-5** | **Good fit on a narrow design space ⇏ structural law.** (`n(1−ρ)²`) | `[EXP]` — methodology invariant |

> **Status discipline on C-1…C-5** (per the approval): these are **methodological consequences** and
> **must not silently become architectural laws.** They constrain how research is conducted; they do
> not amend Theory v1.2.

## Corroboration (added 2026-09-02)

`KR-HILBERT-2026-09` tested whether a Hilbert/spectral representation supplies the joint object
`FR-001` found missing. **It does not.** All four tested spectral functionals are exact on block
structure and fail on equicorrelation by up to **50×, underestimating** — the opposite direction from
the pairwise route's **222× overestimate**.

> **`FR-001` is STRENGTHENED, not reopened.** The residual question gains a constraint: the answer is
> **neither a pairwise construction nor a standard spectral functional.**
> See [`T-hilbert-space-representation.md`](T-hilbert-space-representation.md).

### Not frozen — a candidate awaiting independent replication

`[EXP]` **Under the tested correlation-family regimes, the measured effective multiplicity burden is
not represented by pairwise δ-packing and is not represented by the tested standard spectral
functionals (participation ratio, spectral entropy, trace/λ_max, rank).**

**Status: `[EXP]`, one turn's evidence, NOT frozen.** It becomes a freeze candidate only after
independent replication.

> **Explicitly excluded from the register:** the stronger sentence that the burden *"lies between"*
> the two constructions. **"Between" presupposes a formally defined ordering on candidate functionals
> and a proof that the sought functional lies in that interval — neither exists.** The directional
> observation (pairwise overestimates 222×, spectral underestimates 50×) is recorded as a
> **structural boundary**, not as an interval claim. *(Corrected 2026-09-02 on review; the earlier
> wording appeared here and in `T` and was withdrawn from both.)*

---

## Companion reference

A detailed, cross-linked explanation of the frozen model — the baseline, `FR-001` in full, what else
is established, what is proposed, what is open, and a complete artifact map — is at
`docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260902-142217_the-frozen-model-what-is-settled-and-where-the-artifacts-are.md`.

**This register remains authoritative for the freeze itself**; the reference explains it.

## Adjudicated results — `KR-CONTR-FDE-2026-09` *(authoritative, 2026-09-02)*

**Preserved as authoritative by review.** A **research-result** record, as `FR-001` is — **not** a
theory or architecture decision.

| id | result | class |
|---|---|---|
| **`E-FDE-1`** | independent positive/negative support **preserves the tested contradiction distinctions that K3 collapses** | `[EXP]` |
| **`E-FDE-2`** | two-channel `Standing` **does not** preserve the tested boundary distinctions | `[EXP]` |
| **`E-FDE-3`** | **boundary information is required beyond `Standing`** — within the tested representation language and `ℛ_req` | `[EXP]` |
| **`E-FDE-4`** | **`FDEConflictDetector` cannot implement the tested contextual versions of `Contr`**; the empty frame qualifier **over-generates** contradiction | `[NEG]` |
| **`E-FDE-5`** | **composition and frame qualification are COUPLED** for the tested contradiction/supersession witnesses | `[EXP]` |
| **`I11`** | `Contr ≠ Satisfied` | `[PROP]` |
| **`E-FDE-6`** | appropriate composition semantics and frame qualifier | `[OPEN]` |

### The surviving result is FACTORIZATION, not FDE adoption

> **`Evaluation = Status/Polarity × Typed Boundary/Reason`** — `[EXP]`, **scoped** to the tested
> representation language and required-distinction set.

**Two complementary witnesses:** `K3` is insufficient for **contradiction** distinctions; FDE-like
`Standing` is insufficient for **boundary** distinctions.

**The question has moved up a level.** No longer *"which truth-value logic should KnowledgeOS use?"*
but **"what information must an evaluation representation preserve, and how must semantic standing be
separated from the reason/boundary under which that standing obtains?"**

### Explicitly NOT frozen

FDE as the KnowledgeOS logic · `Standing` as a domain primitive · any Boundary vocabulary ·
`FlatReason` / `LocusModalityReason` / `StructuredBoundary` · `Contr` · `Zero` · a composition algebra.

---

## Authoritative register — `KR-COMP` / `KR-COMP-SEP` *(review §6, 2026-09-02)*

**Status classes for decisions:** **`[DECIDED]`** taken · **`[DECISION REQUIRED]`** open ·
**`[DEFERRED]`** blocked by a prior decision. All are settled by authority, never by evidence.

### Decision register

| # | decision | status |
|---|---|---|
| **`DECISION-01`** | **semantic evaluation must be invariant under non-evidential variation of the representation**; `C6` (permutation) and `C7` (frame-refinement) ratified as **instances** | ✅ **`[DECIDED]`** 2026-09-02 |
| **`DECISION-02`** | **is `φ` a semantically meaningful evaluation frame, or merely an evidence partition?** | ⬅ **`[DECISION REQUIRED]`** |
| `Z` cross-frame divergence — `majority` vs `intraframe-only` | **`[DEFERRED]`** — blocked by `DECISION-02` |
| factivity | `[DECIDED]` — `R1` |
| `φ` vocabulary · `ℛ_req` · adopting `M3≅M4` · adopting `𝓑` | `[DECISION REQUIRED]` |

> **Why `Z` is deferred, and it is not merely prudence:** **`DECISION-01`'s `C7` instance cannot be
> APPLIED until `DECISION-02` is taken.** Whether refining a timestamp is a *non-evidential* change
> depends on whether frames are part of the evidence. **Under Option B `majority` does not violate
> `C7` at all.** Deciding the algorithm now would settle the frame ontology by implication.

> **Withdrawn:** this lane's `[PROP]` observation favouring `intraframe-only`. It treated
> *"non-evidential"* as settled when the term presupposes the very ontology `DECISION-02` fixes, and it
> converted *"aggregation is representation-sensitive"* into *"aggregation is forbidden"*, which does
> not follow. **The `C7` measurement stands; the inference did not.**

| finding | status |
|---|---|
| `W1` does not separate `majority`/`intraframe-only` — **internal conflict is absorbing** | `[NEG]` scoped |
| `W1` eliminates `last-wins` under `C1` | `[NEG]` |
| `W2` separates `majority`, `last-wins`, `intraframe-only` | `[EXP]` |
| `W4` demonstrates `last-wins` **order dependence** | `[EXP]` |
| **`C7`** `W_coarse`/`W_fine` demonstrate `majority` **refinement dependence** | `[EXP]` — **new** |
| `C6` order-invariance | ✅ **RATIFIED** as an instance of `DECISION-01` |
| **`C7`** frame-refinement invariance | ✅ **RATIFIED** as an instance of `DECISION-01` |
| `last-wins` | `[NEG]` **excluded** |
| `majority` | `[OPEN]` survives |
| `intraframe-only` | `[OPEN]` survives |
| **`majority` vs `intraframe-only`** | **`[DEFERRED]`** — blocked by `DECISION-02`; record: [`Z-…`](Z-DECISION-cross-frame-divergence.md) |
| `φ` — **semantic status first**, vocabulary second | **`[DECISION REQUIRED]`** — [`DECISION-02`](DECISION-02-semantic-status-of-the-frame.md) |
| `ℛ_req` | **`[DECISION REQUIRED]`** |
| `Contr` | `[OPEN]` undefined |
| `Zero` | `[OPEN]` |
| kernel | **not selectable** |
| Theory v1.2 | **unchanged** |

> **Scope guard (review §5):** *"`majority` and `intraframe-only` are the only possible composition
> semantics"* is **NOT established** — only that they are the **surviving tested candidates**.
> *"Order-sensitive composition is invalid"* is **NOT established** — only that the tested `last-wins`
> candidate fails the tested requirements.

**The boundary crossed:**
`factivity → representation factorization → φ → composition candidate elimination → semantic decision`

---

## Candidate `FR-002` — **NOT FROZEN**

**Proposed by review of `KR-CONTR-EVAL-2026-09`, and explicitly withheld from freezing** until the
artifact's formal claims were re-checked — **specifically the wording *"reason is the sole
indispensable field"***, which has since been **softened and tested** (`W` §9.1–9.2). The candidate
list stands; **the freeze does not.**

| # | finding | class |
|---|---|---|
| 1 | the tested contradiction requirements **do not force a fourth flat value** | `[EXP]` |
| 2 | flat candidates A/B/C fail the **same seven** required distinctions | `[EXP]` |
| 3 | their common failure lies in the **unknown/boundary family, not contradiction** | `[EXP]` |
| 4 | candidate D separates **all** tested required distinctions | `[EXP]` |
| 5 | **a structured reason/boundary component is indispensable *within the tested candidate representation language*** | `[EXP]` — **wording corrected; see `W` §9.1** |
| 6 | candidate C can map contradiction to **Satisfied** while passing `I1`–`I5` | `[EXP]` |
| 7 | `Contr ≠ Satisfied` is a required candidate invariant | `[PROP]` |
| 8 | **minimum flat cardinality depends on the declared required-distinction set** | `[EXP]` |
| 9 | *"KnowledgeOS requires four-valued logic"* is **not supported** | `[NEG]` |
| 10 | minimal structured evaluation semantics | `[OPEN]` |
| 11 | composition | `[OPEN]` |
| 12 | canonical `Contr` representation | `[OPEN]` |
| 13 | `Zero` semantics | `[OPEN]` |

> **Status: CANDIDATE. Not frozen, not counted in the register total.** Item 5's wording was the
> stated blocker and has been repaired **by measurement, not by rewording** — `reason` is 10 values
> over 21 conditions, collapses 66 pairs, and is **not adequate alone (11/12)**, so it is a genuine
> categorical field and not a disguised state identifier.
>
> **Freezing remains a decision, and this lane does not take it.**

---

## Register status

| | |
|---|---|
| frozen entries | **1** (`FR-001`) · 1 candidate `[EXP]`, not frozen |
| Theory baseline | **v1.2, unchanged** |
| research queue | **`Contr` → `⪰`**; `N_eff` and `⪰` remain **independent** |
| **`Factivity`** | **DECIDED — `R1`** *(governance, 2026-09-02)*: `K_t → A_t` (AttributedState); `Knows → True` retained as an **external factive** assertion; **Verification separate**. Brief: [`U-factivity-adjudication-brief.md`](U-factivity-adjudication-brief.md) |
| **decision register** | **`R1` is NOT a frozen research result and is NOT in this register's scope.** It was taken on **authority**, not evidence — `R1` and `R2` were behaviourally identical, so no evidence could favour either. Recorded here only so the queue reads correctly |
| `KR-EXTREME-2026-09` | **DESIGNED, NOT RUN** — must not precede the queue |
