# Window C26: per-source measures, adversarial pass, step 8, checks

*2026-10-23. Sources: S0136–S0140, in order. Each was started at opening and closed after its theory was written into §8.19. No source was added (§12a: none needed). New: §8.19 (C1–C27); register CON-0245–CON-0247. CON-0240, 0243 and 0246 were amended from this window's sources. A note was added to §8.18 C21, a defect found later in reviewed work (§8.19 C9). No kernel section changed. The kernel questions are collected in §8.19.3.*

## Per source (start at opening, close after writing; wall clock)

| Source | Lines | Start → close (UTC) | Time | Depth | In §8.19 | New concepts |
|---|---|---|---|---|---|---|
| S0136 | 418 | 18:11:41 → 18:14:22 | 161 s | full | C1–C9 | CON-0245, 0246 |
| S0137 | 471 | 18:14:22 → 18:16:21 | 119 s | full ¹ | C10–C16 | — |
| S0138 | 381 | 18:16:21 → 18:17:33 | 72 s | full | C17–C22 | CON-0247 |
| S0139 | 115 | 18:17:33 → 18:17:58 | 25 s | full ² | C23–C24 | — |
| S0140 | 160 | 18:17:59 → 18:18:36 | 37 s | full | C25–C27 | — |

**Window, Professor's harness time:** 18:11:28 (start) → 18:19:48 (draft committed) = 500 s for reading and writing. The frame, the adversarial pass and the checks are included.

¹ Read topic-full once before (2026-10-20, F22). The book cites L52, L172 and L367.
² S0139 mostly repeats S0137–S0138, which made it a skim candidate under the new rule ("repeats earlier material"). It was read in full because two points were new (C23 "addressed, never closed"; C24 a later amendment repointing a summary). **So no source was skimmed in C26, and the §3 safeguard has nothing to check.**

## Practice (C25 practice kept)

- **Second lineage pass:** done per source. It found that no source in the window is an authority's act. AMD3–AMD5 rest on an unregistered commission with no grants. "accepted design `ae451db9`" appears in five producers' texts with no act. The producer sets its own next reviewer's exclusion list (S0140).
- **Source-first attacks:** inline in §8.19, marked *Attack on the source*: C2 (the freeze), C25 (self-declared lanes). Others are folded into the Int parts (C3's "accepted" contradiction).
- **Quote the rule relied on:** done for L9, K6, K7, G3, R2, §9.13's refusal, L10 and §1.16.7. The §1.16.7 paraphrase was replaced by the rule's text, with the Professor's extension labelled (C19).

## Adversarial pass on the book's own claims (before review)

| Claim | Attack | Result |
|---|---|---|
| C1 (CON-0245) | is "L9 needs K6" new, or is it already in L9? | L9 assumes acceptance means recorded; K6 states serialisation. The dependency between them was not stated. Holds as a deduction |
| C3 | "a declaration can only record" a transfer? | **Overstated:** a declaration can direct the switch beforehand (§8.17 C9). Narrowed in the text |
| C4 / C12 (CON-0246) | "only unreachability" reaches the reader who opens the record? | **Overstated:** a status in the path (the store's name) reaches that reader and changes no byte. Amended |
| C11 | is "a bounded claim can be self-satisfying" true in general? | Only for what lies outside the bound. The claim is restricted to that. Holds |
| C13 | does a wrong path give a record in D? | Well-formed entries put it in D. Whether the writer creates a record at a wrong path for every command is not shown. Caveat added |
| C17 (CON-0247) | must the expected list be a predicate? | Expected entries are not known by content beforehand, so yes. S0140's class list agrees. Holds; later amended with the authenticated-actor condition (C25) |
| C18 | does membership depend on the write time? | Under CON-0239, yes. The estate uses the phase as a proxy for the time. Holds |
| C19 | does §1.16.7 cover concurrent writes? | Its text says "merging". The extension to unserialised writes is the Professor's, now labelled. Holds as an extension |
| C26 | is "promoting a non-record" a violation of R-CONFLICT's text? | R-CONFLICT forbids silently selecting a side. Promotion is the source's reading (S0140 L54). The book's deduction is that a merge rule must refuse both. Labelled as a deduction |

## Book search; step 8

| Query | Hits | Relation |
|---|---|---|
| lost write, lock, CAS, read-modify-write | §9.15 K4-o; §9.16 R4a (S0137 L52) | C1, C10 |
| writer switch, transfer instant | §9.16 R4a (S0137 L172) | C3 |
| acceptance, record before use | §9.15 L9, K6 | C1 |
| outside D, refuse, U | §9.13 | C7, C13 |
| interpreter, evaluator version | §9.15 K7; §8.18 C12, C15 | C8 |
| anchor, commitment, manifest | §9.15 K1; CON-0243 | C2, C11, C17, C25 |
| marker, status, unbound reader | CON-0246 (new), §8.18 C13 | C4, C12, C22 |
| assigned identifier, label | §1.10 I-0; CON-0219; §9.16 R2 | C5, C14, C21, C27 |
| serialization, prevent vs detect | §1.16.7 (THEORY-MAP L40) | C19 |
| test present vs adequate | CON-0229 | C20 |

## Checks

- **Quote check:** §8.19 has 34 found and 0 not found; §8.18, 69 and 0. The whole book has 3, the baseline.
- **R1:** 223 rows with a home, 1 inherited flag (CON-0133). A transient H1 flag on CON-0246 (a stale location after the rename) was fixed before the commit.
- **Register:** 247 concepts (40 VALIDATED, 113 PROVISIONAL, 94 INVALID).
- **Draft in git:** `013c343de`. The subject names the section and the ID range only.

## For the S0150 evaluation (recorded as they occur)

- **A defect found later in reviewed work:** §8.18 C21 ("reproducible at the plan's commit"), corrected by S0136 L320–321. C25's review did not catch it.
- **Remedies reintroducing their defect class:** RC-1 inside CL-3's remedy; RD-7 the same shape a third time; DI-1 inside AMD5's own first draft (§8.19 C16, C22, C27).

*Annotation after review (2026-10-23; the rows above are kept as written):* the C4/C12 row's path form was refuted (W-02), and the C17 row's "must be a predicate" too (W-01). The C1 and C18 rows missed durability and the phase proxy (W-07, W-04). See `SETTLEMENT.md`.
