#!/usr/bin/env python3
"""P3b S5 O-25 control-availability simulation (S5 plan v2.3.2 §H.1 last bullet, §O row O-25; G-LOG-0041).

PRE-S5 DESIGN EVIDENCE, NOT A TUNING INPUT: no parameter, threshold or cell rule is derived from its output. Its output
hash is frozen into O-12b.

Runs the A.7 control draw (p3b_s5a_controls, the same code the S5a pass uses) for the pre-S5 generators G-SHARED-GROUP,
G-NOTATION and G-TYPE-SIM on pre-S5 discovery data (allowlisted loaders of p3b_s5_common only), and reports per generator:
the NO-CONTROL-AVAILABLE rate, the relaxation-level distribution, r_s, the resulting test-family size, and control-label
reuse. Reads no sealed artifact, no census file outside the discovery filters, no corpus file.

  python3 p3b_s5a_control_availability.py [--out audit-p3b/S5A-CONTROL-AVAILABILITY.json]
"""
import argparse
import collections
import json
import os
import statistics
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402
import p3b_s5a_controls as K  # noqa: E402
import p3b_s5a_generators as G  # noqa: E402

PRE_S5 = ("G-SHARED-GROUP", "G-NOTATION", "G-TYPE-SIM")
DEFAULT_OUT = "audit-p3b/S5A-CONTROL-AVAILABILITY.json"


def reuse(draws, cand_by_id, subset=None):
    sets, labels = collections.Counter(), collections.Counter()
    cand_labels = set()
    for d in draws:
        if subset is not None and d["candidate_id"] not in subset:
            continue
        cand_labels.update(cand_by_id[d["candidate_id"]]["members"])
        for c in d["controls"]:
            sets[tuple(c["members"])] += 1
    for s in sets:
        for m in s:
            labels[m] += 1
    lv = sorted(labels.values())
    return {"control_slots": sum(sets.values()), "control_sets_distinct": len(sets),
            "control_sets_reused": sum(1 for v in sets.values() if v > 1),
            "max_matched_sets_per_control_set": max(sets.values()) if sets else 0,
            "control_labels_distinct": len(labels),
            "distinct_control_sets_per_label": {"max": lv[-1] if lv else 0,
                                                "mean": round(statistics.mean(lv), 3) if lv else 0,
                                                "median": statistics.median(lv) if lv else 0},
            "control_labels_also_candidate_labels": len(set(labels) & cand_labels)}


def availability(ctx):
    out = {}
    for g in PRE_S5:
        gres = G.run_generator(g, ctx)
        G.assign_ids(gres)
        analysed = G.apply_budget(gres)
        draws = K.draw_for_generator(gres, ctx.bands, analysed)
        nca = {d["candidate_id"] for d in draws if d["status"] == "NO-CONTROL-AVAILABLE"}
        fam = G.mark_test_family(gres, nca)
        by_id = {c["candidate_id"]: c for c in gres.candidates}
        lvl_ctl = collections.Counter(c["relaxation_level"] for d in draws for c in d["controls"])
        lvl_cand = collections.Counter(d["controls"][0]["relaxation_level"] if d["controls"] else "NCA" for d in draws)
        by_k = {}
        for k in G.ARITIES:
            dk = [d for d in draws if d["k"] == k]
            by_k[str(k)] = {"analysed": len(dk), "nca": sum(1 for d in dk if d["status"] == "NO-CONTROL-AVAILABLE"),
                            "nca_rate": round(sum(1 for d in dk if d["status"] == "NO-CONTROL-AVAILABLE") / len(dk), 4) if dk else None}
        out[g] = {"population": len(gres.population), "full_list": len(gres.candidates), "analysed": len(analysed),
                  "no_control_available": len(nca), "nca_rate": round(len(nca) / len(analysed), 4) if analysed else None,
                  "by_arity": by_k,
                  "r_s_distribution": {str(k): v for k, v in sorted(collections.Counter(d["r_s"] for d in draws).items())},
                  "relaxation_level_of_controls": {str(k): v for k, v in sorted(lvl_ctl.items())},
                  "relaxation_level_by_candidate": {str(k): v for k, v in sorted(lvl_cand.items(), key=lambda kv: str(kv[0]))},
                  "relaxation_levels_legend": {str(L): K.level_vars(g, L)[1] for L in range(len(K.BAND_VARS) + 1)},
                  "test_family_size": len(fam),
                  "control_reuse_all_analysed": reuse(draws, by_id),
                  "control_reuse_test_family": reuse(draws, by_id, {c["candidate_id"] for c in fam})}
    return out


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--out", default=DEFAULT_OUT)
    a = ap.parse_args(argv)
    C.assert_sealed()
    C.verify_frozen()
    out = os.path.abspath(a.out if os.path.isabs(a.out) else os.path.join(C.CR, a.out))
    if not (out == os.path.join(C.CR, DEFAULT_OUT) or out.startswith("/tmp/")):
        raise C.S5Error(f"O-25 writes only {DEFAULT_OUT} or a /tmp path")
    ctx = G.Ctx.load()
    body = {"artifact": "S5A-CONTROL-AVAILABILITY.json", "status": "PRE-S5 DESIGN EVIDENCE; not a tuning input",
            "generators": availability(ctx)}
    txt = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
    params = {"generators": list(PRE_S5), "control_parameters": K.parameters(), "budget": G.BUDGET,
              "budget_seed_root": G.BUDGET_ROOT, "type_table_version": G.TYPE_TABLE_VERSION,
              "type_table_sha256": G.type_table_sha256(), "degree_cap": G.DEGREE_CAP}
    h = C.header(__file__, list(ctx.inputs), params, txt,
                 extra={"artifact": DEFAULT_OUT, "deliverable": "O-25", "seed": {"control_root": K.CONTROL_ROOT,
                        "budget_root": G.BUDGET_ROOT}})
    K.write_json_artifact(out, h, body)
    C.assert_sealed()
    print(json.dumps({"out": out, "output_sha256": h["output_sha256"], "generators": {
        g: {k: v[k] for k in ("population", "analysed", "no_control_available", "nca_rate", "test_family_size",
                              "relaxation_level_of_controls")} for g, v in body["generators"].items()}}, indent=1))
    return 0


if __name__ == "__main__":
    sys.exit(main())
