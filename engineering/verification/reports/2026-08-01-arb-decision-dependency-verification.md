# ARB Decision Dependency Verification Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** do the proposed ARB decisions form a **consistent governance state transition**? Dependency integrity only — no architecture, no re-review, no outcomes.
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003`, 6 ahead.

> ## ORDERING NOTE — this commission runs AFTER the pack it should have preceded
>
> **The ARB is right that dependency verification belongs before drafting.** It did not happen in that order, so **the only honest way to run it is as a test of the pack, with a real possibility of invalidating it.** A dependency check that confirms everything is ceremonial by the platform's own **Methodological Fitness Rule**.
>
> ### **RESULT: the pack does NOT pass unchanged. Three defects — DD-1 · DD-2 · DD-3.**
>
> **DD-1 · Item 4 conflates TWO state transitions in one vote** — the exact thing the batching rule forbids, in the pack that asserts the rule.
> **DD-2 · The agenda has no branch for A-1 being REJECTED** — rejection *spawns a decision that is not on the agenda*.
> **DD-3 · "WP-7 execution authorization" is not slice-granular, but the gates are** — as drafted it authorizes more than the evidence supports.
>
> **None is architectural. All three are governance-mechanics defects, and all three are fixable before the session.**

---

## 1. Decision Inventory (Phase 1)

| ID | Decision | Authority | Evidence source | Expected state transition |
|---|---|---|---|---|
| **D1** | Accept WP-6 within approved scope | ARB (Programme Governance) | WP-6 closure package | WP-6: `Delivered / Pending Acceptance` → **`Accepted / Closed`** |
| **D2** | **A-1** — was AP-2's binding content the **invariant** or the **sentence**? | ARB (Governance Interpretation) | alignment commission §2 | G-1: `Analysed` → **`Resolved`** |
| **D3** | **A-2** — Election **answers**, Audit/Retention **acts** | ARB (Architectural Clarification) | transition record; ownership commission | Guard boundary: `described as one` → **`named as two`** |
| **D4** | *(as drafted)* WP-7 **plan approval (EP-01) + execution authorization** | ARB / Decision Authority | current plan text; eight commissions | WP-7: `Planned / Awaiting Governance` → **`Authorized for RED`** |

**Immediately visible from the inventory itself:** D1, D2 and D3 each name **one** transition. **D4 names two** — that is DD-1, and the inventory format surfaced it without any further analysis. *(The value of the format, not of my insight.)*

## 2. Decision Dependency Matrix (Phase 2)

**Rule applied: co-occurrence in a session is NOT dependency.** Each pair judged on subject matter.

| | D1 | D2 | D3 | D4 |
|---|---|---|---|---|
| **D1** WP-6 acceptance | — | **independent** | **independent** | **prerequisite of D4** |
| **D2** A-1 | independent | — | **independent** | **prerequisite of D4** |
| **D3** A-2 | independent | independent | — | **unrelated** ⚠️ *(differs from the pack)* |
| **D4** WP-7 | consequence | consequence | unrelated | — |

**Pairwise, in the commissioned format:**

| Pair | Independent? | Prerequisite? | Consequence? | Mutually exclusive? | Unrelated? |
|---|---|---|---|---|---|
| **D1 → D2** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **D1 → D3** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **D1 → D4** | ❌ | ✅ **D1 is prerequisite** | ✅ D4 is consequence | ❌ | ❌ |
| **D2 → D3** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **D2 → D4** | ❌ | ✅ **D2 is prerequisite** *(content, not execution)* | ✅ | ❌ | ❌ |
| **D3 → D4** | ✅ | ❌ | ❌ | ❌ | ✅ **unrelated** ⚠️ *(the pack says otherwise)* |

**D1 ⟂ D2 — verified, not assumed:** D1 concerns **delivered Adjudication code**; D2 concerns **the reading of a WP-7 plan sentence**. Neither is evidence for the other. **Independent.**

**D2 → D4 — and the reason matters more than the fact.** It is *not* "7A needs the port." It is that **the plan text D4 approves already contains A-1's substitution.** If A-1 is rejected, the plan reverts to a mechanism that **fails Deptrac** — so the plan would be unapprovable **as written**. **A content prerequisite, not an execution one.**

**D3 ⟂ D4 — the pack was wrong here.** The pack treated A-2 as a joint blocker with A-1. **It is not:** the plan already labels A-2 *pending ratification*, so a deferral or rejection leaves the plan **truthful and approvable**. **A-2 gates slice 7B, not the plan and not 7A.**

**Circularity check:** edges are `D1 → D4` and `D2 → D4` only. **A DAG with a single sink. No cycles.**

## 3. State Transition Matrix (Phase 3)

| ID | Current state | Decision | Resulting state | Legal under the roadmap? |
|---|---|---|---|---|
| D1 | WP-6 `Pending Acceptance` | accept | WP-6 `Accepted` | ✅ — the closure package is the roadmap's required evidence |
| D2 | G-1 `Analysed` | rule on binding content | G-1 `Resolved` | ✅ — interpretation of an existing invariant creates no new governance |
| D3 | boundary `one` | ratify split | boundary `two` | ✅ — **no responsibility holder changes** |
| **D4a** | WP-7 plan `Awaiting EP-01` | approve plan | plan `Approved` | ✅ — **EP-01: approval attaches to the plan** |
| **D4b** | WP-7 `Not authorized` | authorize execution | WP-7 **slice 7A** `Authorized` | ✅ **only if D1 ∧ D4a** — the predecessor-acceptance rule |

### DD-1 — Item 4 must be split

> **"Every authority decision changes exactly one programme state."** As drafted, **D4 changes two: plan status AND execution status.**

**The split is not ceremony — it gives the ARB latitude it does not currently have:**

| Scenario | Under the drafted D4 | Under D4a / D4b |
|---|---|---|
| ARB is satisfied with the plan but wants longer on WP-6 | ❌ **must defer everything** | ✅ **approve the plan (D4a); hold authorization (D4b)** |
| ARB authorizes execution without approving the plan | ⚠️ possible, and it would **violate EP-01** | ✅ **structurally impossible** — D4b depends on D4a |

**D4a is independent of D1.** **D4b depends on D1 ∧ D4a.** *(D4a still depends on D2, per §2.)*

## 4. Evidence Sufficiency Assessment (Phase 4)

**Readiness only. No outcome determined.**

| ID | Complete evidence | Explicit recommendation | Identified authority | Expected effect | Verdict |
|---|---|---|---|---|---|
| D1 | ✅ *(scope caveat: no gate covers the AP-1/AP-2 class)* | ✅ accept within scope | ✅ ARB | ✅ | ✅ **READY** |
| D2 | ✅ for the interpretation · ⚠️ the *"Deptrac passes unmodified"* claim is a **prediction, not an executed result** | ✅ rule that the invariant was binding | ✅ ARB | ✅ | ⚠️ **READY, with the limit labelled** |
| D3 | ✅ | ✅ ratify | ✅ ARB | ✅ gates 7B | ✅ **READY** |
| D4a | ✅ current plan text | ✅ approve, conditional on D2 | ✅ | ✅ | ✅ **READY** |
| D4b | ✅ | ✅ authorize **slice 7A** | ✅ | ✅ | ⚠️ **READY once D1 ∧ D4a** |

## 5. Governance Consistency Review (Phase 5)

**Intended sequence — coherent:**

```
WP-6 Pending Acceptance ──D1──► Accepted
G-1 Analysed ────────────D2──► Resolved
plan Awaiting EP-01 ─────D4a─► Approved
WP-7 Not authorized ─────D4b─► Slice 7A Authorized ──► RED
```

**Every intermediate state was tested for coherence, including the ones nobody expects to hit:**

| Partial outcome | Resulting state | Coherent? |
|---|---|---|
| D1 ✅, D2 deferred | WP-6 closed; WP-7 planned, unauthorized | ✅ **idle, not broken** |
| D1 deferred, D2 ✅ | G-1 resolved; WP-6 still open | ✅ **no work in flight** |
| D4a ✅, D4b deferred | plan approved, execution unauthorized | ✅ **the split's whole purpose** |
| D3 deferred, all else ✅ | 7A authorized; **7B gated** | ✅ **a known future gate, not an inconsistency** |
| **D2 REJECTED** | **plan unapprovable; no viable mechanism on the agenda** | ❌ **DD-2** |
| All ✅, C-1 outstanding | RED authorized; **C-1 is a 7A deliverable, not a predecessor** | ✅ **coherent** — engineering sequences within the slice |

### DD-2 — the agenda has no branch for A-1 being **rejected**

**The pack models "deferred" but not "rejected", and they are materially different.** Rejecting A-1 means ruling that *the sentence* ("consume Adjudication's port") was binding — which is **a TP-1 violation Deptrac would fail**. The programme would then require **option (c), relaxing the approved Deptrac model, or a plan revision** — **neither is on the agenda.**

> **A decision whose rejection spawns an unlisted decision is not fully prepared.** **Recommended:** state the rejection consequence on the agenda so the ARB rules knowingly. **Do not pre-authorize option (c)** — relaxing a correct gate must be its own deliberate act.

### DD-3 — authorization must be **slice-granular**

**"Authorize WP-7" is coarser than the gates.** With A-2 deferred, 7B is gated — so a blanket WP-7 authorization **authorizes more than the evidence supports**. **Recommended wording: authorize *slice 7A*; 7B/7C follow their own gates.** *(This also matches 7A being inert: no observable behaviour changes until 7C.)*

## 6. Recommendation for drafting the ARB Decision Pack (Phase 6)

**The pack requires three amendments before the session. All are mechanical; none touches architecture.**

| # | Amendment |
|---|---|
| **1 (DD-1)** | **Split item 4 into 4a (EP-01 plan approval) and 4b (execution authorization).** Five votes, not four |
| **2 (DD-2)** | **Add the A-1 rejection branch** to the agenda, naming its consequence — **without pre-authorizing option (c)** |
| **3 (DD-3)** | **Scope 4b to slice 7A**, noting 7B/7C follow their own gates |
| — | **Correct the A-2 dependency:** the pack pairs it with A-1; **it is unrelated to the plan and to 7A, and gates 7B** |

**Once amended, the pack may be presented.** Dependencies are then explicit, acyclic, evidence-backed, and every decision changes exactly one programme state.

| Completion criterion | Met? |
|---|---|
| Every decision has a defined state transition | ✅ **after the split** |
| Every dependency explicit | ✅ — and one (D3) **corrected** |
| No circular dependencies | ✅ DAG, single sink |
| Evidence sufficient per decision | ✅ with two limits **labelled**, not hidden |
| Resulting programme state internally consistent | ✅ **after DD-2 and DD-3** |
| Pack draftable without new governance assumptions | ✅ — **and option (c) is explicitly NOT pre-authorized** |

---

**Traceability:** ARB session decision pack (the artifact under test) · ARB transition authorization commission · alignment commission (A-1, A-2, four-level model) · WP-6 closure package · implementation guard commission (C-1) · `.claude/CLAUDE.md` §EP-01 · roadmap *"no slice starts before its predecessor's acceptance"* · `deptrac.yaml` §TP-1 · **Methodological Fitness Rule** (the standard this commission was held to). **No architecture redesigned; no work package reopened; no decisions merged; no outcome pre-filled.**
