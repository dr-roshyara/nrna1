*Reviewer's steps 2–3 report, window C16, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — WINDOW C16

*Independence: this review ran as a separate process. That is not independence of person, model or estate. I did step 1 blind; my saved inventory is the basis for the coverage section.*

## 1. Assessment
§8.9 reports its sources faithfully, and the §1.12 integration mostly keeps each claim's meaning. Four derived readings go beyond their evidence:
- **C1** applies to all content a rule the source states only for claims re-derived from primary evidence.
- **C2**'s derivation (Ded) does not fit the case it rests on, and does not state the assumptions it needs.
- **C7** reads the two tracks as "independent applications", a reading the book has already rejected once (§6.6).
- **C4** gives as current a status that S0091 later withdrew.

The largest risk is C7 leaking into the open question K3 (does the kernel serve one application or several independent ones; a question for the Principal) as if it were evidence.

## 2. Findings (ranked)

| # | Sev. | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | major | defect | §8.9 C2 Ded, the eligible pool | S0089 L28; S0090 L17–28 | (a) "E(o) shrinks with every verification" is false whenever the verifier was already ineligible, and both observed verifiers were ineligible before they started. Neither shrank E by verifying o; their conflicts came from earlier work on the protected Track 1. (b) "Can become empty" needs two unstated assumptions: a closed population, and eligibility judged by identifier. In this estate an identifier is self-declared and costs nothing to create, so a fresh session meets "no authorship" trivially (trivial-solution test). §8.6 C1 already says a new identifier is not an independence factor. Under §8.6's factors the pool may be empty from the outset, not run out over time | CON-0151's second half is INVALID as stated | Restate: E is non-increasing, and drops only when an eligible process authors o or what o protects. Empty only if the population is closed. Name the identifier basis and link §8.6 C1 and TODO T1-19 (eligibility is relational) |
| 2 | major | defect | §8.9 C7 Int, "two independent applications over one shared contract" | S0087 L12–15 | Track 2 is EKS platform evolution, i.e. the platform side, not an application. The two tracks are one programme, one repository, one estate. §6.6 (L46) rejected the same reading for an earlier case. Also, "owned by neither" drops "track": S0087 L22 assigns the shared part to "a third authority". The rule is a stated prohibition; the sources do not show that it held | Could be counted as evidence for K3, which is undecided (§9.6.6a) | Withdraw the "independent applications" reading. Write "owned by neither track; changed only by a third authority's act". Mark it as a stated norm. Keep the Hyp |
| 3 | major | defect | §8.9 C4 corrected status "live, narrow, unmodelled" | S0091 L761–769; S0090 L116 | Correction #2 withdrew that status as "NOT ESTABLISHED" and replaced it with CONTESTED: AST-007 may be an instance of CAP-09, not of C-14. §8.4 C3 (L38) already records "contested". So §8.9 contradicts §8.4, and so does THEORY-MAP L201 | C4's three questions miss the question that comes first | Add that question: which capability is the observed behaviour an instance of (a classification needing an act). Use the CONTESTED status |
| 4 | major | defect | §8.9 C1 / CON-0150 scope | S0090 L233 ("Every correction it makes is a claim about primary evidence") | The book generalises to "a work product's claims". It fails for first-hand, self-declared content (F-9, S0091 L102) and for lineage claims ("I did not read it", S0091 L715). There, how much the content weighs depends on who produced it. "One source" is also wrong: §8.6 L59 (S0058 L49–51) is an earlier instance, and the book search missed it | Over-generalisation; a home and a support count are missing | Scope C1 to claims that can be re-derived. Add §8.6 C3 as a home. Count two instances from the same estate |
| 5 | major | defect | §1.12-2, S0090 L233–234 added as "the same two questions" | S0090 L232–234 | Valid/verifiable is about whether acts occurred versus whether records exist. Content/accountability is about whether claims are true versus who answers for them. These are different distinctions, and S0090 actually shows a record failing (no producer) while the content holds. Equating them is a new claim, which Rulebook §4 forbids | A conflation enters Ch. 1 | Replace with a cross-reference: "a related but distinct distinction, §8.9 C1" |
| 6 | major | omission | §1.12-2 evidence | S0090 L30 | The case 1.12-2 names, a record written by an assistant on the decider's behalf, occurs here: the verifier "transcribed the PO/ARB's START act … myself", and the same line states "recording an act is not granting one". §8.9 C9 uses it only as a breach of the roles law | The best supporting instance is missing | Add S0090 L30 to 1.12-2 as supporting evidence |
| 7 | major | omission | D2: one actor, two identifiers | S0089 L13 vs L28; S0091 L562; S0090 L17 | Verifier #1 is `1c8b041b` and also "the Track-1 implementation engineer (`4c6c1dac`)". The lineage line of §8.9 lists only two IDs | C1 (binding to a producer) and C2 (eligibility per identifier) both assume a stable identity | Record the case under C1/C2 and in §5.5 / F13a |
| 8 | major | omission | D5: a self-report tagged OBSERVED and counted as corroboration | S0091 L20, L102, L593, L841; S0089 L106 | F-9 is "OBSERVED (first-hand, self-declared)". The verifiers' own self-recorded STARTs are counted as "independent corroboration". This is a direct instance of 1.12-3 and §8.6 C4, inside the corpus's own epistemic tags | Missing evidence for Ch. 1 / §8.6 | Add to §8.6 C4 (or §1.12-3): three self-reports of one kind are one line of support, not observation |
| 9 | major | omission | Decision rights follow the nature of the activity (F-2a) | S0091 L92 | "The AI decides" for derived facts of executable checks, recommends on judgments, and humans hold gates on authority, value and entrenchment. No hit anywhere in the book | A candidate law of authority (who may decide) is missing | Home: §1.6 / §8.6 C6. Status Src; Hyp as a law |
| 10 | minor | defect | §1.12-1 added sentence "never a replacement" | DR-AB L9 (quoted in the section) | The source is conditional: "if a separate ratification ceremony is required". The added "never" makes it unconditional | A small silent strengthening | Keep the condition |
| 11 | minor | defect | §1.12.99 "No claim … has been refuted" | BASELINE-REVISIONS R1-12a | R1-12a corrected the derivation "every one is a self-report diverging". Under Rulebook §4 the reading that did not hold belongs in "What does not hold" | Book form | List R1-12a's original reading there |
| 12 | minor | omission | D1: corrections still not visible after Correction #2 | S0091 L82, L101, L237, L323, L349–350, L356 | Stale, unmarked statements remain (the C-14 headline, "39", "no actors at all", personas, the SB-3 pointer) after the producer said its edits were "verified after writing" (L648) | A recurrence relevant to §8.9 C5 | Add as a recurrence, from the same producer |
| 13 | minor | defect | §8.9 C6 Int "measuring changes what is measured" | S0090 L151 | The drift came from the verifier's governed work (a handoff), not from the measurement itself | A causal overreach | "governed work, including the measurer's own, changes the count" |
| 14 | minor | omission | "Role" has three meanings | S0091 L169–172 | The homonym is not recorded (lane role · persona · decision right) | Ubiquitous-language gap | Add beside the "authority" homonym (THEORY-MAP L259) |

Source defects that the theory does not rely on are listed separately in §4.

## 3. Concept verdicts
- **CON-0150: PROVISIONAL.** Valid for content that can be re-derived from primary evidence. Not shown for first-hand or lineage-dependent content (finding 4).
- **CON-0151:** split verdict.
  - "Recusal ≠ clearance": **VALIDATED** as a distinction. A conflicted verifier's evidence check is not counted as clearance [S0089 L28; S0090 L196].
  - The pool-exhaustion Ded: **INVALID as stated** (finding 1). It can be restated.
  - Concept overall: **PROVISIONAL**.
- **CON-0152: PROVISIONAL.** The Hyp for "kernel law" is kept correctly. The "independent applications" Int is **INVALID as stated** (finding 2).
- **§8.9 C3: VALIDATED** as an instance of §8.4 C3.
- **§8.9 C4: PROVISIONAL**, until it is restated with CONTESTED (finding 3).
- **§8.9 C5: VALIDATED** as an instance.
- **§8.9 C6: VALIDATED** for the count drift. The causal Int is unsupported (finding 13).
- **§8.9 C8: VALIDATED** (Src instance; the anchors check out).
- **§8.9 C9: VALIDATED.** The self-reports should be labelled as such.
- **§6.7 C1 bullet (C16): VALIDATED.** It is time-indexed ("at the time"), and fits §6.7's lineage (first committed 2026-08-28). Its anchors use round brackets; that is formatting only.
- **§1.12 integration (Rulebook §4), claim by claim:**

| Claim | Meaning preserved | Book form |
|---|---|---|
| 1.12-1 | yes, except finding 10 | yes |
| 1.12-2 | S0095 L141 is integration, not an upgrade: it supports the claim, keeps its status, and says what it does not show. S0090 L233–234 is a mis-integration (finding 5); it does not upgrade the status, but it shifts the meaning | yes |
| 1.12-3 | yes. The added warrant ("does not depend on the record being of an act") states a step the old text left implicit; it adds no claim | yes |
| 1.12-4 | yes. R1-12a's wiring-declaration correction is kept | yes |
| Section as a whole | — | revision tags 0 (I checked with grep); change-log row present; 1081 → 1233 words (confirmed). Finding 11 remains |

## 4. Coverage of my step-1 items
- **Ranked gaps:** 1, 2, 4, 6, 7, 8, 9 above.
- **New fork:** *what is eligibility relative to?* The options are identifier, factor set (§8.6), or the relation to the object (T1-19). C2 depends on the answer.
- **§8.4 needs:** a link to finding 3 (§8.9 must agree with §8.4 L38), and D1 as a recurrence for C5. D8 is already there (C4).
- **D6:** the book avoided the source's overreach (from "self" to "agent" to "only a platform service"). §8.6 L60 states only the case of a process attesting itself. **JUSTIFIABLY EXCLUDED.**
- **D7:** COVERED, §9.5 L30.
- **D9:** partly covered (§8.6 L60). The capability-first guard also appears in the brainstorm (S0091 L713), so the commission's framing may share an origin with it. **MISSING, minor.**
- **Source defects, listed separately:** D3, record counts 15/17/16/16 (an append-only set cannot shrink); D11, C2.6 overstates V1's "declining"; the date of §8.9's lineage line (S0091 includes Correction #2, dated 2026-08-19).
- **My own step-1 defect:** my S0088 anchors were offset by +44, because I numbered a combined listing. The book's anchors (L24, L40, L46) are correct.

## 5. Next steps (dependency order)
1. Restate the C2 Ded (finding 1). Done when the assumptions are stated, the identifier basis is linked to §8.6 C1 and T1-19, and the case is matched.
2. Rewrite C7's Int (finding 2). Done when the "independent applications" reading is gone and the third authority is named.
3. Correct C4 and THEORY-MAP L201 to CONTESTED, and add the classification question.
4. Scope C1 and add the §8.6 home (finding 4).
5. Repair 1.12-2 (findings 5 and 6), 1.12-1 (finding 10) and 1.12.99 (finding 11).
6. Record D2, D5 and F-2a (findings 7–9).

## 6. Limits
- **Not read:** §8.6 in full, §5.5, §6.5–§6.6, §9.6 beyond the lines I grepped, and the C15 settlement.
- **Not verified:** S0095 beyond L141, and DR-AB/SI (they are under docs/knowledge_tranfer, which is forbidden).
- **Book searches were lexical greps,** so a miss counts only for the terms searched.

## Appendix: step-1 items, minor coverage
- **COVERED:** S0087 1, 3, 4; S0088 2–4; S0089 1–4, 10; S0090 1–5, 8, 9, 11, 12; S0091 2–3, 6–7, 10–11, 14.
- **MISSING (minor):**
  - S0087 2: bounded independence;
  - S0087 6: posture enforced by grant;
  - S0089 6: falsification method;
  - S0089 8: a structural mechanism implies a capability gap, rather than a single defect;
  - S0089 9 and S0090 13: "consumption" downgraded to INFERRED;
  - S0091 8: "controlling the lifecycle is not owning what flows through it";
  - S0091 12: I-K2, evidence identity independent of storage location.
- **JUSTIFIABLY EXCLUDED:** procedural boundary statements (S0087 7–8; S0088 5–9; S0089 12).

