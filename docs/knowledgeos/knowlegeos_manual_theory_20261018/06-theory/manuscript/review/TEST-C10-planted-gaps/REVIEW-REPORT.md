# Transition review, Steps 2–3: Professor's §8.5 and §5.6 against S0051–S0055

Process independence only: I am a separate reviewer process, not a separate person. I re-read my saved inventory first, then both sections whole. I re-opened the five sources through the inventory and my first reading of them. I checked line references by line number. I did not read Ch. 1 or Ch. 9, S0049, the THEORY-MAP or the register. "[I]" marks my own judgment.

## 1. Assessment

- **§8.5 and §5.6 are mostly faithful to what they quote.** Their best material is the §8.5 C3 correction that the sample has 13 rows, not 12, and is purposive. I recounted rows at S0055 L61–73: 13 rows, 2 fail, 11 pass.
- **Two sentences state the opposite of their source.**
  - §5.6 C4 says approval of the aggregate carries "the bounded-context placement and the target architecture".
  - §8.5 C5 says "two of the defects reverse the direction of a Phase-A conclusion".
- **Largest risk: a systematic omission.**
  - The sections treat role independence and approval objects as settled facts. The sources say the independence is only Declared.
  - They also say the approved object is a stack of partly superseded documents with a known gap (V-1).
  - Large parts of S0054 and S0055 (grade honesty, the Declared→Observed upgrade, temporal change, the declared-vs-registry divergence) are absent from a section titled "Verifying a reconstruction".

## 2. Findings, ranked

| # | Sev. | Type | Claim or topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | critical | defect | §5.6 C4: "the aggregate recommendation is approved, and with it the bounded-context placement and the target architecture" | S0053 L41–43, L12 | The source says placement is NOT YET CONFIRMED and target architecture NOT APPROVED. This is a direct inversion. It also contradicts the section's own "never collapsed". | The Hyp in C4 ("approved" without its level is read as approving the levels below) has no observed case behind it. The sources also give three levels with separate gates, not a ladder with "levels below" [I]. | Rewrite C4 to the source status. Drop the "levels below" Hyp or mark it as unsupported. |
| 2 | high | defect | §8.5 C5: "two of the defects reverse the direction of a Phase-A conclusion" | S0055 L7 ("No defect changes the direction of any Phase-A conclusion"), L104, L108 | Contradicted by the source. It also contradicts C5's own first clause ("direction preserved") in the same bullet. | The direction-versus-magnitude distinction is sound. The overall sentence is false. | Delete the sentence. The accurate version is that D-2 overstates the magnitude, with direction preserved. |
| 3 | high | omission | Independence of role is treated as established in §8.5 C1/C2, and the lineage note does not draw the consequence | S0055 L95–96 (g), S0054 L89, S0051 L28–35, L140–142 | All three sources say separation is Declared and not attestable (INV-ATTR-2). S0055 says the verifier's separation cannot be established from the record (one shared committer identity). Only the lineage note touches it. | "Role independence" in C2 means declared independence, and the independence of the declaring is itself declared [I]. Every verification claim in §8.5 inherits this. | State this scope in C1 and C2. Add a fork: what counts as independence when it is unattestable. |
| 4 | high | omission / defect | §5.6 C3: acceptance covers a "pinned" delivered version, citing S0053 L21–23 | S0053 L18–23, L48; S0051 L131 | The approved object is a cumulative stack: rev 1, rev 2, rev 3 and the repair record R-1…R-8. It is not one commit. V-1 shows the supersession enumeration of that stack was incomplete (rev 2 §7), and approval was recorded with that note standing, no document edited. | The approved effective text is a composite that exists at no commit. C3's pinning claim is overstated. "What an act covers" has a composite-object problem the section does not state. | Add a claim: an act can cover a composite whose boundary is defined by supersession accounting, which can itself be defective. Cite V-1. |
| 5 | high | omission | §5.6 C2: "a conditional approval needs a second act", with the judgement made in that act | S0052 L85, L87; S0051 L121 | The sources show more than two acts. The landing (L85) came before the independent verification (L87), and the repair had been only Class-A self-reviewed. The final design approval (S0053) came after the verification. | C2 understates the chain. The landing judgement was made on a producer-reviewed repair, and independent checking followed later [I]. That sequence is evidence about what a "met condition" rested on. | Add the three-act sequence as a case. Fork: what evidence must precede the landing act. |
| 6 | high | omission | §8.5 omits most of S0055's grade-honesty content | S0055 L75, L77, L80–86 | The Declared probes were code-confirmed and "may be upgraded to Observed". No Observed claim rested only on a document assertion. The two Observed failures were wrong measurements, not disguised declarations. Boundaries were not inferred from names. The evidence hierarchy was respected. | These are the main logic of "Verifying a reconstruction". Without them §8.5 reads as only counting errors. The upgrade by re-derivation is a belief-revision step (Declared→Observed) with no home [I]. | Add a claim on grade calibration and grade change by re-derivation, with the "defect class" distinction. |
| 7 | normal | omission | Temporal change versus defect | S0054 L50–52; S0055 L50 | "8 workflow records" became 13 and is not an error because the baseline is a dated snapshot. §8.5 uses this only through the V-E point and omits the distinction. | A verifier needs an "as at" rule. Without it, a later growth would be scored as a defect. | Add to C1 or C6. |
| 8 | normal | omission | Counting errors recur across all five sources | S0051 L84, S0052 L17, S0054 L27, S0055 L47, L49, plus the 13-row slip | F-2, E-R1, D-1, D-2, V-A, V-D and the "11 of 12" slip are all count errors. Several are in the very documents that verify counts. Corrections were made by erratum or note, never by rewriting. | A recurring defect class that §8.5 touches only for D-1/D-2 and the 13-row slip. The append-only correction practice is missing in both sections. | Add a population-level observation, with a count of the cases. |
| 9 | normal | defect (scope) | §8.5 C7: verification checks conformance to findings, not their correctness | S0051 L39, L80–93, L113–123, L131 | Broadly right. But S0051 also did cross-cutting integrity checks (invariants unchanged, anonymity, STATUS table, commit hygiene) and found V-1, which is outside the findings. It also states the scope bound explicitly (affirmed items not re-litigated, L39). The section's counter-evidence (Ch. 9 review C9) is not in the five sources, so I cannot check it. | The claim holds for the findings layer only. | Scope C7 to "the finding-conformance layer". Cite L39. |
| 10 | normal | defect (inference) | §8.5 C2 sufficiency case: the producer's different method missed V-A, the verifier found it, so method independence is not sufficient | S0055 L47, S0054 L70–76 | V-A is a same-page reading inconsistency. The verifier was also instructed to "extend". Role, method and the task given are confounded. A single review missing one defect is also trivially expected. | The case is weak evidence on sufficiency. | Mark it a confounded case. |
| 11 | normal | omission | The declared-versus-registry modelling divergence (D-3 with Phase-03A L107) | S0055 L38–39 | Whether the composition root is a component is a definitional split, not a miscount. §8.5 treats only D-1/D-2 as shape examples. | Counts depend on what is counted. | Add. |
| 12 | normal | omission | Acceptance versus approval versus "established" is a homonym family | S0051 L66, L107; S0052 L50 | S0052 separates accepting a report as delivered from approving a design. The rename AssessmentAccepted→AssessmentEstablished was made to avoid a business-acceptance reading. §5.6 C3 merges "accepts or approves". | Homonym in the section's own key term. | Qualify the terms. |
| 13 | normal | omission | S0052's role separation: a producer does not recommend the disposition of its own findings | S0052 L4, L10–11, L30 | Not in §5.6. It may live in Ch. 5 or Ch. 1, which I could not see. | — | Check the home. |

## 3. Concept verdicts

| Concept | Verdict | Scope and reason |
|---|---|---|
| §8.5 C1 fixed object | VALIDATED | Producer non-repair during verification, plus drift control by diff on tracked inputs. One case. |
| §8.5 C2 different method, disclosure as leads | VALIDATED for the possibility claim; PROVISIONAL for the sufficiency case and the Hyp | Role independence is only Declared (finding 3). The sufficiency case is confounded (finding 10). |
| §8.5 C3 wrong high grade (CON-0122) | VALIDATED for the restated core and for "no error rate estimable"; PROVISIONAL for the cross-document Hyp | I recounted 13 rows, 2 fail. I did not recompute the binomial interval (reported, not shown). |
| §8.5 C5 materiality | INVALID as written (the "reverse" sentence); core VALIDATED once that sentence is removed | S0055 L104. |
| §8.5 C6 snapshot unauditable | VALIDATED | Untracked records only. One case. |
| §8.5 C7 verification only as good as findings | PROVISIONAL | The finding-conformance layer only. Depends on the unseen Ch. 9 claim. |
| §5.6 C1 conclusion versus argument (CON-0123) | PROVISIONAL | The "joined" reading is supported by S0052 L66 and S0053 L28. The alternative reading, repair as a precondition and not as part of the object, also fits. |
| §5.6 C1a interpretive act, fork F21 | PROVISIONAL, OPEN | The "invariant" homonym point is sound. The review itself required the ARB record of the presupposition (S0051 L77), which the section omits. |
| §5.6 C2 conditional approval | PROVISIONAL | See finding 5. |
| §5.6 C3 restricted acceptance (CON-0124) | PROVISIONAL | The "or returned to its author" half is not in any source. The pinning claim is overstated (finding 4). |
| §5.6 C4 act states its coverage and levels | INVALID as written (finding 1); the enumerated-scope Int is plausible, PROVISIONAL | S0053 L25–35. |

## 4. Coverage

Rows are ranked by importance.

| Inventory point | Status | Note |
|---|---|---|
| Independence Declared, not attestable (S0051 item 12, S0054 item 10, S0055 item 10) | MISSING | Finding 3. |
| Three-level status (S0052 item 9, S0053 item 3) | CONTRADICTED in §5.6 C4 | Finding 1. |
| Direction versus magnitude (S0055 items 4, 11) | CONTRADICTED in one sentence | Finding 2. |
| Cumulative approved stack and V-1 (S0053 item 4, S0051 item 13) | MISSING, with C3's pinning DISTORTED | Finding 4. |
| Landing before verification, then final approval (S0052 item 5) | DISTORTED | Finding 5. |
| Classification honesty, Declared→Observed upgrade, wrong measurement versus disguised declaration, boundaries not from names, evidence hierarchy (S0055 items 5–8) | MISSING | Finding 6. |
| Assessor standing as a C4 Independence claim, with its regress (S0051 item 8) | MISSING | Bears on declared independence. |
| F-1 insight (S0052 item 2) | COVERED | §5.6 C1. |
| Human's uniqueness sentence (S0052 item 6, S0051 item 4) | COVERED, with a detail MISSING (review demanded the ARB record) | §5.6 C1a. |
| Conditional landing of Q-B1/Q-B6 (S0052 items 4, 5) | COVERED | §5.6 C2, and see finding 5. |
| Acceptance held until delivery (S0052 item 8) | COVERED | §5.6 C3. |
| Fixed object, unmodified, drift control (S0054 item 1, S0055 item 14) | COVERED | §8.5 C1. |
| Different method, leads only, falsify (S0054 items 9, 12) | COVERED | §8.5 C2. |
| Wrong Observed worse than Unknown (S0054 item 3) | COVERED | §8.5 C3. |
| D-1, D-2 (S0054 items 2, 4) | COVERED | §8.5 C2. |
| V-A (S0055 item 3) | COVERED | §8.5 C2. |
| V-E (S0055 item 3) | COVERED | §8.5 C6. |
| Verifier's 8/0/0 against findings (S0051 items 1, 15) | COVERED, scope-limited | §8.5 C7, and see finding 9. |
| Closed gate input, both channels (S0051 item 9) | COVERED as an example only | §8.5 C7. |
| Not re-litigated items (S0051 item 14) | MISSING | Finding 9. |
| Temporal change versus defect (S0054 item 6) | MISSING | Finding 7. |
| Counting errors across all sources (cross-source table) | MISSING | Finding 8. |
| D-3 declared versus registry (S0054 item 5, S0055 item 2) | MISSING | Finding 11. |
| Acceptance, approval and "established" as homonyms (S0051 item 11) | MISSING | Finding 12. |
| Role separation (S0052 item 7) | MISSING | Finding 13. |
| Claims that survived re-derivation, weak points to attack (S0054 items 7, 9) | MISSING | Normal. |
| Disqualified producer (S0054 item 10) | MISSING | Normal. |
| Resolver blind spot V-3 (S0054 item 11) | MISSING | Normal. |
| Do not accept v1.0 unamended, correction under R-34 (S0055 item 12) | MISSING | An act-level case of conditional acceptance. Only L112 is used, as a parallel. |
| Stage 2 gated on baseline acceptance (S0052 item 10) | MISSING | The cross-work-item gate is the main example of "what an act depends on". |
| INV-ATTR-4/5/6 not adopted; negative scope (S0053 items 5, 6) | MISSING | The "what the act does not cover" list. |
| V-B, V-D (S0055 item 3) | MISSING | V-D supports the sections' own open question that every verification should state its method. |
| Aggregate boundary, derivation, 13 events, dispute routing, ownership rows, rename (S0051 items 2, 3, 5, 6, 7, 10) | JUSTIFIABLY EXCLUDED | Belongs to Ch. 9. I could not verify the home. |
| Session closure verbs, status snapshot, V-C (S0052 item 11, S0053 item 8) | JUSTIFIABLY EXCLUDED | Process, not theory. |

**New topics or forks the theory map likely lacks:**
- What an act covers when the object is a composite or a supersession stack.
- The dependency graph of acts across work items.
- Unattestable independence.
- Count integrity.
- Grade change by re-derivation.

**Source errors, listed separately:**
- S0055's "11 of 12" is a slip; the table has 13 rows. The theory relies on it only to correct it, which §8.5 does.
- S0055 L7 says "four precision defects" while the report lists D-1, D-2, D-3 and V-A plus minor items. The theory does not rely on this.

## 5. Next steps, in dependency order

1. Fix findings 1 and 2. Done when both sentences match S0053 L41–43 and S0055 L7.
2. Add the declared-independence scope to §8.5 C1/C2 (finding 3). Done when no claim treats role independence as established.
3. Re-derive C3 in §5.6 with the composite object (finding 4) and the three-act sequence (finding 5).
4. Add the grade-honesty claim (finding 6), then findings 7, 8, 11, 12.
5. Scope C7 (finding 9) and flag the confounded sufficiency case (finding 10).
6. Check homes for finding 13, using the book search over Ch. 5 and Ch. 1.

No question for the Principal.

## 6. Limits

- Not read: Ch. 1, Ch. 8 §8.3, Ch. 9, S0049, the THEORY-MAP and the register. Every "existing home" and the Ch. 9 review-C9 claim are unchecked.
- Not computed: the binomial interval in CON-0122 (reported, not shown).
- The sources are one lineage: one estate of sessions citing each other. They give one line of support, not independent confirmation. S0052 L87 is itself a summary of S0051.

## Appendix

- §8.5's lineage note, that S0055 and S0049 share a reviewer, matches S0055 L12–15. S0051's verifier is a different fresh process.
- §5.6 C3 says Q-C1 approved an assignment that did not yet exist. S0052 L74 and L83 are consistent with that, since the registration came after.
- §5.6's "same registrar" is not shown by the sources. They show only the same role (Governance) and the same day. Treat it as "one line of support" through the role, not the process.