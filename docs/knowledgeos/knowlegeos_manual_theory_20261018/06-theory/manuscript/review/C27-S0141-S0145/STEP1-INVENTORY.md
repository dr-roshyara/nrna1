*Window reviewer W (Opus), step 1 blind inventory, verbatim (saved by the Professor at 2026-10-23T18:42:00Z). sha256 of the hand-back text at receipt: `9624a6cb03e19cf58f5dfe431c1839b80592a44d7215445cd52baa3082f3c8b0` (UTF-8, the text below this line exactly).*

C27 STEP 1: BLIND INVENTORY

**Disclosure.** My session came with the repo git status and recent commit subjects attached. One subject reads "window C27 draft (S0141-S0145; §8.20, CON-0250-0253)", so I know a draft exists. I did not open it. I read REVIEWER-INSTRUCTIONS.md and grepped CORPUS-ALLOWED.tsv. All five paths are listed there (S0141–S0144 at lines 1526–1529, S0145 at line 1376). I then read the five sources whole. I did not grep S0131–S0140 and did not open the migration plan (S0300). I opened no cited file, nothing under W/06-theory, W/archive or W/tmp, no read log or START-TIMES file, and no blocked or sealed file. I ran no git commands. All arithmetic below is mental recomputation from the source text. Independence here means process independence only.

---

## S0141 — AMD5 independent architecture review (ccf6c9c7, 2026-08-20; §18 added 08-21). **FULL**
Reason: new distinctions (denial versus window, terminating condition, quarantine location, declared-delta freeze). It also sets up the defect-shape counting used later.
1. A freeze is redefined from "nothing changed" to "nothing changed that was not declared": a three-way outcome (identical, delta ⊆ declared, delta ⊄ declared) that keeps detection while the migration writes its own records (L154–163). Residual r1: masking at declared-record granularity (L170). Residual r2: the declared list covers only the happy path (L171).
2. "The defect is the denial, not the window" (L87–89). An irreducible non-atomic interval (steps 3→5) is acceptable if it is recorded; the claim that it never happens is the defect. Strongest claim ≤ evidence.
3. Two acceptance criteria can be jointly unsatisfiable (12 with 15). With no stated terminating condition, the case has three readings: a silent promotion, a permanent block, or destruction of the bytes (L113–130).
4. A quarantine with no location is undone by the very act that removes the store (L132–136). The compliance argument holds at the moment of quarantine but not through Phase 7.
5. Authority is decided by **when** a record was written, not where it is stored (L333). A marker reports state and never confers authority (L334).
6. Defect shape: "act named in a specification and not carried where the specification is normative", counted as the "fourth occurrence" (L75). Enumeration-versus-content mismatch, counted as the "third occurrence" (L229). Neither count is enumerated.
7. Glyph collision: CASE B versus CASE β (L144, L299).
8. Closing a finding needs a requirement-by-requirement test. Verdict scale: CLOSED / ADDRESSED BUT NOT CLOSED, with four BLOCKED tests (L372–379).
9. Measured state: 18 records, 216 transitions, 110 grants; no -AMD3, -AMD4 or -AMD5 grant; the corpus has no git history (L37–39).
10. §18 (C-1 to C-5): rename the newer identifier family, never the cited one. Store-level rather than record-level retention. A third marker is needed. C-4: Phase 5's own bookkeeping is a gap-window write by construction (L421–426).

**Lineage.** The findings it re-tests are 870305e0's, "as its author stated them" (L5). The remedies are bc1b47ef's. Statuses: "OPEN-M3 CLOSED (Option A)" is backed by an -OPEN-M3-DECISION grant (L37). "RC-1…RC-11 CLOSED" is a **reviewer's** closure, not an authority act. No PO/ARB words are quoted.

**Defects.**
- (a) L30 and L389 say the review "produces no remedy text", but it does: the exact block "1 · 1b · 2 · 3 · 4 · 5" (L75), a verbatim restatement (L89), and in §18 the names PRE-SWITCH / POST-DEMOTION (L421). AMD6 adopted these word for word (S0142 L93, L183).
- (b) The "fourth"/"third occurrence" counts are not shown.
- (c) "CLOSED" is assigned by a review that "accepts nothing" (L30). Closure authority is undefined [I].

## S0142 — AMD6 independent architecture review (dd639043, 2026-08-21). **FULL**
Reason: the richest new theory in the window: fixed-point placement, mechanical write versus governance append, dead end versus governance state, and an unsafe gap on the normal path.
1. A store's final state cannot be recorded inside that store, because recording changes it. So the closing record must go on the far side of the writer switch: "the only [resolution] available without atomicity" (L232).
2. Six of seven placements are **forced** and one is **chosen**: "derived, not chosen" holds only partly (L206–212).
3. Two write classes: governance appends, which carry authority, and migration mechanical writes, which confer none but are evidenced by an append (L382, L387). This is the hidden premise of item 1 (DV-4, L234).
4. DV-1: the copy is written twice after its last verification. The final gate compares the source with a recorded enumeration and neither operand is the copy. The plan re-verifies on the exception path, not on the normal path that ends in removal (L262–282). "A specification is not a verifying act" (L280).
5. A terminating governance state is not an implementation dead end: "the aggregate refusing an invalid transition", one with an owner and an unbuilt path (L116, L389). The missing DISPOSE transition is measured (L144).
6. The safe-direction trade: misclassify as POST-DEMOTION, losing precision but never bytes (L246–256).
7. DV-6: a MOVE would make the source *spuriously satisfy* the gate. The stated reason for forbidding it is inverted (L325–329).
8. A boundary defined by content, not timestamps: only 2 of 216 transitions carry a time field (L242).
9. Separate commission from delivery: 4 commission grants are registered and no delivery grant exists (L44–45).
10. "Self-check ≠ assurance": the author's own trace table (C-12) missed P5·ALL (L344–356).
11. Corpus: 114 grants but **113 unique grantIds** (L420).

**Lineage.** It consumes S0141's residuals "as ccf6c9c7 stated them" (L7). The remedies are bc1b47ef's. It claims the self-review bound is discharged (L35). OPEN-M7's disposer is called "a reading, not a decision" (L146).

**Defects.**
- (a) The BLOCKED test "AMD6 introduces a technically unsafe property" is met by DV-1 and partly by AMD6's own slot 3(iii). It is argued away with an incentive argument (L511), so the verdict does not follow from its own test.
- (b) A duplicate grantId (114 grants vs 113 IDs) is reported as "verifies exactly" and never investigated.
- (c) The "discharged" bound ignores that parts of the remedy text came from ccf6c9c7 (S0141 defect a).
- (d) The fixed-point claim (item 1) overreaches [I]. A closing record can describe the state that excludes itself (state before the record plus the record), so self-reference is avoidable without atomicity.
- (e) "The only [unsafe gap] in this chain" (L479) is not shown.
- (f) It relabels the RD-7·a shape as "citing an undefined identifier" (L356). S0141 called it a missing act. Two shapes are merged.

## S0143 — Track-2 Phase-0 back-test report (2026-08-21; producer not stated). **FULL**
Reason: introduces falsification by rediscovery and the line "mechanical assurance proves declared structure; review discovers undeclared content".
1. Falsification design: re-run the checkers at historical commits. They must fire on the defective states and stay quiet on the repaired one (L20, L40).
2. Matrix: S1 to S5 × {0a2fa71d, 7d3abc59, 8307beca}, 15 cells (L34–38).
3. Fail-closed: INCONCLUSIVE ≠ PASS, and "absence of evidence is not PASS" (L48, L77).
4. A PASS is scoped. NOT-CHECKED classes must be stated, or the report itself becomes false assurance (L28, L64).
5. Boundary: the checker cannot discover an undeclared act. The missing-act class (RD-1, RD-7, RD-3·b) needs human review (L83).
6. Live findings on the current tree: one ragged table and one set of dangling step references (L68–75).

**Lineage.** No author process is named. "EP-01 AUTHORIZED (DA approval… 1fcdb353)" is a commit citation with no quoted DA words (L7). The D-4 text is called "the DA's formulation, verbatim" (L79–81). It is longer than the two-line DA formulation that S0145 L237–240 records. One of the two is a gloss.

**Defects.**
- (a) AMD3 (bb1708b7) was dropped. S0145 L152 required AMD3–AMD5 and the rediscovery of "17" findings. It also said that otherwise the proposal is "UNPROVEN and Phase 1 must not start" (L153). By that criterion 5 classes were rediscovered, not 17.
- (b) RC-1, RC-7, RC-7a, RC-7b, RD-9 and DI-1·r are silently absent. RC-7b is in S1's own class (S0145 L141). This contradicts "rediscovered every historical finding in their classes" (L26) and "no silent gap" (L64).
- (c) The S3 "rediscoveries" of DI-2 and DI-7 depend on a fixture vocabulary that presumably encodes the target terms. That is circular [I]. Live S3 is INCONCLUSIVE (L48).
- (d) The quiet row is a false negative. AMD6's S2 PASS ("every… reference resolves", L47) coexists with S0142's DV-7: P5·ALL, an undefined operator ID inside a trace table. That is exactly the check class S0145 L146 specified.
- (e) "No governed artifact modified" (L10) sits beside a catalogue status change to CAP-003 REALIZED (S0144 L32). §8 narrows the claim to "checker-written" changes.
- (f) A drifted-oracle test only shows that the harness compares cells, not that the checkers read content.

## S0144 — Phase-0 conformance verification (bc1b47ef, the specification's author, 2026-08-21). **FULL**
Reason: mutation as the test of a checker, the defining-versus-quoting distinction, and the first non-back-test evidence.
1. Mutation separates a checker from a stub. Three single-defect mutations flipped the verdicts and the controls stayed PASS (L36–43). A second-order effect was reproduced: a collision also produced reference ambiguity (L45).
2. V-3: S2 cannot tell **defining** an enumeration from **quoting** one. It FAILed 9 of 83 documents in reviews/, including all four AMD reviews and this report itself (L54).
3. A capability can be realized while its configuration is not adopted: S3 is INCONCLUSIVE 13 of 13 in live use (L52).
4. V-2: five live true positives that human reviews had passed over (L53).
5. Conformance by the specification's author ≠ independent verification (L5, L60).
6. Identifier collision: EKS-06 is taken but the mint is uncommitted, so it is invisible to collision checks (L55).

**Lineage.** It discloses the conflict itself. Its implementation commits (3e95e28d…6ed0bad7) do not match S0143's list (895d38cb…7f04e220). I did not check this.

**Defects.**
- (a) "0 false positives in this class" is not backed: only "two [are] confirmed independently" out of five (L53).
- (b) The mutations cover S1, S2 and S4 only. S3 was tried only on a back-test target, and S5 was not mutated. "Content-driven" is shown for 3 of 5 slices.
- (c) It calls S2 "safe" for architecture/ (L54). Yet AMD5-SUMMARY, which lives there and cites step numbers, is listed among the quoting false positives, while S0143 L75 counts it as a "real finding". It contradicts itself and S0143.

## S0145 — Cost-optimization governance-assurance review (bc1b47ef, the AMD6 author, 2026-08-21; allowed-list date column says 2026-08-01). **FULL**
Reason: an M/J classification of findings, cost counted in amendment cycles, a two-authority split, and the candidate invariant "automation may reduce cost, never manufacture authority".
1. M/J rule: a finding is mechanical if a deterministic checker could have produced it (L41). Claimed trend 29% → 46% → 58% (L45–50).
2. Cost is measured in amendment cycles, not reviewer minutes. "Mechanical defects survive amendments and then trigger the next one" (L52–66).
3. X-5: **verifying** a declared trace is deterministic. **Deriving** the act list is judgement. A checker removes missing-act defects "by NOTHING" (L121).
4. Batch the work but don't merge the semantics. Automation may report CONFLICT, never INDEPENDENCE=TRUE (L30–31).
5. Two authority classes: deterministic evidence versus routing, gates and classes. "Report first, route later" (L83–90, L119). Don't change the review model while a review is pending on it (L89).
6. A gate versus an author's voluntary entry condition (X-4, L120).
7. Phases 0–4, each with an entry condition and an authority (L132–175).
8. Canonical discovery: map the proposed components onto existing capabilities. §11.2 is a self-correction: "searched the scripts, missed the catalogue" (L245–271).

**Lineage.** The author reviews evidence about its own work (L5, L215). The DA's response is reported as having "accepted the two-authority-class split". Only the two-line formulation is quoted, and there is no record ID (L233–243).

**Defects.**
- (a) **An internal contradiction undermines the trend.** §3.1 counts RD-1, RD-7 (AMD4) and RD-3·b (AMD5) as M (L46–47). X-5 (L121) and §9 call the same three not mechanically detectable. RD-9 ("2c-commit doesn't name .gitattributes") is also judgement.
  - AMD5 double-counts aliases: DI-5 = RD-7·a, DI-4 = RD-3·c, DI-6 = DI-3·a/b (S0141 L296–298, L348).
  - Recomputed: AMD4 is 3–4 of 13 (23–31%). AMD5 is about 5 of 11–12 (≈42–45%). The monotonic trend does not survive.
- (b) "Zero lines of implementation" (L64) contradicts the built but unwired resolver (S0142 L404).
- (c) "That single scope gap is why… zero mechanical coverage" (L100) contradicts L109: knowledge-lint needs frontmatter, which Track 2 artifacts lack, so the scope gap is not the single cause.
- (d) Phase 0 needs "Authority: NONE" (L132), but in §11.4 it "proceeds on a DA plan approval" (L286). The status shifts within the source.
- (e) The freeze carve-out rests on a Deferred-Register gloss. Building a KnowledgeOS checker is arguably a "KnowledgeOS proposal" under that freeze [I].
- (f) The mappings strain the catalogue's rules [I]:
  - DI-1 → DP-1 needs "the register = the document", a semantic extension presented as "no new policy".
  - DI-2 (a retired token) → DP-3 (one meaning per context) is not a homonym case.
- (g) The counterfactual that AMD6 was caused by mechanical defects is not established. AMD6 also carried RD-3·a/b and RD-7·b (judgement) [I].
- (h) "4 independent reviews" (L64), although by its own account the AMD6 review is still outstanding. Which is the fourth?

---

## Cross-source points

| Point | Sources | Note |
|---|---|---|
| The missing-act versus dangling-reference boundary is not sharp | S0141 L75, S0145 L121, S0143 L83 | RD-7·a (an act missing from the normative block) was caught mechanically as DI-5, because the act was *cited* elsewhere. So "reduces missing-act by NOTHING" is too strong. Detectability depends on whether the missing act is referenced anywhere [I] |
| Exit criterion narrowed without a record | S0145 L152 → S0143 L20 | 17 findings at 3 commits became 5 classes at 2 defective commits plus 1 quiet commit, yet the result is called PASS |
| False negative in the quiet row | S0142 DV-7 vs S0143 L47 | Both on the same artifact (8307beca) |
| Self-reference of reviewers and authors | S0141 §18 → AMD6 → S0142; bc1b47ef authors AMD6, S0145 and S0144 | The independent and conformance lines share an origin. Remedy text crossed from a reviewer into the amendment |
| Status by non-authority | "CLOSED" (S0141/S0142), "REALIZED" (S0144 L32), "accepted split" (S0145 L233) | None is backed by a quoted authority act. The commission grants (S0142 L44) are the only status shown on the record |
| Grant counts | 110 (S0141) → 114, 113 unique (S0142) | The delta matches the 4 commission grants. The duplicate grantId is left unexplained |
| Recurring defect class | All five | A claim stronger than its own evidence: "at NO point", "FIRST record", "every row resolves", "0 false positives", "every historical finding", "29→46→58" |
| Phase-1 metric confounded | S0145 L160 vs S0145 §3.3, S0142 | Mechanical findings had already fallen at AMD6 through hand checks, before any tool adoption [I] |
| Production order ≠ index order | S0143 cites S0145 §7/§11; S0145 says AMD6 is unreviewed | Probable order: S0141 → S0145 §1–10 → (S0142 / S0143 / S0144); S0145 §11 later [I]. The allowed-list date for S0145 (08-01) conflicts with the document's date (08-21) |
| Still pending | — | DV-1 to DV-7, OPEN-M5/M6/M7, RD-2/4/5/6/9, V-1/V-3/V-4, S8/OQ-1 (enumeration-versus-content), Phases 1–4, EKS number, H-CAT-1 |
