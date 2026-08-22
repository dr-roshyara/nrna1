"""Evaluator — the ONLY module that reads gold.

NO-GOLD BOUNDARY (the corrected apparatus):
  * mechanisms.py receives only `(expression, context)`.
  * this module receives the mechanism CandidateSets AND the gold from the
    corpus, and compares them AFTER the fact via d_snf.
  * nothing a mechanism produces is ever passed back into a mechanism.

Verdict per (case, mechanism):
  * pair cases (equivalent/transformation/distinct/adversarial):
      CORRECT    — answered both expressions, each candidate within tau_eval
                    of its gold IR
      WRONG      — answered both, at least one candidate outside tau_eval
      ABSTAINED  — abstained on either expression
  * ambiguous single-expression cases:
      HANDLED_VIA_ABSTENTION — abstained (Zero lens: right to abstain)
      HANDLED_VIA_HEDGING    — answered, marked UNC_AMBIGUOUS, and the
                    candidate matches a gold reading (intended or alt)
      OVERCOMMITTED          — answered with a single RESOLVED reading
                    (matched a gold reading, but asserted underdetermination)
      WRONG                  — answered, matches no gold reading

  OVERCOMMITTED is deliberately counted as NOT handled: the pilot's gold says
  the case is underdetermined, so a confident single reading is a
  false-consensus risk even when it happens to pick a plausible reading.

False consensus (across mechanisms):
  a consensus event on a case = >=2 mechanisms answered and all their top
  candidates are pairwise within tau_conv (they say "the same thing").
  On equivalent/transformation the consensus is TRUE (gold says same meaning).
  On distinct/adversarial/ambiguous the consensus contradicts gold -> FALSE.
  This measures whether inter-mechanism agreement == truth (it is not, by
  construction, guaranteed — Gödel lens: no mechanism certifies itself).
"""

from __future__ import annotations

import _bootstrap  # noqa: F401

from distance import d_snf
from ir import IR, UNC_AMBIGUOUS, UNC_UNKNOWN
from lexicon import (NOUNS, VERBS, REGISTERED_PREDICATES, REGISTERED_ENTITIES,
                     tokenize)

# Research hyper-parameters (not architectural thresholds).
TAU_EVAL = 0.40      # pair-case correctness threshold
TAU_AMB = 0.55       # ambiguous-case "matches a gold reading" threshold
TAU_CONV = 0.40      # consensus/convergence threshold
AMBIG_CATS = {"ambiguous"}

MECH_NAMES = ("SNF-A", "SNF-B", "SNF-C", "SNF-D", "SNF-E", "SNF-N")


# ---------------------------------------------------------------------------
# World-knowledge boundary for warranted abstention (public, not gold)
# ---------------------------------------------------------------------------

def _unregistered_in_world(expression: str) -> bool:
    """Does the surface name anything outside the SNF-D closed world?

    Mirrors SNF-D's registry check (canonical identifiers outside the
    registered sets). This is public mechanism configuration, not gold.
    """
    toks = tokenize(expression)
    for t in toks:
        if t in NOUNS and NOUNS[t] not in REGISTERED_ENTITIES:
            return True
        if t in VERBS and VERBS[t][0] not in REGISTERED_PREDICATES:
            return True
    return False


def abstention_warranted(case: dict, mechanism: str) -> bool:
    """Should this mechanism abstain on this case (Zero-lens gold)?"""
    if case["gold"]["ambiguous"]:
        return True
    if mechanism == "SNF-D":
        return any(_unregistered_in_world(e) for e in case["expressions"])
    return False


# ---------------------------------------------------------------------------
# Per-case verdict
# ---------------------------------------------------------------------------

def _top_ir(out: dict) -> IR | None:
    if out["abstained"] or not out["candidates"]:
        return None
    return IR.from_dict(out["candidates"][0]["representation"])


def _match_gold(cand: IR, gold_irs: list[dict], tau: float) -> bool:
    return any(d_snf(cand, IR.from_dict(g)) <= tau for g in gold_irs)


def verdict_for(case: dict, mechanism: str, out: dict) -> dict:
    """Return a verdict dict for one (case, mechanism)."""
    cid = case["id"]
    cat = case["category"]
    gold_irs = case["gold"]["irs"]
    ambiguous = case["gold"]["ambiguous"]

    if ambiguous:
        # Single-expression ambiguous case.
        if out["abstained"]:
            return {"case": cid, "category": cat, "mechanism": mechanism,
                    "verdict": "HANDLED_VIA_ABSTENTION", "correct": True,
                    "note": "abstained on underdetermined surface",
                    "conf": None, "hedged": False, "uncertainty": "ABSTAINED"}
        cand = _top_ir(out)
        if cand is None:
            return {"case": cid, "category": cat, "mechanism": mechanism,
                    "verdict": "WRONG", "correct": False,
                    "note": "no candidate produced", "conf": None,
                    "hedged": False, "uncertainty": "UNKNOWN"}
        matches = _match_gold(cand, gold_irs, TAU_AMB)
        unc = out["candidates"][0]["uncertainty"]
        if matches and unc == UNC_AMBIGUOUS:
            return {"case": cid, "category": cat, "mechanism": mechanism,
                    "verdict": "HANDLED_VIA_HEDGING", "correct": True,
                    "note": f"hedged reading; d={d_snf(cand, IR.from_dict(gold_irs[0])):.3f}",
                    "conf": cand.confidence, "hedged": True,
                    "uncertainty": UNC_AMBIGUOUS}
        if matches:
            return {"case": cid, "category": cat, "mechanism": mechanism,
                    "verdict": "OVERCOMMITTED", "correct": False,
                    "note": f"single RESOLVED reading asserted on underdetermined case "
                            f"(d={d_snf(cand, IR.from_dict(gold_irs[0])):.3f})",
                    "conf": cand.confidence, "hedged": False,
                    "uncertainty": unc}
        return {"case": cid, "category": cat, "mechanism": mechanism,
                "verdict": "WRONG", "correct": False,
                "note": "no match to any gold reading", "conf": cand.confidence,
                "hedged": False, "uncertainty": unc}


def verdict_pair(case: dict, mechanism: str, out1: dict, out2: dict) -> dict:
    """Verdict for a two-expression pair case."""
    cid = case["id"]
    cat = case["category"]
    g1 = IR.from_dict(case["gold"]["irs"][0])
    g2 = IR.from_dict(case["gold"]["irs"][1])
    base = {"case": cid, "category": cat, "mechanism": mechanism}

    if out1["abstained"] or out2["abstained"]:
        return {**base, "verdict": "ABSTAINED", "correct": False,
                "note": "abstained on one or both expressions",
                "conf": None, "pair_d": None, "hedged": False,
                "uncertainty": "ABSTAINED"}
    c1 = _top_ir(out1)
    c2 = _top_ir(out2)
    if c1 is None or c2 is None:
        return {**base, "verdict": "ABSTAINED", "correct": False,
                "note": "no candidate on one side", "conf": None, "pair_d": None,
                "hedged": False, "uncertainty": "ABSTAINED"}
    d1 = d_snf(c1, g1)
    d2 = d_snf(c2, g2)
    ok1, ok2 = d1 <= TAU_EVAL, d2 <= TAU_EVAL
    pair_d = d_snf(c1, c2)
    hedged = (out1["candidates"][0]["uncertainty"] == UNC_AMBIGUOUS
              or out2["candidates"][0]["uncertainty"] == UNC_AMBIGUOUS)
    unc = "AMBIGUOUS" if hedged else "RESOLVED"
    if ok1 and ok2:
        return {**base, "verdict": "CORRECT", "correct": True,
                "note": f"both match gold (d1={d1:.3f}, d2={d2:.3f}, "
                        f"pair_d={pair_d:.3f})",
                "conf": (c1.confidence + c2.confidence) / 2.0,
                "pair_d": pair_d, "hedged": hedged, "uncertainty": unc}
    return {**base, "verdict": "WRONG", "correct": False,
            "note": f"mismatch (d1={d1:.3f}, d2={d2:.3f}, pair_d={pair_d:.3f})",
            "conf": (c1.confidence + c2.confidence) / 2.0,
            "pair_d": pair_d, "hedged": hedged, "uncertainty": unc}


# ---------------------------------------------------------------------------
# Consensus / false-consensus detection (across mechanisms)
# ---------------------------------------------------------------------------

GOLD_SAME_RELATIONS = {"EQUIVALENT", "PRESERVING"}


def _gold_says_same(case: dict) -> bool:
    """Use the corpus's DECLARED relation as the gold verdict, not a d_snf
    inference. The gold explicitly decides whether two expressions are the same
    meaning; the metric must not override it (e.g. presence traps are declared
    DISTINCT even though d_snf(gold1,gold2) is small)."""
    if case["gold"]["ambiguous"]:
        return False
    return case["relation"] in GOLD_SAME_RELATIONS


def _case_consensus(case: dict, by_mech: dict[str, dict]) -> dict | None:
    """Detect a consensus event across mechanisms on one case.

    Returns None if no consensus; else a dict with whether it is FALSE.
    """
    cid = case["id"]
    ambiguous = case["gold"]["ambiguous"]
    is_pair = not ambiguous

    answered: dict[str, IR] = {}
    if is_pair:
        for m, (o1, o2) in by_mech.items():
            if not o1["abstained"] and not o2["abstained"]:
                c1, c2 = _top_ir(o1), _top_ir(o2)
                if c1 is not None and c2 is not None:
                    answered[m] = (c1, c2)
        if len(answered) < 2:
            return None
        irs = [ir for (c1, c2) in answered.values() for ir in (c1, c2)]
        all_pairwise = all(d_snf(a, b) <= TAU_CONV
                           for i, a in enumerate(irs) for b in irs[i + 1:])
        if not all_pairwise:
            return None
        gold_says_same = _gold_says_same(case)
        return {"case": cid, "category": case["category"],
                "members": sorted(answered), "converged": True,
                "false": not gold_says_same}
    else:
        # Ambiguous single-expression: confident RESOLVED consensus.
        for m, out in by_mech.items():
            if out["abstained"] or not out["candidates"]:
                continue
            if out["candidates"][0]["uncertainty"] != "RESOLVED":
                continue
            c = _top_ir(out)
            if c is not None:
                answered[m] = c
        if len(answered) < 2:
            return None
        irs = list(answered.values())
        all_pairwise = all(d_snf(a, b) <= TAU_CONV
                           for i, a in enumerate(irs) for b in irs[i + 1:])
        if not all_pairwise:
            return None
        return {"case": cid, "category": "ambiguous", "members": sorted(answered),
                "converged": True, "false": True}


def consensus_pair_stats(case: dict, by_mech: dict[str, dict]) -> dict:
    """Pairwise consensus: for every mechanism pair, did they agree — and was
    that agreement FALSE (contradicts gold)?

    The strict consensus (``_case_consensus``) requires ALL resolved mechanisms
    to agree; a single dissenter (e.g. the null baseline's wrong prior) can
    block it. Pairwise agreement catches a 2-mechanism false consensus even when
    others dissent — the finer-grained version of the same observable.
    """
    cid = case["id"]
    ambiguous = case["gold"]["ambiguous"]
    gold_says_same = _gold_says_same(case)

    cands: dict[str, dict] = {}
    if ambiguous:
        for m, out in by_mech.items():
            if out["abstained"] or not out["candidates"]:
                continue
            if out["candidates"][0]["uncertainty"] != "RESOLVED":
                continue
            c = _top_ir(out)
            if c is not None:
                cands[m] = {"a": c, "b": None}
    else:
        for m, (o1, o2) in by_mech.items():
            if o1["abstained"] or o2["abstained"]:
                continue
            c1, c2 = _top_ir(o1), _top_ir(o2)
            if c1 is not None and c2 is not None:
                cands[m] = {"a": c1, "b": c2}

    names = sorted(cands)
    agreeing_pairs = 0
    false_pairs = 0
    false_edges: list[tuple[str, str]] = []
    for i, m1 in enumerate(names):
        for m2 in names[i + 1:]:
            if not _pair_agrees(cands[m1], cands[m2], ambiguous):
                continue
            agreeing_pairs += 1
            is_false = (not gold_says_same) if not ambiguous else True
            if is_false:
                false_pairs += 1
                false_edges.append((m1, m2))
    return {
        "case": cid,
        "category": case["category"],
        "agreeing_pairs": agreeing_pairs,
        "false_pairs": false_pairs,
        "false_edges": false_edges,
        "gold_says_same": gold_says_same,
    }


def _pair_agrees(a: dict, b: dict, ambiguous: bool) -> bool:
    """Do two mechanisms' candidates agree with each other (and internally)?"""
    if ambiguous:
        return d_snf(a["a"], b["a"]) <= TAU_CONV
    if a["b"] is None or b["b"] is None:
        return False
    internal = d_snf(a["a"], a["b"]) <= TAU_CONV
    cross1 = d_snf(a["a"], b["a"]) <= TAU_CONV
    cross2 = d_snf(a["b"], b["b"]) <= TAU_CONV
    return internal and cross1 and cross2
