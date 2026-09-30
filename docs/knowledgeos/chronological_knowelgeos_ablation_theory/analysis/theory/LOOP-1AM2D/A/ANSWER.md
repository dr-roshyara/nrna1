# Answer: which variable separates raised from not-raised items?

**Bottom line (FORMAL CONSEQUENCE):** None of M_A, M_T, M_K or M_AT is falsified. The confound is **not broken**. The only raised event with known evidence is E01/R-41 (PA · EXTENSION · documentation standard). Every rule-grounded non-raise with known evidence differs from it on actor *and* raise_kind *and* (on a strict reading) target, all at once. The data therefore cannot say which variable matters. They do show that *some* parameter is needed: a model with no parameter is falsified, because E01 was raised at 1 while E02, E20 and R90-1 were refused on RULE at 1.

## 1. Events coded (new)

| id | actor | kind | target | evidence | exc. | outcome | ground |
|---|---|---|---|---|---|---|---|
| R38-1 | ARB | NEW | "new governance" / "not a sixth freeze" | UNK | NO | NOT-RAISED | UNK |
| R38-2 | ARB | UNK | "Precedent going forward" | UNK | UNK | RAISED | CHOICE ("ARB preference, Option A") |
| R40-A1 | DA | EXTENSION | "ES-005 (candidate ES-005.5)" | UNK | UNK | RAISED ("A1 APPROVED") | UNK |
| R40-A2 | DA | NEW | "a distinct descriptive knowledge type" | UNK | UNK | RAISED ("A2 APPROVED") | UNK |
| R40-A4 | DA | NEW | governed artifact ("descriptive until explicitly adopted") | UNK | UNK | NOT-RAISED ("do not adopt") | UNK |
| R41-1 (=E01) | PA | EXTENSION | "ES-004.3 (documentation standard…)" | 1 instance | UNK | RAISED | UNK |
| R90-1 | ARB CHIEF | NEW | "methodology"; "no standing rule for other work packages" | 1 work package | NO | NOT-RAISED | RULE ("ES-006.1 stands: one work package is not a standard.") |
| R90-2 | DA | NEW | the register ("constitutional decisions") | UNK | NO | NOT-RAISED ("WITHDRAWN BEFORE ADOPTION") | RULE ("The register holds constitutional decisions, not operational acceptance.") |

Full quotes are in ANSWER.json.

## 2. Labelled statements

**SOURCE FACT**
- R-41: "adopted as a permanent documentation standard (Principal Architect instruction)". The rule text is "hosted ONCE as ES-004.3 (documentation standard, not a new standard document)". Provenance is "the WP-1 closure inconsistency".
- R-90: "deliberately not promoted to methodology. ES-006.1 stands: one work package is not a standard." The row was later "WITHDRAWN BEFORE ADOPTION" because "The register holds constitutional decisions, not operational acceptance."
- R-40: "A1 APPROVED", with the "expected end-state is a clarification hosted in ES-005 (candidate ES-005.5), not a new standard". A4: "do not adopt; submit for review".
- R-38: "This ruling introduces NO new governance"; "Precedent going forward (ARB preference, Option A)".
- The quotes given for E02 ("single occurrence, and ES-006.1 forbids promoting from one") and E20 ("temporary carrier, not a pattern (PB-005 Q1)") **do not appear in SOURCES.md**.

**INFERENCE**
- R-41's "second instance" was caught by the rule's first execution, which came *after* adoption. So I set evidence at raise = 1 instance.
- R-41 records no exception and does not apply ES-006.1. So recorded_exception = UNK (not NO, not YES), and R-41 stays in the test.
- R40-A1 is coded EXTENSION on the strength of an "expected" end-state.

**FORMAL CONSEQUENCE**
- Qs (NOT-RAISED, RULE, exception not YES): E02, E20, R90-1, R90-2. Ps (RAISED): E01/R41-1, R40-A1, R40-A2, R38-2. Only E01 among the Ps has known evidence.
- **M_A:** E01's actor PA matches no Q actor. Same-actor pairs (DA: R40-A1/A2 vs R90-2; ARB: R38-2 vs E20) have UNK evidence. E01 vs E02 is undetermined (E02 actor UNK). → NOT FALSIFIED.
- **M_K:** there is no EXTENSION Q. The NEW Ps (R40-A2) have UNK evidence. → NOT FALSIFIED.
- **M_T:** strict target names never match. The key pairs E01 vs R90-1 and E01 vs E02 are undetermined because class equality (methodology vs standard) is not established. → NOT FALSIFIED.
- **M_AT:** implied by M_A. → NOT FALSIFIED.
- **Confound broken: none.**

**UNKNOWN**
- Evidence counts for every R-40 and R-38 event, and for R90-2.
- E02's actor.
- The ground of R38-1 and R40-A4.
- Whether ES-006.1 applies to ES-clause extensions (if it does, R-41 raising from 1 is unexplained).
- What counts as a "target class".

## 3. Differences R-41 vs E01
1. **recorded_exception:** E01 says "none recorded", which is not an allowed value. I code UNK. This is a difference in coding, not in outcome: it is not YES, so R-41 is still included.
2. **Evidence:** both give 1 instance. Mine is flagged as ambiguous, since it would be 2 if the post-adoption catch were counted.
3. **Quote:** E01's quote is stitched in reverse order from two non-adjacent passages. It is accurate in substance but not verbatim.
4. Actor, kind, target, outcome and ground match.

## 4. Ambiguities
1. **Target class:** R-90 equates non-promotion to *methodology* with "not a standard". Reading methodology ≡ standard would **falsify M_T** (E01 vs R90-1; E01 vs E02). A strict reading does not.
2. **"ARB CHIEF" vs "ARB":** treated as different, since actors are recorded "as named". This does not decide anything, because the ARB-actor P (R38-2) has UNK evidence.
3. **R-41's evidence:** 1 or 2 instances. Either way it is ≤ 1 only under the first reading. At 2, E01 vs E02/E20/R90-1 would no longer satisfy evidence(Q) ≥ evidence(P). The pooled model would survive, and no model changes verdict.
4. **R-90's withdrawal:** does the ARB CHIEF's non-promotion (R90-1) still count as a decided event once the whole row was withdrawn? I kept it, because it was a non-raise on a RULE ground.
5. **R-40 A1:** is it RAISED on approval, or only when ES-005.5 is actually hosted? And is its kind EXTENSION ("expected")?
6. **Scope of events:** is R40-A2 (knowledge-type recognition) a "higher-standing home"? Is R38-2 (precedent) raised to standing? Both are included, but neither decides anything.
7. **Grounds:** R38-1 could be RULE (parsimony) or CHOICE (Option B). It does not decide anything (evidence UNK).
8. **Given events:** the E02 and E20 quotes cannot be verified from SOURCES.md. They are used as given.
