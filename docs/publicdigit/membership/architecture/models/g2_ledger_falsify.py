"""G-2 falsification round (Principal Reviewer 2145 §4): try to BREAK design H
(derived target + append-only ledger + adjusting postings). Histories come from g2_ledger.py
and are split by kernel admissibility (kernel_filter.py classes).

Independent oracle (written here, interval formulation, BD-REV honoured) supports two fee rules:
  LINEAR   2 per in-force month
  JOINING  3 in the FIRST month of each live period, 2 afterwards (non-linear: depends on
           period starts, so corrections that move or annul a start must move the joining fee)
Attacks:
  F1 non-linear rule: H per service month == oracle
  F2 refund then reinstatement: credit is refunded at the month it appears (a settlement fact);
     a later correction reinstates the charge. Check H1 still holds, no settlement mutated, and
     count histories where money refunded is later owed again (policy consequence, not a defect)
  F3 retried reconciliation (same month, twice): must post nothing the second time
  F4 reordered reconciliation (months processed in reverse): same ledger totals per month
  F5 CONCURRENT duplicate reconciliation (two workers read the same snapshot and both post):
     without an idempotency key H double-posts; with key (service month, target version) it holds
Unmodelled (labelled, not claimed): interest, dunning, partial-month pro-rata by days, multiple
members, currency, accounting-period re-opening, payments allocated to specific months.
Run (repo root): python3 -I docs/publicdigit/membership/architecture/models/g2_ledger_falsify.py
"""
import collections, json, os
from itertools import product
HERE = os.path.dirname(os.path.abspath(__file__))
def load(name, marker):
    ns = {'__file__': os.path.join(HERE, name)}; exec(open(os.path.join(HERE, name)).read().split(marker)[0], ns); return ns
L = load('g2_ledger.py', 'stats = dict(')
K = load('kernel_filter.py', 'report = {}')
T = L['T']

def oracle(h, rule, known=10**9):
    """Independent: interval construction over facts recorded <= known."""
    fs = [f for f in h if f['rec'] <= known]
    dead = {f['target'] for f in fs if f['k'] == 'annul'}
    live = [f for f in fs if f['id'] not in dead]
    ends = {f['period']: f['at'] for f in live if f['k'] == 'end'}
    for f in sorted(live, key=lambda f: f['rec']):
        if f['k'] == 'correct_end' and f['period'] in ends: ends[f['period']] = f['at']   # BD-REV
    waived = {f['month'] for f in live if f['k'] == 'waive'}
    due = {t: 0 for t in range(T)}
    for f in live:
        if f['k'] != 'admit': continue
        for t in range(f['at'], min(ends.get(f['id'], T), T)):
            due[t] = max(due[t], (3 if (rule == 'JOINING' and t == f['at']) else 2))
    for m in waived: due[m] = 0
    return due

def run_H(h, rule, refund=False, retry=False, reverse=False, concurrent=False, keyed=False):
    """keyed: False | 'content' (service month, target value) | 'version' (service month, source log version)."""
    ledger, settlements, keys = [], [], set()
    def reconcile(k, snapshot=None):
        target = oracle(h, rule, k)          # target as known at k (the rule engine's job)
        base = ledger if snapshot is None else snapshot
        posts = []
        months = range(k, -1, -1) if reverse else range(k + 1)
        for m in months:
            net = sum(x[2] for x in base if x[1] == m)
            if target[m] != net:
                version = sum(1 for f in h if f['rec'] <= k)          # Membership facts known at k
                key = (m, tuple(sorted(target.items()))) if keyed == 'content' else (m, version)
                if keyed and key in keys: continue
                keys.add(key); posts.append((k, m, target[m] - net))
        return posts
    refunded_then_owed = False
    for k in range(T):
        if concurrent:
            snap = list(ledger); a = reconcile(k, snap); b = reconcile(k, snap); ledger.extend(a + b)
        else:
            ledger.extend(reconcile(k))
            if retry: ledger.extend(reconcile(k))
        charges = sum(x[2] for x in ledger)
        paid = sum(s[1] for s in settlements)
        if k < T - 1: settlements.append((k, max(0, charges - paid)))            # member pays what is due
        if refund and paid > charges:
            settlements.append((k, charges - paid))                               # refund settlement (negative)
            refunded_then_owed = refunded_then_owed or False
    truth = oracle(h, rule)
    h1 = all(sum(x[2] for x in ledger if x[1] == m) == truth[m] for m in range(T))
    return h1, ledger, settlements

out = collections.defaultdict(collections.Counter); ex = {}
hs = list(L['histories']())
verdicts = K['kernel']([K['projection'](h) for h in hs], T)
for h, v in zip(hs, verdicts):
    has_corr = any(f['k'] in K['CORR'] for f in h)
    c = 'K-REJ' if not v['admissible'] else ('UNCHK-INV-EP-ok' if K['ratified_inv_ep_ok'](h) else 'UNCHK-INV-EP-violated') if has_corr else 'K-ADM'
    o = out[c]; o['histories'] += 1
    for rule in ('LINEAR', 'JOINING'):
        ok, _, _ = run_H(h, rule)
        if not ok: o[f'F1 {rule} H1_fail'] += 1; ex.setdefault('F1', h)
    ok, led, st = run_H(h, 'JOINING', refund=True)
    if not ok: o['F2 H1_fail'] += 1
    if any(s[1] < 0 for s in st):
        o['F2 refund issued'] += 1
        first_refund = min(s[0] for s in st if s[1] < 0)
        if any(x[0] > first_refund and x[2] > 0 for x in led): o['F2 refunded then owed again'] += 1; ex.setdefault('F2', h)
    if run_H(h, 'JOINING')[1] != run_H(h, 'JOINING', retry=True)[1]: o['F3 retry changed ledger'] += 1
    a, b = run_H(h, 'JOINING')[1], run_H(h, 'JOINING', reverse=True)[1]
    if collections.Counter((x[1]) for x in a) and {m: sum(x[2] for x in a if x[1] == m) for m in range(T)} != {m: sum(x[2] for x in b if x[1] == m) for m in range(T)}:
        o['F4 reorder changed totals'] += 1
    if not run_H(h, 'JOINING', concurrent=True)[0]: o['F5 concurrent unkeyed H1_fail'] += 1; (ex.setdefault('F5', h))
    if not run_H(h, 'JOINING', concurrent=True, keyed='content')[0]:
        o['F5 concurrent content-keyed H1_fail'] += 1
        if 'F5c' not in ex or len(h) < len(ex['F5c']): ex['F5c'] = h
    if not run_H(h, 'JOINING', concurrent=True, keyed='version')[0]: o['F5 concurrent version-keyed H1_fail'] += 1
print(json.dumps({k: dict(v) for k, v in out.items()}, indent=1))
print('minimal examples:', json.dumps({k: v for k, v in ex.items()}, default=str)[:1500])
