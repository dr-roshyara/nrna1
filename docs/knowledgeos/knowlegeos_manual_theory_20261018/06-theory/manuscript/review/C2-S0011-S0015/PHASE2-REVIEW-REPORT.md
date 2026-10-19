# Review C2, Phase 2: S0011–S0015 records and book integration

*Saved from the independent reviewer's hand-back (a fresh, read-only Claude sub-agent, AR-11 standing approval; scope in `00-access/ACCESS-RECORD.md`). Its Phase 1 blind extraction is frozen as `PHASE1-BLIND-EXTRACTION.md` (sha256 `2fe7eb9f…3272`). The reviewer reports it opened only the permitted files and wrote nothing.*

**Verdict:** SOUND WITH MINOR ISSUES, conditional on fixing four foundational findings.
- 29 findings: 4 foundational, 25 ordinary.
- Capture: ≈ 268 of 355 blind items captured, ≈ 37 partial, ≈ 50 missed (≈ 80%); S0013 is lowest (≈ 61%).
- The reviewer's own Phase 1 line totals were one too high. The records' totals are correct.

## Chronology check

| Claim | Result |
|---|---|
| S0011's index date 06-25 is a quoted date; S0013's index date 07-11 is a quoted date | validated (the S0013 anchor is L42, not L43: F-22) |
| S0011 → S0010; S0010/S0011 → S0012; S0012 → S0015 | **not established** by these five files |
| S0015 → S0014 | supported (S0014 L9, L37, L45) |
| S0014 vs S0013 | evidence points to **S0014 → S0013** (S0013 L56, L83, L101) |
| S0012 → S0013 | supported (S0013 L52, L102) |
| S0012 itself | **multi-temporal**: L152 (the LG-1 withdrawal) was added later, pointing to the Progression Model |

## Foundational

| ID | Class | Finding |
|---|---|---|
| **F-01** | MISREADING | RC-33 ("proposed the strict rule [S0015], then reopened it [S0014]") is not in the sources. **Both documents carry both the rule and the two options:** S0015 IR-1 L112 (mint, **or** record why the register is not complete) together with L104; S0014 C-2 L53 ("commissions that authorize work must mint") together with D-2 L68. E-S0014 P11 already says so |
| **F-02** | OVERSTATEMENT | "authorizations exist only if minted" mapped to C1-a (only recorded acts count). S0015 itself treats the unminted commissions as **real authorizations** (L58: "The register is provably not the complete record of authorizations"); L104 frames the phrase as a forward **register-completeness rule**. The RC-33 heading states the C1-a reading as the corpus's position |
| **F-03** | MISREADING | RC-29 and T1-46 say recognition "misses authorizations that were given but **never recorded**". The sources say **unregistered**: "no ruling minted" (S0014 L53), "never minted as rulings" (S0015 L58). The commissions exist, and the README records "REALIZED" (S0014 L68) |
| **F-04** | OMISSION | RC-21 ("Derived or authoritative is decided by lifecycle", adopting S0011's "incompatible with derived") conflicts with RC-24/RC-25 (S0012 L152: derived = immutable provenance, authoritative = standing; they coexist). S0011's falsification rests on the one-field model that S0012 dissolves; the PKS artifacts carry "Authority: Generated" (S0011 L53), a provenance value |

## Ordinary (summary)

- **F-05:** RC-25 presents S0012 L67–80 as the mechanical cause without noting that S0012 L152 withdraws that question as ill-posed.
- **F-06:** RC-24's "primary source" needs a qualifier: S0012 L152 itself points to the Progression Model §4.
- **F-07:** RC-24's "× as a product of two value sets" is unlabelled; it is Int.
- **F-08:** RC-26 says nesting "was resolved on evidence", but S0012 L93 offers a permission plus an assertion; RC-26a calls it "a structural argument".
- **F-09:** T1-09 and T1-43 duplicate the nesting question with different statuses.
- **F-10, F-11:** both defensible. For F-11: S0014 B-2's evidence is S0015 row 11 re-classified (not independent), and S0013 probably postdates S0014.
- **F-12:** RC-30's "under one human" is not in S0014 L57.
- **F-13:** RC-35's "predicted outcome matched" is stated in source voice; S0015 L72 quotes no predicted outcome; "strongest" is the source's self-assessment.
- **F-14:** Ch. 0 "validated by two blind derivations" adopts S0014's self-description. The blindness ran one way; S0015 L34 says "Most of our 11 invariants".
- **F-15:** RC-20 generalizes from one act (S0011 U-1, n = 1) to "the recorded practice".
- **F-16:** RC-23 test 4 (subject vs time) is not a boundary test; the "found four tests" and "Int" labels conflict.
- **F-17:** RC-23 test 2 omits S0011's own caveat (U-2: untestable at n = 1) and the recommendation to DEFER D-3.
- **F-18:** the RC-21 quote: "no frontmatter there is validated at all" (not "nothing there is validated").
- **F-19:** the RC-26 Ch. 0 note drops S0012's hedge (L125: "Recorded as an observation, not a defect claim").
- **F-20 … F-29:** record-level omissions or anchors (S0011 tallies; S0013 inconsistencies; the E-S0013 anchor L42; S0013 follows S0014; chronology overstatement; the BASELINE-REVISIONS table rendering after the "Not adopted" paragraph; S0015 scoreboard double counting; S0014 "three doors" omits D-8; RC-34's two readings not exhaustive; E-S0013 drops "ARB-accepted").

## CV-1 second attempt on Int claims

| Claim | Result |
|---|---|
| RC-23 (four boundary tests) | **NOT VALIDATED as a set.** Tests 1–3 are individually faithful with caveats; test 4 is not a boundary test; "real boundary" is undefined |
| RC-25 (derivation vs attestation) | **VALIDATED in substance**, with the F-04 and F-05 caveats |
| RC-29 (consequence for recognition) | **PARTIALLY VALIDATED:** the derivation holds; the premise must be "unregistered" (corrected form: recognition *as the register stands* misses them; *as all records stand* may not) |
| RC-34 (two readings) | **NOT VALIDATED as exhaustive.** Further readings: (c) followed as non-binding convention; (d) force supplied by another ratified instrument. The source frames it as an over-claim |
