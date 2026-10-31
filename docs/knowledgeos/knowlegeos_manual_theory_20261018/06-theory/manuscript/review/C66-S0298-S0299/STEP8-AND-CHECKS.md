# Window C66: measures, challenge records, step 8, checks

*2026-10-31. Sources: S0298, S0299, both on the allowed list. New: §8.59 (C1–C5). No new concept. Changed theory since the last window review: the C59, C60 and C61 settlements; the C62–C65 settlements pending at drafting (settled `ffda36c93`, `1170579e9`, `209347211`, `2f9c57114`; §8.59 re-read at `0e2b0c6e0`).*

## Measures

| Measure | Value |
|---|---|
| Sources, lines (`wc -l`) | S0298 405 (S0253 L8 reviewed it at 403 lines); S0299 186 |
| Depth | full, both (the Professor and W) |
| Professor, reading (start/stop records) | S0298 15:35:54Z–15:36:21Z; S0299 15:36:07Z–15:36:22Z. Bookkeeping times; they understate reading (both files were read before the stop records were written) |
| Reviewer W, step 1 | 106.7 s (harness), 6 tool uses; inventory sha256 in `SHA256-STEP1.txt` |

## Step 8

- **Dependencies (as settled):** C1: §9.13.2; §9.8 F30; §8.35 C1; §8.39 C3 (a), (b); T9-14; §8.46 C2; S0253 L8. C2: S0253 L204; §8.46 C2; §8.36 C1; §8.06; §8.20 C11; §8.60 C3; §8.61 C5. C3: §8.46 C2; §9.17 R1; S0253 L3, L190; S0300 L810. C4: CON-0243; §8.46 C2, §8.46.5. C5: A1; §8.57 C4; §8.55 C2; §8.20 C10, C11; §8.42 C1; S0253 L262–275; S0296 L20, L24, L26, L33, L41; §8.61 C5. *Draft list (superseded):* C4 had CON-0265 and §8.20 C6.
- **Taken from W's inventory into the draft before step 2 (disclosed):** T18 passed on the wrong text and L317 not propagated (C2); Premise 2's "three writes" as the repair's reading (C3); the routing tension S0298 L383–393 against S0299 L169, L176–181 (C5); "this session" not identified with the producer (lineage, C1).
- **R1:** no new register rows; 241 homes, 1 flag (CON-0133, pre-existing).
- **R2 (§8.59):** checked at commit; tier A 0 after two plain-language words were rephrased; no undefined concept, so no TODO row. C4's TODO is a **topic for the map** (whether a failed carrier-change check binds the next attempt's reference), not an undefined term.
- **R3:** none proposed at drafting. At settlement: §8.60 C2 (L50, C1 as settled) and §8.61 C5 (the routing settled in the line) annotated in those drafts.
- **Quote check:** by hand against both sources.

## The Principal's directions D1–D3 (I-013): meeting points in this window (for the report; not used as evidence, not written into the book)

| Direction | Where this window meets it | Label |
|---|---|---|
| D1 | S0298 L321: "`docs/knowledgeos/backlog/EKS-06-*`" left as-is, as a finding record | Src: EKS appears only as a backlog-item prefix; no statement of its relation to KnowledgeOS; no difference shown |
| D2 | none | — |
| D3 | none (no kernel or engine statement in either source) | — |
