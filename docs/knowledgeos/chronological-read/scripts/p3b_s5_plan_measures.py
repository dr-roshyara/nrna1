#!/usr/bin/env python3
"""P3b S5 plan measurements (planning analysis for the S5 plan v2; NOT S5 execution code). Deterministic.

Reads committed pre-S5 artifacts only and keeps only S5-population (Tier-U/Z discovery) labels and discovery labels;
never opens a sealed artifact, never reads corpus content. Inputs: P3B-SAMPLE-PLAN.jsonl, P3B-S5-HUBS.jsonl,
P3B-DISCOVERY-SEARCH.jsonl, P3B-IDENTITY-MANIFEST.jsonl (blob sizes), _batch_input_r2/bundle_index_discovery.jsonl,
20-FAMILIES/_derived.json, 31-RECONCILIATION-PAIRS.jsonl (only pairs whose two labels are both S5-population labels are
kept; H-19 components are pair-disjoint from the discovery population, addendum §1/§3), the S4 R2.2 ledgers and
reader log, the R2.2 slices (checklist flags).

  python3 p3b_s5_plan_measures.py            (prints JSON; the S5 plan v2 cites audit-p3b/S5-PLAN-MEASURES.json)
"""
import collections
import itertools
import json
import os
import statistics
import subprocess
import sys

CR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
R22 = ("PB02", "PB03", "PB04", "PB05")


def jl(name):
    with open(os.path.join(CR, name), encoding="utf-8") as f:
        return [json.loads(line) for line in f if line.strip()]


def pct(vals, p):
    return statistics.quantiles(vals, n=100)[p - 1]


def main():
    out = {}
    plan = {r["working_label"]: r for r in jl("P3B-SAMPLE-PLAN.jsonl")[1:]}
    pop = set(plan)
    hubs = {r["working_label"] for r in jl("P3B-S5-HUBS.jsonl")[1:]}
    non = sorted(pop - hubs)
    man = {r["source_id"]: r for r in jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r}
    specs = [(s, r["blob_spec"]) for s, r in man.items() if r.get("blob_spec_type") == "GIT-OBJECT"]
    got = subprocess.run(["git", "cat-file", "--batch-check=%(objectsize)"], cwd=CR, capture_output=True, text=True,
                         input="\n".join(b for _, b in specs) + "\n").stdout.split("\n")
    size = {s: int(o) for (s, _), o in zip(specs, got) if o.strip().isdigit()}
    recs = jl("P3B-DISCOVERY-SEARCH.jsonl")[1:]
    hits = {r["label"]: r for r in recs if r["record"] == "LABEL-HITS"}
    dims = collections.Counter(r["label"] for r in recs if r["record"] == "DIMENSION")
    disc_labels = set(hits) | set(dims)
    bi = {r["working_label"]: r for r in jl("_batch_input_r2/bundle_index_discovery.jsonl")[1:]}

    def load(lab):
        keys, files = set(), set()
        for kind in ("ledger_hits", "raw_hits"):
            for s, occ in ((hits.get(lab) or {}).get(kind) or {}).items():
                files.add(s)
                keys.update((s, kind, json.dumps(x)) for x in occ)
        rowsrc = {x["source_id"] for x in bi.get(lab, {}).get("rows", [])}
        return {"disp": len(keys) * dims[lab], "s2": sum(size.get(s, 0) for s in files),
                "s1": sum(size.get(s, 0) for s in rowsrc)}

    L = {lab: load(lab) for lab in pop}
    # §9.4 weight: row_count + 3*pair_count + 2*|absences|. pair_count = the label's P3a pairs, which lie entirely inside
    # the discovery population (H-19 components are pair-disjoint from it); only pairs with both labels in the
    # discovery population are kept, so no hold-out record enters any output.
    pc = collections.Counter()
    judged = set()
    for p in jl("31-RECONCILIATION-PAIRS.jsonl"):
        a, b = p.get("a"), p.get("b")
        if a in disc_labels and b in disc_labels:
            judged.add(frozenset((a, b)))
            pc[a] += 1
            pc[b] += 1
    W = {lab: bi.get(lab, {}).get("distinct_rows", 0) + 3 * pc[lab] + 2 * dims[lab] for lab in pop}

    # R2.2 measured anchors (checklist flags from the R2.2 slices)
    anchors = {}
    for b in R22:
        labs = [json.loads(x)["working_label"] for x in open(os.path.join(CR, "ledger-p3b-r2", "S4-R22", b, "objects.jsonl"))]
        chk = [json.load(open(os.path.join(CR, "_batch_input_r2", "s4-pilot-r22", f"{l}.json")))["in_checklist"] for l in labs]
        anchors[b] = {"labels": len(labs), "checklist": sum(bool(c) for c in chk), "disp": sum(L[l]["disp"] for l in labs),
                      "s2": sum(L[l]["s2"] for l in labs), "s1": sum(L[l]["s1"] for l in labs)}
    reads = collections.defaultdict(collections.Counter)
    for e in jl("ledger-p3b-r2/S4-PILOT-R2-002/READ-LOG.jsonl"):
        reads[e["batch_id"]][f"step{e.get('step')}"] += sum(e.get("bytes", {}).values())
    for b in R22:
        anchors[b]["actual_reads"] = dict(reads[b])
    out["r22_anchors"] = anchors
    cap = {"disp": max(a["disp"] for a in anchors.values()), "s2": max(a["s2"] for a in anchors.values()),
           "s1": max(a["actual_reads"].get("step1", 0) for a in anchors.values()),
           "checklist": max(a["checklist"] for a in anchors.values()), "labels": max(a["labels"] for a in anchors.values())}
    out["caps_from_anchors"] = cap

    def pack(labs, lmax, chkmax, stage2=True):
        # §9.4: within a tier, by weight (first-fit decreasing on W, ties by label); H-12 load caps as packing limits.
        # Hub batches (stage2=False) have no stage-2 load, so only the label, checklist and step-1 limits apply.
        bins = []
        for lab in sorted(labs, key=lambda x: (-W[x], x)):
            for bn in bins:
                s2ok = (not stage2) or (bn["d"] + L[lab]["disp"] <= cap["disp"] and bn["s2"] + L[lab]["s2"] <= cap["s2"])
                if (len(bn["L"]) < lmax and bn["c"] + plan[lab]["in_checklist"] <= chkmax and s2ok
                        and bn["s1"] + L[lab]["s1"] <= cap["s1"]):
                    bn["L"].append(lab); bn["c"] += plan[lab]["in_checklist"]; bn["d"] += L[lab]["disp"]
                    bn["s2"] += L[lab]["s2"]; bn["s1"] += L[lab]["s1"]
                    break
            else:
                bins.append({"L": [lab], "c": plan[lab]["in_checklist"], "d": L[lab]["disp"], "s2": L[lab]["s2"], "s1": L[lab]["s1"]})
        return bins

    proj = {}
    for lmax in (5, 8, 10):
        tot, binding = 0, collections.Counter()
        for tier in ("U", "Z"):
            bins = pack([l for l in non if plan[l]["tier"] == tier], lmax, cap["checklist"])
            tot += len(bins)
            for bn in bins:
                binding["labels" if len(bn["L"]) == lmax else "load_or_checklist"] += 1
        hb = sum(len(pack([l for l in hubs if plan[l]["tier"] == t], lmax, cap["checklist"], stage2=False)) for t in ("U", "Z"))
        proj[str(lmax)] = {"nonhub_batches": tot, "hub_batches": hb, "binding": dict(binding)}
    out["packing_projection"] = proj
    over = [l for l in non if L[l]["s1"] > cap["s1"]]
    out["nonhub_labels_over_step1_cap_alone"] = len(over)

    # register kinds requiring audit (§21 item 5) at the R2.2 rate
    mand = tot_reg = 0
    for b in R22:
        for r in jl(f"ledger-p3b-r2/S4-R22/{b}/register.jsonl"):
            tot_reg += 1
            k = r.get("kind") or ""
            if k in ("HYPOTHESIS", "STRUCTURE-CANDIDATE", "SCHEMA-LIMITATION", "METHODOLOGICAL-DEFICIENCY"):
                mand += 1
    out["r22_register"] = {"records": tot_reg, "mandatory_audit_kinds": mand, "labels": 20}

    # file degree: labels whose rows cite the file, over the discovery population (2,452) and the S5 population (1,975)
    def degree(labs):
        d = collections.Counter()
        for lab in labs:
            for s in {x["source_id"] for x in bi.get(lab, {}).get("rows", [])}:
                d[s] += 1
        v = sorted(d.values())
        return {"files": len(v), "p90": pct(v, 90), "p95": pct(v, 95), "p99": pct(v, 99), "max": v[-1],
                "above": {str(c): sum(1 for x in v if x > c) for c in (5, 8, 10, 14, 15)},
                "named": {s: d[s] for s in ("S0239", "S0237", "S0240", "S0241", "S2523", "S2524", "S2528", "S2819")},
                "files_above_10": sorted(s for s, x in d.items() if x > 10), "files_at_10": sorted(s for s, x in d.items() if x == 10)}
    out["degree_discovery_2452"] = degree(disc_labels & set(bi))
    out["degree_s5_1975"] = degree(pop)

    # track composition over the S5 population (1,975)
    tc, mixed_a_unconf, mixed_phase_only, mixed_unconf_as_b = collections.Counter(), 0, 0, 0
    for lab in pop:
        tags = {x.get("track_tag") for x in bi.get(lab, {}).get("sources", [])}
        tc.update(tags)
        b_ = "TRACK-B-GAP-DISCOVERY" in tags
        if b_ and (tags & {"TRACK-A-PHASE-MEASURE", "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED"}):
            mixed_a_unconf += 1
        if b_ and "TRACK-A-PHASE-MEASURE" in tags:
            mixed_phase_only += 1
        if "TRACK-A-PHASE-MEASURE" in tags and (tags & {"TRACK-B-GAP-DISCOVERY", "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED"}):
            mixed_unconf_as_b += 1
    out["tracks_s5_1975"] = {"labels_touching": dict(tc), "mixed_if_unconfirmed_is_A": mixed_a_unconf,
                             "mixed_if_only_phase_measure_is_A": mixed_phase_only,
                             "mixed_if_unconfirmed_is_B (mapping 2)": mixed_unconf_as_b}

    # generator previews over the S5 population: distinct candidate sets
    dv = json.load(open(os.path.join(CR, "20-FAMILIES", "_derived.json"), encoding="utf-8"))
    sg = {2: set(), 3: set()}
    for g in dv["groups"]:
        m = sorted(x for x in g["members"] if x in pop)
        for k in (2, 3):
            sg[k].update(itertools.combinations(m, k))
    notes = collections.defaultdict(set)
    for lab, n in dv["nodes"].items():
        if lab in pop:
            for x in (n.get("notations") or []) + (n.get("aliases") or []):
                notes[str(x).strip()].add(lab)
    gn = {2: set(), 3: set()}
    for labs in notes.values():
        for k in (2, 3):
            gn[k].update(itertools.combinations(sorted(labs), k))

    def fam(sets):
        kept, used = 0, set()
        for s in sorted(sets):
            if not used & set(s):
                kept += 1
                used |= set(s)
        return kept
    def all_judged(s):
        return all(frozenset(x) in judged for x in itertools.combinations(s, 2))
    # structural emptiness of identity-class cells (RC-13), P2a generators only: sets whose member pairs are all P3a-judged
    out["identity_cells_p2a"] = {
        g: {"sets": len(S[2] | S[3]), "unjudged_sets": sum(1 for s in (S[2] | S[3]) if not all_judged(s)),
            "identity_state_conflation_cell": "NOT-REGISTERED (empty after RC-13)" if all(all_judged(s) for s in (S[2] | S[3])) else "REGISTERED"}
        for g, S in (("G-SHARED-GROUP", sg), ("G-NOTATION", gn))}
    out["generator_preview_s5_1975"] = {
        "G-SHARED-GROUP": {"k2": len(sg[2]), "k3": len(sg[3]), "label_disjoint_family": fam(sg[2] | sg[3])},
        "G-NOTATION": {"k2": len(gn[2]), "k3": len(gn[3]), "label_disjoint_family": fam(gn[2] | gn[3])}}

    # G-TIMELINE-SIM behaviour on the R2.2 timelines (the only S5-like timelines that exist)
    seqs = {}
    for b in R22:
        for o in jl(f"ledger-p3b-r2/S4-R22/{b}/objects.jsonl"):
            ch = [p.get("change_vs_previous") for p in (o.get("timeline") or []) if isinstance(p, dict)]
            seqs[o["working_label"]] = ch
    def bigrams(ch, drop_first):
        bg = set(zip(ch, ch[1:]))
        return {x for x in bg if not (drop_first and x[0] == "FIRST")}
    tl = {}
    for drop_first, minbg in ((False, 1), (True, 3)):
        B = {l: bigrams(c, drop_first) for l, c in seqs.items()}
        elig = [l for l, s in B.items() if len(s) >= minbg]
        pairs = list(itertools.combinations(sorted(elig), 2))
        J = [len(B[a] & B[b]) / len(B[a] | B[b]) for a, b in pairs] if pairs else []
        tl[f"drop_first={drop_first},min_bigrams={minbg}"] = {
            "labels": len(seqs), "eligible": len(elig), "pairs": len(pairs),
            "share_J_ge_0.5": round(sum(j >= 0.5 for j in J) / len(J), 3) if J else None,
            "share_J_ge_0.8": round(sum(j >= 0.8 for j in J) / len(J), 3) if J else None}
    out["timeline_sim_r22"] = tl

    # object-record size (for the S5a cross-object reading estimate)
    sizes = [len(x.encode("utf-8")) for b in R22 for x in open(os.path.join(CR, "ledger-p3b-r2", "S4-R22", b, "objects.jsonl"))]
    out["r22_object_record_bytes"] = {"mean": int(statistics.mean(sizes)), "max": max(sizes)}

    # §K rerun allocation: 24 strata plus the purposive (stratum null) group, non-hub labels
    st = collections.Counter(plan[l]["stratum"] if plan[l]["stratum"] is not None else "PURPOSIVE" for l in non)
    out["rerun_frame"] = {"nonhub": len(non), "stratified_nonhub": sum(v for k, v in st.items() if k != "PURPOSIVE"),
                          "purposive_nonhub": st.get("PURPOSIVE", 0),
                          "alloc_5pct": sum(max(1, round(0.05 * v)) for v in st.values()), "groups": len(st)}
    import hashlib
    import platform
    inputs = ["P3B-SAMPLE-PLAN.jsonl", "P3B-S5-HUBS.jsonl", "P3B-DISCOVERY-SEARCH.jsonl", "P3B-IDENTITY-MANIFEST.jsonl",
              "_batch_input_r2/bundle_index_discovery.jsonl", "20-FAMILIES/_derived.json", "31-RECONCILIATION-PAIRS.jsonl",
              "ledger-p3b-r2/S4-PILOT-R2-002/READ-LOG.jsonl"]
    inputs += [f"ledger-p3b-r2/S4-R22/{b}/{f}.jsonl" for b in R22 for f in ("objects", "register")]
    inputs += sorted(os.path.join("_batch_input_r2", "s4-pilot-r22", x) for x in os.listdir(os.path.join(CR, "_batch_input_r2", "s4-pilot-r22")) if x.endswith(".json"))
    body = json.dumps(out, indent=1, sort_keys=True)
    blob = subprocess.run(["git", "hash-object", os.path.abspath(__file__)], capture_output=True, text=True).stdout.strip()
    header = {"script_name": "scripts/p3b_s5_plan_measures.py", "script_version": blob,
              "input_hashes": {i: hashlib.sha256(open(os.path.join(CR, i), "rb").read()).hexdigest() for i in inputs},
              "output_sha256": hashlib.sha256(body.encode("utf-8")).hexdigest(),
              "python_version": sys.version.split()[0], "platform": platform.platform(),
              "note": "planning analysis for the S5 plan; not S5 execution code; output_sha256 covers 'body'"}
    print(json.dumps({"header": header, "body": out}, indent=1, sort_keys=True))
    return 0


if __name__ == "__main__":
    sys.exit(main())
