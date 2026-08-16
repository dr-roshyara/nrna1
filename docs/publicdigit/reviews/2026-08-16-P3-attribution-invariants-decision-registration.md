# Decision Registration — P-3 APPROVED: Attribution Safety Invariants (three, not two)

**Registered by:** Governance, on the delivered Human/PO/ARB act
**Date:** 2026-08-16
**Work item context:** `KOS-GOV-ATTRIBUTION-001` (P-3 of P-1…P-6; P-1, P-2 decided earlier today)

---

## 1 · The human act, verbatim headline

> **P-3 — APPROVED.**
>
> **Attribution and authorization are independent concepts and must never be inferred from one another. Attribution is treated as declared unless independently established by evidence outside the declarer's control. The assurance level of attribution must be explicit and visible to consumers.**

— PO/ARB, 2026-08-16.

---

## 2 · The adopted invariants — governing text

The act adopts the ADP's two candidates **with refined wording**, and **adds a third**. The ADP's draft wording is superseded by the text below.

**INV-ATTR-1 — Attribution never authorizes.**
*Attribution records who is claimed to have acted. Attribution and authorization are independent concepts and must never be inferred from one another: attribution must never be used as a source of permission or authority. Permission derives from the human decision and the governed grant, never from an audit field.*

**INV-ATTR-2 — Assurance is classified; declared is the default.** *(PO's refined wording, chosen so the principle survives evolving assurance models:)*
*Attribution assurance must be explicitly classified. Unless independently established through evidence outside the declarer's control, attribution shall be treated as declared attribution.*

**INV-ATTR-3 — Assurance is visible.** *(New, added by this act:)*
*The assurance level associated with an attribution record must be visible to consumers of that record.* A consumer must never see "verified by X" where the record knows only "X was declared."

**Non-weakening clause (part of the act):** these principles apply to future Governance, Architecture, Implementation and Verification mechanisms **and must not be weakened by implementation convenience.**

---

## 3 · The responsibility boundary the act sets

> **The determination of sufficient independent evidence is an architectural responsibility subject to governance approval.**

Governance holds the intent (the three invariants); **Architecture determines** what counts as evidence outside the declarer's control (git? signing keys? service principals? approval workflows?) — **subject to governance approval** of that determination. Neither role decides the other's half.

---

## 4 · Why the refinements matter — recorded so the reasoning survives

- **P-3 is deliberately decoupled from P-1.** Whatever attribution mechanism P-1's staged path produces, these invariants hold: weak attribution stays honestly *declared*; strong attribution earns a higher class — the invariants never change. This independence is what makes them invariants rather than policy.
- **INV-ATTR-2's rewording anticipates a ladder of assurance levels** (e.g. declared → recorded → corroborated → verified → cryptographically verified) without naming one. Defining the actual ladder belongs to Governance's P-1 deliverable (the business assurance categories); constraining every rung belongs here.
- **INV-ATTR-3 closes the presentation gap:** assurance honesty in the record is worthless if the surface that displays the record drops the qualifier. Transparency of assurance is as important as assurance itself.
- **INV-ATTR-1 prevents the classic drift:** a future precondition reading `recordedBy` as a permission check would turn an audit field into an authority mechanism — exactly the accepted root gap re-created in a new form.

---

## 5 · Placement (Governance advice; the ADP asked Governance to advise)

Per the parsimony route: **this registration hosts the adopted text** until placement; the invariants' durable home should sit **with the existing orchestration invariants** (alongside `INV-DISC-2` and the `A-4.3` duty), **not in a new document**. The disclosure duty from P-2 belongs under the same placement when it is made rule text. Placement execution is administrative follow-through and will be proposed with the remaining registrations — no second canonical copy will be created meanwhile (ES-005.4).

## 6 · Not done

No mechanism change · no assurance ladder defined (that is the P-1 deliverable, still sequenced behind P-5) · no architecture commissioned · P-4…P-6 undecided and unaffected.

---

**Traceability:** PO/ARB act 2026-08-16 (§1 headline verbatim; §2 refined wording; INV-ATTR-3 addition; §3 responsibility note; non-weakening clause) · ADP §2.2 (candidate wordings, superseded) · P-1 registration `02f813ae` (assurance-level disclosure duty, now constrained by INV-ATTR-2/3) · P-2 registration `8de09453` (complementary involvement disclosure) · accepted root gap `487fce74` · `INV-DISC-2` · `A-4.3` · ES-005.4
