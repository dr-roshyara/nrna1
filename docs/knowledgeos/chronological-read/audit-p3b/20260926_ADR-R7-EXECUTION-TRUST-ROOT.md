# ADR-R7-01: the execution trust root of S5 verification is the harness witness (Model D)

| | |
|---|---|
| **Status** | **ACCEPTED by human decision HD-2 (G-LOG-0085).** Implementation is pending the R7 design freeze. Not activated |
| **Scope** | S-Series P3b S5 verification (Revision 7). The reconstruction semantics are unchanged |
| **Decider** | the human project owner. This record is ⚠ authority: generated (drafted by engineering) and records the human decision |
| **Evidence** | re-audit G-LOG-0084 (TB1–TB3, P9b: execution facts forgeable) · `20260926_REVISION-6-REAUDIT-ROOT-CAUSE-ARCHITECTURE.md` §3 · `20260926_MODEL-D-READINESS-RESULT.md` (READY, conditional; 17/18 attacks detected) |

## Context

Revision 6 derived the **plan** independently and anchored the **bytes** in the seal-aware resolver. Every **execution fact**, however, came from files written by the audited agents or by the orchestrator:
- that a read happened;
- in which run, for which label;
- when;
- in which order relative to validation and dispatch.

These files were READ-LOG, R6-PROVENANCE, RUN-MANIFEST and the records. So a PASS certified *mutual consistency of declarations anchored at both ends*, not faithful execution. The binding threat is an LLM agent that shortcuts, fabricates or misreports (documented: E0, OB0018), not a sophisticated attacker.

## Decision

**The harness transcript, reduced deterministically to `WITNESS.jsonl`, is the authoritative execution witness within the declared trust boundary.** Agent-authored artifacts are reconciled against it and are never authorities.

The provenance chain, with its three separated layers:

```
OBSERVATION        harness transcripts (main + subagents + meta)          — written by the harness
      ↓
WITNESS DERIVATION deterministic extractor → WITNESS.jsonl (no corpus text) — reproducible from the archive
      ↓
PRESERVATION       WITNESS.jsonl + raw-transcript digests → freeze → commit — raw transcripts archived outside the repo
      ↓
EVIDENCE           facts anchored on witnessed pages → claims → reconstruction
```

**Trust boundary:**
- Trusted: the harness, the orchestrator, the OS account.
- Untrusted: agent-authored logs, records, facts, claim-evidence, objects and declared times.
- The transcript is independent of the agent's self-authored artifacts, **not universally independent**.

**Invariants** (addendum §5.1; design-repaired under G-LOG-0086):
- **W1** completeness: the completed-dispatch decision table (one notification, one transcript, count present and equal, chain, parse); no agent resumption in S5;
- **W2** identity: agentId → dispatch → run → owner;
- **W3** content: header, page hash recomputed, trailer;
- **W4** failure explicit;
- **W5** ordering by harness intervals;
- **W6** reconciliation with no residue;
- **W7** preservation at unit validation and assembly;
- **W7a** monotonicity: W_unit ⊆ W_final, byte-identical;
- **W8** execution-capability closure: every agent tool call is a canonical reader call, a permitted Write or the handback; all else fails, and a hold-out-bearing call is a SEAL stop.

**Preservation is not trust (PF-11).** The external archive is untrusted storage, accepted only when it matches the committed freeze digests. Its role is reproducibility and re-derivation. Trust = harness observation + freeze commit.

**Execution truth ≠ semantic truth (PF-12).** Model D proves *what happened* (execution observability). Joined with the resolver, it proves *which bytes were observed*. It does not prove that a quote supports a claim (semantic entailment) or what an object means (reconstruction interpretation).

**Decoder (PF-9).** The witness is derived by a neutral transcript syntax decoder. It does not depend on the experimental V1.2.4 instrument.

## Alternatives considered

| Model | Why not chosen |
|---|---|
| A. Trusted reader/orchestrator logs (status quo) | forgeable by the audited party (TB1–TB3) |
| B. Append-only / hash-chained logs | a chain is only as strong as its anchor; the anchor would be another writable file. Useful at most as hardening |
| C. Reader-authenticated evidence (MAC or signature) | requires key isolation through a tool sandbox, which would itself provide most of the value; adds key management; no protection against the orchestrator |
| **D. Harness witness (chosen)** | the agent cannot suppress or edit its own tool calls through its tools; the harness already records the command, the output (page hash, byte span, trailer), the error status, the agent identity and the times. Experimentally shown sufficient |

## Consequences

**Positive:**
- R5-02, R5-03 and R5-04 become *unconditionally* closed within the trust boundary; they are no longer conditional on unedited logs.
- The provenance stages become recomputable.
- Facts become page-anchored (HD-5).

**Negative / obligations:**
- A dependency on the harness transcript format. The extractor is versioned by its sha256 in `WITNESS-DIGESTS.json`, and a format change is a verification failure, never a silent degradation.
- Raw transcripts contain page text, so the archive must live **outside the repository** (FD-4′) and must be retained.
- S5 agents must be dispatched in background mode, so that a harness tool-use count exists, and must never be resumed (FD-7′).
- Agents may use only the capabilities W8 allows (FD-8). The readiness experiment showed agents making exploratory calls (`ls`, reading the wrapper, `mkdir`); under W8 such a run is FAILED. So dispatch prompts must be strict, and the orchestrator pre-creates the run directories.

**Residual (declared, accepted):**
- A malicious orchestrator or account before the freeze commit.
- Non-evidential transcript text before the freeze (readiness F2).
- Semantic entailment and S1 honesty (F2 of the re-audit) remain human-review boundaries.

**Unchanged:** Master Protocol v3.5, R19 (the floor of 396 / 1,975), the reconstruction architecture, and the ML boundary.

## Traceability

G-LOG-0084 → root-cause RC-2 → Model-D readiness (READY, conditional; C1–C7) → HD-2 (G-LOG-0085) → pre-freeze review PF-5/6/7/9/11/12 → design repair (G-LOG-0086; DR-05/06/07/09/11/12) → R7 addendum v2 §1, §5–§7 → tests T14–T29, T49–T62.
