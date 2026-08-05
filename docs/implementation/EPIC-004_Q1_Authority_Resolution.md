# EPIC-004 Q-1 Resolution — The Authority Model

**Kind:** ARB decision record — deliberately small, per the chair's instruction ("not Tactical DDD, not Strategic DDD, simply an ARB decision record"). Answers the five questions the chair listed; nothing more.
**Status:** DECIDED (ARB, 2026-07-26, explicit per-item ruling) — FROZEN on recording.
**Why now (priority ruling, 2026-07-25):** Q-1 is not local — the authority model influences Proceeding → Responsibilities → Commands → Events → Policies → Collaboration; resolving it before further tactical decomposition prevents implicit authority assumptions from propagating (the Discriminator Principle applied).

---

## The five answers

1. **Who may issue a determination?** Only an actor holding a **valid constitutional delegation for adjudication** at the time of issuance.
2. **Is authority delegated?** **Yes** — through constitutional delegation, with lifecycle (granted, scoped, revocable), as the business already practices it.
3. **Is authority itself modeled?** **Yes — but not here.** Authority delegation is already a modeled, implemented domain: Governance's `AuthorityAssignment` / `AuthorityResolver` / `AuthorityChain` / `DelegationLifecyclePolicy` and the `AuthorityDelegated`/`AuthorityRevoked` events.
4. **Is Governance the owner?** **Yes.** Governance owns the authority model — who holds what delegation, on what constitutional basis, over what scope, in what period. This extends ADR-001..003's recorded separations (Trust ≠ eligibility ≠ authorization ≠ governance) rather than inventing a new one.
5. **Is Adjudication merely consuming authority?** **Yes — refined wording per the chair:** **Adjudication depends on Governance's authority decision through a published contract.** Adjudication never knows *how* Governance determines authority; it depends only on the contract's assertion (candidate names for the contract artifact, undecided: *AuthorityDecision* / *AuthorityAssertion* / *AuthorityGrant*). On the wire this remains an opaque crossing per ADR-T16, as `IssuedByAuthority` is shaped today. Whether/how issuance-time validation consults that contract (and what happens on failure) is tactical design for later artifacts, under this decision's constraint: **the validity question is Governance's to answer; Adjudication's obligation is to record which authority issued and to refuse issuance absent an authority assertion (the non-empty check already implemented is the seed of that obligation).** *Scope note: Governance is not one of the five ratified BCs, so this crossing's formal relationship pattern is NOT assigned here — doing so would touch the frozen pattern selection; the tactical realization is bounded by this record and designed in later artifacts.*

## Grounds (recorded)

- **The evidence lean, confirmed as the ruling:** "the constitutional authority issues a determination" — `IssuedByAuthority`'s own docblock, ADR-T17's deferral ("legitimacy decided upstream"), and the EAC third-party-review finding all pointed here; the ARB confirms the business reality matches.
- **Reuse over reinvention (ER-03/04):** Governance's delegation machinery exists, is implemented, and is load-bearing; a local Adjudication authority model would duplicate it across a context boundary.
- **Consistency with the strategic baseline:** authority crossing as an opaque reference honors ADR-T16 (string-crossing identities) and adds no new relationship pattern to the frozen selection — the tactical realization of the crossing is later work, bounded by this record.

## Binding architectural constraint (ARB, recorded verbatim in substance)

> **Adjudication must never implement or duplicate constitutional authority rules. It may validate an authority assertion through Governance's published contract, but Governance remains the sole owner of authority delegation and validity.**

## Consequences

- **Criterion 7 for the AdjudicationProceeding concern is now answerable:** the decision authority for a determination lives with the (Governance-delegated) constitutional authority — the deliberative record is **not** the decision's owner. The Candidate-2 decision reopens with this discriminator in hand (per the frozen artifact №2's own terms). **The chair's recorded confidence shift (not yet a decision):** with Governance owning authority, the business language becomes *Governance authorizes → Adjudication evaluates → Determination issued* — "Proceeding" still lacks strong business identity, so confidence shifts toward **Process Manager or application orchestration**, unless future domain evidence introduces "Proceeding" as a first-class business concept.
- The quarantine path (Constitutional Policy 4: "an authorized authority decides") now has a defined meaning of *authorized* — a valid Governance delegation.
- Q-2 (the Contestation Window) remains open and untouched by this record.

---

**Stop condition:** this record answers Q-1 and nothing else. The next step it unblocks: reopening the Candidate-2 decision (Aggregate vs. Process Manager) with the discriminator resolved — an ARB decision, prepared but not made by this record.

---
*Requested by the chair (2026-07-25 priority ruling) · Discriminator identified in `EPIC-004C_Adjudication_Aggregate_Evaluation.md` (FROZEN) · Governance machinery evidence: EPIC-003 inventory (Trust/Governance/Shared report) · Separations: ADR-001..003 · Crossing discipline: ADR-T16.*
