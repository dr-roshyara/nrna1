# Phase 5K — Decision Authority Analysis (Decision D7)

## The corpus's own governance-protocol document (seq 0927, read in full this phase)

Defines, formally:

$$Authority(a,scope) \quad \text{(theoretical relationship the corpus's own model can represent)}$$
$$LegitimateAuthority(a,scope,source,validity) \quad \text{(an organizational fact requiring
independent establishment)}$$

with the explicit rule: *"Theory defines Authority, but Organization establishes LegitimateAuthority.
This distinction is mandatory."* And, as its own worked negative example (§16, *"Non-Binding Verifier
Recommendations"*): *"The verifier must not write: 'HPA is the ratification authority' — unless the
organizational corpus already establishes that fact."*

## Testing whether the corpus independently establishes HPA's own legitimate authority

**Behavioral/functional evidence found**: "HPA" appears as the acting reviewing/ruling entity across
at least 15 documents in `phase_measure_theory/` (grep count, this phase), including seq 0764's own
"HPA RULING — GN-31... THE FINAL ARCHITECTURE IS HEREBY RATIFIED" (already known from Phase 5D) and
this same governance-protocol document's own closing "HPA SUPERVISORY RULING... Authority: HPA."

**Independent-establishment evidence found**: **none.** No document defines HPA's own charter,
membership, delegation source, or organizational mandate. Every occurrence of "HPA" *exercises*
apparent authority (issuing rulings, accepting/rejecting steps) without any document *establishing*
where that authority itself comes from — exactly the gap seq 0927's own §13.2 (*"Authority as an
Organizational Fact"*) requires to be independently supplied, and exactly the overclaim its own §16
warns against making without that supply.

## Applying seq 0927's own Authority Chain test

$$ValidRatification(d) \iff Mandate(a) \land Authorized(a,d) \land Decision(a,d) \land Recorded(d)
\land Effective(d)$$

- $Mandate(HPA)$: **NOT EVIDENCED** — no organizational mandate document found for HPA itself.
- $Authorized(HPA, \text{Assertion-schema decisions})$: **NOT EVIDENCED** — HPA's own rulings found
  in this corpus concern *other* steps (Step 282, Step 283, GN-31/architecture) — no ruling
  specifically addressing the Assertion field-set conflict was found anywhere.
- The remaining chain links are moot without the first two.

## Verdict

**`GOVERNANCE AUTHORITY NOT EVIDENCED`**, exactly as the authorization's §11 requires when this is the
finding. "HPA" is recorded as **the corpus's own recurring, functionally-acting reviewing entity** —
a fact worth preserving for any future governance process, since it is the closest thing to a named
actor this corpus contains — but its own **legitimacy as the ratification authority for this specific
decision is not independently established**, and this phase does not manufacture that establishment.

## Consequence for this phase's own governance decision record (`15`)

The `Authority` field in every proposed decision entry (`15`) is recorded as `TBD — GOVERNANCE
AUTHORITY NOT EVIDENCED`, matching seq 0927's own register template's own literal `TBD` convention
(`02`) — not filled with "HPA" or any other name.
