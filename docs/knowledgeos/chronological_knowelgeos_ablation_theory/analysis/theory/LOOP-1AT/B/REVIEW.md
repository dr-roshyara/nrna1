# Independent review of Researcher A (ANSWER.json / ANSWER.md)

Files used: `REVIEW-TASK.md`, `TASK.md`, `SOURCES.md`, `CODED.json`, `ANSWER.json`, `ANSWER.md`. Nothing else was used.

**Verdict: `e_CONFOUNDED`.** I agree with A's headline result (n = 0 evidence-only pairs). I disagree with part of A's reasoning, and I raise one selection issue that A did not.

---

## 1. Item mapping and verbatim quotes

I checked every quote in ANSWER.json against SOURCES.md. **All evidence quotes are verbatim.** The mappings are correct:

| Event | Matrix item | R-36 anchor | Mapping strength |
|---|---|---|---|
| promoted #2 | #2 Reuse Before Create (A) | "reuse-before-create" | verbatim |
| promoted #3 | #3 Ownership Drives Reuse (A) | "ownership-determines-architectural-reuse (never precedent)" | **stronger than A says.** The matrix #3 recommendation text reads "reuse is justified per-pattern by ownership, never by precedent", so R-36's "(never precedent)" repeats matrix wording. A calls this mapping "an inference". |
| promoted #9 | #9 Surface gaps / Deferred ≠ skipped (A) | "deferred ≠ skipped" | verbatim |
| promoted #10 | #10 Epistemic labeling (A) | "epistemic labels (Observed·Measured·Derived·Interpreted·Recommended …)" | verbatim label list |
| not promoted #5 | #5 Registration ≠ Delivery (C, + generalization → D) | "Registration ≠ Delivery stays Messaging-scoped in ADR-MP-06 (1 slice)" | named explicitly |
| not promoted #14 | #14 Strangler reconstitution (D) | "Strangler reconstitution … remain Candidates" | named explicitly |
| not promoted #17 | #17 `ChallengeResolvedIntegration` carrier (D) | "`ChallengeResolvedIntegration` carrier … remain Candidates" | named explicitly |

**Cross-check (supports A):** R-36's Promotion Report tallies all agree with the matrix once #5 is counted in both C and D:
- A: 4 matrix items + 2 added by ARB = 6.
- B: #6 and #11 = 2.
- C: #5, #12, #15, #16 = 4.
- D: #14, #17, #18, #19, plus the #5 generalization = 5.
- already: #1, #4, #8, #13 = 4. #7 leaves this group because ARB promoted it.

This confirms that R-36 was built from this matrix, and that the #5 generalization is counted as a Candidate.

## 2. Is each coded `e` stated, derived, or not supported? (with units)

| Event | coded e | Source value | Unit | A's basis | Reviewer's basis |
|---|---|---|---|---|---|
| #2 | 2+ | 3 (PB-004, PB-005, PB-006) | slices, which here are the three tickets | DERIVED | **DERIVED**: the bucket 3 ≥ 2. Agree. |
| #3 | 2+ | 3 | slices = tickets | DERIVED | **DERIVED**. Agree. |
| #9 | 2+ | 3 | slices = tickets | DERIVED | **DERIVED**. Agree. |
| #10 | 2+ | "2–3" | nominally slices. In tickets the count is 3 (PB-004, PB-005, PB-006), plus one document (Handover §9) | DERIVED (weak) | **DERIVED (weak)**. Agree. Every reading of the range gives ≥ 2, so the bucket is robust even though the count is not. |
| #5 | 1 | 1 (matrix); "(1 slice)" (R-36) | slices; one ticket, PB-006 | STATED | **STATED** in slices. Agree. This is the only non-promoted value stated in slices. |
| #14 | 1 | "1 context only (Election)" | **contexts** | STATED | **STATED in contexts, DERIVED in slices.** The number 1 is stated only in contexts. If the value has to be expressed in slices, like the other coded values, a slice count of 1 can only be derived: the pattern is "documented in PB-004 retro §9", and PB-005 "deliberately did NOT reuse it". → *coding* disagreement |
| #17 | 1 | "1 — … (PB-005 Q1)" | A: unspecified. Reviewer: slices, because the column header applies and the single cited ticket is PB-005 | STATED | **STATED.** The unit is most plausibly slices/tickets, not "unspecified". → *source interpretation* disagreement (minor) |

**Overall:** the four "2+" values are derived threshold buckets. The three "1" values are stated, but in mixed units: slices (#5), contexts (#14) and slices by header (#17). No coded `e` is unsupported. The coded `e` does compare unlike units, and A flags this correctly.

## 3. Adoption (R-36) vs recommendation (matrix)

- I agree that **R-36 decides**. It is headed "ADOPTED". It promotes #7, which the matrix marks "already … do not duplicate it". It adds "implementation-evidence-outweighs-unverified-theory", which does not appear in the matrix. It says "2 added by ARB at review".
- For all 7 coded events, R-36 and the matrix agree on the outcome.
- **A does not fully carry this distinction into the reason types (evidence-scope point).** R-36 gives a reason only for #5 ("stays Messaging-scoped … (1 slice)"). For #14 and #17, R-36 gives no reason; it just lists them under "remain Candidates". Their reasons ("does not generalize by default"; "explicitly ruled 'temporary carrier, not a pattern'") come from the **recommendation**, not the adoption. A notes that "R-36 gives no reason" but then types these reasons without marking them as matrix-level.
- **Coded outcome "REFUSED" (coding issue, not raised by A).** R-36 supports "Expressly NOT promoted". However, #14, #17 and the #5 generalization "remain Candidates", and #17 is "replaced only on repeated evidence". Their status is "deferred pending evidence", not "refused".

## 4. Are the non-promotion reasons evidential?

| Item | Stated reason (quote) | A | Reviewer |
|---|---|---|---|
| #5 | R-36: "stays Messaging-scoped in ADR-MP-06 (1 slice)"; matrix: "Stays in ADR-MP-06 (Messaging-scoped)" | SCOPE, with an evidence note | **SCOPE.** Agree. The evidence reason ("has ONE demonstration → Candidate Pattern") applies only to the *generalized* form, which is not the coded item. |
| #14 | "1 context only (Election). PB-005 deliberately did NOT reuse it — evidence it does not generalize by default" | DOMAIN-SPECIFICITY | **MIXED, and evidence and domain cannot be separated.** The source itself calls this "evidence", and it is negative evidence (a deliberate non-reuse), not just a low count. But the count is literally "1 **context**", so here the evidence measure *is* the domain restriction. Evidence and domain-specificity cannot be separated for this item. → *source interpretation* |
| #17 | "explicitly ruled 'temporary carrier, not a pattern' (PB-005 Q1)" | PRIOR RULING | **PRIOR RULING.** Agree. Evidence appears only as the condition for revisiting ("replaced only on repeated evidence (ER-02)"). |

**No non-promotion reason is a pure "count below a bar".** No bar for Category A is stated anywhere. The only stated bar, "2 slices is the floor", appears under #6, which is Category B. A says the same.

## 5. Evidence-only contrast pairs (recomputed)

All 7 events are in cluster R-36, so all 4 × 3 = 12 pairs are within one cluster. **Recomputed n = 0.** The unit is slices. Each pair is confounded as follows:

| Non-promoted partner | Pairs | Confound other than evidence |
|---|---|---|
| #5 | (#2,#5) (#3,#5) (#9,#5) (#10,#5) | scope: "Messaging-scoped" |
| #14 | (#2,#14) (#3,#14) (#9,#14) (#10,#14) | domain-specificity ("1 context only (Election)"); the unit is contexts, not slices; plus counter-evidence (PB-005 non-reuse) |
| #17 | (#2,#17) (#3,#17) (#9,#17) (#10,#17) | a prior ruling (PB-005 Q1) |

In addition, every pair involving #10 carries an unresolved range with mixed units ("2–3", including Handover §9).

**Formal-reasoning disagreement with A.** A's main confounders are category (A vs C/D) and destination (AST-013 vs ADR-MP-06 or the backlog). These are circular: Category A is *defined* as "promote to AI Architecture", and D *is* "remains Candidate Pattern". Category and destination are the recommended outcome itself, not independent properties. Only the stated **reasons** (scope, domain, ruling) are valid confounders. A's n = 0 still holds, but only because of the reasons, not because of category or destination.

**Selection issue within the same cluster (not raised by A).** The coded sample contains 7 events. It leaves out other R-36 items whose evidence does not follow the pattern that the coded set shows:
- **#18 PGP-03 hoist** has "2-ish" evidence and is "Expressly NOT promoted" ("remain Candidates"). It has about the same evidence as promoted #10 (2–3) but the opposite outcome.
- **#6 Domain Event ≠ Integration Event** has 2 slices and was not promoted into AST-013; it became a "Category-B follow-up".
- **#11 process chain** has 3 slices and was not promoted into AST-013 (Category B).
- **#4 and #7** have 3 slices each and are "already". R-36 nevertheless promoted #7 and not #4.

So among R-36 items with ≥ 2 slices, some were promoted and some were not. The different outcomes follow category or scope and ARB judgement, not the count. TASK.md also says the `e` coding was assigned by outcome ("'2+' for promoted items, '1' for not-promoted ones"). The clean split between 2+ and 1 therefore comes from how the 7 events were chosen and coded; the sources do not show it. → *independence / evidence scope*

## 6. Strongest alternative reading

**Alternative reading:** category and destination are outcome labels, so they are not confounders. The #14 reason is evidence by the source's own wording ("evidence it does not generalize"). Under this reading, #14 × {#2, #3, #9, #10} gives up to **4 evidence-only pairs**, and the coded `e` would be supported within the cluster.

**Why I reject it:**
1. #14's count is in contexts, which is not comparable with the slice counts of the promoted items.
2. "1 context only (Election)" is a domain restriction by construction, so evidence and domain-specificity cannot be separated. REVIEW-TASK.md item 4 says this case makes evidence a confound.
3. R-36 adopted no reason for #14. The evidential reading rests only on the recommendation.
4. The uncoded #18 (2-ish, not promoted) against #10 (2–3, promoted) shows that, inside R-36, evidence counts do not separate the outcomes.

The alternative gives an upper bound of 4. The defensible count is 0.

## Disagreements with A

| # | Topic | Class | Severity |
|---|---|---|---|
| D1 | #14: `e`=1 is stated only in contexts. In slices it is derived, not stated. | coding | medium |
| D2 | #17 unit: slices/tickets (header plus a single PB-005 citation) rather than "unspecified" | source interpretation | low |
| D3 | #3 mapping is anchored by matrix wording ("never by precedent"). It is not merely an inference. | wording | low |
| D4 | Category and destination are outcome-circular, so they are not valid pair confounders. Only the stated reasons are. | formal reasoning | medium |
| D5 | #14 reason is mixed evidential/domain (the source calls it "evidence"), not pure domain-specificity | source interpretation | low–medium |
| D6 | #14 and #17 reasons come from the matrix (recommendation); R-36 adopts none | evidence scope | low |
| D7 | Coded outcome "REFUSED" does not match "remain Candidates" (deferred pending evidence) | coding | low |
| D8 | t=AST-013 for the non-promoted items is a defensible inference (R-36 is the review for promotion into AST-013), not simply "not stated" | source interpretation | low |
| D9 | The coded sample omits R-36 items with ≥ 2 evidence that were not promoted (#18, #6, #11, #4). The `e` split is keyed to outcome, so the evidence–outcome link comes from sample selection. | independence | **high** |

I agree with A on everything else: the verbatim quotes, the DERIVED basis for the four "2+" values, R-36 as the deciding source, the reason types for #5 and #17, the absence of a Category-A bar, and n = 0.
