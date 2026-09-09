# MD-070 §01 — Findings

## Finding 1 (structural) — Every "PROVED" theorem in the core chain is a definitional tautology
over an *uninterpreted* `Sat`

Part I (`[05-36]`) §21 "Satisfaction" states, verbatim: *"Sat must be defined before we use it to
define completeness or Zero"* — i.e. at the point `Δ_t`/`Zero` are formalized and their two governing
theorems proved, `Sat` is explicitly an **uninterpreted predicate symbol**, not yet given semantic
content. The proofs that follow:

- **Theorem 24.1 (Zero Equivalence)**: `Δ(K,EC)=∅ ⟺ ∀r,Sat(K,r)` — proved by unfolding the set-builder
  definition of `Δ`. Valid for *any* binary predicate one chooses to call `Sat`.
- **Theorem 25.1 (Gap Reduction)**: `Δ_{t+1}⊆Δ_t ⟹` no previously-satisfied requirement becomes
  unsatisfied — a direct restatement of set inclusion.
- **Theorem 5.1 (Zero-Completeness Equivalence)** and **Theorem 5.2 (Conditional Zero Preservation)**
  (`[05-40]`, read directly, lines 1509–1519 and 2605–2666): the latter's own proof is four lines
  unfolding four *unproven, un-instantiated* assumptions ("every requirement... is stable," "no new
  applicable requirement is introduced" — never shown to hold for any real `K,EC,Γ`).
- **Theorem 6.1 (Determination-Gap Equivalence)** and **Theorem 6.2 (Requirement-Complete
  Determination)** (`[05-41]`, read directly, lines 1008–1050 and 2022–2068): Theorem 6.1's own text
  states outright, immediately after its proof: *"the theorem is fundamentally definitional, but it
  establishes an important bridge"* — the source discloses this itself. Theorem 6.2's proof is
  structurally identical to Theorem 5.2's: seven assumptions ("every required evidence condition is
  satisfied," "the satisfaction projection `χ_EC` is applied correctly") are *assumed*, never shown to
  hold, and the "proof" is a one-line consequence of Definition 6.2 given those assumptions.

**None of these nine theorems establishes that `Sat`'s specific later content (`Det_r(EvalReq(...),
EC)`) is correct, sound, complete, or even well-defined for any concrete `K, r, EC, Γ`.** They
establish only that `Δ_t`/`Zero`/`Determination`'s own *shape* (as functions of whatever `Sat` turns
out to be) is internally consistent — a fact independent of `[05-41]`'s own specific contribution.

## Finding 2 (disclosure inconsistency) — the same self-critical honesty applied unevenly

Theorem 6.1 is self-flagged "fundamentally definitional." Theorems 24.1, 25.1, 16.1 (Part I) and 5.1,
5.2 (Part V) receive **no equivalent disclosure**, despite having the identical logical character. A
reader encountering Theorem 24.1 in isolation (no caveat) would reasonably form a different impression
of its mathematical weight than a reader encountering Theorem 6.1 (explicit caveat) — even though both
are the same kind of claim. This is recorded as a genuine, if minor, methodological inconsistency, not
an error.

## Finding 3 (category conflation) — one claim is simultaneously Definition, proved Theorem, and Axiom

`Zero(K,EC)⟺Δ(K,EC)=∅` appears as: **Definition 23.1** (`[05-36]` §23), **Theorem 24.1** (proved *from*
Definition 23.1 two sections later), and **Axiom A7** (`[05-36]` §39, "Contractual Zero," restated
verbatim as an axiom of the whole theory). A definition cannot also be independently an axiom (an axiom
is stipulated, not derived); a theorem proved from a definition is not itself a further, separately-
axiomatized commitment. This directly contradicts the mission's own (and this document's own, stated
in its Part I opening) discipline of keeping Conceptual/Formal/Operational/Governance states separate
— here, one claim occupies three of those categories simultaneously without comment.

## Finding 4 (unaddressed vacuity risk) — `Determination` may be vacuously true

`Det(K,p,EC,Γ) ⟺ ∀r∈Req_p(EC,Γ), χ_EC(Sat(K,r,Γ))=1` (`[05-41]` Def 6.2). Universal quantification
over an empty set is vacuously true. `Req_p(EC,Γ)` is itself an uncomputed, abstract generation
function (per MD-068's own GAP-003 finding) — nothing in Parts I, II, V, or VI establishes that it is
ever non-empty for any real proposition `p`. **A direct search of Parts V and VI for "vacuous," "empty
req," or an equivalent safeguard returned zero hits.** This stands in contrast to the corpus's own
earlier (2026-09-02) vigilance about exactly this class of problem — M0054's PB-3/PB-4 findings were
specifically about Kleene-conjunction collapse and vacuity-adjacent defects in the `Sat_c` apparatus,
and that vigilance is not carried forward into Theory-00-21's own treatment of `Determination`.

## Finding 5 (decisive) — the theory's own worked example never invokes `Det_r`/`EvalReq`

The worked example (Part XXI-A rev2, read in full, 1859 lines) is the one place Theory-00-21 claims to
demonstrate the complete chain "surviving a complete real-world reasoning chain" (§21A.1). At §21A.17
("Determination"), the four requirements are settled by direct stipulation:

> "All are satisfied: `Sat(K,r_i)=Satisfied` for `i=1,...,4`."

**`Det_r` is never named. `EvalReq` is never named. `Eval` (in the Part VI sense) is never named,
anywhere in this 1859-line document.** The example instead derives `ReleasePermitted(S)` via a
completely separate, simpler mechanism — a rule-based logical derivation (§21A.10–21A.16: premises →
rule `ρ_release` → derivation → proof object → verification) — and then, *disconnected from that
derivation*, simply asserts that the four underlying requirements are "Satisfied." This is exactly the
same move — stipulate `Sat`'s outcome directly rather than deriving it from a computation — that every
earlier, explicitly-retired `Sat` attempt in this corpus made (M0136's `Sat=Entailment`, `[00-51]`'s
unfinished class-by-class `standard`, `[00-55]`'s `Sat_c`). **No file anywhere in the 876-file
traversal, including Theory-00-21's own worked example, computes `Sat(K,r,Γ)` end-to-end via
`Det_r(EvalReq(K,r,EC,Γ),EC)` for any concrete case.**
