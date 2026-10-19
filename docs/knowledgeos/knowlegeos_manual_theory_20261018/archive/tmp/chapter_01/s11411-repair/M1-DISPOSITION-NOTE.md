# M1 disposition note (2026-10-18)

**Status of candidate M1: DEFECTIVE. Not the current repaired model.**

## Basis
- The independent review (`REVIEW-RECORD.md`) found 21 findings. All are accepted by the author.
- The author reproduced five of the reviewer's counterexamples in the author's own encoding (`review_checks.py`): F-2, F-3, F-8, F-14 and F-15.

## What remains valid in the M1 package (`S11411-BOUNDED-REPAIR-PROPOSAL.md`)
- The baseline verification (§2).
- The reconstruction of v1 (§3).
- The confirmation of v1's six defects (§§4–5), with the reviewer's refinements.

## What is defective as written
- The candidate model (§6, `M1-candidate-model.md`).
- M1-T4, Property 2′ and Property 4.
- The decision list (§10).
- The encoding evidence (§7), which hid eight defects.

## What survives the review
- **M1-T1:** existence and uniqueness of the least fixed point.
- **M1-T3:** stratification under own views, with the reviewer's correction F-16.
- **M1-T2:** existence only.

## The decision brief (`DECISION-BRIEF-D1-D7.md`)
- It remains **NOT FOR DECISION** until it is reconciled with reserved decisions 1–6 (Phase D).

## How the historical record is kept
- These documents are not otherwise edited: the proposal, `M1-candidate-model.md`, `m1.py`, `cases_m1.py` and `REVIEW-RECORD.md`.
- One correction is disclosed. A "superseded in part" banner was added to the proposal's header before the Principal's preservation instruction arrived. It was then removed again, so the header is restored and this note carries the disposition instead.
