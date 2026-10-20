# REPORT-C2: turn 7, commission C, §9.15 v3 (reviewer C2, Sonnet)

*2026-10-20. Object: `chapter_09/09.15-the-trust-boundary.md` v3 in full; CON-0193; EXT-077–080; pointers in §9.8 and §9.9. Read for comparison only: §9.12 (I13, I15, 9.12.7), §9.13 (D, output, 9.13.7), §9.16 (R1–R7). Labels: Src / Ded / Int / Hyp / Ext. Facts, deductions and opinions are marked where they differ.*

## 1. Verdicts

| Claim | Verdict | Note |
|---|---|---|
| G1, G2, G3, G6 | sound with corrections | G6 and K5 interact with key binding (C2-01) |
| G4 | sound as now stated (conditional), but then it is not a guarantee of the kernel | C2-03 |
| G5 | not reviewed here (§9.14, commission B) | |
| K1, K3, K6, K7 | sound as stated | K3's input list is incomplete (C2-01) |
| K2 (v3 + note) | sound as a weakening; its cut is under-defined | C2-04 |
| K4 (v3 + note) | **not consistent** with §9.15.2's own criterion and with §9.16 R3 | C2-03 |
| K5 | inconsistent with §9.12.7 and §9.13.7 on key binding and custody | C2-01 |
| L1a–L7 | sound as located; L2 and L3 overlap K5 | C2-01 |
| R1 | holds with C2-01, C2-03 | |
| R2 | holds | |
| R3 | **proof valid; claim needs a restatement**, because marks now read a recorded attribution field | C2-02 |
| R4 | holds; signed-as-actor without P-perf is invisible to the marks | C2-05 |
| R5 | holds | |
| R6 | gap **partly closed**; text stale; I15 vs I13 tension | C2-06, C2-07, C2-08 |
| R7 | holds | |
| R8 | wording stale after §9.16 R7 | C2-09 |
| v2 corrections | each met, except the K/L partition (C2-01) | §3 |
| v3 decisions | applied as worded (decisions 2 and 3) | §4 |
| Turn-7 notes | G4 note follows; K2 note follows with C2-04; K4 note does not match K4's wording | C2-03, C2-04 |
| CON-0193 | stale | C2-10 |

## 2. Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| C2-01 | **Key binding and custody sit on both sides of the K/L line.** (Ded) §9.15.2's criterion: a kernel assumption is one whose failure can change what CE* answers by changing the record or its inputs. I13 (authentic) is read by D: a failing entry puts the record outside D and Q1 refuses. Whether an entry verifies depends on the key-to-party binding and on custody. §9.12.7 and §9.13.7 (Src) put "key binding and key custody behind the integrity check" in the assumptions of the answer, and §9.13.7 reads "the integrity check is sound (key binding, key custody)". Yet §9.15 R1 K5 reads only "the integrity check is sound (an implementation assumption)", R4 and R7 put L2 and L3 under legitimacy, and the mapping sends "key binding → L2" and "the integrity check → K5". A wrong binding table lets a forged entry pass and changes the answer: by the criterion that is a kernel assumption, like K3's wrong covers input. Also, K3 lists only the covers relation and the identity link; the key-binding data that I13 reads is an input missing from K1–K7, so "CE*'s answers depend on these and on nothing else" is not shown. | high | 09.15 R1 K3, K5; R4; R7 L2, L3; mapping; 09.12 §9.12.7; 09.13 §9.13.7 | State whether key bindings are an input of I13. If yes: add it to K3 and name it in K5, and say that L2 and L3 are the real-world facts behind it, not a second kernel-versus-legitimacy cell. If no: say what I13 checks, and why a wrong binding changes no answer. Align §9.13.7's wording. | one cell for key binding and custody; the same classification in §9.12.7, §9.13.7, R1 and R7; the K-list's "nothing else" is true by construction of the input list |
| C2-02 | **R3's proof is valid but its claim is ambiguous after I15.** (Ded) The proof (G1: CE* is a function of record and inputs; occurrence, attribution and completeness relate the record to the world) is correct for the **value**. But D now requires a recorder and an authentication status on every entry (I15), and Q1's **marks** depend on that recorded field. So an element of the answer does depend on recorded attribution, though not on real attribution. R3 says "no CE* answer depends on occurrence, attribution or completeness" without distinguishing recorded from real. Also, a record lacking the I15 field is outside D, so Q1 refuses it: the presence of the field changes the answer type. G1 still names "the record and the inputs covers and the identity link", where the inputs now include I13 and I15 data. Answer to question 1: R3 is a proof for the value only; I15 makes the **mark**, not the value, depend on recorded attribution. | high | 09.15 R3, G1; 09.12 I15; 09.13 output | Restate R3: "no CE* **value** depends on the **truth** of occurrence, attribution or completeness; the marks depend on the **recorded** authentication status (I15), a field of the record." State that I15's value is a claim of the entry (see C2-07). List the I15 and I13 data as inputs in G1. | R3 and G1 say "value" and "recorded", and CON-0193 repeats it |
| C2-03 | **K4 (and G4) do not meet the section's own criterion.** (Ded) The K-table is headed "CE*'s answers depend on these and on nothing else", and §9.15.2 defines a kernel assumption by what changes CE*'s answer. §9.16 R3 (Ded) shows evaluation needs no shared point, and consumption is not an admitted kind in D. So no CE* answer depends on K4 now. K4's wording is "one shared record for every act-consuming mechanism" (unconditional), while the turn-7 note and §9.16 R3–R4 say what is needed is a **serialization point per constraint**, "not one recorder", and only for prevention (K4-p) of a count-bounding law. The note narrows the scope but leaves the wording stronger than the derivation. G4 sits in a table of "what the kernel guarantees" though it is no guarantee of CE* on D; and the "refutable inside" cell says a double consumption "on one record shows" while the failure K4 describes is that the record is not shared. | high | 09.15 R1 header, G4, K4 cell; 09.16 R3, R4 | Move K4 (and G4) out of the CE* list into a separate row set "assumptions of Req's guards, conditional on a kernel single-use law (§9.16 R4)"; reword K4 as "one serialization point per count-bounding constraint, if its violations must be prevented"; fix the refutability cell (a failure shows only after a merge, as in K4-d). | K1–K7's header is true for each item, or K4 is marked conditional outside it, and the cell and wording agree with §9.16 R3 |
| C2-04 | **K2's note is consistent with K2, but "same entries at the cut" needs a definition.** (Ded) Under F26 (c) with ties by content, §9.16 R2 (b) gives the same answers for every linear extension, so the order need not be shared. But Q1's cut is `R≤s`, a position prefix (§9.13). Two readers with the same entry set and different orders have different prefixes for the same s. R2 (d) defines cross-record cuts as sets closed under citation; K2 should say the same. Also K2's main text still says "the same record and the same order"; only the italic note weakens it, and the "refutable inside?" cell speaks of forking only. Answer to question 5: K2's note is consistent only if the cut is a citation-closed set; K4's note is not consistent with K4's wording (C2-03). | normal | 09.15 K2; 09.16 R2 (b), (d); 09.13 Q1 | Reword K2: "every reader evaluates the same citation-closed set of entries (and, under F26 (a), the same order)". | K2's text is a set statement with the order as a condition on F26 |
| C2-05 | **Marks do not cover all the legitimacy assumptions R6 says they state.** (Ded) R6's last bullet: marks "state which legitimacy assumptions an answer rests on". But R4's second row (signed-as-actor, without P-perf: an act performed elsewhere and signed later) rests on L1a, and I15's three values cannot tell it from a signed-as-actor act under P-perf, because P-perf is a property of the act's kind, and "which acts are performed by recording" is undecided (§9.15.9). Such an answer carries no mark though it rests on L1a. L6 and L7 are never marked either. §9.15.10 asks only "should marks also name L2–L3". Answer to question 4: K1–K7 are not complete for answers with marks, because (a) key binding is missing (C2-01), and (b) the mark's value reads I15, which depends on the K5-type check (C2-07); a mark depends on nothing else outside the record, provided "rests on" (the support, Int, a superset) is computed from the record. | high | 09.15 R4 (row 2), R6, 9.15.9, 9.15.10; 09.13 Output | Say explicitly that marks are a **partial** list: they flag only the recorded modes signed-as-other, unsigned and tombstone; a signed-as-actor answer is unmarked even where it rests on L1a, L2, L3, L6, L7. Or add a P-perf flag per kind, with the Principal's decision. | R6's bullet says "marks flag the recorded modes", and no sentence reads the absence of a mark as "no legitimacy assumption" |
| C2-06 | **R6 gap: part remains.** (Ded) I15 fixes that a recorder is named and an authentication status given. It does not say (a) **who may transcribe for whom** (§9.15.9 keeps it as a governance rule), so a signed-as-other entry by any verifying key is accepted and marked, and the recorder's entitlement to transcribe is not checked (G2 is the named actor's entitlement only); (b) what I13 authenticates for signed-as-other, the transcriber's key (R4 says so) or the actor's. The text of R6 still presents the gap in its v2 form ("I13 does not say whose authorship") followed by the decision, without saying which part is closed. The estate's inconsistent `recordedBy` use (origin on STARTs, recorder elsewhere) is unaddressed for how existing records map to I15. Answer to question 3: part (b) is closed only if I13 is stated as "authenticates the recorder's signature"; (a) remains open and is named in §9.15.9. | normal | 09.15 R6, 9.15.9; 09.12 I13, I15 | Add to R6: "closed: a recorder and status exist on every entry; open: who may transcribe for whom (§9.15.9); I13 authenticates the recorder's signature (stated)". | R6 splits the gap into a closed and an open part |
| C2-07 | **I15 against I13: is "unsigned" reachable in D?** (Ded) I13 reads "whether an entry is intact and **authentic**"; D refuses a record with an entry that fails it. If authentic means verifies under a key, every unsigned entry fails and the record is outside D, so "unsigned is an allowed value" and the mark on unsigned entries could never arise on D. If an unsigned entry passes I13 vacuously, then I13's authenticity covers only the signed ones, and a transcriber who declares "signed-as-actor" on an entry that verifies under another key (or none) would be refused only if I13 cross-checks the declared status with the verification. R4 defines modes by "what the record shows", and I15 makes it a recorded field, but no text says whether I15's value is **derived** from the verification or **declared** and then checked. If declared and unchecked, the mark depends on an unchecked claim; the model check 17 is not described as testing this (I did not run it). | high | 09.15 R4, R6; 09.12 I13, I15; 09.13 D | State: I15 is a declared field; I13 checks that it agrees with the verification result (signed-as-actor iff verifies under the actor's key; signed-as-other iff under another bound key; unsigned iff no signature), and that unsigned entries pass I13's authenticity by definition. Add a countermodel row to the model if not present. | one sentence in §9.12 or §9.13 fixing the relation of I13 to I15, and a model check for a mismatching declared status |
| C2-08 | **Stale text in R6.** (Src) "Attribution default ... Until an entry names its recorder and authentication, its legitimacy is read under the unsigned row": after I15 such entries are outside D and Q1 refuses them, so the default has no case on D. Likewise "the real fail-closed option would be for Q1 to mark, or to refuse": decided, but the bullet is left in the future. | normal | 09.15 R6 bullets 3–4 | Mark the default as superseded by I15 (applies only to records outside D, for reading the estate) and past-tense the option. | R6 has no sentence that contradicts the decision |
| C2-09 | **R8 (c) is out of date.** (Src) R8 (c): "no test separating a primitive from a world assumption has been stated (§9.9.7)". §9.16 R7 now states one (proposed, Hyp). §9.15.10's last question is also now answered in form. R8 (a), (b) say no more than the premises allow: they follow from R3 under DB-1. (Ded) R7's use of §9.15 R3 for "occurrence is read by no reference definition" extends a result about CE* to every Req definition (O1–O4); O3's marked absences are evidence of completeness and are read under as-fact (09.12 line 87). That is covered by R7's own as-fact clause, but should be said. Answer to question 2: R8 still says no more than the premises allow, but it no longer says what has been done. | normal | 09.15 R8 (c), 9.15.10; 09.16 R7 | Reword R8 (c): "a test is proposed in §9.16 R7 (Hyp, returned); not adopted; so not decided". In R7, cite DB-1 and R3 for CE* and name O1–O4 separately. | R8 (c) names §9.16 R7 and its status |
| C2-10 | **CON-0193 is stale.** (Src) The register row still says "D names no recorder and I13 does not say whose authorship it authenticates (returned to the Principal)", lists "recorder field, authentication status, and whether Q1 marks or refuses" as open for the Principal, and states G4 and K4 unconditionally in the body, though the history cell records the turn-7 notes. It also says "reviewed at turn 6 ... not re-reviewed" (true) but does not mention v3. EXT-077–080 entries agree with the section (no defect found in the register text; I did not check the outside works, which are Ext). | normal | CONCEPT-REGISTER.tsv line 194 | Update the body and the "open" cell to v3. | the row matches §9.15 Status and R6 |
| C2-11 | **R5's tombstone result holds by construction.** (Ded) §9.16 R5 step 1: Φ reads only kind, citations and the fields D requires; a tombstone keeps exactly those. The required-field list in §9.13.2 contains everything Φ reads (actor, cited bases, target, scope, act time, holder, period, item, validity), so "dropped content" is whatever lies outside it. The claim is true, but it is a design consequence of "keep what D requires", not a discovery; and the entry that performs the removal is itself an entry whose authorization (a loss act) is not defined in §9.15. K1's "a tombstone is a recorded change" does not say who may record it, and a tombstone recorded as **unsigned** is allowed by I15. | normal | 09.15 R6 bullet 5, K1; 09.13 D | Say in R6 that removal authority is not defined (open under F30), and that the tombstone's own mark applies. | R6 or §9.15.10 names the open authority question |

## 3. The v2 corrections, each checked

| v2 correction | Met? |
|---|---|
| 1 modes by what the record shows; P-perf Int | yes (but see C2-07 on the I15 relation) |
| 2 R3 | yes for values (C2-02) |
| 3 R8 | yes, stale wording (C2-09) |
| 4 one criterion | stated; K5/L2/L3 and K4 do not follow it (C2-01, C2-03) |
| 5 K1–K7 with refutability | present; incomplete (C2-01); K4 cell (C2-03) |
| 6 G6 is the record alone | yes |
| 7 refutability cells follow B3 and D | yes for L1a (denial not admitted in D, B3 gives T) |
| 8 L1a, L1b split | yes |
| 9 R5 | yes |
| 10 L6, L7 | yes |
| 11 R6 | decided; part open (C2-06, C2-08) |
| 12 37 files accounted | I counted the table rows: 5 + 5 + the grouped rows add up to 37 by the list given (S0071, S0073, S0090, S0092, S0101, S0106, S0102, S0119, S0120, S0122, S0164, S0330, S0335, S0343, S0381, S0385, S0387, S0012, S0361, S0627, S2576, S0202, S0630, S0930, S1049, S1513, S1798 = 27, plus 10 read = 37). Yes. |
| 13–16 | yes as stated; not re-derived |

## 4. The Principal's decisions, as worded (practice 5)

| Decision | Applied as worded? |
|---|---|
| 2: "D requires both fields on every entry, separately from the actor; 'unsigned' is an allowed value. Q1 marks answers that rest on transcribed or unsigned entries; it does not refuse them." | Yes: §9.13.2 (every kind), §9.12 I15, §9.15 R6. No addition beyond the wording except the claim that marks "state the legitimacy assumptions" (C2-05) and the unresolved I13 relation (C2-07). |
| 3: "by tombstone ... identity, kind and the fields citations rely on ... drops the content. The record stays in D, and answers ... marked 'rests on removed content'. ... partial answer to F30." | Yes: §9.13.2, §9.15 Status and R6, §9.16 R5. The text says "fields D requires"; the Principal said "fields citations rely on". The two coincide only if Φ's reads equal D's required fields; C2-11 notes it is by construction. |
| 1: not in §9.15. | n/a |

## 5. Proof-step tables

**R3 (value only; adequate with C2-02)**

| Step | Claim | Check |
|---|---|---|
| 1 | CE* is a function of the record and the inputs (G1; §9.13 R1) | valid on D, for Θ_f |
| 2 | occurrence, attribution, completeness relate the record to events outside it | definition; valid |
| 3 | changing them with record and inputs fixed cannot change CE*'s value | valid by 1–2 (no use of marks) |
| 4 | under DB-1 the norm is what CE* computes | premise |

**R5 / §9.16 R5, a tombstone changes no answer (adequate)**

| Step | Claim | Check |
|---|---|---|
| 1 | Φ and the currency rule read only kind, citations and D's required fields (§9.13.2–3) | holds by the field list; target is included |
| 2 | a tombstone keeps exactly these | by the rule |
| 3 | so Φ, its fixed point and every answer at every cut are unchanged; only the marks change | valid |
| premise | the integrity check is defined on the tombstone (K5) | stated; ties to C2-07 |

**R4 (signed-as-actor under P-perf ⇔ L2 ∧ L3)**: (1) verifies under a key bound to the named actor; (2) written by that actor iff binding right and no one else used the key; (3) under P-perf, writing is the act. Valid, given P-perf (Int). Interaction with I13: C2-01.

## 6. Coverage

- **Read in full:** §9.15 v3; the changed lines of §9.12 (I13, I15, 9.12.7), §9.13 (D, Output, 9.13.7); §9.16 R1–R7; CON-0193 and CON-0194 rows; EXTERNAL-SOURCES rows EXT-077–080; §9.8 H4/H5 pointer lines.
- **Not done:** I did not run `ce_model.py` (checks 17, 18) or `helly_check.py`; I did not re-run the quote check; I did not open any corpus file (quotes and line numbers are taken as given); no outside works were looked up (the Ext rows are checked only for consistency with the register); §9.9 result 5 pointer read only through §9.15 and §9.16; the mapping to earlier lists (§9.11.5, §9.13.7) checked only for K5/L2/L3 and I13.
- **Not reviewed:** G5 (commission B); R7 of §9.16 beyond its use here (commission D).
- **Limits:** C2-07 is a gap in the text; whether I13 vacuously passes unsigned entries is not stated anywhere I read, so it may be settled in a place I did not open. Severity is critical / high / normal (R5); no critical finding.
