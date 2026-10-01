# Strategic Knowledge Traceability Audit (Track 2) — plan

| | |
|---|---|
| **Date** | 2026-10-01 18:13 |
| **Status** | ✅ **APPROVED by the PO** (2026-10-01 instruction "Proceed with the following human decisions", §2). Execution follows this plan. |
| **Kind** | Discovery and traceability **audit**. **Not implementation.** |
| **Owner process** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared) |
| **Plan convention** | ES-004.2 (`docs/plans/YYYYMMDD-HHMM-<topic>-plan.md`) |

## Objective

Establish the traceability chain from historical brainstorming and Strategic DDD discovery, through architecture and governance decisions, to delivery obligations. **Success means:** every substantive strategic/DDD outcome has exactly one explicit disposition, and every outcome that became a delivery obligation traces to an existing story in `PBDIGIT-00`…`208`. **Success does not mean** that every historical idea becomes a story.

## Background

The delivery inventory closed on 2026-10-01: `2026-10-01-pbdigit-story-integrity-final.md`, *CLOSED WITH KNOWN GOVERNANCE ITEMS*. Its boundary excluded the brainstorming and Strategic DDD corpus, so the knowledge-to-delivery chain is not yet shown closed.

## Recorded human decisions (binding on this audit)

1. **Frozen delivery baseline:** *"Delivery baseline frozen at PBDIGIT-208 (PO, 2026-10-01). New IDs only for a delivery obligation shown not to be covered by PBDIGIT-00…208."*
2. **No PBDIGIT-209+ during Track 2.** An apparently uncovered obligation is recorded as **CANDIDATE — POTENTIAL UNCOVERED DELIVERY OBLIGATION**, with evidence, and reviewed separately afterwards. **No existing story's scope is modified** to make the audit pass.
3. **Brainstorming-file exclusion:** `docs/publicdigit/brainstorming/20260930_0935_current_status.md` (untracked, another session) is **excluded**. If its provenance becomes material, record *HUMAN DECISION REQUIRED — provenance of 20260930_0935_current_status.md* and continue without it.
4. **KnowledgeOS exclusion:** PKS strategic-modelling records, the DR-1…DR-6 rulings and KnowledgeOS research-session material are excluded. Where a PublicDigit document references KnowledgeOS, only the PublicDigit consequence is recorded.

## Scope: corpus and order

**Registers first, in this order:**
1. the DDD programme-state archive (`.claude/memory/ddd_program_state.md` and the `.claude/memory/` index);
2. `docs/implementation/EPIC-002_Concept_Register.md`;
3. the canonical context map (`docs/implementation/EPIC-002_Canonical_Context_Map.md`);
4. the ADR log and register (`docs/adr/ADR-T-LOG-Tactical-Implementation.md`, `docs/adr/README.md`, the ADR files);
5. the ARB register (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`).

**Only then, the underlying documents,** where an entry is unclear or needs evidence: `docs/architecture/design/` (about 227 documents, 20 rounds), the EPIC-002/003/004 discovery set (about 30 documents), `docs/publicdigit/architecture/`, and `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`. **No indiscriminate trawl.**

## Design decisions

- **Unit of audit:** a *substantive outcome* (a bounded context, boundary, invariant, policy, capability, architecture decision, governance decision, hypothesis, deferral, rejection, explicit product requirement or strategic constraint). Equivalent statements across documents are **one** outcome, with several evidence references.
- **Dispositions (exactly one each, with evidence):**
  - A. IMPLEMENTED;
  - B. EXISTING PBDIGIT STORY;
  - C. ARCHITECTURE DECISION;
  - D. GOVERNANCE DECISION;
  - E. DEFERRED / HELD;
  - F. REJECTED / SUPERSEDED;
  - G. HYPOTHESIS / EXPLORATORY;
  - H. UNKNOWN / REQUIRES HUMAN DECISION.

  A candidate uncovered obligation is recorded as an annotation on its disposition, and is never a new ID.
- **IMPLEMENTED needs code evidence** (a path in `app/` or a test). It is not inferred from a document's claim. The delivery audits showed documents overstating completion.
- **No promotion:** a hypothesis stays G. An architecture or governance outcome stays C or D, even when it concerns software.
- **Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) → `reviews/`. Output: `docs/publicdigit/reviews/2026-10-01-strategic-knowledge-traceability-audit.md`.

## Convergence criteria (K1–K10, as approved)

| | Criterion |
|---|---|
| K1 | All declared registers examined |
| K2 | Every substantive outcome in them has a disposition |
| K3 | Every disposition has source evidence |
| K4 | No hypothesis silently promoted |
| K5 | Every delivery-obligation outcome maps to exactly one `PBDIGIT-00`…`208` story, or is marked a candidate |
| K6 | Architecture and governance outcomes stay distinct from delivery stories |
| K7 | Deferred, held, rejected and superseded outcomes stay explicitly recorded |
| K8 | KnowledgeOS and unrelated sessions excluded |
| K9 | No PBDIGIT-209+ created |
| K10 | No outcome left UNKNOWN without a named human, ARB or PO decision |

## Task checklist

- [x] Freeze recorded (README, final closure audit §0)
- [x] Plan written (this file)
- [ ] K1: read the five registers; extract candidate outcomes
- [ ] Consolidate equivalent outcomes; assign IDs (`SK-nnn`)
- [ ] Disposition and evidence per outcome; code evidence for IMPLEMENTED; story evidence for B
- [ ] Inspect underlying documents only for unclear entries; log which ones were opened
- [ ] Write the matrix and the closure report; run K1–K10
- [ ] Session log and context updates

## Progress

- 2026-10-01 18:13: plan approved and recorded. Freeze recorded. Execution starts after the authorised commit of the delivery-audit records.

## Risks

- **Over-extraction:** the corpus is large. Mitigation: registers first, and the outcome is the unit.
- **Document claims mistaken for implementation.** Mitigation: IMPLEMENTED needs code evidence.
- **Boundary bleed into KnowledgeOS.** The DDD programme archive also holds governance-track material that later fed KnowledgeOS; only PublicDigit consequences are recorded.
- **Same-process bias:** disclosed in the report.

## Open questions

- None blocking. The provenance of the excluded brainstorming file is a human decision, if it becomes material.

## Next actions

Execute K1 → closure report. **Stop at the report.** No implementation, no new IDs, no story-scope changes.

**Traceability:** PO instruction 2026-10-01 · `2026-10-01-pbdigit-story-integrity-final.md` §0, §17 · ES-004.2.
