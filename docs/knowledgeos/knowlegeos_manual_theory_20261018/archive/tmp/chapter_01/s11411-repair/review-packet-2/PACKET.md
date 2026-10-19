# Independent review packet 2: candidate M2 for §1.14.1

**You did not author any of this. Your job is to falsify, not to confirm.**
- Work read-only and create, change or delete no file.
- Do not treat the author's tests, proofs, statuses ("resolved in candidate") or recommendations as authoritative. Reconstruct.
- The author's recommendations on policy are **not** for you to evaluate. Decide no policy.

## Material

| File | What it is |
|---|---|
| `CH-01-baseline-v1.md` | Chapter 1 baseline v1 (sha256 `4d65261d…391367`). §1.14.1 is the original model; §1.13 holds the authority concepts |
| `REVIEW-RECORD.md` | the first independent review's findings F-1…F-21 on the earlier candidate M1, with the author's dispositions |
| `PHASE-A-FINDINGS-TRACEABILITY.md` | the author's findings baseline (v1 defects, additional problems, F-1…F-21) |
| `M2-MODEL.md` | the candidate model M2 |
| `M2-PROOFS-TESTS-COMPARISONS.md` | claimed theorems with proofs (**claims to falsify**); v1 → M2 behaviour table; record-view comparison |
| `M2-TRACEABILITY-AND-DECISIONS.md` | the author's claimed resolution status of every finding; mapping of policy questions onto reserved decisions 1–6 and alternatives C1–C16 |
| `M2-EVIDENCE-REGISTER.md` | provenance of each M2 element; investigation of two authority definitions |
| `author-encodings-SUSPECT/` | the author's Python encoding, tests and probe. **The author's previous encodings hid eight defects.** Use them only as objects of suspicion |

**Authorized sources** (read only these line ranges; nothing else under `docs/knowledgeos/`; never `.env*`; never anything under `docs/knowledgeos/chronological-read/`). Directory: `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledge_tranfer/`:
- `20260816_1332_authority_model_business_semantics.md` (AMS): L38–51, L57–135, L183–226, L278–298;
- `20260816_1023_invariant_aggregate_matrix.md` (IM): L125–150, L205–227;
- `20260816_1029_invariant_allocation.md` (IA): L85–105.

## What to do

1. **All 21 findings and the six v1 defects.** For each, state whether M2's claimed resolution actually resolves it. Give a derivation or a counterexample; do not rely on a test.
2. **Changes of meaning.** Find every v1 case whose well-defined result M2 changes. Check the author's table (comparison C) for omissions. For each change, say whether it is a mathematical correction or a policy change hidden in a formula, a default or a convenience branch.
3. **Formal claims.** Attack T1, T2, T2′, T3, T4, P2a, P2b, P4, the question-instant derivation and the annulment-chain claim. Look for:
   - hidden premises, wrong quantifiers and circularity;
   - non-monotone terms disguised as constants;
   - a key set that is not finite;
   - misuse of the fixed-point theorem;
   - axioms (Ax1, Ax2) doing unstated work.
4. **Counterexamples and boundaries.** Try to break:
   - issuance, delegation (including cascade chains and cyclic issuance), expiry instants and the question instant;
   - late-recorded and backdated entries under each record view;
   - the greedy υ rule;
   - quota consumption under each consume_view;
   - act-annulment cycles;
   - the headline "progress" rule.
5. **Decision mapping.** Is every policy question (including reviewer-1's Q1–Q14) mapped onto RD 1–6 or C1–C16 without loss, duplication or a second numbering? Is anything presented as technical actually a policy choice?
6. **The three previously identified gaps:** partial scope overlap ω (declared outside M2), conflicting decisions C6, and quota grants.
7. **Assumptions and source fidelity.** Check every [SRC-V] line the author cites in the AMS / IM / IA ranges above. Flag [ASM]s that carry weight.
8. **Separate issue types:** mathematical defect / meaning change / fidelity / terminology / policy question.

## Report format

- a verdict per item 1–8 (SOUND · SOUND WITH MINOR ISSUES · DEFECTIVE);
- numbered findings G-1, G-2, …: location · severity · type · derivation or counterexample · suggested action;
- an explicit list of any **material defect** that would make M2 unready as a sound candidate;
- limitations. You are a same-model-family session, not an external expert; say what you did not check.
