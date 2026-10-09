# Membership Slice 0: TDD Implementation Review

**Date:** 2026-10-09 · **Story:** `PBDIGIT-258` · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Engineering supplies the evidence; acceptance is the Principal Reviewer's.
**Contract:** `2026-10-08-membership-slice0-semantic-design-contract.md`, with the Principal Reviewer decisions C-S0-1 and C-S0-2 (instruction `docs/publicdigit/membership/architecture/brainstorming/20261009_0730_implementation_01.md` §2).
**Commits:** `ea1a693f5` (contract, story, plan) → `187792b32` … `bc91da46a` (13 TDD commits). Branch `knowelegeos-modelling`; nothing pushed.

## 1. What was implemented

`app/Contexts/Membership/Domain/Organisational/`: 23 files, 1,385 lines, pure PHP.

| Area | Classes |
|---|---|
| aggregate | `Member` |
| facts | `Fact`, `Admit`, `End` |
| record | `RecordedFact` |
| time | `TimePoint`, `Instant`, `Interval`, `IntervalSet` |
| identities | `MemberId`, `FactId`, `PeriodId`, `PersonRef` (+ the shared `TenantId`) |
| evidence | `Provenance`, `ProvenanceKind`, `AuthorityEvidence`, `EndKind` |
| truth | `Resolution`, `ResolvedMember`, `ResolvedPeriod` |
| enforcement | `InvariantSet`, `Decision`, `RefusalReason` |

**Behaviour:**
- Admit / End decisions with guards;
- Resolution with the ratified stage order;
- predicates InLifetime, Ended, TermCovered, InForce, Lapsed, IsMember, CurrentPeriod, First / CurrentAdmission, continuous / cumulative membership (as intervals);
- KnownAt;
- G\* with INV-9, INV-EP and INV-COV;
- acceptance carries `basedOnVersion`.

## 2. What was intentionally not implemented

Everything in contract §5:
- **facts:** Renew, ChangeType, Suspend, Restore, Correct, Annul, Void;
- **mechanisms:** consequences / ConsequenceGenerator;
- **attributes:** MembershipNumber, MembershipType, Standing;
- **numbers:** numeric durations (EP-Q6);
- **everything outside the domain:** repositories, persistence, transactions, idempotency storage, events / outbox, projections, Laravel, HTTP, authorisation, Election / Finance / Governance, migration, UI.

Slice 1a was not started.

## 3. TDD sequence

| # | RED commit | GREEN commit | Behaviour |
|---|---|---|---|
| 1 | `187792b32` (12 failing) | `cf8e2710f` | interval algebra |
| 2 | `d2616f83e` (17 failing) | `2756a512a` | Admit, identity, terms A / B / C, temporal coordinates, provenance, Resolution, G\* (INV-9) |
| 3 | `644543be4` (4 failing; 5 already green on cycle-2 Resolution) | `b1edf4733` | End guards (C-S0-1), INV-2, INV-3 |
| 4 | `0d2689df4` (6 failing; 1 already green) | `7f2e15b27` | member-level predicates, KnownAt |
| 5 | `c8eeb0f99` (2 failing) | `73e637856` | INV-EP, INV-COV checks |
| — | `6c2f8bc2e` | — | architecture fitness test (green on first run; verifies existing properties) |
| — | `417b14406`, `bc91da46a` | — | verification layer: oracle, exhaustive, differential, property, metamorphic (green on first run; verification, not drivers) |

**Honest notes:**
- In cycle 2 I first wrote five Member-level predicates that no test yet demanded. I removed them before committing GREEN and reintroduced them in cycle 4 behind RED tests.
- In cycles 3 and 4 some new tests were green on first run, because earlier GREEN code already satisfied them. They are recorded as such, not claimed as RED.

## 4. RED tests (domain language)

- **Admission:** accepted; accepted at / after the previous end; refused when backdated into a live period or before an existing end (`OverlappingMembershipPeriods`); `InvalidInitialTerm`; `DuplicateFactId`.
- **End:** accepted; `PeriodNotFound`; `PeriodAlreadyEnded`; `EndNotAfterPeriodStart` for e < start and for e = start (C-S0-1).
- **Identity:** PeriodId = Admit FactId; re-entry keeps the MemberId with a new PeriodId; holder and organisation never change.
- **Terms:** A (finite), B (∞), C (none → Lapsed, not a member; C-S0-2).
- **Temporal:** effective / decided / recorded independence; backdated admission and End; KnownAt.
- **Invariants:** INV-2, INV-3 (examples); INV-9; INV-EP and INV-COV (on hand-built states).

## 5. GREEN implementation (notes)

- `Member::accept()` is G\*: candidate F·f → `Resolution::of` → `InvariantSet::violations` → `Decision`.
- The guards run before G\*.
- `Resolution` keeps the ratified stages as private steps. `live`, `active` and `liveCuts` are trivial in Slice 0, and `lifetime()` throws if cuts ever appear before Slice 7.

## 6. Refactoring

- The test-side commit helper (`Support/MemberHistory`) mirrors the Slice 1a append rule `seq = basedOnVersion + 1`.
- The fitness test was corrected to scan code without comments (a test defect: docblock prose matched "time (" and "decide()").
- The property generator was strengthened after measuring thin histories (mean 3 facts → 15).

## 7. Test results

| Suite | Result |
|---|---|
| new kernel unit tests (`tests/Unit/Contexts/Membership/Domain/Organisational/`) | **59 tests, 5,675 assertions, all green** |
| new architecture test | **5 tests, green** |
| repository Architecture suite | 167 tests: the same 4 failures as on the pre-Slice-0 baseline `ea1a693f5` (Election lifecycle / clock, ConstitutionalBoundary, C25 denial-reason hard-coding). **Pre-existing, unrelated** |
| existing `tests/Unit/Contexts/Membership` | 307 tests (248 before + 59 new); 57 problems vs 57 at the baseline. The only difference is inside `CommitteeSlugAvailabilityServiceTest`, which is **flaky on both checkouts** (4–6 errors per run, varying tests; database-dependent; does not reference the kernel). **Pre-existing, unrelated** |

The kernel tests run without the Laravel bootstrap: `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/`. No development database was touched. The repository suites used the configured test database.

## 8. Independent oracle results

`Support/PointwiseOracle` evaluates every predicate at one time point from the definitions. It uses no production class (no Interval, Resolution or value object; plain integers).

Comparison is on the Lemma 4 grid: values are doubled, so the odd ordinals lie strictly between breakpoints, plus points below and above. Result: **0 mismatches** in every exhaustive and property run.

## 9. Property-test results

300 seeded histories, 30 commands each (70% plausible, 30% random), mean 15.0 facts and 7.9 periods (max 27 facts, 14 periods):
- production = oracle;
- INV-2, INV-3, INV-9, INV-EP and INV-COV hold;
- Resolution is deterministic (50 seeds).

All pass. Every failure message would carry its seed.

## 10. Metamorphic-test results

| Relation | Result |
|---|---|
| time translation (k = 1, 7, −40, 1000; 60 seeds each) | pass |
| id renaming (60 seeds) | pass |
| KnownAt prefix stability (every prefix of 60 seeds) | pass |
| End monotonicity: an End shrinks only its own period and changes no other (120 seeds, every End) | pass |

## 11. Exhaustive bounded results

| Bounds | Reachable states | Admit attempts | Mismatches / violations | Theorem 3 mismatches |
|---|---:|---:|---:|---:|
| T=4, L=4 | 373 | 4,410 | 0 | 0 |
| T=5, L=5 | 3,418 | 25,950 | 0 | 0 |

**VERIFIED WITHIN BOUNDS**, not a proof for arbitrary histories.

**Sensitivity:** two deliberate production mutations were each detected and then reverted:
- a closed interval end: oracle mismatches;
- End = start allowed: count and Theorem 3 failures.

## 12. Differential results

The PHP enumeration reproduces the independent Python research model's counts exactly:
- T=4 / L=4: states 373, accepted 372, refused 5,790, Admit attempts 4,410;
- T=5 / L=5: 3,418 / 3,417 / 32,478 / 25,950.

This is a differential on decision outcomes over the whole bounded space, not a copy of the research code (no Python in the repository).

## 13. Architecture purity results

`MembershipSemanticKernelPurityTest`, 5 tests, green:
- **imports:** only the kernel itself + the shared `TenantId`;
- **no** Illuminate / Laravel / Eloquent / `App\Models` / `DB::` / Carbon / DateTime / HTTP;
- **no** clock or randomness calls;
- **no** Election / Finance / Governance / committee / entitlement / fee vocabulary;
- **Resolution** references no G\* type (Theorem 0), and InvariantSet neither resolves nor decides;
- **no** abstract classes, inheritance, generic DDD framework types or stored status fields.

**Sensitivity:** a planted `use Illuminate\…` and an `InvariantSet` call in `Resolution.php` produced 3 failures; the file was then restored.

## 14. Requirements traceability

| Requirement | Contract | Invariant / rule | Test | Code |
|---|---|---|---|---|
| admission opens a period | §17 | INV-9, Theorem 3 | AdmissionTest, Exhaustive | `Member::decideAdmit`, `InvariantSet` |
| re-entry = a new period, same Member | §16 | Theorem 1 | AdmissionTest | `PeriodId`, `Member` |
| termination | §18 | INV-2, C-S0-1 | EndTest, Exhaustive | `Member::decideEnd`, `ResolvedPeriod` |
| lapse ≠ termination | §13 | INV-3, C-S0-2 | EndTest, AdmissionTest | `ResolvedPeriod::isLapsed` |
| no overlapping periods | §14 | INV-9 | AdmissionTest, InvariantSetTest | `InvariantSet` |
| episode contiguity / coverage | §14 | INV-EP, INV-COV | InvariantSetTest | `InvariantSet` |
| holder / organisation constant | §14 | INV-1 (structural) | AdmissionTest | `Fact` has no holder |
| historical knowledge | §11 | INV-14 | MembershipPredicatesTest, Metamorphic | `Member::knownAt` |
| decision evidence | §17 | RA-7 | AdmissionTest | `Provenance`, `AuthorityEvidence` |
| purity / non-circularity | §2, §12 | Theorem 0 | fitness test | — |

## 15. Proof-status matrix

| Claim | Status |
|---|---|
| Resolution does not depend on G\* | PROVEN (contract Theorem 0) + REPOSITORY EVIDENCE (fitness test) |
| PeriodId is not a second identity | PROVEN (Theorem 1) + SCRIPT VERIFIED |
| INV-2, INV-3 | PROVEN (Theorem 2 / corollary) + VERIFIED WITHIN BOUNDS |
| Admit acceptance = Theorem 3 | PROVEN + VERIFIED WITHIN BOUNDS (30,360 Admit attempts) |
| production = oracle | VERIFIED WITHIN BOUNDS (exhaustive) + seeded property (300 histories) |
| differential with the research model | VERIFIED WITHIN BOUNDS (count equality) |
| metamorphic relations | VERIFIED (seeded) |
| INV-EP / INV-COV unreachable in Slice 0 | PROVEN (Lemma 3); the checks are SCRIPT VERIFIED on hand-built states |
| INV-M | not checkable in one aggregate → Slice 1a / 1b |
| stale-decision reuse impossible | IMPLEMENTATION CHOICE in place (`basedOnVersion`); enforcement is Slice 1a |

## 16. Counterexamples

**None in production semantics.** Three defects were found during the work, all in tests and none in the domain:
- the fitness-test false positive (comments scanned);
- thin random histories (generator strength);
- untested predicates written ahead of their tests (TDD discipline; removed and re-added).

## 17. Deviations from the contract

| # | Deviation / clarification | Class | Reason |
|---|---|---|---|
| D-1 | guard `DuplicateFactId` (not listed in the contract) | **C: requirement ambiguity → resolved conservatively** | Theorem 1 presupposes unique FactIds; without the guard, two periods could share an id |
| D-2 | INV-1 is enforced structurally (facts carry no holder), not as a runtime check in G\* (contract §15 lists it) | clarification | §14 already says "structural"; a runtime check could never fail |
| D-3 | Resolution totality on unreachable logs (latest End wins; End on an unknown period ignored) | implementation choice | the contract requires Resolution to be total; these logs cannot arise through `decide` |
| D-4 | a refusal returns the **first** violated rule | implementation choice | the contract asks only that it names the rule |

**None changes semantics.** D-1 is offered for the reviewer's confirmation.

## 18. Technical debt

- `Member::decideEnd` resolves the whole log for the guards, and again in G\*. Cost is O(n²) for small n. Optimise only with evidence.
- The fitness test uses regexes (house style; PHPUnit file scanning). A static-analysis rule could replace it later.
- `backlog/README.md` row for PBDIGIT-258 is pending (another session's uncommitted edits in that file).
- **Recommended backlog item (not created):** the flaky `CommitteeSlugAvailabilityServiceTest`.

## 19. Remaining gates

- **For Slice 1a:** the Principal Reviewer's approval of Slice 0 and of D-1.
- **For later slices:** unchanged (contract §24): BD-CONSENT / LQ-1, BD-REC0, BD-3 (Slice 2); BD-TYPE (4); BD-SUSP (5); BD-2 / BD-NU / EP-Q5 (6); BD-REV-ER (7); DS-4 (9); DS-2 / BD-GVR / DS-5 (10); DS-1 (11); BD-NORM / EP-Q3 / EP-Q4 (13); EP-Q6 (1b, 2).

## 20. Recommendation for Slice 1a

Proceed **after approval**, per contract §22:
- the RepositoryPort contract with an in-memory implementation;
- `append` that rejects `basedOnVersion ≠ current version`;
- the conflict path reload → re-authorise → re-guard → G\* → append, bounded;
- idempotency;
- the creation race (INV-M);
- `recordedAt` monotone in seq.

The kernel already exposes everything 1a needs: `Member::fromLog`, `version()`, `Decision::basedOnVersion()`, `RecordedFact`.

## Final status classification

**B: GREEN WITH CONDITIONS.** Every implementation and verification obligation passes, with no failing test in the slice. Two named conditions remain:

1. **D-1:** the Principal Reviewer confirms the `DuplicateFactId` guard. It is a refusal the contract did not list, derived from Theorem 1's premise.
2. **Administrative:** the `backlog/README.md` index row for PBDIGIT-258, which is blocked by another session's uncommitted edits in that file.

**Why not A:** D-1 adds a refusal that is not in the contract, so it must not be accepted silently. The pre-existing failures elsewhere are evidenced as unrelated and are not conditions of this slice.

**STOP.** Slice 1a is not started.
