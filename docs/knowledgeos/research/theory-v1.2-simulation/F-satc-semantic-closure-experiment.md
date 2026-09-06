# F — `Sat_c` Semantic Closure Experiment · `KR-SIM-2026-09-02-D`

**Commissioned by** the edited `…093546_satisfaction-by-requirement-class-three-valued-sat-predicate.md`
(2 339 lines), which corrected my proposed next step:

> Not *"Zero Closure Decision"* first, but **`Sat_c` Semantic Closure → then Zero Closure.**
> `define Sat_c ≠ implement Sat_c`. Use the **four deterministic cases already found**, not another
> 10 000 random trials.

**Status** `[EXP]` · **Sat_c Formal Candidate Specification v0.1** — not canonical, not implemented.

---

# PHASE A — Formalize the eight `Sat_c` (specification, not implementation)

Common skeleton, per the review:

```
Sat_c(K,r;Γ) = ⊤  if  K,Γ ⊨ P_c(r)        Level 1 : the value
             = ⊥  if  K,Γ ⊨ ¬P_c(r)
             = U  otherwise                Level 2 : Just_c(K,r;Γ) — WHY
```

All twelve required attributes are specified per class in `results/satc_phaseA_spec.json`.
Two results fall out of merely *writing* the specification:

## A-1 — Only 3 of 8 classes are executable now `[EXP]`

| executable | blocked | the blocker |
|---|---|---|
| `content` | `status` | **`⪰` is not defined by the theory.** Any implementation invents it |
| `evidence` | `consistency` | `Contr` undefined; whether contradiction needs a 4th value is OPEN |
| `provenance` | `governance` | **no evaluator exists** — this is why every governance requirement read `U` in E1 |
| | `temporal` | no temporal semantics defined — the second source of `U` in E1 |
| | `operational` | `δ` is Step 290 and open — the third source of `U` in E1 |

> This **explains** the v1.2 result rather than repeating it. The review's diagnosis was right:
> `governance = U` does not mean governance satisfaction is unknowable, it means
> **`Sat_gov` has no supplied evaluator.** The same for temporal and operational.

## A-2 — **No class in the family requires factivity** `[EXP]` `[NEG]`

```
factivity_requirement:  content NONE · evidence NONE · provenance NONE · status NONE
                        consistency NONE · governance NONE · temporal NONE · operational NONE
```

> **A knowledge state can satisfy all eight classes and still be false.** CE-1 is therefore not an
> accident of `Γ`: **the satisfaction family has no place to attach truth.** This is a stronger
> statement than the v1.1 witness, and it is visible from the specification alone.

> **This does NOT establish that KnowledgeOS should be non-factive.** It establishes only
> `Sat(K,r)=⊤ ⇏ Truth(K)` **under the current candidate semantics**, and locates the reason: the
> family has no truth-bearing relation. **Factivity remains `[OPEN]`** — adding a `Truth` class
> prematurely would be the wrong repair.

The nearest thing to a truth hook is `Sat_evidence`, and its own note records the mechanism:
`E_min` can be met by a false report from a policy-trusted source.

---

# PHASE B — Adversarial semantic test on the four deterministic cases

`B1` factivity · `B2` revision/retraction · `B3` provenance & source removal ·
`B4` governance/temporal/operational `U`. No new random trials.

## PB-2 — `Sat_content` is incoherent under contradiction `[NEG]`

`Sat_content` returns `⊤` if `p ∈ Content(K)` and `⊥` if `¬p ∈ Content(K)`. **Both can hold.**
The codomain `{⊤,⊥,U}` has no value for it, so `Sat_content` **is not a function** on such states.

Either the codomain needs a fourth value `C`, or `Sat_content` must delegate to `Sat_consistency`
first. Note this reaches the review's OPEN 4-valued question **from the content class**, not the
consistency class where it was expected.

## PB-3 — the `Just` hypothesis fails, narrowly and usefully `[NEG]`

Hypothesis: every `U` carries a reason from its class's declared vocabulary. **32 cells tested,
2 fail** — both the same defect:

| case | class | reason produced | declared vocabulary for that class |
|---|---|---|---|
| B2-revision | `content` | `UNDERDETERMINED` | `[UNOBSERVED, UNINTERPRETED]` |
| B3-no-source | `content` | `UNDERDETERMINED` | `[UNOBSERVED, UNINTERPRETED]` |

Content can be undetermined **because rivals are live**, which the specification did not anticipate.
The repair is one word; the finding is the process one: **writing the per-class reason vocabulary
before testing it produced an incomplete vocabulary, and the adversarial cases found it.**

> **`[OPEN]` Adding the word closes the two failing cells and proves nothing about the rest.**
> Reason-vocabulary **exhaustiveness remains OPEN** — see `H` §1, where the only candidate repair
> that could be provably exhaustive is one that *derives* the vocabulary from the `Sat` skeleton
> rather than enumerating reasons per class.

Five distinct reasons were exercised: `UNOBSERVED`, `UNDERDETERMINED`, `INSUFFICIENT_PROVENANCE`,
`NO_EVALUATOR`, `NO_TEMPORAL_SEMANTICS`, `DELTA_UNDEFINED`.

## PB-4 — **blocked-class contagion**: the decisive result `[EXP]` `[NEG]`

Under Kleene conjunction, with 5 of 8 classes blocked and the executable ones all returning `⊤`:

| composite arity | permanently `U` | rate |
|---|---|---|
| 1 | 5 / 8 | 0.625 |
| 2 | 25 / 28 | 0.893 |
| 3 | 55 / 56 | **0.982** |
| **4–8** | **all** | **1.000** |
| all eight conjoined | | **`U`** |

> **Every composite requirement of arity ≥ 4 is permanently `U`, no matter how well the executable
> classes perform.** Any realistic requirement touches several classes, so the satisfaction family
> is **effectively inert** until the five blocked classes are unblocked. This is not a defect of
> Kleene conjunction; it is the correct propagation of "we have not defined this yet".

## PB-5 — the family is not provably well-founded `[NEG]`

`Sat_op(K,r) = Sat_κ(δ(K,o))` is recursive. Well-founded **iff** `κ` is drawn from the seven
non-operational classes. **The theory does not restrict `κ`**, so an operational requirement whose
postcondition is itself operational regresses with no base case. `TECHNICALLY OPEN`.

## PB-1 / PB-6

`PB-1`: for `content`, `⊥` is an *explicit negation* rather than the complement of `⊤`, which is what
makes `PB-2` possible. `PB-6`: reported as A-2 above.

---

# PHASE C — Zero Closure Decision, run only now

With `U`'s meanings established, partition the reasons by **who can act**:

| bucket | reasons | meaning |
|---|---|---|
| **agent-remediable** | `UNOBSERVED`, `UNINTERPRETED`, `UNDERDETERMINED`, `INSUFFICIENT_PROVENANCE` | more epistemic work would help |
| **theory-blocked** | `NO_EVALUATOR`, `NO_ORDERING`, `NO_TEMPORAL_SEMANTICS`, `DELTA_UNDEFINED` | no agent work helps — **the theory is missing** |
| **world-blocked** | `UNOBSERVABLE` | nothing helps |

## C-1 — a **fourth** Zero reading, which the two-level `Sat` makes possible `[PROP]`

```
Zero_reasoned  ⟺  no ⊥  ∧  no U that the AGENT could still act on
```

*"The agent has done everything it can; what remains belongs to the theory or the world."*

**It is genuinely distinct**, demonstrated by an explicit separating case — no `⊥` anywhere, and
`content = U` because nobody has looked yet:

| | strict | **reasoned** | weak | Kleene |
|---|---|---|---|---|
| separating case | false | **false** | **true** | `U` |

`weak` closes; `reasoned` does not, because the agent has an unfinished action.

## C-2 — the four readings form a chain — **now DERIVED, not merely observed** `[DERIVED]`

```
Zero_strict  ⟹  Zero_reasoned  ⟹  Zero_weak
```

> **Status upgraded 2026-09-02** (see `H-repair-phase-and-reordered-rerun.md` §2). This was originally
> recorded as observed over the tested cases. It has since been **derived from the definitions**:
> `reasoned ⇒ weak` is immediate (weak is reasoned's first conjunct); `strict ⇒ reasoned` follows
> because `∀r.Sat(r)=⊤` makes weak hold and the second conjunct vacuous. Exhaustive check over
> **1 620 000** assignments — including **every** choice of which buckets close — found **0
> counterexamples**, so the chain does not depend on the reason partition.

Originally verified on all four adversarial cases plus the separating case. `Zero_kleene` is the three-valued
companion, not a member of the chain.

| case | strict | reasoned | weak | Kleene | `U` buckets |
|---|---|---|---|---|---|
| B1 factivity | false | **true** | true | `U` | theory-blocked ×3 |
| B2 revision | false | false | false | `⊥` | agent-remediable ×3, theory-blocked ×3 |
| B3 no-source | false | false | false | `⊥` | agent-remediable ×4, theory-blocked ×3 |
| B4 open classes | false | **true** | true | `U` | theory-blocked ×3 |

`Zero_strict` is **false in every case**, confirming the v1.2 finding — and Phase A now says *why*:
the three theory-blocked classes are present in every case, and always will be until they are defined.

## C-3 — **Zero closure holds on a state whose knowledge is false** `[EXP]` `[NEG]`

Case B1 is the factivity counterexample: the system attributed a falsehood. It is nevertheless

```
Zero_weak = true      Zero_reasoned = true
```

Because **no `Sat_c` requires factivity** (A-2). So:

> **`Zero` and truth are orthogonal.** A system can be epistemically closed — every applicable
> requirement satisfied, nothing left for the agent to do — and simply wrong.

This is the sharpest statement of the CE-1 obstruction yet obtained, and it arrives from the
satisfaction layer rather than from `Γ`.

---

# Consequences

| Question | Answer |
|---|---|
| Can the eight `Sat_c` be defined without implementing them? | **Yes** — done, and the definition alone produced A-1 and A-2 |
| Do they have coherent interpretations? | **5 of 8 have no evaluator; `content` is incoherent under contradiction; the family is not well-founded.** So: not yet |
| Should `Zero` be chosen now? | **A fourth reading now exists and the four form a chain.** Choosing remains `NORMATIVE`, but the decision is now *informed* rather than premature |
| Is `Sat` closeable? | **No.** It cannot be closed while 5 classes lack evaluators, and PB-4 shows the blockage is contagious |

## Revised status

| Element | Status |
|---|---|
| `Sat : 𝒦 × ℛ → {⊤,⊥,U}` as an interface | **`[PROP]`** — unchanged, and now for *specified* reasons |
| Eight-class decomposition | **`[PROP]`, useful** — it localized every blocker |
| Two-level `Sat` / `Just` | **`[PROP]`, productive** — it generated `Zero_reasoned` |
| `Just(U)` reason vocabulary | **`[PROP]`, incomplete** — 2 of 32 cells failed (PB-3) |
| Kleene conjunction across classes | **`[PROP]`, and PB-4 shows its cost** |
| Four-valued codomain | **OPEN** — and PB-2 raises it from the content class |
| Well-foundedness of the family | **TECHNICALLY OPEN** (PB-5) |
| `Zero_reasoned` | **`[PROP]` — new, distinct, chain-ordered** |
| Factivity | **OPEN** — and A-2 shows the family has no hook for it |

## Next experiment

> **Supply the three missing evaluators — governance, temporal, operational — to the test harness,
> and re-run Phases B and C.**

PB-4 is the argument: while those three are blocked, **every composite of arity ≥ 4 is `U`** and no
Zero reading except `weak`/`reasoned` can ever fire. Supplying even *candidate* evaluators would
show whether `Zero_strict` is reachable in principle or unreachable by construction.

**Do not** implement the eight `Sat_c` yet. PB-2, PB-3 and PB-5 are specification defects, and
implementing a defective specification would hide them behind code — which is the failure mode this
whole sequence has been built to avoid.
