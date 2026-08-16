# Decision Registration — P-5 APPROVED: Independence Assessments Are Advisory

**Registered by:** Governance, on the delivered Human/PO/ARB act
**Date:** 2026-08-16
**Work item context:** `KOS-GOV-ATTRIBUTION-001` (P-5 of P-1…P-6; P-1…P-4 decided earlier today)

---

## 1 · The governing decision text, verbatim

> **P-5 — APPROVED: Independence assessments are advisory**
>
> **Automated mechanisms may analyze available attribution and separation evidence and provide findings, warnings, risk indicators, or recommendations. Such assessments must not be represented as proof of independence and must not, by themselves, constitute authorization for governed work.**
>
> **Where independence is materially relevant, the evidence and assessment shall be presented to the Human/PO/ARB for decision.**
>
> **Automated enforcement may only be introduced through a separate governance decision based on clearly defined, objective, and auditable criteria.**

— PO/ARB, 2026-08-16.

---

## 2 · The refinement the act makes — advisory, not reporting-only-forever

Governance recommended *"reporting-only, as a standing constraint."* **The act approves the intent but corrects the breadth**, and the correction is registered because it changes what future designs may do:

**The real prohibition is not automated blocking — it is automated blocking on weak evidence.** The act distinguishes:

| | **Inferred judgments** | **Objectively measurable prerequisites** |
|---|---|---|
| Example | *"these lanes are independent"* | *"the required approval exists — yes/no"* · *"the verification artifact exists"* · *"the signature exists"* |
| Nature | a governance judgment reached by proxy | a factual presence check |
| May determine workflow outcome? | **No** — the evidence is insufficient in principle (P-4: distinct lanes ≠ independence) | **Potentially yes** — via the separate governance decision the act requires |

**Consistency with the running mechanism, recorded:** the existing workflow engine already embodies this line — its preconditions *"check that recorded facts exist; they never supply them"* (Inv I), and the START gate requires a recorded human act and a recorded handoff to *exist*. Those are factual prerequisite gates, not inferred judgments, and P-5 leaves them untouched.

## 3 · What "advisory" does not mean

**Advisory ≠ unimportant.** An assessment may be *high-priority evidence requiring Human attention before proceeding* — the system may raise the issue as strongly as the evidence warrants. The boundary is solely:

```
evidence ≠ authority
```

The system informs the human; it does not replace the human.

## 4 · The evolution path the act deliberately keeps open

Automated enforcement is not forbidden forever — it requires **a separate governance decision** grounded in **clearly defined, objective, and auditable criteria**. The current advisory status is justified by *the present insufficiency of the evidence* (distinct lane identities can share one human, one environment, one authority chain, one bias), **not by a permanent principle against automation**. If future evidence classes clear the bar, the decision to gate is the PO/ARB's to take then — explicitly, never by drift.

## 5 · How P-3 · P-4 · P-5 now compose

```
P-3   attribution never authorizes           (invariant)
P-4   lanes produce attribution evidence      (evidence policy)
P-5   assessments of that evidence advise;    (disposition policy)
      they never decide
```

P-5 is INV-ATTR-1 applied to automation: an assessment built on attribution must not acquire the authority that attribution itself is forbidden to carry.

## 6 · Not done

No assessment instrument designed or built · no criteria for future automated enforcement defined · mechanisms remain an Architecture responsibility subject to Governance approval (per the act and the P-3 addendum split) · P-6 undecided and unaffected.

---

**Traceability:** PO/ARB act 2026-08-16 (§1 verbatim; §2 factual-vs-inferred distinction; §4 evolution clause) · decision request `56fa5706` ("reporting-only" recommendation, refined by §2) · ADP §6 (the honesty limit: process-distinctness is a proxy) · P-3 `6ad39fa0` (INV-ATTR-1) · P-4 `fdfd6470` (loophole sentence: lanes ≠ independence) · `workflow-state.php` Inv I (factual preconditions precedent) · G-2 verification `fe298569`
