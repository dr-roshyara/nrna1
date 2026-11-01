CONSOLIDATION S0278–S0302: REPORT B

Reviewer B · Ch. 3 of the frozen copy (`BOOK-UNDER-REVIEW/`), SCOPE §2.6 · Rulebook v1.4 §12

## 1. Assessment
- Within its scope, Ch. 3 is mostly reliable. Every quote that carries a bracketed S-ID passed the quote check (75 found, 0 not found), and the spot checks against the source lines held.
- **Largest risk: concept status.** CON-0001 is VALIDATED, but by the test that makes CON-0003 INVALID it should not be (B-02). CON-0003 is tagged "validated" in the §3.1 table although it is INVALID (B-01). And §3.3's "three of the nine kinds fail the category test" is overstated, and two other places build on it (B-03).
- **Order:** §3.10 uses Ch. 6 definitions before the book introduces them (B-09).

## 2. Findings (ranked)

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| B-01 | §3.1 L17 | The table tags governance status `[CON-0003 · validated]`. The register, the heading at L52 ("marked invalid here"), T3-01 and the THEORY-MAP (✗) all say INVALID. | high | R1: `CON-0003 \| H3 status disagrees` | tag → `invalid` | 03.01 L52, L79; TODO L11; 08.29 L86; 08.30 L21, L74; MAP L18: no change needed | tag fixed; R1 shows 0 H3 flags |
| B-02 | §3.1 L15; register | **CON-0001 (nature) VALIDATED fails the book's own consistency test.** CON-0003 is INVALID because S0016's test uses values outside its value set (L128, L130). The same happens to nature: 4 of the 6 rows use values outside {decision, definition, constraint, description, finding, measurement, skill}: "capability spec" (L127), "constitution" (L128), "model" (L130, L132). These look like kinds, which is U-MM-1 (S0009 L119), and U-MM-1 is open. | high | S0016 L127–132; §3.3 L36 | CON-0001 → PROVISIONAL, reason stated; add a C3-style note | 03.00 L15 (an example tag only; no change); 03.01 L67 (no change); MAP L18 (add tag) | the value set is shown to cover the test rows, or U-MM-1 is decided |
| B-03 | §3.3 L27–34 | **"Three of the nine fail [the category test]" is not what the test yields.** The test is "one thing cannot change type by being encoded differently" (encoding or scope). By it, only **runtime asset** fails. **Artifact:** a re-encoded artifact is still an artifact. The source's own remedy for "knowledge asset" was to split it into KNOWLEDGE (kind) + ARTIFACT (kind) [S0009 L55], so saying artifact "repeats the fusion" reverses the source. **Projection** fails a different criterion (provenance), not encoding or scope. | high | S0009 L46, L55, L57 | Say: one fails by the test; artifact and projection are questioned on other grounds (bearer under (B); provenance), labelled Int | 03.08 L21 M-4 note (restate); TODO T3-03 (restate); Ch. 0 §0.3 L56 (unaffected) | §3.3, §3.8 L21 and T3-03 restated |
| B-04 | §3.3 L49–61; register CON-0011 | The INVALID reason is weak in both places. The text gives an absence-type reason ("contested": nesting unevidenced, projections n=1). That is the type of reason by which CON-0008/0041/0043/0065 were re-verdicted PROVISIONAL. The register's reason (the PKS lies outside `scope.include`, S0020 L96) confuses the EKP graph's **coverage** with the PKS's own **boundary**, which §3.10 L43 separates. The sound reason goes unstated: the definition quotes S0008 L37 with "spaces NEST", **the defect that invalidated CON-0096**. | normal | §3.3 L53; §3.10 L23, L29, L43 | Restate the core as "definition embeds F8's answer (as CON-0096)"; drop the coverage argument | 03.06 L63; TODO L14; register rows 43, 97, 98: consistent once restated | text and register give the same sound reason |
| B-05 | §3.8 C2 L46 | "references is cyclic (knowledge references other knowledge, L42)". At archetype level that is a **self-loop**. By C2's own criterion (no cycle of length ≥ 2), a self-loop does not stop a partial order. The conclusion holds by other witnesses: artifact → any concept (L43) and projection → its sources (L46), so artifact ↔ projection; S0021 Vision ↔ Mission (L83–84). | normal | S0026 L42–46; S0021 L83–86 | replace the witness | 05.04 L106 (unaffected); MAP L24 (unaffected) | witness replaced |
| B-06 | §3.10 C4 | "Every laminar family forms a forest" needs **non-empty** members. Counterexample: {∅, {1}, {2}} is laminar, but ∅ has two incomparable supersets. The finite check covers non-empty subsets only, so it does not catch this. A declared but empty space is possible. The count reproduced: 1,940 / 0 / 946 (`cons2-b-laminar.py`, output `1940 530 1476 0 946`). The script is still not in the book (T3-28). | normal | own computation | add "non-empty"; save a script (T3-28) | TODO L47 (T3-28 stays Open) | qualifier added; script cited |
| B-07 | §3.6 L76; CON-0258 | The text says the restated test is "non-circular". The register's follow-up ("G may not name x's own values") is not in the text. With G = {I-1…I-11}, the source's case, each guarantee classifies itself as an invariant, so the test decides choices only. | normal | register CON-0258 follow_up | add the clause; drop "non-circular" unless G is independent of the invariant list | TODO L46 (T3-27 consistent) | clause in text |
| B-08 | §3.5 L46; CON-0260 | "Regenerable" (could be regenerated) differs from §3.7 L48's "no longer regenerates" (in practice). A signed statement counts as a projection under the first and not under the second. | normal | §3.7 L48; S0022 L30 | say which one; then restate T3-16 | 08.25 L27, L84; 08.29 L28, L88–90 (C7 "regenerability by discipline": check); 10.01 L64; 10.02 L121 (no change) | definition fixed |
| B-09 | §3.10 L58–59, C6; §3.6 L110 | **Order (SEQ):** CON-0097's *type* and *instantiation* criteria, and C6, rely on §6.3 C1 (type/instance) and §6.1 (PKS = M(β) + evidence). M and β are not defined in Ch. 0–3. | normal | §6.1.2; §6.3 C1 | state the premises in place, or note in 03.00 that §3.9–§3.10 presuppose §6.1/§6.3 | MAP L130, L369 (no change) | gloss or note added |
| B-10 | §3.3 L11 | "not a **kind of change** (Ch. 4)": Ch. 4 says the kinds of change are **not** in that chapter; their home is Ch. 1 §1.6. | normal | 04.00 L3 | pointer → Ch. 1 §1.6 | none | fixed |
| B-11 | 03.00 L6–7 | The source list omits S0025 and S0028 (§3.6, §3.8). S0018 is used in §3.1, not "in §3.5–§3.10". "All of them are CANDIDATE … 'never authoritative without human review'" does not hold: S0166, S0889, S1052 and S0651 contain neither "CANDIDATE" nor "never authoritative" (grep count 0). | normal | grep counts | correct the list; qualify the claim | none | fixed |
| B-12 | TODO T3-24 | Related "§3.6" and evidence "S0021 L36–46" are wrong. CON-0067 is §3.8 C8. The matrix is **S0026** L36–46; S0021 L36–46 is the DP-anchor chain. | normal | S0021 L36–44; S0026 L36–46 | → §3.8; S0026 L36–46 | none | fixed |
| B-13 | TODO T3-18 | Partly resolved elsewhere. The tiers are now defined in §6.1 C3 from S0028 L116–121, which meets "tier definitions from a source". The kernel-list half is still open. | normal | 06.01 L44–49 | Status: partly resolved (§6.1 C3) | none | updated |
| B-14 | stale text | §3.3 L36 is headed "Why invalid here" although CON-0008 is PROVISIONAL. T3-10's Problem column keeps the reason its own note calls stale. T3-21 Related says "§3.1" (home §3.2). T3-20 omits §3.9 (T1). SEQ-0012 says "CON-0010, which is INVALID". T3-21a says "next consolidation (Ch. 3 not in rotation)", but it is due now (see CON-0162 below). | normal | the rows named | update | 00 TODO L30 (T3-10; check) | rows current |
| B-15 | §3.8 C5 table, sense C | It is attributed to S0029 L16 and L60 as "the capability is an interface (a port); the adapters execute". The source says `enable` operates on capabilities, never adapters, and that commit/file-save/PR/CI are adapters. "Port" and "adapters execute" are the book's reading. | normal | S0029 L16, L60 | label Int | 03.09 L10, L69; TODO L33 | labelled |
| B-16 | §3.9 C4; CON-0092 | "Enforcement is running plus a stop" makes `ask`, which stops pending a person, count as enforcement. The same bullet says "a trigger does not block". Non-sufficiency is unaffected. | normal | S0043 L49 | separate "block" from "interrupt" in the definition | 06.04 L34, L59; 08.03 L30 (unaffected) | definition fixed |
| B-17 | §3.8 L21 | "(§3.3 L25–32)": the category test is at L27–34 in the copy. | normal | copy | update | none | fixed |

## 3. Concept verdicts (Ch. 3 homes only; Ch. 2-homed concepts are reviewer A's)

| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0001 | **PROVISIONAL** (was VALIDATED) | B-02: the value set does not cover 4 of the 6 test rows; U-MM-1 open |
| CON-0047 | VALIDATED (2nd) | a well-defined method (S0022 L5, L9–10); evidence strength limited by the hypothetical domains, as recorded |
| CON-0064 | VALIDATED (2nd) | typed union, closure, extension on S0026's listed edges: union acyclic, no pair in two relations (checked); impact clause H |
| CON-0092 | VALIDATED (2nd) | non-sufficiency only (deny vs advisory, S0043 L48–50); definition: see B-16 |
| CON-0093 | VALIDATED (2nd) | the distinction and the grading rule; S0889 L1124–1128; S1524 L900 |
| CON-0002 | PROVISIONAL | bearer F37; "prose + code" compound (L127) |
| CON-0004 | PROVISIONAL | "product" missing from the value list (L129–130) |
| CON-0005 | PROVISIONAL | S0013 L77 H-9 still a hypothesis |
| CON-0007 | PROVISIONAL | owner outside the six (F13 link); no defect |
| CON-0008 | PROVISIONAL | U-MM-1 open; B-03 does not defeat it |
| CON-0010 | PROVISIONAL | distinctness supported (S0017 L68); survival not |
| CON-0041 | PROVISIONAL | n = 1 actual instance; no second found |
| CON-0043 | PROVISIONAL | contested, defined by example |
| CON-0065 | PROVISIONAL | rests on absence; plus the internal conflict (S0026 L42 vs L51) |
| CON-0090 | PROVISIONAL | a map of uses, not exhaustive by its own statement |
| CON-0094 | PROVISIONAL | first review: coherent; (a) is n = 1 and the check was not read |
| CON-0095 | PROVISIONAL | a stipulation; skill case handled by interim scope; holds for person-as-adapter (§6.1 C5) |
| CON-0097 | PROVISIONAL | "answers to" (ownership) undefined; B-09 |
| CON-0098 | PROVISIONAL | S0 attribution U; bound of S2 open |
| CON-0162 | PROVISIONAL | (ii) ⇒ (i) checked. **Order dependence confirmed:** with D = ∅ and d2 a one-to-one recoding of d1, whichever is proposed first is admitted and the other fails (ii); the admitted set depends on order (T3-21a) |
| CON-0184, CON-0185 | PROVISIONAL | a definitional fork; both coherent; they differ only on G/S bearers and carrier-less claims, as stated |
| CON-0258 | PROVISIONAL | B-07 |
| CON-0260 | PROVISIONAL | B-08 |

**INVALID cores, checked as restated (not re-litigated):**
- Consistent: CON-0006, 0009, 0029, 0030 (T3-05 says "not verifiable", the register says "membership incoherent": a minor mismatch), 0031, 0032, 0033, 0034 (Ch. 3 home), 0035, 0036 (omission: PRODUCT's kind "application", S0021 L62, is also outside the eight), 0037, 0038, 0039, 0042, 0044 (Ch. 3 home), 0045, 0046, 0057, 0066, 0067, 0091, 0096.
- Restatement defective: CON-0003 (tag, B-01) and CON-0011 (reason, B-04).

## 4. Coverage

| Item | Status | Home |
|---|---|---|
| Ch. 4 (§3.3 L11) | **stale** | Ch. 1 §1.6 (B-10) |
| §5.1 D-8 (§3.5 L54) | ok | 05.01 L53 |
| §5.3 S0029 (§3.8 C7) | ok | 05.03 |
| §6.1 C4 (§3.6 L110) | ok | (out of scope: §6.1 C4's heading still reads "necessary, not sufficient") |
| §6.1 C4–C5 "admission needs standing" (§3.6 L110) | C5 is off-topic | appendix |
| §6.1 C5 (§3.9 L182) | ok | — |
| §6.1, §6.3 C1, C7 (§3.10) | ok | B-09 |
| Ch. 8 cross-product method (§3.7 L15) | ok | 08.00 L6; CHAPTER-PLAN L57 |
| T3-01–T3-17, T3-19, T3-22, T3-23, T3-27 | open | — |
| T3-18 | partly resolved | §6.1 C3 |
| T3-24 | open, row wrong | B-12 |
| T3-25, T3-26, SEQ-0010, SEQ-0015 | resolved | correct |
| SEQ-0012 | open | stale wording (B-14) |
| T3-20 | open | THEORY-MAP F11; stale Related |
| T3-21, T3-21a | open | due now |
| T3-28 | open | B-06 |

## 5. R2 (`--scope 03`): every candidate judged

| Word | Judgement |
|---|---|
| laminar | defined at first use (L103) |
| D-1 | glossed (seed domain list) |
| F-09, F-10, F-11 | review IDs, opaque; acceptable as review references |
| Tier-1 | glossed by the quote; tiers defined only in §6.1 C3 (order issue, as B-09) |
| MM-1 | defined (03.00 L8) |
| action (§3.9) | defined by the "act" row |
| port | glossed L36 |
| skill | Ch. 0 §0.3 |
| node | §3.8.2 |
| ideal (§3.10 L137) | undefined, but used inside Hyp/Ext C8 (acceptable) |
| domain-knowledge, platform-service, technical-service, source-project, space-in-space | compounds of defined phrases |
| cyclic, restriction | standard mathematics |
| contradictory, larger, qualify, tactical, caution, constrained, classify, enable, hand, unsupported, unbounded, hypothetical, precede, topic, recommended, onto, processe, narrow, opposite, standard, discovery, local, data, asked, machine, protect, stronger, resolve, specification, apply, fourth, edge, produced, built, adopt, govern, stop, beside, contradiction, remain, agree, rival, keep | ordinary English, no theory meaning |

## 6. Coverage of sections
I read 03.00–03.10, 03.99 and TODO.md in full. 03.99 has one row, while the prose keeps history inline (e.g. §3.4 L7, "Earlier text, kept as history"), against its own rule 52a (appendix).

**Limits:**
- EXT-029, GKC and PKSM are not on the allowed list; I did not open them.
- I spot-checked sources and did not re-read all of them. The source dates in §3.10 C2 were not checked.

## Summary
- Ch. 3's quotes and most attributions hold.
- Three high findings concern concept status and one overstated derivation: B-01 (CON-0003 tag), B-02 (CON-0001 → PROVISIONAL) and B-03 (category test).
- Twelve normal findings, B-04 to B-17, plus an appendix:
  - one mathematical qualifier (B-06, empty set);
  - one wrong witness (B-05);
  - one ordering problem with Ch. 6 (B-09);
  - stale pointers and TODO rows.
- I reproduced the laminar count (1,940 / 0 / 946).
- All 22 PROVISIONAL verdicts in my scope are kept; CON-0001 is downgraded.

**Process independence:** a separate run that saw no other reviewer's report; independent in process, not in model.

## Appendix
- §3.10 L31: "containing ∅ and Q" is at S0651 L19, not L51–60.
- Ch. 3 prose carries consolidation history inline; 03.99 has one row.
- §3.2's heading says "(Src)" over the restated CON-0162, which is Ded.

## Files opened
- In the review folder:
  - `06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/`: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-B.md.
- In W:
  - `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- In the copy `BOOK-UNDER-REVIEW/06-theory/`:
  - `manuscript/chapter_03/` 03.00–03.10, 03.99, TODO.md (Read);
  - CONCEPT-REGISTER.tsv (scripted extracts);
  - EXTERNAL-SOURCES.md, by grep;
  - by grep or sed: THEORY-MAP.md, CHAPTER-PLAN.md, chapter_04/04.00 L3, chapter_05/05.00 and 05.01 and 05.03, chapter_06/06.01 and 06.03 and TODO, chapter_08/08.00 L6, other chapters' lines named above.
- Corpus line ranges, all on the allowed list:
  - S0005, S0008, S0009, S0012, S0013, S0016, S0017, S0018, S0020, S0021, S0022, S0023, S0024, S0025, S0026, S0028, S0029, S0031, S0033, S0035, S0043, S0577, S0651, S1052, S1524;
  - a grep only of S0166, S0889, S0357.
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.
- I reached no file in REVIEWER-NOTE item 4: no working chapter, map, register or outline, nothing in 00-access, no git history.

## Commands run
- `ls`, `find` and `wc` on the copy.
- `W/tmp/tools/register_home_check.py --root <copy> --help` (it ran R1).
- `undefined_terms.py --root <copy> --help`; `--scope chapter_03` (empty); `--scope 03`.
- `inbound_citations.py --root <copy> --help`; then CON-0003, 0001, 0011, 0008, 0097, 0258, 0260, 0092; "§3.8 C2", "§3.10 C4", "§3.3", T3-24, T3-18.
- `corpus_quote_check.py --root <copy> --help` (it errors); `corpus_quote_check.py <copy>/…/chapter_03/*.md`.
- `book_search.py --help`, run from `<copy>`.
- `awk` / `sed` / `python3 -I` extracts of the corpus lines listed above.
- `grep` on the allowed list, on the copy (ARCH-INV-1, CON-0041, T3- IDs, category test, kind of change, S0025/S0028, EXT-029), and CANDIDATE/"never authoritative" counts in six brainstorming sources.
- Scratch script: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons2-b-laminar.py`.
- Nothing in the repository was written.
