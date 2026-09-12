# `<YYYYMMDD-HHMM>` — `<session title>`

| | |
|---|---|
| **ID** | `S-<nn>` |
| **Stage** | `10-capture` |
| **Kind** | session |
| **Provenance** | `DERIVED` — the record is written by the agent |
| **Claim layer** | `OBSERVATION` — the agent reports what was said |
| **Governance** | `NONE-RECORDED` |
| **Status vector** | `DERIVED · OBSERVATION · <evidence_status> · N/A · ACTIVE · NONE-RECORDED` |
| **Scope of this session** | `<what was in scope — and what was explicitly out>` |

> **What this session was trying to answer.** `<one sentence>`

**Tagging used in this record.** The session *file* is the agent's — `DERIVED`. The *statements
inside it* are not. Every block below carries its own `provenance · claim_layer`:

| tag | means |
|---|---|
| `PRIMARY · UTTERANCE` | source material — a participant's words, reproduced **verbatim** |
| `DERIVED · OBSERVATION` | the agent reports something checkable in the material |
| `DERIVED · INTERPRETATION` | the agent ascribes meaning |
| `DERIVED · HYPOTHESIS` | a proposed explanation, awaiting test |
| `DERIVED · FINDING` | supported by stated evidence, with a stated falsifier |

**A `PRIMARY` block is never edited, tidied, summarised in place, or reclassified** (§C4.3). When we
assess an utterance, the assessment is a **separate block or a separate record** that cites it — the
utterance stays `UTTERANCE` forever, whatever it says and however it turns out.

## 1. What was said

*Recorded in order. Quote **verbatim** where the wording matters, with an anchor (§C9 — heading or
the quote itself, never a line number). **Do not tidy the wording** — an informal or half-formed
statement is preserved as stated.*

> **`PRIMARY · UTTERANCE`** — `<participant role>`, `<date>`

> `<verbatim text>`

*Elisions are marked `[…]`. A paraphrase is never labelled `PRIMARY`.*

> **`DERIVED · OBSERVATION`** — agent

> `<what the agent directly witnesses in the material — checkable against the block above>`

## 2. What was decided

| # | decision | act | → record |
|---|---|---|---|
| 1 | | `<adopted / rejected / …>` | `D-<nn>` |

*The `act` column is the **governance** axis (§C4.4) — which acts were recorded, not how strong the
decision is. A decision act attaches to a decision; it does not grade the claims around it.*

## 3. What was left open

| # | question | → record |
|---|---|---|
| 1 | | `OQ-<nn>` |

## 4. What was NOT discussed

*Absences are **recorded, never filled.** An absent topic with a stated reason is a finding. A stage
or axis that was not exercised is recorded here too — an untested rule must not read as a passing
one.*

## 5. Corrections to earlier records

*Append-only (§C8). Name the record being corrected and state the correction. **Never edit the
earlier record.***
