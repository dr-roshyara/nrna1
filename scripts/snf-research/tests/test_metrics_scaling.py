"""Metric-scaling tests (D-7).

The pilot hard-coded N_PAIRS=80, N_TOTAL=100 and divided transformation and
adversarial counts by a literal 20. At n=1000 those denominators are silently
wrong, which would corrupt every rate in the competition report.
"""
import unittest

import _t  # noqa: F401

import metrics


class NoHardCodedCorpusSizes(unittest.TestCase):

    def test_denominators_are_derived_not_literal(self):
        import inspect
        src = inspect.getsource(metrics.compute_measurement_vector)
        self.assertNotIn("/ 20", src)
        self.assertNotIn("N_TOTAL", src)
        self.assertNotIn("N_PAIRS", src)

    def test_rates_scale_with_corpus_size(self):
        """Same composition, different size -> same rates."""
        def synth(n_per_cat):
            cases, verdicts = [], []
            for cat, verdict in (("equivalent", "CORRECT"),
                                 ("transformation", "CORRECT"),
                                 ("distinct", "WRONG"),
                                 ("adversarial", "CORRECT"),
                                 ("ambiguous", "HANDLED_VIA_HEDGING")):
                for i in range(n_per_cat):
                    cid = f"{cat}-{i}"
                    cases.append({"id": cid, "category": cat, "family": cat,
                                  "relation": "EQUIVALENT",
                                  "expressions": ["x"],
                                  "gold": {"irs": [], "ambiguous":
                                           cat == "ambiguous"}})
                    for mech in metrics.MECH_NAMES:
                        verdicts.append({
                            "case": cid, "category": cat, "mechanism": mech,
                            "verdict": verdict,
                            "correct": verdict in ("CORRECT",
                                                   "HANDLED_VIA_HEDGING"),
                            "conf": 0.8, "hedged": verdict.endswith("HEDGING"),
                            "uncertainty": "RESOLVED"})
            return cases, verdicts

        small_cases, small_v = synth(4)
        big_cases, big_v = synth(40)
        m_small = metrics.compute_measurement_vector(small_v, [],
                                                     cases=small_cases)
        m_big = metrics.compute_measurement_vector(big_v, [], cases=big_cases)
        for mech in metrics.MECH_NAMES:
            self.assertAlmostEqual(m_small["mechanisms"][mech]["T"],
                                   m_big["mechanisms"][mech]["T"], places=6,
                                   msg=f"{mech}: T did not scale")
            self.assertAlmostEqual(m_small["mechanisms"][mech]["A"],
                                   m_big["mechanisms"][mech]["A"], places=6,
                                   msg=f"{mech}: A did not scale")


class BootstrapIntervals(unittest.TestCase):
    """D-9 — point estimates must be reportable with an interval."""

    def test_bootstrap_ci_is_available_and_deterministic(self):
        vals = [1, 0, 1, 1, 0, 1, 1, 1, 0, 1] * 5
        lo1, hi1 = metrics.bootstrap_ci(vals, seed=20260822)
        lo2, hi2 = metrics.bootstrap_ci(vals, seed=20260822)
        self.assertEqual((lo1, hi1), (lo2, hi2))     # seeded => reproducible
        self.assertLessEqual(lo1, sum(vals) / len(vals))
        self.assertGreaterEqual(hi1, sum(vals) / len(vals))

    def test_empty_input_returns_none(self):
        self.assertEqual(metrics.bootstrap_ci([], seed=1), (None, None))


class CoverageMatchedCalibration(unittest.TestCase):
    """D-5 — an abstaining mechanism must not look better calibrated merely
    because it answered fewer, easier cases. Compare at equal coverage."""

    def test_coverage_matched_comparison_exists(self):
        # abstainer: answers only its 5 most-confident cases, all correct
        abstainer = [(0.95, 1)] * 5
        # answerer: same 5 easy cases plus 15 harder ones
        answerer = [(0.95, 1)] * 5 + [(0.9, 0)] * 15
        raw_a = metrics.calibration(abstainer)["brier"]
        raw_b = metrics.calibration(answerer)["brier"]
        self.assertLess(raw_a, raw_b)   # the confound, reproduced
        matched = metrics.calibration_at_coverage(answerer, coverage=0.25)
        self.assertAlmostEqual(matched["brier"], raw_a, places=6,
                               msg="coverage-matched calibration not equalised")
        self.assertEqual(matched["n"], 5)


if __name__ == "__main__":
    unittest.main(verbosity=2)
