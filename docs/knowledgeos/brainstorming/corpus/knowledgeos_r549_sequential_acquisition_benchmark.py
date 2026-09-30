from dataclasses import dataclass
from functools import lru_cache
from itertools import product

@dataclass(frozen=True)
class Action:
    id: str
    cost: float
    observations: tuple

WORLDS = tuple(product((0, 1), (0, 1)))  # hidden factors X,Y
TARGET = tuple(x ^ y for x, y in WORLDS)  # dependency target Z = X XOR Y

ACTIONS = {
    "A": Action("A", 0.20, tuple(x for x, y in WORLDS)),
    "B": Action("B", 0.20, tuple(y for x, y in WORLDS)),
    "C": Action("C", 0.45, TARGET),                    # direct target observation
    "D": Action("D", 0.05, tuple(0 for _ in WORLDS)), # irrelevant negative control
}
PRIOR = tuple(0.25 for _ in WORLDS)

def bayes_error(indices):
    if not indices:
        return 0.5
    p0 = sum(PRIOR[i] for i in indices if TARGET[i] == 0)
    p1 = sum(PRIOR[i] for i in indices if TARGET[i] == 1)
    mass = sum(PRIOR[i] for i in indices)
    return min(p0, p1) / mass

def partitions(indices, action):
    groups = {}
    for i in indices:
        groups.setdefault(action.observations[i], []).append(i)
    return tuple(tuple(g) for g in groups.values())

def expected_post_error(indices, action):
    mass = sum(PRIOR[i] for i in indices)
    return sum(
        (sum(PRIOR[i] for i in g) / mass) * bayes_error(g)
        for g in partitions(indices, action)
    )

def immediate_information_value(indices, action):
    return bayes_error(indices) - expected_post_error(indices, action)

@lru_cache(None)
def optimal_cost(indices, remaining):
    indices = tuple(indices)
    remaining = tuple(remaining)
    best = bayes_error(indices)
    mass = sum(PRIOR[i] for i in indices)
    for aid in remaining:
        action = ACTIONS[aid]
        value = action.cost
        for group in partitions(indices, action):
            gm = sum(PRIOR[i] for i in group)
            value += (gm / mass) * optimal_cost(
                tuple(group), tuple(x for x in remaining if x != aid)
            )
        best = min(best, value)
    return best

def greedy_first_action(indices):
    return max(
        (
            immediate_information_value(indices, a) - a.cost,
            aid
        )
        for aid, a in ACTIONS.items()
    )

def optimal_first_action(indices, remaining):
    best = (bayes_error(indices), None)
    mass = sum(PRIOR[i] for i in indices)
    for aid in remaining:
        action = ACTIONS[aid]
        value = action.cost
        for group in partitions(indices, action):
            gm = sum(PRIOR[i] for i in group)
            value += (gm / mass) * optimal_cost(
                tuple(group), tuple(x for x in remaining if x != aid)
            )
        if value < best[0]:
            best = (value, aid)
    return best

def run_tests():
    initial = tuple(range(len(WORLDS)))

    # Initially the target is unresolved.
    assert bayes_error(initial) == 0.5

    # A and B are individually uninformative about XOR.
    assert immediate_information_value(initial, ACTIONS["A"]) == 0.0
    assert immediate_information_value(initial, ACTIONS["B"]) == 0.0

    # But C directly resolves the target.
    assert immediate_information_value(initial, ACTIONS["C"]) == 0.5

    greedy_net, greedy_action = greedy_first_action(initial)
    assert greedy_action == "C"

    optimal_value, optimal_action = optimal_first_action(
        initial, tuple(sorted(ACTIONS))
    )
    assert optimal_action in {"A", "B"}
    assert round(optimal_value, 10) == 0.4

    # Greedy chooses C: cost 0.45, zero terminal error.
    greedy_objective = 0.45

    # Optimal chooses A+B (or B+A): cost 0.40, zero terminal error.
    assert greedy_objective > optimal_value

    print("R549 sequential acquisition benchmark: 6/6 core assertions passed")
    print("Greedy:", greedy_action, "objective =", greedy_objective)
    print("Optimal first action:", optimal_action,
          "objective =", optimal_value)
    print("Conclusion: one-step greedy acquisition can be suboptimal "
          "when information has complementarity/synergy.")

if __name__ == "__main__":
    run_tests()
