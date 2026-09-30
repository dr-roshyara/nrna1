# EG-6 final adversarial re-review, v3 (Subagent B)

| | |
|---|---|
| Kind | Independent adversarial review of `EG6-SPEC-A-v3.md` and the updated/new probes, responding to `EG6-REVIEW-B-v2.md` (F-01, F-02, and the minor attempt-1-domain point). Read-only, synthetic fixtures only. |
| Method | Reran the updated `retire_model.py` in full (all 10 crash points + 2 adversarial cases + 3 new EXDEV/st_dev cases). Reran `watchdog_sim.py`. Independently re-derived the exact binomial thresholds with high-precision `Decimal` arithmetic (a method wholly independent of the probe's own `lgamma`-based floating-point computation). Wrote and ran my own larger-replicate (6,000, vs. A's 2,000) Monte Carlo check of the one operationally critical row (q = 0.10) to test Monte Carlo precision at the boundary. |

## 0. Did round-2's findings get fixed?

**F-01 (crash-safety/device rule):** yes, comprehensively — not just patched but genuinely extended with the exact mechanism I asked for (per-rename `st_dev` check before any move in a step, `errno == EXDEV` caught and converted to a resumable class-X refusal). See below for the re-run confirmation.

**F-02 (post-canary systemic watchdog):** yes — W-SYS is a real, quantitatively-derived, simulated rule, not just a promise. See below for my independent verification of both the exact thresholds and the simulated operating characteristics.

**The minor "attempt-1 domain never reported standalone" point:** fixed exactly as asked, §3's added sentence is unambiguous ("reported only alongside... never presented standalone or as a corrected or improved value").

---

## 1. F-01 re-verification: crash-safety is now genuinely closed, including the partial-step case

Reran `retire_model.py` in full. All results match A's table exactly:

```
EXDEV at S2 (3rd ledger dir)     REFUSED (S2: EXDEV renaming OB9004-R7-L02S (class X; resumable)) | blocked=True | resume after cause removed=COMPLETE identical=True
EXDEV at S4 (archive)            REFUSED (S4: EXDEV renaming OB9004 (class X; resumable)) | blocked=True | resume after cause removed=COMPLETE identical=True
st_dev mismatch at S4            REFUSED (S4: OB9004 → target on another device (class X; nothing moved)) | archive moved before refusal=False | blocked=True | resume=COMPLETE identical=True
```

Plus the original 10/10 crash points and both adversarial cases (tamper, source-recreated) — all unchanged and green.

**Is refusing before any rename in a step enough?** I traced this rather than taking it on faith. `same_device_or_refuse` is called *once, upfront*, over every still-pending pair for the step, **before** the loop that performs the actual renames — so for the `st_dev`-mismatch case, the check correctly prevents *any* rename in that step, confirmed empirically by the probe's own extra assertion (`archive moved before refusal=False`). This closes the case cleanly: "nothing moved" is a real, checked guarantee, not just an assumption.

But the more interesting case is the true `EXDEV`-during-rename scenario, where the upfront `st_dev` check *passes* (devices genuinely match at check time) and the OS still raises `EXDEV` partway through the step — this is realistic (e.g., a bind mount inside a directory tree can behave this way even when the parent-level device ids match). I traced the "EXDEV at S2 (3rd ledger dir)" case by hand: with directories processed in sorted order, the first two ledger directories are genuinely renamed (moved) *before* the third one raises the monkeypatched `EXDEV`. At that point the retirement is a true partial-step state — two directories already moved, one failed mid-rename, two never attempted. I checked how resume handles this: on the next `retire()` call, the existing `RETIREMENT-INTENT.json` is reused (not re-inspected), and for each directory the three-way `if/elif/elif` in S2 (`isdir(s) and not exists(t)` / `isdir(s) and exists(t)` / `not exists(t)`) has no branch that matches an *already-moved* directory (source gone, target present) — so it silently falls through and the loop proceeds to the next directory, correctly skipping what's already done and completing only what remains. This is a slightly indirect way to express "skip if already moved" (via exhaustive non-matching of the three explicit branches, rather than an explicit fourth branch), but I confirmed it's correct, and the probe's own `identical=True` result for this exact scenario empirically confirms it produces the same final state as an uninterrupted run.

**Is the EXDEV-resume path truly byte-identical?** Yes, confirmed both by tracing the code and by the probe's own comparison against the clean-run baseline (`ref`) for all three new scenarios. This closes my round-1 concern comprehensively — not just for a hard process crash (tested since v2), but now also for the partial-step, error-during-rename case specifically (new in v3).

---

## 2. F-02 re-verification: W-SYS's numbers are independently confirmed, including the multiplicity question

**Exact thresholds, independently re-derived.** I wrote my own threshold computation using 60-digit-precision `Decimal` arithmetic — a method with no floating-point/`lgamma` dependency at all, completely independent of `watchdog_sim.py`'s own `log_binom_tail` implementation:

```
Q_STAR (Decimal) = 0.127711860598972011618665066468121886197934449131899128528278
n=20: threshold f>= 7  (p=0.00940147)
n=40: threshold f>= 11 (p=0.00976792)
n=100: threshold f>= 22 (p=0.00714132)
n=200: threshold f>= 38 (p=0.00783631)
```

This matches A's table, the orchestrator's own independently-verified values, **and** my own high-precision computation, three independent methods agreeing exactly — I'm confident there's no floating-point boundary bug in the threshold table.

**The repeated-looks / multiplicity question, addressed directly.** I traced `watchdog_sim.py`'s `run()` function specifically to answer this: it checks `triggers(n, f)` at *every* multiple of K = 20 throughout the *entire* simulated 396-batch, up-to-M=4-attempts-per-batch program run (not just once), and a simulated "stop" is recorded if *any* of these repeated checks fires. So the reported `P(stop | q)` already integrates over every look a real execution would perform — this is the methodologically correct way to validate a repeated-testing design (empirical simulation of the *realized* behavior, rather than trusting that a per-look α directly bounds the cumulative false-stop rate, which it does not in general for repeated significance testing).

I then went further and independently re-ran the simulation myself, from scratch, with **3× the replicate count** (6,000 vs. A's 2,000) for the one row that matters most operationally — q = 0.10, a "should stay safe" value since P(all 396 pass | q=0.10, M=4) = 0.961:

```
q=0.10: P(stop)=0.0043  SE~0.0008  95% CI ~[0.0027, 0.0060]  stops=26/6000
```

This is consistent with A's reported 0.006 (my point estimate is lower but my 95% CI's upper bound sits right at 0.006, and A's figure falls inside my CI) — reassuring, and it rules out the concern that A's 2,000-replicate figure was a low-count artifact that happens to look good. Both independent runs land comfortably under α_w = 0.01 even after fully accounting for repeated looks. **The multiplicity concern is empirically addressed, not just asserted.**

**One remaining wording gap (minor).** The rule text states α_w = 0.01 as "the exact one-sided test equivalent to... the Clopper–Pearson lower 99% bound," which correctly describes the *per-look* trigger threshold, but nothing in the §2.8 text itself says outright that this is *not* the same thing as the cumulative, whole-programme false-stop probability — that quantity is only established separately, empirically, in the operating-characteristics table. A governance reader skimming the rule text alone could reasonably (if incorrectly) read "α_w = 0.01" as "there's a 1% chance of a false stop." Recommend one sentence: *"α_w is a per-look trigger constant, not the cumulative false-stop probability across all repeated looks; the cumulative false-stop probability is established separately by the simulated operating characteristics above."* Cheap, removes an easy misreading.

**Density of the operating-characteristics sweep (minor).** The table samples q at 0.02, 0.05, 0.10, q\* (0.128), 0.16, 0.20, 0.30 — sparse between 0.10 and q\*, the exact region where a reader most wants confidence that P(stop) doesn't spike unexpectedly for some untested q in between. The pattern shown is smoothly increasing (0.000 → 0.000 → 0.006 → 0.050), which is reassuring, but it's an empirical spot-check at a few points, not a proven monotonicity result. Recommend either a couple more intermediate q values (e.g., 0.11, 0.115, 0.12) or a one-line analytic argument for monotonicity in q, purely for completeness — not because I found or suspect a problem.

---

## 3. RR-7 / W-SYS interaction: one genuine ambiguity, likely immaterial in practice

The spec states RR-7 (identical failure-string set on two consecutive attempts of *one* batch → class D) and W-SYS (the programme-level rule) are "both D-class stops," and separately that "classes D and S... are excluded from f." I traced through what this means for the *specific attempt* that triggers RR-7: does that attempt's own failure — which, before the RR-7 comparison, had some underlying class (most plausibly A1 or A2) — count toward W-SYS's `f`, or does it get swept into "D" and excluded?

The text doesn't say explicitly, and it matters slightly: `watchdog_sim.py`'s own model treats every attempt-level failure uniformly as contributing to `f` (it doesn't simulate RR-7 at all, by its own docstring's scope), so if the real system excludes RR-7-caught attempts from `f`, the simulation would be a mild over-count of `f` relative to the real system whenever RR-7 fires. I don't think this materially changes the operating-characteristics table, because RR-7 requires an *exact* repeated failure-string set — a narrower, presumably rarer condition than "any A1/A2 failure" — so its effect on the aggregate `f` count should be small. But it's a real specification gap, not just a modeling simplification I invented: recommend one clarifying sentence (I'd suggest: RR-7-triggering attempts are excluded from `f`, consistent with "D excluded," and this simplification should be noted explicitly in `watchdog_sim.py`'s own docstring rather than left implicit).

**Needs a HUMAN decision:** no — a specification-completeness note, foldable into HD-6.2 alongside the rest of the W-SYS text.

---

## 4. Selection risk from an early, mid-programme stop: handled by the existing machinery, but the dispatch-order assumption should be stated explicitly

I checked the concrete scenario the task named: a mid-programme W-SYS stop necessarily leaves whichever batches hadn't yet been dispatched unprocessed, and — per EP-01 §7 — every sampled label in those batches becomes NOT-ASSESSABLE, counted worst-case (discordant) in the primary bound. I confirmed this is **statistically sound regardless of *why* a label is NOT-ASSESSABLE**: the sample itself is drawn before any S5 output and is independent of dispatch order or any stopping event, so the worst-case bound remains a valid conservative bound on θ no matter which subset of the frame ends up unprocessed. An early stop makes the *bound* much less informative (most of the frame worst-cased), but that's the accepted, intended cost of stopping early rather than continuing a systemically broken run — the design's own "conservative direction" framing already says as much.

What I don't see stated anywhere is that **dispatch order itself must be sample-blind and content-blind** for this to hold up as more than a technically-valid-but-vacuous bound. If batches were ever dispatched in an order correlated with anything relevant to the estimand (say, by perceived difficulty, or by content characteristics that also correlate with discordance propensity), a mid-programme stop would leave a *systematically* non-random tail of the frame worst-cased, rather than an arbitrary one — the bound would remain technically valid (worst-case is worst-case regardless of the underlying reason), but the *practical* interpretation of "what the completed portion tells you about the whole programme" would be weaker than the reader might assume. I have no evidence dispatch order is *not* sample-blind — every other rule in this design (RR-1, RR-2, RR-6, W-SYS's own "mechanical; sample- and audit-blind" framing) repeatedly asserts exactly this property for every other mechanism, and manifest/label order is the standing convention used everywhere else in this programme (`L##` = manifest position) — so this is very likely already true in practice. I'm flagging it because it's the one place in the whole chain of rules where "sample- and content-blind" isn't stated for the one variable (dispatch order) that actually determines which labels get affected by an early stop.

**Recommendation:** one sentence in §2.8, alongside W-SYS's text: batches are dispatched in a fixed, pre-declared, content-blind order (e.g., manifest order), never reordered by expected difficulty, prior outcomes, or any other informative criterion.

**Needs a HUMAN decision:** no — a completeness statement, not a design change; foldable into HD-6.2.

---

## 5. Stocktake: anything still MATERIAL across v1 → v2 → v3?

Going through every prior finding by name:

| Finding | Status |
|---|---|
| v1 E-01 (crash-safety/atomicity) | **Resolved** — intent/complete design (v2), now also covers EXDEV/device-mismatch partial-step cases (v3), reverified this round |
| v1 E-02 (retry-conservatism interpretation risk) | **Resolved** — named mechanism + reporting requirements (v2), attempt-1-domain-never-standalone closed (v3) |
| v1 E-03 (Class A too broad) | **Resolved** — split into A1/A2 (v2), spot-checked tag-string mapping directly against code |
| v1 E-04 (attempt-aware state) | **Resolved** — mechanism specified (v2), confirmed feasible by direct reading of `p3b_s5_accept.py`/`p3b_s5_state.py` |
| v1 E-05 / EG-7 | **Resolved and separately implemented** — I reviewed the actual code change in a dedicated implementation review (`EG7-REVIEW-B.md`, verdict IMPL-ACCEPTABLE), independently reproducing the exact reported test failure signature by tracing the diff before comparing |
| v1 E-06 / EG-8 | **Resolved** — concrete G-LOG entry text with the correct recomputed sha256, ready to file |
| v2 F-01 (device rule/EXDEV untested) | **Resolved**, reverified this round |
| v2 F-02 (no post-canary watchdog) | **Resolved**, reverified this round including independent re-simulation |

No finding from any round remains open at MATERIAL severity. The new points this round (§§2–4 above: the α_w wording clarification, the RR-7/W-SYS counting ambiguity, the dispatch-order statement) are all one-sentence textual additions to the already-approved design, not code or mechanism changes, and none of them changes a number, a threshold, or a freeze.

---

## Verdict

**SPEC-ACCEPTABLE.**

The design has converged: every material finding from three rounds of adversarial review (mine) plus a separate implementation review of the one already-shipped piece (EG-7) has been substantively resolved, not just reworded, and I independently reproduced the key quantitative claims this round through methods wholly independent of A's own probes (high-precision `Decimal` threshold arithmetic; a from-scratch, larger-replicate Monte Carlo rerun of the one operationally critical watchdog row). I recommend three small, non-blocking textual additions before this goes to the human act — none require touching the mechanism, the thresholds, or any freeze:

1. One sentence clarifying that α_w = 0.01 is a per-look trigger constant, not the cumulative false-stop probability (which is established separately, empirically, by the operating-characteristics table).
2. One sentence on whether an RR-7-triggering attempt counts toward W-SYS's `f` (recommend: no, consistent with "D excluded"), noted explicitly rather than left implicit, and reflected in `watchdog_sim.py`'s docstring.
3. One sentence stating that batch dispatch order is fixed, pre-declared, and content-blind (e.g., manifest order) — the one variable in the whole rule set that determines which labels are affected by an early stop, and the one place "sample- and content-blind" isn't already stated explicitly even though it's almost certainly already true in practice.
