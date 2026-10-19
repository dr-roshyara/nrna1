# Principal brief: the next research step for KnowledgeOS authority (2026-10-18)

**Status:** **AWAITING PRINCIPAL AUTHORIZATION.** ALM-r2 is paused and DEC is not started.

**Full analysis:** `DEC-CRITICAL-EVALUATION.md`.

## 1. What we are trying to achieve

KnowledgeOS decides **only within its authority**. When it cannot decide safely, AI investigates, explains the evidence and the uncertainty, recommends a solution, and prepares a proposal for the **ARB, who decide**. An AI recommendation never silently becomes a governance decision.

## 2. Verified versus only reported

| Status | Items |
|---|---|
| **Verified by the independent reviewer** | the "was it validly made?" core for the basic case (one reading of C1 (b), explicit periods): existence, uniqueness, locality. Records can legitimately change a past conclusion when late records arrive. Under the "never not-authorized" setting no false "not authorized" is produced |
| **Reproduced counterexamples (author)** | the old recognition layer can say "authorized" when fields, order or the authority of a cancellation are unknown (N1, N2, N3, R2-04, R2-14) |
| **Shown today by a bounded probe** (`probe_policy_quantification.out`) | requiring the answer to hold under every open policy choice **does not** remove those false positives: N1, N2 and N3 survive all 256 tested configurations |
| **Shown today by a bounded probe** (`probe_c1_without_record_times.out`) | without record times, even a perfectly clean case cannot be decided robustly while C1 is open |
| **Reported only, not yet verified** | everything in the paused ALM-r2 specification; the DEC design |

## 3. Is DEC a credible path?

**Yes, for business fit. Not because it is smaller:** the effort is similar.

**Corrected claims:**
- **"Decide only if robust across open policies"** protects ARB authority, but **does not by itself make decisions safe**. Safety needs correct handling of incomplete records.
- **"Not authorized on records"** is renamed **"no authority on record"**. It is not proof that an act was unauthorized.
- **"Missing evidence" vs "governance choice"** are **not exclusive**. A case can be both, and an open-world caveat always remains.
- **Earlier decisions can be undermined by later records.** KnowledgeOS must store the evidence basis, re-examine, and raise an event. It must never back-date.

## 4. The strongest arguments against my recommendation

1. DEC does not reduce the hard technical part. It shares it with ALM-r2.
2. The explanation layer can move complexity rather than remove it.
3. **On today's records any sound model escalates almost everything.** The store lacks record times, grant periods and holder links. Value depends more on **record quality** and on the **C1 decision** than on model sophistication.
4. **Hence a smaller first step is better** (the revised recommendation).

## 5. Unresolved questions, classified

| Class | Items |
|---|---|
| **Research** (resolvable by me) | the soundness proof for incomplete records; the reason-label semantics; the flip bound under a recording deadline; the relevance analysis for open policies |
| **Specification ambiguity** | the domains for some unknown fields (scope, proviso, bars, claim periods); provenance-typed explanations |
| **Implementation** | everything already specified (Auth/Form split, C1-a semantics, C7, RD 1 per ending kind) |
| **Governance (yours or the ARB's)** | C1 · RD 1 · RD 6 · C7 · C10 · C6 · C12 · C14 · C15 · the recording deadline (T1-20) · **what to do with actions taken under a decision later undermined** (no register item: a candidate). **None needs deciding for the next round**, because the model quantifies over them. **C1 is the single decision that most increases what KnowledgeOS can decide on today's records** |

## 6. Recommended path

**Path A, staged.**

**Stage 1 (the bounded round): the "clean-record" decide-or-escalate core.** KnowledgeOS decides only when all of the following hold:
- the relevant records are complete: no unknown fields, no unordered ties, no ending act whose authority is not on record, and every grant period stated;
- the answer holds under every open policy;
- the case lies within the verified core.

Everything else is escalated with a typed reason, plus:
- the human-authority rules: a recommendation is never a decision; a reserved decision requires an ARB act;
- re-examination when new records arrive.

**Why:**
- it is safe by construction (one world, no guessing);
- it reuses only verified results;
- it delivers the business workflow now;
- every known false positive becomes an escalation.

**Stage 2 (only if Stage 1 escalates too much, separately authorized):** reasoning over incomplete records (the shared hard part).

**Also surfaced for the architecture, not decided:**
- **R-Q1:** record a record time per entry;
- **R-Q2:** record grant periods, holders and issuer links.

## 7. Exit criteria for Stage 1 (one bounded round)

1. Every review-2 false-positive scenario ends in **escalation**, with the correct typed reason.
2. A **proof** that Decide implies validity on clean records (under the stated completeness premise), and a **proof** of non-usurpation (no open policy is defaulted; deciding a policy can only add decidable cases).
3. Agreement with an **oracle built by a separate agent from the specification alone**, over a disclosed bounded space.
4. The human-authority rules and re-examination are tested over event logs.
5. **One fresh independent review finds no blocker or major defect.**
6. Every governance item is listed for you, and none is decided.

## 8. If that review fails

**Stop.** No repair cycle without your decision. Keep the verified core and the escalate-by-default rule: KnowledgeOS then decides nothing autonomously in this area and escalates everything, with reasons. This is safe, though it leaves AI and the ARB with more work. We report what failed and why.

## Decision required

1. **Authorize a change of scope:** replace the paused full ALM-r2 round with **Stage 1, the clean-record decide-or-escalate core**, under the exit criteria in §7. Or keep **Path B** (full ALM-r2).
2. *(Optional, not needed for Stage 1)* **Whether you want to take the C1 decision now**, since it most increases what KnowledgeOS can decide on today's records. Otherwise I carry it as an open policy.
