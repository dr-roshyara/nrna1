# Project-State Synchronization — Observation Protocol

**Commission:** ARB, 2026-07-12 · **Status:** ACTIVE — observing; no Engineering Platform artifact modified by this protocol or its use.
**Hypothesis under test:** operational project state (backlog, epic progress, program status) is not explicitly synchronized after lifecycle transitions (ARB approval → In Progress; EP-02 completion → Completed), and this causes recurring operational problems distinct from ordinary documentation-currency drift (ENG-005).
**Origin:** recorded as an observation, not a finding, in `.claude/sessions/2026-07-12.md` (this session) after the EEP's own stability rule — "changes only on usage evidence... imagined improvements are rejected by default" — correctly blocked a same-day proposal to amend the EEP on DDD/CQRS reasoning alone.

## Constraints (binding for the entire observation period)

- **Do not** modify the EEP, ES-001..ES-006, EP-02, or any template.
- **Do not** create governance artifacts *derived from this hypothesis* until the observation period concludes. **Independent observations remain governed by their own lifecycle** — discovering a completely different issue during observation is recorded normally, not suppressed by this protocol *(ARB refinement, 2026-07-12: the original blanket wording was too restrictive)*.
- **Do not** propose any platform change until the observation period closes.
- Use **"project-state synchronization"** throughout, never "backlog updates" — the hypothesis is general (backlog, epic progress, program status, and anything similar tomorrow), not backlog-specific.

## Scope

The next **3–5 real implementation cycles** (e.g., PB-008, PB-009, PB-010, or equivalent — whichever tickets actually run next).

## What to observe

| Symptom | Description |
|---|---|
| Backlog-state divergence | The backlog marks a plan "Ready" while it is already in progress or completed |
| Program-status inconsistency | `PROGRAM_STATUS.md` and `BACKLOG.md` disagree on overall progress |
| Missing state transitions | A plan moves from ARB approval to implementation with no state update anywhere |
| Next-plan selection error | The next plan selected from the backlog is already underway or completed |
| Dashboard staleness | Any status/progress document fails to reflect current execution state |
| **No observable impact** *(ARB refinement — strengthens falsifiability)* | A state document went un-updated **and nothing bad happened**: planning still worked, nobody was confused, no wrong selection occurred. Record these too — if inconsistencies occur without operational consequence across the observation period, the hypothesis should FAIL, because not every inconsistency is operationally significant |

## How to record (session log only)

Each occurrence — not each cycle — gets one entry in that day's `.claude/sessions/YYYY-MM-DD.md`:

```text
## Project-State Synchronization Observation
Cycle: [PB-XXX or ticket id]
Symptom observed: [from the table above, or a new one — describe it]
Context: [what happened]
Evidence: [the specific file(s) found inconsistent]
```

No entry is required for a clean cycle — silence is itself evidence (see the decision table below).

## After the observation period — the decision

One summary report answers exactly one question: **did we observe the predicted problem?**

| Answer | Next action |
|---|---|
| Yes — recurring evidence across multiple cycles | Prepare an evidence-backed EEP amendment proposal, framed around "operational state synchronization," citing the specific logged occurrences |
| No — no evidence observed across 3–5 clean cycles | **The hypothesis is falsified.** Close the observation; no amendment proposed |
| Mixed / inconclusive | Extend the observation period, or refine what counts as a symptom, before deciding either way |

This is the same discipline as every other candidate this session (Artifact Promotion, the verification taxonomy, Commission Validation): evidence decides, not architectural elegance, and "falsified, close it" is as legitimate an outcome as "confirmed, propose the amendment."

---
*Traceability: ARB observation-protocol commission 2026-07-12, following the EEP's own change-policy refusal earlier the same day. Companion candidate: the operational-state-synchronization entry in `.claude/sessions/2026-07-12.md`. STOP — the ARB decides after the observation period; this protocol only collects.*
