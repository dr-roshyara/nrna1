# Release 1, build slice S2: the Membership lifecycle (readiness assessment and proposed plan)

| | |
|---|---|
| **Status** | **APPROVED by the Principal 2026-11-15 (D-236): Q-1 Option A; S2-1 … S2-6 in full.** S2-1 implemented and proven **except D-177 row 20 (rule decided, D-237; implemented in S2-5)**; **S2-2 DONE 2026-11-15**; S2-3 not started |
| **Story** | `PBDIGIT-354` (created with this plan) |
| **Authorisation** | the Principal, 2026-11-15: "proceed with Membership lifecycle", **beginning with a bounded readiness assessment**; development and test only |
| **Slice in the accepted sequence** | release-1 blueprint (D-216) §10: S0 Platform routing ✔ (PBDIGIT-345, D-235) → S1 ledger clock ✔ (PBDIGIT-343) → **S2 Membership lifecycle = G-4** → S3 Governance → S4 Organisation → S5 Forum → S6 Newsletter. *(The slice-1 plan calls the same content "slice 5"; same scope, different numbering.)* |
| **Author** | Claude, the implementer; not independent |

## 1. Objective (business language)

PublicDigit already knows **where** an organisation's membership records live (its own database) and can **admit** and **end** a membership with a full, auditable history.

S2 adds **how a membership changes over its life:**
- **renewing a term**;
- **suspending and restoring** a member's rights;
- a member **resigning**, at once or **from a later date** (and changing their mind before then);
- **recording a death or an exclusion**;
- **correcting records with authority**.

Every change keeps the history. What PublicDigit "knew" at any past moment stays answerable.

## 2. Authoritative sources (verified, not re-derived)

| Source | Status | Verified |
|---|---|---|
| Ratified Membership architecture (RA) and register D-1 … D-5(b) | RAT | as cited by the accepted designs |
| **M-TDD-01 rev 4** (`docs/publicdigit/architecture/reviews/2026-11-12-m-tdd-01-rev2-membership-lifecycle-and-release-1-integration.md`) | **ACCEPTED, D-210** | without its two appended status notes the file hashes to **`b4aba168…`, the accepted text** |
| **Renew / Suspend / Restore design rev 6** (`docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md`) | **ACCEPTED, D-177** | without its status line the file hashes to **`2ec5028f…`, the accepted text** |
| D-163 (Renew and Suspend / Restore in release 1; explicit term, no platform default) | AD | |
| D-201, D-203 (`ownAct`), D-204, D-205 (the one guard exception), D-206 (Correct / Void / Annul authority), **D-223** (late execution) | AD | |
| D-209 (ledger clock), D-162 (C1 / C2) | AD; implemented in slice 1 | |
| Release-1 blueprint rev 4 §6 **G-3, G-4** and §10 | **ACCEPTED, D-216** | |
| D-233 (Platform routing contract), M-1 … M-3 | AD; implemented (PBDIGIT-345) | |
| Governance completion rev 5 | ACCEPTED, D-208 | consulted for authority shapes only |

## 3. Readiness assessment (EP-03)

### 3.1 Already implemented (slice 1, PBDIGIT-343; slice 2 routing, PBDIGIT-345)

| Capability | Where |
|---|---|
| The `Member` aggregate: one per (person, organisation), append-only facts, `knownAt(τ)` | `app/Contexts/Membership/Domain/Organisational/Member.php` |
| Facts **Admit, End** (kinds resignation, exclusion, death, legacy), **Annul, Void, Correct** | `Domain/Organisational/{Admit,End,Annul,VoidFact,Correct}.php` |
| Coverage (`coverage`), `inForce` (Admit's initial term clipped to the lifetime); `suspended` **always empty** (no Suspend fact yet) | `ResolvedMember.php:99–123` |
| Use cases **AdmitMember**, **EndMembership**, each behind `AuthorityCheckPort`, with idempotency and a bounded re-decide loop | `Application/Organisational/` |
| Ledger clock (D-209): database clock, refuse-and-retry, `closedThrough` step; **C1 outbox; C2 `CoverageKnownAt`** (complete or refused) | `Infrastructure/Organisational/`, `Application/Organisational/CoverageKnownAt.php` |
| A hook for due scheduled resignations in the closing step: **`DueScheduledResignations`**, bound to **`NoDueScheduledResignations`** (none exist) | `Infrastructure/Organisational/` |
| Organisation-database routing for every Membership adapter (M-1 … M-3) | PBDIGIT-345 task 10 |
| Ledger-clock proofs **LC-1 … LC-6 and A1 … A3**, except **LC-4** (assigned to the lifecycle slice) | slice 1 acceptance evidence |

### 3.2 Designed and accepted, not implemented (S2 = G-4)

| # | Capability | Accepted source | Gaps found in the code |
|---|---|---|---|
| L-1 | **Renew** (term segments; late renewal keeps the gap; after End refused) | D-163, D-177 §4–§5 rows 1–7, 17, 20–22 | no `Renew` fact; `inForce` uses the Admit segment only; **the facts table's `kind` CHECK admits no new kind** (organisation migration needed) |
| L-2 | **Admission term rule:** a release-1 admission must state its term (finite or open-ended); "absent" only for attested history | D-177 §3 (V-1) | `decideAdmit(…, ?TimePoint $initialTermUntil …)` accepts absent today |
| L-3 | **Suspend / Restore** (restrictions; `NotInForce`; one live Restore per Suspend) | D-163, D-177 §4–§5 rows 8–16, 15b, 18–19 | no facts; `suspendedIntervals()` is empty |
| L-4 | **Resign now**, including the member's **own** resignation (`ownAct`) | D-194, D-203 | End exists; the `ownAct` competence kind and the self-service act are absent |
| L-5 | **Scheduled resignation:** declare for a later date, withdraw, execute in the `closedThrough` step; superseded when the period ends earlier; **the D-205 guard exception** (one case only); late execution (D-223) | D-201, D-204, D-205, D-209, D-223; M-TDD-01 §5.2 | only the empty hook |
| L-6 | **Record a death** (pull) | M-TDD-01 §5.1 | an End with kind `death` exists; no `record-death` act |
| L-7 | **Apply an exclusion** (pushed Resolution part, idempotent per part, applied or `Inapplicable`, reported back) | M-TDD-01 §5.1, K-6 | none. **The producer, Governance, is S3** |
| L-8 | **Grouped renewals** from one resolution: all or none, applied-group marker unique on the group key, size bound | D-177 §7, §16 C-3, §17 M-2, §19 M-8 | none. **The producer is S3** |
| L-9 | **Correct / Void with recording authority; Annul by push** | D-206; M-TDD-01 §5.4 | the facts exist; no authorised use cases for Correct / Void; Annul push needs S3 |

### 3.3 Dependencies

- **Authority (G-3):** every act asks Governance's AuthorityCheck (K-5) through `AuthorityCheckPort`.
  - **That port has no production binding**, and G-3 requires the interim adapter to stay unbound. Governance's AuthorityCheck is **S3**.
  - **Consequence (no decision needed):** S2's use cases are built and proven with test doubles of the port. **In production they stay fail-closed until S3**, exactly as Admit and End are today.
- **Push shapes (L-7, L-8, the Annul push):** Membership's side can be built and proven now. The real producer arrives with S3.
- **Schema:** L-1, L-3, L-5 need organisation migrations (new fact kinds; a scheduled-declaration table).
  - Applied **only by the provisioning process** (PBDIGIT-345). **No operational organisation exists**, so this touches development and test databases only.
  - Existing test organisations take it via `migration-repair`.
  - **PBDIGIT-352 (`migrating`) does not block this:** nothing migrates a routable organisation.
- **Reference Geography:** **not used** (Membership never reads the map; M-TDD-01 §9). **G-6c condition C3 stays open:** S2 introduces no feed consumer.
- **Not in S2:** Organisation belonging (S4), Forum (S5), Newsletter (S6), dues `Standing` (unused in release 1, D-177 §1).

### 3.4 Build gates owned by S2

| Gate | Property | Step |
|---|---|---|
| D-177 §12 (all rows) | Renew / Suspend / Restore decision table, coverage unchanged, `knownAt`, authority, C1 emitted, grouped-renewal atomicity | S2-1, S2-2, S2-6 |
| **MI-1** (read "on or after its date", D-223) | the D-205 guard exception applies only to a scheduled resignation's execution | S2-4 |
| **MI-2** | execute vs withdraw, both orders; never two Ends; withdrawal after the date refused | S2-4 |
| **MI-3** | a declaration is superseded when the period ends earlier | S2-4 |
| **MI-4** | C1 / C2 show no planned change before execution; a backdated End changes coverage only as known from its recording | S2-4 |
| **MI-10** | no C2 answer at τ ≥ an effective date before the due End is recorded; a failed execution holds `closedThrough` | S2-4 |
| **MI-15a … f** (D-223) | normal, late, early (refused), withdrawal, knowledge boundary, no other fact | S2-4 |
| **LC-4** (slice 1, deferred here) | due scheduled resignations executed in the closing step | S2-4 |
| MI-8 (Membership's part) | a refusal is never "not a member" (C2 unchanged) | regression in every step |
| **Not S2's:** MI-5, MI-6, MI-7, MI-9, MI-11, MI-12, MI-14 (Organisation, Newsletter, Forum: S4–S6); MI-13 and A1–A3 (done in slice 1) | | |

### 3.5 A genuine open question: what is "`MembershipStanding`"? (Principal decision Q-1)

- **The finding:** `MembershipStanding` has **no accepted definition.** It appears only:
  - in the accepted Hexagonal DDD baseline (R-2), **as an example name**: "Governance consumes membership facts (in force, suspended) only through a Membership query contract, for example `MembershipStanding`, defined with PBDIGIT-345 or the lifecycle work";
  - in October brainstorming documents (not authoritative).
- **In the accepted designs:**
  - **Governance never reads membership standing in release 1.** Membership asks Governance for authority (C-1a, K-5), and standing flows Membership → Organisation (C1 / C2) → Communication and Deliberation (K-4).
  - **The ratified term `Standing` already means *dues* standing**, unused in release 1 (D-177 §1).
- **Options:**
  - **A (recommended): no new contract in S2.** "Membership standing" = the `inForce` / `suspended` intervals **already published by C1 / C2**. S2 makes them real (Renew, Suspend / Restore). R-2's Governance-facing query is defined **when Governance first needs membership facts** (S3 or later), as a thin answer over C2, under its own decision.
    - **Consequences:** nothing invented, no consumerless contract, no clash with the ratified dues `Standing`.
  - **B: define a `MembershipStanding` query port now.** A new published contract with no accepted consumer in release 1. It needs its own design and acceptance, and would duplicate C2.
- **Recommendation: A.** It realises exactly what "MembershipStanding" was meant to answer (in force, suspended) through the accepted contract, and leaves the Governance query to the slice that has a consumer.

## 4. Proposed implementation sequence (each step: RED → GREEN → mutants → guide → commit)

| Step | Scope | Gates | Depends on |
|---|---|---|---|
| **S2-1** | **Renew** (domain fact, `termSegs` union, decision-table rows 1–7, 17, 20–22) **+ the admission term rule (L-2)**; `RenewMembership` use case (pull, `renew-membership`); persistence and the organisation migration for the new kind; C1 on `inForce` change | D-177 §12 rows for Renew and §3 | — |
| **S2-2** | **Suspend / Restore** (restrictions, `NotInForce`, `AlreadyRestored`, `OutsideRestriction`, rows 8–16, 15b, 18–19); `suspendedIntervals()` real; C1 on `suspended` change; C2 carries it | D-177 §12 suspension rows | S2-1 |
| **S2-3** | **Resign now**, self-service (`ownAct`, D-203) and by a recorder; **record a death** (`record-death`) | authority rows of D-177 §12 for these acts | — |
| **S2-4** | **Scheduled resignation:** the declaration record outside the ledger (scheduled → executed / withdrawn / superseded); execution in the `closedThrough` step (the real `DueScheduledResignations`); the D-205 exception; D-223 late execution; withdrawal vs execution by compare-and-set | **MI-1 … MI-4, MI-10, MI-15a … f, LC-4** | S2-3 |
| **S2-5** | **Correct / Void** use cases with recording authority (D-206); a Void reported to the organisation | D-206 | S2-1, S2-2 |
| **S2-6** | **Push shapes, Membership's side:** apply an exclusion part (K-6); grouped renewals (marker with a unique group key, all or none, size bound); the Annul push. **Proven with a test producer; the real producer is S3** | D-177 §12 grouped rows; §19 M-8 test | S2-1, S2-3 |

**Each step:**
- one commit `(PBDIGIT-354)`;
- a guide in `developer_guide/membership/` (next number after 24);
- administrator notes only where a step adds an organisation migration (S2-1, S2-2, S2-4).

## 5. Rules carried into every step

- **Domain:** pure PHP, no Laravel. **Application:** ports and DTOs. **Infrastructure:** adapters. HTTP stays thin (none planned in S2).
- **Authority is never caller-supplied:** the act asks `AuthorityCheckPort`; `NotAuthorised`, `Undetermined` and a missing answer are all refused. **The interim adapter stays unbound (G-3).**
- **Organisation database only, through the routing contract (D-233).** No legacy table. No cross-context infrastructure import.
- **No lifecycle state, transition or legal consequence beyond the accepted texts.** A missing rule is escalated, never invented.
- **No new migration against an operational organisation database** (none exists). Architecture guards are not weakened.
- **Statuses follow evidence:** a step is marked done only after its runs have finished and are sealed (the task 13 lesson).

## 6. Risks

| Risk | Handling |
|---|---|
| Production use before S3 | impossible by construction: `AuthorityCheckPort` is unbound in production, so every act fails closed |
| A stuck scheduled execution blocks the organisation's membership answers (accepted fail-closed cost, D-73) | monitoring is [EXT] before launch (D-210 (4)); S2-4 proves the hold and its release |
| Resignation effective time, legal | [LQ] before launch (D-210 (4)); S2 implements D-201 / D-223 as decided |
| Grouped renewals hold the organisation's membership writes for their duration | size bound: `Inapplicable(too large)`, never partial (D-177 §16 C-3) |

## 7d. S2-4d finding: an ending recorded on or after a due-but-unexecuted scheduled resignation's date (Q-5, awaiting the Human Arb Chief)

**The situation.**
- A member's resignation is scheduled for 3 November.
- On 3 November, before the confirming step has recorded it (the step is late or failing), someone records another ending for the same membership, effective on or after 3 November: the member "resigns now" on 8 November, a death is recorded as of 5 November, or an exclusion is pushed.

**What the accepted texts say.**
- M-TDD-01 §5.2 and MI-3: a declaration is *superseded* when the period ended **earlier** than its date. That case is implemented and tested (S2-4d, part D).
- Nothing says what happens when the other ending is **on or after** the scheduled date.

**What the code does today (not a decision).**
- The later ending is recorded.
- When the confirming step runs, the declaration is marked *superseded*, with a reason that wrongly says "ended before the scheduled date".
- **Effect:** the membership ends on the later date, not the date the member chose. The person counts as a member, with rights, for the days in between.

**Options:**
- **A (recommended): refuse the other ending while the earlier scheduled resignation is pending.**
  - Membership refuses any ending of that period whose effective date is on or after a still-`scheduled` declaration's date (a new refusal, e.g. "a scheduled resignation takes effect first"); the next confirming step records the resignation on its date.
  - Then:
    - a "resign now" is unnecessary (already leaving);
    - a death can still be recorded later as a fact about the person, not as a membership ending;
    - an exclusion is pushed back as inapplicable.
  - Keeps the member's chosen date; fail-closed; no calendar needed (the check compares dates). A kernel or command-boundary rule in S2-4, test-first.
- **B: carry out the scheduled resignation first, automatically.** Recording the other ending first records the scheduled End (on its date), then refuses the other one as "already ended". Same outcome as A, but the command path does the confirming step's work and needs the calendar.
- **C: let the later ending stand.** Keep today's behaviour, but correct the reason to "superseded by a later ending recorded before execution". The member's date is lost and they count as a member for the extra days. Only defensible if the later ending is deliberate.

**S2-4 is not marked Done until Q-5 is decided and implemented.**

## 7c. S2-4 preflight (2026-11-15): which calendar decides "the date has arrived" (Q-4, **decided: D-239, Option A**; **S2-4a … S2-4d DONE 2026-11-15**; S2-4 waits on **Q-5** (§7d))

**The accepted rules for S2-4** (M-TDD-01 §5.2, D-204, D-205, D-209, D-223; D-208 §2):
- a resignation with a later effective date is a **declaration held outside the ledger**: `scheduled` → `executed` / `withdrawn` / `superseded` (the period ended earlier);
- **one** scheduled declaration per period;
- withdrawal only while `scheduled` and before the date has started;
- **execution in the `closedThrough` step:** `closedThrough` may not pass the start of an **organisation-local date D** until every declaration with effective date ≤ D has its `End(resignation)` recorded, in the same step. A failed execution holds `closedThrough` (MI-10);
- the End takes effect on the scheduled date with `decidedAt` = the declaration date: **D-205's single exception**, recorded on or after the date, never before (D-223, MI-15a … f);
- withdrawal and execution serialise under the ledger-clock lock with a compare-and-set on the declaration (MI-2); LC-4 is proved here.

**Already in place:** `DueScheduledResignations::executeDue($db, $organisation, Instant $t)`, called inside the closing transaction (`LedgerClockCloser`), bound today to `NoDueScheduledResignations`; `record-resignation` with a declared effective date (S2-3a/b).

**Finding: the organisation's time zone has no accepted owner or source in the new architecture.**
- The closing step knows only the database instant `t`. "Is the effective date reached?" needs `t` converted to the **organisation's local date**.
- Z1 (2026-10-09) assumed the **legacy central** `organisations.timezone` (default `UTC`). The organisation-isolation rules (PBDIGIT-345, D-235) forbid Membership from reading legacy central tables.
- No accepted Organisation, Platform or Governance design owns an organisation time zone.
- A wrong calendar either ends a membership early, before midnight local time in Nepal (UTC+5:45), or late.

**Options (Q-4):**
- **A (recommended): a consumer-owned Membership port `OrganisationCalendar`** ("the organisation's local date at instant t").
  - Its production answer belongs to the **Organisation context (S4)**, which owns the organisation's own settings.
  - Until S4, **no production binding**: the closing step keeps `NoDueScheduledResignations`, which is safe, because no scheduled resignation can be recorded in production anyway (no authority before S3).
  - Development and tests use an explicit test calendar.
  - **Effect:** S2-4 is fully built and proven now; it goes live when S3 (authority) and S4 (calendar) exist.
- **B: Membership stores the time zone itself,** on its ledger-clock row in the organisation's database, set at provisioning.
  - **Effect:** usable sooner, but Membership owns an organisation-wide setting, and something must set it: founding (Platform) or an officer (needs Governance).
- **C: UTC for release 1.**
  - **Effect:** simplest, but wrong near midnight for every non-UTC organisation. Z2 was rejected on 2026-10-09 for this reason.
- *(Reading the legacy `organisations.timezone` is not offered: it would break the isolation rules.)*

**Also derived from the accepted texts (no decision needed):** the withdrawal request names **the scheduled declaration** (D-208 §2 "withdraw" shape), so `AuthorityRequest` gains an optional `declarationRef`, additive, like S2-3b's `evidenceRef`.

**Proposed S2-4 steps once Q-4 is decided** (each RED → GREEN → mutants → evidence):
1. **S2-4a:** the declaration (domain): states, one per period, superseded rule, the D-205 exception in the kernel (`End` from a declaration, `effectiveAt` > `decidedAt` allowed only here; never before the recording date).
2. **S2-4b:** schedule and withdraw use cases (`record-resignation`, declared date > declaration date; withdraw shape); persistence (a declarations table, central and organisation, parity).
3. **S2-4c:** execution in the closing step (the real `DueScheduledResignations`, the calendar port); MI-10 hold on failure; LC-4; late execution (D-223).
4. **S2-4d:** withdraw vs execute races on PostgreSQL (MI-2); C1 / C2 show nothing before execution (MI-4); routing and isolation.

**No S2-4 code was written until Q-4 was decided** (D-239: an `OrganisationCalendar` port; production binding by Organisation S4; fail-closed until then).

## 7b. S2-3 readiness (2026-11-15): the authority contract must grow first (Q-3, **decided: D-238, Option A**; **S2-3a DONE 2026-11-15**, reviewed; **S2-3b DONE 2026-11-15**; S2-3 complete)

**Finding.** Governance completion rev 5 (accepted, D-208) §1–§2 and C-1a define the AuthorityCheck that S2-3 needs:
- the **request** carries the subject for subject-specific acts (`requestRef`: the member's `PersonRef`, the period, for a resignation the declared effective date, and a request id);
- the **answer** carries the six ratified fields **plus a basis**: `outcome` (Authorised · NotAuthorised · **Undetermined**), `evaluatedFor`, **`competenceKind`** (which gains **`ownAct`**, D-203, valid only for self-service `record-resignation` with actor = subject), `decisionRef`, `evidence`;
- the act kinds are **`record-resignation`** (self-service or recorder) and **`record-death`** (with an evidence reference). **`exclude-member` is push-only:** no office exercises it.

**The code today:**
- `AuthorityCheckPort::check(tenant, actor, act)` carries **no subject**;
- `AuthorityDecision` is granted / denied only (Undetermined collapses into denied, without its reason);
- `AuthorityEvidence` and `ProvenanceJson` store the six fields and `competenceVerified`, **no basis**;
- `EndMembership` asks one generic `MembershipAct::End` for every end kind, and **accepts `exclusion` by pull**, which the accepted design reserves for the push (S2-6).

**Consequence:** self-service resignation (`ownAct`) cannot be implemented correctly on today's port. Governance needs the subject to answer "actor = subject", and Membership needs the basis to record it.

**Options (Q-3):**
- **A (recommended): S2-3 in two parts.**
  - **S2-3a:** extend Membership's consumer-owned port to the accepted shape. That means an `AuthorityRequest` (act, actor, subject `requestRef`), an answer with `outcome` and the basis (`competenceKind` incl. `ownAct`, `evaluatedFor`, `decisionRef`), stored in `ProvenanceJson` with old rows still readable (absent basis = "not recorded"). Admit, End, Renew, Suspend and Restore are moved to it, behaviour unchanged. End asks `record-resignation` / `record-death` by kind, and a pull exclusion is refused (push only).
  - **S2-3b:** resign now (self-service and recorder) and record a death, on that port.
  - **Cost:** a wider change that touches stored evidence.
  - **Gain:** the accepted contract, and S3 plugs in without a second rework.
- **B: minimal.** Pass the subject only; keep the evidence as it is (`ownAct` written into `ruleRef`).
  - **Cost:** it departs from the accepted answer shape; S3 must rework it; Undetermined is still not distinguishable.
- **C: defer self-service to S3.** Build the recorder resignation and death on today's port now.
  - **Cost:** S2-3 stays partial; a member cannot resign themselves until S3; the pull-exclusion gap stays open until fixed separately.

**No S2-3 code was written until Q-3 was decided** (D-238, 2026-11-15: S2-3a, then S2-3b after review).

## 7a. Q-2 (decided 2026-11-15, D-237: Option A; implemented in S2-5)

**Q-2: the rule for correcting a Renew's `until` (D-177 row 20).** A Correct today may not set a date after its decision date; a term's end is normally in the future.
- **Option A (recommended):** a corrected `until` may be any date after the renewal's start, or open-ended. The "not after the decision date" rule applies only to effective dates. Delivered with S2-5.
- **Option B:** correcting a Renew stays refused; a wrong term is fixed by Annul or Void of the Renew plus a new Renew.

## 7. Decisions requested (decided 2026-11-15, D-236)

1. **Q-1:** "`MembershipStanding`" = the C1 / C2 `inForce` / `suspended` intervals, with no new contract in S2 (**Option A, recommended**), or a new query port now (Option B).
2. **EP-01:** approve this plan's sequence S2-1 … S2-6, or reorder or trim it. In particular, **S2-6 (push shapes)** may wait for S3, if you prefer to build each push contract together with its producer.

## 8. Progress

- 2026-11-15: readiness assessment and this plan written.
- 2026-11-15: **approved (D-236):** Q-1 Option A (standing = the C1 / C2 `inForce` / `suspended` intervals; no `MembershipStanding` contract in S2); S2-1 … S2-6 approved in full. **S2-1 started.**
- 2026-11-15: **S2-1 implemented:** Renew (D-177 rows 1–7, 17, 21–23), the admission-term rule, the `RenewMembership` use case, both migrations, C1. Proven: kernel 22, application 12, PostgreSQL 12, routing 1; full cluster 423 exit 0; database-free 942; 21 mutants, 0 survivors; architecture the same 4 pre-existing failures; no legacy regression. Guide 25; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-1/`.
  - **Not delivered: row 20 (Correct a Renew's `until`).** No accepted text gives the dating rule for a corrected `until`. The kernel refuses such a Correct (`CorrectionOfRenewNotSupported`). **Q-2 for the Principal; proposed: deliver row 20 with S2-5 (Correct / Void) once decided.**
  - **Reading recorded:** D-177 row 1 (decided 20 Dec, effective 1 Jan) cannot be recorded as written under the later D-205 (nothing future-dated). It is realised as a renewal effective on its decision date with a later `until` (segments are a set; `inForce` is continuous).
- 2026-11-15: **S2-4d DONE (except Q-5):** withdrawal decided at ledger time (the store, under the ledger-clock lock, one reading, the organisation's date; `ScheduledResignation::withdraw(…, $organisationToday)`); MI-2 proved with real concurrent processes (both forced orders + 8 free rounds); MI-4 C1/C2 before, during (due, unexecuted) and after (late) execution; part D settled case (an earlier ending supersedes, MI-3); routing and isolation through two real organisation databases (`RoutedScheduledResignationStore`, bound but unbuildable without the calendar); guard `MembershipScheduledResignationWiringTest`. Cluster 512, database-free 1073, architecture 4 known failures; 8 mutants (5 assertion, 2 error by design, 1 argued equivalent). Guide 32; evidence `…/2026-11-15-pbdigit354-s2-4d/`. **Q-5 found** (§7d): an ending on or after a due declaration's date has no accepted rule; S2-4 not Done.
- 2026-11-15: **S2-4c DONE (execution in the ledger close):** the `OrganisationCalendar` port (D-239); `ScheduledResignationExecutor`, the real `DueScheduledResignations`: in the close's transaction, the local date of the close's reading, the due declarations locked, each End recorded at the close's reading (`appendWithinClose`) and the declaration marked executed; an ended period → superseded (MI-3); any failure → the close rolls back and `closedThrough` holds (MI-10, LC-4). Production unchanged (`NoDueScheduledResignations` until S4). Post-merge baseline (Identity I-1/I-2): cluster 493. After: cluster 502, database-free 1071, architecture 4 known failures; 8 mutants, all assertion kills. Guide 31; evidence `…/2026-11-15-pbdigit354-s2-4c/`. **Still open, for S2-4d:** the two-session race (MI-2); the routing proof; a withdrawal's "date has started" against the calendar under the clock lock (today: the caller-supplied date, D-5(a) Z1); resign-now while a due declaration is unexecuted.
- 2026-11-15: **S2-4b DONE (schedule, withdraw, declaration store):** use cases `ScheduleResignation` / `WithdrawScheduledResignation` on the D-238 order (`DecideAndRecordDeclaration`; `DecideAndAppend::authorisationRefusal` shared); `AuthorityRequest.declarationRef` (withdraw shape, additive); `PostgresScheduledResignationStore` (version compare-and-set on schedule, state compare-and-set on transition); migrations central 360000 / organisation 300700 (same SQL; one pending per period, CHECKs, history trigger, no DELETE); a withdrawal records who and when. Cluster 470, database-free 1062, architecture 4 known failures; 19 mutants, 0 survivors (15 assertion, 3 database refusal, 1 fail-closed reload). **Found and fixed:** `CREATE FUNCTION` broke `migrate:fresh` (legacy suite). Guide 30; administrator notes; evidence `…/2026-11-15-pbdigit354-s2-4b/`. **Carried to S2-4c:** withdrawal's "date has started" against the organisation calendar under the ledger-clock lock (today: the caller-supplied date, D-5(a) Z1); superseding a declaration when its period ends earlier.
- 2026-11-15: **S2-4a DONE (domain):** `ScheduledResignation` (scheduled / executed / withdrawn / superseded; one per period; withdrawal only before the date) and `Member::decideScheduledResignationEnd`, D-205's single exception (effective on the scheduled date, decided by the declaration, recorded on or after the date). Kernel 279; 15 mutants, all assertion kills; K7 proves the exception does not leak. Guide 29; evidence `…/2026-11-15-pbdigit354-s2-4a/`.
- 2026-11-15: **S2-3b DONE:** resign now (`ResignMembership`: effective on the declaration date, current period, `record-resignation`, `ownAct` only for the member) and record a death (`RecordDeath`: evidence reference required before asking, `record-death` evaluated for the recording date, evidence kept as `declarationRef`); `EndMembership(death)` refused (no evidence); `AuthorityRequest.evidenceRef` added (an S2-3a gap against D-208 §2). Full cluster 449; database-free 1028; 12 mutants, 0 survivors (10 assertion, 2 error); architecture the same 4. Guide 28; evidence `…/2026-11-15-pbdigit354-s2-3b/`. **S2-3 DONE.** Found outside the step: PBDIGIT-355.
- 2026-11-15: **S2-3a DONE (D-238):** `AuthorityRequest` (act, actor, subject, period, decision date, declared effective date, request id); answers with the basis (outcome incl. Undetermined, `competenceKind` incl. `ownAct`, `evaluatedFor`, `decisionRef`, `evidence`) stored in the provenance jsonb (no migration; old rows read as "not recorded"); `ownAct` only for self-service `record-resignation`; End asks by kind; pull exclusion refused; no existence leak; no creation before authorisation. Full cluster 445; database-free 1015; 20 mutants, 0 survivors; architecture the same 4 pre-existing failures. Guide 27; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-3a/`.
- 2026-11-15: **Q-2 decided, D-237 (Option A):** a corrected Renew `until` is any date after the renewal's start, or open-ended; D-205 binds effective dates only; the original stays traceable. **S2-5 implements it; until then the kernel refuses.**
- 2026-11-15: **S2-2 DONE** (S2-2a `358e66b59`, kernel; then the use cases, persistence, both migrations, routing and C1): D-177 rows 8–16, 15b, 18–19, 22–23. Proven: kernel 27, application 9, interim adapter +2, PostgreSQL 16, routing 1; full cluster 440, 0 failing; database-free 981; 22 mutants, 0 survivors; architecture the same 4 pre-existing failures; no legacy regression. Guide 26; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-2/`.
  - **Found and fixed:** the unbound `InterimRoleAuthority` crashed on Renew (since S2-1), Suspend and Restore; it now denies them with evidence, as it already did for End.
  - **Deferred, as planned:** correcting a Suspend's or Restore's date (row 14) → S2-5.
  - **Guard touched (exact file):** the S2-2 central migration added to `MembershipLedgerIsolationTest`'s list, as for S2-1.
  - **Guard touched:** the new central ledger migration was added, by exact name, to `MembershipLedgerIsolationTest`'s list of the ledger's own migrations (the precedent of every earlier ledger migration).

## Traceability

D-163 · D-177 · D-194 · D-201 · D-203 · D-204 · D-205 · D-206 · D-208 · D-209 · D-210 · D-216 (G-3, G-4) · D-223 · D-233 · D-235 · PBDIGIT-343 · PBDIGIT-345 · PBDIGIT-348 (R-2).
