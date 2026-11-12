# DR-07a: who owns committees, decision brief

| | |
|---|---|
| **Status** | **DECIDED 2026-11-10: option A (D-144, decision record §11.53).** Brief written by Claude, not independent; changes no code |
| **Full paper** | `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` (§4 evidence, §5 options, §8 recommendation, §13 question) |
| **Decides** | DR-07a (register O-6), in the Principal's three roles: PO, Membership owner and ARB chief (paper §9 steps 1–3) |

## 1. The business situation

A party's **committees** (central committee, province committee, district committee) are bodies that hold **positions** (president, secretary), whose holders are **appointed or elected for a term** and **decide within their powers**. **Today the code records committees inside Membership**, and a second, partial write path exists in Governance (paper §4).

So two contexts write committee seats through different stores, and **Governance's invariants run against an empty record**.

## 2. What is already decided (since the paper)

| Decision | Effect on DR-07a |
|---|---|
| **PO-DR07-01** (2026-11-02) | Governance committees are **organs**; organisational units are a separate concept |
| **D-10** (WD) | Governance's working tactical model **already has** Organ, Position, Decision, RuleSet, AuthorityAssignment |
| **D-38**, **D-97** | A position's powers live in the **written rules** (Governance's) |
| **D-72** (ARB) | **Appointment is a capability inside Governance**; Governance owns positions, tenures, delegations and powers, and publishes mandates |
| **D-106** (accepted) and §49–§51 | **Units, unit belonging and coverage are Organisation's** (DR-07b settled in principle); a committee is attached to a unit, not the same thing |
| **Membership act §11** (RAT) | "organs" are named as Governance's |

**[AC] Consequence:**
- **Option C** (Membership keeps committees) would now contradict PO-DR07-01, D-10, D-72 and ratified §11, and would need a Change Request against a ratified text.
- **Option A** (Governance owns) is what every later decision already assumes. **The ARB act was simply never recorded.**

## 3. The question

> **Does Governance own committees (organs), their positions, appointments and terms of office, with Membership supplying only membership facts and Organisation only the unit a committee serves?**

| Option | Meaning | Consequence |
|---|---|---|
| **A, with cutover B (recommended)** | Governance owns them. ADR-0001's **location clause only** is superseded; its CQRS principle stays. The existing Membership committee code is **frozen and used as a behavioural reference** until a Governance model reaches parity, then retired. The Governance stub write path is recorded as a gap | Consistent with every decision above. Governance tactical design for committees can start. **Migration is a later, separately authorised slice** |
| **C** | Membership keeps committees; ADR-0001 unchanged | Needs a Change Request against ratified Membership §11; conflicts with D-72 and D-10 |
| **Defer** | Leave open | Committees cannot be designed or built; two write paths remain |

## 4. What A does **not** decide

- **Not decided by A:**
  - **migration, code or schema changes** (each needs its own authorisation);
  - the **content** of committee powers, which comes from each organisation's written rules (O-8 … O-10);
  - **which existing `committees` rows are organs and which are units in disguise** (DR-07b evidence task O-16).
- **`CommitteeAssociation`** (OQ-DR07-1). **[AC]** It is created when a membership application is approved, so it is **membership held through a unit**. Under D-106 and §50 that belongs to Membership plus Organisation belonging, **not to Governance**. A does not move it.
- **Before migration:** the technical discovery M-1 … M-5 still needs its independent review (PO-ORG-MEM-01 Decision B). **That review gates migration, not this ownership decision.**

## 5. If A is chosen

**Records:**
- a decision entry in the decision record;
- an ADR superseding ADR-0001's location clause;
- O-6 and DR-07a closed;
- the context-map Governance row updated.

**Next:**
- Governance tactical design for committees: organ, position, appointment, term, attached unit, and the Organisation and Membership facts it reads;
- then tests first, then build.
