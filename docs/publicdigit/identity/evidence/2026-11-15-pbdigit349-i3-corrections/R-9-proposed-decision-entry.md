# R-9: proposed decision-register entry (PROPOSED, NOT INSERTED)

**Status: a proposal for the Human Arb Chief.** Nothing has been written to the decision register. It is not accepted and not backdated. Session B prepared it under the I-3 review correction R-9.

## The gap (facts)
- **Canonical register:** `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md`. It ends at **D-239**, both in branch `identity/pbdigit-349-i3` and in the primary `election-flow-updated` HEAD `7aa09fd23` and its working copy, as checked 2026-11-15.
- **D-200** (I-TDD-02 rev 12, accepted with conditions) says in its point **(7): "No implementation is authorised."**
- **No later entry authorises implementing PBDIGIT-349.** The register does not mention PBDIGIT-349 at all.
- **Where the authorisation is recorded:** only in
  - the plan `docs/plans/20261115-1426-identity-claims-and-bindings-pbdigit-349-plan.md`, line 5: "APPROVED for steps I-1 … I-4 by the Principal, 2026-11-15 … Q-1 = authorise I-1 … I-4; Q-2 = L1; Q-3 = the existing users row";
  - the story `PBDIGIT-349-…-implementation.md`.
- **The source** is the Principal's answers in the Session B conversation on 2026-11-15: "Authorise I-1 to I-4 (Recommended)", "L1: stored table (Recommended)" and "Existing users row (Recommended)".
- **Consequence:** I-1 and I-2 were merged into `election-flow-updated` on the strength of that plan-level approval. The register, which governs, still reads "no implementation is authorised".

## Proposed entry (number to be assigned at insertion; the next free number today is D-240, but Session A may take numbers first)

| D-??? | **Authorises the implementation of I-TDD-02 (D-200) steps I-1 … I-4, PBDIGIT-349, for development and test only.**<br>**(1) Scope:** I-1 references, I-2 ordinary claims (issue, replace, revoke), I-3 redemption and binding, I-4 Identity's adapter for Platform's `IdentityBindings` and the R-1 acting-reference adapter. **Not authorised:** bootstrap claims (I-6), claim-first registration (I-7), production rollout, the S4 outbox relay.<br>**(2) Tactical choices:** P-I2 = **L1**, a stored central reference table (O, E) → R; the Account is the existing legacy `users` row (transitional).<br>**(3) Conditions:** strict TDD; each step on its own branch, merged only by the Principal after review; D-200's build and release gates (PI-1 … PI-48, PI-17b) remain release gates; Identity's central migrations run on test databases only until a separate rollout decision.<br>**(4) Not decided here:** acceptance of any step; the throttling values (5 per code, 10 per client, 900 s), which stay [TU]; production readiness.<br>**(5) Record:** this entry records an authorisation the Principal gave on 2026-11-15 in the implementation conversation and wrote into the PBDIGIT-349 plan. It is recorded on the date it is inserted and is not backdated. | Principal, 2026-11-15 (conversation); recorded on insertion |

## Questions for the Principal
1. **Insert as written, amend, or decline?**
2. **Should the register say that I-1 and I-2 were merged before this entry existed?** Recommended: yes, as a one-line note in (5), so the record stays truthful.
3. **I-4 authorisation:** keep it in this entry, or re-confirm it separately after I-3 is accepted?
