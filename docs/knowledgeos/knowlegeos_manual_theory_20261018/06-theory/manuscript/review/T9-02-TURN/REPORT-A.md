T9-02 REVIEW REPORT A

*Reviewer A (kos-lane-reviewer, Opus). Topic review of the §9.20 draft. Process independence: I worked from my saved step-1 answer and the files this brief allows; I opened no other reviewer's answer or report. My step-1 answer was in my working context, so I did not re-open STEP1-ANSWER-A.md.*

## Summary

1. "Partially resolved" is the right outcome. Th2 and Th3 hold, and Th4 holds within its scope. Th1 holds as **definitional** sufficiency (the reference definitions are defined over the record), not as a result with independent content.
2. Th1's scope leaves out two things the book contains:
   - Q5, which §9.19 L167 counts in Req's core;
   - the held act of §5.6, whose once-only handling is a decided "prevent" law (§9.16 L184, L192) that has no kind in D.
   Both meet DC-1 as fixed, unless they are moved explicitly out of scope.
3. The minimality claim of §9.20.5 is too weak in one place and too strong in another.
   - I exhibit a DC-2 pair (model, plus Ded from Φ's text): records that differ only in the **root's or a grant's act time**. Q1's values and marks are equal at every cut and after every extension tried, and the guard verdicts are equal.
   - The claim "Q2 restores it" depends on Q2 returning whole entries, but §9.16 L93's identity leaves out the recorder.
4. §9.20.3 says "each field is read by a clause". That is false for those act times, so the row counts a field necessary because D lists it, which is what the commission forbids.
5. The §9.20.7 table goes beyond the book in four rows:
   - owner: a homonym, since the grant's owner application is not §1.11's maintainer;
   - nature: "Out" conflicts with §9.13 L104, where I1's "kind" is read (DC-5, so the row should be Open);
   - canon scope: it is equated with act scope, a mapping §9.12 L95 calls undefined;
   - governance status: its "standing part" presupposes the split F6 has not decided.
6. The F37 branch implication in §9.20.8 conflates F37 with act-object granularity, which §9.12.3 keeps as a separate parameter. "(B) needs no new field" is true only when acts name claims.
7. My step-1 answer said covers was "undecided". §9.20 is right that covers is an input, and the book says so (§10.5 L245–247). But §9.20 omits the tension recorded there: K3 assumes an input-acceptance step that is never specified.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | Th1 and §9.20.6 say "Req's core" but omit Q5. §9.19 L167 lists the core as (Q1–Q3, Q5, O1–O4). Q5's input, the rules "written in L" (§9.10 L83), is neither in the record nor among the stated inputs of §9.20.3 L51 | high (DC-1) | §9.19 L167; §9.10 L80–89 | either add L-formalised rules as a stated input, or scope Th1 to Q1–Q4 and say why Q5 is set aside | §9.20 L55–68, L95; 09.00 L30 | Th1's scope names Q5 |
| A-02 | Held acts are missing. Law 18 → (c) reading (i) is decided (§9.11 L96), and once-only handling is "prevent" (§9.16 L184). The held act must therefore be in the state before its object exists, yet it is "not a kind of D" (§9.16 L192; §9.10 L229) and is not among the kinds §9.20 excludes | high (DC-1) | as cited | add it to §9.20.3 as Undecided and to Th1's exclusions; add it to §9.20.9's counterexamples | §9.20 L68, L97, L136 | the row exists |
| A-03 | A DC-2 witness exists in the model. Base record: root, grant, adoption. Change the root's act time from 0 to −3, or the grant's act time from 1 to 0. Q1 values and marks agree in 651 of 651 cases each (item, t, cut, over 8 extensions), and the guard verdicts are equal. **Ded from Φ:** basis(root) reads actor and covers; basis(grant) reads the window and the act's own time t_x, never the grant's own act time; current-authority reads only adoption and revocation times (§9.13 L119–129). The pair is observable only if "the acts the answer rests on" (§9.10 L34) is returned as content identities, which include the act time (§9.16 L93). The model returns assigned ids (support = [a, g, r]) | high | `t902-a-probes.py` P3, `t902-a-probe2.py` | §9.20.5: "for Q1's values, marks and the guards, R is not minimal (entries about no item, e.g. act times of roots and grants); whether Q1's act list separates them depends on its output form, which §9.10 does not fix" | §9.20 L88–90, L97 | the output form is stated, or R is weakened to a quotient |
| A-04 | §9.20.3 L41 claims every required field is read by a clause; A-03 shows two that are not. The same pattern appears in §9.13 L94 (revocation scope) and L110 (I2's recording time and reason are not read) | high | §9.13 L94, L110 | split the row into "read" and "required by D, not read (definitional only)" | §9.20 L41 | split |
| A-05 | §9.20.5's "Q2 restores it" rests on Q2 returning whole entries. The book's own identity excludes the recorder and the status (§9.16 L93), and merging joins copies that differ only there. So M5's pair is, by the book's identity, **one** act | normal | §9.16 L93–96 | state that the claim is conditional on Q2 returning fields outside identity | §9.20 L89 | stated |
| A-06 | M5 compares Q1 **values**; Q1's output also carries marks. Under signed-as-other, recorder A against Z changes the marks (P2). §9.20 L88 notes this only in a parenthesis | normal | P2 | test marks in M5 | §9.20 L88 | done |
| A-07 | Owner row: §1.11's owner ("who must maintain", §1.11 L33) and the grant's owner application (§9.13 L29, F22) are homonyms. Classifying §1.11's attribute "F + Δ for grants" mixes them | high (ubiquitous language) | as cited | owner (§1.11): Open (a Req candidate, §9.10 L99). Grant owner application: a separate row | §9.20 L111 | split |
| A-08 | Nature is "Out", but §9.13 L104 lists I1, "item and kind", as **read**, while no kind in D carries an item's kind. The two sections disagree, which meets DC-5; and whether "kind" is a value of nature is F7, an open fork | high (DC-5) | §9.12 L22; §9.13 L104; THEORY-MAP F7 | Open, with the conflict named | §9.20 L113 | marked Open |
| A-09 | Canon scope "F + In": the act's scope field is not an item's canon scope. The mapping between grant scope and canon scope is undefined (§9.12 L95; §1.6 refinement 3), and canon scope has no bearer under (B) (§3.1 L80) | high (DC-6) | as cited | act scope: F. Canon scope: Open | §9.20 L116 | split |
| A-10 | "Δ (its standing part)" for governance status presupposes the split F6 would make | normal (DC-6) | §3.1 L52–58 | "if F6 splits it: standing Δ; otherwise Open" | §9.20 L115 | conditional |
| A-11 | The F37 implication in §9.20.8 conflates F37 with act-object granularity. If acts name documents, **both** branches need carrier@version and Carries (§9.12.3); (B) also needs a lifting rule and (A) an aggregation rule. "(B) needs no new field" is therefore conditional, and the asymmetry tilts the reader towards (B) | high | §9.12.3 (the B1 counterexample) | state the implication per granularity, with "neither decided" | §9.20 L128 | restated |
| A-12 | Th1 step 4 says Q3's reference is "still to be written". §9.10 L62 gives a construction on D (Int, not modelled); the "to be written" in L60 is the observable-consequence line | normal | §9.10 L60–62 | "a construction on D exists (Int); Th1 covers Q3 to the extent of that construction" | §9.20 L63, L68, L135, L138 | corrected |
| A-13 | covers, the identity link and θ as "In" is supported by §10.5 L245. Omitted: that a common, versioned value of each is needed (CON-0173), and the K3 tension, "no input-acceptance step is specified" (§10.5 L246–247) | normal | §10.5 L245–247 | cite both | §9.20 L51 | cited |
| A-14 | Th4's "required" is relative to two decided choices: cuts as position prefixes (§9.10 L33) and D's positional citation rule. The rival D_acyclic (§9.13 L70), or cuts named by entry sets (§9.17 L"Cut of ρ"), may need only a partial order | normal | as cited | add "relative to D and to prefix cuts" | §9.20 L80 | added |
| A-15 | V, R, R+C and Q are used in §9.20 (Th2, Th3) without being defined in the section; they are defined only in RESEARCH-QUESTION §2 | normal (R2) | §9.20 L70–78 | define them in §9.20.4 | — | done |
| A-16 | §9.8 L69 says the record's necessity "rests on CON-0134". That is so for Q1's values only. Q1's act list (§9.10 L34) and Q2 force the record without CON-0134 | normal (omission) | §9.8 L69; §9.10 L34, L50 | an R3 note on §9.8 L69 | §9.8 L69 | noted |
| A-17 | M1 uses a pre-emptive target (g1 is absent from r₂). Probe P1 gives the same divergence with both grants present, under an ending and under an annulment, so Th2 does not depend on unrestricted targets | — (strengthens Th2) | P1 | optional: use P1's pair | — | — |

## Verdicts

| Part | Verdict | Reason |
|---|---|---|
| §9.20.2 | PROVISIONAL | Lines are accurate. It omits F36's law 18 decision and the Q5 core list (A-01, A-02) |
| §9.20.3 | PROVISIONAL | A-02, A-04, A-13 |
| Th1 | PROVISIONAL | Steps 1–3 and 5–7 are adequate as definitional sufficiency. Step 4 is inadequate (miscited, A-12). Scope: premise missing for Q5 and held acts (A-01, A-02) |
| Th2 | VALIDATED | refutes V as stated; "rebuilds" is correctly labelled Int; P1 strengthens it |
| Th3 | VALIDATED | no decided rule reads a stored copy (§9.12 L64); M4 |
| Th4 | VALIDATED | in its scope, relative to D and prefix cuts (A-14) |
| §9.20.5 | PROVISIONAL | A-03, A-05, A-06 |
| §9.20.6 | VALIDATED | "partially resolved" is right; reasons A-01–A-03 should be added |
| §9.20.7 | PROVISIONAL | A-07–A-10 |
| §9.20.8 | PROVISIONAL | A-11. TODO changes are stated as consequences; nothing is closed |
| Model | VALIDATED (form) | re-run on a scratch copy; all assertions pass. M5 tests values, not marks (A-06) |
| Index row 9.20 / change-log row | VALIDATED | match the draft; the index row carries Th1's scope gap (A-01, A-02) |

**Disproof conditions.**
- DC-1: met for Q5 and for held acts unless they are scoped out (A-01, A-02).
- DC-2: met for Q1's values, marks and the guards (A-03).
- DC-3: refuted as §9.20 says.
- DC-4: not met.
- DC-5: met for nature (A-08).
- DC-6: borderline in A-09, A-10 and A-11.
- DC-7: passed.

**Commission limits (Part 2.3):** respected. No primitive is accepted, existence is kept apart from T9-03, and there is no T9-21 or derivation-chain work. The forks are touched only in A-09–A-11.

**Limits of this review.**
- I did not run qc.py, `undefined_terms.py` or `inbound_citations.py`. They need the book tree, and a directory copy is not allowed. R3 lines are listed by hand.
- R2's tier-D words: "above", "addressed" and "remember" are ordinary English and need no definition; "vector" is a defined technical term, covered by A-15.
- My probes use finite alphabets and one scope (DOM = {(s, s)}), so they show form only. A-03's general part rests on Φ's text.
- I read §9.13 only at L1–140 and the cited lines.

---
**Files opened:**
- review/T9-02-TURN/BRIEF-STEP2.md, RESEARCH-QUESTION.md
- 03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md (L60–152)
- chapter_09/09.20-the-state-space.md
- 09.00-index.md (grep); 09.99-change-log.md (tail); TODO.md (grep)
- 09.13 L290, L327 (sed); 09.16 L93–96 (sed) and greps; 09.11 (grep); 09.18 (grep)
- chapter_10/10.05-published-language.md L235–250 (sed); 10.99 (grep)
- tmp/tools/t902_state_model.py, t902_state_model-run-20261102.txt
- scratch copy of ce_model.py (parts)

**Files copied (individually):**
- tmp/tools/t902_state_model.py → `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-copy-t902_state_model.py`
- tmp/tools/t902_state_model.py → `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-tools/t902_state_model.py`
- tmp/tools/ce_model.py → `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-tools/ce_model.py`

**Scratch files written:**
- `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-probes.py`
- `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-probe2.py`

**Commands and searches:**
1. `ls` of the review folder; `wc -l` of §9.20, RESEARCH-QUESTION, the commission, the model and the run file.
2. `cat` of the run file; `sed` of commission L60–152.
3. `cp` of the model and ce_model, file by file; `python3 -I` on the scratch t902_state_model.py (all assertions passed).
4. In the scratch ce_model.py: grep "def |class "; `sed` L120–150, L194–240, L840–883, L1440–1470; grep "before(" and "'scope']".
5. `python3 -I t902-a-probes.py` (P1–P3) and `python3 -I t902-a-probe2.py` (guards, support).
6. grep "PL-12" in chapter_10/*.md; grep for identity in 09.16; `sed` of 10.05 L235–250 and 09.16 L93–96.
7. grep "covers|identity link" in 09.18; grep "Q5" in 09.19.
8. grep "9.20" in 09.00-index.md; `tail -2` of 09.99-change-log.md; grep "T9-02|T9-21|law 18|held" in TODO.md.
9. `sed` of 09.13 L290 and L327; grep for "18 → (c)" and "F36 decided" in 09.11; grep "held" in 09.13 and 09.16.

No git commands.
