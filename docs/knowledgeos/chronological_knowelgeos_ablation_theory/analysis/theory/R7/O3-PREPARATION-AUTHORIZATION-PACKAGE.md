# R7 · O3-preparation authorization package (draft r1.1)

**Status: PROPOSED — for Human ARB act; draft r1.1.** 2026-10-11. Review history:
- r0 had a fresh independent review: PASS_WITH_LIMITATIONS, with material findings F1–F4 and minor findings m1–m10. r1 applies them (§9).
- r1 had a fresh verification: PASS_WITH_LIMITATIONS, with no blocker and no material finding, and ten minor findings n1–n10.
- r1.1 applies n1–n10 (§9). **The n-fixes have not been re-reviewed.** **It decides nothing.** It asks the Human ARB for one separate act, open item **(e)** (F-LOG-0269, F-LOG-0271, F-LOG-0275, F-LOG-0278): authorization to **prepare** an O3 proposal, which is the document that would ask for the E3 gate (bounded architecture derivation), together with that proposal's review. **It does not authorize E3.**

**Placeholders:**
- `F-LOG-[act]` is the next free log id, filled when the act is recorded.
- `r[n]` is the revision of this package as reviewed and presented to the human.

**Inputs (authoritative):**
- `F-GOVERNANCE-LOG.md`: F-LOG-0248, F-LOG-0253 to F-LOG-0278, and F-LOG-0219 (stop-rule precedent);
- `R7/VP-DECISION-REGISTER.md` r1.19 (§0, §2, §3, §5a);
- `R7/ARB-DECISION-PACKAGE.md` r4.4 (§B, §C, §E2, §E3, §E3b, §F);
- `R7/E2b-ADOPTION-TEXT.md` r1.2 (§3, §6, §A1);
- `R7/E3-PREREQ-VP-DECISION-PACKAGE.md` r4.1 (§6);
- `R7/T3-CANDIDATE-MODEL.md` r1.8;
- `R7-AUTHORIZATION-BRIEF.md` r3 (format precedent; Λ1–Λ7).

**Tags:** quotations are verbatim from the cited source. **[E]** marks an analyst reading. Nothing here is a recommendation of any option.

**Standing qualifier (applies to every statement here):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

---

## 0. What O3 is (definitions as recorded)

| source | text |
|---|---|
| register r1.19 §0, O3 | "prepare an architecture authorization proposal (architecture itself is unauthorized, F-LOG-0248). **[r1.2]** O3 is the *preparation* of the proposal that asks for the bounded-architecture-derivation gate (ARB package §E3); the derivation itself is E3 and needs its own human act. Both need the same minimal VP set" |
| register r1.19 §3, O3 note | "Boundaries, participant placement and groupings are what an architecture proposal would map onto. **Preparing such a proposal needs its own human act; architecture and R8 are not authorized** (F-LOG-0248). R4 offers no consistency notion for VP11 (§3b), so VP11 needs a design decision, not a derivation" |
| ARB r4.4 §E3, scope | "derive an architecture proposal from the adopted bounded model only. **Relation to register O3:** O3 is the *preparation* of the authorization proposal for this gate; E3 is the gate itself (the derivation). Both use the same minimal VP set" |
| F-LOG-0248 | "**Not authorized:** architecture, implementation, canonicalization (R8). Also V2, V5, V6, E-A5, and any new evidence." |

**Two different "proposals" [E]:**
- the **O3 proposal**: the authorization proposal, and the object of this package;
- E3's **architecture proposal**: ARB §E3 "permits": "an architecture **proposal** for human review".

This act concerns only the first.

---

## 1. Readiness check against the recorded prerequisites

### 1a. Register §3, O3 minimal set ("VP15, VP5, VP14, VP10, VP12, VP11")

| VP | status | log id | note |
|---|---|---|---|
| VP15 | DECIDED (i): R4's five modelling choices, each "a modelling choice, never a corpus fact" | F-LOG-0255 (supplement F-LOG-0256) | "the working basis for the bounded … model **only**" (F-LOG-0256 correction) |
| VP5 | DECIDED (i): one-corpus basis | F-LOG-0257 | "no generality is claimed" |
| VP14 | DECIDED P-c (reopened; supersedes F-LOG-0259) | F-LOG-0265; T3 re-derived F-LOG-0266/0267; E2b amended F-LOG-0275/0276 | the premise "neutral under 𝐑₀" is not withdrawn. T3 r1.7 [E] records that it holds under S′/S″ only (F-LOG-0271) |
| VP10 | (iii) re-affirmed; **counts for O3 by human ruling, not on evidence** | F-LOG-0263; F-LOG-0264; register/ARB synced F-LOG-0277/0278 | F-LOG-0264: "The contested status in package §1.7 is resolved by this human ruling. It was not resolved by evidence." Register §5a keeps "O3: yes" as recorded; VP10 is never marked "DECIDED" (F-LOG-0278) |
| VP12 | DECIDED (i): one shared participant spanning CB1 and CB23 | F-LOG-0268; re-check F-LOG-0270; disposition F-LOG-0271 | no spanning rule selected; the H-D asymmetry under S "remains documented, unresolved, and unaccepted" |
| VP11 | DECIDED (ii): CG1–CG6 kept separate | F-LOG-0269; effect in E2b by F-LOG-0275 Act II (applied F-LOG-0276) | C-α = (b), consistency only; C-γ = return (ten rows labelled; the guard-row question is open) |

**Result: the O3 minimal set is met.** VP10 is met by ruling (F-LOG-0264); the other five are met by decision. For edges into VP12 and VP11 from VPs outside this set, see A1.

### 1b. ARB r4.4 §E3 prerequisites

| prerequisite (ARB §E3, verbatim item) | status | evidence |
|---|---|---|
| "E1" | MET | F-LOG-0253 |
| "E2b (O2 adoption)" | MET | F-LOG-0261; amended F-LOG-0275; recorded in E2b r1.2, F-LOG-0276 (review PASS_WITH_LIMITATIONS; K1–K4 not re-reviewed) |
| "E2 for VP10, VP12, VP11 (beyond E2b's set)" | MET (VP10 by ruling) | F-LOG-0263/0264, F-LOG-0268, F-LOG-0269. ARB §E3 annotation: "[r4.4 sync] For VP10: met by the re-affirmed (iii) (F-LOG-0263), ruled to count as VP10's decision for O3 (not on evidence; F-LOG-0264)." |
| "a prepared authorization proposal (O3)" | **NOT MET** | It does not exist. It is exactly what the requested act would permit to be produced. It is a prerequisite of **E3**, not of O3 preparation |

### 1c. Other recorded conditions on any O3/E3 proposal

| condition | source | status |
|---|---|---|
| "This value must be revisited before any O3 proposal." (VP10) | F-LOG-0258 | DISCHARGED, F-LOG-0263 |
| "VP14 must be reopened before any O3 proposal." | F-LOG-0259 | DISCHARGED, F-LOG-0265 |
| "Re-check and document the H-D asymmetry before E3 proceeds." | F-LOG-0268 (appended) | SATISFIED, F-LOG-0270 + F-LOG-0271 |
| "prepare an E3 proposal and have it reviewed" | F-LOG-0261 (E3 prerequisites) | OPEN: this is the object of this act |
| open items (b) E2b amendment, (c) register/ARB sync, (d) R21 + F8 | F-LOG-0271 | CLOSED, F-LOG-0278 |

*Context note (not a recorded condition):* T3 is at r1.8. A fresh independent review returned "PASS" (F-LOG-0274).

### 1d. Not met, or ambiguous

| # | item | kind | [E] reading |
|---|---|---|---|
| N1 | "a prepared authorization proposal (O3)" | NOT MET | by construction; it blocks E3, not this act |
| A1 | **(i)** ARB r4.4 §C, row 7, column "needed for": O3. That row lists "VP4, VP12, VP11", with "VP4 open". But register r1.19 §3 lists VP4 under "may stay open" ("VP4, VP7, VP8 (stated as unsupported)"), and the register VP4 row says "none (O3 may list it as "not supported")". **(ii) Edge question.** Register §2: "**A → B** means that A should be decided, or explicitly left open, before B". The edges VP4 → VP12, VP4 → VP11 and VP8 → VP11 apply. E3-PREREQ r4.1 §6 item 6: "F-LOG-0261 says "all other variation points stay open with the defaults of `R7/E2b-ADOPTION-TEXT.md` §2f"; whether that is "explicitly left open" is not stated", with the effect "the human may confirm VP4 and VP8 open first". **No act has ruled on it.** VP12 and VP11 were decided anyway (F-LOG-0268, F-LOG-0269; VP4 and VP8 "stay at their E2b §2f defaults", F-LOG-0269) | AMBIGUOUS | The register §3 minimal set does not include VP4 or VP8. ARB §C is a timing summary. The human may wish to rule whether the §2f defaults count as "explicitly left open" for VP4 and VP8. ARB §E3 exclusions: "VP4, VP7, VP8 stated as unsupported unless decided under E2" |
| A2 | ARB §E3 acceptance criterion: "every architecture element traces to T3 elements and their §2 rows". E2b Act I (1): "The stale T3 r1.7 CB1 / CB23 cells are not adopted". The CB1/CB23 traces are "restricted to the T1 ids and the R4 / FROZEN R3 ids not keyed to hosting" | AMBIGUOUS (for E3/E3b, not for preparation) | The O3 proposal can state which trace basis E3 would use (E2b r1.2 §A1.2, B1). It cannot fix (d′) |
| A3 | Stale status notes. (1) Register VP11 heading: "effect pending E2b amendment" (H13; F-LOG-0277: "That note stays stale"). (2) T3 r1.8 status line: "VP11's effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8)". (3) T3 r1.8 §6 item 6: "Effect on the adopted model awaits the E2b amendment (not made as of r1.8)". (4) T3 r1.8 §6 item 1, which records VP11 = (ii) by human act but still says: "its effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8)" | STALE TEXT, not a decision gap | The E2b amendment was made (F-LOG-0275/0276). The T3 notes are time-qualified ("as of r1.8"). Only H13 is in the logged open-item list; the T3 notes are disclosed here, not proposed for change. The O3 proposal cites F-LOG-0275 for VP11's effect |
| A4 | The review regime for the O3 proposal (number of rounds, reviewer type, stop rule) is not fixed by any record | OPEN for the human | Precedents only. F-LOG-0262: "five fresh, independent subagent reviews, as the Human ARB instructed (`kos-theory-reviewer`, one new reviewer each round)". F-LOG-0219: "stop rule: r1 FAIL → human" |
| A5 | E2b r1.2 §A1.9b, row "any O3 / E3 proposal": "**Still required:** prepare an E3 proposal and have it reviewed (F-LOG-0261), after its own act, open item (e). Also required, each by its own act: (d) was closed by act C (F-LOG-0273; F-LOG-0274), R11 open; (c) stays pending until act B; (d′) stays open." | AMBIGUOUS | It is unclear whether "Also required, each by its own act" makes R11 and (d′) prerequisites of an O3/E3 proposal, or only lists their status. F-LOG-0278 lists them as "Open" without making them prerequisites. (c) has since closed (F-LOG-0278). The human may rule (§4) |

**No log-recorded prerequisite of O3 *preparation* is unmet [E].** The only unmet §E3 prerequisite is the O3 proposal itself. A5 is a contrary reading; it is for the human.

---

## 2. What the act would permit

| permitted | not permitted (exclusions) |
|---|---|
| preparing **one** O3 proposal: the document asking the Human ARB for E3 | E3 architecture derivation: no context, aggregate, value object, event or port is derived |
| a **fresh independent review** of it | E3b (acceptance of an architecture proposal), E4 / R8 (canonicalization), E5 (implementation) |
| corrections required by that review, and their re-review | evidence collection: V2, V5, V6, E-A5, or any new evidence |
| logging the result for a later, separate Human ARB act on E3 | canonical terms (every name stays a working label, N8) |
| | any amendment of adopted or governed records: E2b r1.2, T3 r1.8, register r1.19, ARB r4.4 |
| | any VP decision, including VP4, VP8, VP1a–d, VP2 and VP13 |
| | any spanning-rule selection (S, S′, S″), and any acceptance of the asymmetry |
| | resolution of any open item in §4 |

---

## 3. Binding conditions on the O3 proposal (quoted from source)

| # | condition | source (verbatim) |
|---|---|---|
| B1 | built from the adopted model, with provenance | **Scope.** E3 scope: "derive an architecture proposal from the adopted bounded model only" (ARB §E3).<br>**Adopted model.** E2b r1.2, 27 elements: "CB1, CB23, the participant, CG1–CG6, CV1–CV9, CV-text, CE1–CE6, CS1 and MW1" (F-LOG-0275 Act I (1)).<br>**25 baseline elements:** "The 25 previously adopted elements keep their T3 r1.5 traces". For these only, the full T3 r1.5 §2 row applies: T1 ids, T2 ids (tier), R4 / FROZEN R3 ids, assumptions, reading sensitivity.<br>**CB1 and CB23:** Act I (1), on their `R7/T3-CANDIDATE-MODEL.md` r1.7 §2 traces (T3 r1.7 §2): "The traces are restricted to the T1 ids and the R4 / FROZEN R3 ids not keyed to hosting. Their T2 hosts per spanning reading are recorded as a report of T3 [E], not ratified, and no reading is selected. The stale T3 r1.7 CB1 / CB23 cells are not adopted." T2 hosting for CB1/CB23 is taken only from the per-reading report in E2b §A1.3 / §A1.4 ("a report of T3 [E], not ratified"), never from the T3 cells.<br>**Provenance chain [E].** Applying to O3 the F-LOG-0248 traceability chain ("concept → R4 statement or R3 item → inherited assumptions (§4) → reading sensitivity") is an analyst reading. F-LOG-0248 bound R7 outputs |
| B2 | S, S′ and S″, none privileged | F-LOG-0271 (act): "The asymmetry stays documented and not accepted, and travels as a disclosed limitation into any O3/E3 proposal, which must show results under S, S′ and S″." F-LOG-0271 (human statement, verbatim): "Any O3/E3 proposal must present the relevant results under all three interpretations: S, S′ and S″. It must not silently privilege one interpretation or present the asymmetry as an accepted property of the cut." |
| B3 | F-LOG-0265 premise | F-LOG-0271: "This disposition does not decide VP4 or VP11, does not withdraw F-LOG-0265's premise, and does not authorize E3." E2b Act I (4): "T3 r1.7 records [E] that it holds under S′ and S″ and not under S." |
| B4 | T-B inactive; R83 | F-LOG-0261: "All T-B rows (R25–R39, R79, R80, R83) remain **inactive** policy slots; R83 keeps its condition." F-LOG-0254 condition: "the non-occurrence inference (X18, R49) is never applied to the observed capture." |
| B5 | R1–R24 candidates only | F-LOG-0261: "T-A rows R1–R24 are carried as candidate model constraints, not domain invariants, none enforceable from records (R23)." F-LOG-0275 Act II: "No grouping is enforceable from records (R23). R1–R24 remain candidate model constraints, not domain invariants." |
| B6 | consistency only | F-LOG-0275 Act II (3a): "consistency is admitted as a conceptual human design decision, without transaction semantics, following the package r4.1 §4.6 / §4.7 analyst gloss; the transaction part stays excluded." E2b §A1.5: this "paraphrases the package r4.1 §4.6 / §4.7 analyst gloss … not F-LOG-0269"; it is admitted "with no enforcement unit (R23), not a domain invariant and not derived from R4" |
| B7 | ten labelled rows (interim; C-γ returned) | F-LOG-0275 Act II (3b): "Whether guard rows that cross groupings are cross-grouping constraints is returned for analysis; until then, only the ten enumerated rows are so labelled." The ten rows: "R1, R2, R3, R5, R6, R8, R9, R18, R19 and R22 (T3 §1b)" (F-LOG-0269) |
| B8 | standing qualifier, verbatim | "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable" (F-LOG-0248) |
| B9 | limitations | **ARB r4.4 §B items 1–8**, carried with E2b's notes:<br>• item 4: the [r4.2] note;<br>• item 8 ("all 19 VPs … remain undecided") is stale. Carry E2b §A1.8: "7 items are now decided or recorded (VP1d-H, VP15, VP5, VP10, VP14, VP12, VP11), and 13 VPs are open".<br>**Λ1–Λ7** (brief r3 §1).<br>**The F-LOG-0256 human-preserved limitations:** "one corpus and finite test universes; within-family validation, cross-family waived; the shared Claude interpretation untested; H-D and H-B non-identifiable; the theory does not establish aggregate or consistency boundaries, event order or causal relationships as empirical facts".<br>**E2b disclosures, per §A1.9:** D1 and D2 resolved; D3 replaced by D5; D4, D5 and D6 carried.<br>**Unratified analyst items.** From E2b §6: "the T2 §0 [I] reading of "open"; the E2b VP1a–d rule (package §E2b, analyst resolution); the VP10 (ii) "removed" reading". From §A1.10: the K-c criterion and its rules; the per-reading host lists; the G6 trace restriction; "named roles … not engaged by VP12 (i)"; the T3 r1.7 "not … a reason, by itself, to reopen VP12 or VP14"; the C-β (27) reading |
| B10 | ARB §E3 exclusions carried | ARB §E3: "undecided VP1a–d, and undecided VP2, VP13, as inactive slots …; never invariants (N1, N2, N5); VP3, VP6, VP16, VP9 as evidence gaps …; VP4, VP7, VP8 stated as unsupported unless decided under E2 …; no canonical terms (R8); no implementation; no generality claim" |
| B11 | structuring hypotheses stay hypotheses until E3 | E2b §3: "“grouping”, “value-like”, “occurrence”, “slot” stay structuring hypotheses, not aggregates, value objects, domain events or bounded contexts." (Inner quotes changed to typographic double quotes for nesting. The source has double quotes, i.e. "grouping", "value-like", ….) |
| B12 | open items carried, not resolved | (d′) M5 cells; the C-γ guard-row question; R11; H13; cosmetic punctuation (F-LOG-0278 "Open" list); the CV9 / §3b epistemic-status tension (E3-PREREQ r4.1 §6 item 1; E2b §A1.5 "the package §6 item 1 tension stays unresolved"). See §4 |
| B13 | neutrality | F-LOG-0265 (VP14): "This partition is decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule." F-LOG-0269 (VP11): "This is decided without reference to any VP1a–d (including VP1b) outcome." It has no "encodes neither" clause. F-LOG-0275 (both acts): "decided without reference to any VP1a–d outcome (N1) and encodes neither H-D nor H-B as a rule". [E] The O3 proposal may not justify any mapping by a VP1a–d outcome; this restates N1 |
| B14 | S″ is a counting reading only | F-LOG-0268 (appended): "No additional role-separation assumption is introduced by this act." E2b §A1.9a: S″ "coincides with every VP12 (ii) result that r1.6 stated (indicatively; r1.6 left (ii) to a partial re-derivation)". It also records that VP12 (ii) is the option not chosen, and that S″ may conflict with the F-LOG-0268 sentence. E3-PREREQ r4.1 §6 item 12: "VP12 (ii) risks deciding VP4 by implication".<br>**Condition [E]:** S″ results are reported as counting results only. They introduce no role concepts, and VP4 is not decided by implication (VP4 is open; ARB §E3: "stated as unsupported unless decided under E2"). This condition does not resolve the E2b §A1.9a tension |

---

## 4. Open items: do they block O3 PREPARATION?

| item | content (source) | blocks preparation? | evidence / [E] reading |
|---|---|---|---|
| (d′) M5 cells | "T3 sync of the CB1 / CB23 cells (M5) and the hosting-keyed R4 clauses (G6), including the missing R4 ids under S" (E2b §A1.11) | not by the log; see A5 | E2b already adopts CB1/CB23 by membership and a restricted trace (B1). [E] It is a **risk for E3/E3b** traceability (A2); the proposal must disclose it |
| C-γ guard-row question | "whether guard rows that cross groupings (R14–R17, R21; R11 open) are cross-grouping constraints in the sense of the VP11 act" (E2b §A1.11) | not by the log | "until then, only the ten enumerated rows are so labelled" defines an interim state (B7). [E] It bears on what E3 would map onto groupings; the proposal must carry it open and may not answer it |
| R11 | Kept separate from C-γ. **Crossing / guard-list question:** "R11 … appears to cross groupings. It was not in the F-LOG-0269 pre-act disclosure and is not added; adding it needs its own act" (T3 r1.8 §6 item 1). The C-γ question (above) is whether guard rows are cross-grouping constraints at all | not by the log; see A5 | the proposal reports R11 as an open crossing question. It neither adds R11 to the §1b list nor labels it |
| CV9 / §3b tension | E3-PREREQ r4.1 §6 item 1: "CV9's adoption may already engage the T3 §3b row "epistemic-status / evidential-limitation information in the model", which E2b §2d lists as excluded". E2b §A1.5: "the package §6 item 1 tension stays unresolved" | not by the log | the proposal discloses it at CV9 and maps nothing on it as resolved |
| H13 | stale register VP11 note "effect pending E2b amendment"; "needs its own act" (F-LOG-0277) | not by the log | text sync only. The decision content is in F-LOG-0269/0275, and the proposal cites those |
| cosmetic punctuation | register L3, L73, L174, ARB L3: "cosmetic, deferred" (F-LOG-0278) | no | no content |
| A3 (T3 r1.8 notes) | §1d | no | time-qualified text; disclosed only |
| S/S′/S″ unselected | F-LOG-0271 | no | a binding condition (B2, B14), not a gate: the proposal reports all three |

**Blocking status.** The log (F-LOG-0278) does not make them prerequisites; E2b §A1.9b's wording can be read as doing so; the human may rule. See A5. Whether any of them should be closed **before E3** is a question the O3 proposal must state for the human. It may not answer it.

---

## 5. Expected outline of the O3 proposal (outline only; not drafted)

Derived from ARB r4.4 §E3, register r1.19 §0/§3 and the T3 r1.8 section headings. ARB §E3 and register §3 name **only** "Boundaries, participant placement and groupings". Every mapping to a DDD construct is **[E]**. Per E2b §3 / B11, the adopted elements are "structuring hypotheses, not aggregates, value objects, domain events or bounded contexts". Any mapping is a candidate that E3 would derive, if E3 is ever authorized. The O3 proposal names these only as the questions E3 would answer; it derives no mapping.

| § | content | anchor |
|---|---|---|
| 1 | Request: authorize E3 under the ARB §E3 scope and exclusions; standing qualifier; "This does not authorize R8 or implementation" | ARB §E3 draft |
| 2 | Prerequisite record (§1 of this package, updated), including A1 and A5 for the human | ARB §E3 prerequisites; register §3 |
| 3 | What E3 would derive, from the adopted model only | register §3 note ("Boundaries, participant placement and groupings") |
| 3a | boundaries CB1, CB23 (CB4 is an open slot, not a side) → candidate contexts | **[E] mapping, not named in ARB §E3 or register §3.** Source: T3 §1a heading "Candidate boundaries (bounded-context-like partitions …)"; E2b Act I (1) |
| 3b | participant placement: spans CB1 and CB23; S″ as counting only (B14) | VP12 (i), F-LOG-0268 |
| 3c | groupings CG1–CG6 → candidate aggregates; ten labelled cross-grouping constraints (B7); consistency only (B6) | **[E] mapping, not named in ARB §E3 or register §3.** Source: T3 §1b heading "aggregate-like clusters"; F-LOG-0269; F-LOG-0275 Act II |
| 3d | CV1–CV9, CV-text → candidate value objects; the CV9 tension disclosed (B12) | **[E]**: T3 §1c "value-like concepts"; not named in ARB §E3 or register §3 |
| 3e | CE1–CE6 → candidate events, with no order and no production (T3 §1d) | **[E]**: T3 §1d "domain-event-like"; not named in ARB §E3 or register §3 |
| 3f | CS1, MW1: no DDD mapping recorded | T3 §1e. **[E]** |
| 3g | ports | **UNANCHORED**: no source names ports. Include only if the human names it |
| 4 | results under S, S′ and S″, side by side, none privileged | B2, B14 |
| 5 | exclusions (the ARB §E3 list, B10), and the "permits" line: "an architecture **proposal** for human review; nothing built" | ARB §E3 |
| 6 | E3 acceptance criteria, verbatim: "every architecture element traces to T3 elements and their §2 rows; standing qualifier carried; independent review", plus the F-LOG-0271 condition; the E3b criteria for reference | ARB §E3, §E3b |
| 7 | unresolved risks: "one-corpus primitives (VP5); low reversibility of VP15; R23 (nothing enforceable from records)"; the §4 open items; A2 | ARB §E3 |
| 8 | review plan for the E3 output: independent review (E3 criterion; E3b prerequisite "an independent review of it completed") | ARB §E3, §E3b |
| 9 | limitations (B9); reopen conditions (ARB §F; E2b §A1.9b); draft Human ARB act (approve / return / reject) | ARB §F |
| 10 | self-check | precedent |

---

## 6. Reversibility and reopen conditions

**Reversibility of the requested act [E]: high.** It produces a document and its reviews. It adopts nothing and amends nothing. Withdrawing it discards only the draft.

| condition | effect |
|---|---|
| a human selection of S, S′ or S″, or a ruling on the asymmetry | the proposal's §4 (results) and §3 are re-derived (E2b §A1.9b) |
| the C-γ guard-row question is answered, or R11 is added or excluded | §3c (cross-grouping constraints) |
| a ruling on A1 (the VP4 / VP8 edges) or A5 (prerequisite reading) | §2 (prerequisite record); possibly VP12 and VP11, by human act only |
| the (d′) sync is made and its result differs | §3a traces; E2b §A1.2 (E2b §A1.9b row) |
| VP12 reopened as (ii); VP10 later decided (i); VP14 reopened | the proposal is void at §3; E2b is amended (E2b §A1.9b) |
| a VP is found to have been decided by implication or bundled | that VP, and every act relying on it (ARB §F) |
| V6 corpus-specificity; adequacy failure of a T-A constraint; defect in sealed R4 r3.1; the H5 / F-LOG-0190 condition | per ARB §F, including "E3's derivation and proposal" |
| a defect is found in an independent review of this package or of the O3 proposal | the affected wording, and any act taken on it. [E] This extends ARB §F, whose row names "this package or the register", to this package and the O3 proposal |

---

## 7. Draft Human ARB act text (select one)

**Approve:**
> "I, Human ARB, authorize the **preparation** of an O3 proposal (register r1.19 §0, §3): a document asking for the bounded architecture derivation gate E3 (`R7/ARB-DECISION-PACKAGE.md` r4.4 §E3), built from the adopted bounded model (`R7/E2b-ADOPTION-TEXT.md` r1.2) under the binding conditions of `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r[n] §3. I also authorize its fresh independent review, the corrections that review requires, and the re-review of those corrections. **This authorizes preparation and review only. It does not authorize E3.**
>
> It does not authorize E3b, E4 (R8) or E5. It decides no variation point. It selects no spanning rule and accepts no asymmetry. It resolves no open item ((d′), the C-γ guard-row question, R11, the CV9 / §3b tension, H13). It amends no adopted or governed record (E2b, T3, the register, the ARB package). It authorizes no evidence collection (including V2, V5, V6, E-A5), and it adopts no canonical terms.
>
> [Optional, no default: It does not withdraw F-LOG-0265's premise. The proposal is prepared without reference to any VP1a–d outcome (N1).]
>
> [Optional, no default: The review is performed by fresh independent `kos-theory-reviewer` subagents, one new reviewer per round. Stop rule: a FAIL at round __ → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present.]
>
> The reviewed O3 proposal is presented for a separate Human ARB act on E3."

*Note on the first optional clause:* omitting it changes nothing, because B3 and B13 bind regardless.

**Return:**
> "I, Human ARB, return `R7/O3-PREPARATION-AUTHORIZATION-PACKAGE.md` r[n] for [items]. Nothing is authorized; E3, E3b, E4 and E5 are not authorized."

**Reject:**
> "I, Human ARB, do not authorize the preparation of an O3 proposal at this time. The adopted model (E2b r1.2) and all records stand unchanged; E3, E3b, E4 and E5 are not authorized."

When recorded, the selected text becomes `F-LOG-[act]`.

---

## 8. Self-check

| check | result |
|---|---|
| Decides nothing; recommends no option | yes. §1d and §4 give [E] readings. §7 offers three variants and optional clauses, with no default |
| Does not authorize E3 | yes: §2, and the approve text states it verbatim |
| Approve text matches §2 | yes: preparation, review, corrections and re-review |
| Every prerequisite carries its log id | yes (§1a–§1c). The T3 review is moved to a context note |
| VP10 shown as met by ruling, not evidence | yes (§1a, §1b; F-LOG-0264 quoted) |
| F-LOG-0271 quoted verbatim | yes (B2, both the act and the human statement) |
| Standing qualifier verbatim | yes (header, B8) |
| B1 faithful to E2b Act I (1) | yes. The restriction and the "not adopted" sentence are quoted. The full T2 / reading-sensitivity links apply to the 25 baseline elements only. CB1/CB23 hosting comes from E2b §A1.3/§A1.4. The F-LOG-0248 chain applied to O3 is labelled [E] |
| Outline invents nothing | Ports are marked UNANCHORED. 3a, 3c, 3d and 3e are [E] mappings not named in ARB §E3 or register §3, with B11 quoted |
| Open items listed with blocking status | yes (§4). The log does not make them prerequisites; E2b §A1.9b can be read so (A5); the human may rule. No universal negative is claimed |
| Attributions | B13 corrected: F-LOG-0269 has no "encodes neither" clause. B6 marks the paraphrase source |
| Ambiguities disclosed | A1 (VP4 in ARB §C; the VP4 / VP8 edges), A2 (trace basis), A3 (stale notes), A4 (review regime and stop rule), A5 (E2b §A1.9b) |
| No adopted record edited | yes: only this file |
| Independent review | r1 verified, PASS_WITH_LIMITATIONS (no blocker, no material finding). The r1.1 n-fixes are **not re-reviewed** |

---

## 9. Change rows (r0 → r1; fresh review of r0: PASS_WITH_LIMITATIONS)

| finding | fix in r1 | where |
|---|---|---|
| F1 | Added A5, quoting the E2b §A1.9b row "any O3 / E3 proposal". Replaced §4's universal negative with "the log (F-LOG-0278) does not make them prerequisites; E2b §A1.9b's wording can be read as doing so; the human may rule". Per-row "No" became "not by the log". §8 updated | §1d A5, §4, §8 |
| F2 | B1 rewritten. The E2b Act I (1) restriction and "not adopted" sentence are quoted verbatim. The T2 and reading-sensitivity links are limited to the 25 baseline elements (T3 r1.5). CB1/CB23 hosting points to E2b §A1.3/§A1.4. The F-LOG-0248 chain applied to O3 is labelled [E] | B1 |
| F3 | Rows 3a, 3c, 3d and 3e are labelled [E] mappings not named in ARB §E3 or register §3. The §5 preamble quotes E2b §3 / B11. Self-check fixed | §5, §8 |
| F4 (a) | A1 widened with the VP4 / VP8 edge question (register §2 "A → B" definition; E3-PREREQ r4.1 §6 item 6; no act has ruled) | §1d A1 |
| F4 (b) | The CV9 / §3b tension added to §4 and B12 | §4, B12, §5 3d |
| F4 (c) | Added B14: S″ is a counting reading only; no role concepts; VP4 not decided by implication; F-LOG-0268 quoted | B14, §5 3b |
| F4 (d) | E3-PREREQ r4.1 added to the inputs | header |
| m1 | A1 rephrased: "row 7, column "needed for": O3" | §1d A1 |
| m2 | B13 attribution corrected (F-LOG-0265, F-LOG-0269 and F-LOG-0275 quoted separately) | B13 |
| m3 | B9: the stale item 8 carried with the E2b §A1.8 note (7 / 13); D1 and D2 resolved, D3 → D5; the E2b §6 and §A1.10 unratified items added | B9 |
| m4 | B6 keeps the analyst-gloss attribution and adds "not a domain invariant and not derived from R4" | B6 |
| m5 | B2 adds the F-LOG-0271 act text "travels as a disclosed limitation into any O3/E3 proposal" | B2 |
| m6 | A3 also cites T3 r1.8 §6 item 1 | §1d A3 |
| m7 | The R11 row is kept separate from the C-γ question | §4 |
| m8 | "T3 at r1.8, reviewed" moved to a context note | §1c |
| m9 | `F-LOG-[act]` declared and used in §7. The r[n] rule is stated in the header. The review clause gets a stop rule (precedent F-LOG-0219). The approve text matches §2 | header, §1d A4, §7 |
| m10 | ARB §C added to the inputs. The optional F-LOG-0265-premise and N1 clauses added to the approve text. B7 retitled | header, §7, B7 |

**r1 → r1.1** (fresh verification of r1: PASS_WITH_LIMITATIONS, no blocker, no material finding; the n-fixes are not re-reviewed)

| finding | fix in r1.1 | where |
|---|---|---|
| n1 | The B14 condition is tagged [E]. Added E3-PREREQ r4.1 §6 item 12 ("VP12 (ii) risks deciding VP4 by implication"), and "This condition does not resolve the E2b §A1.9a tension" | B14 |
| n2 | The E2b §A1.9a quote is kept whole, including "(indicatively; r1.6 left (ii) to a partial re-derivation)". The rest is paraphrased outside the quote marks | B14 |
| n3 | Added to the §5 preamble: "The O3 proposal names these only as the questions E3 would answer; it derives no mapping" | §5 |
| n4 | Note after §7 Approve: omitting the F-LOG-0265 / N1 optional clause changes nothing, because B3 and B13 bind regardless | §7 |
| n5 | Stop rule rewritten: "a FAIL at round __ → human; otherwise PASS / PASS_WITH_LIMITATIONS with nothing blocking → present" | §7 |
| n6 | B11: inner quotes changed to typographic double quotes, with a note on the change of quote style | B11 |
| n7 | A3 item (4) quotes the stale clause "its effect on the adopted model awaits the E2b amendment by its own act (not made as of r1.8)" | §1d A3 |
| n8 | The §9 F3 row lists 3a, 3c, 3d and 3e | §9 |
| n9 | The §6 extension of ARB §F to this package and the O3 proposal is tagged [E] | §6 |
| n10 | B1 gives "(T3 r1.7 §2)" as the antecedent of the restriction quote | B1 |
