G1 REVIEW REPORT B

Scope: theory turn on `09.18-evidence-in-the-record.md` (draft) and model `ce_model.py` v21. Reviewer B, Sonnet. Facts are marked [V] verified by me now, [R] reported by the document and not re-shown by me, [I] my interpretation, [Rec] recommendation. Probes are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/g1reviewB/`. That folder holds a scratch copy of `ce_model.py`, `run.txt`, and the probes, which I ran as inline `python3 -c` because heredoc scripts were refused.

## Summary (10 lines)
1. [V] Model checks 1–32 reproduce v20 exactly. My full run is byte-identical to `ce_model-run-20261029-v21.txt`, and its first 94 lines are identical to v20.
2. §9.18 shows that one design can meet premises (i)–(v) and the class rule g0. It does not show that the record must hold exactly these kinds. Most "necessary? yes" answers rest on the Professor's own §10.2.3 definitions, which §10.2.3 itself calls "by definition" and "Int".
3. The largest risk is §9.18.6 and §9.18.8. "Standing does not depend on W's entries" holds for Φ values only.
   - [V] The kernel's single-use guard counts W entries as uses of a single-use grant.
   - [V] A designation must close the maximal evidence entries, and `in_D_S` then refuses standing (probe P2).
   - [V] `append_W` skips the single-use guard.
   - So containment holds only for records with no store move and no single-use grants under W. This is high, not critical, because the §9.18.6 text flags the designation limit (T9-13) and nothing is adopted.
4. [V] `wclass` checks only `in_D_W`. It answers "supported" on a record whose standing part is outside D_S, which contradicts the section's own statement that W's answers need standing's answers.
5. Normative choices presented as results: the g0 precedence (refuted dominates support, which is G2's "adopted by an authorising act"), the default with no purpose named, and who may withdraw or restore.
6. "Ties are never read by the class" is false as worded (probe P4). Check 34's "no tie read" is a text search of the source, not a behavioural test. Three of check 33's four sub-results are true by construction.
7. "NOT ATTESTED" is a homonym. CON-0212 and CON-0221 use it for an unresolved or unbindable present record, not for "no entry in the record". They do not support the reading as cited.
8. "Not attested needs no completeness rule" is correct under the stated reading. Its honesty to promise (b) depends on a display wording rule that is unenforced (PL-14) and on the cut. The document says it is a display rule but does not say it is unenforced.
9. §9.18.8 "not shown necessary" is correctly worded and weakly justified. F1 and F2 add couplings that a separate record would avoid.
10. No critical finding. 8 high, 12 normal. Nothing is VALIDATED. Every part is PROVISIONAL or INVALID as stated, and the verdicts are below.

Process independence: process independence only. I read the same book and corpus as the Professor, and I did not see reviewer A's report.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| F1 | §9.18.6 result 1 and its disproof clause ("a standing value that changes when a W entry is added or removed") test Φ values only. Standing's acceptance depends on W entries in two ways. (a) The single-use guard counts a W use. Probe: with an evidence entry under a single-use grant, a standing adopt and a consume are both refused ("gS is single-use and already used by e1"). Book §9.15 G4: "every recorded use of a single-use grant counts". (b) A designation must close every maximal entry, W entries included. `append` accepted `d1` closing `e1`. Then `in_D_S` returned False ("closes ['e1'], which are not entries of its old store"), because `s_part` drops `e1` and `in_D(s_part)` flags it as alien. | high | [V] probes P2 and the single-use probe; model `in_D_S` L1224–1233; doc L157–160, L172–173 | Restate result 1 as "values". Add (a) and (b) as stated limits. Treat T9-13 as blocking for §9.18.8 and the §10.3.6 restatement. Either fix `in_D_S` to accept closes of W entries (a model choice) or move designation and G4 into the two-level scheme explicitly. | `09.18` L157–173, L188–191; `10.04` R6 rows L58, L107, L140, L164 | The model run shows standing in D_S after a store move that closes W entries, and the single-use coupling is either enforced both ways or documented as a cross-area invariant with an owner |
| F2 | G8 is not complete: `append_W` bypasses `append_guard` (single use, owner, K4). Two evidence entries under one single-use grant were both accepted ((True, None) twice). Standing refuses the same second use. A merged record with W use then consume sits in both D_S and D_W. The cross-kind "at most one use" invariant has no owner. Option (c) also conflicts with G4's "a use is accepted only if T at append". | high | [V] probe; doc L124–133 | Add "single-use and owner guard for W's kinds" to G8, or state that evidence entries under single-use grants are excluded. Name the invariant's owner. | `09.18` L124–133; `09.15` G4 (L66) | G8 lists the guard clause, with a check |
| F3 | `wclass` calls only `in_D_W`, which does not check standing's entries. A record with an unadmitted kind (`bogus`), or a consume of a non-single-use grant, is outside `in_D_S`, yet `wclass` returned "supported". The doc says W's answers need "standing's answers for producer authority" (L168). | high | [V] probes P1, P1b | `wclass` should require `in_D_S`, or the doc should say W answers on a standing-invalid record, and why that is sound. | `09.18` L168; model L1273–1275 | Check 34 has a fixture for this |
| F4 | Normative choices presented as results. (a) The precedence "refuted dominates support" is G2's open rule ("adopted by an authorising act", §10.2.3). The class is "refuted" on one authorised refutation against any number of supports. (b) With no purpose named, no withdrawal applies. Probe P5: a `*` withdrawal gives "refuted" with purpose None and "not attested" for purpose p, so the default display shows "refuted" for evidence withdrawn for every purpose. The doc labels the default "Int" but calls the precedence a result. | high | [V] P5; doc L84, L135–149; `10.02` G2 | Present g0's precedence and the purpose default as choices with the rival "contested" and its consequences. Route them to the Principal or to T10-20, or state why they follow from promise (b). | `09.18` L84, L135–149 | Each is either derived from a stated premise or queued as a question |
| F5 | Who may withdraw or restore is unaddressed. A holder of a grant for scope `s/x` withdrew reliance on an unrelated producer's evidence, scope `s` (probe P6: "not attested"). Any holder of any covering grant can unseat anyone's evidence. T9-12 covers producers only. CON-0214 calls invalidation "an authority act". | high | [V] P6; doc L99–116 | Add a premise or question: withdrawal and restoration authority, relative to the item, the producer or the scope. State that annulment authority is standing's, so a producer cannot retract their own entry. S0887 L1565–1590 asks about retraction. | `09.18` L99–116, L199 | Queued or decided |
| F6 | The O2 grounds line says "An evidence entry is not an act in A1's sense: it changes no authority". The doc quotes this as the premise of containment (L41), yet the model evaluates evidence entries as authorised acts in Φ (grants, windows, endings). Other points about the O2/CON dimensions. (i) "Producer = actor" merges the recording actor with O2's "source" and CON-0150/CON-0217 provenance. (ii) O2's "adjudicated outcomes" are dropped without a note. (iii) O2's idempotency "under a content-derived identity" is not carried. | high | [V] `09.10` L114–119; wfs treats `evid` like any act | Reconcile "not an act" with the model: either the entry is an act that changes no authority, or it is not an act and option (b) needs another basis. Note the other three as not modelled. | `09.18` L41, L60, L124–133 | Reconciliation is stated |
| F7 | "Ties are never read by the class" (L90) is false. The class reads standing's authorisation of the producer, which reads act-time order (grant window, endings). Probe P4: an ending at the same `t` as the evidence entry gives class "U". Ending after gives "refuted". Ending before gives "not attested". Check 34's "no tie read" is `'before(' not in getsource(wclass)`, a text search that says nothing about behaviour. | normal | [V] P4; model check 34 | Reword: "W's own rule reads no order; ties reach the class only through standing's answers, as U". Replace the check with a behavioural one. | `09.18` L87–97 | Wording corrected and test changed |
| F8 | Check 33 mostly confirms its own definitions. "Contained in 600/600", "one D refuses in 600/600" and "generic refuses in 600/600" follow from how `in_D_S` and `in_D_W` are written: `in_D_S` never reads polarity and `in_generic` reads `ok`. Only the value equality is empirical. The generator has no designate, no transfer, no W use of a single-use grant, and no standing entry citing W, so it cannot see F1 or F2. The U share is thin (9 of 1,799 W entries; 137 of 83,000 queries in my probe). | normal | [V] model L2377–2420; my totality probe | Report the check as "values independent by construction plus a sample", and add the F1 and F2 cases to the generator. | `09.18` L160–162 | Generator extended |
| F9 | "Three kinds suffice" and "Necessary? Yes, both" (L81) argue the correction/withdrawal pair, not that restoration (`erst`) is necessary. By code reading, `wclass` gives the same class at every cut after standing annuls the withdrawal (`a[w]` = F) as after a restoration. The difference is only history and reasons ("an error" versus "a change of mind"). Not run. | normal | [I] model L1282–1289 | State that `erst` is necessary only if the display must tell an erroneous withdrawal from a lifted one. | `09.18` L13–18, L81 | Stated |
| F10 | "Not attested" homonym and support. CON-0212 (§8.14 C5) uses NOT ATTESTED for a present record that cannot be bound, and says it "has two senses". CON-0221 uses it as an unresolved status. Both are U-like, not "no entry in the record". CON-0212 also warns that a prefix read gives F silently and needs the cut rule and L4. S0294 states "Unknown≠Absent≠False", a three-way split that §9.18 collapses (T9-15). The wording "in the record at s" is a display rule owned by consumers (PL-14, whose enforcement gap is stated in the book). | normal | [V] register rows; `08.14` C5 | Qualify the term ("not attested (evidence class)"). Re-cite CON-0212 for the cut rule and "absence is not evidence of absence", not for the term. State the dependency on the unenforced display rule. | `09.18` L19, L45, L119–122 | Term qualified |
| F11 | R2 and homonym. "Containment" is already used in the book for scope containment (§2.11, §3.8). §9.18 means fault containment. | normal | [V] grep | Write "fault containment". | `09.18` L151–173 | Qualified |
| F12 | Model robustness. `in_generic` raises `KeyError('auth')` on an entry missing `auth`, where `in_D` returns a refusal. `in_D_W` accepts a withdrawal whose target is a later entry, so `wclass` returns "refused" at an intermediate cut but a class at the full record. In my totality probe (600 records, 83,000 queries, both producer modes, 3 purposes) there were no exceptions and no "refused". The doc says "every record in D_W gets a class" without a check. | normal | [V] probes P3 and totality | Check field presence first in `in_generic`. Require targets to be earlier entries, or state that cuts must be closed under targets. Add the totality check. | model L1201–1214, L1236–1253; `09.18` L133 | Fixed in the model |
| F13 | Option labels collide. §9.18.5 (iv) calls the counting mode "(b) authorised" (model comment: "premise iv, reading (b)"), while Option (b) is "only authorised entries are accepted". Option (b) is not modelled, since `append_W` has no authorisation refusal. The `any` and `authorised` modes model (a) and (c). | normal | [V] doc L100–110; model `wclass` | Relabel. | `09.18` L99–116 | Relabelled |
| F14 | There is no gap-closure table. G7 (three or four classes, F13/§3.1 against §1.3) is not mentioned. G3 and G10 are noted as open. G2 and G4 are closed only by choices (F4). The research question's answer, "each class can be read from the record alone, under these conditions", is never stated as a verdict per class. | normal | [V] `10.02` L224–240 and doc | Add a table G1–G10: closed, conditional, or open. State the conditions per class. | `09.18` L1–25; `10.02` | Table exists |
| F15 | `wclass` returns each entry's Kleene value only, not the cause (unauthorised, annulled, or withdrawn). The Int "with no purpose named, the withdrawals are listed" is neither implemented nor tested. The promise is "with its reasons". | normal | [V] model L1273–1318 | Return causes. Add the listing. | `09.18` L84, L143 | Test added |
| F16 | "Changes no kernel law" (L171) holds only for today's kernel, which has no W kinds. D's domain and "Q refuses outside D" (§9.15 G1) are kernel statements, and the two-level restatement changes the conjunction once W kinds are adopted. The generic level has no owner (the kernel, against (d2)'s "X's rule"), which is T10-16 / R0. No criterion is given for which clauses are generic. Standing's own clauses read other kinds (designation, G4), so the partition is not clean. | normal | [V] `10.03` L256–258; `10.04` R6 | State the criterion, the owner, and the effect on §9.15 G1. Drop "changes no kernel law". | `10.03` L258; `10.04` L58, L107, L140, L164; `10.05` L235 | Criterion stated |
| F17 | Prior-work fairness. CON-0150, 0212, 0214, 0217 and 0221 are all PROVISIONAL in the register. CON-0217 is "one producer, writing the test for its own model". §9.18.3 says "adopted as premise" without the status. The S0237 row is correctly marked a tier-3 candidate. The S0294 "do not invent negative evidence" passage is about inference types, not records. S0887 is correctly "states the problem". | normal | [V] register; sources | Carry the PROVISIONAL status in the table. | `09.18` L39–52 | Status carried |
| F18 | Process note. The research question was not committed before the corpus search. The doc states this itself. The out-of-order consults are logged. No finding. | normal | doc L5, L52 | none | | |

## Verdicts

**Questions 1 and 8: checks and prior work**

| Part | Verdict | Reason |
|---|---|---|
| Quotes and lines | OK | S0237 L192–236, S0294 L1015–1040, S0887 L1565–1590, and the CON quotes were checked by hand; the paths are in `CORPUS-ALLOWED.tsv`. `corpus_quote_check.py` reported "0 found, 0 not found" (nothing parsed). |
| R1 | Pass for this section | One flag, CON-0133 (H4), pre-existing and outside scope. |
| R2 | Judged (below) | |
| R3 | Done by search | `inbound_citations.py` returned zeros for the ids I gave. I used grep. |
| Prior work (§9.10 O2/Q4, CON-0150/0212/0214/0217/0221, S0237, S0294, S0887) | Fair on quotes. The status carried is not fair (F17) and the use of the CON-0212 and CON-0221 term is not fair (F10). | |

R2 in-scope candidates, judged one by one:
- `wclass`: a code identifier. Gloss it as g0.
- containable: operationally defined at L153, but a homonym (F11).
- reversibility, overall, distinguish, refute, exclude, possible, operation, merged, remain, beside, cost, example, reviewer: ordinary words used in their plain sense. "Merged" matches §9.13/§9.17. No finding.

**Question 2: the research question.** Constructive and sufficient. Included but not needed for the class: the containment section (it is routed input, so fine). Missing: F5, F6 (outcomes, idempotency, source), F14, the cause display (F15), and the G4 interaction (F2).

**Questions 3 to 7: verdicts per part**

| Part | Verdict | Reason |
|---|---|---|
| §9.18.4 (three kinds) | PROVISIONAL | The kinds are consistent with CON-0214. `erst` is not shown necessary (F9). F6 is open. |
| (i) polarity and object | PROVISIONAL | Necessary by the class definitions (Int). No standing field is enforced only in the model. G3, G10 and S0294's second axis are stated as open. |
| (ii) correction is standing's annulment | PROVISIONAL | Right under T10-10 (1), since annulling stays with standing for every kind. The as-of check is sound (check 34). The cost is a producer cannot retract their own entry (F5). |
| (iii) ordering and U | PROVISIONAL | W's own rule reads no order. "Ties never read" is false through standing (F7). U is sound under Kleene, per fixtures. |
| (iv) who records | PROVISIONAL | The choice is correctly left to the Principal. The CON-0212 argument for (c) is analogical (it concerns an unread store, not refusal at append). The question omits who withdraws and restores (F5). Labels collide (F13). |
| (v) completeness rule | PROVISIONAL | Right under "not attested in the record at this cut". Honesty to promise (b) depends on an unenforced display rule and a cut mark (F10). |
| G8 | INVALID as complete | Single-use, owner and K4 guards are missing (F2). Totality on generated records holds in my probe (F12). |
| g0 | PROVISIONAL | The rule is well defined and Kleene-monotone. The precedence and default are choices (F4). |
| §9.18.6 containment | INVALID as stated (result 1); PROVISIONAL for W-clause breaches | F1, F2, F8. The argument that W atoms are sinks holds for Φ values only. The generic-clause non-containment argument is sound and agrees with §9.13's fail-closed reason. |
| Two-level restatement of (d3) | PROVISIONAL | Well formed as a sketch. The generic level needs an owner and a criterion (F16). "Changes no kernel law" does not hold once W kinds are adopted. |
| §9.18.7 | PROVISIONAL | The "not placed" cell for g0 is honest. |
| §9.18.8 | PROVISIONAL | "Not shown necessary" is correctly worded. F1 and F2 add couplings that a separate record would avoid. Not routing it to the Principal is defensible only after F1 is resolved. |
| Model v21 | PROVISIONAL | Checks 1–32 reproduce v20 (verified). Checks 33–35 test less than claimed (F7, F8, F3, F12). `append_W` and `wclass` are consistent with each other on the tested cases. |

On T9-12: it is well posed as a choice among (a), (b) and (c). The recommendation (c) is reasonable, but the CON-0212 citation is a stretch. Whether (c) leaves unauthorised entries as maximal entries that every designation must cite (clutter) is not considered. T9-12 omits F5.

## Probes and what they show
- P1, P1b: wclass answers "supported" with standing outside D_S (F3).
- P2: kernel `append` accepts a designation closing an evidence entry, then `in_D_S` returns False (F1).
- Single-use probe: `append_W` accepts two uses; standing append refused on a W use; a merged record is in both D_S and D_W (F1, F2).
- P3: a withdrawal before its target is in D_W for the whole record, but cut 3 is "refused" (F12).
- P4: class U through a tie in standing (F7).
- P5: purpose None against `*` withdrawal (F4).
- P6: an unrelated holder withdraws reliance (F5).
- Totality probe: 600 records, 0 exceptions, 0 "refused". Class counts: not attested 72,651; refuted 5,498; supported 4,514; U 137.
- Missing-`auth` probe: KeyError (F12).
- Unprobed: designations and removals of evidence entries (T9-13), and the transfer / consume interplay beyond the single-use probe.

## Open questions that genuinely remain
- Does a single-use grant "use" include evidence entries? If yes, W needs G4 (F2). If no, G4's wording must change.
- Who may withdraw and restore (F5)?
- The precedence of refutation over support, and the purpose default (F4): the Principal or T10-20.
- Whether the generic level is the kernel's, and what the criterion is (F16, T10-16).
- Designations with W entries (T9-13): blocking.

## Recommended corrections for the main session (a plan, not done by me)
Dependency order:
1. F1 and F2: restate result 1 as values, add both couplings, add the guard to G8.
2. F3.
3. F4 and F5: present as questions.
4. F6 and F14: reconcile with O2, add the gap table.
5. F7, F8, F9, F10, F11, F13, F15, F16, F17: wording and test changes.
6. F12: model fixes.
7. Re-run the model and add probes P1–P6 as checks.

## Files opened
- Brief: `.../06-theory/manuscript/review/G1-EVIDENCE/BRIEF.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` (§3 only, L52–62)
- `06-theory/manuscript/chapter_09/09.18-evidence-in-the-record.md`
- `chapter_09/09.10`, `09.13`, `09.15`, `09.06`, `09.00-index`, `chapter_09/TODO.md` (L64–122, L148–161; grep hits for §9.13 L48, L53, §9.15 R3, G4, K4)
- `chapter_10/10.01`, `10.02`, `10.03`, `10.04`, `10.05`, `10.99-change-log`, `TODO` (grep hits and ranges)
- `chapter_08/08.14`, `chapter_01/*`, `chapter_02/*`, `chapter_03/*` (grep hits only)
- `06-theory/CONCEPT-REGISTER.tsv` (rows CON-0133, 0150, 0212, 0214, 0217, 0221)
- `tmp/tools/ce_model.py` (L115–150, L175–290, L380–520, L893–933, L1042–1060, L1154–1165, L1180–1330, L2186–2475), `ce_model-run-20261028-v20.txt`, `ce_model-run-20261029-v21.txt`
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (grep: S0294 line 57, S0887 line 369, S0237 line 1578)
- Corpus sources, each checked in `CORPUS-ALLOWED.tsv` first:
  - S0237 `docs/knowledgeos/architecture/20260822-0915-KOS-EP01-P4-Constitutional-Invariant-Map-v1.0.md` L192–236
  - S0294 `docs/knowledgeos/KnowledgeOS_Character_Definition.md` L1015–1040
  - S0887 `docs/knowledgeos/brainstorming/phase_measure_theory/20260827-181510_step-025a-2-evidence-to-assessment-to-assertion.md` L1565–1590
- Not opened or refused: none refused. A first attempt to run a compound command was refused by the sandbox, and I re-ran it in plain separate commands.

## Commands and searches
- `cat REVIEWER-INSTRUCTIONS.md`; `grep -n "§3" IMPLEMENTATION-STRATEGY-PROPOSED.md`; `sed -n 52,62p`
- `diff` of the scratch run against `ce_model-run-20261029-v21.txt` (identical). `diff` of the first 94 lines against v20 (identical).
- `python3 .../tmp/tools/ce_model.py` on the scratch copy (exit 0, "all assertions passed").
- `register_home_check.py`; `undefined_terms.py --scope 09.18`; `inbound_citations.py` (ids `--help`, 9.18, 10.3.6, 9.13.2: all zeros); `corpus_quote_check.py` (0 found, 0 not found).
- `grep -n` on the W_FIELDS / in_generic / in_D_S / in_D_W / append_W / wclass / random_evidence definitions, and on `bases_of`, `FIELDS`, `phi`, `wfs`, `random_record`, `desig`, `append`.
- `grep` for "(d3)", "T9-1x", "T10-20", "SD1-01", "containment", "9.18" across `chapter_*`.
- Inline `python3 -c` probes P1–P6, the single-use probes, the totality probe, and the missing-`auth` probe, against the scratch copy of `ce_model.py`.
- `sed -n` on the source lines and chapters listed above.
