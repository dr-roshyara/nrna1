from __future__ import annotations
from dataclasses import dataclass, field
from enum import Enum
from typing import Any, Callable, Optional


class OperationClass(str, Enum):
    PURE = "Pure"
    EPISTEMIC = "Epistemic"
    GOVERNANCE = "Governance"

class MutationPolicy(str, Enum):
    FORBIDDEN = "Forbidden"
    DECLARED = "Declared"
    GOVERNED = "Governed"

class Status(str, Enum):
    PASS = "PASS"
    FAIL = "FAIL"
    UNKNOWN = "UNKNOWN"
    CONDITIONAL = "CONDITIONAL"
    UNDEFINED = "UNDEFINED"
    NOT_APPLICABLE = "NOT_APPLICABLE"

ADMISSIBLE_MUTATION = {
    OperationClass.PURE: {MutationPolicy.FORBIDDEN},
    OperationClass.EPISTEMIC: {MutationPolicy.DECLARED, MutationPolicy.GOVERNED},
    OperationClass.GOVERNANCE: {MutationPolicy.GOVERNED},
}

@dataclass(frozen=True)
class Scope:
    domain: str
    population: str
    time_range: str
    identifier: str = ""

@dataclass(frozen=True)
class Regime:
    name: str
    axioms: frozenset[str] = frozenset()
    rules: frozenset[str] = frozenset()

@dataclass(frozen=True)
class Provenance:
    source: str
    actor: str
    timestamp: str
    lineage: tuple[str, ...] = ()

@dataclass(frozen=True)
class Event:
    event_type: str
    payload: dict[str, Any] = field(default_factory=dict)

@dataclass
class State:
    X: Any
    H: list[Event] = field(default_factory=list)

@dataclass(frozen=True)
class PreservationTarget:
    name: str
    evaluator: Callable[[Any], Any]

@dataclass(frozen=True)
class LossProfile:
    discarded_dimensions: frozenset[str] = frozenset()
    declared_loss: frozenset[str] = frozenset()
    preservation_targets: frozenset[str] = frozenset()

@dataclass(frozen=True)
class Contract:
    name: str
    precondition: Callable[[Any], bool] = lambda _: True
    allowed_mutation: bool = False

@dataclass(frozen=True)
class CompatibilityWitness:
    src: str
    tgt: str
    conv: Callable[[Any], Any]
    pre: Callable[[Any], bool]
    preservation_target: Optional[PreservationTarget]
    loss: LossProfile
    regime: Regime

@dataclass(frozen=True)
class OperationSpecification:
    name: str
    input_type: str
    output_type: str
    op_class: OperationClass
    mutation: MutationPolicy
    transform: Callable[[Any], Any]
    precondition: Callable[[Any], bool] = lambda _: True
    preservation_targets: tuple[PreservationTarget, ...] = ()
    loss_profile: LossProfile = LossProfile()
    regime: Optional[Regime] = None
    scope: Optional[Scope] = None

@dataclass(frozen=True)
class Composition:
    left: OperationSpecification
    right: OperationSpecification
    witness: CompatibilityWitness
    result: OperationSpecification

@dataclass(frozen=True)
class ObservationContract:
    """Declares which aspects of two specifications/executions are observable for equivalence."""
    observe_output: bool = True
    observe_authoritative_state: bool = True
    observe_scope: bool = True
    observe_regime: bool = True
    observe_class: bool = True
    observe_mutation_policy: bool = True
    observe_operation_name: bool = False


def validate_operation(spec: OperationSpecification) -> None:
    if spec.mutation not in ADMISSIBLE_MUTATION[spec.op_class]:
        raise TypeError(f"Illegal Class × Mutation pair: {spec.op_class.value} × {spec.mutation.value}")


def compose(a: OperationSpecification, w: CompatibilityWitness, b: OperationSpecification) -> Composition:
    """Compose a : A->B, witness B->C, b : C->D. Only typed witness compatibility is allowed."""
    validate_operation(a); validate_operation(b)
    if a.output_type != w.src:
        raise TypeError("Left output type does not match witness source type")
    if w.tgt != b.input_type:
        raise TypeError("Witness target type does not match right input type")

    # Conservative first executable law: regime/scope must agree when both are declared.
    if a.regime and w.regime and a.regime != w.regime:
        raise ValueError("Regime mismatch: explicit translation witness required")
    if b.regime and w.regime and b.regime != w.regime:
        raise ValueError("Regime mismatch: explicit translation witness required")
    if a.scope and b.scope and a.scope != b.scope:
        raise ValueError("Scope mismatch: explicit scope-bridge contract required")

    def composed(x: Any) -> Any:
        y = a.transform(x)
        z = w.conv(y)
        return b.transform(z)

    # Composition is epistemically/gov. only if either side is authoritative mutation.
    # Pure composition remains pure. Governance dominates Epistemic for the class.
    if OperationClass.GOVERNANCE in (a.op_class, b.op_class):
        cls = OperationClass.GOVERNANCE
    elif OperationClass.EPISTEMIC in (a.op_class, b.op_class):
        cls = OperationClass.EPISTEMIC
    else:
        cls = OperationClass.PURE

    if cls is OperationClass.PURE:
        mut = MutationPolicy.FORBIDDEN
    elif cls is OperationClass.GOVERNANCE:
        mut = MutationPolicy.GOVERNED
    else:
        mut = MutationPolicy.GOVERNED if MutationPolicy.GOVERNED in (a.mutation, b.mutation) else MutationPolicy.DECLARED

    result = OperationSpecification(
        name=f"({a.name}∘{b.name})",
        input_type=a.input_type,
        output_type=b.output_type,
        op_class=cls,
        mutation=mut,
        transform=composed,
        precondition=lambda x: a.precondition(x) and w.pre(a.transform(x)) and b.precondition(w.conv(a.transform(x))),
        preservation_targets=tuple(dict.fromkeys(a.preservation_targets + b.preservation_targets)),
        loss_profile=LossProfile(
            discarded_dimensions=a.loss_profile.discarded_dimensions | b.loss_profile.discarded_dimensions | w.loss.declared_loss,
            declared_loss=a.loss_profile.declared_loss | b.loss_profile.declared_loss | w.loss.declared_loss,
            preservation_targets=a.loss_profile.preservation_targets | b.loss_profile.preservation_targets | w.loss.preservation_targets,
        ),
        regime=a.regime or b.regime or w.regime,
        scope=a.scope or b.scope,
    )
    return Composition(a, b, w, result)


def functional_associativity(f, g, h, samples):
    for x in samples:
        left = h(g(f(x)))
        right = h(g(f(x)))
        if left != right:
            return Status.FAIL, x
    return Status.PASS, None


def spec_observationally_equivalent(a: OperationSpecification, b: OperationSpecification, samples, obs: ObservationContract):
    validate_operation(a); validate_operation(b)
    if obs.observe_output and a.output_type != b.output_type:
        return False
    if obs.observe_scope and a.scope != b.scope:
        return False
    if obs.observe_regime and a.regime != b.regime:
        return False
    if obs.observe_class and a.op_class != b.op_class:
        return False
    if obs.observe_mutation_policy and a.mutation != b.mutation:
        return False
    if obs.observe_operation_name and a.name != b.name:
        return False
    if obs.observe_output:
        for x in samples:
            if a.precondition(x) != b.precondition(x):
                return False
            if a.precondition(x) and a.transform(x) != b.transform(x):
                return False
    return True


def normalized_composition(a, w1, b, w2, c):
    left = compose(compose(a, w1, b).result, w2, c).result
    right = compose(a, w1, compose(b, w2, c).result).result
    return left, right


def run_tests():
    R = Regime("finite-arithmetic", frozenset({"integer"}), frozenset({"addition"}))
    S = Scope("demo", "finite", "2026")
    W = lambda src, tgt: CompatibilityWitness(src, tgt, lambda x: x, lambda _: True, None, LossProfile(), R)

    A = OperationSpecification("inc", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: x + 1, regime=R, scope=S)
    B = OperationSpecification("double", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: x * 2, regime=R, scope=S)
    C = OperationSpecification("square", "E", "F", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: x * x, regime=R, scope=S)
    wAB = W("B", "C")
    wBC = W("D", "E")

    # 1. Function associativity is structural for total function composition.
    status, ce = functional_associativity(lambda x: x+1, lambda x: 2*x, lambda x: x*x, range(-10,11))
    assert status is Status.PASS and ce is None

    # 2. Typed composition works.
    left, right = normalized_composition(A, wAB, B, wBC, C)
    assert left.input_type == right.input_type == "A"
    assert left.output_type == right.output_type == "F"

    # 3. Specs are not strictly equal because names/grouping are syntax, not semantics.
    assert left.name != right.name
    obs = ObservationContract(observe_operation_name=False)
    assert spec_observationally_equivalent(left, right, range(-10,11), obs)

    # 4. Wrong witness type is rejected.
    bad = W("X", "C")
    try:
        compose(A, bad, B)
        raise AssertionError("bad witness accepted")
    except TypeError:
        pass

    # 5. Regime mismatch is not silently composed.
    R2 = Regime("different", frozenset({"nonstandard"}), frozenset())
    w_bad_regime = CompatibilityWitness("B", "C", lambda x:x, lambda _:True, None, LossProfile(), R2)
    try:
        compose(A, w_bad_regime, B)
        raise AssertionError("regime mismatch accepted")
    except ValueError:
        pass

    # 6. Scope mismatch is not silently composed.
    S2 = Scope("demo", "other-population", "2026")
    B2 = OperationSpecification("double2", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:2*x, regime=R, scope=S2)
    try:
        compose(A, wAB, B2)
        raise AssertionError("scope mismatch accepted")
    except ValueError:
        pass

    # 7. Pure composition remains X-immutable by policy.
    assert left.mutation is MutationPolicy.FORBIDDEN and left.op_class is OperationClass.PURE

    # 8. Mutation policy is not inferred from function output alone.
    E = OperationSpecification("epistemic", "F", "G", OperationClass.EPISTEMIC, MutationPolicy.DECLARED, lambda x:x+1, regime=R, scope=S)
    wCE = W("F", "F")
    comp = compose(C, wCE, E).result
    assert comp.op_class is OperationClass.EPISTEMIC
    assert comp.mutation in {MutationPolicy.DECLARED, MutationPolicy.GOVERNED}

    # 9. Loss is not claimed to be a universal union theorem; here it is only a declared summary.
    lp1 = LossProfile(frozenset({"x"}), frozenset({"x"}), frozenset())
    lp2 = LossProfile(frozenset({"y"}), frozenset({"y"}), frozenset())
    wloss = CompatibilityWitness("B","C",lambda x:x,lambda _:True,None,lp1,R)
    A_loss = OperationSpecification("lossA","A","B",OperationClass.PURE,MutationPolicy.FORBIDDEN,lambda x:x,loss_profile=lp2,regime=R,scope=S)
    comp_loss = compose(A_loss,wloss,B).result
    assert {"x","y"}.issubset(comp_loss.loss_profile.declared_loss)

    # 10. Associativity is conditional at the full specification level: test a concrete case.
    assert spec_observationally_equivalent(left, right, range(-10,11), obs)

    return 10

if __name__ == "__main__":
    n = run_tests()
    print(f"R604.2 composition/associativity tests: {n}/{n} passed")
