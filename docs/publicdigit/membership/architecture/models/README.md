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
