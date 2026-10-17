"""G-7 Governance aggregate boundary: committee-local vs cross-committee invariants. Research only.

Commission: principal review of the consolidated cross-domain architecture (2026-10-10), Finding 4.
No production code. Counts are enumerated interleavings, NOT probabilities.

Two concurrent Appoint commands for one person p:
   cmd1 appoints p to position s1 in committee C1, cmd2 appoints p to position s2 in committee C2.
   Each command: READ (loads state + versions) -> CHECK (invariants on what it read) -> COMMIT (expected versions).
Invariants:
   INV-P3 (committee-local): |holders(s)| <= capacity(s)                    -- exercised in Q2
   INV-P6 (cross-committee): p never holds an incompatible pair (s1, s2)    -- exercised in Q1
Strategies:
   S-GA       : one aggregate per Committee (candidate G-A); INV-P6 checked against what was read.
   S-GA+RES   : G-A + a per-(person, organisation) PositionHoldings reservation aggregate; every Appoint also
                commits to it with its expected version (a small coordinating aggregate).
   S-ORG      : one Governance aggregate per organisation (everything serialised).
Run: python3 -I docs/publicdigit/membership/architecture/models/g7_governance_aggregate_boundary.py
"""
from itertools import permutations
import json

STEPS = [('c1', 'R'), ('c1', 'K'), ('c1', 'W'), ('c2', 'R'), ('c2', 'K'), ('c2', 'W')]


def interleavings():
    seen = set()
    for perm in permutations(STEPS):
        ok = all(perm.index((c, 'R')) < perm.index((c, 'K')) < perm.index((c, 'W')) for c in ('c1', 'c2'))
        if ok and perm not in seen:
            seen.add(perm)
            yield perm


def run(order, strategy, cross):
    """cross=True: Q1 (different committees, incompatible pair). cross=False: Q2 (same committee, capacity 1,
    two different persons competing for the one seat)."""
    agg_of = ({'c1': 'C1', 'c2': 'C2'} if cross else {'c1': 'C1', 'c2': 'C1'})
    if strategy == 'S-ORG':
        agg_of = {'c1': 'ORG', 'c2': 'ORG'}
    version = {'C1': 0, 'C2': 0, 'ORG': 0, 'RES': 0}
    holders = set()                                        # committed (cmd) appointments
    read = {}
    for cmd, step in order:
        if step == 'R':
            read[cmd] = dict(holders=set(holders), v=dict(version))
        elif step == 'K':
            other = 'c2' if cmd == 'c1' else 'c1'
            read[cmd]['ok'] = other not in read[cmd]['holders']    # both rules reject if the other is visible
        else:
            r = read[cmd]
            if not r['ok']:
                continue
            touched = [agg_of[cmd]] + (['RES'] if (strategy == 'S-GA+RES' and cross) else [])
            if any(version[a] != r['v'][a] for a in touched):
                continue                                   # optimistic-concurrency conflict -> rejected
            for a in touched:
                version[a] += 1
            holders.add(cmd)
    return holders


out = {}
for label, cross in (('Q1_cross_committee_incompatibility', True), ('Q2_local_capacity', False)):
    res = {}
    for strategy in ('S-GA', 'S-GA+RES', 'S-ORG'):
        n = viol = 0
        for order in interleavings():
            n += 1
            if len(run(order, strategy, cross)) == 2:      # both committed => invariant broken
                viol += 1
        res[strategy] = dict(interleavings=n, violations=viol)
    out[label] = res
# ---- Q3 (added 2026-10-10, v1.3): three concurrent commands, both invariants at once -----------------
# c1: p -> s1 (C1); c2: p -> s2 (C2), incompatible with s1; c3: q -> s1 (C1), capacity(s1) = 1.
CMDS3 = {'c1': ('p', 'C1'), 'c2': ('p', 'C2'), 'c3': ('q', 'C1')}
CONFLICTS3 = {('c1', 'c2'), ('c2', 'c1'), ('c1', 'c3'), ('c3', 'c1')}


def interleavings3():
    steps = {c: [(c, 'R'), (c, 'K'), (c, 'W')] for c in CMDS3}
    def rec(rem, acc):
        if not any(rem.values()):
            yield list(acc); return
        for c in rem:
            if rem[c]:
                acc.append(rem[c][0]); rem[c] = rem[c][1:]
                yield from rec(rem, acc)
                rem[c] = [acc.pop()] + rem[c]
    yield from rec({c: list(v) for c, v in steps.items()}, [])


def run3(order, strategy):
    def touched(c):
        person, comm = CMDS3[c]
        if strategy == 'S-ORG':
            return ['ORG']
        return [comm] + (['RES_' + person] if strategy == 'S-GA+RES' else [])
    version, committed, read = {}, set(), {}
    for c, step in order:
        if step == 'R':
            read[c] = dict(seen=set(committed), v={a: version.get(a, 0) for a in touched(c)})
        elif step == 'K':
            read[c]['ok'] = not any((c, o) in CONFLICTS3 for o in read[c]['seen'])
        else:
            r = read[c]
            if r['ok'] and all(version.get(a, 0) == r['v'][a] for a in touched(c)):
                for a in touched(c):
                    version[a] = version.get(a, 0) + 1
                committed.add(c)
    return committed


q3 = {}
for strategy in ('S-GA', 'S-GA+RES', 'S-ORG'):
    n = inc = cap = 0
    for order in interleavings3():
        n += 1
        done = run3(order, strategy)
        inc += {'c1', 'c2'} <= done
        cap += {'c1', 'c3'} <= done
    q3[strategy] = dict(interleavings=n, incompatibility_violations=inc, capacity_violations=cap)
out['Q3_three_commands'] = q3
out['note'] = ('S-ORG serialises every Governance write of an organisation; S-GA+RES serialises only the '
               'appointments of the same person. Contention cost is not modelled here.')
print(json.dumps(out, indent=1))
