# KC-1 blind2 packets: source trace (NOT for encoders)

Base folder: `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/R7/` (read-only; no existing file edited).
Build: `build/extract.py` (the `blind/build/extract.py` script with only the seed line changed to take a seed argument, default 20261018), `build/build.py`, extracted data `build/data.json`. Run from `blind2/`:
`python3 -B -I build/extract.py ../.. build/data.json 20261018` then `python3 -B -I build/build.py build/data.json .`

## Rows, concepts, shuffle

- Row texts, concept items C1–C28, their source lines and cleaning: **identical to `blind/`** (checked: `texts`, `src`, `concepts`, `csrc`, `initial_order` of `blind2/build/data.json` equal those of `blind/build/data.json`). See `blind/key_sources.md`.
- Shuffle: initial list `[R1, R6, R9, R11, R14, R15, R16, R17, R21, R79, R80]`, `random.seed(20261018); random.shuffle(...)` → **R9, R80, R79, R21, R1, R16, R14, R17, R6, R15, R11** (same order in both packets).
- Symbol glossary: identical table to `blind/` (T3:67 rule-2 mapping, placement labels removed; K8 (a) and K9 (a) from KC:95–96; σ → C6, Chg → C7 split; "T → no item" analyst wording). It is now its own section 3 in both packets.

## Task text (both packets; changes from `blind/`)

- "using the symbol glossary in section 3" replaces "and the shared encoding rules in section 3": there are no shared reading rules any more; each packet carries its convention's full rule set in section 2.
- **Reasons:** a one-line reason is now required for **every row** (11 rows), not only the hard ones, naming the section-2 rule applied; hard rows are marked "HARD". It is required for all rows, not only R11, R14–R17, R21, because naming those six would disclose the guard list (leak).
- **Unsupplied-text bullet (analyst addition, identical in both packets):** cited definitions, axioms, clauses and rows are not in the packet; read only what the packet supplies and say so. Needed because both rule sets tell the encoder to read cited texts (A: Def 3.1 clause texts, class clauses; B: definiens, row references, class clauses).
- JSON output format unchanged.

## Packet A′ — convention A (T3-CANDIDATE-MODEL.md §1a, lines 66–76, plus line 281)

| packet rule | source | paraphrase / omission |
|---|---|---|
| 1 first sentence | T3:66 rule 1 "its statement (T2 r1.4 "statement (working label)" column, including the clauses it names) reads at least one item placed on CB1 and at least one item placed on CB23" | **P1** crossing criterion → "What a row reads is taken from its statement (the full text given for the row, including the clauses it names)". CB1/CB23, "crosses the P-c cut" removed |
| 1 second sentence | T3:66 "A **T-B row** is judged by its clause text only (see rule 3)." | **P2** "T-B row" → "A row that states a clause or reading of a hypothesis" (description taken from T3:74 rule 3's own wording) |
| 1 third sentence | T3:66 "A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies." | **P3** "A guard row (T-A guard form: R11, R14–R17, R21)" → "A row that denies a tie or an order between items" (general form; enumerated list, "guard", "T-A guard form" removed). **Omitted:** the example "For example, R14 denies an equation between f, d (CB23) and r (CB1), so it crosses" (names a test row, side labels and "crosses"). The `blind/` analyst gloss "(it says that some items are never equated, ordered or linked)" is **dropped** |
| 2 | T3:67 rule 2 | **P4** "placed by the concept table above" → "counted by the concept table (section 4)"; "counts at its own placement" → "counts as its own item"; "counts at the placement of its concept" → "counts as the item of its concept (symbol glossary, section 3)". The inline coordinate mapping is moved to the shared glossary (section 3), not repeated. The C1 tail "counted by rule 2a" removed (see omissions). Relational sentence quoted verbatim |
| 2, "What counts as reading C1" | T3:68 | quoted; **omitted** "The borderlines this convention decides are disclosed in the VP12 re-check section" (meta). "count at their own placement" → "count as their own items" (P4) |
| 2, Def 3.1 clause sentence | T3:68 | verbatim |
| 2, negation sentence | T3:68 "Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68." | scope and phrase examples kept verbatim. **P5 omitted** the tail "as for R79 and R68": R79 is a test row, so naming it discloses a verdict |
| 3 | T3:74 rule 3 | **P6** "so such a row crosses" removed; "a T-B row that states a clause or reading of a hypothesis: T-B rows are assessed by their clause text only" → "a row that states a clause or reading of a hypothesis: such a row is assessed by its clause text only" |
| 4 first sentence | T3:75 rule 4 | "other T2 rows" → "other rows" |
| 4 second sentence | T3:281 ([r1.7 review fix M7], convention (e)) | verbatim; "This decides R71 (…)" omitted |
| 5 | T3:76 rule 5 | **P7** CB4 part omitted ("items in the CB4 open slot (C25, C26, C28) are on no P-c side … touches CB4 … crosses only if …": a side rule, not a reading rule). Kept: "A row reading no placed item (an absent clause, finite parameters, an item outside R4) is "none"" → "A row reading no item (an absent clause, finite parameters, an item outside the frame) reads nothing: give `[]`" ("R4" → "the frame", because "R4" reads as a row id in the packet) |

**A′ rules not included:** rule 2a (spanning rule S / S′ / S″, T3:69–73): it decides on which side C1 counts, not whether a row reads C1; the side is applied afterwards by the analyst. The T3 crossing table and VP12 re-check (row verdicts) are not included.

## Packet B′ — convention B (KC-CONVENTIONS-PROPOSAL.md principle C, line 158, with the K-values of the §2.2 table, lines 169–184)

| packet rule | source | paraphrase / addition |
|---|---|---|
| 1 | KC:158 "The primitive coordinates in the signature of the formula the row **asserts about histories**, after replacing every defined symbol by its definiens (Def 1.10, Def 4.9, Def 3.1 / 3.2; a row reference to a formula is that formula)" | verbatim, with "A row reads" prefixed. **P8** "(φ_x)" attached, from KC:138 notation (φ_x = the formula the row asserts; KC:138's "would activate, prohibits or asks about" is for all principles, not C). **Addition A1:** "A primitive coordinate counts as the item of its concept (symbol glossary, section 3; concept table, section 4)" (bridge from coordinates to C-ids, mirrors A′ rule 2). **K13** value "as T3": "The statement is the full text given for the row" |
| 2 | KC:158 "Bound variables count by their sort: Ref and con bind a ∈ E, Cap binds x ∈ 𝒜." | verbatim. Parenthesis from KC:199 (A3): "Cap ⊆ 𝒜 × {iss, adp} binds an actor just as Ref ⊆ R × (E ∪ W) binds an act". Consequence sentence states **K7** = on (wording from KC:94 / T3:67) and **K6** = actor |
| 3 | KC:158 "A part that asserts no formula about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits φ_x." | verbatim. **K5** = not read: "Absent or negated clauses are not read". **K1** = "object-level parts only" (§2.2): **P9** "guard rows" → "A row that denies a tie or an order between items" (same general form as A′ P3) "reads only the object-level parts of its statement" |
| 4 | §2.2 K3, K10, K10′, K11 = through; K4 = both; K2 = on (definiens) | K3 Adopted (C10), K10 conforms / violation (C21), K10′ Chg (C6, C7), K11 independent (C25), absence (C23), ambiguity (C27): read through. K4: "A Def 3.1 clause that the statement names, and a row id that it cites, are both read through". **K2** class rule worded from T3:74 / KC:89 with "𝐑₀ clauses" → "defining clauses" and "(through the definiens)"; its exemption for clause / reading rows kept, as in A′ rule 3 (P6) |
| 5 | §2.2 K12 = yes; KC:158 T-D / T-C sentences | **K12** value given with T3:68's actor list (the same list as A′). **P10** "**T-D questions (F12):** C reads the formula(s) the question is about, as K does" → "A row that asks a question reads the formula(s) the question is about" ("T-D", "F12", "as K does" removed). **P11** "T-C rows are read as the formula they state" → "A row that states an exclusion is read as the formula it states" (T-C = exclusion rows, KC:159). Closing "gives `[]`" sentence mirrors A′ rule 5 |

- **K8** (C8 + C9) and **K9** (C: not settled; (a) used) are carried by the shared glossary, as before.
- **B′ rules not included:** the §2.4 item 4 parse of "object-level parts" (the K1c parse). It is deliberately left out: whether B′ needs a named parse is the question under test (prereg.md). Principle M and K (KC:157, 159) are not included.
- **Glossary tension (disclosed):** the glossary maps Adopted → C10 and Chg → C7; B′ rule 4 then reads them through. The glossary says it only translates notation; it is unchanged so that both packets share it.

## Length

Section-2 rule text: A′ 404 words / 2205 characters; B′ 393 words / 2217 characters (printed by `build.py`). B′ was balanced by adding the KC:199 typing parenthesis and the K2 exemption sentence. Whole packets: A′ 1841 words, B′ 1782 (`wc -w`; the difference is section 2 and heading only).

## Leak check

`grep -n -i -E 'CB1|CB23|\bX\b|host|cross|E3-REL|Its guard is|non-constraint|guard form|guard'` on both packets: **no hits**. Extended terms `T-A|T-B|placement`: no hits. "side" hits only inside fixed row and concept texts ("B-side", "outside"), identical to `blind/`.
