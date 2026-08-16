# EKS-03 — Governed Relocation of Historical KnowledgeOS Documents

**Status:** BACKLOG — recorded on the PO/ARB act 2026-08-16. **Not commissioned; the PO's fix-forward approval (1+2) explicitly did NOT authorize this — it is option 3, deferred.**
**Class:** governed cleanup (knowledge governance) · **Source:** the 2026-08-16 placement verification.

## Problem

100+ KnowledgeOS-subject documents (66 `KOS-*`-named plus the 08-16 governance set, dated 2026-08-02 … 2026-08-16) live in `docs/publicdigit/reviews/` although the placement rule derives `docs/knowledgeos`. They cannot simply be moved: **39 `tokenRef` references inside the authoritative workflow records (`.claude/runtime/workflow/*.json`) hard-code the current paths**, and those records are the evidence chain.

Standing principle (ARB, 2026-08-16): **historical location is evidence; canonical future location is architecture.**

## What this ticket would do, if commissioned

A governed move with provenance preserved:

1. enumerate every misplaced document and every reference to it (workflow records · cross-references · commits);
2. produce a **recorded path mapping** (old → new), registered as a governance artifact;
3. move the files; leave the mapping where the old paths were referenced (the workflow records themselves are append-only and gitignored — the mapping document, not an edit, carries the correction);
4. independent verification that no reference resolves to nothing.

## Not in scope

No content change to any document · no edit to any workflow record · no history rewrite · does not block or precede EKS-01/EKS-02.

## Recommendation carried from the incident

**Low urgency.** The fix-forward convention stops the growth; the README's historical pointer makes the old location findable. The move is worth doing only when the reference-mapping cost is paid deliberately — or it may be subsumed by EKS-02's Knowledge Space work if that produces a better mechanism for location-independent references.

## Dependencies / relations

Fix-forward convention (`docs/knowledgeos/reviews/README.md`) · the four **unreferenced** 2026-08-16 Architecture documents are NOT this ticket — they are cheap to move now and routed to the owning Architecture lane via `G-KOS-ATTRARCH-DESIGN-AMD2` · EKS-02.
