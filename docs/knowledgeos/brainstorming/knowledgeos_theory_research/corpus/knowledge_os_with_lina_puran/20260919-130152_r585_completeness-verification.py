"""
KnowledgeOS R585 — Completeness Verification

Finite executable reference model.

Core result:
A completeness claim becomes ESTABLISHED only when:
1. coverage is established,
2. soundness is established, and
3. scope, target and contract match.

Counterexample search strengthens assurance but cannot, by itself, prove
completeness. This is finite model evidence, not a universal theorem.
"""

from dataclasses import dataclass
from itertools import product
from collections import defaultdict, deque

@dataclass(frozen=True)
class VerificationEvidence:
    coverage: bool
    soundness: bool
    scope_match: bool
    target_match: bool
    contract_match: bool
    counterexample_search: bool

@dataclass(frozen=True)
class CompletenessAssessment:
    basis_mode: str
    evidence: VerificationEvidence

def completeness_result(a):
    e = a.evidence
    if not (e.scope_match and e.target_match and e.contract_match):
        return "UNKNOWN"
    if e.coverage and e.soundness:
        return "ESTABLISHED"
    if e.coverage or e.soundness or e.counterexample_search:
        return "CONDITIONAL"
    return "UNKNOWN"

@dataclass(frozen=True)
class Edge:
    source: str
    target: str
    material: bool = True
    established: bool = True
    propagates: bool = True

def closure(changed, edges):
    adj = defaultdict(list)
    for e in edges:
        if e.established and e.material and e.propagates:
            adj[e.source].append(e.target)
    seen = set(changed)
    q = deque(changed)
    while q:
        x = q.popleft()
        for y in adj[x]:
            if y not in seen:
                seen.add(y)
                q.append(y)
    return seen - set(changed)

def impact(changed, target, edges, comp):
    if target in closure(changed, edges):
        return "AFFECTED"
    return "NOT_AFFECTED" if completeness_result(comp) == "ESTABLISHED" else "UNKNOWN"

full = CompletenessAssessment(
    "EXHAUSTIVE_MODEL",
    VerificationEvidence(True, True, True, True, True, True)
)
assert completeness_result(full) == "ESTABLISHED"

coverage_only = CompletenessAssessment(
    "VERIFIED_GENERATOR",
    VerificationEvidence(True, False, True, True, True, True)
)
assert completeness_result(coverage_only) == "CONDITIONAL"

soundness_only = CompletenessAssessment(
    "VERIFIED_GENERATOR",
    VerificationEvidence(False, True, True, True, True, True)
)
assert completeness_result(soundness_only) == "CONDITIONAL"

wrong_scope = CompletenessAssessment(
    "CLOSED_WORLD",
    VerificationEvidence(True, True, False, True, True, True)
)
assert completeness_result(wrong_scope) == "UNKNOWN"

counterexample_only = CompletenessAssessment(
    "ADVERSARIAL_SEARCH",
    VerificationEvidence(False, False, True, True, True, True)
)
assert completeness_result(counterexample_only) == "CONDITIONAL"

edges = [Edge("E1", "C1")]
assert impact({"E1"}, "C2", edges, full) == "NOT_AFFECTED"
assert impact({"E1"}, "C1", edges, full) == "AFFECTED"

for bits in product((False, True), repeat=6):
    ev = VerificationEvidence(*bits)
    a = CompletenessAssessment("TEST", ev)
    r = completeness_result(a)
    coverage, soundness, scope, target, contract, counterexample = bits
    if not (scope and target and contract):
        assert r == "UNKNOWN"
    elif coverage and soundness:
        assert r == "ESTABLISHED"
    elif coverage or soundness or counterexample:
        assert r == "CONDITIONAL"
    else:
        assert r == "UNKNOWN"

print("R585 Completeness Verification: PASS")
print("Coverage + soundness + scope/target/contract checks: PASS")
print("Counterexample search is not treated as a completeness proof: PASS")
print("Exhaustive 64-combination evidence-state check: PASS")
print("Evidence class: finite executable reference model")
print("Not a universal theorem for arbitrary real-world dependency systems.")
print("Saved executable reference: /mnt/data/knowledgeos_r585_completeness_verification.py")
