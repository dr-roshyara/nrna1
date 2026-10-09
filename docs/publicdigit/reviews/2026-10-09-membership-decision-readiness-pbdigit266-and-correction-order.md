# Membership Decision Readiness: Identifier Collision, Correction-before-Cutover, PBDIGIT-266 Packet and Test Specification, Consolidated Roadmap

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2301_implementation.md`
**Status:** DECISION-READINESS (research / review). **Not committed** (commission: no commits). **No test or migration run, no HTTP request sent, no database created, no code, test, route or configuration changed.** It authorises and ratifies nothing.

**Labels (this commission):**
- **ADOPTED** — formally ratified;
- **OBSERVED** — supported by current source;
- **CD** — confirmed defect;
- **HYPOTHESIS**;
- **BOUNDED** — bounded-model result;
- **P-ARG** — informal argument;
- **P-PROOF** — proof with an explicit derivation;
- **BIZ** — business decision required;
- **UNKNOWN**.

**Builds on (not repeated):**
- the audit `2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md`, with its errata E-1 … E-8;
- `2026-10-09-membership-audit-challenge-and-pbdigit266-test-plan.md`;
- `PBDIGIT-266`.

## 0. Disagreements (stated first)

| # | Point | Position |
|---|---|---|
| X-1 | Deliverables 3–5 largely exist (the test plan, the audit roadmap) | **Deltas only**, except the roadmap: §6 **consolidates and supersedes** audit D.2 and its errata, so that two roadmaps do not drift. |
| X-2 | B: "rename Slice 2 decisions S2-D1 … S2-D6" | **The collision is systemic, not local.** `D-1` occurs in **589** Markdown files across the repository, each using document-local D-numbering. Within the Membership corpus alone it has **three** meanings (§2). Renaming one series fixes this instance, not the class. The proposal adds a citation rule. |
| X-3 | A: "independent principal review" | Run again by an **independent agent** on the 266 plan; the audit was already challenged. **The two reviews disagreed on one point** (family 2). I resolved it by direct reading (§4, D-2). |

## 1. Verdict and repository state

**Principal-review recommendation: ACCEPT WITH CONDITIONS** for the audit with its errata and for the 266 plan with the corrections in §4. The decision is the Principal Reviewer's.

**Repository [OBSERVED]:**
- HEAD `e8b5b62d5` on `knowelegeos-modelling`. 28 tracked files are modified by other sessions.
- A **concurrent session is active** ("Mission 2", `PBDIGIT-211`). Its notes record test-database setup work; `CREATE DATABASE` was denied.
- **Uncommitted test files** in the working tree (`tests/Support/ElectionScenarioFactory.php`, `tests/Feature/Election/*`) belong to other sessions. The future 266 fixtures depend on them.
- `bootstrap/cache/config.php` is **absent** (relevant to §5.5).

## 2. Decision register: identifier collision (B)

**Meanings of "D-1" in the Membership and context corpus [OBSERVED]:**

| Meaning | Status | Where |
|---|---|---|
| **INV-FID** (FactIds unique per Member) | **ADOPTED** (PR instruction `…0812…`) | `2026-10-09-membership-slice1a-contract.md:4`; `…slice0-tdd-implementation-review.md` (8×); `…organisational-participation-domain-research.md` (12×); `PBDIGIT-258`, `PBDIGIT-259`; `docs/plans/20261009-0800-…` |
| **Admission basis** (BD-CONSENT / LQ-1) | **BIZ** | `…slice1b-acceptance-review-and-slice2-decision-closure.md` (12×); `…pbdigit264-plan-…` (24×); `…theory-synthesis-…-audit.md` (11×); `…committee-responsibility-…` (4×); `…audit-challenge-…` (2×); `.claude/CONTEXT-publicdigit.md:27` |
| **"Do engineering/doc items get PBDIGIT stories?"** | another session's open question | `.claude/CONTEXT-publicdigit.md:177` |

**Proposal (BIZ, PO) — not approved:**
1. Keep the adopted Slice 1a meaning: **D-1 = INV-FID** (better cited as **INV-FID** itself).
2. Rename the Slice 2 decisions to **S2-D1 … S2-D6**.
3. **Citation rule:** any cross-document reference to a document-local decision ID must be qualified, in the form `<document-or-slice>:D-n`, or use the stable invariant or business ID (INV-…, BD-…, LQ-…, CQ-…).

**Reference-migration checklist (to execute only after PO approval; documentation edits only):**
- [ ] decision registers: `2026-10-08-membership-final-decision-register.md`. No "D-n" was found there, so it needs no change; verify.
- [ ] contracts: Slice 1a (keep; add "= INV-FID"); Slice 1b (none found).
- [ ] plans: `docs/plans/20261009-*` (Slice 0 plan: qualify).
- [ ] review reports: the six Slice-2-meaning files above, which get `S2-` prefixes. Historical text is not rewritten; each gets **an errata line pointing to the rename**, as done for the audit.
- [ ] backlog: `PBDIGIT-258`, `259` (qualify), `264`, `265`, `266` (check).
- [ ] context and session records: `CONTEXT-publicdigit.md:27` (Slice 2) and `:177` (another session's D-1; **coordinate with its owner**).
- [ ] ADRs: none found in the Membership scope; verify with `grep -rn "\bD-[1-6]\b" docs/adr | grep -i member`.
- [ ] Verification: after the edits, `grep -rnE "\bD-[1-6]\b"` over the Membership corpus returns only qualified or INV-FID-annotated occurrences.

## 3. Correction-before-cutover (C)

**What the current kernel can remedy.** Probed through the kernel bridge (pure in-memory, real `decideAdmit` / `decideEnd`; command in §7):

| Error class | Kernel behaviour | Remedy today |
|---|---|---|
| End date too late / too early (move an End) | second End refused: `PeriodAlreadyEnded` (both directions) [OBSERVED] | **none** |
| Admission date too late (backdate an Admit) | `OverlappingMembershipPeriods`, also after an End [OBSERVED] | **none** |
| Admission date too early | cannot shorten a lifetime from the left [P-ARG: no fact type affects the start] | **none** |
| Wrong person / admission should not exist | `End` at start refused: `EndNotAfterPeriodStart` [OBSERVED] | only "End at start + 1 day". **At least one day of false membership remains permanently** |
| Ended too early, membership should continue | same-day re-admission **accepted** [OBSERVED] | workaround with **semantic damage**: two periods instead of one; a false End (with its kind and authority) stays in history; `currentAdmission` changes |
| Wrong initial term, wrong End kind, wrong provenance or authority evidence | no fact type [OBSERVED] | **none** |
| A fact that should be *ended prospectively* | End | **yes** (it is not a correction) |

**Answers to C1 – C6:**
1. **Remediable now:** only prospective Ends and the lossy re-admission workaround.
2. **Unremediable:** every retroactive change to dates, person, term, kind or evidence.
3. **A bad fact before correction exists stays in the current resolution permanently.** Its effects:
   - via the reverse projection (C-5), legacy `members` and Full-mode eligibility;
   - via derived predicates, Governance (seat eligibility) and Finance (charge targets).
   
   `knownAt` stays intact, because history is not rewritten. *Current* truth is wrong with no lawful repair. The only escape would be a direct database edit, which breaks the append-only contract and the audit.
4. **Corrections must preserve:**
   - the erroneous fact itself (never deleted);
   - its provenance and authority evidence;
   - the correcting act's own provenance, authority (CQ-3) and decided / recorded instants;
   - valid-time meaning under the ratified algebra (Annul for revoked decisions; Void, a distinct legal operation; Correct per policy; Correct(annulled) inert; Correct(voided) refused; N-3 refusals);
   - `knownAt` reconstruction at every τ.
5. **Minimum capability before live writes or migration** (architectural recommendation, HYPOTHESIS until ratified):
   - (a) **Annul** of Admit / End, plus **Correct(effectiveAt)** of Admit / End, plus **Void** for data-entry errors;
   - (b) BD-REV-ER decided (act N-4: "needed before the reversal slice"; recommended default: refuse);
   - (c) CQ-3 (who may correct or annul) and CQ-9 (appeal body) decided, or an explicitly interim authority;
   - (d) evidence: exhaustive bounded verification against an independent oracle (as in Slice 0); the kernel bridge reclassifying the UNCHK model histories; mutation measurement; persistence round-trips.
6. **Compensating controls if the PO rejects correction-before-cutover** (all of them, formally accepted as residual risk):
   - (i) live writes limited to a **pilot organisation**;
   - (ii) **four-eyes preview** of every Admit / End before append;
   - (iii) migration dry run with a **signed exception report**; only clean rows migrate;
   - (iv) **legacy stays authoritative** (the cutover flag stays off) until correction exists, with reverse projection off;
   - (v) a documented manual remediation queue drained by the first correction slice;
   - (vi) **no direct database edits**.

**Recommended ordering (architectural recommendation, BIZ to ratify):**

```text
Slice 2 (no live entry)  →  Correction slice(s)  →  Cutover machinery  →  Migration dry-run / parity  →  live HTTP / UI
```

## 4. PBDIGIT-266: corrections to the test plan (E) after independent review

**Agreement:**
- **Family 1:** the approve / reject guard is verified; the bypass analysis is clean (`Transition::manual` is never system-triggered).
- **Family 3:** confirmed.
- **Family 4:** confirmed, and worse for Outcome.
- **Family 5:** confirmed. Debug routes are registered in **all environments** (no environment guard).

**The disagreement and its resolution (D-2):**
- **The reviewer:** the committee review controller "uses route-model binding and never reads the attribute; any verified user of any org can approve".
- **Direct reading:** `CommitteeMembershipApplicationController.php:72` `$organisation = $request->attributes->get('organisation');` (and `:31` in `apply`). The file is unmodified since 2026-05-14. The attribute is set **only** by `EnsureOrganisationMember.php:146`, which is not on this route group.
- **Resolved reading:**
  - (a) both `apply` and `review` most likely **fail with 500** today (null organisation);
  - (b) the route has **no organisation-membership check**;
  - (c) a naive fix of (a) via route binding would expose a **cross-tenant review**.
- So the 500 is a route defect, **not** a refutation. G2 tests must still assert denial with no state change.

**New findings from the independent review [OBSERVED]:**
- **N-1:** the slug form of the approve route returns 500 (`findOrFail(slug)` on a uuid primary key, PostgreSQL 22P02) **before** the guard runs.
- **N-2:** `GET /elections/{slug}/auth-test` (`electionRoutes.php:734-760`, `auth, verified` only) returns any election's state and organisation id to any verified user. `debug-tenant` also returns `election_org_id` across tenants.
- **N-3:** `pending()` uses `withoutGlobalScopes()`, which also returns **soft-deleted** elections; `Election` has no `$hidden`.
- **N-4:** a guest with an unknown `X-Tenant-Id` gets 404 vs 400, an **organisation-existence oracle** for unauthenticated callers.
- **N-5:** the Outcome namespace mismatch can **fatally abort a PHPUnit process** on a second autoload attempt.
- **N-6:** the `BelongsToTenant` fallback slug `'platform'` ≠ the test organisation slug `publicdigit`, so the test `organisation_id` may be NULL and cached statically.

**Test-design corrections (apply to plan §4):**

| Plan row(s) | Correction |
|---|---|
| 1.1, 1.3, 1.4 | use the election **UUID** in the URL. Add **1.7** "slug route returns 500 (route defect N-1)". Assert `state` + transition rows, not `updated_at`. Expect **302 + `assertSessionHas('error')`** |
| fixtures | build elections via `ElectionScenarioFactory` (state write barrier, `Election.php:948+`). **Coordinate:** this file has uncommitted changes from another session |
| 1.5 | assert the props exclude other-tenant **and trashed** elections (N-3) |
| 2.x | state the repository wiring (real Eloquent adapters, not the in-memory doubles the existing test binds). `action` is upper-case `APPROVE` / `REJECT` |
| 2.2 | **G2-grounded.** Expected: denial and no state change. Today's expected *observed* result is 500, classified "route defect, guard unreachable" (not REFUTE). Add **2.6** "verified user with no organisation role" |
| 2.4 | keep as route health: once a reviewer is authorised (P2), the path must not 500 |
| guest / unverified rows (1.6, 2.5, 3.4) | specify the transport: `postJson` → 401 / 403; `post` → 302 to login / `verification.notice`. 3.4 observed 500 = route defect |
| family 3 | add **3.7** "member of A, header A, **B's committee id** → 404, no row" (tests repository tenant scoping) |
| 5.1 | assert the **session value regardless of status** (the middleware writes before the controller). Clear the container binding `current.organisation_id` between requests |
| chain 5.1 → 4.1 | one test, two requests; **`TenantContext::clear()` between them** (a static value would otherwise give a false CONFIRM). The session models the browser cookie |
| mail | `Notification::fake()` + `assertSentOnDemand` (finance uses an on-demand Notification; `Mail::fake()` would see nothing) |
| 4.3 | `#[RunInSeparateProcess]` (N-5) |
| 4.x | seed the `income` / `outcome` session drafts with `withSession`. Allow `organisation_id` NULL (N-6) |
| 5.3 (new) | `election.auth-test` disclosure (N-2), under P5 |
| 1.8 (new, low) | guest organisation-existence oracle (N-4), under P5 |

**Status-code semantics:**
- **Security result:** a G-grounded denial or violation (state, props, session, notification).
- **Policy result:** a P-dependent behaviour, recorded.
- **Route-health defect:** an unintended 500 / 400. A 400 / 404 from tenant resolution is a tenant result.
- **A 500 never refutes.**

## 5. PO decision packet P1 – P5 (D)

Every "default" below is a **recommendation**.

| ID | Question | Owning context / decider | Observed now | Precedent | Options (trade-off) | Recommended default | Approval needed | Untestable until decided |
|---|---|---|---|---|---|---|---|---|
| **P1** | Who may see the platform-wide pending-election list? | Platform administration (Election lifecycle approval) / PO | any verified user sees all organisations' pending **and trashed** elections (`AdminElectionController::pending`) | guarded twin `routes/platform.php:7,15` (`platform_admin`); approve / reject require `platform_admin` (`ElectionConstitution:32-44`) | (a) platform admins only: consistent, minimal disclosure; (b) also the organisation's own officers, but for their own elections only | **(a)**, and remove or guard the duplicate route | PO | whether organisation officers may see *their own* pending election here |
| **P2** | Who reviews committee-membership applications? Is self-approval forbidden? | Governance (committee participation) / PO + Governance owner | no reviewer check; the existing test has the applicant approve their own application; the route likely 500s | `MembershipPolicy` approve / reject = owner / admin (organisation membership applications); adopted separation precedent: suspension actor ≠ removal actor (2026-09-16 decision) | (a) owner / admin of the organisation; (b) the committee's chair (organ authority, needs CQ-3); (c) a parent committee | **interim (a) + self-approval forbidden**; long-term per CQ-3 | PO + Governance owner | 2.1, 2.3 |
| **P3** | Which role or organ may add / remove committee members? | Governance / PO + Governance owner | any organisation member (API); owner / admin on the other controller (`OrganisationPolicy::manageCommittee`) | `manageCommittee` = owner / admin (live); `ApprovalRequirementPolicy` PARENT_COMMITTEE (dormant) | (a) owner / admin: consistent with the live UI; (b) an organ decision (CQ-2 / CQ-3) | **interim (a)**, aligned with `manageCommittee` | PO + Governance owner | 3.2 |
| **P4** | Who may record finance income / outcome? Must finance be tenant-scoped? | Finance / PO + Finance owner | any verified user; organisation from session or fallback; on-demand mail to a fixed address | fee recording = owner / admin (`MemberController`, `MembershipFeeController`); tenant isolation is a platform principle | (a) owner / admin of the organisation, explicit tenant; (b) an FC role (CQ-4) | **tenant scoping is not optional** (it is G2, not policy). Role: **interim (a)**, then CQ-4. Is the legacy module retired or kept? | PO + Finance owner | 4.2 |
| **P5** | Is `X-Tenant-Id` a test / API mechanism only? Should the debug routes exist in production? | Platform security / PO + Security | `tenant` middleware trusts the header (session switch); `debug-tenant` and `auth-test` are registered everywhere | `IdentifyTenantFromHeader` (API) **does** verify membership; the multi-tenancy guide expects 403 cross-tenant | (a) header only with a membership check (as in the API); (b) header only in the testing environment | **(a) + debug routes removed (or `local` only)** | PO + Security | 5.2, 5.3, 1.8 |

## 6. Consolidated roadmap (supersedes audit D.2 + errata) (F)

Types: R = research · D = decision · T = test-only · I = implementation · M = migration · P = production readiness.

| # | Item | Type | Owner | Prerequisites | Deliverable / acceptance evidence | Blockers |
|---|---|---|---|---|---|---|
| 1 | Test-database isolation and coordination | P / ops | Engineering + PO (CREATEDB) | — | a dedicated test DB or an exclusive window; `bootstrap/cache/config.php` absent at run time; DB name asserted | concurrent session; privileges |
| 2 | Authorise the `PBDIGIT-266` investigation (tests only) | D | PO | — | a recorded authorisation | — |
| 3 | Execute `PBDIGIT-266` G-grounded + route-health tests (§4) | T | Engineering | 1, 2 | each row CONFIRMED / REFUTED / route defect; no fix | — |
| 4 | P1 – P5 decisions | D | PO (+ Governance, Finance, Security) | — | recorded decisions; then the P-dependent rows are classified | — |
| 5 | Fix stories for confirmed 266 rows | I | Engineering | 3, 4 | RED → GREEN per route | — |
| 6 | S2-D renaming + citation rule (§2) | D → doc | PO | — | approved; checklist executed | — |
| 7 | Formal Slice 1b acceptance act; DV-1 countersignature | D | PR; PO | — | repository records | — |
| 8 | `PBDIGIT-264` (F-1 → F-5 N1 → F-3 → F-4b → F-2) | I | Engineering after PO authorisation | 1, 7 (recommended) | per plan §9 – §10; a fresh PR decision | authorisation |
| 9 | TG-1 … TG-5 | T | Engineering after authorisation | 1 | the mutants killed; MSI re-measured | authorisation |
| 10 | Vocabulary K-1 … K-8, CQ-1, CQ-2 / EM-OPEN-049 / 066 | D | PO / ARB, Election owner | — | acts | — |
| 11 | CQ-3, S2-D1 (basis), S2-D3 (End kinds), S2-D5 (dates), BD-REV-ER, CQ-9 | D | PO + Legal + Governance | 10 | ratified decisions | — |
| 12 | Slice 2 contract incl. G-AUTH-1, T-EO | D | PR | 8, 11 | approved contract | — |
| 13 | Slice 2 implementation (no live entry) | I | Engineering | 12 | contract + race tests; T-EO | — |
| 14 | **Correction slice(s)** (Annul, Correct, Void) | I | Engineering | 11 (BD-REV-ER, CQ-3, CQ-9), 13 | bounded exhaustive vs oracle; kernel bridge reclassifies UNCHK; persistence round-trips | the **correction-before-cutover decision (§3)** |
| 15 | `PBDIGIT-265` | D / T | Election owner | — | one shared test matrix | — |
| 16 | C-2 column map | D | Finance owner | — | a signed map | — |
| 17 | Cutover machinery C-0 … C-5, C-8 | I | Engineering | 13, **14**, 15, 16 | fitness test; DB guard incl. mass updates; rebuild = incremental | — |
| 18 | Dry runs, parity, rollback (C-6, C-7); production engine (`PBDIGIT-261`); rollout (C-10) | M / P | Engineering + Ops + PO | 17 | parity report; rehearsed rollback | production access |
| 19 | HTTP / UI | I | Engineering | 18 (per organisation), 5 (route security) | end-to-end tests | — |
| 20 | Governance contract; Finance ledger contract (design H) | D | Governance / Finance owners | 10, 11; CQ-4 … 10; BQ-P10 / 11 | approved contracts | — |
| ∥ | `PBDIGIT-261`, `262`, `263` (opt-out under N1) | I | per authorisation | — | per story | — |

**Independence:** `264` ⟂ `266` (no shared code path). 8 and 3 can run in either order once item 1 holds.

## 7. Formal methods, statistics, ML (G) and the evidence used here

- **Kernel probes (§3), reproducible, in-memory, no database.** Pipe the histories JSON into `php docs/publicdigit/membership/architecture/models/kernel_admissibility.php 30`. Histories: End moved later or earlier; Admit backdated; End too early + same-day re-admission; End at start; re-admission before an ended period. Results as in §3.
  - These are **example probes (X)**, not enumeration.
  - The unremediability claims for whole classes are **P-ARG**: no fact type in Slice 0 targets an existing fact; `Member::decide*` creates only new Admit / End.
- **Bounded results** (B.2 of the audit) keep their boundaries. They are not probabilities and do not prove production behaviour.
- **Statistics:** none. There is no Membership dataset with a sampling frame.
- **ML:** advisory duplicate triage only (audit D.4). Never in authority, identity equivalence, appointment or entitlement.

## 8. TODO (priority · owner · dependency · acceptance)

- **P0 · PO + Eng** · — · test-DB isolation agreed; config cache absent at run time.
- **P0 · PO** · — · `PBDIGIT-266` test-only authorisation recorded.
- **P0 · PO** · — · P1 – P5 decided (defaults in §5 or alternatives).
- **P0 · PO** · — · S2-D renaming + citation rule approved (§2).
- **P0 · PO + PR** · — · correction-before-cutover decided (§3), or compensating controls formally accepted.
- **P1 · PR / PO** · — · Slice 1b acceptance act; DV-1 countersignature.
- **P1 · Eng** · after authorisation · `PBDIGIT-264`; TG-1 … TG-5.
- **P1 · PO + Legal + Governance** · 10 · CQ-3, S2-D1 / D3 / D5, BD-REV-ER, CQ-9.
- **P2 · Election owner** · — · `PBDIGIT-265` matrix.
- **P2 · Eng** · 12 – 14 · Slice 2, then the correction slice.
- **P3 · Eng + Ops** · 17 – 19 · cutover, migration, HTTP / UI.

## 9. Sources inspected / files changed

**Inspected:**
- the documents listed in the commission;
- `CommitteeMembershipApplicationController.php`, `EnsureOrganisationMember.php`, `TenantContext.php`, `electionRoutes.php`, `ElectionConstitution.php`, `ConstitutionalTransitionGuard.php`;
- the Slice 0 kernel via the bridge;
- the ratification act §4 / N-3 / N-4;
- the D-1 occurrences;
- `bootstrap/cache/`.

**Changed:** this document only (new, **uncommitted**).

**No tests, requests, migrations, database operations, code changes, commits or authorisations occurred under this commission.**


## 10. Human ARB decisions applied (2026-10-09; record `2026-10-09-membership-human-arb-decision-record.md`)

- **ARB-MEM-01 (mandatory gate):**
  - Roadmap #14 (correction slices) is a **hard prerequisite** of #17 (cutover machinery activation), #18 (migration / rollout) and #19 (live HTTP / UI writes).
  - §3 C6 (compensating controls instead of correction) is **closed**.
  - §3's ordering is no longer a recommendation; it is **ratified**.
- **ARB-SEC-01:** item #3 runs only after item #1 passes. The gate check of 2026-10-09 **FAILED** (shared `nrna_test` only; no dedicated DB assigned). **No tests run.**
- **ARB-DEC-01:** §2's `S2-D` prefix stays a **proposal**; Slice 1a "D-1" = INV-FID is preserved. No references were renamed.
- **ARB-MEM-02:** item #7. Slice 1b stays **conditionally accepted**; a formal act is pending.
- **ARB-TEST-01:** item #9 = `PBDIGIT-267` (test-only, not authorised, not executed).

## 11. Dependency order per the Human ARB commission `…20261009_2315…` (supersedes the §6 order where they differ)

```text
1. Authority + correction-policy decisions   (#10, #11: CQ-1/2/3, CQ-9, BD-REV-ER, Slice 2 pack D-1/D-3/D-5)
2. Slice 2 contract approved                 (#12, incl. G-AUTH-1, T-EO)
3. Correction / annulment implemented + verified   (#14; prerequisites 1 + 2)   ← ARB-MEM-01 gate
   (Slice 2 implementation #13 also depends only on 2; #13 and #14 may proceed in parallel)
4. Cutover machinery + migration dry runs / parity  (#17, #18; prerequisites 3, #13, #15 PBDIGIT-265, #16 C-2 map)
5. Live HTTP / UI                            (#19; prerequisites 4 and the security gates: confirmed PBDIGIT-266 rows remediated)
```

- **Change vs §6:** #14 no longer waits for #13 (§6 had "#14 ← 11, 13"). Both need #12.
- **Independent:** `PBDIGIT-264` ⟂ `PBDIGIT-266` (separate workstreams).
- **Not run here:** TG-1 … TG-5 (`PBDIGIT-267`). Slice 1b stays conditionally accepted (ARB-MEM-02).
