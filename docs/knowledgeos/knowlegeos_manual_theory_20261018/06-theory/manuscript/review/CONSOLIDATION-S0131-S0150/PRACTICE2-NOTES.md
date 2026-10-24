# Practice 2 for the new concepts (CON-0239–CON-0256): corpus first, then outside work

*2026-10-24. By the Professor, during the consolidation freeze (the Principal: "Practice-2 searches now, read-only and logged; the citations go in at stage 2"). Nothing here edits the book. Searches are logged in the read log as `scan` rows, and the sources read whole are logged as `topic-full` rows. The reading position did not move.*

*Outside works are cited **from the Professor's knowledge**, by author, year and title. **They were not opened in this session**, and no corpus or book text was sent outside. Each is marked "(not opened)" until a stage-2 check against its text.*

## Searches run (logged)

| # | Pattern | Allowed files matching | Read whole (topic-full) |
|---|---|---|---|
| 1 | `lamport\|happen(s\|ed)?[- ]before\|logical clock\|vector clock\|causal order` | 42 | S0915 |
| 2 | `lost update\|read-modify-write\|last.writer.wins\|clobber` | 13 | S0991 (also the logical-clock hit of search 1) |
| 3 | `mutation test\|mutant\|mutation score\|seeded (defect\|fault)` | 3 | — (S0144 already read; S0475 and S1947 not on topic) |
| 4 | `test oracle\|oracle problem` | 4 | S2389 |
| 5 | `legal hold\|litigation hold\|retention hold` | 0 | — |
| 6 | `canonicali[sz]\|json canonical\|byte-identical` | 294 | — (top hits are the chain already read, S0131–S0142) |
| 7 | `replication (≠\|is not\|vs) corroboration\|reproducib.*replicat` | 2 | — (S1500, S1502: verification checkpoints; S0915 L1199–1231 covers it) |
| 8 | `self-referen.*hash\|hash.*(itself\|own bytes)\|quine` | 31 | — (the chain already read; "quine" hits are about W. V. O. Quine) |

## Per concept

| Concept | Corpus (Src) | Outside work (Ext, not opened) | Classification (to be stated in the book at stage 2) |
|---|---|---|---|
| **CON-0251** order between records only through citation, absent a shared order source | **S0915** L66–91 (happened-before: "Lamport's happened-before relation gives us a useful formal abstraction"; events ordered through a message), L133–163 (physical, recorded and causal order kept apart), L167–231 (a partial order; unknown order preserved as a legitimate state). **S0991** L667–709 (logical clocks: causal order kept although arrival order differs) | **Lamport, L. (1978), "Time, Clocks, and the Ordering of Events in a Distributed System", CACM 21(7): 558–565** (not opened). Happened-before: the smallest relation containing process order and send→receive, closed under transitivity; events unrelated by it are concurrent | **established, adapted.** CON-0251 is Lamport's happened-before with a citation in the role of the message, and record order in the role of process order. "Absent a shared order source" is Lamport's point that without a common clock only the partial order is available. A shared counter acts as one process, or as a total order consistent with it. *The book's own contribution is only the mapping to records and its use for store switches.* Cite S0915, S0991 (Src) and Lamport 1978 (Ext) |
| **CON-0245** acknowledged is not recorded | **S0991** L361–409 (lost update: concurrent writes "may silently lose A's update"), L411–473 (optimistic concurrency control with versions), L1994–2003 (conflict visibility: "must become explicit conflicts, not silent overwrites"). S0970 §72.7 (last-writer-wins) seen at the hit line only | **Berenson, H. et al. (1995), "A Critique of ANSI SQL Isolation Levels", SIGMOD** (not opened): the lost-update anomaly. **Kung, H. T. & Robinson, J. T. (1981), "On Optimistic Methods for Concurrency Control", ACM TODS** (not opened) | **established (the lost update).** What CON-0245 adds is the consequence for L9: an acknowledgement on a stale read is not a record. That is an application, not a new result |
| **CON-0253** rediscovery shows reach, not detection; three axes | **S2389** L23–34 (CE-2: a positive control "demonstrates that P10 can fail, which is what makes its passes elsewhere meaningful") | **DeMillo, R. A., Lipton, R. J. & Sayward, F. G. (1978), "Hints on Test Data Selection: Help for the Practicing Programmer", IEEE Computer 11(4)** (not opened): mutation testing. Fault seeding (Mills, 1970s) (not opened) | **established (mutation testing; seeded faults), adapted.** The three axes (novelty of instance, independence of the planter, blinding) are the book's arrangement for its own review method |
| **CON-0256** a reference from the survivor alone cannot detect its divergence | S2389 L23–34 (a positive control; indirectly) | **Barr, E. T. et al. (2015), "The Oracle Problem in Software Testing: A Survey", IEEE TSE 41(5)** (not opened): a test oracle derived from the implementation under test cannot reveal its faults | **established (the test-oracle problem), adapted** to replacement gates |
| **CON-0243** a commitment detects loss only of what it committed to | already Ext in §9.15 K1 (Schneier & Kelsey 1999; Crosby & Wallach 2009). S0991 L1660–1702 (irreversible actions need higher assurance) | as cited in K1 | **established** (tamper-evident logs): unchanged |
| **CON-0239** one writer is not one record | **S0991** L1085–1124 (split-brain: two nodes each believing itself primary) | split-brain in replicated systems (textbook; not opened) | **adapted.** The estate's case (two readers' defaults) is a split of the reader side with one writer. It is stated in the book only as the estate's case |
| **CON-0242** checkable is not checked | **S0915** L1199–1231 ("Replication ≠ Corroboration") is a near relative (a copy is not a second source) | reproducibility versus replication (the replication literature; not opened) | **adapted** |
| **CON-0250** an undisposed quarantine is a hold | no corpus hit for "legal hold" (search 5) | the legal hold in records management (not opened) | **possibly an analogue.** No corpus support. Recorded as a lead only |
| **CON-0240, 0241, 0244, 0248, 0249, 0254** | searches 6 and 8 return the chain already read (S0131–S0142), which is Src in the book already | — | no outside result checked. Stated as the estate's case, as now |

## For stage 2 (after the score)

- **Citations to add** in the working book, where the concepts live: CON-0251 (S0915, S0991, Lamport 1978), CON-0245 (S0991, Berenson et al. 1995), CON-0253 (S2389, DeMillo et al. 1978), CON-0256 (Barr et al. 2015).
- `EXTERNAL-SOURCES.md` entries for the four outside works, marked "not opened" until each is checked against its text.
- The register's basis fields name the classification ("established, adapted" and so on).
- *Not a correction found by the reviewers*, so not part of stage 1 on the copy.
