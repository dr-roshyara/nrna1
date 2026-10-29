C43 STEP 2-3: REVIEW REPORT

This is window reviewer W's transition review of §8.36 (C1–C6), the step-8 file, the corrections made before review (`8864c11e7`) and the C42 settlement (`fcc602a18`). I re-read my saved inventory first; its sha256 `2ed72590…c54ee1` matches SHA256-STEP1.txt.

## Summary (10 lines)
1. **No critical findings. Five are high; four of them correct C1, C3, C4 and C2.**
2. **C1 rests on the wrong homes, and the book contradicts itself.** §8.34 C3 says the acceptor may be a "person or system". C1 says a person accepts. §8.37 L58 and §8.38 L16 and L43 already build on C1's "person".
3. **C3 is wrong as stated.** Circular dependence does arise in the record, through annulment targets. The book *detects* it and reports U with a reason, which is what the sources ask for.
4. **C4 (iii) answers only on the authority axis.** On evidence, the book's empty value is "not attested", and §8.33 C1 says that meets the open-world reading.
5. **C2 states proposed things as the book's.** The evidence kinds are *proposed*, and (c2) is proposed. It also misses that authority already decides *whose* refutation counts (T9-12 (c)).
6. **The C42 settlement matches the §8.35 diff row for row.** My own C42 W-03 completion condition ("G7 row extended") was not met. C4 (ii) carries my C42 W-02 wording "reported (Q5)", which goes beyond Q5's scope.
7. **Process independence only.** I did not write §8.36. But C4 (ii) and C2 carry corrections I proposed in C42, so on those two my check is not independent.

## Findings (ranked)

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C3: "circular reasoning in the record cannot arise at all" is false.** Only *citations* must name earlier entries. Targets of annulments, endings and revocations are "not restricted" and may be later entries. Negative cycles therefore occur in D: self-annulling acts, self-cancelling acts (S0219's ātmāśraya), mutual annulment and rings of three or more. Each gives **U with a reason naming the acts**. So the book *prevents* positive cycles and *detects and preserves* negative ones, which is S0220's H-KOS-Fallacy-001. Two smaller errors: (a) "every chain ends at a root" is wrong, because a citation chain ends at an entry that cites nothing (F unless it is a root), and only T answers rest on roots (R4); (b) R3a classes violations by whether a later act can undo them, not by "strength", and the citation rule is the Principal's D decision (R3), not an application of R3a. No challenge was recorded; my counterexample is mutual annulment via a forward target (check 11/15). | high | §9.13 L54, L64, L153–156, L199, L227, L252, L267; §9.16 L179 | "Positive (citation) cycles are prevented by D. Negative cycles through targets occur in D and are detected: U with a refusal reason naming the acts. Circularity in content is outside the record." Fix plain-language item 3 and the title. | C3 restated; a challenge recorded |
| W-02 | **C1's "a person accepts" uses the wrong homes and conflicts with §8.34 C3.** §10.4 R1 says the act belongs to the authoriser (AU) and does not say "person". §8.34 C3 reads R1 as "an authorised actor, person or system". The real support is A1 (§1.6), ARCH-INV-1 ("the mechanism records authority, never grants it"; §1.14.2-1, a relayed ruling, one line of support), §1.13.1 ("a human act") and §8.06 ("an automated check is not an act"). Also, D does not check that an actor is a person: the actor is an identity, and the actor–holder link is an input assumed correct (K3). So "no engine does it" is a norm, not something the kernel enforces. Counterexample within scope: an engine identity holding a covering grant records an adoption; D accepts it and CE* gives T. | high | §10.4 L54; §8.34 L67; §1.14 L278; §1.17 L30; §8.06 L82; §9.15 L76 | Recite C1 on A1 and ARCH-INV-1. State "person" as the book's norm and premise (residual trust, §1.12), not as a property D checks. Reconcile §8.34 C3's "person or system". Check §8.37 L58 and §8.38 L16, L43 (R3). | C1 and §8.34 C3 agree; dependents checked |
| W-03 | **C1's "inconsistent with themselves" is too strong; its rival answer is inadequate.** A stronger rival exists: S0218's "validation boundary" can be a guard that refuses unless an accepting act is recorded. That guard is "within ARCH-INV-1", because it reports absence and does not judge merit (§1.14.2-4). The rival is compatible with "does not decide". What the sources lack is an *owner* of acceptance (my S0218 item 2), not consistency. **"Validation" is also a homonym** that C1 merges: in S0218 it is an acceptance gate (L811); in S0220 it is critique by a "critical reasoning engine", i.e. tarka (L169–175, L419–425). | high | S0218 L779–815; S0220 L169–175, L419–431; §1.14 L293 | "Underdetermined: no actor named; consistent if read as a guard on a recorded act." Qualify "validation (acceptance)" and "validation (critique)". | C1 restated; challenge redone |
| W-04 | **C1's agreement "Why was this action justified at that time? = Q1 at the cut" conflicts with settled §8.35 C2.** That claim says knowledge → decision dependency "is not modelled" and that acting agents are outside KnowledgeOS. Q1 gives the item's current authority at a cut. It gives neither the justification of an action nor the link from the action to the item. | high | §8.35 C2 (settled); §9.10 L31–35 | "Q1 at the action's cut answers whether the item had authority then. The action–knowledge link is not modelled (§8.35 C2)." | C1 agreement narrowed |
| W-05 | **C4 (iii) and plain-language item 4 merge axes again.** §8.33 C1's own Int reads: for authority, F (a closed world); **for evidence, "not attested" meets H-ZERO-001**; for content, the kernel has no value. C4 keeps only F and concludes that "missing recognition is F" against S0220's "not missing data". Its recorded rival answer has the same defect. *Four-valued lens: yes, C4 needs it.* The sources' Unknown + CONFLICTED is a Belnap-type status of *content* (neither/both). The book's U is a three-valued *authority* value with a cause. It is not "neither". The book's content-side counterparts are "not attested" (neither) and T9-17's rival "contested" (both). g0 is refutation-first and collapses "both" into "refuted". | high | §8.33 L37–52; §9.18 L66–67, L99, L145, L202 | "For authority, a new item is F (decision C1). For evidence, 'not attested', which agrees with the sources. For content, no value. CONFLICTED corresponds to 'contested', a rival not adopted." | C4 (iii) and rival restated |
| W-06 | **C2 states proposed things as the book's, and omits the producer gate.** (a) §9.18 says "Nothing here is adopted … the evidence kinds are **proposed kinds**", and today the record holds only acts of authority (G1). So "a refutation is recordable" holds only under the proposal. (b) "Changes no value or mark" needs more care: values rest on premise (c1) and on (c2), which is proposed; marks are equal only on records in D_W and under (c3), which is Int; this is shown on a sample. (c) T9-12 (c), decided: every declared producer's entry is shown, but **only authorised producers' entries count towards "refuted"**. So authority does constrain whose refutation counts. That is a second narrow reading of TARKA-005 that the book holds, and the rival answer misses it. The non sequitur itself stands: "Evidence ≠ Authority" does not imply the rule as worded, and the same justification is reused for TARKA-001 and TARKA-003 (L207, L231, L255). | high | §9.18 L1–3, L24, L154–157, L193, L215–225 | Make C2 conditional on §9.18's proposed kinds. Add the producer gate. Narrow "no mark" to D_W. | C2 restated |
| W-07 | **C4 (ii) extends Q5 beyond its scope.** Q5 reports conflicts among **rules written in L**. S0218's CONFLICTED is between factual claims A and B, each with its evidence, and no relation between items is admitted (CON-0188). So the coexistence of the two claims agrees with the book, but *recording* their conflict has no home. This inherits my C42 W-02 wording, and §8.35 C4 (i-a) needs the same narrowing. | normal | §9.10 L80–85; §9.13 L96 | "Coexistence agrees. A conflict between non-rule items is neither recorded nor reported (no links)." | C4 (ii) and §8.35 C4 (i-a) narrowed |
| W-08 | **C5: K3 is misdescribed, and "weight by role" is answered on the wrong axis.** K3 is the *assumption* that the identity-link input is correct; it does not link anything. The link is an unrecorded input (I11). (B)'s weight is epistemic. On evidence, the book weighs by authorisation (counts or does not; T9-12 (c)); graded weight is open (g0 has no strength). The book records "who validated" (adoptions) and "who produced" (proposed evidence). "Who interpreted" and "who reasoned" have no kind of act. So (C)'s lineage agrees only in part. **G7** is the mapping of class vocabulary, not source kinds; source distinct from recording actor is "not carried" (§9.18 L90). This is a stale pointer, also used in §8.38 L16. | normal | §9.15 L64, L76; §9.13 L98; §9.18 L90, L154; §10.2 L218 | Restate as above. Give source kind its own open row, or extend G7's text. | C5 restated; a home for source kind |
| W-09 | **C6: the CI is attributed to the wrong line and the arithmetic is not checked.** "Research consensus" (S0219 L11) introduces the four SEP-based findings; the preprint result is at L13–14 ("demonstrates") and L114. The stated bound [0.510, 1.0] does not match 10/10: Clopper–Pearson gives a lower bound of 0.692 and Wilson 0.722 (local computation). The preprint's "teaches genuine reasoning" is an interpretation stated as a finding. Also missing: TARKA-001 (pramāṇa grounding) leaves S0220's list too, renamed "source separation". | normal | S0219 L11–14, L114–116; S0220 L483–490 | Fix the line. Add the bound as a source defect. Add the rename. | C6 extended |

**Source defects (listed separately; the theory does not rely on them):** S0219 gives three or four pramāṇas (L38 vs L49). Its "five types of tarka" are flaw-types, pramāṇabādhita is not a circularity, and the list's relation to hetvābhāsa is not stated (L88–93, L111). The CI is as in W-09.

## Claim verdicts

| Claim | Verdict | Reason (scope) |
|---|---|---|
| C1 | core **VALIDATED**; "person" **PROVISIONAL**; "self-inconsistent" **INVALID** as stated; Q1 agreement **INVALID** as stated | A passing check is not an act (A1, §8.06). W-02, W-03, W-04. |
| C2 | non sequitur **VALIDATED** (for the rule as worded); "recordable, no standing change" **PROVISIONAL** | Proposed kinds; (c2) proposed; producer gate (W-06). |
| C3 | prevention of positive cycles **VALIDATED**; "cannot arise", "every chain ends at a root" **INVALID** | Negative cycles are detected (W-01). |
| C4 | (i) **VALIDATED**; (ii) **PROVISIONAL**; (iii) **VALIDATED** for authority, **INVALID** as a whole | W-05, W-07. |
| C5 | actor recorded **VALIDATED** (K3 corrected); "weight by role" **PROVISIONAL** | W-08. |
| C6 | **VALIDATED** (Int) | Additions per W-09. |

**Recorded challenges.**
- C1: **inadequate** (W-03; the stronger rival is the guard reading).
- C2: **adequate for the wording**; it misses the producer-gate rival (W-06).
- C4: **inadequate** (W-05).
- C3, C5 and C6 have none. The strongest counterexample to C3 is mutual annulment.

**R2 (scope 08.36; tier A 0; each candidate judged).**
- Need qualifying: **"person"** (W-02); **"contradict"** (content/authority/refute; W-05, W-07); **"circularity"** (positive/negative; W-01); **"reasoning"** (produce/test; source term); **"validation"** (not listed by the tool; a homonym, W-03).
- Acceptable: "structured" and "classified" (U by cause); "action" (outside KnowledgeOS); and as ordinary words: outside-research, warn, dropped, data, together, next, again, rival, start.

**R3:** §8.36 C1 has 4 claim-level citations (§8.37 L20, L58; §8.38 L16, L43), and C5 and C6 have more. W-02 and W-08 reach §8.37 and §8.38.

## C42 settlement (`fcc602a18`)
Rows W-01 to W-09 and the coverage rows each match the §8.35 diff, the index row and the change-log row. Two gaps:
- W-03's "resolved when" asked for the G7 row to be extended, and §10.2 L218 is unchanged (see W-08).
- §8.35 C4 (i-a) "reported (Q5)" needs W-07's narrowing.

## Coverage (my inventory against §8.36)

| Status | Items |
|---|---|
| COVERED | S0218 2, 3, 6, 10, 11, 13, 15; S0219 6 and the lineage; S0220 6, 8, 13, 14 |
| DISTORTED | S0218 7 (W-07); S0218 9 (W-04); S0220 9, read as "cannot arise" (W-01); S0220 8 against "not attested" (W-05); S0219 10 (W-09) |
| MISSING | **S0219 2 / S0220 5: produce vs test (TARKA-003 → Separation-001, "strongest candidate")**, whose nearest counterpart is support vs refute polarity and adopt vs evidence; S0220 7, vyāpti weakened from *establish* to *record*; S0220 12, "preserves the validity of reasoning" (incompatible with a non-reasoning kernel); S0220 9, "SHALL detect" vs "kernel does not reason"; S0220 10, the Knower placed at the pipeline head, against (C)'s distributed agency; four lifecycle vocabularies (S0218 L192–210, S0219 L158–176, S0220 L305–321), of which only L176 is recorded; S0218 4, "relationships are the knowledge" against links not admitted (CON-0188); S0218 5, "a conclusion must never become an observation" (home §8.35 C3, not cited); S0218 12, "reasoning" in two places; S0218 14, four of five candidates named but not formulated; S0219 12, "tarka as kernel primitive: Rejected" (supports §10.2's placement) |
| JUSTIFIABLY EXCLUDED | pramāṇas, syllogism, the six-phase method and the Western comparison as reasons (benchmark); the source defects |

**New topic or fork:** the counterpart of "produce vs test reasoning" (whether it is polarity or a kind of act), and a home for recording conflicts between non-rule items.

## Next steps (dependency order)
1. Restate C3 per W-01. *Done when* the plain language, the title and the claim distinguish prevented from detected.
2. Restate C1 per W-02 to W-04, and reconcile §8.34 C3. *Done when* §8.37 L58 and §8.38 L16, L43 are checked.
3. Restate C4 per W-05 and W-07, and narrow §8.35 C4 (i-a).
4. Restate C2 per W-06, then C5 per W-08 with a home for source kind, then C6 per W-09.
5. Add the coverage rows from the MISSING row above.

## Limits
- I read §9.13, §9.18, §10.4, §1.14 and §8.33 by grep and sed, not whole. I did not run ce_model; checks 11, 15 and 33 are cited as the book reports them. I did not run the quote tool; I spot-checked the quotes in §8.36 against my reading and S0217 L668, and they match.
- I did not open §8.37 or §8.38; I know their citing lines only from inbound_citations.py output.
- A search miss is not absence.

## Files opened
Under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/`:
- `review/C43-S0218-S0220/`: BRIEF-W-STEP2.md, SHA256-STEP1.txt, STEP1-INVENTORY.md (L1–20, plus sha256), STEP8-AND-CHECKS.md
- `review/C42-S0215-S0217/`: SETTLEMENT.md, REVIEW-REPORT.md
- `chapter_08/08.36-…md` (whole); `08.34` (L61–75; grep); `08.33` (L37–52)
- `chapter_09/`: 09.13 (grep); 09.10 (grep; L80–86); 09.15 (grep); 09.16 (grep); 09.18 (grep; L1–12, L209–232)
- `chapter_10/`: 10.04 (grep); 10.02 (grep)
- `chapter_01/` (grep over all files)
- Other chapters only through grep output lines.

Corpus: S0217 (`docs/knowledgeos/brainstorming/20260822-0203-truth-seeking-machine-knowledgeos-core-question-refinement.md`, L660–672), after checking it on CORPUS-ALLOWED.tsv; and S0220 L235–238. I opened no other corpus file.

## Commands run
- `git log --oneline -1 -- BRIEF-W-STEP2.md`, `cat` of the brief; `ls`, `cat SHA256-STEP1.txt`, `sha256sum` and `wc -l` in the C43 folder
- `ls` of chapter_08 (tail), chapter_09, chapter_10, the C42 folder and tmp/tools; `git log --oneline -6`
- `git show fcc602a18 --stat`; `git show fcc602a18 -- 06-theory/manuscript/chapter_08/` to scratchpad `c42diff.txt`, then `grep '^[-+]'`; `git show 8864c11e7 --stat` and its §8.36 diff
- `grep` / `sed -n` on: 10.04 (R1, accept); 08.34 (C3; L61–75); 09.13 (actor, 9.13.2, targets, roots, sections); chapters 00–10 (person/system/automated); chapter_01 (1.13.1, person, ARCH-INV-1); 09.10 (Q1, Q5); 09.18 ((c1), (c2), refuted, g0, producer; L1–12, L209–232); 09.16 (R3a); 09.15 (K3, G6); 10.02 (G7); 08.33 (L37–52, plus Q5 lines)
- `awk` on CORPUS-ALLOWED.tsv for S0217; `sed -n` on S0217 L660–672 and S0220 L235–238
- `python3 -I tmp/tools/inbound_citations.py "§8.36 C1"` … `"§8.36 C6"`
- `python3 -I tmp/tools/undefined_terms.py --scope 08.36`
- Step 1 (earlier in this run): `python3 -I` local Clopper–Pearson and Wilson bounds for 10/10

Nothing was edited, and nothing was sent to an outside service.
