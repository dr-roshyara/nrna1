*Commission T reviewer T2 (Sonnet), returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T22:02:02Z). sha256 of the hand-back text at receipt: `f0282dacb9de5f2293e6792852c1d7c3817cb914b86a3a74a23577b8cb3c0468` (UTF-8, the text below this line exactly).*

# C19 commission T, reviewer T2 (Sonnet). Report

Labels: **F** = fact (read or run), **D** = my deduction, **O** = opinion. Scratch scripts are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c19t2_a.py` to `c19t2_e.py`, `c19t2_h.py`, run with `python3 -I`. They import `ce_model.py` v9 from W/tmp/tools. I also re-ran the whole model: all assertions pass.

## 1. Verdict table

| Concept (scope) | Verdict | One-line reason |
|---|---|---|
| Φ with a three-valued (U at ties) order, §9.13 v8 | VALIDATED | Order is a record constant, so Φ stays monotone (F: 1,500 loop records, 0 violations; theory: strong Kleene is monotone). |
| KK = WF on D under the o ← not o atoms (R3 lemma) | VALIDATED as a result; **proof table not updated** (T2-10) | o only occurs in negative bodies and has the shape of u ← not u, so lemma steps 1–2 apply unchanged. No difference found on 6,000 crowded records or 427 loop records. |
| Program split of ¬(e ∧ o) | VALIDATED for one tied ending per basis | De Morgan and distributivity hold in strong Kleene. With k tied endings the split is 2^k rules (T2-10). |
| Consumption: "ends its grant for acts ordered after it" (authorization clause) | VALIDATED | A consumption ends the grant for acts ordered after it, matching §1.13.2's CONSUMED as a status. |
| Consumption inside current-authority (the "intact" clause) | **INVALID as stated** | Adoptions under a single-use grant can never be current (T2-01). |
| Consumption tied to the grant it consumes | **INVALID as stated** | Any holder of another grant can consume (T2-02). |
| Append guard as the "prevent single use" mechanism | **INVALID as stated**, PROVISIONAL if repaired | Counts unauthorized consumptions (T2-03). |
| Removal as a recorded act (kind `remove`, `removed_by`) | PROVISIONAL | Values cannot change; authorization gates nothing (T2-05, T2-06). |
| §9.16 R2 order-independence under unordered ties: values | VALIDATED | Positions are unread; F: 0 of 400 crowded histories differ. |
| §9.16 R2 order-independence: marks | **INVALID as stated** | Countermodel in T2-04. |
| §9.16 R3a (decision 1 per law, with reasons) | PROVISIONAL | The line is not derived from the criterion (T2-07). The Principal's list itself is applied as worded. |
| §9.16 R4 v3 (F22 consequence) | PROVISIONAL | Valid conditional chain, but the "delegable" half is not shown (T2-08). Offered as a consequence, not a decision: yes. |
| §9.15 K/L criterion and rows | PROVISIONAL | K3 meets both clauses (T2-12). All other rows meet exactly one clause. |
| §9.15 R4 signed-as-actor proof (one direction, with L8) | VALIDATED | Table in §3 below. |
| §9.14 formula h = max(2, sup \|J\| + t(J)), total orders | VALIDATED for \|X\| ≥ 2 | Table in §3 below; degenerate case in T2-15. |
| Professor's choice: signed-as-other needs recorder ≠ actor | PROVISIONAL | Technical in form, normative in effect (T2-14). |
| Professor's choice: marks for an act's authorization | VALIDATED, technical | Same closure started from the act. |

## 2. Findings table

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **T2-01** | **Consumption read through P makes a single-use grant unable to support a current adoption.** §9.13.3 current-authority puts consumptions in the "intact" clause, "no effective ending or consumption of it ordered after y". The Principal's wording was "ends its grant for acts ordered after it". The "intact" clause goes further and is the reserved P (conjunctive). | high | F (c19t2_a): grant g to A, adoption y by A at t=3, consumption k at t=4. y is T, but CE*(c,t=4) = F and CE*(c,5) = F. Consumption at t=2 gives y = F. Consumption at t=3 gives y = U, with k = T. Every timing leaves y either unauthorized, non-current or U. Check 21 tests only `x1`, `x3` (acts, not current-authority). | Remove consumption from the intact clause, or return "consumption and P" to the Principal as a normative question. Add a check of current-authority with an adoption and a later consumption. | Run shows an adoption before its consumption is current (or the text states the Principal's reading and the consequence). |
| **T2-02** | **A consumption is not tied to the grant it consumes.** D requires fields `cites` and `grant` but not `grant ∈ cites`. Φ authorizes via any cited basis. §9.13.2 says "its own authorization runs through the grant. A consumption by a non-holder is F and ends nothing." | high | F (c19t2_e): B holds gB, cites gB, `grant` = gA (A's grant), t=2. k = T, A's later adoption x = F, record in D. | D requires `grant ∈ cites`. Φ must require the consumed grant to be the basis (basis(g,k) for g = k.grant). Add a check with a non-holder who holds another grant. | The record above gives k = F and x = T. |
| **T2-03** | **The append guard has three defects.** (a) It refuses on any consumption of g, including an unauthorized (F) one. (b) A guard that counts only effective consumptions needs CE* at the guard. (c) It prevents a second **record**, not a second **use**. | high | (a) F (c19t2_c): kB by non-holder B is F, record in D; `append_guard(A's consume)` returns refused "already consumed". So any party can permanently block the real single use. (b) D: effectiveness is evaluator-dependent and can change later (annulling k1 makes k1 F, which reopens the grant). (c) R3a's reason rests on "work delivered under a second consumption", but a guard on entries prevents delivery only if delivery follows a successful append. That premise is nowhere stated (not in K1–K7 or L1–L8). | State the guard's counting rule (attempts or effective). If effective, the guard evaluates standing, which bears on T2-08. State the "use follows append" premise as a kernel or legitimacy assumption. | §9.13.2/§9.15 say what the guard counts and name the premise. |
| **T2-04** | **"No merge order can change any value or mark" (§9.16 R2 v3, check 18d) is false for marks.** The merge rule is silent on two tombstones of one entry that name different removal acts. The model keeps the first copy's `removed_by`. | high | F (c19t2_c): R1 holds g tombstoned by rm1 (actor P, authorized), R2 holds g tombstoned by rm2 (actor Z, F). Merge order [R1,R2] gives marks `['rests on removed content g']`. Order [R2,R1] adds `'removal not authorized: rm2 (F)'`. Values equal, marks differ. | Merge rule: the set of removal acts per tombstone; mark "removal not authorized" only if no removal act of it is T (or if all are not T; state which). Add this case to 18d. | Both orders give equal marks in the run. |
| **T2-05** | **`removed_by` is a pointer to a later position, but D says citations of earlier entries only.** The Principal's wording is that the tombstone "cites" the removal. | normal | F: `in_D` does not apply the citation-position rule to `removed_by`; the model accepts the record (c19t2_c, last line). A removal is recorded after its target, so the tombstone sits at the earlier position and points forward. | Say that `removed_by` is outside D's citation rule, or define the pointer's direction. | §9.13.2 states which. |
| **T2-06** | **An unauthorized removal changes nothing but a label; the Principal's "authorized" reads as a precondition.** The tombstone drops content regardless of the removal's standing. The removal's authorization is evaluated by Φ like any act, with its own self-declared scope and the single modelled activity, not tied to the target. | normal (normative part returned) | F: check 22 (`rm` by Z is F, values unchanged, mark only). D: Φ has no clause reading `remove`, so no value can change (answer to question 3: authorization is evaluated as for any act; an unauthorized removal changes no value). D: whether "mark, not refuse" is the right consequence is a normative choice. A standing condition cannot sit in D because D is static. | Label the mark-only consequence as a Professor's reading and return it. Require `covers` to relate the removal activity to the target's scope. | The text labels it and the Principal answers. |
| **T2-07** | **R3a's reasons do not follow from the criterion as worded.** The Principal's criterion concerns effects of a violation that a later recorded act cannot undo. The Professor's line is "the effect the law protects: outside the record vs standing". (i) Acting under ended authority also has world effects, which are dismissed as "legitimacy matters". (ii) Row 1's own text says the second consumption's standing is corrected by evaluation (F), exactly like row 5. (iii) Row 2 says applying a held act "changes standing at once", which is a standing effect. (iv) Row 3 names "supersession" as the correcting act, but supersession is not admitted to D. (v) The closing "Consequence for the guards (Ded)" labels the classification Ded. | normal | F: §9.16 R3a table and the paragraph "The line that carries the classification". D: under a uniform reading (all world effects, or standing only) the five rows do not split 2/3. | Present the line as the Professor's Int reading that reproduces the Principal's list. Fix row 2's reason and row 3's supersession. Relabel the Ded. The decisions need no change. | R3a labels each reason Int and removes the two defects. |
| **T2-08** | **R4 v3's conclusion has an unstated half.** "Recording and evaluation at a given cut remain delegable" is asserted. A guard that counts effective consumptions (T2-03) evaluates standing at the coordination point, so CE* placement would not be open for it. THEORY-MAP F22 and CON-0194 state "one per grant, **per held act**" unconditionally; R4 v3 says "if held acts become a kernel kind". The hybrid "(C) with kernel-provided coordination points" is a third value of F22, which the Principal reserved. | normal | F: §9.16 R4 v3; THEORY-MAP F22; CON-0194. D: step (i) is a valid sufficient condition (invariant confluence, EXT-087), given K3 and "kernel law". | Add "if the guard counts only effective consumptions, the guard needs CE*". Make the held-act clause conditional in the map and register. Say plainly that the hybrid is a consequence for the Principal's decision. | The three places agree. |
| **T2-09** | **Unordered ties: soundness is true but unstated, and "U = the record does not decide" overstates.** Monotonicity gives that every T or F of CE* holds under every resolution of the ties. The converse fails. | normal | F (c19t2_b): consumptions k1, k2 at t=2 (tied), later act x at t=5. CE* gives k1 = U, k2 = U, x = U, and the only fixed point is (U,U,U). In both tie resolutions x = F. §9.13.4 R2(d) lists only "undefined covers pair or a negative cycle" as causes of U; tie atoms are a third cause. | State the soundness property. Qualify the U gloss: U may also mean "every resolution agrees". Add the tie atoms to R2(d). | The text has the property and the qualification. |
| **T2-10** | **Proof table and bounds not updated for the tie atoms.** R3's lemma table steps 1–2 name only u. R1's WF bound "n+1 outer rounds" is for Φ on the record. The program route has 2^k rules per basis. | normal | F: §9.13.4 R3 lemma table. F (c19t2_d): worst outer rounds / (n+1) = 1.0 on 4,000 crowded records, not exceeded. | Extend step 1–2 to o atoms (they occur only in negative bodies, with the single rule o ← not o). Say that the split is polynomial with an auxiliary atom per tied pair. | The table names o. |
| **T2-11** | **Stale text.** (a) §9.16 R1 rows "G4 and §9.15 K4" ("consumption is not an admitted kind") and "§9.15 K2" ("ties by position, the rule now"). (b) R3 ("consumption is not admitted in D"). (c) R4 (ii)–(iii). (d) R6 row 9b ("not done"). (e) §9.15 R5 ("D orders by (t_x, s_x)"). (f) §9.15 R6 ("D admits every combination"). (g) §9.15 §9.15.8 "L1–L7". (h) CON-0191 definition ("endings ordered by the key (act time, position)"). (i) CON-0194 body (ties by content). (j) §9.13.3's description of the support omits removal acts, which the model's `support` includes. (k) The settlement row A1-16 "CON-0191 updated" is only partly met. | normal | F: texts as listed. | Update each. | A grep for the quoted phrases finds none. |
| **T2-12** | **K3 meets both clauses of §9.15's reworded criterion.** "The covers relation and the identity link are correct": a wrong covers input is named as a kernel failure, yet CE*'s answer stays correct relative to the input (G1), which is the legitimacy clause. | normal | F: §9.15.2 criterion and K3. | Define "correct" for an input (relative to what), or place K3 in one clause explicitly. | The criterion table puts K3 in one clause. |
| **T2-13** | **A content-derived identity collapses an identical second consumption.** Two consumptions with the same actor, grant, scope and act time are one entry (idempotence), so K4-d detection cannot see a second use. | normal | D from §9.16 R2 identity rule and the D field list; not run. | State the limit or add a nonce field (which leaves "derived from fields CE* reads"). | The text states the limit. |
| **T2-14** | **Professor's choice: signed-as-other requires recorder ≠ actor.** It was listed as a question for the Principal (settlement §6 item 8, "whether D should restrict the combinations"). It adds a whole-record refusal on a field that changes no value, where the Principal chose "marks, not refuses" (decision 2). It is evaded by an alias. | normal (normative part) | F: `in_D`; settlement §6 item 8. D: the field is a free string. | Treat as a normative boundary: either a mark "inconsistent status" or return it. Marks for an act's authorization stay technical. | The Principal's answer or the text changes to a mark. |
| **T2-15** | **§9.14 minor.** (a) h = max(2, …) is wrong for \|X\| = 1 (h = 1; F: brute force c19t2_h gives 1,2,3,4,5 for n = 1..5). R4 notes the degenerate case only for the forbidden-value form. (b) Check 8's upper bound "the product order's up- and down-sets are boxes, by R2" holds for **principal** up- and down-sets (which is what the model builds), not for all up-sets. | normal | F: c19t2_h; helly_check.py lines 247–267. | State "for \|X\| ≥ 2" and "principal". | Text carries both. |

## 3. Proof-step tables (derivations I judge adequate)

**P1. Φ monotone, KK = WF on D under ties (question 1).**

| Step | Claim | Judgement |
|---|---|---|
| 1 | before(e,x) ∈ {T,F,U} is a record constant, as covers is | Adequate: depends on act times only. |
| 2 | Φ is a composition of strong Kleene ∧ ∨ ¬ over atoms and constants, so it is monotone | Adequate (F: 1,500 random pairs, 0 violations). |
| 3 | o ← not o: o has no positive body atom, and "not o" is not false in KK (o = U), so o is in no unfounded set | Adequate. Same argument as for u. |
| 4 | o occurs only in negative bodies, so the positive dependency graph is unchanged (acyclic on D) | Adequate. The lemma steps 3–8 then run unchanged. |
| 5 | ¬(e ∧ o) = ¬e ∨ ¬o, and ∧ distributes over ∨ in strong Kleene | Adequate. |
| 6 | Every T and F of KK holds under every tie resolution (a resolution refines U constants to T/F; Φ monotone) | Adequate (D). This is the soundness property T2-09 asks the book to state. |
| 7 | F: no record found where the routes differ (6,000 crowded records for Φ vs the program; 427 loop records for fixed points). | Evidence, not proof. |

**P2. §9.14 formula (question 7).**

| Step | Claim | Judgement |
|---|---|---|
| 1 | A minimal empty family F of intervals and point complements: let J = ∩ of the intervals | Adequate. |
| 2 | If J = ∅, two intervals suffice (Helly in dimension 1): size 2 | Adequate. |
| 3 | Otherwise every forbidden point must lie in J (else droppable), and J ⊆ forbidden set, so forbidden set = J and J is finite | Adequate. |
| 4 | Intervals needed to cut out J: 0 if J = X, 1 if J is a proper initial or final segment, 2 otherwise | Adequate. |
| 5 | Minimality of "J's intervals plus all point complements": dropping an interval enlarges J by points not forbidden | Adequate. |
| 6 | Attainment: the sup of \|J\| + t(J) over finite convex J is attained when finite; J = X finite gives \|X\| + 0 | Adequate. Brute force on n = 2..5: n (F). Degenerate \|X\| = 1: h = 1 (T2-15). |

**P3. §9.15 R4, signed-as-actor under P-perf, one direction (question 6).**

| Step | Claim | Judgement |
|---|---|---|
| 1 | K5: the entry verifies under the key the record binds to the named actor | Adequate (premise). |
| 2 | L2: that key is the actor's. L8: only a holder of the key could produce the signature. L3 for this entry: no other holder used it here | Adequate. |
| 3 | Hence the actor wrote it. Under P-perf the writing is the act | Adequate. One direction only, as stated. |

**P4. §9.16 R4 v3, the F22 consequence (question 5).**

| Step | Claim | Judgement |
|---|---|---|
| 1 | Single use prevented (decision) and a kernel law (consumption admitted) | Valid from the decisions. |
| 2 | Not merge-safe (count bound), so by (i) coordination is needed | Adequate (invariant confluence). |
| 3 | Ties unordered, so positions are unread and the order needs no shared point | Adequate for CE*. D's citation rule still reads positions, but linear extensions satisfy it (R2(a)). |
| 4 | "Evaluation at a given cut remains delegable" | **Not shown** (T2-03(b), T2-08). |

## 4. Coverage

**Answers to the brief's questions.**
1. Φ is monotone. KK and WF agree on D. R3's lemma survives the o atoms (P1). The split is correct in strong Kleene. I found no record in D where the two routes differ.
2. The authorization reading of consumption is right for the "ordered after" clause. The current-authority use of consumption goes beyond the Principal's wording (T2-01). The guard does not prevent what decision 1 says (T2-03). Two consumptions arriving by merge: D does not refuse (the guard is not D's rule). The later one is F, ties give U for both, and acts after a tied pair are U although every resolution gives F (T2-09).
3. Removal: authorization is evaluated as for any act. An unauthorized removal changes no value. Mark-only is a Professor's reading (T2-05, T2-06).
4. R3a: the line is not applied consistently (T2-07).
5. R4 v3 is derived conditionally; the "delegable" half is asserted (T2-08). It is offered as a consequence, not as a decision.
6. §9.15: each K/L row meets one clause, except K3 (T2-12). R4's one-direction proof is valid with L8 (P3).
7. §9.14: complete for total orders, |X| ≥ 2 (P2).
8. "Signed-as-other requires recorder ≠ actor" is technical in form, normative in effect (T2-14). "Marks for an act" is technical.

**Read:** §9.13 v8, §9.14, §9.15 (to the corpus table), §9.16 whole; `ce_model.py` (all functions used); `helly_check.py` check 8; settlement §1 (A rows), §6–§8; THEORY-MAP rows F22, F26, F30, F36; register rows CON-0191, 0193, 0194; §1.13.2 (CONSUMED).
**Not done:** the 65 settlement rows were not checked one by one (only commission A's rows, spot-checks of B/C/D by reading the sections). I did not re-run `helly_check.py` (read the v4 run only). I read no corpus files, no EXT register rows, and not the §9.11 F36 note or the §9.06/§9.10 pointers. I checked no quotations. My random tests are form-level: items with one item, n ≤ 10, a toy generator.

## 5. Summary (10 lines)

1. The tie semantics (Φ monotone, KK = WF, program split) are sound; the lemma needs only a text update.
2. The Principal's decisions are applied as worded, except where the Professor extended them.
3. Four high findings, all in the new consumption/removal machinery: consumption inside "intact" (T2-01), consumption not tied to its grant (T2-02), the append guard (T2-03), merge-order dependence of marks (T2-04).
4. T2-01 and T2-02 are single records in D that contradict §9.13's own statements.
5. R3a's reasons and R4 v3's "delegable" half are Int and not derived (T2-07, T2-08).
6. §9.14 and §9.15 R4 are valid within scope (two minor edits).
7. Several stale passages remain in §9.16, §9.15 and the register (T2-11).
8. Two Professor's choices touch normative ground (T2-06, T2-14).
9. No critical finding. The F22 consequence stays PROVISIONAL.
10. Verdict counts: 15 findings, 0 critical, 4 high, 11 normal.

Process independence: I read no other reviewer's report. I ran only my own scratch scripts, which import `ce_model.py` from W/tmp/tools; the import may have refreshed `W/tmp/tools/__pycache__/ce_model.cpython-313.pyc` (no source file was edited).
