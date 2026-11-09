# Reference Geography: strategic-to-tactical coherence assessment

| | |
|---|---|
| **Status** | PROPOSED. Not ratified. Not committed |
| **Assesses** | Strategic baseline §C (`2026-11-09-reference-geography-strategic-baseline.md`) against tactical baseline v3.8 (`2026-11-09-reference-geography-tactical-design-baseline-v3.8.md`) |
| **Supersedes** | The v3.7 assessment (kept as evidence; its §3 line "Membership owns belonging" was wrong) |
| **Binding** | D-3, D-31, D-73 / CR-01, D-75, D-79, D-80, D-81 to D-89, D-84a |
| **Labels** | [AD] accepted · [BF] fact · [AC] architectural conclusion · [TD] tactical · [TU] technical assumption · [OQ] Principal decision |

## 1. Disagreements and precisions (first)

1. **The ownership error was in my v3.7 assessment, not in the strategic baseline.** The strategic baseline already assigned belonging to Organisation (§2, citing D-3 "in every capacity"). **No strategic correction was needed;** the assessment line is withdrawn and v3.8 §10 now states the owner explicitly.
2. **D-88 can require three people, and that follows from its text, not from a new rule** [AC]. D-88 says the second person is "other than the steward who prepared and assessed" and "the preparer can never approve their own structural change; two persons suffice". "Two persons suffice" covers the usual case where one steward prepares and assesses. **When a conflicted person prepares and another steward assesses, both are excluded, so the approver is a third person.**
3. **A matter reviewer never sets the D-89 flag** [AC]. D-84a: the reviewer "has authority for that matter only, holds no standing publication authority". That is not "the appointment of a second steward" (D-84). This is a reading of the text, not new policy; it is flagged here so you can see it.

## 2. Corrections made

| # | Correction | Where |
|---|---|---|
| 1 | **Belonging owned by Organisation** (D-3, D-22); Membership owns membership facts; Election derives the electorate at one τ | v3.8 §10; strategic §C.2 |
| 2 | **D-84 rule by era:** era A (no second steward ever) the sole steward publishes everything; era B (flag set, permanently) structural changes need a second person, **even with one steward left**; non-structural changes always publishable by the assessing steward | v3.8 §8 role table and era table |
| 3 | **Permitted combinations of people** (five cases); the approver is checked against **every** person who prepared or assessed | v3.8 §8 |
| 4 | **Temporal proof no longer depends on a short gap** between the clock reading and the commit; five conditions stated; seven protocol tests | v3.8 §6 |
| 5 | **D-89 and D-3 in every binding list** | v3.8 header; strategic header and §C.6 |
| 6 | **Strategic baseline consolidated** in business language (§C: purpose, context responsibilities, context map, language, lifecycle, invariants, deferred items); "official place" widened to "official geographical reference" because regions and countries-or-areas are not territorial divisions | Strategic §C |

## 3. Accepted decisions → tactical representation

| Decision | Business rule | Tactical mechanism (v3.8) | Status |
|---|---|---|---|
| D-31 | Geography is platform-level; only it publishes | Single write path; consumers read published entries only | **Conditional [TU]:** single write path enforced structurally |
| D-73 / CR-01 | Complete or refused answers at one shared τ | `closedThroughTime`, close waits for writers, refuse-not-bump, snapshot reads | **Proved by transaction ordering, given** lock to commit, one database clock |
| D-79, D-80, D-82 | No belonging, coverage or areas in Geography | No such concepts in the model | **Holds** (absence by design) |
| D-81 | Dated country profiles; positions strictly increase | Profile claims; G-6 derived | Holds, given dependency reads [TU] |
| D-83 | Streets are address components | Address-component tiers hold no identities | Holds |
| D-84, D-84a, D-88, D-89 | Governed publication; conflicts; second person; latching | Roster under the publication lock with versions; flag never unset; approver ∉ {preparers, assessors}; classification computed from claim kinds | Holds for the mechanism; **whether a conflict exists is human judgement** |
| D-85 | Five M49 regions | GROUPING claims (non-structural by analogy) | Holds |
| D-86 | Neutral entries; ISO admitted by default; others by Principal decision | ADMISSION claims (structural by analogy); no sovereignty claim kind exists | Holds |
| D-87 | CLDR display names; sources attributed | Display-name history by CLDR release; attributed names as claims | Holds |

## 4. Proven versus conditional

| Proven in the model | Conditional on |
|---|---|
| Atomic publication and reconciliation; reversal keeps history; history explainable by position and roster version; no answer at τ before the close; stable answers; refusal under clock regression; D-89 cannot be evaded by splitting or relabelling | **Database:** lock held to commit or rollback; `FOR UPDATE` under `READ COMMITTED`; one database clock; host time slewed · **Platform:** each D-73 participant meets the knowledge-time contract; **Membership's §38.2 barrier still moves timestamps** (external dependency for the Membership tactical work, not changed here) · **Architecture:** dependency-only validation reads; single write path |

**Human judgement remains for:** authenticity and competence of evidence; identity in a reconciliation; existence of a material conflict; appointments.

## 5. Independent challenge (the eight questions)

| Question | Answer |
|---|---|
| 1 Is Geography sufficiently bounded? | **Yes.** It owns references, evidence, publication and history only |
| 2 Does another context own a fact Geography publishes? | **No.** Region and country-or-area facts come from external instruments; Geography owns their *publication*, not their truth |
| 3 Does Geography decide an Organisation or Election outcome? | **No.** Resolution returns places only; suggestions and belonging are Organisation's |
| 4 Is every accepted decision represented consistently? | **Yes, after the corrections in §2** |
| 5 Is the language consistent? | **Yes, after "official geographical reference"**; v3.8 uses ReferenceUnitId throughout |
| 6 Is the context map defensible? | **Yes:** one publisher, read-only consumers, one τ per operation |
| 7 Does the tactical model add business policy? | **Two readings, flagged in §1.2 and §1.3**, both derived from accepted text; ADMISSION and GROUPING are classifications by analogy |
| 8 Are decisions hidden in technical recommendations? | **One, already visible:** address ownership (recommendation only) |

## 6. Principal decisions

**No new business decision is required.** Deferred, not blocking: Q-2, L-4c, Japanese classification, names below level 2, level-1 region names, address ownership.

## 7. Readiness

**The strategic baseline (§C) and tactical baseline v3.8 are ready for independent Principal/ARB ratification review.** Not ratified. **Not implementation-ready** until the implementation architecture shows how the conditions in §4 are met.
