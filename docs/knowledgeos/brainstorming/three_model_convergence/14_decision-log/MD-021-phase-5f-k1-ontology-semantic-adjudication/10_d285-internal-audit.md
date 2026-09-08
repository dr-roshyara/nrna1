# Phase 5F — D285-1/D285-6/D285-7 Internal Consistency Audit

Audits the D285 package **without modifying it** — any issue found is a Phase-5F finding only, per the
authorization's §14/§20.

## Checks performed

1. **K-1 through K-7 labels**: consistent between D285-1 and D285-7 (both use the identical seven
   labels with compatible, non-contradictory content). **No inconsistency found.**
2. **Authority claims**: D285-1's "RATIFIED" claim for K-1 and D285-7's "ratified" reference are
   consistent. **No inconsistency found.**
3. **Primitive lists**: D285-1's 8-primitive list matches seq 0630's own §49.29 boxed result exactly
   (`Entity, State, Event, Observation, Proposition, Relation, Policy, Action`). **Faithful, confirmed.**
4. **The 2-component verification formulation**: consistent between D285-1 (`K=(𝒜,ℛ)`) and D285-6
   (same). **No inconsistency found.**
5. **The `Assertion` expansion — a genuine internal variance found**: D285-1 §2 states `Assertion`
   expands to *"Proposition, Entity, Evidence, Context, Time, Provenance"* (6 items). D285-6 §3 states
   the unpacking is *"`{Proposition, Entity, Observation}` + `{id,c,t,Π}`"* (3 named + 4 further
   fields, and **includes `Observation`, which D285-1's own list does not**). **These two unpackings
   are not verbatim identical.** This is disclosed as a **genuine internal tension within the D285
   package**, not silently reconciled — it is plausible the two documents describe the same
   `Assertion` composite at different levels of detail (D285-1 naming conceptual fields, D285-6 naming
   a more technical field set including timestamps/identifiers), but this phase does not assert that
   reconciliation as established fact.
6. **The Observation correction**: D285-1's own ¶43 revision (from "the lanes agree" to "narrowed, they
   do differ on Observation") is **internally consistent with, and directly cited by, D285-6 §4b's own
   "REVISED 2026-08-31" note** — both documents carry the same revision, dated identically. **No
   inconsistency found; if anything, this is unusually strong internal cross-referencing** for this
   corpus (recall seq 0311's own finding that `kernel/`'s 141 documents are "almost entirely
   unlinked" — the D285 package is a notable exception to that general pattern).
7. **The statement concerning State**: D285-1's "`State`-as-primitive remains unimported" claim is not
   contradicted anywhere in D285-6 or D285-7 (neither document claims a recovery construction for
   `State`). **No inconsistency found** — but also no independent confirmation beyond the shared
   absence of any counter-claim.
8. **The vocabulary-zero measurement (GN-75)**: D285-1 cites "`𝒜·ℛ·Σ·Q_t·𝒪·Provenance·Replay·
   Measurement` = 0 occurrences." **Not independently re-measured this phase** (that would require a
   corpus-wide grep against the full `ratified artifacts` set, defined by GN-75's own — unlocated —
   methodology) — restated as reported, not re-verified at evidence level 1.
9. **The statement "two different objects wearing one letter"**: D285-1 §4's own conclusion
   (`K_t` ≠ "the knowledge state") is a **methodological/semantic claim, not a mathematical one** — it
   is not tested against a formal criterion in D285-1 itself, and this phase does not attempt to
   formalize it further (doing so would exceed Phase 5F's own scope, which is the K-1/K-2/K-1-B
   question specifically, not a general "what is Knowledge" investigation).

## Summary

**One genuine internal tension found** (item 5, the `Assertion`-unpacking variance between D285-1 and
D285-6). **No other inconsistency found.** The D285 package is, by this corpus's own general standard
(per seq 0311's dependency-map finding), **unusually well cross-referenced and self-correcting** — it
revises its own earlier claims explicitly and dates the revisions, a discipline this reconstruction's
own methodology has repeatedly had to supply externally for other parts of the corpus.
