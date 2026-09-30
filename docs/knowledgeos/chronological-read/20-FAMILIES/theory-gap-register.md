# theory-gap-register

**Scope(s):** METHODOLOGICAL · **Row count:** 4 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `TG-01..TG-21`
**Aliases:** "Theory Gap Register"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0041, scope METHODOLOGICAL: "A disciplined, ID-numbered (TG-01..TG-21) consolidated gap register with an explicit rule: no proposal unless a corpus search for an existing resolution returned nothing; distinguishes OPEN, CORPUS RESOLVES-NOT-ADOPTED, BLOCKING, and NEW-in-this-pass dispositions across 21 gaps spanning mathematical/semantic/computational/empirical/governance classes."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1692 §"Register discipline (mandate §15). No gap below carries a proposal unless the corpus search returned nothing. Where the corpus ALREADY RESOLVES a gap, that is recorded as CORPUS RESOLVES -- NOT ADOPTED, and no verifier proposal is made, because inventing a second resolution for a solved problem is the failure ES-005.4 names."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1692 §"Register discipline (mandate §15). No gap below carries a proposal unless the corpus search returned nothing. Where the corpus ALREADY RESOLVES a gap, that is recorded as CORPUS RESOLVES -- NOT ADOPTED, and no verifier proposal is made, because inventing a second resolution for a solved problem is the failure ES-005.4 names."]

## Lifecycle

last_seen: S1948. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1692 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1692 |
| dependencies | PRESENT | S1692, S1948 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1692, S1694 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1948 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Classifies the 21-item register into three cross-cutting categories: three gaps are BLOCKING (id/mutability contradiction TG-06, delta commit-case undefined TG-09, Qualify has no body TG-14 -- nothing downstream can be trusted until these resolve); three are already resolved by the corpus and merely await adoption (TG-03 world/observation layer, TG-04 recognised-dimension set, TG-05 uncertainty carrier); and two plus a reclassification are new to this pass (TG-10 Sigma blind to R, TG-15 the Omega overload, and reclassifying TG-06 as a contradiction rather than a mere gap). [S1692]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1692] types=[GOVERNANCE, PRINCIPLE] scope=METHODOLOGICAL — "States and enforces a register discipline: gap documented -> counterexample -> corpus searched for an existing resolution -> only then a proposal labelled VERIFIER RECOMMENDS; three gaps (TG-03 world/observation layer, TG-04 recognised-dimension set, TG-05 uncertainty carrier) are found already resolved by the corpus and therefore carry no verifier proposal, on the explicit ground that inventing a second resolution for an already-solved problem is the ES-005.4 failure mode." (anchor: "Register discipline (mandate §15). No gap below carries a proposal unless the corpus search returned nothing. Where the corpus ALREADY RESOLVES a gap, that is recorded as CORPUS RESOLVES -- NOT ADOPTED, and no verifier proposal is made, because inventing a second resolution for a solved problem is the failure ES-005.4 names.")
- [S1692] types=[ANALYSIS, GOVERNANCE] scope=THEORY-LEVEL — "Classifies the 21-item register into three cross-cutting categories: three gaps are BLOCKING (id/mutability contradiction TG-06, delta commit-case undefined TG-09, Qualify has no body TG-14 -- nothing downstream can be trusted until these resolve); three are already resolved by the corpus and merely await adoption (TG-03 world/observation layer, TG-04 recognised-dimension set, TG-05 uncertainty carrier); and two plus a reclassification are new to this pass (TG-10 Sigma blind to R, TG-15 the Omega overload, and reclassifying TG-06 as a contradiction rather than a mere gap)." (anchor: "Blocking (nothing downstream can be trusted until resolved): TG-06, TG-09, TG-14. Already resolved by the corpus, awaiting adoption: TG-03, TG-04, TG-05. New in this pass, on no prior list: TG-10, TG-15, and TG-06 as a CONTRADICTION rather than a gap.")
- [S1694] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL, also labeled `eight-separate-closure-senses-verdict` — "Final programme statement: the theory is not closed (21 gaps, 3 blocking, 1 internal contradiction, 11 overloaded terms; 4 of 6 previously-declared closures do not survive attack) but is closer than the prior verdict allowed (three previously-excluded capabilities are already constructed in the corpus, and what stands between them and the theory is a governance decision, not a mathematical obstacle); declares an explicit STOP condition halting any further theory-extension phase, with every proposal labelled VERIFIER RECOMMENDS, every corpus resolution labelled NOT ADOPTED, and the one normative question left unanswered for governance." (anchor: "THE THEORY IS NOT CLOSED, AND IT IS CLOSER THAN THE PRIOR VERDICT ALLOWED. ... STOP. No theory-extension phase follows this pass. Per mandate §15, nothing here has been silently repaired: every proposal is labelled VERIFIER RECOMMENDS, every corpus resolution is labelled NOT ADOPTED, and the one normative question is put to the PO/ARB unanswered.")
- [S1948] types=[CONTRADICTION, WARNING] scope=OBJECT, also labeled `step280-281-harness-id-collision-defect` — "TG-06 (Identity: 'id hashes a mutable field') remains OPEN and BLOCKING according to the theory-gap-register, and is 'not marked closed anywhere', even though two mutually opposite proposed repairs exist in the corpus: one proposes projecting Evidence.state OUT of the hashed id, while the applied Step-282/13 fix (this batch's C-NEW closure) keeps state IN the hash (alongside ref and polarity); the synthesis flags this as an unreconciled contradiction between TG-06's own open status and the fix this batch's C-NEW record treats as closing an id-hash defect." (anchor: "| Identity | `id = H(P,e,c,t,Π)`; **TG-06 OPEN, BLOCKING** — "id hashes a mutable field"; **two opposite\nrepairs exist** (project `e.state` OUT vs the applied step-282/13 fix that keeps `state` IN); TG-06\n"is not marked closed anywhere" |") — lineage claim: SOURCE-CLAIMED-CONTRADICTION of the C-NEW closure record's id-hash fix (keeps state IN) versus a different corpus proposal to project e.state OUT — review_flag: TYPE-QUESTION

## Notes for P3

- Lifecycle is mechanically flagged CONTESTED, with `contested_by_own_contradiction_type: true` — a CONTRADICTION-typed row is present (S1948); P3 should verify the specific tension directly rather than take the flag as settled.
