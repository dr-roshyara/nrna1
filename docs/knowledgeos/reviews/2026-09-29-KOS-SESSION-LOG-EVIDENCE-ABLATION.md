# Session-Log Evidence Ablation — Full 379-Commit Population

**Date:** 2026-09-29. Corpus is evidence, not authority. Does not activate the hook, does not
finalize the commit format, does not build EKS/PKS, does not train ML, does not freeze the Kernel.

## 1 · Research question

> Does independent session-log evidence add determinable information to the existing evidence
> model, measured as `Δ Determination` over the same commits, not as raw corroboration prevalence?

## 2 · Previous evidence (recap, correction accepted)

The prior document's `26/27 = 96.3%` figure measures `P(SessionLogCorroborates | TEMPORAL_MISMATCH)`
— conditional prevalence, not `P(correct determination | corroboration)` and not `P(determination
improves | corroboration)`. That distinction, correctly raised, drives this experiment.

## 3 · Formal evidence predicates

```
SessionLogMentions(date, w)      -- S1: work item w appears >=1x in .claude/sessions/<date>.md
SessionLogSubstantive(date, w)   -- S2: S1 AND mention count >= 2 (a disclosed, weak proxy for
                                    "more than one incidental reference" -- this repo's session
                                    logs are not consistently per-entry timestamped, so a true
                                    temporal-match predicate cannot be built more precisely)
SessionLogArtifactMatch(date,w,c)-- S3: S1 AND >=1 of commit c's changed file paths also appears
                                    as a string in that date's session log
```

Explicit semantics: `SessionLogMentions` supports `CorroboratedWorkItemContext`, nothing more. None
of S1/S2/S3 imply `ProducedBy(commit, session)`, `ExecutedBy(session, actor)`, or `Authorized(commit)`
— kept strictly separate throughout.

## 4 · Experiment design

Augmentation rule (deliberately narrow): `IF baseline == TEMPORAL_MISMATCH AND S2(date, work_item):
augmented = CONFIRMED_VIA_SESSION_LOG (a distinct label from citation-based CONFIRMED); ELSE:
unchanged`. Deliberately does **not** touch `UNCITED` (would require a new, separately-validated
attribution mechanism — reopens the extractor-bias question, out of scope) or already-`CONFIRMED`
cases (testing there would be circular).

## 5 · TDD evidence

Predictions recorded in writing (scratchpad, `predictions_s1s2s3.txt`) before any code existed.
`test_session_log_evidence.py` written and run against a nonexistent module first — **confirmed
RED** (`ModuleNotFoundError`). `session_log_evidence.py` implemented; **confirmed GREEN**, 9/9 unit
tests passing. Genuinely test-first this round, correcting both prior disclosed deviations.

## 6 · Full 379-commit results

| | Count |
|---|---|
| Total commits | 379 |
| S1 (mentions) | 233 |
| S2 (substantive, ≥2 mentions) | 226 |
| S3 (artifact match) | 80 |
| Baseline `TEMPORAL_MISMATCH` | 27 |
| **Classification changes** | **26** |

26/27 flip to `CONFIRMED_VIA_SESSION_LOG`; the 1 exception is the already-known `1a1d006b1`
(the meta/cross-cutting commit with 0 corroboration, correctly excluded again). Matches the
pre-recorded prediction closely.

## 7 · Classification changes — critical clustering correction

**The 26 changes are NOT 26 independent trials.** They collapse to **6 distinct `(work_item, date)`
governance narratives**:

| Cluster | Commits changed |
|---|---|
| `KOS-CONTRACT-NEUTRALITY-001`, 2026-09-29 | 2 |
| `KOS-ARCH-BASELINE-001`, 2026-08-21 | 1 |
| `KOS-ATTR-ARCH-001`, 2026-08-17 | 4 |
| `KOS-GOV-ATTRIBUTION-001`, 2026-08-16 | 4 |
| `KOS-SESSION-DISCOVERY-001`, 2026-08-15 | 10 |
| `KOS-SESSION-DISCOVERY-001`, 2026-08-14 | 5 |

Treating this as "26 replications" would overstate the finding by roughly 4×. The real replication
count for the underlying phenomenon (session-log corroboration resolving a temporal mismatch) is
**6**, several of which share the same work item across two adjacent days.

## 8 · Changed-case validation

| Cluster | Verification method | Verdict |
|---|---|---|
| `KOS-CONTRACT-NEUTRALITY-001`/09-29 (via `6b983bc12`) | Independent blind double-review (prior round) | `VALID_RESOLUTION` — unanimous `AUTHORIZED` |
| `KOS-ARCH-BASELINE-001`/08-21 (via `480207d66`) | Independent blind double-review (prior round) | `VALID_RESOLUTION` — adjudicated `AUTHORIZED_BY_ALTERNATE_MECHANISM` |
| `KOS-ATTR-ARCH-001`/08-17 (via `386942459`) | Independent blind double-review (prior round) | `VALID_RESOLUTION` — unanimous `AUTHORIZED` |
| `KOS-GOV-ATTRIBUTION-001`/08-16 (spot-check `487fce741`) | Direct primary-source read (commit body + ledger grant status) this round | `VALID_RESOLUTION` — real `G-KOS-ATTR-ARCH` grant, `AUTHORIZED`, commit is a verbatim PO/ARB-acceptance registration |
| `KOS-SESSION-DISCOVERY-001`/08-15 (spot-check `b96374c8f`) | Direct primary-source read this round | **Nuanced**: commit explicitly states *"No authorization sought and none needed"* — a `NOT_APPLICABLE`-shaped case, not a clean grant-backed `AUTHORIZED`. The binary rule's `CONFIRMED_VIA_SESSION_LOG` output cannot distinguish this from a true grant confirmation — **a real, disclosed limitation of the rule as implemented**, not a false resolution (the underlying governance activity is legitimate either way), but the label is coarser than the corpus's real nuance |
| `KOS-SESSION-DISCOVERY-001`/08-14 | Not independently spot-checked this round (same work item/mechanism as 08-15, already checked) | Inherits 08-15's finding by mechanism identity, not independently verified |

**No `FALSE_RESOLUTION`, `WORK_ITEM_MISATTRIBUTION`, or `CIRCULAR_EVIDENCE` found** in the 5 of 6
clusters checked (3 by independent blind review, 2 by direct primary-source spot-check). One
`REPRESENTATION_ERROR`-adjacent finding: the rule's binary output loses the `AUTHORIZED` vs.
`NOT_APPLICABLE` distinction that a fuller model would need — recorded as `UNKNOWN` whether this
matters for downstream use until such a distinction is actually needed.

## 9 · Independence / circularity analysis

Direct excerpt comparison (not assumed): `487fce741`'s commit body ("The act is registered verbatim.
The engine has no ACCEPT transition...") and `b96374c8f`'s ("No authorization sought and none
needed... manufacturing governance ceremony during an observation period would contaminate the very
effort...") are substantively different in content and reasoning from what a session log would need
to independently corroborate — and, checked directly, the session logs for these dates contain
longer, differently-phrased governance narrative, not a copy of the commit subject line.
Classification: **`INDEPENDENT`** for the 2 spot-checked cases (not `DERIVED_FROM_SAME_SOURCE`) —
both artifacts appear to be separately authored records of the same underlying real event, which is
exactly the kind of corroboration that has evidentiary value, as opposed to one artifact simply
echoing the other.

## 10 · Statistical results

`ResolutionRate = valid_resolutions / baseline_TEMPORAL_MISMATCH = 26/27` **at the raw-commit level**,
but **6/6 clusters resolved (with 1 nuanced) at the independent-narrative level** — the honest
denominator. No confidence interval computed for n=6 (too small for anything beyond exact counts,
consistent with this whole research line's discipline against manufacturing precision from tiny
samples). No accuracy/precision/recall figure computed — no independent ground truth exists for 3 of
the 6 clusters (only 3 have blind-review confirmation; the other 3 are direct-primary-source spot
checks by this same researcher, not independent review).

## 11 · Logic / implication status

| Implication | Status |
|---|---|
| `SessionLogSubstantive(date,w) → Determination changes from TEMPORAL_MISMATCH` | `SUPPORTED` — 26/26 qualifying cases changed, by rule construction (not surprising on its own) |
| `SessionLogSubstantive(date,w) → CorrectDetermination` | `SUPPORTED`, n=6 independent clusters (5 clean, 1 nuanced), **not** `CONFIRMED` at population scale — only 3/6 have independent verification |
| `SessionLogMentions(w,t) → ProducedBy(commit,session)` | `UNTESTABLE`, unchanged from prior document |
| `SessionLogCorroborates(w,t) → Authorized(commit)` | `NOT SUPPORTED` as a strict implication — `b96374c8f` shows corroboration can also mean "no authorization needed," not only "authorized" |
| `EvidenceAdded → CorrectDecision` | `NOT SUPPORTED` as a general law — `SUPPORTED` only for the specific, narrow, verified subset tested here |

## 12 · Governance-mechanism implications

The `b96374c8f` nuance (§8) is directly relevant to the still-`NOT FALSIFIED` "preceding human
statement" invariant: this case shows authority can be **explicitly declared unnecessary** for a
given act ("no authorization sought and none needed"), which is a real, distinct category the
invariant's current wording doesn't cleanly cover — not a counterexample to the invariant (there was
still a preceding human-attributed governance narrative), but a reminder that `AUTHORIZED` and
`NOT_APPLICABLE` need to stay distinguishable, consistent with earlier findings (`REF-006`).

## 13 · Kernel implications

**None promoted.** `Evidence Fusion`/`Corroboration` remains `HYPOTHESIS`, strengthened by real
(if clustered, n=6) evidence rather than by the raw 26/27 count. Per the explicit instruction, no
concept is promoted merely because it appears repeatedly or because this experiment produced a
positive result.

## 14 · EKS/PKS implications

None. `OQ-11` untouched. The `Evidence Sources → Fusion → Determination` sketch remains `HYPOTHESIS`,
unreplicated outside this one corpus/mechanism family.

## 15 · UNKNOWN

Whether the same 6-cluster pattern would replicate on a different work-item family or time period —
**not tested this round** (the redirect's step 6, "test on another independent sample," is the next
step, not this one). Whether the `AUTHORIZED`/`NOT_APPLICABLE` distinction lost by the binary rule
matters for any real downstream use. Whether `KOS-SESSION-DISCOVERY-001`/08-14's 5 commits would
independently confirm or complicate the 08-15 finding if actually blind-reviewed rather than
inherited by mechanism identity.

## 16 · Next smallest decisive experiment

Per the redirect's own step 6: **independent replication on a different work-item family.** Pick the
next-largest `TEMPORAL_MISMATCH`-adjacent pattern **not** from `KOS-SESSION-DISCOVERY-001` or the
3 already-blind-reviewed clusters (all 27 baseline cases are already accounted for by the 6 clusters
identified here, so a genuinely new family requires either new commits accumulating over time or
relaxing the `TEMPORAL_MISMATCH`-only scope to test `UNCITED` cases against a *separately validated*
attribution mechanism — a larger, more careful next step, not attempted here to avoid reopening the
extractor-bias question without dedicated validation).

---

**Traceability:** `2026-09-29-KOS-COMMIT-GOVERNANCE-CONTROLLED-EXPERIMENT.md` (source of the 96.3%
prevalence figure and the statistical correction this document acts on) ·
`session_log_evidence.py`/`test_session_log_evidence.py` (scratchpad, genuinely RED-first) ·
round-1/round-2 blind-review reference sets (source of the 3 independently-verified clusters) ·
`ablation3_results.json` (population reused unmodified).
