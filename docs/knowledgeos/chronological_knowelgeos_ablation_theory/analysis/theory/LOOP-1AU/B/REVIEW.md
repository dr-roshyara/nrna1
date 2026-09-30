# Adversarial review of PACKAGE.md / PACKAGE.json (schema r2 decision package)

**Inputs used:** REVIEW-TASK.md, TASK.md, PACKAGE.md, PACKAGE.json, SCHEMA-v1.md, EVENTS.json, START-1AQ-RECONCILIATION.md, AUDIT-1AR-RECONCILIATION.md, AUDIT-1AR-WORKER.json, AUDIT-1AR-REVIEW.json. No other files, commands or web.

## Verdict: **PASS_WITH_LIMITATIONS**

The numbers hold up. I recounted all 36 events in EVENTS.json. The per-operation table, the class totals (19/13/1/3), both coders' class counts, the single class disagreement (R-89, D2), the tier arithmetic (21/11/1/3; ACT 10/8/1; NORM 10/3), the v1 and act-only verdict columns, and the SCHEMA-v1 quotes all match the sources. Nothing is invented.

The package still has real weaknesses:
1. It overstates how broadly the norm/act distinction is necessary.
2. It sees the dual-nature problem but postpones the only mechanism that would fix it.
3. Its tier rule is applied inconsistently.
4. It miscounts the NORM-dataset contrasts for s.
5. Its option consequences are loaded.

A human can decide from it once the limitations below are attached. None of them reverses the package's core claim: the **k** verdict cannot be fixed by any filter over v1 fields.

---

## 1. Source fidelity

| # | Location | Issue | Class |
|---|---|---|---|
| F1 | §2 ex. 2 and 3 | These are labelled SF and introduced as "The source says …", but they include worker A's *reasoning*: "No record that 7C actually started is provided" and "No attempt to assign R-90 again is recorded". These are A's inferences about what is absent from the *provided* excerpts. They are not source text. | wording / evidence scope |
| F2 | §3 tiers | The tier rule is applied inconsistently. The Chief's declined ADOPT goes into T2 on the grounds that it is "was 'agreed after close check'", but that is not one of the three T2 criteria the package defines. A gave no ambiguity flag for it, and no earlier audit disputed it. The same reviewer list (`agreed_after_close_check`) also covers REGISTER-rule and "All 10 register-row START events", yet those stay in T1 (R-72 excepted). One fix is to move the Chief record to T1. The other is to put REGISTER-rule and 9 STARTs into T2. Either way the 21/11 automatic/blind split in §5 changes. | coding |
| F3 | §7 NORM column, s | "2 contrasts" is taken from the *recorded* witness list, but the NORM column is labelled INF, i.e. recomputed. Recomputing from EVENTS.json gives **4** cross-cluster single-field s contrasts among NORM records: 7B R-47/R-58, 7B R-56/R-58, 7C R-58/R-65, §12 R-81/R-86. These are A's 5 pairs minus R-72/git. All four are circular (D9), so no verdict changes, but the column mixes two counting bases without saying so. | evidence scope |
| F4 | §1 | The quotation leaves out v1's **epistemic basis per semantic field** (`SOURCE` / `DERIVED` / `UNKNOWN`). That is the one existing v1 mechanism that bears directly on whether an outcome is attested (see §2 of this review). | source interpretation |
| F5 | §5 | "No v1 field is overwritten" contradicts "the v1 `outcome` value is … replaced by NOT_APPLICABLE". The value is preserved in `v1_outcome`, so lineage survives, but the sentence as written is false. | wording |
| F6 | JSON `B-guide-only` | "13/36 violated the existing rule" is wrong. The GENERIC_PRACTICE record also breaks "one historical act", so the figure is at least 14. | wording |
| F7 | §6 | The ambiguity rule gives R-60's "placed … under a heading" as a case for UNKNOWN. Both coders agree that record is NORM, so the rule prejudges its T2 blind re-coding. If applied, it would take R-60 out of the NORM counts (13 → 12), and §3/§7 do not reflect that. | coding |
| F8 | §3 non-class notes | D6 is missing: R-56's quoted sentence sits in a passage marked "Recording note, not part of this ruling". It does not change the class, but it is another case where the anchor is a statement *about* a ruling. | evidence scope (minor) |

Disputed classifications are **not** presented as certain. R-89 is marked T3. The R-36 item mapping is marked UNKNOWN. The ambiguous items A flagged are in T2. This part of the brief is done well.

## 2. Necessity: real, but narrower than claimed

§10 says "verdicts depend on it: k, s, h". Only **k** depends *solely* on the norm/act distinction:
- **s:** the v1 support is circular by construction (reviewer D9: "true by construction and carry no evidential weight even before the filter"). An anti-circularity rule removes it without r2. (formal reasoning)
- **h:** the only v1 pair is "ASSIGN a never-used number" (UNCLEAR, "NO SOURCE TEXT PROVIDED") vs ASSIGN-retired. It already fails on anchoring under v1's "Nothing is filled in silently". (formal reasoning)
- **k:** both v1 pairs are identical on every v1 field except `k` itself and `outcome`:
  - REGISTER R-90 vs REGISTER-rule: same cluster, r, a, genre and speech_act, with everything else UNK;
  - OPEN-WORK note vs OPEN-WORK ruling: same r, a, t, genre and speech_act.

  So no analysis-time filter over v1 fields can separate them without filtering on the variable under test. **That is the decisive necessity argument, and the package does not make it.**

The package's filter test is a strawman. It tries single fields and "drop START". A combined filter, `r = EXECUTION ∧ genre = register-row`, removes exactly the 10 START norm statements and keeps the git START. `r = UNK` removes both ASSIGN records. What is left over is only R-60-note, REGISTER-rule (both k) and R-83. This filter is itself post hoc, but it shows that "a filter works only with an extra per-record label" is asserted for 3 of 13 NORM records and not demonstrated. (formal reasoning)

The claim "No v1 field already encodes it" leaves out the epistemic basis of `outcome`. Coded honestly, "7C remains unauthorized" → REFUSED is **DERIVED**, not SOURCE. EVENTS.json carries no basis values, so it is **UNKNOWN** whether v1 would already have discriminated. The package should say UNK, not "no". (source interpretation)

v1 §1 already *forbids* recording non-acts. r2 therefore does two things: it enforces an existing rule, and it keeps the records that the rule would otherwise drop. It is not a new concept. The package says this in §4(b), but it does not follow the point through to the necessity verdict.

**Assessment:** recording the distinction is necessary, narrowly. The case rests on one variable (k) and two records: R-60-note, which A flagged "ambiguous", and REGISTER-rule. If the blind re-code turns R-60-note into ACT-REFUSED, k becomes WEAK, not 0. It still changes, but less than §7 implies.

## 3. The dual-nature problem: identified, then deferred

- **It is the typical case, not the edge case.**
  - Every one of the 13 NORM records is the content of some ruling or annotation act (R-47, R-56, R-58, R-60, R-65, R-72, R-79, R-81, R-83, R-86, R-90).
  - Several ACT records get their outcome from a *norm-type determination*:
    - the Chief's declined ADOPT is refused through R-81's provenance annotation ("DOES NOT HOLD");
    - R-89's NOT-IN-FORCE comes from R-86's *standing rule* (reviewer D2: "a standing rule, not an act on R-89");
    - R-91 is "HELD — NOT ELIGIBLE".
  - **Both surviving a-pairs**, the only SUPPORTED core, therefore sit on this boundary. The package never evaluates F4 ("both rows frequent enough") against the 36 records, although it could do so now.
- **One `observation_type` is enough for the act-only count, but only under a convention the package never states.** The type has to be defined *relative to the record's `o`*: "is an attempt at or performance of `o` attested?" Under that convention, R-58 is unambiguous: its START record is a NORM, and its own act would be an AUTHORIZE record. Without the convention, R-58 can be typed either way, and §8's coder-dependence risk comes true.
- **It is not enough for the NORM dataset.** For a norm record it is unclear whether `a`, `r` and `s` describe the *stating* act or the *governed* act. The package raises this only for `r`. `a` has the same problem: START norms carry a = ENGINEERING, but ARB/DA stated them. That is why the NORM column for a reads "0", and that 0 is an artefact of the choice.
- **The two-level repair is the `stated_by` link that §4(a) defers.** The package names the problem in §8 but proposes nothing for it.

**Minimal adequate two-level form:** keep `observation_type`, define it relative to `o`, and make `source_act` mandatory (not deferred). `source_act` names the anchored row plus the operation that row itself performs. For NORM records, a/r/s describe the governed act, and the stater is found through `source_act`. There is no double counting, because NORM records never enter R3.

## 4. Circularity and hindsight
- **Against the package.** r2 was designed after the verdicts were known. Migration then types **21 records automatically** from audit classes whose coders saw what those classes meant for the witnesses: A recomputed witnesses, and the reviewer read WITNESSES.md and ANSWER.*. Several verdict-bearing records are in that automatic group: REGISTER-rule (k), 9 START norms (s), and R-86 and R-70 (a). Only T2 gets blind re-coding. At least every verdict-bearing record, or a random T1 calibration sample, should be re-coded blind. (independence)
- **In the package's favour, and not mentioned by it.** The distinction comes from v1 §1, which was written before any outcome was known. The 1ar sealed expectation also *failed* for k (the audit expected WEAK and got 0), which is evidence against motivated coding. The proposal is not tailored to save a particular variable: a survives with caveats, and k, s and h fall. On its merits it would plausibly be adopted anyway. The weakness is the independence of the migration, not the design.

## 5. Failure modes and falsification
- The failure modes are concrete and grounded in real records (R-72, R-58, R-89). That is good.
- The falsification criteria are **not yet testable** as written:
  - F1 says "(almost) no", F3 says "below the preset threshold", F4 says "frequent enough" and F5 says "large share". None of these has a number.
  - F1 relies on a single blind coder, and the original v1 coder already conflated norms and acts while working under §1.
  - F2 asks whether a rule "reproduces the audit classes on all 36". That is the wrong target and too strict. The relevant test is whether a rule reproduces the *verdicts*, and on that test F2 is already settled for k (see §2).
  - F3 measures reliability, not validity.
- Two criteria are missing:
  - whether blind disagreement is concentrated on the verdict-bearing records;
  - whether F4 already fires on the 36 (see §3).

## 6. Minimality
A smaller change inside v1, with no new field:
1. Make the epistemic basis of `outcome` mandatory.
2. Amend R3 so that a record enters legality witnesses only if an **attempt at or performance of `o` is attested by an anchored source** (outcome basis SOURCE). Outcomes derived from a permission statement are DERIVED and excluded.
3. Add an anti-circularity clause: `outcome` must not be coded from the same text as the variable being witnessed.

This gives the act-only verdicts for k, s and h (s through clause 3) and deletes nothing. Its costs: norm records keep a misleading `outcome` value, and the NORM dataset stays untyped. The package's option (a) is the next-smallest step up. The alternative in §4(c) is rejected partly on a false premise: "cannot express … UNKNOWN". Under v1 §1, any field the source does not establish is already UNK. The objection that it overloads `outcome` is still valid. (formal reasoning)

## 7. Options

| Option | Loaded element | Class |
|---|---|---|
| A | "Future coding cannot repeat the conflation silently." A wrong type is just as silent as a wrong outcome. Only a *missing* type is caught, and §8's own coder-dependence failure mode contradicts the claim. | wording / formal reasoning |
| B | "Coders who use SCHEMA-v1 alone will reproduce the error" is stated as fact, but it is exactly the untested criterion F1. | formal reasoning |
| C | It treats "do not adopt r2" as if it meant "v1 verdicts stand". The audits exist either way. s can be downgraded for circularity (D9) without r2, and the k finding can be reported as a caveat. "Later datasets will not be comparable" is speculation. | substantive |

Three options are missing:
- **D.** Defer the decision and first run F1/F3 as a pilot, with blind re-coding of the verdict-bearing records and thresholds fixed in advance.
- **E.** The v1-internal rule change from §6 of this review.
- **F.** The two-level form, `observation_type` plus a mandatory `source_act`.

Without D and E, the menu is tilted towards A.

## 8. What should change before the package goes to the human
1. Restrict the necessity claim to k, and add the argument that the k pairs are identical on every v1 field except k and outcome. Attribute s to D9 and h to the missing anchor.
2. Replace "no v1 field encodes it" with "outcome basis: UNK in EVENTS.json".
3. Define `observation_type` relative to `o`, move `stated_by`/`source_act` out of the deferred list, and test F4 on the 36 records.
4. Apply the tier rule consistently (F2), and re-code blind every verdict-bearing T1 record or a T1 sample.
5. Correct F3, F5, F6 and F7, and relabel A's reasoning in §2 as INF.
6. De-load options A, B and C, and add options D and E.
