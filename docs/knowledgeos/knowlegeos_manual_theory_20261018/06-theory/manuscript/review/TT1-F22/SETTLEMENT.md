# Settlement of review TT1 (theory turn 1, F22: recording authority)

*2026-10-19. One reviewer in topic-review mode (§12b); one commission and one step-2 message. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. Harness time: step 1 140 s; steps 2–3 253 s; total 393 s.*

## Re-checked before accepting

| Finding | Check | Holds |
|---|---|---|
| 1 (critical) lost writes with one recorder | S0234 L282: "23 / 30 concurrent-append trials silently losing a transition"; §1.16.7 allows several paths consulting one lock | yes |
| 2 (critical) the purpose's relocation test | RESEARCH-PURPOSE L52–54: "move the capability into several independent applications"; "kernel candidate only if it is necessary (1) and not delegable (3)" | yes |
| 3 E-1, E-2 | S0234 L19–20 | yes |
| 4 no time dimension | S0234 L283 | yes |
| 5 human acts on STARTs | S0078 L63 (START 35/35 human) | yes |
| 6 self-recorded STARTs; `registeredBy` overwrite | S0078 L143, L258, L294; §1.14.3 roles table ("records … STARTs") | yes |
| 9 durability decision contested | S0234 L228–235 | yes |
| App. B endings recount | §1.13.2: REVOKED 1, CONSUMED 1 | yes |

**All 13 findings are accepted.**

## Measures

| Measure | Value |
|---|---|
| Findings | 13: **2 critical** · 7 high · 4 normal |
| Concepts written | 3 (CON-0135–0137): **3 INVALID as stated**; restated forms PROVISIONAL; 1 new at settlement (CON-0138, CE), unreviewed |
| Material omissions (reviewer's ranking) | 7: E-1, time dimension, OQ-10, the `registeredBy` overwrite, S0234's asymmetry and its defects, circularity, the notation bias |
| What the review changed in substance | the deciding variable of F22 (from kernel closure to the purpose's relocation test: K3 × K4, given R_s ∈ Req); the stronger kernel candidate (the evaluator CE, not the recorder) |

## Changes made

| Finding | Change |
|---|---|
| 1 | one-recorder rule withdrawn; U scoped to uniqueness; occurrence by set union with global act identity (§9.6.4 steps 1–3) |
| 2 | Analysis 2 rebuilt on the relocation test; K3 and K4 named; question for the Principal |
| 3 | Analysis 3 "append-only: no, as built" (E-1); the contract gains no-lost-update and one write path (E-2) |
| 4 | Analysis 1: R_s needs the evaluator CE, time, validity, endings and comparable scope; **CE added** (CON-0138) |
| 5 | CR-a redefined as all registration of human acts (grants and STARTs); the split marked as the book's choice; cross-part reads cited |
| 6 | §6.5 C2 and §9.6.7: HANDOFFs not a breach (kept); self-recorded STARTs restored as an open breach of the roles law; I-5 restated (declared role checked, actual writer unattested; 47/47 structural) |
| 7 | KC restated ("checkable on records"; excludes placement-presupposing invariants), kept as an alternative only |
| 8 | options (C) and (S) merged; (C) delegated vs (P) plane |
| 9 | "decided, not executed" → contested |
| 10 | S0234's asymmetry added as plane-ward evidence, with its two defects |
| 11 | "plane and context need not differ" marked Int; the two acts' standing and order recorded |
| 12 | circularity noted in the ARCH-INV-1 row |
| 13 | the Capability Identity Invariant's notation bias noted |
| App. B | the "endings never exercised" statements dated (§6.5 C2, map); §1.13.2's recount cited |

"What does not hold" in §9.6 (§9.6.9) lists the eight refuted parts of the first version.

## Dependency graph (Rulebook §12d)

`python3 -I tmp/tools/dependency_graph.py --down CON-0135 CON-0136 CON-0137`: 13 downstream items each. They are §9.6, then §6.5, then transitively §4.2–§4.3, §5.5–§5.6, §6.4, §8.4–§8.7, §9.4–§9.5. **Reassessment:**
- **§9.6** hosts all three concepts: revised.
- **§6.5** cites §9.6 only for the C2 correction, which does not rest on CON-0135–0137: unaffected beyond the C2 correction itself.
- **The rest** are reached through §6.5 for other claims: unaffected.

*Limit: section-level granularity makes the lists wide. Implicit dependencies remain step 8's and the reviewer's task.*

## For the Principal (F22 is reserved)

The prepared decision (§9.6.6), in one line:
- **if the kernel serves one application, or several with no uniqueness across them:** (C) delegated, under a kernel-defined contract and evaluation rule;
- **if several independent applications must keep a uniqueness across them:** (P), where the kernel provides the serialization point for each such constraint and the evaluator.

The questions that decide it:
- **K3:** does the kernel serve several independent applications?
- **R_s ∈ Req:** must the kernel answer questions of standing?
- **K4:** which uniqueness constraints must the record keep across applications? This one is evidence plus a modelling choice.

## Next

- The next theory turn (after two reading windows, §12b) should test **the evaluator CE** on its own: it is the stronger kernel candidate and was not run in full.
- The reading resumes at **S0066**.
