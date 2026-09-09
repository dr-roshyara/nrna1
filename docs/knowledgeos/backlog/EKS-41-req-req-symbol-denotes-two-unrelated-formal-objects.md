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
