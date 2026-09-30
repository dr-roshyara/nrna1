# EG-6/EG-8 package faithfulness check (Subagent B)

| | |
|---|---|
| Kind | Faithfulness check of §13–§15 of `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md` against my own EG6-SPEC-A v1–v3, EG6-REVIEW-B v1–v3, and probe outputs. Read-only. |
| Method | Read §13–§15 line by line against the working papers now copied to `audit-p3b/eg6/`, re-verified two specific factual claims directly against the underlying files (EP-01's sha256, Freeze-2's actual text) rather than trusting the package's own assertion. |

## Overall

Almost everything in §13–§15 is accurate, precisely worded, and traceable to a specific working paper or probe result — including all seven RR rules, the M=4 arithmetic (independently re-checked: matches my own rerun exactly, 0.9612≈0.96 vs 0.6729≈0.67), every W-SYS constant and threshold (all six thresholds independently verified this round by two methods; the reported simulated rates 0.006/0.0043/0.977 match exactly what I reported), and all three of my v3 text additions (the α_w-is-per-look sentence adopted verbatim, the RR-7/W-SYS exclusion adopted exactly as I recommended, and the dispatch-order statement — see the wording note below). Two findings require an edit; everything else checks out.

---

## Finding 1 — MATERIAL — §14's classes table omits class U (undetermined) and "unknown tags default to D" misstates one specific, named tag

§14's table lists exactly six classes (X, A1, A2, H, D, S) and states "unknown tags default to D." But `EG6-SPEC-A-v2.md` §4's classifier table has a **seventh, distinct row** that the package's summary drops entirely:

> `no matching row, or R7-W UNVERIFIABLE-WITNESS alone | no re-run: restore the archive and re-verify (U); an unknown string → D (fail closed)`

This is not the same thing as "unknown → D." `UNVERIFIABLE-WITNESS` is a specific, recognized tag (the archive is absent, per `p3b_s5_r7_verify.py`'s own `w_undetermined` path, which I traced directly across the EG-5/EG-6 rounds) that maps to a **third kind of resolution** — neither "re-run" (X/A1/A2) nor "STOP for a human act" (D/S) — but "restore the archive and re-verify," a mechanical recovery of the *verification step itself*, not of the attempt. Only a genuinely *unrecognized* string defaults to D. As written, §14's "unknown tags default to D" would lead a reader to believe an archive-absent (UNVERIFIABLE-WITNESS) outcome triggers a D-class human stop, which is wrong per the reviewed spec.

**Exact edit:** add a seventh row to §14's classes table —

```
| U | undetermined (archive absent; UNVERIFIABLE-WITNESS) | no re-run: restore the archive and re-verify |
```

— and change "unknown tags default to D" to "unrecognized tags default to D; `UNVERIFIABLE-WITNESS` specifically is class U (no re-run, restore and re-verify), not an unknown tag."

**Needs a HUMAN decision:** no — this is a transcription-completeness fix against an already-reviewed spec, not a new design question.

---

## Finding 2 — MATERIAL — §15's claim "the Freeze 2 record never mentions EP-01" is factually wrong; the true, established claim is narrower

I re-verified this directly rather than trusting the package's restatement of my own finding. `grep -n "EP-01" audit-p3b/20260928_FREEZE-2-PROPOSAL.md` returns **three hits**, including Freeze-2's own closing Traceability line: `**Traceability:** G-LOG-0094/0095/0096 · EP-01 · B1 v2 · ...`. So Freeze-2 does name EP-01, more than once.

What is actually true — and what my own `EG6-REVIEW-B.md` (E-06) and `EG6-REVIEW-B-v2.md` established, worded carefully to avoid exactly this overstatement — is narrower and more precise: Freeze-2's header **"Rests on"** row cites explicit sha256 values for B1 v2 (`d6b23886…`) and `prereg_v1.py` (`eb6291ab…`), but **never gives EP-01 a sha256 anywhere**, including in the Traceability line that names it (which also lists several other items with no sha, so it isn't a sha-binding list at all). The material gap is the **missing sha-binding**, not an absence of the name — B1 v2 already names EP-01 as its "Base" in its own header, so EP-01 being *mentioned* was never in question; the risk EG-8 identifies is specifically that its *bytes* are never hash-bound anywhere in the executed governance chain.

**Exact edit:** replace "the Freeze 2 record never mentions EP-01" with "the Freeze 2 record cites EP-01 by name (its Traceability line, and inline prose) but never binds its sha256 — unlike B1 v2 and `prereg_v1.py`, which the same document's 'Rests on' row does sha-bind." The rest of §15 (the sha256 value, the remedy, the "before the canary" timing, the process-deviation note) is accurate as written — I independently re-verified the sha256 (`sha256sum` on the actual file) and it matches `f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa` exactly, and confirmed the file is still genuinely untracked (`git status --porcelain` → `??`).

**Needs a HUMAN decision:** no — a factual-precision fix; it does not change the recommended remedy or its urgency.

---

## Note (not an error) — "outcome-blind" vs. my own "content-blind" suggestion: confirmed correct, recommend one added clause explaining why

My `EG6-REVIEW-B-v3.md` recommended stating that dispatch order is "content-blind." §14 instead says dispatch order is the frozen `P3B-STATE batch_order`, "fixed before any S5 output and... independent of every S5 result," and calls the resulting NOT-ASSESSABLE set "outcome-blind" — deliberately *not* reusing my "content-blind" wording. Per the task's own note, `batch_order` is derived from pre-output metadata packing, so it is **not** content-blind in the literal sense (it can depend on characteristics of the batch's content, such as size-based packing), even though it never depends on any S5 *result*.

I checked whether this substitution weakens my original point or is simply more accurate, and it's the latter: "outcome-blind" is exactly the property that actually supports the statistical-validity argument I made (the worst-case bound stays conservative because the sample and dispatch order are independent of S5 *results*, regardless of dispatch order's relationship to pre-existing content metadata) — claiming "content-blind" would have been an overclaim the orchestrator was right not to make. So this is not a faithfulness problem; it's a correct refinement of my own recommendation. The one thing I'd still add: my *separate*, narrower practical point — that if `batch_order` does correlate with something like batch size/complexity, an early stop's NOT-ASSESSABLE set, while still yielding a statistically valid bound, is not a uniformly random sample of the unprocessed frame — isn't currently carried into §14 at all. Recommend one clause: *"batch_order derives from pre-output packing metadata, not a random draw, so an early-stop bound, while statistically conservative regardless, should not be read as representative of a random subsample of the unprocessed tail."* Optional, non-blocking.

---

## Minor completeness notes (not misrepresentations, no edit required)

- §14's one-line summary of `may_start_attempt` ("blocks work while a retirement is PENDING or the archive path is non-empty") omits the guard's third condition from `EG6-SPEC-A-v3.md` §1 (any active planned-run or assembly directory still existing). This *understates* the guard's coverage, not overstates it — benign.
- The W-SYS threshold list in §14 gives five of the six independently-verified rows (skips n = 40 → f ≥ 11). A subset, not a contradiction.

---

## Verdict

**NEEDS-EDIT.**

Two material, cheap, precisely-scoped textual fixes (Findings 1 and 2), neither requiring a human decision or touching any number, threshold, rule, or freeze — both are corrections to how §14/§15 *describe* the already-reviewed spec and the already-established EG-8 finding, not to the spec or finding themselves. Everything else in §13–§15 that I checked — every RR rule's substance, the failure-class re-run policy for X/A1/A2/H/D/S, M = 4's arithmetic, every W-SYS constant and threshold, and all three of my v3 recommended text additions — is accurate, correctly attributed, and traceable to a specific working paper or probe. The "outcome-blind" wording change is a genuine, correct improvement over my own suggested phrasing, not a softening.
