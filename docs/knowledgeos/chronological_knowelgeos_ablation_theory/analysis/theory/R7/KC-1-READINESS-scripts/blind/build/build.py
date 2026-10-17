import json, sys
d = json.load(open(sys.argv[1], encoding='utf-8'))
out = sys.argv[2]

INSTR = """## 1. Task

You are an encoder. Below is a list of concept items (C1 to C28) and a list of numbered statements ("rows"). For each row, list the concept items the row **reads**, as defined by the convention in section 2 and the shared encoding rules in section 3.

- **Encode from the text and convention only; do not search or read any other file.** Do not use outside knowledge of where these texts come from.
- Work row by row. Apply the convention literally, even where it seems to give an odd result.
- If a row reads no concept item, give an empty list.

**Required output format:** a single JSON object mapping each row id to a sorted list of C-ids (sorted by number), or `[]`. Include every row id below exactly once. Example of the format only (not an answer):

```json
{"Rxx": ["C2", "C10"], "Ryy": []}
```

Return the JSON object first. You may then add, after it, one short line per row where you found the decision hard (optional).
"""

CONV_A = """## 2. Convention (Interpretation A)

A row reads the concept items its statement text names or depends on, including the clauses it names.

A row that **denies** a tie or an order between items (it says that some items are never equated, ordered or linked) is judged by the items whose tie or order it denies: it reads the items of the tie or order it denies.
"""

CONV_B = """## 2. Convention (Interpretation B)

A row reads only the concept items in what the row **asserts** about histories (the formula the statement asserts).

A part of the statement that asserts nothing about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits what the row asserts. Hence a denial, a non-entailment, or a statement that the frame requires nothing ("nothing to check", "asserts nothing") contributes no items.
"""

SHARED = """## 3. Shared encoding rules

1. **Level of naming.** Items are counted at the level at which the statement names them. A named concept counts as its own item. A spelled-out symbol counts as the item of its concept (glossary below).
2. **Definitions are not unfolded.** A derived term named as a concept (for example "Adopted", or a capability named alone) counts as its own item and is not read through its definition. A definition is unfolded only where the statement text itself uses the defined term's components.
3. **References are not followed.** A row that refers to other rows (by id, such as "R3", or as a set) is assessed by the items its own statement reads, not through those rows. The same holds for references to axioms, definitions, lemmas or remarks (such as "A5", "Def 1.1", "Lem 3.6"): only what the statement's own words say is encoded.
4. If a symbol or term has no glossary entry, decide from the concept list alone whether the text names a concept item.

**Symbol glossary** (symbol → concept item; this only translates notation and says nothing about which items any row reads):

| symbol / word in the text | item |
|---|---|
| actor set 𝒜, actor attribution α(a), α(b), α(w) / α_W, actor identity, keeper / norm-maker / executor as persons | C1 |
| Cap (capability) | C2 |
| target τ(a) of an act; target set 𝒯 | C3 |
| value set V; permitting subset P | C4 |
| value v(a) of an issue act | C5 |
| σ (norm state) | C6 |
| Chg (change points) | C7 |
| issue act; E^iss | C8 |
| adoption act; E^adp; obj | C9 |
| prep (prepared flag); Adopted | C10 |
| an act in general; the act set E; a ∈ E | C8 and C9 |
| d (decision time) | C11 |
| f (entry into force) | C12 |
| record; record set R; record identity; the observation O | C13 |
| r (recording time) | C14 |
| c (record text); text set Σ | C15 |
| k (keeper of a record) | C16 |
| Ref (reference) | C17 |
| con (constitution) / mode | C18 |
| work event; work-event set W; τ(w) | C19 |
| t(w) (work start time) | C20 |
| conforms / violates | C21 |
| T (the time set) | no item |
"""

def concepts():
    s = "## 4. Concept items\n\n| id | concept item (working label and one-line definition) |\n|---|---|\n"
    for cid, txt in d['concepts']:
        s += f"| {cid} | {txt.replace('this row', 'this item')} |\n"
    return s

def rows():
    s = "## 5. Rows to encode\n\nThe rows are in a fixed order. Encode each one from its text alone.\n\n"
    for r in d['order']:
        s += f"**{r}.** {d['texts'][r]}\n\n"
    return s

for name, title, conv in (('packet_A.md', 'Encoding packet A', CONV_A), ('packet_B.md', 'Encoding packet B', CONV_B)):
    body = f"# {title}\n\n" + INSTR + "\n" + conv + "\n" + SHARED + "\n" + concepts() + "\n" + rows()
    open(f"{out}/{name}", 'w', encoding='utf-8').write(body)
