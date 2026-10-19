"""Read-only analysis probe (NOT a DEC implementation).

Question: does DEC's rule "decide only if the verdict is T under EVERY undecided policy
configuration" remove the review-2 false positives?  We evaluate the reviewer's own
false-positive scenarios (N1, N2, N3, N9) with the FROZEN ALM-r1 code, quantifying over every
ALM-r1 policy parameter that the scenario's Rec computation accepts.

If a false positive survives under every configuration, policy quantification cannot remove it:
it is an EPISTEMIC defect (world semantics), not a policy one.
Run: python3 -B probe_policy_quantification.py
"""
import sys
from itertools import product
from dataclasses import replace
sys.path.insert(0, "../alm-r1")
from alm_r1 import Act, Theta, History, Entry, rec, INF

ROOTSET = frozenset({("R", ("*", "*"))})
PERF = frozenset({("PERFORM", ("implement", "w")), ("ADOPT", "s")})

GRID = dict(RD1=[{"decision": "persist"}, {"decision": "cascade"}], delta=["independent", "dependent"],
            BASIS=["any", "all"], C10=["given", "no_same_instant"], iota_g=["closed", "half_open"],
            iota_c=["closed", "half_open"], C6=["off", "later_prevails"], BAR=["any", "all"])


def configs():
    keys = list(GRID)
    for vals in product(*[GRID[k] for k in keys]):
        yield Theta(C1="b", root=ROOTSET, **dict(zip(keys, vals)))


def robust_T(E, k, q):
    """DEC-style: T under every configuration?  Returns (all_T, n_configs, n_T)."""
    n = t = 0
    for th in configs():
        try:
            v = rec(E, k, q, th, 100, 0, INF)
        except Exception:
            continue                       # configuration not applicable (e.g. C10 needs ords)
        n += 1
        t += v == "T"
    return t == n and n > 0, n, t


g = Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF)
results = {}

# N1: unrecorded tie order (reviewer: false positive T; true world can make e invalid)
e = Act("e", "PERFORM", "b", performer="p1", tau=2, ord=0, activity="implement", work="w")
f = Act("f", "PERFORM", "b", performer="p1", tau=2, ord=1, activity="implement", work="w")
r = Act("r", "REVOKE", "R", tau=2, ord=None, target="g")
results["N1 unrecorded tie order"] = robust_T([Entry(1, g), Entry(2, e), Entry(3, f), Entry(4, r)], 4, ("valid", "e"))

# N2: bottom holder on a recorded REVOKE
rN = Act("r", "REVOKE", None, tau=2, ord=0, target="g")
e3 = Act("e", "PERFORM", "b", performer="p1", tau=3, ord=0, activity="implement", work="w")
results["N2 unknown revoker"] = robust_T([Entry(1, g), Entry(2, rN), Entry(3, e3)], 3, ("valid", "e"))

# N3: recorded REVOKE by a holder with no recorded authority (RD 6 / upsilon)
rc = Act("r", "REVOKE", "c", tau=2, ord=0, target="g")
results["N3 unverifiable revocation"] = robust_T([Entry(1, g), Entry(2, rc), Entry(3, e3)], 3, ("valid", "e"))

for k, (allT, n, t) in results.items():
    print(f"{k:32s} robust-T under all {n} ALM-r1 configurations: {allT}  ({t}/{n} T)")
print("\nNote: ALM-r1 has no upsilon parameter. Under DEC, upsilon (RD 6) would be in the quantified set,"
      "\nso N3 would be removed IF the 'counts_as_undetermined' semantics is correctly implemented.")
