# authority-permission-vs-trust-rank-collision

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Authority"
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0042, scope OBJECT: "Authority names two unrelated things in the corpus: a permission relation Actor×Action×Context×Time (Step 187) and a source-trust rank (authorities.yaml: authoritative>derived>generated>historical>provisional). Step 267 maps the permission concept onto the trust concept and reports IMPLEMENTED — a false implementation correspondence (UL-2/G-28)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1718 §"UL-2 (`DERIVED`, HIGH). `Authority` names two different things"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1718. Candidate lifecycle: DORMANT. Evidence: no retraction, supersession, or self-contradiction recorded — this is a heuristic based on how long ago (by source_id) this label was last used, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1718 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
This label records a documented naming collision (finding "UL-2", severity HIGH, classified DERIVED): the corpus uses the word "Authority" for two unrelated things — a permission relation `Actor×Action×Context×Time` introduced at Step 187, and a source-trust ranking (`authorities.yaml`: authoritative > derived > generated > historical > provisional) [S1718]. The rationale for flagging it is that Step 267 §267.16 conflates the two: it maps "Governance status" onto `authorities.yaml` and reports the result as IMPLEMENTED, which the finding argues is a false implementation correspondence — the theory's permission concept is being satisfied by the implementation's trust-rank concept, "of the same kind as finding 11/PL-8" [S1718]. No alternative resolution is proposed in this row; it is an ARGUMENT/CORRECTION identifying the gap, not a fix.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1718] types=[ARGUMENT, CORRECTION] scope=THEORY-LEVEL — "UL-2: Authority names a permission relation (Step 187, Actor×Action×Context×Time) and a trust rank (authorities.yaml) as two unrelated things; Step 267 §267.16 maps Governance status onto authorities.yaml and calls it IMPLEMENTED, which maps the theory's permission concept onto the implementation's trust concept — a false implementation correspondence of the same kind as finding 11/PL-8." completeness=N/A (anchor: "UL-2 (`DERIVED`, HIGH). `Authority` names two different things"; file `docs/knowledgeos/brainstorming/verification/gap-discovery/15-UBIQUITOUS-LANGUAGE-GAP.md`)

## Notes for P3
This is a gap-discovery finding about an ubiquitous-language collision, not a definition of "Authority" itself — P3 should treat it as a flag to check against whichever label(s) carry the actual Step 187 permission-relation definition and the `authorities.yaml` trust-rank definition (neither of which is this label's own row content). `files_touching` lists an additional source S1720 not present in `family.rows`, so there may be more context on this finding elsewhere in the ledger beyond what was captured here.
