# Governance Decision Report — Start of Voting: Progression Authority

**Type:** Governance decision record (Session 2, Governance) · **Date:** 2026-08-15 · **Predecessor evidence:** Session 4's semantic reconciliation (read in full before this work)
**⛔ Governance only. No code · no lifecycle change · no ADR · no state-machine design · no implementation tests · no architecture decisions. Amendment text is PROPOSED for adoption, not enacted into the Constitution or Manifesto by this report — enactment is a separate one-line act.**

---

## 1 · Executive business ruling *(registered verbatim in substance)*

> **The scheduled voting time does not automatically start voting.** Reaching the scheduled time establishes only that the election **may now be considered** for progression. Voting becomes active only when **(1)** the scheduled start has been reached (or the schedule has been validly corrected), **(2)** the Chief Election Officer explicitly chooses **Proceed**, and **(3)** all mandatory business conditions for starting voting are satisfied.
>
> **Time does not exercise authority. The clock establishes eligibility for consideration; the Chief Election Officer exercises the authority to proceed; the mandatory conditions determine whether progression is valid.**
>
> **"Time makes progression possible; authority and eligibility make progression valid."**

**This is a NEW rule, adopted now — not a restoration.** Session 4 established that **no generation of this system has ever required the Chief to act for an election to become voting-active**; the voting boundary has been clock-driven since the first generation. Describing this ruling as "restoring" past behaviour would be historically false, and this report does not.

## 2 · Formal start-of-voting rule *(business language)*

1. The arrival of the scheduled voting start time **does not** move the election into voting.
2. On arrival — or after a valid schedule correction — the election becomes **eligible for the Chief Election Officer's consideration**.
3. **The Chief Election Officer must explicitly choose to proceed.** No other person, and no passage of time, performs this act.
4. Before progression is accepted, **all mandatory conditions for starting voting must be satisfied**, assessed at the moment of the act.
5. If any mandatory condition is unmet: **voting does not begin** · the election **remains in its current valid non-voting condition** · **the business reason for refusal is made visible to the authorized officer** · the officer may correct the condition or the schedule where authorized · and **may attempt to proceed again**.

## 3 · The meaning of scheduled time

**Scheduled time is a permission to consider, not an instruction to act.** Before it, the election is not yet eligible. On arrival, it becomes eligible. Arrival changes **nothing else** — not the election's phase, not what voters may do, not what has been decided.

## 4 · Chief Election Officer authority

The Chief already holds the exclusive constitutional power to open voting *("Only the chief may open voting or publish results")*. **This ruling makes that exclusivity effective**: the power may no longer be exercised by anything other than the Chief. Two consequences follow in business terms — the Chief's decision is **required**, and it is **capable of being refused** by the election's own conditions. Authority to proceed is not authority to override the conditions.

## 5 · Mandatory conditions for starting voting

| Condition | Status |
|---|---|
| At least one **approved candidate** | **already adopted and enforced** (`EM-VOT-001`, `EM-VOT-002`) |
| At least one **admitted voter** | **already adopted** (`EM-VOT-003`); **not yet enforced anywhere at this boundary** |
| **Nomination has reached its required completed/closed condition** | **part of this ruling — and NEW as a business condition at this boundary.** It exists today only as a derivation input, never as a stated requirement of opening voting. Recorded as adopted by this ruling, and flagged so no one mistakes it for pre-existing |
| No other **already-established** constitutional prohibition is violated | unchanged |

**Nothing else is constitutionalized here.** Credential availability, database or flag state, cache behaviour, lifecycle enumerations, `voting_locked`, and every other implementation matter Session 4 uncovered are **deliberately excluded**. Any of them that later proves necessary is **"REQUIRES SEPARATE BUSINESS DECISION"** — not adopted by silence.

## 6 · Candidate requirement

If the Chief attempts to start voting without the required approved candidate: **progression is refused · voting does not become active · the reason is communicated · the election remains in its current valid non-voting condition · corrective action may be taken where authorized.**

**This does NOT close `EM-OPEN-021`.** Session 4 established that the zero-candidate condition is **also reachable without the clock**, through the recorded `forceCloseNomination()` route. **The general start-of-voting rule is now established; the specific zero-candidate configuration question survives it and remains OPEN.** Ruling progression first was still correct — it reduces how many routes reach the condition, and therefore what the eventual answer must cover.

## 7 · Voter requirement

If the required admitted-voter condition is unmet when the Chief attempts to start voting: **progression is refused · voting does not become active · the reason is communicated · the election remains in a valid non-voting condition · corrective action may be taken where authorized.** This gives `EM-VOT-003` its enforcement boundary in business terms; it adds no new requirement of its own.

## 8 · The meaning of "cannot proceed"

The business rules must be able to distinguish **three genuinely different situations**, because an officer facing them must act differently:

| | Situation | What it means to the officer |
|---|---|---|
| **A** | **Not yet eligible** — the scheduled start has not been reached | Nothing to do yet; wait, or correct the schedule if it is wrong |
| **B** | **Eligible, awaiting the officer's decision** — time reached, conditions could be met, no decision made | **The election is waiting for you** |
| **C** | **Cannot proceed — required conditions not satisfied** — the officer decided to proceed and was refused | **Fix the named condition, then try again** |

**Deliberately not adopted: the historical technical name `voting_blocked`.** Session 4 recovered it as evidence of a real past capability, and it usefully proves that a "cannot proceed" concept once existed — **but a recovered implementation name is not authoritative business vocabulary.** The business meaning above is what is adopted. **How these three situations are named and represented is an Architecture matter, not settled here.**

## 9 · Schedule-correction principle

**Established:** the Chief Election Officer may correct the election schedule under explicitly governed conditions, **including a window whose time has already elapsed**, where necessary for the election to continue legitimately.

**Also established — the qualifiers, which are the substance of the rule:** a schedule correction **must not itself advance the election** · **must not bypass any mandatory progression condition** · **must be attributable to the authorized officer** · **must be auditable** · **must carry a stated reason**.

**Deliberately NOT invented here** — each is **"REQUIRES SEPARATE BUSINESS DECISION"**: moving a window forward · extending a window · correcting a window already past · moving a window backward · the effect on voting credentials already issued · whether correction is permitted at all once votes have been cast. **The principle is adopted; the boundaries are not.** *(An implementation that assumed any of these would be inventing policy.)*

## 10 · Automatic end of voting — preserved, unchanged

**The authorized voting period ends automatically at its valid end time**, after which the election proceeds under the established closing and counting rules. Any separately governed authority to close voting early **remains a distinct act and is unaffected**.

> **This ruling is about the START of voting only.** It must not be generalized into *"election progression is always manual."* Different boundaries may legitimately differ, and **no other boundary's semantics are decided here.**

## 11 · Items still requiring separate business decisions

1. **`EM-OPEN-021`** — the zero-candidate configuration reachable via `forceCloseNomination()` — **OPEN — REQUIRES PO/GOVERNANCE DECISION.**
2. **Schedule-correction boundaries** — the six questions in §9 — **OPEN.**
3. **Every other phase boundary** (entering nomination, closing voting early, counting → results): commanded or derived? **OPEN.** Session 4's Finding G-1 stands: *neither document has ever decided whether progression is commanded or derived* — this ruling settles that for **one** boundary only.
4. **`PBDIGIT-59` (which clock is constitutional) and `PBDIGIT-67` (what an entered time means)** — **OPEN, and prerequisite**: "the scheduled time has been reached" cannot be assessed until both are answered.
5. **Whether a derived read model may ever exercise a constitutional power** — this ruling answers it *for starting voting*; as a general principle it is **OPEN.**

## 12 · Constitution changes required

The Constitution defines actions, actors and preconditions — this ruling changes **two of those** at one action, and adds nothing else:

- **`open_voting`** — record that this action is **the sole means** by which an election becomes voting-active, and extend its preconditions with **at least one admitted voter** and **nomination completed** (alongside the existing window-defined, timezone-set, approved-candidate conditions).
- **A schedule-correction action** — a new named action for the Chief, carrying the §9 qualifiers (does not advance the election; does not bypass conditions; attributable; auditable; reasoned). **Its boundaries remain undecided (§11.2), so the action is proposed at principle level only.**

**No other constitutional article is touched.**

## 13 · Manifesto changes required

Business meaning belongs here, and it is one new adopted rule (candidate ID assigned at adoption, per the established process — not invented in advance):

- **The start-of-voting progression rule** — §1's principle plus §2's five clauses.
- **The three-situation distinction** of §8, as business meaning.
- **The schedule-correction principle** of §9, with its open boundaries recorded in the governance section.

**No existing Manifesto rule is rewritten.** `EM-VOT-001/002/003` stand exactly as adopted; the new rule references them rather than restating them.

## 14 · Existing rules that must remain unchanged

`EM-VOT-001`, `EM-VOT-002` (implemented and verified), `EM-VOT-003` · the chief's exclusive right to open voting and publish results · automatic end of voting · all suspension rules (`EM-GOV-002/003`) · all entitlement rules and Decision A · every rule outside the start-of-voting boundary.

## 15 · Explicitly out of scope — architecture and implementation

Not addressed, not decided, not fixed here: that the current lifecycle derives voting from the clock · that the Chief's authority is currently pre-empted by the derived state · that the Chief's action writes a fact the lifecycle does not consume · that the current action overwrites the scheduled window · the deleted "blocked" vocabulary · how the three situations of §8 are represented · how both progression routes are kept consistent · any state-machine design. **These are Architecture's to determine once this ruling is adopted.**

## 16 · Traceability

Session 4's semantic reconciliation — *the commissioning hypothesis corrected* (command-driven entry never existed) · *Finding G-1* (progression authority never decided by anyone) · *the confirmed regression* (deletion of the "blocked" vocabulary and of the setup-completion gate, without decision or ADR) · *§5* (a derived read model currently overriding a constitutional grant of authority — drift, not decision) · *§6* (`EM-OPEN-021` dependent but **not** dissolved). Plus: `ElectionConstitution` (the fifteen actions) · `ElectionLifecycleEngineImpl` (the derivation order) · `ELECTION_MANIFESTO` (`EM-VOT-001/002/003`, the rule-ownership map, `EM-OPEN-021`) · this stream's prior governance review of 2026-08-15.

## 17 · Proposed amendment text *(for adoption; not enacted here)*

> **Start of voting.** *An election does not enter its voting phase by the passage of time. When the scheduled voting start time is reached — or after the schedule has been validly corrected — the election becomes eligible for the Chief Election Officer's consideration. The Chief Election Officer must explicitly decide to proceed. Progression is valid only if, at that moment, the election has at least one approved candidate, at least one admitted voter, and has completed nomination, and no established constitutional prohibition is violated. If any of these is not satisfied, voting does not begin, the election remains in its current valid non-voting condition, and the officer is told which condition is unmet, may correct it where authorized, and may decide to proceed again. Reaching the scheduled time confers eligibility only; it confers no authority. This rule governs the start of voting; the authorized voting period continues to end automatically at its valid end time.*
>
> **Schedule correction.** *The Chief Election Officer may correct an election's schedule under governed conditions, including a window whose time has already elapsed, where this is necessary for the election to continue legitimately. A schedule correction never by itself advances the election, never bypasses a mandatory condition for progression, is attributable to the officer who made it, is recorded with its reason, and is auditable. The permitted extent of correction — including movement, extension, correction of an elapsed window, effect on issued voting credentials, and permissibility once votes have been cast — is not yet decided and requires a separate business decision.*

---

**Exit state:** the start-of-voting rule is formally expressed · time-eligibility and officer authority are explicitly separated · mandatory conditions are identified (with the new one flagged as new) · automatic closing is preserved and fenced from generalization · schedule-correction authority is captured at principle level with its boundaries left open · unresolved questions are labelled **OPEN — REQUIRES PO/GOVERNANCE DECISION** · Constitution and Manifesto amendments are identified and drafted · **no architecture or implementation work was performed.**

**Next gate:** the PO adopts (or amends) §17 → Governance records it into the Constitution/Manifesto → **then** Session 4 determines how the lifecycle must change → then implementation → then independent verification.
