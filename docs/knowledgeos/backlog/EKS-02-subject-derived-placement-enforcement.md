# EKS-02 — Subject-Derived Placement, Machine-Enforceable

**Status:** BACKLOG — recorded on the PO/ARB act 2026-08-16. **Not commissioned; starting requires a human authorization act.**
**Class:** EKS architecture capability (knowledge governance) · **Source:** the 2026-08-16 placement drift (100+ misplaced documents over three days) and its same-hour recurrence.

## Problem

Documentation placement is ruled (`doc-placement.php`, exit 0 for every misplaced case) but not enforced. The maturity picture:

```
Placement policy        ✅ exists
Placement derivation    ✅ exists
Human awareness         ✅ reinforced
Mechanical enforcement  ❌ absent — "the rule exists, but the workflow does not
                           make it hard enough to violate"
```

Demonstrated twice on 2026-08-16: the session that *detected* the drift had itself written ~20 misplaced documents that morning, and the next Architecture lane misplaced four more within an hour of the convention's registration.

## Requirement (candidate — what a solution must satisfy)

> **Knowledge placement must be derived from knowledge subject / product / domain identity — never inherited from the producing session, predecessor path, terminal, or convenience.** The subject is determined first; the location follows.

Machine-readable derivation target:

```
subject = knowledgeos · artifact_type = review  →  canonical_space = docs/knowledgeos/reviews
```

Candidate domain concept: **Knowledge Space / Knowledge Scope** — a document carries a business subject (`Knowledge subject → Product/Domain → Knowledge Space → Artifact type → Lifecycle/governance`) and placement is derived from that identity.

## Scope when commissioned

Architecture-first: model the Knowledge Space concept and determine the enforcement point (a check at write time? a lint like `knowledge-lint`? a startup-injected manifest per EKS-01?) — honoring P-5's boundary: a *factual* check ("the derived location matches the actual location — yes/no") may gate only via a separate governance decision on objective, auditable criteria.

## Not in scope

No enforcement built by this ticket · no move of historical documents (that is EKS-03's question) · no change to the placement registry semantics without an ADR amendment.

## Dependencies / relations

EKS-01 (this is one instance of the distribution problem — a placement enforcer that sessions never discover would repeat the failure) · `documentation-placement.yaml` + `ADR_20260801_1740` (the ruling mechanism to extend, never duplicate — ES-005.4) · the fix-forward convention `docs/knowledgeos/reviews/README.md`.

---

## Related routing (2026-08-17) — measurement provenance

The PO/ARB routed a **measurement-provenance issue** to future KES governance work when accepting Verification #3 of `KOS-ARCH-BASELINE-003`. It is recorded here as adjacent subject matter, **not merged into this ticket and not commissioned**:

> **A figure stated without its method cannot be reproduced — and a figure handed onward as a check criterion inherits authority it has not earned.**

Evidence: F-1 (a diff count that git contradicts; method-dependent) and F-2 (*"6 of 37"* not reproducible; a plain criterion yields 14 of 38; the selection rule unstated) — both MINOR, both classified as **knowledge-integrity findings, not architecture defects**. Whether this becomes its own EKS ticket or extends an existing one is a governance decision that has not been taken. Registration: `../reviews/2026-08-17-KOS-ARCH-BASELINE-003-verification-3-acceptance-registration.md`.
