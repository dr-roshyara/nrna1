# `OQ-01` — is *source form* a separate axis from epistemic role?

| | |
|---|---|
| **ID** | `OQ-01` |
| **Stage** | `10-capture` |
| **Kind** | open question |
| **Provenance** | `DERIVED` — the question is the agent's |
| **Claim layer** | `OBSERVATION` — the agent records a gap it has witnessed |
| **Governance** | `NONE-RECORDED` |
| **Status vector** | `DERIVED · OBSERVATION · NOT-EVIDENCED-IN-CAPTURE · N/A · ACTIVE · NONE-RECORDED` |
| **Raised** | 2026-09-11 |
| **Owner** | `UNASSIGNED` |
| **Route** | `10-capture` — a capture case that forces the distinction, or a decision that it does not matter |

## 1. The question

`UTTERANCE` currently covers **both** a statement spoken in a brainstorming exchange **and** an
ingested written source (e.g. the controlling procedure in `prompts/`, registered
`PRIMARY · UTTERANCE`).

Are those the same role, or is **how the material arrived** — spoken exchange, written source,
imported record — an axis of its own, separate from `claim_layer`?

## 2. Why it is open

The bootstrap capture `S-01` recorded both kinds in one session without anything breaking:
`provenance` was `PRIMARY` and `claim_layer` was `UTTERANCE` in every case, and no downstream question
needed the distinction. **No case has yet forced it.**

It is recorded rather than resolved because the tempting repair — adding a value to `claim_layer` —
would be exactly the move §C10 forbids: using the record classification to answer a question about
the material that is not a question about its epistemic role.

## 3. What would answer it

A case where two source materials with the **same** epistemic role must be told apart **by form** —
for example, a historical document and a live statement both cited as evidence for one topic, where a
later stage must weight or handle them differently at the point of use.

If no such case arises, the answer is *"no, one axis suffices"*, and the question closes as
`NOT-EVIDENCED-IN-CAPTURE`.

## 4. What it blocks

**NOTHING RECORDED.** Capture proceeds; `PRIMARY · UTTERANCE` is unambiguous for both forms today.

## 5. History of this question

| date | event |
|---|---|
| 2026-09-11 | raised during the bootstrap capture `S-01` |
