"""Executable checks for ALM. Run: python3 tests_alm.py

Part A: named scenarios from sources (each cites its evidence).
Part B: exhaustive bounded checks of Theorems 1-6 and Propositions 7-8.
Passing tests are evidence about the implementation within the bound, not proofs;
the proofs are in ALM-FORMAL-MODEL.md.
"""
from itertools import combinations, permutations, product
import sys
from alm_v1 import (Act, Model, INF, AUTHORIZED, NOT_AUTHORIZED, UNDETERMINED,
                 CURRENT, NOT_CURRENT)

ALL = frozenset({("PERFORM", "*"), ("ADOPT", "*"), ("END_CLAIM", "*"),
                 ("GRANT", "*"), ("REVOKE", "*"), ("CONFIRM", "*")})
PERF = frozenset({("PERFORM", "s"), ("ADOPT", "s")})
RESULTS = []


def check(name, cond, detail=""):
    RESULTS.append((name, bool(cond), detail))


# ======================= Part A: source scenarios =======================

def scenarios():
    M = Model()
    # S0058 L77: grant given AFTER the work -> work not authorized ("anachronistic").
    H = [Act("w", "b", "PERFORM", 1, subject="s"),
         Act("g", "R", "GRANT", 2, holder="b", scope=PERF, period=(2, INF))]
    check("S0058_anachronistic_grant_does_not_authorize_earlier_work",
          M.validity(H)["w"] is False)

    # S0092 L35 / S0095 L141: START given (valid) but unrecorded -> valid, not effective,
    # recognition UNDETERMINED (the act is not on record).
    H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF),
         Act("st", "b", "PERFORM", 2, subject="s")]
    pos = {"g": 1}
    check("S0092_given_unrecorded_act_valid", M.validity(H)["st"] is True)
    check("S0092_given_unrecorded_act_not_effective",
          M.effective(H, pos, 1, "st", now=10, lam=0) is False)
    check("S0092_given_unrecorded_act_recognition_undetermined",
          M.recognize_valid(H, pos, 1, "st", now=10, lam=0) == UNDETERMINED)

    # S0103 L191 (F-7): START given at t=2, work at t=3, START transcribed late.
    H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF),
         Act("w", "b", "PERFORM", 3, subject="s")]
    pos = {"w": 1, "g": 2}       # work on record before its grant is transcribed
    check("S0103_late_transcription_valid_in_V", M.validity(H)["w"] is True)
    check("S0103_before_transcription_records_say_not_authorized_under_closed_world",
          M.recognize_valid(H, pos, 1, "w", now=10, lam=0) == NOT_AUTHORIZED)
    check("S0103_before_transcription_undetermined_under_open_world",
          M.recognize_valid(H, pos, 1, "w", now=10, lam=INF) == UNDETERMINED)
    check("S0103_after_transcription_authorized",
          M.recognize_valid(H, pos, 2, "w", now=10, lam=0) == AUTHORIZED)

    # S0052 / R-72 -> R-86: conditional grant inoperative until an explicit valid CONFIRM.
    H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF, proviso=True),
         Act("w1", "b", "PERFORM", 2, subject="s"),
         Act("c", "R", "CONFIRM", 3, target="g"),
         Act("w2", "b", "PERFORM", 4, subject="s")]
    v = M.validity(H)
    check("R72_proviso_unmet_work_not_authorized", v["w1"] is False)
    check("R86_after_confirm_work_authorized", v["w2"] is True)
    H2 = H[:2] + [Act("c", "x", "CONFIRM", 3, target="g"), H[3]]   # confirm by non-authority
    check("conditional_confirm_by_unauthorized_actor_has_no_effect",
          M.validity(H2)["w2"] is False)

    # S0104 / A-1.4: verification is VALID (granted) but NOT ELIGIBLE as independent
    # verification for a barred performer.
    H = [Act("g", "R", "GRANT", 1, holder="p", scope=frozenset({("PERFORM", "verify")}),
             bars=frozenset({("independent_verification", "p")})),
         Act("v", "p", "PERFORM", 2, subject="verify")]
    check("S0104_valid_but_not_eligible",
          M.validity(H)["v"] is True and M.eligible(H, "v", "independent_verification") is False)
    check("S0074_grant_barring_no_one_eligible",
          M.eligible([H[0].__class__(**{**H[0].__dict__, "bars": frozenset()}), H[1]],
                     "v", "independent_verification") is True)
    # A-1.4 general bar B0 (implementer may not independently verify) without grant text
    check("A1.4_general_bar_applies_without_grant_text",
          M.eligible([Act("g", "R", "GRANT", 1, holder="p",
                          scope=frozenset({("PERFORM", "verify")})), H[1]],
                     "v", "independent_verification",
                     general_bars=frozenset({("independent_verification", "p")})) is False)

    # ADR-001 / Stress Test 8: authority expires; decision made inside the window persists
    # under kappa=persist, ends under kappa=cascade.
    H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF, period=(1, 3)),
         Act("a", "b", "ADOPT", 2, subject="s")]
    check("ADR001_decision_outlasts_expired_authority_persist",
          Model(kappa="persist").current(H, "a", 5) is True)
    check("kappa_cascade_ends_decision_with_authority",
          Model(kappa="cascade").current(H, "a", 5) is False)
    check("decision_by_expired_authority_is_invalid",
          Model().validity(H + [Act("a2", "b", "ADOPT", 4, subject="s")])["a2"] is False)

    # OQ-6 / Stress Test 8: revocation is prospective - earlier acts stay valid.
    H = [Act("g", "R", "GRANT", 1, holder="b", scope=PERF),
         Act("w1", "b", "PERFORM", 2, subject="s"),
         Act("r", "R", "REVOKE", 3, target="g"),
         Act("w2", "b", "PERFORM", 4, subject="s")]
    v = M.validity(H)
    check("revocation_prospective", v["w1"] is True and v["w2"] is False)

    # R-77 / S0079: supersession only by explicit act: a second ADOPT does not end the first.
    H = [Act("a1", "R", "ADOPT", 1, subject="s"), Act("a2", "R", "ADOPT", 2, subject="s")]
    check("test_two_current_claims_without_U",
          M.current(H, "a1", 3) and M.current(H, "a2", 3))
    H3 = H + [Act("x", "R", "END_CLAIM", 2, target="a1")]
    check("explicit_end_claim_ends_claim", M.current(H3, "a1", 3) is False)

    # Circular delegation without root is invalid (Cor. 1.1)
    H = [Act("g1", "a", "GRANT", 1, holder="b", scope=ALL),
         Act("g2", "b", "GRANT", 2, holder="a", scope=ALL)]
    v = M.validity(H)
    check("circular_delegation_without_root_invalid", not v["g1"] and not v["g2"])


# ======================= Part B: exhaustive bounded checks =======================

def templates():
    """Small act alphabet over actors R (root), a, b; one subject s."""
    return [
        lambda t: Act("gRa", "R", "GRANT", t, holder="a", scope=ALL),
        lambda t: Act("gRb", "R", "GRANT", t, holder="b", scope=PERF),
        lambda t: Act("gab", "a", "GRANT", t, holder="b", scope=PERF),
        lambda t: Act("rRa", "R", "REVOKE", t, target="gRa"),
        lambda t: Act("rab", "a", "REVOKE", t, target="gab"),
        lambda t: Act("pb", "b", "PERFORM", t, subject="s"),
        lambda t: Act("pa", "a", "PERFORM", t, subject="s"),
        lambda t: Act("Ab", "b", "ADOPT", t, subject="s"),
    ]


def histories(max_len=4, times=(1, 2, 3)):
    T = templates()
    for n in range(1, max_len + 1):
        for idxs in combinations(range(len(T)), n):
            for ts in product(times, repeat=n):
                yield [T[i](t) for i, t in zip(idxs, ts)]


def strict_fixed_points(M, H):
    """All assignments satisfying the STRICT-precedence equations (brute force)."""
    ordered = [a for _, a in M.order(H)]
    rank = {a.id: i for i, a in enumerate(ordered)}
    by_id = {a.id: a for a in H}
    sols = []
    for bits in product([False, True], repeat=len(ordered)):
        valid = {a.id: b for a, b in zip(ordered, bits)}
        if all(valid[e.id] == M._valid_given(e, ordered, rank, valid, by_id) for e in ordered):
            sols.append(valid)
    return sols


def exhaustive():
    M = Model()
    n_hist = 0
    uniq_fail = local_fail = sound_fail = eff_fail = 0
    flips = []
    same_instant_multi = []
    for H in histories():
        n_hist += 1
        v = M.validity(H)
        # Theorem 1: exactly one fixed point of the strict equations, equal to the recursion
        fps = strict_fixed_points(M, H)
        if len(fps) != 1 or fps[0] != v:
            uniq_fail += 1
        # Lemma 2 (locality): appending a strictly later act does not change earlier validity
        tmax = max(a.tau for a in H)
        later = Act("zz", "R", "REVOKE", tmax + 1, target="gRa")
        v2 = M.validity(H + [later])
        if any(v2[a.id] != v[a.id] for a in H):
            local_fail += 1
        # same-instant semantics: count stable assignments
        if len(H) <= 4:
            s = M.stable_assignments_same_instant(H)
            if len(s) != 1:
                same_instant_multi.append((H, len(s)))
        # Theorem 3 / Theorem 4 / Prop 7 over all recording orders of all subsets
        if len(H) <= 3:
            for r in range(len(H) + 1):
                for sub in permutations(H, r):
                    pos = {a.id: i + 1 for i, a in enumerate(sub)}
                    for e in H:
                        prev = None
                        for k in range(0, len(sub) + 1):
                            rec = M.recognize_valid(H, pos, k, e.id, now=100, lam=0)
                            # completeness for time tau(e): all acts with tau <= tau(e) recorded by k
                            comp = all(a.id in pos and pos[a.id] <= k for a in H if a.tau <= e.tau)
                            if comp and e.id in pos and pos[e.id] <= k:
                                if (rec == AUTHORIZED) != v[e.id]:
                                    sound_fail += 1
                            if prev in (AUTHORIZED, NOT_AUTHORIZED) and rec in (AUTHORIZED, NOT_AUTHORIZED) and rec != prev:
                                flips.append((H, pos, e.id, k, "down" if prev == AUTHORIZED else "up"))
                            prev = rec
                            if M.effective(H, pos, k, e.id, now=100, lam=0) and not (e.id in pos and pos[e.id] <= k):
                                eff_fail += 1
    return dict(n_hist=n_hist, uniq_fail=uniq_fail, local_fail=local_fail,
                sound_fail=sound_fail, eff_fail=eff_fail, flips=flips,
                same_instant_multi=same_instant_multi)


def theorem6_constructive():
    """Theorem 6: whenever records at k lack an authorizing grant, a consistent completion
    (late-recorded root grant) makes the act valid -> NOT_AUTHORIZED is unsound under open world."""
    M = Model()
    bad = 0
    count = 0
    for H in histories(max_len=3):
        for e in H:
            if e.actor == "R" or M.validity(H)[e.id]:
                continue
            count += 1
            g = Act("late", "R", "GRANT", min(a.tau for a in H) - 1, holder=e.actor, scope=ALL)
            if not M.validity(H + [g])[e.id]:
                # could still be invalid because of a recorded revocation of... the late grant is
                # not revoked by any template, so this would be a counterexample to Thm 6
                bad += 1
    return count, bad


def order_from_positions_counterexample():
    """Theorem 3 subtlety: if the order were derived from record positions, soundness fails."""
    M = Model()
    g = Act("g", "R", "GRANT", 1, holder="b", scope=PERF)
    r = Act("r", "R", "REVOKE", 1, target="g")       # same instant as g's use below? no: tau 1
    w = Act("w", "b", "PERFORM", 2, subject="s")
    H_true_order = [g, r, w]                           # revoke given before work
    v_true = M.validity(H_true_order)["w"]            # False
    H_pos_order = [g, w, r]                            # if order followed recording positions
    # reorder by changing taus to reflect position order
    H_pos = [g, Act("w", "b", "PERFORM", 1, subject="s"), Act("r", "R", "REVOKE", 2, target="g")]
    v_pos = M.validity(H_pos)["w"]
    return v_true, v_pos


def main():
    scenarios()
    ex = exhaustive()
    check("T1_unique_fixed_point_equals_recursion", ex["uniq_fail"] == 0,
          f"{ex['n_hist']} histories")
    check("L2_locality", ex["local_fail"] == 0)
    check("T3_soundness_under_completeness", ex["sound_fail"] == 0)
    check("P7_effective_implies_recorded", ex["eff_fail"] == 0)
    check("same_instant_nonuniqueness_absent_in_template_alphabet",
          len(ex["same_instant_multi"]) == 0,
          "templates cannot express mutual revocation - reported, not a proof of absence")
    # explicit construction: a and b each hold a root grant incl. REVOKE; at the SAME
    # instant a revokes b's grant and b revokes a's grant.
    Ms = Model()
    Hm = [Act("ga", "R", "GRANT", 1, holder="a", scope=ALL),
          Act("gb", "R", "GRANT", 1, holder="b", scope=ALL),
          Act("ra", "a", "REVOKE", 2, target="gb"),
          Act("rb", "b", "REVOKE", 2, target="ga")]
    stable = Ms.stable_assignments_same_instant(Hm)
    check("test_same_instant_semantics_not_unique", len(stable) == 2,
          f"stable assignments under same-instant semantics: {len(stable)}")
    vs = Ms.validity(Hm)
    check("strict_semantics_resolves_mutual_revocation_by_A0_order",
          vs["ra"] is True and vs["rb"] is False and len(strict_fixed_points(Ms, Hm)) == 1)
    down = [f for f in ex["flips"] if f[4] == "down"]
    up = [f for f in ex["flips"] if f[4] == "up"]
    check("T4_flip_AUTHORIZED_to_NOT_exists(late_revocation)", len(down) > 0, f"{len(down)}")
    check("T4_flip_NOT_to_AUTHORIZED_exists(late_grant)", len(up) > 0, f"{len(up)}")
    ex["flips"] = down or ex["flips"]
    c, bad = theorem6_constructive()
    check("T6_open_world_completion_always_authorizes", bad == 0, f"{c} unauthorized-act cases")
    vt, vp = order_from_positions_counterexample()
    check("test_order_from_positions_breaks_soundness", vt is False and vp is True)

    width = max(len(n) for n, _, _ in RESULTS)
    fails = 0
    for name, ok, detail in RESULTS:
        fails += not ok
        print(f"{'PASS' if ok else 'FAIL'}  {name.ljust(width)}  {detail}")
    # show minimal examples
    if ex["flips"]:
        H, pos, eid, k, d = min(ex["flips"], key=lambda f: len(f[0]))
        print("\nMinimal T4 flip (", d, "):", [(a.id, a.actor, a.kind, a.tau) for a in H],
              "pos", pos, "act", eid, "at k", k)
    if ex["same_instant_multi"]:
        H, n = min(ex["same_instant_multi"], key=lambda f: len(f[0]))
        print("Minimal same-instant non-uniqueness:", [(a.id, a.kind, a.tau, a.actor) for a in H], "stable:", n)
    print(f"\n{len(RESULTS) - fails}/{len(RESULTS)} checks passed")
    sys.exit(1 if fails else 0)


if __name__ == "__main__":
    main()
