# Revision 5: MATERIAL-finding repair plan (PROPOSAL, for human approval)

| | |
|---|---|
| **Kind** | EP-01 planning artifact. ⚠ authority: generated. **This document authorizes nothing and repairs nothing.** |
| **Input** | the ONE independent audit, `audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md` (verdict NEEDS-REVISION), with its scripts in `audit-p3b/r5-audit/` |
| **Code inspected (read-only)** | `scripts/p3b_s5_r5.py` (9a466d64…) · `scripts/p3b_s5_r5_verify.py` (1b3092ce…) · `scripts/p3b_s5_verify.py` (d3804d21…) · `scripts/p3b_read_source.py` (3ca0197c…) · the addendum (86caedf6…) · the runbook (fb17c235…) · decomposition-pilot contract §1.3 |
| **Governance state** | revision 5 **NOT ACTIVATED** (manifest contract revision 3) · S5 execution **NOT AUTHORIZED** · H-19 **SEALED** · S5c **PROHIBITED** · audit **NOT resolved** |
| **Companions** | `20260926_EP-01-STATISTICAL-SPECIFICATION.md` (statistics) · `20260926_S5-ACTIVATION-GATE.md` (the sequence to S5 authorization) |

The auditor's finding texts are **not restated or edited here**; the audit report §2 is the record. This plan cites findings by their exact IDs.

---

## 0. Answer to the central question

> *Why could the complete verifier produce a false PASS although each component appeared valid?*

**Because every revision-5 check validates a declaration against another part of the same declaration, inside a view that the declaration itself selects.** Three structural properties combine:

1. **Declared, not derived (W1).** Plan facts come from `R5-BATCH.json`, which the orchestrator writes: sizes, packing order, plan hash, stage timestamps, the run set and each run's label. The verifier never derives them independently from an authoritative artifact. It only checks that they are consistent with each other, for example that the units equal `partition()` of the **declared** sizes and order.
2. **Closed view, open world (W2).** The verifier selects its evidence through the declaration: `blog` = entries whose `run_id` is a run declared in the batch file (`p3b_s5_r5_verify.py:197`). Everything else is demoted to an alert, *"READ-LOG … ignored"* (`p3b_s5_verify.py:411–414`). Evidence that contradicts the declaration is therefore never tested. PASS means "no violation found **inside the declared view**", not "every required property positively holds over the complete evidence".
3. **No claim → evidence chain (W3).** File-reading records carry only `reading_state`, `ack_tokens` and `pair_evidence_checks`, and no facts. A synthesized object claim therefore has nothing to be checked against, so every content rule becomes vacuously satisfied. The rules that do exist are also applied to **subsets** of the object: R5-06 covers only the `CHANGES-` prefix, and R5-08 skips the checks on EMPTY objects.

**Each component is locally correct under its own inputs.** The false PASS is compositional. W1 lets an attacker choose the inputs, W2 hides the evidence that would contradict them, and W3 means the payload (unsupported claims) cannot be detected even when the cover holds.

**The invariant class that closes this** (§4): *every predicate that PASS depends on is evaluated over evidence the verifier obtains itself, from an authoritative source, over a completely enumerated universe. Anything outside the plan is a FAIL, never an alert. Every DECOMPOSED layer-A claim is linked to a record fact that is itself checked against the source bytes.*

---

## 1. Step 1: the audit findings reconstructed

Class key: **M** mechanical · **P** protocol · **V** provenance · **E** epistemic · **C** compositional. The layer is where the defect lives, not where it shows.

| Finding | Failure mechanism (as found in the code) | Layer | Class | False-PASS contribution | Required repair | Required test |
|---|---|---|---|---|---|---|
| **R5-01** | Records have no fact schema. `label_failures` / `r5_checks` check records only for count, `reading_state` and acks. No predicate links an object claim to a record (`p3b_s5_r5_verify.py:129–159`) | contract (addendum item 10 / records) + verifier | **E**, V | **The payload.** Synthesis can invent a change point, FOUND, disposition or R2 edge (J1, FP-1, FP-6) | a record fact schema (pilot §1.3), plus a claim map from DECOMPOSED claims to record facts, plus containment and quote checks | J1 and FP-1 must FAIL. A claim with an unknown, non-WHOLE or quote-failing reference must FAIL. The valid fixture must PASS |
| **R5-02** | `units_validated_utc < synthesis_dispatched_utc` is compared as Python strings on self-declared values (`p3b_s5_r5_verify.py:109–112`). Never bounded by the read log's `utc` | verifier | **V**, M | **Cover.** R19 can be violated: synthesis before validation, and unit reads after dispatch (C1–C4, FP-6) | strict RFC 3339 `Z` parsing (else FAIL), and `max(utc of unit-run log entries) ≤ units_validated_utc < synthesis_dispatched_utc`. Any unit-run entry at or after dispatch FAILS | C1–C4 must FAIL; offsets, non-ISO values and late unit reads must each FAIL; the valid ordering must PASS |
| **R5-03** | Only runs declared in `R5-BATCH.json` are examined (`:197`). Other R5-grammar runs and foreign-batch entries are filtered into `foreign` and only alerted (`p3b_s5_verify.py:411–414`). The per-run `READ-LOG.jsonl` files are never read; the assembled union is trusted | verifier (+ reader, which accepts any R5-grammar run: `p3b_read_source.py:135–149`) | **C**, V | **Cover.** A synthesis read hidden under a unit run id, or under an undeclared run (E1–E3, FP-4, FP-6) | closed-world enumeration of **every** per-run log whose run id has the batch prefix or whose `batch_id` is the batch. Any entry of a run outside the frozen plan FAILS. The assembled log must equal the union of the per-run logs, by hash | E1–E3 and FP-4 must FAIL; an undeclared `L02U09` must FAIL; an assembled log missing one per-run entry must FAIL |
| **R5-04** | `coverage_by_label` groups by the agent-supplied `working_label` (`:66`). Run → label is not bound, and `L##` is not compared with the label (R5-18). Two labels can declare one run (E6) | verifier | **V**, C | Coverage for label B can be manufactured by label A's run (D2), so WHOLE-FILE and FOUND rest on another label's reading | ownership comes from the frozen plan (run → label): count an entry for L only if its run belongs to L; require `entry.working_label == plan_label(run)`; run ids unique across labels; `L##` = the label's plan index | D2 and E6 must FAIL; a mislabelled `--label` must FAIL; the valid fixture must PASS |
| **R5-05** | `plan_sha256` is never read. `sizes` are never compared with the content or with the manifest's blob sizes. `packing_order` is never recomputed with `packing_key` from `source_meta` (`structure_failures`, `:72–118`) | verifier | **V**, M | **Enabler.** An inflated size switches SINGLE to DECOMPOSED, which opens the attack surface of R5-01/02/03 on a label that should have had one agent (B1–B3, FP-6: 11.7 MB declared, 117 KB real) | sizes derived from `all_blob_sizes` (manifest metadata) and cross-checked with fetched content lengths; `packing_order` recomputed; `plan_sha256` = sha256 of the frozen plan file whose hash is recorded in the activation act; plan contents = R5-BATCH per label | B1, B1b, B2 and B3 must FAIL; **the committed "valid" fixture (1.0 MB declared, 20 KB real) must be corrected**, since it currently encodes the defect |
| **R5-06** | `reading_state_violations` tests only `startswith("CHANGES-")` (`p3b_s5_r5.py:328–331`). The historical coverage check at `p3b_s5_verify.py:850` uses `READ_CHANGE_POINTS` but ignores `reading_state` | module rule | **P**, M | a partial read supports NARROWS/CONTRADICTS/RETRACTS (D1); one-directional rule broken | use `READ_CHANGE_POINTS` (NARROWS, CHANGES-DEFINITION/TYPE/TERM, CONTRADICTS, RETRACTS) in the item-10 rule | D1 must FAIL for each of NARROWS/CONTRADICTS/RETRACTS; D1b stays FAIL; non-change values unaffected |
| **R5-07** | S1 `quote` is checked only for being present (`p3b_s5_r5.py:441–442`). `p3b_s5_quotes.py` never sees R5-BATCH records | module rule + quote checker scope | **E**, V | a dishonest YES passes (F1, FP-5), so a frozen pair verdict is "confirmed" by a fabricated quote | quote-check every S1 quote against the content bytes the verifier already fetches (the same normalization as `p3b_s5_quotes.check`) | F1 and FP-5 must FAIL; a genuine quote must PASS; the whitespace-normalized match stays reported as in the quote checker |
| **R5-08** | `r5_checks` returns early for EMPTY with only `empty_label_violations` (`p3b_s5_r5.py:550–551`), skipping S3, edge classes and reading state | module rule | **P**, C | content with no required file behind it (H2): an S-id range, an unclassified edge, a foreign timeline point | EMPTY runs every non-vacuous check (S3, edges, reading state with an empty state map) **plus** a structural rule: no timeline points, no dependency edges, no S-id citations | H2 must FAIL; the valid EMPTY object must PASS |

### Class summary

| Class | Findings |
|---|---|
| Provenance (the evidence is self-declared or unbound) | R5-02, R5-04, R5-05 (+ R5-03, R5-01) |
| Compositional (a filtered view, or a skipped path) | R5-03, R5-08 |
| Epistemic (a claim is not linked to evidence) | R5-01, R5-07 |
| Protocol (a rule predicate narrower than the rule text) | R5-06, R5-08 |
| Mechanical (a type error) | R5-02 (string comparison), R5-05, R5-06 |

---

## 2. Step 2: causal dependencies

**The eight findings are not independent.** They are manifestations of three design weaknesses (§0), and they interact in a specific order on the false-PASS path.

```
            W1 DECLARED-NOT-DERIVED                W2 CLOSED VIEW                 W3 NO CLAIM→EVIDENCE
          ┌───────────┬───────────┐           ┌──────────────┐           ┌──────────┬──────────┬──────────┐
          R5-05     R5-02       R5-04 ◄─share─► R5-03                     R5-01    R5-07    R5-06    R5-08
       (sizes,     (stamps)   (run→label)     (undeclared runs,           (payload) (S1      (rule     (EMPTY
        order,        │           │            union unverified)             ▲      quote)   subset)   skip)
        plan hash)    │           │                  │                       │
            │         │   R5-02's repair NEEDS R5-03's repair:               │
            │         │   log utc is only authoritative once the              │
            │         │   log itself is complete and per-run   ──────────────┤
            ▼         ▼                                                      │
      wrong path   R19 ordering        foreign reads            ───► all cover ──► undetectable payload
     (SINGLE→DEC)  unenforced          invisible                                   ═══► COMPOSITE FALSE PASS (FP-6)
```

| Relation | Findings | Nature |
|---|---|---|
| **Enabler → attack surface** | R5-05 → {R5-01, R5-02, R5-03} | a declared size selects the DECOMPOSED path; only that path has units, synthesis and R19, so only that path exposes the other three |
| **Repair dependency** | R5-02 needs R5-03 | the read log's `utc` can bound the declared stamps only if the verifier reads the **complete, per-run** log. Against today's filtered, assembled log, a hidden late read is invisible |
| **Shared root** | R5-03 ↔ R5-04 (+ R5-18) | the run → label → files map is not frozen and enforced. R5-03 is its read-set side, R5-04 its ownership side. One repair (plan-bound run identity) closes both |
| **Shared root** | R5-01 ↔ R5-07 (+ R5-10) | record content is never checked against the source bytes. R5-07 is its S1 instance, R5-01 its general instance |
| **Overlapping** | R5-06 ↔ R5-14 | the item-10 rule is incomplete: R5-06 on the predicate side, R5-14 (MINOR) on the object side (the unread state vanishes) |
| **Overlapping** | R5-08 ↔ R5-09 | the S3 coverage gap: EMPTY skipped (R5-08), regex variants (R5-09, MINOR) |
| **Independent** | R5-06 alone | a pure predicate fix; not needed for FP-6 (the audit shows it at rule level) |

**Consequence for the repair order (§5):** repair W2 before W1. Plan-bound run identity, together with closed-world log enumeration (R5-03 + R5-04), makes the read log authoritative. Only then can R5-02 and R5-05 be derived from it. W3 (R5-01, R5-07) is independent of the other two and is the largest single change.

---

## 3. Step 3: the false-PASS path reconstructed

**The smallest composite** consistent with FP-6 is one label with 3 files (≈ 40 KB real content), so SINGLE. The attacker controls only what the orchestrator writes, **never** the reader, the resolver or the slice.

| # | Stage | What is written | Check that runs | Result today | Why |
|---|---|---|---|---|---|
| 0 | Input | the slice gives R(L) = {S1, S2, S3} with rows {S1}. Hash-verified | SLICES (historical) | PASS | correct and authoritative |
| 1 | **Plan** | R5-BATCH declares `sizes` = {S1: 400 KB, S2: 300 KB, S3: 100 KB}, `path: DECOMPOSED`, 2 units | `structure_failures`: path = `dispatch_path(Σ declared)` | **PASS** | ◆ **the invalid state becomes possible here (R5-05).** The path is recomputed from declared sizes only |
| 2 | Partition | units = `partition()` of the declared sizes and order | units = recomputed partition | PASS | internally consistent with the lie |
| 3 | Unit reads | U01/U02 read their files in byte mode; the reader logs them with utc T1…T2 | byte coverage, mode, acks | PASS | the reads really happened |
| 4 | Records | records = `{source_id, reading_state: WHOLE-FILE, ack_tokens, pair_evidence_checks: []}` | one record per file; WHOLE ⇔ coverage | PASS | records carry no facts (W3) |
| 5 | Stages | `units_validated_utc` = "2026-10-01T10:00:00Z"; `synthesis_dispatched_utc` = "2026-10-01T11:00:00+05:00", which is 06:00Z, 4 h **before** validation in real time | string `<` | **PASS** | ◆ **R19 broken (R5-02)**: "…T10…" < "…T11…" as strings, although the real-time order is reversed |
| 6 | Hidden read | the synthesis agent reads S2 using run id `…L01U02` after dispatch; the reader logs it faithfully under U02 | exposure (permitted set of U02 includes S2) | **PASS** | ◆ **R5-03**: attributed to a unit; the verifier doesn't compare its utc with dispatch |
| 7 | Synthesis | the object adds `CHANGES-DEFINITION` on S2 (INFERENCE) and a FOUND supplied by S3 | `r5_checks`: reading state (S2, S3 WHOLE ✓), S2, S3 lint | **PASS** | ◆ **R5-01**: no rule asks which record states the change |
| 8 | Assembly | `OB####-R5/` = objects + a hand-assembled union log + R5-BATCH | none (runbook step 13 is documentation-only) | — | the union's completeness is unverified |
| 9 | **Verifier** | `p3b_s5_verify.verify` | all historical gates + gate R5 | **PASS** | every conjunct is true **inside the declared view** |
| 10 | Reality | a 40 KB label that should have had one agent: its change point is invented, its synthesis read source bytes, and R19 is violated | — | **INVALID** | — |

*Reading the table.*
- **Step 5:** the timestamps are illustrative. They reproduce the mechanism of FP-6 and C1, where a `+05:00` offset makes the string order disagree with the real-time order.
- **Where the invalid state starts:** the first ◆ is at step 1. The later ◆s are possible only because step 1 switched the label onto the DECOMPOSED path.

---

## 4. Step 4: the compositional invariant

**Principle.** A batch PASSes only if each predicate below is **positively established**. Each is evaluated over evidence that the verifier **obtains itself** from an authoritative source (the hash-verified slice, manifest metadata, resolver bytes, reader-written per-run logs, or the frozen plan whose hash is recorded in the activation act). The universe is **completely enumerated**, and any element outside the plan is a FAIL.

```
FINAL_VALID(batch) ≡ HIST_VALID(batch)                      -- all historical gates, unchanged
                   ∧ PLAN_BOUND(batch)
                   ∧ LOG_CLOSED(batch)
                   ∧ ∀ L ∈ labels(slices):  PATH_DERIVED(L) ∧ RUN_OWNED(L) ∧ R19_ORDERED(L)
                                          ∧ COVERAGE_OWNED(L) ∧ RECORDS_GROUNDED(L)
                                          ∧ CLAIMS_LINKED(L) ∧ RULES_TOTAL(L) ∧ S1(L) ∧ S2(L) ∧ S3(L)
```

| Predicate | Meaning | Authoritative artifact | Checked by (proposed) | Local / compositional | Closes |
|---|---|---|---|---|---|
| **PLAN_BOUND** | the plan used equals the frozen activation plan: `sha256(canonical plan) = plan_sha256` = the hash in the activation record, and each label's `path/files/rows/sizes/packing_order/units/runs` in R5-BATCH = the plan's | frozen production plan file + activation record | `verify_r5` (new `plan_failures`) | compositional (binds R5-BATCH to the activation act) | R5-05 (plan hash) |
| **PATH_DERIVED** | `sizes[s] = all_blob_sizes(manifest)[s]` for every s ∈ R(L), and `= len(content[s])` wherever content is fetched; `packing_order = sort(rows, packing_key(s, source_meta))`; `path = dispatch_path(|R(L)|, Σ sizes)`; units = `partition()` | manifest metadata, resolver bytes, `files_meta` | `structure_failures` | local, **against derived values** | R5-05 |
| **LOG_CLOSED** | the verifier reads **every** `ledger-p3b-r2/<run>/READ-LOG.jsonl` whose run has the batch prefix **or** any entry with `batch_id = batch`. Every such run ∈ plan runs, else FAIL. `run_id` prefix = `batch_id`, else FAIL. Assembled log = union of per-run logs (multiset equality by line hash) | reader-written per-run logs | new `log_universe_failures` | **compositional (closed world)** | R5-03 |
| **RUN_OWNED(L)** | the run → label map comes from the plan; run ids are unique across labels; `L##` = the plan index of L; every entry of a run r has `working_label = plan_label(r)` | frozen plan + per-run logs | `structure_failures` + `log_universe_failures` | compositional | R5-04, R5-18, R5-03(b) |
| **R19_ORDERED(L)** | the stamps parse as RFC 3339 UTC (`Z`), else FAIL; `max(utc of all entries of L's unit runs) ≤ units_validated_utc < synthesis_dispatched_utc`; **no** unit-run entry has utc ≥ `synthesis_dispatched_utc`; the synthesis run has no entry at all | per-run logs (reader clock) | `structure_failures` | compositional (stamps × log) | R5-02, R5-03(a) |
| **COVERAGE_OWNED(L)** | byte coverage of s for L is computed only from entries of runs owned by L (plan), in byte mode; WHOLE-FILE ⇔ complete owned coverage | per-run logs + resolver bytes | `coverage_by_label` (re-keyed) | local | R5-04 |
| **RECORDS_GROUNDED(L)** | every record fact (`timeline_facts`, `birth_candidates`, `omq14_content`, `absence_evidence`, `dependencies`) and every S1 check has a `quote` that occurs in the content of its `source_id` (exact or whitespace-normalized, as in `p3b_s5_quotes`); facts only in WHOLE-FILE records | resolver bytes | new `record_quote_failures` (reuses `p3b_s5_quotes` matching) | local | R5-07, R5-01 (part), R5-10 (if repaired) |
| **CLAIMS_LINKED(L)** (DECOMPOSED) | each layer-A claim in the object (every timeline point, every births entry citing an S-id, every FOUND/GENUINELY-UNDEFINED resolution, every `dependency_edge`, every OMQ14 item) has an entry in a claim map → ≥ 1 `(run, source_id, fact_kind, index)`. The reference resolves to a grounded record fact of a WHOLE-FILE file owned by L; the fact kind is compatible (change value = fact `change_candidate` unless the point is INFERENCE; birth kind = candidate kind; FOUND ⇒ `absence_evidence.finding = DEFINES` for that dimension; R2 edge ⇒ a `dependencies` fact) | claim map + records | new `claim_link_failures` | **compositional (object × records)** | R5-01 |
| **RULES_TOTAL(L)** | each object-level rule is applied to **every** object of **every** path, and every rule predicate is the full value set of its rule text (`READ_CHANGE_POINTS`); for EMPTY: no timeline points, no edges, no S-id citations | the object | `r5_checks` (no early return), `reading_state_violations` | local | R5-06, R5-08 |
| S1 / S2 / S3 | as today (S1 now with grounded quotes; S3 now on EMPTY) | — | unchanged functions | local | — |

**What the invariant does *not* establish (a residual stated honestly):**
- **CLAIMS_LINKED is citation and type containment, not semantic entailment.** A claim can cite a genuine fact that does not actually imply it. That is judged by the probability audit (EP-01), never by the machine.
- **The stamps remain orchestrator-declared.** They are now bounded by the reader clock on one side only (they must be later than all unit reads). A dispatch stamp written *earlier* than the true dispatch, with no synthesis reads, is undetectable. Synthesis reads are prohibited and closed-world, so this residual cannot carry source bytes into synthesis. *Optional hardening, not proposed as MATERIAL: a tool-written stage log using the reader's clock.*
- **The reader and the resolver remain trusted components.** This is unchanged, and the audit did not attack them.

---

## 5. Step 5: repair specification per MATERIAL finding

**Proposed placement: a pre-activation amendment of revision 5, not a revision 6.** Revision 5 is not activated; no production manifest carries its contract hash. **Q-1 below asks the human to confirm this.** Every change lives inside `p3b_s5_r5*.py`, the revision-5 gate of `p3b_s5_verify.py`, or the revision-5 addendum and runbook.

**Proposed slice order:** W2 (R5-03 + R5-04) → W1 (R5-05, R5-02) → rule fixes (R5-06, R5-08) → W3 (R5-07, then R5-01).

| # | Finding | Code / document affected | Smallest safe change | Invariant | Test | Regression | Rev 1–4 byte-identical? | Manifest untouched? | Corpus access? |
|---|---|---|---|---|---|---|---|---|---|
| 1 | **R5-03** | `p3b_s5_r5_verify.py` (`verify_r5`, new `log_universe_failures`); `p3b_s5_verify.py` rev-5 branch l.401–414 only | enumerate the per-run logs by batch prefix and `batch_id`; FAIL on a run outside the plan and on a prefix ≠ `batch_id`; verify the assembled log = the union. In the rev-5 branch, `foreign` becomes a FAIL instead of an alert. *(A reader-side refusal of non-plan runs is optional defense in depth, **not** proposed: it would make the reader depend on the plan)* | LOG_CLOSED | E1–E3, FP-4; missing-union-line; the valid fixture | the 37 r5-verify tests; `full_path.py` BASE PASS | **yes** (the rev-5 branch only; the non-rev5 `foreign` path unchanged) | yes | no (metadata logs; synthetic fixtures) |
| 2 | **R5-04** (+ R5-18) | `p3b_s5_r5_verify.py` (`coverage_by_label`, `structure_failures`) | key coverage by the run owner from the plan; require `working_label == plan_label(run)`, unique run ids, and `L##` = plan index | RUN_OWNED, COVERAGE_OWNED | D2, E6; `--label` spoof | as above | yes | yes | no |
| 3 | **R5-05** | `p3b_s5_r5_verify.py` (`structure_failures`, new `plan_failures`); **the test fixture** in `test_p3b_s5_r5_verify.py` | derive sizes from `all_blob_sizes` over the manifest rows of the batch (metadata), and check `len(content)` where fetched; recompute `packing_order` via `packing_key(sid, source_meta)`; check `plan_sha256` against the frozen plan and the activation record (the plan file does not exist until activation, so the verifier FAILs with "no frozen plan" before activation, which is correct) | PLAN_BOUND, PATH_DERIVED | B1, B1b, B2, B3; **correct the committed fixture's declared sizes** | the fixture correction is a test-data change and must be listed in the implementation record | yes | yes (the plan is a *new* activation artifact, not the manifest) | no (manifest metadata; synthetic bytes in tests) |
| 4 | **R5-02** | `p3b_s5_r5_verify.py` (`structure_failures`) | strict parse (`%Y-%m-%dT%H:%M:%SZ`, the reader's own format; else FAIL); bound the stamps by the owned-run log `utc`; FAIL on a unit entry ≥ dispatch; FAIL on any synthesis-run entry | R19_ORDERED | C1–C4; late read; synthesis entry | as above | yes | yes | no |
| 5 | **R5-06** | `p3b_s5_r5.py` (`reading_state_violations`) | test `change_vs_previous in READ_CHANGE_POINTS` (import or duplicate the constant from `p3b_s5_verify.py:136`, with a test that both sets are equal) | RULES_TOTAL | D1 × {NARROWS, CONTRADICTS, RETRACTS}; D1b | 42 r5 tests | yes (the module is never loaded for rev < 5; import-guard test) | yes | no |
| 6 | **R5-08** | `p3b_s5_r5.py` (`r5_checks`, `empty_label_violations`); **addendum item 16** (+ one sentence) | no early return: EMPTY runs `empty_label_violations` + S3 + edge classes + reading state with `states = {}`; add "no timeline points, no dependency edges, no S-id citations" to item 16 (a clarification of R17's "nothing supports it", flagged for the human under Q-3) | RULES_TOTAL | H2; the valid EMPTY object | as above | yes | yes | no |
| 7 | **R5-07** | `p3b_s5_r5_verify.py` (new `record_quote_failures`, which reuses the matching of `p3b_s5_quotes`) | quote-check every S1 quote against the content already fetched by `coverage_by_label` | RECORDS_GROUNDED | F1, FP-5; a genuine quote; whitespace normalization | as above; `p3b_s5_quotes` tests unchanged | yes | yes | checker reads through the resolver at verification time only (as today) |
| 8 | **R5-01** | **addendum item 10 / a new record section** (the fact schema); **runbook steps 6 and 11** (synthesis writes a claim map); `p3b_s5_r5_verify.py` (new `claim_link_failures`); `R5-BATCH.json` format (a per-label `claims` map) | (a) adopt the pilot §1.3 fact lists **verbatim** as the file-reading record's fact fields (`timeline_facts`, `birth_candidates`, `omq14_content`, `absence_evidence`, `dependencies`, each with `anchor` and `quote`); (b) the synthesis writes a **side-car claim map** in R5-BATCH, which leaves schema E unchanged and so leaves the historical checks untouched; (c) the verifier checks existence, ownership, WHOLE-FILE, grounded quote and kind compatibility. **SINGLE labels:** the object's own quotes are already checked by the production quote checker; no claim map is proposed (Q-2) | CLAIMS_LINKED, RECORDS_GROUNDED | J1, FP-1 FAIL; a claim with an unknown reference, a non-WHOLE or foreign-label reference, a failing quote or a kind mismatch each FAIL; the valid fixture (now with facts) PASS | as above; the OB0018 read-only S2/S3 regression (42 r5 tests) unchanged | yes | yes | no (synthetic) |

**The combined regression gate for the repair slice** (all must hold before the slice is presented for acceptance):
1. `full_path.py` and `attacks.py` from `audit-p3b/r5-audit/` are **re-run unmodified** (their hashes as recorded). Every FP-x and every R5-01…R5-08 attack becomes FAIL, and BASE stays PASS. These are the auditor's scripts, so they serve as the acceptance oracle and are **not edited**.
2. All suites stay green (820 tests/checks before the repair, plus the new ones).
3. Revisions none/1–4: byte-identical reports on OB9002, OB9003 and OB9101 against the pre-repair verifier (the existing isolation test).
4. `prepare --check` IDENTICAL; H-19 seal SEALED and grep 0 violations; ledger fingerprint `4fde15fc…05e5`, `P3B-STATE.json` `db52ac7a…c21a` and manifest contract revision **3**, all unchanged.

---

## 6. Step 6: the MINOR findings

The MINORs stay MINOR: none is promoted. "MUST before activation" means the defect would corrupt either production verification or the frozen statistical design once activation happens. It does not mean the finding is re-graded.

| Finding | Disposition | Rationale |
|---|---|---|
| **R5-14** | **MUST before activation** | the object-side half of the item-10 invariant that R5-06 repairs. Without it, an unread required file leaves no trace in the final object. The addendum item 10 text already requires the CONTRACT-DEVIATION escalation; only the check is missing. Cheap; same function |
| **R5-15** | **MUST before activation, gated** | a **false FAIL** in production: any label with STAGE-2A-2B dispositions fails READ-COVERAGE under rev 5. It blocks liveness, not validity. ⚠ The site (`p3b_s5_verify.py:706`) is on the **historical** path, so the fix must rename the variable **only under `rev5`** to keep rev 1–4 byte-identical, and the rev-3 carry-over must stay as it is |
| **R5-16** | **MUST before the seed freeze** | resolved by the EP-01 specification (disjoint strata, EMPTY in the frame, n = 0 guard, no forced n = 1, stratified variance and bound). A statistical design is frozen at activation and cannot be repaired afterwards |
| **R5-10** | **SHOULD**, fold into the R5-07 slice | exact pair-id tokens, and reason OTHER on the escalation. Same function (S1) as R5-07; a small change. Without it, an S1 NO can be discharged by another pair's register record |
| **R5-09** | **SHOULD**, fold into the R5-08 slice | S3 regex variants and `re.I`; P1-gap records inside the lint. It weakens S3 but does not open a false PASS on the core claims; historical G-12 catches part of it |
| **R5-12** | **SHOULD** | the verifier must not rely on the reader refusing non-paged R5 reads: FAIL on an R5-run entry without a `page`. A one-line defense in depth |
| **R5-17** | **SHOULD** | write the temporary old-verifier copy under a temp dir, not `scripts/`. Hygiene, a tracked-tree risk, trivial |
| **R5-13** | **SHOULD** (the `scan_level` first-return bug) / **MAY defer** (a transcript-scan artifact the verifier checks) | the resolver still refuses hold-out content, so no hold-out bytes can be exposed. Fixing the loop is cheap. A verifier-checked transcript artifact is new machinery; defer unless the human wants stop condition 16 machine-enforced |
| **R5-11** | **MAY defer** | acks prove delivery, not cognition, as the addendum already states. A superset test is adequate for that limited purpose |
| **R5-18** (COSMETIC) | closed by the R5-04 repair | same site |

---

## 7. Historical compatibility statement

- **Revisions none/1–4.** No change reaches them:
  - `p3b_s5_r5*.py` is never loaded below revision 5 (import-guard test);
  - the only edits to `p3b_s5_verify.py` are inside the `rev5` branch (l.401–414) and the gated R5-15 rename;
  - the byte-identity isolation test is part of the gate.
- **Production manifest.** Untouched by the repair. `plan_sha256` binds to a **new** activation artifact, the frozen plan, and to the activation record.
- **Historical logs and ledgers.** Not rewritten. `ledger-p3b-r2/` has no revision-5 run today.
- **The committed test fixture** with a false declared size is corrected, and the correction is listed in the implementation record, because it encodes R5-05.

## 8. Open questions for the human (each changes what gets implemented)

| # | Question | Recommendation |
|---|---|---|
| **Q-1** | Repair as a **pre-activation amendment of revision 5**, or as a new revision 6? | amendment of revision 5 (not activated; no production artifact carries its hash; one §26 revision stays one) |
| **Q-2** | R5-01 scope: claim map for **DECOMPOSED only**, or also SINGLE? | DECOMPOSED only. SINGLE objects are written by the agent that read the files, and their quotes are already checked by the production quote checker |
| **Q-3** | EMPTY: add "no timeline points, no dependency edges, no S-id citations" to item 16? | yes, as a clarification of R17 |
| **Q-4** | INFERENCE timeline points in DECOMPOSED objects: allowed only with ≥ 1 fact reference, and **never** as change points? | yes. A change point needs WHOLE-FILE evidence (item 10), which only a record fact can supply |
| **Q-5** | Which MINORs enter the repair slice? | the MUST and SHOULD rows of §6 |
| **Q-6** | After implementation: is a further independent audit required? | a human decision (stopping rule). The re-run of the auditor's unmodified scripts (§5 gate 1) is the minimum acceptance evidence either way |

## 9. Task checklist (for the implementation slice, once authorized; none started)

- [ ] Human approval of this plan, with answers to Q-1…Q-6 (a G-LOG entry).
- [ ] RED: tests for every row of §5 and each accepted MINOR, failing against the current code.
- [ ] GREEN in slice order: W2 → W1 → rules → W3.
- [ ] Addendum and runbook amendments (item 10 fact schema, item 16, steps 6/11/13), with hashes recorded.
- [ ] Gate §5 (1–4); the implementation record in `audit-p3b/`; developer-facing notes where applicable.
- [ ] Hand-back for the human decision on a further audit (Q-6).

**Risks:**
- R5-01 is a contract change. It adds work to every unit agent (facts with quotes), which raises unit cost and output size. It is empirically unvalidated, like decisions A, B and H.
- The claim-map rule could reject honest syntheses that combine facts loosely. The kind-compatibility table must be written from the closed value sets, not invented.
- The R5-15 gated rename must not change rev-3 output (a byte-identity test).

**Traceability:** audit `20260926_REVISION-5-INDEPENDENT-AUDIT.md` (R5-01…R5-18) · G-LOG-0082 · revision-5 record `20260926_P3B-S26-REVISION-5-RECORD.md` · addendum `prompts/20260926_2100_p3b-agent-contract-r5-addendum.md` · runbook `prompts/20260926_2200_p3b-s5-r5-batch-runbook.md` · pilot contract `prompts/20260925_1325_p3b-s5-decomposition-pilot-contract.md` §1.3.
