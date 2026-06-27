# Round 48A — Bounded Context Evaluation Framework (v1.1)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Under `Round47-OP`**
**Status:** 🧪 EVALUATION FRAMEWORK (instrument; v1.1). Defines the falsification test for whether a candidate seam is an independent bounded context. **Round 49 *applies* it.**
**Date:** 2026-06-26 · **Built against:** Landscape v1.0 · *(v1.0→v1.1: weighted tests; reproducible confidence; semantic-vs-software ownership; rejection record; discovery≠optimization; Boundary Decision Register.)*

> **Falsification stance.** A candidate's **null hypothesis is: *it is NOT an independent bounded context.*** It must **earn** existence by surviving the tests. Process: Candidate → Challenge → Evidence → Decision.
> **Two instruments.** This framework measures **boundary existence**; **ADQC** (`Round48-00`) measures **decision quality**. First "does it exist?", then "is it good?".
> **Discovery ≠ optimization (guard).** **Round 49 *discovers* boundaries** (confirm/merge/reject from evidence). **Round 50 *optimizes/designs* them** (aggregates). Do **not** redesign or optimize during Round 49.

## ⭐ Principle — semantic ownership ≠ software ownership (novel formulation)

**Semantic ownership never changes** (it is certified, frozen). **Software ownership can.** Example: *Evidence* is the **semantic owner** of evidentiary truth — but in software that may live as an **Evidence BC**, a **Replay capability over it**, or a **shared aggregate**. The semantic owner remains *Evidence* regardless. Therefore:

> **A boundary rejection rejects only the *software* boundary — never the certified concept** (SD-6). A `Merge`/`Capability` verdict relocates *software* responsibility; the governance concept is untouched.

*(This is one of the genuinely novel aspects of the methodology — it dissolves the common confusion of semantic truth with software structure.)*

## 1. The nine boundary tests — WEIGHTED

Tests are **not equal**. A candidate can pass many weak tests yet fail a decisive one.

### Primary (decisive — failing any ⇒ likely *not a BC* / Merge, regardless of other passes)
| # | Test | Failing answer → outcome |
|---|------|--------------------------|
| **T1** | Owns **business decisions**? | No → **Reject** |
| **T2** | Owns **authoritative truth** (system-of-record)? | No → **not a BC** (read model / capability) |
| **T5** | **Separate transactional consistency / autonomy** (holds its invariants in one txn, no sync dependence)? | No → **Merge** |

### Secondary (strong, not decisive)
| # | Test | Failing → |
|---|------|-----------|
| **T4** | Separate **lifecycle**? | Merge |
| **T6** | Separate **ubiquitous language**? | Merge |
| **T9** | **Implementation evidence** supports independence? | Remain Candidate / Deferred |

### Supporting (corroborating)
| # | Test | Failing → |
|---|------|-----------|
| **T3** | Can **evolve independently**? | Merge |
| **T7** | Separate **business capability**? | Reject |
| **T8** | Would **another BC simplify if this were absorbed**? (Yes →) | Merge |

## 2. Verdict taxonomy
**Confirmed BC · Merge · Supporting Subdomain · Generic Subdomain · Application Capability · Infrastructure Capability · Deferred.** *(Merge/Subdomain/Capability reject only the software boundary; the governance concept is retained.)*

## 3. Reproducible confidence (state WHY, not just the label)
| Confidence | Rule |
|-----------|------|
| **High** | **all 3 Primary pass** + ≥2 Secondary + **no contradiction** |
| **Medium** | all Primary pass but Secondary mixed, **or** 1 Primary uncertain |
| **Low** | a **Primary fails**, or little/no evidence |

*Every confidence label cites the counts, e.g. "High — T1/T2/T5 pass, T4/T6 pass, T9 partial, no contradiction."*

## 4. Evidence requirements (every verdict)
Evidence table (classes → capability) · T1–T9 results (weighted) · ADQC cross-check (Q2/Q10/Q11) · confidence with counts. **No verdict on "related classes exist" alone (capability ≠ context).**

## 5. Rejection / Merge Record (mandatory when a candidate is not Confirmed)
```
Candidate:           <name>
Verdict:             Merge | Supporting Subdomain | Application/Infra Capability | Rejected | Deferred
Failed test(s):      <e.g. T1 (no independent decisions), T5 (no transactional boundary)>
Reason:              <one line>
Software disposition: merged into <BC> | becomes capability over <BC> | infra/platform
Semantic concept:    RETAINED (certified) — only the software boundary changes
Confidence:          <High/Med/Low + counts>
```

## 6. Boundary Confidence Matrix (the Round-49 roadmap — what to investigate)
| Candidate | Boundary confidence | Round-49 hypothesis to falsify |
|-----------|---------------------|-------------------------------|
| Adjudication | **High** | "not a BC" |
| Evidence | **Medium** | "not a BC / Replay is separate" |
| **Replay (Capability)** | **Low** | **"Replay is a *capability* over Evidence / app / infra — not a BC"** *(treated as Capability until proven otherwise)* |
| Contestation | **High** (gap real) | "belongs inside Adjudication" |
| Authorization | **Medium** | "merge with Appointment" |
| **Appointment** | **Low** | **"not a BC — merge into Authorization"** |
| **Election Lifecycle** | **Medium** | **"merge into Voting"** |
| Voting | Medium-High | "engine ≠ BC" |
| **Audit** | **Low** | **"Infrastructure/Platform, not a domain BC"** |

## 7. Boundary Decision Register (BDR) — the authoritative OUTPUT of Round 49
Round 49 produces a BDR (an ADR-analogue for boundaries):
| Candidate | Decision | Confidence | Evidence | Reason |
|-----------|----------|-----------|----------|--------|
| *(e.g.)* Adjudication | Confirmed BC | High | T1–T9 | owns decisions + autonomy + lifecycle |
| *(e.g.)* Replay | Deferred / Capability | Low | T1–T9 | insufficient independence; likely capability |
| *(e.g.)* Appointment | Merged → Authorization | Medium | T1–T9 | no independent decision ownership |
The BDR is the **authoritative software-boundary record** consumed by Aggregate Design (Round 50). *Certified governance concepts are never altered by it.*

## 8. Self-review
☑ Falsification (null = not-a-BC) · ☑ weighted tests (Primary decisive) · ☑ existence ≠ quality (vs ADQC) · ☑ semantic ≠ software ownership · ☑ Merge/Subdomain/Capability verdicts · ☑ rejection record mandatory · ☑ discovery ≠ optimization · ☑ BDR output · ☑ no code; no governance change.

---

*Round 48A — Bounded Context Evaluation Framework v1.1 — ISSUED (instrument; applied in Round 49).*
*Null = "NOT a BC". 9 tests WEIGHTED (Primary T1/T2/T5 decisive · Secondary T4/T6/T9 · Supporting T3/T7/T8). Verdicts incl. Merge/Subdomain/Capability/Deferred (boundary-rejection ≠ concept-rejection — semantic ownership ≠ software ownership). Reproducible confidence (counts). Rejection Record mandatory. Discovery ≠ optimization (R49 discovers, R50 optimizes). Output = Boundary Decision Register. Challenge hardest: Appointment(merge?)/Lifecycle(merge?)/Audit(infra?); Replay = Capability-until-proven. EXISTENCE instrument (vs ADQC=quality).*
