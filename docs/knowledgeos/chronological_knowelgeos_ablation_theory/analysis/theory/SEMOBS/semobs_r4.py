"""semobs r4 (1ak-2; committed before its run). r3 data + one state pair from ALREADY-READ rows + a DISJOINT-cluster witness count.
Added (source facts): R-81 'Batch 7 partially released: isolation repair ACTIVE, all other Batch-7 work still frozen';
R-86 'WP-4B BATCH 7 IS RELEASED · engineering is AUTHORIZED to execute … R-84's §12 reconcile'. The reconcile work's membership of
Batch 7 is stated in R-86, so its frozen state at R-81 is DERIVED (R-81's universal statement + R-86's membership statement).
Disjoint count = the largest set of strict witnesses whose cluster sets are pairwise disjoint (exhaustive search; small n)."""
import importlib.util, itertools, json, os
d = os.path.dirname(os.path.abspath(__file__))
s = importlib.util.spec_from_file_location("r3", os.path.join(d, "semobs_r3.py"))
src = open(os.path.join(d, "semobs_r3.py")).read().split("st, po = sm.pairs(obs)")[0]
ns = {"__file__": os.path.join(d, "semobs_r3.py")}; exec(compile(src, "semobs_r3.py", "exec"), ns)
sm, obs, E = ns["sm"], ns["obs"], ns["E"]
obs = obs + [
 E("START §12 reconcile at R-81 (Batch 7 frozen)", "R-81", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="not-authorized", t="§12-reconcile"),
 E("START §12 reconcile at R-86 (Batch 7 released)", "R-86", "register-row", "performed", "PERFORMED", "n/a", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="authorized", t="§12-reconcile")]
st, po = sm.pairs(obs); V = sm.verdicts(st, po)
def disjoint(witnesses):
    W = list({tuple(sorted({a, b})) for _, _, a, b in witnesses})
    for n in range(len(W), 0, -1):
        for combo in itertools.combinations(W, n):
            used = [c for w in combo for c in w]
            if len(used) == len(set(used)): return n, [list(w) for w in combo]
    return 0, []
out = {}
for v in sm.VARS:
    n, sel = disjoint(st[v])
    out[sm.NAMES[v]] = {"verdict_R4": V[v]["verdict"], "independent_strict_R4": V[v]["independent_strict"], "disjoint_strict": n, "disjoint_set": sel,
                        "verdict_disjoint": "EMPIRICALLY SUPPORTED" if n >= 2 else ("WEAK" if n == 1 else V[v]["verdict"] if V[v]["independent_strict"] == 0 else "WEAK")}
print(json.dumps({"n_observations": len(obs), "verdicts": out}, indent=1))
