C62 STEP 2-3: REVIEW REPORT

Window reviewer W, transition review of §8.55 (C1–C5) and of the changed theory (`a6bb12593`, the C61 settlement). Before starting I re-read my saved inventory (`STEP1-INVENTORY.md`); its sha256 `22874085…2284` matches `SHA256-STEP1.txt`.

## Summary

1. **Overall.** §8.55 quotes its sources correctly. C2's main agreements and C3's two readings of "source" hold. No finding is critical.
2. **C1 misses the strongest evidence in the window.** The producer's next step in S0288 and S0289 skips the Architecture stage that the independent review had routed. S0291 finds exactly that gap. So the claim that the coordination problem was "solved" meets a counterexample inside the window (W-01).
3. **C4's opposition between the two records rests on word senses nobody checked.** S0290 uses "kernel" in the narrow sense throughout. Its own table maps a root to "identity origin". §8.36 C4 (iii) is about the empty value, not about storage (W-02).
4. **C4 cites three homes for words they do not contain or on the wrong axis:**
   - "content contradictions coexist" (§8.35 C4) uses the heading. The body, narrowed at (i-a) and settled again at C61 for §8.54 C4, says such a conflict is neither recorded nor reported (W-03).
   - The "two times" of §1.16.2 are an authority pair, and Art 11.2 holds only temporal validity (W-04).
   - "A AND B until resolution" is not in Art 8 (W-06).
5. **§8.55 was not re-read against the C61 settlement.** It still lacks the three admissions left out of the count, now in §8.54 C1, and the (i-a) narrowing (W-05). The C61 R3 note that §8.55 is "unchanged in substance" is wrong for these two points (W-17).
6. **The C61 settlement is validated as applied to §8.54.** I checked every row against its text and the plain-language lines by hand. Its step-8 dependency list is stale (W-17).

**Process independence.** The independence here is of process, not of person: I am the same reviewer W who wrote the blind inventory, and I saw §8.55 only after that inventory was saved.

## Findings

Labels used in the Evidence column: V = I verified it in the text; Src = it comes from a source; Int = my interpretation.

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C1 omits an in-window counterexample.** S0288 L129–131 and S0289 L48–50 put the bounded review next. S0291 L45–57 and L97–106 show that the routed Architecture stage (RV-1…7) had not been done, and the bounded review puts it back. S0289 L42 already listed the independent review as done, so the next step the producer named skipped a stage. S0288's scenario (L49) also replays an earlier state of the chain. | high (omission) | V: S0288, S0289, S0291 at the lines given | Add to C1 two readings of the evidence: (a) the handoff misrouted the next step; (b) the mechanism worked because the bounded review caught it (§8.57 C4 later reads it as (b)). The PO/ARB's "solved" stays a decision, now with a counterexample recorded beside it. Correct the rival's answer accordingly. | C1 records S0291 against S0289 L48–50, with both readings |
| W-02 | **C4's opposition rests on word senses not checked in both texts.** (1) S0290 uses "kernel" narrowly throughout: L3, L15, L33 ("none a kernel law"), L56, L85 ("none enter the kernel"). Reading (a), "the whole system", has no support in S0290, so the inconsistency is first *inside* S0290 (L47 against L85). (2) S0290 L33 maps a root (dhātu) to "identity origin", so "a semantic root is content" is not S0290's sense. (3) §8.36 C4 (iii) says that for content "the kernel has no value", which concerns the empty value, not storage, and the record keeps entries (§8.54 C2). (4) "Stores" (S0290) and "law" (S0292) are different relations. | high (defect) | V: S0290 L3/15/33/47/56/85; §8.36 L80–83; §8.54 C2 | Restate C4: the tension is inside S0290 under its own sense of "kernel". Add a reading in which a root is identity, which the book holds as item identity (open, §8.39 C3), with no conflict. Drop "against §8.36 C4 (iii)" or restate it per axis (value, not storage). The rival's answer (reading (a)) is then inadequate. | C4 restated with these senses |
| W-03 | **"Content contradictions coexist until resolved at use (§8.35 C4)" uses the heading of §8.35 C4, not its body.** (i-a) narrows it: a conflict between non-rule items "is neither recorded nor reported". Sandhi composes two items, so (i-a) applies: required, not admitted. Settled §8.54 C4 (C61, W-04) gives exactly this reading. | high (defect) | V: §8.35 L74–77; §8.54 C4 "Content" | Replace with "required, not admitted (§8.35 C4 (i-a); CON-0188)". Also note that Art 8.1 requires *governed* resolution, against rule-based "conflict resolution" at composition. | C4 restated |
| W-04 | **The "two times" agreement is on the wrong axis.** §1.16.2's tₐ and t are times of an *authority* act and its use. §8.55 describes them as "apart from its place in the record", which §1.16.2 does not say. S0292's event time ≠ knowledge-state time is about *content*. Art 11.2 says only "temporal validity", not "valid time and transaction time". §8.57 C5 (L63) repeats the same agreement. | high (defect) | V: §1.16.2 L31–51; S0241 L114 | Restate: Art 11.2 gives one validity time and Art 11.1/11.3 give record order, so agreement holds in part (content validity plus history). §1.16.2 is an authority analogue, not a home. R3: §8.57 L63. | C4 and §8.57 C5 restated |
| W-05 | **Draft not re-read against the C61 settlement.** Settled §8.54 C1 says the count "fourth" leaves out three outside sources (S0263, S0277, S0285). The same omissions apply to S0290's "fifth" (L4, L13, L86), but §8.55 C3 cites §8.54 C1 only for "classification". W-03 shows a second missed point. | high (defect) | V: §8.54 C1 L97; §8.55 L56 | Add the three omissions to C3. Correct the header's "no line relied on a restated claim". | C3 and the header restated |
| W-06 | **Art 8 quoted with words it does not contain.** "A AND B until resolution" is the estate's gloss (§8.48 L7, L78: "the record's, not S0262's"). Art 8.1 reads "coexist as CONFLICTED until governed resolution". | normal | V: S0241 L90; §8.48 | Quote Art 8.1. | C4 restated |
| W-07 | **C3 misses evidence from within the line itself that favours reading (b).** (1) S0290 L86 calls its own admission "single-source". (2) S0250 L114's instances of "intake" are single records (a Vedic intake `…1007` among them). (3) S0287 L13 admits a *cited web source* as the fourth admission, so by that precedent each new cited web source in S0292 is one. | normal (omission) | V: S0250 L114; S0287 L13; S0290 L86 | Add these to C3's reading (b) and to the rival's answer. The answer itself is adequate. | C3 restated |
| W-08 | **Authorship readings missing, and a later source not disclosed.** S0290 L4 says "the commission text is the HPA's own, recorded verbatim-in-substance", and S0292 L3 is similar. §8.55 carries none of the three readings (wrote, adopted, commissioned) that §8.44–§8.54 carry each time. S0292's subject is a later corpus source (S0295, §8.57 C1), which the lineage note at L7 does not disclose. | normal (omission) | V: S0290 L4; §8.57 L7 | State the three readings for S0290 and S0292. In the lineage, disclose that S0295 is S0292's source text (later in index order). | lineage and C3 restated |
| W-09 | **The template gate: the producer's sentence again.** S0288 L8: "The protocol passed the governance loop (… integrated into agent templates)", which is the pattern of §8.52 C4, repeated. The PO/ARB act in S0289 L16–21 is about the *test*. It mentions neither the templates nor the gate, so it does not answer §8.52 C4's question (1). S0289 L25 is credited "(PO/ARB)" but sits outside the verbatim block. L61 strengthens "reduced" (L23) to "no longer". | normal (omission) | V: S0288 L8; S0289 L16–25, L61 | Add these to C1's third bullet and to the "demonstrated" item. | C1 restated |
| W-10 | **C2 states one carrier reading as certain.** "An untracked file is a document, not an entry of the designated record" assumes git is the designated carrier. §9.17 L12–20 says a designation (an act) fixes the carrier, and membership depends on acceptance. Two readings: (a) git designated; (b) the entries exist in one carrier without a durable second copy (§9.17 point 4). | normal | V: §9.17 | Give both readings. The record/carrier home stays. | C2 restated |
| W-11 | **C2 home on the wrong axis.** T9-16 (b) is about *who may withdraw* evidence (a grant over the evidence entry's scope). C-11's "never citable as technical verification" limits *purpose*, which is §9.18's reliance "for a purpose". | normal | V: ch.9 change log L80; MAP L400 | Re-home to §9.18 (purpose), or state the analogy. | C2 restated |
| W-12 | **C2 omits three S0291 points.** "Decides nothing" (L8) next to "PHASE 3 MUST NOT BEGIN" (L89): either a reading of the record (no grant, F) or a decision. "Closed" in two senses (L66 a property, L87 a status). "Only the independent review mentions them" (L38) is an absence found by search. | normal (omission) | V: S0291 | Record each. The first fits decision C1 under the "reading" interpretation. | C2 or §8.55.3 |
| W-13 | **C5 merges two case mappings.** The quote is S0292 L36's: vocative = Recipient, dative = Purpose. S0290 L30 has vocative = addressed, dative = beneficiary, and S0290 L46 promises eight interrogatives but lists seven (a source slip). §8.58 L65 later finds that possession and address are not participant roles. | normal | V: S0290 L30, L46; S0292 L36 | Cite the two mappings separately and record the change. | C5 restated |
| W-14 | **PL5 drops C5's condition.** C5 says "agreement with A1 *if* the judgment is a recorded act"; PL5 states the agreement without the "if". | normal | V: §8.55 L28, L70 | Restore the condition in PL5. | PL5 restated |
| W-15 | **Two home omissions in C4/C5.** S0292's witnessed/unwitnessed mood is a *means of acquisition* (S0241 Art 3.3), not a time. Its "Confidence" field (L34, L49) meets Art 2.3 ("no single score"), as §8.57 C2 later does for S0295. | normal (omission) | V: S0241 L45, Art 3.3 | Add both, with §8.57 C2's two readings. | C4 or C5 |
| W-16 | **Coverage gaps in §8.55.3.** S0290 and S0292 have the status pattern "adds structure at mechanism level" against "does not add structure" against "genuinely new" (S0290 L4/L56/L60; S0292 L20/L66; §8.51 C2, as §8.54.3 records for S0287). The meaning layers shift between v1 and v2 (conventional usage moves from layer 3 to layer 2, and "operational" is added) while the text calls this "enriches". S0288's "deterministic" (L117) rests on one synthetic case. | normal (omission) | V | Record these in §8.55.3. | §8.55.3 |
| W-17 | **Changed theory: propagation and step-8 records.** (1) The C61 settlement's R3 says §8.55 is "unchanged in substance", which W-03 and W-05 contradict. (2) The C61 step-8 dependency list still names §10.5 PL-3 and §1.17.2, both removed by W-04 and W-07. (3) C62 step 8 judged only "designee" under R2 (D2 requires all twelve in-scope candidates; see Coverage). (4) §8.55's homes table lacks §8.54 C3 (C4) and Art 1.1, §8.39 C3, §8.35 C1, §8.52 C2 (C5), all of which the step-8 list has. (5) A naming point: §8.55 L53 keeps the grammarian's name as an adjective inside a quote, against L7's statement that the name is not reproduced. | normal | V: SETTLEMENT C61 L23; C61 STEP8 L20–21; c62-w-r2.txt | Update both step-8 files and the homes table. Decide the naming convention for quotes. | records updated |

## Claim verdicts

| Claim | Verdict | Scope and reason |
|---|---|---|
| C1 | PROVISIONAL | The registrant pattern (§8.50 C3) and the two readings of "demonstrated" hold. The S0291 counterexample and S0288 L8 are missing (W-01, W-09). Recorded challenge: **inadequate**, because it does not test against S0291. |
| C2 | PROVISIONAL | "Folds the record" = decision C1, PASS = §8.36 C1 and the identity U are VALIDATED. The carrier reading is stated as certain (W-10) and the T9-16 home is on the wrong axis (W-11). |
| C3 | VALIDATED (two readings; (b) fits better) | Holds within its scope. The count's omissions are missing (W-05) and evidence from within the line could strengthen it (W-07). Challenge: **adequate**, with the additions in W-07. |
| C4 | PROVISIONAL; three parts INVALID as stated | Art 2 pointer: VALIDATED (S0241 L41–45 is "Dimension"; Art 1.4 is at L36). INVALID as stated: the §8.35 C4 home (W-03), the two-times agreement (W-04), the Art 8 quote (W-06). The kernel opposition is not established (W-02). Challenge: **inadequate**, because reading (a) is adopted against S0290's own usage. |
| C5 | PROVISIONAL | Refusing Token = Meaning agrees with Art 1.2 (VALIDATED). For "one word = one meaning", Art 1.4 is the closer home. Two case mappings are merged (W-13) and PL5 drops the condition (W-14). |
| C61 settlement | VALIDATED as applied to §8.54 | All eleven rows match the text. The plain-language lines PL1–PL5 match C1–C5. Propagation is incomplete (W-17). |

## Coverage (my inventory against §8.55)

- **COVERED:**
  - the synthetic test and producer bar (S0288 1–3, 8–10; S0289 1–5);
  - S0291 1–2, 5–7, 9;
  - S0290 1, 3, 7–9 (S0290 2, 4 and 6 are left as analogies, recorded in §8.55.3);
  - S0292 1, 6–9, 12;
  - the kernel/engine tension.
- **MISSING:**
  - S0289 9 (L61 strengthened) and S0289 10 (synthetic against chain), partly carried;
  - S0291 11–13 (W-12, W-01);
  - S0290 10 (absence by search), 12 (seven of eight), 13 (status pattern);
  - S0292 13–14 (W-13, W-16), S0292 3's confidence (W-15);
  - S0288 11 (n = 1).
- **DISTORTED:**
  - the S0290/S0292 case lists merged (W-13);
  - §8.35 C4's scope (W-03).
- **JUSTIFIABLY EXCLUDED:**
  - S0288's field table as operations;
  - S0290's maturation ladder as "another level list".
- **New fork, not in the map:** whether the bounded review's catch is the handoff mechanism working, or the handoff failing (W-01).
- **Source slips, separate and not relied on:** S0290 L46 (eight promised, seven listed); the sense of vocative in S0292 L36.
- **R2 in scope (`--scope 08.55`, all judged):**
  - "designee": ordinary sense, as step 8 says;
  - "template": two senses in the window (agent templates; the completion template, S0288 L31). §8.55 L39 uses the first; qualify it;
  - "family": the very word C3 turns on; defined there by its two readings, which is adequate;
  - "registration": §8.50 C4's act;
  - "registrant", "material", "beyond", "next", "quoted", "once", "agree", "rival": ordinary words, no row needed.
- **R1:** 241 homes, 1 flag (CON-0133), unchanged.
- **R3** (`inbound_citations.py`) for the corrections:
  - C1: §8.57 L11, L23, L59 (L59 relies on "two handoffs"; W-01 applies);
  - C2: §8.57 L23, L58; §8.59 L20;
  - C3: §8.57 L7, L20, L38;
  - C4: no claim-level citations; §8.57 L63 repeats W-04's agreement independently;
  - C5: §8.58 L65.

## Next steps (in dependency order)

1. W-03, W-04, W-06, then W-02: restate C4. Done when each home is quoted from its own lines and labelled by axis.
2. W-05, W-07, W-08: restate C3 and the lineage.
3. W-01, W-09: restate C1, with the new fork recorded.
4. W-10 to W-16: the remaining points.
5. W-17: update the records.
6. R3: propagate W-04 to §8.57 C5 and W-01 to §8.57 C4.

## Limits

- I did not open S0266, S0273, the independent DV review, or S0295. S0295 is known here only through §8.57.
- No tool checks the "(Lx)" citations. I checked the quotes by hand, for the cited lines only.
- I did not check the C61 rows against S0254, S0283, S0285 or S0286 (not opened). I checked them against the §8.54 text and the C61 settlement only.

## Files opened (read-only)

- W/06-theory/manuscript/review/C62-S0288-S0292/: BRIEF-W-STEP2.md (via `cat`), STEP1-INVENTORY.md, STEP8-AND-CHECKS.md, SHA256-STEP1.txt
- W/06-theory/manuscript/chapter_08/08.55-…md (whole), 08.57-…md (whole), 08.52-…md (L1–30, L61–70, plus grep hits), 08.50-…md (L62–80), 08.36-…md (L38–46, L72–90), 08.35-…md (grep: L74, L77)
- W/06-theory/manuscript/chapter_01/01.16-…md (L31–56); chapter_09/09.17-…md (L1–30)
- W/06-theory/manuscript/review/C61-S0285-S0287/SETTLEMENT.md
- `git show a6bb12593 -- 06-theory/` (saved by the harness to its tool-results file, which I then read)
- Corpus (each path checked on CORPUS-ALLOWED.tsv first):
  - S0241 `docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md` (L30–60, L86–120);
  - S0250 `docs/knowledgeos/reviews/20260822-1028-KOS-EP01-Research-Phase-Closure-Ratification.md` (L108–118);
  - S0287 `docs/knowledgeos/reviews/20260822-1330-KOS-EP01-Escher-Lens-Transformation-Representation-Integrity.md` (L13, L25).
- Grep only (lines shown, not files read): 08.19 (title, L16), 08.48, 08.45, 08.46 (the "A AND B" hits), 08.49 headings, 08.54 and 08.55 (naming check), CONCEPT-REGISTER (CON-0188), THEORY-MAP and the ch. 9 index and change log (T9-16).
- Scratch file written: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c62-w-r2.txt`

## Commands run

- `git log`/`git show --stat c46adca59`; `cat -n BRIEF-W-STEP2.md`
- `ls` of the C61 and C62 folders; `sha256sum STEP1-INVENTORY.md`; `wc -l`
- `git show --stat a6bb12593`; `git show a6bb12593 -- 06-theory/`
- `ls tmp/tools`, chapter_08, chapter_01, chapter_09
- `python3 -I tmp/tools/undefined_terms.py --root W --scope 08.55` (output to c62-w-r2.txt)
- `python3 -I tmp/tools/register_home_check.py --root W`
- `python3 -I tmp/tools/inbound_citations.py --root W "§8.55 C1" … "§8.55 C5"`
- A first probe with `--help`, without `--root`: undefined_terms, register_home_check, inbound_citations, book_search, corpus_quote_check (error) and quote_anchor_check printed usage, background lists or default-root output only. I did not use these outputs.
- `grep`/`sed` on the files listed above, and `grep -n` on CORPUS-ALLOWED.tsv for S0241, S0250, S0266, S0273, S0287.

Nothing was edited.
