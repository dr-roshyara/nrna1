# KOS-GOV-ATTRIBUTION-001 — Governance review of the Architecture Decision Proposal

**Date:** 2026-08-15 · **Session 2 (Governance)** · **Reviewing:** ADP `988c3593`
**Nothing adopted. `P-1`…`P-6` are NOT decided here. `A-4.3` stands unchanged. No recommendation converted into a rule.**

---

## 0 · ⚠️ `A-4.3` disclosure — read before weighing this review's independence

**I cannot demonstrate that this review is independent of the evidence it reviews, and I will not claim that it is.**

| What I can state | Evidential status |
|---|---|
| This conversation did not author the ADP | **My own assertion about my own turns. The record cannot corroborate it.** |
| The assignment's `executionContext` reads `claude-code-session:fbc084f0` | **A label Governance wrote at registration.** It describes the *assignment*, not who actually acted |
| The ADP's commit `988c3593` carries the same git identity as all 60 recent commits, unsigned | measured (§2, F-4) |
| `recordedBy` on the ADP's lane is a role token, not a process | measured (§2, F-1) |

> **Every channel that could establish whether the producing and reviewing processes differ is declaration-based — which is precisely this work item's finding.** So the honest statement is: **independence here is asserted, not attested.** Per the commission, I do not describe this review as independent merely because the role changed. **The reader should weight it accordingly, and `P-2` should be decided knowing that this very artifact could not prove its own independence.**

*(Registered instance count for the `A-4.3` class, now three: `e77fa724` §4 · `c9915445` · this review.)*

---

## 1 · Record inspection — one premise of the commission is contradicted

| Check | Finding |
|---|---|
| Assignment | `S4-architecture-attribution` · role `architecture` · grant `G-KOS-ATTR-ARCH` AUTHORIZED |
| ADP delivered | ✅ `988c3593` — exists at the stated path |
| **"the Architecture assignment is terminal"** | ❌ **CONTRADICTED — it was `ACTIVE`, not terminal.** No `COMPLETE` existed |
| Scope adherence | ✅ investigation only; `AST-015` `e19705ce` / `AST-016` `00c68cc9` unchanged; no grant or assignment created; nothing implemented |
| Self-certification | ✅ none — Session 4 correctly **did not complete itself** and created no handoff |

> **Reported rather than silently corrected.** Session 4's behaviour was **right** — `COMPLETE` is a governance act (`G-1`) and Architecture may not self-complete. The commission's premise was simply ahead of the record. **Governance has now recorded `COMPLETE` at seq 4**, releasing ownership (`mutationOwner: NULL`). This is lifecycle bookkeeping, **not adoption**.

## 2 · Verified findings — load-bearing claims re-derived independently

| ID | ADP claim | Governance verification | Verdict |
|---|---|---|---|
| **F-1** | `recordedBy` identifies only a role (`:179, 245, 261`) | **Confirmed at source, and the ADP UNDERSTATES it.** `recordedBy` occurs exactly 3× : one non-empty check, and two membership checks — on **`CONTINUATION` and `COMPLETE` only**. **Executed test:** `recordedBy: "banana"` is **ACCEPTED** on `REGISTER`, `HANDOFF` **and** `START`; refused only on `COMPLETE` | ✅ **CONFIRMED — STRONGER than claimed** |
| **F-2** | `executionContext` is self-declared and unattested (`:126, 199, 372`) | **Confirmed at source.** Exactly 3 occurrences: stored on `REGISTER`, checked **non-empty only**, re-emitted by `identity`. **Never validated, never compared, never read by any precondition** | ✅ **CONFIRMED** |
| **F-3** | Only two `executionContext` values exist, both self-declared | **Confirmed.** 6 record files; 5 carry `executionContext`; values = `shared-worktree` (12 uses / 4 records) and the C-3 label (1 use). The ADP's "all five records" is accurate read as *records carrying the field* | ✅ **CONFIRMED** |
| **F-4** | Git provides no process discrimination — one identity across 60 commits | **Confirmed and EXTENDED.** Exactly **1 author** and **1 committer** identity across 60 commits, including all four lane-representative commits. **Additionally measured, and not in the ADP: 60/60 commits are UNSIGNED (`%G?` = `N`).** So there is no attestation channel in use at all today | ✅ **CONFIRMED — EXTENDED** |
| **F-5** | The only de-facto lane discriminator is the commit-subject convention | **Substantive claim confirmed** — the discriminator is a subject-line convention, i.e. a declaration. **Counts differ** from the ADP's (mine over the last 60: `docs(governance)` 44 · `docs(architecture)` 4 · `MAINT(verification)` 3 · `(KOS-…)` 6). Different window; **the claim does not rest on the counts** | ✅ **CONFIRMED** (counts corrected) |
| **F-6** | `AST-016` already surfaces `grant holder: UNKNOWN` (`D-2`) | Confirmed in live resolver output | ✅ **CONFIRMED** |

**Consolidated:** the ADP's central finding — **attribution is declaration-based at every level simultaneously** — is **verified, and is if anything stronger than stated.**

## 3 · Disagreements and corrections

**Four items. None overturns the ADP's conclusions; two strengthen them.**

**`C-1` · `E-1` is understated — a correction that strengthens the ADP.**
`recordedBy` is **not a validated role token** on most transitions; it is an **unvalidated free string** accepted verbatim on `REGISTER`, `HANDOFF` and `START`. Only `COMPLETE`/`CONTINUATION` constrain it. **So the attribution surface is weaker than the ADP describes**, and the case that today's attribution is declaration-based is correspondingly stronger.

**`C-2` · The `A1` rejection rests on the wrong leg — but still stands.**
The ADP rejects "Governance as a registered session" partly via an *unresolvable regress* (*"the actor who registers those is Governance"*). **Measured: `REGISTER`/`HANDOFF`/`START` do not gate `recordedBy` at all**, so "only Governance registers" is a **convention, not a mechanism constraint**, and the regress is convention-level rather than structural.
> **The rejection nevertheless holds on its other leg, which IS structural and independently measured:** `START` sets `ACTIVE` **and** seizes mutation ownership, so a Governance session would take ownership from the working lane — the defect that already caused Alternative B to be rejected in `KOS-EXEC-TOPOLOGY-001`. **`A1` should be recorded as rejected on the ownership argument alone.**

**`C-3` · §4.1's "mechanically comparable" is overstated.**
The ADP argues the engineering→governance hazard is recoverable because *"grant scope vs. delivered artifact is mechanically comparable."* **It is not mechanically comparable today:** grant scope is a free string that `AST-016` itself reports as *"compared by equality"*, and grant↔session/role linkage is `UNKNOWN` (`D-2`). **Re-derivation is available to a human reader, not to a machine.** The recoverability argument survives — a later human *can* compare scope to artifact — but it should be restated as **human-re-derivable**, not mechanical. **This matters for `P-2`,** because the asymmetry argument is the main support for STRENGTHEN over REPLACE.

**`C-4` · §9 oversells `DEP-3` relative to the ADP's own §3.1.**
§9 calls per-lane git identities *"the single highest-value, lowest-cost improvement available today"*; §3.1 more carefully notes the attested identity is a *machine user, not a session role*. **In the measured setup — one OS user, one worktree, 60/60 commits unsigned — per-lane git identities are trivially settable by any lane, and signing keys would in practice be co-located.** So `DEP-3` **without enforced key isolation is a legibility and habit gain, not attestation.**
> **`DEP-3` is therefore CORRECTLY classified as a potential provenance improvement rather than proof of identity** *(the specific question put to this review)* — **§3.1 gets this right; §9's framing is stronger than §3.1 supports.** The internal tension should be resolved in favour of §3.1 before `P-4` is decided.

## 4 · The two architectural judgments the commission asked about

**The provenance ↔ authority distinction — VALID, and structurally supported.**
The ADP's type distinction (provenance = *attribute of a recorded act*; authority = *precondition of performing one*) is **not merely rhetorical**: measured, every precondition in `AST-015` reads `handoffsTo`, `humanAct`, `mutationOwner`, `grants`, `roles` or `state` — **and none reads `executionContext`.** A field demonstrably can exist in the payload without any gate consuming it. **`INV-ATTR-1` (evidential-only) codifies an invariant the mechanism currently satisfies by construction**, which is the right shape for a rule: it preserves an existing property rather than inventing one. **Consistent with `INV-DISC-2`, accepted principle 5, and `A-4` `C-1`.**

**STRENGTHEN rather than PROHIBIT — ADEQUATELY SUPPORTED, with one leg weakened by `C-3`.**

| Support | Assessment |
|---|---|
| PROHIBIT is **unenforceable today** (no attribution at any level) | ✅ **Measured** (F-1…F-5). Strongest leg; independent of any argument |
| A blanket prohibition would be a **stronger rule than `R-34` itself**, which restricts *acceptance*, not *review* | ✅ **Verified against `R-34`'s text** (`A-1.4`/`D-5`) |
| The two hazards are **asymmetric in recoverability** | ⚠️ **Sound but weakened** — recoverability is *human*, not mechanical (`C-3`). The argument holds; its strength was overstated |
| A blanket prohibition could make ordinary governance work impossible with few processes | ⚠️ **Plausible, asserted, not measured.** Reasonable on this programme's evidence; should be labelled a judgment |

> **Governance's assessment: the recommendation is adequately supported — principally by the measured unenforceability, not by the asymmetry argument.** Even if `P-2` were decided as REPLACE, it could not be *enforced* until `P-1` yields attribution. **That ordering constraint is the durable finding**, and it holds regardless of which option the PO/ARB prefers.

## 5 · Evidence sufficiency

> ## **SUFFICIENT for `P-1`…`P-6` to be decided.**

Every load-bearing factual claim was re-derived independently — at source (F-1, F-2), by census (F-3, F-4, F-5), and by executed test (F-1's `"banana"` probe). The ADP stayed within its grant, self-certified nothing, and recorded its dependencies without designing them.

**Two honest limits on this sufficiency:**
1. **This review's own independence is asserted, not attested** (§0). It is the one thing here that cannot be verified.
2. The "governance work would become impossible" claim underpinning part of `P-2` is a **judgment**, not a measurement.

## 6 · Decisions required from the PO/ARB — `P-1`…`P-6`

**Governance decides none of these and recommends on none of them.** Alternatives and consequences are preserved from the ADP; Governance's verification notes are added where they change the weight of an option.

| # | Decision | Alternatives | Consequences · Governance notes |
|---|---|---|---|
| **P-1** | **Q-1 direction — process attribution** | **A5 hybrid** *(ADP recommends)* · A2 now · A4 now · A3 only · reject all | **A1 rejected** on the **ownership** argument (`C-2`), not the regress. **A3 alone cannot answer Q-1** — Governance performs no `REGISTER`. **A2** touches **qualified `AST-015`** ⇒ separate authorization; remains self-declared. **A4** is the only path toward non-forgeable attribution, but see `C-4` |
| **P-2** | **Q-2 disposition — the `A-4.3` overlap** | REMAIN · **STRENGTHEN** *(ADP recommends)* · RESTRICT · REPLACE | **REPLACE is unenforceable until `P-1` lands** (measured). **RESTRICT** likewise needs attribution. **STRENGTHEN** and **REMAIN** are both available today. **Decide knowing §0: this review could not prove its own independence** |
| **P-3** | **Adopt `INV-ATTR-1` + `INV-ATTR-2` as rule text?** | adopt both · adopt one · neither · defer | Governance verified `INV-ATTR-1` **codifies a property the mechanism already satisfies** (§4) — low-risk, preserves rather than invents. If adopted, Governance advises amending `KOS-AI-ORCH-001` (the `A-1`…`A-4` route) rather than minting a new document (**ES-005.4**) |
| **P-4** | **Authorize `DEP-3` (per-lane git identities)?** | authorize · authorize **with key isolation** · decline · defer | **⚠️ Weigh `C-4`:** with one OS user, one worktree and 60/60 unsigned commits, per-lane identities are **trivially settable by any lane** — a **legibility gain, not attestation**, unless keys are provisioned per lane **and** not cross-accessible. **No mechanism change either way** |
| **P-5** | **May any Q-3 instrument ever be a GATE, or reporting-only?** | reporting-only *(ADP urges)* · gate permitted · defer | The ADP's honesty limit is **endorsed by Governance**: even perfect attribution verifies **process-distinctness**, a *proxy* for independence — same model, same director, same worktree. **A green check would certify something weaker than the rule intends while appearing stronger.** Precedent favours surface-don't-decide (`AST-016`) |
| **P-6** | **Priority of `DEP-1` vs the open `D-2` / `D-6`** | `DEP-1` first · `D-2` first · `D-6` first · none yet | All three touch **qualified `AST-015`**. Governance observes they are **one family** — the record cannot express *who acted* (`DEP-1`), *whose authority* (`D-2`), or *in what mode* (`D-6`). **Sequencing is the PO's; batching may be cheaper than three separate reopenings** |

## 7 · Out of scope — explicitly

**Not done, not decided, not implemented:** `P-1`…`P-6` **not decided** · `INV-ATTR-1`/`INV-ATTR-2` **not adopted** — they remain **PROPOSED** · `A-4.3` **stands unchanged** · `DEP-3` **not implemented** · no change to `AST-015`, `AST-016`, `executionContext`, git configuration, hooks, `SESSION_START` or workflow semantics · **no implementation or verification grant created** · no architectural redesign · no Architecture recommendation converted into a binding rule.

**Not resolved and not folded in:** `V-3` (`KOS-ACTIVATION-REPORTING-001`, OPEN 0/0) · `D-2` · `D-6` · `E-1` (record untracked) · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. **`KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed and were not reopened.**

**Work item `KOS-GOV-ATTRIBUTION-001`: OPEN · `mutationOwner: NULL` · `S4-architecture-attribution` COMPLETED · awaiting `P-1`…`P-6`.**

---

## Traceability

ADP `988c3593` · `S4-architecture-attribution` seq 1–4 (REGISTER · HANDOFF · human START · **COMPLETE by governance, seq 4**) · `G-KOS-ATTR-ARCH` · source verification `workflow-state.php:126,179,199,245,261,372` · executed `recordedBy` probe (scratchpad `--dir`; estate untouched) · git identity + signature census (60 commits, 1 identity, 0 signed) · `executionContext` census (6 records, 5 carrying, 2 values) · `A-4.3`/`C-3` (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · `A-1.4`/`D-5` · `R-34` · `INV-DISC-2` · accepted principle 5 · `G-1`/`G-2` · `D-2` · `D-6` · prior `A-4.3` instances `e77fa724` §4 and `c9915445`
