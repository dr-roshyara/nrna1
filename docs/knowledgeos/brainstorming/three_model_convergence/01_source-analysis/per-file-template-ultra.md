# Ultra-Concise Per-File Record Template (MD-013 — from seq 0055 onward)

Read deeply; write minimally. The YAML is the primary artifact. The .md is a thin audit wrapper.
Tier 2 prose fires only on genuine novelty (Knowledge/Kernel definitions, K_t, dimensions/values,
operators, probability/measure, topology, Gītā concepts, contradictions/falsifications, C1/C2
transitions, bridge_candidates, major architectural principles). A file that repeats an established
concept gets a repetition pointer, not a re-explanation.

## `.md` file

```markdown
# File Analysis — NNNN

*(Ultra-concise record (MD-013) — see NNNN.yaml for the structured record.)*

## Source
Path · Title · Date · Bytes · source_role

## Classification
`<primary>` (<C1/C2/G/M/X/META>) — [one clause reason]

## Tier 2
NOT TRIGGERED / TRIGGERED — [if triggered: the detailed analysis prose goes here, otherwise
one line stating why not]

## Classification Revision History
| Date | Event | From | To | Reason |
|---|---|---|---|---|
| 2026-09-01 | initial provisional (pass 1, ultra-concise per MD-013) | — | `<value>` | [reason] |
```

## `.yaml` file — the primary record

```yaml
sequence: NNNN
path: ...
title: ...
date: ...
bytes: ...

model:
  primary: c1 | c2 | m | g | x | meta
  secondary: []

source_role: PRIMARY_RESEARCH | FOUNDATIONAL | INDEPENDENT_RESEARCH | BRIDGE | CRITICAL_REVIEW |
             VERIFICATION_RECONSTRUCTION | SESSION_LOG | DUPLICATE_REPRODUCTION | IMPLEMENTATION | META
canonical_source: int | null

importance: critical | high | medium | low
confidence: high | medium | low

about: "1-2 sentences — what this file establishes, not a topic list"

introduces: []          # genuinely new concepts only
defines: []              # formal-ish definitions stated
refines: []               # existing concept, sharpened — state OLD -> NEW briefly if not obvious
contradicts: []            # what it contradicts, with which file if known
repeats:                    # established elsewhere; point to source, don't re-explain
  - concept: ...
    canonical_source: NNNN
    novelty: none

bridge_candidates: []        # MD-012: possible cross-lens relationship, NOT a confirmed bridge

mathematical_content: false
gita_content: false
kernel_content: false

key_evidence: []              # short phrases/precise observations, not paragraphs
undefined_concepts: []
open_questions: []

tier2_triggered: false
tier2_reason: "..."
```
