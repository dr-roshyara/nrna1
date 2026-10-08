# Membership: Proof and Verification Report

**Date:** 2026-10-08 · **For:** the Principal Reviewer · **Canonical model:** `2026-10-08-membership-architecture-ratified-candidate.md` (**RC**)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Status words:**
> - **PROVEN:** a logical proof from the definitions;
> - **PROVEN UNDER ASSUMPTIONS:** a proof with the assumptions listed;
> - **FINITE VERIFIED:** a bounded or random computation (never called proof);
> - **COUNTEREXAMPLE:** conclusive;
> - **OPEN.**
>
> **Generated frequencies are model counts, not statistics.**

## 1. Last Contradiction Register

| # | Claim | Prior classification | Hidden assumption | Contradiction found? | Changes architecture? | Final status |
|---|---|---|---|---|---|---|
| C1 | B′ identity | recommendation | that EM-VOC-001's "aggregate" = the enduring record | none. B′ is compatible; an A-answer requires a terminology act | parameterised (RC §7) | RECOMMENDATION · BD-1 |
| C2 | I5 no overlap | business | that no concurrent legal memberships exist (honorary + ordinary as **distinct classes**; ex-officio + ordinary with independent ends) | **possible.** If the statutes treat them as distinct memberships, I5 is too strong per (p,o) | **invariant scope only** (per class): parameterised | BD-I5 |
| C3 | synchronous I5 | business | that no transient overlap is ever tolerable | the **person merge discovers** overlaps no guard could prevent, so a reconciliation path is needed anyway | D1 stays valid; only minimality is conditional | BD-SYNC |
| C4 | **NE** | "domain rule" (last pass) | that the proof's need = the domain truth | **yes: (a)** NE was a proof convenience; **(b)** trim *produces* empty lifetimes (full supersession), which NE ignored. Re-enlarging such a period recreated the split hazard | replaced by guard **ER**; NE is no longer needed | **WITHDRAWN** → BD-NE is business-only |
| C5 | Invalidate{annulment\|nullity} | a minimality merge | that the grounds differ only in label | **yes:** different authority (appeal organ vs registrar), reporting (annulled admissions exist historically; voided ones do not), downstream reaction (an annulled End may matter to Election FM mapping; a voided record never does) | fact vocabulary: **separate kinds** (Annul, Void); storage may be shared | **REVERTED** |
| C6 | supersede-by-trim | a derived consequence | a policy-independent derivation | **yes:** a derivation from the *current* policy would rewrite history if the policy changed; reversing the triggering fact must revive trimmed periods explicitly | **Trim = a recorded consequence fact** | AMENDED |
| C7 | three clocks | architecture | that seq is merely technical | seq is required for deterministic KnownAt; it must be persisted immutably with the fact | none | CONFIRMED |
| C8 | term segments | architecture | that "Effective" = legal membership | **yes, an ambiguity:** "Effective" mixed the lifetime and the paid term | renamed `TermCovered`; `InForce = InLifetime ∧ TermCovered` | AMENDED (naming) |
| C9 | derived predicates | architecture | that "Recognised" is needed | redundant under REC-0 = no | removed (re-added only if BD-REC0 = yes) | AMENDED |
| C10 | D1 boundary | architecture | that it is a domain object | it is a **transactional enforcement boundary** for INV-9 that coincides with EM-VOC-001's concept | conditional proposal | CONFIRMED (conditional) |

## 2. NE alternatives (C4 resolved)

| Alternative | Preserves INV-9 | Period identity | Trim | History / audit / legal | Complexity | Verdict |
|---|---|---|---|---|---|---|
| **NE-A** same-instant membership impossible (a domain rule) | ✓ | ✓ | ✓ | forces "admitted and ended the same instant" to be **re-described** as an invalidation (it may be legally false) | low | rejected as a *domain* claim (unproven business meaning) |
| **NE-B** possible, as a different fact kind | ✓ | ✓ | ✓ | ✓ | **+1 fact kind** | unnecessary (ER achieves it without a new kind) |
| **NE-C** possible as a zero-length episode **+ guard ER** | ✓ (proof §4) | ✓ (PeriodId kept) | ✓ (only non-empty targets trim) | ✓ (the Admit and End facts both exist) | **one guard rule** | **adopted for the canonical model** |

- **Granularity note:** with day-granularity half-open intervals, a "same-day" membership is `[d, d+1)` and is **non-empty**.
- A **zero-length** lifetime means *ended at the very instant it began*: never in force at any instant, though the acts exist historically.
- **Whether the business wants to allow that is BD-NE: business-only.**

## 3. Predicate consistency (truth table + exhaustive check)

| State | InLifetime | Ended | TermCovered | InForce | Lapsed | Suspended | IsMember |
|---|---|---|---|---|---|---|---|
| 1 before admission | F | F | F | F | F | F | F |
| 2 admitted, effective in the future (t < s) | F | F | F | F | F | F | F |
| 3 active | T | F | T | T | F | F | T |
| 4 lapsed | T | F | F | F | T | F | F |
| 5 renewed after a lapse (new segment) | T | F | T | T | F | F | T (the gap stays F) |
| 6 suspended while active | T | F | T | T | F | T | **T** |
| 7 ended | F | T | (may be T: a paid term outliving the lifetime) | F | F | F | F |
| 8 admission annulled or voided | F | F | F | F | F | F | F |
| 9 trimmed period (t in the removed part) | F for this q | — | — | F for this q | — | — | T **via the other period** |
| 10 fully superseded | F | — | — | F | F | F | via the other period |
| 11 corrected period | per the corrected values | | | | | | |
| 12 unknown historical date | **U** (Kleene) where t falls in the precision window | U | U | U | U | U | U |
| 13 migration (attested) | as the attested dates | | | | | | |
| 14 an overlapping candidate before the guard | refused by g1/g2/ER, so it never becomes a resolved state | | | | | | |
| 15 a same-instant boundary `[a,b)`, `[b,c)` | at t = b: q1 F (Ended), q2 T | | | | | | T (q2) |

**Exhaustive check over all 32 assignments of the primitives** (started, before-end, term-cover, restricted, invalidated) found **0 violations** of:
- `Ended ⇒ ¬InForce`;
- `Lapsed ⇒ ¬TermCovered`;
- `Lapsed ⇒ ¬Ended`;
- `¬(Lapsed ∧ InForce)`;
- `Suspended ⇒ InLifetime`;
- `InForce ⇒ InLifetime`;
- `InLifetime ⇒ (InForce ⊕ Lapsed)`.

`IsMember ⇒` exactly one InForce period: from INV-9 (§4). **Removed:** Recognised (redundant), Effective (ambiguous; replaced by TermCovered).

## 4. INV-9: proof (final assumptions)

**Assumptions:**
- **A1:** g1, g2 and ER are evaluated synchronously on one Membership against `Res(F)`.
- **A3:** a total order `seq`.
- **A4:** each fact changes at most one bound of one target lifetime.
- **A5:** Trim facts are applied as recorded. A Trim reversal is itself a lifetime-enlarging fact, subject to ER.
- **No NE.**

**Induction:** P = "resolved live lifetimes are pairwise disjoint".

| Fact | Case | Preservation |
|---|---|---|
| Admit(v) | new `[v,∞)` | g1: every live lifetime has end ≤ v |
| End / Correct-shrink | subset | trivial |
| Annul / Void of an Admit | removal | trivial |
| enlargement, **non-empty** target (`L_old ≠ ∅`) | refuse, or trim each q′ to `q′ \ L_new` | `L_old ⊆ L_new`; `L_old ∩ q′ = ∅`, with L_old non-empty. Hence q′ **cannot strictly contain** L_new. So `q′ \ L_new` is **one interval** (left, right or empty). **No split** |
| enlargement, **empty** target | **ER:** refuse on any overlap | disjoint by refusal |
| Trim reversal (Annul/Void of a Trim) | restores q′'s removed part | **ER:** refuse on any overlap |
| Renew / ChangeType / Suspend / Restore | no lifetime change | trivial |

**INV-9: PROVEN UNDER ASSUMPTIONS A1, A3, A4, A5 and ER.** I5 is PROVEN from INV-9.

## 5. Supersede-by-trim: domain semantics (the example)

`P1 = [2020, 2025)`, `P2 = [2025, 2030)`. Later, a Correct sets P1's end to 2027, so a Trim cuts P2 to `[2027, 2030)`.

| Question | Answer |
|---|---|
| Was P2 legally valid from 2025? | **The admission was a real act** (its facts remain). The correction establishes that the person **was still a member under P1** until 2027, so P2's effect is **superseded** there |
| Does the correction change that retroactively? | yes, in TrueAt. **KnownAt(τ < correction)** still shows P2 from 2025 |
| Continuously a member? | yes: `IsMember` is true over 2020–2030 (no gap introduced or lost) |
| Membership register | MembershipId unchanged; P1 `[2020, 2027)`, P2 `[2027, 2030)` (trimmed, cause = the Correct fact) |
| Audit | the Correct fact + the Trim fact (cause, removed interval, policy rule ref) |
| Fees | Fee(P2) for 2025–2027 overlaps P1's term. The refund / credit policy is **Finance** (DS-1) |
| Committee tenure | the continuous / cumulative predicates are unchanged (they use the union) |
| Elections referencing P2 for 2026 | the reference is now historically superseded. **Consumers should reference (MembershipId, asOf)** |
| A legal report generated before the correction | reproducible exactly: `KnownAt(·, ·, τ_report)` |

**Definition: TrimmedPeriod** is the **derived temporal effect** of a **legal supersession rule**: the reinstated earlier episode takes precedence (BD-REV). It is **recorded** as a Trim consequence fact (an architecture device for determinism). **It is not an independent domain event.**

## 6. Classification lattice (no hidden lifecycle)

```text
Fact (resolution, derived)            Period (derived classifications)         Temporal predicates
  valid                                 lifetime [s,e) (from facts + Trims)       InLifetime, Ended, TermCovered,
  annulled   (an Annul targets it)      trimmed            (≥1 live Trim)          InForce, Lapsed, Suspended
  voided     (a Void targets it)        fully superseded   (resolved lifetime ∅)
  corrected  (a Correct targets it)     invalidated        (its Admit annulled/voided)
Audit labels = projections of the left two columns. No command targets a classification → no second lifecycle.
```

## 7. Annul vs Void (C5 resolved): Option B

| Test | Annul | Void |
|---|---|---|
| audit / legal history | a decision existed and was set aside | no decision / event ever existed |
| appeal | its instrument | not applicable |
| correction | — | it is a nullity correction |
| authority | the competent appeal / organ | the registrar / data correction authority |
| reporting | counted as an admission that was annulled | **excluded** entirely |
| migration | — | removes legacy junk records |
| external references | a reference stays valid (history) | a reference stays resolvable, marked as never-real |
| downstream | may matter to Election FM mapping (deferred) | never triggers anything |
| TrueAt / KnownAt | both remove the effect; KnownAt shows when | same |

**Verdict: Option B, separate domain fact kinds.** A shared storage shape is an implementation choice. The commands `AnnulAdmission`, `VoidAdmission`, etc. map 1:1.

## 8. Verification suite (V1–V12)

| # | Property | Model | Assumptions | Counterexample | Proof | Finite verification | Status |
|---|---|---|---|---|---|---|---|
| V1 | no overlapping periods | `closure2.py` | A1, A3–A5, ER | without ER: 81 splits / losses | §4 | **0 / 300,000** (set semantics, same-instant allowed, trim reversals) | PROVEN UNDER ASSUMPTIONS |
| V2 | I5 from INV-9 | logic | INV-9 | — | interval disjointness | — | PROVEN |
| V3 | lapse never ends | logic | Ended only from End | — | induction | 0 (E3) | PROVEN |
| V4 | correction cannot overlap without g2 | `ratify4.py` | — | **yes, without g2** | — | 0 with g2 | PROVEN UNDER ASSUMPTIONS |
| V5 | annulment cannot overlap without g2 | same | — | **yes, without g2** | — | 0 with g2 | PROVEN UNDER ASSUMPTIONS |
| V6 | KnownAt deterministic | logic | seq; pure fold; recorded Trims | — | `F_τ` is a fixed prefix; the fold is pure; no policy is consulted | — | PROVEN UNDER ASSUMPTIONS |
| V7 | TrueAt may change via later facts | `stress.py` | — | backdated / annul / correct | — | many | PROVEN (by counterexample to stability) |
| V8 | no contradictory predicates | exhaustive | definitions | none | §3 | 32 / 32 | PROVEN |
| V9 | period references never disappear | construction | INV-14 | — | no deletion fact exists | ids never lost (`ratify4`) | PROVEN UNDER ASSUMPTIONS |
| V10 | trim never splits | §4 + run | ER | without ER (and without NE): 52 (`closure`) / 81 (`closure2`) | §4 | 0 with ER | PROVEN UNDER ASSUMPTIONS |
| V11 | no invalid zero-length periods | — | — | — | **reformulated:** zero-length episodes are **valid** under NE-C. ER makes them harmless | 0 problems | PROVEN UNDER ASSUMPTIONS (ER) |
| V12 | historical facts reconstructable | logic | append-only; seq | — | KnownAt for every τ | — | PROVEN UNDER ASSUMPTIONS |

**Bounds of the runs:**
- `closure2.py`: random 300,000 histories; time domain 3–10; up to 16 facts; seed 8102026.
- `closure.py`: 300,000 × 8 configurations.
- `ratify4.py`: exhaustive, 444,604 histories.
- `final2.py`: exhaustive, 153,920 histories.

## 9. Statistics and ML

- **STATISTICS: NOT AVAILABLE.** No representative production data exists.
- **ML: intentionally not used for domain truth.** It did not improve semantic modelling, invariant proof, requirement discovery or architecture.
- **Future, advisory, human-reviewed, non-authoritative uses:** duplicate person detection (DS-3), migration anomaly detection, identity-resolution suggestions, unusual lifecycle patterns, data quality.
- **ML never decides** identity, authority, membership, termination, legal validity or voting rights.

## 10. Remaining risks

| Risk | Nature |
|---|---|
| concurrent legal memberships (distinct classes) in some statutes | BD-I5 scope (parameterised) |
| person merge discovers overlaps | DS-3. Reconciliation by explicit facts |
| legal validity of consent / statutory bases / appeal effects | LQ-1…4 |
| fees on trimmed periods | DS-1 |
| imprecise legacy dates | three-valued predicates; consumers must handle U |
| bounded computation | the key invariants have proofs (§4, §3) |
