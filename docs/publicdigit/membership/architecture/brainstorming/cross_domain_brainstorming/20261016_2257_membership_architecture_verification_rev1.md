# Membership Architecture Verification, revision 1 (P-findings applied, DDD verification, traceability, corrected dependency plan)

**Author:** Session 1 (Architecture and Requirements Authority). **Status:** PROPOSED. It ratifies, accepts and authorises nothing.
**Supersedes for planning:** `20261016_2219_membership_architecture_verification_and_completion_plan.md` (kept unchanged as history). **Applies:** the findings P-01 … P-11 of `20261016_2244_principal_review_of_membership_verification_plan_and_next_step.md`, each re-checked here.
**Companions:** diagrams `20261016_2257_membership_architecture_diagrams.md`; story drafts `20261016_2257_membership_tdd_backlog_story_drafts.md`.
**Mode:** read-only investigation; the only writes are these three documents. No test, Composer, Artisan, database or HTTP operation; no Git state change.
**Commission note:** the commission text arrived truncated in Phase 7 ("Group the stories into coherent increments, for example: Architecture and requirements corrections. Baseline …"). Phase 7 is completed with the increments that the preceding phases require; any instruction after the truncation point is unknown and not applied.

**Labels for architectural statements (commission §3):** **[1]** confirmed by an authoritative decision · **[2]** verified in the current implementation · **[3]** supported by tests and current evidence · **[4]** proposed architectural improvement · **[5]** open domain or policy decision · **[6]** unverified assumption. Evidence labels: VERIFIED · HISTORICAL · REPORTED · INFERRED · OPEN · BLOCKED · ACCEPTED.

**Names (PROPOSED, not adopted):** COR-1…COR-4 = correction-contract slices C-1…C-4; CUT-0…CUT-10 = cutover gate C-0…C-10; GOV-END = "who may end a membership" (G-1 in the Slice 2 plan).

---

## 1. Baseline (Phase 1)

### 1.1 Repository state (VERIFIED, 2026-10-16 22:57)

| Item | State |
|---|---|
| Branch / HEAD | `election-flow-updated` / **`f54037f96`** |
| Commits since the previous review's baseline `83a059f98` | `b975096ad` (Blade `@context` fix), `982d5742c` (deptrac package), **`f311c97c4`** (`PBDIGIT-70`: deletes `app/Http/Middleware/VoterSlugStep.php`, sets `UNPARSABLE_KNOWN = []` and removes the expiry guard test), `9582de324` (Laravel 12 production-outage note), **`f54037f96`** (A1 evidence) |
| Membership ledger code since `834a4f65c` | unchanged except `tests/Architecture/MembershipDecisionCreationTest.php` (`f311c97c4`). `git diff 9582de324 HEAD` over Membership code, Membership tests, architecture tests and migrations: empty |
| Uncommitted (other sessions; not Membership) | IP-work documents, two review records, `docs/publicdigit/membership/architecture/models/README.md` (rev. 12 by this session, earlier) |
| Untracked, relevant | the brainstorming inputs (incl. the Principal Reviewer source `…/brainstorming/20261010_1901_implementation.md`); the transcript file `docs/publicdigit/membership/reports/Ran 1 shell command`; this session's review documents |
| Drift resolved since the review | the `VoterSlugStep` / `UNPARSABLE_KNOWN` pair is **committed under PBDIGIT-70** (`f311c97c4`); D-1 of the review is closed |

### 1.2 New material fact: the ledger migration has probably reached production

- `9582de324` records: "Laravel 12 already deployed by the PO, **plus 7 migrations run manually**" (`.claude/sessions/2026-10-16.md:139`) and names PBDIGIT-274 and PBDIGIT-286/249 migrations as live.
- The repository holds **exactly seven** migrations dated 2026-10-04 or later: `2026_10_04_000001`, `2026_10_04_000002`, `2026_10_06_000001…000003`, **`2026_10_09_100000_create_org_membership_tables`**, `2026_10_12_000001` (VERIFIED by listing).
- **INFERRED (high probability, not confirmed):** the Membership ledger tables `org_memberships`, `org_membership_facts`, `org_membership_commands` now exist in production, empty (no binding, no caller: VERIFIED, `MembershipServiceProvider` has no Organisational binding per the Slice 2a review).
- **Consequences if confirmed:** (a) CUT-10 ("migration rolled out to dev, then prod (O-4)") was passed out of order without its gate; (b) D-COR-4's premise "rename in an additive migration **while the table is unreleased**" (correction contract, D-COR-4 row) no longer holds; every schema change (COR-4, `end_kind` CHECK, F-4b) becomes a forward migration against a released table; (c) Part 2 (test-database verification) is unaffected.
- **Owner:** Operations / PO to confirm which migrations ran (`migrate:status` on production is an operation this session must not run). Recorded as **OQ-01** (§6).

### 1.3 Evidence currency (re-checked)

| Increment | Evidence | Against | Status now |
|---|---|---|---|
| Slice 0 (258), Slice 1a (259), COR-1 (268), Slice 2a (271) | **A1** `docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/` (committed `f54037f96`): 27 files at `83a059f98` via `git archive`; 11 runs, all exit 0; Domain 114 / 112,541; Application 61; Infrastructure 13; DateOrdinal 5; C-1 + G6 2 / 5,786; architecture 49. Delta at `9582de324`: `MembershipDecisionCreationTest` 33 tests, exit 0 | committed code; Laravel 12, PHPUnit 11.5.6, php-parser 5.9.0 | **[3] CURRENT** for the database-free scope (VERIFIED: README, commit) |
| A1 limitation | `composer install --no-scripts` was added by the developer **without prior approval**; disclosed in the README with the PO's authorisation; effect on plain-PHPUnit tests expected none, **not demonstrated** | — | evidence note, not a defect |
| Slice 1b (260), PBDIGIT-264 PostgreSQL parity | none on an isolated database | — | **BLOCKED** (P-DB) |
| G-6 (lost 2026-10-10 outputs) | hashes only | — | superseded in substance by A1; DEC-G6 still a PO / ARB act |
| Principal Reviewer source for G3 | untracked, misnamed in the G3 record | — | **OPEN** (A2) |
| Mutation score | developer-only (2026-10-10) | HISTORICAL | unchanged |
| Wider Membership suites (A1b) | not run | — | OPEN |
| Session 2's original report | not found in the repository | — | REPORTED only |

**Formal acceptance status:** none of 258, 259, 260, 268, 271 is ACCEPTED (G3 dispositions verdict fields blank; no later record found).

### 1.4 Relevant backlog (existing IDs, statuses VERIFIED from the files)

| ID | Title (short) | Status | Relevance |
|---|---|---|---|
| PBDIGIT-258 / 259 / 260 | Slice 0 / 1a / 1b | implemented; verdicts open; 260 DEFERRED | acceptance |
| PBDIGIT-264 | Slice 1b follow-ups F-1…F-5 | IN PROGRESS; F-2, F-4, F-5 held | Part 2 |
| PBDIGIT-265 | eligibility predicates differ | OPEN, not authorised | CUT-6 |
| PBDIGIT-266 | live routes without a found guard | OPEN investigation; ARB-SEC-01 tests-only, gated | security |
| PBDIGIT-267 | kernel test gaps TG-1…TG-5 | OPEN, not authorised | overlaps V-1 (TG-1 covers the *existing* `fromLog` checks) |
| PBDIGIT-268 | COR-1 | implemented, awaiting G3 | acceptance |
| PBDIGIT-269 | sensitivity-runner hardening | OPEN, not authorised | G3 residual risk |
| PBDIGIT-270 / 292 | credential exposure | OPEN | security, release |
| PBDIGIT-271 | Slice 2a / 2b | IN REVIEW; 2b held | acceptance, 2b |
| PBDIGIT-70 | unparsable files | `VoterSlugStep.php` deleted in `f311c97c4` | drift resolved |
| PBDIGIT-283 | test-database safety | DONE (2026-10-14) | reused by P-DB guard |
| PBDIGIT-261 / 293 / 295 | engine defaults; `DB_URL`; per-test isolation on PostgreSQL | OPEN (REPORTED relevance) | CUT-10, Part 2 |
| highest existing ID | **PBDIGIT-304** | — | new stories are drafted with placeholders (§ story drafts) |

---

## 2. P-01 … P-11 applied (Phase 1 review)

Each claim was re-checked against the source in this session unless marked.

| Finding | Existing claim (2219 plan) | Authoritative evidence | Architectural consequence | Proposed correction | Decision owner | Backlog impact |
|---|---|---|---|---|---|---|
| **P-01** circularity | COR-3 needs G-A1 and emits events; publication built in W6 after W5 after W4 | plan §5 (VERIFIED) | no feasible order | split **COR-3a** (commands, no emission) / **COR-3b** (emission after the publication decision PUB) | Session 1 (plan) · ARB (PUB) | stories MEM-COR3A, MEM-COR3B |
| **P-02** Slice 2b over-gated | W4 → W5 | Slice 2 plan `:65` "PostgreSQL resumed and its verification accepted; G-1 decided; separate authorisation"; correction contract `:224` "Slice 2 (Admit / End wiring) may proceed after its own contract" (**line `:224`, not `:233` as the review cited**); ARB-MEM-01 gates "live writes and cutover" (ARB record `:11`) | Slice 2b is independent of COR-2…4 | remove W4 → W5 and W2 → COR-2 | Session 1 | MEM-2B depends only on Part 2 acceptance + GOV-END |
| **P-03** future date | V-7 "trusted clock"; 2b AC "regardless of supplied date" | S2-C **APPROVED** (Slice 2 plan `:317`); Z1 (`:97`); Q-E1 **DECIDED** option A (`:327`); AUD-1 → Slice 12 (`:328`) | the domain stays clock-free **[1]**; whether the *application boundary* must refuse a supplied `decisionDate` after "today" (organisation time zone) is an interpretation of D-5(b) **[5]** | reformulate as **OQ-04**; no persistence (AUD-1 stays in Slice 12) | Human PO / ARB | MEM-HTTP boundary story carries the outcome |
| **P-04** empty Membership | AO-3 "derived"; V-8 "contradicts D-2" | D-2 concerns the *application* (closure record `:291`); 1a port `create(…) → Created(Member) \| HolderExists` approved (1a contract `:37`) | create-then-decide is approved **[1]**; an identity row is not membership (predicates derive from facts) **[2]** | AO-3 → **PROPOSED NEW RULE**; V-8 → **change request CR-01** against the 1a port, only if a reader is shown to count identity rows | Human PO / ARB on PR advice | reader-inventory task inside MEM-CUT6 prep; no kernel story |
| **P-05** outbox | "no outbox" (gap); recommend own outbox | 1b contract Out "event sourcing, generic persistence, projections; triggers" and B-6 (VERIFIED); EP-01 `outbox_events \| REUSE (Shared)` (`:371`); publish "each only when a consumer slice exists" (`:411`). **New VERIFIED fact:** `app/Contexts/Membership/Infrastructure/Services/OutboxService.php` already writes to the shared `outbox_events` table and has **no caller** | deliberate deferral **[1]**; a reuse path exists in code **[2]** | restate as "deferred, landing slice unnamed"; any own-outbox design = change request | ARB | MEM-PUB (design), MEM-COR3B |
| **P-06** RATIFIED labels | BR-10, BR-16, BR-17 marked RATIFIED | act `:8-10` "do not amend the act" | the notes guide EP-01 **[1 as guidance]** | relabel "act + recorder's note"; BR-10 = "settled per N-3" | Session 1 | none |
| **P-07** owners | D-COR-3 PR; CQ-3 Governance; CQ-9 in Governance package; V-5 "clarification" | CC `:213` (D-COR-3 = PO); `:735` (CQ-3 = PO + Legal + Governance); `:736` (CQ-9 / D-COR-5 (B) = PO + Legal); act §16 (temporal model frozen) | wrong owner → wrong decision | corrected in §6 | Session 1 | decision tasks only |
| **P-08** omissions | — | CUT-0 (cutover gate `:291`); D-6 / R10 (Slice 2 plan `:333`; closure `:203` REPORTED); DV-1 (CC `:740`); C-AMEND-1 C-3 items 3–5 ("reason"); D-COR-5 (B); BD-CONSENT, BD-REC0 (EP-01 §19); `competenceVerified` (Slice 2 plan `:107`); PBDIGIT-267, 269 | missing gates | added to RTM (§5) and dependency plan (§7) | as listed | stories reference them |
| **P-09** security closure | "security track must close before HTTP" | ARB-SEC-01 "investigation and tests only. No application fixes…" (ARB record `:12`); 1b re-entry criterion 4 "persistence and security acceptance conditions" (G3 dispositions §4) | characterisation ≠ closure; security conditions also gate ACC-260 | two-step: **characterisation (ARB-SEC-01)** → **fix stories (separate authorisation)**; name which security conditions ACC-260 needs (**OQ-10**) | ARB | MEM-SEC-CHAR, MEM-SEC-FIX-* |
| **P-10** ARB-MEM-01 | "before any cutover, migration or live write" | verbatim: "Verified correction/annulment capability is required before Membership live writes and cutover" (ARB record `:11`) | gates live writes and cutover only | cite verbatim; COR-1…4 mapping is the contract's (CC `:225`) | Session 1 | none |
| **P-11** triggers | G-A10 "no append-only guarantee" as a gap | 1b B-6 "not included: no database trigger (optional, not authorised)" (1b contract `:19`) | explicit exclusion **[1]** | restate; a trigger = change request (B-11 M-11) | Membership owner → ARB | none now |

**Disagreements with the review (recorded, not silently corrected):** (a) P-02's citation `CC:233` is `CC:224`; (b) P-08's "TG-1 is exactly the `fromLog` validation" is too strong: TG-1 tests the *existing* seq / `recordedAt` checks (PBDIGIT-267 table), V-1 asks for *new* structural checks; (c) the review's handoff H-1 has been executed (A1) with one unapproved deviation (`--no-scripts`), now disclosed.

---

## 3. Strategic DDD verification (Phase 2)

| Statement | Class | Evidence |
|---|---|---|
| Membership owns the enduring (Person, Organisation) relationship, its periods and facts; Election owns entitlement; Finance owns fees; Governance owns authority; Identity owns persons | [1] | ratified §1, §8 |
| Membership publishes period / correction events to Finance and Election via the shared outbox, per consumer slice | [1] (EP-01 `:371`, `:411`) · not implemented [2] | no event classes; `OutboxService` unused |
| Governance supplies authority through `AuthorityCheckPort`; Membership stores evidence | [1] · interim adapter [2] | `AuthorityCheckPort.php:17`; `InterimRoleAuthority.php` (not bound) |
| The interim adapter reads roles from Organisation's `user_organisation_roles` through `OrganisationRoleReader` (an anti-corruption seam) | [2]; Eloquent reader not built (Slice 2b) | `OrganisationRoleReader.php:16` |
| **Two Membership models coexist**: the legacy `members` / `App\Models\Member` world (live, many writers) and the ledger (`org_memberships…`, no writer) | [2] | CUT-3 inventory (REPORTED: 9 direct writers, 8 DDD callers) |
| **Committee is split across three places** (`app/Contexts/Committee`, `app/Contexts/Governance`, `app/Contexts/Membership/Domain/Committee`) | [2] (directories VERIFIED); boundary defect per act N-6 | committee relocation is a separate track (EP-01 EP-Q2) |
| Legacy Membership infrastructure holds unrelated responsibilities (committee applications, outbox service, HTTP controllers) | [2] | `Contexts/Membership/Infrastructure/{Services,Http}` |
| Ubiquitous-language collisions | [2] | "Member" = legacy row **and** ledger aggregate; "membership application" = organisation flow **and** committee flow (S-5); "C-n" and "G-1" series collide (naming proposal) |
| New source-of-truth risk: wiring a live Admit / End before D-6 / R10 creates two truths | [1] (Slice 2 plan `:333`; closure `:203` REPORTED) | gate kept |

**Strategic gaps:** SG-1 no event publication path (deferred); SG-2 no Membership query port yet (needed by Election, Committee, cutover readers); SG-3 committee placement; SG-4 two "membership application" concepts need distinct names (proposal: **OrganisationMembershipApplication** vs **CommitteeSeatApplication**) **[4]**.

---

## 4. Tactical DDD verification (Phase 2)

| Element | Statement | Class | Evidence |
|---|---|---|---|
| Aggregate `Member` (D1, one per person × organisation) | identity `MemberId`, holder `PersonRef`, organisation `TenantId`, seq-ordered `RecordedFact` log | [1][2][3] | `Member.php:22-40`; A1 |
| Facts | `Admit`, `End`, `Annul`, `VoidFact` (`ReversalFact`); `EffectiveDatedFact` for valid time; Renew / ChangeType / Suspend / Restore / Correct **absent** | [2] | `Domain/Organisational/*.php` |
| Value objects | `TimePoint`, `Instant`, `Interval`, `IntervalSet`, `FactId`, `PeriodId`, `EndKind` (closed: resignation, exclusion, death, legacy), `Provenance` (decision / rule / attested), `AuthorityEvidence` | [2][3] | files; A1 |
| Decision | `Decision` (accepted with expected version / refused with `RefusalReason`, 17 cases) | [2][3] | `RefusalReason.php:13-61` |
| Resolution | pure: liveness → periods → lifetimes; CFC+ consequences not needed until Correct | [1][2][3] | `Resolution.php`; ratified §3 |
| Invariants at decide time | INV-9 (overlap), INV-EP, INV-COV via `InvariantSet`; D-1; closed end kinds; legacy ⇔ attested; `effectiveAt ≤ decisionDate`; finite dates; reversal target rules | [2][3] | `Member.php:90-192`; A1 |
| Invariants on load | only seq order and non-decreasing `recordedAt` | [2] | `Member.php:25-35` → V-1 |
| Application services | `AdmitMemberHandler`, `EndMembershipHandler` through `DecideAndAppend` (authority → load → decide → append with idempotency key + fingerprint) | [2][3] | `Application/Organisational/*` |
| Application refusals | `NotAuthorised`, `MemberNotFound`, `ConcurrentModification`, `IdempotencyKeyConflict`, `ValidTimeNotStorable` | [2] | `ApplicationRefusal.php` |
| Repository port | `load`, `findByHolder`, `create` (atomic INV-M), `appliedCommand`, `append` (requires an accepted `Decision`) | [1][2] | `MemberRepositoryPort.php:26-36` |
| Persistence adapter | PostgreSQL, version compare-and-set, idempotency table, `kind` CHECK admit / end only, no `end_kind` CHECK, no Annul / Void encoding (raw `\Error` path) | [2]; PostgreSQL behaviour **unverified** | migration `:40-80`; `PostgresMemberRepository.php:160, 183, 223` |
| Transaction boundary | one transaction per append (version check + fact + command row) | [2] (code); [6] runtime | adapter |
| Temporal semantics | valid time (calendar dates, EP-Q6 T1), decision time (`decidedAt`, `decisionDate` validated not stored), record time (`recordedAt`, ties allowed) | [1][2]; ties **[5]** | §2 V-5 |
| Concurrency | expected-version append; re-decide on conflict | [1][2]; PostgreSQL race tests not run on isolated DB | Slice 1a / 1b |
| Error behaviour | domain refusals are values; invalid input (∞ dates, blank declaration) throws; infrastructure failures throw | [2] | `Member.php:204-209`; `Provenance.php:25-28` |
| Domain vs boundary rules | tenant / actor / today belong to the application boundary, not the domain | [1] (S2-C) · boundary not built [2] | V-7, V-9 |

**Tactical findings (status after P-corrections):** V-1 gap (deferred to COR-4) · V-2 **confirmed defect** against CC §3.1 (no acceptance criterion covers it) · V-3 hardening · V-4 gap (deferred, C-AMEND-1 F-1) · V-5 open · V-7 open (OQ-04) · V-8 open (CR-01) · V-9 later-slice gap · V-10 evidence gap · V-11 later-slice gap · V-12 open (Slice 12).

---

## 5. Requirements traceability matrix (Phase 3)

Story IDs refer to the drafts in the companion document (placeholders `MEM-*`; no PBDIGIT ID allocated). Test levels: K = kernel unit · A = application unit · P = PostgreSQL integration (isolated database) · F = feature / HTTP · R = architecture fitness · E = evidence / review.

| Req | Requirement / invariant | Source and status | Owner | Context / aggregate | Acceptance criteria (summary) | Level | Story | Dependencies |
|---|---|---|---|---|---|---|---|---|
| BR-1 | one Membership per (p,o); D1 aggregate | ratified §1, §7 [1] | Membership | Member | existing Slice 0 / 1a ACs | K, A | 258/259 (accept) | A1 ✓ |
| BR-2 | INV-EP, INV-9 | ratified §5 [1] | Membership | Member | K-G1, K-R3 | K | 268 (accept) | A1 ✓ |
| BR-3 | append-only; KnownAt stable | ratified §5 [1]; ties [5] | Membership | Member + adapter | tie rule per OQ-05 | K, P | MEM-COR4 | OQ-05 |
| BR-4 | exactly one admission basis | D-1 [1]; LQ-1 [5] | Membership; Legal | Member | AC-1 | K, A | 271 | — |
| BR-5 | no pending Member | D-2 [1] | Membership | outside aggregate | "application not modelled in the aggregate" (true) | R | — | CR-01 only if raised |
| BR-6 | closed end kinds; legacy ⇔ attested | D-3 [1]; D-3-L [5] | Membership; Legal | Member + DB | AC-2, AC-3; DB CHECK + load check (OQ-07) | K, P | MEM-2B | OQ-07 |
| BR-7 | authority evidence incl. `competenceVerified`; interim Admit authority; End per GOV-END | D-4 [1]; Slice 2 plan `:107`; GOV-END [5] | Governance | port + adapter | AC-5; AC-13 | A, P | 271; MEM-2B | GOV-END |
| BR-8 | backdating allowed, future dating refused; domain clock-free | D-5(b) [1], S2-C [1], Z1 [1], Q-E1 [1]; boundary interpretation [5] | PO / ARB | domain + boundary | AC-4; boundary rule per OQ-04 | K, F | 271; MEM-HTTP | OQ-04 |
| BR-9 | Annul / Void algebra | ratified §2, §9 [1]; D-COR-5 (A) [1] | Membership | Member | K-A1…K-X2 | K | 268 | A1 ✓ |
| BR-9a | the Annul itself carries decision provenance | CC §3.1 [1] | Membership | Member | an Annul with rule / attested provenance is refused | K | **MEM-K1** (V-2) | — |
| BR-10 | Correct-target sub-policy | act N-3 (settled as recommended) | Membership | Member | CC §14.5 Correct cases | K | MEM-COR2 | BD-REV-ER, D-COR-3 |
| BR-11 | BD-REV-ER | OPEN [5] | PO | Member | per decision | K | MEM-COR2 | — |
| BR-12 | concurrency (expected version, re-decide) | ratified §6 [1] | Membership | adapter | 1b T1–T13 on isolated DB | P | MEM-PG2 | P-DB |
| BR-13 | transactional enforcement | BD-SYNC [1] | Membership | adapter | same | P | MEM-PG2 | P-DB |
| BR-14 | lifetime derived, not stored | act N-2 [1 as guidance] | Membership | Resolution | no stored lifetime column | R | — | — |
| BR-15 | no entitlement in Membership; Election-Only never reads the ledger | ratified §8 [1]; CUT-9 | Election / Membership | — | R5 fitness (literal; hardening MEM-R) | R | 271; MEM-R | — |
| BR-16 | events per consumer slice via the shared outbox | EP-01 `:371`, `:411` [1] | Membership; ARB | outbox | event row iff fact committed | P | MEM-PUB, MEM-COR3B | PUB |
| BR-17 | committee participation outside the aggregate | act N-6 (recorder's note) | Governance | — | relocation | — | separate track | EP-Q2 |
| BR-18 | migration never invents truth | ratified §11 [1]; CUT-7 | Membership + PO | migration | dry-run parity | E | MEM-CUT7 | BD-NORM, BQ-P |
| BR-19 | verified correction capability before live writes and cutover | ARB-MEM-01 [1] | ARB | — | COR-1…4 accepted | E | gate | — |
| BR-20 | database tests only on an isolated, identity-verified database | ARB-SEC-01 [1]; Part 2 plan [1 for Part 1] | ARB / Infra | — | identity proof | E | MEM-PDB | DEC-SEC-DB |
| BR-21 (new, P-08) | CUT-0 writer inventory is reproducible (fitness test) | cutover gate `:291` [1 as gate proposal of the 264 pack] | Membership | legacy | an unknown writer of `members` fails the build | R | MEM-CUT0 | — |
| BR-22 (new) | D-6 / R10 before live Admit / End or HTTP / UI | Slice 2 plan `:333` [1] | PO | — | gate | E | MEM-HTTP | — |
| BR-23 (new) | DV-1 countersignature before COR-4 / cutover | CC `:740` [1] | PR; PO | — | act recorded | E | gate | — |
| BR-24 (new) | "reason" meaning decided before COR-3 | C-AMEND-1 C-3 items 3–5 [1]; CQ-3 [5] | PO + Legal + Governance | Annul / Void / Correct | decision recorded | E | MEM-COR3A | CQ-3 |
| BR-25 (new) | BD-CONSENT, BD-REC0 settled before Slice 2 live use | EP-01 §19 [1] | PO | Admit | decision recorded | E | MEM-HTTP | — |
| BR-26 (new) | persisted reversal facts and decode failures never silently change membership truth | inferred from ratified §5 + CC §4 [4] | Membership | adapter + `fromLog` | defined integrity failure on load | K, P | MEM-COR4 | — |
| SEC-R1 | organisation context selected only within own memberships | platform tenancy [5 as rule wording] | ARB (platform security) | middleware | S-1 tests | F | MEM-SEC-CHAR / FIX-S1 | DEC-SEC-DB, DEC-TENANT-HDR |
| SEC-R2 | fee actions confined to the member's organisation | existing sibling rule [2] | Membership / Finance | legacy controller | S-2 tests | F | MEM-SEC-FIX-S2 | DEC-SEC-DB |
| SEC-R3 | committee composition changed only by the authorised role | **undefined** [5] | Governance + PO | committee API | S-3 tests | F | MEM-SEC-FIX-S3 | rule |
| SEC-R4 | a member's personal data visible only to that member and authorised officers | privacy [5 as wording] | PO | dashboard | S-4 tests | F | MEM-SEC-FIX-S4 | DEC-SEC-DB |
| SEC-R5 | committee-application review by an authorised reviewer; no self-approval; reviewer belongs to the organisation | **undefined** [5] | Governance + PO | committee application | T1–T7 | F | MEM-SEC-FIX-S5 (save + authorisation together) | rule |

**Traceability defects found:**
- *Requirements without acceptance criteria:* BR-9a (until MEM-K1), SEC-R3, SEC-R5 (no rule), BR-26 (proposal).
- *Acceptance criteria without an authorised requirement:* the 2219 plan's Slice 2b criterion "refused regardless of any supplied decision date" (removed, P-03); the AO-3 criterion "no Membership exists after a refused Admit" (removed, P-04).
- *Implementation without an authoritative requirement:* create-then-decide is authorised (1a port) — no defect; the legacy `OutboxService` has no caller and no requirement — record only.
- *Decisions without implementation path:* EP-01's shared-outbox reuse (no landing slice) → MEM-PUB.
- *Ambiguous language:* "membership application" (two flows); "Member" (two models); "G-1".

---

## 6. Open questions and change proposals

| ID | Exact question | Options | Consequences | Recommendation | Owner | Dependent stories |
|---|---|---|---|---|---|---|
| **OQ-01** | Did `2026_10_09_100000_create_org_membership_tables` run in production on 2026-10-16? | confirmed / not | if yes: schema is released, D-COR-4 premise void, CUT-10 passed out of order | confirm before any schema story is planned | Operations + PO | MEM-PG2, MEM-COR4, MEM-2B |
| OQ-02 | BD-REV-ER | allow / refuse | refuse = no implicit set-aside | refuse | PO | MEM-COR2 |
| OQ-03 | D-COR-3: which attributes are correctable | dates only / more | scope of COR-2 | dates only first | PO | MEM-COR2 |
| **OQ-04** | Does D-5(b) require the application boundary to refuse a supplied `decisionDate` later than "today" in `organisations.timezone`? | yes (boundary check; domain unchanged) / no | without it a future decision date makes a future-dated fact acceptable | yes, as a boundary rule (consistent with S2-C) | Human PO / ARB | MEM-HTTP |
| OQ-05 | KnownAt ties | strict `recordedAt` in the adapter / KnownAt over seq (change request to the frozen temporal model) | determinism of history views | adapter strictness | PR proposes; ARB if the model changes | MEM-COR4 |
| OQ-06 | D-COR-4 column (rename / generalise) — revisit if OQ-01 = yes | rename / generalise | forward migration either way | decide after OQ-01 | PR | MEM-COR4 |
| OQ-07 | stored end kinds | CHECK closed set + load check legacy ⇔ attested / JSON CHECK | robustness | CHECK + load check | PR → PO / ARB | MEM-2B |
| OQ-08 | PUB: event publication | REUSE shared outbox (EP-01) / own outbox (CR) | REUSE depends on the shared relay being tenant-correct (PBDIGIT-211, separate track, not investigated) | REUSE when the first consumer slice starts | ARB | MEM-PUB, MEM-COR3B |
| OQ-09 | DEC-SEC-DB | dedicated database / re-use `pbdigit274_isolated_test` under a new authorisation | critical path | dedicated, reusing PBDIGIT-283 rules | ARB + PO + Infrastructure | MEM-PDB, MEM-SEC-CHAR, MEM-PG2 |
| OQ-10 | Which security conditions does ACC-260 (1b re-entry criterion 4) require? | none beyond persistence / S-1 only / all live risks | scope of the 2b gate | S-1 (tenant context) because Slice 2b binds tenant-scoped persistence | ARB | MEM-2B |
| **Package G** | GOV-END; CQ-3 incl. "reason"; CQ-9 / D-COR-5 (B); DS-4; LQ-1; D-3-L; SEC-R3; SEC-R5; suspend / archive authority | per statute / platform default / per-organisation configuration | define who may decide what over membership and committees | present together; record each item with its own owner | GOV-END, DS-4, SEC-R3, SEC-R5: Governance + PO · CQ-3: PO + Legal + Governance · CQ-9, LQ-1, D-3-L: PO + Legal | MEM-2B, MEM-COR3A, MEM-HTTP, MEM-SEC-FIX-* |
| CR-01 | (only if raised) change the 1a port so a refused first Admit leaves no identity row | keep / change | affects handler and version-0 anchor | keep unless a reader counts identity rows | Human PO / ARB | none now |
| CR-02 | (only if raised) Membership-owned outbox instead of REUSE | — | reverses EP-01 | not recommended | ARB | — |
| CR-03 | (only if raised) append-only trigger | — | reverses 1b B-6 | defer to B-11 M-11 | Membership owner → ARB | — |
| NAM-01 | adopt COR / CUT / GOV-END names; adopt §7 order | adopt / keep | fewer wrong citations | adopt | PO | all |

---

## 7. Corrected dependency plan (Phase 5)

Every edge cites its source; **[P]** marks a proposed dependency that requires approval.

| From → To | Source |
|---|---|
| A1 (done) → ACC-258 / 259 / 268 / 271 | G3 dispositions; Slice 2 plan §10 AC-9 |
| A2 (PR source preserved; DEC-G6) → ACC-268 | G3 dispositions §15.7(7) |
| PR review of Slice 2a → ACC-271 | Slice 2 plan status line |
| ACC-268 → MEM-K1 (V-2), MEM-COR2 | CC §14.5 (C-2 depends on C-1) |
| OQ-02, OQ-03 → MEM-COR2 | CC `:221`, `:213` |
| OQ-09 → P-DB + I-1 + PV → Part 2 (MEM-PG2) | Part 2 plan `:66`, `:328ff` |
| MEM-PG2 → 264 F-5 → F-4b → F-2 | Part 2 plan `:328ff`; F3-2 sequence |
| MEM-PG2 + 264 + DV-1 + OQ-10 → ACC-260 | G3 dispositions §4 re-entry 1–4; CC `:740` |
| ACC-260 + GOV-END + authorisation → MEM-2B | Slice 2 plan `:65`, §14 |
| OQ-07 → MEM-2B (CHECK, load check) | Slice 2 plan §15 follow-ups **[P for the load check]** |
| ACC-268 + MEM-PG2 + 264 + OQ-01 + OQ-05 + OQ-06 → MEM-COR4 | CC `:223`, `:527`; C-AMEND-1 C-4 |
| MEM-COR4 accepted + CQ-3 (incl. "reason") + G-AUTH-1 + D-COR-5 (B) → MEM-COR3A | CC `:222`, `:534-536`, `:736` |
| MEM-COR4 accepted → wiring of MEM-COR3A | V-4 (raw `\Error`) **[P]** |
| OQ-08 + first consumer slice → MEM-PUB → MEM-COR3B and period events | EP-01 `:371`, `:411` |
| MEM-PUB → Slice 3 queries / projections where they consume events | EP-01 §18 **[P for the coupling]** |
| D-6 / R10 + BD-CONSENT + BD-REC0 + LQ-1 + D-3-L + OQ-04 + V-9 boundary + affected MEM-SEC-FIX → MEM-HTTP (Slice 14) | Slice 2 plan `:333`; EP-01 §19; P-09 |
| MEM-SEC-CHAR (ARB-SEC-01) → MEM-SEC-FIX-* (separate authorisation) | ARB record `:12` |
| ARB-MEM-01 (COR-1…4 verified) + ACC-260 + DV-1 + CUT-0…CUT-10 + PBDIGIT-265 → migration / cutover | ARB record `:11`; cutover gate `:147-156`, `:291` |

**Independent now (no unresolved decision; authorisation still needed):** A2; Principal Reviewer review of Slice 2a; MEM-K1 after ACC-268; MEM-R scanner hardening; MEM-CUT0 inventory fitness test (read-only effect); A1b.
**Blocked by a decision:** MEM-COR2 (OQ-02/03), MEM-COR3A (Package G), MEM-2B (GOV-END, OQ-07, OQ-10), MEM-PUB (OQ-08), MEM-HTTP (OQ-04, Package G, D-6 / R10), MEM-SEC-FIX-S1/S3/S5 (rules).
**Blocked by infrastructure:** everything at level P or F (OQ-09).
**Requires formal acceptance first:** MEM-K1 and MEM-COR2 (ACC-268); MEM-2B and MEM-COR4 (ACC-260).

**Critical path (INFERRED):** OQ-09 → P-DB → MEM-PG2 → ACC-260 → MEM-2B / MEM-COR4 → MEM-COR3A → (ARB-MEM-01) → cutover. Second path: Package G.

---

## 8. Residual risks and limitations

- Self-authored plan reviewed by the same session; the adversarial reviewer and source re-reads mitigate, not remove, this.
- OQ-01 (production schema) is inferred from a count and a session-log sentence; only Operations can confirm.
- Runtime behaviour (HTTP outcomes, binding order, session persistence on errors, duplicate routes, production cache driver) is not established.
- REPORTED and not re-checked here: S-6, S-8, S-9, S-11; BQ-P owners; the CUT-3 writer counts; PBDIGIT-293 / 295 / 261 relevance.
- Mermaid diagrams in the companion document were **not machine-validated**: no authorised validator was run.
