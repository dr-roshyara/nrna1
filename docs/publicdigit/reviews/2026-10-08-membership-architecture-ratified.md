# Membership Architecture (canonical reference)

**Status:** **RATIFIED by the Principal Reviewer, 2026-10-08. FROZEN FOR IMPLEMENTATION.** Act record: `2026-10-08-membership-architecture-ratification-act.md` (with recorder's notes N-1…N-6). Changes only via an Architecture Decision / Change Request. *(History: READY FOR PRINCIPAL RATIFICATION after the final freeze audit, FP §16.)* This document decides no business rule, implements nothing, and changes no ADR or adopted term.
**Date:** 2026-10-08 (final closure) · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Companions:** `…-membership-final-proof.md` (**FP**; §13 = this round) · `…-membership-final-decision-register.md` · `…-membership-final-implementation-blueprint.md` · `…-membership-final-ratification-verdict.md`

> **Correction in this round (FP §13.1):** the previous round's CFC ("a Trim is live iff its cause is live and not superseded") was **incomplete**. A later End fact that shortens the cause's target leaves the cut **orphaned**, so membership is lost (420 / 120,000 histories). **The fix is CFC+:** a cut is live only while it is **justified** by its cause's target's *base* claim. With CFC+: **0 coverage losses**. The previous "RATIFIED" claim is withdrawn.

> **Correction in the principal-review round (FP §14.1):** formalising CFC+ found three further defects, all in the resolution / enforcement rules (ontology, aggregate and boundaries unchanged):
> - **CX-14a:** g2 computed a Correct's consequences against the wrong state, so legitimate corrections were refused.
> - **CX-14b:** g2 cut over the target's whole claim; two periods could then cut each other, and membership was lost (a 5-fact counterexample).
> - **CX-14c:** all-or-nothing justification refused a legitimate End.
>
> **Corrections:**
> - the live part of a cut is its **intersection** with the cause-target's base claim;
> - g2 cuts only the region the target **newly** claims and has not ceded;
> - a Correct inherits its superseded fact's consequences, clipped.
>
> **Re-verified:** 0 violations in 2.1 M exhaustive and 840,000 random histories (FP §14.6).

> **Correction in the closure re-run (FP §15):**
> - **Defect (CX-15, 4 facts):** a correction of an annulled correction kept its cuts live (an orphaned consequence that hid an overlap).
> - **Fix:** a fact is *active* only if its whole correction chain is live, and a Correct requires an active target.
> - **Proven:** the recorded-counterfactual theorem, Res(F · Annul(c)) = Res(F ∖ D(c)) (FP §15.2), also verified exhaustively.

> **Freeze audit (FP §16):** no new counterexample.
> - **Clarified:**
>   - Annul and Void create no consequences; they only revive them (§2 corrected);
>   - Annul targets only facts with decision provenance; Annul(Void), Correct(Annul) and Correct(Void) are refused;
>   - Annul(Annul) and Void(Annul) restore the original, subject to G\*;
>   - "Correct needs an active target" is a **policy** (BD-REV), not a safety rule.
> - **Concurrency** is an architectural requirement (§6).
> - **Architecture-shaping parameters** are named in §10.

---

## 1. What Membership is, and what it is not

| Concept | Is | Identity | Owner |
|---|---|---|---|
| Person | a human | PersonId | Identity |
| OrganisationAffiliation | access / roles (EM-VOC-002) | own | Organisation |
| **Organisation Membership** (EM-VOC-001 `Member`) | the enduring membership relationship of one person with one organisation | **MembershipId**, one per (Person, Organisation) | **Membership** |
| **Membership Period** | one legal membership episode, from admission to ending | **PeriodId**, the identity of its opening Admit | Membership |
| Admission | the act that opens a period (organisation decision + person declaration, or a rule or attested basis) | the Admit fact | Membership |
| Standing | dues compliance (derived) | — | Membership (E0b-S) |
| ElectionRegistration / VotingEntitlement / Exercisability / Vote | the election chain | — | **Election** (ADR_20261004_1549, ADR-T11) |

**What follows from this:**
- Membership does **not** imply ElectionMembership and grants no entitlement;
- Election does not create Membership;
- Finance, Governance and Identity own their own truths.

## 2. Facts (domain primitives; append-only)

| Fact | Meaning | Target | Can change past truth | Can enlarge a lifetime | Can carry consequences |
|---|---|---|---|---|---|
| Admit | opens a period | — | yes (if backdated) | (it creates one) | no |
| Renew | grants a term segment | a period | yes (if backdated) | no (the term, not the lifetime) | no |
| ChangeType* | a type segment | a period | yes | no | no |
| Suspend / Restore | restriction interval | a period | yes | no | no |
| End(kind) | ends the episode | an open period | yes | no | no |
| Annul | a valid decision set aside (ex tunc) | a fact with decision provenance (incl. an Annul) | yes | **yes** (e.g. annulling an End) | **no**; it can only *revive* superseded consequences, and G\* checks the result (FP §16.4) |
| Void | the record never reflected reality | any fact | yes | **yes** | **no** (revival only, as Annul) |
| Correct | a real fact recorded wrongly; replaces values | a value-bearing fact or a Correct; **never** an Annul / Void (they carry no value) | yes | **yes** | yes |

**Every fact carries:**
- `effectiveAt` (valid time);
- `decidedAt` (decision time; required for decision provenance when known);
- `recordedAt` (system-of-record time);
- `seq` (a total recording order per Membership);
- provenance: decision (actor + **authority evidence** {actor, capacity, ruleRef, policyVersion, result, checkedAt} + person declaration) | rule (ref, version) | attested (source, importer, normalisation decision).

(\* ChangeType only if BD-TYPE allows mid-period changes.)

**Consequence** (the trim policy only): a removed interval `(q, [a,b))` recorded on the lifetime-enlarging fact that caused it. **It is not a fact.**

## 3. Resolution (pure; no dependency on acceptance)

```text
live(f)        ⇔ no live Annul/Void targets f   (for a Correct also: its root is live)
value(f)       = value of the latest live Correct chain on f, else f's own
BaseClaim(q)   = [start, end) from live Admit/End/Correct values of q                 (no cuts involved)
active(f)      ⇔ live(f) ∧ (f is not a Correct ∨ active(target(f)))          (the whole correction chain is live)
LiveCut(c)     = Cut(c) ∩ BaseClaim(Target(c))  if Cause(c) is active and not superseded
               = ∅                              otherwise                              (CFC+, proportional justification)
lifetime(q)    = BaseClaim(q) − ∪ LiveCut(c) over the cuts c on q
```

**Dependency order (acyclic; PROVEN, FP §14.4 / §15.4):** facts → liveness → activeness → values → BaseClaim → LiveCut → lifetimes → predicates. Stored consequences are computed at commit from the strict prefix.

**Formal definitions** of BaseClaim, ResolvedLifetime, Cut, LiveCut, Cause, Consequences and Coverage, and the table of which facts change BaseClaim: FP §14.2–§14.3. **Renew, Suspend, Restore and ChangeType never change BaseClaim**, so they can never change coverage.

## 4. Predicates (two-valued)

| Predicate | Definition |
|---|---|
| `InLifetime(q,t)` | `t ∈ lifetime(q)` |
| `Ended(q,t)` | `t ≥ end(q)` |
| `TermCovered(q,t)` | ∃ a term segment ∋ t |
| `InForce` | `InLifetime ∧ TermCovered` |
| `Lapsed` | `InLifetime ∧ ¬TermCovered` |
| `Suspended` | `InLifetime ∧` ∃ a restriction ∋ t |
| `IsMember(p,o,t)` | ∃ q: `InForce(q,t)` (suspension does not change it) |
| `CurrentPeriod` | the unique q with `InLifetime` |
| `CurrentType` | the type segment ∋ t |
| `FirstAdmissionDate` / `CurrentAdmissionDate` | from period starts |
| `Continuous…` / `CumulativeMembershipDuration` | from `IsMember` |
| `Standing` | E0b-S (derived from fee facts) |
| `Eligibility` | **Election's** (not a Membership predicate) |

## 5. Invariants (domain), stated independently of how they are enforced

| ID | Invariant | Kind |
|---|---|---|
| INV-M | one Membership per (p,o) | identity criterion |
| INV-1 | a period's holder and organisation never change | identity criterion |
| INV-2 | `Ended ⇒ ¬InForce` | definitional |
| INV-3 | lapse never implies an ending | definitional |
| **INV-EP** (was INV-SHAPE) | a period's lifetime is one contiguous interval | **definitional:** an episode is admission→ending without interruption; an interruption is a lapse (term) or an End + a new Admit |
| **INV-9** | within the I5 scope, period lifetimes are pairwise disjoint | **business rule** (BD-I5; recommended scope (p,o)) |
| **INV-COV** | the union of lifetimes = the union of live base claims (consequences move *attribution*, never remove membership). **Coverage still changes legitimately** through Admit, End, Correct, Annul and Void, which change base claims (FP §14.5) | follows from the meaning of supersession (CFC+); held by construction (in I) |
| INV-12 | a late renewal preserves the lapse gap | definitional (term segments) |
| INV-6 / INV-14 | append-only; deterministic and stable KnownAt; nothing deleted or rewritten | architecture |
| INV-11 | no voting entitlement from Membership | ADOPTED |

## 6. Enforcement (application / domain service; not ontology)

- **G\*, invariant-preserving acceptance:**
  - form `f⁺`: f plus its consequences. Under the trim policy, g2 computes them from the pre-state over **Δ = NewBase − PrevBase − the target's own live cuts**; a Correct also inherits its superseded fact's consequences, clipped (FP §14.2).
  - **accept iff `I(Res(S ∪ {f⁺}))`**, with I = INV-EP ∧ INV-9 ∧ INV-COV ∧ INV-1 (∧ ER if BD-REV-ER = refuse);
  - **commit** by appending, under the expected aggregate version (FP §14.12).
- **Layering:** DOMAIN TRUTH → INVARIANTS → CANDIDATE RESOLUTION → ENFORCEMENT (G\*) → COMMIT. G\* is replaceable by any mechanism that commits only logs satisfying I (FP §14.10).
- **Well-founded:** `Res` is a function of facts only, and `f⁺` depends only on the pre-state.
- **Policy switches:** BD-REV (trim vs refuse: whether consequences are generated), BD-REV-ER (whether a correction may absorb a whole period; recommended: refuse) and BD-I5 (scope).
- **Concurrency (ARCHITECTURAL REQUIREMENT, FP §16.10):** two commands on one Membership can each pass G\* and together break I (683 / 23,522 blind pairs in the bounded demonstration). Every command follows: read version v → ResolveCandidate → G\* → Commit(expectedVersion = v). On conflict: reload, **recompute f⁺** and re-run G\*. A database constraint cannot replace this, because the consequences depend on the latest state.

## 7. Aggregate

- **Decision: D1, one aggregate per (Person, Organisation)** containing its periods and facts.
- **Reasons, in order:**
  - **identity:** a PeriodId exists only within a Membership;
  - **lifecycle:** a period cannot outlive its Membership;
  - **invariants:** INV-EP, INV-9 and CFC+ read several periods;
  - **transactional:** a fact and its consequences are committed atomically;
  - **historical:** one seq-ordered fact log;
  - **persistence:** secondary.
- **Not claimed:** "the only correct model". Under the refuse policy, D2 (period aggregates) + a set-based constraint is also correct; under no I5, D2 is minimal. **D1 is correct in all branches**, small (a few facts per year), and minimal for the recommended policy. Per-policy table: FP §14.8.
- **INV-EP** is a domain invariant derived from the ontology (every interruption already has its own fact: lapse, suspension, or End + new Admit), not a representation convenience (FP §14.7).
- **I5:** honorary, statutory, ex-officio, classes, units, committee roles, temporary memberships and parallel legal bases each map to a type, a provenance, another relationship or another organisation; none needs overlapping periods (FP §14.9).

## 8. Context contracts

- **Election:** `IsMember`, `Suspended`, `CurrentType`, the tenure predicates and the events, with (MembershipId, asOf) references. Election-Only needs none (EM-EO). **No entitlement logic in Membership.**
- **Finance:** fees reference periods; `PeriodTrimmed` / `TrimRevived` events; settlement is Finance's.
- **Governance:** the AuthorityCheck port; Membership stores evidence only.
- **Identity:** person equivalence; Membership reconciles through explicit facts.

## 9. Correction algebra (FP §16.3–§16.4)

- `target(f) < f` (seq). Correction chains are finite; `Value` = the latest live Correct (PROVEN deterministic).
- `Active(f) ⇔ live(f) ∧ (f ∉ Correct ∨ Active(target(f)))`. Only active, unsuperseded causes have live consequences.
- **Annul** targets facts with decision provenance (incl. an Annul: an appeal sets the annulment aside). **Void** targets any fact. **Correct** targets value-bearing facts and Corrects, never Annul / Void.
- **Correct of an annulled fact** (inert until revived) vs refused: **policy (BD-REV)**. Recommendation: allow for annulled, refuse for voided. Both preserve every invariant (exhaustively verified).

## 10. Architecture-shaping parameters (explicit, pre-analysed)

| Parameter | Ratified branch | Other answer changes |
|---|---|---|
| BD-I5 | exclusivity within (p,o) | none: INV-9, consequences and INV-COV become unnecessary; D2 minimal (subtractive) |
| BD-REV | trim | refuse: consequences, g2, CFC+ and INV-COV vacuous (subtractive) |
| BD-SYNC | **transactional** | asynchronous: INV-9 becomes a detected condition with a reconciliation process (additive, enforcement layer only; the domain kernel is unchanged) |
| I5 answer "two legally distinct simultaneous memberships in one organisation" | not assumed (no requirement asks for it) | a new relationship type: the only answer that would reopen the ontology |

All other parameters (BD-REV-ER, BD-1, BD-2, BD-NU, BD-REC0, BD-CONSENT, BD-TYPE, BD-SUSP, BD-TERM4, BD-TEN, BD-GVR, BD-NORM; LQ-1…LQ-4) leave the architecture unchanged.

## 11. Decision path

| Node | Decision | Confidence |
|---|---|---|
| ontology | Membership = an enduring relationship; Period = an episode | high |
| Membership identity | (p,o) | high |
| Period identity | its Admit | high |
| time | effective / decided / recorded + seq | high |
| facts | §2 | high |
| resolution | §3 (CFC+, proportional) | high (non-circularity PROVEN; INV-COV PROVEN by construction; the generator VERIFIED / VERIFIED-BOUNDED, FP §14) |
| invariants | §5 | high (INV-9 scope = BD-I5) |
| aggregate | D1 | high |
| contracts | §8 | high |
| migration | normalise representation; never invent truth | high |
| readiness | CONDITIONAL GO (administrative conditions) | — |
