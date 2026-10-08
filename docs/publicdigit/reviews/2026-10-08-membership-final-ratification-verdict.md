# Membership: Final Freeze Audit Verdict

**Date:** 2026-10-08 (final freeze audit) · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **For:** the Principal Reviewer (the ratification act is theirs)
**Evidence:** `…-membership-final-proof.md` (**FP**) §14–§16 · canonical architecture `…-membership-architecture-ratified.md` (**MA**)

## Verdict

**READY FOR PRINCIPAL RATIFICATION**

> **Status annotation (2026-10-08):** the Principal Reviewer **RATIFIED** the architecture and froze it for implementation. EP-01 is authorised. Record: `2026-10-08-membership-architecture-ratification-act.md`. The verdict text below is unchanged.

This document does not ratify the architecture.

**Basis:**
- **All 15 freeze criteria are met** (FP §16.17).
- **The freeze audit found no new counterexample.** It corrected one documentation inconsistency: Annul / Void create no consequences.
- **Architecture-neutral policy:** "Correct needs an active target" is a policy, not a safety rule.
- **Evidence still limited to bounded runs:**
  - the replay-equality result is EXHAUSTIVELY VERIFIED within bounds, not PROVEN;
  - the generator's never-needs-the-gate property is VERIFIED within bounds, not PROVEN.
- **Earlier corrections:** four model corrections in the last three rounds (CX-14a/b/c, CX-15), all confined to resolution / enforcement, all re-verified.

**Freeze statement:** further architectural research is not justified unless a new concrete contradiction appears.

---

## A. Canonical architecture (summary)

- **Membership** is the enduring relationship of one Person with one Organisation (EM-VOC-001 `Member`), identity (p,o). It is the aggregate (D1).
- **A Period** is one legal episode (admission act → ending), identity = its Admit.
- **Facts** are append-only and seq-ordered: Admit, Renew, ChangeType\*, Suspend, Restore, End, Annul, Void, Correct. Each carries effective / decided / recorded time and provenance with authority evidence.
- **Resolution** (pure):
  - live → Active (whole correction chain) → Value (latest live Correct) → BaseClaim (live Admit / End values) → LiveCut (Cut ∩ BaseClaim(cause-target), while the cause is active and not superseded) → ResolvedLifetime → predicates.
  - It is non-circular.
- **Invariants:**
  - INV-M, INV-1;
  - INV-EP (an episode is contiguous);
  - INV-9 (disjoint within the BD-I5 scope);
  - INV-COV (consequences change the owner of an instant, never whether it is owned);
  - INV-2, INV-3, INV-12, INV-6 / INV-14;
  - INV-11 (no entitlement; ADOPTED).
- **Enforcement:**
  - G\* accepts a fact iff the candidate resolution satisfies I;
  - commits are serialised per Membership: expected version, then recompute f⁺ on conflict.
  - G\* is replaceable; the invariants are not.
- **Boundaries:** Election owns entitlement and eligibility; Finance owns settlement; Governance owns competence; Identity owns person equivalence. There is no shared persistence and no Membership → entitlement shortcut.

## B. Proof-status matrix

| Claim | Classification |
|---|---|
| CFC+ non-circular; correction chains terminate; Value deterministic | PROVEN |
| INV-COV, INV-EP, INV-9 for committed states | PROVEN (by construction) |
| the generator never needs the INV-COV gate | EXHAUSTIVELY VERIFIED (bounded) + BOUNDED VERIFIED |
| recorded counterfactual theorem | PROVEN + EXHAUSTIVELY VERIFIED (incl. Annul / Void of Annul / Void) |
| replay = recorded whenever a replay exists | EXHAUSTIVELY VERIFIED (bounded) |
| histories A–R | SCRIPT VERIFIED |
| KnownAt determinism | PROVEN (seq total; recordedAt monotone in seq) |
| need for concurrency control | EXHAUSTIVELY VERIFIED (bounded lost-update demonstration); mechanism = ARCHITECTURE + IMPLEMENTATION |
| D1 | ARCHITECTURE (domain reasons: identity, lifecycle) |
| INV-EP classification | ARCHITECTURE (justified from the ontology) |
| I5 scope | BUSINESS (BD-I5), RECOMMENDATION (p,o) |
| migration safety | DERIVED |
| INV-11 | ADOPTED |

(Full table with evidence and confidence: FP §16.12.)

## C. Decision register (summary)

| Class | Items |
|---|---|
| Adopted / proposed architecture | ontology, D1, fact algebra (FP §16.2), correction algebra (FP §16.3–§16.4), CFC+, invariants, G\* layering, the concurrency requirement, context contracts |
| **Architecture-shaping business decisions** (pre-analysed; MA §10) | **BD-I5** (subtractive) · **BD-REV** (subtractive; includes the correction-target sub-policy) · **BD-SYNC** (asynchronous is additive in the enforcement layer; the ratified branch is transactional) |
| Business policy (architecture-neutral) | BD-REV-ER · BD-1 · BD-2 · BD-NU · BD-REC0 · BD-TYPE · BD-SUSP · BD-TERM4 · BD-TEN · BD-GVR · BD-NORM |
| Legal | LQ-1 · LQ-2 (ex nunc = End(kind); architecture-neutral) · LQ-3 · LQ-4 |
| Product | BD-CONSENT |
| Governance | DS-4 |
| Downstream | DS-1 · DS-2 · DS-3 · DS-5 |
| Implementation | storage shape, version / seq, idempotency, outbox, constraints, projections, time zone |
| Future | ML-assisted anomaly detection; section enrolments |

**The only answer that would reopen the ontology:** "two legally distinct, simultaneous memberships in one organisation". No requirement asks for it.

## D. Implementation prerequisites

1. Principal Reviewer ratification.
2. PO confirmation of **BD-I5, BD-REV, BD-SYNC** (architecture-shaping; before the reversal slice). BD-CONSENT / LQ-1 / BD-REC0 before Admit.
3. PO sequencing act (EM-SEQ-001).
4. EP-01 plan approval and a story ID.
5. The concurrency mechanism (FP §16.10) in slice 1 (fact store).

## E. Business / legal decisions still required

- **Business:** BD-I5, BD-SYNC, BD-REV (incl. the correction target), BD-REV-ER, BD-1, BD-2, BD-NU, BD-3, BD-SUSP, BD-REC0, BD-TYPE, BD-TERM4, BD-TEN, BD-GVR, BD-NORM.
- **Legal:** LQ-1 (consent / statutory bases), LQ-2 (ex tunc / ex nunc annulment), LQ-3 (termination), LQ-4 (retroactive statutes).
- **Product:** BD-CONSENT.
- **Governance:** DS-4.
- **Downstream:** DS-1, DS-2, DS-3, DS-5.

---

**STOP.** No implementation, no migrations, no production code, no EP-01.

**Next:** Principal Reviewer ratification → PO / business parameter confirmation → sequencing decision → EP-01 implementation planning → implementation.
