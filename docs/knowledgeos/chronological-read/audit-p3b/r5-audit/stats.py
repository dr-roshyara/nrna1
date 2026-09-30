#!/usr/bin/env python3
"""Mathematical checks of p3b_s5_r5 draw_audit_sample / ht_estimate / wilson / zero_bound (synthetic labels only).
Run: cd <CR>/scripts && python3 -B /tmp/r5-audit/stats.py"""
import itertools, math, sys, importlib.util, os
sys.dont_write_bytecode = True
sp = importlib.util.spec_from_file_location("r5", os.path.join(os.getcwd(), "p3b_s5_r5.py"))
r5 = importlib.util.module_from_spec(sp); sp.loader.exec_module(r5)

print("1. zero_bound: binomial vs hypergeometric (alpha=0.05)")
for n, N in [(10, 50), (30, 100), (59, 1975), (5, 5), (1, 3)]:
    hb = r5.zero_bound(n, N); bb = r5.zero_bound(n)
    D = round(hb * N)
    p_at = math.comb(N - D, n) / math.comb(N, n) if N - D >= n else 0
    p_next = math.comb(N - D - 1, n) / math.comb(N, n) if N - D - 1 >= n else 0
    print(f"   n={n:3d} N={N:5d}  hyper={hb:.4f} (D={D}, P0|D={p_at:.4f} >= .05, P0|D+1={p_next:.4f} < .05)  binom={bb:.4f}  hyper<=binom:{hb <= bb + 1e-12}")
print("   census n=N=5 ->", r5.zero_bound(5, 5), "(0 discordant in a census => D=0 exactly: bound 0 correct)")
try:
    r5.zero_bound(0)
except ZeroDivisionError as e:
    print("   zero_bound(0) binomial -> ZeroDivisionError (n=0 unguarded)")
print("   zero_bound(0, N=10) ->", r5.zero_bound(0, 10))

print("2. HT unbiasedness by full enumeration (SRSWOR, N=6, n=2, D={a,b})")
pop = list("abcdef"); disc = {"a", "b"}
ests = []
for S in itertools.combinations(pop, 2):
    samp = {"s": {"labels": list(S), "N": 6, "n": 2, "pi": 2 / 6}}
    ests.append(r5.ht_estimate(samp, disc & set(S))["ht_total"])
print("   E[HT total] =", sum(ests) / len(ests), "(true 2)")

print("3. draw_audit_sample: overlapping strata / missing strata are not rejected")
strata = {"decomposed-census": ["L1", "L2"], "decomposed-other": ["L2", "L3", "L4"], "single": ["L5", "L6", "L7", "L8"]}
s = r5.draw_audit_sample(strata, 7, {"decomposed-census": "CENSUS", "decomposed-other": 0.5, "single": 0.5})
print("   accepted; L2 in two strata; returned pi per stratum:", {k: round(v["pi"], 3) for k, v in s.items()},
      "; true P(L2 in sample) = 1 but a discordant L2 is counted in every stratum it is drawn in:")
disc = {"L2"}
print("   HT total with L2 discordant:", r5.ht_estimate(s, disc)["ht_total"], "(true total 1); N summed =",
      sum(v["N"] for v in s.values()), "(true population 8)")
print("   EMPTY labels (8) have no stratum in addendum item 17; a label in no stratum is silently outside the frame")
s0 = r5.draw_audit_sample({"x": ["A", "B", "C"]}, 1, {"x": 0.0})
print("   rate 0.0 still draws n =", s0["x"]["n"], "(max(1, ...)); pi =", s0["x"]["pi"])
print("4. seed reproducibility: same seed/strata -> same sample:",
      r5.draw_audit_sample(strata, 7, {"decomposed-census": "CENSUS", "decomposed-other": 0.5, "single": 0.5}) == s,
      "; adding a stratum named 'aaa' changes others:",
      r5.draw_audit_sample(dict(strata, aaa=["Z1", "Z2"]), 7, {"decomposed-census": "CENSUS", "decomposed-other": 0.5,
                           "single": 0.5, "aaa": 0.5})["single"]["labels"] != s["single"]["labels"])
print("5. wilson(0, n):", r5.wilson(0, 20), "vs exact one-sided zero_bound(20):", round(r5.zero_bound(20), 4),
      "(two-sided 95% Wilson upper ~ one-sided 97.5%)")
print("6. ht_estimate: no variance / CI for the stratified total or share (per-stratum Wilson only)")
