# Window C27: per-source measures, adversarial pass, step 8, checks

*2026-10-23. Sources: S0141–S0145, in order. Each was started at opening and closed after its theory was written into §8.20. No source was added. New: §8.20 (C1–C23); register CON-0250–CON-0253. CON-0243 was amended (the gate's operands). No kernel section changed; the kernel questions are in §8.20.3.*

## Per source (start at opening, close after writing; wall clock)

| Source | Lines | Start → close (UTC) | Time | Depth | In §8.20 | New concepts |
|---|---|---|---|---|---|---|
| S0141 | 428 | 18:31:14 → 18:32:40 | 86 s | full | C1–C5 | CON-0250, 0251 |
| S0142 | 583 | 18:32:40 → 18:34:06 | 86 s | full | C6–C11 | CON-0252 |
| S0143 | 114 | 18:34:06 → 18:35:03 | 57 s | full | C12–C14 | CON-0253 |
| S0144 | 80 | 18:35:03 → 18:35:46 | 43 s | full | C15–C18 | — |
| S0145 | 289 | 18:35:46 → 18:36:49 | 63 s | full | C19–C23 | — |

**Window, Professor's harness time:** 18:31:14 → 18:38:14 (draft committed) = 420 s for reading and writing, including the frame and the adversarial pass.

**Skim.** None. After C26's finding (a full read of S0139 added nothing a skim did not), each source was checked for new distinctions before reading in full. All five had them, and S0144 (80 lines) was the closest to a skim. **No skim, so the §3 safeguard has nothing to check.**

## Practice

- **Lineage pass:** per source. It found two authority acts in the window, AMD6's commission grants and a DA plan approval, neither quoted. "accepted design `ae451db9`" now appears in seven producers' texts. The author of the AMD chain is also the author of Track 2's commissioning review and of its conformance check (S0144, S0145).
- **Source-first attacks:** C2 (the switch record must name the old head), C6 (a store can record its prefix), C12 (rediscovery is reach), C19 (the trend recounted: 29 → 31 → 50%, not 29 → 46 → 58%).
- **Quote the rule:** G3, K2, H3b, CON-0071 and CON-0127 are quoted from their homes.

## Adversarial pass on the book's own claims (before review)

| Claim | Attack | Result |
|---|---|---|
| C1 (CON-0250) | does D already have the hold "by construction"? | **Overstated:** D has removal only by recorded act (the negative half). A positive hold state is not a kind of D. Narrowed |
| C2 (CON-0251) | is direct citation the only cross-record order? | **Too narrow:** a chain of citations across records also orders. Added |
| C2 | "the switch record is the first entry of the new store"? | **False**, per S0142 DV-3, a later source in the same window. Corrected before review |
| C6 (CON-0252) | can a store never record its own state? | It can record its prefix, as a hash chain does. Scoped to a state that includes the recording |
| C7 | is "check the survivor" new beyond CON-0243? | CON-0243 says what an anchor covers. C7 adds which operands a gate must compare. Holds as an amendment |
| C15 (CON-0253 grades) | is mutation by the spec author "a non-author"? | It is a non-author of the **checker**, not of the defect classes. Stated |
| C19 | is RD-7 really in the missing-act class? | The source's own X-5 names it so. The attack is on internal consistency, and it holds either way |

## Book search; step 8

| Query | Hits | Relation |
|---|---|---|
| quarantine, hold, unresolved, tombstone | CON-0221; §9.15 G3 | C1, C9 |
| order, citation, cross-record | §9.15 K2; CON-0251 (new) | C2, C8 |
| final state, closing record, fixed point | §8.18 C8 | C6 |
| gate, operand, survivor | CON-0243 | C7 |
| back-test, mutation, planted gaps | CON-0242; Rulebook §12 | C12, C15 |
| quote vs define, use/mention | CON-0238 | C16 |
| rule in force, model changed mid-review | §9.8 H3b; §9.15 K7 | C20 |
| independence TRUE, routing, report | CON-0127; CON-0236 | C21 |
| rule without check | CON-0071 | C22 |

## Checks

- **Quote check:** §8.20 has 14 found and 0 not found. The whole book has 3, the baseline.
- **R1:** 229 rows with a home, 1 inherited flag.
- **Register:** 253 concepts (40 VALIDATED, 117 PROVISIONAL, 96 INVALID).
- **Draft in git:** `8beb749c0`. The subject names the section and the ID range only.

## For the S0150 evaluation (recorded as they occur)

- **A defect found later in reviewed work:** none in C27 against the book's settled sections. In the sources, S0143/S0144 found a malformed row in S0131, which the book cited in §8.18 without noticing (§8.20 C14, C17).
- **A measurement in a source that contradicts the source's own boundary:** S0145's trend (C19).

*Annotation after review (2026-10-23; the rows above are kept as written):* the C2 row missed the converse of K2 (W-01). The C6 row's prefix scoping was itself refuted, since a self-including entry is possible (W-02). The C1 row's narrowed D clause was still wrong (W-03). The C15 row accepted S0144 as grade 2 (W-07). No new concept recorded a rival explanation and a falsifier (entry 55 (c), (d)). See `SETTLEMENT.md`.
