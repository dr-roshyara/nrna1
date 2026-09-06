# BA-4 · Provenance & Authority Architecture (DESIGN, GN-33)

> ✅ **RATIFIED (GN-34, HPA, 2026-08-28)** — subordinate to the ratified Final Architecture (GN-31). Production remains unauthorized (next gate: BOOK PRODUCTION AUTHORIZATION).

## 1 · Claim model
Every substantive sentence is one of: [FA-claim] (cites the ratified FA/v0.2 element) ·
[H-claim] (cites corpus file/registry with date) · [M-claim] (cites a GN act) · [P-claim]
(prospective, marked as outlook). Grades ([E]/[IN]/[RC]/[H]/[R]/[U] and TESTED/READ/…) are surfaced
in the evidence-map, and inline wherever a reader could otherwise over-read strength (GN-14).

## 2 · Authority/evidence separation (structural rule from BA-0 §9)
"is/must"-claims about the architecture → only in Part III → only [FA-claim].
"was/became/tried/failed"-claims → [H-claim] with provenance.
GN-01: synthesis echoes carry zero weight. Copies never corroborate. Statistics dated (GN-03).

## 3 · The four chapter artifacts and their relationships (defined now, created at production)
```
<chapter>/
  chapter.md        the prose; every claim typed per §1
  evidence-map.md   claim → source → grade table (the provenance ledger of the chapter)
  claims.md         the chapter's assertion inventory + terminology-conformance table
  unresolved.md     OQ links + chapter-local open points ("none" must be explicit)
```
Relationships: claims.md rows ⊇ chapter.md substantive claims; evidence-map rows ⊇ claims rows;
unresolved.md ⊇ every [U] touched. A chapter with an empty evidence-map cannot pass.

## 4 · Book-level provenance spine
One book-level `provenance-index` aggregates the per-chapter evidence-maps; it must resolve every
source to: corpus file (with archaeology classification) · synthesis registry · GN act · ratified
FA element. Unresolvable source ⇒ the claim is removed or re-graded, never footnoted away.
