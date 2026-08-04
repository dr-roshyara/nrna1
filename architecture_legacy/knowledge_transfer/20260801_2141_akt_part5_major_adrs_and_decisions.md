# Architecture Knowledge Transfer (AKT) — Part 5

**Major ADRs & Decisions — including rejected alternatives**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 5 of 10 + Appendix** |
| **Part** | **Part 5 — Major ADRs & Decisions** |
| **Baseline** | ADR-T log **Accepted unless noted** · ADR-MP-01..06 **Accepted** · ADR-UL-01 / ADR-PL-01 **ARB-ratified 2026-07-08** · Rulings register **R-30..R-64 MINTED** · **R-65..R-71 UNMINTED DRAFTS** |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Parts 1–4.** Part 4 gave the principles; **this Part is their application** |

---

## 0. How to read this Part

**This is the decision inventory. §10 is the most operationally valuable section in the entire AKT** — the rejected, deferred and declined alternatives, recorded so a fresh session does not re-propose something the programme already examined and refused.

> ### **The governance note that heads the ADR index, and it prevents a common misreading:**
> **ADR classes describe the NATURE of a decision, not its IMPORTANCE.** *A Published-Language ADR is not "lesser" than a Strategic one — they govern different concerns.* ⛔ **Do not infer priority from class.**

**Statuses used below are the artifacts' own.** ⚠️ *Where a decision is cited but unminted, this Part says so — see §1.3, which is a live defect you may be the one to hit.*

---

## 1. The decision-record system

### 1.1 The ADR classification scheme

| Class | Governs | Examples |
|---|---|---|
| **ADR-S** | **Strategic Architecture** — bounded contexts, subdomains, strategic decisions | domain ADR-001..008 · Round-38/49 strategic rulings |
| **ADR-UL** | **Ubiquitous Language evolution** — a domain concept/term changes | ADR-UL-01 (`ContestedOutcome`) |
| **ADR-PL** | **Published Language evolution** — integration/event contracts change to reflect UL | ADR-PL-01 (`DeterminationIssued` schema v2) |
| **ADR-PC** | **Platform Capability** decisions | ADR-MP-01..06 (Messaging) |
| **ADR-T** | **Tactical implementation** — aggregates, transactions, versioning | ADR-T-LOG (T1..T23) |
| **ADR-IM** | Implementation / technical | *as needed* |
| **ADR-AIP** | **the Engineering Platform itself** — ⛔ ***never interleaved with business/domain ADRs***; files live in `engineering/architecture/adr/` since EM-001 | ADR-AIP-01 (Baseline v1.0) · ADR-AIP-02 (Product Primacy) |

### 1.2 ⭐ ER-06 — the ordering rule for language changes

```
ADR-UL  →  ADR-PL  →  the event/contract version  →  implementation
```

> **A UL ADR PRECEDES the PL ADR it drives; the PL ADR precedes the contract version; the contract precedes implementation.**

**Why this exists as a rule rather than a habit:** a term changes in the *domain* first. If the contract changes first, the code teaches the business its vocabulary instead of the reverse. **ADR-UL-01 → ADR-PL-01 is the worked example, and the index records the dependency explicitly (*"Drives: ADR-PL-01 — per ER-06"*).**

### 1.3 ⛔ LIVE DEFECT — the R-number range R-65..R-71 is double-spoken-for

**Two independent claims exist on the same numbers, and neither is minted:**

| Claim | Numbers | Status |
|---|---|---|
| **Governance decisions from the documentation-placement workstream** | **R-65** repository is a workspace, not a domain · **R-66** documentation is organized by DOMAIN, not by bounded context · **R-67** `engineering/` expresses cross-product SCOPE, not a business domain · **R-68** ES-005 resolves placement by rule, never by enumeration · **R-69** R-39 is the governing precedent before new repository structure · **R-70** the Artifact Classification Model · **R-71** Placement Is Derived, Never Ad Hoc | ⚠️ ***"approved in substance, UNMINTED"*** — cited across at least eight documents; draft text lives in `engineering/verification/reports/2026-08-01-classification-model-approval-record.md` |
| **The slice-7C recommendation** | **R-65** = 7C authorization · **R-66** = 7C acceptance | ⚠️ **recommended in the 7C pre-authorization verification** |

**The register (`ADR-AIP-LOG-Platform-Rulings.md`) contains 35 rows and ends at R-64.** Verify with:

```bash
grep -oE "^\| R-[0-9]+" engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md | tail -5
```

> ### ⚠️ **Consequence: if 7C is minted as R-65/R-66, it COLLIDES with seven governance decisions already cited by number across the repository.**
>
> **The recorded warning was narrower than the actual problem.** The 7C verification flagged *"R-61..R-64 already issued; never reuse R-61"* — **it did not detect that R-65..R-71 are already spoken for.**

**`OBSERVATION` — routed, not enacted.** **Do NOT resolve this by picking numbers.** Minting is an authority act, and *which* claim gets the range (or whether the unminted set is minted first, or a separate series is opened) is the ARB's decision. **What you must do: raise it before any 7C ruling is drafted.**

---

## 2. Constitutional and strategic decisions

### 2.1 The constitutional invariants (permanent)

*Full text in Part 2 §2.9. Restated here only as an index, because everything else defers to them.*

| CI | One line |
|---|---|
| **CI-1** | no challenge disappears without a terminal legal outcome |
| **CI-3** | election correction **never** modifies anonymous votes; corrections are forward-only |
| **CI-4** | resolution **never** re-opens a finalized Determination |
| **CI-5** | **no consumer may infer voter identity from any event payload** — always-invariant, never "eventual" |

**And the tie-breaker from Part 4 §4:** **SD-5(2) — *anonymity is SUPREME over all other concerns*.**

### 2.2 Domain ADRs (ADR-S class) — all Accepted

| ADR | Decision | Date |
|---|---|---|
| **ADR-0001** | **Committee read/write separation** | — |
| **ADR-001** | **Trust Attestation as a SEPARATE domain** | 2026-05-30 |
| **ADR-002** | ⭐ **Verified ≠ Eligible ≠ Authorized** | 2026-05-30 |
| **ADR-003** | **Governance-driven revocation** | 2026-05-30 |
| **ADR-008** | **Geography domain consolidation** *(Phase 8A — Geography Context Foundation)* | 2026-05-11 |

**⭐ ADR-002 is the one to internalize, because collapsing those three states is the single most tempting simplification in this domain:** *verified* (an attestation exists), *eligible* (the rules admit this person), and *authorized* (this person may act now) are **three separate determinations with three separate owners.** *A system that treats them as one cannot express revocation, temporal eligibility, or delegated authority.*

### 2.3 The strategic model of PKS (Phase II, frozen)

*Full model in Part 2 §4.1.* **The decisions:** CBC-1 Knowledge Assessment and CBC-2 Knowledge Projection are **accepted bounded contexts** · CBC-4 Work Management is **ADJACENT — the domain's outer edge** · CBC-3 Normative Governance is an **accepted CANDIDATE SEAM** *(a formal state, not a hedge)* · the **expressed-knowledge core is an UNPARTITIONED region** · **Risk/Question/Exception record is CONTESTED and UNASSIGNED.**

**Binding citation rule:** cite the **structural layer** (dependency · direction · ownership) freely; **cite Evans pattern names ONLY where they survived DAR-1.**

---

## 3. Language decisions — UL before PL

### 3.1 ADR-UL-01 — `ContestedOutcome` (from `TargetRef`)

**Accepted, ARB-ratified 2026-07-08.**

**Context:** Contestation's `Challenge` held `TargetRef: string` — *"a reference to an election outcome or a prior determination; reference only, carries no vote content."* **An opaque string = PRIMITIVE OBSESSION hiding a business concept.** PB-004 needed to know *which Election* a determination affects; investigation showed **no materialized `electionId` anywhere**, yet **a Challenge is intrinsically scoped to exactly one Election.**

> ### **The diagnosis is the transferable part: *the gap was a DOMAIN-MODEL gap, not a TRANSPORT gap.***
>
> *The tempting fix — add `electionId` to the payload — would have solved the symptom in the wire format while leaving the model still unable to say what a challenge is about.*

**It supersedes in-IDD discovery** (PB-004 DD-4/DD-4a/DD-4b, now closed) — *the concept and glossary live in the ADR as **permanent architecture knowledge**, not in a ticket.*

### 3.2 ADR-PL-01 — `DeterminationIssued` payload schema version 2

**Accepted, ARB-ratified 2026-07-08. Driven by ADR-UL-01 per ER-06.** Adds `ContestedOutcomeRef`.

> ### ⚠️ **ARB clarification, and it is the kind of precision that saves a refactor:**
> **The published business event REMAINS `DeterminationIssued` — only its PAYLOAD SCHEMA evolves (v1 → `schema_version` 2). There is NO `DeterminationIssuedV2` class.** *"v2" throughout means **payload schema version 2**, per ADR-T5 + the Event Registry.*

**Ownership recorded with it:** Adjudication **owns** `DeterminationIssued` · Contestation **owns** `ChallengeRaised` · Election **consumes**.

### 3.3 ADR-T22 — the additive successor

**`DeterminationIssued` gains the `EvidenceSet` considered-set carrier**, entering the issuance command *and* the ruling-bearing event. **Additive schema version via the proven v1→v2 mechanism** (*the hydrator tolerates vPrevious per the versioning rule*). **REALIZED by WP-1, ARB-accepted 2026-07-27** *(status annotation per ES-004.3; decision text unchanged)*.

---

## 4. Tactical decisions — ADR-T1..T23

**Accepted unless noted.** Each *"promotes a decision validated in LIT-A (Decision Traceability Matrix D1–D12) and recorded in Round 50-xx."*

| ADR | Decision | The consequence that bites |
|---|---|---|
| **T1** | **One aggregate per transaction** (consistency boundary) | **no txn writes two roots**; fitness-tested |
| **T2** | **Cross-aggregate collaboration via domain events only** (TP-1) | **the event is the seam; no foreign repo calls** |
| **T3** | **Reliable publishing via transactional outbox; at-least-once** | **consumers MUST dedupe** |
| **T4** | **Idempotent consumers via Inbox/dedupe table** (key = `EventId`) | **no ad-hoc dedupe** |
| **T5** | **Events VERSION, never mutate** — vN+1 convertible-from-old else a **NEW event**; ⛔ **never rename or repurpose a property** | old retained until migrated |
| **T6** | **One repository per aggregate ROOT**; non-root reads via read models | ⛔ **no repo-per-table; no generic repo** |
| **T7** | **Executable architecture conformance** — Deptrac + PHPStan + Pest/PHPUnit + `tests/Architecture` | **drift fails CI** *(manual review is insufficient)* |
| **T8** | **Correction loop WITHOUT saga** — thin coordinator, forward-only `ContainedOnly` correction | **compensation assumes reversibility, and anonymity forbids un-casting** |
| **T9** | **Replay = projection-rebuild, NOT re-execution** | *Decision/side-effecting events are **one-time acts*** |
| **T10** | **`EvidenceEnvelope` immutable + content hash** | freeze-then-immutable; idempotent on `envelopeHash` |
| **T11** | ⭐ **Anonymity invariant (Q7)** — no voter↔vote linkage in any aggregate, event payload or projection; **hashes only** | **constitutional, BUILD-BREAKING** |
| **T12** | **Contestation + Adjudication = binding finality** (the greenfield Core) | *dispute resolution is an open problem in the voting literature — **the trustworthiness differentiator*** |
| **T14** | **Adjudication interaction = Option A: Challenge READ-ONLY**; **Determination is the sole aggregate written** | Challenge is resolved **asynchronously later** by reacting to events |
| **T15** | **`EventOutbox` is an Application PORT**, not an infra service | *the service never publishes directly*: `aggregate → pullEvents() → enqueue() → commit` |
| **T16** | **Cross-context references are LOCAL OPAQUE VOs** (`ChallengeRef`, `EvidenceEnvelopeRef`) | ***the id string crosses the boundary, not the type*** |
| **T17** | LegitimacyDecision → Adjudication domain service | ⛔ **SUPERSEDED by T23** |
| **T19** | **Determination persistence = MODEL B** — state-based aggregate **+ event-as-ruling-record** | aggregate holds lifecycle state; **outcome/legitimacy/reason live in the event**; audit served by a projection |
| **T20** | ⭐ **`Adjudicated` ≠ `Resolved`** | **legal finality ≠ operational completion**; Contestation records two independent facts and **never waits synchronously** |
| **T21** | **`ChallengeRouted` becomes PUBLISHED LANGUAGE** | the correlation mint **relocates to the true chain head**; **AUTHORIZED as WP-3** on WP-2 acceptance |
| **T22** | **`DeterminationIssued` additive payload evolution** | **REALIZED by WP-1** |
| **T23** | ⭐ **SUPERSEDES T17.** **No Adjudication domain service decides legitimacy/sufficiency — the constitutional AUTHORITY decides (Q-1); the APM RECEIVES** | T17 → **Superseded**, *remains valid history, never edited* |
| **T13** | Cryptographic E2E verifiability | ⛔ **DEFERRED (Proposed)** — see §10 |
| **T18** | AggregateVersion optimistic concurrency | ⛔ **POSTPONED** — see §10 |

**The dependency graph is part of the decision.** *A new ADR that contradicts an upstream node — **especially T11 anonymity or T1 one-txn — is REJECTED**; superseding requires an explicit ADR + Architecture Review Gate.*

**⭐ T23 is the best case study in the log, because the ground moved rather than the reasoning being wrong:** T17 anticipated a *LegitimacyDecision domain service*. Then **Q-1 ruled the authority decides and Governance owns validity**, and the Candidate-2 ruling gave judgment orchestration to the PM. **A deciding domain service would now contradict K1.** *A future **advisory** computation — explicitly non-binding, a recommendation an authority weighs — may return through ARB review **with a consumer as evidence**.*

---

## 5. Platform Capability decisions — ADR-MP-01..06

**Accepted 2026-07-07.** *Each ADR **applies** a Platform Governance Principle; **one architectural question per ADR** — the bundled decision-log entry D-12 was too large and was split here.*

| ADR | Applies | Decision |
|---|---|---|
| **MP-01** | PGP-01 | **Messaging is a PLATFORM CAPABILITY** (role) **implemented using a Generic Technical Subdomain** (DDD classification), realized as Shared Infrastructure + a thin Shared Kernel/Published Language. ⛔ **Not a bounded context, not a core/business subdomain, holds NO business decisions.** PB-001 (Registry) + PB-002 (Relay) + PB-003 (Inbox) governed **as ONE capability** |
| **MP-02** | PGP-02 | **Every responsibility has exactly ONE owner**, expressed as **Owns / Coordinates / Preserves / Observes / Does-NOT-own.** *Ambiguous ownership is a modeling defect that blocks promotion.* **Operations owns tunable values (Coordinates); business semantics never enter the platform** |
| **MP-03** | PGP-03 | ⭐ **Owner-hosts-the-guard.** *A preserver must never host an owner's guarantee.* **Consequence recorded: the C6B anonymity guard is MIS-HOSTED → tracked as AD-M1** |
| **MP-04** | PGP-04 | **Constitutional preservation** |
| **MP-05** | PGP-05 | **Deferred evolution is explicit** |
| **MP-06** | — | **Registration ≠ Delivery** *(expressly NOT promoted platform-wide — see §10)* |

---

## 6. Business policy — the Q-2 resolution package

**Kind:** *a business-policy resolution package — the last governance gate before implementation authorization.* **Status: PROPOSED — every value is a DRAFT for ARB ratification, marked INTERIM, with a mandatory stakeholder-review requirement.** ⛔ ***Nothing here is a permanent business rule.***

| Term | Parameter | Interim default | Evidential standing — **as the package itself states it** |
|---|---|---|---|
| **CW** Contestation Window | `contestation_window`, per election type + target type | **30 days** | *30 days is the **only dispute-relevant duration the existing system ever operationalized*** (legacy per-voter audit retention). **The determination-window symmetry carries NO independent evidence — symmetry is the least-assumptive interim choice** |
| **MAD** Maximum Adjudication Duration | `max_adjudication_duration`, per election type | **60 days** | ⚠️ ***this package's WEAKEST number — no implemented business evidence exists for MAD anywhere in the record*** (the transport park-deadline is 60 **minutes** and is not business policy). A deliberately conservative-long placeholder: **premature expiry of a legitimate constitutional deliberation is the worse failure mode**, and **K1 forbids automating our way out of a too-short one.** **PRIORITY item for stakeholder ratification** |
| **LSM** Legal Safety Margin | `legal_safety_margin`, per election type | **30 days** | *the window and horizon measure **process** time; the margin absorbs **world** time* — filings in transit, notice/service lags, jurisdictional review of timeliness. **Removal test: deleting it would make evidence destruction lawful at the exact instant a boundary dispute about timeliness is most plausible.** ***Insurance priced in days*** |
| **EPW** Evidence Preservation Window | **derived, not set** | **= CW + MAD + LSM = 120 days** | see the arithmetic note below |

**⚠️ The arithmetic correction, recorded openly so it cannot read as silent deviation:** *the commissioning text rendered the derivation with "×"; the approved form is **ADDITIVE** — durations compose by **sum**. **A multiplicative composition of durations would be dimensionally meaningless.***

**Demo elections are EXEMPT** — no window, no preservation obligation beyond existing demo-reset practice.

**Two policy consequences worth carrying:**
- **MAD does NOT enter finality directly** (finality follows the determination's own CW). *It bounds how late within the preservation window a determination can still be produced — **which is exactly why it appears in the EPW sum***.
- **Lengthening MAD lengthens preservation for every election of that type.** *A parameter with a non-obvious blast radius.*

**And the resolved contradiction, in the direction the ARB ruled:** ***retention serves the window, not vice versa.***

---

## 7. Documentation, repository and knowledge-governance decisions

### 7.1 The classification model and the derivation principle

| Draft ruling | Decision |
|---|---|
| **R-70** | **The Artifact Classification Model.** An artifact is classified by **Scope · Steward · Maturity · Domain** (Domain N/A when Scope = cross-product). **Classification answers *what is this artifact?*; placement answers *where does it live?*** |
| **R-71** | **Placement Is Derived, Never Ad Hoc.** **Consequence: a placement argument that cannot be grounded in a classification property is OUT OF ORDER** |

⚠️ **Both are *approved in substance* and UNMINTED — see §1.3.**

**Two defects corrected in the model as first drawn, and both are instructive:**
1. **`Location` was nested INSIDE Classification** — *which re-couples exactly what the model exists to divide.* **It is an OUTPUT.**
2. **`Domain` was MISSING** though already approved as the organizing unit — *it IS a classification property, because "which domain's knowledge is this?" is answerable **without knowing where the file sits***.

**⚠️ And the honest limit on the model's claim:** the concepts exist in **three separate places** and **no single coherent implementation exists** — ES-005.3's derivation is implicit with incomplete inputs; the card schema is richer but scoped to `docs/knowledge/` and uses `bounded_context` (**the wrong unit**); the header fields are prose read by nothing. ***Enough to show the model is DESCRIPTIVE not speculative; NOT enough to claim it is already in force.***

**⚠️ The card schema carries FIVE properties, not four:** `status` (maturity) **and** `authority` (*trust/source*) are **separate and explicitly independent.** **The model has no slot for TRUST/PROVENANCE.** *Whether trust is a fifth property or a facet of Maturity was **routed, not decided**.*

### 7.2 The documentation roots ADR

**Decision:** `docs/publicdigit/` · `docs/knowledgeos/` · `docs/pks/`.

**Governing invariants** *(full text in Part 2 §7.2)*: **classification precedes placement** · **artifact identity is independent of physical location.**

**The roots are architectural BOUNDARIES only** — they define **ownership and placement**, and **do NOT prescribe internal information architecture**, which each domain owns and may evolve **without amending the ADR**.

**Transition strategy — two phases, and note that Phase 1 moves nothing:**

| Phase | Content | State |
|---|---|---|
| **1** | Approve the ADR (**no files move, no folders created**) → **classify every document** (Scope · Steward · Maturity · Domain) → deliverable is **a classification map** | ⚠️ **NOT STARTED** |
| **2** | Create roots → move files by classification → update cross-references | ⛔ **BLOCKED** — *prerequisite: **does R-37's freeze bind `docs/`?*** unanswered |

**Rollback is specified, and specifies more than files:** moves back, **plus** cross-references, redirects, documentation-build integrity, searchability, knowledge-reference validation.

**The five open questions the ADR carries:** **(1)** does R-37 bind `docs/`? · **(2)** the stewardship decision for cross-product research · **(3)** cross-reference management strategy · **(4)** when classification fields become mandatory · **(5)** ⭐ **OQ-5 — is KnowledgeOS the same thing as `engineering/`?** *(raised 2026-08-01, **not resolved by the approval, which was editorial**)*

### 7.3 The legacy folder decision

`docs/adr/20260801_1712_legacy_folder_and_files.md`: **legacy architecture → `architecture_legacy/`** · **all developer guides → `developer_guide/`, which is itself a legacy folder** · ⭐ **new developer guides go under `./docs`.**

*(**Note the irony worth flagging: this AKT is being written into `architecture_legacy/knowledge_transfer/` at the user's direction. That is a legacy root. The placement was chosen, not derived** — recorded here rather than silently.)*

### 7.4 The migration registry — declarative, not coded

**`docs/knowledge/schema/repository-migrations.yaml`** (id · from · to · kind · source ADR), read by **`scripts/link-check.php`**.

> ### **Adding a future migration is a REGISTRY ENTRY, not a code change.**

**Documented migrations on record:** **(1)** `architecture/` → `architecture_legacy/` · **(2)** repository-root AI-generated developer docs → `developer_guide/` *(root-normalization: repository-root file only · EXACT filename · relocated file must exist · **no other candidate may exist**)*.

**⭐ The distinction that makes this sound:** ***a DOCUMENTED MIGRATION is evidence; a FILENAME HEURISTIC is not.*** *"Capital letters means `developer_guide`" would be a heuristic — **the rule is the documented relocation**, and every use is existence-verified.*

### 7.5 The link-repair confidence model

| Confidence | Basis | Action |
|---|---|---|
| **100** | git rename record · documented migration · exact existing target | **auto-apply** |
| **99** | **exactly one** file in the repo carries that basename | **auto-apply** |
| **75** | several candidates | ⛔ **AMBIGUOUS — evidence, never a repair** |
| **0** | no candidate | ⛔ **MISSING — evidence, never a repair** |

**Only ≥99 may be auto-applied. Every rule requires the destination to EXIST before rewriting.**

**⚠️ A first attempt was DISCARDED:** it picked the *"best"* candidate when several shared a basename and rewrote `election_management/01-overview.md` to a same-directory `01-overview.md` — ***a plausible-looking wrong answer that would have silently corrupted navigation.*** **Tightened to unique-candidate-only; the multi-candidate cases were then resolved correctly by git-rename evidence.**

**The evidence verdict on what remains:** **47 unresolved references — 0 deleted · 0 renamed · 0 archived · 47 NEVER WRITTEN** (verified with `git log --all -- <path>`). ***They are promised-but-unwritten documents — DOCUMENTATION DEBT PREDATING the refactoring, NOT migration damage.*** **6 more are AMBIGUOUS and need a HUMAN CHOICE, not a repair.**

### 7.6 The ES-005 amendment package — prepared, not applied

**Required authorization: ONE ruling that (a) accepts the package, (b) STATES the amended rule text, (c) records provenance.** *Two acts, never one: **acceptance ≠ authorization-to-edit**.*

**The amendment's one real incompatibility, named rather than discovered later:** **on issuance, 92 artifacts (89 PKS + 3 KnowledgeOS) become IMMEDIATELY NON-CONFORMANT** — their derived location is a domain root that does not yet hold them, and creating it is the blocked Phase 2.

> ### **PREFER recording a TRANSITIONAL NON-CONFORMANCE (the R-39 pattern) over a conditional fallback that makes the rule SELF-NULLIFYING.**
> ***A standard that records its own non-conformance is enforceable; one that dissolves on contact is not.***

**And the schema decision inside it:** **`bounded_context` vs `Domain` IS NOT A RENAME** — *they are two properties at two granularities and **both are valid*** (bounded contexts genuinely live **inside** domains; 11 inside PublicDigit). **ADD `domain:` + a new `schema/domains.yaml`; KEEP `bounded_context:` unchanged.** ⛔ ***Renaming a correct field to fix a MISREADING would destroy a distinction the schema had right.***

---

## 8. WP-7 architecture decisions

### 8.1 A-1 (R-44) — the invariant/mechanism separation

> **"MAD has exactly one home" is the INVARIANT. "Consume Adjudication's existing port" was a MECHANISM — never an architectural decision.** **The invariant is binding; the mechanism is substitutable.**

**Approved realization — option (d): Election declares its OWN consumer-side port.** `EvidencePreservationDurations` (Election/Application/Port) + `ConfiguredEvidencePreservationDurations` (Election/Infrastructure/Config), **mirroring `ConfiguredAdjudicationDurations` exactly** (injected Config, precedence, fail-closed), reading **the one canonical MAD key** in `config/adjudication.php`. **No cross-context import anywhere.** **Deptrac unmodified · domain model unchanged.**

**⭐ The reframing that unlocked it:** G-1 was reclassified from *"engineering placement"* to an **ARCHITECTURE–ENFORCEMENT ALIGNMENT GAP** — *architecture correct · enforcement correct · **the MAPPING between them incomplete***. ***The reclassification changed the ANSWER, not just the label:*** *"placement" invites "put the file where Deptrac doesn't look" — **and that would LOOK like compliance.***

**The ownership statement worth memorizing:** ***MAD is not Adjudication's data — it is Q-2's POLICY, housed in `config/adjudication.php`. Both contexts are downstream of GOVERNANCE, not of each other.***

**Recorded limit:** *"Deptrac passes unmodified" was an **analytical prediction** at ruling time, not an executed result.*

### 8.2 A-2 (R-45) — two responsibilities where one was described

> **The retention guard is named as TWO responsibilities: Election ANSWERS (*may this evidence be deleted yet?*) and Audit/Retention ACTS (the guard).**
>
> **No responsibility HOLDER changes** — consistent with the frozen allocation's *"Consumption (acting on the answer)."*

### 8.3 R-D1 — the honest cost, and the extraction that was declined

**Precedence logic now exists TWICE and could DRIFT.** **Mitigation: a gate asserting both adapters resolve the same MAD for the same `(electionType, organisationId)`** — ***protecting AP-2's INTENT, not its letter.*** ⛔ **Extracting to `Shared` on first repetition was DECLINED** (the second-consumer bar).

### 8.4 The placement principle for enforcement coverage

> ### **Coverage follows MEANING, not the reverse.**
> **The VO / port / service / adapter are gated BECAUSE THEY CARRY POLICY. The folder→election parser, traversal, deletion and CLI stay ungated — CORRECTLY, because they carry none.**

### 8.5 The 7B acceptance debt (R-59, criterion 5)

**`anchorOf()`'s ordering — `results_published_at` → `end_date` → `archived_at` — is executable business behaviour NO AUTHORITY CHOSE.** **Class: the AP-1 defect class in a new form — an invented *RULE*, not an invented *VALUE*. Owner: Q-2.** **Accepted rather than blocked because *the authorization did not prohibit a temporary anchor policy, and acceptance criteria are not redefined retroactively after GREEN*.** **R-60 opens the engineering half: an `EvidenceAnchorResolver` port with a `TemporaryDefaultAnchorResolver`, behaviour unchanged.**

> ***Recorded, not silent: silent architectural debt is UNGOVERNED debt.***

---

## 9. Slice decisions and ratifications

*The full R-43..R-64 chain with authorities and transition types is in **Part 1 §7.2** and the pattern analysis in **Part 3 §8.1**. Not restated (ES-005.4).*

**What to take from it as a DECISION pattern:**

| | |
|---|---|
| **Three acts per slice, three separate authorities** | EP-01 plan approval (**DA**) → execution authorization (**ARB**, **slice-granular**) → acceptance (**ARB**) |
| **A plan can be corrected after approval, as its own act** | **R-57** — the approved plan carried a mechanism **R-44 had superseded**, which would have instructed **the exact cross-context import TP-1 forbids and Deptrac fails** |
| **A separate track does not block a live one** | *"WP-6 remediation is a separate track and does not block 7B"* (R-51/R-54) |
| **Evidence collection is separated from repair** | **R-49** chose *historical investigation FIRST*; **R-50** authorized *reproduce and report only — no production changes, no test repairs, no governance edits* |
| **An impeached evidence line does not void an acceptance** | **R-53** annotates; **R-43 STANDS**; FALSE vs UNSUPPORTED **left UNDETERMINED** |
| **The register audits itself and then refuses to grow** | **R-61 → R-62 → R-63 → R-64** |

---

## 10. ⭐ REJECTED · DEFERRED · DECLINED — do not reintroduce these

**This section exists so that a fresh session does not spend a day re-deriving something the programme examined and refused. Each row states WHAT was refused, WHY, and — where one exists — THE CONDITION that would reopen it.**

### 10.1 Rejected outright

| Refused | Why | Reopens? |
|---|---|---|
| **Model A for Determination persistence** *(the aggregate holds ruling content)* | **duplicates immutable data into mutable state** + would touch the frozen aggregate | ❌ ADR-T19 is Accepted |
| **A cross-context COMMAND to trigger adjudication** | **would invent a new coupling style** — every other loop hop is choreography | **return condition recorded** in EPIC-004K §9 |
| **A saga / compensating transactions for the correction loop** | **compensation assumes reversibility, and anonymity forbids un-casting** | ❌ constitutional |
| **A deciding Adjudication domain service for legitimacy** | **Q-1 rules the authority decides; a deciding service would contradict K1** | ✅ **only as an explicitly NON-BINDING ADVISORY computation, through ARB review, WITH A CONSUMER AS EVIDENCE** |
| **G-1 option (b)** — the service inside Election/Application importing Adjudication's port | **a genuine TP-1 violation** | ❌ |
| **G-1 option (c)** — relax the Deptrac model | ⭐ ***not needed, so NOT PROPOSED.*** **And explicitly NOT pre-authorized: relaxing a correct gate must be its own deliberate act** | ⚠️ **only as the fallback if A-1 had been declined** |
| **G-1 option (a)** — put the service where Deptrac doesn't look | **unguarded — and it would LOOK like compliance** | ❌ |
| **Extracting duration-precedence to `Shared` on first repetition** | **abstraction before a second consumer** | ✅ **a second consumer** |
| **Option C of the artifact-ownership investigation** *(register project tooling in the platform registry)* | **REFUTED, not merely deferred (R-42):** project scripts execute at **no AI runtime moment** and carry **no AIP lineage** — *registering them would require **FABRICATING TRACEABILITY*** | ❌ refuted |
| **Renaming `bounded_context` → `domain` in the card schema** | ***renaming a correct field to fix a MISREADING would destroy a distinction the schema had right*** — they are two properties at two granularities | ❌ |
| **Extending knowledge cards repo-wide** | 40 cards today; repo-wide would oblige **~900 documents**, and duplicating classification in card AND header strains **ES-005.4 never-a-copy** | ❌ *(the artifact's own header is the carrier)* |
| **A conditional fallback in the ES-005 amendment** | **makes the rule SELF-NULLIFYING** | ❌ *(use transitional non-conformance)* |
| **Reverting the prematurely-applied link repairs** | *reverting and re-applying **reproduces the identical repository state and only launders the sequence***; ES-004.3 forbids rewriting history to manufacture consistency | ❌ *(audit + governance note instead)* |
| **A new `DAP-001` principles document under `engineering/`** | a **cross-product artifact at RESEARCH maturity**, which the resolver returns **PENDING** for — *exactly the `Layer_Verification_Rule` violation*. Blocked by **R-38** (no new standards) and **R-37** | ❌ **state the principles in the ADR — the approved policy home — and REFERENCE them** |
| **A fourth framework-growth document** | **extraction on first recognition would violate the repeated-evidence filter growth governance itself holds** | ✅ **a growth rule NO single layer can host, or one that must be RESTATED in two layers (the KC-4 condition)** |
| **Inventing a canonical severity enumeration** | **reviewer-invented vocabulary; no evidence its absence let a defect escape** | ✅ **Q-FW-1, the Authority's call** |
| **A first attempt at link repair using "best candidate"** | **a plausible-looking wrong answer that would have silently corrupted navigation** | ❌ *(tightened to unique-candidate-only)* |
| **Pre-creating the documentation roots as empty directories** | **ES-005.2 forbids speculative empty directories** | ❌ *(a README is each root's first artifact — **satisfying the rule rather than working around it**)* |
| **A `RuntimeAdapters/` directory** | **one implementation is a hypothesis, not a demonstrated abstraction** | ✅ **a SECOND runtime** |

### 10.2 Declined by the admission filter *(never admitted)*

| Refused | Evidence | Note |
|---|---|---|
| **Rule 15 — per-finding confidence labels** | **n = 0** — *no case across four review cycles and three self-audits where a confidence label would have changed a classification, severity or routing* | held as **PMR-6**; reopens on *"the first cycle in which a reviewer cannot express a real uncertainty using authority basis, category, resolving act, or evidence origin"* |
| **A Governance Integrity Review contract** | **n = 0** | — |
| **A Methodology Review contract** | **n = 0 — *and MCA → CDR under Configuration Control already does it*** | **DECLINED as unnecessary, not merely unevidenced** |
| **A Strategic Model Publication Review contract** | ⭐ **ELEVEN real findings, and ZERO requiring a capability the KCR lacks** | **the sharpest demonstration that *usefulness is not the test*** |
| **A meta-governance category extension** | *a single validation is not sustained demand* | **R-64** — reopens on **sustained** operational demand |

### 10.3 Deferred / postponed *(with the condition)*

| Deferred | Condition to revisit |
|---|---|
| **ADR-T13 — cryptographic E2E verifiability** | **a recorded KNOWN LIMITATION.** *Current integrity = hash + audit trail, **weaker than voter-verifiable cryptographic proof**.* Revisit in Part B |
| **ADR-T18 — AggregateVersion optimistic concurrency** | *the one contended invariant is enforced by `UNIQUE(organisation_id, challenge_ref)` + a service guard.* **Revisit when concurrent single-aggregate mutation becomes real** |
| **ADR-T17's advisory successor** | ARB review **with a consumer as evidence** |
| **Fitness Function (FF-01..17) implementation** | **deferred per AIP-14 Product Primacy** |
| **ENG-008 — externalize the link-repair confidence policy** | ⭐ **a SECOND CONSUMER.** *Trigger written INTO the backlog item, because a trigger recorded only in a report is one nobody will see* |
| **ENG-009** | **OQ-5** |
| **The stewardship decision** (cross-product research placement) | *deferred on "the evidence is one artifact"* — ⚠️ **that precondition is NO LONGER the situation (two artifacts). This is a REPORT, not a request to decide** |
| **The ES ratification batch** | Decision Authority |
| **Phase 2 of the documentation roots** | **does R-37's freeze bind `docs/`?** |
| **MCR-5 instrument** | trigger named at issuance |
| **Machine-readable ARCHITECTURAL ownership** | ⛔ **deliberately unopened** *(distinct from documentation coverage — R-42's binding vocabulary)* |
| **The `ExternalPlatform` Deptrac layer** | a recorded future refinement so everything becomes **covered-or-intentionally-ignored** |
| **Modelling `EngineeringConversation` as a platform aggregate** | retrospective candidate — **evidence first** |
| **A physical AKB re-foldering** | **AKB v1.1, as ONE reviewed migration — not piecemeal** |
| **The "AI Engineering Platform" → "PublicDigit Engineering Platform" rename** | **ADOPTED, execution deferred (R-35)** — *gradual, after PB-004 succeeds; **no renames before then*** |
| **Engineering Standards document (R-28/R-32)** | *built only if the PB-004 retrospective triggers R-28* |

### 10.4 Promoted vs NOT promoted — R-36's discipline

**Promoted (6):** ownership-determines-architectural-reuse · reuse-before-create · deferred ≠ skipped · epistemic labels · stop-at-architectural-uncertainty · implementation-evidence-outweighs-unverified-theory.

**⛔ Expressly NOT promoted, and the reasons are the lesson:**

| Not promoted | Why |
|---|---|
| **Registration ≠ Delivery** | **stays Messaging-scoped (ADR-MP-06) — 1 slice of evidence** |
| **Domain-Event ≠ Integration-Event**; the Discovery→IDD→RED→GREEN→Qualification→Completion chain | **Category-B follow-ups** — a principles doc / v1.1 ratification, **separate slices** |
| **Strangler reconstitution · `ChallengeResolvedIntegration` carrier · the PGP-03 hoist · Engineering Standards** | remain **Candidates** |
| **4 items** | **already permanent — duplication REFUSED** |

**Report line worth quoting when you propose a promotion:** *6 promoted · 2 follow-ups · 4 platform-doc · 5 candidates · 4 already-permanent · 1 removal scheduled · **ZERO new documents**.*

---

## 11. Reversal conditions and armed triggers — the live index

**A decision with a named reversal condition is *non-blocking*; one without is *forgotten*. These are armed:**

| Decision | Armed trigger |
|---|---|
| **G-2 — no translator** *(a **defended absence**, ASP-compliant)* | armed for **the authority-decision and evidence-admission slices** |
| **`DeterminationFinalized`** | reversal condition armed at Q-2 |
| **The EPW anchor** | **Q-2's ruling collapses `anchorOf()` to one named field** |
| **WP-7B-R1** | open now (R-60) |
| **The Layer Verification Rule** | ⛔ **if it never escalates in two PROSPECTIVE uses outside EPIC-004, it is CEREMONIAL and should be RETIRED** |
| **Retention becoming a context** | **EPW relocates — a NAMESPACE MOVE, not a redesign**, because ownership/construction/dependency rules don't depend on which context holds the value *(the blast radius is what makes it non-blocking)* |
| **R-39's early promotion** | **the next bounded context that adopts the module either confirms the principles or produces the amendment evidence** |
| **Q-2 interim values** | **mandatory stakeholder review before any live constitutional use** |
| **The review framework's freeze** | **only** an *independent* slice showing it **cannot classify or expose a real issue** — *"Failed to classify" is the ONLY reopening trigger* |
| **The PKS baseline** | **execution evidence → MCA-class assessment → CDR-class decision → issuance** |
| **The KnowledgeOS Phase-II baseline** | ⭐ **reopened ONLY by concrete operational evidence from PublicDigit — never by internal analysis** |

---

## Traceability

**Primary sources (all repository-internal, read at authoring):**

- `docs/adr/README.md` — **the ADR classification scheme, the governance note on class ≠ importance, ER-06 ordering, the register**
- `docs/adr/ADR-T-LOG-Tactical-Implementation.md` — **ADR-T1..T23 in full** (decision · context · consequence · source), the dependency graph and its rejection rule, the ADR template
- `docs/adr/ADR-MP-Messaging-Platform.md` — **MP-01..06**, one-question-per-ADR, AD-M1
- `docs/adr/ADR-UL-01-ContestedOutcome.md` · `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` — the UL→PL pair, the *"no `DeterminationIssuedV2` class"* clarification
- `docs/adr/ADR-001/002/003/008` · `ADR-0001` — domain ADR-S decisions
- `docs/implementation/EPIC-004_Q2_Resolution_Package.md` — **CW · MAD · LSM · EPW**, the additive-arithmetic correction, the removal test, the honesty about MAD's evidence
- `docs/implementation/PushB_Architecture_Blueprint.md` §1 — CI-1..5 / BI-1..2
- `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` — roots, invariants, two-phase transition, rollback, the five open questions incl. **OQ-5**
- `docs/adr/20260801_1712_legacy_folder_and_files.md` — the legacy-folder decision
- `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — **R-30..R-64**, and the verification that **the register ends at R-64**
- `engineering/verification/reports/2026-08-01-classification-model-approval-record.md` · `…-documentation-roots-implementation-report.md` — **R-65..R-71 draft text and their UNMINTED status**
- `engineering/verification/reports/2026-08-01-slice-7b-acceptance-record.md` · `…-wp7-architecture-enforcement-alignment-commission.md` · `…-slice-7c-preauthorization-verification.md`
- `.claude/MEMORY.md` — the confidence bar, migration registry, the 47-reference verdict, the ES-005 amendment's incompatibility, the promotion bars
- `docs/architecture/Architecture_Knowledge_Base_v1.0.md` — AKB v1.1 deferral

**New observations recorded by this Part (routed, not enacted):**
1. **§1.3 — the R-number range R-65..R-71 is double-spoken-for**: seven unminted governance decisions cited across ≥8 documents, while the 7C recommendation proposes R-65/R-66 for slice authorization/acceptance. **The recorded warning ("R-61..R-64 already issued") was narrower than the actual problem.** **Raise before any 7C ruling is drafted.**
2. **§7.3 — this AKT's own placement was chosen, not derived** (`architecture_legacy/`, at the author's direction), which the roots ADR would classify differently.

**Supersedes:** nothing. **Superseded by:** nothing. **Depends on:** Parts 1–4.
