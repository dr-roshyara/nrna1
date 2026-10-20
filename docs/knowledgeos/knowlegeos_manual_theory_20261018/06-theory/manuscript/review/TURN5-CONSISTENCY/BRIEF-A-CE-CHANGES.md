# Theory turn 5, commission A: §9.13's changes since the turn-4 review — brief for two independent reviewers

*2026-10-20. By the Professor. Reviewer A1 = Opus, reviewer A2 = Sonnet; neither sees the other's report, nor commission B's. The turn-5 brief became too long for one commission, so it is split in two, as the Principal allowed: **A** reviews §9.13's changes; **B** reviews global consistency (`BRIEF-B-PAIRS.md`). Both are settled before turn 6.*

## 1. What changed, and "resolved when"

The Principal's instruction: "the turn-5 brief lists all of §9.13's settlement changes as changed theory, including the new acyclic proof, not only the parts turn 5 uses." **Changed theory = everything in §9.13 that differs from the reviewed draft `2649b302c`**, in three commits:

| Commit | What |
|---|---|
| `fb1e67be5` | **§9.13 v2, the turn-4 settlement** (`review/TURN4-EVALUATOR/SETTLEMENT.md`, 18 rows): Θ_m replaced by Θ_f (the values Φ implements); forced (KK) vs grounded (WF) values; D a checkable field list; integrity failures refused (fail-closed); endings by the key (t, s); the root-as-basis and own-effects clauses; the program reading and the U encoding; R1's step table repaired and the WF bound O(n²); R2a's two kinds of F; R3 relabelled Int with the acyclic rival; corpus readings corrected (S1414, S1761, S0875); R4's θ fixed point "located, not closed"; R5 "non-degenerate"; the Q3 construction; the I1–I14 coverage table; defects rows 1–7 restated; §9.7 result 2 **restricted**; §9.8 Lemma D, §9.9 T1, §9.10 Q1/Q3 conditional; model v2; EXT-066–068 |
| `d4ffbc692` | **§9.13 v3, the Principal's turn-4 decisions** (decision record addendum 2026-10-20T17:25:51Z, `e59ec069a`): C1 = constitute within the kernel; D admits citations of earlier entries only, with the **design limit**; **the new lemma: on D, KK = WF** (R3, step table), with Apt and Bezem 1991 as the acyclic special case and Fages 1994 as the two-valued analogue; self-cancelling acts U with a refusal reason; model v3; EXT-069–071; dependants §9.07–§9.10, §9.12, THEORY-MAP F4, CON-0190, CON-0191 |
| `422f7eb56` | mutual annulment U with a refusal reason naming both acts (the Principal, confirming an extension); model check 9b |

**Resolved when:** every change above is either sound as stated or corrected; the lemma's proof is a proof (or its gap is named); each decision is applied as the decision record states it, no more and no less; each turn-4 settlement row's "resolved when" is actually met by the text; internal guarantees are kept apart from world assumptions.

## 2. What to review

- `W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md` (current); `git diff 2649b302c -- <that file>` shows all changes.
- `W/tmp/tools/ce_model.py` (v3) and its run `ce_model-run-20261020-v3.txt` (run `python3 -I ce_model.py`, about 20 seconds).
- `review/TURN4-EVALUATOR/SETTLEMENT.md` and the two turn-4 reports, against which v2 was settled.
- The decision record addendum: `W/03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md`, last addendum (read only).
- The dependants' diffs in the three commits (§9.07, §9.08, §9.09, §9.10, §9.12, THEORY-MAP F4, CON-0190, CON-0191, EXT-066–071, index, outline).
- Background: §1.14.1 (the formal model, parameters, defects), §9.12, §9.10.

**Challenge by type (practice 5):** R1 and the R3 lemma are mathematical (countermodel or invalid step). D and Θ_f are definitions (boundary cases, coherence, consequences, a rival). The application of the three decisions is normative: check it against the decision maker's words and the consequences, not by a falsifier.

**Questions to answer in particular:**
1. **The R3 lemma** ("if every positive dependence points to an earlier entry, WF = KK"): is each step valid? Step 2 (coverage atoms u ← not u are in no unfounded set), step 6 (Fitting's operator gives F), step 8 (KK ≤ WF ≤ I). Is the claim that D's programs are in general **not** acyclic in Apt and Bezem's sense correct (a negative dependence on a later entry; self-cancelling)? Is "found nowhere in the searches" stated no stronger than the search supports?
2. **D:** is the field list consistent with Φ and with `in_D`? Does "citations of earlier entries only, targets unrestricted" match the decision ("D allows citations of earlier entries only")? Is the design limit stated, and are its consequences (pre-assigned identifiers, merges under F26 (c)) correct?
3. **Θ_f:** does the table list exactly what Φ and current-authority implement (check against the code)?
4. **Refusal reasons:** does the definition in §9.13.3 match `ce_star_explained` and `act_explained`? Is a reason given in every U case the text names?
5. **Turn-4 rows:** is each row's "resolved when" met (spot-check at least A-01, A-04, A-06, A-08, A-14, F3, F6)?
6. **Dependants:** are §9.07 result 2, §9.08 Lemma D, §9.09 T1, §9.10 Q1/Q3 and §9.12 now consistent with v3 (C1 decided; KK = WF on D; forward citations outside D)?
7. **Prior work:** Apt and Bezem's Definition 1.4 and abstract can be read in the downloaded copy, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/dl-aptbezem/ab.pdf` (an outside work, not corpus; read only). Fages 1994 was seen only through Erdem and Lifschitz 2003; say whether the book claims more than that.

## 3. Verification marks (practice 6)

| Changed piece | Author | Read by the Professor | Reasoning checked by the Professor |
|---|---|---|---|
| §9.13 v2 and v3 (all) | Professor | yes | yes |
| R3 lemma (proof) | Professor (the sketch follows REPORT-A A-03 of turn 4) | yes | yes; **not reviewed** |
| `ce_model.py` v2, v3 | Professor | yes | yes (all checks run) |
| dependants, CON-0190/0191, EXT-066–071, THEORY-MAP F4 | Professor | yes | yes |

No helper session wrote any part.

## 4. Corpus reading (practice 2, corpus first)

For the settlement addendum: three logged scans (acyclic or tight programs and the known names; well-founded and Kripke–Kleene; acyclic derivations or delegation). Hit lines checked; S1721 read at its section (L284–304), logged. Turn 4's own reads as in its brief. Reviewers may open allowed corpus sources (sha256) to check Src claims.

## 5. Quote check (entry 60)

`corpus_quote_check.py`: §9.13 10 found, 0 not found; §9.07, §9.08, §9.09 unchanged against the baseline; §9.10, §9.12 no checked-form quotes.

## 6. Access

- The working book, register, map, outline, review folders, tools and git history of W: allowed. The decision record: read only.
- Corpus: only files in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (sha256 matching).
- **No access to the Ch. 0–2 basis files** (`archive/00-access/READ-LOG.tsv`). A check needing one is marked U.
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. No corpus or book text in any search. Edit nothing except your report.

## 7. Method and report

For each material claim: scope, definitions, assumptions; the derivation; the strongest counterexample (by type); a rival; the falsifier where testable; labels Src / Ded / Int / Hyp / Ext. Severity critical / high / normal (Rulebook §6; R5). A proof-step table for any derivation you judge adequate (R4). An observable "resolved when" for every finding.

Report to `W/06-theory/manuscript/review/TURN5-CONSISTENCY/REPORT-A1.md` (Opus) or `REPORT-A2.md` (Sonnet): verdict table · findings table (ID · finding · severity · evidence · correction · resolved when) · coverage. Final answer: a summary of at most 10 lines.
