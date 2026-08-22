#!/usr/bin/env python3
"""KOS-SNF P5 — the 1,000-case competition.

Deterministic, seeded, reproducible. Runs every candidate producer over the
KOS-SNF-1000 corpus, sweeps the six approved SNF-E arbitration policies, and
reports a measurement VECTOR — never a single score, never a winner.

Boundaries enforced here (not aspirational — structural):
  * no-gold boundary: producers receive only (expression, context);
  * the gold is read exclusively by the evaluation layer, after the fact;
  * no producer sees another producer's output, except SNF-E over its own
    declared members;
  * nothing in this file selects a mechanism, ranks one as correct, or
    combines members into a numeric authority.

Usage:  python3 run-competition.py [--out DIR]
"""

from __future__ import annotations

import _bootstrap  # noqa: F401

import argparse
import hashlib
import json
import os
import platform
import sys
import time
from collections import Counter, defaultdict

import corpus_1000
import distance as dist_mod
import evaluator
import metrics as M
from corpus_1000 import build_corpus, corpus_digest, CORPUS_VERSION, SCOPE_NOTE
from distance import V01, V02, d_snf_gate
from mechanisms import (SNFA, SNFB, SNFC, SNFD, SNFE, SFNN,
                        ARBITRATION_POLICIES, POLICY_DEFERRED)

SEED = 20260822
DISTANCE_VERSION = V02          # the P5 research metric (v0.1 kept for A/B)
BASE_MECHS = ("SNF-A", "SNF-B", "SNF-C", "SNF-D", "SNF-N")
CONTEXT = {"domain": "toy", "language": "en"}


def _producers(policy: str) -> dict:
    """One black box per name. SNF-E is instantiated per swept policy."""
    return {
        "SNF-A": SNFA(),
        "SNF-B": SNFB(),
        "SNF-C": SNFC(),
        "SNF-D": SNFD(),
        "SNF-N": SFNN(),
        "SNF-E": SNFE(policy=policy, distance_version=DISTANCE_VERSION),
    }


def _run_case(prod, case):
    """Produce candidate sets for a case. Producers see expressions only."""
    return [prod.interpret(e, CONTEXT) for e in case["expressions"]]


def _verdicts_for(cases, outs_by_case, mech_names):
    verdicts = []
    for case in cases:
        for mech in mech_names:
            outs = outs_by_case[case["id"]][mech]
            if case["gold"]["ambiguous"]:
                v = evaluator.verdict_for(case, mech, outs[0])
            else:
                v = evaluator.verdict_pair(case, mech, outs[0], outs[1])
            v["family"] = case["family"]
            v["relation"] = case["relation"]
            verdicts.append(v)
    return verdicts


def _consensus(cases, outs_by_case, mech_names):
    events, pair_stats = [], []
    for case in cases:
        by_mech = {}
        for mech in mech_names:
            outs = outs_by_case[case["id"]][mech]
            by_mech[mech] = outs[0] if case["gold"]["ambiguous"] else tuple(outs)
        ev = evaluator._case_consensus(case, by_mech)
        if ev:
            ev["family"] = case["family"]
            events.append(ev)
        ps = evaluator.consensus_pair_stats(case, by_mech)
        ps["family"] = case["family"]
        pair_stats.append(ps)
    return events, pair_stats


def _per_family(verdicts):
    """Per-mechanism, per-family outcome profile — the Pareto substrate."""
    out = defaultdict(lambda: defaultdict(Counter))
    for v in verdicts:
        out[v["mechanism"]][v["family"]][v["verdict"]] += 1
    return {m: {f: dict(c) for f, c in fams.items()}
            for m, fams in out.items()}


def _pareto(profiles, taxonomy, vec):
    """Non-dominated set under the declared objective/constraint structure.

    Objectives (maximise): correct resolution rate, correct-abstention count.
    Constraint (bound):    false acceptance rate.
    A mechanism is dominated only if another is >= on every objective AND <= on
    the constraint, with at least one strict. No weighting, no total order, no
    winner.
    """
    pts = {}
    for mech, tax in taxonomy.items():
        n = tax["n"] or 1
        pts[mech] = {
            "correct_resolution_rate": round(tax["CORRECT_RESOLUTION"] / n, 4),
            "correct_abstention_rate": round(tax["CORRECT_ABSTENTION"] / n, 4),
            "false_acceptance_rate": tax["false_acceptance_rate"],
        }
    frontier = []
    for a, pa in pts.items():
        dominated_by = []
        for b, pb in pts.items():
            if a == b:
                continue
            ge = (pb["correct_resolution_rate"] >= pa["correct_resolution_rate"]
                  and pb["correct_abstention_rate"] >= pa["correct_abstention_rate"]
                  and pb["false_acceptance_rate"] <= pa["false_acceptance_rate"])
            strict = (pb["correct_resolution_rate"] > pa["correct_resolution_rate"]
                      or pb["correct_abstention_rate"] > pa["correct_abstention_rate"]
                      or pb["false_acceptance_rate"] < pa["false_acceptance_rate"])
            if ge and strict:
                dominated_by.append(b)
        if not dominated_by:
            frontier.append(a)
    return {"points": pts, "non_dominated": sorted(frontier),
            "note": ("Non-domination is not a ranking and not a selection. "
                     "No mechanism is endorsed by appearing here.")}


def _coverage_matched(vec, verdicts):
    """D-5 — compare calibration at the LOWEST common coverage, so an
    abstainer's Brier/ECE advantage cannot be a selection artifact."""
    points = {}
    for mech in vec:
        pts = [(v["conf"], int(bool(v.get("correct"))))
               for v in verdicts
               if v["mechanism"] == mech and v.get("conf") is not None
               and v["category"] in M.PAIR_CATS]
        points[mech] = pts
    n_cases = max((len(p) for p in points.values()), default=0) or 1
    coverages = {m: len(p) / n_cases for m, p in points.items() if p}
    common = min(coverages.values()) if coverages else 0.0
    out = {}
    for mech, pts in points.items():
        if not pts:
            out[mech] = {"raw": None, "matched": None}
            continue
        raw = M.calibration(pts)
        matched = M.calibration_at_coverage(
            pts, coverage=(common * n_cases) / len(pts) if pts else 0.0)
        acc = [ok for _, ok in pts]
        out[mech] = {
            "raw_brier": raw["brier"], "raw_ece": raw["ece"],
            "raw_coverage": round(len(pts) / n_cases, 4),
            "matched_coverage": matched.get("coverage"),
            "matched_brier": matched.get("brier"),
            "matched_ece": matched.get("ece"),
            "matched_n": matched.get("n"),
            "accuracy_ci": M.bootstrap_ci(acc, seed=SEED),
        }
    out["_common_coverage"] = round(common, 4)
    out["_note"] = ("An abstainer answers fewer, easier cases. Any calibration "
                    "advantage that disappears under matched coverage was a "
                    "selection effect, not calibration skill.")
    return out


def main() -> int:
    global DISTANCE_VERSION
    ap = argparse.ArgumentParser()
    ap.add_argument("--out", default=None)
    ap.add_argument("--distance-version", default=DISTANCE_VERSION,
                    choices=[V01, V02],
                    help="metric under test; v0.1 is the A/B baseline")
    ap.add_argument("--policies", default=None,
                    help="comma-separated subset of policies (default: all)")
    ap.add_argument("--tag", default="",
                    help="suffix for the output filename")
    args = ap.parse_args()
    DISTANCE_VERSION = args.distance_version
    here = os.path.dirname(os.path.abspath(__file__))
    out_dir = args.out or os.path.normpath(
        os.path.join(here, "..", "..", "docs", "knowledgeos", "brainstorming"))

    t0 = time.time()
    evaluator.set_distance_version(DISTANCE_VERSION)

    # --- gate: the metric must pass BEFORE any producer runs ---------------
    gate = d_snf_gate(DISTANCE_VERSION)
    if not all(g["pass"] for g in gate):
        print("METRIC GATE FAILED — refusing to run producers:")
        for g in gate:
            if not g["pass"]:
                print("  FAIL", g)
        return 2
    print(f"metric gate: {len(gate)}/{len(gate)} PASS "
          f"(d_SNF {DISTANCE_VERSION})")

    cases = build_corpus()
    digest = corpus_digest(cases)
    print(f"corpus: {CORPUS_VERSION}  n={len(cases)}  digest={digest[:12]}")

    # --- the sweep: one full run per arbitration policy --------------------
    sweep = {}
    selected = (sorted(ARBITRATION_POLICIES) if not args.policies
                else [p.strip() for p in args.policies.split(",")])
    for policy in selected:
        prods = _producers(policy)
        mech_names = tuple(sorted(prods))
        outs_by_case = {}
        for case in cases:
            outs_by_case[case["id"]] = {m: _run_case(p, case)
                                        for m, p in prods.items()}
        verdicts = _verdicts_for(cases, outs_by_case, mech_names)
        events, pair_stats = _consensus(cases, outs_by_case, mech_names)
        vec = M.compute_measurement_vector(verdicts, events, cases=cases)
        tax = M.abstention_taxonomy(verdicts, cases)
        prof = _per_family(verdicts)
        cal = _coverage_matched(vec["mechanisms"], verdicts)
        pareto = _pareto(prof, tax, vec)
        agree_pairs = sum(p["agreeing_pairs"] for p in pair_stats)
        false_pairs = sum(p["false_pairs"] for p in pair_stats)
        fc_edges = Counter()
        for p in pair_stats:
            for a, b in p["false_edges"]:
                fc_edges[f"{a}|{b}"] += 1
        sweep[policy] = {
            "measurement_vector": vec,
            "abstention_taxonomy": tax,
            "per_family": prof,
            "calibration": cal,
            "pareto": pareto,
            "consensus_pairs": {
                "agreeing_pairs": agree_pairs,
                "false_pairs": false_pairs,
                "pairwise_false_rate": (round(false_pairs / agree_pairs, 4)
                                        if agree_pairs else None),
                "false_edges_top": dict(fc_edges.most_common(10)),
            },
        }
        e = vec["mechanisms"]["SNF-E"]
        print(f"  policy {policy:<20} SNF-E: C={e['C']} NC={e['NC']} "
              f"A={e['A']} H={e['H']} | pairwise-false="
              f"{sweep[policy]['consensus_pairs']['pairwise_false_rate']}")

    meta = {
        "run": "KOS-SNF P5 competition",
        "seed": SEED,
        "distance_version": DISTANCE_VERSION,
        "distance_gate": gate,
        "corpus_version": CORPUS_VERSION,
        "corpus_digest": digest,
        "corpus_n": len(cases),
        "scope_note": SCOPE_NOTE,
        "excluded_families": corpus_1000.EXCLUDED_FAMILIES,
        "policies_swept": selected,
        "policies_deferred": POLICY_DEFERRED,
        "python": platform.python_version(),
        "elapsed_s": round(time.time() - t0, 2),
        "authority_note": (
            "Candidate != identity. Confidence != identity. Agreement != "
            "identity. Similarity != identity. Canonical representation != "
            "identity. Nothing in this run selects a mechanism or grants "
            "authority to one."),
    }
    payload = {"meta": meta, "sweep": sweep}
    dest = os.path.join(out_dir,
                        f"KOS-SNF-1000-competition{args.tag}.json")
    with open(dest, "w", encoding="utf-8") as fh:
        json.dump(payload, fh, indent=1, sort_keys=True)
        fh.write("\n")
    body = json.dumps(payload["sweep"], sort_keys=True).encode()
    print(f"\nwrote {dest}")
    print(f"sweep digest (excl. timing): {hashlib.sha256(body).hexdigest()[:12]}")
    print(f"elapsed {meta['elapsed_s']}s")
    return 0


if __name__ == "__main__":
    sys.exit(main())
