# Membership / Participation research models (bounded, executable)

**Status:** research artefacts, retained on request (Principal Reviewer, `20261009_2122_implementation.md` §2.B) as **counterexample generators and test-oracle scaffolding**. They are **not** production code, not tests in any suite, and not evidence of real-world frequencies.

**Run:** `python3 -I <file>`. Standard library only; deterministic, with no randomness.

| Model | Question | Domain (finite) | Result (as committed) |
|---|---|---|---|
| `g2_obligations.py` | stored charges vs a derived target after one correction | T = 6 months, 1 member, Admit / End, ≤ 1 annulment, fee 1 per month, pay none / all | stored wrong in 140 / 222; credit in 70 / 222. The "derived" result equals truth **by construction** (no evidence) |
| `g2_ledger.py` | append-only ledger with adjusting postings (design H) vs legacy STORED vs NAIVE recompute, against an **independent** interval-set oracle | T = 6, fee 2, ≤ 2 corrections from {AnnulEnd, AnnulAdmit, ReAdmit, CorrectEnd}, ≤ 1 waiver, close ∈ {none, 2, 4}, pay none / full / half | 42,075 histories. The two resolvers agree in all of them; H matches the oracle **per service month** in all; no closed posting mutated; STORED wrong in 38,106; NAIVE would mutate closed months in 14,433; credit in 14,883 |
| `g3_g4_access_vacancy.py` | an event-maintained access list vs derived access; legal vs functional vacancy | T = 6, one tenure, End optionally annulled | ACL diverges in 180 / 432 (476 wrong months); legal ≠ functional vacancy in 100 / 432 |
| `g4_reentry.py` | lapse vs suspend (BQ-P3) with re-entry | T = 6, End then re-admit at g ≥ e | they differ in 110 / 540; with no re-entry, 0 / 432 |

## Assumptions and limits

- The generators include some histories the kernel would **refuse**, for example AnnulEnd plus ReAdmit producing overlapping periods. Results cover all *generated* histories; they are not filtered by domain admissibility.
- No pro-rata or partial-month fees. One fee rule. No refunds (credit is counted, not settled). No interest or dunning.
- **The `g2_ledger.py` oracle initially disagreed with the replay resolver in 5,040 histories.** The cause was a **correction of an annulled End**. The oracle applied it; the replay treated it as inert. The ratified rule **BD-REV** ("a correction of an annulled fact is inert") decides it, and the oracle was corrected to follow it. That disagreement was the oracle doing its job: finding a semantic point that needs a ratified rule.
- Counts are numbers of enumerated cases in a deliberately small, uniform domain. **They are not probabilities.**

**Traceability:** `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md` (G-2 … G-4) · `…-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §9.

## Revision 2 (2026-10-09): kernel-admissibility filter and Finance falsification (Principal Reviewer `20261009_2145…` §3–§4)

**New files:**
- `kernel_admissibility.php`: a research bridge. It replays the Admit / End / re-Admit operations of a history through the **real Slice 0 kernel** (`decideAdmit` / `decideEnd`, G\*) and returns the refusal reason, or `isMember` per month. **No production code was changed.**
- `kernel_filter.py`: classifies every generated history and reruns the model comparisons per class.
- `g2_ledger_falsify.py`: the attack round on design H.

**Reproduce** (repo root, PHP and vendor present; deterministic):

```bash
python3 -I docs/publicdigit/membership/architecture/models/kernel_filter.py        # ~15 s
python3 -I docs/publicdigit/membership/architecture/models/g2_ledger_falsify.py    # ~10 s
```

**The kernel can judge only part of a history.** Slice 0 implements **Admit and End only**. Annul and Correct are ratified (2026-10-08) but **not implemented**, so histories containing them cannot be judged by the kernel. A waiver is a Finance fact, so the Membership kernel does not need to judge it.

| Class | Meaning | g2 histories |
|---|---|---|
| **K-ADM** | no Membership correction; the kernel accepts every operation | 223 |
| **K-REJ** | the kernel refuses an Admit / End operation | 0 (the generators only produce Ends after starts and non-overlapping re-admissions) |
| **UNCHK-INV-EP-ok** | contains Annul / Correct (not judgeable); a Python re-implementation of the **ratified** INV-EP finds no overlap (a labelled hypothesis filter, **not** the kernel) | 3,948 |
| **UNCHK-INV-EP-violated** | as above, but the final periods overlap, so ratified G\* would refuse the correction | 504 |

**Results, before → after filtering** (counts of enumerated runs, not probabilities):

| Result | Before (all) | K-ADM | UNCHK-ok | UNCHK-violated | Classification |
|---|---|---|---|---|---|
| kernel `isMember` = independent oracle | — | **223 / 223 agree** | n/a | n/a | DEMONSTRATED (the kernel vs an independent oracle, bounded) |
| H matches the oracle per service month | 42,075 / 42,075 | 2,007 / 2,007 | 35,532 / 35,532 | 4,536 / 4,536 | BOUNDED-MODEL RESULT |
| STORED (legacy) wrong | 38,106 | **1,368**, caused only by waivers recorded after the charge | 33,642 | 3,096 | COUNTEREXAMPLE (K-ADM ones are kernel-admissible) |
| NAIVE recompute mutates closed months | 14,433 | 264 | 12,888 | 1,281 | COUNTEREXAMPLE |
| lapse ≠ suspend (g4, with re-entry) | 110 / 540 | **all 540 histories K-ADM; 110 differ; kernel = model in 540 / 540** | — | — | BOUNDED-MODEL RESULT on kernel-admissible histories |

**Effect of the filter:**
- Every earlier conclusion **survives on kernel-admissible or ratified-consistent histories**.
- 504 g2 histories rely on corrections that ratified G\* would refuse. They are reported separately and should be discounted.
- The access model (g3) and `g2_obligations.py` use annulment only, so beyond their base Admit / End (6 / 6 admissible) they are UNCHK.

**Finance falsification round** (`g2_ledger_falsify.py`; histories split by the classes above):

| Attack | Result | Classification |
|---|---|---|
| F1: non-linear joining fee (3 in a period's first month, then 2), alongside the linear rule | H = oracle per month in every class | BOUNDED-MODEL RESULT |
| F2: refund the credit when it appears; a later correction reinstates the charge | H still correct. In **486** UNCHK-ok histories, money refunded is **owed again** later | COUNTEREXAMPLE to "refund immediately" as a safe default → **business decision (BQ-P10)** |
| F3: retried reconciliation in the same month | posts nothing extra (idempotent by construction) | BOUNDED-MODEL RESULT |
| F4: reconciliation in reverse month order | the same per-month totals | BOUNDED-MODEL RESULT |
| F5: **two concurrent reconcilers** on one snapshot, **no key** | double posting in 214 / 223 K-ADM (minimal: a single Admit) | **COUNTEREXAMPLE.** H *requires* serialisation or an idempotency key |
| F5 with a **content key** (service month, target value) | **fails in 53** UNCHK-ok histories | **COUNTEREXAMPLE.** Minimal: Admit@0; End@2; CorrectEnd → 1 (recorded 2); Annul End (recorded 3). Under BD-REV month 1's target goes 2 → 0 → 2, and the content key suppresses the legitimate second adjustment |
| F5 with a **version key** (service month, Membership log version) | 0 failures in every class | BOUNDED-MODEL RESULT → **design rule: key adjustments by source version, never by content** |

**Unmodelled (not claimed):** interest, dunning, day-level pro-rata, multiple members, currency, re-opening closed periods, payments allocated to specific months, Finance's own corrections.

**Semantic disagreements:** none open. The one found earlier (a correction of an annulled End) is decided by ratified BD-REV.

## Revision 3 (2026-10-09): committee authority (`g5_committee_authority.py`)

| Sub-model | Question | Result |
|---|---|---|
| M1 | delegation checked at creation vs at use, under mandate amendment | creation-time check wrong in 35 / 375 [CX]. The use-time 0 / 375 is weak evidence (it nearly restates the spec) |
| M2 | tenure end recorded late: validity as known vs as now | they differ in 35 / 250 → a business question (de-facto acts) |
| M3 | quorum over appointed vs effective seats (seat requires membership) | they differ in 522 / 3,645 [CX] |
| M4 | recusal | 12 / 108 pass only without recusal |
| M5 | separation of duties | 7 / 16 role assignments allow a single-person payment |

Membership enters only as given Admit / End intervals, so the kernel filter is not needed. Details: `docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md` §7 and §11.

## Revision 4 (2026-10-09): synthesis

All models are registered, with what they do and do not establish, in `docs/publicdigit/reviews/2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md` §B.2. The same document records a mutation measurement of the Slice 0 kernel (§B.3):
- MSI 83 %; covered-code MSI 92 %;
- every surviving mutant classified;
- the `continue → break` mutant at `Resolution.php:29`, reported as "uncovered", was killed by 11 tests when run directly.

## Revision 5 (2026-10-09): correction-algebra oracle (`g6_correction_oracle.py`, `g6_checks.py`)

**What it is:** an executable oracle of the **ratified** correction algebra: final proof §14.2 (live, active chain, value, BaseClaim, frozen `g2` consequences, LiveCut, ResolvedLifetime), G\* (INV-9 / EP / COV), and act N-3 refusals.

**Results:**
- **Agreement with the real kernel:** 400 / 400 on the Admit / End fragment.
- **L ≤ 4 (H = 5):** 14,365 accepted histories; the INV-COV safety net never fires; Annul(Annul) restores 90 / 90.
- **Remedy coverage** (histories ≤ 3 facts): every wrong-Admit-date, wrong-End-date, spurious-Admit and spurious-End error is repairable by one ratified operation (418 / 418 operation checks).
- **BD-REV-ER:** a correction absorbs a whole live period in 35 histories; refusing them changes nothing else.

**Not modelled:** a wrong holder, authority, Renew / Suspend / ChangeType, concurrency. Counts are not probabilities.

Details: `docs/publicdigit/reviews/2026-10-09-membership-integrity-report-and-correction-oracle.md`.

## Revision 6 (2026-10-10): cross-domain authorization and Governance boundary (`g7_governance_aggregate_boundary.py`, `g8_pdp_guards_and_cache.py`)

**What they are:** research models for the cross-domain architecture v1.1 (`../brainstorming/cross_domain_brainstorming/20261010_2327_final_cross_domain_architecture_v1.1.md`). No production code.

**Results:**
- **g7:** two concurrent Appoint commands for one person in two committees. A per-committee aggregate alone violates cross-committee incompatibility in 18 / 20 interleavings. Adding a per-(person, organisation) `PositionHoldings` aggregate gives 0 / 20. Local capacity: 0 / 20 in every strategy.
- **g8 P1** (5,625 cases): guards over {T, F, U}. Absence-as-false gives 238 unsound permits; Kleene binding on T only gives 96; Kleene with prohibitions / denies binding on T ∨ U gives 0 (and 0 lost certain permits).
- **g8 P2** (18,960 cases): cached decisions under event lag. An "obvious facts" version vector trusts 1,956 stale entries (978 unsafe permits); the full closure without a lag rule trusts 960 (480 unsafe). Watermark-at-head or a synchronous version read: 0.

**Not modelled:** contention cost, more than two commands, multi-module policies, partial orders of events. Counts are not probabilities.

## Revision 7 (2026-10-10): Governance temporal compatibility (`g9_governance_temporal_compat.py`)

**What it is:** a check that the ratified fact pattern transfers to Governance tenures and the committee lifecycle (cross-domain architecture v1.2 §2). Research only.

**Results** (35,113 histories; months 0–3; up to 4 facts):
- **T-1:** a recording-time status field shows a future-effective appointment as active early in 10,986 histories.
- **T-3:** storing a Vacate on dissolution diverges in 270 histories once the dissolution is annulled.
- **T-6:** the annulment counterfactual holds for the derived reference (0 / 5,916 violations) and fails for cascade (190 / 5,916).
- **T-5** (pinned policy versions) is true by construction and only illustrative.

**Not modelled:** capacity > 1, several positions, Correct / Void on tenure facts, delegation. Counts are not probabilities.

## Revision 8 (2026-10-10): write-boundary authorization and footprints (`g10_toctou_and_absence.py`; `g7` Q3)

**Results:**
- **g10 Q1 (TOCTOU):** decide-then-write and an unlocked re-check each allow 2 unauthorised writes. An atomic read-set precondition gives 0, including from a cached decision. Value comparison is also 0 but loses version pinning.
- **g10 Q2** (111 cases): a read set holding only the returned facts gives 23 replay mismatches and 31 stale cache entries trusted. Aggregate (footprint) versions give 0 / 0.
- **g7 Q3** (3 commands, 1,680 interleavings): committee-only 738 incompatibility violations; Committee + PositionHoldings 0; capacity 0 throughout.

**Not modelled:** more than one fact type per decision, more than one concurrent writer per fact, Vacate interleaving, capacity > 1. Counts are not probabilities.

## Revision 9 (2026-10-10): write-boundary probe on a real PostgreSQL server (`g11_pg_write_boundary.php`)

**What it is:** a research probe (not application code). It runs only against a Unix-socket database not named `nrna_test`. It was run on a private throwaway PostgreSQL 18.6 cluster (`initdb` in the session scratch directory, TCP disabled, stopped afterwards). It executes every interleaving of two real sessions, READ COMMITTED.

**Results:**
- **E1, revocation vs post** (35 schedules): no check 16 unauthorised; plain version read in the transaction 16; SERIALIZABLE plain read 16 (0 serialization failures: logical order, not real-time); `FOR SHARE` on the version row 0.
- **E2, absence / phantom:** locking the matched suspension rows 16; scope version token 0.
- **E3, two-root lock order** (70 schedules): opposite order 36 deadlocks; sorted order 0.

**Not covered:** more than two sessions, the real schema, Laravel. Counts are not probabilities.

## Revision 10 (2026-10-11): multi-module policies, missing tokens, lock upgrade (`g8` P3; `g11` E4, E5)

**Results:**
- **g8 P3** (207,000 cases: three modules, alternative / AND / OR grants, missing modules): 0 unsound permits; 0 permits with a missing module. Completeness gap 481 (173 with a tautology grant). Consistent with the regularity of strong Kleene logic (discovery package v1.5 §4.3).
- **g11 E4** (PostgreSQL 18.6, private throwaway cluster): lazily created scope token 16 / 35 unauthorised; guard upsert-then-lock 0 / 35.
- **g11 E5:** FOR SHARE then UPDATE of the same token 36 / 70 deadlocks; FOR UPDATE up front 0 / 70.
- E1–E3 reproduced identically.

**Not covered:** three sessions, per-context token tables (FV-1), the real schema.

## Revision 11 (2026-10-11): g11 E6–E9, g12, g13

**Research only.** g11 runs on a private throwaway PostgreSQL 18.6 cluster (scratch directory, Unix socket only, stopped afterwards).

**g11 run 3** (executed script SHA-256 `23e63c81…`, exit 0, 21 min 50 s):
- E1–E5 reproduced;
- **E6** three sessions, two context token tables: plain read 2,300 / 4,200 unauthorised; canonical `FOR SHARE` 0 / 4,200, 0 deadlocks;
- **E7** decide inside the transaction: plain 16 / 35; locked 0 / 35;
- **E8** delegation footprint: partial locks 16 / 35; extended locks 0 / 35.

**g11 run 4** (refactored harness `fd1c29d0…`):
- E1–E8 reproduced exactly;
- **timed out (exit 124) in E9**, with no E9 output.

**g11 run 5** (E9 only, `c66b1e55…`):
- **E9a** absent aggregate vs first admission: ROW_ONLY 16 / 35; PARENT_TOKEN (owner ancestor token) 0 / 35;
- **E9b** three sessions with two concurrent creators: ROW_ONLY 2,300 / 4,200 unauthorised and 2,520 duplicate memberships;
- **PARENT_TOKEN: 0 / 4,200 unauthorised, 0 duplicates, 0 deadlocks** (run 5, exit 0). **E9 is closed** as bounded-model evidence.

**g12** (reference PDP, `ada92fb2…`; vectors `745fc839…`):
- 1,920 vectors;
- DO and PO: 0 unsound PERMIT / DENY;
- completeness gap 20 / 22;
- DO and PO disagree in 12 vectors.

**g13** (replay, `b8304ce5…`; seeded, 39,933 decisions):
- per-scope seq replay: 0 mismatches;
- timestamp replay: 7,742 mismatches;
- pruned history → RECONSTRUCTION_INCOMPLETE (by construction).

**Not covered:** the real schema / Laravel / command handlers; cross-table opposite-order writers; restart-with-union; multi-level delegation; a missing parent token (root fallback).

## Revision 12 (2026-10-11): cross-context consistency (`g14_cross_context_consistency.py`, SHA-256 `fd24831b…`)
Research only; no repository data. Write-up: `../brainstorming/cross_domain_brainstorming/20261011_0900_cross_context_consistency_and_simulation_study.md`.

- **S1 (C3), 3,905 sequences:** `A ⇒ M` violations per rule:
  - R0: 2,038;
  - R1 (check at appointment): 161;
  - R2 (check + cascade): 0, but 19 tenures lost after an annulled End;
  - R3 (derived): 0 / 0.
- **S2 (C4 / C8), 20,000 synthetic pairs per noise level, seed 20261011:** latest-wins and fixed priority give silent errors and discard evidence at every level. Agree-or-queue gives 32 / 436 / 1,837 silent errors, discards nothing, and queues 2,980 / 6,939 / 9,611.
- **S3 (C1 / C2 / C7):** 18 / 60 combinations are decidable from data alone; 42 / 60 need an approved type ↔ level mapping.
- **S4 (C6):** SNAPSHOT gives replay stability; SUCCESSOR gives current coverage after retirement. Neither semantics alone gives both.

**Limits:** the noise rates are stated assumptions, not measurements. The counts are model counts, not real-world frequencies.
