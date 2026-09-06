# 03 — Determination Scope Analysis (D-3)

**Mandate Part C.** Second-order derivation attempt on D-3: *"Is conditional determination in scope?"*

**Relied upon from the first-order pass:** `05` CS-4, `16` G-08, `14` FR-5.
**The mandate's warning — "do not conclude from absence alone that it is out of scope" — is exactly
what the first-order pass did wrong.**

---

## 0. HEADLINE — a correction to my own first-order finding

**`Determination` is not missing from the theory. It is fully modelled — as an aggregate root with a
type, an invariant, a lifecycle and domain events — and it was dropped in a band transition.**

| First-order claim | Second-order finding |
|---|---|
| *"`Determination` occurs 0 times in all six terminal steps"* | **True, and verified again.** |
| *"The founding object is absent from the theory"* | **False.** 1 165 occurrences across 142 files. Step 157 §157.22 gives it an aggregate; Step 165 §165.8 gives it a type. |
| *"D-3 requires a human decision on whether to reopen it"* | **No.** The corpus already chose a model, twice, with reasons. |

The first-order pass searched only Steps 262–267 and inferred scope from silence. The mandate
predicted this failure mode by name.

---

## C1 — Archaeology: the terminology map

| Term | Files | Occurrences | Typed anywhere? |
|---|---:|---:|---|
| `assessment` | 287 | 1 678 | §259.7 `Assess : K × X → Assessment` |
| `determination` | 142 | **1 165** | **§157.22 aggregate · §165.8 tuple** |
| `verdict` | 337 | 604 | **no** — still untyped (`10` GR-1 stands) |
| `justification` | 100 | 321 | no |
| `qualification` | 95 | 224 | via `Evidence(O,P,C,R)` |
| `conditional determination` | 5 | 18 | the founding phrase (2026-08-25) |
| `rule instance` | 0 | 0 | **the first-order pass's own phrase — not corpus vocabulary** |

Density by step: Step 157 (43), Step 165 (30), Step 173 (27), Step 170 (27), Step 163 (27),
Step 161 (24), Step 164 (23), Step 174 (21), Step 178 (19), Step 177 (19).

**The concentration is the DDD/architecture band, Steps 155–178 (2026-08-28/29).** The terminal
formalization band (262–271, 2026-08-30) contains none of it.

---

## C2 — The four models, tested against the corpus

### What the corpus actually specifies

**Step 165 §165.8** gives the type:

$$D = (conclusion,\; method,\; context,\; rationale,\; inputReferences)$$

with invariants:

$$Established(D) \Rightarrow RequiredInputsPresent(D) \qquad Established(D) \Rightarrow MethodSpecified(D)$$

**Step 157 §157.22** gives the aggregate root:

```
DeterminationId · Proposition · Method · EvidenceReferences ·
Result · EpistemicStatus · Actor · ContextSnapshot
```

> *"A determination cannot claim evidentiary support without referencing the evidence and method on
> which that support depends."*

**Step 157 §157.23** gives the lifecycle: `DRAFT → PROPOSED → FINALIZED`, **immutable after
finalization**, revised only by `Determination D1 —SupersededBy→ Determination D2`.

**Step 157 §157.x** gives the domain events: `DeterminationProposed`, `DeterminationFinalized`.

**Step 165 §165.10** gives the decisive separation argument:

> `D₁ = f(K,E,C₁)` and `D₂ = f(K,E,C₂)` can **both be valid** because context differs.
> *"Therefore Determination cannot simply be treated as 'the truth derived from Knowledge.' It is a
> **contextual conclusion**."*

**Step 173 §173.4–5** keeps `Knowledge`, `Determination` and `Decision` in three separate bounded
contexts, calling the Determination/Decision merge *"another dangerous merge"*.

### The four-model test

| | Model | Corpus verdict | Evidence |
|---|---|---|---|
| **A** | Determination **outside** the Knowledge State | **✅ SUPPORTED** | §165.10: two valid determinations over one `K` ⟹ `D` cannot be a function of `K` alone, so it is not *in* `K` |
| **B** | Determination stored **with the assertion** | **❌ REFUTED** | §165.10 (same argument): one assertion would carry two contradictory determinations; §157.20 *"argues against embedding evidence inside a determination aggregate"* — and symmetrically against embedding `D` in `A` |
| **C** | Determination + **reference to the rule instance** | **✅ SUBSUMED BY D** | §165.8's `method` + `inputReferences` **are** the rule reference. The first-order pass proposed C as new; it is a strictly weaker version of what §157.22 already specifies |
| **D** | Determination as a **separate typed object** linked to assertion, rule, evidence and authority | **✅ THIS IS THE CORPUS MODEL** | §157.22 aggregate root, with `Proposition` (assertion link), `Method` (rule), `EvidenceReferences` (evidence), `Actor` (authority), `ContextSnapshot` |

### Model D against the mandate's ten criteria

| Criterion | Under Model D |
|---|---|
| explanation | ✅ `method` + `rationale` + `inputReferences` |
| reproducibility | ✅ `ContextSnapshot` + `Method` + `EvidenceReferences` — the §257.11 "no hidden inputs" rule satisfied |
| provenance | ✅ `Actor` + `EvidenceReferences` |
| policy change | ✅ old `D` immutable (§157.23); a new policy yields a new `D` that supersedes |
| historical reconstruction | ✅ the supersession chain **is** the epistemic history |
| deduplication | ⚠️ needs `Determination` identity — **not specified** |
| merge | ✅ unaffected — `D` is outside `K` |
| contradiction | ✅ §165.10 — two valid `D`s over one `K` is *normal*, not a contradiction |
| implementation correspondence | ⚠️ **zero instances**; `EvidenceSet`/`Determination` exist in PublicDigit's **Adjudication** context, which is a different domain (`11` PL-8) |
| minimality | ✅ `D ∉ K` keeps `K` minimal — consistent with §271.35's `K` 🟢 |

---

## C3 — Does the corpus force one model?

**Yes — B is refuted and D is specified.** The mandate says to choose only if corpus, implementation,
mathematics or coherence forces it. **Corpus forces it**, at two independent steps (157 and 165), with
an argument (§165.10), an invariant, a lifecycle and events.

### So what actually happened?

`Determination` was specified in the **DDD band** and never carried into the **formalization band**.
The transition is visible:

```
Steps 155–178  (DDD/architecture, 28–29 Aug)   Determination = aggregate root, typed, with events
        │
        │   ← Steps 213–228: the corpus turns on itself (archaeology)
        │   ← Steps 230–257: kernel reconstruction, formal minimality
        ▼
Steps 258–271  (formalization, 30 Aug)          K = (𝒜, ℛ).  Determination: 0 occurrences
```

**DS-1 (`DERIVATION`, CRITICAL).** The formalization band reduced the model to `K = (𝒜,ℛ)` and did not
record that a fully specified aggregate had been left behind. No terminal step says *"Determination
is out of scope"*; none says *"Determination is retained externally"*. It is simply absent.

**This is not a theory gap. It is a band-transition loss** — the same failure mode as `01` EV-A2
(the repaired measure-theory model) and `15` UL-9 (`Regime` vanishing). Three instances now.

**DS-2 (`DERIVATION`, HIGH).** The founding question — *conditional* determination — is **still** not
answered even under Model D. §157.22's `Method` is a field; nothing types a *rule*, and Step 266
places `Policy` in class C. So:

> The **object** `Determination` is specified. The **conditional structure** that produces it is not.

That is a narrower and truer statement than the first-order `G-08`.

---

## VERDICT ON D-3

$$\boxed{\textbf{D-3 IS DERIVED — IT IS NOT AN IRREDUCIBLE NORMATIVE CHOICE}}$$

| Sub-question | Status |
|---|---|
| Is `Determination` in scope? | **`CORPUS EVIDENCE` — YES.** 1 165 occurrences; an aggregate root at §157.22 |
| Which model? | **`CORPUS EVIDENCE` — Model D.** Separate typed object, outside `K`, linked to proposition/method/evidence/actor/context |
| Is Model B admissible? | **`DERIVATION` — REFUTED** by §165.10 |
| Was the first-order recommendation (Model C) right? | **Yes but weaker** — C is a subset of what §157.22 already specifies |
| Is the founding *conditional* problem solved? | **`UNRESOLVED`** — `Method` is a field, not a typed rule; `Policy` is class C |
| Does anything require a human decision? | **No** |

**Withdrawn from the first-order register:** `G-08` as stated (*"the founding object is absent from
the theory"*).

**Replaced by:**

- `DS-1` — a specified aggregate was lost in the DDD→formalization band transition, unrecorded.
  **Recoverable by reinstatement; no decision needed.**
- `DS-2` — `Determination` is typed; the **rule** that produces it is not. This is the same open
  object as `Policy` (Step 271 marks Policy internal structure 🔴), so it is **not a separate gap** —
  it is downstream of the Policy work Step 271 already commissions.

---

**Next:** `04-GAP-RECLASSIFICATION.md`.
