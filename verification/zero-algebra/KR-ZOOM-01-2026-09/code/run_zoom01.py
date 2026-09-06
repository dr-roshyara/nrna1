#!/usr/bin/env python3
r"""KR-ZOOM-01 executor — emits the raw ledger. No analysis here; analysis is analyse.py.

DECISIONS MADE BEFORE EXECUTION (see DESIGN.md), each preventing a specific false positive:
 D1  Q IS HELD FIXED across resolutions in the primary arm.  H3 ("Zero becomes non-Zero")
     is otherwise trivially satisfiable by changing the question -- the activation-attribution
     trap already recorded for KR-STATE-01.  A varying-Q CONTRAST arm is run separately and
     is never pooled with the primary arm.
 D2  COUNTERFACTUAL ANCHOR TRAP.  If the eliminated dimension IS the zoom anchor, the two
     branches diverge for a bookkeeping reason, not an epistemic one.  Cases are classified
     ANCHOR / NON-ANCHOR and the counterfactual is adjudicated ONLY on NON-ANCHOR.
 D3  Path/order comparison emits FOUR separate equalities; they are never collapsed.
 D4  "Visible" is never recorded as "relevant".  newly_exposed_dimensions is descriptive;
     relevance requires a zero_test intervention.
"""
import os, sys, json, itertools, collections
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kzoom import (World, State, Dim, DIRECTIONS, DTYPES, Q_READS, REL_KIND,
                   state_of, focus, observe, eliminate, zero_test, zoom, nontrivial, atomic)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEED_TRAIN, SEED_TEST = 20260904, 88020260904
N_ROOTS   = 300
MAX_DEPTH = 4
S_PRIMARY = ("INTERNAL", "EVIDENCE", "CONTEXT")      # the focus set
S_CONTRAST= ("INTERNAL", "EVIDENCE", "HISTORY")      # contrast arm only

def dimset(K):   return tuple(sorted(d.id for d in K.dims))
def dtypeset(K): return tuple(sorted({d.dtype for d in K.dims}))

def emit(rows, **kw): rows.append(kw)

def run_case(w, rid, split, rows):
    K0 = state_of(w, rid, 0)
    a0, ex0 = focus(K0, S_PRIMARY)
    x0, anc0 = observe(a0)

    # ---- zero tests at r=0 on EVERY dimension (never inferred from exclusion, D4)
    z0 = {d.id: zero_test(K0, d.id, S_PRIMARY) for d in K0.dims}

    for tau in DIRECTIONS:
        K1 = zoom(w, K0, anc0, tau, 1)
        ok = nontrivial(K1)
        rec = dict(experiment_id="KR-ZOOM-01", split=split, case_id=rid,
                   resolution_level=0, state_id=rid, parent_state_id=None,
                   query_id="Q_liquidity", contract_id="C_primary",
                   focus_set=list(S_PRIMARY),
                   active_dimensions=[d.id for d in a0],
                   excluded_dimensions=[d.id for d in ex0],
                   observed_value=x0, anchor_dim=anc0,
                   traversal_direction=tau,
                   zoom_admissible=K1 is not None,
                   zoom_nontrivial=ok,
                   next_state_id=(",".join(K1.node_ids) if K1 else None),
                   newly_exposed_dimensions=([d.id for d in K1.dims] if K1 else []),
                   newly_exposed_dtypes=(list(dtypeset(K1)) if K1 else []),
                   exposed_relation_kind=REL_KIND[tau],
                   zero_tests=[z0[d.id] for d in K0.dims],
                   atomicity_status=("NON_ATOMIC" if ok else "ATOMIC"),
                   rebasing_observed=False, evidence_refs=[rid])
        if ok:
            a1, ex1 = focus(K1, S_PRIMARY)
            x1, anc1 = observe(a1)                      # D1: SAME Q, SAME S
            rec["obs_at_r1"] = x1
            rec["h4_class"] = ("determination_gained" if x0 == "UNDETERMINED" and x1 != "UNDETERMINED"
                          else "determination_lost"   if x1 == "UNDETERMINED" and x0 != "UNDETERMINED"
                          else "same_observable"      if x0 == x1 else "different_observable")
            rec["h4_state_differs"] = dimset(K0) != dimset(K1)
            # H6 Q-reconstruction: C_Q(K_{r+1}) == x_r  (type-compatible: both labels)
            rec["q_reconstruction"] = {"observed": True, "value": x1, "equal_to_x_r": x1 == x0}
            # H3: a dimension Zero at r=0 whose descendant material is NON-Zero at r=1,
            #     with Q and S held fixed.  Exposure alone does not count (D4).
            z1 = {d.id: zero_test(K1, d.id, S_PRIMARY) for d in K1.dims}
            rec["zero_tests_r1"] = list(z1.values())
            rec["h3_nonzero_at_r1"] = [k for k, v in z1.items() if not v["zero_at_current_resolution"]]
            rec["h3_zero_at_r0_dtypes_excluded"] = sorted({d.dtype for d in ex0})
            # H5 re-basing: use K1 as substrate for a further cycle
            K2 = zoom(w, K1, anc1, tau, 2)
            rec["rebasing_observed"] = nontrivial(K2)
            if nontrivial(K2):
                a2, _ = focus(K2, S_PRIMARY); x2, _ = observe(a2)
                rec["rebasing"] = {"next_query": "Q_liquidity", "next_observation": x2,
                                   "next_state_id": ",".join(K2.node_ids),
                                   "contract_determinate": x2 != "UNDETERMINED"}
            # H2: atomic under some tau' at r=0 but NON-atomic under that same tau' at r=1
            rec["h2_transitions"] = [t2 for t2 in DIRECTIONS
                                     if atomic(w, K0, anc0, t2) and not atomic(w, K1, anc1, t2)]
        emit(rows, **rec)

    # ---- §12 path / order test: FOUR separate equalities (D3)
    for ti, tj in itertools.permutations(DIRECTIONS, 2):
        if (ti, tj) > (tj, ti): continue                  # each unordered pair once
        def path(p, q):
            A = zoom(w, K0, anc0, p, 1)
            if not nontrivial(A): return None
            aA, _ = focus(A, S_PRIMARY); xA, ancA = observe(aA)
            B = zoom(w, A, ancA, q, 2)
            if not nontrivial(B): return None
            aB, _ = focus(B, S_PRIMARY); xB, _ = observe(aB)
            return {"inter": dimset(A), "inter_obs": xA, "final": dimset(B),
                    "obs": xB, "rels": tuple(sorted({d.dtype for d in B.dims}))}
        P, Qp = path(ti, tj), path(tj, ti)
        emit(rows, experiment_id="KR-ZOOM-01", split=split, case_id=rid, record="PATH",
             pair=[ti, tj], both_admissible=bool(P and Qp),
             path_comparison=(None if not (P and Qp) else {
                 "final_state_equal":       P["final"] == Qp["final"],
                 "observable_equal":        P["obs"]   == Qp["obs"],
                 "intermediate_state_equal":P["inter"] == Qp["inter"],
                 "relation_set_equal":      P["rels"]  == Qp["rels"],
                 "one_determines_other_not": (P["obs"] == "UNDETERMINED") != (Qp["obs"] == "UNDETERMINED")}))

    # ---- §13 counterfactual: retain vs eliminate d at r=0, then zoom BOTH (D2)
    for d in K0.dims:
        KR, KD = K0, eliminate(K0, d.id)
        aR, _ = focus(KR, S_PRIMARY); xR, ancR = observe(aR)
        aD, _ = focus(KD, S_PRIMARY); xD, ancD = observe(aD)
        is_anchor = (d.id == ancR)
        for tau in DIRECTIONS:
            ZR, ZD = zoom(w, KR, ancR, tau, 1), zoom(w, KD, ancD, tau, 1)
            emit(rows, experiment_id="KR-ZOOM-01", split=split, case_id=rid, record="CF",
                 dimension=d.id, dtype=d.dtype, traversal_direction=tau,
                 eliminated_dim_is_anchor=is_anchor,
                 anchor_class=("ANCHOR" if is_anchor else "NON_ANCHOR"),
                 zero_at_r0=z0[d.id]["zero_at_current_resolution"],
                 obs_retain=xR, obs_delete=xD,
                 struct_retain=(dimset(ZR) if ZR else None),
                 struct_delete=(dimset(ZD) if ZD else None),
                 exposed_structure_differs=((dimset(ZR) if ZR else None) != (dimset(ZD) if ZD else None)))

def run_contrast(w, rid, rows):
    """CONTRAST arm: Q/S VARIES across resolution. Never pooled with the primary arm (D1)."""
    K0 = state_of(w, rid, 0)
    a0, _ = focus(K0, S_PRIMARY); x0, anc0 = observe(a0)
    for tau in DIRECTIONS:
        K1 = zoom(w, K0, anc0, tau, 1)
        if not nontrivial(K1): continue
        a1, _ = focus(K1, S_CONTRAST); x1, _ = observe(a1)
        z1 = {d.id: zero_test(K1, d.id, S_CONTRAST) for d in K1.dims}
        emit(rows, experiment_id="KR-ZOOM-01", split="contrast", case_id=rid, record="CONTRAST",
             traversal_direction=tau, obs_r0=x0, obs_r1=x1,
             focus_r0=list(S_PRIMARY), focus_r1=list(S_CONTRAST),
             nonzero_at_r1=[k for k, v in z1.items() if not v["zero_at_current_resolution"]])

def controls(w, rows, split):
    """§17 controls A-F, evaluated on the generated population."""
    S = S_PRIMARY; out = collections.Counter()
    for rid in w.roots:
        K0 = state_of(w, rid, 0); a0, ex0 = focus(K0, S); x0, anc0 = observe(a0)
        for tau in DIRECTIONS:
            K1 = zoom(w, K0, anc0, tau, 1)
            out[("terminal" if K1 is None else "structured", tau)] += 1
        inw = zoom(w, K0, anc0, "INWARD", 1); outw = zoom(w, K0, anc0, "OUTWARD", 1)
        if inw is None and nontrivial(outw): out["C_outward_only"] += 1
        for d in ex0:                                   # CONTROL E: visible-but-excluded
            zt = zero_test(K0, d.id, S)
            out["E_excluded_and_zero" if zt["zero_at_current_resolution"]
                else "E_excluded_but_NOT_zero"] += 1
    emit(rows, experiment_id="KR-ZOOM-01", split=split, record="CONTROLS",
         counts={f"{k}": v for k, v in out.items()})

if __name__ == "__main__":
    rows = []
    for split, seed in (("train", SEED_TRAIN), ("test", SEED_TEST)):
        w = World(seed, N_ROOTS, MAX_DEPTH)
        print(f"{split}: {len(w.nodes)} nodes from {N_ROOTS} roots", flush=True)
        for rid in w.roots: run_case(w, rid, split, rows)
        controls(w, rows, split)
        if split == "train":
            for rid in w.roots: run_contrast(w, rid, rows)
    os.makedirs(f"{ROOT}/data", exist_ok=True)
    with open(f"{ROOT}/data/ledger.jsonl", "w") as f:
        for r in rows: f.write(json.dumps(r, separators=(",", ":"), default=str) + "\n")
    man = {"experiment_id": "KR-ZOOM-01", "seeds": {"train": SEED_TRAIN, "test": SEED_TEST},
           "n_roots": N_ROOTS, "max_depth": MAX_DEPTH,
           "focus_primary": list(S_PRIMARY), "focus_contrast": list(S_CONTRAST),
           "Q_reads": list(Q_READS), "directions": list(DIRECTIONS),
           "python": sys.version.split()[0], "rows": len(rows)}
    os.makedirs(f"{ROOT}/manifests", exist_ok=True)
    json.dump(man, open(f"{ROOT}/manifests/manifest.json", "w"), indent=1)
    print(f"ledger rows: {len(rows)}")
