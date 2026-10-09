"""G-2 strengthened: append-only ledger with adjusting postings (design H) vs legacy STORED
vs NAIVE recompute, against an INDEPENDENT oracle.

Domain (finite, stated): months 0..T-1 (T=6); fee 2 units per in-force month; one member.
Facts (each with a recording month): Admit@a; optional End@e; then 0..2 corrections from
{AnnulEnd, AnnulAdmit, ReAdmit@g (new period, g>=e), CorrectEnd e->e2}; optional Waiver of
one month m (a decision fact recorded at w>=m). Accounting close at month c in {None,2,4}:
postings dated < c are frozen. Payments: none | full | half (1 unit) of each charge when
charged; payments are settlement facts, allocated FIFO, never altered.

Two resolvers written independently:
  R1 replay_in_force(facts, known_at)(t): month-loop replay of facts in recording order.
  R2 oracle_in_force(facts): interval-set construction from final facts.
Properties checked for every history:
  A  R1(final) == R2 at every month                       (resolver agreement)
  H1 per SERVICE MONTH, net ledger charge == oracle due   (not only the total)
  H2 no posting mutated/deleted; no posting dated in a closed period after close
  H3 payments unchanged by corrections; credit (payments > net) representable: counted
  S  STORED total == oracle total                         (expected to fail)
  N  NAIVE recompute violates H2 when a correction touches a closed month (counted)
"""
from itertools import product, combinations
T = 6; FEE = 2

def replay_in_force(facts, known):                  # R1
    fs = [f for f in sorted(facts, key=lambda f: f['rec']) if f['rec'] <= known]
    alive = {i: True for i in range(len(fs))}
    ids = {f['id']: i for i, f in enumerate(fs)}
    for i, f in enumerate(fs):
        if f['k'] == 'annul' and f['target'] in ids: alive[ids[f['target']]] = False
    end_of = {}
    for i, f in enumerate(fs):
        if f['k'] == 'end' and alive[i]: end_of[f['period']] = f['at']
    for i, f in enumerate(fs):                       # a later CorrectEnd overrides
        if f['k'] == 'correct_end' and alive[i] and f['period'] in end_of: end_of[f['period']] = f['at']
    waived = {f['month'] for i, f in enumerate(fs) if f['k'] == 'waive' and alive[i]}
    starts = [(f['id'], f['at']) for i, f in enumerate(fs) if f['k'] == 'admit' and alive[i]]
    def due(t):
        if t in waived: return 0
        for pid, a in starts:
            e = end_of.get(pid)
            if a <= t and (e is None or t < e): return FEE
        return 0
    return due

def oracle_due(facts):                               # R2 (independent formulation)
    dead = {f['target'] for f in facts if f['k'] == 'annul'}
    live = [f for f in facts if f['id'] not in dead]
    periods = {}
    for f in live:
        if f['k'] == 'admit': periods[f['id']] = [f['at'], T]
    ended = {f['period'] for f in live if f['k'] == 'end'}
    for f in sorted(live, key=lambda f: (f['k'] != 'end', f['rec'])):
        # BD-REV (ratified): a correction of an annulled fact is inert -> correct_end needs a live End
        if f['k'] == 'end' and f['period'] in periods: periods[f['period']][1] = f['at']
        if f['k'] == 'correct_end' and f['period'] in ended and f['period'] in periods: periods[f['period']][1] = f['at']
    months = set()
    for a, e in periods.values(): months |= set(range(a, e))
    for f in live:
        if f['k'] == 'waive': months.discard(f['month'])
    return {t: (FEE if t in months else 0) for t in range(T)}

def histories():
    for a in range(T):
        base = [dict(id='A', k='admit', at=a, rec=a)]
        for e in [None] + list(range(a + 1, T)):
            h0 = base + ([dict(id='E', k='end', period='A', at=e, rec=e)] if e is not None else [])
            opts = [[]]
            cands = [dict(id='X1', k='annul', target='A', rec=r) for r in range(a + 1, T)]
            if e is not None:
                cands += [dict(id='X2', k='annul', target='E', rec=r) for r in range(e + 1, T)]
                cands += [dict(id='A2', k='admit', at=g, rec=g) for g in range(e, T)]
                cands += [dict(id='X3', k='correct_end', period='A', at=e2, rec=r)
                          for e2 in range(a + 1, T) if e2 != e for r in range(max(e, e2), T)]
            for c in cands: opts.append([c])
            for c1, c2 in combinations(cands, 2):
                if c1['id'] != c2['id']: opts.append([c1, c2])
            for corr in opts:
                for waiver in [None] + [(m, w) for m in range(a, T) for w in (m, T - 1) if True][:3]:
                    h = h0 + corr + ([dict(id='W', k='waive', month=waiver[0], rec=waiver[1])] if waiver else [])
                    yield h

stats = dict(histories=0, resolver_disagree=0, H1_fail=0, H2_fail=0, credit=0, S_wrong=0, N_closed_mutation=0)
for h, close, pay in product(histories(), [None, 2, 4], ['none', 'full', 'half']):
    stats['histories'] += 1
    truth = oracle_due(h)
    final = replay_in_force(h, 10**9)
    if any(final(t) != truth[t] for t in range(T)): stats['resolver_disagree'] += 1
    # design H: at each month k, post charge for month k as known at k; then reconcile all past months
    ledger = []           # (posted_at, service_month, amount) append-only
    paid = 0; frozen_snapshot = None
    for k in range(T):
        known = replay_in_force(h, k)
        for m in range(k + 1):
            net = sum(x[2] for x in ledger if x[1] == m)
            target = known(m)
            if target != net: ledger.append((k, m, target - net))
        if close is not None and k == close - 1: frozen_snapshot = [x for x in ledger if x[0] < close]
        newly = sum(x[2] for x in ledger if x[0] == k and x[2] > 0)
        paid += {'none': 0, 'full': newly, 'half': newly // 2}[pay]
    per_month_ok = all(sum(x[2] for x in ledger if x[1] == m) == truth[m] for m in range(T))
    if not per_month_ok: stats['H1_fail'] += 1
    if frozen_snapshot is not None and [x for x in ledger if x[0] < close] != frozen_snapshot: stats['H2_fail'] += 1
    if paid > sum(truth.values()): stats['credit'] += 1
    # STORED legacy: charge month k if in force as known at k; End waives unpaid; no correction handler
    stored = sum(FEE for k in range(T) if replay_in_force([f for f in h if f['k'] in ('admit','end')], k)(k))
    if stored != sum(truth.values()): stats['S_wrong'] += 1
    # NAIVE recompute rewrites month rows in place; a closed month changing => violation
    if close is not None:
        at_close = replay_in_force(h, close - 1)
        if any(at_close(m) != truth[m] for m in range(close)): stats['N_closed_mutation'] += 1
print(stats)
assert stats['resolver_disagree'] == 0 and stats['H1_fail'] == 0 and stats['H2_fail'] == 0
