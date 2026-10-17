# KC-1 blind2 packet builder (normalized specificity). Run: python3 -B -I build.py data.json <outdir>
# Each packet carries its convention's complete operational rule set (sources and paraphrases: ../key_sources.md).
import json, sys, re
d = json.load(open(sys.argv[1], encoding='utf-8'))
out = sys.argv[2]

INSTR = """## 1. Task

You are an encoder. Below is a list of concept items (C1 to C28) and a list of numbered statements ("rows"). For each row, list the concept items the row **reads**, as defined by the convention in section 2, using the symbol glossary in section 3.

- **Encode from the text and convention only; do not search or read any other file.** Do not use outside knowledge of where these texts come from.
- Work row by row. Apply the convention literally, even where it seems to give an odd result.
- If a row reads no concept item, give an empty list.
- The texts of the definitions, axioms, clauses and rows that a statement or a rule cites (for example "Def 3.1", "A5", "F1", "R3", "D-chg", "H-B") are **not supplied** in this packet. Where the convention tells you to read such a text, read only what this packet supplies about it (the statement's own words, the glossary and the concept list), and say so in your reason for that row.

**Required output format:** a single JSON object mapping each row id to a sorted list of C-ids (sorted by number), or `[]`. Include every row id below exactly once. Example of the format only (not an answer):

```json
{"Rxx": ["C2", "C10"], "Ryy": []}
```

Return the JSON object first. Then, after it, give **one short line for every row** (all rows, in the order of section 5): the row id and the reason for your list, naming the rule of section 2 you applied. Mark with "HARD" any row where you found the decision hard.
"""

CONV_A = """## 2. Convention (Interpretation A)

1. **Statement and denials.** What a row reads is taken from its statement (the full text given for the row, including the clauses it names). A row that states a clause or reading of a hypothesis is judged by its clause text only (see rule 3). A row that denies a tie or an order between items is judged by the items whose tie or order it denies.
2. **Items, at the level named.** Items are counted by the concept table (section 4), at the level at which the statement names them. A named concept counts as its own item. A spelled-out coordinate counts as the item of its concept (symbol glossary, section 3). Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to.
   - **What counts as "reading C1".** A statement reads C1 where its text names an actor: 𝒜, α(a), α(b), α(w) / α_W, k(ρ) ∈ 𝒜, actor identity, a keeper, norm-maker or executor, or a role or office.
   - Per rule 1, the text of a Def 3.1 clause that the statement names counts as read (so a row naming D-chg reads D-chg's "(α(a), iss) ∈ Cap").
   - A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count as their own items and are not read through their definitions.
   - Negations ("with no Cap condition", "no Cap or status condition") are not read.
3. **Class rule.** A statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' 𝐑₀ clauses (Def 3.1). These include the record clauses (D-rec, B-recW). The rule does **not** apply to a row that states a clause or reading of a hypothesis: such a row is assessed by its clause text only.
4. **Row references.** A row that refers to other rows (by id or as a set) is assessed by the items its own statement reads, not through those rows. Where a statement names a Def 3.1 clause and also cites a row id, the clause name takes priority: the clause is read through (rule 1), and the row reference is not (rule 4).
5. **Nothing read.** A row reading no item (an absent clause, finite parameters, an item outside the frame) reads nothing: give `[]`.
"""

CONV_B = """## 2. Convention (Interpretation B)

1. **Asserted formula, unfolded.** A row reads the primitive coordinates in the signature of the formula the row **asserts about histories** (φ_x), after replacing every defined symbol by its definiens (Def 1.10, Def 4.9, Def 3.1 / 3.2; a row reference to a formula is that formula). A primitive coordinate counts as the item of its concept (symbol glossary, section 3; concept table, section 4). The statement is the full text given for the row.
2. **Bound variables.** Bound variables count by their sort: Ref and con bind a ∈ E, Cap binds x ∈ 𝒜 (Cap ⊆ 𝒜 × {iss, adp} binds an actor just as Ref ⊆ R × (E ∪ W) binds an act). So Ref and con, which relate records to acts or work events, also read those acts or work events; Cap named alone also reads the actor (C1).
3. **Nothing asserted, nothing read.** A part that asserts no formula about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits φ_x. Absent or negated clauses are not read. A row that denies a tie or an order between items reads only the object-level parts of its statement.
4. **Definitions and references read through.** A derived predicate named as a concept is read through its definition: Adopted (C10), conforms / violation (C21), Chg (C6, C7), independent (C25), absence (C23), ambiguity (C27). A Def 3.1 clause that the statement names, and a row id that it cites, are both read through. Class rule (through the definiens): a statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' defining clauses (Def 3.1). The rule does **not** apply to a row that states a clause or reading of a hypothesis: such a row is assessed by its clause text only.
5. **Actors, questions, exclusions.** A statement reads C1 where its text names an actor: 𝒜, α(a), α(b), α(w) / α_W, k(ρ) ∈ 𝒜, actor identity, a keeper, norm-maker or executor, or a role or office. A row that asks a question reads the formula(s) the question is about. A row that states an exclusion is read as the formula it states. A row whose asserted formula reads no item gives `[]`.
"""

GLOSS = """## 3. Symbol glossary

Symbol → concept item. This only translates notation and says nothing about which items any row reads.

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

for name, title, conv in (("packet_A.md", "Encoding packet A", CONV_A), ("packet_B.md", "Encoding packet B", CONV_B)):
    body = f"# {title}\n\n" + INSTR + "\n" + conv + "\n" + GLOSS + "\n" + concepts() + "\n" + rows()
    open(f"{out}/{name}", 'w', encoding='utf-8').write(body)
    w = len(re.findall(r'\S+', conv)); print(name, 'convention words', w, 'chars', len(conv))
