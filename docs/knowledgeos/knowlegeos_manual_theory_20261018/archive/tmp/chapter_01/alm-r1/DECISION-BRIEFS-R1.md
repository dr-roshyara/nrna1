# Decision briefs: reserved policies the ALM-r1 model computes but does not decide (2026-10-18)

**Prepared, not requested.** These briefs **supersede the numbering** of `../alm/DECISION-BRIEFS-ALM.md`, whose symbols κ, ι and λ collided with the register; that file is kept as history. IDs are the register's (RD/C) or the TODO's (T1-nn). **Recommendations are research opinions, not decisions.**

None of these blocks the research model, because each is a parameter. **They block a final TD-001 r1.2 text.**

---

## C1 · Do records establish authority, or show it?

**Business question:** a manager gave a permission on Monday, and it was written down on Thursday. Was work done on Tuesday authorized?

| Option | Tuesday's work | Consequence |
|---|---|---|
| **C1-b** (acts establish; records evidence) | **authorized**. The records could not show it until Thursday ("cannot determine" until then) | matches how the organisation behaves today: late transcription, no back-dating, "off-record" work treated as an anomaly (S0092, S0095, S0103). Needs the three-answer recognition |
| **C1-a** (only recorded acts count, at the time of the act) | **not authorized**, permanently | strict auditability. It needs **record times**, which the current workflow store does not have (R1-F6). Also, an **unrecorded revocation is ignored** |

**New fact (Pr-10, bounded):** the two options are **incomparable**. In 100,499 checked cases an act is valid under b only; in 80 it is valid under a only (the cause: unrecorded revocations). **Neither is the "safer" choice in both directions.**

**Research recommendation:** C1-b for validity. Recording governs **operational effect** (the "usable from" layer), which keeps C1-a's audit benefit without rewriting history.

---

## RD 1 · When authority ends, do earlier decisions fall with it? (per kind; including delegates, δ)

**Business question:** the Board Chair's mandate ends. Do (a) her past decisions and (b) the Secretary's permission she delegated survive?

| Option | Past decisions | Delegate | Consequence |
|---|---|---|---|
| persist, δ independent | survive | keeps the permission | stability; orphaned delegates are possible |
| persist, δ dependent | survive | loses it | delegates must be re-authorized at a term end |
| cascade (per kind), δ either way | fall when the authorizing permission ends (BASIS = any: if every authorizing permission ended) | per δ | every decision of that kind lapses at a term end unless re-adopted |

**Evidence:** weaker than claimed before.
- [Repo] ADR-001 (point-in-time legitimacy) supports **validity at the time of the act**, not persistence. It is partly superseded.
- [Repo] Stress Test 8 favours **different behaviour per type**.
- [Repo] v3.1 §10.1 ("its authority **lapsed**") leans cascade for rules.

**Research recommendation:** decide **per kind**. A plausible starting point is decisions persist, rules cascade, exceptions cascade (AB L100's "cannot exceed the approver's authority"). δ = dependent for delegated grants, as v1 §1.13.3(d) leans.

---

## T1-20 (with C1/C2) · When may a missing record mean "not authorized"?

**Business question:** nothing on record permits an action. May we call it unauthorized, or only "cannot determine"?

**Fact (Th-5, derived; characterization bounded on 11,920 cases):** while records may arrive late, "not authorized" can be wrong **except** when it rests on:
- a defect in the record itself (e.g. a permission that claims to start before it was given), or
- the self-acceptance restriction (IND-1).

| Option | Meaning | Consequence |
|---|---|---|
| **never** (r1.1: λ⁻ = ∞) | only "authorized" or "cannot determine" | always sound; never decisive |
| **never, except sound negatives** (R1-F4) | also "not authorized" for record defects and self-acceptance | still sound; more informative. **A change to r1.1** |
| fixed window (finite λ⁻) | after N days, a missing record counts as absence | decisive. A late record can later **reverse** the verdict, which must then be reported as a recognition change |

**Research recommendation:** "never, except sound negatives". A window only together with a recording-deadline duty.

---

## C10 (T1-21) · Two conflicting acts at the same instant

**Business question:** two authorized people revoke each other's permission at the same moment.

| Option | Result |
|---|---|
| **given** (the recorded order of acts decides) | the first recorded act wins. **Needs a reliable act-order field** |
| **no_same_instant** (same-instant acts cannot affect each other) | both revocations take effect |
| escalate (both suspended pending a ruling) | not implemented: "conflicting" would itself need a definition |

**Research recommendation:** **no_same_instant** as the default rule, because it needs no order field and is symmetric. Escalation for genuine conflicts if the organisation wants it. *No observed case exists.*

---

## Also open (register): C6 · C12 · C14 · candidate parameters

- **C6:** do later decisions replace earlier ones on the same subject?
- **C12:** can the root change? Only a static root is modelled.
- **C14:** the interval convention for grants. The repository precedent is closed.

**Candidate parameters surfaced by the model, not minted:**
- **claim-period convention** (C14 covers grants only);
- **BAR** (does one barring permission suffice?);
- **BASIS** (must every authorizing permission still be alive under cascade?).

Recording them in the register is the Principal's act.
