#!/usr/bin/env python3
"""V1b: independent enumeration of U / U5 under alternative readings.

Standard library only.  Implements R5-SPEC §7.1 (universe U, order R-1,
frame F1 & F2, U5 axiom) and V1B-SPEC (readings, check forms, fields,
named histories).  Writes V1B-RESULTS.json next to this file.
"""
import json
import os
import sys
import time
from array import array

T0 = time.time()

# ---------------------------------------------------------------------------
# Encoding (every code is ordered exactly as R-1 orders the component)
#   values: p=0 < q=1
#   act slot: 0 absent; 1..24 iss (d, v, prep, f) ; 25,26 adp (d)
#   f: 0, 1, 2 (=infinity)
#   Cap: 0 {} < 1 {(x,iss)} < 2 {(x,adp)} < 3 both    (bit0 iss, bit1 adp)
#   w1: 0 absent < 1 present t=0 < 2 present t=1
#   Ref: 4*[a1] + 2*[a2] + [w1]
#   con: 0 none < 1 rho1 ; r: 0<1 ; c: 0=c1 < 1=c2
# History tuple: (s0, s1, a1, a2, cap, w1, ref, con1, con2, r, c)
# ---------------------------------------------------------------------------
INF = 2
VN = "pq"
RADIX = (2, 2, 27, 27, 4, 3, 8, 2, 2, 2, 2)


def iss_code(d, v, prep, f):
    return 1 + d * 12 + v * 6 + prep * 3 + f


def adp_code(d):
    return 25 + d


def dec_slot(code):
    if code == 0:
        return None
    if code <= 24:
        i = code - 1
        return ("iss", i // 12, (i // 6) % 2, (i // 3) % 2, i % 3)
    return ("adp", code - 25)


SLOT = [dec_slot(k) for k in range(27)]


def strip_f(code):
    s = SLOT[code]
    if s is None or s[0] == "adp":
        return code
    return iss_code(s[1], s[2], s[3], 0)


STRIP = [strip_f(k) for k in range(27)]


def enc(h):
    n = 0
    for x, rd in zip(h, RADIX):
        n = n * rd + x
    return n


def dec(n):
    out = []
    for rd in reversed(RADIX):
        out.append(n % rd)
        n //= rd
    return tuple(reversed(out))


def well_formed(h):
    """Ranges of §7.1 (adp object present iss; Ref/con only on present items)."""
    s0, s1, a1, a2, cap, w1, ref, c1n, c2n, r, c = h
    sl = (SLOT[a1], SLOT[a2])
    for i in (0, 1):
        if sl[i] is not None and sl[i][0] == "adp":
            o = sl[1 - i]
            if o is None or o[0] != "iss":
                return False
    if (ref & 4) and sl[0] is None:
        return False
    if (ref & 2) and sl[1] is None:
        return False
    if (ref & 1) and w1 == 0:
        return False
    if c1n and sl[0] is None:
        return False
    if c2n and sl[1] is None:
        return False
    return True


def in_U(h):
    """F1 and F2 (and well-formedness)."""
    if not well_formed(h):
        return False
    s0, s1, a1, a2, cap, w1, ref, c1n, c2n, r, c = h
    sig = (s0, s1)
    for code in (a1, a2):
        s = SLOT[code]
        if s is not None and s[0] == "iss" and s[4] != INF and sig[s[4]] != s[2]:
            return False  # F1
    if c1n and not ((ref & 4) and SLOT[a1][1] == r):
        return False  # F2
    if c2n and not ((ref & 2) and SLOT[a2][1] == r):
        return False  # F2
    return True


def u5_axiom(h):
    return h[10] == 0 or (h[0] == 1 and h[1] == 1)


# ---------------------------------------------------------------------------
# Primitive features (everything the clauses and the small observations need)
# ---------------------------------------------------------------------------
def features(h):
    s0, s1, a1, a2, cap, w1, ref, c1n, c2n, r, c = h
    sig = (s0, s1)
    sl = (SLOT[a1], SLOT[a2])
    capI = bool(cap & 1)
    capA = bool(cap & 2)
    chg = s0 != s1  # Chg(h) = {(1, tau)} iff sigma(0) != sigma(1)
    act = False      # some iss a with f(a)=1, v(a)=sigma(1)
    act_cap = False  # ... and (alpha(a), iss) in Cap
    for s in sl:
        if s is not None and s[0] == "iss" and s[4] == 1 and s[2] == s1:
            act = True
            if capI:
                act_cap = True

    def adopted(i, t):
        s, o = sl[i], sl[1 - i]
        if s[3] == 0 and capA and s[1] <= t:
            return True
        # obj(b) = the other slot, by the ranges of §7.1
        if o is not None and o[0] == "adp" and capA and o[1] <= t:
            return True
        return False

    dst = True
    dstp = True
    for i in (0, 1):
        s = sl[i]
        if s is None or s[0] != "iss":
            continue
        if s[4] != INF and not adopted(i, s[4]):
            dst = False
        # Adopted is monotone in t, so "for some t in T" == "at t = N = 1"
        if (adopted(i, 0) or adopted(i, 1)) and s[4] == INF:
            dstp = False
    drec = bool(ref & 6)
    recW = bool(ref & 1)
    allref = ((sl[0] is None or ref & 4) and (sl[1] is None or ref & 2)
              and (w1 == 0 or ref & 1))
    enon = sl[0] is not None or sl[1] is not None
    return (chg, act, act_cap, dst, dstp, drec, recW, bool(allref), enon,
            s0, s1, w1, ref, cap, r, c, u5_axiom(h))


(F_CHG, F_ACT, F_ACTCAP, F_DST, F_DSTP, F_DREC, F_RECW, F_ALLREF, F_ENON,
 F_S0, F_S1, F_W1, F_REF, F_CAP, F_R, F_C, F_U5) = range(17)


def w1_conf(ft):
    """w1 present and conforms."""
    return ft[F_W1] != 0 and (ft[F_S0], ft[F_S1])[ft[F_W1] - 1] == 0


def w1_viol(ft):
    return ft[F_W1] != 0 and (ft[F_S0], ft[F_S1])[ft[F_W1] - 1] == 1


# ---------------------------------------------------------------------------
# Readings (Def V1.3 / Def P0.1)
# ---------------------------------------------------------------------------
READINGS = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h",
            "R-i", "R-i'", "R-j"]


def clauses(R, ft, dst_plus=False):
    """Return (R_B, H-D, H-B) membership for a history with features ft."""
    chg = ft[F_CHG]
    # D side
    if R == "R-e":
        dchg = (not chg) or ft[F_ACT]
    else:
        dchg = (not chg) or ft[F_ACTCAP]
    if R == "R-j":
        dchg = dchg and chg
    dst = ft[F_DST]
    if R == "R-d" or dst_plus:
        dst = dst and ft[F_DSTP]
    drec = ft[F_DREC]
    HD = dchg and dst and drec
    # B side
    bchg = (not chg) or ft[F_ACT] or ft[F_W1] == 1  # some w with t(w) < 1
    if R in ("R-g", "R-g+"):
        bchg = bchg and chg and ft[F_W1] == 1
    if R == "R-g+":
        bchg = bchg and ft[F_ACT]
    if R in ("R-b", "R-c"):
        bconf = w1_conf(ft)                       # some w in W conforms
    else:
        bconf = ft[F_W1] == 0 or w1_conf(ft)       # every w in W conforms
    if R in ("R-c", "R-f"):
        brec = drec
    elif R == "R-h":
        brec = drec and ft[F_RECW] and ft[F_ALLREF]
    elif R == "R-i":
        brec = ft[F_ENON]
    elif R == "R-i'":
        brec = True
    else:
        brec = drec and ft[F_RECW]
    HB = bchg and bconf and brec
    return bool(brec), bool(HD), bool(HB)


# ---------------------------------------------------------------------------
# Enumeration of U (in R-1 order)
# ---------------------------------------------------------------------------
def enumerate_U():
    codes = array("I")
    fids = array("I")
    kids = array("I")
    feat_id = {}
    feats = []
    key_id = {}
    keys = []
    for s0 in (0, 1):
        for s1 in (0, 1):
            for a1 in range(27):
                for a2 in range(27):
                    for cap in range(4):
                        for w1 in range(3):
                            for ref in range(8):
                                for c1n in (0, 1):
                                    for c2n in (0, 1):
                                        for r in (0, 1):
                                            for c in (0, 1):
                                                h = (s0, s1, a1, a2, cap, w1, ref,
                                                     c1n, c2n, r, c)
                                                if not in_U(h):
                                                    continue
                                                ft = features(h)
                                                fi = feat_id.get(ft)
                                                if fi is None:
                                                    fi = feat_id[ft] = len(feats)
                                                    feats.append(ft)
                                                k = (STRIP[a1], STRIP[a2], cap, w1,
                                                     ref, c1n, c2n, r, c)
                                                ki = key_id.get(k)
                                                if ki is None:
                                                    ki = key_id[k] = len(keys)
                                                    keys.append(k)
                                                codes.append(enc(h))
                                                fids.append(fi)
                                                kids.append(ki)
    return codes, fids, kids, feats, keys, key_id


# ---------------------------------------------------------------------------
# Pretty printing
# ---------------------------------------------------------------------------
def fs(f):
    return "inf" if f == INF else str(f)


def show_slot(code):
    s = SLOT[code]
    if s is None:
        return "absent"
    if s[0] == "iss":
        return "iss(d=%d,v=%s,prep=%d,f=%s)" % (s[1], VN[s[2]], s[3], fs(s[4]))
    return "adp(d=%d)" % s[1]


CAPS = ["{}", "{(x,iss)}", "{(x,adp)}", "{(x,iss),(x,adp)}"]


def show_ref(ref):
    items = [n for n, b in (("a1", 4), ("a2", 2), ("w1", 1)) if ref & b]
    return "{" + ",".join(items) + "}"


def show_w1(w1):
    return "absent" if w1 == 0 else "t=%d" % (w1 - 1)


def show_h(h):
    s0, s1, a1, a2, cap, w1, ref, c1n, c2n, r, c = h
    return {
        "sigma": "(%s,%s)" % (VN[s0], VN[s1]),
        "a1": show_slot(a1), "a2": show_slot(a2), "Cap": CAPS[cap],
        "w1": show_w1(w1), "Ref": show_ref(ref),
        "con": "(a1:%s,a2:%s)" % ("rho1" if c1n else "none",
                                  "rho1" if c2n else "none"),
        "r": r, "c": "c%d" % (c + 1),
        "tuple_R1": list(h),
    }


def show_O(r, c):
    return "(r=%d,c=c%d)" % (r, c + 1)


def show_key(k):
    a1, a2, cap, w1, ref, c1n, c2n, r, c = k

    def sl(code):
        s = SLOT[code]
        if s is not None and s[0] == "iss":
            return "iss(d=%d,v=%s,prep=%d)" % (s[1], VN[s[2]], s[3])
        return show_slot(code)
    return "a1=%s;a2=%s;Cap=%s;w1=%s;Ref=%s;con=(%s,%s);%s" % (
        sl(a1), sl(a2), CAPS[cap], show_w1(w1), show_ref(ref),
        "rho1" if c1n else "none", "rho1" if c2n else "none", show_O(r, c))


# Observations on feature tuples: (sort key, printable)
def obs_value(name, ft):
    r, c = ft[F_R], ft[F_C]
    if name == "O":
        k = (r, c)
        return k, show_O(r, c)
    if name == "O1":
        k = (r, c, ft[F_S0], ft[F_S1])
        return k, "(%s,sigma=(%s,%s))" % (show_O(r, c)[1:-1], VN[ft[F_S0]], VN[ft[F_S1]])
    if name == "O3":
        k = (r, c, ft[F_REF])
        return k, "(%s,Ref=%s)" % (show_O(r, c)[1:-1], show_ref(ft[F_REF]))
    if name == "O4":
        k = (r, c, ft[F_W1] != 0)
        return k, "(%s,w1=%s)" % (show_O(r, c)[1:-1],
                                   "present" if ft[F_W1] else "absent")
    if name == "Cap":
        k = (ft[F_CAP],)
        return k, CAPS[ft[F_CAP]]
    if name == "O_Cap":
        k = (r, c, ft[F_CAP])
        return k, "(%s,Cap=%s)" % (show_O(r, c)[1:-1], CAPS[ft[F_CAP]])
    raise KeyError(name)


FEAT_OBS = ["O", "O1", "O3", "O4", "Cap", "O_Cap"]
ALL_OBS = ["O", "O1", "O3", "O4", "O_notsigmaf", "Cap", "O_Cap"]
FULL_LIMIT = 300  # O_notsigmaf sets larger than this are summarised


# ---------------------------------------------------------------------------
# (iii)-L: K7 with the reading's classes
# ---------------------------------------------------------------------------
def iii_candidates(h):
    """All h' allowed by K7's literal constraints for h (h has a2 absent and
    w1 present).  sigma' differs from sigma only at t(w1); f of existing iss
    acts free; a2 optionally added as iss, prep 1, unreferenced, con none
    (any d, v, f; actor x)."""
    s0, s1, a1, a2, cap, w1, ref, c1n, c2n, r, c = h
    tw = w1 - 1
    sig_opts = []
    for x in (0, 1):
        sg = [s0, s1]
        sg[tw] = x
        sig_opts.append(tuple(sg))
    s = SLOT[a1]
    if s is not None and s[0] == "iss":
        a1_opts = [iss_code(s[1], s[2], s[3], f) for f in (0, 1, INF)]
    else:
        a1_opts = [a1]
    a2_opts = [0] + [iss_code(d, v, 1, f) for d in (0, 1) for v in (0, 1)
                     for f in (0, 1, INF)]
    out = []
    for sg in sig_opts:
        for x1 in a1_opts:
            for x2 in a2_opts:
                out.append((sg[0], sg[1], x1, x2, cap, w1, ref, c1n, 0, r, c))
    return out


def iii_ok(h, hp, R, frame):
    """Is hp a valid (iii)-L witness for h under reading R in frame?"""
    if not in_U(hp):
        return False
    if frame == "U5" and not u5_axiom(hp):
        return False
    ft = features(hp)
    if not clauses(R, ft)[2]:
        return False  # h' in H-B^R
    if not w1_conf(ft):
        return False  # w1 conforms in h'
    if hp[0] != hp[1]:  # change point at t = 1
        new = hp[1]
        s = SLOT[hp[2]]  # the act of h (a1), with f' of h'
        by_h_act = s is not None and s[0] == "iss" and s[4] == 1 and s[2] == new
        if not by_h_act:
            s2 = SLOT[hp[3]]
            if not (s2 is not None and s2[0] == "iss" and s2[4] == 1):
                return False
    return True


def iii_witness(h, R, frame):
    best = None
    for hp in iii_candidates(h):
        if iii_ok(h, hp, R, frame):
            if best is None or hp < best:
                best = hp
    return best


def iii_scope(R, ft):
    rb, hd, hb = clauses(R, ft)
    return hd and rb and w1_viol(ft)  # a2 absence checked on the tuple


# ---------------------------------------------------------------------------
# Named histories (V1B-SPEC §C)
# ---------------------------------------------------------------------------
p, q = 0, 1
I1 = iss_code
NAMED = {
    "H_c": ((q, q, I1(0, q, 1, INF), 0, 0, 0, 4, 0, 0, 0, 0),
            ["R-c"]),
    "H_d": ((p, p, 0, I1(0, q, 0, INF), 2, 1, 3, 0, 0, 0, 0), ["R-d"]),
    "H_d2": ((p, p, I1(0, p, 0, 0), I1(0, q, 0, INF), 2, 1, 3, 0, 0, 0, 0),
             ["R-d"]),
    "H_g": ((p, q, I1(0, q, 0, 1), 0, 3, 1, 5, 0, 0, 0, 0),
            ["R-g", "R-j", "R-g+"]),
    "H_g'": ((p, q, I1(0, q, 0, 1), 0, 2, 1, 5, 0, 0, 0, 0), ["R-g"]),
    "I7": ((q, q, I1(0, q, 1, INF), 0, 0, 2, 5, 0, 0, 0, 0), ["R-h", "R0"]),
    "W7": ((q, p, I1(0, q, 1, INF), I1(1, p, 1, 1), 0, 2, 7, 0, 0, 0, 0),
           ["R-h", "R0"]),
    "H_i": ((p, p, I1(0, p, 1, INF), 0, 0, 1, 1, 0, 0, 0, 0), ["R-i", "R-i'"]),
    "H_empty": ((p, p, 0, 0, 0, 0, 0, 0, 0, 0, 0), ["R-i", "R-i'"]),
    "H_j": ((p, p, 0, I1(0, p, 0, 0), 3, 1, 3, 0, 0, 0, 0), ["R-j"]),
    "H_g+": ((p, q, I1(0, q, 1, INF), 0, 0, 1, 5, 0, 0, 0, 0), ["R-g+"]),
    # V1B-SUPP-SPEC
    "H_U5h": ((q, q, I1(0, q, 1, INF), 0, 0, 1, 5, 0, 0, 0, 1), ["R-h"]),
    "H_U5i": ((q, q, I1(0, q, 1, INF), 0, 0, 1, 5, 0, 0, 0, 1),
              ["R-i", "R-i'"]),
    "H_U5i_c1": ((q, q, I1(0, q, 1, INF), 0, 0, 1, 5, 0, 0, 0, 0),
                 ["R-i", "R-i'"]),
}


def key_of(h):
    return (STRIP[h[2]], STRIP[h[3]], h[4], h[5], h[6], h[7], h[8], h[9], h[10])


def key_nocap(k):
    return k[:2] + k[3:]


# ---------------------------------------------------------------------------
def sorted_strings(pairs):
    return [s for _, s in sorted(pairs)]


def run():
    codes, fids, kids, feats, keys, key_id = enumerate_U()
    N = len(codes)
    t_enum = time.time() - T0
    print("U enumerated: %d histories, %d feature classes, %d sigma/f-keys "
          "(%.1fs)" % (N, len(feats), len(keys), t_enum), flush=True)

    results = {}
    meta = {"U_size": N}
    u5_by_fid = [ft[F_U5] for ft in feats]
    meta["U5_size"] = sum(1 for f in fids if u5_by_fid[f])

    # R0-based H-D+ (D-st+) and H-B for K4b and exists(H-D+, ~ H_d2)
    r0_plus = [clauses("R0", ft, dst_plus=True) for ft in feats]

    for frame in ("U", "U5"):
        inF = [True] * len(feats) if frame == "U" else u5_by_fid
        idx = [i for i in range(N) if inF[fids[i]]]

        # literal K4b / exists(H-D+) sets (R0 classes)
        k4b_keys = set()
        hdplus_keys = set()
        for i in idx:
            rb, hd, hb = r0_plus[fids[i]]
            if hd:
                hdplus_keys.add(kids[i])
                if hb:
                    k4b_keys.add(key_nocap(keys[kids[i]]))

        for R in READINGS:
            t1 = time.time()
            cls = [clauses(R, ft) for ft in feats]
            clsp = [clauses(R, ft, dst_plus=True) for ft in feats]
            classes_def = {
                "R_B": lambda c: c[0],
                "H-D": lambda c: c[1],
                "H-B": lambda c: c[2],
                "H-D&R_B": lambda c: c[1] and c[0],
                "H-D&H-B": lambda c: c[1] and c[2],
            }
            cls_fids = {n: set() for n in classes_def}
            cls_keys = {n: set() for n in classes_def}
            sizes = {n: 0 for n in classes_def}
            hdhb_keys = set()
            hv_keys, hc_keys = set(), set()
            hv_O, hc_O = set(), set()
            k4bR_keys = set()
            hdplusR_keys = set()
            for i in idx:
                f = fids[i]
                c = cls[f]
                k = kids[i]
                ft = feats[f]
                for n, fn in classes_def.items():
                    if fn(c):
                        cls_fids[n].add(f)
                        cls_keys[n].add(k)
                        sizes[n] += 1
                if c[1] and c[2]:
                    hdhb_keys.add(k)
                    if w1_conf(ft):
                        hc_keys.add(k)
                        hc_O.add((ft[F_R], ft[F_C]))
                if c[1] and not c[2] and ft[F_S0] == ft[F_S1] and w1_viol(ft):
                    hv_keys.add(k)
                    hv_O.add((ft[F_R], ft[F_C]))
                cp = clsp[f]
                if cp[1]:
                    hdplusR_keys.add(k)
                    if cp[2]:
                        k4bR_keys.add(key_nocap(keys[k]))

            checks = {}
            # (i)-S
            ce = None
            for i in idx:
                if cls[fids[i]][0] and kids[i] not in hdhb_keys:
                    ce = i
                    break
            checks["(i)-S"] = {"holds": ce is None,
                               "least_counterexample": None if ce is None
                               else show_h(dec(codes[ce]))}
            # (i)-O
            Oimg = {n: {(feats[f][F_R], feats[f][F_C]) for f in cls_fids[n]}
                    for n in ("H-D&R_B", "H-B", "H-D&H-B", "R_B")}
            common = set.intersection(*Oimg.values())
            allv = set.union(*Oimg.values())
            ce = None
            if common != allv:
                for i in idx:
                    c = cls[fids[i]]
                    ft = feats[fids[i]]
                    inany = c[0] or c[2]  # every listed class is within R_B or H-B
                    if inany and (ft[F_R], ft[F_C]) not in common:
                        ce = i
                        break
            checks["(i)-O"] = {
                "holds": common == allv,
                "images": {n: sorted_strings((v, show_O(*v)) for v in s)
                           for n, s in Oimg.items()},
                "least_counterexample": None if ce is None
                else show_h(dec(codes[ce])),
            }
            # (ii)-S and (ii)-O
            for form in ("S", "O"):
                ce = None
                why = None
                for i in idx:
                    f = fids[i]
                    ft = feats[f]
                    if not (cls[f][0] and ft[F_RECW]):
                        continue
                    if form == "S":
                        okv = kids[i] in hv_keys
                        okc = kids[i] in hc_keys
                    else:
                        o = (ft[F_R], ft[F_C])
                        okv = o in hv_O
                        okc = o in hc_O
                    if not (okv and okc):
                        ce = i
                        why = {"h_v_exists": okv, "h_c_exists": okc}
                        break
                checks["(ii)-" + form] = {
                    "holds": ce is None,
                    "least_counterexample": None if ce is None
                    else dict(show_h(dec(codes[ce])), **why),
                }
            # (iii)-L
            ce = None
            n_scope = 0
            n_fail = 0
            for i in idx:
                f = fids[i]
                if not iii_scope(R, feats[f]):
                    continue
                h = dec(codes[i])
                if h[3] != 0:
                    continue
                n_scope += 1
                if iii_witness(h, R, frame) is None:
                    n_fail += 1
                    if ce is None:
                        ce = i
            checks["(iii)-L"] = {
                "holds": ce is None, "scope_size": n_scope,
                "n_counterexamples": n_fail,
                "least_counterexample": None if ce is None
                else show_h(dec(codes[ce])),
            }
            checks["(ii)_holds(S and L-form=O-form)"] = (
                checks["(ii)-S"]["holds"] and checks["(ii)-O"]["holds"])
            checks["(iii)_holds"] = checks["(iii)-L"]["holds"]

            # images and diffs
            images = {}
            diffs = {}
            for ob in ALL_OBS:
                img = {}
                raw = {}
                for n in classes_def:
                    if ob == "O_notsigmaf":
                        s = cls_keys[n]
                        raw[n] = {keys[k]: show_key(keys[k]) for k in s}
                    else:
                        raw[n] = dict(obs_value(ob, feats[f]) for f in cls_fids[n])
                    img[n] = summarise(raw[n])
                images[ob] = img
                d = {}
                for a, b in (("H-D&R_B", "H-B"), ("R_B", "H-B"),
                             ("R_B", "H-D&H-B")):
                    dd = {k: v for k, v in raw[a].items() if k not in raw[b]}
                    d["%s \\ %s" % (a, b)] = summarise(dd)
                diffs[ob] = d

            # named histories
            named = {}
            for name, (h, designated) in NAMED.items():
                ft = features(h)
                inframe = in_U(h) and (frame == "U" or u5_axiom(h))
                rb, hd, hb = clauses(R, ft)
                k = key_of(h)
                ki = key_id.get(k)
                o = (h[9], h[10])
                entry = {
                    "designated_for": designated,
                    "designated_here": R in designated,
                    "in_frame": inframe,
                    "member(R_B)": rb and inframe,
                    "member(H-D)": hd and inframe,
                    "member(H-B)": hb and inframe,
                    "iS": ki is not None and ki in hdhb_keys,
                    "K4b": key_nocap(k) in k4b_keys,
                    "K4b_with_reading_classes": key_nocap(k) in k4bR_keys,
                    "iiO_c": o in hc_O,
                    "O": show_O(*o),
                }
                ins = inframe and hd and rb and w1_viol(ft) and h[3] == 0
                wit = iii_witness(h, R, frame) if (h[5] != 0 and h[3] == 0) else None
                entry["iiiL_in_scope"] = ins
                entry["iiiL"] = (wit is not None) if ins else None
                entry["iiiL_exists_ignoring_scope"] = (
                    (wit is not None) if (h[5] != 0 and h[3] == 0) else None)
                entry["iiiL_least_witness"] = (
                    show_h(wit) if wit is not None else None)
                if name == "H_d2":
                    entry["exists(H-D+, ~sf H_d2)"] = ki is not None and ki in hdplus_keys
                    entry["exists(H-D^R with D-st+, ~sf H_d2)"] = (
                        ki is not None and ki in hdplusR_keys)
                named[name] = entry

            results.setdefault(R, {})[frame] = {
                "class_sizes": sizes,
                "checks": checks,
                "images": images,
                "diffs": diffs,
                "named": named,
            }
            print("  %-5s %-3s done (%.1fs)  (i)-S=%s (i)-O=%s (ii)-S=%s (ii)-O=%s "
                  "(iii)-L=%s" % (R, frame, time.time() - t1,
                                  checks["(i)-S"]["holds"], checks["(i)-O"]["holds"],
                                  checks["(ii)-S"]["holds"], checks["(ii)-O"]["holds"],
                                  checks["(iii)-L"]["holds"]), flush=True)

    meta["runtime_seconds"] = round(time.time() - T0, 1)
    out = {"_meta": meta}
    out.update(results)
    path = os.path.join(os.path.dirname(os.path.abspath(__file__)),
                        "V1B-RESULTS.json")
    with open(path, "w") as fh:
        json.dump(out, fh, indent=1, ensure_ascii=False)
    print("runtime %.1fs; wrote %s" % (meta["runtime_seconds"], path))


def summarise(d):
    """d: {sortkey: printable}.  Full set when small, else count + head."""
    items = sorted_strings(d.items())
    if len(items) <= FULL_LIMIT:
        return {"count": len(items), "values": items}
    return {"count": len(items), "truncated": True,
            "first_values_in_order": items[:20]}


if __name__ == "__main__":
    run()
