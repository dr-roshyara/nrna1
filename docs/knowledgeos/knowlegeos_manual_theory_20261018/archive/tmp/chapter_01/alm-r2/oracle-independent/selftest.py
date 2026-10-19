"""Self-tests for oracle.py.  Expected values are derived BY HAND from
ALM-R2-SPEC.md; each derivation is in the comment above the check.

Run:  python3 selftest.py
"""
import copy
import random
import sys
import os

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import oracle as O  # noqa: E402

PASS = FAIL = 0


def check(name, got, exp):
    global PASS, FAIL
    if got == exp:
        PASS += 1
    else:
        FAIL += 1
        print("FAIL %s: got %r expected %r" % (name, got, exp))


def raises(name, fn):
    global PASS, FAIL
    try:
        fn()
    except O.SpecError:
        PASS += 1
        return
    FAIL += 1
    print("FAIL %s: expected SpecError" % name)


def TH(**kw):
    """Theta with every key stated (no defaults in the oracle)."""
    t = {"C1": "b",
         "RD1": {"%s|%s" % (c, e): "persist" for c in O.CKINDS for e in O.EKINDS},
         "delta": {"expiry": "independent", "revocation": "independent"},
         "BASIS": "any", "C10": "no_same_instant", "iota_g": "closed", "iota_c": "closed",
         "C6": "off", "BAR": "any", "C15": "no",
         "root": [["r", ["*", "*"]]]}
    t.update(kw)
    return t


def RP(**kw):
    p = {"lam_pos": 0, "lam_neg": 0, "upsilon": "does_not_count", "pi": "open_ended",
         "D_tau": [1, 2], "D_rho": [1, 2, 3], "B": 10000}
    p.update(kw)
    return p


def G(id, holder, grantee, scope, tau, frm=None, until="inf", proviso=False, bars=None, ord=None):
    return {"id": id, "kind": "GRANT", "holder": holder, "grantee": grantee, "scope": scope,
            "period": [tau if frm is None else frm, until], "proviso": proviso,
            "bars": bars or [], "tau": tau, "ord": ord}


def P(id, holder, tau, activity="implement", work="w", performer=None, attested=False, ord=None):
    return {"id": id, "kind": "PERFORM", "holder": holder, "performer": performer or holder,
            "activity": activity, "work": work, "tau": tau, "attested": attested,
            "disclosure": False, "ord": ord}


def R(id, holder, target, tau, kind="REVOKE", ord=None):
    return {"id": id, "kind": kind, "holder": holder, "target": target, "tau": tau, "ord": ord}


def A(id, holder, subject, ckind, tau, cp=(None, "inf"), ord=None):
    return {"id": id, "kind": "ADOPT", "holder": holder, "subject": subject, "ckind": ckind,
            "cperiod": [tau if cp[0] is None else cp[0], cp[1]], "tau": tau, "ord": ord}


def HIS(*acts, rho=None):
    return {"acts": list(acts), "rho": rho or {}}


PW = [["PERFORM", ["implement", "w"]]]          # scope for implement on w

# ===================================================================== S-3
raises("S-3 no default C1", lambda: O.valid(HIS(), {k: v for k, v in TH().items() if k != "C1"}))
raises("S-3 missing RD1 key", lambda: O.valid(HIS(), TH(RD1={"decision|expiry": "persist"})))
raises("S-3 missing B", lambda: O.rec([], 0, ["valid", "x"], TH(), {k: v for k, v in RP().items() if k != "B"}, 1))

# ===================================================================== S-4.1
# C10=given: g (tau1, ord2), revoke x of g (tau1, ord1). prec(g,x) iff (1,2)<(1,1): false
# -> avail(g,x) false -> Form(x) false -> Valid(x)=F.  Swap ords -> (1,1)<(1,2) -> Form T;
# Need(x)={(REVOKE,g)}, holder r root (*,*) covers -> Auth T -> Valid T.
h = HIS(G("g", "r", "h", PW, 1, ord=2), R("x", "r", "g", 1, ord=1))
check("4.1 given ord later", O.valid(h, TH(C10="given"))["x"], False)
h = HIS(G("g", "r", "h", PW, 1, ord=1), R("x", "r", "g", 1, ord=2))
check("4.1 given ord earlier", O.valid(h, TH(C10="given"))["x"], True)
# no_same_instant: tau(g)=tau(x)=1, 1<1 false -> Form(x) false.
check("4.1 no_same_instant tie", O.valid(h, TH())["x"], False)
# C10=given with duplicate (tau,ord) is rejected (spec: "must be defined and distinct").
raises("4.1 given dup ord", lambda: O.valid(HIS(G("g", "r", "h", PW, 1, ord=1), R("x", "r", "g", 1, ord=1)), TH(C10="given")))

# a_tau: g by r to h tau1 rho5; e PERFORM by h tau3 rho3.  avail(g,e) needs rho(g)=5<=tau(e)=3: no
# -> Basis(e)=∅ (h not root) -> Auth F.  Under b: avail = prec only -> g alive -> Auth T.
h = HIS(G("g", "r", "h", PW, 1), P("e", "h", 3), rho={"g": 5, "e": 3})
check("4.1/4.7 a_tau cut", O.auth(h, TH(C1="a_tau"))["e"], "F")
check("4.1/4.7 b", O.auth(h, TH(C1="b"))["e"], "T")
# a_tau, rho(g)=2<=3 -> T.
h2 = HIS(G("g", "r", "h", PW, 1), P("e", "h", 3), rho={"g": 2, "e": 3})
check("4.7 a_tau recorded in time", O.auth(h2, TH(C1="a_tau"))["e"], "T")
# a_rho with rho(g)=5, rho(e)=6: inst(g)=5, Form(g): from=1 >= inst(g)=5? no -> Valid(g)=F
# -> Basis(e)=∅ -> Auth(e)=F.  (Finding F-06: late recording invalidates a back-dated period.)
h3 = HIS(G("g", "r", "h", PW, 1), P("e", "h", 3), rho={"g": 5, "e": 6})
check("4.7 a_rho Form(g) from<rho", O.valid(h3, TH(C1="a_rho"))["g"], False)
# a_rho with grant period from 5: Form ok; prec(g,e): 5<6 -> alive at e: time=inst(e)=6>=5 -> T.
h4 = HIS(G("g", "r", "h", PW, 1, frm=5), P("e", "h", 3), rho={"g": 5, "e": 6})
check("4.7 a_rho ok", O.valid(h4, TH(C1="a_rho"))["e"], True)
# same but rho(e)=4 -> 5<4 false -> F.  Auth(e) thus depends on rho(e) (Finding F-02 vs S-0).
h5 = HIS(G("g", "r", "h", PW, 1, frm=5), P("e", "h", 3), rho={"g": 5, "e": 4})
check("4.7 a_rho Auth depends on rho(e)", O.auth(h5, TH(C1="a_rho"))["e"], "F")

# ===================================================================== S-4.2
# GRANT from(0) < tau(1) -> Form F -> Valid F (Auth via root is T).
h = HIS(G("g", "r", "h", PW, 1, frm=0))
check("4.2 grant from<tau", O.valid(h, TH())["g"], False)
check("4.2 auth independent of form", O.auth(h, TH())["g"], "T")
# REVOKE of a non-existent target: Form F; Need {(REVOKE,'zz')} defined; root -> Auth T.
h = HIS(R("x", "r", "zz", 1))
check("4.2 revoke missing target valid", O.valid(h, TH())["x"], False)
check("4.2 revoke missing target auth", O.auth(h, TH())["x"], "T")
# a_tau with rho(e)=never: RecOK false -> Valid F.
h = HIS(P("e", "r", 1), rho={"e": "never"})
check("4.2 RecOK", O.valid(h, TH(C1="a_tau"))["e"], False)
check("4.2 RecOK b", O.valid(h, TH(C1="b"))["e"], True)

# ===================================================================== S-4.3
# END with missing target -> Need undefined -> Auth NA, Valid F.
h = HIS(R("x", "r", "nope", 2, kind="END"))
check("4.3 END NA", O.auth(h, TH())["x"], "NA")
# END whose target is a GRANT -> NA.
h = HIS(G("g", "r", "h", PW, 1), R("x", "r", "g", 2, kind="END"))
check("4.3 END on grant NA", O.auth(h, TH())["x"], "NA")
# Empty-scope GRANT by a non-root holder z: Need(e)=∅ -> ∀ vacuous -> ROOT in Basis -> Auth T (F-22).
h = HIS(G("g", "z", "h", [], 1))
check("4.3 empty scope", O.auth(h, TH())["g"], "T")

# ===================================================================== S-4.4
# g [0,5] to h; e PERFORM by h at tau 5.  closed: 5>5 false -> no expiry -> T.
# half_open: 5>=5 -> expiry -> not alive -> F.
h = HIS(G("g", "r", "h", PW, 0, until=5), P("e", "h", 5))
check("4.4 closed", O.valid(h, TH())["e"], True)
check("4.4 half_open", O.valid(h, TH(iota_g="half_open"))["e"], False)
# revocation at 3; e at 4 -> ending -> F;  e at 3 (no_same_instant) -> r not avail -> T.
h = HIS(G("g", "r", "h", PW, 0), R("x", "r", "g", 3), P("e", "h", 4))
check("4.4 revoked", O.valid(h, TH())["e"], False)
h = HIS(G("g", "r", "h", PW, 0), R("x", "r", "g", 3), P("e", "h", 3))
check("4.4 revoke same instant", O.valid(h, TH())["e"], True)
# Started: g period [5,inf] tau 0; e at 3 -> 3>=5 false -> F.
h = HIS(G("g", "r", "h", PW, 0, frm=5), P("e", "h", 3))
check("4.4 not started", O.valid(h, TH())["e"], False)

# ===================================================================== S-4.5
# Chain: r -> g1 to h, scope {(GRANT,(PERFORM,(implement,w)))}; h -> g2 to p scope PW at tau1;
# Need(g2)={(GRANT,(PERFORM,(implement,w)))} literal match in g1 -> Basis(g2)={g1}.
# x revokes g1 at 3; e PERFORM by p at 4.
# Parent(g2,e): g1 has Endings {(revocation,3)}, FirstEnd={revocation}; delta(rev)=independent
# -> survives -> g2 alive -> Valid(e)=T.  dependent -> F.
GP = [["GRANT", ["PERFORM", ["implement", "w"]]]]
h = HIS(G("g1", "r", "h", GP, 0), G("g2", "h", "p", PW, 1), R("x", "r", "g1", 3), P("e", "p", 4))
check("4.5 delta independent", O.valid(h, TH())["e"], True)
check("4.5 delta dependent", O.valid(h, TH(delta={"expiry": "independent", "revocation": "dependent"}))["e"], False)
# delta(expiry) only governs expiry endings: with revocation dependent, still F.
check("4.5 delta exp dep only", O.valid(h, TH(delta={"expiry": "dependent", "revocation": "independent"}))["e"], True)
# proviso: g proviso=1, no CONFIRM -> not alive -> F; CONFIRM c by r at 1 -> T.
h = HIS(G("g", "r", "h", PW, 0, proviso=True), P("e", "h", 2))
check("4.5 proviso unconfirmed", O.valid(h, TH())["e"], False)
h = HIS(G("g", "r", "h", PW, 0, proviso=True), R("c", "r", "g", 1, kind="CONFIRM"), P("e", "h", 2))
check("4.5 proviso confirmed", O.valid(h, TH())["e"], True)

# ===================================================================== S-4.6
# Restr: h is root for (*,*) too (root list); f implement PERFORM by h on w at 1 (Valid via ROOT);
# ACCEPT a by h on w at 2: Restr true -> Auth F.  ACCEPT by other root k -> T.
th = TH(root=[["r", ["*", "*"]], ["h", ["*", "*"]], ["k", ["*", "*"]]])
acc = {"id": "a", "kind": "ACCEPT", "holder": "h", "performer": "h", "work": "w", "tau": 2,
       "attested": False, "disclosure": False}
h = HIS(P("f", "h", 1), acc)
check("4.6 Restr", O.auth(h, th)["a"], "F")
acc2 = dict(acc, holder="k", performer="k")
check("4.6 no Restr", O.auth(HIS(P("f", "h", 1), acc2), th)["a"], "T")
# C15: g to h scope {(GRANT,(*,*))} period [0,10]; h grants g2 period [1,inf] -> not ⊆ [0,10]
# C15=yes -> g not in Basis(g2) -> Auth F; C15=no -> T.
h = HIS(G("g", "r", "h", [["GRANT", ["*", "*"]]], 0, until=10), G("g2", "h", "p", [["*", "*"]], 1))
check("4.6 C15 yes", O.auth(h, TH(C15="yes"))["g2"], "F")
check("4.6 C15 no", O.auth(h, TH(C15="no"))["g2"], "T")
# Covers: (kind,*) wildcard: g scope [(PERFORM,*)] covers (PERFORM,(implement,w)).
h = HIS(G("g", "r", "h", [["PERFORM", "*"]], 0), P("e", "h", 1))
check("4.6 kind wildcard", O.valid(h, TH())["e"], True)
# no nested wildcard: scope [(PERFORM,(implement,*))] does NOT cover (PERFORM,(implement,w)).
h = HIS(G("g", "r", "h", [["PERFORM", ["implement", "*"]]], 0), P("e", "h", 1))
check("4.6 no nested wildcard", O.valid(h, TH())["e"], False)

# ===================================================================== S-4.8
# ADOPT a by r at 1, cperiod [1,10].
h = HIS(A("a", "r", "s", "decision", 1, cp=(1, 10)))
check("4.8 current in", O.current(h, TH(), "a", 5), True)
check("4.8 before adopt", O.current(h, TH(), "a", 0), False)
check("4.8 closed end", O.current(h, TH(), "a", 10), True)
check("4.8 half_open end", O.current(h, TH(iota_c="half_open"), "a", 10), False)
# END x by r at 3: at t=5 x valid & avail -> F; at t=2 -> T.
h = HIS(A("a", "r", "s", "decision", 1), R("x", "r", "a", 3, kind="END"))
check("4.8 ended", O.current(h, TH(), "a", 5), False)
check("4.8 before end", O.current(h, TH(), "a", 2), True)
# C6: a1 at 1, a2 at 2 same subject/ckind.  proxy: Current(a2,5)=T (no later) so a1 F; off: a1 T.
h = HIS(A("a1", "r", "s", "decision", 1), A("a2", "r", "s", "decision", 2))
check("4.8 C6 proxy a1", O.current(h, TH(C6="same_subject_proxy"), "a1", 5), False)
check("4.8 C6 proxy a2", O.current(h, TH(C6="same_subject_proxy"), "a2", 5), True)
check("4.8 C6 off", O.current(h, TH(C6="off"), "a1", 5), True)
# Carry & RD1 & ties: g to h scope [(ADOPT,(s,decision))] period [0,5]; ADOPT a by h at 1;
# REVOKE x of g at 5.  At t=6: Endings {(expiry,5),(revocation,5)} -> tie -> FirstEnd both.
# RD1 all persist -> Survives -> T.  decision|revocation cascade -> F.
SA = [["ADOPT", ["s", "decision"]]]
h = HIS(G("g", "r", "h", SA, 0, until=5), A("a", "h", "s", "decision", 1), R("x", "r", "g", 5))
check("4.8 tie persist", O.current(h, TH(), "a", 6), True)
rd = TH()["RD1"].copy(); rd["decision|revocation"] = "cascade"
check("4.8 tie one cascade", O.current(h, TH(RD1=rd), "a", 6), False)
# only expiry at t=6 without revoke, expiry cascade -> F; rule-kind not affected.
h = HIS(G("g", "r", "h", SA, 0, until=5), A("a", "h", "s", "decision", 1))
rd = TH()["RD1"].copy(); rd["decision|expiry"] = "cascade"
check("4.8 expiry cascade", O.current(h, TH(RD1=rd), "a", 6), False)
check("4.8 expiry cascade before", O.current(h, TH(RD1=rd), "a", 4), True)
# BASIS: two grants g1,g2 to h; revoke g1 at 3 with RD1 cascade; t=5.
# any: g2 survives -> T.  all: g1 does not -> F.
h = HIS(G("g1", "r", "h", SA, 0), G("g2", "r", "h", SA, 0), A("a", "h", "s", "decision", 1),
        R("x", "r", "g1", 3))
rd = TH()["RD1"].copy(); rd["decision|revocation"] = "cascade"
check("4.8 BASIS any", O.current(h, TH(RD1=rd, BASIS="any"), "a", 5), True)
check("4.8 BASIS all", O.current(h, TH(RD1=rd, BASIS="all"), "a", 5), False)

# ===================================================================== S-4.9
# IND-2: f implement by P on w at 1 (holder r, root); v verify PERFORM by r performer P at 2.
f_ = P("f", "r", 1, performer="P")
v_ = P("v", "r", 2, activity="verify", performer="P")
h = HIS(f_, v_)
check("4.9 IND-2", O.elig(h, TH(), "v", "independent_verification", "w"), "INELIGIBLE")
check("4.9 IND-2 other work", O.elig(h, TH(), "v", "independent_verification", "w2"), "ELIGIBLE_ASSERTED")
check("4.9 other purpose", O.elig(h, TH(), "v", "governance_review", "w"), "ELIGIBLE_ASSERTED")
fl = O.elig_full(h, TH(), "v", "governance_review", "w")[1]
check("4.9 IND-3 flag", fl, ["DISCLOSURE_REQUIRED"])
# NOT_VALID: performer by unauthorized z.
h = HIS(P("v", "z", 2, activity="verify"))
check("4.9 NOT_VALID", O.elig(h, TH(), "v", "x", "w"), "NOT_VALID")
# ATTESTED
h = HIS(P("v", "r", 2, activity="verify", attested=True))
check("4.9 attested", O.elig(h, TH(), "v", "x", "w"), "ELIGIBLE_ATTESTED")
# Bars: h is also root for (*,*); g to h scope [(PERFORM,(verify,w))] with bars [(pp,h,*)].
# Basis(v)={ROOT,g}; g barred for purpose pp.
#   any -> INELIGIBLE; all_root_exempt -> ROOT in Basis -> ELIGIBLE; all_root_not_exempt -> INELIGIBLE.
th_h = TH(root=[["r", ["*", "*"]], ["h", ["*", "*"]]])
h = HIS(G("g", "r", "h", [["PERFORM", ["verify", "w"]]], 0, bars=[["pp", "h", "*"]]),
        P("v", "h", 2, activity="verify"))
check("4.9 BAR any", O.elig(h, dict(th_h, BAR="any"), "v", "pp", "w"), "INELIGIBLE")
check("4.9 BAR root exempt", O.elig(h, dict(th_h, BAR="all_root_exempt"), "v", "pp", "w"), "ELIGIBLE_ASSERTED")
check("4.9 BAR root not exempt", O.elig(h, dict(th_h, BAR="all_root_not_exempt"), "v", "pp", "w"), "INELIGIBLE")
check("4.9 bar other purpose", O.elig(h, dict(th_h, BAR="any"), "v", "qq", "w"), "ELIGIBLE_ASSERTED")

# ===================================================================== S-6.1 / S-6.2
def E(pos, act, rho=None):
    return {"pos": pos, "act": act, "rho": rho}

NOW = 100
# Step 1, contradictions: e recorded twice, holder r vs z.  World A valid (root), world B not -> U.
ents = [E(1, P("e", "r", 1)), E(2, P("e", "z", 1))]
check("6.1 step1 contradiction", O.rec(ents, 2, ["valid", "e"], TH(), RP(), NOW), "U")
check("6.1 step1 one variant", O.rec(ents, 1, ["valid", "e"], TH(), RP(), NOW), "T")
# identical duplicates are one variant -> T.
ents = [E(1, P("e", "r", 1)), E(2, P("e", "r", 1))]
check("6.1 step1 duplicate", O.rec(ents, 2, ["valid", "e"], TH(), RP(), NOW), "T")
# Step 2 tau: tau ⊥, D_tau=[1,2]; lam+=0; now=1: world tau=2 fails 2<=1 -> U; now=2 -> T.
e = P("e", "r", None); e["tau"] = None
check("6.1 tau horizon U", O.rec([E(1, e)], 1, ["valid", "e"], TH(), RP(), 1), "U")
check("6.1 tau horizon T", O.rec([E(1, e)], 1, ["valid", "e"], TH(), RP(), 2), "T")
# Step 2 holder ⊥: pool(holder) = {} (only ⊥ recorded) ∪ {?new} -> 1 world, ?new not root -> F.
e = P("e", None, 1, performer="P")
check("6.1 holder ⊥ only new", O.rec([E(1, e)], 1, ["valid", "e"], TH(), RP(), NOW), "F")
# add a recorded act by holder r -> pool {r, ?new} -> worlds T and F -> U.
ents = [E(1, P("o", "r", 1)), E(2, e)]
check("6.1 holder ⊥ pool", O.rec(ents, 2, ["valid", "e"], TH(), RP(), NOW), "U")
# Step 2 target ⊥: REVOKE x by r of ⊥; grants recorded: g.  Worlds target=g (Form T, valid)
# and ?unrecorded (Form F) -> U.  Number of worlds = 2.
ents = [E(1, G("g", "r", "h", PW, 0)), E(2, R("x", "r", None, 1))]
check("6.1 target ⊥", O.rec(ents, 2, ["valid", "x"], TH(), RP(), NOW), "U")
check("6.1 target ⊥ count", O.count_worlds(ents, 2, TH(), RP()), 2)
# Step 2 period ⊥: g by r to h tau 1 period ⊥; e by h tau 3.
# open_ended -> [1,inf) -> e valid -> T.
# undetermined, D_tau [1,2]: (1,1),(1,2),(1,inf),(2,2),(2,inf): closed expiry at 3 for until 1,2 -> F
# in 3 worlds, T in 2 -> U; 5 worlds.
gq = G("g", "r", "h", PW, 1); gq["period"] = None
ents = [E(1, gq), E(2, P("e", "h", 3))]
check("6.1 period open_ended", O.rec(ents, 2, ["valid", "e"], TH(), RP(pi="open_ended"), NOW), "T")
check("6.1 period undetermined", O.rec(ents, 2, ["valid", "e"], TH(), RP(pi="undetermined"), NOW), "U")
check("6.1 period undetermined count", O.count_worlds(ents, 2, TH(), RP(pi="undetermined")), 5)
# Step 2 scope ⊥: N_w = {(PERFORM,(implement,w))} (g's own Need excluded, F-18);
# subsets of {that, (*,*)} = 4 worlds; e valid in 3 of them -> U.  B=3 -> U("bound").
gs = G("g", "r", "h", None, 1); gs["scope"] = None
ents = [E(1, gs), E(2, P("e", "h", 3))]
check("6.1 scope count", O.count_worlds(ents, 2, TH(), RP()), 4)
check("6.1 scope U", O.rec(ents, 2, ["valid", "e"], TH(), RP(), NOW), "U")
check("6.1 bound reason", O.rec_explain(ents, 2, ["valid", "e"], TH(), RP(B=3), NOW)[1], "bound")
# Step 2 rho ⊥ under a_tau with AX-PosRho.
# (a) g pos1 rho ⊥ tau1; e pos2 rho 2 tau 2. rho(g) ∈ {1,2,3}∩[>=1], PosRho -> <= 2 -> {1,2};
#     both <= tau(e)=2 -> T in both worlds -> T.
ents = [E(1, G("g", "r", "h", PW, 1), None), E(2, P("e", "h", 2), 2)]
check("6.1 rho PosRho T", O.rec(ents, 2, ["valid", "e"], TH(C1="a_tau"), RP(), NOW), "T")
check("6.1 rho PosRho count", O.count_worlds(ents, 2, TH(C1="a_tau"), RP()), 2)
# (b) reversed positions: e pos1 rho2, g pos2 rho ⊥: rho(g) >= 2 -> {2,3}; rho=3 > tau(e) -> F -> U.
ents = [E(1, P("e", "h", 2), 2), E(2, G("g", "r", "h", PW, 1), None)]
check("6.1 rho PosRho U", O.rec(ents, 2, ["valid", "e"], TH(C1="a_tau"), RP(), NOW), "U")
# Step 3 order: C10=given; g tau1 ord ⊥; e tau1 ord 1: any position -> g before e (T) or after (F) -> U.
ents = [E(1, G("g", "r", "h", PW, 1, ord=None)), E(2, P("e", "h", 1, ord=1))]
check("6.1 order free", O.rec(ents, 2, ["valid", "e"], TH(C10="given"), RP(), NOW), "U")
ents = [E(1, G("g", "r", "h", PW, 1, ord=0)), E(2, P("e", "h", 1, ord=1))]
check("6.1 order fixed", O.rec(ents, 2, ["valid", "e"], TH(C10="given"), RP(), NOW), "T")
ents = [E(1, G("g", "r", "h", PW, 1, ord=2)), E(2, P("e", "h", 1, ord=1))]
check("6.1 order fixed after", O.rec(ents, 2, ["valid", "e"], TH(C10="given"), RP(), NOW), "F")
# Step 4 upsilon: g by r to p; x REVOKE of g by unauthorized z at 2 (Form T, Auth F); e by p at 3.
# does_not_count: single world, x invalid, e valid -> T.
# counts_as_undetermined: X={x}; Y=∅ (T), Y={x} (x valid -> g revoked -> e F) -> U; 2 worlds.
ents = [E(1, G("g", "r", "p", PW, 0)), E(2, R("x", "z", "g", 2)), E(3, P("e", "p", 3))]
check("6.1 upsilon no", O.rec(ents, 3, ["valid", "e"], TH(), RP(upsilon="does_not_count"), NOW), "T")
check("6.1 upsilon yes", O.rec(ents, 3, ["valid", "e"], TH(), RP(upsilon="counts_as_undetermined"), NOW), "U")
check("6.1 upsilon count", O.count_worlds(ents, 3, TH(), RP(upsilon="counts_as_undetermined")), 2)
# forced END gives UNREC: END y by z of a (adopt by r): forced -> claim ended -> Current U.
ents = [E(1, A("a", "r", "s", "decision", 1)), E(2, R("y", "z", "a", 2, kind="END"))]
check("6.1 upsilon END", O.rec(ents, 2, ["current", "a", 5], TH(), RP(upsilon="counts_as_undetermined"), NOW), "U")
check("6.1 upsilon END no", O.rec(ents, 2, ["current", "a", 5], TH(), RP(), NOW), "T")

# S-6.2 horizons and missing subject.
ents = [E(1, P("e", "r", 1))]
check("6.2 lam+ inf", O.rec(ents, 1, ["valid", "e"], TH(), RP(lam_pos="inf"), NOW), "U")
check("6.2 lam+ 50 (1<=50)", O.rec(ents, 1, ["valid", "e"], TH(), RP(lam_pos=50), NOW), "T")
check("6.2 lam+ 100 (1<=0 no)", O.rec(ents, 1, ["valid", "e"], TH(), RP(lam_pos=100), NOW), "U")
ents = [E(1, P("e", "z", 1))]
check("6.2 F", O.rec(ents, 1, ["valid", "e"], TH(), RP(), NOW), "F")
check("6.2 lam- inf", O.rec(ents, 1, ["valid", "e"], TH(), RP(lam_neg="inf"), NOW), "U")
check("6.2 subject absent", O.rec(ents, 0, ["valid", "e"], TH(), RP(), NOW), "U")
check("6.2 auth query", O.rec(ents, 1, ["auth", "e"], TH(), RP(), NOW), "F")
# Elig note: implementer f with performer ⊥ (pool {V} ∪ {?new}); verifier v performer V.
# World performer(f)=V -> INELIGIBLE; ?new -> eligible -> U.
f2 = P("f", "r", 1, performer="X"); f2["performer"] = None
v2 = P("v", "r", 2, activity="verify", performer="V")
ents = [E(1, f2), E(2, v2)]
check("6.2 elig ⊥ implementer", O.rec(ents, 2, ["elig", "v", "independent_verification", "w"], TH(), RP(), NOW), "U")
# implementer entirely unrecorded: only v recorded -> every world eligible -> T (F-31: note in S-6.2 says U).
check("6.2 elig unrecorded implementer", O.rec([E(2, v2)], 2, ["elig", "v", "independent_verification", "w"], TH(), RP(), NOW), "T")

# ===================================================================== S-7
# e by h needs g; g recorded at pos 2 (late, tau 0).  k=1: world {e}: F.  k=2: T.
ents = [E(1, P("e", "h", 1)), E(2, G("g", "r", "h", PW, 0))]
always = lambda k, eid: True
check("7 eff k1", O.eff(ents, 1, "e", TH(), RP(), NOW, always), False)
check("7 eff k2", O.eff(ents, 2, "e", TH(), RP(), NOW, always), True)
check("7 eff_obs k1", O.eff_obs(ents, 1, "e", always), True)
check("7 usable_from", O.usable_from(ents, "e", TH(), RP(), NOW, always), 2)
check("7 usable_from never", O.usable_from(ents, "e", TH(), RP(), NOW, lambda k, e: False), None)
raises("7 Proc required", lambda: O.eff(ents, 1, "e", TH(), RP(), NOW, None))
# Th-4 non-monotone: g pos1, e pos2 tau2 (T at k=2), late REVOKE of g at tau 1 -> F at k=3.
ents = [E(1, G("g", "r", "h", PW, 0)), E(2, P("e", "h", 2)), E(3, R("x", "r", "g", 1))]
check("Th-4 k2", O.rec(ents, 2, ["valid", "e"], TH(), RP(), NOW), "T")
check("Th-4 k3", O.rec(ents, 3, ["valid", "e"], TH(), RP(), NOW), "F")

# ===================================================================== S-8
# Same entries.  d=10.  Wit+ {(10,2)}, no Wit-: K=[2,3] -> T,F -> U.
check("8 no absence witness", O.rec_at_date(ents, 10, [[10, 2]], [], ["valid", "e"], TH(), RP()), "U")
# Wit- {(10,3)}: hi = 3-1 = 2 -> K=[2,2] -> T.
check("8 with absence witness", O.rec_at_date(ents, 10, [[10, 2]], [[10, 3]], ["valid", "e"], TH(), RP()), "T")
# Wit+ (10,3), Wit- (10,3): lo=3 > hi=2 -> CONFLICT.
check("8 conflict", O.rec_at_date(ents, 10, [[10, 3]], [[10, 3]], ["valid", "e"], TH(), RP()), "CONFLICT")
# Wit+ dated after d is ignored (d' <= d required): lo=0 -> K=[0,3], k=0 gives U -> U.
check("8 future wit+", O.rec_at_date(ents, 10, [[11, 3]], [], ["valid", "e"], TH(), RP()), "U")
# Wit- dated before d is ignored (d' >= d required).
check("8 past wit-", O.rec_at_date(ents, 10, [[10, 2]], [[9, 3]], ["valid", "e"], TH(), RP()), "U")

# ===================================================================== theorem counterexamples
# Th-3(b) for phi = Auth (F-36): H = {x: END by root r of a at tau 1; a: ADOPT by r at tau 5}.
# V: Need(x) defined (a exists, ADOPT) -> ROOT -> Auth(x)=T.
# Records E={x} (Comp(k,1) holds: only x has tau<=1; no ⊥; no contradiction; C1=b).
# Every world: a absent -> Need undefined -> Auth NA -> phi false -> Rec F  ≠ V = T.
hx = HIS(R("x", "r", "a", 1, kind="END"), A("a", "r", "s", "decision", 5))
check("Th-3 cx V", O.auth(hx, TH())["x"], "T")
check("Th-3 cx Rec", O.rec([E(1, R("x", "r", "a", 1, kind="END"))], 1, ["auth", "x"], TH(), RP(), NOW), "F")
# Th-5a for phi = Current (F-38): C1=a_tau; a ADOPT by r tau1 rho1 pos1; END x by r tau2 rho3 pos2.
# RComp(1, s=tau(a)=1) holds.  V: Current(a,5)=F (x valid & avail: tau2<=5, rho3<=5).
# Rec_1: only a -> Current T.
hy = HIS(A("a", "r", "s", "decision", 1), R("x", "r", "a", 2, kind="END"), rho={"a": 1, "x": 3})
check("Th-5a cx V", O.current(hy, TH(C1="a_tau"), "a", 5), False)
check("Th-5a cx Rec", O.rec([E(1, A("a", "r", "s", "decision", 1), 1)], 1, ["current", "a", 5], TH(C1="a_tau"), RP(), NOW), "T")

# F-07 (a_tau retroactive first ending): g to h scope SA period [0,5] rho0; ADOPT a by h tau1 rho1;
# REVOKE x of g tau3 rho7.  RD1 decision|expiry persist, decision|revocation cascade.
# t=6: x not avail (rho7>6) -> Endings {(expiry,5)} -> persist -> Current T.
# t=8: x avail -> Endings {(expiry,5),(revocation,3)} -> FirstEnd {revocation} -> cascade -> F.
hz = HIS(G("g", "r", "h", SA, 0, until=5), A("a", "h", "s", "decision", 1), R("x", "r", "g", 3),
         rho={"g": 0, "a": 1, "x": 7})
rd = TH()["RD1"].copy(); rd["decision|revocation"] = "cascade"
check("F-07 t=6", O.current(hz, TH(C1="a_tau", RD1=rd), "a", 6), True)
check("F-07 t=8", O.current(hz, TH(C1="a_tau", RD1=rd), "a", 8), False)

# ===================================================================== brute force vs stratified
# Random small histories: the brute-force fixpoint (unique) must equal stratified evaluation.
rnd = random.Random(7)
nrand = 0
for trial in range(150):
    acts = []
    n = rnd.randint(2, 7)
    for i in range(n):
        tau = rnd.randint(0, 4)
        kd = rnd.choice(["GRANT", "REVOKE", "CONFIRM", "PERFORM", "ADOPT", "END"])
        hol = rnd.choice(["r", "h", "p"])
        ids_ = ["a%d" % j for j in range(n)]
        if kd == "GRANT":
            acts.append(G("a%d" % i, hol, rnd.choice(["h", "p"]),
                          rnd.choice([PW, GP, [["*", "*"]], SA, [["GRANT", ["*", "*"]]]]), tau,
                          until=rnd.choice(["inf", tau + 2]), proviso=rnd.random() < 0.2))
        elif kd in ("REVOKE", "CONFIRM", "END"):
            acts.append(R("a%d" % i, hol, rnd.choice(ids_), tau, kind=kd))
        elif kd == "ADOPT":
            acts.append(A("a%d" % i, hol, "s", "decision", tau))
        else:
            acts.append(P("a%d" % i, hol, tau))
    for c1 in ("b", "a_tau", "a_rho"):
        rho = {a["id"]: rnd.choice([a["tau"], a["tau"] + 1, "never"]) for a in acts}
        th = O.check_theta(TH(C1=c1, delta={"expiry": "independent", "revocation": rnd.choice(["independent", "dependent"])}))
        H = O.History.from_json({"acts": copy.deepcopy(acts), "rho": rho}, th)
        b1 = O.solve_valid(H, method="brute").V
        b2 = O.solve_valid(H, method="strat").V
        nrand += 1
        if b1 != b2:
            check("brute==strat trial %d %s" % (trial, c1), b2, b1)
check("random cross-check ran", nrand, 450)

print("selftest: %d passed, %d failed" % (PASS, FAIL))
sys.exit(1 if FAIL else 0)
