#!/usr/bin/env python3
"""Revision-6 re-audit (G-LOG-0084), item 12: internal coherence of the frozen statistical specification against the
actual functions (assign_strata, validate_strata, draw_sample_v6, ht_with_variance, zero_bound_v6, freeze_sample_record).
Synthetic label names only. Expected values are computed independently here (exact enumeration where feasible).
Run: cd audit-p3b/r6-audit && python3 -B -W ignore stats.py > stats.out
"""
import importlib.util
import itertools
import math
import os

import fx

sp = importlib.util.spec_from_file_location("p3b_s5_r6_stats", os.path.join(fx.SCRIPTS, "p3b_s5_r6.py"))
r6 = importlib.util.module_from_spec(sp)
sp.loader.exec_module(r6)
OUT = []


def rep(cid, expected, observed, ok, note):
    v = "OK" if ok else "DEFECT"
    OUT.append((cid, v))
    print(f"{cid:6} expected: {expected}\n       observed: {observed}\n       => {v}  # {note}")


def raises(f):
    try:
        f()
        return None
    except Exception as e:           # noqa: BLE001
        return f"{type(e).__name__}: {e}"


POP = [f"lab{i:02d}" for i in range(20)]
ATTR = {l: {"path": "SINGLE"} for l in POP}
for l in POP[:2]:
    ATTR[l] = {"path": "DECOMPOSED", "hub": True}
ATTR[POP[2]] = {"path": "EMPTY"}
ATTR[POP[3]] = {"path": "DECOMPOSED", "multirow": True}
for l in POP[4:8]:
    ATTR[l] = {"path": "DECOMPOSED"}
STR = r6.assign_strata(ATTR)
print("strata sizes:", {h: len(v) for h, v in STR.items()})

# ST1 overlapping strata (draw)
ov = {h: list(v) for h, v in STR.items()}
ov["SINGLE"].append(POP[4])                           # a DECOMPOSED label also in SINGLE
e = raises(lambda: r6.draw_sample_v6(POP, ov, 7, {"SINGLE": 0.5, "DECOMPOSED": "CENSUS"}))
rep("ST1", "overlap refused", e, e is not None and "exclusive" in e, "overlapping strata attack on draw_sample_v6")

# ST2 missing label (draw)
ms = {h: [l for l in v if l != POP[9]] for h, v in STR.items()}
e = raises(lambda: r6.draw_sample_v6(POP, ms, 7, {"SINGLE": 0.5}))
rep("ST2", "missing label refused", e, e is not None and "exhaustive" in e, "a label with no stratum")

# ST3 missing stratum via an extra stratum name: labels allocated to a name draw_sample_v6 never iterates
xs = {h: list(v) for h, v in STR.items()}
moved = xs["SINGLE"][:6]
xs["SINGLE"] = xs["SINGLE"][6:]
xs["TIER-X"] = moved
val = r6.validate_strata(POP, xs)
smp = r6.draw_sample_v6(POP, xs, 7, {h: "CENSUS" for h in r6.STRATUM_PRECEDENCE})
disc = {l for l in xs["SINGLE"][:2]} | {POP[4]}
est = r6.ht_with_variance(smp, disc)
true_tau = len(disc)                                   # CENSUS everywhere: the estimate must equal the truth over N=20
Nsum = sum(s["N"] for s in smp.values())
rep("ST3", f"frame of N=20; an unsampled stratum must be refused or reported; share = {true_tau}/20 = {true_tau/20:.3f}",
    f"validate_strata -> {val}; sample covers N={Nsum}; 'TIER-X' in sample: {'TIER-X' in smp}; share {est['share']:.3f}, "
    f"CI {tuple(round(x, 3) for x in est['ci95_share'])}",
    False if (not val and Nsum != 20) else True,
    "missing-stratum attack: 6 of 20 labels silently leave the frame; an apparently exact census estimate results")

# ST4 a stratum with rate 0 / missing rate: 'no information' must not become 'zero with zero variance'
r0 = r6.draw_sample_v6(POP, STR, 7, {"HUB": "CENSUS", "EMPTY": "CENSUS", "MULTIROW": "CENSUS", "DECOMPOSED": "CENSUS"})
e0 = r6.ht_with_variance(r0, set())
rep("ST4", f"SINGLE (N={len(STR['SINGLE'])}) unobserved: overall share bound must reflect up to "
    f"{len(STR['SINGLE'])}/20 = {len(STR['SINGLE'])/20:.2f} (or be declared unestimable)",
    f"share {e0['share']}, ci95 {e0['ci95_share']}, per-stratum SINGLE zero_bound {e0['strata']['SINGLE']['zero_bound_share']}",
    e0["ci95_share"][1] >= len(STR["SINGLE"]) / 20,
    "rate missing -> n=0: stated per stratum as 'no information', but the combined estimate/CI treats it as exactly 0")

# ST5 n_h = 1 (non-census): the spec says 'reported as unestimable'
r1 = r6.draw_sample_v6(POP, STR, 7, {"HUB": "CENSUS", "EMPTY": "CENSUS", "MULTIROW": "CENSUS", "DECOMPOSED": 0.25,
                                     "SINGLE": 0.01})
d1 = set(r1["SINGLE"]["labels"])
e1 = r6.ht_with_variance(r1, d1)
rep("ST5", "n_SINGLE=1 < N: variance reported as unestimable (spec 'Variance / CI')",
    f"n={r1['SINGLE']['n']} N={r1['SINGLE']['N']} var={e1['strata']['SINGLE']['var']} ht_total={e1['strata']['SINGLE']['ht_total']}; "
    f"overall ci95 {tuple(round(x, 3) for x in e1['ci95_share'])}; keys {sorted(e1['strata']['SINGLE'])}",
    "unestimable" in str(e1["strata"]["SINGLE"]).lower(),
    "a single draw with d=1 yields HT total 12 with variance 0.0: a point 'estimate' with a zero-width stratum CI")

# ST6 frozen-sample guard: does ht_with_variance hash the sample it is given?
frozen = r6.draw_sample_v6(POP, STR, 11, {"HUB": "CENSUS", "EMPTY": "CENSUS", "MULTIROW": "CENSUS", "DECOMPOSED": 0.5,
                                         "SINGLE": 0.25})
rec = r6.freeze_sample_record("spec text v6", 11, frozen)
tampered = {h: dict(v, labels=list(v["labels"])) for h, v in frozen.items()}
ml_label = next(l for l in STR["SINGLE"] if l not in frozen["SINGLE"]["labels"])
tampered["SINGLE"]["labels"].append(ml_label)          # an ML-prioritized label injected into the probability sample
e = raises(lambda: r6.ht_with_variance(tampered, {ml_label}, frozen_sha=rec["sample_sha256"], sample_sha=rec["sample_sha256"]))
e_none = raises(lambda: r6.ht_with_variance(tampered, {ml_label}))
est_t = r6.ht_with_variance(tampered, {ml_label})
rep("ST6", "a sample that is not the frozen one (and an ML-injected discordant label) is refused",
    f"with frozen_sha==sample_sha (caller-supplied): {e or 'ACCEPTED'}; without shas: {e_none or 'ACCEPTED'}; "
    f"HT total {est_t['ht_total']:.2f} (true sampled total 0)",
    e is not None, "the guard compares two caller-supplied strings; it never hashes the sample argument")

# ST7 overlapping sample fed to the estimator (bypassing draw): double counting
ovs = {h: dict(v, labels=list(v["labels"])) for h, v in frozen.items()}
x = frozen["DECOMPOSED"]["labels"][0]
ovs["SINGLE"]["labels"].append(x)
e = raises(lambda: r6.ht_with_variance(ovs, {x}))
eo = r6.ht_with_variance(ovs, {x})
rep("ST7", "estimator refuses a label present in two strata", f"{e or 'ACCEPTED'}; HT total {eo['ht_total']:.2f} for one discordant label",
    e is not None, "overlapping-strata attack on ht_with_variance")

# ST8 zero bounds against an independent computation
def hyper_bound(n, N, a=0.05):
    best = 0
    for D in range(N + 1):
        p0 = math.comb(N - D, n) / math.comb(N, n) if N - D >= n else 0.0
        if p0 >= a:
            best = D
    return best / N
checks = [(0, 50, 1.0), (50, 50, 0.0), (10, 50, hyper_bound(10, 50)), (25, 200, hyper_bound(25, 200)),
          (30, None, 1 - 0.05 ** (1 / 30))]
got = [(n, N, r6.zero_bound_v6(n, N), w) for n, N, w in checks]
rep("ST8", "n=0 -> 1.0; census -> 0; hypergeometric = largest D with P(0|D) >= a; binomial w/o N",
    [(n, N, round(g, 6), round(w, 6)) for n, N, g, w in got], all(abs(g - w) < 1e-12 for _, _, g, w in got), "")

# ST9 HT unbiasedness and variance-estimator unbiasedness by exact enumeration (N=8, n=3, D=3)
N, n = 8, 3
labs = [f"e{i}" for i in range(N)]
D = set(labs[:3])
tots, vars_ = [], []
for s in itertools.combinations(labs, n):
    smp_ = {"SINGLE": {"labels": list(s), "N": N, "n": n, "pi": n / N}}
    r = r6.ht_with_variance(smp_, D & set(s))
    tots.append(r["ht_total"])
    vars_.append(r["var_total"])
mean = sum(tots) / len(tots)
truevar = sum((t - mean) ** 2 for t in tots) / len(tots)
rep("ST9", f"E[HT]=|D|=3; E[var-hat]=Var(HT)={truevar:.4f}", f"E[HT]={mean:.4f}; E[var-hat]={sum(vars_)/len(vars_):.4f}",
    abs(mean - 3) < 1e-9 and abs(sum(vars_) / len(vars_) - truevar) < 1e-9, "estimator and SRSWOR variance are correct")

# ST10 strata by declared attributes
a = r6.assign_strata({"s1": {"path": "SINGLE", "multirow": True}, "e1": {"path": "EMPTY", "hub": True}})
rep("ST10", "MULTIROW = DECOMPOSED with row sources over > 1 unit (derived); hub precedence", a,
    "s1" not in a["MULTIROW"], "a SINGLE label with a declared multirow flag lands in MULTIROW (attribute not derived)")

# ST11 seed / freezing
s1, s2 = (r6.draw_sample_v6(POP, STR, 99, {"SINGLE": 0.3}) for _ in range(2))
fr = r6.freeze_sample_record("spec", 99, s1)
rep("ST11", "same seed -> same sample; frozen record carries seed, labels, spec sha, python version (+ rates/frame?)",
    f"reproducible={s1 == s2}; record keys {sorted(fr)}", s1 == s2,
    "the record freezes drawn labels and N_h but not the strata membership, rates or a population hash")

# ST12 rate edge cases
rz = r6.draw_sample_v6(POP, STR, 1, {"SINGLE": 0})
rbig = r6.draw_sample_v6(POP, STR, 1, {"SINGLE": 1.7})
rep("ST12", "rate 0 -> n=0 (stated); rate > 1 refused or capped", f"n(rate 0)={rz['SINGLE']['n']}; n(rate 1.7)={rbig['SINGLE']['n']} "
    f"of N={rbig['SINGLE']['N']}", rz["SINGLE"]["n"] == 0, "rate > 1 is silently capped to a census (not refused)")

print("\n== SUMMARY", {k: sum(1 for _, v in OUT if v == k) for k in ("OK", "DEFECT")})
print("DEFECTS:", [c for c, v in OUT if v == "DEFECT"])
