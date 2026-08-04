# Engineering Capability Map — the verification platform

**Produced by:** ARB Chief / Chief Software Architect, 2026-08-04.
**This is an assessment of capabilities and their dependencies.** It contains **no implementation plan, no technology choice, no backlog priority and no roadmap sequencing** — by instruction and by design.
**Evidence base:** this session's execution output, the WP-4B/WP-4C-1 delivery record, the ENG-012 diagnosis, and the Event D wiring evidence. **No new investigation was performed.**

---

## 1. Capability inventory

Each described independently, without comparison.

| # | Capability | Purpose |
|---|---|---|
| **C1** | **Behaviour verification** | prove that code does what the design says it does |
| **C2** | **Production-path coverage** | prove that the code path which *ships* is the one exercised |
| **C3** | **Test isolation** | prevent a test's environment from perturbing or being perturbed by others |
| **C4** | **Mutation confidence** | establish that a passing test is capable of failing |
| **C5** | **Verification observability** | interpret verification output — distinguish what a signal means |
| **C6** | **Framework-integration understanding** | know how the framework's runtime affects our own |
| **C7** | **Execution diagnostics** | localise a failure to its cause once one occurs |
| **C8** | **Engineering evidence generation** | produce evidence an acceptance authority can act on |

## 2. Maturity assessment

| | Maturity | Supporting evidence | Known limitations |
|---|---|---|---|
| **C1** Behaviour verification | **MATURE** | 8 seam keystones · §12 reconcile branches · database round trips · 307-test gate at exit 0 | proves behaviour **through doubles** at unit level |
| **C2** Production-path coverage | **WEAK** | `CoordinatorIssuanceRequest` had **never been executed** by any test until today; the container's undecorated resolution was **invisible to every test** | no capability exists to answer *"is the shipped wiring exercised?"* |
| **C3** Test isolation | **MATURE** | `RefreshDatabase` per test; unit tests deliberately avoid container resolution to escape `TenantContext::require()` | **achieved partly by substituting doubles for shipped collaborators** — see §3's tension |
| **C4** Mutation confidence | **EMERGING** | one deliberate check: breaking `'jurisdiction' => null` failed all three round-trip tests | **a single instance, applied by choice, not by practice.** No other test in the corpus has been shown able to fail |
| **C5** Verification observability | **IMMATURE** | ENG-012: one aggregate `risky` count blends four signal classes; no baseline | cannot answer *"did the signal change because the product changed?"* |
| **C6** Framework-integration understanding | **PARTIAL** | app-boot handler installation located; one plausible mechanism disproven | the acting mechanism remains **unestablished** |
| **C7** Execution diagnostics | **MATURE** | per-batch cadence localised a displaced docblock (1→8 static errors) and a query-typing fault to the batch that caused each | untested against failures spanning batches |
| **C8** Engineering evidence generation | **MATURE** | acceptance packages · evidence/inference/hypothesis labelling · **disproven hypotheses recorded rather than replaced** | none observed |

## 3. Capability dependency map

```
        C3 Test isolation ──────┐
                                ├──► C1 Behaviour verification ──┐
        C4 Mutation confidence ─┘                                │
                                                                 ├──► C8 Engineering
        C6 Framework-integration ──► C5 Verification             │     evidence
             understanding             observability ────────────┤     generation
                                                                 │
        C3 Test isolation ─(TENSION)─► C2 Production-path ───────┘
                                            coverage
                                                 ▲
                                            C7 Execution
                                            diagnostics
```

| Provider | Consumer | Consequence if the provider is weak |
|---|---|---|
| **C3** → **C1** | isolation → behaviour verification | behaviour results become order-dependent and untrustworthy |
| **C4** → **C1** | mutation confidence → behaviour verification | passing tests may prove nothing; **coverage becomes indistinguishable from verification** |
| **C6** → **C5** | framework understanding → observability | signals cannot be classified, because their origin is unknown |
| **C5** → **C8** | observability → evidence generation | evidence carries numbers whose meaning the author cannot state — **and a reader cannot challenge** |
| **C7** → **C2** | diagnostics → production-path coverage | a production-path failure, once reached, cannot be localised |
| **C1 + C2** → **C8** | both → evidence | evidence describes *behaviour proven* while leaving *path shipped* unaddressed |

### 3a. ⚠️ The one relationship that is a TENSION, not a dependency

**C3 (test isolation) and C2 (production-path coverage) pull against each other.**

Isolation was achieved, correctly and deliberately, by substituting doubles for shipped collaborators — the seam tests construct the manager directly to avoid the outbox adapter's `TenantContext::require()`. **That decision was sound and documented at the time.**

**Its cost was invisible: strengthening C3 by doubling weakened C2 on the same path.** Two findings this session came from exactly that seam — the adapter no test had executed, and the container's undecorated resolution.

> **This is not a provider/consumer relationship. Strengthening one does not strengthen the other; it can silently weaken it.** **A capability map that recorded only dependencies would have missed it**, and the two findings would keep recurring in different classes.

## 4. Capability gap analysis

**Only what engineering currently cannot observe, verify or distinguish. No remedies.**

| | Cannot **observe** | Cannot **verify** | Cannot **distinguish** |
|---|---|---|---|
| **C2** | whether a shipped code path is exercised by any test | that the wiring under test is the wiring that runs | a doubled collaborator from the real one, from test output alone |
| **C4** | which tests in the corpus are capable of failing | that a green suite is a *discriminating* suite | coverage from verification |
| **C5** | the origin class of a verification signal | that a signal change reflects a product change | framework · application · infrastructure · business signals |
| **C6** | which framework code removes a handler it did not install | that the mechanism is fully characterised | framework-intrinsic behaviour from configuration-induced behaviour |
| **C1** | — | — | behaviour proven *via doubles* from behaviour proven *as shipped* |

**The recurring shape across C1/C2/C4/C5:** **engineering cannot distinguish a genuine result from its own instrument.** Doubles from collaborators · passing from discriminating · product signal from framework signal. **Four capabilities, one class of blindness.**

## 5. Authorization boundary

**Is this describing a capability?** Yes — §1–§4. **Is this describing an implementation?** No. **Has implementation been authorized?** **No.**

| | |
|---|---|
| Governance domain for any capability work | **Execution Governance** |
| Decision authority | the **ARB** |
| Status | **assessment only.** No recommendation is made about which gap to close, in what order, or by what means |

**Explicitly absent from this document:** implementation plans · technology choices · backlog priorities · roadmap sequencing · any statement that one gap matters more than another.

## 6. Traceability

ENG-012 diagnosis `2026-08-04-eng-012-diagnosis.md` (§9–§12) · Event D evidence `2026-08-04-seam-issuance-transaction-boundary-evidence.md` · WP-4C-1 acceptance evidence · WP-4B delivery record (`fdd09babf` … `2f8087bf2`) · `CoordinatorIssuanceRequestTest` · `AdjudicationExpiredHydratorTest` · `IssuanceContextRoundTripTest` · R-71 · R-81 · R-83 · R-84 · ER-08.

---

# PART II — Capability taxonomy (added 2026-08-04, ARB-required)

**Classification only. No capability is invented, redefined, or removed; none is prioritized.**

## 7. ⚠️ A classification finding before the taxonomy

**The proposed four domains — Verification · Observability · Platform Understanding · Evidence Generation — provide seven slots for eight capabilities. `C3 Test isolation` fits none of them.**

It proves nothing (not Verification) · interprets nothing (not Observability) · is not knowledge (not Platform Understanding) · produces no evidence (not Evidence Generation). **It is a PRECONDITION that other capabilities consume.**

**Two coherent placements exist, and the choice is architecturally consequential:**

| Placement | Consequence for §3a's tension |
|---|---|
| **C3 inside Verification** | the C3↔C2 tension becomes **intra-domain** — a trade-off one domain owner manages alone |
| **C3 as its own domain** *(adopted)* | the tension becomes **cross-domain** — a negotiation with two owners, visible at a boundary |

**Adopted: a fifth domain, `Execution Environment`.** Grounds: **a tension is easier to lose inside a domain than across a boundary**, and this specific tension has already produced two structural findings by being invisible. **Recorded as a classification decision with its reason, not presented as the only option.**

## 8. The taxonomy

```
Engineering Verification (reference architecture)
│
├── Verification
│     ├── C1  Behaviour verification
│     ├── C2  Production-path verification
│     └── C4  Mutation confidence
│
├── Observability
│     ├── C5  Verification observability
│     └── C7  Execution diagnostics
│
├── Platform Understanding
│     └── C6  Framework-integration knowledge
│
├── Execution Environment
│     └── C3  Test isolation
│
└── Evidence Generation
      └── C8  Engineering evidence generation
```

**No capability was added or removed.** `C2` is renamed *production-path **verification*** — it belongs to the Verification domain and *coverage* described a measure rather than a capability.

## 9. Typed relationships

**Every relationship carries exactly one architectural type.**

| Relationship | Type | Note |
|---|---|---|
| C3 → C1 | **Dependency** | isolation provides the conditions behaviour verification relies on |
| C4 → C1 | **Dependency** | without mutation confidence, C1 proves execution rather than verification |
| C6 → C5 | **Dependency** | signals cannot be classified without knowing their origin |
| C5 → C8 | **Dependency** | evidence inherits the interpretability of the signals it cites |
| C7 → C2 | **Dependency** | a production-path failure must be localisable to be actionable |
| C1 + C2 → C8 | **Composition** | evidence is *composed of* both, and is incomplete with either absent |
| C5 + C7 → Observability | **Composition** | the domain is constituted by them, not merely served by them |
| **C3 ↔ C2** | **⚠️ TENSION** | strengthening isolation by substituting doubles weakens production-path verification on the same path |
| C4 ↔ C5 | **Independence** | whether a test *can fail* is unrelated to whether its signal is *interpretable* |
| C6 ↔ C1 | **Independence** | framework knowledge neither strengthens nor weakens behavioural proof |
| C8 ↔ C3 | **Independence** | evidence generation is unaffected by isolation quality |

**Three Independence claims are stated deliberately.** An unstated independence is indistinguishable from an unexamined relationship — and **§3a's tension existed for weeks precisely because C3↔C2 had never been typed at all.**

## 10. Capability boundaries

**Architectural responsibilities only — no classes, files or technologies.**

| | Responsibility | Inputs | Outputs | Consumers | Success criterion |
|---|---|---|---|---|---|
| **C1** | prove code behaves as designed | design intent · executable specification | behavioural verdicts | C8 · engineering decisions | a wrong behaviour cannot pass |
| **C2** | prove the *shipped* path is the exercised path | assembled runtime configuration | path-fidelity verdicts | C8 · release decisions | a mis-assembled runtime cannot pass |
| **C4** | establish that a passing check can fail | the check corpus | discrimination verdicts | C1 | no check is trusted before it has been shown able to fail |
| **C5** | make verification output interpretable | raw verification signals | classified signals · deltas | C8 · engineering decisions | a signal's origin and meaning are stateable without investigation |
| **C7** | localise a failure to its cause | failure occurrences | cause locations | C2 · engineering response | any failure is attributable to a bounded change |
| **C6** | know how the platform's runtime affects ours | platform behaviour · observed runtime effects | characterised mechanisms | C5 | no runtime effect is attributed to the product without cause |
| **C3** | prevent cross-test perturbation | execution environment | isolated execution conditions | C1 · C2 | a result does not depend on execution order |
| **C8** | produce evidence an authority can act on | outputs of C1 · C2 · C5 | acceptance-grade evidence | acceptance · governance authorities | an authority can decide without further engineering analysis |

## 11. Evolution readiness

**Mapped to the required vocabulary. `WEAK` is not in it, so C2 is reclassified on its evidence rather than its rating.**

| | Readiness | Evidence already collected |
|---|---|---|
| **C1** | **Stable** | 8 seam keystones · reconcile branches · database round trips · gate at exit 0 |
| **C3** | **Stable** | per-test database refresh · deliberate avoidance of container resolution, documented at the time |
| **C7** | **Stable** | per-batch cadence localised a displaced docblock and a query-typing fault to their causing batch |
| **C8** | **Stable** | acceptance packages · evidence/inference labelling · disproven hypotheses retained |
| **C2** | **Emerging** | its **first** deliberate coverage was added today; no established practice exists for asserting shipped-path fidelity |
| **C4** | **Emerging** | one deliberate mutation check; applied by choice, not by practice |
| **C5** | **Under Investigation** | ENG-012 diagnosed; the gap is named and unclosed |
| **C6** | **Under Investigation** | mechanism located to app boot; one hypothesis disproven; acting mechanism unestablished |

**No improvement is recommended for any of them.**

## 12. Governance boundary for Part II

**Is this classification?** Yes. **Does it change any capability?** No — one rename, one domain added to accommodate an unclassifiable capability. **Does it recommend work?** No.

| | |
|---|---|
| Governance domain for any capability work | **Execution Governance** |
| Decision authority | the **ARB** |
| Authorization needed | an execution-governance act, for any implementation arising from §11 |
| Status | **stopped at classification** |

**This taxonomy is offered as the reference architecture for engineering verification capabilities**, so that future implementation proposals can be evaluated against a stable model rather than against whichever investigation happened to surface them.
