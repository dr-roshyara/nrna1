# Membership: Final Proof

**Date:** 2026-10-08 · **Reference:** `…-membership-architecture-ratified.md` (**MA**) · **Supersedes:** `…-membership-proof-and-verification.md` (kept as history)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Classes:**
> - **MP:** mathematical proof;
> - **PA:** proof under explicit assumptions;
> - **EF:** exhaustive finite verification;
> - **RV:** randomised / property-based verification;
> - **CX:** counterexample;
> - **EO:** empirical observation;
> - **AD:** architectural derivation;
> - **REC:** recommendation.
>
> **Generated frequencies are never population statistics.**

## 1. Final Contradiction Register

**Key:** D = domain truth · R = requirement · A = architecture · F = formal consequence · I = implementation choice.

| ID | Proposition | D | R | A | F | I | Evidence | Counterexample? | Disposition |
|---|---|---|---|---|---|---|---|---|---|
| 1 | B′ identity (MembershipId per (p,o); PeriodId per Admit) | | ● (BD-1) | ● | | | the identity criteria (MA §1) | none | **CLOSED** (structure); the naming is BD-1 |
| 2 | D1 aggregate | | | ● | ● (from g1/g2/CFC) | | §2 | none under I5 + SYNC | **CLOSED, CONDITIONAL** on BD-I5 / BD-SYNC |
| 3 | INV-9 | | ● (I5) | | ● | | §3 | none with the guards | **CLOSED** (proof); scope = BD-I5 |
| 4 | I5 scope | | ● | | | | no concurrent classes in the corpus | — | **OPEN: BD-I5** |
| 5 | synchronous enforcement | | ● | ● | | | §2 | — | **OPEN: BD-SYNC** |
| 6 | g1 | | | ● | ● | | §3 | — | CLOSED |
| 7 | g2 | | | ● | ● | | §3 | without it: CX (E0a-4) | CLOSED |
| 8 | ER | | | ● | ● | | §3 | without it: CX (closure2: 81) | CLOSED |
| 9 | **Trim independent of cause** | | | ● | | | **CX: 1,903 / 200,000; scenario F** | **yes** | **AMENDED → CFC** |
| 10 | Trim reversal ("Annul Trim") | | | ● | | | removed by CFC | — | **REMOVED** |
| 11 | Annul / Void / Correct distinct | ● | | ● | | | authority, reporting, downstream (§5) | none | CLOSED |
| 12 | End | ● | | | | | guide | none | CLOSED |
| 13 | Re-Admit = Admit | | | ● | | | — | none | CLOSED |
| 14 | the clocks (effective / decided / recorded) | ● (effective, decided) | | ● (recorded) | | | E0a-4 CX | none | CLOSED |
| 15 | persisted seq | | | ● | ● (determinism) | ● (mechanism) | §4 | none | CLOSED (not a business timestamp) |
| 16 | provenance | ● (validity basis) | | ● | | | path analysis | none | CLOSED |
| 17 | authority evidence | | | ● | | | §6 | none | CLOSED (a result + refs; no rule copy) |
| 18 | term segments / lifetime | ● | | ● | ● (lemma) | | E0a-4 CX | none | CLOSED |
| 19 | TermCovered / InForce / Lapsed / Suspended / IsMember / CurrentPeriod | | | ● | ● | | 32/32 + the DAG | none | CLOSED |
| 20 | tenure predicates | | ● (BD-TEN chooses) | ● | | | — | none | CLOSED (named; no ambiguous field) |
| 21 | MembershipNumber = a credential | ● | ● (BD-2) | | | | tutorial / import | none | CLOSED |
| 22 | number uniqueness outside the aggregate | | ● (BD-NU) | | ● | ● | it spans persons | none | CLOSED |
| 23 | no stored status | | | ● | | | — | none | CLOSED |
| 24 | append-only facts | | | ● | ● (KnownAt) | | — | none | CLOSED |
| 25 | no Event Sourcing | | | ● | | ● (allowed) | no requirement needs it | none | CLOSED |
| 26 | **three-valued predicates** | | | ● | | | **no requirement; Election needs booleans** | — | **WITHDRAWN** (import-time normalisation) |
| 27 | Election boundary | ● (adopted) | | | | | §8 | none | CLOSED |
| 28 | Finance boundary | | | ● | | | — | none | CLOSED; DS-1 open |
| 29 | Audit boundary | | | ● | | | — | none | CLOSED |
| 30 | Identity boundary | | | ● | | | the merge act (§7) | none | CLOSED; DS-3 open |

## 2. Aggregate analysis (D1 / D2 / D3)

| Criterion | D1: one aggregate per (p,o) | D2: period aggregates + an external consistency mechanism | D3: a root + an external temporal-consistency policy |
|---|---|---|---|
| invariant ownership | INV-9, CFC and g1/g2 are owned in one place | INV-9 is owned by a saga / lock / reconciler outside any aggregate | split |
| consistency boundary | all periods of (p,o) | one period | root and periods are separate |
| transaction need | **atomic:** cause + its Trim facts on several periods; g1 checks every period | cross-aggregate transactions or eventual consistency | the same as D2 |
| concurrency | serialised per person and organisation (very low write rate) | per period. The guards need a distributed lock anyway | same |
| backdated corrections / annulment / Trim / CFC revival | local | cross-aggregate, multi-step | cross |
| identity semantics | MembershipId = the aggregate id; PeriodId = an entity id | PeriodId = the aggregate id; MembershipId = a grouping key | mixed |
| size / fact volume | ~1–2 facts per year per (p,o) | smaller | — |
| migration / persistence / testability | simple | complex (coordination tests) | complex |
| eventual consistency | none needed | transient overlaps possible, so reconciliation is needed | same |
| person merge | an explicit merge of two aggregates (rare, organ-decided) | the same | the same |

**Answer:**
- The four boundaries are distinct concepts: the **domain consistency boundary** (the set of facts that must change atomically to keep invariants true), the **transaction boundary**, the **database row boundary** and the **repository boundary**.
- Under **BD-I5 (any scope) + BD-SYNC = yes**, the facts that must change atomically are: (a) an Admit against all periods in scope (g1), and (b) a lifetime-enlarging fact together with its Trim consequences on other periods (g2, CFC).
- **The smallest set closed under these atomic changes is "all periods in the I5 scope".**
- **D1 (with scope = (p,o)) is therefore the minimal domain consistency boundary (PA).** The transaction, row and repository boundaries are implementation choices that follow it.
- **EM-VOC-001 is not used as an argument.** It merely agrees.
- **If BD-I5 = no invariant:** no fact changes more than one period, so **D2 is minimal.** g1, g2, ER and Trim disappear; the rest of the model is unchanged.
- **If BD-SYNC = no:** D2 + a reconciler is admissible, and D1 remains valid but not necessary.

## 3. INV-9 and CFC: proofs

**Definitions:**
- live facts per MA §2;
- the lifetimes `L(q)` come from `Res(live facts)`;
- `Scope` is the BD-I5 key.

**Assumptions (each classified):**
- **A1:** guards are evaluated synchronously on the scope's facts at record time. *Business:* BD-SYNC.
- **A2:** I5 is a rule in this scope. *Business:* BD-I5.
- **A3:** a total recording order `seq`. *Implementation constraint* (required for determinism).
- **A4:** each primary fact changes at most one bound of one lifetime. *Architecture* (the fact vocabulary is designed so).
- **A5 (CFC):** a Trim is live iff its cause is live. *Architecture*, justified by the counterexample.

**Note:** none of A3–A5 is a domain truth. They are architecture or implementation constraints chosen to make the business rule A2 enforceable. **INV-9 is therefore "enforced under assumptions", never "domain-proven".** The domain content is A2 alone.

**Induction:** P(F) = the live lifetimes in each scope are pairwise disjoint.

| Step (appending a fact α) | Case | Preservation |
|---|---|---|
| Admit(v) | new `[v,∞)` | g1 ⇒ every live lifetime in scope has `e ≤ v` ⇒ disjoint |
| End, a shrinking Correct | subset | trivial |
| enlargement of a **non-empty** L by α | refused, or every overlapping q′ is trimmed to `q′ \ L_new` (recorded as Trims caused by α) | `L_old ⊆ L_new`, `L_old ∩ q′ = ∅`, L_old ≠ ∅ ⇒ q′ ⊄ interior of L_new… ⇒ `q′ \ L_new` is one interval (**no split**) and disjoint from L_new |
| enlargement of an **empty** L | ER: refused on any overlap | disjoint |
| **Annul / Void of a cause c** (c has consequences T_c) | c's effect is removed (its target reverts) **and** T_c becomes not live (CFC), so the trimmed parts revive | **ER on the revival:** refused if any revived part overlaps a live lifetime. Otherwise the result equals the counterfactual (no c, no T_c) at that scope, which was disjoint by the induction hypothesis on the prefix order |
| Annul / Void of an Admit | removal (+ its consequences per CFC) | as above |
| Renew, ChangeType, Suspend, Restore | no lifetime change | trivial |

**⇒ INV-9: PA (A1–A5). I5 (≤ 1 period in lifetime at any t, per scope): MP from INV-9.**

**INV-CFC:** after annulling a cause c (not refused), coverage = the counterfactual coverage (F without c and T_c). **PA:** CFC removes exactly c and T_c, and Res is a fold over the remaining live facts, so it equals the fold of the counterfactual set. (The fold order is preserved because seq is fixed.)

**Computation:**
- **RV** `cascade3.py`, 200,000 random histories (time domain 3–9; up to 13 commands; admissions, ends, corrections, annulments of causes):
  - **naive** (Trim independent of its cause): **1,903 histories lose counterfactual coverage (CX)**, including scenario F;
  - **CFC + ER:** **0 losses, 0 overlaps.**
- **Earlier runs** (still valid for their scope):
  - `closure2` RV, 300,000 histories: ER 0 splits / violations;
  - `ratify4` EF, 444,604 histories;
  - `final2` EF, 153,920 histories.

## 4. Determinism of KnownAt

- `F_τ` = the facts with `recordedAt ≤ τ`, ordered by `seq` (a strict total order per Membership).
- Liveness is a function of `F_τ`. CFC and Annul/Void targeting are static references.
- Res is a pure fold, and **guards are not re-evaluated** in resolution: their outcomes are recorded as Trim facts or as refusals (no fact).

⇒ `KnownAt(φ,t,τ)` is a function of `F_τ` alone. **MP**, given append-only storage and a persisted seq.

**Four orders, kept distinct:**
- **domain truth** = Res over all live facts at valid time t;
- **historical knowledge** = Res over `F_τ`;
- **system recording order** = seq;
- **database transaction order** = implementation. It is used only to *assign* seq, never read as semantics.

**Same `recordedAt`:** ordered by seq. **Retries:** an idempotency key prevents a second fact (implementation requirement). **Distributed execution:** seq is assigned inside the Membership's serialised write (D1).

## 5. Annul vs Void vs Correct

| | Annul | Void | Correct |
|---|---|---|---|
| authority | the competent organ / appeal body | the registrar / data authority | the recording authority (+ the organ, if a decision attribute changes) |
| temporal effect | the target's effect removed from its effectiveAt; consequences per CFC | the same | values replaced |
| history / reporting | "decision X was set aside on …" | "record X was never valid" (excluded from statistics) | "X recorded with wrong attribute; corrected on …" |
| downstream events | `FactAnnulled` | `FactVoided` | `FactCorrected` |
| Election | may change past IsMember, so Election decides (DS-2) | the same | the same |
| Finance | may affect fees (DS-1) | the same | the same |
| identity | — | a wrong holder ⇒ Void + a new Admit | **cannot change the holder** |
| migration | — | removes legacy junk | normalises legacy dates (with a reason) |
| references | stay; target marked annulled | stay; target marked void | stay |
| KnownAt / TrueAt | TrueAt changes; KnownAt(τ < rec) unchanged | the same | the same |

**Domain commands:** separate (`Annul…`, `Void…`, `Correct…`).
**Domain facts:** separate kinds.
**Persistence:** a shared shape is allowed (implementation choice).

## 6. Authority evidence: reference or snapshot?

A **reference alone** (ruleRef + version) would suffice **only if** Governance guarantees immutable, versioned rule *and* organ-composition history **and** the check is recomputable deterministically.
- The second condition fails in practice: the actor's capacity at the time depends on mutable appointments.
- **Minimum stored evidence:** {actor, claimedCapacity, ruleRef, policyVersion, checkResult, checkedAt}. That is references + a result, **not** rule content. **AD.**

## 7. Practical scenarios A–J (mental execution against MA)

| Scenario | Execution | Semantic hack needed? |
|---|---|---|
| A normal admission → term → renew | Admit (g1, consent, authority) → segment → Renew → segment | no |
| B lapse → late renewal | the segment ends; Lapsed (IsMember false); a late Renew opens a new segment; **the gap stays** | no |
| C resignation | End(resignation, effectiveAt) | no |
| D re-entry | End → Admit (g1 sees e ≤ v) → a new PeriodId, the same MembershipId | no |
| E corrected ending | P1 end corrected later → g2 → Trim(P2, cause = the Correct) | no |
| F reverse the correction | Annul(Correct) → CFC: Trim(P2) not live → P2 revives → ER checks the revival → P1 reverts. **Coverage = the counterfactual** | **no (with CFC); the candidate failed here** |
| G void | Void(Admit) → the period is never real; excluded from statistics | no |
| H successful appeal | Annul(End{exclusion}) → enlarges P → g2 / ER | no |
| I migration with incomplete dates | attested Admit with a **normalised date + precision note**, recorded by a normalisation decision; predicates stay two-valued | no (three-valued logic withdrawn) |
| J person merge | an Identity equivalence → a Membership merge act (survivor id kept; the merged id resolvable); overlaps reconciled by facts | no (an explicit act) |

## 8. Election, Finance, Governance, Identity checks

| Event | Election | Finance | Governance | Identity |
|---|---|---|---|---|
| Trim | IsMember unchanged (coverage preserved) | `PeriodTrimmed`; fee policy DS-1 | — | — |
| CFC revival | IsMember unchanged (back to the counterfactual) | `TrimRevived` | — | — |
| Annul / Void | past IsMember may change; Election decides registration effects (DS-2) | may affect fees | evidence recorded | — |
| Suspend | Suspended published; IsMember unchanged | — | bans are separate | — |
| End | IsMember false from effectiveAt; FM-5 mapping (DS-2) | — | — | — |
| re-entry | (MembershipId, asOf) references are stable | — | — | — |
| type change | the CurrentType criterion only; **never an entitlement** | fee snapshot | — | — |

**The adopted-corpus audit finds no CONFLICT:** EM-VOC-001/002/003, EM-ENT-001…007, EM-EO-001…004, EM-FM-001…006 (deferred), EM-SEQ-001/002, EM-GOV-001…003, ADR_20261004_1549, ADR-T11, SM2, Manifesto ≠ Constitution and the delivery freeze are all **compatible**.

## 9. Minimality

| Concept | Class | If removed |
|---|---|---|
| Membership (MembershipId) | MUST EXIST | no enduring record; B′ breaks; INV-M breaks |
| Period (PeriodId) | MUST EXIST | episodes are not referenceable; the legal history is lost |
| Fact | MUST EXIST | no history, no KnownAt |
| seq | MUST EXIST | non-deterministic resolution |
| recordedAt | MUST EXIST | no KnownAt |
| decidedAt | USEFUL BUT OPTIONAL | DecidedBy and future-effective decisions are lost (null when unknown) |
| provenance | MUST EXIST | no validity basis (consent, rule, attested) |
| authority evidence | MUST EXIST | decisions are not explainable after Governance changes |
| term segment | MUST EXIST | lapse-gap erasure (CX) |
| type segment | USEFUL BUT OPTIONAL | needed only if BD-TYPE allows mid-period changes |
| restriction | MUST EXIST | no suspension |
| Resolution (the fold) | MUST EXIST | there is no other way to derive the state |
| Trim | MUST EXIST **iff** BD-REV = trim and I5 holds | otherwise NOT NEEDED |
| three-valued logic | **NOT NEEDED** (withdrawn) | — |
| Annul Trim | **NOT NEEDED** (CFC) | — |
| a stored status, a Number Registry, Event Sourcing | NOT NEEDED / IMPLEMENTATION CHOICE | — |

## 10. Requirements traceability: gaps found

- **Requirements invented by the architecture** (flagged; each needs a PO / owner nod): seq (A3, implementation); Trim (it follows from BD-REV); ER; CFC; the import-time date normalisation decision (who may normalise? that is DS-4 + a registrar role).
- **Missing requirements:** the import normalisation authority; the idempotency of retries; the reporting rule for voided records (excluded) and annulled ones (shown).
- **Duplicated / contradictory:** none.

## 11. ML and statistics

- ML did **not** materially improve the architecture. **It is not used.**
- Future advisory uses: duplicate person detection, identity-resolution suggestions, migration anomalies, legacy data quality, suspicious lifecycle patterns.
- **ML must never decide** membership, legal validity, authority, termination, annulment, entitlement, eligibility or legal truth.
- **Statistics: not available.** The generated counts are not population statistics.

---

## 12. Final challenge round (amendment)

### 12.1 New counterexamples and resolution

| CX | Minimal history | Failure | Classification | Resolution |
|---|---|---|---|---|
| CX-A | Admit P1@2 → End P1@4 → Admit P2@7 → **Correct P2 start→0** (supersedes P1 fully) → **Correct P1 end→5** | the ER test looked at P1's *base* bounds, not its *resolved* (empty) lifetime, so P2 was trimmed and **split** | specification precision | **"empty" = resolved-empty** |
| CX-B | Admit P1@1 → End@2 → Admit P2@3 → Correct P1 end→4 (trims P2 by [3,4)) → **Correct P2 start→2** | P2 moves **across its own cut**, so its lifetime fragments into `[2,3)+[4,∞)` | **a spec gap:** assumption A4 presumed cut-free targets | INV-SHAPE + post-condition guard |
| CX-C | Admit P1@2 → End@4 → Admit P2@7 → Correct P2 start→3 (trims P1 by [3,4)) → End P2@4 → **Annul(End P1)** | the annulment stretches P1 across its cut, so P1 fragments | the same gap, via annulment | the post-condition guard also applies to Annul / Void |

`multicause*.py` RV, removed-interval representation, guards as originally specified:
- CX-A family: 278 splits;
- CX-B family: 170 splits;
- CX-C family: 7 splits.

**With G\*** (accept iff the post-state satisfies INV-SHAPE ∧ INV-9): **0 splits, 0 overlaps, 0 recorded-counterfactual mismatches in 400,000 histories**, under both trim representations (absolute and relative).

### 12.2 The proof becomes simpler (safety)

- **Theorem (safety):** every reachable resolved state satisfies INV-SHAPE ∧ INV-9 ∧ INV-1.
- **Proof (MP, given G\* implemented as an atomic post-state check over the scope):**
  - **base:** ∅ satisfies them;
  - **step:** a fact is accepted only if the post-state satisfies them; refused facts change nothing.
- The earlier case analysis (Allen relations) now only shows **usefulness**: g2 trims on cut-free non-empty targets produce acceptable post-states (they never split), so the trim policy rarely forces a refusal. **It is not needed for safety.**
- **Assumptions:**
  - G\* sees all periods of the scope atomically (D1, or an equivalent serialisable mechanism);
  - BD-I5 fixes the scope.

### 12.3 CFC: counterfactual theorem and domain meaning

- **Theorem:** for an accepted Annul(c) or Void(c), `Res(F ∪ {Annul c}) = Res(F \ ({c} ∪ C(c)))`.
- **Proof (MP):**
  - liveness removes exactly c;
  - C(c) is stored on c, so it is removed with it;
  - every other fact keeps its liveness and its *recorded* consequences;
  - Res is a pure fold over live facts in a fixed seq order.
- **Scope of the theorem:** it is the **recorded** counterfactual. Later independent decisions keep their recorded effects; history is **not re-executed**, and guards are not re-evaluated. Legally, setting aside one decision does not silently re-make later decisions.
- **Domain classification:**
  - CFC is **derived from the domain meaning of Annul and Void:** setting aside a decision ex tunc removes *all its effects*, and a Trim is an effect of its cause.
  - It is therefore not a projection trick or a mechanism.
  - The **automatic revival** of P2 is legal truth **under the A1 annulment semantics** (BD-TERM4, LQ-2). If the law gave annulment only ex nunc effect, revival would not follow. **This is a legal question, already registered.**
- **Generalisation, tested:**
  - multiple causes on one period, consequence chains and nested causes: verified (RV);
  - **chains have depth 1:** a revival never trims (G\* refuses overlaps), and only primary enlarging facts generate trims;
  - Correct of a cause replaces its consequences with the Correct's own.

**The commission's CFC example is not reachable:** `P1 = [2020, 2027)` overlapping `P2 = [2025, 2030)` cannot be admitted (G\*). Analysed instead: P1 `[2020, 2025)`, P2 admitted 2025, Correct(P1 end → 2030) → P2 fully superseded → Annul(Correct) → P2 revived to `[2025, …)`, and P1 is back to 2025.

### 12.4 Trim identity (Options A / B / C)

| | A: Trim as its own fact | B: derived during resolution | **C: recorded on its cause** |
|---|---|---|---|
| KnownAt / deterministic replay | ✓ | ✗ unless the policy is versioned and guards re-run (circularity) | ✓ |
| audit / legal evidence | ✓ | weak | ✓ (cause + removed intervals) |
| reversal | it needs CFC linkage | automatic | **automatic** (it goes with its cause) |
| downstream events | its own id | none | id derived from (cause, period) |
| idempotency / migration / performance | ✓ | — | ✓ |

**Choice: C.** Trim is **removed as a fact kind**. Representation (relative removed interval vs absolute) is an **implementation choice**: both pass under G\*. Relative is recommended because it states the legal meaning.

### 12.5 Policy decision tree (does I5 remove the guards?)

| I5 | SYNC | REV | Aggregate (minimal) | G\* | g2 / Trim / CFC | Notes |
|---|---|---|---|---|---|---|
| yes (p,o) | transactional | trim | **D1** | yes (INV-9 + SHAPE) | yes | recommended |
| yes | transactional | refuse | D1 valid; **D2 + a DB exclusion constraint / serialisable** is also sufficient (a pure prohibition, no cross-period decision) | yes | no | D1 not proven minimal |
| yes | eventual | either | D2 + a reconciler; overlaps are *detected* and resolved by organ decisions (Annul / Correct facts; trims as their consequences) | becomes a detection rule | CFC still needed if reconciliation trims | — |
| no | — | — | D2 (D1 valid) | INV-SHAPE only | no | INV-9 dropped |
| class scope | as above | as above | D1 per (p,o) with a class-keyed G\* (or per (p,o,class)) | yes per class | yes | needs "class" to be a domain-real concept (no corpus evidence) |

**Answer:** changing I5 to "no" removes INV-9, g2, Trim and CFC. **Counterexample to "it removes everything":** under an *eventual* SYNC, CFC is still required whenever a reconciliation decision trims. **D1 is valid in every branch** (never wrong), so the aggregate choice does not block ratification.

### 12.6 The four synchronicity meanings

| Meaning | Status |
|---|---|
| domain / business ("there are never two valid memberships") | BD-I5 |
| aggregate invariant (the domain model enforces it) | D1 + G\* |
| transaction invariant (atomic per command) | the implementation of G\* |
| database constraint (an exclusion constraint) | an optional extra safety net (IMPLEMENTATION) |

**A business rule does not by itself require one aggregate.** With REV = refuse, a DB exclusion constraint or a serialisable transaction suffices. **D1 is required as a *domain* boundary only when the domain itself decides cross-period consequences (trim).**

### 12.7 I5 ontology

| Candidate concurrent "membership" | Domain reading | Basis |
|---|---|---|
| ordinary + honorary | **type / status of one membership** | EM-VOC-001: the Member concept carries *a* type |
| statutory / ex-officio | a **basis** (provenance `rule`) of one membership | path analysis |
| temporary + permanent | sequential periods | — |
| chapter / section / department | another organisation or a Governance unit (affiliation) | committee model |
| role-based | Governance role | EM-VOC-002 |
| multiple affiliations | Organisation context | EM-VOC-002 |

**I5 at scope (p,o):** strongly supported as domain truth (the adopted vocabulary + ontology). Final confirmation is BD-I5. **No evidence supports a class scope.**

### 12.8 Predicate truth table (the commission's rows)

| Lifetime | Term | Suspended | IsMember | InForce |
|---|---|---|---|---|
| yes | yes | no | T | T |
| yes | yes | yes | **T** (S2) | T |
| yes | no | no | F (lapsed) | F |
| yes | no | yes | F | F |
| no | yes | (n/a) | F | F (a paid term outliving the lifetime does not count) |

Consistent with EM-ENT (Election evaluates eligibility from the published `IsMember` and `Suspended`; Membership infers no entitlement).

### 12.9 Reversal combinations

| Combination | TrueAt | KnownAt | Audit | Finance / Election | Statistics |
|---|---|---|---|---|---|
| Annul(End) | never ended (G\* checked) | changes from recordedAt | shows the annulment | `TrimRevived` if any; Election re-reads (DS-2) | the ending is not counted |
| Void(Admit) | never a member | changes | shows the voidance | fees on the period → DS-1 | excluded |
| Correct(Admit) | corrected start (G\*) | changes | shows original + correction | possible trims | corrected |
| Annul(Correct) | original values + their consequences (CFC) | changes | shows it | revival events | — |
| Void(Correct) | as Annul(Correct), but "the correction was never valid" | changes | distinct reason class | same | — |
| Correct(End) | the corrected end (G\*) | changes | shows it | possible trims | — |

**Annul and Void remain distinct** (authority, reporting, legal narrative). They share the same *effect algebra*.

### 12.10 Identity merge

- `A ≡ B` is an **Identity decision**.
- Membership then executes **ReconcileMemberships(survivor, merged)**: a Membership command (G\* over the union) with organ decisions for any overlaps.
- **Identity semantics stay out of Membership.**

### 12.11 Migration: date normalisation is itself a domain decision

- The normalisation rule and its authority (BD-NORM) require a **business decision**: which precision maps to which date, and who may decide.
- **Nothing is manufactured.** When no defensible date exists, the fact stays an attested record **outside** the predicates (not imported as a membership period), pending an organ decision.

### 12.12 Verification summary V1–V12

| V | Property | Class |
|---|---|---|
| V1 | ended ⇒ not in force | MP |
| V2 | lapse ⇏ termination | MP |
| V3 | term gaps preserved | MP |
| V4 | KnownAt deterministic | MP (given a persisted seq, append-only, guards not re-evaluated) |
| V5 | CFC (recorded) counterfactual | MP + RV (0 / 400,000) |
| V6 | INV-9 under the scope | MP via G\* (PA: atomic scope) + RV |
| V7 | re-entry identity | AD (the identity criteria) |
| V8 | Annul / Correct reversal | MP (the fold) + RV |
| V9 | no split | MP via INV-SHAPE in G\* + RV. **Counterexamples CX-A/B/C were found and closed** |
| V10 | acyclic predicates | MP (the DAG) |
| V11 | no entitlement inference | AD + ADOPTED |
| V12 | history reconstructable | MP (append-only + seq) |

**Minimality changes:**
- Trim is removed as a fact kind (a consequence on its cause);
- g1, ER and ER\* are **subsumed by G\***;
- INV-SHAPE is added (one invariant replaces three guards);
- everything else is unchanged from §9.

**ML / statistics:** unchanged. ML is not used for semantics; generated counts are not statistics.

---

## 13. Final closure round

### 13.1 Counterexample: orphaned consequences (CFC incomplete)

- **Model:** `orphan.py` / `orphan2.py`, RV over 120,000 random histories per rule. It covers Admit, End (and end changes), Correct targeting causes **and Corrects**, and Annul / Void of causes and of Corrects.
- **Property INV-COV:** the union of resolved lifetimes equals the union of live base claims, i.e. **no membership instant is lost**.

| Consequence-liveness rule | INV-COV violations |
|---|---|
| naive (live iff the cause is live) | 576 |
| **CFC as stated in §12** (+ not superseded by a Correct) | **420 (CX)** |
| **CFC+** (+ the cut lies inside the cause-target's *base* claim) | **0** |

**Minimal failure pattern:** a cause extends P1 and trims P2 → a **new End fact** shortens P1 again. The End is a new primary fact, **not** a Correct of the cause, so the cause is neither annulled nor superseded, and its cut on P2 survives with nothing claiming the region.

**Domain reading:** a supersession exists only while the superseding episode actually claims the region. When the claim goes, the superseded episode's own admission is effective again there. **CFC+ is the precise form of "a consequence follows its cause".**

### 13.2 Non-circularity of CFC+

- The justification test uses **base(target)**, which is computed from Admit / End / Correct values only, never from cuts.
- Using the *resolved* lifetime would make two periods' cuts depend on each other (a fixpoint). That is **rejected**.

**Order:** facts → liveness → values → base → live consequences → lifetimes → predicates. **Acyclic (MP).**

### 13.3 INV-COV: proof sketch (PA) + RV

- Let t lie in some live base claim. Every live cut at t on a period q is justified by the base claim of its cause's target p at t.
- **Assume** for contradiction that every claim covering t is cut at t. Then the "cuts" relation among the claimants of t is a total function claimant → justifier, which contains a cycle (q cut by p, p cut by … q).
- **A cycle requires** some period to have been trimmed while it already held a cut, or to have trimmed while it was itself cut.
- **g2 forbids this:** only **cut-free** non-empty targets generate consequences. A later enlargement over a region held by another claimant is refused by G\* (INV-9).
- Hence no cycle exists, so some claim at t is uncut, so t stays covered. **PA** (assumptions: g2 cut-free condition, G\* atomic, CFC+). RV: 0 / 120,000.

### 13.4 G\*: classification and well-foundedness

| Question | Answer |
|---|---|
| Is G\* domain truth? | **No.** The **invariants** (INV-EP, INV-9, INV-1, INV-COV) are the domain truth. G\* is their **enforcement algorithm** (application / domain-service level) |
| Circular? | **No.** `ResolveCandidate(S,f) = Res(F_S ∪ {f⁺})` where `f⁺`'s consequences are computed from `Res(F_S)`. `Accept(S,f) ⇔ I(ResolveCandidate(S,f))`. `Commit(S,f) = F_S ∪ {f⁺}`. Res depends only on facts, never on acceptance. Well-founded by induction on seq (**MP**) |
| Business form | "The organisation's record never holds a contradictory membership history": a violating fact is **not recorded** (refused). That is the domain rule; G\* is how |

### 13.5 INV-EP (renamed from INV-SHAPE): classification

- **Domain-definitional, not a mere convenience.** A legal episode runs from admission to ending.
  - An interruption *within* an episode does not exist as a legal concept: suspension is a restriction, lapse is a term gap, and a genuine break is an End + a new Admit.
  - A lifetime "hole" could only come from another episode's precedence, which would mean the middle belongs to that other episode, and the resumption would require its own admission.
- **Hence one interval per period**, and G\* refuses facts that would fragment one.

### 13.6 CFC stress cases A–K (RV + reasoning)

| Case | Result |
|---|---|
| A cause → a trim | ✓ |
| B one cause → several trims | ✓ (all die with the cause) |
| C several causes → the same period | ✓ (each cut justified independently; relative intervals) |
| D Correct(cause) | the cause is superseded → its cuts die; the Correct carries its own (g2 at its record time) ✓ |
| E Annul(cause) | cuts die, the region revives; G\* checks ✓ |
| F Void(cause) | same as E; different reason class ✓ |
| G Correct(c), then Annul(Correct) | c's values and c's own cuts revive (if c is not otherwise superseded); the Correct's cuts die ✓ |
| H Correct(Correct(c)) | the value chain: the latest live Correct wins; only its cuts are live ✓ |
| I Correct, then annulment of the original c | the root is not live, so the Correct is not effective and all cuts die ✓ |
| J Annul, then a new Correct | the new Correct targets a not-live fact → no effect. **Rule:** Correct requires a live target (G\* refuses otherwise) ✓ |
| K chains | a revival never generates cuts (only primary enlargements under g2) → **depth 1**; no orphans under CFC+ ✓ |

**RV:** 0 coverage violations across these combinations (`orphan2.py`, cfcplus). **No residual counterexample found.**

### 13.7 Temporal scenarios

| # | Scenario | Clocks | KnownAt |
|---|---|---|---|
| 1 | decided today, effective yesterday | decidedAt = today, effectiveAt = yesterday | TrueAt(yesterday) changes; KnownAt(τ < recordedAt) does not |
| 2 | decided today, effective next month | effectiveAt in the future | no change until then; DecidedBy shows the pending effect |
| 3 / 4 / 5 | backdated admission / termination / correction | effectiveAt < recordedAt | as 1 |
| 6 | appeal after an effective termination | Annul(End) (ex tunc per LQ-2) | KnownAt shows both states by τ |
| 7 | import with an unknown exact date | attested + a BD-NORM normalisation decision, **or** kept outside the predicates | no invented date |
| 8 | identical `recordedAt` | ordered by `seq` | deterministic |
| 9 | different DB transaction timestamps, same domain order | **seq is authoritative**; DB timestamps are never read as semantics | deterministic |
| 10 | a retried command | the idempotency key → one fact | deterministic |

**Proof:** `KnownAt(φ,t,τ) = φ(Res(F_τ))`; `F_τ` = the seq-ordered prefix with `recordedAt ≤ τ`; Res is pure. **MP**, given append-only storage, a persisted seq and idempotency.

**Four times, separated:** domain valid time · domain decision time · system recording time (`recordedAt` + `seq`) · technical DB time (implementation only).

### 13.8 Annul / Void / Correct (final)

| | Annul | Void | Correct |
|---|---|---|---|
| legal meaning | a valid decision set aside (ex tunc; ex nunc = LQ-2) | never real | real but mis-recorded |
| past queries | change (TrueAt), KnownAt is preserved | the same | the same |
| statistics | "annulled" (counted as a historical decision) | excluded | the corrected values |
| audit | the reason + the organ | the reason + the registrar | original + correction |
| consequences | the target's die (CFC+) | the same | the target's die; the Correct carries its own |
| can enlarge a lifetime / trigger G\* | yes / yes | yes / yes | yes / yes |
| authority | the competent organ (appeal) | the registrar / data authority | the recording authority (+ the organ for decision attributes) |
| `decidedAt` | required (it is a decision) | optional (a finding) | optional |
| can itself be targeted | Annul / Void yes; Correct no (its "values" are its target reference) | Annul yes (wrongful voiding); Void yes | Correct, Annul and Void yes |

**Distinct in meaning; a shared storage shape is allowed.**

### 13.9 Minimality (final)

| Element | Class |
|---|---|
| Membership, Period, Fact | MUST |
| consequence (cut) | OPTIONAL (only under the trim policy) |
| resolution (incl. CFC+) | MUST |
| G\* | IMPLEMENTATION / domain service (it enforces MUST invariants) |
| INV-EP, INV-COV | MUST (definitional) |
| INV-9 | OPTIONAL (business, BD-I5) |
| provenance, authority evidence, recordedAt, seq | MUST |
| `decidedAt` | SHOULD |
| term segments, restriction intervals | MUST |
| type segments | OPTIONAL (BD-TYPE) |
| MembershipNumber | SHOULD (a credential; uniqueness EXTERNAL / IMPLEMENTATION) |
| published facts | MUST (the contracts) |
| Standing | EXTERNAL to the core (E0b-S) |
| Eligibility | EXTERNAL (Election) |

### 13.10 ML / statistics

Unchanged. Computational results are labelled per run: proof, exhaustive verification, random verification. **Simulation frequencies are not statistics about members.** ML may detect or recommend (duplicates, migration anomalies); it never decides.

---

## 14. Final principal review round

> Scope: no research is reopened. This section formalises CFC+, records the counterexamples found while doing so, and states the status of each claim. Machine evidence comes from scratchpad models (Python, bounded); it is evidence, not code of the product.

### 14.1 Counterexamples found in this round (minimal, with correction)

Verification of the §13 model found **two defects in the consequence generator g2** and **one defect in the CFC+ justification rule**. All three are corrected below. None changes the ontology, the aggregate or a context boundary; all three are corrections of the resolution / enforcement rules.

**CX-14a: g2 computed consequences against the wrong state (completeness defect)**

| Item | Content |
|---|---|
| Initial state | P1 admitted @0, End @2; P2 admitted @2 |
| Fact sequence | Correct(End P1 → 4) (cuts P2 [2,4)); Correct(Correct → 3) |
| Expected | the second correction is accepted; P1 [0,3), P2 [3,∞) |
| Actual | refused: g2 computed the cut as the delta against the *current* resolved state, but superseding the cause kills the cause's cuts, so the overlap re-appeared and G\* refused |
| Root cause | the consequences of a Correct were not derived from the target's *new base claim* |
| Correction | the consequences of a Correct are computed from the target's new base claim, and the superseded cause's consequences are inherited, clipped to the new base claim |
| Status | scenarios C, D pass after the correction |

**CX-14b: mutual cuts lose coverage (safety defect, INV-COV)**

| Item | Content |
|---|---|
| Initial state | empty |
| Fact sequence (5 facts) | Admit P1 @0 · End P1 @1 · Admit P2 @2 · End P1 @3 (enlarges P1; cuts P2 [2,3)) · End P2 @4 |
| Expected | INV-COV: coverage [0,4) |
| Actual | base claims {P1 [0,3), P2 [2,4)}; live cuts: P2 cut [2,3) by P1's End, **and** P1 cut [2,3) by P2's End; resolved {P1 [0,2), P2 [3,4)}: **[2,3) is lost** |
| Root cause | g2 generated consequences over the target's **whole** new base claim, so a target that had ceded a region to another period re-cut the period that cut it |
| Correction | g2 generates consequences only over **Δ = NewBase(target) − PrevBase(target) − (target's own live cuts)**: only the region the target newly claims, never a region it has ceded |
| Status | not reproducible after the correction (all runs in §14.6) |

**CX-14c: all-or-nothing justification refuses a legitimate End (completeness defect)**

| Item | Content |
|---|---|
| Initial state | P1 admitted @0, End @2; P2 admitted @2 |
| Fact sequence | Correct(End P1 → 4) (cuts P2 [2,4)); End P1 @3 |
| Expected | (scenario I) accepted; P1 [0,3), P2 [3,∞) |
| Actual | refused: with "a cut is live only if **wholly** inside the cause-target's base claim", the cut [2,4) died completely when P1's base shrank to [0,3), so P2 [2,∞) overlapped P1 [0,3) |
| Root cause | justification was binary (⊆) instead of proportional |
| Correction | **the live part of a cut is its intersection with the cause-target's base claim** (§14.2, LiveCut) |
| Status | scenario I passes; all runs in §14.6 report 0 violations |

The same correction also removed a residual random failure (1 / 120,000, a 12-fact history mixing Correct, Void and End) that the intermediate model still had.

### 14.2 Formal definitions

Let `F = ⟨f₁,…,fₙ⟩` be the fact log of one Membership in `seq` order. Each fact has a kind, an optional target index, an optional period `pid`, a value (a time point or ∞), and, for lifetime-defining facts, a frozen consequence set `cons(f) ⊆ PeriodId × Interval` recorded at commit.

```text
live(i)        ⇔ ¬∃ j > i : kind(j) ∈ {Annul, Void} ∧ target(j) = i ∧ live(j)
                 (well-defined: recursion only on larger indices of a finite log)
root(i)        = i if kind(i) ≠ Correct, else root(target(i))
active(i)      ⇔ live(i) ∧ (kind(i) ≠ Correct ∨ active(target(i)))     (whole chain live; corrected in §15.1, was live(root(i)))
superseded(i)  ⇔ ∃ j > i : kind(j) = Correct ∧ target(j) = i ∧ live(j)
value(i)       = value(max{ j > i : kind(j) = Correct ∧ target(j) = i ∧ live(j) })  if that set is non-empty
                 v(i)                                                             otherwise

BaseClaim(q,F) = ∅                         if q's Admit a is not live
               = [value(a), e)             otherwise, with
                 e = max(value(a), value(last live End of q))  if q has a live End, else ∞

Cause(c)            = the fact that carries consequence c
Target(c)           = pid(root(Cause(c)))           (the period whose claim c protects)
Consequences(f,F)   = cons(f)                       (frozen at commit; never recomputed)
Cut(c,q,F)          = the interval of c on q
LiveCut(c,F)        = Cut(c) ∩ BaseClaim(Target(c),F)   if active(Cause(c)) ∧ ¬superseded(Cause(c))
                    = ∅                                 otherwise
ResolvedLifetime(q,F) = BaseClaim(q,F) − ⋃ { LiveCut(c,F) : c on q }
Coverage(F,t)         ⇔ ∃ q : t ∈ ResolvedLifetime(q,F)
```

**g2 (consequence generator; trim policy only; computed from the pre-state S at commit):**

```text
for a lifetime-defining fact f on target p:
  Δ    = BaseClaim(p, S·f) − BaseClaim(p, S) − ⋃ { LiveCut(c,S) : c on p }
  cons(f) = { (q, Δ ∩ ResolvedLifetime(q,S)) : q ≠ p, non-empty }
          ∪ (if f = Correct(g): { (q, x ∩ BaseClaim(p,S·f)) : (q,x) ∈ cons(g), non-empty })
```

### 14.3 Which facts affect BaseClaim

| Fact | Changes BaseClaim? | Changes resolved lifetime? | Can create a consequence? | Can revive a consequence? |
|---|---:|---:|---:|---:|
| Admit | yes (creates it) | yes | no (a backdated Admit into a live lifetime is refused by G\*) | no |
| End | yes (sets the end) | yes | only where it **enlarges** the claim (Δ ≠ ∅) | no |
| Correct(Admit) | yes (start) | yes | yes, where an earlier start enlarges the claim | yes (inherits the superseded fact's consequences, clipped) |
| Correct(End) | yes (end) | yes | yes, where a later end enlarges the claim | yes (as above) |
| Annul / Void (End) | yes (the end reverts, possibly to ∞) | yes | no (consequences are created only by lifetime-defining facts) | yes: the superseded fact's consequences become live again, re-clipped to the base claim; G\* still checks the result (scenario H) |
| Annul / Void (Correct) | yes (the value reverts) | yes | no | yes (as above) |
| Annul / Void (Admit) | yes (the claim disappears) | yes | no | no; cuts *caused by* that period clip to ∅ |
| Renew / term extension | **no** (term segments) | no | no | no |
| Suspend / Restore | **no** (restriction intervals) | no | no | no |
| ChangeType | **no** (type segments) | no | no | no |

### 14.4 Non-circularity (dependency DAG)

```text
F (facts, seq-ordered)
  └─► live            recursion on strictly larger indices (finite ⇒ well-founded)
        └─► active, superseded
              └─► value          recursion along the Correct chain (strictly larger indices)
                    └─► BaseClaim         uses only live Admit/End values; no cut, no lifetime
                          └─► LiveCut     uses stored cons(f) + BaseClaim(Target) + active/superseded
                                └─► ResolvedLifetime
                                      └─► Coverage, InLifetime, Ended, InForce, IsMember, …
cons(f) itself = g2(S) where S is the strict prefix before f   (well-founded on seq)
G* = Accept ⇔ I(Res(S·f⁺))                                     (Res never reads G*)
```

**There is no edge from ResolvedLifetime or LiveCut back to BaseClaim.** The cycle `BaseClaim → Cut → ResolvedLifetime → BaseClaim` is impossible by construction. **Status: PROVEN** (by inspection of the definitions: every recursion is on a strictly larger index or a strictly earlier prefix).

### 14.5 INV-COV (coverage neutrality): exact statement

INV-COV is **not** "coverage never changes". Coverage legitimately changes when a real or legal fact changes a base claim: an Admit, an End, a Correct, an Annul or a Void. The conservation property is about **consequences only**:

> **INV-COV.** For every reachable fact log F: ⋃_q ResolvedLifetime(q,F) = ⋃_q BaseClaim(q,F).
> Equivalently, for all t: Coverage(F,t) ⇔ ∃ q: t ∈ BaseClaim(q,F).
> Consequences move the **attribution** of an instant from one period to another; they never decide **whether** the person was a member at that instant.

**Corollary (what may change coverage):** coverage at t changes between F and F·f only if `⋃ BaseClaim` changes at t, i.e. only through a fact that changes a base claim (§14.3, column 1). Renew, Suspend, Restore and ChangeType can never change coverage (they change InForce / Suspended / CurrentType, not membership-lifetime coverage).

**Proof status:**
- `⊆` (no consequence creates coverage): **PROVEN**, since ResolvedLifetime(q) ⊆ BaseClaim(q) by definition.
- `⊇` for the enforced system: **PROVEN by construction**. INV-COV is part of the acceptance predicate I of G\*, the empty log satisfies it, each accepted commit is checked, and Res is a pure function of the log (nothing changes between commits). Induction on commits gives INV-COV for every reachable F. This requires G\* to be atomic with the commit (§14.12).
- **That g2 + CFC+ never need G\* to refuse a command *because of* INV-COV** (i.e. the safety net never fires): **VERIFIED** on the exhaustive bounded spaces and **VERIFIED-BOUNDED** on random histories (§14.6). In 120,000 random histories with Correct(Admit), the refusal count is **identical with and without** INV-COV in I (17,697 both), so the gate never fired. **Not PROVEN** in general.

### 14.6 Adversarial histories A–N and machine verification

**Scripted histories (commission §6), final model:**

| ID | History | Expected | Result |
|---|---|---|---|
| A | one cause / one cut; annul the cause | P2's coverage restored | PASS |
| B | one cause cuts P2 and P3; annul | all and only its cuts disappear | PASS |
| C | two causes cut P2; annul A | B's cut survives | PASS |
| D | Correct(cause) | the cause's cuts are replaced by the correction's (inherited + Δ) | PASS |
| E | Correct(Correct(Correct)) | no orphaned consequence | PASS |
| F | Annul(Correct) | back to the original | PASS |
| G | Void(Correct) | back to the original | PASS |
| H | Annul(original) after Correct | P1 re-opens; would overlap P2 ⇒ refused by G\* | PASS (refused) |
| I | End after Correct | P2 regains the region P1 gives up | PASS (was CX-14c) |
| J | Annul that End | back to the corrected state | PASS |
| K | Correct(End) shortening | no consequence | PASS |
| L | re-entry, then modify P1 historically | P2 survives | PASS |
| M | late renewal | the lapse gap is preserved | **PROVEN** (definitional: TermCovered = ∃ segment ∋ t; a renewal adds the segment it grants and fills no gap; Renew does not touch BaseClaim, §14.3). Not part of the lifetime harness. |
| N | long reversal chain | all invariants | PASS |
| O | Correct(Admit P2) earlier into P1 | P1 trimmed; coverage kept | PASS |
| P | Annul that Correct(Admit) | P1 regains the region | PASS |
| Q | Correct(Admit P1) later | no consequence | PASS |
| R | Correct(Admit P2) before P1's start (absorbs P1 entirely) | coverage kept; P1 empty **or** refused | PASS with both policies (see BD-REV-ER below) |

**Machine verification of the final model** (invariants checked after every step: INV-COV, INV-EP, INV-9, no live cut outside its cause-target's base claim, no live cut of a dead or superseded cause):

| Run | Alphabet | Histories | Violations | Status |
|---|---|---:|---:|---|
| exhaustive, T=3, ≤4 facts | Admit, End, Correct(End/Correct), Annul, Void | 73,167 | 0 | VERIFIED |
| exhaustive, T=4, ≤4 facts | same | 171,500 | 0 | VERIFIED |
| exhaustive, T=3, ≤5 facts | same | 2,121,843 | 0 | VERIFIED |
| random, seed 77 | same | 120,000 | 0 | VERIFIED-BOUNDED |
| random, seed 991 | same | 300,000 | 0 | VERIFIED-BOUNDED |
| random, seed 77, + Correct(Admit) | + Correct(Admit) | 120,000 | 0 (with or without the INV-COV gate) | VERIFIED-BOUNDED |
| random, seed 991, + Correct(Admit) | + Correct(Admit) | 300,000 | 0 | VERIFIED-BOUNDED |
| exhaustive, T=3 / T=4, ≤4 facts, + Correct(Admit) | + Correct(Admit) | 73,167 / 171,500 | 0 / 0 | VERIFIED |
| all four Correct(Admit) runs repeated with ER in I (BD-REV-ER = refuse) | + Correct(Admit) | 120,000 · 300,000 · 73,167 · 171,500 | 0 | VERIFIED / VERIFIED-BOUNDED |

("VERIFIED" here means exhaustive over the stated finite space only; it is not a proof for unbounded histories.)

**BD-REV-ER (absorbing correction; a business policy, not a contradiction):** when a correction would leave another period with a live Admit but an **empty** lifetime (history R), the model is consistent either way: (a) accept, and the absorbed period remains in the history with an empty lifetime; or (b) refuse (ER), and require an explicit Annul/Void of the absorbed period's Admit first. Both satisfy INV-COV in all runs. **Recommendation: (b) refuse**, because a period that is admitted but never in its lifetime has no domain meaning without a decision saying so. This keeps the earlier ER test in the blueprint.

### 14.7 INV-EP (re-evaluated)

**Classification: a domain invariant that follows from the ontology; not an implementation convenience.**

- **Domain reason:** a Membership Period is defined as one legal episode, from one admission act to its ending. Every way the domain interrupts membership already has its own fact:
  - **dues not paid** ⇒ a lapse (term gap; the episode continues; INV-3);
  - **rights restricted** ⇒ a suspension (a restriction interval; the person remains a member);
  - **the relationship stopped** ⇒ an End, and returning requires a new admission act ⇒ a new period.
- **Consequence:** a non-contiguous lifetime would mean "one admission act, interrupted without an End". No fact in the domain produces that; it could arise only as an artefact of trimming (CX-B/C).
- **The test:** if the business ever needs "one admission, interrupted without an End and resumed without a new admission", INV-EP would become a policy. No requirement asks for this (EM-VOC-001; requirements matrix). The name INV-EP states the concept, not the representation.
- **Not legal:** whether a statute treats re-entry as a new admission is a legal input to *which facts are recorded* (LQ-3), not to the shape of a period.

### 14.8 D1 (final table)

| Policy | D1 (one aggregate per (p,o)) | D2 (period aggregates + set constraint) | D3 (root + external temporal policy) | Minimal | Why |
|---|---|---|---|---|---|
| I5 = (p,o), **trim** (recommended) | valid; enforces INV-9 / INV-COV / CFC+ atomically | **invalid** without distributed transactions: a fact on P1 writes consequences on P2 | valid only if the policy service locks (p,o), i.e. D1 in disguise | **D1** | a consequence and its cause must commit atomically across periods |
| I5 = (p,o), **refuse** | valid | valid with a set-based constraint (exclusion constraint or serialised (p,o) lock) | valid | D1 ≈ D2 | no cross-period write; only a cross-period check |
| no I5 (overlaps allowed) | valid | valid | valid | **D2** | no cross-period invariant remains except INV-M |
| I5 = (p,o,type) | valid | valid with a per-type constraint | valid | D1 per (p,o) or D2 | depends on BD-I5 |

- **Semantic validity:** D1 represents every concept (Membership, Period, Fact, consequences) — VERIFIED by the scenarios.
- **Consistency capability:** D1 can enforce every cross-period invariant inside one transaction — ARCHITECTURE.
- **Minimality:** D1 is minimal **for the recommended policy (trim)**, not universally — ARCHITECTURE.
- **Why D1 is chosen:** the identity and lifecycle reasons hold in every branch (a PeriodId exists only within a Membership), D1 is correct in every branch, and it is minimal in the recommended one. **D1 is not claimed to be universally necessary.**

### 14.9 I5 (final)

| Case | What it is | Effect on I5 = (p,o) |
|---|---|---|
| honorary membership | a **Membership Type** (or a type segment) | none: a type, not a second period |
| statutory membership | a **provenance** (rule-based admission) | none |
| ex-officio membership | a provenance (rule: office ⇒ member) + Governance owns the office | none; if the office and an ordinary membership coexist, it is one membership with two legal bases (provenance), not two periods |
| membership classes | Membership Types | none |
| organisational units (sections, chapters) | **section enrolments**, a separate relationship (deferred) or separate Organisations | none at (p,o) for the parent |
| committee roles | **Governance** (not Membership) | none |
| temporary memberships | a period with an End or a fixed term | none |
| parallel legal bases | provenance of the one period | none |

**Domain reason:** a person is either a member of an organisation at t or not; the membership relationship is one per (p,o) (INV-M). Everything that looks like parallel membership is a **type**, a **provenance**, a **different relationship** (section enrolment, committee role) or a **different organisation**. **Classification: BUSINESS (BD-I5), recommendation (p,o).** If the PO answers that two legally distinct, simultaneously valid memberships exist in one organisation, the answer is a separate relationship type, not overlapping periods; D1 still holds (§14.8).

### 14.10 G\* (layering)

```text
DOMAIN TRUTH          Membership, Period, facts, Annul/Void/Correct semantics   (ontology; no G*)
    ↓
INVARIANTS            INV-M, INV-1, INV-EP, INV-9 (scope BD-I5), INV-COV, INV-2, INV-3, INV-12
    ↓
CANDIDATE RESOLUTION  Res(S·f⁺): pure function of facts (§14.2); g2 builds f⁺ under BD-REV
    ↓
ENFORCEMENT / G*      Accept ⇔ I(Res(S·f⁺)); else refuse with the violated invariant
    ↓
COMMIT                append f⁺ atomically with the expected aggregate version
```

- **The domain does not depend on G\*:** Res and the predicates are defined without it (§14.4).
- **G\* does not define meaning:** it only refuses states the invariants forbid.
- **G\* is replaceable:** any mechanism that guarantees "only logs satisfying I are committed" preserves the same invariants. For example: a database exclusion constraint for INV-9 under the refuse policy; a serialised (p,o) worker; a check-then-append with optimistic versioning. **What is not replaceable is I itself.**

### 14.11 Migration safety (classification)

| Legacy evidence | Class | Treatment |
|---|---|---|
| missing admission date | **unresolved legacy evidence** | attested Admit with `effectiveAt = unknown` is **not** allowed in predicates; the record is kept as evidence, and a BD-NORM decision (a dated normalisation by an authorised role) is needed to create a period |
| approximate dates (year / month only) | **normalised** | normalisation by a recorded decision (BD-NORM); precision kept as metadata |
| missing authority | **attested** | `authority = unknown`; never fabricated |
| missing consent | **attested** | `declaration = unknown`; LQ-1 decides whether that blocks recognition |
| missing termination reason | **attested** | End(kind = `legacy-unknown`) |
| duplicate Members (same person, two records) | **unresolved** until Identity decides (DS-3) | dry-run report; merged only by an explicit reconciliation fact |
| duplicate memberships (same (p,o)) | **deterministic** if the periods are disjoint (one Membership, several periods); **unresolved** if they overlap | overlap ⇒ report, never auto-trim |
| legacy status fields | **deterministic** (mapping table) | reinterpreted as attested facts; `expired` derived, not stored |
| legacy fee fields | **deterministic** into Fee (E0b-S), **unresolved** where the two fee truths disagree | reconciliation report |
| historical election links | **none**: Election's own data, untouched | — |
| historical finance links | **none**: Finance's own data, untouched | — |

### 14.12 Implementation realism and concurrency

**Can two concurrent commands produce an invalid state? Yes, unless commits are serialised per Membership.**

Example: two commands on the same Membership each pass G\* against the same snapshot (e.g. an Admit and an Annul of an End that re-opens the earlier period). Each is valid alone; together they violate INV-9.

**Required mechanism:**
- optimistic concurrency on the Membership aggregate: the append carries the expected `seq` / version, `UNIQUE(membership_id, seq)`; on conflict, reload and re-run G\* (not just retry the write);
- f and its consequences in the same transaction;
- an idempotency key per command;
- an outbox for integration events (PeriodTrimmed, TrimRevived, …).

**Outside the aggregate:**
- INV-M (one Membership per (p,o)): `UNIQUE(organisation_id, person_id)` at creation;
- membership-number uniqueness (BD-NU): a set-based constraint per organisation.

These are **database constraints that enforce a domain rule**; they are not the domain rule.

| Concern | Feasible? | Note |
|---|---|---|
| transaction boundary | yes | one Membership per transaction |
| sequence assignment | yes | per-aggregate counter under the version check |
| candidate resolution | yes | a few facts per year; Res is O(n²) in the log and trivially cheap at that size |
| historical queries | yes | KnownAt = Res over the prefix with `recordedAt ≤ k` |
| tenant isolation | yes | organisation_id on every row; global scope |
| persistence shape | yes | facts table (append-only) + consequences (json or child rows) + materialised periods (cache, not authoritative) |
| projections / integration events | yes | rebuilt from facts; outbox |
| migration | yes | dry-run first; §14.11 |
| test strategy | yes | the A–R scenarios as RED tests; a property test with the same generator; architecture tests for the boundaries |

### 14.13 Mathematical status table

| Claim | Status |
|---|---|
| CFC+ is non-circular (§14.4) | PROVEN |
| ResolvedLifetime ⊆ BaseClaim | PROVEN |
| INV-COV holds for every committed log (G\* atomic, I ∋ INV-COV) | PROVEN (by construction) |
| g2 + CFC+ never need the INV-COV gate (§14.5) | VERIFIED (bounded exhaustive) · VERIFIED-BOUNDED (random) |
| INV-EP, INV-9 hold for every committed log | PROVEN (by construction: in I) |
| scripted histories A–L, N, O–R | VERIFIED (each is a single finite case) |
| M: late renewal preserves the gap (INV-12) | PROVEN (definitional) |
| INV-2, INV-3 | PROVEN (definitional) |
| KnownAt determinism | PROVEN (Res is a pure function of the prefix; append-only) |
| Annul / Void / Correct are distinct | DERIVED (different targets, provenance and statistics treatment, §13.8) |
| INV-11 no entitlement from Membership | DERIVED (ADOPTED ADR_20261004_1549 / EM-ENT) |
| INV-EP is a domain invariant | ARCHITECTURE (justified from the ontology, §14.7) |
| D1 | ARCHITECTURE (minimal for the trim policy only) |
| I5 scope (p,o) | BUSINESS (BD-I5), RECOMMENDATION (p,o) |
| trim vs refuse | BUSINESS (BD-REV) |
| absorbing correction (ER) | BUSINESS (BD-REV-ER), RECOMMENDATION refuse |
| ex tunc / ex nunc annulment | LEGAL (LQ-2) |
| optimistic versioning, constraints, outbox | IMPLEMENTATION |

### 14.14 Minimality (final)

| Concept | If removed, what fails |
|---|---|
| Membership | INV-M; the identity across re-entry (same MembershipId) |
| Period | the episode semantics (INV-EP); re-entry; FirstAdmission vs CurrentAdmission |
| Fact | INV-6 / INV-14 (append-only history; KnownAt) |
| BaseClaim | CFC+ becomes circular (justification against resolved lifetimes, §14.4) and INV-COV cannot be stated |
| Consequence | under the trim policy, correcting a period into another would be refused (no attribution move). **Optional under BD-REV = refuse.** |
| Resolution | there is no truth without it (facts → state) |
| INV-COV | CX-14b (mutual cuts) and §13.1 (orphans) would be legal states: membership silently lost |
| INV-EP | trimming could fragment a period (CX-B/C); the episode loses its meaning |
| INV-9 | two periods of one person in one organisation at t (BD-I5) |
| provenance | the authority / consent / attestation requirements |
| authority evidence | explaining a decision without Governance's current state |
| recordedAt | KnownAt (a pre-correction report) |
| seq | a total order for same-time facts; the concurrency mechanism |
| term segments | INV-12 (lapse gaps); Lapsed vs Ended (INV-3) |
| type segments | CurrentType for Election. **Optional if BD-TYPE forbids mid-period type changes** (then a period attribute) |
| restriction intervals | Suspended (Suspend / Restore) |

Nothing in the list can be removed without a named failure, except where marked optional by a business decision.

---

## 15. Closure re-run (the "Final Membership Architecture Closure and Ratification" commission, re-issued)

The commission re-issued on 2026-10-08 is the one first answered in §13; its items are answered in §13–§14 (mapping in the verdict document). It was not re-researched. **One item was re-checked because the model had changed since it was last verified:** commission §6, "Res(F ∪ {Annul(c)}) equals the counterfactual state with c and all causally dependent consequences removed". That check found a defect.

### 15.1 CX-15: the consequences of a correction of an annulled correction are orphaned

| Item | Content |
|---|---|
| Initial state | empty |
| Fact sequence (4 facts; the shortest failure in the exhaustive space T=3, ≤4 facts) | f0 Admit P1 @0 · f1 Correct(f0 → ∞) (P1's claim becomes empty) · f2 Admit P2 @0 · f3 Correct(f1 → 1) (P1 starts @1; cuts P2 [1,∞)) |
| Checked property | Res(F · Annul(f1)) = Res(F ∖ {f1, f3}) (recorded counterfactual) |
| Expected | P1 [0,∞), P2 [0,∞): the counterfactual (an overlap, so G\* must refuse the Annul, as in history H) |
| Actual | P1 [0,∞), P2 [0,1): f3's cut stays live although f3 no longer supplies any value. The overlap is **hidden**, and G\* would accept the Annul |
| Root cause | §14.2 defined `active(i) ⇔ live(i) ∧ live(root(i))`. A Correct whose *intermediate* target is annulled is therefore still "active" for its consequences, while `value` already ignores it |
| Frequency (simulation, not a statistic) | 27 / 73,167 exhaustive histories; 62 / 124,998 and 82 / 244,715 random checks |
| Model correction | **a fact is active only if its whole correction chain is live:** `active(i) ⇔ live(i) ∧ (kind(i) ≠ Correct ∨ active(target(i)))`. LiveCut uses `active`, and a Correct requires an **active** target |
| Status after correction | 0 mismatches (§15.3); Theorem 15.2 PROVEN |

**Affected invariant / requirement:** INV-COV is not violated (the hidden overlap keeps coverage). What is violated is the **CFC principle** (consequence follows cause: an orphaned consequence) and, through it, **INV-9**: G\* accepts an annulment whose true result is an overlap. Requirement: annulment / correction history (FDR traceability: "correction / annulment / void").

### 15.2 Theorem (recorded counterfactual), corrected model

Let `D(c)` be c together with every fact whose target chain reaches c (Corrects of c and of those Corrects, and Annul / Void facts targeting any of them).

> For every log F and every live fact c: **Res(F · Annul(c)) = Res(F ∖ D(c))**, where every remaining fact keeps its recorded consequences. The same holds for Void (Annul and Void differ in statistics, audit and legal meaning, not in resolution).

**Proof sketch:**
1. In F · Annul(c), every fact in D(c) is inactive: c is dead, and every other member of D(c) has c in its chain.
2. Inactive facts contribute nothing:
   - **no value:** `value` follows only live Corrects of a live target, and no fact outside D(c) has a target inside D(c), except c's own target, which then skips the dead c;
   - **no consequences:** LiveCut = ∅ for inactive causes.
3. For every fact x ∉ D(c), liveness, value and supersession are unchanged:
   - every Annul / Void / Correct targeting x is itself outside D(c), or it is c;
   - c is dead in one log and absent in the other.
4. Therefore BaseClaim and LiveCut agree on all periods, and so does Res.

**Status:**
- PROVEN (structural argument above);
- VERIFIED: exhaustive T=3 and T=4, ≤4 facts, every live Admit / End / Correct annulled in every reachable state;
- VERIFIED-BOUNDED: random checks.

The theorem is about **resolution**. Whether the annulment is *accepted* is still G\*'s decision: when the counterfactual violates I, the annulment is refused (history H) and needs a further decision first. This keeps the earlier meaning: an annulment never silently produces a state the invariants forbid.

### 15.3 Re-verification of the corrected model

| Run | Histories / checks | Violations | Status |
|---|---:|---:|---|
| scripted histories A–L, N, O–R | 17 | 0 | VERIFIED |
| exhaustive, T=3, ≤4 facts (with Correct(Admit)) | 73,167 | 0 | VERIFIED |
| exhaustive, T=4, ≤4 facts | 171,500 | 0 | VERIFIED |
| exhaustive, T=3, ≤5 facts | 2,121,843 | 0 | VERIFIED |
| random, seeds 77 / 991 | 120,000 / 300,000 | 0 / 0 | VERIFIED-BOUNDED |
| counterfactual, exhaustive T=3 / T=4, ≤4 facts | 73,167 / 171,500 histories, all live facts | 0 / 0 | VERIFIED |
| counterfactual, random (seeds 5, 6; incl. Annul(Admit)) | 122,171 + 240,709 checks | 0 | VERIFIED-BOUNDED |

The number of refused commands rose (seed 77: 22,766 → 25,102). The extra refusals are exactly the cases where an orphaned cut used to hide an overlap, or where a Correct targeted an inactive fact. **This is the intended effect, not a loss of completeness:** histories A–R are unchanged.

### 15.4 Answers to the re-issued commission that differ from §13

- **§3 (no circularity):** `active` recurses on the target, i.e. on strictly smaller indices; `live` recurses on strictly larger ones. Both are well-founded on a finite log. The DAG of §14.4 is unchanged, with `active` placed between `live` and `value`.
- **§19 (verdict wording):** the commission offers "RATIFIED", but its own §21 forbids claiming ratification, and the later principal-review commission fixed the wording. The status is therefore **READY FOR RATIFICATION BY THE PRINCIPAL REVIEWER**.

---

## 16. Final freeze audit

> Freeze rule applied: nothing is reopened without a concrete failure. **No new counterexample was found in this audit.** One documentation inconsistency was found and corrected: MA §2 said Annul and Void "can carry consequences", whereas the model, FP §14.3 and every verification run treat them as revive-only.

### 16.1 Semantic kernel

| Kernel concept | Definition |
|---|---|
| Person, Organisation | external identities (Identity, Organisation contexts), referenced by id |
| Membership | the enduring relationship of one Person with one Organisation (EM-VOC-001 `Member`); identity (p,o) |
| MembershipPeriod | one legal episode inside a Membership; identity = its opening Admit |
| Fact | an append-only, seq-ordered statement about a Membership (§16.2) |
| BaseClaim | the interval a period claims from its live Admit / End values alone (§14.2) |
| Consequence | an interval removed from another period, recorded on the lifetime-enlarging fact that caused it |
| Resolution | the pure function facts → (live, active, value, BaseClaim, LiveCut, ResolvedLifetime) |
| Invariant | INV-M, INV-1, INV-EP, INV-9, INV-COV, INV-2, INV-3, INV-12 |
| Derived predicate | InLifetime, Ended, TermCovered, InForce, Lapsed, Suspended, IsMember, CurrentPeriod, CurrentType, admission dates, durations |

**Everything else, classified:**

| Item | Class |
|---|---|
| provenance, authority evidence, person declaration | **evidence** (attributes of a Fact) |
| term segments, type segments, restriction intervals | derived structures inside **Resolution** (from Renew / ChangeType / Suspend / Restore payloads) |
| effectiveAt, decidedAt, recordedAt, seq | temporal attributes of a Fact (§16.9) |
| BD-\* parameters | **policy** |
| eligibility, entitlement, settlement, competence, person equivalence | **external context** |
| G\*, optimistic version, idempotency key, outbox, DB constraints, transaction time | **implementation / enforcement** |
| materialised periods, read models | **projection** |
| published predicates and events (IsMember, PeriodTrimmed, …) | **integration contract** |
| MembershipNumber | an attribute of Membership (an identifier credential); its uniqueness is policy (BD-NU) |

### 16.2 Fact algebra (authoritative)

| Fact | Meaning | BaseClaim | Term | Restriction | Can enlarge lifetime | Creates consequences | Can be Corrected | Can be Annulled | Can be Voided |
|---|---|---:|---:|---:|---:|---:|---:|---:|---:|
| Admit | opens an episode | yes | may carry the first segment | no | creates it | no (a backdated Admit into a live lifetime is refused) | yes | yes (decision provenance) | yes |
| Renew | grants a term segment | no | yes | no | no | no | yes | yes | yes |
| ChangeType\* | a type segment | no | no | no | no | no | yes | yes | yes |
| Suspend | starts a restriction | no | no | yes | no | no | yes | yes | yes |
| Restore | ends a restriction | no | no | yes | no | no | yes | yes | yes |
| End(kind) | ends the episode (targets an open period) | yes | no | no | **no** (only shrinks) | no | yes | yes | yes |
| Annul | a decision set aside, ex tunc | via its target | via its target | via its target | yes (e.g. annulling an End) | **no**; revives superseded consequences only | **no** (no value) | yes, if the Annul is itself a decision (an appeal) | yes |
| Void | the record never reflected reality | via its target | via its target | via its target | yes | **no**; revive only | **no** (no value) | **no** (not a decision) | yes |
| Correct | a real fact recorded wrongly; replaces its value | if the root is Admit / End | if the root is Renew | if the root is Suspend / Restore | yes | **yes** (Δ cuts + inherited, §14.2) | yes (chains) | yes, with decision provenance | yes |

(\* only if BD-TYPE allows mid-period changes.)

**Harness note:** the verification harness lets End *enlarge* a period, and lets Annul target any fact. That is a **superset** of this algebra; the domain expresses an enlarging End as Correct(End). Restrictions only shrink the reachable set, so every verified property carries over (§16.4).

**No new fact is required.** Ex nunc annulment (LQ-2), if adopted, is an End(kind = annulled-ex-nunc), not a new fact kind.

### 16.3 Correction algebra

```text
target(f) < f                    (a fact can only reference an already-recorded fact)
TargetChain(f) = f, target(f), target(target(f)), …          strictly decreasing in seq, so finite
live(f)        ⇔ ¬∃ g > f : g ∈ {Annul, Void} ∧ target(g) = f ∧ live(g)
Active(f)      ⇔ live(f) ∧ (f ∉ Correct ∨ Active(target(f)))
Superseded(f)  ⇔ ∃ g > f : g ∈ Correct ∧ target(g) = f ∧ live(g)
Value(f)       = Value(max{ g : g ∈ Correct, target(g) = f, live(g) })   if non-empty, else v(f)
BaseClaim(q)   = [Value(Admit_q), max(Value(Admit_q), Value(End_q)))     over live Admit / End; ∅ without a live Admit
Consequences(f)= cons(f), frozen at commit: g2 on the strict prefix
LiveCut(c)     = Cut(c) ∩ BaseClaim(Target(c))   if Active(Cause(c)) ∧ ¬Superseded(Cause(c)), else ∅
```

**Termination and determinism (PROVEN):**
- **Every recursion is well-founded:**
  - `TargetChain`, `Active` and `Value` recurse to strictly smaller seq;
  - `live` recurses to strictly larger seq, bounded by the log length;
  - so there is no cycle and no infinite chain.
- **`Value` is deterministic:** it picks the maximum live Correct in a total order (seq).
- **The precondition `target(f) < f`** is guaranteed because a command can name only a fact that is already committed, and seq is assigned at commit (§16.10).

**Challenge of "Correct may target only an active fact":**

| Question | Answer |
|---|---|
| Why active? | Introduced with CX-15 as a safety measure. **The audit shows it is not needed for safety:** chain-wide activeness already makes a Correct of an inactive fact inert. With the rule relaxed, the exhaustive audit (T=3, ≤4 facts, 151,959 histories, Annul / Void of any fact incl. Annul / Void) shows 0 invariant violations and 0 counterfactual mismatches (§16.5). |
| Domain or enforcement? | **Neither a domain truth nor required enforcement: a policy.** |
| A legitimate case for correcting an inactive fact? | **Annulled target: yes.** A registrar finds that an annulled admission was recorded with the wrong date. The annulled record is still part of the audit history (KnownAt), and if the annulment is later set aside on appeal, the corrected value should apply. **Voided target: no.** A voided record never reflected reality, so there is nothing real to correct; the right act is a new fact. |
| Correction or new fact? | For an annulled fact, a correction (same real event, wrong record). For a voided fact, a new fact. |
| Does refusing preserve historical truth? | Refusing keeps a known-wrong value in the audit record of an annulled fact. Allowing it is inert for current truth and improves the record. |
| Classification | **Business policy, folded into BD-REV.** Recommendation: allow Correct of an *annulled* fact (inert until revived); refuse Correct of a *voided* fact. Both variants preserve every invariant, so **this does not change the architecture.** |

### 16.4 Annul / Void / Correct combinations

| Operation | Valid? | Effect | Changes current truth | Changes consequences |
|---|---|---|---|---|
| A Annul(original) | yes (decision provenance) | original dead; D(original) inert | yes | its cuts die; superseded cuts revive only if not superseded |
| B Annul(Correct) | yes if the Correct has decision provenance, else Void | the value reverts | yes | the correction's cuts die; the target's own cuts revive, clipped |
| C Annul(Correct(Correct)) | as B | the value reverts to the middle correction | yes | as B |
| D Void(original) | yes | as A; excluded from statistics | yes | as A |
| E Void(Correct) | yes | as B | yes | as B |
| F Annul(Annul) | **yes:** the annulment decision is itself set aside (a successful appeal); the original stands again | original live again | yes | the original's consequences revive; **G\* checks the revival** (refused if it would violate I) |
| G Annul(Void) | **refused (domain rule):** a Void is a record determination, not a decision. Use Void(Void) if the Void was recorded in error | — | — | — |
| Void(Annul) | yes: the annulment record never reflected reality | original live again | yes | as F |
| Correct(Annul), Correct(Void) | **refused (domain rule):** they carry no value. To change *what* was annulled: Void(Annul) + a new Annul. An annulment's *effective date* exists only under ex nunc (LQ-2), and then it is an End(kind), which is correctable. | — | — | — |
| H Correct after Annul (of its target) | **policy** (§16.3): recommended allowed, inert until revived | none now | no | none now |
| I Correct after Void (of its target) | refused | — | — | — |
| J Correct(Correct) | yes | the latest live correction wins | yes | inherits clipped consequences + its own Δ |

**Machine evidence:** every combination above, including Annul / Void of Annul / Void, is inside the audited space of §16.5. Domain rules that *refuse* a combination (G, Correct(Annul), Correct(Void), I) only shrink the reachable set. Every property proven or verified over the larger set therefore holds for the smaller one.

### 16.5 Is D(c) exactly the causal dependency closure?

`D(c)` is the **reference closure**: c plus every fact whose target chain reaches c. Other facts are not in it, even when their recorded consequences were computed in a state that contained c.

So the question is whether a full **replay** (re-run every remaining command through g2 and G\* as if c had never existed) could give a different result from the **recorded** counterfactual.

Exhaustive audit, T=3, ≤4 facts. Annul and Void may target any live fact, including Annul and Void. Every live fact c is annulled in every reachable state:

| Measure | Strict (active target) | Relaxed (any Correct target) |
|---|---:|---:|
| histories | 151,959 | 151,959 |
| counterfactual checks | 449,773 | 479,341 |
| recorded-counterfactual mismatches | **0** | **0** |
| invariant violations | **0** | **0** |
| replay possible and **equal** to recorded | 431,481 | 466,209 |
| replay possible and **different** | **0** | **0** |
| replay impossible (some remaining fact would have been refused without c) | 18,292 | 13,132 |

**Reading:**
- **Whenever a replay is possible, it gives exactly the recorded result.** The recorded semantics therefore never "freezes" an attribution that a re-decision would change. EXHAUSTIVELY VERIFIED (bounded), **not PROVEN** in general.
- **When a replay is impossible**, the history without c is not a valid history: some later fact was only admissible because c existed. Two outcomes:
  - **the annulment is accepted** (8,988 cases): only when the recorded result satisfies all invariants. The smallest case is an ordering artefact: two Ends on an empty period, of which only one is annulled;
  - **the annulment is refused** (9,304 cases): the dependants must be decided first (history H).
- **Domain meaning:** an annulment never silently re-decides later facts. Either the result is valid as recorded, or a decision about the dependants is required. **D(c) is the right closure.**

### 16.6 CFC+ semantic test (does a consequence live exactly while its justification lives?)

| Case | Consequence | Evidence |
|---|---|---|
| one cause → one cut | lives while the cause is active and not superseded; clipped to the cause-target's base claim | A |
| one cause → many cuts | each clipped independently | B |
| many causes → one target | independent; annulling one keeps the others | C |
| overlapping causes on one target | the union; removing one keeps the other's part | C, exhaustive |
| cause shrinks (End / Correct shortens the cause's period) | **shrinks** to the intersection; the target regains the rest | I (CX-14c) |
| cause expands | existing cuts unchanged; new cuts only on the new Δ, recorded on the enlarging fact | D, exhaustive |
| cause corrected | **replaced**: the cause is superseded; the correction carries Δ + inherited cuts, clipped | D |
| cause annulled / voided | **disappears** | A, F, G |
| cause's correction annulled | the correction's cuts disappear; the cause's own cuts **revive**, clipped | F |
| cause's correction corrected | replaced by the newer correction (inherits) | E |
| target corrected | the cut is unchanged; the target cannot reclaim a ceded region (Δ excludes its own live cuts) | CX-14b |
| target annulled / voided | its BaseClaim is ∅, so the cut intersects nothing (inert, not orphaned) | L, exhaustive |

**Semantic statement (holds in every case):** a consequence is alive exactly to the extent that its cause is active, unsuperseded, and still claims the interval that justified it.

### 16.7 INV-COV (exact)

```text
Claim(t,F)  = { q : t ∈ BaseClaim(q,F) }             base membership claim at t
Owner(t,F)  = the q with t ∈ ResolvedLifetime(q,F)    ownership of the instant (unique by INV-9)
Coverage(t,F) ⇔ Owner(t,F) is defined                resolved membership at t

INV-COV:  Claim(t,F) ≠ ∅  ⇒  Owner(t,F) is defined ∧ Owner(t,F) ∈ Claim(t,F)
```

**Meaning:**
- Consequences may **change the owner** of an instant among its claimants, but never leave a claimed instant **unowned**.
- **Membership disappears** at t only if `Claim(t,F)` becomes empty, i.e. through a fact that changes a base claim: End, Correct, Annul, Void, or (for the future) the absence of an Admit.
- INV-COV says nothing about such changes; it forbids only what **consequences** do.

**Status:** PROVEN for committed states (by construction). That the generator never needs the gate: EXHAUSTIVELY / BOUNDED VERIFIED.

### 16.8 INV-EP, I5, D1 (bounded re-check; nothing reopened)

- **INV-EP (domain invariant):** the distinctions validate against all six cases.
  - suspension: a restriction, so the episode continues;
  - lapse: a term gap, so the episode continues (INV-3);
  - renewal: a term segment;
  - restoration: ends a restriction;
  - termination: End;
  - re-entry: a new Admit, so a new episode.

  No domain fact interrupts an episode without ending it, so continuity follows from "episode = admission act → ending" (FP §14.7). If a requirement ever asks for "interrupted without End and resumed without a new admission", INV-EP becomes a policy. No current requirement does.

| Question | Domain truth | Architecture | Policy | Implementation |
|---|---|---|---|---|
| I5 scope (p,o) | — | — | **BD-I5** (recommended (p,o); FP §14.9) | — |
| D1: identity (PeriodId only within a Membership) | **yes** | — | — | — |
| D1: lifecycle ownership (a period cannot outlive its Membership) | **yes** | — | — | — |
| D1: cross-period invariants (INV-9, INV-COV) | — | **yes** | exist only if BD-I5 | — |
| D1: atomic consequence handling | — | **yes** | exists only if BD-REV = trim | — |
| D1: sequence ordering (one seq per Membership) | — | **yes** | — | the counter |
| D1: concurrency (one version per Membership) | — | **yes** | — | optimistic version |

D1 is valid in every branch and minimal under trim. D2 is valid under refuse (with a set constraint) or without I5 (FP §14.8).

### 16.9 Temporal algebra

| Time | Why it exists | Owner | Domain truth? | Affects resolution? |
|---|---|---|---|---|
| effectiveAt | when the fact holds in the world (valid time) | the deciding authority / the fact | **yes** | **yes**: values and intervals |
| decidedAt | when the decision was taken (authority, appeal deadlines) | the deciding authority | **yes** (evidence) | **no** |
| recordedAt | when the system of record learned it (KnownAt) | Membership (system of record) | **yes** (record truth) | only through KnownAt prefixes |
| seq | total recording order per Membership; ties and the correction chain | Membership | **yes** (record order) | **yes**: live / Active / Value |
| DB transaction time | technical | infrastructure | **no** | **never read by Resolution** |

**Requirement (derived):** within one Membership, `recordedAt` is non-decreasing in `seq`. Both are assigned at commit under the aggregate version.

**KnownAt(φ,t,τ)** := φ at valid time t over the **seq-prefix** {f : recordedAt(f) ≤ τ}. It is deterministic (PROVEN) because Res is a pure function of a seq-ordered prefix, and:

| Situation | Why KnownAt stays deterministic |
|---|---|
| identical recorded facts | each is its own fact with its own seq |
| equal recordedAt | seq breaks the tie |
| retries | the idempotency key gives one fact |
| concurrent commands | serialised per Membership (§16.10), so seq is total |
| rejected commands | never appended, so invisible |
| corrections / annulments | appended; earlier prefixes unchanged |

### 16.10 Concurrency (architectural requirement)

**Lost update exists (computational demonstration):**
- From every state reachable in ≤3 facts (T=3), each pair of commands that G\* accepts individually on the same state was committed blind (the second without re-evaluation).
- **683 / 23,522 pairs violate I.**
- Smallest case: P1 admitted and ended @0. A = Admit P2 @0; B = End P1 → 1 (alone: no other period, so no consequence). Blind commit: P1 [0,1) overlaps P2.
- On re-evaluation, B must be **recomputed**, not merely re-checked: its f⁺ now needs a cut on P2 (or B is refused). 439 of the 683 lead to a refusal; the rest commit with different consequences.

**Requirement:** every command that can change a Membership follows

```text
read version v → ResolveCandidate(S_v, f) → G* → Commit(expectedVersion = v)
    conflict → reload S_v' → recompute f⁺ (g2) → G* → Commit(expectedVersion = v')
```

**Why a database constraint cannot replace it:** f⁺ (the consequences) is a function of the latest state, and INV-COV / INV-EP are properties of the resolution, not of a stored row. A unique or exclusion constraint can at most back up INV-9 on a projection. It cannot recompute consequences.

### 16.11 Policy parameters: does changing them change the architecture?

| Parameter | Changes the architecture? | Class |
|---|---|---|
| **BD-I5** | **yes, subtractive:** without exclusivity, INV-9, consequences, g2 and INV-COV become unnecessary, and D2 becomes minimal | architecture-shaping business decision (every branch pre-analysed) |
| **BD-REV** (trim vs refuse) | **yes, subtractive:** under refuse, Consequence, g2, CFC+ and INV-COV are vacuous | architecture-shaping business decision (pre-analysed) |
| **BD-SYNC** | **yes, additive** if asynchronous: INV-9 becomes a detected condition and needs a reconciliation process. The domain kernel is unchanged; the enforcement layer changes | architecture-shaping; **the ratified branch assumes transactional** |
| BD-REV-ER (absorbing correction) | no: both options satisfy every invariant; one predicate in I | business policy (recommended: refuse) |
| BD-REV correction target (§16.3) | no | business policy (recommended: annulled allowed, voided refused) |
| BD-1, BD-2, BD-NU, BD-REC0, BD-CONSENT, BD-TERM4, BD-TEN, BD-GVR, BD-NORM, BD-SUSP | no | business / product policy |
| BD-TYPE | no (type segments become a period attribute if mid-period changes are forbidden) | business policy |
| LQ-2 ex nunc | no (End(kind)) | legal |
| I5 answered "two legally distinct simultaneous memberships in one organisation" | **yes: a new relationship type** (ontology) | the only answer that would reopen the ontology; no current requirement asks for it |

### 16.12 Proof-status matrix (authoritative)

| Claim | Classification | Evidence | Confidence |
|---|---|---|---|
| CFC+ non-circular; correction chains terminate; Value deterministic | PROVEN | §14.4, §16.3 | high |
| INV-COV for committed states | PROVEN (by construction) | §14.5, §16.7 | high |
| the generator never needs the INV-COV gate | EXHAUSTIVELY VERIFIED (bounded) + BOUNDED VERIFIED | §14.6, §15.3 | high within bounds |
| INV-EP for committed states | PROVEN (by construction); classification ARCHITECTURE (justified from the ontology) | §14.7, §16.8 | high |
| INV-9 for committed states | PROVEN (by construction); scope BUSINESS (BD-I5) | §14.9 | high |
| recorded counterfactual Res(F·Annul(c)) = Res(F ∖ D(c)) | PROVEN + EXHAUSTIVELY VERIFIED (incl. Annul / Void of Annul / Void) | §15.2, §16.5 | high |
| replay = recorded whenever a replay exists | EXHAUSTIVELY VERIFIED (bounded) | §16.5 | medium–high |
| scripted histories A–R | SCRIPT VERIFIED | §14.6 | high |
| D1 | ARCHITECTURE (domain reasons: identity, lifecycle) | §16.8 | high |
| I5 | BUSINESS (BD-I5), RECOMMENDATION (p,o) | §14.9 | high |
| temporal determinism (KnownAt) | PROVEN (given seq total and recordedAt monotone in seq) | §16.9 | high |
| concurrency safety | ARCHITECTURE requirement + IMPLEMENTATION mechanism; the need is EXHAUSTIVELY VERIFIED (bounded lost-update demonstration) | §16.10 | high |
| migration safety | DERIVED (rule: never invent truth) + classification | §14.11, §16.14 | high |
| INV-11 (no entitlement) | ADOPTED | ADR_20261004_1549, EM-ENT | — |

### 16.13 Requirements traceability (final, full chain)

| Requirement | Domain concept | Fact | Predicate | Invariant | Aggregate | Context | Test |
|---|---|---|---|---|---|---|---|
| admission | Period | Admit | CurrentPeriod | INV-9, INV-EP | Membership | — | re-entry → new PeriodId |
| recognition | Admit provenance | Admit | IsMember | — | Membership | Governance (competence) | admit without competent authority refused |
| consent | person declaration | Admit (provenance) | — | — | Membership | legal LQ-1 | admit without declaration refused (BD-CONSENT) |
| authority | authority evidence | every decision fact | — | — | Membership stores evidence | **Governance owns competence** | evidence explains the decision without Governance's current state |
| membership | Membership | — | IsMember | INV-M, INV-1 | Membership | — | one Membership per (p,o) |
| periods | Period | Admit / End | InLifetime | INV-EP, INV-9, INV-COV | Membership | — | A–R |
| renewal | term segment | Renew | TermCovered | INV-12 | Membership | Finance (payment) | history M |
| lapse | term gap | — | Lapsed | INV-3 | Membership | — | lapse ≠ End |
| suspension | restriction | Suspend | Suspended | — | Membership | Governance (decision) | IsMember unchanged |
| restoration | restriction end | Restore | Suspended | — | Membership | — | restriction closes |
| termination | End | End(kind) | Ended | INV-2 | Membership | — | Ended ⇒ ¬InForce |
| re-entry | new Period | Admit | FirstAdmission / CurrentAdmission | INV-9 | Membership | — | history L |
| number | Membership attribute | — | — | uniqueness (BD-NU; a set rule) | Membership + set constraint | — | duplicate refused |
| type | type segment | ChangeType / Admit | CurrentType | INV-11 | Membership | Election reads it | no entitlement inferred |
| fees | — | — | — | — | **Membership does not own this** | Finance | fee survives a trim |
| standing | Standing | — | Standing (derived) | — | Membership (E0b-S) from Finance evidence | Finance | — |
| election | published contract | — | IsMember, Suspended, CurrentType | INV-11 | — | **Election owns entitlement / eligibility** | Election reads no Membership table |
| finance | events | — | — | — | — | **Finance owns settlement** | PeriodTrimmed / TrimRevived emitted |
| governance | port | — | — | — | — | **Governance owns organs and competence** | AuthorityCheck port |
| identity | PersonRef | — | — | INV-1 | — | **Identity owns equivalence** | merge only via an explicit fact (DS-3) |
| audit | fact log | all | KnownAt | INV-6, INV-14 | Membership | Audit consumes the stream | pre-correction report reproducible |
| historical truth | Resolution | all | TrueAt / KnownAt | INV-14 | Membership | — | §16.9 |
| correction | Correct | Correct | Value | CFC+, INV-COV | Membership | — | A–K, CX-14a/b/c |
| annulment | Annul | Annul | live | counterfactual theorem | Membership | legal LQ-2 | F, H, CX-15 |
| void | Void | Void | live | counterfactual theorem | Membership | — | G; excluded from statistics |
| migration | attested facts | Admit / End / Renew (attested) | — | never invent | import command | BD-NORM | §16.14 |

### 16.14 Legacy migration (field level)

| Legacy field | Target | Transformation | Confidence | Missing evidence | Unresolved case |
|---|---|---|---|---|---|
| membership status (3 vocabularies) | attested facts | mapping table | high for the mapping | the decision behind each status | `archived` (a record flag, not a fact) |
| expiry / `membership_expires_at` | first term segment | derive | medium | when the renewals happened before the ledger existed | a stored `expired` is not an End |
| application approval | Admit (attested; authority = unknown when absent) | approval → Admit at the approval's effective date | medium | the organ that decided | approvals without a date |
| admission (`joined_at`) | Admit.effectiveAt | normalise (BD-NORM) | medium | precision | missing date → kept as evidence, **no period created** |
| membership number | Membership attribute | copy | high | — | collisions on merge (report) |
| fees (`fees_status`, `fee_state`) | Fee (Finance / E0b-S) | reconcile two truths | low–medium | which field is authoritative | disagreement report |
| suspension | Suspend (attested) | copy | low (rare in legacy) | the decision | — |
| termination (`ended`, `endMembership`) | End(kind = legacy or `legacy-unknown`) | copy | medium | the reason | — |
| authority | provenance.authority | `unknown` when absent | — | the organ | **never fabricated** |
| consent | provenance.declaration | `unknown` when absent | — | the declaration | LQ-1 decides whether that blocks recognition |
| election membership | **Election context, untouched** | none | — | — | — |

No inferred legal truth, no fabricated date, no fabricated authority.

### 16.15 ML and statistics boundary

- **Mathematics** (invariants, proofs, the dependency DAG) gives the PROVEN rows.
- **Computation** (exhaustive bounded enumeration, property / random tests, minimisation, the lost-update demonstration) gives the VERIFIED rows. Every frequency in §14–§16 is a count over **generated histories**, **not a statistic about members**.
- **ML** is not used and not needed for semantics. Future permitted uses (recommend or detect only): duplicate detection, identity-resolution assistance, migration-anomaly detection, legacy classification, suspicious lifecycle patterns.
- **ML never decides** admission, membership truth, legal validity, authority, termination, annulment, voting entitlement or eligibility.

### 16.16 Minimality (final)

| Concept | If removed |
|---|---|
| BaseClaim | CFC+ becomes circular; INV-COV cannot be stated |
| Consequence | under trim, every correction into another period is refused (OPTIONAL under BD-REV = refuse) |
| Active | CX-15: orphaned cuts hide overlaps |
| Resolution | there is no truth from facts |
| INV-COV | CX-14b: membership silently lost |
| INV-EP | trimming fragments an episode (CX-B/C) |
| INV-9 | parallel periods in (p,o) (OPTIONAL if BD-I5 = none) |
| authority evidence | decisions cannot be explained later |
| provenance | the consent / authority / attestation requirements fail |
| seq | ties, correction chains, Value and concurrency become undefined |
| recordedAt | KnownAt (a pre-correction report) fails |
| term segments | INV-12, INV-3 |
| type segments | CurrentType (OPTIONAL if BD-TYPE forbids mid-period changes) |
| restriction intervals | Suspended |

**Nothing is kept without a named failure.** The concepts that are optional are optional by policy.

### 16.17 Freeze criteria

| # | Criterion | Met |
|---|---|---|
| 1 | no known counterexample | yes (none open; CX-14a/b/c and CX-15 are corrected and re-verified) |
| 2 | correction chains deterministic | yes (PROVEN, §16.3) |
| 3 | CFC+ non-circular | yes (PROVEN) |
| 4 | counterfactual theorem valid | yes (PROVEN + EXHAUSTIVELY VERIFIED, incl. Annul / Void of Annul / Void) |
| 5 | INV-COV precise | yes (§16.7) |
| 6 | INV-EP justified | yes (§16.8) |
| 7 | INV-9 scoped | yes (BD-I5) |
| 8 | D1 justified | yes (domain + architecture reasons separated) |
| 9 | I5 classified | yes |
| 10 | concurrency explicit | yes (§16.10, an architectural requirement) |
| 11 | requirements mapped | yes (§16.13) |
| 12 | migration invents nothing | yes (§16.14) |
| 13 | boundaries intact | yes |
| 14 | computation correctly labelled | yes (§16.12, §16.15) |
| 15 | no hidden architecture | yes. The three architecture-shaping parameters (BD-I5, BD-REV, BD-SYNC) are **explicit**, pre-analysed, and the ratified branch is named (§16.11) |
