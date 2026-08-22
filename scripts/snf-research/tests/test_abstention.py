"""Abstention tests (D-6).

The pilot measured abstention but never priced it, so "prudence" and
"inability" were indistinguishable. The commission's central research question:

    "When a mechanism abstains, is it demonstrating epistemic discipline or
     merely weakness?"

The four-way taxonomy must be computable, and FALSE ACCEPTANCE must be treated
as a CONSTRAINT (the most dangerous error), never as a positive score.
"""
import unittest

import _t  # noqa: F401

from evaluator import abstention_warranted
from metrics import abstention_taxonomy, ABSTENTION_OUTCOMES


def _case(cid, ambiguous=False, abstain_expected=False, exprs=("the committee approved the order",)):
    return {"id": cid, "category": "ambiguous" if ambiguous else "equivalent",
            "family": "f", "relation": "AMBIGUOUS" if ambiguous else "EQUIVALENT",
            "expressions": list(exprs),
            "gold": {"irs": [], "ambiguous": ambiguous,
                     "abstain_expected": abstain_expected}}


class WarrantedAbstentionGold(unittest.TestCase):

    def test_ambiguous_warrants_abstention_for_every_mechanism(self):
        c = _case("a1", ambiguous=True)
        for mech in ("SNF-A", "SNF-B", "SNF-C", "SNF-D", "SNF-E", "SNF-N"):
            self.assertTrue(abstention_warranted(c, mech))

    def test_declared_abstain_expected_warrants_abstention_for_every_mechanism(self):
        """A case the toy world cannot represent at all: abstention is the only
        correct behaviour, for every mechanism, and the corpus DECLARES it."""
        c = _case("a2", abstain_expected=True)
        for mech in ("SNF-A", "SNF-B", "SNF-C", "SNF-D", "SNF-E", "SNF-N"):
            self.assertTrue(abstention_warranted(c, mech),
                            f"{mech} not required to abstain on declared case")

    def test_well_determined_case_does_not_warrant_abstention(self):
        c = _case("a3")
        for mech in ("SNF-A", "SNF-B", "SNF-C", "SNF-E"):
            self.assertFalse(abstention_warranted(c, mech))


class FourWayTaxonomy(unittest.TestCase):

    def test_outcome_vocabulary(self):
        self.assertEqual(sorted(ABSTENTION_OUTCOMES),
                         ["BLIND_ABSTENTION", "CORRECT_ABSTENTION",
                          "CORRECT_RESOLUTION", "FALSE_ACCEPTANCE"])

    def test_classification(self):
        cases = [_case("c1"), _case("c2", ambiguous=True),
                 _case("c3", abstain_expected=True), _case("c4")]
        verdicts = [
            # answered a well-determined case correctly -> CORRECT_RESOLUTION
            {"case": "c1", "mechanism": "M", "verdict": "CORRECT",
             "correct": True, "category": "equivalent", "conf": 0.9},
            # abstained where abstention was warranted -> CORRECT_ABSTENTION
            {"case": "c2", "mechanism": "M", "verdict": "HANDLED_VIA_ABSTENTION",
             "correct": True, "category": "ambiguous", "conf": None},
            # answered where it could not possibly know -> FALSE_ACCEPTANCE
            {"case": "c3", "mechanism": "M", "verdict": "WRONG",
             "correct": False, "category": "equivalent", "conf": 0.8},
            # abstained on something it should have handled -> BLIND_ABSTENTION
            {"case": "c4", "mechanism": "M", "verdict": "ABSTAINED",
             "correct": False, "category": "equivalent", "conf": None},
        ]
        tax = abstention_taxonomy(verdicts, cases)["M"]
        self.assertEqual(tax["CORRECT_RESOLUTION"], 1)
        self.assertEqual(tax["CORRECT_ABSTENTION"], 1)
        self.assertEqual(tax["FALSE_ACCEPTANCE"], 1)
        self.assertEqual(tax["BLIND_ABSTENTION"], 1)

    def test_false_acceptance_is_a_constraint_not_a_score(self):
        """It must be reported as a rate to be bounded, with no 'higher is
        better' framing anywhere in the payload."""
        cases = [_case("c3", abstain_expected=True)]
        verdicts = [{"case": "c3", "mechanism": "M", "verdict": "WRONG",
                     "correct": False, "category": "equivalent", "conf": 0.8}]
        tax = abstention_taxonomy(verdicts, cases)["M"]
        self.assertIn("false_acceptance_rate", tax)
        self.assertEqual(tax["constraint"], "false_acceptance_rate <= epsilon")
        self.assertNotIn("score", tax)

    def test_prudence_and_inability_are_separated(self):
        """Two mechanisms with an identical abstention RATE must be
        distinguishable by outcome quality."""
        cases = [_case("p1", ambiguous=True), _case("p2")]
        prudent = [
            {"case": "p1", "mechanism": "P", "verdict": "HANDLED_VIA_ABSTENTION",
             "correct": True, "category": "ambiguous", "conf": None},
            {"case": "p2", "mechanism": "P", "verdict": "CORRECT",
             "correct": True, "category": "equivalent", "conf": 0.9}]
        unable = [
            {"case": "p1", "mechanism": "U", "verdict": "OVERCOMMITTED",
             "correct": False, "category": "ambiguous", "conf": 0.9},
            {"case": "p2", "mechanism": "U", "verdict": "ABSTAINED",
             "correct": False, "category": "equivalent", "conf": None}]
        t = abstention_taxonomy(prudent + unable, cases)
        self.assertEqual(t["P"]["abstention_rate"], t["U"]["abstention_rate"])
        self.assertGreater(t["P"]["CORRECT_ABSTENTION"],
                           t["U"]["CORRECT_ABSTENTION"])
        self.assertGreater(t["U"]["BLIND_ABSTENTION"],
                           t["P"]["BLIND_ABSTENTION"])


if __name__ == "__main__":
    unittest.main(verbosity=2)
