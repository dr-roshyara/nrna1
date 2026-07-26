# EPIC-004J Determination Domain Services

**Kind:** Tactical DDD artifact №9 — **the final artifact of the chain** (one artifact → ARB review → refine → freeze). **Role:** implementation architect from the frozen baseline.
**Entry condition (ARB, binding — deliberately the strictest of the series):** *a Domain Service exists only when a business operation cannot naturally belong to a single Aggregate while still preserving the protected domain truths of the participating Aggregates. If removing the service weakens no accepted business responsibility or invariant, it does not become a Domain Service.*
**Litmus:** *does this business operation naturally belong to a single Aggregate?* If yes → not a Domain Service. Historically, Domain Services are where DDD models dilute — anything that fits nowhere gets moved into a service; this artifact exists to prevent exactly that.
**The honest shape — stated up front:** the extended Adjudication context contains **one ratified aggregate**. By the entry condition's own logic ("coordination across aggregate boundaries"), there is structurally almost nothing for a Domain Service to do inside this context today. The implemented context confirms it: **`Domain/Policies/` is empty (a `.gitkeep`), and no domain service exists** — the `AdjudicationService`/`CoordinatesAdjudication`/`TransactionalAdjudicationService` trio lives in the Application layer, where it belongs.

---

## 1. Result: ZERO Domain Services confirmed — and that is the correct answer, not a gap

Every candidate either belongs to the single aggregate, to the application layer (per a frozen finding), to Governance (per Q-1), or to the Emergent Design Cluster. The empty `Domain/Policies/` folder is honest implementation evidence: the nucleus never needed a domain service, and the audit confirms it still doesn't.

## 2. Candidates evaluated (each against the litmus)

- **Issuance coordination (`CoordinatesAdjudication`) — REJECTED as a Domain Service; confirmed where it is.** Its business content is INV-B1's boundary check plus orchestration of ports (repository, outbox, transaction, identity) — infrastructure-facing coordination, which is *application*-service work by definition. Its seat was frozen at R-3 (the issuance boundary); reclassifying it as a domain service would reopen a frozen finding for no gain. The litmus besides: nothing here coordinates *across aggregates* — there is one.
- **Transaction wrapping (`TransactionalAdjudicationService`) — REJECTED.** Pure mechanics of ADR-T1's one-transaction rule; not a business operation at all.
- **`LegitimacyDecision` / sufficiency policy — DEFERRED, with a genuine finding (this artifact's contribution).** ADR-T17 recorded, pre-Q-1, that the legitimacy rules would eventually live as an **"Adjudication DOMAIN SERVICE"** — while deferring the real rules. **The frozen rulings since then have moved the ground under that placement:** Q-1 rules that *the authority decides* (legitimacy/sufficiency conclusions are received, never computed — K1 structural); the Candidate-2 ruling gives judgment orchestration to the PM. A domain service that *decides* legitimacy would now contradict K1; at most, a future service could *advise* (compute a recommendation an authority weighs) — a materially different thing than ADR-T17's wording anticipated. **Recorded finding for the ARB — in the traceability form the ARB prescribed:** *ADR Observation → later governance artifacts (Q-1, K1-structural, the Candidate-2 ruling) provide stronger evidence → **ADR-T17 REMAINS VALID** as the recorded decision of its time **until a superseding ADR is issued** — which the PM design is expected to produce.* The tension is named, never silently overridden; architectural traceability preserved (the old decision stands in the record even as the ground moves under it). → **Emergent Design Cluster** (the judgment half's realization is its parent question).
- **Authority validation against Governance's published contract — REJECTED for this context.** The Q-1 binding constraint is explicit: Adjudication never implements or duplicates authority rules; the consultation of Governance's contract is the *crossing's* tactical design (likely an application-layer port when designed), never an Adjudication domain service.
- **Evidence sufficiency computation — DEFERRED → cluster.** Same parent as DeclareFailure/EvidenceSet: meaningless until judgment orchestration is modeled.
- **Finalization policy (window-closure → finalize) — DEFERRED → the Q-2/finality center.** If №7's temporal-policy hypothesis proves out, the *policy* realization could be domain-service-shaped — but its definition belongs to Q-2; presupposing the shape now would repeat the exact mistake the corrected reversal condition avoids.

## 3. Open items carried (the chain's complete residue)

- **The Emergent Design Cluster** (parent: the Adjudication PM): FailureDeclared · DeclareFailure · EvidenceSet · R-4-expanded seat · loop-head request shape · PM-state persistence (№8) · **the ADR-T17 placement revision (№9, new)** — seven members now, one opening agenda.
- **The Q-2/finality center:** retention arithmetic · finality's trigger · the realization shape (command vs. temporal policy vs. PM reaction) — cross-referenced with the cluster, never merged.
- **Jurisdiction semantics** (non-blocking).

## Self-review

Zero domain services confirmed — the entry condition held at maximum strictness, and the structural argument (one aggregate → no cross-aggregate coordination to own) is stated rather than padded ✅ · every candidate faced the litmus; the strongest temptation (reclassifying the existing application services) was refused with the frozen R-3 finding as grounds ✅ · one genuine finding surfaced: the ADR-T17 placement tension with Q-1/K1 — named for the ARB, not silently absorbed or silently contradicted ✅ · ASP throughout: the empty `Domain/Policies/` folder is now a *defended* emptiness ✅ · all deferrals landed in their named centers; the cluster's membership is current (seven) ✅ · ADP lineage intact; no code designed or modified ✅.

**Restraint decision (recorded per ARB):** a "No Domain Service Principle" is deliberately **NOT adopted** — the zero-services outcome is domain-specific, and the reusable knowledge is already fully captured by this artifact's entry condition. The methodology declines to mint a principle from a single context's result — the same evidence-before-promotion discipline that has governed every promotion since EPIC-001.

**Stop condition: STOP — and with this artifact, the nine-artifact chain is COMPLETE.** Await ARB review → refine → freeze of №9. What follows the freeze is the ARB's call — the natural candidates being: the chain-completion review of the whole Determination tactical model; the PM design (its agenda pre-assembled by the cluster); or Q-2's resolution (triply loaded). None is presupposed here.

---
*Frozen inputs: `EPIC-004I` (repositories; RMSP adopted at its freeze) · the full frozen chain №2–№8 · `EPIC-004_Q1_Authority_Resolution.md` (binding constraint) · ADR-T17 (remains valid; superseding ADR expected from the PM design) · Implementation evidence: EPIC-003 inventory (empty `Domain/Policies/`; the Application-layer service trio).*

---

## FROZEN (ARB, 2026-07-26) — CHAIN COMPLETE

**Artifact №9 is accepted and FROZEN. The nine-artifact tactical chain for the Determination aggregate is COMPLETE.** The ARB's recorded verdict: the zero-confirmed-services result is the series' most important methodological validation — the methodology demonstrated it can say **no** at every level (VOs, events, commands, repository operations, and now an entire pattern) when evidence does not support another concept; ASP extended to its strongest application (a justified absence of a whole pattern). The chain's complete residue stands in two named business centers (the seven-member PM cluster · the Q-2/finality center) plus one non-blocking item (Jurisdiction semantics). **What follows is the ARB's choice** — chain-completion review · PM design · Q-2 resolution — none presupposed.
