#!/usr/bin/env python3
"""Independent finite-state verification of R5-SPEC §7 (checks K1-K12.2).

Standard library only.  Run: python3 verify.py  (writes RESULTS.json, prints it).

History encoding (a tuple whose lexicographic order IS the §7.1 order R-1):
    h = (s0, s1, a1, a2, cap, w, ref, con1, con2, r, c)
  s0, s1 : sigma(0), sigma(1); 0 = p, 1 = q
  a1, a2 : slot code; 0 = absent,
           1 + 12*d + 6*v + 3*prep + f  (iss; f: 0, 1, 2 = infinity), codes 1..24
           25 + d                        (adp; obj = the other slot), codes 25..26
  cap    : 0 = {}, 1 = {(x,iss)}, 2 = {(x,adp)}, 3 = {(x,iss),(x,adp)}
  w      : 0 = w1 absent, 1 = present with t = 0, 2 = present with t = 1
  ref    : 4*[(rho1,a1)] + 2*[(rho1,a2)] + [(rho1,w1)]
  con1/2 : 0 = none, 1 = rho1
  r      : r(rho1) in {0, 1}
  c      : 0 = c1, 1 = c2
"""
import json
import sys
import time
from collections import defaultdict

T0 = time.time()
INF = 2
VAL = ["p", "q"]
P = {0}
S0, S1, A1, A2, CAP, W, REF, C1, C2, R, C = range(11)
SLOTNAME = ["a1", "a2"]
REFBIT = [4, 2]


def slot_info(code):
    if code == 0:
        return None
    if code <= 24:
        k = code - 1
        return ("iss", k // 12, (k // 6) % 2, (k // 3) % 2, k % 3)  # kind, d, v, prep, f
    return ("adp", code - 25)


SLOT = [slot_info(i) for i in range(27)]


def iss_code(d, v, prep, f):
    return 1 + 12 * d + 6 * v + 3 * prep + f


def cap_iss(cap):
    return cap in (1, 3)


def cap_adp(cap):
    return cap in (2, 3)


# ---------------------------------------------------------------- enumeration
def enumerate_universe(Vvals):
    iss_codes = [iss_code(d, v, pr, f) for d in (0, 1) for v in Vvals for pr in (0, 1) for f in (0, 1, 2)]
    slot_codes = [0] + sorted(iss_codes) + [25, 26]
    pairs = []
    for x in slot_codes:
        for y in slot_codes:
            ix, iy = SLOT[x], SLOT[y]
            if ix and ix[0] == "adp" and not (iy and iy[0] == "iss"):
                continue
            if iy and iy[0] == "adp" and not (ix and ix[0] == "iss"):
                continue
            pairs.append((x, y))
    out = []
    for s0 in Vvals:
        for s1 in Vvals:
            sig = (s0, s1)
            for (x, y) in pairs:
                # F1 depends only on sigma and slots
                ok = True
                for code in (x, y):
                    inf = SLOT[code]
                    if inf and inf[0] == "iss" and inf[4] != INF and sig[inf[4]] != inf[2]:
                        ok = False
                if not ok:
                    continue
                for cap in range(4):
                    for w in (0, 1, 2):
                        for ref in range(8):
                            if (ref & 4 and x == 0) or (ref & 2 and y == 0) or (ref & 1 and w == 0):
                                continue
                            for k1 in ((0, 1) if x else (0,)):
                                for k2 in ((0, 1) if y else (0,)):
                                    for r in (0, 1):
                                        for c in (0, 1):
                                            h = (s0, s1, x, y, cap, w, ref, k1, k2, r, c)
                                            if F2(h):
                                                out.append(h)
    return out


def in_ranges(h, Vvals=(0, 1)):
    """Coordinate ranges of §7.1 (used for constructed histories)."""
    s0, s1, x, y, cap, w, ref, k1, k2, r, c = h
    if s0 not in Vvals or s1 not in Vvals:
        return False
    for code in (x, y):
        inf = SLOT[code]
        if inf and inf[0] == "iss" and inf[2] not in Vvals:
            return False
    ix, iy = SLOT[x], SLOT[y]
    if ix and ix[0] == "adp" and not (iy and iy[0] == "iss"):
        return False
    if iy and iy[0] == "adp" and not (ix and ix[0] == "iss"):
        return False
    if (ref & 4 and x == 0) or (ref & 2 and y == 0) or (ref & 1 and w == 0):
        return False
    if (k1 and x == 0) or (k2 and y == 0):
        return False
    return True


def F1(h):
    sig = (h[S0], h[S1])
    for code in (h[A1], h[A2]):
        inf = SLOT[code]
        if inf and inf[0] == "iss" and inf[4] != INF and sig[inf[4]] != inf[2]:
            return False
    return True


def F2(h):
    for i in (0, 1):
        if h[C1 + i]:
            if not (h[REF] & REFBIT[i]):
                return False
            if SLOT[h[A1 + i]][1] != h[R]:
                return False
    return True


def in_H(h, Vvals=(0, 1)):
    return in_ranges(h, Vvals) and F1(h) and F2(h)


# frame variants (extra axioms)
def ax5(h):
    return not (h[C] == 1) or (h[S0] == 1 and h[S1] == 1)


def ax7(h):
    if h[A1] and not h[REF] & 4:
        return False
    if h[A2] and not h[REF] & 2:
        return False
    if h[W] and not h[REF] & 1:
        return False
    return True


def ax9(h):
    return not (h[C] == 1) or (h[C1] == 0 and h[C2] == 0)


FRAMES = {"U": lambda h: in_H(h), "U5": lambda h: in_H(h) and ax5(h),
          "U7": lambda h: in_H(h) and ax7(h), "U9": lambda h: in_H(h) and ax9(h)}


# ---------------------------------------------------------------- clauses
def iss_slots(h):
    return [(i, SLOT[h[A1 + i]]) for i in (0, 1) if SLOT[h[A1 + i]] and SLOT[h[A1 + i]][0] == "iss"]


def adopted(h, i, t):
    inf = SLOT[h[A1 + i]]
    cap = h[CAP]
    if inf[3] == 0 and cap_adp(cap) and inf[1] <= t:
        return True
    oth = SLOT[h[A1 + 1 - i]]
    if oth and oth[0] == "adp" and cap_adp(cap) and oth[1] <= t:  # obj(other) = this slot
        return True
    return False


def changes(h):
    return [(1, "tau")] if h[S0] != h[S1] else []


def D_chg(h):
    if h[S0] == h[S1]:
        return True
    return any(inf[4] == 1 and inf[2] == h[S1] and cap_iss(h[CAP]) for _, inf in iss_slots(h))


def D_st(h):
    return all(adopted(h, i, inf[4]) for i, inf in iss_slots(h) if inf[4] != INF)


def D_st_plus(h):
    if not D_st(h):
        return False
    for i, inf in iss_slots(h):
        if any(adopted(h, i, t) for t in (0, 1)) and inf[4] == INF:
            return False
    return True


def D_rec(h):
    return (h[REF] & 6) != 0


def B_chg(h):
    if h[S0] == h[S1]:
        return True
    if any(inf[4] == 1 and inf[2] == h[S1] for _, inf in iss_slots(h)):
        return True
    return h[W] != 0 and (h[W] - 1) < 1


def violates(h):
    return h[W] != 0 and (h[S0], h[S1])[h[W] - 1] not in P


def B_conf(h):
    return not violates(h)


def B_recW(h):
    return (h[REF] & 1) != 0


def B_rec(h):
    return D_rec(h) and B_recW(h)


def is_HD(h):
    return D_chg(h) and D_st(h) and D_rec(h)


def is_HDp(h):
    return D_chg(h) and D_st_plus(h) and D_rec(h)


def is_HB(h):
    return B_chg(h) and B_conf(h) and B_rec(h)


def is_RB(h):
    return B_rec(h)


# ---------------------------------------------------------------- observations
def O(h):
    return (h[R], h[C])


def Fhat(h):
    return frozenset((SLOTNAME[i], inf[2], inf[4]) for i, inf in iss_slots(h) if inf[4] != INF)


def Fhat0(h):
    return frozenset((SLOTNAME[i], inf[2], inf[4]) for i, inf in iss_slots(h) if inf[3] == 0 and inf[4] != INF)


def kind(code):
    return SLOT[code][0] if SLOT[code] else "absent"


def dfun(h):
    return tuple((SLOTNAME[i], SLOT[h[A1 + i]][1]) for i in (0, 1) if h[A1 + i])


OBS = {
    "O": O,
    "O1": lambda h: (O(h), h[S0], h[S1]),
    "O2": lambda h: (O(h), Fhat0(h)),
    "O3": lambda h: (O(h), h[REF]),
    "O3'": lambda h: (O(h), h[C1], h[C2]),
    "O4": lambda h: (O(h), h[W] != 0),
    "O4'": lambda h: (O(h), kind(h[A1]), kind(h[A2])),
    "O8": lambda h: (O(h), frozenset((SLOTNAME[i], inf[1]) for i, inf in iss_slots(h) if inf[3] == 0)),
    "O+": lambda h: (O(h), h[REF], h[C1], h[C2], dfun(h)),
}


def sig_at_w(h):
    return (h[S0], h[S1])[h[W] - 1]


BOT = "⊥"


def Q1(h):
    return VAL[sig_at_w(h)] if h[W] else BOT


def Q0(h):
    return VAL[h[S0]]


def Qe(h):
    return "p" if h[W] else BOT


def Q2(h):
    acts = tuple((SLOTNAME[i], SLOT[h[A1 + i]][0],
                  SLOT[h[A1 + i]][3] if SLOT[h[A1 + i]][0] == "iss" else None,
                  SLOTNAME[1 - i] if SLOT[h[A1 + i]][0] == "adp" else None,
                  SLOT[h[A1 + i]][1]) for i in (0, 1) if h[A1 + i])
    return (h[S0], h[S1], Fhat(h), h[CAP], acts)


def Q1B(h):
    if not h[W]:
        return BOT
    return VAL[sig_at_w(h)] if is_HB(h) else "q"


def Qm(h):
    if not h[W]:
        return BOT
    return VAL[sig_at_w(h)] if is_HD(h) else "q"


def Qstar(h):
    if not h[W]:
        return frozenset()
    s = {("λ1", VAL[sig_at_w(h)])}
    if not is_HD(h):
        s.add(("λ2", VAL[sig_at_w(h)]))
    return frozenset(s)


def Qprime(h):
    X = 0
    for i, inf in iss_slots(h):
        if inf[4] == 1 and cap_iss(h[CAP]) and adopted(h, i, 1):
            X = 1
    return (VAL[h[S0]], VAL[h[S1]], X)


# ---------------------------------------------------------------- display
def show(h):
    if h is None:
        return None

    def slot(code, i):
        inf = SLOT[code]
        if not inf:
            return "absent"
        if inf[0] == "iss":
            return {"kind": "iss", "d": inf[1], "v": VAL[inf[2]], "prep": inf[3],
                    "f": "inf" if inf[4] == INF else inf[4]}
        return {"kind": "adp", "d": inf[1], "obj": SLOTNAME[1 - i]}

    capn = [[], ["(x,iss)"], ["(x,adp)"], ["(x,iss)", "(x,adp)"]][h[CAP]]
    refl = [n for b, n in ((4, "(rho1,a1)"), (2, "(rho1,a2)"), (1, "(rho1,w1)")) if h[REF] & b]
    return {"sigma": [VAL[h[S0]], VAL[h[S1]]], "a1": slot(h[A1], 0), "a2": slot(h[A2], 1),
            "Cap": capn, "w1": "absent" if h[W] == 0 else {"t": h[W] - 1}, "Ref": refl,
            "con": {"a1": "rho1" if h[C1] else "none", "a2": "rho1" if h[C2] else "none"},
            "r": h[R], "c": "c" + str(h[C] + 1)}


def jsonable(x):
    if isinstance(x, (frozenset, set)):
        return sorted((jsonable(e) for e in x), key=lambda e: json.dumps(e, ensure_ascii=False))
    if isinstance(x, (tuple, list)):
        return [jsonable(e) for e in x]
    if isinstance(x, dict):
        return {str(k): jsonable(v) for k, v in x.items()}
    return x


def ostr(o):
    return "(r=%d,c%d)" % (o[0], o[1] + 1)


ALL_O = [(r, c) for r in (0, 1) for c in (0, 1)]


def TF(b):
    return "TRUE" if b else "FALSE"


# ---------------------------------------------------------------- build U
U = enumerate_universe((0, 1))
assert U == sorted(U)
N_U = len(U)
U_SET = set(U)

HD = [is_HD(h) for h in U]
HB = [is_HB(h) for h in U]
RB = [is_RB(h) for h in U]
HDp = [is_HDp(h) for h in U]
DCHG = [D_chg(h) for h in U]
DST = [D_st(h) for h in U]
BCHG = [B_chg(h) for h in U]
BCONF = [B_conf(h) for h in U]
BRECW = [B_recW(h) for h in U]
DREC = [D_rec(h) for h in U]
BREC = [B_rec(h) for h in U]
IDX = range(N_U)

checks = {}


def first(pred):
    for i in IDX:
        if pred(i):
            return U[i]
    return None


def image(obs, mask):
    return {obs(U[i]) for i in IDX if mask(i)}


def strip_f(code):
    inf = SLOT[code]
    if inf and inf[0] == "iss":
        return code - inf[4]
    return code


def key_sf(h):  # all enumerated coordinates except sigma and f values
    return (strip_f(h[A1]), strip_f(h[A2]), h[CAP], h[W], h[REF], h[C1], h[C2], h[R], h[C])


def key_sfc(h):  # ... and Cap
    return (strip_f(h[A1]), strip_f(h[A2]), h[W], h[REF], h[C1], h[C2], h[R], h[C])


# ---------------------------------------------------------------- K1
parts = {}
w = {}
for name, m in (("H-D", lambda i: HD[i]), ("H-B", lambda i: HB[i]), ("H-D∩H-B", lambda i: HD[i] and HB[i]),
                ("H-D∖H-B", lambda i: HD[i] and not HB[i]), ("H-B∖H-D", lambda i: HB[i] and not HD[i])):
    x = first(m)
    parts[name + " nonempty"] = x is not None
    w["least " + name] = show(x)
cex = first(lambda i: (HD[i] or HB[i]) and U[i][A1] == 0 and U[i][A2] == 0)
parts["Lem 3.6: every h in H-D∪H-B has a present act"] = cex is None
w["Lem 3.6 first counterexample"] = show(cex)
checks["K1"] = {"result": TF(all(parts.values())),
                "detail": {"parts": parts, "sizes": {"H-D": sum(HD), "H-B": sum(HB),
                                                     "H-D∩H-B": sum(1 for i in IDX if HD[i] and HB[i]),
                                                     "R_B": sum(RB)}},
                "witness_or_counterexample": w}

# ---------------------------------------------------------------- K2
c1 = first(lambda i: DCHG[i] and not BCHG[i])
c2 = first(lambda i: BREC[i] and not DREC[i])
w1_ = first(lambda i: BCHG[i] and not DCHG[i])
w2_ = first(lambda i: DREC[i] and not BREC[i])
parts = {"D-chg ⇒ B-chg (all h)": c1 is None, "B-rec ⇒ D-rec (all h)": c2 is None,
         "witness B-chg ∧ ¬D-chg exists": w1_ is not None, "witness D-rec ∧ ¬B-rec exists": w2_ is not None}
checks["K2"] = {"result": TF(all(parts.values())), "detail": parts,
                "witness_or_counterexample": {"first cex D-chg⇒B-chg": show(c1), "first cex B-rec⇒D-rec": show(c2),
                                              "least witness B-chg∧¬D-chg": show(w1_),
                                              "least witness D-rec∧¬B-rec": show(w2_)}}

# ---------------------------------------------------------------- K3
A = [HD[i] and HB[i] for i in IDX]
B = [HD[i] and BCONF[i] and BRECW[i] for i in IDX]
Cc = [HB[i] and DCHG[i] and DST[i] for i in IDX]
cAB = first(lambda i: A[i] != B[i])
cAC = first(lambda i: A[i] != Cc[i])
checks["K3"] = {"result": TF(cAB is None and cAC is None),
                "detail": {"H-D∩H-B = H-D∩B-conf∩B-recW": cAB is None, "H-D∩H-B = H-B∩D-chg∩D-st": cAC is None,
                           "|H-D∩H-B|": sum(A)},
                "witness_or_counterexample": {"first h in symmetric difference (1st eq)": show(cAB),
                                              "first h in symmetric difference (2nd eq)": show(cAC)}}

# ---------------------------------------------------------------- K4
keys_star = {key_sf(U[i]) for i in IDX if HD[i] and HB[i]}
cex = first(lambda i: RB[i] and key_sf(U[i]) not in keys_star)
checks["K4"] = {"result": TF(cex is None),
                "detail": "for every h in R_B (|R_B|=%d) searched h* in H-D∩H-B equal on all enumerated "
                          "coordinates except sigma and f-values" % sum(RB),
                "witness_or_counterexample": show(cex)}

# ---------------------------------------------------------------- K4b
keys_star_b = {key_sfc(U[i]) for i in IDX if HDp[i] and HB[i]}
cex = first(lambda i: RB[i] and key_sfc(U[i]) not in keys_star_b)
checks["K4b"] = {"result": TF(cex is None),
                 "detail": "H-D+ = D-chg ∧ D-st+ ∧ D-rec; for every h in R_B searched h* in H-D+∩H-B with "
                           "h* ≈_{sigma,f,Cap} h; |H-D+|=%d" % sum(HDp),
                 "witness_or_counterexample": show(cex)}

# ---------------------------------------------------------------- K5
oHD, oHB, oHDHB = image(O, lambda i: HD[i]), image(O, lambda i: HB[i]), image(O, lambda i: HD[i] and HB[i])
ok = oHD == oHB == oHDHB == set(ALL_O)
checks["K5"] = {"result": TF(ok),
                "detail": {"O(H-D)": [ostr(o) for o in sorted(oHD)], "O(H-B)": [ostr(o) for o in sorted(oHB)],
                           "O(H-D∩H-B)": [ostr(o) for o in sorted(oHDHB)]},
                "witness_or_counterexample": None}

# ---------------------------------------------------------------- K6
keys_v = {key_sf(U[i]) for i in IDX if HD[i] and not HB[i] and U[i][S0] == U[i][S1] and violates(U[i])}
cex_v = first(lambda i: RB[i] and BRECW[i] and key_sf(U[i]) not in keys_v)
cex_c = first(lambda i: RB[i] and BRECW[i] and key_sf(U[i]) not in keys_star)
cex = min([x for x in (cex_v, cex_c) if x is not None], default=None)
checks["K6"] = {"result": TF(cex is None),
                "detail": {"h_v exists for all h": cex_v is None, "h_c exists for all h": cex_c is None,
                           "n_h_checked": sum(1 for i in IDX if RB[i] and BRECW[i])},
                "witness_or_counterexample": {"first counterexample": show(cex),
                                              "first lacking h_v": show(cex_v), "first lacking h_c": show(cex_c)}}


# ---------------------------------------------------------------- (iii) machinery
def iii_witnesses(h, frame, obs, a2_ref=False, require_added_rule=True):
    """All h' meeting Prop 4.1(iii) as stated in K7, for h with a2 absent.
    frame: membership predicate; obs: observation compared; a2_ref: whether an added a2 is
    referenced (False = literal (iii): unreferenced, con none)."""
    tw = h[W] - 1
    a1inf = SLOT[h[A1]]
    a1_opts = [h[A1]] if not (a1inf and a1inf[0] == "iss") else [strip_f(h[A1]) + f for f in (0, 1, 2)]
    a2_opts = [0] + [iss_code(d, v, 1, f) for d in (0, 1) for v in (0, 1) for f in (0, 1, 2)]
    res = []
    for sv in (0, 1):
        sig = [h[S0], h[S1]]
        sig[tw] = sv
        for a1c in a1_opts:
            for a2c in a2_opts:
                ref = h[REF] | (2 if (a2_ref and a2c) else 0)
                hp = (sig[0], sig[1], a1c, a2c, h[CAP], h[W], ref, h[C1], 0, h[R], h[C])
                if not frame(hp) or not is_HB(hp) or obs(hp) != obs(h) or violates(hp):
                    continue
                if require_added_rule and hp[S0] != hp[S1]:
                    by_h = any(SLOT[hp[A1 + i]] and SLOT[hp[A1 + i]][0] == "iss" and SLOT[hp[A1 + i]][4] == 1
                               and SLOT[hp[A1 + i]][2] == hp[S1] for i in (0, 1) if h[A1 + i])
                    if not by_h and not (a2c and SLOT[a2c][4] == 1):
                        continue
                res.append(hp)
    return res


# ---------------------------------------------------------------- K7
scope7 = [i for i in IDX if HD[i] and RB[i] and U[i][A2] == 0 and violates(U[i])]
cex = None
nw = 0
for i in scope7:
    ws = iii_witnesses(U[i], FRAMES["U"], O)
    if not ws:
        cex = U[i]
        break
    nw += 1
checks["K7"] = {"result": TF(cex is None),
                "detail": "h in H-D∩R_B with a2 absent and w1 violating: %d; h with a valid h': %d"
                          % (len(scope7), nw),
                "witness_or_counterexample": show(cex)}

# ---------------------------------------------------------------- K8.1
o1_HB = image(OBS["O1"], lambda i: HB[i])
o1_HDRB = image(OBS["O1"], lambda i: HD[i] and RB[i])
diff = o1_HDRB - o1_HB
per_o = {ostr(o): [[VAL[s0], VAL[s1]] for (oo, s0, s1) in sorted(diff) if oo == o] for o in ALL_O}
checks["K8.1"] = {"result": TF(o1_HB < o1_HDRB),
                  "detail": {"O1(H-B) ⊊ O1(H-D∩R_B)": o1_HB < o1_HDRB,
                             "difference sigma values per o": per_o,
                             "O1(H-B) sigma values per o": {ostr(o): [[VAL[a], VAL[b]] for (oo, a, b) in sorted(o1_HB)
                                                                      if oo == o] for o in ALL_O}},
                  "witness_or_counterexample": None}

# ---------------------------------------------------------------- K8.2
o2_HDRB = image(OBS["O2"], lambda i: HD[i] and RB[i])
o2_HB = image(OBS["O2"], lambda i: HB[i])
F0 = frozenset({("a1", 1, 0), ("a2", 1, 1)})
per = {}
for o in ALL_O:
    per[ostr(o)] = {"in O2(H-D∩R_B)": (o, F0) in o2_HDRB, "not in O2(H-B)": (o, F0) not in o2_HB}
ok = all(all(v.values()) for v in per.values())
wit = first(lambda i: HD[i] and RB[i] and Fhat0(U[i]) == F0)
checks["K8.2"] = {"result": TF(ok), "detail": per,
                  "witness_or_counterexample": {"least h in H-D∩R_B with that F̂0": show(wit)}}

# ---------------------------------------------------------------- K8.3
o3_HB = image(OBS["O3"], lambda i: HB[i])
wit = first(lambda i: HD[i] and not (U[i][REF] & 1) and OBS["O3"](U[i]) not in o3_HB)
s1_ = image(OBS["O3"], lambda i: HD[i] and RB[i])
s3_ = image(OBS["O3"], lambda i: HD[i] and HB[i])
s4_ = image(OBS["O3"], lambda i: RB[i])
eq = s1_ == o3_HB == s3_ == s4_
checks["K8.3"] = {"result": TF(wit is not None and eq),
                  "detail": {"value without (rho1,w1) in O3(H-D)∖O3(H-B) exists": wit is not None,
                             "(i) for O3: O3(H-D∩R_B)=O3(H-B)=O3(H-D∩H-B)=O3(R_B)": eq,
                             "(i) for O3 per-h: implied by K4 (≈_{σ,f} preserves Ref)": checks["K4"]["result"]},
                  "witness_or_counterexample": {"least h in H-D with such a value": show(wit),
                                                "its O3 value": None if wit is None else
                                                {"o": ostr(O(wit)), "Ref": show(wit)["Ref"]}}}

# ---------------------------------------------------------------- K8.4
o4_HD = image(OBS["O4"], lambda i: HD[i])
o4_HB = image(OBS["O4"], lambda i: HB[i])
per = {ostr(o): ((o, False) in o4_HD and (o, False) not in o4_HB) for o in ALL_O}


def I7(r, c):
    return (1, 1, iss_code(0, 1, 1, INF), 0, 0, 2, 5, 0, 0, r, c)


i7info = {}
part_b = True
for (r, c) in ALL_O:
    h7 = I7(r, c)
    assert in_H(h7) and is_HD(h7) and is_RB(h7) and violates(h7)
    w_O4p = iii_witnesses(h7, FRAMES["U"], OBS["O4'"])
    w_O = iii_witnesses(h7, FRAMES["U"], O)
    all_add = all(x[A2] != 0 for x in w_O)
    # no h' at all (even without the change-point clause) without an added act and same O4'
    w_noadd = [x for x in iii_witnesses(h7, FRAMES["U"], OBS["O4'"], require_added_rule=False)]
    i7info[ostr((r, c))] = {"(iii) witnesses under O4'": len(w_O4p), "(iii) witnesses under O": len(w_O),
                            "every O-witness adds a2 (changes O4')": all_add,
                            "h' in H-B with same O4', w1 conforming, ignoring change-point clause": len(w_noadd),
                            "least O-witness": show(min(w_O)) if w_O else None}
    if not (len(w_O4p) == 0 and len(w_O) > 0 and all_add):
        part_b = False
ok = all(per.values()) and part_b
checks["K8.4"] = {"result": TF(ok),
                  "detail": {"(o, w1 absent) in O4(H-D)∖O4(H-B) per o": per,
                             "(iii) fails at I7 under O4' and only because of the added act": part_b,
                             "I7 analysis per (r,c)": i7info},
                  "witness_or_counterexample": None}

# ---------------------------------------------------------------- K8.5
in5 = [ax5(h) for h in U]
o_HDRB5 = image(O, lambda i: in5[i] and HD[i] and RB[i])
o_HB5 = image(O, lambda i: in5[i] and HB[i])
per = {ostr((r, 1)): ((r, 1) in o_HDRB5 and (r, 1) not in o_HB5) for r in (0, 1)}
checks["K8.5"] = {"result": TF(all(per.values())), "detail": {"per r": per, "|U5|": sum(in5)},
                  "witness_or_counterexample": {ostr((r, 1)): show(first(lambda i, r=r: in5[i] and HD[i] and RB[i]
                                                                           and O(U[i]) == (r, 1))) for r in (0, 1)}}

# ---------------------------------------------------------------- K8.6
U6 = enumerate_universe((0,))
RB6 = [h for h in U6 if is_RB(h) and B_recW(h)]
hv6 = [h for h in U6 if is_HD(h) and not is_HB(h) and h[S0] == h[S1] and violates(h)]
checks["K8.6"] = {"result": TF(len(hv6) == 0 and len(RB6) > 0),
                  "detail": {"|U6|": len(U6), "|R_B6 with w1 referenced|": len(RB6),
                             "number of candidate h_v in U6 (H-D6∖H-B6, sigma constant, w1 violating)": len(hv6),
                             "(ii) fails (no h_v for any h in R_B6)": len(hv6) == 0 and len(RB6) > 0},
                  "witness_or_counterexample": {"first h in R_B6 for which no h_v exists": show(RB6[0]) if RB6 else None}}

# ---------------------------------------------------------------- K8.7
info = {}
ok = True
for (r, c) in ALL_O:
    h7 = I7(r, c)
    assert FRAMES["U7"](h7)
    w7 = iii_witnesses(h7, FRAMES["U7"], O)
    wU = iii_witnesses(h7, FRAMES["U"], O)
    w7ref = iii_witnesses(h7, FRAMES["U7"], O, a2_ref=True)
    hp = (1, 0, h7[A1], iss_code(1, 0, 1, 1), 0, 2, 7, 0, 0, r, c)
    good = FRAMES["U7"](hp) and is_HB(hp) and O(hp) == O(h7) and not violates(hp)
    reason = all(x[A2] != 0 and not FRAMES["U7"](x) for x in wU)
    info[ostr((r, c))] = {"(iii) fails at I7 in U7": len(w7) == 0, "(iii) holds at I7 in U": len(wU) > 0,
                          "every U-witness adds an unreferenced a2 (not in U7)": reason,
                          "witnesses in U7 if added a2 is referenced": len(w7ref),
                          "CM-A7(b) h' in U7∩H-B, same O, w1 conforms": good, "CM-A7(b) h'": show(hp)}
    ok = ok and len(w7) == 0 and len(wU) > 0 and reason and good
checks["K8.7"] = {"result": TF(ok), "detail": info, "witness_or_counterexample": None}


# ---------------------------------------------------------------- K9
def OQ(Q):
    return lambda h: (O(h), Q(h))


# K9.1
f = OQ(Q1)
sHD, sHB = image(f, lambda i: HD[i]), image(f, lambda i: HB[i])
per = {ostr(o): ((o, "q") in sHD and (o, "q") not in sHB) for o in ALL_O}
checks["K9.1"] = {"result": TF(all(per.values()) and sHB <= sHD),
                  "detail": {"(o,q) in O_Q1(H-D)∖O_Q1(H-B)": per, "O_Q1(H-B) ⊆ O_Q1(H-D)": sHB <= sHD},
                  "witness_or_counterexample": None}
# K9.2
f = OQ(Q0)
a_, b_ = image(f, lambda i: HD[i] and RB[i]), image(f, lambda i: HB[i])
allv = {(o, v) for o in ALL_O for v in VAL}
checks["K9.2"] = {"result": TF(a_ == b_ == allv),
                  "detail": {"O_Q0(H-D∩R_B)=O_Q0(H-B)": a_ == b_, "= all (o,v)": a_ == allv and b_ == allv,
                             "missing from O_Q0(H-D∩R_B)": sorted(allv - a_), "missing from O_Q0(H-B)": sorted(allv - b_)},
                  "witness_or_counterexample": None}
# K9.3
f = OQ(Qe)
a_, b_ = image(f, lambda i: HD[i] and RB[i]), image(f, lambda i: HB[i])
qe_vals = {Qe(h) for h in U}
never_bad = not any(v in VAL and VAL.index(v) not in P for v in qe_vals)
checks["K9.3"] = {"result": TF(a_ == b_ and never_bad),
                  "detail": {"O_Qe(H-D∩R_B)=O_Qe(H-B)": a_ == b_, "values of Qe over U": sorted(qe_vals),
                             "Qe never takes a value in V∖P (over U)": never_bad,
                             "Qe values on H-D∩R_B ∪ H-B": sorted({v for (_, v) in a_ | b_})},
                  "witness_or_counterexample": None}
# K9.4
f = OQ(Q2)
q2HB = image(f, lambda i: HB[i])
q2HD = image(f, lambda i: HD[i])
q2HDRB = image(f, lambda i: HD[i] and RB[i])
d1 = q2HB - q2HD
d2 = q2HDRB - q2HB
w1_ = first(lambda i: HB[i] and f(U[i]) in d1)
w2_ = first(lambda i: HD[i] and RB[i] and f(U[i]) in d2)
named1 = any(v[1][0] == 0 and v[1][1] == 1 and v[1][2] == frozenset() for v in d1)
named2 = any(v[1][0] == 1 and v[1][1] == 1 for v in d2)
checks["K9.4"] = {"result": TF(bool(d1) and bool(d2) and named1 and named2),
                  "detail": {"|O_Q2(H-B)∖O_Q2(H-D)|": len(d1), "|O_Q2(H-D∩R_B)∖O_Q2(H-B)|": len(d2),
                             "named witness sigma=(p,q), F̂=∅ in first difference": named1,
                             "named witness sigma=(q,q) in second difference": named2},
                  "witness_or_counterexample": {"least h in H-B with value in first difference": show(w1_),
                                                "least h in H-D∩R_B with value in second difference": show(w2_)}}
# K9.5
f = OQ(Q1B)
sHB, sHD = image(f, lambda i: HB[i]), image(f, lambda i: HD[i])
allp = all(v == "p" for (_, v) in sHB)
per = {ostr(o): ((o, "q") in sHD and (o, "q") not in sHB) for o in ALL_O}
checks["K9.5"] = {"result": TF(allp and all(per.values())),
                  "detail": {"every O_Q1B(H-B) value has Q1B = p": allp, "(o,q) in O_Q1B(H-D)∖O_Q1B(H-B)": per},
                  "witness_or_counterexample": None}
# K9.6
f = OQ(Qm)
ver = first(lambda i: HD[i] and U[i][W] and Qm(U[i]) != VAL[sig_at_w(U[i])])
some_q = first(lambda i: HD[i] and Qm(U[i]) == "q")
sHD, sHB = image(f, lambda i: HD[i]), image(f, lambda i: HB[i])
per = {ostr(o): ((o, "q") in sHD and (o, "q") in sHB) for o in ALL_O}
checks["K9.6"] = {"result": TF(ver is None and some_q is not None and all(per.values())),
                  "detail": {"Qm = sigma(t(w1)) on H-D with w1": ver is None, "some h in H-D has Qm=q": some_q is not None,
                             "(o,q) in O_Qm(H-D)∩O_Qm(H-B)": per},
                  "witness_or_counterexample": {"first cex veridicality": show(ver), "least h in H-D with Qm=q": show(some_q)}}
# K9.7
f = OQ(Qstar)
sHD, sHB = image(f, lambda i: HD[i]), image(f, lambda i: HB[i])
v1 = frozenset({("λ1", "q")})
v2 = frozenset({("λ1", "p"), ("λ2", "p")})
per = {ostr(o): {"(o,{(λ1,q)}) in H-D∖H-B": (o, v1) in sHD and (o, v1) not in sHB,
                 "(o,{(λ1,p),(λ2,p)}) in H-B∖H-D": (o, v2) in sHB and (o, v2) not in sHD} for o in ALL_O}
checks["K9.7"] = {"result": TF(all(all(x.values()) for x in per.values())), "detail": per,
                  "witness_or_counterexample": None}
# K9.8
f = OQ(Qprime)
sHD, sHB = image(f, lambda i: HD[i]), image(f, lambda i: HB[i])
per = {ostr(o): ((o, ("p", "q", 0)) in sHB and (o, ("p", "q", 0)) not in sHD) for o in ALL_O}
bad = sorted(v for v in sHD if v[1][0] != v[1][1] and v[1][2] == 0)
cexh = first(lambda i: HD[i] and U[i][S0] != U[i][S1] and Qprime(U[i])[2] == 0)
checks["K9.8"] = {"result": TF(all(per.values()) and not bad),
                  "detail": {"(o,(p,q,0)) in O_Q'(H-B)∖O_Q'(H-D)": per,
                             "no H-D value with sigma(0)≠sigma(1), X=0": not bad},
                  "witness_or_counterexample": show(cexh)}


# ---------------------------------------------------------------- K10 ambiguity machinery
def amb_table(hs, obs):
    """(obs value, slot) -> bitmask of con values (1: none, 2: rho1) among h in hs with (rho1,s) in Ref."""
    t = defaultdict(int)
    for h in hs:
        o = obs(h)
        for s in (0, 1):
            if h[A1 + s] and h[REF] & REFBIT[s]:
                t[(o, s)] |= (2 if h[C1 + s] else 1)
    return t


def amb_first_fail(scope, table, obs):
    """first (h, s) in scope (sorted) with present referenced s that is NOT ambiguous."""
    for h in scope:
        o = obs(h)
        for s in (0, 1):
            if h[A1 + s] and h[REF] & REFBIT[s] and table[(o, s)] != 3:
                return h, s
    return None


def amb_report(name, cls, scope, obs=O):
    tab = amb_table(cls, obs)
    r_ = amb_first_fail(scope, tab, obs)
    return name, r_


def run_K10_1(frame_mask, restrict=lambda h: True):
    out = {}
    Ucl = [U[i] for i in IDX if frame_mask(i)]
    HBcl = [U[i] for i in IDX if frame_mask(i) and HB[i]]
    HDcl = [U[i] for i in IDX if frame_mask(i) and HD[i]]
    HDRB = [U[i] for i in IDX if frame_mask(i) and HD[i] and RB[i]]
    HDHB = [U[i] for i in IDX if frame_mask(i) and HD[i] and HB[i]]
    for name, cls, scope in (("C=U, every h", Ucl, Ucl), ("C=H-B, every h", HBcl, HBcl),
                             ("C=H-D, every h in H-D∩R_B", HDcl, HDRB),
                             ("C=H-D∩H-B, every h", HDHB, HDHB)):
        tab = amb_table(cls, O)
        r_ = amb_first_fail([h for h in scope if restrict(h)], tab, O)
        out[name] = r_
    return out


res = run_K10_1(lambda i: True)
checks["K10.1"] = {"result": TF(all(v is None for v in res.values())),
                   "detail": {k: (v is None) for k, v in res.items()},
                   "witness_or_counterexample": {k: (None if v is None else {"h": show(v[0]), "slot": SLOTNAME[v[1]]})
                                                 for k, v in res.items()}}

# K10.2
tabp = amb_table(U, OBS["O+"])
amb_pairs = [k for k, v in tabp.items() if v == 3]
keysp = {OBS["O+"](U[i]) for i in IDX if HD[i] and HB[i]}
cex = first(lambda i: RB[i] and OBS["O+"](U[i]) not in keysp)
checks["K10.2"] = {"result": TF(not amb_pairs and cex is None),
                   "detail": {"no pair ambiguous in U under O+ (hence in no subclass)": not amb_pairs,
                              "K4 for O+: every h in R_B has h* in H-D∩H-B with O+(h*)=O+(h)": cex is None},
                   "witness_or_counterexample": {"first K4(O+) counterexample": show(cex),
                                                 "ambiguous O+ pairs (count)": len(amb_pairs)}}

# K10.3
ca = first(lambda i: any(U[i][C1 + s] and SLOT[U[i][A1 + s]][1] != U[i][R] for s in (0, 1)))
dsets = defaultdict(dict)
for h in U:
    for s in (0, 1):
        if h[A1 + s] and h[REF] & REFBIT[s]:
            dd = SLOT[h[A1 + s]][1]
            if dd not in dsets[(O(h), s)]:
                dsets[(O(h), s)][dd] = h
per = {}
wit = {}
for o in ALL_O:
    found = None
    for s in (0, 1):
        if len(dsets[(o, s)]) == 2:
            found = (s, dsets[(o, s)][0], dsets[(o, s)][1])
            break
    per[ostr(o)] = found is not None
    wit[ostr(o)] = None if found is None else {"slot": SLOTNAME[found[0]], "h (d=0)": show(found[1]),
                                               "h' (d=1)": show(found[2])}
checks["K10.3"] = {"result": TF(ca is None and all(per.values())),
                   "detail": {"(a) every constituted slot has d = r(rho1)": ca is None, "(b) per o": per},
                   "witness_or_counterexample": {"(a) first counterexample": show(ca), "(b) witnesses": wit}}


# K10.4
def bound_viol(h):
    for i, inf in iss_slots(h):
        oth = SLOT[h[A1 + 1 - i]]
        if inf[4] != INF and inf[3] == 0 and h[C1 + i] and not (oth and oth[0] == "adp") and inf[4] < h[R]:
            return True
    return False


cex = first(lambda i: HD[i] and bound_viol(U[i]))
wit = first(lambda i: HB[i] and bound_viol(U[i]))
checks["K10.4"] = {"result": TF(cex is None and wit is not None),
                   "detail": {"bound holds on all of H-D": cex is None, "some h in H-B violates it": wit is not None},
                   "witness_or_counterexample": {"first H-D counterexample": show(cex), "least H-B violator": show(wit)}}

# K10.5
tab8 = amb_table(U, OBS["O8"])
r8 = amb_first_fail(U, tab8, OBS["O8"])
ex = None
for h in U:
    inf = SLOT[h[A1]]
    if inf and inf[0] == "iss" and inf[3] == 0 and inf[1] == 0 and h[R] == 1 and h[REF] & 4 \
            and tab8[(OBS["O8"](h), 0)] != 3:
        ex = h
        break
checks["K10.5"] = {"result": TF(r8 is not None),
                   "detail": {"some referenced pair not ambiguous in U under O8": r8 is not None,
                              "the cited example (a1 iss, prep 0, d=0, r=1, referenced) is non-ambiguous": ex is not None},
                   "witness_or_counterexample": {"least (h, slot)": None if r8 is None else
                                                 {"h": show(r8[0]), "slot": SLOTNAME[r8[1]]},
                                                 "least h of the cited example form": show(ex)}}

# K10.6
tab3 = amb_table(U, OBS["O3'"])
amb3 = [k for k, v in tab3.items() if v == 3]
checks["K10.6"] = {"result": TF(not amb3), "detail": {"ambiguous (O3' value, slot) pairs in U": len(amb3)},
                   "witness_or_counterexample": None}

# K10.7
in9 = [ax9(h) for h in U]
U9 = [U[i] for i in IDX if in9[i]]
tab9 = amb_table(U9, O)
cex = None
for h in U9:
    if h[C] == 1:
        for s in (0, 1):
            if h[A1 + s] and h[REF] & REFBIT[s] and tab9[(O(h), s)] == 3:
                cex = (h, s)
                break
    if cex:
        break
res9 = run_K10_1(lambda i: in9[i], restrict=lambda h: h[C] == 0)
ok = cex is None and all(v is None for v in res9.values())
checks["K10.7"] = {"result": TF(ok),
                   "detail": {"c2: no referenced pair ambiguous in U9": cex is None,
                              "c1: K10.1 holds in U9": {k: v is None for k, v in res9.items()}},
                   "witness_or_counterexample": {"c2 first ambiguous (h, slot)": None if cex is None else
                                                 {"h": show(cex[0]), "slot": SLOTNAME[cex[1]]},
                                                 "c1 K10.1 failures": {k: (None if v is None else
                                                                           {"h": show(v[0]), "slot": SLOTNAME[v[1]]})
                                                                       for k, v in res9.items()}}}

# ---------------------------------------------------------------- K11.1
cex_rm = cex_add = None
for h in U:
    if h[W]:
        hm = (h[S0], h[S1], h[A1], h[A2], h[CAP], 0, h[REF] & 6, h[C1], h[C2], h[R], h[C])
        if hm not in U_SET and cex_rm is None:
            cex_rm = h
    else:
        for wv in (1, 2):
            ha = h[:W] + (wv,) + h[W + 1:]
            if ha not in U_SET and cex_add is None:
                cex_add = h
checks["K11.1"] = {"result": TF(cex_rm is None and cex_add is None),
                   "detail": {"closed under removing w1": cex_rm is None, "closed under adding unreferenced w1": cex_add is None},
                   "witness_or_counterexample": {"first removal cex": show(cex_rm), "first addition cex": show(cex_add)}}


# ---------------------------------------------------------------- K11.2
def forced_ref(h, cmp):
    for i, inf in iss_slots(h):
        if h[REF] & REFBIT[i] and inf[4] != INF and cmp(inf[4], h[R]):
            return True
    return False


wg = first(lambda i: forced_ref(U[i], lambda a, b: a > b))
wl = first(lambda i: forced_ref(U[i], lambda a, b: a < b))
checks["K11.2"] = {"result": TF(wg is not None and wl is not None),
                   "detail": {"referenced forced slot with f > r exists": wg is not None,
                              "referenced forced slot with f < r exists": wl is not None},
                   "witness_or_counterexample": {"least h with f > r": show(wg), "least h with f < r": show(wl)}}


# ---------------------------------------------------------------- K12.1
def strip_d(code):
    inf = SLOT[code]
    if not inf:
        return 0
    if inf[0] == "iss":
        return code - 12 * inf[1]
    return 25


GROUP_KEYS = {
    "sigma-only": lambda h: h[2:],
    "f-only": lambda h: (h[S0], h[S1], strip_f(h[A1]), strip_f(h[A2])) + h[4:],
    "Ref-only": lambda h: h[:REF] + h[REF + 1:],
    "con-only": lambda h: h[:C1] + h[C2 + 1:],
    "d-only": lambda h: (h[S0], h[S1], strip_d(h[A1]), strip_d(h[A2])) + h[4:],
}


def depends(hs, obs, keyf):
    """least pair (h, h') in hs, h' ≈_X h, obs differs; None if obs does not depend on X over hs."""
    groups = {}
    best = None
    for h in hs:  # hs sorted: first element of each group is its least member
        k = keyf(h)
        if k not in groups:
            groups[k] = (h, obs(h))
        elif obs(h) != groups[k][1]:
            cand = (groups[k][0], h)
            if best is None or cand < best:
                best = cand
    return best


def fresh_additions(h, kinds=("+act", "+work", "+ref")):
    out = []
    if "+act" in kinds:
        for s in (0, 1):
            if h[A1 + s] == 0:
                for d in (0, 1):
                    for v in (0, 1):
                        for f in (0, 1, 2):
                            hl = list(h)
                            hl[A1 + s] = iss_code(d, v, 1, f)
                            out.append(("+act", tuple(hl)))
    if "+work" in kinds and h[W] == 0:
        for wv in (1, 2):
            out.append(("+work", h[:W] + (wv,) + h[W + 1:]))
    if "+ref" in kinds:
        for bit, present in ((4, h[A1]), (2, h[A2]), (1, h[W])):
            if present and not h[REF] & bit:
                out.append(("+ref", h[:REF] + (h[REF] | bit,) + h[REF + 1:]))
    return out


addU = defaultdict(list)  # precompute fresh additions in U (satisfying F1, hence in U)
for h in U:
    for kd, hp in fresh_additions(h, ("+act", "+work")):
        if hp in U_SET:
            addU[kd].append((h, hp))

k121 = {}
k121w = {}
for oname in ["O", "O1", "O2", "O3", "O3'", "O4", "O4'", "O8"]:
    ob = OBS[oname]
    dep = []
    wit = {}
    for gname, kf in GROUP_KEYS.items():
        r_ = depends(U, ob, kf)
        if r_:
            dep.append(gname)
            wit[gname] = [show(r_[0]), show(r_[1])]
    for kd in ("+act", "+work"):
        for (h, hp) in addU[kd]:
            if ob(h) != ob(hp):
                dep.append(kd + " additions")
                wit[kd] = [show(h), show(hp)]
                break
    k121[oname] = dep
    k121w[oname] = wit
checks["K12.1"] = {"result": {k: v for k, v in k121.items()},
                   "detail": "change groups (over U) on which each observation depends; "
                             "witness pair = least h in order with an h' in its change group differing in the observation",
                   "witness_or_counterexample": k121w}

# ---------------------------------------------------------------- K12.2
A9_KEYS = {"A9 (con-only)": GROUP_KEYS["con-only"], "A9 (d-only)": GROUP_KEYS["d-only"]}
A5_KEY = lambda h: (strip_f(h[A1]), strip_f(h[A2])) + h[4:]


def closure_fail_group(member, keyf):
    """Frame X ⊆ U defined by member(); replacements that satisfy F1/F2 land in U and
    preserve keyf.  Fails iff some keyf-group of U contains a member of X and a non-member."""
    groups = {}
    for h in U:
        k = keyf(h)
        g = groups.setdefault(k, [None, None])
        if member(h):
            if g[0] is None:
                g[0] = h
        elif g[1] is None:
            g[1] = h
    best = None
    for g in groups.values():
        if g[0] is not None and g[1] is not None:
            if best is None or g[0] < best[0]:
                best = (g[0], g[1])
    return best


k122 = {}
k122w = {}
for fname, member in (("U", lambda h: True), ("U5", ax5), ("U7", ax7), ("U9", ax9)):
    fails = []
    wit = {}
    r_ = closure_fail_group(member, A5_KEY)
    if r_:
        fails.append("A5")
        wit["A5"] = [show(r_[0]), show(r_[1])]
    for kd in ("+act", "+work", "+ref"):
        bad = None
        for h in U:
            if not member(h):
                continue
            for k2, hp in fresh_additions(h, (kd,)):
                if F1(hp) and in_H(hp) and not member(hp):
                    bad = (h, hp)
                    break
            if bad:
                break
        if bad:
            fails.append("A7 " + kd)
            wit["A7 " + kd] = [show(bad[0]), show(bad[1])]
    for an, kf in A9_KEYS.items():
        r_ = closure_fail_group(member, kf)
        if r_:
            fails.append(an)
            wit[an] = [show(r_[0]), show(r_[1])]
    k122[fname] = fails
    k122w[fname] = wit
checks["K12.2"] = {"result": k122,
                   "detail": "closure properties failing in each frame; witness = (least h in frame, a related "
                             "history satisfying F1/F2 outside the frame)",
                   "witness_or_counterexample": k122w}

# ---------------------------------------------------------------- output
order = ["K1", "K2", "K3", "K4", "K4b", "K5", "K6", "K7", "K8.1", "K8.2", "K8.3", "K8.4", "K8.5", "K8.6", "K8.7",
         "K9.1", "K9.2", "K9.3", "K9.4", "K9.5", "K9.6", "K9.7", "K9.8", "K10.1", "K10.2", "K10.3", "K10.4",
         "K10.5", "K10.6", "K10.7", "K11.1", "K11.2", "K12.1", "K12.2"]
out = {"n_frame_histories": N_U, "checks": {k: jsonable(checks[k]) for k in order}}
txt = json.dumps(out, indent=1, ensure_ascii=False)
with open("RESULTS.json", "w", encoding="utf-8") as fh:
    fh.write(txt + "\n")
print(txt)
for k in order:
    print(k, checks[k]["result"] if not isinstance(checks[k]["result"], dict) else "REPORT", file=sys.stderr)
print("runtime %.1fs" % (time.time() - T0), file=sys.stderr)
