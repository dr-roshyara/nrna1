# Independent review 1 of ALM: reviewer's report (verbatim)

**Header (author):**
- **Reviewer:** a fresh-context, read-only `kos-theory-reviewer` subagent of the same model family, so not external.
- **Date:** 2026-10-18.
- **Subject:** the ALM baseline, sha256 prefixes `ALM-FORMAL-MODEL.md 647403780527af5a`, `alm.py ebc1386a80731c35`, `tests_alm.py 59370490b3982750`.
- **Reviewer's probe script:** `ce_reviewer_original.py`, preserved unchanged.
- **Author's re-run** on the project-local baseline: `ce_reproduced_on_tmp_baseline.py`; output in `ce_reproduction_output_20261018.txt`.

**The text below the line is the reviewer's report, unedited. The author's responses are in `REVIEW-ALM-1-DISPOSITIONS.md`.**

---

# Independent review: ALM (layered Authority-Lifecycle Model) candidate

**Scope.** I read ALM-FORMAL-MODEL.md, alm.py, tests_alm.py and PHASE-1-CHECKPOINT-20261018.md, and spot-checked 8 repository sources. No files were modified. I respected the access restrictions.

**Tests.** `python3 -B tests_alm.py` gives 32/32 PASS, matching run-20261018.txt. Passing does not mean the theorems hold (see findings T-1 to T-8).

**Counterexample probes.** I wrote and ran them in a separate script, outside the alm folder: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/review_ce/ce.py`. Results are cited as CE1–CE12.

**Copies.** The scratchpad files are byte-identical to the untracked copies under `docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/chapter_01/`.

**Column key.** Type: TD = theory defect, ME = missing evidence, UP = unresolved policy (decided silently or under-parameterized), CD = code/spec divergence.

## 1. Proof check

| ID | Sev | Location | Finding | Counterexample / evidence | Type |
|---|---|---|---|---|---|
| P-1 | **BLOCKER** | Lemma 2; Thm 3; V2 END_CLAIM `covers` | **Proof gap.** The proofs say each act's validity depends only on acts before it, "by inspection". This is false for END_CLAIM: its coverage pair needs the *subject of its target claim*, which is a field of another act. Nothing requires that act to come first or to be recorded. **Effect:** Theorem 3 (soundness under completeness) fails in the code, and also in the spec under a reading the spec allows. The exhaustive tests miss it because their alphabet has no END_CLAIM. | **CE7:** `g`: R grants b `{(END_CLAIM,s)}` at τ1; `x`: END_CLAIM by b at τ2, target `a`; `a`: ADOPT by R at τ3, subject s. Records: `pos={g:1, x:2}`. Comp(2, τ(x)) holds and R3 holds. Yet `Valid(x)=True` while `Rec_2(x)=NOT_AUTHORIZED`. | TD + CD |
| P-2 | MAJOR | Thm 3 proof subtlety; A0 | **Hidden assumption.** The proof needs the recognizer to know the act order `≺`, including how same-time acts are ordered. Records carry only `pos`. BC-7 `Seq` is "sequence, never wall-clock time; BC-7 holds no clocks" (KOS-ARCH-BASELINE-003 L197). The code gets the true order from the Python list order of the full history, which is information a records-only recognizer would not have. Theorem 3 is only stated for a recognizer that has both `≺` and τ (act time). The doc's own fallback (order derived from positions) is the case it says breaks soundness. | **CE8:** `[g,r,w]` vs `[g,w,r]` with r and w both at τ=2: `w` flips False→True. | TD |
| P-3 | MAJOR | Thm 6, "Consequence" | **The consequence contradicts the proof.** The proof adds a late-recorded root grant. That completion defeats *every* records-based negative verdict, including one backed by a recorded revocation. So "positive negative evidence: a recorded revocation, a recorded bar, or a recorded refusal" cannot license NOT_AUTHORIZED under open world. The code's own T6 check confirms this (2611/2611 negatives are defeasible). A "recorded bar" is also a category error: bars affect eligibility (Elig), not validity (Valid). **Hidden premise:** a Root actor exists whose root scope can grant e's scope. | **CE11:** recorded REVOKE makes `w` invalid; adding a late root grant makes `w` valid. | TD |
| P-4 | MAJOR | Thm 5 | **The theorem is unstated and the consequence overreaches.** "∎" with no proof. `Rec_k` also depends on `≺`, Root, κ, ι, B₀ and μ, which the statement omits. The "iff prefixes are retained" clause is degenerate: under R1 (append-only) with positions kept, every prefix can be rebuilt by filtering `seq ≤ k`. Being gitignored with no git history does not by itself make past recognition irreproducible. The real gaps are that append-only cannot be verified (no provenance, C-3) and that there is no date-to-position map μ. "ADR-C6 load-bearing" therefore rests on a misattributed cause. ADR-C6 is an R-37-gated open question, not an ADR (BASELINE-003 L324). | — | TD |
| P-5 | MAJOR | Thm 4 | **Missing premise.** The theorem needs λ < ∞ and `now(k) ≥ t+λ`. With λ = ∞ the verdicts are UNDETERMINED at both k (verified: `[AUTH, NOT_AUTH]` at λ=0, `[UND, UND]` at λ=∞). τ(g) is unspecified. The cited `test_late_revocation_flips_recognition` does not exist. The printed minimal flip is a different example (validity of `gab`). | — | TD |
| P-6 | MINOR | Remark after Cor 1.1 | "A partial order is not enough" is false as stated. Any strict well-founded order in which acts depend only on strict predecessors gives uniqueness, including strict `<` on τ (which equals "a ban on same-instant interaction"). What fails is the non-strict `⪯`. Also: the uniqueness A0 buys is uniqueness *relative to an arbitrary tie-break*, so outcomes can depend on an order nobody recorded (CE8). | — | TD |
| P-7 | MINOR | Thm 1 | **Valid.** Recursion on a finite strict total order gives existence and uniqueness. The cascade "hypothetical act at t" is not defined in V1, which is defined only for acts in H. Its position relative to acts at the same τ is unspecified; the code places it after all of them. The quantifier "for the relevant x" in Root is informal. | — | TD (minor) |
| P-8 | MINOR | Prop 7 | Bullets 1–2 hold. They are true by construction, because `effective()` checks that the act is recorded. Bullet 3 inherits P-1: Theorem 3 fails for END_CLAIM. A second witness needs no false record at all: a late revocation (Theorem 4) yields an act that is effective but invalid while R3 holds. | — | TD |
| P-9 | MINOR | Prop 8 | The inputs are not disjoint: `H≤k ⊆ H`. "No contradiction between layers" is vacuous, since the layers define different predicates. "Theorem 3 bounds disagreement" holds only under Comp + R3; otherwise disagreement is unbounded (Thm 4, Thm 6). Not tested, although the test docstring claims it is. | — | TD |
| P-10 | MINOR | §1, §3 naming | **Name collisions.** R1/R2/R3 name both record axioms and Definitions R1–R3; Theorem 3 cites "R3 (truthfulness)" and Theorem 5 cites "R1 (append-only)". `now(k)` is never defined. The well-formedness rule `from ≥ τ(e)` is stated but not enforced. Every exhaustive-test grant breaks it: its period starts at 0 with τ ≥ 1 (CE10). | — | TD |

## 2. Prose ↔ code conformance

| ID | Sev | Spec | Code | Divergence / counterexample | Type |
|---|---|---|---|---|---|
| C-1 | MAJOR | V3(d): "the grant(s) that authorized `a` are still in force at t" | `current()` cascade: *any* grant to the same holder covering the scope and in force at t | **CE1:** g1 (period 1–3) authorizes ADOPT a at τ2; an unrelated g2 is given at τ4. Under κ = cascade the code says a is current at 5; the spec says not current. If several grants authorized a, "all" vs "any" is undecided. | CD + UP |
| C-2 | MAJOR | κ ∈ {persist, cascade}; r1.1 "no ending of the authority carries over" | Validity of a grant is fixed at the time it was given, so upstream revocations never propagate down the delegation chain. κ acts only at the claim layer. | **CE3:** R→a, a→b, then R revokes R→a. b's later PERFORM is still valid, and b's ADOPT is still current under κ = cascade. This silently decides a persist policy for delegation chains. | UP |
| C-3 | MAJOR | V2 GRANT: `(GRANT, subject_of(spec))`; REVOKE/CONFIRM: `(kind, target)` | Required pairs are wildcards: `("GRANT","*")`, `("REVOKE","*")`, `("CONFIRM","*")` | **CE4:** a grant with scope `{(REVOKE, g)}` never covers the revocation (its REVOKE is invalid). Target- or subject-specific revocation authority cannot be expressed. `subject_of(spec)` is undefined for multi-subject scopes. Spec scope is `⊆ Kind_perf × Subject`, yet `covers` needs GRANT/REVOKE pairs, so the signature is inconsistent. | CD + TD |
| C-4 | MAJOR | V3(c): END_CLAIM with `τ(x) ≤ t` (r1.1: "**later** … act") | Same; there is no requirement that the END_CLAIM come after the adoption. | **CE2:** an END_CLAIM at τ1 ends a claim adopted at τ2. The claim id is conflated with the ADOPT act id. The coverage pair is written `(ADOPT\|END, subject)` in prose and `END_CLAIM` in code. | TD + CD |
| C-5 | MAJOR | V4: barred "by `bars(g)` of **the** authorizing grant" | Barred only if *all* in-force authorizing grants bar it | **CE5:** two grants, one barring and one not, give "eligible". A second grant therefore defeats a PO/ARB grant-specific bar (IND-5: "any future exception requires a separate explicit PO/ARB act"). | UP |
| C-6 | MAJOR | R2 first branch "a field φ needs is missing" | Not implemented. The only UNDETERMINED cases are "act not on record" (not a branch R2 lists) and the λ gate. | Missing or opaque fields (τ unknown, etc.) cannot be represented. | CD |
| C-7 | MINOR | R2 order of branches | `recognize_valid` checks recorded-ness before λ; `recognize_current` checks λ first | Same output; inconsistent structure. | CD |
| C-8 | MAJOR | §6: λ is the "closure horizon for **absence-based negatives**" | R2 and the code gate **all** verdicts with λ, positives included | **CE6:** at λ = ∞ even a recorded root act is UNDETERMINED. R3 "secured positive" is defined but never instantiable. | TD |
| C-9 | MINOR | O1: procedural preconditions (HANDOFF ∧ START) | `procedural_ok=True` parameter, never computed. HANDOFF, START and REGISTER are not in the kind set, although §5 maps COMMISSIONING to them. | — | CD |
| C-10 | MINOR | Root ⊆ Actor × Scope | Root is keyed by (actor, kind), with no subject and no period. Root acts cannot be revoked or bounded. | Perpetual root is a silent policy. | CD + UP |
| C-11 | MINOR | ι is the "interval convention" (for grants) | ι is also applied to claim periods | **CE12:** claim with period [1,3]: current at t=3 under closed, not under half_open. One parameter is used for two conventions. | UP |
| C-12 | NOTE | A0 tie-break | Code uses (τ, index in the history list); the list is supplied by the test harness | See P-2. | — |

## 3. Test adequacy

| ID | Sev | Finding |
|---|---|---|
| T-1 | MAJOR | **The exhaustive alphabet is narrow.** 8 fixed templates; kinds GRANT, REVOKE, PERFORM and ADOPT only. No CONFIRM/proviso, END_CLAIM, bars, finite grant or claim periods. Actors {R, a, b}, one subject, τ ∈ {1, 2, 3}, each template at most once. Histories have ≤4 acts for Thm 1 and Lemma 2, and ≤3 acts for Thm 3, Thm 4, Prop 7 and Thm 6: 7458 = 8·3 + 28·9 + 56·27 + 70·81. Only κ = persist and ι = closed are used. Every recognition check uses λ = 0 and now = 100, so the λ gate is never exercised exhaustively. |
| T-2 | MAJOR | `current()` and `eligible()` are never exercised exhaustively, even though ADOPT `Ab` is in the alphabet. The Current half of Theorem 3, Lemma 2 for Current, κ = cascade, V4 and ι are covered only by single hand-written scenarios. That gap is how P-1, C-1, C-4 and C-5 went undetected. |
| T-3 | MAJOR | `test_order_from_positions_breaks_soundness` does not test what its name says. It never uses `pos` or recognition. It changes the τ values and compares two *different histories*. This is trivial and does not support the Theorem 3 "subtlety" that cites it. |
| T-4 | MINOR | T1 check: brute-force fixed points use the same `_valid_given` as the recursion. This shows the implementation is self-consistent, not that it conforms to V1–V2. |
| T-5 | MINOR | L2 check: a single probe (one REVOKE of gRa at tmax+1), on Valid only. |
| T-6 | MINOR | `same_instant_nonuniqueness_absent_in_template_alphabet` passes when the phenomenon is *absent*. It supports no theorem; the explicit 4-act construction is the real evidence. `stable_assignments_same_instant` ignores proviso/CONFIRM. |
| T-7 | MINOR | **Mislabelling.** The docstring claims exhaustive checks of "Theorems 1–6 and Propositions 7–8". Thm 5 and Prop 8 have no check, and Prop 7 is checked only for bullet 1, which holds by construction. The spec cites the nonexistent `test_late_revocation_flips_recognition`. |
| T-8 | MAJOR | **Tests that pass while encoding non-r1.1 behaviour:** `S0103_before_transcription_records_say_not_authorized_under_closed_world` asserts NOT_AUTHORIZED derived from a *missing* record, which r1.1 forbids ("never 'not authorized'"). `S0103_after_transcription_authorized` asserts retroactive effect after late transcription, which is contrary to C1-a (see F-3). |

## 4. Ambiguities: places where independent implementers would diverge

| ID | Sev | Point | Divergent readings |
|---|---|---|---|
| A-1 | MAJOR | Bars (V4) | Any authorizing grant bars, or all must (CE5). Does `bars ⊆ Purpose × Actor` bind the performer or the user of the act? |
| A-2 | MAJOR | Cascade (V3(d)) | The specific authorizing grant(s) vs any covering grant (CE1); all vs any; one level vs transitive (CE3); where the hypothetical act at t sits among same-τ acts. |
| A-3 | MAJOR | Coverage (V2) | Specific `(REVOKE, g)` vs wildcard (CE4). Does `(ADOPT\|END, subject)` mean the kind-specific pair or either? `subject_of(spec)` with several subjects. |
| A-4 | MAJOR | Ending (V3(c)) | Must the END_CLAIM come after the adoption (CE2)? Is it prospective only, or can annulment be retroactive (void from the start)? ALM silently collapses revoke, supersede and annul into one prospective END_CLAIM. Whether another ADOPT supersedes an earlier claim depends on U. |
| A-5 | MAJOR | Instant semantics | REVOKE takes effect strictly before (`≺`) while END_CLAIM takes effect at `τ ≤ t`. Implementers will pick one or the other for same-instant cases. |
| A-6 | MAJOR | Actor | Is Actor a process identity or a role? INV-ATTR-1 (adopted 2026-08-15): "process identity is evidential only and never an authority input". INV-ATTR-2: self-declared identity is `asserted`, never `attested`. That conflicts with V2 (holder = actor) and with A3 (truthfulness) for the actor field. |
| A-7 | MINOR | Recognition details | Unrecorded act: UNDETERMINED vs NOT_*. λ: applied to positives or not (C-8). `now(k)` undefined. Is an ill-formed grant rejected or treated as invalid? |

## 5. Fidelity to TD-001 r1.1 (§7)

| ID | Sev | Finding | Type |
|---|---|---|---|
| F-1 | **BLOCKER** | **"r1.1 is expressible in ALM without change" and "r1.1 under-specifies λ" are contradicted.** r1.1 fixes the negative case ("otherwise 'cannot determine', never 'not authorized'") and puts no horizon on positives ("when its records, as they stood… establish 1–3"). ALM's single λ cannot reproduce that. With λ < ∞, R2 outputs NOT_AUTHORIZED / NOT_CURRENT from absence, which r1.1 forbids (test T-8). With λ = ∞, R2 outputs nothing positive (CE6). r1.1 corresponds to a *projection* of Rec at λ=0 (negatives mapped to UNDETERMINED), or to two horizons (λ⁺ = 0, λ⁻ = ∞). ALM states neither. The doc's §3 consequence of Theorem 6 ("requires a policy (λ)") softens r1.1's "never" into "unless λ". | UP + TD |
| F-2 | MAJOR | **Clause (3) drift.** "No *later* properly authorized act" loses "later" (C-4). "No ending… carries over **under the organisation's rules**" is compressed into one global binary κ, applied one level deep and only to claims (C-1, C-2). The evidence suggests κ may be **type-dependent**: Stress Test 8 says decisions "Often persist (**if certified**) · Can be overturned (by higher Authority)", with verdict H-B "different authority types could have different revocation effects". | UP |
| F-3 | MAJOR | **C1 is not parameterized; it is decided.** G1 fixes validity by acts (C1-b), and C1-a is mapped to layer O computed on records *as of now*. Under C1-a as written ("an act" reads "a recorded act" *at the time of that act*), authority would be the recorded grant at time τ(e), via μ(τ(e)). Work done before the grant was transcribed stays unauthorized. ALM's O layer instead makes it effective after late transcription (S0103 test). Counter-evidence for C1-a, known to the checkpoint (§2: §10.2 "recorded human act… leans to C1-a"), is not recorded in ALM's goals table. | UP |
| F-4 | MINOR | U (optional invariant) is parameterized but not implemented, which is acknowledged. ι is reused for claim periods (C-11). Root is perpetual (C-10). Delegation narrowing (N) is excluded, so a holder of `(GRANT,*)` can grant anything. That is a policy consequence r1.1 does not decide. | UP |

## 6. Evidence fidelity (8 repository sources checked)

| ID | Sev | Source | Result |
|---|---|---|---|
| E-1 | MAJOR | `docs/ARCHITECTURE_DECISION_RECORDS.md` ADR-001 | The quotations are accurate (L35 "Legitimacy is always point-in-time. Never 'current legitimacy.'"; L39 "Historical decisions maintain their legitimacy at decision time"). The status claim "ACCEPTED, implemented" omits that ADR-002 (L68) supersedes ADR-001 "(partial)" and ADR-006 (L293) supersedes it "(enforcement)". The file summary L589 says "0 Superseded", which is internally inconsistent. The checkpoint itself flags the dates (2024-Q1) as predating the file (git add 2026-05-11). **Substance:** ADR-001 supports *act-time validity* (V2), not κ = persist. It also rejects "current legitimacy" outright, which is in tension with the very notion of "currently authoritative". It is used as the main evidence for G4 / κ = persist. |
| E-2 | MAJOR | `docs/architecture/contexts/Round6F2_AuthorityStressTest.md` Stress Test 8 (L362–394) | Quoted accurately ("Revocation stops future Authority… prior… remains as historical fact"). The fuller text: "Often persist (if certified) · Can be overturned". It is a research stress test (2026-06-03) favouring per-type behaviour (H-B), not a uniform persist rule. |
| E-3 | MAJOR | `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` §0.2 / §10.1 / §10.2 | Verified: PROPOSED; ARB "APPROVABLE AS A PROPOSAL"; §0.2 ADOPT lists ARCH-INV-1 and "Per-type lifecycle refinement §10"; §0.3 stages the richer Rule model; OQ-11 is open; T3 scope. **Omitted counter-evidence:** §10.1 "not currently authoritative — because **its authority lapsed**" reads as a cascade-type ending. ALM's §6 says "evidence favours persist" without recording this. §10.2 leans C1-a (see F-3). |
| E-4 | OK / MINOR | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` | **R-72** (L58): proviso text verbatim ✓. **R-77** (L63): "supersession requires an EXPLICIT ACT, NEVER INFERENCE" ✓, but nothing about *annulment* (G6 overextends). **R-80** (L66): four terms ✓, scope annotation ✓. **R-86** (L72): Decision Authority adopts R-81..R-85 and authorizes batch 7. It never says R-72's proviso is satisfied, and its actor differs from R-72's (ARB). "R-72 → R-86 as CONFIRM" is an *inference*; R-77 (between them) says "WP-4B REMAINS BLOCKED". **R-34** (L20): only ruling-classification discipline ✓ (the checkpoint's overload note is correct). **R-41** (L27), cited for U ("one current claim per subject; later prevails"): it actually says "an artifact has one authoritative lifecycle state at any moment". That is lifecycle-state consistency, not claim uniqueness or "later prevails" (misattribution, MINOR). **R-60** (L46), cited as an R3 failure: it is a recording-note vs ruling *classification* discrepancy, not a false recorded field (stretch, NOTE). |
| E-5 | OK / MAJOR | `docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` | A-1.4 L277 / L338 / L348 verbatim ✓. A-4.3 "PERMIT WITH DISCLOSURE" (L362) ✓. A-5.2 "Do not introduce a blanket prohibition" (L413) ✓. **Modelling defect:** IND-2 is *relational* ("may not independently verify **that same implementation**"). `bars ⊆ Purpose × Actor` and `B₀(p)` have no work or subject dimension. The A1.4 scenario bars p from *all* independent verification. |
| E-6 | OK / MAJOR | `docs/publicdigit/reviews/2026-08-15-KOS-GOV-ATTRIBUTION-001-decision-registration.md` | INV-ATTR-1/2 (L19), P-5 "must not act as an authorization or enforcement gate" (L25), asserted/attested (L6, L53) ✓. Consequence: see A-6 (actor identity as an authority input, and A3 truthfulness for actor fields). |
| E-7 | OK / MAJOR | `docs/knowledgeos/reviews/KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` | I-2 conjunction (L67, L232) ✓. Seq is not a clock (L197) ✓. Store gitignored, no provenance (C-3), ADR-C6 is an R-37 question (L324) ✓. T-3 append-only is **`Proposed`** (L242), but ALM cites it as an axiom from a source. **Contradicting observation:** START "**does not consult grants at all**" (L134) and the human act's "entitlement" is never validated (L123). O1's `Rec_k(Valid(e)) = AUTHORIZED ∧ procedure` is therefore a *target* conjunction, not the observed BC-7 behaviour: a target design presented as current fact. |
| E-8 | MAJOR | Checkpoint vs ALM | **REVOKE is unevidenced on the governance side.** Checkpoint Q6: "no grant revocation has ever been observed (47/47)"; the observed endings are AMEND, CANCEL and "ruled ineligible", and none of them is modelled. Revocation evidence is product-side and research-level only. **IND-1 contradiction:** checkpoint §3: "IND-1 … **is authority, not eligibility**"; ALM V4 puts IND-1 in B₀ (eligibility), which changes engineering self-acceptance from invalid to valid-but-ineligible. **Minor counts:** G3 says "n ≥ 6" but lists 5 sources. `TemporalAuthorityWindow::stateAt` is closed-interval ✓ (`app/Contexts/Membership/Domain/Committee/Constitutional/TemporalAuthorityWindow.php` L20–31). |

**Not verified.** I did not open corpus S-IDs (S0067, S0071, S0058, S0079, S0092, S0095, S0103, S0104, S0062, S0016). Those quotations rest on the checkpoint only.

## Overall verdict: **NOT SOUND** (as currently stated)

**What survives:**
- The validity-layer recursion (Theorem 1, Corollary 1.1).
- The same-instant non-uniqueness observation.
- Theorem 4 (non-monotone recognition), provided λ < ∞ is added as a premise.
- Theorem 6's core statement: under open world, absence-based negatives are unsound.
- The validity / eligibility / effect layering as a framing device.

**What fails:**
- **Theorem 3 / Lemma 2:** fail at code level, and under a reading the spec allows (P-1, CE7).
- **Theorem 3's dependency on act order:** the act order it needs is not available from records (P-2).
- **Theorem 6's stated consequence:** contradicts its own proof (P-3).
- **Theorem 5's architectural consequence:** a non-sequitur (P-4).
- **§7's central fidelity claim** ("r1.1 expressible without change; r1.1 under-specifies λ"): contradicted (F-1).

**Silent policy decisions:**
- κ handling: any grant rather than the authorizing grant; persist down delegation chains; global rather than per-type.
- Bars: "all grants must bar".
- Ending: prospective-only, with no "later" requirement.
- Wildcard-only GRANT / REVOKE / CONFIRM scope.
- Perpetual root.
- C1 resolved toward C1-b.

**Test-suite limits:** the restricted alphabet structurally cannot detect most of these issues, and two checks are mislabelled or vacuous.

**Evidence status:** citations are mostly accurate verbatim. However, the key evidence for κ = persist (ADR-001, Stress Test 8) is weaker and narrower than stated. The model's primitive REVOKE has no governance-side instance.

The defects look repairable:
- END_CLAIM and the act order made explicit fields of the act.
- Two λ horizons (or an explicit projection) for r1.1.
- Cascade defined against the specific authorizing grant(s), with transitivity as a parameter.
- Bars that carry a subject/work dimension, with any/all as a parameter.
- An exhaustive alphabet that includes END_CLAIM, CONFIRM, periods, bars, κ = cascade and λ > 0.

None of these repairs is done here.
