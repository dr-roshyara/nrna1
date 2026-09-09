# EKS-41 — The `ℛ_req` symbol denotes two unrelated formal objects, never cross-referenced

## Problem, in business language

Two separate strands of the KnowledgeOS research corpus independently chose the identical
mathematical symbol `ℛ_req` for two completely different ideas, on the same research day, and
neither strand appears to know the other exists. Anyone reading the corpus later — a researcher, a
reviewer, or an AI assistant reconstructing the theory — has no way to tell, from the symbol alone,
which of the two unrelated meanings is intended in any given document. This is exactly the kind of
silent ambiguity that produces wrong conclusions later: someone could reasonably (and wrongly) treat
a finding about one `ℛ_req` as if it applied to the other.

## What the two meanings actually are

1. **`Req(EC_t)`** (M0043, M0047 — 2026-09-02, ~00:46–08:00): the set of *requirements* a knowledge
   state must satisfy under a given "epistemic contract" `EC_t`. Each requirement is a record with
   fields like id, type, scope, content, an acceptance standard, priority, and validity. This is
   part of the theory's central open question: whether a knowledge state `K_t` satisfies its
   requirements (`Sat(K_t,r)`).

2. **`ℛ_req(Q,Γ)`** (M0165, M0187 — 2026-09-02, ~18:20): a completely different object called the
   "Required Distinction Universe" — a set of *distinctions* (ways of telling two system states
   apart) that a knowledge representation must be able to preserve, given a question `Q` and a
   context `Γ`. This object is used in a different, separately-closed piece of theory (the "ABK-1"
   kernel architecture) to define representation adequacy: a representation is "adequate" if it
   keeps all these distinctions visible.

These are not two versions of the same idea — they have different arity, different domains, no
shared fields, and neither document mentions the other's existence or the `EC_t`/`Sat(K_t,r)`
apparatus at all.

## Why this matters

- A reader following citations or search results for `ℛ_req` will find results from both threads
  mixed together with no signal that they are unrelated.
- Any future attempt to "close" or "ratify" `ℛ_req` risks silently merging two unrelated ideas under
  one name, producing a theory that looks more unified than the evidence actually supports.
- This is the same class of problem this reconstruction has already found and tracked several times
  under different symbols (see `EKS-23`, two independently-invented Kernel-capability vocabularies;
  `EKS-28`, two governance commissions that never cross-reference each other; `EKS-34`, a Lane-T-side
  naming collision; `EKS-36`, two same-day sibling research threads that never cross-cite) — this is
  a further, distinct instance of the same recurring corpus-hygiene pattern, this time a bare symbol
  collision rather than a conceptual one.

## Recommended resolution (not performed by this ticket — governance decision required)

Rename one of the two `ℛ_req` objects (most naturally, the "Required Distinction Universe" object
from M0165/M0187, since it is the later-arriving, more narrowly-scoped one) to a distinct symbol
before either thread is further developed or ratified. This ticket does not perform that rename —
it only records the problem so a future, separately-authorized phase can decide and act on it.

## Discovery context

Found during MD-066 (Chronological Definition Reconstruction, `three_model_convergence/
14_decision-log/MD-066-chronological-definition-reconstruction/`), a phase specifically re-reading
the corpus in chronological order around 2026-09-02 to check whether an earlier "no boundary found"
finding (MD-063/064) had been premature.

---

## Appendix A — a THIRD meaning, and it is the oldest of the three (added by P-96, 2026-09-09)

The chronological read of the `phase_measure_theory/` lane found a **third** use of the same base
symbol, **three days earlier** than either of the two above.

**`ℛ` = the family of representation functions.**
`docs/knowledgeos/brainstorming/phase_measure_theory/20260830-215918_step_276_foundational-gap-reconciliation-and-closure-audit-final.md`,
§276.5 ("Operation typing must be revisited"), **2026-08-30 21:59**. It partitions the operation
universe by signature class and assigns:

```
𝒯 = state transformations      𝒬 = queries/observations
𝒢 = governance functions       ℛ = representation functions      (Serialize, Deserialize, Save, Load)
```

So the ordering is: **`ℛ` = representation functions (08-30 21:59) → `Req(EC_t)` (09-02 ~00:46) →
`ℛ_req(Q,Γ)` (09-02 ~18:20).**

### Why this makes the ticket worse, not merely longer

- The **oldest** meaning is the one furthest from the other two: `ℛ` there ranges over *functions on
  representations* (serialisation), whereas `ℛ_req` ranges over *distinctions a representation must
  preserve*. A reader who has met `ℛ` first will read `ℛ_req` as "the required representation
  functions" — a reading that is wrong, plausible, and undetectable from the symbol.
- It shows the collision is **not** a two-strand accident on a single day. The base glyph was
  already load-bearing in a third sense before either strand chose it.

### One mitigating fact, recorded for fairness

The `ℛ` = representation-functions use was **repaired two minutes later**: `step_277` (2026-08-30
22:01) §277.5 re-glyphs the same five classes as `𝒪_T ∪ 𝒪_E ∪ 𝒪_Q ∪ 𝒪_G ∪ 𝒪_R`, moving
representation functions to `𝒪_R`. **The repair was never recorded as a repair**, so the colliding
text stands in the corpus unmarked and is what a glyph search still returns.

### Consequence for the candidate requirement

The disambiguation ruling this ticket asks for must cover **three** meanings, not two, and must state
that `ℛ` (bare) and `ℛ_req` are unrelated. It should also record the `𝒪_R` re-glyph as the
disposition of the first meaning, so a reader who finds the 21:59 text knows it was superseded
within two minutes.

### Evidence

- `phase_measure_theory/20260830-215918_step_276_…-final.md` §276.5 — the `ℛ` definition.
- `phase_measure_theory/20260830-220134_step_277_transformation-inventory-and-o-core-closure.md`
  §277.5 — the unmarked repair.
- Read record: `docs/knowledgeos/theory-extraction/117-P96-QUEUE-DRIVEN-CHRONOLOGICAL-READ.md` §3.3, §5.1.
