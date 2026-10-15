# PublicDigit Product Backlog

**The product is an Organisation Governance Platform whose first major capability is running elections.** The customer journey therefore starts long before an election exists — it starts with an organisation.

**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit`
**Distinct from** `docs/implementation/backlog/` (programme/engineering: `PB-nnn` tickets, `ENG-nnn` items, work packages). **Engineering debt lives there; what a customer can or cannot do lives here.**

---

## Level 0 — the customer journey

```
Create Organisation → Configure Organisation → Create Committees → Import Members → Approve Members
        ↓
Create Election → Configure Election → Candidate Registration → Candidate Approval → Voter Verification
        ↓
Open Voting → Vote → Close Voting → Count Votes → Publish Results → Audit → Archive
```

Every epic below is a segment of this one journey. Nothing in the backlog exists outside it.

---

## Epics

| Epic | Capability | Stories | Status |
|---|---|---|---|
| [`PBDIGIT-EPIC-01`](PBDIGIT-EPIC-01-organisation-management.md) | Organisation Management | `PBDIGIT-01`…`03` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-02`](PBDIGIT-EPIC-02-membership-management.md) | Membership Management | `PBDIGIT-04`…`08` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-03`](PBDIGIT-EPIC-03-election-management.md) | Election Management | `PBDIGIT-09`…`11` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-04`](PBDIGIT-EPIC-04-candidate-management.md) | Candidate Management | `PBDIGIT-12`…`14` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-05`](PBDIGIT-EPIC-05-voting.md) | Voting | `PBDIGIT-15`…`17` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-06`](PBDIGIT-EPIC-06-results-and-audit.md) | Results & Audit | `PBDIGIT-18`…`24` | `IMPLEMENTED — NOT VERIFIED` |

### Standalone defect stories

Small, low-risk defects get their own story file rather than waiting for their epic's review. They keep the same flat ID sequence.

| ID | Story | Epic | Status |
|---|---|---|---|
| [`PBDIGIT-25`](PBDIGIT-25-copy-button-must-not-submit-the-form.md) | Copy button must not scroll or submit the voting form (public demo) | `EPIC-05` | `FIXED — AWAITING RUNTIME VERIFICATION` |
| [`PBDIGIT-26`](PBDIGIT-26-copy-code-action-on-verification-page.md) | Add "Copy Code" action to the Voter Verification page | `EPIC-05` | `IMPLEMENTED — AWAITING BROWSER VERIFICATION` |
| [`PBDIGIT-27`](PBDIGIT-27-case-sensitive-import-paths-break-the-app-on-linux.md) | Case-sensitive import paths break the app on Linux (home page + build) | cross-cutting | `FIXED` — build green |
| [`PBDIGIT-28`](PBDIGIT-28-resolve-remaining-missing-module-imports.md) | Resolve the remaining missing-module imports (dead code, not runtime failures) | cross-cutting | `FIXED` — 0 unresolved live imports |
| [`PBDIGIT-29`](PBDIGIT-29-organisation-context-discovery.md) | **Organisation Context Discovery** — implementation discovery, no code (rev 2) | `EPIC-01` | `DISCOVERY COMPLETE — Q1–Q6 open` |
| [`PBDIGIT-30`](PBDIGIT-30-active-organisation-business-lifecycle-discovery.md) | **Working Organisation Business Lifecycle** — business decision record | `EPIC-01` | ✅ **CLOSED** — B1 · B2 · B3 · B10 approved; B4 withdrawn → `PBDIGIT-34`; B5–B7 answered by B1–B3 |
| [`PBDIGIT-31`](PBDIGIT-31-redirection-mechanism-review.md) | **Redirection Mechanism Review** — discovery only ([report](../reviews/2026-08-06-pbdigit-31-redirection-mechanism-review.md)) | `EPIC-01` | `REVIEW COMPLETE` — I-1 awaits authorisation; I-2 is now a one-line defect fix (RD-12) |
| [`PBDIGIT-32`](PBDIGIT-32-implement-working-organisation-routing.md) | **Implement Working Organisation Routing** — extend the resolver, do not redesign it | `EPIC-01` | ✅ **UNBLOCKED** — awaiting authorisation |
| [`PBDIGIT-34`](PBDIGIT-34-remember-the-working-organisation-across-logins.md) | **Remember the Working Organisation across logins?** — product *question*, not a requirement | `EPIC-01` | `OPEN — unprioritised`, blocks nothing |
| [`PBDIGIT-33`](PBDIGIT-33-fix-routing-cache-invalidation.md) | **Fix routing-cache invalidation** — observer was imported but never attached (+ UUID type fix) | cross-cutting | `FIXED — AWAITING RUNTIME VERIFICATION` |
| [`PBDIGIT-35`](PBDIGIT-35-retire-the-legacy-global-voter-flags.md) | **Production code reads retired global voter flags** — `is_voter`/`can_vote` exist in no database; eligibility is org+election scoped | `EPIC-02` | `OPEN` — found by `PBDIGIT-00` F-1 |
| [`PBDIGIT-36`](PBDIGIT-36-the-e2e-suites-do-not-test-the-journey.md) | **The "end-to-end" suites do not test the journey** — 10 of 21 tests never reach an assertion | cross-cutting | `OPEN` — found by `PBDIGIT-00` F-2/F-3 |
| [`PBDIGIT-37`](PBDIGIT-37-test-credential-and-safety-claim-hygiene.md) | **Committed test credentials + a safety claim that is false on PostgreSQL** | cross-cutting | `OPEN` — credential deduplicated; the rest open |
| [`PBDIGIT-38`](PBDIGIT-38-a-vote-cannot-be-saved.md) | ✅ **A vote cannot be saved** — audit write poisoned the vote transaction | `EPIC-05` | ✅ **FIXED & VERIFIED** — a vote persists, journey completes; I-2 residual → `PBDIGIT-42` |
| [`PBDIGIT-42`](PBDIGIT-42-define-audit-schema-semantics.md) | **Define `overlay_influence_chain` semantics** — decision required, not engineering's to make | `EPIC-06` | `OPEN` — blocks invariant I-2 only |
| [`PBDIGIT-43`](PBDIGIT-43-voting-code-never-populated.md) | **`demo_votes.voting_code` is never populated** — must derive from `vote_id`, never `code_id` (anonymity) | `EPIC-05` | `OPEN` — found verifying `PBDIGIT-38` |
| [`PBDIGIT-46`](PBDIGIT-46-components-registered-but-never-imported.md) | **Components registered but never imported crash their pages** — 5 live pages fixed | cross-cutting | ✅ **FIXED** · gate `OPEN` |
| [`PBDIGIT-47`](PBDIGIT-47-voter-not-routed-to-live-election.md) | 🔴 **A voter is sent to the organisation page during a live election** — routing reads a stale `status` column | `EPIC-05` | `OPEN` — **blocking**; workaround: `/election/select` |
| [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) | **Login routing reads a deprecated field instead of the constitutional lifecycle** — an unfinished migration, not competing authorities | `EPIC-03` | ✅ **DISCOVERY COMPLETE** → `PBDIGIT-58` |
| [`PBDIGIT-58`](PBDIGIT-58-complete-legacy-election-state-migration.md) | **Complete the legacy election-state migration** — 7 capabilities to restore; `58A` observes before anything is migrated | `EPIC-03` | ⬜ **AWAITING AUTHORISATION** — Option B approved |
| [`PBDIGIT-59`](PBDIGIT-59-which-timestamps-are-constitutional.md) | 🟡 **Which timestamps are constitutional?** — the lifecycle reads `voting_*`, the legacy queries read `start_date`/`end_date`; they disagree for 4 of 4 elections | `EPIC-03` | **DECISION REQUIRED** — sequences `58B` |
| [`PBDIGIT-49`](PBDIGIT-49-voter-eligibility-has-two-homes.md) | **Voter eligibility has two homes** — `election_memberships` (3) vs `voters` (0); **corrects `PBDIGIT-35`** | `EPIC-02` | `OPEN` |
| [`PBDIGIT-50`](PBDIGIT-50-timezone-not-converted-for-display.md) | **Times not converted to the viewer's timezone** — device timezone, not residence (PO decision) | `EPIC-03` | `OPEN` |
| [`PBDIGIT-51`](PBDIGIT-51-error-pages-cannot-render.md) | **Error pages cannot render** — `symfony/config` missing, so every 500 hides its own cause | cross-cutting | `OPEN` |
| [`PBDIGIT-45`](PBDIGIT-45-unconfigured-safeguards-in-real-elections.md) | 🟡 **Who owns "votes per IP"?** — the Constitution does; the controller check is legacy H.3 with an unwired retirement flag ([discovery](../reviews/2026-08-06-who-owns-the-votes-per-ip-rule.md)) | `EPIC-05` | **DECISION REQUIRED** — do not implement |
| [`PBDIGIT-44`](PBDIGIT-44-locale-messages-break-pages-with-no-gate.md) | 🔴 **A locale string broke a public page** (`@` is vue-i18n syntax) — **fixed**; the missing i18n gate is open | cross-cutting | ✅ `@` class **FIXED — verified in production** · `{{ }}` class + gate `OPEN` |
| [`PBDIGIT-39`](PBDIGIT-39-unset-config-blocks-every-voter.md) | **IP vote limit wrongly applied to demo elections** + no safe default for real ones | `EPIC-05` | ✅ demo exemption **FIXED & VERIFIED** · real-election default `OPEN` |
| [`PBDIGIT-40`](PBDIGIT-40-completion-page-has-never-worked.md) | **`thank-you` route 500s** — undefined `$vote`. **NOT on the happy path** (severity corrected) | `EPIC-05` | `OPEN` — **Low** |
| [`PBDIGIT-41`](PBDIGIT-41-demo-provisioning-has-two-paths.md) | **Demo provisioning has two paths and the documented one is broken** | `EPIC-03` | `OPEN` — found by `PBDIGIT-00` (b) |
| [`PBDIGIT-65`](PBDIGIT-65-voter-eligibility-decided-by-tenant-context.md) | 🔴 **A voter's eligibility depends on which page they last visited** — a valid `ElectionMembership` is hidden by tenant scope, at the *enforcement* boundary. Root cause proven by A/B | `EPIC-05` | `OPEN` — repair owner undecided |
| [`PBDIGIT-66`](PBDIGIT-66-establish-election-only-mode-constitutionally.md) | 🟡 **Establish Election-Only in the constitution and the projection** — it works end to end and the constitution never mentions it; both modes converge at `ElectionMembership` ([investigation](../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md)) | `EPIC-03` | **DECISION REQUIRED** — `D-1`…`D-6`; not engineering's to resolve |
| [`PBDIGIT-70`](PBDIGIT-70-two-unparseable-files-under-app.md) | **Two files under `app/` cannot be parsed by PHP** — both dead/unreachable, but any full static-analysis sweep of `app/` fails; `ElectionUser` must not be mistaken for `Q3` prior art | cross-cutting | `PARTIALLY RESOLVED` — `ElectionUser.php` repaired 2026-08-12; `VoterSlugStep.php` still open (a whole Markdown document, see `reviews/2026-10-11-voterslugstep-markdown-provenance-investigation.md`); disposition is the PO's call |
| [`PBDIGIT-69`](PBDIGIT-69-voter-eligibility-cache-is-tenant-unaware.md) | 🔴 **The voter-eligibility cache key is tenant-unaware** — a wrong-context read poisons the ballot predicate for 300s, in **both** directions ([measured](../reviews/2026-08-12-g1-voter-suspension-enforcement.md)) | `EPIC-02` | `OPEN` — **`D-1` is a business decision**; makes `PBDIGIT-65` persist |
| [`PBDIGIT-69`](PBDIGIT-69-operating-core-post-verification-items.md) ⚠️ | **Operating-core post-verification items (C-5 findings N-3…N-6)**. ⚠️ **The ID collides** with the row above; the collision is owned by `PBDIGIT-134` | `EPIC-03` | `OPEN` |
| [`PBDIGIT-68`](PBDIGIT-68-election-membership-is-a-durable-entitlement.md) | ✅ **`D-ENT-1` APPROVED — Model B** (PO 2026-08-12) · **`BR-1` PARTIALLY RESOLVED** — Option B recommended (termination retains the record, permanent to officers, admin-reversible); `revoke` is already taken with an inverted meaning; `ADR-T11` blocks post-vote reach; **authority does not track consequence** and **no governance act reaches the election audit trail** · **`ADR-002` amendment PROPOSED · `Q3` OPEN · IMPLEMENTATION NOT AUTHORISED** — closes Appendix E's `NOT SPECIFIED`. ⚠️ **G-1 MEASURED 2026-08-12: the ballot IS protected (403); enforcement is incidental** — `G-6` withdrawn, `G-5` escalated, and `admit`/`revoke`/`restore` have no constitutional names | `EPIC-03` | **DECISION RECORDED** · consequences `G-1`…`G-7` open; Constitution **not** amended |
| [`PBDIGIT-67`](PBDIGIT-67-election-schedule-input-interprets-local-time-as-utc.md) | 🔴 **The election schedule input interprets browser-local time as UTC** — an election opens 1–2 hours late (DST-variable); **corrects `PBDIGIT-50`'s "display gap, not a data defect"** ([trace](../reviews/2026-08-12-time-value-trace-browser-to-display.md)) | `EPIC-03` | `OPEN` — **not authorised**; `D-1`…`D-4` need business answers |
| [`PBDIGIT-64`](PBDIGIT-64-election-opens-voting-without-any-candidate.md) | 🔴 **An election can enter voting with no candidates** — `complete_nomination` enforces the rule, the automatic window transition does not. Reproduced end to end | `EPIC-03` | `OPEN` — PO-confirmed defect; found by IERVP runtime verification |
| [`PBDIGIT-63`](PBDIGIT-63-verify-the-organisation-creation-journey-and-its-verification-estate.md) | 🟢 **"Create an organisation → land on its homepage" — RUNTIME-VERIFIED end to end 2026-08-08** (first recorded execution; persisted outcome checked in the database). Six creation assertions still describe a different contract from the controller — **classify before repairing**; corrects rule `O1`'s automated-test *evidence* ([report](20260808-create-organisation-and-visit-homepage-journey-report.md)) | `EPIC-01` | **Part A ✅ DONE** · Part B unblocked, unauthorised · browser render + `F-5` walk still unverified · `D-1`…`D-6` need business answers |
| [`PBDIGIT-60`](PBDIGIT-60-result-visibility-never-became-a-capability.md) | **Result Visibility never became a first-class capability** during the constitutional migration | `EPIC-03` | 🔴 D-2 defect fixable now · D-1 design open |
| [`PBDIGIT-61`](PBDIGIT-61-newsletter-suite-fails-before-any-change.md) | The newsletter test suite fails at HEAD, before any change | `EPIC-05` | ✅ **RESOLVED** 2026-08-07 |
| [`PBDIGIT-62`](PBDIGIT-62-already-voted-exclusion-never-fires-at-login.md) | The "already voted" exclusion never fires at login: a tenant scope defeats it | `EPIC-05` | ✅ **REPAIRED** 2026-08-07 |
| [`PBDIGIT-71`](PBDIGIT-71-w2-guard-scans-comments-as-code.md) | W-2 structural guard scans comments as if they were code | `EPIC-03` (EM-IMPL-002) | `OPEN` — low |
| [`PBDIGIT-72`](PBDIGIT-72-em-gov-063-terminal-consequence-is-unreachable.md) | `EM-GOV-063`'s terminal consequence is unreachable | `EPIC-03` (EM-DOM-001) | `BACKLOG` — not authorised |
| [`PBDIGIT-73`](PBDIGIT-73-green-5-labels-two-different-slices.md) | `GREEN-5` labels two different slices; the EM-IMPL-002 status picture is stale | `EPIC-03` | 🔴 `OPEN` — no repair authorised |
| [`PBDIGIT-74`](PBDIGIT-74-election-audience-selects-zero-recipients.md) | Election notification selects **zero** recipients where eligible recipients exist | `EPIC-05` | 🔴 `OPEN` — **DECISION REQUIRED** |
| [`PBDIGIT-75`](PBDIGIT-75-newsletter-authorization-omits-election-tenancy-check.md) | Newsletter authorization never verifies the named election belongs to the organisation | `EPIC-05` | 🔴 `OPEN` — not authorised |
| [`PBDIGIT-158`](PBDIGIT-158-adjudication-issuance-marker-shifts-two-hours.md) | 🔴 **The adjudication issuance marker comes back two hours shifted after persistence**. A legal timestamp changes value on save and load (`IssuanceContextRoundTripTest`, 12:00:05Z → 10:00:05Z); the mapper is symmetric, so the root cause is on the issuance path and still UNKNOWN ([verification](../../implementation/2026-10-01-push-b-status-verification.md)) | `EPIC-06` | `OPEN — NOT AUTHORISED` |
| [`PBDIGIT-209`](PBDIGIT-209-membership-fee-payment-cannot-be-recorded.md) | 🔴 **A membership fee payment cannot be recorded.** `MembershipPaymentService` writes `currency` and `payment_reference`, which `membership_payments` does not have, so the routed `fees/{fee}/pay` fails (runtime-confirmed: 14 / 14 tests) ([review](../reviews/2026-10-01-backlog-completion-review.md)) | `EPIC-02` | `OPEN — defect` · **not ready:** PO decides the payment column shape first · added under the freeze rule |
| [`PBDIGIT-210`](PBDIGIT-210-participant-import-rejects-the-templates-own-roles.md) | 🔴 **Participant import fails on the roles its own template offers**: `coordinator`, `observer` and `scrutineer` violate the DB CHECK constraint (`participant`, `organizer`), so the routed import returns 500 (runtime-confirmed) | `EPIC-02` | `OPEN — defect` · **not ready:** PO decides the role vocabulary first · added under the freeze rule |
| [`PBDIGIT-211`](PBDIGIT-211-scheduled-jobs-see-no-tenant-data.md) | 🔴 **Scheduled jobs see no tenant data** (⛔ ARB: tenant-context model for non-request execution, first): with no tenant, `BelongsToTenant` filters `organisation_id IS NULL` (its fallback looks for slug `platform`; the platform org is `publicdigit`). Annual fees, overdue marking, renewal reminders and the voter-cache flush silently do nothing (runtime probe) | `EPIC-02` / `EPIC-03` | `OPEN — defect` · **not ready: ⛔ ARB architecture decision required first** (the tenant-context model for non-request execution) · added under the freeze rule |
| [`PBDIGIT-212`](PBDIGIT-212-election-timeline-pages-forbidden-to-every-officer.md) | 🔴 **The election timeline pages return 403 to every user, chief officers included**: `can:manageSettings` receives the slug string because the actions declare `string\|Election` (implicit binding skips union types); on `main` since April/May 2026 (Gate probe) | `EPIC-03` | `OPEN — defect` · **the only one of `PBDIGIT-209`–`212` ready for PO authorisation** (other backlog stories, e.g. 201, 202, 158, are also ready) (no further business or architecture decision stated) · added under the freeze rule |
| [`PBDIGIT-213`](PBDIGIT-213-election-only-voter-admission-v1.md) | **Election-Only voter admission v1**: identity verification by the election's Chief or Deputy Chief before a voter may vote | `EPIC-05` | 🟢 **AUTHORISED** (PO, 2026-10-04) · C0–C4 implemented, C4 accepted (`9c482db85`) |
| [`PBDIGIT-214`](PBDIGIT-214-voter-restart-issues-a-slug-without-an-entitlement-check.md) | **`voter.restart` issues a voting slug without consulting voting entitlement** | `EPIC-05` | 🟡 `OPEN` — latent, no voting bypass today; not authorised |
| [`PBDIGIT-215`](PBDIGIT-215-voter-recovery-is-destructive-and-calls-a-missing-method.md) | **Voter recovery deactivates all slugs non-atomically, then calls a missing method** | `EPIC-05` | 🟡 `OPEN` — latent, currently unreachable; not authorised |
| [`PBDIGIT-216`](PBDIGIT-216-emergency-lockdown-calls-missing-methods.md) | **Emergency lockdown and slug enforcement call methods that do not exist** | `EPIC-05` | 🔴 `OPEN` — feature non-functional; not authorised |
| [`PBDIGIT-217`](PBDIGIT-217-demo-session-flag-skips-lifecycle-check-on-real-slugs.md) | **A leftover demo session flag skips the lifecycle check on real-election slug routes (step 1)** | `EPIC-05` | 🟠 `OPEN` — admission still enforced; not authorised |
| [`PBDIGIT-218`](PBDIGIT-218-legacy-votes-route-may-judge-the-wrong-election.md) | **Legacy `POST /votes` may evaluate entitlement against the wrong election** | `EPIC-05` | 🟡 `OPEN` — latent, refused today; not authorised |
| [`PBDIGIT-219`](PBDIGIT-219-vote-store-crashes-without-a-code.md) | **`VoteController::store` returns 500 when the voter has no Code** | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — Phase 4 E3 (`CodeMissing`) |
| [`PBDIGIT-220`](PBDIGIT-220-remove-voter-management-left-column.md) | **Remove the outdated left column from Voter Management** (Export CSV moved to the header; empty text "The register is empty.") | `EPIC-05` | 🟢 **IMPLEMENTED** 2026-10-05 (`7ea1dc06f`) |
| [`PBDIGIT-221`](PBDIGIT-221-add-organisation-members-as-election-voters.md) | **Add organisation members as voters of an election** (replaces the removed left-column assignment) | `EPIC-05` | 🟡 `OPEN` — not authorised; PO decisions first |
| [`PBDIGIT-222`](PBDIGIT-222-ip-verification-has-no-backend-of-its-own.md) | **"Verify IP Address" has no backend of its own**: it records identity-admission evidence | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — separate Capture IP (`voter_ip_verifications`) + "Voter's IP Verification" setting; no pass → fail |
| [`PBDIGIT-223`](PBDIGIT-223-vote-hash-computable-from-voter-side-data.md) | 🔴 **`vote_hash` is computable from voter-side data and configuration (A5)** — critical, ballot secrecy | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0 Anonymity ADR); mechanism unchanged |
| [`PBDIGIT-224`](PBDIGIT-224-voter-slug-step-stores-the-vote-id.md) | 🔴 **The voter's slug step record stores the vote id (A6)** — stored voter → ballot link | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0); accepted interim risk |
| [`PBDIGIT-225`](PBDIGIT-225-cast-logs-correlate-voter-and-ballot.md) | **Cast logs correlate voter and ballot and log the receipt secret (A1)** | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0); gates Phase 7 |
| [`PBDIGIT-226`](PBDIGIT-226-voter-side-timestamps-join-to-the-ballot.md) | **Voter-side timestamps join to exactly one ballot (A2)** | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0) |
| [`PBDIGIT-227`](PBDIGIT-227-vote-ids-encode-creation-time.md) | **Vote ids encode their creation time and order (A4)** | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0) |
| [`PBDIGIT-228`](PBDIGIT-228-receipt-and-view-my-vote-expose-the-ballot.md) | **Receipts and "view my vote" expose the ballot to any code holder (K2–K5)** | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0, Model A vs B); feature kept |
| [`PBDIGIT-229`](PBDIGIT-229-membership-has-voted-never-written.md) | **`election_memberships.has_voted` is never written after a real vote (D9)** | `EPIC-05` | 🟡 `OPEN` — decision pending (Phase 2.0); no `voted_at = now()` fix |
| [`PBDIGIT-230`](PBDIGIT-230-no-database-guarantee-against-double-voting.md) | **No database-level guarantee against double voting (D4)** | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — Phase 4 E4 (`b1daf26a2`, compare-and-set before the vote insert) |
| [`PBDIGIT-231`](PBDIGIT-231-regenerated-code-skips-code-verification.md) | **A regenerated code keeps the code step verified; code entry accepts any value (D6)** | `EPIC-05` | 🔴 `OPEN` — not authorised; `can_vote_now` frozen (ADR Q7) |
| [`PBDIGIT-232`](PBDIGIT-232-security-denial-crashes-with-type-error.md) | **The final vote's security-denial branch crashes with a `TypeError` (D3)** | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — Phase 4 E1 (`7aa33df68`) |
| [`PBDIGIT-233`](PBDIGIT-233-vote-post-check-ignored-and-missing-view.md) | **`vote_post_check` result ignored; denials crash on a missing view; unset IP limit blocks every vote (D1, D10)** | `EPIC-05` | 🟢 **RESOLVED for the final cast** 2026-10-05 — Phase 4 E3; ops item `MAX_USE_IP_ADDRESS` (code entry) and Demo/Deligate copies remain · step-4 occurrence → `PBDIGIT-245` |
| [`PBDIGIT-234`](PBDIGIT-234-voter-ip-binding-reads-a-column-that-does-not-exist.md) | **Voter IP binding reads `users.voting_ip`, a column that does not exist** — `validateVotingIpWithResponse()` and the trust chain's `registeredIpHash` are unreachable (found by Phase 4 E1 RED) | `EPIC-05` | 🟡 `OPEN` — not authorised; deferred to the security / IP-binding phase (P4-D4 retired) |
| [`PBDIGIT-235`](PBDIGIT-235-soft-deleted-code-can-still-cast-a-vote.md) | **A soft-deleted voting `Code` can still be used to cast a vote** — `store()` loads the code `withoutGlobalScopes()`, which also lifts `SoftDeletingScope` (found by Phase 4 E3 RED) | `EPIC-05` | 🟡 `OPEN` — not authorised; semantics decision required (out of Phase 4 E3, option B) |
| [`PBDIGIT-236`](PBDIGIT-236-regional-ballot-authority-rests-on-mutable-user-region.md) | **The regional ballot rests on the mutable, user-level `users.region` (D6)** — which region decides the regional ballot is an open architecture decision (found by the VE-01 design investigation) | `EPIC-05` | 🟡 `OPEN` — architecture decision required; not authorised for implementation |
| [`PBDIGIT-237`](PBDIGIT-237-final-cast-commits-ballot-without-integrity-check.md) | **The final cast commits a ballot without checking it against the voter's ballot (VE-01)** — cross-election / cross-post / out-of-region / duplicate / over-maximum / malformed selections are committed and change the tally (verification gate T1) | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — GREEN `33bef9f7d` (only a `ValidatedBallot` is persisted; 0 regressions over 97 suites) |
| [`PBDIGIT-238`](PBDIGIT-238-codecontroller-real-election-boundary.md) | **CodeController: real-election boundary** — demo behaviour in the real code step (incl. a reset of consumed participation) and plaintext codes in logs; R0 discovery → R1 boundary (Track B, VE-06) | `EPIC-05` | 🟡 `IN PROGRESS` — R0 `b90547add`, R1 RED `f722520dc`, R1 GREEN `7a8407d06` committed; close-out (guide, session log, CONTEXT) open |
| [`PBDIGIT-239`](PBDIGIT-239-entry-authorization-forentry-at-code-step.md) | **Entry authorization `forEntry()` at the code step** — ADR §3.1: E1 `VotingEntitlement.decide()` + E2 one Security Verdict Authority shared with the final cast; called before code creation (Phase 5 Round 2) | `EPIC-05` | 🟡 `IN PROGRESS` — blueprint `ca9629b99`, 2A RED `430d28742`, Stage A `0578091f8`, Stage B `d90131361` GREEN; Stage C deferred → PBDIGIT-240; 2B after Q3 |
| [`PBDIGIT-240`](PBDIGIT-240-votecontroller-security-assessment-consolidation-stage-c.md) | **VoteController security assessment vs the Security Verdict Authority (deferred Stage C)** — the final cast needs the four-valued outcome and the trust envelope, not only the verdict; decide verdict-only vs richer assessment and telemetry ownership first | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 for the final cast — RED `c6490376c`, GREEN: one `SecurityAssessment` consumed by `store()`; checks #2–#6 deferred to Q3 / 2B |
| [`PBDIGIT-241`](PBDIGIT-241-voter-ballot-linkage-and-receipt-secret-in-logs.md) | **Logs link a voter to a ballot and expose the receipt secret (F-V6 / F-V7)** — receipt secret (full and 20-char prefix) logged at the final cast; user id with receipt / vote id in one entry in `confirmCorrect()` and `submit_code_to_view_vote()`; A8 audit receipt-hash decision attached | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 for F-V6 / F-V7 — RED `19f0d3356`, GREEN `826a8f70d`; 🟡 A8 decision open |
| [`PBDIGIT-242`](PBDIGIT-242-empty-real-ballot-is-accepted-as-a-vote.md) | **A real-election ballot with nothing answered is accepted as a vote** — zero answers consume participation and commit a vote with no result rows (found in the VE-01 GREEN regression) | `EPIC-05` | 🟡 `OPEN` — decision needed (valid participation-only vote, or refusal before the CAS); not authorised |
| [`PBDIGIT-243`](PBDIGIT-243-final-cast-exits-that-bypass-fail-closed.md) | **Two final-cast exits return without the fail-closed exit (F-V9)** — fresh-membership redirect (after BEGIN, before the lock) and `validateVotingIpWithResponse()` (after BEGIN and the lock; latent, PBDIGIT-234) | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — RED `da54c03c5`; Exit B fails closed (GREEN); Exit A kept as characterization; regression unchanged |
| [`PBDIGIT-244`](PBDIGIT-244-security-policy-not-locked-from-ready-for-voting.md) | **The election's security policy can still be changed from ReadyForVoting (I9)** — IP restriction, whitelist and verification mode have no lifecycle lock (completion matrix, source reading) | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 for the four-field boundary — RED `58b0ec769`, GREEN: application refusal + model guard; 🟡 Q3 open (policy surface) |
| [`PBDIGIT-245`](PBDIGIT-245-step4-security-refusal-renders-missing-error-view.md) | **Step-4 security refusal renders missing error view and degrades to generic system error** — every `vote_post_check()` refusal at step 4 renders the non-existent `components.error_message`; `verify()`'s catch-all answers "System error during verification" (Q3 C4) | `EPIC-05` | 🟠 `OPEN` — not authorised; separate from 2B-4 |
| [`PBDIGIT-246`](PBDIGIT-246-whitelist-broken-in-security-verdict-authority.md) | **The election whitelist breaks the Security Verdict Authority (2B-1)** — `json_decode` on the array-cast list throws, hashed vs raw comparison never matches; a configured whitelist blocks voting (Q3 #8, C1) | `EPIC-05` | 🟢 **RESOLVED** 2026-10-05 — RED `902060e92`, GREEN: raw IPv4 CIDR matched inside the authority; no pass → fail |
| [`PBDIGIT-247`](PBDIGIT-247-p-binding-registered-network-binding.md) | **P-BINDING: bind a voter to a registered network (Security Policy)** — supersedes Q3-D6; storage, capture, scoping, lock, authority evaluation at entry / cast | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — captured AND matching, fail closed, at forEntry / forCast; `IpBindingDenied`; follow-up PBDIGIT-251 |
| [`PBDIGIT-248`](PBDIGIT-248-identity-verification-cancel-and-ip-separation.md) | **Identity verification: Cancel Verification and no IP / device data in identity evidence** — verified voter gets Cancel (existing revoke), the identity dialog and endpoint carry no network / device data | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — RED `7ae825d1e`, GREEN: Cancel Verification, identity without IP / device; no pass → fail |
| [`PBDIGIT-249`](PBDIGIT-249-email-verification-admission-evidence.md) | **Email verification as election-scoped admission evidence** — `EMAIL_VERIFICATION` requirement, voter-initiated hashed single-use 60-min challenge | `EPIC-05` | 🟡 `OPEN` — design decided; RED contract to finalise; not authorised |
| [`PBDIGIT-250`](PBDIGIT-250-read-only-voter-list-verification-states.md) | **Read-only voter list shows verification states** — Identity Verification (when required) and IP Address Verification (when enabled) columns, state only, no IP addresses | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — state-only columns, shared `VoterVerificationStates` read-model; no pass → fail |
| [`PBDIGIT-251`](PBDIGIT-251-voter-management-reachable-during-voting-for-verification.md) | **Voter Management reachable during voting, for verification only** — page is 403 from SetupNomination while identity / IP-capture endpoints are open until voting closes; contact data stays unchangeable | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — page readable in every state; verification / IP capture / two-person suspension until voting closes; remove stays setup-only |
| [`PBDIGIT-252`](PBDIGIT-252-participation-authority-codes-has-voted.md) | **One participation authority: `codes.has_voted`** — Voter Management Voted column, row actions, suspension guard and newsletter voted / not-voted audiences stop reading the writer-less `election_memberships.has_voted` | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — all four consumers read `codes.has_voted`; Voted column Yes / No |
| [`PBDIGIT-253`](PBDIGIT-253-voter-management-voted-filter.md) | **Voter Management: filter by Voted** (All / Voted / Not voted, from `codes`) — also fixes filters navigating to the read-only list | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — Voted filter from `codes`; filters stay on `/voters/manage` |
| [`PBDIGIT-254`](PBDIGIT-254-settings-page-shows-why-a-save-was-refused.md) | **Election Settings shows why a save was refused, and locks locked fields** — server errors (e.g. the security-policy lock while voting) were silently discarded | `EPIC-05` | 🟢 **RESOLVED** 2026-10-06 — refusals shown; security fields locked from Ready for Voting; all settings frozen while voting (IP capture continues) |
| [`PBDIGIT-255`](PBDIGIT-255-election-test-module-setup-fixes.md) | **Election test module, story 1: test-setup defects** — duplicate role row; redundant `RefreshDatabase` hiding rows from the audit connection | `EPIC-05` | 🟢 **RESOLVED** 2026-10-07 — 9 tests fixed, no assertion changed |
| [`PBDIGIT-256`](PBDIGIT-256-election-test-module-lifecycle-fixtures.md) | **Election test module, story 2: lifecycle fixtures** — fixtures now carry the facts the derived lifecycle needs (approved candidate, voting opened, guard actor) | `EPIC-05` | 🟢 GREEN 2026-10-07 — 19 tests fixed, no production change |
| [`PBDIGIT-257`](PBDIGIT-257-membership-mode-switch-crashes-for-any-organisation-with-elections.md) | 🔴 **Changing the membership mode crashes (HTTP 500) for any organisation that has an election** (`IERVP-1`, 2026-08-08, never ticketed): the guard calls `isParticipationLocked()` on the `ElectionLifecycle` facade, which lacks it ([review](../reviews/2026-10-07-membership-mode-switch-blocked-review.md)) | `EPIC-01` | `OPEN — defect` · not authorised · fixing it alone does not unlock orgs with locked elections (ARB guard decision) |
| [`PBDIGIT-258`](PBDIGIT-258-membership-slice0-semantic-kernel.md) | **Membership Slice 0: the semantic kernel** — pure domain `Domain/Organisational` (Admit / End, resolution, invariants, G\*) | `EPIC-02` | 🟢 GREEN WITH CONDITIONS 2026-10-09 — awaiting formal acceptance |
| [`PBDIGIT-259`](PBDIGIT-259-membership-slice1a-repository-and-concurrency.md) | **Membership Slice 1a: repository port, concurrency and idempotency** — handlers, bounded re-decide loop, contract test suite | `EPIC-02` | 🟢 GREEN WITH CONDITIONS 2026-10-09 — awaiting formal acceptance; IDEM-1 amended by PBDIGIT-260 |
| [`PBDIGIT-260`](PBDIGIT-260-membership-slice1b-postgres-adapter.md) | **Membership Slice 1b: PostgreSQL adapter** — three tables, tenant-scoped FKs, atomic append, real-process race tests | `EPIC-02` | 🟢 GREEN 2026-10-09 — awaiting Principal Reviewer acceptance · migration **not rolled out** (dev / prod) |
| [`PBDIGIT-261`](PBDIGIT-261-database-engine-defaults-contradict-postgresql-decision.md) | **Database-engine defaults still say `mysql`** (config, `.env.example`, docs) — contradicts DB-1; includes the operator check of the production engine | — | `OPEN — technical debt` · not authorised |
| [`PBDIGIT-262`](PBDIGIT-262-legacy-migrations-carry-dead-mysql-branches.md) | **Legacy migrations carry dead MySQL branches** (5 migrations) | — | `OPEN — technical debt` · not authorised |
| [`PBDIGIT-263`](PBDIGIT-263-test-base-class-claims-transaction-isolation-it-skips-on-postgresql.md) | **`Tests\TestCase` claims per-test transaction rollback it skips on PostgreSQL** — data persists between tests; the "zero corruption" safety comment is false | — | `OPEN — technical debt` · not authorised |
| [`PBDIGIT-264`](PBDIGIT-264-membership-slice1b-acceptance-follow-ups.md) | **Membership Slice 1b follow-ups** — far-date defect (a Member becomes unloadable after year 9999), Admit race-test gap, adapter parity, commands→facts FK | `EPIC-02` | `OPEN` · not authorised |
| [`PBDIGIT-265`](PBDIGIT-265-full-membership-eligibility-bulk-and-single-predicates-differ.md) | **Full-Membership voter eligibility: the bulk list and the single-user decision use different predicates** (type voting rights, expiry) — may overlap IERVP-2 / entitlement retention | `EPIC-03` | `OPEN — investigation` · not authorised |
| [`PBDIGIT-266`](PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md) | 🔴 **Live routes whose authorisation guard could not be found** — `/admin/elections/{slug}/approve`, committee application review, governance member add/remove, legacy finance (static evidence; confirm by feature tests) | — | `OPEN — security investigation` · not authorised |
| [`PBDIGIT-267`](PBDIGIT-267-membership-kernel-test-gaps-tg1-tg5.md) | **Membership kernel test gaps TG-1 … TG-5** (test-only; from the Slice 0 mutation measurement) — `fromLog` validation, evidence-field validation, whitespace ids, refused-decision misuse, `IntervalSet::equals` | `EPIC-02` | `OPEN` · not authorised (Human ARB ARB-TEST-01: separate, explicitly authorised work item) |
| [`PBDIGIT-268`](PBDIGIT-268-membership-correction-slice-c1-annul-void-kernel.md) | **Membership correction slice C-1: Annul and Void in the existing kernel** (TDD; fact liveness, D-COR-2 `TargetNotLive`, D-COR-5 (A) no implicit consequences; kernel only) | `EPIC-02` | `IMPLEMENTED, AWAITING REVIEW` · acceptance correction done 2026-10-10; formal acceptance deferred to independent review (H-6) |
| [`PBDIGIT-269`](PBDIGIT-269-membership-sensitivity-runner-hardening.md) | **Membership sensitivity-runner hardening** (test tooling; KILLED only on assertion failures, exactly one test, anchored filter, abort on surviving canary, explicit HEAD / working-tree source, no patch fuzz; from the C-1 independent review) | `EPIC-02` | `OPEN` · not authorised |
| [`PBDIGIT-270`](PBDIGIT-270-credential-exposure-config-and-tracked-env-backups.md) | **Credential exposure: committed database password and tracked environment backups** (security; S-1 / S-2 from the Membership Part 2 readiness investigation; values not reproduced; owner to be named) | — | `OPEN` · not authorised |
| [`PBDIGIT-271`](PBDIGIT-271-membership-slice2-admit-end-command-rules.md) | **Membership Slice 2: Admit / End command rules** (Slice 2a, database-free: D-1 basis guard, D-3 closed ending acts, D-5(b) future-dating refusal with a required decision date, interim authority for Admit (End denied until Governance decides G-1), Election-Only ledger-isolation and creation-path fitness tests; Slice 2b binding held with PostgreSQL; plan `docs/plans/20261011-0010-membership-slice2-command-rules-plan.md`) | `EPIC-02` | `IN REVIEW` · Slice 2a implemented and hardened (H1, H2), committed `834a4f65c` (not pushed), not accepted; 2b held |
| [`PBDIGIT-272`](PBDIGIT-272-unparsable-voterslugstep-middleware.md) | **`VoterSlugStep.php` is not valid PHP** (the whole file is a Markdown document; the only `php -l` failure in `app/`; found by the `PBDIGIT-271` H2 parser scan, which excludes it by name) | — | `CLOSED` · **duplicate of `PBDIGIT-70`** (2026-10-11) |
| [`PBDIGIT-273`](PBDIGIT-273-verifyvoterslug-hardening.md) | **`VerifyVoterSlug` voter-slug guard** (V-1 auth, V-2 route type, V-3 owner, V-5 revoked, V-4 expiry incl. fixed lifetime from `created_at`, V-6 election / organisation; safe logging; database-free tests green, request-path tests written not run) | — | `IN REVIEW` · committed `6d3b9e1e0` (not pushed); merge to main and deploy only with `PBDIGIT-274` |
| [`PBDIGIT-274`](PBDIGIT-274-voter-slug-lifecycle-fixed-lifetime-and-replacement.md) | **Voter-slug lifecycle** (fixed 30 min from creation, no in-place extension, same slug through all steps, replacement by a new row with a partial unique index, election-specific issuance, concurrency) | — | `OPEN` · next; plan required |
| [`PBDIGIT-275`](PBDIGIT-275-voting-request-path-authorisation-gaps.md) | **Voting request-path authorisation gaps** (no `auth` on real slug routes, demo-session eligibility bypass, one-way type check / HTTP 500 mismatches, demo steps not ordered) | — | `OPEN` · not authorised |
| [`PBDIGIT-276`](PBDIGIT-276-voter-slug-defects-and-log-hygiene.md) | **The exception renderer logs the raw voter slug** (`bootstrap/app.php:219` logs the URL containing the slug; security, verified in code) | — | `OPEN` · not authorised |
| [`PBDIGIT-277`](PBDIGIT-277-voter-slug-correctness-defects.md) | **Voter-slug correctness defects** (`vote_completed` column doesn't exist; `VoterSlug::isExpired()` missing but called; demo-type check mismatch; recovery slugs for demo elections) | — | `OPEN` · not authorised |
| [`PBDIGIT-278`](PBDIGIT-278-voter-slug-documentation-test-and-ops-hygiene.md) | **Voter-slug documentation, test and operations hygiene** (stale route comment, broken test route, factory gaps, unscheduled cleanup command; one claim refuted) | — | `OPEN` · not authorised |
| [`PBDIGIT-279`](PBDIGIT-279-receipt-code-failure-absorbed.md) | **A failed receipt-code write is absorbed; the vote counts without a receipt** (savepoint in `firstOrCreate`; voter sees success; from 274 S9a, F-A) | — | `OPEN` · not authorised |
| [`PBDIGIT-280`](PBDIGIT-280-console-vote-writers-and-delegated-voting.md) | **Console commands that write votes have no environment guard; delegated-voting route undecided** (7 commands, P4; `POST /deligatevotes` statically broken, P3; from 274 plan section 8) | — | `OPEN` · not authorised |
| [`PBDIGIT-281`](PBDIGIT-281-voter-slug-history-retention-policy.md) | **Retention and erasure policy for voting-link history** (history now never hard-deleted; parent hard deletes cascade; from 274 O-12) | — | `OPEN` · needs a business / legal decision |
| [`PBDIGIT-282`](PBDIGIT-282-vote-confirmed-email-after-commit.md) | **Send the "vote confirmed" email only after the vote is saved** (sent inside the transaction; reproduced by a test; from 274 O-15; **2026-10-14:** also holds the network lock during the email, the PBDIGIT-286 BL-EMAIL item) | — | `OPEN` · not authorised |
| [`PBDIGIT-283`](PBDIGIT-283-test-database-reset-guard.md) | **Tests may reset only a test database** (`Tests\TestCase` ran `migrate:fresh` on whatever was connected; two `--no-configuration` runs wiped `publicdigit` on 2026-10-13; guard + architecture test) | — | **`DONE` (2026-10-14)** · guard `772d06a5d` + remediation R1–R6 · closure `docs/publicdigit/reviews/2026-10-14-pbdigit283-closure-record.md` · follow-ups `PBDIGIT-290`..`293` |
| [`PBDIGIT-284`](PBDIGIT-284-vote-hash-links-vote-to-voter.md) | **The vote hash can link a vote to its voter** (`vote_hash` built from `code.id` + code value + cast second with an empty, undefined `app.vote_salt`; anonymity) | — | `OPEN` · not authorised · **highest priority** |
| [`PBDIGIT-285`](PBDIGIT-285-ipv6-whitelist-lets-every-client-through.md) | **An IPv6 or malformed whitelist entry exempts every IPv6 client** (`ip2long` false → `0 === 0`; no server-side validation) | — | `OPEN` · not authorised |
| [`PBDIGIT-286`](PBDIGIT-286-one-visible-ip-limit.md) | **The constitutional IP limit is hidden, effectively 3, and not controlled by the admin switch** (`attested` hard-coded false; switched by `network_binding_strategy`) | — | `OPEN` · needs a PO decision |
| [`PBDIGIT-287`](PBDIGIT-287-proxy-trust-and-client-ip.md) | **Every proxy is trusted, so the client IP can be spoofed** (`trustProxies(at: '*')`; exposure depends on deployment) | — | `OPEN` · not authorised |
| [`PBDIGIT-288`](PBDIGIT-288-ip-limit-enforcement-and-ip-data.md) | **IP limit enforcement gaps, visibility and IP data minimisation** (no IP check at cast, per-voter lock only, code-entry IP counted, IPv6 per address, audit, retention, dead code) | — | `OPEN` · not authorised |
| [`PBDIGIT-289`](PBDIGIT-289-opened-election-without-end-time-reopens-settings.md) | **An opened election without a voting end time falls back to SetupNomination, and its security settings become editable again** (`isVotingOpen` false on an empty end; `isLocked()` false; from the PBDIGIT-286 review) | — | `OPEN` · not authorised |
| [`PBDIGIT-290`](PBDIGIT-290-committee-handler-binding-queries-on-resolution.md) | **Resolving the committee-creation handler queries the database, so constructing its controller does too** (`AppServiceProvider` binding reads `governance_level_definitions`; `gatherMiddleware()` reaches it; from PBDIGIT-283 R1) | — | `OPEN` · not authorised |
| [`PBDIGIT-291`](PBDIGIT-291-outbox-adapter-test-weak-assertion.md) | **An outbox adapter test passes on any database failure, so it proves little** (`OutboxEventAdapterTest` set-tenant case swallows every `Throwable`; from PBDIGIT-283 R4) | — | `OPEN` · not authorised |
| [`PBDIGIT-292`](PBDIGIT-292-hard-coded-testing-connection-and-tracked-password.md) | **A hard-coded `testing` database connection carries a plaintext password in a tracked file** (`config/database.php:140-150`; credential review, rotation if warranted, removal; from PBDIGIT-283 R6) | — | `OPEN` · not authorised · **security** |
| [`PBDIGIT-293`](PBDIGIT-293-db-url-not-neutralised-or-pinned.md) | **`DB_URL` (Laravel 11's URL variable) is neither neutralised nor pinned by the test-database safety layers** (read by the framework-merged `mariadb` entry; R3 fails closed; from the first PBDIGIT-283 R6 negative run) | — | `OPEN` · not authorised |
| [`PBDIGIT-294`](PBDIGIT-294-test-schema-and-test-data-drift.md) | **Tests use database columns and settings that the current schema doesn't have** (`users.is_voter` 196, MySQL `foreign_key_checks` 99, NOT NULL `organisation_id`, integer ids for UUIDs; from PBDIGIT-283 R6 positive) | — | `OPEN` · not authorised |
| [`PBDIGIT-295`](PBDIGIT-295-postgres-tests-lack-per-test-isolation.md) | **Tests on PostgreSQL share data, because the per-test transaction is switched off** (`TestCase::beginDatabaseTransaction()`; 158 unique violations; from PBDIGIT-283 R6 positive) | — | `OPEN` · not authorised |
| [`PBDIGIT-296`](PBDIGIT-296-tests-out-of-step-with-code.md) | **Tests call classes, methods and routes that the code no longer has** (missing classes 86, `HasMany::attach()` 103, Type/ArgumentCount errors 164, HTTP 302 vs 422/201; from PBDIGIT-283 R6 positive) | — | `OPEN` · not authorised |
| [`PBDIGIT-297`](PBDIGIT-297-phpunit-error-handler-risky-notices.md) | **About 1,800 tests are marked "risky" for changing PHP's error handlers** (PHPUnit 11 / Laravel `HandleExceptions`; baseline unknown; from PBDIGIT-283 R6 positive) | — | `OPEN` · not authorised |
| [`PBDIGIT-298`](PBDIGIT-298-php-memory-limit-for-test-suites.md) | **The Feature suite runs out of memory under PHP's default 128 MB limit** (peak 144.5 MB; `phpunit.xml` sets no `memory_limit`; from PBDIGIT-283 R6 positive) | — | `OPEN` · not authorised |
| [`PBDIGIT-299`](PBDIGIT-299-svg-logo-audit-after-laravel-12.md) | **Audit stored SVG logos and images now that Laravel 12 rejects SVG uploads** (3 endpoints list `svg`; PO accepted the rejection as security tightening; from the Laravel 12 upgrade ADR) | — | `OPEN` · not authorised |
| [`PBDIGIT-300`](PBDIGIT-300-vote-ids-encode-creation-time.md) | **Vote and result ids encode their creation time** (anonymity gap A4; UUIDv7 ms on Laravel 12, `orderedUuid` on 11; characterization K7; priority raised by the PO) | — | `OPEN` · not authorised |
| [`PBDIGIT-301`](PBDIGIT-301-remaining-composer-security-advisories.md) | **12 Composer security advisories remain** (php-jwt, phpseclib, phpunit, psysh, symfony/yaml; 65 → 12 after the Laravel 12 upgrade) | — | `OPEN` · not authorised |
| [`PBDIGIT-302`](PBDIGIT-302-mail-transport-tls-policy.md) | **Mail transport TLS policy and the misleading `verify_peer` block** (STARTTLS opportunistic on 587; port 465 used for SES; check symfony/mailer 7.4) | — | `OPEN` · not authorised |

### Constitutional Governance Runtime migration — TODO (`PBDIGIT-76` … `PBDIGIT-157`)

**Full specifications, dependency graph and Definition of Done:** [`2026-09-30-constitutional-runtime-pbdigit-backlog.md`](../reviews/2026-09-30-constitutional-runtime-pbdigit-backlog.md) (from the [reality audit](../reviews/2026-09-30-constitutional-runtime-reality-audit.md) and the [migration readiness review](../reviews/2026-09-30-constitutional-runtime-migration-readiness.md)). **Working path (Jira-ready):** [execution map](../implementation/2026-10-01-constitutional-runtime-execution-map.md): master index, three working paths, decision matrix, dependency graph, waves, execution protocol and per-ticket execution cards. **Where to start and in which order:** [implementation runbook and ticket sequence](../implementation/2026-10-01-constitutional-runtime-implementation-sequence.md) (15 computed waves). **First implementations, one per track:** first Constitutional Governance implementation (Track A1, Admin Constitutional Governance) **`PBDIGIT-139` part A**; first Voting Participation implementation (Track A2, Voting Participation Governance) **`PBDIGIT-76`**; first general repository implementation (Track B, General repository/freeze work) **`PBDIGIT-133`**. There is no single overall first ticket. **None is authorised.** Decision tickets (`PBDIGIT-90`…`100`, `138`, `141`, `150`) are the PO/ARB's. Related existing items: `PBDIGIT-35` · `37` · `45` (owns per-IP retirement; gates `117`/`121`) · `49` · `65` · `68` · `69`.

| ID | Story | Epic | Status |
|---|---|---|---|
| `PBDIGIT-76` | C-1 — Repair voting feature-test fixtures for current schema (retired columns) | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-77` | C-1 — Repair voting feature-test tenancy, factory, constructor and route fixtures | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-78` | C-1 — Repair voting-active lifecycle fixtures to current lifecycle rules | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-79` | C-2 — Characterize the voting entry path at the HTTP boundary | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-80` | C-2 — Characterize the ballot, verify and store path at the HTTP boundary | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-81` | C-3 — Pin voting-flow control defects X-1, X-2, X-3 | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-82` | C-3 — Pin voting-code secrecy defects X-4, X-5 | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-83` | C-3 — Pin participation-bypass defects X-6, X-7 | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-84` | C-3 — Pin configuration and schema defects X-8…X-12 | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-85` | X — X-3 — Release the vote lock and roll back on the store IP-denial branch | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-86` | X — X-4 — Stop logging voting codes and compare codes in constant time | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-87` | X — X-5 — Remove plaintext debug_code exposure outside local environments | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-88` | X — X-6 — Remove the session demo-flag bypass from real-election gates | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-89` | X — X-10 — Fix the two voting-path TypeErrors (whitelist decode, UUID user id) | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-90` | H — H-1 — Which IP is the canonical 'verified IP' (rules 1 and 4)? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-91` | H — H-2 — What is the registered device fingerprint (rule 5)? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-92` | H — H-3 — Does trust level scale the per-IP vote limit as law, or is the limit exactly N? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-93` | H — H-4 — Rule 7 semantics: which record counts prior votes per IP, and is the N-th or the (N+1)-th vote refused? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-94` | H — H-5 — Where does the participation action registry live? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-95` | H — H-6 — What is the single platform-organisation rule? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-96` | H — H-7 — Which record is the canonical 'already voted' fact, and which membership predicate is canonical? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-97` | H — H-8 — Do overlays ever influence CAST_VOTE, or remain audit-only observations? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-98` | H — H-9 — Is demo voting in scope for CAST_VOTE? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-99` | H — H-10 — Is ORM-level constitutional immutability sufficient, or is DB-level protection required? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-100` | H — H-11 — How are existing elections (frozen with DB-default law) treated? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-101` | L-1 — One canonical constitution representation, schema version and hash | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-102` | L-1 — Author constitutional law at election creation and freeze it | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-103` | L-1 — Enforce constitutional immutability and verify the hash on load | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-104` | L-1 — Establish constitutional law for existing elections | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-105` | E-1 — Typed evidence: membership, lifecycle and already-voted facts | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-106` | E-1 — Typed evidence: network, device and officer-verification facts with provenance | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-107` | E-1 — Typed evidence: ballot/code session facts under the constitutional protocol | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-108` | V-1 — Evaluators: network (composable binding + limit), device comparison, verification | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-109` | R-1 — R-1a — resolver mechanics: Resolver evaluates every policy and returns a stateless trace | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-110` | R-1 — R-1b — CAST_VOTE integration: Typed CAST_VOTE context and participation action registry | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-111` | R-1 — R-1b — CAST_VOTE integration: Register the CAST_VOTE resolver policy set | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-112` | S-1 — Persist a CAST_VOTE decision record for every evaluation | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-113` | S-1 — Shadow-evaluate CAST_VOTE in the live voting path | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-114` | S-1 — Replay harness: reproduce stored decisions with DB access disabled | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-115` | P-1 — Runtime parity harness at the HTTP boundary with persisted divergences | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-116` | P-1 — Execute the 14 critical parity paths and obtain approval of expected divergences | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-117` | D-1 — Switch the vote-submission decision to CAST_VOTE | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-118` | D-1 — Drive the entry and ballot pages from the CAST_VOTE decision | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-119` | D-2 — Delete the legacy trust-decision pipeline | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-120` | D-2 — Delete legacy participation middleware | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-121` | D-2 — Delete duplicate controller participation gates and per-IP authorities | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-122` | D-2 — Delete dead legacy code and retire tests of non-existent classes | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-123` | CI — Bring election security zones under a blocking CI job | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-124` | CI — CI rule 1 — Only the resolver and registered policies may create CapabilityDecision | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-125` | CI — CI rule 2/4 — Canonical zones cannot import legacy authority; policies cannot consume policy outcomes | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-126` | CI — CI rule 3 — Canonical zones cannot read mutable configuration or live models | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-127` | CI — CI rule 5 — Snapshots cannot evaluate; middleware cannot derive participation | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-128` | CI — CI rule 7 — Forbidden procedural vocabulary in canonical governance code | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-129` | CI — CI rule 8 — Constitutional immutability and replay sufficiency run in CI | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-130` | DOC — Correct false completion claims in historical documents | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-131` | DOC — Consolidated Constitutional Governance Runtime developer guide | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-132` | V1 — Constitutional Governance Runtime v1.0 freeze | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-133` | CI — Temporal-determinism audit scanner flags `validate(` as a `date()` call | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-134` | DOC — Resolve the `PBDIGIT-69` numbering collision | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-135` | C-3 — Characterize the non-slug `POST /votes` route and route-file loading | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-136` | C-3 — Characterize `voter_slugs.vote_completed`, which no code writes | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-137` | D-2 — Dispose of the unwired divergence-observation infrastructure | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-138` | H — H-12 — Should voting codes be stored hashed at rest? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-139` | GOV — Repair capability-suite and suspension-suite test drift | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-140` | GOV — Characterize the admin-action authorities at the HTTP boundary | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-141` | H — H-13 — Who owns governance role authority, and what is the one role vocabulary? | `EPIC-03` | **DECISION REQUIRED** |
| `PBDIGIT-142` | GOV — Suspend and resume bypass ConstitutionalTransitionGuard | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-143` | GOV — Suspend/resume governance audit is incomplete (resume unrecorded; suspend lacks prior stage) | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-144` | GOV — Resolver role and precondition policies for admin actions | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-145` | GOV — Remove the lifecycle engine's second capability matrix | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-146` | GOV — Declare overlay behaviour in the constitution and fix the placeholder resume target | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-147` | GOV — Frontend renders governance capabilities only | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-148` | GOV — Lifecycle derivation cannot be recomputed for a given instant | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-149` | GOV — Governance lifecycle end-to-end suite through HTTP | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-150` | H — H-14 — Is suspension a lifecycle state or an operational overlay? | `EPIC-05` | **DECISION REQUIRED** |
| `PBDIGIT-151` | GOV — Align Laravel ElectionPolicy gates and controller state inference with the resolver | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-152` | GOV — Route-level operation capability matrix duplicates the resolver | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-153` | GOV — Reproduce the reported governance POST regression (activate/suspend/resume) | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-154` | GOV — `updateDates` edits voting dates in any lifecycle state, bypassing every governance gate | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-155` | GOV — One constitutional action vocabulary: RULES, ElectionAction enum and ElectionActions.ts drift | `EPIC-03` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-156` | CI — Separate business-policy expectations from the Architecture suite | `EPIC-05` | `TODO — NOT AUTHORISED` |
| `PBDIGIT-157` | GOV — Repair election-management feature-test drift (activation and management suites) | `EPIC-03` | `TODO — NOT AUTHORISED` |

### PublicDigit delivery TODO from the completeness audit — (`PBDIGIT-159` … `PBDIGIT-208`)

> **Delivery baseline frozen at PBDIGIT-208 (PO, 2026-10-01). New IDs only for a delivery obligation shown not to be covered by PBDIGIT-00…208.**
>
> `PBDIGIT-00`…`208` is the authoritative current delivery inventory. **Added since, under the rule:** `PBDIGIT-209` (fee payment cannot be recorded), `PBDIGIT-210` (participant import roles), `PBDIGIT-211` (scheduled jobs see no tenant data; architecture decision first), `PBDIGIT-212` (timeline pages forbidden to everyone). The existing-story search is in each file. **Also added since:** `PBDIGIT-213` (Election-Only voter admission v1, PO-authorised) and the defects/stories it surfaced, `PBDIGIT-214`…`222`; `PBDIGIT-223`…`233` (Phase 1 voting-integrity and anonymity characterization findings, [evidence report](../reviews/2026-10-05-phase1-characterization-evidence-report.md)); `PBDIGIT-234` (IP binding reads a missing column; existing-story search in the file); `PBDIGIT-235` (soft-deleted Code still usable; existing-story search in the file); `PBDIGIT-236` (regional ballot authority, D6) and `PBDIGIT-237` (VE-01 ballot integrity at the final cast) — existing-story search in each file; `PBDIGIT-238` (CodeController real-election boundary; existing-story search in the file); `PBDIGIT-239` (entry authorization `forEntry()`) and `PBDIGIT-240` (deferred Stage C — security assessment contract) — existing-story search in each file; `PBDIGIT-241` (voter ↔ ballot linkage and receipt secret in logs; existing-story search in the file). The Strategic Knowledge Traceability Audit (Track 2) creates **no** new ID: an apparently uncovered obligation is recorded there as a *candidate* and reviewed separately afterwards.

Every remaining PublicDigit delivery TODO from the engineering backlog, the ARB programme register, the OperatingCore track and the product gaps, with its source linked. Specifications, classification and exclusions: [`2026-10-01-pbdigit-completeness-audit.md`](../reviews/2026-10-01-pbdigit-completeness-audit.md). `195`…`204` were added by the [story integrity review](../reviews/2026-10-01-pbdigit-story-integrity-review.md), and `205`…`208` by the [final closure audit](../reviews/2026-10-01-pbdigit-story-integrity-final.md) (**baseline: CLOSED WITH KNOWN GOVERNANCE ITEMS**). The review also leaves **decision D-1** open: 10 of these stories are engineering or documentation work that this README's own rule (line 6) places outside the product backlog. **None is authorised for implementation.**

| ID | Story | Source | Status |
|---|---|---|---|
| `PBDIGIT-159` | Programme status integrity: the health table, the epic table and the NEXT ACTION describe July 2026, not today | `ENG-015` | 🔴 OPEN — first in sequence |
| `PBDIGIT-160` | The merge gate cannot pass while an authorised RED-first increment is open (decision) | `ENG-013` | 🟡 DECISION REQUIRED (ARB) |
| `PBDIGIT-161` | Greenfield PHPStan regression: 22 errors in Election OperatingCore | `ENG-014` | 🔴 OPEN — 2 fixable, 20 bound to held work |
| `PBDIGIT-162` | Risky notices in `GreenfieldCore` have grown 57 → 112 with an unestablished cause | `ENG-012` · `F-7C-6` | 🟡 PLAN AUTHORISED (R-95), implementation not |
| `PBDIGIT-163` | Shared Infrastructure (Outbox/Inbox) is outside static analysis and fails PHPStan max | `ENG-002` | 🔴 OPEN |
| `PBDIGIT-164` | First mutation-score ratchet on the correction loop (MSI 50 %) | `ENG-004` | 🔴 OPEN — after 160 |
| `PBDIGIT-165` | Finance tests crash: Membership and Shared each define their own `TenantId` | `AD-006` | 🔴 OPEN — re-verified 5/5 fail |
| `PBDIGIT-166` | A test class name does not match its file, so its tests never run | `F-7C-1` | 🔴 OPEN — re-verified |
| `PBDIGIT-167` | Broken unit and feature tests in the Membership, Elections, Geography and Governance contexts, and in legacy middleware | `F-7C-4` · `F-7C-5` | 🔴 OPEN — re-measure first |
| `PBDIGIT-168` | The engineering platform (gate scripts, pre-push, CI role gate) has no developer guide | `ENG-006` | 🔴 OPEN |
| `PBDIGIT-169` | Documentation indexes promise 30 documents that were never written | `ENG-010` | 🔴 OPEN |
| `PBDIGIT-170` | Programme documentation consolidation (Stream B), trigger-based | `ENG-005` | ⏸️ DORMANT — trigger-based |
| `PBDIGIT-171` | Domain Model Catalogue: recommended, never written | BACKLOG Parked (docs) | ⬜ OPEN — Low |
| `PBDIGIT-172` | The voter journey and the election lifecycle use disjoint vocabularies | P-4 | 🔴 OPEN — discovery first |
| `PBDIGIT-173` | Deployment documentation: the P-6 claim "none exists" is no longer literally true, and nothing verifies what exists | P-6 · health row | 🔴 OPEN — verification |
| `PBDIGIT-174` | WP-3B: the determination issuance still mints a fresh provenance chain instead of continuing the challenge's | WP-3B · R-67 · R-71 | ⛔ BLOCKED — ownership unruled |
| `PBDIGIT-175` | WP-4C-1 D3/D4: `AdjudicationFailureDeclared` is not in the Event Catalog and is never published | WP-4C-1 D3/D4 · R-97 | ⏸️ HELD — Board E1/E2 |
| `PBDIGIT-176` | WP-4C-2: the Contestation Domain Owner has not answered Q1–Q4 | WP-4C-2 · R-90 · R-100 | ⛔ BLOCKED — Domain Owner decision |
| `PBDIGIT-177` | WP-4D: no authority can deliver an adjudication decision (intake port + interim adapter unbuilt) | WP-4D · R-72 · R-73 | ⛔ NOT AUTHORISED — after 178 |
| `PBDIGIT-178` | The issuance path writes the determination and the outbox row in two separate transactions (R-91) | R-91 · R-92 | ⛔ UNAUTHORISED — Decision Authority |
| `PBDIGIT-179` | PM-6's issuance-confirmation half is allocated to no work package | PM-6 · R-82 | ⬜ OPEN — unallocated |
| `PBDIGIT-180` | `enforceHorizon()` is not isolated per process (ER-08) | ER-08 · R-95 | 🟡 PLAN AUTHORISED, implementation not |
| `PBDIGIT-181` | `ChallengeRaised` publication: the adopted path (R-75) is annotated as prohibited by accepted architecture | R-75 | ⛔ BLOCKED — contradictory record |
| `PBDIGIT-182` | WP-8: the correction loop has never been validated end to end over the real path | WP-8 · R-79 | ⏸️ DEFERRED |
| `PBDIGIT-183` | GREEN-5: report-period expiry (UC-4) is pinned RED and unimplemented | EM-IMPL-002 GREEN-5 | ⛔ DECISION PENDING (A/B/C) |
| `PBDIGIT-184` | GREEN-6: recording a committee's constitution (UC-5) is pinned RED and unimplemented | EM-IMPL-002 GREEN-6 | ⛔ BLOCKED — `Q-UC5` |
| `PBDIGIT-185` | GREEN-7: the four operating-core query services (UQ-1…UQ-4) are pinned RED and unimplemented | EM-IMPL-002 GREEN-7 | ⛔ HELD |
| `PBDIGIT-186` | ADR-1 absent-aggregate normalization: the required domain slice does not exist, so the Rule-8 gate is BLOCKED | ADR-1 Rule-8 · BND-1 | ⛔ BLOCKED |
| `PBDIGIT-187` | EM-DOM-001 Acts C and D (operational-status adapter and caller) are not authorised | EM-DOM-001 Acts C/D | ⬜ OPEN — not authorised |
| `PBDIGIT-188` | A binding RED pin's docblock says the ADR "decision block is blank"; it has been decided since 2026-08-18 | CONTEXT-publicdigit 2026-09-30 | ⬜ OPEN — comment only |
| `PBDIGIT-189` | Reconcile the programme epic numbering: EPIC-003/004 were reused, EPIC-005/006 are undefined | BACKLOG epic table | 🟡 DECISION REQUIRED (ARB) |
| `PBDIGIT-190` | Roadmap epic: Voting migration and verifiability (formerly EPIC-003) | epic: Voting | ⬜ NOT OPENED |
| `PBDIGIT-191` | Roadmap epic: Appointment / Governance — delegates, mandates, authority chains (formerly EPIC-004) | epic: Appointment | ⬜ NOT OPENED |
| `PBDIGIT-192` | Roadmap epic: Read Models and Public Transparency (formerly EPIC-005) | epic: Read Models | ⬜ NOT OPENED |
| `PBDIGIT-193` | Roadmap epic: Election/Lifecycle migration of the legacy operational contexts (EPIC-006) | epic: legacy migration | ⬜ NOT OPENED |
| `PBDIGIT-194` | Reference value objects (`*Ref`) were never reviewed for consistent naming and shape | `ENG-003` | ⬜ OPEN — Low |
| `PBDIGIT-195` | Creating a committee through the canonical endpoint returns 201 and persists nothing | inline sweep (`CommitteeRepository.php:16`) | 🔴 OPEN — defect |
| `PBDIGIT-196` | No one can file a challenge: the correction loop has no product entry point | roadmap §1 finding 1 | 🔴 OPEN — PO contract first |
| `PBDIGIT-197` | No determination ever becomes final: the finality evaluator was never built | roadmap §0(f) · WP-6 closure | ⛔ BLOCKED — integration contract |
| `PBDIGIT-198` | The adjudication horizon is never enforced in production: nothing calls `enforceHorizon()` | WP-6 plan `:84`/`:164` | 🔴 OPEN — ARB check vs R-91 |
| `PBDIGIT-199` | WP-6B: Contestation never reacts to `AdjudicationExpired` | WP-6 closure · Decision C | ⏸️ DEFERRED |
| `PBDIGIT-200` | Evidence-demand deadlines: the domain has no `Demand` concept | roadmap §0(e) · WP-6 closure | ⛔ BLOCKED — modelling gap |
| `PBDIGIT-201` | Starting the vote still hides a valid voter's membership behind the ambient tenant (third site of `PBDIGIT-65`) | session-3 record B-2 | 🔴 OPEN — defect, re-verified |
| `PBDIGIT-202` | Full Membership voter import always fails: `ElectionMembership::bulkAssignVoters()` does not exist | session-3 record §4.3 | 🔴 OPEN — defect, re-verified |
| `PBDIGIT-203` | Voter import integrity: swallowed row errors, non-atomic writes, no assignment audit | session-3 record B-6 | ⛔ NOT AUTHORISED — grant re-confirmation |
| `PBDIGIT-204` | ADR-2 restoration provenance: the domain cannot say why an election was restored | ADR-2 Rule-8 gate Q1–Q5 | ⛔ BLOCKED — domain slice first |
| `PBDIGIT-205` | A raised challenge can be admitted but never dismissed or put under investigation | ADR-T20 · WP-5 plan | ⬜ OPEN — unallocated |
| `PBDIGIT-206` | A challenge never lapses: the ruled timeout has no trigger | `Challenge::lapse()` · Round 50-07 | ⬜ OPEN — unallocated |
| `PBDIGIT-207` | 99 write-path feature tests assert only a success status, so they cannot detect a lost business outcome | closure audit §6.3 | 🔴 OPEN — verification |
| `PBDIGIT-208` | 1,345 failing tests in 213 files belong to no story: the legacy test estate outside the greenfield core | closure audit §7 (full run) | 🔴 OPEN — verification, classify first |

> **Adjudication re-verification, 2026-10-11** (read-only; the frozen rows above are unchanged): [`reviews/2026-10-11-adjudication-readiness-review-and-d03-scope.md`](../reviews/2026-10-11-adjudication-readiness-review-and-d03-scope.md).
> - **Confirmed still open in code:** `PBDIGIT-174`, `175`, `177`, `178` (R-91: `CoordinatorIssuanceRequest` gets the bare coordinator), `181` (T-2: challenge content not persisted), `182`, `196`, `197`, `198`, `205`, `206`.
> - **`PBDIGIT-191` (the Governance authority contract)** is still unopened: Adjudication imports nothing from Governance, and `IssuedByAuthority` only checks the value isn't empty.
> - **No code changed** in the Adjudication, Contestation or Election folders since `216552aa7` (2026-08-19).
> - **Next:** PO scope decision P-0, then the ARB ruling on Q1–Q7 (`PBDIGIT-211`). The drafts T-3 and T-5 still await IDs.
> - **D.0.3** belongs to the voting-security track (`PBDIGIT-45`, `240`), not to Adjudication.

---

## 🚧 [PBDIGIT-00](PBDIGIT-00-verify-the-journey-end-to-end.md) — Verify the journey end-to-end *(gates every epic above)*

| | |
|---|---|
| **Customer goal** | *"Prove that one complete journey works before anything is improved."* |
| **Why it is first** | every story below is evidenced in code and **none has been observed working**. Verification converts 24 inferences into facts at near-zero cost; building on unverified ground does not |
| **Evidence that already exists** | `tests/Feature/Phase6EndToEndIntegrationTest.php` · `tests/Feature/RealWorldVotingFlowTest.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php` · demo mode (`organisation_id = NULL`) · `docs/DEMO_ELECTION_GUIDE.md` |
| **Verification** | (a) run the three E2E suites against a provisioned Postgres; (b) walk the Level 0 journey once manually in demo mode, recording at each step: page · controller · DB rows · events · log files |
| **Status** | ✅ **(a) DONE · (b) DONE — 2026-08-06.** Both verifications ran. **The result is negative, and that is the deliverable** |
| 🔴 **What (a) established** | **There is NO automated end-to-end coverage of a complete election.** 21 tests: **10 errored before asserting anything**, 1 failed, 10 passed — and the 10 that pass test the *middleware chain*, not an election. → `PBDIGIT-35` · `PBDIGIT-36` · `PBDIGIT-37` |
| 🔴 **What (b) established** | **The journey was walked end to end for the first time. Steps 1–4 worked; the vote was never saved.** The security middleware chain and step-order enforcement work correctly. → `PBDIGIT-38` · `PBDIGIT-39` · `PBDIGIT-40` · `PBDIGIT-41` |
| ✅ **Since resolved** | **`PBDIGIT-38` is FIXED and VERIFIED (2026-08-06): a vote now persists and the journey completes.** This record is historical and is **not** rewritten — a closed verification is evidence with a date on it. Re-walking after a fix verifies *that fix*, not `PBDIGIT-00` |

**Until `PBDIGIT-00` completes, no story may be marked `VERIFIED`.** **It has now run — and nothing may be marked `VERIFIED`, because the journey does not complete.** `PBDIGIT-38` is the gate that replaced it.

**One box did close as a side effect:** `GET /` returns **200 / 89 550 bytes**, so the home page renders — `PBDIGIT-27`'s last unchecked item.

---

## ID conventions

**Stories: `PBDIGIT-nn`** — one flat sequence across the whole product backlog (`PBDIGIT-00`…`PBDIGIT-24` today; the next epic continues from `PBDIGIT-25`). Uppercase, matching the repository's existing ID style (`PB-003`, `ENG-012`, `ES-004`, `ADR-T20`).
**Epics: `PBDIGIT-EPIC-nn`.**
**Never reuse a number**, even for a cancelled story — an ID names one thing permanently (precedent: the `R-89`/`R-90` collision recorded in the platform rulings register).
**`PBDIGIT-nn` ≠ `PB-nnn`** — different backlogs, different authorities.

## How a story is written

Every story answers one question: **"Could a customer complete this step today?"** — and carries its own evidence.

| Section | Contains |
|---|---|
| **Customer goal** | one sentence, in the customer's words. No framework nouns |
| **Business steps** | what the customer does, in order |
| **Route → code** | business step → route → controller → service/domain → model → events (`file:line` where known) |
| **Business rules evidenced** | the rules the code actually enforces |
| **Verification** | the concrete actions that would prove it works |
| **Known findings** | product gaps (`P-n`) and architecture debt touching this story |
| **Status** | see legend |

## Status legend

| Status | Meaning |
|---|---|
| `IMPLEMENTED — NOT VERIFIED` | code + routes (+ tests) evidenced; never observed working end-to-end |
| `PARTIAL` | some steps evidenced, others `Evidence not found` |
| `VERIFIED` | a human ran it and recorded the result |
| `BLOCKED` | waiting on a named dependency |

## Product Capability Review — 9 phases, never reversed

**Name (proposed, 2026-08-06):** *Product Capability Review*. Earlier working names — "Architecture Discovery", "Capability Review" — no longer describe it: every application now starts from the customer, measures a business capability, separates product issues from technical debt, and ends in evidence-based product readiness. **The name is proposed for use at promotion time; the documents already written keep their titles** (renaming them would break the record of the method's evolution).

Reusable for **any** capability (Elections, Membership, Finance, Appointments, …) — and, because it starts from the customer rather than from aggregates, for domains other than governance. Established by `PBDIGIT-29`; **customer-first, business-second, framework-last.**

| Phase | Question it answers | Output |
|---|---|---|
| **0 Customer journey** | what is the customer *trying to accomplish*? goals and expectations — **not** rules, **not** events, **no code** | the human story: "I want my association on the platform… I come back tomorrow and expect to be where I left off" |
| **1 Business lifecycle** | which **business events** happen, in order? | the yardstick everything else is measured against |
| **2 Business events** | which business events exist, who owns each consequence? | event table: class exists? · dispatched? · **listened to?** · who actually carries the consequence |
| **3 Route map** | which routes correspond to each business step? | step → route |
| **4 Controller / implementation map** | route → controller → service → domain → model → persistence | step-by-step mapping with `file:line` |
| **5 DDD analysis** | is there one authority? is the concept modelled? is responsibility duplicated? | interpretation, kept separate from facts |
| **6 Code quality** | duplication · dead code · complexity — **only where it affects this capability** | findings, not a general audit |
| **7 Runtime verification** | does a customer actually get through it? | observed behaviour, or an explicit *not verified* |
| **8 Improvement backlog** | what follows? | new `PBDIGIT-nn` items — never prose advice |

### Every review closes with these four artifacts

*(plus a **Method Assessment** that must answer **"what surprised us?"** — an expected-vs-observed table. A surprise is evidence the method investigated rather than confirmed; its absence is a warning sign.)*

**1 · Business Outcome** — the finding in one customer sentence, not one engineering sentence:

```
Business Outcome

Today     the customer cannot return to the organisation they were working in.
Expected  the customer always returns to the organisation they last chose.
```

**2 · Business Rule Matrix** — five independent levels of confidence:

`rule · designed · implemented · verified · automated test · evidence`

It is the bridge from discovery to testing. It separates *"code exists"* from *"it runs"* from *"a regression would be caught"* — and it asks of each test **which business rule it actually protects**, not merely whether a test exists.

**3 · Capability Verdict + Findings Table** — the thirty-second read, then what happens next:

*Capability Verdict* — one line per area: `Ready for verification` · `PARTIAL` · `NOT OPERATIONAL` · `IMPOSSIBLE`. **Never "Ready"** without runtime evidence.

| Finding | Class | Type | Priority | Confidence | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|---|
| *(one row)* | BROKEN · MISSING · INCONSISTENT | Product · Architecture · Technical | High/Med/Low | High/Med/Low | Yes / No / Maybe | Yes / No | Yes / No |

- **Class** encodes the **remediation path**, which Type does not: *BROKEN* = exists but cannot execute (wire it or delete it) · *MISSING* = wanted but never built (decide, then build) · *INCONSISTENT* = works, described more than once (consolidate).
- **Confidence** states how strongly the evidence supports the conclusion. Anything resting on an interpretation, or on someone else's estimate rather than a `file:line` fact, is **not High**.
- **Product findings lead with customer impact** — *"the customer cannot declare how their organisation is governed"*, not *"`activateGovernance` is unreachable"*. Technical findings stay technical.

**Product findings and Technical findings are never mixed.** A customer landing in the wrong organisation is a *product* finding; fifteen writers of a session key is a *technical* one. They compete for different attention and are prioritised differently.

**4 · Authorization Boundary** — mandatory, stated as a table of negatives:

```
Code changed          none
Architecture proposed none
Solutions recommended none
Status                STOPPED — awaiting <named decision>
```

It makes the commission's scope unambiguous *afterwards*, not only in intent — and it is the artifact that stops a review from quietly becoming a redesign.

### Three rules that make the method work

- **The route is a consequence of the business step, never the starting point.**
- **Facts · interpretation · decisions stay in separate sections.** State the evidence (*"no class of that name was found"*), then the reading of it (*"the concept appears implicit"*) — never one as the other.
- **Ask what a test protects, not whether one exists.** A green test can lock in the wrong behaviour (`PBDIGIT-29` §5, R5).
- **A discovery report describes reality; it never prescribes implementation.** Every finding states *what is true · why it matters to the business · what decision is required* — and stops. Naming a mechanism ("add these priorities", "attach this observer", "extract this responsibility") quietly moves a decision from the implementation story to the reviewer, and it is often wrong: `PBDIGIT-31` called one such remedy a "one-line fix" that turned out to need two changes, the first of which would have broken organisation creation. **Rank findings by impact and risk — that is the reviewer's job; choosing the mechanism is not.**
- **Business discovery finds the rules a product already implies; it never designs the product's future.** The moment a discovery story starts answering *"what would be a good product?"* instead of *"what does this product already require?"*, it has become design and must be split into an enhancement story. Warning sign, observed for real: a discovery draft writing *"this creates work"* — a business rule had begun driving architecture, when architecture exists to support the business. *(`PBDIGIT-30` B4 → `PBDIGIT-34`.)*
- **Lead with customer impact, not class names.** *"The customer may return to the wrong organisation for up to five minutes"* comes before `DashboardResolver`. Evidence follows the impact; it never replaces it.

### ⛔ This method is FROZEN

**Applied to three capabilities independently — the freeze's condition is now met:** **Election** ✅ (`PBDIGIT-29`) → **Organisation** ✅ (`../reviews/2026-08-06-organisation-capability-review.md`) → **Membership** ✅ (`../reviews/2026-08-06-membership-capability-review.md`).

**The planned validation cycle is COMPLETE. Evidence is now sufficient for a Decision Authority review** — *not* for promotion, which does not follow automatically from validation:

```
Validation complete  →  Evidence available  →  Decision Authority review  →  possible promotion
```

**Three applications inside one repository cannot demonstrate repository-independence** — that is exactly the question the Decision Authority must weigh, and `OQ-2` (where cross-product research lives) remains unruled. Evidence from application #3: high-value findings **without** any of the five parked refinements, and the method **refuted its own leading hypothesis**.

**Cross-capability patterns after three applications (observation only — nothing promoted).** Each is stated at the abstraction that explains **all three** sightings, not the symptom of the first:

| Pattern (abstracted) | Election | Organisation | Membership | Verdict |
|---|---|---|---|---|
| Event dispatched with no listeners, consequence hard-coded in the controller | ✅ | ✅ | ❌ | **refuted at 2/3** — a legacy-code trait, not a repository trait |
| **The business lifecycle has no single authoritative representation** | ✅ | ✅ | ✅ | **3/3** — repeated observation |
| **Multiple competing representations of one business concept** | ✅ | ✅ | ✅ | **3/3** — repeated observation |

*(Rows 2–3 were first phrased as "a declared state that cannot be reached" and "the same concept defined twice" — the symptoms of their first sighting, which could not explain Membership's competing-state-models form. Re-abstracted after application #3; recorded rather than silently substituted.)*

Three observations is *repeated observation*, not a standard (`ES-006.1`). The **refutation** is the most useful row: it shows the method discriminates rather than confirming what it looked for first.

## ⛔ The freeze, with an actual gate

**Honest record: the freeze has been declared and then breached four times** (Phase 0 + Business Outcome + Findings Table → "what surprised us" + pattern re-abstraction → Authorization Boundary + naming → Capability Verdict + Class + Confidence). Every round was individually justified. **That is what methodology inflation looks like from the inside** — nobody ever adds a phase for a bad reason.

**The cause is structural, and it is the same defect this method keeps finding in the code:** a rule that is *declared* but has no *enforcement point*. See the 3/3 pattern *"the business lifecycle has no single authoritative representation"* — our own process had exactly that shape.

**So the freeze now has a gate rather than an intention:**

| | |
|---|---|
| **Refinements may only be adopted** | at a **Method Retrospective**, never during or immediately after a review |
| **A retrospective may be held** | after **three further applications** (target capabilities: Finance · Appointment · Audit · Notification · Authentication — any three) |
| **Applications to date** | **3** — Election · Organisation · Membership |
| **Next retrospective eligible at** | **6 applications** |
| **In between** | refinement candidates are **parked in writing** (see the parked list above) and **not applied**, no matter how well justified |
| **Exception** | a refinement may be adopted mid-cycle **only** if a review could not be completed without it — a blocking defect, not an improvement |

**Promotion is further out still:** ~8–10 applications, then a Decision Authority review of whether the value is repository-independent. Validation ≠ promotion.

**Process status: ROUTINE USE — parked, not evolving.**

---

## Product gaps carried across epics

| # | Gap | Epic |
|---|---|---|
| **P-1** | no recorded end-to-end run | `PBDIGIT-00` |
| **P-2** | counting has no domain model — tally is an inline controller query | `EPIC-06` |
| **P-3** | `unpublish_results` is not a constitutional action | `EPIC-06` |
| **P-4** | voter journey ↔ lifecycle vocabularies disjoint | `EPIC-05`/`06` |
| **P-5** | voter-journey routes defined twice; `/vote/submit_seleccted` misspelled | `EPIC-05` |
| **P-6** | no deployment documentation | cross-cutting |

**Architecture debt is deliberately excluded from this backlog** (`L-1`, `L-2`, `E-1`, `E-4`, `E-5`, `R-1`, `R-4`, `C-1`, and the PB003 register `M-1`…`M-4`). It raises the cost of *changing* the software, not of *using* it — see `../reviews/2026-08-05-election-process-review-phase1.md`.

**Source reviews:** `../reviews/2026-08-05-election-product-readiness-overview.md` (product lens) · `../reviews/2026-08-05-election-process-review-phase1.md` (architecture discovery)

### 🅿️ Post-freeze refinement candidates — RECORDED, NOT APPLIED

Proposed during the review of application #2 (Organisation). **Deliberately not applied: the freeze forbids refinement before three applications.** Re-evaluate only after Membership, and only if the third application shows they are needed.

| # | Candidate | Rationale offered |
|---|---|---|
| C-1 | **Capability Summary** right after Phase 0 — purpose · primary actor · business value · success criteria · current status | a reader knows what the capability *is* before reading 20 pages |
| C-2 | **Capability Readiness** verdict — *can a customer use this today: YES / PARTIALLY / NO*, with "customer can / customer cannot" lists | one executive answer instead of inference across sections |
| C-3 | Split the confidence ladder further: **designed → implemented → reachable → used → verified** | the Organisation governance finding proves *implemented ≠ reachable* |
| C-4 | Structured **cross-capability pattern table** with a count column across all reviewed capabilities | accumulated evidence, not prose observations |
| C-5 | Rename/duplicate `Verified` as **`Observed`** — someone actually watched it happen, vs. a test asserting it | *(the proposer's own note: consider after more applications, not now)* |

**Do not apply these while the freeze stands.** If the Membership review produces high-value findings without them, that is evidence they are optional — which is itself worth knowing.

### 🅿️ Second parked set — proposed 2026-08-08 (Principal Architect / DDD discovery commission)

Proposed by the Product Owner at the review of the organisation-creation journey check ([report](20260808-create-organisation-and-visit-homepage-journey-report.md) · `PBDIGIT-63`). **Recorded verbatim in `.claude/sessions/2026-08-08.md`; NOT applied.**

| # | Candidate | Rationale offered |
|---|---|---|
| C-6 | **Start from the business outcome, never from "is it broken?"** — the latter frames discovery around implementation failure before the capability is defined | a journey is a business object; the route is evidence |
| C-7 | **Layered ownership analysis** — business outcome → capability → domain invariants → **decision ownership** → application → authorization → interface → persistence → verification, never inverted | prevents a technical mechanism being mistaken for the owner of a business decision |
| C-8 | **Explicit evidence classification** — `OBSERVED IN CODE` · `OBSERVED AT RUNTIME` · `TESTED` · `INFERRED` · `NOT VERIFIED` · `BUSINESS DECISION REQUIRED` · `MECHANISM NOT ESTABLISHED` | makes "statically wired" structurally impossible to report as "works" |
| C-9 | **Test-classification taxonomy** — `VALID` · `OBSOLETE` · `CONTRACT DRIFT` · `FIXTURE DRIFT` · `WRONG LAYER` · `IMPLEMENTATION-COUPLED` · `COVERAGE GAP` · `UNDETERMINED`, applied *before* any repair is contemplated | a divergent test has ~8 possible causes; "repair it" presumes one |
| C-10 | **Specified report structure** (22 sections) and **specified ticket structure**, with acceptance criteria stated as business behaviour rather than mechanism | *"the creator reaches the homepage without reconstructing the URL"*, not *"the controller returns 302"* |
| C-11 | **Severity discipline** — *absence of verification is not proof of failure* · *static wiring is not proof of runtime correctness* · *a failing test is not proof production is wrong* · establish the business invariant **before** naming a security defect | the 2026-08-08 review found the report's *summary* outran evidence its *findings* had earned |

**Revised twice more the same day.** The commission went through three drafts on 2026-08-08: **v1** (filed verbatim, commit `b6467e35`) → **v2** (general; added the self-limiting governance clause — never filed, superseded before it was) → **v3** (the operative one: scoped to the *next session* on this journey; verbatim in `.claude/sessions/2026-08-08.md`). **v2 is recorded but not reproduced** — it was an unfiled intermediate draft, and reproducing every draft would bury the operative one. Its one durable contribution is `C-12`.

**Additional candidates from v2/v3 — same freeze, same parking:**

| # | Candidate | Rationale offered |
|---|---|---|
| C-12 | **Governance precedence — the commission defers to the repository's adopted method and forbids implicit governance** | *"Governance must never be inferred from the fact that a better method would be useful"* — **the proposal limits itself**, which is what makes it safe to park *and* safer to adopt |
| C-13 | **Coverage ≠ completeness** — *complete · missing · gap* are **normative** claims requiring a business contract; without one, record `UNDETERMINED` | applied immediately in the claim-weakening direction: it withdrew `F-6`'s `MISSING` class |
| C-14 | **Persisted-record classification by writers *and readers*, never by shape** — immutable + actor + reason ≠ audit record; a reader's decision is what confers meaning | **an observation only.** It resembles questions open in `PBDIGIT-42` and `PBDIGIT-59`, **and it does not govern them** — those belong to a different workstream and are not reinterpreted from here |
| C-15 | **`GREEN` ≠ verified · `RED` ≠ production defect** — trace the mechanism before classifying either | a test can pass because middleware was bypassed or a compatibility shim supplied the value |
| C-16 | **Summary calibration as a first-class discipline** with its own pre-write interrogation of every sentence | the 2026-08-08 evidence: findings held, the summary did not |
| C-17 | **Keep three questions apart** — *does the capability exist? · does the implementation realize it? · can the verification estate be trusted?* | all three held different values on one journey; a single verdict overstates at least two |

### 🔴 Open governance question raised by the proposal itself

**`OQ-M1` — the commission forbids adopting the machinery it simultaneously instructs.** v3 §1 and §20 say *"do not adopt the parked C-6…C-11"* and *"do not introduce new taxonomies"*; v3 §7, §10 and §19 then **require** a test-classification taxonomy, an evidence taxonomy and a 17-item completion checklist — which **are** `C-8`, `C-9` and `C-10`. A fresh session meets a direct contradiction and will resolve it silently, **which is the exact implicit-governance failure §1 exists to prevent.**

**Engineering will not resolve this.** Two clean resolutions exist, and both are Product Owner acts: **(a)** invoke the freeze's own mid-cycle clause — *"only if a review could not be completed without it"* — and adopt `C-8`/`C-9`/`C-10` **scoped to this journey**, or **(b)** mark those sections as applying **only in the claim-weakening direction**, which is already permitted and needs no adoption at all. **(b) costs nothing and is available today.**

**Also parked, and noted rather than acted on:** the method's own Capability Verdict vocabulary includes **`Ready for verification`**, which `C-13` shows is itself a **normative** label — *ready* asserts nothing further is required. The 2026-08-08 report withdrew it as *its own* verdict and replaced it with four factual clauses, **but the method's vocabulary at *Capability Verdict* above is untouched**: changing adopted method vocabulary is governance, not engineering. **Retrospective candidate.**

### 🔴 `OQ-M2` — the three-question separation is simultaneously "a permanent programme principle" and on the do-not-adopt list

At the 2026-08-08 review the Product Owner wrote of `C-17`: *"I would make this a permanent programme principle. It is more fundamental than any particular taxonomy."* **The same message's commission §1 lists `C-17` among the candidates not to be silently adopted.**

**Engineering will not resolve this either — it is the same class of question as `OQ-M1`.** What engineering *has* done needs no adoption: the distinction is **used descriptively** in the 2026-08-08 report and in `PBDIGIT-63` to state findings more precisely. **Using an analytical distinction to make a weaker, truer claim is always permitted; declaring it a permanent programme principle is a governance act and requires one explicit sentence.** *"I would"* is an intention; it is not read as ratification.

### 📌 The governing rule this review produced, recorded verbatim

> **A technically observable difference is not yet a business defect. A business obligation must exist before its absence can be called a defect.**

**Applied to itself the same day, it withdrew four of this report's own classifications:** `Ready for verification` (readiness is normative), `F-6 MISSING` (completeness is normative), **`F-2` as a security defect** (the invariant cited turned out to be `PBDIGIT-37`, which concerns credentials in *tracked files* and never mentions logging, plus a line from the operator's *private* AI-instruction file — **not a PublicDigit contract**), and **Part D as a pre-authorised repair list** (two of its three items had no obligation behind them). **`D-6` now asks whether the platform has any policy on secrets in application logs. None was found.**

### 🔀 Workstream boundary — `PBDIGIT-63` and `PBDIGIT-48` are separate, and one rule now has two governance states

**Two investigations ran concurrently on 2026-08-08:** `PBDIGIT-63` (organisation-creation journey — this backlog) and `PBDIGIT-48` (election verification estate — `docs/plans/…election-verification-execution-plan.md`). **Both independently reached similar analytical conclusions. That is methodological convergence, and convergence is evidence, not authorisation.**

**Observed, and deliberately left unresolved:** the rule *"coverage is descriptive, completeness is normative"* is **`BOUND`** in the `PBDIGIT-48` execution plan (commit `5e9dc5f5`) and **`PARKED` as `C-13`** here. **One rule, two governance states, one repository.** Whether that is a contradiction or simply two streams legitimately governed by their own adopted rules **is a governance question, not an engineering one** — recorded so a future reader does not find the two states and assume one is a mistake.

**The boundary has held so far, and was checked rather than assumed:** `5e9dc5f5` touched only that stream's plan and nothing in `docs/publicdigit/backlog/`; the `PBDIGIT-63` artifacts have been touched only by the four `PBDIGIT-63` commits. **Neither stream's findings are absorbed, superseded or reinterpreted by the other**, and nothing here claims authority over `PBDIGIT-42`, `PBDIGIT-59` or the election plan.

### ⚠️ A practical cost of `OQ-2` is now visible

**Four commission drafts were produced on 2026-08-08.** Because `doc-placement.php --scope=cross-product --maturity=research` returns **unruled (`OQ-2`)**, each operative draft has been parked verbatim in the **append-only** session log. **That log now carries two full commissions and will carry more.** This is a workaround, not a home: **the placement question has moved from theoretical to costly, and ruling `OQ-2` would let successive drafts supersede one another in one artifact instead of accumulating.** Recorded as evidence for that ruling; **no folder was invented in the meantime.**

**Three of these (C-8, C-9, C-11) were partially exercised in the 2026-08-08 correction — and that is not adoption.** They were used only in the *claim-weakening* direction the existing rules already require (*"the strongest statement made must never exceed the strength of the available evidence"*). **No new phase, section or mandatory artifact was added.** Adopting a weaker claim is always permitted; adding machinery is what the gate governs.

**Placement of the full commission text is `PENDING`** — `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns *unruled, ref `ADR:OQ-2`*, the same open question this README already records. **Escalated, not guessed at.**

**Applications to date remain 3.** The retrospective is still eligible at **6**. **One Product Owner sentence — declaring a Method Retrospective, or an explicit mid-cycle exception — adopts any of C-1…C-11. That is a governance act and engineering will not perform it implicitly.**
