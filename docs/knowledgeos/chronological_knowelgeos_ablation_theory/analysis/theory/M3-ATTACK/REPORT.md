# 1u: M3 consolidated and attacked computationally

| | |
|---|---|
| Status | research record, not canonical, authority none. **Every result is MODEL-DERIVED from development data; nothing here is new empirical evidence** |
| Pre-registration | `prompts/KNOWLEDGEOS-M3-PREREGISTRATION.md` (`1ea8669e…`), frozen at `3e1e32f65` before the checker existed |
| Checker | `m3_check.py` (`d212d24f…`): deterministic BFS; selftest 4/4 (frame-mutation detection, clean frames, all invariants hold, every relaxation yields a counterexample) |
| Output | `RESULT.json` (`6696388c…`) |
| Log | F-LOG-0125 |

## 1. What the attack establishes (and what it cannot)
The invariants hold in M3 **by construction**: the guards encode them. The computation is informative for three other reasons.

**(a) Joint satisfiability.** The 12 invariants do not over-constrain the system.
- START(8) is reachable, and so is ACCEPT(§4).
- A "permission without authorization" state is reachable, which is I-A2's witness.
- Reachable states: sub-model A 84, B 250, C 9.
- There is no frame violation in any transition.

**(b) The corpus sequence is a theorem of the guards.**
- The shortest path to START(8) is: BOARD-ACTS → AUTHORIZE-COND(4B) → SATISFY-PROVISO(4B) → START/FINISH/ACCEPT(4B) → AUTHORIZE(4C) → … → ACCEPT(4D) → ACCEPT(§4) → AUTHORIZE(8) → START(8).
- In **every** reachable state where WP-8 has started, the board acts are done and 4B, 4C, 4D and §4 are all accepted.
- This is exactly R-79's "SEQUENCE AFFIRMED". **The ordering R-79 states is not an extra rule: it follows from the local guards** ("authorized after the predecessor is accepted", "closure by acceptance", "WP-8 after §4 closes").
- This is a development consistency result, not a test.

**(c) Observation templates.** Each invariant was relaxed by removing its enforcing guard or widening its frame. The shortest violating trace in the relaxed model is exactly what a source would have to record to falsify M3:

| Invariant | Shortest counterexample (relaxed) | What a source would have to show | Best source family (rank) |
|---|---|---|---|
| I-A1 no execution without full authorization | `START(4B)` | an implementation start/commit for X dated before X's full authorization | **git × register (1)** |
| I-A2 permission ≠ authorization | `PERMIT(8) · START(8)` | WP-8 implementation while only planning permission existed | **git × register (1)** |
| I-A3 closure only by acceptance | `CLOSE(4B)` | an item recorded CLOSED with no acceptance act | register (2) |
| I-A4 part ≠ whole | `… ACCEPT(4B)` leading to §4 accepted | a parent accepted upon one child's acceptance | register (2) |
| I-A5 authorization ≠ acceptance | `… AUTHORIZE(4C)` that also accepts | an authorization recorded as accepting or closing | register (2) |
| I-B1 no self-adoption | `ISSUE(r1,chief) · ADOPT(r1,Chief)` | a Chief ruling adopted by the Chief | register (2) |
| I-B2 no delegation from adoption | `ISSUE(chief) · ADOPT(Authority) · ISSUE(r2,chief)` governing at issue | after an adoption, a Chief ruling governing at issue | register (2) |
| I-B3 supersession only by explicit act | `ISSUE(r2,human) · INFER-SUPERSEDED(r1)` | a ruling treated as superseded with no act | register / logs (3) |
| **I-B4 decision text immutable** | `ISSUE(r1,human) · AMEND-TEXT(r1)` | **a decision text edited in place after its first record** | **git history of the register (1)** |
| I-B5 withdrawn retires the number | `ISSUE(chief) · WITHDRAW` then reissue | a withdrawn identifier reused | **register + git (1)** |
| I-B6 human rulings never PREPARED | `ISSUE(r1,human)` giving PREPARED | a human ruling recorded PREPARED | register (2) |
| I-C1 evidence is target-indexed | `INTAKE-CONTRA(imp)` questioning the decision | a decision's standing lowered on implementation evidence alone | register / reviews (3) |

## 2. Where the theory now stands
- **Survives both regimes on selected cases:**
  - the record invariant;
  - frame structure;
  - scope-bounded authorization;
  - target-indexed evidence persistence.
- **Regime-parametric:** the status model.
- **Corrected twice:** the frame tables (ADOPT in 1s, ACCEPT in 1t).
- **Consolidated:** M3 gives a small, jointly satisfiable, compositional invariant set. It **derives** the corpus's stated delivery sequence.
- **Open:**
  - composition of sub-models A, B and C, which are checked separately;
  - the evidence-bar question (G-R/G-K/G-O/G-E), widened by R-72's evidence guard on AUTHORIZE;
  - whether any rule needs unbounded history (none so far).

## 3. Next targeted read (chosen by observability, from a different source family)
Four templates are **rank 1**, meaning mechanical and cross-source: I-A1, I-A2, I-B4 and I-B5.

**Recommended first: I-B4 in the register's git history (L0-REL-30).**
- **Why:**
  - It tests the strongest recurring invariant, the two-layer record.
  - It covers **all 71 rows at once**.
  - The source family is new: version history, with mechanical ordering rather than a content snapshot.
  - It needs almost no interpretation, and almost no corpus exposure.
- **Proposed method** (to be frozen before running):
  - for every commit touching the file, extract each row's Ruling cell (column 3) and Effect cell (column 4);
  - compare every later version with the row's first version, after whitespace normalization;
  - classify each cell UNCHANGED / APPEND-ONLY / MODIFIED;
  - report counts, row IDs and edit sizes only, **never the text**.
- **Falsifier:** any column-3 MODIFIED that is not labelled as an annotation.
- **Expected under M3:** column 3 UNCHANGED for every row; column 4 APPEND-ONLY.

**Second: I-A1 on WP-4B.** Compare its implementation commit timestamps (git subjects and dates only) with the date its proviso was satisfied (the register). This needs a mapping from commits to work items, so more interpretation. Do it after I-B4.
