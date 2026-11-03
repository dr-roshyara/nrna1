# PublicDigit architecture assessment — baseline, strategic DDD, governance, consistency

**Date:** 2026-11-03 · **Author:** Claude (independent architecture review; **authority: generated**)
**Status:** **REPORT.** It records evidence. It accepts, decides and authorises nothing.
**Snapshot:** branch `election-flow-updated`, HEAD `2b89829cc` (2026-11-03).
**Method:** static inspection of the repository at HEAD; every claim traced to a file, line or recorded act. **No test suite was run; no database was accessed; no deployment was observed; no domain expert was interviewed.**
**Placement:** derived with `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit`.

**Classification vocabulary — never interchanged.**

| Label | Means exactly | Does not mean |
|---|---|---|
| RATIFIED | Formally approved and frozen by an identified authority | Implemented |
| DECIDED | A decision was taken; formal ratification may not be recorded | Ratified |
| ACCEPTED | Reviewed and accepted with evidence, within a stated scope | Complete beyond that scope |
| APPROVED | PO decision; an ARB act may still be pending | Ratified |
| IMPLEMENTED | Present in the codebase at HEAD | Verified, or correct |
| VERIFIED | Supported by a reproducible test or independent evidence | Architecturally right |
| PROVISIONAL / PROPOSED | Current working position, subject to confirmation | Stable |
| OPEN | No decision exists | A gap in analysis |
| UNVERIFIED | A claim exists; the evidence is insufficient | False |
| SUPERSEDED | Replaced by a later decision | Deleted |

> A project report is evidence of what the project **believes**. Where a report and the code disagree, this assessment reports the code and records the disagreement.

---

## Part 1 — Executive assessment

### 1.1 How mature is the architectural baseline?

**Structurally established for the constitutional spine; not established for the product domain; and the artefact that claims canonical status is out of date.**

The repository holds several competing "baselines" and no record says which governs a given review question:

| Artefact | Status | Scope |
|---|---|---|
| `docs/implementation/Architecture_Baseline_1.1.md` | FROZEN 2026-06-27; self-described "canonical baseline against which all future greenfield-Core code reviews are measured"; **not** listed among authoritative documents in `.claude/MEMORY.md` | greenfield Core |
| `docs/implementation/PushB_Architecture_Blueprint.md` v1.0 | FROZEN 2026-07-06, IMMUTABLE; **is** the one MEMORY names as "Architecture (FROZEN)" | correction loop |
| `engineering/architecture/baseline/Phase-03A-Reference-Architecture.md` | platform-track reference architecture | engineering platform |
| `docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md` | ACCEPTED 2026-08-17 as authoritative **current-state reconstruction**; explicitly "no target architecture, no redesign" | KnowledgeOS |

The **pattern** content of Baseline 1.1 still holds in code (verified — see Part 4). Its **factual** content does not: the frozen aggregate description and the "sole Adjudication event" claim are both false at HEAD. The baseline's own change-control rule — "change only via ADR-T + Architecture Review" — was honoured in decisions (ADR-T20…T23 exist) but never executed on the artefact.

### 1.2 How mature is Strategic DDD?

**Deep in one context, structurally incomplete across the domain, and blocked on decisions rather than on analysis.**

The register's own count is correct on inspection: of nine exit criteria, three met, three partly met, three not met. Membership is the only fully ratified bounded context. The context map is PROPOSED. The committee write model's ownership is open (DR-07); authorisation ownership and geography history are with the ARB (XD-4, XD-5); the vocabulary split is open (O-14); the statutes that would answer three business-rule questions have **not been requested**.

Discovery quality where it was performed is genuinely high — the Membership domain is modelled to fact, decision and interval level, with correction, annulment and concurrency semantics. This is not a project that lacks discovery capability. It is a project whose discovery has reached decisions it is not taking.

### 1.3 How stable is the governance model?

**Strong and unusually well-instrumented; its protection of the baseline is the weak point.**

Authorities are separated (PO, Human ARB, Membership owner, Principal Reviewer, Engineering); ratification is distinguished from readiness and from acceptance; residual risks are recorded when slices are accepted; a freeze criterion adopted 2026-08-01 requires residue to be **enumerated**. Four failures are visible:

1. **Baseline protection failed in fact** — the frozen baseline was overtaken by lawful decisions without amendment.
2. **Artefact authority is ambiguous** — the repository itself records "four different ADR series in three directories … no single next number exists" (AMB-6).
3. **Enforcement is partial** — deptrac covers 4 of 11 context directories (241 of 1,767 application files); PHPStan at max covers three contexts; the rest is protected by token-scanning tests.
4. **Its own constitution is unratified** — ES-001…ES-006 all carry `Status: PROPOSED`, and EP-01/EP-02/EP-03 exist only in the DRAFT v1.1. The practice is real; the rule text it cites is not adopted.

### 1.4 How consistent are design and system?

**Consistent wherever a context is ratified; contradictory in six recorded places, two of them structural.**

Material contradictions:

- The product's core capability — the **election lifecycle** — is owned by `app/Domain/Election` + `app/Application/Election` + `app/Models/Election.php`, outside every bounded context.
- **Production runs the legacy Membership representation** (four verified boundary violations against Election) while the ratified replacement is wired to nothing.

### 1.5 Most important remaining gaps

1. **DR-07a — committee write-model ownership.** Blocks the statutes request, the relationship specifications, and the exit criterion on responsible authority.
2. **The event surface has two authorities.** Two live Adjudication events sit outside the frozen catalogue; admission is a recorded Board escalation.
3. **The election core is not inside the architecture.** Three code homes; the governed Election context disclaims lifecycle ownership; the placement ADR is still PROPOSED.
4. **Which baseline governs.** Until ruled, no review can claim to have applied "the baseline".
5. **Vocabulary (O-14).** member / role / observer / ElectionMembership remain overloaded.
6. **Subdomain ranking carries no scope.** The same capability is Core in one record and Supporting in another.

---

## Part 2 — Architectural baseline (A1–A7)

| Dimension | Evidence | Status | Remaining gap | Confidence |
|---|---|---|---|---|
| **A1 Business architecture** | One customer journey and six epics: `docs/publicdigit/backlog/README.md:14-27`.<br>Rules for person/account/affiliation: BR-1…14, PO-approved, BR-9 open.<br>99 `EM-*` Election rules marked ADOPTED inside a catalogue that is PROPOSED and unratified: `business_rules/ELECTION_MANIFESTO.md:4` (EM-OPEN-017).<br>Constitutional constraints: `PushB_Architecture_Blueprint.md` §1.A–1.B.<br>Unresolved business questions numbered: register §4 (O-1…O-18; BQ-, CQ-, OQ- series). | **PARTIAL** | Capabilities exist as three unconnected fragments (delivery journey; a 14-capability boundary marked PROPOSED; an election calibration marked investigation-only) and in no enterprise capability model. Rules for committees, tenure and incompatibility depend on statutes not yet requested. | High |
| **A2 Domain architecture** | Nine context directories in code. Declared context set: EPIC-002 (ARB 2026-07-25) and the Membership act.<br>The 2026-10-08 act ratified **ownership statements for five contexts** (Identity, Membership, Election, Governance, Finance): `context-map.md:69-86`. | **PARTIAL** | Subdomain classification exists but never states its scope (B3). Organisation, Geography and Communication remain candidates. The same business concept has three code homes (C-6). | High |
| **A3 Bounded context architecture** | Contexts explicitly defined for evidence/integrity: `EPIC-002_Canonical_Context_Map.md:95-97`, ACCEPTED 2026-07-25.<br>Membership owns its data with no entitlement shortcut — RATIFIED; the phrase "no shared persistence" is itself recorded as two different sentences and read as a prohibition (`context-map.md:199-211`, C-3).<br>Executable boundary guards: `tests/Architecture/MembershipElectionBoundaryTest.php`. | **PARTIAL** | Governance's ownership text is ratified but the **committee write model's placement** is not (DR-07a, ARB-1…8). Membership ↔ Governance cycle found but unspecified. Runtime violations baselined, not removed (G-1…G-4; S2–S4 gated). Organisation-scope bypasses open: 359 real `withoutGlobalScopes()` calls; the missing-organisation fallback **never fails closed** (PBDIGIT-266/309). | High |
| **A4 Integration architecture** | Event ownership, transaction boundaries and context boundary matrix FROZEN: `PushB` §3, §4, §5, §10.<br>Outbox/inbox/relay decided: ADR-MP-06, `PushB` §6.<br>11 canonical events, single producer each: `Canonical_Event_Catalog_v1.0.md:6-34`. | **PARTIAL** | The context-map **document** is PROPOSED, but six of its eighteen relationships already carry RATIFIED or FROZEN status from the act and the BDR (R-01, R-04, R-05, R-06, R-09, R-17). The other twelve are proposed or approved only as principle; R-03 (Identity → Election) is "not established in the reviewed authoritative sources". Two live Adjudication events sit outside the frozen catalogue. | High |
| **A5 Application and technical architecture** | Hexagonal style with application ports; domain purity enforced by test; process manager without saga (ADR-T8); ADR-T1…T23 (T13 DEFERRED, T17 SUPERSEDED by T23, T18 POSTPONED); ADR-MP-01…06.<br>Anonymity and domain-purity guards: `tests/Architecture/GreenfieldCoreArchitectureTest.php:70`. | **DECIDED** | deptrac covers Contestation, Adjudication, Election and Shared only. The `merge-gate` is real and CI-blocking, but the Membership workflow tolerates feature-test failure (`|| true`) and the quality tier is declared non-blocking. Decision records live in four series across three directories (AMB-6). | High |
| **A6 Data and persistence architecture** | State-based Determination with ruling content in the event (ADR-T19; `Determination.php:31-88`); Membership fact ledger with corrections.<br>Per-context migration ownership: `app/Contexts/*/Infrastructure/Database/Migrations/` (Membership 14, Geography 7, Adjudication 4, Governance 2, Contestation 1, Election 1). | **PARTIAL** | One shared PostgreSQL database with `organisation_id` scoping; no tenant or central connection exists. Cross-context FKs are real (`voters.member_id → members` ON DELETE CASCADE). D-1 option C approved as a **direction** only; INV-ORG-SCOPE-1 as a principle only; the Membership migration release gate is closed. | High |
| **A7 Architecture governance** | Readiness → plan → approval → RED → GREEN → acceptance, with acceptance separate from implementation: `Implementation_Process_v1.0` (FROZEN); EP-01/02/03 in the v1.1 DRAFT.<br>Acceptance with recorded residual risks: ACC-260, ACC-271, PBDIGIT-264.<br>Freeze criterion requiring enumerated residue, adopted 2026-08-01: `.claude/MEMORY.md:44-52`. | **DECIDED** | The rule protecting the baseline exists; the artefact was never protected — no amendment record, no 1.2 review, three competing baselines. ES-001…ES-006 are all still PROPOSED. The freeze criterion lives only in `.claude/MEMORY.md`, in no governed document. Ratification and readiness are cleanly separated — that part is genuinely well done. | High |

---

## Part 3 — Strategic DDD maturity (B1–B8)

| Dimension | Evidence | Status | Remaining gap | Confidence |
|---|---|---|---|---|
| **B1 Domain discovery** | Membership modelled to fact, decision and interval level with correction, annulment and concurrency semantics (ratification act §2–§14; D-COR-3; BD-REV-ER). Conflicting interpretations preserved rather than smoothed (O-8, O-9, O-10). | **PARTIAL** | Quality is uneven: Membership is modelled to a standard the other contexts have not reached. No recorded expert evidence base for committees, tenure or incompatibility — the statutes will supply it and have not been requested. | Medium (written records only; no expert interview) |
| **B2 Ubiquitous language** | Per-context vocabulary decisions exist (EM-VOC-001/002/003 ADOPTED). Six dictionaries exist: Round 46 canonical vocabulary (frozen), Round 38C governance glossary (frozen), trust-domain UL, PKS glossary seed, two KnowledgeOS glossaries. | **INCOMPLETE** | No single glossary is adopted (O-14 OPEN) and none of the six governs the product domain. There is an **actual conflict**: EM-VOC-001 defines "Organisation Membership" as the `Member` aggregate, and the Membership reconciliation records that it must be amended by a PO terminology act. member / role / observer / ElectionMembership stay overloaded. | High |
| **B3 Subdomain classification** | Three classifications at three scopes: AIP-14 declares the Election **system** the Core Domain at platform level; the frozen context map calls "Election lifecycle a supporting subdomain" (BDR-08, `context-map.md:74`); a 2026-10-09 review table ranks Adjudication/Contestation/Evidence "Strategic Core" and Election/Voting/Appointment "Supporting". | **PARTIAL** | No classification names its scope or states what makes something Core; none classifies the product domain as a whole. Read unqualified the three look contradictory; read by scope they are probably compatible — but a reader cannot tell which applies. | High |
| **B4 Bounded context discovery** | Contexts derived from business responsibility for evidence/integrity (ACCEPTED) and Membership (RATIFIED).<br>An ungoverned context was investigated rather than assumed: `app/Contexts/Elections/` recorded as a strangler slice with no provider, absent from the declared architecture (`2026-08-13-election-context-architecture-archaeology.md` §8.2). | **PARTIAL** | Committee write-model ownership undecided (DR-07a); the Election authority question is a PROPOSED ADR. The archaeology method that resolved the claim is a recorded CANDIDATE practice, not a standard — correctly, but it means the method is not yet governed. | High |
| **B5 Context mapping** | Strategic map with relationships, open questions and change control; location and role approved by the PO 2026-11-02 (`context-map.md` §10, §14). Relationship **directions** treated as better-evidenced than pattern names — a deliberate, defensible discipline. | **PROPOSED** | Six of eighteen relationships carry RATIFIED or FROZEN status from the act and the BDR; the rest are proposed or unestablished. The Membership ↔ Governance cycle is found but unspecified, so translation responsibility across it is unassigned. | High |
| **B6 Strategic dependencies** | Data ownership and decision authority conceptually separated ("no context decides for another"). Messaging recorded as **preserving** constitutional invariants rather than owning them (ADR-MP-04). | **PARTIAL** | Hidden dependencies on the legacy system remain: production runs the legacy Membership representation; the election lifecycle depends on `app/Models/Election.php` as operational source of truth while the greenfield context mirrors it. A cross-context Domain import survives (Governance → Membership `CommitteeId`). | High |
| **B7 Invariants and business rules** | Constitutional invariants documented and build-breaking: anonymity, single producer, tenant isolation, one aggregate per transaction (PushB §1; ADR-T11; ADR-T14; ADR-MP-04). Ownership of preservation distinguished from ownership of the rule. Anonymity executable (`GreenfieldCoreArchitectureTest.php:70`). | **DECIDED** | Business rules proper — incompatibility, tenure, committee authority — remain open because their source is unavailable. The anonymity guard is a **token scan**: it proves the absence of named linkage terms, not the absence of linkage. | High |
| **B8 Strategic design stability** | RATIFIED: Membership architecture (2026-10-08). ACCEPTED: EPIC-002 context map (2026-07-25). DECIDED: K7 → K′, PO-DR07-01, BR-13 family. APPROVED as direction only: D-1 option C. Under discovery: Governance. | **PARTIAL** | Stability demonstrated for one context and asserted for none other. The register's nine exit criteria — three met, three partly, three not — have not moved since 2026-11-02. | High |

> **Observation on how the language is used.** The project is disciplined about the difference between a discovery and a decision — a report "accepts, decides and authorises nothing". That discipline is rare and is why this assessment can be evidence-based at all. It has a cost: because discovery outputs are promoted only by an explicit authority act, a large amount of correct analysis sits unratified, and the strategic position looks emptier than the thinking behind it.

---

## Part 4 — Consistency between design and system

### 4.1 Confirmed consistency

- **Model B is real.** The Determination aggregate holds no outcome, legitimacy or reason in state; `reconstitute()` restores identity, references and lifecycle state only; ruling content lives in the emitted event (`Determination.php:31-39, 66, 69-88`).
- **Aggregate boundary enforced at the database.** One determination per challenge is a uniqueness constraint, not a convention (`create_determinations_table.php:28`).
- **Anonymity is executable.** AT-Q7-001 scans every context and the messaging surface for forbidden linkage tokens; messaging preserves rather than owns the invariant (`GreenfieldCoreArchitectureTest.php:70`; ADR-MP-04).
- **One aggregate per transaction survives the process manager.** The manager holds no transaction manager; the issuance transaction is opened by the coordinator.
- **The frozen service is still the live path.** `CoordinatesAdjudication` remains wired and used by the issuance adapter and the process manager.

### 4.2 Contradictions

| # | Contradiction | Evidence | Class |
|---|---|---|---|
| **C-1** | **Baseline 1.1 describes a system that no longer exists.** It names a five-state Challenge lifecycle and `DeterminationIssued` as the sole Adjudication event. HEAD has eight lifecycle states and three events. Changes were lawfully decided (ADR-T20…T23); the baseline was never amended. | `Architecture_Baseline_1.1.md:11,19`; `ChallengeState.php:15-22`; `Domain/Events/` (3 files); two commits ever, both June | Stale artefact |
| **C-2** | **The event surface has two authorities.** Two live Adjudication events appear in no catalogue; admission is a recorded stop-and-escalate with three open options. | `plans/20260804-1900-wp4c1-…` escalation block; catalogue: 0 hits for both | Open decision |
| **C-3** | **The most actively developed election code sits in a directory the architecture declines.** `app/Contexts/Elections/` has no provider, is absent from deptrac, and is a downstream strangler slice — yet took the most recent election commit. Making it the bounded context is explicitly refused pending an ADR that is still PROPOSED. | archaeology review §8.2; `ADR_20260813_1722` (PROPOSED) | Structural |
| **C-4** | **Production runs the legacy model the architecture replaced.** The ratified Membership design is wired to nothing; the live representation carries verified defects and four verified boundary violations against Election. | `2026-11-02-membership-ddd-status.md` §1.4–1.5, §5a | Known gap |
| **C-5** | **Two "patterns to copy" coexist.** Baseline 1.1 defines aggregate + ports + outbox with state-based persistence; the only ratified context is a fact ledger with decisions and effective-dated intervals. Neither record acknowledges the other. | `Architecture_Baseline_1.1.md:23-30`; `Membership/Domain/Organisational/` | Unreconciled |
| **C-6** | **The election lifecycle lives outside every bounded context.** Runtime authority is `app/Domain/Election` (rules) + `app/Application/Election` (engine/guard) + `app/Models/Election.php` (orchestration); the greenfield context documents the legacy table as operational source of truth "until a Strangler replaces it". | archaeology review §1, §6, §7; `LegacyElectionExistenceAdapter` | Structural |
| **C-7** | **"No shared persistence" is two sentences in one ratified text**, read as a prohibition. | `…-ratified.md:46` vs `…-ratification-act.md:349`; `context-map.md:199-211` (C-3) | Ambiguity, resolved by reading |

### 4.3 Unresolved ownership

- **Committee write model (DR-07a).** PO-DR07-01 decided the business question (committees are organs; units are separate; existing records unclassified); the Membership owner's M-1…M-5 answers and the ARB act are outstanding.
- **Authorisation (XD-4) and geography history (XD-5).** Referred to the ARB, not decided.
- **Vocabulary (O-14).** With the ARB, not decided.
- **`FeePaid` direction (R-05 / N-5).** Finance's ownership of settlement is ratified; the direction of the fee fact is not, and the pre-ratification theory states the opposite direction from the one recorded.

### 4.4 Strategic decisions not reflected in implementation

- **D-1 option C** — approved as a *direction*; validation and ARB act pending, so no implementation may claim conformity.
- **INV-ORG-SCOPE-1** — approved as a principle; ratification pending; the bypass classes it forbids are open.
- **Context-map relationships** — proposed; nothing may be built to them as contracts.

### 4.5 Implementation that may violate the intended architecture

- Organisation-scoped access failures (PBDIGIT-266, PBDIGIT-309); fail-closed approved, not enforced.
- Runtime gaps G-1…G-4: rule ratified, violations baselined, removal waits on S2–S4 — each gated. **Baselining a violation is not compliance**, and the record says so.
- The Membership ledger migration release gate remains closed.
- Cross-context Domain import (Governance → Membership `CommitteeId`); Trust and Elections publish events with no registered listener (`EPIC-003_Tactical_DDD_Entry_Assessment.md:123-126`).

### 4.6 Verification that checks less than its name implies

- `EventRegistryCompletenessTest` lists three produced event types where at least seven exist (AD-009); nothing asserts an `InboxHandler` is registered at all (AD-010). Both pass green.
- Purity tests skip their own assertions when a directory is absent: `GovernanceDomainPurityTest` skips five, `VocabularyProhibitionTest` skips two, `CommitteeDomainPurityTest` marks three incomplete.
- The project already names this class — "Governance Verification Drift" (AD-004 · AD-009 · AD-010) — so this is a **known** gap, not a discovery.

---

## Part 5 — Remaining work, prioritised

| # | Task | Category | Reason / outcome enabled | Dependencies | Definition of Done |
|---|---|---|---|---|---|
| **P1** | Decide DR-07a (committee write-model ownership) | D1 Strategic | The write model has no assigned home; blocks the statutes request, P11, and the exit criterion on responsible authority | PO-DR07-01 taken; paper ready; Membership owner answers to paper §16 outstanding | ARB act recorded; placement stated in the register; O-6 closed |
| **P2** | Send the statutes request and obtain the statutes | D1 Strategic | Unblocks O-8, O-9, O-10 — incompatibility, membership vs tenure, committee authority | P1 (recipient named); request text prepared (ARB pack §6) | Statutes received; O-8/9/10 answered or transferred with an owner |
| **P3** | Rule on the event surface (catalogue vs code) | D2 Baseline | Two live events are outside the frozen catalogue; until ruled, no integration claim is checkable | Board act; three options already written | Ruling recorded; catalogue and code agree, or the exception is governed |
| **P4** | Decide which baseline governs, and issue Baseline 1.2 | D2 Baseline | A reviewer cannot currently claim to have applied "the baseline" | P3; ARB act | One authoritative baseline per review question; 1.2 accepted with an amendment log; Q-1 answered inside it |
| **P5** | Accept or refuse the code-placement ADR | D2 Baseline | The core capability has three code homes and no context owner | Independent falsification pass named in the ADR | ADR accepted or refused; Election authority recorded in one place |
| **P6** | Decide the vocabulary split (O-14) | D1 Strategic | The terms every context shares are overloaded; a context map cannot be ratified over an ambiguous language | ARB | Terms defined once, with the owning context named per term |
| **P7** | Classify the product subdomains, and rule on the scope of each existing classification | D1 Strategic | Election is Core at platform scope and Supporting at programme scope; neither states its scope | None — a ruling, not research | One classification with explicit scope and a stated test for Core; existing three reconciled or annotated as scope-bound |
| **P8** | Rule the `FeePaid` direction (R-05 / N-5) | D1 Strategic | Finance's ownership is ratified; the direction of the fee fact is not, and the pre-ratification theory states the opposite | PO routing; Finance and Membership owners | Direction recorded; ratifying text and context map agree |
| **P9** | Decide pattern applicability per context (Q-1) | D2 Baseline | Two patterns are presented as "the pattern to copy" | Feeds P4 | Baseline 1.2 states which contexts follow which pattern, and why |
| **P10** | ARB acts: INV-ORG-SCOPE-1; INV-AFF-1; XD-4; XD-5; status of ES-001…ES-006 | D1 Strategic | Four approved-in-principle positions cannot be implemented against; the six governing standards are themselves PROPOSED | ARB session (pack prepared) | Acts recorded; principles binding; standards adopted or draft status acknowledged deliberately |
| **P11** | Context relationship specifications, starting with Membership ↔ Governance | D1 Strategic | The only exit criterion requiring new design rather than a decision | P1 | Cycle specified as two published languages in opposite directions, or explicitly accepted |
| **P12** | Complete Membership correction: COR-4 → COR-3 → Slice 2b | D3 Tactical | ARB-MEM-01 requires correction before cutover; the kernel is accepted but unconnected | COR-2 verdict; PBDIGIT-266 (K-1) | Each slice accepted with residual risks; correction semantics complete |
| **P13** | Open the release gates: isolated test database (O-4), scale figures (O-3), PBDIGIT-266 security tests | D4 Implementation | Nothing involving persistence is verifiable while the gates are shut | PO / Operations | Isolated database assigned; ARB-SEC-01 satisfied |
| **P14** | Repair guard coverage: AD-009, AD-010, skipping purity tests | D4 Implementation | Two guards pass while checking a fraction of what their names claim | P13 (for the inbox assertion) | Produced types derived from code; an unregistered handler fails a test; no untracked skip |
| **P15** | Retire the legacy Membership representation | D4 Implementation | Production runs the model the architecture replaced | P12, P13; cutover ADR | New representation serves production; legacy paths removed |
| **P16** | Strategic exit assessment (register §7) | D1 Strategic | The formal act declaring strategic design complete — or accepting named exceptions | P1–P11 | All nine criteria met, or exceptions accepted with owners and blast radius |

**Eleven of these sixteen are decisions or rulings; two are external acts (PO, Operations); three are delivery work.** The project does not lack discovery capacity — the analysis for most of these is already written.

---

## Part 6 — Maturity verdict

**Levels.** L1 Initial discovery · L2 Concepts identified · L3 Structure and responsibilities defined · L4 Decisions accepted and governed · L5 Stable, verified and ready for controlled implementation.

| Dimension | Level | Basis |
|---|---|---|
| **Business architecture** | **L2 of 5** | Capabilities in three unratified fragments; rules for two areas, one catalogue itself PROPOSED; no enterprise capability model; no domain-wide subdomain ranking. |
| **Strategic DDD** | **L3 of 5** | Contexts derived from business responsibility; map with change control; but only part of the domain ratified, the map document proposed, and three of nine criteria not met with three more only partly. |
| **Bounded context architecture** | **L3 of 5** | Boundaries explicit and, where ratified, enforced by executable guards; committee ownership undecided; two contexts candidates; the core capability has no context owner. |
| **Architecture baseline** | **L3 of 5** | Frozen and structurally sound for the spine it covers; pattern claims still hold in code; but factual claims overtaken, never amended, and not the only artefact claiming the role. |
| **Architecture governance** | **L4 of 5** | Authorities, ratification and readiness cleanly separated; acceptance evidence-based; residual risks recorded. Stops short of L5: baseline protection failed in fact, enforcement partial, and its own six standards are PROPOSED. |
| **Tactical DDD** | **L4 of 5** | Aggregates, value objects, ports, events and process managers defined and decided; 23 tactical ADRs; enforcement tests. Weakest where the tactical model meets the catalogue and where two patterns compete. |
| **Implementation** | **L3 of 5** | Substantial and disciplined where it follows ratified design; but the core runs on legacy representations, the new kernel is unwired, and migrations are behind a closed gate. |
| **Verification** | **L3 of 5** | Independent reviews exist and are honest; database-touching tests unrun for want of an isolated database; some guards check a fraction of what they claim. |

**No combined score is given.** The distribution is the finding: governance and tactical design are ahead of business architecture and strategic completeness — the signature of a project better at *executing* architecture than at *deciding* it.

---

## Part 7 — Strategic recommendation

### 7.1 What can legitimately be declared complete

- **The evidence and integrity context set (EPIC-002)** — ACCEPTED for its own scope, 2026-07-25. Complete *within that scope*; the boundary should be stated whenever it is cited.
- **The Membership architecture** — RATIFIED 2026-10-08: boundaries, correction semantics, temporal model, concurrency contract.
- **The constitutional invariants and their owners** — including the distinction between owning and preserving anonymity.
- **The adjudication / contestation tactical spine** — aggregates, ports, outbox, process manager, and a reference implementation validated against a frozen baseline.

### 7.2 What should remain frozen

- The Membership architecture and its ratified boundaries.
- Anonymity (ADR-T11) and tenant isolation — constitutional, build-breaking, not subject to pattern preference.
- Event-is-seam, one-aggregate-per-transaction, events-version-never-mutate.
- The **pattern** content of Baseline 1.1 — nothing in it failed; only its factual description did.

### 7.3 What should not be reopened

No ratified decision should be reopened on the strength of this assessment. Nothing found contradicts a ratified decision: the contradictions are between **records** and **code**, or between two **unratified** records. Where a potential conflict with a ratified position exists — pattern applicability, and the status of `app/Contexts/Elections/` — the evidence is presented and the ruling left to the ARB.

### 7.4 What should be completed next

A bounded decision sprint — **P1 to P11** — before any new context is started. Membership delivery continues in parallel because it is already governed (P12).

### 7.5 Are we still doing Strategic DDD, or have we moved into Tactical DDD and implementation?

**Predominantly the latter — and that is the problem, because the strategic design is not finished.**

Since 2026-09-01 the repository records 1,869 commits: 1,349 (72%) on the KnowledgeOS corpus and theory track; 431 (23%) on the product; 105 on `app/`. The strategic register has not changed status since 2026-11-02. Two structural signals point the same way: the engineering platform received a formally accepted architecture baseline while the product's went stale, and the frozen methodology states that PublicDigit delivery is the primary focus while most commit activity is elsewhere.

This is not a criticism of the implementation work, which is disciplined and reviewable. It is the inversion the project's own operating loop forbids: tactical work running ahead of strategic decisions that are not being queued at the rate the implementation consumes them.

### 7.6 Evidence needed to declare the Architectural Baseline complete

1. One artefact, accepted by the ARB, stating which baseline governs which review question — competing artefacts retired or scoped.
2. That artefact verifies against HEAD at the moment of acceptance.
3. An amendment record for every frozen element that changed, with the ADR that changed it.
4. Every declared bounded context with a ratified responsibility and owner — Governance included.
5. Enforcement coverage stated explicitly: which contexts are dependency-checked, which type-checked at max level, which rely on token scans.
6. Pattern applicability decided per context.

### 7.7 Evidence needed to declare Strategic Design complete

1. All nine register exit criteria met — every context with defined responsibility, authority and boundary; vocabulary unambiguous; relationships specified; business rules scoped; reversal conditions documented.
2. DR-07a, XD-4, XD-5, O-8, O-9, O-10, O-14 and the `FeePaid` direction decided and recorded with named authorities.
3. The context map ratified, with the Membership ↔ Governance cycle specified rather than merely found.
4. The exit assessment (P16) held, with exceptions accepted explicitly, each with an owner and a blast radius.

### 7.8 The question asked

> Have we completed the fundamental business and strategic architecture work for PublicDigit, and are we now primarily executing the established architecture through Tactical DDD and implementation — or are there still fundamental strategic questions that must be resolved first?

**No, the fundamental strategic work is not complete, and yes, fundamental questions remain.** Three of nine exit criteria are met and three more only partly. The committee write model has no decided home. The vocabulary every context shares is ambiguous, and the subdomain ranking carries no scope. The product's core capability is not inside the architecture at all. The event surface has two authorities.

**But the useful diagnosis is about where the remaining work sits.** It is not discovery — the discovery is largely done and of high quality. It is **decision**: eleven of the sixteen remaining items are rulings only the PO and ARB can make, and several have had their papers ready for some time. Until those rulings are taken, more implementation adds to a system whose strategic frame is still moving underneath it.

**Recommendation.** Hold new bounded contexts. Run P1–P11 to closure. Keep Membership delivery running, because it is the one context whose frame is ratified and stable. Then hold the exit assessment. The cheapest way to make the architecture *look* complete is to start another context; the correct way is to decide the questions holding the current one unfinished.

---

## Limitations

- Static inspection only. No test suite, database or deployment was exercised.
- Test counts and pass rates quoted are the project's own recorded claims unless a review records an independent re-run.
- Business understanding is assessed from written rules and discovery records; no domain expert was interviewed.
- The working tree contains other sessions' uncommitted changes, which were not assessed.
- Decisions taken outside the repository are not reflected.

## Traceability

- Registers: `docs/publicdigit/architecture/strategic-design-status.md`; `docs/publicdigit/reviews/2026-11-02-organisation-governance-strategic-design-status-assessment.md`
- Context map: `docs/publicdigit/architecture/context-map.md`
- Baselines: `docs/implementation/Architecture_Baseline_1.1.md`; `docs/implementation/PushB_Architecture_Blueprint.md`; `docs/implementation/Canonical_Event_Catalog_v1.0.md`
- Decisions: `docs/adr/ADR-T-LOG-Tactical-Implementation.md`; `docs/adr/ADR-MP-Messaging-Platform.md`; `docs/publicdigit/adr/*`
- Governance: `engineering/governance/ES-001…006`; `docs/implementation/Implementation_Process_v1.0`; v1.1 DRAFT
- Code: `app/Contexts/*`; `tests/Architecture/*`; `deptrac.yaml`; `phpstan-greenfield.neon`; `composer.json`
- Debts: `docs/implementation/Architecture_Debt_Backlog.md` (AD-006…AD-010 open)
