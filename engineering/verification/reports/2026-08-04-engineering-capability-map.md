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
