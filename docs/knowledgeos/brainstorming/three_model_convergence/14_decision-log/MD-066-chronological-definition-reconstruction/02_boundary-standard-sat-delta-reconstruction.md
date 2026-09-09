# MD-066 §02 — Boundary, `standard`, `Sat`, `Δ_t` Reconstruction

## Output 4: Newly discovered semantic bridges

### Bridge candidate B1 — `EC_t → Req(EC_t) → r → App(r,Q_t,C_t,S_t,EC_t) → Sat_c(K_t,r) → Δ_t^sem`
(evidenced in M0051, already known from the immediately-preceding conversation turn but restated
here as the anchor this phase's new material must be checked against)

M0051 supplies an explicit **Applicability** step between `Req` and `Sat`: `App(r,Q_t,C_t,S_t,EC_t)`
— "Applicability ≠ Satisfaction." This is a genuine, corpus-native bridge element MD-063 did not
have. It is class-indexed: eight requirement classes `𝓡_content/evidence/provenance/epistemic/
consistency/governance/temporal/operational`, each with its own `[PROP]`-only `Sat_c` candidate, a
three-valued codomain `{⊤,⊥,U}`. `Δ_t^sem={r∈𝓡_t^app | Sat(K_t,r)≠⊤}`. Status: `[PROP]`, not `[DEF]`;
factivity (CE-1) and revision-without-retraction (CE-3) counterexamples found against it in the same
document; M0054's own follow-up experiment (KR-SIM-2026-09-02-B) found only 3/8 classes executable.
**This bridge exists, is real, and is more developed than MD-061–064 had located — but it was never
frozen, and the corpus's own later self-review (below) does not build on it or cite it.**

### Bridge candidate B2 — `K_t^E → Cn_S → K_t^{I,S} → Eval_c → EVal_t → Determination → Decision → δ`
(new, this phase: M0140, HPA-endorsed)

This is a genuinely new, more disciplined connecting structure than B1, arrived at independently
(no citation to M0051's own `App`/`Sat_c` apparatus, ~8 hours later, same day). It explicitly:
- Separates representation (`K_t^E`) from reasoning (`Cn_S`) from evaluation (`Eval_c`) from
  determination from decision from transition (`δ`) — a **typed pipeline**, not a single `Sat`
  function.
- Explicitly **retires** `Sat(K_t,r)≡K_t⊨Content(r)` as too strong (see §01).
- Leaves `Eval_c`'s own internal structure (the relation between Standing, Boundary, Reason,
  Context, and Provenance) **explicitly unresolved** — "remains subject to the evaluation research
  programme" (M0140 §X.4). This is the same defect-class MD-062 found in `Sat*` (purpose-relativity/
  `EC_t` never actually consumed) — now diagnosed **natively, independently, by the corpus's own
  self-review**, corroborating MD-062's finding from an entirely separate angle.
- Never once mentions `EC_t` by name. `Γ_t` (context) appears, but the specific `EC_t=(S_t,G_t,Q_t,
  C_t)` tuple from M0043 is not referenced. **`EC_t` itself is not consumed by B2 either** — the
  same structural absence MD-062 found in `Sat*`, now found in the pipeline that formally replaced
  the object MD-062 tested.

### Bridge candidate B3 — `Adequate(K,Q,Γ)⟺ℛ_req(Q,Γ)⊆Distinctions(K)` (new, this phase: M0165/M0187)

A third, structurally distinct object. This is not a bridge for M0043's `EC_t`/`Sat(K_t,r)` at all —
it is a **sibling apparatus** answering a parallel question (representation adequacy relative to a
required-distinction set) using unrelated primitives (`Distinctions(K)`, a state-partition
equivalence-relation universe). It reaches genuine closure (Category A, six frozen axioms, M0187)
but **does not bridge to, cite, or resolve anything about `EC_t`, `Req(EC_t)`, or `Sat(K_t,r)`.**
Recorded here as a discovered object, not as an answer to the mission's own named boundary question.

## Output 5: `standard` reconstruction

No file read this phase gives `standard` (M0047's `r=(...,standard,...)` field) a computed body.
M0140's own pipeline replaces the informal `Sat(K_t,r)` oracle with `Eval_content(K,r)=
Entails_S(K,Content(r))` for the content dimension only — but `standard` (the general "acceptance
criterion" field) is not the same object as `Content(r)`; M0140 never equates them, and the wider
evaluation dimensions (evidence, provenance, status, boundary, context, temporal, operational,
governance, contradiction) are explicitly named as **not** captured by entailment. M0165's `ℛ_req`
gives one candidate for what an "acceptance criterion" could formally look like for its OWN,
unrelated object (a distinction-preservation predicate) — not for M0043/M0047's `standard` field.
**`standard` remains `[OPEN]`, more precisely bounded than before** (it is not entailment, not the
distinction-preservation test) but still without a computed body anywhere read this phase.

## Output 6: `Sat` reconstruction

This phase's central, decisive finding. Three distinct positions on `Sat` were found, in strict
chronological order, all on 2026-09-02:

1. **09:35 (M0051):** `Sat(K_t,r)∈{⊤,⊥,U}`, class-indexed (`Sat_c`), eight `[PROP]`-only candidate
   bodies, real executed experiment (KR-SIM-2026-09-02-B), 3/8 classes executable per M0054's
   follow-up. Never frozen.
2. **17:53–18:00 (M0136→M0138→M0140):** A concrete candidate body is proposed
   (`Sat(K_t,r)⟺K_t⊨Content(r)`), then **explicitly rejected and formally removed from canonical
   theory**, replaced by a typed pipeline whose evaluation stage (`Eval_c`) is itself left
   unspecified beyond naming its component dimensions.
3. **18:20 (M0165→M0187):** A sibling apparatus reaches **genuine axiomatic closure** for its own
   `Adequate`/`EVal`/`Det` — but this is not `Sat(K_t,r)` under a new name; it never engages `EC_t`
   or `Req(EC_t)` at all.

**No file read this phase supplies a computed, corpus-frozen body for M0043's `Sat(K_t,r)` taking
`K_t`, `r`, and `EC_t` as arguments.** The closest real progress (M0051's `Sat_c`/`App` apparatus)
predates, and is not cited by, the same-day retirement act (M0138/M0140) that formally supersedes
the very idea of a single `Sat` function. This is stronger and more precise than MD-063/064's own
"identity unresolved" framing — it is not merely that `Sat`'s identity across sources is unclear; it
is that the corpus's own later self-review **actively decided against** keeping a single `Sat(K_t,r)`
object at all, on the same day, in an HPA-endorsed document.

## Output 7: `Δ_t` reconstruction

`Δ_t^sem={r∈𝓡_t^app|Sat(K_t,r)≠⊤}` (M0051) is the most operational `Δ_t` variant found across this
whole reconstruction's F4 work — computable in shape, given `App` and any one `Sat_c` body, but
inheriting `Sat_c`'s own incompleteness (only 3/8 classes executable, per M0054). M0140's own
pipeline does not restate `Δ_t` at all — the TODO register (M0138 §2.3/M0140 X.19) does not list
`Δ_t` among either the closed or the still-open items, an omission, not a resolution. **`Δ_t`
remains shape-computable-in-part (for the 3/8 executable `Sat_c` classes), not semantically
faithful, not corpus-frozen** — unchanged in substance from MD-061's own finding, now with a richer
evidentiary basis for exactly why it stops where it stops.
