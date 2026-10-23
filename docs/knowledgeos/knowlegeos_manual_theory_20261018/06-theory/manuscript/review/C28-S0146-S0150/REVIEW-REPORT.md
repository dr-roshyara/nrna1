*Window reviewer W (Opus), steps 2-3 review report, verbatim (saved by the Professor at 2026-10-23T19:03:58Z). sha256 of the hand-back text at receipt: `f860e3676a4b3aa40247092b0bc09fd06279fc113977f11009c6512810df6423` (UTF-8, the text below this line exactly).*

C28 STEP 2-3: REVIEW REPORT

*Re-read first: `review/C28-S0146-S0150/STEP1-INVENTORY.md`. With its header line removed and no trailing newline, the body's sha256 is `71c87c03…b9f2`, the value you stated, so the saved text is verbatim.*

## 1. Assessment
§8.21 is reliable as a record of evidence for C2–C6 and C10–C12.
- **Largest risk: the one new concept, CON-0255, is false as stated.** A check whose reference is derived only from the checked state *can* fail: an integrity check fails when the bytes no longer match the hashes recorded with them. One of its three conditions is not needed, and its falsifier is written so that no case could ever refute it.
- The skim of S0149 lost theory, so the claim "every distinction already in the book" is false.
- Two citations rest on concepts the register marks INVALID.

## 2. Findings (ranked)

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| 01 | CON-0255 / C1 "cannot fail" needs an unstated premise: the comparison holds for (X, f(X)) for every state X. Without that premise it is false. **Critical by F1:** CON-0255 rests on this derivation (no other dependants). | crit | My small model (`scratchpad/con0255.py`): a check derived only from the copy's own content and its recorded hash fails in 2 of 4 cases. The *self* reference gave 64/512 correct verdicts. | Restate: a reference that is a function of the checked state alone **cannot detect that the survivor differs from its intended state**; it can still catch internal inconsistency. State the premise. | C1, its Ded and the CON-0255 row (or a restating CON) carry the premise and the narrower conclusion. R3 is run. |
| 02 | The three conditions are not minimal. "Closed before the act" is not necessary. | high | Same model: a source-derived reference closed *after* the act gives 64/64 correct verdicts while the source is frozen. It gives 374/448 when the source moves, and that failure breaks "cover exactly the changes", not timing. The order in HO-1 comes from a data dependency ("(iii) takes it as its target", S0146 L207–212). The source says "the strict reading survives" (L220). | Keep two conditions: (i) the reference is exactly the intended state; (ii) it comes from an input independent of the survivor. Record the ordering as a separate note on executability. | CON-0255 has two conditions plus the ordering note. The STEP8 C1 attack row is redone. |
| 03 | The falsifier and the rival are inadequate (entry 55 (c), (d)). | high | Falsifier: "In that case the concept's third condition is met, not refuted" (§8.21 L50). That makes it immune to refutation. The rival ("a careful reader avoids it") is weak; the source's own stronger rival is a dependency inversion. Support is one lineage (`dd639043`: S0142, S0146). | Falsifier: a check whose reference is a function of the survivor alone yet detects divergence from the source. Rival: dependency inversion; the test-oracle problem (Ext). Under the §4 generalisation rule, limit the claim to replacement gates, as Hyp beyond them. | Register row: new falsifier and rival, scope "replacement gates; one lineage". |
| 04 | C15 calls CON-0085 "the book's home" that "governs". CON-0085 is **INVALID**. **Critical by F4 only, no dependants.** | crit | Register: CON-0085, INVALID (review C6: no sufficiency condition). | Give CON-0085's status. The tension is between two necessary-condition forms, neither of which has a sufficiency condition. | C15 gives the status and drops "governs". |
| 05 | C13 cites CON-0045 (INVALID, and about *inert vs attested* projection) as a home for "graph is a projection". **Critical by F4 only, no dependants.** | crit | Register: CON-0045 | Cite §8.17 C5 only. | C13 no longer cites CON-0045 as support. |
| 06 | C13's mappings distort: (a) S0149's "ACTIVE requires owner, evidence, approval, validation" is a **necessary** condition, while CON-0199 is a **sufficient** one; (b) CON-0244 is about evidence vs execution state, while S0149's pair is evidence vs product. | high | S0149 L211–218, L283–289. Register rows CON-0199, CON-0244. | Rewrite C13 and drop "already in the book". | C13 states each relation correctly. |
| 07 | **The skim of S0149 lost theory** (§3 safeguard). | high | Theory map L294: "knowledge product" appears only from EXT-015. S0149 L28–34, L96–137 is corpus evidence for the R_K fork. Also lost: contradictory aggregate boundaries (L141–158 vs L569–610); "authority" as a field (L259, L580, L623) vs "comes from Governance" (L432–436), which CON-0016 could separate; the ACTIVE invariant with no owner; aggregates with fields and no commands (they pass the trivial-solution test); the context map vs the final picture (L459–495 vs L714–754). | Add S0149 to the R_K fork as Src (non-authoritative). Record the DDD defects as a source-defect note. | Theory map L294 cites S0149. §8.21 has a short S0149 defect note. |
| 08 | C9 accepts S0148's "VERIFIED" without qualification. | high | The status is self-issued by an unnamed producer (L6, L159). The validation is in-sample: the oracle was built from known findings, and the "new draft" was written for the report (L95), which is CON-0253's case. "Corrected… 0 false positives" (L94, L121) is stated while AMD6 carried DV-1…7 (S0146 L264). Compare the estate's bound against self-closure (S0146 L120); its scope is undetermined. | C9: "verified (self-issued; shows reach, not detection; CON-0253); quiet means within scope". | C9 and C12 carry the qualification. |

**Appendix findings (normal).**
- **W-09.** C8 quotes §9.13's *information* order. The aggregate is the minimum in the *truth* order (F < U < T), so the quote is not the rule relied on (the C25 lesson). Boundary case: in strong Kleene an empty conjunction, or a vacuous "for all", is T. The estate gives INCONCLUSIVE to a slice with nothing to assess (S0148 L96: S2 goes FAIL → INCONCLUSIVE after remediation). So the identity holds only for aggregation across slices. Drop "independently".
- **W-10.** C14: "2 of 216" is S0142's figure (§8.20 C8); §8.17 C6 has 2 of 210. Commits do carry dates, which the claimant controls, so the premise is "L6 not assured", not "vacuous". The title overstates: the source's ledger has a "Repository" field (L265), which could be an external commitment. The stronger attack comes from the source itself: "Evidence ≠ Proof" (L475) against "later you can prove" (L271).
- **W-11.** C1's "§8.20 C11's 'self-satisfying'" should point to §8.19 C11 and CON-0243.
- **W-12.** C7 misses that "acceptance" appears in the Governance Engineer's label as well as on the PO/ARB edge (the record has PO/ARB accept, S0146 L162). It also misses the role/actor merge (roles drawn as "person" while an external AI runtime executes the work). The "assures" attack is weak, since assurance given as evidence is compatible with "recommendation only".
- **W-13.** S0146 gaps: the "second artifact" propagation (L78, L121, L177), which is evidence for CON-0232. Source defects: §7 comes before §6; "four traps" against six delta rows. C2–C6 all come from one lineage with S0142.
- **W-14.** S0148 source defects: "All four runs" against five runs (L105 vs L90–96); "byte-verified" using line counts (L88); L131 against L150.
- **W-15.** S0150: the second turn corrects the first (L490 vs L571–577), and its anti-pattern applies to S0149's seven contexts. The internal model list (L555–564) changes S0149's contexts.
- **W-16 (step 9).** CON-0250 still says "a hold on what contains it" next to "independent of where the item sits". W-04 was only partly applied.

## 3. CON-0255 verdict
**INVALID as stated** (W-01 to W-03). Proposed restatement, **PROVISIONAL**: "a reference that is a function of the checked state alone cannot detect that the state differs from its intended state; to detect that, the reference must be exactly the intended state and must be computed from an independent input". It counts as Ded only together with the premise; one lineage; replacement gates only.

**Proof-step table (C1).**

| Step | Justification | Defined? |
|---|---|---|
| R = f(X), X the post-act state | definition | yes |
| the check passes iff R(X, f(X)) | CON-0243 | yes |
| R(X, f(X)) holds for every X | **unstated premise** | no |
| so it cannot fail | from the steps above | inadequate: premise missing |

**Proof-step table (C8).** Mapping (stipulated) → truth order (Ext Kleene; **not the order quoted**) → WARN added as a level → minimum is commutative → depends on the set, not the order. Adequate for aggregation across slices only (W-09).

## 4. Rulebook §6, six questions
1. **Order:** cross-references slip (W-11), and two INVALID homes are cited (W-04, W-05).
2. **Mathematics:** W-01, W-02, W-09.
3. **Statistics:** C10 is correct; the next-review metric is n = 1, which gives counts, not rates.
4. **Computation:** small model run (above); quote check 5/0 (rerun).
5. **Clarity:** "other side" is defined only for copies.
6. **Coverage:** below.

**Adversarial pass:** C10 adequate; C8 adequate in scope; C1, C14 and C15 inadequate. The C1 attack tested the wrong direction of necessity. C15 checked the form but not CON-0085's status.

## 5. Coverage (from my inventory)

| Item | Mark |
|---|---|
| Comparison object (S0146 1) | DISTORTED (W-01, W-02) |
| Coverage is not the number of gates (S0146 2) | OPEN (§8.21.3) |
| Reasons load-bearing (S0146 5): DV-4 / DV-6 | COVERED (C2) / COVERED in §8.20 C9 |
| DV-3 vocabulary | COVERED in §8.20 (CON-0251 history) |
| Second artifact; section-order and count defects (S0146) | MISSING (W-13) |
| Tool scope (S0146 9) | COVERED (C6) |
| S0148: false negatives, in-sample validation, self-issued VERIFIED | MISSING (W-08) |
| S0148: carried trend | COVERED (C10) |
| S0148: FAIL → PASS | COVERED (C11) |
| S0148: count and line-count defects | MISSING (W-14) |
| S0149 | MISSING (W-07) |
| S0150: git as authorship | COVERED (C14) |
| S0150: second turn corrects first | MISSING (W-15) |
| S0147: acceptance in two places; role/actor | MISSING (W-12) |

## 6. Skim and depth

| Source | Professor | Me | Did the skim lose theory? |
|---|---|---|---|
| S0146 | full | full | — |
| S0147 | skim | skim | Yes, a little (W-12) |
| S0148 | full | full | — |
| S0149 | skim | **full** | **Yes** (W-06, W-07) |
| S0150 | full | skim, plus the 2nd turn in full | — |

At 33–61 s per source with writing included, harness time still cannot tell a full read from a skim.

**Step 9.** I checked these C27 settlement rows against §8.20 and the register: W-01, W-02, W-03–05, W-07–09, W-10, W-12, W-15, and the register count (255: 40 VALIDATED / 118 PROVISIONAL / 97 INVALID). All hold except the remnant left in CON-0250 (W-16).

**Running measure.** 16 findings. 3 critical: W-01 by F1; W-04 and W-05 critical by F4 only, no dependants. Material omissions: 3 (W-07, W-08, W-13). One later-found defect in settled work: W-16.

## 7. Summary
1. CON-0255 is INVALID as stated; it should be restated narrower, with two conditions.
2. Its falsifier is written so that nothing could refute it; its rival is weak.
3. C13 and C15 cite INVALID concepts (CON-0045, CON-0085). Each is critical by F4 only, with no dependants.
4. The skim of S0149 lost the corpus evidence for "what the kernel manages" and a set of DDD defects.
5. C9 takes a self-issued, in-sample VERIFIED at face value.
6. The identification in C8 holds for aggregation only; the quoted rule is the wrong order.
7. C10 and C11 are sound.
8. Step 9 holds except the CON-0250 remnant.

**Limits.** I read no sources outside the §8.21 homes, the settlement and the register rows. S0300 and later-chain sources were not read. The model is a small-scope check, not a proof. I checked no claim about the Principal.

**Independence:** this is process independence, not personal independence.
