# MD-072 §04 — Updated Definition Evolution Registry (delta only)

MD-068's own Definition Evolution Registry (`MD-068-.../01_definition-evolution-registry.md`) is not
rewritten — every version it already lists stands unchanged. This is a **delta**: new definitions
found by the census, each versioned against the *lane-qualified* object it belongs to (per §01), never
merged into the F4 chain's own version sequence.

| Object | New version | Source | Body | Status |
|---|---|---|---|---|
| `K_t [kernel-lane]` | v1–v15+ | `kernel/`, Aug 24–25 | see §01 table | proliferating, never frozen within `kernel/`'s own evidence |
| `K_t [phase_measure_theory-lane]` | v1–v25+ | `phase_measure_theory/`, Aug 25–30 | see §01 table | proliferating; corpus's own `step_239` self-audit confirms "~25 competing right-hand sides" |
| `K_t [phase_measure_theory-lane]` | final | `step-292/`, Sep 2 | renamed `A_t` under governance decision "R1" | SUPERSEDED (within its own lane only) |
| `Zero [kernel-lane]` | v1–v4 | `kernel/`, Aug 24 | see §01 table | proliferating |
| `Zero [phase_measure_theory-lane]` | v1–v4 | `phase_measure_theory/`, Aug 27–28 | see §01 table | proliferating; self-flagged drift (`CON-01`) |
| `Δ [phase_measure_theory-lane]` | v1–v6 | `phase_measure_theory/`, Aug 27–30 | see §01 table | proliferating; "Discrepancy" naming (Aug 31) |
| `Req(r)`/`Witness(r) [phase_measure_theory-lane]` | v1 | `step_186`, Aug 29 02:12 | `TransitionAllowed(r) ⟺ Req(r)⊆Witness(r)` | single instance, not repeated elsewhere in either lane |
| `EC [phase_measure_theory-lane]` | v1 | `combine-prompt-4`, Aug 28 15:01 | `EC=η(G,IdealState)` | restated `combine-prompt-6`, `remaining-todos`; not frozen |
| `EC_G [phase_measure_theory-lane]` | v1 | `remaining-todos`/`combine-prompt-6`, Aug 28 23:xx | `EC_G=(R_G,Γ_G)` | single restated instance |
| `Determination`/`Decision [phase_measure_theory-lane]` | v1–v8+ | `phase_measure_theory/`, throughout | 8+ distinct tuple arities, see §01 | proliferating, never reconciled |
| `AuthReq(r,a)` | v1 | `step_187`, Aug 29 08:16 | `Allowed(a,r)=Cap(a,r)∧Perm(a,r)∧AuthReq(r,a)` | NEW, built on `step_186`'s `Req(r)` |

## No revision to any F4-chain object's own version history

None of the above touches `EC_t` v1–v4, `Req` v1–v4, `Sat` v1–v7, `Δ_t` v1–v4, `Zero` v1–v3, `K_t`
v1–v5, `Determination` v1–v4, or `Decision` (MD-068's own registry) — every one of those version rows
stands exactly as MD-068 recorded it. The census found no evidence anywhere in the 1,200-file
population that changes, extends, or contradicts any F4-chain version — only lane-local parallels.
