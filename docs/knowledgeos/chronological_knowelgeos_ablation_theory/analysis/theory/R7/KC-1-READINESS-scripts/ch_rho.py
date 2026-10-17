# KC-1-READINESS-AUDIT r2 §13: two relations per row, rho_name and rho_assert, over all 83 rows. Proposal analysis only;
# decides nothing. Existing encodings only (no new row encoding):
#   rho_name   : N-T3 = FZ (T3: denied-tie items read; contrast / absent clauses not read)  [= K1 (a)]
#                N-M  = M  (polarity-blind mention: contrast / absent clauses also read)    [the strict "names" reading]
#   rho_assert : A-T3[p] = FZ outside L, parse p at L   (assertion at T3's level of naming)  [= T3 rest + K1 (b)/(c)]
#                A-C[p]  = K rest + parse p             (C and C-Sigma: definitions read through) [= the C family]
#                p in {SH, K1c, R15ref, R15ref-R14, Sigma}
# Reports: rows where rho_name and rho_assert differ (set level, verdict level per rule); E3-REL and T-A / T-B host cells
# under each; monotonicity (rho_assert subset of rho_name); and a counterexample search for a row-level rule that selects
# the guard list L from row features without a per-row table.
# Reuses readiness.py by import; modifies nothing.  Run from this folder:  python3 -B ch_rho.py ..
import sys, os, io, contextlib, itertools, re
R7 = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else '..')
sys.argv = ['readiness.py', R7]
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
with contextlib.redirect_stdout(io.StringIO()):
    import readiness as RD
B = RD.B; P = RD.P; FZ = B.FZ; TA, TB, TC, TD, ALL = B.TA, B.TB, B.TC, B.TD, B.ALL
RULES = RD.RULES; RN = RD.RN; L = RD.GUARD; num = RD.num
srt = lambda xs: sorted(xs, key=num)
PARSES = {'SH': 'b-SH', 'K1c': 'c-K1c', 'R15ref': 'c-R15ref', 'R15ref-R14': 'c-R15ref-R14', 'Sigma': 'c-Sigma'}
NAME = {'N-T3 (FZ)': FZ, 'N-M (M)': P['M']}
ASSERT = {}
for p, g in PARSES.items():
    ASSERT[f'A-T3[{p}]'] = RD.compose('T3', g)
    ASSERT[f'A-C[{p}]'] = RD.compose('K', g)
V = lambda e, rule: {r: B.side(e, r, rule) for r in ALL}

print('== 1. where the relations differ (set level; verdict level S / S\' / S\'\') ==')
for nn, ne in NAME.items():
    for an, ae in ASSERT.items():
        sd = [r for r in ALL if ne[r] != ae[r]]
        vd = {RN[ru]: [r for r in ALL if B.side(ne, r, ru) != B.side(ae, r, ru)] for ru in RULES}
        print(f'  {nn:10s} vs {an:16s} set-diff {len(sd):2d} rows {sd if len(sd) <= 14 else sd[:14] + ["..."]}')
        print(f'      verdict-diff {vd}')

print('\n== 2. monotonicity: is rho_assert(x) a subset of rho_name(x) for every row? (then coupling-crossing implies crossing) ==')
for nn, ne in NAME.items():
    for an, ae in ASSERT.items():
        bad = [r for r in ALL if not (ae[r][0] <= ne[r][0])]
        print(f'  {nn:10s} > {an:16s}: {"holds" if not bad else "fails at " + str(bad)}'
              + ('' if not bad else '  e.g. ' + ', '.join(f'{r}: +{sorted(ae[r][0] - ne[r][0])}' for r in bad[:3])))

print('\n== 3. E3-REL (D12 a) and host-cell departures from the ratified lists, per relation ==')
for nm, e in list(NAME.items()) + list(ASSERT.items()):
    row = []
    for ru in ('S', 'S1'):
        v = V(e, ru); row.append(f'{RN[ru]}: E3-REL {len(RD.e3rel(v, "a"))} host-diff {len(RD.hostdiff(v, ru))}')
    v1 = V(e, 'S1')
    print(f'  {nm:16s} ' + ' | '.join(row) + f' | E3-REL S\' {list(RD.e3rel(v1, "a"))}')

print('\n== 4. the refined split: crossing / hosting on rho_name, E3-REL as coupling on rho_assert ==')
for an, ae in ASSERT.items():
    if not an.startswith('A-T3'): continue
    for ru in ('S', 'S1'):
        vn, va = V(FZ, ru), V(ae, ru)
        print(f'  hosts from N-T3 (ratified, {len(RD.hostdiff(vn, ru))} departures); E3-REL-coupling from {an:16s} {RN[ru]:3s}:'
              f' {len(RD.e3rel(va, "a"))} {list(RD.e3rel(va, "a"))}; hosted on both but not coupled (r2.1 label fix, N8): {srt(set(RD.e3rel(vn, "a")) - set(RD.e3rel(va, "a")))}')

print('\n== 5. counterexample search: a row-level rule selecting L without a per-row table ==')
# Question: is there a rule over simple row features (T2 r1.4 text) that picks out exactly L = R11, R14-R17, R21 among the
# 83 rows? Features are hand-chosen [E]; the search is exhaustive over conjunctions of <= 3 literals and over disjunctions of
# two conjunctions of <= 2 literals. Validation: exact extension on the 83 rows (no train / test split: 83 rows, no labels
# beyond L itself). The features that read T2's tier cell ("guard", "non-constraint") are flagged as LABEL features.
t2 = open(os.path.join(R7, 'T2-RULE-CANDIDATES.md'), encoding='utf-8').read().splitlines()
ROW = {}; hdr = None
for l in t2:
    if l.startswith('| id |'): hdr = [c.strip() for c in l.strip().strip('|').split('|')]; continue
    m = re.match(r'^\| (R\d+) \|', l)
    if not m or m.group(1) in ROW or hdr is None: continue
    cells = [c.strip() for c in l.strip().strip('|').split('|')]
    d = dict(zip(hdr, cells)); ROW[m.group(1)] = d
def cell(r, start):
    for k, v in ROW[r].items():
        if k.startswith(start): return v
    return ''
NEG = r"\b(no|not|never|without|nothing|none|neither|nor|cannot|absent|absence|does not)\b"
F = {}
for r in ALL:
    st = cell(r, 'statement') or cell(r, 'question'); tier = cell(r, 'tier'); ncol = cell(r, 'N-list')
    title = (re.match(r'\*\*(.*?)\*\*', st) or re.match(r'(.*)', st)).group(1)
    F[r] = {
        'title_starts_No': bool(re.match(r'\s*(\[r[\d.]+\]\s*)?No\b', title)),
        'title_has_neg': bool(re.search(NEG, title, re.I)),
        'stmt_has_neg': bool(re.search(NEG, st, re.I)),
        'stmt_has_does_not_require': bool(re.search(r'does not require|imposes no|never equates|no axiom|no frame axiom', st, re.I)),
        'tier_TA': tier.startswith('T-A'), 'tier_TB': tier.startswith('T-B'),
        'tier_meta': 'meta' in tier,
        'LABEL_tier_guard': 'guard' in tier, 'LABEL_tier_nonconstraint': 'non-constraint' in tier,
        'ncol_guard': 'guard' in ncol.lower(),
    }
FEATS = list(F[ALL[0]])
LIT = [(f, True) for f in FEATS] + [(f, False) for f in FEATS]
target = set(L)
ext = lambda conj: {r for r in ALL if all(F[r][f] == v for f, v in conj)}
hits = []
for k in (1, 2, 3):
    for conj in itertools.combinations(LIT, k):
        if len({f for f, _ in conj}) < k: continue
        if ext(conj) == target: hits.append(('AND', conj))
C2 = [c for k in (1, 2) for c in itertools.combinations(LIT, k) if len({f for f, _ in c}) == k]
E2 = {c: ext(c) for c in C2}
for a, b in itertools.combinations(C2, 2):
    if E2[a] | E2[b] == target and E2[a] and E2[b] and E2[a] != target and E2[b] != target: hits.append(('OR', (a, b)))
lab = lambda h: any('LABEL' in f for part in ([h[1]] if h[0] == 'AND' else h[1]) for f, _ in part)
fmt = lambda conj: ' & '.join(('' if v else '~') + f for f, v in conj)
print(f'  rows {len(ALL)}; features {len(FEATS)}: {FEATS}')
print('  feature extensions (rows where true):')
for f in FEATS: print(f'    {f:28s} {srt(r for r in ALL if F[r][f])}')
nl = [h for h in hits if not lab(h)]
print(f'  exact rules found: {len(hits)}; using a LABEL feature: {len(hits) - len(nl)}; label-free: {len(nl)}')
for h in hits[:12]:
    print('    ', 'AND' if h[0] == 'AND' else 'OR ', fmt(h[1]) if h[0] == 'AND' else ' | '.join('(' + fmt(c) + ')' for c in h[1]), '[LABEL]' if lab(h) else '')
best = min((len(ext(c) ^ target), c) for k in (1, 2, 3) for c in itertools.combinations([x for x in LIT if 'LABEL' not in x[0]], k)
           if len({f for f, _ in c}) == k)
print(f'  best label-free conjunction (<= 3 literals): {fmt(best[1])} -> {srt(ext(best[1]))}; symmetric difference with L: {srt(ext(best[1]) ^ target)}')
# Disclosure: 'stmt_has_does_not_require' is a phrase list written AFTER reading the six guard statements (post hoc); its exact
# fit is therefore partly circular. Re-run without it, and show the near misses that test its boundary (R80, R23).
print('  [post-hoc feature] stmt_has_does_not_require was written after reading L; re-search without it:')
LIT2 = [x for x in LIT if x[0] not in ('stmt_has_does_not_require',) and 'LABEL' not in x[0]]
h2 = [c for k in (1, 2, 3) for c in itertools.combinations(LIT2, k) if len({f for f, _ in c}) == k and ext(c) == target]
C22 = [c for k in (1, 2) for c in itertools.combinations(LIT2, k) if len({f for f, _ in c}) == k]
h2 += [(a, b) for a, b in itertools.combinations(C22, 2) if ext(a) | ext(b) == target and ext(a) and ext(b)]
print(f'    label-free exact rules without it: {len(h2)}')
b2 = min((len(ext(c) ^ target), c) for k in (1, 2, 3) for c in itertools.combinations(LIT2, k) if len({f for f, _ in c}) == k)
print(f'    best: {fmt(b2[1])} -> {srt(ext(b2[1]))}; symmetric difference with L: {srt(ext(b2[1]) ^ target)}')
for r in ('R80', 'R23'):
    st = cell(r, 'statement')
    print(f'    near miss {r}: {re.sub(r"[*]", "", st)[:150]}')
