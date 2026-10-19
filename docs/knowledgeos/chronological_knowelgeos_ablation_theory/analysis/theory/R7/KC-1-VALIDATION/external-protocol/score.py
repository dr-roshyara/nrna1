# PROPOSED / NOT RATIFIED; materials only; not executed.
# KC-1 external blind replication: scorer (analysis-plan.md is the authority; this script implements it).
#
# Usage (run from external-protocol/, always with -B so that no __pycache__ is written):
#   python3 -B score.py --manifest <manifest.csv> --key key_PRIVATE.md [--base <dir>]
#   python3 -B score.py --oc            # operating characteristics of the decision rule (analysis-plan.md section 3)
#
# Manifest: CSV, '#' lines are comments; columns  encoder_id,arm,identity_class,cluster,sheet_path
#   arm            T3 | SH | PARSE | PARSE_B | GEN | any other label (scored key-free)
#   identity_class "human" or "model:<family>" ; any Claude-family entry is flagged INELIGIBLE (dry runs only)
#   cluster        the correlation cluster (model family, or human cohort / training group)
#   sheet_path     answer sheet (answer_sheet_template.md format) or a raw reply whose first JSON object is the answer
#
# The ENCODER gives item sets only. The SCORER derives each guard-row verdict from the item set with the published
# placement table below (analysis-plan.md section 1.2). Nothing here is shown to encoders.
import sys, os, re, json, math, csv, argparse, itertools
from collections import Counter

# ---------------- published placement table (T3 r1.9 section 1a; analysis-plan.md section 1.2) ----------------
CB1 = {13, 14, 15, 16, 17, 18, 23, 24, 27}
CB23 = {2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 19, 20, 21}
NO_SIDE = {22, 25, 26, 28}           # C22 unplaced; C25, C26, C28 in the open slot: contribute nothing
SPANNING = {1}                       # C1: S' (primary) counts it on neither side; S counts it on both
GUARD = ['R11', 'R14', 'R15', 'R16', 'R17', 'R21']
CONTROLS = ['R1', 'R6', 'R9', 'R79', 'R80']
ROWS = GUARD + CONTROLS
CATS = ['CB1', 'CB23', 'X', 'none']
CID = re.compile(r'^C([1-9]|1[0-9]|2[0-8])$')

def verdict(items, rule="S'"):
    """items: set of ints (C-numbers). Returns CB1 / CB23 / X / none."""
    s = set()
    for c in items:
        if c in CB1: s.add('CB1')
        elif c in CB23: s.add('CB23')
        elif c in SPANNING and rule == 'S': s |= {'CB1', 'CB23'}
    return 'X' if len(s) == 2 else (s.pop() if s else 'none')

# ---------------- answer-sheet parsing ----------------
def first_json_object(t):
    i = t.find('{')
    while i != -1:
        depth = 0
        for k in range(i, len(t)):
            if t[k] == '{': depth += 1
            elif t[k] == '}':
                depth -= 1
                if depth == 0:
                    try: return json.loads(t[i:k + 1])
                    except json.JSONDecodeError: break
        i = t.find('{', i + 1)
    return None

def parse_sheet(path):
    """Returns (rows, notes). rows: {row: set(int)} or {row: None} for an ambiguous row; None if the sheet is invalid."""
    t = open(path, encoding='utf-8').read()
    m = re.search(r'## B\. Encoder response.*?```json\s*(\{.*?\})\s*```', t, flags=re.S)
    obj = None
    if m:
        try: obj = json.loads(m.group(1))
        except json.JSONDecodeError: obj = None
    if obj is None: obj = first_json_object(t)
    notes = []
    if not isinstance(obj, dict): return None, ['INVALID: no parseable JSON object']
    missing = [r for r in ROWS if r not in obj]
    extra = [r for r in obj if r not in ROWS]
    if extra: notes.append(f'extra keys ignored: {extra}')
    if missing: return None, notes + [f'INVALID: rows missing {missing}']
    rows = {}
    for r in ROWS:
        v = obj[r]
        if isinstance(v, list) and all(isinstance(x, str) and CID.match(x.strip()) for x in v):
            rows[r] = {int(x.strip()[1:]) for x in v}
        else:
            rows[r] = None; notes.append(f'{r} AMBIGUOUS {v!r} (counted as non-agreement)')
    return rows, notes

# ---------------- statistics ----------------
def binom_cdf(k, n, p):
    return sum(math.comb(n, i) * p ** i * (1 - p) ** (n - i) for i in range(0, k + 1))

def clopper_pearson(k, n, alpha=0.05):
    if n == 0: return (float('nan'), float('nan'))
    def solve(f, lo=0.0, hi=1.0):
        for _ in range(200):
            mid = (lo + hi) / 2
            if f(mid): hi = mid
            else: lo = mid
        return (lo + hi) / 2
    lower = 0.0 if k == 0 else solve(lambda p: 1 - binom_cdf(k - 1, n, p) >= alpha / 2)
    upper = 1.0 if k == n else solve(lambda p: binom_cdf(k, n, p) <= alpha / 2)
    return lower, upper

def fleiss_kappa(table):
    """table: list of per-item Counters over CATS, equal n per item. Descriptive only (analysis-plan.md 3.4)."""
    if not table: return None
    n = sum(table[0].values()); N = len(table)
    if n < 2 or any(sum(c.values()) != n for c in table): return None
    P_i = [(sum(v * v for v in c.values()) - n) / (n * (n - 1)) for c in table]
    p_j = {cat: sum(c[cat] for c in table) / (N * n) for cat in CATS}
    Pbar = sum(P_i) / N; Pe = sum(v * v for v in p_j.values())
    return None if Pe == 1 else (Pbar - Pe) / (1 - Pe)

def threshold(n):
    return math.ceil(0.8 * n - 1e-9)

# ---------------- key ----------------
def read_key(path):
    t = open(path, encoding='utf-8').read()
    m = re.search(r'```key\n(.*?)```', t, flags=re.S)
    key = {}
    for line in m.group(1).splitlines():
        line = line.split('#')[0].strip()
        if not line: continue
        g, c = line.split('|')
        g = g.split(); c = c.split()
        key[g[0]] = dict(zip(GUARD, g[1:])); key[g[0]].update(dict(zip(CONTROLS, c)))
    return key

# ---------------- arm analysis ----------------
def classify(counts_by_row, key, n):
    thr = threshold(n)
    rep = key is not None and all(counts_by_row[r][key[r]] >= thr for r in GUARD)
    det = all(max(counts_by_row[r].values(), default=0) >= thr for r in GUARD)
    if rep: lab = 'REPRODUCED'
    elif det: lab = 'DETERMINATE-OTHER' if key is not None else 'DETERMINATE (no key)'
    else: lab = 'INDETERMINATE'
    return lab, thr

def analyse(arm, recs, key):
    print(f'\n==================== arm {arm} ====================')
    valid = [r for r in recs if r['rows'] is not None]
    for r in recs:
        flag = '  INELIGIBLE (Claude-family; dry run only)' if 'claude' in r['identity'].lower() else ''
        print(f"  {r['id']:>10s} {r['identity']:<28s} cluster {r['cluster']:<12s} {'valid' if r['rows'] is not None else 'INVALID'}{flag}"
              + ('' if not r['notes'] else '  [' + '; '.join(r['notes']) + ']'))
    n = len(valid)
    clusters = Counter(r['cluster'] for r in valid)
    print(f'  n_valid = {n}; clusters = {dict(clusters)}')
    big = [c for c, k in clusters.items() if n and k / n > 0.40]
    if big: print(f'  FLAG: cluster(s) {big} exceed 40% of the arm (analysis-plan.md 3.5)')
    V = {r['id']: {row: (verdict(r['rows'][row]) if r['rows'][row] is not None else 'AMBIG') for row in ROWS} for r in valid}
    VS = {r['id']: {row: (verdict(r['rows'][row], 'S') if r['rows'][row] is not None else 'AMBIG') for row in ROWS} for r in valid}
    sdiff = [(e, row) for e in V for row in GUARD if V[e][row] != VS[e][row]]
    print(f"  S vs S' at guard rows: {'identical' if not sdiff else 'DIFFER at ' + str(sdiff)}")
    print("\n  -- primary unit: guard-row verdict (S'), derived by the scorer --")
    print(f"  {'row':5s} {'key':5s} {'counts':40s} {'modal':12s} {'k/n key':8s} {'95% CP (key)':16s} unanimous")
    counts = {}
    for row in GUARD:
        c = Counter(V[e][row] for e in V); counts[row] = c
        k = c[key[row]] if key else None
        mc = c.most_common(); modal = mc[0][0] if mc else '-'
        ties = mc and len(mc) > 1 and mc[0][1] == mc[1][1]
        ci = clopper_pearson(k, n) if key and n else None
        print(f"  {row:5s} {(key[row] if key else '-'):5s} {str(dict(c)):40s} {modal + (' (tie)' if ties else ''):12s} "
              f"{(f'{k}/{n}' if key else '-'):8s} {(f'[{ci[0]:.2f}, {ci[1]:.2f}]' if ci else '-'):16s} {len(c) == 1}")
    if n < 4:
        label = f'INCONCLUSIVE (n_valid = {n} < 4; descriptive only)'; thr = None
    else:
        label, thr = classify(counts, key, n)
        if n == 4: label = 'PROVISIONAL ' + label
    print(f"\n  DECISION (analysis-plan.md 2): {label}" + (f'   [threshold {thr}/{n} per row]' if thr else ''))
    if thr and label.endswith('INDETERMINATE'):
        print('  rows below threshold:', [row for row in GUARD if max(counts[row].values()) < thr])
    # cluster-collapsed sensitivity
    cl = sorted(clusters)
    if len(cl) >= 3 and n >= 4:
        cc = {}
        for row in GUARD:
            modal = []
            for c_ in cl:
                vs = Counter(V[r['id']][row] for r in valid if r['cluster'] == c_).most_common()
                modal.append('TIE' if len(vs) > 1 and vs[0][1] == vs[1][1] else vs[0][0])
            cc[row] = Counter(modal)
        lab2, thr2 = classify(cc, key, len(cl))
        print(f'  cluster-collapsed sensitivity ({len(cl)} clusters, threshold {thr2}/{len(cl)}): {lab2}'
              + ('' if lab2 == label.replace('PROVISIONAL ', '') else '  -> report as FAMILY-DEPENDENT'))
    else:
        print(f'  cluster-collapsed sensitivity: not possible ({len(cl)} cluster(s), n_valid {n}; needs >= 3 clusters and n_valid >= 4)')
    # Fleiss kappa (descriptive only)
    complete = [e for e in V if all(V[e][row] != 'AMBIG' for row in GUARD)]
    kap = fleiss_kappa([Counter(V[e][row] for e in complete) for row in GUARD]) if len(complete) >= 2 else None
    print(f"  Fleiss' kappa over 6 guard rows x {len(complete)} raters (DESCRIPTIVE ONLY; see caveats): "
          + ('undefined (no variation in the margins)' if kap is None else f'{kap:.3f}'))
    # secondary unit
    print('\n  -- secondary unit: exact item-set match --')
    for row in ROWS:
        sets = [frozenset(r['rows'][row]) if r['rows'][row] is not None else None for r in valid]
        pairs = list(itertools.combinations(range(len(sets)), 2))
        agree = sum(1 for i, j in pairs if sets[i] is not None and sets[i] == sets[j])
        mc = Counter(s for s in sets if s is not None).most_common(1)
        modal = sorted(mc[0][0]) if mc else []
        print(f"  {row:4s} {'guard  ' if row in GUARD else 'control'} pairwise exact {agree}/{len(pairs)}; "
              f"modal set {['C%d' % i for i in modal]} held by {mc[0][1] if mc else 0}/{n}")
    print("\n  -- controls (descriptive; enter no decision): verdict S' --")
    for row in CONTROLS:
        c = Counter(V[e][row] for e in V)
        print(f"  {row:4s} key {(key[row] if key else '-'):5s} {dict(c)}")
    return label

def oc_table():
    print('Operating characteristics of the per-arm rule "every one of the 6 rows has >= ceil(0.8 n) encoders on the key verdict".')
    print('p = per-encoder probability of giving the key verdict on a row, assumed equal and INDEPENDENT across encoders and rows')
    print('(both assumptions are optimistic; analysis-plan.md 3.5). P(row) = P(Bin(n,p) >= thr); P(arm) = P(row)^6.')
    print(f"{'n':>3s} {'thr':>4s} " + ' '.join(f'p={p:<4}: row / arm   ' for p in (0.95, 0.9, 0.8, 0.7, 0.6, 0.5)))
    for n in (3, 4, 5, 6, 8, 10, 12, 15):
        thr = threshold(n)
        cells = []
        for p in (0.95, 0.9, 0.8, 0.7, 0.6, 0.5):
            pr = 1 - binom_cdf(thr - 1, n, p)
            cells.append(f'{pr:.3f} / {pr ** 6:.3f}      ')
        print(f'{n:3d} {thr:4d} ' + ' '.join(cells))
    print('\nOne row at p = 0.6 and five rows at p = 1 (a single genuinely split row): P(arm) = P(row at 0.6):')
    for n in (5, 8, 10):
        thr = threshold(n); print(f'  n = {n:2d}, thr = {thr}: {1 - binom_cdf(thr - 1, n, 0.6):.3f}')
    print('\nExact 95% Clopper-Pearson interval when every encoder agrees (k = n):')
    for n in (3, 5, 8, 10, 15):
        lo, hi = clopper_pearson(n, n); print(f'  {n:2d}/{n:<2d}: [{lo:.3f}, {hi:.3f}]')
    print('and at the threshold:')
    for n in (5, 8, 10):
        k = threshold(n); lo, hi = clopper_pearson(k, n); print(f'  {k}/{n}: [{lo:.3f}, {hi:.3f}]')

def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--manifest'); ap.add_argument('--key'); ap.add_argument('--base', default='.')
    ap.add_argument('--oc', action='store_true')
    a = ap.parse_args()
    if a.oc: oc_table(); return
    key = read_key(a.key)
    recs = {}
    with open(a.manifest, encoding='utf-8') as f:
        for row in csv.reader(l for l in f if l.strip() and not l.lstrip().startswith('#')):
            eid, arm, ident, cluster, path = [x.strip() for x in row]
            if eid == 'encoder_id': continue          # column header
            rows, notes = parse_sheet(os.path.join(a.base, path))
            recs.setdefault(arm, []).append({'id': eid, 'identity': ident, 'cluster': cluster, 'rows': rows, 'notes': notes})
    print('KC-1 external replication scorer. Primary unit: guard-row verdict (S\'), derived by the scorer from item sets via the')
    print('published placement table; secondary: exact item-set match. No significance test is computed (analysis-plan.md 3).')
    out = {arm: analyse(arm, recs[arm], key.get(arm)) for arm in recs}
    print('\n==================== summary ====================')
    for arm, lab in out.items(): print(f'  {arm:16s} {lab}')

if __name__ == '__main__':
    main()
