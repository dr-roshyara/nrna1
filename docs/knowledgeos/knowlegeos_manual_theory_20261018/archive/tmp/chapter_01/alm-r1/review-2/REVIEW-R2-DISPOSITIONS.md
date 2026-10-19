# ALM-r1 review 2: the author's responses (2026-10-18)

**Basis:**
- the reviewer's report: `REVIEW-R2-REPORT-verbatim.md`, unedited;
- every probe was re-run by the author against the frozen subject (`probes-reproduced-on-subject.txt`). **All counterexamples reproduce.**

**Classes:**
- **MD** mathematical;
- **ID** implementation;
- **SM** semantic (vs r1.1 or sources);
- **EV** evidence/claims;
- **PQ** policy decided silently;
- **TA** test adequacy.

**Verdict accepted: ALM-r1 FAILS the review gate.** It is not repaired in this response.

| ID | Sev | Reproduced | Class | Response | Proposed repair (round 2; not started) |
|---|---|---|---|---|---|
| **R2-01** | MAJOR | yes (N1) | ID + TA | Accepted. The ord domain must allow every placement relative to recorded ords | ord worlds = all linear placements within each equal-τ group (rank-based), not integer slots |
| **R2-02** | MAJOR | yes (N2) | SM + PQ | Accepted. ⊥ in holder, target, subject, grantee and scope was read as a definite value | every ⊥ field is enumerated over its domain, **including "an unrecorded value"**. Where that is unbounded, the verdict is U unless φ is invariant (proved per field) |
| **R2-03** | MAJOR | yes (N3) | SM + PQ (**RD 6 υ**) | Accepted, and **this is the most important theory finding of the round.** Under an open world, r1.1's clause (3) ("**no** later properly authorized act ended it") cannot be *established* from records while any recorded ending has an **unestablished** authority. So RD 6 is **not separable from the TD-001 core**: the claim "r1.1 = (λ⁺ = 0, λ⁻ = ∞)" is withdrawn as a deduction. It is a reading that additionally needs υ | add **υ (RD 6)** as a parameter: `counts_as_undetermined` / `does_not_count`. Define r1.1-literal = (λ⁺ = 0, λ⁻ = ∞, υ = counts_as_undetermined), labelled **interpretation**. Bring RD 6 *into* the core as a parameter, not as a decision |
| **R2-04** | MAJOR | yes (Th-3 probe) | MD | Accepted. Th-3's premise is too weak, and finite domains are a hidden premise | Th-3 premises: no ⊥ on **any** recorded act whose true τ could be ≤ t **or** on any act ≺ e; and the true values lie in the domains (or out-of-domain ⇒ U). Re-prove. Add ⊥ histories to the bounded checks |
| **R2-05** | MAJOR | yes (Th-5 probe) | MD + EV + TA | Accepted. The characterization is false in both clauses and missed cases. **The bounded "11,920/11,920" was circular**: the check's completion family mirrored the proof's construction. *This is the author-encoding pattern again (7th occurrence, counting ALM-r1's R1-F2).* **R1-F4 is withdrawn** | restate Th-5 as an existence statement (some negatives are defeasible), with **no** characterization claim, unless one is proved with a completion family that includes **all act kinds** (target-adding, revocation-adding, two-step grant-then-revoke) |
| **R2-06** | MAJOR | yes (N7) | MD + SM | Accepted. A by-date witness is a lower bound only. **The P-4 repair introduced this defect** | `K(d) = [lo, k_max]` unless there are **absence witnesses** ("p not on record at d′"), a distinct evidence type. Conflicting witnesses → report the conflict |
| **R2-07** | MAJOR | yes (N4) | ID (layer collapse) | Accepted | compute `Basis` independently of Form and ρ. `Auth = Basis ≠ ∅ ∧ ¬Restr`. `Valid = Form ∧ Auth (∧ recorded under C1 = a)`. Add Auth to the oracle |
| **R2-08** | MAJOR | yes (N5, probe4) | SM + MD | Accepted. The C1 = a currency asymmetry, and the S-4.7 ambiguity, where both implementations chose the "recursive" reading | under C1 = a, every act, the constituting ADOPT included, counts at an instant only once recorded. Choose and **state** one reading of S-4.7 and prove it is well-defined; offer the other as a variant only if both are meaningful |
| **R2-09** | MAJOR | yes (N6 divergence) | TA | Accepted. The oracle transliterates the implementation and shares its misreadings | **commission the oracle from a separate fresh-context agent given only the SPEC** (no code), covering V, Auth, Current, R (worlds, ⊥, contradictions, horizons, dates), Eff and Elig. Differential testing over the full parameter grid, including C1 = a |
| **R2-10** | MAJOR | yes (probe3) | PQ (**C7 π**) + EV | Accepted. **The author's R1-F2 "fix" silently chose C7 = open-ended and mis-cited the spec.** This is a false citation in the code and in RESULTS | add **π (C7)** as a parameter: `undetermined` / `open_ended`. Make fixtures state periods explicitly. Correct the citations |
| **R2-11** | MAJOR | by reasoning: agree | SM | Accepted. RD 1 must be per claim kind **and per ending kind** (expiry vs revocation vs supersession of the grant); δ per ending kind as well | `RD1 : K × EndingKind → {persist, cascade}`, with `EndingKind ∈ {expiry, revocation}` in scope |
| R2-12 | MINOR | by reasoning: agree | PQ (C11) | Accepted. Under C1 = b, T after transcription follows from r1.1's act semantics, but must be **declared**. C11's "U" alternative belongs to C1 = a-like readings | declare in the spec. Offer C11 = U as a variant of recognition (a grant recorded after the act → U) |
| R2-13 | MINOR | yes (N8) | SM | Accepted. A spec defect (vacuous truth) plus a silent ROOT exemption | define BAR = all over the non-root basis when it is non-empty, and state the ROOT treatment as part of the [Cand] parameter |
| R2-14 | MINOR→MAJOR | yes (N9) | SM | Accepted, **treated as MAJOR**: eligibility must not be asserted under an unknown implementer identity | Elig under ⊥ performer of a relevant implementer → UNDETERMINED. Add Elig to `rec`. Add a prec condition to IND-3 |
| R2-15 | MINOR | by inspection | ID + SM | Accepted | add `(*, *)` to the spec's Atom grammar explicitly, or remove it from the code; the spec and the fixtures must agree |
| R2-16 | MINOR | by reasoning | PQ (C6) | Accepted | C6 needs a **recorded conflict relation** (register). Implement it as a relation, not as "same subject" |
| R2-17 | MINOR | by inspection | ID | Accepted. The S-11 "no defaults" claim was false | remove the defaults (domains, proc, ckind). Make `rec` total (no RuntimeError; return U with the reason "bound exceeded") |
| R2-18 | MINOR | by inspection | EV | Accepted. A repeat of P-5 | correct the RESULTS wording; report both minimal examples |
| R2-19 | MINOR | by inspection | EV | Accepted. False claims: contradiction tests; "ML independently confirms"; the Pr-10 example label; "conjecture"; the nonexistent test file name | correct all of them; add contradiction tests |
| R2-20 | MINOR | by inspection | TA | Accepted | Th-2 probes at arbitrary positions; exhaustive Elig; Th-5 for currency and for acts by R |
| R2-21…R2-26 | NOTE | — | — | Accepted | state R2-21; fix R2-22 (the "only if" is false); R2-23 two-step completion; **R2-24: add the claim kind to the ADOPT atom** (r1.1 "kind of act"); R2-25 C15 as a parameter or a stated assumption; R2-26 a Form check for claim periods |

## What holds (reviewer-verified)

- V layer: Th-1 and Th-2 proofs; the C-1 to C-4 repairs; IND-1 as authority; relational IND-2;
- 6,000 random differential trials with 0 validity disagreements;
- Th-4's construction;
- "never F under λ⁻ = ∞";
- Pr-10, as a proof of non-implication;
- contradiction handling (U);
- every count in RESULTS reproduces.

## Lessons recorded (methodology)

1. **Seventh occurrence of the author-encoding pattern** (R1-F2's own "fix" became R2-10; Th-5's circular check). A same-author oracle is not independent. **The oracle must be written by a separate agent from the spec alone.** This is now proposed for the methodology addendum §2.5.
2. **A completion search must include every act kind**, or its "non-defeasible" results are artefacts of the search.
3. **The r1.1 recognition clause depends on RD 6 (υ).** This is a genuine structural fact about the requirement, not about the model.

## No disagreement with the reviewer

Every finding is accepted as stated, with one sharpening: R2-14's severity is raised to MAJOR.

---

## Addendum (after the reviewer's final dispositions; the text above is kept as written)

1. **The R2-03 framing is corrected.** It is *not* "a structural fact about the requirement".
   - Under an open world, λ⁺ = 0 is already a closure presumption. υ is a **second closure**: does the presumption extend to the *authority* of recorded ending acts?
   - **Read uniformly, C1 settles υ:** C1 = b → υ = counts_as_undetermined (r1.1's own "never 'not authorized'" applied to the ending act); C1 = a → υ = does_not_count.
   - The accurate statement: **r1.1 leaves υ open only if its "cannot determine" rule is not applied recursively to ending acts.**
   - "Lessons recorded" point 3 is withdrawn in that form.
2. **The review-1 dispositions in `../ALM-R1-RESULTS.md` §3 are relabelled PARTIAL** for P-2, P-3, P-5, C-5, C-6, F-1, F-2 and F-3. They are recorded in the exit report. RESULTS itself is part of the frozen subject and is not edited.
3. **Re-proofs required:** Th-3, Pr-9 (via Th-3), Th-5's corollary and Th-8 (via K(d)).
4. **Unmanifested files:** `DECISION-BRIEFS-R1.md`, `ML-DISCOVERY-NOTE.md` and `TRACEABILITY-R1.md` were written after the manifest and **were not reviewed**. They will enter the round-2 manifest. Until then they are drafts and carry the pre-review claims.
5. **Scope:** treating υ (RD 6) inside the core changes the Phase A scope, so it **requires the Principal's authorization**. This is raised in the exit report.
