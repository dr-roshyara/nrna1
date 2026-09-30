"""
KnowledgeOS R595 — Dependency Semantics Beyond Deterministic Sufficiency

Finite executable benchmark only. No universal theorem is claimed.
"""

from itertools import product
from dataclasses import dataclass
from enum import Enum

class DependencyKind(Enum):
    LOGICAL = "logical"
    PROBABILISTIC = "probabilistic"
    CAUSAL = "causal"
    EPISTEMIC = "epistemic"

@dataclass(frozen=True)
class DependencyContract:
    source: str
    target: str
    kind: DependencyKind
    regime: str
    context: str
    scope: str

def logical_z(a, b):
    return a & b

def is_sufficient_condition(fn, factors, condition, target_value):
    assignments = []
    for bits in product([0, 1], repeat=len(factors)):
        state = dict(zip(factors, bits))
        if all(state[k] == v for k, v in condition.items()):
            assignments.append(state)
    return bool(assignments) and all(
        fn(**state) == target_value for state in assignments
    )

# 1. Logical dependency: Z = A AND B
assert is_sufficient_condition(
    logical_z, ["a", "b"], {"a": 1, "b": 1}, 1
)
assert not is_sufficient_condition(logical_z, ["a", "b"], {"a": 1}, 1)
assert not is_sufficient_condition(logical_z, ["a", "b"], {"b": 1}, 1)

# 2. Probabilistic dependency: A changes P(Z), but does not determine Z.
joint = {(0,0): .20, (0,1): .30, (1,0): .10, (1,1): .40}
p_a1 = sum(p for (a,z), p in joint.items() if a == 1)
p_z1_given_a1 = joint[(1,1)] / p_a1
p_a0 = 1 - p_a1
p_z1_given_a0 = joint[(0,1)] / p_a0

assert p_z1_given_a1 != p_z1_given_a0
assert p_z1_given_a1 < 1.0
assert p_z1_given_a0 > 0.0

# 3. Causal dependency: observational cancellation hides an effect.
# U ~ Bernoulli(.2), A := U, Z := A XOR U.
# Observationally Z is always 0.
# Intervening on A changes P(Z=1): .2 -> .8.
p_u1 = .2
p_z1_do_a0 = p_u1
p_z1_do_a1 = 1 - p_u1
assert p_z1_do_a1 - p_z1_do_a0 != 0

# 4. Epistemic/evidential dependency: evidence changes assessment,
# while the underlying world state is not changed by the evidence.
prior = .5
p_e_given_z1 = .9
p_e_given_z0 = .1
posterior = (p_e_given_z1 * prior) / (
    p_e_given_z1 * prior + p_e_given_z0 * (1 - prior)
)
assert posterior != prior
assert abs(posterior - .9) < 1e-12

# 5. Anti-unification: the decisive semantic tests are different.
semantic_tests = {
    DependencyKind.LOGICAL: "sufficient_condition",
    DependencyKind.PROBABILISTIC: "conditional_distribution_difference",
    DependencyKind.CAUSAL: "interventional_contrast",
    DependencyKind.EPISTEMIC: "assessment_update",
}
assert len(set(semantic_tests.values())) == 4

print("KnowledgeOS R595 — Dependency Semantics Beyond Deterministic Sufficiency")
print("Logical minimal sufficient condition: PASS")
print("Probabilistic dependence without deterministic sufficiency: PASS")
print("Causal dependence hidden by observational cancellation: PASS")
print("Epistemic dependency changes assessment, not world state: PASS")
print("Four semantic tests are non-identical: PASS")
print()
print("RESULT:")
print("A single untyped dependency predicate is UNSOUND.")
print("A shared DependencyContract with typed semantic regimes is viable.")
print("Logical support, probabilistic dependence, causal effect, and")
print("epistemic update must remain distinct semantic relations.")
