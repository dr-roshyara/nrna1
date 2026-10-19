# Critical evaluation of DEC (decide-or-escalate core) versus the full ALM-r2 (2026-10-18)

**Status:** evaluation only.
- ALM-r2 stays **paused**. DEC is **not** implemented.
- The ALM-r1 evidence and the review-2 records are unchanged.
- One read-only analysis probe was run against the frozen ALM-r1 code: `probe_policy_quantification.py` / `.out`.

**Evidence labels:**
- **[RV]** reviewer-verified: review 2, "Verified as correct";
- **[AR]** author-reproduced counterexample;
- **[PR]** proof (premises stated);
- **[BE]** bounded computational evidence;
- **[RP]** reported only;
- **[CJ]** conjecture;
- **[GOV]** governance choice.

---

## 1. Challenge of my own recommendation

### Q1 · Does reusing the "verified validity layer" give DEC everything it needs?

**No.** My earlier wording overstated what is verified.

**What is verified:**
- [RV] the Th-1 and Th-2 proofs (existence, uniqueness, locality of validity);
- [RV] implementation–oracle agreement on **validity** over the whole ALM-r1 parameter grid (6,000 trials). The oracle was not independent (R2-09), so this is agreement between two same-author encodings.

**Unverified dependencies inside the V layer that DEC would inherit:**
- **Auth** is conflated with Form (R2-07) [AR].
- **The C1 = a semantics** are inconsistent (R2-08) [AR]. DEC quantifies over C1, so it needs this.
- **An unstated grant period** silently means open-ended (R2-10, C7) [AR].
- **RD 1** is per claim kind only (R2-11).
- **The C6 proxy** (R2-16) and **BAR root exemption** (R2-13).

**Accurate statement:** the V layer is verified **for C1 = b, explicit periods, and Valid** (not Auth). The repaired V layer is only *specified* (ALM-R2-SPEC S-4); it is neither implemented nor reviewed.

### Q2 · Does "positive under every undecided policy" prevent the review-2 false positives?

**No, not by itself.** This is the most important correction to my recommendation.

| False positive | Cause | Removed by policy quantification? | Evidence |
|---|---|---|---|
| N1: unrecorded tie order (R2-01) | world semantics: order placement | **No**: T under 256/256 configurations | [BE] probe |
| N2: unknown revoker (R2-02) | world semantics: ⊥ holder read as a definite value | **No**: 256/256 | [BE] probe |
| N3: unverifiable revocation (R2-03) | RD 6 (υ) not modelled | **Only if** υ is in the quantified set **and** its "counts as undetermined" semantics is correctly implemented | [BE] probe (ALM-r1 has no υ); [CJ] for DEC |
| R2-04: Th-3 premise too weak (⊥ τ, domains) | soundness theorem | **No** | [AR] |
| R2-14: eligibility with unknown implementer | world semantics | **No** | [AR] |

**Conclusion:**
- DEC's safety depends on the **same repaired world semantics** as ALM-r2's positive verdicts (S-6.1: ⊥ fields, order placement, υ, π).
- Policy quantification gives **non-usurpation**, not safety.
- **Both are necessary. Neither is sufficient.**

### Q3 · Can DEC reliably separate missing evidence from an unresolved governance decision?

**Not as I defined it.**
- My definition ("with the evidence complete enough that worlds agree…") is **circular**.
- The categories are **not exclusive**: a case can depend on evidence and on policy at once.
- Under an open world there is **always** a residual evidence gap: an unrecorded grant or revocation could exist (Th-4, Th-6 [RV]).

**Defensible definition** [CJ: to be proved and tested]. Let `EST(θ)` mean "φ holds in every world under policy θ". The reasons are **multi-label** and relative to the records as of position k:

| Label | Definition |
|---|---|
| **policy-sensitive** | `∃ θ₁, θ₂ ∈ Π: EST(θ₁) ∧ ¬EST(θ₂)`. The output lists the *minimal* parameter assignments under which the case becomes decidable |
| **evidence-sensitive** | `∃ θ: ¬EST(θ) ∧ ∃ w ∈ W(θ): φ(w)`. Unknown or contradictory recorded facts would decide it. The output lists the fields and acts concerned |
| **no recorded basis** | `∀ θ, w: ¬φ(w)`. See Q5 |
| **open-world caveat** | always attached unless a completeness presumption is in force |

Both main labels can hold together, and the explanation must say so.

### Q4 · Can DEC explain without presenting inference as fact?

**Only if every statement in an explanation carries its provenance type**, and this must be tested:
- **RECORDED:** a fact from a record, under AX-Truth;
- **DERIVED:** computed from recorded facts by a stated rule;
- **HYPOTHETICAL:** "if record X existed, or if field Y were Z";
- **POLICY:** "depends on reserved decision D".

This is feasible, but nothing in the design guarantees it yet [CJ].

**Risk:** the explanation layer can **move complexity rather than remove it**. Minimal reason sets are combinatorial.

### Q5 · Does `NOT-AUTHORIZED-ON-RECORDS` mean the act was proven unauthorized?

**No. The terminology is corrected.**
- Under C1 = b and an open world, **no record-based negative is sound in general**. [PR + RV]: "never F under λ⁻ = ∞"; Th-5's existence part. A grant could exist unrecorded.
- **Corrected name:** **`NO AUTHORITY ON RECORD`** (no recorded basis under any open policy). It is *not* a finding that the act was unauthorized.
- Under C1 = a a stronger reading may become possible (ALM-R2-SPEC Th-5a: record-based negatives are sound once a later-recorded entry exists). That is **[CJ], unproved**, and it applies only if the ARB chooses C1 = a.

### Q6 · Can later records invalidate a previous operational decision?

**Yes.** [RV]: the Th-4 construction reproduced. A decision recognized as authorized on the records as of k can become "not established" when a revocation is recorded late. Under r1.1 the change is T → U.

**What KnowledgeOS must do:**
1. Store every decision with its **evidence basis**: the record position k, the policy set Π, and the reasons.
2. **Re-examine** affected decisions when new records arrive.
3. Raise a **recognition-change event**. Never back-date; this follows the corpus's no-back-dating rule [Src].

**Classification:**

| Item | Class |
|---|---|
| detecting a change | research and implementation; provable from Th-2 locality once repaired |
| what to do about actions already taken (continue, suspend, roll back, ARB review) | **[GOV]** |
| **bounding** flips: if every act is recorded within Δ of its act time, decisions about times ≤ now − Δ cannot flip | **research**: follows from repaired Th-3 (completeness) [CJ until Th-3 is re-proved] |

### Q7 · Is DEC genuinely smaller?

**Less than I claimed.** Tally of the 13 MAJOR findings of review 2 (12 + R2-14):

| Still needed in DEC | Not needed in DEC |
|---|---|
| R2-01, R2-02, R2-03, R2-04, R2-07, R2-08 (C1 is quantified), R2-09, R2-10 (π quantified), R2-11 (RD 1 quantified), R2-14: **10 of 13 needed** | R2-05 (sound negatives), R2-06 (dated questions): **2 of 13** |

R2-13 and R2-16 are MINOR and carried either way.

**DEC adds new work:**
- quantification over Π. The full product of the reserved parameters is very large, so a **relevance analysis** is needed: which parameters can affect φ. This is research;
- the reason classification (Q3);
- the provenance-typed explanations (Q4);
- the re-examination invariant (Q6).

**Honest verdict:** DEC does **not** reduce the hard part. The hard part is the correct positive recognition under incomplete records, and it is shared by both paths. DEC removes negatives and dated questions, and adds the explanation and quantification layer. **The effort is of the same order. The fit to the business goal is much better.**

---

## 2. Classification of every unresolved item

| Item | Class |
|---|---|
| Repaired world semantics for positives (⊥ fields, order, υ, π) | **implementation question** (specified in ALM-R2-SPEC S-6.1) + **research** (soundness proof) |
| Th-3(b) soundness re-proof with record-based premises | **research question** |
| Auth/Form separation; C1 = a semantics; C7; RD 1 per ending kind | specified → **implementation question** |
| N_w scope-domain construction; the ⊥ domain for proviso, bars and cperiod | **specification ambiguity** (found during the paused ALM-r2 draft) |
| A relevance analysis to make quantification over Π tractable | **research question** |
| Exact reason-label definitions (Q3) and their soundness | **research question** |
| Provenance-typed explanations (Q4) | **specification ambiguity → research** |
| Flip bound under a recording deadline (Q6) | **research question** |
| Whether to impose a recording deadline, and its length (T1-20) | **governance decision** |
| What to do with actions taken under a decision that later flips | **governance decision** (no register item: a **candidate**, not minted) |
| C1, RD 1 (per kind × ending), RD 6 (υ), C7, C10, C6, C12, C14, C15, BAR, BASIS, ιc | **governance decisions**. DEC does **not** need them decided: it quantifies over them |
| Whether the independent oracle and the implementation agree on the positive layer | **implementation question** (an oracle built from the spec is in progress for the ALM-r2 spec; it is reusable) |

### The recording-deadline versus re-examination question in business terms

| Option | Effect | Cost |
|---|---|---|
| **Re-examination only** | decide as soon as the records establish authority; re-check on every new record; raise an event if a decision loses support | fast decisions. Occasional reversals, and someone must decide what happens to actions already taken |
| **Recording deadline (a waiting window)** | require acts to be recorded within Δ; decide only on matters older than Δ | far fewer reversals (provably none, if the duty is honoured: research). Decisions are delayed by Δ, and the duty must be enforced |
| **Both** | decide early, re-examine, with a deadline as the backstop | most robust; most process |

**Who decides:** the Principal or the authorized ARB. This is operational governance policy.
- The length of the window is T1-20 in the register.
- The reversal remedy has **no register item yet**: a candidate.

## 3. Minimum evidence before acceptance (for whichever path)

| Property | Required evidence |
|---|---|
| **Safety** (no positive decision unless authority is established) | (1) a re-proved soundness theorem with record-based premises, including completeness; (2) every review-2 false positive as a regression (N1, N2, N3, N9 and the R2-04 probe), each shown to give "not established"; (3) bounded differential testing against an **oracle built by a separate agent from the specification only**, over an alphabet with ⊥ fields, ties, unverifiable endings and unknown periods; (4) the stated limit: safety holds only under completeness |
| **Non-usurpation** | (1) a static check: no defaults for any reserved parameter; (2) a proof that `Decide` quantifies over all of Π (monotonicity: deciding a parameter can only *add* decidable cases); (3) a mutation test that removing any parameter value from Π changes no "decide" into a different decision |
| **Explanation quality** | (1) a soundness proof or test for each reason label (Q3); (2) completeness: every non-decision has at least one reason; (3) every explanation statement carries a provenance type; (4) a scenario corpus from the sources (S0058, S0092, S0103, S0052, S0104, the v1 cases) with expected explanations derived **by hand from the specification** |
| **Human authority** | property tests over event logs: H-1 (a recommendation is never recorded as a decision); H-2 (a reserved decision requires a referenced ARB act). Both are sourced (ES-001.2, R-34, G-2/R5b, ARCH-INV-1) |
| **Re-examination** | (1) a proof that recognition changes are detected (via Th-2); (2) tests that every T → not-T transition in the bounded space raises an event; (3) a flip-bound theorem under a deadline (research) |

**What can be verified:**
- safety under completeness;
- non-usurpation;
- detection of recognition changes;
- the label semantics.

**What remains a conjecture until proved:** the flip bound; Th-5a (C1 = a negatives).

**What depends on governance:** the deadline and the reversal remedy.

**What cannot be guaranteed by any record-based system:** safety **without** completeness.

## 4. Comparison of the two paths

| | **A: DEC** | **B: full ALM-r2** |
|---|---|---|
| Fit to the business goal | **direct**: decide, escalate, explain, preserve ARB authority | indirect: gives verdicts (incl. negatives and dated answers); decide/escalate and explanations would still need to be built on top |
| Shared hard part (correct positive recognition) | required | required |
| Majors to close | 10 of 13 | 13 of 13 |
| New work | quantification relevance analysis; reason labels; provenance explanations; re-examination | sound-negative characterization (falsified once); dated questions (needs evidence BC-7 lacks) |
| Policy dependencies | **none need deciding** (quantified). Deadline and remedy are [GOV] | needs λ⁻ and policy values *configured* to say anything; the same governance list |
| Correctness risk | concentrated in the shared layer + the new explanation semantics | the shared layer + negatives (Th-5 already failed once) + dates |
| Maintenance | parameters disappear from Π as the ARB decides them; the explanation layer must evolve with them | many configuration combinations to maintain |
| Capability gained | safe autonomous decisions in clear cases; grounded escalation packages | full audit-style answers |
| Capability deferred | proving "not authorized"; historical "as of date" answers | none (but slower to any business capability) |

**Recommendation: Path A (DEC), for business fit, not for size.** The effort is of the same order. A delivers exactly the capability KnowledgeOS needs, and B's extra results (negatives, dated answers) need evidence the platform does not have today (absence witnesses, record times).

## 5. Exit criteria for one bounded round (if A is authorized)

1. Every review-2 false-positive regression gives "not established", for the intended reason.
2. The soundness theorem is re-proved with record-based premises. Non-usurpation monotonicity is proved.
3. The **separately built oracle** agrees with the implementation on the positive layer, Decide and the reason labels, over the disclosed bounded alphabet. Every disagreement is resolved **against the specification**.
4. The reason labels (Q3) are proved sound on the bounded space. Every explanation statement is provenance-typed.
5. H-1, H-2 and re-examination are tested over event logs.
6. **One fresh independent review finds no BLOCKER or MAJOR.**
7. All governance items are listed for the Principal, with none decided.

## 6. If the next independent review fails

**Stop repairing.** Fall back to the **"clean-record" fragment** of DEC: KnowledgeOS decides only when **all** of the following hold.
- The relevant records have **no** unknown fields, **no** unordered ties, **no** unverifiable endings and **no** unstated periods. These conditions are syntactically checkable.
- The answer is the same under every open policy.
- The case is inside the verified V layer: explicit periods, Valid, and **C1 provably irrelevant**. On today's records that holds only if the ARB has decided C1 = b, or record times exist (see §7).

**Everything else is escalated to AI and ARB.** This fragment rests only on results already verified (Th-1, Th-2 [RV]), plus a much simpler soundness argument: no ⊥, so a single world. It is safe by construction and conservative by design. The cost: more escalations.


## 7. The strongest arguments against my own recommendation, and what they change

1. **DEC is not smaller** (Q7). Accepted: the recommendation now rests on business fit only.
2. **Policy quantification does not give safety** (Q2, [BE] probe). Accepted: safety must come from the repaired world semantics, as in any path.
3. **On today's records, every sound model escalates almost everything.**
   - The live BC-7 store has **no record times**, no grant periods and only partial holder linkage (TD-001 report §Unknown; S0067 L94; S0071 §5.2).
   - **[BE] probe** `probe_c1_without_record_times.out`: even a perfectly clean case (root grant, then the act) is **not robustly decidable while C1 is undecided**, because under C1-a record times are needed. With record times known, it is decidable.
   - *Caveat:* the probe uses ALM-r1's C1 = a code, which is partly defective (R2-08). This simple case does not touch the defect.
   - **Consequence:** the practical value of *any* model (DEC or ALM-r2) depends more on **(a) record quality** and **(b) the C1 decision** than on model sophistication.
4. **A staged path is safer and faster than full DEC.**
   - **Stage 1:** the **clean-record DEC** (§6), with Decide robust over Π, escalation with simple reasons ("field X unknown", "C1 undecided", "authority of ending act not on record"), the human-authority invariants, and re-examination.
     - Its safety argument needs no world enumeration: no ⊥, so one world.
     - **It answers every review-2 false positive by escalating, by construction.**
   - **Stage 2** (later, separately authorized, only if Stage 1 shows too many escalations): incomplete-record recognition. This is the shared hard part.

**Revised recommendation:** **Path A, staged: Stage 1 = the clean-record DEC** as the bounded round. Full world semantics are deferred to Stage 2.

**Two record-quality requirements are surfaced for the architecture.** Each is a requirement, not decided here:
- **R-Q1:** record a record time per entry;
- **R-Q2:** record the grant period, holder and issuer link explicitly.

Without them, autonomous decisions stay rare whatever the model.
