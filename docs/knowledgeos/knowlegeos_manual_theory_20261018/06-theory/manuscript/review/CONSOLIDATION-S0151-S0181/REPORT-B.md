CONSOLIDATION S0151-S0181 REPORT B

Reviewer B (Sonnet). Process independence only, not personal independence. I did not read report A. Evidence labels: V = I checked it myself and a command or list is named; R = reported or read, not re-run. My scratch files are in the scratchpad (prefix cons_b_).

## 0. Summary (15 lines)
1. No critical finding. Findings: 24 (0 critical, 5 high, 19 normal). Nothing depends on a premise the book itself calls false (F4), so no "critical by F4 only".
2. The designation rule (§9.17 R3 (c), CON-0264) is where the book's claims go beyond its model. In ce_model v18, "foreign / withheld / safe direction" is a label that changes no CE* value (B-01). A concurrently accepted annulment, classed foreign, still flips an adoption from T to F (V: cons_b_probe2.py).
3. "Accepted entries only" and "carrier in force" in the guard read an oracle flag, `bypass`, that no real record carries (B-02). Check 30's "bypass write not forced into the members" passes because of that flag.
4. Of the Professor's completion, carrier-in-force and the three D clauses are needed and correctly labelled. The kernel-set store is needed by the decision's own wording. The accepted-only clause is needed, but its premise (the kernel holds a private accepted set) is unstated (B-02). Scope "one serialised kernel; across applications detected, not prevented" is right as far as it goes, but "detected" means the merged record leaves D and every CE* answer is refused (B-05).
5. The decision of 2026-10-27 is applied as worded in §9.13.2, §9.17 and T8-04. The content applied is the Rule Reviewer's text; the Principal's own act is three words ("yes record them"). I could not open the decision record (not on my list).
6. Ch. 4 has 6 defects (1 high, 5 normal). Ch. 5 has 7 defects (1 high, 6 normal). Ch. 4 and Ch. 5 corpus quotes hold: every `"…" [S L]` quote in scope was found, and I spot-checked about 120 corpus lines by hand.
7. Ch. 4 and Ch. 5 are estate observation and methodology. Ch. 9 does not cite §4.1/CON-0070 or §5.3/CON-0077 at all. §5.4 and the others appear only as pointers (grep, §0-style table in §6). They are not part of the primitives→laws→results derivation.
8. C29–C33 added no primitive and no law. The only theory content is turn 8 (K2-d/K2-w/K2-r, designation, carrier-change gate) and the T8-04 decision. §8.22–§8.26 are mostly "agreement within one lineage", which is zero independent support. They must not be counted as confirmation.
9. Access pattern hit: §8.26 C6 relays summary statements about files outside the allowed list (the "T3 PKS" cluster) as if they were findings (B-06).
10. T9-10: (a) technical shape exists, but whether to prevent or only detect forks needs the Principal, with a default I recommend. (b) Technical, with one new kernel assumption to be put to the Principal for notice.
11. Verdicts: Ch. 4 four VALIDATED confirmed (CON-0068, 0069, 0070, 0071), CON-0107 VALIDATED (as a source description only), CON-0113 INVALID. Ch. 5 VALIDATED 0048, 0049, 0077, 0131 confirmed with narrowed scope (0131 for the lemma only). The PROVISIONALs stay PROVISIONAL. CON-0261, 0264 and 0265 stay PROVISIONAL.
12. Stale items: THEORY-MAP §9 "Position" and §8 "71 of 134"; the §9.15 status line and the K2 cell attribution; §9.17.1 items 3–4; §5.2 "Ch. 8, planned"; §4.2 C4 "not yet read".
13. Source claims I relied on, and could not check: decision record `7ecf5a024`, PRINCIPAL-TESTIMONY, S0300/S0253/S1375/S0917/S0904 line citations in §9.17 (read as reported). Not read in full by me: Ch. 0–3, 6, §8.1–8.21, §9.1–9.16 (searched only).

## 1. Concept verdicts

### 1.1 In-scope concepts without an independent consolidation verdict
| Concept | Verdict | Reason (one line) | Scope |
|---|---|---|---|
| CON-0261 designated record (K2-d/K2-w/K2-r) | PROVISIONAL | R1 is valid but near-definitional. Independence of K2-w, K2-r, K1 is shown only by small countermodels in the text (R). K2-d is a presupposition that cannot fail alone, so it is not testable. The book itself labels the restatement "a re-axiomatisation, not a derivation". | kernel assumptions K2-w, K2-r; K2-d and L11 are legitimacy-level |
| CON-0264 membership by acceptance; classification across a carrier change (restated core of CON-0262) | PROVISIONAL | The two-history proof R3 (a) is valid, but its scope names only trusted shared time as a way to make membership decidable. A kernel-authenticated label is a second way, and it is neither adopted nor excluded (B-02). "Withheld, never promoted" is not realised (B-01, B-02). Revised three times in four days. | kernel (D kind, guard); the classification is application-level |
| CON-0265 carrier-change gate (restated core of CON-0263) | PROVISIONAL | Re-run anchor_model v2: output identical to the saved run (V: diff empty, 384,000 worlds). C1–C5 are one-line proofs from the gate definitions, so the model mostly confirms the definitions. The substantive claim is the decomposition into aspects of one gate (a coherent design). | architecture result, not kernel (D has no carriers) |
| CON-0262, CON-0263 (INVALID as stated) | not re-litigated | Their restated cores are CON-0264 and CON-0265 above. | — |

### 1.2 Ch. 4 (second independent verdict)
| Concept | Verdict | Reason |
|---|---|---|
| CON-0068 evidence entry (five sections) | VALIDATED | Valid as a record design (S0027 L113–121, V). The "four kinds, not three" Ded in C1 is a recount (B-15). |
| CON-0069 patterns derived, never written into entries | VALIDATED | The Ded "necessary, not sufficient" is valid. The rule that the proposing instance does not count is a stipulation, labelled as one. |
| CON-0070 counted class fixed in advance | VALIDATED | A methodological norm for confirmatory counts only. "Any n can be reached" overclaims. Its R-6 application omits S0027's own pre-fixed wording (B-12). |
| CON-0071 monitored compliance = scope ∩ coverage | VALIDATED | Valid for the definitional part only, which is a tautology. The empirical part is n=1 and Hyp, correctly excluded. |
| CON-0107 improvement cycle stages | VALIDATED | Valid only as a description of S0040 L14–41. It reports no run. Its only run (§4.3 C5b) was mixed on "the developer decides". |
| CON-0113 (INVALID as stated) | INVALID confirmed; restated promotion rule PROVISIONAL | The restated rule is an interpretation. S0044 L30 reads "HYPOTHESIZED — not introduced" under the heading "only terms in actual use", so an admission reading is not excluded (B-13). |

### 1.3 Ch. 5
| Concept | Verdict | Reason |
|---|---|---|
| CON-0048 decision package | VALIDATED | The core (one decision, evidence both sides, named authority, recommendation ≠ decision) holds. One instance, produced by the platform about itself. |
| CON-0049 gating order | VALIDATED | A near-tautological design principle. Its application in the docket is partial, as stated. |
| CON-0077 deferral with activation criterion | VALIDATED | As a design only. Its own practice breaks it (§5.3 C3, C4). |
| CON-0131 subject-blind check | VALIDATED | For the definitional lemma only. The lemma is trivial: a subject-blind check cannot separate subjects. That this engine is subject-blind rests on one probe (S0058 L82–88, V) that is not replicated. |
| CON-0079 three questions, orthogonal | PROVISIONAL | "Logically independent truth values" holds for any three unrelated yes/no questions. It does not show the source's decision-relevant "orthogonal". The conjunction in activation is asymmetric. |
| CON-0080 gap operator | PROVISIONAL | Not kernel, and isolated from Ch. 9 (see §6). Rests on sources consulted but not read in order. The must-do half (owner of R and criticality) is unresolved. |
| CON-0101 gap theory, second line (S2377) | PROVISIONAL | Its proof-status table matches S2377 where I checked (S2377 L461/L465–830: seven announced, ten defined; L1346 age formula; V). One corpus line, not independent. |
| CON-0123 conclusion ≠ argument | PROVISIONAL | n=1 (S0052). |
| CON-0165 executor ≠ authorizer | PROVISIONAL | One observed instance (S0044 L42, V). The authorizer being a person is inferred from "at the user's direction". |
| CON-0168 accepted pinned version | PROVISIONAL | Two cases in one estate. The §5.00 owner table overstates it as an invariant (B-18). |
| CON-0174 closure | PROVISIONAL | Null-deliverable gap (B-23). |
| CON-0175 supersession recognized only when recorded | PROVISIONAL, narrowed | "Inferring one is not evidence" exceeds S0080 L47 (B-20). |
| CON-0187 gate justified by failure mode | PROVISIONAL | A paraphrase of S0029 L14; trivial design principle. |
| CON-0050 | INVALID stands | The qualified form (T5-04) is unreviewed. Its "robust evidence" test quantifies over an open set of candidate answers. |
| CON-0052 | INVALID stands | Unreconciled counts (n=11 vs #12). Unsupported, not disproved. |
| CON-0053 | INVALID stands | The single-kind claim fails on genesis (S0024 L155/L162 vs S0025). Useful as vocabulary. |
| CON-0078 | INVALID stands | The cap is not met by the register's gates. |
| CON-0081 | INVALID stands | The status set mixes kinds. |
| CON-0109 | INVALID stands | Restated as CON-0165. |
| CON-0124 | INVALID stands | Restated as CON-0168. |
| CON-0144 | INVALID stands | Restated as CON-0174 (but see B-23). |
| CON-0145 | INVALID stands | Restated as CON-0175 (but see B-20). |

## 2. Findings
Severity rule: "high" = material defect or contradiction; none is critical. ID · finding · severity · type · evidence · correction · resolved when.

| ID | Finding | Sev. | Type | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|---|
| B-01 | Foreign classification is inert in v18. CE* (`wfs`) evaluates every entry in D. `members_closed` is not used by wfs, phi or current (cons_b_probe.py P8). A flagged bypass entry z has value T (P7). A concurrent entry accepted into the old store by another application (an annulment of g, labelled C1, uncited by the designation) is classed foreign yet flips x1 from T to F (cons_b_probe2.py). "Safe direction (withheld)" in §9.17 R3 (b), (c), §9.17.1 item 3 and CON-0264 is therefore not realised. It also conflicts with §9.13.2's own v5 result that "the safe direction was false for negative acts": withholding a negative act is the unsafe direction. | high | defect / contradiction | cons_b_probe.py P6–P8; cons_b_probe2.py; ce_model.py lines 218, 228; §9.13.2 (v5 paragraph) | Either define CE* over members only and state which direction is safe per kind (positive vs negative acts), or withdraw "safe direction". Add a model check with foreign positive and negative acts, asserted on CE* values. | the check exists and passes, or the claim is withdrawn |
| B-02 | The guard's "accepted entries only" (`maximal_entries(accepted_only=True)`) and `carrier_in_force` read an oracle attribute `bypass` (ce_model.py L217–218, L228). No real record carries it. R3 (a) proves content cannot separate accepted from foreign. K5 gives intactness only for unsigned entries, so it does not bind an entry to kernel acceptance. Without the flag (P1, P2): an unrecorded write z in the old store either blocks the designation (P1: refused, "omits the maximal entries ['z']") or is promoted into the members when cited (P2). Check 30's "bypass write not forced into the members" passes because of the flag (P3). R3 (a)'s scope also omits kernel-authenticated labels as a second way membership becomes decidable. | high | defect (missing premise) | cons_b_probe.py P1–P3; ce_model.py L2092–2094 | State the premise as a kernel assumption (the kernel holds a private accepted set, or K5 binds entries to kernel acceptance). Test without the flag. Extend R3 (a)'s scope to name this route. Or drop "never promoted". | premise stated and tested, or the claim withdrawn |
| B-03 | `store` is not a required field of D. FIELDS lacks it, `Record` default-fills 'C1', and `in_D` reads `x.get('store', ROOT_CARRIER)`. A late entry after the designation, labelled with the closed store (forged or defaulted), stays in D (P4) and is evaluated (P6). The text says "an unknown store puts the record outside D"; that holds only for an explicit unknown label. | normal | defect | ce_model.py L173, L321; cons_b_probe.py P4, P6 | Make `store` a required field of every kind in D, or state that absence means the root carrier and that this is a weakness. | stated in §9.13.2 or required |
| B-04 | Stale and mislabelled text. (a) §9.15 K2 cell attaches "decided by the Principal 2026-10-25" to "every later carrier by a designation entry in D", while §9.17 R1 labels that clause K2-d, the Professor's presupposition. (b) §9.15 status line still reads "version 10 (C22 settlement)". (c) §9.17.1 items 3–4 describe a closing list (R3 (b)) and "errs only by withholding, never by promoting", but not the designation kind or the decision of 2026-10-27. P2 contradicts "never promoting". | normal | defect (stale) | 09.15 L8–10, L75; 09.17 L64, L128–175 | Re-attach the attribution; update the status line; rewrite §9.17.1 items 3–4 from R3 (c). | the lines read consistently |
| B-05 | Scope consequence unstated. Across applications, a fork leaves the merged record outside D. Q1 then refuses every CE* answer (fail-closed total unavailability), and any application with write access to the carrier can cause it. The text says only "detected". P5 shows a bypass second designation gives "(False, two designations close the same store ['C1'] (a fork))". | normal | omission | 09.17 L166, L173; cons_b_probe.py P5 | State the consequence in R3 (c) and in T9-10. | stated |
| B-06 | §8.26 C6 relays summary statements about files outside the allowed list as a finding. S0178 L161 and S0179 L43 and L158 say "T1 KnowledgeOS vs T3 PKS context sets … must not be merged" and name the T3 context names. The T3 files are the `knowledge_tranfer` cluster, which is not on CORPUS-ALLOWED.tsv (grep count 0). C6 states "These are different systems with different context names" without a "reported by the summary" label. Pattern: content from a non-allowed file reached through a summary. | high | defect (access, pattern) | 08.26 L62–64; S0178 L25–27, L161; S0179 L43; CORPUS-ALLOWED.tsv (no rows) | Label the sentence "reported by S0178/S0179; the T3 documents are not on the allowed list and not read", or remove it. The T0-03 routing can stay as "a summary asserts". | sentence relabelled or removed; record in the access note |
| B-07 | §8.22 C7 uses an INVALID concept as the home of its deciding condition. "CON-0256 (i)" is INVALID as stated (register basis: its (ii) was refuted, (i) was never re-validated). C7 also equates it with "§9.17 R4's exactness", but R4's "exact" means "R lists members only", while C7 means "the reference IS the intended result". The two senses of "exact" differ. The deduction itself (a check against a reference that differs from the intended result cannot show conformance) is true by definition and needs no home. | normal | defect (INVALID home; homonym) | 08.22 L107–110; register CON-0256; 09.17 L218–219 | Drop CON-0256 (i) as home; state the deduction as definitional; qualify "exact". | home removed |
| B-08 | §8.24 C7 (labelled Ded) restates K2-d + K2-w. "A rebinding without a designation entry sends appends elsewhere" is K2-w's contrapositive, and K2-d is the Professor's PROVISIONAL presupposition. S0166 L1038 gives an analogy only (reasoning engine vs evidence authority model; the Professor's own [I] admits this). §8.24.1 item 3 presents it as "one deduction is new". The window's own evidence is not independent of the claim. | normal | defect (circularity, theory cited as evidence) | 08.24 L79–89, L37 | Relabel C7 "restatement of K2-d/K2-w; S0166 an analogy". | relabelled |
| B-09 | §8.26 C1 applies S0179's criterion for "Established" to S0178 ("overreaches by S0179's criterion, since S0178 states none of its own"). A rule quoted and applied beyond its own text. The title and C2's last sentence generalise "a summary is not evidence / not even reliable evidence" from one mis-gloss (S0181 L55–60, V) and the tag instances. The plain-language text says "can". | normal | defect (quantifier, rule applied beyond text) | 08.26 L1, L38, L45 | Drop the S0178 application or label it "by the book's reading of S0179's rule". Change the title to "can". | done |
| B-10 | §8.22–§8.26 add no independent support. Every agreement is "within the estate's lineage". At least six sections routed "one more framing" to T0-03 (S0153, S0163, S0167, S0171, S0175, S0176, S0178/S0179 "kernel" uses) without resolving it. T0-03 (what KnowledgeOS is) is the open question on which the kernel/estate relation depends. | normal | omission (architecture) | 08.22 C5; 08.23 C10; 08.24 C8; 08.25 C1, C7; 08.26 C6 | Give T0-03 its own turn, or state that the window sections are a coverage ledger, not validation. | T0-03 decided or the status line added |
| B-11 | §4.1.2 "Consequence in this estate (Ded)": "every pair of instances shares the operator and the model family, so no two in-estate instances are independent". The definition lists "the same actor, the same instructions, the same reviewer or the same work item". The Ded needs the unstated premise that "actor" means operator/model family. C5 labels the same fact an inference (Int: S0027 names no actor). It also makes the promotion condition unsatisfiable in-estate (T4-03, DB-2) by definition. | high | defect (missing premise, labelling) | 04.01 L16–18, L62 | State the premise and relabel the consequence Int, or restrict "actor" to a session. | premise stated, label consistent |
| B-12 | §4.1 C3 says the R-6 label "was checked by a membership test applied after the review's caution … not fixed in advance". S0027 L175 says the edge was checked against the canonical wording (Relationship Validation Matrix, activity level) and that "had R-6 been implementation-scoped, the count would have been trimmed to n=1 — the check was real". C3 omits both. The unit point (OE-KOS-1/-2 = one work item WP-4B) is verified (S0027 L11, L52, V). | normal | defect (omission) | 04.01 L46–50; S0027 L175 | Add the canonical-wording claim and its status (asserted by S0027, not verifiable here). | added |
| B-13 | §4.3 C1 and CON-0113: "Hypothesized terms are already listed in the table … refers to their promotion". S0044 L30 says "HYPOTHESIZED — not introduced. They enter this table only when engineering performs them", under the heading "Candidate Ubiquitous Language (only terms in actual use)". The row does not show the terms as members. Both readings fit the source. The INVALID ground ("the source grades vocabulary and does not exclude it") is contestable. | normal | defect (source reading) | 04.03 L13–16; S0044 L22–30 | State both readings and keep the restated rule as Int. | done |
| B-14 | §4.2 C4 "The evidence base it names (RD-1 … RD-7) is not yet read." S0040 L8 names S0044, which §4.3 reads. §4.2.4 and §4.3 C5 already say the answer. | normal | defect (stale) | 04.02 L36; S0040 L8 | Update C4. | updated |
| B-15 | §4.1 C1 "four kinds, not three (Ded)" counts provenance as an epistemic kind. The source's three kinds (L113) are most plausibly observation, claims affected, interpretation. The "fourth kind" is a recount. | normal | defect (label) | 04.01 L22–31; S0027 L113–121 | Relabel Int, or say which three S0027 means. | relabelled |
| B-16 | §4.2 C3 maps "Sub-week windows withhold rates" to CON-0070 ("fixed-class, fixed-unit rule"). CON-0070 concerns confirmatory counts. A reporting rule is not that. | normal | defect (loose mapping) | 04.02 L31–32 | Drop or qualify the mapping. | done |
| B-17 | §5.7 C7: "The ARB: 'The reconciliation itself … current portfolio truth.' Then: 'Adopted. … authoritative portfolio state.' [S0080 L8–10]". The L10 line is the registration's voice, not a quoted ARB act. S0080 L4 says the registration "records ARB DIRECTION and RECOMMENDATIONS", and §5.7 C4 uses that same line to say a recommendation authorizes nothing. F28 rests on this. | high | defect (authority read through a registrar's gloss; internal inconsistency) | 05.07 L62–66, L51; S0080 L4, L8–10 | Label L10 as the registrar's rendering. Decide whether the registration records an act. Restate F28 on the quoted ARB words only. | F28 re-grounded or withdrawn |
| B-18 | §5.00 owner table lists "the accepted object is pinned | the registrar, by hash" as an invariant with an owner. §5.6 C3 and CON-0168 are Hyp (2 cases, one estate); §5.6 C7 says the approved text is a composite with no single commit. | normal | defect (overstatement) | 05.00 L48; 05.06 L29–33, L68 | Move the row to "none (practice, n=2)" or qualify. | row changed |
| B-19 | Stale forward references. §5.2 "a method point for the validation of the theory (Ch. 8, planned)". Ch. 8 has 26 sections. §5.3 C2 "Ch. 6 §6.1 C1 (a forward reference)". Ch. 6 is written. | normal | defect (stale) | 05.02 L51; 05.03 L46 | Update the references. | updated |
| B-20 | §5.7 C3: "inferring one is not evidence". S0080 L47 ("do not infer" supersession) is a conduct instruction; S0079 L62 treats supersession as "plausible". The claim is stronger than the text. | normal | defect (rule beyond its text) | 05.07 L42–44; S0080 L47; S0079 L62 | "An inferred supersession is not sufficient to recognize one." | reworded |
| B-21 | §5.3 C3 is labelled "(Src, counted here)", but its conclusion "none was an independent criterion" is Int. S0029 L62 labels one row "FIRST CRITERION-FIRED TRANSITION" (V). The book's argument is defensible but is an interpretation. | normal | defect (label) | 05.03 L49–64; S0029 L62 | Relabel Int. | relabelled |
| B-22 | §5.4 C7 (inclusion): "eligibility cannot be lost" ignores that eligibility is "subject to authorization" and that criticality may change. "Most pairs of states are incomparable" is unsupported. | normal | defect (hidden premise) | 05.04 L112 | State "under fixed R and fixed criticality, ignoring authorization"; compute or drop the incomparable-pairs claim. | stated |
| B-23 | §5.7 C1 formula deliverable ∧ (lifecycle ∨ defect stated) does not define "deliverable" for a null. A deliberate null counts, a never-ran null does not. The difference rides on an unformalised "ran". V#3's lane ran off-record. | normal | defect (undefined term) | 05.07 L26–31; S0079 L32, L57 | Add the "ran" condition to the formula. | added |
| B-24 | THEORY-MAP is stale. §9 Position says "Last source read in order: S0150; next: S0151" and "8 (§8.1–8.21) · 9 (§9.1–9.16)", beside §8.22–8.26 and §9.17. §8 Weak points says "71 of 134 registered concepts are INVALID". The register has 265 concepts, 96 INVALID (V: csv count 45/124/96). | normal | defect (stale) | THEORY-MAP L362–364, L360 | Update. | updated |

Mechanical checks (V): R1 on the copy: 241 homes, 1 flag (CON-0133 H4, in ch. 6, outside my scope). R2: tier A 63 / B 59 / C 17 / D 529. In my chapters the tier-A words are unattested, lexicographically, obedient, staffing (ordinary words), plus TURN8 (a label). Quote check `corpus_quote_check.py`: Ch. 4: 17 found / 0 not found; Ch. 5: 96/0; §8.22 11/0; §8.23 14/0; §8.24 8/0; §8.25 3/0; §8.26 0/0 (no quote in that format); §9.17 7/0. The tool covers only `"…" [S L]` quotes. "In meaning" citations were not checked by the tool.

## 3. Rotated chapters: defect counts
| Chapter | Defects | High | Normal | IDs |
|---|---|---|---|---|
| Ch. 4 (04.00–04.03, 04.99, TODO) | 6 | 1 | 5 | B-11, B-12, B-13, B-14, B-15, B-16 |
| Ch. 5 (05.00–05.07, 05.99, TODO) | 7 | 1 | 6 | B-17, B-18, B-19, B-20, B-21, B-22, B-23 |

Notes: the §4.00 index still says Ch. 4 "does not hold the kinds of change" (pending M13, the Principal). Ch. 4 TODO T4-04 is consistent with the text. Ch. 5 §5.4 uses sources consulted but not read in order (declared; not a defect).

## 4. Coverage against the five inventories
Marks: C = COVERED, M = MISSING, D = DISTORTED, X = JUSTIFIABLY EXCLUDED. "(not read)" means the home is a section I did not read; I relied on its title and on search hits. Items resting on S0155/S0156/S0157/S0161/S0170/S0174 do not occur in the inventories, so no X (access) marks were needed. S0175 items 9 and 11 may rest on S0156/S0157/S0170 content; I mark them X (business / access).

### C29 (S0151, S0152, S0153, S0154, S0158)
| Items | Mark | Book location |
|---|---|---|
| S0151 1, 3–12 (identity, evidence referenced, graph projection, AI no authority, seven contexts, Method/Binding/Evidence, Decision aggregate, events, delivery flow, runtime services, pillars) | C | §8.22 C1 (duplicate = S0149) and §8.21 C13 (not read); item 4 also §8.23 C8; item 5 §8.22 C1 |
| S0151 2, 13 (ACTIVE invariant owner; aggregate boundary contradiction) | C | §8.22 C1, §8.23 C7 |
| S0151 14 (lineage) | C | §8.22 C1 |
| S0152 1 (sequencing/authority thesis), 9 ("single scope gap" causal overreach), 10 (two-authority split), 11 (seven integration surfaces) | M (application/governance level, low) | — |
| S0152 2, 3, 4, 6, 8 | C | §8.22 C3, C10; §8.20 C13; §8.25 C2 via §8.06 C6 |
| S0152 5 (G-4) | C | §8.24 C10 (via S0168/S0164 wording) |
| S0152 7 (back-test), 12 (RD-* tension) | C | §8.22 C4 |
| S0152 13 (lineage) | C | §8.22 lineage |
| S0153 1–4, 9, 12 (role model detail, options, inference from component name) | M (application, low) | §8.22.3 records "advisers' proposals" |
| S0153 5, 10, 13 | C | §8.25 C8; §8.24.3; §8.22 header |
| S0153 6, 7, 8 | C | §8.22 C5, C6 |
| S0153 11 (metaphor) | X | not theory |
| S0154 1, 2, 4, 5, 6, 7, 8 | C | §8.22 C7, C8 (the recorder's reading separated and scoped) |
| S0154 3 (scanner failure observation), 9 (L4/L5 separability precondition), 10 (PHP only), 11 ("CURRENT" inconsistency) | M (low) | — |
| S0154 12 (dialogue, session tail) | C | §8.22 header lineage |
| S0158 1, 2, 3, 6, 8, 9 | C | §8.22 C9, C10, C11; §8.22.3 |
| S0158 4, 5 (Assurance context; Assurance ≠ Governance) | C | §8.22.3 (recorded only); §8.22 C10 |
| S0158 7 (pipeline) | C | §8.23 C6 (same lifecycle, S0160) |
| S0158 10 (command path) | C | §8.24 C8 (applications reach knowledge through the kernel) |
| S0158 11 (adoption table), 13 (undefined "Kernel" above contexts) | M (low) | — |
| S0158 12 (formula; refined EKS definition L586) | M (the EKS-definition part bears on T0-03) | — |

### C30 (S0159, S0160, S0162, S0163, S0164)
| Items | Mark | Book location |
|---|---|---|
| S0159 1, 2, 3, 4, 5, 6, 9, 13 | C | §8.23 C1, C2, C3, C10, header; §8.22 C11 |
| S0159 7 (R-CONFLICT as event stream) | M for the event-stream recast. The no-side rule is homed at §8.17 C9, §8.19 C26 (not read). | — |
| S0159 8, 10, 11, 12 | M / X (technology, low) | — |
| S0160 1, 2, 4, 5, 6, 8, 10, 12 | C | §8.23 C4, C5, C6, C9, §8.23.3 |
| S0160 3 (Evidence "source of truth governance evaluates") | M (low) | — |
| S0160 7 (R-CONFLICT as an Evidence-owned domain invariant) | M (settlement: noted for the consolidation) | — |
| S0160 9 (ADR-KOS-002 rules are constraints only; trivial-model test) | M | §8.23 does not apply the test; the book does apply it elsewhere (§9.6, §8.19) |
| S0160 11 (contradictions) | C, reading differs. The book calls the freezing statements "not a contradiction" (Professor's reading). Not disproved by me. | §8.23 C6 |
| S0160 13 | M (low) | — |
| S0162 3, 4, 6, 7, 14 | C | §8.23 C7, C8, C9, §8.23.3 |
| S0162 5, 8 | C (recorded only) | §8.23.3 (W-14) |
| S0162 1, 2, 9, 10, 11, 12, 13 | M (low) | — |
| S0163 1, 2, 5, 8 | C | §8.23 C10 |
| S0163 3, 4, 6, 7, 10, 11, 12 (moat, IP, legal, numbers) | X (business) | — |
| S0163 9 (records as authorship evidence) | C? probably §8.10 (not read) | — |
| S0164 1, 2, 3, 5, 6, 9, 11, 12, 13, 14, 15 | C | §8.23 C11, C12; §8.06; §8.07; §8.15; §8.22 C3 |
| S0164 4 (batching) | C (recorded only) | §8.23 C11 |
| S0164 7 (neutrality scope) | C | §8.23 C12 |
| S0164 8 (AST necessary not sufficient; regex residual risk) | M (application, low) | — |
| S0164 10 (registration vs capability) | C? §1.13.3 / §8.09 C9 (not read) | — |

### C31 (S0165, S0166, S0167, S0168, S0169)
| Items | Mark | Book location |
|---|---|---|
| S0165 1–5, 9, 10, 11, 12, 14 | C | §8.24 C1–C5, §8.24.3 |
| S0165 6, 13 | M (low) | — |
| S0165 7, 8 | C | §8.24 C10 partial; §8.24.3 |
| S0166 4–6, 9, 11, 12, 13, 14 | C | §8.24 C6, C7, §8.24.3 |
| S0166 1, 2, 3, 7, 10 | M (low; hexagonal covered in §8.21 C13, not read) | — |
| S0166 8 | C | §8.24.3 |
| S0167 1, 2, 3, 4, 6, 8, 9, 11, 12, 13 | C | §8.24 C8 |
| S0167 5 ("stores meaning"; correctness and trust as the kernel's critical property; primary risk knowledge corruption) | M | — |
| S0167 7 (distinguishing question) | C (RESEARCH-PURPOSE central question is the same) | — |
| S0167 10 (unsupported prediction) | M (low) | — |
| S0168 1, 3, 4, 5, 6, 7, 9, 10, 12, 13, 14, 15 | C | §8.24 C3, C9, C10, C11, §8.24.3 |
| S0168 2 ("audit record ≠ provenance proof"), 8, 11 | M / C partial | §8.24.3 for 8 |
| S0169 1–9 | C (duplicate of S0163) | §8.24 C12 |
| S0169 3, 5, 6, 10 | X (business) | — |

### C32 (S0171, S0172, S0173, S0175, S0176)
| Items | Mark | Book location |
|---|---|---|
| S0171 1–8, 11, 12 | C | §8.25 C1, C2, §8.25.3 |
| S0171 9 (composition root) | M (low) | — |
| S0171 10 (names) | X | — |
| S0172 3–9, 11–14 | C | §8.25 C3, C4, C5, §8.25.3 |
| S0172 1 | C | §8.25 lineage |
| S0172 2 (Track 2 governs nothing; "rulings" authorize nothing; the source never states the distinction) | M | — (§8.25 labels the relay under the C23 rule but does not state it) |
| S0172 10 (SC-1) | C? §2.8 (not read) | — |
| S0173 2, 3, 4, 6, 7, 8, 9 | C | §8.25 C6, §8.25.3 |
| S0173 10 | C | §8.25 C7 |
| S0173 1, 5, 11, 12 | M (low) | — |
| S0173 13 | C | §8.25 C6 |
| S0175 1, 3, 4, 5, 7, 8, 10, 12 | C | §8.25 C5, C7, C8 |
| S0175 2 (undeclared level tag, OPEN in the level slot), 6 (T1) | M (low) | — |
| S0175 9, 11 | X (business; access) | — |
| S0176 1, 2, 3, 5, 6, 7, 8, 9, 10, 12 | C | §8.24 C8; §8.25 C1, C7; §8.26 C1 |
| S0176 4 | M (low) | — |
| S0176 11 | X | — |

### C33 (S0177, S0178, S0179, S0180, S0181)
| Items | Mark | Book location |
|---|---|---|
| S0177 1, 4, 5, 6, 8, 9, 10, 11, 12, 14 | C | §8.26 C1, C3, C4; §8.22 C2, C3; §8.23 C6; §8.24 C10, C11; §8.25 C3 |
| S0177 2 (three-way separation), 13 (T1–T4), 15 (changes over time, counts) | M (left by the settlement as source defects) | — |
| S0177 3 (Option B′ / R-CONFLICT as an Evidence-owned invariant) | M (noted for the consolidation; no-side rule homed at §8.17 C9, not read) | — |
| S0177 7 (assurance facts ESTABLISHED) | C partial | §8.25 C7 (S0175's freeze trigger) |
| S0178 3, 5, 6, 7, 8, 9, 10, 12, 13, 15, 16, plus duplicate point | C | §8.22 C1, §8.22.3, §8.23 C4, C7, C9, §8.25 C3, C4, §8.26 C1, C6 (15: but see B-06) |
| S0178 1 (identity claim "Knowledge Governance Domain Platform"), 2, 4, 14 | M (identity bears on T0-03) | — |
| S0178 11 | C | §8.23.3 |
| S0179 1, 2, 4, 5, 8, 12, 13 | C | §8.26 C1, C3, C6, §8.26.3 |
| S0179 3, 6, 7, 9, 10, 11 | M (low; 9 is DC-6's inconsistency in caution) | — |
| S0180 1, 2, 3, 8, 10, 11 | C | §8.26 C5, §8.26.3 |
| S0180 4, 5, 6, 7, 9, 12 | M / X (7 and 12 left as source defects) | — |
| S0180 13 | C | §8.25 C6 |
| S0181 5, 6 | C | §8.24 C6; §8.26 C2 |
| S0181 4 | C | §8.24 C8 |
| S0181 1, 2, 11 (Hexagonal ESTABLISHED on one assertion; "consistent with" ≠ adoption) | M (left as source defects "adapters") | — |
| S0181 3, 7, 9, 10 | M (low; 7 bears on T0-03) | — |
| S0181 8 | X | — |

No item is DISTORTED in a way that changes meaning. The nearest case is S0160 item 11 (the book's "not a contradiction" is a reading, not shown wrong). The most important MISSING items are: S0172 2; S0177 3 and S0160 7 (R-CONFLICT as a domain invariant owned by Evidence); S0178 1 and S0158 12 (identity and refined EKS definition, T0-03); S0160 9 (trivial-model test on ADR-KOS-002); S0167 5.

## 5. Sequence check (SEQ)
- §8.22–§8.26 use Ch. 9 vocabulary (D, K2-w, K2-r, T/F/U, CE*, L11, K6) before Ch. 9 in number order. This is acceptable only if the reading order (TARGET-TOC, not visible to me) puts Ch. 9 first. The Ch. 8 index does not define them.
- §4.1.2 cites §8.6's factor set (forward). Defined later in the same book; fine if labelled.
- §4.2 C4 stale (B-14). §5.2 and §5.3 stale (B-19). §9.17.1 items 3–4 stale (B-04). THEORY-MAP §9 and §8 stale (B-24). §9.15 status line stale (B-04).
- §5.4 uses "Lord" (explained inline) and E1–E10 (Ch. 2, earlier). No concept used before its definition was found within Ch. 4–5 itself.
- §8.25 C2 uses "T" as an authorization value; defined in Ch. 1/Ch. 9, not in §8.25.
- No citation resolves to a missing section in the files I read.

## 6. The changed theory: the designation rule
### 6.1 Is the decision of 2026-10-27 applied as worded?
- I could not open `7ecf5a024` (not on my list). I compared the book's quotations of it in §9.13.2, §9.17 R3 (c), §8.22 C7, T8-04 and T9-09 with one another: they agree.
- The decision's (a) is reproduced verbatim in §9.13.2 and §9.17: cite every maximal entry, refused at append if omitted, the old store's end fixed by citation alone, limit for bypass entries. (b) F or U refused at append, authority in the same kernel step (K6).
- The book applies (a) in append_guard (maximal over accepted entries only) and (b) in the guard (wfs on tmp): V (model v18 run identical to the saved file, `diff` empty; checks 29 and 30 PASS).
- Heading status: "Resolved", "Decided". The content is the Rule Reviewer's recommendation, adopted by the Principal in three words. The book labels this ("the Rule Reviewer's recommendation, adopted by the Principal"), so the labelling is correct. What the Principal's "them" covered is only in the record.
- T8-04's application ("conformance on that choice is U until a recorded act adopts one") is a deduction by the Professor, labelled, with check 29 (6) "true by construction" and (7) testing adoption machinery, not conformance. Correctly labelled.

### 6.2 Is the completion needed and correctly labelled?
| Part | Needed? | Labelled? | Comment |
|---|---|---|---|
| carrier in force (old store must be the carrier in force) | Yes. Without it two designations close one store and K2-d fails. | Yes ("the Professor's completion") | holds within one serialised kernel only |
| kernel-set store | Yes by the decision's own wording ("every entry no other entry of that store cites"). The rival (down-closure) is recorded. | Yes ("technical choice, for review") | B-02, B-03: the label is a claim, and D does not require it |
| accepted entries only | Yes, the decision's Limit requires it. | Yes | realised by an oracle flag (B-02) |
| three D clauses (one chain, one designation per store, no unknown store) | Yes for merged records, so D can decide the carrier in force. | Yes ("the Professor's additions", W-02) | B-03; a bypass entry can take the record out of D (P5) |

### 6.3 Is the scope right?
- "One serialised kernel (K6); across applications detected, not prevented": right as a statement of what the guard and D do. B-05 adds the consequence (total refusal). B-01 shows "classed foreign, the safe direction" is not realised, and fails for negative acts. P1b is not a model check (the book says so).

### 6.4 T9-10 recommendations
| Question | Recommendation | Technical or Principal |
|---|---|---|
| (a) One coordination point per carrier for designations, as K4 is for grants? | Yes, if prevention is wanted: model a designation like a single-use grant. The carrier has an owner application, and the kernel refuses a designation not recorded by its owner (the check-24 pattern: "use of g refused: recorded by A2, not by its owner A1"). Detection alone makes the merged record unanswerable (B-05), so a default of "prevent" is better. | The mechanism is technical. Whether prevention is required is a normative choice (it fixes a deployment constraint and liveness) and needs the Principal. The Principal decided "prevent" for single-use grants; this is the same shape. |
| (b) Should a bypass entry be able to take the record out of D? | Split it. An entry with an inconsistent label (unknown store; a second designation of one store) is an integrity-type failure, so leaving D agrees with the book's existing fail-closed rule for failed integrity (§9.13.2 "Integrity") and is intended. A well-formed forged entry cannot be separated from an accepted one (R3 (a); B-02), so it must not be described as "not seen" but as indistinguishable. Foreign entries that remain consistent should be evaluated and marked ("rests on foreign / unattributed entries"), consistent with "marks, not refusals" (decision 2) and with negative acts (B-01), not silently withheld. | Technical, except that it adds a kernel assumption (kernel-held accepted set, or K5 binding to kernel acceptance). Bring that assumption to the Principal for notice, as K1–K7 were. |

## 7. Architecture view
- **What C29–C33 and turn 8 added.**
  - To the primitives: nothing. To the laws: turn 8 (K2 restated: K2-d, K2-w, K2-r; L11; the designation kind in D; the carrier-change gate, an architecture result) and the T8-04 decision (the incumbent is evidence of one choice, not a specification).
  - Everything else in §8.22–§8.26 is "recorded as evidence for X within the estate's lineage". It is observation of one estate (one operator, one model family, one adviser thread), not theory.
  - The robot, translator, role-model, DI, Linux and IPO texts produced no kernel-level addition.
- **Isolated, circular or unsupported parts.**
  - **Isolated:** Ch. 4 and Ch. 5 are not used by the derivation. Grep over Ch. 9 (V): §4.1/CON-0070 and §5.3/CON-0077 in 0 files; §5.4/CON-0080 in 4 files (pointers or notes: §9.07 L26/L177, §9.04 L58, §9.01 L29, §9.10 L96/L246), none derivation. §5.4 is a candidate (Q7 in §9.10) with an unowned must-do half.
  - **Circular:** B-08 (§8.24 C7 restates K2-d/K2-w and cites S0166 as evidence).
  - **Unsupported:** foreign classification has no semantic effect (B-01); the accepted set is an oracle (B-02).
  - **Open and blocking:** T0-03 collects at least six "further framings" without resolution (B-10).
- **Per concept: kernel or application / minimal / stable.**
  | Concept | Layer | Minimal | Stable |
  |---|---|---|---|
  | CON-0261 K2-w, K2-r | kernel assumptions | shown independent of K1 by small countermodels (R); all rest on K2-d | revised three times (v16–v18) |
  | K2-d, L11 | presupposition / legitimacy | n/a | no |
  | CON-0264 | the kernel part is the D designate kind and the guard; classification is application-level | not shown minimal | no (B-01, B-02) |
  | CON-0265 | architecture / application | G0, G1, G2 decomposition; coverage, independence and operand are aspects of one gate | stable as a decomposition |
  | CON-0080 gap operator | application / derived; could be a kernel candidate | must-do half missing | no |
  | CON-0131, CON-0165 | kernel boundary requirements (the kernel's authorization check is subject-aware; recorder identity is a claim) | CON-0131 is a lemma | CON-0131 stable, CON-0165 n=1 |
  | CON-0048/0049/0077 | application / methodology of one estate | n/a | n/a |
  | CON-0068–0071, CON-0107 | methodology / application | n/a | n/a |

## 8. The previous settlement (C33) against the copy
All 11 rows (W-01 to W-11) are applied in the copy where I looked: W-01 §9.17 R3 (c)(1), §9.13.2, CON-0264 register text, T9-10 (V); W-02, W-03, W-04 §9.17 L171–174, §9.13.2 (V); W-05 "last entry" superseded at §9.17 L64, L158 and §8.19 L236 ("this replaces") (V); W-06 §8.25 C7 and §8.26 C1 contain the removal notes, and the six files have no row in CORPUS-ALLOWED.tsv (V); W-07 §8.26 C3 restated, S0179 L115, L205–206 match (V); W-08 §8.26 C1 scoped (V); W-09 §8.26 C2 reviewer-verified against S0154 (S0181 L55–60 matches the book's description, V); W-10 §8.26 C5, S0180 L78–81 is tagged PROPOSED (V); W-11 §8.26 C6 restated. I could not verify the Professor's own V marks on the decision record (line 428) or on the index match S0155 = `123902`. "Checks 1–28 unchanged" is not something I could check; I re-ran v18 and found its output identical to the saved v18 run.

## 9. Pattern checks
| Pattern | Instances |
|---|---|
| Rule quoted but applied beyond its text | B-09 (S0179's criterion applied to S0178); B-20 (S0080 "do not infer"); B-04(a) (K2 cell attribution) |
| Derivation missing a premise | B-02 (kernel-held accepted set); B-11 (actor); B-22 (eligibility); R3 (a) scope (B-02); B-23 |
| INVALID concept cited as a home | B-07 (CON-0256) |
| Source's self-issued status adopted as shown | none new. §8.25 C7, §8.26 C1 and §5.3 C3 (B-21) handle the cases correctly. |
| Summary used as evidence for what it summarizes | B-06 (S0178/S0179 on T3 PKS); §8.25 C1 relies on S0176's "distribution" but labels it a summarizer's interpretation (acceptable) |
| Authority's decision read through a producer's or registrar's gloss | B-17 (S0080 L10); the decision of 2026-10-27 is the Rule Reviewer's text plus a three-word adoption (labelled, §6.1) |
| Content from a non-allowed file reached through a summary | B-06; §8.25 and §8.26 lineage lines name which non-allowed files S0175/S0176/S0178/S0181 summarize (identification only, no content) |

## 10. Limits
- I did not read §8.1–§8.21, §9.1–§9.16 beyond searches and the cited cells (§9.13.2 designation paragraph, §9.15 K1, K2, L11 rows, status line). Coverage marks "(not read)" or "C?" rest on titles and search hits.
- I did not open the decision record `7ecf5a024`, PRINCIPAL-TESTIMONY, or any file in REVIEWER-NOTE item 4. I did not check the S0300, S0253, S1375, S0917, S0904 citations in §9.17 or the Ext references beyond what is cited.
- I cannot show that the checks 1–28 are unchanged from v17. I did not run the model with the book's checks removed or with other random seeds.
- The blind inventories are another reviewer's readings. I used them as the coverage basis and checked a sample of their line references (S0058, S0129, S0177–S0181, S0027, S0044, S0079–S0080, S2377) against the sources. I did not re-read the S0151–S0181 sources.
- A search miss is not absence. "MISSING" means I found no home by the searches listed below.

## 11. Files opened and searches run
Opened (all by full or partial read): W = `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/`; REV = `W/06-theory/manuscript/review/`; CP = `REV/CONSOLIDATION-S0151-S0181/`; BK = `CP/BOOK-UNDER-REVIEW/`.
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`; `CP/REVIEWER-NOTE.md`; `CP/SCOPE.md`; `CP/BRIEF.md`.
- Outside the list in the brief, disclosed: `CP/frozen-table.txt` (file list and hashes, opened and printed in full; no book text); directory listings (`ls`) of `CP/`, `CP/BOOK-UNDER-REVIEW/`, `REV/`, `BK/tmp/`, `BK/tmp/tools/` (names only). I did not open `CP/R3-OUTPUT.txt`. The persisted tool-output file of my own command, holding Ch. 5 text from the copy, was read from my session store.
- Five inventories: `REV/C29-S0151-S0158/STEP1-INVENTORY.md`, `REV/C30-S0159-S0164/STEP1-INVENTORY.md`, `REV/C31-S0165-S0169/STEP1-INVENTORY.md`, `REV/C32-S0171-S0176/STEP1-INVENTORY.md`, `REV/C33-S0177-S0181/STEP1-INVENTORY.md` (read to the "Files opened" line); `REV/C33-S0177-S0181/SETTLEMENT.md`.
- In `BK/06-theory/manuscript/`: chapter_04/04.00, 04.01, 04.02, 04.03, 04.99, TODO.md; chapter_05/05.00, 05.01, 05.02, 05.03, 05.04, 05.05, 05.06, 05.07, 05.99, TODO.md; chapter_08/08.22, 08.23, 08.24, 08.25, 08.26 (whole); chapter_09/09.17 (whole); 09.13 (grep and lines 40–60); 09.15 (grep and rows K1, K2, L11, status line); 09.12 (grep I13); chapter_09/TODO.md (rows T9-08, T9-09, T9-10); chapter_08/TODO.md (row T8-04); 08.19 and 08.20 (grep lines). `BK/06-theory/THEORY-MAP.md` (lines 1–40, 190–196, 354–380); `BK/06-theory/CONCEPT-REGISTER.tsv` (parsed by script; rows of the in-scope and cited concepts printed). Not opened: Ch. 0–3, Ch. 6, §8.1–8.21, §9.1–9.16 in full.
- In `BK/tmp/tools/`: `ce_model.py` (read in part and run), `ce_model-run-20261027-v18.txt`, `anchor_model.py` (run), `anchor_model-run-20261024-v2.txt`, and the tools below. I did not open any corpus basis file, `.env*`, `_batch_input*`, hold-out, sealed or quarantine file, nor `docs/knowledgeos/chronological-read/*`.
- Corpus (each path checked against `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, which I opened for lookup): S0023, S0024, S0026, S0027, S0028, S0029, S0040, S0044, S0058, S0077, S0078, S0079, S0080, S0129, S0158 (lines 213–222, 325–352), S0152 (lines 37–60), S0896 (L105, 781, 800, 1237), S2377 (selected lines), S0177, S0178, S0179, S0180, S0181. S0155, S0156, S0157, S0161, S0170, S0174: no row in the allowed list, not opened.

Searches and commands run:
- Tools on the copy: `register_home_check.py --root BK`; `undefined_terms.py --root BK` (read the tier-A/B lines); `corpus_quote_check.py` on Ch. 4, Ch. 5, §8.22–8.26 and §9.17 files; `inbound_citations.py --root BK` for "CON-0168", "CON-0113", "CON-0264"; `book_search.py` lexical runs for about 40 phrases: warn-only; did not check; outbox; Assurance Context; Communication Engineer|Knowledge Engineer|six-role; composition root; ACTIVE invariant; Linux; Advisor|Autonomous Operator; temporal end; federated; ProductVersion; EvidenceBundle; Spring|dependency injection; HANDOFF…ACTIVE; shared blindness; Hexagonal; Translator; IPO|open core; Digitalization Robot; LCOM4; idempot; R-CONFLICT; UNKNOWN ≠ …; independence graded; ADR-KOS-00; Method…Binding; absence of registration; Platform Composition|Capability Binding; Git for|knowledge lineage; roadmap|Rust; trivial; qualified for the scope; ArchitectureJudgmentRequired; attestable; NOT ATTESTED; Environment; Robot Skill.
- Greps in the copy (read-only): "last entry", "designat" in 09.13, "K1|K2|L11" in 09.15, "I13", citations of §4.1/CON-0070, §5.3/CON-0077, §5.4/CON-0080, §5.5, §5.6, §5.7 across chapters 6, 8, 9; "store" and "bypass" in ce_model.py.
- Python (`python3 -I`): cons_b_probe.py and cons_b_probe2.py (probes against ce_model, in the scratchpad, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`); inline csv parse of the register; `ce_model.py` full run (output diffed with the saved v18 run: identical); `anchor_model.py` full run (output diffed with the saved v2 run: identical).
- Corpus line reads by `sed -n` on allowed paths, about 120 lines in total across the sources listed above; `grep -c knowledge_tranfer` on CORPUS-ALLOWED.tsv (0).
- `awk` lookups of source IDs in CORPUS-ALLOWED.tsv for the ~50 IDs cited in Ch. 4/5/8/9 (all present except the six excluded).
- No file in the repository was edited. No outside service was used. I reproduced no private person's name.
