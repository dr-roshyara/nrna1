# SD-4A — Business-capability boundary audit: is the 1,376-test universe aligned with the Election business boundary?

**Commission:** Principal Architect · Election Verification Slice 1 · **SD-4A, investigation only**
**Date:** 2026-08-08 · **Checkpoint:** `f75eac65` · **Status:** **no scope amended, no test added, no production/test/fixture/config change**

> **The question is not "where are the tests?" It is "what business capability are we claiming to verify, and does the verification population cover it?"**

---

## 1 · Answer first

> 🔴 **No. The adopted 1,376-test universe is NOT structurally aligned with the Election business-capability boundary.**
>
> **Candidacy was not an exception. It was one visible instance of a large pattern.**

| Measure | Value | Basis |
|---|---:|---|
| All tests under `tests/` | **6,351** | `phpunit --list-tests tests` |
| Adopted `SD-1` universe | **1,376** | the recorded contract |
| **Outside the universe** | **4,975** | arithmetic — **includes non-Election tests; an upper bound, not an Election count** |
| **Out-of-universe test FILES carrying an Election capability noun in their name** | 🔴 **155** | `find` over `tests/`, excluding the five adopted paths |
| Candidacy share of those 155 | **5 files / 29 tests** | B1, `--list-tests` |

**The candidacy finding was 5 of 155 files — roughly 3% of the out-of-universe capability-named population.**

## 2 · Two search methods, and the difference is the methodological finding

**By constitutional ACTION NAME** — how many test files reference each of the 15 action strings, split by universe membership:

| Action | IN | OUT | out-of-universe file |
|---|---:|---:|---|
| `open_voting` | 20 | 1 | `ElectionStateMachineTest` |
| `close_voting` | 12 | 1 | `ElectionStateMachineTest` |
| `complete_administration` | 9 | 1 | `ElectionStateMachineTest` |
| `archive` | 4 | 1 | `ElectionStateMachineTest` |
| `apply_candidacy` | 1 | 1 | `ElectionStateMachineTest` |
| `resume` | 8 | 2 | `OverlaySemanticsTest` *(see caveat)* |
| `submit_for_approval` · `approve` · `reject` · `auto_submit` · `begin_setup` · `revise_and_resubmit` · `complete_nomination` · `publish_results` · `suspend` | 3–10 each | **0** | — |

> **By action name the leak looks trivial — essentially ONE out-of-universe file** (`tests/Feature/ElectionStateMachineTest.php`).

**By CAPABILITY NOUN** — the method that found candidacy: **155 out-of-universe files.**

**OBSERVED FACT.** **The candidacy population never references the string `apply_candidacy`.** `CandidacyApplicationTest` and its four siblings test candidacy *behaviour* without naming the constitutional action.

> **CONCLUSION — and it governs how any future boundary check must be done: searching for the action name finds tests that INVOKE the action; it does not find tests that verify the CAPABILITY. The two populations barely overlap.** Had SD-4A used only the action-name method, it would have concluded the boundary was sound and been wrong by two orders of magnitude.

⚠️ **Caveat on my own measurement:** `resume` shows `OverlaySemanticsTest` twice — my script concatenated two `grep -rl` passes without de-duplicating, so **OUT counts may double-count a file matching both quote styles.** The IN/OUT *split* is unaffected; the individual OUT numbers are **an upper bound**. Recorded rather than silently corrected — it is the fourth tooling slip today and the pattern is consistent: **my scans locate reliably and count unreliably.**

## 3 · Sample of the 155 — enough to show it is not one accident

`ElectionCapabilityArchitectureTest` · `ElectionControllerArchitectureTest` · `VoterSourceStrategyCallerEnforcementTest` · `VoterStrategyConvergenceTest` · `VoterStrategyInvariantTest` · `ElectionApprovalTest` · `ElectionAuditServiceTest` · `ElectionVotingControllerAuditTest` · `VoteControllerAuditTest` · `VoterVerificationControllerAuditTest` · `HasActiveElectionTest` · `EloquentVoterEligibilityQueryServiceTest` · `EloquentVoterRepositoryTest` · `ElectionMembershipsMigrationTest` · `CandidacyApplicationTest` · `ProcessElectionAutoTransitionsTest` · `VoteAnonymityTest` · … (155 total)

**Capability areas visibly represented outside the universe:** voter source strategy · voter eligibility · voter verification · voter repository · **election membership** · candidacy application · approval · audit · **vote anonymity** · auto-transitions · capability architecture.

**Several of these are the load-bearing ones.** `VoteAnonymityTest` and the `VoterStrategy*` trio sit outside a universe adopted to verify Election correctness. **Whether they verify business behaviour is NOT ESTABLISHED — none was read.**

## 4 · The three conditions, kept separate

| | Condition | SD-4A finding |
|---|---|---|
| **A** | Tests do not exist | **not the situation for candidacy**; unknown elsewhere |
| **B** | Tests exist but are **outside the programme universe** | ✅ **155 files, established** |
| **C** | Tests **intentionally** excluded | 🔴 **EXCLUSION INTENT NOT ESTABLISHED.** The five paths were adopted because the 2026-08-07 assessment named them. **No document states why these paths and not others** |
| **D** | Tests exist but verify implementation, not business behaviour | ✅ **demonstrated once** — B1's in-scope test checks a projection key. **Rate across the estate NOT MEASURED** |

**`SCOPE OWNERSHIP NOT ESTABLISHED`** for the original five-path choice: it entered the programme as an assessment heading, not as a recorded decision.

## 5 · What this does NOT establish

* **Not** that the 155 files verify business behaviour — **none was read.**
* **Not** that any capability is unverified — B (outside) ≠ A (absent).
* **Not** a test count for the 155. **4,975 is the total outside the universe including non-Election tests** — an upper bound, deliberately not presented as an Election figure.
* **Not** a defect. **A boundary that excludes relevant tests is a scope error, not a code error.**

## 6 · SD-4 options, now with evidence rather than intuition

| | Option | What the evidence says |
|---|---|---|
| **A** | Expand to include candidacy (5 files / 29 tests) | 🔴 **Now looks too narrow.** It fixes 3% of the out-of-universe capability-named population and would leave 150 files outside — **repeating the same defect at smaller scale, and harder to notice next time** |
| **B** | Keep 1,376 as a deliberate technical scope | **Defensible only if the exclusion is intentional — and `EXCLUSION INTENT NOT ESTABLISHED`.** If chosen, the programme may never claim *"the Election estate is verified"*, only *"these five paths are verified"* |
| **C** | **Re-derive the universe from the business-capability boundary** | The audit's own logic. **Cost is real:** a new denominator, a new baseline, and the 1,376 preserved as historical evidence. **Engineering does not choose it** |

**No option recommended.** The evidence has shifted which option is *narrow*, and that is the audit's contribution — **not a decision.**

## 7 · Open questions

* **Why these five paths?** `EXCLUSION INTENT NOT ESTABLISHED`.
* Do the 155 files verify business behaviour or implementation? **Not read.**
* How many tests do they contain? **Not measured** — and per the programme's own rule, would need `--list-tests`, not a scan.
* **CROSS-STREAM:** Session 2 is investigating `ElectionMembership` vs organisation `Member`; `ElectionMembershipsMigrationTest` and the `VoterStrategy*` files sit outside this universe. **Recorded as relevant evidence, not imported as specification.**

## 8 · Self-audit

| Check | ✓ |
|---|---|
| Business capability before filesystem | ✅ §2 — the action-name method was tested **and found inadequate** |
| Did not amend `SD-1` / add tests / change the denominator | ✅ |
| Three conditions A/B/C kept separate, plus D | ✅ §4 |
| No option recommended from intuition | ✅ §6 |
| Unmeasured quantities labelled | ✅ 4,975 as an upper bound; 155 as **files**, not tests |
| My own measurement error disclosed | ✅ §2 caveat — OUT counts are an upper bound |
| Session 2 not imported | ✅ §7 |
| No production/test/fixture/config change | ✅ |
| Scope ownership stated as unestablished | ✅ §4 C |

---

**SD-4A COMPLETE — AWAITING PRODUCT OWNER DECISION ON SD-4**

**Traceability:** `phpunit --list-tests tests` (6,351) and the 1,376 contract · `find` over `tests/` excluding the five adopted paths (155 files) · B1 report (candidacy 5 files / 29 tests) · `ElectionConstitution::RULES` (15 actions) · plan §`SD-4`
