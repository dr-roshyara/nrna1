#!/usr/bin/env python3
"""Independent finite-state verification of R5-SPEC section 7 and V1B-SPEC (+SUPP).

The raw assignment space is the mixed-radix product of the enumerated coordinates
in the R-1 order, so the integer code of a history *is* its rank in R-1 order
(restricted to the frame, the order is the order of codes).  Every class is a
boolean array over the raw code space.
Usage: python3 verify.py OUTDIR
"""
import sys, json, time, hashlib
import numpy as np


def _js(o):
    if isinstance(o, np.generic): return o.item()
    if isinstance(o, np.ndarray): return o.tolist()
    raise TypeError(type(o))

T0 = time.time()
OUT = sys.argv[1] if len(sys.argv) > 1 else "."

# ---------------------------------------------------------------- universe
RAD = [2, 2, 27, 27, 4, 3, 8, 2, 2, 2, 2]   # s0 s1 a1 a2 cap w ref con1 con2 r c
W = [1] * len(RAD)
for i in range(len(RAD) - 2, -1, -1):
    W[i] = W[i + 1] * RAD[i + 1]
NRAW = W[0] * RAD[0]
code = np.arange(NRAW, dtype=np.int64)


def dig(i):
    return ((code // W[i]) % RAD[i]).astype(np.int8)


s0, s1, a1i, a2i, capd, wd, refd, con1, con2, rr, cc = [dig(i) for i in range(11)]
I8 = np.int8


def slot_dec(idx):
    kind = np.where(idx == 0, 0, np.where(idx <= 24, 1, 2)).astype(I8)
    j = np.clip(idx.astype(np.int16) - 1, 0, 23)
    f = np.where(kind == 1, j % 3, 0).astype(I8)
    prep = np.where(kind == 1, (j // 3) % 2, 0).astype(I8)
    v = np.where(kind == 1, (j // 6) % 2, 0).astype(I8)
    d = np.where(kind == 1, j // 12, np.where(kind == 2, idx - 25, 0)).astype(I8)
    return kind, d, v, prep, f


k1, d1, v1, p1, f1 = slot_dec(a1i)
k2, d2, v2, p2, f2 = slot_dec(a2i)
capI = (capd & 1).astype(bool)
capA = ((capd >> 1) & 1).astype(bool)
wp = wd > 0
tw = np.where(wp, wd - 1, 0).astype(I8)
ra1 = ((refd >> 2) & 1).astype(bool)
ra2 = ((refd >> 1) & 1).astype(bool)
rw = (refd & 1).astype(bool)
Pk = (k1 > 0), (k2 > 0)
sig_tw = np.where(tw == 0, s0, s1)          # sigma(t(w1)), meaningful if wp
KIND = (k1, k2); D = (d1, d2); V = (v1, v2); PREP = (p1, p2); F = (f1, f2)
RA = (ra1, ra2); CON = (con1, con2)

# base frame: F1, F2, typing, Ref restricted to present items, con only on present acts
base = np.ones(NRAW, dtype=bool)
base &= ~((k1 == 2) & (k2 != 1))
base &= ~((k2 == 2) & (k1 != 1))
base &= ~(ra1 & (k1 == 0)) & ~(ra2 & (k2 == 0)) & ~(rw & ~wp)
for k in range(2):
    base &= ~((CON[k] == 1) & (KIND[k] == 0))
    base &= ~((CON[k] == 1) & ~RA[k])
    base &= ~((CON[k] == 1) & (D[k] != rr))
    sg = np.where(F[k] == 0, s0, s1)
    base &= ~((KIND[k] == 1) & (F[k] < 2) & (sg != V[k]))

frames = {}
frames["U"] = base
frames["U5"] = base & ~((cc == 1) & ~((s0 == 1) & (s1 == 1)))
frames["U6"] = base & (s0 == 0) & (s1 == 0) & ~((k1 == 1) & (v1 == 1)) & ~((k2 == 1) & (v2 == 1))
frames["U7"] = base & ~((k1 > 0) & ~ra1) & ~((k2 > 0) & ~ra2) & ~(wp & ~rw)
frames["U9"] = base & ~((cc == 1) & ((con1 == 1) | (con2 == 1)))
NU = int(base.sum())
print("raw", NRAW, "|U|", NU, {k: int(v.sum()) for k, v in frames.items()}, flush=True)

# ---------------------------------------------------------------- clauses
change = s0 != s1


def adopted(k, t):
    o = 1 - k
    return (KIND[k] == 1) & (((PREP[k] == 0) & capA & (D[k] <= t)) | ((KIND[o] == 2) & capA & (D[o] <= t)))


ad_k_t = {(k, t): adopted(k, t) for k in range(2) for t in range(2)}
act_f1 = np.zeros(NRAW, dtype=bool)          # some iss s with f=1, v=sigma(1)
for k in range(2):
    act_f1 |= (KIND[k] == 1) & (F[k] == 1) & (V[k] == s1)
Dchg = ~change | (act_f1 & capI)
Dchg_e = ~change | act_f1
Dchg_j = Dchg & change
Dst = np.ones(NRAW, dtype=bool)
Dstp = np.ones(NRAW, dtype=bool)
for k in range(2):
    ad_at_f = np.where(F[k] == 0, ad_k_t[(k, 0)], ad_k_t[(k, 1)])
    Dst &= ~((KIND[k] == 1) & (F[k] < 2)) | ad_at_f
    Dstp &= ~((KIND[k] == 1) & (F[k] < 2)) | ad_at_f
    Dstp &= ~(ad_k_t[(k, 1)] | ad_k_t[(k, 0)]) | (F[k] < 2)
Drec = ra1 | ra2
Bchg = ~change | act_f1 | (wp & (tw == 0))
Bchg_g = Bchg & change & wp & (tw == 0)
Bchg_gp = Bchg_g & act_f1
Bconf = ~wp | (sig_tw == 0)
Bconf_b = wp & (sig_tw == 0)
BrecW = rw
Brec = Drec & BrecW
Brec_h = Drec & BrecW & ~((k1 > 0) & ~ra1) & ~((k2 > 0) & ~ra2) & ~(wp & ~rw)
Brec_i = (k1 > 0) | (k2 > 0)
Brec_ip = np.ones(NRAW, dtype=bool)

READINGS = {
    "R0": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec),
    "R-b": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf_b, Brec=Brec),
    "R-c": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf_b, Brec=Drec),
    "R-d": dict(Dchg=Dchg, Dst=Dstp, Bchg=Bchg, Bconf=Bconf, Brec=Brec),
    "R-e": dict(Dchg=Dchg_e, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec),
    "R-f": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Drec),
    "R-g": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg_g, Bconf=Bconf, Brec=Brec),
    "R-g+": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg_gp, Bconf=Bconf, Brec=Brec),
    "R-h": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec_h),
    "R-i": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec_i),
    "R-i'": dict(Dchg=Dchg, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec_ip),
    "R-j": dict(Dchg=Dchg_j, Dst=Dst, Bchg=Bchg, Bconf=Bconf, Brec=Brec),
}


def classes(reading, fr):
    r = READINGS[reading]
    Fm = frames[fr]
    HD = Fm & r["Dchg"] & r["Dst"] & Drec
    HB = Fm & r["Bchg"] & r["Bconf"] & r["Brec"]
    RB = Fm & r["Brec"]
    HDp = Fm & r["Dchg"] & (Dstp if True else None) & Drec    # H-D+ (D-st replaced by D-st+)
    return dict(F=Fm, HD=HD, HB=HB, RB=RB, HDp=HDp)


# ---------------------------------------------------------------- keys / observations
proj_tab = np.zeros(27, dtype=np.int64)
for i in range(1, 25):
    j = i - 1
    proj_tab[i] = 1 + ((j // 12) * 2 + (j // 6) % 2) * 2 + (j // 3) % 2
proj_tab[25] = 9; proj_tab[26] = 10
pr1 = proj_tab[a1i]; pr2 = proj_tab[a2i]
key_nsf = (((((((pr1 * 11 + pr2) * 4 + capd) * 3 + wd) * 8 + refd) * 2 + con1) * 2 + con2) * 2 + rr) * 2 + cc
key_nsfc = (((((((pr1 * 11 + pr2) * 3 + wd) * 8 + refd) * 2 + con1) * 2 + con2) * 2 + rr) * 2 + cc)  # also Cap erased
Oo = (rr.astype(np.int64) * 2 + cc)


def fh0(k):
    return np.where((KIND[k] == 1) & (PREP[k] == 0) & (F[k] < 2), 1 + V[k] * 2 + F[k], 0).astype(np.int64)


def fh(k):
    return np.where((KIND[k] == 1) & (F[k] < 2), 1 + V[k] * 2 + F[k], 0).astype(np.int64)


def dc(k):
    return np.where(KIND[k] > 0, D[k] + 1, 0).astype(np.int64)


OBS = {}
OBS["O"] = Oo
OBS["O1"] = Oo * 4 + s0 * 2 + s1
OBS["O2"] = (Oo * 5 + fh0(0)) * 5 + fh0(1)
OBS["O3"] = Oo * 8 + refd
OBS["O3p"] = Oo * 4 + con1 * 2 + con2
OBS["O4"] = Oo * 2 + wp
OBS["O4p"] = (Oo * 3 + KIND[0]) * 3 + KIND[1]
OBS["O8"] = (Oo * 3 + np.where((KIND[0] == 1) & (PREP[0] == 0), D[0] + 1, 0)) * 3 + np.where((KIND[1] == 1) & (PREP[1] == 0), D[1] + 1, 0)
OBS["Oplus"] = ((((Oo * 8 + refd) * 4 + con1 * 2 + con2) * 3 + dc(0)) * 3 + dc(1))
OBS["Onsf"] = key_nsf
OBS["Cap"] = capd.astype(np.int64)

# ---------------------------------------------------------------- history <-> code
SIG = ["p", "q"]; FN = [0, 1, "inf"]


def slot_to_json(idx):
    if idx == 0:
        return None
    if idx <= 24:
        j = idx - 1
        return {"kind": "iss", "d": j // 12, "v": SIG[(j // 6) % 2], "prep": (j // 3) % 2, "f": FN[j % 3]}
    return {"kind": "adp", "d": idx - 25}


def hist(c):
    c = int(c)
    dg = [(c // W[i]) % RAD[i] for i in range(11)]
    sl = [slot_to_json(dg[2]), slot_to_json(dg[3])]
    if sl[0] and sl[0]["kind"] == "adp": sl[0]["obj"] = "a2"
    if sl[1] and sl[1]["kind"] == "adp": sl[1]["obj"] = "a1"
    cap = [n for b, n in ((1, "iss"), (2, "adp")) if dg[4] & b]
    ref = [n for b, n in ((4, "a1"), (2, "a2"), (1, "w1")) if dg[6] & b]
    return {"sigma": [SIG[dg[0]], SIG[dg[1]]], "a1": sl[0], "a2": sl[1], "Cap": cap,
            "w1": None if dg[5] == 0 else {"t": dg[5] - 1}, "Ref": ref,
            "con": {"a1": None if dg[2] == 0 else ("rho1" if dg[7] else "none"),
                    "a2": None if dg[3] == 0 else ("rho1" if dg[8] else "none")},
            "r": dg[9], "c": "c%d" % (dg[10] + 1)}


def iss(d, v, prep, f):
    return 1 + ((d * 2 + SIG.index(v)) * 2 + prep) * 3 + FN.index(f)


def enc(sigma, a1, a2, cap, w, ref, con=(0, 0), r=0, c=1):
    """a1,a2: slot idx; cap: iterable of 'iss','adp'; w: None or t; ref: iterable of a1,a2,w1; c in {1,2}"""
    cp = (1 if "iss" in cap else 0) + (2 if "adp" in cap else 0)
    rf = (4 if "a1" in ref else 0) + (2 if "a2" in ref else 0) + (1 if "w1" in ref else 0)
    ds = [SIG.index(sigma[0]), SIG.index(sigma[1]), a1, a2, cp, 0 if w is None else w + 1, rf, con[0], con[1], r, c - 1]
    return sum(x * W[i] for i, x in enumerate(ds))


def jvals(arr, dec):
    return [dec(int(x)) for x in arr]


def o_dec(o): return [o // 2, "c%d" % (o % 2 + 1)]
def sg_dec(x): return [SIG[x // 2], SIG[x % 2]]


def first(mask):
    ix = np.flatnonzero(mask)
    return int(ix[0]) if len(ix) else None


def H(c):
    return None if c is None else hist(c)


# ---------------------------------------------------------------- (iii)-L
ALLOW_NEW_A2 = [0] + [iss(d, v, 1, f) for d in (0, 1) for v in SIG for f in FN]


def iii_exists(idx, cl):
    """idx: array of codes of histories with a2 absent and w1 present. Returns bool array: exists h' as in K7/(iii)-L."""
    HBm, Fm = cl["HB"], cl["F"]
    res = np.zeros(len(idx), dtype=bool)
    cs = [(idx // W[i]) % RAD[i] for i in range(11)]
    S0, S1, A1, A2, CP, WD, RF, C1, C2, R, C = cs
    TW = WD - 1
    kk1, dd1, vv1, pp1, ff1 = slot_dec(A1.astype(np.int64))
    for sv in (0, 1):
        ns0 = np.where(TW == 0, sv, S0); ns1 = np.where(TW == 1, sv, S1)
        conf = (sv == 0)
        for fnew in (0, 1, 2):
            nA1 = np.where(kk1 == 1, A1 - ff1 + fnew, A1)
            for na2 in ALLOW_NEW_A2:
                c2 = (ns0 * W[0] + ns1 * W[1] + nA1 * W[2] + na2 * W[3] + CP * W[4] + WD * W[5] + RF * W[6]
                      + C1 * W[7] + C2 * W[8] + R * W[9] + C * W[10])
                ok = HBm[c2] & Fm[c2]
                if not conf:
                    ok = ok & False
                chg = ns0 != ns1
                # change point at 1: some act of h enters into force (f') with the new value, or f(a2) = 1 (a2 added)
                a1_ok = (kk1 == 1) & (fnew == 1) & (vv1 == ns1)
                a2_ok = (na2 != 0) and slot_dec(np.array([na2]))[4][0] == 1
                ok = ok & (~chg | a1_ok | a2_ok)
                res |= ok
    return res


def in_iii_scope(cl):
    return cl["HD"] & cl["RB"] & (k2 == 0) & wp & (sig_tw == 1)


def iii_L(cl):
    sc = in_iii_scope(cl)
    idx = np.flatnonzero(sc).astype(np.int64)
    ex = iii_exists(idx, cl)
    bad = idx[~ex]
    return (len(bad) == 0), (int(bad[0]) if len(bad) else None), len(idx)


# ---------------------------------------------------------------- check forms (i), (ii), (iii)
def img(obs, m):
    return np.unique(obs[m])


def form_checks(cl):
    HD, HB, RB, Fm = cl["HD"], cl["HB"], cl["RB"], cl["F"]
    DB = HD & HB
    out = {}
    # (i)-S
    ks = np.unique(key_nsf[DB])
    miss = RB & ~np.isin(key_nsf, ks)
    c = first(miss)
    out["(i)-S"] = {"holds": c is None, "least_counterexample": H(c)}
    # (i)-O
    s_a, s_b, s_c, s_d = img(Oo, HD & RB), img(Oo, HB), img(Oo, DB), img(Oo, RB)
    eq = all(np.array_equal(s_a, x) for x in (s_b, s_c, s_d))
    out["(i)-O"] = {"holds": bool(eq)}
    # (ii)
    scope = RB & rw
    hv = HD & ~HB & (s0 == s1) & wp & (sig_tw == 1)
    hc = DB & wp & (sig_tw == 0)
    okS = np.isin(key_nsf, np.unique(key_nsf[hv])) & np.isin(key_nsf, np.unique(key_nsf[hc]))
    c = first(scope & ~okS)
    out["(ii)-S"] = {"holds": c is None, "least_counterexample": H(c)}
    okO = np.isin(Oo, np.unique(Oo[hv])) & np.isin(Oo, np.unique(Oo[hc]))
    c = first(scope & ~okO)
    out["(ii)-O"] = {"holds": c is None, "least_counterexample": H(c)}
    holds, c, n = iii_L(cl)
    out["(iii)-L"] = {"holds": holds, "least_counterexample": H(c), "scope_size": n}
    return out


# ================================================================== R5 CHECKS
def run_R5():
    res = {}
    U = classes("R0", "U")
    HD, HB, RB = U["HD"], U["HB"], U["RB"]
    DB = HD & HB
    allU = base

    # K1
    w = {"H-D": first(HD), "H-B": first(HB), "H-D&H-B": first(DB), "H-D\\H-B": first(HD & ~HB), "H-B\\H-D": first(HB & ~HD)}
    lem36 = bool(((HD | HB) & ~((k1 > 0) | (k2 > 0))).sum() == 0)
    c36 = first((HD | HB) & ~((k1 > 0) | (k2 > 0)))
    res["K1"] = {"result": all(v is not None for v in w.values()) and lem36,
                 "least_witness_or_counterexample": {k: H(v) for k, v in w.items()},
                 "sizes": {"H-D": int(HD.sum()), "H-B": int(HB.sum()), "H-D&H-B": int(DB.sum()), "H-D\\H-B": int((HD & ~HB).sum()),
                           "H-B\\H-D": int((HB & ~HD).sum()), "R_B": int(RB.sum())},
                 "lem36_all_have_present_act": lem36, "lem36_counterexample": H(c36)}
    # K2
    Fm = base
    c_a = first(Fm & Dchg & ~Bchg); c_b = first(Fm & Brec & ~Drec)
    w1_ = first(Fm & Bchg & ~Dchg); w2_ = first(Fm & Drec & ~Brec)
    res["K2"] = {"result": c_a is None and c_b is None and w1_ is not None and w2_ is not None,
                 "least_witness_or_counterexample": {"counterexample_D-chg=>B-chg": H(c_a), "counterexample_B-rec=>D-rec": H(c_b),
                                                     "witness_against_converse_B-chg=>D-chg": H(w1_), "witness_against_converse_D-rec=>B-rec": H(w2_)}}
    # K3
    A = HD & HB; B = HD & Bconf & BrecW & Fm; C = HB & Dchg & Dst
    cex = first((A != B) | (B != C))
    res["K3"] = {"result": cex is None, "least_witness_or_counterexample": H(cex)}
    # K4
    ks = np.unique(key_nsf[DB])
    c = first(RB & ~np.isin(key_nsf, ks))
    res["K4"] = {"result": c is None, "least_witness_or_counterexample": H(c)}
    # K4b
    HDp = U["HDp"]
    ksb = np.unique(key_nsfc[HDp & HB])
    c = first(RB & ~np.isin(key_nsfc, ksb))
    res["K4b"] = {"result": c is None, "least_witness_or_counterexample": H(c)}
    # K5
    allo = np.arange(4)
    ok5 = all(np.array_equal(img(Oo, m), allo) for m in (HD, HB, DB))
    res["K5"] = {"result": bool(ok5), "least_witness_or_counterexample": None,
                 "images": {"O(H-D)": jvals(img(Oo, HD), o_dec), "O(H-B)": jvals(img(Oo, HB), o_dec), "O(H-D&H-B)": jvals(img(Oo, DB), o_dec)}}
    # K6, K7
    fc = form_checks(U)
    res["K6"] = {"result": fc["(ii)-S"]["holds"], "least_witness_or_counterexample": fc["(ii)-S"]["least_counterexample"]}
    res["K7"] = {"result": fc["(iii)-L"]["holds"], "least_witness_or_counterexample": fc["(iii)-L"]["least_counterexample"],
                 "scope_size": fc["(iii)-L"]["scope_size"]}

    # K8.1
    O1 = OBS["O1"]
    sB, sDR = img(O1, HB), img(O1, HD & RB)
    diff = np.setdiff1d(sDR, sB)
    proper = bool(np.isin(sB, sDR).all() and len(diff) > 0)
    perO = {}
    for o in range(4):
        perO["(r=%d,%s)" % tuple(o_dec(o)[:1] + [o_dec(o)[1]])] = [sg_dec(int(x) % 4) for x in diff if int(x) // 4 == o]
    sigvals = sorted({tuple(sg_dec(int(x) % 4)) for x in diff})
    wit = first((HD & RB) & np.isin(O1, diff))
    res["K8.1"] = {"result": proper, "least_witness_or_counterexample": H(wit),
                   "difference_sigma_values": [list(s) for s in sigvals],
                   "difference_by_o": perO, "n_O1(H-B)": len(sB), "n_O1(H-D&R_B)": len(sDR),
                   "O1_subset": bool(np.isin(sB, sDR).all())}
    # K8.2
    O2 = OBS["O2"]
    # F^0 = {(a1,q,0),(a2,q,1)}: slot code 1+v*2+f ; (q,0)->3 ; (q,1)->4
    tgt = np.array([(o * 5 + 3) * 5 + 4 for o in range(4)])
    inA = np.isin(tgt, img(O2, HD & RB)); inB = np.isin(tgt, img(O2, HB))
    ok = bool((inA & ~inB).all())
    wit = first((HD & RB) & (OBS["O2"] % 25 == 3 * 5 + 4))
    res["K8.2"] = {"result": ok, "least_witness_or_counterexample": H(wit),
                   "per_o_in_O2(H-D&R_B)": [bool(x) for x in inA], "per_o_in_O2(H-B)": [bool(x) for x in inB]}
    # K8.3
    O3 = OBS["O3"]
    dd = np.setdiff1d(img(O3, HD), img(O3, HB))
    nw = [int(x) for x in dd if int(x) % 2 == 0]       # Ref bit w1 = 0
    wit = first(HD & np.isin(O3, np.array(nw, dtype=np.int64)))
    imgs = [img(O3, m) for m in (HD & RB, HB, DB, RB)]
    iholds = all(np.array_equal(imgs[0], x) for x in imgs[1:])
    res["K8.3"] = {"result": len(nw) > 0 and iholds, "least_witness_or_counterexample": H(wit),
                   "n_values_without_w1_ref_in_O3(H-D)\\O3(H-B)": len(nw), "(i)_holds_for_O3_on_R_B": bool(iholds),
                   "example_values": [[int(x) // 16, "c%d" % (int(x) // 8 % 2 + 1), [n for b, n in ((4, "a1"), (2, "a2"), (1, "w1")) if (int(x) % 8) & b]] for x in nw[:4]]}
    # K8.4
    O4 = OBS["O4"]
    abs_vals = np.array([o * 2 for o in range(4)])
    okA = bool((np.isin(abs_vals, img(O4, HD)) & ~np.isin(abs_vals, img(O4, HB))).all())
    wit = first(HD & ~wp)
    # O4' at I7 under (iii)-L (literal): h' in HB with O4'(h')=O4'(I7) and the (iii) shape
    I7 = {}
    iii4p = {}
    relaxed = {}
    HBm, Fm_ = HB, base
    for r_ in (0, 1):
        for c_ in (1, 2):
            i7 = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 1, ["a1", "w1"], (0, 0), r_, c_)
            idx = np.array([i7], dtype=np.int64)
            exist_any = bool(iii_exists(idx, U)[0])             # with O (added a2 allowed)
            # with O4' equality: h' must have same presence/kind of a1,a2 => a2 absent; reuse iii_exists restricted
            same = _iii_exists_o4p(idx, U)
            iii4p[(r_, c_)] = same
            relaxed[(r_, c_)] = exist_any
    res["K8.4"] = {"result": okA and not any(iii4p.values()) and all(relaxed.values()),
                   "least_witness_or_counterexample": H(wit),
                   "w1_absent_in_O4(H-D)\\O4(H-B)_for_every_o": okA,
                   "iii_L_at_I7_under_O4prime_holds_per_(r,c)": {"r=%d,c=%d" % k: v for k, v in iii4p.items()},
                   "iii_L_at_I7_with_base_O_(added_act_allowed)_holds_per_(r,c)": {"r=%d,c=%d" % k: v for k, v in relaxed.items()}}
    # K8.5 in U5
    U5 = classes("R0", "U5")
    o5 = [(r_ * 2 + 1) for r_ in range(2)]
    a5 = np.isin(o5, img(Oo, U5["HD"] & U5["RB"])); b5 = np.isin(o5, img(Oo, U5["HB"]))
    wit = first(U5["HD"] & U5["RB"] & (cc == 1))
    res["K8.5"] = {"result": bool((a5 & ~b5).all()), "least_witness_or_counterexample": H(wit),
                   "O(H-D&R_B)_U5": jvals(img(Oo, U5["HD"] & U5["RB"]), o_dec), "O(H-B)_U5": jvals(img(Oo, U5["HB"]), o_dec)}
    # K8.6 in U6
    U6 = classes("R0", "U6")
    fc6 = form_checks(U6)
    hv6 = U6["HD"] & ~U6["HB"] & (s0 == s1) & wp & (sig_tw == 1)
    res["K8.6"] = {"result": (not hv6.any()) and (not fc6["(ii)-S"]["holds"]) and (not fc6["(ii)-O"]["holds"]),
                   "least_witness_or_counterexample": fc6["(ii)-S"]["least_counterexample"],
                   "n_h_v_candidates_in_U6": int(hv6.sum()), "(ii)-S_holds": fc6["(ii)-S"]["holds"], "(ii)-O_holds": fc6["(ii)-O"]["holds"],
                   "|U6|": int(U6["F"].sum()), "|R_B(U6)|": int(U6["RB"].sum())}
    # K8.7 in U7
    U7 = classes("R0", "U7")
    rows = {}
    i7_fail_U7 = []; i7_hold_U = []; wit_ok = []
    for r_ in (0, 1):
        for c_ in (1, 2):
            i7 = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 1, ["a1", "w1"], (0, 0), r_, c_)
            idx = np.array([i7], dtype=np.int64)
            i7_hold_U.append(bool(iii_exists(idx, U)[0]))
            i7_fail_U7.append(not bool(iii_exists(idx, U7)[0]))
            hp = enc(("q", "p"), iss(0, "q", 1, "inf"), iss(1, "p", 1, 1), [], 1, ["a1", "a2", "w1"], (0, 0), r_, c_)
            wit_ok.append(bool(U7["F"][hp] and U7["HB"][hp] and OBS["O"][hp] == OBS["O"][i7] and Bconf[hp]))
    res["K8.7"] = {"result": all(i7_hold_U) and all(i7_fail_U7) and all(wit_ok),
                   "least_witness_or_counterexample": H(enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 1, ["a1", "w1"], (0, 0), 0, 1)),
                   "I7_in_scope_U7": bool(in_iii_scope(U7)[enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 1, ["a1", "w1"], (0, 0), 0, 1)]),
                   "iii_L_at_I7_holds_in_U_per_(r,c)": i7_hold_U, "iii_L_at_I7_fails_in_U7_per_(r,c)": i7_fail_U7,
                   "CM-A7(b)_h'_in_U7&H-B_with_same_O_and_w1_conforming_per_(r,c)": wit_ok,
                   "cm_a7b_history": H(enc(("q", "p"), iss(0, "q", 1, "inf"), iss(1, "p", 1, 1), [], 1, ["a1", "a2", "w1"], (0, 0), 0, 1))}

    # K9: observables
    def Qimg(qarr, m): return np.unique(Oo[m] * 100000 + qarr[m])
    Q1 = np.where(wp, sig_tw, 2).astype(np.int64)
    Q0 = s0.astype(np.int64)
    Qe = np.where(wp, 0, 2).astype(np.int64)
    Q2 = np.zeros(NRAW, dtype=np.int64)
    sl = [np.where(KIND[k] == 0, 0, np.where(KIND[k] == 1, 1 + PREP[k] * 2 + D[k], 5 + D[k])).astype(np.int64) for k in range(2)]
    Q2 = (((s0 * 2 + s1).astype(np.int64) * 25 + fh(0) * 5 + fh(1)) * 4 + capd) * 49 + sl[0] * 7 + sl[1]
    Q1b = np.where(wp, np.where(HB, sig_tw, 1), 2).astype(np.int64)
    Qm = np.where(wp, np.where(HD, sig_tw, 1), 2).astype(np.int64)
    Qs = np.where(wp, 1 + sig_tw + 2 * (~HD), 0).astype(np.int64)
    X = np.zeros(NRAW, dtype=bool)
    for k in range(2):
        X |= (KIND[k] == 1) & (F[k] == 1) & capI & ad_k_t[(k, 1)]
    Qp = ((s0 * 4 + s1).astype(np.int64) * 2 + X)
    AO = lambda v: np.array([o * 100000 + v for o in range(4)])
    # K9.1
    a = Qimg(Q1, HD); b = Qimg(Q1, HB)
    ok = bool(np.isin(AO(1), a).all() and not np.isin(AO(1), b).any() and np.isin(b, a).all())
    res["K9.1"] = {"result": ok, "least_witness_or_counterexample": H(first(HD & (Q1 == 1) & ~np.isin(Oo * 100000 + Q1, b)))}
    # K9.2
    allv = np.array([o * 100000 + v for o in range(4) for v in range(2)])
    a = Qimg(Q0, HD & RB); b = Qimg(Q0, HB)
    res["K9.2"] = {"result": bool(np.array_equal(a, allv) and np.array_equal(b, allv)), "least_witness_or_counterexample": None}
    # K9.3
    a = Qimg(Qe, HD & RB); b = Qimg(Qe, HB)
    inP = bool(np.isin(np.unique(Qe[HD | HB]), [0, 2]).all())
    res["K9.3"] = {"result": bool(np.array_equal(a, b) and inP), "least_witness_or_counterexample": None,
                   "Qe_values_on_H-D&R_B_union_H-B_(0=p,2=bottom)": [int(x) for x in np.unique(Qe[(HD & RB) | HB])]}
    # K9.4
    a = Qimg(Q2, HB); b = Qimg(Q2, HD)
    d1_ = np.setdiff1d(a, b)
    a = Qimg(Q2, HD & RB); b = Qimg(Q2, HB)
    d2_ = np.setdiff1d(a, b)
    def q2dec(x):
        q = x % 100000; return q // (49 * 4 * 25), (q // (49 * 4)) % 25
    w1c = first(HB & np.isin(Oo * 100000 + Q2, d1_) & (s0 == 0) & (s1 == 1) & (fh(0) == 0) & (fh(1) == 0))
    w2c = first((HD & RB) & np.isin(Oo * 100000 + Q2, d2_) & (s0 == 1) & (s1 == 1))
    res["K9.4"] = {"result": len(d1_) > 0 and len(d2_) > 0 and w1c is not None and w2c is not None,
                   "least_witness_or_counterexample": {"H-B\\H-D: sigma=(p,q), F^=empty": H(w1c), "H-D&R_B\\H-B: sigma=(q,q)": H(w2c)},
                   "n_O_Q2(H-B)\\O_Q2(H-D)": int(len(d1_)), "n_O_Q2(H-D&R_B)\\O_Q2(H-B)": int(len(d2_))}
    # K9.5
    HBv = np.unique(Q1b[HB])
    a = Qimg(Q1b, HD); b = Qimg(Q1b, HB)
    ok = bool(np.array_equal(HBv, [0]) and np.isin(AO(1), a).all() and not np.isin(AO(1), b).any())
    res["K9.5"] = {"result": ok, "least_witness_or_counterexample": None, "Q1b_values_on_H-B": [int(x) for x in HBv]}
    # K9.6
    c1_ = bool((Qm[HD & wp] == sig_tw[HD & wp]).all())
    c2_ = bool((Qm[HD] == 1).any())
    a = Qimg(Qm, HD); b = Qimg(Qm, HB)
    c3_ = bool(np.isin(AO(1), a).all() and np.isin(AO(1), b).all())
    res["K9.6"] = {"result": c1_ and c2_ and c3_, "least_witness_or_counterexample": H(first(HD & (Qm == 1))),
                   "Qm=sigma(t(w1))_on_H-D_with_w1": c1_, "some_H-D_has_Qm=q": c2_, "(o,q)_in_both_for_every_o": c3_}
    # K9.7: value 1 = {(l1,p)}, 2 = {(l1,q)}, 3 = {(l1,p),(l2,p)}, 4 = {(l1,q),(l2,q)}
    a = Qimg(Qs, HD); b = Qimg(Qs, HB)
    ok = bool(np.isin(AO(2), a).all() and not np.isin(AO(2), b).any() and np.isin(AO(3), b).all() and not np.isin(AO(3), a).any())
    res["K9.7"] = {"result": ok, "least_witness_or_counterexample": None,
                   "O_Q*(H-D)_values_(1:{l1p},2:{l1q},3:{l1p,l2p},4:{l1q,l2q})": [int(x) % 100000 for x in a if int(x) // 100000 == 0],
                   "O_Q*(H-B)_values_o0": [int(x) % 100000 for x in b if int(x) // 100000 == 0]}
    # K9.8: Qp = s0*4+s1*2+X ; (p,q,0) = 0*4+1*2+0 = 2
    a = Qimg(Qp, HB); b = Qimg(Qp, HD)
    ok1 = bool(np.isin(AO(2), a).all() and not np.isin(AO(2), b).any())
    bad = np.unique(Qp[HD & (s0 != s1) & ~X])
    res["K9.8"] = {"result": ok1 and len(bad) == 0, "least_witness_or_counterexample": H(first(HB & (Qp == 2))),
                   "n_H-D_histories_with_sigma_change_and_X=0": int((HD & (s0 != s1) & ~X).sum())}

    # K10.x ambiguity machinery
    def ambig_amb(M, obs):
        """returns per slot k the array of obs values that are ambiguous for slot k in class M"""
        r = []
        for k in range(2):
            rel = M & RA[k] & (KIND[k] > 0)
            a_ = np.unique(obs[rel & (CON[k] == 1)]); n_ = np.unique(obs[rel & (CON[k] == 0)])
            r.append(np.intersect1d(a_, n_))
        return r

    def ambig_all(M, scope, obs):
        amb = ambig_amb(M, obs)
        bad = np.zeros(NRAW, dtype=bool)
        for k in range(2):
            bad |= scope & RA[k] & (KIND[k] > 0) & ~np.isin(obs, amb[k])
        return bad

    def k101(Ucl, restrict=None):
        o = OBS["O"]
        rs = {}
        pairs = [("U", Ucl["F"], Ucl["F"]), ("H-B", Ucl["HB"], Ucl["HB"]), ("H-D", Ucl["HD"], Ucl["HD"] & Ucl["RB"]),
                 ("H-D&H-B", Ucl["HD"] & Ucl["HB"], Ucl["HD"] & Ucl["HB"])]
        for nm, M, sc in pairs:
            if restrict is not None: sc = sc & restrict
            bad = ambig_all(M, sc, o)
            c = first(bad)
            rs[nm] = {"holds": c is None, "first_counterexample": H(c), "scope_size": int(sc.sum())}
        return rs
    r101 = k101(U)
    res["K10.1"] = {"result": all(v["holds"] for v in r101.values()), "least_witness_or_counterexample": next((v["first_counterexample"] for v in r101.values() if not v["holds"]), None), "per_class": r101}
    # K10.2
    op = OBS["Oplus"]
    amb_any = {}
    for nm, M in (("U", base), ("H-B", HB), ("H-D", HD), ("H-D&H-B", DB)):
        amb = ambig_amb(M, op)
        amb_any[nm] = int(sum(len(x) for x in amb))
    kk = np.unique(key_nsf[DB] * 100000000 + op[DB])
    # K4 for O+: need h* with equal key and equal O+
    comb = key_nsf * 100000000 + op
    c = first(RB & ~np.isin(comb, kk))
    res["K10.2"] = {"result": all(v == 0 for v in amb_any.values()) and c is None, "least_witness_or_counterexample": H(c),
                    "n_ambiguous_(obs,slot)_per_class": amb_any, "K4_for_O+_holds": c is None}
    # K10.3
    ca = first(base & (((con1 == 1) & (d1 != rr)) | ((con2 == 1) & (d2 != rr))))
    okb = []
    wit_b = None
    for o in range(4):
        found = False
        for k in range(2):
            m = base & RA[k] & (KIND[k] > 0) & (Oo == o)
            ds = np.unique(D[k][m])
            if len(ds) > 1: found = True
        okb.append(found)
    res["K10.3"] = {"result": ca is None and all(okb), "least_witness_or_counterexample": H(ca),
                    "(a)_holds": ca is None, "(b)_per_o": okb}
    # K10.4
    viol = np.zeros(NRAW, dtype=bool)
    for k in range(2):
        viol |= (KIND[k] == 1) & (F[k] < 2) & (PREP[k] == 0) & (CON[k] == 1) & (KIND[1 - k] != 2) & (F[k] < rr)
    cx = first(HD & viol); wb = first(HB & viol)
    res["K10.4"] = {"result": cx is None and wb is not None, "least_witness_or_counterexample": {"counterexample_in_H-D": H(cx), "H-B_violator": H(wb)}}
    # K10.5
    o8 = OBS["O8"]
    amb = ambig_amb(base, o8)
    bad = np.zeros(NRAW, dtype=bool)
    for k in range(2):
        bad |= base & RA[k] & (KIND[k] > 0) & ~np.isin(o8, amb[k])
    c = first(bad)
    ex = enc(("p", "p"), iss(0, "p", 0, "inf"), 0, [], None, ["a1"], (0, 0), 1, 1)
    exbad = bool(bad[ex]) if base[ex] else None
    res["K10.5"] = {"result": c is not None, "least_witness_or_counterexample": H(c), "example_a1_iss_prep0_d0_r1_not_ambiguous": exbad,
                    "n_unambiguous_(h,slot)_histories": int(bad.sum())}
    # K10.6
    o3p = OBS["O3p"]
    amb = ambig_amb(base, o3p)
    n_amb = int(sum(len(x) for x in amb))
    res["K10.6"] = {"result": n_amb == 0, "least_witness_or_counterexample": None, "n_ambiguous_(obs,slot)": n_amb}
    # K10.7
    U9 = classes("R0", "U9")
    bad = ambig_all(U9["F"], U9["F"] & (cc == 1), OBS["O"])
    anyref = U9["F"] & (cc == 1) & ((RA[0] & (k1 > 0)) | (RA[1] & (k2 > 0)))
    allnot = bool(((bad & anyref) == anyref).all())
    # each referenced pair not ambiguous: check per history that every referenced slot is not ambiguous
    amb = ambig_amb(U9["F"], OBS["O"])
    n_amb_c2 = sum(int(np.isin(OBS["O"][U9["F"] & (cc == 1)], a_).any()) for a_ in amb)
    r1011 = k101(U9, restrict=(cc == 0))
    res["K10.7"] = {"result": n_amb_c2 == 0 and all(v["holds"] for v in r1011.values()) and allnot,
                    "least_witness_or_counterexample": None, "c2_histories_with_ambiguous_pair": n_amb_c2,
                    "c2_all_referenced_pairs_not_ambiguous": allnot, "K10.1_in_U9_for_c1": r1011}
    # K11.1
    hw = base & wp
    code_rm = code - (wd.astype(np.int64)) * W[5] - rw.astype(np.int64) * W[6]   # remove w1 and its Ref entry
    ok_rm = bool(base[code_rm[hw]].all())
    c_rm = first(hw & ~base[np.where(hw, code_rm, 0)])
    nw = base & ~wp
    ok_add = True; c_add = None
    for t in (0, 1):
        code_add = code + (t + 1) * W[5]
        m = nw & ~base[np.where(nw, code_add, 0)]
        if m.any(): ok_add = False; c_add = c_add or first(m)
    res["K11.1"] = {"result": ok_rm and ok_add, "least_witness_or_counterexample": H(c_rm if c_rm is not None else c_add),
                    "closed_under_removing_w1_with_Ref_entry": ok_rm, "closed_under_adding_w1_unreferenced_at_any_t": ok_add}
    # K11.2
    forced_gt = first(base & ((KIND[0] == 1) & (F[0] < 2) & RA[0] & (F[0] > rr) | (KIND[1] == 1) & (F[1] < 2) & RA[1] & (F[1] > rr)))
    forced_lt = first(base & ((KIND[0] == 1) & (F[0] < 2) & RA[0] & (F[0] < rr) | (KIND[1] == 1) & (F[1] < 2) & RA[1] & (F[1] < rr)))
    res["K11.2"] = {"result": forced_gt is not None and forced_lt is not None,
                    "least_witness_or_counterexample": {"referenced_forced_f>r": H(forced_gt), "referenced_forced_f<r": H(forced_lt)}}

    # K12.1
    def depends_by_key(obs, keyarr):
        """obs depends on the erased coordinate group iff some class of keyarr (over U) has >1 obs value"""
        kk_ = keyarr[base]; oo_ = obs[base]
        order = np.lexsort((oo_, kk_))
        ks, os_ = kk_[order], oo_[order]
        same_k = ks[1:] == ks[:-1]
        diff_o = os_[1:] != os_[:-1]
        return bool((same_k & diff_o).any())
    # keys erasing one group
    def nodig(i): return code - dig(i).astype(np.int64) * W[i]
    key_sigma = code - s0.astype(np.int64) * W[0] - s1.astype(np.int64) * W[1]
    key_f = code.copy()
    for k in range(2):
        key_f = key_f - np.where(KIND[k] == 1, F[k], 0).astype(np.int64) * W[2 + k]
    key_ref = code - refd.astype(np.int64) * W[6]
    key_con = code - con1.astype(np.int64) * W[7] - con2.astype(np.int64) * W[8]
    key_d = code.copy()
    for k in range(2):
        key_d = key_d - np.where(KIND[k] == 1, D[k] * 12, np.where(KIND[k] == 2, D[k], 0)).astype(np.int64) * W[2 + k]

    def add_dep(obs):
        dep = {"act": False, "work": False}
        # (+act): slot absent -> iss prep1, Ref/con unchanged
        for k in range(2):
            m = base & (KIND[k] == 0)
            for d_ in (0, 1):
                for v_ in SIG:
                    for f_ in FN:
                        idx = iss(d_, v_, 1, f_)
                        c2 = np.where(m, code + idx * W[2 + k], 0)
                        okm = m & base[c2]
                        if (obs[okm] != obs[c2[okm]]).any(): dep["act"] = True
        m = base & ~wp
        for t in (0, 1):
            c2 = np.where(m, code + (t + 1) * W[5], 0)
            okm = m & base[c2]
            if (obs[okm] != obs[c2[okm]]).any(): dep["work"] = True
        return dep
    k121 = {}
    for nm, ob in (("O", OBS["O"]), ("O1", OBS["O1"]), ("O2", OBS["O2"]), ("O3", OBS["O3"]), ("O3'", OBS["O3p"]), ("O4", OBS["O4"]), ("O4'", OBS["O4p"]), ("O8", OBS["O8"])):
        ad = add_dep(ob)
        k121[nm] = {"sigma-only": depends_by_key(ob, key_sigma), "f-only": depends_by_key(ob, key_f), "Ref-only": depends_by_key(ob, key_ref),
                    "con-only": depends_by_key(ob, key_con), "d-only": depends_by_key(ob, key_d),
                    "(+act)": ad["act"], "(+work)": ad["work"]}
    res["K12.1"] = {"result": True, "least_witness_or_counterexample": None, "depends_on_change_group": k121,
                    "note": "result is true because a report is produced; the content is the table"}
    # K12.2
    def closure(Fm):
        r = {}
        # A5
        viol = None
        okA5 = True
        for s0n in (0, 1):
            for s1n in (0, 1):
                for fa in (0, 1, 2):
                    for fb in (0, 1, 2):
                        c2 = code - s0.astype(np.int64) * W[0] - s1.astype(np.int64) * W[1] + s0n * W[0] + s1n * W[1]
                        c2 = c2 + np.where(KIND[0] == 1, fa - F[0], 0).astype(np.int64) * W[2] + np.where(KIND[1] == 1, fb - F[1], 0).astype(np.int64) * W[3]
                        m = Fm & base[c2] & ~Fm[c2]
                        if m.any():
                            okA5 = False
                            viol = viol or first(m)
        r["A5"] = okA5
        # A7 act / work / ref
        okact = True; vact = None
        for k in range(2):
            m0 = Fm & (KIND[k] == 0)
            for d_ in (0, 1):
                for v_ in SIG:
                    for f_ in FN:
                        c2 = np.where(m0, code + iss(d_, v_, 1, f_) * W[2 + k], 0)
                        m = m0 & base[c2] & ~Fm[c2]
                        if m.any(): okact = False; vact = vact or first(m)
        r["A7_act"] = okact
        okw = True; vw = None
        m0 = Fm & ~wp
        for t in (0, 1):
            c2 = np.where(m0, code + (t + 1) * W[5], 0)
            m = m0 & base[c2] & ~Fm[c2]
            if m.any(): okw = False; vw = vw or first(m)
        r["A7_work"] = okw
        okr = True; vr = None
        for bit, present in ((4, KIND[0] > 0), (2, KIND[1] > 0), (1, wp)):
            m0 = Fm & present & ((refd & bit) == 0)
            c2 = np.where(m0, code + bit * W[6], 0)
            m = m0 & base[c2] & ~Fm[c2]
            if m.any(): okr = False; vr = vr or first(m)
        r["A7_ref"] = okr
        # A9
        ok9 = True; v9 = None
        for a_ in (0, 1):
            for b_ in (0, 1):
                c2 = code - con1.astype(np.int64) * W[7] - con2.astype(np.int64) * W[8] + a_ * W[7] + b_ * W[8]
                m = Fm & base[c2] & ~Fm[c2]
                if m.any(): ok9 = False; v9 = v9 or first(m)
        for da in (0, 1):
            for db in (0, 1):
                c2 = code.copy()
                c2 = c2 + np.where(KIND[0] == 1, (da - D[0]) * 12, np.where(KIND[0] == 2, da - D[0], 0)).astype(np.int64) * W[2]
                c2 = c2 + np.where(KIND[1] == 1, (db - D[1]) * 12, np.where(KIND[1] == 2, db - D[1], 0)).astype(np.int64) * W[3]
                m = Fm & base[c2] & ~Fm[c2]
                if m.any(): ok9 = False; v9 = v9 or first(m)
        r["A9"] = ok9
        r["failed"] = [k for k in ("A5", "A7_act", "A7_work", "A7_ref", "A9") if not r[k]]
        r["witness_h_(an_h_in_frame_whose_admissible_change_leaves_the_frame)"] = {"A5": H(viol), "A7_act": H(vact), "A7_work": H(vw), "A7_ref": H(vr), "A9": H(v9)}
        return r
    k122 = {nm: closure(frames[nm]) for nm in ("U", "U5", "U7", "U9")}
    res["K12.2"] = {"result": True, "least_witness_or_counterexample": None, "closure": k122,
                    "failed_properties": {nm: v["failed"] for nm, v in k122.items()}}
    return res


def _iii_exists_o4p(idx, cl):
    """(iii)-L at histories idx under O4' (observation of presence and kind of a1,a2): h' must have the same O4'
    as h, i.e. the same presence/kind of a1,a2.  With a2 absent in h this excludes every added a2."""
    HBm, Fm = cl["HB"], cl["F"]
    res = np.zeros(len(idx), dtype=bool)
    cs = [(idx // W[i]) % RAD[i] for i in range(11)]
    S0, S1, A1, A2, CP, WD, RF, C1, C2, R, C = cs
    TW = WD - 1
    kk1, dd1, vv1, pp1, ff1 = slot_dec(A1.astype(np.int64))
    for sv in (0, 1):
        ns0 = np.where(TW == 0, sv, S0); ns1 = np.where(TW == 1, sv, S1)
        for fnew in (0, 1, 2):
            nA1 = np.where(kk1 == 1, A1 - ff1 + fnew, A1)
            c2 = (ns0 * W[0] + ns1 * W[1] + nA1 * W[2] + 0 * W[3] + CP * W[4] + WD * W[5] + RF * W[6] + C1 * W[7] + C2 * W[8] + R * W[9] + C * W[10])
            ok = HBm[c2] & Fm[c2] & (sv == 0)
            chg = ns0 != ns1
            ok = ok & (~chg | ((kk1 == 1) & (fnew == 1) & (vv1 == ns1)))
            res |= ok
    return res


# ================================================================== V1B
def decode_image(name, arr):
    if name == "O": return [o_dec(int(x)) for x in arr]
    if name == "O1": return [o_dec(int(x) // 4) + [sg_dec(int(x) % 4)] for x in arr]
    if name == "O3": return [o_dec(int(x) // 8) + [[n for b, n in ((4, "a1"), (2, "a2"), (1, "w1")) if (int(x) % 8) & b]] for x in arr]
    if name == "O4": return [o_dec(int(x) // 2) + [bool(int(x) % 2)] for x in arr]
    if name == "Cap": return [[n for b, n in ((1, "iss"), (2, "adp")) if int(x) & b] for x in arr]
    return None


def summarize(name, arr):
    if name == "Onsf":
        return {"size": int(len(arr)), "sha256_of_sorted_int64_keys": hashlib.sha256(np.asarray(arr, dtype="<i8").tobytes()).hexdigest()}
    return decode_image(name, arr)


NAMED = {}
NAMED["H_c"] = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], None, ["a1"], (0, 0), 0, 1)
NAMED["H_d"] = enc(("p", "p"), 0, iss(0, "q", 0, "inf"), ["adp"], 0, ["a2", "w1"], (0, 0), 0, 1)
NAMED["H_d2"] = enc(("p", "p"), iss(0, "p", 0, 0), iss(0, "q", 0, "inf"), ["adp"], 0, ["a2", "w1"], (0, 0), 0, 1)
NAMED["H_g"] = enc(("p", "q"), iss(0, "q", 0, 1), 0, ["iss", "adp"], 0, ["a1", "w1"], (0, 0), 0, 1)
NAMED["H_g'"] = enc(("p", "q"), iss(0, "q", 0, 1), 0, ["adp"], 0, ["a1", "w1"], (0, 0), 0, 1)
NAMED["I7"] = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 1, ["a1", "w1"], (0, 0), 0, 1)
NAMED["W7"] = enc(("q", "p"), iss(0, "q", 1, "inf"), iss(1, "p", 1, 1), [], 1, ["a1", "a2", "w1"], (0, 0), 0, 1)
NAMED["H_i"] = enc(("p", "p"), iss(0, "p", 1, "inf"), 0, [], 0, ["w1"], (0, 0), 0, 1)
NAMED["H_empty"] = enc(("p", "p"), 0, 0, [], None, [], (0, 0), 0, 1)
NAMED["H_j"] = enc(("p", "p"), 0, iss(0, "p", 0, 0), ["iss", "adp"], 0, ["a2", "w1"], (0, 0), 0, 1)
NAMED["H_g+"] = enc(("p", "q"), iss(0, "q", 1, "inf"), 0, [], 0, ["a1", "w1"], (0, 0), 0, 1)
NAMED["H_U5h"] = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 0, ["a1", "w1"], (0, 0), 0, 2)
NAMED["H_U5i"] = NAMED["H_U5h"]
NAMED["H_U5i_c1"] = enc(("q", "q"), iss(0, "q", 1, "inf"), 0, [], 0, ["a1", "w1"], (0, 0), 0, 1)


def named_fields(reading, fr, cl):
    out = {}
    HD, HB, RB, Fm, HDp = cl["HD"], cl["HB"], cl["RB"], cl["F"], cl["HDp"]
    DB = HD & HB
    ksDB = np.unique(key_nsf[DB]); ksDpB = np.unique(key_nsfc[HDp & HB])
    ohc = np.unique(Oo[DB & wp & (sig_tw == 0)])
    scope = in_iii_scope(cl)
    for nm, c in NAMED.items():
        f = {"in_frame": bool(Fm[c]),
             "member": {"R_B": bool(RB[c]), "H-D": bool(HD[c]), "H-B": bool(HB[c])},
             "iS": bool(key_nsf[c] in ksDB),
             "K4b": bool(key_nsfc[c] in ksDpB),
             "iiO_c": bool(Oo[c] in ohc),
             "O": o_dec(int(Oo[c]))}
        ins = bool(scope[c]) if Fm[c] else False
        f["iiiL_in_scope"] = ins
        if Fm[c] and k2[c] == 0 and wp[c]:
            # evaluate the existence of h' even when outside the scope, keep separate
            ex = bool(iii_exists(np.array([c], dtype=np.int64), cl)[0])
        else:
            ex = None
        f["iiiL"] = ex if ins else None
        f["iiiL_h'_exists_ignoring_scope"] = ex
        if nm == "H_d2":
            f["exists(H-D+, ~sigma,f H_d2)"] = bool(key_nsf[c] in np.unique(key_nsf[HDp]))
        out[nm] = f
    return out


def run_V1B():
    res = {}
    for rd in READINGS:
        res[rd] = {}
        for fr in ("U", "U5"):
            cl = classes(rd, fr)
            ent = form_checks(cl)
            HD, HB, RB = cl["HD"], cl["HB"], cl["RB"]
            DB = HD & HB
            ent["named"] = named_fields(rd, fr, cl)
            ent["sizes"] = {"frame": int(cl["F"].sum()), "H-D": int(HD.sum()), "H-B": int(HB.sum()), "R_B": int(RB.sum()),
                            "H-D&R_B": int((HD & RB).sum()), "H-D&H-B": int(DB.sum())}
            imgs = {}
            for on in ("O", "O1", "O3", "O4", "Onsf", "Cap"):
                ob = OBS[on]
                A = {"R_B": RB, "H-D": HD, "H-B": HB, "H-D&R_B": HD & RB, "H-D&H-B": DB}
                d = {"image": {k: summarize(on, img(ob, m)) for k, m in A.items()}}
                dd = {"diff(H-D&R_B,H-B)": (HD & RB, HB), "diff(R_B,H-B)": (RB, HB), "diff(R_B,H-D&H-B)": (RB, DB)}
                d["diff"] = {k: summarize(on, np.setdiff1d(img(ob, a), img(ob, b))) for k, (a, b) in dd.items()}
                imgs[on if on != "Onsf" else "O_notsigmaf"] = d
            ent["images"] = imgs
            res[rd][fr] = ent
        print("reading", rd, "done", round(time.time() - T0, 1), flush=True)
    return res


if __name__ == "__main__":
    r5 = run_R5()
    print("R5 done", round(time.time() - T0, 1), flush=True)
    out5 = {"n_frame_histories": NU, "frame_sizes": {k: int(v.sum()) for k, v in frames.items()}, "checks": r5}
    json.dump(out5, open(OUT + "/R5-RESULTS.json", "w"), indent=1, default=_js)
    v1 = run_V1B()
    json.dump(v1, open(OUT + "/V1B-RESULTS.json", "w"), indent=1, default=_js)
    print("total seconds", round(time.time() - T0, 1))
