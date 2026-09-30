#!/usr/bin/env python3
"""
KSME-17 -- Minimality Computation (completed after fixing the closure bug).

CONSTRUCTION throughout. Fixes the two problems that made the earlier quick
check untrustworthy:
  1. fresh_id() is now content-addressed (see ess.py) -- identity no longer
     depends on exploration order, so a BFS reachable-closure is actually
     well-defined.
  2. E is explicitly BFS-closed under T before behavioral_partition() or
     any minimality computation is run -- the same fix already applied to
     Track-B's so_model.py (reachable_closure()) and required by bse.py's
     own documented assumptions for H=None to mean genuine unbounded ~_B.

Bounded regime, disclosed: operations parameterized by only a SMALL finite
label alphabet (evidence in {"e0","e1"}, revision values in {"p0","p1"}) so
the reachable closure from one seed state is actually finite. This is a
CONSTRUCTION choice for tractability, not a claim about the real ESS's full
(unbounded) universe.

One candidate per ambiguous operation is fixed for this regime (C1, the
"minimal"/most conservative reading in each case) -- disclosed, not implying
C1 is the "correct" completion; KSME-17's materiality results already
establish C1 vs C2 diverge, so this regime tests component/partition/
representation minimality WITHIN one fixed, disclosed choice.
"""
import json
import sys
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/.claude/scripts/knowledgeos-ksme")
from bse import Regime, component_minimality, partition_minimality, representation_minimality
from ess import K, Assertion, assertion_created, evidence_added_C1, value_revised, rollback_C1_no_marker


def pick_active(k: K):
    actives = [a for a in k.A if not a.retired]
    return actives[0] if actives else None


# ============================================================ bounded operation set (CONSTRUCTION)
EVIDENCE_ALPHABET = ["e1"]        # only one addable item beyond the seed's e0 -- keeps closure small
REVISION_ALPHABET = ["p1"]        # only one revision target


def op_add_evidence(k: K, ev: str) -> K:
    a = pick_active(k)
    if a is None:
        return k
    return evidence_added_C1(k, a.id, ev)


def op_revise(k: K, v_new: str) -> K:
    """CONSTRUCTION FIX: tau is fixed at 1 for every revision, not
    incremented (a.tau+1) as originally written. Incrementing tau feeds
    the content-addressed fresh_id hash, so it strictly grows the reachable
    state space forever (0,1,2,3,...) -- an unbounded, not merely large,
    closure. This was the actual root cause the first BFS run hit. Fixing
    tau to a constant for this bounded, disclosed regime lets content
    stabilize (repeated revision to the same value becomes idempotent)."""
    a = pick_active(k)
    if a is None:
        return k
    return value_revised(k, a.id, v_new, tau=1)


SEED = assertion_created(K(), P="p0", Sigma="Weak", E=frozenset({"e0"}), tau=0, Pi="s0")


def op_rollback(k: K, reason: str) -> K:
    return rollback_C1_no_marker(SEED, reason)


T = {
    "add_evidence": (op_add_evidence, EVIDENCE_ALPHABET),
    "revise": (op_revise, REVISION_ALPHABET),
    "rollback_to_seed": (op_rollback, ["reset"]),
}


def bfs_closure(seed: K, T: dict, max_states: int = 2000) -> list:
    """CONSTRUCTION FIX: genuine BFS reachable closure, the same discipline
    already used for Track-B's so_model.py and required by bse.py's own
    documented H=None assumptions. Without this, behavioral_partition()
    silently falls back to raw-representation comparison for any
    off-universe successor state -- exactly the artifact caught and
    discarded in the earlier quick check."""
    seen = {seed}
    frontier = [seed]
    while frontier:
        nxt_frontier = []
        for state in frontier:
            for opname, (fn, ctxs) in T.items():
                for c in ctxs:
                    nxt = fn(state, c)
                    if nxt not in seen:
                        seen.add(nxt)
                        nxt_frontier.append(nxt)
                        if len(seen) > max_states:
                            raise RuntimeError(f"closure exceeded {max_states} states -- alphabet too large for this bounded experiment")
        frontier = nxt_frontier
    return list(seen)


# ============================================================ observation set (CONSTRUCTION, disclosed)
# Deliberately excludes `tau`, `Pi`, and raw `id` -- these are candidates
# for behaviorally-IRRELEVANT components under this regime, tested below,
# not assumed.
def obs_P_values(k: K):
    return tuple(sorted(a.P for a in k.A if not a.retired))

def obs_Sigma_values(k: K):
    return tuple(sorted(a.Sigma for a in k.A if not a.retired))

def obs_evidence_sizes(k: K):
    return tuple(sorted(len(a.E) for a in k.A if not a.retired))

def obs_active_count(k: K):
    return sum(1 for a in k.A if not a.retired)

O = {
    "P_values": obs_P_values,
    "Sigma_values": obs_Sigma_values,
    "evidence_sizes": obs_evidence_sizes,
    "active_count": obs_active_count,
}


def main():
    E = bfs_closure(SEED, T)
    regime = Regime(E, T=T, O=O, H=None, name="ESS_bounded_C1")

    # ---------- partition minimality ----------
    pm = partition_minimality(regime)

    # ---------- component minimality ----------
    # Candidate components extracted from the (single) active assertion.
    # Using a fixed projection helper: pi_S(k) = tuple of the named fields
    # of the active assertion, restricted to S.
    FIELDS = ["P", "Sigma", "E", "tau", "Pi"]

    def extract(k: K, field: str):
        a = pick_active(k)
        if a is None:
            return None
        return getattr(a, field)

    cm = component_minimality(E, FIELDS, extract, regime)

    # ---------- representation minimality ----------
    def F_full(k):
        a = pick_active(k)
        return None if a is None else (a.P, a.Sigma, a.E, a.tau, a.Pi)

    def F_no_tau_pi(k):
        a = pick_active(k)
        return None if a is None else (a.P, a.Sigma, a.E)

    def F_P_Sigma_only(k):
        a = pick_active(k)
        return None if a is None else (a.P, a.Sigma)

    def F_P_only(k):
        a = pick_active(k)
        return None if a is None else a.P

    candidates = {
        "F_full": F_full, "F_no_tau_pi": F_no_tau_pi,
        "F_P_Sigma_only": F_P_Sigma_only, "F_P_only": F_P_only,
    }
    cost = {"F_full": 5, "F_no_tau_pi": 3, "F_P_Sigma_only": 2, "F_P_only": 1}
    rm = representation_minimality(candidates, regime, lambda name: cost[name])

    out = {
        "n_states_in_closure": len(E),
        "partition_minimality": pm,
        "component_minimality": {
            "minimal_S": cm["minimal_S"], "size": cm["size"],
            "n_subsets_tested": len(cm["all_tested"]),
            "all_tested_summary": [{"S": t["S"], "sufficient": t["sufficient"]} for t in cm["all_tested"]],
        },
        "representation_minimality": {
            "best": rm["best"]["name"] if rm["best"] else None,
            "all_candidates": [{"name": c["name"], "sufficient": c["sufficient"], "cost": c["cost"]}
                                for c in rm["all_candidates"]],
        },
    }
    print(json.dumps(out, indent=2, default=str))
    with open("ksme17_minimality_results.json", "w") as f:
        json.dump(out, f, indent=2, default=str)


if __name__ == "__main__":
    main()
