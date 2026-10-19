*Reviewer A's (Opus) steps 2–3 report, theory turn 4, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — TT4 (reviewer A)

*Topic review of §9.9, STEP8-AND-SCAN, CON-0160 and CON-0161, plus the changed theory of the C17 settlement (621ef5edd), measured against my saved step-1 inventory. I read the book sections §9.9 depends on (§1.3, §1.6, §1.12, §1.13, §8.6, §9.8), RESEARCH-PURPOSE, the BOOK-OUTLINE and THEORY-MAP rows, the C17 report and settlement, and the cited lines of S0090, S0092–S0096 and S0129. Independence here is process independence only: the same operator stands behind sources, book and commission.*

## 1. Assessment
- **Holds:** T2's core holds: faithfulness cannot be *established* from inside the record. L1, read correctly, holds, and so does the conditional form of T1. The quotes I checked match.
- **Does not hold:**
  - "two senses" as an exhaustive result;
  - L5 as worded;
  - T2's "does not reduce / dischargeable".
  - T1 is a definition, and it hides a regress.
- **Largest risk:** §9.9 answers the central question's *"how can **knowledge** be trusted"* with trust in **sources**. Trust in a knowledge item is never treated.
- **Counts:** 12 findings: 0 critical, 7 major, 5 minor.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | major | omission | Result 1, "two senses" | S0973 L1001, L1794; RESEARCH-PURPOSE L123 | The sources also use **trust in a knowledge item** (`TrustAssessment(a)` over an artifact, with EpistemicSupport and Authority as inputs; "TrustedKnowledgeState"). They use **key assurance** ("CryptographicTrust", L557) and **sincerity** (S0913 L223). T-a is about sources and T-b about the record; neither is the purpose's "why a user may rely on it". | F23's answer is incomplete for the central question. | Add T-c, trust in a knowledge item, plausibly derivable from evidential grade, standing (§1.3) and authentic provenance, conditional on T-b. Mark sincerity and key assurance. Restate result 1 as "at least …". |
| 2 | major | defect | T1, "derivable" | §9.9.3; S0913 L752, L772 | T1 needs *evaluated* outcomes. Each evaluation is a claim by some evaluator, and that evaluator's weight is T-a again. So T-a has a regress like F31 (θ(u)). It ends only at a validator taken as ground truth, or a prior; my "anchors". T1 also assumes the outcome records are faithful (T-b) and does not say so. | "Derivable" hides a premise and a regress. | State the premises "outcome records faithful (T-b)" and "a designated root evaluator or prior, set by an act". Open a fork beside F31. |
| 3 | major | defect | T1 is definitional | §9.9.3, §9.9.6 | T-a is *defined* as f(evidence, identity, c), with f in policy. So "no independence model" follows from the definition. My test asks whether two states can be identical in evidence, identity, record and policy yet differ in trust. Under T1 they cannot, because priors and fiat weights are put into policy (§9.9.3, "a policy value"). The clause "with evidence, period, method, **uncertainty**, revision history" is the source's requirement and is not derived. | Its content is the premise list, as with Lemma D. | Label T1 a conditional, definitional reduction. Move the knowledge-item clause to Src/Hyp. |
| 4 | major | defect | T2, "does not reduce"; CON-0161 "dischargeable only by an outside act" | S0973 L185–297, L538, L560, L725–804; S0095 L208–217; §8.10 C10 | (a) H4 lumps three things: integrity since writing, authenticity of the author, and occurrence of the act. With hashes and signatures, S0973 reduces the first two to trust in keys and timestamps. Only occurrence stays irreducible. So T-b *reduces partially*, to a smaller anchor. (b) Like §8.6 C3, unfaithfulness can be *refuted* from inside the record (a hash mismatch, a contradiction); T2 omits this side. (c) The PO/ARB's word enters the system as a record transcribed by Governance. That anchors the root at a party; it does not discharge T-b. The example used is undated, and its time is contested (§8.10 C10), which §9.9.5 does not cite. | T2 overclaims. | Restate: "occurrence cannot be established from inside the record; integrity and authenticity reduce to key/timestamp anchors; unfaithfulness is refutable, never establishable; an outside act names the root, it does not discharge it." Cite C10. |
| 5 | major | defect | L5 | §9.9.4; S0913 L736–752 | "The evidence for T-a must be independent of the outputs it assesses" cannot be met: reliability is measured *on* the source's outputs. What must be independent is the **judgement of their correctness**, that is, the outcome evaluator. That evaluator must be independent of the source and of trust-weighted acceptance (the S0913 loop: trust → weight → acceptance → validation). The "must" does not follow from "can rise unchecked" without a normative premise. | The law as stated is unsatisfiable or wrong. | Restate it as independence of the outcome evaluator. Link it to finding 2. |
| 6 | major | defect | L1, "never confers" | §1.6 refined; S0913 L1386–1399 | Real counterexample to the unscoped reading: privileges by reputation threshold, where an adopted rule grants permission automatically when trust ≥ k. Under A1 the *rule act* confers and trust is its condition, so L1 survives only as "trust is not an act; it may be a condition of one". §9.9 says "Trust can still inform an act", but a standing rule makes trust *trigger* permission with no further act. S0973's authority-as-input-to-trust (L451, L1003) is the converse direction and does not contradict L1. T1's f, however, silently drops the authority that §9.9.2 quotes from L451. | "Never" misreads real systems. | Scope L1 as above. Note that T1 narrows S0973's trust and say why. |
| 7 | major | omission | Must-do and trivial case | Rulebook; §9.8 M4 | L1–L5 are all must-never constraints. A kernel with f ≡ Low satisfies T1 and L1–L5. §9.9 has no trivial-case test; M-T1 defers to Req. Must-do candidates in the sources: honour revocation (S0973 L586–590); revise with new outcome evidence and version it (S0913 L732); state the basis (S0913 L758–804). | The model can be met by doing nothing. | Add the trivial-case test and the must-do candidates, labelled Hyp. |
| 8 | minor | defect | L4 counterexample | §9.9.4 | As written, A "holds no evidence about C" yet "may weigh" B's claim. That claim *is* evidence about C (testimony), so the scenario is not a model. A valid model: f_A(∅) = Low, or B's testimony insufficient under A's policy. Then High, High, Low, and transitivity fails (checked by hand). It shows *non-determination* under some f, not non-transitivity under every f. Referral policies with weakening (discounting) are legitimate and not discussed. L4 also uses the relational Trust(A,B) while T-a's signature Trust(a,d,c,t) has no truster. | The arity is ambiguous. | Give two explicit models. Add the truster to T-a's signature. Mention referral trust. |
| 9 | minor | defect | L2 | §9.9.4 | The Ded covers only the same source. The source's law is universal (S0913 L1421). L4 itself uses B's trust assessment as evidence for A. | — | Scope L2 to the same source. |
| 10 | minor | defect | L3 uses S0913 L217 | S0913 L217 | "Generally trustworthy but unreliable for a particular measurement" presupposes a *general* trustworthiness (sincerity). That cuts against domain-bound trust, and it is the trust ≠ reliability distinction which T1's f (outcomes plus identity) largely collapses. | — | Report the tension. Use S0973 L379–425 for L3. |
| 11 | minor | omission | Temporal and crypto limits | S0973 L542–698; S1500 L223, L285 | Missing points: compromise time ≠ revocation time; signing time can be backdated after compromise; no threat model (an insider with key access); the Beta-reliability model that was dropped (it bears on f with few data, the prior anchor). | — | Add them to boundary cases. |
| 12 | minor | defect (changed theory) | §9.8 M5, result 6, §1.13.2-3: "Recording ≠ Asserting … among principles that are still RECOMMENDATION" (S0129 L96) | S0129 L97, L102 | L96 is blank; the heading is at L97. L102 calls Recording ≠ Asserting one of "the estate's **existing** principles". Only R-CONFLICT is a recommendation, and "History ≠ Reconstruction Guess" is "new". The fix for C17 finding 12 introduced a distortion. | — | Use L97. Recording ≠ Asserting is an existing principle; only the new one sits in a recommendation's rationale. |

**Further problems in the changed theory (minor, in the appendix):**
- (a) 1.13.1-3: "property 3 observed for grants, breached for transitions". Property 3 speaks only of grants, so a recorded transition is outside its scope, not a breach. And S0129 L147 evidences property 2 (reference), not "only one role".
- (b) The cross-reference from §5.7 C4 asked for by C17 finding 6 is absent (grep shows no "8.10" in §5.7).
- (c) The CON-0151 register text still says "non-increasing" without "over a fixed population" (C17 finding 8).

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| T-a / T-b as "the two senses" (result 1) | **INVALID** as stated | Trust in a knowledge item, key assurance and sincerity are missing (finding 1). The two senses are VALIDATED as distinct. |
| CON-0160 / T1 | **PROVISIONAL** | The derivation step is valid but definitional. The premises T-b and the root evaluator or prior are missing (findings 2, 3). |
| CON-0161 / T2 | **VALIDATED**: occurrence cannot be established from inside the record (regress, scoped to establishing). **INVALID as stated**: "does not reduce", "dischargeable" | Finding 4. |
| L1 | **VALIDATED** (scoped) | Trust is not an act; it may be a condition of a rule act (finding 6). |
| L2 | **VALIDATED** for the same source; **PROVISIONAL** universally | Finding 9. |
| L3 | **PROVISIONAL** | Definitional on f; source tension (finding 10). |
| L4 | **VALIDATED** as non-determination under some f; **PROVISIONAL** as a law | Finding 8. |
| L5 | **INVALID** as stated | Finding 5. |
| M-T1 | **VALIDATED** | Trivially, from T1's definition. |
| M-T2 | **VALIDATED** as an independence model for T-b | The axiom-vs-primitive classification is PROVISIONAL. |
| C17 changes | **VALIDATED** as answers to findings 1–5, 7–11, 13–17 | Exceptions: finding 12 above; appendix items (a)–(c). |

## 4. Coverage of my step-1 items

| Item | Status |
|---|---|
| Contextual and temporal trust; trust ≠ authority; trust creates no evidence or truth; assessment object; feedback loop; non-transitivity; reliability ≠ trust | COVERED, with L5, L4 and L3 DISTORTED (findings 5, 8, 10) |
| Trust in knowledge items or artifacts (S0973 L996–1014, L1867–1902) | MISSING (finding 1) |
| Anchors: root keys, priors, validator taken as ground truth | Partly covered: priors via "fiat = policy value". The validator appears only as L5 independence, not as a regress. Keys are MISSING. **Not the same as T2.** Keys bear on T-b's authenticity part; priors and validator are T-a's own root. Not a third *sense*, but a third *premise*, analogous to F31 (findings 2, 4). |
| Identity / authenticity / integrity separation; the limits of cryptography | MISSING from §9.9 (finding 4) |
| Senses: weight, relying-party confidence, assessment object → T-a; key assurance → between T-b and anchors; accepted-knowledge status → T-c; sincerity → none | finding 1 |
| Authority as an input to trust (S0973) | Quoted in §9.9.2 but dropped from T1's f, unreported (finding 6) |
| Must-do gap | MISSING (finding 7) |
| Weighted voting; trust score ≠ truth selector (S0913 L564–597) | MISSING (minor; bears on F24) |
| Beta-reliability dropped; compromise and backdating | MISSING (finding 11) |
| Lineage: one line; S1500 assesses it; S0973 re-derives S0913 without citing it | COVERED; the absence of citation is not noted |
| LR arithmetic (19 × 15 = 285; the product needs independence given both H and ¬H) | JUSTIFIABLY EXCLUDED (not a trust law) |

**New forks:**
- the root of T-a (designated evaluator or prior): an act, or an axiom?
- T-c, trust in a knowledge item: derivable, and where?
- may a rule act take trust as its condition (L1's scope)?

## 5. Next steps (dependency order)
1. Add T-c and restate result 1 (finding 1). Done when the central question's "trusted" has its own definition.
2. Add T-b and a root evaluator or prior as premises of T1, with the regress fork (finding 2). Relabel T1 as definitional (finding 3).
3. Split H4 into integrity, authenticity and occurrence. Restate T2 and CON-0161, and cite §8.10 C10 (finding 4).
4. Restate L5 and L1. Fix L4's models and the truster arity. Scope L2 and L3 (findings 5, 6, 8–10).
5. Add the trivial-case test and the must-do candidates (finding 7). Add the boundary cases of finding 11.
6. C17 fixes: finding 12 and appendix (a)–(c).

No question for the Principal is needed before step 4. Where f and the root live stays Req.

## 6. Limits
- I read only S0913, S0973 and S1500 among the corpus sources, plus the cited lines of S0090, S0092–S0096 and S0129. I did not read the other 3 hits (S0630, S1004, S1491), 025y, or the A1–A15 family.
- The L4 and L1 counterexamples are hand constructions, not executed searches. No script was run.
- I did not re-check the quote counts in STEP8-AND-SCAN ("8/8"); they are reported, not shown.

## Appendix (changed theory)
- (a) 1.13.1-3: property 3 is mis-scoped ("breached for transitions"), and "observed for grants" rests on S0129 L147, which shows reference, not role exclusivity.
- (b) §5.7 C4 has no cross-reference to §8.10 C10 (C17 finding 6).
- (c) The CON-0151 definition is not updated to "fixed population; grows on entry" (C17 finding 8).
- (d) 40 inline revision notes remain book-wide (grep). This was acknowledged in the settlement.
- (e) BOOK-OUTLINE's Trust row still reads "not in the book"; update it after the settlement.

