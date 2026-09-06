# 10 — The Equality Closure Contract (mandate §17)

> **§17: *"Never use 'closed' globally without specifying which sense."*** Three senses, kept separate.

## The contract — what would have to become true

| # | Requirement | TECHNICALLY CLOSED needs | NORMATIVELY RATIFIED needs | IMPLEMENTATION-READY needs | now |
|---|---|---|---|---|---|
| 1 | **relation definitions** | each of the 9 relations has exactly one definition | `N-1` adjudicated; a register owner (`N-20`) | — | 🔴 **two relations share one definition** |
| 2 | **decision procedures** | a procedure per relation | which procedure is authoritative | deterministic implementation | 🔴 2 of 9, both qualified |
| 3 | **identity** | 11 kinds defined; `K_t` has a rule | `N-8`·`N-9`·`N-14`·`N-15` | collision model | 🔴 2 of 11 |
| 4 | **observation semantics** | `𝒪_K` closed | `N-3` | observation functions implemented | 🔴 |
| 5 | **provenance semantics** | `λ` a computable predicate | `N-2`·`N-6` | — | 🔴 principle only |
| 6 | **operation semantics** | `𝒪` closed | `N-4` | — | 🔴 |
| 7 | **transformation semantics** | `𝒯` closed | `N-4` | — | 🔴 |
| 8 | **congruence** | `258-A` proven for all mandatory `T` | ⛔ **must NOT be ratified — it is derivable** | — | 🔴 and `=` **disproven** |
| 9 | **interaction with `δ`** | commit case specified | `N-4` | `Γ` given a carrier | 🔴 executed `K₁ is K₀` |
| 10 | **canonicalization** | evidence-driven rule fixed | `N-13` | stable serialization | 🔴 |
| 11 | **governance authority** | — | a named owner per relation | — | 🔴 **none** |
| 12 | **implementation determinism** | — | — | same inputs → same verdict | 🔴 |
| 13 | **testability** | a test that can fail | — | **and a degeneracy check** (`08`) | ⚠️ **partial** — 4 negative results executed |

$$\boxed{\textbf{0 of 13 satisfied in any of the three senses.}}$$

## The three senses, defined for this programme

| Sense | Definition | Status |
|---|---|---|
| **TECHNICALLY CLOSED** | every relation has one definition and a decision procedure; congruence proven; the quotient constructed | 🔴 **NOT** — and `≡` **cannot** be, per `012 §35` |
| **NORMATIVELY RATIFIED** | an authority has fixed every `N-` record that is genuinely normative, and no derivable item was ratified | 🔴 **NOT** — 21 open, 0 ratified |
| **IMPLEMENTATION-READY** | two independent engineers produce the same verdicts | 🔴 **NOT** — they could not agree how many relations exist |

⚠️ **These are not stages on one line.** `≡` can be **normatively ratified without ever being
technically closed** (declare it; `012 §35` forbids total decidability). And a relation can be
**technically closed and never ratified**. **Never report one as the other.**

## STATUS
**ESTABLISHED** the 13-row contract; 0 satisfied; the three senses are independent, not sequential ·
**BOUNDED** the contract is finite and complete as far as current evidence reaches ·
**G1** requirement 2 for `≡` · **NORMATIVE** requirements 1,3–7,10,11 · **DEFERRED** requirements 12–13
