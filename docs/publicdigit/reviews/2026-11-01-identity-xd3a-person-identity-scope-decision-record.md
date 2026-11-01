# XD-3a: Person identity across organisations, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED, pending governance approval (PO + ARB, with data-protection review).** The Principal instructed this record on 2026-11-01. It is **not** an approval to implement anything.
**Amendment (2026-11-01, same day):** at the Principal's request, option A is held as a **candidate, not a final selection**, until the future organisational-multiplicity rule (OQ-3a-4) is explicit. The five conditions in §2 stay mandatory whichever option is finally selected.
**Placement:** derived with `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); file name follows the existing `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.

---

## 1. Decision

**Question.** When the same human belongs to two organisations, does PublicDigit recognise one Person or separate Persons?

**Selected option (proposed): A, one platform-wide Person identity, owned by the Identity context.** Each organisation keeps its own OrganisationAffiliation, Membership, committee, appointment and governance facts, which refer to that Person.

**Option D** (one platform Person, but an organisation-specific pseudonymous reference stored in each organisation database, resolvable only by Identity) **is retained for evaluation as a separate hardening measure.** It must not be implemented without its own architecture decision, and it must not weaken the conditions in §2.

**Preferred direction for option D (Principal, 2026-11-01; NOT decided, NOT ratified):**
- Identity keeps a stable internal `person_id`.
- Each organisation receives **its own opaque `PersonRef`**, so organisation databases share no identifier.
- **Reasons given:** privacy; organisation-local ownership of affiliation data; no cross-organisation writes when resolving or merging identities.

**Reconciliation, 2026-11-01:**
- Compatible with the ratified Membership model: opaque `PersonRef`, unique per `(organisation_id, person_ref)`; Person identity = `PersonId`, owned by Identity.
- Compatible with ratified DS-3: a Membership root-merge is needed only when one organisation holds two references for the same human.
- Identity's mapping `person_id → (organisation, PersonRef)` **is** the central index that approved PBDIGIT-30 B1.3 / B1.4 require for sign-in routing (0 / 1 / 2+ organisations). So OQ-3a-3 resolves to **"minimise, cannot avoid"** while B1.3 / B1.4 stand, and condition C-4 (minimisation, data-protection review) applies to that mapping.
- Open conflict K4 with XD-3b is listed there.

**Proposed change (2026-11-01, INV-AFF-1, PROPOSED):**
- The OQ-3a-3 reading above ("cannot avoid, only minimise") assumed that the sign-in answer must be **stored**.
- INV-AFF-1 sets the direction to **discover at sign-in, persist no cross-organisation register**. That requires per-organisation references that Identity can **compute** rather than store (mechanism L2 in the evaluation).
- Whether that is feasible is **not established**. If it is not, the stored mapping (L1) remains the fallback under C-4.
- See `2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md` and `2026-11-01-identity-affiliation-discovery-evaluation.md`.

**Naming note.** In the 2026-11-01 discussion, the labels "Option A" and "Option B" were used in opposite senses in different messages. In this record, **A means platform-wide Person**.

## 2. Mandatory architectural conditions

All five are binding conditions of this decision:

| # | Condition |
|---|---|
| C-1 | Identity does not imply visibility into another organisation's data. |
| C-2 | The central Identity store holds only the minimum information required for identity and authentication. |
| C-3 | OrganisationAffiliation, Membership, committee, appointment and governance facts remain in the respective organisation's database. |
| C-4 | The central index of which organisations a person may access is minimised and undergoes data-protection review. |
| C-5 | Person merges happen only through the explicit Identity act required by the ratified architecture (DS-3). |

## 3. Constraints on use of this record

- It is **not** approval to implement a global identity service.
- It does not authorise any change to, or duplication of, the Membership implementation.
- **No legal basis is assumed** for central identity or for cross-organisation identity resolution.
- The ratified Identity and Membership decisions are preserved unchanged.

## 4. Questions kept open

| # | Question | Owner |
|---|---|---|
| OQ-3a-1 | What is the legal basis, and who are the controllers and processors? | PO + data protection |
| OQ-3a-2 | Does any organisation require legally separate identities? (If yes, the hybrid option is reconsidered for that organisation.) | PO |
| OQ-3a-3 | Can the central organisation-access index be avoided or minimised by another login and routing model (e.g. per-organisation hostname login with central credentials only)? | ARB, linked to multi-database decision D1 (cookie scope) |
| OQ-3a-4 | **Future rule:** may one person belong to more than one organisation? Today they may [F]. The Principal has said this will change; the target rule is not yet stated. | PO. **Answered 2026-11-01: yes.** BR-4 ("one person can be associated with multiple organisations", confirmed) in `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md`. The candidate hold on option A (status amendment above) is therefore lifted. XD-3a remains PROPOSED pending governance approval. |

**Evidence for OQ-3a-4:**
- No ratified or candidate rule limits a person to one organisation.
- The only multiplicity rule, INV-MULT-1 (candidate, consistent with frozen BD-I5), applies **per person per organisation** (`2026-10-08-e0a2-membership-development-report.md:117,281`).
- Live: `organisation_users` is unique on `(organisation_id, user_id)` only, and `user_organisation_roles` allows several organisations per account.

## 5. Evidence

**Ratified decisions it rests on** (2026-10-08):
- Person = a human, `PersonId`, owned by Identity (`2026-10-08-membership-architecture-ratified.md:41`).
- OrganisationAffiliation ≠ Membership (`:42`).
- One Membership per (Person, Organisation) (`:43,140`).
- Identity owns person equivalence (`:157`; verdict `:46`).

**What the ratification does and does not settle:**
- The ratified set is compatible with both a platform-wide and an organisation-local Person. It requires only that identity belongs to Identity and that Membership stores an opaque `PersonRef`.
- Identity **scope** was left explicitly open as XD-3 (`…/cross_domain_brainstorming/20261011_0008_cross_domain_discovery_package_v1.5.md:332`, earlier recommendation "global + consent").
- This record settles the scope half of XD-3 (as XD-3a, proposed). The mapping half (what `PersonRef` concretely is) remains open; see Decision B.

**Current implementation:**
- `users.email` is unique across the platform; one login account can hold affiliations in several organisations (`database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,16,23`).
- No Identity context exists in `app/Contexts/`.
- `PersonRef` exists only as an opaque value object in the Membership kernel (`app/Contexts/Membership/Domain/Organisational/PersonRef.php`).

## 6. Consequences

- **Deployment:** the decision implies a central Identity store separate from organisation databases. That is consistent with tenancy option C (hybrid) of XD-15 (`2026-10-11-cross-domain-decision-round-1-sheet.md:63-73`). **XD-15 itself remains open**, and so does the proposed invariant set INV-DB-1…3.
- **Facts from the central store reach organisation-local decisions with a delay** (XD-10). The locking write guarantees (XD-12) hold only within one database.
- **Conflict with the earlier multi-database recommendation:** investigation #2 keeps `user_organisation_roles` (the affiliation and access grants) **central**, with organisation copies (`docs/publicdigit/brainstorming/multi-database-system/07-authentication-and-identity.md:16,22`). Condition C-3 places affiliation in the organisation database. The two must be reconciled through OQ-3a-3 before any tenancy design is approved. The investigation's files are read-only; a reconciliation goes into a new file.
- **Dependency on OQ-3a-4:** if the future rule is "one organisation per person", enforcing it across separate organisation databases needs a **central cross-organisation membership check**. That is in tension with conditions C-3 and C-4, and it raises the same political-affiliation disclosure risk.
- **Privacy risk to keep visible:** for political organisations, a central record of which organisations a person may access can disclose political affiliation. C-4 and OQ-3a-1 / OQ-3a-3 apply.

## 7. Traceability

- Strategic discovery conversation, 2026-11-01 (Principal decision instruction for XD-3a).
- `docs/publicdigit/reviews/2026-10-11-cross-domain-decision-round-1-sheet.md` (XD-3, XD-15).
- `docs/publicdigit/brainstorming/multi-database-system/` (investigations #1 and #2; read only).
- `docs/publicdigit/membership/reports/20261101-1642-membership-current-status-report.md`.
