*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back (window C10; one step-2 message).*

REVIEW C10 (S0051–S0055), STEPS 2–3: transition review report
Tool clock: first `date -u` = Mon Oct 19 14:14:38 UTC 2026, before reading anything · last `date -u` = Mon Oct 19 14:19:02 UTC 2026 · 4 min 24 s.
Independence: process independence only (another session of the same model family).
I re-read the saved STEP1-INVENTORY.md first. I wrote no files.

## 1. Assessment
- §8.5 and §5.6 describe the sources faithfully, and C1, C2, C7 and C8 hold within their scope.
- The largest risk is that two of the four new concepts are stated more widely than the evidence allows. CON-0122 rests on a miscounted, non-random sample. CON-0124 is stated as a law of all acts, and grants are a counterexample.
- §5.6 also takes the source's "correct conclusion" at face value, although the premise behind it was chosen, not forced (F19).

## 2. Findings (ranked)

| # | Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | §8.5 C3, CON-0122: "11 of 12 → CI 0.62–1.00; up to 38 % wrong" | S0055 L7, L57–73 | (a) The S0055 table has 13 data rows with two failures (D-1, D-2), so 11/13 (CP [0.546, 0.981]; Wilson [0.578, 0.957]); "11 of 12" is a source slip the book relies on. (b) The sample is not random: the two failures were the producer's disclosed leads, picked *because* they were known to fail. The other 11 were picked as load-bearing, and all held (11/11, CP lower bound 0.715). No binomial interval estimates the error rate of the document's OBSERVED claims. | The numeric part is wrong, and the reading "up to 38 % wrong" has no basis. | Remove the interval. State: "purposive sample; 2 failures were pre-disclosed; no rate is estimable". List "11 of 12 vs 13 rows" as a source defect. |
| 2 | high | defect | §5.6 C3, CON-0124: "the object of an act must exist and be fixed when the act is performed" | S0052 L74, L66; book §1.13.1 | Counterexamples inside the book and the window: a grant permits *future* activity (§1.13.1); Q-C1 approved a Stage-2 assignment that does not exist yet; Q-B1's conditional approval covers text not yet written. Also, the second "case" (S0055 L112) is the scope of a verifier's report, not an act. | As a kernel law of acts it contradicts the authority model. "Conditional or covers nothing" makes the claim close to a tautology. | Restrict it to acceptance or approval *of a deliverable*. Home: §1.16.6 (pinning; S0053 pins commits). Evidence: one case. |
| 3 | high | omission | §5.6 C1, CON-0123: what kind of premise made the argument sound | S0051 L77, L118; S0052 L81; map F19 | The repair's key premise (uniqueness) was supplied by a Human *interpretive* act that is framed as "not a new … invariant". F19 (review C9) shows the premise was not forced: an aggregate would also make the outcome definite. So the "correct conclusion" is correct only relative to a chosen premise, and an interpretive act can work as an amendment that is not recorded as one. "Invariant" is a homonym here: a logical constraint of the model vs a governed item with standing. | CON-0123's example is weaker than stated. A new act kind ("interpretation") is missing from §5.6. | Add a claim on interpretive acts, cite F19 and §1.16.8 (stipulation declared as such), and qualify "correct". Open fork: is an interpretation an act that changes what is in force? |
| 4 | high | omission | Engine invariant gaps verified in code | S0055 L75 | Four gaps, upgraded from Declared to code-verified: (1) two ACTIVE sessions are possible; (2) a second START transfers ownership unguarded; (3) there is no CLOSE vocabulary; (4) `recordedBy` is an unvalidated free string. A book search found nothing on them. | The residual trust assumption (§1.12) and the invariant owners (§1.16.7, CON-0102) lack direct, observed evidence. Gap (4) is the recorder-identity gap of §5.5 C2. | Add to §1.13.2 or §5.5 C2 as "stated, not held" evidence. |
| 5 | high | defect | Generalisation rule (entry 45) applied incompletely | STEP8 L152–156 | Law-form statements not marked Hyp: CON-0122's register meaning ("every claim of that grade"; the section says "in the document"); §8.5 C6 ("a declaration, whatever its grade"); §5.6 C2 ("whether the condition is met is a judgement", which is false for conditions a machine can decide); §5.6 C4 ("would be read as approving the levels below"); §5.6 C5 ("producer ≠ disposer", from one Governance remark). | Unsupported generalisations. | Mark each Hyp and state its case, or scope it. Align the register with the section. |
| 6 | normal | omission | §8.5 C2: independence of method vs of role | S0054 L14, L73; S0055 L47 | The window contains the comparison: the producer, using a different method, missed V-A; the verifier, with a different role and a different method, found it. That is one case of evidence that method-independence alone is not sufficient. Also, §1.16.3 (verifying by re-running the same method) is *reproduction*; C2 is *replication*. Neither section states this distinction. The "eyeballed" hook count is an execution slip that a careful same-method re-run would catch; D-2 (re-read repeatedly, never seen) is the better example. | C2's Ded is right, but its example is weak. | Add the distinction and the case; swap the example. |
| 7 | normal | omission | Trivial case of the S0051 must-never rules | S0051 L59, L63–64 | The assurance model's default state (no establishment; every outcome "weakest", i.e. Declared) satisfies INV-ATTR-1, the closed gate inputs and the derivation. R-6 names who establishes, but nothing obliges that establishment ever happens. | §9.4.3's open question gets a concrete answer: this model has no must-do. | Note it in §9.4 (C1/C4), with the must-do for the assurance model left open. |
| 8 | normal | omission | Existing homes missed | §1.17.2 L63–67; §1.16.8 RC-22 | CON-0123 is closest to §1.17.2 (valid ≠ well-founded; an ill-founded act keeps its standing). CON-0122 is closest to RC-22 ("falsified is worse than unevidenced"). The meaning search ranks §1.17.2 at 0.46. | Novelty is overstated. | Cite both; restate CON-0123 as "the object of an act may include its argument", a refinement of §1.17.2. |
| 9 | normal | defect | §8.5 C3 Int: "an acknowledged unknown lowers nothing else" | S0055 L89 | The verifier also checked for *false unknowns*: the Unknown grade has its own calibration. S0055 L77 separates a wrong measurement from a declaration disguised as an observation, which are two different ways a grade can be wrong. | The asymmetry is overstated. | Scope it; add the two-class distinction. |
| 10 | normal | defect | §8.5 C6 | S0055 L50 | "Declaration" merges what the observer saw (OBSERVED for that observer) with what others can re-check later (afterwards it is testimony only). | Two meanings merged. | "Unverifiable by third parties after the fact", not "a declaration". |

## 3. Concept verdicts

| Concept | Verdict and scope | Kernel/app · minimal · stable |
|---|---|---|
| CON-0121 | **VALIDATED** as the practice of one line of support (S0054→S0055). The Ded holds as a possibility claim: a second method can catch the systematic errors that repeats of the first share. Generality stays Hyp. | Application (verification method). Shown independent of producer ≠ verifier: S0054 is method-independent but not role-independent; a same-method re-run by a verifier is the opposite case. Stable. Bears on trust. |
| CON-0122 | **INVALID as stated** (finding 1; the register scope differs from the section). Core "a wrong high grade damages reliance on that grade": PROVISIONAL. | Application/meta (calibration of a grading practice). Not minimal: §1.3 reliance plus updating. Stable. |
| CON-0123 | **PROVISIONAL.** The distinction is sound by definition, but the source *joined* conclusion and argument in the approval, and the home §1.17.2 is missed (findings 3, 8). | Kernel-relevant (the object of an act; F13a). Largely derivable from §1.17.2 plus "an act states its object". |
| CON-0124 | **INVALID as stated** for acts in general (finding 2). Core restricted to acceptance of a pinned deliverable: PROVISIONAL, one case. Needs a must-do: a held act is applied once its object exists, or returned to its author (S0052 L87; compare §5.5 C3). | Kernel candidate only in the restricted form. Derivable from §1.16.6. Stable. |

## 4. Coverage
- **COVERED:** declared, unattestable independence (§1.14.3, §5.5 C2); the assessor regress (§9.4 C6); self-classification excluded (§9.4 C5); conditional approval landing by a second act (§5.6 C2); levels kept apart (§5.6 C4); snapshot vs drift (§8.5 C6); supersession placement (C8); conformance-only verification (C7, which could also cite S0051 L7, L39: the verifier says it works from "the review's own disposition").
- **MISSING:** findings 3, 4, 6, 7, 9. Also:
  - the resolver vs the fold (S0054 L91): two views of one state disagree, and a precedence rule follows (§1.16.3, projections);
  - no boundary inferred from a name alone, and an evidence hierarchy whose levels' disagreements are recorded, not settled (S0055 L80–83);
  - a count is evidence only with its method (V-D; CON-0070).
- **DISTORTED:** §5.6 C1 (finding 3); §5.6 C3 (finding 2).
- **JUSTIFIABLY EXCLUDED:** the 13-event taxonomy; the Stage-2 gating; "an erratum strengthens credibility" (evaluative).
- **New fork:** *the interpretive act*. Does "interpreting" an approved text change what is in force, and must it be recorded as an amendment?
- **New topic:** the approved composite in S0053 (rev 1–3 "as repaired") inherits V-1's ambiguity about rev 2 §7. An enumerated scope (§5.6 C4) is only as checkable as the supersession accounting of the documents it lists.
- **Source defects:** S0055 "11 of 12" against 13 rows and 2 failures (L7, L57–73; the book relies on it). S0051 L108 "Erratta" (trivial).

## 5. Next steps (in dependency order)
1. Fix C3/CON-0122 (finding 1). Done when no interval is left and the source defect is recorded.
2. Restrict CON-0124 (finding 2). Done when grants, Q-C1 and Q-B1 are tested against the restricted form.
3. Add the interpretive-act claim to §5.6 and link it to F19 (finding 3). Done when "correct conclusion" is qualified.
4. Apply entry 45 to the five items in finding 5. Done when the register and the sections agree.
5. Add the S0055 L75 gaps (finding 4) to §1.13.2 or §5.5.
6. Then findings 6–10 and the missing items above.

## 6. Limits
- I did not open S0049/S0050 or the C9 settlement. I took S0049's identity from the §9.4 references.
- I read the theory map only to L249 (the whole file). I did not read §1.14.1 in full, §1.16.6, or Ch. 0.
- The searches were lexical and meaning-based: a miss is not absence. The intervals were computed with scipy and are shown in this session only.

---
Appendix (lower priority)
- §8.5 C5's home in §8.3 (cases vs counterfactual) is weak. Materiality relative to reliance (S0055 L103: "the classification currency is what acceptance relies on") is the better gloss.
- §8.5 C4's Hyp is fine. V-A was found by a verifier told to look for that shape, so "more search finds more" competes with "the class predicts its siblings".
- Running measure: 10 findings · 0 critical · 5 high · 5 material omissions (3, 4, 6, 7, 9).
