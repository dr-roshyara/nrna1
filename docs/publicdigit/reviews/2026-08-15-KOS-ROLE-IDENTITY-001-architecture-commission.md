# KOS-ROLE-IDENTITY-001 — DEP-3 bounded investigation: Architecture commission registered

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance) · **Work item:** `KOS-ROLE-IDENTITY-001` (`architecture-decision`, four canonical roles)
**Status: lane ACTIVE.** Registration only — **no implementation authorized; no mechanism changed.**

> **⏱ This lane feeds the 2026-08-30 review.** It exists because `DEP-3` was one of the three things `P-6` put in the `A5` evaluation, and — as Governance flagged — it had **no lane**, so that review would otherwise have been `DEP-3`-evidence-empty. **The review date does not move.**

---

## 1 · The commissioning act, registered verbatim

> **"I approve DEP-3 as a bounded investigation only.**
>
> *The purpose is to determine whether clearer identification of the different working responsibilities — Architecture, Implementation, Verification and Governance — would improve transparency and traceability.*
>
> *The investigation should compare reasonable alternatives and recommend the approach that provides the best balance between transparency, reliability and operational effort.*
>
> *The investigation must clearly distinguish between: knowing which working responsibility performed an activity, knowing which person initiated or operated it, knowing which person approved or verified it, and establishing genuine independent judgement.*
>
> *Different identities must not be treated as proof of independence, authority, correctness or approval.*
>
> *No implementation is authorized by this decision. Do not change the workflow mechanism, reopen AST-015 or AST-016, introduce authorization rules, or modify SESSION_START or other automatic processing.*
>
> *The result should be a recommendation with alternatives, benefits, limitations, risks and the minimum evidence that would be useful for the 30 August review.*
>
> *Governance may now register the DEP-3 investigation and its bounded assignment. After registration, the appropriate Architecture session may investigate and report its recommendation. Any implementation decision remains reserved for a later PO/ARB decision."*
> — **PO/ARB, 2026-08-15**

## 2 · What was registered

| Act | Value |
|---|---|
| **Work item** | `KOS-ROLE-IDENTITY-001` — `architecture-decision`, roles `governance · architecture · implementation · verification` |
| **Assignment** | seq **1** — `S4-architecture-role-identity`, role `architecture`, predecessor `null` |
| **Grant** | **`G-KOS-ROLEID-ARCH`** — `AUTHORIZED`, registered at assignment time (E-14) |
| **Predecessor relationship** | seq **2** — bootstrap `HANDOFF` (ownership unheld) |
| **Human START** | seq **3** — see §5 for the reading |

**Verified:** `identity` → role `architecture`, **ACTIVE**, linkage `G-KOS-ROLEID-ARCH` · `AST-016` → **RESOLVED · operable: true · ACTIVE (mutation owner)**.

**Naming note.** The work item is named for **role identity**, not for git. `DEP-3` originated as *"per-lane git identities"*, but the commission's purpose is broader — *clearer identification of the working responsibilities*. **Naming it `…-GIT-…` would have prejudged the answer** toward one mechanism; the investigation is free to conclude that git identities are the right instrument, the wrong one, or insufficient.

## 3 · Grant scope — `G-KOS-ROLEID-ARCH`

**Bounded investigation only. Deliverable: a recommendation, not an implementation plan.**

**PURPOSE.** Determine whether clearer identification of the working responsibilities — Architecture · Implementation · Verification · Governance — would improve **transparency and traceability**.

**REQUIRED.** Compare reasonable alternatives; recommend the approach with the best balance of **transparency · reliability · operational effort**.

**🔑 THE FOUR-WAY DISTINCTION — mandatory, and sharper than any prior analysis in this programme:**

| # | Question | Note |
|---|---|---|
| **1** | Which **working responsibility** performed an activity? | role provenance |
| **2** | Which **person** initiated or operated it? | **operator identity** |
| **3** | Which **person** approved or verified it? | **approver identity** |
| **4** | Is there **genuine independent judgement**? | **not establishable by identity at all** |

> **Governance observation, recorded for the investigation's benefit:** the prior ADP separated **provenance** from **authority** — a two-way split. **This commission adds a third axis the earlier analysis never separated: *person*-level identity, and within it the distinction between the operator and the approver.** Those are different people at different moments with different accountability, and today the record distinguishes **none** of the four.

> **⚖️ The line to hold — corrected per the PO/ARB refinement of 2026-08-15 (§8.1).** The four are **four distinct QUESTIONS**, and the commission requires them to be **distinguished**. But **how many INSTRUMENTS answer them is NOT prejudged** — one combined mechanism, several separate ones, or none may prove best. *(Governance's earlier phrasing here — "treat the four as genuinely independent questions, not as one question with four labels" — leaned toward separate instruments and is **withdrawn as a steer**; the distinction it drew between the four questions stands, the implied answer does not.)*

**🔒 BINDING.** **Different identities MUST NOT be treated as proof of independence, authority, correctness or approval.** Already binding via `INV-ATTR-2` (self-declared is never attested), `A-5.5` (reporting-only, never a gate) and `A-4` `C-1` (identity never authorises) — **the commission restates it rather than creating it.**

**DELIVERABLE.** Recommendation · alternatives · benefits · limitations · risks · **and the minimum evidence that would be useful for the 2026-08-30 review.**

**EXCLUDED.** Implementation of any kind · changing the workflow mechanism · reopening `AST-015`/`AST-016` · introducing authorization rules · modifying `SESSION_START` or other automatic processing · hooks · locks · deciding for the PO/ARB · self-certification. **Any implementation decision is RESERVED to a later PO/ARB decision.** Where a qualified-mechanism change would be required: **record it as an explicit dependency — do not design it.**

## 4 · Evidence the investigation inherits

**Measured, and already in the record:**

- **`recordedBy` is an unvalidated free string** on `REGISTER`/`HANDOFF`/`START` — `"banana"` is accepted; only `COMPLETE`/`CONTINUATION` constrain it to `['governance','human']`. It is a *claimed role*, not even a validated one.
- **`executionContext`** is stored on `REGISTER`, checked non-empty, re-emitted by `identity` — **never validated, never compared, never read by any precondition.**
- **Git offers no discrimination:** one author and one committer identity across 60 commits; **60/60 unsigned.**
- **The only de-facto lane discriminator is a commit-subject convention** — a declaration.
- **`AST-016` already reports** `is the grant holder: UNKNOWN — grants carry no session/role linkage (D-2)`.

⇒ **Today the record answers none of the four questions in §3.** Question 4 is the one that **cannot** be answered by identity at any strength — *(same model, same director, same worktree: two processes are one judgment computed twice)*.

**Relevant open items, not to be resolved here:** `D-2` (grant↔session linkage) · `DEP-1` (actor field) · `D-6` (read-only participation) — the `P-6` mechanism-evolution family, **all reopening qualified `AST-015`**, **separately commissioned**, **not batched**.

## 5 · ⚠️ How the human START was read — stated openly for correction

The commission did **not** use the word "START". It said:

> *"After registration, the appropriate Architecture session **may investigate and report** its recommendation."*

**Governance read this as an activation permission addressed to the session, and registered it as the human START (seq 3).** The basis is the `A-3` practice and a direct precedent in this programme — `KOS-SESSION-DISCOVERY-001` seq 7, where *"Session 3 **may prepare and present** the exact implementation boundary…"* was registered as a human START on identical reasoning. The commission's own sequencing (*"After registration…"*) is satisfied, since registration is this act.

> **If the PO/ARB intended registration WITHOUT activation, say so and Governance will record a `STOP` with the reason.** The log is append-only — the START cannot be erased, only superseded by a further recorded transition. **The reading is disclosed here rather than left implicit precisely because it cannot be silently undone.**

## 6 · Timing

**15 days to the review.** The chain from here is: investigation → report → Governance review → presentation at the 2026-08-30 review point. **`FU-1`/`DEP-3` is now the only one of `P-6`'s three evaluation subjects that was commissioned late**, so its evidence has the least runway.

**The review date does not move.** Moving it would reopen `OB-2` and destroy the property that made `T-3` preferable — *a date always arrives*. **If the investigation cannot produce useful evidence by 2026-08-30, the correct outcome is to report that honestly at the review, not to move the date.**

## 7 · Next actor

> **Session 4 (Architecture)** — run the startup check, then investigate within `G-KOS-ROLEID-ARCH` only, and deliver the bounded recommendation.

**Carried into the lane:** `operable ≠ authorized` · mechanism change is a **recorded dependency**, never designed · the deliverable is a **recommendation for human decision** · **no self-certification** · **Architecture does not complete its own assignment** (`G-1`) · **`A-5.2`'s four-part disclosure duty applies** to any Governance review of the resulting report.

**Untouched:** `V-3` · `D-2` · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. `KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed.

---

## 8 · PO/ARB refinements to the commission (2026-08-15) — registered and binding

The PO/ARB confirmed the commission and added **two refinements**. The machine grant text is **append-only and therefore unchanged**; these refinements are carried **here**, in the artifact the grant's `humanActRef` already references, and **they bind the investigation**. **Neither expands scope — both narrow or clarify it.**

### 8.1 · No preselected answer — in either direction

> *"Do not preselect the 'combined provenance model' as the likely answer. Let Architecture investigate the alternatives and recommend one. Otherwise the investigation is subtly biased toward a predetermined solution."*

**Registered, and applied against Governance's own wording.** No combined model was preselected — **but §3's earlier phrasing leaned the opposite way**, toward separate instruments. **Both leanings are bias.** The line now held:

| | |
|---|---|
| **Required** | The four **questions** must be clearly **distinguished** — this is the commission's own instruction |
| **Open — not to be prejudged** | **How many instruments answer them.** One combined mechanism · several separate ones · a partial subset · none — **all remain live outcomes, and the investigation decides on evidence** |

**Governance corrected its own steer rather than defending it.** *(§3, annotated in place.)*

### 8.2 · Separate what can be evidenced by 2026-08-30 from what needs a later phase

> *"Given your 30 August 2026 review date, I would explicitly ask Architecture to distinguish between **what can realistically be evidenced before 30 August** and what would require a later implementation phase. That prevents DEP-3 from becoming an open-ended architecture project."*

**Registered as a deliverable requirement.** The recommendation must split its content into:

1. **Evidenceable by 2026-08-30** — what this investigation can actually establish, and with what evidence, in the remaining window.
2. **Requires a later implementation phase** — recorded as **dependencies with their authorization requirements**, *not designed here*.

> **This makes the deliverable useful at the review even if the investigation is short**, and it is the structural guard against `DEP-3` becoming open-ended. It also composes with the existing scope line requiring *"the minimum evidence that would be useful for the 30 August review"* — §8.2 says where the boundary falls; that line says what must be on the near side of it.

### 8.3 · The principle both refinements protect

> **`DEP-3` investigates PROVENANCE. It does not create authority, independence, or authorization.**

Already binding via `INV-ATTR-1` (identity is evidential, never an authority input), `INV-ATTR-2` (self-declared is never attested), `A-5.5` (reporting-only, never a gate) and `A-4` `C-1` (the terminal confers nothing). **Restated because it is the sentence the whole lane must not drift from** — and because question ④ (*genuine independent judgement*) is precisely the one no identity scheme can answer.

**Unchanged by these refinements:** the grant's exclusions · the reserved implementation decision · the 2026-08-30 review date · the human-START reading disclosed at §5 · every prior amendment.

---

## Traceability

PO/ARB decision 2026-08-15 (§1 verbatim) · `KOS-ROLE-IDENTITY-001` seq 1–3 · `G-KOS-ROLEID-ARCH` · origin `DEP-3` (`A-5.4`) and `FU-1` · `P-6` evaluation scope · `A-6.8` (review date 2026-08-30) · `INV-ATTR-1`/`INV-ATTR-2` (`A-5.3`) · `A-5.5` reporting-only · `A-4` `C-1` · `C-3` convention · measured evidence: `recordedBy` probe, `executionContext` source census, git identity/signature census (`184d2745`) · `D-2` · `DEP-1` · `D-6` · START-reading precedent `KOS-SESSION-DISCOVERY-001` seq 7
