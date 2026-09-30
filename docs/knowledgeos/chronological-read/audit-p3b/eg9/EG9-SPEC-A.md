# EG-9 SPEC-A: the AUDITED → ACCEPTED path under R7 (design only; authority: generated)

| | |
|---|---|
| **Author** | Subagent A4 (builder/researcher), S-Series P3b. DESIGN ONLY: no repository file changed, nothing executed against production |
| **Read set** | `scripts/**` (code + tests); contract rev3 `prompts/20260925_1204_p3b-agent-contract-r2.md`; R7 addendum `prompts/20260926_2400_…r7-addendum.md`; execution package; B1 v2; Freeze-2 proposal; EP-01 (design use only); EG-5 v2.8 package; EG-6 SPEC-A v2; `P3B-GOVERNANCE-LOG.md` (searched: §21, AUDITED, H-06, accept) |
| **Not read** | protocol core v1.7 §21 full text (outside the read set; only what the contract, the tools and the log quote about it is used); production slices, ledgers, corpus, hold-out, reviews, F-Series |
| **Tags** | **[F]** fact (file:line) · **[D]** design proposal · **[H]** human decision |

Citation key: ST = `scripts/p3b_s5_state.py`, AR = `scripts/p3b_s5_audit_record.py`, AS = `scripts/p3b_s5_audit_sample.py`, AC = `scripts/p3b_s5_accept.py`, K3 = contract rev3, AD = R7 addendum, PKG = `audit-p3b/20260928_S5-EXECUTION-PACKAGE-DRAFT.md`, EP = EP-01, EG5 = `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md`, EG6 = `audit-p3b/eg6/EG6-SPEC-A-v2.md`, GL = governance log.

---

## 1. What the §21 audit is (Q1)

**[F] Mechanics.**
- It is a per-batch gate between VERIFIED and ACCEPTED. The sequence is fixed in K3 §16.5: "verifier PASS (§20) → audit disposition complete (§21) → human acceptance entry (H-06, §25)" (K3:1292-1293). K3 §20 makes it gate **G-06**: "blind audit sample (§21), disagreements individually dispositioned", mechanism "AI audit + human" (K3:1343). A batch fails if "G-06 finds a disagreement dispositioned as a protocol violation (not a judgment call)" (K3:1352-1353).
- It also carries the judgment halves of three other gates: G-08 anti-projection/hindsight ("script + audit", K3:1345; §14.4 "audited §21", K3:1178); G-09 judgment conditions, §21 item 6: "favours H", a "verified" counterexample, DESCRIPTIVE/DISCOVERY fidelity (K3:1346); G-12 layer separation, §21 item 5, manufactured hypotheses (K3:1347, K3:952).
- G-11 requires every accepted record in `32-RECONCILIATION-OBJECTS.jsonl` to carry a resolvable `audit_ref` (K3:1301, 1320, 1350).
- **Sample** (AS:1-22): all CONTESTED / HOMONYM-SPLIT object records (item 1); every HYPOTHESIS, STRUCTURE-CANDIDATE, SCHEMA-LIMITATION, METHODOLOGICAL-DEFICIENCY register record (item 5); ≥ 2 other register records; max(5, ⌊0.1·N⌋) further seeded records; for a label with more than 100 Stage-2B occurrences, 30 occurrences (F3(3), GL:556). The seed is per batch, from `SeedSequence(20261100)` by manifest index (AS:16-17). It is independent of the Freeze-2 seed. It reads `objects.jsonl` and `register.jsonl` of one run directory (AS `sample_run`). A cross-batch audit is due per group of 5 batches (AS:19-20; item 3).
- **Record** (AR:1-17): an independent auditor writes a findings JSON with dispositions in the closed vocabulary ORIGINAL-UPHELD | AUDIT-UPHELD | JUDGMENT-CALL | PROTOCOL-VIOLATION (AR:30). `result = PASS` iff there is no PROTOCOL-VIOLATION (AR:15, 61-63). The report `audit-p3b/S5-AUDIT-<run>.json` is written once (AR:65-67). It is the only admissible AUDITED evidence (ST:59-60, 135-136).
- **Who:** "an independent agent, §21 item 1" (AR:5). In practice this is a fresh agent of the same model family, so "under-discovery is a lower bound, §21 item 8" (GL:916). The human gets the result at H-06 (G-06 "AI audit + human").
- **Blind or not.** K3 calls the sample "blind" (K3:1343). But the disposition vocabulary (ORIGINAL-UPHELD / AUDIT-UPHELD) and the logged practice ("sampled object agreement: 19 exact, 3 coarse, 5 different", GL:916) show that the auditor re-derives each record and then compares it with the S5 record. At most, the re-derivation is blind before that comparison. The §21 auditor therefore **sees S5 records**, unlike the EP-01 θ_D re-analyst (EP:84). The full §21 blindness text is in protocol v1.7, which was not read.
- **Known tool gaps:** AR does not enforce the §21 sample minimums, and it does not bind `sample_sha256` to an actual sample (AR:38-39 checks only for 64 hex characters). Both are recorded residuals (GL:824). The evidence check trusts the header's self-declared script, with "the H-06 human decision [as] the safeguard" (GL:824).
- **History:** §21 has run for the S4 pilot (GL:447, 510, 626) and for OB0004-R2.2. There it failed a batch that the verifier had passed (3 PROTOCOL-VIOLATIONs, GL:906-921). **The audit caught the incomplete reading of files that were recorded as whole-file. That failure mode was the origin of rev3's READ-COVERAGE gate (GL:937-948)**, and today of R7's reading-state and witness layers.

**[F] How §21 relates to the EP-01 audit.** They are different objects:

| | §21 (G-06) | EP-01 / B1 probability audit (θ_D, θ_A) |
|---|---|---|
| Unit | the batch (all 396), a record-level sample within it | the label, 231 frozen labels of 1,975 (GL:1597-1609) |
| Role | a **gate**: a FAIL fails the batch (K3:1352-1353) | a **measurement**: "never … an input to the machine verifier" (EP:8); it fails no batch |
| Timing | after VERIFIED, **before** H-06 (K3:1293; ST:6, 56) | **after** the label's object is accepted, before P4 (EP:83; PKG:77) |
| Sees the S5 record | yes (compares) | never (EP:84) |
| Selection | per-batch seed 20261100 (AS:16) | Freeze-2 CSPRNG seed (GL:1602-1604) |

**[F] Is §21 superseded under R7? No entry supersedes it.**
- G-LOG-0082 item H *added* the probability audit and says nothing about removing §21 (GL:1362).
- AD §13 lists what R7 changes and drops. §21 and G-06 are not on the list (AD:773-779). The dropped authorities are READ-LOG, R6-PROVENANCE, RUN-MANIFEST times and the rule of force (AD:779).
- AD §15 item 3, "ONE narrow independent R7 audit (HD-9)", is an audit of the R7 *implementation* (GL:1395-1411, 1466-1496), not a per-batch audit.
- The R7 dispatch prompt still names K3 as the CONTRACT input (PKG:44).
- ST's operator hint for VERIFIED is still "run §21 audit" (ST:56).
- Conclusion: **§21 is complemented by EP-01, not superseded, and under the texts in force it is still required.** What is missing is its R7 execution path:
  - PKG §1 ends at VERIFIED (PKG:30), a runbook omission that was approved by G-LOG-0104 (GL:1618);
  - AR refuses every R7 run id (AR:53).
- EG-6 (EG6:160, 228 HD-6.5) and EG-5 (EG5:244) both record the question as open.

**[F] Side observation (runbook).** PKG §1 goes from DISPATCHED (step 2) straight to "PROPOSED → VERIFIED" (step 9). No step writes DISPATCHED → PROPOSED. ST:46-47 requires that transition. This is a runbook omission for the orchestrator to fix. It is not part of EG-9.

## 2. What H-06 is (Q2)

**[F]**
- AC records a **human** decision. "It decides nothing and never auto-accepts" (AC:4).
- Every argument is mandatory: outcome, decision text, decider, reference (AC:8-10, 44-46). A decider name that matches `claude|ai|agent|orchestrator|…|auto` is refused (AC:31, 47-48).
- AC refuses unless the batch is **AUDITED** and the current run's history holds both a VERIFIED and an AUDITED entry with evidence sha256s (AC:52-54).
- A run is decided at most once, keyed on (batch, run_id) (AC:55-56). This key is attempt-blind; EG6:136-137 E-04 addresses it.
- ACCEPTED → state ACCEPTED. NOT-ACCEPTED → FAILED, with no partial acceptance (AC:12, 57-58).
- The append-only record `audit-p3b/S5-ACCEPTANCE.jsonl` is the join key "records of H-06-accepted batches". Records themselves stay `record_status: PROPOSED` (AC:13-14; K3:1295-1303).
- K3 §16.5 item 4 lets a human accept a batch with named exceptions (K3:1304).

**What H-06 certifies:** that a named human, with a named reference, accepted *this run*, after a mechanically evidenced verifier pass and §21 pass. It certifies no content.

**Can a batch be ACCEPTED without AUDITED? No, on two independent grounds:**
- ST: `TRANSITIONS["VERIFIED"] = {"AUDITED","FAILED"}` (ST:48); only AUDITED leads to ACCEPTED (ST:49); `transition` refuses ACCEPTED without `_allow_accept` (ST:189-190).
- AC:52-54 refuses without an evidenced AUDITED.
- The existing tests pin this: `test_refuses_unless_verified_and_audited`, `test_valid_path_and_invalid_transitions` (`scripts/tests/test_p3b_s5_ops_state.py`:180-189, 322).

**The block is exactly one line.**
- The state side is already R7-ready for AUDITED. `check_evidence("AUDITED", …, "OB0004-R7")` accepts an AR PASS report and refuses BATCH-PASS (ST:64-68; `scripts/tests/test_p3b_s5_state_r7_evidence.py`:100-104).
- Only AR:53, `rf"{bid}-R2(\.\d+|S)?"`, blocks R7.
- So today every R7 batch halts at VERIFIED. PROGRAM-ACCEPTED (AD:42) and "first attempt that passes AND is accepted" (EG6:183-184; EG5:274) have no reachable ACCEPTED state. By EP:20 and EP:126, every label then ends NOT-ASSESSABLE, which worst-cases the whole primary bound.

## 3. Options (Q3)

### (a) R7 keeps AUDITED; extend §21 to R7

**Engineering: what AR must check that the R7 verifier does not.**
- The R7 verifier is a mechanical composer, U ∧ W ∧ E ∧ R, with "no epistemic rule of its own" (AD:63). It carries none of the judgment checks: G-06, and the audit halves of G-08, G-09 and G-12 (§1). Those judgments stay with the auditor.
- AR's job under R7 is to bind that judgment to the right bytes. The AD's lexical "AUDIT" gate (AD:132, T99) is a verifier gate, **not** §21. The shared name is only a coincidence.
- New checks for a revision-7 run:
  1. **Grammar:** accept exactly `<B>-R7`. Refuse unit, synthesis, `.A<m>` and `-R7S` ids. R2 forms stay unchanged. This is EG-7 fix (b), EG6:159, S-07.
  2. **Verified-state binding:** read P3B-STATE. B must be VERIFIED at its current attempt, with an evidenced BATCH-PASS.
  3. **Sample binding:** a new required input `--sample`, the AS output. It is refused unless all of these hold:
     - its header `output_sha256` equals the findings' `sample_sha256`;
     - its `batch_id` is B;
     - its input hashes equal the current bytes of the verified assembly `ledger-p3b-r2/<B>-R7/`.
     This closes the GL:824 residual for R7 and detects any post-verify change.
  4. **Coverage:** every sampled record has at least one disposition. This enforces the §21 minimums, the second GL:824 residual. **[H] optional:** it tightens the tool.
- **[F] to check at implementation:** AS reads `objects.jsonl` and `register.jsonl` from one directory. The final-run write set uses these names (`scripts/p3b_s5_r7_universe.py`:53). The EG-5 assembly (EG5 §2.7) must present them in `<B>-R7/`.

**Governance: three R7 execution rules, recorded as a ruling.**
- **Non-corrective:** at R7 a disposition is recorded and never applied. AUDIT-UPHELD produces no correction record against the witness-frozen assembly. The estimation object is the verified assembly bytes (RR-3). Under R2, AUDIT-UPHELD could lead to correction records (GL:510).
- **Firewall:**
  - AS and AR take no Freeze-2 input (RR-2, EG6:132).
  - The §21 auditor is neither the S5 reader agent nor any θ_D re-analyst or θ_A adjudicator.
  - §21 samples, findings and reports are S5-record content. They are excluded from every θ_D and θ_A input (EP:84).
- **Failure class:** a §21 FAIL leads to FAILED, class H. There is no automatic re-run. This is what EG-6 already pre-registers (EG6:108; EG5:287). E-04 #2 makes it mechanical: `new_attempt` refuses once a VERIFIED entry exists (EG6:140).

**Materiality**
- Code: non-material engineering. AR and its tests change; the verifier, reader, witness, frozen objects and addendum do not.
- Governance: one ruling (a G-LOG entry) with the three rules above, plus an amendment to the approved runbook, PKG §1 steps 10-12. The runbook was human-approved in GL:1618, so its amendment is also a human act.
- **No contract text change.** K3 §16.5, §20 and §21 already bind R7, so there is no rebind.

**Effect on EP-01 blindness**
- Order: §21 → H-06 → EP-01 audit. Acceptance cannot depend on the sampled audit, because that audit happens later (EP:83; PKG:77).
- §21 selection depends only on the per-batch seed. So every batch, sampled or not, gets the same audit intensity, and there is no differential adjudication (EP:134-139).
- Blindness holds as long as the firewall holds. The θ_D re-analyst must never receive §21 material.

**Effect on EG-6**
- RR-1 holds: §21 never triggers a re-run, because class H is not re-runnable.
- RR-3 holds: "passes AND is accepted" becomes reachable.
- The accepted population is now "survived the verifier + §21 + H-06". This extends the pre-registered survival limitation (EG5:294-296). The worst-case NOT-ASSESSABLE treatment (EP:126) keeps the bound conservative whatever the reason for the loss.
- W-SYS is unaffected: it counts only X, A1 and A2 (EG5:306).

### (b) R7 skips AUDITED: VERIFIED → ACCEPTED at revision 7; EP-01 is the only audit

**Changes required**
- ST: a revision-dependent `TRANSITIONS`.
- AC:52-54: an AUDITED-free rule for R7.
- K3 §16.5, G-06, the audit halves of G-08, G-09 and G-12, and G-11 `audit_ref` (K3:1301, 1350) all need R7 text changes.
- EG-6's class-H mapping ("the §21 audit FAIL", EG6:108, 122) must be edited. That mapping is part of the policy that must be pre-registered before the canary.

**Materiality:** **contract-material.** It needs a §26 revision and an addendum rebind. It also **removes the only batch-level judgment gate.** EP-01 cannot replace it:
- it measures 231 labels;
- it gates nothing (EP:8);
- it runs only after acceptance.

The one production precedent (GL:906-921) is exactly a verifier PASS that §21 then failed.

**Effect on blindness:** safe, with no pre-acceptance audit at all. **Effect on RR:** RR-3 is reachable. Class H shrinks to H-06 rejection.

**Verdict:** it trades a governance-visible safeguard for convenience. **Not recommended.**

### (c) Other options

- **(c1) EP-01 audit as the pre-acceptance gate.** Rejected. It makes acceptance depend on the sampled audit's result. It treats sampled labels differently from unsampled ones (EP:134-139). It breaks the order in EP:83. An audit-informed rejection would also sit next to RR-1.
- **(c2) = (a) with tranche timing.** R7 batches rest at VERIFIED during S5. §21 runs per AS audit group of 5 batches (AS:19-20), which also does the item-3 cross-batch audit. H-06 is decided per tranche. This is (a) plus a scheduling choice, and it is compatible with (a).

### H-06 granularity (applies to (a) and (b))

- **[F]** AC takes one batch per call (AC:41) and permits the same `--reference` on many batches. Only (batch, run) must be unique (AC:55).
- Two readings of "a human decision":
  1. **396 individual acts.** Faithful, but costly.
  2. **Enumerated-tranche acts.** One G-LOG entry per tranche lists the batch ids, with each batch's verify-report sha and audit-report sha, reviewed at decision time. AC is then called once per listed batch under that reference, and each call is still individually gate-checked.
- **A standing rule** such as "accept whatever reaches AUDITED" would be auto-acceptance by rule. It contradicts AC:4 and EP-02, so it is not an option.
- **[H]** The human chooses between the per-batch and enumerated-tranche forms, and the tranche size. Recommended: per AS audit group (5 batches, ≈ 80 acts) or larger tranches. Named exceptions (K3:1304) stay per batch.

## 4. Recommendation (Q4)

**[D] Option (a), "R7 keeps AUDITED", with enumerated-tranche H-06.** It is the minimal option: it changes no contract text, adds no rebind and changes no frozen object.

**Proposal text** (for the governance ruling and the runbook amendment):

> **EG-9 (R7 §21 execution ruling).** Contract rev3 §16.5, §20 (G-06, and the audit halves of G-08/G-09/G-12, G-11 `audit_ref`) and §21 apply unchanged to revision-7 batches. A revision-7 batch reaches ACCEPTED only as VERIFIED (BATCH-PASS) → AUDITED (§21 PASS) → H-06. At revision 7: (1) the §21 sample is drawn by `p3b_s5_audit_sample.py` over the verified assembly `ledger-p3b-r2/<B>-R7/`, and the audit report binds that sample and the assembly bytes; (2) dispositions are recorded, never applied: no correction touches the witness-frozen assembly, whose bytes are the estimation object; (3) the §21 tools take no Freeze-2 input; the §21 auditor is neither the S5 reader nor a θ_D/θ_A auditor or adjudicator; §21 samples, findings and reports never enter any θ_D/θ_A input; (4) a §21 FAIL or an H-06 NOT-ACCEPTED is class H (EG-6): FAILED, never re-run automatically, NOT-ASSESSABLE unless a human act decides otherwise; (5) H-06 is decided by the human per enumerated tranche (listed batch ids with verify- and audit-report sha256), recorded per batch by `p3b_s5_accept.py` under the tranche's G-LOG reference; never by a standing rule.
>
> **Runbook PKG §1, steps added after step 9:** 10 `p3b_s5_audit_sample.py sample ledger-p3b-r2/<B>-R7 --index I --n-batches 396 --batch-id B --out audit-p3b/S5-AUDIT-SAMPLE-<B>-R7.json` → 11 independent §21 audit (fresh agent; findings JSON) → `p3b_s5_audit_record.py --batch B --run <B>-R7 --findings F --sample S` → `state transition B AUDITED --evidence audit-p3b/S5-AUDIT-<B>-R7.json`, or FAILED (class H) → 12 at the tranche close: the human H-06 act, then `p3b_s5_accept.py` per listed batch. Cross-batch §21 audit when a group's last batch is audited.

**RED tests** (tests first; synthetic fixtures; /tmp states):

| # | Test | Expected |
|---|---|---|
| E9-01 | AR `--run OB0004-R7` with a valid sample + findings on a VERIFIED (BATCH-PASS) synthetic state | report written; `result` PASS |
| E9-02 | AR with `OB0004-R7-L01`, `OB0004-R7-L01S`, `OB0004-R7.A2`, `OB0004-R7S` | refused (exit 2); R2 forms `-R2`, `-R2.2`, `-R2S` unchanged (regression of `test_bad_ids_refused`) |
| E9-03 | R7: findings `sample_sha256` ≠ the `--sample` output sha, or the sample's `batch_id` ≠ B, or `--sample` missing | refused |
| E9-04 | R7: an assembly byte changed after verify (sample input hash ≠ current file) | refused |
| E9-05 | R7: state not VERIFIED, or VERIFIED without an evidenced BATCH-PASS of the current attempt | refused |
| E9-06 | R7: a sampled record with no disposition (only if the coverage rule is adopted) | refused |
| E9-07 | ST: VERIFIED → AUDITED with the E9-01 report accepted (EG-7 S-08); a FAIL report refused; then FAILED with cause "§21 FAIL"; `new_attempt` refused (E-04 #2, class H) | as stated |
| E9-08 | AC on an R7 batch: refused at VERIFIED; ACCEPTED at AUDITED; NOT-ACCEPTED → FAILED and `new_attempt` refused | as stated |
| E9-09 | AC: one reference reused across two batches → two records; the second decision on the same (batch, run, attempt) refused (attempt-aware after EG-6 part (d)) | as stated |
| E9-10 | Firewall (RR-2): AS output byte-identical whether or not the batch's labels are in a synthetic frozen sample; static check: AS/AR reference no Freeze-2 record, anchor or sample path | as stated |
| E9-11 | Non-corrective: after AR, the assembly and WITNESS digests are unchanged and a re-verify is still BATCH-PASS; AR writes only `audit-p3b/S5-AUDIT-<run>.json` | as stated |
| E9-12 | `resume` next action for an R7 VERIFIED batch = "run §21 audit" (ST:56) | unchanged |

**Separability and timing**
- **v2.8 package:** EG-9 is **separable**. It touches no addendum text, so it shares no rebind with parts (a)/(b)/(d). It can join the pending v2.8 act as an independent part "(f)" at no extra rebind cost. If the human wants rules (2)-(3) normative in the contract rather than a G-LOG ruling, they become v2.8 addendum text. That makes them contract-material, and they would then share the v2.8 rebind.
- **Canary execution:** EG-9 is not needed. **Canary outcome recording:** also not needed. The canary records the per-run W1/W8/coverage/pages/time (PKG:71) and applies the stop rule to run FAILs (PKG:69). Both end at VERIFIED or FAILED. A passing canary batch "counts as a normal S5 batch" (PKG:70), so it simply waits at VERIFIED until EG-9 lands.
- **Two dependencies argue for deciding (a) vs (b) with or before the canary act. Implementing it can wait.**
  1. EG-6's pre-registered class H names "the §21 audit FAIL" (EG6:108, 122). Option (a) keeps that text true. Option (b) would change the policy, and the policy must be fixed before any S5 output (EG5:271).
  2. The firewall and non-corrective rules are audit-procedure rules. Fixing them before any S5 output exists avoids post-hoc audit design.
- **Implementation deadline:** it must be implemented before the first R7 §21 audit, and in any case before any R7 batch can reach ACCEPTED, and so before the EP-01 audit, which starts only on accepted objects (EP:83).

## 5. Human decisions

1. **H-EG9-1:** confirm option (a): §21 and AUDITED apply to R7 unchanged in the contract, and EG-9 is recorded as a governance ruling. The alternative is (b), a §26 contract revision that removes the gate.
2. **H-EG9-2:** where the ruling's rules (2)-(3) live: as a G-LOG ruling (separable, no rebind), or as v2.8 addendum text (shares the rebind).
3. **H-EG9-3:** H-06 granularity: per batch (396 acts), or enumerated tranches (recommended: per audit group of 5, or larger). A standing rule is excluded.
4. **H-EG9-4:** whether AR enforces the §21 sample coverage minimums (E9-06).
5. **H-EG9-5:** authorize the AR/state engineering, tests first. Timing: the decision with the v2.8 act; the implementation any time before the first R7 §21 audit.

**Traceability:** K3 §16.5, §20, §21 (as quoted) · AD §0, §13, §15 · PKG §1, §3, §4 · EP §1, §4, §7 · B1 v2 · EG5 §13-14 · EG6 §3-5 · GL G-LOG-0043 (:824), 0049 (:906-921), 0050, 0082 (:1362), 0103, 0104 (:1611-1620) · ST, AR, AS, AC as cited.
