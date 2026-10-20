# Window C20: per-source measures, step 8, falsification attempts, book search, mechanical checks

*2026-10-21. Sources: S0106, S0107, S0108, S0109, S0110, in order, each read whole (depth full), each started and closed in turn (the Principal, 2026-10-21: "record per window: time per source, findings per source"). New: Ch. 8 §8.13 (C1–C7); register CON-0202–CON-0206. Pointers: Ch. 8 index row 8.13; THEORY-MAP F24. Window size 5: §12a's extension to 10 was not used. The series continues past S0110 (same work item), but "at most 2 added" and the split review would have bought little for 425 lines.*

*Also in this window's review, as changed theory: the C19 settlement's changes (Rulebook §2 step 9) and the application of F22 = (C + O) (the Principal, 2026-10-21): §9.13 v10, §9.15 v7, §9.16 v5 (R4a), the §9.06 pointer, THEORY-MAP F22, CON-0191/0193/0194, model v11 (check 24), EXT-090/091, and the corpus-first reading behind it (S0071, S0137, S0183, topic-full).*

## Per source (time, depth, findings)

Time = the interval between `close_source.py --start` and the close, from the READ-LOG. For this reader it is one whole-file read plus the close, so it measures the procedure, not reading effort, and is not comparable to a human's time.

| Source | Lines | Start → stop (UTC) | Time | Depth | Findings | Used in §8.13 | Repeats |
|---|---|---|---|---|---|---|---|
| S0106 | 69 | 22:29:16 → 22:29:28 | 12 s | full | 2 | C6 | S0102's map (L15–31), S0104's recusal |
| S0107 | 118 | 22:29:28 → 22:29:41 | 13 s | full | 5 | C3, C5, C6, C7 | S0103's gate criteria (L73–85) |
| S0108 | 53 | 22:29:41 → 22:29:52 | 11 s | full | 3 | C3, C4 | the distinction chains (L45), S0102 |
| S0109 | 137 | 22:29:52 → 22:30:03 | 11 s | full | 4 | C1, C2, C3, C4 | CON-0154 (L19–22), S0108's attribution point |
| S0110 | 48 | 22:30:03 → 22:30:17 | 14 s | full | 3 | C1, C2, C5 | S0109 |

(Start times from `tmp/corpus-reading/START-TIMES.tsv`; each start was recorded at the previous close.)

**Skim, honestly applied (Rulebook v1.3 §3).** No source was skimmed. The judgement was made per source, after reading it whole:
- **S0106 is the nearest to skim.** It is mostly a mapping of a commission onto S0102's sections, plus a refusal of the kind S0104 made. What it adds: the governor of a record may not produce the analysis of the separation it governs (L43), and that one line is used in C6. A reviewer may judge it skim; it is closed full in the log, which is not rewritten.
- S0107–S0110 each add at least one finding not in S0101–S0105 (table above).
- **The reviewer's safeguard.** There is no skimmed source to read blind, in this window or before it. The window reviewer was therefore asked for a blind depth judgement of each source (brief, step 1).

## Book search (lexical and by meaning; top hits read)

| Query | Hits read | Relation |
|---|---|---|
| meaning: a pointer carried without vouching; authority scoped to one claim | §1.13 ("a pointer to the act"); CON-0154 (§8.10 C4) | extends (C1) |
| meaning: a requirement whose referent never occurs; existence | CON-0199 (§8.12 C7); §1.16 (INV-EXC-004, "needing no referent") | dual (C2) |
| meaning: a decision's basis and its status; corroboration never authority | §9.13 marks; §2.8 (corroboration); §1.13 L81 | extends (C3) |
| lexical: P-perf, "performed by recording" | §9.15 R4 P-perf; §9.16.8 | a case (C4) |
| lexical: supersession, supersede | §0.5 I-7; §1.6 (CON-0019); §1.2; THEORY-MAP F24 | converse (C5) |
| lexical: "admits no", "no eligible", assurance capacity | §8.9, §8.11, §9.13 (none on the point) | parallel to §9.16 R4a (C6) |
| meaning: an append-only record with no correcting kind | §8.12 C8; §9.13 (removal acts; I15) | extends (C7) |

## Step 8: dependencies of §8.13

| Relies on | Status | Check |
|---|---|---|
| §1.13 (the grant as a pointer) | — | C1 compares; nothing changed |
| §8.10 C4 (CON-0154 PROVISIONAL) | **unverified** | C1 restates claim-scoping from S0109 |
| §8.12 C7 (CON-0199 PROVISIONAL), C8 | **unverified** | C2 is the dual; C7 extends C8 |
| §9.13 v10 (marks; D's kinds; I15) | changed theory, **not yet reviewed** | C1, C3, C5, C7 compare with it. One stale line found and corrected: "read by no clause … or the marks" (since v9 one mark reads the recorder) |
| §9.15 P-perf | open | C4 is one case; P-perf stays open |
| §0.5 I-7; F24 | — | C5 is the converse; F24 row pointer added |
| §9.16 R4a (F22 = (C + O)) | changed theory, **not yet reviewed** | C6 draws a parallel only |

## Falsification attempts (Rulebook §2 step 2, entry 55)

| Concept | (a) strongest counterexample | (b) boundary | (c) rival | (d) falsifier |
|---|---|---|---|---|
| **CON-0202** (a reference is not an assertion) | a reference whose presence is itself read as endorsement: a receipt listing "applicability decision A" may be read as "A applied", whatever the negative list says | one decided receipt; the negative list is what makes the reference non-asserting | a reference **is** a weak assertion (that A exists and was the one used); S0109 itself requires A to exist (C2) | an estate act that treats a receipt's applicability reference as evidence that A was correct, uncorrected |
| **CON-0203** (the referent gap) | the referent may exist under another name (an act performed but not recorded as `ContextSelected`) | S0109's evidence is one line of the canonical analysis (L551), itself unverified (S0108) | the gap is a **schema** gap, not an existence gap: a null field is a valid value | a populated conforming receipt before `ContextSelected` has an owner |
| **CON-0204** (a basis carries its assurance status) | a decision on an unverified basis that is later verified needs no change: the "inherited question" was harmless | decisions of one authority, as recorded by one process | the PO/ARB "may decide on any basis it judges sufficient" (S0108 L39): the status is information, not a constraint | a decided act later found to rest on a refuted basis and left standing without note |
| **CON-0205** (selecting is deciding) | a recorder that transcribes a selection made elsewhere (the decider's words) records, not decides: the line is the **origin** of the selection, not the act of writing it | decision slots only | the corpus's own rule may be a governance convention, not a property of recording | an estate act accepting a process-supplied selection as the decider's without a further act |
| **CON-0206** (a later act is not a supersession) | the second act is the PO/ARB's own, on the same day: the most recent will of one authority is the natural reading | two acts of one authority on one slot | "later wins" for one authority (a single writer's last act); §9.4 C3's one current claim plus supersession | a disposition that treats the later act as superseding with no recorded link, accepted as such |
| **C6** (a strict gate admits no one) | a fresh process with no lineage satisfies the gate trivially, so "no known process" is not "no possible process" | the processes known to one work item | the gate is not too strict; the work item simply has no fresh process yet | — (an observation) |
| **C7** (attribution amended beside the record) | the documentary amendment is itself recorded in git, so the estate does correct attribution, outside the engine | the workflow engine's transition types | correction by a further transition of a new type (Governance's choice, S0107 L18) | — (an observation) |

## Mechanical checks

- **Quote check** (`corpus_quote_check.py`): §8.13 25 found, 0 not found; §9.13 10 found, 0 not found; §9.15 13 found, 0 not found; §9.16 21 found, 0 not found; §9.06 14 found, 0 not found. Whole book: **14 NOT FOUND lines, equal to the baseline.**
- **R1** (`register_home_check.py`): 182 rows with a section home; 1 flag (CON-0133, H4 information, inherited; unchanged). CON-0202–0206 found in §8.13 with their status tags.
- **R2** (`undefined_terms.py`), candidates in changed sections: tier A "crowded", "tombstoned", "iterating" (inherited, as at C19); tier C "defined", "correct" (plain words). None in §8.13. No action.
- **close_source.py**: S0106–S0110 logged full, in order; S0071, S0137, S0183 topic-full (F22-CO-single-writer), the reading position unmoved by them. One citation slip found and fixed before the check: S0071's DP-3 is at L276, not L277.
- **Model**: `ce_model.py` v11, run `ce_model-run-20261021-v11.txt`, all assertions passed (about 40 s).

## Strength scan (§8.13)

- Each claim is scoped to one work item; "one line of support" is stated in the lineage.
- "Closes the authority defect" is the source's statement (S0109 L41), in quotes.
- C2's kernel line is labelled "Ded, trivial", and C6 and C7 are labelled observations, not concepts.
