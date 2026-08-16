# Registration — Knowledge-Governance Architecture Requirement: Subject-Derived Placement

**Registered by:** Governance, on the delivered Human/PO/ARB (Principal Architect) act · 2026-08-16
**Status of the requirement:** **RECORDED as a knowledge-governance architecture requirement and candidate EKS capability. Nothing is commissioned, designed, or implemented by this registration.**

---

## 1 · The accepted finding

The ARB **accepted the placement finding** and the fix-forward remediation (1+2), and elevated the incident from documentation hygiene to its real class:

> **This is not merely a folder problem; it is a product-boundary and knowledge-ownership problem.** The three documentation roots are physical representations of knowledge ownership and product boundaries. The failure mechanism — *path inheritance became a substitute for architectural placement derivation* — is the same pattern seen elsewhere: a locally convenient precedent becomes an implicit rule.

## 2 · The requirement, registered for the future EKS architecture

> **Knowledge placement must be derived from knowledge subject / product / domain identity, never inherited from producer or session context.**

The derivation the EKS should eventually make first-class and machine-readable:

```
Knowledge subject → Product/Domain → Knowledge Space → Artifact type → Canonical location

e.g.   subject = knowledgeos · artifact_type = review  →  canonical_space = docs/knowledgeos/reviews
```

**The subject is determined first.** A *Knowledge Space / Knowledge Scope* concept is the candidate domain expression of this — noted as a concept candidate for the EKS target architecture, not modeled here.

## 3 · The maturity picture the incident establishes

| Capability | State |
|---|---|
| Placement policy | ✅ exists (ADR + registry + `doc-placement.php`) |
| Placement derivation | ✅ exists (ruled; exit 0 for the misplaced class) |
| Human awareness | ✅ reinforced (convention README, this incident) |
| **Mechanical enforcement** | ❌ **weak/absent — the rule exists, but the workflow does not make it hard enough to violate.** Demonstrated by the detecting session itself misplacing ~20 documents the same day. |

The missing row is registered as a **candidate EKS architecture capability** — to be taken up through the normal governed path (evidence → decision → architecture), **not** as another ad-hoc documentation rule.

## 4 · What stands unchanged

The fix-forward convention (README, this directory) · the historical files in `docs/publicdigit/reviews/` as provenance-bearing evidence · the placement policy and registry as the ruling mechanism · no new work item, no commission, no enforcement built.

---

**Traceability:** ARB acceptance act 2026-08-16 (this message's verdict: fix-forward ✅ · historical-stay ✅ · new-root ✅ · requirement registered) · convention `docs/knowledgeos/reviews/README.md` (`70fd2b35`, wording ARB-strengthened same day) · drift verification (66 `KOS-*` files; 19/22/19 across 08-14/15/16; 39 `tokenRef` hard references) · placement policy `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` · `documentation-placement.yaml` · G-5 (the same failure shape in the role topology) · ES-001.3 (the detection duty under which the drift was surfaced)
