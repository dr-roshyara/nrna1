
from __future__ import annotations
from dataclasses import dataclass, field
from enum import Enum
from typing import Any, Callable, Iterable, Optional


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
class Event:
    event_type: str
    payload: dict[str, Any] = field(default_factory=dict)


@dataclass
class State:
    X: Any
    H: list[Event] = field(default_factory=list)

    def snapshot(self) -> tuple[Any, tuple[Event, ...]]:
        return self.X, tuple(self.H)


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
    regime: str

    def apply(self, value: Any) -> Any:
        if not self.pre(value):
            raise ValueError("CompatibilityWitness precondition failed")
        return self.conv(value)


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
    regime: str = "default"


@dataclass(frozen=True)
class VerificationResult:
    invariant_id: str
    operation: str
    expected: Any
    actual: Any
    status: Status
    method: str
    counterexample: Any = None
    message: str = ""


def validate_operation(spec: OperationSpecification) -> None:
    allowed = ADMISSIBLE_MUTATION[spec.op_class]
    if spec.mutation not in allowed:
        raise TypeError(
            f"Illegal Class × Mutation pair: {spec.op_class.value} × "
            f"{spec.mutation.value}"
        )


def execute(
    state: State,
    spec: OperationSpecification,
    value: Any,
    contract: Contract,
    *,
    record_history: bool = True,
) -> tuple[State, Any]:
    validate_operation(spec)

    if not contract.precondition(value):
        raise ValueError("Contract precondition failed")
    if not spec.precondition(value):
        raise ValueError("Operation precondition failed")

    old_x = state.X
    output = spec.transform(value)

    if spec.mutation is MutationPolicy.FORBIDDEN:
        new_x = old_x
    elif spec.mutation in (MutationPolicy.DECLARED, MutationPolicy.GOVERNED):
        if not contract.allowed_mutation:
            raise PermissionError("Contract does not authorize authoritative mutation")
        new_x = output
    else:
        raise TypeError("Unknown mutation policy")

    new_h = list(state.H)
    if record_history:
        new_h.append(Event(
            "OperationExecuted",
            {"operation": spec.name, "class": spec.op_class.value},
        ))
    return State(new_x, new_h), output


def verify_pure_x_immutability(
    before: State, after: State, operation: OperationSpecification
) -> VerificationResult:
    if operation.op_class is not OperationClass.PURE:
        return VerificationResult(
            "I-X02", operation.name, True, None, Status.NOT_APPLICABLE,
            "typed_scope_check"
        )

    actual = before.X == after.X
    return VerificationResult(
        "I-X02", operation.name, True, actual,
        Status.PASS if actual else Status.FAIL,
        "exact_equality",
        None if actual else {"before_X": before.X, "after_X": after.X},
    )


def tpp_exhaustive(
    states: Iterable[Any],
    projection: Callable[[Any], Any],
    target: Callable[[Any], Any],
) -> VerificationResult:
    states = list(states)
    buckets: dict[Any, list[Any]] = {}
    for s in states:
        buckets.setdefault(projection(s), []).append(s)

    for projected, members in buckets.items():
        values = {target(s) for s in members}
        if len(values) > 1:
            witness = {
                "projection_value": projected,
                "states": members,
                "target_values": list(values),
            }
            return VerificationResult(
                "I-X05/I-X06", "TPP", True, False, Status.FAIL,
                "finite_exhaustive", witness,
                "Projection does not preserve the target on the declared state space.",
            )

    return VerificationResult(
        "I-X05/I-X06", "TPP", True, True, Status.PASS,
        "finite_exhaustive"
    )


def preservation_check(
    domain: Iterable[Any],
    transform: Callable[[Any], Any],
    source_target: PreservationTarget,
    target_target: PreservationTarget,
) -> VerificationResult:
    for x in domain:
        lhs = source_target.evaluator(x)
        rhs = target_target.evaluator(transform(x))
        if lhs != rhs:
            return VerificationResult(
                "Preservation", "Transformation", True, False, Status.FAIL,
                "finite_exhaustive",
                {"input": x, "expected": lhs, "actual": rhs},
            )
    return VerificationResult(
        "Preservation", "Transformation", True, True,
        Status.PASS, "finite_exhaustive"
    )


def compose_with_witness(
    t1: OperationSpecification,
    t2: OperationSpecification,
    witness: CompatibilityWitness,
) -> OperationSpecification:
    if witness.src != t1.output_type or witness.tgt != t2.input_type:
        raise TypeError("CompatibilityWitness types do not bridge the two operations")

    def composite(x: Any) -> Any:
        y = t1.transform(x)
        y2 = witness.apply(y)
        return t2.transform(y2)

    return OperationSpecification(
        name=f"{t2.name} ∘ {witness.src}->{witness.tgt} ∘ {t1.name}",
        input_type=t1.input_type,
        output_type=t2.output_type,
        op_class=OperationClass.PURE,
        mutation=MutationPolicy.FORBIDDEN,
        transform=composite,
        regime=t2.regime,
    )


# ---------------- deterministic reference tests ----------------

def test_pure_does_not_mutate_X():
    s = State({"authoritative": 10})
    op = OperationSpecification(
        "Evaluate", "Evidence", "Assessment",
        OperationClass.PURE, MutationPolicy.FORBIDDEN,
        lambda x: {"score": x},
    )
    after, _ = execute(s, op, 7, Contract("eval"))
    r = verify_pure_x_immutability(s, after, op)
    assert r.status is Status.PASS
    assert after.X == s.X
    assert len(after.H) == len(s.H) + 1


def test_epistemic_declared_mutation():
    s = State({"version": 1})
    op = OperationSpecification(
        "Revise", "Assessment", "State",
        OperationClass.EPISTEMIC, MutationPolicy.DECLARED,
        lambda x: {"version": x},
    )
    after, _ = execute(
        s, op, 2, Contract("revision", allowed_mutation=True)
    )
    assert after.X != s.X
    assert after.X == {"version": 2}


def test_illegal_class_mutation_pair():
    op = OperationSpecification(
        "Bad", "A", "B",
        OperationClass.PURE, MutationPolicy.DECLARED,
        lambda x: x,
    )
    try:
        validate_operation(op)
    except TypeError:
        return
    raise AssertionError("Illegal Pure × Declared pair was accepted")


def test_partial_compatibility_witness():
    w = CompatibilityWitness(
        "String", "PositiveInteger",
        int,
        lambda x: isinstance(x, str) and x.isdigit() and int(x) > 0,
        None,
        LossProfile(),
        "default",
    )
    assert w.apply("12") == 12
    try:
        w.apply("abc")
    except ValueError:
        return
    raise AssertionError("Invalid conversion input was accepted")


def test_tpp_counterexample():
    states = [(h, t) for h in range(5) for t in (2, 3)]
    r = tpp_exhaustive(
        states,
        projection=lambda s: s[0],
        target=lambda s: int(s[0] >= s[1]),
    )
    assert r.status is Status.FAIL
    assert r.counterexample is not None


def test_tpp_holds_on_restricted_space():
    states = [(h, 2) for h in range(5)]
    r = tpp_exhaustive(
        states,
        projection=lambda s: s[0],
        target=lambda s: int(s[0] >= s[1]),
    )
    assert r.status is Status.PASS


def test_projection_aggregation_deduplication_distinct():
    records = [("p1", 101, 20), ("p2", 101, 20), ("p3", 102, 20)]
    projected = [(r[1], r[2]) for r in records]
    deduped = list(dict.fromkeys(projected))
    aggregated = {101: 0, 102: 0}
    for _, zip_code, _ in records:
        aggregated[zip_code] += 1

    assert len(projected) == 3
    assert len(deduped) == 2
    assert aggregated == {101: 2, 102: 1}


def test_preservation():
    domain = [0, 1, 2, 3]
    identity_target = PreservationTarget("value", lambda x: x)
    r = preservation_check(domain, lambda x: x + 0, identity_target, identity_target)
    assert r.status is Status.PASS


def test_preservation_failure():
    domain = [0, 1, 2, 3]
    identity_target = PreservationTarget("value", lambda x: x)
    r = preservation_check(domain, lambda x: 0, identity_target, identity_target)
    assert r.status is Status.FAIL
    assert r.counterexample is not None


def test_composition_witness():
    t1 = OperationSpecification(
        "MetersToCentimeters", "Meter", "Centimeter",
        OperationClass.PURE, MutationPolicy.FORBIDDEN,
        lambda x: x * 100,
    )
    t2 = OperationSpecification(
        "NormalizePositive", "PositiveLength", "NormalizedLength",
        OperationClass.PURE, MutationPolicy.FORBIDDEN,
        lambda x: x,
    )
    w = CompatibilityWitness(
        "Centimeter", "PositiveLength",
        float, lambda x: x > 0,
        PreservationTarget("length", lambda x: x),
        LossProfile(),
        "metric",
    )
    composed = compose_with_witness(t1, t2, w)
    assert composed.transform(1.5) == 150.0


def test_status_distinctions():
    assert Status.UNKNOWN is not Status.FAIL
    assert Status.UNDEFINED is not Status.FAIL
    assert Status.NOT_APPLICABLE is not Status.PASS


def run_all():
    tests = [
        test_pure_does_not_mutate_X,
        test_epistemic_declared_mutation,
        test_illegal_class_mutation_pair,
        test_partial_compatibility_witness,
        test_tpp_counterexample,
        test_tpp_holds_on_restricted_space,
        test_projection_aggregation_deduplication_distinct,
        test_preservation,
        test_preservation_failure,
        test_composition_witness,
        test_status_distinctions,
    ]
    failures = []
    for test in tests:
        try:
            test()
            print(f"PASS  {test.__name__}")
        except Exception as exc:
            failures.append((test.__name__, repr(exc)))
            print(f"FAIL  {test.__name__}: {exc!r}")

    print(f"\n{len(tests) - len(failures)}/{len(tests)} tests passed.")
    if failures:
        raise SystemExit(1)


if __name__ == "__main__":
    run_all()
