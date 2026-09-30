# KnowledgeOS — Execution Instruction

Do not create a new methodology and do not replace either existing protocol.

Use:

1. **Master Protocol** as the authoritative protocol for Phase 1.
2. **Step-2 Theory Construction Protocol v3.2** as the authoritative protocol for Phase 2.

Use the following architecture documents as the governing architectural context:

* `KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`
* `architecture_phase_1_phase_2.md`

The protocols remain authoritative for their respective phases. The architecture documents define the relationship and boundaries between them.

---

## Phase 1 — File-driven

Process every canonical corpus file that has not yet completed Phase 1.

For each file, apply the Master Protocol §9 and §9A completely.

The objective is to establish the evidence-backed Research Reconstruction Package, including:

* theory-bearing content
* Theory Objects
* Theory Threads
* verified relationships
* derivations
* gaps
* contradictions
* research obligations

Do not classify a file as irrelevant merely because of its filename, folder, or document type.

---

## Phase 2 — Theory-driven

Do **not** execute Phase 2 automatically after every file.

Phase 2 operates on the emerging research/theory state:

* Theory Objects
* Theory Threads
* Clusters
* Derivations
* Propositions
* Contradictions
* Gaps
* Research Obligations
* Open Questions
* Candidate mathematical/logical structures

Phase 2 may begin as soon as a sufficiently coherent theory object/thread/cluster exists. It does not have to wait until the entire corpus has completed Phase 1.

Phase 2 is theory-driven, not file-driven.

Do not interpret an unresolved issue in one theory thread as automatically blocking all other Phase-2 work unless the authoritative Step-2 protocol explicitly requires that dependency.

---

## Targeted feedback

If Phase 2 needs additional evidence, formulate a specific research obligation and perform a targeted corpus investigation.

Do not reread files without a reason.

If Phase 2 discovers that Phase 1 missed evidence, a relationship, or theory-bearing content:

```text
Phase-2 discovery
      ↓
Phase-1 correction/request
      ↓
updated Phase-1 evidence
      ↓
Phase-2 reassessment
```

Do not silently modify Phase-1 historical reconstruction from inside Phase 2.

---

## Theory construction

Recover and consolidate the theory actually developed in the corpus.

Search the corpus before introducing anything new.

If an expert-derived component is genuinely necessary and unsupported by the corpus, mark it explicitly `[E] EXPERT-DERIVED` and record:

* rationale
* assumptions
* alternatives
* falsification conditions

Do not invent mathematical definitions, thresholds, axioms, or structures merely to make the theory appear complete.

Maintain the distinction:

```text
Evidence
   ↓
Reconstruction
   ↓
Hypothesis
   ↓
Derivation
   ↓
Validation
   ↓
Canonical
```

Do not promote Candidate Theory to validated or canonical theory prematurely.

---

# Gate Verification and Phase Completion

## Mandatory rule

**Do not declare Phase 1 or Phase 2 complete merely because the research artifacts say that the gates passed.**

After completing the applicable work for a phase:

1. Identify the gates required by the authoritative protocol.
2. Locate the existing gate/check/validation script or machine-checkable mechanism in the repository.
3. Run the applicable gate/check script.
4. Inspect the actual output and exit status.
5. Compare the machine result with the protocol's gate definitions.
6. Verify that the underlying artifacts and registries are consistent with the reported result.
7. Record the actual gate result.
8. Only then declare the phase or work item eligible to proceed.

### If a gate script exists

Run it.

Do not substitute a manual statement such as:

> "The gates appear to pass."

The actual script/check result is the primary machine-verifiable evidence.

Record at minimum:

* script/check name
* command executed
* execution date/time
* exit status
* PASS/FAIL result
* relevant failure or warning output
* affected artifacts, if any

### If no gate script exists

Do not invent one.

Instead:

1. Identify the gate definition in the authoritative protocol.
2. Determine whether it is manually verifiable from existing artifacts.
3. Perform the prescribed verification.
4. Clearly report:

```text
MACHINE_GATE_CHECK: NOT_AVAILABLE
MANUAL_GATE_CHECK: PASS / FAIL / PARTIAL / NOT_ASSESSABLE
```

Do not represent a manual assessment as a machine-verified pass.

### If the gate fails

Do not silently continue as though the phase passed.

Report:

```text
GATE: <name>
STATUS: FAIL
REASON: <actual reason>
AFFECTED ARTIFACTS: <...>
NEXT ACTION: <...>
```

Then follow the authoritative protocol and governance rules for correction.

Do not modify evidence merely to make a gate pass.

---

# Phase-1 completion verification

At the end of a Phase-1 work unit, verify **both**:

### A. Protocol compliance

Confirm the required Master Protocol §9 and §9A steps were actually performed.

### B. Gate verification

Run the applicable machine-checkable gate/checks and inspect their actual results.

The following are distinct:

```text
Phase-1 work completed
        ≠
Phase-1 gate passed
        ≠
Theory validated
```

A Phase-1 PASS means the reconstruction artifact/process satisfies the applicable Phase-1 admission/completion requirements.

It does **not** mean that the recovered theory is mathematically, logically, statistically, empirically, or computationally validated.

---

# Phase-2 completion verification

At the end of a Phase-2 work unit or readiness boundary, verify:

### A. Step-2 protocol compliance

Confirm that the applicable Step-2 v3.2 requirements were actually executed.

### B. Gate verification

Run the applicable gate/check scripts or machine-checkable mechanisms.

### C. Evidence and provenance consistency

Confirm that:

* theory statements have traceable evidence;
* Phase-1 evidence has not been silently rewritten;
* expert-derived material is explicitly marked `[E] EXPERT-DERIVED`;
* hypotheses are not presented as validated facts;
* Candidate Theory is not prematurely promoted.

### D. Registry/state consistency

Check that the actual artifacts agree with the registries and status files.

For example:

```text
File Reconstruction Record
        ↕
Dossier
        ↕
Theory Object Registry
        ↕
Conformance Registry
        ↕
Research state
```

A stale registry entry is a state inconsistency and must not be ignored merely because the underlying artifact appears complete.

---

# Gate result versus research result

Always distinguish these:

### Gate result

Answers:

> "Did the prescribed process/artifact satisfy the defined structural and procedural conditions?"

### Research result

Answers:

> "What did the research discover?"

### Theory validation result

Answers:

> "Does the proposed theory survive mathematical, logical, statistical, empirical, and computational examination?"

These are different claims.

A gate PASS must never be reported as proof that the KnowledgeOS theory is correct.

Likewise, a research discovery must not be promoted merely because a procedural gate passed.

---

# Independence and corroboration

Never count repeated transmission of the same claim as independent corroboration.

If the same observation, conclusion, or text is copied or propagated through multiple files, treat the records as potentially originating from one underlying observation unless the authoritative protocol establishes genuine independence.

In particular:

```text
7 files containing the same claim
        ≠
7 independent observations
```

Before promoting an evidence count, explicitly establish the independence basis.

If independence cannot be established, record the evidence as repeated/transmitted evidence rather than independent corroboration.

Follow the Step-2 protocol's existing rules concerning evidence maturity and independent readers.

Do not redefine "independent" merely to increase the evidence count.

---

# Current-state synchronization before execution

Before performing another substantial batch of work, inspect the **current repository and research state**, not merely the previous prompt or previous session summary.

Check for:

* current git status
* newly created or modified research artifacts
* governance verification records
* L0 decisions
* unresolved governance restrictions
* current conformance registry
* current Theory Object/Thread state
* current gaps and Research Obligations
* pending corrections
* files that are held, blocked, or explicitly forbidden
* relevant untracked research/governance artifacts

Do not assume that an untracked file is irrelevant merely because it is not committed.

Do not assume that a previously read file is covered by an authorization that applies only to previously unread files.

Before execution, resolve conflicts between:

```text
protocol
current research state
governance state
explicit user authorization
```

Do not silently choose one interpretation when an unresolved authority conflict exists.

---

# Immediate task

Before performing another large batch of work, inspect the current state and report:

1. Phase-1 completion status.
2. Current Theory Objects and Threads.
3. Current theory clusters.
4. Current gaps and research obligations.
5. Current Candidate Theory state.
6. Which theory areas are ready for Phase 2.
7. Whether any targeted Phase-1 investigations are required.
8. The single next action that is best justified by the current research state.
9. Which Phase-1 or Phase-2 gates are currently applicable.
10. Whether the applicable gate/check scripts exist.
11. Whether the most recent applicable gates were actually executed.
12. The actual PASS/FAIL/PARTIAL result of those checks.

Then execute the single next action **only if it is authorized and permitted by the current protocol and governance state**.

If the current state contains an unresolved authorization or governance conflict, stop at that conflict and report it rather than proceeding by assumption.

---

# Execution invariant

The required execution order is:

```text
Read authoritative architecture/protocols
        ↓
Inspect current research + governance state
        ↓
Determine applicable phase
        ↓
Determine permitted next action
        ↓
Execute
        ↓
Run applicable gate/check
        ↓
Inspect actual result
        ↓
Reconcile artifacts + registries
        ↓
Record result
        ↓
Continue or stop according to the result
```

Do not skip the gate/check step merely because the work appears correct.

Do not treat a self-authored "PASS" statement as equivalent to an executed gate.

Do not proceed past a failed or unresolved gate without an explicit protocol/governance basis.

---

# Do not create another methodology

Do not create another protocol, another phase model, or additional governance gates unless a concrete contradiction in the existing architecture requires one.

If a problem is discovered:

1. First determine whether the existing architecture/protocol already addresses it.
2. If it does, follow it.
3. If execution diverged from it, correct the execution.
4. Only if a genuine contradiction exists should an architectural/protocol change be proposed.
5. Do not silently modify an authoritative protocol during execution.

The objective is to execute the existing KnowledgeOS research architecture faithfully, maintain provenance, verify gates empirically, and recover/validate the theory without inventing methodology.
