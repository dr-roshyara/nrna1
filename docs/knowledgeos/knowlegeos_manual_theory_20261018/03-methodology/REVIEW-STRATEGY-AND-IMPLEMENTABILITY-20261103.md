# Review: strategy and implementability (independent strategy review, advisory)

| Field | Value |
|---|---|
| Status | **ADVISORY — decides nothing.** Answers the six questions of `03-methodology/PROMPT-DEEPSEEK-STRATEGY-REVIEW-20261103.md`, at the Principal's instruction of 2026-11-03 ("read the prompts and work as principal DDD Architect and Researcher … write the review and suggestions in the same folder") |
| Provenance | [Fact] The prompt is marked "**Not sent by the Governance Engineer**; the Principal sends it" (PROMPT L5). [Fact] This answer was produced in the Claude Code harness using the **DeepSeek API**, the model as reported by the Principal. [Inference] It is therefore a DeepSeek-model answer, but **not the clean-room independent review the prompt envisaged**: the session ran inside the lane with prior context and its own tools, so it is one model's answer given here, and independence from the lane is not claimed |
| Boundary | Respected: no corpus source, nothing from `00-access/`, no review folder, no git history. **Disclosed:** two files read beyond §3's list — `BRAINSTORM-CHAPTER-SUMMARIES-SPLITTING-CH10-20261103.md` and `GOVERNANCE-ENGINEER-STATE.md` (both methodology/status files, the class L7 allows). Two listed files not opened (§5) |
| Limits | [Fact] Files only; no section-by-section judgement of the theory (PROMPT L69). [Fact] `THEORY-MAP.md` not opened, so F1–F37 is cited only via `BOOK-OUTLINE.md` L160. [Unknown] Whether the unread 87 % of the corpus bears on deliverables 1 and 2 |

## 1. Verdict

[Fact] Deliverable 5 is strongest; deliverable 2 has been "missing" since the first consolidation; deliverable 1 has 2 of 12 candidates ✓ (PROMPT L35; `BOOK-OUTLINE.md` L11–22, L44). [Inference] **The lane is on a path to a functional theory and not yet on a path to an implementable one** — two finishing lines, and only the first has a plan. [Inference] The constraint is not reading speed (two turns of 27–41 min changed the theory more than seven windows: PROMPT L33; ~200 sources added no concept: L34); it is that **no artifact states what follows from what**. [Proposal] Make one derivation chain the main line, with reading serving it.

## 2. Recommendations, ranked

### R1. A derivation-chain turn that first fixes *the unit of derivation*

- **What** [Proposal]: ask **"by what relation are the laws derived — from primitive concepts, from entry kinds and fields, from mechanisms, or from Req?"** Pick one, then derive **one law end to end** before touching the rest.
- **Why** [Fact]: `BOOK-OUTLINE.md` L44 records the chain missing since S0036–S0065; §9.19.10 L403 records the Req/A turn leaving it missing. [Fact] "Primitive" is a **homonym**: §9.19.4 L177 ("'Primitive' here means a mechanism") against `RESEARCH-PURPOSE.md` L28–35 and `BOOK-OUTLINE.md` §1 (**concepts**); §9.19.5 L203–226 adds a *field* sense and says "**None of this is membership of a minimal kernel**". [Inference] Deliverable 1 cannot close while three tests share one name.
- **Gain**: the oldest gap closes, and deliverable 1's missing **independence model** (`TARGET-TOC.md` L12) arrives with deliverable 2's first segment.
- **Risk**: a chain on the wrong unit yields a large consistent restatement; the turn may find the laws do **not** derive from the candidates — a real result that stops the outline.
- **Measure**: one law derived end to end with premises and falsifier, and every law of §9.11's 31-row classification marked *forced by Req* · *compatible* · *independent*, each "independent" reclassified as application rule or world assumption (§9.15's K/L split is the precedent).
- **Copy-ready**: > "Commission one theory turn on the derivation chain: determine which object the laws derive from — primitive concepts, entry kinds and fields, mechanisms, or Req — and answer with one law derived end to end plus every law of §9.11 marked forced by Req, compatible with Req, or independent of it. Scope bounded to the existing book; no new primitive fixed; up to 8 sources by logged search."

### R2. Fix the interface assumption **A** and the output forms (T9-22), in one turn

- **What** [Proposal]: derive **G** (granularity, batching) from Req's kind structure rather than choosing it; decide whether Q1's act list, Q2 and Q3 return identifiers or whole entries.
- **Why** [Fact]: "a minimal kernel" is well posed only relative to (B, G, Req's cut) — T1, §9.19.6 L238; §9.19.8 L361 "**not stated**". [Fact] U3 kept G and batching unfixed; minimality of 𝒮 "turns on **three output forms**" (§9.20.5 L107–111 = T9-22, `TODO.md` L32). [Inference] Granularity and query output **are** the interface; while both are open, no implementer can be given a kernel.
- **Gain**: unblocks T9-03, T9-04 and minimality; a short turn, since 𝒮 is already fixed (§9.20 Th1–Th4).
- **Risk**: fixing G **by decision** breaches Req/A approval condition 4; deriving it from U6's per-kind cut avoids that.
- **Measure**: T9-22 closed or reduced to a decision; §9.20.5 restated for the fixed form; T9-03's two notions related.
- **Copy-ready**: > "Commission one theory turn on A and the output forms: derive the mechanism granularity and batching from Req's per-kind cut (U6), and decide whether Q1's act list, Q2 and Q3 return identifiers or whole entries. It closes T9-22 and restates minimality for the decided form; no primitive and no selection criterion fixed."

### R3. Make the theory-turn **chain** the main line, reading at a floor — no rule change

- **What** [Proposal]: a chain of critical-path turns, one deliverable each, with index-order reading continuing at a floor of one window a day.
- **Why** [Fact]: v1.4 **already provides it** — "**A chain of critical-path turns** … consecutive turns, one deliverable each, each settled before the next builds on it; reading resumes when the chain is settled or the Principal decides" (RULEBOOK L160). [Fact] ~200 sources, no new concept; register 265 since 2026-10-26 (PROMPT L34; assessment L43); recent sources are "largely operational records of the estate's own sessions and governance acts" (assessment L45) — [Unknown] whether later corpus bears on primitives and laws (L46).
- **Gain**: 27–41 min per turn against 16 min per window, for more change than seven windows (PROMPT L33).
- **Risk**: a theory grown from the book alone becomes internally consistent and ungrounded; the corpus is the only evidence line (`RESEARCH-PURPOSE.md` L43). A reading floor preserves coverage and §12b's prediction practice.
- **Measure**: claims whose status changed per turn; if a turn changes none, the chain is not producing. Sources per day reported separately.
- **Copy-ready**: > "From the S0353–S0377 settlement, the main activity is a chain of critical-path theory turns under Rulebook §12b — one deliverable each, each settled before the next builds on it — while reading continues in index order at a floor of one window a day. I may end the chain at any settlement."

### R4. Chapter 10: write the **record's** published language; close or attribute every map edge

- **What** [Proposal]: (i) write W's language — the entry kinds and their fields — beside standing's PL-0–PL-14; (ii) make every §10.4 edge unconditional or name the decision it waits on; (iii) state §10.3's rows as *owned / preserved*.
- **Why** [Fact]: "W's language not yet written" (`10.00-index.md` L11); 23 relationships, **16 conditional** (L10); all §10.3 rows S0 (L9); "**Baseline version:** none yet" (L3). [Inference] Published language plus ownership is what an implementation is written against; without W's language there is no contract. It is the only Ch. 10 work that serves implementability without crossing the boundary.
- **Gain**: a design map becomes a specification surface, at no risk of writing implementation.
- **Risk**: W's language can slide into message design — vocabulary, fields, invariants only.
- **Measure**: every §10.4 edge unconditional or attributable; W's language covers the F rows of §9.20.7.
- **Copy-ready**: > "At the next Ch. 10 revision the Professor writes the record's published language (entry kinds and fields, from §9.20.7), makes every §10.4 edge either unconditional or explicitly attributable to an open decision, and states §10.3's rows as owned or preserved. Vocabulary, fields and invariants only — no operations, protocols or storage."

### R5. Keep the review; measure the one thing that would justify reducing it

- **What** [Proposal]: add one measurement; reduce nothing yet.
- **Why** [Fact]: blind planted-gap results 4/8, 3/5, 2/4, 4/4, 3/4, 3/4 (assessment L22) — stable about 3 of 4; corrections change conclusions, not wording (L24). [Inference] No evidence supports less review; the review record is ~3× the book (PROMPT L34), so the pressure will come and should be met with data.
- **Gain**: a recurring argument becomes a number. **Risk**: none to quality.
- **Measure**: for each window since S0303, classify findings as step-2 only, consolidation only, or both. High overlap is the only defensible basis for trimming step 2 on windows that change no concept.
- **Copy-ready**: > "Add to the review-quality analysis: for each window since S0303, classify findings as found at the window's step 2 only, at the consolidation only, or at both, and report counts. No change to review until it reports."

### R6. Throughput: the arithmetic and the honest trade-off

- [Fact] A window cycle is 12–20 min **whatever its size**, step 2 about half (ANALYSIS L14–25): at 5 sources per window, ~19 sources/hour → 100 sources ≈ 5.3 h. [Fact] Consolidations every 25 sources = four per 100; ~40 min normally (L31), ~3 h 17 min with a planted-gap test (L32).
- [Inference] 100 sources/day ≈ 8 h of windows plus consolidations with **no theory turns**; with two planted-gap tests it does not fit a working day.
- [Proposal] Combination: 5-source windows (decided), planted-gap preparation off the critical path (ANALYSIS L62), minor access deviations recorded not stopped (L64), consolidation reports assembled from settled window records rather than re-derived.
- [Inference] **Cost**: the window is the unit at which a source's *interpretation* is independently checked; larger windows mean up to 4,000 lines per reviewer pass (RULEBOOK L81 caps 5 sources; PROMPT L31 the line figure), so coverage per source falls. Measure findings per 100 lines and planted-gap hit rate before and after.
- **Copy-ready**: > "Throughput is reported as two numbers per day: sources read in index order, and claims whose status changed. Reading may reach about 100 sources a day by 5-source windows with planted-gap preparation off the critical path; theory turns are counted separately and not traded for sources."

## 3. Implementation readiness checklist

[Inference] An implementation can be **specified** only when every row closes. "Stage 1 is not authorised" (PROMPT L36) is the governance fact; this is the technical precondition beneath it.

| # | Condition | State | Evidence |
|---|---|---|---|
| 1 | Req fixed: items, O read as EF, O1 cut per kind | **done** | §9.19 v3; U5, U6 |
| 2 | 𝒮 sufficient **and** minimal | sufficient; **minimality open** | §9.20 Th1; §9.20.5 L107–111; T9-22 |
| 3 | A = (B, G) fixed | B fixed; **G and batching unfixed** | §9.19.4 L145–166; U3 (b) |
| 4 | Every Req item has a total reference definition | Q1 (CE\*), Q4 (g1) done; **Q2, Q3 open**; five act kinds outside D | §9.19.4; T9-21 |
| 5 | Every law placed: prevent or detect | per-law, except law 31 | §9.19.2 L56 |
| 6 | The record's published language (W) stated | **not written** | `10.00-index.md` L11 |
| 7 | Invariant ownership settled per invariant | rows S0; admission T10-05 (B) with K2 | `10.00-index.md` L9 |
| 8 | Map edges decided or openly conditional | **16 of 23 conditional** | `10.00-index.md` L10 |
| 9 | Coordination points named (serialization; non-merge-safe laws) | in theory, not in Ch. 10 | §9.4 C2; §9.16 R3 |
| 10 | Boundary tested for each exposed capability | only F22 and single use | `BOOK-OUTLINE.md` L59, L214, L232 |

**Order**: 1 → 3 → 2 → 4 → 5 are theory (rows 1 and 3 are R1 and R2); 6–9 are Ch. 10, and **6 is the highest-value single item**; 10 is a decision, not a turn. [Proposal] Ch. 10 evolves by applying Ch. 9's consequences *as consequences*, closing the map edges, writing W's language and stating ownership as owned/preserved — vocabulary, fields, invariants only.

## 4. What not to change, and why

| Item | Why not |
|---|---|
| Blind-first review; two-stage planted-gap settlement | [Fact] The only quantitative evidence of review quality (assessment L22). Cutting it removes the ability to **know** whether a faster lane is still safe |
| Three reviewers on theory turns, different models | [Fact] The highest-yield activity (PROMPT L33); its trustworthiness is a product of the review. Cutting it makes the best activity the least-checked |
| Allowed files only; no corpus text outside (RULEBOOK L124) | [Inference] The only hard guarantee the corpus is not disclosed, and unrecoverable if broken once |
| Stable section numbers (`TARGET-TOC.md` L28–38: 2,364 § citations) | [Inference] Renumbering buys navigation, costs every citation |
| One canonical home per concept (RULEBOOK L33, L96) | [Inference] With 265 concepts this prevents the fork that makes a theory unimplementable: two names, two meanings, no contract |
| Optimisation only at fixed points (RULEBOOK L45) | [Inference] A rule changed between checkpoints lacks the measurement that would justify it |

## 5. Every file opened

**On the list, opened (13):** `03-methodology/PROMPT-DEEPSEEK-STRATEGY-REVIEW-20261103.md` · `03-methodology/RULEBOOK-v1.4.md` · `03-methodology/ASSESSMENT-RESEARCH-QUALITY-AND-DIRECTION-20261102.md` · `03-methodology/ANALYSIS-WINDOW-TIMES-AND-SAVINGS-20261103.md` · `03-methodology/ASSESSMENT-OF-METHODOLOGY-EFFICIENCY-REVIEW-20261101.md` · `06-theory/RESEARCH-PURPOSE.md` · `06-theory/manuscript/BOOK-OUTLINE.md` · `06-theory/manuscript/TARGET-TOC.md` · `06-theory/manuscript/chapter_09/09.00-index.md` · `06-theory/manuscript/chapter_09/TODO.md` · `06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md` · `06-theory/manuscript/chapter_09/09.20-the-state-space.md` · `06-theory/manuscript/chapter_10/10.00-index.md`

**On the list, not opened:** `06-theory/THEORY-MAP.md` (see Limits) and `03-methodology/METHODOLOGY-EFFICIENCY-REVIEW-20261101.md` (its content and weaknesses are in the assessment of it, §2–§3; the prompt also says not to repeat what was decided). [Inference] Neither changes R1–R6.

**Off the list, opened:** `03-methodology/BRAINSTORM-CHAPTER-SUMMARIES-SPLITTING-CH10-20261103.md` · `03-methodology/GOVERNANCE-ENGINEER-STATE.md`. **Not opened:** any corpus source, anything in `00-access/`, any `review/` folder, no git history.

---

*Nothing here is in force. Each recommendation is the Principal's to accept, reject or reword; the copy-ready lines are written to be approved as they stand or not at all.*
