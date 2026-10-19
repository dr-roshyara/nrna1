# PROPOSED / NOT RATIFIED; materials only; not executed.
# KC-1 external blind replication: packet builder. Builds every arm's packet from ONE template, so that
# task text, general rules 1-5, rule-6 skeleton, glossary, concept list and rows are byte-identical across arms;
# only the rule-6 lead sentence and the rule-6 treatment column differ (one-factor design, PROTOCOL.md section 4).
# Run from external-protocol/:   python3 -B tools/build_packets.py tools/source_data.json .
# Writes packet_T3.md, packet_SH.md, packet_PARSE.md, packet_PARSE_B.md (optional arm), packet_GEN.md (optional arm).
import json, sys, re, random

d = json.load(open(sys.argv[1], encoding='utf-8'))
out = sys.argv[2]
HEADER = "PROPOSED / NOT RATIFIED; materials only; not executed\n\n"
BEGIN = "<!-- BEGIN PACKET: send to the encoder exactly the text between this line and the END line -->\n"
END = "<!-- END PACKET -->\n"

ROW_SEED = 20261017          # row order (blind2 used 20261018)
CODE_SEED = 20261019         # neutral packet codes
order = list(d['initial_order']); random.seed(ROW_SEED); random.shuffle(order)
ARMS = ['T3', 'SH', 'PARSE', 'PARSE_B', 'GEN']
letters = list('DFHJKMNPQRSTVWZ')   # no X (a verdict token in the scoring); random.seed(CODE_SEED); random.shuffle(letters)
CODE = dict(zip(ARMS, letters))
LISTED = "R11, R14, R15, R16, R17 and R21"

TASK = """## 1. Task

You are an encoder. Below is a list of concept items (C1 to C28) and a list of numbered statements ("rows"). For each row, list the concept items the row **reads**, as defined by the convention in section 2, using the symbol glossary in section 3.

- **Encode from the text and the convention only.** Do not search, browse, use tools or read any other file, and do not use outside knowledge of where these texts come from. Do not discuss the task with anyone.
- Work row by row. Apply the convention literally, even where it seems to give an odd result. There is no preferred answer; the study measures how the convention text is read, not whether you agree with it.
- If a row reads no concept item, give an empty list.
- The texts of the definitions, axioms, clauses and rows that a statement or a rule cites (for example "Def 3.1", "A5", "F1", "F2", "R1", "R3", "D-chg", "H-B") are **not supplied** in this packet. Where the convention tells you to read such a text, read only what this packet supplies about it (the statement's own words, the glossary and the concept list), and say so in your reason for that row.

**Required output, in this order:**

1. **A single JSON object** mapping each row id to a list of C-ids sorted by number, or `[]`. Include every row id of section 5 exactly once and nothing else. Use only the forms "C1" to "C28"; do not add question marks, alternatives or comments inside the JSON. Example of the format only (not an answer):

```json
{"Rxx": ["C2", "C10"], "Ryy": []}
```

2. **One reason line for every row**, in the order of section 5: the row id, the rule(s) of section 2 you applied, and why. Mark with "HARD" any row where you found the decision hard.
3. **For each row you treated under rule 6, and only for those:** list the parts you found in its statement, each with the letter (a) to (g) of the rule-6 table you assigned it to, and whether you read it. If a part fits no letter, or more than one, say so.
"""

GEN_RULES = """1. **Statement.** What a row reads is taken from its statement (the full text given for the row, including the clauses it names). A row that states a clause or reading of a hypothesis is judged by its clause text only (see rule 3). {R1TAIL}
2. **Items, at the level named.** Items are counted by the concept table (section 4), at the level at which the statement names them. A named concept counts as its own item. A spelled-out coordinate counts as the item of its concept (symbol glossary, section 3). Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to.
   - **What counts as "reading C1".** A statement reads C1 where its text names an actor: 𝒜, α(a), α(b), α(w) / α_W, k(ρ) ∈ 𝒜, actor identity, a keeper, norm-maker or executor, or a role or office.
   - Per rule 1, the text of a Def 3.1 clause that the statement names counts as read (so a row naming D-chg reads D-chg's "(α(a), iss) ∈ Cap").
   - A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count as their own items and are not read through their definitions.
   - Negations ("with no Cap condition", "no Cap or status condition") are not read.
3. **Class rule.** A statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' 𝐑₀ clauses (Def 3.1). These include the record clauses (D-rec, B-recW). The rule does **not** apply to a row that states a clause or reading of a hypothesis: such a row is assessed by its clause text only.
4. **Row references.** A row that refers to other rows (by id or as a set) is assessed by the items its own statement reads, not through those rows. Where a statement names a Def 3.1 clause and also cites a row id, the clause name takes priority: the clause is read through (rule 1), and the row reference is not (rule 4).
5. **Nothing read.** A row reading no item (an absent clause, finite parameters, an item outside the frame) reads nothing: give `[]`.
"""

PARTS = [
    ('a', 'the tie or order between items that the row denies'),
    ('b', 'a statement of the type, set or attribute of a symbol (for example "x : A → B is total", "x ∈ S", "every x has an attribute y(x) in S")'),
    ('c', 'a report that the frame lacks an axiom, a requirement or a constraint, or a remark on how a missing item is to be understood'),
    ('d', 'a contrast that only says what something is not ("…, not Y")'),
    ('e', 'a statement that something may hold in some histories'),
    ('f', 'an exception ("except …", "beyond …") that refers to another axiom or row'),
    ('g', 'a restatement of another axiom or row cited by name or id'),
]
R_ = 'read, for the items its own words name'
NOT = 'not read'
REF = 'read, for the items its own words name; the cited axiom or row is not read through (rule 4)'
CELLS = {
    'T3':      ['read (the denied items)', 'read', R_, R_, R_, REF, REF],
    'SH':      [NOT] * 7,
    'PARSE':   [NOT + ' (a denial asserts no formula)', 'read', NOT, NOT, NOT, NOT, NOT],
    'PARSE_B': [NOT + ' (a denial asserts no formula)', 'read', NOT, NOT, R_, REF, NOT],
}
CELLS['GEN'] = CELLS['T3']
LEAD = {
    'T3': 'A listed row is judged by the items whose tie or order it denies. The negation sentence of rule 2 does not remove those items, and the other parts of its statement are read as in any other row.',
    'SH': 'A listed row reads nothing. It contributes no items, whatever its statement names, denies, permits or excepts, and whatever clauses or rows it cites.',
    'PARSE': 'A listed row reads only the object-level parts of its statement, that is, the parts that themselves assert a formula about histories. A denial, a report or a contrast asserts no such formula.',
}
LEAD['PARSE_B'] = LEAD['PARSE']
LEAD['GEN'] = 'Such a row is judged by the items whose tie or order it denies. The negation sentence of rule 2 does not remove those items, and the other parts of its statement are read as in any other row.'

def rule6(arm):
    if arm == 'GEN':
        head = ('6. **Rows that deny a tie or an order.** Rule 6 applies to every row that denies a tie or an order between items; '
                'every other row is read by rules 1–5 alone. ')
        what = 'such a row'
    else:
        head = (f'6. **Listed rows.** The listed rows are {LISTED}. Rule 6 applies to them only; '
                'every other row is read by rules 1–5 alone. ')
        what = 'a listed row'
    s = head + LEAD[arm] + (f' Treat the statement of {what} part by part, as the table below says. In a part that is read, '
                            'count items by rule 2 (a clause it names by rule 1, a row id it cites by rule 4). '
                            f'If no part of {what} is read, give `[]`.\n\n')
    s += f'   | part of {what}\'s statement | treatment |\n   |---|---|\n'
    for (k, txt), cell in zip(PARTS, CELLS[arm]):
        s += f'   | ({k}) {txt} | {cell} |\n'
    return s

GLOSS = """## 3. Symbol glossary

Symbol → concept item. This only translates notation and says nothing about which items any row reads. Every entry is at the level named (rule 2): a derived predicate listed here (Adopted, Chg, conforms / violates) maps to its own item and is not read through its definition.

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
    for r in order:
        s += f"**{r}.** {d['texts'][r]}\n\n"
    return s

FILES = {'T3': 'packet_T3.md', 'SH': 'packet_SH.md', 'PARSE': 'packet_PARSE.md', 'PARSE_B': 'packet_PARSE_B.md', 'GEN': 'packet_GEN.md'}
for arm in ARMS:
    r1tail = ('Rows that deny a tie or an order between items are also subject to rule 6.' if arm == 'GEN'
              else 'The listed rows are also subject to rule 6.')
    conv = "## 2. Convention\n\n" + GEN_RULES.replace('{R1TAIL}', r1tail) + rule6(arm)
    body = (f"# Encoding packet {CODE[arm]}\n\n" + TASK + "\n" + conv + "\n" + GLOSS + "\n" + concepts() + "\n" + rows()).rstrip('\n') + '\n'
    open(f"{out}/{FILES[arm]}", 'w', encoding='utf-8').write(HEADER + BEGIN + body + END)
    wc = lambda t: len(re.findall(r'\S+', t))
    print(f"{FILES[arm]:18s} code {CODE[arm]}  rule-6 words {wc(rule6(arm)):4d}  section-2 words {wc(conv):4d}  packet words {wc(body)}")
print('row order (seed', ROW_SEED, '):', ', '.join(order))
print('codes (seed', CODE_SEED, '):', CODE)
