# PO-ORG-MEM-01: Organisation ownership of Membership, decision record

**Status:** **APPROVED by the Principal / PO, 3 November 2026** (stated in the strategic discovery session; recorded here verbatim). ARB validation of its architectural consequences is **pending** (see §3). This record authorises no implementation.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `f826d275b`.
**Recorded by:** Claude (Architecture Session B: Membership and Committee). The recorder records the act; it does not perform it.

---

## 1. Decision (verbatim)

> **Decision ID:** PO-ORG-MEM-01
> **Date:** 3 November 2026
> **Decision Authority:** Principal / PO
> **Status:** APPROVED
>
> ### Decision A — Organisation Ownership of Membership
>
> The Organisation owns its Membership domain.
>
> The Organisation's Constitution (statutes) establishes the competent organisational organs, offices and positions, and defines the authority and responsibilities under which Membership decisions are made.
>
> A competent organisational organ or authorised office holder makes Membership decisions within its constitutional competence or valid delegation.
>
> An organisational Membership Responsible Person coordinates and administers Membership operations. This person may be the General Secretary, Membership Secretary or another person appointed by the competent organisational body. Administrative responsibility alone does not confer authority to make Membership decisions.
>
> PublicDigit implements the authorised organisational rules, enforces the applicable Membership invariants, and records Membership facts and decision evidence. PublicDigit's technical ownership and platform access permissions do not constitute organisational authority.
>
> The Organisation's authority remains independent of the software used to administer Membership.
>
> ### Decision B — Technical Discovery Authorisation
>
> **Status: AUTHORISED**
>
> Proceed with DR-07a technical discovery covering M-1 to M-5.
>
> The technical owner is authorised to investigate and document:
>
> - M-1: Current Membership technical structure.
> - M-2: Legacy CommitteeAssociation model and its actual business meaning.
> - M-3: Governance and Membership responsibility boundaries.
> - M-4: Legacy model freeze options.
> - M-5: Migration, data, security and compatibility risks.
>
> ### Constraints
>
> This authorisation does not permit:
>
> - Changes to application code, database schemas or tests.
> - Changes to ratified Membership Architecture or its invariants.
> - Changes to ADRs, BDRs, Context Map or Platform Rules Index.
> - Approval of P11, CQ-10 or other outstanding architectural decisions.
> - Execution of a migration or legacy-model freeze.
> - Commits or pushes.
>
> The findings must be independently reviewed before they are used as the basis for further architectural decisions.
>
> **Final status: PO-ORG-MEM-01 APPROVED; DR-07a M-1–M-5 AUTHORISED.**

**Fidelity note (correction, 2026-11-06).** The first version of this record abbreviated Decision B's list and condensed the Constraints into one sentence while calling the whole quotation verbatim. Both are now restored word for word from the Principal's 3 November statement in the Session B conversation. The source is a conversation, not a repository file, so the restoration cannot be checked against a committed original. Decision A was verbatim in both versions.

**Interpretation boundary.** The business decision is approved. Its architectural interpretation is **not** settled by this record. In particular:
- the decision says "a competent organisational organ **or authorised office holder**", and both must be preserved wherever the decision is applied;
- it refers to "the Organisation's Constitution" but does not decide whether the Organisation Constitution is a separately owned domain concept, or where it is represented; that stays an ARB question (§3).

## 2. Related designation (stated by the Principal, 2026-11-03)

Nab, as sole developer of PublicDigit, holds the DR-07 **technical Membership owner** role (`docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §2 "Membership owner"; designation and safeguard in `docs/publicdigit/architecture/reviews/2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md` §4.4). This is a technical responsibility. It transfers no business ownership and confers no constitutional authority.

## 3. Consequences recorded, not decided

- **ARB validation pending** (preparation document §3.4):
  - whether the decision adds to ACT §11 or is its business reading;
  - where the Organisation Constitution is represented;
  - the minimum authority evidence;
  - conflicts with D-1…D-5, E-1…E-5, GOV-END B1.
- **Consistency:** consistent with ACT §11 (Governance owns competence, organs, authority) and RAT §8 (Membership stores authority evidence only). It does not reopen the ratified Membership architecture.
- **Interim rules stay interim:** D-4 (owner / admin, interim) is unchanged. This decision confirms that platform access is not organisational authority.

## 4. Traceability

- Preparation: `docs/publicdigit/architecture/reviews/2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md` (§3, §4).
- Discovery authorised by Decision B: `docs/publicdigit/architecture/reviews/2026-11-03-dr07a-membership-technical-discovery-m1-m5.md`.
- Ratified frame: `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md` §10–§11.

## 5. Linked records

- **Principal clarifications J-1, J-3, J-4 (2026-11-07):** `docs/publicdigit/reviews/2026-11-07-organisation-po-org-mem-01-principal-clarifications-j1-j3-j4-decision-record.md`. J-1 confirms that Decision A applies to Membership decisions only. J-3 confirms that Decision B, the Constraints and the final status line above are authentic and accurately reproduced. J-4 corrects who decided D-4 and in which capacity. §1 above is unchanged.
