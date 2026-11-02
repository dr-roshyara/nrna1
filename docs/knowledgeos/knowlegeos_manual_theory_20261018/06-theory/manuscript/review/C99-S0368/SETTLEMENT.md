# C99 settlement (S0368; §8.92)

*2026-11-02. Report saved as REVIEW-REPORT.md (sha256 in SHA256-REPORT.txt, `b39a9bbe…6c43`); step-1 inventory STEP1-INVENTORY.md (sha256 `480ade1a…ea95`). Draft `03a9d915d`, committed before W's step-1 dispatch. Both briefs carried the copy list.*

## Theory
- **No new concept.**
- **Lineage:** (A) is a second answer to S0365 (B)'s commission and carries its framing (S0365 L1806, L1818, L1820, L1824, L1796); the six-part formula has one origin and is counted once; (A) states a verdict and ends with STOP and ten open questions.
- **C1:** within D, (A)'s consistency invariants (L154, L211) hold by derivation; no co-update requirement follows; the invariant only forbids (trivial case).
- **C2:** 𝒮 has the shape of an event log as a domain model; persistence is engineering (Th3).
- **C3:** the kernel derives the class itself by U2 (§9.19 L396); §9.18.5 L151 is stale (book item).
- **C4:** within D an item can carry a governed identifier and entries before any adoption, so admission confers no identity (against (A)'s L50, L98).
- **C5:** a purpose-free invalidation maps to annulment or a refuting entry; ewdr/erst only for a purpose (CON-0214; §9.18.5); standing unaffected by the sink property.
- **C6:** reading (ii), agreement; D's clauses have a model (§9.20.8 L153).

## Dispositions
| W-nn | Disposition | Where |
|---|---|---|
| W-01 | accepted (verified S0365 L1796, L1806, L1818, L1820, L1824) | lineage |
| W-02 | accepted | C4; item 4; §8.92.4 |
| W-03 | accepted (verified §9.18 L120, L203; CON-0214) | C5; item 5; §8.92.4 |
| W-04 | accepted | C1; index row |
| W-05 | accepted | items 4–6; headings C4, C6 |
| W-06 | accepted | C5 |
| W-07 | accepted | §8.92.3 |
| W-08 | accepted | lineage; index row |
| W-09 | accepted (verified §9.20 L90) | C1; item 1 |
| W-10 | accepted | C2; item 2 |
| W-11 | accepted | C2 |
| W-12 | accepted (verified §9.18 L151, §9.19 L396); book item for the consolidation | C3; §8.92.3 Q6 |
| W-13 | accepted | C1; §8.92.3 |
| W-14 | accepted | C6; §8.92.3 |
| W-15 | accepted | §8.92.3 |
| W-16 | accepted | C5 |
| W-17 | accepted | C1 |

Coverage items from W's report (ownerless "Across contexts" invariants, (B)'s vocabulary defect, "determination" from S0365 L1828 (verified), Śiva–Śakti, (B)'s Zero list, KnowledgeWithdrawn) added to §8.92.3.

## Procedure
- Order kept: brief (with copy list) → reading → draft committed → W step 1 → inventory saved → step-2 brief and copy list committed → report saved → settlement. Step-2 materials were held in scratch until the inventory was saved.
- W read one harness file holding its own printed output of the brief and copy list (no other content). No access problem; no stop condition.
- The Professor's recurring defect: a sixth D-scoped finding stated for the whole book (W-05).
