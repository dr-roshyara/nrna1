# MD-054 §02 — The `K=(𝒜,ℛ)` Construction and Its Actual Terminal State

**Discipline applied throughout**: the *intermediate* closure claim (`THEORY-CLOSURE-AUDIT.md`,
"24/24 criteria met") is **not** treated as the thread's own conclusion — its own successor document
explicitly attacks and substantially overturns it. Only `THEORY-STATUS-VERDICT.md` (20:52, the last
file in the thread) is treated as authoritative for "what this thread concludes."

## The construction, as it stands after both passes

```
K = (𝒜, ℛ)
𝒜 = Set(Assertion)      Assertion = (id, P, e, c, t, Π)
                        P = (E, D, V)         Entity, Dimension, Value — Q14
                        e ⊆ Evidence           Evidence = (ref, source, observation, context,
                                                            time, method, provenance, polarity, state)
                        c ∈ Context             t = [vf, vt)  bitemporal        Π ∈ Origin
ℛ = ℛ_sup ⊎ ℛ_ref ⊎ ℛ_der ⊎ ℛ_supp ⊎ ℛ_res ⊎ ℛ_rel
    (3 acyclic DAG families, 2 non-transitive edge sets, 1 symmetric family)
DERIVED, never stored: Σ · equals · contradicts · conflictsWith
EXTERNAL: History · Policy · Authority · Γ
```

`[SD]` = source-defined (a corpus document states it) · `[FD]` = formally derived by the verifier ·
`[EX]` = executed by the source programme (self-reported, not re-run this phase) · `[RF]` = refuted
by the thread's own second pass.

## What the second (re-verification) pass actually found — the load-bearing content of this whole thread

### Survives fully

- **Provenance's four-way split** (`Π` assertion-origin / `EvidenceProvenance` / `History(T)` /
  `MessageProvenance`) — independently re-anchored against four separate sources
  (`t=0` counterexample; corpus §230.15; the executed `History(K)≠K` counterexample; 18 real PHP
  files under `app/Contexts/`). The one closure the re-verification pass calls "the one that survives
  adversarial re-verification unchanged."
- The `Σ ⊥ Γ` **derivation** and `authorities.yaml`'s own declared independence (though one of its
  three claimed "independent proofs" — an executed Python witness — was found tautological on direct
  code inspection and withdrawn as evidence; see below).
- `(Policy, ∧)` as a meet-semilattice; `∨` refuted against the corpus's own "no averaging" law.
- `Lineage = Π ∘ ℛ_der*`, corroborated against real, passing production tests (47 tests,
  `GovernanceLineageGraph`).
- `132/132` production authority grants carry a `humanActRef` field — re-measured by the second pass
  at a larger scope than the first (132 vs. the first pass's 20), confirmed as a real discipline
  (though not, on inspection, a typed *binding* — see below).

### Refuted or substantially weakened by the second pass

- **A genuine new internal contradiction**: `id = H(P, e, c, t, Π)` and `e.state` (evidence
  withdrawn/invalidated) being declared mutable cannot both hold — withdrawing one evidence item
  re-keys the assertion's own identity hash and dangles every relation edge pointing at it, violating
  the theory's own `StructuralValid` predicate. Confirmed by direct execution, both by the source
  programme and independently traceable from the stated definitions (this phase re-derived the
  argument from the stated `id` formula and the stated mutability of `e.state`, without needing to
  execute code — the contradiction follows from the two definitions as written).
- **`Σ` has no access to `ℛ`**: two assertions joined by an explicit `contradicts` edge can both read
  as `Supporting` — a fully-supported inconsistency is representable and undetected by the epistemic-
  status machinery. A new finding, not on any prior gap list.
- **`merge = union` cannot deduplicate**: the same fact observed from two sources produces two
  permanently distinct assertions (since `Π` is inside the identity hash), so corroboration and
  duplication become indistinguishable, and `K` grows without bound under repeated observation of an
  unchanged fact.
- **`δ`'s commit case is undefined**: executed, the "authorized, executed, historied" commit operation
  produces `K₁ ≡ K₀` — the system's own central governed act is, on its own definitions, the identity
  function.
- **The `Σ ⊥ Γ` executed witness is a tautology**: the cited Python script's `evidence_volume`
  parameter is declared, passed the value `10⁶`, and never read by the function body — confirmed by
  direct grep of the script — so the "10⁶ evidence cannot cross the governance boundary" result would
  hold identically for `evidence_volume = 0`, and would "pass" even if the underlying law were false.
- **`Σ ⊥ Γ`'s empirical support is weaker than claimed**: measured directly against this repository's
  own 39 governed documents, `status` and `authority` are declared independent but are in fact almost
  perfectly collinear in the actual data (`draft ⇔ provisional`, 13/13 both directions) — orthogonality
  is a design property, not an observed one.
- **"Every canonical term now has exactly one meaning"** — false by direct measurement: 11 of 25
  audited terms carry more than one live semantic role with no declared boundary. `Ω` alone carries at
  least four incompatible global senses, two of them foundational and pointing in opposite directions
  (a "knowledge space" superset vs. an "observation function" — conflating them would make the
  theory's own non-identifiability result trivially false).
- **"30/30 symbols resolve"** — internally irreconcilable with an earlier artifact in the *same*
  thread (`COMPUTABILITY-MATRIX.md`, 68 minutes earlier) reporting 14 of 16 computable, 2 blocked; the
  two blocked symbols were never actually addressed, only silently replaced by a different pair in the
  later "30/30" count.

### Reversed a second time — three "inexpressible" capabilities found expressible after all

The closure pass had declared uncertainty, non-identifiability, and missingness permanently
inexpressible in `K=(𝒜,ℛ)`'s own shape, calling this a scope exclusion rather than a defect. The
re-verification pass searched the corpus directly and found **formal, typed constructions for all
three already exist**, dated 2026-08-22 through 2026-08-28 — before this entire verification programme
began, so not contamination:
- `Identifiable(g,Ω)` and a required `Underdetermined` return value (step 31.17–31.21) — standard,
  correct identifiability theory.
- A six-state "Zero" dimension-taxonomy with five explicit non-collapse laws, including
  `NOT_ASSESSED ≠ LOW_CONFIDENCE` — directly answering the closure pass's own "cannot say nobody ever
  asked" complaint.
- `U(H)=(type,value,model,scope,source)` (step 31.24) — a typed uncertainty object that *deliberately*
  does not require a probability space, precisely the objection the closure pass raised against
  adopting uncertainty at all.

**These constructions are absent from the *authorized* architecture** (`canonical-architecture-v0.2.md`
— measured: zero occurrences of "unknown," "uncertain," "identifiab," "not assessed") because a prior,
separate governance ruling explicitly classified adjacent material ("Abhāva") as *"Enrichment, NOT a
row"* and closed the question. **The gap is therefore governance, not mathematics** — and the thread's
own final document states this precisely and puts the adoption question to a PO/ARB, unanswered,
rather than deciding it itself.

## The terminal verdict, verbatim structure (`THEORY-STATUS-VERDICT.md`)

Eight separate closure senses, deliberately not collapsed into one PASS/FAIL:

| Sense | Verdict |
|---|---|
| Mathematically closed | **NO** — 9 of 30 symbols have no decision procedure; 6 missing dependency edges; 1 internal contradiction |
| Semantically closed | **NO** — 11 of 25 terms overloaded |
| Computationally closed | **PARTIAL** — closed for non-governance operations under a fixed policy; not closed for `commit` |
| Empirically validated | **NO** — strongest witness is tautological; declared independence measured collinear |
| Implementation-conformant | **PARTIAL** — skeleton conformant, epistemics absent |
| Governance-closed | **PARTIAL** — closed twice, by two unreconciled mechanisms |
| Practically implementable | **PARTIAL** — three specific undefined bodies block the governed path |
| Theoretically complete | **NO** — 21 registered gaps, 3 blocking |

**Final boxed statement**: *"THE THEORY IS NOT CLOSED, AND IT IS CLOSER THAN THE PRIOR VERDICT
ALLOWED."* One normative question is explicitly put to a PO/ARB and explicitly not answered by the
programme: whether to adopt the `(W,Ω)` observation layer, `D_t`, and `U(H)` into the ratified
architecture. **`⛔ STOP. No theory-extension phase follows this pass`** — a self-issued hard stop,
honored (no further file in the thread continues it).
