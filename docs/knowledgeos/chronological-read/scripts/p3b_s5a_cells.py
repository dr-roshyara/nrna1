#!/usr/bin/env python3
"""P3b S5a registered cells, pre-reveal pools and the H-17 label-cluster permutation test (frozen protocol v1.7 §9E.2
items 5-8, 11; S5 plan v2.3.2 §G.1-§G.3, §H.2-§H.5, §H.7 links 6-11, 13; G-LOG-0039, G-LOG-0041: K = 64).

INFRASTRUCTURE ONLY. Building and testing this script is authorized (G-LOG-0041); executing S5a research is not.

(a) Registered cells: 5 counted generators x 14 classes - 6 DESCRIPTIVE = K = 64, stable cell_id "<generator>:<class>".
    No cell is structurally impossible (plan §G.2; ISC is not an identity class, G-LOG-0041).
(b) Pre-reveal pools (link 6) per cell: the frozen test family and its controls after hub unscoring (absence-based
    classes MISSING-TRANSITION / MISSING-INVARIANT: units containing a hub) and RC-13 unscoring (EQUIVALENCE-CLASS where
    it is DISCOVERY: units containing a P3a-judged pair), with the §H.4 closed form applied to those structural
    statuses. Written as S5A-POOLS-PREREVEAL.json (§19.5 header); its sha256 goes to the pass record, and the reveal
    refuses unless the file matches it.
(c) Symmetric removal (§H.4, closed form): a matched set is retained iff its candidate is determined and it keeps
    >= 1 determined control; undetermined controls leave every set; attrition by role and cause.
(d) Blocks: label components over the retained distinct units x exact arity (x has-formal-rows profile, G-TYPE-SIM).
(e) T = candidate units showing the class in informative blocks; exact p = P(sum X_b >= T), X_b ~ Hypergeometric
    (N_b, K_b, n_C,b), by integer convolution (exact rational arithmetic). No randomness.
(f) Gating: TESTED iff m >= 20, x_min >= 3, m_inf >= 1 and blinding FULL; precedence PARTIAL, then UNDERPOWERED.
(g) BH at q = 0.10 over exactly the 64 registered cells (untested p = 1); BY as a sensitivity only.
(h) BCDD at q/K and at 0.05 by seeded simulation (root 20261011; stream spawn_key (cell index, level index, step);
    grid 0.01; 2,000 tables per step; alternative per §H.5; NOT-REACHED / NOT-COMPUTABLE).
(i) S5A-CELL-RESULTS.json with a §19.5 header.

  python3 p3b_s5a_cells.py pools  --candidates C.jsonl --out POOLS.json --pass-record PR.json
  python3 p3b_s5a_cells.py reveal --pools POOLS.json --pass-record PR.json --reveal REVEAL.jsonl \
                                  --dispositions DISP.jsonl --out RESULTS.json
"""
import argparse
import json
import math
import os
import sys
from fractions import Fraction

import numpy as np

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402

GENERATORS = ("G-SHARED-GROUP", "G-DEPENDENCY", "G-NOTATION", "G-COCHANGE", "G-TYPE-SIM")
CLASSES = ("STATE-MACHINE-CANDIDATE", "ORDER-OR-LATTICE-CANDIDATE", "EQUIVALENCE-CLASS", "COMPOSITIONAL-STRUCTURE",
           "PROBABILISTIC-STRUCTURE", "MEASUREMENT-SCALE", "LOGICAL-ENTAILMENT", "DDD-BOUNDARY-CANDIDATE",
           "IDENTITY-STATE-CONFLATION", "SHARED-INVARIANT", "SHARED-TRANSITION", "MISSING-TRANSITION",
           "MISSING-INVARIANT", "DEPENDENCY-CHAIN")                       # plan §G.1, numbered 1..14
DESCRIPTIVE = {"G-SHARED-GROUP": ("EQUIVALENCE-CLASS",), "G-NOTATION": ("EQUIVALENCE-CLASS",),
               "G-TYPE-SIM": ("EQUIVALENCE-CLASS",),
               "G-DEPENDENCY": ("DEPENDENCY-CHAIN", "ORDER-OR-LATTICE-CANDIDATE"),
               "G-COCHANGE": ("SHARED-TRANSITION",)}                     # plan §G.3
IDENTITY_CLASSES = ("EQUIVALENCE-CLASS",)                                 # G-LOG-0041: ISC is not an identity class
ABSENCE_CLASSES = ("MISSING-TRANSITION", "MISSING-INVARIANT")
K_REGISTERED = 64
Q = Fraction(1, 10)
M_MIN, X_MIN, M_INF_MIN = 20, 3, 1
BCDD_ROOT = 20261011
BCDD_GRID = Fraction(1, 100)
BCDD_TABLES = 2000
BCDD_POWER = 0.8
BAND_VARS = C.BAND_VARS
FORMAL_VAR = "has_formal_rows"


def parameters():
    return {"K": K_REGISTERED, "q": "0.10", "bh_family": "the 64 registered cells; untested p = 1",
            "by": "sensitivity only (q / sum_{i<=K} 1/i)", "m_min": M_MIN, "x_min": X_MIN, "m_inf_min": M_INF_MIN,
            "blinding_required": "FULL", "bcdd": {"root": BCDD_ROOT, "grid": "0.01", "tables_per_step": BCDD_TABLES,
            "power": BCDD_POWER, "levels": ["q/K = 0.10/64", "0.05"], "stream":
            "SeedSequence(20261011, spawn_key=(cell index, level index, step))",
            "alternative": "candidate unit in informative block b shows c w.p. min(1, phat_b + delta); controls phat_b; "
                           "independent; reject iff exact p <= level"},
            "descriptive": {g: list(v) for g, v in DESCRIPTIVE.items()}, "identity_classes": list(IDENTITY_CLASSES),
            "absence_classes": list(ABSENCE_CLASSES)}


# ------------------------------------------------------------------ (a) registered cells
def registered_cells():
    cells = []
    for g in GENERATORS:
        for n, c in enumerate(CLASSES, start=1):
            if c in DESCRIPTIVE[g]:
                continue
            cells.append({"cell_index": len(cells), "cell_id": f"{g}:{c}", "generator": g, "class_no": n, "class": c,
                          "basis": "DISCOVERY"})
    if len(cells) != K_REGISTERED:
        raise C.S5Error(f"registered cells {len(cells)} != K = {K_REGISTERED}")
    return cells


def basis(generator, cls):
    if cls not in CLASSES:
        return "UNMAPPED"
    return "DESCRIPTIVE" if cls in DESCRIPTIVE.get(generator, ()) else "DISCOVERY"


def unscoring_rule(generator, cls):
    if cls in ABSENCE_CLASSES:
        return "HUB"
    if cls in IDENTITY_CLASSES and basis(generator, cls) == "DISCOVERY":
        return "RC-13"
    return None


def set_key(members):
    """Internal key of a distinct set (its sorted members). Not the blind file's `unit_key`."""
    return "|".join(members)


# ------------------------------------------------------------------ dispositions (the ONE schema; G-LOG-0042 M3)
DISPOSITIONS = ("ANALYSED", "DEFERRED", "FAILED")
CLASS_VALUES = ("RECORDED", "NOT-RECORDED", "UNDETERMINED")
DISPOSITION_SCHEMA = {   # exported for the pass contract (JSON Schema draft 2020-12); one JSON object per line
    "$schema": "https://json-schema.org/draft/2020-12/schema",
    "$id": "p3b-s5a-disposition-v1",
    "title": "S5a disposition of one blinded unit",
    "type": "object",
    "additionalProperties": False,
    "required": ["unit_key", "disposition"],
    "properties": {
        "unit_key": {"type": "string", "pattern": "^U[0-9]{6}$",
                     "description": "the unit_key of the blind file (P3B-CROSS-BLIND.jsonl)"},
        "disposition": {"enum": list(DISPOSITIONS)},
        "reason": {"type": "string", "description": "required for DEFERRED and FAILED"},
        "classes": {"type": "object", "additionalProperties": False,
                    "properties": {c: {"enum": list(CLASS_VALUES)} for c in CLASSES},
                    "description": "per pre-registered class: RECORDED (at >= ANALYSED) | NOT-RECORDED | UNDETERMINED. "
                                   "A class missing here is UNDETERMINED, never NOT-RECORDED (G-LOG-0039)."},
    },
    "allOf": [{"if": {"properties": {"disposition": {"enum": ["DEFERRED", "FAILED"]}}},
               "then": {"required": ["reason"],
                        "properties": {"classes": {"type": "object",
                                                   "additionalProperties": {"const": "UNDETERMINED"}}}}}],
    "x-semantics": ["a missing class is UNDETERMINED (UNDETERMINED is not 0)",
                    "DEFERRED / FAILED: undetermined for every class",
                    "unknown keys, dispositions, classes or values are refused, never coerced",
                    "every blinded unit has exactly one disposition; duplicates and unknown unit_keys are refused",
                    "UNMAPPED classes are recorded in the research register, not here"],
}


def validate_disposition(d):
    """Refuses anything outside DISPOSITION_SCHEMA (no coercion). Returns d."""
    if not isinstance(d, dict):
        raise C.S5Error("disposition: not an object")
    extra = set(d) - {"unit_key", "disposition", "reason", "classes"}
    if extra:
        raise C.S5Error(f"disposition: unknown field(s) {sorted(extra)}")
    uk = d.get("unit_key")
    if not isinstance(uk, str) or not (len(uk) == 7 and uk[0] == "U" and uk[1:].isdigit()):
        raise C.S5Error(f"disposition: invalid unit_key {uk!r}")
    if d.get("disposition") not in DISPOSITIONS:
        raise C.S5Error(f"disposition {uk}: unknown disposition {d.get('disposition')!r}")
    if "reason" in d and not isinstance(d["reason"], str):
        raise C.S5Error(f"disposition {uk}: reason must be a string")
    cls = d.get("classes", {})
    if not isinstance(cls, dict):
        raise C.S5Error(f"disposition {uk}: classes must be an object")
    for k, v in cls.items():
        if k not in CLASSES:
            raise C.S5Error(f"disposition {uk}: unknown class {k!r}")
        if v not in CLASS_VALUES:
            raise C.S5Error(f"disposition {uk}: unknown value {v!r} for {k}")
    if d["disposition"] in ("DEFERRED", "FAILED"):
        if not d.get("reason"):
            raise C.S5Error(f"disposition {uk}: {d['disposition']} requires a reason")
        if any(v != "UNDETERMINED" for v in cls.values()):
            raise C.S5Error(f"disposition {uk}: {d['disposition']} cannot record or reject a class")
    return d


def validate_dispositions(dispositions, blind_keys):
    """Exactly one valid disposition per blinded unit. -> {unit_key: disposition}."""
    out = {}
    for d in dispositions:
        validate_disposition(d)
        if d["unit_key"] in out:
            raise C.S5Error(f"disposition: duplicate unit_key {d['unit_key']}")
        out[d["unit_key"]] = d
    unknown = set(out) - set(blind_keys)
    if unknown:
        raise C.S5Error(f"disposition: {len(unknown)} unit_key(s) not in the blind file")
    missing = set(blind_keys) - set(out)
    if missing:
        raise C.S5Error(f"REFUSED: {len(missing)} blinded unit(s) not dispositioned (the reveal needs every set)")
    return out


# ------------------------------------------------------------------ (c) symmetric removal (closed form)
def symmetric_removal(matched_sets, status):
    """matched_sets: [{candidate_id, candidate: unit_key, controls: [unit_key, ...]}];
    status(unit_key) -> None if determined, else a cause string. Returns (retained, attrition)."""
    retained, lost = [], []
    bad_controls = {}
    for ms in matched_sets:
        for u in ms["controls"]:
            st = status(u)
            if st is not None:
                bad_controls[u] = st
    for ms in matched_sets:
        st = status(ms["candidate"])
        if st is not None:
            lost.append({"candidate_id": ms["candidate_id"], "cause": "CANDIDATE-" + st})
            continue
        ctl = [u for u in ms["controls"] if u not in bad_controls]
        if not ctl:
            lost.append({"candidate_id": ms["candidate_id"], "cause": "NO-DETERMINED-CONTROL"})
            continue
        retained.append({**ms, "controls": ctl})
    kept_controls = {u for ms in retained for u in ms["controls"]}
    lost_ids = {x["candidate_id"] for x in lost}
    with_set = sorted({u for ms in matched_sets if ms["candidate_id"] in lost_ids for u in ms["controls"]
                       if u not in kept_controls and u not in bad_controls})
    by_cause = {}
    for x in lost:
        by_cause[x["cause"]] = by_cause.get(x["cause"], 0) + 1
    ctl_cause = {}
    for u, st in bad_controls.items():
        ctl_cause["CONTROL-" + st] = ctl_cause.get("CONTROL-" + st, 0) + 1
    attrition = {"sets_in": len(matched_sets), "sets_retained": len(retained), "sets_lost": len(lost),
                 "candidates_removed": len(lost),
                 "candidates_removed_undetermined_or_unscored": sum(1 for x in lost if x["cause"].startswith("CANDIDATE-")),
                 "sets_lost_no_determined_control": sum(1 for x in lost if x["cause"] == "NO-DETERMINED-CONTROL"),
                 "controls_removed_undetermined_or_unscored": len(bad_controls),
                 "controls_removed_with_their_set": len(with_set),
                 "controls_kept_serving_another_retained_set": len({u for ms in matched_sets
                                                                    if ms["candidate_id"] in lost_ids
                                                                    for u in ms["controls"] if u in kept_controls}),
                 "by_cause_sets": by_cause, "by_cause_controls": ctl_cause, "lost_sets": lost}
    return retained, attrition


# ------------------------------------------------------------------ (b) pre-reveal pools
def build_pools(cand_records, ctrl_records, hubs, judged, withheld=()):
    """`withheld`: member tuples withheld under the M4 residual rule (p3b_s5a_controls.withheld_sets). They are not in
    the blind file, so they are removed symmetrically (cause WITHHELD-M4R1) from every cell containing them, before the
    hub / RC-13 unscoring rule."""
    hubs, judged = frozenset(hubs), frozenset(frozenset(p) for p in judged)
    withheld = frozenset(set_key(m) for m in withheld)
    by_cand = {}
    for r in ctrl_records:
        if r["record_type"] == "CONTROL":
            by_cand.setdefault(r["for_candidate"], []).append(r)
    draws = {r["candidate_id"]: r for r in ctrl_records if r["record_type"] == "CONTROL-DRAW"}
    fam = {g: [r for r in cand_records if r["generator"] == g and r.get("in_test_family")] for g in GENERATORS}
    nca = {g: sum(1 for r in cand_records if r["generator"] == g and r.get("in_budget")
                  and draws.get(r["candidate_id"], {}).get("status") == "NO-CONTROL-AVAILABLE") for g in GENERATORS}
    relaxed = {g: sum(1 for r in ctrl_records if r["record_type"] == "CONTROL" and r["generator"] == g
                      and r["matching"]["relaxation_level"] > 0) for g in GENERATORS}
    units = {}

    def reg(members):
        units[set_key(members)] = list(members)
        return set_key(members)

    base = {}
    for g in GENERATORS:
        base[g] = []
        for r in fam[g]:
            ctl = sorted(by_cand.get(r["candidate_id"], []), key=lambda x: x["draw_index"])
            if not ctl:
                raise C.S5Error(f"family candidate {r['candidate_id']} has no control (NO-CONTROL-AVAILABLE must be dropped)")
            base[g].append({"candidate_id": r["candidate_id"], "candidate": reg(r["members"]),
                            "controls": [reg(x["members"]) for x in ctl],
                            "control_ids": [x["control_id"] for x in ctl]})

    def has_hub(u):
        return any(m in hubs for m in units[u])

    def has_judged(u):
        m = units[u]
        return any(frozenset((a, b)) in judged for i, a in enumerate(m) for b in m[i + 1:])

    cells = []
    for cell in registered_cells():
        rule = unscoring_rule(cell["generator"], cell["class"])
        test = has_hub if rule == "HUB" else has_judged if rule == "RC-13" else (lambda u: False)
        cause = "UNSCORED-HUB" if rule == "HUB" else "UNSCORED-RC13"
        retained, attr = symmetric_removal(base[cell["generator"]],
                                           lambda u: "WITHHELD-M4R1" if u in withheld else cause if test(u) else None)
        cells.append({**cell, "unscoring_rule": rule, "matched_sets": retained, "structural_attrition": attr,
                      "m": len(retained), "family_size": len(base[cell["generator"]]),
                      "nca_dropped": nca[cell["generator"]], "controls_relaxed": relaxed[cell["generator"]]})
    return {"artifact": "S5A-POOLS-PREREVEAL.json", "K": K_REGISTERED, "cells": cells, "units": units,
            "m4_residual_withheld_units": len(withheld)}


# ------------------------------------------------------------------ (d) blocks and (e) exact test
def components(unit_members):
    """unit_members: {unit_key: members}. Union-find over labels; returns {unit_key: component id (min label)}."""
    parent = {}

    def find(x):
        parent.setdefault(x, x)
        while parent[x] != x:
            parent[x] = parent[parent[x]]
            x = parent[x]
        return x
    for m in unit_members.values():
        for lab in m[1:]:
            a, b = find(m[0]), find(lab)
            if a != b:
                parent[max(a, b)] = min(a, b)
    return {u: find(m[0]) for u, m in unit_members.items()}


def build_blocks(units, generator, bands=None):
    """units: {unit_key: {"members", "role": "C"|"K", "y": 0|1}} -> {block key: [unit_key, ...]}."""
    comp = components({u: v["members"] for u, v in units.items()})
    blocks = {}
    for u in sorted(units):
        m = units[u]["members"]
        key = (comp[u], len(m))
        if generator == "G-TYPE-SIM":
            key = key + (tuple(sorted(bool(bands[x][FORMAL_VAR]) for x in m)),)
        blocks.setdefault(key, []).append(u)
    return blocks


def exact_tail(blocks, t_obs):
    """P(sum_b X_b >= t_obs), X_b ~ Hypergeometric(N_b, K_b, n_b) independent; blocks = [(N, K, n)]. Exact Fraction."""
    num, den = [1], 1
    for N, K, n in blocks:
        pm = [math.comb(K, x) * math.comb(N - K, n - x) for x in range(n + 1)]
        den *= math.comb(N, n)
        out = [0] * (len(num) + n)
        for i, a in enumerate(num):
            if a:
                for x, b in enumerate(pm):
                    if b:
                        out[i + x] += a * b
        num = out
    return Fraction(sum(num[max(t_obs, 0):]), den)


def block_table(units, blocks):
    rows = []
    for key, us in sorted(blocks.items(), key=lambda kv: (str(kv[0]))):
        n_c = sum(1 for u in us if units[u]["role"] == "C")
        rows.append({"block": list(key[:2]) + ([list(key[2])] if len(key) > 2 else []), "N": len(us), "n_C": n_c,
                     "K": sum(units[u]["y"] for u in us), "t": sum(units[u]["y"] for u in us if units[u]["role"] == "C"),
                     "informative": 0 < n_c < len(us)})
    return rows


# ------------------------------------------------------------------ (h) BCDD
def _log_comb(n, k):
    return math.lgamma(n + 1) - math.lgamma(k + 1) - math.lgamma(n - k + 1)


def _hyper_matrix(N, n):
    H = np.zeros((N + 1, n + 1))
    for K in range(N + 1):
        for x in range(max(0, n - (N - K)), min(n, K) + 1):
            H[K, x] = math.exp(_log_comb(K, x) + _log_comb(N - K, n - x) - _log_comb(N, n))
    return H


def tail_vectorized(blocks, Ks, T):
    """Float (log-space pmf) exact-convolution tail for many tables. blocks = [(N, n)], Ks (tables x B), T (tables)."""
    L = sum(n for _, n in blocks)
    dist = np.zeros((Ks.shape[0], L + 1))
    dist[:, 0] = 1.0
    used = 0
    for b, (N, n) in enumerate(blocks):
        Hk = _hyper_matrix(N, n)[Ks[:, b]]
        new = np.zeros_like(dist)
        for x in range(n + 1):
            new[:, x:used + x + 1] += dist[:, :used + 1] * Hk[:, x:x + 1]
        dist = new
        used += n
    rev = np.cumsum(dist[:, ::-1], axis=1)[:, ::-1]
    return rev[np.arange(Ks.shape[0]), np.minimum(T, L)] * (T <= L)


def bcdd(sim_blocks, level, cell_index, level_index, tables=BCDD_TABLES):
    """sim_blocks: [(n_C, n_K, phat)] for the informative blocks. Smallest delta (grid 0.01) with power >= 0.8."""
    if not sim_blocks:
        return {"status": "NOT-COMPUTABLE", "reason": "no informative block"}
    nC = np.array([b[0] for b in sim_blocks])
    nK = np.array([b[1] for b in sim_blocks])
    ph = np.array([b[2] for b in sim_blocks], dtype=float)
    shape = [(int(c + k), int(c)) for c, k in zip(nC, nK)]
    lvl = float(level)
    step = 1
    while True:
        delta = float(BCDD_GRID * step)
        rng = np.random.Generator(np.random.PCG64(np.random.SeedSequence(BCDD_ROOT, spawn_key=(cell_index, level_index, step))))
        pc = np.minimum(1.0, ph + delta)
        xc = rng.binomial(nC, pc, size=(tables, len(sim_blocks)))
        xk = rng.binomial(nK, ph, size=(tables, len(sim_blocks)))
        p = tail_vectorized(shape, xc + xk, xc.sum(axis=1))
        power = float(np.mean(p <= lvl))
        if power >= BCDD_POWER:
            return {"status": "REACHED", "bcdd": round(delta, 2), "power_at_bcdd": power, "steps": step,
                    "stream": {"root": BCDD_ROOT, "spawn_key": [cell_index, level_index, "step"]}}
        if bool(np.all(ph + delta >= 1.0 - 1e-12)):
            return {"status": "NOT-REACHED", "last_delta": round(delta, 2), "power_at_last": power, "steps": step,
                    "stream": {"root": BCDD_ROOT, "spawn_key": [cell_index, level_index, "step"]}}
        step += 1


# ------------------------------------------------------------------ per-cell analysis
def outcome_status(disp_by_unit, u, cls):
    """-> (y or None, cause) per DISPOSITION_SCHEMA. DEFERRED/FAILED units, UNDETERMINED classes and classes MISSING
    from `classes` are undetermined, never 0. Unknown values are refused."""
    d = disp_by_unit.get(u)
    if d is None:
        raise C.S5Error(f"unit {u} has no disposition (the reveal requires every set dispositioned)")
    validate_disposition(d)
    if d["disposition"] in ("DEFERRED", "FAILED"):
        return None, "UNDETERMINED-" + d["disposition"]
    v = d.get("classes", {}).get(cls)
    if v == "RECORDED":
        return 1, None
    if v == "NOT-RECORDED":
        return 0, None
    return None, "UNDETERMINED" if v == "UNDETERMINED" else "UNDETERMINED-CLASS-MISSING"


def balance_table(units, bands, generator):
    vars_ = list(BAND_VARS) + ([FORMAL_VAR] if generator == "G-TYPE-SIM" else [])
    out = {}
    for v in vars_:
        t = {"candidate": {}, "control": {}}
        for u in sorted(units):
            side = "candidate" if units[u]["role"] == "C" else "control"
            for m in units[u]["members"]:
                b = str(bands[m][v]) if bands and m in bands else "UNKNOWN"
                t[side][b] = t[side].get(b, 0) + 1
        out[v] = t
    return out


def reuse_stats(retained, units):
    serve = {}
    for ms in retained:
        for u in ms["controls"]:
            serve[u] = serve.get(u, 0) + 1
    lab_units = {}
    for u in serve:
        for m in units[u]["members"]:
            lab_units[m] = lab_units.get(m, 0) + 1
    cand_labels = {m for ms in retained for m in units[ms["candidate"]]["members"]}
    return {"control_units_distinct": len(serve), "control_slots": sum(serve.values()),
            "max_sets_served_by_one_control_unit": max(serve.values()) if serve else 0,
            "control_units_reused": sum(1 for v in serve.values() if v > 1),
            "control_labels_distinct": len(lab_units), "max_control_units_per_label": max(lab_units.values()) if lab_units else 0,
            "control_labels_also_candidate_labels": len(set(lab_units) & cand_labels)}


def analyse_cell(cell, unit_members, disp_by_unit, blinding_level, bands=None, do_bcdd=True, tables=BCDD_TABLES):
    cls, g = cell["class"], cell["generator"]
    causes = {}

    def status(u):
        y, cause = outcome_status(disp_by_unit, u, cls)
        causes[u] = (y, cause)
        return cause if y is None else None
    retained, attr = symmetric_removal(cell["matched_sets"], status)
    units = {}
    for ms in retained:
        units[ms["candidate"]] = {"members": unit_members[ms["candidate"]], "role": "C", "y": causes[ms["candidate"]][0]}
    for ms in retained:
        for u in ms["controls"]:
            if u in units and units[u]["role"] == "C":
                raise C.S5Error(f"unit {u} is both candidate and control in {cell['cell_id']}")
            units[u] = {"members": unit_members[u], "role": "K", "y": causes[u][0]}
    blocks = build_blocks(units, g, bands)
    table = block_table(units, blocks)
    inf = [b for b in table if b["informative"]]
    T = sum(b["t"] for b in inf)
    m_inf = sum(b["n_C"] for b in inf)
    p_exact = exact_tail([(b["N"], b["K"], b["n_C"]) for b in inf], T) if inf else None
    m = cell["m"]
    if blinding_level != "FULL":
        test_status, reason = "REPORT-ONLY-PARTIAL-BLIND", f"blinding_level {blinding_level}"
    elif not (m >= M_MIN and T >= X_MIN and m_inf >= M_INF_MIN):
        test_status, reason = "UNDERPOWERED", f"m={m} (>= {M_MIN}), x_min={T} (>= {X_MIN}), m_inf={m_inf} (>= {M_INF_MIN})"
    else:
        test_status, reason = "TESTED", None
    p_bh = p_exact if test_status == "TESTED" else Fraction(1)
    cand = [u for u in units if units[u]["role"] == "C"]
    ctl = [u for u in units if units[u]["role"] == "K"]
    pc = Fraction(sum(units[u]["y"] for u in cand), len(cand)) if cand else None
    pk = Fraction(sum(units[u]["y"] for u in ctl), len(ctl)) if ctl else None
    sim_blocks = [(b["n_C"], b["N"] - b["n_C"], (b["K"] - b["t"]) / (b["N"] - b["n_C"])) for b in inf]
    res = {k: cell[k] for k in ("cell_index", "cell_id", "generator", "class_no", "class", "basis", "unscoring_rule")}
    res.update({
        "pool": {"m": m, "family_size": cell.get("family_size"), "nca_dropped": cell.get("nca_dropped"),
                 "controls_relaxed": cell.get("controls_relaxed"), "structural_attrition": cell.get("structural_attrition")},
        "attrition": attr,
        "counts": {"candidate_units": len(cand), "control_units": len(ctl),
                   "candidate_showing": sum(units[u]["y"] for u in cand), "control_showing": sum(units[u]["y"] for u in ctl),
                   "candidate_proportion": float(pc) if pc is not None else None,
                   "control_proportion": float(pk) if pk is not None else None,
                   "difference": float(pc - pk) if pc is not None and pk is not None else None},
        "blocks": {"n_blocks": len(table), "informative": len(inf), "uninformative": len(table) - len(inf), "table": table},
        "T": T, "x_min_count": T, "m_inf": m_inf, "m_inf_below_20": m_inf < 20, "blinding_level": blinding_level,
        "test_status": test_status, "status_reason": reason,
        "p_exact": float(p_exact) if p_exact is not None else None,
        "p_exact_fraction": (f"{p_exact.numerator}/{p_exact.denominator}"
                             if p_exact is not None and len(str(p_exact.denominator)) <= 300 else None),
        "p_bh_input": float(p_bh), "_p_bh_input_exact": p_bh,
        "balance_table": balance_table(units, bands, g), "reuse": reuse_stats(retained, units)})
    if do_bcdd:
        res["bcdd"] = {"q_over_K": bcdd(sim_blocks, Q / K_REGISTERED, cell["cell_index"], 0, tables),
                       "alpha_0.05": bcdd(sim_blocks, Fraction(5, 100), cell["cell_index"], 1, tables)}
    return res


# ------------------------------------------------------------------ (g) multiplicity
def bh_by(results, q=Q):
    K = len(results)
    if K != K_REGISTERED:
        raise C.S5Error("the BH family must be exactly the registered cells")
    order = sorted(results, key=lambda r: (r["_p_bh_input_exact"], r["cell_index"]))
    hk = sum(Fraction(1, i) for i in range(1, K + 1))
    kmax_bh = max([i for i, r in enumerate(order, 1) if r["_p_bh_input_exact"] <= i * q / K] or [0])
    kmax_by = max([i for i, r in enumerate(order, 1) if r["_p_bh_input_exact"] <= i * q / (K * hk)] or [0])
    adj, run = {}, Fraction(1)
    for i in range(K, 0, -1):
        run = min(run, order[i - 1]["_p_bh_input_exact"] * K / i)
        adj[order[i - 1]["cell_id"]] = min(run, Fraction(1))
    for i, r in enumerate(order, 1):
        r["bh"] = {"rank": i, "threshold": float(i * q / K), "reject": i <= kmax_bh, "p_adjusted": float(adj[r["cell_id"]])}
        r["by_sensitivity"] = {"rank": i, "threshold": float(i * q / (K * hk)), "reject": i <= kmax_by}
    for r in results:
        if r["test_status"] == "TESTED":
            r["outcome"] = "DIFFERENTIATED" if r["bh"]["reject"] else "UNDIFFERENTIATED"
        else:
            r["outcome"] = r["test_status"]
            if r["bh"]["reject"]:
                raise C.S5Error("an untested cell cannot be rejected (p = 1)")
    return {"K": K, "q": float(q), "cells": [r["cell_id"] for r in sorted(results, key=lambda r: r["cell_index"])],
            "p_inputs": {r["cell_id"]: r["p_bh_input"] for r in results}, "bh_rejections": kmax_bh,
            "by_rejections": kmax_by, "by_harmonic_H_K": float(hk), "bonferroni_calibrated_level": float(q / K),
            "note": "BH decides; BY is reported as a sensitivity analysis and decides nothing"}


def analyse(pools_body, reveal_records, dispositions, blinding, bands=None, do_bcdd=True, tables=BCDD_TABLES):
    by_members = {set_key(r["members"]): r["unit_key"] for r in reveal_records}
    disp = validate_dispositions(dispositions, [r["unit_key"] for r in reveal_records])
    disp_by_unit = {u: disp[k] for u, k in by_members.items()}
    results = []
    for cell in pools_body["cells"]:
        lvl = blinding.get(cell["generator"], "PARTIAL")
        results.append(analyse_cell(cell, pools_body["units"], disp_by_unit, lvl, bands, do_bcdd, tables))
    fam = bh_by(results)
    for r in results:
        r.pop("_p_bh_input_exact")
    return {"artifact": "S5A-CELL-RESULTS.json", "K": K_REGISTERED, "cells": results, "family": fam,
            "registered_cells": [c["cell_id"] for c in registered_cells()]}


# ------------------------------------------------------------------ CLI
def _committed(path):
    ap = os.path.abspath(path)
    if not ap.startswith(C.REPO_ROOT + os.sep):
        return None                                        # /tmp build artifact: git commit status not applicable
    tracked = C.git("ls-files", "--", ap) != ""
    return tracked and C.git("status", "--porcelain", "--", ap) == ""


def main(argv=None):
    import p3b_s5a_controls as K
    import p3b_s5a_generators as G
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="cmd", required=True)
    p1 = sub.add_parser("pools")
    p1.add_argument("--candidates", required=True)
    p1.add_argument("--out", default="/tmp/s5a-build/S5A-POOLS-PREREVEAL.json")
    p1.add_argument("--pass-record", default="/tmp/s5a-build/S5A-PASS-RECORD.json")
    p1.add_argument("--real-pass", action="store_true")
    p2 = sub.add_parser("reveal")
    for x in ("--pools", "--pass-record", "--reveal", "--dispositions", "--candidates"):
        p2.add_argument(x, required=True)
    p2.add_argument("--pass-snapshot", default=None, help="P3B-PASS-SNAPSHOT.json (link 1); required with --real-pass")
    p2.add_argument("--out", default="/tmp/s5a-build/S5A-CELL-RESULTS.json")
    p2.add_argument("--real-pass", action="store_true")
    p2.add_argument("--no-bcdd", action="store_true")
    a = ap.parse_args(argv)
    C.assert_sealed()
    C.verify_frozen()
    G.safe_out(a.out, a.real_pass)
    G.safe_out(a.pass_record, a.real_pass) if a.cmd == "pools" else K.check_artifact_path(a.pass_record, a.real_pass)
    if a.cmd == "pools":
        cpath = K.check_artifact_path(a.candidates, a.real_pass)
        ch, recs = G.read_jsonl_artifact(cpath)
        cand = [r for r in recs if r["record_type"] == "CANDIDATE"]
        ctrl = [r for r in recs if r["record_type"] in ("CONTROL", "CONTROL-DRAW")]
        judged = [frozenset((p["a"], p["b"])) for p in C.discovery_pairs()]
        withheld = K.withheld_sets(G.Ctx.load(), cand, ctrl)                   # M4 residual rule (same set as the blind)
        m4r1 = K.m4r1_record(withheld)
        K.check_m4r1_binding(K.pass_record_frozen(a.pass_record, "PRE-ANALYSIS-BLIND-REVEAL", a.real_pass).get("m4_residual"),
                             m4r1)                                               # pools use the blind's withheld set
        body = build_pools(cand, ctrl, C.hubs(), judged, withheld)
        txt = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
        h = C.header(__file__, [C.HUBS, "31-RECONCILIATION-PAIRS.jsonl", "P3B-DISCOVERY-SEARCH.jsonl"],
                     {**parameters(), "candidates_output_sha256": ch["output_sha256"]}, txt,
                     extra={"artifact": "S5A-POOLS-PREREVEAL.json", "link": 6})
        K.write_json_artifact(a.out, h, body)
        K.pass_record_append(a.pass_record, "LINK6-POOLS-PREREVEAL",
                             {"pools_file_sha256": C.sha256_file(a.out), "pools_output_sha256": h["output_sha256"],
                              "m4_residual": m4r1},
                             __file__)
        print(json.dumps({"out": a.out, "m": {c["cell_id"]: c["m"] for c in body["cells"]}}, indent=0))
    else:
        rp = a.real_pass
        pools_path = K.check_artifact_path(a.pools, rp)
        want = K.pass_record_frozen(a.pass_record, "LINK6-POOLS-PREREVEAL", rp)     # the first and only LINK6 entry
        if C.sha256_file(pools_path) != want["pools_file_sha256"]:
            raise C.S5Error("REFUSED: S5A-POOLS-PREREVEAL.json does not match the hash in the pass record")
        if _committed(a.pass_record) is False:
            raise C.S5Error("REFUSED: the pass record holding the pre-reveal hash is not committed")
        pre = K.pass_record_frozen(a.pass_record, "PRE-ANALYSIS-BLIND-REVEAL", rp)
        rpath = K.check_artifact_path(a.reveal, rp)
        if C.sha256_file(rpath) != pre["reveal_file_sha256"]:
            raise C.S5Error("REFUSED: the reveal file does not match the hash in the pass record")
        cpath = K.check_artifact_path(a.candidates, rp)                             # link 3
        if C.sha256_file(cpath) != pre["link3_candidates_file_sha256"]:
            raise C.S5Error("REFUSED: the candidates file does not match the link-3 hash in the pass record")
        if rp and not a.pass_snapshot:
            raise C.S5Error("--real-pass requires --pass-snapshot (link 1)")
        spath = K.check_artifact_path(a.pass_snapshot, rp) if a.pass_snapshot else None   # link 1
        if spath and pre.get("link1_pass_snapshot_sha256") not in (None, C.sha256_file(spath)):
            raise C.S5Error("REFUSED: the pass snapshot does not match the link-1 hash in the pass record")
        _, pools_body = K.read_json_artifact(pools_path)
        rh, reveal = G.read_jsonl_artifact(rpath)
        K.check_m4r1_binding(rh["parameters"].get("m4_residual"), pre.get("m4_residual"),
                             want.get("m4_residual"))                            # reveal header, PRE entry, LINK6 entry
        dpath = K.check_artifact_path(a.dispositions, rp)
        with open(dpath, encoding="utf-8") as f:
            disp = [json.loads(l) for l in f if l.strip()]
        body = analyse(pools_body, reveal, disp, pre["blinding_level"], C.label_bands(), not a.no_bcdd)
        body["link6_pools_sha256"] = want["pools_file_sha256"]
        txt = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
        links = ([spath] if spath else []) + [cpath, os.path.abspath(a.pass_record), pools_path, rpath, dpath]
        h = C.header(__file__, links, parameters(), txt,
                     extra={"artifact": "S5A-CELL-RESULTS.json", "seed": {"bcdd_root": BCDD_ROOT},
                            "link6_pools_sha256": want["pools_file_sha256"],
                            "provenance_links": {"1_pass_snapshot": spath, "2_3_candidates": cpath,
                                                 "3_pass_record": os.path.abspath(a.pass_record), "6_pools": pools_path,
                                                 "7_reveal": rpath, "7_dispositions": dpath},
                            "disposition_schema_id": DISPOSITION_SCHEMA["$id"]})
        K.write_json_artifact(a.out, h, body)
        print(json.dumps({"out": a.out, "output_sha256": h["output_sha256"]}))
    C.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
