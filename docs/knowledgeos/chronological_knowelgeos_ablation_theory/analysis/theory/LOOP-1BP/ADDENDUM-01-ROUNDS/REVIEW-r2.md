# Review of PREREG-ADDENDUM-01 r2 (independent subagent, read-only; Claude family)

**Verdict: FAIL.**

**Reviewer disclosure:** the reviewer read the sealed values. This review must never be routed to the checker.

## Findings
1. **r1's changes.** 2, 5, 6 and 7 are OK. 1, 3, 4 and 8 are still open.
2. **Exposure (blocking).**
   - Planted histories coincide with stated examples:
     - H_c = Lem 3.5(a′);
     - H_g⁺ = Lem 3.5(b);
     - H_U5h = Lem 3.5(a) with c = c2;
     - W7 is fully described in K8.7.

     Their R0 memberships are therefore exposed.
   - R0/U5 (i)-S is implied by K8.5.
   - R-d/U (i)-S is asserted by Lem 3.7 read literally (see finding F-1 below).
   - The option-W list is under-inclusive: "(A5 broken)" hints at every U5 form, "the W = ∅ break" hints at the R-c forms, and :83 also applies under R0.
   - The tiers of the E2 witnesses are not mechanical.
3. **Unit set (blocking).**
   - `O` is not a Def P0.3 field.
   - The (i)-O least counterexample is not in TASK's format.
   - SUPP yields six values, not five.
   - There are no alias tables: H_∅/`H_empty`, H_g′/`H_g'`, H_g⁺/`H_g+`, the K12 group labels and universe.
   - There is no rule for reading-independent fields.
   - K12 is counted twice.
   - Witness matching is any-of and ignores roles.
   - The Claude-side string-history parser and the comparison script are committed "before it runs", not before the seal is opened.
4. **Classes (blocking).**
   - NOT APPLICABLE lies outside the precedence order and is asymmetric, because Claude's null decides applicability.
   - The absent-unit residual is undefined.
   - UNTESTABLE discards values that should still be compared.
   - AMBIGUOUS-as-flag deviates from the human's §7 list and must be disclosed.
5. **§9 "only narrowings" is false.** Any-of witness matching, NOT APPLICABLE, the exclusion of Claude-only fields, and bundle-02 replacing the bundle named at P:5 are all loosenings.
6. **Options N/W/F: nonblocking.**
   - N's benefit is overstated.
   - README:2 ("no expected answers") and V:1 ("NO predictions") stay false under N and W, and this is undisclosed.
   - The bundle-sha change in the operator guide is unmentioned.
7. **Nonblocking.**
   - A footnote is referenced but missing.
   - `witness_or_counterexample` vs `least_…` is not mapped.
   - Malformed values have no handling rule.

## Reconciliation (main analyst)
- **Accepted:** all findings.
- **Process decision:** r3 is deferred until the human decides the §6 conflict. The tier lists depend on the bundle choice (N/W/F). r3 will replace the prose unit and canonicalization rules with a **comparison script and Claude-side parser, committed before the return exists** (review change 4). Prose has failed twice to be mechanical.
- **F-1 (new, about R4; not about the addendum).**
  - Sealed R4 r3 Lem 3.7 (FORMAL-THEORY-R4.md:235-239) concludes "Prop 4.1(i) holds with H-D⁺′". Prop 4.1(i) includes h* ≈_{σ,f} h.
  - The proof (:241) constructs h** = h*[Cap := ∅], which is only ≈_{σ,f,Cap} h.
  - Read literally, the statement is false in U: the sealed V1b value R-d/U (i)-S = false, with counterexample H_d.
  - R4's own §9 (:931), table row :873 and ledger :618 already state the correct scope (≈_{σ,f,Cap}; the O-form only).
  - Classification: **a wording imprecision in a formal statement.** No conclusion changes, because the O-image equality is what the proof delivers and what §9 uses.
  - Changing the statement is a human gate (§18). It is not edited here.
  - Under any bundle option, if a checker reads Lem 3.7 literally, R-d/U (i)-S would be a (c) disagreement. It is pre-registered here as a known (c) candidate.
