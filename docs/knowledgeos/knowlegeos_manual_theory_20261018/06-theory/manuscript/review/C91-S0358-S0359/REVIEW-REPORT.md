C91 REVIEW REPORT

Window C91 (S0358, S0359), reviewer W (Opus), transition review, step 2. This is process independence, not personal independence. Before starting I re-checked my saved inventory: its sha256 (657e4a56…cf4) equals the value in SHA256-STEP1.txt.

## Summary (at most 10 lines)
1. All 44 quoted spans in §8.84 are verbatim at the lines cited (checked by hand, commands below). The source line references hold.
2. C1 and C2 stand. C2's derivation is sound once two hidden premises are stated (W-08).
3. C3 places the conflict with A1 in the wrong spot. The breach of A1 is supersession decided by weight (§8.38 C1 reading (i)). The authority-register multiplier is a different "authority" (§8.38 C5), and its real rival is the decided T9-12 (c): authorisation gates whether evidence counts, it does not multiply it (W-01). C3 also leaves out the open fork on what Q4's grading rule reads (W-06).
4. C4 merges two senses of "execute" (W-03) and two senses of "identity" (W-04).
5. §8.84.3 calls the in-kernel intent parser an "agreement" and points to a §8.83 C1 claim that does not exist. S0357 explicitly excludes a natural-language parser from the kernel (W-02).
6. The largest omission is S0357's requirement that a claim A and a claim not-A coexist (K5). S0358 and S0359 contradict it, and the book's "contested" agrees with it (W-05).
7. Nothing in §8.84 presents as decided anything that is the Principal's to decide.

## Findings
R3 below means the lines in the book that cite the claim and would need updating if it is corrected.

| W-nn | Sev. | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high | C3 "authority folded into evidence … what A1 separates" | **Weight-ratio supersession.** S0359 L178 replaces a claim when one weight exceeds the other by 1.5×. This is reading (i) of §8.38 C1, standing computed from a value, and it is what breaks A1 (§1.6 L15). **The register multiplier.** S0359 L393–394 multiplies weight by 1.2 if the author is in an "authority_register". That is "authority" as trustworthiness, or as producer authorisation (§8.38 C5). The book decided T9-12 (c): only authorised producers' evidence counts (§9.18 L79). That is a gate; S0359 makes it a 1.2× multiplier, under which unauthorised evidence still counts. S0359 also has a separate gate: an admissibility rule requiring a recognised author (L350–352). **A1's wording.** A1 does not say "a recorded act of a holder". | Restate C3 as: (a) supersession by weight is a standing change made without an act, which breaks A1-recognized; (b) the register multiplier is a rival to T9-12 (c), gate against multiplier; (c) "authority" has the §8.38 C5 meaning. Cite §8.38 C5 and T9-12 (c). | none (tool); index row 08.00 L100; title L1 and plain-language item 3 (L21) |
| W-02 | high | §8.84.3: closed vocabulary is "agreement with S0356's 'not general NLP' (§8.83 C1)" | §8.83 C1 (L27–37) contains no such phrase; the pointer is stale or the content missing. S0356 is not allowed, so I could not check it. S0357 excludes a natural-language parser from the kernel: "The Kernel should not parse English…" (L294–308; also L7). S0358 makes parsing an English proposal stage 1 of the kernel (L60–63) and puts IntentParser among the domain services (L305). S0359 calls the parser "step 1 of the kernel" (L222). | Change "agreement" to "disagreement with S0357 L294–308". Drop the §8.83 C1 pointer, or source it. | none |
| W-03 | high | C4 "execution inside the kernel" | S0357 K8 itself puts production of the resulting state inside the kernel (L236–240). What S0357 excludes is "arbitrary business workflows" (L316–326). S0358's executor creates the new state, appends it and emits an event (L100–104), which matches K8. The real boundary crossing is that the kernel's state is a work item's workflow state (status, assigned_to, review_deadline, S0358 L141–143), and the kernel is named as the act's actor (L176) while the proposer is human (L134). | Separate "execute (apply a knowledge-state transition)" from "execute (run a workflow)". The claim holds only for the second sense, and for the kernel being named as the actor. | index L100 "a kernel that executes" |
| W-04 | high | C4 "S0358 adds a third position on identity: never merged" | S0358 L191 is about entity identity. §9.16 R2's union by content identity is about acts and records. §8.83 C3 already left these as a homonym to qualify. The like-for-like comparison is S0357 K1, which allows "candidate identity" and "identity conflict" (L56–64); S0358 forbids merging and splitting outright. | Qualify "identity (entity)". Compare with S0357 K1, not with R2. | index L100 |
| W-05 | high | omission: contradiction | S0357 K5 requires A and not-A, each with its evidence, to "coexist without silently destroying one another" (L139–155). S0358 L209 says no transition may introduce a contradiction. S0359 fails such transitions (L168–169) or supersedes by weight (L178). The book's "contested" (§9.18 L67; I-4) sides with S0357. | Add to C3 as a contradiction between the advisers, with the book agreeing with S0357. | — |
| W-06 | high | omission: open Q4 fork | S0359's ten evidence types (L315–325), weighted by type (L381–390), are a source-kind vocabulary and an input to grading. That is evidence on the open fork "source kind or reliability as Q4's input" (§8.38 C1; D-07; G7, §9.18 L82). The recency multiplier (L399–400) is the "latest wins" rival that §9.18 L152 names as what would disprove the rule. As an adviser's design it is not a display need, so it disproves nothing. | Record S0359 as an adviser's argument bearing on D-07 and G7, with the scope stated. | — |
| W-07 | normal | C1 "Six Pillars are S0357's capacities less admissibility and determinism" | K1–K8 minus K7 and K8 leaves Identity, Evidence, Provenance/History, Context, Contradiction, Temporal Evolution. The pillars instead keep Provenance and History apart and drop Temporal Evolution. The pillars are exactly the six nouns of S0357 L473, in the same order, under the same verb ("preserve", S0358 L16). | Restate. This makes the evidence for relay stronger. | none |
| W-08 | normal | C2 derivation | See the proof-step table below. Two premises are hidden: that S0358's L204 rule governs its own example, and that "requires" (S0359 L62) means every listed rule must pass. S0358 L141 shows the state as IN_REVIEW, not STOPPED; L205 does not allow the action from IN_REVIEW either, so the conclusion does not depend on which reading is right. A stronger rival than "illustrative": the adviser treats the order as a continuation ("continuation required", L170). It fails because the parsed action is START_ADOPTION_REVIEW (L161) and the parser keeps it distinct (S0359 L288). | State both premises. Add the IN_REVIEW case and the continuation rival. | none |
| W-09 | normal | omission in C2 | S0358's STOPPED list includes CONTINUATION (L204), which agrees with the estate's rule that a STOPPED item exits only by a continuation (§8.82 L59). S0359 changes the list so that SUPERSEDE also leaves STOPPED (L151), with no continuation: a change between the two replies. A transition rule that can never be satisfied is a concrete case of F-min's "a non-default answer must stay reachable" (§9.19.8). | Record the agreement, the change and the F-min link. | — |
| W-10 | normal | C3 "a rival to g1" | g1 combines support and refutation of one claim. S0359 compares two claims, A and B (L175–181), so "rival" assumes a mapping between the two that is not stated. S0359's rule is also not total. Taking the "within 20%" bands to mean a ratio up to about 1.2, ratios between about 1.2 and 1.5 fall in no branch. The case where B outweighs A is unhandled. The multipliers can take a weight to 1.2·1.1³ ≈ 1.597, outside the declared [0, 1] (L311). g1 is total (§9.18 L190, L200). | State the mapping premise. Add the totality contrast. | none |
| W-11 | normal | C5 | Nothing enforces no_self_review against the actor. adopt_workitem checks only role == governance (L206), not that the adopter is outside the owner and reviewer fields. The check "evidence.author != evidence.subject" (L372) uses a field the Evidence schema lacks (L300–313). A role-level bar also blocks two independent processes that share a role. UNAUTHORIZED (S0358 L270) has no grant model behind it, which links to §8.83 C4. | Add the gap in who enforces the invariant, and the undefined field. | none |
| W-12 | normal | §8.84.3 "RESOLVED / AMBIGUOUS / MISMATCH / UNRESOLVED again (§8.83 C1)" | S0356's set has MATCH, not RESOLVED (§8.83 L31). RESOLVED is also a contradiction status in S0358 (L145) and a bootstrap value in the estate (§8.82 L64): a homonym. S0358's six fail codes (L265–272) are a further outcome vocabulary, not mapped to the others. | Restate as "MATCH replaced by RESOLVED", flag the homonym, and note the fail codes. | none |
| W-13 | normal | omission: determinism | Several rules depend on the current time: S0359's 30-day observation limit (L115–116), validity windows (L105–106, L356) and the recency multiplier (L399). So S0358's "same input → same decision" (L38) holds only if the clock is part of the input. The book answers for a given cut (§9.16, row for law 21). | Add it as an agreement on condition. | — |
| W-14 | normal | R2 | Not ordinary English here: "admissible" has a home in the book (the guard; §8.83 L96); "merged" (R2's merge rule), "entity" (W-04) and "weight" (used in two senses, S0359 L311 against L178) need qualifying. "adviser" is defined (THEORY-MAP L7), so the tool's hit is a false positive. "entitie", "identitie" and "dutie" are artefacts of the tool's plural folding. | Qualify the four terms. | — |
| W-15 | normal | lineage (C1) | The filename times are 10:36:06, 10:41:48 and 10:42:51; S0359 (481 lines) came 63 s after S0358. S0358's "Revisited" (L348) and "we've been discussing" (L8) show that its adviser had an earlier discussion not in the window. "Second adviser" rests on voice alone. Relay, that is non-independence, holds either way. | Mark "second" as Int and add the times. | index L100 |

**Proof-step table for C2**

| Step | Justification | Status |
|---|---|---|
| 1. action = START_ADOPTION_REVIEW | S0358 L161 | Src |
| 2. state STOPPED (L170); or IN_REVIEW (L141) | Src, the two lines conflict | source defect, not relied on |
| 3. STOPPED allows {CONTINUATION, HANDOFF, ESCALATE}; IN_REVIEW allows {SUPERSEDE, COMPLETE, ABANDON} | L204–205 | Src |
| 4. the L188–220 constitution governs the example | — | **hidden premise** |
| 5. context should be FAIL; it is PASS (L167) and RESOLVED (L172) | 1–4 | Ded, valid |
| 6. start_adoption_review: from INITIATED or STOPPED; action START_ADOPTION_REVIEW; requires context_lifecycle_transition | S0359 L185, L188, L194 | Src |
| 7. INITIATED allows {START_REVIEW, ABANDON}; STOPPED allows {CONTINUE, ESCALATE, SUPERSEDE} | L148, L151 | Src |
| 8. "requires" means every listed rule must pass | grammar L62 leaves this undefined | **hidden premise** |
| 9. no alias from START_ADOPTION_REVIEW to START_REVIEW | L288 | Src |
| 10. the rule can never be satisfied | 6–9 | Ded, valid |

## Verdicts
- **C1:** VALIDATED as relay, that is, not independent support. The pillar mapping is corrected (W-07), and "second adviser" stays Int (W-15).
- **C2:** VALIDATED, once the premises in steps 4 and 8 are stated. My judgement of the Professor's recorded rival ("illustrative"): adequate but weak; the stronger rival and its answer are in W-08.
- **C3:** PROVISIONAL. The conflict with A1 is real but placed wrongly; the T9-12 (c) rival and the D-07 link are missing (W-01, W-06, W-10). My judgement of the Professor's recorded rival ("weights are a tunable default"): adequate for the values, inadequate for totality (W-10).
- **C4:** PROVISIONAL. Two homonyms need splitting before either half holds (W-03, W-04).
- **C5:** VALIDATED within role-level scope. W-11 is an addition, not a defeat.

## Coverage against my inventory
- **COVERED:** the admissibility-versus-truth distinction (C1/C4); the defective worked example and the rule that can never be satisfied; the weights; the constitutional rule counted as evidence; no self-review; the kernel executing; no merge; the contradiction vocabulary; the state machine; the analogy layer; the next-steps options.
- **DISTORTED:** the in-kernel parser called an agreement (W-02); "execute" (W-03); the identity pairing (W-04); the pillar mapping (W-07); the outcome words (W-12).
- **MISSING, and a loss of theory:** contradictions coexisting (W-05); the Q4 fork and source kinds (W-06); determinism depending on the clock (W-13); the rule's lack of totality (W-10); authority with no pillar and no grant model (W-11).
- **MISSING, minor:** the regress in provenance admissibility (S0359 L358–363); transitions that produce their own evidence (L195–198); the history and version rule dropped in S0359; the single KnowledgeGraph aggregate (S0358 L299). I judge the last JUSTIFIABLY EXCLUDED, as part of a design sketch.
- **Source defects, not relied on:**
  - S0358 L149–151: state abc123 lists transition abc124, later than itself;
  - L141 against L170: IN_REVIEW against STOPPED;
  - S0359 L82 against L88: REVIEW against IN_REVIEW;
  - ADOPTION_RECORD and Observation(type=…) are not in the schemas;
  - REJECTED is unreachable and EXECUTED unused (L413–448).

## Limits
- S0356 was not opened (not allowed), so W-02's pointer can be judged only against §8.83.
- R2 was run with `--no-corpus`, because corpus mode reads every source on the allowed list, beyond what this brief permits. Its tiers A–C therefore do not separate corpus hits.
- Not read: the C90 settlement file (in a review folder); §8.33, §8.34 and §8.06 beyond their titles and the lines grepped.

## Files opened
- `.../06-theory/manuscript/review/C91-S0358-S0359/BRIEF-W-STEP2.md`, `STEP8-AND-CHECKS.md`; `SHA256-STEP1.txt` (cat); `STEP1-INVENTORY.md` (sha256 and wc only)
- `.../06-theory/manuscript/chapter_08/08.84-…md`, `08.83-…md` (whole); `08.00-index.md` (grep L99–100)
- grep or excerpts only:
  - Ch. 8: `08.38-…md`, `08.81-…md`, `08.82-…md`, `08.79-…md`, `08.06-…md`;
  - Ch. 9: `09.18-evidence-in-the-record.md`, `09.16-reassessment.md`, `09.15-the-trust-boundary.md`, `09.19-…md`;
  - Ch. 1: `01.06-…md` (L10–22);
  - `THEORY-MAP.md` (L7).
- Sources: S0358 and S0359 (the quote checks); S0357 `docs/knowledgeos/brainstorming/20260823-103606-kernel-eight-capacities-k1-k8-and-minimum-kernel.md` (excerpts: L1–12, 50–72, 85–115, 137–200, 236–330, 465–495; sha256 matches its allowed-list row); the allowed list (awk for S0357).
- Tools: `tmp/tools/undefined_terms.py` (lines 1–45, 125–140, 170–190) and `inbound_citations.py` (grep), both read in the scratch copy.

## Commands run (all read-only; nothing in the repository was changed)
1. `cat` of SHA256-STEP1.txt; `sha256sum` and `wc -l` on STEP1-INVENTORY.md and STEP8-AND-CHECKS.md.
2. `ls` of the manuscript, chapter_08 and chapter_09; `grep -n "8.84\|8\.83"` on 08.00-index.md.
3. `awk -F'\t' '$2=="S0357"'` on CORPUS-ALLOWED.tsv; `sha256sum`, `wc` and `awk`/`sed` line excerpts on S0357; `grep -n "^#\|K[1-8]…"` on S0357.
4. `sed -n 7p THEORY-MAP.md`.
5. `grep` on 08.81 and 08.82 (CONTINUATION, STOPPED, adoption review); 09.18 (g1, T9-17, contested, weight, F-min, T9-12); 09.16 (R2) and `sed` L88–100; 09.15 (G2, G4); 08.06 (C1, role); 08.79 (8.79.3, label); 08.38 (C1 and C5 blocks, via awk); 09.19 (F-min).
6. `sed -n '10,22p'` on 01.06.
7. Scratch copy at `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c91-wren/c91-w-root`: the tools, the register, the map, the purpose file, the outline and the top-level files of each chapter_*. Review folders were excluded. A first attempt using `rm -rf` was denied, and the copy was redone without it.
8. Tool runs, all with `--root` on the copy:
   - `undefined_terms.py --root … --help`: failed with a corpus path error;
   - `inbound_citations.py --root … --help`;
   - `undefined_terms.py --root … --no-corpus --scope 08.84`, output to `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c91-wren/c91-w-r2.txt`;
   - `inbound_citations.py --root … "§8.84 C1" … "§8.84"`, output to `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c91-wren/c91-w-r3.txt` (0 citations for all six).
9. Quote check by hand: a shell function running `sed -n Lp | grep -F` over 44 quoted spans across S0357–S0359. All OK.

No git commands were run, and nothing was sent to an outside service.
