# 1bf information-gain review: decision rules frozen BEFORE any host data exists (the human's four questions, F-LOG-0190)

Applied after Protocol C is sealed, by a worker and then an independent reviewer. Only the sealed Protocol C package plus the sealed A/B/1be records are used.

| Q | Question | YES requires (all) | Otherwise |
|---|---|---|---|
| **Q1** | Did GitHub provide an independent observable? | A channel returned EXISTS; it has at least one **HOST_GENERATED** timestamp field; and it is **not** a re-statement of a git object (a commit date shown by the API is CLIENT_SUPPLIED and is never Q1-YES) | NO / NOT_DETERMINABLE (NOT_ACCESSIBLE or NOT_RETAINED → NOT_DETERMINABLE, **never NO**) |
| **Q2** | Does it map to WP-4C-1 and its norm or target? | The record is linked **by API relation** to `e4724a44` or to a PR that contains it, **and** its content or field semantics concern permission or gating (a required check, a review approval, a protection rule) | NO if it is generic activity only; NOT_DETERMINABLE if the link is absent but not excluded |
| **Q3** | Are the actors independent of the norm-making authority? | **Not decidable from equality relations alone.** YES is possible only if the record's actor role is the **platform itself** (e.g. a host-enforced rule evaluation) **and** the rule's configuration author is shown to differ from the norm-maker. Different logins **never** suffice | INDEPENDENCE_NOT_DETERMINABLE by default; NO if SAME is observed for the approver/configurer and the committer |
| **Q4** | Does it distinguish H-D from H-B? | Q1 = Q2 = YES, Q3 ≠ NO, **and** the observable fixes the norm state at t **independently of the rulings register**, so that D1 is broken: under one hypothesis the observed state is predicted and under the other it is excluded. Apply the symmetry rule explicitly | NO, or NOT_DETERMINABLE |

## Mapping to the next step (the human's decision logic)
- **Q4 = YES → Case B.** STOP. Draft a small pre-registered discrimination experiment for human authorization; do not score.
- **Q4 ∈ {NO, NOT_DETERMINABLE} → Case A.** Prepare **H5** for the human: close H-D vs H-B as NON-IDENTIFIABLE ON THIS CORPUS.
  - The H5 brief must state precisely which bounded channels were exhausted.
  - It must also state what remains theoretically possible but outside the bound.
  - **It must not reopen the search.**

## Prohibitions (the human's instructions)
- No interpretation that a merge, a check or a CI run proves a norm was in force.
- No falsifier. No conformance judgement.
- **No resurrection of the 1bb machinery.**
