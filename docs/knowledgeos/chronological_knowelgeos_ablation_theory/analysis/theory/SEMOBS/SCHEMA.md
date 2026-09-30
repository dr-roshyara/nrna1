# Semantic-observation schema v1 (frozen before `semobs.py` runs)

| | |
|---|---|
| Status | research instrument, not canonical. Authority: none |
| Purpose | separate **what happened** (the semantic layer) from **how a document describes it** (the realization layer), so that genre and wording differences do not masquerade as theory differences (the lesson of F-LOG-0140) |

## 1. One semantic observation = one historical act (never one textual mention)
- `event_id`: the act.
- `cluster`: its **independence cluster**, i.e. the decision event it belongs to. Several mentions or copies of one decision share a cluster, and so do the items of one decision (e.g. the R-36 promotion matrix).
- `anchors`: source path + line(s).
- **Semantic fields:**
  - `o` operation · `r` route · `a` actor/authority · `k` object kind · `t` target/scope · `s` state before · `e` evidence condition · `x` exception · `h` history summary · `c` role conformance;
  - `outcome` ∈ {PERFORMED, REFUSED, NOT-IN-FORCE};
  - `ground` ∈ {RULE, CHOICE, n/a}.
- **Realization fields** (never used as theory variables):
  - `genre` ∈ {register-row, session-log, plan, ADR, git};
  - `speech_act` ∈ {performed, reported, self-restraint, claim-revision};
  - `voice` ∈ {active, passive};
  - `authority_mention` ∈ {named, implied-by-host, absent}.
- **Epistemic basis per semantic field:** `SOURCE` (stated) · `DERIVED` (follows from a stated fact by an explicit rule) · `UNKNOWN`. **A field that the source does not establish is `UNK`. Nothing is filled in silently.**

## 2. Rules
- **R1, applicability.** A field is compared only if it is *applicable* to the operation (table below). Non-applicable fields are `n/a` and are ignored.
- **R2, identity.** Two events about the **same object** share that object's intrinsic fields (k, c) by identity, even if the value itself is unknown. This is recorded as `same:<object>`.
- **R3, witness strength.** A pair of events with opposite RULE-grounded / PERFORMED outcomes that differs in exactly one applicable field x:
  - is a **STRICT witness** for x if every other applicable field is known and equal;
  - is a **POSSIBLE witness** if every other applicable field is equal or `UNK`.

  CHOICE-grounded refusals never enter legality witnesses.
- **R4, independence.** The number of independent witnesses for x = the number of **distinct cluster-pairs** among its strict witnesses. A within-cluster contrast counts once, as its cluster.
- **R5, verdicts.**
  - ≥ 2 independent strict witnesses: **EMPIRICALLY SUPPORTED**;
  - 1: **WEAK**;
  - 0 strict but ≥ 1 possible: **NOT DEMONSTRATED (possible)**;
  - none: **NOT DEMONSTRATED**.
  - **Never "not necessary".**

## 3. Applicability of fields by operation (frozen)

| Operation | Applicable fields (besides o, r) |
|---|---|
| ADOPT | a, k, s, t, c |
| AUTHORIZE-IMPL, AUTHORIZE-PLAN | a, k, s, t |
| REGISTER | a, k |
| OPEN-WORK | a, k, t |
| START | a, k, s, t |
| RAISE | a, k, s, t, e, x |
| ASSIGN-ID | k, s, h |
| SUPERSEDE | a, k, e, x |
| ANNOTATE | a, k |
