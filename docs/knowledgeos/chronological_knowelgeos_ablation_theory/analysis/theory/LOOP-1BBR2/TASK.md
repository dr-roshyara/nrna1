# Task (frozen): a targeted repair (r2) of a failed specification and its search protocols

Use ONLY the files here:
- `SPEC-r1.md` / `.json`: the version to repair;
- `REVIEW-r1.md` / `.json`: its review, verdict **FAIL**;
- `ENVIRONMENT.md`;
- `HUMAN-DECISIONS.md`, `HYPOTHESES.md`, `IDENTIFIABILITY.json`, `CONFORMANCE-CASE.json`, `STATE.md`.

No commands, no web. **Collect nothing.**

**Scope: repair ONLY what the r1 review marks as failing.** Leave everything marked FIXED unchanged.
1. The PARTIAL defects **D1, D2, D5, D8, D9**.
2. The new defects **N1–N4** (and any further new defects in REVIEW-r1).
3. The rank-0 protocol issues **R0-*** that are marked blocking, and the others where cheap.
4. The scoping issues **SC-1..SC-5**.

For each item give:
- (1) the defect;
- (2) the correction;
- (3) why it is logically sufficient;
- (4) any change of prediction;
- (5) the frozen rule.

**Symmetry requirement (the core of N1–N3):** a premise that **either** hypothesis could explain (behaviour; the absence of an act; the maker's records) may never FIRE a falsifier of **either** hypothesis. Such premises grade DISJUNCTIVE. Only an **independent norm-state observable (NIF-A)** can fire. **Apply this identically to H-D and H-B**, and show the check in a table: for each falsifier, list its premises, whether each is independent, and which hypothesis each presupposes.

**Rank-0 protocol:** rewrite it so that it is executable in the environment described in ENVIRONMENT.md:
- a working clone;
- local refs only;
- no host access;
- read-only;
- deterministic;
- explicit handling of expected non-zero exits.

Declare the capture as the local ref set, with **completeness NOT shown**, so that absence = NOT-EVIDENCED-IN-CAPTURE. Specify the WP-4C-1 matching (identifier variants, Unicode hyphens, ref names, merges) and the trace-vs-mention rule precisely. Give the **exact shell script**.

**Scoping protocol:**
- make every outcome reachable, or label it NOT_DETERMINABLE_BY_EXISTENCE_CHECK;
- report "not determinable" as such, never as a negative;
- cover paths that existed during the window (history), not only the current tree;
- output **no personal identities** (no names or emails; counts only).

Give the exact shell script.

Write **SPEC-r2.md**, **SPEC-r2.json** ({repairs: [...], symmetry_table: [...], spec_changes: [...], rank0_protocol: {..., script}, scoping_protocol: {..., script}}) and **CHANGELOG-r2.md**.
