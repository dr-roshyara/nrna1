#!/usr/bin/env python3
"""
KSME-17 -- Behavioral Materiality Experiment.

For each of the three construction gaps (G1 x2, G3), build the candidate
K-states from a shared starting point, then use BSE to determine whether
the candidates are behaviorally MATERIAL (distinguishable) or IMMATERIAL
(equivalent) under a disclosed observation set O and a disclosed
"downstream" operation set T (used to continue traces after the ambiguous
step, since materiality is about whether the CHOICE has any future
observable consequence, not just an immediate one).

Every result is CONSTRUCTION-tier unless stated otherwise: this experiment
tests the behavior of ESS's own disclosed candidates, not a historical fact.
"""
import json
import sys
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/.claude/scripts/knowledgeos-ksme")
from bse import Regime, RegimeManifest
from ess import (
    K, Assertion, assertion_created, evidence_added_C1, evidence_added_C2,
    value_revised, conflict_resolved_C1, conflict_resolved_C2,
    rollback_C1_no_marker, rollback_C2_with_marker,
)

# ============================================================ shared observation set (disclosed, CONSTRUCTION)
def obs_assertion_values(k: K):
    return tuple(sorted((a.id, a.P, a.Sigma, a.retired) for a in k.A))

def obs_relations(k: K):
    return tuple(sorted(k.R))

def obs_active_count(k: K):
    return sum(1 for a in k.A if not a.retired)

def obs_epistemic_strength(k: K):
    return tuple(sorted((a.id, len(a.E)) for a in k.A))

def obs_rollback_marker(k: K):
    return k.rollback_marker

O_BASE = {
    "assertion_values": obs_assertion_values,
    "relations": obs_relations,
    "active_count": obs_active_count,
    "epistemic_strength": obs_epistemic_strength,
}
O_WITH_MARKER = dict(O_BASE, rollback_marker=obs_rollback_marker)

# ============================================================ shared downstream operations (disclosed, CONSTRUCTION)
def op_add_more_evidence(k: K, ctx):
    aid, ev = ctx
    return evidence_added_C1(k, aid, ev)  # fixed choice for continuation, disclosed

def op_revise(k: K, ctx):
    aid, v, tau = ctx
    return value_revised(k, aid, v, tau)

def op_noop(k: K, ctx):
    return k


def make_regime(O, extra_T=None, H=3):
    T = {
        "add_more_evidence": (op_add_more_evidence, [(1001, "e2"), (1002, "e2")]),
        "revise": (op_revise, [(1001, "v2", 99), (1002, "v2", 99)]),
        "noop": (op_noop, [None]),
    }
    if extra_T:
        T.update(extra_T)
    # E is populated per-experiment with the actual candidate states, not a
    # fixed universe -- Regime is built fresh per test below.
    return T


def run_pair(name, k1, k2, O, T, manifest_note):
    T_dict = make_regime(O)
    E = [k1, k2]
    regime = Regime(E, T=T_dict, O=O, H=3, name=name)
    manifest = RegimeManifest(
        regime_id=name, source_track="TRACK-A-CONSTRUCTION", semantic_status="MIXED",
        state_sources={"A": "CONSTRUCTION (ess.py Assertion)", "R": "CONSTRUCTION (ess.py K.R)"},
        operation_sources={k: "CONSTRUCTION (ess.py)" for k in T_dict},
        observation_sources={k: "CONSTRUCTION (ksme17_materiality.py)" for k in O},
        horizon=3, finite_state=True, determinism="deterministic",
    )
    ok, problems = manifest.validate(regime)
    equivalent, cert = regime.behaviorally_equivalent(k1, k2, max_len=3)
    return {
        "experiment": name,
        "note": manifest_note,
        "manifest_valid": ok,
        "manifest_problems": problems,
        "behaviorally_material": not equivalent,
        "counterexample": cert.to_dict() if cert else None,
    }


def main():
    results = []

    # ---------- G1a: EvidenceAdded C1 (no epistemic update) vs C2 (deterministic update)
    base = assertion_created(K(), P="Nexus=3.70", Sigma="Weak", E=frozenset({"e0"}), tau=1, Pi="src1")
    aid = next(iter(base.A)).id
    k_c1 = evidence_added_C1(base, aid, "e1")
    k_c2 = evidence_added_C2(base, aid, "e1")
    results.append(run_pair(
        "G1a_EvidenceAdded_C1_vs_C2", k_c1, k_c2, O_BASE, None,
        "Does deterministically resolving 'may be updated' as ALWAYS-update vs NEVER-update "
        "produce a behaviorally observable difference under downstream evidence-adding/revision ops?"
    ))

    # ---------- G1b: ConflictResolved C1 (record only) vs C2 (evidence-based retraction)
    a1 = assertion_created(K(), P="X", Sigma="Weak", E=frozenset({"e0"}), tau=1, Pi="s1")
    a1_id = next(iter(a1.A)).id
    base2 = assertion_created(a1, P="notX", Sigma="Weak", E=frozenset({"e0", "e1"}), tau=1, Pi="s2")
    a2_id = [a.id for a in base2.A if a.id != a1_id][0]
    k_c1b = conflict_resolved_C1(base2, a1_id, a2_id, "evidence-based")
    k_c2b = conflict_resolved_C2(base2, a1_id, a2_id, "evidence-based")
    results.append(run_pair(
        "G1b_ConflictResolved_C1_vs_C2", k_c1b, k_c2b, O_BASE, None,
        "Does 'record only' vs 'evidence-based strategy retracts the loser' produce an "
        "observable difference?"
    ))

    # ---------- G3: Rollback C1 (no marker) vs C2 (explicit provenance marker)
    target = assertion_created(K(), P="Y", Sigma="Strong", E=frozenset({"e0"}), tau=1, Pi="s3")
    k_g3_c1 = rollback_C1_no_marker(target, "user-requested")
    k_g3_c2 = rollback_C2_with_marker(target, "user-requested")
    # Test under BOTH observation sets: one that can see the marker, one that can't.
    results.append(run_pair(
        "G3_Rollback_C1_vs_C2_under_O_BASE (marker-blind observations)",
        k_g3_c1, k_g3_c2, O_BASE, None,
        "Under observations that never inspect rollback_marker: is the source's 'different "
        "provenance' claim behaviorally witnessed at all?"
    ))
    results.append(run_pair(
        "G3_Rollback_C1_vs_C2_under_O_WITH_MARKER",
        k_g3_c1, k_g3_c2, O_WITH_MARKER, None,
        "Under observations that DO inspect rollback_marker: does the constructed marker "
        "make the distinction observable?"
    ))

    print(json.dumps(results, indent=2, default=str))
    with open("ksme17_materiality_results.json", "w") as f:
        json.dump(results, f, indent=2, default=str)


if __name__ == "__main__":
    main()
