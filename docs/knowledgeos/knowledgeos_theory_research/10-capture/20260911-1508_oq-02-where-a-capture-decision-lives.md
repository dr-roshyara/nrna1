# `OQ-02` — where does a decision made during capture live?

| | |
|---|---|
| **ID** | `OQ-02` |
| **Stage** | `10-capture` |
| **Kind** | open question |
| **Provenance** | `DERIVED` |
| **Claim layer** | `OBSERVATION` — the agent records a gap it has witnessed in this root's own machinery |
| **Governance** | `NONE-RECORDED` |
| **Status vector** | `DERIVED · OBSERVATION · NOT-EVIDENCED-IN-CAPTURE · N/A · ACTIVE · NONE-RECORDED` |
| **Raised** | 2026-09-11 |
| **Owner** | `UNASSIGNED` |
| **Route** | a decision that arises in `10-capture/` and must be recorded — or a decision to keep the stage gates as they are |

## 1. The question

`TEMPLATE-session.md` §2 has a column **`→ record: D-<nn>`** — a decision reached in a session is
recorded as a decision record.

But `60-governance/_STAGE.md` states its **entry criteria** as *"`50-validation/` holds validation
records"*, and `D-<nn>` is defined as a `60-governance` record (`TEMPLATE-decision.md`).

**So a decision reached during capture has a pointer and no stage it may legally be written into.**
Either the pointer is wrong, or the stage gate is.

## 2. Why it is open

Nothing has been written into `60-governance/` out of order. The bootstrap session `S-01` §2 recorded
its acts **inline** rather than as a `D-01`, so the tension is **structural, not yet a violation**.

It is recorded rather than resolved because both readings are defensible and the choice is a design
decision about this root, not a fact about the material:

- **(a)** `60-governance`'s gate is about **theory** decisions — those do need validation first — and
  decisions arising *inside* other stages belong to those stages' records.
- **(b)** The gate is wrong: a decision should be recordable at the stage it arises, and the pipeline
  position of `60-governance` reflects where decisions are *consolidated*, not where they are *born*.

The tempting repair — writing a `D-01` into `60-governance/` because the session template points
there — is the move §C5 forbids: status is not acquired because of where a file sits.

## 3. What would answer it

**A real decision arising during capture** — not a machinery amendment like A1–A4, but a research
decision that must be recorded and is not a theory claim. The first one forces the answer.

If the first such decision is recorded inline without anyone reaching for `D-nn`, answer (a) holds
and this closes. If recording it requires a `D-nn`, the gate needs an amendment.

## 4. What it blocks

**NOTHING RECORDED.** Capture proceeds. Every decision taken so far — A1–A4 — was an act on this
station's own machinery, licensed by the participant's approval, and is recorded in `S-01` §2 and in
`90-meta/CHANGELOG.md` rather than as a `D-nn`.

## 5. History of this question

| date | event |
|---|---|
| 2026-09-11 | raised while writing the bootstrap capture `S-01` — the tension was found in §2 of that record, but `S-01` is append-only (§C8) and is **not** edited to add this pointer |
