*Reviewer's steps 2–3 report, window C18, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — WINDOW C18

Independence here is process independence only: I am another AI session working under the same operator.

## 1 · Assessment
- The two-sided reasoning that runs through the window is not yet reliable as written. Most of §8.11 holds, but its lead concept, CON-0157 ("both directions"), does not survive the sources: no direction has a confirmed instance.
- The largest risk is spread: C1's misreading has already been carried into §1.14.3-2's further evidence, in a Ch. 1 section that has just been integrated.
- The §1.14 integration keeps the meaning of 38 of the 47 claims. Of the other nine, four have major problems (a head upgrade, and evidence integrated one-sided or distorted) and five minor ones.

## 2 · Findings (ranked)

| # | Sev | Type | Claim | Source | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | defect | §8.11 C1 / CON-0157 / THEORY-MAP L206 | S0100 L128; S0099 L57 vs L76–80; S0099 L46 | (a) C1 quotes the "first … FALSE-BAR" instance but drops the erratum's own verdict on it: "true as a class, **wrong in its instance**". So the false-bar direction has no instance. (b) The "second instance" (a false non-bar) assumes an answer to the very question C2 leaves open. (c) Every self-declaration was **true**: the refusal's "2da45a86" and CORRECTION 1's "4858c37c" alike. What failed was **attribution of an act in inherited context**, not self-declared identity. The same error sits in C1's Int ("identifiers failed") and in the plain-language summary ("did the barred work"). | CON-0157 is invalid as stated, and the error has spread to §1.14.3-2 | Restate: "a compaction continuation attributed an act in its inherited context to itself; on that basis it 'corrected' a true declaration and treated a bar as inapplicable to itself. Whether that was a false clear depends on C2. One case." |
| 2 | major | defect | §1.14.3-2 further evidence | S0099 L46 | Says "after misreading its own identity", but the process measured its own identity correctly. "Verified the barred work" assumes C2's answer. "Cannot check" generalises from one case. A clearer in-window breach is not used (#5). | Wrong meaning integrated into Ch. 1 | Rewrite once #1 is fixed, and use #5 |
| 3 | major | defect | §8.11 C2 Int | S0099 L78 | "C1 refutes the identifier option" and "authorship is not enough" both assume that eligibility should follow working context, which is exactly what C2 asks. The "in meaning" quote of L78 also drops the source's own argument (INV-ATTR-1 "cuts against settling this by sessionId"). | F32 is pre-judged | Say instead: C1 shows that identifiers do not track working context. Whether eligibility should follow working context stays open. |
| 4 | major | omission | Invariant with no enforcer | S0097 L41; S0099 L78; S0100 L22, L78 | "No gate reads process identity", yet the bar is written by name. It is enforced only by the barred process's own judgement, plus a human START choice that sees only self-declarations. 4858c37c acted on the estate "never declared in any record". C4 says that declining "keeps H4 true", but omits that nothing else would. Under the trivial case (refuse everything), the model's constraints hold while no one is obliged to act. | The bar invariant has no owner (§9.8 H4/M5) | Add to C4; cross-reference §9.8 M5 |
| 5 | major | omission | Verifier produced the fix for its own findings; C5 is time-indexed | S0100 L130–135; S0099 L24, L90 | 4858c37c wrote F-1…F-7 (ca6039a8) and then produced Correction #3 (c3839624, with "seq-21 producer mismatch"). This is a counter-instance to S0097 L67, which C6 quotes. C5's "Correction #3 has not been produced" was true at 13:15 and overtaken about 10 minutes later (git: c3839624 at 13:25 local, checked by me). | C6 is one-sided; C5 is unscoped in time | Add to C6 and C5; cite CON-0156 (superseded, not disproven) |
| 6 | major | distortion | §8.11 C7: "the erratum restored it" | S0100 L127 vs L140; S0099 L59 | The source says the argument "stands" (it is true, it follows) but "I … restore nothing": standing is a Governance act. The book merges truth with standing, the very distinction §1.14 rests on. "Stand(s)" is used as a homonym. | Breach of the central distinction | Record the chain: claim → withdrawal → withdrawal of the withdrawal, with no act deciding standing |
| 7 | major | defect | CON-0159 meaning; C6 Int; 1.14.5-1 further evidence | S0097 L47; 1.14.5-3 | The source says the analysis is "out of scope", not that it is a decision. "Manufactures a decision as such a gate does" conflicts with 1.14.5-3 (recommending has no normative effect). A gate blocks without any act; an analysis takes effect only through an unregistered adoption (1.14.5-13(a)). In 1.14.5-1 the claim also appears without the Int label. | An Int stated as the concept's meaning | Restate; link 1.14.5-13(a) |
| 8 | major | defect | 1.14.1-7 head and argument | its status box, defect 1; §9.7 L129 | The new head says "is well-defined as a least fixed point". But defect 1 says a least fixed point need not exist and that Knaster–Tarski is the wrong theorem, and §9.7 finds the model "not yet a function for any θ". The body still asserts "exists and is unique" beside the box that retracts it, so the argument was not rewritten. | Silent upgrade | Head: "well-definedness sketched; not established". Make the body conditional. Note that the box's "in these cases" is narrower than §9.7. |
| 9 | major | omission (two-sidedness) | 1.14.2-2 further evidence (S0129 L147) | S0129 L147; S0100 L22; S0097 L41 | Placed under the resolver claim although it bears on 1.14.2-1. Supporting only, although C17 made the same evidence two-sided in 1.13.1-3. The engine checks only that a humanActRef is present. A START by a barred process would have been recorded. | A core invariant reads stronger than its evidence | Move to 1.14.2-1; add the challenge |
| 10 | major | omission | Identifiers unstable when a session resumes (my item 0.1) | S0100 L68 vs S0099 L44; S0099 L44, L111 vs S0100 L142 | CORRECTION 1 says 2da45a86 "ended 08:49:17Z", but its records continue from 11:10:52Z. 4858c37c's transcript "ends 10:42:21Z", yet it declares authorship of the erratum at about 11:28Z. A same id is not the same episode. C1's "How it was settled" covers the refusal and CORRECTION 1, not the erratum. | §8.6's "resume" factor goes without evidence; C1 closes too much | Add to C1 and §8.6 |
| 11 | major | omission (Ded) | F32 lineage option; C3 scope | S0099 L26 | Anyone verifying Correction #3 must read F-1…F-7. If eligibility followed **information** lineage, the pool for every uptake verification would be empty. The option has to be limited to inherited **working context**, as §8.6.2 already separates. C3 also needs a direct-vs-transitive scope. Counterexample to C3 as a law: a referee checking that their own comments were taken up. | The option, read naively, defeats itself | State both scopes; mark C3 as estate policy (Hyp) |

## 3 · Concept verdicts

| Concept | Verdict | Reason (scope) |
|---|---|---|
| CON-0157 | **INVALID** as stated | #1. Restated form (act misattribution through inherited context, one case): PROVISIONAL |
| CON-0158 | PROVISIONAL | Two instances on one line; scope (#11); a policy, not shown to be a law |
| CON-0159 | **INVALID** as stated | "Manufactures a decision" (#7). The scope-check half: PROVISIONAL |
| §8.11 C2 question | OPEN (correctly) | Its Int is INVALID as stated (#3) |
| C4 | PROVISIONAL | One case; no enforcer (#4) |
| C5 | VALIDATED, scoped | Existence and registration are necessary conditions, at that time (#5) |
| C7, C8 | PROVISIONAL | C7 after #6 is fixed |
| §1.14 integration | Not passed | #2, #7, #8, #9; the rest preserved |

## 4 · Coverage of my step-1 items

| Item | Mark |
|---|---|
| 0.1 resume under the same id | MISSING (#10); §8.6.2 names "resume" without evidence |
| 0.2 bidirectional failure | DISTORTED (#1) |
| 0.3 no self-ruling | COVERED (S0099 L80); S0100 L138 not cited |
| 0.4 invariant with no enforcer | MISSING (#4); partly covered by §9.8 M5 |
| 0.5 truth vs standing | DISTORTED (#6) |
| 0.6 conduct vs premise (S0100 L88) | MISSING (appendix) |
| 0.7 uptake bar | COVERED (C3) |
| 0.8 verifier supplies the replacement | MISSING (#5) |
| 0.9 supply / single operator | COVERED (C7; 1.14.3-3) |
| 0.10 time-indexed claims | MISSING (#5) |
| S0099 L57 vs L76–80 | MISSING; the book repeats the inconsistency (#1) |

New fork: F32's lineage option needs a split between working context and information lineage (#11). Source defects: S0099 L44 (stray character); S0099 L94 calls commit timing "mechanical" when it is only corroborating.

## 5 · Next steps (in dependency order)
1. Restate C1 and CON-0157 and fix THEORY-MAP L206 (#1). Done when no sentence says "both directions" or "self-declared identity failed".
2. Rewrite §1.14.3-2's further evidence (#2, using #5).
3. Fix C2's Int and add the scope of the lineage option (#3, #11).
4. Add the enforcer gap to C4 (#4) and the challenge to 1.14.2-1 (#9).
5. Fix C7's wording (#6) and restate CON-0159 and 1.14.5-1 (#7).
6. Fix 1.14.1-7's head and body (#8).
7. Add the resume evidence to C1 and §8.6 (#10).

No question for the Principal beyond C2, which is already listed.

## 6 · Limits
- Read: §8.11; STEP8-AND-SCAN; HEADS-OLD-NEW; the full word-diff of old vs new §1.14 (`git diff --no-index --word-diff`, 228 lines); the 01.99 change-log rows; register rows CON-0155–0159, 0125, 0144, 0151; excerpts of §9.7, §9.8, §8.6, §8.9; S0096 L31; S0128 L48–49; S0129 L147.
- Not read: the rest of the book, RESEARCH-PURPOSE.md, the C17 changes, and the cited commits' contents. "Six separations" (S0096) is not checked.
- Transcript measurements are reported by the sources, not shown to me.
- Running measure: 19 findings (11 above, 8 in the appendix); 1 critical; 5 material omissions.

## Appendix (minor)
12. 1.14.1-4: the head should name the premise; put "given the premise below, not by the data alone" next to "would fail". Nothing from the "Corrected" box was lost: the formula and "refuted given the stated premise" are in 1.14.7, and the premise paragraph is kept.
13. Status box: "baseline v1 (2026-10-18)" and the review identity were lost. The register pointer survives only in the change log; add a pointer to it.
14. 1.14.5-14: removing the tag broke the sentence ("freeze is (not verifiable …)"). All four INVALID concepts remain in 1.14.7; only that local signal was lost.
15. "My answer reads it" remains (L265), although the change log says "my" was made impersonal.
16. Heads that dropped a condition: 1.14.4-10 (PM-1's split, undecided); 1.14.5-14 (S0017's claim and its limit to freezes of activity); 1.14.5-8 (the conditional); 1.14.3-3 ("unless a different person is evidenced"). The header's lineage sentence lost its Int label.
17. C1's "not by identifiers": the transcripts are keyed by sessionId, and the timing evidence only corroborates.
18. C8 omits two things from S0098. L337 names the identity/provenance gap at about the time the identity episode began, possibly the window's only independent line. Its state machine mixes states with transitions, against S0097 L5/L75, and it gives ACCEPT to Governance rather than the human.
19. Also missing: S0097 L21 (existence is not correctness); S0100 L88 (conduct correct on its premise); S0097 L53 (state decomposition: delivered, applied, verified).
