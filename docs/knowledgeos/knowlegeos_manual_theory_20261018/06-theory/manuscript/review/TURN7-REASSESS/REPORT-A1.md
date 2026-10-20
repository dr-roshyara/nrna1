*Report of reviewer A1 (Opus), returned as hand-back text, verbatim (saved by the Professor, 2026-10-20; the reviewer's Write call was refused).*

**I could not write REPORT-A1.md.** The harness refused the Write call ("Subagents should return findings as text, not write report files"), so the file `W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-A1.md` does not exist. The full report is below for you to save. I edited nothing else.

**Summary (10 lines):**
1. There are no critical findings. There are 4 high and 15 normal.
2. R1, R2 and R3's lemma are sound. The proof-step tables are in §5 of the report.
3. **A1-01 (high):** the marks miss entries an answer rests on. `support` follows only live rules, so a cited basis whose holder, window or covers fails is never reached. My probes P1 and P2 give an unmarked T and an unmarked F that depend on an unsigned or transcribed entry. The text's claim that support is "a superset" is false.
4. **A1-03 (high):** the tombstone keeps "every field D requires", but the decision says "the fields citations rely on". That is more than worded, and the extra fields are what make "no answer changes" true. Under the decision's literal words, a tombstoned annulment could drop its target and change answers.
5. **A1-04 (high):** "the record stays in D" needs an integrity check defined on a tombstone. §9.13 never defines one; only §9.16 R5 states it as a premise.
6. **A1-11 (high):** the turn-7 tie note says ties "must" be broken by content, while also calling the tie rule reserved. Its "every merge order" holds only for orders that keep citations earlier. And content ties decide by identifier order: probe P7 flips with the id. The three-valued rival is not considered.
7. Decision 3 is applied as worded, including the unneeded-basis point. Two reason clauses are worded wider than the code (A1-13, A1-14).
8. I15 admits recorder = actor with signed-as-other, which is incoherent under §9.15's definition. The recorder field is never read, and the status is a declared label nobody verifies (A1-07, A1-08).
9. Several things are stale: line 44 still says removal puts a record outside D, the inputs table omits I15 and lists I12 as not read, CON-0191 is out of date, and §9.13 cites model v6 although the tie note relies on check 18, which exists only in v7.
10. All twelve v5 rows are met in the text. The model checks a looser iteration bound than R1 states (n + 2, not n + 1); on my 6,000-record run R1's bound n + 1 holds.

The two scratch probes are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`: `t7a1_probe.py` and `t7a1_bounds.py`.

---

# Turn 7, commission A: report of reviewer A1 (Opus)

*2026-10-20.*
- **Object:** `chapter_09/09.13-the-evaluator.md` v6 in full; `tmp/tools/ce_model.py` v7 and its run `ce_model-run-20261020-v7.txt`; CON-0191; §9.12 row I15.
- **Stance:** independent and adversarial. I judge; I do not author.
- **Changes:** none. The harness blocked writing this file, so this text is the whole report.
- **Probes:** `t7a1_probe.py` and `t7a1_bounds.py`, in the session scratchpad, run with `python3 -I`. They import the committed model and do not change it.

**Labels:**
- **Fact:** observed in a file or a run.
- **Ded:** follows from facts by a step I give.
- **Opinion:** my judgment; it may be overruled.

## 1. Verdict

| Object | Verdict |
|---|---|
| R1 (totality, uniqueness, bounds) | **Sound as stated** (Ded). The model checks a weaker bound than the text states (A1-18). The stated bound holds on my run. |
| R2 (forced T and F; U causes; the decision 3 cases) | **Sound.** The list of "undefined" causes is incomplete (A1-17). |
| R3 lemma (KK = WF without positive cycles) | **Sound.** |
| D's field list (v6, I15) | **Matches the code** (`FIELDS`, `AUTH`, `in_D`). It admits an incoherent combination and does not tie the status to verification (A1-07, A1-08). |
| The marks (v6) | **Not sound as stated.** "Support is a superset of what the value strictly needs" is false: two counterexamples (A1-01). The marks also flag answers that do not rest on the flagged entry (A1-02). |
| The tombstone (v6) | **Applied more than worded**, and the extension is load-bearing (A1-03). "The record stays in D" rests on an undefined integrity check (A1-04). A v5 sentence contradicts it (A1-06). |
| Decision 3 (as decided) | **Applied as worded**, including the point about a basis the act does not need. Two wording defects in the reason clauses (A1-13, A1-14). |
| Turn-7 tie note | **Overstated.** Position dependence elsewhere in §9.13 is not followed through (A1-11, A1-12). |
| CON-0191 and the section's tool references | **Stale** (A1-15, A1-16). |
| v5 rows 1–12 | **Each met in the text** (§4). Row 6 carries A1-13 and A1-14; row 8 is not met in the model's ratio (A1-18). |

Severity counts: no critical finding; four high (A1-01, A1-03, A1-04, A1-11); fifteen normal.

## 2. Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| A1-01 | **The marks miss entries an answer rests on: `support` is not a superset of what the answer rests on.** `support` follows only live rules of the program (`deps` is built from `program()`). A cited basis whose record constants fail (holder, window, covers = F) has no rule, so it is never reached, although the answer depends on its fields. The text's word "superset" is false. | high | **P1 (Fact).** Records: root r; grant g to A; adoption y by A under g; grant h with holder Z, unsigned; revocation v by B citing h. CE*(c, 5) = T; support = {g, r, v, y}; marks = []. With h's holder changed to B, CE* = F (P1b). So the answer rests on h, and h is unmarked. **P2 (Fact).** Records: grant g0 with holder Z, signed-as-other; grant g by A under g0; adoption y under g. CE* = F; support = {g, y}; marks = []. **Ded:** the Principal's "Q1 marks answers that rest on transcribed or unsigned entries" is not met. This is the unsafe direction. | Include every cited basis of every entry in the support, before the live-rule filter. Or define "rests on" once and derive the marks and the reasons from it. Drop "superset" until it is proved. | P1 and P2 are marked. A random check (A1-09) shows that every entry whose change alters the answer is in the support. |
| A1-02 | **The marks also flag entries the answer does not rest on, and "rests on" has two meanings.** For the reasons, "rests on" is the U walk. For the marks, it is a structural set. The code's support is also larger than the text's own definition: it takes every revocation with t ≤ t, not only those keyed above the adoption, and it ignores the queried scope and the adoption's validity. | normal | **P4 (Fact):** two intact bases, g2 transcribed. The answer is T and marked "transcribed g2"; without g2 it is still T. **P8 (Fact):** an unsigned revocation recorded before the adoption, not read by current-authority, still produces the mark "unsigned v". | Give one definition of "rests on", or name the marks' set differently and return the choice to the Principal. Align `support` with the text. | One term with one definition; P8 unmarked, or the text says why it is marked. |
| A1-03 | **The tombstone is applied more than worded.** The decision keeps "the fields citations rely on"; the book keeps "every field D requires". Only grants and roots are ever cited. The target, item, validity and grant fields of annulments, revocations, adoptions and endings are read by CE* but relied on by no citation. | high | **Fact:** `basis` and `phi` read citations only for grants and roots. **Fact (P6):** a tombstoned annulment keeps its target, and the answer stays F. **Ded:** under the literal wording the target may be dropped. Then y turns T and the answer changes, which §9.13.7 and §9.16 R5 deny. **Fact:** actor, holder and recorder, which are identity-bearing fields, are kept. The open question names only the entry identity. | Return the reading to the Principal, naming the extra fields and the identity-bearing fields. | The wording agrees with the decision verbatim, or is marked as returned. |
| A1-04 | **"The record stays in D" needs an integrity check on tombstones that §9.13 does not define.** I13 counts a hash mismatch as a failure, and a failing entry puts the record outside D. A tombstone has dropped its content. §9.16 R5 states the needed premise (K5); §9.13 does not. The model's `tombstone()` leaves `ok` untouched. It is also unstated how a removal is recorded (in place, or as an entry of some kind), and under what authority. | high | **Fact:** §9.13.2; §9.12 I13 and line 68; §9.16 line 138; `tombstone()`. **Ded:** under a content hash the claim fails. | State the integrity rule for tombstones as a premise (for example, a kept digest or the remover's signature), citing K5. State how and by whom a removal is recorded. | §9.13.2 states both, and the model keeps a digest or marks the check as not modelled. |
| A1-05 | **Check 17's tombstone test is vacuous, and "content" is undefined.** The tombstoned g has no `content` key, so `tombstone(g)` only sets the `removed` flag. | normal | **Fact:** check 17's `base`; `x.pop('content', None)`. | Define "content". Test a tombstone with non-empty content, plus a negative control that drops a required field. | The check asserts both. |
| A1-06 | **A v5 sentence contradicts v6.** §9.13.2 line 44 says "So does a removal of a cited entry" (puts the record outside D). Line 37 says that after a tombstone the record stays in D. | normal | **Fact:** lines 44 and 37. | Add "without a tombstone". | Fixed. |
| A1-07 | **I15 admits an incoherent combination, and the recorder field is never read.** §9.15 defines signed-as-other as "verifies under a key bound to another party, who records on the actor's behalf". `in_D` admits recorder = actor with signed-as-other. `marks()` never reads `recorder`, so an unsigned entry with recorder ≠ actor is not marked as transcribed. | normal | **P3 (Fact):** the combination is accepted into D. **Fact:** `marks()` reads only `auth`. | Add a domain condition (signed-as-other ⇒ recorder ≠ actor, with the key bound to the recorder), or define the combination's meaning. State the marks' rule over both fields. | `in_D` and the marks state the rule. |
| A1-08 | **The status is a declared label.** §9.15 says "verifies" is a fact about the entry, but D checks only that the field has one of three values. An entry can declare signed-as-actor and avoid the marks. | normal | **Fact:** `in_D` checks only membership in `AUTH`. | State that I13 computes the status, or list the declaration as an assumption in §9.15. | Stated. |
| A1-09 | **The defaults keep every earlier check mark-free.** `defaults=True` sets recorder = actor and signed-as-actor everywhere. No value depends on these fields (Fact, by inspection). But only check 17's hand-made record exercises the marks, which is why A1-01 went unseen. | normal | **Fact:** `Record.__init__`; checks 1–16 and 18. | Add a random check: on records in D, every entry whose change alters the answer is in the support. | The check passes after A1-01 is fixed. |
| A1-10 | **The inputs table is stale.** It has no row for I15, and lists I12 as "not read", although the marks read the tombstone flag. §9.13.10 calls I12 decided at line 297 and open at line 302. | normal | **Fact:** the §9.13.2 table; lines 297 and 302. | Add the I15 and I12 rows, each "read by the marks only", and merge the two open items. | Fixed. |
| A1-11 | **The turn-7 tie note is overstated.** (a) It says ties "must" be broken by content, then calls the tie rule a reserved value of θ. (b) "Every merge order gives the same answers" holds only for orders that keep citations earlier; other orders leave D. (c) Content ties decide by identifier order: in the race, F only because "e" sorts before "x". | high | **Fact:** `linear_extension`; the race ids. **P7 (Fact):** a same-time ending and an adoption: T with adoption id "a_h", F with "z_h". **Rival:** treat same-time acts of different applications as unordered (the F26 (b) route of §9.13.5 row 4); the race then gives U with a reason. | Make the note conditional ("if answers must not depend on the merge order…"). Restrict the claim to citation-respecting orders. Say that the order is by identifier, and record the rival. | Done, with the reserved choice not prejudged. |
| A1-12 | **Position on ties is read beyond the key.** Inside the evaluator, positions are read only through the key (Fact, by inspection). Beyond it: (i) §9.13.5 row 4 and check 10 say a same-time ending counts if "recorded before", which is false under content ties. (ii) R5's Q3 places the hypothetical act h "after the cut"; under content ties the answer depends on h's identifier (P7). (iii) The Θ_f table lacks the tie rule. (iv) CON-0191 says "(act time, position)". | normal | **Fact:** row 4; R5; the Θ_f table; CON-0191; P7. | Add a reserved tie-rule row to Θ_f. Restate row 4 and R5 under each rule. Give Q3's h a tie convention. | Done. |
| A1-13 | **A reason does not always name the component.** In the code, if a component holds a self-cancelling act, only that act and its link are named. | normal | **P5 (Fact):** component {g1, e, a, b}. The reason for a is "self-cancelling act e…", naming neither a nor b. The reason is sound (the named acts are U), but it is not the component. | Name all the acts of the component, or reword the text. | The text and `u_reasons` agree on P5. |
| A1-14 | **Reason (ii) is worded wider than decision 3.** The text says "every undefined covers pair on a live rule". The code names only the pairs the walk reaches, which is what decision 3 requires. | normal | **Fact (bounds probe):** mutual annulment x, y, plus an unrelated adoption w with an undefined covers pair on a live rule. The reason for x does not name w's pair. | Say "met by the walk of (i)". | Fixed. |
| A1-15 | **The section cites the wrong model version, and there is a typo.** §9.13 cites v6 and run v6, but the tie note relies on check 18, which exists only in v7. Apart from check 18, the runs are identical (my diff). There is also the typo "With the marks, For U it adds". | normal | **Fact:** lines 3 and 316; the run diff. | Cite v7 and fix the typo. | Fixed. |
| A1-16 | **CON-0191 is stale.** It still says decision 3's reading is "returned to the Principal" and cites model v5. It has no I15, marks or tombstone. | normal | **Fact:** CONCEPT-REGISTER row CON-0191. | Update the row. | The row matches v6. |
| A1-17 | **R2's list of "undefined" causes is incomplete.** It omits the dangling citation, a repeated identifier, and a status outside the three allowed values. | normal | **Fact:** R2; `in_D`. | Complete the list, or refer to §9.13.2. | Fixed. |
| A1-18 | **The model checks a looser bound than R1 states.** Checks 2 and 2b divide by `len(rec.e) + 1`, which is n + 2 when n counts non-root acts, and assert a ratio ≤ 1. "Worst 0.6 of the bound" is therefore a ratio to another bound. | normal | **Fact:** check code. **Fact (6,000 records, n non-root):** 0 runs exceed n changing applications, n + 1 outer rounds, or 2(n + 1)² inner passes. Worst ratios to n + 1: 0.83 for changing applications, 1.0 for outer rounds (attained). | Use the non-root count. | The run reports the ratio to n + 1. |
| A1-19 | **Only item answers carry marks.** authorized(x) (read by §9.9 T1 premise (vi)) and Q3 have none. | normal | **Fact:** `marks`; `act_explained`. | Define marks for acts and Q3, or state that they are for items only. | Stated. |

## 3. The brief's questions

1. **Marks.**
   - They are defined over a structural set. That set is neither the decision's "rests on" nor a superset of it (A1-01, A1-02).
   - The support is well-defined for T, F and U alike, because it does not read the value.
   - For F by C1 the support is empty, so there are no marks. This is defined, but not stated in §9.13.
2. **The tombstone.**
   - **Kept fields:** more than the decision names (A1-03).
   - **Integrity check on a tombstone:** not defined in §9.13 (A1-04).
   - **Can it change an answer?** Under the book's reading and A1-04's premise, no value changes, but the marks gain "removed content". Under the decision's literal wording, values can change (A1-03).
3. **I15.**
   - Signed-as-other with actor = recorder is incoherent under §9.15, and D admits it (A1-07).
   - The defaults hide no value dependence, but they hide the marks from every random check (A1-09).
4. **Decision 3.** Met. The walk follows only U bodies, starting from U alternatives. Check 15d covers the three clauses, and check 16 covers roots. The reason for the F case is not required: the Principal confirmed "as you applied it".
5. **Ties.** Within the evaluator, only through the key. Beyond it: D's membership, row 4 and check 10, Q3, the Θ_f table and CON-0191 (A1-11, A1-12).

## 4. The v5 rows

| Row | Status |
|---|---|
| 1, 3, 4, 5, 7, 9, 10, 11, 12 | Met. |
| 2 | Met. Line 44 is stale for removals (A1-06), and R2's list omits the dangling citation (A1-17). |
| 6 | Met in the code. The text is wider at (ii) (A1-14) and at "names the component" (A1-13). |
| 8 | Met in the text, not in the model's ratio (A1-18). |

Row 7: I traced P's four boundary cases in `current`, and they hold.

## 5. Proof-step tables for derivations I judge adequate

**R1, the KK bound.**

| Step | Claim | Why |
|---|---|---|
| 1 | Φ is monotone. | Strong Kleene connectives with fixed constants. Check 1: 0 violations in 800 pairs. |
| 2 | Bottom ≤ Φ(bottom). | Roots are T; the rest are U. |
| 3 | Each non-root atom changes at most once. | 1, 2, and three values. |
| 4 | At most n + 1 applications are needed. | 3. My run: 0 violations in 6,000 records. |
| 5 | The limit is the least fixed point. | Induction along the chain. |

**The R3 lemma.** I re-derived steps 2–8 and found no invalid step.

| Step | Claim | Why |
|---|---|---|
| 1 | Positive atoms are earlier in the order, and u atoms are in no unfounded set. | The hypothesis; step 2. |
| 2 | The earliest member of A that is not false has a false literal in every rule. | Every earlier member of A is false. |
| 3 | GUS(KK) = F(KK), so KK is a fixed point of W_P, and WF ≤ KK. | Fitting fixed point; VRS 1991. |
| 4 | WF is a Fitting fixed point, so KK ≤ WF. | An undefined atom is in no unfounded set and has no true body, so its body is U. |

**A tombstone changes no value** (under the book's reading and A1-04's premise).

| Step | Claim | Why |
|---|---|---|
| 1 | Φ, current-authority and `in_D` read only D's fields and positions. | Code inspection. |
| 2 | The tombstone keeps all of D's fields and the position. | §9.13.2; `tombstone()`. |
| 3 | If the tombstone passes I13, the record stays in D. | The premise. |
| 4 | Values and reasons are unchanged; the marks gain "removed content". | 1–3. |

**Content ties give the same answers across merge orders** (restricted to citation-respecting orders).

| Step | Claim | Why |
|---|---|---|
| 1 | The key (t, id) does not depend on position. | Definition. |
| 2 | The evaluator reads position only through the key. | Code inspection. |
| 3 | Every linear extension of the citation order is in D. | Citations point earlier; the other conditions are per entry. |
| 4 | So every such order gives the same answers. | 1–3. Check 18: 600 of 600. |

## 6. Coverage

- **Read in full:** COMMON.md; BRIEF-A-CE-V6.md; §9.13 (333 lines); `ce_model.py` (1,029 lines); run v7. I compared runs v6 and v7.
- **Read in part:**
  - §9.12 rows I12, I13 and I15, and line 68;
  - §9.15 lines 30–50, 90–100, 115–128 and 164–175;
  - §9.16's tombstone lines, by grep;
  - CON-0191 and CON-0194;
  - F30 in THEORY-MAP.
- **Run:** probes P1–P8 and the bounds and reason-(ii) probe. Their outputs are quoted in the findings.
- **Not done:**
  - I did not re-run the quote check, and I opened no corpus file. v6 adds no quotation.
  - I did not review §9.14–§9.16 beyond the lines cited.
  - I searched for a counterexample to R1 step 8's outer-round count only in the 6,000-record sample, and found none.
- **Access:** I read no basis file, no other REPORT file and no forbidden path.
- **Strongest statement:** A1-01. It rests on two constructed records run on the committed code.
