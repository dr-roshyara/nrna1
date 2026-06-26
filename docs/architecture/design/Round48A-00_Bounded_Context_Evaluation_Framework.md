# Round 48A — Bounded Context Evaluation Framework

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Under `Round47-OP`**
**Status:** 🧪 EVALUATION FRAMEWORK (instrument) — defines the falsification-based test for whether a candidate seam is an independent bounded context. **Round 49 *applies* this framework; this round *defines* it.**
**Date:** 2026-06-26 · **Built against:** Landscape v1.0

> **Falsification stance (the core methodological point).** A candidate is **not** "confirmed." Its **null hypothesis is: *this candidate is NOT an independent bounded context.*** The candidate must **earn its existence** by surviving explicit rejection/merge tests. Process: **Candidate → Challenge → Evidence → Decision** (science, not confirmation bias). Round 49 is renamed accordingly: **Bounded Context *Evaluation*** (not "Confirmation").
> **Two instruments, not one.** **ADQC** (`Round48-00`) measures **decision quality** ("is this a good design?"). **This framework** measures **boundary existence** ("is this a BC at all?"). Both are required; neither substitutes for the other.

## 1. The nine boundary tests (rejection protocol)

Each candidate is run against all nine. The **outcome arrow** is the disposition if the answer is the failing one.

| # | Test | Failing answer → outcome |
|---|------|--------------------------|
| **T1** | Does it **own decisions**? | No → **Reject** (not a domain context) |
| **T2** | Does it **own authoritative truth** (a system-of-record)? | No → **Probably not a BC** (read model / capability) |
| **T3** | Can it **evolve independently**? | No → **Merge** |
| **T4** | **Separate lifecycle**? | No → **Merge** |
| **T5** | **Separate transactional consistency** (own invariants in one txn)? | No → **Merge** |
| **T6** | **Separate ubiquitous language**? | No → **Merge** |
| **T7** | **Separate business capability**? | No → **Reject** |
| **T8** | Would **another BC become simpler if this were absorbed**? | Yes → **Merge** |
| **T9** | Does **implementation evidence support independence**? | No → **Remain Candidate (Deferred)** |

*A candidate that passes T1, T2, T7 cleanly and is not pulled into a merge by T3–T6/T8 is a strong BC; failures route to the verdict taxonomy below.*

## 2. Verdict taxonomy (replaces the blunt "Rejected")

| Verdict | Meaning |
|---------|---------|
| **Confirmed BC** | strong evidence supports an independent bounded context |
| **Merge** | better represented *within* another bounded context |
| **Supporting Subdomain** | not a BC, but a meaningful software module |
| **Generic Subdomain** | commodity capability |
| **Application Capability** | better placed *above* the domain layer |
| **Infrastructure Capability** | not domain logic |
| **Deferred** | insufficient evidence (remain Candidate) |

*This specializes `Round47-OP`'s general confidence levels for the BC case — crucially adding **Merge** and the **Subdomain/Capability** outcomes, which "Rejected" was too blunt to express. A `Merge`/`Subdomain`/`Capability` verdict **rejects only the software boundary** — the certified governance concept remains valid (SD-6 / `Round47-OP`).*

## 3. Confidence scoring

| Confidence | Basis |
|-----------|-------|
| **High** | passes T1/T2/T7 + ≥3 of T3–T6 cleanly + implementation evidence (T9) |
| **Medium** | passes T1/T2/T7 but ≥1 merge-pull (T3–T6/T8) unresolved, or weak T9 |
| **Low** | fails T1/T2/T7, or strong merge-pull, or no implementation evidence |

## 4. Evidence requirements (every verdict must cite)

1. **Evidence table** — observed classes → the capability they imply (the `Round49-01` style).
2. **Test results** — T1–T9 with the failing-answer dispositions.
3. **ADQC cross-check** — relevant ADQC v1.1 criteria (esp. Q2 ownership, Q10 invariant-integrity, Q11 boundary-clarity).
4. **Confidence** + the one-line reason.

*No verdict without evidence. A verdict asserting "Confirmed BC" with only "related classes exist" is invalid (capability ≠ context).*

## 5. Boundary Confidence Matrix (initial — to be filled/falsified in Round 49)

Prior (pre-evaluation) expectations; Round 49 must **try to falsify** each:

| Candidate | Prior confidence | Hypothesis to falsify in Round 49 |
|-----------|------------------|-----------------------------------|
| Adjudication | High | "not a BC" — strong ownership + autonomy expected to survive |
| Evidence | High | "not a BC" — SoR + immutability expected to survive |
| Replay | **Medium** | **"Replay belongs inside Evidence / is application/infra"** — investigate without assuming |
| Contestation | High (gap) | "the gap is not real / belongs in Adjudication" |
| **Appointment** | **Low** | **"Appointment is NOT a BC — merge into Authorization"** (challenge hardest) |
| Authorization | Medium | "merge with Appointment" |
| **Election Lifecycle** | **Medium** | **"merge into Voting"** (challenge) |
| Voting | High | "not a BC" — expected to survive |
| **Audit** | **Low** | **"Audit is Platform/Observability, NOT a domain BC"** (actively try to reject) |

**The three to challenge hardest:** Appointment (likely Merge→Authorization) · Election Lifecycle (possible Merge→Voting) · Audit (likely Infrastructure/Platform, not a BC). **Replay** is the genuine unknown (domain / application / infrastructure).

## 6. Self-review (framework completeness)
☑ Falsification stance (null = not-a-BC) · ☑ existence ≠ quality (separate from ADQC) · ☑ Merge + Subdomain + Capability verdicts available · ☑ evidence required for every verdict · ☑ Merge/Reject of a software boundary ≠ rejection of the governance concept (SD-6) · ☑ no code; no governance change.

---

*Round 48A — Bounded Context Evaluation Framework — ISSUED (instrument; applied in Round 49).*
*Falsification: null hypothesis = "NOT an independent BC"; candidate must survive 9 boundary tests (T1–T9). Verdict taxonomy adds Merge / Supporting-Subdomain / Generic-Subdomain / Application-Capability / Infrastructure-Capability / Deferred (boundary-rejection ≠ governance-concept-rejection). Confidence High/Med/Low; evidence required. Measures EXISTENCE (vs ADQC = quality). Challenge hardest: Appointment(merge?) · Lifecycle(merge?) · Audit(infra?); Replay = genuine unknown. Round 49 = application.*
