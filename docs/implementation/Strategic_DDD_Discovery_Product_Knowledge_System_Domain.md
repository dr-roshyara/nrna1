# Strategic DDD Discovery — Product Knowledge System Domain

| | |
|---|---|
| **Kind** | Strategic Discovery report — evidence document, **not design**. No technology, no databases, no UI, no AI agents, no bounded contexts are proposed here. |
| **Authority** | Generated (AI-produced under Principal Architect commission) — **never authoritative without human review** (ES-001.2 / AIP-10). |
| **Status** | **DISCOVERY — submitted for review. STOP: no strategic model, no reference model, and no architecture may be drawn from this document until it is reviewed and the next phase is explicitly authorized.** |
| **Commission** | Principal Architect, 2026-07-27: *"Perform Strategic Discovery of the Product Knowledge System domain — the governed body of engineering knowledge that describes, justifies, verifies, and evolves a specific product throughout its lifecycle. Discover from repository evidence; do not assume answers; if evidence is insufficient, mark it as an open question."* **Revised same day** to the ten-question structure (Q0 purpose · knowledge ≠ artifact · evolution ≠ lifecycle · authoritative vs informational relationships · contradiction semantics · AI delegation phrasing · completeness criterion). |
| **Evidence base** | Four full-corpus sweeps executed this session over the **product-side** knowledge corpus: (1) `docs/knowledge/` (EKP pilot, 153 files, lint executed live); (2) `docs/adr/` + `docs/architecture/` (475 files); (3) `docs/implementation/` + `docs/plans/` + `docs/handbook/` (~105 files); (4) `developer_guide/` (555 files) + `.claude/` runtime + the product bindings of ES-004/005/006. All quotes verbatim from files; counts measured, not estimated. |
| **Placement note** | Project-side by the ES-005.3 litmus (this document is inseparable from PublicDigit evidence). Precedent and sibling: `Strategic_DDD_Discovery_Engineering_Governance_Domain.md` (same class, same home). The **Engineering Platform's** knowledge domain was discovered separately (`engineering/verification/reports/2026-07-27-knowledge-domain-model.md`); this report covers the **adjacent domain that report scoped out** — the *product's* knowledge (ES-006 header: "Explicitly OUT of scope: Project Knowledge — a separate bounded context; its standards (PKS-class) arrive only after its pilot and qualification"). |
| **Method note** | Statements are labeled **Observed** (quoted), **Measured** (counted), **Derived** (follows from observations, derivation shown), or **Interpreted** (grouping/judgment). "Evidence not found" is used 9 times and is a complete answer. |

---

## 0. Executive Finding

**The product already runs a Product Knowledge System — it just isn't the one it designed.**

Three facts organise everything below:

1. **Two designed knowledge systems exist in the corpus, and both stalled at the same point.** The **AKB** (Architecture Knowledge Base v1.0, 2026-06: L1–L4 levels, lifecycle classes, maturity model, document registry) and the **EKP** (Engineering Knowledge Platform, 2026-07: knowledge cards, 31-type ontology, typed relationships, lint + graph tooling). Both defined vocabulary, lifecycle, and governance; in both, the *rules* are written and the *instances* don't follow them (AKB: the recommended physical reorg was never executed, the handbook is 2 volumes of 11, the maturity model was never re-scored; EKP: 36 governed docs against a self-claimed ~2,500-file corpus, 0 `reviewed_by` entries against 24 `approved` docs, the flagship duplicate-authority lint rule is a structural no-op, CI is warn-only). *(Measured/Observed — §2, §7.)*

2. **The knowledge system that actually works is the practice-based one** — prose status headers, freeze discipline, supersession-never-edit, traceability lines, precedence ladders, the produce → ARB review → refine → freeze loop, append-only logs, evidence-source columns on boards. It has no name, no schema, and no tooling, but it exhibits every property the designed systems specified: identity, lifecycle, authority polarity, evidence discipline, and human-gated promotion. *(Derived from §§3–7 — the operating rules quoted there come overwhelmingly from ungoverned convention, not from the AKB/EKP schemas.)*

3. **The fundamental unit of knowledge is already sub-document.** 23 tactical decisions (ADR-T1…T23) live in one file; 14 implementation decisions (D-01…D-14) in one log; 6 messaging ADRs (ADR-MP-01…06) in one file; 18 engineering rules (ER-nn/EP-nn) inside the process document; invariants (CI-n/BI-n/INV-n), findings (F-…), observations (O-n), responsibilities, and UL terms all carry their own identities and lifecycles *inside* carrier documents. The document is demonstrably a **projection** of finer-grained knowledge concepts — the corpus discovered this by practice before anyone named it. *(Measured — §1.2.)*

**Consequence for the commission's central worry:** the discovery confirms that staying at the artifact level would model the wrong thing. The artifact inventory (§2) and the concept inventory (§1) genuinely differ — in count, in identity scheme, and in lifecycle.

---

## Q0 — Why does this knowledge exist? (Purpose)

Discovered purposes, each with the evidence that the corpus *states or enforces* it (not merely exhibits it):

| # | Purpose | Evidence (Observed) |
|---|---|---|
| P-1 | **Preserve decisions with rationale, permanently** | Decision-log schema: "every implementation decision … records {Decision · Reason · Related ADR · Alternative rejected · Impact}. **No undocumented design drift.**" (`PushB_Decision_Log.md`); "history is never edited in place" (Blueprint) |
| P-2 | **Prevent architectural drift / bind implementation to approved design** | "**No Pull Request may be merged unless every changed class can be traced** to one or more sections of this blueprint, the applicable ADR(s), and a Traceability Matrix row" (Blueprint §binding rule); ER-01 "Architecture Before Implementation" |
| P-3 | **Enable verification and qualification** | ADR-T template carries a per-decision **Conformance test** column; `Implementation_Traceability_Matrix.md`: "The Architecture Review Gate … check this matrix before EVERY merge"; fitness tests derived from tables ("This table is the single source for the no-foreign-consumer fitness test", Blueprint §10) |
| P-4 | **Transfer knowledge / onboard collaborators — explicitly including AI collaborators** | `Architecture_Handover_Release_2.0.md`: "**authoritative starting document for any new session / AI collaborator**", containing a literal §14 "Bootstrap prompt (paste into a fresh session)"; `developer_guide/INDEX.md` by-role navigation; handbook = "Level 2 navigation center" |
| P-5 | **Provide continuity across work sessions** (memory the collaborator does not have) | "At the beginning of every work session: 1. Read MEMORY.md. 2. Read CONTEXT.md…"; session logs named "**reconstruction source**" among practices that "earned their keep" (`EPIC-001_Retrospective.md`); SessionStart hook injects MEMORY → CONTEXT → active plan → today's log |
| P-6 | **Teach contributors how (operational how-to, distinct from decision records)** | Developer-guide DoD: "the ADR records the *decision*; the guide is the developer *how-to*" (`.claude/CLAUDE.md`); audience lines per area index |
| P-7 | **Assemble task-scoped context for AI** | EKP Knowledge Packages: "Reusable AI context is packaged as **Knowledge Packages** (portal/packages/) so the right bundle can be loaded for a task" (Knowledge-Constitution) |
| P-8 | **Carry evidence for governance decisions** (knowledge as input to authority, never as authority) | "implementation → evidence → analysis/recommendation → ARB decision → standard" (`EPIC-001_Retrospective.md`); board rows carry an **Evidence source** column; "certified artifacts beat memory … When memory and evidence disagree, evidence wins" (AKB Principle 01) |
| P-9 | **Resolve disputes / audit the past** | append-only session logs ("paths inside describe the world as it was", ES-004.2); rejected measurements "recorded, not erased" ("the earlier 75%/96% figures … history only", BACKLOG) |

**Derived observation:** P-5 and P-4's AI-collaborator form are purposes that classical documentation taxonomies don't name — a large fraction of this corpus exists specifically because the primary engineering collaborator (an AI) has **no persistent memory**, so the repository *is* the memory. The corpus states this as law: "The repository is the authoritative memory" (`.claude/CLAUDE.md` §General Rules).

**Evidence not found:** compliance/regulatory purpose (no document cites an external regulation or auditor as its consumer — the platform's *product* has audit-trail features, but no knowledge artifact names external compliance as its reason to exist).

---

## Q1 — What knowledge exists? (The fundamental units, beneath the artifacts)

### 1.1 The corpus's own answer to "what is the fundamental unit?"

The EKP states the unit explicitly: identity is `knowledge_id:`, "filename is *not* identity" (`docs/knowledge/schema/knowledge-schema.yaml`). The practice-based system states it by behavior: durable ids (ADR-T5, D-03, ER-06, F-PB006-1, CI-1) are what other documents cite — ES-004.2 makes it a rule: "**Cite durable ids, not dated filenames**, in long-lived documents." *(Observed.)*

### 1.2 Knowledge concepts discovered (with sub-document evidence)

These are the units that carry their own identity, are cited independently, and have lifecycles independent of their carrier document. Status: **OPERATING** (instances exist and are cited) unless noted.

| # | Concept | Identity scheme(s) observed | Where instances live (carrier ≠ concept) | Evidence |
|---|---|---|---|---|
| K-1 | **Decision** | `ADR-Tn` (23) · `ADR-MP-nn` (6) · `D-nn` (14) · `ADR-nnn` (5 colliding series) · `ADR-M-nnn` · `DD-n` (per-IDD) | one ADR-T register file; one decision log; one ADR-MP file; scattered ADR files; Round-37 "ADR-1 of 7" series | sweep 2 §1.1–1.2; the register/log pattern proves N decisions : 1 document |
| K-2 | **Rule** | `ER-nn` / `EP-nn` (process) · `ES-00n.m` (platform-hosted, product-binding) · PGP-nn · TP-n | inside `Implementation_Process_v1.x`; standards host/register | "engineering rules and process gates are process, and live **once** — here. Other documents *reference* them" (v1.1 draft) |
| K-3 | **Invariant** | `CI-n`/`BI-n` (Blueprint) · `INV-n`/`INV-B1` (EPIC-004E) · `CL-n` (EPIC-003) · 38C01-INV-01 | inside blueprints, chain artifacts, charters | sweep 2 §1.4; sweep 3 §1.11–1.12 |
| K-4 | **Ruling** (governance act smaller than an ADR) | per-ruling docs (Round38C-15) or register rows (`R-nn` platform series); ARB decision columns in retrospectives | ~40+ ARB-titled documents; decision columns inside retrospectives | sweep 2 §1.3; "only the ARB column carries decisions" |
| K-5 | **Finding** | `F-…` — **at least six run-scoped id spaces** (F-1/2, F-PB006-n, F-7C-n, F-7D-n, F-GATE-n, F-KDC-1, GEP-F1, RR-Fn) | inside discovery docs, audits, IDDs, constitutions | "Findings (F-series) are internal entities, **id-scoped to the run**" |
| K-6 | **Question** (open, owned, routed) | `OQ-n` (per-doc open questions) · `Q-1/Q-2/Q-3` (EPIC-004) · `D35/D36/D37` (governance open decisions) · RQ-register entries | event stormings, work packages, registers | Q-1/Q-2 have their own resolution documents — a question's lifecycle outlives its carrier |
| K-7 | **Term** (ubiquitous-language entry) | term → definition; changes via `ADR-UL-nn` | vocabulary dictionaries (Round46-VOCAB); ADR-UL-01 | ER-06: "an ADR-UL precedes the ADR-PL it drives" — a *term change* is a first-class governed event |
| K-8 | **Event contract** | catalog row + `SchemaVersion` | Canonical Event Catalog (12 rows, FROZEN); Round50-05 payload contracts | "never edit a frozen row's meaning"; rows version independently of the catalog document |
| K-9 | **Model element** (aggregate, VO, responsibility, state machine, policy) | named per element; responsibilities `R-n`, criteria numbered | the EPIC-004B–J chain: each artifact freezes a *set of elements*, and later artifacts cite individual elements ("the eight invariants are the binding input to artifact №5") | sweep 3 §2.5 |
| K-10 | **Observation / evidence entry** | `O-n` (retrospective input) · register rows · FACT/INTERPRETATION/RECOMMENDATION/OPEN-QUESTION labels | input packs, hypothesis/assumption registers (statuses per-entry: "Strengthening", "Validated by ARB") | sweep 2 §3.2; sweep 3 §1.9 |
| K-11 | **Risk** | `R-1..R-7` (EPIC-003 register) · residual risks per report | risk registers inside assessments | sweep 3 §1.11 |
| K-12 | **Candidate** (promotion-ladder resident: pattern, principle, practice) | `P-n` (promotion candidates) · "Candidate principle (recorded, not adopted)" | retrospective inputs; dossiers | "One successful experiment ≠ candidacy" |
| K-13 | **Work item** | `PB-nnn` · `EPIC-nnn` · `ENG-nnn` · `AD-nnn` · WBS items | boards; epic files | lifecycle ≠ progress rule applies to these only |
| K-14 | **Guide step** | numbered file per area (`01_step_…`) | 6 areas follow the convention; 25 do not | sweep 4 §1.1 |
| K-15 | **Verdict / gate outcome** | per-run (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT); CERTIFIED | readiness reports, qualification records | sweep 3 §1.13 |
| K-16 | **Exception / deviation record** | keyed by file+element (design-system exceptions); "Recorded deviation" blocks | `design-system.exceptions.json` (with approvers, expiry, status active/permanent); EP-02 deviation records | sweep 4 §1.8; CONTEXT step records |
| K-17 | **Purpose/charter grant** (authorization) | per-charter; "construction permit, not a design" | charters, work packages, authorization-chain headers | "**Authorization chain:** Strategic Baseline accepted … Tactical DDD itself remains unauthorized" |

**Assessed and NOT confirmed as first-class product-side concepts** *(Interpreted, evidence basis stated)*:

- **Requirement** — Evidence not found as an identified unit. The operating forms are *invariant* (K-3), *acceptance criterion* (IDD §14), and *conformance test* (ADR-T column). No `REQ-nn` scheme exists anywhere in the product corpus.
- **Constraint** (as distinct from Rule/Invariant) — the corpus does not separate them; "Constraints that bite" (MEMORY) is a hint list pointing at rules and ADRs.
- **Runbook** — `knowledge_type: runbook` is declared in the EKP ontology with **zero instances**; no operational runbook exists product-side.
- **Context Package** — exists (2 instances, EKP) but as an *assembly of references*, i.e. a projection over concepts, not a concept.

### 1.3 The concept/artifact ratio *(Measured — the decisive evidence for the commission's second question)*

| Carrier document | Concepts inside it |
|---|---|
| `ADR-T-LOG-Tactical-Implementation.md` (1 file) | 23 decisions, each independently cited, individually superseded (T17 by T23), individually deferred (T13), individually gated (T21/T22 "Implementation NOT yet authorized") |
| `PushB_Decision_Log.md` (1 file) | 14 decisions; D-12 was individually **split out** into 6 ADRs, leaving "a pointer" behind |
| `Implementation_Process_v1.0/v1.1` (2 files) | ~18 rules + 2 gate definitions + 1 lifecycle vocabulary + the 14-box DoD |
| `PushB_Architecture_Blueprint.md` (1 file) | 21 sections, a gap register (G-1..5), 4+2 invariants, a decision-authority table, a gate record |
| `EPIC-001_Retrospective.md` (1 file) | 10 candidate dispositions, each with its own ARB decision status |

An identical unit count and file count never coincide anywhere in the governed corpus. **The document is a serialization; the knowledge concepts are the domain.** *(Derived.)*

---

## Q2 — What artifacts represent that knowledge? (The projections)

### 2.1 Artifact-kind inventory *(Measured/Observed; ~40 kinds, grouped)*

**A. Decision carriers** — ADR (in ≥5 filename schemes and ≥4 colliding numbering spaces across three roots), decision log, ARB ruling (judicial format per frozen template 38C-16), ARB decision record (incl. one blank unfilled template at `docs/architecture/ARB_Decision_Record.md`), decision paper (Plan Concept — HISTORICAL; Placement Rule — DRAFT), disposition register (19 rows, six-valued disposition vocabulary), Q-resolution records.

**B. Normative specifications** — architecture blueprint (FROZEN v1.0), event catalog (FROZEN), state machines (FROZEN v1.2, "Last tactical design doc — implementation proceeds from here"), implementation constitution + coding standard + package conventions (all FROZEN v1.0), process document (FROZEN v1.0 + DRAFT v1.1), governance charters and bindings (`DDD_PRINCIPLES.md` — "a binding only … contains no rule text"), platform principles (PGP, FROZEN), pattern documents (PROVISIONALLY STABLE).

**C. Discovery & design records** — 383 files in `design/` + `discovery/` + `contexts/` under "Round nn" naming (Round 5–50, with sub-schemes `Round38C-P2-02H`, `-GCD-`, `-SYN-`); registers and catalogs (boundary decisions, hypotheses, assumptions, research questions, uncertainty, discovery debt); EPIC-002/003/004 chains (problem statement → literature → context map → entry assessment → 9-artifact tactical chain); event stormings; a ~17-file second-model "`-deepseek`" shadow-review corpus.

**D. Work/process artifacts** — backlog, epic files, tickets, PROGRESS/WBS records, IDDs (5 + a frozen 17-section template), discovery-findings documents, retrospectives + input packs, program status one-pagers, development log, implementation baseline, engineering plans (2 governed + 7 legacy), runtime work plans (8, three naming generations, incl. one provider-random-named counter-instance of the corpus's own naming rule).

**E. Verification/evidence artifacts** — readiness/certification reports (ARR, "CERTIFIED"), governance audits (F-GATE findings, severity+priority), traceability matrix, verification matrices, qualification records (platform-side OQ-ENG, cited product-side).

**F. Teaching artifacts** — developer guides (**555 files, 31 areas; only 6 areas follow the governed numbered convention**), guide indexes (two competing classes: `00_index.md` vs 13 `INDEX.md`/`MASTER_INDEX.md`), handbook (2 of 11 planned volumes, orphaned — its pipeline diagram still says "Strategic DDD ← GATED (not started)"), handover documents, FAQ/knowledge-transfer folders.

**G. Views** — C4 documents + 13 `.puml` (status legend IMPLEMENTED/OPERATIONAL/PLANNED/DESIGNED; "documentation only … the artifact wins and the diagram is corrected"), context maps, knowledge graph (generated, 38 nodes/69 edges), portal indexes and hubs ("the hub connects them; the files stay where they are").

**H. Runtime knowledge artifacts** — CONTEXT.md (hook-parsed structured block; supersedes-in-file with `<!--superseded-->` markers), MEMORY.md ("hints only; the ES documents are the truth"), session logs (11, append-only, violation precedent recorded), hooks/scripts (7 — two of which are *knowledge-system enforcement*: dev-guide-reminder, discipline-gate), `design-system.exceptions.json`.

**I. Designed-knowledge-system artifacts** — the AKB family (portal, base, certified landscape, constitution-handbook) and the EKP family (constitution, schemas, cards, packages, hubs, recipes, lint+graph tooling, archive).

### 2.2 Where the projections diverge from the concepts *(Derived — the mismatch table)*

| Mismatch | Evidence |
|---|---|
| **Identity lives at the wrong level** for C/F/G families: guides, rounds, views, reports identify by filename/date only — while the concepts they carry (decisions, invariants, findings) have durable ids. The ids-not-filenames rule exists but binds only citations, not the artifacts themselves | ES-004.2; sweep 2 §6.3 |
| **One concept, many carriers, no declared canonical serialization**: three distinct decisions are each called "ADR-004"/"ADR-005"/"ADR-001" in three folders; the ADR index that claims to be "the one place that lists every ADR" lists 6 of 10 files in its own folder | sweep 1 §6e(9); sweep 2 §6.3(3) |
| **Frozen carrier, superseded content**: the FROZEN Blueprint still encodes ADR-T17's position after ADR-T23 superseded it — the carrier's lifecycle (frozen) and the concept's lifecycle (superseded) disagree, and the corpus has no mechanism that reconciles them short of a versioned re-issue | sweep 2 §6.4 |
| **Three roots, no unified index**: the product knowledge corpus spans `docs/`, `architecture/` (outside docs/), and `engineering/` bindings; "no single index covering all three" | sweep 2 §6.5 |
| **Corpus scale vs governed scale**: self-claimed ~2,500 markdown files; 36 carry knowledge cards (~1.4%) | EKP `GUIDE-EKP:26`, `portal/INDEX.md:18` |

---

## Q3 — What relationships exist? (with the authoritative/informational polarity)

### 3.1 Relationship vocabulary discovered *(Observed; source in parentheses)*

**Authoritative relationships** — they change what is binding; violating them is a governance violation:

| Relationship | Semantics as practiced | Evidence |
|---|---|---|
| `supersedes / superseded-by` | replaces while preserving history; "remains valid history; never edited" | ADR-T23→T17; handover 2.0→1.x; 50-07 v1.2→v1.0; VOID-before-issuance variant (38C-14A) |
| `drives / driven-by` (ordered derivation) | **ER-06**: ADR-UL precedes the ADR-PL it drives; ADR-PL precedes the contract version; contract precedes implementation — a binding *ordering* between knowledge kinds | `docs/adr/README.md:18`; ADR-UL-01/ADR-PL-01 headers |
| `frozen-input-to` | a frozen artifact is the immutable premise of its successor; entry conditions verify the link | EPIC-004 chain: "treating every frozen artifact as immutable input"; "every proposed Value Object must trace back to one or more frozen business invariants" |
| `authorizes` | a charter/gate grant permits the next activity and nothing else | authorization-chain headers; "authorizes *planning* an architecture, not the architecture itself" |
| `binds` (platform→project) | adoption without fork; "may be stricter, never looser" | DDD_PRINCIPLES.md; KnowledgeOS constitution lineage annex |
| `precedes in conflict` (precedence) | higher wins; "the lower one is corrected (living) or superseded (permanent)" | the 7-level ladder (Implementation Constitution §"Document precedence"); the c4 10-step "Authority order"; "ADR > IDD > Developer Guide > logs/records" |
| `is-recorded-by` | a Human decision exists only when a record captures it explicitly per item | "documents record governance; decisions create it"; "the explicit authorization is requested per item and recorded when given — not inferred from the signal" |
| `rejects` (dependency guard) | "a new ADR that contradicts an upstream node (esp. T11 anonymity or T1 one-txn) **is rejected**" — the ADR dependency graph is a *constraint*, not a picture | ADR-T log Mermaid graph + Rule |

**Informational relationships** — they aid navigation/understanding; breaking them degrades findability, not validity:

| Relationship | Semantics as practiced | Evidence |
|---|---|---|
| `traces-to` | lineage lines listing every upstream source; mandatory in guides (DoD), IDDs, 2026-07 artifacts | 22 guide files carry Traceability; "Every implemented class cites its lineage" (AKB chain) |
| `references / related_to` | navigation; the EKP's only heavily-used typed field (30+ docs) | EKP frontmatter |
| `describes / documents` | views and guides describe; "nothing may cite a view as authority" | c4 README; hub self-description: "authority: derived — it points at sources, it is not itself the source of truth" |
| `points-to` (pointer/binding text) | a section that names a rule's canonical home carries **no rule text** | "POINTER, not a restatement" headers; "MEMORY carries hints; the ES documents are the truth" |
| `evidences` | board rows and claims cite their evidence source | Evidence-source columns; ER-02 |
| `includes` (package assembly) | ordered list of ids to load for a task | EKP packages (note: `includes` is used by tooling but undeclared in the relationship schema) |

**Derived polarity rule** *(the corpus practices it without stating it once)*: **every authoritative relationship is created or changed only at a human decision event; every informational relationship may be written by the author.** No counterexample found: supersessions cite ADR/ARB acts; traceability lines are authored freely.

### 3.2 The machine-readable inversion *(Measured — a load-bearing discovery)*

Where relationships are richest in *meaning* (supersession, drives, frozen-input, precedence) they exist only as **prose**; where they are machine-readable (EKP's 11 typed fields with declared inverses and direction), they are barely used — `supersedes`: **0 instances**; `depends_on`: **0**; `reviewed_by`: **0**; `implements`: declared 3×, always empty; the most-used field is the weakest (`related_to`). The EKP graph even emits an edge type (`includes`) its own schema doesn't declare, and ignores the declared `directed:`/`inverse:` semantics. **The designed relationship system and the practiced relationship system are disjoint.** *(Sweep 1 §2.)*

---

## Q4 — How does knowledge evolve? (Operations, distinct from lifecycle states)

Evolution operations discovered, each with its governing rule and precedent:

| Operation | Rule as practiced | Precedent (Observed) |
|---|---|---|
| **Supersede** | never edit in place; successor cites predecessor; predecessor keeps standing as history | ADR-T23/T17; Plan Concept paper → "HISTORICAL — SUPERSEDED BY the Engineering Standards" |
| **Version (re-issue)** | frozen artifact changes only as v_{n+1}; "freeze follows incorporation" (conditions folded before freeze, never patched after) | Blueprint v1.0→v1.1 rule; D-07 |
| **Amend additively (append-only delta)** | the old table is untouched; the delta is dated and appended | "BDR v1.1 delta … *(append-only; v1.0 table unchanged)*" |
| **Split** | one carrier's concept extracted into first-class records, a pointer left behind | D-12 → ADR-MP-01…05 ("now a pointer"); BACKLOG split ("scales to 100+ tickets") |
| **Consolidate** | scattered restatements collapsed into one canonical home + pointers | STANDARDS_INDEX consolidation ("the consolidation obeys the rule it enforces"); "Bindings reconciled 2026-07-11 … the convention lives once, here" |
| **Refine (the dominant loop)** | produce → review → fold corrections **visibly** (correction blocks stay in the artifact) → freeze | EPIC-004B: "every such claim is corrected to 'candidate'"; recorded review scores 7.8 → 8.2 → 9.6/10 |
| **Promote** | up an evidence ladder, one human decision per rung, never by momentum | "observation → repeated observation → practice → candidate standard → approved standard"; work plan → engineering plan at EP-01 approval |
| **Demote / void / reject-with-record** | a claim found unsupported is re-labeled, never deleted; rejected measurements stay visible as history | 38C-14A "[VOID — OUT OF SEQUENCE] … Do not cite this document as program guidance"; Round40-GDR-01 re-classification of over-claims; "8-thread figures REJECTED — history only" |
| **Reclassify** | the object kind, not the content, changes — by explicit decision | Q-3 "REJECTED as an ARB question — reclassified Tactical Collaboration concern"; disposition "Reclassify (RR-F4 accepted)" |
| **Retire** | explicit act, with successor named or gap accepted | retrospective D-1/D-3 "CONFIRMED — RETIRED"; D-2 "OVERRIDE — RETIRE AST-008 NOW" |
| **Archive** | move, never delete; history preserved via `git mv` | EKP archive rule; 106 archived files |
| **Migrate/relocate** | placement changes are decisions, recorded, with bindings updated | EM-001 namespace migration plan; "recorded here so the move is a decision, not drift" |
| **Generalize** | a rule proven in one scope is promoted to permanent governance, explicitly | "Methodological Fitness Rule (ARB, **generalized** — permanent governance)"; "the R-17 discipline, generalized" |
| **Derive (regenerate)** | derived artifacts are recomputed from sources, never hand-edited to disagree | derived percentages ("NEVER written by hand"); the knowledge graph; views corrected to match artifacts |

**Evolution operations with NO evidence** *(checked for, not found)*: **merge** (two knowledge objects combined — the corpus splits and consolidates text, but no two identified objects were ever merged under a rule); **expiry/aging** (nothing ages out by time — the sole time-bounded mechanism anywhere is `design-system.exceptions.json`'s `max_duration_days: 90`, and its records carry `expires: null, status: "permanent"`); **automatic invalidation** (no mechanism marks dependents stale when an upstream object changes — see Q7 contradictions).

**The split criterion exists exactly once** *(Observed)*: the first-class-element test — parts split only when they stop sharing "one lifecycle, one owner, and one revision process," and "accumulation documents split at the retrospective, never before" (KnowledgeOS constitution header, citing the dossier precedent).

---

## Q5 — What lifecycle exists? (Governance states)

### 5.1 Declared lifecycle vocabularies *(Measured: at least EIGHT independent ones)*

| # | Vocabulary | States | Home |
|---|---|---|---|
| L-1 | EKP document status | draft → discovery → reviewed → approved → baseline → frozen (→ superseded → archived); 8 enum values | `schema/statuses.yaml` |
| L-2 | AKB lifecycle classes | Immutable · Frozen · Living · Generated · Historical | `Architecture_Knowledge_Base_v1.0.md` |
| L-3 | ADR lifecycle | PROPOSED → UNDER REVIEW → APPROVED / REJECTED / SUPERSEDED (+ who-sets-it column) | Round32C addendum |
| L-4 | ADR-M dual axes | record status (Draft·Accepted·Controlled·Superseded·Retired) ⊥ methodology maturity (Observed→…→General Principle) — "do not conflate" | Round39-D6 |
| L-5 | Ticket lifecycle | Designed → Approved → In Development → Implemented → Verified → Released (+ Blocked flag) | Process v1.0 |
| L-6 | Knowledge release | Draft → Candidate → Certified → Published → Superseded → Deprecated → Archived (+ SemVer) | Round46-KRG |
| L-7 | Research-question states | Open · Evidence insufficient · Supported · Partially supported · Narrowed · Rejected · Superseded | Round40-07 register |
| L-8 | Traceability maturity | Designed → Approved → Implemented → Verified → Production ("replaces binary status") | Traceability Matrix |

Plus **~60 ad-hoc status tokens** observed in headers that belong to no declared vocabulary (from `SEALED — pre-validation` to `PROPOSED — INERT` to `HOLD — stays armed` to `Exploratory. Boundaries are candidates, not conclusions.`). *(Sweep 2 §3.2, sweep 3 §3.2.)*

### 5.2 The lifecycle principles the corpus itself discovered *(Observed — these outrank any one vocabulary)*

1. **Lifecycle ⊥ progress** — "two different concepts — never conflated"; percentages always derived (Process v1.0 §Lifecycle vs Progress).
2. **Status ⊥ authority** — "Authority … is INDEPENDENT of status. e.g. status: approved + authority: generated is valid" (EKP `authorities.yaml`); generalized to **four orthogonal axes**: "authority ⊥ status ⊥ maturity ⊥ adoption" (disposition register row 8, rejecting a single-chain roadmap as "a regression").
3. **Completion is determined by the reviewer, never the author** — "its completion is determined by that review, never asserted by its authors"; "'Status: Adopted' was corrected to PROPOSED per AIP-10 — adoption is the ARB's act, not the author's."
4. **Terminal states are supersession-shaped, not deletion-shaped** — archive/void/historical always preserve the record.
5. **Frozen ≠ final** — "frozen ≠ final; controlled & governed … Change only via ADR-M + version bump — never silently."

### 5.3 Defined-vs-used divergence *(Measured)*

The designed vocabularies are honored mostly in the breach: EKP — 4 of 8 statuses never used, **no document has ever occupied `reviewed`** (the state the quality gates route through), `idea`/`research` appear in the lifecycle prose but would be lint errors; three mutually inconsistent lifecycle diagrams across the EKP's own three governing documents; 105 of 105 archived files lack the `status: archived, authority: historical` markers the archive rule mandates. The practiced system meanwhile applies its states rigorously but has no closed vocabulary at all. *(Sweep 1 §3, §6e.)*

---

## Q6 — Who owns knowledge? (Authority boundaries)

### 6.1 The roles observed *(Observed)*

**Human decision roles:** Chief Architect (froze the process, blueprint gate) · ARB / ARB Chair (the dominant authority — acceptance, freeze, rulings, promotions) · multi-role panels (Chair + Senior DDD Architect + Senior Online Voting Security Architect) · Decision Authority (the platform-side name, used product-side since 2026-07-26) · Sponsor/Founder/Constitutional Owner (constitutional rulings: 38C-14C) · Principal Architect (commissions) · "Deciders" (early ADRs: "Domain Architecture Team").

**Designed-but-unstaffed roles (EKP):** Knowledge Manager (1 person, backup "(to assign)") · Knowledge/Release Manager (used as if distinct, defined nowhere) · Governance Board (never mapped to a person) · Peer reviewer (`reviewed_by`: 0 instances) · domain owners (**8 of 10 slots "(to assign)"**; all 37 carded docs carry the same single `owner:` value, against the constitution's "ownership is by domain expertise, not by person-wide decree").

**The AI's role:** producer, recorder, analyst, instrument — with role separation stated as law product-side: "the AI is the engineer (plans, implements, reports) · an independent reviewer may assess the plan · **the human ARB approves**" (Process v1.1); "this artifact **recommends** aggregate selection. **The ARB decides.**"; "gates are DA-owned"; "the assistant prepares, never advocates."

### 6.2 Per-activity ownership *(Derived from §6.1 + Q4/Q5 evidence)*

| Activity | Owner as practiced | Evidence |
|---|---|---|
| Create/draft | AI or engineer, freely — output enters as generated/draft/proposed | "Authority: generated … not authoritative until ARB ratification" (the two EPIC-002 drafts — the only two files in docs/ with that exact header form) |
| Review | ARB (scored reviews, refinement instructions); a second AI model as shadow reviewer (the `-deepseek` corpus) — with no recorded authority | sweep 3 §4.4; sweep 2 §1.7 |
| Approve/freeze/promote | humans only, per-item, explicitly | "continue/looks good/go ahead ≠ Approved/Promoted/Retired/Closed" |
| Own after approval | **Evidence not found as a named assignment.** Frozen artifacts have change-*procedures* but no named custodian; the EKP owner model exists but is unstaffed | sweep 1 §4b |
| Retire/archive | explicit ARB/DA act (retrospective dispositions; Knowledge Manager on paper) | EPIC-001 retrospective D-1..D-3 |
| Keep consistent across artifacts | **Evidence not found.** No role owns cross-artifact consistency; drift is caught by ad-hoc audits and one-time syncs ("boards were 4 days / 5 ticket-closures stale before the one-time sync") | sweep 3 §6.4 |

---

## Q7 — What makes knowledge authoritative? (Governance, contradictions, AI delegation)

### 7.1 Authority establishment *(Observed)*

- **The gate is human acceptance:** "all outputs remain hypotheses until ARB acceptance" (38C01-INV-01); "No PROPOSED ADR may be treated as binding."
- **The transition is explicit and located:** "*Upon ARB acceptance of this Canonical Context Map, the previously approved Candidate Domain Boundaries are ratified…* Before that acceptance they remain candidates; **the transition happens at acceptance, unmistakably, and nowhere else.**"
- **Authority is rank-ordered on conflict** — three precedence statements exist (the Implementation Constitution's 7-level ladder; the c4 README's 10-step authority order; the v1.1 draft's "ADR > IDD > Developer Guide > logs/records"), plus two meta-rules: evidence beats memory; frozen beats descriptive.
- **Authority is revocable only forward:** supersession, versioned re-issue, or VOID-with-record; one recorded irrevocability ("the ruling itself may not be revisited", 38C-15); **no in-place revocation exists anywhere.**
- **Evidence requirements:** ER-02 (never infer intent; absence of evidence ≠ evidence of intent); findings invalid without evidence refs; "an untraceable responsibility is rejected or deferred"; removal tests as falsification probes; "a well-evidenced 'no change' is a success outcome."

### 7.2 What constitutes a contradiction, and how it is detected/resolved *(Observed + Derived)*

The corpus operates a **conflict taxonomy in practice**:

| Contradiction class | Resolution rule as practiced | Precedent |
|---|---|---|
| Descriptive vs normative | the artifact wins; the description is corrected | "If a diagram and a frozen artifact ever disagree, the artifact wins and the diagram is corrected" |
| Lower-rank vs higher-rank normative | higher wins; lower is "corrected (living) or superseded (permanent)" | v1.1 authority hierarchy |
| Memory vs repository | repo wins | MEMORY/INDEX redirect rules; "ARB documents are authoritative over memory artifacts" |
| Record vs record (same rank) | **detected by reading, resolved by a correction record or a re-classification document** — a whole document may exist to correct another (Round40-GDR-01), or a self-audit section corrects the doc's own claims ("'~32 bounded contexts' — not supported by certified artifacts") | sweep 2 §5.3 |
| Frozen carrier vs superseded concept | **detected but deliberately deferred**: "Record and correct via versioned re-issue + ADR after the PB-005 IDD, never during" | event-catalog inconsistencies; Blueprint/ADR-T17 |
| Found-but-out-of-scope | reported, not resolved ("Inconsistencies found (reported, NOT resolved here — prompt rule)") | c4 README |

**Detection today is entirely human/AI reading.** *(Derived, with negative evidence:)* the only machine contradiction-detector designed for the product side is the EKP linter's `single_authoritative` rule — which is a structural **no-op** (gated on a `topic:` field no document has), and its `frozen_changed_without_adr` rule is declared but **not implemented**. CI runs the lint warn-only. The corpus's documented failure mode is exactly what unmonitored contradiction produces: stale boards ("Implementation outran governance records"), an orphaned handbook contradicting the program state, a "single canonical" ADR index missing 4 of 10 files, and a count mismatch inside a FROZEN catalog (12 rows, "11 canonical events"). **Known-open contradictions are themselves catalogued knowledge** — the corpus keeps registers of its own unresolved conflicts (UL collisions "⚠️ COLLISION — open"; naming drift "known").

### 7.3 Which knowledge activities may be delegated to AI, and which must remain under human authority *(Observed — the corpus answers this explicitly)*

| Delegated to AI (evidenced) | Reserved to humans (evidenced) |
|---|---|
| Draft any artifact class (ADR drafts, IDDs, discovery, plans, retrospective inputs, guides) | Approve, ratify, freeze, adopt, promote, certify, close, authorize ("You never: approve governance · ratify architecture · authorize implementation · promote knowledge") |
| Execute discovery and qualification instruments; gather evidence; run audits ("Role: Chief Engineer — evidence-gathering and root-cause analysis ONLY") | Pass gates ("gates are DA-owned"); rule on findings; disposition registers ("dispositions are DA decisions") |
| Record decisions verbatim after they occur ("generated to capture and justify the ARB's decision; the decision itself belongs to the ARB") | Make the decision; per-item explicit acts ("Approve/Reject/Defer required") |
| Maintain traceability, indexes, session logs, context snapshots, derived numbers | Determine completion ("determined by that review, never asserted by its authors") |
| Recommend, score-ready analysis, propose classifications | Advocate for adoption (forbidden: "the assistant prepares, never advocates") |
| Second-model shadow review (deepseek corpus exists) | Grant the shadow review any authority (none recorded — the shadow corpus carries mostly no status headers) |

The boundary rule in one sentence, already law: **"Authors propose; the authority adopts"** (ES-001.2) — with the enforcement precedent that even the AI's *filename or status claims* are corrected when they assert unoccurred adoption (AIP-10 corrections recorded twice).

---

## Q8 — What constitutes a complete Product Knowledge System? (Readiness)

**Per-unit completeness is richly defined; whole-system completeness is not defined at all.** *(Derived; the strongest asymmetry in the domain.)*

**What exists (Observed):**
- Per **ticket**: the 14-box DoD — five of the fourteen boxes are *knowledge* obligations (traceability updated · decision log updated · progress derived · development log updated · guide via the DoD standing rule): "No subjectivity, no partial credit."
- Per **slice**: the EPIC-004 roadmap's per-slice DoD (dev guide committed · conformance recorded · "no deviation from a frozen ADR … without a recorded ARB decision — objectively verifiable").
- Per **session**: "Never finish a work session without updating these files" (plan, CONTEXT, MEMORY, session log).
- Per **capability**: certification — "PB-003 is 'Verified' only when verification passes AND certification answers all 7 reuse questions affirmatively" ('all tests green' ≠ 'reusable capability').
- Per **document** (EKP): 8 objective quality gates for `approved` — "'Approved' is objective, not a feeling."

**What does not exist (Evidence not found, checked in all four sweeps):**
- Any criterion for "this product's knowledge system is sufficient to support engineering." The nearest artifacts are: the AKB's 0–10 maturity model (scored once, "~10–15%", never re-scored); the handbook's 11-volume plan (2 exist); the EKP's "Iteration 1" self-label; and the readiness-audit *concept* (named in the AKB traceability chain, no product-side instance).
- Any freshness/coverage obligation: nothing requires that a bounded context has a guide area, that a frozen artifact's dependents are re-verified after supersession, or that boards match reality on any cadence (the two synchronizations that happened are both labeled "one-time").
- Any completeness measurement that survived contact: the corpus's only whole-system health checks were reactive audits, and the most recent one found the governance-gate machinery itself "NOT currently trustworthy … five of six gates passed for the wrong reasons."

**Derived observation for the next phase:** the practiced definition of done is *event-triggered* (a ticket closes, a session ends) — never *state-assessed* (is the knowledge system currently whole?). If the commission's instinct is right that completeness "could become the heart of the domain," the evidence says it would be the domain's first genuinely **new** concept: everything else in Q0–Q7 already operates; Q8 operates nowhere.

---

## Q9 — Open Questions (cannot be answered from repository evidence)

| # | Open question | Why the evidence is insufficient |
|---|---|---|
| OQ-PKS-1 | **What is the identity scheme for the fundamental knowledge units product-side?** | Sub-document ids exist but are run-scoped and collide (six `F-` spaces, four `D-` spaces, five ADR numbering spaces, `OQ-` overloaded, `EPIC-003/004` meaning two things). No rule says whether identity is global or scoped — practice does both |
| OQ-PKS-2 | **Which of the two designed systems (AKB, EKP) — if either — is the incumbent to evolve?** | ES-006 records EKP as "disposition PENDING ARB (metadata model aligned; consumption model falsified by E-1)"; the AKB was never formally superseded; both are cited as current by different documents |
| OQ-PKS-3 | **Who owns cross-artifact consistency, and on what cadence is contradiction detection run?** | No role is named; both executed synchronizations were "one-time"; the EKP's quarterly knowledge audit has no recorded execution |
| OQ-PKS-4 | **Is the authoritative/informational relationship polarity (derived in Q3) a rule?** | It has no counterexample but also no statement — adopting it would be a decision, not a discovery |
| OQ-PKS-5 | **What is the canonical set of evolution operations, and are merge/expiry deliberately absent or merely unneeded so far?** | Q4's operations are reconstructed from precedents; nothing names the set; the two absences have no recorded rationale |
| OQ-PKS-6 | **What does whole-PKS completeness/readiness mean?** | Q8: no criterion exists; candidate ingredients exist (coverage, freshness, contradiction count, staffing) but selecting among them is design, which this phase may not do |
| OQ-PKS-7 | **How do the three roots (`docs/`, `architecture/`, `engineering/` bindings) relate in the PKS domain — one corpus or three?** | The corpus spans them with no unified index; ES-005.1 separates concerns but predates several inhabitants |
| OQ-PKS-8 | **What is the successor class for `developer_issues/`?** (deleted in the working tree, uncommitted, no decision recorded) | No document records the deletion or names a successor; the plausible successor (Finding + step guide) is nowhere stated as a decision |
| OQ-PKS-9 | **Which lifecycle vocabulary (of the eight) governs which object kind?** | Each vocabulary claims its own scope; nothing maps object kind → vocabulary; the four-orthogonal-axes principle exists but only one register applies it |
| OQ-PKS-10 | **What authority, if any, do second-model (shadow) reviews carry?** | The `-deepseek` corpus exists beside ARB reviews with no status headers and no recorded standing |
| OQ-PKS-11 | **Is "Qualification" one concept or two?** | The corpus's own UL audit flags it: "(a) the verification activity/instrument; (b) a promotion-ladder stage … Candidate for a future UL ruling — not resolved here" |
| OQ-PKS-12 | **Are the ungoverned advisory transcripts inside governed folders** (three chat-shaped files in `docs/implementation/`, six in `developer_guide/ai_platform/`) **inputs awaiting harvest, or debris?** | One (the Knowledge Governance Roadmap) already has a DA disposition ("Reclassify … execution pending"); the others have none |

---

## Constraint Check

No technology proposed · no database proposed · no UI proposed · no AI agents designed · no bounded contexts drawn · no relocations performed ("this report moves nothing") · no governance created — every rule quoted above already exists and keeps its canonical home; the two derived rules (relationship polarity, event-triggered-vs-state-assessed completeness) are labeled Derived and routed to Q9 as decisions, not adopted · "Evidence not found" stated 9 times · all counts measured this session (four sweep reports archived in the session scratchpad; key figures re-verifiable by the greps cited).

---

*Traceability: Principal Architect Phase-1 commission + same-day Q0–Q9 revision (2026-07-27) · evidence: four full-corpus sweeps this session (EKP · architecture/ADR · implementation/process · guides/runtime/standards-bindings) · companions: `engineering/verification/reports/2026-07-27-knowledge-domain-model.md` (the platform-side twin) · precedent for class and placement: `Strategic_DDD_Discovery_Engineering_Governance_Domain.md` · next phase per the commission's summary table: review of this discovery, then Strategic Modeling — neither is begun here. **STOP — submitted for review.***
