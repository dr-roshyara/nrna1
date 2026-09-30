# Promotion decisions and how their evidence is counted

Full coding is in `ANSWER.json`. The sources are SOURCES.md Q1 (session log 2026-08-01, lines 440-487) and Q2 (lines 662-685).

## Events (7)

| ID | Item → target | Act | Evidence | Outcome | Ground |
|---|---|---|---|---|---|
| E1 | Layer module → canon | ASSESSMENT | "many instances", "one context" | NOT-RAISED ("Outcome B, not yet canon") | RULE |
| E2 | Layer module → ES-006.1 Standard | ASSESSMENT | UNK | NOT-RAISED (sits at "Research") | RULE |
| E3 | Candidates 2–5 → engineering/canon | ASSESSMENT | 1 context (EPIC-004) | NOT-RAISED | RULE |
| E4 (cited) | DDD principles → `engineering/` via R-39 | REPORT-OF-PRIOR-DECISION | "one context" | RAISED | UNK |
| E5 | Five positions → Chair's position | DECISION | UNK | RAISED | UNK |
| E6 | Same five → rulings register | DECISION | UNK | NOT-RAISED (R-34) | RULE |
| E7 | Staging root, file move, "undefined cell" conclusion | DECISION | "n = 1" artifact | HELD | UNK |

## Statements

- **SOURCE FACT:** The Layer module was assessed "Outcome B, not yet canon", with repeated evidence "⚠️ partial (many instances, **one context**)" and "stability ❌ — four post-freeze amendments in a single day".
- **SOURCE FACT:** "What is missing is a second context, and no amount of refinement inside EPIC-004 can supply it."
- **INFERENCE:** The promotion threshold counts contexts. The source's wording is "second context" and "single-context programme", but it never quotes the rule's own text.
- **SOURCE FACT:** "Candidates 2–5 all fail on exactly that, not on merit". "That" refers to the single-context restriction.
- **SOURCE FACT:** R-39 permitted "early promotion ... as an explicit, recorded exception with a stated validation expectation". The DDD module is "ADOPTED ... via the R-39 exception", and its evidence base is "one context."
- **SOURCE FACT:** "`R-nn` identifiers belong to the Decision Authority."
- **INFERENCE:** R-39 was issued by the DA. No passage names who issued R-39.
- **SOURCE FACT:** The Chair's five positions were "Recorded as disposition, not minted as rulings" under R-34. Three items were "Held".
- **FORMAL CONSEQUENCE (U):** No event meets all four U conditions. E1 comes closest but fails on two:
  - its instance count is "many", not a number;
  - the rule's context-counting wording is inferred rather than quoted.

  E1 is also weak evidence for U: stability ❌ blocks promotion on its own grounds, so the NOT-RAISED outcome can't be attributed to the context unit alone. E3 fails because its instance count is UNK. **U_witnesses: none.**
- **FORMAL CONSEQUENCE (X):** E4 is the only RAISED event where the evidence is plausibly below threshold. Its exception is YES, so it is **X-consistent**. Two parts of that call rest on inference: that E4 is below threshold (it is called "early promotion" of "not-fully-qualified material"), and that the actor is the DA.
- **FORMAL CONSEQUENCE:** No X-counter event exists in the sources. E5 is RAISED but has no stated threshold or evidence, so it is not an X event.
- **UNKNOWN:**
  - who received the file move ("Referred");
  - how the ARB answered the "standing exception mechanism" question;
  - the exact wording of R-39's threshold;
  - whether R-39 is RULE- or CHOICE-grounded.

## Ambiguities

1. **"Many instances"** is not a count. Formally, "many" means ≥2, but the task asks for counts "as stated". That choice decides whether E1 could be a U witness.
2. **The threshold unit** is never quoted as rule text, only paraphrased in the author's framing: "second context" and "strict reading".
3. **The Layer file is physically in the canonical tree but has not been adopted.** Its outcome is coded by status ("PROPOSED — NOT ADOPTED"), not by where the file sits.
4. **E1 and E2** assess the same artifact against two frameworks: the promotion criteria and the ES-006.1 ladder. They could be merged into one event.
5. **E5, E6 and E7 are Chair dispositions.** Whether a "Chair's position" counts as a higher-standing home is debatable, and the source denies that it is a ruling.
6. **Who acts in R-39:** the DA link rests only on the general R-34 statement in Q2.
7. **Exclusions:** I left out several items as not standing-raising, including ES-006.1's canonical status, the ARB exception-mechanism question (PENDING, and a question about a rule rather than an item) and the `methodology/` reservation question. Someone else could reasonably include them.
