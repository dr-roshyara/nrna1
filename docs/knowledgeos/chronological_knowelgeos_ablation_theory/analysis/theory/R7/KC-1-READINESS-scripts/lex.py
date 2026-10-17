# KC-1-READINESS-AUDIT r0: lex specialis checks for T3 rule 1 (guard clause) vs rule 2's negation sentence / rule 5.
# Text checks (verbatim at file:line), row-selector check (T2 tier "guard form"), strict-reading conflict witnesses,
# rule-domain overlap, and parse robustness of guard-row verdicts under K1 (a) versus K1 (b)/(c).
# Analysis only; decides nothing. Run from this folder:  python3 -B lex.py ..
import sys, os, io, contextlib, re, json
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.path.insert(0, os.path.join(R7, 'KC-MKC-REASSESSMENT-scripts'))
sys.argv = ['mkc.py', R7]
with contextlib.redirect_stdout(io.StringIO()):
    import mkc
kc = mkc.kc; B = mkc.B; FZ = B.FZ; P = mkc.P; E = {8, 9}
GUARD = ['R11', 'R14', 'R15', 'R16', 'R17', 'R21']
T3 = open(os.path.join(R7, 'T3-CANDIDATE-MODEL.md'), encoding='utf-8').read().splitlines()
T2 = open(os.path.join(R7, 'T2-RULE-CANDIDATES.md'), encoding='utf-8').read().splitlines()

print('== 1. verbatim text checks ==')
Q = [('T3', 66, 'A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies'),
     ('T3', 66, 'A **T-B row** is judged by its clause text only'),
     ('T3', 68, '**[r1.7] What counts as "reading C1".**'),
     ('T3', 68, 'Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68'),
     ('T3', 76, 'A row reading no placed item (an absent clause, finite parameters, an item outside R4) is "none"'),
     ('T3', 75, 'is assessed by the items its own statement reads, not through those rows'),
     ('T3', 281, 'where a statement names a Def 3.1 clause and also cites a row id, the clause name takes priority')]
for f, ln, s in Q:
    src = T3 if f == 'T3' else T2
    ok = s in src[ln - 1]
    if not ok:
        hits = [i + 1 for i, l in enumerate(src) if s in l]
        print(f'  {f}:{ln} MISSING (found at {hits})')
    else:
        print(f'  {f}:{ln} OK  "{s[:70]}..."')
# where does the negation sentence sit? (scope of the bullet)
neg_line = [i + 1 for i, l in enumerate(T3) if 'Negations ("with no Cap condition"' in l]
print('  negation sentence lines:', neg_line, '| same line opens with the bullet "What counts as reading C1":',
      all('What counts as "reading C1"' in T3[i - 1] for i in neg_line))
print('  other occurrences in T3 of "are not read" / "not read":',
      [i + 1 for i, l in enumerate(T3) if re.search(r'\bare not read\b', l)])
print('  occurrences in T3 of "lex specialis" / "takes priority" / "prevails":',
      [i + 1 for i, l in enumerate(T3) if re.search(r'lex specialis|takes priority|prevails|overrides', l)])

print('\n== 2. row selector: T2 tier column containing "guard form" ==')
sel = []
for l in T2:
    m = re.match(r'^\| (R\d+) \|', l)
    if not m: continue
    cells = [c.strip() for c in l.split('|')]
    if len(cells) > 3 and 'guard form' in cells[3]: sel.append(m.group(1))
print('  rows:', sel, '| equals T3 rule-1 list:', sel == GUARD)
print('  T2:183 counts line:', [l.strip()[:120] for l in T2 if 'guard / non-constraint form' in l])

print('\n== 3. strict reading (both rules as exceptionless universals) -> conflict witnesses ==')
print('  For a guard row x, D(x) = items read under rule 1 (FZ) that are absent when denial parts are not read (K1c/SH):')
for r in GUARD:
    d1 = FZ[r][0] - kc.GUARD_C[r][0]; d2 = FZ[r][0]
    print(f'  {r}: FZ {sorted(FZ[r][0])}  K1c {sorted(kc.GUARD_C[r][0])}  D(K1c)={sorted(d1)}  D(SH)={sorted(d2)}  -> conflict: {bool(d1)}')
print('  strict reading: a rho(x) must both contain D(x) (rule 1) and exclude it (negation rule) -> no model for every x with D(x) nonempty')

print('\n== 4. rule domains under lex specialis (specific: closed list in rule 1; general: negation / absent-clause handling) ==')
def flag(r): return FZ[r][1]
print('  guard rows with an actor flag in FZ (overlap with a C1-scoped negation sentence):', [r for r in GUARD if flag(r)])
print('  guard list intersect T-B:', sorted(set(GUARD) & set(B.TB)), '| R79, R80 in guard list:', [r in GUARD for r in ('R79', 'R80')])
print('  rows other than the guard list that FZ reads through a denial: none listed by T3; R79 contrast and R80 absent clause read nothing extra:',
      FZ['R79'], FZ['R80'])

print('\n== 5. parse robustness of guard-row verdicts ==')
BL = os.path.join(os.path.dirname(os.path.abspath(__file__)), 'blind', 'results')
def jl(n):
    d = json.load(open(os.path.join(BL, n + '.json')))
    return {r: {int(c[1:]) for c in v} - {1} for r, v in d.items()}
BA1, BA2, BB1, BB2 = jl('A1'), jl('A2'), jl('B1'), jl('B2')
# K1 (a) parses of "the items whose tie or order it denies": FZ; M's R15 (K7 off); MKC adhoc K-T A-i variants;
# a minimal-tie parse [E] (only the endpoints of the denied tie); the two blind A encodings.
# r1 (M9): six realisations in all (four analyst parses + two blind). They are NOT independent: all start from the same
# row texts, and the minimal-tie parse is built from the tie endpoints that section 5 then uses to explain robustness.
A_PARSES = {
    'FZ (T3)': {r: FZ[r][0] for r in GUARD},
    "M (R15 without E)": {r: P['M'][r][0] for r in GUARD},
    'K-T A-i variants': {'R11': {3, 4, 6} | E, 'R14': FZ['R14'][0], 'R15': {11, 12, 14, 20, 22}, 'R16': {13, 17, 19} | E, 'R17': FZ['R17'][0], 'R21': {6, 12, 15}},
    'minimal tie [E]': {'R11': {6} | E, 'R14': {11, 12, 14}, 'R15': {11, 12, 14}, 'R16': {13, 17, 19} | E, 'R17': {5, 6, 19} | E, 'R21': {6, 12, 15}},
    'blind A1': {r: BA1[r] for r in GUARD}, 'blind A2': {r: BA2[r] for r in GUARD},
}
BC_PARSES = {
    'SH (b)': {r: set() for r in GUARD}, 'K1c': {r: kc.GUARD_C[r][0] for r in GUARD},
    'R15ref': {r: P['C-R15ref'][r][0] for r in GUARD}, 'R15ref-R14': {r: P['C-R15ref-R14'][r][0] for r in GUARD},
    'C-Sigma': {r: P['C-Sigma [E]'][r][0] for r in GUARD},
    'blind B1': {r: BB1[r] for r in GUARD}, 'blind B2': {r: BB2[r] for r in GUARD},
}
def verdicts(parses):
    tab = {}
    for nm, g in parses.items():
        tab[nm] = {r: B.side({'Q': (g[r], None)}, 'Q', 'S1') for r in GUARD}
    return tab
for lab, parses in (('K1 (a)', A_PARSES), ('K1 (b)/(c)', BC_PARSES)):
    tab = verdicts(parses)
    print(f'  {lab}: verdicts (S\'; guard rows carry no actor flag, so S = S\' = S\'\')')
    for nm, v in tab.items(): print(f'     {nm:22s}', ' '.join(f'{r}:{v[r]:4s}' for r in GUARD))
    dist = {r: sorted({v[r] for v in tab.values()}) for r in GUARD}
    print(f'     distinct verdicts per row: {dist}  | rows with >1 verdict: {[r for r in GUARD if len(dist[r]) > 1]}')
    e3 = {nm: sorted(r for r in GUARD if v[r] == 'X') for nm, v in tab.items()}
    print(f'     guard rows in E3-REL by parse: {e3}')
print('\n  (a) by construction: a guard row crosses iff its denied tie has an endpoint on each side. Endpoints per T2 label [E]:')
TIE = {'R11': ('sigma C6', 'acts E C8/C9'), 'R14': ('f C12, d C11', 'r C14'), 'R15': ('d C11, f C12', 'r C14'),
       'R16': ('acts/work C8/C9/C19', 'records/Ref C13/C17'), 'R17': ('norm value C4/C5/C6', 'acts/work C8/C9/C19'), 'R21': ('sigma C6, f C12', 'text c C15')}
for r, (x, y) in TIE.items(): print(f'     {r}: {x}  <->  {y}')

print('\n== 6. E3-REL per K1 value vs the T-A rows of T3\'s (unadopted) relations-table "held" column (T3:310) ==')
line = T3[309]
# [E] hand-transcribed from T3:310 (r1, M7). held_S is read off the cell; held_S1 is INFERRED (the cell gives R8 as
# internal to CB1 under S'/S'' and marks R5, R6, R10, R19 as S-only; T3 does not list an S' set explicitly).
# The held column contains no guard row by construction (guards appear only under "not held"), so equality with
# E3-REL under K1 (b)/(c)-K1c is near-definitional, not independent corroboration.
held_S = ['R2', 'R3', 'R5', 'R6', 'R8', 'R9', 'R10', 'R19', 'R22']   # R9, R2, R3; R8 (S only); R5, R6, R10, R19 (S-only); R22
held_S1 = ['R2', 'R3', 'R9', 'R22']                                  # inferred [E]: R8 internal to CB1 under S'/S''; S-only rows drop
print('  T3:310 names R9, R2, R3, R8, R5, R6, R10, R19, R22:', all(f'R{n}' in line for n in (9, 2, 3, 8, 5, 6, 10, 19, 22)),
      '| R15 cited as guard in "not held" (T3:310):', '(R15 guard; R69)' in line, '| R17 cited as guard in "not held" (T3:311):', '(R17 guard;' in T3[310])
num = lambda r: int(r[1:])
for nm, enc in (('K1 (a): T3/FZ', FZ), ('K1 (b): SH on T3', B.SH), ('K1 (c) K1c on K', P['C (K1c)']), ('K1 (c) R15ref', P['C-R15ref']), ('C-Sigma', P['C-Sigma [E]'])):
    for ru, held in (('S', held_S), ('S1', held_S1)):
        rel = sorted((r for r in B.TA if B.side(enc, r, ru) == 'X' and r not in B.HOSTED), key=num)
        print(f'  {nm:18s} {ru:3s} E3-REL {len(rel):2d} == held T-A rows: {rel == held}  extra {sorted(set(rel) - set(held), key=num)}')
