# Principal brief: Stage 1 clean-record decide-or-escalate core (2026-10-18)

**Status:**
- **specification delivered** (`STAGE1-SPEC.md`);
- **not implemented**;
- **AWAITING PRINCIPAL AUTHORIZATION** before any implementation.

C1 stays open, as instructed.

## The three answers

### 1. Can Stage 1 be proved safe under clearly stated conditions?

**Yes, conditionally. The proof is not complete yet.**

- **Statement:** if the records are truthful and complete up to the time in question, every autonomous "decide" is correct under every open policy choice.
- **What is established now:**
  - **No silent policy defaulting** is fully proved: deciding a policy can only add decidable cases.
  - **Safety** is a proof sketch. It needs one lemma (every act that could matter is checked), plus the repaired validity rules, which are specified but not yet reviewed.
- **The honest limit:** "complete records" cannot be checked from the records themselves. No record-based system can avoid this. It is managed **operationally**: a recording deadline, re-examination, or both (`RECORDING-DEADLINE-BRIEF.md`).
- Every known false-positive case from review 2 is designed to escalate; the expectations are hand-derived and still to be tested.

### 2. Can those conditions be met by KnowledgeOS's current records?

**No.**

- A census of the live store on 2026-10-18 covered 22 records, 144 grants and 274 transitions.
- **No grant records who received it, its period, when it was given or when it was recorded.** Every scope is free prose.
- **Only 2 of 274 transitions carry a date.**
- Stage 1 would therefore **escalate every case**: safe and well explained, but with **no autonomous decisions** today.

*Side finding:* the store now shows one REVOKED and one CONSUMED grant, so revocation is no longer unobserved.

### 3. What must be fixed or decided before implementation can deliver business value?

| Need | Kind | Who |
|---|---|---|
| **R-Q1:** a record time per entry | data / architecture | **ARB act**: the authority store is gated by R-37 and ADR-C6 |
| **R-Q2:** an act time per act, plus a tie order | data / architecture | ARB act |
| **R-Q3:** a structured grantee and issuer | data / architecture | ARB act |
| **R-Q4:** an explicit grant period | data / architecture | ARB act |
| **R-Q5:** scope as permission atoms, or a governed mapping from prose to atoms | data / architecture | ARB act |
| Recording deadline and/or re-examination, and the **remedy when a decision is reversed** | governance | Principal / ARB (T1-20 + a candidate) |
| C1 | governance (kept open by you) | Principal. Note: **with R-Q1 in place, C1 no longer blocks decisions**; without it, C1-a blocks them |
| Th-S1 Lemma R, Th-S3, Th-S4 proofs; review of the V-layer repairs | research | me, in the implementation round |

## Is Stage 1 ready for a separately authorized implementation round?

**Technically yes, as a research implementation. In business terms it is premature without R-Q1–R-Q5.**

**Recommendation (research opinion):**
1. **Authorize the Stage 1 implementation round as a research deliverable** (exit criteria below). It would prove the safety and explanation behaviour and produce the escalation packages, so the business can see exactly *why* nothing is decidable today.
2. **In parallel, put R-Q1–R-Q5 to the ARB as an architecture question.** These are what turn Stage 1 into autonomous decisions.

The alternative is to wait for the ARB's data decision before implementing. That is lower cost now, but it delays evidence for the safety claims.

## Exit criteria for the implementation round

1. Every review-2 false-positive scenario escalates with the expected reason codes.
2. Th-S1 is proved, including Lemma R, under the stated premises. Th-S2 is proved (done).
3. **A separately built oracle from `STAGE1-SPEC.md` only** agrees over a disclosed test space. Disagreements are resolved against the specification.
4. H-1–H-4 and re-examination are tested over event logs.
5. One fresh independent review finds no blocker or major defect.
6. All assumptions, data prerequisites and governance items are listed.

**If the round fails: stop, preserve the evidence, and report the cause and the choices.** No automatic repair round.

## Open item

**The independent oracle for ALM-r2 is still running.** Its output did not exist when this brief was written. I will assess it on completion (`STAGE1-TRACEABILITY-AND-EVIDENCE.md` §C). Its relevance to Stage 1 is partial: the validity functions only.

## Decisions requiring your authorization

1. **Authorize (or not) the Stage 1 implementation round**, under the exit criteria above.
2. **Refer data prerequisites R-Q1–R-Q5 to the ARB** as an architecture question, or decide that Stage 1 stays escalate-only for now.
3. *(Later, for ARB/Principal; not needed for the research round)* the recording-deadline option and the reversal remedy (`RECORDING-DEADLINE-BRIEF.md`).
