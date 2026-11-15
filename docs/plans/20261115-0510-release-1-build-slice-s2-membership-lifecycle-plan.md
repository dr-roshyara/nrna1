# Release 1, build slice S2: the Membership lifecycle (readiness assessment and proposed plan)

| | |
|---|---|
| **Status** | **APPROVED by the Principal 2026-11-15 (D-236): Q-1 Option A; S2-1 … S2-6 in full.** S2-1 implemented and proven **except D-177 row 20, which waits for Q-2**; S2-2 not started |
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

## 7a. Decision requested after S2-1 (Q-2)

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
  - **Guard touched:** the new central ledger migration was added, by exact name, to `MembershipLedgerIsolationTest`'s list of the ledger's own migrations (the precedent of every earlier ledger migration).

## Traceability

D-163 · D-177 · D-194 · D-201 · D-203 · D-204 · D-205 · D-206 · D-208 · D-209 · D-210 · D-216 (G-3, G-4) · D-223 · D-233 · D-235 · PBDIGIT-343 · PBDIGIT-345 · PBDIGIT-348 (R-2).
