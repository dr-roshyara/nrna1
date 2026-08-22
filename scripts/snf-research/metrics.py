"""Metrics — measurement vector M and supporting statistics.

Measurement vector M = (C, NC, FC, T, K, R, A, H, Cal):

  C   correct convergence  — pair cases where the mechanism's candidates match
                             gold on BOTH expressions (any pair category)
  NC  non-convergence      — pair cases where the mechanism answered but at
                             least one candidate mismatched gold
  T   transformation stability — fraction of the 20 transformation cases CORRECT
  K   inter-mechanism agreement — Fleiss' kappa over per-case verdicts
  R   selective recall     — CORRECT / (CORRECT + WRONG) among answered pair cases
  A   abstention rate      — abstained cases / 100
  H   hedging rate         — answered cases marked UNC_AMBIGUOUS / answered
  Cal calibration          — Brier + ECE of confidence vs correctness

FC (false consensus) is a CROSS-mechanism observable, not per-mechanism:
  consensus event  = >=2 mechanisms say "the same thing" on a case
  true  consensus  = on equivalent/transformation cases (gold says same meaning)
  false consensus  = on distinct/adversarial/ambiguous cases (consensus != gold)
  The consensus-correctness rate answers "does agreement imply truth?".

Supporting:
  * abstention quality  (A_appropriate / A_false / A_missed, precision, recall)
  * selective accuracy + coverage + risk-coverage curve (Zero-lens evaluables)
  * ambiguity handling  (hedged vs overcommitted vs abstained, per mechanism)
  * calibration         (Brier, ECE, reliability bins; hedge vs assert error rate)
"""

from __future__ import annotations

import _bootstrap  # noqa: F401

from ir import UNC_AMBIGUOUS, UNC_UNKNOWN
from evaluator import (verdict_for, verdict_pair, abstention_warranted,
                       TAU_EVAL, TAU_AMB, TAU_CONV, MECH_NAMES)

PAIR_CATS = {"equivalent", "distinct", "transformation", "adversarial"}
AMBIG_CATS = {"ambiguous"}
N_PAIRS = 80
N_TOTAL = 100


# ---------------------------------------------------------------------------
# Fleiss' kappa
# ---------------------------------------------------------------------------

def fleiss_kappa(subjects: list[list[str]]) -> float:
    """Fleiss' kappa over N subjects x M raters (categorical labels).

    Each entry in `subjects` is the list of M labels the raters gave to one
    subject. Returns -1..1 (1 = perfect agreement; 0 = chance).
    """
    n = len(subjects)
    m = len(subjects[0]) if subjects else 0
    if n == 0 or m == 0:
        return 0.0
    labels = sorted({lab for sub in subjects for lab in sub})
    k = len(labels)
    if k == 0:
        return 0.0
    # P_j: extent of agreement on subject j.
    P_j = []
    for sub in subjects:
        counts = {lab: sub.count(lab) for lab in labels}
        n_r = m
        P_j.append((sum(c * c for c in counts.values()) - n_r) /
                   (n_r * (n_r - 1)))
    P_bar = sum(P_j) / n
    # p_c: probability each label is used overall.
    total = n * m
    p_c = 0.0
    for lab in labels:
        cnt = sum(1 for sub in subjects for l in sub if l == lab)
        p_c += (cnt / total) ** 2
    denom = 1.0 - p_c
    return (P_bar - p_c) / denom if denom > 0 else 0.0


# ---------------------------------------------------------------------------
# Calibration
# ---------------------------------------------------------------------------

def _bins(points: list[tuple[float, int]], n_bins: int = 10) -> list[dict]:
    """points = [(confidence, correct0/1), ...] -> ECE bins (width equal)."""
    bins = [{"lo": i / n_bins, "hi": (i + 1) / n_bins, "conf": 0.0,
             "acc": 0.0, "n": 0} for i in range(n_bins)]
    for conf, correct in points:
        idx = min(n_bins - 1, int(conf * n_bins))
        if conf == 1.0:
            idx = n_bins - 1
        bins[idx]["conf"] += conf
        bins[idx]["acc"] += correct
        bins[idx]["n"] += 1
    for b in bins:
        if b["n"]:
            b["conf"] /= b["n"]
            b["acc"] /= b["n"]
        b["conf"] = round(b["conf"], 4)
        b["acc"] = round(b["acc"], 4)
    return bins


def calibration(points: list[tuple[float, int]]) -> dict:
    """points = [(confidence, correct0/1), ...]. Brier + ECE + reliability."""
    if not points:
        return {"brier": None, "ece": None, "n": 0, "reliability": []}
    brier = sum((c - ok) ** 2 for c, ok in points) / len(points)
    bins = _bins(points)
    total = sum(b["n"] for b in bins) or 1
    ece = sum((b["n"] / total) * abs(b["acc"] - b["conf"]) for b in bins)
    return {"brier": round(brier, 4), "ece": round(ece, 4),
            "n": len(points), "reliability": bins}


def risk_coverage(points: list[tuple[float, int]],
                  thresholds=(0.0, 0.5, 0.6, 0.7, 0.8, 0.9, 0.95)) -> list[dict]:
    """Selective classification: accuracy among answered above a confidence
    threshold, and the coverage that buys."""
    total = len(points) or 1
    out = []
    for t in thresholds:
        sel = [(c, ok) for c, ok in points if c >= t]
        if not sel:
            out.append({"threshold": t, "coverage": 0.0, "accuracy": None,
                        "n": 0})
            continue
        acc = sum(ok for _, ok in sel) / len(sel)
        out.append({"threshold": t, "coverage": round(len(sel) / total, 4),
                    "accuracy": round(acc, 4), "n": len(sel)})
    return out


# ---------------------------------------------------------------------------
# Abstention quality
# ---------------------------------------------------------------------------

def abstention_stats(verdicts: list[dict], cases: list[dict]) -> dict:
    """Per-mechanism abstention quality (Zero-lens gold)."""
    stats = {}
    for mech in MECH_NAMES:
        mv = [v for v in verdicts if v["mechanism"] == mech]
        appropriate = 0
        false_ = 0
        missed = 0
        for v in mv:
            case = next(c for c in cases if c["id"] == v["case"])
            warranted = abstention_warranted(case, mech)
            if v["verdict"] == "ABSTAINED":
                appropriate += int(warranted)
                false_ += int(not warranted)
            elif v["verdict"] in ("HANDLED_VIA_ABSTENTION",):
                appropriate += int(warranted)
                false_ += int(not warranted)
            elif warranted:
                missed += 1
        denom_p = appropriate + false_
        denom_r = appropriate + missed
        stats[mech] = {
            "A_appropriate": appropriate,
            "A_false": false_,
            "A_missed": missed,
            "precision": round(appropriate / denom_p, 4) if denom_p else None,
            "recall": round(appropriate / denom_r, 4) if denom_r else None,
        }
    return stats


# ---------------------------------------------------------------------------
# Ambiguity handling
# ---------------------------------------------------------------------------

def ambiguity_stats(verdicts: list[dict], cases: list[dict]) -> dict:
    out = {}
    for mech in MECH_NAMES:
        amb = [v for v in verdicts if v["mechanism"] == mech
               and v["category"] == "ambiguous"]
        n = len(amb)
        abst = sum(1 for v in amb if v["verdict"] == "HANDLED_VIA_ABSTENTION")
        hedged = sum(1 for v in amb if v["verdict"] == "HANDLED_VIA_HEDGING")
        over = sum(1 for v in amb if v["verdict"] == "OVERCOMMITTED")
        wrong = sum(1 for v in amb if v["verdict"] == "WRONG")
        out[mech] = {
            "n": n,
            "handled": abst + hedged,
            "handled_rate": round((abst + hedged) / n, 4) if n else None,
            "abstained": abst,
            "hedged": hedged,
            "overcommitted": over,
            "wrong": wrong,
        }
    return out


# ---------------------------------------------------------------------------
# Main aggregation
# ---------------------------------------------------------------------------

def compute_measurement_vector(verdicts: list[dict],
                               consensus_events: list[dict]) -> dict:
    """Per-mechanism measurement vector + cross-mechanism agreement."""
    vec = {}
    for mech in MECH_NAMES:
        mv = [v for v in verdicts if v["mechanism"] == mech]
        pairs = [v for v in mv if v["category"] in PAIR_CATS]
        trans = [v for v in mv if v["category"] == "transformation"]
        amb = [v for v in mv if v["category"] == "ambiguous"]
        all_cases = [v for v in mv]

        C = sum(1 for v in pairs if v["verdict"] == "CORRECT")
        NC = sum(1 for v in pairs if v["verdict"] == "WRONG")
        ABST = sum(1 for v in pairs if v["verdict"] == "ABSTAINED")
        assert C + NC + ABST == len(pairs), f"{mech}: verdict accounting broken"

        T = sum(1 for v in trans if v["verdict"] == "CORRECT") / 20

        # Abstention rate over ALL cases.
        A = sum(1 for v in all_cases if v["verdict"] == "ABSTAINED"
                or v["verdict"] == "HANDLED_VIA_ABSTENTION") / N_TOTAL

        # Hedging rate among answered cases (any category).
        answered = [v for v in all_cases if v["conf"] is not None]
        n_answered = len(answered)
        n_hedge = sum(1 for v in answered if v["hedged"])
        H = round(n_hedge / n_answered, 4) if n_answered else None

        R = C / (C + NC) if (C + NC) else None

        # Calibration over answered pair candidates: (confidence, correct).
        cal_points = [(v["conf"], int(v["correct"])) for v in pairs
                      if v["conf"] is not None]
        cal = calibration(cal_points)
        rc = risk_coverage(cal_points)

        vec[mech] = {
            "C": C, "NC": NC, "ABST_pair": ABST, "T": round(T, 4),
            "R": R, "A": round(A, 4), "H": H,
            "pair_coverage": round((C + NC) / N_PAIRS, 4),
            "calibration": cal,
            "risk_coverage": rc,
        }

    # Inter-mechanism agreement (Fleiss' kappa over verdicts).
    case_ids = sorted({v["case"] for v in verdicts})
    subjects = [[next(v["verdict"] for v in verdicts
                      if v["case"] == cid and v["mechanism"] == mech)
                 for mech in MECH_NAMES] for cid in case_ids]
    kappa = fleiss_kappa(subjects)

    # Consensus statistics.
    true_c = [e for e in consensus_events if not e["false"]]
    false_c = [e for e in consensus_events if e["false"]]
    n_events = len(true_c) + len(false_c)
    consensus_correctness = (len(true_c) / n_events) if n_events else None
    fc_adversarial = [e for e in false_c if e["category"] == "adversarial"]
    fc_ambiguous = [e for e in false_c if e["category"] == "ambiguous"]
    fc_distinct = [e for e in false_c if e["category"] == "distinct"]

    return {
        "mechanisms": vec,
        "inter_mechanism_kappa": round(kappa, 4),
        "consensus": {
            "events": n_events,
            "true_consensus": len(true_c),
            "false_consensus": len(false_c),
            "consensus_correctness_rate": consensus_correctness,
            "fc_by_category": {
                "adversarial": len(fc_adversarial),
                "ambiguous": len(fc_ambiguous),
                "distinct": len(fc_distinct),
                "equivalent_or_transformation": len([e for e in false_c
                                                     if e["category"] in
                                                     ("equivalent",
                                                      "transformation")]),
            },
            "adversarial_fc_rate": round(len(fc_adversarial) / 20, 4),
        },
    }
