"""ALM-r1 scenario tests: (A) the 13 review-1 regressions, re-encoded behaviourally;
(B) r1.1 conformance; (C) policy-dependent consequences; (D) corpus + v1 in-scope cases;
(E) the seven scoped independence rules.

Every configuration is stated in full (no hidden defaults). Labels:
  CONF  = must hold under r1.1 (lam+=0, lam-=inf) for BOTH values of C1 unless stated
  POL   = consequence of a stated policy value (shown for each alternative)
  REG   = review-1 regression
Run: python3 -B tests_r1_scenarios.py
"""
import sys
from alm_r1 import Act, Theta, History, Entry, rec, eff, usable_from, rec_at_date, INF, ROOT

ROOTSET = frozenset({("R", ("*", "*"))})
RES = []


def T(**kw):
    """Explicit configuration builder: every key MUST be given (raises otherwise)."""
    need = {"C1", "RD1", "delta", "BASIS", "C10", "iota_g", "iota_c", "C6", "BAR"}
    missing = need - kw.keys()
    if missing:
        raise TypeError(f"configuration must state {sorted(missing)}")
    return Theta(root=ROOTSET, **kw)


def cfgs(**fixed):
    """All combinations of the remaining parameters (to show results do not depend on them)."""
    from itertools import product
    grid = {"C1": ["b"], "RD1": [{"decision": "persist"}, {"decision": "cascade"}],
            "delta": ["independent", "dependent"], "BASIS": ["any", "all"],
            "C10": ["given", "no_same_instant"], "iota_g": ["closed", "half_open"],
            "iota_c": ["closed", "half_open"], "C6": ["off", "later_prevails"], "BAR": ["any", "all"]}
    grid.update({k: [v] for k, v in fixed.items()})
    keys = list(grid)
    for vals in product(*[grid[k] for k in keys]):
        yield T(**dict(zip(keys, vals)))


def check(label, name, ok, detail=""):
    RES.append((label, name, bool(ok), detail))


BASE = dict(C1="b", RD1={"decision": "persist"}, delta="independent", BASIS="any", C10="given",
            iota_g="closed", iota_c="closed", C6="off", BAR="any")


def th(**over):
    d = dict(BASE)
    d.update(over)
    return T(**d)


PERF = frozenset({("PERFORM", ("implement", "w")), ("ADOPT", "s"), ("PERFORM", ("verify", "w"))})

# ============================ (A) review-1 regressions ============================
# CE1 (C-1): cascade uses the AUTHORIZING grant(s). POL: RD1 = cascade vs persist.
H = [Act("g1", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF, period=(1, 3)),
     Act("a", "ADOPT", "b", tau=2, ord=0, subject="s"),
     Act("g2", "GRANT", "R", tau=4, ord=0, grantee="b", scope=PERF, period=(4, INF))]
check("REG/POL", "REG_CE1_cascade_uses_authorizing_grant",
      History(H, th(RD1={"decision": "cascade"})).current("a", 5) is False
      and History(H, th(RD1={"decision": "persist"})).current("a", 5) is True,
      "cascade: not current; persist: current")

# CE2 (C-4): END before ADOPT has no effect (r1.1 'later'). CONF over all configs.
H = [Act("x", "END", "R", tau=1, ord=0, target="a"), Act("a", "ADOPT", "R", tau=2, ord=0, subject="s")]
check("REG/CONF", "REG_CE2_end_must_follow_adoption",
      all(History(H, c).current("a", 3) for c in cfgs()))

# CE3 (C-2): delta is a parameter.
H = [Act("gRa", "GRANT", "R", tau=1, ord=0, grantee="a", scope=frozenset({("*", "*")})),
     Act("gab", "GRANT", "a", tau=2, ord=0, grantee="b", scope=PERF),
     Act("r", "REVOKE", "R", tau=3, ord=0, target="gRa"),
     Act("pb", "PERFORM", "b", performer="p1", tau=4, ord=0, activity="implement", work="w")]
check("REG/POL", "REG_CE3_delta_parameter",
      History(H, th(delta="independent")).valid("pb") is True
      and History(H, th(delta="dependent")).valid("pb") is False,
      "independent: valid; dependent: invalid")

# CE4 (C-3): target-specific REVOKE scope is honoured. CONF.
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF),
     Act("gc", "GRANT", "R", tau=1, ord=1, grantee="c", scope=frozenset({("REVOKE", "g")})),
     Act("r", "REVOKE", "c", tau=2, ord=0, target="g"),
     Act("w", "PERFORM", "b", performer="p1", tau=3, ord=0, activity="implement", work="w")]
check("REG/CONF", "REG_CE4_target_specific_revoke",
      all(History(H, c).valid("r") and not History(H, c).valid("w") for c in cfgs()))

# CE5 (C-5): BAR any/all is a parameter.
VS = frozenset({("PERFORM", ("verify", "w"))})
H = [Act("g1", "GRANT", "R", tau=1, ord=0, grantee="p", scope=VS, bars=frozenset({("iv", "pp", "*")})),
     Act("g2", "GRANT", "R", tau=1, ord=1, grantee="p", scope=VS),
     Act("v", "PERFORM", "p", performer="pp", tau=2, ord=0, activity="verify", work="w")]
check("REG/POL", "REG_CE5_bar_mode_parameter",
      History(H, th(BAR="any")).elig("v", "iv", "w")[0] == "INELIGIBLE"
      and History(H, th(BAR="all")).elig("v", "iv", "w")[0].startswith("ELIGIBLE"),
      "any: ineligible; all: eligible")

# CE6 (C-8/F-1): r1.1 recognises a recorded root act positively. CONF (lam+=0, lam-=inf).
E = [Entry(1, Act("e", "PERFORM", "R", performer="p1", tau=1, ord=0, activity="implement", work="w"))]
check("REG/CONF", "REG_CE6_r11_positive_for_recorded_root_act",
      rec(E, 1, ("valid", "e"), th(), now=10**6, lam_pos=0, lam_neg=INF) == "T")

# CE7 (P-1, BLOCKER): Th-3 soundness under completeness for END. CONF.
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=frozenset({("END", "s")})),
     Act("x", "END", "b", tau=2, ord=0, target="a"),
     Act("a", "ADOPT", "R", tau=3, ord=0, subject="s")]
E = [Entry(1, H[0]), Entry(2, H[1])]
v = History(H, th()).valid("x")
r = rec(E, 2, ("valid", "x"), th(), now=100, lam_pos=0, lam_neg=0)
check("REG/CONF", "REG_CE7_theorem3_end_claim",
      (r == "T") == v and r in ("T", "F"),
      f"V={v} Rec={r} (END precedes its ADOPT: ill-formed, so invalid in both)")

# CE8 (P-2): with the tie order UNRECORDED, recognition is U; with it recorded, it is determined.
g = Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF)
r_ = Act("r", "REVOKE", "R", tau=2, ord=None, target="g")
w_ = Act("w", "PERFORM", "b", performer="p1", tau=2, ord=None, activity="implement", work="w")
E = [Entry(1, g), Entry(2, r_), Entry(3, w_)]
u = rec(E, 3, ("valid", "w"), th(), now=100, lam_pos=0, lam_neg=0)
E2 = [Entry(1, g), Entry(2, Act("r", "REVOKE", "R", tau=2, ord=0, target="g")),
      Entry(3, Act("w", "PERFORM", "b", performer="p1", tau=2, ord=1, activity="implement", work="w"))]
d = rec(E2, 3, ("valid", "w"), th(), now=100, lam_pos=0, lam_neg=0)
check("REG/CONF", "REG_CE8_unrecorded_tie_order_gives_U", u == "U" and d == "F", f"unrecorded: {u}; recorded: {d}")

# CE10 (P-10): anachronistic grant (from < tau) is not valid. CONF.
H = [Act("g", "GRANT", "R", tau=3, ord=0, grantee="b", scope=PERF, period=(1, INF)),
     Act("w", "PERFORM", "b", performer="p1", tau=4, ord=0, activity="implement", work="w")]
check("REG/CONF", "REG_CE10_wellformedness", all(not History(H, c).valid("g") for c in cfgs()))

# CE12 (C-11): grant and claim conventions are separate parameters.
H = [Act("a", "ADOPT", "R", tau=1, ord=0, subject="s", cperiod=(1, 3))]
check("REG/POL", "REG_CE12_separate_conventions",
      History(H, th(iota_g="closed", iota_c="closed")).current("a", 3) is True
      and History(H, th(iota_g="closed", iota_c="half_open")).current("a", 3) is False)

# v1 case 12: same-moment ADOPT/END ordered by the recorded ord.
H1 = [Act("A", "ADOPT", "R", tau=1, ord=0, subject="p"), Act("x", "END", "R", tau=1, ord=1, target="A")]
H2 = [Act("x", "END", "R", tau=1, ord=0, target="A"), Act("A", "ADOPT", "R", tau=1, ord=1, subject="p")]
check("REG/CONF", "REG_V1_CASE12_same_moment_order",
      (History(H1, th()).current("A", 1), History(H2, th()).current("A", 1)) == (False, True))

# IND-1: ACCEPT by a producer of the work is NOT VALID (authority), not merely ineligible.
ACC = frozenset({("ACCEPT", "w"), ("PERFORM", ("implement", "w"))})
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="eng", scope=ACC),
     Act("i", "PERFORM", "eng", performer="p1", tau=2, ord=0, activity="implement", work="w"),
     Act("acc", "ACCEPT", "eng", performer="p9", tau=3, ord=0, work="w")]
check("REG/CONF", "REG_IND1_authority_restriction",
      all(not History(H, c).valid("acc") and History(H, c).basis("acc") for c in cfgs()),
      "basis exists, yet not valid: restriction, not missing grant")

# IND-2 relational: ineligible for w, eligible for w2.
# spec S-1: the wildcard exists only at object level, so atoms are listed explicitly
VS2 = frozenset({("PERFORM", ("verify", "w")), ("PERFORM", ("verify", "w2")),
                 ("PERFORM", ("implement", "w")), ("PERFORM", ("review", "w"))})
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="h", scope=VS2),
     Act("i", "PERFORM", "h", performer="p1", tau=2, ord=0, activity="implement", work="w"),
     Act("v1", "PERFORM", "h", performer="p1", tau=3, ord=0, activity="verify", work="w"),
     Act("v2", "PERFORM", "h", performer="p1", tau=3, ord=1, activity="verify", work="w2")]
Hh = History(H, th())
check("REG/CONF", "REG_IND2_relational",
      Hh.elig("v1", "independent_verification", "w")[0] == "INELIGIBLE"
      and Hh.elig("v2", "independent_verification", "w2")[0].startswith("ELIGIBLE"))

# ============================ (B) r1.1 conformance ============================
# (1) authority at the time of the act (S0058): later grant does not authorize earlier work.
H = [Act("w", "PERFORM", "b", performer="p1", tau=1, ord=0, activity="implement", work="w"),
     Act("g", "GRANT", "R", tau=2, ord=0, grantee="b", scope=PERF, period=(2, INF))]
check("CONF", "S0058_anachronistic_linkage", all(not History(H, c).valid("w") for c in cfgs()))

# (2) own validity period
H = [Act("a", "ADOPT", "R", tau=1, ord=0, subject="s", cperiod=(1, 2))]
check("CONF", "own_period_ends_currency", not History(H, th()).current("a", 5))

# (3) a later authorized ending ends; an unauthorized one does not (v1 11a)
H = [Act("a", "ADOPT", "R", tau=1, ord=0, subject="s"), Act("x", "END", "m", tau=2, ord=0, target="a")]
check("CONF", "unauthorized_end_no_effect", all(History(H, c).current("a", 3) for c in cfgs()))

# recognition: never 'not authorized' under r1.1, even with a recorded revocation (Th-5 corollary)
g = Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF)
r_ = Act("r", "REVOKE", "R", tau=2, ord=0, target="g")
w_ = Act("w", "PERFORM", "b", performer="p1", tau=3, ord=0, activity="implement", work="w")
E = [Entry(1, g), Entry(2, r_), Entry(3, w_)]
check("CONF", "r11_never_F_even_with_recorded_revocation",
      rec(E, 3, ("valid", "w"), th(), now=100, lam_pos=0, lam_neg=INF) == "U"
      and History([g, r_, w_], th()).valid("w") is False)

# S0092: given but unrecorded -> valid in V, U in R, not effective
H = [g, Act("st", "PERFORM", "b", performer="p1", tau=2, ord=0, activity="implement", work="w")]
E = [Entry(1, g)]
check("CONF", "S0092_given_unrecorded",
      History(H, th()).valid("st") and rec(E, 1, ("valid", "st"), th(), 100, 0, INF) == "U"
      and not eff(E, 1, "st", th(), 100, 0, INF))

# S0103: late transcription: C1=b recognises after transcription; C1=a does not validate
#        work done before the grant's record time.
gg = Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF)
ww = Act("w", "PERFORM", "b", performer="p1", tau=3, ord=0, activity="implement", work="w")
E = [Entry(1, ww, rho=3), Entry(2, gg, rho=5)]
rb = (rec(E, 1, ("valid", "w"), th(C1="b"), 100, 0, INF), rec(E, 2, ("valid", "w"), th(C1="b"), 100, 0, INF))
ra = rec(E, 2, ("valid", "w"), th(C1="a"), 100, 0, 0)
check("POL", "S0103_C1b_vs_C1a",
      rb == ("U", "T") and ra == "F",
      f"C1=b: k1 {rb[0]}, k2 {rb[1]}; C1=a (closed world): {ra}")

# ============================ (C) policy consequences ============================
# T1-20 horizons: same records, three horizon settings
check("POL", "T1-20_horizons_change_negative_only",
      [rec([Entry(1, ww)], 1, ("valid", "w"), th(), 100, 0, ln) for ln in (INF, 98, 50, 0)] == ["U", "U", "F", "F"],
      "lam-=inf: U; window still open (3 > 100-98): U; closed: F")

# C10: simultaneous mutual revocation
ALLS = frozenset({("*", "*")})
Hm = [Act("ga", "GRANT", "R", tau=1, ord=0, grantee="a", scope=ALLS),
      Act("gb", "GRANT", "R", tau=1, ord=1, grantee="b", scope=ALLS),
      Act("ra", "REVOKE", "a", tau=2, ord=0, target="gb"),
      Act("rb", "REVOKE", "b", tau=2, ord=1, target="ga")]
given = History(Hm, th(C10="given"))
nsi = History(Hm, th(C10="no_same_instant"))
check("POL", "C10_mutual_revocation",
      (given.valid("ra"), given.valid("rb")) == (True, False)
      and (nsi.valid("ra"), nsi.valid("rb")) == (True, True),
      "given: first recorded wins; no_same_instant: both take effect")

# C6 later prevails
H = [Act("a1", "ADOPT", "R", tau=1, ord=0, subject="s"), Act("a2", "ADOPT", "R", tau=2, ord=0, subject="s")]
check("POL", "C6_two_current_vs_later_prevails",
      History(H, th(C6="off")).current("a1", 3) and not History(H, th(C6="later_prevails")).current("a1", 3))

# conditional grant (S0052, n=1): inoperative until a valid CONFIRM
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="b", scope=PERF, proviso=True),
     Act("w1", "PERFORM", "b", performer="p1", tau=2, ord=0, activity="implement", work="w"),
     Act("c", "CONFIRM", "R", tau=3, ord=0, target="g"),
     Act("w2", "PERFORM", "b", performer="p1", tau=4, ord=0, activity="implement", work="w")]
Hc = History(H, th())
check("CONF", "S0052_conditional_grant", (Hc.valid("w1"), Hc.valid("w2")) == (False, True))

# ============================ (D) dated questions, effectiveness ============================
E = [Entry(1, gg), Entry(2, ww)]
res, rng = rec_at_date(E, d=5, witnesses=set(), q=("valid", "w"), theta=th(), now_of=lambda k: 100,
                       lam_pos=0, lam_neg=INF)
res2, rng2 = rec_at_date(E, d=5, witnesses={(4, 2)}, q=("valid", "w"), theta=th(), now_of=lambda k: 100,
                         lam_pos=0, lam_neg=INF)
check("CONF", "S-8_no_clock_undetermined_with_witness_determined",
      res == "U" and res2 == "T", f"no witness: {res} on {rng}; witness (4,2): {res2} on {rng2}")
check("CONF", "usable_from_position", usable_from(E, "w", th(), 100, 0, INF) == 2)

# ============================ (E) independence rules ============================
H = [Act("g", "GRANT", "R", tau=1, ord=0, grantee="h", scope=VS2),
     Act("i", "PERFORM", "h", performer="p1", tau=2, ord=0, activity="implement", work="w"),
     Act("rv", "PERFORM", "h", performer="p1", tau=3, ord=0, activity="review", work="w")]
fl = History(H, th()).elig("rv", "governance_review", "w")
check("CONF", "IND3_permit_with_disclosure", fl[0].startswith("ELIGIBLE") and fl[1], str(fl))
H2 = H[:2] + [Act("rv", "PERFORM", "h", performer="p1", tau=3, ord=0, activity="review", work="w",
                  attested=True, disclosure=True)]
fl2 = History(H2, th()).elig("rv", "governance_review", "w")
check("CONF", "IND7_attested_grade_and_IND3_disclosed", fl2 == ("ELIGIBLE_ATTESTED", []), str(fl2))
check("SCOPE", "IND4_closure_IND6_gate_rule_not_in_core", True, "declared out of scope (Phase A A3)")

if __name__ == "__main__":
    w = max(len(n) for _, n, _, _ in RES)
    bad = 0
    for lab, n, ok, det in RES:
        bad += not ok
        print(f"{'PASS' if ok else 'FAIL'}  {lab:9s} {n.ljust(w)}  {det}")
    print(f"\n{len(RES) - bad}/{len(RES)} passed")
    sys.exit(1 if bad else 0)
