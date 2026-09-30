# R7 (v2.4): the ONE independent adversarial audit, authorization package

| | |
|---|---|
| **Kind** | Authorization package (HD-9). ⚠ authority: generated. It requests one human act. It also records the v2.4 implementation verification, so no separate record is written |
| **State** | R7 v2.4 **IMPLEMENTED · ENGINEERING-VERIFIED · NOT ACTIVATED · NOT INDEPENDENTLY AUDITED** · S5 NOT AUTHORIZED · H-19 SEALED |
| **Authority** | G-LOG-0085 (HD-9), G-LOG-0088 (freeze v2.3), G-LOG-0089 (v2.4 approved; DC-2 approved; RI-1 deferred) |

## 1. What was done under G-LOG-0089 (implementation evidence)

| Item | Result |
|---|---|
| Contract | addendum **v2.4**, sha256 **`fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674`**: the 4 proposal rows (verbatim), row semantics, §3.0 INV-LEX, the META type line stating the per-row rule reading, T98–T104, DR-18. v2.3 (`cdbf53cb…`) is kept in git at `05569322d` |
| Code | **one** production change: `p3b_s5_r7_universe.py` (the sha constant; `SELF_REF_RULE`/`SELF_REF`; the DC-1 rule in `meta_violations`). No Witness, Evidence, Reconstruction, Statistics or composer change |
| Tests | Universe: T103/T104 and the NDB-1 test replace the known-conflict pins (RED observed: sha mismatch + missing DC-1 rule → GREEN). Full path: T98, T103/T104 re-pinned. The proposal suite became `test_p3b_s5_r7_inv_lex.py` (v2.4 from the file; v2.3 from git as the differential baseline). **Fixture de-sanitized:** the positive control now carries 63 real META S-id mentions and is BATCH-PASS |
| DC-2 | applied: the R6 positive control asserts R6 = PASS ∧ "revision 6 is superseded". R6 53/53 |
| RI-1 | DEFERRED (recorded in DR-18, G-LOG-0089 and developer guide 04). No production input was read |
| Verification | see §6 |

## 2. Audit target

> **Find ∃ s: verifier(s) = BATCH-PASS ∧ ¬VALID(s)** within the declared trust boundary (harness, orchestrator and account trusted; ADR-R7-01). VALID(s) is the addendum's meaning of U ∧ W ∧ E ∧ R.
>
> The secondary target is `estimate_v7`: STATISTICS-VALID on an input outside the domain D, or with a wrong τ̂ or V̂.

For each trust invariant, the objective is the **smallest counterexample**, not many tests.

| # | Invariant (addendum §) | Attack classes to prioritize |
|---|---|---|
| I1 | Universe closure: Reach ⊆ dom(Registry) ∧ Valid_Σ ∧ Unique_K; default-deny (§3) | namespace confusion; unregistered S-id paths; discriminator ambiguity; key normalization (`.` in keys, `[*]`); null or missing sources |
| I2 | **INV-LEX** (§3.0): META ↛ evidence | META → evidence escalation; a claim or anchor driven by a META value; the DC-1 rule bypassed (Unicode look-alikes, whitespace, case) |
| I3 | Witness W1–W8 (§5) | transcript incompleteness and truncation; provenance, ownership and timestamp substitution; notification forgery or multiplicity; persisted-output substitution; capability-closure gaps; run/input/evidence confusion |
| I4 | Evidence (§6) | anchoring outside witnessed bytes; cross-run or non-contiguous pages; N-WS map errors; input bytes posing as evidence (T96); hash substitution |
| I5 | Reconstruction (§4) | precedence from anything other than A.10; non-strict orders; the `later_*` lateness gate; summary one-directional constraints; lifecycle ⇔ superseded sources |
| I6 | Composition (§0) | a strong-Kleene error; a U reported as T; a bare PASS; the historical-tag routing |
| I7 | Binding (§2, §5.8) | revision or contract-hash substitution; legacy `-R2*` runs; I(run) derivation ≠ committed manifest; plan-hash substitution |
| I8 | Statistics (§8) | domain D escapes; n_h ∈ {0, 1}; frozen-record anchoring; rate or rounding boundaries |
| — | RI-1 (deferred) | **assessment only:** can an incomplete input read materially invalidate S5 evidence? This is G-LOG-0089's reopening condition 3 |

## 3. Methods (computer logic first)

1. **Counterexample search** per invariant: state the invariant as a predicate, then search small domains exhaustively (bounded enumeration of object shapes, transcript orders, page sets).
2. **Mutation testing of the verifier** (on a scratch copy only): negate conditions, drop checks, swap operands. A *surviving* mutant (all tests still green) marks an unguarded invariant.
3. **Property-based testing** with seeded generators. `hypothesis` is not installed, so use a stdlib `random` with fixed seeds and record the seeds.
4. **Combinatorial interaction** (2-wise and 3-wise) across the perturbation dimensions: registry path × discriminator × value shape; transcript × notification × manifest.
5. **Declaration-vs-observation attacks**: every agent-declared field that a predicate might trust.
6. **Differential checks** against the historical verifier, where the semantics overlap.

**ML:** none in any verdict, generation, sample or estimate.

## 4. Independence and rules

- **The auditor is not the implementation agent** (this session and its subagents). The recommended auditor is a fresh agent with no access to this session's context, **on a different model where feasible** (it reduces correlated blind spots), or a human reviewer.
- **Inputs:** addendum v2.4, ADR-R7-01, `scripts/` (R7 modules, gate, reader), `scripts/tests/`, `developer_guide/knowledgeos/s5_r7/`. The implementation and decision records may be read, but their conclusions must not be relied on.
- **Read-only on the repository.** The auditor writes only (a) scratch reproducers and (b) one report at `audit-p3b/<date>_R7-INDEPENDENT-AUDIT.md`.
- **Synthetic data only**, with synthetic hold-outs (testlib). No corpus, no ledger or state writes, no activation, no H-19 access.
- **Report format:**
  - per finding: invariant, **minimal reproducer** (runnable), observed vs expected verdict, and severity (**MATERIAL** = a false BATCH-PASS or false STATISTICS-VALID is possible · **MINOR** = a false FAIL or robustness issue · **OBSERVATION**);
  - an overall **ACCEPTABLE / NEEDS-REVISION**;
  - the auditor recommends and does not accept.
- **Budget:** one audit. The auditor stops at the first MATERIAL finding per invariant, after minimizing it, and continues with the other invariants.

## 5. Human act requested

**Authorize the ONE independent adversarial R7 audit per this package** and name the auditor (the recommendation: a fresh agent on a different model, or a human reviewer).

No repair is authorized in advance. Any MATERIAL finding returns to a human decision.

## 6. Engineering verification (v2.4)

| Check | Result |
|---|---|
| Full suite | **39 files, 1,049 tests, 0 failures** (1 pre-existing skip, `test_p3b_ob0018_pilot`). This is 1,008 under unittest plus 41 in the five `test_p3b_s5a_*` plain-function suites, run with their own runner. `test_p3b_s5_audit_record` prints no parseable count (OK). The former expected DC-2 failure is gone |
| Affected suites | Universe 21 · INV-LEX 20 · full path 50 · R6 53: all OK |
| Static design checks (v2.4 addendum) | **73/73 PASS**; registry 39 rows, consumers 8, tests 104 |
| `prepare --check` | IDENTICAL |
| H-19 | SEALED (HS-3d32dd44d162); guard 72 files, 0 violations (pre-seal module notes pre-existing, not counted) |
| Baselines | P3B-STATE `db52ac7a…` · seal `9b99169d…` · manifest `1b383fbc…` (revision 3): unchanged; ledger untouched |
| Module sha256 (first 12 hex) | universe `64de34ead34a` (changed) · the others unchanged since `ef9bdf7ba`: syntax `07a803303a9f`, witness `859e342382fc`, evidence `f9f7296a05d0`, reconstruction `5d86d2ff6c68`, stats `eed4ee34d1ae`, composer `62c53b5ed74b`, gate `a227b988e2f5`, reader `f4be32e51389`, common `86e5849130b9` |
| Lane isolation | S-Series paths only; no F-Series, application or Master Protocol file touched |
