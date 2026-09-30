from __future__ import annotations
from dataclasses import dataclass
from typing import Any, Callable, Iterable, Optional
from enum import Enum

class Status(str, Enum):
    PASS = "PASS"
    FAIL = "FAIL"
    UNKNOWN = "UNKNOWN"
    CONDITIONAL = "CONDITIONAL"
    UNDEFINED = "UNDEFINED"
    NOT_APPLICABLE = "NOT_APPLICABLE"

@dataclass(frozen=True)
class LossProfile:
    discarded_dimensions: frozenset[str] = frozenset()
    declared_loss: frozenset[str] = frozenset()
    preservation_targets: frozenset[str] = frozenset()

@dataclass(frozen=True)
class Provenance:
    source: str
    actor: str
    lineage: tuple[str, ...] = ()

@dataclass(frozen=True)
class FiniteTransformation:
    name: str
    domain: tuple[Any, ...]
    codomain: tuple[Any, ...]
    transform: Callable[[Any], Any]
    precondition: Callable[[Any], bool] = lambda _: True
    provenance: Optional[Provenance] = None
    loss_profile: LossProfile = LossProfile()

@dataclass(frozen=True)
class Reconstruction:
    name: str
    reconstruct: Callable[[Any], Any]
    source: str
    provenance: Provenance

@dataclass(frozen=True)
class Augmentation:
    """Adds external information; it is not, by itself, recovery of discarded information."""
    name: str
    augment: Callable[[Any], Any]
    external_source: str
    provenance: Provenance


def defined_inputs(t: FiniteTransformation) -> list[Any]:
    return [x for x in t.domain if t.precondition(x)]


def tpp_holds(t: FiniteTransformation, target: Callable[[Any], Any]) -> bool:
    xs = defined_inputs(t)
    for x1 in xs:
        for x2 in xs:
            if t.transform(x1) == t.transform(x2) and target(x1) != target(x2):
                return False
    return True


def tpp_counterexample(t: FiniteTransformation, target: Callable[[Any], Any]):
    xs = defined_inputs(t)
    for x1 in xs:
        for x2 in xs:
            if t.transform(x1) == t.transform(x2) and target(x1) != target(x2):
                return (x1, x2, t.transform(x1), target(x1), target(x2))
    return None


def target_recovery(t: FiniteTransformation, target: Callable[[Any], Any], codomain_values: Iterable[Any]):
    """Find g with target(x)=g(t(x)) over the finite defined domain, if one exists."""
    mapping = {}
    for x in defined_inputs(t):
        y = t.transform(x)
        z = target(x)
        if y in mapping and mapping[y] != z:
            return None
        mapping[y] = z
    return mapping


def has_left_inverse(t: FiniteTransformation) -> bool:
    seen = {}
    for x in defined_inputs(t):
        y = t.transform(x)
        if y in seen and seen[y] != x:
            return False
        seen[y] = x
    return True


def target_loss_status(t: FiniteTransformation, target: Callable[[Any], Any]) -> Status:
    if not defined_inputs(t):
        return Status.UNKNOWN
    return Status.PASS if tpp_holds(t, target) else Status.FAIL


def compose(a: FiniteTransformation, b: FiniteTransformation) -> FiniteTransformation:
    if not set(a.codomain).intersection(b.domain):
        # Still constructable, but no reachable composed executions.
        domain = a.domain
    else:
        domain = a.domain
    reachable = tuple(b.transform(a.transform(x)) for x in a.domain if a.precondition(x) and b.precondition(a.transform(x)))
    codomain = tuple(dict.fromkeys(reachable))
    return FiniteTransformation(
        name=f"({b.name}∘{a.name})",
        domain=domain,
        codomain=codomain,
        transform=lambda x: b.transform(a.transform(x)),
        precondition=lambda x: a.precondition(x) and b.precondition(a.transform(x)),
        provenance=(Provenance("composed", "reference-calculus", (a.name, b.name))),
        loss_profile=LossProfile(
            discarded_dimensions=a.loss_profile.discarded_dimensions | b.loss_profile.discarded_dimensions,
            declared_loss=a.loss_profile.declared_loss | b.loss_profile.declared_loss,
            preservation_targets=a.loss_profile.preservation_targets | b.loss_profile.preservation_targets,
        ),
    )


def prove_no_posthoc_recovery(t: FiniteTransformation, post: FiniteTransformation, target: Callable[[Any], Any]) -> bool:
    """If t fails TPP for target, then post∘t also fails TPP, provided post is a deterministic function of t(x)."""
    c = compose(t, post)
    witness = tpp_counterexample(t, target)
    if witness is None:
        return False
    x1, x2, y, z1, z2 = witness
    return c.transform(x1) == c.transform(x2) and z1 != z2


def validate_external_augmentation_not_recovery(t: FiniteTransformation, target: Callable[[Any], Any], augmentation: Augmentation) -> bool:
    """The augmentation is classified as external unless its added value is provenance-linked to the original input."""
    return augmentation.external_source != (t.provenance.source if t.provenance else None)


def run_tests() -> int:
    # W = {0,1,2,3}; projection keeps parity class only.
    W = (0, 1, 2, 3)
    parity = lambda x: x % 2
    identity = lambda x: x
    even_projection = FiniteTransformation(
        "parity-projection", W, (0, 1), parity,
        provenance=Provenance("original-dataset", "analyst", ("raw",)),
        loss_profile=LossProfile(frozenset({"exact-value"}), frozenset({"exact-value"}), frozenset()),
    )

    # 1. TPP failure detects target-relevant information loss.
    assert target_loss_status(even_projection, identity) is Status.FAIL

    # 2. A target that is a function of the projection is recoverable.
    recovered = target_recovery(even_projection, parity, even_projection.codomain)
    assert recovered == {0: 0, 1: 1}
    assert target_loss_status(even_projection, parity) is Status.PASS

    # 3. Exact target recovery is impossible when TPP fails.
    assert target_recovery(even_projection, identity, even_projection.codomain) is None

    # 4. A transformation is fully lossless on a finite domain iff it is injective (left-invertible here).
    injective = FiniteTransformation("injective", W, W, lambda x: x + 10)
    noninjective = FiniteTransformation("noninjective", W, (0, 1), parity)
    assert has_left_inverse(injective)
    assert not has_left_inverse(noninjective)

    # 5. Target-level recoverability can hold even when full-state recovery cannot.
    assert not has_left_inverse(noninjective)
    assert tpp_holds(noninjective, parity)

    # 6. Once a target is lost by a deterministic transformation, post-processing cannot recover it.
    post = FiniteTransformation("post", (0, 1), (10, 20), lambda y: 10 if y == 0 else 20)
    assert prove_no_posthoc_recovery(even_projection, post, identity)

    # 7. Composition can introduce new loss even when the first transformation preserves the target.
    add2 = FiniteTransformation("add2", W, (2, 3, 4, 5), lambda x: x + 2)
    parity_losing = FiniteTransformation("collapse", (2, 3, 4, 5), (0, 1), lambda y: y % 2)
    # add2 preserves parity; collapse's output does not preserve exact value.
    assert tpp_holds(add2, parity)
    composed = compose(add2, parity_losing)
    assert target_loss_status(composed, identity) is Status.FAIL

    # 8. Empty defined domain is UNKNOWN, not PASS.
    undefined = FiniteTransformation("undefined", W, (), lambda x: x, precondition=lambda _: False)
    assert target_loss_status(undefined, identity) is Status.UNKNOWN

    # 9. External augmentation must not be mislabeled as recovery of discarded information.
    augmentation = Augmentation(
        "lookup-external-value", lambda y: (y, "external"), "external-database",
        Provenance("external-database", "system", ("lookup",)),
    )
    assert validate_external_augmentation_not_recovery(even_projection, identity, augmentation)

    # 10. Declared LossProfile is not itself a semantic proof of loss; executable behavior decides assessment.
    declared_only = FiniteTransformation(
        "declared-loss-but-injective", W, (10, 11, 12, 13), lambda x: x + 10,
        loss_profile=LossProfile(frozenset({"claimed-dimension"}), frozenset({"claimed-dimension"}), frozenset()),
    )
    assert target_loss_status(declared_only, identity) is Status.PASS

    return 10

if __name__ == "__main__":
    n = run_tests()
    print(f"R604.5 loss/TPP/recovery tests: {n}/{n} passed")
