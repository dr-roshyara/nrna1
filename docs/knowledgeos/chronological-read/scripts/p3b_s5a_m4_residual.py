#!/usr/bin/env python3
"""P3b S5a M4-A residual blinding analysis (human ruling after G-LOG-0044: "perform a focused blinding analysis of
this residual"). DESIGN EVIDENCE ONLY: runs in memory on the pre-S5 design data (the same generators, controls and
blind build as the S5a engine); no S5 outcome exists or is read; no artifact is written; counts and AUCs only.

It measures the pointer-derived features of a blind unit (evidence pointers per member; arity is matched, and label-name
semantics and anchors are not measured) for G-SHARED-GROUP
candidate vs control units, with M4-A off and on, and simulates candidate remediations:
  R1        withhold (symmetrically, both roles) every G-SHARED-GROUP-family unit with a member that has no pointer
  R1+cap c  R1, and show at most c pointers per member (deterministic: lowest (source_id, row_line))
  implemented_A1 the committed engine (M4-A + R1 + cap 1, scope A'1 per the §26 annex), measured directly
AUC = P(feature(candidate) > feature(control)) + ties/2, reported as max(AUC, 1 - AUC) (0.5 = no signal).

  python3 p3b_s5a_m4_residual.py        prints one JSON document (design data)
  python3 p3b_s5a_m4_residual.py gate --blind B --reveal R --pools P --pass-record PR [--out O] [--real-pass]
      REGISTERED PRE-RELEASE BLINDING GATE (G-LOG-0045; §26 annex §5): on the blind and reveal files as written for
      the actual S5a pass (bound to the pass record), for every generator with a testable cell (blinding_level FULL
      and a pre-reveal pool with m >= 20: the outcome-free part of the registered test gate), the best AUC over the
      registered features must be <= 0.55. Writes audit-p3b/S5A-BLINDING-GATE.json and a frozen
      PRE-RELEASE-BLINDING-GATE pass-record entry. Exit 0 = PASS (the blind file may be released), 1 = FAIL (STOP;
      human ruling). It reads only the blind, reveal and pools files and the pass record: no disposition or outcome.
"""
import collections
import json
import sys
import os

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402
import p3b_s5a_generators as G  # noqa: E402
import p3b_s5a_controls as K  # noqa: E402

PRE = ("G-SHARED-GROUP", "G-NOTATION", "G-TYPE-SIM")
FEATURES = ("any_empty", "min_ptrs", "total_ptrs", "distinct_sources", "shared_src")


def auc(pos, neg):
    """Mann-Whitney AUC, returned as max(AUC, 1 - AUC); None if a side is empty."""
    if not pos or not neg:
        return None
    cn, below, cum = collections.Counter(neg), 0, {}
    for v in sorted(set(pos) | set(neg)):
        cum[v] = below
        below += cn[v]
    a = sum(cum[p] + 0.5 * cn[p] for p in pos) / (len(pos) * len(neg))
    return round(max(a, 1 - a), 3)


def feats(ev, members):
    per = [len(ev.get(x, [])) for x in members]
    srcs = [{p["source_id"] for p in ev.get(x, [])} for x in members]
    union = set().union(*srcs)
    return {"any_empty": int(min(per) == 0), "min_ptrs": min(per), "total_ptrs": sum(per),
            "distinct_sources": len(union), "shared_src": sum(1 for s in union if sum(s in x for x in srcs) >= 2)}


MODES = {"pre_M4A": ((), False, 0), "post_M4A": (("G-SHARED-GROUP",), False, 0),
         "implemented_A1": (("G-SHARED-GROUP",), True, 1)}


def build(ctx, cands, ctrl, mode):
    """Blind build under a mode: pre_M4A (no stripping), post_M4A (M4-A only) or implemented_A1 (the committed engine:
    M4-A + R1 + cap 1, scope A'1). The module flags are restored afterwards; implemented_A1 must equal the defaults."""
    gens, r1, cap = MODES[mode]
    old = (K.SHARED_SOURCE_STRIP_GENERATORS, K.M4R1_WITHHOLD, K.M4R1_CAP)
    if mode == "implemented_A1" and (old != (gens, r1, cap) or K.M4R1_SCOPE != K.M4R1_APPROVED["scope"]
                                     or not K._m4r1_in_scope([{"role": "CONTROL", "generator": "G-TYPE-SIM"}])):
        raise C.S5Error("engine flags or scope differ from the implemented rule (R1 + cap 1, scope A'1)")
    K.SHARED_SOURCE_STRIP_GENERATORS, K.M4R1_WITHHOLD, K.M4R1_CAP = gens, r1, cap
    try:
        return K.build_blind(ctx, cands, ctrl), K.withheld_sets(ctx, cands, ctrl)
    finally:
        K.SHARED_SOURCE_STRIP_GENERATORS, K.M4R1_WITHHOLD, K.M4R1_CAP = old


def measure(blind, reveal, generator, withhold_empty=False, cap=None):
    rv = {r["unit_key"]: r for r in reveal}
    P, N, wc, wn = [], [], 0, 0
    for b in blind:
        roles = {x["role"] for x in rv[b["unit_key"]]["roles"]
                 if x["generator"] == generator and x["role"] in ("CANDIDATE", "CONTROL")}
        if not roles:
            continue
        if withhold_empty and any(len(b["evidence"].get(x, [])) == 0 for x in b["members"]):
            wc += "CANDIDATE" in roles
            wn += "CONTROL" in roles
            continue
        ev = {x: sorted(b["evidence"].get(x, []), key=lambda p: (p["source_id"], p["row_line"]))[:cap] if cap
              else b["evidence"].get(x, []) for x in b["members"]}
        f = feats(ev, b["members"])
        (P if "CANDIDATE" in roles else N).append(f)
        if roles == {"CANDIDATE", "CONTROL"}:
            N.append(f)
    a = {k: auc([f[k] for f in P], [f[k] for f in N]) for k in FEATURES}
    return {"cand_units": len(P), "ctrl_units": len(N), "withheld_cand_units": wc, "withheld_ctrl_units": wn,
            "cand_with_empty_member": sum(f["any_empty"] for f in P), "ctrl_with_empty_member": sum(f["any_empty"] for f in N),
            "auc": a, "max_auc": max(v for v in a.values() if v is not None)}


def scope_variants(ctx, cands, ctrl):
    """Scope of the residual rule (implementation finding after human ruling A): the rule applied to G-SHARED-GROUP
    units only (as ruled) vs to every unit with a candidate/control role (A'1: M4-A stripping still G-SHARED-GROUP
    only; A'2: stripping too). Simulated by substituting the unit-evidence step; engine flags untouched."""
    orig_ue, orig_chk = K._unit_evidence, K.check_m4a

    def make(strip_all, rule_all):
        def ue(ctx_, m, roles, cand_by_id):
            cc = any(r["role"] in ("CANDIDATE", "CONTROL") for r in roles)
            sg = K._sg_family(roles)
            toks = set()
            for role in roles:
                if role["generator"] in K.POINTER_TOKEN_GENERATORS:
                    toks.update(K._role_tokens(role, cand_by_id))
            shared = K.shared_sources({lab: ctx_.rows_by_label.get(lab, []) for lab in m}) if (cc if strip_all else sg) else set()
            ev = {lab: [{"source_id": x["source_id"], "row_line": x["line"], "anchor": x.get("anchor")}
                        for x in ctx_.rows_by_label.get(lab, []) if x["source_id"] not in toks | shared] for lab in m}
            if (cc if rule_all else sg):
                if any(not v for v in ev.values()):
                    return ev, 0, 0, True
                ev = {lab: sorted(v, key=lambda p: (p["source_id"], p["row_line"]))[:1] for lab, v in ev.items()}
            return ev, 0, 0, False
        return ue

    out = {}
    try:
        K.check_m4a = lambda *a: True
        for name, sa, ra in (("A_as_ruled_SG_only", False, False), ("A1_rule_all_units", False, True),
                             ("A2_strip_and_rule_all_units", True, True)):
            K._unit_evidence = make(sa, ra)
            blind, reveal = K.build_blind(ctx, cands, ctrl)
            out[name] = {"blind_units": len(blind), "withheld_units": len(K.withheld_sets(ctx, cands, ctrl)),
                         "generators": {g: measure(blind, reveal, g) for g in PRE}}
    finally:
        K._unit_evidence, K.check_m4a = orig_ue, orig_chk
    return out


def _variant_ue(strip_all, rule_all, r1, cap):
    """Unit-evidence step for a variant: M4-A stripping on G-SHARED-GROUP units (or every candidate/control unit), then
    R1 (withhold if a member has no pointer) and/or cap on G-SHARED-GROUP units (or every candidate/control unit)."""
    def ue(ctx_, m, roles, cand_by_id):
        cc = any(r["role"] in ("CANDIDATE", "CONTROL") for r in roles)
        sg = K._sg_family(roles)
        toks = set()
        for role in roles:
            if role["generator"] in K.POINTER_TOKEN_GENERATORS:
                toks.update(K._role_tokens(role, cand_by_id))
        shared = K.shared_sources({lab: ctx_.rows_by_label.get(lab, []) for lab in m}) if (cc if strip_all else sg) else set()
        ev = {lab: [{"source_id": x["source_id"], "row_line": x["line"], "anchor": x.get("anchor")}
                    for x in ctx_.rows_by_label.get(lab, []) if x["source_id"] not in toks | shared] for lab in m}
        if cc if rule_all else sg:
            if r1 and any(not v for v in ev.values()):
                return ev, 0, 0, True
            if cap:
                ev = {lab: sorted(v, key=lambda p: (p["source_id"], p["row_line"]))[:cap] for lab, v in ev.items()}
        return ev, 0, 0, False
    return ue


def _means(blind, reveal, generator, feature):
    rv = {r["unit_key"]: r for r in reveal}
    vals = {"CANDIDATE": [], "CONTROL": []}
    for b in blind:
        for role in {x["role"] for x in rv[b["unit_key"]]["roles"] if x["generator"] == generator} & set(vals):
            vals[role].append(feats(b["evidence"], b["members"])[feature])
    return {k: round(sum(v) / len(v), 2) if v else None for k, v in vals.items()}


def scope_decomposition(ctx, cands, ctrl):
    """Mechanism of the cross-generator cue (human request after ruling A): overlap of G-NOTATION units with the
    G-SHARED-GROUP family; R1 and cap separated; family visibility; per-generator withholding; cell sizes m."""
    import p3b_s5a_cells as CELLS
    orig_ue, orig_chk = K._unit_evidence, K.check_m4a
    units = K._collect_units(cands, ctrl)
    overlap = {}
    for g in PRE:
        for role in ("CANDIDATE", "CONTROL"):
            us = [m for m, rs in units.items() if any(r["generator"] == g and r["role"] == role for r in rs)]
            overlap[f"{g}:{role}"] = {"units": len(us), "with_G-SHARED-GROUP_role": sum(1 for m in us if K._sg_family(units[m]))}
    variants = {"M4A_only": (False, False, False, 0), "SG_R1_only": (False, False, True, 0),
                "SG_cap1_only": (False, False, False, 1), "SG_R1_cap1_as_ruled": (False, False, True, 1),
                "A1_R1_cap1_all_units": (False, True, True, 1), "A2_strip_R1_cap1_all_units": (True, True, True, 1)}
    judged = [frozenset((p["a"], p["b"])) for p in C.discovery_pairs()]
    hubs = C.hubs()
    out = {"overlap": overlap, "feature_definitions": {
        "total_ptrs": "sum over the unit's members of the number of evidence pointers written for that member in the blind unit",
        "min_ptrs": "minimum over members of that number", "any_empty": "1 if some member has no pointer",
        "distinct_sources": "number of distinct source_id over all pointers of the unit",
        "shared_src": "number of source_id cited by pointers of two or more members",
        "all_single": "1 if every member shows exactly one pointer (family-visibility feature)"}, "variants": {}}
    try:
        K.check_m4a = lambda *a: True
        for name, (sa, ra, r1, cap) in variants.items():
            K._unit_evidence = _variant_ue(sa, ra, r1, cap)
            blind, reveal = K.build_blind(ctx, cands, ctrl)
            withheld = K.withheld_sets(ctx, cands, ctrl)
            rv = {r["unit_key"]: r for r in reveal}
            fam = [b for b in blind if K._sg_family(rv[b["unit_key"]]["roles"])]
            non = [b for b in blind if not K._sg_family(rv[b["unit_key"]]["roles"])]
            single = lambda b: int(all(len(v) == 1 for v in b["evidence"].values()))
            wh = {f"{g}:{role}": sum(1 for m in withheld if any(r["generator"] == g and r["role"] == role for r in units[m]))
                  for g in PRE for role in ("CANDIDATE", "CONTROL")}
            pools = CELLS.build_pools(cands, ctrl, hubs, judged, withheld)
            m = {c["cell_id"]: c["m"] for c in pools["cells"] if c["generator"] in PRE and c["class"] == "SHARED-INVARIANT"}
            out["variants"][name] = {
                "blind_units": len(blind), "withheld_units": len(withheld), "withheld_by_role": wh,
                "family_visibility": {"family_units": len(fam), "non_family_units": len(non),
                                      "all_single_family": sum(map(single, fam)), "all_single_non_family": sum(map(single, non)),
                                      "auc_all_single": auc([single(b) for b in fam], [single(b) for b in non]),
                                      "auc_total_ptrs": auc([feats(b["evidence"], b["members"])["total_ptrs"] for b in fam],
                                                            [feats(b["evidence"], b["members"])["total_ptrs"] for b in non])},
                "generators": {g: dict(measure(blind, reveal, g), mean_total_ptrs=_means(blind, reveal, g, "total_ptrs"))
                               for g in PRE},
                "m_SHARED-INVARIANT": m}
    finally:
        K._unit_evidence, K.check_m4a = orig_ue, orig_chk
    return out


GATE_THRESHOLD = 0.55                     # registered by human ruling (G-LOG-0045); never tuned after measurement


def prerelease_gate(blind, reveal, levels, pools_body):
    """Registered gate: per generator, best AUC over FEATURES (candidate vs control units, as written); a generator is
    testable iff blinding FULL and some registered cell has pre-reveal m >= 20. PASS iff every testable generator has
    best AUC <= GATE_THRESHOLD."""
    gens = sorted({x["generator"] for r in reveal for x in r["roles"] if x["role"] in ("CANDIDATE", "CONTROL")})
    m_max = {}
    for c in pools_body["cells"]:
        m_max[c["generator"]] = max(m_max.get(c["generator"], 0), c["m"])
    out = {}
    for g in gens:
        x = measure(blind, reveal, g)
        testable = (levels.get(g, {}).get("blinding_level") == "FULL") and m_max.get(g, 0) >= 20
        out[g] = {"testable": testable, "blinding_level": levels.get(g, {}).get("blinding_level"), "max_pool_m": m_max.get(g, 0),
                  "cand_units": x["cand_units"], "ctrl_units": x["ctrl_units"], "auc": x["auc"], "best_auc": x["max_auc"],
                  "passes": x["max_auc"] is not None and x["max_auc"] <= GATE_THRESHOLD}
    tested = [g for g, v in out.items() if v["testable"]]
    return {"threshold": GATE_THRESHOLD, "features": list(FEATURES), "generators": out, "testable_generators": tested,
            "result": "PASS" if all(out[g]["passes"] for g in tested) else "FAIL"}


def gate_main(argv):
    import argparse
    ap = argparse.ArgumentParser(description="registered pre-release blinding gate")
    for x in ("--blind", "--reveal", "--pools", "--pass-record"):
        ap.add_argument(x, required=True)
    ap.add_argument("--out", default="/tmp/s5a-build/S5A-BLINDING-GATE.json")
    ap.add_argument("--real-pass", action="store_true")
    a = ap.parse_args(argv)
    C.assert_sealed()
    G.safe_out(a.out, a.real_pass)
    if any(e["kind"] == "PRE-RELEASE-BLINDING-GATE" for e in K._verified_entries(os.path.abspath(a.pass_record))):
        raise C.S5Error("REFUSED: PRE-RELEASE-BLINDING-GATE is already frozen for this pass (the gate runs once)")
    pre = K.pass_record_frozen(a.pass_record, "PRE-ANALYSIS-BLIND-REVEAL", a.real_pass)
    link6 = K.pass_record_frozen(a.pass_record, "LINK6-POOLS-PREREVEAL", a.real_pass)
    for path, key, d in ((a.blind, "blind_file_sha256", pre), (a.reveal, "reveal_file_sha256", pre),
                         (a.pools, "pools_file_sha256", link6)):
        if C.sha256_file(K.check_artifact_path(path, a.real_pass)) != d[key]:
            raise C.S5Error(f"REFUSED: {os.path.basename(path)} does not match the pass record")
    hb, blind = G.read_jsonl_artifact(a.blind)
    hr, reveal = G.read_jsonl_artifact(a.reveal)
    K.check_m4r1_binding(hb["parameters"].get("m4_residual"), hr["parameters"].get("m4_residual"),
                         pre.get("m4_residual"), link6.get("m4_residual"))
    _, pools_body = K.read_json_artifact(a.pools)
    body = prerelease_gate(blind, reveal, hr["blinding"], pools_body)
    body["artifact"] = "S5A-BLINDING-GATE.json"
    h = C.header(__file__, [os.path.abspath(p) for p in (a.blind, a.reveal, a.pools)], {"threshold": GATE_THRESHOLD},
                 json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False), extra={"artifact": "S5A-BLINDING-GATE.json"})
    K.write_json_artifact(a.out, h, body)
    K.pass_record_append(a.pass_record, "PRE-RELEASE-BLINDING-GATE",
                         {"gate_file_sha256": C.sha256_file(a.out), "result": body["result"], "threshold": GATE_THRESHOLD,
                          "best_auc": {g: v["best_auc"] for g, v in body["generators"].items()},
                          "testable_generators": body["testable_generators"]}, __file__)
    print(json.dumps({"result": body["result"], "best_auc": {g: v["best_auc"] for g, v in body["generators"].items()},
                      "testable": body["testable_generators"]}))
    C.assert_sealed()
    return 0 if body["result"] == "PASS" else 1


def main():
    if len(sys.argv) > 1 and sys.argv[1] == "gate":
        return gate_main(sys.argv[2:])
    C.assert_sealed()
    ctx = G.Ctx.load()
    _, cand, ctrl, _ = G.build(ctx, PRE)
    cands = [r for r in cand if r["record_type"] == "CANDIDATE"]
    built = {mode: build(ctx, cands, ctrl, mode) for mode in MODES}
    pre, post = built["pre_M4A"][0], built["post_M4A"][0]
    out = {"data": "pre-S5 design data (no S5 outcomes)", "generators": {},
           "implemented_A1_withheld_units": len(built["implemented_A1"][1])}
    for g in PRE:
        out["generators"][g] = {mode: measure(*built[mode][0], g) for mode in MODES}
    sg = "G-SHARED-GROUP"
    out["remediations_G-SHARED-GROUP"] = {"R1": measure(*post, sg, withhold_empty=True)}
    for c in (1, 2, 3, 5):
        out["remediations_G-SHARED-GROUP"][f"R1+cap{c}"] = measure(*post, sg, withhold_empty=True, cap=c)
    out["remediations_G-SHARED-GROUP"]["cap1_without_R1"] = measure(*post, sg, cap=1)
    for label, ((bl, _), _w) in built.items():
        out[f"blind_file_{label}"] = {"units": len(bl),
                                      "units_with_empty_member": sum(1 for b in bl if any(len(b["evidence"].get(x, [])) == 0 for x in b["members"]))}
    out["scope_variants"] = scope_variants(ctx, cands, ctrl)
    out["scope_decomposition"] = scope_decomposition(ctx, cands, ctrl)
    print(json.dumps(out, indent=1, sort_keys=True))
    C.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
