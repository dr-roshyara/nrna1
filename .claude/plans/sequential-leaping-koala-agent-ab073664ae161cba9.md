# Implementation Plan — AST-017 Session Bootstrap & Responsibility Resolution
## EKS-07 minimal operational correction (PLAN ONLY — no files modified)

Work item (proposed): KOS-SESSION-BOOTSTRAP-001 · Component CMP-004 (workflow_engine) · governance_tier 2 · runtime_moments [ON_DEMAND]

## 0. Framing
- Treat EKS-07 as an OBSERVED coordination deficiency. This is a minimal operational correction, NOT an EKS-07 implementation. Do NOT declare EKS-07 solved.
- Do NOT weaken governance. Do NOT create a second engine/identity/role/authority/completion model. No autonomous authority transfer.
- ONE authoritative resolver: AST-017 delegates ALL interpretation to AST-015 (workflow-state.php) per AMENDMENT 2, with ONE documented bounded exception (V-3 handoff read).
- Identity is evidence-only (INV-ATTR-1/2): reported, never used to grant.
- Fail-closed: UNRESOLVED/AMBIGUOUS/UNRESOLVABLE → actionable message; never invent identity/authorization/transition.
- Keep ON_DEMAND + pointer-only. Do NOT wire SESSION_START in this increment (V-3 binding on automatic startup paths; KOS-EXEC-TOPOLOGY-001:162).

## 1. Output schema (YAML default, --json flag)
See full field table in the plan body. Six concept buckets kept separate:
identity / role / eligibility / authorization / ownership / continuation.

## 2. Resolution algorithm (pseudocode)
See plan body.

## 3. Registry entries
- AST-017 entry (planned → adopted after verification), five-question trace, V-3 resolution note.
- AST-016 finding_open annotated: V-3 remedy PARTIALLY chosen by AST-017 (bounded consumer-side handoff read); full remedy (read command on AST-015) remains undecided; AST-016 line itself unchanged.

## 4. Test matrix (SessionBootstrapContractTest, hermetic proc_open/temp-dir)
S1 normal active · S2 CREATED names START prereq · S2b CREATED+handoff names only missing human START (V-3) · S3 active-within-scope · S4 AMBIGUOUS no guess · S5 identity mismatch STOP · S6 no grant → no + next actor · S7 completed → completion fields · S8 determinism · S9 read purity · S10 UNRESOLVED actionable · S11 absent mechanism UNRESOLVABLE · S12 no records dir · S13 interpreter identity · S15 exit contract.

## 5. Files & order
1 registry.yaml (AST-017 planned + AST-016 annotation)
2 script .claude/scripts/session-bootstrap.php
3 tests (RED first)
4 governed doc + developer guide
5 harness pointers (.claude/CLAUDE.md, AGENTS.md, .codex/README.md)
6 inject-context.sh: NO wiring this increment
7 EKS-07 addendum + diagnostic report + Session Completion Report

## 6. Verification
Unit tests · determinism · read-purity · live read-only run against real record · provider-independence by construction (deterministic PHP, no model call) · governance ceremony (PO/ARB act + scope; no migration/DV/RV touch).

## 7. Risks & STOP boundary
- V-3 bounded read tension with Single Authority Resolver invariant → documented, alternative = read-command on AST-015 (separate governed slice / EKS-07 FOLLOW-UP).
- Prose executionContext → multiple labels per lane → attribution caution, fail-closed.
- EKS-08 §1b warns: never reimplement the fold. The V-3 read is the single handoff fact only.
- STOP if: any deeper gap revealed → record EKS-07 FOLLOW-UP and stop; any governance rule must weaken; any SESSION_START wiring attempted before V-3 full remedy; any migration/DV/RV artifact touched.
