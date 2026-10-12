# R7 · Research audit 1: substantive validity and simplicity of the theory and the adopted model

**Research audit: analysis only. It decides nothing. Draft r1.1, 2026-10-13.** r1.1 applies the targeted re-review of r1 (PASS_WITH_LIMITATIONS), recorded as rows Z1–Z8 in §0. Earlier history: r0 (2026-10-12) had an adversarial review, PASS_WITH_LIMITATIONS: the reviewer re-encoded all 83 rows independently from the T2 text and recomputed every result. r1 applies all eleven required corrections. **Corrections after review** (§0) lists them, and Q2 is re-run on a corrected encoding (Appendix C).

This audit adopts, amends, ratifies, selects and authorizes nothing. It treats every claim in the corpus as a hypothesis. A human act governs what the project may do; it does not make a mathematical or empirical claim true. The verdicts are the auditor's analysis. They are labelled [E] where they rest on the auditor's judgment.

**Standing qualifier (applies wherever a conclusion depends on the corpus):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Sources read** (TH = `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/`):
- `FORMAL-THEORY-R4.md` r3.1 (§0–§4.3, §9);
- `R6-DECISION-BRIEF.md` r4;
- `R7/T1-CONCEPT-CANDIDATES.md` r1.6;
- `R7/T2-RULE-CANDIDATES.md` r1.4;
- `R7/T3-CANDIDATE-MODEL.md` r1.9 (§0, §1a–§1e);
- `R7/E2b-ADOPTION-TEXT.md` (header, §A1, §A2);
- `R7/E3-ARCHITECTURE-PROPOSAL.md` r1.1 (§1–§3);
- `F-GOVERNANCE-LOG.md` F-LOG-0264, 0265, 0271, 0284, 0285, 0290.

Nothing else was read. Project history is not reconstructed.

**Computation.** Every number below comes from a row-to-concept encoding of the 83 T2 r1.4 rows. Three encodings are used (Appendix A):
- **A0**, the r0 transcription of the T3 r1.9 §1a crossing table;
- **A1**, A0 corrected under four explicit conventions K1–K4;
- **RV**, the reviewer's independent encoding.

All three reproduce T3's P-c totals exactly: 46 crossing rows under S, 36 under S′ and S″. Every result that is not about P-c is reported under A1 and RV, and under A0 where it differs. The scripts are kept in the session scratchpad and are not committed. Appendices A and C contain enough to re-implement them.

---

## 0. Corrections after review (r0 → r1; r1 → r1.1, rows Z1–Z8)

| # | review item | r0 statement | r1 correction |
|---|---|---|---|
| 1 | P-d "equally supported" | F5, S6 and C5 called P-d an equally supported alternative, tied with P-c | **Withdrawn (refuted).** The tie came from A0 omitting C13 in R2 ("a record that is an act"). With C13, P-d has 13 / 18 / 37 crossings (RV) or 13 / 18 / 36 (A1), against P-c's 12 / 17 / 36. r0's "35" also depended on where the unplaced C25, C26 and C28 were put. Against its own size-matched null (4│19 cuts), P-d is ordinary: P(≤) 0.15–0.43; conductance at the 69th–89th percentile; modularity ≈ 0.01–0.02 (§2 Q2) |
| 2 | Lemma 2 was "convention-free" | "Convention-free neutrality at 𝐑₀" | **Restated.** Excluding actor-valued coordinates is the S′ decision. Lemma 2 now reads: "neutral at coordinate level **if actor-valued coordinates are not counted (equivalent to S′)**". It cannot retire the spanning-rule question without deciding it. It also changes what "neutral" means relative to F-LOG-0265 and VP12 (i) (S1 restated) |
| 3 | F3 was selective | "on conductance … cannot be told apart from random" was used as the main qualifier | **Restated as measure-dependent.** P-c is strong on raw crossings, on normalized cut (p 0.0003–0.015) and on modularity against random cuts (p 0.0005–0.007). It is ordinary on conductance: the 17th–49th percentile over both row sets (T-A and T-A + T-B) and all three encodings; on T-A + T-B alone, the 24th–49th percentile for A1 and RV and the 39th for A0. It is not the modularity maximum: P-b scores higher |
| 4 | Lemma 1's S′ sub-claim | "Under S′, χ(D-chg) = 0 for every such cut" | **False; removed.** Counterexample: the cut CB1 ∪ {C2} │ rest keeps K together, yet D-chg crosses under S′ (C2 ∉ K). The asymmetry is carried by D-chg reading {α, Cap}, which is a content difference. Under P-c it is **rule-dependent**. "Artifact" is a judgment, not a result. **F1 is downgraded to medium confidence** |
| 5 | Cohesion had no null | "CB1 33% … not cohesive" | **Null added.** **[r1.1, Z2]** CB1's cohesion is not distinguishable from chance: 0.26–0.33 against a random 9-set mean of 0.35, P(random ≥ CB1) 0.51–0.94. The value depends on how co-reads are weighted: with 1/(k−1) weighting it is 0.36–0.45 against a null of 0.35 (P 0.12–0.40), and dropping rows with 10+ concepts gives 0.39–0.51. CB23 is above chance in every variant. CB23's share is far above chance (0.77–0.86 against 0.58–0.59; P ≤ 0.002). The core-periphery conclusion is restated on this null |
| 6 | p-values read as inferential | small p-values implied significance | **Marked descriptive.** P-c was chosen post hoc, and uniform random labelling is a weak null. The informative comparison is with size-matched sort-aligned cuts (§2 Q2) |
| 7 | Q4 tautologies | S′ ≡ S″, 27/27 membership and 73/83 presented as findings | **Marked definitional.** "Exactly 10" holds under T3's conventions; across the S borderlines the range is 8–16. The 21/24 figure is T2's own reading-sensitivity column (Table 9.4 readings), now kept apart from the spanning readings |
| 8 | S5 (17 elements) | "loses only the kind labels" | **Corrected.** S5 also loses the participant's VP12 (i) spanning status and E3-PP: folding C1 into CG1 restores the superseded r1.6 placement. It loses the per-element observability tags (CE6 obs; CG4 mixed) and the §1d guard (no order, no production: X1, N3). S5 is admissible only if all of these are preserved |
| 9 | C24 / CG6 | "C24 is read by no T2 statement" | **Reason added.** C24 has no R4 coordinate (it is corpus-only: B06, A11, A15), so no T2 row can read it under rule 2 |
| 10 | Appendix A | presented as complete | **Inconsistencies disclosed:** C13 for ρ (R2, R3, R40); read-through of "conforms" (R33 and R34 vs R32 / B-conf); E mapped to C8 vs C8 + C9; class-rule actor flags (R24, R82); the R66 flag. A0 is a transcription of T3's crossing table. It is sufficient for the P-c verdict but understates crossings for other cuts. A1 fixes these under conventions K1–K4 |
| 11 | Misc. | "T-A core cannot encode H-D/H-B whatever the cut"; L4 "medium"; L7 wording | R12 and R13 are reading-relative, so the claim is softened (Q1). L4's "low crossing" is robust: medium-high. L7 notes that F-LOG-0269 and E3 already label consistency as a human design decision |
| — | Q2 re-run | — | Re-run on A0, A1 and RV with size-matched nulls for every candidate cut (P-a, P-b, P-c, P-d and three sort-aligned cuts), including modularity against random (§2 Q2; Appendix C) |
| — | S1–S6 | — | Re-assessed (§2 Q4 table; §3) |
| Z1 | (r1.1) specificity wording | "the sort-aligned acts and norm cuts also beat their nulls" | F5 and the Q2 bullets are qualified "on raw crossings". "Acts and norm" becomes "acts, norm and work". Added: on conductance every sort-aligned cut (0–3rd percentile) scores better than P-c (24th–49th) |
| Z2 | (r1.1) CB1 cohesion | "CB1 at or below chance" | "CB1's cohesion is not distinguishable from chance". Weighting sensitivity added: 1/(k−1) gives 0.36–0.45 against 0.35 (P 0.12–0.40); without rows of 10+ concepts, 0.39–0.51. CB23 is above chance in every variant. Applied in item 5, F4, Q2 and S6 |
| Z3 | (r1.1) "separation comes from sparsity" | as quoted | the records side is sparse, and still crosses fewer rows than equal-volume 9-sets (P(≤) 0.0001–0.003 at ±0, ±2 and ±5 volume), without detectable internal cohesion. This also supports F3 |
| Z4 | (r1.1) F3's T-A clause | "robust across three encodings" | on T-A, 228 (A0), 92 (A1) and 9 (RV) cuts beat P-c, so its T-A rank depends on the encoding and is not minimal. Robustness is limited to T-A + T-B, which includes the 18 inactive T-B slots |
| Z5 | (r1.1) tie counts; conductance ranges | "exactly P-c's count: 8 / 3 / 1"; mixed percentile ranges | the tie counts include P-c, and under RV P-c is the unique minimum. Ranges: 17th–49th covers both row sets and all encodings (item 3); 24th–49th covers T-A + T-B for A1 and RV (§2, §4) |
| Z6 | (r1.1) A1 residuals | none listed | listed in Appendix A.2 (R58, R63, R72, R83, R41, R52, R11). No reported figure changes. Freeze the encoding only after Q6 test 1 |
| Z7 | (r1.1) S5 arithmetic | "17 in place of 27" | the 3 elements outside the 10 + 14 are named: MW1, CB1, CB23 |
| Z8 | (r1.1) multiple comparisons | none noted | about 170 comparisons, uncorrected. P-values between 0.02 and 0.05 carry no weight (e.g. P-d's modularity p 0.04; the work cut) (§4) |

---

## 1. Executive findings

| # | claim (as found in the corpus) | verdict | confidence | consequence |
|---|---|---|---|---|
| F1 | Under P-c, VP12 (i) and spanning rule S, the 𝐑₀ cut is H-D-asymmetric in the change clauses (D-chg crosses; B-chg does not) | **rule-dependent under P-c.** It holds under S and fails under S′ and S″. It is carried by a real content difference: D-chg reads the issuing actor and its capability {α, Cap}, and B-chg does not. Under S it holds for every cut that keeps B-chg's and B-conf's concepts on one side (Lemma 1). Calling it an "artifact" is a judgment [E], not a result | **medium** | whether the cut is "neutral" cannot be settled without choosing how the actor counts. Lemma 2 shows that the S′ choice yields coordinate-level neutrality, but it does not remove the choice |
| F2 | Under R-i and R-i′, the record clauses are H-D-asymmetric (D-rec crosses; the H-B side has no record-crossing clause) | **holds.** It is substantive and the same under S, S′, S″, P-d and the coordinate-level criterion | high | it is the real reading-dependent limitation of the cut. F-LOG-0265 already records it |
| F3 | P-c (records │ norm-and-work) is a low-coupling cut | **holds conditionally; measure-dependent.** It is strong on raw crossings, on normalized cut and on modularity against random cuts. **[r1.1, Z4]** Its rank depends on the row set:
  - on T-A + T-B, no cut of size 9│14 has fewer crossings on A1 or RV (1 on A0);
  - on T-A alone, 228 (A0), 92 (A1) and 9 (RV) cuts have fewer, so its T-A rank depends on the encoding and is not minimal.

  **[r1.1, Z3]** The records side is sparse, and it still crosses fewer rows than random 9-sets of equal volume (P(≤) 0.0001–0.003 at volume ±0, ±2 and ±5). It is ordinary on conductance. It is not the modularity maximum (P-b scores higher). The p-values are descriptive (P-c was chosen post hoc) | medium-high | the low-crossing property is robust across the three encodings **on T-A + T-B only**, a row set that includes the 18 inactive T-B slots |
| F4 | CB1 and CB23 are candidate **bounded contexts** | **not supported by structure; a modelling choice** [E]. Against a null: CB23 is cohesive far above chance, but CB1's cohesion is not distinguishable from chance under any of the weightings tested. CB23 is above chance in every variant. The pattern is a cohesive norm-and-work core plus a records periphery. The records side is sparse, and still crosses fewer rows than random 9-sets of equal volume (P(≤) 0.0001–0.003 at volume ±0, ±2 and ±5), without detectable internal cohesion. DDD's ownership and relationship criteria have no evidence (E3 §2.3 and §2.4 already mark them [O]) | medium | the ⚠E3b outputs are better described as one candidate module cut over one model than as two bounded contexts |
| F5 | The records cut is special among candidate cuts | **holds, as a descriptive result.** On raw crossings, the cuts that contain the records side (P-c, P-a, P-b) beat their size-matched nulls. Sort-aligned cuts do not, except the norm cut, which is marginal (p 0.05–0.11). P-d is ordinary. On normalized measures the sort-aligned acts, norm and work cuts also score well. On conductance every sort-aligned cut (0–3rd percentile, T-A + T-B) scores better than P-c (24th–49th). So specificity holds on raw crossings only | medium | **on raw crossings**, the structural support is specific to separating the records |
| F6 | H-D vs H-B are not separable by records (Prop 4.1; Prop 9.6, O-form) | **holds** formally, within R4. The proof follows almost directly from A1, A2 and A5. Its relevance to the corpus rests on Obs O3 and O4, on A5, and on the H5 waiver | high (formal); low to medium (as a fact about the domain) | the non-identifiability is a property of the observation model, not an empirical discovery |
| F7 | The R4 primitives are adequate to the domain | **unresolved.** Only satisfiability is shown (bK1); the act layer is not reliably codable (F17, B11) | low | every element and both cells depend on it |
| F8 | The spanning-reading machinery (S, S′, S″) is needed to describe the adopted model | **mostly unnecessary on rows.** S″ coincides with S′ on all 83 rows (definitional; see Q4). Only 7 hostings vary under T3's conventions: 5 T-A frame-typing rows and 2 inactive T-B slots | high (counts); the S vs S′ choice remains human | collapse S″ into S′ (S2). The choice between S and S′ stays a human question |

---

## 2. Audit questions

### Q1. Is the H-D asymmetry under P-c substantive, or an artifact of the counting convention?

**Premises.**
- **P1 (Def 3.1, 𝐑₀).**
  - D-chg: for every (t, τ) ∈ Chg(h) there is a ∈ E^iss with τ(a) = τ, f(a) = t, v(a) = σ(t)(τ) and **(α(a), iss) ∈ Cap**.
  - B-chg: the same act disjunct **without** a capability or status condition, or some w ∈ W with t(w) < t.
  - B-conf: every w conforms.
- **P2 (T3 rule 2).** Statements are placed by concept. α(a) is an actor item (C1); Cap is C2.
- **P3 (VP12 (i), F-LOG-0268).** C1 spans CB1 and CB23. Rule 2a states S (both sides), S′ (neither side) and S″ (the located side).
- **P4 (K-c).** A statement crosses iff it reads an item on CB1 and an item on CB23.
- **P5 (Def 1.6, 1.7).** The record coordinates are R, r, c, k, Ref and con. Actors 𝒜 are a separate item set. k(ρ) ∈ 𝒜 is the only actor-valued record coordinate.

**Formal statement.** Write κ(x) for the concepts read by statement x, excluding C1, and A(x) = 1 iff x names an actor. Then:
- χ_S(x) = 1 iff |sides(κ(x))| ≥ 2 or A(x) = 1;
- χ_S′(x) = 1 iff |sides(κ(x))| ≥ 2;
- χ_S″(x) = 1 iff |sides(κ(x)) ∪ {loc(x)}| ≥ 2.

The clauses read:
- κ(D-chg) = {C2, C3, C5, C6, C7, C8, C12}, with A = 1;
- κ(B-chg) = {C3, C5, C6, C7, C8, C12, C19, C20}, with A = 0;
- κ(B-conf) = {C3, C4, C6, C19, C20, C21}, with A = 0.

**Lemma 1 (S-asymmetry for any cut keeping K together).** Let Π be a cut with at least two non-empty sides, and let K = κ(B-chg) ∪ κ(B-conf) = {C3, C4, C5, C6, C7, C8, C12, C19, C20, C21} lie on one side. Under S, D-chg crosses, and B-chg and B-conf do not.
*Proof.* A(D-chg) = 1 forces χ_S = 1. B-chg and B-conf name no actor, and their reads lie inside K. ∎

- **[r1] What Lemma 1 does not say.** r0 added: "under S′, χ(D-chg) = 0 for every such cut". **That is false.** C2 ∉ K, so the cut CB1 ∪ {C2} │ rest keeps K together while D-chg crosses under S′. The correct statement: under S′, D-chg crosses exactly when the cut separates C2 from the rest of κ(D-chg). Under P-c it does not separate them, so χ_S′(D-chg) = 0 **under P-c**.
- Under S″, D-chg locates the actor with Cap (CB23), so χ_S″(D-chg) = 0 under P-c.

**What carries the asymmetry.** D-chg differs from B-chg in **content**: it reads {α, Cap}, the issuing actor and its capability (HYP: "by actors holding the capability"). B-chg excludes this in so many words. Whether that difference shows up as a crossing depends on the cut (where C2 lies) and on the spanning rule (where α lies). **Under P-c, the asymmetry is rule-dependent:** present under S, absent under S′ and S″. To call it an artifact is a judgment [E] that rule S is the inappropriate way to count a spanning concept. That is not a derived result.

**Lemma 2 (coordinate-level neutrality, conditional on S′).** Count only R4's record coordinates (P5), and **do not count actor-valued coordinates.** This is the S′ decision for the actor.
- D-chg, D-st, B-chg and B-conf read no record coordinate.
- D-rec and B-recW each read Ref and R.
- Hence 𝐑₀ is symmetric under this criterion.

*Proof:* inspection of Def 3.1 (Appendix C, item C6). ∎

- **[r1] Status of Lemma 2.** Excluding actor-valued coordinates **is** S′. Lemma 2 is therefore "neutral at coordinate level if actor-valued coordinates are not counted (equivalent to S′)". It is not convention-free. It also changes what "neutral" means: F-LOG-0265 and VP12 (i) treat the actor as spanning both sides, and Lemma 2 treats it as on neither. **A human cannot use Lemma 2 to retire the spanning-rule question without deciding it.**
- Under R-i and R-i′ (the H-B side reads no record coordinate), the asymmetry persists under every convention tested: S, S′, S″, P-d and Lemma 2's criterion (F2).

**Counterexamples tested.**
- (a) **Read Adopted through** (T3 §1a (e)). D-st then names actors, and the S-asymmetry widens to the status clause.
- (b) **Cut CB1 ∪ {C2} │ rest.** D-chg crosses under S′ while B-chg does not (correction 4).
- (c) **P-d with C1 spanning.** The S-asymmetry is reproduced (Lemma 1). With C1 placed as an ordinary unobserved concept, it disappears.
- (d) **Spanning dependence across conventions.** The number of rows whose status changes between S and S′ is 10 under T3's conventions. Across T3's S borderlines (§1a (e)) it ranges from 8 to 16.

**Verdict.** The 𝐑₀ change-clause asymmetry under P-c is **rule-dependent**. It rests on a real content difference ({α, Cap} in D-chg). It appears under S and not under S′ or S″, and its width depends on the read-through convention. Medium confidence. The R-i / R-i′ record-clause asymmetry **holds**.

**What follows for "neutral under 𝐑₀".** The premise is convention-relative, and no counting rule makes it convention-free. The options open to the human are unchanged: choose S or S′ (S″ coincides with S′ on rows), or keep the per-reading statement. The asymmetry concerns only R25 and R38, which are inactive T-B slots and cannot be checked from records (R82). **[r1] Softened from r0:** most T-A rows hold in every history of 𝓗, but R12 and R13 (implications) and R24 are reading-relative (T2's own column). So the claim is that the T-A core does not *assert* H-D or H-B. It is not that its hosting is reading-independent.

### Q2. Do CB1 and CB23 have any property beyond being the two cells of a chosen partition?

**Premises.** A DDD bounded context needs:
- (i) a distinct language or model;
- (ii) distinct ownership or responsibility;
- (iii) an explicit relationship to other contexts.

Structural proxies for these are cohesion (concepts on one side are read together) and low coupling (few statements read both sides).

**Method.** Full detail is in Appendix C.
- **Encodings:** A0, A1 and RV (Appendix A). Concepts C1, C22, C25, C26 and C28 are excluded from every cut (C1 = S′; the others unplaced), so every cut is a partition of the same 23 placed concepts.
- **Candidate cuts:**
  - P-c (9│14);
  - P-a (9│11│3);
  - P-b (9│6│8; the auditor's [E] placement of C2–C5);
  - P-d (4│19);
  - three sort-aligned cuts: acts {C2, C8–C12} │ rest (6│17); work {C19–C21} │ rest (3│20); norm {C3–C7} │ rest (5│18).
- **Measures:**
  - raw crossings;
  - conductance, cut / min vol;
  - normalized cut, Σ cut / vol_i;
  - modularity of the clique-expanded co-read graph.
- **Null for each cut:** 8,000 random partitions of the 23 concepts into the **same side sizes**, seed 20261013; 2,000 of them for modularity.
- **Cohesion null:** 2,000 random 9│14 partitions.
- **Exhaustive check:** P-c's rank among all 817,190 cuts of size 9│14.
- **Status of the p-values: descriptive.** P-c was chosen post hoc by a human (F-LOG-0265), and uniform random labelling is a weak null. The informative comparison is between candidate cuts, each against its own size-matched null.

**Results, rows T-A + T-B (42), encodings A1 / RV.** T-A-only results agree in direction (Appendix C).

| cut | crossings | null mean | P(null ≤) | conductance percentile | normalized-cut p | modularity Q | P(null Q ≥) |
|---|---|---|---|---|---|---|---|
| **P-c** records │ norm-and-work | 17 / 17 | 34.7 / 33.6 | < 0.0001 / < 0.0001 | 49% / 24% | 0.004 / 0.0003 | 0.066 / 0.090 | 0.007 / 0.001 |
| P-a records │ acts + σ │ work | 26 / 25 | 37.0 / 36.0 | 0.0003 / < 0.0001 | 2% / 2% | 0.003 / < 0.0001 | 0.069 / 0.104 | 0.004 / < 0.001 |
| P-b records │ acts │ σ + work | 27 / 27 | 37.9 / 36.9 | < 0.0001 / < 0.0001 | 33% / 11% | < 0.0001 / < 0.0001 | **0.133 / 0.132** | < 0.001 / < 0.001 |
| P-d observed │ unobserved | 18 / 18 | 25.5 / 24.7 | 0.15 / 0.16 | 87% / 71% | 0.80 / 0.59 | 0.011 / 0.012 | 0.04 / 0.04 |
| sort: acts │ rest | 26 / 26 | 30.7 / 29.7 | 0.22 / 0.25 | 0% / 0% | 0.002 / 0.005 | 0.049 / 0.035 | 0.005 / 0.014 |
| sort: work │ rest | 20 / 19 | 21.7 / 21.0 | 0.45 / 0.43 | 3% / 2% | 0.03 / 0.02 | 0.014 / 0.025 | 0.017 / 0.007 |
| sort: norm │ rest | 20 / 19 | 28.5 / 27.6 | 0.11 / 0.07 | 0% / 0% | < 0.0001 / 0.0001 | 0.058 / 0.068 | 0.001 / 0.0005 |

**Exhaustive rank.** Among the 817,190 cuts of size 9│14, the number with strictly fewer crossings than P-c:

| rows | A0 | A1 | RV |
|---|---|---|---|
| T-A | 228 | 92 | 9 |
| T-A + T-B | 1 | 0 | 0 |

On T-A + T-B, the cuts with exactly P-c's count number 8 (A0), 3 (A1) and 1 (RV). **These counts include P-c itself.** So under RV, P-c is the unique minimum over all 9│14 cuts (T-A + T-B).

**Cohesion against a null** (P-c; T-A / T-A + T-B):

| encoding | CB1 inside share | random 9-set mean | P(random ≥ CB1) | CB23 inside share | random 14-set mean | P(random ≥ CB23) |
|---|---|---|---|---|---|---|
| A0 | 0.33 / 0.34 | 0.35 | 0.60 / 0.51 | 0.81 / 0.86 | 0.59 / 0.58 | < 0.0005 |
| A1 | 0.26 / 0.26 | 0.35 | 0.94 / 0.87 | 0.80 / 0.85 | 0.59 | < 0.0005 |
| RV | 0.32 / 0.33 | 0.35 | 0.68 / 0.57 | 0.77 / 0.83 | 0.59 | 0.0015 / 0.001 |

**Reading of the results.**
- **On T-A + T-B, the low-crossing property of P-c is robust across all three encodings.** On A1 and RV, no cut of size 9│14 has fewer crossings over these rows. The row set includes the 18 inactive T-B slots. **[r1.1, Z4]** On T-A alone, 228 (A0), 92 (A1) and 9 (RV) cuts beat P-c: its T-A rank depends on the encoding and is not minimal. Normalized cut and modularity against random agree.
- **[r1.1, Z3] Sparse, but not only sparse.** Against random 9-sets of equal volume (±0, ±2, ±5), P-c still crosses fewer rows (P(≤) 0.0001–0.003).
- **It is not robust on conductance.** Conductance penalizes the low-volume records side: P-c is at the 24th–49th percentile on T-A + T-B for A1 and RV, and at the 17th–49th over both row sets and all encodings. It is also not the modularity maximum: P-b scores higher on every encoding.
- **On raw crossings, the structure is specific to separating the records** (F5). Every cut that contains the records side (P-a, P-b, P-c) beats its size-matched null on raw crossings. None of the sort-aligned cuts does (norm is marginal at p 0.07–0.11). **P-d is ordinary** on raw crossings, conductance and normalized cut.
- **On normalized measures, the sort-aligned acts, norm and work cuts also beat their nulls.** On conductance, every sort-aligned cut (0–3rd percentile, T-A + T-B) scores better than P-c (24th–49th). So "only the records cut is significant" holds on raw crossings only.
- **Core-periphery, now with a null.**
  - CB23 is internally cohesive far above chance.
  - **[r1.1, Z2]** CB1's cohesion is not distinguishable from chance: 0.26–0.33 against a random 9-set mean of 0.35. With 1/(k−1) weighting it is 0.36–0.45 (P 0.12–0.40). Dropping rows with 10+ concepts gives 0.39–0.51. CB23 is above chance in every variant.
  - **[r1.1, Z3]** The records side is sparse: record concepts are read by few rows, mostly together with CB23 concepts. It still crosses fewer rows than random 9-sets of equal volume (P(≤) 0.0001–0.003 at ±0, ±2 and ±5), without detectable internal cohesion. The records form a periphery attached to a cohesive core, not a second cohesive module.
- **C24 (execution trace) has no R4 coordinate** (corpus-only: B06, A11, A15). Under rule 2 no T2 statement can read it, so CG6 has no statement-level constraint content (E3 §3.2's method exception agrees).

**DDD criteria against the evidence.**
- **(i) Distinct language.** The vocabularies are disjoint, but **by construction**: any concept partition gives disjoint vocabularies. Both sides are fragments of one formal model, a single history tuple h (Def 1.7), bound by R2, R3, R9 and R22. Under VP12 (i) the shared actor term means the same thing on both sides, with "no role separation". **Not supported.**
- **(ii) Ownership.** There is no evidence. Nothing is enforceable from records (R23). Actor-identity ownership is [O] (E3 §2.4). **Not supported.**
- **(iii) Relationship.** The context-map pattern is [O] (E3 §2.3). T3 says a crossing "is not a boundary relation". **Not supported.**

**Verdict.**
- P-c as a **low-crossing cut: holds conditionally.** It is robust on raw crossings, normalized cut and modularity against random. It is ordinary on conductance and not the modularity maximum. The p-values are descriptive. Medium-high confidence.
- CB1 and CB23 as **bounded contexts: not supported by structure; a modelling choice** [E]. The structure supports "a cohesive norm-and-work core plus a records periphery". Medium confidence.
- **P-d: not an equally supported alternative** (correction 1).

### Q3. Load-bearing claims

| # | claim | evidence | if false | rests on a ruling? |
|---|---|---|---|---|
| L1 | **Non-identifiability** of H-D vs H-B on records (Prop 4.1; Prop 9.6, O-form; R82) | formal: strong (proved; finite within-family checks). Empirical: medium (Obs O3, O4; the A5 modelling choice) | the neutral, all-inactive T-B design loses its justification | partly: H5 was adopted with the F-LOG-0190 condition knowingly waived (R6 brief §9 note 1); cross-family is waived (F-LOG-0245) |
| L2 | **Adequacy of the R4 primitives** (bK1, OQ9) | weak: satisfiability only; one corpus; the act and norm layer is not codable (F17, B11) | every element, both cells and all aggregates lose their domain meaning | no; [E] |
| L3 | **Acts are identifiable in records** (OQ11, R77) | weak or negative: within-family, not reliably codable | CB23 and CG2–CG5 have no populated instance | no |
| L4 | **P-c is the right cut** (VP14) | **medium-high** for "low crossing" (robust across three encodings; Q2). Weak for "contexts" | the ⚠E3b outputs (E3-BC1, E3-BC23, E3-REL, E3-PP) lose their basis as contexts. They keep it as a cut | **yes**: F-LOG-0265, "a human design decision, not derived" |
| L5 | **VP12 (i): one shared participant** | none either way: C1 identity is unobserved (Lem 1.12) | the S/S′/S″ question and the S-asymmetry disappear | **yes**: F-LOG-0268 |
| L6 | **Per-reading host lists** (ratified, R-a) | weak as method: they are the output of the unratified K-c rules 1–5 and 2a. The S borderlines give 44–52 crossings, and 8–16 rows vary between S and S′ | the ⚠E3b host statements change for a handful of rows | **yes**: F-LOG-0290 ratifies an output of an unratified method (E2b §A2.2 discloses this) |
| L7 | **A consistency notion for CG1–CG6** (VP11 (ii); C-α (b)) | none from R4; no enforcement (R23). **F-LOG-0269 and E3 §3.1 already label it "a human design decision introducing a consistency notion not supplied by R4"**, so it is not presented as structure | E3-AG1–AG6 are clusters of concepts only | **yes**: F-LOG-0269, F-LOG-0275 |
| L8 | **Within-family evidence suffices** | medium: 67/67 blind agreement between two Claude programs; the shared interpretation is untested (R6 brief §8). This audit's review was also within-family | correlated interpretation errors go undetected everywhere | **yes**: a waiver (F-LOG-0245) |

Two rulings are procedural, with little substantive weight: VP10 counting for O3 (F-LOG-0264: "resolved by this human ruling. It was not resolved by evidence"), and C-γ, returned for analysis.

**Weakest load-bearing claims.**
- **L2 and L3** are weakest by evidence and carry the most.
- **L7** has no evidential support. Its governance record already discloses that it is a design decision.
- **L6** is the weakest relative to its governance status: a ratified output of an unratified, encoding-sensitive method.
- **L5** has no evidence either way.
- **L1** is the strongest formally. Its strength is largely definitional.

### Q4. Simplicity

**Invariance across S, S′ and S″.** **[r1] Each figure is marked as definitional or as a finding.**

| layer | invariant | reading-dependent | status |
|---|---|---|---|
| S′ vs S″ on rows | identical on 83 / 83 | — | **definitional under P-c.** Every actor-flagged row either locates the actor on a side its other concepts already occupy (flag N with Cap on CB23; R8's keeper with k and C13 on CB1), names an unlocated actor (U), or crosses anyway (R70). So S″ adds no side that S′ lacks |
| element membership (27 elements) | 27 / 27 | 0 | **definitional.** Membership is by concept; the spanning rules count statements, not members |
| crossing status, 83 rows | 73 = 83 − 10 | 10 under T3's conventions (R5, R6, R8, R10, R19; R25, R38; R63; R71, R76). The range is 8–16 across the S borderlines | 73 is definitional given 10. The finding is the range 8–16 |
| hosting of T-A / T-B rows | 19 / 24; 16 / 18 | 5 frame-typing rows; 2 inactive slots | a finding under T3's conventions |
| **Table 9.4 readings** (a different axis from the spanning readings) | 21 / 24 T-A rows reading-robust | R12, R13, R24 | **T2's own reading-sensitivity column**, restated, not computed |

**Simplifications, re-assessed in r1.**

| id | simplification | evidence | risk | assessment |
|---|---|---|---|---|
| S1 | **[r1 restated]** Record Lemma 2 as "neutral at coordinate level if actor-valued coordinates are not counted (equivalent to S′)". Keep the R-i / R-i′ asymmetry as the substantive limitation | Lemma 2 (conditional) | it does not retire the spanning question. Adopting the S′ reading of neutrality is itself the human choice of S′ | only in this restated form |
| S2 | Collapse S″ into S′ on rows. The choice between S′ and S stays a human choice | 0 of 83 rows differ (definitional) | none for the collapse. **[r1]** The r0 label "shared kernel" is dropped: E3 proposes no context-map pattern | the collapse is fine; S vs S′ stays human |
| S3 | Restrict crossing and hosting statistics to T-A (and optionally T-B); drop T-C and T-D from boundary counts | excluded and open rows contribute 19 of 36 crossings (S′) and 22 of 46 (S); they are not model content | the record of which excluded rows would cross is lost from the counts, though it stays in T2 | **proposal-worthy** |
| S4 | Keep the T-B slots as one VP1 register list until a slot is activated | all 18 are inactive and uncheckable (R82) | lost: a precomputed impact analysis for activation | **proposal-worthy** |
| S5 | Present the 10 concept-redundant elements (CE1–CE6, CV2, CV5, CV-text, the participant) as attributes of the 14 concept-bearing elements, giving 17 in place of 27. **[r1.1, Z7]** 10 + 14 = 24. The 3 elements this leaves unaccounted for are MW1 (the model-wide constraint set) and the two cells CB1 and CB23. They carry no concepts of their own and are kept, so 14 + 3 = 17 | concept-subset relation (r0 Q4) | **[r1]** this loses more than the kind labels: (a) the participant's VP12 (i) spanning status and E3-PP (folding C1 into CG1 restores the superseded r1.6 placement); (b) the per-element observability tags (CE6 obs; CG4 mixed); (c) the §1d guard ("no order … no production", X1, N3) | **conditional:** admissible only if (a)–(c) are preserved |
| S6 | Carry P-c as one candidate module cut over one model, not as two bounded contexts | Q2: the DDD criteria are unmet; the cut is robust on crossings (T-A + T-B); CB1's cohesion is not distinguishable from chance | the ⚠E3b outputs and Amendment 2's per-reading cells would need relabelling, not re-derivation | **keep the relabel. The P-d half (r0) is withdrawn** |

### Q5. Reversibility of the human rulings

| ruling | what depends on it | cost to reverse | why |
|---|---|---|---|
| **VP10 counts for O3** (F-LOG-0264) | O3 gating only | **cheap** | procedural. No element, trace or host list depends on it |
| **A2 trace rulings** (F-LOG-0284; Amendments 1 and 2) | the trace cells of CB1 and CB23; the ⚠E3b flag | **cheap in content, costly in procedure** | no element changes. D11–D13 and the in-place tags would need re-tagging |
| **R-a ratification of the host lists** (F-LOG-0290) | E2b §A1.3, §A1.4 and §A2.2; E3-REL | **moderate** | the reopen condition is pre-registered (E2b §A2.7). The content is small, but the ratified status gives an encoding-sensitive [E] output a stability it has not earned (L6) |
| **C-γ returned** | the labels of the guard rows | **cheap** | it is a deferral |
| **Consistency only** (C-α (b); VP11 (ii)) | E3-AG1–AG6 (accepted, F-LOG-0284) | **cheap in content, moderate in procedure** | the notion has no semantics or enforcement (R23). It is already labelled a design decision |
| *(for comparison)* VP14 = P-c, VP12 = (i) | T3 r1.6–r1.9; E2b Amendments 1 and 2; the ⚠E3b part of E3 | **expensive** | this is where the re-derivation cost sits. S6 would only relabel |

### Q6. Smallest next tests

1. **Reconcile the two independent encodings** (analysis only; within existing evidence; within-family). A1 and RV now exist, built from different sources (T3's table vs T2's text).
   - Freeze one encoding, with conventions K1–K4 or their replacement, as the reference for any future K-c or host-list work. **[r1.1, Z6]** Freeze it only after this test, because A1 has known residuals (Appendix A.2).
   - Report how the S-vs-S′ row set (8–16) varies under the frozen conventions.
   - This directly bounds L6.
2. **Reading-by-family table of record-coordinate reads** (analysis only).
   - For every clause of 𝐑₀ and of each Table 9.4 reading, tabulate the record coordinates it reads, under S and under S′.
   - This is the whole evidence base for the restated S1, and it shows exactly where the actor convention matters.
3. **A non-Claude blind test of act identifiability** (OQ11, R77), or V6 (a second corpus). **This needs new authorization**: evidence collection beyond the existing corpus is not authorized, and V2 and V6 are unauthorized. It bears on the weakest load-bearing claims (L2, L3, L8).

---

## 3. Recommended corrections or simplifications

| # | recommendation | tag |
|---|---|---|
| C1 | Record that the 𝐑₀ change-clause asymmetry under P-c is rule-dependent and carried by D-chg's {α, Cap} content (Lemma 1, as corrected) | analysis only (a T3 note would need a proposal) |
| C2 | If the human wants a coordinate-level neutrality statement, use Lemma 2 only in its restated form: "neutral at coordinate level if actor-valued coordinates are not counted (equivalent to S′)". It does not retire the spanning question | needs proposal, then needs human act |
| C3 | Collapse S″ into S′ on rows. S vs S′ stays a human choice (S2) | needs proposal |
| C4 | Restrict crossing and hosting statistics to T-A (and T-B) (S3). Keep the T-B slots as one VP1 list until activated (S4) | needs proposal |
| C5 | Relabel CB1 and CB23, and the ⚠E3b outputs, as one "candidate module cut over one model" rather than two bounded contexts (S6). **The r0 recommendation to record P-d as equally supported is withdrawn** | needs proposal, then needs human act |
| C6 | Record that the R-a ratified host lists depend on an unratified, encoding-sensitive method. Either ratify a frozen encoding and method after Q6 test 1, or downgrade the lists to "report" | needs human act |
| C7 | Note that CG6 has no statement-level constraint content, because C24 has no R4 coordinate | analysis only |
| C8 | Consider S5 only with (a)–(c) of the Q4 table preserved | needs proposal |
| C9 | Run Q6 tests 1 and 2 before any further amendment that cites host lists or the asymmetry | analysis only |
| C10 | Q6 test 3 (non-Claude act identifiability, or V6) | needs human act (new authorization) |

---

## 4. Limitations of this audit

- **Λ7: Claude-family.** The auditor (Opus) and the reviewer are Claude-family, as is every analyst, reviewer and coder audited. Shared interpretive errors would not be detected. The "independent" re-encoding is independent in authorship only.
- **Encoding judgment.** A0, A1 and RV differ on many rows (A0 → A1 alone changes 33). The headline P-c results agree across all three. Cohesion of CB1 (0.26–0.33) and the conductance percentile (24%–49%) vary with the encoding. The P-b side assignment of C2–C5 is the auditor's [E].
- **Structural proxies only.** The measures describe co-reading in statement text. They say nothing about domain coupling, ownership or runtime dependency.
- **Descriptive statistics.** P-c was chosen post hoc. Uniform random labelling is a weak null. **[r1.1, Z8] Multiple comparisons.** The Q2 tables hold about 170 comparisons (cuts × measures × row sets × encodings), and no correction is applied. P-values between 0.02 and 0.05 carry no weight. Examples: P-d's modularity p of 0.04, and the work cut's p-values on normalized cut and modularity.
- **Scope.** Only the listed sources were read. E3 §4–§12, the register, the ARB package, V1A and the R4 proofs beyond §4.3 were not audited.
- **No new evidence.** Nothing here bears on H-D vs H-B themselves.
- **Review status.** r0 reviewed (adversarial re-encoding); r1 re-reviewed PWL; r1.1 fixes not re-reviewed.

Standing qualifier (verbatim): "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## Appendix A. Encodings

### A.0 Concept vocabulary (shared by A0, A1 and RV)

**Concepts** (T1 C1–C28):
- placed under P-c, CB1: C13–C18, C23, C24, C27;
- placed under P-c, CB23: C2–C12, C19–C21;
- unplaced: C22, C25, C26, C28;
- spanning: C1.

**Coordinate → concept** (T3 rule 2):

| coordinate | concept |
|---|---|
| records / O / record ids | C13 |
| r | C14 |
| c | C15 |
| k | C16 |
| Ref | C17 |
| con / mode | C18 |
| absence | C23 |
| Cap | C2 |
| target τ | C3 |
| V, P | C4 |
| norm / v | C5 |
| σ | C6 |
| Chg | C7 |
| issue act | C8 |
| adoption act / obj | C9 |
| prep / Adopted | C10 |
| d | C11 |
| f | C12 |
| work event | C19 |
| t(w) | C20 |
| conformance | C21 |
| independence | C25 |
| attestation Q | C26 |
| limitation kind | C28 |

C24 has no R4 coordinate.

**Actor flags** (location used by S″):
- N = actor of an act or work event, or Cap holder (side of C2);
- R = keeper (side of C16);
- U = unlocated;
- B = both.

### A.1 Encoding A0 (r0; a transcription of T3 r1.9 §1a's crossing table)

**Clause sets.**
- D-chg = {2, 3, 5, 6, 7, 8, 12} plus the actor;
- D-st = {8, 10, 12};
- D-rec = {8, 13, 17};
- B-chg = {3, 5, 6, 7, 8, 12, 19, 20};
- B-conf = {3, 4, 6, 19, 20, 21};
- B-recW = {13, 17, 19};
- CLASS = their union (T3 rule 3).

| row | concepts | actor | row | concepts | actor | row | concepts | actor |
|---|---|---|---|---|---|---|---|---|
| R1 | 3,5,6,8,12 | – | R25 | D-chg | N | R52 | 8,15,18 | – |
| R2 | 8,17,18 | – | R26 | D-chg − {2} | – | R53 | ∅ | – |
| R3 | 8,11,14,18 | – | R27 | D-st | – | R54 | 5 | – |
| R4 | 8,9 | – | R28 | D-st | – | R55 | 8,9 | – |
| R5 | 3,4,5,8,9,10,11,12 | N | R29 | D-rec | – | R56 | 2 | – |
| R6 | 3,19,20 | N | R30 | B-chg | – | R57 | ∅ | – |
| R7 | 13,14,15 | – | R31 | B-chg | – | R58 | 5,8 | – |
| R8 | 13,16 | R | R32 | B-conf | – | R59 | 13,28 | – |
| R9 | 8,9,13,17,18,19 | – | R33 | 19,21 | – | R60 | 2 | – |
| R10 | 2 | N | R34 | 19,21 | – | R61 | 2,7,8,10 | – |
| R11 | 6,8 | – | R35 | B-recW | – | R62 | 13,25,26 | – |
| R12 | CLASS ∪ {8} | N | R36 | D-rec ∪ B-recW | – | R63 | 9 | N |
| R13 | CLASS | N | R37 | 6,7,8,12,19,20 | – | R81 | 7,19 | – |
| R14 | 11,12,14 | – | R38 | D-chg | N | R64 | 5 | – |
| R15 | 11,12,14,20 | – | R39 | 8,13,18 | – | R65 | 8,9 | – |
| R16 | 8,13,17,19,23 | – | R79 | 8 | – | R66 | CLASS ∪ {2,6,8,12,13,16,17,18,19,26} | N |
| R17 | 6,8,19 | – | R80 | ∅ | – | R67 | CLASS ∪ {3,4,19,20,26} | N |
| R18 | 3,4,6,19,20,21 | – | R83 | 8,13,17,19 | – | R68 | CLASS ∪ {2,6,10,26} | N |
| R19 | 2,8,9,10,11 | N | R40 | 12,14 | – | R69 | CLASS ∪ {6,19,20,26} | N |
| R20 | 6,7 | – | R41 | 8,13,17,19 | – | R70 | 8,13,16,18,25 | B |
| R21 | 6,12,15 | – | R42 | 11,14 | – | R71 | D-chg ∪ B-chg | N |
| R22 | 8,13,19 | U | R43 | 11,12,14 | – | R72 | 2,6,11,12,16,17,18,20 | N |
| R23 | 2,6,8,11,12,13,16,17,18,19 | U | R44 | 8,13,18 | – | R73 | 3,7 | – |
| R24 | 8,13,17,18,27 | – | R45 | 8,13,18 | – | R74 | 28 | – |
| | | | R46 | 6,15 | – | R75 | ∅ | – |
| | | | R47 | 6,8 | – | R76 | 2 | U |
| | | | R48 | 6,19 | – | R77 | 8,13 | – |
| | | | R49 | 8,13,19,23 | – | R78 | 8,13,18 | – |
| | | | R50 | 7,19 | – | R82 | CLASS ∪ {6,12,13,17,18,19} | – |
| | | | R51 | CLASS | N | | | |

**[r1] Internal inconsistencies of A0 (disclosed, review item 10).**
- **C13 for ρ.** R8 and D-rec include C13 for a named record, but R2 ("a record that is an act"), R3 (con(a) = ρ) and R40 ("for actual records") do not.
- **"Conforms".** It is read through Def 1.10 in R32 / B-conf, but not in R33 and R34.
- **E.** "E", "acts" and "act" are mapped to C8 in most rows, but to C8 + C9 in R9 and R4.
- **Class-rule actor flags.** R12, R13 and R51 carry N, but R82 carries none. R24 omits the class rule, although T3 applies it.
- **R66** reads keepers, so its flag should be B, not N.

A0 transcribes T3's crossing table. **It is sufficient for the P-c verdict**, because none of these issues changes a P-c crossing. **It understates crossings for other cuts**: every result about a cut other than P-c needs a complete encoding.

### A.2 Encoding A1 (r1; A0 corrected under explicit conventions)

**Conventions.**
- **K1:** C13 whenever the statement names a record (ρ, "record", "records"). Naming only an attribute (r, c) does not add C13.
- **K2:** "conforms" / "violates" is read through Def 1.10, σ(t(w))(τ(w)) ∈ P, giving {3, 4, 6, 19, 20, 21}. This applies to R32, R33, R34 and B-conf.
- **K3:** an unspecified "act" or E means E^iss ⊔ E^adp → {8, 9}. "Issue act" → {8}. Hence D-rec = {8, 9, 13, 17}.
- **K4:** the class rule (T3 rule 3) adds CLASS with actor flag N (D-chg's α). A row that also reads keepers gets flag B. CLASS is the union of the K2/K3 clause sets.

**Rows changed from A0 (33):**
- **R2:** +9, 13.
- **R3:** +9, 13.
- **R40:** +13.
- **R24:** +CLASS (+2, 3, 4, 5, 6, 7, 9, 10, 12, 19, 20, 21); flag N.
- **R33 and R34:** +3, 4, 6, 20.
- **R66:** +9; flag N → B.
- **R82:** +9; flag – → N.
- **+9 only:**
  - R11, R12, R13, R16, R17, R22, R23;
  - R29, R36, R39, R79, R83;
  - R41, R44, R45, R47, R49, R51, R52;
  - R67, R68, R69, R70, R77, R78.

All other rows are as in A0.

**[r1.1, Z6] Known residuals of A1.** None of them changes any reported figure.
- **K3 misses:**
  - R58 should read {5, 8, 9};
  - R63 should read {8, 9}.
- **Flag gap:** R72 should be B.
- **Read-through gaps:**
  - C23 is absent from R83 and R41;
  - C27 is absent from R52;
  - R11's τ, v and f are not read.
- **Effect of the R58, R63 and R83 fixes:** crossings are unchanged, and the number of cuts tied with P-c on T-A + T-B (P-c included) drops from 3 to 1.

The encoding should be frozen only after Q6 test 1.

### A.3 Encoding RV (reviewer)

RV was encoded independently from the T2 r1.4 statement text. It is held in the reviewer's scratch file `enc.py`, dict `mine`, in the session scratchpad. It is not reproduced here; A1 and RV agree on every P-c crossing.

## Appendix B. Key computed outputs (P-c; spanning)

- **B1 (validation).** P-c crossings on rows T-A / T-A + T-B / all, under S │ S′ │ S″:
  - A0: 17 / 24 / 46 │ 12 / 17 / 36 │ 12 / 17 / 36;
  - A1 and RV: identical to A0.
- **B2 (spanning dependence).** Under every encoding, R5, R6, R8, R10, R19, R25, R38, R63, R71 and R76 change between S and S′. S′ and S″ differ on 0 rows. The range across the T3 §1a (e) borderlines is 8–16.
- **B3 (P-d crossings, placed concepts only; T-A / T-A + T-B / all).**
  - A0: 12 / 17 / 35.
  - A1: 13 / 18 / 36.
  - RV: 13 / 18 / 37.
  - The difference from A0 is C13 in R2.

## Appendix C. Q2 re-run: method and full output

**Method.**
- Each row's concept set is intersected with the 23 placed concepts. Rows with an empty intersection are dropped.
- **Crossings:** the number of rows meeting ≥ 2 parts.
- **Volume of a part:** Σ over rows of |row ∩ part|.
- **Conductance:** crossings / min volume.
- **Normalized cut:** Σ crossings / volume_i.
- **Modularity:** Newman Q on the graph where each row with k ≥ 2 concepts adds 1/(k−1) to each pair.
- **Null:** random partitions of the 23 placed concepts with the cut's part sizes. 8,000 per cut (seed 20261013); modularity on the first 2,000.
- **Cohesion:** the share of ordered co-read pairs that stay in their part. Its null is 2,000 random 9│14 partitions (seed 3).
- **Exhaustive check:** all 817,190 9-subsets.

**Output, rows T-A (24).** Each cell gives A0 / A1 / RV. "p" is P(null ≤ observed) for crossings and normalized cut, and P(null ≥ observed) for modularity. "pct" is the share of null cuts with strictly lower conductance.

| cut | crossings | null mean | p | conductance pct | normalized-cut p | Q | modularity p |
|---|---|---|---|---|---|---|---|
| P-c | 12 / 12 / 12 | 18.7 / 19.5 / 19.9 | 0.002 / 0.001 / 0.0001 | 0.30 / 0.48 / 0.17 | 0.008 / 0.015 / 0.0005 | 0.098 / 0.064 / 0.082 | < 0.001 / 0.003 / 0.0005 |
| P-a | 15 / 15 / 15 | 20.3 / 20.9 / 21.3 | 0.001 / 0.0005 / < 0.0001 | 0.08 / 0.07 / 0.04 | 0.004 / 0.008 / 0.0001 | 0.091 / 0.059 / 0.079 | 0.0005 / 0.002 / 0.0005 |
| P-b | 16 / 16 / 16 | 21.0 / 21.5 / 21.8 | 0.0006 / 0.0004 / < 0.0001 | 0.00 / 0.03 / 0.00 | < 0.0001 / 0.0001 / < 0.0001 | 0.125 / 0.095 / 0.110 | < 0.001 |
| P-d | 12 / 13 / 13 | 13.3 / 14.5 / 14.8 | 0.43 / 0.41 / 0.36 | 0.84 / 0.89 / 0.69 | 0.82 / 0.87 / 0.62 | 0.018 / 0.019 / 0.020 | 0.03 / 0.02 / 0.02 |
| acts │ rest | 16 / 16 / 16 | 16.3 / 17.3 / 17.7 | 0.52 / 0.36 / 0.31 | 0.01 / 0.00 / 0.00 | 0.04 / 0.008 / 0.005 | 0.029 / 0.027 / 0.027 | 0.02 / 0.02 / 0.01 |
| work │ rest | 10 / 11 / 11 | 11.3 / 12.5 / 12.7 | 0.45 / 0.43 / 0.38 | 0.07 / 0.08 / 0.05 | 0.07 / 0.08 / 0.05 | 0.011 / 0.008 / 0.010 | 0.04 / 0.04 / 0.03 |
| norm │ rest | 10 / 11 / 11 | 15.0 / 16.1 / 16.4 | 0.07 / 0.08 / 0.05 | 0.00 / 0.01 / 0.00 | 0.002 / 0.003 / 0.0001 | 0.048 / 0.039 / 0.057 | 0.004 / 0.003 / 0.0005 |

**Output, rows T-A + T-B (42), encoding A0.** The A1 and RV values are in the §2 Q2 table.

| cut | crossings | null mean | p | conductance pct | normalized-cut p | Q | modularity p |
|---|---|---|---|---|---|---|---|
| P-c | 17 | 32.0 | < 0.0001 | 0.39 | 0.003 | 0.100 | 0.0005 |
| P-a | 24 | 34.8 | 0.0003 | 0.02 | 0.0001 | 0.124 | < 0.001 |
| P-b | 27 | 35.9 | 0.0005 | 0.15 | < 0.0001 | 0.139 | < 0.001 |
| P-d | 17 | 22.9 | 0.21 | 0.85 | 0.77 | 0.009 | 0.06 |
| acts │ rest | 26 | 28.0 | 0.37 | 0.01 | 0.02 | 0.036 | 0.02 |
| work │ rest | 17 | 19.2 | 0.43 | 0.01 | 0.01 | 0.038 | 0.004 |
| norm │ rest | 17 | 25.8 | 0.08 | 0.00 | < 0.0001 | 0.067 | 0.002 |

**C6 (Lemma 2 check).** Record coordinates read by each clause:
- D-chg, D-st, B-chg, B-conf, R-i "E ≠ ∅", and R-e (D-chg without Cap): none;
- D-rec and B-recW: R (C13) and Ref (C17).

This holds only with actor-valued coordinates not counted (S′).

**Seeds.**
- r1 re-run: 20261013 (cuts) and 3 (cohesion).
- r0 values in §0, quoted for comparison: 20261012, 7 and 11.
