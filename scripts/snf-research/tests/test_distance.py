"""d_SNF tests — the four-way epistemic distinction (D-3) and presence
sensitivity (D-2), plus the pre-metric properties we are entitled to claim.

Commission amendment (HPA 2026-08-22): the experiment must test and preserve

    EXPRESSED  ≠  EXPRESSED+NEGATED  ≠  NOT_EXPRESSED  ≠  UNKNOWN

Epistemic uncertainty, semantic absence and negation must not collapse into one
representation. TAU_EVAL (0.40) is the acceptance threshold used by the
evaluator, so "must not collapse" means: pairwise distance must exceed it.
"""
import unittest

import _t  # noqa: F401

from distance import d_snf, V01, V02
from evaluator import TAU_EVAL
from ir import (IR, Argument, make_ir, ROLE_AGENT, ROLE_PATIENT, ROLE_INSTRUMENT,
                ARG_EXPRESSED, ARG_UNKNOWN, ARG_NOT_EXPRESSED, ARG_AMBIGUOUS,
                MODALITY_POSSIBLE)

SEP = TAU_EVAL  # a difference must land strictly above the acceptance threshold


def _eat(patient_entity, patient_status=ARG_EXPRESSED, negation=False,
         modality=None):
    args = [Argument(ROLE_AGENT, "rama", ARG_EXPRESSED),
            Argument(ROLE_PATIENT, patient_entity, patient_status)]
    kw = {}
    if modality:
        kw["modality"] = modality
    return IR(predicate="EAT", arguments=args, negation=negation, **kw)


class FourWayDistinction(unittest.TestCase):
    """EXPRESSED / EXPRESSED+NEGATED / NOT_EXPRESSED / UNKNOWN — six pairs."""

    def setUp(self):
        self.expressed = _eat("rice")
        self.negated = _eat("rice", negation=True)
        self.not_expressed = _eat(None, ARG_NOT_EXPRESSED)
        self.unknown = _eat(None, ARG_UNKNOWN)
        self.cases = {
            "EXPRESSED": self.expressed,
            "NEGATED": self.negated,
            "NOT_EXPRESSED": self.not_expressed,
            "UNKNOWN": self.unknown,
        }

    def test_all_six_pairs_separated_v02(self):
        names = sorted(self.cases)
        for i, a in enumerate(names):
            for b in names[i + 1:]:
                d = d_snf(self.cases[a], self.cases[b], version=V02)
                self.assertGreater(
                    d, SEP,
                    f"{a} vs {b} collapsed: d={d:.3f} <= tau={SEP}")

    def test_identity_is_zero(self):
        for name, ir in self.cases.items():
            self.assertEqual(d_snf(ir, ir, version=V02), 0.0, name)

    def test_symmetry(self):
        names = sorted(self.cases)
        for i, a in enumerate(names):
            for b in names[i + 1:]:
                self.assertAlmostEqual(
                    d_snf(self.cases[a], self.cases[b], version=V02),
                    d_snf(self.cases[b], self.cases[a], version=V02), places=9)

    def test_epistemic_absence_is_not_semantic_absence(self):
        """UNKNOWN vs NOT_EXPRESSED — the distinction the commission names."""
        d = d_snf(self.not_expressed, self.unknown, version=V02)
        self.assertGreater(d, SEP, f"UNKNOWN collapsed into NOT_EXPRESSED (d={d:.3f})")

    def test_v01_baseline_retained_for_ab_comparison(self):
        """v0.1 must remain callable — it is the A/B baseline, not the default
        for the competition. It is EXPECTED to collapse these pairs; that is
        the defect v0.2 repairs, and keeping it callable is how we show it."""
        d01 = d_snf(self.not_expressed, self.unknown, version=V01)
        d02 = d_snf(self.not_expressed, self.unknown, version=V02)
        self.assertLessEqual(d01, SEP)      # the D-3 defect, reproduced
        self.assertGreater(d02, SEP)        # and repaired


class PresenceSensitivity(unittest.TestCase):
    """D-2 — an optional role's PRESENCE is a semantic difference and must not
    be diluted below the acceptance threshold by role averaging."""

    def _store(self, with_location):
        args = [Argument(ROLE_AGENT, "engineer", ARG_EXPRESSED),
                Argument(ROLE_PATIENT, "file", ARG_EXPRESSED)]
        if with_location:
            args.append(Argument("LOCATION", "server", ARG_EXPRESSED))
        else:
            args.append(Argument("LOCATION", None, ARG_NOT_EXPRESSED))
        return IR(predicate="STORE", arguments=args)

    def test_optional_role_presence_not_diluted(self):
        d = d_snf(self._store(True), self._store(False), version=V02)
        self.assertGreater(d, SEP, f"optional-role presence diluted (d={d:.3f})")

    def test_structural_absence_not_diluted(self):
        """Role present in one IR, entirely absent from the other."""
        with_instr = make_ir("CLEAN", (ROLE_AGENT, "engineer"),
                             (ROLE_PATIENT, "machine"),
                             (ROLE_INSTRUMENT, "brush"))
        without = make_ir("CLEAN", (ROLE_AGENT, "engineer"),
                          (ROLE_PATIENT, "machine"))
        d = d_snf(with_instr, without, version=V02)
        self.assertGreater(d, SEP, f"structural role absence diluted (d={d:.3f})")

    def test_more_shared_roles_do_not_wash_out_one_boundary(self):
        """The dilution mechanism: adding agreeing roles must not push a real
        category difference below tau."""
        def ir(n_extra, patient_status):
            args = [Argument(ROLE_AGENT, "engineer", ARG_EXPRESSED),
                    Argument(ROLE_PATIENT,
                             "file" if patient_status == ARG_EXPRESSED else None,
                             patient_status)]
            for i, role in enumerate(("LOCATION", ROLE_INSTRUMENT,
                                      "RECIPIENT", "SOURCE")[:n_extra]):
                args.append(Argument(role, f"e{i}", ARG_EXPRESSED))
            return IR(predicate="STORE", arguments=args)
        for n in range(0, 5):
            d = d_snf(ir(n, ARG_EXPRESSED), ir(n, ARG_NOT_EXPRESSED),
                      version=V02)
            self.assertGreater(d, SEP,
                               f"washed out with {n} agreeing roles (d={d:.3f})")


class Tier1Decisive(unittest.TestCase):
    """Tier-1 boundaries must stay decisive under v0.2."""

    def test_predicate_difference(self):
        a = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        b = make_ir("REJECT", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        self.assertGreaterEqual(d_snf(a, b, version=V02), 0.60)

    def test_argument_inversion(self):
        a = make_ir("CHASE", (ROLE_AGENT, "cat"), (ROLE_PATIENT, "dog"))
        b = make_ir("CHASE", (ROLE_AGENT, "dog"), (ROLE_PATIENT, "cat"))
        self.assertGreaterEqual(d_snf(a, b, version=V02), 0.60)

    def test_negation(self):
        a = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        b = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"),
                    negation=True)
        self.assertGreaterEqual(d_snf(a, b, version=V02), 0.60)

    def test_paraphrase_stays_low(self):
        a = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        b = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        self.assertLess(d_snf(a, b, version=V02), 0.30)

    def test_modality_is_graded_not_decisive(self):
        """A modality difference is real but must remain a degree, not a
        category boundary — otherwise every hedge becomes a different meaning."""
        a = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"))
        b = make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order"),
                    modality=MODALITY_POSSIBLE)
        d = d_snf(a, b, version=V02)
        self.assertGreater(d, 0.0)
        self.assertLess(d, 0.60)


class PreMetricPropertiesOnly(unittest.TestCase):
    """We claim non-negativity, symmetry and d(x,x)=0 — and nothing more.
    The triangle inequality is NOT claimed (documented limitation)."""

    def test_bounded_non_negative(self):
        irs = [make_ir("APPROVE", (ROLE_AGENT, "committee"), (ROLE_PATIENT, "order")),
               make_ir("REJECT", (ROLE_AGENT, "cat"), (ROLE_PATIENT, "dog")),
               _eat(None, ARG_UNKNOWN),
               _eat("rice", negation=True)]
        for a in irs:
            for b in irs:
                for v in (V01, V02):
                    d = d_snf(a, b, version=v)
                    self.assertGreaterEqual(d, 0.0)
                    self.assertLessEqual(d, 1.0)


if __name__ == "__main__":
    unittest.main(verbosity=2)
