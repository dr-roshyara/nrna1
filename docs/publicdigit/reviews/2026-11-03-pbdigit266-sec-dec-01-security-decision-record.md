# SEC-DEC-01: PBDIGIT-266 R-1 and R-2 security decisions, decision record

**Status:** **DECIDED by the Principal / PO, 3 November 2026; explicitly confirmed 6 November 2026** (§1). **No implementation, test or production check is authorised by this record** (§2).

**Confirmation of the act (Principal / PO, 2026-11-06, verbatim):**

> I confirm the full SEC-DEC-01 decision table (P1–P5) as recorded in the decision record.
>
> I confirm that my earlier acceptance of the recommendation was intended to approve the complete decision table, including the interim owner/admin restriction for committee-association application reviews.
>
> The qualifications and restrictions remain binding:
>
> 1. P5(a) is not authorised until the isolated database is assigned to PBDIGIT-266 and the ARB confirms that it satisfies ARB-SEC-01.
> 2. P5(b) requires separate approval.
> 3. P5(c) requires a separate decision with Operations.
> 4. R-2 persistence repair and R-1 DELETE import repair remain unauthorised.
> 5. No implementation, testing or production checks are authorised by this confirmation.

This confirmation replaces the recorder's earlier interpretation of the 3 November acceptance. All decisions in §1 stand as recorded, including the ordering constraints and the items marked NOT YET or NOT AUTHORISED.

*History (kept):* on 3 November the Principal accepted "the recommendation", which set out a table titled "Please confirm or change the following". The recorder read that acceptance as confirming the table and flagged the reading for confirmation (Principal review, 2026-11-06).

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `f826d275b`.
**Decision package:** `docs/publicdigit/architecture/reviews/2026-11-03-sec-dec-01-principal-security-decision-package.md`.
**Evidence:**
- `docs/publicdigit/architecture/reviews/2026-11-03-ir-sec-01-independent-review.md` (IR-SEC-01, "CONFIRMED WITH CORRECTIONS"). **Nature of its independence:** a Claude model (Sonnet) in a fresh context, commissioned by the session that wrote the reports under review; it read the code before the reports. It is **not** a human or non-Claude review. Where a human or non-Claude review is required (Principal, 2026-11-06), IR-SEC-01 does not satisfy it;
- `docs/publicdigit/architecture/reviews/2026-11-03-po-security-triage-pbdigit-266-r1-r2.md`;
- `docs/publicdigit/backlog/PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md`.

All findings behind these decisions are static. No request, test or production check has been run.

---

## 1. Decisions

| ID | Decision | Choice | Qualification |
|---|---|---|---|
| **P1** | Prioritise R-1 (unauthorised committee-member POST; latent DELETE) | **YES** | — |
| **P2** | Prioritise R-2 (application review without authority; persistence currently broken) | **YES** | priority authorises no persistence repair |
| **P3-R1** | Interim guard on the Governance committee-member endpoints | **BOTH POST and DELETE**, using the existing `manageCommittee` policy (owner / admin) | an **interim platform-access restriction**, not constitutional authority (PO-ORG-MEM-01) |
| **P3-R2** | Application-review protection | **R2-a**: reviewer must belong to the organisation; interim owner / admin restriction; no self-review; checks before any persistence change | if R2-a cannot be implemented and verified before persistence work: **deliberate, tested feature-off**; never reliance on the current persistence failure |
| **P3-R2 (owner / admin)** | May owner / admin review committee-association applications? | **YES, as an explicitly interim platform restriction** | a new interim decision; **not** an extension of D-4. It does not establish that owner / admin has constitutional authority over committee membership; to be replaced when permanent Governance authority is established |
| **P5(a)** | Authorise RED security tests now | **NOT YET** | first verify isolated-database readiness against ARB-SEC-01 (§3) |
| **P5(b)** | Authorise containment code | **NOT YET** | separate approval after P5(a) |
| **P5(c)** | Production exposure checks | **to be decided separately**, with Operations | read-only only, if approved |
| — | R-2 persistence repair | **NOT AUTHORISED** | — |
| — | R-1 DELETE import repair (`RemoveCommitteeMemberHandler.php:10`) | **NOT AUTHORISED** | — |
| **P4** | Permanent authority source | not decided; evidence to be commissioned (statutes; M-2 answers; ARB evidence-contract questions) | Session B consumes Organisation authority decisions from Session A; it does not invent them |

**Ordering constraints (binding with these decisions):**
1. No R-2 persistence repair may be implemented or deployed before the R2-a protection has been implemented and verified, or review has been deliberately disabled.
2. No repair of the R-1 DELETE import before the `destroy` guard is in place.

## 2. What is not authorised

Code, route, middleware, policy, schema, test, permission or configuration changes; test execution; production log or data access; persistence repairs; any permanent authority rule; changes to D-4, ratified Membership architecture, ADRs, BDRs or the Platform Rules Index.

## 3. Isolated-database readiness for P5(a) (read-only check, 2026-11-03)

| Question | Finding |
|---|---|
| Is the PBDIGIT-324 container isolated from development and production? | [F] Yes, by design: a throwaway PostgreSQL cluster on 127.0.0.1 with a random port and password, removed on exit (`scripts/testing/membership-pg.sh:3-14`). The bootstrap refuses unless the connection reports the expected cluster identity and database `membership_pg_isolated` (`tests/bootstrap-membership-pg.php:12-15, :40`). It is listed as resettable in `tests/Support/TestDatabaseSafety.php` |
| Can it run Governance / committee feature tests? | [OQ] Not as configured: its suite covers only `tests/Feature/Contexts/Membership/Organisational` (`phpunit.membership-pg.xml:21-23`). Tests there run `migrate:fresh` (`MembershipPostgresTestCase.php:15`), but whether all committee, Governance and organisation tables build on it is **unverified**. A separate test configuration would be needed (a test-infrastructure change, itself requiring authorisation) |
| Can tests reset and recreate data safely? | [F] for the container (throwaway cluster); [OQ] for the additional suites until configured |
| Does it satisfy ARB-SEC-01? | [F] **Not yet.** ARB-SEC-01 requires "a dedicated test DB assigned to PBDIGIT-266 (the PO creates it; separate authorisation)" (`PBDIGIT-266…:40-42`). PBDIGIT-324 states it unblocks the PBDIGIT-266 gate "each separately authorised" (`PBDIGIT-324…:11`) and lists PBDIGIT-266 security tests as outside its own scope (`:33`) |
| If not, is O-4 required? | [OQ] For the ARB. The PBDIGIT-324 container is a candidate; the platform-wide isolated database (O-4) remains open |

**Next decision needed before P5(a):**
- the PO assigns an isolated database to PBDIGIT-266 (e.g. the PBDIGIT-324 container with a PBDIGIT-266 test configuration);
- the ARB confirms that the assignment satisfies ARB-SEC-01.

## 4. Verification limit of R2-a (recorded)

With persistence repair not authorised, an owner / admin's legitimate review still fails. Containment tests can verify the **refusals** (non-member, ordinary member, self-review). They cannot verify the positive path. "R2-a verified" therefore means "refusals verified", not "review works". No UI depends on review.

## 5. Out of scope, handed on

PBDIGIT-266 item 1 (election approval; the story's "most severe") and item 4 (finance routes) are outside Architecture Session B. They need separate investigation and prioritisation.

## 6. Traceability

SEC-DEC-01 §3 (P1–P5), §7 (sequence); IR-SEC-01 §A, §E.3, §G; PBDIGIT-266 (ARB-SEC-01, items 2 and 3); PO-ORG-MEM-01 (`2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`).
