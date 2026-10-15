"""Verify that every quote cited in KC-GUARD-PURPOSE-ANALYSIS.md occurs verbatim
at the cited file:line, and that T2 contains no crossing / coupling vocabulary.
Run from this folder:  python3 -B quotes.py ..
Computes nothing new about rows; checks text only."""
import hashlib, re, sys, os

R7 = sys.argv[1] if len(sys.argv) > 1 else ".."
LOG = os.path.join(R7, "..", "..", "..", "F-GOVERNANCE-LOG.md")

Q = [
 # id, file, line, verbatim substring
 ("Q1", "T3-CANDIDATE-MODEL.md", 66, "reads **at least one item placed on CB1 and at least one item placed on CB23**"),
 ("Q2", "T3-CANDIDATE-MODEL.md", 66, "A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies"),
 ("Q3", "T3-CANDIDATE-MODEL.md", 68, "Negations (\"with no Cap condition\", \"no Cap or status condition\") are not read, as for R79 and R68"),
 ("Q4", "T3-CANDIDATE-MODEL.md", 259, "\"Crosses\" means that the clause's statement reads items on both CB1 and CB23. It says nothing about the truth of the clause."),
 ("Q5", "T3-CANDIDATE-MODEL.md", 296, "A crossing records which items a clause's text reads, not whether the clause is true or fits the domain"),
 ("Q6", "T3-CANDIDATE-MODEL.md", 299, "A crossing is not a boundary relation (see the relations table)"),
 ("Q7", "T3-CANDIDATE-MODEL.md", 310, "reference (R9) and, under constitution only, F2 (R2, R3)"),
 ("Q8", "T3-CANDIDATE-MODEL.md", 310, "work start read off recording order (R15 guard; R69)"),
 ("Q9", "T3-CANDIDATE-MODEL.md", 358, "a T-A row \"crosses\" if it reads attributes of concepts placed in two or more groupings"),
 ("Q10", "T3-CANDIDATE-MODEL.md", 358, "Guard / non-constraint rows that cross: R14, R15, R16, R17"),
 ("Q11", "T3-CANDIDATE-MODEL.md", 571, "Whether guard rows are cross-grouping constraints in the act's sense is not decided here (E2b-AMENDMENT-1-PROPOSAL choice C-γ)"),
 ("Q12", "T3-CANDIDATE-MODEL.md", 191, "R14, R15, R16, R21 (guard form)"),
 ("Q13", "T2-RULE-CANDIDATES.md", 17, "**model-entailed constraint:**"),
 ("Q14", "T2-RULE-CANDIDATES.md", 43, "Per brief §6.3, anything touching N1–N8 is admissible only as an *open question* or *optional policy*, never as an invariant. A row that names an N-item as **guard** prevents it and asserts nothing about it."),
 ("Q15", "T2-RULE-CANDIDATES.md", 63, "T-A (guard form)"),
 ("Q16", "T2-RULE-CANDIDATES.md", 66, "T-A (non-constraint; guard form)"),
 ("Q17", "T2-RULE-CANDIDATES.md", 66, "Nothing to check: it is the absence of a constraint"),
 ("Q18", "T2-RULE-CANDIDATES.md", 73, "T-A (guard form; modelling choice)"),
 ("Q19", "T2-RULE-CANDIDATES.md", 110, "Its guard is R14 (T-A)"),
 ("Q20", "T2-RULE-CANDIDATES.md", 166, "Guard-form rows R14–R17 are absences of constraints"),
 ("Q21", "T2-RULE-CANDIDATES.md", 166, "Every T-A row states observed vs unobserved coordinates"),
 ("Q22", "T2-RULE-CANDIDATES.md", 172, "T-A rows that name N1, N2, N3, N4, N7 do so as **guards** (R2, R3, R9, R11, R14–R18, R21, R24)"),
 ("Q23", "E3-ARCHITECTURE-PROPOSAL.md", 71, "no domain coupling, runtime dependency, deployment, team or transaction meaning"),
 ("Q24", "E3-ARCHITECTURE-PROPOSAL.md", 102, "means only that the row's statement reads items placed in that module"),
 ("Q25", "E3-ARCHITECTURE-PROPOSAL.md", 111, "the set of T-A rows that E2b §A1.3 states, ratified per reading (R-a), as"),
 ("Q26", "E3-ARCHITECTURE-PROPOSAL.md", 115, "A crossing records which items a row's statement reads."),
 ("Q27", "E3-ARCHITECTURE-PROPOSAL.md", 115, "the rows the two modules share"),
 ("Q28", "E3-ARCHITECTURE-PROPOSAL.md", 116, "R14, R15 and R16 are guard rows, the absence of a constraint"),
 ("Q29", "E3-ARCHITECTURE-PROPOSAL.md", 116, "[D] Checkability"),
 ("Q30", "E3-ARCHITECTURE-PROPOSAL.md", 76, "structural proxies only"),
 ("Q31", "E2b-ADOPTION-TEXT.md", 83, "| R11, R14, R15, R16, R21 | guard / non-constraint"),
 ("Q32", "E2b-ADOPTION-TEXT.md", 320, "hosted on both CB1 and CB23: R2, R3, R9, R14, R15, R16, R21, R22"),
 ("Q33", "E2b-ADOPTION-TEXT.md", 324, "**Whether guard rows that cross groupings are cross-grouping constraints is returned for analysis**"),
 ("Q34", "E2b-ADOPTION-TEXT.md", 584, "The ratified lists are therefore a ratified output of an unratified method"),
 ("Q35", "E2b-ADOPTION-TEXT.md", 634, "**the K-c criterion or rule 2a revised**"),
 ("Q36", "E2b-ADOPTION-TEXT.md", 496, "the P-c \"Relations between boundaries\" table and the 83-row K-c crossing table"),
 ("Q37", "RESEARCH-AUDIT-1.md", 136, "low coupling (few statements read both sides)"),
 ("Q38", "RESEARCH-AUDIT-1.md", 297, "They say nothing about domain coupling, ownership or runtime dependency."),
 ("Q39", "VP5-VP10-VP14-DECISION-PACKAGE.md", 202, "| 4 | coupling [I] |"),
 ("Q40", "VP5-VP10-VP14-DECISION-PACKAGE.md", 202, "fewest clause crossings among the cuts"),
 ("Q41", "KC-CONVENTIONS-PROPOSAL.md", 215, "If a crossing measures **coupling of constraint content**"),
 ("Q42", "KC-CONVENTIONS-PROPOSAL.md", 158, "A part that asserts no formula about histories contributes nothing"),
 ("Q43", "KC-MKC-REASSESSMENT.md", 172, "a row crosses only if a constraint it carries couples the sides"),
 ("Q44", "KC-MKC-REASSESSMENT.md", 171, "a T-A row that delimits a tie between items on both sides is hosted on both"),
 ("Q45", "KC-CONVENTIONS-PROPOSAL.md", 445, "K1 [a/b/c; if c: guard parse K1c / SH / R15ref / R15ref-R14]"),
 ("Q48", "E3-ARCHITECTURE-PROPOSAL.md", 62, "In particular it asserts nothing about crossings or co-reading"),
 ("Q49", "T3-CANDIDATE-MODEL.md", 310, "keeper typing (R8): a CB23 ↔ CB1 relation under the spanning reading S [E]"),
 ("Q50", "T3-CANDIDATE-MODEL.md", 310, "(other S-only T-A crossings: R5, R6, R10, R19; crossing table); identifiers (R22); recording order of traces (C24)"),
 ("Q51", "KC-CONVENTIONS-PROPOSAL.md", 69, "The guard-row question (K1) is in substance the C-γ question under every option."),
 ("Q52", "KC-MKC-REASSESSMENT.md", 284, "guard role taken from the T2 \"guard form\" label"),
 ("Q53", "KC-MKC-REASSESSMENT.md", 58, "K with a textual guard criterion"),
 ("Q54", "T2-RULE-CANDIDATES.md", 73, "The constraint is on interpretation, so it is a modelling rule, not a record check"),
 ("Q55", "E3-ARCHITECTURE-PROPOSAL.md", 110, "Also not adopted as constraints: the P-c \"Relations between boundaries\" table and the 83-row K-c crossing table"),
 ("Q56", "E2b-ADOPTION-TEXT.md", 324, "Labelled as cross-grouping constraints, **until the returned question is analysed**: only the ten rows enumerated in F-LOG-0269"),
 ("Q57", "KC-1-DECISION-MATRIX.md", 88, "its encoding differs at R11 and its S rank differs"),
 ("Q58", "E3-ARCHITECTURE-PROPOSAL.md", 115, "[O] **Whether E3-REL is a \"relation\" at all.**"),
 ("Q61", "T2-RULE-CANDIDATES.md", 183, "6 guard / non-constraint form (R11, R14–R17, R21)"),
 ("Q63", "KC-CONVENTIONS-PROPOSAL.md", 171, "| K1 guard rows | read the denied items | read | object-level parts only | read the prohibited formula |"),
]
LOGQ = [
 ("Q46", 4153, "No 9│14 cut has strictly fewer crossings under FZ and its variants"),
 ("Q47", 4151, "if guard rows read nothing, R11, R14–R17 and R21 move, and **E3-REL drops from 13/8 to 9/4.**"),
 ("Q59", 3397, "with the T-A rows that cross groupings as cross-grouping constraints"),
 ("Q60", 3399, "Guard rows R14–R17 and R21 also cross groupings"),
 ("Q62", 3399, "the listed crossing rows predate the T3 r1.6 notes"),
 ("Q64", 4188, "Output preservation is offered as a candidate criterion only."),
]

def lines(p):
    with open(p, encoding="utf-8") as f:
        return f.read().split("\n")

bad = 0
files = sorted({q[1] for q in Q})
for fn in files:
    with open(os.path.join(R7, fn), "rb") as f:
        print(f"{fn}: sha256 {hashlib.sha256(f.read()).hexdigest()[:16]}")
for qid, fn, ln, s in Q:
    L = lines(os.path.join(R7, fn))
    ok = s in L[ln - 1]
    bad += not ok
    print(f"{qid} {fn}:{ln} {'OK' if ok else 'MISSING'}")
L = lines(LOG)
with open(LOG, "rb") as f:
    print(f"F-GOVERNANCE-LOG.md: sha256 {hashlib.sha256(f.read()).hexdigest()[:16]}")
for qid, ln, s in LOGQ:
    ok = s in L[ln - 1]
    bad += not ok
    print(f"{qid} F-GOVERNANCE-LOG.md:{ln} {'OK' if ok else 'MISSING'}")

# T2 vocabulary check: does T2 ever speak of crossing, cuts, coupling or hosting?
T2 = lines(os.path.join(R7, "T2-RULE-CANDIDATES.md"))
pat = re.compile(r"\b(cross(es|ing|ed)?|coupl\w*|host\w*|cut)\b", re.I)
hits = [(i + 1, l[max(0, m.start() - 25):m.end() + 25]) for i, l in enumerate(T2) for m in pat.finditer(l)]
print("T2 crossing/coupling/hosting/cut tokens (line, context):")
for h in hits:
    print("  ", h)
print("RESULT:", "all quotes verified" if bad == 0 else f"{bad} quote(s) missing")
