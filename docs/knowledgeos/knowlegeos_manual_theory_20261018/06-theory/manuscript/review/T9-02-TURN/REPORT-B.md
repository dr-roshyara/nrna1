T9-02 REVIEW REPORT B

Reviewer B (kos-lane-reviewer, Sonnet), topic review, steps 2 and 3. W = docs/knowledgeos/knowlegeos_manual_theory_20261018/. Marks: [Src] book states it; [Ded] follows from stated premises; [I] my judgment; [run] my own model run (finite, form only). Paths are under W/06-theory/manuscript/ unless noted. "09.20 Lnn" = line nn of chapter_09/09.20-the-state-space.md.

## Summary (10 lines)

1. 09.20's core direction is sound: the record is necessary and sufficient-in-form, a per-item attribute table is not, and derived values need no separate storage. My step-1 answer agrees (it was reached independently).
2. Th1 is not yet adequate. It omits Q5, which Req's core includes (09.19 L167). It uses a Q3 status the book has superseded. It omits the generic record clauses (store, kernel attestation, integrity result). It uses G-kind without listing it as a premise. Its step 7 is true by construction.
3. Two rows of the §9.20.7 table contradict other sections: "standing = CE*" (against 09.10 Q1's own naming note) and "canon scope = F + In" (against 02.08 L114). The F6, F37 and owner-sense rows go further than the book decides.
4. M1 refutes a vector narrower than the table's own provenance row. With cited bases in provenance, M1's vectors differ [run P1].
5. DC-2: I can exhibit pairs differing only in the recorder or authentication status of a grant or root entry that Q1's values never separate [run P2–P4, 2106 of 2106 cases]. Whether Q1/Q3 outputs that return acts separate them is not stated by the book. 09.20.5 is right to stay cautious; M5 is value-level only.
6. DC-1 is not shown excluded for Q5 or for the generic clauses. DC-5 is met by the standing and canon-scope rows. DC-6 is touched by governance status (F6) and by the F37 branch implication.
7. Outcome "partially resolved" is right. Several of the stated reasons need correcting (finding F4, F2).
8. Nothing is closed by implication, except where noted (F-12, F-6).
9. Model: forms correct; my re-run reproduced all five lines. It is finite and proves no general claim.
10. Index row 30 repeats the stale Q3 phrase.

**Process independence:** I am a separate reviewer instance (process independence, not personal independence). I read only my own saved step-1 answer, as held in my session; I did not open any other reviewer's file. I did not re-open STEP1-ANSWER-B.md from disk.

## 1. Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines (citing/affected) | Resolved when |
|---|---|---|---|---|---|---|
| F1 | **Th1 omits Q5.** Req's core is Q1–Q3, Q5, O1–O4 [Src 09.19 L167]. Th1 steps 1–4 cover Q1–Q4 only. Q5's inputs are "the rules that apply in a region, written in L" and a cut [Src 09.10 Q5, Inputs]. Rule content in L is neither in D's required fields (09.13 L33–47) nor among the stated inputs of §9.20.3. So DC-1 is not shown excluded for Q5. Also the entry's content (dropped by a tombstone, 09.13 L59) is not listed as a distinction of 𝒮. | high (omission; premise missing) | 09.10 Q5 (Inputs, Reference); 09.19 L167; 09.13 L59 | Add Q5 to Th1 with its inputs, or scope Th1 to Q1–Q4 and name "where rule content in L lives" as open. State content as a distinction (or not). | 09.20 L55, L68, L95, L133; 09.00 row 9.20 ("Req's core") | Th1 states Q5's inputs and where they come from |
| F2 | **Q3's status is stale in 09.20.** 09.20 says Q3's reference is "still to be written" (L63, L68, L95, L135, L138(iv); index row 30). That is a quotation of 09.10's Observable-consequences line. The same Q3 item's Reference-definition line says the reference "exists on D, for Θ_f, under C1 = constitute: a construction (§9.13 R5; Int, not modelled)", and 09.16 L76 says Q3 "holds: §9.13 R5's construction on D; its hypothetical act needs a tie convention". Source defect: 09.10 is internally inconsistent here (L60 vs the Reference line). | high | 09.10 Q3 lines "Observable consequences" and "Reference definition"; 09.16 L76 | State Q3's reference as a construction on D (Int, not modelled, tie convention open). Drop "Q3's reference written" from assumptions. Report 09.10 L60 as a source defect. | 09.20 L63, L68, L95, L135, L138; 09.00 row 30 | the claim and the assumption list match 09.10's reference line |
| F3 | **Generic record clauses missing from "required fields" and Th1 step 5.** Every entry must carry a store and a kernel attestation and pass its integrity check; the record with an entry lacking them is outside D [Src 09.17 L164; model in_generic: "carries no store or no kernel attestation", "integrity check failed"]. A tombstone keeps position and integrity result [Src 09.13 L61]. 09.20.3's field list, §9.20.7's provenance row and Th1 omit store, attestation, integrity result and the carrier in force. | high (omitted state components that D requires) | 09.17 L164; 09.13 L61; model ce_model.py in_generic (copy, my scratch) | Add them as record-level distinctions (kernel, K5/K6 premises: attestation assumed unforgeable, 09.17 L164). | 09.20 L41, L64, L95, L107 | the field/distinction list equals D's generic clauses |
| F4 | **Th1's premises are incomplete, and its step 7 is true by construction.** (a) Step 6 ("the effect of each accepted input is an entry appended, or a tombstoning") rests on G-kind (09.19.4), which U3 (b) leaves unfixed. Th1's premise list does not include "granularity G-kind". 09.20.2 L25 notes G-kind but Th1 does not use it as a premise. (b) Steps 1–4 say each reference is a function of the record and stated inputs, which is the premise "Req stated over D" (09.19 L166–167). So step 7 follows from the premise; "sufficient" adds only the check of what each reference reads. That check is real work (and F1, F2, F3 show it was incomplete). | high (premise missing) | 09.19 L159–167, L398 (U3); 09.20 L55–66 | Add premises: G-kind (or any granularity whose effects are entries); "references are functions of D and the named inputs". Say Th1's content is the input-by-input check, not a new proof. | 09.20 L55–56, L66 | premises listed and Th1's scope says "relative to G-kind" |
| F5 | **"Standing = CE*" row (L109) contradicts 09.10 Q1.** 09.10 Q1 says: "what the book can compute is current authority, which §1.14.1-12 separates from standing (current authority can lapse with time while standing is untouched)". §1.3's standing values are provisional / adopted / historical, not T/F/U [Src 01.03]. The row puts Ch. 1's "standing" under Δ with value set {T,F,U}, "decided on D". It is current authority that is Δ; the relation to standing is open, like G7 for grade. | high (DC-5, DC-6) | 09.10 Q1 Naming; 01.03 "three labels"; 09.20 L109 | Row: current authority Δ (CE*); standing (Ch. 1 label 3) Open, mapping to current authority Int. | 09.20 L96, L109 | row says what is Δ and what is open |
| F6 | **Canon-scope row merges two senses.** Ch. 2 §2.8 L114 says "authority scope" names two things, canon scope (a claim's dimension: where canonicity holds) and grant scope (what an actor may act upon), kept apart; their containment is a premise left undefined until a mapping exists (§1.6; A-14). 09.20.7 L116 gives "authority scope / canon scope" as F + In from D's act `scope` field and `covers`. That is the grant-scope sense. | high (DC-5) | 02.08 L114; 03.01 L18, L80 | Row: grant scope F + In (decided fields); canon scope Open (or Out, Int) until the §1.6 mapping exists. | 09.20 L116; §9.20.9 | the two senses are separate rows |
| F7 | **Governance status "Δ (its standing part)" (L115) uses F6 as if decided.** CON-0003 is invalid and F6 (split standing from lifecycle) is open. Naming a "standing part" presupposes the split. (Same cluster: "owner (Ch. 1 §1.11)" L111 maps the maintainer sense of §1.11 to the grant owner of F22; those are different senses: "who must maintain this" vs the application that holds the grant's uses; Ch. 1 §1.11 and 09.13 L38.) | normal | 03.01 L52–58; THEORY-MAP F6; 01.11 "Ownership is a property of its own" | Mark governance status Open (F6) throughout; split the owner row by sense. | 09.20 L111, L115 | rows say "Open" for the split |
| F8 | **Visibility "Out" (L110) is stronger than the book.** Q2 takes "a reader r if a read rule exists (Q6): then only entries r may see are returned" [Src 09.10 Q2], and Q6 is met by deny-all until a read rule exists (09.10 L94, L179). So visibility is an input of a core observer when a read rule exists. "Out" would be right only for the case without a read rule. | normal | 09.10 Q2, Q6 | Row: Open (input to Q2 once a read rule exists; Q6 candidate). | 09.20 L110 | row names Q2's reader parameter |
| F9 | **Th2/M1 refutes a narrower V than the table's own.** M1's vector is (standing, adopting actor, "not attested"). §9.20.7 L107 lists "cited bases" under provenance (F). With cited bases in provenance the two M1 vectors differ [run P1: False]. A vector keyed by all identified entries (grants as items) also differs, because g1 and g2 differ [run P6: False]. So M1 shows that per-item V = (standing, actors, grade) is insufficient. It does not show that an F13-attribute vector with provenance as 09.20.7 defines it is. The last sentence of Th2 ("any sufficient vector carries the record's distinctions") conflicts with §9.20.5 (the record is not shown minimal): a coarser sufficient state (Q) is not excluded. The step "rebuilds the record's information" is shown for three increments only; labelled Int, which is right. | normal (high if Th2 is cited as refuting V generally) | run P1, P6; 09.20 L71–72, L107 | Restate: "V = (standing, actors, grade) per item is insufficient (M1)". Replace the last sentence by "a sufficient state must separate what the observers separate". Test the table's V (with cited bases) explicitly or say it reduces to the record. | 09.20 L70–72, L134; 09.00 row 30 | Th2 names the V it refutes |
| F10 | **M5 compares values only.** Q1's output is "T, F or U, with the acts the answer rests on" [Src 09.10 Q1]; M5 compares CE*'s value. §9.20.5's claim "for Q1 alone the record is not a minimal state" is true of Q1's value. Whether acts in the output are returned whole (then the recorder shows) is not stated. 09.13 L51 already says recorder and status change no value (model check 20, 1,500 records), and one mark reads the recorder. | normal | run (M5 reproduced: 322 of 322); 09.13 L51 | State "Q1's value". Say what Q1/Q3 return as "acts". | 09.20 L88 | the claim names the observer it is about |
| F11 | **"Required fields per kind" is necessary only "because a clause reads it" (L41).** That is the current formulation's own reason; the commission's Step 2 says not to count a state necessary merely because it appears in the current formulation. No per-field removal test (which answer fails) is given except for the recorder (M5), which fails the test. | normal | 09.20 L41, L88 | Give a per-field table (field, reader, answer that changes) or say "necessary by decided clause, not by Req". | 09.20 L41, L95 | table or relabelling |
| F12 | **F37 branch implication (L128).** "Under (A) the kernel would need a carrier per instance, which no admitted kind records, so (A) needs a field or kind 𝒮 does not have." D treats an item as a governed identifier and is agnostic about what that identifier names (claim, or instance). Under (A) an instance is just an identifier; no field is forced. What (A) needs is the instance-to-claim relation, but only for the equality constraints on claim-level values (nature etc.), which §9.20.7 places Out. So the sentence is not shown and tilts toward (B), while F37 is the Principal's open fork (DB-5). | normal (touches a Principal fork) | 03.01 L70, L89; 09.20 L128 | Say: "𝒮 as defined is neutral between (A) and (B); the branches differ only for attributes the table puts Out". | 09.20 L128 | wording neutral |
| F13 | **Self-containment (R2).** §9.20 uses V, R, R+C, Q and DC-1…DC-7 as terms; they are defined only in review/T9-02-TURN/RESEARCH-QUESTION.md, not in the section. A book reader has no definition. Tier D words (above, addressed, remember, vector): "vector" is a technical term here, "above/addressed/remember" ordinary. | normal | 09.20 L70–88, L134 | Define V, R, R+C in §9.20 (one sentence each) or replace by plain words. | 09.20 L72, L78, L134 | defined in the section |
| F14 | **Decisions in force (L32)** omit those that fix rows of the table: F22 = (C + O) (owner, transfer), F36 decisions, DB-1, Θ_f (09.13 I7), designation as a kind of D (decision 2026-10-25). | normal | 09.13 L38, L52; 09.17 L64, L164 | Add them. | 09.20 L32 | list complete |
| F15 | **Index row 30** repeats F2 ("Q3's reference unwritten") and says "Req's core" while Th1 omits Q5 (F1). Change-log row (last row): the "before" column holds the commit-hash note of the research question and the "after" column "for review"; columns do not match the other rows. | normal | 09.00 L30; 09.99 last row | Fix with F1, F2; reformat the row. | 09.00 row 9.20; 09.99 | rows match the section |
| F16 | **Candidate-primitive wording (L126):** "History is derived from the record (agreeing with §9.8)". T9-03 keeps primitive minimality apart. I did not verify §9.8's own verdict. Safer: "history is not a separate field of 𝒮; it is carried by the record" and leave primitive status to T9-03. | normal | 09.19 T9-03 text; 09.20 L126 | Reword. | 09.20 L126 | wording scoped |

## 2. Per-part verdicts

| Part | Verdict | One-line reason and scope |
|---|---|---|
| §9.20.2 (position) | PROVISIONAL | Lines checked and right (TODO L12; 09.13 L81, L52; 09.19 L166–167, L396; 09.10 L201). Decisions list incomplete (F14); it does not misstate Q3. |
| §9.20.3 (distinctions) | PROVISIONAL | Derived rows right. Required-fields row circular (F11); store, attestation, integrity, content missing (F3, F1). |
| Th1 | PROVISIONAL | Steps 1, 2, 3, 5, 6 adequate for Q1, Q2, Q4 and the guards listed; Q5 absent (F1); Q3 status stale (F2); G-kind premise missing (F4); generic clauses missing (F3). Table below. |
| Th2 | PROVISIONAL | Refutes V = (standing, actors, grade) per item (M1, re-run). Does not refute the table's V (F9). |
| Th3 | VALIDATED | Within scope (CE*, g1 are functions of the record, so a store adds no information). M4 illustrates the hazard; it is not a necessity argument. |
| Th4 | VALIDATED | M2 (cut) and M3 (act time) re-run; they separate "no positions" and "no act times" from the record in D. |
| §9.20.5 (minimality) | PROVISIONAL | Q2 argument sound given "about" is defined for the kind (it is not defined for grants or roots). M5 value-level only (F10). Right to leave it open. |
| §9.20.6 (outcome) | PROVISIONAL | Label "partially resolved" right; reasons need F1–F4 corrections; "why not unresolved" rests on premises (Req over D, G-kind). |
| §9.20.7 (table) | PROVISIONAL; rows standing and canon scope INVALID as stated | F5, F6; rows owner, governance status, visibility overstate (F7, F8). |
| §9.20.8 (consequences) | PROVISIONAL | No TODO closed; TODO changes are proposals. F37 implication tilts (F12); history wording (F16). |
| Model t902_state_model.py | VALIDATED (form only) | Reproduces its run file; M1–M5 assert what they say; scope is the exhibited pairs. |
| Index row 9.20 | PROVISIONAL | Stale Q3 phrase (F15). |
| Change-log row | PROVISIONAL | Column placement (F15). |

## 3. Th1 proof-step table (my own)

| Step | Justification given | My check | Objects defined in the book? | Adequate? |
|---|---|---|---|---|
| 1 Q1 = CE*(k,x,t;R≤s), θ(u) | 09.10 Q1; 09.13 | matches Q1 Reference line | yes (θ fixed to Θ_f, 09.13 I7) | yes |
| 2 Q2 = record cut at s | 09.10 Q2 | yes; omits "r may see" (F8) | "about k" undefined for grants/roots | yes, with F8 |
| 3 Q4 = g1 at s | 09.18.2; U2 | yes (purpose, g, identity link are inputs) | yes | yes |
| 4 Q3 over recorded grants, reference "to be written" | 09.10 Q3 | stale; reference is a construction on D (F2) | yes (R5) | inadequate: wrong status |
| (missing) Q5 | — | core item; inputs not in D (F1) | rule content in L: not a field | inadequate: premise missing |
| 5 every guard reads the record and CE* | 09.13.2; 09.18.5 G8 | list omits the generic clauses and the store-owner check (F3) | store, attestation: defined (09.17 L164) | partly |
| 6 effect of each input is an entry or a tombstone | 09.10.3; 09.19.4 G-kind | holds for G-kind only; U3 (b) unfixed (F4) | G-kind stated, not fixed | inadequate as unscoped |
| 7 same record, same answer | 1–6 | true by construction from 1–6 (F4) | — | adequate once 1–6 are |

**Th1 scope.** It is right to exclude the kinds outside D (09.13 L52). Premises C1 = constitute, "Req over D" and the inputs are stated where used (L56), except G-kind and Q5's inputs.

## 4. Questions 2 to 11

**Q2: completeness against my step 1.** Where 09.20 is better than my step 1: θ is an input (fixed to Θ_f; my "undecided" was weaker); M2 and M3 give positions and act times with a countermodel each; it keeps minimality open. Where I was right and 09.20 differs: visibility Open (F8); standing ≠ current authority (F5); owner homonym (F7). What I missed and 09.20 also misses: Q5 and rule content; generic clauses (F1, F3). What I missed and 09.20 has: nothing further. Where we differ, the evidence supports me on F5, F6, F8 and 09.20 on θ.

**Q3: step 1 (§9.20.2).** Lines right. Decisions list incomplete (F14).

**Q4: step 2.** Entries; record order; act time; removal state: right. Derived rows right (entry identity, standing, class, history variable, current owner of a grant). Required-fields row counts necessity by clause (F11). Missing distinctions: store, kernel attestation, integrity result, carrier in force (derived from designations), content (F1, F3).

**Q5: premises.** C1 = constitute and Req over D are stated. Missing: G-kind, Q5's inputs; Q3's status wrong. M1 does not refute V as the table defines provenance (F9). "Rebuilds the record's information" is shown for three distinctions only, and the claim is labelled Int.

**Model probes (copy, python3 -I, my scratch).** Re-ran t902_state_model.py: all five lines equal the run file.

| Probe | Result |
|---|---|
| P1: provenance including cited bases, M1 pair | vectors differ (False) |
| P1b: annul of an absent target in r2 | in D (True) |
| P2: grant recorder A vs Z | Q1 equal on 2106 of 2106; D-membership same |
| P3: root recorder P vs Q | 2106 of 2106 |
| P4: grant auth signed-as-other vs unsigned | 2106 of 2106 |
| P5: grant end 9 vs 8 (control) | 2096 of 2106: distinguished |
| P6: per-entry standing vector (grants as items), r1 vs r2 | differs |

Probe cases: items k, k2, g, h, r, a; t = 0..12; every cut; eight extensions (none, two annulments, a revocation, an adoption, a grant, an adoption under it, an annulment of the root). The model's Q1 value only, finite, no general claim. (The model sees recorder and auth only through a mark; it does not model Q2, Q3 outputs, W's kinds or guards that read store and attestation.)

**Q6: minimality (DC-2).** The Q2 claim is sound as stated for "entries about k", given that "about" is defined (it is for adoption by `item` and for evidence by `about`). It is not defined for grants, roots, designations, transfers. Two records differing only in the recorder or authentication status of a grant or root entry are not separated by Q1's values (P2–P4). Whether Q1's or Q3's output (the acts the answer rests on, "found, with the grant") separates them depends on whether acts are returned whole; the book does not say. Under value-only outputs such a pair exists, so the record is not minimal; under whole-entry outputs I found none among these fields. Fields read by a guard or the generic clauses (store, attestation, the transfer's recording application) are read, so they are distinguished. Note: identity excludes recorder and auth (09.20 L45, 09.16 R2), so two such entries have one identity; merge() keeps the set of recorders (ce_model L1004–1020). D already behaves partly as the quotient Q for those two fields. §9.20.5's statement "not shown" is right; I did not prove a general result either way.

**Q7: outcome.** "Partially resolved" is right. Reasons to restate: sufficiency is shown input by input, with Q5, the generic clauses and G-kind open (F1, F3, F4); minimality open; two table rows wrong; F13's vocabulary open. "Why not unresolved" rests on premises (Req over D, G-kind), which should be said.

**Q8: table.** Kinds right: provenance F (with F3 additions); evidential grade Δ with G7 open; identity (entries Δ, items Open); nature Out (Int); representation Out/F; operational state Out/Open; F13a F+Open. Wrong or too strong: standing (F5), canon scope (F6), governance status (F7), owner (F7), visibility (F8). Decided beyond the book: standing and canon-scope rows (F5, F6), the governance-status standing part (F6/F7).

**Q9: consequences.** No TODO closed by implication; rows are proposals. The F37 branch implication is not shown and tilts (F12). "History derived" is wording close to T9-03 (F16).

**Q10: disproof conditions.** DC-1: not shown excluded for Q5, content, generic clauses (F1, F3). DC-2: not claimed; handled honestly. DC-3: met for V = (standing, actors, grade) only (F9). DC-4: not met; Th3 sound. DC-5: met by standing and canon-scope rows (F5, F6). DC-6: touched by governance status (F6) and the F37 sentence (F12). DC-7: met (one-point and empty-Req cases handled at L82).

**Q11: limits (Part 2.3).** State space not treated as settled: yes. No primitive accepted: yes, except the wording at L126 (F16). Existence kept apart from primitive minimality: yes (L124). No fork resolved beyond existing decisions: touched by F5–F7, F12. No derivation-chain turn, no T9-21: yes.

**Q12:** not asked of B.

## 5. Not checked

Q2's "about" for every kind; §9.8's verdict on history as a primitive; R2 tier A–C (the tool was not run; I judged the in-scope words by reading); quote check (Professor's script, not re-run; I checked by hand: the Q3 phrase, "The pool needs 𝒮", the G-kind phrase, "Act times are not compared", "stored is not true"); 09.10 L60's wording is a book defect I did not correct; W's kinds in the model (no probe with evid, ewdr, erst).

## Files opened

BRIEF-STEP2.md; RESEARCH-QUESTION.md (in review/T9-02-TURN/); chapter_09/09.20-the-state-space.md (whole); tmp/tools/t902_state_model.py (whole); tmp/tools/t902_state_model-run-20261102.txt (whole); 03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md (lines 20–80 only); tmp/tools/ce_model.py (copied; read in parts); chapter_09: 09.10 (L25–92 parts), 09.13 (L20–62, lines 81, 52, 290), 09.16 (L76), 09.17 (L160–170), 09.19 (greps), 09.00-index.md (grep 9.20), 09.99-change-log.md (tail), TODO.md (greps, L12); chapter_02/02.08 (L114–118, greps); chapter_01/01.03 (one line); 06-theory/CONCEPT-REGISTER.tsv (grep CON-0141). I did not re-open STEP1-ANSWER-B.md from disk and did not open any other reviewer's file.

## Files copied (to the scratch dir …/scratchpad/rev-t902-b/)

tmp/tools/ce_model.py as t902-b-ce_model.py, and a second copy named ce_model.py (the import name the model needs); tmp/tools/t902_state_model.py as t902-b-state.py and t902-b-state-copy.py. Scripts I wrote: t902-b-probe.py.

## Commands and searches run

mkdir/cp of the files above; `python3 -I t902-b-state-copy.py` (5 lines, all assertions passed); `python3 -I t902-b-probe.py` (probes P1–P6); `wc -c` on 09.20, RESEARCH-QUESTION, the commission, the model and its run; `ls` of the T9-02-TURN folder; greps in ce_model.py for recorder, status, auth, in_D, in_generic, append_guard, W_FIELDS; greps and sed in chapter_09 (09.10, 09.13, 09.16, 09.17, 09.19, 09.00, 09.99, TODO.md) for Q3, Q5, "still to be written", "recorder is required", "scope sense"; grep and sed in chapter_02/02.08 for authority scope; grep -n "^CON-0141" in CONCEPT-REGISTER.tsv. No git commands. No refused files.
