# Decision Registration — the six-role model is ADOPTED as the AI Engineering Platform's engineering operating model

**Registered by:** Governance, on the delivered PO/ARB decision · 2026-08-19
**Subject:** the six-role engineering operating model · **related work item:** `KOS-AIP04-DISCOVERY-001` (ADR-AIP-04 capability discovery)
**⚠ This registration records a DECISION. It decides no capability, no bounded context, no ownership, and no open question.**

---

## 1 · The decision

> **ADOPT the six-role model as the AI Engineering Platform's engineering operating model.**

| # | Adopted role |
|---|---|
| **1** | Governance Engineer |
| **2** | Architecture Engineer |
| **3** | Implementation Engineer |
| **4** | Verification Engineer |
| **5** | Knowledge Engineer |
| **6** | Communication Engineer |

**Meaning, as stated:** the six roles are now **governed architecture as an operating-role model**. They describe **distinct engineering responsibilities within the session-based engineering operating model.**

**Status change:** the six-role model is **no longer classified as an "ungoverned candidate."** Future Architecture / Governance / KnowledgeOS artifacts **may use the six-role vocabulary as an adopted operating model.**

## 2 · ⭐ The non-equivalences — the load-bearing half of this act

**Adoption does NOT by itself establish that:**

```
role ≠ bounded context
role ≠ capability
role ≠ agent
role ≠ platform service
role ≠ organizational position
```

**Those remain separate architectural / DDD questions.**

**The DDD rule, registered as binding:** capability analysis continues **independently** —

```
capability → ownership → bounded context / stewardship → role → agent → service / technology
```

> ⛔ **The adopted role model must not be used as proof that a capability or bounded context exists.**

*This is the sharpest thing in the act, and it cuts both ways. The Verification #3 grant already forbids the inference in one direction — "do not use a role title as evidence that a capability exists." This act closes the other: **adoption is not that evidence either.** A model can be the right way to organize engineering responsibility and still license no conclusion about the domain. Those are different kinds of claim, and this decision keeps them apart deliberately rather than by omission.*

## 3 · Historical material — explicitly not converted

> **Existing ungoverned documents remain historical / unadopted artifacts unless separately incorporated through the normal governance process.**
> ⛔ **Do NOT retroactively convert historical brainstorming documents into governed architecture.**

**Registered consequence, named because it is the concrete case in the live chain:** `docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md` (925 lines, untracked, carrying all six roles and its own ADR-AIP-04 Options A/B/C) is **not adopted by this act.** **Adoption of the model is not adoption of that document.** Its disposition remains `OQ-I`, **open**.

## 4 · ⚠️ Consequence for the registered Verification #3 — returned to the PO/ARB, not resolved here

**Recorded as `G-KOS-AIP04-VERIFY3-AMD1`.** The amendment **adds and removes no scope**; it records a decided input the grant predates. But the grant is now **partly stale**, and Governance does not repair it by inference:

| | |
|---|---|
| **`W-1`** | commissioned to test the **narrow thesis** *"the six-role model is not currently established as governed architecture."* **As of this decision that thesis is FALSE — the model IS governed.** What remains live in `W-1` is the **evidence-quality** question (the six-class distinction, the full-estate search, whether the 925-line artifact creates a new governed fact) and the **historical** question of what was established *when the correction was written* |
| **verdict `G`** | *"six-role hypothesis"* — **no longer has a hypothesis to test.** It must be **re-scoped to the evidence question or struck.** ⛔ **The verifier must not decide this for itself** |

**Unaffected and still live:** `W-2` (C-14) · `W-3` (OQ register) · `W-4` (provenance) · `W-5` (authority distinction) · `W-6` (CAP-09) · `SB-1` · the self-corroboration check · the DDD test · verdicts **A–F** and **H–O**.

*On `W-5` specifically: it distinguishes **approval authority** from **engineering role** from **workflow lane role**, and the model adopted here is an **engineering-role** model. **The adoption does not collapse that distinction** — an adopted engineering role is still not an approval authority and still not a workflow lane.*

## 5 · Next work, as directed

**Re-evaluate the capability / ownership questions using the adopted six-role model as a DECIDED INPUT.**
⛔ **Do not reopen the role-adoption decision.**

## 6 · Not done

`OQ-A`…`OQ-I` **remain open and are not decided by this act** · ADR-AIP-04 not decided · no capability adopted · no bounded context created · no agent or service created · no ownership assigned · no historical document promoted · Verification #3 **still `CREATED`, not started** · no lane closed.

**Next actor: Human PO/ARB → `START S1-verification-aip04-correction2`, with the `W-1` / verdict-`G` reconciliation stated in the START act.**

**Traceability:** the PO/ARB decision act 2026-08-19 · `G-KOS-AIP04-VERIFY3` + `-AMD1` · `KOS-AIP04-DISCOVERY-001` seq 13–14 · ADR-AIP-04 discovery proposal · Correction #2 (`3cbb915c`) · Verification #2 (`acdc613f`, `W-1`…`W-6`) · Verification #1 · `docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md` (untracked, unadopted) · `OQ-I`
