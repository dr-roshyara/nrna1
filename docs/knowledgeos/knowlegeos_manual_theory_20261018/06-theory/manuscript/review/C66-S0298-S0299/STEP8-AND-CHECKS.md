# Window C66: measures, challenge records, step 8, checks

*2026-10-31. Sources: S0298, S0299, both on the allowed list. New: §8.59 (C1–C5). No new concept. Changed theory since the last window review: the C59, C60 and C61 settlements; the C62–C65 settlements are pending.*

## Measures

| Measure | Value |
|---|---|
| Sources, lines (`wc -l`) | S0298 405 (S0253 L8 reviewed it at 403 lines); S0299 186 |
| Depth | full, both (the Professor and W) |
| Professor, reading (start/stop records) | S0298 15:35:54Z–15:36:21Z; S0299 15:36:07Z–15:36:22Z. Bookkeeping times; they understate reading (both files were read before the stop records were written) |
| Reviewer W, step 1 | 106.7 s (harness), 6 tool uses; inventory sha256 in `SHA256-STEP1.txt` |

## Step 8

- **Dependencies:** C1: §8.39 C3 (a); §9.13.2; §8.46 C2; S0253 L8. C2: §8.46 C2; §8.36 C1; §8.06; §8.20 C11. C3: §8.46 C2; A1; §9.17 R1. C4: CON-0265; §8.20 C6. C5: A1; §8.36 C1; §8.57 C4; §8.55 C2; §8.42 C1; §8.20 C10.
- **Taken from W's inventory into the draft before step 2 (disclosed):** T18 passed on the wrong text and L317 not propagated (C2); Premise 2's "three writes" as the repair's reading (C3); the routing tension S0298 L383–393 against S0299 L169, L176–181 (C5); "this session" not identified with the producer (lineage, C1).
- **R1:** no new register rows; 241 homes, 1 flag (CON-0133, pre-existing).
- **R2 (§8.59):** checked at commit; tier A 0 after two plain-language words were rephrased; no undefined concept, so no TODO row. C4's TODO is a **topic for the map** (whether a failed carrier-change check binds the next attempt's reference), not an undefined term.
- **R3:** none proposed; §8.46 C2 already holds the line's distinctions and is cited, not restated.
- **Quote check:** by hand against both sources.

## The Principal's directions D1–D3 (I-013): meeting points in this window (for the report; not used as evidence, not written into the book)

| Direction | Where this window meets it | Label |
|---|---|---|
| D1 | S0298 L321: "`docs/knowledgeos/backlog/EKS-06-*`" left as-is, as a finding record | Src: EKS appears only as a backlog-item prefix; no statement of its relation to KnowledgeOS; no difference shown |
| D2 | none | — |
| D3 | none (no kernel or engine statement in either source) | — |
