# C14 — Human governance and change control · v1.2 (delta)

**Base:** `contracts-v1.1/C14-human-governance-change-control.md`, sha256 `2abe7d02e9ef1953f4702cf5873553c35488635befe0be667cd8c487257538ee`.

## Changes (F-20, F-08, F-12, F-15)
1. **Execution approval is explicit and hash-bound.**
   - A protocol version becomes executable only when the human records one line per governing document in `F-GOVERNANCE-LOG.md`: `APPROVED-FOR-EXECUTION: <repo-relative path> <sha256>`. The governing documents are the protocol, the runbook and `contracts-v1.2/00-CONTRACTS-IN-FORCE.md`; the index pins every contract file's sha256.
   - A stamped version number is never approval.
   - Changing any governing document changes its hash and so revokes execution until a new approval line is written.
2. **Human references.** Every exception after READ-COMPLETE, every re-extraction and every independent-audit CONFIRMED cites an `F-LOG-####` entry whose section names the F-ID.
3. **Human-decision switches.** `F-DECISIONS.json` holds decisions that code depends on (HDR-2 `temporal_semantics`, HDR-3 `multiplicity_rules`). A null value blocks the dependent functions. A switch is changed only together with the F-LOG entry that records the decision.
4. **The AI never writes approval lines, human references or decision switches on its own initiative.** It proposes them; the human decides.
