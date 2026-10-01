# 1bj: coarse binary core instrument (SCHEMA-CORE): pre-registered BEFORE the schema is written (human act F-LOG-0211)

## Instrument (specification; the wording is to be drafted)
Three fields per record, each YES / NO / UNKNOWN:
- **ACT_REPORTED:** the text reports that an act actually happened, or was attempted, refused, withdrawn or reversed, as a past or present event.
- **ACT_SELF_PERFORMED:** the span's own words perform an act (a performative: the text itself adopts / authorizes / withdraws / classifies …).
- **NORM_STATED:** the span states normative content: something is permitted, required, prohibited, authorized, eligible, or has a normative status.

**Rules carried over:**
- R-A1: never infer an act from a norm.
- R-A4: UNKNOWN when the span does not decide; a span with no available text is UNKNOWN on all three.

Fine-grained categories are not coded.

**Why three fields:** keeping ACT_REPORTED separate from ACT_SELF_PERFORMED means the schema does not decide the theory choice TC-1 (whether self-performance counts as an act). Each alternative stays computable.

## Drafting discipline
- The schema is written **without access to the records or sources**.
- Its examples come from one neutral fictional domain.
- The coder text is **≤ 1,000 words**.
- **One** review, which checks anchoring with the records. A FAIL gets one targeted repair; a second FAIL means option A (record the limitation).

## Test (fixed now)
- **Coders:** Claude Opus 5.5, Sonnet 5.5 and Haiku 4.5. Each is an isolated headless session outside the repo, given only the bundle (README, TASK, SCHEMA-CORE, RECORDS, SOURCES), with one attempt.
- **Pass:** pairwise Cohen κ ≥ **0.67** on **each** of the three fields, for **every** coder pair (3 pairs × 3 fields = 9 values). κ ≥ 0.80 is reported as "good".
- **If κ is undefined** (no variance): report the agreement proportion. The value does not count as a pass unless agreement ≥ 0.90 **and** the reviewer confirms the field is genuinely determinate.
- **Comparator:** a new script for these three fields that reuses the frozen `kappa` function. It is frozen and committed **before** any return.
- **Outcome rules:**
  - PASS → R3's R2-dependent items are re-derived on the core fields only;
  - FAIL → **option A**: record that the act/norm observation layer is not reliably codable on this corpus, at either grain.
- **Interpretation limit:** within-family only (F-LOG-0201).
