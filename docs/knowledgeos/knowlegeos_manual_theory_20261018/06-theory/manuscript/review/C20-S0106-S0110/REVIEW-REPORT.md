*Window reviewer W (Opus), steps 2-3 review report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T22:43:43Z). sha256 of the hand-back text at receipt: `926766c93463788016949612a4fbbf2f74b2644dbc22803356de6940b4ba777a` (UTF-8, the text below this line exactly).*

C20 STEP 2-3: REVIEW REPORT

**Before comparing, I re-read my saved inventory.** `STEP1-INVENTORY.md` has one header line added above the text. I recomputed the hash on what follows that header (line 3 onward, no final newline) and got 68c840b6…ad8, which matches the hash you recorded at receipt.

**Scratch evidence.** The counterexamples below come from `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c20w_owner.py`, run with `python3 -I`. It imports ce_model v11 unchanged. Run lines W1–W6 are cited where used.

## 1. Assessment
- §8.13's source extraction is faithful, and its depth judgements match mine.
- Three of its interpretations, which link the sources to the kernel, are wrong as stated: C1's citation gloss, C2's kernel Ded and C4. The C5 claim "CE* agrees" is overstated.
- The application of F22 = (C + O) is the larger risk. The guard rests on a declared application field that no one authenticates. A transfer is authorized without reference to the grant it moves. One U transfer freezes a grant for good, which defeats the remedy the Principal named ("unless ownership is moved"). And the claim that the kernel's owner check "is not an evaluation of CE*" is false in the model.

## 2. Findings

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-1 | **One U transfer freezes the grant permanently.** `owner_at` returns "undetermined" at the first U transfer and ignores later T transfers. An authorized move recorded at the kernel therefore does not restore use. This contradicts R4a's "the cost … holds in the model" and the remedy "unless ownership is moved". The U does not need a bypass: a transfer that is T when appended becomes U when its basis is ended at the same act time. | high | W2b: m1 is T at append and then U; m2 (by P, at the kernel) is accepted; a use by A3 is refused: "transfer m1 … is U" | Define the fold past a U (for example: a later T transfer whose authorization does not depend on m1 sets the owner), or state the freeze as a third cost | `owner_at` revised or the freeze stated; a check with U → T added |
| W-2 | **A transfer's authority is not scoped to the grant it moves.** "Evaluated like any act's" checks the transfer's own `scope` field, which is free. An actor with authority only on s/b moves g (scope s/a). Separately, the grant's holder can move its own grant's owner by citing g. This breaks the book's own CON-0154 ("authoritative for which claim"). "Authorized" in the decision is left open, and filling it is normative. | high | W4b: Z (s/b only) moves g (s/a) to A9: (True); W5: holder A moves g to A5 citing g: (True) | Tie the transfer's scope to covers(scope(g), ·). Return to the Principal who may move ownership: the issuer, the holder, or the current owner (S0071 DP-3) | the transfer rule is stated and decided; a check added |
| W-3 | **The application field is a declared claim, and the guard trusts it.** "Recorded by its owner" is decided from the entry's `app` field. A non-owner that writes app=A1, or app=kernel on a transfer, is admitted. The corpus prior says exactly this: attribution is "claims recorded, not identities authenticated" (S0071 L72, INV-ATTR-2). No K or L assumption in §9.15 v7 covers it. | high | W1: (True, None) for both | Add a located assumption (the kernel authenticates the submitting application, as an L2-type binding) and make G4 under (C + O) depend on it | §9.15 lists it; G4 cites it |
| W-4 | **"That check … is not an evaluation of CE*" (R4a) is false in the model.** `owner_at` reads `wfs()` values of the grant's transfers. Those values depend on their bases and on later annulments and endings, so they are non-monotone. | high | ce_model L806–822; W3: a transfer annulled later → owner reverts | Restate: the owner check evaluates CE* for the grant's transfers. Either snapshot the value at append (and say how that differs from CE*), or concede the cost | R4a and K4-k restated |
| W-5 | **The owner mark judges a past use by the present values of the transfers.** After a transfer is annulled, a use that passed the guard is marked "not recorded by its owner". | normal | W3: mark on k (by A2, owner A1) | Evaluate the owner at the use's position with values at that cut (`ce_star_cut`-style), or relabel the mark | check added |
| W-6 | **C4 / CON-0205 is contradicted inside the window.** In S0109 the recording process wrote the PO/ARB's selection into the record and did not thereby decide. L28: "The selection is the PO/ARB's own act". So writing a selection is not deciding; supplying one not present in the decider's act is (S0108 L8). The Professor's own attempt (a) names this rival and then does not apply it. This is a transcription case (signed-as-other), not a case for P-perf. | high | S0108 L8; S0109 L28 | CON-0205 INVALID as stated. Restate as "the origin of a selection, not the writing of it, makes the decision"; drop the claim that this supports P-perf | §8.13 C4 and register |
| W-7 | **C2's kernel Ded fails on D as it stands.** (a) Targets "are not restricted" and may never arrive (§9.13 L55), so a required reference can lack its referent while the record is in D. (b) `owner` and `new_owner` name applications, and D records no applications at all. (c) `in_D` does not check that a transfer's `grant` names a grant. (d) The source's gap is a performer or owner gap (ContextSelected is a defined event that nobody performs), not a kind that "can [not] occur". The Ded tests the wrong property. | high | ce_model L157–183; S0109 L130 | Drop "D's present kinds meet this". State that D has referent gaps by design (forward targets, applications) and ask how Q1 answers for them (already the fourth open question) | C2 restated |
| W-8 | **C1's citation gloss over-reaches.** A citation in D does assert something: that its target exists and is earlier, and D refuses otherwise. It asserts nothing about the target's soundness. This is the Professor's own rival (c) and CON-0203's point, and it was not carried into C1. | normal | §9.13 (dangling citations refused); in_D L176–182 | "A reference asserts the existence of its referent, not its correctness" | C1 and CON-0202 reworded |
| W-9 | **C5's "CE* agrees" is overstated.** CE* has no conflict state. Two adoptions of item c are OR-ed, and their contents are not read. A revocation names an item, not an act, so a later revocation defeats every earlier adoption without a link. "Without the link, a later act does not supersede" is true of adoptions only. | normal | `current()` L387–409 | Scope it: CE* has no later-wins rule for adoptions, but it does not represent the conflict F24 needs, and revocation is item-wide | C5 restated |
| W-10 | **CON-0202 merges two concepts.** It defines "reference ≠ assertion" and also restates claim-scoped authority, which is CON-0154. One register row should carry one concept. | normal | register rows 155, 203 | Point to CON-0154 | register |
| W-11 | **"Read field" now has two senses.** The identity is "derived from the fields CE* reads" (CON-0194), but READ_FIELDS now includes `owner` and `app`, which Φ does not read (24g). | normal | ce_model L768; R4a "Merging" | Qualify: read by Φ vs read by the guard and marks | R4a and CON-0194 |
| W-12 | **"Use" = consumption.** The reading is supported by R4 v4's option text ("an owner application per grant for its consumptions"), not by §1.13.2. Its effect is that the owner controls at most one event per grant, and adoptions under g go unchecked (24d). | normal | R4 v4; check 24d | Cite R4's option; ask the Principal to confirm the scope | confirmed |
| W-13 | **The second cost needs a premise.** "A kernel step on every use" follows from "the kernel refuses" only under prevention at append with L9. Without L9, the kernel's refusal could come after the use. | normal | §9.15 L9 | Add L9 to the deduction | R4a |
| W-14 | **The book's lineage count.** S0137 and S0183 are one line of support (the same migration-plan series). S0071's own rule (DP-3) is the one rejected, so the corpus prior for the transfer reading is adverse, not supportive. "Follows S0137 L172" for record order is Int: that line is about a store's writer switch. | normal | S0137 L172; S0183 L175 | Label as Int and count the lines of support | R4a |

## 3. Proof-step tables (R4)

**C2, "for the kernel (Ded, trivial)"**

| Step | Claim | Justified? |
|---|---|---|
| 1 | A required field citing a kind never recorded ⇒ no entry of that kind in D | yes, if D refuses unfilled references |
| 2 | ⇒ every required reference must name a kind that can occur | yes (contrapositive) |
| 3 | D's present kinds meet this | **no**: targets may be forward or never arrive; applications are never entries (W-7) |
| 4 | Fit to S0109 | **no**: the source's case is "can occur, but nobody performs it" |

Verdict: steps 1–2 hold and are trivial; step 3 is false; the derivation does not capture the source. Missing premise: severity high.

**R4a, "a second cost: a kernel step on every use"**

| Step | Claim | Justified? |
|---|---|---|
| 1 | The kernel refuses a non-owner use | decision |
| 2 | Refusal is at append (prevention) | Professor's reading; consistent with decision 1 |
| 3 | The use is not delivered before acceptance | **hidden premise L9** (W-13) |
| 4 | ⇒ a kernel step is synchronous with every use | follows from 1–3 |
| 5 | The step "is not an evaluation of CE*" | **false** (W-4) |

**R4a, "the cost holds in the model"**

| Step | Claim | Justified? |
|---|---|---|
| 1 | Owner unavailable ⇒ others refused | check 24b, yes |
| 2 | An authorized move restores use | **only if no transfer of g is U**; W-1 is a counterexample |

**Check 24 adequacy:** it consists of hand cases only, and its random part, 24g, contains no transfers. It **admits** uses that it should refuse in the intended sense (W1 spoofing, W4b transfer under unrelated authority). It **refuses** a use by the owner after an authorized move (W2b).

## 4. Verdicts

| Concept | Verdict | Reason and scope |
|---|---|---|
| CON-0202 | PROVISIONAL | Source extraction holds for one decision; C1's citation Int needs W-8 |
| CON-0203 | PROVISIONAL (Src); kernel Ded **INVALID as stated** | W-7 |
| CON-0204 | PROVISIONAL | sound analogy; it omits S0107 L53 and S0106 L59, which support it |
| CON-0205 | **INVALID as stated** | S0109 L28 counterexample (W-6) |
| CON-0206 | PROVISIONAL | Src holds; "CE* agrees" must be scoped (W-9) |
| CON-0191 v10 (owner, transfer) | PROVISIONAL | W-1, W-2, W-4, W-5 |
| CON-0193 v7 (K4-k/K4-o, G4) | PROVISIONAL | missing application-authentication assumption (W-3) |
| CON-0194 v5 R4a | PROVISIONAL | second cost valid with L9; "not CE*" invalid |

## 5. On A, the application of F22 = (C + O)

| Question | Answer |
|---|---|
| Applied as worded? | **More than worded:** transfers only at the kernel; refusal while the owner is undetermined; a new detection mark. **Less than worded:** "use" narrowed to consumption; "authorized" not given a scope (W-2). |
| Reading 1, "use" = consumption | technical, with R4's option as its source (W-12); its consequence should be confirmed |
| Reading 2, "recorded" = at the kernel | sound against DP-3, because the cost sentence presupposes that an absent owner can be replaced. But who is authorized to transfer is **normative**, and it is unresolved (W-2) |
| Reading 3, record order rather than act time | technical for a guard that prevents at append. It is an asymmetry with endings, which are ordered by act time: state it |
| EXT-090 gloss | within what was verified, since it is labelled "recalled, not checked" |
| S0071 / S0137 / S0183 | used in their meaning, with the caveats in W-14 |

## 6. Questions 1–6 (on §8.13 and A)

1. **Order:** fine, apart from the stale §9.13 L347 tool pointer (it still names the v10 run).
2. **Mathematics:** see the tables in §3.
3. **Statistics:** not applicable, except the lineage count (W-14).
4. **Computation:** W-1, W-4, W-5; check 24 is too narrow.
5. **Clarity:** "read field" (W-11); "authorized" for a transfer (W-2).
6. **Coverage:** see §8 below.

**C3's link to the marks:** an interpretation the evidence allows (Int).
**C4:** a governance convention about the origin of a selection, not a case for P-perf (W-6).
**C5:** "CE* agrees" is true for adoptions only (W-9).

## 7. Refutation adequacy (the step-8 table)
- **Adequate:** CON-0204; C6; C7; and CON-0202's attempts (a) and (c), though (c) was not acted on.
- **Inadequate:**
  - CON-0203: it missed the forward targets and applications already in D.
  - CON-0205: rival (a) is realized at S0109 L28 and was left unapplied.
  - CON-0206: it did not test item-wide revocation.
  - R4a: no counterexample search on the guard at all.
- **Strongest counterexample I found:** W2b.

## 8. Coverage, from my blind inventory

| Item | Status |
|---|---|
| Taint without retraction (S0107 L53); inheritance downstream (S0106 L59) | MISSING (supports C3) |
| "Never infer PASS from absence; NOT ATTESTED" (S0107 L85) | MISSING here, possibly elsewhere in the book (not checked) |
| A required property whose content is deferred (invalidation semantics must exist; what they are is D3's, S0109 L125) | MISSING: a deontic "Required" with open content |
| A decision-authority boundary is not a refusal; no gate reached (S0108 L8) | MISSING |
| Refusal as evidence, "no ownership inferred" (S0106 L45); a refusal is not clearance (S0107 L67) | MISSING |
| Disclosed, not cured: a party recording an adverse disposition about itself (S0107 L16) | MISSING (it bears on I15's recorder) |
| A within-source supersession recorded as an explicit diff (S0109 L96, L114–121) | MISSING: a corpus instance of I-7 that would support C5 |
| Necessary is not sufficient for a bounded context (S0109 L84); schema follows semantics (L67) | MISSING, low |
| Option C folds "selected" into "delivered" (S0108 L24) | MISSING, low |
| Source slip: "fifteen deliverables" against 13 rows (S0106 L15) | source defect, not relied on |

**Depth:** we agree on all five sources: full, with S0106 borderline in both judgements. No source closed as full deserved skim.

**C19 settlement, spot-checked:** W-3, W-4, W-9, W-10, W-13, T1-03 (L9), T1-08 and T2-13 are met where the settlement says. I did not check the other rows (see Limits).

## 9. Summary
1. §8.13 extracts its sources faithfully.
2. CON-0205 is INVALID as stated: the window itself contains a recorder writing a selection without deciding it.
3. C2's kernel Ded is INVALID as stated: D already admits forward targets and application references that have no referent.
4. C1 should read "asserts existence, not correctness".
5. C5's "CE* agrees" holds for adoptions only.
6. (C + O) as applied has three high defects: the U-transfer freeze, transfer authority not scoped to the grant, and an unauthenticated application field.
7. The owner check does evaluate CE*.
8. Who may transfer a grant is normative and goes to the Principal.
9. Check 24 needs transfer cases in the random generator.
10. Depth judgements agree.

## 10. Limits
- I did not read Ch. 0–2.
- I read S0071, S0137 and S0183 only around the cited lines.
- I did not run the full v11 model (I relied on the saved run).
- I did not verify every C19 row.
- The scratch checks are small hand cases, not proofs.

Independence is process independence (a separate reviewer session), not personal independence.

## Appendix
- §9.13 L347 still names the "v10" tool and run, although §9.13 v10 corresponds to model v11.
- EXT-090 and EXT-091 give their home as "§9.16 R4"; they are used in R4a.
- In the model, a grant without an `owner` gets 'A0' by default, so "fixed at issuance" is only partly modelled.
