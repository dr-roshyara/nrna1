T9-22 REVIEW REPORT A

*Reviewer A (kos-lane-reviewer, Opus). Topic review of §9.21 (draft). Independence here means a separate process, not a separate person. Interruption: this step-2 run stopped once on a usage limit and was resumed under the same brief. No file was opened or run outside the lists below, before or after the stop.*

## Summary (≤ 10 lines)

1. §9.21 mostly agrees with my step-1 answer: G-kind = G-act under U6; no batching of acts; Q2 returns whole entries; Q1 and Q3 narrowed.
2. **Q2's scope is not derived** (§9.21 says it is). §9.10 itself requires Q2 to return what CE reads, including grants, roots and annulments. The derivation also uses forks that are still open (T9-14, T9-21). This undoes "reduced to one decision" and most rows of §9.21.4.
3. **Row M6 of §9.21.4 depends on which reading of entry identity (§9.16 L93) holds.** The book's R3 note B-07 says the two readings differ, and §9.21 picks one without saying so.
4. **The F_e form does not make the record minimal.** No output ever returns a designation, a withdrawal or restoration of reliance (ewdr, erst), or a grant that is never T.
5. **(2) and (3) hold for act entries only, and only on a reading.** Two things break the general claim:
   - evidence entries, which O2 says are not acts in A1's sense;
   - batches whose guards are all checked against the record as it was before the batch.
6. Not addressed: Q3's plural bases and roots; Q4's identical form question; my step-1 point that a delegated grant's act time is read by Φ.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines (citing) | Resolved when |
|---|---|---|---|---|---|---|
| A-01 | **Q2's scope as "derived" (items named by an entry's item field) is contradicted by the book.** §9.10 L186: "Q1 at a past cut is computable from Q2 with CE"; L194: "Q1 at a cut is CE over Q2's entries". CE reads grants, roots, annulments and endings, which this scope never returns. The derivation also treats an adoption's item field as a "governed identifier". Registration is not in D (§9.13 L52), and item identity is T9-14 and T9-21, both open. **DC-1 and DC-5 are met.** | high | §9.10 L45–46, L186, L194; §9.13 L52; §9.21 L62 | Reclass Q2's scope as **narrowed**. Options: items only; the entries CE reads for k (the support closure); every entry. Name the conflict with §9.10 L186/L194. "About k" is undefined for annulments, removals and endings of an entry about k: this is theory work. | §9.21 L11, L62, L85, L93, L96; index row 9.21 | Q2's scope is restated as narrowed, with the L186/L194 conflict resolved or recorded |
| A-02 | **M6 row "separated under F_id" is unconditional, but L93 has two readings.** L93 says identity is "derived from the fields CE* reads: kind and D's required fields other than the recorder and the status". B-07 says these two sets differ. The root's act time is required but not read by CE*. So under the "fields CE* reads" reading, identifier outputs do **not** separate M6. **DC-7 is met for the claim as stated.** | high | §9.16 L93 and its B-07 note; §9.20.5 L104 | State row 1 and §9.21.3 L66 as conditional on B-07. Make fixing the reading a precondition of the question in §9.21.5. | §9.21 L66, L74; §9.20.5 L109 | B-07 resolved, or the condition is stated |
| A-03 | **The F_e bullet and "the deciding question is exactly (i)" overstate.** Under F_e, these entries are still never returned: designations (not in Q1's support; not a Q3 basis); ewdr and erst (no item field; only Q4's reasons list them); a grant whose own value is F. So option (b) still leaves the record not minimal. The example given is partly wrong: an uncited grant that is T **is** returned by Q3 for its holder. | high | §9.13 L138 (support closure); §9.13 L255 (Q3 over T bases); §9.18 L111–113 | Restate: under (b), pairs are separated only for the entries some output returns; list the kinds no output returns. The decision fixes minimality only for returned entries. | §9.21 L80–81, L86 | Restated |
| A-04 | **"Unobservable" (3) needs a premise it does not state: entries of a batch are guarded one by one, in order.** Two counterexamples if all guards are checked against the record before the batch: two uses of one single-use grant in one batch both pass ("accepted only if T at append", §9.13 L45). A designation that omits a maximal entry added in the same batch also passes. Both reach records that single appends cannot reach. **DC-4 is met under batch-snapshot guarding.** Replies also differ (one reply against two; C6 result 8 counts replies as observable). | high | §9.13 L45, L48; §9.17 L161–166; §9.1 C6 result 8 | Add the premise of sequential guarding; scope replies out, or count them | §9.21 L8, L45–50; proposed §9.19 L164 note (§9.21 L95) | Premise stated |
| A-05 | **(2) is a reading, and it covers acts only.** The input-origin condition (C6 L93) describes inputs. What forbids a step from appending an act its input did not carry is the origin **transition invariant** (C6 L96). §9.1 L124 puts origin's kernel status under F36, and the F36 decisions (§9.11 L96) do not name law 2. Also, an evidence entry "is not an act in A1's sense" (§9.10 L115). So a composite that appends the carried act plus W entries meets C6 as written: **DC-3 is met for W kinds.** §9.21.1 item 2 and the T9-04 sentence "T5's route … closed by C6" overgeneralise. | normal | §9.1 L93, L96, L124; §9.10 L115; §9.11 L96 | Label (2) and (3) Int, or Ded on the stated premise that origin ∈ I_T. Scope them to act entries; leave W batching narrowed. | §9.21 L8, L33–44, L88 | Restated |
| A-06 | **"The forms differ in exactly two fields" (§9.21.1 item 4) contradicts §9.21.3 L66,** which lists a second difference: content beyond the required fields. The generic clauses (store, attestation, integrity result) and `removed_by` are not placed. Owner and recording application are inside identity (§9.16 L319, C20 W-11), so §9.21 is right on those. | normal | §9.16 L93, L319; §9.13 L48, L60 | Write "recorder, status, content beyond the required fields, and any generic clause outside the identity" | §9.21 L12 | Restated |
| A-07 | **The merge check ("R2 does not decide it") is incomplete.** The merge rule fixes only recorder (as a set), status (the weakest), the tombstone, and agreement on identity fields. It does not fix content beyond the required fields, or the store, which differs between the copies held in two applications' records. So a whole-entry output of a merged record is not fully defined. | normal | §9.16 L94–98, L319; §9.13 L48 | Add the limit | §9.21 L63 | Stated |
| A-08 | **Not addressed: §9.20.5's Ded "never the grant's act time" is false for delegated grants.** A grant is itself an act, and basis(g₁, g₂) tests t_{g₂} ∈ g₁'s window (§9.13 L122). Pair: g₂ at act time 3 or 6 under g₁ with window [2, 5); an adoption under g₂ is T in one record and F in the other. | high | §9.13 L120–125; §9.20.5 L104; §9.20.3 L48 | R3 note on §9.20.5 L104 and §9.20.3 L48: the Ded holds only for grants whose basis is a root | §9.20 L48, L104, L111, L117, L162; §9.21 L74 | Note placed |
| A-09 | **Q3 not fully narrowed.** The reference ranges over grants **and roots**, so "found" can name a root. Several bases can be T at once (plural bases are allowed, §9.16 L170), so the singular "the grant" is not a function. The options are: the set of T bases, or one basis chosen by a rule (itself a choice). | normal | §9.13 L255; §9.16 L170; §9.10 L57 | Add both points to (5) and to the question | §9.21 L64, L86 | Added |
| A-10 | **Omission: Q4's "evidence entries it rests on"** (§9.10 L70; g1's reasons, §9.18 L202) raises the same identity-or-entry question, and it bears on the minimality pairs for evidence entries | normal | §9.10 L70 | Include Q4 in the question, or exclude it with a stated reason | §9.21 L86 | Stated |
| A-11 | **(1)'s scope is too wide.** §9.10 L105's O1 list does not name annulment, ending, consumption, transfer, removal or designation. Removal can be O3; designation is record-keeping outside every area (§9.18 c4). O2's cut (one kind or three) is undecided. "Same pool" is shown only for kinds that are O1 kinds. | normal | §9.10 L105; §9.18 L223 | Scope (1) to the O1 kinds; mark O2's cut open | §9.21 L7, L31 | Restated |
| A-12 | **Tension with law 12 not addressed.** §1.16 L171 permits, and even prefers, a human approval recorded in the same atomic change as its effect: two acts. Law 12 reads atomicity as "implementation steps map to one step of the type" (§9.11 L48). With one act per step, that implementation step must map to two type steps. | normal | §1.16 L171; §9.11 L48 | State it as a refinement premise | §9.21 L43 | Stated |

## Verdicts

| Part | Verdict | Reason |
|---|---|---|
| §9.21.2 | VALIDATED | The current position is reported accurately |
| (1) | PROVISIONAL | Sound for O1's kinds; scope too wide (A-11) |
| (2) | PROVISIONAL | Holds for act entries on the reading origin ∈ I_T (A-05) |
| (3) | PROVISIONAL | The exclusion as in (2); unobservability needs sequential guarding (A-04) |
| (4) | VALIDATED | Scope: the same guards in force. Leaving the choice to T9-03 adopts no criterion and stays within the limits |
| (5) | Q2 output: VALIDATED (by its text; merge limit A-07). Q2 scope: INVALID as derived (A-01). Q1 and Q3 narrowed: VALIDATED, incomplete (A-09). "Exactly": INVALID (A-06) | — |
| §9.21.4 | INVALID as stated | A-02, A-03, A-08 |
| §9.21.5 | PROVISIONAL | The one-decision reduction fails while Q2's scope is open. The question needs B-07 as a precondition and Q3's plural bases and roots added. T9-03 and T9-04 are stated, not settled, except the T5 closure for W kinds (A-05) |
| §9.21.6 | PROVISIONAL | The proposed notes need rescoping (A-01, A-04, A-05) |
| Index and change-log rows | VALIDATED as records | They match the draft; the index row carries the Q2 "derived" claim, to be updated at settlement |

## Checks done

- **Quotes, by hand, all match:** §9.1 L93; §9.19 L161, L162, L164; §9.10 L45, L50, L46, L60, L32; §9.16 L93; U6's addendum text (the "(a)" is dropped).
- **Labels:** "Src" is used for readings of book lines; Int or Ded would fit better (normal, not tabled).
- **R2 (`undefined_terms.py`) not run.** It reads the whole `06-theory` tree, and a scratch copy needs every file copied by name. Not done; recorded.
- **R3:** the tool was not run, for the same reason. The citing lines above were found by name-restricted grep, and are complete only for the files searched.
- **`ce_model.py` FIELDS (scratch copy):** confirms that only `adopt` and `revoke` carry `item`; grant carries `owner`; consume and transfer carry `app`.

## Limits

- **Not opened:** the commission file `COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`; BOOK-OUTLINE; RESEARCH-PURPOSE.
- **Read in part only:** §9.13, §9.16, §9.18, §1.16 (as at step 1); §9.08 (grep only); §9.16 L310–325.
- No model was run, and no corpus source was used.

**Files opened (step 2):**
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/T9-22-TURN/BRIEF-STEP2.md`
- `…/review/T9-22-TURN/RESEARCH-QUESTION.md`
- `…/chapter_09/09.21-the-interface-granularity-batching-and-output-forms.md`
- through grep and sed: `…/chapter_09/09.00-index.md`, `09.99-change-log.md`, `TODO.md`, `09.16-reassessment.md` (L310–325), `09.08-is-history-a-primitive.md`, `09.19-…`, `09.20-…`
- the scratch copies of the tools

Step-1 files are as listed in my step-1 answer.

**Files copied** to `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t922-a/tools/`: `undefined_terms.py`, `inbound_citations.py`, `ce_model.py` (each by its own path).

**Commands and searches run (all read-only):**
1. `mkdir` of the scratch tools folder; `cp` of the three tools.
2. `grep` on the scratch `undefined_terms.py` (argument parsing, root) and on the scratch `ce_model.py` (FIELDS).
3. `sed -n 120,160p` of the scratch `ce_model.py`; `sed -n 1,40p` of the scratch `undefined_terms.py` piped to grep.
4. `grep -n '9\.21'` in 09.00-index.md; `tail -n 3` of 09.99-change-log.md; `grep` for the T9-22, T9-03 and T9-04 rows in TODO.md; `sed -n 310,325p` of 09.16.
5. `grep` for "Lemma D" and "Hist" in 09.08; `grep` for L164, 9.20.5, L104 and L48 in 09.19, 09.20, TODO.md and 09.00-index.md.

No globs, no recursive search and no git command were used in step 2.
