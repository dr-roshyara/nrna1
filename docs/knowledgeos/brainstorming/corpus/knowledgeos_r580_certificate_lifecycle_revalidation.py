
from dataclasses import dataclass, field
from enum import Enum
from typing import FrozenSet, Iterable


class AssessmentStatus(str, Enum):
    PASS = "PASS"
    FAIL = "FAIL"
    UNKNOWN = "UNKNOWN"
    UNDEFINED = "UNDEFINED"


class Lifecycle(str, Enum):
    CURRENT = "CURRENT"
    REVALIDATION_REQUIRED = "REVALIDATION_REQUIRED"
    SUPERSEDED = "SUPERSEDED"
    EXPIRED = "EXPIRED"


@dataclass(frozen=True)
class Evidence:
    id: str
    version: int
    material_factors: FrozenSet[str] = frozenset()


@dataclass(frozen=True)
class Certificate:
    id: str
    assessment_status: AssessmentStatus
    lifecycle: Lifecycle
    evidence_versions: dict[str, int]
    dependencies: FrozenSet[str]


@dataclass
class EpistemicState:
    determination: str
    history: list[str] = field(default_factory=list)


def append_revision(state: EpistemicState, event: str, new_determination: str):
    """Revision changes current state while preserving append-only history."""
    state.history.append(event)
    state.determination = new_determination


def affected_by_revision(cert: Certificate, evidence: Evidence) -> bool:
    return evidence.id in cert.dependencies


def mark_revalidation(cert: Certificate, evidence: Evidence) -> Certificate:
    if affected_by_revision(cert, evidence):
        return Certificate(
            cert.id,
            cert.assessment_status,
            Lifecycle.REVALIDATION_REQUIRED,
            dict(cert.evidence_versions),
            cert.dependencies,
        )
    return cert


def revalidate(cert: Certificate, evidence: Evidence, result: AssessmentStatus) -> Certificate:
    versions = dict(cert.evidence_versions)
    versions[evidence.id] = evidence.version
    lifecycle = Lifecycle.CURRENT if result == AssessmentStatus.PASS else Lifecycle.SUPERSEDED
    return Certificate(
        cert.id, result, lifecycle, versions, cert.dependencies
    )


def selective_revalidation(certificates: Iterable[Certificate],
                           revised: Evidence) -> list[Certificate]:
    return [mark_revalidation(c, revised) for c in certificates]


def dependency_invalidation_set(certificates: Iterable[Certificate],
                                revised: Evidence) -> FrozenSet[str]:
    return frozenset(
        c.id for c in certificates if affected_by_revision(c, revised)
    )


def certificate_is_stale(cert: Certificate) -> bool:
    return cert.lifecycle == Lifecycle.REVALIDATION_REQUIRED


# 1. Material revision marks only dependent certificate.
e1 = Evidence("E1", 1)
e2 = Evidence("E2", 1)
c1 = Certificate("C1", AssessmentStatus.PASS, Lifecycle.CURRENT, {"E1": 1}, frozenset({"E1"}))
c2 = Certificate("C2", AssessmentStatus.PASS, Lifecycle.CURRENT, {"E2": 1}, frozenset({"E2"}))
out = selective_revalidation([c1, c2], Evidence("E1", 2))
assert out[0].lifecycle == Lifecycle.REVALIDATION_REQUIRED
assert out[1].lifecycle == Lifecycle.CURRENT

# 2. Affected does not imply invalid.
assert out[0].assessment_status == AssessmentStatus.PASS

# 3. Revalidation can restore CURRENT + PASS.
c1r = revalidate(out[0], Evidence("E1", 2), AssessmentStatus.PASS)
assert c1r.lifecycle == Lifecycle.CURRENT
assert c1r.assessment_status == AssessmentStatus.PASS
assert c1r.evidence_versions["E1"] == 2

# 4. Revalidation can supersede after failure; history is not erased.
c1f = revalidate(out[0], Evidence("E1", 3), AssessmentStatus.FAIL)
assert c1f.lifecycle == Lifecycle.SUPERSEDED
assert c1f.assessment_status == AssessmentStatus.FAIL

# 5. Selective closure: only reachable/dependent certificates are stale.
assert dependency_invalidation_set([c1, c2], Evidence("E1", 2)) == frozenset({"C1"})

# 6. Revision preserves epistemic history.
state = EpistemicState("SUPPORTED", ["E1 acquired", "C1 issued"])
append_revision(state, "E1 version 2 acquired", "UNRESOLVED")
assert state.history == ["E1 acquired", "C1 issued", "E1 version 2 acquired"]
assert state.determination == "UNRESOLVED"

# 7. Expiration is a lifecycle event, not a truth value.
expired = Certificate("C3", AssessmentStatus.PASS, Lifecycle.EXPIRED, {"E2": 1}, frozenset({"E2"}))
assert expired.assessment_status == AssessmentStatus.PASS
assert expired.lifecycle == Lifecycle.EXPIRED

# 8. Unknown revalidation must not silently become FAIL.
c_unknown = revalidate(out[0], Evidence("E1", 4), AssessmentStatus.UNKNOWN)
assert c_unknown.assessment_status == AssessmentStatus.UNKNOWN
assert c_unknown.lifecycle == Lifecycle.SUPERSEDED

# 9. Idempotent marking: already stale remains stale.
stale_again = mark_revalidation(out[0], Evidence("E1", 2))
assert stale_again.lifecycle == Lifecycle.REVALIDATION_REQUIRED

# 10. Unrelated revision cannot invalidate a certificate.
unrelated = mark_revalidation(c1, Evidence("E99", 2))
assert unrelated.lifecycle == Lifecycle.CURRENT

print("R580 Certificate Lifecycle & Selective Revalidation tests: 10/10 passed")
print("1 selective dependency impact: PASS")
print("2 affected != invalid: PASS")
print("3 successful revalidation restores CURRENT/PASS: PASS")
print("4 failed revalidation supersedes certificate: PASS")
print("5 selective dependency closure: PASS")
print("6 append-only epistemic history: PASS")
print("7 expiration != truth failure: PASS")
print("8 UNKNOWN preserved during revalidation: PASS")
print("9 revalidation marking idempotence: PASS")
print("10 unrelated revision leaves certificate current: PASS")
print("Evidence class: finite executable reference model")
print("Not a universal theorem for arbitrary certificate systems.")
