#!/usr/bin/env python3
"""Independent enumeration for SLICE-SPEC (frames U, U_P2; 12 readings).
Usage: python3 slice_enum.py [outdir]   -> writes SLICE-RESULTS.json in outdir."""
import json, sys, time

READINGS = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h", "R-i", "R-i'", "R-j"]
INF = 2
VN = ["p", "q", "r"]


def slots(nv):
    out = [None]
    for d in (0, 1):
        for v in range(nv):
            for prep in (0, 1):
                for f in (0, 1, INF):
                    out.append(("iss", d, v, prep, f))
    for d in (0, 1):
        out.append(("adp", d))
    return out


def gen(nv):
    """Yield F1/F2-valid histories in R-1 order.
    h = (s0, s1, a1, a2, cap, w1, ref, con1, con2, r, c); cap/ref are the R-1 integer codes."""
    S = slots(nv)
    for s0 in range(nv):
        for s1 in range(nv):
            sg = (s0, s1)
            for a1 in S:
                if a1 is not None and a1[0] == "iss" and a1[4] != INF and sg[a1[4]] != a1[2]:
                    continue
                for a2 in S:
                    if a2 is not None and a2[0] == "iss" and a2[4] != INF and sg[a2[4]] != a2[2]:
                        continue
                    if a1 is not None and a1[0] == "adp" and not (a2 is not None and a2[0] == "iss"):
                        continue
                    if a2 is not None and a2[0] == "adp" and not (a1 is not None and a1[0] == "iss"):
                        continue
                    for cap in range(4):
                        for w1 in (None, 0, 1):
                            for ref in range(8):
                                if (ref & 4 and a1 is None) or (ref & 2 and a2 is None) or (ref & 1 and w1 is None):
                                    continue
                                for c1 in ((0, 1) if a1 is not None else (0,)):
                                    for c2 in ((0, 1) if a2 is not None else (0,)):
                                        for r in (0, 1):
                                            if c1 and (not ref & 4 or a1[1] != r):
                                                continue
                                            if c2 and (not ref & 2 or a2[1] != r):
                                                continue
                                            for c in (0, 1):
                                                yield (s0, s1, a1, a2, cap, w1, ref, c1, c2, r, c)


def make_classes(P):
    Pset = frozenset(P)

    def classes(h):
        s0, s1, a1, a2, cap, w1, ref, c1, c2, r, c = h
        sg = (s0, s1)
        capiss = bool(cap & 1)
        capadp = bool(cap & 2)
        sl = (a1, a2)
        change = s0 != s1
        iss = [s for s in sl if s is not None and s[0] == "iss"]

        def adopted(i, t):
            s = sl[i]
            o = sl[1 - i]
            if s[3] == 0 and capadp and s[1] <= t:
                return True
            if o is not None and o[0] == "adp" and capadp and o[1] <= t:
                return True
            return False

        dst = True
        dstp_extra = True
        for i in (0, 1):
            s = sl[i]
            if s is not None and s[0] == "iss":
                if s[4] != INF and not adopted(i, s[4]):
                    dst = False
                if (adopted(i, 0) or adopted(i, 1)) and s[4] == INF:
                    dstp_extra = False
        dstp = dst and dstp_extra
        act1 = any(s[4] == 1 and s[2] == s1 for s in iss)
        dchg = (not change) or (act1 and capiss)
        dchge = (not change) or act1
        w1p = w1 is not None
        viol = w1p and sg[w1] not in Pset
        conf = w1p and not viol
        bchg = (not change) or act1 or (w1p and w1 == 0)
        drec = bool(ref & 6)
        brecw = bool(ref & 1)
        allref = (a1 is None or bool(ref & 4)) and (a2 is None or bool(ref & 2)) and (w1 is None or bool(ref & 1))
        E = (a1 is not None) or (a2 is not None)

        def mk(dchg_=None, dst_=None, bchg_=None, bconf_=None, brec_=None):
            D = (dchg if dchg_ is None else dchg_) and (dst if dst_ is None else dst_) and drec
            brec = (drec and brecw) if brec_ is None else brec_
            B = (bchg if bchg_ is None else bchg_) and (not viol if bconf_ is None else bconf_) and brec
            return (D, B, brec)

        out = {}
        out["R0"] = mk()
        out["R-b"] = mk(bconf_=conf)
        out["R-c"] = mk(bconf_=conf, brec_=drec)
        out["R-d"] = mk(dst_=dstp)
        out["R-e"] = mk(dchg_=dchge)
        out["R-f"] = mk(brec_=drec)
        bg = bchg and change and w1p and w1 == 0
        out["R-g"] = mk(bchg_=bg)
        out["R-g+"] = mk(bchg_=bg and act1)
        out["R-h"] = mk(brec_=drec and brecw and allref)
        out["R-i"] = mk(brec_=E)
        out["R-i'"] = mk(brec_=True)
        out["R-j"] = mk(dchg_=dchg and change)
        return out

    return classes, Pset


def key_sf(h):
    s0, s1, a1, a2, cap, w1, ref, c1, c2, r, c = h
    k = lambda s: None if s is None else (s[:4] if s[0] == "iss" else s)
    return (k(a1), k(a2), cap, w1, ref, c1, c2, r, c)


def jslot(s):
    if s is None:
        return "absent"
    if s[0] == "adp":
        return {"kind": "adp", "d": s[1]}
    return {"kind": "iss", "d": s[1], "v": VN[s[2]], "prep": s[3], "f": ("inf" if s[4] == INF else s[4])}


def jhist(h):
    s0, s1, a1, a2, cap, w1, ref, c1, c2, r, c = h
    return {"sigma": [VN[s0], VN[s1]], "a1": jslot(a1), "a2": jslot(a2),
            "Cap": (["(x,iss)"] if cap & 1 else []) + (["(x,adp)"] if cap & 2 else []),
            "w1": "absent" if w1 is None else {"t": w1},
            "Ref": (["(rho1,a1)"] if ref & 4 else []) + (["(rho1,a2)"] if ref & 2 else []) + (["(rho1,w1)"] if ref & 1 else []),
            "con": {"a1": "rho1" if c1 else "none", "a2": "rho1" if c2 else "none"},
            "r": r, "c": "c1" if c == 0 else "c2"}


def iii_exists(h, R, classes, nv, Pset):
    """(iii)-L witness search: K7 literal constraints."""
    s0, s1, a1, a2, cap, w1, ref, c1, c2, r, c = h
    sg = [s0, s1]
    f_opts = (0, 1, INF) if (a1 is not None and a1[0] == "iss") else (None,)
    a2opts = [None] + [("iss", d, v, 1, f) for d in (0, 1) for v in range(nv) for f in (0, 1, INF)]
    for vn in sorted(Pset):                 # w1 must conform in h'
        sp = sg[:]
        sp[w1] = vn                         # sigma changes only at t(w1)
        for fa in f_opts:                   # f free
            na1 = a1 if fa is None else (a1[0], a1[1], a1[2], a1[3], fa)
            if na1 is not None and na1[0] == "iss" and na1[4] != INF and sp[na1[4]] != na1[2]:
                continue                    # F1
            for na2 in a2opts:              # a2 optionally added
                if na2 is not None and na2[4] != INF and sp[na2[4]] != na2[2]:
                    continue                # F1
                if sp[0] != sp[1]:          # change point at t=1
                    ok = (na1 is not None and na1[0] == "iss" and na1[4] == 1 and na1[2] == sp[1]) or \
                         (na2 is not None and na2[4] == 1)
                    if not ok:
                        continue
                h2 = (sp[0], sp[1], na1, na2, cap, w1, ref, c1, 0, r, c)
                if classes(h2)[R][1]:
                    return True
    return False


def run_frame(nv, P):
    classes, Pset = make_classes(P)
    keyid = {}
    n = 0
    sizes = {R: [0, 0, 0, 0, 0] for R in READINGS}   # R_B, H-D, H-B, H-D&H-B, H-D&R_B
    DBkeys = {R: set() for R in READINGS}
    iikey = {R: {} for R in READINGS}
    iiO = {R: {} for R in READINGS}
    Oset = {R: [set(), set(), set(), set()] for R in READINGS}  # O(HD&RB), O(HB), O(HD&HB), O(RB)
    for h in gen(nv):
        n += 1
        cl = classes(h)
        w1 = h[5]
        sg = (h[0], h[1])
        o = (h[9], h[10])
        k = None
        for R in READINGS:
            D, B, Rb = cl[R]
            z = sizes[R]
            if Rb:
                z[0] += 1
                Oset[R][3].add(o)
            if D:
                z[1] += 1
            if B:
                z[2] += 1
                Oset[R][1].add(o)
            if D and B:
                z[3] += 1
                Oset[R][2].add(o)
                if k is None:
                    k = keyid.setdefault(key_sf(h), len(keyid))
                DBkeys[R].add(k)
            if D and Rb:
                z[4] += 1
                Oset[R][0].add(o)
            if w1 is not None and D:
                viol = sg[w1] not in Pset
                bit = 0
                if (not B) and sg[0] == sg[1] and viol:
                    bit = 1
                elif B and not viol:
                    bit = 2
                if bit:
                    if k is None:
                        k = keyid.setdefault(key_sf(h), len(keyid))
                    iikey[R][k] = iikey[R].get(k, 0) | bit
                    iiO[R][o] = iiO[R].get(o, 0) | bit
    cnt = {R: {"i": [0, 0, None], "ii": [0, 0, None], "iii": [0, 0, None]} for R in READINGS}
    iiO_holds = {R: True for R in READINGS}
    for h in gen(nv):
        cl = classes(h)
        w1 = h[5]
        sg = (h[0], h[1])
        o = (h[9], h[10])
        k = None
        for R in READINGS:
            D, B, Rb = cl[R]
            if not Rb:
                continue
            if k is None:
                k = keyid.get(key_sf(h), -1)
            c = cnt[R]
            c["i"][0] += 1
            if k not in DBkeys[R]:
                c["i"][1] += 1
                if c["i"][2] is None:
                    c["i"][2] = h
            if h[6] & 1:
                c["ii"][0] += 1
                if iikey[R].get(k, 0) != 3:
                    c["ii"][1] += 1
                    if c["ii"][2] is None:
                        c["ii"][2] = h
                if iiO[R].get(o, 0) != 3:
                    iiO_holds[R] = False
            if D and h[3] is None and w1 is not None and sg[w1] not in Pset:
                c["iii"][0] += 1
                if not iii_exists(h, R, classes, nv, Pset):
                    c["iii"][1] += 1
                    if c["iii"][2] is None:
                        c["iii"][2] = h
    out = {"n_frame_histories": n, "readings": {}}
    for R in READINGS:
        z = sizes[R]
        c = cnt[R]
        Os = Oset[R]

        def form(x):
            return {"holds": x[1] == 0, "scope_size": x[0], "n_counterexamples": x[1],
                    "least_counterexample": None if x[2] is None else jhist(x[2])}
        out["readings"][R] = {
            "sizes": {"R_B": z[0], "H-D": z[1], "H-B": z[2], "H-D&H-B": z[3], "H-D&R_B": z[4]},
            "(i)-S": form(c["i"]), "(ii)-S": form(c["ii"]), "(iii)-L": form(c["iii"]),
            "(i)-O": {"holds": Os[0] == Os[1] == Os[2] == Os[3]},
            "(ii)-O": {"holds": iiO_holds[R]}}
    return out


if __name__ == "__main__":
    t0 = time.time()
    outdir = sys.argv[1] if len(sys.argv) > 1 else "."
    result = {}
    for name, nv, P in (("U", 2, [0]), ("U_P2", 3, [0, 1])):
        result[name] = run_frame(nv, P)
        print(name, result[name]["n_frame_histories"], round(time.time() - t0, 1), "s", flush=True)
    json.dump(result, open(outdir + "/SLICE-RESULTS.json", "w"), indent=1)
    print("runtime", round(time.time() - t0, 1))
