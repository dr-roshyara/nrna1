# Theory Readiness Assessment: S-Series KnowledgeOS (Session B, Professor)

| | |
|---|---|
| **Date** | 2026-10-17 |
| **Kind** | Assessment for the Principal and the guiding reviewer. **It decides nothing, changes no other file, and runs no study.** |
| **Answers** | charter `review/what_reviwer_shold do_to review_professor_s-Series KnowledgeOS.md` (sha `77131e10…`), §14 items 4–6, and its Priority 3 questions Q-M1 and Q-M2. Also covers the Session A review `review/20261017_1407_review_what_prof_wants.md` (sha `8377ba4e…`). |
| **Method** | Read-only reconciliation against the authoritative files, plus deductive analysis of authorized governing documents and frozen code. **No computation was run for this document.** No corpus, S5, ledger, batch-input, hold-out or seal material was read (O1, GL-0125). |
| **Status labels** | **VERIFIED** (checked by Session B against the file today) · **REPORTED** (stated by another session; not checked) · **DERIVED** (Session B's own deduction; same-model, unreviewed) · **CONDITIONAL** (holds under stated assumptions) · **OPEN** |

---

## 1. Question and objective

The question is what the Professor can responsibly establish today, what still needs proof or evidence, and what must wait for a decision by the Principal.

The objective is a claim-by-claim classification plus two new pieces of analysis that are permitted now. Both are pre-evidence analyses of documents and code:
- Q-M1: which verdicts are reachable;
- Q-M2: which hypotheses depend on agent classifications.

## 2. Current authoritative state (reconciled)

| Item | State | Basis |
|---|---|---|
| Governance-log head | **G-LOG-0140**; 2,218 lines; sha `04cb0c19…`; last commit `11dc5cf8c` (2026-10-13); no working-tree change | VERIFIED (PROD file) |
| Worktree copy of the log | ends at G-LOG-0110 (1,718 lines) | VERIFIED. Which copy is authoritative is **not recorded in the log** (DS-3) |
| Branches | PROD `election-flow-updated` @ `f54037f96`; W `s-series/s5-r7-v29-repair` @ `725bfa1e3` | VERIFIED |
| S-Series TODO | 510 lines, sha `e7d35147…` (unchanged since the source register); **uncommitted modifications** | VERIFIED |
| N-a/C-4 (Q-a), V-1…V-13 | open; no governance entry after GL-0140 | VERIFIED |
| Run-3 | approved at G2 (GL-0117), execution DEFERRED; no PREFLIGHT-OK recorded; no quiet period in force | VERIFIED (log + TODO l.8). Live preflight output not inspected |
| Measured S5 labels | 0 of 1,975 | VERIFIED (TODO l.5, l.8, l.47; no accepted batch in the log) |
| prereg_v2 | FROZEN, GL-0137, sha `827ce461…`; the file still self-labels "DRAFT, NOT FROZEN" (l.4, l.348) | VERIFIED |
| Manuscript v0.1 | sha `3d43f59b…`, unchanged since 13:17; untracked; unreviewed | VERIFIED |
| PO-01 note / architecture proposal | shas `4ea81900…` / `f6d84cee…`; untracked; unreviewed by any independent party | VERIFIED |
| RQ-1 (Freeze 1 vs prereg_v2) | Session A reportedly recommends explicit reconciliation in the governance record. No reconciling entry exists | REPORTED + VERIFIED absence |
| RQ-2 (which log copy is authoritative) | Session A reportedly confirms PROD. Consistent with the record (entries 0111–0140 exist only on PROD), but **not a recorded human act** | REPORTED; consistent |
| RQ-3 (RI-1) | **The TODO is stale.** GL-0104 l.1616 records "RI-1 reopened on its condition 1", treated by EG-2 (SLICE-VIEW); GL-0106 (f) adds "RI-1b coverage report-only". The TODO still says "stays DEFERRED" (l.20, l.231, l.290) | **VERIFIED** by Session B |
| Session A's "last report" answering RQ-1…3 | not found in any authorized file | the answers stay REPORTED |

## 3. Discrepancies the Principal should see

| # | Discrepancy | Handling |
|---|---|---|
| Δ-1 | **Manuscript architecture.** The charter (§8) reports a recommendation to "keep one source manuscript for now and split … only when chapter-level review makes that useful". My proposal (D1) recommends modular chapters now, and Session A agrees "with conditions". | Not reconciled. The charter's position is the more conservative; §7 gives a revised recommendation |
| Δ-2 | **N-a/C-4 recommendation.** Package v2 §5 recommends **(a)**; the TODO l.161 and Session A recommend **(b)**. The charter frames the choice as (accept limitation / binding second judgement / defer); package v2 says "Deferral: not an option for adoption" | Reported in the decision package (DS-1); nothing resolved |
| Δ-3 | **V-1 readiness.** The review record l.750 says "V-1 itself … can be put to the Principal"; v1.8 §22.4 says it "does not claim to be ready for V-1 adoption as a complete rule set" | Clarification needed (DS-2) |
| Δ-4 | **The ambiguity between "unruled until resolved" and "J5 resolution".** v1.8 l.69 freezes a conflicted pair at E_close, while l.270 and l.313 speak of a J5 "resolution" (package v2 §1) | Recorded. **Not reconciled in the manuscript**, as the charter requires |
| Δ-5 | **Name collision.** "RQ-n" names both the manuscript's research questions and my requests to the Researcher | Rename proposed (D7); not applied |
| Δ-6 | **Withdrawn claims still present.** My PO-01 note still contains the withdrawn "conservative" reading and Wald intervals | Correction proposed as a diff (§8); not applied |

## 4. Claim readiness matrix

The claims are drawn from the manuscript v0.1 and the PO-01 note.
- *Supportable now* means "established design or a verified fact as recorded". It never means that a theory is supported.
- No theoretical claim about KnowledgeOS exists yet, so none is classified.

| Claim | Type | Readiness | Assumptions / scope | What would weaken it | Next step |
|---|---|---|---|---|---|
| The governing hierarchy is v3.5 + P3b v1.7; ARCH v1.2 is not declared governing (GL-0069) | governance fact | **Supportable now** | as recorded | a Decision A act | Decision A (Principal) |
| 0 of 1,975 measured; only OPEN reachable under v1.8 (Prop. 8.5) | fact + derived (conditional on a proposal) | **Supportable now**; Prop. 8.5 **conditional** on v1.8 | v1.8 definitions | an accepted S5 batch; changes to the v1.8 definitions | none |
| Design constants (α = 0.05, families, B = 999, π = 0.7, h₀, e₀, s₀, D = 12) | design | **Supportable now** | GL-0118…0137 | none (frozen) | none |
| **Prop. 1 / Prop. 8.3:** H1, H2, H4 p-values valid; Holm FWER ≤ 0.05 over the three Model-0 nulls | proposition | **Conditional on A1–A4; needs independent review** | A1 null invariance (H4 including uncounted keys), A2 ideal randomness, A3 one pre-fixed run (**not yet a recorded rule**), A4 extracted data as recorded | a proof error; a violation of A3 (seed shopping) | DS-5 external review; record A3 (DS-4a) |
| Prop. 8.2: the H2 power formula is exact under i.i.d. orientations | proposition | **Conditional; needs independent review** | independence of orientations (PO-2) | dependence within objects (see §6, Prop. Q2) | review with Prop. 1 |
| Prop. 8.4: ev(c) ≤ DISCRIMINATING while S5c is prohibited | proposition | **Supportable** (a short deduction from frozen rules); needs review | P3b §13.9b, §24.1; GL-0045 | an S5c authorization | review |
| H3/H5 tests control size | — | **Not established; unknown.** My "conservative" reading is **withdrawn** (exact bounds include sizes above nominal; my H5 simulation used the wrong statistic) | — | — | optional authorized study (DS-4b / DS-7) |
| The star scheme is not valid in general (Prop. 2) | proposition with constructed counterexample | **Supportable** (counterexample recomputed by Session A) | concerns the *scheme*, not the frozen tests at their chain lengths | — | none |
| The H3 swap chain is reducible; no T_h3 difference for n ≤ 4 | finite computational result | **Supportable for n ≤ 4 only** (reproduced by Session A) | exhaustive n ≤ 4 | a difference at n ≥ 5 | optional n = 5 check (needs authorization) |
| T_h3 identical across components for all n | conjecture | **Unproved.** My proof route is refuted (Session A's n = 4 counterexample; checked by hand by Session B) | — | a counterexample at n ≥ 5 | none, unless authorized |
| H2's forward share measures a property of the domain | interpretation | **Seriously weakened by Q-M2** (§6) | — | — | REQ to the Researcher; Principal awareness |
| Any statement about the theory's content | — | **Evidence-pending** (O1; S5, S5a/S5b, charter) | — | — | gates |
| Verdict criteria v1.8 | proposal | **Unresolved** (V-1…V-13; N-a/C-4) | — | — | Principal |

## 5. Q-M1: which verdicts are reachable while S5c is prohibited, and does V-2 need reconsideration?

### 5.1 Source position

v1.8 already analyses this question in §11.3 (d)–(f) and §10 case 16. This section consolidates that analysis into one map and adds one decision coupling (§5.3). No new rule is proposed.

### 5.2 Reachability map (DERIVED from v1.8 §3–§6, §10–§11; conditional on v1.8)

Notation: K-EG = an empirical-generalization corpus claim. "S5c prohibited" = GL-0045, ruling A.

| Configuration | ROBUST reachable? | NCTF reachable? | Basis in v1.8 |
|---|---|---|---|
| V-2 (ii), S5c prohibited | **No.** R4 needs ≥ 1 K-EG claim meeting CA-9 (PREDICTIVE), and no prediction can be scored | **Only** if every manifest candidate is disposed under N3 through grounds (i), (ii), (iii) or (v), or vacuously. K-EG claims pending CA-9 are **not** removed from C\*(T) (no qualifying finding), so any candidate with a surviving K-EG pair blocks NCTF | §5.2 R4; §3 C\*(T); §11.3 (f) |
| V-2 (i), S5c prohibited | **Only** through a Kind-P-only core. With k_min = 2 that core is exactly **{H2, H4}**, because H1 fails CA-8 under D0. It needs, all together: both claims Holm-REJ; a three-way comparison (CA-5); R1 within V-5 and R2-INVARIANT within V-8 (CA-6); the C1 model for CA-8; a J1 "linked" ruling between H2 and H4; registration before any S5 data | as for (ii), plus **Kind P candidates cannot be disposed of** with today's instruments (§11.3 d), except through ground (ii) or vacuously; V-11 is needed | §10 case 16; §11.3 (a), (d) |
| either V-2 option, S5c authorized and scoreable | ROBUST requires a K-EG claim confirmed out of sample (under (ii)) | ground (vi) only if V-10 (b) | §5.2; §6.2 (vi) |

**DERIVED corollary.** While S5c stays prohibited:
- under the recommended V-2 (ii), the outcome space is effectively **{NCTF (narrow), OPEN}**, and ROBUST is structurally impossible;
- under V-2 (i), ROBUST is possible only for the two-claim core {H2, H4};
- §6 below shows that H2's reading is very likely method-determined, which makes that core weaker still.

### 5.3 The decision coupling that Q-M1 exposes

**V-2 cannot be decided sensibly in isolation from the S5c/ADD §6 ruling** (TODO item E7; P3B-ESC-0001: "S5c requires a separate human decision").

- If the Principal intends **never** to authorize S5c, then V-2 (ii) means **ROBUST is impossible by design**. The programme could then only ever say "no coherent theory" or "open".
- That may be acceptable, but it should be chosen knowingly. It is a substantive asymmetry: v1.8 §11.3 itself calls the likely outcome OPEN.

**Q-M1 answer.**
1. NCTF is *not* unreachable while S5c is prohibited, but it is narrow.
2. ROBUST is unreachable under V-2 (ii) and confined to {H2, H4} under V-2 (i).
3. V-2 should therefore be decided **together with** an explicit statement of intent on S5c. **[OPEN — Principal]**

## 6. Q-M2: which prereg_v2 readings rest on agent classifications?

### 6.1 Origin of every input (VERIFIED from code and frozen rules)

| H | Input (prereg_v2 extraction) | Produced by | Mechanical part |
|---|---|---|---|
| H1 | `timeline[*].change_vs_previous`, sequences of ≥ 3 points (l.270–272) | the S5 agent: a "descriptive, relative to the previous point" class over a timeline the agent builds "IN HISTORICAL ORDER" (P3b §9.8 step 1, §14.3) | none in the statistic. The order of points is agent-built (§6.2) |
| H2 | relations = keys of `contradiction_precedence` = agent-written `timeline_summary.contradicted_by` entries (`p3b_s5_r7_reconstruction.py` l.160–164) | the S5 agent. Contract rule DR-01: "CONTRADICTS ⇒ ∈ `contradicted_by` with target = predecessor" (`20260926_R7-DESIGN-REPAIR-IMPLEMENTATION-RECORD.md` l.45; `ANNEX-CLAUSE-AUDIT.md` l.149) | dates: `file_position` = A.10 file-level dated position from slice metadata (l.63–65) |
| H3 | `dependency_edges` with the target among the labels (l.275–278) | the S5 agent, "only with a source row that states the dependency" (§9.8 step 8) | none |
| H4 | `births` (any key) → S-ids → earliest dated position (l.281–293) | birth kinds: P2b mechanical detector (CANDIDATE-*-BIRTH), then agent inspection (ESTABLISHED / MOVED; S-MP l.239–241, §9.8 step 5) | dates: A.10 `file_position` |
| H5 | `absences[*].resolution == FOUND`, non-hub rows (S-PRV2 l.217–227) | stage 1 by script; FOUND requires a stage-2 agent whole-file reading (P3b §11.4) | the stage-1 hit search |

**DERIVED conclusion 1.** All five readings rest on agent classifications. The only mechanical components are the A.10 dates (H2, H4), the stage-1 hit search (H5) and the hub flag. CA-8 therefore applies to every Kind P claim, not only "where convenient". This is consistent with v1.8 §4, CA-8 ("wherever it rests on one (e.g. H4's birth kinds)") and §11.5.

### 6.2 A sharper result for H2 (DERIVED; conditional; unreviewed)

Two facts:
- **(F1)** By DR-01, every CONTRADICTS timeline point s yields a relation (s, pred(s)), where pred(s) is the immediately preceding point **in the agent's timeline order** τ.
- **(F2)** The verifier does **not** check τ against A.10 dates. `timeline[*].historical_position` is free text with "no predicate". `order` is only {ORDERED, UNORDERED-BLOCK}. Precedence "reads only slice meta and date_applies_to_file" (`ANNEX-CLAUSE-AUDIT.md` l.123–134).

> **Proposition Q2.** Let R_C be the relations arising from CONTRADICTS points under DR-01. For r = (s, pred(s)) ∈ R_C with both ends A.10-dated and untied, H2's indicator X_r = 1[d(pred(s)) < d(s)] equals 1 **iff the agent's timeline order τ and the A.10 file-date order d agree on that adjacent pair**.
>
> *Proof.* pred(s) precedes s in τ by definition (F1), and X_r compares the same two points by d. ∎

**Consequences (DERIVED, conditional on F1 and F2 and on the share of R_C among H2's relations):**
1. **On R_C, H2's forward share π equals the rate at which the agent's chronological ordering agrees with the mechanical file dates on adjacent contradiction pairs.** A rejection of π = ½ then shows that the agent orders sources more or less consistently with A.10 dates. It shows **nothing about the direction of contradictions in the domain**.
   - If agents order mostly from the same A.10 metadata, π ≈ 1 on R_C by construction. H2 would then reject for any m large enough. With T = m, P(T\* ≥ m) = 2⁻ᵐ, so the expected null count is E[K] = 999·2⁻ᵐ:
     - at m = 10, E[K] ≈ 0.98, giving p ≈ 0.002;
     - from about m = 14, E[K] < 0.07, giving p = 1/(B+1) = 0.001 with high probability.

     Both values are below α/3.
2. This sharpens the frozen caveat "PARTLY METHOD-CONSTRAINED … need not be a property of the domain" (S-PRV2 l.81–83) into a precise statement. For R_C relations, the reading may be **almost entirely** method-determined.
3. Relations entered by other routes (e.g. a `semantic_evidence.d4` CONTRADICTION row; R6 root-cause doc l.222) are not covered by Prop. Q2. Their share is unknown before the data.
4. For Q-M1: the only ROBUST path under V-2 (i), the core {H2, H4}, therefore depends on a claim whose confirmatory content may be close to nil. CA-8 (the C1 model) would have to establish otherwise.
5. Prop. Q2 also explains a dependence relevant to PO-2. Relations within one object share one agent ordering, so their orientations are not independent. That violates the independence premise of NR-P's power (Prop. 8.2). Validity of the H2 test under A1 is unaffected: A1 is the stated null.

**What Prop. Q2 does not show:**
- that H2 *will* reject;
- what share of relations falls in R_C;
- that τ and d actually agree.

All three are empirical and cannot be answered before S5 data. **No change to the frozen instrument is proposed.** A changed licensed reading for H2 would itself need a governed re-registration (GL-0133/0137). That is the Principal's matter, if at all.

### 6.3 Q-M2 answer

All five readings depend on agent classifications. The most consequential is H2, whose forward share on CONTRADICTS-derived relations is, by construction, a measure of agreement between the agent's ordering and the A.10 dates (Prop. Q2). The C1 observation-process model is therefore a precondition of **any** Kind-P-based ROBUST, not an optional refinement.

## 7. Implications

**For the theory.**
- No substantive theory claim is supportable before S5 evidence.
- The pre-data analyses (Q-M1, Q-M2) show that the *current* verdict design makes ROBUST hard to reach, and, while S5c is prohibited, very narrow or impossible.
- Whether that is the intended scientific stance is a Principal decision (V-2 with S5c intent), not a defect to be repaired by the Professor.

**For the manuscript and its architecture (Δ-1 reconsidered).**
- My recommendation is revised to follow the charter: **defer the modular split (D1)** until chapter-level review is actually commissioned (DS-5 or P3).
- Until then, the single file continues, and one small citation/status checker remains a proposal (charter §8).
- The rest of the architecture proposal (a single source register, IDs only for load-bearing items, no duplicate records) stays useful as the target.

**For the TODOs (proposals only; the Professor does not edit the S-Series TODO).**

| TODO item | Proposed change | Reason |
|---|---|---|
| l.20, l.231, l.290 "RI-1 stays DEFERRED" | "RI-1 reopened on condition 1 (GL-0104); treated by EG-2 SLICE-VIEW and RI-1b report-only (GL-0106 f)" | VERIFIED record (§2) |
| Lane V | add "V-2 to be decided with an explicit S5c intent (Q-M1)" | §5.3 |
| CA-8 / C1 model | add "C1 is a precondition of any Kind-P ROBUST; H2 orientation ≡ agent-order vs A.10 agreement on CONTRADICTS relations (Prop. Q2), to be verified" | §6 |
| PO-01 | add "H3/H5 size unknown; star scheme; H3 reachable-component null; H4 key scope; H3 self-loops; A3 single-run condition to be recorded" | Session A §6; DS-4a |

## 8. Proposed corrections, shown as diffs and NOT applied

**PO-01 note §3.4 (the reading paragraph).**
```diff
-**Reading.** In these regimes the frozen tests are **at or below nominal size (conservative)**. There is no sign of the anti-conservatism that Proposition 2 makes possible.
+**Reading (corrected 2026-10-17 after Session A's review).** The point estimates are below nominal, but the ±1.96·SE
+intervals used above are invalid this close to zero. Exact Clopper–Pearson 95% upper bounds (Session A, not recomputed by
+Session B) reach 0.056 (H3 sparse) and 0.052 (H3 hub) at nominal 0.05. The H5 row used the exact-implication statistic,
+not the frozen v2 min-p statistic. **The size of the frozen H3 and H5 tests is therefore unknown.** "Conservative" is withdrawn.
```

**PO-01 note §3.3 (the proof-route sentence).**
```diff
-- **[OPEN]** Whether this holds for all n. A proof would need to show that triangle reorientation preserves the number of 2- and 3-cycles together, which is plausible but not shown here.
+- **[OPEN]** Whether this holds for all n. The proof route "triangle reorientation preserves T_h3" is **false**: with arcs
+  0→1, 1→2, 2→0, 1→3, 3→0, T = 2; with the triangle reversed, T = 1, and the degrees are equal (Session A; checked by hand by Session B).
+  The n ≤ 4 result stands as a finite check only.
```

**PO-01 note §4 table, row H3/H5.**
```diff
-| H3, H5 (exploratory) | not exact by construction (Prop. 2); conservative in three synthetic regimes (§3.4) | DERIVED + synthetic evidence; general validity remains a **declared limitation** |
+| H3, H5 (exploratory) | not exact by construction (Prop. 2); **size unknown** (§3.4 as corrected) | declared limitation; also the H3 reachable-component null, H3 self-loops, H4 key scope (Session A §6) |
```

**Manuscript Ch. 8.10, row PO-1.** Change the standing to: "H1/H2/H4 discharged under A1–A4 (PO-01 Prop. 1; same-model, needs external review); H3/H5 open, size unknown".
**Manuscript Ch. 6.8.** Add L-S9 (A3 single-run condition), L-S10 (H4 null over all birth keys) and L-S11 (H2 orientation and agent order, Prop. Q2, conditional).
**README.** Rename RQ→REQ; record the RQ-3 status as answered by GL-0104/0106.

## 9. Requests to the Researcher (proposed queue; none sent)

| # | Request | Why |
|---|---|---|
| REQ-4 | Check Prop. Q2: (F1) the DR-01 rule as implemented, (F2) that τ is not tied to A.10 by any check, and which other routes create `contradicted_by` entries | It may change what H2 can license (Q-M1, CA-8) |
| REQ-5 | Put the RQ-1/RQ-2 answers in a file (the "last report" is not in any authorized location) | traceability |

## 10. Mathematics, logic and ML: what this changes

- **M-2, a machine-checkable verdict specification, gains a concrete target:** encode the reachability map of §5.2 and let a model checker confirm it. A finite case table like this is the ideal first property set, because every row is checkable by enumeration. This still needs DS-6.
- **M-5, the C1 rater model, is now on the critical path for any Kind-P ROBUST (§6.3).** Identifiability needs a human or gold-standard subsample (Session A §8.3). The design can be prepared (DS-7). Nothing is fitted before the data.
- **ML stays post-data and candidate-only.** Prop. Q2 is a warning about ML-free instruments too: a mechanical pipeline can measure the analyst's ordering rather than the domain. Any future ML generator must be checked the same way for whether its output is the generator's own defining property (P3b §9E.1, DESCRIPTIVE).

## 11. Decisions this assessment adds or sharpens (for the Principal; none taken)

1. **V-2 together with an S5c intent (§5.3).** Do you accept that, under V-2 (ii) with S5c prohibited, ROBUST is impossible by design?
2. **The A3 execution condition (one run, B = 999, seed 20260927).** Should it be recorded in the governance log (DS-4a)?
3. **Architecture.** Defer D1 per the charter (Δ-1)?
4. **Corrections.** Authorize the §8 corrections to the PO-01 note, the manuscript and the README (files in the Professor's workspace)?

## 12. The smallest next step and the boundaries

**Smallest next step.** The Principal reviews this assessment and decides items 1–4 of §11 together with DS-3 (branch). After that, the Professor applies only the authorized §8 diffs and stops.

**Done today.** Read-only checks; this one new file in the workspace.
**Not done.** No other file changed; nothing committed; no run, study, model check or simulation; no corpus or S5 access; run-3 not touched.
