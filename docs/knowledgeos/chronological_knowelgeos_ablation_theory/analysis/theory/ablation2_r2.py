"""ablation2 r2 (disclosed revision after the first run): the frozen special-case measure (connected components of the conflict
graph) is degenerate — with few fields the conflicts merge into one component, so cost is undercounted and {e} looked optimal.
r2 measure: special_cases = the MINIMUM number of events that must be carved out as row-level exceptions so that the rest is a
function of M's fields = the minimum vertex cover of the conflict graph. The graph is bipartite (PERFORMED vs REFUSED), so by
König's theorem it equals the maximum matching. ablation2.py (frozen) is imported read-only; its data are unchanged."""
import importlib.util, itertools, json, os
s = importlib.util.spec_from_file_location("a2", os.path.join(os.path.dirname(os.path.abspath(__file__)), "ablation2.py")); a2 = importlib.util.module_from_spec(s); s.loader.exec_module(a2)
def max_matching(pairs):
    L = {}; ev = {e["id"]: e for e in a2.EV}
    for p, q in pairs:
        perf, ref = (p, q) if ev[p]["out"] == "PERFORMED" else (q, p)
        L.setdefault(perf, set()).add(ref)
    match = {}
    def aug(u, seen):
        for v in L.get(u, ()):
            if v in seen: continue
            seen.add(v)
            if v not in match or aug(match[v], seen): match[v] = u; return True
        return False
    return sum(aug(u, set()) for u in L)
def cost(fields): return max_matching(a2.conflicts(a2.EV, list(fields)))
F = a2.F; out = {"single_field_ablation_r2": {}, "optimum_r2": {}}
for x in F:
    sc = cost(sorted(set(F) - {x})); out["single_field_ablation_r2"][x] = {"special_cases_if_removed": sc, "keep_at_lambda": {str(l): sc > l for l in (0.5, 1, 2)}}
for l in (0.5, 1, 2):
    best = min(((cost(c) + l * len(c), sorted(c)) for n in range(1, len(F) + 1) for c in itertools.combinations(F, n)), key=lambda z: (z[0], len(z[1])))
    out["optimum_r2"][str(l)] = {"best_fields": best[1], "score": round(best[0], 2), "special_cases": cost(best[1])}
out["reference"] = {"all 10 fields": cost(F), "minimal representable set {a,c,e,h,k,o,s,t}": cost(["a","c","e","h","k","o","s","t"])}
print(json.dumps(out, indent=1))
