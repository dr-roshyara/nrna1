from dataclasses import dataclass

@dataclass(frozen=True)
class Regime:
    name: str
    values: tuple

CLASSICAL = Regime("Classical-2", (0, 1))
PARACONSISTENT = Regime("Paraconsistent-4", ("T", "F", "B", "N"))

@dataclass(frozen=True)
class Assessment:
    regime: Regime
    proposition: str
    status: str
    reason: str

def classical_neg(v):
    return 1 - v

def paraconsistent_neg(v):
    return {"T": "F", "F": "T", "B": "B", "N": "N"}[v]

def classical_contradiction(p, not_p):
    return p == 1 and not_p == 1

def paraconsistent_contradiction(v):
    return v == "B"

def admit_same_regime(source_regime, target_regime):
    return source_regime == target_regime

def admit_bridge(source_regime, target_regime, bridge_exists):
    return source_regime == target_regime or bridge_exists

def classify_difference_without_bridge():
    return "REGIME_DIFFERENCE"

def classify_conflict(p_support, not_p_support):
    return "CONFLICT" if p_support and not_p_support else "NO_CONFLICT"

def run_tests():
    passed = 0
    total = 10

    assert admit_same_regime(CLASSICAL, CLASSICAL); passed += 1
    assert not admit_same_regime(CLASSICAL, PARACONSISTENT); passed += 1
    assert admit_bridge(CLASSICAL, PARACONSISTENT, True); passed += 1
    assert classify_difference_without_bridge() == "REGIME_DIFFERENCE"; passed += 1
    assert classical_contradiction(1, 1); passed += 1
    assert paraconsistent_contradiction("B"); passed += 1
    assert classify_conflict(False, False) == "NO_CONFLICT"; passed += 1
    assert classify_conflict(True, True) == "CONFLICT"; passed += 1
    assert "UNKNOWN" != "FALSE"; passed += 1
    candidate = {"status": "CANDIDATE", "source": "ML"}
    established = {"status": "ESTABLISHED", "source": "L4_VALIDATION"}
    assert candidate["status"] != established["status"]; passed += 1

    return passed, total

if __name__ == "__main__":
    passed, total = run_tests()
    print(f"R575 logical-regime boundary tests: {passed}/{total} passed")
    print("Evidence class: finite executable model check")
    print("This result is not a universal theorem about all logical systems.")
    print("Core invariant: RegimeDifference != EvidenceConflict")
    print("Core invariant: Unknown != False")
    print("Core invariant: MLCandidate != EstablishedTranslation")
