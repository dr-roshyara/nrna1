T9-02 REVIEW REPORT C

## Summary

1. Verdict on §9.20: reliable on its strongest results. The record is sufficient for Q1, Q2 and Q4 (Th1, within scope). An attribute vector per item is refuted (Th2). Derived values are not state (Th3). Act time is required (Th4, its second half).
2. Largest risk (F-1, high): Th1 says "Req's core", but §9.19 L167 counts Q5 in the core, and §9.20 never mentions Q5. Q5 needs rule content written in the restricted language L, and no admitted kind carries it. So DC-1 is met for Q5 unless the rules are an input.
3. Th1 is close to the standard fact that any system's accepted-input history is a sufficient state. Read literally, DC-7 is met by Th1: it also holds for an empty Req. The informative results are the necessity ones (Th2, Th4) and the minimality question, which stays open (F-4).
4. The record-order half of Th4 holds only relative to two formulation choices: cuts defined as position prefixes (H2), and the Principal's decision to keep D rather than D_acyclic. Req's information does not force it (F-3).
5. DC-2: I exhibit a concrete candidate pair. Two records differ only in the recorder of a grant that is not single-use and is signed by its actor. In the model, every implemented observer, mark, guard and class treats them alike. Only Q2 or Q3 could tell them apart, and the book fixes neither output (F-4). My step-1 claim that "the record is minimal by Q2" was too strong; §9.20 is right to leave minimality open.
6. In §9.20.7, four rows decide more than the book does:
   - visibility and nature are marked Out, but declassification and registration are decided O1 kinds (U6 (a));
   - provenance is marked F, but claim provenance and act provenance are homonyms;
   - the evidential grade row merges §1.3's sense into g1's class.
7. "Partially resolved" is the right outcome; its reasons need F-1, F-3 and F-4 added.
8. No TODO row is closed and no fork is decided formally. The F37 asymmetry in §9.20.8 is an artefact of the table's Out classes (F-9).

**Process independence:** my step-1 answer was saved before I saw any of this material. I read no other reviewer's work. The independence is of process, not of person.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| F-1 | Th1 claims "Req's core", but Q5 is omitted. §9.19 L167 lists "Req's core (Q1–Q3, Q5, O1–O4)"; §9.10.4 L161–162 puts Q5 outside the core ("grounded by chapter must-dos only"). Q5's inputs are "the rules … written in the restricted language L" (§9.10 L83). No admitted kind carries rule content: adoption's fields are actor, bases, item, scope, act time, validity (§9.13 L30). | high | §9.19 L167; §9.10 L83, L161–162; §9.13 L30 | Either name Q5 an input (the rules formalised in L, a world assumption as §9.10 L88 states) or scope Th1 to Q1–Q4. Also record the §9.10/§9.19 disagreement on Q5's membership in the core. | 09.00 row 9.20; §9.20.1 item 1; §9.20.6 bullet 1 | Th1's scope names Q5's place |
| F-2 | Sufficiency of "inputs the kernel does not hold" (covers, identity link) is stated as Src. K3 defines correct as "equal to the relation the kernel's own steps accepted as input" (§9.15 L76), and G3 requires "pinned inputs (H3)". Q1 at a past cut needs covers as it stood then. θ is versioned by u; covers has no version parameter. | high | §9.15 L65, L76; §9.18 L58; §9.10 L33 | Mark "covers and the link as unversioned inputs" as **Open**: they must be recorded, or versioned like θ(u), for replay at a cut. Otherwise DC-1 is met for Q1 at a cut whenever covers changes. | §9.20.3 "Not state" line | stated as a premise of Th1 |
| F-3 | Th4's "record order required" depends on the formulation. M2 differs only because cut 3 is a positional prefix. If cuts are named by citation-closed entry sets (§9.17.2 already names a cut "by its entries"), the two records are one set. Positions are read only by D's citation rule (§9.13 L105), and the choice of D over D_acyclic is the Principal's (§9.13 L70). | high | §9.13 L70, L105; §9.17.2; M2 | Say "required by H2's cut definition and by decision D", not by Req. Keep the act-time half as Ded. | §9.20.3 row "Record order"; §9.20.6 bullet 1; 09.00 row 9.20 | restated |
| F-4 | DC-2 candidate exhibited. Two records are identical except the recorder of a grant h (not single-use, signed-as-actor). Probe P2 (37 extension sequences of length up to 2): Q1 values and marks equal in 7028 of 7028 cases, guard verdicts in 156 of 156, g1 classes in 74 of 74. **Ded, beyond the model:** the recorder is read only by the "inconsistent recording status" mark, which needs signed-as-other (§9.13 L51); entry identity excludes recorder and status (§9.16 L93), so no citation can tell the two apart; the owner check reads `app`, not the recorder. Only Q2 (if grants are "items with a governed identifier", §9.10 L46) or Q3 (if "found (with the grant)" returns the whole entry, L57) could distinguish them. | high | probe P2; §9.13 L51; §9.16 L93; §9.10 L46, L57 | §9.20.5: name the pair. State that minimality turns on Q2's scope and Q3's output. The recorder is required by the Principal's v6 decision, not by any core observer. | §9.20.3 row "Required fields"; §9.20.5; §9.20.9 "Counterexamples" | Q2's and Q3's outputs fixed, or the pair recorded as open |
| F-5 | Visibility and nature are marked **Out**, but declassification and registration are O1 kinds (§9.10 L105) with a per-kind cut decided (U6 (a)) and D clauses still to come (T9-21). Their fields are not yet written, so the classes are **Open**, not Out. This is a DC-5 conflict between §9.10 and §9.20.7. | high | §9.10 L105; §9.19 L395; §1.7 L18 | Visibility and nature → Open (T9-21). Representation → Out only as far as the record's own carrier goes. | §9.20.1 item 4; §9.20.7 rows | rows changed |
| F-6 | Provenance is marked **F**, but the fields cited are an act's fields. §1.3's provenance is "who or what produced" the claim, which is O1's registration "with its provenance" (§9.10 L105), outside D. A homonym. | high | §1.3 L25; §9.10 L105; §9.13 L52 | Split the row: act provenance F; claim provenance Open (T9-21). Link to F13a. | §9.20.7 row 1 | split |
| F-7 | The evidential grade row assigns Δ to §1.3's label. U2 makes **Q4's** grade g1's class. §1.3's observed / inferred / hypothesized is a mode of knowing; §9.18 L82 records "the source kind of evidence content, not carried". So the row decides part of G7's F13 part (DC-6). | high | §1.3 L6, L26; §9.18 L82; §9.19 L396 | Split the row: Q4 grade Δ (U2); §1.3 or §3.1 grade "not carried, Open (G7)". | §9.20.7 row 2; §9.20.1 item 3 | split |
| F-8 | M5's line "for Q1 alone the record is not a minimal state" is too broad. Q1's output includes the acts it rests on and the marks (§9.10 L34). Probe P1: with signed-as-other, recorder A against Z gives equal values but different marks. | normal | probe P1; §9.10 L34; §9.13 L51 | Say "for Q1's value, with status signed-as-actor". | §9.20.5 bullet 1 | reworded |
| F-9 | F37's branch asymmetry in §9.20.8: "(B) … needs no new field" holds only because governance status, canon scope and the enforcement part of operational state are classed Out or Open. Under (B) those also lack bearers in 𝒮 (§3.1 L73–80). It decides nothing, but it tilts toward (B). | normal | §3.1 L73–80; §9.20.8 | State that both branches need fields for the Open rows. | §9.20.8 F37 bullet | reworded |
| F-10 | Th1 step 4 cites Q3's reference as "still to be written" (§9.10 L60). §9.10 L62 gives "a construction on D … (§9.13 R5)", reading covers and the link (Int, not modelled). | normal | §9.10 L60, L62 | Cite both lines; scope Q3 to R5's construction. | §9.20.4 Th1 step 4 and scope | cited |
| F-11 | Minor omissions in the inputs and fields: the root designation outside D (§9.15 L75, L157; needed for the carrier in force); the kernel's attestation and the store field required since v19 (§9.13 L48); grants' extended identity (owner and recording application, §9.16 L93); E1 still open (U4 (d)). | normal | the lines named | List them in §9.20.3. | §9.20.3 | listed |
| F-12 | Homonyms in §9.20.7: owner (grant's owner application against §1.11's maintainer), standing (CE* computes current authority, §9.10 L31), canon scope (identified with the act scope field; §3.1 L80 keeps it open). | normal | §1.11 L33; §9.10 L31; §3.1 L80 | Qualify each term. | §9.20.7 rows | qualified |
| F-13 | Rigour of Th1 step 7: it states that observers factor through ρ. It should also state that the transition map δ(ρ, x) is a function of ρ and x alone, and that refusals leave no state. The named constants (kernel identity, root carrier, key material under K5) should appear. | normal | §9.20.4 | Add both halves. | §9.20.4 | added |

## Verdicts

| Part | Verdict | Reason (scope) |
|---|---|---|
| §9.20.2 | PROVISIONAL | Accurate with the right lines (TODO L12, §9.19 L166–167, §9.10 L201, §9.13 L81 checked). Omits Q5 in §9.19 L167's core, Q3's construction, and U4 (d). |
| §9.20.3 | PROVISIONAL | Record order is formulation-relative (F-3); covers is not settled as an input (F-2); the recorder is required by decision (F-4); fields missing (F-11). |
| Th1 | PROVISIONAL | Steps adequate for Q1, Q2 and Q4 under the stated premises. The scope "Req's core" fails on Q5 (F-1). The DC-7 note applies. |
| Th2 | VALIDATED | M1 reproduced, and V as modelled is refuted. "Rebuilds" is correctly labelled Int. It can be made Ded for entries about items, because Q2 returns them. |
| Th3 | VALIDATED | No decided rule reads a stored copy; M4 reproduced. Scope: the decided rules. |
| Th4 | PROVISIONAL | The act-time half is valid (M3). The order half rests on H2 and decision D (F-3). |
| §9.20.5 | VALIDATED as "open" | Correctly not claimed. F-8 wording to fix; F-4 supplies the candidate. |
| §9.20.6 | VALIDATED | "Partially resolved" is right; add F-1, F-3 and F-4 to the reasons. |
| §9.20.7 | PROVISIONAL | Rows F-5, F-6 and F-7 decide beyond the book; F-12 homonyms. |
| §9.20.8 | PROVISIONAL | No TODO closed; proposals labelled as consequences. F-9 to fix. |
| Model | VALIDATED (form) | My run reproduced the Professor's output line for line. M5's scope: values only, with the default status. |
| Index and change-log rows | VALIDATED | Accurate; the index row needs F-1's and F-3's scope after correction. |

## The disproof conditions and the limits

- **DC-1:** met for Q5 if §9.19 L167's core holds (F-1). At risk for Q1 at a cut with unversioned covers (F-2).
- **DC-2:** a candidate pair exists, modulo Q2's scope and Q3's output (F-4).
- **DC-3, DC-4:** not met.
- **DC-5:** met by visibility and nature (F-5).
- **DC-6:** met in part by the grade and provenance rows (F-6, F-7).
- **DC-7:** literally met by Th1, not by Th2 and Th4.
- **Commission limits:** respected. Nothing is treated as settled, no primitive is accepted, existence is kept apart from minimality, no T9-21 or derivation-chain work was done. F-5 to F-7 bring parts of the table close to the fork limit.

## Rigour (question 12)

- **The quotient frame is right.** The minimal state is the reachable records modulo the coarsest right congruence that refines equality of all outputs (every observer at every query parameter, including the cut s, plus replies and guard verdicts).
- **Limits of the frame:**
  - the equivalence is relative to the observer set as written, so Q2's and Q3's output specifications decide it (F-4);
  - "every extension" means all finite extension sequences. The model checks up to length 2 only, so it shows form, not a general result;
  - for a field that no clause reads and that identity excludes, a structural induction over the clauses gives the general result. That argument is Ded and needs no enumeration;
  - "minimal state" (a quotient) and "minimal distinction set" (dropping fields) are different notions; DC-2 should keep them apart.

## Against my step-1 answer

- **Mine was too strong:** "S₂ is minimal by Q2" is wrong for entries about no item. §9.20 is right (F-4).
- **§9.20 left out these step-1 points:** the homonyms (F-6, F-7, F-12); covers versioning (F-2); sequence against DAG (F-3); the root designation (F-11); the implication that law 1 moves from S to O (§9.11 L33 says it "presupposes … authority in 𝒮"; under Δ standing, I1 cannot fail). The last is normal and should be recorded as an implication, not a decision.

## Limits

- R2: I ran `undefined_terms.py --scope 09.20` read-only on the working book. Its output was not restricted to scope (tier D 1929 book-wide), so I did not re-judge tier D. The brief's four words (above, addressed, remember, vector) are ordinary English. "Attribute vector" is defined only in RESEARCH-QUESTION, not in §9.20 (normal).
- I did not run the quote check.
- Q3 is not modelled, so the claim that P2 is decided by Q3 is unverified.

## Files opened

- `review/T9-02-TURN/BRIEF-STEP2.md`, `RESEARCH-QUESTION.md`;
- `03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md` (L100–152; I had line numbers via `grep`);
- `chapter_09/09.20-the-state-space.md` (whole);
- `09.00-index.md` (only the lines matching "9.20", via grep);
- `09.99-change-log.md` (last 3 lines);
- `TODO.md` (lines matching T9-02);
- `09.15` L65, L76, L77, L157 and grep lines; `09.16` L93; `09.18` grep lines; `09.13` L285–292;
- `chapter_10/10.05-published-language.md` and `10.99-change-log.md` (grep lines only);
- `CONCEPT-REGISTER.tsv` (grep lines);
- `tmp/tools/t902_state_model.py`, its run file, and `ce_model.py` (L194–240, L313–330, L840–882, L1055–1075, L1206–1350).

STEP1-ANSWER-C.md: line count only (`wc`), not re-read.

## Files copied to the scratch directory

All are under `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-c/`, each copied individually:
- `t902-c-t902_state_model.py`
- `ce_model.py`
- `t902-c-run-orig.txt`
- `undefined_terms.py`
- `inbound_citations.py`

Written there: `t902-c-probe.py` and `t902-c-probe-run.txt`.

## Commands and searches

- `wc -l` on the step-2 files;
- `python3 -I t902-c-t902_state_model.py` (reproduced the run);
- `python3 -I t902-c-probe.py` (P1–P3);
- `grep` on `ce_model.py` for API, FIELDS, W_FIELDS;
- `grep -n` in 09.16 "other than the recorder"; in CONCEPT-REGISTER "stored is not true|CON-0141"; in chapter_10 "PL-12"; in 09.18 "covers|identity link"; in 09.15 "G3" and "L11"; in 09.00 "9.20"; in TODO "T9-02"; in the commission "2.3|2.4";
- `sed` and `awk` ranges as above;
- `undefined_terms.py --root W --no-corpus --scope 09.20`;
- `inbound_citations.py --root W "§9.20"` (one inbound line: 09.99 L96).

No git commands. No other reviewer's file and no other review folder was opened.
