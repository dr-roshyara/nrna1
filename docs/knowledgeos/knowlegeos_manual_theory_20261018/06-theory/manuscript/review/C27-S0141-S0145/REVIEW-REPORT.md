*Window reviewer W (Opus), steps 2-3 review report, verbatim (saved by the Professor at 2026-10-23T18:48:40Z). sha256 of the hand-back text at receipt: `6abbd80bc94d5b4aee5a2f118feea07c3470c4048817ad24417e9615c0b797a3` (UTF-8, the text below this line exactly).*

C27 STEP 2-3: REVIEW REPORT

**Read for this step:** my saved inventory (its body re-hashes to `9624a6cb…`, so it is intact), the brief, STEP8-AND-SCAN, §8.20, register rows CON-0243 and CON-0250–0253 plus the rows §8.20 relies on, §8.19, §9.8 H2/H3b, §9.15 G3/K2/K7, §8.02, Rulebook v1.3 §2/§6, and the C26 SETTLEMENT and REVIEW-REPORT. `corpus_quote_check.py` on §8.20 gives 14 found, 0 not found, which confirms STEP8. Nothing was modified.

## Assessment
§8.20 represents the sources well and its quotes are accurate. Two of the new concepts rest on derivations that do not hold as written, though:
- **CON-0251** takes the converse of K2.
- **CON-0252** does not survive a counterexample, and it misstates its own source.

Largest risk: CON-0251 is a "K2 corollary" that other claims already cite (C8, CON-0252), so its missing premise travels with it.

## Concept verdicts

| Concept | Verdict | Reason / scope |
|---|---|---|
| CON-0250 (hold) | **PROVISIONAL** | The core holds: an undecided item blocks acts that need it decided and forbids its own destruction. Two clauses are wrong (W-03, W-04), and the place / hold / disposition triple is not minimal (W-05) |
| CON-0251 (order via citation) | **PROVISIONAL** | The "if" direction follows from K2. The "only if" direction needs a premise the proof does not state (W-01). The switch record also decides only "inside the named prefix", not "after the switch" (W-06) |
| CON-0252 (closing record) | **INVALID as stated** | A counterexample defeats the general claim, and the source's loop runs between **two** stores, not within one (W-02). Restate (correction column) |
| CON-0253 (reach vs detection) | **PROVISIONAL** | The distinction is valid. The grades merge separate axes, S0144 does not meet the book's own grade-2 standard, and "blind to omitted declarations" is too strong (W-07, W-08, W-09) |
| CON-0243 as amended | **PROVISIONAL** | The survivor clause is sound only if it names a condition. It is a second concept, not a corollary of K1 (W-10) |

## Proof steps (R4)

**CON-0251, C2's "Int" paragraph**

| Step | Justification | Defined? | Judgement |
|---|---|---|---|
| 1. A citation orders the cited entry first (readers' order respects citations) | §9.15 K2 | yes | valid |
| 2. "Citations are the only order between entries that is not act time" | none: a premise | **no**. H2 gives every entry a recording time τ, and nothing says whether τ is shared across records | missing premise |
| 3. So e is before x only if x cites e, or there is a chain from e to x | converse of step 1 plus step 2 | — | valid only with step 2. **Counter-model:** one writer stamps a global counter across both stores. That orders writes without any citation and without physical time |
| 4. "A successor of e" | position within the record | K2 v5 says readers read no position | tension; needs H2's within-record order |

**Judgement: inadequate, premise missing.**

**CON-0252, C6**

| Step | Justification | Judgement |
|---|---|---|
| 1. Every write is evidenced by an append in **the same store** | attributed to S0142 L234 | **misread.** The mechanical write goes into the *copy*; its evidence lands in the *source*, because P-1 resolves there before the switch |
| 2. A complete, current statement that includes itself is impossible | none | **false in general.** A closing entry c can say: "the final state is e1…en, which hash to h, then this entry c; the store is closed." That is complete and current. Only a hash commitment cannot cover its own bytes |
| 3. So the closing record must lie in another record | 1 and 2 | does not follow. The loop runs: source changes, copy syncs, evidence lands in the source, the source changes again. It ends when the evidence store moves to the copy (the switch), not when the statement moves out of the store |

**Judgement: inadequate, and the counterexample is demonstrated.**

**CON-0250, C1 and C9**

Steps 1–3 are valid: CON-0221, then the source's three readings, then the hold extends to containers. Step 4 (the triple) and steps 5–6 (the D clause and "on the container") fail; see W-03 to W-05.

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| 01 | CON-0251 takes K2's converse. "Without a trusted common time" does not exclude a shared logical counter | **critical** (F1: C8 and CON-0252 cite it) | K2 text; H2 (τ on every entry); the counter-model above | State the premise "no shared order source, physical or logical, across the records". Say that K2 gives only the "if" half. Reassess C8 and CON-0252 | C2, C8 and the CON-0251 row carry the premise |
| 02 | CON-0252: the counterexample, plus the source misread as a single store | high | S0142 L229–234; step 2 above | INVALID as stated. Restate as: (i) a content commitment cannot cover itself; (ii) under evidence-to-leader, a follower cannot be closed until evidence moves to it. Re-scope C6 | Register INVALID, restatement row added, C6 rewritten |
| 03 | The D clause misapplies G3. Removal "only by a recorded act" makes a removal *visible*; it does not *block* one, so D has neither half of a hold. G3's own text leaves how a removal is recorded, and by whom, open | high | §9.15 G3 full text | "D records every removal; it has no state that forbids one" | C1 and CON-0250 say this |
| 04 | "Recorded on the container" contradicts the source. S0142 puts the QUARANTINED marker on the quarantine store and gates removal on "any undisposed quarantine". What is needed is an explicit hold state that does not depend on where the item sits, plus a gate on *moves* (P7·2) | high | S0142 L129–131, L329 | Restate the requirement | C9 and CON-0250 |
| 05 | The triple is not minimal. Retaining the whole store already preserves the bytes, so the extra place is redundant there; the hold is needed for *completion*, not for preservation | normal | S0142 L122, L134 | Separate preservation from blocking | C1 |
| 06 | Naming the head decides "inside the prefix". A write outside the prefix is only "not shown to be earlier", and classing it "after" needs it to cite x. The estate also names the state at slot 3(i), not at the switch | normal | S0142 L246 | Scope C2, C8 and the CON-0251 row | stated |
| 07 | S0144 is not grade 2 by the book's own standard. The planter wrote the specification of the defect classes, the key was not sealed, and the result is self-reported (CON-0242: checkable is not checked). The grades merge three axes: novelty of the instance, independence of the planter, blinding | high | S0144 L5, L36; Rulebook §12 | Make the grades two-dimensional; record S0144 as "fresh instances, rules-author, unsealed" | CON-0253 row |
| 08 | A missing act can be caught mechanically when it is *cited*. RD-7·a (S0141's missing-act shape) was found as DI-5, a dangling reference. So "blind to omitted declarations" and S0145's "by NOTHING" hold only for acts referenced nowhere | high | S0141 L75, L348; S0143 L37 | Scope CON-0253 and C12 | stated |
| 09 | S3's "rediscovery" runs on a fixture vocabulary that encodes the target terms, so it does not even reach grade 1. The quiet AMD6 row is silent on DV-7 (P5·ALL), a class S0145 L146 assigned to the checker. A quiet row is evidence only within the implemented scope | high | S0143 L47–48; S0142 L346–352 | Add both to C12/C13 and to CON-0253 | stated |
| 10 | The CON-0243 amendment merges a second concept (which operands a gate compares) into a K1 corollary, and omits its condition: the survivor was written after its last verification | normal | S0142 L278 | Separate the clause, or label it "not from K1"; add the condition | register row |
| 11 | The exit criterion was narrowed without a record. S0145 L151–153 required AMD3–AMD5 and 17 findings, "else UNPROVEN, Phase 1 must not start". S0143 ran 2 defective commits and 5 classes. RC-1, RC-7a/b, RD-9 and DI-1·r were dropped silently, which contradicts the "no silent gap" that C13 praises (S0143 L64) | high | S0145 L151–153; S0143 L20, L26 | Record it in C12/C13 and as an S0150 instance (the same category as C19) | stated |
| 12 | C19's recount (detail in the next section) | normal | S0141 L296–298, L348 | Use the ranges | C19 |
| 13 | Lineage: S0141 wrote remedy text that AMD6 adopted verbatim (the "1·1b·…·5" block, L75; the restatement at L89; the PRE-SWITCH / POST-DEMOTION names at L421). So "the remedies are bc1b47ef's" is incomplete, and S0142's check of RD-7·b reviews text that came from the reviewer | high | S0141 L75, L89, L421; S0142 L93, L183 | Correct the "who supplied what" passes for S0141 and S0142 | lineage passes |
| 14 | Lineage: S0143 calls a longer text "the DA's formulation, verbatim"; S0145 records two lines. S0145 also moves Phase 0's authority from "NONE" (L132) to a "DA plan approval" (L286) | normal | S0143 L79–81; S0145 L237–240 | Add to the S0143 and S0145 lineage passes | stated |
| 15 | Wrong rule applications. H3b *permits* a model change if the old version is pinned, so it is not "agreement" with "don't change mid-review" (C20). CON-0071's empirical part is a hypothesis, and review was coverage (C22). C16 merges two mechanisms: a quoted enumeration (mention) and a cross-document reference resolved in the wrong namespace | normal | §9.8 H3b; CON-0071 basis; S0144 L54 | Rephrase each | stated |
| 16 | C17's "each blind where the other sees" is false: review found DI-1 to DI-7, and the checker found RD-7·a. C17 also passes "five true positives, 0 FP" when only 2 were confirmed. C14 counts the AMD5-summary S2 FAIL as a real defect, although S0144 lists it among V-3's false positives | normal | S0144 L53–54 | Scope C14 and C17 | stated |
| 17 | Homonym: §8.02 already uses "rediscovery" for re-proposing existing knowledge, and §8.02 is an existing home for C23 that the book search missed | normal | §8.02 | Qualify the term; cite §8.02 in C23 | stated |
| 18 | Small points: "two of the three steps" (there are two steps); C5's "each amendment" overreaches the window; S0142 counts itself "a sixth" process (L35), not the book's seventh | normal | — | Fix | stated |

**Not raised:** my inventory's S0142 (b), the duplicate grantId, is withdrawn. §8.19 C14 (S0137) explains same-grantId re-issue as designed, so it merits a one-line note at most.

## C19 recount and S0145
Neither recount is fully right; mine is the more consistent.
- **The book's AMD4 count keeps RD-9.** RD-9 is an undeclared act, so it falls in X-5's own class: 3/13 = 23%.
- **The book's AMD5 count of 6 includes RD-7·a and DI-5, which are one defect** (S0141 L348). Correct: 5/12 = 42%. My "11–12" denominator should read 12, since S0145's "distinct" count already merges the aliases.
- **AMD3 is also in question.** If RC-7 ("table incomplete") is a missing item too, AMD3 falls to 3/14 = 21%.
- **Result:** about 21–29 → 23–31 → 42%. The direction of the first step is undecided, and whether the trend "still rises" depends on the classification.
- **Is the attack fair?** It is fair as an attack on the source's consistency. But it treats X-5 as the correct side, and W-08 shows X-5 is itself overbroad. The book should state the inconsistency, not a corrected trend.

## Coverage, against my inventory

| Point | Mark | Weight |
|---|---|---|
| S0141 (a) remedy text by the reviewer | MISSING | high (W-13) |
| S0142 (a) BLOCKED test argued away | MISSING | normal: belongs in C7 as verdict-versus-test |
| S0142 (d) fixed-point overreach | DISTORTED: the book scopes to "prefix possible, self-including impossible"; I hold that the self-including case is also possible | high (W-02) |
| S0143 (a)/(b), (c), (d) | MISSING | high (W-11, W-09) |
| S0144 (a)–(c) | DISTORTED / MISSING | normal (W-16); the missing mutation of S3/S5 goes into C15's limit |
| S0145 (b) "zero lines", (c) single scope gap, (g) the cycle-cost counterfactual | MISSING | normal. §3.2 (cost counted in amendment cycles) is absent from the book |
| S0145 (d) | MISSING | normal (W-14) |
| S0145 (e) the freeze carve-out | JUSTIFIABLY EXCLUDED | a governance question |
| S0145 (f) catalogue mappings | MISSING | normal: ubiquitous language in C23 |
| S0145 (h) | JUSTIFIABLY EXCLUDED | trivial |
| Missing-act versus dangling-reference boundary | MISSING | high (W-08) |
| "Claim stronger than its evidence" class | partly COVERED (C3, C19) | normal: list the instances across all five sources |

## Practice checks
- **Six questions:**
  1. Order: CON-0251 rests on an unstated premise (W-01).
  2. Mathematics: W-01 and W-02.
  3. Statistics: C19 (W-12); three purposive points, no rate. C15's limit is correct.
  4. Computation: the hash claim scoped (W-02).
  5. Clarity: "rediscovery" (W-17).
  6. Coverage: see the table above.
- **Refutation adequacy:**
  - C1: partly adequate; it missed G3.
  - C2: inadequate on the converse; adequate on the DV-3 correction.
  - C6: inadequate.
  - C7: adequate.
  - C15: inadequate.
  - C19: partly adequate.
  - None of the four new concepts records a rival explanation and a falsifier, as entry 55 (c) and (d) require.
- **Depth:** we agree, five FULL. The harness times (43–86 s, writing included) cannot tell a full read from a skim.
- **C23 rule / C25 lesson:**
  - G3, K2 and CON-0071 are quoted accurately but applied beyond their text (W-03, W-01, W-15).
  - H3b is quoted, but its use as "agreement" is wrong (W-15).
  - K7, CON-0236 and CON-0238 are paraphrased; "requires" turns K7, an assumption, into a requirement.
  - CON-0127 fits, minus its truthfulness premise.
  - The DA's words are labelled correctly in S0145; S0143's "verbatim" is missed (W-14).
- **Step 9:** I checked C26 rows 01, 02, 04, 07, 08, 09, 16 and 18. All hold in §8.19 and the register: CON-0246/0247 are INVALID with CON-0248/0249 as restatements; C18's correction, C1's durability and analogy label, C13's label and C19's quote of §1.16.7 are present; CON-0243 says "necessary, not sufficient".
- **R5:** none of these findings is critical by F4 alone. W-01 is critical by F1, with C8 and CON-0252 as dependants; it calls for their reassessment, not automatic invalidation.

## Summary
1. §8.20 is faithful to its sources and its quotes check: 14 found, 0 not found.
2. The one critical finding: CON-0251 inverts K2 and needs a stated "no shared order source" premise before C8 and CON-0252 can rely on it.
3. CON-0252 is INVALID as stated. A self-referencing closing entry is possible, and the source's loop involves two stores.
4. In CON-0250, G3 gives visibility, not a hold, and "on the container" contradicts S0142.
5. The CON-0253 grades merge three axes, and S0144 is not grade 2 under the book's own sealed standard.
6. S0143's narrowed exit criterion, its fixture-fed S3 and its quiet-row miss of DV-7 are missing.
7. The lineage passes misattribute remedy text that the reviewer wrote.
8. C19's recount still double-counts DI-5 = RD-7·a and keeps RD-9. Corrected: about 21–29 → 23–31 → 42%, trend not established.

**Independence:** process independence only. I wrote the step-1 inventory before seeing the Professor's work, and I am not independent in person or in model family.
