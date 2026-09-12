# 10-capture — what was said

**`[DEF]` stage contract · P1.** Written 2026-09-11 · amended 2026-09-11 (A4). Registered in
[`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What was said?*

| | |
|---|---|
| **Entry criteria** | a brainstorming discussion has happened and is not yet recorded |
| **Exit criteria** (terminal predicate) | every session that occurred has a session record; every **theoretically substantive** statement in it is captured as a finding or a topic; **every source block carries `PRIMARY · UTTERANCE` and every agent block carries a `DERIVED` role**; **nothing was dropped for being repetitive, partial, informal or inconsistent** |
| **May be written here** | session records · findings · topics · open questions |
| **Must NOT be written here** | any adjudication · any merge of two forms · any judgement that a form is wrong, superseded or canonical · **any reclassification of a source utterance** |
| **Source phase** | protocol P1 — *maximise information preservation, not reduction. Reduction begins later.* |
| **Templates** | [`TEMPLATE-session`](../templates/TEMPLATE-session.md) · [`TEMPLATE-finding`](../templates/TEMPLATE-finding.md) · [`TEMPLATE-topic`](../templates/TEMPLATE-topic.md) · [`TEMPLATE-open-question`](../templates/TEMPLATE-open-question.md) |

## The rule that governs this stage

**Record, do not resolve.** A term is a handle, not an object. A statement that contradicts another
is recorded **as stated** — the contradiction is a finding, not an error to repair.

**This stage never corrects a source silently.** If something is wrong, it is flagged; flagging is
recording.

## Two layers in every session record

A session file is `DERIVED` — the agent wrote it. The **statements inside it are not.** Every block
carries its own tag (§C4.1):

- `PRIMARY · UTTERANCE` — the participant's words, verbatim, untidied.
- `DERIVED · OBSERVATION | INTERPRETATION | HYPOTHESIS | FINDING` — the agent's.

**A `PRIMARY` block is never edited, summarised in place, or reclassified** (§C4.3). Our assessment of
an utterance is a **new block or a new record that cites it**. The utterance stays `UTTERANCE`
forever — including when it is later used as the basis of a finding, and including when it is
overtaken.

> **Why this is a capture rule and not a later one.** A mis-typed statement at intake is unrecoverable
> downstream: every later stage would be reading agent material where it should be reading the
> participant's. Provenance is decided here or it is lost.

## Repetition is not corroboration *(A4)*

**A statement repeated across sessions is captured every time it occurs** — each occurrence is
recorded, with its own anchor and date. Repetition is **provenance**, and provenance is kept.

**But repetition is never evidence that the statement is independent.** Two occurrences of the same
statement — in two sessions, in two wordings, by the same participant — are **one** source material
and **one** claim. They never count as two.

> **Duplicate ≠ independent evidence. Repetition is not confirmation.**
> *Restatement is not independent arrival.*

This is recorded **at intake** because intake is where the count is created. A later stage can only
count what this stage kept apart, and by then the two occurrences look like two findings.

**How it is recorded.** A repeat is captured with a `cites:` field pointing at the earlier occurrence.
It is never dropped, and it is never counted as a second source.

## What is deliberately absent here

No JSONL records and no script. This stage promotes its records to JSONL only if it turns out to
need machine-parsing — the Markdown-first decision. Until then, Markdown only.
