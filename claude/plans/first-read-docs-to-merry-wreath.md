# External Proposal Verification — Spec-Driven Agentic Architecture vs the Platform

**Status:** APPROVED WITH MINOR AMENDMENTS (Decision Authority, 2026-07-10) — amendments applied; this file is the permanent record of the verification (kept per ARB: "it documents why certain things were intentionally not implemented"). No other repository record exists for this analysis, by ruling.

## Context

An external proposal (spec-driven, domain-first agentic architecture: state-machine lifecycle, workflow engine, execution aggregates, JSON artifacts, approval UI) was verified against the platform. **Finding: the proposal's intent is implemented as enforced governance (EEP + gates + traceability + append-only records), not as orchestration software — deliberately, by ruling (R-37 burden of proof).** Governance defines the process; the process executes the work; the current AI is one execution adapter filling the Engineer role.

## Verification matrix (external proposal → platform)

| Capability proposed | Status |
|---|---|
| Lifecycle state machine, no skipped stages | ✅ EEP (governance, not software) |
| Human approval before any write | ✅ EP-01/EP-01-Light + EEP §5 |
| Structured artifacts over chat | ✅ markdown, versioned (JSON deferred — trigger: first machine consumer) |
| Test-first execution | ✅ TDD standing rule (RED before GREEN) |
| Traceability change→plan→decision | ✅ five-question trace + EP-02 reports |
| Governance layer, human final authority | ✅ rulings register + freezes + EEP principle 10 |
| Quality gates at transitions | 🟡 merge-gate/quality-gate DONE; AST-010 = C3 (approved, cold-session); CI = 7E (gated on F-7D-2) |
| Workflow engine / execution aggregates / approval UI / rollback machinery | ⏸ Deferred by ruling — triggers recorded in the retrospective inbox |
| Autonomous next-step loop | ❌ Rejected by design (EEP §9: continuation never implicit) |
| Provider-independent execution role | ✅ Engineer role, provider-neutral (OQ-verified) |

## Decision

**Accepted:** the verification's conclusion — *nothing in the external proposal requires a new architectural change.*
**Deferred (by ruling, triggers already queued):** workflow engine · execution aggregates · JSON artifacts · approval UI · rollback machinery — one consolidated evaluation at the retrospective.
**Rejected:** autonomous continuation (human decision is the design, not a gap) · any new mapping record/memo/C4 for this analysis (this conversation + this file are the reasoning).

**Next executable work (in order):**
1. **C3 / AST-010** `run-gates.sh` — fresh session only, plan approved as written.
2. **PB-007 7E** CI workflow — after the F-7D-2 ruling; closes PB-007 = EPIC-001 complete.
3. **PB-004..** continue product capabilities under the EEP.
4. **Retrospective** — the single decision point for everything deferred.

*Vocabulary (prospective): "governance defines the process; the process executes the work" · "the AI is one execution adapter used by the Engineer role under the EEP". The c4 §2 Engineer-box correction waits for the retrospective (R-37).*
