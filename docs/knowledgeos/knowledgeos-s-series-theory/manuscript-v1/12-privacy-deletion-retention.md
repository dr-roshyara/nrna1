# Chapter 12. Privacy, deletion and retention

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 with independent checks; Thm 12.5(b) downgraded to ANA, citations corrected, Prop. 12.6 added; all corrections applied). Not externally reviewed. Late addition not re-reviewed: Prop. 12.6.*

**Research question (RQ9).** Privacy rules limit what may be recorded. Deletion rules remove what was recorded. How do these interact with recoverability (Ch. 7), verifiability (Ch. 8–9), contestability (Ch. 10) and corrections (Ch. 11)?

**Plain meaning.**
- For privacy rules of the kind "coarser is always allowed", that depend only on what a record can tell (its kernel), **one check suffices**. If the minimal outcome log complies, a compliant record exists; if not, none does.
- **Recording corrections can itself break such a rule.**
- **After deletion**, a past decision remains **independently** checkable only if enough evidence was kept to separate the cases that could be confused. Otherwise checking depends on a trusted statement recorded beforehand.
- **Retroactive re-evaluation and deletion constrain each other.** You cannot re-evaluate under a new rule what you have deleted, unless someone supplies the evidence again.
- **Which option to choose is a stakeholder decision.** The theory only maps the consequences.

## 12.1 Privacy constraints on records

**DEF 12.1.** A **privacy predicate** Π is a property of a record's kernel, independent of its representation. It is **upward-closed** iff π satisfies Π and π ⊆ π′ imply that π′ satisfies Π.

**Theorem 12.2 (= Thm 7.8). THM.** For upward-closed Π, a recoverable online record satisfying Π exists iff the outcome log r\* satisfies Π.

**Instances:**
- "the record does not determine attribute s";
- k-anonymity **among histories of a stated length**.

*Note:* the unrestricted history-counted form is never satisfiable (Thm 7.8 caution).

**Not instances:**
- rules requiring a field to be stored;
- minimum-retention rules;
- caps on merging.

These are not upward-closed.

**Theorem 12.3 (bitemporal records). THM.**
- (a) The analogue of Theorem 12.2 holds for (P_bi) (Ch. 11), with r\*_bi in place of r\*.
- (b) Within one truncation family D^k of a fixed base D: if Π is satisfiable at horizon k, it is satisfiable at every k′ ≤ k. *Note:* (P_bi) itself changes with k. This uses Prop. 11.14.
- (c) **CE:** a privacy predicate satisfied by the log of historical decisions alone can be violated by the minimal bitemporal log.
  - *Instance (verified by an internal reviewer):* Σ = {a, b}, histories of length 2. D(h₁, 1) = [h₁ = b], and D(h₂, 1) = [h₁ = b] ⊕ [h₂ = b]; all other values 0. Let s = [h₂ = b].
  - The historical log has classes {aa, ab} | {ba, bb}, which mix s, so it does not determine s. The bitemporal log does determine s.
  - *Remark:* this is near-immediate. ≡\*_bi is finer than the historical kernel, so an upward-closed Π that holds for the coarser log need not hold for the finer one. The historical log is not a (P_bi)-record, so there is no conflict with (a).

*Proof.* (a) applies Theorem 12.2 to the vector decision 𝔇_bi. (b) uses Prop. 11.14 and upward closure. (c) is by construction. ∎

**Plain meaning of (c).** Keeping correction history is itself information. **Privacy review must cover the corrected record.**

## 12.2 Deletion and verification

**DEF 12.4.**
- A **deletion** at time τ replaces the retained evidence E by a coarser retained part E′ = φ ∘ E (so E′ ⊑ E).
- E′ remains a function of x alone. A deletion that depends on o leaves this setting.
- The output log o is kept (ASM OF). Decisions and attestations recorded before τ may remain.

**Theorem 12.5.**
- **(a) THM.** Under the auditor's fault model W, a past decision remains **independently verifiable** (Ch. 8) after deletion iff E′ still separates that decision's W-confusable pairs (Thm 8.6).
- **(b) ANA.** Suppose records have the form (o, E′, attested items), and E′ does not separate. Then verification needs a trusted attested item a, recorded before deletion, such that (o, E′, a) determines c. Verification then holds only for auditors who trust the attester.
  - *Other routes,* outside this form: evidence re-supplied by a party (with a commitment, see the remark below), or an auditor assuming a narrower fault model W′.
- **(c) THM.** For a new rule F′:
  - re-evaluating a past decision **from the retained record** is possible iff F′ ⊑ E′, i.e. E′ determines F′ (Lemma 2.6);
  - re-evaluation remains possible if a party **re-supplies** the evidence;
  - merely **checking** the old output against F′ needs only that E′ separate the (F′, W)-confusable pairs. That is weaker than or equal to determining F′, with equality under (Conf) (Thm 8.8).

*Proof.* (a) is Thm 8.6 applied to E′. (c): the first item is Lemma 2.6; the third is Thm 8.6 and Thm 8.8 applied to F′. ∎

**Consequence: retroactivity policy and deletion policy constrain each other.**
- **(THM, from 12.5(c))** A deletion that keeps only evidence determining the current rule makes re-evaluation under a new rule F′ impossible from the record whenever F′ ⋢ E′.
- **Proposition 12.6. THM.**
  - *Claim:* suppose the possible future rules include every function X → {0,1}. Then retroactive re-evaluation from the record for all of them requires E′ to be **injective** on X, i.e. no deletion at all.
  - *Proof:* for x ≠ x′, the indicator of {x} must be determined by E′, so E′(x) ≠ E′(x′). ∎
- **(ANA)** In practice, a retroactivity requirement forces retaining evidence that determines all **anticipated** future rules. This is analogous to Prop. 7.9.

**Remark (commitments; cryptographic assumptions LIT, unverified).**
- **Unsalted deterministic hashes** are deterministic maps, so they fall **inside** ⊑. On small evidence spaces they are effectively injective: a dictionary attack recovers E. That is a privacy exposure.
- **Randomized or salted commitments** lie outside the deterministic ⊑ framework. They allow later verification **only if** the evidence is re-supplied, and only under the commitment's binding assumption. Perfectly hiding commitments carry no information-theoretic content.
- The commitment itself must be recorded faithfully, analogous to OF.

## 12.3 Retention options (neutral; STAKE)

| Option | Retain | Enables | Costs and risks |
|---|---|---|---|
| A | outcomes (rule version: forward-looking, outside the model) | recoverability (Thm 7.3) | under an unconstrained fault model, no independent verification (CE 8.11); constrained fault models can let outputs verify themselves (CE 8.9(a)). New decisions may be unanswerable (Prop. 7.9) |
| B | A + a minimal verification-evidence set per decision type | independent verification (Cor. 8.7 / Thm 8.6) | by analogy with CE 7.7, minimal evidence sets may not be unique; privacy exposure |
| C | A + attestations | verification under trust (Prop. 8.12) | not independent (Ch. 10, CE 10.5) |
| D | B + execution data | reproducibility (informal; not defined in this model) | the most operational data |
| E | the full raw input history | future decisions; retroactivity | least minimal; highest privacy exposure |

**Common to all options (ARCH, from Ch. 11):** append-only records; corrections as superseding entries; "current" as a query.

**Stakeholder decisions this chapter feeds:**
- which decisions are critical (D9), and which option applies to each;
- who may attest;
- retroactivity;
- deletion schedules, together with the retroactivity decision;
- correction horizons.

## 12.4 Limits

- **Deterministic records only.** Differential privacy and randomized mechanisms are OPEN (Ch. 14).
- **The legal adequacy** of deletion, tombstones and hash retention is outside mathematics.

## 12.5 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Thm 12.2 | an automated privacy check on the outcome log, for upward-closed rules |
| Thm 12.3(c) | privacy review must cover correction history |
| Thm 12.5 / Prop. 12.6 | **if** post-deletion verification is required: before deletion, retain a minimal verifiable set or a trusted attestation; document the effect on retroactivity |
| §12.3 | present options A–E to stakeholders; record the choice per decision type |
