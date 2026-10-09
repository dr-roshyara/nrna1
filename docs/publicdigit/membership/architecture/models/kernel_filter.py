"""Kernel-admissibility filter for the research models (Principal Reviewer 2145 §3).

The REAL Slice 0 kernel (via kernel_admissibility.php) judges the Admit / End / re-Admit
part of each generated history. Slice 0 has NO Annul / Correct / Waive, so histories that
contain them are classed UNCHECKED (only their Admit/End projection is judged, which is a
necessary, not a sufficient, condition). The Python oracles stay independent: the kernel is
used as a filter and as a party under test, never as the oracle.

Classes per history:
  K-ADM  no correction facts; the kernel accepts every op
  K-REJ  the kernel refuses an Admit/End op of the projection (reason recorded)
  UNCHK  correction facts present; projection accepted; corrections not judgeable by Slice 0
Run (repo root): python3 -I docs/publicdigit/membership/architecture/models/kernel_filter.py
"""
import json, os, subprocess, sys, collections
from itertools import product
HERE = os.path.dirname(os.path.abspath(__file__)); ROOT = os.path.abspath(os.path.join(HERE, *['..'] * 5))

def load(name, marker):
    src = open(os.path.join(HERE, name)).read().split(marker)[0]
    ns = {}; exec(src, ns); return ns

def kernel(histories, T):
    out = subprocess.run(['php', os.path.join(HERE, 'kernel_admissibility.php'), str(T)], cwd=ROOT,
                         input=json.dumps(histories), capture_output=True, text=True, check=True).stdout
    return [json.loads(l) for l in out.splitlines()]

CORR = {'annul', 'correct_end'}          # Membership corrections (not in Slice 0). 'waive' is a Finance fact.

def ratified_inv_ep_ok(h):
    """Python re-implementation of the RATIFIED no-overlap invariant (INV-EP / BD-I5) over the final
    facts, honouring BD-REV (correction of an annulled End is inert). NOT the kernel: a labelled
    hypothesis filter for UNCHK histories, so results can be split by it."""
    dead = {f['target'] for f in h if f['k'] == 'annul'}
    live = [f for f in h if f['id'] not in dead]
    ends = {f['period']: f['at'] for f in live if f['k'] == 'end'}
    for f in sorted(live, key=lambda f: f['rec']):
        if f['k'] == 'correct_end' and f['period'] in ends: ends[f['period']] = f['at']
    periods = sorted((f['at'], ends.get(f['id'], 10**9)) for f in live if f['k'] == 'admit')
    return all(periods[i][1] <= periods[i + 1][0] for i in range(len(periods) - 1))
def projection(h):
    ops = [f for f in sorted(h, key=lambda f: f['rec']) if f['k'] in ('admit', 'end')]
    return [dict(k=f['k'], id=f['id'], at=f['at'], rec=f['rec'], **({'period': f['period']} if f['k'] == 'end' else {})) for f in ops]

report = {}

# ---- A: g2_ledger (Finance) -------------------------------------------------------
L = load('g2_ledger.py', 'stats = dict(')
T, FEE = L['T'], L['FEE']
hs = list(L['histories']())
verdicts = kernel([projection(h) for h in hs], T)
cls = []; reasons = collections.Counter(); kernel_vs_oracle = 0; kernel_checked = 0; ex_rej = None
for h, v in zip(hs, verdicts):
    has_corr = any(f['k'] in CORR for f in h)
    if not v['admissible']:
        cls.append('K-REJ'); reasons[v['reason']] += 1
        if ex_rej is None: ex_rej = (projection(h), v)
    elif has_corr: cls.append('UNCHK-INV-EP-ok' if ratified_inv_ep_ok(h) else 'UNCHK-INV-EP-violated')
    else:
        cls.append('K-ADM'); kernel_checked += 1
        membership_truth = L['oracle_due']([f for f in h if f['k'] != 'waive'])   # membership, not money owed
        if any(v['member'][t] != (membership_truth[t] > 0) for t in range(T)): kernel_vs_oracle += 1

def design_stats(h, close, pay):
    truth = L['oracle_due'](h); ledger = []; paid = 0; frozen = None
    for k in range(T):
        known = L['replay_in_force'](h, k)
        for m in range(k + 1):
            net = sum(x[2] for x in ledger if x[1] == m)
            if known(m) != net: ledger.append((k, m, known(m) - net))
        if close is not None and k == close - 1: frozen = [x for x in ledger if x[0] < close]
        newly = sum(x[2] for x in ledger if x[0] == k and x[2] > 0)
        paid += {'none': 0, 'full': newly, 'half': newly // 2}[pay]
    h1 = all(sum(x[2] for x in ledger if x[1] == m) == truth[m] for m in range(T))
    h2 = frozen is None or [x for x in ledger if x[0] < close] == frozen
    stored = sum(FEE for k in range(T) if L['replay_in_force']([f for f in h if f['k'] in ('admit', 'end')], k)(k))
    naive = close is not None and any(L['replay_in_force'](h, close - 1)(m) != truth[m] for m in range(close))
    return h1, h2, stored != sum(truth.values()), naive, paid > sum(truth.values())

per = collections.defaultdict(lambda: collections.Counter())
for h, c in zip(hs, cls):
    for close, pay in product([None, 2, 4], ['none', 'full', 'half']):
        h1, h2, s, n, cr = design_stats(h, close, pay)
        p = per[c]; p['runs'] += 1; p['H1_fail'] += (not h1); p['H2_fail'] += (not h2)
        p['STORED_wrong'] += s; p['NAIVE_closed_mutation'] += n; p['credit'] += cr
report['A_g2_ledger'] = dict(histories=len(hs), classes=dict(collections.Counter(cls)), reject_reasons=dict(reasons),
    kernel_vs_independent_oracle_disagreements=f'{kernel_vs_oracle}/{kernel_checked} (K-ADM)',
    by_class={k: dict(v) for k, v in per.items()}, example_rejected=ex_rej)

# ---- B: g4_reentry (lapse vs suspend) ---------------------------------------------
cases = []
for x in range(6):
    for y in [None] + list(range(x + 1, 7)):
        for e in range(1, 6):
            for g in [None] + list(range(e, 6)):
                ops = [dict(k='admit', id='A', at=0, rec=0), dict(k='end', id='E', period='A', at=e, rec=e)]
                if g is not None: ops.append(dict(k='admit', id='A2', at=g, rec=g))
                cases.append(((x, y, e, g), ops))
vs = kernel([o for _, o in cases], 6)
c = collections.Counter(); kd = 0; ex = None
for ((x, y, e, g), _), v in zip(cases, vs):
    if not v['admissible']: c['K-REJ:' + v['reason']] += 1; continue
    m = lambda t: t < e or (g is not None and t >= g)
    if any(v['member'][t] != m(t) for t in range(6)): kd += 1
    held = lambda t: x <= t and (y is None or t < y)
    sus = [held(t) and v['member'][t] for t in range(6)]
    lap = []; ended = False
    for t in range(6):
        if held(t) and not v['member'][t]: ended = True
        lap.append(held(t) and not ended and v['member'][t])
    c['K-ADM'] += 1
    if lap != sus:
        c['K-ADM lapse!=suspend'] += 1
        if ex is None: ex = dict(tenure=(x, y), end=e, readmit=g)
report['B_g4_reentry'] = dict(cases=len(cases), counts=dict(c), kernel_vs_model_disagreements=kd, example=ex)

# ---- C: g3 access / g2_obligations (single annulment: not judgeable by Slice 0) -----
c3 = collections.Counter()
for e in [None] + list(range(1, 6)):
    ops = [dict(k='admit', id='A', at=0, rec=0)] + ([dict(k='end', id='E', period='A', at=e, rec=e)] if e else [])
    v = kernel([ops], 6)[0]
    c3['projection admissible' if v['admissible'] else 'projection refused'] += 1
report['C_g3_g2obligations'] = dict(note='annulment histories are UNCHK (Slice 0 has no Annul); base Admit/End projections:', counts=dict(c3))

print(json.dumps(report, indent=1, default=str))
