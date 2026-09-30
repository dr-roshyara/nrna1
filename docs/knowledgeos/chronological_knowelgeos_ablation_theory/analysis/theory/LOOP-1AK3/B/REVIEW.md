# Independent review of Researcher A (ANSWER.json / ANSWER.md)

Inputs used: `REVIEW-TASK.md`, `TASK.md`, `SOURCES.md`, `ANSWER.json`, `ANSWER.md`. Nothing else.

## Summary

- **A's classification: NOT A WITNESS.** A's decisive fields: `kind`, `target`, `state_before`.
- **Reviewer's classification: NOT A WITNESS.** Reviewer's decisive fields: `kind`, `target`.
- **The classification is the same, and it is robust.** None of the disagreements below changes it. The one reading that would change it (see Alternatives) is ruled out by the text.
- **Where A is strong.** A uses only permitted evidence. A keeps "adopting a protocol" separate from "promoting it into a document". A treats P2's claim of object identity as the implementer's assertion, not an authority's finding. A applies the §3 rule correctly, including how UNK fields are handled.
- **Where A is weaker:**
  1. Two fields are left UNK even though the text establishes them: P1 `evidence` is 0 slices, and P1 `exception` is NO.
  2. P2 `kind` mixes up the object's kind (protocol) with its target standing (operating standard).
  3. Two quotes do not support the value they are attached to: P1 `state_before` and P1 `target`. A third quote shifts a parenthesis: P2 `exception`.
  4. A relies on `state_before` as decisive, but that difference is built into any parking-then-revisit pair and is open to challenge.
  5. A misses one passage inside the permitted sources. It shows that the P1 contract was *already in force operationally* for WP-1. That makes P1 and P2 parallel on both facets, which strengthens NOT A WITNESS.

## Check 1 — Permitted evidence
Passed. Every quote and inference comes from `SOURCES.md`. A's inference that P1 had 0 slices draws on P1's own text ("vs. starting WP-1"). No outside material is used.

## Check 2 — Quote fidelity and support

| Field | Verbatim? | Supports the value? |
|---|---|---|
| same_object quote_p1 | Yes, with elision. Bold is stripped and "…:" replaces the parenthetical. | Yes |
| same_object quote_p2 | Yes | Yes. It is the implementer's claim. |
| P1 object, kind | Yes, with elision | Yes |
| P1 actor | Yes | Partly. The header labels the confirmation "(chair)". The parked-candidate bullet itself names no actor, and P2 says "the PA parked" it. |
| P1 target | Yes, with elision and bold stripped | **Weak.** The quote states the *criterion* ("Extract only after…"), not the *home*. The target's words are "reusable doc … that future WPs reference instead of repeating". |
| P1 state_before | Yes ("one candidate parked") | **No.** The quote describes the state *after* the disposition, not before it. |
| P1 evidence | Yes | Yes, for showing that WP-1 had not started. But A then leaves the value UNK. |
| P1 outcome, ground | Yes | Yes |
| P2 object, kind, target, state_before, evidence, outcome | Yes | Yes. For `kind`, see Check 4. |
| P2 exception | **Not quite.** A writes "(R-39 ...) recorded as an exception". In the source, "recorded as an exception so the normal bar stays intact" sits *inside* the R-39 parenthesis. The elision moves it outside. | Yes, the value NO is still supported. |
| P2 ground note ("Phase 16 forbids") | Yes ("exactly what the protocol's own **Phase 16** forbids") | Yes |

## Check 3 — Silent inference / UNK that the text establishes

- **P1 `evidence` (A: UNK; reviewer: 0 slices, SOURCE).** Three passages in the text establish that no slice in the criterion's unit had been completed at P1:
  - The verdict weighs polish "vs. starting WP-1".
  - The session close sets out the path "→ fresh session → … four keystone RED tests".
  - "WP-1 OPENED" appears only *after* the parking.
  
  The criterion counts from WP-1 ("WP-1 + a few more slices"), and P2 counts in the same unit ("two slices (WP-1, WP-2)"). So the count at P1 is 0 of that unit. This is established by the text, not guessed.
- **P1 `exception` (A: UNK; reviewer: NO, SOURCE).** P1 applies the stated bar as it stands: "Extract only after … — not now". P2 shows that exceptions in this program are *explicitly recorded*: "recorded as an exception so the normal bar stays intact". P1 records none, and applying the normal bar is by definition not an exception.
- **P2 `actor` (A: "PA (issuer); adopter unnamed").** This is defensible, but the text does name who gives the protocol its standing: "Received from the PA as the going-forward protocol. In force from the next work package." Reviewer codes the actor as the PA. The implementer is the recipient. The DA holds only the open promotion decision.
- A's labelled INFERENCE and UNKNOWN statements in ANSWER.md are correctly labelled. Nothing is silently inferred.

## Check 4 — Category confusion

- **Operating standard vs reusable document.** A separates the two correctly across dispositions. But in P2 `kind`, A codes "protocol / operating standard". "Operating standard" is the *standing* the protocol receives, so it belongs in `target`. The kind is **protocol**. The comparison result does not change: protocol ≠ reusable document.
- **Chair vs PA vs DA.** Handled correctly:
  - P1 names the chair.
  - P2 attributes the P1 parking to the PA.
  - No passage says the chair is the PA.
  - The DA is only the holder of the pending promotion decision.
  
  UNK equality is the right code.
- **Parking vs refusal.** P1 is a conditional deferral ("not now. Revisit … ~WP-3/WP-4"), not a permanent refusal. A codes the outcome as NOT-RAISED, which is correct, and does not call it a refusal. §3 speaks of the ground that "requires the refusal"; reading parking as the not-raised disposition is within the frozen rule. No confusion.
- **Adopting a protocol vs promoting it into a document.** This is A's strongest point. P2's own header draws the line: "ADOPTED AS THE OPERATING STANDARD …; NOT self-promoted into a standards document". A carries the distinction through to the `target` coding and to Alternative 1.

## Check 5 — Same object?

- **SOURCE FACT:** P2's author states: "This protocol is, in substance, the **"Implementation Execution Contract"** candidate that the PA parked on 2026-07-26".
- **Reviewer's reading.** The two passages concern the same *candidate idea*, as the implementer asserts it. They do not concern the same *artifact*:
  - P1's object is a to-be-extracted reusable doc with five parts.
  - P2's RAISED object is a 17-phase protocol authored by the PA.
- **The key point.** The *two dispositions* A pairs act on different facets:
  - P1 disposes of **document promotion**.
  - P2's RAISED act disposes of **operating adoption**.
  
  P2's disposition of document promotion (the same facet as P1) is *pending with the DA*, and the recommendation is to hold. A says the same in substance (ANSWER.md line 29). The reviewer puts it more strongly, because P1 itself shows the operating facet was already live (Check 6).
- **Independence.** The identity claim, the "already-demonstrated" list and the slice count are all the implementer's self-assessment. A flags this for identity. It is still valid SOURCE for coding what the text says.

## Check 6 — Recomputed classification (reviewer coding)

| Field | P1 | P2 | Compared |
|---|---|---|---|
| object | Implementation Execution Contract, a reusable doc candidate (SOURCE) | DDD-Driven Architectural Implementation Protocol, 17 phases (SOURCE); asserted "in substance" to be the P1 candidate | same idea, different artifact |
| kind | reusable document (SOURCE: "reusable doc") | protocol (SOURCE: "Protocol … received") | **KNOWN-DIFFERENT** |
| actor | chair (SOURCE: "(chair)"). P2 attributes the parking to the PA. | PA (SOURCE: "Received from the PA as the going-forward protocol") | UNK equality |
| target | a reusable doc "that future WPs reference instead of repeating" (SOURCE) | "THE OPERATING STANDARD for implementation work … In force from the next work package" (SOURCE); standards document explicitly not created | **KNOWN-DIFFERENT** |
| state_before | candidate, unpromoted (SOURCE, weak: "Parked candidate") | parked candidate (SOURCE) | literally different; see note |
| evidence | **0 slices** (SOURCE: WP-1 not yet started — "vs. starting WP-1"; "WP-1 OPENED" follows) | 2 slices (SOURCE: "Evidence today = **two** slices (WP-1, WP-2)") | known, differ |
| exception | **NO** (SOURCE: normal bar applied; no exception recorded) | NO (SOURCE: option (a) only offered) | known, equal |
| outcome | NOT-RAISED | RAISED (operating standard only) | differ |
| ground | RULE (SOURCE: "evidence-before-promotion"; corroborated by P2's "explicit promotion criterion" and "the normal bar") | UNK, or n/a for an adoption | P1 = RULE |

**Result under §3:**
- `kind` is known and different, and so is `target`. Either one alone gives **NOT A WITNESS**.
- The evidence requirement is now satisfied (0 vs 2), and so are the outcome and P1-ground requirements. The failure lies only in the compared fields.

**About `state_before`:**
- Literally, "candidate" differs from "parked candidate", so under A's coding it is also decisive.
- But any pair where the first disposition *is* the parking will differ this way by construction. Parking also leaves the standing unchanged: the candidate stays unpromoted, "not actioned".
- If both are coded "unpromoted candidate", they are equal. The reviewer therefore does not rely on this field.

**Decisive fields (reviewer):** `kind`, `target`.

**Supporting evidence A did not use.** At P1 the execution contract was *already operating*:
- "WP-1 contract validated as three reinforcing layers"
- "SessionStart injects the WP-1 contract"
- "Auto mode, per the recorded contract"

P2's practical note draws the same parallel: "as the WP-1 plan carried the execution contract". So on each facet the two dispositions *match*:
- operating use: in force at P1 and in force at P2;
- document promotion: not raised at P1 and not raised or pending at P2.

When the same facet is paired, the outcomes do not differ. This is another, independent route to NOT A WITNESS.

## Check 7 — Strongest alternative that would change the classification

**The reading.** Code the candidate at the level of the abstract discipline:
- `kind` = "implementation execution contract/protocol" in both;
- `target` = "going-forward standing for how future WPs are governed" in both (P1: "future WPs reference instead of repeating"; P2: "going-forward protocol … In force from the next work package");
- `state_before` normalised to "unpromoted candidate" in both;
- `exception` NO/NO;
- `evidence` 0 vs 2;
- outcomes NOT-RAISED vs RAISED;
- P1 ground RULE;
- `actor` UNK.

That would give **POSSIBLE WITNESS**: evidence rises from 0 to 2 and the candidate is put in force.

**Does the text rule it out? Yes, on three counts:**
1. P1 fixes the kind as "reusable doc". P2's header explicitly denies that what was adopted is a document: "NOT self-promoted into a standards document".
2. P2 states that the P1 candidate's promotion is still undecided and belongs to the DA. The recommendation is **(b)** "hold to the parked criterion and let WP-3 supply the third instance". So for P1's target, P2's outcome is NOT-RAISED, not RAISED.
3. P1 shows the operating contract was already in force before the parking ("SessionStart injects the WP-1 contract"). The "put in force" facet therefore did not change between P1 and P2, and the evidence increase did not flip anything.

Other alternatives, as A lists them: P1 ground as CHOICE; objects as different; P1 evidence as 0. Each still gives NOT A WITNESS. The reviewer agrees. The reviewer also notes that the CHOICE reading of P1's ground is weaker than A suggests: "further contract polish would not produce proportionate value" is about closing the chapter and polishing the WP-1 contract, not about the extraction decision.

## Disagreement register

| # | Field / point | A | Reviewer | Type | Changes classification? |
|---|---|---|---|---|---|
| 1 | P1 `evidence` | UNK (inferred 0) | 0 slices, SOURCE | coding | No. It satisfies the evidence requirement; kind and target still fail. |
| 2 | P1 `exception` | UNK | NO, SOURCE | source interpretation | No. Exception becomes known and equal. |
| 3 | P2 `kind` | "protocol / operating standard" | protocol. "Operating standard" is the target. | coding | No |
| 4 | P1 `state_before` quote | "one candidate parked" | The quote shows the post-state and does not support "before" | wording | No |
| 5 | `state_before` as decisive | decisive (candidate ≠ parked candidate) | Different by construction, and parking leaves the standing unchanged; not relied on | source interpretation | No. kind and target suffice. |
| 6 | P1 `target` quote | criterion quote | The target's words are "reusable doc … that future WPs reference instead of repeating" | wording | No |
| 7 | P2 `exception` quote | "(R-39 ...) recorded as an exception" | The clause sits inside the R-39 parenthesis; the elision moves it | wording | No |
| 8 | P2 `actor` | PA issuer; adopter unnamed | PA gives the standing; implementer is the recipient; DA only for promotion | coding | No. Actor equality is UNK either way. |
| 9 | P1 ground: strength of the CHOICE alternative | RULE, with a live CHOICE alternative | RULE. The "proportionate value" clause concerns the chapter closure, and P2 corroborates a standing bar. | source interpretation | No |
| 10 | Operating contract already in force at P1 | not used | "SessionStart injects the WP-1 contract" / "as the WP-1 plan carried the execution contract" show matching dispositions on each facet | evidence scope | No. It strengthens NOT A WITNESS. |
| 11 | Same object | "YES (asserted, qualified)" | Same candidate idea, different artifact. The paired dispositions act on different facets (promotion vs operating adoption). | source interpretation | No |

There are no disagreements of type *independence*, *formal reasoning* or *substantive*. A's application of §3 is formally correct given A's coding. The two classifications are identical.

## What the text can and cannot establish

**The text can establish:**
- P1 parks the extraction of a reusable doc under a named principle and an explicit criterion.
- At P1, zero slices in the criterion's unit had been completed.
- P2 records two slices.
- P2 puts a PA-issued protocol in force as the operating standard, and explicitly does not create a standards document.
- The P1 candidate's promotion is still pending with the DA, with a recommendation to hold.
- An operating contract was already in force for WP-1 at the time of P1.

**The text cannot establish:**
- Whether the chair and the PA are the same authority.
- Whether P2's identity claim ("in substance") would be accepted by an authority, as opposed to the implementer.
- What the DA will decide, and therefore whether promotion will be made as an exception (a) or on the criterion (b).
- Any case in which additional evidence alone moved the *same* object, on the *same* target, from NOT-RAISED to RAISED.

That last point is why the pair is not a witness for `evidence`.
