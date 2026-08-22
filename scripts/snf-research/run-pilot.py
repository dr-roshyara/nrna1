"""KOS-SNF pilot runner — reproducible entry point.

Usage:
    python3 scripts/snf-research/run-pilot.py

What it does (in order):
  1. GATE — runs the d_SNF sanity test; refuses to run mechanisms if it fails.
  2. Loads KOS-SNF-pilot-100.json (gold lives here; loaded but NEVER given to
     a mechanism).
  3. Runs every mechanism on every expression with ONLY `(expression, context)`
     — the black-box contract. Gold is read only by evaluator.py afterwards.
  4. Evaluates (verdicts), aggregates metrics, writes:
        docs/knowledgeos/brainstorming/KOS-SNF-pilot-results.json
        docs/knowledgeos/brainstorming/KOS-SNF-pilot-metrics.json
  5. Prints a compact per-mechanism + consensus summary.

Reproducibility: the mechanisms and evaluator are deterministic; `seed` is
fixed for any stochasticity, and a `meta` block records versions + seed +
input hash. Re-running with the same corpus reproduces the same numbers.
"""

from __future__ import annotations

import hashlib
import json
import os
import sys

import _bootstrap  # noqa: F401

from distance import d_snf_sanity_test
from evaluator import (verdict_for, verdict_pair, _case_consensus,
                       consensus_pair_stats, MECH_NAMES)
from mechanisms import instantiate
from metrics import (compute_measurement_vector, abstention_stats,
                     ambiguity_stats)

SEED = 20260822


def _here_dir() -> str:
    return os.path.dirname(os.path.abspath(__file__))


def _out_dir() -> str:
    return os.path.normpath(os.path.join(
        _here_dir(), "..", "..", "docs", "knowledgeos", "brainstorming"))


def _load_corpus() -> list[dict]:
    path = os.path.join(_out_dir(), "KOS-SNF-pilot-100.json")
    with open(path) as f:
        data = json.load(f)
    return data["cases"]


def _gate() -> None:
    results = d_snf_sanity_test()
    ok = all(r["pass"] for r in results)
    print("=" * 78)
    print("GATE: d_SNF sanity test")
    for r in results:
        print(f"  {r['pair']} {r['label']:<26} d={r['d_snf']:.3f} "
              f"bound<={r['bound']}  {'PASS' if r['pass'] else 'FAIL'}")
    if not ok:
        print("\nGATE FAILED — refusing to run mechanisms. "
              "Fix the metric first (investigate representation/evaluator).")
        sys.exit(1)
    print("GATE PASSES — mechanisms may run.\n")


def _mechanism_context(case: dict) -> dict:
    # Mechanisms receive ONLY expression + context (domain, language).
    # Everything else in the case (gold, category, relation) is withheld.
    return dict(case["context"])


def run_pilot() -> tuple[list, list]:
    cases = _load_corpus()
    mechs = {name: instantiate(name) for name in MECH_NAMES}

    # ---------------- RUN (no gold in scope) ----------------
    outputs: dict[str, dict] = {}   # (case_id, mech) -> CandidateSet(s)
    for case in cases:
        cid = case["id"]
        ctx = _mechanism_context(case)
        expr = case["expressions"]
        ambiguous = case["gold"]["ambiguous"]
        for mname, m in mechs.items():
            if ambiguous:
                outputs[(cid, mname)] = m.interpret(expr[0], ctx)
            else:
                outputs[(cid, mname)] = (m.interpret(expr[0], ctx),
                                         m.interpret(expr[1], ctx))

    # ---------------- EVALUATE (gold enters here only) ----------------
    verdicts: list[dict] = []
    consensus_events: list[dict] = []
    consensus_pair: list[dict] = []
    for case in cases:
        cid = case["id"]
        ambiguous = case["gold"]["ambiguous"]
        by_mech: dict[str, dict] = {}
        for mname in MECH_NAMES:
            out = outputs[(cid, mname)]
            if ambiguous:
                v = verdict_for(case, mname, out)
            else:
                o1, o2 = out
                v = verdict_pair(case, mname, o1, o2)
            verdicts.append(v)
            by_mech[mname] = out
        cons = _case_consensus(case, by_mech)
        if cons is not None:
            consensus_events.append(cons)
        consensus_pair.append(consensus_pair_stats(case, by_mech))

    return cases, verdicts, consensus_events, consensus_pair


def _collect_raw(cases, verdicts, consensus_events, mechs) -> dict:
    """Verbatim per-case/per-mechanism record for the results file."""
    raw = {}
    for case in cases:
        cid = case["id"]
        raw[cid] = {
            "expressions": case["expressions"],
            "category": case["category"],
            "relation": case["relation"],
            "gold_ambiguous": case["gold"]["ambiguous"],
            "per_mechanism": {},
        }
        for v in verdicts:
            if v["case"] == cid:
                raw[cid]["per_mechanism"][v["mechanism"]] = {
                    "verdict": v["verdict"],
                    "correct": v["correct"],
                    "pair_d": v.get("pair_d"),
                    "confidence": v["conf"],
                    "uncertainty": v["uncertainty"],
                    "note": v["note"],
                }
    for e in consensus_events:
        raw[e["case"]]["consensus"] = {
            "members": e["members"],
            "false": e["false"],
        }
    return raw


def _aggregate_pairwise(pair_stats: list[dict]) -> dict:
    """Aggregate pairwise consensus over cases, per category."""
    agg: dict[str, dict] = {"all": {"agreeing": 0, "false": 0}}
    for ps in pair_stats:
        key = agg.setdefault(ps["category"], {"agreeing": 0, "false": 0})
        key["agreeing"] += ps["agreeing_pairs"]
        key["false"] += ps["false_pairs"]
        agg["all"]["agreeing"] += ps["agreeing_pairs"]
        agg["all"]["false"] += ps["false_pairs"]
    for key in agg:
        a = agg[key]
        a["false_rate"] = round(a["false"] / a["agreeing"], 4) if a["agreeing"] else None
    return agg


def main() -> None:
    _gate()

    cases, verdicts, consensus_events, consensus_pair = run_pilot()

    vector = compute_measurement_vector(verdicts, consensus_events)
    vector["consensus_pairwise"] = _aggregate_pairwise(consensus_pair)
    abst = abstention_stats(verdicts, cases)
    amb = ambiguity_stats(verdicts, cases)

    out_dir = _out_dir()
    corpus_hash = hashlib.sha256(
        json.dumps([c for c in cases], sort_keys=True).encode()).hexdigest()[:12]
    meta = {
        "experiment": "KOS-SNF research simulation — Phase 1 pilot (100 cases)",
        "seed": SEED,
        "corpus": "KOS-SNF-pilot-100.json",
        "corpus_sha256_prefix": corpus_hash,
        "thresholds": {"tau_eval": 0.40, "tau_amb": 0.55, "tau_conv": 0.40},
        "note": "Gold was never passed to a mechanism. Mechanisms received "
                "only (expression, context). Identity authority is absent: no "
                "score here confers Identity(E, theta).",
    }

    results = {
        "meta": meta,
        "cases": _collect_raw(cases, verdicts, consensus_events, MECH_NAMES),
        "verdicts": verdicts,
        "consensus_events": consensus_events,
        "consensus_pair_stats": consensus_pair,
    }
    metrics = {
        "meta": meta,
        "measurement_vector": vector,
        "abstention_quality": abst,
        "ambiguity_handling": amb,
    }

    with open(os.path.join(out_dir, "KOS-SNF-pilot-results.json"), "w") as f:
        json.dump(results, f, indent=2)
    with open(os.path.join(out_dir, "KOS-SNF-pilot-metrics.json"), "w") as f:
        json.dump(metrics, f, indent=2)

    print("=" * 78)
    print("KOS-SNF PILOT (100 cases) — summary")
    print(f"inter-mechanism kappa: {vector['inter_mechanism_kappa']}")
    cons = vector["consensus"]
    print(f"consensus events: {cons['events']} "
          f"(true={cons['true_consensus']}, false={cons['false_consensus']}) "
          f"correctness_rate={cons['consensus_correctness_rate']}")
    print(f"  false consensus by category: {cons['fc_by_category']}")
    pw = vector["consensus_pairwise"]
    print(f"pairwise consensus (agreeing_pairs, false, false_rate) — all: "
          f"{pw['all']}")
    for cat in ("equivalent", "transformation", "distinct", "ambiguous",
                "adversarial"):
        print(f"  {cat:<14} {pw.get(cat)}")
    print("-" * 78)
    hdr = f"{'mech':<7}{'C':>4}{'NC':>5}{'T':>7}{'R':>7}{'A':>7}{'H':>7}{'Brier':>8}{'ECE':>7}"
    print(hdr)
    for mname in MECH_NAMES:
        v = vector["mechanisms"][mname]
        cal = v["calibration"]
        print(f"{mname:<7}{v['C']:>4}{v['NC']:>5}{v['T']:>7}"
              f"{str(v['R']):>7}{v['A']:>7}{str(v['H']):>7}"
              f"{str(cal['brier']):>8}{str(cal['ece']):>7}")
    print("-" * 78)
    print("abstention quality (A_appropriate/A_false/A_missed | prec/rec):")
    for mname in MECH_NAMES:
        a = abst[mname]
        p = a["precision"] if a["precision"] is not None else "-"
        r = a["recall"] if a["recall"] is not None else "-"
        print(f"  {mname:<7} {a['A_appropriate']:>2}/{a['A_false']:>2}/{a['A_missed']:>2}"
              f"  | {p} / {r}")
    print("ambiguity handling (handled/20: abstained, hedged, overcommitted, wrong):")
    for mname in MECH_NAMES:
        a = amb[mname]
        print(f"  {mname:<7} handled={a['handled']:>2}/20 "
              f"(abst={a['abstained']}, hedge={a['hedged']}, "
              f"over={a['overcommitted']}, wrong={a['wrong']})")
    print("=" * 78)
    print("wrote KOS-SNF-pilot-results.json and KOS-SNF-pilot-metrics.json")
    print("next: interpret the measurement in KOS-SNF-RESEARCH-REFINEMENT-001.md")


if __name__ == "__main__":
    main()
