# KC-1 blind packet: source trace (NOT for encoders)

Base folder: `docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/R7/` (read-only; nothing edited).
Build scripts: `../kc1-build/extract.py`, `../kc1-build/build.py` (run with `python3 -B -I`); extracted data in `../kc1-build/data.json`.

## Row texts (T2-RULE-CANDIDATES.md, column 2 "statement", line)

| row | role in test | line |
|---|---|---|
| R11 | test row | T2:63 |
| R14 | test row | T2:66 |
| R15 | test row | T2:67 |
| R16 | test row | T2:68 |
| R17 | test row | T2:69 |
| R21 | test row | T2:73 |
| R9 | control (constraint row) | T2:61 |
| R79 | control (T-B slot) | T2:96 |
| R80 | control (T-B slot) | T2:97 |
| R1 | control (plain T-A) | T2:53 |
| R6 | control (plain T-A) | T2:58 |

Cleaning applied to the statement cell only: `**` bold removed; revision markers `[r1]` removed (R11, R21, R79, R80); whitespace normalized. Tier, pairing, rests-on, evidence, N-list and coordinates columns are not included. Row/axiom references inside the statement (e.g. "(R3)", "(R1)", "(A5)", "F1", "F2", "Def 1.1") are kept verbatim.

Shuffle: initial list `[R1, R6, R9, R11, R14, R15, R16, R17, R21, R79, R80]`, `random.seed(20261017); random.shuffle(...)` → **R6, R16, R80, R21, R11, R79, R15, R9, R17, R1, R14** (same order in both packets).

## Concept items (T1-CONCEPT-CANDIDATES.md, column 2 "candidate concept", lines 46–73)

C1 = T1:46 … C28 = T1:73 (one line per C-id, consecutive). Column 2 only; bold removed; "this row" → "this item". No X-rows, status, N-list or placement included. No C-ids beyond C28 exist in T1/T3/KC.

## Convention texts

- **Interpretation A:** T3-CANDIDATE-MODEL.md:66 (rule 1: "including the clauses it names"; "judged by the items whose tie or order it denies"). The R14 example and CB labels were removed. The generic "denies a tie or an order … never equated, ordered or linked" gloss follows the caller's brief.
- **Interpretation B:** KC-CONVENTIONS-PROPOSAL.md:158 (principle C: "the formula the row asserts about histories"; "A part that asserts no formula about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits φ_x"). **Deliberate omission:** C's clause "after replacing every defined symbol by its definiens" and its bound-variable sort rule ("Ref and con bind a ∈ E, Cap binds x ∈ 𝒜") are NOT included, because the caller's shared rule (no unfolding of definitions) overrides it.
- **Shared rules:** level of naming / own placement: T3:67 (rule 2) and T3:68 ([r1.7] "A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count at their own placement and are not read through their definitions"); KC:90 (K3 (a)). Row references not followed: T3:75 (rule 4); KC:91 (K4). Extension to axiom/definition references: analyst wording, following the brief.
- **Symbol glossary:** T3:67 (rule 2 coordinate mapping, placement labels removed); generic act / E → C8 + C9 from KC:95 (K8 (a), FZ F4); v → C5, τ → C3, V / P → C4, prep → C10 from KC:96 (K9 (a), A.0); σ → C6 and Chg → C7 split as own items (T3 maps "σ, Chg → C6, C7" jointly); "T → no item" is analyst wording.
- **Not included (deliberately):** T3:67 relational clause ("Ref and con … also reads the acts or work events it relates them to", KC K7); T3:68 "Negations … are not read"; the class rule (T3 rule 3, KC K2); the conforms read-through (KC K10). These are not in the caller's shared-rule spec.

## Leak check

`grep -i -E 'CB1|CB23|\bX\b|host|cross|E3-REL|guard form|guard|non-constraint|Its guard is'` on both packets: no hits.
