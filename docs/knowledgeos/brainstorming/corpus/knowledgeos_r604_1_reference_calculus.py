
from __future__ import annotations
from dataclasses import dataclass, field
from enum import Enum
from typing import Any, Callable, Iterable, Optional


# ---------------- Core vocabulary ----------------

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
    OperationClass.EPISTEMIC: {
        MutationPolicy.DECLARED,
        MutationPolicy.GOVERNED,
    },
    OperationClass.GOVERNANCE: {MutationPolicy.GOVERNED},
}


@dataclass(frozen=True)
class Scope:
    """
    Scope = the declared population/domain/time boundary of a claim.
    It prevents a result valid for one population or period from silently
    becoming a claim about another.
    """
    domain: str
    population: str
    time_range: str
    identifier: str = ""


@dataclass(frozen=True)
class Regime:
    """
    Regime = the declared semantic/rule system under which an operation
    or assessment is interpreted.
    """
    name: str
    axioms: frozenset[str] = frozenset()
    rules: frozenset[str] = frozenset()


@dataclass(frozen=True)
class Provenance:
    """
    Provenance = where an artifact/result came from and how it was produced.
    """
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
    regime: Regime

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
    regime: Optional[Regime] = None
    scope: Optional[Scope] = None


@dataclass(frozen=True)
class Execution:
    operation: str
    input_value: Any
    output_value: Any
    state_before: State
    state_after: State
    scope: Optional[Scope]
    regime: Optional[Regime]
    provenance: Provenance


@dataclass(frozen=True)
class Assessment:
    """
    Assessment = evaluated epistemic claim.
    It is not an execution result and is not authoritative state.
    """
    claim: str
    value: Any
    scope: Scope
    regime: Regime
    provenance: Provenance


@dataclass(frozen=True)
class Certificate:
    """
    Certificate = scoped assertion that a verification condition was met.
    It is evidence about verification, not a truth certificate.
    """
    certificate_id: str
    invariant_id: str
    status: Status
    scope: Scope
    regime: Regime
    method: str
    provenance: Provenance


@dataclass(frozen=True)
class VerificationResult:
    invariant_id: str
    operation: str
    scope: Optional[Scope]
    expected: Any
    actual: Any
    status: Status
    method: str
    counterexample: Any = None
    provenance: Optional[Provenance] = None
    certificate: Optional[Certificate] = None
    message: str = ""


# ---------------- Admissibility / execution ----------------

def validate_operation(spec: OperationSpecification) -> None:
    if spec.mutation not in ADMISSIBLE_MUTATION[spec.op_class]:
        raise TypeError(
            f"Illegal Class × Mutation pair: "
            f"{spec.op_class.value} × {spec.mutation.value}"
        )


def execute(
    state: State,
    spec: OperationSpecification,
    value: Any,
    contract: Contract,
    provenance: Provenance,
    *,
    record_history: bool = True,
) -> Execution:
    validate_operation(spec)

    if not contract.precondition(value):
        raise ValueError("Contract precondition failed")
    if not spec.precondition(value):
        raise ValueError("Operation precondition failed")

    before = State(state.X, list(state.H))
    output = spec.transform(value)

    if spec.mutation is MutationPolicy.FORBIDDEN:
        new_x = state.X
    elif not contract.allowed_mutation:
        raise PermissionError("Contract does not authorize authoritative mutation")
    else:
        new_x = output

    new_h = list(state.H)
    if record_history:
        new_h.append(
            Event(
                "OperationExecuted",
                {
                    "operation": spec.name,
                    "class": spec.op_class.value,
                    "scope": spec.scope.identifier if spec.scope else None,
                    "regime": spec.regime.name if spec.regime else None,
                },
            )
        )

    after = State(new_x, new_h)
    return Execution(
        operation=spec.name,
        input_value=value,
        output_value=output,
        state_before=before,
        state_after=after,
        scope=spec.scope,
        regime=spec.regime,
        provenance=provenance,
    )


# ---------------- Verification ----------------

def verify_pure_x_immutability(
    execution: Execution,
) -> VerificationResult:
    if execution.state_before.X == execution.state_after.X:
        return VerificationResult(
            "I-X02",
            execution.operation,
            execution.scope,
            True,
            True,
            Status.PASS,
            "exact_equality",
            provenance=execution.provenance,
        )

    return VerificationResult(
        "I-X02",
        execution.operation,
        execution.scope,
        True,
        False,
        Status.FAIL,
        "exact_equality",
        counterexample={
            "before_X": execution.state_before.X,
            "after_X": execution.state_after.X,
        },
        provenance=execution.provenance,
    )


def tpp_exhaustive(
    states: Iterable[Any],
    projection: Callable[[Any], Any],
    target: Callable[[Any], Any],
    scope: Scope,
    provenance: Provenance,
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
                "target_values": sorted(values, key=repr),
            }
            return VerificationResult(
                "I-X05/I-X06",
                "TPP",
                scope,
                True,
                False,
                Status.FAIL,
                "finite_exhaustive",
                counterexample=witness,
                provenance=provenance,
            )

    return VerificationResult(
        "I-X05/I-X06",
        "TPP",
        scope,
        True,
        True,
        Status.PASS,
        "finite_exhaustive",
        provenance=provenance,
    )


def preservation_check(
    domain: Iterable[Any],
    transform: Callable[[Any], Any],
    source_target: PreservationTarget,
    target_target: PreservationTarget,
    scope: Scope,
    provenance: Provenance,
) -> VerificationResult:
    for x in domain:
        lhs = source_target.evaluator(x)
        rhs = target_target.evaluator(transform(x))
        if lhs != rhs:
            return VerificationResult(
                "Preservation",
                "Transformation",
                scope,
                True,
                False,
                Status.FAIL,
                "finite_exhaustive",
                counterexample={
                    "input": x,
                    "expected": lhs,
                    "actual": rhs,
                },
                provenance=provenance,
            )

    return VerificationResult(
        "Preservation",
        "Transformation",
        scope,
        True,
        True,
        Status.PASS,
        "finite_exhaustive",
        provenance=provenance,
    )


def metamorphic_check(
    input_value: Any,
    transformed_input: Any,
    target: Callable[[Any], Any],
    relation_name: str,
    scope: Scope,
    provenance: Provenance,
    *,
    declared_irrelevance: bool,
) -> VerificationResult:
    if not declared_irrelevance:
        return VerificationResult(
            "Metamorphic",
            relation_name,
            scope,
            "NotApplicable",
            None,
            Status.NOT_APPLICABLE,
            "contract_scope_check",
            provenance=provenance,
            message="No contract declared the transformation irrelevant to the target.",
        )

    before = target(input_value)
    after = target(transformed_input)

    if before == after:
        return VerificationResult(
            "Metamorphic",
            relation_name,
            scope,
            before,
            after,
            Status.PASS,
            "exact_target_equality",
            provenance=provenance,
        )

    return VerificationResult(
        "Metamorphic",
        relation_name,
        scope,
        before,
        after,
        Status.FAIL,
        "exact_target_equality",
        counterexample={
            "input": input_value,
            "transformed_input": transformed_input,
        },
        provenance=provenance,
    )


def issue_certificate(
    result: VerificationResult,
    certificate_id: str,
) -> Certificate:
    if result.status not in {Status.PASS, Status.FAIL}:
        raise ValueError("Only resolved PASS/FAIL results can be certified.")

    if result.scope is None or result.provenance is None:
        raise ValueError("Certificate requires scope and provenance.")

    # The certificate records the verification outcome.
    # It does not assert world truth.
    return Certificate(
        certificate_id=certificate_id,
        invariant_id=result.invariant_id,
        status=result.status,
        scope=result.scope,
        regime=Regime("certificate-regime"),
        method=result.method,
        provenance=result.provenance,
    )


# ---------------- Tests ----------------

BASE_SCOPE = Scope(
    domain="KnowledgeOS reference calculus",
    population="finite-test-domain",
    time_range="test-run",
    identifier="R604.1",
)

BASE_REGIME = Regime(
    name="finite-reference",
    axioms=frozenset({"class-mutation-admissibility"}),
    rules=frozenset({"pure-preserves-X"}),
)

PROV = Provenance(
    source="KnowledgeOS reference test",
    actor="reference-calculus",
    timestamp="test-run",
    lineage=("R602.4", "R604.1"),
)


def test_scope_and_regime_are_preserved_in_execution():
    s = State({"authoritative": 10})
    op = OperationSpecification(
        "Evaluate",
        "Evidence",
        "Assessment",
        OperationClass.PURE,
        MutationPolicy.FORBIDDEN,
        lambda x: {"score": x},
        scope=BASE_SCOPE,
        regime=BASE_REGIME,
    )
    e = execute(s, op, 7, Contract("eval"), PROV)
    assert e.scope == BASE_SCOPE
    assert e.regime == BASE_REGIME


def test_assessment_is_not_execution():
    a = Assessment(
        claim="amount is 100",
        value=True,
        scope=BASE_SCOPE,
        regime=BASE_REGIME,
        provenance=PROV,
    )
    assert not isinstance(a, Execution)
    assert a.value is True


def test_pure_i_x02_with_execution_record():
    s = State({"authoritative": 10})
    op = OperationSpecification(
        "Evaluate",
        "Evidence",
        "Assessment",
        OperationClass.PURE,
        MutationPolicy.FORBIDDEN,
        lambda x: {"score": x},
        scope=BASE_SCOPE,
        regime=BASE_REGIME,
    )
    e = execute(s, op, 7, Contract("eval"), PROV)
    r = verify_pure_x_immutability(e)
    assert r.status is Status.PASS
    assert r.scope == BASE_SCOPE


def test_metamorphic_provenance_irrelevance():
    record = {"amount": 100, "payer": "A", "payee": "B"}
    enriched = {**record, "provenance": {"source": "Bank-A", "timestamp": "t"}}

    target = lambda x: x["amount"]
    r = metamorphic_check(
        record,
        enriched,
        target,
        "provenance-addition",
        BASE_SCOPE,
        PROV,
        declared_irrelevance=True,
    )
    assert r.status is Status.PASS


def test_metamorphic_relation_not_assumed_without_contract():
    record = {"amount": 100}
    enriched = {"amount": 100, "provenance": "source-A"}

    r = metamorphic_check(
        record,
        enriched,
        lambda x: x["amount"],
        "provenance-addition",
        BASE_SCOPE,
        PROV,
        declared_irrelevance=False,
    )
    assert r.status is Status.NOT_APPLICABLE


def test_metamorphic_counterexample():
    before = {"amount": 100}
    after = {"amount": 101}

    r = metamorphic_check(
        before,
        after,
        lambda x: x["amount"],
        "amount-change",
        BASE_SCOPE,
        PROV,
        declared_irrelevance=True,
    )
    assert r.status is Status.FAIL
    assert r.counterexample is not None


def test_certificate_is_scoped():
    result = VerificationResult(
        "I-X02",
        "Evaluate",
        BASE_SCOPE,
        True,
        True,
        Status.PASS,
        "exact_equality",
        provenance=PROV,
    )
    cert = issue_certificate(result, "CERT-R604-001")
    assert cert.invariant_id == "I-X02"
    assert cert.status is Status.PASS
    assert cert.scope == BASE_SCOPE


def test_certificate_not_issued_for_unknown():
    result = VerificationResult(
        "I-X02",
        "Evaluate",
        BASE_SCOPE,
        True,
        None,
        Status.UNKNOWN,
        "insufficient-information",
        provenance=PROV,
    )
    try:
        issue_certificate(result, "CERT-R604-002")
    except ValueError:
        return
    raise AssertionError("UNKNOWN must not silently become a certificate")


def test_tpp_still_finds_counterexample():
    states = [(h, t) for h in range(5) for t in (2, 3)]
    r = tpp_exhaustive(
        states,
        lambda s: s[0],
        lambda s: int(s[0] >= s[1]),
        BASE_SCOPE,
        PROV,
    )
    assert r.status is Status.FAIL
    assert r.counterexample is not None


def test_projection_aggregation_deduplication():
    records = [
        ("p1", 101, 20),
        ("p2", 101, 20),
        ("p3", 102, 20),
    ]

    projected = [(r[1], r[2]) for r in records]
    deduped = list(dict.fromkeys(projected))

    aggregated = {}
    for _, zip_code, _ in records:
        aggregated[zip_code] = aggregated.get(zip_code, 0) + 1

    assert len(projected) == 3
    assert len(deduped) == 2
    assert aggregated == {101: 2, 102: 1}


def run_all():
    tests = [
        test_scope_and_regime_are_preserved_in_execution,
        test_assessment_is_not_execution,
        test_pure_i_x02_with_execution_record,
        test_metamorphic_provenance_irrelevance,
        test_metamorphic_relation_not_assumed_without_contract,
        test_metamorphic_counterexample,
        test_certificate_is_scoped,
        test_certificate_not_issued_for_unknown,
        test_tpp_still_finds_counterexample,
        test_projection_aggregation_deduplication,
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
