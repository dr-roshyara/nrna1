*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back. Harness time: step 1 227 s (15:03:58Z → hand-back); step 2 sent 15:08:33Z, received at this save.*

# REVIEW REPORT — C11/T3

**Scope of this review:** transition review (steps 2–3) of §8.6, §8.7, §6.5, §5.5 C6, §5.6 C6 and CON-0125…0134, checked against my saved inventory. Independence is process independence only: I share a model family with several of the sources' authors.

## 1. Assessment
- **Proofs.** The formal parts are mostly sound under their own definitions. The §5.5 C6 proof, the §8.7 C3 statement of the Galton–Watson theorem and the §6.5 C5 relocation argument all hold. LR 19·15 = 285 recomputed correctly.
- **Largest risk.** Two things. First, §6.5 makes BC-7 a context boundary, but the book's own §1.14.3 records the ARB ruling that the same machinery is "a plane, not a bounded context". That ruling sits on the kernel/application boundary and is neither cited nor reconciled. Second, three claims are drawn more broadly than their premises allow: differential testing, one-sided independence, and the LR "between" statement.

## 2. Findings (ranked)

| Sev. | Type | Claim | Source / line | Problem | Correction |
|---|---|---|---|---|---|
| **critical** | defect | §6.5 C1/C6, CON-0133 | book §1.14.3 L260 ("a plane, not a bounded context"), §1.14.2 ARCH-INV-1 | The section's home search missed §1.14.2, where "the mechanism records authority, never grants it" is already stated. §6.5 is also silent on the earlier ARB ruling that the machinery is a plane, later reversed by ADR-AIP-03. CON-0133 duplicates ARCH-INV-1, and the C6 Hyp on the kernel's authority primitive rests on an unresolved classification. | Merge CON-0133 into ARCH-INV-1. Record the plane→context change. Hold the C6 Hyp until it is resolved. |
| high | defect | §8.6 C2, CON-0126 | S0164 L1529 | Hidden premise: "both miss" means *identical output*. Real differential testing does flag a shared failure when the two wrong outputs differ. "A defect that both miss never shows up" (§8.6.1) is therefore false outside the binary model. | Define B(c) as common-mode failure (same output). Restrict the plain-language sentence. |
| high | defect | §8.6 C3, CON-0127 | S0058 L77 (anachronistic linkage); S0164 L1998–2005 (commit read as a session), L2129 (false "ALL PRECONDITIONS SATISFIED") | Soundness of detection needs R ⊆ F, i.e. the record is truthful. Recorded factors are themselves declarations (INV-ATTR-2), and the corpus has false ones. | State the truthfulness premise. Refutation is only as reliable as the record. |
| high | defect | §8.6 C4, CON-0128 | §8.6 L60 "Between these two…" | Under dependence the joint LR is not bounded by LR₁ and LR₁·LR₂. Each piece alone LR 1.5 (product 2.25): joint 50 in one distribution, 0.857 in another (python run, this session). The copy case also needs P(E₂\|E₁,¬H) = 1. The lineage rule is a conservative heuristic, not "the general form". | Strike "between". Call the lineage rule a heuristic. |
| high | omission | §8.6 C1, CON-0125 | S0164 L2070–2073 vs S0913 L238 | Homonym: "lineage" is used for *process* lineage (fork, compaction, resume) and for *information* lineage. The book merges them, so process lineage disappears as a factor. The commission/scope-author factor ("independent of the commission — no", L1789) is also missing. | Split the two factors. Add scope authorship (cf. legislator ≠ evaluator, §1.14). |
| high | omission | §8.6 open Q2 | S0164 L2424 | The corpus's own answer to "can independence be attested?" is to refuse INDEPENDENT as an evidence dimension and replace it with REPRODUCIBLE. This is not mentioned. | Add it as the competing option. |
| high | omission | authority primitive (AM-1, §1.13) | S0071 L46, L187, L350 | All 41 grants are AUTHORIZED and no ending was ever recorded. CONTINUATION and FAIL occur 0 times in 117 transitions. Neither AM-1's "authority must be able to end" nor the exit from sticky STOPPED was ever exercised. | Add to §6.5 or §1.13 as evidence. |
| high | omission | §6.5 C2, preserved I-5 | S0071 L11, L237, L354 | The producer lane wrote its own START (an accepted practice), and one `recordedBy: architecture` exists. This is direct evidence of a preserved invariant breached in practice. Also, "2 of 5 transition types" does not match the 8-type vocabulary. | Add both. Query the denominator (possible source defect). |
| high | defect | §6.5 C4, CON-0134 | S0062 L112 (C-2) | "Computing on read cannot drift" assumes a fixed fold function. The interpreter can be swapped by env var (KOS_MECHANISM_PATH), so derived state can change with no change to the log. | State the premise. Cite C-2. |
| high | distortion | §6.5 lineage; §5.6 C6 | S0065 L20; S0164 L1119–1135 | "One human reviewer" is unverified. The log shows the Principal pasting reviews produced by another assistant session. | Mark the reviewer's nature as UNKNOWN. |
| high | omission | §8.7 C4 / §5.6 | S0058 L51 vs S0061 L19; S0064 L5 vs S0065 L16 | Acceptance ignored the recorded caveat that V#2 had no governed lane, and quoted the superseded first-pass verdict. Caveats dropped at acceptance. | Add as a case. |
| normal | defect | §8.7 C1 | S0056 L60, L89 | "No occurrence of the superseded value remains" is contradicted by the source itself: the old "8" is retained in place, by design, as a disclosure. | Use "no occurrence asserted as current". |
| normal | defect | §6.5 C3 Ded | S0058 L63; S0071 L235 | "No mechanism enforces the rule" overreaches. R8, fresh assignments and named HANDOFF do enforce *lane* separation. What is unenforced is binding a process to a lane. | Narrow the statement. |
| normal | defect | §8.7 C2, CON-0129 | — | "Exactly" overclaims. The condition shown is sufficient. Renumbering is also safe when outside references are updatable or bind by anchor, and a dangling reference is detectable. | Say "sufficient". |
| normal | omission | §8.7 C3 | — | At m = 1 the chain ends a.s., but expected total findings are infinite (1/(1−m): 10 at m = 0.9, 100 at 0.99). Findings within a round are correlated through a shared remedy (not i.i.d.). A materiality filter lowers *counted* m and can hide defects. | Add all three. |
| normal | omission | §6.5 | S0071 L282, L293 | The key law ("can refuse a malformed act, never an unauthorized one") is missing, as are the must-do responsibilities (S0067 L73–77). Without them BC-7 passes the trivial-solution test (refuse-everything). | Add both. |
| normal | omission | Fork F4 | S0071 L67 (G-3) | The record gates activation. This bears on "constitute vs represent" and is not linked. | Cross-reference F4. |

## 3. Concept verdicts

| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0125 | PROVISIONAL | Factors overlap and are incomplete; the lineage homonym. Kernel? No: assurance practice. |
| CON-0126 | VALIDATED | Proof correct, but only for the deterministic same-output model; the plain-language overgeneralisation must be removed. |
| CON-0127 | PROVISIONAL | Valid only with a truthful-record premise; the corpus has counterexamples. |
| CON-0128 | PROVISIONAL | Product rule and copy case are standard. The "between" statement is INVALID, and the lineage rule is not derived from it. |
| CON-0129 | VALIDATED | Validated as a sufficient condition; "exactly" removed. Application rule, not kernel. |
| CON-0130 | PROVISIONAL | Theorem stated correctly; its i.i.d. premise is unmet; no m is estimable. |
| CON-0131 | VALIDATED | Proof sound where the check is the only enforcement point and the caller supplies the scope. Instance: one line (S0058, S0067, S0071 re-measures). |
| CON-0132 | PROVISIONAL | "Reason lives in" is not operational, and a breach is omitted. A good candidate for boundary question 2. |
| CON-0133 | PROVISIONAL | Duplicates ARCH-INV-1; conflicts with §1.14.3 (critical finding). |
| CON-0134 | PROVISIONAL | Needs a fixed fold function; not new (§1.14.1 L207). |

**Claims without a CON row:** §8.6 C5 PROVISIONAL (one case, assumption only). §8.6 C6 VALIDATED (an instance of A1). §8.7 C4 and C5 VALIDATED (instances). §6.5 C5 VALIDATED (relocation argument). §5.6 C6 tension (an annotation after a pinned blob) VALIDATED.

**Entries 45 and 46:** applied correctly. 17/39 is given as a count only, and S0913's PASS results are flagged as unexecuted.

## 4. Coverage
- **Covered:** model vs process separation, wrong pen, move-not-renumber, evidence acceptance ≠ artifact acceptance, the owns/preserves distinction, the defect-vs-boundary re-reading, the negative-invariant split.
- **Ranked gaps:** the plane-vs-context contradiction > unexercised authority endings > the I-5 breach in practice > REPRODUCIBLE as a substitute for independence > dropped acceptance caveats > the must-do of BC-7.
- **Justifiably excluded:** most of S0913 (trust, conflict taxonomy) is outside the commissioned topic.
- **New fork:** is the authority-record machinery a kernel plane or an application context?
- **Source defects:** S0067 L65 "2 of 5" denominator; I-9 missing in S0067/S0071; S0065 L16 cites the superseded first-pass verdict.

## 5. Next steps (in dependency order)
1. Reconcile §6.5 with §1.14.2–1.14.3 and merge CON-0133. *Done when* the plane/context history is recorded and the fork is entered in the map.
2. Fix the premises of CON-0126/0127/0128. *Done when* each is restated with its premise and the counterexamples are cited.
3. Split the factors in CON-0125. *Done when* the factor list is stated and tested for orthogonality.
4. Add the S0071 census evidence to §1.13 (AM-1) and to §6.5 C2.
5. Add the §8.7 C3 caveats.

**Question for the Principal:** were the "Principal DDD Architect" reviews (S0064) human or AI? The answer decides whether S0065's acceptance rests on any human review.

## 6. Limits
- S0164 was read in targeted sections only (as in step 1).
- Of the dependent sections I did not read §1.6, §1.10, §1.16.8, §4.1, §4.3, §6.2–6.4, §8.4 or §9.3. Of §9.4 I read only C7, and of §8.5 only C2.
- book_search.py was not run; lexical grep only.
- About 40 quote anchors were spot-checked by hand, and all matched.

**Running measure:** 17 findings (1 critical, 10 high, 6 normal); 7 material omissions.

---
## Appendix
- §8.6 C1's counter-observation (the same-model verifier found the defects) is valid and well placed.
- §5.5 C6's trivial case (one subject) is correct. A hidden premise is that the caller chooses x. It is harmless, but should be stated.
- S0164 L834/L911 carry external web citations (ijaibdcms, network-ai): one assistant's restatement, rightly counted as one source.
- §6.5 C8 (enforced in the record, held by restraint in the repository) is a good refinement of CON-0102.
- LR counterexample, computed with python3 in this session: H = {11: .5, 10: .1, 01: .1, 00: .3}, ¬H = {11: .01, 10: .39, 01: .39, 00: .21} → LR₁ = LR₂ = 1.5, joint 50. Second distribution gives joint 0.857.

