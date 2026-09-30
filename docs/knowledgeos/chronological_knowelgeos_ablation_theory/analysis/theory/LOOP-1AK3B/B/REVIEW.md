# Independent review of Researcher A's answer

Files used: `REVIEW-TASK.md`, `TASK.md`, `SOURCES.md`, `ANSWER.json`, `ANSWER.md`. Nothing else.

## Summary

- **Evidence scope.** A used only SOURCES.md. I found no outside facts. The line references in A's answer point to lines of SOURCES.md.
- **Quotes.** I checked every quoted value against the source. All are verbatim apart from markdown emphasis and one marked ellipsis. Each quote supports the value it is attached to. One ANSWER.md statement is labelled SOURCE FACT but includes an interpretive step (D6).
- **X.** I agree with A: **E4 is X-consistent**, and there are no other X events.
- **U. I disagree with A.** A found no U witness. Under the frozen §3 rule, and applying A's own threshold coding consistently, **E1 is a U witness.**
  - A's JSON rejects E1 on four grounds. Two of them (E1 is an assessment; stability ❌ blocks promotion independently) are not conditions in §3.
  - A third ground ("many" is not a count) fails because "many" entails ≥ 2, which is all that §3 asks for.
  - The fourth ground (the threshold unit is inferred) is inconsistent with A's own E3. There, A codes the same source sentence as a stated, context-counting threshold.
- **Other coding problems** (none of them changes U or X):
  - E7 bundles three different items, and at least one of them ("relocating the file") is not a standing-raising move.
  - A does not address the "rejected AP-1 and AP-2 … accepted G-1" assessments.
  - ANSWER.md and ANSWER.json give different reasons for rejecting E1.

## Per-event findings

**E1: Layer module → canon (assessment).**
- Item, target, actor, evidence and exception: the quotes are verbatim and they support the values.
- Linking "a module straight into … methodology/" to `Layer_Verification_Rule.md` is well supported. Both are "PROPOSED — NOT ADOPTED" (Q1 l.11; Q2 l.70).
- `evidence_instances`: A codes this as "many instances" with no number stated. I code it as "many instances", which is **≥ 2** by lexical entailment. That is not a guess: "many" cannot mean fewer than two.
- `evidence_contexts`: "one context" = 1. I agree.
- **`threshold_stated`.** No rule text is quoted anywhere in the sources. The closest stated requirement is "Under a strict reading, no insight from a single-context programme can ever be promoted", together with "What is missing is a second context". Both count **contexts**.
  - A accepted the first sentence as E3's stated threshold (unit: contexts), but marked the same unit as INFERENCE for E1. That is inconsistent (D3).
  - The R-39 passage supports "contexts" as the normal rule's unit. A one-context item needed "an explicit, recorded exception" in order to be promoted.
- **`outcome`: NOT-RAISED/HELD** ("Outcome B, not yet canon"), `ground`: RULE (promotion criteria). I agree. This outcome is an **assessment** outcome. The actual decision was "Referred". §3 does not require a DECISION, however.

**E2: Layer module → ES-006.1 ladder (assessment).** The quotes are verbatim. The coding is sound. `exception` NO is right under TASK's definition "states the normal rule is applied" ("ES-006.3 already forbids that shape"). It has no counts, so it is irrelevant to U and X. Agreed.

**E3: Candidates 2–5 (assessment).**
- The quotes are verbatim.
- Instances are UNK, which is correct. So E3 cannot be a U witness. Agreed.
- Minor: A codes the actor as UNK, but E1, in the same report, has actor "I". The sentence is the author's own assessment (D9).
- Also note that the whole passage is framed "Under a strict reading" inside an "open question" block. PENDING is an arguable alternative outcome (see Alternatives).

**E4 (cited): DDD principles → `engineering/` via R-39.**
- The quotes are verbatim: "evidence base *"one context."*", "holds the **ADOPTED** DDD module (via the R-39 exception)" and "*ruled*, not merely written into the tree".
- `act_type` REPORT-OF-PRIOR-DECISION is correct. This is a cited precedent, not a new decision.
- `exception`: YES, and it is directly quoted.
- Below threshold: I rate this more strongly than A does. "early promotion", "not-fully-qualified material" and the fact that an *exception* was needed are all stated in the text, so they are not inference.
- DA: "`R-nn` identifiers belong to the Decision Authority" and R-39 is an `R-nn` identifier. I treat this as a formal consequence of a general stated rule rather than a free inference, though the DA is still not named for R-39 (D10).
- `ground` UNK is acceptable.

**E5: five positions → "the Chair's position" (decision).**
- The quotes are verbatim. RAISED.
- Evidence and threshold are UNK, so it is not an X event, because there is no stated threshold to fall below. Agreed.
- Whether a "Chair's position" is a higher-standing home is debatable, as A notes.

**E6: same five positions → rulings register.**
- The R-34 quote is verbatim.
- This is a **refusal on authority** ("not minted"; the identifiers "belong to the Decision Authority"), not a *hold* pending evidence. The outcome enum NOT-RAISED/HELD covers both, so the value stands. The distinction matters only descriptively.
- It has no counts. Not relevant to U or X.

**E7: "Held" items.**
- The quotes are verbatim.
- **Coding problem:** A bundles three heterogeneous items.
  - "relocating the file" means moving the Layer module *out of* `methodology/` (Q1 l.51: "the module moves — not as a demotion"). That is not a standing-raising move.
  - "a new staging root" is structural, not an item being raised.
  - Only the "undefined cell" conclusion, held from the Chair's position and separately "Withdrawn as framed" by its author, is arguably an item being raised.
- The "n = 1" figure counts artifacts and relates to the staging-root proposal.
- None of this touches U or X.

**Missed or unaddressed events.**
- Q1 l.17: §1–2 "rejected AP-1 and AP-2 retrospectively and accepted G-1 prospectively". These are assessments that §1–2 of the Layer rule made. Their target, counts and threshold are all UNK, and the text does not show that they are standing-raising (acceptance *into what* is never said).
- A should have included these events, or at least excluded them explicitly. Even if coded, they cannot enter U (no counts) or X (no stated threshold).
- A's other exclusions are defensible: ES-006.1's canonical status, the "Referred" move, the ARB exception-mechanism question and the `methodology/` reservation question. The ARB question could alternatively be coded as a PENDING event, but that does not affect U or X.

## Recomputed U / X (reviewer coding)

**U: [E1].** E1 meets all four §3 conditions:

| §3 condition | E1 | Supporting text |
|---|---|---|
| RULE-grounded NOT-RAISED/HELD | ✓ | "Applying the promotion criteria"; "Outcome B, not yet canon" |
| `evidence_instances` ≥ 2 | ✓ | "many instances" |
| `evidence_contexts` = 1 | ✓ | "one context" |
| Threshold counts contexts | ✓ | "no insight from a single-context programme can ever be promoted"; "What is missing is a second context". This is the same threshold coding A applied to E3. |

The other events do not qualify:
- E3 fails because its instance count is UNK.
- E2, E6 and E7 have no context counts.

**X:** only E4 is RAISED with evidence below the stated normal rule.

| Event | exception | DA identified? | Class |
|---|---|---|---|
| E4 | YES | Not named, but follows formally from "`R-nn` identifiers belong to the Decision Authority" | **X-consistent** |

Whether the actor is the DA does not matter for X-consistent. There are no X-counter events.

## Strongest alternative readings

1. **The threshold is only the author's hedged reading.** On this reading, the "stated threshold" in §3 means the rule's own wording, which the sources never quote. "Under a strict reading" would signal the author's interpretation rather than the rule, and U would be empty, which is A's result.
   - The text does not fully rule this out. That is the main residual uncertainty.
   - It is weakened because the R-39 exception was *needed* for "one context" evidence, which confirms that the normal rule blocks single-context promotion.
   - Applying this reading consistently would also strip E3's threshold, and A did not do that.
2. **"Repeated evidence ⚠️ partial", not ❌.** Many instances might earn partial credit, so instances would partly substitute for contexts. §3 does not test this, and "What is missing is a second context, and no amount of refinement inside EPIC-004 can supply it" rules it out as a route to promotion.
3. **E1's outcome is PENDING, not NOT-RAISED.** The decision was "Referred", and the structural question is "the ARB's". This reading would remove E1 from U.
   - The text partly rules it out: the assessment itself states "Outcome B, not yet canon". What was referred is the file move and the exception-mechanism question, not the assessment verdict.
   - The cost is that U then rests on an **assessment**, not a decision.
4. **"Domain independence ✅" means more than one context.** The text rules this out: it is a quality criterion ("domain-neutral"), not a count, and it sits beside "one context".
5. **E4 is not below threshold because the R-39 ruling itself is the applicable rule.** The text rules this out. It is called an "exception" and "early promotion" of "not-fully-qualified material".

## Disagreement register

| # | Topic | Class | A | Reviewer | Changes a classification? |
|---|---|---|---|---|---|
| D1 | E1 rejected from U because it is an assessment and because stability ❌ blocks promotion independently | formal reasoning | Treated as U-blocking | Neither is a §3 condition | Yes (U) |
| D2 | "many instances" against the ≥ 2 test | coding | Not a stated count, so it fails | Lexically ≥ 2, which satisfies §3 | Yes (U) |
| D3 | Threshold unit, E1 vs E3 | coding | E3: the sentence is the stated threshold (contexts). E1: the unit is INFERENCE | The same sentence applies to both. Contexts is stated for E1, supported by "second context" | Yes (U) |
| D4 | Net U result | substantive | none | [E1] | Yes |
| D5 | Reasons for rejecting E1 | wording | ANSWER.md gives 2 reasons; ANSWER.json gives 4 | Internal inconsistency | No |
| D6 | "'That' refers to the single-context restriction", labelled SOURCE FACT | wording | SOURCE FACT | The referent is an (easy) INFERENCE and should carry that label | No |
| D7 | E7 bundles heterogeneous items | coding | One HELD event | "Relocating the file" is not standing-raising, and "staging root" is structural. Only the "undefined cell" conclusion qualifies | No |
| D8 | AP-1 / AP-2 rejected, G-1 accepted | coding | Not mentioned | Should be coded (target UNK) or explicitly excluded | No |
| D9 | E3 actor | coding | UNK | "I" (the author), consistent with E1 | No |
| D10 | E4 DA status | formal reasoning | INFERENCE | Formal consequence of the stated R-nn/DA rule; still not named | No (X-consistent either way) |
| D11 | Strength of E4's below-threshold status | source interpretation | INFERENCE | Stated ("early promotion", "exception", "not-fully-qualified") | No |

I found no disagreements about evidence scope or independence.

## What the text can and cannot establish

**The text can establish:**
- The Layer module was assessed as not canon, with "many instances, **one context**", and the thing missing is "a second context".
- A single-context item (DDD principles) reached `engineering/` only through a recorded R-39 exception.
- `R-nn` rulings belong to the DA.
- The Chair's approvals were dispositions, not rulings.

**The text cannot establish:**
- The verbatim wording of the promotion rule's threshold, or whether "repeated evidence" formally counts contexts rather than being read that way "under a strict reading".
- Numeric instance counts.
- Who issued R-39 by name, and its validation expectation.
- The ARB's answer.
- Whether the E1 verdict was ever converted into a decision.
- What AP-1, AP-2 and G-1 were assessed *for*.

The U result [E1] therefore holds under the frozen rule with consistent coding. It rests on accepting the source's own context-counting statements as the stated threshold, and on an assessment rather than a decision.
