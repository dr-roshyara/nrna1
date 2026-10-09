# Membership Theory Synthesis and Implementation-Readiness Audit

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2230_implementation.md` (research and documentation only)
**Status:** RESEARCH / AUDIT. It **ratifies nothing, authorises nothing and changes no code, test, schema or configuration.**

**Labels:**
- **[ADOPTED]** formally adopted or ratified;
- **[DEMO]** demonstrated by inspected code or an executed test;
- **[MODEL]** bounded-model result;
- **[CX]** concrete counterexample;
- **[HYP]** hypothesis or proposal;
- **[BIZ]** business decision required;
- **[NV]** not verified;
- **[BLOCKED]** blocked by an unresolved dependency.

**Linked corpus (not copied):**
- ratification act `2026-10-08-membership-architecture-ratification-act.md` and `…-architecture-ratified.md`;
- final decision register `2026-10-08-membership-final-decision-register.md`;
- Slice 0 / 1a / 1b contracts and reviews;
- the closure report `2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`;
- the plan `2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` (§9–§10);
- `2026-10-09-organisational-participation-domain-research.md`;
- `2026-10-09-participation-semantic-contract-draft.md`;
- `2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`;
- models `docs/publicdigit/membership/architecture/models/`.

## 0. Disagreements (stated first)

| # | Instruction | Position |
|---|---|---|
| Z-1 | §11: deliverables A–D | **One document in four parts.** It is the smallest coherent set; four files would duplicate the shared glossary and labels. |
| Z-2 | §8: statistics | **No statistical claim is possible.** The corpus contains **no empirical Membership dataset with a sampling frame**. The CSV files in the repository root and `storage/` are voter, candidate and user lists. They hold personal data unrelated to the Membership ledger and were **deliberately not opened**. Evidence is therefore exhaustive enumeration (finite domains), property tests, and a new **mutation measurement** (B.3). |
| Z-3 | §8: ML | **No ML system is proposed now.** One auxiliary design (migration duplicate triage) is specified in D.4 as deferred, with data, metrics and error costs. Its prerequisite is the C-7 dry run. |
| Z-4 | §2: "do not change tests" | Respected. The mutation run used a **scratchpad** PHPUnit / Infection configuration. One mutant was re-checked through a scratch autoloader. `git diff` of `app/` and `tests/` is empty. |

## R. Repository state and the transcript discrepancy [DEMO]

**State:**
- Branch `knowelegeos-modelling`. **HEAD `177caf687`**, which matches the report.
- 26 tracked files are modified, plus 3 staged renames under `docs/membership → docs/publicdigit/membership`, plus untracked files. All belong to other sessions or are uncommitted context; they are **left untouched**.

**The stop-hook warning** ("database implementation code changed; update `developer_guide/database/`") is **reconciled**:
- The hook (`.claude/scripts/dev-guide-reminder.sh`) reads the **whole day's** file log `.claude/runtime/2026-10-09-files.log` and maps `database/migrations/*` to the guide area `database`.
- The log contains `database/migrations/2026_10_09_100000_create_org_membership_tables.php`, created in authorised Slice 1b (commit `c562c6a38`, 09:37).
- **None of the seven commits since 12:39** (`0b2685c38` … `177caf687`) touches a file outside `docs/`, `.claude/` or `developer_guide/`. The last code or test commits were `362c5ee03` / `eb29153c7` (12:33).
- That migration's developer guide exists as `developer_guide/membership/03_slice1b_postgres_adapter.md`. `developer_guide/database-schema.md` is a narrow 2026-02 document on voter flags and demo tables, not a general schema index.
- **Conclusion:** the warning is an **area-mapping artefact**, not missing documentation.
- **Not attributable by me:** the uncommitted `database/factories/OrganisationFactory.php` change. It is preserved.

**Fresh test run** (this audit; no test changed):
- Domain and application unit tests: **OK, 93 tests, 5,768 assertions**.
- Membership architecture tests: **OK, 12**.
- PostgreSQL feature tests (`nrna_test`), including the race group: **OK, 27 tests, 184 assertions** (3 deprecations).

---

# Part A: Membership theory synthesis

## A.1 Executive conclusion

**The core is coherent.** Membership *proper* forms an internally consistent theory:
- identity: one Member per (organisation, person);
- episodes: periods, re-entry as a new period under the same Member;
- temporal semantics: valid time in calendar dates, decision and recorded instants, `knownAt`;
- invariants: no overlap, contiguous lifetimes, coverage;
- append-only evidence, with resolution as the only source of truth.

It is ratified [ADOPTED], and for **Admit / End** it is executable and verified [DEMO].

**The theory is incomplete or inconsistent at five edges:**
1. **Authority** (who may decide a Membership act) is undefined (DS-4, CQ-3). The authority port cannot carry a collective decision (G-AUTH-1).
2. **Vocabulary collisions in adopted documents** (A.7: K-1 … K-4).
3. **The ratified correction algebra (Annul / Void / Correct / Trim) is not executable.** Every result that depends on it is unverified by the kernel.
4. **Full-Membership eligibility** reads the legacy `members` table, with two different predicates (`PBDIGIT-265`).
5. **Two potential truths during migration** (legacy `members` vs the ledger) until the cutover gates exist.

None of these edges requires changing an adopted Membership rule. Each requires a decision or an implementation slice.

## A.2 Canonical glossary and ownership

The columns give: meaning · identity and lifecycle · owning context · temporal semantics · transitions and evidence · invariants · read by / **never written by** · enforcement · status.

| Concept | Meaning | Identity / lifecycle | Owner | Temporal | Transitions / evidence | Invariants | Read by / never written by | Enforcement | Status |
|---|---|---|---|---|---|---|---|---|---|
| **Person** | a human identity | identity reference (`PersonRef`) | Identity (DS-3) | — | merge (DS-3, open) | — | all / never by Membership | `PersonRef` value object | [ADOPTED] boundary; equivalence open |
| **OrganisationAffiliation** | a user's relation to a tenant (`organisation_users`) | legacy row | Organisation (legacy) | timestamps | role grant | — | Election-Only voter source / not by Membership | legacy | [DEMO] |
| **Member** | the recognised organisation–person relationship | `MemberId`; one per (org, person) | **Membership** | — | created by the first Admit | INV-M | Governance (ε), Election Full mode, Finance / **never written by other contexts** | `Member` aggregate; `UNIQUE(org, person_ref)` | [ADOPTED] [DEMO] |
| **MembershipPeriod** | one episode of belonging | `PeriodId` = the Admit's FactId | Membership | valid time [start, end) in dates; initial term none / until / unlimited | Admit opens, End closes; re-entry = a new period | INV-9, INV-EP, INV-COV, C-S0-1, C-S0-2 | same | `Resolution`, `InvariantSet` | [ADOPTED] [DEMO] (Admit / End) |
| **Membership fact** | evidence of an act | FactId (INV-FID), seq | Membership | effectiveAt (date), decidedAt, recordedAt (instants) | append-only; corrections are new facts | R-1a-1 … 5 | Audit / never updated | kernel + adapter (no update / delete) | [DEMO]; correction kinds [ADOPTED] not implemented |
| **CommitteeAppointment / Tenure** | a person holding a seat | appointment act → tenure interval | **Governance** | valid interval, bitemporal (CQ-6) | appoint, end, vacancy (EM-GOV-064) | CI-3, CI-4 | Election, Finance, services / never Membership | dormant code only | [HYP] / [BLOCKED] |
| **Mandate** | an organ's authorised scope | versioned | Governance | valid time (amendments) | amendment act | CI-2, CI-8 | all consumers / — | none | [BLOCKED] (CQ-1 … 4) |
| **Authority** | permission to decide or act | derived, never stored | Governance | evaluated **at time of use** (CI-2) | derived from tenure, mandate, delegation, decision | IP-1 | asked through ports | `AuthorityCheckPort` (consumer side); no implementation | [ADOPTED] ownership; content [BIZ] |
| **Duty** | what must be done or answered for | attached to an organ or tenure | Governance | may outlive the tenure (BQ-P8) | discharge act | IP-2, IP-3 | — | none | [HYP] |
| **ElectionRegistration** (ElectionMembership) | a person's registration for an election | election-scoped | **Election** | election timeline | assign, suspend, remove (2026-09-16 decision) | EM-EO-001 … 004 | — / never Membership | Election | [ADOPTED] |
| **VotingEntitlement** | a derived right to vote in an election | derived | Election | as of the election's decision points | — | single point of entitlement (`VotingEntitlementSinglePointTest`) | — | Election | [ADOPTED] [DEMO] |
| **Exercisability** | the right is usable now (codes, window, verification) | derived | Election | instant | — | — | — | Election | [ADOPTED] |

**Never collapsed:**
- Membership grants **no** voting entitlement.
- An appointment creates **no** Member.
- Access is a **projection**.
- Charges and payments are **Finance**'s.

## A.3 Temporal and lifecycle semantics [ADOPTED; executable part DEMO]

- **Valid time:** calendar dates (EP-Q6 = T1). `TimePoint` is an ordinal of days; ∞ for open-ended.
- **Knowledge time:** `decidedAt`, `recordedAt` (µs instants), `seq`.
- **`Member::knownAt(τ)`** resolves only the facts recorded at or before τ, which gives the "as known then" view [DEMO].
- **Resolution order** (ratified): live → active → value → BaseClaim → LiveCut → lifetime → predicates. **Slice 0 implements** the stages needed for Admit / End. Liveness and activeness are trivial, because nothing is annulled (`Resolution.php` stage comments).
- **Re-entry:** a new period under the same Member. A same-day re-entry is accepted; an overlapping one is refused (`OverlappingMembershipPeriods`). [DEMO] Kernel bridge, `g4`: 540 / 540 histories admissible.
- **Correction and annulment** [ADOPTED algebra; **not implemented**]:
  - An annulment makes its target inert, and a correction of an annulled fact is inert (BD-REV).
  - A correction of a voided fact is refused.
  - Consequences follow their cause (CFC+).
- **Meaning of Membership after a correction:** the *current* resolution changes. The *as-known* view at an earlier τ does not. Both are answerable only because facts are never rewritten.

## A.4 Four kinds of act and their evidence

| Act kind | Example | Evidence (existing `Provenance`) | Missing |
|---|---|---|---|
| **The person's own act** | application / declaration (Admit basis), resignation (End) | `decision(actor, evidence, declarationRef)` | the D-1 rule (is a declaration required?) [BIZ] |
| **Registrar attestation** | death; migrated history | `attested(source, importer, note)` | who may attest (CQ-3) [BIZ] |
| **Statutory rule** | ex-officio membership | `rule(ruleRef, ruleVersion)` | LQ-1 [BIZ] |
| **Individual authorised act** | an officer admits under a delegated power | `decision` + `AuthorityEvidence(actor, capacity, ruleRef, policyVersion, …)` | the tenure, delegation and mandate-version fields (G-AUTH-1) |
| **Collective organ decision** | a committee resolves to admit or exclude | **no slot:** neither `Provenance` nor `AuthorityEvidence` carries the organ, decision reference, procedure result or subject | **G-AUTH-1** [HYP], gated by CQ-3 / D-4 |

**G-AUTH-1, the minimal future contract** [HYP; not implemented, not ratified]:
- The request adds an optional `AuthorityContext{subject: PersonRef|MemberId, decisionRef?: string, asOf: TimePoint}`.
- `AuthorityEvidence` gains optional `{organ, decisionRef, tenureRef, delegationRef, mandateVersion, procedureResult}`.
- **Compatibility:** all fields are optional, so the interim D-4 adapter (owner / admin) and a later Governance adapter share one port.
- The Membership fact records the evidence it received. It does **not** validate the procedure; that is Governance's job.
- **This is a port change** (Slice 1a contract), so it needs Principal Reviewer approval in the Slice 2 contract.

## A.5 Modes

- **Election-Only** [ADOPTED EM-EO-001 … 004]:
  - [DEMO] the voter source reads `organisation_users` only; no production code outside `Infrastructure/Organisational` references `org_memberships`.
  - Existing pins prove independence from `members`, but **not yet from the ledger** (T-EO is a Slice 2 entry criterion).
- **Full Membership:** eligibility reads legacy `members`. The bulk and single-user predicates differ (`PBDIGIT-265`, [NV] runtime). That is a **cutover blocker** wherever eligibility could change.

## A.6 Prohibited cross-context writes [ADOPTED §11 + HYP detail]

| Writer | Must never write | Instead |
|---|---|---|
| Election (incl. its committees) | Member facts | read `MembershipQueryPort` (Full mode) |
| Finance (incl. FC) | Member facts, Member status | read predicates; keep its own ledger (design H) |
| Governance (incl. OC) | Member facts directly | answer authority; the competent organ's decision is cited by the Membership command |
| Membership | ElectionMembership, fees, tenures | publish predicates; events only as triggers |
| Audit, service access | anything authoritative | consume and project |

**Live counterexample shape** [DEMO]: `Member::endMembership()` waives fees and removes ElectionMemberships. It is dormant (no caller), and its disposition is DELETE (blueprint).

## A.7 Contradictions register

| ID | Contradiction | Sources | Authority to resolve |
|---|---|---|---|
| K-1 | "Election Committee" = the operating leadership (PO principle, ADR_20261004_1549) vs the independent acceptance body (EM-GOV-029) | committee discovery X-1 | PO + Election owner (CQ-1) |
| K-2 | `EM-FM-005` [ADOPTED · DEFERRED]: "Organisation Chief" governs Membership (term undefined, EM-OPEN-014), vs ratified §11 (Governance owns competence) | Manifesto :362; act §11 | PO / ARB |
| K-3 | ADR-0001 (Accepted) puts the committee write model in `Membership/Domain/Committee`, and ADR-F3.2 D8 calls committee membership "Membership", vs ratified §10 / N-6 (committee participation is not in Membership) | committee discovery; act :19 | ARB status act (RD-5) |
| K-4 | EM-GOV-028 / 056 require an "organisational governance body"; EM-OPEN-049 / 066 forbid inventing it; the PO principle names an Organisational Committee | Manifesto; PO principle | PO / ARB (CQ-2) |
| K-5 | Full-mode eligibility: the bulk and single-user predicates differ | `PBDIGIT-265` | Election owner |
| K-6 | Legacy `endMembership` cascade vs EM-EO-004 | `Member.php:186-189` | already disposed (DELETE); confirm at cutover |
| K-7 | The interim authority (owner / admin) vs "the competent organ under the statutes" | EP-01 principal review :276 | D-4 (labelled interim) |

---

# Part B: Formal evidence and counterexample register

## B.1 Invariant catalogue

"Evidence" names the strongest *kind* of evidence: P = proof or argument; E = exhaustive finite enumeration; R = random / property-based; X = example tests; M = mutation-checked.

| ID | Statement | Owner | Status | Enforced at | Evidence | Counterexample / note |
|---|---|---|---|---|---|---|
| INV-M | ∀ org, person: ≤ 1 Member | Membership | [ADOPTED] [DEMO] | `create` + `UNIQUE(org, person_ref)` | X + real-process race (8 rounds) | none found |
| INV-FID | FactIds are unique per Member | Membership | [ADOPTED] [DEMO] | `decide*` guard + UNIQUE | X, R | — |
| INV-9 | ∀ q ≠ q′: lifetime(q) ∩ lifetime(q′) = ∅ | Membership | [ADOPTED] [DEMO] | `InvariantSet` via G\* | **E** (T ≤ 5, L ≤ 5, independent pointwise oracle) + R + M | — |
| INV-EP | every lifetime is one interval | Membership | [ADOPTED] [DEMO] | `InvariantSet` | E + R | exercised by `InvariantSetTest`. Whether Admit / End histories alone can reach a violation was not analysed here [NV] |
| INV-COV | ⋃ lifetime = ⋃ BaseClaim | Membership | [ADOPTED] [DEMO] (code) | `InvariantSet` | X | **Unreachable with Admit / End only.** A surviving mutant (`IntervalSet::equals`) cannot be killed until cuts or consequences exist (B.3) |
| C-S0-1 | an End is strictly after its period start | Membership | [ADOPTED] [DEMO] | `decideEnd` | E, X; kernel bridge refusal `EndNotAfterPeriodStart` | — |
| C-S0-2 | no initial term ⇒ Lapsed | Membership | [ADOPTED] [DEMO] | resolution | X (T4 persistence too) | — |
| R-1a-1 / 2 | append only at the current version; never reuse a stale decision | Membership app | [ADOPTED] [DEMO] | CAS + bounded re-decide | X + race + M (manual) | — |
| R-1a-3 | recordedAt is monotone in seq | Membership | [DEMO] | adapter clamp; `Member::fromLog` check | X (T6) | **Test gap:** the `fromLog` validation loop is not exercised by the kernel suite (B.3) |
| R-1a-4 | append-only | Membership | [DEMO] | no update / delete path | X (T9 reflection) | — |
| R-1a-5 | explicit tenant everywhere | Membership | [DEMO] | composite FKs | X (T2, T3) | — |
| IDEM-1 … 3 | one (key, fingerprint) gives ≤ 1 fact, and every retry gets the original outcome | Membership app | [DEMO] (End path) | key table + re-checks | race (End) + independent analysis | **Admit race gap (F-2)** |
| P-DATE | valid-time dates round-trip | adapter | **[CX] confirmed defect** | `DateOrdinal` | reproduction | ordinal 2932897 → `10000-01-01` → unloadable (F-1) |
| P-CMD | a command row references exactly its fact | adapter | **[CX]** | — | probe | valid seq + wrong fact_id is accepted (F-4; F-4b chosen) |
| P-ISO | answers assume READ COMMITTED | adapter | [HYP] → N1 chosen | — | probe Q1 … Q4 | nested `SET TRANSACTION` → 25001 |
| BQ-P3 | lapse vs suspend of an office after membership ends | Governance | [BIZ] | — | **E, kernel-admissible**: differ in 110 / 540 | they differ only with re-entry |
| IP-4 | access projections are rebuildable | services | [HYP] | — | E | an event-maintained ACL diverges in 180 / 432 (**UNCHK**: uses annulment) |
| IP-5 / 5a / 5b | Finance: derived target + append-only ledger; version-keyed adjustments; serialised or keyed reconciliation | Finance | [HYP] | — | E, independent oracle | content key fails in 53; unkeyed concurrency double-posts (214 / 223 K-ADM) |
| IP-7 / CI-9 | no cross-context writes | all | [ADOPTED] principle, [HYP] detail | — | P (argument) + E (g2, g3) | legacy `endMembership` shape |
| CI-2 | a delegate cannot exceed the current mandate | Governance | [HYP] | — | E | create-time check wrong in 35 / 375 |
| CI-3 / CQ-6 | validity of acts after a late-recorded tenure end | Governance | [BIZ] | — | E | as-known ≠ as-now in 35 / 250 |
| CI-4 | quorum counts effective seats | Governance | [HYP] / [BIZ] (CQ-5) | — | E | 522 / 3,645 |
| CI-5 / CI-6 | recusal; separation of duties | Governance / Finance | [BIZ] | — | E | 12 / 108; 7 / 16 |

## B.2 Model register

| Model | Domain | Method | Oracle | Kernel status | Establishes | Does **not** establish |
|---|---|---|---|---|---|---|
| Slice 0 `ExhaustiveBoundedVerificationTest` | T ≤ 5, L ≤ 5 histories | E in PHPUnit | independent pointwise oracle (test support) | the kernel itself | kernel = oracle on all bounded Admit / End histories | behaviour beyond the bounds; correction semantics |
| `ReferenceModelPropertyTest` (+ PostgreSQL) | 200 × 40 (memory), 25 × 30 (PostgreSQL) | R | independent array model | the kernel + adapter | the handlers and persistence agree with the model on the generated commands | coverage of rare interleavings (races are separate) |
| `g2_obligations.py` | 222 histories | E | none (DERIVED = truth by construction) | UNCHK (annulment) | the stored-charge shape is fragile | anything about the derived design |
| `g2_ledger.py` + `kernel_filter.py` | 4,675 histories × 9 runs | E | **independent interval oracle** (agrees with the replay after BD-REV) | 223 K-ADM, 3,948 UNCHK-ok, 504 UNCHK-violated | design H matches per month in every class; kernel = oracle on 223 / 223 | correctness of unimplemented correction semantics |
| `g2_ledger_falsify.py` | the same histories | E (attacks F1 … F5) | the same oracle | as above | the version key is needed; a content key fails; immediate refunds round-trip | interest, pro-rata by day, multi-member, re-opened periods |
| `g3_g4_access_vacancy.py`, `g4_reentry.py` | 432 / 540 | E | the spec in the code (weak for g3) | g4: **540 K-ADM**; g3: UNCHK | BQ-P3 is observable only with re-entry | access policy content (BQ-P9) |
| `g5_committee_authority.py` | M1 … M5 (≤ 3,645) | E | spec functions (**M1 use-time check is near-circular**) | n/a (no Membership corrections) | creation-time mandate checks are unsafe; procedure choices change outcomes | statutory semantics; chains; weighted votes |

**Caveats that remain in force:**
- An enumerated count is **not** a probability.
- Agreement with a strategy that restates the spec is weak evidence.
- UNCHK histories are **not** kernel-verified.
- The Python INV-EP filter is a hypothesis filter, not the kernel.

## B.3 Mutation measurement of the Slice 0 kernel (new, [DEMO])

**Run:** Infection 0.29 with pcov. The scope is `app/Contexts/Membership/Domain/Organisational`; the tests are **only** `tests/Unit/Contexts/Membership/Domain/Organisational` (59 tests). The configuration lives in the scratchpad: `phpunit.xml` with that suite and source, plus `infection.json5` with the source directory and log paths.

```bash
php vendor/bin/infection --configuration=<scratch>/infection.json5 --threads=8 \
  --initial-tests-php-options="-d pcov.enabled=1 -d pcov.directory=app/Contexts/Membership/Domain/Organisational"
```

**Result:**
- 349 mutants: **292 killed**, **23 escaped**, **34 uncovered**.
- **MSI 83 %**; mutation code coverage 90 %; **covered-code MSI 92 %**.
- Runtime 1 m 14 s.

**Classification of the survivors (by me, from the diffs):**

| Class | Mutants | Meaning |
|---|---|---|
| **Coverage artefact** | `Resolution.php:29` `continue → break` (reported *uncovered*) | **Killed** when run directly: 11 kernel tests fail (3 errors, 8 failures) through a scratch autoloader, with production unchanged. pcov did not attribute the `continue` line. **The MSI understates the suite slightly** |
| **Test gap TG-1** | `Member.php:26` (validation loop removed); `:28`, `:31` throws uncovered | `Member::fromLog` rejection of out-of-order seq or decreasing `recordedAt` is untested in the kernel suite (the persistence clamp is tested separately) |
| **Test gap TG-2** | `AuthorityEvidence.php:25-27` (field validation removed) | empty evidence fields are accepted unnoticed. Relevant to G-AUTH-1 |
| **Test gap TG-3** | `UnwrapTrim` on `FactId`, `MemberId`, `PersonRef`, `EndKind`; their `Throw_` uncovered | whitespace-only identifiers are untested |
| **Test gap TG-4** | `Decision.php:37,42` (`Throw_` removed) | misuse (`fact()` on a refused decision) is untested |
| **Unreachable in Slice 0** | `IntervalSet.php:76` (used by INV-COV) | INV-COV cannot be violated by Admit / End histories, so this is not killable yet |
| **Dead code** | `Interval::isAdjacentTo` (2 mutants) | no production caller; only `IntervalAlgebraTest` uses it |
| **Equivalent / harmless** | `ArrayOneItem` (InvariantSet: only `[0]` is used), `UnwrapArrayValues` ×3, nullsafe ×2, `PublicVisibility` (methods used only by other layers), `Resolution.php:93` (Slice 7 placeholder) | no semantic change in the kernel's scope |

**What this establishes:** the kernel suite detects 92 % of covered semantic mutations, and **every surviving mutant is classified**. Four minor gaps remain (TG-1 … TG-4), none in Resolution or the invariants.

**What it does not establish:**
- the application and persistence suites were not included, and they might kill more;
- mutation operators are syntactic samples, not proof.

---

# Part C: Theory-to-implementation traceability matrix

Status vocabulary: **IV** implemented and verified · **II** implemented, incompletely verified · **SN** specified, not implemented · **PI** partially implemented · **BL** blocked by a decision · **SD** suspected defect awaiting confirmation · **NI** not investigated.

| # | Business statement | Rule | Invariant | Context → command / port | Persistence fact | Test / model | Backlog | Gate | Status |
|---|---|---|---|---|---|---|---|---|---|
| 1 | a person is at most one member of an organisation | ratified D1 | INV-M | Membership → `AdmitMember` / `create` | `org_memberships` UNIQUE | contract trait; race T12 | — | — | **IV** |
| 2 | membership episodes never overlap | INV-9 | INV-9 | Membership → `decideAdmit` (G\*) | facts | Exhaustive; Property; M | — | — | **IV** (Admit / End) |
| 3 | a membership ends after it starts | C-S0-1 | — | `decideEnd` | End fact | `EndTest`; bridge | — | — | **IV** |
| 4 | an admission without a term lapses | C-S0-2 | — | resolution | `initial_term='none'` | T4 | — | — | **IV** |
| 5 | re-entry is a new period of the same Member | ratified | INV-9 / EP | `decideAdmit` | new Admit | `AdmissionTest`; g4 bridge (540) | — | — | **IV** |
| 6 | history is never rewritten; past knowledge is reconstructable | R-1a-4; temporal model | — | no update / delete; `knownAt` | facts + recordedAt | T9; predicate tests | — | — | **IV** (TG-1 minor) |
| 7 | concurrent decisions never lose an update | R-1a-1 / 2 | — | `DecideAndAppend`; CAS | `version` | race tests; interleaving doubles | — | — | **IV** |
| 8 | a retried command never duplicates and returns the original result | IDEM-1 … 4 | — | key table | `org_membership_commands` | T12 (End); in-memory interleavings | `PBDIGIT-264` F-2 | 264 authorisation | **II** |
| 9 | membership dates are stored exactly | EP-Q6 T1 | P-DATE | adapter | `date` columns | T5 | 264 F-1 | 264 | **SD → confirmed defect** (far dates) |
| 10 | a command record matches its fact | IDEM | P-CMD | adapter | commands table | probe | 264 F-4b | 264 + preflight | **PI** (defect class) |
| 11 | concurrency answers are isolation-safe | IDEM-3 | P-ISO | adapter | — | probes Q1 … Q4 | 264 F-5 (N1) | 264 | **PI** |
| 12 | tenants are isolated | R-1a-5 | — | every port operation | composite FKs | T2, T3 | — | — | **IV** |
| 13 | admission needs a basis (consent / statute / attestation) | D-1 | — | `AdmitMember.declarationRef` (nullable) | provenance jsonb | none | — | D-1 ratification | **BL** |
| 14 | only legitimate ending kinds | D-3 | — | `EndKind` (opaque) | `end_kind` | none | — | D-3 | **BL** |
| 15 | only the competent organ admits or ends | DS-4, CQ-3 | IP-1 | `AuthorityCheckPort` (FakeAuthority only) | `AuthorityEvidence` in provenance | handler tests with doubles | — | D-4, CQ-3, G-AUTH-1 | **BL** |
| 16 | mistakes are corrected without rewriting history | ratified algebra, BD-REV | INV-COV (reachable only then) | — | — | models UNCHK | — | a lifecycle slice + contract | **SN** |
| 17 | suspension, renewal, type, number | BD-SUSP, BD-TYPE, BD-NU | — | — | — | — | — | BD decisions | **SN / BL** |
| 18 | Election-Only never needs Membership | EM-EO-001 … 004 | — | Election voter source | — | `ElectionOnlyEntitlementPinTest` (no `members` row); static: no ledger reference | — | T-EO in Slice 2 | **II** (ledger-independence test missing) |
| 19 | Full-mode eligibility follows Membership | Election rules | — | Election reads legacy `members` | legacy | — | `PBDIGIT-265` | Election owner | **SD** |
| 20 | one authoritative membership truth during migration | R10 | C-0 … C-10 | — | — | inventory (≈ 36 readers, ≈ 13 writers) | plan §4 / §9.5 | cutover slice | **SN** |
| 21 | service access follows authority, not stored lists | IP-4 | — | none (no service exists; legacy `canParticipateInForums()`) | — | g3 (UNCHK) | — | BQ-P9 | **NI** in code / [HYP] |
| 22 | charges and payments are reconciled without rewriting closed periods | IP-5 / 5a / 5b | — | Finance (no ledger) | legacy `fees_status` / `fee_state` | g2 models | — | Finance contract, C-2 | **SN** (research only) |
| 23 | committees act within mandate and procedure | CI-2 … CI-6 | — | dormant `AuthorityAssignment`, `ThresholdRule` | — | g5 | — | CQ-1 … CQ-10 | **BL** |
| 24 | only authorised users approve elections, review committee applications, manage committees, record finance | — | — | live routes | — | static inspection | `PBDIGIT-266` | authorised test-first investigation | **SD** |

**Reconciliation with earlier reports:**
- No status contradicts them.
- **Newly measured:** kernel mutation strength (B.3) and the four minor kernel test gaps.
- The Slice 0 / 1a acceptance remains *referred to* as accepted in instruction `…0922…`, with **no formal acceptance act** recorded.

---

# Part D: Dependency-ordered implementation-readiness roadmap

## D.1 Single recommended next action

**Authorise the test-first investigation of `PBDIGIT-266`.**

**Why first:**
- It is the only open item concerning **live, reachable behaviour** that could let an unprivileged verified user change election or organisational state (item 1: election approval).
- Everything else is pre-production: the ledger migration is **Pending** on dev and unrun elsewhere.
- Its first step is a **test** (confirm or refute), so it is cheap and decision-neutral.

`PBDIGIT-264` follows. It hardens a store that holds no production data yet.

## D.2 Ordered TODO

| # | Item | Owner / authority | Prerequisite | Completion evidence |
|---|---|---|---|---|
| 1 | `PBDIGIT-266`: feature tests per route (same organisation / other organisation / unprivileged) | PO authorisation; Engineering + Security | none | each route confirmed or refuted by a test; fix stories only for the confirmed ones |
| 2 | DV-1 countersignature in the repository | PO | — | one line in the Slice 1b contract amendment |
| 3 | `PBDIGIT-264`: F-1 → F-5 (N1) → F-3 → F-4b → F-2 | PO authorisation (PR dispositions recorded) | preflight queries wherever the 1b migration ran (none known) | RED / GREEN commits; mutation sensitivity for F-2; populated `up → down → up`; fresh PR decision |
| 4 | Kernel test gaps TG-1 … TG-4 | PO authorisation (small test-only story) | — | the escaped mutants are killed; MSI re-measured |
| 5 | CQ-1 vocabulary; K-2 / K-3 status acts | PO + Election owner; ARB | — | recorded acts |
| 6 | CQ-2 + EM-OPEN-049 / 066 | PO / ARB | 5 | a named appointing authority |
| 7 | CQ-3 + DS-4 + D-1 / D-3 / LQ-1 … 3 | PO + Legal + Governance owner | 5 | a ratified Membership decision allocation |
| 8 | D-2, D-5 (b) / (c) | PO | — | ratified |
| 9 | Slice 2 contract incl. **G-AUTH-1**, T-EO, the basis guard, the End-kind list, date validation, the interim adapter (D-4) | PR approval | 3, 7, 8 | an approved contract |
| 10 | Slice 2 implementation (no live entry point) | PO authorisation | 9 | contract tests; race tests; T-EO green |
| 11 | `PBDIGIT-265` | Election owner | — | one shared test matrix for the bulk and single paths |
| 12 | C-2 column map (`fees_status` / `fee_state`) | Finance owner | — | a signed map |
| 13 | Cutover machinery C-0 … C-5, C-8 | PO authorisation | 10, 11, 12 | fitness test (C-0); DB guard incl. mass updates; rebuild = incremental |
| 14 | Dry runs and parity C-6 / C-7; rollout C-10 | PO + Ops | 13; production engine verified (`PBDIGIT-261`) | parity report; rehearsed rollback |
| 15 | HTTP / UI (Admit on application approval; End) | PO authorisation | 14 for the organisation concerned | end-to-end tests |
| 16 | Lifecycle slices: correction / annulment first (it unlocks INV-COV, the UNCHK models, the appeal) | PR contract per slice | 9 | the kernel bridge reclassifies UNCHK → K-ADM / K-REJ |
| 17 | Governance contract (participation + committee authority) | RD-1 … 4, CQ-4 … 10 | 6, 7 | an approved contract |
| 18 | Finance ledger contract (design H, IP-5a / 5b) | Finance owner, BQ-P10 / 11 | 12 | an approved contract |
| ∥ | `PBDIGIT-261`, `262`, `263` (the 263 opt-out under N1) | PO authorisation each | — | per story |

## D.3 Explicitly deferred

- Event Sourcing.
- A shared cross-context kernel.
- Governance, Finance and Audit implementation.
- Service-access policies.
- ML.
- Any change to an adopted rule.

## D.4 Deferred ML design (specification only; not started)

**Purpose:** triage legacy `members` / `users` rows into candidate duplicate persons before C-7 dry runs. It is **advisory**.

| Aspect | Specification |
|---|---|
| **Input** | name, normalised email, phone, birth date if present, organisation, timestamps. Personal data: run on the operator side, never in a repository |
| **Labels** | none initially. A human-labelled sample (stratified by organisation) forms the evaluation set |
| **Method** | blocking + pairwise similarity (record linkage) → clusters |
| **Metrics** | precision and recall on the labelled sample, reported with confidence intervals **only** if the sample is random |
| **Error costs** | a **false merge** creates a wrong Member identity, which is the costlier error. A **missed duplicate** is caught by `UNIQUE(org, person_ref)` only if `PersonRef` is unified (DS-3) |
| **Operating rule** | a high-precision threshold; **every merge confirmed by a human**; deterministic seeds; versioned feature code |
| **Authority** | none. It never decides Membership, identity equivalence (DS-3) or eligibility |

**Changed files:** this document; the models README pointer; the session and context records. **No code, test, schema or configuration changed.**

---

## Errata (2026-10-09): from the independent challenge review

Source: `2026-10-09-membership-audit-challenge-and-pbdigit266-test-plan.md` §2. The text above is kept as written; **these corrections take precedence**.

| # | Severity | Correction |
|---|---|---|
| E-1 | **major** | **Identifier collision "D-1".** Slice 1a contract line 4 and plan §7 use **D-1 = INV-FID** [ADOPTED]. Plan §3 and this audit (A.4, C row 13, D.2 #7) use **D-1 = admission basis (BD-CONSENT / LQ-1)** [BIZ]. Every "D-1" in this audit means the admission basis. New contradiction **K-8** (an identifier collision); the PO decides the renaming (proposal: call the Slice 2 decisions "S2-D1 … S2-D6") |
| E-2 | **major** | **D.2 #16 (correction / annulment) dependencies and order.** Its prerequisites are #10 (Slice 2 implemented, not #9), **BD-REV-ER** (act N-4: "needed before the reversal slice"), **CQ-3** (who may correct or annul) and **CQ-9** (appeal body). **Ordering risk:** #13 – #15 (cutover, migration, live Admit / End) precede any correction capability. Slice 0 refuses a second End (`PeriodAlreadyEnded`), and changing an end date is "Correct(End), Slice 7" (`RefusalReason.php:21`). Erroneous live or migrated facts would therefore be uncorrectable in an append-only store. **Whether correction must precede cutover / HTTP is an explicit PO decision; this audit recommends that it does** |
| E-3 | minor | INV-COV **and** INV-EP are **structurally unreachable in Slice 0**: lifetime = `IntervalSet::of(BaseClaim)` and LiveCut is always empty (`Resolution.php:84-97`). B.1 "[NV] not analysed" (INV-EP) is wrong, and "E + R" there is vacuous. INV-COV is also asserted at every bounded state (`ExhaustiveBoundedVerificationTest`), not only "X" |
| E-4 | minor | B.3: the `IntervalSet.php:76` mutant is **killable now** by a direct unit test of `IntervalSet::equals` on sets of different size. It is **test gap TG-5**, not "unreachable". The sentence "none in Resolution or the invariants" is corrected to: one gap in the invariant support code (`IntervalSet::equals`) |
| E-5 | minor | B.1 legend: P is split into **P-proof** and **P-arg** (informal argument). IP-7 / CI-9 is **P-arg** |
| E-6 | minor | B.1 BQ-P3 "they differ only with re-entry" holds **within the enumerated domain** (0 / 432 without re-entry) |
| E-7 | minor | D.1 rationale: "unrun elsewhere" → production is **not accessible to engineering** (status unknown). `PBDIGIT-265` is also live behaviour. **266 and 264 are independent**: the order is a risk *priority*, not a dependency |
| E-8 | minor | (a) The status vocabulary gains **CD = confirmed defect**: C row 9 → CD; row 10 → CD (P-CMD [CX]). (b) R-1a-1 … 5 are all [ADOPTED] through Principal Reviewer instruction text (contract table), as are C-S0-1 / 2. There is **no formal acceptance act**. (c) D.2 gains "formal Slice 1b acceptance act (PR)" before #3. (d) K-2 also conflicts on *context*: EM-FM-005 places Membership in an "Organisation context", while act §11 places it in Membership |
