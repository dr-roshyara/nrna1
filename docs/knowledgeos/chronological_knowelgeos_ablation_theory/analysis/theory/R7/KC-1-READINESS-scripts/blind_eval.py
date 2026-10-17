# KC-1-READINESS-AUDIT r0: evaluation of the blind determinacy test (encodings in blind/results/).
# (1) substitute each blind guard-row encoding into FZ (all other rows FZ): guard-row verdicts and E3-REL under S, S';
# (2) controls: blind control rows vs FZ; (3) row-by-row Jaccard vs FZ, K and the C parses;
# (4) uncertainty of item-level kappa: item bootstrap (168 decisions, treats items as independent: they are not)
#     and row-cluster bootstrap (resamples the 6 guard rows), plus exact enumeration over row subsets.
# Analysis only; decides nothing. Run from this folder:  python3 -B blind_eval.py ..
import sys, os, io, contextlib, json, random, itertools
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.path.insert(0, os.path.join(R7, 'KC-MKC-REASSESSMENT-scripts'))
sys.argv = ['mkc.py', R7]
with contextlib.redirect_stdout(io.StringIO()):
    import mkc
kc = mkc.kc; B = mkc.B; FZ = B.FZ; P = mkc.P; TA = B.TA; ALL = B.ALL
GUARD = ['R11', 'R14', 'R15', 'R16', 'R17', 'R21']; CTL = ['R1', 'R6', 'R9', 'R79', 'R80']
HERE = os.path.dirname(os.path.abspath(__file__)); BL = os.path.join(HERE, 'blind', 'results')
RAW = {n: json.load(open(os.path.join(BL, n + '.json'))) for n in ('A1', 'A2', 'B1', 'B2')}
def conv(lst):  # blind C-id list -> FZ form: C1 becomes the actor flag (N for an act/work actor; none of the guard rows names one)
    s = {int(c[1:]) for c in lst}
    return (s - {1}, 'N' if 1 in s else None)
ENC = {n: {r: conv(v) for r, v in d.items()} for n, d in RAW.items()}
num = lambda r: int(r[1:])
def e3(enc, rule):
    return sorted((r for r in TA if B.side(enc, r, rule) == 'X' and r not in B.HOSTED), key=num)

print('== 1. guard rows substituted into FZ (all other rows FZ) ==')
FZv = {r: B.side(FZ, r, 'S1') for r in GUARD}
print('  FZ (T3) guard verdicts:', FZv)
for n, en in ENC.items():
    d = dict(FZ); d.update({r: en[r] for r in GUARD})
    v = {r: B.side(d, r, 'S1') for r in GUARD}; vS = {r: B.side(d, r, 'S') for r in GUARD}
    rs, r1 = e3(d, 'S'), e3(d, 'S1')
    print(f'  {n}: guard verdicts S\' {v} | S==S\' {v == vS} | reproduces T3 on all six: {v == FZv}')
    print(f'      E3-REL S {len(rs)} {rs} | S\' {len(r1)} {r1}')
print('  C range for comparison: K1c/SH 9/4; R15ref and C-Sigma 10/5; R15ref-R14 11/6')

print('\n== 2. controls (blind encoding vs FZ, verdicts under S and S\') ==')
for n, en in ENC.items():
    row = []
    for r in CTL:
        d = dict(FZ); d[r] = en[r]
        row.append(f"{r}: enc {'=' if en[r] == FZ[r] else '!='}FZ, S {B.side(FZ, r, 'S')}->{B.side(d, r, 'S')}, S' {B.side(FZ, r, 'S1')}->{B.side(d, r, 'S1')}")
    print(f'  {n}: ' + ' | '.join(row))
print('  R80 reference values: T3/K/C empty -> none; VC8 {8,9} -> CB23; M/U denial {8,9,13,17} -> X')

print('\n== 3. Jaccard of each blind guard-row set vs reference encodings ==')
REF = {'FZ=T3=K=K-T=U': FZ, 'M': P['M'], 'C-K1c': P['C (K1c)'], 'C-SH': P['C-SH'], 'C-R15ref': P['C-R15ref'],
       'C-R15ref-R14': P['C-R15ref-R14'], 'C-Sigma': P['C-Sigma [E]']}
def jac(a, b): return 1.0 if not (a | b) else len(a & b) / len(a | b)
for n, en in ENC.items():
    print(f'  {n}:')
    for r in GUARD:
        cells = ' '.join(f'{k}:{jac(en[r][0], REF[k][r][0]):.2f}' for k in REF)
        best = max(REF, key=lambda k: jac(en[r][0], REF[k][r][0]))
        print(f'     {r} {sorted(en[r][0])!s:28s} {cells}  | best {best}')
    means = {k: sum(jac(en[r][0], REF[k][r][0]) for r in GUARD) / 6 for k in REF}
    print('     mean over guard rows:', {k: round(v, 3) for k, v in means.items()})

print('\n== 4. kappa uncertainty (guard rows; 6 rows x 28 items = 168 item decisions per pair) ==')
C = list(range(1, 29))
def vec(en, rows): return [int(c in ({1} if en[r][1] else set()) | en[r][0]) for r in rows for c in C]
def kappa(a, b):
    n = len(a); po = sum(x == y for x, y in zip(a, b)) / n; pa = sum(a) / n; pb = sum(b) / n
    pe = pa * pb + (1 - pa) * (1 - pb)
    return float('nan') if pe == 1 else (po - pe) / (1 - pe)
def pct(xs, q):
    xs = sorted(x for x in xs if x == x); k = (len(xs) - 1) * q; f = int(k); c = min(f + 1, len(xs) - 1)
    return xs[f] + (xs[c] - xs[f]) * (k - f)
rng = random.Random(20261017); NB = 20000
for p, q in (('A1', 'A2'), ('B1', 'B2')):
    a = vec(ENC[p], GUARD); b = vec(ENC[q], GUARD); k0 = kappa(a, b)
    it = []
    for _ in range(NB):
        ix = [rng.randrange(168) for _ in range(168)]; it.append(kappa([a[i] for i in ix], [b[i] for i in ix]))
    rc = []
    for _ in range(NB):
        rs = [rng.choice(GUARD) for _ in GUARD]; rc.append(kappa(vec(ENC[p], rs), vec(ENC[q], rs)))
    nan_rc = sum(1 for x in rc if x != x)
    # exact: kappa over every non-empty subset of the 6 rows (63 subsets) -> range
    sub = [kappa(vec(ENC[p], list(s)), vec(ENC[q], list(s))) for k in range(1, 7) for s in itertools.combinations(GUARD, k)]
    sub = [x for x in sub if x == x]
    print(f'  {p}~{q}: kappa {k0:.3f} | item bootstrap 95% [{pct(it, .025):.3f}, {pct(it, .975):.3f}] | '
          f'row-cluster bootstrap 95% [{pct(rc, .025):.3f}, {pct(rc, .975):.3f}] ({nan_rc} undefined draws dropped) | '
          f'range over the 63 row subsets [{min(sub):.3f}, {max(sub):.3f}]')
    ex = sum(ENC[p][r] == ENC[q][r] for r in GUARD)
    vv = sum(B.side({'Q': ENC[p][r]}, 'Q', 'S1') == B.side({'Q': ENC[q][r]}, 'Q', 'S1') for r in GUARD)
    print(f'      exact set agreement {ex}/6; P-c verdict agreement {vv}/6')
print('  note (r1, M6): these intervals are DESCRIPTIVE ONLY. They resample items or the six rows, not encoders (n = 2 per arm);\n'
      '  items within a row are not independent; undefined row-cluster draws were dropped. They are not inferential.')
