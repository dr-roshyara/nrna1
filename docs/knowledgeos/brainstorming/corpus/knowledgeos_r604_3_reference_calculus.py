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

    def compose(self, other: "Provenance") -> "Provenance":
        # Provenance composition is lineage construction, not truth validation.
        return Provenance(
            source=f"{self.source}|{other.source}",
            actor=f"{self.actor}|{other.actor}",
            timestamp=max(self.timestamp, other.timestamp),
            lineage=self.lineage + other.lineage,
        )

@dataclass(frozen=True)
class LossProfile:
    discarded_dimensions: frozenset[str] = frozenset()
    declared_loss: frozenset[str] = frozenset()
    preservation_targets: frozenset[str] = frozenset()

@dataclass(frozen=True)
class PreservationTarget:
    name: str
    evaluator: Callable[[Any], Any]

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
    provenance: Optional[Provenance] = None

@dataclass(frozen=True)
class ObservationContract:
    observe_output: bool = True
    observe_scope: bool = True
    observe_regime: bool = True
    observe_class: bool = True
    observe_mutation_policy: bool = True
    observe_operation_name: bool = False


def validate_operation(spec: OperationSpecification) -> None:
    if spec.mutation not in ADMISSIBLE_MUTATION[spec.op_class]:
        raise TypeError(f"Illegal Class × Mutation pair: {spec.op_class.value} × {spec.mutation.value}")


def compose(a: OperationSpecification, w: CompatibilityWitness, b: OperationSpecification) -> OperationSpecification:
    validate_operation(a); validate_operation(b)
    if a.output_type != w.src:
        raise TypeError("Left output type does not match witness source type")
    if w.tgt != b.input_type:
        raise TypeError("Witness target type does not match right input type")
    if a.regime and w.regime and a.regime != w.regime:
        raise ValueError("Regime mismatch: explicit translation witness required")
    if b.regime and w.regime and b.regime != w.regime:
        raise ValueError("Regime mismatch: explicit translation witness required")
    if a.scope and b.scope and a.scope != b.scope:
        raise ValueError("Scope mismatch: explicit scope-bridge contract required")

    def composed(x: Any) -> Any:
        return b.transform(w.conv(a.transform(x)))

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

    provenance = None
    if a.provenance and b.provenance:
        provenance = a.provenance.compose(b.provenance)
    elif a.provenance or b.provenance:
        provenance = a.provenance or b.provenance

    return OperationSpecification(
        name=f"({b.name}∘{a.name})",
        input_type=a.input_type,
        output_type=b.output_type,
        op_class=cls,
        mutation=mut,
        transform=composed,
        precondition=lambda x: a.precondition(x) and w.pre(a.transform(x)) and b.precondition(w.conv(a.transform(x))),
        preservation_targets=tuple(dict.fromkeys(a.preservation_targets + b.preservation_targets)),
        loss_profile=LossProfile(
            discarded_dimensions=a.loss_profile.discarded_dimensions | b.loss_profile.discarded_dimensions | w.loss.discarded_dimensions,
            declared_loss=a.loss_profile.declared_loss | b.loss_profile.declared_loss | w.loss.declared_loss,
            preservation_targets=a.loss_profile.preservation_targets | b.loss_profile.preservation_targets | w.loss.preservation_targets,
        ),
        regime=a.regime or b.regime or w.regime,
        scope=a.scope or b.scope,
        provenance=provenance,
    )


def equivalent(a: OperationSpecification, b: OperationSpecification, samples, obs=ObservationContract()) -> bool:
    validate_operation(a); validate_operation(b)
    if obs.observe_output and a.output_type != b.output_type: return False
    if obs.observe_scope and a.scope != b.scope: return False
    if obs.observe_regime and a.regime != b.regime: return False
    if obs.observe_class and a.op_class != b.op_class: return False
    if obs.observe_mutation_policy and a.mutation != b.mutation: return False
    if obs.observe_operation_name and a.name != b.name: return False
    for x in samples:
        if a.precondition(x) != b.precondition(x): return False
        if a.precondition(x) and a.transform(x) != b.transform(x): return False
    return True


def witness(src, tgt, regime):
    return CompatibilityWitness(src, tgt, lambda x: x, lambda _: True, None, LossProfile(), regime)


def make(name, inp, out, cls, mut, f, regime, scope, provenance=None):
    s = OperationSpecification(name, inp, out, cls, mut, f, regime=regime, scope=scope, provenance=provenance)
    validate_operation(s)
    return s


def run_tests():
    R = Regime("finite-arithmetic", frozenset({"integer"}), frozenset({"addition", "multiplication"}))
    S = Scope("demo", "finite", "2026")
    P = Provenance("source", "tester", "2026-09-19T10:00:00", ("root",))
    Q = Provenance("source2", "tester2", "2026-09-19T11:00:00", ("step2",))
    W = lambda src, tgt: witness(src, tgt, R)

    # --- 1. Corrected associativity test: left and right are independently constructed.
    A = make("inc", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: x + 1, R, S, P)
    B = make("double", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: 2*x, R, S, Q)
    C = make("square", "E", "F", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x: x*x, R, S)
    left = compose(compose(A, W("B","C"), B), W("D","E"), C)
    right = compose(A, W("B","C"), compose(B, W("D","E"), C))
    assert equivalent(left, right, range(-20, 21))

    # --- 2. Non-commutativity is real and must not be treated as an error.
    add1 = lambda x: x + 1
    times2 = lambda x: 2*x
    assert any(times2(add1(x)) != add1(times2(x)) for x in range(-5, 6))

    # --- 3. Exhaustive Class x Mutation admissibility matrix.
    for cls in OperationClass:
        for mut in MutationPolicy:
            spec = OperationSpecification("m", "A", "B", cls, mut, lambda x:x)
            allowed = mut in ADMISSIBLE_MUTATION[cls]
            try:
                validate_operation(spec)
                actual = True
            except TypeError:
                actual = False
            assert actual == allowed

    # --- 4. Composition class policy currently used by the reference calculus.
    pure = make("p", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x, R, S)
    epi = make("e", "C", "D", OperationClass.EPISTEMIC, MutationPolicy.DECLARED, lambda x:x+1, R, S)
    gov = make("g", "E", "F", OperationClass.GOVERNANCE, MutationPolicy.GOVERNED, lambda x:x+2, R, S)
    for first, second, expected in [
        (pure, pure, OperationClass.PURE),
        (pure, epi, OperationClass.EPISTEMIC),
        (epi, pure, OperationClass.EPISTEMIC),
        (epi, epi, OperationClass.EPISTEMIC),
        (pure, gov, OperationClass.GOVERNANCE),
        (gov, pure, OperationClass.GOVERNANCE),
        (epi, gov, OperationClass.GOVERNANCE),
        (gov, epi, OperationClass.GOVERNANCE),
        (gov, gov, OperationClass.GOVERNANCE),
    ]:
        # types are bridged according to the actual output/input pair
        ww = W(first.output_type, second.input_type)
        r = compose(first, ww, second)
        assert r.op_class is expected

    # --- 5. Scope mismatch must fail closed.
    S2 = Scope("demo", "other", "2026")
    b2 = make("b2", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:2*x, R, S2)
    try:
        compose(A, W("B","C"), b2)
        assert False
    except ValueError:
        pass

    # --- 6. Regime mismatch must fail closed.
    R2 = Regime("different", frozenset({"different"}), frozenset())
    badw = witness("B", "C", R2)
    try:
        compose(A, badw, B)
        assert False
    except ValueError:
        pass

    # --- 7. Preservation is not inferred merely from type compatibility.
    Z = PreservationTarget("parity", lambda x: x % 2)
    preserve_a = make("pA", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x+2, R, S)
    preserve_b = make("pB", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x*1, R, S)
    # This is a deliberately modest executable check: same target value for samples.
    for x in range(-10, 11):
        assert Z.evaluator(preserve_a.transform(x)) == Z.evaluator(x)
    assert Z.name == "parity"

    # --- 8. Loss is a declared profile, not a theorem that union is semantically complete.
    l1 = LossProfile(frozenset({"y"}), frozenset({"y"}), frozenset())
    l2 = LossProfile(frozenset({"z"}), frozenset({"z"}), frozenset())
    a_loss = make("aL", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x, R, S)
    b_loss = OperationSpecification("bL", "C", "D", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x, loss_profile=l2, regime=R, scope=S)
    wloss = CompatibilityWitness("B","C",lambda x:x,lambda _:True,None,l1,R)
    rl = compose(a_loss, wloss, b_loss)
    assert rl.loss_profile.declared_loss == frozenset({"y","z"})

    # --- 9. Provenance composes as lineage metadata; it does not become evidence of truth.
    rprov = compose(A, W("B","C"), B)
    assert rprov.provenance is not None
    assert rprov.provenance.lineage == ("root", "step2")

    # --- 10. Provenance itself must not determine observational equivalence unless observed.
    A2 = make("inc2", "A", "B", OperationClass.PURE, MutationPolicy.FORBIDDEN, lambda x:x+1, R, S, Provenance("different","other","2026-09-19T12:00:00"))
    assert equivalent(A, A2, range(-10,11))

    return 10

if __name__ == "__main__":
    n = run_tests()
    print(f"R604.3 composition-law tests: {n}/{n} passed")
