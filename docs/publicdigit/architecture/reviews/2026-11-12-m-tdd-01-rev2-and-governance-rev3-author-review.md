# Review of M-TDD-01 rev 2 and Governance completion rev 3: findings and proposed corrections

| | |
|---|---|
| **Status** | **Author's self-review, NOT independent** (Claude wrote both designs). Findings and proposed corrections only. **Neither design document is changed by this review** |
| **Subjects** | `2026-11-12-m-tdd-01-rev2-membership-lifecycle-and-release-1-integration.md` (`37f66b4b…`); `2026-11-12-governance-completion-tactical-design.md` rev 3 (`fa5b38a8…`) |
| **Commission** | Principal, 2026-11-12: "Independent Principal DDD Review and Tactical Completion" |
| **Sources re-checked** | decision record (D-101, D-103, D-152, D-153, D-56, D-161, D-168, D-194, D-201 … D-205); RA; C1 / C2 spec (D-162) §4, §6, §11; CR-02; Organisation §50 (accepted D-156); NFD (accepted D-158); Reference Geography v3.11 §6, §8; `composer.json` |

## A. Business summary

| Capability | What it does | How it relies on the others |
|---|---|---|
| **Membership** | keeps the one membership record per person and organisation | asks Governance who may act; learns who acts from Identity |
| **Organisation** | keeps where each person belongs | counts a member's place only while Membership says the membership covers the date |
| **Newsletter** | sends one series issue to a frozen audience | gets the audience from Organisation at one moment, and the editor's authority from Governance |
| **Forum** | lets people read and post in their units' forums | asks Organisation and Governance at each read and post |
| **Geography** | the official map | **not used in release 1** |

## B. Findings

| # | Finding | Section | Class | Why it matters | Proposed correction (§D) |
|---|---|---|---|---|---|
| **F-1** | **A late scheduled-resignation execution leaves the person eligible after the effective date.** D-204 keeps the declaration out of C1 / C2 until executed, and §5.2 allows late execution. Between the date and the late recording, Organisation, Newsletter and Forum still see a member | M-TDD-01 §5.2, §5.3 | **BLOCKER** | breaks D-201 / D-204's business guarantee (effect on the date) | **D-1:** couple execution to Membership's knowledge boundary |
| **F-2** | GC-12 still requires "the original End reference" for a withdrawal | Gov §7 GC-12 | **MAJOR** (contradicts D-204) | proof tests would test the wrong rule | D-2 |
| **F-3** | **`ownAct` still shown as a Principal decision (O-1, §2.3 "for the Principal's decision")**, although **D-203 approved it** | Gov §2.3, §8 O-1 | **MINOR** (status and provenance) | the document misstates the decision record | D-2 |
| **F-4** | **no authority act kinds for Annul / Void / Correct** of membership facts; the proposed reading is not yet an accepted contract | M-TDD-01 §5.4 | **MAJOR** + **PRINCIPAL DECISION** (Void) | corrections impossible, or decided by guess | D-3; **question G-1** |
| **F-5** | **Membership ledger clock adjusts timestamps** (spec §4 :74) vs the D-73 participant contract (Geography PO-6, PO-8) | M-TDD-01 §5.5 | **EXTERNAL GATE** (ARB amendment of the accepted spec mechanism) | historical cross-context guarantees cannot be claimed | D-4: smallest compliant mechanism |
| **F-6** | **newsletter operational contract incomplete:** issue identity, send idempotency, snapshot evidence, batching, provider acceptance, retry, partial failure, late opt-out, retention and access | M-TDD-01 §7 | **MAJOR** | unreliable or duplicate delivery | D-5 |
| **F-7** | **"pending applicant" has no representation in Organisation** (not a D-101 capacity; an application is Membership's, outside the Member, D-2). **D-153's "pending applicants read where they belong" has nothing to resolve against** | M-TDD-01 §8; D-153 | **MAJOR** + **PRINCIPAL DECISION** | applicants either see nothing, or a rule is invented | **question G-2** |
| **F-8** | **non-member belongings (Worker, Guest) need a placement act and an authority for it**; only `place-member` exists | M-TDD-01 §6, §8 | **MAJOR** | workers and guests cannot be placed → read nothing | D-6 (act kind; derivable from D-154's pattern) |
| **F-9** | "forum open" must mean **a valid moderator designation (tenure) at the read or post time, not a moderator online** | M-TDD-01 §8 | **MINOR** | ambiguity | D-7 |
| **F-10** | **committee forums are an accepted requirement (D-56, D-152), deferred, not dropped**; the deferral must be stated as a release-scope boundary | M-TDD-01 §8 | **MINOR** | silent loss of a requirement | D-7 |
| **F-11** | **admission and placement across contexts:** Membership (`Admit`) and Organisation (belonging) are separate transactions; their consistency path (outbox / inbox) is stated only for C1 | M-TDD-01 §5.1, §6 | **MAJOR** | half-completed admission (member without placement) | D-8 |
| **F-12** | **project instructions say Laravel 11; the repository is Laravel 12** (`composer.json` `^12.0`; Inertia 2) | (project) | **MINOR** (outside both designs) | implementation guidance is stale | note only |
| F-13 | ownership boundaries, contract directions, D-161 / D-168 rules, belonging rules (§50), Geography later boundary | both | **ACCEPTED** | — | none |
| F-14 | Geography PO-1 … PO-8; the four-point Identity contract; Organisation model fingerprint drift (D-156) | M-TDD-01 §9, §6 | **EXTERNAL GATE** | — | keep visible |
| F-15 | legal basis for newsletters; resignation effective time; retention of snapshots and delivery records | — | **LEGAL QUESTION** | — | counsel |

## C. Ownership and contract assessment

- **Boundaries and directions are correct:**
  - Membership never reads Organisation;
  - Communication and Deliberation never read Membership (CR-02 :37);
  - no context copies another's truth;
  - refusals are never "not a member".
- **Missing contracts:**
  - scheduled-declaration reconciliation with the knowledge boundary (F-1);
  - correction act kinds (F-4);
  - non-member placement (F-8);
  - the admission-to-placement path (F-11);
  - the newsletter delivery protocol (F-6).

## D. Proposed corrections (minimum; to be written after the Principal reviews these findings)

**D-1 (F-1): due declarations are executed by the knowledge-boundary step.**
- **Today:** Membership's `closedThrough` step is a separate committed step (spec K-2, A3), and C2 refuses any τ > `closedThrough`.
- **Proposed:**
  - **before `closedThrough` may advance past the start of an organisation-local date D, every scheduled resignation with effective date ≤ D is executed** (its End recorded), in that same step under the ledger-clock lock;
  - **if an execution fails, `closedThrough` does not advance** → C2 answers `BOUNDARY_NOT_CLOSED` for later τ → Organisation, Newsletter and Forum **refuse rather than act on a member who has resigned**.
- **Consequences:**
  - **no contract change** (C2's shape and refusals are as accepted);
  - **historical answers stay honest** (the End's `recordedAt` ≥ the date, so "as known at" earlier τ is unchanged);
  - **availability cost:** one failing execution blocks the organisation's membership answers until fixed. This is fail-closed, as D-73 requires.
- **Withdrawal vs execution:**
  - both take the ledger-clock lock (spec :190 order);
  - withdrawal is valid only if, under the lock, the clock reading is before the start of the effective date (organisation-local, D-5(a) Z1);
  - **exactly one of them changes the declaration's state** (compare-and-set on the declaration);
  - **concurrent or delayed: one wins.**
- **The organisation-local date boundary is computed from the organisation's time zone** (D-5(a)).

**D-2 (F-2, F-3):**
- **GC-12:** "withdrawal refused once the effective date has started (organisation-local), or when the declaration is not `scheduled`; requires the declaration reference".
- **§2.3 / O-1:** status "approved by D-203 (§11.102); ratified with this design's acceptance".

**D-3 (F-4): correction authority. Proposed reading, with one genuine choice (G-1):**

| Operation (RA) | Meaning (RA) | Authority (proposed) | Basis |
|---|---|---|---|
| **Correct** | a real fact recorded wrongly; replaces values | **recording authority with evidence** (`correct-membership-record`, pulled) | D-202 item 8 (the same rule for Governance records); V-59, G-6 |
| **Annul** | a valid decision set aside (ex tunc) | **a new decision of the body competent for the original act** (pushed Resolution part), **or a tribunal determination remedy** (Adjudication, applied as a fact; frozen boundary respected) | D-202 item 8 ("changing what was decided needs a new decision"); D-67; GM §40.1 |
| **Void** | the record never reflected reality | **G-1: recording authority with evidence, or the competent body?** | not derivable: a Void can remove a fact that a body decided |

**D-4 (F-5): the smallest compliant ledger clock.**
- **Replace** `max(appClock, last + 1 µs, closedThrough + 1 µs)` **with:**
  - read `clock_timestamp()` under the ledger-clock lock;
  - **if it is ≤ `closedThrough` or < the last `recordedAt`, refuse and retry. Never adjust.**
- **This is an amendment of the accepted spec's mechanism (D-162), not of the C1 / C2 contract.** It needs ARB acceptance.
- **It resolves PO-8 for Membership** once A1–A3 and the refusal path are proved.

**D-5 (F-6): newsletter delivery protocol.**

| # | Question | Proposed rule | Basis |
|---|---|---|---|
| 1 | identity | `seriesId`; `issueId` per edition; **one send per issue** | NFD |
| 2 | send idempotency | `Send(issueId)` is idempotent: a repeat returns the existing send; a second send of the same issue is **refused** (a correction is a new issue) | [TD] |
| 3 | snapshot | frozen at one τ in one transaction: per recipient `PersonRef`, inclusion basis (belonging, participation rule or "platform default (D-161)", not opted out), contact reference | NFD; D-73 |
| 4 | batching and acceptance | per-recipient delivery record: `queued` → `handed-to-provider` → `accepted` \| `rejected` \| `failed` (never "read") | NFD |
| 5 | retry | provider idempotency key = (issueId, recipient); **only non-accepted records are retried** | [TD] |
| 6 | partial failure, crash | the outbox drives delivery; a restart resumes from the records; nothing accepted is resent | [TD] |
| 7 | opt-out after the snapshot | **excluded at delivery** (an opt-out takes effect from its act, D-151) | D-151 |
| 8 | membership or belonging change after the snapshot | **the snapshot governs** (one τ). Suspension or ending after τ does not recall a send already frozen | D-73 |
| 9 | contact change or missing contact | the contact is read at delivery from the person profile (D-101); missing → excluded, reason recorded | NFD |
| 10 | retention and access | editors: counts only (D-151). Snapshots and records: access per the organisation's rules; **retention [LQ]** | D-151; LQ |
| 11 | attempt ≠ acceptance ≠ reading | recorded separately; **reading is never recorded** | NFD |

**D-6 (F-8):** a Governance act kind **`place-person(capacity, unit)`** for non-member capacities (Worker, Guest), following D-154 / D-172 (a designated body; rules silent → `Undetermined`). It is **derivable as the same pattern as `place-member`**, but **requires a Governance addition** (as D-163 / D-190).

**D-7 (F-9, F-10):**
- **"Forum open"** = a moderator tenure (designation, `moderate(forum)` competence) valid at the read or post time; **nobody needs to be online**.
- **Committee forums:** an accepted requirement (D-56, D-152) **deferred to a later release**. It is listed in the release-scope boundary.

**D-8 (F-11): admission and placement.**
- **Governance answers both act kinds in one request** (note 3); Membership records `Admit` and emits C1.
- **Organisation records the belonging from the admission act** (basis = the admission act, §50.15:1340), **consuming the same authority answer**, idempotent on the admission fact id, via **Membership's outbox → Organisation's inbox** (same database, separate transactions).
- **Until applied, the root-unit default holds** (§50.15:1342), so a member is never without a unit. The placement then takes effect from the admission date (backdated within invariants).

## E. Scenario results

| # | Scenario | Result |
|---|---|---|
| 1–3 | registration, admission, newsletter receipt | **passes after D-8, D-5** |
| 4 | opt-out and re-subscription | passes (D-5 item 7) |
| 5–6 | forum reading and posting | passes (D-7) |
| 7–9 | suspension, restoration, expiry and renewal | **passes** (accepted D-177) |
| 10 | immediate resignation | passes |
| 11 | scheduled resignation and withdrawal | passes (D-1 race rule) |
| **new 17** | **scheduled resignation, execution delayed** | **fails today (F-1); passes after D-1** |
| **new 18** | **scheduled resignation vs concurrent withdrawal** | passes after D-1 |
| 12–13 | exclusion; death | passes |
| **new 19** | **correction, void, annul** | **refused until D-3 and G-1** |
| **new 20** | **newsletter partial provider failure and retry** | passes after D-5 |
| **new 21** | **forum read by a pending applicant, worker or guest** | **fails today (F-7, F-8)** |
| **new 22** | **moderator designated, then removed** | passes (D-7: the forum becomes read-only from the tenure end) |
| 14 | transfer | passes |
| 15–16 | Geography revision (later); Geography unavailable | passes (release 1 unaffected) |

## F. Proof obligations (none executed)

| Proof | Proves | Status |
|---|---|---|
| A1–A3 | C1 / C2 ledger clock | **not executed; release-1 build gate** |
| MI-1 … MI-9 | M-TDD-01 rev 2 | not executed; build gates |
| GC-1 … GC-13 | Governance completion | not executed; build gates (GC-12 to be corrected) |
| new: MI-10 | **D-1:** no answer at τ ≥ an effective date before the due End is recorded; a failed execution holds `closedThrough` | not executed; build gate |
| new: MI-11 | D-5: one send per issue; no duplicate acceptance; late opt-out excluded | not executed; build gate |
| new: MI-12 | D-8: admission → placement exactly once; root default meanwhile | not executed; build gate |
| new: MI-13 | D-4: no timestamp adjusted; refusal and retry on regression | not executed; **gate with ARB acceptance of D-4** |
| PO-1 … PO-8, Identity contract | Geography | not executed; **later-release gate** (PO-8 also concerns Membership) |

## G. Principal decisions (genuine; one at a time)

- **G-1 (F-4):** who may **Void** a membership record ("the record never reflected reality")? Recording authority with evidence, or only the competent body?
- **G-2 (F-7):** may a **pending applicant** read forums **before** admission, and where?

## H. Verdict (author's)

**READY AFTER SPECIFIC CORRECTIONS:** D-1 … D-8, after G-1 and G-2.
- **F-1 is a blocker with a contract-compatible fix.**
- **No ratified decision is contradicted.**
- **Proof gates stay open.**

## Implementation feasibility (Laravel 12, Vue 3, Inertia 2; DDD boundaries framework-independent)

| Concern | Placement |
|---|---|
| aggregates (Member with facts; ScheduledResignation; PersonBelonging; Series / Issue; Forum / Post) | **Domain** layer, pure PHP (existing kernel `app/Contexts/Membership/Domain/Organisational/`) |
| commands, queries, AuthorityCheck consumption | **Application** layer per context; DTOs; repository interfaces; no facades |
| ledger clock, outbox / inbox, delivery worker, `closedThrough` step | **Infrastructure:** PostgreSQL row locks (`FOR UPDATE`), READ COMMITTED; Laravel queues for outbox delivery (idempotent consumers) |
| projections (C1 current views) | Infrastructure read models; **never used for historical answers** |
| context-facing services (C2, K-4, AuthorityCheck) | Application services behind interfaces; architecture tests forbid cross-context imports and table access (MI-9) |
| UI (Inertia pages) | Http layer only; `router.post` |
