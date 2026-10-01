# H5 proposal: close the H-D / H-B discrimination line as NON-IDENTIFIABLE ON THIS CORPUS (for human adoption)

| | |
|---|---|
| Status | **ADOPTED by human act, 2026-10-01** (F-LOG-0197): "Adopt H5, waive 0190". The F-LOG-0190 exhaustion condition is **knowingly waived** |
| Trigger | Human act (2026-10-01): "Skip H1 now" (F-LOG-0196). Protocol C stays sealed and **not executed** |
| Coder family | Every finding cited here is same-family (Claude). The non-Claude Gate 1 coding (R2) is pending |
| Review | `kos-theory-reviewer`, PASS_WITH_LIMITATIONS. The 8 corrections are applied in this version (`REVIEW.md`) |

## 0. Waiver the human must make knowingly
**The human's own condition (F-LOG-0190):** "We do not declare non-identifiability until the bounded, genuinely informative evidence channels have been exhausted."

**H1 was not exhausted. It was skipped.**
- The frozen information-gain rule (`LOOP-1BF/INFOGAIN-PREREG.md`) leads to H5 only after Q4 has been evaluated. **Q4 was never evaluated.**
- **Adopting H5 now therefore waives the F-LOG-0190 condition.** The adoption act should say so.

## 1. Proposed statement
> **On the acquired evidence and within the authorized scope, the competing hypotheses H-D and H-B are non-identifiable.**
>
> No independent norm-state observable was found. Without one, D1 holds: work evidence cannot separate "the norm changed without a recorded act" from "the work violated an unchanged norm".
>
> **Whether such an observable exists anywhere is NOT_DETERMINABLE** (1be). The bounded host-side channel was **not examined**, by human decision.
>
> **Neither hypothesis is falsified or validated.** Both are retained as unresolved alternatives.

## 2. Channels examined (sealed)

| Channel | Result | Record |
|---|---|---|
| Register / log coding under A′ | Act records are almost all performed acts (23 performed vs 1 refused), so no act-level contrasts exist. Status mediation: **2 episodes, both self-reported by the rulings themselves** | `LOOP-1BB/STATE.md` |
| P1–P8 predictions | Each is NON-IDENTIFIABLE or CONFOUNDED as a discriminator | `LOOP-1BB/IDENTIFIABILITY.json` |
| Local execution traces (Protocol A) | The earliest WP-4C-1 trace is `e4724a44`, 2026-08-04T00:30:25Z, RECORDING_ORDER only | `LOOP-1BC/` |
| Local norm-status channels (Protocol B) | No locally checkable channel is an independent norm-in-force record; the overlap test is underpowered | `LOOP-1BD/` |
| Discrimination readiness | No independent observable in A ∪ B; its existence elsewhere is NOT_DETERMINABLE | `LOOP-1BE/` |

## 3. Channels and items NOT examined (the disclosed gap; this weakens the statement)

**Host-side records** (PR approvals, branch protection, rulesets, CI runs, repository events) were **not examined, by human decision.** Protocol C (`LOOP-1BF/A2/scope_c.sh`) is sealed and SAFE TO RUN (authenticated). Its stated limits:
- protection, branch rules and rulesets return **current state only**;
- events retention is an **unverified assumption**;
- Q3 defaults to INDEPENDENCE_NOT_DETERMINABLE, because different logins never suffice;
- Q2 requires an API relation to `e4724a44` or its PR.

The low expected gain (< 10%) is a **main-analyst estimate, not a reviewed finding.**

Also not examined:
- **Signer verification:** declined (H2 = NO).
- **Content of status, register, CI and hook files:** Protocol B checked existence and metadata only. K-CI and K-HOOK content and enforcement history were not read.
- **Uncoded or unacquired items:** `IMPLEMENTATION_PROGRESS.md:58` was not acquired. The further execution reports (R-83 grep, "Container resolution executed", R-41, S0815) are uncoded.
- **Self-contamination:** the A and B captures predate R-SELF, and 21/51 Protocol A matches are programme artefacts. They are not re-run (R-SELF applies forward only).
- **Corpora outside this repository.**

## 4. What H5 does NOT mean
- It is not support for H-D and not support for H-B.
- It is not a failure of the research. It is an **identifiability result**, relative to the evidence and the scope.
- **The candidate anomalies stay open, with their directions recorded symmetrically** (`IDENTIFIABILITY.json`):

| Item | Direction | Status |
|---|---|---|
| WP-4B RED (P2) | weakly **against H-B** (a one-sided test; H-D makes no prediction) | n = 1, confounded |
| R-89 / WP-4C-1 (P7) | against H-B, **if** R-89 took force | confounded with R-89's status |
| R-94 (P5) | a candidate **falsifier of H-D** | open; alternative reading: a scope question per R-90 |
| Status mediation (P4) | corroborates H-D's status clause; does not discriminate | n = 2, self-reported |

## 5. Consequences if adopted
1. **R3 Empirical Theory Record:** this result enters section D (non-identifiability results) together with the §0 waiver, the §1 scope qualifier and the §3 gap. H-D and H-B appear as unresolved alternatives, never as the theory.
2. **Candidates for R4 formalization (not axioms):**
   - **D1** enters as a **necessary, not sufficient,** condition for discrimination: an observable of norm state, independent of the norm-maker, the executor and the self-asserting ruling. Discrimination additionally requires a mapping to the target at t, and a state that one hypothesis predicts and the other excludes (INFOGAIN-PREREG Q2/Q4; the symmetry rule).
   - D1 was **derived on this corpus by a same-family analysis**; it is not a validated formal condition.
   - The A′ act/norm separation is carried forward **conditional on R2** reproducing the coding.
   - No formal model may build in H-D or H-B as an axiom.
3. **R2 (Gate 1 independent coding) proceeds unchanged.** R2 cannot supply the missing independent observable. It can only test the coding on which D1 rests.
4. **No further evidence hunting** on this question, unless a §6 condition is met.

## 6. Reopening conditions (each needs a human act)
1. **The human revokes the skip** and authorizes the sealed Protocol C (authenticated). If its information-gain review returns Q4 = YES, that leads to **Case B: a pre-registered discrimination experiment drafted for human authorization.** It is not an automatic reopening.
2. **A new corpus or channel is authorized** that contains an independent norm-state record **mapped to a register target at a stated t.**
3. **R2 or later review finds a defect in the A′ coding** on which D1 rests (for example, act/norm codings that do not reproduce).
