C77 STEP 1: BLIND INVENTORY

Window C77, reviewer W. Transition review, step 1 only. One source, read whole: lines 1–1563 in four consecutive reads. `wc -l` gives 1562 because the file has no final newline; the last line is 1563.
Independence is process independence (I did not write or see the Professor's work), not personal independence.

## S0321: `docs/knowledgeos/brainstorming/kernel/20260822-161933-snf-formula-research-review-and-measurement-framework.md`

**Depth: full.** The file carries theory throughout (definitions, formulas, distinctions and verdicts), so no part qualified for a skim.

**Structure (fact).** The file has three layers:
- **A** (1–482): an "External Research Review" of the SNF formula. It classes itself "NOT ARCHITECTURE EVIDENCE / NO KERNEL DECISION" (478–480).
- **B** (483–1143): a critique in the first person ("I agree with the direction, but would not adopt the combined formula").
- **C** (1144–1563): a "Senior Mathematical Review" that "FULLY AGREE[S]" with B (1152, 1551).

### Ranked items (most important first)

1. **Definition: SNF separated from its implementation (B 504–521; C 1174–1198).** SNF(E,C) = N_S(M(E,C)).
   - E is the expression, C the context and M the semantic interpretation candidate. N_S is a normalisation function that must satisfy explicitly tested invariance and non-collapse properties.
   - How N_S is implemented is left experimental: "the port is stable; SNF is the replaceable mechanism behind it" (1143).
   - The definition rests on an architecture fact cited from outside: "SNF encoding is undecided", and the Expression↔Meaning Port is encoding-agnostic (504). I did not open the cited port contract, which is outside my allowed list.
   - C restates the result as "representation (replaceable) ≠ measurement (stable)" (1166).
2. **Distinction and boundary rule: fingerprint ≠ semantic equality ≠ knowledge identity ≠ truth (B 551–593; C 1202–1227).**
   - Fingerprint(SNF) = Hash(CanonicalEncoding(SNF)). Hash equality shows only that two representations are equal.
   - The prohibition "SNF equality → Knowledge identity is forbidden" is cited from the port contract (575). C adds that knowledge identity "requires provenance, not just content" (1222).
   - [I] C's table (1220) gives the fingerprint the use "Fast lookup, identity check". "Identity check" sits uneasily with the prohibition. It is a homonym risk: representation identity vs knowledge identity.
3. **Distinction: interpretation entropy vs epistemic uncertainty (B 608–649; C 1231–1253).**
   - A had made H_sem depend on the knowledge base. B rejects that: the same expression could get a different H in a different KnowledgeOS state. B calls that quantity "contextual interpretation uncertainty", not semantic entropy.
   - B's replacement is H_I(E,C) = −Σ P(θ_i|E,C) log P(θ_i|E,C).
   - Rule cited from the port contract: interpretation probability may exist on the candidate side but "must never become KnowledgeOS Confidence" (647).
   - [I] C allows that H_I may change "for context changes, which are explicitly part of C" (1251). If the context C contains KB state, the coupling B wanted to remove comes back. The boundary of C is undefined.
4. **Measurement vector, and rejection of a single score (B 981–1113; C 1257–1282, 1445–1459).**
   - The vector is M_SNF = (H_I, C_conv, D_sep, T_preserve, FCR, FDR, A_abstain).
   - A composite "SNF = 87%" score is explicitly rejected (1075–1089). It "would recreate the exact problem we have spent the last several iterations removing". That earlier problem lies outside this source.
   - The architectural question changes from "how intelligent is SNF" to "does this mechanism preserve the distinctions KnowledgeOS requires?" (1105–1111).
5. **Two failure modes and their error rates (B 774–834; C 1372–1410).**
   - False convergence or false collapse: FCR = P(SNF(E1)=SNF(E2) | E1≢E2).
   - False separation or false divergence: FDR = P(SNF(E1)≠SNF(E2) | E1≡E2).
   - Ranking: FCR is "potentially much more dangerous" (810). C escalates a false collapse to a "constitutional violation" (1387).
   - Change within the source: B first writes FCR with exact equality (817). B's own later restatement (1059) and C (1376) use ≈. C's signature then adds τ (1455–1456). As a result, which threshold defines "collapse" is never fixed.
6. **Distinction: classes of transformation (B 838–907).**
   - T_j ∈ {T^preserve, T^alter, T^ambiguous}.
   - Example: the passive voice must converge; negation "must not converge".
   - Claim: without this split, stability "can reward a system that simply ignores meaning-changing transformations".
   - [I] T^ambiguous gets no metric anywhere. C says that T^alter is tested "via D_sep or FCR" (1366).
7. **Non-collapse reformulated (B 706–770).**
   - The threshold-only form NC = mean I(NSID>τ) is criticised because τ is arbitrary: 0.79 vs 0.81 flips the outcome.
   - It is replaced by a continuous D_sep plus a reported FAR_τ, the "semantic false-collapse rate".
   - Port-contract citation: collision rate is a critical metric, and non-collapse is required "in both directions" (768).
   - [I] FAR_τ (760) and FCR (816–821) are both named "false-collapse rate", and FAR_τ never appears again. This is a duplicate term with two definitions. The acronym FDR also clashes with the standard "false discovery rate".
8. **Boundary rule: KG normalisation ≠ semantic normalisation (B 911–937; C 1436).**
   - "A KG can be perfectly normalized while still representing the wrong meaning."
   - 1NF/2NF/3NF-KG is deferred as a possible later optimisation and kept out of the definition of SNF.
9. **SAIT/STMS demoted to a research candidate (B 941–977).**
   - A had proposed SNF(K) = STMS-Normalize(K) (290). B's grounds: A itself rated SAIT only "moderate" evidence (951).
   - B proposes a research space instead: SNF-A symbolic, SNF-B graph, SNF-C STMS-inspired, SNF-D hybrid, all run on a common benchmark (956–975).
10. **Original proposals in layer A, later superseded (A 19–339).**
    - Fingerprint: the Gödel product ∏p_i^{e_i} becomes Option B, SHA-256 over a URDNA2015 canonicalisation plus a lazily computed Gödel verification fingerprint (40–61).
    - Entropy: the knowledge-base-dependent H_sem, estimated with LLMs (116–129). A lists five rival definitions of semantic entropy (75–110).
    - Distances: NSID-based C_snf, NC_snf and T_snf (166–232).
    - Pipelines: two competing combined pipelines (255, 313).
    - Mathematical relation quoted (112): H(f(U)) ≤ H(U), strict when f merges two symbols of positive probability. [I] Correct for a deterministic f.
11. **Acceptance thresholds and protocol (C 1461–1491).**
    - Every SNF variant is run on the 1,000-case corpus and all 7 metrics are computed.
    - Selection rule (1476–1479): FCR<0.01, FDR<0.05, C_conv>0.90, D_sep>0.85.
    - [I] The selection rule equals the "Target" column of the table (1483–1491), not the "Minimum" column. The meaning of "Minimum" is therefore unclear. No threshold is justified.
12. **Kernel diagram in layer C (1506–1533).** The KnowledgeOS kernel "Uses M_SNF metrics to decide epistemic state". It outputs VALIDATED / UNKNOWN / CONFLICTED / REJECTED / SUPERSEDED.
    - [I] This is a contradiction with three statements:
      - B 1113 keeps interpretation probability on the candidate side, with the KnowledgeCore deciding admissibility.
      - A's discipline rule says "Do not design the kernel" (458).
      - Most of M_SNF are benchmark aggregates over sets of pairs, yet C writes M_SNF(E, C, SNF_variant) for a single expression (1450). That is a type error: corpus-level metrics are used as per-claim inputs.
13. **Abstention (B 1069–1071 vs C 1414–1428).**
    - B: prefer calibration to thresholding entropy.
    - C: "precision and recall at a calibrated threshold", "with the note that calibration is preferred".
    - [I] B and C disagree on emphasis. A_abstain has no formula, and B's table entries CR (collision rate) and AR (701–702) drop out of the final vector.
14. **Properties C asserts for the metrics (1295–1406).** Boundedness, extrema and monotonicity are stated as "✅" with one-line reasons.
    - [I] These are sketches, not proofs. "Additive for independent interpretations" (1302) is not a property of one H_I. "T_preserve min when meaning changes" (1364) mixes up the scopes of T^preserve and T^alter.
    - C_conv and D_sep presuppose a ground-truth relation ≡. Only C 1325 states this, and only for C_conv.
15. **Open questions in the source.**
    - The "Unknown" row (372): whether EKS/PKS/AIP already implement the improvements; performance; rigour vs cost.
    - An accuracy hypothesis of 74.2% → 85–90% on a "120-case corpus" (405–406). The 74.2% baseline is not explained in this source.
    - Discipline rule (450–468): "EXTERNAL RESEARCH ≠ KNOWLEDGEOS ARCHITECTURE". Only a comparison with the archaeology of what we have can establish relevance. No ADRs, no bounded contexts, no technology choice.

### Changes over time inside the source

| Component | A | B | C |
|---|---|---|---|
| Fingerprint | Gödel product → SHA-256 plus Gödel-VF (61) | hash fingerprint; Gödel a secondary option (557–567) | accepted; "Gödel as truth" rejected (1439) |
| Entropy | depends on the KB (117) | H_I(E,C); epistemic uncertainty is separate | accepted |
| Convergence | C_snf over all pairs i≠j (167) | C_conv, conditioned on ≡ (1011–1024) | accepted |
| Non-collapse | I(NSID>τ) (190) | D_sep + FAR_τ / FCR | FCR "constitutional" |
| Stability | T_snf over all T_j (221) | T_preserve only for T^preserve | accepted |
| KG-NF / SAIT | integrate (266, 304) | defer / research candidate | accepted |
| Corpus | 120 cases (405) | — | 1,000 cases (1464, 1563) |
| Next artefact | implement P1–P2 (404) | "KOS-SNF Measurement Specification v0.2" (1141) | "KOS-SNF-ME v0.1" (1563) |

### Source defects (listed separately; [I] unless marked fact)

- **Citations.** Bibliography labels and links do not match. For example, "Measuring Semantic Entropy, ACL 1997" links to IEEE (417). "Semantic Channel Coding" links to the ACL W97 paper (418). "Thoughtworks Medium" links to SciSpace (425). Definition 5 (an LLM method) cites a "knowledge entropy" PDF (104).
  - KG normal forms are dated "Recent work (2020)" but linked to arXiv 2401 (238).
  - Several quotations carry no citation at all (32, 59, 185, 216).
  - None of the external claims can be checked from inside the source.
- **Evidence ratings.** A rates KG normalisation "Strong" (369) but "Moderate" (352, 385). This is an internal contradiction.
- **Pipeline order.** B quotes A's pipeline as "Normalize(Logical(KG-NF))" (917). A has KG-NF(Logical(K)) (313). "Normalize" means KG-NF at 258 but SAIT at 318.
- **Notation.** "Values in range[0][1]" (171) is garbled. The NC formula sums over ordered pairs i≠j but normalises by "number of distinct pairs" (190–193), so ordered and unordered counts are mixed.
- **Recommendation wording.** Option A is described as hash-based and Option B as hash plus Gödel. The recommendation "Option B — hash-based canonical labeling for performance" (61) mixes the two.
- **Straw man.** C claims that A "conflated fingerprint with semantic equality" (1216). A's stated motive for the change was scalability (36–38, 396).

### Assumptions and trivial cases [I]

- **Constant SNF map** (everything to one SNF): FDR=0, C_conv=1, T_preserve=1, but FCR=1 and D_sep=0.
- **SNF injective on surface strings:** FCR=0 and D_sep is high, but FDR≈1 and C_conv is low.
- Only the joint vector excludes these two maps, which supports the "no single score" rule (item 4).
- Abstaining on everything is constrained only through A_abstain recall, which has no formula yet.
- Every metric presupposes a ground-truth ≡ over expressions and an NSID that is actually computable. The NSID claim is reported from external literature, not shown.

## Cross-source table

Only one source falls in this window, so the table compares the three internal layers.

| Theme | Layer A (1–482) | Layer B (483–1143) | Layer C (1144–1563) |
|---|---|---|---|
| Kind of text | literature synthesis, web citations | critique of A | review that endorses B |
| SNF definition | one concrete pipeline | abstract N_S(M(E,C)) | accepts B |
| Kernel role | "Do not design the kernel" | port boundary preserved | kernel uses M_SNF to set epistemic states (conflicts with A and B) |
| Evidence status | "NOT ARCHITECTURE EVIDENCE" | cites the port contract (outside this source) | "FULLY ACCEPTED" |

## Lineage note [I]

- **Not independent.** Layers B and C are not independent confirmations. C reviews B directly and repeats B's vocabulary, table structure and verdicts. Their agreement is one line of support, not two. B in turn reacts directly to A.
- **Outside material.** All three layers rest on material outside this source:
  - the "Port Contract" / Expression↔Meaning Port (504, 575, 647, 768, 1113);
  - the "current" SNF formulas with the Gödel product and C_snf = 1−d̄;
  - the 74.2% baseline and the 120-case corpus;
  - "the problem we have spent the last several iterations removing" (1089).

  A source citing these is presumably a successor of earlier SNF sources in the corpus. I did not open those sources, and none of the excluded IDs (S0155, S0156, S0157, S0161, S0170, S0174) is named in this file.
- **External evidence.** A's literature evidence comes from one synthesis whose citations are scrambled. It should count as one unverified line, not as several references.

## Files opened

1. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C77-S0321/BRIEF-W-STEP1.md` (whole)
2. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md` (whole)
3. `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260822-161933-snf-formula-research-review-and-measurement-framework.md` (S0321; read whole in four parts: 1–400, 401–820, 821–1220, 1221–1563)

## Commands and searches run

- One Bash call, from the repository root:
  - `grep -c` and `grep` for the S0321 filename in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. This was the targeted allowed-list check the brief permits. Result: one row, `S0321 … match`.
  - `wc -l` on the S0321 file. Result: 1562, meaning no final newline.

No other searches or listings. I wrote no scratch files and edited nothing in the repository.
