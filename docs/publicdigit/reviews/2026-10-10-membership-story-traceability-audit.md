# Membership story traceability audit (read-only), 2026-10-10

**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261010_0945_update_stories.md` ("Continuous Story Traceability Audit").
**Change control:**
- read-only;
- **no story, backlog-index, CONTEXT or contract change** was made, and no ID was created;
- C-1 was not touched.

This report is the only file written.
**Author:** Developer (Claude). **Status:** findings and proposals for PR / PO / Human ARB. Nothing here is an approved requirement.

## 0. Disagreement with the commission's premise (recorded first)

**The premise is out of date.** The commission assumes the C-1 plan still awaits approval and that C-1 has not started. The record says otherwise:
- the PO approved the plan with conditions P-1 to P-3 and constraints 1 to 7;
- the gate was re-evaluated and all 7 conditions closed (`2026-10-10-membership-c1-gate-decision-record.md`);
- C-1 was implemented (`f7c251354` … `5765b9fa3`) and awaits PR / PO review (`2026-10-10-membership-c1-implementation-review.md`).

**So this is the close-of-slice check, not a pre-implementation audit.** The commission's "Ongoing rule" describes this check: it audits C-1 as implemented, not yet accepted.

## 1. Sources inspected

- `backlog/README.md` (no uncommitted edits at audit time) and `PBDIGIT-EPIC-02`.
- Membership stories 258, 259, 260, 264, 267 and 268.
- Adjacent stories:
  - Election / Voting: 68, 213, 221, 249, 265;
  - security: 266;
  - legacy defects: 209, 229, 252, 257.
- Correction contract (approved) §3 / §4 / §9 / §10 / §13.7 / §14.3 / §14.4 / §15.7.
- C-1 gate record, C-1 plan, D-COR-5 decision request, Human ARB decision record (ARB-MEM-01, ARB-SEC-01, ARB-DEC-01, ARB-MEM-02, ARB-TEST-01), C-1 implementation review.

**Evidence classes used below:**
- **[APP]** approved requirement or decision;
- **[PROP]** proposal;
- **[DER]** derived invariant;
- **[TEST]** test evidence;
- **[OPEN]** unresolved.

## 2. Traceability matrix (ratified-architecture Membership track)

| Story | Status (as recorded) | Rule / decision | AC consistency | Dependencies / blockers | Missing obligation | Disposition | Evidence |
|---|---|---|---|---|---|---|---|
| **258** Slice 0 kernel | GREEN WITH CONDITIONS; "condition: confirm D-1" | ratified act; C-S0-1, C-S0-2 [APP]; INV-M stable identity | consistent. Out-of-scope list names Annul / Void, now owned by 268 (no overlap) | the condition is **met**: ARB-DEC-01 preserves D-1 as INV-FID [APP]. Still no formal PR acceptance act found | — | **UPDATE** (status: D-1 condition met; acceptance act still outstanding) | `2026-10-09-membership-human-arb-decision-record.md` ARB-DEC-01; story :10 |
| **259** Slice 1a | GREEN WITH CONDITIONS; confirm S1-1, S1-3 | INV-M (creation race), IDEM, tenant isolation [APP] | consistent | no record found confirming S1-1 / S1-3 | — | **KEEP** (no evidence to change) | story :10 |
| **260** Slice 1b | GREEN; conditionally accepted (ARB-MEM-02) | DB-1, EP-Q6 = T1 [APP] | consistent with ARB-MEM-02 ("do not describe as formally accepted") | formal acceptance act outstanding | — | **KEEP** | story; ARB-MEM-02 |
| **264** 1b follow-ups | OPEN, not authorised; PR dispositions F-4b, F-5 N1 | — | consistent | depends on 260 acceptance. **Not recorded:** correction contract §10 makes 264 a prerequisite of **C-4** [APP, approved contract] | — | **UPDATE** (add "prerequisite of C-4 (contract §10)" to dependants) | contract §10 C-4 row |
| **267** TG-1 … TG-5 | OPEN, not authorised (ARB-TEST-01); "Index row: pending" | ARB-TEST-01 [APP] | **stale on two points.** (1) The index row **exists** (`README.md:146`, commit `ae3faec5d`). (2) **TG-4 is now partly exercised:** `AnnulVoidTest::test_annulling_without_decided_at_is_refused_and_changes_nothing` asserts that `Decision::fact()` on a refusal throws `LogicException` (`Decision.php:37`). Not re-measured: `Decision.php` was outside the C-1 Infection scope. TG-1 still survives (C-1 run: `Member.php:26/28/31`) [TEST] | authorisation outstanding | — | **UPDATE** (remove "index row pending"; note TG-4 `:37` probably killed, re-measure on execution) | README :146; C-1 review §5 |
| **268** C-1 Annul / Void | IMPLEMENTED, AWAITING REVIEW | D-COR-2, D-COR-5 (A), R-2, N-3, P-1 to P-3 [APP] | **two gaps vs the approved DoD (§15.7), see §3 I-1 and I-2** | PR review, PO acceptance | K-E1 evidence | **UPDATE** (record I-1 / I-2; add the index row) | contract §14.3 K-E1, §15.7 (1); C-1 review §1, §3 |
| **EPIC-02** Membership Management | IMPLEMENTED — NOT VERIFIED; children `PBDIGIT-04…08` | — | covers only the **legacy** journey (04 to 08). **Not mentioned:** the ratified-architecture track (258, 259, 260, 264, 267, 268) and its gates (ARB-MEM-01 cutover gate, ARB-MEM-02) | — | visibility of the track and its gates | **UPDATE** (add a short "Ratified architecture track" section; no status change to 04 to 08) | EPIC-02 file, sections 04 to 08 only |

### Adjacent stories (domain-distinction check)

| Story | Owner | Distinction preserved? | Disposition |
|---|---|---|---|
| 68 ElectionMembership = durable entitlement | Election | yes: organisation membership is an admission prerequisite only (Q-A0 = F1) [APP] | NO ACTION |
| 213 Election-Only voter admission | Voting | yes: admission is separate from registration | NO ACTION |
| 221 Add organisation members as voters | Voting | yes: "Registration ≠ Voter Admission ≠ Voting Entitlement (ADR_20261004_1549); owns registration only" [APP] | NO ACTION |
| 249 Email verification as admission evidence | Voting | yes (admission evidence). **The file has another session's uncommitted line; not touched** | NO ACTION |
| 265 Full-Membership eligibility predicates differ | Election | yes: "out of scope for Membership"; relevant to the cutover reverse projection | KEEP |
| 266 Routes without an authorisation guard | security | not a kernel story; ARB-SEC-01 gate FAIL recorded (no isolated DB) | KEEP |
| 209, 229, 252, 257 | legacy Membership / Voting defects | outside the ratified kernel; no conflict with it | NO ACTION |

## 3. Concrete inconsistencies

- **I-1. K-E1 has no test (C-1, my own gap).**
  - **The criterion [APP]:** contract §14.3 K-E1, "an accepted Annul / Void records `FactAnnulled` / `FactVoided` evidence (the kernel decision carries the event data …)", test type *unit*.
  - **What the code does:** the accepted `Decision` carries the `Annul` / `VoidFact` (id, target, provenance, decidedAt) and `basedOnVersion`. So the data is present [DER].
  - **What is missing:** no test asserts it. The implementation review §1 and the guide do not map K-E1, and the review's "no deviation" statement is therefore too strong.
  - **Remedy (within the approved PBDIGIT-268 scope, test-only):** one unit test asserting that an accepted Annul / Void decision exposes those fields. **Needs your go-ahead, because this commission is read-only.**
- **I-2. §15.7 (1) says "RED first for each of K-A1 … K-X2".**
  - Seven criteria (K-R1, K-R2, K-R3, K-G1, K-H1, K-X1, K-X2) had no RED phase, because earlier GREEN behaviour already satisfied them.
  - This was disclosed, and sensitivity was shown with reverted mutants (review §3).
  - PO constraint 3 ("record each required failing test before its production change") was not breached, since no production change followed.
  - **The deviation from the literal DoD wording needs explicit PR / PO acceptance.**
- **I-3. Contract §10 is not marked superseded where §14 / §15 changed it.**
  - The §10 C-1 row still lists "events `FactAnnulled` / `FactVoided`" in C-1. §13.7 is marked superseded for exactly this, but §10 is not.
  - §10's per-slice criterion includes "27 PostgreSQL Membership tests", which §15.7 (6) does not require for C-1.
  - **Proposed:** an annotation only ("superseded for C-1 by §14.3 K-E1 / §15.7"). The contract is PO-approved, so this needs PO authorisation.
- **I-4. Stale story metadata.**
  - 258: the D-1 condition is met.
  - 267: "index row pending" while the row exists.
  - 268: "index row pending"; the README is now free to edit.
- **I-5. Dependants not recorded.**
  - 264 is a C-4 prerequisite, but neither 264 nor any story records it.
  - C-1 review F-1 (adapter `encode()` typing) belongs to C-4, which has no story yet (see §5).

## 4. Specific checks requested

| Check | Result | Evidence |
|---|---|---|
| One Member per (person, organisation) | preserved: INV-M (Slice 1a creation race, Slice 1b unique key); the C-1 kernel adds no identity path | 259 AC 3; 260 contract |
| Periods, re-entry, append-only, valid time, known-at | preserved: re-entry is a new period (T8 to T10); facts stay append-only; P-KNOWN holds with reversals [TEST] | `AnnulVoidTest`, `C1PropertyTest` |
| Membership ≠ appointment ≠ mandate ≠ registration ≠ entitlement ≠ exercisability | no conflation found: the kernel-purity architecture test forbids Election / Governance vocabulary; 68 / 213 / 221 keep the distinctions | `MembershipSemanticKernelPurityTest`; stories 68, 221 |
| D-COR-2, D-COR-5 reflected | yes in 268, the contract, the plan, the guide and the code (`TargetNotLive`; no implicit consequences, T8 to T10) | `Member.php`; guide 04 |
| C-1 limited to Annul / Void in the kernel | yes: only `Domain/Organisational/` changed; no Correct, consequences, persistence, application, cutover or live writes | C-1 review §1 / §2; `git log` |
| CQ-9 and other open decisions kept open | yes: CQ-9, BD-REV-ER, D-COR-1 / 3 / 4, CQ-3, D-COR-5 (B) remain [OPEN] in contract §9 and are excluded from 268. Owned by no story, which is correct for decisions | contract :5, :206; 268 scope |
| Dependencies / status / index agree | **no**: see I-4, I-5 | §3 |

## 5. Uncovered delivery obligations (searched first; no new IDs proposed now)

Search: `grep -il "cutover|Slice 2|C-2|C-3|C-4|reverse projection|CQ-9"` over `backlog/*.md`. It returned only 264, 265, 268 and two unrelated Election items (72, 73).

| Obligation | Source [APP] | Existing owner | Recommendation |
|---|---|---|---|
| C-2: Correct and consequences | contract §10 | none | **create a story only when the PO authorises preparing C-2.** Blocked by BD-REV-ER and D-COR-3 [OPEN] |
| C-3: application commands / authority / event emission (incl. emitting K-E1 events) | contract §10, §14.3 K-E1 | none | same; blocked by CQ-3 and G-AUTH-1 [OPEN] |
| C-4: persistence of Annul / Void (incl. C-1 F-1 `encode()` typing) | contract §10; C-1 review F-1 | none | same; depends on 264; blocked by D-COR-4 [OPEN] |
| Slice 2: Admit / End application wiring | contract §10 ("may proceed after its own contract") | none | same; Slice 2 decision names are still a proposal (ARB-DEC-01) |
| Cutover / live-write gate | ARB-MEM-01 | recorded in the ARB record and CONTEXT, **not in EPIC-02** | make it visible via the EPIC-02 update (I-4 / §2), not a story |

**No genuinely new obligation outside the approved slice plan was found.** Every gap above is a slice the approved contract already names, and each needs separate authorisation before a story exists.

## 6. Proposed minimal changes (apply only after authorisation)

1. **268:** record I-1 and I-2; add the README index row.
2. **267:** remove "index row pending"; note TG-4 `Decision.php:37` probably killed, to be re-measured.
3. **258:** status wording "D-1 condition met (ARB-DEC-01); formal acceptance act outstanding".
4. **264:** add "prerequisite of C-4 (contract §10)".
5. **EPIC-02:** a short "Ratified architecture track" section listing 258 / 259 / 260 / 264 / 267 / 268, the ARB-MEM-01 and ARB-MEM-02 gates, and C-2 / C-3 / C-4 / Slice 2 as *named, not authorised*.
6. **Contract §10:** annotation only (I-3), if the PO authorises touching the approved contract.
7. **C-1 test (I-1):** one K-E1 unit test plus the review mapping corrected. Test-only, within PBDIGIT-268.

## 7. Decisions requested

| # | Decision | Who |
|---|---|---|
| D-1 | Authorise changes 1 to 5 (story / index / epic metadata only) | PO |
| D-2 | Authorise the K-E1 test (I-1), or accept K-E1 as satisfied by the decision data alone | PO / PR |
| D-3 | Accept the I-2 deviation from §15.7 (1) for the seven verification-only criteria | PR / PO |
| D-4 | Authorise the §10 annotation (I-3) | PO |
| D-5 | When to authorise preparing the C-2 / C-3 / C-4 / Slice 2 stories | PO (C-2 / C-3 also depend on Human ARB items BD-REV-ER and CQ-3) |

## 8. Ongoing rule (adopted as working practice for this track, per the commission)

At each Membership slice close:
1. repeat a focused check of the affected stories and their dependants;
2. record evidence;
3. update authorised status and acceptance criteria;
4. list uncovered obligations;
5. never expand the active slice.

This audit is the first instance. **Strength of claim:** repository-verified for the stories listed. Other EPIC-02 legacy stories (04 to 08) were not re-verified against code.
