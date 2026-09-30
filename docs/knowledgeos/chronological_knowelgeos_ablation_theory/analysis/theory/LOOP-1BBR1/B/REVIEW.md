# REVIEW of SPEC-r1 (repair of 1bb, plus rank-0 and scoping protocols)

**Inputs used (only these):** REVIEW-TASK.md, REVIEW-1bb.md, TASK.md, HUMAN-DECISIONS.md, SPEC-r1.md, SPEC-r1.json, CHANGELOG.md. No command was run and nothing was collected.

**Limits of this review.**
- SPEC-1bb, HYPOTHESES, IDENTIFIABILITY, CONFORMANCE-CASE and STATE are not among the permitted inputs. So the `[UNCHANGED]` marks, the CHANGELOG "before" column and the source quotes cannot be checked against their originals. They are taken as given.
- Statements about git behaviour come from knowledge of git, not from running it. Each one gives its confidence.

---

## Verdict: **FAIL**

| Component | Verdict | Execute? |
|---|---|---|
| Part A: corrected spec 1bb-r1 | **PASS_WITH_LIMITATIONS**. 9 of 14 defects fixed, 5 partial, none left unaddressed. But the D1 repair moves the confound instead of removing it (N1, N2). | Not applicable. It must be amended before any Stage 1 scoring. |
| Part B: rank-0 protocol | **FAIL** | **Must not be executed.** Run B, the reproducible run, aborts as frozen (R0-1). The completeness check C2 fails spuriously on ordinary hosted repositories (R0-2). |
| Part C: scoping protocol | **PASS_WITH_LIMITATIONS** | It may be run, because it is existence-only, bounded and harmless. Its overall result must be read as "not determinable by existence check", not as "no" (SC-1, SC-2). |

The overall verdict is FAIL because one of the two frozen protocols is not executable as written, and the frozen rules forbid ad-hoc fixes (B.8 W5). Protocol r2 is required for Part B.

The repair of the spec itself is careful and mostly real. The six distinctions are introduced, the NIF matrix is given per prediction, G-D1 exists, and D3, D4, D6, D7, D10, D11, D12 and D14 are properly fixed. But the headline D1 repair relocates the confound instead of removing it:
- **H-B is smuggled into H-D's falsifier.** Third-party *application* (NIF-B) is treated as force (N1).
- **H-D is still smuggled into H-B's falsifiers.** "No permitting act ⇒ not permitted" is used unguarded (N2).

---

## 1. Defects D1–D14

| D | Status | Assessment |
|---|---|---|
| **D1** | **PARTIAL** | Real structural work: the six distinctions, NIF tiers, G-D1, the per-prediction NIF matrix, and the DISJUNCTIVE grade. Not sufficient, because NIF-B is itself behaviour that both hypotheses explain (N1). G-D1 covers only H-D's *status* rule, not H-D's *act-exclusivity* rule (N2). The fallback is applied asymmetrically (N3). See §2. |
| **D2** | **PARTIAL** | The two-stage acquisition, the items (a)–(d), the effort restatement and O0 are all real. But the claim that the outcome table is exhaustive is false, and O5 is wrong (N7). O0 wrongly removes P4, which after D1 no longer depends on work (N8). |
| **D3** | FIXED | The "only" claim is deleted. The ranking criterion is explicit and labelled PROPOSAL. |
| **D4** | FIXED | Rank-0 has no lower bound. The P7 start is min(R-72 trace, earliest ET). P4 and P5 start at the earliest trace. Recording times are used only as bounds. (There is a residual evidence-scope gap for waivers recorded before the window start: N10.) |
| **D5** | **PARTIAL** | Sub-fixes (a), (c), (d), (e) and (f) are real. (b), late recording, is not operational: Stage 1 reads the DCS only *within* the window, so a late-recorded act is never seen. The rule also covers only acts that *state* an in-window effect (N9). The (c) classification of "R-89 already governs" is an interpretive choice frozen against H-D (N4). |
| **D6** | FIXED | P2 and P7-HB now test conformance only, and responses are moved to P8 with LATE_RESPONSE. Minor gap: the promised check whether R-91 or R-94 refer to R11 is not an item in Stage 1, and Stage 1 is gated on a WP-4C-1 trace (N11). |
| **D7** | FIXED | Separating observation from attestation resolves both contradictions. |
| **D8** | **PARTIAL** | All named fields are added. The P1 per-clause "satisfaction rule" is deferred to "before Stage 1", so it is not frozen (N12). The review JSON `missing_fields` is not an input, so the rest cannot be checked (this is disclosed). |
| **D9** | **PARTIAL** | The single P ∧ ¬B structure, the default scope and the fallback are all added. But the fallback "not earlier than recording time" is only a lower bound, and it conflicts with M-TIME. As a result, "in force before 16:10" can never be shown, and the verdict rule is not mutually exclusive (N5). The default turns an absence that is not shown ("no unresolved governance blockers") into NON-CONFORMS, which breaks §0.8 (N6). The "unauthorized unless shown" baseline carries H-D (N2). |
| **D10** | FIXED | The UNKNOWN label, readings 1a and 1b, the scoping to W, and dropping the tendency reading are all correct. |
| **D11** | FIXED | The artefact/fallback/grade table is complete for the listed items, and git dates are SOURCE_STATED. |
| **D12** | FIXED | P7-HD is NOT_APPLICABLE to R-89, and elsewhere is merged into P1. The motive clause is removed. |
| **D13** | FIXED (with caveat) | Item 1 is relabelled, item 0 (NIF-A) is added, and the eligibility condition is added. The caveat: FR-D13 still certifies "independently observed" through item (2), NIF-B, so N1 carries over. |
| **D14** | FIXED | The relabels and OPTIONAL marks are all present. The conflict is described as "possibly only apparent". |

### New defects introduced or left visible by the repair

| # | Class | Where | Defect | Consequence / fix |
|---|---|---|---|---|
| **N1** | formal reasoning; independence | §0.4 NIF-B; §0.3 N-FORCE row; O3; FR-D13(2) | **NIF-B is behavioural, so the D1 confound returns one actor further out.** A third party "applying R-89 as binding" while R-89 is not adopted is explained by H-D (the third party acted contrary to the unchanged norm, or erred) and by H-B (practice changed the norm). This is exactly the REVIEW-1bb D1 argument, applied to a non-executor. The spec treats it as N-FORCE[OPERATIONAL], and O3 scores **P4-HD FIRED**. This collapses distinction 4 (the third party's act according to a norm) into distinction 2 (the norm in force). Downgrading only when the application is "expressly voided" makes silence count as force. | Extend G-D1 from "executor behaviour" to "any behaviour". If a force premise rests only on NIF-B, grade it DISJUNCTIVE {H-D clause, third-party conformance}. Only NIF-A (or NIF-B together with NIF-A) can give FIRED. |
| **N2** | formal reasoning; substantive | §0.3 ACT-CONTRA ("absence of any permitting norm … requires a complete DCS"); graded fallback; FR-D9 default "unauthorized unless P shown"; O5 "baseline of no authorization" | **G-D1 is too narrow.** It catches premises that use H-D's *status* rule (force only via adoption). It does not catch premises that use H-D's *act-exclusivity* clause (P1: norms change only through acts). The inference "no permitting act in a complete DCS ⇒ no permitting norm in force" is valid only if H-D's P1 clause holds. Under H-B reading 1, a permission can arise from work without an act. So P2, C1 and O5 falsify H-B conformance on a premise that presupposes H-D. | Add a G-D1(c) rule: a non-permission premise derived from the absence of a permitting act grades DISJUNCTIVE {H-D P1 clause, H-B conformance}, unless NIF-A shows the state. |
| **N3** | formal reasoning | §1.7 P2, P5-HB and P7 rows vs the P4 row; §0.6 MAKER_FORCE_ASSERTION | **Asymmetric evidential standard.** The maker's records are admitted as a graded fallback (FIRED-CONDITIONAL, "maker's records state force accurately") when they cut against H-B. The maker's record that states force ("R-89 already governs") is barred even as a conditional premise when it cuts against H-D. The TASK requires NIF *or* NON-DISCRIMINATING. The fallback is neither, and it is offered to one side only. | Either allow the fallback symmetrically (P4-HD FIRED-CONDITIONAL on MAKER_FORCE_ASSERTION) or on neither side. Otherwise declare P2 and P7 NON-DISCRIMINATING or FIRED-CONDITIONAL without NIF, and state that the condition includes H-D P1 (N2). |
| **N4** | source interpretation | §0.6 AF list; D5(4) | "R-89 already governs", written by the **sole adopter**, is frozen as not an adoption, and the sensitivity result is "never scored". Together with N1 this gives a concrete false falsification of H-D: the DA declares R-89 governing, a third party applies it, and O3 fires P4-HD, although under H-D the DA's declaration is plausibly the adoption. | Label the classification PROPOSAL/UNKNOWN. Score O3 only if it holds under **both** classifications. Otherwise grade it FIRED-CONDITIONAL(AF-reading). |
| **N5** | formal reasoning; M-TIME | FR-D9 entry-into-force fallback; §0.5 | "Entry into force not earlier than recording time" is (a) not implied by the inputs: decision and effect can precede recording, which the spec's own late-recording rule concedes. It is also (b) only a *lower* bound. So "text in force before 16:10" can never be shown through the fallback. P is then unshowable and NON-CONFORMS follows by default. At the same moment "entry into force NOT_IDENTIFIABLE relative to 16:10" is true, so NON-CONFORMS and UNDETERMINED both hold, and the verdict is not a function. | Give the verdicts a precedence (UNDETERMINED first). Use the fallback only as an existence bound. Where no upper bound on entry into force exists, P and B are NOT_IDENTIFIABLE, which gives UNDETERMINED. |
| **N6** | formal reasoning; evidence scope | FR-D9 P ("no unresolved governance blockers") | Showing P requires showing an **absence**. By §0.8 that needs a complete channel, which is practically unattainable (SC-4). So failing to show an absence becomes NON-CONFORMS, and an H-B falsifier fires on NOT-EVIDENCED-IN-CAPTURE. This contradicts §0.8, FR-D5(e) and D9's own sufficiency claim ("the default is never applied to evidence that has not been seen"). | If the proviso's absence clause is NOT-EVIDENCED, C1 is UNDETERMINED, not NON-CONFORMS. |
| **N7** | formal reasoning | §5 outcome table | **Not exhaustive and not exclusive.** (i) Trace cites R-89, ACC, ¬AD, no NIF: no row. (ii) ¬G (gate not in force): no row. (iii) MENTION_ONLY and CAPTURE_ERROR (B.9 labels) are not mapped. (iv) **O5 lacks ¬ACC.** If WP-4B's acceptance is in force, the R-72 gate is satisfied and the work is authorized, yet O5 scores "P7-HB/P2-type FIRED". (v) The joint firing of O2 and O3, and the precedence of O6 over the other rows, are unstated. | Rebuild the table as a decision procedure over (trace class, ACC, G, WV, AD, F89 source), evaluated in a fixed order. Add ¬ACC to O5. |
| **N8** | formal reasoning; evidence scope | FR-D2; O0; Stage 1 gate | After D1, P4-HD (O3) is "whatever the work was", so it does not need a WP-4C-1 trace. Yet O0 says "P4 … discriminate nothing", and S1-e and S1-f (the only NIF acquisitions for R-89) run only if a trace exists. P4 is dropped for a reason that no longer applies. | Run S1-a, S1-b, S1-e and S1-f for P4 regardless of O0. Only P7 is gated on a trace. |
| **N9** | evidence scope | FR-D5(b); S1-b | The late-recording rule cannot fire: S1-b reads the DCS "over the P7 window" only, so acts recorded after the window are never acquired. Acts decided in W but recorded later *without* stating an effect date are not covered at all. | Extend S1-b to the full DCS history up to capture. Treat any post-window act on the same target as possibly late (NOT_IDENTIFIABLE), whether or not it states a date. |
| **N10** | evidence scope | S1-b; P7 WV | "No waiver in force at t_w⁻" needs waivers recorded *before* the window start, but S1-b is bounded by the window. | Read the waiver history from the root of the record. |
| **N11** | evidence scope | D6 vs §5 | "Stage 1 checks whether R-91 or R-94 refer to R11" appears in no S1 item, and Stage 1 is gated on a WP-4C-1 trace. | Add it as a P8 item, not gated on the trace. |
| **N12** | coding | FR-D8 P1 eligibility | The per-clause satisfaction rule is "written before Stage 1", so it is not frozen now. The eligibility definition also derives H-B's "work condition" from the text of H-D-style conditional acts. | Freeze the rules now, or mark P1 NOT_FROZEN. |
| **N13** | wording | §0.9 grades; §0.3 N-KNOWN; §0.6 vs C.5 | The grade vocabulary omits NON-DISCRIMINATING, LEGITIMATED, VACUOUS, ONE-SIDED and NOT_TESTABLE_AS_SPECIFIED, which are all used. N-KNOWN is defined as "known, **or claimed**", which merges factive knowledge (distinction 3) with assertion. DCS v1 membership includes NORM_STATE in §0.6 and the JSON, but not in the C.5 consequences. | Harmonise. |

---

## 2. D1 assessment

- **Is a NIF specified per prediction?** Yes. §1.7 and `nif_matrix` give each limb a requirement and a "without it" status. This part of TASK is met in form.
- **Does the spec avoid presenting a dual-explainable observation as a discriminator?** Not fully. Two dual-explainable observations still discriminate:
  1. **NIF-B (third-party application)** fires P4-HD (O3) and can establish N-FORCE for P1, P5, P7 and P8. It is behaviour, and both hypotheses explain it (N1). HUMAN-DECISIONS' principle that "a work event alone cannot establish a norm change" applies equally to a non-executor's act.
  2. **The absence of a permitting act** is used to establish non-permission (ACT-CONTRA, the graded fallback, the C1 default, O5). This observation is compatible with "H-D P1 holds and H-B conformance fails" and with "H-D P1 fails and H-B conformance holds" (N2). G-D1 does not catch it, because G-D1 is limited to the status rule.
- **Is the fallback independent?** No. The graded fallback (the maker's records) is not an independent observable. TASK says "state what additional independent observable … is required, or declare the prediction NON-DISCRIMINATING without it". For P2, P5-HB and P7, the spec takes a third route (FIRED-CONDITIONAL on the maker's records), and it offers that route asymmetrically (N3).
- **Where only NIF-A counts, the handling is correct:** P1 and P4 need NIF-A or NIF-B, and otherwise are NON-DISCRIMINATING. Once NIF-B is demoted (N1), these become NIF-A-only. Part C cannot deliver NIF-A (SC-1). The honest expectation is therefore that P1, P4 and P5-HD are NON-DISCRIMINATING, and the spec should say so in the ranking.

## 3. The six distinctions

| Distinction | Kept separate? | Issue |
|---|---|---|
| Norm exists | Yes | — |
| Norm in force | **No, partly** | It is established by NIF-B, which is an *act according to a norm* by a third party (distinction 4 → 2). The absence of a permitting *text* (¬exists) is used to infer that no norm is in force, through ACT-CONTRA (distinction 1 → 2, valid only under H-D P1). |
| Known to be in force | **Wording** | Defined as "known or claimed", which conflates knowledge with assertion. The rule that it "never establishes N-FORCE" is right for claims, but makes the category non-factive. |
| Act according to | Yes, for the executor | For third parties, it is merged into "in force" (N1). |
| Act contrary to | **Partly** | It can be established by "no permitting norm", which is derived from "no permitting act" (N2). |
| Change without act | Mostly | If both states are established by NIF-B, a "change" is a change in third-party behaviour (N1). |

## 4. Smuggling

- **H-D is still smuggled in: PARTIAL.** It enters through the act-exclusivity baseline: "no permitting act ⇒ not permitted" in ACT-CONTRA, the fallback, the FR-D9 default and O5 (N2). It also enters through the asymmetric admission of the maker's records against H-B only (N3). G-D1 neutralises the status rule but not this.
- **H-B is smuggled in (overcorrection): YES, in P4 and O3.** NIF-B treats practice as force, which is H-B's own ontology used to falsify H-D (N1). "Already governs", written by the sole adopter, is frozen as non-adoption with an unscored sensitivity (N4). Together they let O3 fire P4-HD in a scenario fully consistent with H-D.
- **Net effect.** Each hypothesis's falsifier contains a premise drawn from the other hypothesis. The repair is symmetric in form but not in substance.

---

## 5. Rank-0 protocol (Part B): **FAIL, do not execute**

**What is right:**
- The channel set is explicit and there is no lower bound.
- PAT is case-sensitive, with boundaries.
- Variant hits go to a separate bucket and are never counted.
- The ET-1, ET-2 and MENTION classes have a frozen rule and quoted clauses.
- Ancestry-minimal defines "earliest". Git dates are SOURCE_STATED only. Order relative to decision or entry into force is always NOT_IDENTIFIABLE. **M-TIME is respected: no timestamp is used for decision or effectiveness.**
- Absence is NOT-EVIDENCED-IN-CAPTURE unless C1–C4 are shown, and CAPTURE_INVALID is kept apart from absence. **Absence is handled correctly.**
- The worker/reviewer split, the blind reclassification and the DISPUTED handling are good.

**Blocking defects:**

| # | Class | Defect | Failure scenario |
|---|---|---|---|
| **R0-1** | coding | **Run B cannot run.** W4 runs `rank0-wp4c1.sh` in `git clone --mirror`, which is a **bare** repository. S0 calls `git rev-parse --show-toplevel`, and since git 2.25 this dies ("this operation must be run in a work tree") outside a work tree. The prerequisite is git ≥ 2.31. Under `set -euo pipefail` the script aborts at `00_env.txt`. (Confidence: high.) | W5 gives CAPTURE_ERROR on every run. PRIMARY results "come from Run B", so no valid result can exist, and the reviewer's R2 rerun fails the same way. The protocol forbids ad-hoc fixes, so it cannot produce an output. |
| **R0-2** | evidence scope; coding | **C2 conflicts with the fetch refspec.** `rank0-fetch.sh` fetches with the default refspecs (heads and tags). `ls-remote --refs` also lists `refs/pull/*/{head,merge}` (GitHub) and `refs/merge-requests/*/head` (GitLab), whose objects are usually not fetched. Examples: synthetic PR merge commits, squash-merged MR heads, fork PR heads. (Confidence: high for GitHub PR merge refs.) | `08_remote_objects.txt` contains MISSING, so C2 fails and the capture is CAPTURE_INVALID on any hosted repository with PRs or MRs. Conversely, unmerged or squashed MR work on WP-4C-1, which is prime execution evidence, is never searched, although B.1 claims "all refs". `refs/notes` is claimed but is not fetched by default either. |

**Non-blocking but must be fixed in r2:**

| # | Class | Defect |
|---|---|---|
| R0-3 | coding | The path-name pass (`15_paths.txt`) is **not included in `90_hitset.txt`**. ET-2 clause 3 ("a NON_DOC path name matches PAT") therefore never reaches `93_details`, ancestry, or `rank0-order.sh`. If such hashes are put into `$ET`, they have no ancestry rows and appear ancestry-minimal when they are not. |
| R0-4 | coding | Under `LC_ALL=C`, VAR's `[^0-9a-z]{0,2}` counts **bytes**. U+2010, U+2011 and U+2013 are 3 bytes in UTF-8, so "WP‑4C‑1" with non-ASCII hyphens is **not** matched, contrary to B.2. The variant bucket is under-inclusive exactly where the spec says it is covered. |
| R0-5 | coding (over-inclusion) | ET-1 fires on any subject or trailer mention plus one NON_DOC change. Examples: "WP-4B: unblock WP-4C-1 after R-89" together with WP-4B code (MIXED is recorded but has no effect on the class); a register, queue or work-plan commit in `register.yaml`, `queue.json` or `workplan.yml` (none of these match DOC_GOV unless their directory name does). ET-2 fires on a `TODO(WP-4C-1)` comment. In each case a plan or ruling becomes t_w, the premise of P7-HB. Fix: MIXED demotes ET-1 to ET-2 unless WP-4C-1 is the *first* WP id; add `ya?ml|json|csv|toml` files with governance-stem names to DOC_GOV; comment-only added lines count as MENTION. |
| R0-6 | coding | Merge rule: "NON_DOC changes not present in any other hit commit" gives no criterion for identity (path? blob? hunk?). It is not operational. |
| R0-7 | coding; reproducibility | CLOCK_ANOMALY is defined but **computed by no script**. The global git config (`~/.gitconfig`) is not isolated: only `GIT_CONFIG_NOSYSTEM` is set, not `GIT_CONFIG_GLOBAL=/dev/null`. The "byte-identical with same git version" claim is false, because `00_env.txt` (toplevel path) and `04_remotes.txt` (bundle path) are machine-specific. Classification W7 is manual, which is acceptable only because of R3's blind double coding. |
| R0-8 | evidence scope (under-inclusion) | Ref **names** (for example `feature/WP-4C-1`) are captured in `01_refs.tsv` but not matched against PAT. Work on an unmerged branch named for the package, but without the token in its messages, is invisible, although it is the cheapest identifier channel. |
| R0-9 | coding (minor) | There is a race between `fetch` and `ls-remote`. The awk path extraction uses `$2`, which truncates paths with spaces, and it maps deleted-file lines to `/dev/null`. |

**Target matching:**
- **Over-inclusive** through ET-1/ET-2 mentions (R0-5).
- **Under-inclusive** through untokened work (disclosed), ref names (R0-8), variant hyphens (R0-4), and unfetched MR/PR refs (R0-2).
- The exclusions (WP-4C-10, bare WP-4C, WP-4B) are correct.

**"Execution trace" vs "mention":** operational in principle (a path-class rule plus message position), but miscalibrated (R0-5, R0-6).

## 6. Scoping protocol (Part C): **PASS_WITH_LIMITATIONS**

**Passes:**
- It is **bounded**: a fixed list of 10 channels, one script, a stop rule, and CANDIDATE_NOT_CHECKED for anything else.
- It is essentially **existence and metadata only**.
- The independence criteria I1–I4 and the completeness criteria K1–K4 are explicit and graded.
- NOT_ACCESSIBLE is never recorded as NO, HOST = none gives SERVER_CHANNELS_UNCHECKED, and API reproducibility is correctly marked LIMITED.

**Limitations (the result must be read in their light):**

| # | Class | Issue |
|---|---|---|
| **SC-1** | formal reasoning | **NIF_A_CHANNEL_EXISTS is unreachable by construction.** Q5 = YES needs Q2 ∋ NORM_STATE. But Q2 is derived "from channel type, not content", and the only candidate type that could hold NORM_STATE (K-STATUS) is UNKNOWN_CONTENT by rule. I2 (derivation) also needs content, so Q7 = INDEPENDENT is unreachable. The protocol's answer is fixed before it is run. |
| **SC-2** | formal reasoning | The Q5 vocabulary {YES, PARTIAL, NO} has **no UNKNOWN**, so unread K-STATUS content is forced to NO. "NO_NIF_A_CHANNEL_IN_CHECKED_SET" then reads as a negative finding. This violates the spec's own rule that "not evidenced" is not "absent". Report it as NIF_A_NOT_DETERMINABLE_BY_EXISTENCE_CHECK. |
| SC-3 | evidence scope; M-TIME | Paths come only from the **current** default-branch tree (`ls-tree $DEF`). CI, hook or status files that existed during the window and were later deleted are missed. The "ever-existed" comment is inaccurate, and the current configuration does not show the configuration then in force. |
| SC-4 | evidence scope | K3 (configuration stability) and Part B's C3 (no force-push or deletion) need audit-log *content*. The script fetches only one `created_at` value. So COMPLETE_SHOWN, and hence ABSENT_IN_COMPLETE_CHANNEL and any "complete DCS", are unreachable with the frozen commands. The spec implies that Part C can supply them. The practical consequence is that every absence-premised falsifier is UNDETERMINED, and the §4 ranking should say so. |
| SC-5 | wording (internal inconsistency) | `58_path_history_meta.txt` outputs author **names and emails**, contrary to C.1's "roles, not personal names". |
| SC-6 | evidence scope | The completeness window is hard-coded to 2026-08-02..15. This conflicts with FR-D4, under which rank-0 has no lower bound and the P7 window may start before R-72. |
| SC-7 | coding | Host detection uses a URL substring: self-hosted GitLab or GHE gives HOST = none (disclosed as UNKNOWN). STATUS_RE is over-broad (`norm` also matches `normalize`), which adds noise but stays bounded. |

---

## 7. Disagreements

1. **With SPEC-r1 D1(3):** "no single observation can be counted for both sides" is false. Third-party application (N1) and the absence of a permitting act (N2) each serve both sides.
2. **With SPEC-r1 D2(3):** "the outcome table is exhaustive over the branches" is false (N7).
3. **With SPEC-r1 D9(3):** "the default is never applied to evidence that has not been seen" is false (N6).
4. **With REVIEW-1bb D1 (partly):** I agree with SPEC-r1 that bare citation shows only N-KNOWN. I go further: *application* by a non-executor is equally dual-explainable. Only an independent status record (NIF-A) separates the limbs.
5. **With REVIEW-1bb D9 (partly):** the reviewer's recommended default ("NON-CONFORMS by default", "unauthorized unless the proviso is shown met") is itself the source of N2 and N6. That default presupposes H-D's act-exclusivity and converts an absence that is not shown into a falsification. UNDETERMINED (or DISJUNCTIVE) is the defensible default.
6. **With SPEC-r1 B.7 and B.8:** the byte-identity claim and the claim that the canonical key is independent of the git version and reproducible are not supported (R0-7). Reproducibility actually rests only on the canonical key, and only once R0-1 is fixed.
7. **With SPEC-r1 §4:** the ranking of P4+P7 as HIGH is optimistic. Given SC-1 and SC-4, NIF-A and complete-channel absences are unobtainable with the frozen tools. Once N1 and N2 are fixed, the realistic outcomes for P4 and P7 are NON-DISCRIMINATING, DISJUNCTIVE or UNDETERMINED.

## 8. Required before any execution or scoring

1. **Part B, r2:**
   - Run Run B in a non-bare clone of the bundle, or guard `--show-toplevel`.
   - Either fetch `+refs/*:refs/*` or restrict C2 to refs in the fetch refspec, and state that MR/PR refs are searched or out of channel.
   - Add `15_paths` hits to the hit set.
   - Make VAR multibyte-aware, or list explicit byte sequences.
   - Recalibrate ET-1 and ET-2 (MIXED, governance data files, comments).
   - Compute CLOCK_ANOMALY; set `GIT_CONFIG_GLOBAL=/dev/null`; match PAT against ref names.
2. **Spec:**
   - Demote NIF-B (N1).
   - Extend G-D1 to act-exclusivity (N2).
   - Apply the fallback symmetrically or not at all (N3).
   - Score O3 under both AF readings (N4).
   - Give FR-D9 a verdict precedence and treat an absence that is not shown as UNDETERMINED (N5, N6).
   - Rebuild the outcome table and ungate P4 (N7, N8).
   - Extend S1-b to the full history (N9, N10); add the R-91/R-94 item (N11); freeze the P1 rules (N12).
3. **Part C:**
   - Add UNKNOWN to Q5 and rename the negative overall result (SC-1, SC-2).
   - Optionally, in r2, list historical paths and drop names and emails from `58`.
