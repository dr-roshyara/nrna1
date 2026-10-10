# Plan: Membership correction slice C-1, Annul and Void in the existing kernel, TDD-first (PBDIGIT-268)

**Status:** **APPROVED in principle by the PO, 2026-10-10, with conditions (incorporated below: §"PO conditions").** Implementation may start only after the full gate re-evaluation confirms every condition closed (gate record).
**Contract:** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` (APPROVED by the PO 2026-10-10)
**Gate:** `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md` (condition 7 = this plan's approval + gate re-evaluation)
**Supersedes:** none. **Predecessors:** `20261009-0800-…slice0…`, `20261009-0900-…slice1a…`, `20261009-1100-…slice1b…`.

## Objective

Add **Annul** and **Void** to the **existing** Membership kernel (`app/Contexts/Membership/Domain/Organisational/`). The behaviour is exactly as approved (D-COR-2, D-COR-5 (A)):
- liveness only;
- no implicit consequences;
- G\* rejects invalid post-states;
- `TargetNotLive` for non-live targets;
- appeal restoration (Annul(Annul), Void(Annul), Void(Void)).

No second context, no greenfield code, no change to Admit / End behaviour.

## Scope

**In:**
- new fact classes `Annul`, `Void`;
- `Member::decideAnnul` / `Member::decideVoid`;
- `Resolution` stages 1 – 2 (`live`, `active`, currently stubs returning all facts);
- new `RefusalReason` cases;
- test support: a PHP port of the g6 oracle + a golden fixture;
- unit, differential and property tests;
- mutation measurement;
- developer guide `04`;
- an implementation review.

**Out (PO 2026-10-10 + contract §14.5):**
- Correct and consequences (C-2);
- application handlers / `MembershipAct` / authority (C-3);
- persistence / migration / adapter (C-4);
- HTTP, legacy, cutover, live writes.

## Design points for PO review (decide when approving this plan)

| # | Point | Proposal | Why | Alternative |
|---|---|---|---|---|
| P-1 | `Fact` requires `effectiveAt(): TimePoint`, but the contract says Annul / Void have **no valid-time value** | **Interface segregation:** keep `Fact` = `id()`, `decidedAt()`, `provenance()`. Move `effectiveAt()` to a new `EffectiveDatedFact extends Fact`, implemented by `Admit` and `End`. Annul / Void implement `Fact` + a new `ReversalFact` (`target(): FactId`) | invents no valid time (contract §3.1 / §3.2). Domain consumers call `effectiveAt()` only after `instanceof Admit / End` (`Resolution.php:28, 71`), so their behaviour is unchanged. The persistence adapter's `encode()` (`PostgresMemberRepository.php:218`) calls it at runtime only for Admit / End, because **no C-1 path writes an Annul / Void to persistence**. No adapter code changes | (b) Annul / Void return the target's `effectiveAt()`: invents semantics, **rejected**. (c) Annul / Void throw from `effectiveAt()`: a type lie, **rejected** |
| P-2 | the `decidedAt` requirement for Annul (R-16) | the parameter is nullable; a `null` → refusal `DecidedAtRequired` | matches contract K-A4 (a refusal, not a type error), so the C-3 application layer can pass caller input unchanged | a non-nullable parameter (K-A4 becomes a type guarantee) |
| P-3 | refusal names | new `RefusalReason` cases: `TargetNotFound`, `TargetHasNoDecisionProvenance`, `AnnulOfVoidRefused`, `DecidedAtRequired`, `TargetNotLive` | contract §4. `TargetNotLive` is PO-approved; the others were approved with the contract | — |

## PO conditions (decision 2026-10-10, incorporated verbatim in substance)

- **P-1 approved, with a naming and scope condition.**
  - The name is **`EffectiveDatedFact`** ("Prefer `EffectiveDatedFact` or `ValidTimeFact` over `ValueFact`"; the repository has no prior term; it matches the existing `effectiveAt()`).
  - `Admit` / `End` keep their effective-time semantics. **`Annul` / `Void` get no invented effective-time semantics.**
  - The refactor is **behaviour-preserving**.
  - **No persistence adapter modification under this approval. If an adapter change is required: STOP and report it for separate approval.**
- **P-2 approved.**
  - A missing `decidedAt` → `DecidedAtRequired`.
  - **A test proves the refusal appends no fact and changes no domain state.**
  - **The current time is never substituted.**
- **P-3 approved.**
  - The refusal names are `TargetNotFound`, `TargetHasNoDecisionProvenance`, `AnnulOfVoidRefused`, `DecidedAtRequired`, `TargetNotLive`. Each triggering condition is documented (table below) and each applicable refusal is tested.
  - **Fact liveness is kept distinct from active Membership-period status.**
- **Execution constraints:**
  1. verify and record the actual baseline before modifying production code;
  2. demonstrate behaviour preservation for the interface refactor;
  3. record each required failing test before its production change;
  4. use the oracle for differential evidence, not as a substitute for the approved contract;
  5. stay within the existing Membership kernel and the C-1 scope;
  6. no persistence-adapter, schema, database-behaviour, legacy, cutover or live-write changes without separate authorisation;
  7. report regression and mutation results with their actual commands and outcomes; never claim unrun tests passed.

### Refusal triggers (P-3)

| Refusal | Triggering condition (checked in this order) | Test |
|---|---|---|
| `TargetNotFound` | no fact with the target id in this Member's log | step 6 |
| `DecidedAtRequired` | Annul with `decidedAt` = null | step 5 (+ no fact appended, version / resolution unchanged) |
| `AnnulOfVoidRefused` | Annul whose target is a `Void` fact | step 4 |
| `TargetHasNoDecisionProvenance` | Annul whose target's provenance kind ≠ `Decision` | step 3 |
| `TargetNotLive` | the target fact is **not live** (a live Annul / Void targets it). This is **fact liveness**, not period activity. A live but inert fact is a valid target | step 6 |
| existing invariant refusals | the post-state violates INV-9 (G\*) | step 8 |

(`DuplicateFactId` still applies first to the new fact's own id, as for Admit / End.)

## Steps (strict TDD: every behaviour RED → GREEN → refactor; the failing run is recorded before production code)

- [ ] 0. **Baseline:** run the Membership domain + application unit tests and the 3 Membership architecture tests (no database); record the counts. Expected from the 2026-10-09 audit: 93 / 12 (re-verified at step 0, not assumed).
- [ ] 1. **Refactor (P-1), behaviour-preserving:** introduce `EffectiveDatedFact`. Admit / End implement it. **Evidence:** the identical test counts and results before and after (domain + application + architecture), plus `git diff` showing no behaviour change. **If any adapter edit would be required (e.g. a static-analysis type complaint): STOP and report.**
- [ ] 2. **RED:** `AnnulVoidTest` (K-A1, K-V1, T1, T2): Annul(Admit) / Void(Admit) accepted → the target is dead; the resolution has no periods. *Fails: `Annul` / `Void` / `decide*` do not exist.* **GREEN:** the fact classes, `decideAnnul` / `decideVoid` (minimal), `Resolution::live`.
- [ ] 3. **RED → GREEN (K-A2, T11):** Annul of a `rule` / `attested` target → `TargetHasNoDecisionProvenance`.
- [ ] 4. **RED → GREEN (K-A3, T6):** Annul(Void) → `AnnulOfVoidRefused`.
- [ ] 5. **RED → GREEN (K-A4, P-2):** Annul without `decidedAt` → `DecidedAtRequired`; the test also asserts that **no fact is appended and the domain state is unchanged** (version, log, resolution); no clock is substituted.
- [ ] 6. **RED → GREEN (K-A5, K-V2, T7):** Annul / Void of a non-live target → `TargetNotLive` (D-COR-2). Unknown target → `TargetNotFound`.
- [ ] 7. **RED → GREEN (K-R1, K-R2, T3 – T5):** appeal restoration: Annul(Annul(a)), Void(Annul(a)), Void(Void(a)) → a live again.
- [ ] 8. **RED → GREEN (K-G1, K-R3, T8 – T10):** G\* refuses an overlapping post-state (INV-9). This covers Annul(End) after re-entry and a revival that overlaps.
- [ ] 9. **RED → GREEN (K-H1):** P-KNOWN, KnownAt prefix stability with reversals.
- [ ] 10. **Test support:** a PHP port of `g6_correction_oracle.py`, restricted to the C-1 fragment and to R-2 / D-COR-2, written independently of the production `Resolution`. **Golden cross-check:** a JSON fixture generated once by the Python oracle (bounds H = 5, L ≤ 5; the generation command is recorded in the fixture header) is compared with the PHP port (transcription fidelity only).
- [ ] 11. **Exhaustive differential test (K-X2):** for every history in the bounds, the kernel's verdicts, refusal reasons **and per-period lifetimes** equal the PHP port's.
- [ ] 12. **Properties** (contract §14.4): P-REV-V, P-REV-A, P-APPEAL, P-EQ, P-COMM (with both orders accepted + dependency independence), and the C-1 closed form (§13.5 d).
- [ ] 13. **K-X1 regression:** all existing Membership unit + architecture tests green; Admit / End-only behaviour unchanged.
- [ ] 14. **Mutation measurement** (Infection, scratch configuration as in audit §B.3) on the changed kernel files. Report the MSI with its scope; classify every survivor.
- [ ] 15. **Developer guide** `developer_guide/membership/04_correction_c1_annul_void.md` + `00_index.md` row; an **implementation review** (`docs/publicdigit/reviews/…-membership-c1-implementation-review.md`); a story update; then **STOP** for the PR / PO decision.

**Commits:** one per RED and per GREEN, subject `… (PBDIGIT-268)`, staging explicit paths only. **No database:** steps 0 – 14 are pure PHP (kernel + unit / architecture tests). The PostgreSQL feature suite is not run (the adapter is untouched; shared test-DB caveat, `PBDIGIT-266`).

## Risks

- **P-1 touches a kernel interface:** mitigated by step 1 being behaviour-preserving, guarded by the full existing suite.
- **P-1 and static analysis:** if PHPStan analyses `Infrastructure/Organisational`, `PostgresMemberRepository::encode(Fact $fact)` calling `effectiveAt()` may be flagged once `Fact` no longer declares it. **Fallback, needing PO approval because it touches the adapter:** narrow the parameter type to `EffectiveDatedFact` (a one-line signature change, no behaviour change). Otherwise keep `effectiveAt()` on `Fact` and choose alternative (b)/(c), both rejected. Checked at step 1; reported before any adapter edit.
- **Correlated error between the PHP port and the kernel:** both derive from FP §14.2. Mitigated by the golden fixture from the independently written Python oracle and the specification-derived properties (§13.5).
- **Exhaustive bounds vs CI time:** the bounds are chosen so the suite stays practical; the runtime is reported in the review.

## Open questions (not decided by this plan)

BD-REV-ER, D-COR-1 / 3 / 4, CQ-3, CQ-9, D-COR-5 (B): all outside C-1.

## Progress

- 2026-10-10: plan drafted for PO review (the PO authorised its preparation).
- 2026-10-10: **PO approved in principle, with conditions P-1 (name `EffectiveDatedFact`), P-2, P-3 and execution constraints 1 – 7, incorporated above.**
