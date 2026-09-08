# Phase 5G — Final Adjudication Matrix (required, per the authorization's §19)

| Claim | Phase-5F verdict | Independent audit | Evidence strength | Final verdict |
|---|---|---|---|---|
| K-1 = K-1-B | (not stated as identity) | One referent, no claimed distinctness or transformation; one explicit package cross-reference found (D285-7→D285-6) | High (textual reuse + package self-awareness), but not an external referent-identity statement | **DEMONSTRATED IDENTITY (qualified: within-package, textual-reuse basis)** |
| K-1 ↔ K-1-B formal equivalence | FORMAL EQUIVALENCE | Superseded by the identity finding above — there are not two distinct representations for equivalence to hold between | High | **SUPERSEDED by DEMONSTRATED IDENTITY (qualified)** — "formal equivalence" no longer the operative category |
| K-1 → K-2 projection | "definable, not computable" (treated as blanket) | Partial, non-total; the non-computability is specific to the `Observation` component; `State`'s own target is under-specified | High for the parts tested; `State`'s mapping `NOT EVIDENCED` | **PARTIAL, NON-TOTAL PROJECTION — precisely characterized, `05`** |
| K-1/K-2 semantic equality | TRUE (after unpacking) | Corpus term undefined; tested against 5 named equivalence types, 3 fail outright, 1 unverifiable-as-stated, 1 fails (no inverse) | Low, as a "semantic equality" claim; moderate as a narrower "partial correspondence" claim | **PARTIAL CORRESPONDENCE over a declared, UNVERIFIED subset — DOWNGRADED, `06`** |
| K-1/K-2 observational equivalence | FALSE | Re-confirmed against D285-6 §5 verbatim | High | **FALSE — confirmed, unchanged** |
| Observation = layering gap | layering gap, not incompatibility | H1 (best-supported), qualified by `Qualify`'s own non-implementation | High for the structural claim; the computability caveat is now explicit | **CONFIRMED, H1 (qualified) — `07`** |
| State relationship | UNRESOLVED | Closest to H2 by elimination, but no positive mapping found either way | Low | **UNRESOLVED — confirmed, unchanged, `07`** |
| Entity correspondence | PARTIAL | Re-audited independently against the six-level ladder | Moderate | **PARTIAL CORRESPONDENCE — confirmed, unchanged, `08`** |
| Proposition correspondence | STRUCTURAL | Re-audited independently | Moderate-high (closest of the three) | **STRUCTURAL CORRESPONDENCE — confirmed, unchanged, `08`** |
| Relation correspondence | PARTIAL | Re-audited independently, role-asymmetry noted | Moderate | **PARTIAL CORRESPONDENCE — confirmed, unchanged, `08`** |
| DDD context mapping | Customer/Supplier, ACL-attempted | No bounded-context declaration, no governance rule, no specified/implemented ACL artifact found | Low for the DDD-pattern claim specifically; the underlying math projection remains high | **DOWNGRADED — mathematical projection evidenced; DDD architectural context mapping NOT independently established, `09`** |

## Reading this matrix against the required equivalence ladder (§20)

- **K-1↔K-1-B**: the highest independently justified level is **DEMONSTRATED IDENTITY**, reached not
  by promoting mechanically from Phase 5F's own "formal equivalence" but by recognizing (per the
  user's own logic) that the evidence never supported treating them as two distinct representations
  in the first place.
- **K-1↔K-2**: the highest independently justified level is **STRUCTURAL/PARTIAL CORRESPONDENCE** —
  explicitly **not** promoted to formal equivalence, since the "semantic equality" claim does not
  survive definition-testing (`06`), and **not** demonted to incompatible, since a real, if partial and
  non-total, projection is genuinely evidenced (`05`).

**No claim in this matrix was promoted merely because a prior phase promoted it; three were
downgraded, one was confirmed as superseded-by-strengthening, and the remainder were independently
confirmed.**
