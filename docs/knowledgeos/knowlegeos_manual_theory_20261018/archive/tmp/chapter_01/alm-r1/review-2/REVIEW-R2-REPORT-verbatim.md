# ALM-r1 independent review 2: reviewer's report (verbatim)

**Header (author):**
- **Subject:** the frozen state in `../MANIFEST-R1.md` (hashes re-verified OK before and after).
- **Reviewer:** a fresh-context, read-only subagent; same model family.
- **Independence:** it was instructed to derive its findings before reading the author's RESULTS §3.

The text below the line is unedited.

---

## ALM-r1 independent review 2: findings register

**Verdict: FAIL.** There are 0 BLOCKER and 12 MAJOR findings. The V layer (Th-1, Th-2, the C-1 to C-4 repairs, IND-1 and IND-2 behaviour) holds. The R layer, Th-3, Th-5, S-8 and the r1.1-fidelity claim do not. Several review-1 "R" dispositions are only partial.

**Integrity and reproduction.** All 12 files match the hashes in MANIFEST-R1.md, and still matched at the end of the review. I ran everything in a scratch copy:
- scenario tests: 28/28;
- exhaustive tests: same JSON as run-exhaustive.json (apart from the seconds field), 36.6 s;
- `ml_discovery_r1.py`: 282,243 cases, 7,153 disagreements, 0 with completeness — same as the recorded run.

Three files appeared in the subject directory at 22:03, after the manifest (21:01–22:01): DECISION-BRIEFS-R1.md, ML-DISCOVERY-NOTE.md, TRACEABILITY-R1.md. They are not part of the frozen subject and I did not review them. No project file was modified. My probes are `probe1.py` to `probe4.py` in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/review2_ce/`.

### Findings

**R2-01 · MAJOR · implementation and test adequacy.** Location: `alm_r1.py` L281–282, where an unrecorded `ord` is given the domain `range(len(acts))`.
- The spec (S-2: ord ∈ ℚ; S-6: "any tie order") requires every placement. The code can never place an unrecorded `ord` before or between recorded same-instant `ord` values, because collisions drop those worlds.
- Counterexample: g (GRANT R→b, τ1 ord0); e (PERFORM by b, τ2 ord0); f (any act, τ2 ord1); r (REVOKE g, τ2 ord ⊥). `Rec_4(valid e) = T` under λ⁺=0, λ⁻=∞. But the world with ord(r) = −1 makes e invalid, so the correct answer is U.
- This is a false positive under the r1.1 configuration, and it shows the P-2 repair is incomplete.

**R2-02 · MAJOR · semantic and silently decided policy.** Location: S-5 L164 says any field may be ⊥, but S-6 L180 and `worlds()` enumerate only τ, ord, performer and ρ.
- A ⊥ in holder, target, subject, grantee or scope is treated as a definite value (`None`).
- A recorded REVOKE with ⊥ holder is therefore invalid in every world: `Rec(valid e) = T`, whereas a world where the holder is R gives False.
- A recorded END with ⊥ target gives `Rec(current a, 3) = T`.
- The C-6 repair is therefore partial.

**R2-03 · MAJOR · fidelity to r1.1 and silently decided policy (RD 6 υ).** Location: S-6 "A world contains only recorded acts", and S-6 L200 "[Ded] … exactly λ⁺=0, λ⁻=∞".
- Worlds are closed over acts and over authority. A recorded REVOKE or END by a holder whose authority is not on record is therefore treated as unauthorized, and recognition returns T.
- Probes: g; REVOKE g by c; e → `Rec(valid e) = T`. Likewise END by c → `Rec(current a) = T`.
- This decides v1 case 15 / RD 6 as "does not count", although the spec declares RD 6 out of scope.
- By the spec's own Th-5/Th-6 reasoning, records cannot "establish" r1.1 clause (3) in such a case. So "r1.1 = (λ⁺=0, λ⁻=∞)" is an interpretation, not a deduction. The part that does hold is "never F under λ⁻=∞".

**R2-04 · MAJOR · mathematical (Th-3 is false as stated).**
- The premise "every field needed is non-⊥ for acts in H≤t" is too weak. It allows ⊥ on acts whose true τ > t. There is also a hidden premise that true values lie inside the finite domains.
- Counterexample:
  - truth: g τ1, e PERFORM τ4, r REVOKE g τ7;
  - records: g, e, and r with τ = ⊥;
  - Comp(3, τ(e)) holds and V(e) = T;
  - with the default domain τ ∈ {1, 2, 3}: `Rec_3(valid e) = F` under λ⁻=0, which violates (b);
  - with τ ∈ {1..7}: Rec = U, which violates (a) |W| = 1 and (c).
- Pr-9 inherits this defect.
- The bounded evidence (363,558 checks) contains no ⊥ fields at all.

**R2-05 · MAJOR · mathematical and evidence (the Th-5 characterization is false).**
- Clause (i) is refuted. An ill-formed REVOKE or END whose target is simply not yet recorded is curable by a completion that adds the target. Probe: `Rec_1(valid rv) = F`; after adding g9 at position 2, T.
  - Of the 3,378 bounded "non-defeasible" ill-formed rows, 3,174 have an absent target. The test's completion family adds only grants, so the bounded match "11,920/11,920" is circular: the check mirrors the proof's own construction.
- Clause (ii) is refuted. An IND-1 negative is defeasible: a completion adds a late ROOT revocation of the implementer's grant before the PERFORM, plus a need-only grant. Probe: F → T.
- The list misses non-defeasible negatives:
  - τ(a) > t;
  - cascade with BASIS = all where a basis grant has expired or was revoked under ROOT (a late g\* does not help);
  - C6 supersession by an ADOPT made under ROOT.
- The theorem's table claim ("for every F there is a completion") contradicts its own refined proof.
- SPEC lists exceptions (i)–(iv); RESULTS says "iff ill-formed or IND-1". The two documents are inconsistent.
- Under C1 = a, the late g\* needs ρ(g\*) ≤ τ(e) while sitting at the last position. That conflicts with any reading in which position order follows record time. This hidden assumption is not stated.
- R1-F4, offered as "an input to r1.2", is refuted in general.

**R2-06 · MAJOR · mathematical and semantic (S-8 K(d)).**
- A witness "position p was on record by date d′" is a lower bound only. A witness with d′ > d does not show that p was absent at date d, so the upper bound `min{p : d′ > d} − 1` is unjustified.
- Counterexample: g, e, r at positions 1–3; witnesses {(2, 2), (5, 3)}; d = 3. `rec_at_date` returns T on K = [2, 2], but `Rec_3 = U`.
- Th-8 "determinate iff constant on K(d)" rests on this unsound K.
- When witnesses conflict (hi < lo), the code silently evaluates only lo.

**R2-07 · MAJOR · spec and code differ; the layers collapse.**
- S-4.5 defines Auth(e) as Basis(e) ≠ ∅ ∧ ¬Restr(e), independent of Form and of ρ(e).
- The code empties the basis when Form fails or when ρ(e) = ⊥ under C1 = a, so `auth()` returns False.
- Probe: a root-made anachronistic GRANT gives `auth = False`, whereas the spec gives T.
- This undermines S-0's "questions kept apart" (Q1 vs Q2). The oracle has no Auth at all.

**R2-08 · MAJOR · semantic (C1 = a).**
- Valid_a(e) requires only ρ(e) ≠ ⊥, and Current requires τ(a) ≤ t. So a claim is current before its constituting act is recorded: `current(a, 2) = True` with ρ(a) = 50.
- Grants, REVOKEs and ENDs, by contrast, count only once recorded: an END at τ2 recorded at 50 leaves the claim current at t = 3.
- This asymmetry contradicts C1's "a recorded act".
- S-4.7 is also ambiguous. "V evaluated on H^a_e" read literally gives a different result from the "recursive" reading. In the probe (g0 τ1 ρ3; g τ2 ρ2; e τ4), the literal reading gives valid and the recursive reading gives invalid. **Both the implementation and the oracle choose the recursive reading**, which answers task (c).

**R2-09 · MAJOR · test adequacy (the oracle is not independent).**
- `oracle_r1.py` transliterates the implementation:
  - same decomposition;
  - same `('*','*')` wildcard in Covers, which the spec does not define;
  - same unstated-period default, with the same false citation "spec S-2";
  - same C1 = a reading.
- Its only genuinely distinct element is the brute-force fixed-point search.
- It is never run on:
  - C1 = a;
  - currency under C10 = no_same_instant or δ = dependent;
  - the R layer (worlds, ⊥ fields, contradictions, horizons, `rec_at_date`, Eff);
  - Auth;
  - Elig, BAR, IND-2.
- An undetected divergence exists. With C1 = a, C6 = later_prevails, a1 (τ1, ρ5) and a2 (τ2, ρ2): `current(a1, 6)` is False in the implementation and True in the oracle.
- Credit: my 6,000-trial random differential over the full grid (C1 = a, δ, C10, BASIS, cascade, C6) found 0 validity disagreements, so V-layer agreement is credible.

**R2-10 · MAJOR · silently decided policy (C7 π).**
- `gperiod`: a grant with no stated period runs [τ, ∞), i.e. open-ended.
- The register lists C7 as undetermined / open-ended, recommends "undetermined", and notes that AMS calls "permanent" a defect.
- The spec contains no such rule. The code and oracle comments ("spec S-2") and RESULTS R1-F2 ("fixed by the spec rule") nevertheless cite one.
- Almost all fixtures depend on it.

**R2-11 · MAJOR · semantic vs sources (RD 1 reduced).**
- `RD1 : K → {persist, cascade}` is per claim kind only. v1's P and the register's RD 1 are "per kind of claim **and kind of ending**", and δ is a single global switch.
- The model cannot express v1's central premise: a term end or expiry persists, while revocation carries over. Both endings are treated alike through Alive(g, t).

**R2-12 · MINOR · silently decided policy (C11 / RD 2(c)).** A grant recorded after the act it covers gives T under C1 = b (retroactive) and F under C1 = a. The "U" alternative, which the register recommends, is unavailable, while RD 2 is declared out of scope. The C1 = b answer is arguably entailed by r1.1, but it should be declared.

**R2-13 · MINOR · spec and code differ (BAR = all).**
- Spec: INELIGIBLE when "every non-root g ∈ Basis bars". This is vacuously true for a root-only basis, which is a defect in the spec.
- Code: requires ROOT ∉ Basis.
- Probe: Basis = {ROOT, gb}, with gb barring, gives ELIGIBLE in code and INELIGIBLE by the spec. The ROOT exemption is decided silently.

**R2-14 · MINOR (arguably MAJOR) · semantic (Elig).**
- An implementer PERFORM with ⊥ performer gives ELIGIBLE for independent verification of the same work (probe), i.e. eligibility is asserted under uncertainty.
- S-6 defines t_φ for Elig, but `rec` does not support it.
- IND-3 has no prec condition.

**R2-15 · MINOR · spec and code differ (Covers).**
- The code adds `('*','*')`, which S-1 and S-4.2 do not allow. Read literally, the spec's ROOTSET covers nothing.
- The scenario comment "spec S-1: wildcard exists only at object level" contradicts the ROOTSET it uses.
- Th-5's g\* scope `{(*,*)}` lies outside the Atom domain.

**R2-16 · MINOR · silently decided policy (C6).** "Later prevails" fires on any later valid ADOPT with the same subject, even if that ADOPT has itself been ENDed, has not reached its cperiod, or is of another claim kind. "Conflict = same subject" is decided silently; the register says a recorded conflict relation is needed.

**R2-17 · MINOR · implementation.** The S-11 claim "No default exists in the code" is false:
- `rec`: tau_dom = (1, 2, 3) and perf_dom;
- `eff`: proc = True;
- `Act`: ckind = "decision".

Also, `max_worlds` raises a RuntimeError, so the implementation is not total, unlike Th-7.

**R2-18 · MINOR · evidence.** RESULTS Th-4 says the "minimal T→F example = the hand-derived one". It is not: the run found gRa / gab / rRa, a same-instant revocation affecting a delegated grant, while the hand-derived case is g, r, e (PERFORM). This repeats review-1 P-5.

**R2-19 · MINOR · evidence.**
- "Contradictory … fields exercised in scenario tests": no test with contradictions exists. My probe shows contradictions are handled (U).
- "The ML count independently confirms Th-3": it runs the same implementation over a subset of the same space, so it is not independent.
- The Pr-10 example b_not_a is trivial (the act itself is unrecorded) and is mislabelled "an unrecorded grant".
- The SPEC table still calls Pr-10 a conjecture.
- PHASE-A cites a nonexistent `tests_r1_regression.py`.

**R2-20 · MINOR · test adequacy.**
- The Th-2 probe only appends acts at τ = 4, later than everything else, which is trivial and repeats T-5.
- Eligibility is never tested exhaustively, so T-2 is partly unrepaired.
- The Th-5 check covers validity only and excludes acts by R.

**Notes.**
- R2-21: under C10 = no_same_instant, an END or REVOKE at the same instant as its target is permanently void, not deferred. This consequence is not stated.
- R2-22: Th-6 "T sound **iff** Comp ∧ AX-Truth": the "only if" direction is false.
- R2-23: Th-5's "ROOT REVOKE" needs (REVOKE, \*), but A-Root grants only (GRANT, \*). This is repairable with a two-step grant-then-revoke.
- R2-24: the claim kind is not part of the ADOPT atom, so a permission cannot distinguish adopting a rule from adopting an exception (r1.1 "permission for that kind of act").
- R2-25: C15 is implicitly "no".
- R2-26: claim periods have no Form check (a cfrom before τ(a) is accepted).

### Review-1 findings: repaired or not

| Status | Findings |
|---|---|
| Genuinely repaired (verified behaviourally) | P-1, P-6, P-7, P-10, C-1, C-2 (as parameter), C-3, C-4, C-7, C-8 (mechanically), C-11, CE10, IND-1 as authority (E-8), IND-2 relational (E-5), A-6, v1 case 12 |
| Partial | P-2 (R2-01); P-3 (R2-05); P-5 (R2-18); C-5 (R2-13); C-6 (R2-02, R2-19); F-1 (R2-03); F-2 (R2-11); F-3 (R2-08, R2-09); T-1 to T-8 (R2-09, R2-20); P-8 (inherits R2-04) |
| New defect introduced by a repair | P-4 → S-8's K(d) (R2-06) |

### Verified as correct
- Th-1 and Th-2 proofs are valid: prec is strict and well-founded, Form forces an END's target to come first, and δ recursion descends the delegation chain.
- Th-4: the construction reproduces (T, then F at λ⁻ = 7; U at λ⁻ = ∞).
- Under λ⁻ = ∞, F is never produced.
- Th-7 holds mathematically.
- Th-8 holds conditionally, apart from K(d).
- Pr-10's non-implication examples are valid proofs.
- Contradiction worlds give U.
- The C14 and claim-period half-open conventions are applied consistently.
- The implementation and the oracle agree on validity across the whole parameter grid (my 6,000 random trials).
- Every count in RESULTS reproduces exactly.

**Overall verdict: FAIL.**
