# Is each START's pre-act authorization state independently established?

Sources used: `SOURCES.md` (R-47, R-56, R-58, R-65, R-72, R-78, R-79, R-81, R-86, git 6a67da5d7) and `START-EVENTS.json`. No other sources.

## Verdict

**FORMAL CONSEQUENCE:** None of the 11 events is INDEPENDENT. So the frozen claim "START legal ⇔ target authorized" has **no** non-circular contrast pair (two INDEPENDENT events, different clusters, opposite outcomes). In these sources its premise is never exercised non-circularly.

| Class | Count |
|---|---|
| INDEPENDENT | 0 |
| SAME-STATEMENT | 1 (R-72) |
| OUTCOME-DERIVED | 0 |
| ACT-NOT-ATTESTED | 9 |
| UNCLEAR | 1 (git 6a67da5d7) |

Coded `s` mismatches: 1 (the WP-8 event).

## Per event

**1. START WP-4B at R-72: SAME-STATEMENT. `s` matches.**
- SOURCE FACT (R-72): "THAT PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN". The state and the non-start are one sentence joined by "SO", which is the task's own example of this class. The blockers (a)–(c) follow a colon in the same sentence. "CONSEQUENCE: no RED begins" repeats the outcome and adds no state.
- INFERENCE: no attempted START is described, and "does not begin" is not a refusal of an attempt. So the coded outcome REFUSED is an interpretation.

**2. START WP-4B, commit 6a67da5d7 (08-03 16:10): UNCLEAR. `s` = UNK matches.**
- SOURCE FACT: this is the **only** START actually attested: "WP-4B RED … Four keystones written; 4 failed … STOP at the RED boundary."
- SOURCE FACT: the only state statement is the actor's own claim in the same commit: "approved decision (R-72 authorization, R-76 scope)".
- SOURCE FACT: R-72 (08-02) says implementation does not begin because the proviso is unmet.
- SOURCE FACT: R-79 (08-03) puts "(1) the two remaining Board acts" before "(2) WP-4B RED". The commit itself says "the two Board acts are outstanding".
- SOURCE FACT: later text treats the seam as authorized. R-81 (08-04) calls it "the seam already authorized by R-72", and R-86 says "authorized to *resume* implementation". Both come after the act, and R-81 was only PREPARED when written.
- UNKNOWN: whether R-73..R-77 (not in the sources) discharged R-72's proviso before 16:10.
- UNKNOWN: whether R-79 came before or after the commit.

**3. START WP-8 (R-79): ACT-NOT-ATTESTED. `s` does NOT match.**
- SOURCE FACT: "NO IMPLEMENTATION WORK FOR WP-8 SHALL COMMENCE …" and "NOT PERMITTED: any implementation work for WP-8." This is a prohibition, not a described attempt.
- SOURCE FACT: the permission covers planning only: "R-79 grants PERMISSION for WP-8 planning activities only."
- INFERENCE: for an implementation START, the source's state is **deferred / not authorized / not even permitted**. "permission-only" wrongly carries planning permission over onto the START target. R-72 adds a second, independent precondition ("MANDATORY BEFORE WP-8").

**4. START 7A (R-47): ACT-NOT-ATTESTED. `s` = authorized matches.**
- SOURCE FACT: "SLICE 7A EXECUTION AUTHORIZED … 7A ONLY."
- SOURCE FACT: no source describes 7A RED being begun. "First authorized activity" and "execution responsibility transfers to engineering" state permission and responsibility, not performance.
- INFERENCE: the coded outcome PERFORMED has no source.

**5. START 7B after AUTHORIZE(7A) (R-47): ACT-NOT-ATTESTED. `s` = not-authorized matches.**
- SOURCE FACT: "7B and 7C are NOT authorized and follow their own gates." No attempt is described.

**6. START 7B at R-56: ACT-NOT-ATTESTED. `s` = not-authorized matches.**
- SOURCE FACT: "Execution of 7B is a SEPARATE act and has NOT been issued" (a recording note, "not part of this ruling"). The effect column says "execution still NOT authorized". No attempt is described.

**7. START 7B at R-58: ACT-NOT-ATTESTED. `s` = authorized matches.**
- SOURCE FACT: "Engineering is authorized to begin Slice 7B RED …" and "planned -> RED AUTHORIZED."
- SOURCE FACT: no performance is described.

**8. START 7C at R-58: ACT-NOT-ATTESTED. `s` = not-authorized matches.**
- SOURCE FACT: "Slice 7C remains unauthorized." No attempt is described.

**9. START 7C at R-65: ACT-NOT-ATTESTED. `s` = authorized matches.**
- SOURCE FACT: "SLICE 7C AUTHORIZED … engineering may begin RED."
- SOURCE FACT: "Engineering shall implement" is a directive (commissioning in R-79's terms), not a performance.

**10. START §12 reconcile at R-81: ACT-NOT-ATTESTED. `s` = not-authorized matches.**
- SOURCE FACT: "Batch 7's freeze is lifted FOR THIS REPAIR ONLY" and "all other Batch-7 work still frozen". R-81 never names §12.
- INFERENCE: §12 counts as batch-7 work only because R-86 lists "R-84's §12 reconcile" under "WP-4B BATCH 7 IS RELEASED".
- SOURCE FACT: by the provenance annotation, even R-81's own repair was only PREPARED at that point ("BATCH 7 IS RELEASED DOES NOT HOLD").

**11. START §12 reconcile at R-86: ACT-NOT-ATTESTED. `s` = authorized matches.**
- SOURCE FACT: "engineering is AUTHORIZED to execute … R-84's §12 reconcile."
- SOURCE FACT: no performance is described.

## Non-circular contrast

- **FORMAL CONSEQUENCE:** no qualifying pair exists (`exists: false`).
- INFERENCE: the nearest candidate is R-72 (not begun) vs 6a67da5d7 (performed): same target, different clusters, opposite outcomes. It fails on both sides: R-72 is SAME-STATEMENT and the commit is UNCLEAR.
- INFERENCE: left unresolved, this pair is a **possible counterexample** rather than support. It is a performed START whose last independently recorded state is "proviso unmet". It could be resolved either way by R-73..R-77 or R-76, none of which are in the sources.
- INFERENCE: in eight of the nine authorization rows (all except R-72), the state really is stated separately from any outcome. That is exactly why they cannot count: the outcome half of those events is not attested at all. The corpus establishes states without acts, and the one real act without a state.

## Ambiguities

1. **REFUSED vs "not begun" vs "prohibited".** No source describes a START attempt being refused. R-72 states a non-start as a consequence, and R-47/56/58/79 state non-authorization or prohibition. Coding these as outcome = REFUSED, rather than "no act", is the main source of apparent support.
2. **PERFORMED without attestation.** Events 4, 7, 9 and 11 are coded PERFORMED but no commit or row reports the work. Only WP-4B has a commit.
3. **Authorization vs permission vs commissioning.** R-79's annotation separates PERMISSION, AUTHORIZATION, COMMISSIONING and EXECUTION. The WP-8 coding ("permission-only") mixes up planning permission with the implementation target.
4. **"Authorized in principle" (R-72) vs execution authorization.** R-81 later calls the seam "already authorized by R-72", which conflicts with R-72's "IMPLEMENTATION DOES NOT BEGIN". Whether WP-4B was authorized on 08-03 turns on which reading governs, and on rulings not in the sources.
5. **Intra-day ordering on 08-03.** R-79 and the commit share a date. Whether R-79's sequence ("Board acts before WP-4B RED") was already in force at 16:10 is UNKNOWN.
6. **Prepared vs adopted (R-81..R-85).** Whether an R-81-era state is "not authorized" because batch 7 was frozen, or because the ruling was merely PREPARED, changes the grounds but not the coded value.
7. **§12 membership in batch 7** is inferred from R-86. R-81 is silent on §12.
8. **R-56's state sentence** is a "recording note, not part of this ruling". It is weaker than a ruling, though the effect column agrees with it.
9. **R-78** contains no START event and was not used except as context.
