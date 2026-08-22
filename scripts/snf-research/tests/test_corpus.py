"""Corpus discipline tests (Q-4 decision, HPA).

    "Templates generate surface cases; they must not automatically generate
     semantic gold. The gold relationship must be explicitly declared and
     independently reviewable. Otherwise we risk testing whether the generator
     agrees with itself."
"""
import unittest

import _t  # noqa: F401

import corpus_1000
from corpus_1000 import build_corpus, TEMPLATES, RELATIONS, FAMILIES


class GoldIndependence(unittest.TestCase):

    def test_corpus_builder_never_imports_a_mechanism(self):
        import inspect
        src = inspect.getsource(corpus_1000)
        for banned in ("from mechanisms", "import mechanisms", "SNFA", "SNFB",
                       "SNFC", "SNFD", "SNFE", "interpret("):
            self.assertNotIn(banned, src,
                             f"gold must not come from a mechanism ({banned})")

    def test_every_template_declares_its_relation_by_hand(self):
        for t in TEMPLATES:
            self.assertIn(t.relation, RELATIONS, t.name)
            self.assertIn(t.family, FAMILIES, t.name)
            self.assertTrue(t.rationale.strip(),
                            f"{t.name}: a declared relation needs a written "
                            f"rationale to be independently reviewable")

    def test_relation_is_never_inferred_from_the_metric(self):
        import inspect
        src = inspect.getsource(corpus_1000)
        self.assertNotIn("d_snf", src,
                         "the declared relation must not be derived from d_snf")


class CorpusShape(unittest.TestCase):

    @classmethod
    def setUpClass(cls):
        cls.cases = build_corpus()

    def test_size(self):
        self.assertGreaterEqual(len(self.cases), 1000)

    def test_ids_unique(self):
        ids = [c["id"] for c in self.cases]
        self.assertEqual(len(ids), len(set(ids)))

    def test_every_case_has_declared_gold(self):
        for c in self.cases:
            self.assertIn("relation", c)
            self.assertIn("family", c)
            self.assertIn("gold", c)
            self.assertIn("irs", c["gold"])
            self.assertIn("ambiguous", c["gold"])
            self.assertIn("abstain_expected", c["gold"])
            n_expected = 1 if c["gold"]["ambiguous"] else 2
            self.assertEqual(len(c["expressions"]), n_expected, c["id"])

    def test_no_mechanism_competence_covers_a_majority_of_families(self):
        """Corpus balance target from the plan: the corpus must be able to make
        each mechanism fail somewhere, so no single family dominates."""
        from collections import Counter
        counts = Counter(c["family"] for c in self.cases)
        biggest = max(counts.values())
        self.assertLess(biggest / len(self.cases), 0.20,
                        f"family distribution too concentrated: {counts}")

    def test_hard_families_present(self):
        from collections import Counter
        fams = Counter(c["family"] for c in self.cases)
        for required in ("optional_role_presence", "unknown_vs_not_expressed",
                         "false_consensus_trap", "abstain_expected",
                         "argument_inversion", "negation_vs_predicate"):
            self.assertGreater(fams[required], 0, f"missing family {required}")

    def test_presence_traps_are_declared_distinct_with_a_rationale(self):
        """The presence trap is exactly the case where gold IRs are CLOSE but
        the declared relation is DISTINCT. That declaration is the reviewable
        artifact; it must never be softened to EQUIVALENT."""
        traps = [c for c in self.cases
                 if c["family"] == "optional_role_presence"]
        self.assertTrue(traps)
        for c in traps:
            self.assertEqual(c["relation"], "DISTINCT", c["id"])
            self.assertTrue(c["rationale"].strip())

    def test_determinism(self):
        a = [c["id"] for c in build_corpus()]
        b = [c["id"] for c in build_corpus()]
        self.assertEqual(a, b)


class ToyWorldHonesty(unittest.TestCase):
    """Q-1/Q-2 decisions: no real-language claims, and the excluded families
    must be recorded as excluded rather than faked."""

    def test_excluded_families_declared(self):
        self.assertTrue(corpus_1000.EXCLUDED_FAMILIES)
        for fam in ("translation", "metaphor", "pragmatics", "legal_semantics",
                    "narrative_semantics", "institutional_meaning",
                    "procedural_semantics", "technical_ontology"):
            self.assertIn(fam, corpus_1000.EXCLUDED_FAMILIES)

    def test_excluded_families_are_not_built(self):
        built = {c["family"] for c in build_corpus()}
        for fam in corpus_1000.EXCLUDED_FAMILIES:
            self.assertNotIn(fam, built)

    def test_corpus_carries_the_toy_world_limitation(self):
        self.assertIn("toy", corpus_1000.SCOPE_NOTE.lower())
        self.assertIn("not", corpus_1000.SCOPE_NOTE.lower())


if __name__ == "__main__":
    unittest.main(verbosity=2)
