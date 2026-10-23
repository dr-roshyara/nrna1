*Window reviewer W (Opus), steps 2-3 review report, verbatim (saved by the Professor at 2026-10-23T18:03:09Z). sha256 of the hand-back text at receipt: `43812221f92e681ad9b77b134917c87ccca9b85b0336ec33d73ceab53d69a2de` (UTF-8, the text below this line exactly).*

C25 STEP 2-3: REVIEW REPORT

I re-read my saved inventory first. The file's sha256 is `b004cb5d…`. The hash `679d4c6b…` given in its header matches the body below the header line, with no trailing newline. So the saving is verbatim.

## Summary (≤10 lines)
1. §8.18 is well sourced: the quote check gives 68 found and 0 not found, and I reproduced it. The C23 labelling holds in C15 and C16. The lineage passes are mostly right. But three of the five new concepts overreach, and the adversarial pass missed the defects that matter.
2. The largest risk is in C2 (CON-0240). Its "sharpened" point says the R2 merge rule is silent on copies whose recorder differs. §9.16 R2 says the opposite: "the recorder is the set of recorders", and the status takes the weakest copy. The pass introduced this error.
3. CON-0239 is overstated. Two replicas that agree satisfy K2, so two defaults do not make two truths. Its "CE* on a record in D" also contradicts §9.15, which says the estate's record is outside D.
4. CON-0241 ignores R2's setting. R2 merges the records of several applications; a fork of one record is a K2 failure. For any R3 law that a merge can violate, a positional conflict *is* an authority conflict.
5. CON-0243 mostly restates K1's existing "relative to a commitment"; it is a corollary. Its hash clause is wrong.
6. C4(c) attacks a strawman: the source's two shapes cover every case except the mirror case.
7. The largest omission is S0131's lifecycle discriminator. THEORY-MAP L191 already lists "Execution Context ≠ Governance Evidence Context" as missing from the book.
8. Under R4, every judgement of a derivation in the step-8 pass is "not assessed", because no proof-step table was given.

## Concept verdicts

| CON | Verdict | Reason and scope |
|---|---|---|
| 0239 | **PROVISIONAL**, restate | The core holds: writer exclusivity does not designate the authoritative store. Wrong as stated in two ways. "Two resolutions give two truths" fails for byte-identical replicas (a counterexample within scope). K2 requires agreement on the entries, not one store, so a fixed record identity is sufficient for K2, not presupposed by it. "CE* on a record in D" conflicts with §9.15 L196 ("the present estate's record, which is outside D"). |
| 0240 | **PROVISIONAL**, restate | "Bytes ⇒ identities, not conversely" holds only with one fixed decoder from bytes to D's fields. That premise is missing, and it is S0134's own interpreter axis. The converse fails correctly. The recorder/status sentence is false against R2. The conclusion (verify the migration on bytes) survives. |
| 0241 | **PROVISIONAL**, scope | It is right for acts governed by no law a merge can violate. For single-use grants and held acts (R3, R3a), two contents at one position can be a genuine authority conflict, which is the estate's escalation. It also misses that R2 assumes several applications' records each in D, while a prefix disagreement is a fork of one record. |
| 0242 | **PROVISIONAL**, restate | (a) The list is distorted: S0133 L38 checked "against the commission's own list", and L11's "a checklist the author wrote to" means written *against*, not authored. (b) "Checked" has two meanings (performed vs independently re-run); qualify it. (c) "Another party" needs independence of stake, not only of authorship. See W-09. |
| 0243 | **PROVISIONAL**, mark as K1's corollary | K1's external note already says "relative to a commitment kept outside" (§9.15 L74, L254). What is new is re-taking the anchor at the operation, and that a count is not a commitment. "Only a list or a hash shows **which** entry is missing" is false for a hash, which shows *that* one is missing, not *which*. An entry lost after the anchor is still detectable from the record if a numbered successor survives (K1, I-07). So limit the claim to an uncited tail or a whole record. |

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| 01 | C2 and the step-8 pass: "R2's merge rule … is not stated for copies that differ in the recorder" is false | high | §9.16 R2: "the recorder is the set of recorders"; status "the **weakest**" | Delete the sentence. Say instead that under union the change shows as a set of recorders (visible). A migration replaces copies rather than taking their union, so byte verification is still needed. | C2, CON-0240 and the step-8 row restated |
| 02 | CON-0240 misses a premise: one fixed decoder from bytes to fields. R5 F4 holds (the book marks the interpreter as not established: C15, §8.18.3), so by rule this is **critical**. No dependants were found (register and Ch. 9 grep), so the cost is one premise. | critical (R5 F4) | S0134 L87 "right bytes + wrong interpreter"; §8.18 L138 | State the premise in C2 and in the register row. Link it to C12, C15 and K7. | Premise in the statement and the row |
| 03 | CON-0239 overstated (replica counterexample). Its D-membership claim conflicts with §9.15. The text says "one store, read by two readers whose defaults name different stores", which contradicts itself. | high | §8.18 L37; §9.15 L75, L196 | Restate as: "diverging resolutions give two views with no fork in any single store; hypothetically, records in D" | C1 and the row restated |
| 04 | CON-0241: R2's multi-application setting is transplanted onto a fork of one record. The R3-law limit is understated ("recorded, not prevented") although a merge can violate G4. | high | §9.16 R2 setting and R3; §9.15 K2, K4 | Scope the concept to acts under no merge-violable law. Name the fork as a K2 failure. | C3 and the row restated |
| 05 | C4(c) is a strawman. Strict extension plus prefix disagreement (defined over the overlap) cover every pair of sequences. "Both extended" counts as a prefix disagreement. The real gap is the mirror case (evidence longer) and the equal case. The book's condition "only if the common prefix covers the shorter side's whole length" is reversed: that condition defines a strict extension. | high | S0131 L118–119; §8.18 L52 | Replace with the mirror case, reachable through `--dir` (S0131 L160) | C4(c) rewritten |
| 06 | Omission: S0131's lifecycle discriminator ("written once" vs "rebuilt on demand", so "two lifecycles ⇒ two locations") and O-3 (no derived state is persisted). The map already lists the topic as not yet in the book. | high | S0131 L45–52, L24; S0132 L50–92; THEORY-MAP L191 | Add a claim tied to G3 (the record's form) and the map item | §8.18 claim added; map item closed or updated |
| 07 | C4(a) covers only half the defect. The test also **cannot be met** by the "preserve both" outcome the source mandates, since two entries at one seq in one record are not monotonic. | normal | S0131 L119, L121 | Add to C4(a) | C4(a) amended |
| 08 | Omission: VERIFY conflicts with the window. A hash failure "stops the migration" (L72), yet appends during the window are expected (L83–90). Either the migration never completes under live writes, or VERIFY compares with a snapshot and cannot see the window. | normal | S0131 L72, L83–90 | Add as C4(d) | C4(d) added |
| 09 | Independence of S0135 is limited. Its conformity check (L61) judges how the plan incorporates an extension whose wording its own author drafted (S0134 L49). C21 and C23 note only declared independence. | normal | S0134 L49; S0135 L61 | Qualify C21 and C23 and CON-0242: authorship independence is not stake independence (mechanical counts are unaffected) | Text and row amended |
| 10 | C23 rule broken twice. C17 attributes the D2 table's prose to "the decision"; C19 attributes "Execution is gated on…" to "the decision". Both are the registrar's text under the headings "as decided" and D5. Only the italic fragments (L87, L94) are the authority's words as quoted. | normal | S0134 L89–94, L112–121 | Label both as the registrar's gloss | C17 and C19 relabelled |
| 11 | S0134 L30 cites the self-review as "the Governance review". Its author was `1c8b041b`. The S0134 lineage omits this, and C12 quotes "Governance must not determine it" without noting the speaker. | normal | S0133 L7, L126; S0134 L28–30 | Add to the S0134 lineage | Lineage amended |
| 12 | Scope widening is unexamined. S0130 L68 lists the migration subject as two work items, while S0131, S0133 and S0135 migrate every record. This is a test of C5 and C16 (a widening needs its own act). D1 is general (S0130 L22), so the row may be only illustrative. | normal | S0130 L22, L68; S0131 L71 | Record as OPEN under C5 | Note added, or a later source settles it |
| 13 | Omission: append-only is logical, not physical. `saveRecord` rewrites the whole file on every append, so K1's "no entry altered" rests on the writer, not on the form. | normal | S0131 L22, L47, L64 | Add a note to C1 and K1 evidence | Note added |
| 14 | C18 overclaim not attacked. "Exactly the measured evidence of insufficiency R-37 requires" (S0134 L20): a line address shows that the coupling exists, not that the current handling is insufficient. Also, C-2 was not read; it is the registrar's report. | normal | S0134 L19–20 | Add an attack, and mark C-2 "as reported" | C18 amended |
| 15 | Settlement row I-11 is applied in §8.13 C5. But CON-0206's meaning still says "The converse of I-7". | normal | register CON-0206 meaning | Remove "converse" | Row fixed |
| 16 | THEORY-MAP still says the last source read is S0130 and has no §8.18 entry | normal | THEORY-MAP L353 | Update at settlement | Map updated |
| 17 | The path-source count disagrees: "three sources" (S0135 L19) vs P-1…P-4 with P-3a/b (S0133 L23) | normal | as cited | Mark it pending S0300 in the coverage list | Listed |

## Rulebook §6 (on §8.18)
1. **Order:** C1 uses "D-membership" against §9.15 (W-03). Otherwise terms are defined before use.
2. **Mathematics:** W-01, W-02, W-04, W-05, W-07.
3. **Statistics:** not applicable; there are no quantitative claims beyond counts.
4. **Computation:** I reproduced the quote check (68/0). Note its limit: 67 paraphrases marked "in meaning" are not machine-checked. I hand-checked five against the sources (C1 L33, C4 L83, C9 L26, C17 L94, C20 L93), and all match.
5. **Clarity:** the sentence in C1 that contradicts itself, and the reversed condition in C4(c).
6. **Coverage:** W-06 to W-09, W-12 to W-14, W-17.

**Refutation adequacy (entry 55; R4, R5).**
- C4(a), C4(b), C9 and C17 are adequate.
- C2 is inadequate: the attempt created W-01 and missed W-02.
- C1 and C3 are inadequate: they missed the replica counterexample and the R2 setting.
- C10 and C20 are inadequate: they did not compare with S0133 L38, nor with K1's wording "relative to a commitment".
- C16 is adequate, with its stated condition.
- No proof-step tables were given, so every Ded row is "not assessed" (R4).

My strongest counterexamples are the replicas (W-03), the single-use double use (W-04) and the mirror shape (W-05).

**Depth.** We agree: FULL on all five sources. For S0132 I had judged the business section L341–706 as SKIM. The Professor's FULL reading of it produced C9 (L401–407). It is justified. On timing: the adversarial-pass edits came after each source's close time, so the per-source times leave them out.

**Pending marks.** The three pending items (S0300, the design's acceptance, the interpreter recorded) are the right ones. Three more should be pending: the path-source count (W-17), C-2 as reported (W-14), and the scope row (W-12). No claim exceeds a pending item, except C22's "Phase 5 conforms", which rests on S0300. That is covered by the S0133/S0135 header only; repeat the mark inline.

**Spot-check of the previous settlement (§2):** I checked 10 rows.
- Held: A-13 (K1, stated once), A-03 (CON-0202 INVALID, CON-0238), A-02 (CON-0208 VALIDATED, CON-0213 PROVISIONAL), A-01 ("silently" in CON-0237), I-09 (R2 (d)), B-03 (CON-0236), A-07/A-18 (K4-o as a pointer to L9), B-04 (§8.6 corrected), A-19 (S0117 L211; "permanently" labelled).
- Failed: I-11 in the register (W-15).

## Coverage, from my inventory
- **Covered:** RA-2, the hidden second truth, `--dir`, byte copy, order, window, "no automatic winner", the two shapes, Option D, the summary's statuses, the resolver, migration evidence, who/when, self-review limits, INFO-1, INFO-2, OPEN-M2, OPEN-M3, OPEN-M4, the premise correction, explicit extension, "decided ≠ remediated", two gates, the stale literal, the reviewer's own error.
- **Missing:** W-06, W-08, W-12, W-13, W-14.
- **Distorted:** CON-0242's "producer's own list" (W-09 area).
- **Justifiably excluded:** S0133's missing §0 (nothing rests on it); S0132's "compatible ⇒ merge" gloss (it adds nothing beyond C3).

**Process independence:** independent in process only. My blind inventory was saved before I saw the book, and I am the same model family as the Professor.

---
### Appendix: proof-step tables (R4)

**CON-0240**
1. Identity = f(kind, required fields other than the recorder and the status) (R2). Defined.
2. Byte equality ⇒ field equality. **Premise missing: one fixed decoder** (W-02).
3. Steps 1–2 ⇒ equal identities. Valid given step 2.
4. The converse fails: whitespace differences, and recorder or status edits. Valid.
5. Hence byte-level verification. Valid, independent of step 2.

**CON-0243**
1. The anchor commits to a set S at time t. Defined.
2. An entry e added after t is not in S. Valid.
3. Hence the loss of e is not detected *by comparison with the anchor*. Valid.
4. "Only a list or a hash shows which entry": **invalid for a hash.**
5. The book's wording "not detected" without scope **ignores K1 I-07** (a numbered successor reveals the loss).

**CON-0241**
1. Content identity ⇒ the two entries are distinct acts. Valid (R2).
2. The union keeps both. **R2's setting is several applications; this premise is missing** (W-04).
3. CE* gives T, F or U. Valid for the value.
4. "No authority conflict" **fails for R3 laws.**

