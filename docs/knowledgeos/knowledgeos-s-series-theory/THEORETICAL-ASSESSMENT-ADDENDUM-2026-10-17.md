# Addendum to the Theoretical Assessment: Response to Session A's Research Handoff

| | |
|---|---|
| **Author / date** | Session B (Professor), 2026-10-17 |
| **Responds to** | `audit-p3b/20261017_S-SERIES-RESEARCH-HANDOFF-TO-PROFESSOR.md` (S-Series worktree, uncommitted, sha `4eb6cc91…`), §6 questions 1–5 and Annex 2 |
| **Supplements** | `THEORETICAL-ASSESSMENT-2026-10-17.md`. That file is not edited; corrections to it are listed in §6 |
| **Kind** | Theoretical assessment. It decides nothing, changes no file other than adding this one, runs no computation, and reads no restricted material |
| **Labels** | **[E]** established by derivation · **[A]** valid only under the stated assumptions · **[P]** plausible, not proven · **[C]** contradicted · **[U]** unresolved. Source status: VERIFIED (checked here) · REPORTED (Session A; not rechecked) |
| **Independence** | same model family as Session A; shared sources. Not independent validation |

---

## 0. Business summary

1. **Correction to my own work.** I said B = 9,999 would give "slightly higher power" but did not compute it. Session A's calculation shows otherwise: power is not uniformly higher, and at 59 relations it falls below the 0.8 bar. **The claim is withdrawn.** On the mathematics, neither B is better.
2. **On the B conflict, I read the record differently from both sessions' "genuinely inconsistent".**
   - The only explicit decision on the confirmatory B is 9,999 (Freeze 1).
   - The later "58 relations" figure is an illustration computed at 999. The actual rule decided is "level α/3, power ≥ 0.8", and that works at any B.
   - On this reading the record is *consistent* (B = 9,999 governs), and the genuine gaps are the missing seed and the missing single-run rule.
   - That is an interpretation, not a fact. The Principal decides it with RQ-1.
3. **The H2 concern (Q2′) becomes more robust, but its verified scope is narrower than I stated.**
   - The agent contract itself says the "predecessor" carries **no** time meaning. So whichever way the list is ordered, the predecessor relations tell H2 nothing about which way contradictions run.
   - But the contract and code I checked are the **v2.9 draft** proposed for the next gate (G3), not the production version v2.8.1, which I have not seen.
   - It also conflicts with the governing P3b annex, which tells agents to build the timeline "in historical order". That conflict needs a ruling.
4. **On the linkage question, my result and Session A's derivation A3 agree once my condition (R) is added.** The second-judgement safeguard also survives the "generation sensitivity" test (N2 d). Session A's derivation B3 is correct: disclosure still needs a rule fixing how conflicts are handled.
5. **The shared conclusions on reachability (Q-M1) and on agent inputs (Q-M2) hold on re-examination,** with one qualifier: "the C1 model is needed for any positive verdict" is true *only if* the v1.8 rule CA-8 is adopted.

---

## 1. Question 1: Freeze 1's immutability and pre-data changes (C-4 / RQ-1)

**Text (VERIFIED, GL-0094).** "Immutability: no later observation may change hypotheses, estimands, thresholds, null models, sampling logic or decision rules; a methodological problem found later is recorded as a finding, never patched into the frozen design."

**Two readings:**

| Reading | Content | Consequence |
|---|---|---|
| **R-literal** | The clause bars *any* later change, including by a later human act. The second sentence ("never patched into the frozen design") covers methodological findings, which is what motivated prereg_v2 (pass 0c, `SENSITIVITY-v1`; TODO l.276) | prereg_v2's changes to the family, H2's wording and H5's B cannot amend Freeze 1. They stand only as a new design adopted by later acts, which needs an explicit statement that it **supersedes** the Freeze 1 elements |
| **R-purposive** | The clause targets *data-informed* changes (forking paths). Pre-data, governed, disclosed changes are allowed (OP §17; P3b §26: a change is a new file plus a human decision) | prereg_v2's changes are valid amendments. Only the explicit reconciliation entry is missing |

**Assessment:**
1. Under **both** readings, the Principal's later acts can change the design. What differs is whether those acts must be recognized explicitly as superseding Freeze 1. **[E]** Under P3b §26 a change requires a human decision; nothing forbids the decider from amending its own freeze.
2. The clause says "no later **observation** may change". The pass-0c amendments drew on pilot and synthetic studies, plus a disclosed read of discovery-input names and counts (GL-0127). Whether that counts as "observation" in the clause's sense is interpretive. **[U, reserved]**
3. **H5's B and the confirmatory B differ.**
   - H5's B was changed by an explicit act (GL-0122). Under R-purposive it is a valid amendment; under R-literal it supersedes Freeze 1 for H5 once recognized.
   - The confirmatory B was **never decided again**. Under **either** reading, Freeze 1's B = 9,999 is the only decision on it. **[E]** (by inspection of the record)
4. **Is there a real conflict on the confirmatory B?** Session A says the record is "genuinely inconsistent" because later frozen material presupposes 999. My reading:
   - GL-0135 #1 decides "**α/3**, fixed in advance", and adds the parenthetical "(≥ 58 dated relations at B = 999)".
   - The decided rule is the level, with the power criterion of GL-0133. The number 58 is a consequence computed at one B.
   - The frozen code computes power **at the B actually used**, so the decided rule is B-agnostic.
   - The prereg_v2 docstring's "m ≥ 58 at B = 999" is explanatory text, not a parameter: `run()` takes B as an argument.
   - **So the record is consistent with B = 9,999 governing**, with "58" being a B = 999 illustration. **[P]**: an interpretation of the parentheticals, defensible but not the only one. The counter-reading is that the reviewers and the Principal *assumed* 999 throughout (GL-0135; the review's M4 "hard-wired to B = 999", since fixed), so a later intent towards 999 may be inferred.

**For the Principal:** the choice is between "Freeze 1 governs B (9,999); the '58' figure is restated for 9,999" and "999 is adopted by an explicit act superseding Freeze 1 l.68". Both are coherent. The record does not decide between them on its own.

## 2. Question 2: D2 mathematics

### 2.1 Annex 2 power table

- **Formula** (VERIFIED): Annex 2's formula matches the frozen `h2_power` as previously extracted from `prereg_v2.py` l.116–131: k_max = ⌊level·(B+1) − 1 + 1e-9⌋; power = Σₜ Bin(t; m, π) · P[Bin(B, q_t) ≤ k_max]; q_t = P(Bin(m, ½) ≥ t).
- **Numbers** (REPORTED, COMPUTED by Session A). **Not recomputed by Session B**, because a computation is outside this commission's authorization.
- **Mechanism** (consistent with known theory **[P]**). As B grows, the Monte-Carlo test tends to the exact one-sided binomial test at level α/3. Exact binomial tests have a saw-tooth power curve in m, because the attainable rejection threshold t\*(m) jumps in integer steps. At B = 999 the Monte-Carlo randomization (k_max = 15) smooths the boundary and damps the saw-tooth. At B = 9,999 (k_max = 165) the test is close to the exact test, so the saw-tooth appears. A pattern such as `…Y..Y.YYYY…` is exactly what this predicts. This explains Annex 2's direction; it does not reproduce its numbers.
- **Correction (C).** My "[P] slightly higher power at 9,999" (THEORETICAL-ASSESSMENT §3.2) is **contradicted** at specific m (54, 59, 61) by the reported computation, and is withdrawn.

### 2.2 Is "power ≥ 0.8 at the observed m" coherent under non-monotonicity?

**Yes, as a pre-registered rule [A].** It is a fixed function f(m) ∈ {NR-P, NR-U}, specified before the data. Non-monotonicity only means that the set {m : power(m) ≥ 0.8} is not an interval. The researcher cannot know ex ante whether m will fall in it, but the researcher cannot choose m either, so no forking path is created.

**What it needs: m must be ancillary for π.** The number of dated, untied relations must not depend on their orientations.
- If dating or tie status correlated with direction (for example, backward relations more often undated), conditioning on m would bias the power statement.
- This is an assumption, not a proven fact. **[U]**

**Interpretive cost.** Under B = 9,999, "no forward share as large as 0.7" could be licensed at m = 58 but not at m = 59. That is coherent but counter-intuitive, and should be disclosed in the wording. **[E]**

### 2.3 Is a single-run rule sufficient for A3 once the seed is recorded?

**No, it is not sufficient on its own [E].** A3 requires that the statistic, the group, B and the seed are fixed before the data, and that one run is reported. With a deterministic instrument, re-running with the recorded seed reproduces the p-values. But selection can still enter through the **inputs** and the **code**:
1. the input set (which accepted S5 objects, which version, after which re-adjudications);
2. the instrument version (code sha);
3. the extraction environment (the dependencies `p3b_s5_r7_reconstruction.file_position` and `contradiction_precedence`, and their versions).

**Sufficient conditions [E]:**
- the instrument sha;
- B and the seed;
- a **hash-frozen input manifest** of the accepted objects and metadata;
- the dependency code shas;
- the rule "the first run on that manifest is the confirmatory run; all runs are reported".

That is one recorded sentence plus hashes.

## 3. Question 3: D3 linkage judgements

1. **Is A3 equivalent to Theorem 2 once (R) is added?** **Yes, in substance [E].**
   - C1 (fixed manifest, leaves not feeding generation) corresponds to (M).
   - C2 corresponds to (S).
   - C4 (only evidence events change leaves), together with package v2 a6 (no re-audit, no resolution), corresponds to (F).
   - C3 (the outcome order) is used inside Lemma 1's proof.
   - A3's "the outcome moves only toward OPEN" is Lemma 1. Theorem 2 adds the pathwise comparison across regimes, which needs (R).
2. **Is A2 stated correctly?** Partly. "ε₁ε₂ under independent errors" needs **unconditional** independence. For judges who are independent *given the pair* but face pairs of varying difficulty, P(both wrong) = E[ε_π²] ≥ (E ε_π)² (my Prop. 4). So ε₁ε₂ is a lower bound, not the expected value, even for genuinely independent judges. **[E]** A refinement, not a contradiction.
3. **Is B3 correct (underdetermination without a fixed reading)?** **Yes [E].**
   - v1.8 l.69 says a conflicting pair "counts as unruled until resolved" and stays unruled *after* E_close.
   - It does not specify **how** a conflict may be resolved *before* E_close; l.270 and l.313 speak of a J5 "resolution".
   - Under (b), a resolution procedure that re-rules until one value prevails is not excluded, which is item C-4's "shopping" lever. So (b) requires a fixed reading as well: either "a conflict is unruled and final" or a specified, pre-registered resolution procedure.
4. **Does (a) preserve dominance through N2 (d) (generation sensitivity)?** **Yes, under (M), (R) and a pre-fixed recovery rate [A].**
   - N2 (d) runs "the exact recovery procedure, stopping rule and J1 criteria" on synthetic planted candidates, and requires recovery at a rate fixed before any S5 data.
   - Under (M) the generation procedure is the same under both regimes. Only the J1 leaves differ, and under (a) they are a coarsening of (b)'s (given (R), also on the synthetic items).
   - Recognition of a planted coherent set needs ⊤-edges, so the recovered count under (a) is ≤ the count under (b) on every realization. Hence N2 (d) passes under (a) ⇒ it passes under (b).
   - N2 (d) is a necessary condition of NCTF only, so (a) can only make NCTF harder, which is a move toward OPEN. Theorem 2 extends. ∎
   - Under G1 (generation reads leaves), the procedures differ, and this is **[U]**.

## 4. Question 4: Q2′, re-examined

**Premises, now checked across all R7 modules on the S-Series branch (VERIFIED, by search):**
- **(F1)** `p3b_s5_r7_reconstruction.py` l.89 sets `pred = tl[i − 1]`. The contract text (`prompts/20260926_2400_p3b-agent-contract-r7-addendum.md`, sha `7eba0385…`, §4.1.3 rule 3) says: "a point whose class is CONTRADICTS **must** appear in `contradicted_by`, targeting its predecessor". This is HUMAN SEMANTIC DECISION **FD-1′a** (l.725, l.1219).
- **(F2)** A search of every non-test module under `scripts/` that references the timeline finds **no** comparison of list order with dated positions. The only hits are schema and key checks.
- **New, and decisive (VERIFIED):** the same contract says: "'Predecessor' here means only the adjacent point the class refers to. It carries **no** historical precedence (§4.2)" (l.714).
- **The other route (VERIFIED):** "No converse: a summary may contain points of other classes … supported by its own CONTRADICTION fact" (l.715).

**Consequences:**
1. **Q2′ holds more strongly than I stated [A].** Its conclusion no longer depends on reading the list as the agent's chronology.
   - The contract assigns list adjacency no temporal meaning.
   - So on R_C (the predecessor-rule relations), H2's "forward" indicator compares a relation that by contract carries no time direction with the A.10 file dates.
   - Whatever the list order means, the R_C forward share contains **no information about the temporal direction of contradictions**. It reflects only how list adjacency aligns with the file dates.
2. **A conflict between the contract and the governing annex [C between documents; not resolved here].**
   - P3b v1.7 §9.8 step 1 (frozen, GL-0034) instructs reading the rows "IN HISTORICAL ORDER" and building the timeline.
   - The contract says predecessor adjacency "carries no historical precedence".
   - These can be reconciled: the list *is* meant to be historical, and the verifier simply *does not rely on* that for precedence. But the contract sentence can also be read as denying any temporal meaning.
   - Under the first reading, Q2′'s interpretation is agent-chronology agreement; under the second, it is arbitrary alignment. **Under both, the domain-direction content is nil on R_C.**
3. **A scope correction [C to my earlier statements].**
   - The contract file at that path has sha `7eba0385…`. The governance log records this as the **v2.9 DRAFT** (isolated environment; GL-0112/0115).
   - The S-Series branch is the v2.9 repair branch.
   - The **production-bound contract is v2.8.1** (`7b68cbc9…`, GL-0108). I have **not** inspected it, nor any production code.
   - So Q2′'s premises are VERIFIED **for the v2.9 draft** (the version proposed for G3) and **unverified for v2.8.1**. Earlier statements (TRA §6.2; TRA-CR B-14, B-15) did not carry this qualifier.
4. **Overall impact on H2: [U].** The share of R_C among H2's relations is unknown before the data. The other route (non-predecessor entries "supported by its own CONTRADICTION fact") may carry genuine directional content. H2 is **not** shown to be invalid.

## 5. Question 5: Q-M1 and Q-M2, shared conclusions or shared errors?

I tried to refute each conclusion against the v1.8 text and the code.

| Conclusion | Attempted counterexample | Result |
|---|---|---|
| ROBUST unreachable under V-2 (ii) with S5c prohibited | Is there any route to CA-9 without scoring? Under V-7 (a), CA-6 for K = "the confirmed prediction", which also needs scoring. An ADD §6 ruling "not scoreable" also blocks it. V-2 (iii) would avoid CA-9 but is excluded by the recommendation ("never (iii)") | **holds [A]**: conditional on v1.8 R4/CA-9 as written and on the prohibition |
| NCTF narrow; any registered Kind P candidate blocks it without V-11 | v1.8 §11.3 (d) states it explicitly; ground (ii) or a vacuous disposal remain | **holds [A]** |
| All five pre-registered readings rest on agent-produced inputs | Look for a purely mechanical reading. H4's birth kinds originate in a mechanical P2b detector, but S5 agents confirm them (§9.8 step 5). H2's dates are mechanical, but its relations are agent-entered. H5's FOUND needs a stage-2 agent reading | **holds [E]** (by inspection of code and procedure) |
| The C1 model is needed for any positive verdict | CA-8 applies to Kind P ("wherever it rests on one") and to Kind K ("the same test applied to the record's interpretation"), with gap code `INSTRUMENT` until C1 exists | **holds [A] only if CA-8 is adopted as written.** Session A's packet states it without this conditional. That is a minor overstatement, not an error in the logic |

**No shared error found [P].** These are the same model's checks of the same texts. An independent reviewer should repeat them (DS-5, items 4–5 of Session A's list).

## 6. Corrections to Session B's earlier documents (listed; not applied)

| Document | Correction | Reason |
|---|---|---|
| THEORETICAL-ASSESSMENT §3.2 | withdraw "mild preference for 9,999 … slightly higher power [P]". Replace it with "power is non-monotone in m at B = 9,999 (Session A, COMPUTED); neither B is mathematically preferable; see Addendum §1–2" | §2.1 |
| THEORETICAL-ASSESSMENT §3.2 | add the A3 sufficiency conditions (input manifest, code shas) | §2.3 |
| TRA §6.2 / TRA-CR §C.1 | add: Q2′'s premises are verified for the v2.9 draft contract (`7eba0385…`) and repair-branch code, not for production v2.8.1; contract l.714 states that the predecessor carries no historical precedence | §4.3 |
| Source register | new conflict: P3b §9.8 "IN HISTORICAL ORDER" vs contract l.714 "no historical precedence" | §4.2 |
| PRINCIPAL-DECISION-PACKET Decision B | add the reading of §1.4 (the record may be consistent with B = 9,999) as an option basis | §1 |

On Session A's disagreement X-a: my two documents are not both live. The critical review's "B = 999" recommendation was already withdrawn in my decision packet (§0, K-1), which Session A did not read. The remaining live statement, the preference for 9,999, is now withdrawn as well (§2.1).

## 7. Needed next (none authorized here)

1. **An independent reproduction of Annex 2,** by any exact binomial routine. It is cheap, but a computation, so it needs authorization.
2. **Inspection of the production contract v2.8.1 and the production code** for F1/F2, if v2.8.1 could still be the S5 contract (depends on G3).
3. **A ruling on P3b §9.8 vs contract l.714** (the meaning of timeline order).
4. **The Principal's reading of GL-0094's immutability clause and GL-0135 #1** (§1). This bears on B and RQ-1.
5. **A check that m is ancillary for H2** (§2.2). It is a design question, answerable without data only as an assumption.
6. **An independent review** (DS-5) of: Prop. 1; Lemma 1 / Theorem 2 with the N2 (d) extension (§3.4); Q2′ with the v2.9 qualifier; the A3 sufficiency conditions.

## 8. Boundaries kept

- One new file in the Professor's workspace. No other file changed.
- No computation, simulation or enumeration run.
- No restricted material read.
- No Git operation, no message sent, no decision recorded.
- The lane-status question (Session A's records: "set aside"; the Principal: commissioning Session B) remains the Principal's to clarify.
