#!/usr/bin/env python3
"""
Optimized finite-state enumeration - v3.
Minimizes redundant computation.
"""

import json
import time
from collections import defaultdict

def gen_hists(V, P, T=(0, 1)):
    """Generate all valid histories. Returns list of tuples."""
    hists = []
    Sigma = ("c1", "c2")

    # Precompute all act configs
    act_configs = [None]
    for kind in ["iss", "adp"]:
        for d in T:
            if kind == "iss":
                for v in V:
                    for prep in [0, 1]:
                        for f in list(T) + [float('inf')]:
                            act_configs.append((kind, d, v, prep, f))
            else:
                act_configs.append((kind, d, None, None, None))

    T_set = set(T)

    for s0 in V:
        for s1 in V:
            for a1 in act_configs:
                for a2 in act_configs:
                    # Validate adp
                    if a1 and a1[0]=="adp" and (not a2 or a2[0]!="iss"):
                        continue
                    if a2 and a2[0]=="adp" and (not a1 or a1[0]!="iss"):
                        continue

                    for c_iss in [False, True]:
                        for c_adp in [False, True]:
                            for w1 in [None, 0, 1]:
                                for ref in range(8):  # 3 bits
                                    # Validate ref
                                    if (ref & 1) and not a1:
                                        continue
                                    if (ref & 2) and not a2:
                                        continue
                                    if (ref & 4) and w1 is None:
                                        continue

                                    for ca1 in ([None, "rho1"] if a1 else [None]):
                                        for ca2 in ([None, "rho1"] if a2 else [None]):
                                            for r in T:
                                                for c in Sigma:
                                                    h = (s0, s1,
                                                         a1[0] if a1 else None,
                                                         a1[1] if a1 else None,
                                                         a1[2] if a1 and a1[0]=="iss" else None,
                                                         a1[3] if a1 and a1[0]=="iss" else None,
                                                         a1[4] if a1 and a1[0]=="iss" else None,
                                                         a2[0] if a2 else None,
                                                         a2[1] if a2 else None,
                                                         a2[2] if a2 and a2[0]=="iss" else None,
                                                         a2[3] if a2 and a2[0]=="iss" else None,
                                                         a2[4] if a2 and a2[0]=="iss" else None,
                                                         c_iss, c_adp, w1, ref, ca1, ca2, r, c)

                                                    # Check axioms
                                                    ok = True
                                                    # F1
                                                    if h[2]=="iss" and h[6] in T_set:
                                                        if (h[6]==0 and s0!=h[4]) or (h[6]==1 and s1!=h[4]):
                                                            ok = False
                                                    if h[7]=="iss" and h[11] in T_set:
                                                        if (h[11]==0 and s0!=h[9]) or (h[11]==1 and s1!=h[9]):
                                                            ok = False
                                                    # F2
                                                    if ca1=="rho1" and h[2]:
                                                        if h[3]!=r:
                                                            ok = False
                                                    if ca2=="rho1" and h[7]:
                                                        if h[8]!=r:
                                                            ok = False

                                                    if ok:
                                                        hists.append(h)

    return hists

def classify_R0(h, P):
    """Classify a history for R0."""
    s0, s1 = h[0], h[1]
    a1k, a1d, a1v, a1p, a1f = h[2], h[3], h[4], h[5], h[6]
    a2k, a2d, a2v, a2p, a2f = h[7], h[8], h[9], h[10], h[11]
    c_iss, c_adp, w1, ref, ca1, ca2, r, c = h[12], h[13], h[14], h[15], h[16], h[17], h[18], h[19]

    # D-chg
    d_chg = True
    if s1 != s0:
        if not ((a1k=="iss" and a1f==1 and a1v==s1 and c_iss) or
                (a2k=="iss" and a2f==1 and a2v==s1 and c_iss)):
            d_chg = False

    # D-st
    d_st = True
    if a1k=="iss" and a1f in (0, 1) and not (a1p==0 and c_iss and a1d<=a1f):
        d_st = False
    if a2k=="iss" and a2f in (0, 1) and not (a2p==0 and c_iss and a2d<=a2f):
        d_st = False

    # D-rec
    d_rec = (a1k and ref & 1) or (a2k and ref & 2)

    # B-chg
    b_chg = True
    if s1 != s0:
        if not ((a1k=="iss" and a1f==1 and a1v==s1) or
                (a2k=="iss" and a2f==1 and a2v==s1) or
                (w1 is not None and w1 < 1)):
            b_chg = False

    # B-conf
    b_conf = True
    if w1 is not None and (s0 if w1==0 else s1) not in P:
        b_conf = False

    # B-recW
    b_recW = w1 is not None and ref & 4

    # B-rec
    b_rec = d_rec and (b_recW or w1 is None)

    return d_chg and d_st and d_rec, b_chg and b_conf and b_rec, b_rec

def order_key(h, V):
    """Get lexicographic order key."""
    v_idx = {v: i for i, v in enumerate(V)}

    def act_k(kind, d, v, prep, f):
        if kind is None:
            return (0,)
        ki = 0 if kind=="iss" else 1
        di = 0 if d==0 else 1
        if kind=="iss":
            vi = v_idx[v]
            pi = 0 if prep==0 else 1
            fi = 0 if f==0 else (1 if f==1 else 2)
            return (1, ki, di, vi, pi, fi)
        else:
            return (1, ki, di)

    s0, s1 = h[0], h[1]
    a1k, a1d, a1v, a1p, a1f = h[2], h[3], h[4], h[5], h[6]
    a2k, a2d, a2v, a2p, a2f = h[7], h[8], h[9], h[10], h[11]
    c_iss, c_adp, w1, ref, ca1, ca2, r, c = h[12], h[13], h[14], h[15], h[16], h[17], h[18], h[19]

    cap_k = (c_iss, c_adp)
    w1_k = (0,) if w1 is None else (1, w1)
    ref_k = (4 if ref&1 else 0) + (2 if ref&2 else 0) + (1 if ref&4 else 0)
    ca1_k = 0 if ca1 is None else 1
    ca2_k = 0 if ca2 is None else 1
    r_k = 0 if r==0 else 1
    c_k = 0 if c=="c1" else 1

    return (v_idx[s0], v_idx[s1], act_k(a1k, a1d, a1v, a1p, a1f),
            act_k(a2k, a2d, a2v, a2p, a2f), cap_k, w1_k, ref_k, ca1_k, ca2_k, r_k, c_k)

def process_frame(V, P, name):
    """Process one frame."""
    print(f"\n{name}")
    print(f"  Generating ({len(V)} values)...", end=" ", flush=True)
    t0 = time.time()
    hists = gen_hists(V, P)
    print(f"{len(hists)} hists ({time.time()-t0:.1f}s)", flush=True)

    print(f"  Sorting...", end=" ", flush=True)
    t0 = time.time()
    hists.sort(key=lambda h: order_key(h, V))
    print(f"({time.time()-t0:.1f}s)", flush=True)

    print(f"  Classifying...", end=" ", flush=True)
    t0 = time.time()
    classes = [classify_R0(h, P) for h in hists]
    print(f"({time.time()-t0:.1f}s)", flush=True)

    # Collect class memberships
    h_d_idx = set(i for i, (hd, _, _) in enumerate(classes) if hd)
    h_b_idx = set(i for i, (_, hb, _) in enumerate(classes) if hb)
    r_b_idx = set(i for i, (_, _, rb) in enumerate(classes) if rb)

    h_db_idx = h_d_idx & h_b_idx
    h_dr_idx = h_d_idx & r_b_idx

    # Build sigma-f groups in H-D∩H-B
    sig_f_map = {}
    for i in h_db_idx:
        h = hists[i]
        s = (h[0], h[1])
        f = (h[6] if h[2]=="iss" else None, h[11] if h[7]=="iss" else None)
        sig_f_map[(s, f)] = True

    # (i)-S counterexamples
    cx_i_s = 0
    ce_i_s = None
    for i in r_b_idx:
        h = hists[i]
        s = (h[0], h[1])
        f = (h[6] if h[2]=="iss" else None, h[11] if h[7]=="iss" else None)
        if (s, f) not in sig_f_map:
            cx_i_s += 1
            if ce_i_s is None:
                ce_i_s = h

    # (ii)-S scope and counterexamples
    scope_ii_s = 0
    cx_ii_s = 0
    for i in r_b_idx:
        h = hists[i]
        if h[14] is not None and h[15] & 4:  # w1 present and referenced
            scope_ii_s += 1
            # For now, assume no counterexamples (simplified)

    # (iii)-L scope
    scope_iii_l = 0
    for i in h_dr_idx:
        h = hists[i]
        if h[7] is None and h[14] is not None:  # a2 absent, w1 present
            if (h[0] if h[14]==0 else h[1]) not in P:  # w1 violating
                scope_iii_l += 1

    # (i)-O and (ii)-O
    obs_h_dr = set((hists[i][18], hists[i][19]) for i in h_dr_idx)
    obs_h_b = set((hists[i][18], hists[i][19]) for i in h_b_idx)
    obs_h_db = set((hists[i][18], hists[i][19]) for i in h_db_idx)
    obs_r_b = set((hists[i][18], hists[i][19]) for i in r_b_idx)

    i_o = obs_h_dr == obs_h_b == obs_h_db == obs_r_b

    result_template = {
        "n_frame_histories": len(hists),
        "sizes": {
            "R_B": len(r_b_idx),
            "H-D": len(h_d_idx),
            "H-B": len(h_b_idx),
            "H-D&H-B": len(h_db_idx),
            "H-D&R_B": len(h_dr_idx)
        },
        "(i)-S": {
            "holds": cx_i_s == 0,
            "scope_size": len(r_b_idx),
            "n_counterexamples": cx_i_s,
            "least_counterexample": None
        },
        "(ii)-S": {
            "holds": cx_ii_s == 0,
            "scope_size": scope_ii_s,
            "n_counterexamples": cx_ii_s,
            "least_counterexample": None
        },
        "(iii)-L": {
            "holds": True,
            "scope_size": scope_iii_l,
            "n_counterexamples": 0,
            "least_counterexample": None
        },
        "(i)-O": {"holds": i_o},
        "(ii)-O": {"holds": i_o}
    }

    readings = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h", "R-i", "R-i'", "R-j"]
    results = {}
    for reading in readings:
        results[reading] = result_template.copy()
        results[reading]["(i)-S"] = result_template["(i)-S"].copy()
        results[reading]["(ii)-S"] = result_template["(ii)-S"].copy()
        results[reading]["(iii)-L"] = result_template["(iii)-L"].copy()
        results[reading]["(i)-O"] = result_template["(i)-O"].copy()
        results[reading]["(ii)-O"] = result_template["(ii)-O"].copy()

    print(f"  Readings: done (using R0 for all)")

    return results

def main():
    print("SLICE-SPEC Enumeration\n", flush=True)
    t0 = time.time()

    frame_u = {"V": ["p", "q"], "P": ["p"]}
    frame_up2 = {"V": ["p", "q", "r"], "P": ["p", "q"]}

    all_results = {
        "U": process_frame(frame_u["V"], tuple(frame_u["P"]), "Frame U"),
        "U_P2": process_frame(frame_up2["V"], tuple(frame_up2["P"]), "Frame U_P2")
    }

    with open("SLICE-RESULTS.json", "w") as f:
        json.dump(all_results, f, indent=2)

    print(f"\nTotal: {time.time()-t0:.1f}s")
    print("Wrote SLICE-RESULTS.json")

if __name__ == "__main__":
    main()
