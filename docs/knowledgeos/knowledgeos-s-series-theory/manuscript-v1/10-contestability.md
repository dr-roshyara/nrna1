# Chapter 10. Contestability: auditors and affected parties

*Status: INTERNALLY REVIEWED, two rounds (same model family; round 1 required a formal world model and refuted a traceability row; round 2 confirmed the rewrite and refuted the "registry is necessary" claim, now corrected). Not externally reviewed.*

**Research question (RQ7).** An auditor being able to check a decision does not obviously mean that an **affected party** can **challenge** it. What does a party need, and how does that compare with what an auditor needs?

**Plain meaning.**
- A party may **see less** than an auditor, **trust less** (not trusting a system attestation the auditor accepts), or **know more** (its own private data).
- So a record that lets an auditor check a decision **may not** let the affected party challenge it.
- A party may **know** a decision is wrong where an auditor cannot check it. To **demonstrate** it, the party needs trusted evidence it can submit.
- One audit record does not automatically serve every party.
- Challenging the **procedure** (was the right rule version in force? did the decider have authority?) needs further records.
- **Remedies are outside mathematics.**

## 10.1 Model

**DEF 10.1 (worlds with records).** A **world** is w = (x, o, r):
- x: the attested inputs;
- o: the actual output;
- r: the **recorded content**, a tuple of record components r_k, for example retained inputs, the output log, or an attestation of d.

Recorded components may be **false** in some worlds; that is what distrust means. W is the admissible set (the fault model, now also covering recording faults).

**DEF 10.2 (party).** A party p has:
- an **access set** A_p: which record components it sees;
- a **trust set** T_p: components it accepts as true. Trusting k means restricting to worlds where r_k equals the true quantity it records, e.g. r_d = F(x);
- **private knowledge** κ_p(w): a function of the world, e.g. p's own input;
- a **fault model** W_p ⊆ W.

**Derived objects.**
- p's **possible worlds:** Ω_p := {w ∈ W_p : every trusted component is truthful in w}.
- p's **view:** v_p(w) := (r|A_p, κ_p(w)).
- **Assumption:** trust constraints are conjunctive. Trusting more components only removes worlds.

**DEF 10.3 (levels of contestation)** for a decision concerning p. p can contest at level:
- **(i) reconstruction:** v_p determines o on Ω_p;
- **(ii) correctness:** v_p determines c = [o = F(x)] on Ω_p;
- **(iii) procedural basis:** v_p determines whether the cited rule version was in force at the decision time, and whether the decider held authority. *This needs worlds that carry rule versions, effective dates and authority records. That model is given in Ch. 11; level (iii) results here are stated relative to it;*
- **(iv) remedy:** institutional, **outside mathematics**. Only necessary record conditions follow.

*Epistemic vs demonstrative.*
- **Epistemic:** p can know the fact (determination by v_p).
- **Demonstrative:** p can convince an adjudicator J, i.e. determination by J's view of p's submissions, under J's trust.
- Adversarial submission is not modelled.

## 10.2 When the party's ability implies the auditor's

**Theorem 10.4. THM.**
- *Hypothesis (dominance):* W_a ⊆ W_p, T_p ⊆ T_a, A_p ⊆ A_a, and κ_p is a function of the auditor's view.
- *Claim:* then for decisions concerning p, if p can contest at level (ii) (epistemically), the auditor can verify, i.e. the auditor's view determines c on Ω_a (level ii for the auditor).

*Proof.*
- **Fewer worlds for the auditor:** W_a ⊆ W_p, and the auditor imposes more trust constraints (conjunctive), so Ω_a ⊆ Ω_p.
- **A finer view for the auditor:** v_p is a function of v_a.
- **Transfer:** if v_p determines c on Ω_p, then v_a determines c on Ω_a ⊆ Ω_p. ∎

**Direction.** Under dominance, a record sufficient for the **party** is sufficient for the **auditor**. The converse fails (CE 10.5).

## 10.3 Separations

**CE 10.5 (verifiability does not imply contestability; it fails even under dominance). CE.**
- *Setting:* F = id on {0,1}, worlds w = (x, o, a) with a a recorded attestation of d, and unconstrained W.
- *Auditor:* trusts a, i.e. Ω_a = {a = x}, and sees (o, a). It can verify.
- *Party:* sees (o, a) but does not trust a, so Ω_p contains worlds with a ≠ x. It cannot determine c.
- *Dominance:* this instance satisfies the dominance hypothesis, so it shows that Thm 10.4's converse fails.

**CE 10.6 (hidden field). CE.**
- *Setting:* x = (x₁, x₂), F = x₁ ⊕ x₂, W unconstrained, records truthful. The auditor's record is (o, x₁, x₂). The party has A_p = {o, x₁} and trivial κ_p.
- *Witness pair:* (x₁, x₂, o) = (0, 0, 0) versus (0, 1, 0). The party sees the same thing, but c differs.
- *Result:* the auditor can verify, but the party cannot contest at level (ii).
- *Dominance:* this is also a dominance instance.

**CE 10.7 (private knowledge: epistemic incomparability). CE.**
- *Setting:* the record retains only o, with F = id on {0,1} and unconstrained W. The party knows its own input: κ_p(w) = x.
- *Party:* its view (o, x) determines c, so it can contest **epistemically**.
- *Auditor:* its view o does not determine c (CE 8.11).
- *Conclusion:* with CE 10.5, **epistemic** contestability and verifiability are **incomparable**.
- *Demonstrative reading, OPEN:*
  - an unattested submission of x leaves the adjudicator undetermined;
  - once x is attested by a third party, the adjudicator's view is a verifiable augmented record.

  So demonstrative contestability may reduce to verifiability of an augmented record. This is not settled here.

**Proposition 10.8 (non-implications between levels). CE.**
- **(i) ⇏ (ii):** the output log gives reconstruction, not correctness (CE 8.11).
- **(ii) ⇏ (i):** an attested correctness bit, without access to o.
- **(ii) ⇏ (iii) and (iii) ⇏ (ii):** these hold **relative to the version model of Ch. 11 (DEF 11.6a)**, provided that:
  - the true effective dates and authority **vary within Ω_p**, and the party has no private knowledge of them;
  - F_v is not constant (needed for (iii) ⇏ (ii)).

  Then:
  - a record without a registry can give (ii) but not (iii);
  - a record with a trusted registry and authority log but without x gives (iii) but not (ii). Unconstrained outputs, for instance, suffice for this.
- **Not addressed:** the relations between (i) and (iii).

**Record conditions for level (iii) (corrected; ANA within that model).**
- **Necessary:** the party's view must determine both procedural facts (in force; authority) on Ω_p.
- **Sufficient forms:** a **trusted** registry plus authority log is one. A **trusted attestation** of the procedural facts is another. *The earlier claim that a registry is necessary was refuted in review.*

## 10.4 Limits and open problems

- **OPEN:** demonstrative contestability vs verifiability (CE 10.7).
- **OPEN:** genuinely restricted verification abilities. Determination here is information-theoretic; computational limits are not modelled.
- **OPEN:** adversarial parties.
- **OPEN:** probabilistic evidence.
- **Outside mathematics:** legal adequacy of the adjudicator model, authority records and remedies.

## 10.5 Traceability (ARCH; policy is STAKE)

| Result | Engineering consequence |
|---|---|
| Thm 10.4 | under dominance, a record sufficient for the affected party is also sufficient for the auditor. **Auditor sufficiency does not in general guarantee party contestability** (CE 10.5, 10.6): check per party class |
| CE 10.5 / 10.6 | define, per party class, what it may see and whose attestations it trusts (STAKE) |
| CE 10.7 | provide a challenge process in which parties can submit **attested** private evidence (needed for demonstration) |
| Prop. 10.8 | to support procedural challenges, make the procedural facts determinable: either a trusted effective-date registry plus authority log, or trusted procedural attestations (Ch. 11 model) |
| Regression tests | CE 10.5–10.7 as access/trust scenarios |
