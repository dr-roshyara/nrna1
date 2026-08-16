# Decision Registration — P-1 APPROVED: Staged Hybrid Attribution

**Registered by:** Governance, on the delivered Human/PO/ARB act
**Date:** 2026-08-16
**Work item context:** `KOS-GOV-ATTRIBUTION-001` (ADP delivered 2026-08-15; P-1 of P-1…P-6)

---

## 1 · The human act, verbatim

> **P-1 — APPROVED: Staged Hybrid Attribution**
>
> KnowledgeOS shall improve attribution progressively. Every governed action must carry explicit attribution and disclose the level of assurance supporting that attribution. Stronger attribution shall be required for actions whose business risk, governance importance, audit requirements, or demonstrated incidents justify it.
>
> This decision does not select a signing technology, identity provider, Git configuration, process-ID mechanism, database structure, or other implementation design.
>
> Governance shall define the business assurance categories and escalation triggers; Architecture shall later design the mechanism after the remaining attribution decisions are resolved.

---

## 2 · What the decision establishes — in the PO's wording, which supersedes the ADP's option label

The ADP offered **A5 (staged hybrid)** as a sequence of mechanisms (convention → record field → signatures). **The PO's approved wording is broader and is the governing text:**

1. **Progressive improvement** — attribution strengthens over time, not in one step. *(Aligns with A5.)*
2. **Universal attribution with disclosed assurance** — *every* governed action carries explicit attribution **and states the assurance level supporting it**. This generalizes INV-ATTR-2 from a labelling duty into a first-class attribute of every act: not merely "self-declared until attested", but *"say which level this is."*
3. **Risk-proportionate escalation** — stronger attribution where **business risk, governance importance, audit requirements, or demonstrated incidents** justify it. The escalation driver is *business criteria*, not technology availability.
4. **No technology selected** — signing, identity providers, git configuration, process-ID mechanisms and data structures are all explicitly **not chosen** by this decision.

## 3 · Responsibilities assigned by the act

| Role | Assigned by P-1 | Status |
|---|---|---|
| **Governance** | define the **business assurance categories** and **escalation triggers** | **Commissioned by this act — not yet started** (see §4) |
| **Architecture** | design the mechanism — **only after the remaining attribution decisions (P-2…P-6) are resolved** | **Explicitly deferred by the act** |
| Implementation | — | nothing authorized |

## 4 · Governance recommendation on sequencing its own deliverable

The assurance-category definition should **wait for P-3 and P-5** before drafting: P-3 decides whether the two attribution invariants become binding rule text (the categories must be written under them), and P-5 decides whether any assurance level may ever gate rather than report (which changes what a "category" is allowed to imply). **Drafting categories before those two decisions risks encoding assumptions the PO has not yet made.**

**Recommendation: decide P-2…P-6, then Governance drafts the categories as its next deliverable.** If the PO prefers the categories drafted immediately, Governance will do so and mark the P-3/P-5-dependent aspects as provisional.

## 5 · What this registration does not do

No mechanism change · no technology evaluation · no Architecture commissioning · no assurance categories drafted yet · no change to `AST-015`/`AST-016` · P-2…P-6 remain undecided and are unaffected.

---

**Traceability:** PO/ARB act 2026-08-16 (verbatim, §1) · decision request `2026-08-16-decision-request-attribution-P1-P6.md` (`56fa5706`) · ADP §3/§3.2/§5(1) (A5) · INV-ATTR-1/2 (P-3 pending) · §6 honesty limit (P-5 pending) · acceptance registration `487fce74` (the accepted root gap this remediates)
