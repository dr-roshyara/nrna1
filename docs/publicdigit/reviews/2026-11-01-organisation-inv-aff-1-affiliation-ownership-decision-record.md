# INV-AFF-1: organisation-owned affiliation with central discovery, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED.** The Principal said "yes, with boundaries" on 2026-11-01. Approval by PO + ARB, and data-protection review, are still required.
- It is not ratified, and **feasibility is not established**: see the evaluation report.
- It is not an approval to implement anything.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.
**Related (all PROPOSED):**
- XD-3a, XD-3b, XD-3c;
- Decision C;
- business rules `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md`;
- evaluation `2026-11-01-identity-affiliation-discovery-evaluation.md`.

**Approved decisions this must respect:** PBDIGIT-30 B1 (B1.3 / B1.4 sign-in routing, B1.5 remembered choice), B10 (election-day direct entry). Ratified Membership architecture (2026-10-08).

---

## 1. Invariant (proposed wording)

> **INV-AFF-1, organisation-owned affiliation with central discovery.** Each organisation owns and stores its affiliation records in its own database. At sign-in, PublicDigit may *discover* which organisations a person can enter, using signed, organisation-specific identity assertions. It may not *persist* a cross-organisation affiliation register, and may not log discovery results that would recreate one, unless a separate business and data-protection decision authorises it. Access to an organisation's data occurs only for requests in that organisation's context. Automatic entry happens only when discovery is complete.

**Boundaries set by the Principal:**
- Each organisation owns its affiliation records in its own database.
- PublicDigit may discover available organisations at sign-in.
- PublicDigit must not persist a central, cross-organisation affiliation register, and must not create one indirectly through logs (or caches, traces, sessions; see the evaluation, item 4).
- Organisation data is accessed within the appropriate organisation context.
- Automatic entry is allowed only when discovery is complete (approved B1.4: "never guess").
- **A `PersonRef` is not proof of identity or authorisation.**

**What the invariant promises, and what it does not:**
- It promises **no persisted register** and **organisation-context access only**.
- It does **not** promise that the platform operator is technically unable to reach organisation data. One shared application serves all organisation databases; stronger separation needs per-organisation credentials (multi-database decision D6), auditing and operational controls.

## 2. Proposed consequences for other records (proposed changes only)

| Record | Proposed change | State |
|---|---|---|
| BR-9 (business rules) | direction: affiliation is **organisation-local** | proposed; see that file |
| XD-3a, OQ-3a-3 | the earlier note "the central index cannot be avoided, only minimised" is **superseded in direction**: discovery without a persisted index, *if* the evaluation supports it | proposed; see that file |
| XD-3b, K4 | if the derived-reference mechanism (L2) is adopted, Identity creates the Person at import and computes the organisation's `PersonRef`, **storing no organisation for it**. K4 would then be resolved without dropping the XD-3b condition. | proposed; **not resolved** until a mechanism is chosen |

## 3. Kept separate, not decided here

- **B1.5:** where the remembered Working Organisation is kept (central pointer vs on the person's device).
- **K7:** amendment of approved B1 for PublicDigit as a real organisation (candidate wording "B1′" in the Decision C record).
- **The discovery mechanism** (L1 / L2 / L3): see the evaluation.

## 4. Traceability

- Strategic discovery conversation, 2026-11-01:
  - the Principal's decision "INV-AFF-1: yes, with boundaries";
  - the Principal's decision "bounded discovery evaluation: yes, research model and documentation only".
