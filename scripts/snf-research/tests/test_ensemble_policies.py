"""SNF-E arbitration tests (D-4).

The pilot ran ONE rule. Worse: inspection showed several named rules were
behavioural ALIASES — "majority", "abstain_conflict" and "veto" shared the same
promote threshold and the same partial-agreement fallback, so a "sweep" over
them would have produced the same numbers under different labels.

These tests pin the six approved policies to DISTINCT, declared behaviour.
DOMAIN_CONDITIONAL is DEFERRED by HPA decision (no domain model exists in the
toy world) and must not be silently implemented.
"""
import unittest

import _t  # noqa: F401

from mechanisms import SNFE, ARBITRATION_POLICIES, POLICY_DEFERRED


class PolicyRegistry(unittest.TestCase):

    def test_six_policies_available(self):
        self.assertEqual(
            sorted(ARBITRATION_POLICIES),
            ["ABSTAIN_ON_CONFLICT", "LEAST_DIVERGENT", "MAJORITY", "QUORUM",
             "UNANIMOUS", "VETO"])

    def test_domain_conditional_is_deferred_not_implemented(self):
        self.assertIn("DOMAIN_CONDITIONAL", POLICY_DEFERRED)
        self.assertNotIn("DOMAIN_CONDITIONAL", ARBITRATION_POLICIES)
        with self.assertRaises(ValueError):
            SNFE(policy="DOMAIN_CONDITIONAL")

    def test_unknown_policy_rejected(self):
        with self.assertRaises(ValueError):
            SNFE(policy="WHATEVER")


class PolicyBehaviourIsDistinct(unittest.TestCase):
    """Behaviour is decided from a member-outcome pattern, not from prose."""

    def _decide(self, policy, cluster, n_answered, n_members=4):
        return SNFE(policy=policy)._decide(cluster_size=cluster,
                                           n_answered=n_answered,
                                           n_members=n_members)

    def test_unanimous_requires_all(self):
        self.assertEqual(self._decide("UNANIMOUS", 4, 4), "PROMOTE")
        self.assertNotEqual(self._decide("UNANIMOUS", 3, 4), "PROMOTE")

    def test_majority_promotes_on_three_of_four(self):
        self.assertEqual(self._decide("MAJORITY", 3, 4), "PROMOTE")
        self.assertNotEqual(self._decide("MAJORITY", 2, 4), "PROMOTE")

    def test_quorum_promotes_on_two(self):
        self.assertEqual(self._decide("QUORUM", 2, 4), "PROMOTE")
        self.assertNotEqual(self._decide("QUORUM", 1, 4), "PROMOTE")

    def test_abstain_on_conflict_never_hedges(self):
        """Any internal disagreement -> ABSTAIN. This is what distinguishes it
        from MAJORITY, which hedges on partial agreement."""
        self.assertEqual(self._decide("ABSTAIN_ON_CONFLICT", 4, 4), "PROMOTE")
        self.assertEqual(self._decide("ABSTAIN_ON_CONFLICT", 3, 4), "ABSTAIN")
        self.assertEqual(self._decide("ABSTAIN_ON_CONFLICT", 2, 4), "ABSTAIN")

    def test_majority_hedges_where_abstain_on_conflict_abstains(self):
        self.assertEqual(self._decide("MAJORITY", 2, 4), "HEDGE")
        self.assertEqual(self._decide("ABSTAIN_ON_CONFLICT", 2, 4), "ABSTAIN")

    def test_veto_blocks_on_member_abstention(self):
        self.assertEqual(self._decide("VETO", 3, 3), "ABSTAIN")   # one member out
        self.assertEqual(self._decide("VETO", 4, 4), "PROMOTE")

    def test_least_divergent_always_answers(self):
        for cluster in (1, 2, 3, 4):
            self.assertEqual(self._decide("LEAST_DIVERGENT", cluster, 4),
                             "PROMOTE")

    def test_no_two_policies_are_aliases(self):
        """Every policy pair must differ on at least one member pattern."""
        patterns = [(c, a) for a in (1, 2, 3, 4) for c in range(1, a + 1)]
        sigs = {}
        for p in ARBITRATION_POLICIES:
            sigs[p] = tuple(self._decide(p, c, a) for c, a in patterns)
        names = sorted(sigs)
        for i, p in enumerate(names):
            for q in names[i + 1:]:
                self.assertNotEqual(sigs[p], sigs[q],
                                    f"{p} and {q} are behavioural aliases")


class CompositeScoreForbidden(unittest.TestCase):

    def test_no_weighted_average_authority(self):
        """A weighted composite must not exist as the ensemble's decision rule
        (commission: 'Do not create a composite authority score')."""
        import inspect
        import mechanisms
        src = inspect.getsource(mechanisms.SNFE)
        for banned in ("wA", "weighted_average", "composite_score"):
            self.assertNotIn(banned, src)


if __name__ == "__main__":
    unittest.main(verbosity=2)
