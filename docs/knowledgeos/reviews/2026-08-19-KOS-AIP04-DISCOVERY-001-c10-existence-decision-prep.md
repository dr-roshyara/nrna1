# C-10 Knowledge Distribution — Decision 2 (capability existence) · **DECISION-PREP, NOT THE DECISION**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Prepared by:** Governance (`b64828fe`), on the PO/ARB act 2026-08-19
**Evidence base:** the canonical analysis only — §5.1–5.4 and A1.3. ⛔ **No second C-10 model created.**

> ## ⚠️ **The act is headed "PO/ARB DECISION" and instructs "Record exactly one" — but it names no option.**
> **Governance does not record a verdict the act does not contain.** Below is the evidence, the reasoning, and a recommendation. **One line from the PO/ARB naming YES / NO / CONTESTED / NOT YET ESTABLISHED lands it.**

---

## 1 · The five constituent activities, against the estate

| Existence-test activity | Canonical state | Owner |
|---|---|---|
| determine / consume **applicability** | `ContextSelected` — 🔴 **does not occur** | 🔴 **nobody** |
| **deliver** the applicable package | 🟡 **occurs incidentally** — `CAP-01` bootstrap; **leaking** via BC-7 `tokenRef` | **BC-6 / BC-7 — not C-10** |
| obtain / record **acknowledgement** | `ContextAcknowledged` — 🔴 **does not occur** | 🔴 **nobody** |
| maintain the **D1 claim-scoped receipt** | 🔴 **does not exist** | 🔴 **nobody** |
| support **invalidation / reconciliation** | 🔴 **does not occur** | 🔴 **nobody** |

**Of five activities: one occurs and is owned elsewhere; four do not occur at all.** Evidence of C-10 having occurred: **none** (§5.3 pt 8). `EKS-01` is evidence of its **absence** — *"Recording a rule is not sufficient"*; a lane was *"taught the old path by its workflow record's `tokenRef`."*

**The named fact, handled as instructed:** the absent completed `ContextSelected` with an established owner is recorded as **realization and ownership evidence**, ⛔ **not** as an existence verdict.

## 2 · The ten DDD questions, answered from the canonical record

| # | | Answer | Class |
|---|---|---|---|
| 1 | outcome | **rule-application failures stop being invisible**; the executing session holds the *applicable, current* governed knowledge | `OBSERVED` frame / `PROPOSED` |
| 2 | decision/state owned | an **applicability determination** + a **receipt**. 🔴 **Neither exists today** | `OBSERVED` |
| 3 | invariants | **`I-K1`** — *"before a governed act begins, the session must possess a valid receipt for the applicable context version required by the act."* ⚠️ three load-bearing terms undefined: *valid · applicable · required by the act* | `PROPOSED` |
| 4 | authoritative claim | **fixed by D1**; A1.3's `OQ-J` shows a receipt can carry **2 of 5** claims — delivery authority, possession authority. It **cannot** carry applicability, execution, or compliance authority | `INFERRED` |
| 5 | evidence of occurrence | 🔴 **none** | `OBSERVED` |
| 6 | **applicability owned / supplied / unresolved** | **UNRESOLVED** — `ContextSelected` is unowned; it may be C-10's or supplied externally | `OPEN` |
| 7 | own reason to change | ✅ **YES — the distribution mechanism** (hooks, payload shape, discovery), **distinct from BC-1's knowledge-lifecycle clock** | `OBSERVED` |
| 8 | already belongs elsewhere | creation/governance/publication → **BC-1** (`CAP-03` not built) · retrieval/assembly/bootstrap → **BC-6** (`CAP-01` live) · delivery → **BC-7 by leakage** | `OBSERVED` |
| 9 | meaningful if applicability is external | ✅ **YES, but narrower** — it becomes custodian of **possession-at-START** evidence (`OQ-J` claims 1–2), which is `I-K1`'s actual scope | `INFERRED` |
| 10 | maturity | ⭐ **EVIDENCED BUT NOT REALIZED** — the *need* is evidenced (`EKS-01`, the `tokenRef` leak) and the *semantics* are **decided** (D1); **none of C-10's own activities occur.** The stages that do occur belong to other contexts | `OBSERVED` |

**Exclusions honoured (they narrow the case):** missing STARTs, off-record execution and the Amendment-1 provenance gap are **BC-7 lifecycle**, not distribution — *excluded from C-10's evidence base*. The absent-artifact recurrence bears on C-10 but is **not claimed by it**.

## 3 · Recommendation — **YES**, and the single argument that carries it

> ### **Recommended: `YES` — C-10 exists as a capability, with realization NONE and its outer boundary OPEN.**

**The load-bearing argument is D1 itself.** The PO/ARB has **already decided** that a C-10 receipt *"is authoritative only for the claim that a specified context package was delivered to and acknowledged by a specified session execution at a specified time."* **A decision fixing the authoritative claim boundary of C-10's artifact presupposes a subject for that artifact.** D1 is not reconcilable with "no coherent distinct capability."

**Supporting, and independent of realization:**
- ✅ **a distinct reason to change** (Q7) — the classic DDD existence signal, and it is *not* BC-1's clock;
- ✅ **a distinct outcome** no existing owner produces (Q1);
- ✅ **two genuinely unowned stages that share one purpose** — §5.2's *smallest coherent boundary*: **applicability + receipt**. Everything else in the path has an owner, and claiming it would take work from BC-1 and BC-6;
- ✅ **the existence/category separation is already governed** (A1.1, verified) — so **implementation incompleteness must not decide existence**, exactly as the act requires.

⭐ **Why `YES` is robust against the unresolved Q6:** under **both** candidate scopes a non-empty coherent capability remains — *applicability + receipt* if applicability is C-10's, or *receipt custodian for possession-at-START* if applicability is external (Q9). **Existence survives either resolution**, which is what makes it decidable now while Q6 stays open.

### 3.1 · The honest counter-case, stated at full strength

**For `NOT YET ESTABLISHED`:** zero occurrences of its own activities and **no evidence of occurrence at all** (pt 8); its *defining* half is unowned and may belong elsewhere (Q6); the BC-1/BC-6 pull is *"genuinely two-sided"* (pt 24). **A reasonable PO/ARB could hold that a capability whose every activity has never once occurred is a plausible hypothesis, not an established capability.**

**Against `CONTESTED`:** the competing readings (applicability inside vs outside C-10) are a **scope** question, not two competing answers on existence — **both yield existence**. Contested does not discriminate here, and the distinction the option would require (existence vs category) **already exists** (A1.1).

**Against `NO`:** it contradicts D1 and ignores a distinct reason to change.

## 4 · Evidence boundary

**Sufficient to decide existence:** §5.1's owner map · §5.2's smallest-boundary argument · §5.3 pts 1–2, 7, 17 · A1.3's seven states and `OQ-J` · D1 · `EKS-01`.
**NOT sufficient, and not needed for this decision:** who owns applicability (Q6/`OQ-B`) · halt-or-warn (pt 12) · whether the receipt becomes authoritative state (pt 22 — *the decision that changes the category*) · the receipt schema.
⚠️ **`CAP-03` is not built and `ContextSelected` has never occurred — so no part of this rests on observed C-10 behaviour.** It rests on the *shape* of the gap and on D1.

## 5 · Explicitly not decided here

⛔ bounded context · cross-context capability vs stewardship · ownership · **Knowledge Engineer ownership (a forbidden hypothesis)** · `OQ-K` · `OQ-B` · implementation technology · final receipt schema · authoritative state / lifecycle *(the next decision)*.
⛔ **And the verdict itself — Governance recommends; it does not decide.**

**Traceability:** PO/ARB act 2026-08-19 (Decision 2) · D1 as decided · canonical analysis §5.1–5.4, A1.1, A1.3 (`I-K1`, `OQ-J`, seven states) · `EKS-01` · `CAP-01`/`CAP-03` · BC-1 / BC-6 / BC-7
