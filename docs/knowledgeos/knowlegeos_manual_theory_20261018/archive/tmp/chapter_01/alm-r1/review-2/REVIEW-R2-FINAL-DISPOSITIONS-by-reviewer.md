# ALM-r1 review 2: the reviewer's final disposition of the author's responses (verbatim, 2026-10-18)

The same reviewer, resumed, was given `REVIEW-R2-DISPOSITIONS.md`. The text below the line is unedited.

---

**R2 final dispositions.** The overall FAIL verdict stands: no repairs have been made, and the author accepts all 12 MAJORs (13 with R2-14 raised).

| ID | Disposition | Condition for round 2 / remark |
|---|---|---|
| R2-01 | ACCEPTED-WITH-CONDITION | Placing acts by rank within each equal-τ group must also cover acts whose τ and ord are both ⊥ (placement across groups). A regression must reproduce N1 and show it now gives U. |
| R2-02 | ACCEPTED-WITH-CONDITION | The spec must define world semantics for each ⊥ field. A ⊥ holder or ⊥ target must be allowed to stand for an unrecorded holder or act. Each "φ is invariant" claim must be proved, not tested. This interacts with R2-03 and must be repaired together with it. |
| R2-03 | ACCEPTED-WITH-CONDITION | Rework υ as a parameter, with three conditions. (a) υ must cover every recorded act whose authority is not established and whose effect is negative: REVOKE, END, a superseding ADOPT under C6, and also CONFIRM. (b) The spec must say that υ = counts_as_undetermined brings back the "freeze any decision" risk (safeguard 6). (c) Moving RD 6 into the core changes the scope the Principal set in Phase A A3, so it needs the Principal's authorization; the author cannot decide it. The framing of the claim also needs correcting; see point 3. |
| R2-04 | ACCEPTED-WITH-CONDITION | The proposed premise is not usable as written. "Could be ≤ t" refers to unknown truth, and "out-of-domain ⇒ U" cannot be implemented, because the recognizer cannot see true values. The premises must be stated over records: no ⊥ τ on any recorded act unless its recorded fields force τ > t, and true values lie in the domains. Then re-prove Th-3 and Pr-9, and add ⊥ histories to the bounded checks. |
| R2-05 | ACCEPTED-WITH-CONDITION | Restating Th-5 as an existence result is right. The corollary ("λ⁻ = ∞ is the only sound choice") must be re-derived from that weaker statement. The hidden C1 = a assumption (record position and ρ order) must be stated. Any future characterization must be proved, not only bounded. |
| R2-06 | ACCEPTED-WITH-CONDITION | K(d) = [lo, k_max] is correct. The spec must state the consequence: without absence witnesses, RecAt_d is almost always U. "Absence witness" must be listed as required evidence that BC-7 does not currently provide, not assumed to exist. |
| R2-07 | ACCEPTED-WITH-CONDITION | Auth must also be defined for an ill-formed END whose target is missing. There Need(END) has no subject, so Auth is undefined; the spec must say whether it is U or not applicable. Also add Rec(Auth) to the differential tests. |
| R2-08 | ACCEPTED-WITH-CONDITION | "Counts only once recorded" leaves one choice open. Under C1 = a, authority for e can be evaluated at τ(e) or at ρ(e), the instant the act is constituted. Choose one and state it; the probe4 reading must follow from that choice. |
| R2-09 | ACCEPTED-WITH-CONDITION | The oracle agent sees only the spec, with no code and no tests. It should compute the R layer by direct enumeration from the definitions. Disagreements are settled against the spec; where the spec is ambiguous, record a spec finding rather than aligning to the code. State the limit that the oracle comes from the same model family. |
| R2-10 | ACCEPTED-WITH-CONDITION | π = undetermined needs world semantics (an unknown period end is enumerated, or U). Its interaction with expiry as an ending kind (R2-11) must be defined. Correct the false "spec S-2" citations in the code, the oracle and RESULTS. |
| R2-11 | ACCEPTED-WITH-CONDITION | Define Carry for a basis grant that ends in more than one way (for example, revoked after it expired: which ending counts?). Make δ per ending kind as well. Add a test for v1's premise: expiry persists, revocation cascades. |
| R2-12 | ACCEPTED | — |
| R2-13 | ACCEPTED-WITH-CONDITION | "Over the non-root basis when it is non-empty" is still a choice of root exemption. List "root exempts" and "root does not exempt" as alternatives under the [Cand] parameter, without choosing between them. |
| R2-14 | ACCEPTED (MAJOR) | The result must also be UNDETERMINED when a relevant implementer is unrecorded or has a ⊥ performer at the R layer. |
| R2-15 | ACCEPTED | — |
| R2-16 | ACCEPTED-WITH-CONDITION | Implementing the conflict relation needs a new record kind. If that is not modelled in round 2, C6 = later_prevails must be removed or labelled "same-subject proxy (provisional)". |
| R2-17 | ACCEPTED | — |
| R2-18 | ACCEPTED | — |
| R2-19 | ACCEPTED | — |
| R2-20 | ACCEPTED | — |
| R2-21 to R2-26 | ACCEPTED | For R2-24: if the claim kind is added to the ADOPT atom, add it to the END atom too. |

**1. The FAIL verdict stands.**

**2. Would the repair plan close the MAJORs?** Plausibly yes for R2-01, R2-05, R2-06, R2-09, R2-10, R2-11 and R2-14. Four need a different or more specific approach:
- **R2-04:** the premises must be reformulated over records, as in the table. "Out-of-domain ⇒ U" cannot be implemented.
- **R2-08:** the evaluation instant under C1 = a must be decided first.
- **R2-03:** needs the Principal's authorization to change scope, and the corrected framing.
- **R2-02 and R2-07:** need definitional work in the spec before any code changes. Because unbounded ⊥ domains will push many results to U, the run report must show how many results each repair turns into U.

**3. Is r1.1 "structurally dependent on RD 6"? Only partly.**
- **Correct:** the pair (λ⁺, λ⁻) does not fix the result for a recorded ending whose authority is not established. So "r1.1 = (λ⁺ = 0, λ⁻ = ∞)" is not a deduction, and withdrawing it is right.
- **Overstated, for two reasons:**
  - Under an open world, clause (3) can never be established, even when no ending is recorded at all (Th-6). λ⁺ = 0 is therefore already a closure presumption. υ only asks whether that presumption also covers the *authority* of a recorded act, so it is a second closure, not a new structural gap.
  - The answer depends on C1. Under C1 = b, applying r1.1's own "never 'not authorized'" to the ending act points to υ = counts_as_undetermined. Under C1 = a, a missing authority record means no authority, which gives υ = does_not_count.
- **Accurate statement:** r1.1 leaves υ open only if its "cannot determine" rule is not applied recursively to ending acts. Read uniformly, C1 settles υ. This is a reading of the requirement, not "a structural fact about the requirement".

**4. What the responses missed or softened.**
- **RESULTS §3:** the review-1 dispositions marked "R" for P-2, P-3, P-5, C-5, C-6, F-1, F-2 and F-3 overstate the repairs. The response does not commit to relabelling them as partial. Fixing the wording under R2-18 and R2-19 alone is not enough.
- **Scope:** the change needed for R2-03 needs governance approval, and the author cannot simply adopt it.
- **Unmanifested files:** three files were added after the manifest (DECISION-BRIEFS-R1.md, ML-DISCOVERY-NOTE.md, TRACEABILITY-R1.md). Their status is not addressed; they should go into the round-2 manifest or be explicitly excluded.
- **Re-proofs:** Pr-9 (via Th-3) and Th-8 (via K(d)) inherit defects and need re-proving; this is not listed.
- **"Lessons recorded" point 3:** it repeats the overstated R2-03 claim and should be corrected before it reaches the methodology addendum.

No project file was modified.
