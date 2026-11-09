# PBDIGIT-336: Organisations cannot set their own voter-eligibility rules by a recorded decision

**Type:** Strategic capability gap (not implementation) · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** the Principal accepted the recommendation to record Finding 1 of the business demand discovery ("yes, create a backlog item", 2026-11-09). Recorded by Claude.

| | |
|---|---|
| **Status** | `PROPOSED` · not authorised · no design, rule engine or code change implied |
| **Classification** | **observed in code** (static, HEAD `bad1202d0`); business rule from the Principal [PK] |
| **Does not duplicate** | `PBDIGIT-265` (single and bulk checks differ: a defect, OPEN) · `PBDIGIT-318` V2 (Election reads Membership tables: boundary remediation, OPEN) |
| **Analysis** | `docs/publicdigit/architecture/reviews/2026-11-09-strategic-ddd-reconciliation-and-participant-channel-mapping.md` §E.1 |

## Business need
In membership mode, the organisation's governance body **sets the eligibility rule and confirms the resulting voter list** (DD-11, [PK], `docs/publicdigit/reviews/2026-11-09-business-demand-discovery-record.md`). Organisation-specific rules must not become platform defaults (D-1b). Eligibility is evaluated by Election (ratified Membership act `2026-10-08-membership-architecture-ratified.md:110`) and is process-specific (ADR-002).

## Gap (FACT)
- The membership-mode criteria are fixed in code: status `active`, fees `paid`/`exempt`, type grants voting rights, not expired (`app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:113-119`; `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php:55-61`).
- Configurable today: voter source mode per organisation; `grants_voting_rights` per membership type; one per-election admission requirement (`OFFICER_IDENTITY_VERIFICATION`).
- Not representable: whether fees count, expiry, cut-off date, minimum tenure, electorate scope beyond regional posts, effect of suspension.
- No rule or admission setting records the governance decision that adopted it (`app/Contexts/Elections/Application/Commands/AdmissionPolicyChange.php:15-19`).
- The voter-list confirmation is a person-level "approved by" (`app/Http/Controllers/VoterlistController.php:40-54`), not a recorded decision.

## Scope when authorised (strategic first)
1. Principal decision PD-C: which criteria are universal platform constraints and which are organisation policy (proposed split in the analysis §E.1).
2. Model the rule as a **decided input** to Election (who adopts it, the decision reference, when it is fixed for an election) and the list confirmation as a governance act.
3. Only then tactical design. Membership invariants are not reopened.

## Out of scope
A generic rule engine; making every criterion configurable; any change before the strategic decisions; the defects in `PBDIGIT-265` and `PBDIGIT-318`.

## Depends on
Phase 3 (which body adopts rules; D-10, HG-1 statutes) · FeePaid ownership (whether fees count, and from which context).
