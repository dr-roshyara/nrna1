Independent review report: PO-01 note and architecture proposal (Session A, 2026-10-17)

The bottom line:
- The confirmatory proof holds. H1, H2 and H4 give valid p-values under their stated assumptions, and Holm controls the family-wise error over those three nulls. I checked every step.
- The Professor's "conservative" claim for H3 and H5 does not hold. The synthetic evidence can't rule out sizes above nominal.
- The Professor's suggested proof route for H3 is false. I found a counterexample.

No file, decision, commit, study or run was changed or started.

Independence limit: I am the same model family as the Professor. My checks are re-derivations and independent re-implementations, not independent review in the scientific sense. For the confirmatory guarantee, which everything later rests on, a different reviewer (a human or another model family) is still needed.

1. Executive conclusion

- Established:
  - For H1, H2 and H4, each p-value is valid (it rejects a true null no more often than the stated level, at any finite sample size) under four stated assumptions. PROVED UNDER STATED ASSUMPTIONS.
  - Holm then gives strong control of the family-wise error, but only for the three "Model 0" nulls (the pre-registered chance models). PROVED UNDER STATED ASSUMPTIONS.
  - The "star" sampling scheme behind H3 and H5 is not valid in general. VERIFIED: I recomputed the counterexample exactly.
  - The H3 reachability counts for n ≤ 4. COMPUTATIONALLY VERIFIED by my own re-implementation.
- Only claimed:
  - "H3 and H5 are conservative in synthetic regimes." The intervals in the note are invalid, and the exact 95% bounds include sizes above nominal.
  - "The components have identical T_h3 distributions for all n." The Professor's proof route fails; a counterexample is in §3.
- New findings in the frozen code, none of which affects the confirmatory guarantee:
  - The confirmatory run takes B and the seed as command-line arguments, so "exactly one run, with the frozen defaults" has to be a documented execution condition.
  - The frozen prereg_v2.py still labels itself and its output "DRAFT, NOT FROZEN".
  - H3 does not filter self-loops.
  - H4's null shuffles dates across every key in births, including keys outside the five counted kinds.

2. Current state (verified)

┌───────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│               Item                │                                                                      Verified                                                                       │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PO-01-NULL-MODEL-VALIDITY-NOTE.md │ sha256 4ea81900…81a4; untracked; unreviewed (self-declared)                                                                                         │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ ARCHITECTURE-PROPOSAL.md          │ sha256 f6d84cee…e1b; status "PROPOSED, not approved"; D1–D9 undecided                                                                               │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Manuscript                        │ unchanged since 13:17 (3d43f59b…, 82,677 bytes); the README confirms it is held                                                                     │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Frozen code                       │ prereg_v1.py eb6291ab…, prereg_v2.py 827ce461…, test_prereg_v2.py 8e126dd8…: all match G-LOG-0137                                                   │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PO-01 scripts                     │ embedded in the note. The files lived in Session B's scratch area. Their recorded hashes could not be checked against files, and I did not run them │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Data used                         │ the note says synthetic only. The embedded code reads only prereg_v1.py and generates data: consistent, but I did not observe the runs              │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Governance                        │ 140 entries; last is G-LOG-0140; no new human act. O1 (G-LOG-0125) in force; T-0/T-1 pending; run-3 not launched, no quiet period in force          │
├───────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ Branch                            │ PROD is still on election-flow-updated; all professor files are untracked                                                                           │
└───────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

3. Mathematical review of PO-01

What "valid" means here: P(p ≤ u) ≤ u for every u in [0, 1] when the null hypothesis is true, at any finite sample size. Because of the +1 in the p-value formula and because ties count against the hypothesis, the test is conservative: its size can be below the nominal level, never above it. "Exact" in the note should be read as "valid at finite samples", not "size exactly α".

Assumptions for H1, H2 and H4 (all four are needed):
- A1, the null: the data's distribution is unchanged when the generator's group acts on it.
  - H1: within each object, the order of change classes after the first position is exchangeable, given the first class and the multiset of classes (m0_h1; the statistic t_h1 only looks at positions ≥ 2: consistent).
  - H2: all pooled orientations are independent and fair. Invariance under the full sign-flip group {0,1}^m is equivalent to this.
  - H4: within each object, the date values are exchangeable across all keys of the extracted births dict, including any keys outside the five counted kinds (m0_h4 shuffles every key; t_h4 counts only KIND_ORDER).
- A2: the random group elements are independent, uniform, and independent of the data. This is the usual idealization of Python's Mersenne-Twister generator.
- A3: the statistic, the group, B and the seed are fixed before the data, and one run is reported. run() takes B and the seed from argv (defaults 999 and 20260927). Running several seeds and choosing among them would void validity.
- A4: validity concerns the extracted data as recorded. It says nothing about extraction or measurement error.

Proposition 1 (H1, H2, H4): PROVED UNDER A1–A4.
- I checked every step. Write u₀ = g₀⁻¹ and u_b = g_b·g₀⁻¹. Given u₀, each u_b is uniform and they are independent, so (u₀, u₁, …, u_B) are i.i.d. uniform and independent of y = g₀x₀, which has the law of x₀.
- The vector of statistics is therefore exchangeable.
- The p-value (1 + #{T* ≥ T})/(B + 1) is then valid, with ties counted against the hypothesis and the Monte Carlo correction applied (the standard rank argument; Phipson and Smyth's result for randomly drawn group elements).
- The validity holds conditionally on the orbit of x₀ under the group, and therefore also unconditionally.

Scientific caveat (not a validity gap):
- A rejection means only "this Model-0 null is false".
- For H2, the frozen "partly method-constrained" caveat applies, and pooling across objects means any dependence within an object also violates the null. So rejection licenses only the frozen reading.
- For H1, rejection licenses only "source/agent unresolved" (rule D0).

H3 and H5: not valid in general; their actual size is unknown.
- Proposition 2: VERIFIED by my own closed-form computation: size 0.1232 at nominal 0.01 and 0.3322 at nominal 0.05.
- What it shows. Running every chain from the observed x₀ (the "star" scheme) can be badly anti-conservative when chains mix slowly from extreme states.
- What it does not show. It doesn't show that the frozen H3 or H5 test is invalid at its real chain lengths (10·|E| swaps for H3, 5·|R| trades for H5). That depends on mixing, which is unknown. Whether the error runs conservative or anti-conservative depends on where x₀ sits.
- The synthetic size table: SUPPORTED BY SYNTHETIC SIMULATION ONLY, and weaker than the note says.
  - With R = 300 replications, exact Clopper–Pearson 95% upper bounds are:
    - H3 sparse: 0.018 at nominal 0.01 and 0.056 at nominal 0.05;
    - H3 hub: 0.024 and 0.052;
    - H5: 0.018 and 0.038.
  - So "at or below nominal" is not established. The ±1.96·SE intervals in the note are invalid this close to zero.
  - The H5 simulation used the exact-implication statistic, not the frozen v2 H5 statistic (the min-p combination of the exact-implication and Loevinger statistics), so it doesn't test the actual H5 instrument.
  - The regimes were chosen by the author, and the burn-in only approximates the null distribution.
- The v2 H5 min-p combination is valid only if all B + 1 matrices are exchangeable. The star scheme breaks that, so the H5 combination inherits the problem.

H3 graph reachability:
- COMPUTATIONALLY VERIFIED FOR THE ENUMERATED CASES, by my own enumeration using exactly the frozen move (four distinct nodes, both new arcs absent):
  - n = 3: 64 graphs, 63 degree classes, 1 disconnected class;
  - n = 4: 4,096 graphs, 2,656 degree classes, 16 disconnected classes;
  - 0 classes whose components differ in their T_h3 distribution.
  - At n = 3 no swap is possible at all, so the only multi-member class, the two directed triangles, is trivially disconnected. Both triangles have T = 1.
- The note's suggested proof route is false. Reversing a directed triangle need not preserve T_h3. In my n = 4 example, triangle 0→1→2→0 plus arcs 1→3 and 3→0 has T = 2; with the triangle reversed it has T = 1, and the degrees are identical. Here swaps happen to connect the two graphs, so the n ≤ 4 result stands, but a general proof must take another route.
- Status of "identical distributions for all n": PLAUSIBLE BUT UNPROVED.
- The smallest useful next check: an exhaustive n = 5 enumeration (2²⁰ graphs) with an efficient component algorithm. Or, cheaper, only the degree classes that contain a directed triangle not reachable by swaps.
- What H3 actually tests is the uniform distribution on the swap-reachable component, not "a random graph with the same degrees". That is a documentation correction.

4. The confirmatory error-rate guarantee: its exact scope

- Guaranteed (PROVED UNDER A1–A4): P(at least one false REJ among the true nulls of {H1, H2, H4}) ≤ 0.05. This is strong control under any dependence, because Holm needs only valid marginal p-values. An untestable hypothesis entering with p = 1 doesn't affect it. The family {H1, H2, H4} matches the frozen v2 (CONFIRMATORY, G-LOG-0126…0131).
- Not covered:
  - NR-P for H2: a power statement at level α/3 under an i.i.d. alternative with π = 0.7. It is not an error-rate guarantee, and it relies on independence across relations.
  - NR and OPEN readings.
  - Substantive readings beyond Model 0: the artifact and competitor models of B1 §4 are separate requirements.
  - The descriptive acyclicity output.
  - H3 and H5: exploratory. BH's FDR control also needs valid p-values and suitable dependence; neither is established.
  - Measurement validity: A4.

5. Reproducibility

- Independently reproduced by me: Prop. 1 (the proof, step by step), Prop. 2 (exact numbers), and the n ≤ 4 enumeration counts, all with my own code. Re-implementation agreement is not independent review, but disagreement would have exposed an error.
- Not reproduced: the synthetic size table. Running it would be a synthetic study, which I am not authorized to run.
- What a third party would need:
  - the script files with hashes they can check (the files are in an ephemeral scratch area; the code is only embedded in the note);
  - the Python version (not stated);
  - the seeds (size.py: 20261017, stated) and the input generators (stated);
  - the enumeration bounds (n ≤ 4);
  - exact binomial intervals in place of Wald intervals;
  - expected outputs (given) with tolerances (not given).
- A matching sha256 proves file identity only, not correctness.

6. The frozen code

1. Is it faithful to the protocol? The statistics and generators are the protocol. But the frozen file's docstring and output label say "DRAFT, NOT FROZEN", contradicting G-LOG-0137. The output of a real run would carry that label. Record it as a known discrepancy; it can't be patched.
2. Is it valid?
   - Confirmatory: yes, under A1–A4.
   - Exploratory: not exact, and of unknown size.
   - H3 has a self-loop quirk that distorts what T measures, though not validity.
3. Are the limitations described correctly? No. The current note, "exact validity of the H3 null chain is unproven", understates the issue. A correct statement would say:
   - the star scheme is not exact in general, for H3 and H5 including the min-p combination;
   - H3 tests the reachable component only;
   - there is the self-loop handling;
   - H4's null includes uncounted keys.
4. Would an alternative method need re-registration? Yes. The Besag–Clifford parallel scheme would need a new version, review and a human act before any S5 data (G-LOG-0137).
5. What documentation alone can fix:
   - the limitation text;
   - the execution condition (one run, B = 999, seed 20260927, recorded);
   - the stale-label note.

Recommendation: keep the code unchanged. Every defect is either confined to exploratory tests, which are never confirmatory, or is a documentation issue. Reopening the frozen instrument only for exploratory gains isn't worth a new freeze cycle, unless a new version is opened for another reason.

7. Architecture proposal

Recommendation: adopt option 3 (modular chapters, an assembled book, sources.yaml, check.py, build.py) with conditions. Chapter-level review is planned and the manuscript is already 82 KB, so modularity pays off.

Conditions:
- (i) tools/studies/ stores code. Its existence authorizes no study; running anything needs the corresponding act.
- (ii) check.py runs only outside the run-3 quiet period.
- (iii) The checker's denylist must be tested against known restricted paths before it is trusted.
- (iv) Status vocabularies stay subordinate to the governance log. "ACCEPTED" for a chapter is never scientific acceptance.

D1–D9, with dependencies:

┌─────┬──────────────────────────────────────┬──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬─────────────┐
│  #  │               Decision               │                                                                   My view                                                                    │ Depends on  │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D1  │ adopt architecture 3                 │ yes, with conditions (i)–(iv)                                                                                                                │ —           │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D2  │ build output not tracked in git      │ yes                                                                                                                                          │ D1          │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D3  │ sources.yaml is the single truth     │ yes                                                                                                                                          │ D1          │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D4  │ LaTeX at publication                 │ yes                                                                                                                                          │ independent │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D5  │ where edition acceptance is recorded │ governance log, as a human act                                                                                                               │ independent │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D6  │ baseline commit of v0.1              │ only after the branch decision (G1); committing on election-flow-updated repeats the misplacement of 83a059f98                               │ G1, D1      │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D7  │ Ch. 14 restructure; RQ → REQ rename  │ yes; the placement-resolver run is optional                                                                                                  │ independent │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D8  │ tools/studies/ folder                │ yes, under condition (i)                                                                                                                     │ D1          │
├─────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ D9  │ PO-1 displaces REQ-3                 │ largely moot. RQ-1…RQ-3 were answered in my last report, and this report is the PO-1 review. Formal independent review of PO-1 is still open │ —           │
└─────┴──────────────────────────────────────┴──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴─────────────┘

8. The Professor's proposed next steps

1. Machine-checkable verdict rule: high value, conditional.
- Source: v1.8 (469fddca…), not adopted.
- Method: the outcome function over three-valued leaves, encoded in SMT (each leaf as two booleans), or exhaustive enumeration over small scopes. For the timing and procedure properties, a state-machine model checker (TLA+ or Alloy).
- Properties: exactly one outcome; exclusivity; "any decisive unknown leaf gives OPEN"; downgrade monotonicity (Lemma A, conditions C1–C4); no clock-triggered change of an input.
- N-a/C-4 must be modelled as it actually stands. Defective-but-determinate judgements are allowed, and the checker should find a false-definite path. That counterexample is useful: it gives the limitation in sheet (b) a precise form, instead of hiding it.
- Review: counterexamples are checkable by anyone. Proofs depend on how faithful the encoding is, so the encoding itself needs review.
- Authorization: it is tooling, and G-LOG-0140 excludes tooling, so it needs your go-ahead.

2. The two simulation studies (V-5, V-9): design now, run only by act.
- V-5 (θ_D adequacy): how much reconstruction disagreement degrades H1, H2 and H4.
  - Estimand: the attenuation of power and size as a function of θ_D, under a misclassification model.
  - Design: synthetic label flips at controlled rates.
  - Acceptance: a threshold fixed before any S5 data.
- V-9 (generation sensitivity): the estimand is the recovery rate of planted coherent candidates by the exact generation procedure. That procedure doesn't exist until the charter (V-13), so V-9 can only be designed generically now. The evidence form is an exact lower confidence bound on the recovery rate.

3. Separating what the AI reader adds from what the source says (the C1 model, CA-8): design now, fit after data.
- A latent-class rater model (Dawid–Skene type).
- Bias is not identifiable from same-model agent replicates alone, because their errors are correlated. It needs a human or gold-standard subsample, such as the θ_E reference standard of Freeze 2.
- Measures: verbatim-span grounding rate, unsupported-inference rate, agreement between raters, the human-subsample error rate.

4. Formal Concept Analysis: a good registered candidate generator, never a confirmation.
- Setup: objects = non-hub labels; attributes = the 12 frozen dimensions; incidence = FOUND.
- Method: compute the concept lattice (NextClosure) and the Duquenne–Guigues implication basis.
- What an implication means: an implication holds in this population only, as a descriptive closure.
- Caveats:
  - exact implications are brittle under noise; use support and confidence, or Loevinger H (Mokken scaling);
  - "missing" is not "absent": a three-valued incidence needs partial-context methods.
- What the pre-registration must fix: the object set, the attributes, the incidence rule, the support threshold, the handling of missing values, the choice of basis, the multiplicity treatment, and the promotion rule (candidate → test).

5. Machine learning: agree, it waits for adjudicated data. What can be done now, by authorization, is synthetic and design work only: the planted-structure benchmark for the candidate-generator baselines (part of V-9), the leakage protocol (time-ordered splits, hold-out isolation), and the identifiability study for the rater model. No training on corpus material.

9. Prioritized critical path

1. 👤 N-a/C-4, then V-1: the existing sheets. Highest value; they unblock adoption.
2. 👤 Branch (G1). It blocks every commit, including D6.
3. Documentation corrections (needs your OK to edit the TODO and manuscript): the H3/H5 limitation text; the single-run execution condition; the stale label; the H4 and self-loop notes. Acceptance: each statement cites the frozen file and line.
4. 👤 Independent review of Prop. 1 by a human or another model family. Acceptance: a written verdict on A1–A4 and the proof.
5. 👤 Machine-checkable verdict specification (M-2). Acceptance: it reproduces the known v1.7 defects and finds no material defect in v1.8 except the declared N-a/C-4 path.
6. 👤 Study designs (V-5, V-9, C1 rater model, FCA pre-registration): design documents, runs only by acts.
7. 👤 Run-3, when you schedule it (quiet period, preflight, launch).
8. Optional: the n = 5 H3 check (exploratory test only; low priority).

Blocked: everything that depends on corpus or S5 data (O1, the gates).

10. Decision sheet (stated neutrally)

┌──────┬─────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────┬────────────────────┬────────────────────────────────────┬───────────────────┐
│  #   │                  Decision                   │                          Business implication                           │   Recommendation   │          Counterargument           │ Decision / date / │
│      │                                             │                                                                         │                    │                                    │         GL        │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-1 │ N-a/C-4                                     │ see the earlier sheet                                                   │ (b)                │ (a) is safer against false         │                   │
│      │                                             │                                                                         │                    │ verdicts                           │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-2 │ V-1                                         │ see the earlier sheet                                                   │ adopt three        │ the protocol names two             │                   │
│      │                                             │                                                                         │ outcomes           │                                    │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-3 │ Branch for S-Series state and the           │ stops misplaced commits                                                 │ the S-Series       │ PROD visibility                    │                   │
│      │ manuscript                                  │                                                                         │ branch             │                                    │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-4 │ PO-1 handling                               │ record the limitation (no freeze change) / open a prereg v3             │ record only        │ exploratory results stay of        │                   │
│      │                                             │ (Besag–Clifford) before data                                            │                    │ unknown size                       │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-5 │ External review of Prop. 1                  │ the confirmatory guarantee gets independent backing                     │ yes                │ cost; one more dependency          │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-6 │ Authorize M-2 (verdict specification and    │ mechanical evidence on the verdict rule                                 │ yes, after DS-1    │ spec work on a non-adopted rule    │                   │
│      │ model check)                                │                                                                         │                    │                                    │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-7 │ Authorize study designs (no runs)           │ prepares V-5, V-9, C1, FCA                                              │ yes                │ more documents before evidence     │                   │
├──────┼─────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────┼────────────────────┼────────────────────────────────────┼───────────────────┤
│ DS-8 │ Architecture D1–D9                          │ as in §7                                                                │ as in §7           │ migration effort                   │                   │
└──────┴─────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────┴────────────────────┴────────────────────────────────────┴───────────────────┘

11. Limitations

- Cannot yet be concluded:
  - anything about the corpus or a theory;
  - the actual size of H3 and H5;
  - whether the T_h3 distribution is the same across components for n ≥ 5;
  - whether the assumptions A1–A4 hold for the real data.
- My checks are same-model-family, and the synthetic table remains unreproduced.

Status statement:
- Verified: document states and hashes; frozen code identity; Prop. 1's proof; Prop. 2's numbers; the n ≤ 4 enumeration; the counterexample to the proof route; the governance state.
- Unverified: the synthetic size results; the script files' hashes; general-n behaviour; real-data assumptions.
- May proceed under existing authorization: read-only analysis and drafting (G-LOG-0140 covers the verdict-criteria lane).
- Needs your authorization: DS-1 to DS-8.
- Changes made: no project file, governance record, branch, study or run. My verification script sits only in my scratch area. My earlier TODO edit is still uncommitted.
Stop says: Project-state sync (CLAUDE.md):
  x Session log .claude/sessions/2026-10-17.md missing (7 files modified today)
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-10-17-files.log)