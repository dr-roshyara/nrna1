# Cross-check pre-registration, addendum 01 (fixed before any return exists)

**Written:** 2026-10-07. At the time of writing, `~/F-XCHECK-RETURN-01/` does not exist, and the bundle has not been handed over.
**Trigger:** the human's control prompt "R6 Independence Cross-Check → Validation Decision Gate":
- §6: no expected outcomes in the handover;
- §7: five result classes, a sealing record and eight reviewer questions.

**What this addendum does:**
- it adds the rules below;
- it **narrows** what an agreement may be claimed to show.

**What it leaves unchanged:**
- the bundle (`26ad0404…ce4a8`);
- the comparison units of `PREREG.md`;
- the classification (a)–(d);
- the rule that (b) or (d) goes to the human gate;
- the "Not claimed" section.

Where this addendum and `PREREG.md` differ, the stricter rule applies.

## 1. A finding about the bundle: exposure of the R5 claims
- **V1b is exposure-free.** V1B-SPEC and V1B-SUPP-SPEC define the readings, the check forms, the output fields and the planted named histories. They contain no truth values and no verdicts.
- **R5-SPEC §7.2 is not exposure-free.** It states every R5 check K1–K12.2 as a claim to be tested, not as an open question. The checker is asked for a truth value and a least witness or counterexample. In five places the spec also supplies outcome detail:
  - K8.2: the parenthetical says why one forced act does not suffice;
  - K8.4: "(iii) fails at I7, and the failure comes only from …";
  - K8.7: it names h′ explicitly, and says "(iii) fails at I7 … in U, (iii) holds at I7";
  - K9.4: it lists the witnesses;
  - K10.5: it gives an example of a pair that is not ambiguous.
- The Claude-side R5 verifier received the same R5-SPEC. Both sides therefore had the same information. Exposure is still a confirmation-bias risk for the non-Claude side.
- **Why the bundle is not changed:** rewording 33 checks after the Claude run would put the two sides on different specifications. It would also need a reviewed proof that the semantics are preserved. Tiering the units (§2) removes the risk at the level of what is claimed, without that cost.

## 2. Exposure tiers of the comparison units
| Tier | Units | What agreement licenses |
|---|---|---|
| **BLIND** | `n_frame_histories`; every least witness or first counterexample **except** those listed under EXPOSED; K8.1's difference set; the K12.1 and K12.2 sets; **all V1b units** (12 readings × 2 frames × 5 forms: `holds` and least counterexample; every named-history field; the supplementary fields) | "independently reproduced across model families" for that finite unit |
| **EXPOSED** | the 33 R5 truth values; the K8.2 value; the K8.7 h′ and its I7 verdicts; the K9.4 witnesses; the K10.5 example pair | "consistent across model families, with the claim visible to the checker". Not counted as independent reproduction |

A DISAGREEMENT on an EXPOSED unit is **not** discounted. Disagreeing against a visible claim is the stronger signal.

## 3. Result classes, one per unit (replacing AGREE / DISAGREE / NOT_REPORTED)
- **AGREEMENT:** the value is reported and equal to the sealed Claude value, and NOTES records no deviating reading for it.
- **DISAGREEMENT:** the value is reported and differs. It is then classified (a)–(d) per `PREREG.md`.
- **UNTESTABLE:** NOTES says the checker could not compute the unit, for example because of a timeout or a construct it declared out of reach.
- **INCOMPLETE:** the unit is missing from the output without such a statement (`PREREG.md`'s NOT_REPORTED), or the output file is truncated or malformed. A malformed return is never repaired.
- **AMBIGUOUS:** NOTES records a reading choice for this unit that differs from the spec's literal text, or the reviewer finds the spec admits more than one reading for it. This applies whether the values agree or not. A DISAGREEMENT explained by a disclosed reading is AMBIGUOUS, which is class (c) in `PREREG.md`.

Only AGREEMENT on BLIND units counts toward "independent reproduction". UNTESTABLE, INCOMPLETE and AMBIGUOUS are listed and are never counted as agreement.

## 4. Seal record (Step A), done before any file is read
For each file: path, size and sha256. Also:
- the bundle sha the checker reports, if any;
- `PROVENANCE.txt`, copied verbatim;
- the verifier's identity and version;
- the execution environment and runtime, as stated in NOTES;
- completeness: whether the 3 required files and the program are present, and whether the JSON parses. Whether the JSON parses is a property of the file and is recorded without reading its content.

The seal file is committed before the comparison runs.

## 5. Reviewer questions (Step C), answered for every DISAGREEMENT and every AMBIGUOUS unit
The reviewer has both programs.
1. Is the disagreement real, i.e. reproducible from both programs?
2. Is it caused by implementation?
3. Is it caused by interpretation?
4. Is it caused by an underspecified definition?
5. Does it affect a theorem (an R4 statement)?
6. Does it affect only a finite check?
7. Does it affect an empirical conclusion (R1/R3)?
8. Does it require reopening R4?

A disagreement is never turned directly into falsification, and an agreement is never turned directly into proof.

## 6. Stop rule
- **Any (b) or (d), or a substantive AMBIGUOUS:** stop and return to the human with:
  - the exact disagreement;
  - a minimal reproducing history;
  - the affected statement and check;
  - the possible causes;
  - alternatives for resolving it.
- **Nothing is repaired automatically.**
- **Otherwise:** report the result and stop. V2, E-A5, V5, V6 and R7 are not started.
