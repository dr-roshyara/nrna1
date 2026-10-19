*Reviewer B's (Sonnet) steps 2–3 report, theory turn 4, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — TT4 (reviewer B)

Independence is process independence only. I did not open any *-A.md file under review/TT4-trust/. I read §9.9, STEP8-AND-SCAN, CON-0160/0161, the 621ef5edd diff, the C17 REVIEW-REPORT and SETTLEMENT, §8.10, §9.8, §8.6, §1.12 and §1.13 diff, and the source lines named below. My step-1 inventory is the baseline.

## 1. Assessment
- §9.9's two-way split holds up: trust as an assessment of a source (T-a) is built from the evidence, identity, record and policy primitives, and trust as the ground of the record (T-b) is an assumption. But T1 is a stipulation, not a derivation (finding 3). T2's argument is a sketch with a missing horn (finding 1). The sources' trust-in-an-artifact sense is left out (finding 4).
- The largest risk is a premise T1 hides: someone must label outcomes as correct or incorrect. That is neither covered by T2 nor solved by L5 (finding 2).
- The C17 changes are mostly sound. The corrections to §8.9 C2, §9.8 and the §1.13 integrations hold. I found only minor issues there (findings 10–12).

## 2. Findings, ranked
Counts: 12 findings, 0 critical, 6 major, 6 minor. "S090" below means S0090, and so on.

| # | Sev | Type | Claim, location | What is wrong or missing | Correction |
|---|---|---|---|---|---|
| 1 | major | defect (proof is a sketch) | T2, §9.9.5 steps 1–3 | Step 1 says all evidence about faithfulness "is itself a record". That is false if "record" is read narrowly. Counter-cases:<br>• a hash-chain or signature check, which relies on key custody rather than on another record;<br>• re-execution of a derived claim;<br>• a digital act that is the record (a signature is the act).<br>If "record" is read broadly, the refutation condition in §9.9.8 ("a check from inside the record that assumes no record faithful") is vacuous. Step 3's trilemma omits the corroboration horn: independent records agreeing, with no single record assumed faithful. Also, T-b lumps H4 (soundness) with H5. A marked absence is checkable inside the record; the claim "no unrecorded act exists" is an absence claim needing a search scope (§8.4). | State the corroboration horn and close it with §8.6 C1 (independence can be refuted from the record, never shown) and §8.10 C7 (organisational independence and external attestation are absent in a single-operator estate). Then T2 follows, with the digital-act case reducing to identity binding. Report as a sketch until then. |
| 2 | major | omission (hidden premise) | T1 premise (i) and §9.9.3 | T1 needs outcomes labelled correct, which is my adjudication channel J. T2 does not cover it. T2 concerns whether a label is faithfully recorded. J concerns whether the label is right, and a faithfully recorded label can be wrong. J resolves to one of two things:<br>• an act that says what counts as correct, under A1 (S0913 L601–639);<br>• observation graded under §1.3 without using T-a (S0913 L641–677).<br>It looks like authority, not trust, as I suggested. It is not a separate primitive, but it is a missing premise. There is also a well-foundedness question: if T-a of the labelers weights the labels, T-a depends on T-a (compare F31). §9.9.3 notes only "who evaluates", and L5 answers independence, not correctness. | Add a premise (v): outcomes are adjudicated by an A1 act or by evidence graded without T-a. Say that T-a is then derived from evidence, authority acts and the record. |
| 3 | major | defect (overclaim) | T1 "Ded"; M-T1; result 2 | The proof says f exists, so the value is determined. That is the same close-to-definitional status the book gave Lemma D. §9.9.3 admits "it defines T-a as evidence-based", yet the headings say "derivable" and the register says Ded. M-T1's "no independence model under T1's definition" restates the stipulation. f is unconstrained, so any function qualifies, including a constant. The only support is a design requirement, not a finding: S0913 L781–804 ("trust becomes knowledge"). S1500 does not test it. Trust in the security sense (will the source be compromised? S0973 L453) is not a function of past outcomes without a threat model (S1500 L285: none exists). | Relabel T1 "conditional reduction". Constrain f (it uses past outcomes monotonically and is calibrated). Scope "derivable" to the reliability-and-identity sense. |
| 4 | major | defect (definitional conflicts) | §9.9.2, T1, L4 | The conflicts I found are not handled:<br>• (a) Arity. S0913 has Trust(a,d,c,t) with no assessor; S0973 has Trust(x,y,C,t) with a truster x. §9.9.2 cites both "in meaning" as one thing. T1's formula has no assessor, but L4 uses Trust(A,B).<br>• (b) Authority is an input to TrustAssessment in S0973 (L451–457, L1006). T1 drops it silently.<br>• (c) The containment of reliability and trust is reversed between the sources (S0913 L203–227, L726–730 against S0973 L441–457). §9.9 takes S0973's side without saying so. Under T1, "trustworthy but unreliable for a measurement" (S0913 L217) is only a different d, so T-a differs from reliability only by identity status and policy.<br>• (d) TrustAssessment(artifact) (S0973 L996–1012) overlaps EvidenceAssessment (L1559–1577) and is not mentioned. The four-layer model (L958–994) and the SemanticTrustBoundary admission gate (L1775–1799) are omitted, though the central question is about knowledge, not sources. | Fix one signature per sense and add the assessor. Say whether authority is an input to f. State the containment explicitly and what T-a adds beyond reliability. Add an artifact-level sense or justify excluding it. |
| 5 | major | omission (must-do gap) | L1–L5; T-a as a whole | Trivial-case test, my own:<br>• A constant or always-zero T-a satisfies L1–L5. L1–L5 are all must-never constraints.<br>• No operation (compute, revise on new outcome evidence, expire on revocation, withdraw) and no invariant owner (who guarantees L5?) is stated.<br>• "Req" is left open.<br>For T-b, a discharge act exists (outside act or attestation) but is not stated as an operation. It also bears on F33 (a state no command can leave without an outside fact). §9.9 does not link F33, and T2 suggests the answer for facts about the world is "no": the kernel can only make absence visible (§1.16.8). | State T-a's commands and who enforces each of L1–L5. Link T2 to F33. |
| 6 | major | defect (labels) | L5 | A one-step restatement of the source is labelled Ded. L5 requires evidence for T-a to be independent of the outputs it assesses. But §8.6 C1 (CON-0125, PROVISIONAL, flagged unverified in STEP8) says the record can refute independence, never show it. So L5 cannot be enforced from inside the record. S1500 L225 notes no damper; L5 offers a condition, not a damper. Self-reinforcement is also not always unsound, since independent adjudication (J) could validate it. | Relabel as Int or Hyp. Say it can only be refuted. |
| 7 | minor | defect | L3, L4 | L3's "by definition of f" is circular. Identity compromise (S0973 L568–596) crosses domains, so "says nothing about d′" is too strong. L4's derivation shows only that Trust(A,C) is not determined by two values. A policy f can make transitivity hold (delegated trust), and the 2-party notation is not defined in T1. | Restate both as "not implied; transitivity only by explicit policy" and keep them Hyp. |
| 8 | minor | distortion | §9.9.1 and result 1 ("two senses in the sources") | The sources use T-a only. T-b is §1.12's, and §9.9's own lineage paragraph says the estate's records use it "without using the word in this sense". Adjacent source support exists but is not cited: S0973 L554–563 (CryptographicTrust depends on KeyManagement) and L828 ("if the relevant provenance records are protected"). | Reword the headline; cite those lines for T2. |
| 9 | minor | omission | §9.9 as a whole | Not covered:<br>• S0973 L1703–1773 (Identity is added to the kernel list, Trust is not): direct source evidence on F23.<br>• "Who said this?" against "Why believe this?" (S0973 L1837–1855), and authentic ≠ reliable (L1609–1621).<br>• The key-compromise window (signatures made between compromise and revocation).<br>• Trust-weighted LR is undefined: S0913 gives no formula linking trust to LR (my sketch: trust is absorbed in P(E|H) and P(E|¬H), so it is derivable).<br>• S1500's verified LR error (see 12.3 below) and its note that Beta-reliability was dropped (S1500 L223). T1's f therefore has no formal reliability model. | Add as sources and notes. |
| 10 | minor | changed theory | §9.9.5 against §8.10 C5 and C10 | §9.9.5 cites the authorship trace without C5's caveats (one case, intact transcripts assumed). "Intact transcripts" is itself a T-b instance. C10 shows the START's time rests on the authority's later, undated word (S0095 L210), so a discharge fixes the fact, not its time (§9.8 H2). Neither point is stated. | Cite the restated forms and note both points. |
| 11 | minor | changed theory | §8.10 C10 | "Calls the sequence both 'sixteen-step' (S0095 L81) and 'eleven-stage' (L235)": these look like different objects (a capability sequence in an assignment, a chain in grant AMD1). Same-object identification is unshown. "Silently" rests on S0095 L99 not announcing a change, and S0095 explicitly says "The analysis method is superseded — not the direction." | Say "two differently ordered chains; the sources do not say they differ", and do not equate the counts. |
| 12 | minor | changed theory | §8.10 C2 Int | A finding "currently X" is mapped onto Hist(c,x,t,s,u) with t=s=u. Hist is a standing, a finding is a claim about the estate. The finding's act time and recording time differ (S0094 registered 00:34, amended 09:13, per git), which is the point of H2. CON-0156 is fine as a distinction; the Int is an analogy. | Mark the Int as an analogy. |

## 3. Concept verdicts
| Item | Verdict | Reason |
|---|---|---|
| CON-0160 / T1 | PROVISIONAL | Valid as a conditional reduction; the "derivable" headline depends on the definition and on findings 2–4. |
| CON-0161 / T2 | PROVISIONAL | Conclusion plausible; the argument is a sketch (finding 1). |
| T-a / T-b split | PROVISIONAL | Useful; T-b is the book's own sense (finding 8). |
| L1 (no authority) | VALIDATED | From A1 and "an assessment is not an act". Direction authority→trust is compatible. Trivially true, so scope it to T-a as defined; a rule-adoption act is the conferrer when a threshold triggers a grant. |
| L2 (no evidence) | VALIDATED (scoped) | A derived quantity adds no information beyond its basis. The double-count argument holds when the basis overlaps the claim. |
| L3, L4, L5 | PROVISIONAL | Findings 6–7. |
| M-T1 | PROVISIONAL | Circular (finding 3). |
| M-T2 | PROVISIONAL | The same as §9.8 M5, relative to form; the identification of T-b with H4 and H5 is not shown. |
| CON-0153, 0156 | agree: VALIDATED (distinction; scoped) | |
| CON-0154, 0155 (restated) | agree: PROVISIONAL | |

## 4. Answers to the coordinator's specific questions
- **Is T1 more than definitional?** No. The content is the premise list. The one non-trivial part, that nothing else is needed, is unproven. Candidates: the security sense, the cold-start prior (which is policy), and J.
- **Is J covered by T2?** No; it is a separate hidden premise that resolves into authority or observation (finding 2).
- **Is L1 right given S0973's authority-as-input?** Yes. The input direction is authority→trust and L1 forbids trust→authority. But T1 drops authority as an argument without saying so (finding 4b).
- **Are L4 and L5 sound?** They are plausible requirements with overstated labels (findings 6–7).
- **Does §9.9 handle the definitional conflicts?** No, not (a), (b), (c) or (d) (finding 4).
- **Is S0913's LR "if independent" omission relevant to L2?** Only marginally. §9.9 gives no combination rule, so it is not exposed. S0913 L1485–1503 states no condition for the product. The product needs independence given H and given ¬H (I checked: with P(E_i|H)=0.95 independent given H and E1=E2 given ¬H at 0.05, the true LR is 361/20 against a product of 361, a factor of 20). L2's double-count argument is exactly the dependence case. Add the correct condition if a combination rule is ever formalised.
- **Is the must-do gap handled?** No (finding 5).

## 5. Coverage of my step-1 items
- **COVERED:** trust ≠ authority (L1); no evidence created (L2); trust as a derived object (T1); contextual and temporal indexing (L3); transitivity (L4); feedback loop (L5, with caveats); identity lifecycle (partly); lineage as one line.
- **DISTORTED:** the three Trust definitions merged (items 5–8); containment of reliability and trust (item 9).
- **MISSING:** "who said this" against "why believe this"; authentic ≠ reliable; regress of trust's own provenance (J; item 13); independence model and LR condition (items 18–22); the kernel list with Identity in and Trust out (item 23); SemanticTrustBoundary; artifact-level TrustAssessment; crypto limits (item 27); Beta-reliability dropped; the key-compromise window; must-do gap.
- **OPEN:** where f lives (§9.9.9).
- **JUSTIFIABLY EXCLUDED:** consensus and quorum rules and BFT, which belong to §8.6 rather than to the question of trust as a primitive.
- **New forks:**
  - the well-foundedness of T-a (J; analogue of F31);
  - T2 ↔ F33.

## 6. Next steps, in dependency order
1. Finish T2's proof (corroboration horn via §8.6 and §8.10 C7). Done when the trilemma is exhaustive or the argument is marked a sketch.
2. Add premise (v) on J. Done when T-a's regress ends in an A1 act or in observation graded without T-a.
3. Fix signatures and containment (finding 4) and relabel T1 and the L-labels (findings 3, 6, 7).
4. State T-a's operations and invariant owners, and link T-b/T2 to F33 (finding 5). This needs a decision from the Principal on Req (must the kernel compute T-a?).
5. Cite S0973 L1703–1773, S0973 L554–563 and S1500's LR and Beta findings (findings 8–9).
6. Correct the minor changed-theory items (findings 10–12).

## 7. Limits
- I read §9.9, §8.10, §9.8, §8.6, the §1.13 and §8.9 diffs and the C17 documents. I did not read §5.7's diff, the §1.13.2-3 point 5 text beyond the diff, or the other §1.x sections beyond the ones named.
- Source lines I verified: S0913 L134, L197, L217, L752, L781, L793, L1399, L1421; S0973 L367, L441, L1424; S1500 L225; S0090 L11, L30; S0092 L15, L35, L85, L90; S0093 L43, L63; S0094 L19, L41, L77, L104; S0095 L13, L81, L99, L141–149, L193, L216, L235; S0096 L11–22, L28, L30. File times came from `git log` (S0093 09:05, S0094 00:34 and 09:13, S0095 09:20, S0092 transcript 00:42).
- Not verified: the counts "fourth time" and "third time" in C9; S0129 anchors beyond what the book quotes; §8.10 C9's other S0092 lines; the "seven exclusions" slip (C10).
- Computation: the LR counterexample, in my step 1 (Python, Fraction arithmetic).
- I did not read the other reviewer's files, the CONSOLIDATION sections, or any untracked brainstorm documents.

