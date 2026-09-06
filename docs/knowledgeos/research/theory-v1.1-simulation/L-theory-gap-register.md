# L — Theory Gap Register (§31 L)

| ID | Gap | Class | Evidence | Severity |
|---|---|---|---|---|
| **TG-1** | `DEF-1` factivity and the v1.1 equation `K = Γ(E,Q,C,EC)` are jointly unsatisfiable for any attributing `Γ` | **G3 epistemological** | CE-1; witness; 420/10 000 randomized violations | **CRITICAL** |
| **TG-2** | No evidence-retirement relation: `Revise` exists, `Supersede/Retract/Expire` do not | **G2 semantic** | CE-3 | **HIGH** |
| **TG-3** | `Adequate` (DEF-20) and `Zero` (DEF-22) are extensionally identical | **G1 mathematical** | CIRC-3 | MEDIUM |
| **TG-4** | `≡_sem` is defined via behaviour, behaviour via a chosen projection | **G1 mathematical** | CIRC-5 | **HIGH — blocks minimality** |
| **TG-5** | `E` denotes both Evidence (DEF-7) and EpistemicState (v1.1) — in a correction whose purpose is to separate them | **G2 semantic** | type audit, 10 notation collisions | MEDIUM |
| **TG-6** | `Γ` attributes a determination with **empty provenance** (AD-18 was attributed) | **G2 semantic** | CE-2 | MEDIUM |
| **TG-7** | Requirement kinds and the unknown taxonomy were chosen by the experimenter; neither is proved exhaustive | **G1 mathematical** | SMUG-3 | MEDIUM |
| **TG-8** | Is attribution epistemics or governance? `Γ` is parameterized by the epistemic contract | **G5 DDD** | policy sweep | MEDIUM |
| **TG-9** | Uncertainty is carried as a scalar weight; no structured uncertainty object `U_t` | **G4 statistical** | model design | MEDIUM |
| **TG-10** | Nothing here is evidence about real knowledge; the world is synthetic | **G7 empirical** | by construction | inherent |
| **TG-11** | Scenarios C/F/G construct observation tokens rather than acquiring them | **G6 computational** | SMUG-1 | LOW (instrumentation) |
| **TG-12** | No governance/authorization *model* — authorization is a lookup table | **G9 governance** | P16 | LOW here, HIGH later |
| **TG-13** | The kernel shape `(𝒫, ℛ, δ, ℐ, 𝒰)` proposed by the prior lane was not tested | **G8 architectural** | out of scope | MEDIUM |

## The three repairs available for TG-1 — presented, not chosen

**R1 — Rename the object.** `K_t` is *attributed knowledge* / warranted determination, **not** factive
knowledge. `DEF-1`'s factivity then applies to a different, externally-evaluated predicate. Cheapest,
and it matches what the simulator actually computes.

**R2 — Make factivity external.** Keep `DEF-1`, and accept that factivity is a **success condition an
agent cannot verify from inside its own epistemic state**. `Γ` produces *claims to knowledge*; whether
they are knowledge is settled by the world, not by the kernel. Philosophically the most honest;
architecturally it means **no KnowledgeOS component may ever assert `Knows`**.

**R3 — Make `Γ` partial.** Attribute only where a verification channel supplies truth externally.
Preserves both clauses, at the cost that `K_t` is empty in every ordinary case — the policy sweep
already shows the degenerate endpoint (`policy = none` is the only factive one).

`[OPEN]` **The choice is a theory decision, not an experimental one.** This lane does not make it.
