# Chapter 1 §1.14.1 — bounded correction (step 1) and targeted review (2026-10-18)

*Authorized by the Principal ("go ahead with the bounded §1.14.1 correction"; detailed instruction of the same day). One correction pass plus one independent review, then stop. The reviewer was a fresh read-only session of the same model family, not an external expert. It was given the text and the authorized evidence only, and built its own encoding from the text.*

## 1. Correction register (findings of `CH-01-final-review.md`)

| Finding | Disposition | Where | Substance |
|---|---|---|---|
| F1 empty vs unknown citation | **corrected, verification incomplete** | §1.14.1 | ∅ (explicitly none) → F unless a root act; ⊥ (unknown) → κ. *Review:* κ-constitutive turns missing evidence into F without C1-a, so κ should be tied to C1 (open) |
| F2 two time axes | **corrected, verification incomplete** | §1.14.1 | act time, record position, effective time; ρ selects the records known. *Review:* no recording **time**; ρ wired only into condition (i); nested views unspecified |
| F3 authorized grant endings | **corrected for endings; issuance not covered** | §1.14.1 | endings count only if authorized; root acts end the regress. *Review:* **issuance** of grants (delegation) is never checked (open defect) |
| F4 mutual annulment | **corrected for υ = undetermined only** | §1.14.1 | least fixed point; cycles → U. *Review:* not monotone under υ = "does not count", so there is no least fixed point; wrong theorem cited (Knaster–Tarski → Kleene/Kripke on a cpo) |
| F5 same timestamps, intervals | **partly corrected** | §1.14.1 | total order by (act time, record position); half-open windows. *Review:* intervals mix act time with the total order (same-moment consumption race) |
| F6 total headline | **not achieved** | §1.14.1 | causal order and the "no adoption" case were added. *Review:* "no adoption" is F in the formula but U in the headline; annulled adoption has no headline |
| F7 strong Kleene named | **corrected and verified** | §1.14.1 | — |
| F8 joint coverage | **corrected as parameters χ, ω; definition incomplete** | §1.14.1 | *Review:* covers() and the actor–holder link undefined; ω has no scope argument |
| F9 supersession, revocation of revocation, act annulment | **partly corrected** | §1.14.1 | σ parameter; act annulment added. *Review:* σ-conditional recursion outside the fixed point; annulment of a revocation has no defined effect |
| F10 standing grows only | **corrected and verified** (Property 3) | §1.14.1 | — |
| F11 C1 against AMS L51 | **corrected and verified** | §1.13.1 | both concepts with consequences; no silent choice |
| F12 SC-2 analogy | **corrected** | §1.14.1 (π) | labelled Int; AMS reading added. *Review:* the AMS line is L123, and AMS states it as a defect, not as support |
| F13 two senses of standing | **corrected and verified** | §1.2 | de jure vs recognized |
| F14 §1.3 inference support; normative effect | **corrected and verified** | §1.3, §1.14.5, glossary | valid deductions inherit support; normative effect vs standing change unified |
| F15 §1.16.8 claim types | **corrected and verified** | §1.16.8 | "evidenced"; stipulations and interpretive readings added |
| F16 conflicting decisions | **corrected in the business text** | §1.14.1 | C6 alternatives stated. *Review:* "judged on its own" still silently adopts "both stay current" in the formula |
| F17 quality-gate example | **corrected and verified** (code re-read) | §1.14.5 | real switches; false self-report |
| F18, L1 editorial | **corrected** | §1.14.1, glossary, numbering | reserved-decision numbering unified (1–6) |
| Business explanation | **added** | §1.14.1 | five distinctions; six safeguards; realistic cases incl. delegation |

**Defects I found in my own correction while testing, and fixed:**
- (a) a backdated annulment could never end a claim under ρ = at-the-time;
- (b) a consuming act fell outside its own window;
- (c) an act annulling its own basis made its validity depend on itself. Hence the general rule: **an act's own effects never count against its own authorization**.

## 2. Proof and counterexample report

**Proofs stated in the text:**
- **Property 2**, adding a basis or adoption never turns T into F (disjunction): **holds**. *Review:* it can turn T into **U** in a consumption race.
- **Property 3**, standing grows only: **holds**.
- **Property 4**, the views agree without late-recorded or backdated entries: **holds in the encoding** after fix (c); a proof is sketched in the text.
- **Property 1**, totality: **does not hold** (F5/F6 above).
- **Existence and uniqueness of the fixed point:** **holds only for υ = undetermined**, and needs the correct theorem.

**Tests (lead's encoding; scratchpad, not part of the book):** 32/32 expected results on the 15-case suite under the relevant alternatives, and 12,000 random checks of Properties 1, 2 and 4. **These results validated the encoding, not the text.** The encoding silently returned U for "no adoption" and used the grant's recorded end date, both things the text does not say. That is how F-5 and F-7 escaped the tests and were found by the reviewer reading the text. This is the third occurrence of the lesson that a test checks the encoding, not the written theory.

**The reviewer's suite results**, derived from the text:
- **defined:** cases 4, 12, 13;
- **mostly defined:** case 9;
- **partly defined:** cases 2, 3, 5, 7, 8, 11, 14, 15;
- **contradictory:** case 1;
- **not defined:** cases 6 (under υ = does not count) and 10;
- **its own cases:** 16, 18 and 20 not defined; 19 partly; 17 defined but probably unintended.

## 3. Independent review and reconciliation

**Verdict: DEFECTIVE** overall. Criteria 1, 3, 4, 5 and 6 are defective; source fidelity is sound with minor issues. The reviewer's 19 findings were checked by reasoning:
- **Accepted:** all formal findings, F-1 to F-12.
  - F-1 (non-monotone υ) follows directly: the map U ↦ F is not monotone in the information order.
  - F-3/F-4 (issuance, delegation) follow from treating grants as primitive.
  - F-5/F-7 were masked by my encoding (above).
- **Accepted as a policy alternative, not a defect:** F-13 (a revoked actor's cycle freezes the revoker): should cycles resolve to U or by causal priority?
- **Accepted as a factual question:** F-14 (issuer vs holder in the `authority` field), supported by the resolver's own statement.
- **Accepted:** F-15 to F-19.
- **No finding is rejected.** Where the reviewer and the lead differ, it is only in emphasis: the lead regards the overall design as sound, and the reviewer agrees.

## 4. Alternative-concept register (updated; nothing chosen here)

**Kept from the final review:**
- **C1** record constitutes vs represents authority;
- **C2/κ** citation constitutive vs evidential, ideally tied to C1;
- **C4/χ, ω** joint coverage and partial overlap (needs a scope argument);
- **C5/σ** supersession standalone vs conditional;
- **C6** explicit resolution vs "later prevails" (the formula currently adopts "both stay current" silently, which the repair must parameterize);
- **C7/π** a missing grant period;
- **C8** headline order;
- **ρ** record view (needs a recording-time axis);
- **υ** unverifiable endings (valid only after the F-1 repair).

**New from this review:**
- **C10** whether cycles created by revoked actors resolve to U or by causal priority;
- **C11** whether retroactive ratification is allowed (a grant recorded after the act it covers);
- **C12** whether the root can change over time;
- **C13** whether consumption is an act, an event or a condition.

## 5. Principal decision briefs

Briefs 1–6 of `CH-01-final-review.md` stand unchanged. The repairs above are **formal**, not policy. The new items C10–C13 are recorded as alternatives. Briefs will be written when the formal repair makes them decidable, since none blocks current work.

## 6. Readiness

- **Formal defects resolved?** **No.** The correction resolved part of F1–F18 and introduced a clearer structure. The independent review demonstrated remaining **formal defects** (F-1 to F-12). They are recorded in the chapter as a **status note** in §1.14.1, so no reader relies on the claims of existence, uniqueness or totality.
- **Does anything block the next step?** **No.** Under the continuity rule, Chapter 1 becomes **baseline v1** with these tracked issues. The five-file batch does not depend on the fixed-point semantics of §1.14.1, and the domain-events file (`…1044`) may inform the repair of the time axes. The batch proceeds as authorized.
- **Repair route (proposed, not started):** at the batch checkpoint, one bounded repair of the formal model:
  - grants as products of acts, with issuance and delegation inside the fixed point;
  - υ applied after the U-valued least fixed point;
  - C1 inside the formula;
  - a recording-time axis;
  - interval domain fixed;
  - covers() and the actor–holder link defined.

  This would apply the operational baseline rule: only §1.14.1 and the claims depending on it.

## 7. Inputs from batch 4 to the proposed repair (recorded at file 3, `…1044` domain events)

- **Causal links.** Events carry `causation_id` and `correlation_id` [DE L101–105]. That is the recorded link the model needs for "an ending **caused by** *x*" (review F-15, consumption) and for nested evaluation.
- **Order must be guaranteed, not assumed.** "Do not assume ordering unless the contract guarantees it" [DE L380]. The total order of acts in §1.14.1 must be a **recorded** property (a global sequence), and where it is missing the result is undetermined.
- **Version-aware evaluation.** Stale, duplicate or newer is decided by one comparison of recorded versions [DE L372–378]. This supports adding a recording-time axis together with versions.
