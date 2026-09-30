# NOTES: hard cases

Scope: 180 values across 33 records. R24, R28 and R35 have no source span and no values, so there was nothing to map for them. The only inputs used were VALUES.json and RECORDS.json.

## Rules I applied throughout

- **Whether the act happened is separate from which operation it is.** "did not occur", "not superseded" and "not promoted" do not change the operation.
  - R22 act "promote … (did not occur)" → RAISE.
  - R31 "issue 7B execution authorization (not occurred)" → AUTHORIZE-IMPL.
  - R29 "supersede" (the span shows it was rejected) → SUPERSEDE.
  - Exception: when the value's own verb is the positive act, I mapped that verb instead of the operation it denies.
    - R26 "rule (adjudicate…: not superseded)" → OTHER:adjudicate.
    - R27 "apply (not supersede)" → OTHER:apply.
    - Mapping either to SUPERSEDE would claim a supersession took place.
- **A secondary clause in a value is ignored; two equal clauses are not merged.**
  - R12 "start …; co-stated: plan = PERMITTED" → START-EXECUTION. The value marks planning as secondary.
  - R05 "plan (EP-01) and implement" → **UNMAPPABLE**. PLAN and START-EXECUTION carry equal weight here, and the task forbids merging them. Anyone using this value should know that R-89 (PREPARED) authorizes both.
- **RED, implement, execute and begin implementation all map to START-EXECUTION.**
  - In the spans, RED is the first implementation activity: R-58 says "begin Slice 7B RED", and R-65 says "engineering may begin RED".
  - R11 (committing RED tests) is the act of starting execution, so it gets the same mapping.
- **Authorizations are all AUTHORIZE-IMPL.** Each one authorizes implementation, RED or execution (R04, R31, R32, R34, R36). None authorizes only planning, so AUTHORIZE-PLAN was never used.
  - R05 is PREPARE-ISSUE rather than an authorization, because the span marks it "(PREPARED)" and it was not adopted.

## Operation cases

- **R01 act vs norm.** The act is DECLINE-INTERPRETATION. The norm is about the operation that was declined: adopting a reading that favours the issuer. I mapped that to OTHER:interpret rather than ADOPT, because ADOPT in this vocabulary means adopting a ruling.
- **R06 "file/propose ruling R-90 for adoption" → PREPARE-ISSUE.** The span says R-90 was withdrawn before adoption, so it was never entered in the register. REGISTER, the other candidate, would overstate what happened.
- **R08 and R09 are two views of the same motion (R-60), and both map to CLASSIFY.**
  - The issuing text classified it as a non-ruling (R08).
  - The recorder filed it as a ruling (R09).
  - I mapped R09 to CLASSIFY rather than REGISTER, because the disagreement is about classification.
- **R15–R21 (promotion matrix): the act is CLASSIFY.**
  - "Recommend promotion" is a recommendation, not the promotion itself, so it is not RAISE.
  - The norms "promotion status (Candidate)" in R20 and R21 map to RAISE. The norm is about whether the pattern gets raised.
- **R15 "reuse existing capability" → OTHER:reuse, not REUSE-ID.** This is the main case where matching by string would go wrong. REUSE-ID is about register numbers, which is R25's norm.
- **R25 "retire register number" → OTHER:retire.** It is not WITHDRAW, because WITHDRAW applies to the ruling and R-90's withdrawal is a separate act. It is also not an ID operation in the vocabulary.
- **R02 "govern" → OTHER:govern.** This is the state of being in force after adoption. I kept it apart from ADOPT.
- Norm operations with no vocabulary match were given OTHER labels:
  - `justify` (R16)
  - `record` (R17)
  - `label` (R18)
  - `scope` (R19)
  - `synchronize` (R23)

## Actor cases

- **ARB vs ARB-CHIEF.**
  - I used ARB-CHIEF only where the value says "Chief": R01, R05, R27, R29, R30.
  - R04 (R-70) is ARB, and its span also names ARB, not the Chief.
- **R27 actor and stated_by → ARB-CHIEF.** The value mentions a later Decision Authority adoption (R-86), but the Chief issued R-83. I did not collapse the two into DECISION-AUTHORITY.
- **Matrix rows "Promotion Review matrix, adopted by ARB via R-36" → ARB.** The matrix is a document, and its authority comes from ARB adopting it.
- **Mapped UNMAPPABLE because no author is named:**
  - R22 "ES-006.1": a document rule.
  - R25 "withdrawal annotation (author unnamed)"
  - R31 "recording note to R-56"
  - R08 "the issuing text": both actor and stated_by.
- **R09 "the register's recorder" → OTHER:recorder.** I also used this for R25 "whoever files or numbers register rulings". That is a judgement that the two describe the same role; neither value states it outright.
- **AI actors.**
  - R15–R17 "AI engineering agents" → OTHER:agent.
  - R18 "AI (all AI findings)" → OTHER:AI.
  - I did not map these to ENGINEERING or SESSION-AUTHOR. Nothing in the values or spans says these agents are the "engineering" of R-58 and R-65, or the author of a session. If a reviewer decides they are the same role, all four would become ENGINEERING.
- **R01 norm.about_actor "any self-issued act (here, … ARB Chief)" → OTHER:issuer.** The norm applies to any issuer, so mapping the example (ARB Chief) would narrow it.
- **R23 "whoever authors or closes slices" → OTHER:author, not SESSION-AUTHOR.** It is a general class, not the author of a particular session.

## Target cases

- **Values naming several targets equally, kept as written:**
  - R02 "R-81 through R-85"
  - R14 "Slices 7B and 7C"
  - R36 "R-81 and R-84"
  - None of the named items is more specific than the others, and picking one would be a guess. This departs from "exactly one identifier". If a single token is required, use UNMAPPABLE, not the first item.
- **R01 target → UNMAPPABLE.** The target is the ambiguity itself. R-81..R-85 is only what the ambiguity concerns.
- **R08 vs R09: same motion, different mappings.**
  - R09's value names "(R-60)", which identifies the motion, so it maps to R-60.
  - R08's value only names WP-7B-R1, so it maps to WP-7B-R1.
  - The difference is deliberate, because targets must come from the value itself.
- **R30 norm target → IMPLEMENTATION_PROGRESS.md:58.** This row locator is more specific than PB-006. R29 and R30 act targets name only PB-006, so they map to PB-006.
- **R23 → ES-004.3.** This identifier appears in the value but not in the R-41 span. I took it from the value as the task allows.
- **Matrix candidates (R15–R21) have no ID-style identifier in the value.**
  - I used the candidate names as written, e.g. "Reuse Before Create".
  - The span also gives their matrix row numbers: #2, #3, #9, #10, #5, #14 and #17. If the task's example "matrix item #N" is the expected form, use those numbers instead.
- **R22 → O-CLOSURE-VOCAB.** The framing ("three state planes") has no identifier of its own, so I used the item it frames.
- **General classes → UNMAPPABLE.** This covers values like "the register", "gaps and deferrals" and "AI findings". None has an identifier.
