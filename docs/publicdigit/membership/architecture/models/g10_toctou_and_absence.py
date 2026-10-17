"""G-10 authorization at the write boundary (TOCTOU) and negative knowledge in the read set. Research only.

Commission: principal review of cross-domain architecture v1.1 (2026-10-10), §2.3 (TOCTOU) and §2.4 (negative knowledge).
No production code. Counts are enumerated interleavings / histories, NOT probabilities.

Q1 TOCTOU. A forum post needs EffectiveTenure (initially true, version 0). A concurrent writer applies a
   sequence of 1..2 Governance changes (revoke, reinstate), each an atomic commit that bumps the tenure version.
   UNAUTHORISED = the post commits while the tenure is NOT effective at the post's commit point.
   S0 CHECK_THEN_WRITE       : decide, then write (no precondition).
   S1 RECHECK_UNLOCKED       : decide, re-check the version, then write (two separate steps).
   S2 ATOMIC_PRECONDITION    : decide, then one atomic step: re-check read-set versions AND write.
   S3 CACHED_ATOMIC          : decision taken from a cache entry built before the request; atomic commit as S2.
   S2v / S3v use VALUE comparison instead of VERSION comparison (shows the ABA case).
Q2 Negative knowledge. Predicate NotSuspended(p) over a log of Suspend / Restore facts.
   RS-FACTS  : the read set records only the facts the query returned (empty when no Suspend exists).
   RS-AGG    : the read set records the subject aggregate's version (log length) = footprint version.
   Check (a) replay AS_DECIDED reproduces the decision; (b) a cached decision trusted after a later append is stale.
Run: python3 -I docs/publicdigit/membership/architecture/models/g10_toctou_and_absence.py
"""
from itertools import product
import json

out = {}

# ---- Q1 -------------------------------------------------------------------------------
def post_steps(strategy):
    return {'S0': ['DECIDE', 'WRITE'], 'S1': ['DECIDE', 'RECHECK', 'WRITE'],
            'S2': ['DECIDE', 'ATOMIC'], 'S3': ['ATOMIC'],
            'S2v': ['DECIDE', 'ATOMIC'], 'S3v': ['ATOMIC']}[strategy]


def merges(a, b):
    if not a:
        yield list(b); return
    if not b:
        yield list(a); return
    for rest in merges(a[1:], b):
        yield [a[0]] + rest
    for rest in merges(a, b[1:]):
        yield [b[0]] + rest


def run(strategy, order):
    effective, version = True, 0
    seen_eff, seen_ver = True, 0            # decision's view (S3: cache built at version 0, effective)
    ok = True
    wrote, unauthorised = False, False
    for kind, step in order:
        if kind == 'G':
            effective = (step == 'reinstate')
            version += 1
            continue
        if step == 'DECIDE':
            seen_eff, seen_ver = effective, version
            ok = seen_eff
        elif step == 'RECHECK':
            ok = ok and version == seen_ver
        elif step == 'WRITE':
            if ok:
                wrote, unauthorised = True, not effective
        elif step == 'ATOMIC':
            same = (effective == seen_eff) if strategy.endswith('v') else (version == seen_ver)
            if ok and seen_eff and same:
                wrote, unauthorised = True, not effective
    return wrote, unauthorised


q1 = {}
for strategy in ('S0', 'S1', 'S2', 'S3', 'S2v', 'S3v'):
    n = unauth = refused = 0
    for gov in (['revoke'], ['revoke', 'reinstate']):
        for order in merges([('P', s) for s in post_steps(strategy)], [('G', g) for g in gov]):
            n += 1
            wrote, bad = run(strategy, order)
            unauth += bad
            refused += not wrote
    q1[strategy] = dict(interleavings=n, unauthorised_writes=unauth, refused=refused)
out['Q1_toctou'] = q1

# ---- Q2 -------------------------------------------------------------------------------
def not_suspended(log):
    s = False
    for f in log:
        s = (f == 'S')
    return not s


q2 = {k: dict(replay_mismatch=0, stale_trusted=0) for k in ('RS-FACTS', 'RS-AGG')}
cases = 0
for L in range(0, 4):
    for log in product('SR', repeat=L):                     # S = Suspend, R = Restore
        for i in range(L + 1):                              # decision taken after the first i facts
            decided = not_suspended(log[:i])
            for later in range(L - i + 1):                  # facts appended after the decision
                cases += 1
                now = log[:i + later]
                # read set at decision time
                facts_rs = tuple(f for f in log[:i] if f == 'S')      # only returned (matching) facts
                agg_rs = i                                            # footprint version
                # replay AS_DECIDED
                replay_facts = not_suspended(now) if not facts_rs else not_suspended(log[:i])
                replay_agg = not_suspended(now[:agg_rs])
                q2['RS-FACTS']['replay_mismatch'] += (replay_facts != decided)
                q2['RS-AGG']['replay_mismatch'] += (replay_agg != decided)
                # cache validity: RS-FACTS trusts if the returned facts are still present (unchanged);
                # RS-AGG trusts only if the aggregate version is unchanged
                trust_facts = tuple(f for f in now[:i] if f == 'S') == facts_rs
                trust_agg = len(now) == agg_rs
                truth = not_suspended(now)
                q2['RS-FACTS']['stale_trusted'] += (trust_facts and truth != decided)
                q2['RS-AGG']['stale_trusted'] += (trust_agg and truth != decided)
q2['cases'] = cases
out['Q2_negative_knowledge'] = q2
print(json.dumps(out, indent=1))
