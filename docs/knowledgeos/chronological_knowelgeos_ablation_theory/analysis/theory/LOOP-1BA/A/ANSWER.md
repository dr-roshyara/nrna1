# Did the recorded work conform to the norm in force?

Sources: `SOURCES.md` only.

## Candidate 1: WP-4B RED commit 6a67da5d7 (2026-08-03 16:10:37 +0200)

### 1. Norms on starting implementation of WP-4B

| Ruling | Date | Kind | Quote |
|---|---|---|---|
| R-72 | 2026-08-02 | Conditional; proviso unmet, so start prohibited | "authorized … *provided its authorization package demonstrates executable scope and no unresolved governance blockers remain*. THAT PROVISO IS NOT MET TODAY, SO IMPLEMENTATION DOES NOT BEGIN" … "CONSEQUENCE: no RED begins. The next acts are the three decisions and the scope ruling; when they land, engineering proceeds RED → GREEN → VERIFY → acceptance evidence" |
| R-76 | 2026-08-03 | Closes proviso item (c) only | "the scope ambiguity … IS CLOSED. RED carries the roadmap keystone … and not the confirmation half." |
| R-77 | 2026-08-03 | Block restated | "R-73–R-76 stand · WP-4B's authorization (R-72) stands · … ⚠️ AND WP-4B REMAINS BLOCKED: … IT DOES NOT GIVE `ContestedOutcomeRef` AN INBOUND PATH. R-75's adopted path remains prohibited by ADR-UL-01, ADR-PL-01, WP-5's accepted keystone and a live test." |
| R-79 | 2026-08-03 | Sequence/gate | "ENGINEERING IS READY AND NOT EXECUTABLE" … "SEQUENCE AFFIRMED: (1) the two remaining Board acts — PROMOTION … and ALLOCATION … · (2) WP-4B RED → GREEN → VERIFY → ACCEPT" … "It authorizes nothing" … "the two Board acts remain the gate." |
| R-80 | 2026-08-03 | Descriptive only | "authorized read as executable (R-72's unmet proviso)" |
| R-86 | 2026-08-04 | Permitted | "Batch 7 is released … Engineering is authorized to resume implementation in accordance with the adopted governance." |

### 2. Norm in force at 16:10 on 2026-08-03

- **SOURCE FACT (order from text):**
  - R-77 says "R-73–R-76 stand", so it comes after R-76.
  - R-78 is "the natural successor to R-77" and "does not reopen R-73–R-77".
  - R-79 "does not reopen R-73–R-78".
  - R-86 adopts R-81..R-85, which "become governing rulings effective immediately". So before R-86 (dated 2026-08-04) they were not governing.
  - By text, the order is R-72 → R-76 → R-77 → R-78 → R-79, and R-86 comes later, on 2026-08-04.
- **SOURCE FACT:** None of R-76..R-80 says the R-72 proviso is met. R-77 says WP-4B "REMAINS BLOCKED". R-79 says engineering is "NOT EXECUTABLE" and puts the Board acts before RED. R-80 calls the proviso "unmet".
- **INFERENCE (timeline used as secondary evidence):** The text gives only dates for R-77 and R-79, the same day as the commit, not hours. To place them before 16:10 I rely on the recording timeline: R-77 was written into the register at 15:28:34 and R-79 at 15:47:45. A ruling cannot be recorded before it is decided, so recording time is an upper bound on decision time. That is enough here. Neither ruling carries a PREPARED marker. **Order basis: MIXED.**
- **INFERENCE:** In force at 16:10 was R-72's authorization with its proviso unmet, as restated by R-77 and R-79. No permitting norm was in force.

### 3. Verdict: **NON-CONFORMS**

The norms separate RED from GREEN, and they gate **RED** explicitly:
- R-72 says "no RED begins".
- R-79's sequence puts "(1) the two remaining Board acts" before "(2) WP-4B RED".

The commit shows the author treated the gate as applying to GREEN only:
- "GREEN not attempted: three producers are absent and **the two Board acts are outstanding**."
- The commit cites "approved decision (R-72 authorization, R-76 scope)". But R-72's authorization is conditional, and R-76 closes only item (c).
- R-80 names this exact conflation: "*authorized* read as *executable* (R-72's unmet proviso)."

"No production code written" does not help, because RED itself is the phase that is gated.

**UNKNOWN (possibly missing):**
- The text of R-73..R-75 and R-81..R-85.
- The exact hour of decision of R-77 and R-79.
- Any Board act between 15:47 and 16:10 that was never recorded.

None of these is likely to flip the verdict. R-77 already holds that R-73..R-76 stand and WP-4B is still blocked, and R-81..R-85 were not governing until 2026-08-04.

## Candidate 2: R-89 vs R-72's gate

1. **SOURCE FACT, the gate (R-72):** "WP-4C AND WP-4D ARE EXPRESSLY NOT AUTHORIZED. Each is to be authorized from IMPLEMENTATION EVIDENCE rather than roadmap intent, **after its predecessor is accepted**." Also: "WP-4C is considered only after WP-4B is accepted."
2. **Was WP-4B accepted (R-87) before R-89? Yes.**
   - **SOURCE FACT (text):** R-88 cites R-87 ("Per R-86 and R-87, no standing delegation"). R-89 cites R-88 and its adoption ("single owner — Adjudication (R-88)"; "R-88's adoption was a SINGLE ACT"). So the order is R-87 < R-88 < R-89.
   - **SOURCE FACT (timeline):** The timeline corroborates this: R-87 at 01:47:00, R-88 at 01:54:24, R-89 at 02:08:48, all on 2026-08-04.
3. **Is R-89 in force? No.**
   - **SOURCE FACT:** R-89 says "⚠️ PROVENANCE: ISSUED BY THE ARB CHIEF — PREPARED, NOT ADOPTED … this ruling awaits the Decision Authority." Unlike R-88, it has no adoption annotation.
   - R-86 says: "Any future ruling issued by the ARB Chief remains PREPARED, NOT ADOPTED, until the Decision Authority acts on it."
4. **Verdict: CONFORMS**, measured against the gate.
   - **INFERENCE:** R-89 comes after WP-4B's acceptance. It bases its authorization on engineering evidence (the EP-03 readiness review and existing code), not on roadmap intent.
   - The gate carries over to WP-4C-1 via the R-88 subdivision (also an inference).
   - Caveat: R-89 is a conforming *draft*. It has no force and authorizes nothing yet, and no execution under it is recorded.

## Part 3: bearing on "work conforms to the norms in force"

- **C1: AGAINST.** Strength: one case, one episode (a single RED commit at a single boundary).
  - It is enough to refute a *universal* conformance claim, but it tells us nothing about how often non-conformance happens.
  - It is mitigated, not cured: the commit disclosed the outstanding acts, wrote no production code, and WP-4B was later accepted (R-87). Acceptance does not ratify how the work started.
- **C2: NEITHER.** R-89 is a governance act, not recorded work, and it is PREPARED rather than in force. It shows that governance sequencing was respected in one episode. It is not evidence about whether work conforms.

## Ambiguities

1. **The R-72 proviso vs R-73..R-76.** On paper, R-73..R-75 settled ownership and paths, and R-76 settled scope, so the three decisions and the scope ruling had "landed". R-72's wording ("when they land, engineering proceeds RED → …") could be read as permitting RED once they did. But R-77 then says WP-4B "REMAINS BLOCKED" because the path R-75 selected is prohibited. The proviso's second limb, "no unresolved governance blockers remain", was therefore unmet.
2. **Do "the two Board acts" (promotion and allocation) gate RED?** R-79 says yes, by sequence. R-86 (2026-08-04) later says they "bear on the production producer path and §WP-4 closure, not on batch 7's execution". That could be read as saying they never gated test-only work. R-86 is later and about batch 7, though, and it does not revise R-79 retroactively.
3. **Producer-agnostic RED.** The commit deliberately leaves producers unnamed, so the tests arguably do not depend on the blocked `ContestedOutcomeRef` transport. That is an engineering argument; no ruling grants a test-only exception.
4. **Recording time vs decision time.** Recording time bounds decision time from above. That is enough for R-77 and R-79 to precede 16:10, but it assumes they were in force when written, since neither is marked PREPARED.
5. **R-86 "resume implementation".** This implies implementation had already started and paused, which fits an earlier start. It is not a ratification of that start.
6. **"IMPLEMENTATION EVIDENCE" in R-72's gate.** It is unclear whether this means evidence from WP-4B's implementation or any code-level evidence. R-89 uses a readiness review and existing code.
7. **R-80 records the "authorized read as executable" conflation as having occurred.** It does not say whether this commit is the instance it means.
