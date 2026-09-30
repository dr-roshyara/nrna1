# Task (frozen): repair a specification, and freeze two search protocols, before any evidence is collected

Use ONLY the files here:
- `SPEC-1bb.md` / `.json`: the specification to repair;
- `REVIEW-1bb.md`: the review with defects **D1–D14**;
- `HYPOTHESES.md`, `IDENTIFIABILITY.json`, `CONFORMANCE-CASE.json`, `STATE.md`: the inputs of the original spec;
- `HUMAN-DECISIONS.md`: what is authorized.

No commands, no web. **Collect nothing.**

## Part A: 1bb-r1, the corrected specification
For **each** defect D1–D14, give:
- (1) the defect, quoted from REVIEW-1bb;
- (2) the correction;
- (3) why the correction is logically sufficient;
- (4) whether it changes a scientific prediction, and which one;
- (5) the corrected rule, stated so it can be frozen.

Then write the **complete corrected specification**. Keep the valid parts unchanged, and mark every changed item. It must:
- explicitly handle **D1**: an observation explainable by both hypotheses is not a discriminator. State what additional **independent observable of the norm in force** is required for each prediction, or declare the prediction NON-DISCRIMINATING without it;
- keep the six distinctions in HUMAN-DECISIONS.md separate.

## Part B: the frozen protocol for the rank-0 WP-4C-1 check (to be executed later by deterministic commands, then reviewed)
Specify:
- (1) the channels searched (git history of this repository; which refs; which paths, if any; commit messages, trailers, diffs);
- (2) the target: how "WP-4C-1" is matched (identifiers, aliases permitted by the sources, and what is excluded);
- (3) what counts as an **execution trace** of WP-4C-1 work, vs mentions, plans, rulings or documentation;
- (4) the timestamps to record (committer date vs author date) and what each can and cannot establish under M-TIME;
- (5) what counts as **absence**, and why absence is NOT-EVIDENCED-IN-CAPTURE unless completeness is shown;
- (6) the search window, with a reason;
- (7) the exact commands (e.g. `git log --all --format=... -G/--grep ...`) and how their output is hashed;
- (8) the worker / reviewer procedure;
- (9) the output labels (RECORDING_ORDER / SOURCE_STATED_ORDER / INFERRED_ORDER / NOT_IDENTIFIABLE).

## Part C: the frozen protocol for the norm-status-channel scoping search
Specify how to answer the 8 scoping questions **without broad archaeology**: which candidate channels to check for **existence only** (e.g. repository configuration, CI definitions, hooks, audit or status files), by which exact commands, and the criteria for independence and completeness.

Write **SPEC-r1.md**, **SPEC-r1.json** ({defects: [{id, defect, correction, sufficiency, prediction_change, frozen_rule}], spec: {...}, rank0_protocol: {...}, scoping_protocol: {...}}) and **CHANGELOG.md** (every change against 1bb).
