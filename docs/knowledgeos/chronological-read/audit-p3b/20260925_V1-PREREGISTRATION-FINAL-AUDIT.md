# V1 pre-registration: final independent methodological audit (audit only; nothing executed)

**Commission:** human instruction "S-SERIES — FINAL V1 PREREGISTRATION AUDIT" (2026-09-25), recorded as G-LOG-0061.

**Verdict: NEEDS-PREREGISTRATION-REPAIR** (§16). **The frozen pre-registration was NOT modified.**

**What was not done:** no V1 agent run; `V1-AUTHORIZATION.json` not created; `pilot-s5-decomp/v1/` not created; no corpus content read; H-19, S5c, OB0018 and the v2 pilot untouched; no Gate C or frozen S-Series evidence modified.

**Severity scale:** BLOCKER (unrepaired, it can make a V1 outcome uninterpretable) · MATERIAL BUT REPAIRABLE · MINOR · ACCEPTABLE LIMITATION.

## 1. Audit scope

- **Question:** is the frozen V1 pre-registration internally coherent and methodologically defensible enough to execute **exactly as written**?
- **Criterion:** findings are raised only where a contradiction, invalid inference, circularity, leakage channel or decision-rule defect could make the result uninterpretable. Nothing is raised because another design might be better.
- **Evidence:** reading of the frozen text and code, plus **synthetic probes** (P1–P11) run against the frozen tool source extracted from commit `ec4b5912e`. The probes used invented text only; no corpus, no resolver, no V1 artifact.

## 2. Files audited (all at commit `ec4b5912e232aa242f250dc4307e4836d3d5457e`; working tree verified identical)

| File | Role |
|---|---|
| `audit-p3b/20260925_1530_v1-instrument-validation-preregistration.md` (sha256 `86917a61…04fb`) | pre-registration |
| `scripts/p3b_v1_instrument.py` | frozen tool |
| `scripts/tests/test_p3b_v1_instrument.py` | 16 synthetic tests |
| `P3B-GOVERNANCE-LOG.md` G-LOG-0060 | governance record |
| `scripts/p3b_read_source.py` (read-only check) | confirms the paged reader accepts runs `PX0106-[USA]##` and batch `PX0106` (pilot logs go to `pilot-s5-decomp/<run>/`) |

**Synthetic probes (frozen code, invented inputs):**

| Probe | Result |
|---|---|
| P1 | the list-letter permutation is **computable** from the public commit hash and the committed code (computed; deliberately not recorded here) |
| P2 | an inventory marking **every** segment NO-SUBSTANTIVE-PROPOSITION (with a reason) scores coverage 1.0, a clean schema and zero quotes |
| P3 | one-character quotes verify as EXACT |
| P4 | a repair record for an **unflagged** item is accepted and replaces it |
| P5 | capture not computable → NOT-USABLE; κ not computable → NOT-USABLE |
| P6 | coverage 0.6 (e.g. an agent that could not finish) → NOT-USABLE |
| P7 | an agreed set of size 1 gives capture 1.0 |
| P8 | segmentation edge cases (leading whitespace, heading-first, unclosed fence, CRLF, `#######`, no newline, U+2028, whitespace merge) all **tile**, are **deterministic** and give the expected kinds; one merged segment is 6,047 characters (> MAX_SEG) |
| P9 | `access()` scans only `OUT-<run>.jsonl`; `CLUSTERS-A01` and `DECISIONS-A01/A02` are never scanned |
| P10 | the tool never compares model identity with §3 |
| P11 | a decision unit that M1 omitted is silently excluded from κ; with a single unit, κ = 1.0 |

## 3. Findings by severity

| ID | Severity | Section(s) | Finding |
|---|---|---|---|
| **B1** | **BLOCKER** | §16, §17, §8 P-OPEN; `capture()`, `decide()` | The capture gate cannot attribute failure to the SEG instrument. Low or uncomputable capture can come from (a) a **scope asymmetry**: SEG is told multi-file claims are out of scope, while P-OPEN has no such exclusion; (b) matching and granularity; (c) M1 bias under weak blinding (M3); (d) an empty or tiny agreed set (P5, P7). Yet capture alone can return NOT-USABLE. See §4 |
| **B2** | **BLOCKER** | §15, §17; `access()`, `cmd_check`, `cmd_result` | Matcher integrity is **outside the decision**. `access_breaches` is computed in `check`, which runs **before** M1 and M2 exist, and `result` reuses it. Matcher read logs are never audited, and matcher outputs are never canary-scanned (P9). Model identity (listed as a hard integrity condition in §15) is not checked (P10). **Consequence:** M2 reading the committed `DECISIONS-A01.jsonl` would inflate κ undetected and could yield INSTRUMENTS-VALID |
| **M1** | MATERIAL-REPAIRABLE | §17 vs §18; `decide()` | (a) **Contradiction:** §18 says an agent unable to complete → NEEDS-REVISION, but the tool maps the resulting low coverage to **NOT-USABLE** (P6). (b) "Not computable" κ or capture (e.g. M2 produced nothing, or E1 and E2 agree on nothing) → NOT-USABLE, which attributes a run failure to the instrument (P5). (c) RUN-INVALID shares the outcome label NEEDS-REVISION and is distinguished only in free-text reasons. (d) §18 stops (forbidden-path self-report, model mismatch) are not mapped to an outcome |
| **M2** | MATERIAL-REPAIRABLE | §9, §11; `with_repair()` | The repair is not mechanically bounded. Any key can be replaced (P4), and SEG repairs replace a **whole segment record**, so unflagged propositions in that segment can change. The failure list given to the agent is not committed before the repair. "Change nothing else" is enforced by prompt only |
| **M3** | MATERIAL-REPAIRABLE | §4, §11, §15 | Blinding is weaker than stated. (a) The "sealed" letter map is derivable from the public commit hash and code (P1). (b) Canaries detect only **verbatim copying of a header string**, and the prompt instruction "never write any other CANARY- string" suppresses even that signal. Content borrowed from another output carries no canary. (c) The SEG list is plausibly recognisable from structure (atomic granularity, size). This matters because M1 **alone** decides capture (B1) |
| m1 | MINOR | §13; `kappas()`, `_kappa()` | Decision units are counted in both directions (X→Y and Y→X), so they are not independent. A unit omitted by M1 is excluded without being counted (P11). The degenerate case p_e = 1 returns κ = 1.0 |
| m2 | MINOR | §13 | The text says κ is "not evidence of completeness", but not explicitly that **agreement ≠ correctness** (two matchers can agree wrongly) |
| m3 | MINOR | §9 | Quote verification checks string provenance only. Trivially short quotes pass (P3). No quote-length diagnostic is registered |
| m4 | MINOR | §5; tests | A whitespace merge can exceed MAX_SEG (P8: 6,047). U+2028 and similar characters count as line breaks. Both are deterministic and harmless. The tests do not exercise the leading-whitespace, unclosed-fence, CRLF or heading-first branches; the probes show they behave correctly |
| m5 | MINOR | §7, §11 | Duplicate `item_id` or `proposition_id` values collapse silently in repair and equalization. Referential fields (`parent_proposition`, `first_occurrence`, `relation_target`) are not checked |
| m6 | MINOR | §11, §18 | Re-dispatch after a transient agent or tool error is unspecified. The rule "one repair round when failures exist" is implied rather than stated as mandatory |
| L1 | ACCEPTABLE LIMITATION | §3, §4 | Single vendor; M2 = the E1 model; E2 a smaller model. All disclosed; none is a protocol violation |
| L2 | ACCEPTABLE LIMITATION | §2 | The file-selection rule was chosen after sizes were visible (disclosed); the files were previously exposed (disclosed) |
| L3 | ACCEPTABLE LIMITATION | §4 | Non-corpus file access is enforced by prompt and self-report only (disclosed) |
| L4 | ACCEPTABLE LIMITATION | §13, §16 | κ is conditional on M1's clustering (stated); capture cannot see shared blind spots (stated) |
| L5 | ACCEPTABLE LIMITATION | §7 | CLAIM acts as a de facto broad class. The taxonomy does not enter any gate |

## 4. Capture-gate analysis (the critical point)

**What capture is:** the share of E1 clusters that M1 matched (MATCH) to an E2 cluster and also matched (MATCH or PARTIAL) to a SEG cluster. It is a **relative recall of SEG against an operational reference set**, E1∩E2 as judged by M1. It is **not** a capture–recapture estimate. The independence caveat in §16 belongs to the Chapman estimate. §16 and §17 place the two side by side, and that conflation is the source of the apparent contradiction.

1. **Can a diagnostic-because-not-independent quantity be a hard gate?** Capture's **computation** does not need independence. Its **interpretation** as "SEG instrument quality" does need three unestablished conditions:
   - the reference is on SEG's scope;
   - matching is unbiased;
   - the reference is large enough.

   So capture can legitimately be an *engineering* gate that is able to *withhold* VALID. It cannot legitimately *condemn* the instrument (NOT-USABLE), because a low value is not uniquely attributable to SEG.
2. **Does low capture measure instrument failure or E1/E2 disagreement?** Neither cleanly. E1/E2 disagreement shrinks the **denominator**; it does not lower capture directly. Low capture mixes:
   - SEG omission (instrument failure);
   - scope asymmetry (SEG correctly excluding multi-file claims that the E prompts allow);
   - granularity and matching effects;
   - M1 bias.
3. **Can E1 and E2 be incomplete in the same way and still give high capture?** Yes. High capture says nothing about shared blind spots. This is acceptable only because V1 claims no completeness (§22 holds).
4. **Can one extractor be much better while capture is low?** A quality gap does not lower capture directly. It shrinks the agreed set towards the most salient propositions, which makes capture **optimistic and noisy**. A tiny agreed set gives an extreme value (P7: n = 1 → 1.0).
5. **Does the gate turn an unvalidated assumption into a decision criterion?** Partly. It is not the independence assumption. It is the assumption that **E1∩E2, judged by M1 alone, is a fair same-scope reference for SEG**. That becomes decisive in the NOT-USABLE direction.
6. **Would V1 be cleaner with capture diagnostic only?** **No, not entirely.** Without a content-sensitive gate, an inventory that marks every segment NO-SUBSTANTIVE passes coverage = 1.0 with zero quote misses (P2). INSTRUMENTS-VALID would then be reachable by a **vacuous** inventory. Capture is the only gate that guards against this.

**Smallest correction (R1):**
- keep capture as a gate that can **withhold** INSTRUMENTS-VALID;
- **remove it from the NOT-USABLE rules**;
- require a minimum agreed-set size;
- route "not computable" or too-small agreed sets to NEEDS-REVISION (INSTRUMENT-UNDETERMINED);
- align scope by one sentence in P-OPEN;
- relabel it in §16/§17 as a **relative-agreement engineering gate**, separated from Chapman.

## 5. κ-gate analysis

**What κ_target establishes:** agreement between two blinded matchers on cross-list correspondence decisions (status plus target) for a seeded, stratified sample. It is **conditional on M1's clustering**, which M2 receives and does not reproduce. The model pairing is same-vendor, and M2 shares a model with E1.

**What it does not establish:**
- **validity of the clustering** (M2's objections are diagnostic only);
- **correctness of matches** (agreement ≠ correctness; m2);
- extraction completeness;
- model independence.

**Is the existing text sufficient?** §13 already states that κ is conditional on clustering and not completeness evidence. Adding "agreement ≠ correctness" (m2) is a wording improvement, not a blocker.

**Sample adequacy:** strata (6 files × 3 lists) with n = max(5, ⌈0.3N⌉) give at least about 90 clusters and about 180 decision units, which is adequate for a descriptive κ. The two directions are not independent (m1). The κ is descriptive, so this is MINOR.

**Thresholds:** 0.60 and 0.40 are labelled ARBITRARY engineering values, which is correct.

**The real κ risks are integrity, not arithmetic:**
- **B2:** M2 could read M1's committed decisions undetected;
- **M1(b):** κ not computable → NOT-USABLE attributes a run failure to the instrument.

## 6. File-selection analysis

- **Reproducible:** yes. The rule plus the committed read logs (hashes recorded) give exactly the six files.
- **Protected against selection bias:** for V1's purpose, adequately. V1 needs files that **exercise the instruments** (1 to 9 pages, 15–174 k characters, two labels), not representative files. File size is not plausibly related to the instrument outcome in a way the investigator could exploit.
- **Visible sizes:** disclosed. They would be material for a corpus-inference or strategy experiment, but not for instrument validation. A rule fixed before seeing sizes would be preferable, but it is not required here.
- **Previous exposure:** the orchestrator's prior exposure does not reach the agents. Disclosed.
- **Verdict:** ACCEPTABLE LIMITATION (L2).

## 7. Model-independence analysis

| Distinction | Present? | Assessment |
|---|---|---|
| Same-vendor | yes (all roles) | limitation, disclosed; not a violation |
| Same-model | M2 = E1 (fable); SEG = the orchestrator model (opus; the orchestrator makes no judgments) | possible self-preference in M2 on E1-involving units. Its direction on κ is unknown. Disclosed in §4 |
| Cross-role contamination | none by design | M1 (sonnet) extracts nothing |
| Actual information leakage | **not controlled for matchers** | B2 (M2 → M1 decisions) and M3 (derivable letter map). This is the material issue, not vendor identity |

**Is the disclosure sufficient?** Yes for vendor and model overlap (L1). The leakage channels require B2/M3 repairs, not more disclosure.

## 8. Segmentation analysis

**Properties:**
- **Deterministic:** yes (P8; tests).
- **Complete tiling:** asserted and checked; P8 holds on all edge cases.
- **Headings:** `^#{1,6}[ \t]+\S` outside fences; `#######` correctly rejected.
- **Fences:** toggled by fence lines; an unclosed fence gives no headings or cuts until the end of the file, and falls back to hard cuts (still tiles).
- **Long segments:** greedy last paragraph cut within 6,000, else a hard cut.
- **Whitespace:** merged into the previous segment, or the next if first. This can exceed 6,000 (m4, harmless).
- **Page mapping:** a correct 20,000-character page arithmetic.
- **Stable IDs:** order-based, stable given the pinned content hash (checked by `texts_of`).

**What coverage measures:** coverage = 1.0 measures **mechanical accounting for every segment**, and nothing about semantic completeness. §14 and §17 do not claim more. P2 shows it is satisfiable vacuously, which is why B1's correction keeps a content-sensitive gate.

**Tests:** they cover the critical invariants (tiling, determinism, bounds, fences, split, hard cut, merge, pages). Uncovered branches (m4) behave correctly under probe. Adding them is desirable at repair time but not required.

## 9. Proposition-taxonomy analysis

- **Types do not enter any V1 gate.** They are dropped at equalization. Taxonomy imprecision therefore cannot change the outcome.
- **"There is no 'other' class"** prevents *literal* category escape. The residual *functional* escape (forcing an item into CLAIM) is harmless to V1 (L5).
- **DECLINE, NON-MATERIAL and OUT-OF-LABEL-SCOPE are not escapes from "every proposition".** A declined proposition stays in the inventory and in the matching lists (`flatten_inventory` includes it).
- **NO-SUBSTANTIVE-PROPOSITION is the only segment-level escape.** It is bounded by the required reason, the NO-SUBSTANTIVE rate diagnostic, and the capture gate. That bound depends on R1 keeping capture as a VALID-withholding gate.
- **No taxonomy expansion is warranted.**

## 10. Outcome-state analysis

- **RUN-INVALID must never read as instrument failure.** Under the frozen text it is distinguished only in a reasons string (M1c).
- Two contradictions or misattributions exist:
  - **§17 vs §18** for an agent that cannot finish (M1a);
  - "not computable" → NOT-USABLE (M1b).
- **Minimal reporting distinction (R3):** outcome ∈ {INSTRUMENTS-VALID, NEEDS-REVISION, NOT-USABLE}, with a mandatory qualifier for NEEDS-REVISION ∈ {INSTRUMENT, INSTRUMENT-UNDETERMINED, RUN-INVALID}. Every §18 stop is mapped explicitly. The three-outcome rule you set is preserved.

## 11. Repair analysis

- **No outcome-dependent bias from evaluator judgment.** The repair prompt receives failure ids and classes only (MISSING-SEGMENT, SCHEMA, QUOTE-MISS, QUOTE-OUT-OF-SEGMENT), with no substantive judgment. That is sufficient.
- **The remaining risk is scope (M2):**
  - unflagged keys may be replaced;
  - a SEG repair replaces the whole segment record, so unflagged propositions in it may change;
  - withdrawals of E items shrink the E sets (and so the capture denominators);
  - the failure list is not committed first.

  Repair can therefore silently change unrelated propositions.
- **Minimal fix (R4):**
  - commit the failure list;
  - accept repair keys only from that list;
  - require unflagged propositions in a repaired SEG segment to be carried over unchanged;
  - report withdrawals.

## 12. Blinding analysis

| Channel | Protection in the frozen protocol | Actual strength |
|---|---|---|
| E1 ↔ E2 ↔ SEG | parallel dispatch; prompt prohibition; canaries | outputs sit on a shared filesystem while being written. Canaries catch only verbatim header copying, and the prompt tells agents not to write other canaries (M3b). **Prevention is by prompt only; detection is weak** |
| M2 → M1 decisions | prompt prohibition | `DECISIONS-A01` is committed before M2 runs, and matcher reads and outputs are not audited (B2). **Not prevented, not detected** |
| M1 knowing which list is which source | letter permutation, hashed ids, structure equalization, delayed key commit | the permutation is derivable from public data (P1), and the structure likely reveals SEG (M3). **Nominal** |
| Extractors → prior results | prompt prohibition | only corpus reads are logged; repository files (pilot/Gate C outputs) are readable. Disclosed (L3) |
| Hashed item ids | `sha(commit:source:orig_id)` | effective, unless an agent reads the OUT files (prohibited; unaudited for matchers, see B2) |

**Stated plainly:** the protocol **prevents** nothing mechanically beyond corpus-read logging. It **detects** extractor over-reading of corpus files and verbatim header copying. All other isolation rests on prompts and self-report.

## 13. Pre-registration-boundary analysis

**Frozen before execution (confirmed):**
- six files (§2 plus tool constants, with a hash and length check);
- model assignments (§3; tool `RUNS`);
- prompts (§8, verbatim);
- segmentation (§5; `segment()`);
- schemas (§6–§7; tool sets);
- matching protocol (§10–§11);
- sample seed and rule (§12; `draw_sample`);
- κ calculation (§13; `kappas`);
- integrity conditions (§15, but see B2);
- decision rules (§17; `decide`, but see B1 and M1);
- stop conditions (§18).

**Execution-time choices that remain uncontrolled:**
- how model identity is evidenced (self-report vs the orchestrator's dispatch record; B2);
- who verifies the freeze ordering of §15 (manual, unspecified);
- re-dispatch after transient failure (m6);
- whether a repair round is mandatory (m6);
- the failure list not committed before repair (M2).

## 14. V1 interpretation boundary

**V1 CAN establish (on six previously exposed files, with these same-vendor models):**
- whether the segmentation and inventory machinery can mechanically achieve complete segment accounting;
- whether extractor quotes verify verbatim;
- whether two blinded matchers reach the pre-set agreement gate, conditional on M1's clustering;
- whether the SEG inventory reproduces what two independent open-ended readers agree on (after R1);
- whether the pipeline runs reproducibly end to end.

**V1 CANNOT establish:**
- corpus-wide or label-wide extraction completeness;
- semantic completeness of any inventory;
- correctness of matches;
- superiority of B2 or A0;
- research-first vs reconstruction-first performance;
- model independence;
- the theoretical validity of KnowledgeOS;
- production readiness.

**Does the pre-registration preserve this boundary?** Yes: §1, §13, §17, §21 and §22 hold it. The only statement that overreaches is the blinding and detection language (M3), which R5 corrects.

## 15. Exact required corrections (minimum set; proposed, NOT applied)

**Mechanics:** apply as a superseding pre-registration **v1.1** (a new dated file citing `ec4b5912e`; the frozen file stays as the historical record), with the tool and tests re-frozen in one new commit. Execution authorization would then name that commit.

| # | Fixes | Section / code | Minimum change |
|---|---|---|---|
| R1 | B1 | §8 P-OPEN, §16, §17; `capture`, `decide` | (a) Rename capture to **"relative agreement with the E1∩E2 reference (engineering gate; not capture–recapture; not completeness)"**, in a paragraph separate from Chapman. (b) Delete `capture < 0.50` from NOT-USABLE. (c) INSTRUMENTS-VALID additionally requires agreed-set size ≥ 20 (**ARBITRARY**). (d) Agreed-set size < 20 or capture not computable → NEEDS-REVISION (INSTRUMENT-UNDETERMINED). (e) Add to P-OPEN: "Record only propositions supported by a single file; do not combine files." (This aligns scope with SEG.) |
| R2 | B2 | §15, §17; `access`, `cmd_result` | (a) `result` recomputes the access check over **all eight runs' read logs**. (b) The canary scan covers **every** V1 output (`OUT-*`, `CLUSTERS-A01`, `DECISIONS-A01/A02`); matcher output headers must carry their own canary. (c) Model identity is evidenced by an orchestrator-written `V1-DISPATCH.json` (the Agent `model` parameter per run), compared with §3; the self-report becomes diagnostic |
| R3 | M1 | §17, §18; `decide` | Add the qualifier for NEEDS-REVISION ∈ {INSTRUMENT, INSTRUMENT-UNDETERMINED, RUN-INVALID}. Map outcomes as follows: a SEG agent unable to finish → NEEDS-REVISION (INSTRUMENT); an E, M or orchestrator failure, M2 missing units, or any §18 stop → NEEDS-REVISION (RUN-INVALID); κ not computable → RUN-INVALID. Restrict NOT-USABLE to coverage < 0.95 **from completed SEG runs**, quote misses > 5%, or κ_target < 0.40 on a complete M2 |
| R4 | M2 | §9, §11; `with_repair`, `cmd_check` | Commit `CHECK.json` (the failure list) before the repair. Accept repair keys only from that list (others are recorded as deviations and ignored). In a repaired SEG segment, unflagged propositions must be carried over unchanged (compared by id and content), else deviation. Report withdrawals |
| R5 | M3 | §4, §11, §15; `equalize` | Salt the letter permutation with a secret nonce generated at `equalize`, stored in `SOURCE-KEY.json` (committed after M2; only its sha256 is committed earlier). Delete "never write any other CANARY- string". Restate: canaries detect verbatim copying only; SEG may be recognisable from structure; isolation otherwise rests on prompts |
| (opt.) | m1–m6 | §13, §9, tests | Count M1-omitted units; report κ per direction; define p_e = 1 as "not computable"; add "agreement ≠ correctness"; add a quote-length diagnostic; add the tests listed in m4; check for duplicate ids; state "one repair round is mandatory when failures exist; no re-dispatch" |

These corrections change no file, model, prompt intent, segmentation rule, schema, sample rule or κ formula. They repair attribution, integrity coverage and wording only.

## 16. Overall verdict

### NEEDS-PREREGISTRATION-REPAIR

- **Two blockers** make a V1 outcome potentially uninterpretable:
  - **B1:** NOT-USABLE can be caused by a capture shortfall not attributable to SEG;
  - **B2:** matcher leakage and model identity lie outside the decision, so INSTRUMENTS-VALID could rest on an inflated κ.
- **Three material issues** are all repairable before execution: M1 (outcome attribution), M2 (repair scope) and M3 (blinding overstatement).
- **No fundamental methodological problem** was found. The instrument concept, file set, models, segmentation, schema, sample and κ are sound for their stated purpose.

**Next human decision:**
- **"AUTHORIZE V1 PRE-REGISTRATION REPAIR (R1–R5)"** (optionally including the minor items). That would produce v1.1 plus the re-frozen tool, followed by a re-audit or direct execution authorization; or
- another ruling.

**Execution under `ec4b5912e` is not recommended.**

**State:** nothing executed; the frozen pre-registration is unmodified; production frozen; H-19 SEALED; S5c PROHIBITED.
