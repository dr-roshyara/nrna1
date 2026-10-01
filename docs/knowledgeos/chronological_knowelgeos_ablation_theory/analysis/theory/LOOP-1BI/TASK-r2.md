# Task (frozen): a targeted repair of SCHEMA-r3 (r2): neutrality and determinacy residues

Use ONLY the files here:
- r1: `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`, `CHANGELOG-r1.md`;
- its review: `REVIEW-r1.md` / `.json`, PASS_WITH_LIMITATIONS;
- `SCHEMA-r2.md`, `GAPS.md`, `RECORDS.json`, `SOURCES.md`, `HYPOTHESES.md`.

No commands, no web. Repair **only** the following:

1. **N-a (neutrality):** replace rank 3's "R-A3 (a)-(c) hold" with "a SELF-PERFORMANCE (D3)". `state_if_extended` must then be defined **for positive and negative gated acts alike**. Record `state_if_extended` for every gated candidate, including those that lose at step 4.
2. **N-d (neutrality):** mechanism refusals and failures ("refused: unknown transition type …") must not be dropped asymmetrically. Either code them as ACT_ATTEMPTED with attempt_kind FAILED where a stated attempt exists, or tag them so they are computable. Disclose which.
3. **Determinacy, R18 / R20:** close the R-A5 step 1 ground-marker list, and state whether REPORTED acts mentioned only as provenance or location ("documented in …", "already ARB-adopted in …") are candidates.
4. **The rank-1 refusal cell:** "proposal or attempt" per R-A2.
5. **D7 vs D12:** settle "do not …".
6. **Primary-clause rule:** give one, or state that class labels like "operational acceptance" are status predicates (R06/R07).
7. **THEORY-CHOICES completeness:**
   - fix the TC-2 2c recipe;
   - give TC-2 2b a join tag;
   - make the `performative_token` definition consistent (TC-4 4c);
   - add any choice that the review marks as missing.

Do **not** choose any rule to reproduce an existing coding. Change nothing else. Mark changes `[r2-fix-<id>]`.

Write **SCHEMA-r3.md**, **THEORY-CHOICES.md**, **RATIONALE.md** and **CHANGELOG-r2.md**, plus **KNOWN-AMBIGUITIES.md**: every record or rule that you believe still admits more than one answer after this repair. This list will be frozen before coding as a prediction.
