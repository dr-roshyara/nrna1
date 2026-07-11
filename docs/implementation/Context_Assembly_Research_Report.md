# Context Assembly — Research Report

**Kind:** research report (charter: `Context_Assembly_Research_Charter.md`) — answers the five research questions from evidence; designs nothing.
**Status:** COMPLETE — presented for ARB review. **STOP: no design of the ContextAssemblyService until the ARB rules.**
**Evidence:** FIRST-PARTY (session logs 2026-07-08..11 = four days of recorded engineering; the E-1 grep executed 2026-07-11) + the held corpus (executors B/C/D). Labels: FACT · INTERPRETATION · RECOMMENDATION · OPEN.

---

## 0. The headline finding — E-1 answered

**FACT:** the EKP (`docs/knowledge/`) was consulted **essentially never** during PB-004..007: two mentions in four days of session logs, both administrative (a setup reference and the parsimony analysis itself), zero in-flow consultations. What sessions actually consumed: **IDDs, the ADR corpus, the Blueprint, developer guides, prior session logs, and the code itself** — live sources, read at the moment of need.
**INTERPRETATION:** the EKP exhibits the second-population pattern its own governance was designed to avoid — its consumption model (portal navigation) sits outside the engineering gesture. Its *metadata model* (ownership, status, lifecycle) remains aligned with the constitution; its *consumption model* is the falsified part. This is precisely what the same-population law predicted.
**FACT (the counter-observation):** the knowledge that WAS consumed in-flow — IDDs, ADRs, guides, logs — all live where the work happens and are maintained by the working population. The project's *de facto* knowledge system already obeys the constitution; the EKP is a parallel structure the flow routes around.

## 1. KnowledgeNeed lifecycle (state machine, first-party-grounded)

**FACT:** the platform already runs an unrecognized KnowledgeNeed state machine — the EP-03 Engineering Readiness Review. Its "derive first, ask only gaps" is need qualification; its stopping condition is the satisfiability test.

| State | Trigger in | Trigger out | First-party evidence | Invariants |
|---|---|---|---|---|
| **Expressed** | A task exists (ticket, review, question, session start) | ERR begins | every session's opening ("read MEMORY/CONTEXT/plan/log") | need is task-bound, never free-floating |
| **Qualified** | ERR partitions the need: *derivable* vs *must-ask* | derivable → Assembly; must-ask → routed to human | the ERR's standard output ("Derived: context=Election… Cannot determine: X — please decide") | the partition is explicit, recorded in the plan |
| **Assembly** | reading/agentic search over live sources | all predicate classes covered → Satisfied; a gap found → Unsatisfiable branch | logs show IDD/ADR/guide/code reads; greps; `git log` archaeology | live sources default (PK-AD8); status visible on what is read (weakly held today — see §2 failures) |
| **Satisfied** | a plan can be produced (the ERR stopping condition) | task proceeds; the Working Context exists | 15-step process entered; EP-01 plans produced | sufficiency judged by the stopping condition, validated by outcome (§3) |
| **Unsatisfiable** | knowledge does not exist or authority is required | routed: to the ARB (ruling request) or to discovery (a finding) | **43 STOP/ask/ruling markers in 3 days**; PB-006's missing-bridge finding (the need "how does outbox reach inbox?" was unsatisfiable from all records — satisfied only by code search, then became a FINDING and new construction) ; F-7D-2 (need "which MSI is true?" → ARB ruling) | unmet needs are recorded, never silently worked around (stop-at-uncertainty behaviour, R-36) |
| **Superseded** | plan invalidation (EP-01 step 6) or a ruling changing the ground | a new need is expressed | re-plan events; "plan invalidated: STOP" occurrences | the old need's record survives (append-only) |

**FACT (the load-bearing state):** Unsatisfiable is the most productive state in the record — PB-006's entire dispatcher construction, F-7D-2's measurement investigation, and multiple ADRs originated as unsatisfiable needs. **INTERPRETATION:** unmet needs are the project's highest-quality signal for what knowledge to produce next — demand-driven production, inverting the failed supply-driven KM tradition.

## 2. Concept-to-coordinate mapping

**FACT (worked example from the record, PB-004 "implement Election reaction"):**

| Need element | Coordinates consumed (observed) |
|---|---|
| Concept "Election" (greenfield) | Definition: UL corpus + ADR-UL-01 lineage · **failure surfaced:** legacy `Elections/` vs greenfield `Election/` — same term, two contexts, disambiguated only by convention |
| Governing decisions | ADR-T8 (no saga), ADR-T16 (local VOs), D-02, Blueprint §§ — Decision×Recorded×Canonical×Project |
| Rules | anonymity CI-5/Q7 as **fitness tests** — Constraint×Executable (consumed by running them) |
| Construction knowledge | developer guides + prior slices' commits |
| Memory | prior session logs (reconstruction source — named as such in the retrospective's "used and earned their keep") |

**Where the mapping fails (all first-party):** (a) **homonyms across contexts** — `DeterminationId` deliberately differs per context (handled BY DESIGN via ADR-T16: the mapping must be context-scoped or it is wrong); (b) **stale coordinates presented as canonical** — the boards drifted five closures behind (O-8): a consumer mapping "current state" to BACKLOG.md got falsehoods with canonical scent; (c) **label drift** — "Approval Authority"/"Decision Authority" (F-OQ-1): one concept, two labels, caught only by qualification; (d) **scent gaps** — knowledge that existed but was found by `git log` archaeology rather than by address.
**INTERPRETATION:** the mapping mechanism that works today is *conventions + pointers + agentic search* (MEMORY/CONTEXT as the pointer layer; naming conventions as scent; grep as the resolver). Its failures are exactly the constitutional gaps: status-at-discovery not yet stamped on everything (b), and label discipline (c).

## 3. Sufficiency measurement (the PK-P6 proposal)

**FACT:** sufficiency was never measured in the record — but its violations were, in both directions:
- **Under-inclusion events (observable, countable):** mid-task STOP-and-ask occurrences · re-derivations of derivable facts · deviations recorded at EP-02 (ER-08 deviations are often a missing caveat) · **the 07-11 log-overwrite incident** — a genuine first-party under-inclusion failure: the acting session lacked one fact (the file already existed) that was in scope and discoverable.
- **Over-inclusion events (observable):** context compaction occurrences (this very research program's session compacted — a lossy disposal, correlating with executor D's compression-fidelity findings) · token spend · re-reading of already-held material.

**RECOMMENDATION (the measurement model):**
| Aspect | Proposal |
|---|---|
| Metric | Per task: **U** = count of under-inclusion events (unplanned mid-task need expressions: stops, asks, re-derivations, missed-fact incidents) · **O** = over-inclusion cost (tokens consumed on unused material; compaction events). Sufficiency improves as both fall. **Both are countable from existing records** — no new instrumentation class needed. |
| When measurable | Before task: only a weak **coverage check** (does the Working Context contain claims for every predicate class the Need derived? — the ERR stopping condition, formalized). During/after: U and O, the strong signals. **Sufficiency is conclusively observable only by use** — consistent with validation-is-use (FACT, constitutional). |
| Cost asymmetry | Evidence says under-inclusion is the costlier failure for correctness (missing caveats change decisions — D) while over-inclusion is the costlier for economics (rot + tokens — D). The metric must never collapse the two into one number (score-persistence discipline applies). |

## 4. Contradiction ranking model (E-2)

**FACT:** the record contains real contradictions and their handling: F-7D-2 (75% vs 50% MSI — two measurements, same referent) was **not resolved by any automatic rank**: it was surfaced, investigated, and ruled by the ARB, with the losing number retained as marked history. The two-session log conflict was resolved by restoration + both-preserved. Wikidata/ADR/ROT converge on rank-and-retain (corpus).

**RECOMMENDATION (lexicographic ranking for machine consumers, with the human rail):**

| Order | Criterion | Rationale | Evidence | Trade-off |
|---|---|---|---|---|
| 1 | Governance status | canonical > draft > deprecated — the constitutional signal | C (ranks), first-party statuses | requires status stamping to exist (§2 failure b is the blocker) |
| 2 | Authority-scope applicability | a constraint of wider scope wins conflicts it governs; out-of-scope claims don't compete | C (scoped canonicity), EM-001 split | scope must be recorded; provisional dimension |
| 3 | Execution proximity | executable/measured > prose | B (trust gradient r=0.67→0.03) | executables can lag intent |
| 4 | Recency | tiebreaker only — **never a trump** | D (freshness rot) BUT born-stale (58.4%) disproves recency=validity | must not override status |
| 5 | Provenance depth | triangulated claims > single-source | C (nanopublications) | bookkeeping cost |

**The rail (invariant 4, non-negotiable):** ranking *presents*; it never *erases*. A machine consumer acts on the top rank **with the contradiction flagged in its output**; a high-stakes contradiction (constraint-level, F-7D-2-class) escalates to a human — first-party evidence shows this is what actually worked. Presentation to humans: the ranked pair with reasons (the Wikidata pattern), not a decision tree.

## 5. WorkingContext lifecycle (first-party: every session IS one)

| Stage | Observed reality | Evidence |
|---|---|---|
| Creation | SessionStart injection (MEMORY/CONTEXT/plan/log) + ERR-driven reads — the existing gesture | hooks + every session opening |
| Use | the task consumes the assembled set; status weakly visible (§2b) | all logs |
| Update | mid-task arrivals: ARB rulings, new findings, parallel-session commits — the context is LIVE, not a snapshot | rulings folded mid-task throughout the record |
| Disposal | session end — or **compaction, the observed lossy disposal**: an unplanned, mid-task, partial disposal with measured fidelity risk (decontextualization — D) | this program's own compacted session |
| Feedback extraction | **already implemented as the session-close discipline:** decisions → session log · evidence → commits · plan state → plan files · unmet needs → open-question registers · durable facts → MEMORY | the standing session-end workflow |
| What genuinely vanishes | the assembled reading set, the reasoning traces, the conversation — correctly ephemeral (no second population) | by construction |

**INTERPRETATION:** the WorkingContext lifecycle does not need to be invented — it needs to be *named and hardened*: the weak points are creation (project-context assembly is ad hoc — the original observed gap), disposal (compaction is unmanaged and lossy), and status visibility during use.

## 6. Readiness assessment

| Question | Answer |
|---|---|
| Sufficient to design ContextAssemblyService? | **CONDITIONALLY YES.** The service is not an invention — it is a **formalization of observed, working practice** (ERR = need qualification; injection+agentic search = assembly; session-close = feedback extraction). All five questions have evidence-grounded answers; two have proposals rather than validated models (sufficiency metric §3, ranking order §4). |
| Recommended next step | **The instrumented pilot** (charter option 3): ONE real ticket run with explicit Need derivation, recorded reads, and U/O counting — producing the sufficiency baseline and testing the ranking on any live contradiction. Cheapest decisive evidence; also the direct test of the reflexive risk. Only after the pilot: the Project Knowledge Reference Architecture. |
| Missing evidence | The pilot's baseline (U/O counts on a real task) · ranking validated on live contradictions · the EKP disposition decision (its metadata model is constitution-aligned; its consumption model is falsified — the ARB must rule: refit as the status/metadata layer consumed in-flow, or archive per staged forgetting). |
| Biggest remaining uncertainty | Whether formalizing the assembly (naming it, instrumenting it) preserves the same-population property that makes the current informal practice work — exactly the charter's reflexive risk; only the pilot answers it. |

---
**Constraint check:** no design, no structures, no implementation; every claim labeled and traced (first-party greps + logs; corpus by executor). **STOP — awaiting ARB review.**
