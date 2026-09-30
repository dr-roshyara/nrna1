#!/usr/bin/env python3
"""B5 STATISTICS (R7 v2.3 §8, HD-6, DR-14/15): estimate_v7 = total on its domain; REJECT outside it; NOT-ESTIMABLE when
the estimand or variance is undefined; formulas unchanged and verified by exact enumeration. Synthetic frames only.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_stats
"""
import copy
import itertools
import math
import os
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.dirname(HERE))
import p3b_s5_r7_stats as S                         # noqa: E402

FRAME = {f"L{i:02d}": {"path": "SINGLE", "hub": False, "multirow": False} for i in range(12)}
FRAME.update({f"D{i}": {"path": "DECOMPOSED", "hub": False, "multirow": False} for i in range(4)})
FRAME.update({"M0": {"path": "DECOMPOSED", "hub": False, "multirow": True}, "E0": {"path": "EMPTY", "hub": False, "multirow": False}})
RATES = {"HUB": 1, "EMPTY": "CENSUS", "MULTIROW": "CENSUS", "DECOMPOSED": 0.5, "SINGLE": 0.25}


def setup(frame=FRAME, rates=RATES, seed=7):
    rec = S.freeze(frame, rates, seed, spec_text="frozen spec text")
    return rec, S.sha(S.canon(rec))


def outcomes(rec, disc=()):
    return {l: (1 if l in disc else 0) for h in rec["strata"].values() for l in h["sample"]}


class Domain(unittest.TestCase):
    def test_valid_estimate(self):
        rec, anchor = setup()
        r = S.estimate_v7(rec, anchor, FRAME, rec["sample"], outcomes(rec))
        self.assertEqual(r["verdict"], "STATISTICS-VALID", r)
        self.assertEqual(r["estimate"]["ht_total"], 0.0)
        # v2.5-S (G-LOG-0093): d = 0 fails the count condition → no normal CI; the exact bound is the primary statement
        self.assertIsNone(r["estimate"]["ci95_share"])
        self.assertIsNotNone(r["estimate"]["ci95_informational"])
        self.assertIn("upper_bound_share", r["estimate"])

    def test_T30_tier_x_T33_overlap_T36_incomplete_T37_multirow(self):
        rec, anchor = setup()
        bad = copy.deepcopy(rec)
        bad["strata"]["TIER-X"] = {"N": 0, "n": 0, "rate": 0, "members": [], "sample": []}
        self.assertEqual(S.estimate_v7(bad, S.sha(S.canon(bad)), FRAME, bad["sample"], outcomes(bad))["verdict"], "STATISTICS-REJECTED")
        f2 = dict(FRAME)
        del f2["L00"]
        self.assertEqual(S.estimate_v7(rec, anchor, f2, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-REJECTED")
        f3 = copy.deepcopy(FRAME)
        f3["L01"]["multirow"] = True
        self.assertEqual(S.estimate_v7(rec, anchor, f3, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-REJECTED")
        ov = copy.deepcopy(rec)
        ov["strata"]["SINGLE"]["members"].append("D0")
        self.assertEqual(S.estimate_v7(ov, S.sha(S.canon(ov)), FRAME, ov["sample"], outcomes(ov))["verdict"], "STATISTICS-REJECTED")

    def test_T34_forged_sample_and_T70_anchor(self):
        rec, anchor = setup()
        forged = copy.deepcopy(rec["sample"])
        forged["SINGLE"] = sorted(set(forged["SINGLE"]) | {"L11", "L10"})[: len(forged["SINGLE"])]
        forged["SINGLE"][0] = next(l for l in rec["strata"]["SINGLE"]["members"] if l not in rec["sample"]["SINGLE"])
        o = outcomes(rec)
        o[forged["SINGLE"][0]] = 1
        self.assertEqual(S.estimate_v7(rec, anchor, FRAME, forged, o)["verdict"], "STATISTICS-REJECTED")
        self.assertEqual(S.estimate_v7(rec, "0" * 64, FRAME, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-REJECTED")
        o2 = outcomes(rec)
        o2["L00" if "L00" not in o2 else "L01"] = 1                                    # ML-injected outcome outside the sample
        if set(o2) - {l for s in rec["sample"].values() for l in s}:
            self.assertEqual(S.estimate_v7(rec, anchor, FRAME, rec["sample"], o2)["verdict"], "STATISTICS-REJECTED")

    def test_T35_invalid_rates(self):
        for bad in (1.7, -0.1, "HALF"):
            with self.assertRaises(ValueError):
                setup(rates=dict(RATES, SINGLE=bad))

    def test_T31_n0_T69_rounding_to_zero(self):
        rec, anchor = setup(rates=dict(RATES, SINGLE=0))
        self.assertEqual(S.estimate_v7(rec, anchor, FRAME, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-NOT-ESTIMABLE")
        rec, anchor = setup(rates=dict(RATES, SINGLE=0.04))                         # 0.04 * 12 = 0.48 → n = 0
        self.assertEqual(rec["strata"]["SINGLE"]["n"], 0)
        self.assertEqual(S.estimate_v7(rec, anchor, FRAME, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-NOT-ESTIMABLE")

    def test_T32_n1_point_informational_variance_undefined(self):
        rec, anchor = setup(rates=dict(RATES, SINGLE=1 / 12))                       # n = 1 < N = 12
        r = S.estimate_v7(rec, anchor, FRAME, rec["sample"], outcomes(rec))
        self.assertEqual(r["verdict"], "STATISTICS-NOT-ESTIMABLE")
        self.assertIn("SINGLE", r["informational"]["point_estimates"])
        self.assertIsNone(r.get("estimate"))

    def test_T68_empty_stratum_is_not_a_trigger(self):
        f = {k: v for k, v in FRAME.items() if not k.startswith("M")}               # no MULTIROW label: N_MULTIROW = 0
        rec, anchor = setup(frame=f)
        self.assertEqual(S.estimate_v7(rec, anchor, f, rec["sample"], outcomes(rec))["verdict"], "STATISTICS-VALID")


class ExactEnumeration(unittest.TestCase):
    def test_ht_and_variance_unbiased_by_enumeration(self):
        """E[τ̂] = τ and E[V̂] = Var(τ̂) over all SRSWOR samples (N = 8, n = 3, D = 3) — the frozen formulas."""
        pop = [1, 1, 1, 0, 0, 0, 0, 0]
        N, n = len(pop), 3
        ests, vhats = [], []
        for s in itertools.combinations(range(N), n):
            t, v = S.ht_stratum(N, n, sum(pop[i] for i in s))
            ests.append(t)
            vhats.append(v)
        m = sum(ests) / len(ests)
        var = sum((e - m) ** 2 for e in ests) / len(ests)
        self.assertAlmostEqual(m, sum(pop), places=9)
        self.assertAlmostEqual(sum(vhats) / len(vhats), var, places=9)


def hyper_cdf(d, N, D, n):
    tot = math.comb(N, n)
    return sum(math.comb(D, k) * math.comb(N - D, n - k) for k in range(0, d + 1)) / tot


class ExactBoundV25(unittest.TestCase):
    """v2.5-S (G-LOG-0093): exact finite-population upper bound per stratum → Bonferroni combination → overall bound
    (primary); the normal CI only when every sampled stratum has d ≥ 5 and n − d ≥ 5 (T105–T109)."""

    def test_T105_definition_by_brute_force(self):
        for N in range(1, 13):
            for n in range(0, N + 1):
                for d in range(0, n + 1):
                    U = S.exact_upper_bound(d, n, N, 0.05)
                    if n == 0:
                        self.assertEqual(U, N)
                        continue
                    ok = [D for D in range(d, N - (n - d) + 1) if hyper_cdf(d, N, D, n) >= 0.05]
                    self.assertEqual(U, max(ok), (N, n, d))

    def test_T106_coverage_guarantee_exhaustive(self):
        """For every true D: P_D(D ≤ U(X)) ≥ 1 − α exactly (hypergeometric X), on all small (N, n)."""
        for N in range(2, 16):
            for n in range(1, N):
                for D in range(0, N + 1):
                    cov = sum(math.comb(D, x) * math.comb(N - D, n - x) for x in range(0, min(n, D) + 1)
                              if D <= S.exact_upper_bound(x, n, N, 0.05)) / math.comb(N, n)
                    self.assertGreaterEqual(cov, 0.95 - 1e-12, (N, n, D))

    def test_T107_census_and_zero_match(self):
        self.assertEqual(S.exact_upper_bound(3, 10, 10, 0.05), 3)                    # census: exact
        for N, n in ((66, 33), (170, 85), (1700, 100)):
            self.assertEqual(S.exact_upper_bound(0, n, N, 0.05), round(S.r6.zero_bound_v6(n, N) * N))

    def test_T108_bonferroni_combination_covers(self):
        """Two sampled strata, α split: P(D1 + D2 ≤ U1 + U2) ≥ 1 − α for every (D1, D2), exhaustive."""
        (N1, n1), (N2, n2), a = (9, 4), (7, 3), 0.05
        for D1 in range(N1 + 1):
            for D2 in range(N2 + 1):
                cov = 0.0
                for x1 in range(0, min(n1, D1) + 1):
                    p1 = math.comb(D1, x1) * math.comb(N1 - D1, n1 - x1) / math.comb(N1, n1)
                    for x2 in range(0, min(n2, D2) + 1):
                        p2 = math.comb(D2, x2) * math.comb(N2 - D2, n2 - x2) / math.comb(N2, n2)
                        U = S.exact_upper_bound(x1, n1, N1, a / 2) + S.exact_upper_bound(x2, n2, N2, a / 2)
                        cov += p1 * p2 * (D1 + D2 <= U)
                self.assertGreaterEqual(cov, 1 - a - 1e-12, (D1, D2))

    def test_T109_estimate_reports_exact_bound_and_conditional_ci(self):
        frame = {f"S{i:03d}": {"path": "SINGLE", "hub": False, "multirow": False} for i in range(200)}
        rec = S.freeze(frame, {"SINGLE": 0.5}, 3, spec_text="spec")
        anchor = S.sha(S.canon(rec))
        smp = rec["sample"]["SINGLE"]
        few = S.estimate_v7(rec, anchor, frame, rec["sample"], {l: int(i < 2) for i, l in enumerate(smp)})
        self.assertEqual(few["verdict"], "STATISTICS-VALID")
        e = few["estimate"]
        self.assertIsNone(e["ci95_share"])                                           # d = 2 < 5: no normal CI
        self.assertEqual(e["upper_bound_total"], S.exact_upper_bound(2, 100, 200, 0.05))   # one sampled stratum: α
        self.assertAlmostEqual(e["upper_bound_share"], e["upper_bound_total"] / 200)
        many = S.estimate_v7(rec, anchor, frame, rec["sample"], {l: int(i < 20) for i, l in enumerate(smp)})
        self.assertIsNotNone(many["estimate"]["ci95_share"])                        # d = 20, n − d = 80: CI reported
        bad = dict(rec, alpha=1.5)
        self.assertEqual(S.estimate_v7(bad, S.sha(S.canon(bad)), frame, rec["sample"], {l: 0 for l in smp})["verdict"],
                         "STATISTICS-REJECTED")
        noalpha = {k: v for k, v in rec.items() if k != "alpha"}
        self.assertEqual(S.estimate_v7(noalpha, S.sha(S.canon(noalpha)), frame, rec["sample"], {l: 0 for l in smp})["verdict"],
                         "STATISTICS-REJECTED")


if __name__ == "__main__":
    unittest.main()
