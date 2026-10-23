*Window reviewer W (Opus), steps 2-3 review report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-23T16:32:56Z). sha256 of the hand-back text at receipt: `4d5ed242550e7f360826e0f975fd9a6e9bf56e5773c207a4d8cc7e4f3cc3ef79` (UTF-8, the text below this line exactly).*

C23 STEP 2-3: REVIEW REPORT

I checked the saved inventory before starting. The text below its header line, without the final newline, hashes to 05871bcd…aad10, which matches what you sent. The brief has been obeyed.

## 1. Assessment
- §8.16 records the window's main distinctions: released is not completed, storing is not owning, requiring is not owning, and a review is citable only for what it checked. In outline it is reliable.
- The largest risk is that, in two places, a producer's gloss or a source's self-description is taken for the decided or demonstrated content:
  - CON-0227's "four parts" are not four on the source's own data.
  - CON-0222's C23 extension writes the producer's phrase "world-fact" into the authority's decision. This is exactly what the skim of S0125 lost.

## 2. Findings (ranked)

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-1 | **CON-0227's four-part ownership is not shown to have four parts.** On every field whose owners are listed, the semantic owner equals the invariant owner (knowledge_reference BC-1/BC-1; session_reference BC-7/BC-7; external_correlation_reference external/external; all other fields C-10 throughout). The claim of "four different answers for one field" is false: knowledge_reference gets three distinct values (BC-1, C-10, knowledge governance). | high | S0122 L103–133, L142; my own reading of each field's ⑤ | State that data, semantic and authority are shown distinct. Semantic vs invariant are not separated by any instance, so they are undecided, not independent. List L142's "four answers" as a source defect. | C3, CON-0227 meaning and §8.16.3 state "three shown distinct, a fourth undecided" |
| W-2 | **The source's own text refutes its sufficiency claim.** delivery_timestamp needs "the record is C-10's; the event is not". That is an owner of the fact apart from an owner of its record, which none of the four parts expresses. The table flattens it to "C-10 OWNED". | high | S0122 L94 vs L121; L146 ("no field required a fifth") | Add to C3: the sufficiency test failed on the source's own field. Record/event ownership is the CON-0222 split appearing at field level. | C3 and CON-0227 carry it |
| W-3 | **The C23 extension of CON-0222 and §8.16 C4 attribute the producer's gloss to the decision.** "World-fact" is 5e1dd9ee's wording (S0122 L38, L43). The authority's own text says "the governed event claim" and evidence "associated with this claim boundary". That is weaker than "evidence toward a world fact". This repeats the W-3 error type of C22 (authority vs registrar). | high | S0125 L16–24 (verbatim block) vs S0125 L41–43 (Governance's rationale); S0122 L41 | Rewrite C4 and CON-0222 so the authority's words come first. Label "world fact / evidence toward" as the producer's and the registrar's reading. | C4 and CON-0222 quote S0125 L21/L24 and label the gloss |
| W-4 | **C4 "settles §8.15 C10's qualification" overstates.** S0122 answers only qualification 1, and only as the authority's intent: the asymmetry is intended. Qualification 2 is untouched: "acknowledgement was recorded" is met trivially by the receipt itself. | high | §8.15 C10; S0122 §2.2 | Say: qualification 1 answered as intent, qualification 2 open. Add it to §8.16.3. | C4, CON-0222 and §8.16.3 state it |
| W-5 | **C5's book analogy is inverted.** S0122 *forbids* the actor field so that it cannot invite the identity inference. The book does the opposite for its recorder: it *requires* the field and does not read it in Φ, and since v9 one mark reads it. So S0122's argument ("presence invites the inference") applies to the book's own recorder field. That is an unstated design tension, not an analogue. | high | §8.16 C5 Int; §9.13 L48–49; S0122 L111–114 | Restate C5's Int as two strategies (structural absence vs present-but-unread). Add the question to §8.16.3: does the recorder field invite what I15 denies? | C5, CON-0228 and §8.16.3 carry it |
| W-6 | **C3 (b) puts the gap in the wrong place.** The referent of knowledge_version is the *delivered version*, and that exists if delivery occurred. What is unbuilt is BC-1's *supersession/publication act* (CAP-03), which bears on invalidation-by-supersession, not on populating the field. CON-0220's shape (a required reference to an act that may not yet exist) does not fit. | normal | S0114 L55–60 ("supersession event … not built"); S0122 L150 (AIP-11 versioning) | Restate (b): populatable wherever versions have identifiers. The open gap is that the SUPERSEDED invalidation has no trigger mechanism. | C3 (b), CON-0227 follow_up and §8.16.3 restated |
| W-7 | **The §8.15 C2 correction is right in direction but keeps a premise S0121 rejects, and misses the stronger point.** (i) S0121 H.2 calls the release "legitimate", and HANDED_OFF accurately means ownership released. So "an owner that cannot honestly hand off" no longer holds; the misleading part is only the reader's projection. (ii) The seq-29 HANDOFF was done *by governance*. The engine's "from the current owner" names the source **lane**, not the **actor**. So the case does not show the rival S0071 rule ("only the owner may hand off") at all. If anything it shows an authority moving ownership, which is R4a's own rule. | normal | S0121 L99–101, L116–118; S0116 L62–67 | Amend the pointer in §8.15 C2: the case is a lane-state constraint with an authority as actor. It is no longer evidence for R4a's reason against the rival. | §8.15 C2 pointer and §8.16 C2 say so |
| W-8 | **Homonym: "owner".** C1 maps S0121's *mutation owner of a work item* (a lane) onto R4a's *owner application of a grant*, and C3 adds *field owners* (four senses). The Int "R4a is already a split ownership" also reads "invariant owner" as *enforcer* (the kernel). S0122 uses it as the *context that defines the rule* (BC-1 for knowledge_reference). | normal | §8.16 C1, C3 Int; S0122 L117 | Qualify each use: owner (lane), owner (grant), owner (field: semantic, data, invariant, authority). Label the reading of "invariant owner" as an interpretation. | §8.16 uses qualified terms |
| W-9 | **C6 has a counterexample in its own source, and "not a basis" distorts that source.** S0124 states "No field classification is wrong" (L92) while declaring that it assessed no field decision for correctness (L104), so the bounded review itself cites beyond its bound. And C6's line "a basis that is not on record is not a basis" contradicts S0124/S0125: "a registration gap, not an authenticity one". | normal | S0124 L92, L99–100, L104; S0125 L12 | Add the L92 instance as a breach recorded in the source. Label "not a basis" as D's convention (Int), not the source's reading. | C6 and CON-0229 state both |
| W-10 | **C1's source premise is adopted uncritically.** "Indistinguishably from lanes that finished" holds only if finished lanes are also HANDED_OFF without COMPLETE. The engine has COMPLETE, so this is an unstated premise about practice. | normal | S0121 L113, L116 | Mark the premise in C1. | C1 states it |

## 3. Verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0226 | **VALIDATED** (scope: the engine's state semantics, release ≠ completion) | Distinct engine edges are on the record (S0121 L113, L136). "Transfer is not a use" checked in `ce_model.py` L856–860. "No Φ clause reads a transfer as an ending": reported, not shown. The Int to R4a stays provisional (W-7, W-8). |
| CON-0227 | **INVALID as stated** | "Four parts" and "four answers" are defeated by W-1 and W-2. A restatement can stand: storing ≠ owning, requiring ≠ owning, at least three dimensions shown distinct. |
| CON-0228 | PROVISIONAL | The source argument is sound. The D analogue is inverted (W-5). One producer. |
| CON-0229 | PROVISIONAL | An application of CON-0154. W-9. |
| CON-0222 (C23 extension) | PROVISIONAL; the extension is **distorted** | W-3, W-4. |
| §8.15 C2 correction | holds in direction; the residual is wrong | W-7. |

## 4. Coverage

**Skim safeguard (§3).**
- **S0125, read closely: theory was lost.** Its verbatim block (L16–28) separates the authority's wording ("governed event claim", "evidence associated with") from Governance's rationale ("world-fact", L41–43). The skim line ("content is S0122's") lost exactly the distinction behind W-3. The non-proof list also names "the delivery event" (L26). My inventory item S0125-5 is therefore confirmed as theory the skim lost, and as material.
- **S0123: agree, skim.** Nothing lost. Its "language creates ownership" point is S0122 L128's, and S0122 was read full.

**Ranked gaps from my inventory (other items COVERED or justifiably excluded as repeats; S0124 INFO-2 is covered by §8.12 L87):**
1. MISSING: the tension between D1's "the receipt claims…" and rule 1 (recording ≠ asserting) and rule 7. What kind of speech act is the receipt's "claim"? This bears on W-3 and W-4 (S0122 L34, L69, L75).
2. MISSING: naming as bounded-context protection: an identifier must not carry a forbidden concept (S0122 L128; S0123 L31). It is the converse of CON-0223.
3. MISSING: conjunctive eligibility is not monotone under relief; criterion 2 ceasing to bind does not clear 4, 6 or 7 (S0121 L67–71).
4. MISSING: universal absence claims drawn from search, and the problem of search completeness: "exactly three windows … no other process" (S0121 L17); "occurs 0 times" (S0124 L59). The latter is praised in C6.
5. MISSING: the classification lattice, with "fields to analyse ≠ required" (S0122 L26, L82). Evidence values map onto CON-0217's dimensions, but the subject dimension is unused (S0122 L196–203).
6. MISSING: role eligibility. b64828fe is barred from *executing* E2 but reviews it, and S0107's NOT ATTESTED status for it goes unmentioned in S0121. The finder of INFO-3 also registers its closure.

**New fork:** what is a receipt's claim: an assertion, a boundary, or evidence associated with one?

**Source defects:** S0122 L26 ("four SUPPORTING"; noted by the Professor); S0122 L142 ("four different answers"); S0123 L35 (part mapping); S0124 L92 vs L104; S0124 L100 vs S0125 L66 (the lists of earlier occurrences differ).

## 5. Refutation adequacy

| Claim | Professor's attack | Judgement | Strongest attack |
|---|---|---|---|
| C1 | does D conflate? | inadequate | the "owner" homonym (W-8); the source's premise (W-10) |
| C2 | is the withdrawal reliable? | inadequate | the actor of seq 29 was governance, and the release was legitimate (W-7) |
| C3 (a) | is orthogonality shown? | adequate in direction, not the strongest | semantic ≡ invariant on all fields; sufficiency refuted by L121 (W-1, W-2) |
| C3 (b) | referent gap? | inadequate | referent misidentified (W-6) |
| C4 | does "toward" rename the problem? | inadequate | the authority's wording differs; qualification 2 untouched (W-3, W-4) |
| C5 | is information lost? | inadequate | the book analogy is inverted (W-5) |
| C6 | is it CON-0154 again? | adequate for that question | S0124 L92 breaches the rule (W-9) |

R4: §8.16 contains no Ded, so no proof-step table is required. C1's claims about the model were partly checked (see the CON-0226 verdict).

**Brief's three questions:**
- C2: right in direction, wrong residual (W-7).
- C3 (a): correct but weaker than what the data shows (W-1).
- C4: no, S0122 does not settle the C22 qualification (W-4).

**Previous settlement (C22 §2):** spot-checked W-8 and W-14. Both conditions hold in CON-0217 and in CON-0222 / §8.15 C10. C22's recorded omission "transfer versus completion in S5's block" is now covered by C1.

**Rulebook §6 questions:**
- Order: consistent. THEORY-MAP.md is stale (last source in order S0115; no entry for §8.15 or §8.16).
- Mathematics: W-1 and W-2 are counts over the fields, not formal proofs.
- Statistics: not applicable, with a reason: a single producer and no quantitative claim.
- Computation: one check, `single_use_grants` excludes transfers.
- Clarity: W-8.

## 6. Next steps (in dependency order)
1. W-3 and W-4: rewrite C4 and CON-0222 so the authority's words come first. Done when both quote S0125 L21/L24 and list qualification 2 as open.
2. W-1 and W-2: restate CON-0227 as three dimensions shown distinct and a fourth undecided, with record/event split noted. Done when the register row is restated and the verdict changed accordingly.
3. W-5: correct C5's Int and add the recorder question to §8.16.3. Done when C5, CON-0228 and §8.16.3 carry it.
4. W-6, W-7: correct C3 (b) and the §8.15 C2 pointer. Done when both are restated.
5. W-8 to W-10: textual qualifications in §8.16. Done when each named section carries them.
6. Update THEORY-MAP.md for §8.15 and §8.16.

No question for the Principal is needed.

## 7. Limits
- I read the five sources, S0116 L58–70, S0114 L55–60, §8.15 (C2, C7–C10, §8.15.3–4), §8.16, the step-8 file, the register rows, C22 SETTLEMENT §2, and greps of §9.13 and §9.16.
- I did not read §9.15 in full. I did not run the model, and the Φ "no ending" claim is not checked.
- The field-owner counts (W-1, W-2) are my own reading of S0122 L103–133, done by hand; there is no script record.
- Independence is process independence, not personal independence. In addition, my step-1 depth calls for S0123 and S0125 were exposed to the commit subject, as disclosed in step 1.
