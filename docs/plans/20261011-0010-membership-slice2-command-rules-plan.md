# Plan: Membership Slice 2 — Admit / End command rules

> **Current status (rev. 5, 2026-10-11):** Slice 2a **authorised, implemented and hardened** (H1, H2; §15); **committed 2026-10-11** (`834a4f65c` code and tests, `14f89d18e` documents), not pushed; **not accepted**; Slice 2b held. The status line below is the rev. 4 record, kept unchanged and superseded by §15.

**Status:** `revision 4` — S2-A … S2-D, S2-F and Q-E1 (option A) **approved** by the Human ARB on 2026-10-11; S2-E approved in principle with its implementation gate open pending the C-1 reconciliation (§6.1) and the provenance-origin decision S2-G (§4.7). **Implementation of Slice 2a is not authorised.** **Nothing implemented; no test run.** PostgreSQL work is on hold. **This plan authorises nothing.**
**Story:** `PBDIGIT-271` · **Baseline:** HEAD `aa047ca6b` (decision record `53f19fee7` is an ancestor).
**Single sources (linked, not copied):** decision record D-1 … D-5 (`docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`, "Decision record"; analysis §86–198; Slice 2 line §254) · EP-01 design §18–§19 · Slice 0 contract · Slice 1a contract (`2026-10-09-membership-slice1a-contract.md`) · ratified architecture (`2026-10-08-membership-architecture-ratified.md`).

**Labels:** **[F]** verified repository fact (file:line) · **[R]** ratified decision · **[E]** engineering recommendation, needs approval · **[Q]** open legal / Governance question · **[A]** assumption to validate.

**Revision history:**
- rev. 0: initial draft.
- rev. 1: the 2a / 2b split; a required decision date; End fails closed.
- **rev. 5 (this):** hardening after the independent review: H1-a / H1-b (infinite decision / effective date = invalid input, `InvalidArgumentException`), H2 (name-resolving creation-path scan; named exclusion `PBDIGIT-272`); §14 superseded note; §15 progress. Human PO / ARB decisions of 2026-10-11.
- **rev. 4:** provenance-origin finding and R8 / S2-G (§4.7, step 8b, AC-7); the overlap mutant routed through the sensitivity kit's scratch copy, with its limitation stated (§6.1).
- **rev. 3:** Human ARB decisions recorded in §12 (S2-A … S2-D, S2-F approved; S2-E in principle, gate open; Q-E1 = A plus the AUD-1 follow-up); the C-1 reconciliation (§6.1); guard precedence (§4.1); step 1 split into 1a / 1b with a predicted divergence run and an overlap sensitivity mutant.
- **rev. 2:**
  - G6 analysis corrected: rev. 1 wrongly said `MemberHistory` feeds G6 (§6);
  - D-1 creation-path proof, plus a new fitness test R7 (§4.6);
  - full call-site inventory (§7);
  - fixture strategy (§8);
  - one consolidated narrative.

## 1. Objective and fixed statements

Enforce the ratified D-1 … D-5 decisions on the Admit / End decisions and commands, with database-free tests.

The following hold throughout this plan:
1. **Slice 2a is database-free.** It uses the in-memory test double and static scans.
2. **Slice 2b stays held with PostgreSQL.**
3. **Slice 2a grants admission authority only**, under the approved interim policy (D-4 A1).
4. **Ending authority fails closed** until Governance decides G-1.
5. **`decisionDate` is required** for every new Admit and End domain decision.
6. **No new HTTP / UI and no live ledger authority.**
7. **The story and plan authorise no implementation.**

## 2. Repository facts (inspected 2026-10-11)

| # | Fact | Source |
|---|---|---|
| F1 | `AdmitMember`: `?string $declarationRef`, `int $effectiveAt`, `?int $decidedAt`; the handler always builds `Provenance::decision($actor, $evidence, $declarationRef)` | `Application/Organisational/AdmitMember.php`; `AdmitMemberHandler.php:49-55` |
| F2 | `EndMembership`: `string $endKind`; the handler calls `EndKind::fromString` and `Provenance::decision($actor, $evidence)` | `EndMembership.php`; `EndMembershipHandler.php:115-121` |
| F3 | `Provenance` kinds: `decision(actor, evidence?, declarationRef?)`, `rule(ruleRef, ruleVersion)`, `attested(source, importer)`. In `app/`, `rule` / `attested` are produced only by the persistence decoder | `Provenance.php:32-44`; `ProvenanceJson.php:57-58` |
| F4 | `EndKind`: an open non-empty string; "Resolution never reads it" | `EndKind.php` |
| F5 | `decideAdmit(FactId, TimePoint effectiveAt, ?TimePoint until, Provenance, ?Instant decidedAt = null)`; guards: duplicate fact id, invalid initial term, then G\* | `Member.php:85-95` |
| F6 | `new Admit` / `new End` in `app/`: only `Member.php:94, 113` (new decisions) and `PostgresMemberRepository.php:259, 270` (rehydration) | grep |
| F7 | `Decision::accepted(Fact, int)` is **public**; its only caller is `Member.php:165` (`Member::accept`) | `Decision.php:20`; grep `app/`, `tests/` |
| F8 | No container binding of any Membership port; the handlers have no caller outside tests | grep `app/Providers`, `bootstrap`, `config`, `routes` |
| F9 | Legacy policy: `approveApplication` = owner / admin (explicit admission authority); `manageMembership` = owner / admin ("membership settings"); **no explicit "end membership" ability**. Moreover **no legacy ending path is reachable**: `Member::endMembership()` has no caller, and `TerminateMembershipHandler` is container-bound but has no HTTP / console caller and checks no authority. There is therefore **no ending policy for A1 to mirror** (G-1 is a new Governance decision, not a mapping) | `app/Policies/MembershipPolicy.php:33-41, 94-100`; `app/Models/Member.php:172`; `Application/Membership/TerminateMembership/TerminateMembershipHandler.php`; `Infrastructure/Providers/MembershipServiceProvider.php:215`; grep `app/`, `routes/` |
| F10 | The G6 golden test hashes the output of `G6CorrectionOracle::enumerate`, a test-only port that "deliberately shares NO code with production" | `G6CorrectionOracleGoldenTest.php:21-45`; `Support/G6CorrectionOracle.php:12` |
| F11 | The C-1 exhaustive test drives the kernel through `MemberHistory` (admit / end provenance `Provenance::decision('registrar-1')`, no declaration reference) and asserts **0 mismatches** and **15 530 decisions** | `C1ExhaustiveDifferentialTest.php:28-65`; `Support/MemberHistory.php:42-56` |
| F12 | `ReferencePropertyRun` builds every `AdmitMember` with `declarationRef = null`; effective dates `mt_rand(0, 20)`; the test asserts no failures and `appended > 0` (no fixed counts). Also used by the held `PostgresReferenceModelPropertyTest` | `Support/ReferencePropertyRun.php:55-80`; `ReferenceModelPropertyTest.php:22-30` |
| F13 | `DualHistory` admits with `Provenance::rule('verification','v1')` and shifts times by `$shift`; the metamorphic test uses shifts 1, 7, −40, 1000 with `MAX_V = 60` | `Support/DualHistory.php:57-71`; `PropertyAndMetamorphicTest.php:26, 104` |
| F14 | Idempotency: IDEM-1 (key before decide, re-checked after a domain refusal), IDEM-2, IDEM-3, **IDEM-4 (only appended outcomes are remembered)** | Slice 1a contract §4 |
| F15 | Architecture tests scan sources statically, without booting Laravel | `tests/Architecture/MembershipSlice1aBoundaryTest.php:25-28` |
| F16 | Nothing outside `app/Contexts/Membership/` references the new ledger, except the migration `database/migrations/2026_10_09_100000_create_org_membership_tables.php` | grep |
| F17 | Legacy `MemberStatus` types exist; D-2 = R1 keeps them out of the domain | `Domain/Member/MemberStatus.php`, `Domain/ValueObjects/MemberStatus.php` |

## 3. Scope [E, decision S2-A]

| | **Slice 2a** (this plan) | **Slice 2b** (held) |
|---|---|---|
| Content | R1 D-1 guard · R2 closed end kinds · R3 required decision date · R4 interim authority for **Admit** (End denied) · R5 Election-Only isolation test · R6 idempotency unchanged · R7 creation-path fitness test | container bindings of `MemberRepositoryPort` → `PostgresMemberRepository` and `AuthorityCheckPort` → the interim adapter; the Eloquent `OrganisationRoleReader`; End authority after G-1 |
| Database | none | needs the PostgreSQL hold lifted: binding makes an unverified adapter executable, and its test needs a booted Laravel app whose test base runs `migrate:fresh` |
| Gate | review + Human PO / ARB approval of S2-A … S2-F + authorisation | PostgreSQL resumed and its verification accepted; G-1 decided; separate authorisation |
| Acceptance | AC-1 … AC-10 (§10) | AC-11 … AC-13 (§10) |

The decision pack's Slice 2 line (§254) lists the PostgreSQL binding inside Slice 2. S2-A moves it into 2b.

## 4. Design

### 4.1 D-1: admission basis

| Aspect | Statement |
|---|---|
| Invariant **[R]** | every new Admit carries exactly one recognised basis: declaration reference, statutory-rule reference, or attested historical evidence (migration only) |
| "Exactly one" **[F]** | structural: a `Provenance` has exactly one kind (F3). The uncovered case is `decision` without `declarationRef` |
| Domain rule **[E] (S2-E)** | `decideAdmit` refuses `decision` provenance with a null or blank `declarationRef` → `AdmissionBasisMissing`. `rule` and `attested` are valid domain bases |
| Command capability **[E]** | `AdmitMemberHandler` builds only `decision` provenance (F1), so **through the ordinary command only declaration-based admission is possible**. It offers no statutory or ex-officio admission (pending **LQ-1 [Q]**) and no attested admission, which is reserved for the Slice 13 migration path and is **not** an ordinary admission route |
| Rehydration **[F]** | `Member::fromLog` and the decoder (`PostgresMemberRepository.php:259`) rebuild existing facts without calling `decideAdmit`, so historical facts are never re-validated |
| Alternative | a command-only guard (no kernel change) would leave the invariant unenforced on any future admission path. Not recommended |
| Guard precedence **[E]** (rev. 3) | **Admit:** `DuplicateFactId` → `InvalidInitialTerm` → **`AdmissionBasisMissing`** → **`EffectiveDateAfterDecisionDate`** → G\* (overlap, contiguity, coverage). **End:** `DuplicateFactId` → `PeriodNotFound` → `PeriodAlreadyEnded` → `EndNotAfterPeriodStart` → **`LegacyEndRequiresAttestedHistory`** → **`EffectiveDateAfterDecisionDate`** → accept. That is, the existing structural guards first (their reported reasons stay unchanged), then the new input-validity guards, then the lifetime invariants. The order only decides *which* reason is reported for an input that violates several rules; it is pinned by precedence tests in steps 1, 3 and 5 |

### 4.2 D-3: ending acts

- **[R]:** the closed set is `resignation`, `exclusion`, `death`, with `legacy` only for attested migration history. Non-payment is a ground for exclusion; term expiry is derived; dissolution is out of scope.
- **[E] boundary (S2-B):**
  - `EndKind` gets the four values as constants. **`EndKind::fromString` throws for any other value**, so no open string can exist in the domain.
  - `EndKind::tryFromString(string): ?self` returns `null` for an unknown value.
  - `EndMembershipHandler`'s decide closure returns `Decision::refused(RefusalReason::EndKindNotRecognised)` on `null`. The refusal then follows the standard path: IDEM-1's re-check, then IDEM-4.
  - `decideEnd` refuses `legacy` with non-`attested` provenance → `LegacyEndRequiresAttestedHistory`.
- **[A] rehydration:** the decoder uses `EndKind::fromString`, so a stored unknown kind would throw on load. Only test databases could hold one. This is held PostgreSQL code; noted for 2b.
- **[Q]:** the D-3 legal procedures are not modelled; the command records the decision only.

### 4.3 D-5: decision date

- **[R] Z1:** explicit calendar dates from the caller. "Today" is only a UI default computed in `organisations.timezone`, outside the domain.
- **[E] (S2-C):** `decideAdmit(FactId, TimePoint $effectiveAt, TimePoint $decisionDate, ?TimePoint $initialTermUntil, Provenance, ?Instant $decidedAt = null)` and `decideEnd(FactId, PeriodId, TimePoint $effectiveAt, TimePoint $decisionDate, EndKind, Provenance, ?Instant $decidedAt = null)`. Guard: `effectiveAt ≤ decisionDate`, else `EffectiveDateAfterDecisionDate`. Both commands gain a required `int $decisionDate`.
- **Unchanged [F]:**
  - backdating is accepted within the existing invariants;
  - backdating into a live lifetime is refused (`OverlappingMembershipPeriods`);
  - conflicting re-admission is refused.
- **Not persisted, not cross-checked [E]:** `decisionDate` is a decision-time check, with no schema change (Q-E1 asks whether to persist it). It isn't compared with `decidedAt`, which would need a time zone.

### 4.4 D-4 / G-1: interim authority

- **[R] A1:** interim, replaceable, recording `AuthorityEvidence`: actor, role as `capacity`, policy source as `ruleRef`, `policyVersion`, result, `checkedAt`, `competenceVerified`.
- **Admit [E]:** owner / admin, mirroring `approveApplication` (F9). `ruleRef = interim:legacy-MembershipPolicy:approveApplication`, `policyVersion = interim-1`, `competenceVerified = false`, `checkedAt` from an injected clock.
- **End [Q]:** **G-1 is unresolved.** The adapter denies every End, with `ruleRef = interim:unresolved:G-1`. No mapping to `manageMembership` is implemented or documented as policy.
- **Structure:** `Infrastructure/Organisational/InterimRoleAuthority implements AuthorityCheckPort`, depending on `OrganisationRoleReader`. In 2a that's an in-memory reader in tests only; the Eloquent reader comes in 2b.
- **[Q] DS-4:** the replacement criterion.

### 4.5 Election-Only boundary [E]

| Item | Specification |
|---|---|
| Mechanism | `tests/Architecture/MembershipLedgerIsolationTest.php`, a static scan following F15 conventions, with no Laravel boot and no database |
| Scanner | a pure function `violations(array<string,string> $sources, list<string> $allowlist): list<string>` in `tests/Architecture/Support/` |
| Scanned | `app/`, `routes/`, `config/`, `database/`, excluding `app/Contexts/Membership/` |
| Forbidden | `App\Contexts\Membership\Domain\Organisational`, `App\Contexts\Membership\Application\Organisational`, `App\Contexts\Membership\Infrastructure\Organisational`, `org_memberships`, `org_membership_facts`, `org_membership_commands` |
| Allowlist | exactly `database/migrations/2026_10_09_100000_create_org_membership_tables.php`: it creates those tables (F16) |
| Detection proof | fixture sources: a planted `use App\Contexts\Membership\Domain\Organisational\Member;` in `app/Domain/Election/X.php` must be reported; a table name in the allowlisted migration must not |
| Real-tree check | the scanner over the files on disk |

### 4.6 R7: creation-path fitness test [E, decision S2-F]

`Decision::accepted` is public (F7). Any code could build an `Admit` / `End` and an accepted `Decision` and append it, bypassing **every** `decide*` guard, including the existing G\* invariants. Nothing reaches this today. Closing it doesn't need a kernel change: a static architecture test can assert that, in `app/`:
1. `Decision::accepted(` appears only in `app/Contexts/Membership/Domain/Organisational/Member.php`;
2. `new Admit(` / `new End(` appear only in `Member.php` and in the rehydration decoder `Infrastructure/Organisational/PostgresMemberRepository.php`.

Its detection proof works the same way as §4.5.

### 4.7 R8: provenance-origin rule (rev. 4) [E, decision S2-G]

**Finding [F]:**
- `Member::decideAdmit` and `Provenance::{decision, rule, attested}` are all **public** (`Member.php:85`; `Provenance.php:32-42`).
- In `app/`, `rule` / `attested` provenance is constructed **only** by the persistence decoder (`ProvenanceJson.php:57-58`, reached only from `PostgresMemberRepository.php:255`, i.e. rehydration). The only `decideAdmit` caller is `AdmitMemberHandler` (decision provenance).
- So **no reachable path creates an attested or statutory admission today, but nothing structural forbids one.** Any future application code could call `decideAdmit(…, Provenance::attested(…))` and record a "migration" admission outside the migration slice, or a statutory admission before LQ-1.
- The S2-E domain guard (D-1) does not catch this, because `rule` and `attested` are recognised bases. R7 does not catch it either, because it governs `Decision::accepted` and `new Admit/End`, not provenance origin.

**Options:**

| Option | Mechanism | Assessment |
|---|---|---|
| (a) **recommended** | extend the static fitness test: in `app/`, `Provenance::attested(` and `Provenance::rule(` may appear **only** in `Infrastructure/Organisational/ProvenanceJson.php` (rehydration). The planted-violation self-test proves detection. A future migration slice (Slice 13) or a statutory-admission capability (after LQ-1) must change the allowlist explicitly in its own authorised, reviewed change | no kernel change; matches the R5 / R7 approach; makes the origin restriction a reviewed architectural constraint rather than a convention |
| (b) | a domain capability: `decideAdmit` refuses `attested` / `rule` unless a migration / statutory capability object is passed | changes the kernel API beyond the ratified model; would also need to distinguish rehydration. Larger than necessary now |
| (c) | the domain refuses `attested` / `rule` admissions altogether in Slice 2a | contradicts ratified D-1 (all three are recognised bases); breaks `AnnulVoidTest:61` (non-decision targets); blocks Slice 13 |

**Limitation of (a), stated explicitly:** it constrains source code in `app/`, not runtime values. A future allowlisted path is trusted to the reviewed change that adds it.

**Test coverage today [F]:**
- `AdmissionTest:203-204` only checks the provenance kinds; `AnnulVoidTest:61` admits with `attested` / `rule` in a test.
- **No test asserts the origin restriction:** it is untested until R8.

## 5. Refusal contract changes (all additive)

| Identifier | Invariant | Produced by | Contract | Tests | Compatibility |
|---|---|---|---|---|---|
| `AdmissionBasisMissing` (`admission_basis_missing`) | D-1 | domain `decideAdmit` | Slice 0 (guard), Slice 1a §5 | steps 1a, 1b, 2 | admission fixtures need a reference (§8, §6.1) |
| `EndKindNotRecognised` (`end_kind_not_recognised`) | D-3 | application, End decide closure | Slice 0 (`EndKind` closed), Slice 1a §5 | steps 3, 4 | `DualHistory` `'k'` → `'resignation'` |
| `LegacyEndRequiresAttestedHistory` (`legacy_end_requires_attested_history`) | D-3 | domain `decideEnd` | Slice 0, Slice 1a §5 | step 3 | no caller uses `legacy` |
| `EffectiveDateAfterDecisionDate` (`effective_date_after_decision_date`) | D-5(b) | domain `decideAdmit` / `decideEnd` | Slice 0 (required parameter), Slice 1a §2 / §5 | step 5 | all call sites (§7) |

Unchanged: `AppendOutcome`, `ApplicationRefusal`, the port, `TimePoint`, `StorableValidTime`, legacy `MemberStatus`.

**Retry and idempotency [F] (verified, not extended):**
- each new refusal: nothing appended, **not remembered** (IDEM-4), so a corrected retry under the same key may append;
- an identical retry → the original `Appended` (IDEM-1 / IDEM-3);
- same key, different command → `IdempotencyKeyConflict` (IDEM-2);
- same `FactId` under a new key → `DuplicateFactId`;
- conflicting re-admission → `OverlappingMembershipPeriods`.

## 6. G6 and C-1 regression semantics (answers with sources)

| # | Question | Answer |
|---|---|---|
| 1 | What bytes form the G6 hash? | For each `[depth, line, refusal]` from `G6CorrectionOracle::enumerate(H=5, L=5)`: `line . "\n"` (`G6CorrectionOracleGoldenTest.php:26-27`). `line` = `<facts>|<verdict>|<resolution>` (`G6CorrectionOracle.php:115-131`), e.g. `A0@0|ok|0:0,1,2,3,4`: fact kinds, ids and times only. **No provenance, no production code** (F10) |
| 2 | Does adding `declarationRef` alter those bytes? | **No.** The oracle never sees provenance or `MemberHistory`. **Correction of rev. 1**, which claimed `MemberHistory` feeds G6 |
| 3 | What does G6 assert? | **Byte-for-byte identity** of the PHP port's stream with the Python golden stream: transcription fidelity of the port only ("evidence, not the contract", lines 13-15). It asserts nothing about production. **Semantic equivalence** of kernel and oracle is the C-1 exhaustive test |
| 4 | Can the fixture updates preserve the invariants without weakening D-1? | **Yes.** G6 is unaffected by construction. The C-1 equivalence holds if the `MemberHistory` admissions carry a declaration reference and all decisions carry a decision date at or after every effective date: neither new refusal can then fire, so the decision sequence is unchanged. D-1 isn't weakened, because the fixtures **satisfy** it and its refusal has its own tests (steps 1, 2) |
| 5 | Which counts and invariants must stay unchanged? | C-1 exhaustive: **0 mismatches**, **15 530 decisions** (`C1ExhaustiveDifferentialTest.php:63-64`). G6: the stream hash `fa62f0d5…`, the per-depth counts, the listing. Reference model property: **0 failures**, `appended > 0`. Metamorphic: translation by 1, 7, −40 and 1000 equal; renaming equal. Exhaustive bounded: `check()` empty. C-1 property tests: green |
| 6 | What if a hash or count changes? | **G6:** it can only change if the oracle port or its golden fixture changes, and Slice 2a touches neither. A change means an unintended edit: **stop**. **C-1:** a mismatch or a count other than 15 530 means a new refusal fired in a fixture (a fixture defect) or a genuine semantic conflict: **stop and report**; never update the expected values to fit |

### 6.1 C-1 reconciliation (rev. 3, for the Human ARB's S2-E gate)

1. **Affected scenarios [F].**
   - The oracle offers H = 5 admission candidates in every state (`G6CorrectionOracle.php:69-71`).
   - Derived from the golden per-depth counts (`Fixtures/g6_c1_golden.json`), **4 130 of the 15 530 decisions (26.6 %) are admissions**: 5, 25, 100, 625 and 3 375 at depths 1 … 5. All 5 depth-1 decisions are admissions.
   - `MemberHistory::admit` uses `Provenance::decision('registrar-1')` with no declaration reference (`MemberHistory.php:42-51`). So with the D-1 guard and unchanged fixtures, the kernel would refuse all 4 130.
   - End, Annul and Void decisions (11 400) are unaffected by D-1.
2. **What changes [E].** **Fixtures only.** `MemberHistory` gains an `admissionProvenance()` carrying a declaration reference (§8). The **independent reference model (the G6 oracle) does not change**: it models resolution, the lifetime invariants and the C-1 guards over *valid inputs* and has no provenance concept (F10). D-1 is a pure input-validity rule that depends only on the provenance argument, not on the history state, so it is orthogonal to everything the oracle checks.
3. **Detection is not by construction [E].**
   - Only the D-1 dimension is held at a valid value. Every state-dependent outcome (accept / refuse, refusal reason, lifetimes) is still computed independently by the kernel and the oracle and compared on all 15 530 decisions.
   - D-1 itself is tested separately, positive and negative (steps 1–2).
   - **Step 1 is split to prove it** (§9): implementing the guard **before** the fixture update must make C-1 fail in a precisely predicted way:
     - 5 mismatches, all depth-1 admissions reported `admission_basis_missing` by the kernel;
     - an empty frontier, so **5 decisions explored instead of 15 530**.

     That run, recorded, shows the comparison detects a kernel divergence that the oracle does not share.
   - After the fixture update, a **temporary sensitivity mutant** in the lifetime invariants: the INV-9 overlap check (`InvariantSet.php:30-38`) disabled **in the existing sensitivity kit's disposable scratch copy** (`models/c1_sensitivity/run.php:8-10`; scratch directory set to the session scratchpad through `C1_SENSITIVITY_SCRATCH`), so the working tree is never touched. **Kit limitation (PBDIGIT-269):** the kit reports `KILLED` for any non-zero exit, so the C-1 *assertion* failure must be confirmed by reading the output. It must again produce mismatches. The predicted first mismatches are admissions the oracle refuses as `overlapping_membership_periods` (2 499 such refusals in the golden counts) but the kernel would accept.
4. **Counts.**
   - **Unchanged, justified:**
     - C-1: 0 mismatches and 15 530 decisions. The candidate enumeration and the frontier depend only on accept / refuse outcomes, which are identical once the fixtures satisfy D-1 and D-5.
     - The G6 hash, per-depth counts and listing (§6, rows 1–3).
     - The property run: 0 failures. The model has no provenance, and the fixtures add no random draws.
     - The metamorphic and exhaustive-bounded results: `DualHistory` uses `rule` provenance, a valid basis, and a shift-translated decision date.
   - **Legitimately changing:**
     - `Admit` provenance in the fixture histories (now carrying a reference);
     - command fingerprints in `ReferencePropertyRun` (they include `declarationRef`; both model and kernel consume the same fingerprint, so outcomes are unaffected);
     - the new negative tests.
   - **Not changing, and must not:** `AnnulVoidTest`'s attested / rule admissions (line 61), which must stay accepted, since R-2 needs non-decision targets.
5. **G6 boundary [F].** Confirmed in §6, rows 1–3:
   - the hash covers only `G6CorrectionOracle::enumerate` lines (`G6CorrectionOracleGoldenTest.php:21-45`);
   - the oracle shares no production code (`G6CorrectionOracle.php:12`);
   - Slice 2a changes neither the oracle, its golden fixture, nor the Python generator.

   **No input or output of the proposed work enters that hash.**

Classification:
- **semantic invariants:** C-1 equivalence, the property and metamorphic tests;
- **serialization invariant:** the G6 stream bytes;
- **golden-oracle expectation:** the G6 fixture;
- **fixture-only changes:** the provenance references and decision dates in §8.

## 7. Call-site inventory (decision-date and declaration changes)

| File | `decideAdmit` | `decideEnd` | `new AdmitMember` | `new EndMembership` | Status |
|---|---|---|---|---|---|
| `app/…/AdmitMemberHandler.php` | 1 | — | — | — | production |
| `app/…/EndMembershipHandler.php` | — | 1 | — | — | production |
| `tests/…/Domain/…/Support/MemberHistory.php` | 1 | 1 | — | — | fixture |
| `tests/…/Domain/…/Support/DualHistory.php` | 1 | 1 | — | — | fixture |
| `tests/…/Domain/…/AdmissionTest.php` | 3 | — | — | — | unit |
| `tests/…/Domain/…/AnnulVoidTest.php` | 4 | — | — | — | unit |
| `tests/…/Application/…/MemberRepositoryContract.php` | 2 | 5 | — | — | contract (shared with the PostgreSQL class) |
| `tests/…/Application/…/InMemoryMemberRepositoryTest.php` | 1 | — | — | — | unit |
| `tests/…/Application/…/AppendOutcomeMappingTest.php` | 1 | — | — | — | unit |
| `tests/…/Application/…/MembershipCommandHandlersTest.php` | — | — | 2 | 1 | unit |
| `tests/…/Application/…/ConcurrencyAndRaceTest.php` | — | — | 1 | 1 | unit |
| `tests/…/Application/…/Support/ReferencePropertyRun.php` | — | — | 1 | 1 | fixture (also used by the held PostgreSQL property test) |
| `tests/Feature/…/PostgresMemberRepositoryInvariantsTest.php` | 3 | 1 | — | — | **held: edit only, not executed** |
| `tests/Feature/…/PostgresConcurrencyRaceTest.php` | — | — | 3 | 2 | **held: edit only, not executed** |

Totals: `decideAdmit` 1 + 16, `decideEnd` 1 + 8, `new AdmitMember` 7, `new EndMembership` 5, all in test code apart from the two handlers. The held files are updated only so their signatures match; they run in Part 2.

## 8. Fixture strategy (fixture-only; does not weaken D-1 or D-5)

- **`MemberHistory`:**
  - a separate `admissionProvenance()` = `Provenance::decision('registrar-1', null, 'decl-fixture')` for `admit()` only. End, Annul and Void provenance stay unchanged.
  - `decisionDate = TimePoint::of(1_000_000)`, a constant above every time in H = 5.
- **`DualHistory`:** keeps `Provenance::rule` (a valid D-1 basis); `decisionDate = TimePoint::of($this->at(1_000_000))`. **Translated by the shift**, so the metamorphic relation stays exact.
- **`ReferencePropertyRun`:** `declarationRef = 'decl-property'` and `decisionDate = 1_000_000` as constants. **No additional `mt_rand` draw**, so the random sequence and every generated command are unchanged.
- **Unit and contract helpers:** a declaration reference and a decision date at or after the effective date, unless the test targets the new refusal.

## 9. TDD sequence (Slice 2a; runs only after authorisation)

| Step | Requirement | Test (location) | Expected first result | Smallest change | Regression / boundary | Gate |
|---|---|---|---|---|---|---|
| 0 | baseline | Domain, Application, architecture; record the G6 hash, C-1 counts, property results | exit 0 | — | — | authorisation |
| 1a | D-1 | `AdmissionBasisTest` (Domain): decision without a reference refused; with a reference, `rule`, `attested` accepted; precedence (`DuplicateFactId` before `AdmissionBasisMissing`) | refusal case **fails (assertion)** | guard + refusal case, **fixtures unchanged** | **predicted and recorded:** C-1 fails with exactly 5 mismatches (depth-1 admissions, `admission_basis_missing`) and 5 decisions explored; the reference-model property run fails at its first admission. This is the evidence that both comparisons detect the divergence (§6.1) | S2-E |
| 1b | D-1 | — (fixture update per §8) | — | fixtures only; no production change | **C-1 0 / 15 530**, G6 unchanged, property, metamorphic and exhaustive-bounded green; then the **overlap sensitivity mutant** (scratch copy, byte-identical revert) must make C-1 fail again | S2-E |
| 2 | D-1 | handler test: `AdmitMember` without a reference → `admission_basis_missing`, nothing appended | **fails** | handler test helper default reference | Application | — |
| 3 | D-3 | `EndKindTest` (Domain): the 4 kinds construct; `expiry` / `non_payment` / `k` throw; `tryFromString` → null; `legacy` + decision refused, + attested accepted | **fails** | closed set, `tryFromString`, guard; `DualHistory` `'k'` → `'resignation'` | Domain | S2-B |
| 4 | D-3 | handler: `non_payment` → `end_kind_not_recognised`; `legacy` → `legacy_end_requires_attested_history` | **fails** | decide-closure mapping | Application | — |
| 5 | D-5(b) | `FutureDatingTest` (Domain) + handler tests: `effectiveAt = decisionDate + 1` refused (Admit, End); equal accepted; backdated accepted; backdated into a live lifetime → `OverlappingMembershipPeriods` | **fails** | required parameter + command field + guard; all §7 sites (held files edited, not run) | Domain, Application; C-1 / G6 / property again | S2-C |
| 6 | D-4 (Admit) | `InterimRoleAuthorityTest` (Infrastructure, in-memory reader): owner / admin granted for Admit; member / commission / none denied; evidence fields; **End always denied with `interim:unresolved:G-1`** | **fails** (class absent) | adapter + reader interface | Application; architecture dependency direction | — |
| 7 | boundary | `MembershipLedgerIsolationTest`: planted violation detected; allowlisted migration ignored; real tree clean | detection **fails** (scanner absent). The real-tree check passes at once (characterisation, F16); the planted case proves its value | scanner | architecture | — |
| 8 | creation path | `MembershipDecisionCreationTest`: planted `Decision::accepted(` outside `Member.php` detected; real tree clean | detection **fails** (scanner absent); real tree passes at once (characterisation, F6 / F7) | scanner (shared helper) | architecture | S2-F |
| 8b | provenance origin | same test class: planted `Provenance::attested(` / `Provenance::rule(` outside `ProvenanceJson.php` detected; real tree clean | detection **fails** (rule absent); real tree passes at once (characterisation, §4.7) | allowlist rule in the shared scanner | architecture | **S2-G** |
| 9 | IDEM-4 | handler tests: each new refusal, corrected and retried under the same key, appends | **passes at once** (characterisation of F14), stated as such | — | Application, `AppendOutcomeMappingTest` | — |
| 10 | all | full regression; Slice 0 / 1a amendments (§5); Slice 2 contract note D-5(a); developer guide 08; evidence and checksums | — | — | exit 0 | S2-D |

Refactoring after GREEN: test helpers and fixtures only.

## 10. Acceptance criteria

**Slice 2a:**
- AC-1: D-1 refusal; declaration, `rule` and `attested` accepted in the domain; only declaration-based admission through the command.
- AC-2: no open end kind in the domain; unknown kinds refused at the command.
- AC-3: `legacy` only with attested provenance.
- AC-4: future-dated Admit / End refused; backdating within invariants accepted; `decisionDate` required at every new-decision call (§7).
- AC-5: Admit authority as mirrored; End denied pending G-1; evidence recorded.
- AC-6: ledger isolation enforced, detection proven.
- AC-7: the creation-path rule enforced, detection proven; and (if S2-G is approved) `rule` / `attested` provenance constructible in `app/` only by the rehydration decoder, detection proven.
- AC-8: IDEM-1 … IDEM-4 unchanged.
- AC-9: C-1 0 mismatches / 15 530 decisions; G6 hash, counts and listing unchanged; property and metamorphic green; Domain, Application and architecture suites green.
- AC-10: additive contract amendments; developer guide; no production change outside §4–§5; no database used; held files edited only.

**Slice 2b (held):**
- AC-11: bindings resolve.
- AC-12: Eloquent role reader verified.
- AC-13: End authority per G-1.

All on PostgreSQL / booted Laravel after the hold is lifted.

## 11. Traceability (authoritative)

| Decision | Requirement | Invariant / mechanism | Refusal | Steps | AC | Open |
|---|---|---|---|---|---|---|
| D-1 C1 [R] | R1 | exactly one basis; command = declaration only | `AdmissionBasisMissing` | 1a, 1b, 2 | AC-1, AC-9 | LQ-1 [Q]; S2-E gate (§6.1) |
| D-2 R1 [R] | — | no pending Member (unchanged; nothing in the kernel models one) | — | regression | AC-9 | — |
| D-3 E1 [R] | R2 | closed acts; `legacy` ⇔ attested | `EndKindNotRecognised`, `LegacyEndRequiresAttestedHistory` | 3, 4 | AC-2, AC-3 | D-3-L [Q]; S2-B |
| D-4 A1 [R] | R4 | interim Admit authority; End fail-closed | `NotAuthorised` (existing) | 6 | AC-5, AC-13 | G-1, DS-4 [Q] |
| D-5(a) Z1 [R] | R3 | explicit dates; no clock in the domain | — | 5 | AC-4 | Q-E1 |
| D-5(b) [R] | R3 | `effectiveAt ≤ decisionDate`; backdating within invariants | `EffectiveDateAfterDecisionDate` | 5 | AC-4 | S2-C |
| D-5(c) [R] | — | technical range already enforced (R-1a-6) | — | — | — | — |
| Election-Only | R5 | no ledger reference outside Membership | — | 7 | AC-6 | — |
| (kernel integrity) | R7 | accepted decisions only from `Member` | — | 8 | AC-7 | S2-F |
| IDEM-1 … 4 | R6 | unchanged | — | 9 | AC-8 | — |

## 12. Unresolved dependencies and decisions

| ID | Question | Owner | Blocks |
|---|---|---|---|
| S2-A | the 2a / 2b split | Human PO / ARB | **APPROVED 2026-10-11** |
| S2-B | closed `EndKind` + `tryFromString` + decide-closure refusal | Human PO / ARB | **APPROVED 2026-10-11** |
| S2-C | required `decisionDate` at the domain decision boundary; no hidden clock; UI default under the organisation-time-zone rule | Human PO / ARB | **APPROVED 2026-10-11** |
| S2-D | four additive refusals and contract amendments | Human PO / ARB | **APPROVED 2026-10-11** |
| S2-E | the D-1 guard in the domain, protecting every admission path | Human PO / ARB | **CLOSED 2026-10-11** (C-1 / G6 reconciliation accepted at architecture level; the predicted outcomes remain implementation acceptance checks) |
| S2-F | the creation-path fitness test (R7), with a planted-violation self-test | Human PO / ARB | **APPROVED 2026-10-11** |
| S2-G | the provenance-origin rule R8 (§4.7, option a), extending the S2-F fitness test; a source-architecture guard, not a runtime security boundary | Human PO / ARB | **APPROVED 2026-10-11** |
| AUTH | implementation of Slice 2a | Human PO / ARB | **AUTHORISED 2026-10-11** (pasted decision, confirmed by the PO in session: "Confirmed, implement Slice 2a"); **no commit or push** until the evidence is reviewed |
| G-1 | which authority ends a membership | **Governance owner** | Slice 2b (2a denies End) |
| DS-4 | replacement criterion for the interim adapter | Governance owner | — |
| LQ-1 | admission bases, statutory cases, evidence, legacy members | **legal adviser** | statutory admission via the command |
| D-3-L | procedures and evidence for ending acts | **legal adviser** | procedure modelling (not in scope) |
| Q-E1 | persist `decisionDate` for audit? Persisting it would add a field to the ratified fact model ("every fact carries effectiveAt, decidedAt, recordedAt, seq, provenance", ratified §2), i.e. an architecture amendment, and a schema change (held). **DECIDED 2026-10-11: option A for Slice 2a** (validate, do not persist) | Human PO / ARB | — |
| AUD-1 | **follow-up for the Audit slice (Slice 12):** decide the evidence and historical-reproducibility requirements for decision dates (persisting `decisionDate`, requiring `decidedAt`, or keeping organisation time-zone history). The option-A deferral is **not** evidence that the audit requirement is met | Human PO / ARB, with the Audit slice | Audit slice |

## 13. Gates kept, exclusions, stop conditions

- **Gates kept:**
  - the new ledger stays non-authoritative: no binding (2b), no caller outside tests (F8), no HTTP / UI;
  - D-6 / R10 stays the gate for cutover and HTTP / UI, and those later slices inherit R5 and R7 as entry criteria.
- **Excluded:**
  - PostgreSQL Part 2, binding, provisioning, migrations, connections, verification;
  - F-2 / F-4 / F-5;
  - tenancy strategy or packages;
  - legacy `MemberStatus`;
  - statutory or attested admission through the command;
  - procedure workflows;
  - unrelated refactoring and backlog items.

  Editing the held PostgreSQL test files (§7) is **not** permission to run them.
- **Stop if:**
  - a test fails first for a reason other than the stated one;
  - the C-1 count or mismatches change, or the G6 hash changes;
  - a change is needed outside §4–§5 and §7–§8;
  - any test needs a database;
  - a contradiction with the ratified architecture appears.

## 14. Readiness

**Ready for independent review.** Every claim cites a source. The G6 / C-1 semantics are resolved without conflict (§6), the D-1 creation paths are proven, apart from the public `Decision::accepted`, which R7 closes (§4.6). No decision-date bypass exists once S2-C is applied (§4.3, §7).

**Not ready for implementation** until S2-A … S2-F are decided by the Human PO / ARB and Slice 2a is explicitly authorised. G-1 doesn't block 2a, since End fails closed, but it does block 2b.

> **Superseded 2026-10-11 (rev. 5):** S2-A … S2-G were decided and Slice 2a authorised and implemented (§12, §15). The statement "no decision-date bypass exists once S2-C is applied" was refuted by the independent review (H1: an infinite decision date was accepted at the domain API, unreachable through the commands); closed by the hardening below. The text above is kept as the rev. 4 record.

## 15. Progress

- 2026-10-11: **Slice 2a implemented, test-first, database-free; not committed** (evidence: `docs/publicdigit/membership/evidence/2026-10-11-slice2a/`).
  - Baseline at `cedef2dc4`: Domain 89, Application 55, DateOrdinal 5, architecture 13; C-1 + G6 5 786 assertions; exit 0.
  - Step 1a: `AdmissionBasisTest` failed first (2 assertion failures). With the guard and **unchanged** fixtures, C-1 failed with exactly **5 mismatches** (depth-1 admissions, `admission_basis_missing`), as predicted. **Not shown:** the predicted "5 decisions explored", because the test's count assertion is never reached once the first assertion fails. The property run failed at seed 1, step 0.
  - Step 1b: fixtures given a declaration reference. C-1 + G6 back to 5 786 assertions, 0 mismatches. **Overlap sensitivity check** in a disposable scratch copy: the control passed; with INV-9 disabled, C-1 failed **on its assertion**, with the oracle refusing `overlapping_membership_periods` and the kernel saying `ok`. Working tree unchanged; `InvariantSet.php` checksum unchanged.
  - Steps 2–9:
    - D-1 at the command;
    - closed `EndKind` (first failure: 8 errors for the missing `tryFromString`, plus 1 assertion failure);
    - unknown kind → refusal (first failure: the exception being replaced);
    - required decision date (first failure: 5 `TypeError`s);
    - the interim authority, with End always denied;
    - R5 / R7 / R8 fitness tests: planted violations detected exactly;
    - IDEM-4 characterisation.
  - Step 10: Domain 109, Application 60, Infrastructure 13, architecture 19 (six classes), C-1 + G6 5 786; all exit 0. Contracts amended (Slice 0, Slice 1a); developer guide 08.
  - **Deviations:**
    - steps 2 and 9 passed at once, because the domain guard already existed / the behaviour is a characterisation;
    - the first failures in steps 3, 5 and 6 were errors (a missing method, a missing parameter, a missing class), not assertion failures;
    - the overlap mutant was run with the kit's scratch-copy method by hand, not through `run.php`, to avoid editing the tracked kit and to capture the assertion text.
  - **Status:** implemented and tested locally; PostgreSQL unverified (held files edited only); not accepted.
- 2026-10-11: **Slice 2a hardening (review findings H1, H1-b, H2), test-first, database-free; not committed** (Human PO / ARB: H1 = A with the effective-date exception restored, H2 = php-parser, docs D1 … D5, implementation authorised; evidence `docs/publicdigit/membership/evidence/2026-10-11-slice2a-hardening/`).
  - Baseline at `bf605092c`: Domain 109, Application 60, Infrastructure 13, architecture 19, C-1 + G6 5 786; exit 0.
  - **H1:** 5 new `FutureDatingTest` cases (Admit / End × infinite decision / effective date; precedence over `DuplicateFactId`). RED: 5 assertion failures (none an error). GREEN: `Member::requireFiniteDates`, called first in `decideAdmit` / `decideEnd`; the `Admit` / `End` constructor checks stay. `TimePoint` unchanged. Command characterisation: `effectiveAt` / `decisionDate` are non-nullable `int` (passed at once, as predicted).
  - **H2:** `MembershipSourceScan::creationViolations` (nikic/php-parser 5.4.0 + `NameResolver`). RED against a literal-scan stub: 22 assertion failures (15 missed forms, 6 false positives, the unparsable source not rejected). GREEN: 23 forbidden forms detected, 7 permitted forms not reported, allowlist respected, parse failure throws. R5 unchanged.
  - **Stop and decision:** the real-tree scan stopped on `app/Http/Middleware/VoterSlugStep.php`, which is not valid PHP (`php -l` fails; the only such file of 1 754). Human PO / ARB: named exclusion `UNPARSABLE_KNOWN` plus backlog `PBDIGIT-272`; a guard test fails once the file parses.
  - Regression: Domain 114, Application 61, Infrastructure 13, architecture 34 + 4 + 3 + 5 + 1 + 2 (six classes), C-1 + G6 5 786 (oracle and golden fixture unchanged; stream hash asserted); all exit 0.
  - **Deviations:**
    - the old literal planted tests for R7 / R8 were replaced by the matrix (their unnamespaced sources referred to global classes, which a resolving scan correctly ignores; case N7);
    - php-parser 5.4.0 raises 3 PHP 8.5 deprecations inside the library (`SplObjectStorage::attach`), so the run reports "OK, but there were issues"; no upgrade is in scope.
  - **Status:** hardened locally; not committed; not accepted.
- 2026-10-11: **Committed**, on the Human PO's instruction in session ("commit all what you have written"): `834a4f65c` (code and tests), `14f89d18e` (documents and evidence), `a932b7ae8` (state). Not pushed. The "not committed" statements in the entries above are historical and stay as written.
  - **Architect review (sub-agent, read-only):** no blocking defect in Slice 2a. Follow-ups:
    - a CHECK constraint on `end_kind` and a decode-failure test (Slice 2b);
    - R5 moved onto the parser scan and narrowed;
    - the finite-date note added to the Slice 0 contract (done 2026-10-11).
  - **Correction:** `14f89d18e` also carried the `PBDIGIT-264` status sync, which `PBDIGIT-271` did not cause.
