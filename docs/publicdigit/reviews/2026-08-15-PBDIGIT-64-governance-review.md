# PBDIGIT-64 — Governance review: *"an election can open voting with no approved candidate"*

**2026-08-15 · Session 2 (Governance)** · Commissioned to explain the situation, identify the decision, and establish the next step. **No implementation authorized; none requested.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #4
 Responsibility : governance
 Operator       : Session 2 (Governance)                 [declared]
 Approver       : PO/ARB — review instruction 2026-08-15  [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

> # 🟠 DECISION NEEDED FROM PO/ARB
>
> ### **Recommended: do NOT commission implementation. The fix already exists and has been in the product since 13 August.**
>
> **What is actually outstanding is smaller and different:** an independent check that the fix works, a stale ticket, and a coordination risk with work happening right now.
>
> *The explanation follows. The one thing worth your attention immediately is §4.*

---

## 1 · What is the situation?

**The problem you selected is real — and it has already been fixed.**

The concern was that an election could reach its voting stage with nobody to vote for. That was true, was reproduced at runtime, and its cause was established: **the system worked out whether voting was open by looking only at the clock**, before it ever checked whether the election was ready.

**On 13 August that was corrected.** The rule *"voting requires at least one approved candidate"* is now applied on **both** routes into voting — the automatic clock-driven one and the manual one — under a business rule the organisation had already adopted.

**The ticket still says `OPEN — not authorised`.** That status was written on 8 August and was never updated. **It is two days out of date, and reading it alone would lead you to commission work that has already been done.**

## 2 · Why does it matter to the organization?

**The business risk you were concerned about is already closed:** voters can no longer be routed to a ballot with nothing on it, by either route.

**The residual risk is different, and it is about assurance rather than behaviour:**
- **Nobody has independently checked the fix.** The person who built it recorded, in their own words, *"Independent verification: PENDING."* We have the builder's word that it works, plus their tests — but not a second opinion.
- **The ticket's stale status is itself a hazard.** It is exactly the trap this review nearly walked into, and the next person to read the backlog would hit it too.

## 3 · What is affected?

Election integrity — **already protected**. What remains affected is **confidence**: we believe the protection works, but have not confirmed it the way this organisation normally confirms things.

**One further observation, honestly reported:** the tests that pin this protection **run, and their checks execute, but they report deprecation warnings rather than a clean pass.** That is a housekeeping matter, not a hole in the protection — the checks did run. It should be looked at, but it does not put voters at risk.

## 4 · ⚠️ The thing that needs your attention first

**Another team is working on Election matters right now, in the same workspace.**

Thirteen Election commits landed today, the most recent at **13:30** — active work on election timing, voting opportunities and publication rules. **It is running in parallel with the platform work, in a different session.**

> **Opening a second Election lane without coordinating would risk two lanes editing the same election rules and records at once — precisely the failure this organisation documented and built its whole session-coordination approach to prevent.**

**I did not open anything.** This is the reason I would pause before commissioning any Election work at all today, regardless of which item you pick.

**This also corrects something I told you earlier.** I said the Election queue *"has been waiting behind the platform track."* **That was wrong.** It has been running alongside it all day. I was reading a summary rather than checking, and I should have checked.

## 5 · What decision is required from you?

**Whether to do anything about PBDIGIT-64 now — and if so, whether to coordinate with the active Election lane first.**

## 6 · What are the realistic options?

| | Option | What it means |
|---|---|---|
| **A** | **Correct the ticket only** | Mark it implemented-but-unverified, so nobody re-commissions finished work. **Minutes. No engineering.** |
| **B** | **A, plus commission the independent check** | Someone who did not build it confirms the protection holds on both routes. **Small, and it is the step the builder themselves said was outstanding** |
| **C** | **Coordinate with the active lane first, then decide** | Find out what the other Election session is doing before adding anything |
| **D** | **Commission implementation** | ❌ **Not recommended — the work already exists.** Would duplicate finished work |

## 7 · What does Governance recommend, and why?

**Option A now; Option B next, and only after C.**

Correcting the ticket costs nothing and removes a trap that has already misled one reader today. The independent check is genuinely outstanding and is worth doing — **but not by opening a second lane into a live Election workspace this afternoon.** A short coordination step first is cheap; an accidental collision between two lanes editing election rules is not.

**Option D would spend engineering effort rebuilding something that shipped two days ago.**

## 8 · What happens if we do nothing?

Voters stay protected — the fix is in the product either way. But the ticket keeps saying work is needed when it is not, and the protection stays on the builder's word alone. **Neither is urgent. Both are cheap to fix.**

## 9 · What would change operationally?

**Option A:** one ticket reads accurately. Nothing else changes.
**Option B:** one short verification task, ending in a yes-or-no answer on whether the protection holds.
**Option C:** one message to the other Election session before anything starts.

---

*Technical references, for traceability only: `PBDIGIT-64` · rule `EM-VOT-002` (Election Manifesto §4a, `SD-14` = YES) · implementing commit `f2c2cc4e` (2026-08-13; 12 files, +443) — `ElectionLifecycleEngineImpl::compute()` priority 5 now requires `hasCandidatesApproved()`, and `ElectionConstitution` `open_voting` preconditions gained `has_approved_candidates` · tests `EmVot002ApprovedCandidateBeforeVotingTest` (159 lines) and `EmVot002OpenVotingPreconditionTest` (140 lines) — 8 tests / 8 assertions, reported deprecated, 0 failures · builder's note: "Independent verification by Session 1: PENDING" · fallback semantics deliberately left open (`EM-OPEN-021`) · active parallel lane: `PBDIGIT-68`, 13 commits today, latest `16f4dd3d` 13:30.*
