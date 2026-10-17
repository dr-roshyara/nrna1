# KC-1-READINESS-AUDIT r2 §12.2: the enumeration boundary. Analysis only; decides nothing.
# Question: can a convention that readiness.py did NOT enumerate (K2-K7, K10-K12, K15-K19) change
#   (i) a guard-row verdict for a fixed K1 value, (ii) a T-A host cell, (iii) the E3-REL box?
# Method:
#   0. row-locality: B.side(enc, r, rule) reads enc[r] only (checked by perturbing every other row);
#   1. footprint of every kc.ALT alternative (rows whose encoding it changes) against L and T-A;
#   2. K15 / K16 as transformers (attribute -> bearer; O -> C13+C14+C15) and a side-preservation check;
#   3. extended per-row product: for every row in L u T-A, every distinct base row of the 928 enumerated encodings,
#      and every signed subset of the non-enumerated deltas touching that row (each delta off / on / inverted),
#      plus K15 and K16; verdict sets collected per K1 value (guard rows) or overall (other T-A rows);
#   4. K18 (source of rho = T2 "rests on" column, as restson.py) composed with K1 (a) and (b);
#   5. K19 (actor-location typing): every flag value N / R / B / U at the flagged T-A rows, under S, S', S'';
#   6. N_gen outside L: R23's positive residue [E] (the independence clause dropped).
# Reuses readiness.py (and through it mkc.py, kc.py, blind_enc.py) by import; modifies nothing.
# Run from this folder:  python3 -B ch_boundary.py ..   (about 20 s)
import sys, os, io, contextlib, itertools, re
from collections import defaultdict
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.argv = ['readiness.py', R7]
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
with contextlib.redirect_stdout(io.StringIO()):
    import readiness as RD
B = RD.B; kc = RD.kc; FZ = B.FZ; TA, TB, ALL = B.TA, B.TB, B.ALL; RULES = RD.RULES; RN = RD.RN
L = RD.GUARD; num = RD.num; HOSTED = set(B.HOSTED)
CB1 = {c for c, s in B.PC.items() if s == 0}; CB23 = {c for c, s in B.PC.items() if s == 1}
srt = lambda xs: sorted(xs, key=num)

print('== 0. row-locality of the verdict ==')
ok = True
for r in ALL:
    for rule in RULES:
        v = B.side(FZ, r, rule)
        pert = {k: ((set(), None) if k != r else FZ[k]) for k in ALL}       # every other row emptied
        pert2 = {k: (({2, 13}, 'B') if k != r else FZ[k]) for k in ALL}     # every other row crossing
        ok &= (B.side(pert, r, rule) == v == B.side(pert2, r, rule))
print('  B.side(enc, r, rule) depends on enc[r] only (83 rows x 3 rules, two perturbations):', ok)
print('  hence: guard verdicts depend only on rho at L; T-A host cells only on rho at T-A; E3-REL (D12 a) only on rho at T-A \\ H')

print('\n== 1. footprints of the KC inventory alternatives (rows whose encoding differs from FZ) ==')
ENUM = {'K1 guard rows read nothing (SH)', 'K1c guard rows read object-level content only', 'K5a R80 negated clause read (SF)',
        'K5b R80 "acts exist" read (VC8)', 'K8 generic act -> C8 only', 'K9 value mapping v->C4, prep->C8',
        'K13 statement scope: R41/R83 without C23 (VC6)'}
DELTA = {}
for k, alt in kc.ALT.items():
    rows = [r for r in ALL if alt[r] != FZ[r]]
    tag = 'enumerated in readiness.py' if k in ENUM else 'NOT enumerated independently'
    print(f'  {k:52s} [{tag}] rows {rows} | in L: {[r for r in rows if r in L]} | in T-A: {[r for r in rows if r in TA]}')
    if k not in ENUM:
        DELTA[k] = {r: (alt[r][0] - FZ[r][0], FZ[r][0] - alt[r][0], alt[r][1], FZ[r][1]) for r in rows}

print('\n== 2. K15 (attribute reads bearer) and K16 (O -> C13 + C14 + C15): side preservation ==')
BEARER = {14: 13, 15: 13, 16: 13, 17: 13, 18: 13, 11: 8, 12: 8, 20: 19}   # r, c, k, Ref, con -> record; d, f -> act; t(w) -> work event
print('  attribute -> bearer pairs, placement equal:', {f'C{a}->C{b}': B.PC.get(a) == B.PC.get(b) for a, b in BEARER.items()})
print('  O-expansion {C13, C14, C15} all CB1:', {13, 14, 15} <= CB1)
def k15(row):
    cs, a = row; return (set(cs) | {BEARER[c] for c in cs if c in BEARER}, a)
def k16(row):
    cs, a = row; return ((set(cs) | {14, 15}) if 13 in cs else set(cs), a)
print('  [FD] adding an item placed on a side the row already reads, or a co-placed bearer, never changes |sides|;'
      ' both transformers are side-preserving for every row, every K1 value and every spanning rule')

print('\n== 3. extended per-row product over the non-enumerated deltas (signed: off / on / inverted), plus K15, K16 ==')
# Guards against vacuous re-additions (found in a first run, r2): a delta that ADDS items is applied only to a row that
# reads something (an emptied row, K1 (b) / (c) or Ccls = nothing, has no clause for the convention to act on), and the
# relational clause (K7) adds acts / work events only to a row that reads Ref or con (C17, C18), as T3 rule 2 states.
def apply(row, d, sign, conv=''):
    add, rem, fa, fz = d
    cs, a = row
    plus = add if sign > 0 else rem; minus = rem if sign > 0 else add
    if not cs and not a: return (set(), None)          # emptied row: nothing for the convention to act on
    if conv.startswith('K7') and not (set(cs) & {17, 18}): plus = set()
    return ((set(cs) | plus) - minus, (fa if sign > 0 else fz) if fa != fz else a)
BASES = defaultdict(lambda: defaultdict(set))   # row -> K1 value -> set of base rows
for c in RD.COMBOS:
    e = RD.build(rest=c['rest'], g=c['K1'], r80=c['R80'], ccls=c['Ccls'], k8=c['K8'], k9=c['K9'], k13v=c['K13'])
    for r in L + TA:
        cs, a = e[r]; BASES[r][c['K1']].add((frozenset(cs), a))
def ext_verdicts(r, bases):
    ds = [(k, d[r]) for k, d in DELTA.items() if r in d]
    out = defaultdict(set); n = 0
    for cs, a in bases:
        for signs in itertools.product((0, 1, -1), repeat=len(ds)):
            row = (set(cs), a)
            for (kk, d), s in zip(ds, signs):
                if s: row = apply(row, d, s, kk)
            for t15, t16 in itertools.product((0, 1), repeat=2):
                rr = row
                if t15: rr = k15(rr)
                if t16: rr = k16(rr)
                n += 1
                for rule in RULES: out[rule].add(B.side({r: rr}, r, rule))
    return out, n, len(ds)
print('  guard rows, verdict set per K1 value: enumerated space vs extended space (S / S\' / S\'\')')
moved_guard = False
for r in L:
    for g in RD.GV:
        enum = {rule: {B.side({r: (set(cs), a)}, r, rule) for cs, a in BASES[r][g]} for rule in RULES}
        ext, n, k = ext_verdicts(r, BASES[r][g])
        same = all(enum[ru] == ext[ru] for ru in RULES)
        moved_guard |= not same
        print(f'    {r} K1={g:13s} enum {[sorted(enum[ru]) for ru in RULES]} ext {[sorted(ext[ru]) for ru in RULES]} deltas {k} evals {n} {"SAME" if same else "WIDER"}')
print('  any guard verdict widened for a fixed K1 value:', moved_guard)
print('  non-guard T-A rows: verdict set over the enumerated space vs the extended space')
widened = {}
for r in TA:
    if r in L: continue
    allb = set().union(*BASES[r].values())
    enum = {rule: {B.side({r: (set(cs), a)}, r, rule) for cs, a in allb} for rule in RULES}
    ext, n, k = ext_verdicts(r, allb)
    if any(enum[ru] != ext[ru] for ru in RULES) or k:
        who = [kk for kk, d in DELTA.items() if r in d]
        widened[r] = (enum, ext)
        print(f'    {r} {"(H)" if r in HOSTED else "   "} deltas {who} enum {[sorted(enum[ru]) for ru in RULES]} ext {[sorted(ext[ru]) for ru in RULES]}')
print('  T-A \\ H rows (E3-REL, D12 a) whose X-status can change in the extended space but not in the enumerated one:',
      [r for r, (e, x) in widened.items() if r not in HOSTED and any(('X' in e[ru]) != ('X' in x[ru]) or (e[ru] == {'X'}) != (x[ru] == {'X'}) for ru in RULES)])
print('  R24 (enters E3-REL under D12 b):', {RN[ru]: sorted(widened['R24'][1][ru]) for ru in RULES} if 'R24' in widened else 'no delta')

print('\n== 4. K18: source of rho = T2 "rests on ... C-ids" column (restson.py logic), composed with K1 ==')
t2 = open(os.path.join(R7, 'T2-RULE-CANDIDATES.md'), encoding='utf-8').read().splitlines()
COL = {}
for l in t2:
    m = re.match(r'^\| (R\d+) \|', l)
    if not m or m.group(1) in COL: continue
    rc = [c for c in (x.strip() for x in l.split('|')) if re.search(r'·\s*C\d', c)]
    COL[m.group(1)] = ({int(x) for x in re.findall(r'C(\d+)', rc[0].split('·')[-1])} if rc else None)
miss = [r for r in ALL if COL.get(r) is None]
print('  rows without the column (kept at FZ here; restson.py drops them):', miss)
def k18enc():
    d = {}
    for r in ALL:
        if COL.get(r) is None: d[r] = FZ[r]; continue
        cs = COL[r]; d[r] = (cs - {1}, 'N' if 1 in cs else None)
    return d
K18 = k18enc()
for lab, enc in (('K18 + K1 (a) [column sets at L]', K18), ('K18 + K1 (b) SH', RD.var(K18, **{r: (set(), None) for r in L})),
                 ('statement + K1 (a) = T3 (reference)', FZ)):
    for rule in ('S', 'S1'):
        v = {r: B.side(enc, r, rule) for r in ALL}
        print(f'  {lab:38s} {RN[rule]:3s} guard {[v[r] for r in L]} | E3-REL a {len(RD.e3rel(v, "a"))} {list(RD.e3rel(v, "a"))}'
              f' | E3-REL \\ L {srt(set(RD.e3rel(v, "a")) - set(L))} | T-A host diffs {[h for h in RD.hostdiff(v, rule) if h.split(":")[0] in TA]}')
# r2.1 (review N6): two compositions, stated. Composition I ("the column replaces the input at L", used above for (a)):
# every K1 value that reads the row's input reads the column, so (a) and (c) both collapse to the column sets. Composition II
# ("the K1 value overrides the column at L"): the column is used outside L only, and L takes the K1 value's own sets.
print('  composition I (column replaces the input at L): K1 (a) and K1 (c) both read the column at L -> identical to the K18 + (a) rows above')
for g in RD.GV:
    enc = RD.var(K18, **RD.GV[g])
    for rule in ('S', 'S1'):
        v = {r: B.side(enc, r, rule) for r in ALL}
        print(f'  composition II: K18 outside L + K1={g:13s} {RN[rule]:3s} guard {[v[r] for r in L]} | E3-REL a {len(RD.e3rel(v, "a"))}'
              f' | E3-REL \\ L {srt(set(RD.e3rel(v, "a")) - set(L))}')

print('\n== 5. K19: actor-location typing (S\'\' only); every flag value at every flagged T-A row ==')
FL = [r for r in TA if FZ[r][1]]
print('  flagged T-A rows (FZ):', FL, '| flags:', {r: FZ[r][1] for r in FL}, '| guard rows flagged:', [r for r in L if FZ[r][1]])
for r in FL:
    vs = {f: {ru: B.side({r: (FZ[r][0], f)}, r, ru) for ru in RULES} for f in ('N', 'R', 'B', 'U')}
    dep = {RN[ru]: sorted({vs[f][ru] for f in vs}) for ru in RULES}
    print(f'    {r} {"(H)" if r in HOSTED else "   "} FZ flag {FZ[r][1]}: verdict range by flag {dep}')
print('  [FD] under S any flag spans both sides and under S\' no flag counts, so K19 can act only under S\'\'')

print('\n== 6. N_gen outside L: T-A rows of denial form not in rule 1\'s list ==')
# [E] R23 "O ... is independent of sigma, f, Ref, con, d, Cap, k, A, E, W ...; unchanged by fresh additions. Hence no constraint ...
#     can be checked or enforced from records": under N_gen the independence (a denied dependence) and the "no constraint" clause
#     are not read; the positive residue "O is unchanged by fresh additions" reads O -> C13. No actor named in the residue.
RES = {'R23': ({13}, None)}
for r, row in RES.items():
    print(f'  {r}: FZ {srt(["C%d" % c for c in FZ[r][0]])} flag {FZ[r][1]} -> residue [E] {sorted(row[0])};',
          {RN[ru]: (B.side(FZ, r, ru), B.side({r: row}, r, ru)) for ru in RULES}, '| hosted (H):', r in HOSTED)
print('  R24, R1, R4, R13: the negated part is a contrast inside a positive statement whose items are read anyway (no verdict effect) [E]')
# r2.1 (review N4): class-rule rows that DENY a separation (R66, R69, R82; R68 is a positive sufficient condition, T2:143,
# whose contrast clause T3:207 already leaves unread). Witness: verdict with the class rule off (kc's SD encoding).
print('  class-rule denial rows, verdict with rule 3 on (FZ) vs off (SD):')
for r in ('R66', 'R69', 'R82'):
    print(f'    {r}:', {RN[ru]: (B.side(FZ, r, ru), B.side(B.SD, r, ru)) for ru in RULES})

print('\n== 7. cross-row dependencies inside L u T-A (scope of row-locality for the deltas; review N7) ==')
# The deltas are taken relative to FZ. A row whose encoding is BUILT from other rows (row references read through, K4b;
# R15's "(R3)" gloss, K17 / R15ref) could interact with conventions acting on the referenced rows. Inside L u T-A the only
# such construction is R15 -> R3. Check: the sides of R3 over all 928 enumerated encodings.
r3 = set()
for c in RD.COMBOS:
    e = RD.build(rest=c['rest'], g=c['K1'], r80=c['R80'], ccls=c['Ccls'], k8=c['K8'], k9=c['K9'], k13v=c['K13'])
    r3.add(frozenset(B.PC[x] for x in e['R3'][0] if x in B.PC))
print('  K4b footprint inside L u T-A:', [r for r in kc.ROWREF if r in L or r in TA], '| R3 side sets over 928 encodings:', [sorted(x) for x in r3])
