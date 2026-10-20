*Report of reviewer C1 (Opus), returned as hand-back text, verbatim (saved by the Professor, 2026-10-20; the reviewer's Write call was refused).*

The report was not written to disk. The harness refused the Write to `W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-C1.md` ("Subagents should return findings as text"), so the full report is below for you to save. I edited no file. My only scratch file is `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t7c1_probe.py`.

**Summary (10 lines):**
1. 13 findings: 0 critical, 4 high (C1-01, 02, 03, 08), 9 normal.
2. C1-01 (high): no K or L assumption covers the marks. In D and the model, the authentication status is a recorded value taken as is (no key material, no check), so a mark is only as true as that field.
3. C1-02 (high): R4's "the actor wrote it ⇔ L2 ∧ L3" is invalid. "⇒" fails because L3 covers the whole key, not this entry. "⇐" needs unforgeability, which is not in L1–L7. Settlement row C1-01 is therefore not met.
4. C1-03 (high): the L1a cell contradicts D. A recorded denial takes the record out of D, so Q1 refuses instead of "nothing changes (T)". The probe confirms it.
5. C1-08 (high): the K4 note contradicts K4's own wording, which is too strong (one record, not one point per constraint) and too narrow (consumption only). G4 is false under K4-d, and within one application it rests on K6.
6. Q1: R3 is a valid proof but definitional under DB-1. I15's recorder field is read by nothing, and the marks read the recorded status, a claim, not attribution (C1-04).
7. Q2: R8 (c)'s conclusion "not decided" stands, but its premise "no test stated" is stale since §9.16 R7, and §9.9's pointer is stale too (C1-07).
8. Q3: the Principal's part of R6 is closed. Still open: who may transcribe for whom, and how the recorder relates to the signing key. R6's text and CON-0193 are stale (C1-05, C1-06).
9. Q5: the K2 note holds only for cuts named by their entries, and only once a content tie rule is chosen (C1-09). Tombstones are missing from K5 and G3 (C1-10).
10. Quote check on §9.15 re-run: 13 found, 0 not found. EXT-078, 079 and 080 agree with public records; EXT-077 not checked.

---

# Turn 7, commission C: report of reviewer C1 (Opus)

*2026-10-20. Object: §9.15 v3 in full, CON-0193, EXT-077–080, and the pointers in §9.8 (H4/H5) and §9.9 (result 5). I read COMMON.md and BRIEF-C-TRUST-V3.md first, and no other turn-7 report.*

*Labels: **Fact** is what I observed in a file or a run; **Ded** is what I derived; **Op** is my opinion.*

## 1. Verdict

| Claim or item | Verdict | Finding |
|---|---|---|
| G1 (CE* a function of record and inputs) | holds for the **value**; for marks, only if the authentication status is read as a recorded claim | C1-01, C1-04 |
| G2, G5, G6 | hold ("contradiction" in G6 is vague, not wrong) | — |
| G3 | holds, with a gap on tombstones | C1-10 |
| G4 with its turn-7 note | **incomplete**: false as worded under K4-d; within one application it rests on K6, not K4 | C1-08 |
| K1–K7 for answers with marks (Q4) | **incomplete**: a mark's correctness rests on the status field, and no K or L covers that | C1-01, C1-10 |
| K2 note (Q5) | follows from §9.16 R2 (b) only for cuts named by their entries, and only if a content tie rule is chosen (a θ value, not decided); the cell states the strong form first | C1-09 |
| K4 note (Q5) | **inconsistent** with K4's wording ("one shared record for every act-consuming mechanism") | C1-08 |
| §9.15.2 criterion | its two clauses are not exclusive as worded: L4 meets the first | C1-11 |
| R2 | holds (Ded, under A1 recognized) | — |
| R3 (Q1) | a valid proof, but definitional. It holds for values. I15 creates no dependence on attribution. R3 does not say it covers marks | C1-04 |
| R4, signed-as-actor under P-perf, "⇔ L2 ∧ L3" | **invalid as stated** | C1-02 |
| R4, signed-as-other | assumes L1a even when the transcriber witnessed the act | C1-12 |
| R5 | holds (Ded, definitional) | — |
| R6 after v3 (Q3) | the decision is applied as worded; the surrounding text is stale; part of the gap remains | C1-05, C1-06 |
| R7, the L1a cell | **contradictory** for CE* on D | C1-03 |
| R7's mapping lists | hold against §9.11.5 and §9.13.7 | C1-13 (naming only) |
| R8 (Q2) | (a) and (b) hold. (c)'s conclusion holds, but its premise "no test has been stated" is false since §9.16 R7; §9.9's pointer has the same problem | C1-07 |
| v3 decisions | applied as worded (D, Q1 marks, tombstones), with the unstated reading "transcribed = signed-as-other" | C1-05 |
| v2 corrections (16 settlement rows) | 15 met; row C1-01's "⇔" not met | C1-02 |
| CON-0193 | stale on R6 and G4 | C1-06 |
| EXT-077–080 | 078, 079 and 080 agree with public records; 077 not checked | coverage |
| §9.8 pointer | agrees with R2 and R5 | — |
| quote check, §9.15 | Fact: 13 found, 0 not found (re-run) | — |

## 2. Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| C1-01 | **No K or L assumption covers the marks (Q4).**<br>§9.15.2 defines the modes by verification: signed-as-actor "verifies under a key that the record binds to the named actor". A record in D cannot express that:<br>• no admitted kind binds a key;<br>• the only inputs are covers and the identity link.<br>In D and in the model, the status is a recorded value taken as is. So R6's "R4's mode can be read from the record for every entry" holds only for the *claimed* mode. An unsigned entry labelled signed-as-actor gets no mark. Neither K5 nor I13 says that the integrity check verifies the status | high | Fact: `in_D` checks only `auth in AUTH`; `marks` reads `auth`.<br>Fact: §9.13.2's kind list; G1's inputs.<br>Ded: a mark depends on the truth of a field that D does not check | Either:<br>(a) define the status as a recorded claim and add an assumption that it equals the verification result; or<br>(b) make the verification material (keys, bindings, revocations: B3's second parameter) an input, extend K3 to it, and extend K5 to verifying the status.<br>Either way, say whether G1's "answer" includes the marks | the K list (or a new K8) names what makes a mark correct; G1's inputs match |
| C1-02 | **R4's "the named actor wrote it ⇔ L2 ∧ L3" is invalid.**<br>*⇒ fails, two ways:*<br>• L3 is stated for the whole key ("no other party used the key before its revocation"). The actor writes e with their own key, which a thief also used for another entry f: the actor wrote e, yet L3 is false.<br>• The actor writes e with a key bound to them that belongs to someone else: they wrote e, yet L2 is false.<br>*⇐ needs more:* signature unforgeability, and a correct verifier (K5). Neither is in L1–L7.<br>So settlement row C1-01's correction is the step that fails | high | Ded: the countermodels above.<br>Fact: R4's table and proof; L3 in R7; CON-0193's "reduces to L2 and L3" | • Make L3 per entry: "no other party used the key for this entry".<br>• Add unforgeability (L8), or fold it into L3 explicitly.<br>• State R4 as "⇐, given L2, L3(e), unforgeability and K5" | R4's proof states each direction with its premises, and the L list names unforgeability |
| C1-03 | **The L1a cell contradicts D.**<br>The cell says a recorded denial "changes nothing (§9.12 B3: T)", and also that a denial is not an admitted kind, so the record is outside D. For CE* on D, outside D means Q1 refuses every question on the record. So the denial does change the answer: T becomes a refusal. B3's T belongs to CE on a domain that admits evidence entries.<br>Side effect: since D is not closed under appending, any party that can append a non-admitted kind can block Q1. Only K1 guards against that | high | Fact (probe):<br>• before the denial: in D, CE*(c, 3) = T;<br>• after appending kind `deny`: `(False, 'kind deny not admitted (d)')`.<br>Fact: B3's counterexample; §9.13.2's "Not admitted" list | Rewrite the cell:<br>• under D, a denial puts the record outside D and Q1 refuses with the reason;<br>• B3's T is CE on an evidence-admitting domain;<br>• L1a stays unrefutable inside;<br>• blocking by non-admitted appends is a K1 availability cost | the cell states one outcome, consistent with §9.13.2 and the model |
| C1-04 | **R3 is valid, but its scope is not stated (Q1).**<br>The proof (CE* = f(record, inputs), and the three are not arguments of f) shows only that CE* reads no world fact. The content comes from DB-1.<br>On I15:<br>• the recorder field is required but read by nothing;<br>• the marks read the recorded status, a claim about attribution, not attribution itself.<br>So R3 extends to marks only through C1-01's reading. R8 (a)'s "dissolved" is definitional | normal | Fact: `marks` reads `auth` and `removed`, never `recorder`; `in_D` checks only that `recorder` is present.<br>Proof table §3.1 | • State R3 for "values and marks".<br>• Add that the marks depend on recorded fields that are claims about attribution.<br>• Label R8 (a) "Ded, definitional under DB-1" | R3 names its scope and cites C1-01's resolution for the marks |
| C1-05 | **The marks claim more than they are.**<br>(i) R6 says the marks "state which legitimacy assumptions an answer rests on". By §9.13.3 the support is a *superset* of what the value needs: an unsigned annulment that is F anyway still marks the answer.<br>(ii) The Principal's "transcribed" is read as signed-as-other only, and the text does not say so. Governance registering a START the human signed (recorder ≠ actor, signed-as-actor) gets no mark. Op: the reading is defensible.<br>D also does not require the recorder to be the party whose key signs a signed-as-other entry | normal | Fact (probe):<br>• an annulment by Q, citing nothing, unsigned: CE* = T, marks `['rests on unsigned entries a']`;<br>• recorder 'Gov' with signed-as-actor: in D, marks `[]`.<br>Fact: §9.13.3, "a superset of what the value strictly needs" | • Say "a superset" in R6.<br>• State the reading "transcribed = signed-as-other; the recorder drives no mark".<br>• Decide whether recorder must equal signer | R6 and §9.13.3 agree on "superset"; the reading of "transcribed" is stated where the marks are defined |
| C1-06 | **R6 and CON-0193 are stale, and part of the gap remains (Q3).**<br>*Stale text:*<br>• R6's heading still says "returned to the Principal";<br>• its first bullet says "D's entries name an actor, not a recorder", false since v6;<br>• "That is the gap" is still in the present tense;<br>• §9.15.8 lists "the recorder gap in D";<br>• CON-0193's meaning still says "D names no recorder … returned", its follow_up lists the recorder as open, its history has no v3 line, and its meaning lists G4 unconditionally.<br>*What remains open:*<br>(a) what verifies the status (C1-01);<br>(b) who may transcribe for whom: G2 checks only the named actor's entitlement, so a signed-as-other entry by *any* party counts as the actor's act, with only a mark;<br>(c) how the recorder relates to the signing key | normal | Fact: R6 lines 115–123 against §9.13.2 (v6 recorder field); CON-0193's columns.<br>Ded: (b) from Φ, which reads the actor and the holder, never the recorder | • Recast R6 as "decided (v3); what remains".<br>• Move (a)–(c) to §9.15.9 or §9.15.10.<br>• Update CON-0193 (meaning, follow_up, history; G4 conditional) | no sentence in R6 is falsified by I15; CON-0193 agrees with §9.15 v3 and §9.16 |
| C1-07 | **R8 (c)'s premise is out of date (Q2).**<br>R8 (c), §9.9's result-5 pointer and §9.15.10 all say no test has been stated. §9.16 R7 proposes one. The conclusion "not decided" still holds.<br>Also: R7's step "under DB-1 occurrence is read by no reference definition (§9.15 R3)" needs more than R3. R3 is about CE* only, not O1–O4, and "depends on" is not "reads" | normal | Fact: R8 (c); §9.9 line 126; §9.16 R7, "What it gives" | • R8 (c): "a test is proposed (§9.16 R7), not adopted; not decided until then". Same in §9.9 and §9.15.10.<br>• Note the premise R7 needs about O1–O4 | the three places point to §9.16 R7 as "proposed" |
| C1-08 | **The K4 and G4 notes are inconsistent with K4's and G4's wording (Q5).**<br>*K4* says "one shared record for every act-consuming mechanism". §9.16 R3 says prevention needs "one serialization point per constraint … It does not need one recorder", and it covers law 26 (assessments, not consumption) and role exclusivity. So K4 is too strong (one record) and too narrow (consumption only). The note does not fix the wording.<br>*G4:* under K4-d, two consumptions are recorded and the earlier one counts (R3). So "no act consumed twice" is false of the world; it holds only as "counted once". Within one application (law 9b), G4 rests on K6's serialisation, yet its "rests on" cell lists K4 alone | high | Fact: K4's row; §9.16 R3's prevention and detection bullets; §9.6 line 244 (the one-recorder rule invalid; one lock consulted by several paths suffices).<br>Ded: the K4-d case above | • K4 := "for each kernel law bounding a count across applications whose violations must be prevented, one serialization point shared by its writers".<br>• G4 := "once consumption is admitted: no act **counted** twice (K6 within an application; K4-d across applications); no act consumed twice only under K4-p".<br>• Update CON-0193 to match | K4's and G4's own cells carry the conditions; G4's "rests on" lists K6 |
| C1-09 | **The K2 note needs two conditions it does not state (Q5).**<br>(i) A cut is R≤s, named by a *position*. In different orders the prefix up to s holds different entries. So "the same entries at the cut" requires cuts named by their entries (one prefix per record, R2 (d)), and Q1's cut parameter must change.<br>(ii) §9.13.2 still breaks ties by position; the content tie rule is a reserved θ value. So today K2 holds in its strong form, and the weakening waits on a choice. The cell states both forms side by side | normal | Fact: §9.13.3 "CE*(c, x, t; R≤s)"; the order key (t_x, s_x); §9.16 R2, "a value of θ".<br>Ded: (i) from the definition of a cut | K2 := "readers agree on the entries of the cut, the cut named by its entries; with ties by position (the rule now), also on the order" | K2 states one condition per tie rule, and the cut parameter is defined without positions where K2 is weakened |
| C1-10 | **Tombstones are not in the K list or in G3.**<br>(i) §9.16 R5's premise (the integrity check is defined on the tombstone) is not in K5. A signature over content cannot verify once the content is dropped.<br>(ii) G3 says removal is "by a recorded act with a loss entry". No removal kind is admitted to D, and the model rewrites the entry in place. So who removes, and whether removal is an entry or a kernel step, is open. K1 then needs tombstoning to be a kernel step.<br>(iii) An unauthorized tombstoning changes no value but adds a mark, so the marks also rest on K1 | normal | Fact: `tombstone()` pops `content` and sets `removed` in place; §9.13.2's kinds; G3's wording; §9.16 R5's premise | • Add "defined on tombstones" to K5.<br>• Make removal either an admitted kind (with actor, recorder, status) or a kernel operation under K1, and reword G3 to match | K5 and G3 name the tombstone; the way a removal is recorded is stated |
| C1-11 | **The §9.15.2 criterion is not exclusive as worded.**<br>Its first clause makes a kernel assumption one whose failure changes CE*'s answer "by changing what the record … is". L4 meets it: an annulment that occurred but was never recorded changes the answer. Only the second clause ("correct by the norm") moves L4 to the legitimacy side.<br>The line also moves with I11: per §9.12 B3, if the identity link is defined by key binding, L2 would become a kernel assumption | normal | Fact: §9.15.2; §9.12 B3's last bullet.<br>Ded: L4 meets clause 1 | Rival wording (recommended): a kernel assumption is one whose failure makes the record or the inputs differ from the entries the kernel's own steps accepted; a legitimacy assumption relates that record to the world. Note the I11 dependence | every K row and every L row meets exactly one clause |
| C1-12 | **R4's signed-as-other row assumes too much.**<br>It requires both L1a and L1b. If the transcriber witnessed the act, the actor's testimony is not used, so L1a is not needed. The row's own gloss of L1b already has an "or" | normal | Fact: the row.<br>Ded: the witnessed branch uses no testimony of the actor | "L1b; and L1a in the affirmation branch" | the row is stated per branch |
| C1-13 | **One label, two concepts.**<br>• §9.6's K1–K4 are questions (K2 = identity mechanism, K4 = uniqueness).<br>• §9.15's K1–K7 are kernel assumptions (K2 = single view, K4 = shared record).<br>• §9.16 uses both: R2's "content-derived identity (K2)" is §9.6's K2; R1's "K2 (a single view)" is §9.15's.<br>• §9.15's K4 note cites §9.16 R3, whose K4 is §9.6's question | normal | Fact: §9.6 lines 116–119; §9.16 R1 and R2; §9.15 R1 | Rename §9.15's list (e.g. KA1–KA7) or qualify every use | no K label is ambiguous |

**Counts:** 13 findings: 0 critical, 4 high (C1-01, C1-02, C1-03, C1-08), 9 normal.

## 3. Proof-step tables (derivations judged adequate)

**3.1 R3** (adequate, with C1-04's scope)

| Step | Claim | Justification | Label |
|---|---|---|---|
| 1 | on D, CE*'s value is fixed by the record, covers, the identity link and Θ_f | G1; §9.13 R1; §9.13.3 | Ded |
| 2 | occurrence, attribution and completeness relate the record to events outside it; none is an argument of CE* | §9.15.2; D's fields | Ded (definitional) |
| 3 | vary the world with step 1's arguments fixed: the value is unchanged | steps 1, 2 | Ded |
| 4 | the marks are functions of the support and of the recorded `auth` and `removed` fields, so they are unchanged too | §9.13.3; model `marks` | Ded, given C1-01's reading |
| 5 | under DB-1 the norm is recognized standing, which is what CE* computes | DB-1 | Ded (definitional) |

**3.2 R2** (adequate)

| Step | Claim | Justification | Label |
|---|---|---|---|
| 1 | H4 = occurrence ∧ attribution ∧ entitlement | H4's wording | Ded |
| 2 | entitlement = authorized(x), relative to the inputs | Φ | Ded |
| 3 | occurrence and attribution are unread by CE* | R3 | Ded |
| 4 | under A1-as-fact, entitlement is the actual performer's, so it needs the other two | R2 | Ded |

**3.3 R5** (adequate)

| Step | Claim | Justification | Label |
|---|---|---|---|
| 1 | under A1 recognized and C1, a standing change is recognized only if a recorded act effects it | §9.12.4 | Src (definition) |
| 2 | so H5 restricted to recognized standing holds | step 1 | Ded (definitional) |
| 3 | a late transcription with an earlier act time changes earlier valid times, seen from later cuts | the order key (t_x, s_x); the cut parameter | Ded |
| 4 | an outside binding decision with no entry falsifies A1-as-fact, not R5 | step 2 is definitional | Ded |

**3.4 R4, the unsigned and signed-as-other rows** (adequate in direction; see C1-12)

| Step | Claim | Justification | Label |
|---|---|---|---|
| 1 | the act, or the actor's affirmation, is outside the entry | the modes | Ded |
| 2 | any evidence of it inside is a further entry | D's kinds | Ded |
| 3 | so T2.2's regress applies; corroboration is refutable only, within its scope | T2.2 | Ded |

R4's signed-as-actor row is **not** adequate (C1-02).

## 4. The brief's questions

1. **R3:** valid from G1, and definitional under DB-1. I15's recorder field is read by nothing. The marks depend on the recorded status, a claim, not attribution (C1-04), provided C1-01 is resolved.
2. **R8:** (a) and (b) are within the premises, and (c)'s conclusion stands. Its premise is stale given §9.16 R7 (C1-07).
3. **R6:** the Principal's part is closed. Three things remain: who may transcribe for whom, how the recorder relates to the key, and what verifies the status (C1-06, C1-05, C1-01).
4. **K1–K7 with marks:** not complete. A mark depends on the truth of the status field (C1-01) and on how tombstones are handled (C1-10). With D as it stands, that is either a recorded claim or key material outside the record.
5. **The turn-7 notes:** the K2 note follows from R2 (b) only under unstated conditions (C1-09). The K4 note contradicts K4's wording, and G4 needs K4-p or a weaker wording (C1-08). The G4 note follows from §9.13.2.

## 5. Coverage

- **Read in full:** §9.15 v3; §9.16; COMMON.md; the brief; TURN6-TRUST/SETTLEMENT.md §0 and §3–§5.
- **Read at the relevant parts:** §9.13.2–3 and §9.13.7; §9.12 I13, I15 and §9.12.4 (B2, B3); §9.11.3 law 24 and §9.11.5; §9.8 H4/H5 and the pointers; §9.9 result 5 (line 126) and §9.9.10; §9.6 K1–K4 and items 1–4.
- **Model:** I read `ce_model.py` v7 (`Record`, `in_D`, `tombstone`, `support`, `marks`, check 17) and the run's checks 17–18.
  - My probe loads the model read-only and builds three records. Its outputs are quoted in C1-03 and C1-05.
  - I did not re-run the full models.
- **Quote check:** §9.15 gives 13 found, 0 not found.
  - The tool opened only cited sources.
  - I first checked that S0078, S0090, S0095, S0103, S0206 and S0336 are in CORPUS-ALLOWED.tsv.
  - I opened no corpus file myself.
- **Register:** CON-0193 and CON-0194; EXT-077–080.
- **Outside works**, through public pages only, with no book or corpus text sent:
  - EXT-078: Crossref gives TISSEC 2(2), 159–176, May 1999. It agrees.
  - EXT-079: the authors' USENIX 2009 PDF covers an untrusted logger, auditors and forking. It agrees; page numbers not checked.
  - EXT-080: the RFC Editor page gives Laurie, Langley and Kasper, June 2013, Experimental, obsoleted by RFC 9162, with the "refuse to honor certificates that do not appear in a log" wording. It agrees.
  - EXT-077 not checked.
- **Not done:**
  - §9.10 O1–O4 not read; they bear on C1-07's last sentence.
  - No basis files read.
- **Settlement rows against v3:** all 16 checked. Fifteen are met; row C1-01's "⇔" is not (C1-02).
