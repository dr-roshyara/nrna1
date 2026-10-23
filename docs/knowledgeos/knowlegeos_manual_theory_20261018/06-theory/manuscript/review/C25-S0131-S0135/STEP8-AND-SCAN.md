# Window C25: per-source measures, adversarial pass, step 8, checks

*2026-10-23. Sources: S0131–S0135, in order. Each was started at opening and closed after its theory was written into §8.18. New: §8.18 (C1–C23); register CON-0239–CON-0243. No kernel section changed. The sources bear on §9.15 K1, K2 and K7 as evidence and as open questions, recorded in §8.18.3. Under the Principal's rule (kernel edits only when a source changes the theory), they go to a theory turn, not into §9.15.*

## Per source (start at opening, close after writing; wall clock)

| Source | Lines | Start → close (UTC) | Time | Depth | In §8.18 | New concepts |
|---|---|---|---|---|---|---|
| S0131 | 180 | 17:45:07 → 17:48:01 | 174 s ¹ | full | C1–C5 | CON-0239, 0240, 0241 |
| S0132 | 706 | 17:47:45 → 17:49:04 | 79 s | full | C6–C9 | — |
| S0133 | 151 | 17:49:09 → 17:50:20 | 71 s | full | C10–C14 | CON-0242 |
| S0134 | 127 | 17:50:20 → 17:51:20 | 60 s | full | C15–C19 | — |
| S0135 | 114 | 17:51:26 → 17:52:13 | 47 s | full | C20–C23 | CON-0243 |

¹ S0131's first close was refused because its register rows were missing. S0132 was opened before the rows were added and S0131 closed, so the two intervals overlap by 16 s.

**Skim.** None. S0132 came closest: it is an unsigned status summary, and most of its business-value text (L341–706) adds no theory. But its single-resolver and migration-proof points (C7, C8) were not yet in the book, so under the C23 bar it was read in full.

## Practice from C25 (the Principal: keep it)

- **Second lineage pass.** Done per source, under each source's claims in §8.18. It found that S0134's decision reaches the record only through the registrar, in wording close to the registrar's recommendation (C15, lineage). It also found that AMD1 and AMD2 amend the plan's grant, not the design (S0133 lineage).
- **Source-first attacks.** The attacks on the sources' own claims are inline in §8.18 and marked *Attack on the source*: C2, C4 (a)–(c), C9, C10, C17, C20.

## Adversarial pass on the book's own claims (before review)

| Claim | Attack | Result |
|---|---|---|
| C1 (CON-0239) | is it more than K2, which already requires readers to agree? | K2 states the requirement. CON-0239 adds a **cause** of its failure with no fork, and the point that K1's "the record" is a parameter. Stated as evidence and an open question, not a kernel change. Holds |
| C2 (CON-0240) | does the book's identity really omit fields? | Yes, and more than the draft said: §9.16 R2 L93 excludes the recorder and the status. **Sharpened in the text.** R2's merge rule is silent on copies that differ in the recorder (recorded) |
| C3 (CON-0241) | does the union really settle a prefix disagreement mechanically? | In D, yes. The estate's fold, though, is a state machine, and a union of branches may be an illegal sequence. **Limit added** |
| C4 (a) | does take-one-side really pass the stated test? | Yes. Both inputs share the seq numbers, so any one-content-per-seq result is dense, monotonic and a superset of both sets. Holds |
| C4 (b) | could step 2 or step 4 include the reconciliation? | No: step 2 is hash equality before the switch, and step 4 is a declaration. Appends between 1 and 3 exist only at the old path. Holds; pending S0300 for the plan's version |
| C9 | is "durable is not attested" an attack on a strawman? | The source says "can prove: who decided, when it was decided" (S0132 L401–404). With 2 of 213 dated transitions and self-declared attribution, that exceeds the record. Scoped, holds |
| C10 (CON-0242) | is "checkable is not checked" just CON-0217? | CON-0217 separates provenance from reproducibility. CON-0242 adds the **transition**: a recorded re-run by another party, on a named cut. S0135 then supplies the instance (C21). Holds |
| C16 | is "different answers at earlier valid times" right? | Under "already inside", P-4 is governed from AMD2's act time; under "extended", from the extension's. Cuts between the two differ. Holds, if AMD2 is the act that adopted the invariant (the source says it is the invariant's grant, S0134 L61) |
| C17 | "Phase 5 invalid as written"? | **Overstated:** that is the registrar's phrase for option C only (S0134 L41). Corrected to "rested on an undecided presumption" |
| C20 (CON-0243) | is "a commitment covers only its cut" new, or is it K1's anchor already? | K1 says "with an external anchor, yes" and does not limit the yes to the anchor's cut. CON-0243 is that limit. Holds |

## Book search; step 8

| Query | Hits | Relation |
|---|---|---|
| exclusive write path, single view, record identity, default | §9.15 K1, K2 | C1, C7, C13 |
| content identity, serialisation, byte-identical | §9.16 R2 (L93), §8.15 C6 (CON-0219), §9.6 K2 | C2 |
| unordered, ties, positional | §9.13 order (v8), §9.16 R2 (d) | C3 |
| density, seq gap, detector | §8.17 C5 (CON-0237), §9.15 K1 | C4 (a) |
| summary, accepted, status | CON-0232, CON-0221 | C6 |
| date, act time, attested | §8.17 C6, C8; §9.15 L1a, L6; CON-0196 | C9 |
| reproducible, re-run, self-review | CON-0217, CON-0127, CON-0229 | C10, C21, C23 |
| assignment, performer | §9.15 L10; CON-0195; §8.16 | C11 |
| evaluator version | §9.15 K6, K7; §9.11.5 | C12, C15 |
| scope, widen, by interpretation | §8.9 ("what an act covers is fixed by the act") | C16 |
| historical claim, later act | §8.14 C1 | C17, C22 |
| decided, realized | §8.17 C9 | C18 |
| commitment, anchor, manifest | §9.15 K1 | C20 |

**Dependencies:** §9.15 K1, K2, K7 (settled; unchanged); §9.16 R2 (settled); CON-0217, CON-0229, CON-0231, CON-0232 (validated or provisional as registered).

## Checks

- **Quote check:** §8.18 has 68 found and 0 not found. The whole book has 3, the baseline (Ch. 3, inherited).
- **R1:** 219 rows with a home, 1 inherited flag (CON-0133).
- **Register:** 243 concepts (40 VALIDATED, 109 PROVISIONAL, 94 INVALID), checked after each write.
- **Draft in git:** `f1e14921d`. The subject names the section and the ID range only, and no depth choices.

*Annotation after review (2026-10-23; the rows above are kept as written):* the C2 row's "R2's merge rule is silent on copies that differ in the recorder" is false (W-01); the C1, C3, C10 and C20 rows missed the replica case, R2's setting, S0133 L38 and K1's "relative to a commitment" (review, refutation adequacy). See `SETTLEMENT.md`.
