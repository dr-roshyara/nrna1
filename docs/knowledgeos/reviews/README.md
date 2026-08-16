# KnowledgeOS Reviews — placement convention and historical pointer

**Registered by:** Governance, on the delivered Human/PO/ARB act · 2026-08-16

## 1 · The human act, verbatim

> **"Approve the fix-forward convention (1+2)."**

— PO/ARB, 2026-08-16, following the Governance verification that ~100+ KnowledgeOS documents (66 `KOS-*`-named plus the 08-16 attribution/assurance/governance set) had accumulated in `docs/publicdigit/reviews/` although the placement rule derives `docs/knowledgeos` for them (`scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → exit 0, ruled).

## 2 · The convention now in force

**From 2026-08-16 onward, KnowledgeOS work products — commissions, registrations, reviews, verification reports, decision requests — are placed under `docs/knowledgeos/reviews/`.** Placement is **derived, never chosen**: run `php scripts/doc-placement.php` with the document's scope and domain; do not inherit a root from a predecessor document's path or a workflow record's `tokenRef`.

*Why the drift happened (recorded so it is not repeated):* `docs/publicdigit/reviews/` was the repository's only `reviews/` folder when the KnowledgeOS governance work began on 2026-08-14; each session then inherited the previous session's paths via `tokenRef` references — consistency-by-repeated-configuration instead of derivation. The 08-16 session that verified the drift had itself written ~20 documents to the wrong root the same day.

## 3 · Historical pointer — where the earlier KnowledgeOS documents live

**KnowledgeOS documents dated 2026-08-02 … 2026-08-16 remain in `docs/publicdigit/reviews/` and are NOT moved.** Reason: 39 `tokenRef` references inside the authoritative workflow records (`.claude/runtime/workflow/*.json`) hard-code those paths; moving the files would break the records' evidence chain. Look there for:

- `*KOS-*` — all KnowledgeOS work-item documents (`KOS-AI-ORCH-001`, `KOS-OQ-001`, `KOS-SESSION-DISCOVERY-001`, `KOS-EXEC-TOPOLOGY-001`, `KOS-GOV-ATTRIBUTION-001`, `KOS-ROLE-IDENTITY-001`, `KOS-ARCH-BASELINE-001`, `KOS-LCOM4-CONTRACT-001`, `KOS-CONTRACT-NEUTRALITY-001`, `KOS-GOV-GAPS-*`, `KOS-ATTR-ARCH-001`)
- the 2026-08-16 governance-gap verification chain, the P-1…P-6 decision registrations, the Business Assurance Model, and their approval registrations

A later governed cleanup MAY move them with a recorded path mapping — that requires its own Human/PO/ARB authorization and is **not** authorized by the 1+2 act.

## 4 · Scope of this convention

This convention governs **placement only**. It changes no decision, no record, no grant, and no content of any existing document.
