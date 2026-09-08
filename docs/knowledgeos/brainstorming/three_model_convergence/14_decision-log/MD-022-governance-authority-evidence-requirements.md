# MD-022 — Governance Authority Evidence-Requirements Preparation (K-2 track only)

**2026-09-08.** Not a research phase. Not Phase 5O. Not an authority determination, governance
decision, or ratification. This artifact prepares the evidence requirements and verification tooling
a real organizational governance process will need when obtaining authority evidence **outside** the
corpus this programme has read.

## 1. Scope and relationship to MD-021

MD-022 derives its starting state from `14_decision-log/MD-021-research-to-governance-handover.md`,
the current research-to-governance boundary. MD-022 prepares evidence requirements only. It does not
search for, infer, name, recommend, or determine a decision authority. Scope is **K-2 track only**:
`GK-5K-1` through `GK-5K-5`. **K-1/OQ-2 is explicitly out of scope** — per MD-021 §9, the parallelism
between the two tracks is structural, not evidentiary, and combining them here would blur exactly the
boundary MD-021 exists to hold.

## 2. Restated established finding

**`GOVERNANCE AUTHORITY NOT EVIDENCED`.**

- **Established** in Phase 5K, `03_decision-authority-analysis.md` — no document establishes HPA's own
  mandate, charter, or delegation source; applying seq 0927's own Authority Chain test,
  `Mandate(HPA)` and `Authorized(HPA, Assertion-schema decisions)` are both NOT EVIDENCED.
- **Independently re-tested and re-confirmed** in Phase 5L, `08_governance-authority-reaudit.md` —
  search widened corpus-wide (not merely `phase_measure_theory/`-scoped); same result; explicitly
  recorded as *strengthening* Phase 5K's own finding, not merely repeating it.
- **Independently re-tested and re-confirmed** in Phase 5N, `07_authority-and-legitimacy.md` — HPA
  re-tested specifically against the D-FA-6/D-FA-4 rulings; unchanged conclusion.

**"Not evidenced" does not mean "does not exist."** No further search of this corpus is performed by
this document, and none is warranted: three independent tests, at three different scopes, reached the
same result.

## 3. Evidence-status taxonomy

| Level | Definition | What could move an item into this status | Who may perform that transition |
|---|---|---|---|
| 1. Direct evidence | A claim directly stated, verbatim or near-verbatim, in a source document | Locating the exact quoted text | Any reader, verified against source |
| 2. Machine-observable fact | A fact confirmable by mechanical means (a search count, a file's presence/absence, a git log entry) | Running the mechanical check | Any reader, reproducibly |
| 3. Reconstructed provenance | A relationship inferred by combining multiple direct-evidence items (e.g. identity across two labels) | Explicit, disclosed inference from items at level 1–2 | The researcher performing the reconstruction, disclosed as such |
| 4. Research interpretation | A reasoned reading of level 1–3 material that goes beyond what is directly stated | Explicit argument, held non-authoritative | The researcher, never presented as fact |
| 5. Hypothesis | A proposition offered for future testing, not yet supported or refuted | Naming a testable claim | Any researcher |
| 6. Candidate | An option identified as one of several live possibilities, not preferred | Inclusion in a formally evaluated option set (e.g. 5K's A–E) | The evaluating research phase |
| 7. Recommendation | A non-binding suggestion among candidates, explicitly labeled `NON-BINDING` | A reasoned comparison (e.g. 5K `14`) | The evaluating research phase, never self-executing |
| 8. Governance decision | A substantive choice made by a legitimately authorized body | An act by a party whose authority is itself evidenced per §4 below | Only a legitimately authorized organizational actor |
| 9. Ratified / canonical | A governance decision formally recorded as authoritative per the organization's own ratification mechanism | The organization's own `Record`/`Effective` act (seq 0927's own `Prepare → Decide → Ratify → Record` sequence) | Only the organization's own ratification mechanism |

**This research programme cannot promote any item into level 8 or 9.** Every finding this programme has
produced about GK-5K-1 through GK-5K-5 sits at levels 1–7; none has been, or can be, self-promoted.

## 4. Required authority evidence

Reusing seq 0927's own predicate directly — no replacement predicate is invented:

$$LegitimateAuthority(a,\ scope,\ source,\ validity)$$

For a claimed authority `a` to satisfy this for the decision class `GK-5K-1..5`, the organization would
need to supply a `source` (an artifact or act establishing `a`'s standing) and `validity` (that the
grant is current and covers this scope). Evidence categories that could potentially satisfy this — **not
assumed to exist, not evaluated against any candidate**:

- A governance charter that explicitly names this decision class (K-2 Assertion reconciliation) within
  its scope.
- A formal role mandate (a person or role explicitly tasked with resolving KnowledgeOS kernel/schema
  conflicts).
- A committee mandate (a body with documented jurisdiction over this class of technical-governance
  question).
- An explicit delegation record (a higher authority formally delegating this decision downward).
- An approved decision-rights or RACI assignment naming a responsible/accountable party for this class
  of decision.
- A formally recorded governance responsibility, dated and still current.

**No candidate authority is named. HPA is not implied, suggested, or excluded by this list** — the
list is evidence-category-shaped, not name-shaped.

## 5. Authority-chain verification test

A reusable template, for future use only — **not applied to any candidate in this document**:

1. Identify the purported decision-maker.
2. Identify the organizational role or body they act through.
3. Identify the source establishing that role/body's own authority.
4. Determine whether that source's scope covers this decision class (K-2 Assertion reconciliation).
5. Determine whether the authority is current (not lapsed, not superseded).
6. Determine whether delegation is permitted under the source.
7. If delegated, identify the delegation's own source and confirm it independently.
8. Confirm explicitly whether the authority covers `GK-5K-1` through `GK-5K-5` specifically, not
   merely KnowledgeOS matters generally.
9. Record any partial, ambiguous, expired, or unresolved scope explicitly — do not round up to "covered."

Steps 1–9 mirror seq 0927's own `ValidRatification(d) \iff Mandate(a) \land Authorized(a,d) \land
Decision(a,d) \land Recorded(d) \land Effective(d)` decomposition, applied prospectively rather than
retrospectively.

## 6. K-2 decision-readiness matrix

| Decision | Semantic research complete? | Authority established? | Governance decision possible? | Ratification possible? |
|---|---:|---:|---:|---:|
| GK-5K-1 (which Assertion option) | YES | NO | NO | NO |
| GK-5K-2 (who has authority) | YES as a research finding | NO | NO | NO |
| GK-5K-3 (Π meaning) | YES | NO | NO | NO |
| GK-5K-4 (Qualify arity) | YES | NO | NO | NO |
| GK-5K-5 (State's carrier) | YES | NO | NO | NO |

"Semantic research complete" means the authorized research established the current unresolved state —
**not** that the substantive governance question has been answered. GK-5K-2's own "YES as a research
finding" reflects that the finding *is* `GOVERNANCE AUTHORITY NOT EVIDENCED` — a completed research
result whose content is itself a negative.

## 7. Governance sequencing (non-collapsible)

- **A — Authority establishment.** The organization establishes who is legitimately empowered to
  decide. *Not performed by this document.*
- **B — Governance decision.** That authorized authority decides the substantive question (GK-5K-1,
  -3, -4, -5). *Cannot occur until A.*
- **C — Ratification / canonicalization.** The organization's own mechanism formally records the
  decision as authoritative. *Cannot occur until B.*

## 8. Explicit prohibited actions

This document does not, and no action following from it may without separate authorization: open
Phase 5O; conduct another corpus search for authority; search for a candidate authority; name or imply
HPA (or any other actor) as authority; infer authority from repeated ruling/review behavior; select
Option A, B, C, D, or E; resolve Π; resolve Qualify's arity; resolve State's carrier; create Schema v3;
canonicalize Assertion; close K-1/OQ-2; adopt a DDD Context Map; perform four-model or cross-model
convergence; use P-series material as evidence; modify any frozen artifact (5A–5N, MD-021, source
documents, or the three executable scripts).

## 9. Exact organizational ask

**Establish, within the real organization, which body, role, or explicitly delegated authority is
legitimately empowered to decide `GK-5K-1` through `GK-5K-5`.** Supply an organizational artifact or
explicit delegation satisfying one of §4's evidence categories, or state explicitly that no such
evidence is currently available. This request does not ask the organization to select an Assertion
option at this stage, unless its own legitimate governance process explicitly combines authority
establishment with substantive decision-making.

## 10. Statistical discipline (applied throughout)

No frequency, recurrence, document count, number of rulings, number of mentions, or lexical prevalence
is treated as evidence of organizational authority anywhere in this document (§2, §4). No numerical
scoring or confidence value is assigned to any authority alternative — none is evaluated. §2's three
confirmations are stated as what they are — one finding established once and independently re-tested
twice at widening scope — never as three independent replications.

## 11. Mathematical discipline (applied throughout)

Definition, observation, derivation, interpretation, candidate, recommendation, governance decision,
and ratification are kept as the nine distinct levels of §3 throughout this document. No table
formatting (§4, §5, §6) promotes any item across a level boundary. No formal notation (§4's
`LegitimateAuthority` predicate, §5's chain test) is used to manufacture organizational authority — it
is used only to specify what would need to be true for authority to hold.

## 12. DDD discipline (applied throughout)

No Bounded Context, Context Map, Aggregate, canonical invariant, domain contract, Assertion schema, or
Schema v3 is adopted or defined as a result of this preparation. This document's purpose is limited to
the governance *mechanism* by which such decisions may later be legitimately made — never the
decisions themselves. Architectural plausibility is not, anywhere in this document, substituted for
organizational authority.

## 13. Knowledge-engineering discipline (applied throughout)

Every requirement in §4's checklist and every level in §3's taxonomy carries its own definition and, for
§2, its own source pointer — none is freestanding. The four-way distinction is maintained explicitly:
evidence that an authority exists (§4, not yet supplied) ≠ evidence that an actor performs a function
(HPA's own behavioral pattern, established in 5K/5L/5N, not reused here as authority evidence) ≠
inference that the actor is therefore authorized (never performed, in this document or any prior phase)
≠ formally established legitimate authority (§4's own target, not yet met). Only the last satisfies the
governance requirement. No frozen research artifact is normalized, repaired, merged, or reinterpreted
by this document.

## 14. Provenance statement

MD-022 is derived entirely from the frozen Three-Model governance-boundary state recorded in MD-021.
No new semantic research was performed to produce it. No authority was established by it. No candidate
authority was named by it. No substantive governance decision was made by it. No ratification occurred
as a result of it.

## 15. Final status

**This artifact establishes no authority, decides no question, and opens no new phase. It exists
solely so that whoever next looks for governance-authority evidence outside this corpus knows precisely
what evidence would count and how that evidence must be verified.**

---

```
MD-022 COMPLETE — GOVERNANCE AUTHORITY EVIDENCE-REQUIREMENTS PREPARED, K-2 TRACK ONLY.
GK-5K-1 THROUGH GK-5K-5 REMAIN OPEN. K-1/OQ-2 NOT TOUCHED. PHASE 5O NOT OPENED.
NO RATIFICATION, CANONICALIZATION, OR IMPLEMENTATION OCCURRED.
```
