# Assessment of the advisory methodology review of 2026-11-01

| Field | Value |
|---|---|
| Status | **The active Governance Engineer's assessment** (Option A, research record 2026-11-01T13:48:39Z: "The active Governance Engineer must assess advisory recommendations against the mandate and evidence"). It decides nothing |
| Assesses | `03-methodology/METHODOLOGY-EFFICIENCY-REVIEW-20261101.md` (`035f18d81`, sha256 `449bbb35e1d318fcdb37d34a8a97d17ab22c7b7ac7f966df32bafe829100f950`, 580 lines, read in full), by an advisory session reporting the model `deepseek-chat` |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-01; mandate §8.2 (factual premises first, then point by point) |

**Labels.** **[V]** checked against the records · **[I]** interpretation · **[P]** proposal.

## 1. Verdict

**[I] A sound, honest review whose main recommendations are right and are already decided.** It keeps facts, inferences and proposals apart, states its own limits (§2.2–§2.3), and withdraws two of its own earlier findings (F8, F9). **Six weaknesses** (§3) do not change its main conclusions but should not be carried forward: one internally inconsistent inference, two overstatements, one outdated recommendation, and a provenance problem in how it was later used.

## 2. Factual premises checked

| Claim | Check | Result |
|---|---|---|
| 25 sources in order on 2026-10-30, 50 on 2026-10-31 (§1, §7.1) | read log, depth full/skim per day | **[V] confirmed** (25, 50) |
| `METHOD-VERSIONS.md` L52–54 says the workspace is untracked; it is tracked | L54 read; the lane is in git | **[V] confirmed** (assigned to the Professor, decision of 2026-11-01 item 4) |
| v1.4 §12b requires predictions before index order reaches a branch consult (L159) | rulebook L159 | **[V] confirmed** |
| `REVIEWER-INSTRUCTIONS` L150: the running measure includes "defects discovered later" | L150 | **[V] confirmed**; never aggregated (also my reading) |
| S0150: two found plants treated only in part | `CONSOLIDATION-S0131-S0150/PLANTED-GAP-RESULT.md` L24–25 | **[V] confirmed** |
| Wilson intervals for 4/8, 3/5, 2/4, 4/4 overlap | recomputed mentally against the standard formula | **[V] consistent** |
| "21,056 lines of book in 183 files; 111 review folders" | `find` today: 21,064 lines, 183 files; 105 review folders | **[V] approximately right**; the folder count differs (counting method not stated) |
| Novelty 0.62 → 0.03 after S0150; by source kind | not recomputed | **not checked**; its own caveats (three readings, attribution artefact) are adequate |

## 3. Weaknesses

1. **An inference against its own caveat (§3.4, B1).** It says the read-log times "are close events, not work intervals" (§1, item 2), then uses them ("25 sources and 10,606 lines were closed in 76 minutes") to call "reading is the bottleneck" **"probably false"**. **[V]** The C58 file shows four sources opened and closed within a minute: the log records bookkeeping. **[I]** B1 is **unknown**, not "probably false". Its own recommendation — measure first — is the right one for that reason.
2. **"The lane is not doing what its own priority rule says" (§1, §12) is overstated.** **[V]** The blockers ranking it relies on was produced **at the reading gate (S0302), on the Principal's instruction of 2026-10-31**, after which reading stopped; it did not exist while reading "continued past" those questions. **[V]** Two of the five questions are marked "**Principal decision**" only (questions 2 and 3), and the other three end in a Principal decision after research (`BLOCKERS-S0278-S0302.md`, column "Research or decision"). v1.4 §1's "critical foundational problem" is a priority rule, not a schedule.
3. **"150 section-citations" double-counts.** **[I]** It sums the five questions' counts (36 + 31 + 29 + 28 + 26); a section citing several questions is counted several times, and the Professor's file notes that the dependency graph reaches one large cluster from any of them. The number of distinct sections is smaller and unstated.
4. **I-6 targets classes that are no longer the missed ones.** It proposes plants of "an ordinary word used as a defined term" and "a removed claim read as a pointer error", "missed repeatedly". **[V]** Both were **found** at S0226–S0252 (4 of 4), a result file it lists as read. **[V]** The test of S0278–S0302 (after the review) missed a **stale pointer** instead. If I-6 is ever run, its classes need re-choosing from the latest results.
5. **§6.3 (d) rests on a reading the Principal has since rejected.** "Under the guard reading, `Req` is not monotone … the existence result of §9.1 C3 fails." **[V]** The Principal decided **T9-20: no guard on adoption** (2026-10-31), before the review. **[I]** (d) does not apply to the decided theory; the review does not mention T9-20.

6. **It treats `Req` as having no production step, and never cites §9.10.** *(Added 2026-11-01 while preparing the Req/A commission.)* **[V]** `Req` was defined at **theory turn 1** (2026-10-20): §9.10, version 2, Req = (Q, O) with Q1–Q4 and O1–O4 and fixed disproof conditions (`review/TURN1-REQ/SETTLEMENT.md`: "Met, with the obstacle documented"); §9.11 derives the kernel type from it. The review's I-2 and §6 cite §9.1 and T9-01 but not §9.10 or turn 1. **[I]** Its recommendation still stands, narrowed: the open work is how §9.10 relates to `BOOK-OUTLINE.md` L179's "missing" item, plus `A` and minimality — not deriving `Req` from scratch. The commission is written that way.

## 4. A provenance problem in how the review was used (not a defect of the review)

**[V]** The same session that wrote the review later **acted as Governance Engineer** (state file, rewritten 2026-11-01T02:33Z: "began … as an analysis session … and took the Governance Engineer role afterwards"). In that role it (a) recorded the Req/A approval, whose stated basis is **its own "pre-execution report … given in session; it is not in the repository"** (research record 2026-11-01T02:31:51Z), and (b) committed its own review (on the Principal's instruction). **[I]** The Principal's decision stands as recorded, but **an approved commission rests on a report no one can now read**. **[P]** The copy-ready Req/A commission (owed after the settlement) should therefore be built **only from repository records** — the seven conditions in the addendum, the review's §6.2 template, `BLOCKERS-S0278-S0302.md`, `BOOK-OUTLINE.md` — and say so. Option A (2026-11-01) prevents a repeat.

## 5. Its recommendations, and where each stands

| Rec. | Status | Assessment |
|---|---|---|
| I-2 / Decision 1: theory turn on `Req` and `A` | **[V]** approved for preparation only (2026-11-01T02:31:51Z); issued after the settlement, on the Principal's dispatch | agree; condition 4 already bars a fixed selection criterion and a final primitive set |
| I-1 / Decision 2 / E1: time split | **[V]** authorised (2026-10-31, item 2; starts at S0303) | agree; read "active vs elapsed" as the review defines it |
| I-5: aggregate review quality | **[V]** authorised, after the settlement (2026-11-01, item 2) | agree; mine to do |
| I-7 / Decision 3: correct the stale premise | **[V]** (a) assigned to the Professor (2026-11-01, item 4); **(b) milestone premise checks: not decided** | (b) is cheap and fits mandate §8.9 if scoped to repository-state premises; a separate decision |
| Decision 4 / E3: faster-reading test | **[V]** deferred (2026-11-01, item 1) | agree; its amendments (lines per active hour; one variable at a time) are the right form for any later test |
| I-3: register extension field | **[V]** not decided; the Professor must define any register change first (2026-11-01, item 5) | sound as measurement; the meaning of "confirmed" must be defined (mention ≠ confirmation) |
| I-4: score predictions | **not decided** | sound; it can enter the Req/A commission as the 3–5 predictions its §6.2 template already lists, without a rule change |
| I-6 / E2: paired plant test | deferred (by its own recommendation) | if ever run, re-choose the classes (§3 item 4) |
| No Rulebook v1.5 | consistent with every decision since | agree |
| F8 (redo strategic design): withdrawn | — | agree |
| F9 (two three-valued logics): withdrawn; residual U×U composition question | **not checked by me** | worth a line in the Req/A turn's unresolved questions, not a separate task |

## 6. What follows (nothing decided here)

- Nothing new is needed now; the review's three priority actions are decided or authorised.
- **Open for the Principal later:** I-7 (b) milestone premise checks; I-3 once the Professor defines it; I-4 inside the Req/A commission.
- The Req/A commission, when presented, will cite repository records only (§4).
