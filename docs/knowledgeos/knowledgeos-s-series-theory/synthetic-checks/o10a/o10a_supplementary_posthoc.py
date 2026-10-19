#!/usr/bin/env python3
"""POST-HOC supplementary run (not in PROTOCOL.md): coarser decisions (one binary decision, biased towards 0) so that
the feasible prefix-closed family is large and the 'greatest element' test has power. Same checks C1, C2, C4."""
import random, sys
sys.path.insert(0, ".")
import o10a_checks as X

rnd = random.Random(20261018)
for kind in ("all", "random prefix-closed"):
    res = []
    for _ in range(200):
        L = X.all_hist(X.N) if kind == "all" else X.random_prefix_closed(rnd, X.N)
        d = {h: int(rnd.random() < 0.2) for h in L}
        Dsig = {h: (d[h],) for h in L}
        feas = X.pc_feasible(L, Dsig)
        st = X.star(L, Dsig)
        c2 = (any(X.subset_rel(st, f, L) and X.subset_rel(f, st, L) for f in feas)
              and all(X.subset_rel(f, st, L) for f in feas))
        s = {h: rnd.randrange(2) for h in L}
        c4 = (any(not X.determines(f, s, L) for f in feas)) == (not X.determines(st, s, L))
        res.append((c2, c4, len(feas)))
    print(f"POST-HOC L={kind}: C2 {sum(r[0] for r in res)}/200  C4 {sum(r[1] for r in res)}/200  "
          f"feasible per instance: min {min(r[2] for r in res)} median {sorted(r[2] for r in res)[100]} max {max(r[2] for r in res)}")
