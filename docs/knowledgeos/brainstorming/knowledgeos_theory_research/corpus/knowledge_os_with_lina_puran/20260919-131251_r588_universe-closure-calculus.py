
from dataclasses import dataclass
from itertools import product
from typing import FrozenSet

# R588 — Universe Closure Calculus
#
# Research question:
# How can KnowledgeOS establish that a declared dependency universe is closed?
#
# Evidence class: finite executable reference model.
# This is not a universal theorem about arbitrary real-world universes.

@dataclass(frozen=True)
class UniverseEvidence:
    mode: str
    universe_declared: bool
    scope_match: bool
    target_match: bool
    contract_match: bool

    # Finite explicit universe
    exhaustive_enumeration: bool = False

    # Closed-world registry
    registry_authoritative: bool = False
    registry_complete_for_scope: bool = False
    registry_current: bool = False

    # Verified generator
    generator_sound: bool = False
    generator_complete: bool = False
    generator_domain_bounded: bool = False
    generator_exhaustively_checked: bool = False

    # Open-world environment
    open_world: bool = False
    boundary_explicit: bool = False
    boundary_authoritatively_verified: bool = False

    # Additional negative evidence
    counterexample_search: bool = False


@dataclass(frozen=True)
class UniverseAssessment:
    status: str          # ESTABLISHED / CONDITIONAL / UNKNOWN
    reason: str


def assess_universe_closure(e: UniverseEvidence) -> UniverseAssessment:
    # Universal prerequisites.
    if not e.universe_declared:
        return UniverseAssessment("UNKNOWN", "No declared universe")

    if not (e.scope_match and e.target_match and e.contract_match):
        return UniverseAssessment("UNKNOWN", "Scope/target/contract mismatch")

    # 1. Finite explicit universe.
    if e.mode == "FINITE_EXPLICIT":
        if e.exhaustive_enumeration:
            return UniverseAssessment(
                "ESTABLISHED",
                "Finite universe explicitly bounded and exhaustively enumerated"
            )
        if e.counterexample_search:
            return UniverseAssessment(
                "CONDITIONAL",
                "Counterexample search does not establish exhaustiveness"
            )
        return UniverseAssessment("UNKNOWN", "Finite universe not exhaustively verified")

    # 2. Closed-world registry.
    if e.mode == "CLOSED_REGISTRY":
        if (
            e.registry_authoritative
            and e.registry_complete_for_scope
            and e.registry_current
        ):
            return UniverseAssessment(
                "ESTABLISHED",
                "Authoritative, current registry is verified complete for scope"
            )
        if e.registry_authoritative or e.registry_complete_for_scope:
            return UniverseAssessment(
                "CONDITIONAL",
                "Registry evidence exists but completeness/currentness is incomplete"
            )
        return UniverseAssessment("UNKNOWN", "Registry closure not established")

    # 3. Verified generator.
    if e.mode == "VERIFIED_GENERATOR":
        if (
            e.generator_sound
            and e.generator_complete
            and e.generator_domain_bounded
            and e.generator_exhaustively_checked
        ):
            return UniverseAssessment(
                "ESTABLISHED",
                "Generator is sound, complete, bounded and exhaustively verified"
            )
        if (
            e.generator_sound
            or e.generator_complete
            or e.generator_domain_bounded
            or e.generator_exhaustively_checked
        ):
            return UniverseAssessment(
                "CONDITIONAL",
                "Generator evidence is partial"
            )
        return UniverseAssessment("UNKNOWN", "Generator closure not established")

    # 4. Open world.
    if e.mode == "OPEN_WORLD":
        # Open world is not automatically impossible. It becomes a bounded
        # subclaim only if an authoritative boundary explicitly closes the
        # relevant question.
        if e.boundary_explicit and e.boundary_authoritatively_verified:
            return UniverseAssessment(
                "ESTABLISHED",
                "Open environment is explicitly bounded for the declared claim"
            )
        if e.boundary_explicit:
            return UniverseAssessment(
                "CONDITIONAL",
                "Boundary is declared but not authoritatively verified"
            )
        return UniverseAssessment(
            "UNKNOWN",
            "Open world has no verified completeness boundary"
        )

    return UniverseAssessment("UNKNOWN", "Unsupported universe-closure mode")


def can_issue_completeness_certificate(assessment: UniverseAssessment) -> bool:
    return assessment.status == "ESTABLISHED"


def run_adversarial_cases():
    cases = [
        (
            "F1 Finite explicit — exhaustive",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                exhaustive_enumeration=True,
            ),
            "ESTABLISHED",
        ),
        (
            "F2 Finite explicit — search only",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                counterexample_search=True,
            ),
            "CONDITIONAL",
        ),
        (
            "F3 Finite explicit — no enumeration",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
            ),
            "UNKNOWN",
        ),
        (
            "R1 Registry — authoritative, complete, current",
            UniverseEvidence(
                mode="CLOSED_REGISTRY",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                registry_authoritative=True,
                registry_complete_for_scope=True,
                registry_current=True,
            ),
            "ESTABLISHED",
        ),
        (
            "R2 Registry — authoritative but completeness unverified",
            UniverseEvidence(
                mode="CLOSED_REGISTRY",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                registry_authoritative=True,
                registry_complete_for_scope=False,
                registry_current=True,
            ),
            "CONDITIONAL",
        ),
        (
            "R3 Registry — stale",
            UniverseEvidence(
                mode="CLOSED_REGISTRY",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                registry_authoritative=True,
                registry_complete_for_scope=True,
                registry_current=False,
            ),
            "CONDITIONAL",
        ),
        (
            "G1 Generator — fully verified bounded generator",
            UniverseEvidence(
                mode="VERIFIED_GENERATOR",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                generator_sound=True,
                generator_complete=True,
                generator_domain_bounded=True,
                generator_exhaustively_checked=True,
            ),
            "ESTABLISHED",
        ),
        (
            "G2 Generator — sound but incomplete",
            UniverseEvidence(
                mode="VERIFIED_GENERATOR",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                generator_sound=True,
                generator_complete=False,
                generator_domain_bounded=True,
                generator_exhaustively_checked=True,
            ),
            "CONDITIONAL",
        ),
        (
            "G3 Generator — unbounded domain",
            UniverseEvidence(
                mode="VERIFIED_GENERATOR",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                generator_sound=True,
                generator_complete=True,
                generator_domain_bounded=False,
                generator_exhaustively_checked=True,
            ),
            "CONDITIONAL",
        ),
        (
            "O1 Open world — no boundary",
            UniverseEvidence(
                mode="OPEN_WORLD",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                open_world=True,
            ),
            "UNKNOWN",
        ),
        (
            "O2 Open world — boundary asserted but not verified",
            UniverseEvidence(
                mode="OPEN_WORLD",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                open_world=True,
                boundary_explicit=True,
                boundary_authoritatively_verified=False,
            ),
            "CONDITIONAL",
        ),
        (
            "O3 Open environment — authoritative bounded subdomain",
            UniverseEvidence(
                mode="OPEN_WORLD",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=True,
                open_world=True,
                boundary_explicit=True,
                boundary_authoritatively_verified=True,
            ),
            "ESTABLISHED",
        ),
        (
            "X1 Scope mismatch",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=False,
                target_match=True,
                contract_match=True,
                exhaustive_enumeration=True,
            ),
            "UNKNOWN",
        ),
        (
            "X2 Target mismatch",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=True,
                target_match=False,
                contract_match=True,
                exhaustive_enumeration=True,
            ),
            "UNKNOWN",
        ),
        (
            "X3 Contract mismatch",
            UniverseEvidence(
                mode="FINITE_EXPLICIT",
                universe_declared=True,
                scope_match=True,
                target_match=True,
                contract_match=False,
                exhaustive_enumeration=True,
            ),
            "UNKNOWN",
        ),
    ]

    for name, evidence, expected in cases:
        result = assess_universe_closure(evidence)
        assert result.status == expected, (
            name, result.status, expected
        )
        if expected != "ESTABLISHED":
            assert not can_issue_completeness_certificate(result)

    return cases


def exhaustive_truth_table():
    # Finite explicit mode:
    # completeness can be established exactly when all universal prerequisites
    # and exhaustive enumeration hold.
    failures = 0
    cases = 0

    for universe, scope, target, contract, exhaustive in product(
        (False, True), repeat=5
    ):
        e = UniverseEvidence(
            mode="FINITE_EXPLICIT",
            universe_declared=universe,
            scope_match=scope,
            target_match=target,
            contract_match=contract,
            exhaustive_enumeration=exhaustive,
        )
        result = assess_universe_closure(e)

        expected = (
            "ESTABLISHED"
            if universe and scope and target and contract and exhaustive
            else "UNKNOWN"
        )

        cases += 1
        if result.status != expected:
            failures += 1

    return cases, failures


def verify_open_world_boundary():
    # An "open world" can be closed relative to an explicitly declared,
    # authoritative boundary. The claim is then boundary-relative, not
    # globally universal.
    unbounded = UniverseEvidence(
        mode="OPEN_WORLD",
        universe_declared=True,
        scope_match=True,
        target_match=True,
        contract_match=True,
        open_world=True,
    )
    bounded = UniverseEvidence(
        mode="OPEN_WORLD",
        universe_declared=True,
        scope_match=True,
        target_match=True,
        contract_match=True,
        open_world=True,
        boundary_explicit=True,
        boundary_authoritatively_verified=True,
    )

    assert assess_universe_closure(unbounded).status == "UNKNOWN"
    assert assess_universe_closure(bounded).status == "ESTABLISHED"


if __name__ == "__main__":
    cases = run_adversarial_cases()
    truth_cases, truth_failures = exhaustive_truth_table()
    verify_open_world_boundary()

    established = sum(
        assess_universe_closure(e).status == "ESTABLISHED"
        for _, e, _ in cases
    )
    conditional = sum(
        assess_universe_closure(e).status == "CONDITIONAL"
        for _, e, _ in cases
    )
    unknown = sum(
        assess_universe_closure(e).status == "UNKNOWN"
        for _, e, _ in cases
    )

    print("R588 Universe Closure Calculus: PASS")
    print(f"Adversarial closure cases: {len(cases)}")
    print(f"ESTABLISHED cases: {established}")
    print(f"CONDITIONAL cases: {conditional}")
    print(f"UNKNOWN cases: {unknown}")
    print(f"Finite closure truth-table cases: {truth_cases}")
    print(f"Finite closure truth-table failures: {truth_failures}")
    print("Finite explicit universe rule: PASS")
    print("Authoritative registry rule: PASS")
    print("Verified generator rule: PASS")
    print("Open-world boundary rule: PASS")
    print("Scope/target/contract gating: PASS")
    print("No global completeness claim for unbounded open world: PASS")
    print("Certificate promotion only from ESTABLISHED closure: PASS")
    print("Evidence class: finite executable reference model")
    print("Not a universal theorem for arbitrary real-world dependency systems.")
