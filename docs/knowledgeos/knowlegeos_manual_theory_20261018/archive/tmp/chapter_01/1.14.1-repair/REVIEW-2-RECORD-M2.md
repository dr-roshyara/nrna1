# Independent review 2: candidate M2 for §1.14.1. Record and dispositions (2026-10-18)

**Placement:** under the chapter-folder rule (scratchpad mirror of `chapter_01/1.14.1-repair/`). The M2 package itself stays in `s11411-repair/` until it is copied over (rule §"Effect"), so that review paths stay stable.

**Reviewer:**
- a fresh-context, read-only session of the same model family; not external;
- baseline hash checked (match);
- every finding derived by hand; the author's encoding used only as a cross-check;
- not read: reviewer-1's Q-text, the final review's C-section, the decision brief, the disposition note;
- v1 cases checked one by one: only 3, 4, 6, 12 and 15.

**The reviewer's report is reproduced in substance below, not edited to agree with M2.** Dispositions are the author's.

**Author's check:** the four material counterexamples were **reproduced** in the author's encoding (`s11411-repair/review2_checks.py`). Reproduction confirms behaviour; the findings stand on the reviewer's derivations.
- **CE1:** greedy suppression under ρ = now gives "current"; with the ending z removed it gives "revoked".
- **CE2:** late = reach gives "authority ended" at *t* = 3 and 6; late = never gives "current" at both; **v1 gives current, then authority ended**.
- **CE3:** δ = yes gives "not validly made"; with an unlimited quota, "current".
- **CE4:** at-act gives U; now and at-recording give T.

## Verdicts (reviewer)

| Item | Verdict |
|---|---|
| 1 findings resolved | DEFECTIVE |
| 2 changes of meaning | DEFECTIVE |
| 3 formal claims | SOUND WITH MINOR ISSUES |
| 4 counterexamples | DEFECTIVE |
| 5 decision mapping | DEFECTIVE (moderate) |
| 6 three gaps | ω and C6 minor; quota DEFECTIVE |
| 7 fidelity | DEFECTIVE (moderate) |
| 8 issue types | SOUND WITH MINOR ISSUES |

**Reviewer's conclusion: M2 is NOT ready as a sound candidate.** Material defects: G-1, G-2 (with G-3 and G-5), G-4, G-5.

## Findings and dispositions

| G | Severity / type | Finding (reviewer) | Author's check | Disposition |
|---|---|---|---|---|
| **G-1** | major · math + meaning | greedy υ under ρ = now (or consume_view = now) suppresses entries that end up authorized (CE1). Record order ≠ dependency order; an unverifiable entry blocks an authorized cancellation, which is what safeguard 6 forbids | reproduced | **ACCEPTED.** T2's "policy property" framing was wrong: it is a defect. Correction options: (a) a suppression fixed point with a proof; (b) restrict "does-not-count" to the stratified views, as a stated constraint on RD 6 |
| **G-2** | major · meaning | v1's "from its own act time, never retroactively" cannot be expressed; RD 2's "from then on" value is lost (CE2) | reproduced | **ACCEPTED.** The claim "every difference has a v1-reproducing option" was **false**. Correction: a `late` value applying the ending from *t_y*; add CE2 to table C |
| **G-3** | moderate · meaning | consumption never reaches claims; "consumed ∈ P" has no effect; v1 case 4's second branch changes; NEW-3 was wrongly marked resolved; live CONSUMED records would be U | by reasoning (c4 has no consumption term) | **ACCEPTED.** NEW-3 is reopened as **unresolved** |
| **G-4** | moderate–major · math + hidden policy | the issuing act debits quota, contradicting the brief's "issuance does not consume" (CE3) | reproduced | **ACCEPTED.** The formula and the brief disagree. A choice (C13) was hidden in the formula |
| **G-5** | major · meaning + disclosure | at-act with λ: a late-filed grant leaves acts undeterminable for **every** *k* (CE4). Comparison D omitted this; v1 would give F there; the λ = U default is an unlisted change | reproduced | **ACCEPTED.** The **RD 2 recommendation (at-act) is withdrawn** until D is corrected. "Independent of filing delay" was true for the act's own filing only |
| G-6 | minor · math | the question-instant derivation from case 12 needs an unstated premise; inclusive follows instead from v1's formula ("t_x ≤ t", "effective time ≤ t") | by reasoning | **ACCEPTED.** Re-derive; C16 stays "confirm only" |
| G-7 | moderate · math + meaning | the "progress" headline breaks its own rationale (CE6: never-valid adoption headlined "revoked"; CE7: an ex-nunc annulment is "void") and changes a v1 headline; it is a C8 policy default, not a correction | by reasoning | **ACCEPTED.** Comparison C #12 relabelled as policy (C8). The staging rule needs redefinition |
| G-8 | minor–moderate · math | the expiry point in c4 ignores β (CE5) | by reasoning | **ACCEPTED** |
| G-9 | minor–moderate · math | containment with a missing `to` is not in Kleene order (CE9) | by reasoning | **ACCEPTED** |
| **G-10** | moderate · fidelity + hidden policy | scope and activity narrowing tagged [SRC] beyond the sources (AMS L285 is a recognition trait; L92–94 is an observation of scope only). Two hidden policies: every non-root issuance counted as delegation; an issuer must hold the activity it delegates. IM L144–145 not cited | AMS L285 and L92–94 were read by the author earlier; **the reviewer's reading is correct**: "narrower boundary" describes how delegation is **recognized** | **ACCEPTED.** My [SRC] tags were an over-attribution. Scope and activity narrowing → [ASM] (with the IM L144–145 analogy), plus two new C-items |
| G-11 | moderate · policy + terminology | false or incomplete brief statements; double mappings (Q10, Q11); Q4(b)(c) absorbed; C13 alternative missing; a new date-only rule introduced; **"C3 and C9 are not in the baseline"** | — | **ACCEPTED except one sub-point, PARTLY DISPUTED:** C3 and C9 **are defined**, in `CH-01-final-review.md` L70 (C3: de jure vs recognized standing) and L76 (C9: ρ chooses the record state). The reviewer did not have that file. **The real defect is packet incompleteness** (as G-12), not a missing ID |
| G-12 | minor · terminology | "v1 L…" numbers are relative to an excerpt (offset 492); the C-layer view is "·"; referenced files are absent from the packet | by reasoning | **ACCEPTED** |
| G-13 | minor · policy | C6 override uses V(*z*), not C(*d*, *t*); conflict direction unspecified | by reasoning | **ACCEPTED** |
| G-14 | minor · math + terminology | T2′ agrees with stratified M2, not v1; T3(i) overclaims; T3(iii) mechanism misattributed; P4 nearly vacuous; v1 Property 3 not restated and fails under consume_view = now | by reasoning | **ACCEPTED** |
| G-15 | minor · fidelity + policy | κ_g = T under C1-a vs AMS L51; the promise applied to κ only; under C1-b, pre-adoption questions are U (unlisted) | by reasoning | **ACCEPTED** |
| G-16 | info | encoding defects: the λ branch skips w1/w2/closers/w4 (CE10); Ax2 not enforced, so the probe includes off-axiom records; helper defaults hide issuance; unknown issuer → F unstated | by reasoning (the λ early return is visible in `m2.py`) | **ACCEPTED.** The 700-run probe and the 49/49 tests **lose evidential weight** for T3 and T2′. *The fourth time the author's encoding has hidden defects* |

## What survives (the reviewer's verdict, item 3)

- **T1:** existence and uniqueness of the least fixed point.
- **T2:** termination and determinism.
- **T2′ and T3,** under their stated premises, with G-14's corrections.
- **T4, P2a and P4** (P4 is nearly vacuous).
- **P2b** correctly declared false.
- **Annulment chains.**
- **No non-monotone term; the key set is finite.**

**Status of findings after review 2:**
- *Resolved per the reviewer:* F-1, F-2, F-4, F-7, F-8, F-12, F-14, F-16, NEW-1, NEW-2.
- *Partly resolved:* V1-a, F-3, F-5, F-6, F-9, F-21, F-13, V1-c, V1-f(6).
- *Unresolved:* NEW-3.
- *Declared outside M2:* F-19.

## Consequences

1. **M2 is not a sound candidate.** Per the Principal's instruction ("If a material defect remains, M2 is not ready for Principal review as a sound candidate"), it is **not** presented as one.
2. **The decision brief stays NOT FOR DECISION.** It contains false statements (G-11) and a withdrawn recommendation (G-5).
3. **What a further revision (M3) would need, if authorized:**
   - G-1 (the suppression rule);
   - G-2 (a third `late` value);
   - G-3 (consumption as an ending kind);
   - G-4 (define "use");
   - G-5 (λ disclosure; table D);
   - G-6 to G-9;
   - G-10 (re-tagging; new C-items);
   - G-11 and G-12 (brief and traceability);
   - G-16 (encoding).

   **And a check of every v1 case**, which neither the author nor the reviewer has done exhaustively (the reviewer checked 3, 4, 6, 12 and 15).
4. **The pattern across three rounds.** Each review found defects that the author's tests hid. The evidential weight of author-written encodings is now **low**, and the method should say so.
