# 65/69 Authorization-State Report — RED reconciled, grant verified

**Type:** Governance reconciliation (Session 2) · **Date:** 2026-08-14 · **Commission:** PO — reconcile RED evidence and implementation authority; authorization check only
**⛔ Nothing implemented · no production change · grant not modified · no decision reopened · PKS observation not promoted.**

---

## 1 · Authorization state — the commission's central question

> ## **Option A IS FORMALLY GRANTED.**
> **The performative ruling:** PO acceptance recorded verbatim in commit **`f6bb5504`** (PO-authored, 2026-08-14 09:50), disposition package **§2e**: *"So Accept Option A … prepare the final bounded implementation authorization. Do not begin implementation. Session 3 must first perform the six-question boundary reconciliation and present the proposed implementation boundary for review."* — with the PO's own disambiguation on record that **"Accept Option A" is an explicit authorization** (unlike the earlier agreement-with-reasoning). Grant: **§5a — Option A · v5 · ☑ GRANTED**, one mandatory gate.
> **The gate is SATISFIED, in the required order:** boundary proposal presented (`3499ea38`) → **PO boundary review with five recorded corrections** → proposal **v2** (corrections noted, never silently swapped) → **RED only after** (`32215fea`, *"under the approved boundary"*). Authorization was at no point inferred from the boundary, the RED tests, the template, Session 3's wording, or the proven defect — it rests solely on §2e.

## 2 · RED evidence — consistent with every accepted ruling

| Evidence | Verified against | Verdict |
|---|---|---|
| **TC1 🟢 / TC2 🟢** — mismatching credential denied · absent credential denied | Q-TEN-1 (credential comparand) · Q-TEN-2 (no credential → deny) | ✅ consistent — the credential layer already enforces both rulings; pins protect them |
| **TE1 🟢 / TE2 🔴 / TE4 🔴** — entitled voter TRUE under matching ambient; ambient change flips the answer (`65`, both sites) | Decision A as clarified + ruled: entitlement is a fact of (voter, election); ambient must not influence it | ✅ consistent — RED reproduces exactly the ruled defect |
| **TE3 🔴** — B→A→B→A must yield four identical TRUE answers; steps 1–2 reproduce the P6 FALSE-then-replayed-FALSE (`65`+`69`) | the full invariant incl. never-reuse | ✅ **the central acceptance invariant, correctly encoded** |
| **TE5 🟢** — non-member stays FALSE under every ambient | overshoot guard (repair must not widen eligibility) | ✅ consistent |
| Production code | grant boundary | ✅ unchanged (`32215fea` adds one test file only) |

**The tests express the desired ruled architecture, not today's implementation** — the two-decision model (CC = credential correspondence; ENT = entitlement invariance) is carried in the test file's own header and never blurred.

**Two reserved review items — both now discharged, neither silently:**
- **Predicate session-independence (was §2c.2 derived reading):** ✅ **CONFIRMED by the PO's own boundary review** — v2's correction 2 models post-repair behaviour as *ambient-invariance* (v1's "wrong ambient FALSE then corrected" framing withdrawn as preserving the dependency being eliminated). TE2/TE3 encode it; the PA endorses TE3 as "exactly the invariant we want."
- **`=== 1` platform branch:** boundary v2 **withdrew its own** "statically unreachable" overclaim, restored Session 1's NOT ESTABLISHED verbatim, and recorded the branch as a **targeted-verification follow-up OUTSIDE this grant**. Correctly handled; still open; not investigated under this grant.

## 3 · Test-scope observation recorded (commission item 6)

> **TC1 is a credential-correspondence pin.** It proves that a mismatching credential is rejected by the credential layer (`VerifyVoterSlugConsistency` fires first; `voterB` is not admitted to Election A). **It must not be interpreted as a voting-entitlement test.** The two concepts stay separate: mismatch-deny is CC's duty; ambient-invariance is ENT's property.

*(To be reflected in the test file's documentation by Session 3 — one docblock line; a documentation clarification inside the granted scope, not a scope change.)*

## 4 · What Session 3 is authorized to do now — exactly

Under §5a + the approved boundary v2, and nothing more:

1. **Minimal implementation design → GREEN** inside the approved boundary: **(i)** the entitlement predicate's internals (`User::isVoterInElection` — election-derived organisation requirement, ambient scope not consulted; signature and callers unchanged), **(ii)** the entry-surface projection lookup (`ElectionVotingController.php:37-44`, same semantics), **(iii)** the predicate's cache identity (per the boundary's traced decision-identity analysis).
2. **Regression against the frozen baseline** (`SD-1` = 1,376).
3. **Hand to Session 1 for independent verification — Session 3 does not self-certify.**

**Outside the grant, unchanged:** `voter_count` · `has_voters` · `BelongsToTenant` + its 39 other consumers · the credential layer (reused, untouched) · `EM-VOT-003` implementation · `EM-OPEN-021` · the `=== 1` branch (follow-up item) · the 375 bypass sites · any broader refactoring.

## 5 · Closed decisions — checked for contradiction, none found, all stay closed

RED contradicts **no** accepted ruling — it *reproduces* what the rulings predict. **Not reopened:** Decision A · Decision B · Q-TEN-1 · Q-TEN-2 · the family audit · `EM-OPEN-021` (untouched, still the open Track-B business decision). **PKS cache observation:** remains an observation; the boundary's cache-identity work is design under the invariant, not a promotion of the observation.

---

**Programme position:** business rule ✅ · ownership ✅ · repair scope ✅ · family audit ✅ · grant ✅ (`f6bb5504`) · boundary ✅ (PO-reviewed v2) · RED ✅ (`32215fea`) · production changes ❌ none yet → **next: Session 3 minimal design → GREEN; then Session 1 verifies. Session 2's next trigger: the GREEN commit or a stop condition.**

**Traceability:** §2e/§5a (`f6bb5504`) · boundary proposal v2 (`3499ea38`) · RED (`32215fea`, `tests/Feature/Election/VotingEntitlementAmbientInvarianceTest.php`) · disposition package §§2a–2d · P6 (`fc86049f`) · family audit (`9cf441fc`+`67ace629`).
