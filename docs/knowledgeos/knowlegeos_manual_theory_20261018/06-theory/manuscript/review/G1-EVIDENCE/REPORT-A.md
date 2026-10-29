G1 REVIEW REPORT A

Object: §9.18 (draft), `ce_model.py` v21, and its run file. Reviewer A. I edited nothing in the repository. Probes ran on scratch copies under …/scratchpad/g1reviewA/.

## Summary (10 lines)
1. Verified: the v21 run reproduces exactly. Output lines 1–94 are identical to v20's run, and checks 33–35 pass.
2. Critical: containment (§9.18.6) is invalid as stated. Valid W entries change standing's answers by two routes the text does not name. (a) The single-use rule counts an evidence entry as a use: the guard refuses a later consumption or adoption, and standing gets the mark "second use of a single-use grant". (b) The designation guard forces a designation to cite W entries, and that takes standing out of D_S.
3. So "W's atoms are sinks" holds only for Φ's values. It does not hold for marks, guards or membership of D. "This changes no kernel law" is false.
4. g0 does read act-time order. It reads it through the producer's authorization (ρ): a same-time ending gives U. Check 34 tests the source text of `wclass`, not its behaviour.
5. `wclass` returns a class when standing is outside D_S, which contradicts §9.18.6's own L168. It also checks D_W on the cut only, unlike `ce_star_cut` (T1-01).
6. Two of the three reasons given for "correction ≠ withdrawal" fail in the model. Correction is reversible (annul the annulment), and the reasons it displays are identical to a withdrawal's. Only the per-purpose scope survives. `erst` gives the same class as annulling the withdrawal.
7. "One normative choice remains" understates. G2's precedence needs an authorising act (§10.2.3). Who may withdraw, restore or correct evidence is unstated. Two rules the Principal decided (designation's maximal entries, single-use "use") would need restating for containment to hold.
8. The research question is answered only conditionally. The class depends on unrecorded inputs (covers), on the purpose, on the version g and on T9-12. G7 is not addressed.
9. Survives: polarity and object (i); the Kleene ordering of g0 (argued, not computed exhaustively); "not attested in the record at s" as a display reading (v), with qualifications. "Separate record not shown necessary" stays PROVISIONAL, because its stated reasoning ("the only thing it would add") is wrong.
10. Every line stays S0. Nothing here supports raising a level.

Process independence: I am a separate agent process. I did not see reviewer B's work, or any review folder's content, and I took no part in drafting. This is process independence, not personal independence.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines (grep; the tool found no Ch. 10 claim forms) | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | **Containment result 1 and "changes no kernel law" are invalid as stated.** The proof is a sketch over Φ only. Missing premises: `uses_of` and `append_guard` (v13: "every act under a single-use grant is a use") and `marks` read W entries. `maximal_entries` and the designation guard (decision 7ecf5a024) quantify over every entry of the store. O2 says an evidence entry "is not an act", yet §9.18.5 (iv) evaluates it "like any act". | **critical** (F1, F3: R6, (d3), §9.18.8 rest on it) | Probe P1: consume refused, "gS is single-use and already used by e1"; without e1 it is accepted. P1c: the same for an adoption. P1b: mark "second use of a single-use grant" appears only with the W entry, values T=T. P2: the guard refuses a designation that omits W's maximal entry. After a designation that cites it, `in_D_S` is False ("closes ['e1'], which are not entries of its old store"); without the W entry it is True. P18: 279 interleaved records, 0 value differences, 19 mark differences. | Restrict the claim to "Φ's values". State the added D clause (no standing basis on W). Name the kernel rules that must change: the single-use "use" is restricted to standing's kinds; maximal entries are taken per kind or area. Mark these as changes, possibly for the Principal (Principal-decided rules). Withdraw "changes no kernel law". Turn T9-13 from "open" into "demonstrated conflict". | 9.18 L155–173, L188–190, L21–23; 10.03 L258, L266; 10.04 L58, L107, L118, L140, L158, L164; 10.05 L235, L324; 10 TODO L18; THEORY-MAP L375; 9 TODO L23; 9.00 L29 | restated claim, plus a model check that compares marks, guard outcomes and D_S membership on interleaved records with designations and single-use grants |
| A-02 | `wclass` gives a class while standing is outside D_S, and checks D_W on the prefix only. | high | P3: an unadmitted standing kind or an invalid consume gives `in_D_S` False, but the class is "supported" or "not attested". P3b: `in_D_W` on the whole record is False, the class at cut 3 is "supported". `ce_star_cut` checks D on the whole record (v10, T1-01). | `wclass` must require `in_D_S` and `in_D_W` on the whole record, as L168 states. | 9.18 L56, L125, L133, L168 | model fix and a check |
| A-03 | "The class reads no order of act times … ties are never read" is false. The producer atom reads `before` (ρ, §9.13 L82). Check 34 is a string test on the source. | high | P4: an ending at the same act time gives `('U', [('e1','refutes','U')])`; with tie=pos it gives "not attested"; an ending at a later act time gives "refuted". | Say instead: g0 reads no order among W's entries; act-time order enters through standing's atoms, and a tie there gives U, named (G9). Replace the string test with a behavioural test. | 9.18 L19, L87–90, L96–97; 9.00 L29 | restated, with a behavioural check |
| A-04 | §9.18.5 (ii)'s necessity reasons are partly false, and correction-as-annulment merges two meanings. A recording error is not the same as false content. An annulment voids the producer's assertion, against CON-0214 (b)'s "historical claim stays true". S0888's Retract(E1) keeps history. | high | P16: annul plus annul-of-annul, and withdrawal for '*' plus restoration, give identical classes at every cut and identical reasons. P17: annulling the withdrawal equals `erst`. | Keep the per-purpose scope as the reason. Drop reversibility, and drop the display reason unless the model carries a cause. Separate "mis-recorded" (annul) from "content wrong" (a refuting entry or a withdrawal for '*'). Justify `erst`, or remove it. | 9.18 L13–18, L64, L76–84, L199; 10.02 L201, L216 | rewritten (ii) with a model case |
| A-05 | The reasons do not carry the cause (annulled / withdrawn / unauthorised / U). (ii)'s display distinction and (c)'s "shown, not counted" therefore cannot be read. | high | P5: all three give `('e1','refutes','F')`. | Add the cause to each reason, then re-run check 34. | 9.18 L111, L143, L149 | model and text |
| A-06 | Normative choices are presented as settled: G2 precedence (§10.2.3 requires "adopted by an authorising act"); who may withdraw, restore and correct evidence; purpose semantics (none named means withdrawals are ignored; '*'); restating the Principal's designation and single-use rules (A-01). S0237's "CONFLICTED preserved until governed resolution" favours the rival "contested" class. | high | P6: B, with an unrelated grant, withdraws A's refutation for every purpose, and `append_W` accepts it. P7: a restoration by a third party is accepted. S0237 L214. | List these as choices: the decision-maker and the consequences of each option. Do not claim "one normative choice remains". | 9.18 L25, L84, L135–149, L205; 10.02 L213; 9 TODO L22; 10 TODO L25 | questions put, or argued from book law |
| A-07 | "Read from the record alone" holds only given unrecorded covers, θ and the identity link (PL-12 (a)), plus the purpose, g and the T9-12 option. G7 (vocabulary against F13 and the Q4 grade) is not addressed. g0's inputs (k, s, purpose) are not Q4's (k, s, g). Q4's "no grade without a recorded evidence entry" sits uneasily with an output of "not attested". | high | check 34 case e4 (covers undefined gives U); §9.10 L66–74; §10.2.3 L218, L223 | State the conditions. Map g0 to Q4 and G7, or say that it does not. | 9.18 L19, L29, L96; 10.02 L223 | stated |
| A-08 | Labels: §9.18.5 (iv) calls the model's "authorised" mode "(b)". It is option (c). Option (b) (refuse at append) is not modelled, since `append_W` never evaluates authority. The docstring says "reading (b)". | normal | `append_W` source; 9.18 L101 against L108–109 | Relabel it, or model (b). | 9.18 L101, L130 | text and model |
| A-09 | The generic/per-area partition is not well formed. "The kind cannot be trusted" applies to integrity and attestation, not to a dangling citation by an intact entry. The store-known clause is missing from `in_generic`. Three different lists appear (L23, L129, L162). Cross-area clauses (A-01) fit neither level. | normal | P10: a dangling citation refuses standing. P11: a W entry in an unnamed store passes `in_D_S` and `in_D_W`. | One list, each clause with its reason. Add a third class (cross-area clauses) or restate them. | 9.18 L23, L129, L162, L165–168 | text and model |
| A-10 | Check 33 is weaker than stated. "One D refuses 600" is by construction, since it counts `not(D_S and D_W)`. "Generic refuses 600" is true by definition. W entries are always appended last, no designations occur, and only 9 U values appear. | normal | source L2377–2416 | State the limits. Add interleaving, designations and single-use cases. | 9.18 L160–162 | revised check |
| A-11 | Homonym: CON-0212's and CON-0221's NOT ATTESTED means unresolved (two senses in S0115), which is close to W's U. §10.5 L33 adds "attested by the kernel". | normal | register row CON-0212 | Qualify the term, or cite only the "absence" clause. | 9.18 L44, L111, L119 | text |
| A-12 | Prior work: S0888 (allowed, line 370) is the direct continuation that answers S0887's question ("status = conflicted", Retract). It is not cited. It shares an estate and an origin with S0887. CON-0150's "not for first-hand content" is omitted, though it bears on T9-12. | normal | S0888 L365–380, L414–439 | Add it as one line of support, not as independent. | 9.18 L46, L50, L105 | table updated |
| A-13 | Quotes: "no guarantee" is not in §10.1.3, which reads "without a guarantee that it is well-founded" (about well-foundedness, not completeness). The T10-10 quote at L64 is spliced from L300 and L301. The tool checked 0 quotes (format). | normal | 10.01 L78; 10.03 L300–301 | Quote exactly. | 9.18 L64, L119 | text |
| A-14 | Undefined terms: purpose (no vocabulary or owner), evidence-entry scope, D_S/D_W, "generic clause", "containable"; review IDs SD4-02, SD1-01 and B4 used as references. §10.3.3 row B (law 26, I-9, one-sidedness) is not checked against g0. Removal of evidence gives "not attested" with no mark. | normal | R2 output; 10.03 L111–119 | Define them, or use the established wording. | 9.18 passim | text |

## Verdicts

| Part | Verdict | Reason |
|---|---|---|
| §9.18.4 three kinds | PROVISIONAL | Sufficient in the model. `erst` is not shown necessary (P17), and correction semantics are merged (A-04). |
| (i) polarity and object | VALIDATED (scope: item-level, two polarities) | It follows from §10.2.3 premise (i). The bearer and version are openly deferred. |
| (ii) correction and withdrawal | PROVISIONAL | Purpose scope differs; the other two reasons fail (A-04, A-05). |
| (iii) ordering and U | INVALID as stated | Ties are read through ρ (A-03). The Kleene ordering is argued sound. |
| (iv) producer | PROVISIONAL | Correctly left to the Principal. Mislabelled (A-08). The option set misses "count with a mark". |
| (v) completeness | PROVISIONAL | Sound as a display reading if qualified. Removal and unauthorised entries need marks (A-05, A-14). |
| G8 acceptance | PROVISIONAL | The refusals work. No authority is evaluated at append, and the standing-guard interplay is unhandled (A-01). |
| g0 | PROVISIONAL | Internally consistent. Its adoption is G2's normative choice (A-06), and the reasons lack causes. |
| §9.18.6 containment | INVALID as stated | A-01, A-09, A-10. Containing a W-clause breach holds for Φ's values only. |
| §9.18.7 placement | PROVISIONAL | No defect found beyond A-01. |
| §9.18.8 separate record | PROVISIONAL | "Not shown necessary" is fair as burden of proof. "The only thing it would add" is false (A-01). Reopening it would revisit the Principal's T10-10 (1). |
| Model v21 | checks 1–32 reproduced (output identical); 33–35 pass but test less than claimed | A-02, A-03, A-08, A-10 |

Proof-step table, §9.18.6 result 1:
1. "W read by Φ only for its own atom": justified by `phi` and `wfs`; true.
2. "No standing basis on W": a new D clause, not a book law. It is a hidden premise.
3. "No ending, revocation or adoption reads it": true for Φ and `current`. False for `append_guard`, `marks` and the designation clause.
4. "Sinks, so standing does not depend on W": valid only for values.

Judgement: inadequate, premise missing.

Limits: I did not compare v20 and v21 source code, because the main checkout's git history was refused in this worktree; I compared outputs only. Monotonicity was argued, not checked exhaustively. I read §9.16 and §9.17 only through citations. The R3 tool cannot resolve Ch. 10 claim labels, so the citing lines come from grep.

## Files opened
- BRIEF.md; 03-methodology/REVIEWER-INSTRUCTIONS.md; IMPLEMENTATION-STRATEGY-PROPOSED.md (headings, §3).
- 06-theory/manuscript: chapter_09/09.18 (whole); 10.02 L188–263; 10.03 L51–120, L246–325; 10.05 L221–240, L261–276 (plus grep); 10.04 (grep, L58–60); 10.01 (headings, L45, L77–80); 09.10 L66–75, L114–121 (plus grep); 09.13 L44–53, L82 (plus grep); 09.15, 09.06, 09.04 (grep); 09.00-index, chapter_09/TODO, chapter_10/TODO (grep).
- 06-theory: THEORY-MAP.md (grep); CONCEPT-REGISTER.tsv (rows CON-0150, 0173, 0212, 0214, 0217, 0221; grep).
- tmp/tools: ce_model.py (as a scratch copy; sha256 9ef90289… identical to the original); ce_model-run-20261028-v20.txt; ce_model-run-20261029-v21.txt; tool headers of register_home_check, undefined_terms, inbound_citations, quote_anchor_check, corpus_quote_check; 02-FILES.jsonl (grep).
- Corpus, each checked first in CORPUS-ALLOWED.tsv (lines 1578, 57, 369, 370), with sha256 prefixes matching: S0237 L192–236; S0294 L1015–1040; S0887 L1565–1590; S0888 (grep, L360–439).

## Commands and searches
- Copied ce_model.py to the scratch area and ran `python3 -I ce_model.py`. Diffed the output against the v21 run (identical) and against the v20 run (lines 1–94 equal).
- `register_home_check.py --root W`: 1 flag (CON-0133 H4, unrelated).
- `corpus_quote_check.py 09.18`: 0 found, 0 not found.
- `undefined_terms.py --root W --scope 09.18`: 15 in-scope candidates, each judged above (only wclass, containable and reversibility matter; the rest are ordinary words).
- `inbound_citations.py`, twice: no Ch. 10 claim hits; CON-0212 gave 15 concept hits.
- Probes p1.py–p4.py (P1–P18) on the scratch copy.
- grep searches over `chapter_*/*.md`, THEORY-MAP and the register for D_S, D_W, SD4-02, SD1-01, I-4, B4, K2-w, T10-20, T10-16, T10-10, T9-12…15, g0, PL-14, R5, R6, row C, BRANCH-02, F37, G8, Θ_f, (d3), G2/G5/G9, 9.18, "no guarantee" and S0888; greps of headings in 09.13, 10.01, 10.02, 10.03 and the implementation strategy.
- **Process deviation:** two searches were recursive (`grep -rn "no guarantee" chapter_*/` and `grep -rn S0888 chapter_*/ THEORY-MAP.md`). They traversed chapter_09/review/, whose existence I then confirmed with `ls -d`. Neither returned any line from it, and I opened nothing there.
- Refused, and not attempted another way: `git log` and `git diff` of ce_model.py in the main checkout (worktree isolation).
