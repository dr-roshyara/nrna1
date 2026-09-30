"""Tests for aggregate.py on SYNTHETIC records only (no corpus content; every text field is a placeholder)."""
import unittest

import aggregate as ag


DEFAULT_KIND = {"F-A2e": "GOV", "F-A3g": "EVID", "F-A4": "COMP", "F-A5e": "WORK", "F-A5g": "WORK",
                "F-A3m": "EVID", "F-A6": "GOV", "F-A0": "GOV"}
CE_EFFECT = {"F-A2e": ["evidential"], "F-A3g": ["grant"], "F-A4": ["evidential", "grant"], "F-A5e": ["evidential"],
             "F-A5g": ["grant"], "F-A3m": ["evidential"], "F-A6": ["bar"], "F-A0": ["bar"]}


def op(kind="GOV", **kw):
    o = {"actor": "SYNTHETIC", "action": "SYNTHETIC", "object": "SYNTHETIC", "assigned_kind": kind,
         "typing_basis": "UNKNOWN" if kind == "UNKNOWN" else "ACTION_SEMANTICS",
         "typing_justification": "SYNTHETIC", "split_basis": "NONE", "typing_recorded_before_effect": True}
    if kind == "UNKNOWN": o["ambiguity_reason"] = "SYNTHETIC"
    o.update(kw)
    return o


def rec(test, cls, mech="P-SYN-1", **kw):
    r = {"test": test, "source_id": "SYNTHETIC", "source_version": "SYNTHETIC", "location": "SYNTHETIC",
         "original_wording": "SYNTHETIC", "reconstructed_interpretation": "SYNTHETIC", "classification": cls,
         "mechanism_ref": mech, "confidence": "HIGH", "reader": "SYNTHETIC",
         "independence_class": "SELF", "discovery_channel": "COMPLETE_READING"}
    if test in DEFAULT_KIND:
        r["operation"] = op(DEFAULT_KIND[test])
        r["effect"] = {k: ("SYNTHETIC" if cls == "DIRECT_COUNTEREXAMPLE" and k in CE_EFFECT[test] else None)
                       for k in ("evidential", "grant", "standing", "bar", "promotion", "other")}
    if cls == "AMBIGUOUS":
        r.update(competing_interpretation="SYNTHETIC", competing_classification="NOT_BEARING")
    if cls == "DIRECT_COUNTEREXAMPLE":
        r.update(countermodel_effect_stated=False)
    if test == "F-A6":
        r.update(a6_level="NEITHER_OR_UNCLEAR")
    r.update(kw)
    return r


CORE_TESTS = ("F-A2e", "F-A3g", "F-A4", "F-A5e", "F-A5g", "F-A6")
ALL = {"M-1", "M-2", "M-3", "M-4"}


def supported_core():
    return [rec(t, "DIRECT_SUPPORT") for t in CORE_TESTS]


class Verdict(unittest.TestCase):
    def test_precedence_counterexample_beats_support(self):
        self.assertEqual(ag.verdict([rec("F-A2e", "DIRECT_SUPPORT"), rec("F-A2e", "DIRECT_COUNTEREXAMPLE")], True),
                         "COUNTEREXAMPLE_FOUND")

    def test_live_counterexample_reading_beats_support(self):
        rs = [rec("F-A2e", "DIRECT_SUPPORT"),
              rec("F-A2e", "AMBIGUOUS", competing_classification="DIRECT_COUNTEREXAMPLE")]
        self.assertEqual(ag.verdict(rs, True), "INCONCLUSIVE")

    def test_r1_overlap_case_is_now_decided(self):  # S=1, A=5 (non-counterexample readings) -> SUPPORTED only
        rs = [rec("F-A2e", "DIRECT_SUPPORT")] + [rec("F-A2e", "AMBIGUOUS") for _ in range(5)]
        self.assertEqual(ag.verdict(rs, True), "SUPPORTED")

    def test_absence_is_not_support(self):
        self.assertEqual(ag.verdict([], True), "NOT_EVIDENCED")
        self.assertEqual(ag.verdict([rec("F-A2e", "NOT_EVIDENCED")], True), "NOT_EVIDENCED")

    def test_not_released_is_not_run(self):
        self.assertEqual(ag.verdict([], False), "NOT_RUN")


class Candidate(unittest.TestCase):
    def test_all_core_supported_survives(self):
        self.assertEqual(ag.aggregate(supported_core(), ALL, "UNIVERSAL")["H-F2-1a"], "SURVIVES_T-A")

    def test_untested_core_axiom_is_partially_untested(self):
        rs = [r for r in supported_core() if r["test"] != "F-A5g"]
        self.assertEqual(ag.aggregate(rs, ALL, "UNIVERSAL")["H-F2-1a"], "PARTIALLY_UNTESTED")

    def test_scope_changes_refuted_vs_weakened(self):
        rs = supported_core() + [rec("F-A6", "DIRECT_COUNTEREXAMPLE", mech="P-SYN-2")]
        self.assertEqual(ag.aggregate(rs, ALL, "UNIVERSAL")["H-F2-1a"], "REFUTED_AS_STATED")
        self.assertEqual(ag.aggregate(rs, ALL, "MECHANISM_CLASS")["H-F2-1a"], "WEAKENED")

    def test_a6_counterexample_leaves_replacement_unresolved(self):
        out = ag.aggregate(supported_core() + [rec("F-A6", "DIRECT_COUNTEREXAMPLE")], ALL, "UNIVERSAL")
        self.assertTrue(out["A6_replacement_mechanism"].startswith("UNRESOLVED"))

    def test_axiom_counterexample_makes_props_unsupported_not_false(self):
        out = ag.aggregate(supported_core() + [rec("F-A6", "DIRECT_COUNTEREXAMPLE")], ALL, "UNIVERSAL")
        self.assertEqual({p: v["P-SYN-1"] for p, v in out["propositions"].items()},
                         {"D1": "UNSUPPORTED", "D3": "UNSUPPORTED", "D3+": "UNSUPPORTED", "D5": "UNSUPPORTED"})

    def test_stated_effect_falsifies_only_named_props(self):
        r = rec("F-A6", "DIRECT_COUNTEREXAMPLE", countermodel_effect_stated=True, effect_instantiates=["D3"])
        out = ag.aggregate(supported_core() + [r], ALL, "UNIVERSAL")
        self.assertEqual(out["propositions"]["D3"]["P-SYN-1"], "FALSIFIED")
        self.assertEqual(out["propositions"]["D1"]["P-SYN-1"], "UNSUPPORTED")

    def test_extension_counterexample_does_not_touch_core(self):
        out = ag.aggregate(supported_core() + [rec("F-A0", "DIRECT_COUNTEREXAMPLE")], ALL, "UNIVERSAL")
        self.assertEqual(out["H-F2-1a"], "SURVIVES_T-A")
        self.assertEqual(out["stability_extension"], "REFUTED_AS_STATED")

    def test_minimum_release_marks_authority_questions_not_run(self):
        out = ag.aggregate(supported_core(), {"M-1", "M-4"}, "UNIVERSAL")
        self.assertFalse(out["questions"]["Q-GS"]["run"])
        self.assertFalse(out["questions"]["Q-D4"]["run"])
        self.assertTrue(out["questions"]["F-A6"]["run"])


class Validation(unittest.TestCase):
    def test_reader_may_not_fill_prediction_match(self):
        out = ag.aggregate([rec("F-A2e", "DIRECT_SUPPORT", expected_finding_matched=True)], ALL, "UNIVERSAL")
        self.assertFalse(out["valid"])

    def test_effect_outside_map_rejected(self):
        r = rec("F-A0", "DIRECT_COUNTEREXAMPLE", countermodel_effect_stated=True, effect_instantiates=["D1"])
        self.assertFalse(ag.aggregate([r], ALL, "UNIVERSAL")["valid"])

    def test_ambiguous_needs_competing_classification(self):
        r = rec("F-A4", "AMBIGUOUS"); del r["competing_classification"]
        self.assertFalse(ag.aggregate([r], ALL, "UNIVERSAL")["valid"])

    def test_no_scope_stops(self):
        self.assertEqual(ag.main(["/dev/null"]), 2)

    def test_map_matches_minimal_sets(self):  # 5.1 map is the transpose of the attack's minimal sets (core + ext)
        minimal = {"D1": {"A2e", "A6"}, "D2": {"A3g", "A5g"}, "D3": {"A2e", "A3g", "A4", "A5e", "A5g", "A6"},
                   "D3+": {"A0", "A2e", "A3g", "A3m", "A4", "A5e", "A5g", "A6"}, "D5": {"A0", "A3g", "A3m", "A6"}}
        for ax, ps in ag.PROPS_OF.items():
            self.assertEqual(set(ps), {p for p, s in minimal.items() if ax in s}, ax)


class Typing(unittest.TestCase):
    """r3 (pre-registration 3.0): K-S + O-1 + SP-S + UNKNOWN by enumeration."""

    def bad(self, r):
        return not ag.aggregate([r], ALL, "UNIVERSAL")["valid"]

    def test_effect_actor_object_lexicon_are_not_typing_bases(self):
        for basis in ("EFFECT", "ACTOR", "OBJECT", "LEXICON"):
            self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=op("GOV", typing_basis=basis))), basis)

    def test_typing_must_precede_effect(self):
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT",
                                     operation=op("GOV", typing_recorded_before_effect=False))))

    def test_operation_and_effect_blocks_required(self):
        r = rec("F-A2e", "DIRECT_SUPPORT"); del r["effect"]
        self.assertTrue(self.bad(r))

    def test_reader_splitting_rejected_source_split_needs_quote(self):
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=op("GOV", split_basis="READER"))))
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=op("GOV", split_basis="SOURCE_EXPLICIT"))))
        self.assertFalse(self.bad(rec("F-A2e", "DIRECT_SUPPORT",
                                      operation=op("GOV", split_basis="SOURCE_EXPLICIT", split_quote="SYNTHETIC"))))

    def test_declared_type_needs_declaration(self):
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=op("GOV", typing_basis="SOURCE_DECLARED"))))

    def test_counterexample_needs_matching_kind(self):  # an A2e counterexample must be a GOV operation
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_COUNTEREXAMPLE", operation=op("EVID"))))

    def test_counterexample_needs_stated_transition(self):
        r = rec("F-A2e", "DIRECT_COUNTEREXAMPLE"); r["effect"]["evidential"] = None
        self.assertTrue(self.bad(r))

    def test_a4_counterexample_needs_comp_and_two_components(self):
        r = rec("F-A4", "DIRECT_COUNTEREXAMPLE"); r["effect"]["grant"] = None
        self.assertTrue(self.bad(r))
        self.assertTrue(self.bad(rec("F-A4", "DIRECT_COUNTEREXAMPLE", operation=op("GOV"))))

    def test_unknown_equal_outcomes_fix_classification(self):
        o = op("UNKNOWN", admissible_kinds=["GOV", "WORK"],
               outcome_by_kind={"GOV": "NOT_BEARING", "WORK": "NOT_BEARING"})
        self.assertFalse(self.bad(rec("F-A2e", "NOT_EVIDENCED", operation=o)))
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=o)))

    def test_unknown_differing_outcomes_are_ambiguous_with_most_adverse(self):
        o = op("UNKNOWN", admissible_kinds=["GOV", "EVID"],
               outcome_by_kind={"GOV": "DIRECT_COUNTEREXAMPLE", "EVID": "NOT_BEARING"})
        good = rec("F-A2e", "AMBIGUOUS", operation=o, competing_classification="DIRECT_COUNTEREXAMPLE")
        self.assertFalse(self.bad(good))
        self.assertEqual(ag.aggregate(supported_core() + [good], ALL, "UNIVERSAL")["questions"]["F-A2e"]["verdict"],
                         "INCONCLUSIVE")
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_COUNTEREXAMPLE", operation=o)))  # never auto-counterexample
        self.assertTrue(self.bad(rec("F-A2e", "AMBIGUOUS", operation=o)))              # weaker competing reading

    def test_unknown_never_auto_support(self):
        o = op("UNKNOWN", admissible_kinds=["GOV", "EVID"],
               outcome_by_kind={"GOV": "DIRECT_SUPPORT", "EVID": "NOT_BEARING"})
        self.assertTrue(self.bad(rec("F-A2e", "DIRECT_SUPPORT", operation=o)))

    def test_unknown_needs_enumeration_on_kind_specific_tests(self):
        self.assertTrue(self.bad(rec("F-A2e", "NOT_EVIDENCED", operation=op("UNKNOWN"))))

    def test_a6_testable_without_typing(self):  # A6 is kind-free: an UNKNOWN operation can refute it
        r = rec("F-A6", "DIRECT_COUNTEREXAMPLE", operation=op("UNKNOWN"))
        out = ag.aggregate(supported_core() + [r], ALL, "UNIVERSAL")
        self.assertTrue(out["valid"])
        self.assertEqual(out["questions"]["F-A6"]["verdict"], "COUNTEREXAMPLE_FOUND")
        self.assertEqual(out["questions"]["F-A6"]["coverage"], {"N_typed": 1, "N_unknown": 1})

    def test_unknown_over_qualifying_kinds_can_be_counterexample(self):  # A3g: EVID vs EVIDREF both qualify
        o = op("UNKNOWN", admissible_kinds=["EVID", "EVIDREF"],
               outcome_by_kind={"EVID": "DIRECT_COUNTEREXAMPLE", "EVIDREF": "DIRECT_COUNTEREXAMPLE"})
        self.assertFalse(self.bad(rec("F-A3g", "DIRECT_COUNTEREXAMPLE", operation=o)))


if __name__ == "__main__":
    unittest.main()
