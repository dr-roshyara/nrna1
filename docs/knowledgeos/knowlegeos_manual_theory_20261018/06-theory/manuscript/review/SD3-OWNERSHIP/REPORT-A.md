SD3 REVIEW REPORT A

**Object:** §10.3 draft (`06-theory/manuscript/chapter_10/10.03-ownership-of-invariants.md`), read with §10.1 and §10.2. Mode: theory turn review under REVIEWER-INSTRUCTIONS. Nothing in the repository was edited.

## Summary (10 lines)
1. Keeping owner and enforcer apart is sound, and both the mandate and the corpus support it (S0198 L62–64; §8.30 C3). Every other part of §10.3.2 and §10.3.4 needs correction.
2. **Critical (F-01).** The refinement falsifier does not meet the turn's own owner definition. Under §10.3.2 the "designation" part does not define its terms, so it does not own the rule. That makes options (i) and (ii) the same test, so the question for the Principal separates two options the turn has already made identical.
3. The four-relation table merges owner and enforcer back into one row ("owns / enforces"). It gives "preserves" a meaning different from CON-0132, which it cites as its ground. An enforcer that is not the owner then has no relation at all. The table itself needs "shared", "contested", "no owner" and "held by convention".
4. The book already has an operational reading of ownership (§6.5.2: the holder of the amending act), already analyses the two-owner case (§6.5.2 L31), and already separates three senses of "owned" (§9.10 L150). The turn uses none of these, so "owner" is now a homonym with at least five senses.
5. Row C contradicts the settled §10.2 (candidate 3 "preserved, not owned"; L120–123), and contradicts the turn's own owner definition.
6. The inventory is incomplete. It has no must-do law (law 17, O1–O4), so it fails the trivial-solution check. It also omits the roles law, §9.15 G6, the grant-owner rule, CON-0141 and §0.5's second set.
7. Several level ceilings are higher than what they rest on (rows at S1 and S2).
8. Two gaps are stale against F36 as decided (§9.16 R6: law 22 takes option (a), law 20a takes option (b)).
9. "Not sufficient" is plausible, but the turn shows it weakly. Its S0033 evidence is an ad hoc refusal (H-CAT-1), and stronger evidence it omits is available (S0036 L138 with L143; S0001 L29 via §1.11).
10. No line uses DDD literature as a reason. Point 1's premise (a context needs its own language) has no book source in the turn, although §6.5 C6 (S0062 L14–20) would supply one.

**Process independence:** I am an independent reviewer in process only (a separate session that worked only from the brief's file list). This is not personal independence.

## Findings

| ID | Finding | Sev. | Evidence | Correction | R3 lines | Resolved when |
|---|---|---|---|---|---|---|
| F-01 | **The admission test's falsifier and its recommendation use two different owner notions.** Under §10.3.2 (L35) the owner is the candidate whose model *defines the terms*, and exclusivity means no second model defines them (L49). So a part "that shares its whole language" (L160) is not an owner, and the falsifier is not met. Option (ii) under the L35 definition already contains (i) (L167–168). L173 even says (i) "uses only the owner definition". Conversely, a split that carries its own distinguishing terms (designation/carrier on one side, grant/use on the other) passes (i) as well. Under a strict reading of "the terms", which takes in shared terms such as record, entry, item and standing, (i) excludes 2, 3 and 4 (their invariants use 1's and 5's terms). | critical (decides a fork for the Principal; F3) | 10.03 L35, L49, L160, L167–173 | Restate the falsifier against a named, looser owner notion ("whose rules state it"), or drop it. Define which terms count. Re-derive (i) under both readings. Add the rival (iv): an exclusive invariant **and** its own language (§6.5 C6, S0062 L14–20; S0150) | none (draft) | the test and its options are restated, with a proof-step table, and reviewed |
| F-02 | **§2c table.** (a) The "owns / enforces" row merges what L34–36 separate. (b) "Preserves = does not violate" is not CON-0132's meaning ("enforced on behalf of an owner elsewhere", register; §8.30 C3: operating is preserving). It is the mandate's meaning, with no ground in the book. (c) An enforcer that is not the owner (law 13 as written) fits none of the four relations. (d) The table needs shared, contested, none and held (CON-0102). So "yes, supported and useful" is not shown | high | 10.03 L41–47; register CON-0132; 08.30 L53; 06.04 C3 | Split owns from enforces. Qualify the two senses of "preserves" (CON-0132 vs mandate §2c) or pick one. Add "enforces for another" and "held by convention", or say why they are excluded | CON-0132: 06.05:29, :54; 08.28:56; 08.30:19, :53; register:133, :138; map:203, :228; outline:57, :138 | relations restated and reviewed |
| F-03 | **The owner definition is applied inconsistently.** Row C gives 3 ownership while stating that "the values are defined by 1 and 2" (L93). I-6 freshness is 1's (L95). The pin uses 5's claim and version. I-3 "provenance" is in 1's concept list (CON-0016; §10.2 L80), yet row E gives it to 5. Law 27's "current rule" belongs to 1 and 4. Role exclusivity / mutation ownership are 7's words. In §10.3.4 L177, 5 is penalised for "item" but 2, 3 and 4 are not penalised for using 1's and 5's terms | high | 10.03 L93–98, L114, L76, L124, L175–179 | Apply L35 row by row, or mark each row shared or contested | none | table re-derived |
| F-04 | **Prior work in the book is omitted, and "owner" becomes a homonym.** §6.5.2 L30 already makes CON-0132 operational (the owner holds the amending act). §6.5.2 L31 gives two non-refuting readings of S0091's two owners (joint ownership, or two invariants under one label: S0067's I-4 vs S0091's I-4), so L24's "the counterexample" overstates. Also unused: §9.10 L150 (o1)–(o3); §1.11 (who must maintain; L57: "ownership is evidence about boundaries, not proof"); §9.17 "owner application", which appears in the same table (L74) | high | 06.05 L29–31; 09.10 L150; 01.11 L33, L57 | Compare L35 with the amendment-act reading. Qualify "owner (invariant)" | as F-02 | rival weighed; homonym qualified |
| F-05 | **The settled §10.2 is changed without a record.** Candidate 3 was "rules preserved, not owned" with invariants "?" (§10.2 L64, L120–123). Q2, Q5, Q6, O1 and O3 were "unplaced" (§10.2 L248; T10-07), yet here Q2 is placed in 1, visibility in 1 and 3, and Q5's liveness in 4, with no argument | high | 10.02 L64, L120–123, L248 | State each change with its reason, or keep them unplaced | §10.2: 10.01:92; 10.99:9; map:373 | changes justified or withdrawn |
| F-06 | **The inventory is not "every invariant in the book".** §9.11's own selection is limited (L11: "A law outside these sections is not classified here"). Missing: **law 17** (Req inputs O1–O4, the only must-do laws; the trivial-solution check); §9.15 G6 (an integrity failure refused); the §9.16 R4a grant-owner rule (§10.2 L88 lists it as 1's); §1.14.3 roles law; CON-0141 (non-consultation, §8.8 C1); §0.5's engineering-side set (S0014 L35) and the candidate "governance precedes automation"; GKC's derived rules (never-a-copy, …) | high | 09.11 L11, L53; 09.15 L68; 00.05 L40, L51–59 | Add the rows, or exclude each with a reason | none | inventory closed or exclusions stated |
| F-07 | **Ceilings exceed what they rest on.** Rows in block A are set at S1, against candidate 1's "S0 or S1, undetermined" (§10.2 L95). Row D CON-0063 is "S1 at most" while saying "the class is S0", and candidate 4 is at S0. CON-0072 is at S1 while candidate 6 is at S0. The gate is at S2, but S2 also needs a reviewed specification and tests (strategy §3), and the gate's enforcer is an architecture act outside every candidate. Law 13 is at S1, yet §1.7 is "Src as a proposal", read governance "Open". Law 21 rests on CON-0143, a stipulation "for the Principal to confirm" | high | strategy §3; 10.02 L95, L145, L159; 01.07 L18 | Cap each row at the lower of the invariant's level and the owner's level | none | levels restated |
| F-08 | **K5 homonym (Q4).** Row A owns "law 21, K5" (the K5 of §9.7, CON-0143). Row G assumes §9.15's "K1–K7 … K5 = integrity check". §10.2 L85 glosses CON-0143 as "the kernel assumption of §9.15", which is wrong: §9.15's K5 is the integrity check. No §9.15 K item is truly owned, but the text reads as if one were | high | 09.07 L76; 09.15 L47, L78; 10.02 L85 | Write "K5 (§9.7)". Correct §10.2 L85 | CON-0143: 09.07:76; 10.02:85; register:144; map:260 | labels qualified |
| F-09 | **Stale against F36 as decided.** Law 22's HP part was adopted as (a), self-composition. Law 20a was adopted as (b), applications/the workflow, unless F33 is a kernel law. Rows L99 and L135 and the gap list say "no owner established" on §9.11's expressibility alone | high | 09.16 L359–370; 09.11 L96 | Cite R6. For 20a, "placed in applications; owner within them unnamed" | none | rows updated |
| F-10 | **The evidence for "not sufficient" is weak, and better evidence is omitted.** In S0033 the reason given is H-CAT-1's standing refusal and n-counts (L85, L130), not that invariants are insufficient. §6.4 C1 shows H-CAT-1 blocks even a candidate that meets three criteria. Omitted: S0036 L138 ("Each has its own invariant" gives five *capabilities*) with L143 ("Capabilities are not contexts"); S0001 L29 via §1.11 L57. These are all one estate around 2026-08-02, which counts as one line of support. Point 1's language premise needs §6.5 C6, and S0150's one/most/all fork is open (§10.2 L229) | high | S0033 L83–86, L128–131; S0036 L135–143; 06.04 L18 | Re-ground the result. State it conditionally on "language is necessary" | none | re-grounded and reviewed |
| F-11 | S0036 is judged to "agree that ownership is catalogued per invariant, not per capability". The words "not per capability" are not in S0036 L60, and L138 assigns invariants per capability | normal | S0036 L60, L138 | Restate | none | — |
| F-12 | S0091 L176–190 already separates Owner from Enforcement, and lists PROPOSED I-K1 (= law 27) under C-10, which has "NO OWNER". That conflicts with row F (owner 7) and is not discussed | normal | S0091 L68, L188 | Add to §10.3.1. Mark law 27 contested | none | — |
| F-13 | Law 13's "3 enforces the read filter" conflates law 13 (widening only by an act; vis(d) ⊆ ∩) with law 22 (the non-disclosure filter). §1.7 consequence 2 makes visibility orthogonal to standing, so owner 1 is not shown | normal | 01.07 L7–12; 09.11 L49, L59 | Enforcer: 1 (step) and the derivation. Owner: open (Q6) | none | — |
| F-14 | Law 18: "'its object exists' is a gap of 4's" is unsupported. In §5.6 the object is a deliverable (L30–31), which makes it 5's or 7's | normal | 05.06 L30–31 | Restate | none | — |
| F-15 | Designation row: enforcement assumes L10/L11, K2-w and an attestation "assumed unforgeable" (§9.17 (c′)). None is marked "assumes" | normal | 09.17 L164–204 | Mark them | none | — |
| F-16 | Forced or unsupported relations. Candidate 8 "observes" names no invariant. CON-0072 is a definition, not an invariant, so "6 owns one" (L152) is not shown | normal | 10.03 L125–126, L152 | Restate | none | — |
| F-17 | I-4 row: "resolution at use open" leaves out §0.5's partial answer (H-11: a conflict region or UNKNOWN) | normal | 00.05 L10 | Add it | none | — |
| F-18 | "Necessary-like" and "strongest single criterion" are undefined or unargued (L164). "Any candidate can be split" (L160) is false for a candidate with one invariant | normal | 10.03 L160, L164 | Weaken | none | — |
| F-19 | No CON-IDs for owner, enforcer, the relations or option (i). Mandate §5 requires a CON-ID per line | normal | mandate §5 | Register them | none | — |

**R2 (`--scope 10.03`, 11 candidates, all in tier D):**
- **"define" (L35): defect (high, part of F-01 and F-04).** No procedure says when a model "defines" a term. Proposal: the CON-IDs listed per candidate in §10.2.
- **"violation" (L36): normal.** "Prevent / detect a violation" fits laws that a finite run can violate (S, T, H), not O, L or R laws.
- **"supported" (L38 vs L85): homonym.** It means evidential support in one place and the class name in the other.
- **"useful":** the criterion is informal but argued (L47). Acceptable.
- **qualify, frame, eight, falsifier, operation, reviewer, keep:** ordinary use. No defect.

**R1:** no flag in 10.03 (one H4 note on CON-0133 elsewhere).

**Quote check:** the tool found no `"…" [S… L…]` quotes. I checked the table quotes by hand against S0091, S0198, S0036, S0033 and CON-0132, and all match; S0198's is truncated but keeps its meaning.

**Missing-claim reference:** L24's "§6.5.2 and the register" is valid content, not a stale pointer (06.05 L31; register follow-up).

## Verdicts per block (§10.3.3)

| Block | Verdict | Reason |
|---|---|---|
| A Standing | PROVISIONAL | The owners are mostly plausible. Ceilings (F-07), law 13 (F-13), K5 (F-08), role exclusivity (owner contested with 7), and assumptions (F-15) need correction |
| B Well-foundedness | PROVISIONAL | The G1 marks are correct. The producer row is correctly "shared" |
| C Delivery | **INVALID as stated** | Ownership by 3 contradicts L35 and §10.2 (F-03, F-05). It should read "3 enforces; owner 1, 2, or shared". Law 22 is stale (F-09) |
| D Norms | PROVISIONAL | Levels should be S0 (F-07). Law 23 placed without argument (F-05) |
| E Catalogue | PROVISIONAL | I-3 is shared with 1 (F-03). The G10 marks are correct |
| F Method/work/assessment | PROVISIONAL, in part INVALID | CON-0072 is not an invariant. Law 27 is contested (F-12). 8 "observes" is forced (F-16) |
| G Assumed | PROVISIONAL | L1–L11 are correctly never owned. Law 20a is stale (F-09). K5 is a homonym (F-08) |

**§10.3.2:**
- Owner ≠ enforcer as a distinction: **VALIDATED** within its scope.
- The owner definition: **PROVISIONAL**.
  - It is not operational ("define" has no procedure).
  - On S0091's case it yields *no* owner, not two. "Shared" is a stipulation the definition does not produce.
  - The rival readings (§6.5.2; (o2)) are not weighed.
- The four relations: **INVALID as stated** (F-02).

**§10.3.4:**
- "Not sufficient": **PROVISIONAL.** It is plausible, conditional on language being necessary, and needs re-grounding (F-10).
- The refinement argument: **INVALID as stated** (F-01).
- Recommendation (i): **INVALID as stated.** It coincides with (ii), or it is too strong, and it is applied inconsistently.
- The question for the Principal is not well posed until F-01 is corrected. Option (iv) is missing.
- No line rests on DDD literature as a reason.

**Limits:**
- I did not open: the §10.1 review A, the S0067 text, S0001, S0037, S0062 or S0014. Those are relayed through the book.
- I read §9.13 and the strategy only in part, and did not run `ce_model.py`.
- I did not build a proof-step table for (i), because no derivation is offered.

## Files opened
- `06-theory/manuscript/review/SD3-OWNERSHIP/BRIEF.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`
- `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` (§3 only, plus a heading grep)
- `chapter_10/10.03` (whole), `10.02` (whole), `10.00`, `TODO.md`; `10.01` (grep)
- `chapter_00/00.05` (whole)
- `chapter_09/09.11` (whole); `09.15`, `09.07`, `09.10`, `09.04`, `09.05` (grep); `09.16` (grep, L179–190, L359–370); `09.17` (grep, L160–215)
- `chapter_06/06.05` (whole), `06.04` (whole)
- `chapter_05/05.06` (grep)
- `chapter_01/01.07` (whole), `01.11` (grep)
- `chapter_08/08.30` (L17–21, L50–55), `08.28` (L52–58)
- `CONCEPT-REGISTER.tsv` (rows), `THEORY-MAP.md` (grep, L199–203), `BOOK-OUTLINE.md` (grep), `RESEARCH-PURPOSE.md` (grep)
- Tool headers: `register_home_check`, `undefined_terms`, `inbound_citations`, `quote_anchor_check`, `corpus_quote_check`, `strength_scan`; `ls tmp/tools`
- A harness spill file of my own `cat` of 00.05 and 09.11
- `CORPUS-ALLOWED.tsv` (head; grep for S0033, S0034, S0036, S0091, S0150, S0196, S0198, S0199; all allowed). Corpus lines read:
  - S0033: L25–32, L75–135, grep
  - S0036: L58–61, L84–94, L135–144
  - S0091: L55–72, L170–190, grep
  - S0150: L582–593
  - S0198: L54–75
  - S0199: L82–121
  - S0196: L415–437
- Nothing refused.

## Commands and searches
- `mkdir` scratch; `cat` brief, instructions, mandate; `sed` strategy §3; `ls` / `wc` chapters
- `grep` K/L labels in 09.15; K5 in 09.07; R3a / law 22 / 26 / 20a in 09.16; R3 (c) in 09.17; held in 05.06; claims in 09.04 / 09.05; Q/O in 09.10; "own" in 01.11, RESEARCH-PURPOSE and 10.01; T10-05 and ownership in map and outline; CON rows in the register
- `python3 -I tmp/tools/register_home_check.py`
- `python3 -I tmp/tools/undefined_terms.py --scope 10.03`
- `python3 -I tmp/tools/corpus_quote_check.py 10.03`
- `python3 -I tmp/tools/inbound_citations.py CON-0143 CON-0132 "§6.5 C2" "§10.3"`
- `python3 -I tmp/tools/inbound_citations.py "§10.2"`
- Outputs are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/sd3A/` (`r1.txt`, `r2.txt`, `r3.txt`, `r3b.txt`)
