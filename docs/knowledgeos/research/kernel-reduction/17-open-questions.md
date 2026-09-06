# 17 — Open Questions `[OPEN]`

Ordered by how much they block further kernel work.

## Blocking

| # | Question | Why it blocks | Cheapest next test |
|---|---|---|---|
| **Q-1** | Is `closure-judgment` primitive, or is adequacy a difference-decision over the norm delta? | `Determine`'s status flips on it (V3). Determines whether the kernel has 13 or 12 irreducible powers. | Decide whether the epistemic contract `EC` is reducible to a requirement set. If yes, V3 wins. |
| **Q-2** | Are `meaning-assignment` and `symbolic-encoding` one atom or two? | `Interpret` and `Represent` both flip under V1, and the *atom* irreducibility claim (§14 shape hypothesis) flips with them. | Find one KnowledgeOS operation that must act on semantic content *before* encoding, or on an encoding whose meaning is not yet fixed. One example settles it. |
| **Q-3** | Does `Select` belong to the epistemic kernel at all, or to a neighbouring bounded context? | It is the only operator whose output no epistemic capability consumes, and whose input (`Objective`) the kernel does not own. | A strategic-DDD context-map question, **not** a minimality experiment. Wrong instrument used so far. |
| **Q-4** | Is `Revise` a domain operation or an infrastructure mechanism? | If mechanism, the kernel emits *proposals* and committing belongs elsewhere — a different kernel shape. | Test whether the corpus's seven revision verbs (Update/Revise/Supersede/Correct/Invalidate/Retract/Expire) are epistemically distinguishable. The current simulator cannot see them. |

## Substantive but non-blocking

| # | Question |
|---|---|
| Q-5 | Is `Relate` derivable as `Infer` over a relation-asserting `Rule`? (falsifier F-4, untested) |
| Q-6 | Is `Challenge` derivable as `Hypothesize` a competitor + `Discriminate` in its favour? (F-9, untested) |
| Q-7 | Is `Validate` derivable as `Discriminate` over `{claim, defeaters}` given evidence? (F-10; V6 is halfway there) |
| Q-8 | Does KnowledgeOS actually require the DEDUCTIVE warrant kind, or does evidential warrant suffice? If the latter, `Infer` collapses into `Validate ∘ Hypothesize` (F-7). |
| Q-9 | Is the `𝒫`/`ℛ`/`δ` shape (§14) better than an operator set — i.e. does any required capability depend on the *packaging*? |
| Q-10 | Should `Qualify` be added to the candidate list as a first-class operator, given it is the corpus's `G1` and the cause of the baseline failure? |

## Method-level

| # | Question |
|---|---|
| Q-11 | How can provenance (C18/P3) be made testable? The current instrument guarantees it structurally and therefore learns nothing about it. |
| Q-12 | How can *degree* be modelled without reintroducing a narrative simulator, in which semantic smuggling becomes invisible again? |
| Q-13 | Governance and Authorization were assumed as ambient constraints and never tested. What experiment would test the assumption rather than encode it? |
| Q-14 | The capability set C1–C25 was largely inherited from the prompt. What would an *independently derived* capability set look like, and would the results survive it? (This is the deepest robustness question and V1–V7 only scratch it.) |

## The question this experiment says is the *right* next one

`[PROP]` Not *"which operator do we delete?"* but:

> **What is the correct mathematical type and semantic granularity of an epistemic primitive?**

The evidence for that reframing: all 14 atoms irreducible but only 12 of 14 operators; two minimal
kernels of identical cardinality differing only in packaging; and three operators
(`Interpret`/`Represent`, `Determine`, `Select`) whose status flips under a single defensible change
of granularity. **The instability is in the granularity, not in the count.**
