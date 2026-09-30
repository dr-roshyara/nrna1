# 1aq: reconciliation. Is each START's pre-act authorization independently established?

| | |
|---|---|
| Frozen | `PREREG.md` (`544d69232`; pre-run bundle defect corrected `78619e9de`) |
| Result | A: INDEPENDENT 0 · SAME-STATEMENT 1 · ACT-NOT-ATTESTED 9 · UNCLEAR 1. Reviewer: **INDEPENDENT 0 · ACT-NOT-ATTESTED 10 · UNCLEAR 1**. They agree that a non-circular contrast **does not exist** |
| Disagreements | D1 (source interpretation): R-72's "does not begin" is a ruling consequence, not an attempted act. D2 (coding): the counts. D6 (coding): R-81's §12 membership comes from later rows. D7 (formal reasoning): a performed unauthorized START would be an **illegal act**, not a counterexample to the definition. D8 (independence): the nearest pairs rest on single episodes. The rest are wording or evidence scope |
| Bias check | the sealed expectation (3–5 INDEPENDENT) **failed badly**: the result is 0 |

## Reconciled finding (SOURCE FACT + FORMAL CONSEQUENCE)

**10 of 11 coded START legality events are not acts.** They are **ruling statements of permission or prohibition**:
- "7B and 7C are NOT authorized";
- "SO IMPLEMENTATION DOES NOT BEGIN";
- "NOT PERMITTED: any implementation work".

Only the git commit 6a67da5d7 records an actual START, and its pre-act state is UNCLEAR.

## Model impact (major; the empirical core is affected)

1. **START is essentially unobserved as an act.** Its "legality data" are **deontic statements**: what rulings permit or forbid. They are not observed transitions. The F-LOG-0158 D-7 reverse-circularity risk is confirmed at scale.
2. **Downgrade.** "Target-indexed state: EMPIRICALLY SUPPORTED" rested on the strict witnesses {R-58, R-65} and {R-81, R-86}. **Both are ACT-NOT-ATTESTED.** Reconciled status: **supported as a property of permission statements** (what the rulings say is permitted, per target). It is **not** demonstrated as a guard on observed acts.
3. **Methodological (INFERENCE):** the strict schema coded permission statements as REFUSED acts. That conflates **norm statements** with **act observations**. Every legality event in the dataset needs an **act-attestation audit** before any variable's "empirical support" can stand.
4. **The theory target itself (HYPOTHESIS):** the corpus is predominantly a **deontic record**: rulings that create, adopt and revise permissions. Its behavioural record is thin (git). The theory being derived may be a **theory of norm-making acts** (ADOPT, AUTHORIZE, SUPERSEDE, ANNOTATE: rulings are themselves acts, and they are attested) plus the **permissions those acts produce**. It is not a theory of the governed actions.

## Next (highest information)
**1ar: an act-attestation audit of all 36 strict legality events.**
- For each, is it an **attested act** (a norm-making act by a ruling, or a performed action with its own record), or a **permission statement**?
- Then recount every variable's strict witnesses using attested acts only.

This is decisive for which "SUPPORTED" dimensions survive.
