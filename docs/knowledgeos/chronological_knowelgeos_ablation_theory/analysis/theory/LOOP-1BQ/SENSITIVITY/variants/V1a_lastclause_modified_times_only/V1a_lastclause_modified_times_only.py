#!/usr/bin/env python3
# VARIANT V1a_lastclause_modified_times_only
# AMB-1 alt (a): the (iii)-L last clause constrains only change points of sigma' at a time where sigma' was modified (sigma'(t) != sigma(t)); i.e. 'the new value' = the value newly set by h'.
# Exact diff from the base (LOOP-1BQ/CLAUDE-SIDE/slice_enum.py):
# --- base/slice_enum.py (Opus, sealed)
# +++ V1a_lastclause_modified_times_only.py
# @@ -184,7 +184,7 @@
#              for A2 in a2opts:
#                  if not f1_ok(sig[0], sig[1], A1, A2):
#                      continue
# -                if sig[0] != sig[1]:
# +                if sig[0] != sig[1] and sig[1] != s1:
#                      by_h_act = a1_iss and A1[4] == 1 and A1[2] == sig[1]
#                      if not by_h_act and not (A2 is not None and A2[4] == 1):
#                          continue
#
"""Independent exhaustive enumeration for SLICE-SPEC (frames U and U_P2, 12 readings).

History tuple: (s0, s1, a1, a2, cap, w1, ref, con1, con2, r, c)
  values      : ints 0,1,2 = p,q,r
  slot        : None | ('iss', d, v, prep, f) | ('adp', d)   f: 0, 1, INF(=2)
  cap         : 0 = {}, 1 = {(x,iss)}, 2 = {(x,adp)}, 3 = both   (R-1 order)
  w1          : None | t
  ref         : 4*[a1] + 2*[a2] + 1*[w1]                          (R-1 order)
  con1, con2  : 0 = none, 1 = rho1
  r, c        : 0/1, 0 = c1, 1 = c2
"""
import json
import sys
import time

INF = 2
T = (0, 1)
VAL_NAMES = ("p", "q", "r")
READINGS = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h", "R-i", "R-i'", "R-j"]


def rank(h):
    """Sort key implementing enumeration order R-1."""
    s0, s1, a1, a2, cap, w1, ref, k1, k2, r, c = h

    def sr(a):
        if a is None:
            return (0,)
        if a[0] == "iss":
            return (1,) + tuple(a[1:])
        return (2, a[1])

    return (s0, s1, sr(a1), sr(a2), cap, -1 if w1 is None else w1, ref, k1, k2, r, c)


def slot_structures(V):
    """Slot contents without f (f is enumerated in the inner loop)."""
    out = [None]
    for d in T:
        for v in V:
            for prep in (0, 1):
                out.append(("iss", d, v, prep))
    for d in T:
        out.append(("adp", d))
    return out


def adopted(a, other, t, capA):
    """Def 1.10 Adopted(a, t) for iss slot a; the only possible adp act on a is the other slot."""
    if a[3] == 0 and capA and a[1] <= t:
        return True
    if other is not None and other[0] == "adp" and capA and other[1] <= t:
        return True
    return False


def classes(h, P):
    """Return {reading: (in R_B, in H-D, in H-B)}."""
    s0, s1, a1, a2, cap, w1, ref, k1, k2, r, c = h
    sig = (s0, s1)
    capI = cap in (1, 3)
    capA = cap in (2, 3)
    chg = s0 != s1  # Chg(h) is {(1, tau)} or empty
    iss_f1 = any(a is not None and a[0] == "iss" and a[4] == 1 and a[2] == s1 for a in (a1, a2))

    dchg = (not chg) or (iss_f1 and capI)
    dchg_e = (not chg) or iss_f1
    dchg_j = dchg and chg

    dst = True
    dst_extra = True
    for a, o in ((a1, a2), (a2, a1)):
        if a is not None and a[0] == "iss":
            if a[4] != INF and not adopted(a, o, a[4], capA):
                dst = False
            # Adopted is monotone in t, so "for some t in T" == at t = max T = 1
            if adopted(a, o, 1, capA) and a[4] == INF:
                dst_extra = False
    dstp = dst and dst_extra

    drec = (ref & 6) != 0  # Ref restricted to present items
    recW = (ref & 1) != 0
    brec = drec and recW

    bchg = (not chg) or iss_f1 or (w1 == 0)
    bchg_g = bchg and chg and (w1 == 0)
    bchg_gp = bchg_g and iss_f1
    bconf = w1 is None or sig[w1] in P
    bconf_b = w1 is not None and sig[w1] in P

    all_ref = ((a1 is None or ref & 4) and (a2 is None or ref & 2) and (w1 is None or ref & 1))
    brec_h = brec and bool(all_ref)
    brec_i = a1 is not None or a2 is not None

    HD0 = dchg and dst and drec
    HB0 = bchg and bconf and brec
    return {
        "R0": (brec, HD0, HB0),
        "R-b": (brec, HD0, bchg and bconf_b and brec),
        "R-c": (drec, HD0, bchg and bconf_b and drec),
        "R-d": (brec, dchg and dstp and drec, HB0),
        "R-e": (brec, dchg_e and dst and drec, HB0),
        "R-f": (drec, HD0, bchg and bconf and drec),
        "R-g": (brec, HD0, bchg_g and bconf and brec),
        "R-g+": (brec, HD0, bchg_gp and bconf and brec),
        "R-h": (brec_h, HD0, bchg and bconf and brec_h),
        "R-i": (brec_i, HD0, bchg and bconf and brec_i),
        "R-i'": (True, HD0, bchg and bconf),
        "R-j": (brec, dchg_j and dst and drec, HB0),
    }


def f1_ok(s0, s1, a1, a2):
    sig = (s0, s1)
    for a in (a1, a2):
        if a is not None and a[0] == "iss" and a[4] != INF and sig[a[4]] != a[2]:
            return False
    return True


def keys(V):
    """Every coordinate except sigma and f, restricted by typing and F2 (F2 involves neither sigma nor f)."""
    S = slot_structures(V)
    for a1 in S:
        for a2 in S:
            if a1 is not None and a1[0] == "adp" and (a2 is None or a2[0] != "iss"):
                continue
            if a2 is not None and a2[0] == "adp" and (a1 is None or a1[0] != "iss"):
                continue
            for cap in range(4):
                for w1 in (None, 0, 1):
                    allowed = (4 if a1 is not None else 0) | (2 if a2 is not None else 0) | (1 if w1 is not None else 0)
                    for ref in range(8):
                        if ref & ~allowed:
                            continue
                        for k1 in ((0, 1) if a1 is not None else (0,)):
                            for k2 in ((0, 1) if a2 is not None else (0,)):
                                for r in T:
                                    # F2
                                    if k1 and not (ref & 4 and a1[1] == r):
                                        continue
                                    if k2 and not (ref & 2 and a2[1] == r):
                                        continue
                                    for c in (0, 1):
                                        yield (a1, a2, cap, w1, ref, k1, k2, r, c)


def with_f(a, f):
    if a is None or a[0] != "iss":
        return a
    return a + (f,)


def variants(key, V):
    """All frame histories with this key (sigma and f varied, F1 enforced)."""
    a1, a2, cap, w1, ref, k1, k2, r, c = key
    f1s = (0, 1, INF) if (a1 is not None and a1[0] == "iss") else (None,)
    f2s = (0, 1, INF) if (a2 is not None and a2[0] == "iss") else (None,)
    for s0 in V:
        for s1 in V:
            for f1 in f1s:
                A1 = with_f(a1, f1)
                for f2 in f2s:
                    A2 = with_f(a2, f2)
                    if f1_ok(s0, s1, A1, A2):
                        yield (s0, s1, A1, A2, cap, w1, ref, k1, k2, r, c)


def iii_witness(h, reading, V, P):
    """K7 literal constraints: does some h' exist?"""
    s0, s1, a1, a2, cap, w1, ref, k1, k2, r, c = h
    assert a2 is None and w1 is not None
    a1_iss = a1 is not None and a1[0] == "iss"
    f1s = (0, 1, INF) if a1_iss else (None,)
    a2opts = [None] + [("iss", d, v, 1, f) for d in T for v in V for f in (0, 1, INF)]
    for nv in V:
        sig = [s0, s1]
        sig[w1] = nv
        if nv not in P:  # w1 conforms in h'
            continue
        for f1 in f1s:
            A1 = (a1[:4] + (f1,)) if a1_iss else a1
            for A2 in a2opts:
                if not f1_ok(sig[0], sig[1], A1, A2):
                    continue
                if sig[0] != sig[1] and sig[1] != s1:
                    by_h_act = a1_iss and A1[4] == 1 and A1[2] == sig[1]
                    if not by_h_act and not (A2 is not None and A2[4] == 1):
                        continue
                hp = (sig[0], sig[1], A1, A2, cap, w1, ref, k1, 0, r, c)
                if classes(hp, P)[reading][2]:
                    return True
    return False


def to_json(h):
    s0, s1, a1, a2, cap, w1, ref, k1, k2, r, c = h

    def slot(a):
        if a is None:
            return "absent"
        if a[0] == "iss":
            return {"kind": "iss", "d": a[1], "v": VAL_NAMES[a[2]], "prep": a[3],
                    "f": "inf" if a[4] == INF else a[4]}
        return {"kind": "adp", "d": a[1]}

    capl = []
    if cap in (1, 3):
        capl.append("(x,iss)")
    if cap in (2, 3):
        capl.append("(x,adp)")
    refl = []
    if ref & 4:
        refl.append("(rho1,a1)")
    if ref & 2:
        refl.append("(rho1,a2)")
    if ref & 1:
        refl.append("(rho1,w1)")
    return {"sigma": [VAL_NAMES[s0], VAL_NAMES[s1]], "a1": slot(a1), "a2": slot(a2), "Cap": capl,
            "w1": "absent" if w1 is None else {"t": w1}, "Ref": refl,
            "con": {"a1": "rho1" if k1 else "none", "a2": "rho1" if k2 else "none"},
            "r": r, "c": "c2" if c else "c1"}


class Check:
    def __init__(self):
        self.scope = 0
        self.n_cex = 0
        self.least = None

    def add(self, h, ok):
        self.scope += 1
        if not ok:
            self.n_cex += 1
            k = rank(h)
            if self.least is None or k < self.least[0]:
                self.least = (k, h)

    def out(self):
        return {"holds": self.n_cex == 0, "scope_size": self.scope, "n_counterexamples": self.n_cex,
                "least_counterexample": None if self.least is None else to_json(self.least[1])}


def run_frame(V, P):
    n = 0
    st = {}
    for R in READINGS:
        st[R] = {"sizes": dict.fromkeys(["R_B", "H-D", "H-B", "H-D&H-B", "H-D&R_B"], 0),
                 "i": Check(), "ii": Check(), "iii": Check(),
                 "O_HDRB": set(), "O_HB": set(), "O_HDHB": set(), "O_RB": set(),
                 "O_iiscope": set(), "O_hv": set(), "O_hc": set()}
    for key in keys(V):
        hs = list(variants(key, V))
        if not hs:
            continue
        n += len(hs)
        cls = [classes(h, P) for h in hs]
        w1 = key[3]
        ref = key[4]
        o = (key[7], key[8])
        for R in READINGS:
            s = st[R]
            sz = s["sizes"]
            ex_i = ex_v = ex_c = False
            for h, cl in zip(hs, cls):
                rb, hd, hb = cl[R]
                if hd and hb:
                    ex_i = True
                    if w1 is not None and h[w1] in P:
                        ex_c = True
                if hd and not hb and h[0] == h[1] and w1 is not None and h[w1] not in P:
                    ex_v = True
            if ex_v:
                s["O_hv"].add(o)
            if ex_c:
                s["O_hc"].add(o)
            for h, cl in zip(hs, cls):
                rb, hd, hb = cl[R]
                if rb:
                    sz["R_B"] += 1
                    s["O_RB"].add(o)
                    s["i"].add(h, ex_i)
                    if ref & 1:
                        s["ii"].add(h, ex_v and ex_c)
                        s["O_iiscope"].add(o)
                if hd:
                    sz["H-D"] += 1
                if hb:
                    sz["H-B"] += 1
                    s["O_HB"].add(o)
                if hd and hb:
                    sz["H-D&H-B"] += 1
                    s["O_HDHB"].add(o)
                if hd and rb:
                    sz["H-D&R_B"] += 1
                    s["O_HDRB"].add(o)
                    if h[3] is None and w1 is not None and h[w1] not in P:
                        s["iii"].add(h, iii_witness(h, R, V, P))
    res = {"n_frame_histories": n, "readings": {}}
    for R in READINGS:
        s = st[R]
        iO = s["O_HDRB"] == s["O_HB"] == s["O_HDHB"] == s["O_RB"]
        iiO = s["O_iiscope"] <= (s["O_hv"] & s["O_hc"])
        res["readings"][R] = {"sizes": s["sizes"], "(i)-S": s["i"].out(), "(ii)-S": s["ii"].out(),
                              "(iii)-L": s["iii"].out(), "(i)-O": {"holds": iO}, "(ii)-O": {"holds": iiO}}
    return res


def main():
    t0 = time.time()
    out = {}
    out["U"] = run_frame((0, 1), {0})
    t1 = time.time()
    print(f"U: {out['U']['n_frame_histories']} histories, {t1 - t0:.1f}s", file=sys.stderr)
    out["U_P2"] = run_frame((0, 1, 2), {0, 1})
    t2 = time.time()
    print(f"U_P2: {out['U_P2']['n_frame_histories']} histories, {t2 - t1:.1f}s", file=sys.stderr)
    with open("SLICE-RESULTS.json", "w") as fh:
        json.dump(out, fh, indent=1)
    print(f"total {t2 - t0:.1f}s", file=sys.stderr)


if __name__ == "__main__":
    main()
