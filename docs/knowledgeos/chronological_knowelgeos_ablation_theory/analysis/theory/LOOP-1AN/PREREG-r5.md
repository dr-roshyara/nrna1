# 1an-r5: pre-registration (frozen before the first run)

- **Question:** does START's state guard {s} predict held-out decision clusters, where {s, t} could not (r4: 2/11 coverage, a lookup)?
- **Instrument:** `diag_1an_r5.py`: a fixed single guard, no refit, no voting; majority baseline; abstention reasons recorded.
- **Pre-freeze disclosure:** the self-test's toy expectation for the baseline count was wrong (3 of 5 toy folds tie); it was corrected before any real-data run. The instrument code is unchanged.

| Result | Consequence |
|---|---|
| {s} DIAGNOSTICALLY PREDICTIVE and {s,t} NOT SHOWN | t adds no predictive information on this data; START-s gains a **prediction-based formal diagnostic** (still formal, strict-data-only; not empirical replication) |
| {s} NOT SHOWN | START stays UNCLEAR; s = recurrence only |
| {s} has wrong > 0 | a counterexample; START-s recurrence is contradicted under prediction → record it |

**Sealed main-analyst expectation (bias check):** {s} predicts most s-known held-out events with 0 wrong and beats the baseline; git's s = UNK abstains. {s,t} NOT SHOWN. Moderate confidence.
