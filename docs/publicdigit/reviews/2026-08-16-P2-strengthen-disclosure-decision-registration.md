# Decision Registration — P-2 APPROVED: Strengthen Disclosure (with PO refinement)

**Registered by:** Governance, on the delivered Human/PO/ARB act
**Date:** 2026-08-16
**Work item context:** `KOS-GOV-ATTRIBUTION-001` (P-2 of P-1…P-6; P-1 decided earlier today)

---

## 1 · The governing decision text, verbatim

> **A producer may review governance artifacts they helped create, provided their involvement is explicitly disclosed and the review scope is clearly stated. Such reviews may assess completeness, traceability, or administrative quality, but must not be represented as independent verification. Any aspects that could not be independently established must be explicitly identified.**

— PO/ARB, 2026-08-16. *Approve P-2 — Strengthen disclosure, with refinement.*

---

## 2 · The two review classes established by the act

| | **Class A — Ownership Review** | **Class B — Independent Verification** |
|---|---|---|
| **Assesses** | quality and completeness of **records** | correctness of **conclusions or outcomes** |
| **Examples** | completeness checks · formatting · evidence-presence · traceability · record maintenance | correctness validation · compliance certification · independence assessment · acceptance recommendations · implementation verification |
| **Producer may perform?** | **Yes, with disclosure** | **No — cannot be claimed by a participant in the work** |
| **Required disclosure** | *"Reviewer participated in producing this artifact"* + scope | *"This activity was not independently verified"* wherever Class B was not performed |

**The mandatory disclosure shape** (the act makes the "not independently established" element mandatory, not optional):

```
Reviewer involvement:            what the reviewer produced or co-produced
Review scope:                    what this review actually assessed
Not independently established:   what this review could not and does not claim
```

A generic *"I reviewed my own work"* does not satisfy the duty.

---

## 3 · Two rationale corrections the act makes — recorded because they change the rule's foundation

**3a · The justification is the review-class distinction, not "recoverability."** The ADP defended permit-with-disclosure on the ground that scope errors are *recoverable from the record afterwards*. The PO's governing rationale is sharper: **governance review assesses the quality and completeness of records; independent verification assesses the correctness of conclusions or outcomes** — different activities with different independence requirements. Recoverability remains supporting evidence; it is no longer the load-bearing argument.

**3b · The rule must not rest on today's missing attribution.** The absence of trustworthy actor identity is temporary state (P-1 is already improving it). Grounding P-2 in *"we cannot enforce a prohibition anyway"* would make the rule unstable — collapsing the moment attribution arrives. **The permanent justification:** *some review activities require independence and some do not; when independence is absent, disclosure is mandatory.* **P-2 therefore remains valid unchanged after P-1/P-4 introduce stronger attribution.**

---

## 4 · Boundaries

- **Never weakened:** implementation verifying its own implementation remains prohibited (`A-1.4`/`D-5`, `R-34`). P-2 governs *governance-level review*, not verification.
- **Deferred to Architecture** (after P-3…P-6): the exact disclosure template and any technical enforcement mechanism. The shape in §2 is the governing structure; its form as an artifact convention is design work.
- **Interaction with P-1 recorded:** the assurance-level disclosure P-1 requires and the involvement disclosure P-2 requires are complementary duties on the same acts — the future assurance categories (Governance's P-1 deliverable) should express both without merging them.

## 5 · Not done

No template drafted · no enforcement mechanism · no change to `A-4.3`'s text (it stands until the rule text is placed by the established parsimony route — a placement Governance will propose with the P-3 registration if P-3 adopts the invariants) · P-3…P-6 undecided and unaffected.

---

**Traceability:** PO/ARB act 2026-08-16 (§1 verbatim; refinements §2–§3) · decision request `56fa5706` · ADP §4 (options; §4.1 recoverability asymmetry — superseded as load-bearing rationale per §3a) · `A-4.3` · `A-1.4`/`D-5` · `R-34` · P-1 registration `02f813ae`
