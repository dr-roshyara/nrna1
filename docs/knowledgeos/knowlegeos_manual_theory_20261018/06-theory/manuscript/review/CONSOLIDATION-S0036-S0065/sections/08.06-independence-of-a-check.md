# 8.6 Independence of a check: what it is made of, and what can show it

*Window C11, Trial T3 (2026-10-19). Sources, in index order: S0056, S0058, S0059 (all 2026-08-17). Added under Rulebook §12a after a logged search on "model separation": S0164 (2026-08-21, earliest relevant hit) and S0913 (2026-08-28, latest relevant hit). **Status: reviewed in C11/T3 (2026-10-19); settled in `review/C11-S0056-S0065/SETTLEMENT.md`.***

*Lineage:* S0056–S0060 belong to one work item (KOS-ARCH-BASELINE-001), all written on one day, and they refer to each other. S0059's verifier is the process that wrote S0055 ("this process performed VERIFICATION #1" [S0059 L17]), and it repeats S0056's model disclosure "as V#2 disclosed its own" [S0059 L21–22]. **S0056–S0060 count as one line of support.** S0164 is a working log of pasted assistant replies. Its L1100 passage comes from this same chain, so it is part of that line. Its L1529 (parsers) and L2073 (reviewer independence for another work item) are other cases in the same estate. S0913 is an abstract step in a series and names none of these cases. Whether it shares their origin (the same programme and its owner) is **not shown**. It is counted as a second line, with that caveat.

*Existing homes (book search, lexical and by meaning):*
- Ch. 8 §8.5 C2: independence of **method** beside independence of **role**;
- Ch. 1 §1.14.3: producer ≠ verifier;
- Ch. 4 §4.1 C5: recurrence must be independent, and a shared process is a common cause;
- Ch. 1 §1.16.8: acceptance needs the test that fits the claim, and absence must be visible;
- Ch. 8 §8.4 C-on-absence: an absence claim needs its search scope `[CON-0120]`;
- Ch. 1 §1.6: only an act confers standing (A1);
- Ch. 9 §9.4 C7: the trivial case of the assurance model.

This section adds two further factors of independence (model and information lineage), a proof about differential testing, and the one-sidedness of any test of independence run on the record.

## 8.6.1 In plain language

A second check is worth something only where it can fail differently from the first. Two reviewers in two sessions can still share the same model, the same method, the same reading of the same document, or the same working notes. Wherever they share something, they can share a blind spot. Comparing two checks shows only where they disagree. A defect that both miss **in the same way**, with the same wrong output, never shows up as a disagreement. And the record can show that two checks are **not** independent (a shared factor is found), but it cannot show that they **are**: that something was not found does not mean it is not there.

## 8.6.2 Definitions

- A **check** c is a procedure, run by an executor, that reports or misses each defect in a set D. Its **blind set** B(c) ⊆ D is the set of defects it misses. *(This is a modelling choice: each check either detects a defect or misses it, every time it runs. "Misses" means it gives the output it gives on a correct object, so two checks that miss d give the **same** output on d: a common-mode failure. Two checks that are both wrong with **different** wrong outputs disagree and are not "both missing" in this sense; corrected after review C11/T3.)*
- A **factor** of a check is anything its result depends on: the role and process that ran it, its **process lineage** (continuation, compaction, fork or resume of an earlier process), its **method**, the **model** that executed it, the **information** it read (information lineage), the **working context** it inherited, and who **wrote its scope** (its commission). *(Restated after review C11/T3: "lineage" had merged process lineage with information lineage, and scope authorship was missing.)*
- Two checks are **independent in a factor** if they do not share that factor.

## 8.6.3 Claims and derivations

**C1. Independence has several factors, and a new identifier is not one of them (Src; Int; Hyp for the general case).** `[CON-0125 · provisional; restated after review C11/T3]`
- **Model:** the verifier of S0056 discloses: "v1.1 was co-authored by the SAME MODEL as this report … Process separation is real; MODEL separation is not. A shared model can share a blind spot" [S0056 L25–27]. S0059, by contrast, records full model separation from the corrector [S0059 L21].
- **Lineage and working context:** a later rule, for another work item: "**A different session ID alone is insufficient.** The assurance boundary is **process identity + lineage + working-context provenance**" [S0164 L2073].
- **Information:** S0913 makes the same point for agents in general. Two agents that read the same vendor document "are not independent merely because" they are different agents [S0913 L238], and "Counting A and B as two independent confirmations would inflate confidence." [S0913 L274].
- **Int:** with §8.5 C2 (method) and §1.14.3 (role), the book now has seven candidate factors: role/process, process lineage, method, model, information lineage, working context and scope authorship. A session ID labels the process; changing it alone changes none of the others.
- **Scope authorship, from the corpus:** a verification can be "independent of the implementation — yes; independent of the commission — no" (S0164 L1789). This is legislator ≠ evaluator (Ch. 1 §1.14) applied to verification.
- **Model integrity check (CLAUDE.md "model integrity", an observation):** is "model" a new dimension, or another value of "method"? Two checks can follow the same written method on different models, or different methods on one model. So the two vary separately: model is a new factor, not another value of method. Whether all seven factors are needed, sufficient and orthogonal is **not shown**.
- **What the case does not show:** that a shared model *causes* a shared blind spot. S0164 reads the two defects that survived as "exactly the shape a shared blind spot produces — both formatting-adjacent, both survived a same-model correction pass" [S0164 L1100]. But they were **found** by a verifier of that same model (S0056, model disclosed at L25–26). In this case, then, a shared model did not prevent detection. The claim stays a possibility ("can share", in S0056's own words). *Hyp:* that a shared model raises the overlap of blind sets.

**C2. Differential testing reports disagreement and never shared blindness (Ded: a proof under the definitions).** `[CON-0126 · validated]` (for common-mode failures under the deterministic model)
- **The source:** "**both** implementations miss ?->, so no comparison of the two can ever surface it. **Differential testing detects disagreement, never shared blindness.**" [S0164 L1529]
- **Proof (under the definitions above, where both-miss means the same output).** Compare checks a and b, and report d whenever their results on d differ. They differ exactly when one detects d and the other misses it, that is, for d ∈ B(a) Δ B(b) (the symmetric difference). For d ∈ B(a) ∩ B(b) both miss it, so the results agree and nothing is reported. ∎
- **Trivial-case test:**
  - if B(a) = B(b), the comparison reports nothing, however large the shared blind set is. Silence from a comparison is therefore not evidence of correctness;
  - if B(a) = ∅ (a is an oracle), the comparison reports exactly B(b), which is what testing against an oracle does.

  The model gives both expected results.
- **Disagreement does not say who is right.** It shows where to look. S0164 names what closes the class both miss: "Only the contract — ruling ?-> explicitly — closes that class" [S0164 L1529]. That is a stated requirement, not a further check.
- **Scope of the generalisation:** the claim holds for any pair of checks under these definitions, because it is proved. It does not cover:
  - checks with random outcomes: run twice, such a check can miss a defect once and catch it once;
  - checks that fail differently: real differential testing **does** flag a defect both get wrong when their wrong outputs differ. The blind spot proved here is the **common-mode** failure. That is S0164's case: both parsers miss the same construct in the same way.
- **For verification:** a producer's self-check p and a verifier v together miss B(p) ∩ B(v). Shared factors (C1) are the likely reason for a large intersection. So the value of a second check is bounded by what it does **not** share with the first.

**C3. On the record, independence can be refuted but not established (Ded; Src).** `[CON-0127 · provisional]` (needs a truthful record)
- **The source:** an AI "can **detect possible conflicts**" (author = reviewer; author created the finding; reviewer created the decision referred to). But it should report "CONFLICT DETECTED", not "INDEPENDENCE = TRUE" [S0164 L384–401]. And: "If lineage cannot be established, the classification is NOT ATTESTED — never PASS." [S0164 L2076]
- **Proof sketch.** Let F be the set of factors two checks really share, and R the shared factors the record shows. **Premise: the record is truthful, R ⊆ F** (it may be incomplete, but it records no shared factor that is not there). A detector reports a conflict when R ≠ ∅. That is sound: R ⊆ F, so F ≠ ∅. When R = ∅ it cannot conclude F = ∅, because a factor the record does not hold is not in R. ∎
- **Trivial-case test:** if the record is complete (R = F), the test decides both ways. So the one-sidedness comes exactly from the open world, and the claim needs that premise, stated: **the record need not hold every shared factor.**
- **The second premise can fail too (added after review C11/T3).** Recorded factors are themselves declarations (INV-ATTR-2), and the corpus holds false ones: a grant linked to work it post-dates [S0058 L77], a commit read as a session [S0164 L1998–2005], a false "ALL PRECONDITIONS SATISFIED" from a truncated pipe [S0164 L2129]. So refutation from the record is only as reliable as the record's truthfulness.
- **Int (Ch. 8 §8.4, CON-0120):** "no shared factor" is an absence claim. It is only as good as the search behind it. This is why both verifications classify their own independence as "Independence = Declared." [S0056 L31; S0059 L31]: separation is "DECLARED, not attestable".
- **Instance: a verification with no lane.** For S0056 itself the Governance record finds "no lane, no `START` and no grant under which Verification #2 was performed … **its independence is not recorded anywhere.**" Its findings were acted on all the same: "This does not make its content wrong" [S0058 L49–51]. **Int:** the content was judged on its evidence; its independence was neither attested nor declared in the record.
**C4. Corroboration is counted in independent information, not in sources (Ded; Src).** `[CON-0128 · provisional]` (product rule and copy case valid; "between" INVALID)
- **The source:** two likelihood ratios, 19 and 15, multiply to 285 "If independent" [S0913 L1485–1488]. If both come from the same document, the combined ratio "must **not** automatically be" 285 [S0913 L1497]. The step's key result is that corroboration is measured in independent information, not in the number of agents (in meaning, S0913 L1509–1516).
- **Ded (standard probability):** the product rule LR(E₁, E₂) = LR(E₁) · LR(E₂) holds when E₁ and E₂ are conditionally independent given H and given ¬H. In the extreme case where E₂ is a copy of E₁, P(E₂ | E₁, H) = P(E₂ | E₁, ¬H) = 1, so E₂ adds nothing: LR(E₁, E₂) = LR(E₁).
- **Under dependence the joint ratio is not bounded by these two (corrected after review C11/T3).** Counterexample, recomputed in this session: on two binary observations, P(·|H) = (11: .5, 10: .1, 01: .1, 00: .3) and P(·|¬H) = (11: .01, 10: .39, 01: .39, 00: .21). Each observation alone has LR 1.5; the product is 2.25; the joint LR of both is 50. Another distribution gives 0.857. Dependence can strengthen as well as weaken.
- **Int:** the copy case is what two rules the book already uses guard against. Neither is derived from the probability; both are **conservative heuristics** (they never count a dependent source twice, and so never overcount, at the cost of sometimes undercounting):
  - Ch. 4 §4.1 C5: the R-6 count fails, because one process is a common cause;
  - this book's own lineage rule (Principal, 2026-10-19): sources of the same day and author, or that annotate each other, "count as one line of support". This section applies it in its lineage line.
- **Src, with a limit:** S0913 ends its tests with "**PASS**" (e.g. "three agents, one source": independent clusters = 1 [S0913 L1524–1534]). No computation is shown. These are statements of what the model should give, not executed tests (Ch. 3 §3.9: executable ≠ executed).

**C5. A mechanical check is the class least exposed to a shared executor (Src; Int; Hyp; PROVISIONAL after review C11/T3: an assumption of the source, not shown).**
- **The source:** S0059 bounds its missing model separation: "every check below is mechanical (diffs, byte comparisons, line references), the check class least exposed to a shared model blind spot — and the findings under check were produced by the OTHER model" [S0059 L27–30].
- **Int:** a mechanical check is fixed by its procedure and its inputs. The executing model enters only where it chooses what to check and reads the output. So of the five factors (C1), model sharing matters least for this kind of check. It still matters for the **choice** of checks.
- **Hyp (one case):** that a verification made only of mechanical checks may waive model separation, if it says so.
- **The limit, from S0164:** a checker can verify that a declared trace resolves, but "The checker cannot discover an architectural act that the author never declared." [S0164 L3139]. Mechanical checks look at the declared; completeness needs judgement (§8.4, absence).

## 8.6.4 Open questions

- Are the five factors of C1 independent of one another, and is the list complete?
- Can a record ever attest the absence of a shared factor (C3), for example by binding the model and the lineage at START? **The corpus's own competing answer:** refuse INDEPENDENT as an evidence dimension and use REPRODUCIBLE instead. "OBSERVED cannot distinguish 'someone recorded seeing it' from 'anyone can re-derive it'", and organizational independence is "unachievable in a single-operator estate" (S0164 L2420–2424, in meaning). A reproducible observation does not need an independent observer.
- When is a verification made only of mechanical checks enough without model separation (C5)?

## 8.6.5 What does not hold

- **"A defect that both miss never shows up"** without its premise (plain language, C2). Holds for common-mode failures (same wrong output) only; differing wrong outputs do disagree (review C11/T3, finding 2).
- **C3 without the truthfulness premise.** Refutation from the record needs R ⊆ F; the corpus has false recorded factors (finding 3).
- **"Between these two, the combined ratio depends on how dependent…"** (C4). INVALID: under dependence the joint LR can exceed the product or fall below either ratio (counterexample above; finding 4). The lineage rule is a heuristic, not "the general form".
- **Five factors** (C1). Restated as seven: "lineage" was a homonym, and scope authorship was missing (finding 5).

## References

| Source | S-ID | Lines | Read |
|---|---|---|---|
| `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-phase-a-v1.1-correction-verification.md` | S0056 | L25–27, L31 | in order |
| `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-verification-3-authorization.md` | S0058 | L49–51 | in order |
| `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-verification-3-n1-n2-report.md` | S0059 | L17, L21–22, L27–31 | in order |
| `docs/knowledgeos/brainstorming/20260821-120810-kos-governance-role-cost-optimization.md` | S0164 | L384–401, L1100, L1529, L2073, L2076, L3139 | topic-full (§12a) |
| `docs/knowledgeos/brainstorming/phase_measure_theory/20260828-100905_step-025u-multi-agent-knowledge-conflict-resolution-consensus-trust-and-distributed-epistemics.md` | S0913 | L238, L274, L1485–1497, L1509–1516, L1524–1534 | topic-full (§12a) |
| Ch. 1 §1.6, §1.14.3, §1.16.8; Ch. 3 §3.9; Ch. 4 §4.1; Ch. 8 §8.4, §8.5; Ch. 9 §9.4 | — | — | existing homes |
