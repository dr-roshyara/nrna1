# KOS-ATTR-ARCH-001 — Architecture Commission

## Target Governance & Attribution Architecture

**Work item:** `KOS-ATTR-ARCH-001` · **Workflow:** `architecture-design`
**Declared role set:** `governance`, `architecture`, `verification` — **implementation deliberately excluded; see §6**
**Status:** grant AUTHORIZED · architecture assignment REGISTERED · HANDOFF recorded · **START NOT performed**
**Supersedes for execution:** `2026-08-16-KOS-ATTR-ARCH-001-commission-prepared.md` (the unsigned preparation — its wording is honored; this document registers the performed act and the PO's refined scope, which governs where they differ).

---

## 1 · The human act

> **"Yes — commission Architecture now."** — PO/ARB, 2026-08-16

…performed against the drafted commissioning wording the PO endorsed (*"that is exactly what I would commission"*):

> *"Commission Architecture to design the target Governance and Attribution architecture against the approved Business Assurance Model rev 3 and approved P-1 through P-6 decisions. The work must remain architecture-only: no implementation, no technology commitment, and no adoption of candidate invariants INV-ATTR-4/5/6 without separate approval."*

…and with the PO's refined scope in the same message, registered as the governing text (§3–§5).

**Explicitly not this commission:** *"Design the KnowledgeOS architecture"* — too broad, rejected by the PO in the act itself.

---

## 2 · Purpose

Design the target Governance/Attribution architecture required to satisfy the approved business assurance baseline.

## 3 · Inputs

- approved **Business Assurance Model rev 3** (`5ab3b4e6`; approval `f4df7884`/`b8b2914c`)
- approved **P-1 … P-6** (`02f813ae` · `8de09453` · `6ad39fa0` · `fdfd6470` · `defc8a22` · `30976423`)
- the accepted **G-2 root gap** (`487fce74`)
- the **current Architecture Baseline** — **once verified/accepted**: `KOS-ARCH-BASELINE-001` Phase A is still ACTIVE; its output enters as an input when accepted, and until then the target design proceeds on the other inputs without presuming the baseline's conclusions
- existing **`KOS-GOV-ATTRIBUTION-001`** evidence and decisions

## 4 · Architecture must determine

- the **domain model** around Attribution Claim · Evidence · Assurance Assessment · Assurance Outcome;
- the **boundaries** between Governance, Attribution, Authorization, and Verification (the model's §7 candidate domain areas are hypotheses to test, not conclusions to inherit);
- **ownership** of state, knowledge, evidence, and authority;
- actual **dependencies and authority direction**;
- how the **three assurance dimensions** are represented;
- what **mechanisms can provide the required assurance levels** (this answers the P-3 addendum's Architecture half — subject to Governance approval);
- how the **P-2 review classes** and the **P-5 advisory boundary** are represented;
- how **future stronger assurance** can be introduced under P-6.

**First output — ordered by the PO:** a **domain and responsibility model**, not C4 diagrams and not technology choices. The DDD questions come first: *what is an Attribution Claim? who owns it? what is Evidence? who owns it? what is an Assurance Assessment? who can create/supersede it? where is the boundary between attribution, authorization, and verification?* C4 and the target architecture follow once those are stable.

## 5 · Architecture must not

- implement anything;
- choose a technology merely because it is familiar;
- **adopt INV-ATTR-4/5/6** — they remain candidates; the design **may accommodate them** but must not treat them as approved requirements *(written into the grant, per the PO)*;
- change the approved business requirements;
- redesign the entire KnowledgeOS platform;
- **use the target design to overwrite the current-state reconstruction** — Phase A discipline stands: reconstruction before target design, and `KOS-ARCH-BASELINE-001` is not contaminated by this work.

**Boundary note (carried from the preparation):** the Election programme's Architecture pause is **unaffected** by this commission and survives it. Neither posture may be inferred from the other.

## 6 · Why implementation is excluded from the role set

`REGISTER` refuses a role outside the record's declared set. Declaring `governance, architecture, verification` makes **"no implementation" mechanically enforced** for this work item, not merely written in the grant — the same deliberate narrowing used for `KOS-GOV-GAPS-001`, whose merit the PO endorsed (D-2). **Consequence, disclosed:** an implementation lane on this question will be refused by this record and would require a new work item after the design is approved — which is the intended sequence anyway (*design → Human/ARB design approval → separate implementation authorization*).

## 7 · The loop this commission runs inside

```
Human/PO/ARB  — business authorization (performed, §1)
Governance    — record + translate + route (this document; stops before START)
Architecture  — perform the design (after the Human START)
Independent Verification — challenge the result
Human/ARB     — approve/reject the design
Governance    — record the outcome
```

The deliverable **returns for Human/ARB design approval** before anything downstream.

---

**Traceability:** PO/ARB act 2026-08-16 (§1) · prepared commission `b8b2914c` · assurance model rev 3 + approval registrations (cross-referenced) · P-1…P-6 registrations · G-2 acceptance `487fce74` · `KOS-ARCH-BASELINE-001` (ACTIVE, input-when-accepted) · `KOS-GOV-ATTRIBUTION-001` · ES-001.3 command protocol · `R8` · `INV-ATTR-1/2/3` (established) · INV-ATTR-4/5/6 (candidates, accommodate-not-adopt)
