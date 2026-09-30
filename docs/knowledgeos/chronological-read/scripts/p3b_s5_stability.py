#!/usr/bin/env python3
"""P3b S5 repeated-run stability tooling (plan v2.3.2 §K; deliverables O-9 / O-21 tooling). Infrastructure only.

Approved parameters (G-LOG-0041; never changed here): rerun share 5 % with root 20260928; group size
max(1, round(0.05 n_g)) with Python's round-half-to-even; 97 labels; κ bootstrap root 20260930; METHOD-INSTABILITY when
the κ 95 % CI upper bound < 0.40; re-analysis share 20 % with root 20260929, blind to role.

  (a) draw        the §K object-rerun draw over the NON-HUB S5-population labels: 24 strata + the PURPOSIVE group
                  (stratum null), 25 groups, sorted by group key; group g uses
                  random.Random(int(SeedSequence(20260928).spawn(25)[g].generate_state(1)[0])).sample(sorted members, n_g).
                  Reads only P3B-SAMPLE-PLAN.jsonl and P3B-S5-HUBS.jsonl. Asserts 97 labels.
  (b) agreement   per field: exact agreement, Cohen's κ and PABAK, design-weighted by 1/π (π = the label's inclusion
                  probability in the draw) with unweighted figures alongside; 95 % CI by a design-following bootstrap:
                  labels resampled with replacement within each group (a label's dimension pairs stay together); groups
                  with a single sampled label pooled into one group. Per-field streams
                  SeedSequence(20260930).spawn(n_fields)[i], fields in sorted order; B replicates (default 2,000).
  (c) flag        METHOD-INSTABILITY if the design-weighted κ CI upper bound < 0.40; otherwise no verdict.
  (d) reanalysis  S5a same-model re-analysis draw: ceil(0.2 N) of all blinded unit ids,
                  random.Random(20260929).sample(sorted ids, n); inputs must be bare ids (blind to role); every unit's
                  members must be S5-population labels (population assert, plan §M item 1).
  Also: Wilson 95 % CI for the per-CORPUS-finding agreement share; label Jaccard of recorded classes.

  p3b_s5_stability.py draw [--out PATH]
"""
import argparse
import json
import math
import os
import random
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
RERUN_ROOT, RERUN_SHARE, RERUN_EXPECTED = 20260928, 0.05, 97
BOOT_ROOT, BOOT_B = 20260930, 2000
KAPPA_FLAG = 0.40
REANALYSIS_ROOT = 20260929
REANALYSIS_NUM, REANALYSIS_DEN = 1, 5
PURPOSIVE = "PURPOSIVE"


# ---------------------------------------------------------------- (a) rerun draw
def rerun_groups(pop, hub_labels):
    groups = {}
    for lab in sorted(set(pop) - set(hub_labels)):
        st = pop[lab].get("stratum")
        groups.setdefault(st if st is not None else PURPOSIVE, []).append(lab)
    return {k: sorted(v) for k, v in sorted(groups.items())}


def rerun_draw(pop=None, hub_labels=None):
    pop = pop if pop is not None else c.s5_population()
    hub_labels = hub_labels if hub_labels is not None else set(c.hubs())
    groups = rerun_groups(pop, hub_labels)
    keys = list(groups)
    out, gi = [], {}
    for i, k in enumerate(keys):
        members = groups[k]
        n = max(1, round(RERUN_SHARE * len(members)))
        seed = c.int_seed(RERUN_ROOT, len(keys), i)
        picked = sorted(random.Random(seed).sample(members, n))
        gi[k] = {"n_group": len(members), "n_drawn": n, "inclusion_probability": n / len(members), "seed": seed}
        out += [{"working_label": l, "group": k, "inclusion_probability": n / len(members), "weight": len(members) / n}
                for l in picked]
    return {"groups": gi, "labels": out, "n": len(out), "n_groups": len(keys),
            "draw_sha256": ops.canon_hash([[x["working_label"], x["group"]] for x in out])}


# ---------------------------------------------------------------- (b) agreement
def _metrics(pairs, weighted=True, categories=None):
    """pairs: [(weight, a, b)] -> (agreement, kappa, pabak, prevalence)."""
    tot = sum(w if weighted else 1.0 for w, _, _ in pairs)
    if tot == 0:
        return None, None, None, {}
    po = sum((w if weighted else 1.0) for w, a, b in pairs if a == b) / tot
    cats = sorted(set(categories or []) | {a for _, a, _ in pairs} | {b for _, _, b in pairs}, key=str)
    pa = {k: sum((w if weighted else 1.0) for w, a, _ in pairs if a == k) / tot for k in cats}
    pb = {k: sum((w if weighted else 1.0) for w, _, b in pairs if b == k) / tot for k in cats}
    pe = sum(pa[k] * pb[k] for k in cats)
    kappa = None if abs(1 - pe) < 1e-15 else (po - pe) / (1 - pe)
    k = len(cats)
    pabak = None if k < 2 else (k * po - 1) / (k - 1)
    prev = {str(x): (pa[x] + pb[x]) / 2 for x in cats}
    return po, kappa, pabak, prev


def _flatten(units, weights):
    return [(weights[u["label"]], a, b) for u in units for a, b in u["pairs"]]


def agreement(field_units, groups_of, weights, B=BOOT_B, field_index=0, n_fields=1, categories=None):
    """field_units: [{label, pairs: [(a, b), ...]}] (dimension pairs stay with their label).
    groups_of: {label: group}; weights: {label: 1/π}."""
    pairs = _flatten(field_units, weights)
    po, kap, pabak, prev = _metrics(pairs, True, categories)
    upo, ukap, upabak, _ = _metrics(pairs, False, categories)
    by_group = {}
    for u in sorted(field_units, key=lambda u: u["label"]):
        by_group.setdefault(groups_of[u["label"]], []).append(u)
    strata, pooled = [], []
    for g in sorted(by_group):
        if len(by_group[g]) == 1:
            pooled.extend(by_group[g])
        else:
            strata.append(by_group[g])
    if pooled:
        strata.append(pooled)
    import numpy as np
    rng = np.random.default_rng(np.random.SeedSequence(BOOT_ROOT).spawn(n_fields)[field_index])
    reps = {"agreement": [], "kappa": [], "pabak": []}
    for _ in range(B):
        sample = []
        for s in strata:
            idx = rng.integers(0, len(s), size=len(s))
            sample += [s[i] for i in idx]
        bpo, bk, bp, _ = _metrics(_flatten(sample, weights), True, categories)
        for name, v in (("agreement", bpo), ("kappa", bk), ("pabak", bp)):
            if v is not None:
                reps[name].append(v)

    def ci(v):
        if not v:
            return None
        q = np.quantile(np.array(v), [0.025, 0.975])
        return [float(q[0]), float(q[1])]

    kci = ci(reps["kappa"])
    return {"n_labels": len(field_units), "n_pairs": len(pairs),
            "weighted": {"agreement": po, "kappa": kap, "pabak": pabak, "agreement_ci95": ci(reps["agreement"]),
                         "kappa_ci95": kci, "pabak_ci95": ci(reps["pabak"])},
            "unweighted": {"agreement": upo, "kappa": ukap, "pabak": upabak},
            "prevalence": prev, "bootstrap": {"root": BOOT_ROOT, "B": B, "field_index": field_index,
                                               "valid_kappa_replicates": len(reps["kappa"]),
                                               "strata": len(strata), "pooled_singletons": len(pooled)},
            "flag": method_instability(kci)}


def method_instability(kappa_ci):
    """(c) METHOD-INSTABILITY iff the κ 95 % CI upper bound < 0.40; otherwise no verdict (None)."""
    if kappa_ci is None:
        return "KAPPA-NOT-COMPUTABLE"
    return "METHOD-INSTABILITY" if kappa_ci[1] < KAPPA_FLAG else None


def agreement_all(fields, groups_of, weights, B=BOOT_B):
    names = sorted(fields)
    return {f: agreement(fields[f], groups_of, weights, B, i, len(names)) for i, f in enumerate(names)}


def jaccard(a, b):
    a, b = set(a), set(b)
    return None if not (a | b) else len(a & b) / len(a | b)


def wilson(k, n, z=1.959963984540054):
    if n == 0:
        return None
    p = k / n
    d = 1 + z * z / n
    centre = (p + z * z / (2 * n)) / d
    half = z * math.sqrt(p * (1 - p) / n + z * z / (4 * n * n)) / d
    return [centre - half, centre + half]


# ---------------------------------------------------------------- (d) re-analysis draw
def reanalysis_draw(unit_ids, members, pop=None):
    """unit_ids: bare ids (blind to role). members: {unit_id: [member labels]} for the plan §M item 1 population
    assert: every member of every unit is an S5-population label (P3B-SAMPLE-PLAN.jsonl). Refusals give counts only."""
    if any(not isinstance(u, str) for u in unit_ids):
        raise c.S5Error("re-analysis draw takes bare unit ids only (blind to role)")
    ids = sorted(set(unit_ids))
    if len(ids) != len(unit_ids):
        raise c.S5Error("duplicate unit ids")
    pop = pop if pop is not None else c.s5_population()
    missing = [u for u in ids if not members.get(u)]
    if missing:
        raise c.S5Error(f"{len(missing)} unit(s) without a member list (population assert, §M item 1)")
    outside = sum(1 for u in ids for m in members[u] if m not in pop)
    if outside:
        raise c.S5Error(f"{outside} unit member(s) outside the S5 population (§M item 1)")
    n = (len(ids) * REANALYSIS_NUM + REANALYSIS_DEN - 1) // REANALYSIS_DEN
    picked = sorted(random.Random(REANALYSIS_ROOT).sample(ids, n))
    return {"n_units": len(ids), "n_drawn": n, "seed": REANALYSIS_ROOT, "units": picked,
            "draw_sha256": ops.canon_hash(picked)}


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="cmd", required=True)
    d = sub.add_parser("draw")
    d.add_argument("--out")
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        dr = rerun_draw()
        if dr["n"] != RERUN_EXPECTED:
            raise c.S5Error(f"rerun draw gives {dr['n']} labels, not the approved {RERUN_EXPECTED}")
        text = ops.canon(dr)
        hdr = c.header(__file__, ["P3B-SAMPLE-PLAN.jsonl", c.HUBS], {"root": RERUN_ROOT, "share": RERUN_SHARE,
                       "rounding": "round-half-to-even", "groups": dr["n_groups"]}, text, extra={"seed": RERUN_ROOT})
        if a.out:
            ops.write_new(a.out, json.dumps({"header": hdr, "body": dr}, indent=1, sort_keys=True, ensure_ascii=False) + "\n")
        pis = [g["inclusion_probability"] for g in dr["groups"].values()]
        c.assert_sealed()
        print(json.dumps({"labels": dr["n"], "groups": dr["n_groups"], "pi_min": round(min(pis), 4),
                          "pi_max": round(max(pis), 4), "draw_sha256": dr["draw_sha256"],
                          "output_sha256": hdr["output_sha256"]}))
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
