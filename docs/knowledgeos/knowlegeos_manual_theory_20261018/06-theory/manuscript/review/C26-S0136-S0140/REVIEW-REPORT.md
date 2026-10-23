*Window reviewer W (Opus), steps 2-3 review report, verbatim (saved by the Professor at 2026-10-23T18:28:35Z). sha256 of the hand-back text at receipt: `bcb7be1f77b1a4cee33ce13a703c7ad67b46bede596d7ab496882eb672dcce1d` (UTF-8, the text below this line exactly).*

C26 STEP 2-3: REVIEW REPORT

**What I checked first.** I re-read my saved inventory before comparing anything. Its hash matches 04d52c41… when computed over the text from line 3 on, with the trailing newline stripped.

**What I ran** (all local, read-only):
- `corpus_quote_check.py`: §8.19 gives 34 found, 0 not found; §8.18 gives 69 and 0.
- Register counts recomputed: 247 rows, 40 VALIDATED, 113 PROVISIONAL, 94 INVALID.
- Every book rule §8.19 quotes, checked against its home by grep: L9, L10, K6, K7, G3, §9.13 Q1, §9.16 R2, CON-0229. All match.

The quote tool tests exact quotes only. I spot-checked the "in meaning" paraphrases by hand.

## 1. Assessment
1. Inside its scope, the section's reading of the sources and its lineage are reliable.
2. The concepts are not. Two of the three new ones fail as stated: CON-0246 and CON-0247 each claim a "must" or "only" that a counterexample defeats.
3. Largest risk: twice §8.19 "settles" §8.18 items that §8.18 says only S0300 can settle.

## 2. Concept verdicts

| Concept | Verdict | Reason and scope |
|---|---|---|
| **CON-0245** acknowledged is not recorded | **PROVISIONAL** | Holds as a model-level deduction. It is incomplete: an acknowledged write can also be lost by a crash (no `fsync`, which C9 files under K1). So L9 needs K6 **and** K1's durability (W-07). The "checked as a possibility" basis covers the lost update, not the acknowledgement clause. K6 is the right home for the serialisation part: K1's text covers losses "outside" the kernel's steps, and K4 is for merge-violable laws |
| **CON-0246** status must be in the artifact | **INVALID as stated** | The term contradicts its own meaning for byte-verified evidence, which can carry no in-file status. The path form fails (W-02). The S0137 amendment (no in-file marker; an out-of-band marker is advisory) and the S0138 retraction are stated correctly |
| **CON-0247** expected writes named in advance | **INVALID as stated** | "Must be a predicate" and "must bind an authenticated actor" are both refuted (W-01). The core claim survives: "nothing written except the declared", detection of unexpected writes only. Application level, not kernel |
| **CON-0240** (medium premise added) | **PROVISIONAL** | The premise is correct but one-directional. C15 / S0137 L209 require no conversion in either direction (W-17) |
| **CON-0243** (destructive-step clause added) | **PROVISIONAL** | Guarding the destructive step is necessary but not sufficient: an irreducible interval remains (C19, S0138 L116) (W-18) |

## 3. Findings (ranked)

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| 01 | **CON-0247: the necessity claims fail.** (i) Counterexample: declare a predicate in advance, but check the delta against an identity list the operator logs as it writes. That check is exact, and it catches a forger writing under the lane's name without authenticating any entry. So a predicate check is not necessary, and L10 is sufficient, not necessary. (ii) Trivial case: the predicate "everything" satisfies "named in advance" and detects nothing, so there is a must-never with no must-do (an exclusion or bound). (iii) A loss *among* the expected writes stays undetectable. (iv) The book works at entry level, while S0140's "delta ⊆ declared **records**" works at record level, which masks any write to the migration's own record file. The book refines this silently | high | C17 L144; C25 L193; S0140 L66–71; §9.15 L10 | Restate as sufficient conditions; add (ii)–(iv); label the book's granularity against the source's | CON-0247, C17 and C25 carry this |
| 02 | **CON-0246: the path form is refuted** (the Professor's addition, not a source's). The path is the resolution key: defaults compute it (S0136 L181) and the manifest keys files by it. Renaming the runtime store to carry "DEMOTED" redirects the default, and `saveRecord`'s `mkdir` then creates a fresh location: C13's hazard. "Changes no byte" is true and beside the point. The term also contradicts the refined meaning | high | C12 L109; C13; CON-0239 | Rename the concept; drop the path form or mark it Hyp with both conflicts | Register term, C12 and the adversarial-pass row agree |
| 03 | **C2 and C11 "settle" §8.18's pending items C4 (a) and (b) from reviewers' reports.** §8.18 L198 and L211 say these are "settled only by the plan itself", and §8.19 L36 keeps plan content "pending S0300" | high | §8.19 L51, L105 | "bears on, pending S0300" | §8.18.3 and §8.19 agree |
| 04 | **C18: "the remedy uses the phase in place of the time" is false for the case that matters.** The α window (after the Phase-5 re-hash, before the writer switch) lies inside Phase 5, so no phase orders it. With 2 of 216 transitions dated, S0140's tie-break sends every such write to β, which makes α unreachable in practice. C18 also cites CON-0239 for "the record is whichever store the writers resolve". CON-0239 says each reader names its own; the writer-defines-the-record premise is C3's (S0137 L172) | high | C18 L148; S0140 L55; S0136 L258 | Correct the sentence; cite C3; add "α unreachable without write times" | C18 and C26 carry it |
| 05 | **C3's heading says authority moves "when the readers move".** Its body, plain-language item 3, S0137 L172 and S0138 L138 all say the writer. C18 and C26 rest on the writer version | high | §8.19 L53 vs L56 | Fix the heading | Heading matches the body |
| 06 | **C27's rule is contradicted by its own instance.** AMD4's added §4.1 and §4.2 were already cited by the delivered review S0138 (L106, L135, L148), and AMD5 moved them anyway. S0140 L92's rationale is partly false. When both colliding labels are cited, no repair keeps every cited label | high | S0140 L92; S0138 | Restate C27 with the unsatisfiable case; record a source defect | C27 states it |
| 07 | **CON-0245 incomplete** (see the verdict). The lost update was already in the book (§9.11 law 12; K4-o, S0137 L52). S0137's structural reading does not show exit 0 or the acknowledgement | high | C1, C9, C10 | Add K1 durability; scope "checked" to the lost update | Register basis and C10 amended |
| 08 | **C1, "the estate does not meet K6":** K6 is about CE*'s implementation, applied to the estate's writer, which the book places outside D (CON-0239, §9.15). The premise "the writer is a kernel implementation" is unstated. **Critical by F4 only, no dependants.** CON-0245's own conclusion is model-level and does not need it | critical (F4 only) | C1 L44–45 | Label it an analogy | C1 states the premise |
| 09 | **C13 reads the wrong-path record in D terms** without the book's "estate outside D, hypothetical". **Critical by F4 only, no dependants** | critical (F4 only) | C13 L114 | Add the label | C13 states it |

## 4. Adversarial pass, refutation adequacy, proof steps

| Row | Judgement |
|---|---|
| C3 | adequate: a real overstatement was found |
| C11 | adequate |
| C13 | adequate |
| C26 | adequate |
| C19 | adequate as an extension. But it quotes THEORY-MAP L40, not §1.16.7. §1.16.7's own text is stated over writes ("every write passes one serialization point"), so most of the labelled extension is unnecessary (W-16) |
| C1 | **inadequate:** the attack tested novelty but not K1 durability (W-07) |
| C4/C12 | **inadequate:** the amendment introduced an untested form (W-02) |
| C17 | **inadequate:** necessity was not tested against an identity list taken after the fact (W-01) |
| C18 | **inadequate:** the phase-as-proxy assumption was not tested (W-04) |
| Missing rows | C27 (W-06), C23 (W-10), C2/C11 "settles" (W-03) |

Proof steps for "L9 needs K6" (CON-0245):

| Step | Justification | Defined? |
|---|---|---|
| 1 | L9: work is delivered only after the kernel accepts | §9.15 L9 ✓ |
| 2 | L9's purpose: no use left in the world unrecorded | L9 text ✓ |
| 3 | Without serialisation, an acceptance can precede an overwrite | §9.11 law 12; S0136 ✓ |
| 4 | So an acceptance must follow a serialised commit (K6) | steps 1–3 ✓ |
| 5 | **Missing:** and the committed state must survive (K1 durability) | §9.15 K1 "W4"; C9 ✗ not stated |

**Judgement: inadequate, premise missing (step 5).** Rated high, not critical: the statement is incomplete rather than wrong, and nothing cites the missing step. The deduction is close to trivial inside K1–K7.

**Rulebook §6, the six questions:**
1. Order: W-03, W-05.
2. Mathematics: W-01, W-06.
3. Statistics: C10 treats "77%" as a rate. It comes from a forced-contention purposive trial (n=30), so under entry 46 it should be stated as a count (W-14).
4. Computation: quote check and register counts reproduced.
5. Clarity: C11's "true by construction of whatever was not observed" is unclear (W-18).
6. Coverage: §5 below.

## 5. Coverage of my inventory points

| Point | Status | Weight / home |
|---|---|---|
| S0140 (b): α unreachable without write times | MISSING (DISTORTED in C18) | high, W-04 |
| S0140 (c): granularity of "delta ⊆ declared" | MISSING | high, in W-01 |
| S0140 (d): renumbering invalidated S0138's citations | MISSING | high, W-06 |
| S0136 (b): "non-destructive" (L21, L88) falsified by RC-4 | MISSING | normal: a source defect in C11; counts as a defect found later in reviewed work for S0150 (W-13) |
| S0137 (c): CL-1 "CLOSED" beside RC-4 | MISSING | normal: evidence for C23 on what closure means (W-13) |
| S0139 (a): "closed via" (L44–45) against "no self-closure" (L20); stale "six" (L39) | MISSING | normal: C23 accepts L20 at face value (W-10) |
| S0140 (a): RD-1, RD-2, RD-4, RD-5, RD-9 dropped; L13 "only the six" | MISSING | normal: list in §8.19.3 (W-11) |
| S0138 L7 "7 new residuals" against 9+1 defined | MISSING. The book itself says 7 (L137) and also 10 (L168) | normal: add to the count-versus-content class (W-12) |
| S0136 (c): 77% generalised to the migration window (L160) | DISTORTED (C10 uses it as a rate) | normal (W-14) |
| Density claim originates in S0131 L25 and L121, not the plan alone | MISSING | normal: cross-reference §8.18 (W-15) |
| Everything else in my inventory | COVERED | — |

**Depth.** The Professor's full read of S0139 produced C23 and C24. My skim found the same two points and also the two self-contradictions that the full reading missed. So the full reading added no theory there, and skimming S0139 would have lost nothing. For S0140, the full reading produced C25–C27, all of which were in my inventory's §2-of-S0140 points.

## 6. Book rules, lineage, specific questions, settlement

- **The C23 rule.** L9, K6, K7, G3, R2, §9.13 Q1 and L10 are quoted exactly and applied within their text. The exceptions are W-08 and W-09: K6 and D are applied to the estate without the "outside D" label. §1.16.7 is quoted through the map (W-16).
- **Lineage.** Correct, including:
  - AMD3–AMD5 have no grants and rest on an unregistered commission;
  - five producers say "accepted" with no act cited;
  - the producer sets its own next reviewer's exclusion list.
- **C18:** sound only with C3's writer premise, not by deduction from CON-0239 (W-04).
- **C26:** "promoting a non-record" is a fair reading of S0140 L54. Quarantine plus escalation is not silent, so the claim survives.
- **The §8.18 C21 note is right.** Sharpen it: S0135's figure is derived from the present state, assuming append-only writes and no unlogged loss, which is the very assumption S0136 shows can fail (W-19).
- **C25 settlement §1.** I checked eight rows: W-01, W-02, W-03, W-04, W-08, W-09, W-15 and the CON-0243 row. All hold in §8.18 and the register. The W-16 map position was correct at that settlement.

## 7. Summary
1. The reading and lineage of S0136–S0140 are faithful. Quotes pass 34 of 34.
2. CON-0247 is INVALID as stated: an identity list the operator keeps of its own writes defeats both "must" clauses, and the trivial predicate shows a must-do is missing.
3. CON-0246 is INVALID as stated: the term contradicts its own meaning, and the Professor's path form collides with path-keyed resolution and the `mkdir` hazard.
4. CON-0245 is PROVISIONAL: it needs K1 durability as well as K6.
5. §8.19 settles two §8.18 items on reports, against §8.18's own condition.
6. C3's heading says readers where its body says writer. C18's phase proxy fails inside Phase 5. C27 is contradicted by its own instance.
7. Two findings are critical by F4 only, with no dependants (W-08, W-09).
8. Ten inventory points are missing or distorted; six are normal weight.

**Process independence:** the independence here is between processes, not between people. I am a fresh session that saw none of the Professor's work before my inventory was saved.

---
### Appendix (normal)
- **W-10:** add S0139's L20 against L44–45 contradiction to C23.
- **W-11:** list the dropped residuals in §8.19.3.
- **W-12:** fix the book's 7-versus-10 count; add S0138's header to the count class.
- **W-13:** S0136's L21 and L88 verdicts, and S0137's CL-1 "CLOSED", as defects found later in reviewed work.
- **W-14:** state 23/30 as a count, not 77%.
- **W-15:** S0131 L25 and L121 are the origin of the density claim.
- **W-16:** quote §1.16.7's text.
- **W-17:** CON-0240 should say no conversion in either direction, citing S0137 L209.
- **W-18:** CON-0243 should say "necessary, not sufficient"; clarify C11's sentence.
- **W-19:** sharpen the §8.18 C21 note.

**Limits:**
- I did not run `proof_step_check.py`, the R1 check or the inbound-citation check.
- I did not read S0300, so claims about the plan's content stay the reviewers'.
- No skimmed source existed, so the §3 safeguard did not apply.
