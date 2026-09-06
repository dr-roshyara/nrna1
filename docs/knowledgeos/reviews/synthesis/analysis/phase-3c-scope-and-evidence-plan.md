# PHASE 3C · Scope & Evidence Plan — Repository Conformance against Canonical Architecture v0.2

**Status: PLAN — PRESENTED FOR REVIEW. Conformance testing has NOT begun.**
**Authority: HPA ruling of 2026-08-28 (ledger GN-21; HPA-labeled "GN-20" — see governance-notes for
the numbering-collision note). The ruling authorizes 3C PLANNING ONLY; execution awaits review of
this plan.**

---

## 1 · The conformance question

> Does the actual KnowledgeOS repository conform to the authorized canonical architecture v0.2
> (`model/canonical-architecture-v0.2.md`)?

3C **tests** v0.2 against repository artifacts. Binding constraints from the ruling:

1. **3C may not modify v0.2**, repair findings, or silently revise the architecture.
2. Every tested relation/artifact receives **exactly one** verdict:
   `CONFORMANT · PARTIALLY CONFORMANT · NON-CONFORMANT · NOT ESTABLISHED · OUT OF SCOPE`.
3. **No silent upgrade:** NOT ESTABLISHED never becomes CONFORMANT through interpretation or
   architectural preference.
4. Discrepancies produce **findings**, never repairs. Whether a discrepancy is an implementation
   defect, an architecture problem, an evidence gap, or a scope problem is a *subsequent governance
   decision*; 3C may at most propose a classification, marked ⟦INT⟧.
5. `brainstorming/` is **not silently absorbed** — it remains the Brainstorming Archaeology target.
6. Open questions carried by v0.2 stay open unless 3C produces sufficient evidence to change their
   status (change of status = recorded finding with evidence, never assumed).

## 2 · Scope classification

### 2.1 IN SCOPE — Tier 1: authoritative KnowledgeOS specification & governance record
*(These artifacts claim current authority over what KnowledgeOS IS; primary conformance targets.)*

| Artifact set | Why in scope |
|---|---|
| `docs/knowledgeos/architecture/` (31 md + puml) — esp. `20260822-0951-KOS-EP01-Constitution-v1.0.md`, `20260822-0955-KOS-EP01-Reference-Architecture-v1.0.md`, `20260822-0939-KOS-EP01-Step5-Kernel-Decision.md`, `20260822-0915/0927` invariant map & decision matrix, the `01…07-KnowledgeOS-*` series, KOS-AIP-GOV-STATE-DURABILITY set | The repository's own constitution, reference architecture, kernel decision and invariant register — the closest implementation-side counterparts of v0.2's concepts and invariants |
| `docs/knowledgeos/governance/` (9 md) — operating model, adoption/authorization decisions, session-completion handoff protocol | Implementation-side governance mechanism: tests v0.2's DC, BC_Governance, A6 boundary, policy-in-force behavior |
| `docs/knowledgeos/reviews/` **root** (285 md, minus the two excluded subfolders in §2.4) | The operational record of KnowledgeOS governance actually being run (commissions, verifications, acceptance registrations, grants, lanes) — evidence for status-ladder, authorization-boundary and no-skip conformance **in practice** |
| `docs/knowledgeos/backlog/` (EKS-01…EKS-11 + index) | Registered gaps; direct evidence for known non-conformances (e.g. EKS-09 activation write atomicity) |

### 2.2 IN SCOPE — Tier 2: executable implementation & tests
*(The only machine-checkable conformance evidence in the repository.)*

| Artifact | Why in scope |
|---|---|
| `scripts/observations/KnowledgeOsDoctor.php` (126 lines), `KnowledgeOsInitPlanner.php` (36), `scripts/observations/vscode-knowledgeos/` | Executable KnowledgeOS behavior |
| `.claude/scripts/session-bootstrap.php` | Runtime binding of session authorization/activation — tests A6 and the authorization boundary in code |
| `tests/Unit/KnowledgeOsDoctorTest.php`, `tests/Unit/KnowledgeOsInitPlannerTest.php` | Executable expectations |
| `tests/experiments/knowledgeos_evidence_calculus_property_tests.csv` | Implementation-side twin of corpus EXP-01 (4 operators × 7 properties) — a direct cross-check of the evidence-algebra non-claim in v0.2 |

### 2.3 IN SCOPE — Tier 3: secondary/contextual KnowledgeOS statements
*(Prior architecture claims; evidence of what the repository SAYS, weighted below Tier 1/2; conflicts
between tiers are themselves findings.)*

| Artifact set | Note |
|---|---|
| `docs/knowledgeos/*.md` root corpus (~58 files: Meta_Model, Ontology, Character_Definition, Conceptual_Foundation, falsification/validation matrices, …) | Sampled by relevance to the v0.2 test matrix (§3), not exhaustively read; sampling recorded, no silent cap |
| `docs/knowledgeos/developer_guide/`, `developer_guide/knowledgeos/` (6 md), `docs/knowledgeos/how_far_we_are/` | Developer-facing bindings |
| `docs/implementation/KnowledgeOS_Development_Constitution.md`, `KnowledgeOS_Governance_Execution_Plan.md`, `KnowledgeOS_Product_Discovery_Charter.md` | Cross-referenced constitution-family docs |

### 2.4 OUT OF SCOPE (explicit)

| Artifact set | Reason |
|---|---|
| `docs/knowledgeos/brainstorming/` (696 md, incl. `kernel/`, `phase_measure_theory/`) | Reserved for Brainstorming Archaeology (GN-20 sequence ruling); historical exploration, not implementation |
| `docs/knowledgeos/reviews/synthesis/` (this workplace) | Circularity: v0.2's own derivation cannot serve as conformance evidence for v0.2 |
| `docs/knowledgeos/reviews/kernel/session1/` | Research extraction FROM the brainstorming corpus — same track as archaeology, not implementation |
| PublicDigit application (`app/`, `resources/`, `routes/`, `database/`, `tests/` except the KnowledgeOs* items in §2.2, `config/`, `public/`, …) | Different product; not KnowledgeOS implementation |
| `docs/publicdigit/`, `engineering/` standards, `docs/knowledge/` (EKP), `docs/adr/`, `docs/plans/` | Governance context of the host repo, not KnowledgeOS artifacts (may be cited as context, never as conformance targets) |
| `architecture_legacy/`, `docs/knowledge_tranfer/` | Historical/legacy material |
| `.claude/worktrees/kos-v11-ddd/` | Duplicate worktree of the repo; testing it would double-count. **Triage rule T-1:** one divergence check (does its `docs/knowledgeos/` differ from the main tree?); divergence itself = a recorded finding, content still untested |
| `.claude/` session machinery (except `session-bootstrap.php`), `.codex/`, logs, dumps, CSVs of the voting product | Not KnowledgeOS |

### 2.5 Triage rule for unlisted artifacts
Anything encountered that this table does not classify (e.g. `docs/eks/`) is **triaged first**: the
plan's classification logic (does it claim current authority over KnowledgeOS? is it executable? is
it historical exploration?) assigns IN/OUT, and the assignment is **recorded in the findings file
before any testing of that artifact**. Nothing enters scope silently.

## 3 · Test matrix — what of v0.2 gets tested, against what

Each row will receive verdict(s) per tested relation, with provenance.

| # | v0.2 element under test | Primary evidence sources |
|---|---|---|
| T-1 | 18+4 canonical concepts (existence & meaning fidelity: Knower, Zero(K,EC), EC=η(G,IdealState), Evidence, Determination, K_t, AcceptancePolicy, Proposal, DC, BC_Governance, …) | Tier 1 constitution + reference architecture; Tier 2 code; Tier 3 meta-model/ontology |
| T-2 | Invariants I-1…I-12 (esp. I-1 Knower ownership; I-11 governed/versioned policy change; I-12 no-skip) | Constitution v1.0, invariant map/decision matrix, governance decisions, reviews-root operational record |
| T-3 | Epistemic ladder Candidate→Supported→Accepted + Committed as decision-boundary + A6 | Governance/acceptance registrations; session-bootstrap gates; operating model |
| T-4 | Covering relation / no-skip in practice | Reviews-root lane/grant/verification chains (sampled chains end-to-end) |
| T-5 | Decision Contract DC(d) 6-tuple & authorization boundary (SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction) | Authorization/adoption decision docs; session-bootstrap.php; AST-019 lifecycle records |
| T-6 | Policy stratification (policy-as-content vs policy-in-force, versioning) | Constitution versioning practice; operating-model amendments; EKS backlog items |
| T-7 | Evidence-algebra non-claim (no operator selected) | `knowledgeos_evidence_calculus_property_tests.csv` vs corpus EXP-01 |
| T-8 | Non-collapse distinctions (seven + 042 triple) | Cross-tier reading of T-1 sources |
| T-9 | v0.2 open questions (η-totality/G-residual; Kernel membership; Lord naming; action/execution) | Any Tier 1/2 evidence that bears on them — **status change only via recorded finding** |

## 4 · Evidence discipline

- **Provenance:** every finding cites `path` (+ line/§ where possible) and quotes before paraphrasing.
- **Markers:** ⟦E⟧ = repository evidence (quoted/cited) · ⟦INT⟧ = interpretation/inference ·
  ⟦V⟧ = verdict. A verdict may rest only on ⟦E⟧; ⟦INT⟧ alone yields at most NOT ESTABLISHED.
- **Grades carried:** findings state whether they touch a v0.2 element graded EVIDENCE-DERIVED,
  COMPOSED, or REQUIRED-BY-COHERENCE — conformance of a REQUIRED-BY-COHERENCE element is reported as
  such, never as corpus-proven.
- **Sampling honesty:** wherever coverage is sampled (reviews-root 285 files; Tier 3 root corpus),
  the sample and the residue are both recorded (no-silent-caps rule).
- **Timestamping:** all counts and verdicts dated (GN-03 discipline).

## 5 · Deliverables & sequence

```
D-1  this plan (presented for review)                        ← done, awaiting approval
D-2  analysis/phase-3c-conformance-findings.md               findings registry (CF-nnn)
D-3  analysis/phase-3c-verdict-register.md                   one verdict per tested relation, T-1…T-9
D-4  analysis/phase-3c-report.md                             conformance verdict summary + residue
     (all read-only w.r.t. v0.2 and w.r.t. every source artifact)
```

Execution order: triage sweep (incl. T-1 worktree divergence check, `docs/eks/` triage) →
Tier 1 → Tier 2 → Tier 3 sampling → verdicts → report. Stop points: after D-1 (now); after D-4.

## 6 · Stop rule

**This plan authorizes nothing by itself.** The substantive conformance run begins only after this
plan has been reviewed. Brainstorming Archaeology, Final Architecture, Book Architecture and Book
remain locked and are untouched by this plan.
