*Reviewer's steps 2–3 report, window C17, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — WINDOW C17

*A transition review of §8.10, the §1.13 integration (step 2), STEP8-AND-SCAN, CON-0153–0156, and the changed theory of the TT3 settlement (1a8c79bd3) and the C16 settlement (fc8d9071f). My step-1 inventory is the basis.*

*What I read:*
- *the five sources and the book files named in the commission;*
- *the cited lines S0129 L42–48, L92–102, L143–150, L156–159, L187; S0090 L26–33; S0089 L77–84;*
- *the git diffs of both commits, and the TT3 reports A and B.*

*Independence here is process independence, not personal independence: the same human operator stands behind the sources, the book and this commission. The report runs past about 1,200 words because there are four objects of review.*

## 1. Assessment

- **What holds:**
  - §8.10's source extraction is accurate. The quotes I checked match.
  - C1 (adoption is not evidence), C2's core (superseded, not disproven), C6 and C7 hold within their scope.
  - The §1.13 integration preserves meaning for 8 of its 10 claims and is in book form.
  - The TT3 rewrite answers its findings.
- **What does not hold:**
  - C3 maps capability existence onto the book's necessity test, and category onto kernel placement. Both mappings are wrong.
  - C5 / CON-0155 overstates what one self-declared provenance case shows.
  - Two §1.13 integrations use only the half of the evidence that points one way.
- **Largest risk:** the window's main contradiction is not in the book. Was the human START of Verification #3 given before the report, or still pending at 09:05? It undercuts C2's example, C9, and the new evidence in 1.13.1-2.
- **Counts:** 17 findings: 0 critical, 8 major, 9 minor.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Source/line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | major | defect | C3 Int: S0096's split "is the separation the book's boundary test already makes"; open question 1, "existence (necessity) … category (placement)" | §8.10 C3, §8.10.3; RESEARCH-PURPOSE L50–54; §5.7 C4 | (a) Existence (is there an instance in the estate?) is empirical; necessity (the removal test) is counterfactual. C-5 is "absent" (S0092 L309) yet may be necessary, and "not yet built" is not "unnecessary". (b) S0096's categories (bounded context, stewardship, control-plane) are DDD placements *inside the platform*, not kernel vs application. A control-plane function can sit on either side. (c) This contradicts §5.7 C4's caution that such orders are "compatible with, not identical to" the boundary questions. (d) Category is posed only "if it exists" [S0096 L18], so "do not derive one from the other" holds in one direction only. | A distorted reading of the purpose's central test enters the book. | Restate C3's Int: an analogous rule at the DDD level (a failed placement test is not evidence of non-existence), not the boundary test itself. Keep CONTESTED and NOT YET ESTABLISHED as a Hyp about the epistemic status of boundary answers. Fix the open question. |
| 2 | major | defect | C5 / CON-0155: authorship shown "by provenance, not by identifier"; "two routes"; "decided by the relation to the object"; evidence for F32's relational option | S0092 L66–69, L85, L90, L13–15 | (a) Route B is the producer's own written disclosure, a self-declaration of exactly the class INV-ATTR-2 says cannot be attested. So there is one provenance route, not two. (b) Route A assumes the transcripts are intact, and the verifier's mapping of itself to "its" transcript is self-declared [L13–15]. (c) The case did not do without identifiers: provenance mapped the artifact to `2da45a86`, an identifier already on the bar, and the exclusion "collapses into an exclusion the record already names" [L83]. (d) Not reported: S0092 labels the inference at L85 OBSERVED and calls the clearance "durable … should not be re-litigated" [L90]. | It overstates F32 evidence and imports the source's overreach. | Restate: "an artifact's author traced to a declared identifier through the record of the write act, plus the producer's self-disclosure; it assumes transcript integrity; one case". The case shows an identifier bar becoming enforceable through provenance, not eligibility without identifiers. Note the L85 and L90 overreach. |
| 3 | major | omission | Was the human START of Verification #3 given before the report? | S0092 L35; S0093 L77–79 (09:05); S0094 L104 (09:13); S0095 L210 | S0092 says the act "did occur" before the report (around 00:42 local). Later, at 09:05 and 09:13, two registrations record the lane as "not started" and ask for a START that would carry the verdict-G reconciliation. The PO/ARB's affirmation [S0095 L210] is undated. So either the re-scoping (AMD2) never governed the work it re-scopes, or the START came after the report. | It bears on C2 (the re-scope targets a verification already finished), C9 bullet 1, and 1.13.1-2. | Add it to §8.10 as a recorded contradiction. Note that the act's time rests on the authority's later word, not on the record. |
| 4 | major | defect (integration) | 1.13.1-2 further evidence: "The estate thus treats the act as having occurred before its record, which is C1-b's reading" | S0095 L141, L149, L210, L216 | (a) The evidence is one-sided: the dropped head phrase "the sources point both ways" was the balance cue. S0095 L149 first decided to keep the report "OFF-RECORD", with "that absence … part of the governed record", a handling compatible with C1-a. Finding 3 shows the act's timing is contested. (b) START activates a lane under a grant (`G-KOS-AIP04-VERIFY3`) that is already authorized; it is not itself a grant. The case bears on act vs record (§1.12) more than on whether a grant *is* authority. (c) "The estate thus treats…" is an Int with no label. | Not a status change, but it tilts a decision reserved for the Principal. | Label it Int. Add L149 and finding 3. State that the case concerns a lifecycle act, not a grant. Restore "the sources point both ways". |
| 5 | major | defect (integration) | 1.13.1-3: S0090 L30 "supporting" | S0090 L30; §8.9 C9; 1.12-2 | In the same line, a verifier "transcribed the PO/ARB's START act into the workflow record myself". The book uses that line in §8.9 C9 as a breach of the roles law and in 1.12-2 as "a record written by someone other than the decider". Against property 3 ("only one role may register grants") it is a challenge for transitions, though not for grants. | Integration was selective, against the rule "supports **or challenges**". | Add the challenge to 1.13.1-3 and scope property 3 to grants. |
| 6 | major | omission | The DDD direction chain | S0093 L43; S0095 L99, L81, L235; §5.7 C4 (S0080 L28–33) | S0093 repeats S0080's order (ownership before bounded context / stewardship). S0095 L99 gives context/stewardship *before* ownership and says it "still binds". S0095 itself calls the sequence "sixteen-step" (L81) and "eleven-stage" (L235). | §5.7 C4 rests on one order, and the corpus changed it silently on the same day. This bears on C3. | Record it in §8.10 as a contradiction, with a cross-reference from §5.7 C4. |
| 7 | major | omission | S0095 outside history | S0095 L13–18, L183–196, L71, L83, L116–120, L179 | "Checked against §9.8 and §1.12 only" does not justify the exclusion: TT3 was a history topic. Missing: (a) a **reachable stuck state**: mutationOwner None, with Inv C and G-3 together, so no command can leave it without an external fact. This is the must-do / liveness question for the workflow model. (b) Decision vs recommendation transcribed as a decision [L71]. (c) Separability of the disposition [L179]. (d) Routing a lane by the content of the work [L83]. (e) Unregistered external content carried in "on its merits" [L120]. | A real counterexample on liveness is missing from the book. | Add a C10 to §8.10, or move (a) to §5.7 or §9.6 as a liveness case. |
| 8 | major | defect (changed theory, C16) | §8.9 C2: "E(o) is **non-increasing** as work proceeds" | §8.9 C2 restated Ded; S0092 L13–29 | The same paragraph says the population is not closed (a new session is a new identifier). S0092 is the case: a new process `1db2b4cc` entered eligible and cleared SB-1, so E(o) grew. | The restated Ded is internally inconsistent. | "Non-increasing over a fixed population of processes; it grows when eligible processes enter." Cite S0092. |
| 9 | minor | source defect relied on | C9: "third external input … whose artifact is absent from the estate" | S0096 L64 vs S0092 L153, S0093 L55 | The 925-line document is present but untracked, and §8.10 C1 itself treats it as such. | The count "third" is wrong as stated. | Note it as a source slip: "two absent, one untracked". |
| 10 | minor | defect | C2 / CON-0156 Int, "Hist at (t, s), t = s" | §9.8.2 | The restated Hist has five arguments (u), and CON-0148 is INVALID as stated. The homonym is not reported: S0093 L63 calls the thesis "FALSE", S0094 L77 says "superseded, not disproven". | — | Use Hist(c, x, t, s, u) with u = s, and report the homonym. |
| 11 | minor | defect (integration) | 1.13.2-3 point 5: new sentence, "§9.8 … a truthful history needs every change to the record to be itself an entry" | §9.8.1, §9.8.3 | Every change being an entry is H1′, a condition for computing state history. In §9.8, truthful history adds H4 and H5. This is a new, inexact claim inside a step-2 integration, and the point's "Ded" label was dropped. | — | Cite §9.8 exactly (H1′ with H5; result 6), or remove the sentence. Restore "Ded". |
| 12 | minor | defect (changed theory, TT3) | §9.8 M5 cites "Recording ≠ Asserting" at S0129 L147 | S0129 L102 | The phrase is at L102. It, and "History ≠ Reconstruction Guess", sit in a rationale that is "still RECOMMENDATION, not DECIDED" [S0129 L96]. §9.8 result 6 and §1.13.2-3 say "S0129 records/states the principle" without that status. | — | Correct the anchor and add the status. |
| 13 | minor | form | Inline revision notes in theory prose | §8.9 L28, 35, 49, 62, 74; §5.7 L29 | "restated after review C16", "added after review C16", "scoped at the TT3 settlement" break the labels rule ("No revision numbers or correction notes appear in the theory text"). | — | Move them to the change logs. |
| 14 | minor | omission | The next step in C-14's status | S0096 L41–45 | §8.9 C4 ends at CONTESTED. S0096 records the analysis's categorical "no C-14", replaced by provisional wording, and the open question whether the CAP-09 instance "constitutes the same capability". No identity criterion for capabilities exists. | — | Add to §8.10, with a cross-reference to §8.9 C4. |
| 15 | minor | omission | Where the record lives | git: S0092 first committed 2026-08-28 (f24edf316) | "On-record by transcription" [S0095 L235] refers to the workflow record. The report itself was untracked for nine days. S0093–S0096 declare no producing process, the defect S0094 L41 calls material. | It bears on H1′ (which record?) and on C5 ("one producer declared none"). | Add to §8.10's lineage note. |
| 16 | minor | defect | C4 Int: "It is not standing conferred by an act (§1.14)" | S0096 L37 | Unsupported: S0096 speaks of delivery, compliance and other *authority* kinds. Seven context states [S0096 L34] vs a "nine-stage" lifecycle [S0095 L110] are not reconciled. | — | Mark it Hyp, or remove it. Note the two stage counts. |
| 17 | minor | source defect | "seven exclusions" | S0094 L19, L41 | Only three processes and one unnamed identifier are listed. The theory does not rely on it. | — | List it only. |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0153 existence vs category | **VALIDATED** (distinction) / C3 Int **INVALID as stated** | A failed category test is no evidence of non-existence (one source; category presupposes existence). The mapping to the necessity and placement tests fails (finding 1). |
| CON-0154 authoritative for which claim | **PROVISIONAL** | Plausible relation reading, one source; the "not standing" clause is unsupported. |
| CON-0155 authorship by provenance | **INVALID as stated** | One provenance route plus a self-declaration; identifiers still used; transcript integrity assumed (finding 2). The restated form is PROVISIONAL. |
| CON-0156 superseded, not disproven | **VALIDATED** (scoped) | An indexical finding ("currently") evaluated at its time is not falsified by a later act. The Int through Hist is PROVISIONAL. |
| C1 adoption is not evidence | **VALIDATED** (Src) | "Exactly what it names" is PROVISIONAL: the model's content is defined only by names plus an unadopted document. |
| C6, C7 (Src) | **VALIDATED** | C7's rule is a Hyp, with no named invariant owner. |
| C8, C9 | **PROVISIONAL** | C9 bullet 1 needs finding 3. |
| §1.13 integration | meaning kept: 1.13.1-1, 2-1, 2-2, 2-3 (except point 5), 3-1 … 3-4. Fix: 1.13.1-2, 1.13.1-3 | Book form yes: 0 inline tags; the change-log row is complete; the "Source added" and Classification paragraphs are folded with no loss of content. |
| TT3 changes (§9.8, §5.7 C1, §9.6 (C), F29–F31, BOOK-OUTLINE) | **VALIDATED** as answers to A and B | New defects: findings 12 and 13. §9.6 step 6 carries a qualification. |
| C16 changes | **VALIDATED**, except §8.9 C2's Ded | Finding 8. |

## 4. Coverage of the step-1 items

| Step-1 item | Status |
|---|---|
| S0092: provenance / namespace; self-contamination; authorship ≠ separation; access evidence; propagation; observation point; off-record | COVERED (C5, C6, C9) |
| S0092 L85, L90 overreach; S0092 L356 non-independence; closed world over the four adopting classes; the L397 heuristic | MISSING (finding 2; the rest minor) |
| S0093 non-equivalences; model ≠ document | COVERED (C1) |
| S0093: three authority kinds (L68); "FALSE" homonym; chain order | MISSING (findings 6, 10) |
| S0094 superseded, not disproven; append-only grants | COVERED (C2) / MISSING (minor) |
| S0094 "seven exclusions" | source defect (finding 17) |
| S0095 history parts | COVERED in §9.8 |
| S0095 deadlock, decision vs recommendation, separability, role routing, external content | MISSING (finding 7) |
| S0096 existence/category; claim-relative authority; six separations; OQ-L; dependencies; absent review | COVERED |
| S0096 C-14 wording and capability identity | MISSING (finding 14) |
| S0096 "absent" 925-line document | DISTORTED, repeated uncritically (finding 9) |
| START contradiction; re-scoping a finished verification | MISSING (finding 3) |
| git lineage (S0092 committed 08-28; no producers declared) | MISSING (finding 15) |

**New forks:**
- liveness of the workflow record: must the kernel guarantee that no state is stuck without an external fact?
- an identity criterion for capabilities;
- the DDD order of ownership vs category.

## 5. Next steps (dependency order)

1. Restate C3's Int and the open question (finding 1). Done when necessity, existence, category and kernel placement are four separate items.
2. Restate C5 and CON-0155 (finding 2), then re-read F32's evidence. Done when no "two routes" and no "not from identifiers" remain.
3. Add the START contradiction (finding 3). Then revise 1.13.1-2 (finding 4) and C2 / C9 to cite it.
4. Fix 1.13.1-3 (finding 5) and 1.13.2-3 point 5 (finding 11). Re-run `integration_check.py`.
5. Restate §8.9 C2's Ded over a fixed population (finding 8).
6. Add S0095's non-history content and the chain contradiction (findings 6, 7), with cross-references from §5.7 C4.
7. Minor fixes 9–17.
8. No question for the Principal is needed.

## 6. Limits

- Not opened: S0079 and S0080 (the CANCEL basis of §5.7 C1), S0091, S1609, S2782, S2775. I did not re-check S0014, S0015 or S0023 at 1.13.3's lines, because the integration did not change those citations.
- No script was run for this step. The quote checks I did are in finding 12 and the S0129, S0090 and S0089 lines read above; the rest of the quote counts are reported, not shown.
- The timings in finding 3 come from S0092's stated timestamps, file mtimes and git commit times; the act time of the human START is unknown.

