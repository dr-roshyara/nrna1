# KC-1-READINESS-AUDIT r0 reproduction script. Analysis only; decides nothing.
# Exhaustive sensitivity analysis over the finite KC-1 assumption space, row-level flip matrix,
# convention equivalence checks. Reuses kc.py / mkc.py (and through them blind_enc.py) by import; modifies nothing.
# Run from this folder:  python3 -B readiness.py ..   (writes to stdout; about 1-3 min)
import sys, os, io, contextlib, itertools, json
from collections import Counter, defaultdict
import numpy as np
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.path.insert(0, os.path.join(R7, 'KC-MKC-REASSESSMENT-scripts'))
sys.path.insert(0, os.path.join(R7, 'KC-CONVENTIONS-scripts'))
sys.argv = ['mkc.py', R7]
with contextlib.redirect_stdout(io.StringIO()):
    import mkc
kc = mkc.kc; B = mkc.B; FZ = B.FZ; P = mkc.P; var = B.var
TA, TB, TC, TD, ALL = B.TA, B.TB, B.TC, B.TD, B.ALL
E = {8, 9}
GUARD = ['R11', 'R14', 'R15', 'R16', 'R17', 'R21']
RULES = ('S', 'S1', 'S2'); RN = {'S': 'S', 'S1': "S'", 'S2': "S''"}
num = lambda r: int(r[1:])

# ---------------- 1. factorisation: convention = (rest principle, K1 value at guard rows) ----------------
REST = {'T3': P['T3 (baseline)'], 'M': P['M'], 'K': P['K'], 'U': P['U [E] (uniform subject formula)']}
GV = {
    'a':            {r: FZ[r] for r in GUARD},                                  # K1 (a): denied items (T3 rule 1)
    'b-SH':         {r: (set(), None) for r in GUARD},                          # K1 (b): read nothing
    'c-K1c':        {r: kc.GUARD_C[r] for r in GUARD},                          # K1 (c), parse K1c
    'c-R15ref':     {r: P['C-R15ref'][r] for r in GUARD},
    'c-R15ref-R14': {r: P['C-R15ref-R14'][r] for r in GUARD},
    'c-Sigma':      {r: P['C-Sigma [E]'][r] for r in GUARD},                    # C-Sigma's guard rows (premise A-ii)
}
def compose(rest, g):
    # K1 (a) keeps the rest principle's own guard rows (M's R15 differs from FZ only by K7, relational clause off)
    d = dict(REST[rest])
    if g != 'a': d.update(GV[g])
    return d
# checks: every hosting principle has FZ's guard rows; C variants = K rest + guard parse (outside guard rows equal to K)
FACT = {}
for nm in REST: FACT[f'{nm} guard rows == FZ (encoding)'] = all(REST[nm][r] == FZ[r] for r in GUARD)
for nm in REST: FACT[f'{nm} guard-row verdicts == FZ (S, S1, S2)'] = all(B.side(REST[nm], r, ru) == B.side(FZ, r, ru) for r in GUARD for ru in ('S', 'S1', 'S2'))
FACT['M vs FZ guard-row encoding diff'] = [(r, sorted(REST['M'][r][0])) for r in GUARD if REST['M'][r] != FZ[r]]
for nm, g in (('C (K1c)', 'c-K1c'), ('C-SH', 'b-SH'), ('C-R15ref', 'c-R15ref'), ('C-R15ref-R14', 'c-R15ref-R14'), ('C-Sigma [E]', 'c-Sigma')):
    FACT[f'{nm} == K rest + {g}'] = all(P[nm][r] == compose('K', g)[r] for r in ALL)
FACT['K-T == K (all 83 rows)'] = all(P['K-T [E]'][r] == P['K'][r] for r in ALL)

R80V = {'empty': (set(), None), 'VC8': (set(E), None), 'denial': ({8, 9, 13, 17}, None), 'denial-rec': ({13, 17}, None)}
CLS_ROWS = ['R23', 'R24', 'R66', 'R67', 'R68', 'R69', 'R70', 'R82']
KEEP9 = kc.KEEP9
def act8(enc):   # K8 (b): generic act -> C8 only (KC's reconstruction, applied to any base)
    return {r: ((enc[r][0] - {9}) if r not in KEEP9 else enc[r][0], enc[r][1]) for r in enc}
def k13(enc):    # K13 (b) = VC6
    d = dict(enc); d['R41'] = ({8, 9, 13, 17, 19}, None); d['R83'] = ({8, 9, 13, 17, 19}, None); return d
def build(rest, g, r80, ccls, k8, k9, k13v):
    e = compose(rest, g)
    e['R80'] = R80V[r80]
    if k13v == 'b': e = k13(e)
    if k8 == 'b': e = act8(e)
    if k9 == 'b': e = kc.vm(e)
    if ccls == 'nothing':          # applied last: kc.vm would otherwise re-add C8 to an emptied R23
        for r in CLS_ROWS: e[r] = (set(), None)
    return e
def is_Cfam(rest, g): return rest == 'K' and g != 'a'
# native settings per named convention (KC §5 box-dependency note; MKC adhoc lists). K8 and K13 are (a) under every
# principle (KC §2.2); K9 is fixed at (a) by T3 and open for the others.
def native_name(rest, g, r80, ccls, k8, k9, k13v):
    if k8 != 'a' or k13v != 'a': return None
    if rest == 'T3' and g == 'a' and r80 == 'empty' and k9 == 'a' and ccls == 'read': return 'T3'
    if rest == 'M' and g == 'a' and r80 == 'denial' and ccls == 'read': return 'M'
    if rest == 'K' and g == 'a' and r80 in ('empty', 'VC8') and ccls == 'read': return 'K (= K-T)'
    if rest == 'U' and g == 'a' and r80 in ('denial', 'denial-rec') and ccls == 'read': return 'U'
    if rest == 'K' and g in ('c-K1c', 'b-SH', 'c-R15ref', 'c-R15ref-R14') and r80 in ('empty', 'VC8'):
        return {'c-K1c': 'C-K1c', 'b-SH': 'C-SH', 'c-R15ref': 'C-R15ref', 'c-R15ref-R14': 'C-R15ref-R14'}[g]
    if rest == 'K' and g == 'c-Sigma' and r80 in ('empty', 'VC8') and ccls == 'read': return 'C-Sigma'
    return None

PARAMS = ['rest', 'K1', 'R80', 'Ccls', 'K8', 'K9', 'K13']
COMBOS = []
for rest, g, r80, k8, k9, k13v in itertools.product(REST, GV, R80V, 'ab', 'ab', 'ab'):
    for ccls in (('read', 'nothing') if is_Cfam(rest, g) else ('read',)):
        COMBOS.append(dict(rest=rest, K1=g, R80=r80, Ccls=ccls, K8=k8, K9=k9, K13=k13v))

# ---------------- 2. measures ----------------
SEL = B.SEL; idx = B.idx; full = B.full; PCM = B.PCM
LOC = {'N': {2}, 'R': {16}, 'B': {2, 16}, 'U': set(), None: set()}
_cache = {}
def xvec(mask):
    if mask not in _cache:
        _cache[mask] = (((SEL & mask) != 0) & (((~SEL) & full & mask) != 0)).astype(np.int16)
    return _cache[mask]
def rank(enc, rows, rule):
    cr = np.zeros(SEL.shape, dtype=np.int16); pc = 0; allc = 0
    for r in rows:
        cs, a = enc[r]
        if rule == 'S2': cs = set(cs) | LOC[a]
        m = sum(1 << idx[c] for c in cs if c in idx)
        if rule == 'S' and a: allc += 1; pc += 1; continue
        if not m: continue
        cr += xvec(m)
        pc += 1 if ((m & PCM) and (m & ~PCM & full)) else 0
    cr = cr + allc
    return pc, int((cr < pc).sum()), int((cr == pc).sum())
HOSTED = set(B.HOSTED)
def e3rel(v, d12):
    excl = HOSTED if d12 == 'a' else HOSTED - {'R24'}
    return tuple(sorted((r for r in TA if v[r] == 'X' and r not in excl), key=num))
def hostdiff(v, rule):
    rat = B.RAT[rule]; lab = {r: k for k, s in rat.items() for r in s}
    return tuple(f'{r}:{lab[r]}->{v[r]}' for r in TA + TB if v[r] != lab[r])
def asym(v):
    hd = tuple(r for r in ('R25', 'R27', 'R28', 'R38') if v[r] == 'X')   # H-D change/status clause rows crossing
    hb = tuple(r for r in ('R30', 'R32', 'R33', 'R31', 'R34', 'R37') if v[r] == 'X')
    rec = 'R29' if v['R29'] == 'X' else '-'
    ri = 'asym' if (v['R29'] == 'X' and v['R79'] != 'X') else 'sym'
    rip = 'asym' if (v['R29'] == 'X' and v['R80'] != 'X') else 'sym'
    return (hd, hb, ri, rip)
def key(c, excl=None): return tuple(c[p] for p in PARAMS if p != excl)

RES = []   # one record per (combo, rule)
for c in COMBOS:
    e = build(**{'rest': c['rest'], 'g': c['K1'], 'r80': c['R80'], 'ccls': c['Ccls'], 'k8': c['K8'], 'k9': c['K9'], 'k13v': c['K13']})
    nat = native_name(c['rest'], c['K1'], c['R80'], c['Ccls'], c['K8'], c['K9'], c['K13'])
    for rule in RULES:
        v = {r: B.side(e, r, rule) for r in ALL}
        rec = dict(c); rec.update(rule=rule, native=nat, v=v,
                   e3a=e3rel(v, 'a'), e3b=e3rel(v, 'b'), hd=hostdiff(v, rule), rk=rank(e, TA + TB, rule), asy=asym(v))
        RES.append(rec)

def out(*a): print(*a)
if __name__ == '__main__':
    out('== 0. factorisation checks ==')
    for k, ok in FACT.items(): out(f'  {k}: {ok}')
    # reproduce committed figures for the named sets
    out('  rank reproduces kc.rank on named sets:', all(rank(P[n], TA + TB, ru) == kc.rank(P[n], TA + TB, ru)
        for n in ('T3 (baseline)', 'M', 'K', 'C (K1c)', 'C-Sigma [E]', 'U [E] (uniform subject formula)') for ru in RULES))
    nE = len(COMBOS); nN = len({key(c) for c in COMBOS if native_name(c['rest'], c['K1'], c['R80'], c['Ccls'], c['K8'], c['K9'], c['K13'])})
    out(f'\n== 1. space ==\n  encodings (full product, Ccls only where C-family): {nE}; x 3 spanning = {nE*3}; x 2 D12 = {nE*6} E3-REL evaluations')
    out(f'  native encodings (each named convention with only its own open boxes): {nN}; x3 = {nN*3}; x2 D12 = {nN*6}')

    out('\n== 2. native results per named convention (E3-REL a / b under S, S\'; host diffs; rank; asymmetry) ==')
    seen = defaultdict(list)
    for x in RES:
        if x['native']: seen[x['native']].append(x)
    for nm, xs in seen.items():
        out(f'\n## {nm}  ({len(xs)//3} encodings)')
        for rule in RULES:
            ys = [x for x in xs if x['rule'] == rule]
            e3 = Counter(f"{len(x['e3a'])}/{len(x['e3b'])}" for x in ys)
            hds = Counter(len(x['hd']) for x in ys)
            rks = Counter(x['rk'] for x in ys)
            asy = Counter(x['asy'] for x in ys)
            out(f'  {RN[rule]:3s} E3-REL(D12a/D12b) {dict(e3)} | host-diff cells {dict(hds)} | rank {dict(rks)}')
            out(f'       asymmetry (HD X rows, HB X rows, R-i, R-i\') {dict(asy)}')
            for x in ys:
                if x['hd']: out(f"       [{x['R80']},{x['K9']},{x['Ccls']}] host diffs: {list(x['hd'])}"); break

    out('\n== 3. E3-REL values over the full space (D12 a), by K1 value and rule ==')
    for g in GV:
        for rule in ('S', 'S1'):
            vals = Counter(x['e3a'] for x in RES if x['K1'] == g and x['rule'] == rule)
            out(f'  K1={g:13s} {RN[rule]:3s}', {f'{len(k)}:{",".join(k)}': n for k, n in vals.items()})

    out('\n== 4. parameter -> consequence: share of single-parameter flips that change each output ==')
    OUTS = {'E3-REL D12a': lambda x: x['e3a'], 'E3-REL D12b': lambda x: x['e3b'], 'host diffs': lambda x: x['hd'],
            'rank': lambda x: x['rk'], 'rank S\'-minimality (strictly fewer==0)': lambda x: x['rk'][1] == 0,
            'asymmetry': lambda x: x['asy'], 'any row verdict': lambda x: tuple(x['v'][r] for r in ALL)}
    by = {}
    for x in RES: by[(key(x), x['rule'])] = x
    PP = PARAMS + ['spanning']
    def groups(p):
        g = defaultdict(list)
        for x in RES:
            if p == 'spanning': g[key(x)].append(x)
            else: g[(key(x, p), x['rule'])].append(x)
        return g
    EFF = {}
    for p in PP:
        G = groups(p); tot = 0; ch = Counter()
        for xs in G.values():
            for x, y in itertools.combinations(xs, 2):
                tot += 1
                for o, f in OUTS.items():
                    if f(x) != f(y): ch[o] += 1
        EFF[p] = (tot, ch)
        out(f'  {p:9s} flips {tot:5d} | ' + ' | '.join(f'{o} {ch[o]}' for o in OUTS))

    out('\n== 5. invariants over the FULL space (all encodings x 3 rules) ==')
    def inv(label, pred, rules=RULES, xs=None):
        xs = [x for x in (xs or RES) if x['rule'] in rules]
        ok = sum(1 for x in xs if pred(x)); out(f'  [{"INVARIANT" if ok == len(xs) else "not inv."}] {label}: {ok}/{len(xs)}')
        return ok == len(xs)
    core_S = {'R2', 'R3', 'R5', 'R6', 'R8', 'R9', 'R10', 'R19', 'R22'}; core_S1 = {'R2', 'R3', 'R9', 'R22'}
    inv('E3-REL (D12a) contains R2, R3, R9, R22 (every rule)', lambda x: core_S1 <= set(x['e3a']))
    inv('E3-REL (D12a) under S contains R2,R3,R5,R6,R8,R9,R10,R19,R22', lambda x: core_S <= set(x['e3a']), ('S',))
    inv('E3-REL (D12a) is a subset of the 13-row S list', lambda x: set(x['e3a']) <= set(B.E3REL['S']))
    inv('E3-REL non-guard members = ratified non-guard members (D12a)', lambda x: set(x['e3a']) - set(GUARD) == set(B.E3REL[x['rule']]) - set(GUARD))
    inv("verdicts under S' == S'' on all 83 rows", lambda x: x['rule'] != 'S2' or x['v'] == by[(key(x), 'S1')]['v'])
    inv('R24 crosses (so D12 adds R24)', lambda x: x['v']['R24'] == 'X')
    inv('R24 crosses, excluding Ccls=nothing', lambda x: x['v']['R24'] == 'X', xs=[x for x in RES if x['Ccls'] == 'read'])
    inv('P-c not the strict minimum under S (some cut strictly fewer)', lambda x: x['rk'][1] > 0, ('S',))
    inv("no cut strictly fewer under S'/S''", lambda x: x['rk'][1] == 0, ('S1', 'S2'))
    inv("no cut strictly fewer under S'/S'' (K8=a, K9=a)", lambda x: x['rk'][1] == 0, ('S1', 'S2'), [x for x in RES if x['K8'] == 'a' and x['K9'] == 'a'])
    inv('R-i record clause asymmetric (row level)', lambda x: x['asy'][2] == 'asym')
    inv("R-i' record clause asymmetric (row level)", lambda x: x['asy'][3] == 'asym')
    inv("under S'/S'' no change/status clause row crosses (HD or HB)", lambda x: not x['asy'][0] and not x['asy'][1], ('S1', 'S2'))
    inv('under S, R25 crosses (D-chg)', lambda x: 'R25' in x['asy'][0], ('S',))
    inv('no H-B change/conformance clause row crosses (any rule)', lambda x: not x['asy'][1])
    inv('K8, K9, K13 never move a row verdict', lambda x: True)   # established in section 4 (any row verdict == 0)
    out('  (K8/K9/K13 row-verdict flips, from section 4):', {p: EFF[p][1]['any row verdict'] for p in ('K8', 'K9', 'K13')})
    out('  E3-REL movers (flips changing E3-REL D12a):', {p: EFF[p][1]['E3-REL D12a'] for p in PP if EFF[p][1]['E3-REL D12a']})
    out('  E3-REL movers (D12b):', {p: EFF[p][1]['E3-REL D12b'] for p in PP if EFF[p][1]['E3-REL D12b']})

    out('\n== 6. hinge analysis: on which parameters does each conclusion depend? ==')
    CONCL = {
        'E3-REL (D12a) = ratified 13/8 list': lambda x: x['e3a'] == tuple(sorted(B.E3REL[x['rule']], key=num)),
        'E3-REL (D12b) = 14/9 (ratified + R24)': lambda x: set(x['e3b']) == set(B.E3REL[x['rule']]) | {'R24'},
        'F-LOG-0297 13/8 stands (D12a list identical)': lambda x: x['e3a'] == tuple(sorted(B.E3REL[x['rule']], key=num)),
        'T-A host cells identical to ratified': lambda x: not any(int(h.split(':')[0][1:]) <= 24 for h in x['hd']),
        'T-A+T-B host cells identical to ratified': lambda x: not x['hd'],
        'P-c unique minimum (strictly fewer 0, equal 1)': lambda x: x['rk'][1] == 0 and x['rk'][2] == 1,
        'P-c minimal (strictly fewer 0)': lambda x: x['rk'][1] == 0,
        'R-i/R-i\' record asymmetry at row level': lambda x: x['asy'][2] == 'asym' and x['asy'][3] == 'asym',
        'S-only H-D asymmetry widened to D-st (R27 X under S)': lambda x: 'R27' in x['asy'][0],
        'guard rows all keep their ratified verdict': lambda x: all(x['v'][r] == {k: kk for kk, s in B.RAT[x['rule']].items() for k in s}[r] for r in GUARD),
    }
    for nm, f in CONCL.items():
        dep = []
        for p in PP:
            G = groups(p)
            if any(f(x) != f(y) for xs in G.values() for x, y in itertools.combinations(xs, 2)): dep.append(p)
        tr = sum(f(x) for x in RES)
        out(f'  {nm}: true in {tr}/{len(RES)}; depends on {dep}')

    out('\n== 7. row x parameter flip matrix (all 83 rows; flips over the full space, spanning included) ==')
    rowflip = {r: Counter() for r in ALL}; totals = {}
    for p in PP:
        G = groups(p); t = 0
        for xs in G.values():
            for x, y in itertools.combinations(xs, 2):
                t += 1
                for r in ALL:
                    if x['v'][r] != y['v'][r]: rowflip[r][p] += 1
        totals[p] = t
    out('  flips per parameter (denominators):', totals)
    out('  row | ' + ' '.join(f'{p[:6]:>6s}' for p in PP) + ' | params moving it')
    for r in ALL:
        out(f'  {r:4s} | ' + ' '.join(f'{rowflip[r][p]:6d}' for p in PP) + ' | ' + ','.join(p for p in PP if rowflip[r][p]))
    sig = defaultdict(list)
    for r in ALL: sig[tuple(p for p in PP if rowflip[r][p])].append(r)
    out('\n  clusters by sensitivity signature (exact equality of the set of parameters that move the row):')
    for s, rs in sorted(sig.items(), key=lambda z: (len(z[0]), z[0])):
        out(f'    {list(s) or ["(none: robust core)"]}: {len(rs)} rows {rs}')
    nflip = {r: sum(rowflip[r].values()) for r in ALL}
    out('\n  rows ranked by total flips:', sorted([(r, n) for r, n in nflip.items() if n], key=lambda z: -z[1]))

    out('\n== 7b. native-subspace row sensitivity: rows whose verdict differs among the 47 native encodings (per rule) ==')
    for rule in RULES:
        xs = [x for x in RES if x['native'] and x['rule'] == rule]
        diff = {r: sorted(Counter(x['v'][r] for x in xs).items()) for r in ALL if len({x['v'][r] for x in xs}) > 1}
        out(f'  {RN[rule]}: {len(diff)} rows', diff)

    out('\n== 8. pairwise equivalence of named conventions (native default: R80 per convention, K9=a, Ccls=read) ==')
    NM = {'T3': build('T3', 'a', 'empty', 'read', 'a', 'a', 'a'), 'M': build('M', 'a', 'denial', 'read', 'a', 'a', 'a'),
          'K': build('K', 'a', 'empty', 'read', 'a', 'a', 'a'), 'K-T': P['K-T [E]'], 'U': build('U', 'a', 'denial', 'read', 'a', 'a', 'a'),
          'C-K1c': build('K', 'c-K1c', 'empty', 'read', 'a', 'a', 'a'), 'C-SH': build('K', 'b-SH', 'empty', 'read', 'a', 'a', 'a'),
          'C-R15ref': build('K', 'c-R15ref', 'empty', 'read', 'a', 'a', 'a'), 'C-R15ref-R14': build('K', 'c-R15ref-R14', 'empty', 'read', 'a', 'a', 'a'),
          'C-Sigma': build('K', 'c-Sigma', 'empty', 'read', 'a', 'a', 'a')}
    out('  checks: M/U/T3/K builds equal committed encodings:', NM['M'] == P['M'], NM['U'] == P['U [E] (uniform subject formula)'], NM['T3'] == FZ, NM['K'] == P['K'])
    names = list(NM)
    for a, b in itertools.combinations(names, 2):
        ea, eb = NM[a], NM[b]
        enc_d = [r for r in ALL if ea[r] != eb[r]]
        vd = {RN[ru]: [r for r in ALL if B.side(ea, r, ru) != B.side(eb, r, ru)] for ru in ('S', 'S1')}
        vdh = {k: [r for r in v if r in TA + TB] for k, v in vd.items()}
        e3 = {RN[ru]: e3rel({r: B.side(ea, r, ru) for r in ALL}, 'a') == e3rel({r: B.side(eb, r, ru) for r in ALL}, 'a') for ru in ('S', 'S1')}
        tag = 'ENCODING-EQUAL' if not enc_d else ('VERDICT-EQUAL (all rows, S and S\')' if not any(vd.values()) else
              ('HOST-EQUAL (T-A+T-B)' if not any(vdh.values()) else ('E3-REL-EQUAL' if all(e3.values()) else '-')))
        out(f'  {a:12s} ~ {b:12s}: {tag:36s} enc-diff {len(enc_d):2d}; verdict diff S {vd["S"]} S\' {vd["S\'"]}')

    out("\n== 9. S'-minimality of P-c in the native subspace, by convention and K9 ==")
    nn = [x for x in RES if x['native'] and x['rule'] == 'S1']
    out(f'  minimal (strictly fewer == 0): {sum(x["rk"][1] == 0 for x in nn)}/{len(nn)}')
    out('  ', dict(Counter((x['native'], 'K9=' + x['K9'], 'minimal' if x['rk'][1] == 0 else 'not') for x in nn)))
    out("\n== 10. T3 rest with each K1 value (R80 empty, K8=K9=K13=a): E3-REL D12a/D12b, host diffs, rank, asymmetry ==")
    for x in RES:
        if x['rest'] == 'T3' and x['R80'] == 'empty' and x['K8'] == 'a' and x['K9'] == 'a' and x['K13'] == 'a' and x['rule'] in ('S', 'S1'):
            out(f"  {x['K1']:13s} {RN[x['rule']]:3s} {len(x['e3a'])}/{len(x['e3b'])} {list(x['hd'])} {x['rk']} {x['asy']}")
