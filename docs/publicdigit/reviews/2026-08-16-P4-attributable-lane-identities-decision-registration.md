# Decision Registration — P-4 APPROVED: Distinct Attributable Lane Identities

**Registered by:** Governance, on the delivered Human/PO/ARB act
**Date:** 2026-08-16
**Work item context:** `KOS-GOV-ATTRIBUTION-001` (P-4 of P-1…P-6; P-1, P-2, P-3 decided earlier today)

---

## 1 · The governing decision text, verbatim

> **P-4 APPROVED: Distinct Attributable Lane Identities**
>
> **Governed execution lanes that produce persistent engineering artifacts shall have distinct attributable engineering identities. Git identities may be used as an initial implementation mechanism.**
>
> **These identities provide attribution evidence and support auditability, traceability, and independence analysis. They do not constitute authorization, proof of individual human identity, or proof of independence.**
>
> **The assurance level associated with lane attribution must be represented explicitly and honestly in accordance with the Attribution Safety Principles.**
>
> **Architecture shall determine the detailed operating model, controls, credential management, and identity-to-lane mapping.**

— PO/ARB, 2026-08-16.

---

## 2 · The reframe the act performs — evidence policy, not a git policy

The decision request offered *"per-lane git identities."* **The act deliberately broadens it:** git is an *implementation detail* and *initial mechanism*; the governance principle is

> **governed execution lanes shall produce attributable engineering evidence**

— a principle that survives a later move to repository signatures, service principals, workflow identities, attestations or signing keys without re-decision. Governance holds the intent; the mechanism may change under it.

## 3 · The audit loophole the act closes explicitly

> **Distinct lane identities provide evidence of role separation but do not, by themselves, establish independence of persons, systems, or decision authority.**

Registered with emphasis, because it forecloses the misreading most likely to arise: a future reader assuming *"verification-lane commit ⇒ independent verification."* The same human can operate both identities; apparent separation is not actual separation. Lane identity is **evidence for independence analysis** — never a verdict of independence. *(This is INV-ATTR-1/2 applied to the new evidence class, and it is also exactly the boundary the mechanism-level verification measured: process-distinctness is a proxy, not the thing itself.)*

## 4 · The open question the act names and assigns — identity *of what?*

The act acknowledges that different identity models carry **different assurance levels**, and assigns the choice to Architecture rather than deciding it:

| Model | Shape | Assurance consequence |
|---|---|---|
| A | lane-owned service identity (`implementation@…` used by the execution environment) | reasonable lane attribution |
| B | shared human account relabelled per lane | **reduces** individual accountability — apparent gain, real loss |
| C | individual identity **plus** lane attribution | strongest of the three |

**Architecture's later design must state which model it provides and classify the resulting assurance under INV-ATTR-2/3** — the models are not interchangeable and must not be presented as if they were.

## 5 · Interaction with the earlier decisions

- **P-1:** this is the first concrete step on the staged path — from *declared* toward *corroborated* attribution, without requiring human identity verification.
- **P-3:** the act's own text subordinates lane attribution to the Attribution Safety Principles: evidence, never authorization (INV-ATTR-1); assurance explicitly classified (INV-ATTR-2); visible to consumers (INV-ATTR-3).
- **P-3 addendum split:** Governance has now set the *requirement* (distinct attributable identities, honestly classified); the *mechanism* (operating model, controls, credential management, identity-to-lane mapping) is Architecture's, subject to governance approval.

## 6 · Not done

No git configuration changed · no identities created · no credential scheme chosen · no identity model (A/B/C) selected · no Architecture work commissioned — mechanism design remains gated behind the remaining decisions (P-5, P-6) per the P-1 act.

---

**Traceability:** PO/ARB act 2026-08-16 (§1 verbatim; §3 loophole sentence verbatim; §4 model acknowledgment) · decision request `56fa5706` (git framing, superseded by §2) · ADP §3 A4/DEP-3, §9 (E-4: one git identity across 60 commits — the measured gap this remediates) · P-1 `02f813ae` · P-3 `6ad39fa0` + addendum · G-2 verification `fe298569` (§6 honesty limit: distinctness is a proxy)
