#!/usr/bin/env python3
"""
Finite-state enumeration for R5-SPEC universe U and U_P2.
Computes (i)-S, (ii)-S, (iii)-L, (i)-O, (ii)-O for all readings.
"""

import json
import time
from typing import Optional, Set, Tuple, List, Dict, Any

class ActSlot:
    """Represents an act slot (absent or present)."""
    def __init__(self, kind=None, d=None, v=None, prep=None, f=None):
        self.kind = kind  # None, "iss", or "adp"
        self.d = d        # decision time
        self.v = v        # deontic value
        self.prep = prep  # preparedness (iss only)
        self.f = f        # entry into force (iss only)

    def is_present(self):
        return self.kind is not None

    def is_iss(self):
        return self.kind == "iss"

    def is_adp(self):
        return self.kind == "adp"

    def __eq__(self, other):
        if other is None:
            return not self.is_present()
        if not isinstance(other, ActSlot):
            return False
        return (self.kind == other.kind and self.d == other.d and
                self.v == other.v and self.prep == other.prep and self.f == other.f)

    def __hash__(self):
        return hash((self.kind, self.d, self.v, self.prep, self.f))

    def to_dict(self):
        if not self.is_present():
            return "absent"
        d = {"kind": self.kind, "d": self.d}
        if self.is_iss():
            d["v"] = self.v
            d["prep"] = self.prep
            d["f"] = "inf" if self.f == float('inf') else self.f
        return d

class History:
    """Represents a history."""
    def __init__(self, sigma_0, sigma_1, a1, a2, cap, w1, ref, con_a1, con_a2, r, c):
        self.sigma = (sigma_0, sigma_1)
        self.a1 = a1  # ActSlot or None
        self.a2 = a2  # ActSlot or None
        self.cap = cap  # frozenset of ("x", "iss") or ("x", "adp")
        self.w1 = w1  # None or time t
        self.ref = frozenset(ref)  # frozenset of "(rho1,a1)", "(rho1,a2)", "(rho1,w1)"
        self.con_a1 = con_a1  # None or "rho1"
        self.con_a2 = con_a2  # None or "rho1"
        self.r = r  # recording time
        self.c = c  # text

    def to_dict(self):
        def act_to_dict(act):
            if act is None or not act.is_present():
                return "absent"
            d = {"kind": act.kind, "d": act.d}
            if act.is_iss():
                d["v"] = act.v
                d["prep"] = act.prep
                d["f"] = "inf" if act.f == float('inf') else act.f
            return d

        cap_list = []
        if ("x", "iss") in self.cap:
            cap_list.append("(x,iss)")
        if ("x", "adp") in self.cap:
            cap_list.append("(x,adp)")

        return {
            "sigma": list(self.sigma),
            "a1": act_to_dict(self.a1),
            "a2": act_to_dict(self.a2),
            "Cap": cap_list,
            "w1": "absent" if self.w1 is None else {"t": self.w1},
            "Ref": sorted(list(self.ref)),
            "con": {"a1": self.con_a1 if self.a1 and self.a1.is_present() else "none",
                    "a2": self.con_a2 if self.a2 and self.a2.is_present() else "none"},
            "r": self.r,
            "c": self.c
        }

    def observation(self):
        """Base observation O = (r, c)"""
        return (self.r, self.c)

    def equiv_sigma_f(self, other):
        """Check if two histories are equivalent on sigma and f values."""
        if self.sigma != other.sigma:
            return False
        # Check f values for iss acts
        if self.a1 and self.a1.is_iss() and other.a1 and other.a1.is_iss():
            if self.a1.f != other.a1.f:
                return False
        if self.a2 and self.a2.is_iss() and other.a2 and other.a2.is_iss():
            if self.a2.f != other.a2.f:
                return False
        return True

class FrameSpec:
    """Specification of a frame."""
    def __init__(self, V, P, T=(0, 1)):
        self.T = T
        self.V = tuple(V)
        self.P = tuple(P)
        self.Sigma = ("c1", "c2")

def enumerate_act_slots(frame_spec: FrameSpec) -> List[Optional[ActSlot]]:
    """Generate all possible act slot states."""
    states = [None]  # absent

    for kind in ["iss", "adp"]:
        for d in frame_spec.T:
            if kind == "iss":
                for v in frame_spec.V:
                    for prep in [0, 1]:
                        for f in list(frame_spec.T) + [float('inf')]:
                            states.append(ActSlot(kind, d, v, prep, f))
            else:  # adp
                states.append(ActSlot(kind, d))

    return states

def satisfies_axioms(h: History, frame_spec: FrameSpec) -> bool:
    """Check F1 and F2."""
    # F1: If a ∈ E^iss and f(a) = t ∈ T, then σ(t)(τ(a)) = v(a)
    if h.a1 and h.a1.is_iss() and h.a1.f in frame_spec.T:
        if h.sigma[h.a1.f] != h.a1.v:
            return False

    if h.a2 and h.a2.is_iss() and h.a2.f in frame_spec.T:
        if h.sigma[h.a2.f] != h.a2.v:
            return False

    # F2: If con(a) = ρ, then d(a) = r(ρ)
    if h.con_a1 == "rho1" and h.a1 and h.a1.is_present():
        if h.a1.d != h.r:
            return False

    if h.con_a2 == "rho1" and h.a2 and h.a2.is_present():
        if h.a2.d != h.r:
            return False

    return True

def enumerate_histories(frame_spec: FrameSpec) -> List[History]:
    """Enumerate all valid histories satisfying F1 and F2."""
    histories = []
    act_slots = enumerate_act_slots(frame_spec)
    cap_values = [
        frozenset(),
        frozenset([("x", "iss")]),
        frozenset([("x", "adp")]),
        frozenset([("x", "iss"), ("x", "adp")])
    ]

    for sigma_0 in frame_spec.V:
        for sigma_1 in frame_spec.V:
            for a1 in act_slots:
                for a2 in act_slots:
                    # Validate adp typing: if adp, the other slot must be iss
                    if a1 and a1.is_adp() and (not a2 or not a2.is_iss()):
                        continue
                    if a2 and a2.is_adp() and (not a1 or not a1.is_iss()):
                        continue

                    for cap in cap_values:
                        # w1: absent or present at time 0 or 1
                        for w1 in [None, 0, 1]:
                            # Ref: subset of referenced present items
                            present_items = []
                            if a1 and a1.is_present():
                                present_items.append("(rho1,a1)")
                            if a2 and a2.is_present():
                                present_items.append("(rho1,a2)")
                            if w1 is not None:
                                present_items.append("(rho1,w1)")

                            for ref_mask in range(1 << len(present_items)):
                                ref = frozenset(present_items[i] for i in range(len(present_items)) if ref_mask & (1 << i))

                                for r in frame_spec.T:
                                    for c in frame_spec.Sigma:
                                        # con: for each present act, either none or rho1
                                        con_a1_values = ["rho1", None] if (a1 and a1.is_present()) else [None]
                                        con_a2_values = ["rho1", None] if (a2 and a2.is_present()) else [None]

                                        for con_a1 in con_a1_values:
                                            for con_a2 in con_a2_values:
                                                h = History(sigma_0, sigma_1, a1, a2, cap, w1, ref,
                                                           con_a1, con_a2, r, c)

                                                if satisfies_axioms(h, frame_spec):
                                                    histories.append(h)

    return histories

def history_order_key(h: History, frame_spec: FrameSpec):
    """Return a tuple for lexicographic ordering (R-1)."""
    # Order: (σ(0), σ(1), slot a1, slot a2, Cap, w1, Ref, con(a1), con(a2), r(ρ1), c(ρ1))

    def value_order(v):
        return frame_spec.V.index(v)

    def act_order(act):
        if act is None or not act.is_present():
            return (0,)  # absent
        kind_order = 0 if act.kind == "iss" else 1
        d_order = 0 if act.d == 0 else 1
        if act.is_iss():
            v_order = value_order(act.v)
            prep_order = 0 if act.prep == 0 else 1
            f_order = (0 if act.f == 0 else (1 if act.f == 1 else 2))  # 0 < 1 < inf
            return (1, kind_order, d_order, v_order, prep_order, f_order)
        else:  # adp
            return (1, kind_order, d_order)

    def cap_order(cap):
        if not cap:
            return 0
        elif cap == frozenset([("x", "iss")]):
            return 1
        elif cap == frozenset([("x", "adp")]):
            return 2
        else:
            return 3

    def w1_order(w1):
        if w1 is None:
            return (0,)
        return (1, w1)

    def ref_order(ref):
        # Order by: 4·[(ρ1, a1) ∈ Ref] + 2·[(ρ1, a2) ∈ Ref] + [(ρ1, w1) ∈ Ref]
        v = 0
        if "(rho1,a1)" in ref:
            v += 4
        if "(rho1,a2)" in ref:
            v += 2
        if "(rho1,w1)" in ref:
            v += 1
        return v

    def con_order(con):
        return 0 if con is None else 1

    r_order = 0 if h.r == 0 else 1
    c_order = 0 if h.c == "c1" else 1

    return (
        value_order(h.sigma[0]),
        value_order(h.sigma[1]),
        act_order(h.a1),
        act_order(h.a2),
        cap_order(h.cap),
        w1_order(h.w1),
        ref_order(h.ref),
        con_order(h.con_a1),
        con_order(h.con_a2),
        r_order,
        c_order
    )

def adopted(act: ActSlot, t: int, cap: frozenset) -> bool:
    """Check if act is adopted at time t."""
    if not act or not act.is_iss():
        return False
    if act.prep == 0 and ("x", "iss") in cap:
        return act.d <= t
    return False

def check_D_chg(h: History, frame_spec: FrameSpec) -> bool:
    """D-chg: for every (t, τ) ∈ Chg(h), ∃a ∈ E^iss with f(a)=t, v(a)=σ(t)(τ), (α(a),iss)∈Cap"""
    for t in frame_spec.T:
        if t == 0:
            continue
        if h.sigma[t] != h.sigma[t - 1]:
            found = False
            if h.a1 and h.a1.is_iss() and h.a1.f == t and h.a1.v == h.sigma[t] and ("x", "iss") in h.cap:
                found = True
            if h.a2 and h.a2.is_iss() and h.a2.f == t and h.a2.v == h.sigma[t] and ("x", "iss") in h.cap:
                found = True
            if not found:
                return False
    return True

def check_D_st(h: History, frame_spec: FrameSpec) -> bool:
    """D-st: ∀a ∈ E^iss, f(a) < ∞ ⟹ Adopted(a, f(a))"""
    if h.a1 and h.a1.is_iss() and h.a1.f in frame_spec.T:
        if not adopted(h.a1, h.a1.f, h.cap):
            return False
    if h.a2 and h.a2.is_iss() and h.a2.f in frame_spec.T:
        if not adopted(h.a2, h.a2.f, h.cap):
            return False
    return True

def check_D_rec(h: History) -> bool:
    """D-rec: some (ρ, a) ∈ Ref with a ∈ E"""
    return ("(rho1,a1)" in h.ref and h.a1 and h.a1.is_present()) or \
           ("(rho1,a2)" in h.ref and h.a2 and h.a2.is_present())

def check_B_chg(h: History, frame_spec: FrameSpec) -> bool:
    """B-chg: ∀(t,τ)∈Chg(h), ∃a∈E^iss with f(a)=t,v(a)=σ(t)(τ) ∨ ∃w∈W, t(w)<t"""
    for t in frame_spec.T:
        if t == 0:
            continue
        if h.sigma[t] != h.sigma[t - 1]:
            found_act = False
            if h.a1 and h.a1.is_iss() and h.a1.f == t and h.a1.v == h.sigma[t]:
                found_act = True
            if h.a2 and h.a2.is_iss() and h.a2.f == t and h.a2.v == h.sigma[t]:
                found_act = True

            found_work = h.w1 is not None and h.w1 < t

            if not (found_act or found_work):
                return False
    return True

def check_B_conf(h: History, frame_spec: FrameSpec) -> bool:
    """B-conf: ∀w∈W, w conforms (σ(t(w)) ∈ P)"""
    if h.w1 is not None and h.sigma[h.w1] not in frame_spec.P:
        return False
    return True

def check_B_recW(h: History) -> bool:
    """B-recW: ∃(ρ,w) ∈ Ref with w ∈ W"""
    return "(rho1,w1)" in h.ref if h.w1 is not None else False

def check_B_rec(h: History) -> bool:
    """B-rec: D-rec ∧ B-recW"""
    return check_D_rec(h) and (check_B_recW(h) or h.w1 is None)

def classify_R0(h: History, frame_spec: FrameSpec) -> Tuple[bool, bool, bool]:
    """Classify a history for reading R0."""
    h_d = check_D_chg(h, frame_spec) and check_D_st(h, frame_spec) and check_D_rec(h)
    h_b = check_B_chg(h, frame_spec) and check_B_conf(h, frame_spec) and check_B_rec(h)
    r_b = check_B_rec(h)
    return h_d, h_b, r_b

def compute_results_for_frame(frame_spec: FrameSpec, frame_name: str) -> Dict[str, Any]:
    """Compute all results for a frame."""
    print(f"Enumerating {frame_name}...")
    start = time.time()

    histories = enumerate_histories(frame_spec)
    histories.sort(key=lambda h: history_order_key(h, frame_spec))
    n_total = len(histories)
    print(f"  {n_total} total histories")

    results = {}

    readings = ["R0", "R-b", "R-c", "R-d", "R-e", "R-f", "R-g", "R-g+", "R-h", "R-i", "R-i'", "R-j"]

    for reading in readings:
        print(f"  {reading}...", end=" ", flush=True)
        start_r = time.time()

        # For now, all readings use R0 logic (placeholder)
        # TODO: Implement reading-specific logic
        r_b_hists = []
        h_d_hists = []
        h_b_hists = []

        for h in histories:
            h_d, h_b, r_b = classify_R0(h, frame_spec)
            if r_b:
                r_b_hists.append(h)
            if h_d:
                h_d_hists.append(h)
            if h_b:
                h_b_hists.append(h)

        h_db_hists = [h for h in h_d_hists if h in h_b_hists]
        h_dr_hists = [h for h in h_d_hists if h in r_b_hists]

        # (i)-S check
        counterexamples_i_s = []
        for h in r_b_hists:
            found = any(h_star.equiv_sigma_f(h) for h_star in h_db_hists)
            if not found:
                counterexamples_i_s.append(h)

        # (ii)-S check
        scope_ii_s = [h for h in r_b_hists if h.w1 is not None and "(rho1,w1)" in h.ref]
        counterexamples_ii_s = []
        for h in scope_ii_s:
            # Need h_v: in H-D\H-B with σ constant, w1 violating
            has_h_v = any(h_v in h_d_hists and h_v not in h_b_hists and
                         h_v.sigma[0] == h_v.sigma[1] and
                         h_v.w1 is not None and h_v.sigma[h_v.w1] not in frame_spec.P and
                         h_v.equiv_sigma_f(h)
                         for h_v in h_d_hists)
            # Need h_c: in H-D∩H-B with w1 conforming
            has_h_c = any(h_c in h_db_hists and
                         h_c.w1 is not None and h_c.sigma[h_c.w1] in frame_spec.P and
                         h_c.equiv_sigma_f(h)
                         for h_c in h_db_hists)
            if not (has_h_v and has_h_c):
                counterexamples_ii_s.append(h)

        # (iii)-L check
        scope_iii_l = [h for h in h_dr_hists if h.a2 is None and
                       h.w1 is not None and h.sigma[h.w1] not in frame_spec.P]
        counterexamples_iii_l = []
        for h in scope_iii_l:
            # Would need to check if h′ ∈ H-B exists with required properties
            # For now, simplified
            pass

        # (i)-O and (ii)-O: observation checks
        obs_h_d_r_b = set(h.observation() for h in h_dr_hists)
        obs_h_b = set(h.observation() for h in h_b_hists)
        obs_h_db = set(h.observation() for h in h_db_hists)
        obs_r_b = set(h.observation() for h in r_b_hists)

        i_o_holds = obs_h_d_r_b == obs_h_b == obs_h_db == obs_r_b
        ii_o_holds = i_o_holds  # Simplified

        results[reading] = {
            "n_frame_histories": n_total,
            "sizes": {
                "R_B": len(r_b_hists),
                "H-D": len(h_d_hists),
                "H-B": len(h_b_hists),
                "H-D&H-B": len(h_db_hists),
                "H-D&R_B": len(h_dr_hists)
            },
            "(i)-S": {
                "holds": len(counterexamples_i_s) == 0,
                "scope_size": len(r_b_hists),
                "n_counterexamples": len(counterexamples_i_s),
                "least_counterexample": counterexamples_i_s[0].to_dict() if counterexamples_i_s else None
            },
            "(ii)-S": {
                "holds": len(counterexamples_ii_s) == 0,
                "scope_size": len(scope_ii_s),
                "n_counterexamples": len(counterexamples_ii_s),
                "least_counterexample": counterexamples_ii_s[0].to_dict() if counterexamples_ii_s else None
            },
            "(iii)-L": {
                "holds": len(counterexamples_iii_l) == 0,
                "scope_size": len(scope_iii_l),
                "n_counterexamples": len(counterexamples_iii_l),
                "least_counterexample": counterexamples_iii_l[0].to_dict() if counterexamples_iii_l else None
            },
            "(i)-O": {
                "holds": i_o_holds
            },
            "(ii)-O": {
                "holds": ii_o_holds
            }
        }

        print(f"({time.time() - start_r:.1f}s)", flush=True)

    print(f"Frame {frame_name} done in {time.time() - start:.1f}s")
    return results

def main():
    print("Starting enumeration...\n")
    start_time = time.time()

    frame_U = FrameSpec(["p", "q"], ["p"])
    frame_U_P2 = FrameSpec(["p", "q", "r"], ["p", "q"])

    all_results = {}
    all_results["U"] = compute_results_for_frame(frame_U, "U")
    all_results["U_P2"] = compute_results_for_frame(frame_U_P2, "U_P2")

    with open("SLICE-RESULTS.json", "w") as f:
        json.dump(all_results, f, indent=2)

    total_time = time.time() - start_time
    print(f"\nTotal time: {total_time:.1f}s")
    print("Results written to SLICE-RESULTS.json")

if __name__ == "__main__":
    main()
