"""Read-only probe (frozen ALM-r1 code): a perfectly clean case (root grant, then the act; all fields
known) evaluated under C1=b and C1=a, with record times UNKNOWN (as on today's BC-7 store) vs KNOWN."""
import sys
sys.path.insert(0, "../alm-r1")
from alm_r1 import Act, Theta, Entry, rec, INF
ROOT = frozenset({("R", ("*", "*"))})
PERF = frozenset({("PERFORM", ("implement", "w"))})
def th(c1):
    return Theta(C1=c1, RD1={"decision": "persist"}, delta="independent", BASIS="any", C10="given",
                 iota_g="closed", iota_c="closed", C6="off", BAR="any", root=ROOT)
g = Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF)
e = Act("e", "PERFORM", "b", performer="p1", tau=2, ord=0, activity="implement", work="w")
unknown = [Entry(1, g, rho=None), Entry(2, e, rho=None)]
known = [Entry(1, g, rho=1), Entry(2, e, rho=2)]
for name, E in (("record times unknown", unknown), ("record times known", known)):
    vb = rec(E, 2, ("valid", "e"), th("b"), 100, 0, INF, tau_dom=(1, 2, 3))
    va = rec(E, 2, ("valid", "e"), th("a"), 100, 0, INF, tau_dom=(1, 2, 3))
    print(f"{name:22s} C1=b: {vb}   C1=a: {va}   robust over C1: {'T' if vb == va == 'T' else 'not established'}")
