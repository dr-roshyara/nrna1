# Hypothesis discrimination 01: why the promotion order is violated (after R-100 closure)

| | |
|---|---|
| Status | research instrument output. **Not a test, not a pre-registration, not canonical.** Authority: none |
| Instrument | `model_discrimination.py` (`50450c9f…`) → `MODEL-DISCRIMINATION.json` (`2d8d61ac…`); sensitivity `MODEL-DISCRIMINATION-SENS-P1-WORK.txt` (`fe902a54…`) |
| Labels | SOURCE-FACT · MODEL-ASSUMPTION · MODEL-DERIVED · HYPOTHESIS · EMPIRICAL-RESULT. **Nothing below is an EMPIRICAL-RESULT** apart from what F-LOG records already state |
| Log | F-LOG-0113 |

## 1. What the evidence fixes (SOURCE-FACT, from earlier F-LOG records)
- P1 (R-41 / ES-004.3): evidence was prior (1 instance) → Promote (PA) → Validation afterwards. Target ES-00x (READING, high).
- ES-006 is PROPOSED in all 7 versions, so for any case judged by ES-006.1 **text**, the force is AMBIGUOUS.
- R-100: two records plus the L0-REL-23b section contain no necessity evidence, context count or exception → C3 **UNDETERMINABLE**, and the case is **CLOSED**.
- P2: UNDETERMINED (development).

## 2. Outcome alphabet (MODEL-ASSUMPTION; one Promote act)
- `CONF`: evidence, plus qualification or validation if the target is high, all before P.
- `(dEv | dVal, BARE | OBL | EXC)`: a deviation.
  - `dEv`: no prior necessity evidence.
  - `dVal`: evidence was prior, but validation happened only after P.
  - The marker records whether the source *positively* records neither (BARE), a later validation or obligation (OBL), or an exception (EXC).
- `UNDET`: the source is silent. NOT-RECORDED ≠ FALSE, so UNDET is permitted under every hypothesis and carries no information.
- Case attributes: force of the decisive requirement (IN / AMB) · kind (knowledge / work) · high (target ≥ Engineering Standard).

## 3. Hypotheses (HYPOTHESIS; formal predicate = the permitted outcome set)

| H | Claim | Permitted outcomes | Falsifier | Distinguishing case |
|---|---|---|---|---|
| H1 | the evidence-first norm governs practice | {CONF} | any recorded deviation | any deviation. **P1 already falsifies H1** |
| H2 | authorization before validation is normal; evidence still precedes P | {CONF} ∪ (high ? dVal·* : ∅) | a `dEv` of any kind, or a `dVal` on a low-rung target | a low-rung promotion with post-hoc validation |
| H3 | the transition system depends on kind: knowledge follows H1; governed work follows authorize → execute → validate | knowledge: {CONF}; work: {CONF} ∪ dVal·* | a knowledge-kind deviation, or a work `dEv` | a governed-work adoption (freeze, plan) with later validation. **P1 falsifies H3 if P1 is knowledge (READING)** |
| H4 | apparent violations are differences of force or applicability | IN: {CONF}; AMB: everything | a deviation under an IN-force requirement | an act under an **eligible interpretation** (I-R39, from 2026-07-26 on) or an adopted, in-force rule |
| H5 | provisional adoption → validation obligation → later validation | {CONF} ∪ (*·OBL) | a deviation that is BARE or EXC-only | a deviation with the later validation positively absent |

The existing r3.1 claim C3 (deviation ⇒ exception) is the EXC-counterpart of H5. It is kept as a claim and not re-listed here.

## 4. Observational equivalence (MODEL-DERIVED, `separability`)
- **Low-rung knowledge case under IN force:** H1 ≡ H2 ≡ H3 ≡ H4. All of them permit only CONF, and only H5 is separable, via *·OBL. An IN-force case can therefore falsify H1–H4 **jointly** (by showing any deviation), or H5 (by showing a BARE/EXC deviation). It cannot separate H1–H4 among themselves.
- **Low-rung knowledge case under AMB force:** H1 ≡ H2 ≡ H3. H4 is separated from them by every deviation, and H5 by any OBL deviation. H4 and H5 are separated by BARE/EXC deviations.
- **Structural consequence:** for a case judged by ES-006.1 text, **H4 is unfalsifiable**, because that text is always AMB. H4 can be refuted only through an IN-force requirement: an eligible, prior, applicable interpretation, or a rule other than ES-006.1.
- **H2 vs H3** are separable only on work-kind or high-rung cases.

## 5. Development update (MODEL-DERIVED; P1 only; uniform prior; u = 0.5)
- Base reading (P1 = knowledge): H1 0 · H2 .344 · H3 0 · H4 .197 · H5 .459.
- Sensitivity (P1 = work): H1 0 · H2 .256 · H3 .256 · H4 .146 · H5 .341.
- These are **not** posteriors on truth. They are ranking weights for choosing the next read; P1 is a development case.

## 6. Next case by expected information gain (MODEL-DERIVED; heading-level attributes = MODEL-ASSUMPTION)
- EIG scales exactly with (1 − u), so the ranking is **invariant in u** (0.25, 0.5 and 0.75 were tested).

| Candidate (unread) | EIG (bits), base | EIG, P1=work |
|---|---|---|
| **2026-08-04 L216 "Discovery Freeze v1.0 adopted"** (work, AMB) | .340 | **.372** |
| 2026-08-04 L82 "Collector birth convention adopted" (knowledge, AMB) | .340 | .350 |
| R-88 ADOPTED (L328) / 08-17 L170 fork ruling (force unknown) | .252 | .263 / .253 |
| L811 qualification split · Plan Concept Paper · R-36 | .239 | .314 / .253 / .314 |
| 08-15 L493 vocabulary ruling · L205 meta-principle freeze (IN via I-R39) | .234 | .230 / .262 |
| 08-23 non-action "from a single occurrence" (a Reject) | 0 | 0 |

- **Maximin choice: L216 "Discovery Freeze v1.0 adopted".** It ties for the top in the base reading and leads under the sensitivity reading. Its v2 counterpart at L525 is a *separate* heading and is not included.
- **Caveat:** the IN-force cases (L493, L205) score lower on EIG. They are nevertheless the only kind that can refute H4. They should be read after L216.

## 7. Minimal transition model (MODEL-ASSUMPTION unless marked)
- **States:** Proposed → Evidenced → Qualified → Promoted → {Validated | Obligated}.
- **Events:** Ev, Qual, P(a ∈ 𝒜), Val, Obl, Exc.
- **Invariants carried over as candidate, not canonical** (SOURCE-supported, F-LOG-0093…0112):
  - C1 decision mediation;
  - C2 non-collapse (frozen ≠ adopted, ready ≠ promoted, recommendation ≠ decision ≠ authorization ≠ execution);
  - C4 authority as a parameter;
  - Force(ρ, t) ≠ Status(D, t);
  - completion ≠ mandate;
  - authority is not inherited between stages.
- **Open:** the ordering constraint between P and Val. That constraint is exactly what H1–H5 dispute.

## 8. What would change this note
- A released case whose source **positively** records the absence of prior evidence or validation. Without that, deviations stay NOT-RECORDED and every hypothesis survives.
- A reading that fixes P1's kind.
