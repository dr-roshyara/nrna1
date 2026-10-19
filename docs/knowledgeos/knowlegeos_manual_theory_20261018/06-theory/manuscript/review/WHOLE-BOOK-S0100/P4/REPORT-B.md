*Whole-book review S0100, part P4, reviewer B (sonnet). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:06:39Z.*

# P4 review (Ch. 6 and Ch. 9): step 2 report, whole-book review S0100

*Process independence: I am an AI session independent of the author as a process, not as a person; whether my model differs from the author's is unknown to me. The blind comparison covers only my five sampled sources: S0071, S0066, S2834, S0073 and, by skim, S0034. The rest of the part was reviewed non-blind.*

## 1. Assessment

- **Ch. 6 outside §6.5 is a source critique of engineering-platform packaging, not kernel theory.**
  - It is mostly careful and honest. Its quotes check out and it scopes its claims.
  - Its sections rest largely on INVALID or PROVISIONAL concepts.
  - Only §6.5, and parts of §6.4 C3/C7, bear on the kernel.
- **Ch. 9 is reliable as a disciplined record of conditional analyses, not as derivations from primitives.**
  - The formal scaffold (§9.1) is sound but disconnected from the boundary tests (§9.6 to §9.9).
  - §9.8 and §9.9 reduce history and trust to an evaluator (CE) that §9.7 itself shows is not yet a function.
  - Two foundations carry superseded wording from S0071: "derived state never stored", and "`authorized` never consults the session".
- **Largest risk:** conclusions that are carried into THEORY-MAP and BOOK-OUTLINE, though the text under them is weaker or contradicts them. Examples are F23 "none of the three senses is a primitive" and F22 "CE kernel-defined".

## 2. Findings, ranked

Critical first. "Internal" means checked for internal consistency only.

| # | Sev | Type | Location | Source line | Defect and consequence | Correction | Verified |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | theory omission | §9.1 CON-0099/0100 vs §9.6 to §9.9 | S0045 L69; §9.3 C2(2); §9.1 C3 scope | The scaffold models only single-step state predicates (𝒮_I) and mechanisms that add transitions (closed reading). It has no applications, no delegation, no uniqueness and no transition or history invariants. The corpus's observed mechanisms are guards (refuse, block, constrain), where §9.1's existence result does not apply. "Necessary and not delegable" is redefined in prose in §9.6.3 and §9.6.4. So no result in §9.6 to §9.9 is derived from the scaffold, and the kernel definition may not describe the target object. | Extend the model (an interface A with several applications, non-monotone guards, transition invariants, uniqueness), or declare §9.6 to §9.9 outside the formal frame. | internal; S0045 L69 source-verified |
| 2 | **critical** | theory defect | §9.9.9 result 5; THEORY-MAP F23; BOOK-OUTLINE Trust row | none | The text says none of the three senses is a primitive. §9.9.2 defines a primitive by an independence model. Yet §9.9.7 says M-T2 *is* an independence model for T-b's occurrence part. It then relabels that part "axiom", which adds a criterion the definition lacks. T-c is "Hyp, not shown" and has no independence attempt. T-a is a definition with an unconstrained f, so anything defined as a function of other things "reduces". The map row says T-a and T-c "reduce", while the outline row says T-c is "Hyp". | State T-a as a re-expression, T-c as untested, T-b occurrence as independent under the stated definition. Or amend the definition to separate axiom from primitive and apply it equally to M5 and H4/H5. | internal |
| 3 | high | theory defect (source distortion) | CON-0134; §6.5 C4; §9.7.5 table; §9.8.4 | S0071 L208, L240 vs S0073 L112–124; S0078 L167–189 | The book quotes T-1 in its absolute form "never stored or accepted as input". S0073 and S0078 show that form is false: `append` has no key whitelist (S0073 L77), and 20 stored keys are inert (S0073 L128–138). The restatement is "one producer, never consulted if present". So §6.5 C4's drift argument holds only if the stored copy is consulted. §9.8's "necessity rests on CON-0134" then rests on a design rule, not a mechanism fact. | Restate CON-0134 as "one producer; recorded values have no standing". Fix §6.5 C4, §9.7.5 and §9.8.4. | source-verified |
| 4 | high | theory defect | §6.5 C5 | S0071 L132–137 vs S0073 L98–110; S0072 L32 | C5 uses "`authorized` never consults the session" as a premise. F-1 falsified it: the session must be registered, as an answerability precondition. §9.6.2 cites S0072 L32, so the part is inconsistent. S0073 L108 says F-1 *strengthens* one root, which C5 omits. | Cite F-1 in C5 and draw its consequence. | source-verified |
| 5 | high | theory defect | §9.6.4 steps 1–3; K4; "F22 turns on K4 alone" | internal | U in §9.6.2 reads "serialization point, or only detection is possible". Step 2 drops the detection clause. A kernel serialization point follows only if prevention is required, which K4 does not encode. Step 1's "global act identity" itself presupposes dedupe of one act recorded twice, which is a K4 example. | Add the prevention/detection sub-fork to K4. State that global identity must come from the act (reference or content), not from the recorder. | internal |
| 6 | high | theory defect | §9.6.3 and §9.7.3 Analysis 1 ("Ded") | internal | "Necessary" follows by definition from R_s: to answer standing from acts, you need records of acts and a derivation. The real content is the input-gap table (Src). The necessity gate does not discriminate, so the test reduces to delegability. Delegability is fixed by stipulated K3/K4/F26, and the relocation test is "not yet adopted" (RESEARCH-PURPOSE). It is used as the decider and is never run on a known case. | Relabel necessity as definitional. Validate the boundary test on a known kernel/application case. | internal |
| 7 | high | theory defect | §9.8 Lemma D, CON-0148, §9.9 T1 premises (iii)(iv), CON-0160 | internal; §9.7.7 result 2 | The chain is: §9.9 T1 → §9.8 D → §9.7 CE → §1.14.1 (seven formal defects, no function for any θ). D is "close to definitional". "History decomposes" and "trust reduces" are therefore reductions to a function that does not yet exist. | Mark them "conditional on CE defined", with the dependency shown in the register. | internal |
| 8 | high | theory defect | §9.8 results 1–2; M1–M5; §9.9.7 | internal | The candidate primitives overlap by definition: authority includes checkability and the time index; policy includes RM-3. So "not independent" is a definitional consequence. The "models" are narrative structures with no stated language. M1's estate cases (commits) are anecdotes. | Fix the attribute signature (F13/T9-02) before further primitive tests. | internal |
| 9 | high | scope | Ch. 6 §6.1 to §6.3, §6.7 | dependency table | These sections rest on CON-0041/0057/0073/0074/0085/0086/0088 (INVALID) and are not marked kernel or application (RESEARCH-PURPOSE test 1). Only §6.5 is kernel-relevant. | Mark each concept kernel, application or platform-engineering. Park the rest. | internal |
| 10 | normal | theory defect | THEORY-MAP F22 row | §9.6.6a item 2 (struck); §9.7.9 | The row still says "CE delegable only as a kernel-defined function"; the text withdrew this. The F23 and outline rows also disagree (finding 2). | Update the map rows. | internal |
| 11 | normal | theory defect | §6.1 C1 | internal | The "declared interface" remedy does not verify that M does not secretly depend on E_PD. It fixes the intended split by declaration. | Say "well-defined, not validated". | internal |
| 12 | normal | theory defect | §6.2 C1 | S0033 L81–86; S0034 L20–33 | "Opposite verdicts the same day, so the rule is underdetermined" has an untested rival. The sources are one programme, so this is within-rater inconsistency, and S0034 later downgraded itself. | State the rival. | source-verified |
| 13 | normal | theory defect | §6.5 C2 / CON-0132 | S0068 L12; §9.5 C3 | "Reason lives in" is not operational. I-4 has two owners (BC-7 and BC-5), and the one-owner notation is biased. Rival: owner = holder of the amendment act (A1). | Define ownership by recorded act. | internal (S0091 not opened) |
| 14 | normal | theory defect | §9.8.4 "in every deployment the fold is many-to-one" (Ded) | S1609 L405, L415 | This needs a premise: convergent histories must also differ on Hist. A deployment with one monotone operation, or with no revocation, is a counterexample. | Rescope to deployments with revocation or supersession. | source-verified |
| 15 | normal | theory defect | §9.9 T2.2 and result 4 | internal | The "Ded" depends on §8.6 C1 and a single operator. "Candidate axiom of the kernel" extends it beyond that. Rival: faithfulness is an applicability assumption about the environment. The same residual is counted twice (M-T2 and M5/H4/H5). | Count it once. | internal |
| 16 | normal | theory omission | F31, F34, §9.4 C6, §1.14.1 root | internal | These are four instances of one regress. A1 already says it ends in an act. Only *which* act is open. | Unify the forks. | internal |
| 17 | normal | theory omission | Ch. 9 vs corpus kernel sets | S0973 L1747–1773 | The corpus's kernel lists are not compared with RESEARCH-PURPOSE's seven. S0973's list has Artifact, Type, Identity, Provenance, Event, Invariant, Transformation, Policy. S0167, S0357 and S0630 are unread in order. Completeness is untestable. | Compare when read in order. | S0973 source-verified |
| 18 | normal | theory omission | book-wide | S2834 L2039–2081 | The factive/non-factive split of "knowledge" (E_t, A_t, K_t) is absent. A grep for "factiv" in `manuscript/` gave no hits (a search miss is not absence). §9.9's "trust in a knowledge item" uses "knowledge item" without it. | Add a definition. | source-verified |

## 3. Changed theory (TT4 settlement)

| Item | Holds? | Reason |
|---|---|---|
| §9.9 body, T1 (CON-0160) | Partly | A definitional re-expression whose content is the premise list. PROVISIONAL is right. It is not a reduction result. |
| §9.9 L1–L5 | Mostly | L1 and L2 follow from A1 and T1. L3 to L5 are Hyp. The trivial case (constant f) is tested. |
| §9.9 T2 / CON-0161 (restated) | Partly | Holds for this estate and a single operator. The extension to a kernel axiom does not (finding 15). |
| §9.9.9 result 5; F23 row; outline Trust row | No | Findings 2 and 10. |
| F34 | Holds as open | Overlaps F31 (finding 16). |
| §9.9.11 | Holds | Accurate self-correction. |
| §9.8 M5 | Holds as a model | S0129 L4 concerns one recording process, so applying it to the whole record is an extension. It duplicates M-T2. |
| §9.8 result 6 | Holds | S0129 L45 and L158 verified: R-CONFLICT is not adopted. |

## 4. Refutation adequacy and the extra task

**Adequacy of the book's attempts**

| Attempt | Verdict | Reason |
|---|---|---|
| §6.3 C1 CON-0086 counterexample | Inadequate as a test | The book says it "cannot fail". This is honest labelling. |
| §6.3 C2 CON-0087 (pattern card) | Adequate | It finds a third kind. |
| §6.1 C4 counterexamples | Adequate | Set-level portability is a real counterexample. |
| §9.1 C4 toy model | Adequate for form only | It illustrates an easily proved theorem. I checked the counts by hand: 64 states, 16 valid, 128 subsets. I did not run `kernel_model.py`. |
| §9.6 "64 small cases" for set union | Inadequate | The cases are unspecified, and the book says "form only". |
| §9.7 step 2 three interleavings | Adequate | It is a constructive counterexample, but it depends on §1.14.1, which I did not verify. |
| §9.8 M1–M5 | Partly | They are narrative models and anecdotes. |
| §9.9 L4 models (a) and (b); the constant-f test | Adequate | |
| M-T1 | Self-declared trivial | |

No falsification record exists for most Ch. 6 claims (§6.1 C1/C2, §6.2 C1/C2, §6.4 C1/C4/C5, §6.5 C0 to C9), §9.2 to §9.3, §9.6 Analysis 1 or §9.9 T1. The "what a reviewer would need to challenge it" lines are not attempts.

**Foundational derivations (extra task)**

| Derivation | (a) Counterexample | (b) Boundary | (c) Rival | (d) Falsifier | Book's treatment |
|---|---|---|---|---|---|
| §9.1 C1–C3 | A required refusal is not a reachability. A kernel that is a guard breaks monotonicity. | Stops with guards, trace invariants, concurrency, or an interface with environment inputs. | The degeneracy comes from formalising A2 over subsets. The guard reading is the natural one for EXT-011 L230 (cannot open). | A corpus mechanism that cannot be a closed-reading addition, such as BC-7's refusals. | Adequate for the form. Inadequate for carrying weight (finding 1). |
| §6.5 owns/preserves and record ≠ act | I-4 has two owners. | Stops with jointly or constitutionally owned invariants. | Ownership means holding the amendment act. | Two readers applying "reason lives in" who disagree. | Partial. |
| §9.6 relocation and serialization | Cross-application uniqueness met by per-application stores with detection. A partition of keys by application. | Stops if identity is assigned by the recorder, or prevention is not required. | The external act artifact (git order) supplies the serialization, so no kernel is needed. | A multi-application design with a uniqueness constraint and no shared point. | Inadequate: it drops the detection clause (finding 5). |
| §9.8 Lemma D | None. It is a definition. | Valid only if CE is defined. | A stored bitemporal state with provenance. | A model satisfying all premises where Hist is undetermined. | Adequate and honest. |
| §9.9 T1/T2 | A constant f. | Stops at the root of adjudication (F34). | Faithfulness as an environment assumption. | A corroboration model with a single operator. | Adequate for T1, over-extended for T2. |

## 5. Concept verdicts and architecture view

**Verdicts** (only for concepts I tested; "internal" means checked for internal consistency only)

| Concept | Verdict | Scope or reason |
|---|---|---|
| CON-0099 | VALIDATED | Closed reading and A1∧A2 fragment. I re-proved it. |
| CON-0100 | PROVISIONAL | See finding 1. |
| CON-0075 | VALIDATED | Trivial lattice. Tiers verified at S0028 L118–133. |
| CON-0087, CON-0089, CON-0102, CON-0104, CON-0105 | VALIDATED | As distinctions. Quotes verified. |
| CON-0108 | VALIDATED | Blocking enforcement only. |
| CON-0111 | VALIDATED | Non-identity only. The S2834 example is asserted, not shown. |
| CON-0085 | VALIDATED | As the logical reading (necessary, no sufficiency). The non-reproducibility evidence is PROVISIONAL (finding 12). |
| CON-0086, CON-0103, CON-0117, CON-0132 | PROVISIONAL | |
| CON-0134 | INVALID as stated | See finding 3. The restated form is PROVISIONAL. |
| CON-0138 | Not built | Not a function. |
| CON-0148 | INVALID as stated | Restated form PROVISIONAL, conditional on CE. |
| CON-0160 | PROVISIONAL | |
| CON-0161 | INVALID as stated | Restated form PROVISIONAL, scoped to this estate. |
| CON-0114, CON-0116, CON-0118 | Agree INVALID as stated | Not independently stressed. |

Not tested: CON-0072/0073/0074/0088 (only read), CON-0110, CON-0115, CON-0135 to CON-0137, CON-0139 to CON-0143, CON-0146 to CON-0149.

- **Disproved:** the S0071 T-1 absolute, and "never consults the session".
- **Unsupported:** any kernel placement, and F23 result 5.
- **Unresolved:** F22, F26, F27, K4, K5, F31, F34.
- **Not yet built:** Req (T9-01), the state space (T9-02), CE.

**Architecture view**

- **Chain.** The chain runs A1, U, RA and ARCH-INV-1 as laws, then P, Req and H1′–H5 and L1–L5, to the results F22/F23. The candidate primitives tested are only history and trust. Authority-record and evaluator are tested but are not on RESEARCH-PURPOSE's list. Identity, lifecycle, evidence, relationships, policy and authority are untested, so completeness is untestable.
- **ARCH-INV-1.** It is a kernel invariant used in the §9.6 test and sourced to V3, which is outside my allowed list.
- **Isolated parts.** §6.1 to §6.3, most of §6.4, §6.7, and §9.2/§9.3 (evidence only).
- **Near-circularities.**
  - The relocation test parameters (K3 and R_s) are set by an act. The test is then said to decide F22.
  - §9.8's necessity rests on CON-0134, which rests on T-1.
  - §9.6.5 row 1 flags the BC-7 boundary as read off the same mechanism, which the book already notes.

**Independence of the scaffold's and the history properties' components**

| Component | Result | Basis |
|---|---|---|
| P and Req | independent | The toy rows show a model where each fails alone. |
| A2 | independent | |
| H1′, H2 | independent | Only relative to A1-as-fact (M1, M2). |
| H3a | derivable | From H1′ and RM-3 (the book). |
| H3b, H4, H5 | independent | |
| L1, L2 | derivable | From A1 and T1. |
| L3 to L5 | undecided | |

## 6. Coverage

**Ranked gaps**

1. No link from the formal scaffold to the boundary tests (finding 1).
2. The primitives' completeness is untested, and Req is undefined.
3. No comparison with the corpus's kernel lists (finding 17).
4. Factivity (finding 18).
5. The enforcement-class taxonomy of S0073/S0078 (refuse, overwrite, non-consultation, writer obligation; OQ-12). The book has three classes: stated, held, enforced. Also the (A) to (D) diagnosis of an apparent invariant violation.
6. New fork: prevention versus detection under K4.
7. Unify F31, F34 and §9.4 C6.

**Blind comparison, per source**

| Source | Covered | Distorted | Missing |
|---|---|---|---|
| S0071 | Root test, owned/preserved, recorded occurrence, OQ-4/5/6/8/9/10, two truth disciplines, T-2 | T-1 (finding 3); `authorized` (finding 4) | T-3 as a named invariant; the Observed/Inferred/Proposed scheme against the book's labels; the "recorded facts, not published events" point; OQ-7 and OQ-11 |
| S0066 | All 26 anchors verified | none found | Cold-boot qualification; "no empirical support (n=0)" is implicit only |
| S2834 | §9.3 C4 | none | Factivity; the "does not guarantee" list; the claim-status matrix |
| S0073 | C-3 (D) in §6.5 C2; the `recordedBy` referent | F-1, C-2 | The 4-way enforcement class; the diagnosis taxonomy; the falsified cell as the empirical instance of T-1b |
| S0034 (skim, L1–150 plus selected lines) | §6.3 | none | Covered otherwise |

**Source defects, kept separate**

- Counts are time-indexed and drift: 117, 130 and 144 records; 210 and 218 transitions; 47 and 48 grants. §9.7 states them by date.
- S0071 L137 and L307, and S0067 L83, carry the false F-1 claim.
- S0071's T-1 is the absolute form.
- §9.3 C4 notes that S2834's foundation list (L2093–2101) lacks dependency.
- I found no misquoted line among about 90 anchors checked. Two were located only after a second look: S0045 L77 (the quote is at the end of the line) and S0071 L237 (it does not say HANDOFF; S0078 supplies that).

## 7. Next steps, in dependency order

| Step | Completion condition |
|---|---|
| 1. Resolve scope of §9.1 (finding 1) | A model with applications, guards, transition invariants and uniqueness, or an explicit "informal" label on §9.6 to §9.9 |
| 2. Correct source-bound distortions (findings 3, 4, 10, 14) | CON-0134, §6.5 C4/C5, §9.7.5, §9.8.4 and the map rows match S0072/S0073/S0078 |
| 3. Fix the attribute signature (F13/T9-02) and define Req (T9-01) | Each primitive and requirement has a stated language and source |
| 4. Define CE (total, and either monotone or declared non-monotone) | The §1.14.1 formal defects are repaired |
| 5. Re-run the history and trust independence tests (findings 2, 7, 8, 15) | Disjoint definitions, formal models |
| 6. Add the prevention/detection fork; unify F31, F34 and §9.4 C6 | |
| 7. Classify Ch. 6 concepts kernel or application; park the rest | |
| 8. Validate the relocation test on a known case; compare corpus kernel sets | |
| 9. Record falsification entries per Rulebook form | |

**Question for the Principal.** Must the kernel *prevent* cross-application uniqueness violations or only *detect* them? K4 cannot be decided without it.

## 8. Limits

- I did not open `chapter_06/review` or `chapter_09/review`, because they are not on my commission list and note item 3 warns against old texts.
- I read Ch. 1 only through targeted greps (the total-order rule at §1.14.1 and the change log).
- I did not open EXT-008/010/011 (not allowed), so §9.1.1 and §9.1 C1 are checked for internal consistency only.
- I did not open V3 and did not read Ch. 7 (absent from the copy). I did not read Ch. 8 §8.6 or §8.9 to §8.11.
- Allowed sources cited but not checked: S0050, S0090, S0091, S0092, S0095, S0096, S0113, S0068/69/70, S0916, S1414, S2775, S0002, S0001 and S0042.
- I did not run `kernel_model.py`.
- I did not attest the model-separation question.
- I read the register rows for 14 IDs only, plus the map rows F22/F23/F26/F31/F34 and the outline Trust row.

## 9. Files opened and searches run (reviewer note item 5)

**Item 3 files:** I reached none. The only working-location file I touched was the commission-listed `BRIEF.md`.

**Opened, step 1** (all under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/`):
- `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/P4/BRIEF.md` (one grep, then in full in step 2)
- `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` (S0071)
- `docs/knowledgeos/developer_guide/developer-guide-set-up-ai-agents-with-knowledgeos.md` (S0066)
- `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260907-131918_what-knowledgeos-makes-possible-beyond-conventional-ai-rag.md` (S2834)
- `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-verification-2-report.md` (S0073)
- `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md` (S0034, L1–150)

**Opened, step 2:**
- `P4/STEP2-COMMISSION.md` and `P4/BRIEF.md`.
- In `BOOK-UNDER-REVIEW/06-theory/`: `RESEARCH-PURPOSE.md`.
- `manuscript/chapter_06/`: 06.00–06.07 and `TODO.md`.
- `manuscript/chapter_09/`: 09.00–09.09 and `TODO.md`.
- I concatenated them into my own temporary files in the scratchpad directory, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/` (`ch6b.txt`, `ch9a.txt`, `ch9b.txt`, `ch9c.txt`). I also read the harness-saved copies of two outputs.
- In the copy, by grep or `sed`:
  - `CONCEPT-REGISTER.tsv` (14 rows plus the header).
  - `THEORY-MAP.md` (lines 138, 139 and the TT4 line).
  - `manuscript/BOOK-OUTLINE.md` (Trust and History rows).
  - `manuscript/CHAPTER-PLAN.md` (a grep for "chapter 7", no hits).
  - `manuscript/chapter_01/01.14-state-is-not-authority-roles.md` and `01.99-change-log.md` (grep "total order").
  - A listing of `chapter_01/` and `manuscript/`.

**Allowed corpus sources, selected lines only (via `sed`):** S0028, S0031, S0033, S0034 (L174–266), S0035, S0036, S0037, S0039, S0045 (L40–80), S0049, S0062, S0067, S0072 (L32, plus a grep), S0078 (greps and L63, L143, L201–208, L230, L258, L294), S0128, S0129, S0234, S0913, S0973 (L1720–1780 plus selected lines), S1500, S1609, S2782.

**Searches (grep/rg, scoped):**
- In the copy:
  - `grep -E "^CON-(0072|0099|0100|0102|0110|0111|0114|0117|0132|0134|0138|0148|0160|0161)\b"` on `CONCEPT-REGISTER.tsv`.
  - `grep -n -E "^\| *\**F(22|23|26|27|31|33|34|13|14|4|18)\b"` on `THEORY-MAP.md`.
  - `grep -n "TT4|Theory turn 4"` on `THEORY-MAP.md`.
  - `grep -n -i "trust"` and `grep -n -i "history"` on `BOOK-OUTLINE.md`.
  - `grep -rl -i "factiv"`, `grep -rn "S2834"` and `grep -rn "S0357\|S0630"` over `manuscript/`.
  - `grep -rn -i "Knows(\|factive\|true belief\|E_t"` over `manuscript/`.
  - `grep -n "total order"` over `chapter_01/*.md`.
- In allowed sources:
  - `grep -n "to-be-exercised|never exercised"` and `grep -n -i "absence of exercise…"` on S0045.
  - `grep -n "T-1"` and `grep -n -i "origin"` on S0078.
  - `grep -n "T-1\|derived"` on S0072.
  - `grep -n -i "6 of 9"` on S0034.
- `book_search.py` was not run.
- No `git` commands.
