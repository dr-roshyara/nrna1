*Reviewer's steps 2–3 report, verbatim (window C14; saved by the Professor, 2026-10-19). Harness time: see SETTLEMENT.md.*

# C14 transition review, steps 2–3: §5.7, the §1.13 form rewrite, STEP8-AND-SCAN, CON-0144 and CON-0145

## 1. Assessment

§5.7 quotes its sources accurately. I checked all 15 quotes against S0076–S0080 and every one matches. Its two new concepts are both stated more strongly than the evidence: CON-0144 is contradicted by two cases inside its own source, and CON-0145 settles a fork the book holds open. The largest risk is that C1, CON-0144 and C5 become "laws" built only on a prohibition ("close only when…"). Doing nothing satisfies a prohibition, and the source's actual finding (15 of 19 lanes never closed) is the missing duty to close. The §1.13 rewrite leaves the body unchanged, but its new header blocks add content and change one attribution.

## 2. Findings (ranked)

| # | Sev. | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | major | defect | CON-0144, "a lane with no output is not completed" | S0079 L32; S0076 L53, L57; S0079 L73 | (a) A lane was closed COMPLETE after "deliberately implemented nothing … the intended outcome". (b) The V#3 lane went from CREATED to COMPLETED with no START, so the record never showed it active, and the reconciliation kept it closed "with the defect stated". Both cases come from the sources §5.7 relies on. C1 and C2 are never put side by side. | INVALID as stated | "Completed only when the **commissioned** deliverable is delivered (a deliberate null counts) **and** the lifecycle justifies it, **or** the closure states its lifecycle defect." Also record that the mechanism accepted a COMPLETE on a lane that was never started: C1 is held by Governance's restraint, not by the mechanism (CON-0102). |
| 2 | major | defect | C1 and CON-0144: only a "must-never" | S0079 L74 | A Governance that never closes anything satisfies the rule (trivial-solution test). The E-2 drift (15 of 19 lanes delivered but not closed) is a breach of the missing **must-do**: close when both conditions hold. | The rule as stated cannot detect the failure its own source measured | Add the duty half. Mark generality *Hyp* (entry 45: one act, one day). |
| 3 | major | defect | CON-0145, "a supersession exists only as a recorded link" | S0079 L62; S0080 L47 | The sources say the supersession was never recorded and must not be inferred. That is an epistemic rule about recognition. "Exists only as …" is the C1-a reading (the record constitutes), which §1.13.1-2 leaves to the Principal. | Settles an open fork | "A supersession is **recognized** only when it is recorded, and inferring one is not evidence." |
| 4 | major | defect | C5, "This is A1 for models … an act needs a reason that is new evidence" | S0080 L57 | A1 says only an act confers standing; it requires no reason. C5 adds a justification condition and calls it A1. The source's "should be driven by" is a norm about practice, but the book writes "changes **only** by an act on evidence". "Explicit change pressure" is not evidence. | Changes a basic axiom quietly | Mark it as a candidate law of its own (Hyp), modality *should*, separate from A1. Its trivial case (never reopen) also needs a duty to reopen when new evidence arrives. |
| 5 | major | omission | S0080 L8–10, a reconciliation "treated as the current portfolio truth" | S0080 L8–10; S0079 L4, L97; S0078 L163, L268 | The ARB adopts a prose synthesis as "the authoritative portfolio state". C1's own rule is that prose saying "finished" does not close anything, and BC-7's I-10 is that the record outranks prose. The two are compatible only if the adoption gives standing to the **classification** (what still needs a decision), as a **dated** snapshot of the record (the 15 closures are record acts), and not to a second store of state that keeps standing as the record moves. | Possible conflict with record-over-prose; not recorded | Add this as a claim, with that reading as *Int* and the conflict named. |
| 6 | major | defect | C2, "Ded (§1.12): … for the record the verification has no lifecycle"; "a second instance of §8.6 C3" | S0076 L53, L57 | The record holds a REGISTER and a COMPLETE, so it has a lifecycle with no activation. §8.6 C3's case had "no lane, no START and no grant" (S0058 L49). This case had a registered lane, and was then closed. "Same class" is the source's own classification. The step is labelled Ded but rests on A1, not §1.12, and is at most *Int*. | The key mechanism fact (COMPLETE without START) disappears | Restate as "registered, never activated, closed". Label Int. Call it a different subtype of §8.6 C3. Feeds finding 1. |
| 7 | major | omission | S0078 T-1a, "the party recording an act never supplies the standing against which the act is judged" | S0078 L146, L161–163 | A grep for "never supplies", "self-supplied" and "validation input" found nothing outside the review folders. §6.5, §8.8 and §9.6 cover non-consultation (T-1b), but not the rule that the validator recomputes its own premise. | A candidate law that answers "how can it be trusted" (no premise supplied by the party being judged) is missing | Add to §8.8 C1 or Ch. 9 (Src; structural in source). |
| 8 | major | omission | S0078 D.5, no context owns "the ability to produce a lifecycle fact"; it is "a property of access to the mechanism" | S0078 L222 | §6.5 and §9.6 record that the actual writer is unattested, but not the boundary claim behind it. By the invariant-owner test, writer identity has no owner and no operation that guarantees it. | Kernel/application boundary (deliverable 3) | Add under F22 or §9.6 as an unowned invariant. |
| 9 | major | omission / source defect | The accepted T-1 text still says "no non-canonical key appears anywhere in the estate", at the old denominator 0/126 | S0078 L169 vs L67; S0077 L11; S0076 L47 | The corrected §2 table reports 20 such keys, but the replacement text for T-1 was not corrected. The model accepted "as refined and corrected" therefore contains a claim already shown false, and V#3, plus the ARB's "accurately repairs", passed it. | A counterexample to the claim that correction and verification together restore integrity (§8.7) | Record it in §8.7 or §8.8 as an instance of a verification passing a leftover false statement. |
| 10 | minor | omission | S0077 "four independent verification passes", "never accepted on its author's word" | S0077 L26 vs L38 | Only V#1–V#3 exist, and the same document says two of three independence links are compromised. For C2 the facts stand, since S0077 L39 does carry the off-record START. But C2's *Int* "the acceptance carries the defect" is only half true: the summary contradicts the qualification it carries. | A third case of "caveats dropped at acceptance" (§5.6 C6), and of counting passes instead of independent information (§8.6 C4) | One line in C2. Record the count slip as a source defect. |
| 11 | minor | defect | C4, "this is the order the book's boundary questions assume" | S0080 L28–33 | The ARB's chain orders an organisation-design discovery (ADR-AIP-04) and puts "shared platform service" **last**. The three boundary tests (necessary, must the kernel provide it, could an application provide it) are not an order of staffing. §9.3 C4 (capability ≠ primitive) is a different claim. | Weak support presented as a match | "Compatible with, not identical to." |
| 12 | minor | defect | C3, "§6.5 C9 records the same gap" | S0080 L46–48 | The endings that never ran are in §6.5 **C2**, not C9. There, existing transitions were never exercised; here, transitions are missing. "Un-cancel" is a reversal, not an ending. | — | Fix the reference and the distinction. |

## 3. Concept verdicts

| Concept | Verdict | Reason and scope |
|---|---|---|
| CON-0144 | **INVALID as stated** | Two counterexamples in its own sources (finding 1). The restricted form in finding 1 is PROVISIONAL (one line). |
| CON-0145 | **INVALID as stated** | Settles C1-a (finding 3). The recognition form is PROVISIONAL (one line). |
| C2 | PROVISIONAL | The facts hold. "No lifecycle" and the Ded label do not (finding 6). |
| C3 (gaps in the lifecycle vocabulary) | VALIDATED | Within its scope (retirement). Fix the reference (finding 12). |
| C4 | PROVISIONAL | The mapping goes too far (finding 11). |
| C5 | **INVALID as stated** | Identified with A1 (finding 4). As a *Hyp* it is PROVISIONAL. |
| C6 | VALIDATED | Follows from P-8 for standing. Mark the homonym: the source's "inherits authority" means reliance in practice. |

## 4. Coverage of my step-1 inventory

- **S0078:** items 2 (T-1b), 3, 4, 5, 6, 10, 12 and 14 are COVERED (§8.8 C1, C2 and C6; §6.5 C2; §9.6). Item 11 is COVERED (§9.6 L52), but OQ-13 is MISSING. Items 1/T-1a, 8 and 13 are MISSING (findings 7–9). Items 7, 9 and 15 are partly covered; see the appendix.
- **S0076:** item 1 is COVERED (C6), items 5 and 6 are COVERED (C2; §8.8 C3). Items 2 (precision vs integrity), 8 (accepting a verification ≠ accepting the model) and the blank signature date are MISSING (minor).
- **S0077:** item 2 is COVERED. Items 1 (the accepted object is a composite of two commits) and 6 (a banner annotation on the accepted file, a third case of §5.6 C6) are MISSING (minor). Item 5 is finding 10.
- **S0079:** items 1, 2, 3, 4, 6 and 7 are COVERED. The counterexample at L32 is finding 1. Items 5 (no cosmetic closure) and 8 (the kinds of non-closure) are MISSING (minor).
- **S0080:** items 1, 2, 4 and 5 are COVERED. Item 6 is finding 5. Item 3 (sequencing to avoid encoding an unresolved fork) is MISSING (minor).
- **New topic or fork:** whether a recorded synthesis can hold standing as "truth" next to the record (finding 5). This is a candidate F28.
- **THEORY-MAP** has no entry for §5.7 yet.

## 5. Next steps (in dependency order)

1. Restate CON-0144 (findings 1–2). Done when the restated rule has both its must-do and must-never halves and both counterexamples are cited.
2. Restate CON-0145 as a recognition rule (finding 3).
3. Separate C5 from A1 (finding 4).
4. Add claims for findings 5, 7, 8 and 9.
5. Correct C2 (finding 6), then the minor findings.
6. Correct the §1.13 header (appendix A1–A3).

## 6. Limits

- I did not read AMS, IA, S0014 beyond L53, or S0015 L58, so "AI-generated" and the location of "REALIZED" are unverified.
- I read only the parts of §1.14.4 and §9.6 that my searches returned. I did not read CONSOLIDATION folders or test copies.
- The coverage searches were lexical greps. A miss is a pointer, not proof that a concept is absent.
- Independence: this review is process-independent of the Professor; it is not personal independence.

---

## Appendix

**A. The §1.13 form rewrite** (checked with `git diff 3c56ba464~1 3c56ba464`)

- **Body:** unchanged apart from the labels. `form_rewrite.py` checks only the body. The inserted blocks (lineage, plain language, open questions, WDNH, references) are not checked by construction.
- **A1 (major):** the lineage paragraph adds content that is not in the section: AMS and IA are "AI-generated"; IA's date and its Track B status; "one programme on one day → one line"; the S-IDs "with their own lineage notes". Old L3 says only "Track B (exploratory; governs nothing)". The header's "No claim was added … each pointing only to text already in the section" is false for this paragraph, which labels itself *Int, added at the rewrite*. Move it out of the form-only rewrite, or call it a new claim.
- **A2 (major):** the plain-language summary says "**The ARB's** rulings on ending, delegation and linkage". The body says AM-1…AM-5 are "approved inside Track B", and Track B governs nothing. In a section about authority, this raises the rulings' standing. Write "AMS's Track B rulings".
- **A3 (minor):** 1.13.99 drops "very likely" in "the 144 records include AMS's 20" (body L72), which strengthens the quantifier.
- **A4 (minor):** the claims index numbers non-claims ("Limits of the check") and skips the Classification paragraph (L50), which carries a labelled Ded. The open questions and WDNH entries otherwise match the body. The labels are mechanically correct.
- **"REALIZED" [S0014 L53]:** S0014 L53 contains "catalog vs README vs register, found blind" and the process fix, but not the word "REALIZED". This is a **quote-anchor defect**. It existed before the rewrite and is minor, provided the word is found at L68 or S0015 L58. It matters more if it is not: RC-29a's "unregistered, not unrecorded" uses the README as evidence. A README's "REALIZED" records realization, not authorization; the commissions are the authorization records, so the reading should cite them.

**B. STEP8-AND-SCAN**

- "only ×4, all scoped" is wrong. C5's "changes **only** by an act on evidence" is not scoped, and CON-0145's "exists only" is unscoped in the register.
- The dependency table omits C5 (A1, §5.6 C6) and C2's dependence on §1.12.
- "Quotes 15/15": I confirmed it.

**C. Lower-priority S0078 points** (items 7, 9, 15)

- An instance the model predicts is not a counterexample (L218).
- D.4: no path from a recorder to authority or activation (L220).
- The refiner was exposed to the ARB's framing and still reached a verdict outside all three candidates, one case bearing on information lineage in §8.6 C1 (L38–40).
- OQ-13.

