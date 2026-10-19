# Step 8 and strength scan: §5.4 C7–C8 (S2377) and Ch. 9 §9.1

*2026-10-19, before the S0036–S0040 review, where these sections go as new theory.*

## §5.4 C7 (revised) and C8 (new)

| Dependency | Status | Definitions kept? | Result contradicted? | Fix |
|---|---|---|---|---|
| CON-0080, the gap operator (S0896) | PROVISIONAL → **unverified** | yes | no; C8 compares S2377 with it | named in the CON-0101 row as `same_subject_as` |
| C1, Unknown must be split (Ch. 2 §2.8.8) | **unverified** (BRANCH-01, no VALIDATED verdict) | yes | no | C8 states that binary Sat repeats the collapse |
| C2, (status, criticality) decides | **unverified** | yes | no | C8: weights do not replace criticality; C7: inclusion compatible |
| C3 / CON-0081, the status set mixes kinds | INVALID | — | — | not used as an axis in C8; the table compares fields, not rival typologies |
| 5.4.4 ContractDerivation (open) | open | — | — | C8 item 2 bears on it; it stays open |
| Principal's decision: the "Gap theory" is S2377 | decision | — | — | C7 rewritten: restatement, not convergence |

**Checked in the source:** S2377 never names S0896 (0 hits for S0896, "025d" or "zero algebra"), so "dependence not shown" holds. "Prohibited", "not applicable", "criticality" and "blocking" are absent from S2377 (0 hits; the source was also read in full). All six S2377 quotes match at their anchors (script).

**Strength scan (C7–C8):** 4 strong words, each justified:
- "only" ×3: "comparable only under the same contract" is S2377's own condition; "derived only after the set"; "add comments" (weakened from "only");
- "never names S0896": checked by search.

## Ch. 9 §9.1

- The dependencies are in §9.1.3. Ch. 1 A1 is used for the shape of an invariant only; F13 and the kernel's attribute set are **unverified**, so they are replaced by a toy state and the section says so.
- **Strength scan:** 8 words. They are definitional ("using only M"; refuse is "always ⊥"), limiting ("only illustrates", "not a proof"), or a result inside the model's scope ("all three trivial cases").
- **Trivial cases (Rulebook §4):** ∅, refuse and noop all run and all fail Req.
