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

# D-7: corpus sizes are DERIVED from the data, never literals. The Phase-1 code
# divided by 20 / 80 / 100, which is silently wrong at any other corpus size.

ABSTAINED_VERDICTS = ("ABSTAINED", "HANDLED_VIA_ABSTENTION")

# Four-way abstention taxonomy (commission §15). FALSE_ACCEPTANCE is the most
# dangerous error and is reported as a CONSTRAINT to be bounded, never as a
# score to be maximised.
CORRECT_RESOLUTION = "CORRECT_RESOLUTION"
CORRECT_ABSTENTION = "CORRECT_ABSTENTION"
BLIND_ABSTENTION = "BLIND_ABSTENTION"
FALSE_ACCEPTANCE = "FALSE_ACCEPTANCE"
ABSTENTION_OUTCOMES = (CORRECT_RESOLUTION, CORRECT_ABSTENTION,
                       BLIND_ABSTENTION, FALSE_ACCEPTANCE)
FALSE_ACCEPTANCE_CONSTRAINT = "false_acceptance_rate <= epsilon"


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
# Confidence intervals (D-9) and coverage-matched calibration (D-5)
# ---------------------------------------------------------------------------

def bootstrap_ci(values: list[float], seed: int, iters: int = 2000,
                 alpha: float = 0.05) -> tuple:
    """Percentile bootstrap CI for the mean of `values`. Seeded => reproducible.

    A single deterministic run gives point estimates that read as more precise
    than they are; this is the interval the report must carry.
    """
    if not values:
        return (None, None)
    import random
    rng = random.Random(seed)
    n = len(values)
    means = []
    for _ in range(iters):
        means.append(sum(values[rng.randrange(n)] for _ in range(n)) / n)
    means.sort()
    lo = means[int((alpha / 2) * iters)]
    hi = means[min(iters - 1, int((1 - alpha / 2) * iters))]
    return (round(lo, 4), round(hi, 4))


def calibration_at_coverage(points: list[tuple[float, int]],
                            coverage: float) -> dict:
    """Calibration restricted to the most-confident `coverage` fraction.

    D-5: a mechanism that answers only its easiest cases will look better
    calibrated than one that answers everything. Comparing two mechanisms at
    the SAME coverage removes that artifact — the abstainer's advantage either
    survives the matching (calibration skill) or disappears (selection effect).
    """
    if not points or coverage <= 0:
        return {"brier": None, "ece": None, "n": 0, "coverage": coverage,
                "reliability": []}
    ordered = sorted(points, key=lambda p: -p[0])
    n_sel = max(1, min(len(ordered), round(coverage * len(ordered))))
    sel = ordered[:n_sel]
    out = calibration(sel)
    out["coverage"] = round(n_sel / len(ordered), 4)
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
# Abstention taxonomy — prudence vs inability (D-6)
# ---------------------------------------------------------------------------

def abstention_taxonomy(verdicts: list[dict], cases: list[dict]) -> dict:
    """Classify every (case, mechanism) into the four-way taxonomy.

        CORRECT_RESOLUTION  answered a determinable case, correctly
        CORRECT_ABSTENTION  abstained where abstention was warranted
        BLIND_ABSTENTION    abstained where the case was determinable
        FALSE_ACCEPTANCE    answered where it could not possibly know
                            (the most dangerous error — a CONSTRAINT)

    Two mechanisms with the same abstention RATE are separated here by outcome
    quality: this is what answers "epistemic discipline or merely weakness?".
    Cases that are neither abstentions nor warranted-abstentions but simply
    wrong are counted as `plain_error`, outside the taxonomy.
    """
    by_id = {c["id"]: c for c in cases}
    mechs = sorted({v["mechanism"] for v in verdicts})
    out = {}
    for mech in mechs:
        mv = [v for v in verdicts if v["mechanism"] == mech]
        counts = {k: 0 for k in ABSTENTION_OUTCOMES}
        plain_error = 0
        n_abstained = 0
        n_warranted = 0
        for v in mv:
            case = by_id.get(v["case"])
            if case is None:
                continue
            warranted = abstention_warranted(case, mech)
            abstained = v["verdict"] in ABSTAINED_VERDICTS
            n_abstained += int(abstained)
            n_warranted += int(warranted)
            if abstained and warranted:
                counts[CORRECT_ABSTENTION] += 1
            elif abstained and not warranted:
                counts[BLIND_ABSTENTION] += 1
            elif not abstained and warranted:
                counts[FALSE_ACCEPTANCE] += 1
            elif v.get("correct"):
                counts[CORRECT_RESOLUTION] += 1
            else:
                plain_error += 1
        n = len(mv) or 1
        out[mech] = {
            **counts,
            "plain_error": plain_error,
            "n": len(mv),
            "abstention_rate": round(n_abstained / n, 4),
            "warranted_cases": n_warranted,
            "false_acceptance_rate": (round(counts[FALSE_ACCEPTANCE] /
                                            n_warranted, 4)
                                      if n_warranted else 0.0),
            "correct_abstention_precision": (
                round(counts[CORRECT_ABSTENTION] / n_abstained, 4)
                if n_abstained else None),
            "constraint": FALSE_ACCEPTANCE_CONSTRAINT,
        }
    return out


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
                               consensus_events: list[dict],
                               cases: list[dict] | None = None) -> dict:
    """Per-mechanism measurement vector + cross-mechanism agreement.

    Every denominator is DERIVED from the verdicts/cases actually present, so
    the same code is correct at n=100 and n=1000 (D-7).
    """
    mech_names = sorted({v["mechanism"] for v in verdicts}) or list(MECH_NAMES)
    n_adversarial_cases = len({v["case"] for v in verdicts
                               if v["category"] == "adversarial"})
    vec = {}
    for mech in mech_names:
        mv = [v for v in verdicts if v["mechanism"] == mech]
        pairs = [v for v in mv if v["category"] in PAIR_CATS]
        trans = [v for v in mv if v["category"] == "transformation"]
        amb = [v for v in mv if v["category"] == "ambiguous"]
        all_cases = [v for v in mv]

        C = sum(1 for v in pairs if v["verdict"] == "CORRECT")
        NC = sum(1 for v in pairs if v["verdict"] == "WRONG")
        ABST = sum(1 for v in pairs if v["verdict"] == "ABSTAINED")
        assert C + NC + ABST == len(pairs), f"{mech}: verdict accounting broken"

        T = ((sum(1 for v in trans if v["verdict"] == "CORRECT") / len(trans))
             if trans else None)

        # Abstention rate over ALL cases seen by this mechanism.
        A = ((sum(1 for v in all_cases if v["verdict"] in ABSTAINED_VERDICTS)
              / len(all_cases)) if all_cases else None)

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
            "C": C, "NC": NC, "ABST_pair": ABST,
            "T": round(T, 4) if T is not None else None,
            "R": R, "A": round(A, 4) if A is not None else None, "H": H,
            "n_pairs": len(pairs), "n_cases": len(all_cases),
            "pair_coverage": (round((C + NC) / len(pairs), 4)
                              if pairs else None),
            "calibration": cal,
            "risk_coverage": rc,
        }

    # Inter-mechanism agreement (Fleiss' kappa over verdicts).
    case_ids = sorted({v["case"] for v in verdicts})
    by_case_mech = {(v["case"], v["mechanism"]): v["verdict"] for v in verdicts}
    subjects = [[by_case_mech[(cid, mech)] for mech in mech_names
                 if (cid, mech) in by_case_mech] for cid in case_ids]
    subjects = [row for row in subjects if len(row) == len(mech_names)]
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
            "adversarial_fc_rate": (round(len(fc_adversarial) /
                                          n_adversarial_cases, 4)
                                    if n_adversarial_cases else None),
        },
    }
