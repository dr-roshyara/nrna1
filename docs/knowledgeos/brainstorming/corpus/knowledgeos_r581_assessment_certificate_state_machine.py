"""
KnowledgeOS R581 — Formal Assessment × Certificate Lifecycle State Machine

Finite executable reference model.
This is finite model-checking evidence, not a universal theorem for arbitrary
certificate systems.
"""
from itertools import product

A = ("PASS", "FAIL", "UNKNOWN", "CONDITIONAL")
L = ("CURRENT", "REVALIDATION_REQUIRED", "SUPERSEDED", "EXPIRED")

def transition(assessment, lifecycle, event, *, conditional_current_allowed=False):
    if lifecycle in ("SUPERSEDED", "EXPIRED"):
        return None
    if event == "MATERIAL_REVISION":
        if lifecycle in ("CURRENT", "REVALIDATION_REQUIRED"):
            return (assessment, "REVALIDATION_REQUIRED")
        return None
    if event == "NONMATERIAL_REVISION":
        return (assessment, lifecycle)
    if event == "EXPIRE":
        return (assessment, "EXPIRED")
    if event == "REVALIDATE_PASS" and lifecycle == "REVALIDATION_REQUIRED":
        return ("PASS", "CURRENT")
    if event == "REVALIDATE_FAIL" and lifecycle == "REVALIDATION_REQUIRED":
        return ("FAIL", "SUPERSEDED")
    if event == "REVALIDATE_UNKNOWN" and lifecycle == "REVALIDATION_REQUIRED":
        return ("UNKNOWN", "REVALIDATION_REQUIRED")
    if event == "REVALIDATE_CONDITIONAL" and lifecycle == "REVALIDATION_REQUIRED":
        return (("CONDITIONAL", "CURRENT")
                if conditional_current_allowed
                else ("CONDITIONAL", "REVALIDATION_REQUIRED"))
    # New assessment creates a new certificate; it does not mutate this one.
    if event == "NEW_ASSESSMENT":
        return None
    return None

events = (
    "MATERIAL_REVISION", "NONMATERIAL_REVISION", "REVALIDATE_PASS",
    "REVALIDATE_FAIL", "REVALIDATE_UNKNOWN", "REVALIDATE_CONDITIONAL",
    "EXPIRE", "NEW_ASSESSMENT"
)

# Core transition checks
assert transition("PASS", "CURRENT", "MATERIAL_REVISION") == ("PASS", "REVALIDATION_REQUIRED")
assert transition("PASS", "REVALIDATION_REQUIRED", "REVALIDATE_PASS") == ("PASS", "CURRENT")
assert transition("PASS", "REVALIDATION_REQUIRED", "REVALIDATE_FAIL") == ("FAIL", "SUPERSEDED")
assert transition("UNKNOWN", "REVALIDATION_REQUIRED", "REVALIDATE_UNKNOWN") == ("UNKNOWN", "REVALIDATION_REQUIRED")
assert transition("UNKNOWN", "CURRENT", "EXPIRE") == ("UNKNOWN", "EXPIRED")
assert transition("FAIL", "SUPERSEDED", "REVALIDATE_PASS") is None
assert transition("PASS", "SUPERSEDED", "NEW_ASSESSMENT") is None
assert transition("CONDITIONAL", "REVALIDATION_REQUIRED", "REVALIDATE_CONDITIONAL",
                  conditional_current_allowed=False) == ("CONDITIONAL", "REVALIDATION_REQUIRED")
assert transition("CONDITIONAL", "REVALIDATION_REQUIRED", "REVALIDATE_CONDITIONAL",
                  conditional_current_allowed=True) == ("CONDITIONAL", "CURRENT")

# Exhaustive finite model check.
witnesses = set()
for a, l in product(A, L):
    for event in events:
        for flag in (False, True):
            nxt = transition(a, l, event, conditional_current_allowed=flag)
            if nxt is not None:
                witnesses.add((a, l, event, flag, nxt))

# UNKNOWN may become FAIL only through an explicit FAIL result from revalidation.
for a, l, event, flag, nxt in witnesses:
    if a == "UNKNOWN" and nxt[0] == "FAIL":
        assert event == "REVALIDATE_FAIL"

# Terminal lifecycle states cannot be revived by revalidation.
for a in A:
    for event in ("REVALIDATE_PASS", "REVALIDATE_FAIL",
                  "REVALIDATE_UNKNOWN", "REVALIDATE_CONDITIONAL"):
        assert transition(a, "SUPERSEDED", event) is None
        assert transition(a, "EXPIRED", event) is None

# Revision marking is idempotent and does not alter the historical assessment.
for a in A:
    assert transition(a, "CURRENT", "MATERIAL_REVISION")[0] == a
    assert transition(a, "REVALIDATION_REQUIRED", "MATERIAL_REVISION") == (
        a, "REVALIDATION_REQUIRED"
    )

print("R581 Assessment × Certificate Lifecycle State Machine: PASS")
print(f"Finite state pairs checked: {len(A) * len(L)}")
print(f"Admissible transition witnesses checked: {len(witnesses)}")
print("Core safety properties: PASS")
print("Evidence class: finite executable reference model")
print("Not a universal theorem for arbitrary certificate systems.")
print("Saved executable reference: /mnt/data/knowledgeos_r581_assessment_certificate_state_machine.py")
