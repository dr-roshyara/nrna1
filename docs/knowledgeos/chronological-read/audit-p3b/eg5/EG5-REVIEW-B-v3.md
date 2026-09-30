# EG-5 decision-package adversarial review, v3 / round 3 (Subagent B)

| | |
|---|---|
| Kind | Independent adversarial review of `EG5-SPEC-A-v3.md` (delta) and the consolidated `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md`. Read-only, synthetic fixtures only. |
| Method | Reread R3 §9C directly (not via A's excerpt). Reran `probes/probe_eg5_checklist.py` myself. Re-read `p3b_s5_r7_activate.rebind_contract` and `tests/test_p3b_s5_r7_contract_rebind.py` line-by-line. Cross-checked every claim in the package's §7 evidence tables against the actual probe files that are supposed to produce them. Grepped B1/Freeze-2/the execution package for any downstream consumer of `checklist_examined`/`generation_parameters.checklist`. |

## 0. Did v2's findings survive into the package, faithfully?

Checked package §3 (semantics table), §4 (statistics), §6 (review record), §8 (T-09/T-13 changes), §10 (rollback) against `EG5-REVIEW-B.md` and `EG5-REVIEW-B-v2.md` directly.

- **B-01 (EG-5b universal, blocks both canaries):** §1 states it plainly ("EG-5b: record identity blocks every batch, including the canary"). Not softened.
- **C-01 (EG-5c is an accepted-verifier residual, not tool-fixed):** §10 states "EG-5c stays a residual in both cases. Closing it in the verifier is a later, separate decision" — and §8 confirms the fix I asked for in the v2 round: *"T-13 (fixture sanitation) is DROPPED... replaced by T-13′, an EG-5c residual regression: the unchanged verifier alone still accepts evidence records on an EMPTY label, and the tool refuses them."* This is exactly what I recommended (keep the demonstration, don't silently sanitize it away) — correctly and completely carried through, not just nominally.
- **C-02 (R10 proves consistency, not independent truth):** §3's provenance row cites it directly: *"B v2 (C-02: R10 proves consistency, not independent truth)"*. Not softened or dropped.
- **C-03 (hub-EMPTY θ_D reporting):** §4 states θ_D is a rule-determinism check *"On the EMPTY stratum (census of 7) and for the hub-EMPTY label inside the HUB stratum"* — my v2 ask was exactly to extend the reporting rule to the HUB-stratum case, not just the census; done.

I found no dropped, softened, or reversed finding from either review round. This is good and worth stating plainly before the two problems below.

---

## D-01 — MATERIAL (task 2, faithfulness) — the package's §7 evidence table attributes a result to a probe file that does not contain that test

Package §7, "Record identity" table, header: *"(`probe_eg5b_options.py`; re-run by the orchestrator and by B)"*. Last row: *"Non-colliding out-of-range `n` | BATCH-PASS (so the verifier cannot catch it; R11 required)"*.

I reread `probes/probe_eg5b_options.py`'s `overflow()` function line by line. It only ever constructs the **colliding** case:

```python
def overflow(st):
    """... n = 1000*L + 1000 + 1 == first id of label L+1 (if it has records)."""
    ...
    if b is None:
        print("overflow probe: no adjacent register-bearing labels in the fixture; skipped")
        return
    r["rs_id"] = f"{R7}:{OB}:{1000 * IDX[b] + 1}"   # <- deliberately picks the id of the NEXT label
```

There is no code path in this file that produces an out-of-range `n` **without** a collision. I reran the whole probe (`main()`) myself: the single overflow-related row it prints is `(a) + k overflow into next label → BATCH-FAIL {'G-07 repeated rs_id values': 1}` — the colliding case. It never prints a non-colliding row at all.

The "Non-colliding out-of-range `n` → BATCH-PASS" claim is **true** — I independently verified it in the v2 round with my own supplementary probe (`probe_overflow_novacuous.py`: one `rs_id` set to `<B>-R7:<B>:999999`, colliding with nothing, gives `BATCH-PASS`, zero failures) — but that script was never committed to `probes/`, and the package cites the wrong file as its source. This is exactly the kind of thing task item 2 asked me to check ("any claim in the package not backed by a working paper or probe?"): as written, this specific evidence-table row is **not** backed by the probe the package names, only by an ad hoc scratch script from my own v2 review that isn't part of the committed record.

This matters because the whole justification for **R11** (the assembly's out-of-range refusal) rests on this one row — it's the proof that the verifier alone cannot catch the defect, so a governance reviewer relying on "re-run `probe_eg5b_options.py` yourself" (as the package invites) would not reproduce it.

**Fix (either is sufficient, no design change implied):**
1. Add the no-collision-overflow mutator to `probes/probe_eg5b_options.py` (a few lines, mirroring `overflow()` but targeting a value outside every label's band with nothing to collide with), so the citation is accurate and the result is reproducible from the named file; or
2. Correct §7's citation to name the actual source and add the missing mutator to the eventual `tests/test_p3b_s5_r7_assemble.py` RED list (it already should be there per my v2 recommendation to split T115).

**Needs a HUMAN decision:** no — this is a documentation/evidence-integrity fix, not a design question. Should be done before the package is presented for sign-off, since it's cheap and the package explicitly offers this table as reproducible evidence.

---

## D-02 — MATERIAL (task 4) — "the EG-4 procedure, already tested" overstates what the current test suite actually exercises for a v2.7→v2.8 transition

I reread `p3b_s5_r7_activate.rebind_contract` (scripts/p3b_s5_r7_activate.py:204-260) and `tests/test_p3b_s5_r7_contract_rebind.py` end to end.

**What the code does (confirmed, matches the package's description exactly):**
- Refuses unless `new_sha == U.ADDENDUM_SHA256` **and** the addendum file's own sha256 on disk equals that value (`:218`) — so the transition procedure's step 1 ("Freeze addendum v2.8... Update `U.ADDENDUM_SHA256`") is a hard precondition of the tool, not just a suggested order.
- Refuses if the manifest already binds the target sha (`:225-226`, "the manifest already binds the target addendum") — confirmed present, matches the task's specific question.
- Refuses unless every batch is `PREPARED` at revision 7 (`:230-232`).
- Leaves the 396 entry lines byte-identical, only the header `contract.sha256` and an appended `r7_contract_rebinds` provenance entry change (`:236-241`; verified by `test_valid_rebind_changes_only_the_contract_sha`'s `assertEqual(new_lines[1:], old_lines[1:])`).
- Appends exactly one `kind="MANIFEST-REVISION"` state-history entry (`p3b_s5_state.py:262`) — matches the package's "one MANIFEST-REVISION entry" claim exactly.

All of this matches the package's §9 description precisely, and the mechanism is written generically — nothing in `rebind_contract` hardcodes a v2.6/v2.7-specific value; it always reads the *current* `U.ADDENDUM_SHA256`/addendum file as the target. By code inspection, I agree it will generalize correctly to v2.7→v2.8.

**What "already tested" actually means today.** `test_p3b_s5_r7_contract_rebind.py`'s own docstring: *"the narrow production rebind of the R7 contract binding v2.6+AF-1 → v2.7."* Every test in the file uses `r7_setup(old_sha=OLD, revision=7)` where `OLD` is one **hardcoded** historical sha constant, and calls `rebind_contract(...)` with the default `new_sha=None → U.ADDENDUM_SHA256` (whatever is **currently** frozen in the repo, i.e. today's v2.7 value). The only test that varies the target (`test_a_target_other_than_the_frozen_addendum_is_refused`, passing `new_sha="0"*64`) is a **negative** case (expected refusal), not a positive rebind to a fresh value. There is no test in the suite today that walks the mechanism through binding a *newly computed* target sha — because v2.8 doesn't exist yet, there couldn't be.

So: the *mechanism* is tested and, by inspection, generic; but the specific claim "the EG-4 procedure, already tested" — read naturally by a governance reviewer as "this exact transition has already been exercised" — is not true yet for v2.7→v2.8. The first real exercise of that specific transition, as the package's own §9 sequences it, would be the **production** rebind itself (step 9.2), with the only prior check being the generic mechanism test on an unrelated historical sha pair.

**Fix.** Add a parametrized case (or simply a second `r7_setup`/`rebind` pair) to `tests/test_p3b_s5_r7_contract_rebind.py` that freezes a synthetic "v2.8-like" sha and rebinds from the v2.7-like state to it, and/or run an explicit staged dry run of the real v2.8 addendum sha (on a copy, per the tool's own `staging` requirement) as part of package §8's "Full suite; A/B implementation review" step, **before** step 9.2 is executed against production. This does not change the mechanism or the decision; it only closes the gap between "the class of operation is tested" and "this operation has been tried."

**Needs a HUMAN decision:** no — an implementation/test-completeness fix, foldable into §8's existing "tests first" / "implementation review" step without reopening any design question.

---

## Task 1 — checklist vacuous-examination: not vacuous, truthful under §9C as far as the verifier and the visible design docs go; one caveat on what I could not check

**Re-ran the probe myself:**

```
baseline                                       BATCH-PASS   {}
(i) in_checklist + examined 1..23              BATCH-PASS   {}
(i) + no EMPTY register/gap records            BATCH-PASS   {}
in_checklist, checklist_examined absent        BATCH-FAIL   {'G-09 ...: in_checklist but checklist_examined is not the full 1..23': 1}
in_checklist, examined 1..22                   BATCH-FAIL   {'G-09 ...': 1}
(ii) in_checklist, EMPTY object not assembled  BATCH-FAIL   {'G-02 object labels differ...': 1, 'R7-R ...: no object in the assembled batch': 1}
not in_checklist but examined 1..23            BATCH-FAIL   {'SCHEMA ...: checklist_examined on a label not in the checklist': 1}
```

Exact match with the spec v3 §2(i) table. **Non-vacuous:** the probe's `show()` counts *every* failure in the report (not a filtered subset), so a PASS genuinely means nothing else in the batch broke from mutating `slice["in_checklist"]` — I checked whether any other verifier code reads `in_checklist`/`checklist` and confirmed by grep (`p3b_s5_verify.py`, `p3b_s5_r7_verify.py`, `p3b_s5_r7_universe.py`): the only reference anywhere is `V:504-509` (G-09). This matches A's own grep claim and my independent one agrees.

**Re-read R3 §9C directly** (not from A's excerpt): *"The checklist is a cognitive control mechanism, not a documentation obligation. It exists so that the researcher asks the right questions... No object is required to yield positive answers, and a 'no' is never recorded. Only positive findings are recorded, as research records or timeline content."* This text supports A's reading: the field records that the 23 questions were posed, never that they were answered positively, and the contract already anticipates the "examined, found nothing" case as a normal, valid outcome for any label (not just EMPTY ones) — a real agent with a rich source and nothing to report produces the byte-identical `checklist_examined=[1..23]`, zero register records. The EMPTY case is a **degenerate instance of an already-normal outcome**, not a new category the field wasn't built to express. I agree with A's conclusion that this is truthful under §9C, not an overstated cognitive act, given the field's stated scope.

**Downstream-consumer check.** I grepped `B1-EXPERIMENT-DESIGN-FREEZE-PROPOSAL.md`, `FREEZE-2-PROPOSAL.md`, and `S5-EXECUTION-PACKAGE-DRAFT.md` for "checklist": **zero hits** outside an unrelated "Activation checklist" heading. Nothing in the statistical design or audit protocol, as far as I'm permitted to read, treats `checklist_examined` or the checklist population as a stats-frame attribute, domain, or estimand input — so I found no gate or downstream consumer that would be misled by a vacuous examination looking identical to a real one.

**Caveat (not a finding against A, a scope limit on my own check):** `EP-01` (`audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`), which B1 names as its own base for the decision-field list and the audit protocol, is **not** in my permitted read set for this review, so I could not check it directly for a checklist-keyed consumer. Given the grep result across everything I *could* read, I think this is very likely clean, but I'd rather say so explicitly than imply I verified EP-01 too. Recommend one line of confirmation against EP-01 before treating "no downstream consumer" as fully closed — low urgency, does not block approval.

---

## Task 3 — bundling: reasonable, but the package should say explicitly what is and isn't separable

The package bundles at least three logically distinct decisions into one APPROVE/REJECT/REVISE act: (1) §2.6 record identity (forced by EG-5b, effectively non-discretionary), (2) §2.7 EMPTY-object construction + provenance interpretation (a real design choice among rejected alternatives), (3) the in-checklist vacuous-examination rule (a separate §9C interpretation, only needed for 2 of 396 batches per the preflight). All three share one rebind cost, which is a legitimate reason to bundle them — I don't think they should be split into three separate governance acts given that constraint, and §0 already allows the human to "REVISE" a named section rather than reject the whole thing. What's missing is a one-line statement that these three are logically independent and that naming one section for revision does not require re-litigating the others. Minor, recommended, not blocking.

**One scope-boundary note (task 3, "anything missing"):** §13 lists "EG-3 (hub batches)" as explicitly **not** covered by this approval, while §2.7 simultaneously prescribes hub-EMPTY-specific behavior (the LOAD escalation per hit-bearing dimension). I don't have EG-3's own definition in my permitted read set to confirm there's no overlap, but the juxtaposition is worth one clarifying sentence in the package (e.g., "the hub-EMPTY object-construction rule in §2.7 is EG-5-scoped and does not preempt or substitute for EG-3's broader hub-batch decisions") so a reviewer doesn't read the out-of-scope line as contradicting the in-scope text just above it. Minor, not blocking.

**Combined case, for robustness (task 1/5, a note not a finding):** per the package's own disclosed preflight (§1: hub label OB0386 L05; in-checklist labels OB0110 L04, OB0225 L03 — three distinct labels), no current EMPTY label is both hub and in-checklist, so the combined case isn't live in this population. §2.7's two relevant clauses (LOAD escalation for hub; `checklist_examined` for in-checklist) are written as independent, additive rules with no stated conflict, and I see no reason they wouldn't compose correctly if a future batch did combine them. Recommend one golden test for the combined case as good practice before the tool ships generally (not required for this approval, since it doesn't affect the canary or the currently-known population).

---

## Task 4, second half — re-rendering: plans/slices/I(run)/views need no re-rendering; only future `prepare` calls render prompts, and none are committed yet

§2.6/§2.7 touch only the *values agents write inside their own records* and the *tool/prompt* that constructs the assembly — nothing in either section touches `plan_derive`, slice hashing, SLICE-VIEW rendering, or I(run) manifest construction (confirmed again this round; consistent with every prior round's reading of `p3b_s5_r7_universe.py`). `rebind_contract` itself guarantees the 396 entry lines (which carry `r7_plan_sha256`/`slice_sha256`) stay byte-identical across the rebind, which is correct and required given §2.6/§2.7 don't touch that content.

The remaining question is whether any **prompt** has already been rendered and committed under the old text. Per `audit-p3b/20260929_S5-CANARY-PRECHECK.json`, both canary batches show `"ledger_R7_dirs_existing": 0` — nothing has been PREPARED to a production path yet for either batch. So in the current state of the program, there is nothing already-committed that needs re-rendering; the next `prepare` call for any batch, run after the code change ships, will pick up the new `render_prompt` (with the RECORD IDS block) automatically. I'd recommend the package say this explicitly in §9 (one sentence, citing the precheck's `ledger_R7_dirs_existing: 0`) rather than leaving it implicit, since a reviewer without that cross-reference might reasonably ask "what about prompts already handed to agents?" — the answer is "none exist," but the package doesn't currently say so. Minor, not blocking.

---

## Task 5 — scientific invariants: no hidden change found, consistent across all three rounds

§12's list (corpus boundary, historical reconstruction, Freeze 1, Freeze 2, population, labels, strata, sample, seed, θ_D/θ_A/θ_E definitions, H1–H5, Model 0, competing models, estimands, multiple-testing rules) matches everything I independently verified against B1 v2 and Freeze-2 across the v1 and v2 review rounds (frame `d0c972bf…`, the EMPTY census row with `U_h at d=0 = 0`, stratum precedence unchanged). §4's "interpretation recorded, not changed" framing is accurate: nothing in the package touches a frozen number, only how the EMPTY/hub-EMPTY/checklist-vacuous results should be *read* once produced. I found no hidden change.

---

## Verdict

**PACKAGE-NEEDS-REVISION** — two MATERIAL, cheap-to-fix evidence/testing gaps, no design-level problem found.

**Exact edits required before this goes to the human:**
1. **§7 evidence table (D-01):** either extend `probes/probe_eg5b_options.py` with the non-colliding out-of-range-`n` mutator so the cited file actually reproduces the "verifier cannot catch it" row, or correct the citation and route that specific test into the committed RED list (T115-split, as I asked for in the v2 round).
2. **§8/§9 (D-02):** add a test (or at minimum a documented staged dry run) that exercises `rebind_contract` from a v2.7-bound state to a *freshly computed* v2.8-like target sha, before production step 9.2 runs for real; soften "the EG-4 procedure, already tested" to state precisely what is tested today (the generic mechanism, on the historical v2.6→v2.7 pair) versus what will be exercised for the first time in production.

**Recommended, not blocking:** one sentence on bundling-separability (task 3), one sentence resolving the EG-3/§2.7 scope juxtaposition, one sentence in §9 noting no batch has committed prepare-time artifacts yet (`ledger_R7_dirs_existing: 0`), and a note that the checklist-consumer check (task 1) has not been cross-checked against EP-01 specifically.

Everything else — the v1/v2 findings' faithful carry-through, the checklist probe's non-vacuous truthfulness, the rebind tool's refusal logic, and the scientific-invariants list — checks out. Once D-01 and D-02 are fixed, I would expect this package to be ready.
