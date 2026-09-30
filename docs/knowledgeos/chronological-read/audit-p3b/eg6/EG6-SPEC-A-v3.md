# EG-6 specification v3: DELTA ONLY (F-01 device rule and EXDEV; F-02 post-canary watchdog)

| | |
|---|---|
| Kind | Design only; a delta on `EG6-SPEC-A-v2.md` (v1 and v2 kept). authority: generated. No repository file is changed. |
| Responds to | `EG6-REVIEW-B-v2.md`: F-01, F-02, and the minor point "the attempt-1 domain estimate is never reported standalone" |
| Probes | `probes/retire_model.py`, **updated**: a per-rename `st_dev` check and EXDEV handling, with 3 new tests · `probes/watchdog_sim.py`, **new**: exact thresholds and simulated operating characteristics |

## 1. F-01: device rule and EXDEV

**Replaces the v2 §1 S0 bullet** "archive and ledger on one filesystem (else REFUSED)":

> **Device rule.** Every rename stays inside its own root: ledger → ledger (`ledger-p3b-r2/<name>` →
> `ledger-p3b-r2/<B>-R7.A<m>/<name>`) and archive → archive (`<archive>/<B>` → `<archive>/<B>.A<m>`). The ledger and
> the archive may be on different devices; no rename crosses between them. **Before the first rename of S2 and of
> S4**, the tool checks, for every still-pending pair, `st_dev(source) == st_dev(parent of target)`. If any pair
> differs, the step is **REFUSED as class X and nothing is moved in that step**. If `os.rename` nevertheless raises
> `OSError` with `errno == EXDEV`, the step is REFUSED as class X; every rename done before it is complete and
> verified-by-resume, so the retirement stays **PENDING and resumable**. Any other `OSError` propagates (a crash, which
> is resumable by the same rules). Copy-and-delete is never used.

Consequences:
- The EXDEV failure is class **X** (environment), not S: no byte is changed and the intent record still holds.
- While a retirement is PENDING, `may_start_attempt` blocks attempt m+1. After the cause is removed (for example the target parent is re-mounted), `retire` resumes.

**[T] `retire_model.py`** (the previous 10/10 crash points and the 2 adversarial cases are unchanged and green):

| Test | Result |
|---|---|
| a simulated **EXDEV at S2** (`os.rename` monkeypatched to raise for the 3rd ledger directory) | REFUSED "S2: EXDEV … (class X; resumable)" · blocked = True · after the cause is removed: **COMPLETE, byte-identical final state** |
| a simulated **EXDEV at S4** (the archive rename) | REFUSED "S4: EXDEV … (class X; resumable)" · blocked = True · resume → **COMPLETE, identical** |
| a simulated **`st_dev` mismatch at S4** (the archive root reported on another device) | REFUSED "S4: … target on another device (class X; nothing moved)" · the archive was **not** moved before the refusal · blocked = True · resume → **COMPLETE, identical** |

**Test ids added** (implementation slice): T142 EXDEV at S2 · T143 EXDEV at S4 · T144 `st_dev` mismatch refused before any rename of the step.

## 2. F-02: post-canary systemic-failure watchdog

**Gap.** The canary rule covers only the canary. RR-7 covers only repetition *within one batch*. A shared defect that shows up as elevated failure across *different* batches (each below RR-7) would otherwise be seen only at the end, in E-3.

**Rule** (pre-registered; mechanical; sample- and audit-blind). It is added to the §2.8 policy:

> **Watchdog W-SYS.** Let *n* be the number of **completed post-canary attempts** (every attempt of every non-canary
> batch that has reached a verifier verdict or ended INCOMPLETE; attempts 1…M alike) and *f* the number of them that
> **failed with class X, A1 or A2**. (Classes D and S already stop S5 on their own; class H and U outcomes are not
> counted.) After every **K = 20** completed attempts, S5 **stops** if
> **P(Bin(n, q\*) ≥ f) ≤ α_w**, with **q\* = 0.1277** and **α_w = 0.01**. This is the exact one-sided test
> equivalent to "the Clopper–Pearson lower 99% bound on the per-attempt failure rate exceeds q\*". q\* is the
> per-attempt batch failure rate at which the frozen cap **M = 4** gives P(all 396 batches pass) = 0.90
> (q\* = (1 − 0.90^(1/396))^(1/4)). On a stop, no new attempt is dispatched; attempts already dispatched complete and
> are verified normally; the event is recorded as class **D (systemic)** and escalated to a human act. Resumption
> requires that act (a diagnosis; any instrument repair is a human gate per RR-6). Accepted objects stand.

**Exact stop thresholds** (printed by the tool; `watchdog_sim.py`):

| n (completed attempts) | 20 | 40 | 100 | 200 | 400 | 800 |
|---|---:|---:|---:|---:|---:|---:|
| STOP iff f ≥ | 7 | 11 | 22 | 38 | 68 | 126 |
| (q̂ ≥) | 0.350 | 0.275 | 0.220 | 0.190 | 0.170 | 0.158 |

The exact test at each look prevents early noise: a small n needs a large q̂.

**[T] Operating characteristics** (2,000 simulated programmes per row; 396 batches, up to M = 4 attempts each, independent attempts):

| True per-attempt q | 0.02 | 0.05 | 0.10 | 0.128 (= q\*) | 0.16 | 0.20 | 0.30 |
|---|---:|---:|---:|---:|---:|---:|---:|
| P(W-SYS stops) | 0.000 | 0.000 | 0.006 | 0.050 | 0.483 | 0.977 | 1.000 |
| median attempts at the stop | — | — | 20 | 120 | 220 | 120 | 40 |
| P(all pass \| q, M = 4) | 1.000 | 0.998 | 0.961 | 0.900 | 0.771 | 0.530 | 0.040 |

Reading the table:
- Where M = 4 keeps PROGRAM-ACCEPTED likely (q ≤ 0.10), false stops are rare (≤ 0.6%).
- Where the programme is unlikely to be accepted (q ≥ 0.20), W-SYS stops early with near certainty: at a median of about 120 attempts, i.e. before about 100 of the 394 non-canary batches.
- Correlated attempts (hard batches fail repeatedly) inflate *f* and make W-SYS stop **sooner**, which is the conservative direction.

**Relations to the other rules:**
- **The canary stop rule** (B1:96; RB §3: ≥ 6 of 11 runs FAIL, or the same W-code in ≥ 2 runs) governs the canary batches and the canary gate. W-SYS starts with the first non-canary attempt; canary attempts are not counted.
- **RR-7** (an identical failure-string set on two consecutive attempts of one batch → D) is the per-batch rule. W-SYS is the programme-level rule. Both are D-class stops.
- **D and S outcomes** stop on their own and are excluded from *f*, so the count is not double-counted.
- **Constants** (K = 20, α_w = 0.01, q\* = 0.1277, the counted classes) are fixed before the canary together with M = 4. They are never adjusted after outputs exist.

**Role.** W-SYS is a **stopping rule for execution only, with no inferential role**.
- It enters no estimator, bound or estimand.
- It reads only per-attempt class counts from the state history: no sample, no audit result, no content.
- It changes no freeze.
- If a stop leaves batches without an accepted object, their labels are NOT-ASSESSABLE under the existing EP-01 §7 rule (worst case), and the stop itself is reported in E-3.

**Test ids added:**

| # | Test |
|---|---|
| T145 | the threshold table above is reproduced exactly by the tool's function |
| T146 | a synthetic history with f at the threshold − 1 / at the threshold at n = 100 → continue / STOP (class D recorded, no new attempt admitted) |
| T147 | canary attempts, D, S, H and U outcomes are not counted |
| T148 | static: the watchdog reads no sample or audit artifact |

## 3. Minor: the domain estimate is never reported standalone

This sentence is added to the v2 §6 policy text:

> The attempt-1 domain estimates of θ_D and θ_A are reported **only alongside** the primary accepted-population
> quantities, labelled exploratory, and are never presented standalone or as a corrected or improved value.

## 4. Human decisions

No new decision. F-01 folds into **HD-6.1** (tests first: T142–T144). W-SYS and the §3 sentence fold into **HD-6.2** (the pre-registered policy text). Recommended constants: **K = 20, α_w = 0.01, q\* = 0.1277**, tied to **M = 4 / P(all pass) = 0.90**.

**Traceability:** v2 §1 S0, §2, §6 · `EG6-REVIEW-B-v2.md` F-01, F-02 · B1:96 · RB §3 · EP-01 §7 · probes `retire_model.py` (updated), `watchdog_sim.py`.
