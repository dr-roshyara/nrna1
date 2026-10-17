# Membership Architecture Verification and Completion Plan

**Author:** Session 1 (Architecture and Requirements Authority). **Status:** PROPOSED. This plan decides nothing and approves nothing; every decision and acceptance it names belongs to the owner listed. It reopens no ratified decision (§3.3).
**Baseline:** branch `election-flow-updated`, HEAD `ebdfee484` (2026-10-16). Working tree contains other sessions' uncommitted changes (§8), which are not part of the baseline.
**Method:** requirements extracted from the ratified and decided records (§1); two independent read-only conformance reviews (C-1 against its approved contract, Slice 2a against its approved plan), each finding with `file:line`; the material findings re-read in the code by Session 1. **No test was run, no database was used, no code was changed.** Test results cited are REPORTED, not observed.

**Labels:** **RATIFIED** (Principal Reviewer act 2026-10-08) · **DECIDED** (Human PO / ARB record) · **OPEN** · **VERIFIED** (read in code at HEAD by Session 1 or its reviewers) · **REPORTED** (a committed record says so) · **PROPOSED** (this plan).

**Terminology fix used in this document (PROPOSED).** Three unrelated series share the name "C-n", and "G-1" means different things in different records. Here:
- **COR-1 … COR-4** = correction slices (contract: C-1 kernel Annul/Void, C-2 Correct, C-3 application, C-4 persistence);
- **CUT-1 … CUT-10** = the cutover gate (`2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md:147-156`);
- **XC-1 … XC-12** = the cross-context contradictions (Round 2 report);
- **GOV-END** = "which authority ends a membership" (called G-1 in the Slice 2 plan).

---

## 1. Approved business requirements and domain invariants

Sources: R = `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`; ACT = `…-ratification-act.md`; DR = decision record D-1…D-5 in `…-slice1b-acceptance-review-and-slice2-decision-closure.md`; CC = `…-correction-slice-semantic-contract-draft.md` (approved 2026-10-10); ARB = `…-membership-human-arb-decision-record.md`; EP01 = `…-membership-ep01-implementation-readiness-and-design.md`.

| ID | Requirement / invariant | Source | Status | Still open |
|---|---|---|---|---|
| BR-1 | One Membership per (Person, Organisation); one aggregate (D1) holding its periods and facts (INV-M, INV-1) | R §1, §5, §7 | RATIFIED | — |
| BR-2 | A period is one episode: lifetime contiguous (INV-EP); periods disjoint within (p,o) (INV-9, BD-I5) | R §5, §10 | RATIFIED | — |
| BR-3 | Facts are append-only; nothing deleted or rewritten; KnownAt deterministic and stable (INV-6/14); every fact carries effectiveAt, decidedAt, recordedAt, seq, provenance | R §2, §5 | RATIFIED | tie semantics of recordedAt (§3.2 V-5) |
| BR-4 | Every admission carries exactly one recognised basis (declaration ref / statutory rule / attested history, the last for migration only) | DR D-1 | DECIDED | LQ-1 (Legal) |
| BR-5 | No pending Member: the application stays outside the aggregate; approval records an Admit with the application as declarationRef | DR D-2 | DECIDED | — |
| BR-6 | Ending acts are a closed list: resignation, exclusion, death; `legacy` only for attested migration. Non-payment is a ground, expiry is derived | DR D-3 | DECIDED | D-3-L procedures (Legal) |
| BR-7 | Authority evidence {actor, capacity, ruleRef, policyVersion, result, checkedAt} is recorded on decision facts; Membership stores evidence, Governance owns the rule. Interim adapter mirrors owner/admin for Admit | R §2, §8; DR D-4 | DECIDED (interim) | **GOV-END**, DS-4, CQ-3 (Governance) |
| BR-8 | Backdating allowed within invariants; future-dated admissions and endings refused; technical range 0001-01-01…9999-12-31 | DR D-5(b)(c) | DECIDED | D-5(a) "today" left to engineering (Z1) |
| BR-9 | Correction algebra: Annul targets decision-provenance facts (incl. Annul); Void targets any fact; Annul(Void) refused; Annul(Annul) and Void(Annul) restore subject to G\*; Annul and Void create no consequences | R §2, §9; ACT N-3; CC (D-COR-5 = A) | RATIFIED / DECIDED | CQ-9 appeal process (PO + Legal) |
| BR-10 | Correct targets value-bearing facts and Corrects only; Correct of an annulled fact is inert, of a voided fact refused | ACT N-3 | RATIFIED | — |
| BR-11 | May a correction absorb a whole period (BD-REV-ER)? Recommended: refuse | ACT N-4; CC §9 | **OPEN** | PO; blocks COR-2 |
| BR-12 | Concurrency: read v → resolve → G\* → commit(expected v); on conflict reload, recompute, re-run G\* | R §6; ACT §13 | RATIFIED | — |
| BR-13 | Synchronous (transactional) enforcement of INV-9 (BD-SYNC) | R §10 | RATIFIED | — |
| BR-14 | The resolved lifetime is derived, never stored as truth; projections only | ACT N-2 | RATIFIED | — |
| BR-15 | Membership grants no voting entitlement; Election consumes predicates and events with (MembershipId, asOf); Election-Only organisations never read the ledger | R §1, §8 (INV-11); CUT-9 | RATIFIED / ADOPTED | DS-2, BD-GVR, DS-5 |
| BR-16 | Finance owns fees; Membership consumes FeePaid; Membership publishes period events (`PeriodTrimmed`, `TrimRevived`) | R §8; ACT N-5 | RATIFIED | DS-1, DS-5 |
| BR-17 | Committee participation is not part of the Membership aggregate; legacy committee code inside `app/Contexts/Membership` is a boundary defect | ACT N-6 | RATIFIED (as a defect) | EP-Q2, DR-07 |
| BR-18 | Migration normalises representation and never invents truth (no invented dates, no automatic overlap trimming, exceptions reported) | R §11; CUT-7 | RATIFIED | BD-NORM, EP-Q3, EP-Q4 |
| BR-19 | Correction (COR-1 … COR-4) is accepted before any cutover, migration or live write | ARB-MEM-01 | DECIDED | — |
| BR-20 | Security and persistence tests run only on an isolated, identity-verified database | ARB-SEC-01; Part 2 plan | DECIDED | P-DB, I-1 |

**Derived architectural obligations (PROPOSED; they follow from the rows above and change none of them):**
- **AO-1** Structural invariants (INV-TGT, INV-N3, INV-1, the closed end-kind set, legacy ⇔ attested) must hold on **every** path that produces a Member, including rehydration, not only at decide time (from BR-3, BR-6, BR-9: "invariants stated independently of how they are enforced", R §5).
- **AO-2** The application boundary, not the caller, supplies tenant, actor and the decision date's reference clock (from BR-7, BR-8: authority evidence is meaningless if the actor is self-declared; "future" is meaningless without a trusted "now").
- **AO-3** A Membership aggregate exists only together with its first accepted fact (from BR-1, BR-5: Membership is a relationship opened by admission; D-2 forbids a pending Member).
- **AO-4** Every fact kind the kernel can accept must be storable, or the port must refuse it with a defined outcome before anything else happens (from BR-3, BR-19).
- **AO-5** Membership events reach other contexts only through a transactional, tenant-correct publication mechanism (from BR-13, BR-15, BR-16).

---

## 2. Traceability matrix

| Req | Architecture element | Implementation (VERIFIED) | Tests (names VERIFIED; results REPORTED) | Acceptance evidence | Status |
|---|---|---|---|---|---|
| BR-1 | D1 aggregate, `MemberId`, `findByHolder` | `Domain/Organisational/Member.php`; `PostgresMemberRepository.php`; `org_memberships` PK incl. `organisation_id` | Slice 0 kernel tests; 1a contract suite | Slice 0 / 1a verdict fields blank | implemented; **not accepted** |
| BR-2 | G\* + `InvariantSet` | `Member.php:184-192`; `InvariantSet.php:172-188` | C-1 differential (15,530); T8, T10 | INDEP review of C-1 | implemented; not accepted |
| BR-3 | append-only log, seq, `knownAt` | `Member.php:25-35, 78-83`; adapter `:183` (`max(clock,last)`) | `C1PropertyTest` K-H1 (no ties generated) | — | implemented; tie semantics undefined (V-5); no append-only DB trigger (B-11 M-11 proposal) |
| BR-4 | D-1 guard | `Member.php:101-103`; `AdmitMemberHandler.php:54` | `AdmissionBasisTest`; handler test | 2a evidence | implemented; not accepted |
| BR-5 | no pending Member | **contradicted in effect** by `AdmitMemberHandler.php:66` (identity created before the decision) | none | — | **GAP V-8** |
| BR-6 | closed `EndKind` | `EndKind.php:12-35`; `Member.php:131-133` | `EndKindTest`; handler tests | 2a evidence | implemented in domain; **no DB CHECK, no load-time check** (V-11) |
| BR-7 | `AuthorityCheckPort`, interim adapter | `InterimRoleAuthority.php:26-53` (Admit owner/admin; End denied `interim:unresolved:G-1`) | `InterimRoleAuthorityTest` | — | implemented, **not bound**; End blocked by GOV-END; actor self-declared (V-9) |
| BR-8 | decision-date guard | `Member.php:104-106, 134-136` (`effectiveAt > decisionDate`) | `FutureDatingTest` | 2a evidence | implemented **relative to a caller-supplied date only** (V-7) |
| BR-9 | Annul / Void, liveness | `Member.php:142-181`; `Resolution.php:63-79` | `AnnulVoidTest`, `C1PropertyTest`, differential, golden oracle | INDEP: ACCEPT WITH CONDITIONS; PR: ACCEPT WITH RESIDUAL RISKS | kernel only; **G3 verdict blank**; not storable (V-4) |
| BR-10, BR-11 | Correct, g2, CFC+ | none (no Correct class) | none | — | COR-2 not started; BR-11 OPEN |
| BR-12 | expected-version commit | adapter version CAS (`PostgresMemberRepository.php:159`); 1a contract | 1a race tests (double); PG race tests **never run on an isolated DB** | — | PostgreSQL unverified |
| BR-13 | transactional append | adapter transaction | as BR-12 | — | PostgreSQL unverified |
| BR-14 | derived lifetime | `Resolution` (no stored lifetime) | Slice 0 tests | — | conforms |
| BR-15 | Election-Only isolation (R5) | `tests/Architecture/MembershipLedgerIsolationTest.php` (literal scan) | planted + real-tree tests | 2a evidence | conforms within scan limits (V-10); Election integration not started |
| BR-16 | period events, outbox | **none**: no outbox table in `2026_10_09_100000_…`, no event classes | none | — | **GAP G-A1** |
| BR-17 | committee relocation | committee code still in `app/Contexts/Membership/Domain/Committee` | — | — | separate track; not started |
| BR-18 | migration slice | none | none | — | slice 13 not started |
| BR-19 | ARB-MEM-01 | COR-1 only | — | — | COR-2…4 not started |
| BR-20 | test-database rules | PBDIGIT-283 `DatabaseTargetRules` exists (DONE 2026-10-14); Membership P-DB not provisioned | — | — | partially available (§5 W2) |

---

## 3. Independent assessment of COR-1 (C-1, PBDIGIT-268) and Slice 2a (PBDIGIT-271)

### 3.1 Verdicts (architecture conformance, not acceptance)

| Slice | Against its own acceptance criteria | Against the ratified architecture | Blocking defect in scope? | Session 1 recommendation to the Principal Reviewer and PO / ARB |
|---|---|---|---|---|
| **COR-1** | K-A1 … K-X2 all implemented with tests that genuinely assert them (read, not run); §14.4 properties conform; DoD 1 RED-first only partly met (H-2, already accepted) | conforms: Annul(Void) refused (ACT N-3), Void(Annul) and Annul(Annul) restore (R:30), no consequences (D-COR-5 A); Correct-target sub-policy not applicable (no Correct) | **No** | accept-eligible **with the conditions V-1 … V-5 recorded as entry criteria of COR-3 / COR-4**, once evidence is current (W0) |
| **Slice 2a** | AC-1 … AC-10 conform; AC-5 partial (no handler + adapter integration test; denied evidence discarded) | conforms to D-1 … D-5 as written; two findings (V-7, V-8) show that D-5(b) and D-2 are not yet guaranteed end to end | **No** (nothing reachable) | accept-eligible **with V-7 … V-12 recorded as Slice 2b / HTTP entry criteria**, once evidence is current (W0) and the Principal Reviewer has reviewed the hardening |

**Evidence currency (VERIFIED):** the reported runs predate the Laravel 12 merge; `nikic/php-parser` is v5.9.0 at HEAD, while the 2a evidence cites v5.4.0. The R7/R8 scanner depends on it. Both verdicts need a re-run at the accepted commit (W0).

### 3.2 Findings

| ID | Slice | Finding (VERIFIED unless marked) | Requirement | Severity | Required action / where it belongs | Owner of the decision |
|---|---|---|---|---|---|---|
| **V-1** | COR-1 | `Member::fromLog` (`Member.php:37`) checks only seq order and non-decreasing recordedAt. A stored Annul(Void), or a reversal of a missing target, is resolved without question; `Resolution` would revive the voided target | AO-1, BR-9 | **medium** | load-time structural validation (or equivalent DB constraints) as a **COR-4 entry criterion**, with decode tests | PR (architecture); implementer builds |
| **V-2** | COR-1 | `decideAnnul` (`Member.php:142-165`) checks the **target's** provenance, not the Annul's **own**. CC §3.1 says the Annul carries decision provenance. An attested Annul would be accepted and could never be appealed | BR-9, CC §3.1 | low–medium | small kernel guard (RED first) before COR-3; contract already requires it, so no new rule | PO / ARB authorises the change |
| **V-3** | COR-1 | the creation-path test (`MembershipDecisionCreationTest.php:39-44`) does not cover `new Annul` / `new VoidFact`; the `Annul` constructor allows `decidedAt = null`; protection is source-level only | AO-1 | low–medium | extend R7 to the reversal facts; consider constructor invariants | PR |
| **V-4** | COR-1 | the adapter cannot store Annul/Void: schema `kind` CHECK admits only admit/end (`2026_10_09_100000_…:64-67`); `encode()` calls `effectiveAt()` on the `Fact` interface, which no longer declares it (`PostgresMemberRepository.php:223`), so PHP raises `\Error` before the intended `LogicException`. Rolled back by the transaction (INFERRED) | AO-4, BR-19 | medium (latent) | COR-4 (already deferred, C-AMEND-1 F-1). **Sequencing rule: no COR-3 handler is bound before COR-4 is accepted** | PO / ARB |
| **V-5** | COR-1 | equal recordedAt values are reachable in production (adapter `max(clock, last)`); `knownAt` uses ≤ τ, so tied facts become visible together and the state between them is unobservable. Tests never generate ties | BR-3 (INV-14) | low | clarify KnownAt: either strictly increasing recordedAt per Membership, or KnownAt defined over seq. A clarification of INV-14, not a change | PR |
| **V-6** | COR-1 | RED-first only for part of K-*; seven criteria characterised after GREEN with sensitivity mutants | CC §15.7(1) | low (process) | none; already accepted as H-2 | — |
| **V-7** | 2a | "no future dating" is checked only against the **caller-supplied, unpersisted** decision date (`Member.php:104, 134`). A caller who supplies a future decision date gets a future-dated fact accepted, and nothing records the claim | BR-8, AO-2 | **high once a caller exists** | the application boundary must derive the reference date from a trusted clock in the organisation's time zone, or persist and audit the claimed date (AUD-1). **Entry criterion for Slice 2b / HTTP** | PR (mechanism) + PO (may a recorder enter a decision date at all, or only "today"?) |
| **V-8** | 2a (since 1a) | `AdmitMemberHandler::memberOf` (`:60-72`) creates the Membership **before** the domain decides; a refused admission leaves an empty Membership (in PostgreSQL an `org_memberships` row, version 0) | BR-5, AO-3 | **medium** | Option A (recommended): create the identity atomically with the first accepted Admit. Option B: allow an empty Membership as an identity reservation, and require every reader and count to ignore it. Decide **before Slice 2b binds the PostgreSQL adapter** | PR proposes; PO / ARB decides |
| **V-9** | 2a | tenant and actor are plain strings from the command (`TenantId::fromString($command->tenantId)`); authority is checked for whatever actor the caller names | BR-7, AO-2 | high once wired | command envelope built only from the authenticated context; entry criterion for Slice 2b / HTTP | PR |
| **V-10** | 2a | R7/R8 allowlist is a per-file union (e.g. the adapter could call `Decision::accepted`); `database/` seeders and factories are not scanned; R5 is still a literal scan | BR-15, AO-1 | medium | per-rule allowlists; widen scan roots; move R5 onto the parser (already a 2b follow-up) | PR |
| **V-11** | 2a | no `end_kind` CHECK; an unknown stored kind makes the Member unloadable with an undefined exception; legacy ⇔ attested not re-checked on load | BR-6, AO-1 | medium | Slice 2b: CHECK on the closed set; load-time check of legacy ⇔ attested (provenance is `jsonb`: decide JSON CHECK vs domain-only); decode-failure test | PR proposes; PO / ARB decides the representation |
| **V-12** | 2a | evidence of a **denied** authority check is discarded (`DecideAndAppend.php:48-49`) | BR-7 (audit) | low | decide whether refused attempts are audit facts (Audit slice 12) | PO |

### 3.3 Ratified decisions not reopened

None of V-1 … V-12 changes a ratified or decided rule. V-1, V-3, V-4, V-9, V-10 and V-11 are enforcement obligations of existing invariants. V-2 implements an existing contract sentence. V-5 clarifies INV-14. V-7 and V-8 identify where D-5(b) and D-2 are not yet guaranteed end to end; the rules themselves stand.

---

## 4. Architectural gaps and unresolved decisions

### 4.1 Gaps

| ID | Gap | Evidence | Consequence if left | Proposed handling |
|---|---|---|---|---|
| **G-A1** | No event publication. EP-01 §18 placed "outbox" in Slice 1b; the 1b migration has none and no event classes exist. The shared messaging outbox has open tenant-context defects (PBDIGIT-211 T-1/T-4, Adjudication track, **not touched here**) | migration; CONTEXT 2026-10-09 | BR-15/BR-16 contracts cannot be met; COR-3 cannot emit `FactAnnulled` / `FactVoided` | architecture decision before COR-3 emission and Slice 10/11: Membership-owned outbox in the append transaction, **or** the shared outbox once PBDIGIT-211 is fixed. Owner: ARB |
| **G-A2** | Correction persistence (COR-4) | V-4 | ARB-MEM-01 cannot be met | COR-4 slice (W4) |
| **G-A3** | Structural invariants not enforced on load | V-1, V-11 | corrupted or migrated data silently changes membership truth | one rule for all load paths (AO-1); COR-4 + Slice 2b criteria |
| **G-A4** | Aggregate creation semantics | V-8 | phantom memberships; wrong counts | decision V-8 |
| **G-A5** | Trusted command envelope | V-7, V-9 | self-declared actor, tenant and dates once any entry point exists | one boundary contract for all Membership commands (Slice 2b) |
| **G-A6** | Authority model incomplete: D-4 approved an interim mirror for "admit **and** end", but legacy has no ending policy (Slice 2 plan §2 F9), so End is denied; who may Annul / Void / Correct (CQ-3) and hear appeals (CQ-9) is open | DR D-4; plan F9; CC CQ-3/CQ-9 | no ending and no correction can go live | **one Governance decision package**: GOV-END + CQ-3 + CQ-9 + DS-4 + the G-AUTH-1 port extension |
| **G-A7** | Roadmap drift: EP-01 placed Correct/Annul/Void in Slice 7 after queries and segments; ARB-MEM-01 pulled correction forward as COR-1…4 | EP01 §18; ARB-MEM-01 | two roadmaps in circulation | adopt §5 below as the single sequence (PO sequencing act) |
| **G-A8** | Queries and projections (EP-01 Slice 3) not started; Election, UI and the legacy reverse projection (CUT-5) depend on them | EP01 §18 | integration and cutover have nothing to read | W6 |
| **G-A9** | Committee code inside Membership; seat-vs-membership rule (XC-3, XC-9) unresolved; a cascade from End into Committee would conflict with COR-1 (an annulled End does not restore a cascaded vacate) | ACT N-6; Round 2 report | inconsistent committee rosters | out of Membership scope; prerequisite: the Membership query port (W6). Recommendation for the owners: derived effectiveness (R3), no cascade |
| **G-A10** | No append-only guarantee in the database (only by code convention) | B-11 M-11 (proposal) | INV-6 depends on every writer behaving | decide with B-11 (Membership owner) |
| **G-A11** | Naming collisions (C-n, G-1) | this document's header | wrong gate cited in a decision | adopt COR / CUT / XC / GOV-END prefixes (PO) |

### 4.2 Decision register (unresolved)

| ID | Question | Options (consequence) | Session 1 recommendation | Owner | Blocks |
|---|---|---|---|---|---|
| ACC-258/259 | Slice 0 / 1a verdicts | accept / defer | accept after W0 | Human PO / ARB | W0 exit |
| ACC-268 (G3) | COR-1 verdict | accept with residual risks / defer / reject | accept with V-1…V-5 as recorded conditions | Human PO / ARB | COR-2…4 |
| ACC-271 | Slice 2a verdict | accept / defer | accept with V-7…V-12 as Slice 2b / HTTP entry criteria, after PR review | PR, then PO / ARB | Slice 2b |
| ACC-260 | Slice 1b | stays DEFERRED until Part 2 evidence | — | PO / ARB | Slice 2b |
| BD-REV-ER | may a correction absorb a whole period? | allow (implicit set-aside) / refuse | refuse | PO | COR-2 |
| GOV-END | which authority ends a membership | per statute / platform default / interim mapping | one Governance package (G-A6) | Governance owner | Slice 2b End |
| CQ-3, CQ-9, DS-4 | who may Annul / Void / Correct; appeal body; interim replacement criterion | — | same package | Governance + Legal + PO | COR-3; Slice 9 |
| LQ-1, D-3-L | admission bases; ending procedures | — | — | Legal | live HTTP |
| V-7 | source of the decision-date reference | trusted clock / declared + audited | trusted clock in the organisation's time zone; declared past dates allowed (backdating) | PR + PO | Slice 2b / HTTP |
| V-8 | aggregate creation | A atomic / B reservation | A | PR → PO / ARB | Slice 2b |
| V-5 | KnownAt ties | strict recordedAt / seq-based | seq-based KnownAt, or strictly increasing recordedAt in the adapter | PR | COR-4 |
| V-11 | legacy ⇔ attested in the DB | JSON CHECK / domain + load check | domain + load check; CHECK only for the closed set | PR → PO / ARB | Slice 2b |
| G-A1 | event publication mechanism | own outbox / shared outbox after PBDIGIT-211 | own transactional outbox unless 211 is fixed first | ARB | COR-3 emission; Slices 10–12 |
| D-COR-3, D-COR-4 | trim representation; `target_fact_id` column | — | per CC | PR | COR-2, COR-4 |
| P-DB, I-1 | isolated Membership database and role; revoke `nrna_test` access | dedicated DB / re-use `pbdigit274_isolated_test` under a new authorisation | dedicated, reusing the PBDIGIT-283 rules (no second guard) | PO + infrastructure | W2, PBDIGIT-266 tests |
| BD-TYPE, BD-SUSP, BD-2, BD-NU | type changes, suspension, numbering | — | — | PO | Slices 4–6 |
| DS-1, DS-2, DS-5, BD-GVR | Finance and Election contracts | — | — | context owners | Slices 10–11 |
| BD-NORM, EP-Q3, EP-Q4, BQ-P | migration normalisation; reconciliation of conflicting legacy records | P1 latest / P2 priority / P3 agree-or-queue | P3 with conditions (Round 2 report) | PO + Membership owner | Slice 13 / CUT-7 |
| PBDIGIT-265 | Full-mode eligibility predicate | — | — | Election + Membership | CUT-6 |
| B-11 (M-07…M-11) | authority in transaction, unit of work, ancestor token, knownAtVersion, append-only trigger | — | decide M-11 with G-A10 | Membership owner, then ARB | Slice 9; G-A10 |

---

## 5. Dependency-ordered implementation and verification plan

```
W0 evidence + verdicts ─┬─► W2 PostgreSQL foundation ─► W4 COR-2 → COR-4 → COR-3 ─┐
W1 decision packages ───┤                                                         ├─► W5 Slice 2b ─► W6 outbox + queries ─► W7 slices 4,5,6,8,9 ─► W8 Election/Finance/Audit ─► W9 migration, cutover, UI, retirement
                        └─► W3 kernel hardening (V-1, V-2, V-3, V-8) ─────────────┘
Security track (parallel, must close before any HTTP exposure): PBDIGIT-266, PBDIGIT-270, PBDIGIT-292
```

| Wave | Content | Entry criteria | Exit criteria | Who acts |
|---|---|---|---|---|
| **W0** | database-free re-run at HEAD (clean export, not the shared tree); PR review of 2a hardening; verdicts ACC-258/259/268/271 | authorisation of the run | outputs + exit codes + hashes stored as evidence; verdicts recorded with conditions | implementer runs; PR reviews; PO / ARB decides |
| **W1** | decision packages: Governance (GOV-END, CQ-3, CQ-9, DS-4); PO (BD-REV-ER, V-7, V-8, naming); PR (V-5, D-COR-3/4, G-A1 design) | — | decisions recorded in the decision register | owners in §4.2 |
| **W2** | P-DB + I-1 + provisioning evidence; Part 2 plan reconciled with PBDIGIT-283; fail-closed proof; E4-L; the Membership PostgreSQL suite; then PBDIGIT-264 F-5 → F-4b → F-2 | P-DB provisioned; PV accepted; Part 2 approved | 1b re-entry criteria 1–4 met; ACC-260 decided | infrastructure; implementer; PR; PO / ARB |
| **W3** | kernel hardening story: V-1 (load validation), V-2, V-3, and V-8 per decision | W0 verdicts; V-8 decided; authorisation | RED → GREEN per item; COR-1 differential still 0 mismatches; golden hash unchanged | implementer; Session 1 conformance review |
| **W4** | COR-2 (Correct, g2, CFC+, BD-REV-ER guard) → COR-4 (schema incl. target column and same-Member FK, encode/decode of all fact kinds, load validation, PG race tests) → COR-3 (commands, authority evidence, event emission) | COR-2: BD-REV-ER, D-COR-3. COR-4: W2 done, D-COR-4, V-5. COR-3: CQ-3 / G-AUTH-1, G-A1, COR-4 accepted | CC §14.5 criteria + C-AMEND-1; every kernel-acceptable fact round-trips; no handler bound before COR-4 | as above |
| **W5** | Slice 2b: container bindings; Eloquent role reader; End authority per GOV-END; trusted envelope (V-7, V-9); `end_kind` CHECK + load checks (V-11); scanner hardening (V-10); handler + adapter integration test | W2 accepted; GOV-END decided; V-7, V-8, V-11 decided; authorisation | AC-11…AC-13 + the criteria in §6 | implementer; Session 1 review; PO / ARB |
| **W6** | event publication (G-A1); Slice 3 queries / projections (GetMember, Timeline, History, KnownAt) | G-A1 decided; W5 | projections rebuild = incremental; events emitted in the append transaction | as above |
| **W7** | Slices 4 (Renew / ChangeType), 5 (Suspend / Restore), 6 (numbering), 8 (application → Admit), 9 (Governance port) | per-slice BD decisions; DS-4 for 9 | per-slice ACs | as above |
| **W8** | Slices 10 (Election, incl. PBDIGIT-265), 11 (Finance), 12 (Audit); committee seat rule (G-A9) through the query port | DS-1/2/5, BD-GVR; owners' contract agreement | parity tests; Election sign-off | context owners |
| **W9** | Slice 13 migration under CUT-1…CUT-10 and BQ-P; Slice 14 API/UI; Slice 15 retirement | ARB-MEM-01 (COR-1…4 accepted); security track closed; BD-NORM, EP-Q3/Q4 | CUT-1…CUT-10 evidenced; parity 100 % or signed exceptions; rehearsed rollback | PO, Ops, owners |

---

## 6. Acceptance criteria and required evidence per remaining slice

**Every slice:** story ID; approved plan; RED before GREEN, recorded; regression of COR-1 differential (0 mismatches) and golden hash; evidence = command, commit, PHP / PHPUnit versions, exit code, output hash; developer guide; Session 1 conformance review; PR review; PO / ARB verdict. **Database evidence only from the isolated, identity-verified database.**

| Slice | Key acceptance criteria (Given / When / Then where it matters) | Required evidence |
|---|---|---|
| **W0 re-run** | all Membership database-free suites exit 0 at the accepted commit | stored outputs and hashes |
| **Part 2 / 1b** | 1b contract T1–T13 pass on P-DB; fail-closed proof (a wrong database is refused before connecting); `nrna_test` unchanged before / after | run logs, identity marker, OID, before / after check |
| **264 F-5, F-4b, F-2** | constraint detected by SQLSTATE + name; command row cannot reference a non-existent fact; real-process Admit retry race | PG test output |
| **W3 hardening** | *Given* a stored log containing Annul(Void), *when* the Member is loaded, *then* loading fails with a defined integrity error and nothing is resolved (V-1). *Given* an Annul with attested provenance, *when* decided, *then* refused (V-2). *Given* a refused Admit for a new person, *when* the command completes, *then* no Membership exists (V-8 option A) | unit + architecture tests |
| **COR-2** | CC §14.5 (Correct cases 1–10), BD-REV-ER per decision, g2 equals the oracle | differential vs oracle; properties |
| **COR-4** | *Given* any fact the kernel accepts, *when* appended and reloaded, *then* the reloaded Member resolves identically (round trip for Admit, End, Annul, Void, Correct, consequences); same-Member FK on the target; tie rule (V-5) enforced | PG round-trip + race tests |
| **COR-3** | each command authorises with recorded evidence (CQ-3); idempotency IDEM-1…4 per command; events emitted transactionally; re-decide on conflict | application + PG tests |
| **Slice 2b** | *Given* an authenticated officer of organisation A, *when* they submit an Admit naming organisation B or another actor, *then* the command is built for A and the officer only (V-9). *Given* today's date in the organisation's time zone, *when* an Admit has effectiveAt after today, *then* refused regardless of any supplied decision date (V-7). End allowed only per GOV-END. Unknown stored `end_kind` refused by the DB; a load of legacy-without-attested fails defined (V-11) | integration tests on P-DB |
| **Outbox + Slice 3** | an event exists iff its fact is committed (same transaction); projections rebuild equal to incremental | PG tests; property test |
| **Slices 4–6, 8, 9** | per EP-01 §19 and their decisions | per slice |
| **Slices 10–12** | parity: legacy predicate = ledger predicate per member and month, except signed exceptions; Election-Only never reads the ledger | parity report; fitness test |
| **Slice 13 / cutover** | CUT-1…CUT-10 each evidenced; no invented dates; disagreements queued (BQ-P per decision) | dry-run report; rehearsal log |
| **PBDIGIT-266** | each item confirmed or refuted by a test as a non-privileged user of the same and another organisation (403 expected) | test outputs on an isolated DB |

---

## 7. Division of responsibilities

| Actor | May do | Must not do |
|---|---|---|
| **Session 2 (implementer)** | after authorisation per item: W0 re-run; evidence and review packs; RED/GREEN for authorised slices (W2–W9); PBDIGIT-266 tests within ARB-SEC-01; report deviations from this plan | decide any §4.2 item; change contracts or invariants; choose V-5 / V-7 / V-8 / V-11 / BQ-P semantics; bind handlers before their gates; self-accept; modify another session's work (§8) |
| **Session 1 (architect)** | maintain this plan and the traceability; conformance review after each slice; propose options and recommendations; raise change requests | implement, run tests, decide, accept |
| **Principal Reviewer** | review evidence; recommend verdicts; confirm interpretations (V-5, D-COR-3/4) | formal acceptance |
| **Human PO / ARB** | all DECIDED / acceptance items; sequencing act for §5; authorisations | — |
| **Governance owner** | GOV-END, CQ-3, DS-4 | — |
| **Legal** | LQ-1, D-3-L, CQ-9 / LQ-2 | — |
| **Infrastructure / Ops** | P-DB, I-1, provisioning evidence; CUT-8, CUT-10 | — |
| **Election, Finance, Committee owners** | DS-1/2/5, BD-GVR, PBDIGIT-265, G-A9 | — |

---

## 8. Observations outside this plan (no action by Session 1)

- Staged by another session: deletion of `app/Contexts/Membership/Domain/ValueObjects/TermPeriod.php` (PBDIGIT-304, plan approved 2026-10-16).
- Uncommitted by another session: `tests/Architecture/MembershipDecisionCreationTest.php` sets `UNPARSABLE_KNOWN = []`. Its comment credits PBDIGIT-272 (closed as a duplicate of PBDIGIT-70), and the expiring guard test then iterates over nothing (INFERRED: PHPUnit reports it as risky). For the owning session to resolve.
- Previous Session 1 statements re-checked: the evidence-currency finding (php-parser 5.4.0 → 5.9.0) and the non-storability of Annul/Void are confirmed; the adapter failure mode is now known precisely (V-4).

---

## 9. Progress and TODO

**Where we are:** the architecture is ratified and its first two kernel increments (COR-1, Slice 2a) conform to their contracts with no blocking defect. Nothing is formally accepted; the database layer, correction persistence, authority, event publication and cutover remain.

**Next (in order):**
- PO / ARB: authorise W0; then verdicts ACC-258, 259, 268, 271 with the conditions in §3.2.
- PO: adopt §5 as the single Membership sequence and the naming prefixes (G-A7, G-A11).
- Governance owner: the G-A6 package (GOV-END, CQ-3, CQ-9, DS-4).
- PO / ARB: V-7, V-8 (before Slice 2b); BD-REV-ER (before COR-2).
- ARB: G-A1 event publication.
- PO + infrastructure: P-DB and I-1.
- Session 2: consume this plan; report discrepancies against it.
