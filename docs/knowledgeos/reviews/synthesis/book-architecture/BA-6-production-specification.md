# BA-6 · Production Specification (DESIGN, GN-33 — executable only after production authorization)

> ✅ **RATIFIED (GN-34, HPA, 2026-08-28)** — subordinate to the ratified Final Architecture (GN-31). Production remains unauthorized (next gate: BOOK PRODUCTION AUTHORIZATION).

1. **Gate sequence** (unchanged from BA-0): this design → BOOK ARCHITECTURE RATIFICATION →
   BOOK PRODUCTION AUTHORIZATION → production → book review.
2. **Production order:** Part III first (authority chapters, hardest constraints), then Part I
   (narrative, longest), then II, then IV. Rationale: Part III fixes the terminology/authority
   surface everything else must conform to; writing narrative first risks backward pressure on
   architecture prose. IV.4/IV.5 last (they aggregate).
3. **Chapter workflow:** allocate (BA-2 row) → draft chapter.md → build evidence-map/claims →
   unresolved.md → run BA-5 §1 gate → mark chapter DONE-PENDING-REVIEW. No chapter skips the gate;
   no two chapters share a working draft.
4. **Folder creation:** the `book/` production tree (GN-15/16 layout) is created at production
   start, not before, and its location is decided by the production authorization (doc-placement
   discipline applies).
5. **Change control:** production may NOT edit any synthesis/FA artifact. A discovered defect in
   an upstream artifact is a finding raised to governance, never an inline fix (the standing
   chain: DISCOVER → CLASSIFY → TRACE → REPORT → DISPOSITION).
6. **Estimated shape:** 25 chapters × (prose + 3 companion artifacts); Part III ~10 chapters is
   the authority core; the book-level provenance-index and the IV.5 methods chapter close.
