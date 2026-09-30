# B0042 extraction summary

**Files processed:** 40/40 (S1717–S1756). 39 read in full as text; 1 (S1752, a compiled
`.pyc` bytecode cache for `so_model.py`) confirmed binary/unreadable by the Read tool and
recorded as `FIREWALL-LIMITED` with no contributions — its source (`so_model.py`, S1753) was
read in full and is the authoritative record of the same content.

**Commit:** `39fdef05dc027c6264b6c349a26362a59191a35f`.

**What this batch is.** The tail of a long, self-referential adversarial re-verification
programme (2026-08-30): the gap-discovery series' final documents (15–17 + exec/), the entire
14-document "independent" verification series (00–14 + witnesses/), two book-production
governance artifacts (Part II acceptance closure; Book Edition 2 II.1 claims), three
book<->theory synchronization/readiness artifacts, and a previously-unseen **second-order**
investigation (`second-order/exec/so_*.py`) that computationally executes the corpus's own
Step 259 congruence-matrix test and **explicitly corrects** the first-order gap-discovery
pass's own EXP-3 finding ("its arithmetic was correct; its inference was not").

**Continuation of the gap-discovery/re-verification stream noted in the batch contract:**
confirmed. This batch both deepens the prior findings (13 CRITICAL / 25 HIGH / 10 MEDIUM /
5 LOW gaps consolidated into a Master Gap Register; the corpus's own highest step, 271,
shown to commission a never-executed Step 272; the "47 tests" overstatement; the ℛ
8-tuple→triple reduction) and adds a **new layer of self-correction on top of it**: the
second-order pass shows the first-order EXP-3 (used to justify G-01/G-02, "K sufficiency
depends on the operation set") mis-applied a history-reading (class-4) predicate against a
congruence test the corpus's own taxonomy restricts to state-transforming (class-1)
operations. Per the contract, both the original finding and its correction are extracted as
separate, non-reconciled contributions (see S1724 vs. S1754/S1756).

**Kept as competing/unresolved, not reconciled:**
- EXP-3's "K=(A,R) sufficiency depends on 𝒪" vs. SO-EXP-01/03's "K=(A,R) is congruent for
  every class-1 operation" — both extracted; the second explicitly names the first's category
  error but does not retract the underlying operation-set gap (G-01) itself.
- The book-lane's own claim of zero completeness-overreach (BOOK-READINESS-AUDIT) vs. the
  theory-lane's blunt "NOT COMPLETE" verdict — these are different lanes about different
  objects (book text vs. mathematical theory) and are recorded as such, not merged.
- Three explicit SELF-1/2/3 corrections in `11-CONTRADICTION-REGISTRY.md` of the
  *immediately preceding* adversarial pass's own findings — extracted as their own
  contributions rather than silently folded into the corrected claims.

**Object index:** 30 new labels proposed (`index-proposals.jsonl`), all checked against the
current ~1033-line snapshot of `11-OBJECT-INDEX.jsonl` before proposing; no near-synonym
found for any of them. One `UNKNOWN-OBJECT-CANDIDATE` used (S1718, "Knowledge" as the whole
field/atom/state/capacity collision, uncertain against `knowledge-state-formalization`).

**Volume:** 135 contribution rows across 40 files (avg. ~3.4/file; dense analytical
documents such as the Master Gap Register, the K-Attack, and the second-order SO-EXP series
carry 4–7 rows each; short README/index/binary files carry 0–2).

**Self-checks:** all six mandatory checks run and passed —
`TOTAL INVALID ROWS: 0` · `TOTAL UNREGISTERED LABELS: 0` · `valid lines: 135` (all JSON valid)
· `TOTAL INCONSISTENT ROWS: 0` · `TOTAL FIELD-SHAPE ERRORS: 0` · `TOTAL SCOPE ERRORS: 0`.
(The scope-enum check initially failed on 12 rows where `scope` had been mistakenly copied
from `types`, e.g. `"GOVERNANCE"`/`"FUTURE-RESEARCH"`; all 12 were corrected to a genuine
`scope` value — mostly `METHODOLOGICAL` for book/governance-process content, `THEORY-LEVEL`
for theory-closure content — and the check re-run clean.)

**Not done / disclosed limitations:** `witnesses/attack_output.txt` (S1748) and
`witnesses/attack.py` (S1749) are near-duplicates in content (the former is the executed
transcript of the latter) and are recorded with an `in_file_overlap_claim` on S1748 rather
than re-deriving the same findings twice at full weight. No comparison was attempted between
this batch's findings and batches outside B0042 (out of scope for a single-batch extraction
agent).
