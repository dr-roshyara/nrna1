# Phase 5N — Governance Evidence Census (corpus-wide, per the authorization's §5)

## Search terms and key results

| Term | Result |
|---|---|
| K-1, `K_t`, M₄₉, "8-primitive kernel" | seq 0630/0757/0764/1006/1007/1008 — already fully accounted (`02`) |
| `D-FA` | seq 0764 only, 7 determinations (`D-FA-1` through `D-FA-7`) |
| `FA-4` (standalone) | **Multiple hits, all referring to a document named `FA-4-concept-terminology-reconciliation.md`, cited as "RATIFIED, GN-31"** (D285-2, D285-8, R1, 01-GAP-UPDATE, 18-GITA-CANDIDATES, 03-D285-PROTOCOL-CONFORMANT-HYPOTHESIS-DOSSIER) — **this file does not exist anywhere in the checked-in corpus** (`05`) |
| `FA-9` | seq 0764's own preamble ("Document: FA-9 Ratification Packet") and one other file (`step_276_foundational-gap-reconciliation-and-closure-audit-final.md`) |
| "qualified naming" | seq 0764 (D-FA-6), and downstream citers (D285-1, REFINED-STEP-285) |
| "naming register" | seq 0764 (D-FA-6's own title, "K_t naming register") |
| RULING, ACCEPT | seq 0764, its own 7-row summary table |
| RATIFIED | seq 0764 ("The Final Architecture is hereby RATIFIED"); D285-1's own K-1 row; D285-2's own "already RATIFIED" framing for `FA-4` |
| HPA, "Highest Project Authority" | seq 0764, already established (Phase 5M) |
| "governance decision," "register," "packet," "claim" | Various, all already accounted via the above; no new independent artifact found |
| C-022, claim-registry | Already fully censused, Phase 5M |

## False positives, recorded separately (per the authorization's own instruction)

- "FA-4" also appears as a corpus-internal shorthand inside `D285-6`'s own text ("FA-4: `K_t` is 'state
  over 8 primitives', not 2") — **this is a citation OF the same missing document, not an independent
  artifact** — not double-counted as a second source.
- "M₄₉" appears in exactly 2 files (seq 0757, seq 0764) — no additional occurrences found.

## The single most consequential new result

**`FA-4-concept-terminology-reconciliation.md` is cited, quoted, and described as "RATIFIED" under
"GN-31" by at least 6 separate downstream documents (D285-2, D285-8, R1-SOURCE-EVIDENCE-REGISTER,
01-GAP-UPDATE-FROM-REVISED-286, 18-GITA-CANDIDATES-FOR-KNOWLEDGEOS-AND-THE-KERNEL,
03-D285-PROTOCOL-CONFORMANT-HYPOTHESIS-DOSSIER) — yet this file does not exist anywhere in the
checked-in corpus, confirmed via `find` and `grep`, this phase (`05`).**
