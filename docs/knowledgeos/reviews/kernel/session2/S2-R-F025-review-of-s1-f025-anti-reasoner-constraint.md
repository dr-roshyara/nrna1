# Session-2 Review — S1-F025

## 1 · Source artifact
`session1/S1-F025-anti-reasoner-constraint-the-kernel-must-not-presuppose-the-judgement-it-governs.md`

## 2 · What Session 1 claims
A constraint derived from Davidson: *"**The Kernel must not validate knowledge by secretly presupposing the semantic judgement it is supposed to govern**"*, with an operational test — *"**Can every Kernel decision be justified without requiring the Kernel to reconstruct the semantic interpretation that produced the candidate?**"* Session 1 rates it *"the strongest single candidate finding in the corpus"* and marks implementation relevance **STRONG IMPLEMENTATION EVIDENCE** for a constraint. Plus five non-collapses and a *retrieval ≠ authority* claim.

## 3 · Evidence class · Question type · Altitude
**CLAIM/CONSTRAINT** on the decision procedure · **TEST** · **DISTINCTION** ×5. Question types: **BOUNDARY** (what a decision may rely on) and **METHODOLOGY**. Altitude: a **policy on the admission procedure**, explicitly not a member.

## 4 · Level 1 — extraction fidelity
**UNVERIFIED.** ✅ Session 1 correctly treats the two Davidson documents (`005813`, `005850`) as *"**one finding with two sources**, not two arrivals"* — the intra-lineage discipline applied without prompting.

## 5 · Level 2 — analytical validity

### `.1` The constraint is different in kind, and Session 1 is right about why — **CONFIRMED**
*"S1-F009…F019 all answer **what the Kernel contains**; this answers **what any Kernel decision procedure may rely on**."*

✅ And it is the corpus's only finding **independent of which member list wins**. That is a real structural property: every other candidate presupposes a boundary; this one constrains any boundary.

### `.2` It supplies the missing *argument* for a rule the corpus had only asserted — **CONFIRMED**
`S1-F011`, `S1-F012` and others insist the Kernel *"does not reason"* / performs *"status assignment, not content transformation"*. Those are assertions. Davidson supplies the reason: **an admission procedure that reconstructs interpretation is circular — it uses the judgement it is meant to authorise.**

✅ This is the single best instance in the corpus of philosophy doing what `S1-F022`/`S1-F023` say it should: not supplying content, but supplying a *reason* for a position already held.

### `.3` Law asserts the prohibition; law does **not** state the test — the distinction Session 1 draws correctly
⟦L⟧ v1.1 §16: *"The kernel does not reason… it cannot generate a conclusion."* Session 1: *"**CONSISTENT — and this supplies the missing justification for that rule.** The corpus-side contribution is the **test**, which v1.1 does not state in decidable form."*

✅ Correct, and it is the hinge of the implementation assessment (§6): a **prohibition** and a **decidable test for compliance with it** are different artifacts, and only the first is in law.

### `.4` The bearing on `W:F-CM-1b` is an argument, not a ruling — **CONFIRMED**
*"if the constraint holds, selection **cannot** be inside without circularity. **That is an argument, not a ruling.**"* ✅ Correctly labelled and correctly not routed. I do the same.

### `.5` What is genuinely new versus already carried
Of the five non-collapses, two are new to Session 1 and only one is new to law:

| Non-collapse | Status |
|---|---|
| *false ≠ meaningless* | ⟦L⟧ **already carried** — `FALSE` is one of seven states, *"first-class negative and failure states"* (l. 213). An admitted-but-false claim is representable |
| *a claim is not an answer merely because produced in response to a question* | **New to the corpus**, and likely covered in effect by ⟨C-2⟩ — a mechanism's output is a *candidate outside the boundary* until admitted. The provenance-of-response framing is new; the protection is not |
| *entity ≠ asserted fact*; *referents vs assertions* | Narrows `S1-F018`'s vocabulary without resolving it — ✅ Session 1 says exactly this, and per `S2-R-F018` the question was already differently answered |
| *retrieval must remain candidate-generation, not epistemic authority* | ⟦L⟧ **already carried** — ⟨R-1⟩ + `INV-KOS-AGENCY-001` + *"no mechanism may ever produce this member"* |
| *knowledge ≠ acknowledgment* (Cavell) | Genuinely without counterpart. ✅ Session 1 draws no disposition and neither do I |

## 6 · Level 3 — implementation relevance · **what would we have to build?**

This is the artifact where the question bites hardest, because Session 1 marks it **STRONG IMPLEMENTATION EVIDENCE**. Taking it seriously, item by item:

**(a) The prohibition itself.** Build nothing — §16 states it. **DO NOT IMPLEMENT (already protected).**

**(b) A record of what each admission decision relied on**, so circularity is checkable after the fact. This is the only reading on which the constraint implies a *capability*.
⟦L⟧ `JustificationPath` (l. 210) — *"the reasoning **path**: premises · rules · assumptions · inference rule · conclusion — **the reason the state is justified, never a black box.** The *record* of reasoning; the *process* is external."*
That is exactly the required record, and the *record/process* split is precisely the anti-reasoner constraint in member form. **DO NOT IMPLEMENT (already protected).**

**(c) The decidability test.** Build nothing at runtime — it is a **design-time fitness question** applied to a candidate admission procedure: *can this decision be justified without reconstructing the interpretation that produced the candidate?*
**PRESERVE AS KNOWLEDGE ONLY**, strong form. It joins the instrument set as the **eighth** member, and it is the only one that tests a *procedure* rather than a *definition* or a *claim*.

**(d) Whether the test is operable at all.** Session 1's own open question: *"can the decidability test actually be applied to a concrete admission procedure?"* It never has been — like `S1-F023`'s Tarka instrument, it is **specified and unused**.
**NEEDS FURTHER EVIDENCE.** What would change it: one application to a concrete procedure. That is the cheapest high-value action the corpus offers, and it requires no new architecture.

⚠ **So Session 1's *"STRONG IMPLEMENTATION EVIDENCE"* does not survive as stated.** The constraint is strong *evidence*; it is not evidence *for an implementation*. Its two implementable readings are both already law (`§16`, `JustificationPath`), and what remains is an instrument. **The finding is more valuable than an implementation requirement would be — it explains why several existing law provisions are right — but it requires building nothing.**

## 7 · Conclusion vs justification
Constraint is the strongest candidate finding: **conclusion survives** — it is the only member-list-independent result in the corpus. *"Strong implementation evidence"*: **fails** (§6) — both implementable readings are already protected. *Supplies the missing justification for §16*: **both survive**, and this is the finding's real contribution.

## 8 · Standing hypothesis (§17) — per `X-004`
**CONSISTENT**, and unusually informative: a boundary forbidden from reconstructing interpretation can *preserve* and *record*, but cannot *derive*. That pulls against `S1-F014`'s derived-states hypothesis and toward the preservation reading — but see `S2-R-F024.8`, where Williamson pulls the same way for a different reason, and `S2-R-F016.12`, which pulls the other way. **Not adopted.**

## 9 · Open questions · Confidence · Status
Can the test be applied to a concrete procedure (never attempted) · does the constraint forbid interpretation-selection inside the boundary (`W:F-CM-1b` — argument only, **not adjudicated**) · does *acknowledgment* have any place.
**Confidence:** high on `.1`–`.4` and on §6. Level 1 UNVERIFIED. **Status: OPEN.**

## 10 · Final assessment
The corpus's best finding, and it requires building nothing. Its value is **explanatory**: it supplies the argument for a rule law already states and the corpus had only asserted, and it is the one result that holds whichever member list wins. Session 1's `STRONG IMPLEMENTATION EVIDENCE` marking is the single verdict I most want to correct — not because the finding is weak, but because its strength is of a kind that produces instruments rather than software. **Eighth entry in the strong-form PRESERVE set, and the first that tests a procedure rather than a definition.**
