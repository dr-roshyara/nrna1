# Chapter 8. Verification under fault and trust models

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 refuted the witness of Prop. 8.10(b), now replaced, and corrected Prop. 8.12; all corrections applied). Not externally reviewed. Late addition not re-reviewed: the new Prop. 8.10(b) witness.*

**Research question (RQ6, single step).** When can an auditor, using only what is retained and what it trusts, decide whether a recorded decision was **correct**?

**Plain meaning.**
- A log of outcomes shows **what** was decided, not **whether it was right**.
- To check correctness, the auditor needs **trusted evidence** that, together with the published rule, settles the question.
- Evidence that **determines the rule's result** always suffices, whatever the faults.
- **Less evidence can suffice**, but only if one relies on knowing how the system can fail. That knowledge is itself a trust assumption.
- Storing a trusted party's statement of the correct decision is the cheapest option. It moves trust to that party: the check is then not independent.

## 8.1 Model

**DEF 8.1 (single-step decision event).**
- A step consists of **attested inputs** x ∈ X and the system's **actual output** o ∈ O.
- The **correct** decision is d = F(x), for a **published rule** F (one rule version; Ch. 11 treats versions).
- The **correctness bit** is c := [o = F(x)].
- *Notation:* in this chapter d denotes the decision **value** F(x), whereas in Ch. 2 d is a decision map.

**DEF 8.2 (fault model).**
- A fault model is a set W ⊆ X × O of admissible pairs (x, o): the worlds the observer considers possible.
- **The fault model is observer-relative (ASM).** Different parties may assume different W.
- X_W := {x : (x, o) ∈ W for some o}.

**ASM OF (output-log fidelity).** The retained record of o is faithful to what was actually output. It does not say whether o was right.

**DEF 8.3 (trust and evidence).**
- The auditor trusts the attested inputs and F.
- An **evidence map** E : X → 𝓔 says what the record retains about x. In this chapter E depends on x only.
- The auditor does **not** trust o's correctness, nor any derived claim, unless an attester is trusted (§8.4).

**DEF 8.4 (verifiability).** The record (o, E(x)) is **verifiable under W** iff for all (x, o), (x′, o′) ∈ W with o = o′ and E(x) = E(x′), the correctness bits agree: [o = F(x)] = [o′ = F(x′)].

**DEF 8.5 (confusable pairs).** x, x′ ∈ X_W are **W-confusable** iff:
- F(x) ≠ F(x′); and
- there is an output o, admissible with both x and x′, with o ∈ {F(x), F(x′)}.

## 8.2 The characterization

**Theorem 8.6 (verifiability ⇔ no confusable pair is merged). THM.** The record (o, E) is verifiable under W iff E identifies no W-confusable pair.

*Proof.*
- **(⇐)** Suppose verifiability fails. Then there are (x, o), (x′, o) ∈ W with E(x) = E(x′) and [o = F(x)] ≠ [o = F(x′)].
  - The bits can differ only if F(x) ≠ F(x′): equal decisions give equal bits.
  - Also o must equal one of F(x), F(x′): if o equals neither, both bits are 0.
  - So (x, x′) is W-confusable, and E identifies it.
- **(⇒)** Suppose E identifies a W-confusable pair (x, x′), with witness o ∈ {F(x), F(x′)} admissible with both.
  - Then (x, o) and (x′, o) have equal views (o, E).
  - Exactly one of the bits is 1, because F(x) ≠ F(x′) and o equals one of them.
  - So verifiability fails. ∎

**Corollary 8.7 (determining evidence is always enough). THM.** If E determines F on X_W (E(x) = E(x′) ⇒ F(x) = F(x′)), then (o, E) is verifiable under **every** W.

*Proof.* Under determining evidence, E identifies only pairs with F(x) = F(x′). Such pairs are never confusable. ∎

**Theorem 8.8 (when "verifiable" and "determining" coincide). THM.** The statement "for all evidence maps E: (o, E) is verifiable ⇔ E determines F on X_W" holds iff every pair x, x′ ∈ X_W with F(x) ≠ F(x′) is W-confusable. Call this condition **(Conf)**.

*Proof.*
- **(⇐)** One direction is Corollary 8.7. For the other: if E does not determine F, it identifies some F-distinct pair. By (Conf) that pair is confusable, so verification fails (Thm 8.6).
- **(⇒)** Suppose (Conf) fails for some pair (x, x′). Let E identify **only** x and x′, and be injective elsewhere. Then E does not determine F. But the only identified pair is not confusable, so (o, E) is verifiable (Thm 8.6). ∎

*Sufficient condition for (Conf):* every x ∈ X_W may output every value in F(X_W). This holds under an **unconstrained** fault model, W = X × O, provided F(X) ⊆ O.

**CE 8.9 (constrained faults: less evidence suffices).**
- *Setting:* W = {(x, F(x)), (x, F(x) + 2)}: outputs are either correct or off by exactly 2.
- *Confusability:* x, x′ are confusable iff |F(x) − F(x′)| = 2.
- **(a)** With X = {0,1} and F = id, no two values of F are 2 apart. So **empty evidence is verifiable**, but it does not determine F: o ∈ {0,1} means "correct", o ∈ {2,3} means "faulty". The output itself reveals correctness.
- **(b)** With F(x) = 2x, the inputs 0 and 1 are confusable (o = 2 is correct for x = 1 and faulty for x = 0). Empty evidence is **not** verifiable.
- For a general E, verification fails as soon as E merges an F-distinct pair whose values differ by exactly 2.

## 8.3 Recoverability versus verifiability

**Definition.** The record (o, E) **determines d on W** iff (o, E(x)) = (o′, E(x′)) implies F(x) = F(x′), for (x, o), (x′, o′) ∈ W. Here the record is taken jointly, not E alone.

**Proposition 8.10. THM.**
- (a) Under OF, if the record determines d on W, it is verifiable: c = [o = d] is computed from o and d.
- (b) The converse fails: a record can be verifiable without determining d.

*Proof.* (a) is immediate. For (b):
- *CE:* X = {0,1}, F = id, W = {(0,0), (1,1), (0,9), (1,9)}: outputs are correct or a sentinel 9. Take empty evidence.
- *Verifiable:* o ∈ {0,1} means c = 1, and o = 9 means c = 0.
- *Not determining:* o = 9 is compatible with d ∈ {0,1}. ∎

*Note (correction to an earlier example).* CE 8.9(a) is **not** a witness for (b). There the output alone determines d (d = o or d = o − 2), and only E fails to determine F. The same faulty witness was used in the earlier report record (F2-4); the conclusion stands, but with this example.

**Plain meaning.** "We can tell whether it was right" can hold without "we can tell what the right answer was". In the example, a recognizable error signal (the sentinel) reveals that the output is wrong without revealing the correct value.

**Relation to Ch. 7 (interpretive bridge).** Ch. 7 has no faults: its outcome log records 𝔇(h). Reading Ch. 7's log as "the log of o" is an interpretation. Under faults, o may differ from d, so a log of outputs alone does not recover d and, in general, is not verifiable.

**CE 8.11.** F = identity on {0,1}, unconstrained W. The outputs (x = 0, o = 0) and (x = 1, o = 0) have the same outcome log but different correctness. Verification needs x.

*Remark (the gap is about trust, not information).* A log containing o **and** true values of d is verifiable information-theoretically. The obstacle is that the auditor cannot **trust** stored d-values without trusting whoever computed them (§8.4).

## 8.4 Attestation

**Proposition 8.12. THM.**
- *Setting:* suppose a component T, trusted by the auditor, attests a value a(x) recorded alongside o.
- **(a)** If T attests d itself, (o, d) is verifiable.
  - *Setting:* ⊑ is taken on the domain W, and (Conf) holds (Thm 8.8).
  - *Claim:* among **verifiable** records of the form (o, attested function a of x), (o, d) is ⊑-least.
  - *Reason:* by Thm 8.8 every verifiable a determines F on X_W, so F ⊑ a, and hence (o, d) ⊑ (o, a).
- **(b)** If attested entries may depend on o as well, then (o, c), with c the attested correctness bit, is verifiable and ⊑-below (o, d) on W (because c = [o = d]).
  - It is **strictly** below **iff** there are two faulty worlds with the same o and different d.
  - A necessary condition is |O ∪ F(X_W)| ≥ 3, since o, d and d′ must be pairwise distinct. It is not sufficient.
  - *CE (not strict):* X = {0,1,2}, F = id, W = {(x, x), (x, x+2)}. Here c = 0 forces d = o − 2.
  - *CE (strict):* the sentinel model of Prop. 8.10(b).

**Plain meaning.** Attestation is the most compact option, but verification then means "if the attester is honest". **Who may attest is a STAKE decision.**

## 8.5 Limits

- **Deterministic, x-only evidence.** Ch. 9 treats several steps. Evidence that reads outputs is treated in §8.4. Randomized records and commitments are OPEN (Ch. 14).
- **The fault model is an assumption.** If the true failure behaviour is wider than W, a record verifiable under W may not be verifiable in reality. **Exploiting a constrained W trades retained evidence for a fault-model assumption.**
- **Rule versions.** Verification is relative to a rule version. Records must say which version applied (Ch. 11).

## 8.6 Traceability (ARCH)

| Result | Engineering consequence | Regression test |
|---|---|---|
| Cor. 8.7 | for critical decisions, retain attested evidence that determines the rule's result, plus the rule version (safe default) | — |
| Thm 8.6 / CE 8.9 | smaller evidence sets require a documented fault model, a trust assumption (STAKE) | CE 8.9(a)/(b) |
| CE 8.11 | an outcome log alone is not an audit trail for correctness | CE 8.11 |
| Prop. 8.12 | attestation is cheaper but not independent; name the attesters (STAKE) | — |
