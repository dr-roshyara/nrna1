# `EM-BRQ-001` — FROZEN SCENARIO SUITE (Model A)

**Type:** Qualification input (Governance) · **Date:** 2026-08-16 · **Corpus:** Manifesto sha256 `5172d3e2…` @ `affddca6` (see `…-start-and-freeze.md`)
**⛔ THIS DOCUMENT CONTAINS NO OUTCOMES, NO CLASSIFICATIONS AND NO RULE CITATIONS BEYOND THE CONFIGURATION — deliberately, so the Verification pass receives it blind. Each scenario ends with the same instruction: derive what happens using ONLY adopted rules from the frozen corpus; answer the thirteen questions; where no adopted rule answers, record `UNRESOLVED — Governance rule missing`.**

**Standing configuration unless a scenario says otherwise:** one election · acceptance participation model = **Election Committee alone (Model A)** · Committee threshold rule = `TWO_THIRDS_OF_COMMITTEE_VOTES` · Committee constituted with **3 members** at Election Appointment · service-policy periods configured and recorded · schedule published by the Chief.

---

## F-1 · Normal lifecycle
**S-01** — Every phase's conditions are satisfiable and every appointed person is available and willing. **Walk the complete lifecycle from Application to result publication. At each phase transition: what must be true, who must do what, and is any required actor or authorization undefined?**

## F-2 · Committee arithmetic (N = 3)
**S-02** — At an acceptance gate, all 3 members cast acceptance.
**S-03** — 2 accept, 1 objects (reason recorded).
**S-04** — 1 accepts, 2 object. **Then: what may anyone do next, and what happens if nobody does anything?**
**S-05** — 1 member is temporarily unavailable (not resigned); the other 2 accept.
**S-06** — 2 members become unavailable before the gate; 1 remains. **Derive everything that follows, including which clock (if any) is running at each moment and what event ends the situation.**

## F-3 · Attrition, vacancy, denominator
**S-07** — A member casts acceptance, then resigns; the organisational authority fills the seat while the gate is still open. **What is the status of the cast vote, may the replacement cast one, and what is the denominator?**
**S-18** — During the representative-freeze window of a hypothetical mixed election, a Committee seat falls vacant and is filled. **Does any adopted rule forbid the filling?** *(Committee-side question only; no Model C qualification.)*
**S-19** — A seat falls vacant and CANNOT be filled; the remaining 2 members both accept. **Does the gate pass? Answer strictly from adopted text, and state every adopted rule that bears on whether a 2-available Committee may act.**

## F-4 · Committee failure chain
**S-08** — The Committee cannot decide anything and the organisational authority does not restore it within the applicable restoration period. **Derive the chain to its end, with each clock's state at each step.**
**S-09** — Same, but the authority restores the Committee before the period ends, while a halted-phase recovery period had already been running earlier in the election. **What resumes, where, and with how much time?**

## F-5 · Chief failure (post-`EM-GOV-063`)
**S-10** — A phase halts because a required document is temporarily unavailable; the Chief performs a governed reschedule; the document arrives. **Derive the recovery, including what the old and new opportunities each record.**
**S-11** — The election halts; the Chief does nothing at all; the halted-phase recovery period expires. **Derive every consequence — for the election, for the Chief, for the Deputy, and for the record.**
**S-12** — The election halts; the Chief diligently reschedules three times; each new opportunity halts on the same unsatisfied condition; the period expires mid-fourth-attempt. **Does diligence change anything?**

## F-6 · Impossibility and the inaction probe
**S-13** — Candidacy completes with ZERO approved candidates; the Chief requests progression to voting. **Derive what blocks, what recovery is available, and how the situation ends.**
**S-14** — The external organisational authority never appoints the Committee (Model A) or the Deputy; Election Appointment cannot complete. **Derive what the election can and cannot do, and how the situation ends.**
**S-15** — An actor asserts: *"nobody has acted for a long time, therefore recovery is impossible, therefore the election should end now."* **Does ANY adopted rule give that assertion effect?**

## F-7 · Version-binding under recovery
**S-16** — Mid-election, the Chief corrects a published phase window (a governed correction). **What happens to the old opportunity, its records, and any decision made under it?**
**S-17** — The election halts at Administration; Candidacy's scheduled window comes and goes while halted. **What is Candidacy's status?**

## F-8 · Configuration
**S-20** — At Application, the applicant selects Model A and the two-thirds Committee threshold. **From which adopted source does the set of permitted choices come, and what exactly is recorded?**

---
**20 scenarios. Suite frozen at this document's commit. The thirteen questions (per scenario):** state · condition · responsible actor · permitted action · consequence of acting · consequence of not acting · deadline · expiry consequence · recoverability · configuration mutability · authority · record · next state.
