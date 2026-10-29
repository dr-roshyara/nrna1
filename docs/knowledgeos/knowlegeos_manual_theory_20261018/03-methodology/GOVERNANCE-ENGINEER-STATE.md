# Governance Engineer: working state (mutable)

> **Two tracks (decided 2026-10-29).** This file is the **research track** state and the Governance Engineer's own. The **implementation track** state is `docs/knowledgeos/IMPLEMENTATION-TRACK-STATE.md`; its decisions are in `docs/knowledgeos/IMPLEMENTATION-TRACK-DECISION-RECORD.md`.

*Rewritten 2026-10-29 for the handover (option a). Rules: `MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md` (revision 3 in force). This file is overwritten as the state changes; history is in git.*

## Handover (2026-10-29)

- **Outgoing session** `f1575f5d-96b1-404a-af96-797d354857e2` was started in the lane folder, so the repository's deny rules, agents and skills did not apply to it (pilot finding F1). It closes at the handover.
- **Incoming session:** start at the **repository root** with `/role-governance-engineer`. First: record your own session ID in the Sessions table below and mark `f1575f5d…` closed; then give the Principal the five-item report (mandate §11.2).
- **Nothing is in progress with the Governance Engineer** at the handover; everything open is listed under Pending and Waiting for.

## Where things stand (research track)

| Item | State | Evidence |
|---|---|---|
| Reading | S0001–S0200 read; **window C38 (S0201–S0204)** drafted as §8.31 and under review (reviewer W); the gate counts 4 of 25 towards the **S0225 consolidation** | `0612831ad`; pointer `f7504c6c9` |
| Register | 265 concepts: 52 VALIDATED, 117 PROVISIONAL, 96 INVALID (at S0200) | `0ae02e66b` |
| Consolidation S0182–S0200 | settled; planted-gap test 2 of 4 | `e5da8af5a`; D1 |
| Strategic design (Ch. 10) | **turns 1–5 settled** (`dca72c57f`); T10-16 (b) and the routed clarifications applied at the G1 settlement (`8e4b1a420`); all S0 | decision record |
| G1 theory turn (§9.18) | settled (`8e4b1a420`); model `ce_model.py` v22 reproduces (46 PASS, 0 FAIL, checked by the Governance Engineer); evidence kinds are **proposals, not adopted** | `8e4b1a420` |
| Principal's design decisions | T10-01 (b) promise; T10-05 (B) admission; T10-10 (1) own state; T10-16 (b) record owned by no area (designation with it; removal record-keeping, annulment standing); T9-12 (c) evidence shown, authorised counts; **T9-16 (b) + restore entry** (`4756be2f2`) | decision record |
| R2 `--scope` (D2) | done by the Professor | `d587cfaf5` |
| Brief template | in force from the S0225 consolidation | `bf75badfb` |

## Sessions (Claude Code session IDs; verified 2026-10-29 from each transcript's first message)

| Role | Session ID | Started from | State |
|---|---|---|---|
| Governance Engineer | `f1575f5d-96b1-404a-af96-797d354857e2` | the lane folder | **to be closed at the handover** |
| Governance Engineer (successor) | *(the incoming session records its ID here)* | repository root | — |
| Professor (Research Lead) | `16a0ac97-2df1-4d21-8fca-c43f5de5b1e2` | repository root | active |
| Verification Auditor | `76f3445f-0fdb-4fe5-833a-efe25aa62005` | repository root | idle (next: full rerun after the Ch. 10 revision) |
| Lead DDD Architect | `94ef3b1c-9499-4412-a177-082445a27076` | repository root | idle |
| Implementation Engineer | — | — | inactive until stage 1 |
| Independent Reviewers | — (a fresh `kos-lane-reviewer` per review) | — | per commission |

*Resume with `claude --resume <id>` from the folder the session was started in. A session ID identifies the conversation, not the role.*

## Roles (three statuses, from the strategy's roles table and the decision records)

| Role | Role defined | Mandate | Activation |
|---|---|---|---|
| Principal (human ARB) | yes | — | active |
| Governance Engineer | yes | revision 3 approved | active (handover pending) |
| Professor (Research Lead) | yes | Rulebook v1.4 + strategic-design mandate rev. 1 | active: reading; evidence questions one at a time |
| Independent Reviewers | yes | `REVIEWER-INSTRUCTIONS.md` + brief template + agent `kos-lane-reviewer` | per commission |
| Verification Auditor | yes | revision 3 approved (I-004) | idle |
| Lead DDD Architect | yes | revision 2 approved | part 1 done for turns 1–5; parts 2–3 not authorised |
| Implementation Engineer | yes | revision 2 approved | inactive until stage 1 |

## Pending (owner → action)

| # | Owner | Action |
|---|---|---|
| 1 | Principal | **fix the decision-record gap for the Lead DDD Architect and the Implementation Engineer** (both mandates list only the research record; their stage-1 authorisations will be in the implementation-track record): recommended yes, needed **before the stage-1 decision** |
| 2 | Professor | apply T9-16 (b) (model new version: unrelated grant shown not counted; scoped grant counts; restore after withdrawal); then **T9-17** (with the routed Impl evidence B-04, B-05), then adopting the evidence kinds, then naming T10-21 — each to the Principal one at a time |
| 3 | Governance Engineer | evaluate each question and settlement as it arrives (hashes; model re-run; scope) and explain it to the Principal in business terms |
| 4 | Governance Engineer | **check the S0225 consolidation briefs** against the template (G1–G7); no test there |
| 5 | Governance Engineer | **prepare the S0250 planted-gap test** (mandate §8.3), aimed at the classes missed at S0200 (ordinary undefined word; removed claim); its preparation checked by a separate session (§8.3a) before reviewers start |
| 6 | Professor | confirm the implementation-candidates list (`IMPLEMENTATION-CANDIDATES-S0200.md`) and answer its §4 point 2 (the estate's record is outside D) — before the stage-1 decision |
| 7 | Professor | Ch. 10 revision for T10-17–T10-21 (routed C items; naming) before any tactical design; then the Lead DDD Architect's review of it (bundled with the Ch. 10 changes at `8e4b1a420`), then the auditor's full rerun |

## Waiting for

- C38 settlement; the T9-16 application and the T9-17 question (Professor).

## Next milestones

- **S0225:** consolidation (Ch. 9 rotated in full); then the Principal's **stage-1 decision** (implementation track).
- **S0250:** consolidation with a planted-gap test.
