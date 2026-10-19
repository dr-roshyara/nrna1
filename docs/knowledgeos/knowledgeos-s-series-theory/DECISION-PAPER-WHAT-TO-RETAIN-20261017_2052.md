# Decision paper: what information a governance platform should retain (neutral options)

| Field | Value |
|---|---|
| Prepared by | Session B, 2026-10-17 20:52 (clock) |
| Status | **Options for the Principal and the stakeholders. Nothing here is a requirement or a decision.** The basis is synthetic mathematics, internally reviewed (same model family), not yet externally reviewed, and **not yet checked against the real KnowledgeOS**. |
| Basis | O-10a (minimal record), O-10f (verifiability), O-10i (revisions), O-10g (storage), and the D9/D10 principles |

## 1. The question in plain words

For each important decision, how much should the platform keep:
- just **what was decided**;
- enough to **check that it was decided correctly**;
- enough to **re-run it exactly**;
- or **everything that happened**?

## 2. What the research has established (under its assumptions)

| Finding | Plain meaning |
|---|---|
| The smallest record from which every decision can be **recovered** is the log of decision outcomes (O-10a) | "Store only what was decided" is the data-minimal option, but only for decisions that are fixed in advance and never revised |
| An outcome log cannot show whether a decision was **right** (O-10f, V1) | Checking a decision needs trusted evidence plus the published rule, or a trusted attester |
| Evidence that is sufficient for checking may come in several equally small versions (O-10f, V3) | Someone must **choose** which evidence to keep. The mathematics does not pick a unique answer |
| Corrections need a record of both the original and the current decision (O-10i) | Overwriting a decision loses the history. Keeping only "current" decisions loses the corrected past |
| Concurrent corrections need a declared order, or an order-independent merge rule (O-10i, B5) | "Last one wins" requires recording the order |
| Less information does **not** always mean less storage in streaming logs (O-10g) | The privacy and storage benefits must be assessed separately |
| A record minimized for today's decisions may not answer a decision added later (O-10a, P6) | Minimization trades against future needs |

## 3. Options

| Option | Retain | Gains | Costs and risks | Fits when |
|---|---|---|---|---|
| **A. Outcomes only** | decision outcomes plus rule version | the most data-minimal; simplest privacy case | cannot independently verify or contest; cannot answer new decision types; corrections need extra entries anyway | low-stakes decisions, fixed and never revised |
| **B. Outcomes + verification evidence** | A, plus a chosen minimal set of attested evidence per decision type | independent checking; contestability (once defined) | evidence must be chosen; integrity of the evidence store; more personal data | **critical** decisions (D9) that must be auditable |
| **C. Outcomes + attestation** | A, plus a trusted component's signed statement of the correct decision | small, and checkable against the attester | not independent: trust moves to the attester | where a trusted authority exists and is acceptable |
| **D. Reproduction package** | B, plus execution data (versions, seeds, external responses) | exact re-execution for incident analysis | the most operational data; retention cost | safety-critical or disputed processes |
| **E. Full raw event history** | every input event | the most future-proof; supports new decisions later | the least data-minimal; highest privacy exposure; may conflict with minimization law and policy | where future, unknown decisions must be supported and law permits it |

**Common to all options** (from O-10i):
- records are append-only;
- corrections are new entries that reference what they supersede;
- "current decision" is computed, never overwritten.

## 4. Decisions for the Principal and stakeholders (none can be settled by mathematics)

1. **Which decisions are critical (D9)?** For each, which of A–E applies?
2. **Who is trusted to attest**, if option C is used?
3. **Which evidence set to keep** where several qualify (V3)? Weigh privacy, cost and availability.
4. **Retroactivity:** do rule changes re-evaluate past decisions?
5. **Retention periods and privacy rules** per option.
6. **Future-proofing:** how much to keep for decisions not yet defined (P6)?

## 5. A suggested starting point (a proposal, not a recommendation to adopt)

- Use **B** for decisions designated critical, and **A** for the rest.
- Append-only correction semantics everywhere.
- Revisit **E** only where a stakeholder can name a concrete future need.

**Caveat:** this rests on a synthetic model. It should be confirmed against the real KnowledgeOS sources once the safety gate passes.
