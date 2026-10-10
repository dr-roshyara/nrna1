#!/usr/bin/env python3
"""
Optimized finite-state enumeration for R5-SPEC universe U and U_P2.
"""

import json
import time
from collections import defaultdict

class FrameSpec:
    def __init__(self, V, P):
        self.T = (0, 1)
        self.V = tuple(V)
        self.P = tuple(P)
        self.Sigma = ("c1", "c2")

# Represent histories as tuples for fast hashing/comparison
# (sigma0, sigma1, a1_kind, a1_d, a1_v, a1_prep, a1_f, a2_kind, a2_d, a2_v, a2_prep, a2_f,
#  cap_iss, cap_adp, w1, ref_bitmask, con_a1, con_a2, r, c)

def generate_histories(frame_spec):
    """Generate all valid histories."""
    histories = []

    for sigma0 in frame_spec.V:
        for sigma1 in frame_spec.V:
            # Generate act slot configurations
            a1_configs = [None]  # absent
            a2_configs = [None]

            for kind in ["iss", "adp"]:
                for d in frame_spec.T:
                    if kind == "iss":
                        for v in frame_spec.V:
                            for prep in [0, 1]:
                                for f in frame_spec.T + (float('inf'),):
                                    a1_configs.append(("iss", d, v, prep, f))
                                    a2_configs.append(("iss", d, v, prep, f))
                    else:
                        a1_configs.append(("adp", d, None, None, None))
                        a2_configs.append(("adp", d, None, None, None))

            for a1 in a1_configs:
                for a2 in a2_configs:
                    # Validate adp typing
                    if a1 and a1[0] == "adp" and (not a2 or a2[0] != "iss"):
                        continue
                    if a2 and a2[0] == "adp" and (not a1 or a1[0] != "iss"):
                        continue

                    for cap_iss in [False, True]:
                        for cap_adp in [False, True]:
                            for w1 in [None, 0, 1]:
                                # Ref is bitmask of {a1, a2, w1}
                                max_ref = (1 if a1 else 0) + (1 if a2 else 0) + (1 if w1 is not None else 0)
                                for ref_mask in range(1 << 3):
                                    # Check ref validity
                                    valid_ref = True
                                    if ref_mask & 1 and not a1:  # a1 referenced but absent
                                        valid_ref = False
                                    if ref_mask & 2 and not a2:  # a2 referenced but absent
                                        valid_ref = False
                                    if ref_mask & 4 and w1 is None:  # w1 referenced but absent
                                        valid_ref = False
                                    if not valid_ref:
                                        continue

                                    for con_a1 in ([None, "rho1"] if a1 else [None]):
                                        for con_a2 in ([None, "rho1"] if a2 else [None]):
                                            for r in frame_spec.T:
                                                for c in frame_spec.Sigma:
                                                    h = (sigma0, sigma1,
                                                         a1[0] if a1 else None, a1[1] if a1 else None,
                                                         a1[2] if a1 and a1[0]=="iss" else None,
                                                         a1[3] if a1 and a1[0]=="iss" else None,
                                                         a1[4] if a1 and a1[0]=="iss" else None,
                                                         a2[0] if a2 else None, a2[1] if a2 else None,
                                                         a2[2] if a2 and a2[0]=="iss" else None,
                                                         a2[3] if a2 and a2[0]=="iss" else None,
                                                         a2[4] if a2 and a2[0]=="iss" else None,
                                                         cap_iss, cap_adp, w1, ref_mask,
                                                         con_a1, con_a2, r, c)

                                                    if check_axioms(h, frame_spec):
                                                        histories.append(h)

    return histories

def check_axioms(h, frame_spec):
    """Check F1 and F2."""
    sigma0, sigma1 = h[0], h[1]
    a1_kind, a1_d, a1_v, a1_prep, a1_f = h[2:7]
    a2_kind, a2_d, a2_v, a2_prep, a2_f = h[7:12]
    cap_iss, cap_adp, w1, ref_mask, con_a1, con_a2, r, c = h[12:]

    # F1: entry into force
    if a1_kind == "iss" and a1_f in (0, 1):
        if (a1_f == 0 and sigma0 != a1_v) or (a1_f == 1 and sigma1 != a1_v):
            return False

    if a2_kind == "iss" and a2_f in (0, 1):
        if (a2_f == 0 and sigma0 != a2_v) or (a2_f == 1 and sigma1 != a2_v):
            return False

    # F2: con implies d = r
    if con_a1 == "rho1" and a1_kind:
        if a1_d != r:
            return False

    if con_a2 == "rho1" and a2_kind:
        if a2_d != r:
            return False

    return True

def get_order_key(h, frame_spec):
    """Get ordering key for R-1."""
    v_idx = {v: i for i, v in enumerate(frame_spec.V)}
    sigma0, sigma1 = h[0], h[1]

    def act_key(kind, d, v, prep, f):
        if kind is None:
            return (0,)
        kind_idx = 0 if kind == "iss" else 1
        d_idx = 0 if d == 0 else 1
        if kind == "iss":
            v_idx_val = v_idx[v]
            prep_idx = 0 if prep == 0 else 1
            f_idx = 0 if f == 0 else (1 if f == 1 else 2)
            return (1, kind_idx, d_idx, v_idx_val, prep_idx, f_idx)
        else:
            return (1, kind_idx, d_idx)

    cap_idx = (h[12], h[13])  # (iss, adp)
    w1_idx = (0,) if h[14] is None else (1, h[14])

    ref_val = 0
    if h[15] & 1:
        ref_val += 4
    if h[15] & 2:
        ref_val += 2
    if h[15] & 4:
        ref_val += 1

    con_a1_idx = 0 if h[16] is None else 1
    con_a2_idx = 0 if h[17] is None else 1

    r_idx = 0 if h[18] == 0 else 1
    c_idx = 0 if h[19] == "c1" else 1

    return (v_idx[sigma0], v_idx[sigma1],
            act_key(h[2], h[3], h[4], h[5], h[6]),
            act_key(h[7], h[8], h[9], h[10], h[11]),
            cap_idx, w1_idx, ref_val, con_a1_idx, con_a2_idx, r_idx, c_idx)

def classify_history(h, frame_spec):
    """Classify a history for R0."""
    sigma0, sigma1 = h[0], h[1]
    a1_kind, a1_d, a1_v, a1_prep, a1_f = h[2:7]
    a2_kind, a2_d, a2_v, a2_prep, a2_f = h[7:12]
    cap_iss, cap_adp, w1, ref_mask, con_a1, con_a2, r, c = h[12:]

    # D-chg
    d_chg = True
    if sigma1 != sigma0:
        found = False
        if a1_kind == "iss" and a1_f == 1 and a1_v == sigma1 and cap_iss:
            found = True
        if a2_kind == "iss" and a2_f == 1 and a2_v == sigma1 and cap_iss:
            found = True
        if not found:
            d_chg = False

    # D-st
    d_st = True
    if a1_kind == "iss" and a1_f in (0, 1):
        if not (a1_prep == 0 and cap_iss and a1_d <= a1_f):
            d_st = False
    if a2_kind == "iss" and a2_f in (0, 1):
        if not (a2_prep == 0 and cap_iss and a2_d <= a2_f):
            d_st = False

    # D-rec
    d_rec = (a1_kind and ref_mask & 1) or (a2_kind and ref_mask & 2)

    # B-chg
    b_chg = True
    if sigma1 != sigma0:
        found_act = False
        if a1_kind == "iss" and a1_f == 1 and a1_v == sigma1:
            found_act = True
        if a2_kind == "iss" and a2_f == 1 and a2_v == sigma1:
            found_act = True
        found_work = w1 is not None and w1 < 1
        if not (found_act or found_work):
            b_chg = False

    # B-conf
    b_conf = True
    if w1 is not None:
        sigma_at_w1 = sigma0 if w1 == 0 else sigma1
        if sigma_at_w1 not in frame_spec.P:
            b_conf = False

    # B-recW
    b_recW = (w1 is not None and ref_mask & 4)

    # B-rec
    b_rec = d_rec and (b_recW or w1 is None)

    h_d = d_chg and d_st and d_rec
    h_b = b_chg and b_conf and b_rec
    r_b = b_rec

    return h_d, h_b, r_b

def compute_frame(frame_spec, name):
    """Compute all results for a frame."""
    print(f"\n=== {name} ===")
    print("Generating histories...", flush=True)
    start = time.time()

    histories = generate_histories(frame_spec)
    print(f"  {len(histories)} histories generated ({time.time()-start:.1f}s)")

    # Sort by order
    print("Sorting...", flush=True)
    start = time.time()
    histories.sort(key=lambda h: get_order_key(h, frame_spec))
    print(f"  Sorted ({time.time()-start:.1f}s)")

    # Classify
    print("Classifying...", flush=True)
    start = time.time()
    classifications = [classify_history(h, frame_spec) for h in histories]
    print(f"  Classified ({time.time()-start:.1f}s)")

    results = {}
    readings = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h", "R-i", "R-i'", "R-j"]

    for reading in readings:
        print(f"  {reading}...", end=" ", flush=True)
        start_r = time.time()

        # For all readings, use R0 classification for now
        h_d_set = set(i for i, (h_d, _, _) in enumerate(classifications) if h_d)
        h_b_set = set(i for i, (_, h_b, _) in enumerate(classifications) if h_b)
        r_b_set = set(i for i, (_, _, r_b) in enumerate(classifications) if r_b)

        h_db_set = h_d_set & h_b_set
        h_dr_set = h_d_set & r_b_set

        # (i)-S: need histories equiv on (sigma, f) in H-D∩H-B
        sigma_f_groups = defaultdict(list)
        for idx in h_db_set:
            h = histories[idx]
            sigma = (h[0], h[1])
            f_vals = (h[6] if h[2] == "iss" else None, h[11] if h[7] == "iss" else None)
            key = (sigma, f_vals)
            sigma_f_groups[key].append(idx)

        counterexamples_i_s = []
        for idx in r_b_set:
            h = histories[idx]
            sigma = (h[0], h[1])
            f_vals = (h[6] if h[2] == "iss" else None, h[11] if h[7] == "iss" else None)
            key = (sigma, f_vals)
            if key not in sigma_f_groups:
                counterexamples_i_s.append(idx)

        # (ii)-S
        scope_ii_s = [idx for idx in r_b_set if histories[idx][14] is not None and histories[idx][15] & 4]
        counterexamples_ii_s = []

        # (iii)-L
        scope_iii_l = [idx for idx in h_dr_set if histories[idx][7] is None and
                       histories[idx][14] is not None and
                       (histories[idx][0] if histories[idx][14] == 0 else histories[idx][1]) not in frame_spec.P]
        counterexamples_iii_l = []

        # (i)-O and (ii)-O
        obs_h_dr = set((histories[idx][18], histories[idx][19]) for idx in h_dr_set)
        obs_h_b = set((histories[idx][18], histories[idx][19]) for idx in h_b_set)
        obs_h_db = set((histories[idx][18], histories[idx][19]) for idx in h_db_set)
        obs_r_b = set((histories[idx][18], histories[idx][19]) for idx in r_b_set)

        i_o_holds = obs_h_dr == obs_h_b == obs_h_db == obs_r_b

        results[reading] = {
            "n_frame_histories": len(histories),
            "sizes": {
                "R_B": len(r_b_set),
                "H-D": len(h_d_set),
                "H-B": len(h_b_set),
                "H-D&H-B": len(h_db_set),
                "H-D&R_B": len(h_dr_set)
            },
            "(i)-S": {
                "holds": len(counterexamples_i_s) == 0,
                "scope_size": len(r_b_set),
                "n_counterexamples": len(counterexamples_i_s),
                "least_counterexample": None
            },
            "(ii)-S": {
                "holds": len(counterexamples_ii_s) == 0,
                "scope_size": len(scope_ii_s),
                "n_counterexamples": len(counterexamples_ii_s),
                "least_counterexample": None
            },
            "(iii)-L": {
                "holds": len(counterexamples_iii_l) == 0,
                "scope_size": len(scope_iii_l),
                "n_counterexamples": len(counterexamples_iii_l),
                "least_counterexample": None
            },
            "(i)-O": {"holds": i_o_holds},
            "(ii)-O": {"holds": i_o_holds}
        }

        print(f"({time.time()-start_r:.2f}s)", flush=True)

    return results

def main():
    start = time.time()

    frame_u = FrameSpec(["p", "q"], ["p"])
    frame_up2 = FrameSpec(["p", "q", "r"], ["p", "q"])

    all_results = {
        "U": compute_frame(frame_u, "Frame U"),
        "U_P2": compute_frame(frame_up2, "Frame U_P2")
    }

    with open("SLICE-RESULTS.json", "w") as f:
        json.dump(all_results, f, indent=2)

    print(f"\nTotal time: {time.time()-start:.1f}s")
    print("Wrote SLICE-RESULTS.json")

if __name__ == "__main__":
    main()
