# `KOS-CONTRACT-NEUTRALITY-001` — Pass-1 containment verification · **one unauthorized act found** · amendment **DRAFTED, NOT REGISTERED**

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-29
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; holds no lane on this work item; not the performer of anything examined below)*
**Commissioned by:** PO/ARB, 2026-09-29: *"verify Pass 1 hasn't drifted beyond its containment rule"*, then *"draft the amendment and record the finding."*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0), per the convention recorded 2026-09-28 (`1a1d006b1`).

> ### ⛔ **READ-ONLY VERIFICATION.** No transition, no grant, no amendment registered. The `expected.json` change examined below was **not** reverted, altered or re-performed. **Nothing here ratifies anything** — §5 is a *draft* awaiting a human act.

---

## 1 · Verdict

**Pass 1 did not drift.** The expansion beyond evidence reconciliation happened through **separate authorizations**, which is precisely what the containment rule required:

> *"PASS 1 MUST REMAIN AN EVIDENCE-RECONCILIATION ACTIVITY AND MUST NOT SILENTLY BECOME THE IMPLEMENTATION OR CONTRACT-CORRECTION ACTIVITY. If reconciliation reveals that correction or implementation is needed, that is a FINDING to report — not work to perform. **Any continuation requires a SEPARATE authorization.**"*

**One act is the exception**, and it is a **recording gap, not a rogue change** — §3.

## 2 · What the verification established

| Check | Result |
|---|---|
| **Separate authorizations for the expansion** | ✅ **11 new grants** since 2026-09-04 — targeted V-3 continuation · adapter slice · 7 Python semantic experiments · evidence-precision correction · Python rule-validation. **Every one carries a `humanActRef` with a verbatim PO/ARB act.** |
| **Second lane or second actor** | ✅ **None.** Workflow record unchanged at **45 transitions**; the S5 lane remains the sole performer. One actor, many grants — the Option-C pattern held throughout. |
| **`KOS-ARCH-BASELINE-001`** | ✅ **Untouched** — 0 files. |
| **The seven golden fixtures** | ✅ **Unmodified.** Across `scripts/observations/` the *only* change is the `expected.json` prose addition in §3; no fixture value key was touched. |
| **Python implementation** | ✅ Performed, but **under its own dedicated grants** — barred by Pass 1's grant, permitted by the later ones. Correct sequencing. |
| **`expected.json` (the contract)** | ⛔ **MODIFIED WITHOUT A COVERING GRANT** — §3. |

## 3 · The finding — `CV-1`: the D-1 incorporation has no covering grant

**What happened.** On **2026-09-28** (`9f83a369c`, *"formally incorporate D-1 into the expected.json contract"*), `scripts/observations/examples/lcom4/expected.json` gained a seventh pinned-decision key, `d1_computed_method_name_representation`.

**What is right about it, stated first because it is most of the picture.** The act rests on a **genuine human decision** — *"Option A — formally incorporate D-1 now, **made by the human**"* — and the performer recorded itself as *"executing the decision, not making it."* The execution was narrow and verifiable: prose-only, one key, `D-4`/`D-5` explicitly excluded, JSON validated, full Cohesion suite re-run **188/188 green**, no fixture value touched. The performer even disclosed that it had **not** inspected or transitioned workflow state. **This is not a rogue change, and it is not being treated as one.**

**What is missing.** **No grant authorizes it, and the grant closest in scope forbids it by name.** `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION` — the grant that *named D-1* — states verbatim:

> *"does not authorize: implementation, **contract modification (`expected.json` or the pinned decisions)**, golden fixture modification, application/runtime code change, changing `G-KOS-CONTRACT-V3-ARCH` or `AMD1`, closing the V-3 lane, or reopening any item the decisions-registration already closed."*

And its own human act is equally explicit: *"**No implementation, contract modification, fixture modification, or runtime code change is authorized by this act.**"*

**This is not an isolated silence.** A sweep of all 40 grants across both work items found that **every grant touching this contract forbids modifying `expected.json`** — `SEMANTIC-CLARIFY`, `SEMANTIC-DRAFT`, `SEMANTIC-CLARIFY-AMD1`, `IMPL-ARCH`, `IMPL-ARCH-AMD3`, `ARTIFACT-UPDATE-AMD3` (*"**do not modify expected.json**"*), `V3-TARGETED-CONTINUATION`, `ADAPTER-SLICE1`, all seven Python experiments, `PYTHON-EVIDENCE-PRECISION-CORRECTION`, `PYTHON-RULE-VALIDATION-R1`.

**Exactly one grant in the estate ever permitted such an edit:** `G-KOS-LCOM4-CONTRACT-APPLY`, scoped to *"add the **sixth** pinned decision … using the PO/ARB approved wording"* — a different work item, already applied, different content. **The 2026-09-28 change added a seventh key and is not covered by it.**

**Classification: `GOVERNANCE — RECORDING GAP`, not `BREACH OF SUBSTANCE`.** The authority existed; it was never registered, and it contradicts a live prohibition that was never amended. By this estate's own governing principle — ***"the grant is the authority; the assignment text cannot manufacture authority"*** — a standing bar needs an **amendment before the act**, not a decision recorded only in a downstream document afterwards. **The record, as it stands today, cannot show what authorized the change.**

**Not claimed:** that the decision was wrong · that the wording is defective · that anything should be reverted. **The substance is the PO/ARB's and is not reviewed here.**

## 4 · A second observation — `CV-2`: the question Pass 1 exists to answer may have been superseded

`G-KOS-PYTHON-RULE-VALIDATION-R1-CONSTRUCTORS` carries this human act:

> *"From now on Python should become a second native implementation of KnowledgeOS rules, **not a test language for PHP**."*

That is a **strategic reorientation**, and it is **properly authorized** by its own grant — no drift is alleged. But it changes what the work item is for. Pass 1's deliverable was a neutrality verdict from the fixed vocabulary (`PROVEN NEUTRAL` / `CONDITIONALLY NEUTRAL` / `NOT NEUTRAL` / `NOT YET DETERMINABLE`), and **nothing on record closes it**.

**Open question for the PO/ARB, not decided here:** is the neutrality verdict still owed, or superseded by the reorientation? **`RECORDED · NOT DECIDED`.**

## 5 · The amendment — **DRAFTED, NOT REGISTERED**

> ⛔ **This is a draft. Governance has written no grant.** The amendment's entire content is *"the human ratifies X"* — **only the PO/ARB can say what it ratifies.** Grants are **append-only and cannot be edited**, so a wrongly-worded ratification would be permanent. **Confirm the wording and Governance will register it; amend the wording first if it does not say what you mean.**

Proposed `grantId`: **`G-KOS-CONTRACT-V3-TARGETED-CONTINUATION-AMD1`** · `status`: `AUTHORIZED` · `registeredBy`: `governance` (automatic)

**`authority`:**
> PO/ARB (human) — in-session ratification 2026-09-29 of the decision act of 2026-09-28

**`humanActRef`** *(the PO/ARB supplies or confirms the verbatim act; the 2026-09-28 decision is quoted as its subject)*:
> PO/ARB act 2026-09-29, ratifying the act of 2026-09-28: 'Option A — formally incorporate D-1 now', made by the human on the options presented in `2026-09-28-KOS-D1-governance-decision-preparation.md`, and executed at commit `9f83a369c`.

**`scope`:**
> **AMENDMENT 1 to `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION` — RATIFICATION OF AN ACT ALREADY PERFORMED, NARROWED TO D-1.**
> **This amendment is recorded AFTER the act it authorizes, and says so plainly rather than presenting itself as prior authorization.** The base grant's human act stated *"No implementation, contract modification, fixture modification, or runtime code change is authorized by this act"*, and its scope excluded *"contract modification (`expected.json` or the pinned decisions)"*. On **2026-09-28** the PO/ARB decided **Option A — incorporate D-1 now**, and that decision was executed at `9f83a369c`: a single prose-only key `d1_computed_method_name_representation` added to `expected.json._variant_decisions_pinned`, `D-4`/`D-5` explicitly excluded, JSON validated, Cohesion suite 188/188 green, **no fixture value touched**.
> **WHAT THIS AMENDMENT DOES:** lifts the base grant's contract-modification bar **for D-1 only**, and **only for that already-performed edit**, so the record can show what authorized it.
> **WHAT IT DOES NOT DO:** it does **not** lift the bar for `D-4` or `D-5`; does **not** authorize any further `expected.json` or pinned-decision change; does **not** authorize golden-fixture modification, implementation, or application/runtime code change; does **not** change `G-KOS-CONTRACT-V3-ARCH` or its `AMD1`; does **not** close the V-3 lane; does **not** reopen anything the decisions-registration closed; does **not** ratify any other act, past or future; and does **not** alter the Pass-1 grant or its containment rule.
> **STANDING RULE REAFFIRMED:** the grant is the authority. A live prohibition is lifted by an amendment **before** the act. This ratification records an exception and **sets no precedent** for acting first and registering later.

## 6 · Non-actions

No grant registered · no amendment registered · no transition (still **45**) · `expected.json` **not** reverted, altered or re-performed · no fixture, code, adapter or test change · no V-3 adjudication · `CV-2` not decided · `KOS-LCOM4-CONTRACT-001` not reopened · nothing in any grant's not-authorized list touched · the performer's own records not altered.

**Note on this document's own placement:** it follows the convention recorded 2026-09-28 (`docs/knowledgeos/reviews/`). Governance's earlier documents on this work item sit under `docs/publicdigit/reviews/` with ~128 others and **stay there by explicit human decision** — *"a sequencing deviation is recorded, not reverted."*

**Traceability:** PO/ARB acts 2026-09-29 · `9f83a369c` (the act examined) · `2026-09-28-…-D1-incorporation-registration.md` · grants `G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`, `G-KOS-LCOM4-CONTRACT-APPLY`, `G-KOS-CONTRACT-PASS1-RECONCILE` (+`AMD1`), `G-KOS-PYTHON-RULE-VALIDATION-R1-CONSTRUCTORS` · fold (45 transitions, 40 grants, S5 sole performer) · placement convention `1a1d006b1` · `ES-006.1`
