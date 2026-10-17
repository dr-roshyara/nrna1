# KC-1 normalized blind replication (blind2/): evaluation per blind2/prereg.md.
# Primary unit: guard-row verdict (derived from each encoder's item set, substituted into FZ; S' with S alongside).
# Secondary unit: exact item-set match. Descriptive only; no significance testing. Analysis only; decides nothing.
# Run from this folder:  python3 -B blind2_eval.py ..
# r2 review (KC-1-READINESS-AUDIT §12.4; output above the "== r2 additions" line is unchanged):
#   - removed: the last print line, a no-op ("... if False else '(see mkc.out)'") that printed no comparison;
#   - added (sections r2-a to r2-d): the KC / MKC C-family guard verdicts and E3-REL from mkc.P, actually computed;
#     each encoder's distance to them; the controls' verdicts against FZ (not reported before; R80 departs from T3 in
#     every encoder); the verdict-level row sets that would enter E3-REL; and the hash checks of prereg.md (with its
#     own "- prereg.md:" line removed, as its §9 specifies) and the two packets.
# r2.1 (review N1, N10): r2-d now reports the verbatim replies in blind2/results/verbatim/ and blind/results/verbatim/
#   (extracted by the orchestrator with blind2/build/extract_verbatim.py from session-local transcripts that are not
#   archived): presence, JSON equality with the saved <id>.json, and the number of rows that carry a reason line.
import sys, os, io, contextlib, json, itertools
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.path.insert(0, os.path.join(R7, 'KC-MKC-REASSESSMENT-scripts'))
sys.argv = ['mkc.py', R7]
with contextlib.redirect_stdout(io.StringIO()):
    import mkc
B = mkc.B; FZ = B.FZ; TA = B.TA
GUARD = ['R11', 'R14', 'R15', 'R16', 'R17', 'R21']; CTL = ['R1', 'R6', 'R9', 'R79', 'R80']
HERE = os.path.dirname(os.path.abspath(__file__)); BL = os.path.join(HERE, 'blind2', 'results')
NAMES = ['A1', 'A2', 'A3', 'B1', 'B2', 'B3']
RAW = {n: json.load(open(os.path.join(BL, n + '.json'))) for n in NAMES}
def conv(lst):
    s = {int(c[1:]) for c in lst}
    return (s - {1}, 'N' if 1 in s else None)
ENC = {n: {r: conv(v) for r, v in d.items()} for n, d in RAW.items()}
num = lambda r: int(r[1:])
def e3(enc, rule):
    return sorted((r for r in TA if B.side(enc, r, rule) == 'X' and r not in B.HOSTED), key=num)
FZv = {r: B.side(FZ, r, 'S1') for r in GUARD}
print('FZ (T3) guard verdicts S\':', FZv)
V = {}
for n in NAMES:
    d = dict(FZ); d.update({r: ENC[n][r] for r in GUARD})
    V[n] = {r: B.side(d, r, 'S1') for r in GUARD}
    vS = {r: B.side(d, r, 'S') for r in GUARD}
    print(f"{n}: S' {V[n]} | S==S' {V[n] == vS} | =T3 {V[n] == FZv} | E3-REL S {len(e3(d,'S'))} S' {len(e3(d,'S1'))}")
print('\n== H2 (A\' agree on all six guard verdicts) ==')
for r in GUARD: print(' ', r, [V[n][r] for n in ('A1', 'A2', 'A3')])
print('  H2:', all(len({V[n][r] for n in ('A1','A2','A3')}) == 1 for r in GUARD))
print('== H1 (B\' disagree on >=1 guard verdict) ==')
for r in GUARD: print(' ', r, [V[n][r] for n in ('B1', 'B2', 'B3')])
print('  H1:', any(len({V[n][r] for n in ('B1','B2','B3')}) > 1 for r in GUARD))
print('== cross-arm: B\' verdicts equal A\' verdicts? ==')
for r in GUARD: print(' ', r, 'A', sorted({V[n][r] for n in ('A1','A2','A3')}), 'B', sorted({V[n][r] for n in ('B1','B2','B3')}))
print('\n== secondary: exact item-set match (pairs within arm) ==')
for arm in ('A', 'B'):
    for rows, lab in ((GUARD, 'guard'), (CTL, 'control')):
        ps = list(itertools.combinations([arm + '1', arm + '2', arm + '3'], 2))
        tot = sum(set(RAW[p][r]) == set(RAW[q][r]) for p, q in ps for r in rows)
        print(f'  {arm} {lab}: {tot}/{len(ps)*len(rows)} pairwise row matches')
print('\n== r2 additions ==')
import hashlib
print('== r2-a. KC / MKC encodings at the guard rows (S\'), and E3-REL S / S\' ==')
REF = {'T3 (FZ)': 'T3 (baseline)', 'C-SH': 'C-SH', 'C (K1c)': 'C (K1c)', 'C-R15ref': 'C-R15ref', 'C-R15ref-R14': 'C-R15ref-R14', 'C-Sigma': 'C-Sigma [E]'}
RV = {}
for lab, k in REF.items():
    e = mkc.P[k]; RV[lab] = {r: B.side(e, r, 'S1') for r in GUARD}
    print(f'  {lab:13s} {RV[lab]} | E3-REL S {len(e3(e, "S"))} S\' {len(e3(e, "S1"))}')
print('== r2-b. guard rows where each blind encoder departs from each reference (S\') ==')
for n in NAMES:
    print(f'  {n}: ' + '; '.join(f'{lab} {[r for r in GUARD if V[n][r] != RV[lab][r]] or "="}' for lab in RV))
print('== r2-c. controls: verdicts (S\', S) against FZ ==')
for r in CTL:
    fz = (B.side(FZ, r, 'S1'), B.side(FZ, r, 'S'))
    got = {n: (B.side(dict(FZ, **{r: ENC[n][r]}), r, 'S1'), B.side(dict(FZ, **{r: ENC[n][r]}), r, 'S')) for n in NAMES}
    print(f'  {r}: FZ {fz} | ' + ' '.join(f'{n} {g[0]}' for n, g in got.items()) + f' | all equal FZ: {all(g == fz for g in got.values())}')
print('== r2-d. integrity ==')
P2 = os.path.join(HERE, 'blind2')
pre = open(os.path.join(P2, 'prereg.md'), 'rb').read().split(b'\n')
body = b'\n'.join(l for l in pre if not l.startswith(b'- prereg.md:'))
txt = open(os.path.join(P2, 'prereg.md'), encoding='utf-8').read()
for fn, h in (('prereg.md (own line removed)', hashlib.sha256(body).hexdigest()),
              ('packet_A.md', hashlib.sha256(open(os.path.join(P2, 'packet_A.md'), 'rb').read()).hexdigest()),
              ('packet_B.md', hashlib.sha256(open(os.path.join(P2, 'packet_B.md'), 'rb').read()).hexdigest())):
    print(f'  {fn:30s} {h[:16]} recorded in prereg.md: {h in txt}')
print('  saved outputs:', sorted(os.listdir(os.path.join(P2, 'results'))))
import re
print('  verbatim replies (prereg §4 "<id>.txt"; extracted by the orchestrator, transcripts not archived):')
nv = ok = 0
for d, names in (('blind2', NAMES), ('blind', ['A1', 'A2', 'B1', 'B2'])):
    for n in names:
        vp = os.path.join(HERE, d, 'results', 'verbatim', n + '.reply.txt')
        if not os.path.exists(vp): print(f'    {d}/{n}: MISSING'); continue
        nv += 1; t = open(vp, encoding='utf-8').read()
        i = t.find('{'); k = t.find('}', i); r = json.loads(t[i:k + 1])
        j = json.load(open(os.path.join(HERE, d, 'results', n + '.json')))
        eq = {a: sorted(b) for a, b in r.items()} == {a: sorted(b) for a, b in j.items()}; ok += eq
        rows = sorted({m for m in re.findall(r'^(R\d+)\b', t[k + 1:], flags=re.M)}, key=num)
        print(f'    {d}/{n}: JSON equals saved <id>.json: {eq}; rows with a reason line: {len(rows)} of {len(r)}')
print(f'  verbatim replies present: {nv}/10; JSON equal: {ok}/{nv}')
