# ARB Transition Authorization — Single-Session Decision Pack

**Date prepared:** 2026-08-01 · **Prepared by:** Senior Principal DDD Architect · **For:** one ARB session, **four separate decisions, four separate votes**
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 5 ahead of origin.
**Status:** 📋 **PREPARED (v2, AMENDED) — NO OUTCOME RECORDED.** Every outcome field in §3 is deliberately blank. **Evidence is prepared by architects; authority is exercised by the ARB.**

> ### ✍️ AMENDED after dependency verification — **the pack did NOT pass unchanged**
>
> The **Decision Dependency Verification Commission** (`2026-08-01-arb-decision-dependency-verification.md`) was run *after* this pack, as a genuine test of it rather than a confirmation. **It found three defects, all now applied:**
>
> - **DD-1 · Item 4 conflated TWO state transitions in one vote** — the exact thing the batching rule forbids, in the pack that asserts the rule. **Split into 4a (plan approval) and 4b (execution authorization) → FIVE votes.** *The split gives the ARB latitude it did not have: approve the plan while holding authorization.*
> - **DD-2 · No agenda branch existed for A-1 being REJECTED** — rejection **spawns a decision that was not on the agenda**. Now stated. **Option (c) is explicitly NOT pre-authorized.**
> - **DD-3 · Authorization was not slice-granular** while the gates are — **4b is now scoped to slice 7A.**
> - **Correction:** this pack paired A-2 with A-1 as joint blockers. **A-2 is unrelated to the plan and to 7A; it gates 7B.**

---

> ## TWO FINDINGS THE PREPARATION PRODUCED — both narrow the ARB's burden, and I did not assume either
>
> **F-A · An item I have carried unresolved all session belongs on this agenda: the WP-7 plan has never received EP-01 approval.** Its status line has read *"awaiting EP-01 approval"* since it was written. The rule is explicit — *"**APPROVAL APPLIES TO THE PLAN**, not merely to the task request."* **Authorizing WP-7 without approving its plan would satisfy the roadmap and violate EP-01.** Item 4 is therefore *plan approval **and** execution authorization*, not authorization alone.
>
> **F-B · A-1 may need a NARROWER vote than I previously requested — by the ARB's own newly-adopted model.** I asked for ratification of *"the mechanism substitution."* But under the four-level model, **Mechanism is engineering's level.** What genuinely requires ARB authority is only the **governance interpretation**: *was AP-2's binding content the **invariant** ("one canonical home") or the **sentence** ("consume Adjudication's port")?* **Once that is ruled, selecting the consumer-side port is a level-3 choice belonging to engineering** — and asking the ARB to vote it would be the mirror-image over-reach: the ARB absorbing engineering's authority.
>
> **The ARB owns its own agenda.** I record the finding and recommend the narrower framing; **I do not remove a vote I previously requested.**

---

## 1. Decision Readiness Verification (Deliverable 1)

**Assessed per decision. Not pre-filled — one item is NOT ready, and one requires a scope choice.**

| # | Decision | Evidence complete? | Ready for ARB? | Finding |
|---|---|---|---|---|
| **1** | **WP-6 Acceptance** | ✅ **YES** — verified category by category | ✅ **YES** | **One scope caveat, not a completeness defect:** *"Deptrac 0 / Architecture 146 green"* does **not** cover the AP-1/AP-2 defect class (*a business value was invented*) — **no automated gate does.** Both defects were nonetheless **found and fixed before acceptance**. The ARB should read the verification evidence with that boundary in mind |
| **2** | **A-1 Ratification** | ✅ **YES for the interpretation** · ⚠️ **PARTIAL for the realization** | ⚠️ **YES, with a scope choice** | **See F-B.** The *interpretive* evidence (invariant vs mechanism) is documentary and complete. The *realization* claim **"Deptrac passes unmodified" is an analytical prediction, not an executed result** — no code exists yet. It is structurally sound (no cross-context import ⇒ no rule to violate), **but it has not been empirically demonstrated, and I will not present a prediction as a verification** |
| **3** | **A-2 Ratification** | ✅ **YES** | ✅ **YES** | **Correction of my own earlier imprecision:** I summarised A-1 and A-2 together as blocking RED. **They are not equivalent — A-1 blocks 7A; A-2 blocks 7B.** 7A creates the port (A-1's subject); the answer/act boundary (A-2's subject) is not exercised until the guard is assembled. **A-2 is ratifiable now but is not a 7A precondition** |
| **4** | **WP-7 Plan Approval (EP-01) + Execution Authorization** | ✅ **YES** — the plan is current, including the A-1 supersession note | ⚠️ **CONDITIONALLY** | **See F-A.** Two components, and **conditional on items 1 and 2**. The plan text put to EP-01 must be the **current** version — it was amended today, and approving a superseded text would be worse than not approving |

**Nothing here is assumed. One item (2) is partial, one (4) is conditional, and one previous claim of mine (3) is corrected.**

## 2. ARB Session Agenda (Deliverable 2)

**Four items · four votes · four records. Batching is permitted; conflation is not.**

| Item | Decision | Authority | Evidence | Separate vote |
|---|---|---|---|---|
| **1** | **Accept WP-6 within its approved scope** | ARB (Programme Governance) | WP-6 closure package — 3/3 scope · keystones 10 tests / 22 assertions · contexts 91 / 260 · PHPStan max clean (4 root fixes, **none suppressed**) · Deptrac 0 · Architecture 146 green · APR · ADPR · AGIR · authority verification · dev guide + operational record | ✅ |
| **2** | **A-1 — was AP-2's binding content the INVARIANT or the SENTENCE?** *(recommended framing per F-B; the ARB may instead vote the wider "approve option (d)")* | ARB (Governance Interpretation) | alignment commission §2 — invariant/mechanism split; four-level model; TP-1 text in `deptrac.yaml` | ✅ |
| **3** | **A-2 — ratify the boundary: Election ANSWERS, Audit/Retention ACTS** | ARB (Architectural Clarification) | transition record's frozen allocation, *"Consumption (acting on the answer)"*; ownership commission places the **question** in Election | ✅ |
| **4a** | **Approve the WP-7 plan (EP-01)** | Decision Authority | `.claude/plans/WP-7-retention-alignment.md` (**current text**, amended today) · eight closed commissions | ✅ |
| **4b** | **Authorize execution of SLICE 7A** *(not WP-7 wholesale — DD-3)* | ARB (Programme Governance) | 4a approved · WP-6 accepted · zero architectural gates | ✅ |

**Optional, non-blocking, may be deferred without effect on RED:** adoption of the **Layer Verification Rule** methodology module *(PROPOSED; WP-7 does not depend on it)*.

**Dependencies — verified, not assumed (DAG, single sink, no cycles):**
- **Items 1 and 2 are mutually independent** — neither is evidence for the other.
- **4a depends on 2** *(content: the plan already contains A-1's substitution)*. **4b depends on 1 ∧ 4a.**
- **Item 3 is UNRELATED to 4a and 4b** — it gates **slice 7B**, and may be taken at any point or deferred with no effect on 7A.

> ### ⚠️ Item 2 — the REJECTION branch (DD-2), so the ARB rules knowingly
>
> **Rejecting A-1** = ruling that *the sentence* (*"consume Adjudication's port"*) was binding = **a direct cross-context import, which TP-1 forbids and Deptrac would correctly fail.** The programme would then need **either option (c) — relaxing the approved Deptrac model — or a plan revision. Neither is on this agenda.**
>
> **Option (c) is NOT pre-authorized by this pack.** Relaxing a correct gate must be its own deliberate, recorded act. **If item 2 is rejected, items 4a and 4b cannot proceed and a new decision must be scheduled.**

## 3. Recording Template (Deliverable 3) — **TO BE COMPLETED IN SESSION**

```markdown
### ARB Transition Authorization — [DATE]

**Present:** [ ]   **Chair:** [ ]

| # | Decision | Outcome | Rationale | Evidence relied on | Authority |
|---|----------|---------|-----------|--------------------|-----------|
| 1 | WP-6 Acceptance (within approved scope) | ☐ Approved ☐ Rejected ☐ Deferred | | | ARB |
| 2 | A-1 — AP-2's binding content (invariant vs sentence) | ☐ Approved ☐ Rejected ☐ Deferred | | | ARB |
| 3 | A-2 — Election answers / Audit acts (gates 7B only) | ☐ Approved ☐ Rejected ☐ Deferred | | | ARB |
| 4a | WP-7 plan approval (EP-01) — *requires 2* | ☐ Approved ☐ Rejected ☐ Deferred | | | Decision Authority |
| 4b | Authorize execution of **slice 7A** — *requires 1 ∧ 4a* | ☐ Approved ☐ Rejected ☐ Deferred | | | ARB |

**Effect on programme state:** [ ]
**Conditions or provisos attached:** [ ]
**Deferred items and the evidence each awaits:** [ ]
```

**Rationale is not optional.** Six months on, the rationale is the only part that explains *why* — the outcome alone cannot be audited. **A blank rationale makes a recorded decision unusable, whatever its outcome.**

**Where the completed record belongs:** the platform rulings register (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`), per the precedent set by R-39. **Filing it is a governance act and is not performed by this pack.**

## 4. Decision Traceability (Deliverable 4)

| # | Decision | Evidence reviewed | **Recommendation** *(architect's — not a vote)* | ARB outcome | Effect |
|---|---|---|---|---|---|
| 1 | WP-6 Acceptance | closure package, verified complete | **Accept within the approved scope.** *Readiness is established by evidence; acceptance is established by authority — only the first is done here* | *(pending)* | WP-6 closes; the predecessor-acceptance rule is satisfied |
| 2 | A-1 | alignment commission | **Rule that the INVARIANT was binding.** Then, under the four-level model, the consumer-side port is **engineering's level-3 choice** — no further ARB act needed | *(pending)* | G-1 resolves; 7A's port placement settles |
| 3 | A-2 | transition record + ownership commission | **Ratify the split.** No responsibility holder changes; it names as two what was described as one | *(pending)* | **7B** proceeds with a named boundary — **7A unaffected** |
| 4a | WP-7 plan (EP-01) | current plan text; eight commissions | **Approve, conditional on 2.** *Approval attaches to the plan text **as amended today*** | *(pending)* | plan `Awaiting EP-01` → **`Approved`** |
| 4b | Execution of **slice 7A** | 4a + WP-6 acceptance | **Authorize slice 7A only** — 7B/7C follow their own gates | *(pending)* | WP-7 → **`Slice 7A AUTHORIZED`** |

**Governance loop:** `Evidence → Recommendation → Authority Decision → Recorded Rationale → Programme State`. **Steps 1–2 are complete; step 3 is the session; steps 4–5 follow it.**

## 5. Post-Decision Status (Deliverable 5) — **projection, not a record**

| Item | If all five approved | If item 1 deferred | If item 2 deferred | **If item 2 REJECTED** |
|---|---|---|---|---|
| **WP-6** | ✅ Accepted, closed | ⏳ open; **no work in flight** | ✅ unaffected | ✅ unaffected |
| **A-1** | ✅ Ratified — G-1 resolved | unaffected | ⏳ pending | ❌ **the sentence was binding** |
| **A-2** | ✅ Ratified | unaffected | unaffected | unaffected — **independent** |
| **WP-7 plan (4a)** | ✅ Approved | ✅ **approvable — 4a does not depend on item 1** | ⛔ | ⛔ **plan has no viable mechanism; revision required** |
| **Execution (4b)** | ✅ **slice 7A authorized** | ⛔ | ⛔ | ⛔ **and a NEW decision must be scheduled** |
| **RED** | ✅ **Authorized — first activity: slice 7A** | ⛔ blocked | ⛔ blocked | ⛔ blocked |
| **C-1 automation** | 🔴 deliverable **inside 7A** — engineering's call, **recommended**-blocking | 🔴 unchanged | 🔴 unchanged |
| **R-D1** (cross-adapter MAD agreement) | 🟠 recommended inside 7A, non-blocking | unchanged | unchanged |
| **Layer Verification Rule adoption** | ⚠️ **non-blocking for RED** | unchanged | unchanged |
| **EPW anchor · CW · LSM values** | 💼 Q-2 — **non-blocking**, fail-closed covers | unchanged | unchanged |

---

## Completion criteria — self-assessed

| Criterion | Met? |
|---|---|
| Every decision verified against its evidence | ✅ per-category, per-decision |
| **No decision pre-filled with "Yes"** | ✅ — **item 2 is PARTIAL, 4a/4b are CONDITIONAL**, and item 3 corrected an earlier claim of mine |
| Every vote has a separate recorded rationale field | ✅ **five** rows, rationale mandatory |
| **Evidence sufficiency assessed, not assumed** | ✅ — the WP-6 gate-coverage caveat and A-1's *prediction-not-verification* limit are both stated |
| Governance loop closed | ✅ through recommendation; **the decision step is the ARB's** |
| **No decisions merged** | ✅ **five items, five votes** — **DD-1 found one merge in v1 and it is split** |
| **Every decision changes exactly ONE programme state** | ✅ **after the 4a/4b split** — v1 failed this |
| **Dependencies verified, not assumed** | ✅ DAG, single sink, no cycles; **one pack dependency (A-2) corrected** |
| **Every branch reaches a coherent state** | ✅ including **rejection** of item 2, which v1 did not model |

**No architecture redesigned · no governance created · no outcome recorded · no vote taken.**

---

**Traceability:** ARB transition authorization commission (gates, authority matrix, evidence verification) · WP-6 closure package · architecture–enforcement alignment commission (A-1, A-2, four-level model) · implementation guard commission (C-1, gate-coverage limits) · `.claude/CLAUDE.md` §EP-01 (*"APPROVAL APPLIES TO THE PLAN"*) · `.claude/plans/WP-7-retention-alignment.md` (current text, *"awaiting EP-01 approval"*) · **R-34** (authority only by explicit issuance) · **R-39** (rulings-register precedent).
