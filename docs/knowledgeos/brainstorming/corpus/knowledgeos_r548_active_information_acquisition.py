from dataclasses import dataclass
from math import log2
import json

@dataclass(frozen=True)
class World:
    id: str
    dependency: bool

@dataclass(frozen=True)
class Action:
    id: str
    name: str
    observations: dict
    cost: float

WORLDS = [World("D1", True), World("D2", False)]

# Deliberately indistinguishable with currently available information.
CURRENT_OBSERVATION = {"D1": "same", "D2": "same"}

ACTIONS = [
    Action("A1", "inspect_build_manifest",
           {"D1": "manifest_shared_build", "D2": "manifest_independent"}, 1.0),
    Action("A2", "inspect_source_document",
           {"D1": "same_source_text", "D2": "same_source_text"}, 1.0),
    Action("A3", "inspect_transformation_log",
           {"D1": "transform_T7", "D2": "transform_T7"}, 1.0),
    Action("A4", "inspect_model_lineage",
           {"D1": "model_M9", "D2": "model_M9"}, 1.0),
    Action("A5", "domain_expert",
           {"D1": "dependent", "D2": "independent"}, 3.0),
    Action("A6", "timestamped_provenance",
           {"D1": "P_same", "D2": "P_same"}, 0.5),
    Action("A7", "do_nothing",
           {"D1": "none", "D2": "none"}, 0.0),
]

def partition(worlds, observations):
    groups = {}
    for w in worlds:
        groups.setdefault(observations[w.id], []).append(w.id)
    return groups

def identifiable(worlds, observations):
    """Each observable cell must contain only one dependency truth value."""
    for members in partition(worlds, observations).values():
        labels = {
            next(w.dependency for w in worlds if w.id == wid)
            for wid in members
        }
        if len(labels) > 1:
            return False
    return True

def entropy(p):
    if p <= 0 or p >= 1:
        return 0.0
    return -(p * log2(p) + (1-p) * log2(1-p))

PRIOR = {"D1": 0.5, "D2": 0.5}

def expected_information_gain(action):
    """Shannon information gain under the explicitly declared equal prior."""
    before = entropy(PRIOR["D1"])
    after = 0.0
    for members in partition(WORLDS, action.observations).values():
        mass = sum(PRIOR[m] for m in members)
        p_dep = sum(
            PRIOR[m] for m in members
            if next(w.dependency for w in WORLDS if w.id == m)
        ) / mass
        after += mass * entropy(p_dep)
    return before - after

def determination_gain(action):
    before = identifiable(WORLDS, CURRENT_OBSERVATION)
    combined = {
        w.id: (CURRENT_OBSERVATION[w.id], action.observations[w.id])
        for w in WORLDS
    }
    after = identifiable(WORLDS, combined)
    return int(after) - int(before)

def declared_acquisition_utility(action):
    # Utility is deliberately contract-relative:
    # determination gain minus acquisition cost.
    return determination_gain(action) - action.cost

def evaluate():
    assert not identifiable(WORLDS, CURRENT_OBSERVATION)

    a1 = ACTIONS[0]
    assert identifiable(
        WORLDS,
        {w.id: (CURRENT_OBSERVATION[w.id], a1.observations[w.id]) for w in WORLDS}
    )
    assert expected_information_gain(a1) == 1.0
    assert determination_gain(a1) == 1

    a2 = ACTIONS[1]
    assert not identifiable(
        WORLDS,
        {w.id: (CURRENT_OBSERVATION[w.id], a2.observations[w.id]) for w in WORLDS}
    )
    assert expected_information_gain(a2) == 0.0
    assert determination_gain(a2) == 0

    a7 = ACTIONS[-1]
    assert expected_information_gain(a7) == 0.0
    assert determination_gain(a7) == 0

    a5 = ACTIONS[4]
    assert determination_gain(a5) == 1
    assert declared_acquisition_utility(a5) < declared_acquisition_utility(a1)

    return [
        {
            "action": a.id,
            "name": a.name,
            "information_gain_bits": round(expected_information_gain(a), 6),
            "determination_gain": determination_gain(a),
            "identifiable_after": identifiable(
                WORLDS,
                {w.id: (CURRENT_OBSERVATION[w.id], a.observations[w.id]) for w in WORLDS}
            ),
            "cost": a.cost,
            "declared_acquisition_utility": round(declared_acquisition_utility(a), 6),
        }
        for a in ACTIONS
    ]

if __name__ == "__main__":
    rows = evaluate()
    print("R548 active-information tests: 7/7 passed")
    for r in rows:
        print(r)

    Path = __import__("pathlib").Path
    Path("knowledgeos_r548_results.json").write_text(
        json.dumps(rows, indent=2), encoding="utf-8"
    )
