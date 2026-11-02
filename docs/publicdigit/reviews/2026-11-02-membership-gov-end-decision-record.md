# Membership GOV-END: who may end a membership? Decision record (interim)

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect), on the PO's instruction
**Status:** **DECIDED by the PO, acting as the single owner for Governance (2026-11-02): option B1, interim.** G-E1…G-E3 are OPEN.
**Also called:** G-1 in the Slice 2 plan (`docs/plans/20261011-0010-membership-slice2-command-rules-plan.md` §4.4); GOV-END in the roadmap (`20261016_2219_…_completion_plan.md:14`).
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.

## 1. Background (verified 2026-11-02, HEAD `d36b18ee1`)

**The new code refuses every End for every actor:**
- `InterimRoleAuthority.php:27, :41`, rule `interim:unresolved:G-1`;
- this blocks Slice 2b (roadmap W5, AC-13).

**No legacy ending rule exists to mirror** (Slice 2 plan F9, re-verified):
- `MembershipPolicy` has no "end membership" ability;
- `Member::endMembership()` has no caller;
- `TerminateMembershipHandler` is container-bound, but no route reaches it.

**Settled inputs:**
- **D-3:** the closed list of ending acts (resignation, exclusion, death; `legacy` for migration only).
- **D-3-L, OPEN:** the legal procedure review (notice, hearing, appeal for exclusion).
- **BR-7 / D-4:** Membership stores authority evidence; Governance owns the rule; the interim adapter introduces no policy of its own.
- **D-5:** no future-dated endings.

**The three acts differ by nature:**

| Act | Who makes it | What the organisation does |
|---|---|---|
| Resignation | the member's declaration | records it |
| Exclusion | the competent organ's decision | records it |
| Death | an attested fact | records it |

## 2. Decision (PO, 2026-11-02)

**PO words:** "GOV-END: B1, record it and commit".

> "GOV-END, interim (until Governance's AuthorityCheck implementation meets DS-4):
> (1) **Resignation** and **death** may be recorded by an organisation owner or admin. Each End must carry evidence: for resignation, a reference to the member's declaration; for death, a reference to the attested evidence.
> (2) **Exclusion** is refused for every actor until Governance provides the competence check for the excluding organ.
> (3) `legacy` ends remain migration-only (attested).
> (4) Every check records AuthorityEvidence with `ruleRef` naming this decision and `competenceVerified = false`.
> This interim rule is replaced by the Governance-owned implementation. It is not a Membership policy."

**Reasons (brief §5):**
- It unblocks the two acts where the organisation only records what the member, or reality, has already done.
- The disciplinary act (exclusion) fails closed until the competent organ can be checked: BR-7 (Governance owns the rule) and D-3-L (procedure under review).
- No regression: nobody can end a membership today, in either the new or the legacy code.

**Relation to D-4:** D-4 approved mirroring *existing* policy only. Because no ending policy existed, B1 is an **explicit interim Governance decision**, recorded here. It is not an inferred mirror.

## 3. Consequences (recorded, not authorised)

- **Port shape (Slice 2b):** today the port is `AuthorityCheckPort::check(tenant, actor, MembershipAct)`, and `MembershipAct` has only `Admit` and `End`. B1 needs the **End kind** and the **evidence reference** at the authority check. Slice 2b must therefore extend the act passed to the port. The design is part of 2b.
- **Evidence reference on End:** whether the End fact already carries a declaration or evidence reference must be checked when 2b is specified. If it does not, carrying one is part of 2b. Not verified here.
- **`ruleRef`:** something like `interim:GOV-END-B1:2026-11-02`, with `policyVersion` and `competenceVerified = false`. Exact values are fixed in 2b.
- **Exclusion:** refused with a distinct reason, so callers can tell "not permitted yet" apart from "not authorised". The name is fixed in 2b.
- **Roadmap:** W5 / Slice 2b is no longer blocked by GOV-END (annotation R-11). It is still gated by W2 (isolated PostgreSQL; PBDIGIT-264 Part 2) and its own authorisation.

## 4. Not decided (OPEN)

| # | Question | Recommendation (PROPOSED) |
|---|---|---|
| G-E1 | Should a dedicated registrar role replace owner/admin as the recording role? | keep owner/admin for now, mirroring admission (D-4) |
| G-E2 | May a member resign online themselves, with their authenticated request as the declaration? | yes, later (Slice 8); members without an account resign through the organisation (BR-5) |
| G-E3 | Effective date of a resignation: receipt, or a statutory notice date? | leave it to D-3-L; backdating is allowed within the invariants (D-5) |

Also still OPEN: **exclusion authority** (DS-4, XD-4, DR-07a; D-3-L), **CQ-3** (who may Annul, Void, Correct) and **CQ-9** (appeals).

## 5. Not changed or authorised

- No ratified text changes; D-3 and D-4 stand.
- **No code change.** `InterimRoleAuthority` still denies every End until Slice 2b is authorised and implemented.

## 6. Traceability

- Brief: the GOV-END brief of 2026-11-02 (conversation; summarised in §1–§4 here).
- Slice 2 plan §2 F9, §4.4, AC-5, AC-13.
- D-3, D-4, D-5 (`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:292-295`).
- Roadmap BR-7, G-A6, W5 (annotation R-11).
- Code: `app/Contexts/Membership/Infrastructure/Organisational/InterimRoleAuthority.php`; `app/Policies/MembershipPolicy.php`.
