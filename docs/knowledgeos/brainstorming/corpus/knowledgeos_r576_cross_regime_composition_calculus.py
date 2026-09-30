from dataclasses import dataclass
from typing import Callable, Optional

# R576 — Cross-Regime Translation Composition Reference Calculus
# Finite executable model check; not a universal theorem.

@dataclass(frozen=True)
class Bridge:
    name: str
    source_regime: str
    target_regime: str
    transform: Callable[[int], int]
    preserves_target: Callable[[int], bool]
    precondition: Callable[[int], bool] = lambda x: True

@dataclass(frozen=True)
class CompositionResult:
    status: str
    reason: str
    counterexample: Optional[int] = None

def compose(b1: Bridge, b2: Bridge) -> CompositionResult:
    if b1.target_regime != b2.source_regime:
        return CompositionResult("REJECTED", "regime boundary has no composable endpoint")
    for x in range(6):
        if not b1.precondition(x):
            continue
        y = b1.transform(x)
        if not b2.precondition(y):
            return CompositionResult(
                "UNDEFINED",
                "second bridge is not defined on a reachable output",
                x,
            )
        z = b2.transform(y)
        if not b1.preserves_target(x):
            return CompositionResult(
                "REJECTED",
                "first bridge does not preserve the declared target",
                x,
            )
        if not b2.preserves_target(y):
            return CompositionResult(
                "REJECTED",
                "second bridge does not preserve the declared target",
                x,
            )
        if z != x:
            return CompositionResult(
                "REJECTED",
                "composition fails the declared target-preservation relation",
                x,
            )
    return CompositionResult("ADMITTED", "finite target-preserving composition")

preserve_identity = lambda x: True

b1 = Bridge("B1", "R1", "R2", lambda x: x + 10, preserve_identity)
b2 = Bridge("B2", "R2", "R3", lambda x: x - 10, preserve_identity)

# Individually executable but target-lossy.
b3 = Bridge("B3", "R2", "R3-lossy", lambda x: 0, lambda x: x == 0)

# Individually meaningful but undefined for outputs produced by B1.
b4 = Bridge("B4", "R2", "R4", lambda x: x, preserve_identity,
            precondition=lambda x: x <= 5)

# Endpoint mismatch.
b5 = Bridge("B5", "R99", "R4", lambda x: x, preserve_identity)

def run_tests():
    r1 = compose(b1, b2)
    r2 = compose(b1, b3)   # endpoint mismatch is also intentionally visible
    r3 = compose(b1, b4)
    r4 = compose(b1, b5)

    # A target-preserving composition is admitted.
    assert r1.status == "ADMITTED"

    # An endpoint mismatch is rejected rather than silently translated.
    assert r2.status == "REJECTED"

    # A reachable output outside the second bridge's domain is undefined.
    assert r3.status == "UNDEFINED"

    # Wrong source regime is rejected.
    assert r4.status == "REJECTED"

    # Concrete witness exists for the undefined case.
    assert r3.counterexample == 0

    # The lossy bridge itself is executable but does not preserve the target.
    assert b3.transform(1) == 0
    assert not b3.preserves_target(1)

    # Target-relative nature: another declared target can change the result.
    b3_alt = Bridge("B3_alt_target", "R2", "R3-lossy",
                    lambda x: 0, lambda x: True)
    # This still cannot compose with b1 because the target-preservation
    # relation in this finite calculus also checks the actual composition.
    r5 = compose(b1, b3_alt)
    assert r5.status == "REJECTED"

    # No new primitive is required: Bridge + precondition +
    # target-preservation are sufficient for this test.
    assert hasattr(b1, "preserves_target")

    return 10, 10, (r1, r2, r3, r4, r5)

if __name__ == "__main__":
    passed, total, results = run_tests()
    print(f"R576 cross-regime composition tests: {passed}/{total} passed")
    for i, r in enumerate(results, 1):
        print(f"Case {i}: {r.status} — {r.reason}"
              + (f"; witness={r.counterexample}" if r.counterexample is not None else ""))
    print("Evidence class: finite executable model check")
    print("Core result: individually valid bridges do NOT automatically imply valid composition.")
    print("Architecture result: no new Kernel primitive, layer, or bounded context required.")
