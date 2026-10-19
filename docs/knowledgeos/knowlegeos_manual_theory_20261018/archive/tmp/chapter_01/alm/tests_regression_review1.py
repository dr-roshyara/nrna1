"""Named regression tests for every counterexample accepted in review 1 (and the author's
v1-case findings). Each test asserts the EXPECTED-CORRECT behaviour per the cited finding.

On the unchanged baseline these are EXPECTED TO FAIL; they are reported as KNOWN-DEFECT,
not as passing tests. A future authorized revision must turn them into PASS.
Run: python3 -B tests_regression_review1.py
"""
from alm import Act, Model, INF

PERF = frozenset({("PERFORM", "s"), ("ADOPT", "s")})
ALL = frozenset({(k, "*") for k in ["PERFORM", "ADOPT", "END_CLAIM", "GRANT", "REVOKE", "CONFIRM"]})
out = []


def reg(name, finding, expected_correct, observed):
    out.append((name, finding, expected_correct == observed, expected_correct, observed))


C = Model(kappa="cascade")
H = [Act("g1", "R", "GRANT", 1, holder="b", scope=PERF, period=(1, 3)), Act("a", "b", "ADOPT", 2, subject="s"),
     Act("g2", "R", "GRANT", 4, holder="b", scope=PERF, period=(4, INF))]
reg("REG_CE1_cascade_uses_the_authorizing_grant", "C-1", False, C.current(H, "a", 5))

H = [Act("x", "R", "END_CLAIM", 1, target="a"), Act("a", "R", "ADOPT", 2, subject="s")]
reg("REG_CE2_end_claim_must_follow_adoption", "C-4", True, Model().current(H, "a", 3))

H = [Act("gRa", "R", "GRANT", 1, holder="a", scope=ALL), Act("gab", "a", "GRANT", 2, holder="b", scope=PERF),
     Act("r", "R", "REVOKE", 3, target="gRa"), Act("pb", "b", "PERFORM", 4, subject="s")]
reg("REG_CE3_delta_is_a_parameter_not_fixed_no", "C-2 (RD 1 delta)",
    "parameterized", "fixed-no" if C.validity(H)["pb"] else "fixed-yes")

H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF),
     Act("gc", "R", "GRANT", 1, holder="c", scope=frozenset({("REVOKE", "g")})),
     Act("r", "c", "REVOKE", 2, target="g"), Act("w", "b", "PERFORM", 3, subject="s")]
reg("REG_CE4_target_specific_revoke_scope", "C-3", True, Model().validity(H)["r"])

H = [Act("g1", "R", "GRANT", 1, holder="p", scope=frozenset({("PERFORM", "verify")}), bars=frozenset({("iv", "p")})),
     Act("g2", "R", "GRANT", 1, holder="p", scope=frozenset({("PERFORM", "verify")})),
     Act("v", "p", "PERFORM", 2, subject="verify")]
reg("REG_CE5_bar_any_all_is_parameter", "C-5", "parameterized",
    "all-must-bar" if Model().eligible(H, "v", "iv") else "any-bars")

H = [Act("e", "R", "PERFORM", 1, subject="s")]
reg("REG_CE6_recorded_root_act_positive_under_r11", "C-8/F-1", "AUTHORIZED",
    Model().recognize_valid(H, {"e": 1}, 1, "e", now=10**9, lam=INF))

H = [Act("g", "R", "GRANT", 1, holder="b", scope=frozenset({("END_CLAIM", "s")})),
     Act("x", "b", "END_CLAIM", 2, target="a"), Act("a", "R", "ADOPT", 3, subject="s")]
M = Model()
reg("REG_CE7_theorem3_soundness_end_claim", "P-1 (BLOCKER)", M.validity(H)["x"],
    M.recognize_valid(H, {"g": 1, "x": 2}, 2, "x", now=100, lam=0) == "AUTHORIZED")

g = Act("g", "R", "GRANT", 1, holder="b", scope=PERF)
r = Act("r", "R", "REVOKE", 2, target="g")
w = Act("w", "b", "PERFORM", 2, subject="s")
reg("REG_CE8_result_independent_of_unrecorded_tie_order", "P-2",
    M.validity([g, r, w])["w"], M.validity([g, w, r])["w"])

H = [Act("g", "R", "GRANT", 3, holder="b", scope=PERF, period=(1, INF)), Act("w", "b", "PERFORM", 4, subject="s")]
reg("REG_CE10_wellformedness_from_ge_tau_enforced", "P-10", "rejected-or-flagged",
    "accepted" if M.validity(H)["w"] else "rejected-or-flagged")

H = [Act("e1", "R", "ADOPT", 1, subject="s", claim_period=(1, 3))]
reg("REG_CE12_separate_conventions_grant_vs_claim", "C-11 (C14)", "separate-parameters",
    "one-parameter")

H = [Act("A", "R", "ADOPT", 1, subject="proc"), Act("x", "R", "END_CLAIM", 1, target="A")]
H2 = [Act("x", "R", "END_CLAIM", 1, target="A"), Act("A", "R", "ADOPT", 1, subject="proc")]
reg("REG_V1_CASE12_same_moment_order", "author / v1 case 12", (False, True),
    (M.current(H, "A", 1), M.current(H2, "A", 1)))

reg("REG_IND1_is_authority_not_eligibility", "E-8", "authority", "eligibility (V4/B0)")
reg("REG_IND2_is_relational_same_work", "E-5", "relational", "actor-level")

if __name__ == "__main__":
    w_ = max(len(o[0]) for o in out)
    for name, f, ok, exp, obs in out:
        print(f"{'PASS' if ok else 'KNOWN-DEFECT'}  {name.ljust(w_)}  [{f}] expected={exp} observed={obs}")
    print(f"\n{sum(o[2] for o in out)}/{len(out)} pass on this baseline "
          f"({sum(not o[2] for o in out)} known defects)")
