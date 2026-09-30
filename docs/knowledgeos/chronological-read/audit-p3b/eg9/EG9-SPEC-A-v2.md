# EG-9 SPEC-A v2: delta to EG9-SPEC-A (design only; authority: generated)

| | |
|---|---|
| **Author** | Subagent A4. DESIGN ONLY. No repository file was changed |
| **Responds to** | `EG9-REVIEW-B.md` (SPEC-ACCEPTABLE with revisions), orchestrator items 1–6 |
| **Newly read** | protocol v1.7 §21, `prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md`:1958-1992 (P17) |
| **Unchanged from v1** | recommendation (a), keep §21/AUDITED under R7 · the AR engineering and tests E9-01…E9-12 · the firewall · class H |

Tags as in v1: **[F]** fact · **[D]** design · **[H]** human decision.

---

## 0. Correction found on reading P17 (new, MATERIAL to v1 rule (2))

**[F]** P17 §21 item 2 reads: "`AUDIT-UPHELD` (→ correction record)". v1's rule (2), "dispositions are recorded, never applied", therefore conflicts with the protocol text. Item 1 also states that the auditor is given the same contract and inputs "but not the batch's output". This confirms that the re-derivation is blind and that only the later comparison sees S5 records.

**[D] Replacement rule (2): the correction is kept apart from the assembly.**
- At R7, an AUDIT-UPHELD disposition produces its correction record exactly as P17 requires.
- The record is **append-only, under `audit-p3b/`** (P17 item 4). It is never written into the witness-frozen assembly `ledger-p3b-r2/<B>-R7/`.
- The **estimation object stays the verified assembly bytes** (RR-3), so θ_D and θ_A measure the S5 reading, not an audit-corrected reading.
- Correction records reach downstream consumers only through the acceptance join, as §16.5 already arranges (K3:1295-1303).
- E9-11 is re-read accordingly. After AR, the assembly and WITNESS digests are unchanged. Any correction records exist only under `audit-p3b/`.

**[H] H-EG9-6:** confirm this reading of item 2 at R7.

## 1. H-06 tranche binding (MATERIAL; review finding 5)

**[F] The gap.** AC checks that `--reference` is present (AC:44-46). It never checks that the reference names *this* batch, or *this* batch's evidence. A tranche is therefore prose only.

**[D] The artifact: `audit-p3b/H06-TRANCHE-<nnnn>.json`**, canonical JSON, committed before any AC call:
```
{"schema": "H06-TRANCHE v1", "tranche_id": "<nnnn>", "reference": "G-LOG-####", "decided_by": "<human name>",
 "batches": [{"batch_id": "OB####", "run_id": "OB####-R7", "attempt": m,
              "verify_report_sha256": "<64hex>", "audit_report_sha256": "<64hex>",
              "outcome": "ACCEPTED"|"NOT-ACCEPTED", "exceptions": [<label ids stay PROPOSED, K3:1304>]}]}
```
The governance entry `G-LOG-####` carries exactly one line of the form `H06-TRANCHE audit-p3b/H06-TRANCHE-<nnnn>.json sha256 <64hex>`.

**[D] Minimal change to `p3b_s5_accept.py`.** It adds one argument and one function; the state machine and the rest of the checks stay as they are.

1. **New argument `--tranche FILE`.**
   - It is required when the batch's revision is 7, and optional before that. Historical behaviour for rev < 7 stays byte-identical.
   - `record()` gains `tranche=None` and calls `check_tranche(...)` **before** `stm.transition`, so a refusal changes nothing.
2. **New function `check_tranche(tranche_path, batch, b, entries, outcome, decided_by, reference, gl_path="P3B-GOVERNANCE-LOG.md")`.** It refuses unless every one of these holds:
   - (a) The tranche file is tracked in git and unmodified: `git ls-files --error-unmatch` and `git diff --quiet HEAD -- <path>`.
   - (b) The governance log has a section `## <reference> —` (regex `^## (G-LOG-\d{4}) —`, up to the next `## `). That section contains **exactly one** `H06-TRANCHE <path> sha256 <h>` line, and `h` equals sha256(tranche file).
   - (c) `tranche.reference == --reference` and `tranche.decided_by == --decided-by`. The existing NON_HUMAN check (AC:31, 47-48) is applied to `tranche.decided_by` as well.
   - (d) The batch appears **exactly once** in `batches`. Its `run_id` and `attempt` equal the current ones: `b["run_id"]`, and the EG-6 attempt (until EG-6 part (d) lands, the attempt is 1).
   - (e) `verify_report_sha256` equals the `evidence.sha256` of the current attempt's VERIFIED entry, and `audit_report_sha256` equals that of its AUDITED entry. These values come from `entries` (AC:51-52).
   - (f) `outcome == --outcome`.
3. **Two new fields in the acceptance record:** `tranche_path` and `tranche_sha256`.
4. **Nothing else changes:**
   - the AUDITED precondition (AC:52-54);
   - the at-most-once rule (AC:55-56; attempt-aware after EG-6 (d));
   - the human-only decider rule;
   - the rule that ACCEPTED is written only by AC (ST:189-190).

**RED tests** (on /tmp git repos, synthetic governance log, state and reports):

| # | Case | Expected |
|---|---|---|
| T-01 | valid tranche, listed batch, matching shas, outcome, decider, reference | recorded; state ACCEPTED; record carries `tranche_sha256` |
| T-02 | R7 batch without `--tranche` | refused; state unchanged |
| T-03 | batch not listed (same reference) | refused |
| T-04 | batch listed twice | refused |
| T-05 | listed `run_id` or `attempt` ≠ current | refused |
| T-06 | listed verify or audit sha ≠ the live evidence sha (report replaced after the decision) | refused |
| T-07 | tranche file edited after commit, or untracked | refused |
| T-08 | governance entry missing, names another sha, or carries two `H06-TRANCHE` lines | refused |
| T-09 | `--reference` or `--decided-by` ≠ the tranche's; a tranche `decided_by` matching NON_HUMAN | refused |
| T-10 | `--outcome ACCEPTED` for a batch listed NOT-ACCEPTED (and the reverse) | refused |
| T-11 | NOT-ACCEPTED listed and called → FAILED (class H); `new_attempt` refused | as stated |
| T-12 | rev < 7 batch with no `--tranche`: behaviour and output identical to today | regression green |
| T-13 | every refusal leaves `P3B-STATE.json` and `S5-ACCEPTANCE.jsonl` byte-identical | as stated |

**Is each per-batch call then a genuine execution of one human decision? Yes, within a stated bound.**
- **What it guarantees:** every ACCEPTED batch was named individually, together with the exact evidence the human reviewed, in a committed artifact anchored by sha in the named governance entry. The call can neither extend the decision to an unlisted batch nor carry it over to changed evidence. In the property that matters, it is equivalent to 396 individual acts.
- **What it does not prove:** that a human authored the governance entry. Many entries are "recorded by the AI session" (for example GL:912). The binding proves *consistency* between the decision record and the state change; *authorship* rests, as today, on the governance log's decider field. This is the residual, and it is disclosed.

## 2. Materiality argument for rejecting (b): replacement text

Replaces the last paragraph of v1 §3(b):

> **Why EP-01 cannot replace §21 (categorical).**
> - The R7 verifier is "a composer of predicates … It contains no epistemic rule of its own" (AD:63). BATCH-PASS := U ∧ W ∧ E ∧ R (AD:39).
> - No combination of these predicates can evaluate the judgment content that K3 assigns to the audit:
>   - G-06, semantic disagreement (K3:1343);
>   - the audit halves of G-08, anti-projection (K3:1345);
>   - G-09, the §13.9a judgment conditions (K3:1346);
>   - G-12, layer separation and manufactured hypotheses (K3:1347; P17 item 5).
> - EP-01 cannot take this role either. It is a post-acceptance measurement of 231 labels over a fixed list of decision fields. It gates nothing (EP:8), and it does not examine register form or manufactured hypotheses.
> - Option (b) would therefore leave these gates with **no enforcement at all**.
>
> *Historical colour only:* OB0004-R2.2 (GL:906-921) passed the verifier and then failed §21, because files that had been read only partially were recorded as whole-file. **R7 now closes that specific mechanism** through witnessed reading coverage (AD §6: WHOLE-FILE ⇔ pages 1…n witnessed in the owning run). The precedent is not evidence that the gap persists under R7.

## 3. Class-H limitation sentence (added to the EG-9 proposal text, as item (4a))

> **(4a) Named limitation.**
> - A §21 FAIL or an H-06 NOT-ACCEPTED removes the **whole batch** (K3:1352-1355): every sibling label, including labels in no sampled record. The loss cannot be retried (class H).
> - All of these labels become NOT-ASSESSABLE and are **worst-cased in the primary bound under EP-01 §7** (EP:126). No replacement draw is made.
> - This batch-granular, non-retryable loss is disclosed **alongside** EG-6's retry-survival limitation (EG5:294-296) and reported separately in E-3 per class.
> - It redefines the accepted population and introduces no estimator bias. The worst-case rule already absorbs it (review §2).

## 4. Minor fixes

- **GL:824 sourcing.** v1 §1 said "Both are recorded residuals (GL:824)". That is corrected to:
  - "AR does not enforce the §21 sample minimums" is a **recorded** residual (GL:824);
  - "AR does not bind `sample_sha256` to an actual sample" is an **independent finding** from AR:38-39, not sourced from GL:824.
- **Tranche-size wording.** v1 said "per AS audit group (5 batches, ≈ 80 acts)". That is replaced by: "one tranche per AS audit group of 5 consecutive batches. 396 batches give **80 tranches** (79 of 5 and 1 of 1), so 80 human acts instead of 396."

## 5. EG-10: runbook DISPATCHED → PROPOSED (orchestration only, no code)

**[F]**
- PKG §1 goes from step 2 (DISPATCHED) to step 9 ("PROPOSED → VERIFIED"). Nothing writes PROPOSED.
- `p3b_s5_r7_orchestrate.py` never touches the state file (review §6).
- ST allows DISPATCHED → {PROPOSED, INCOMPLETE, FAILED} and PROPOSED → {VERIFIED, FAILED} (ST:46-47). PROPOSED needs no evidence (ST:59).
- **Consequence:** step 9 would be refused for every R7 batch. This blocks the canary, independently of EG-9.

**[D] Corrected runbook rows.** Insert a new step 7b after step 7 (freeze-final) and before step 8 (verify), and restate step 9:

| # | Step | Actor | Output | Stop condition |
|---|---|---|---|---|
| **7b** | `python3 -B scripts/p3b_s5_state.py transition B PROPOSED --reason "assembly + freeze-final complete; WITNESS-DIGESTS.json sha256 <h>"` | tool | state (DISPATCHED → PROPOSED) | refused transition → stop. If freeze-final or the archive digests failed, write `transition B FAILED --reason "<cause>"` instead (from DISPATCHED), or `transition B INCOMPLETE --reason "<cause>"` for an interrupted dispatch (W1) |
| 9 | `python3 -B scripts/p3b_s5_state.py transition B VERIFIED --evidence audit-p3b/S5-VERIFY-<B>-R7.json` on BATCH-PASS; otherwise `transition B FAILED --reason "<BATCH-FAIL tags>"`. For BATCH-UNDETERMINED: no transition; restore the archive and re-verify (EG-6 class U) | tool | state | — |

The steps after 9 (EG-9 steps 10–12) follow as in v1 §4.

## 6. Human decisions (updated; consolidatable into the v2.8 act as separable part (g))

1. **H-EG9-1:** option (a). §21 and AUDITED apply to R7 unchanged in the contract. Recorded as a governance ruling; no rebind.
2. **H-EG9-2:** the ruling's rules live in a G-LOG ruling (recommended), or in v2.8 addendum text (which then shares the rebind).
3. **H-EG9-3:** H-06 per enumerated tranche **with mechanical binding** (§1; recommended: 80 tranches, one per audit group). The alternatives are 396 individual acts, or unbound tranches accepted explicitly as a documentary-only control.
4. **H-EG9-4:** whether AR enforces the §21 sample coverage minimums (E9-06).
5. **H-EG9-5:** authorize the engineering, tests first:
   - AR (E9-01…E9-12);
   - AC `--tranche` (T-01…T-13).
   Timing: decide with the v2.8 act; implement before the first R7 §21 audit.
6. **H-EG9-6:** AUDIT-UPHELD at R7 produces an append-only correction record under `audit-p3b/`. The assembly and the estimation object stay the verified bytes (§0).
7. **H-EG10:** approve runbook step 7b and the restated step 9 (§5). This is needed **before the canary**, independently of H-EG9-1…6.

**Traceability:** P17 §21 items 1, 2, 4, 5 · K3 §16.5, §20 · AD §0 (:39, :63), §6 · EP §7 · EG5 §14 · EG6 §3-4 · GL:824, 906-921 · AC:31, 44-58 · ST:46-47, 59, 189-190 · AR:38-39 · EG9-REVIEW-B §2, §4-§6.
