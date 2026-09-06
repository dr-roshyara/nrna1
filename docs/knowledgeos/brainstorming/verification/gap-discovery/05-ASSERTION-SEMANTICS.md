# 05 — Assertion Semantics

**Mandate §10.** Executed evidence: `exec/exp_assertion.py`.

---

## 1. The six words, separated

The mandate forbids leaving `Assertion / Proposition / Claim / Observation / Evidence / Assessment`
as informal synonyms. The corpus *does* separate five of the six; this session verified each
separation at source and found one word with no definition at all.

| Object | Type | Identity | Content | Epistemic semantics | Temporal | Evidence rel. | Context rel. | Authority rel. |
|---|---|---|---|---|---|---|---|---|
| **Observation** | primitive | `(source, method, time)` register | raw registered datum | **none** — an observation is not true or false, it *occurred* | occurrence time | is the *left* argument of `Evidence` | none intrinsic | none |
| **Proposition** | `P = (E, D, V)` | structural equality of the triple | the claimable content | truth-apt but **carries no truth value** | none — `P` is timeless | is the *right* argument of `Evidence` | none — context is on `A`, not `P` | none |
| **Assertion** | `A = (id, P, e, c, t, Π)` | `id` (or one of four candidate equalities — see `04` §4) | a proposition **placed in a state** | carries `σ`; **admission ≠ truth** (Closure-03) | `t` (single field) | `e` ⊆ evidence ids | `c` | via `Π` and governance |
| **Evidence** | **relation** `Evidence(O,P,C,R)` | — | not an object at all | relevance-conditioned support | inherits from `O` | *is* the relation | `C` is a parameter | `R` is a rule, hence policy-governed |
| **Assessment** | function `(Evidence*, Policy) → σ` | — | — | produces `σ` | — | consumes | — | policy-governed |
| **Claim** | **undefined** | — | — | — | — | — | — | — |

### CS-1 (`UNRESOLVED`, MEDIUM) — "Claim" is used and never defined

Step 253 §253.14 has a section titled "Claim". This session read it: it discusses claims and does
not give a type, an identity criterion or a relation to `Assertion`. The word recurs throughout the
corpus (`claim registry`, `authority claims`, `claim provenance`). Under the mandate's rule this must
be recorded: **it is an informal synonym, and the corpus's own §253.14 does not remove it.**

The natural repair — `Claim ≡ Assertion` — is available and costs nothing. This session recommends
retiring the word rather than defining it (`ES-005.4`-style: extend the existing term, do not create
a second).

---

## 2. The separations that ARE established, and are good

Three of these are genuine achievements and should be preserved by any successor theory.

**(1) `P ≠ A`** (Step 262). A proposition is timeless, contextless, sourceless content; an assertion
is that content *placed* by someone, somewhere, at some time, with some backing. Consequence: two
assertions of the same proposition are two objects. `CORPUS ESTABLISHES`.

**(2) Admission is not a truth function** (Closure-03). An assertion enters `K` because a governed
admission rule let it in, not because it is true. Consequence: `K` can contain refuted assertions,
and must, if it is to explain how it came to be wrong. `CORPUS ESTABLISHES` — and this is the single
most important semantic decision in the whole corpus.

**(3) Evidence is a relation, not a substance** (Closure-04). `Evidence(O,P₁) ≠ Evidence(O,P₂)`,
argued with the `3.69 / secure / secure-for-five-years` counterexample. `CORPUS ESTABLISHES`.

---

## 3. EXECUTED: what the assertion type can and cannot say

`exec/exp_assertion.py` EXP-19 takes ten propositions **the corpus itself requires** and attempts to
express each in the terminal type.

```
EXPRESSIBLE            2      Nexus.version=3.69 ; the same in a named context (via A.c)
LOSSY                  2      validity interval (Step 187.13) ; units (Step 264.14)
INEXPRESSIBLE          3      negation ; conditional ; quantification
NOT-A-PROPOSITION      1      "a1 contradicts a2" -- lives in R, not P
EXPRESSIBLE-BUT-EMPTY  1      "Nexus is ready to migrate" -- holds the conclusion,
                              cannot hold the rule that produces it
AMBIGUOUS              1      "Nexus.version is UNKNOWN" -- value or epistemic marker?
```

Four of these deserve individual statement.

### CS-2 (`EXECUTED`, HIGH) — the temporal field is single, the theory needs two

`A = (id, P, e, c, t, Π)` has **one** `t`. Step 185 and Step 025w both establish
`T_valid ≠ T_known` (bitemporality). Step 187 §187.13's own worked example — *"Alice is Architecture
Board chair from 2026-01-01 to 2026-12-31"* — needs a **validity interval**, which the type cannot
carry. Either `t` is assertion time (and validity is unrepresentable) or it is validity time (and
`replay` loses its ordering key). The corpus establishes bitemporality and then defines a
unitemporal assertion.

### CS-3 (`EXECUTED`, HIGH) — two mechanisms for propositional content, with no stated relation

`"a1 contradicts a2"` is propositional content. In this type system it is **not** a proposition; it
is an `ℛ`-edge. So the theory has two carriers of content — `P` for entity-dimension-value facts, `ℛ`
for inter-assertion facts — and never states how they relate. Concretely: **can an `ℛ`-edge be
asserted, evidenced, statused, or contested?** `ℛ ⊆ 𝒜 × 𝒜 × RelType` says no: edges are bare
triples with no `id`, no `e`, no `σ`, no `Π`.

This is not academic. `EXP-2` in `exec/exp_congruence.py` showed `ℛ_der` is the component that makes
the state sufficient — so the theory's load-bearing component is precisely the one with no
provenance, no evidence and no status.

### CS-4 (`EXECUTED`, **CRITICAL**) — the founding problem is inexpressible in the terminal type

The corpus opened, on 2026-08-25 between 23:57 and 00:03, on the **conditional determination
problem**:

> *"What substrate must be preserved so that multiple determination regimes can independently
> reconstruct the same phenomenon?"* (`235804`)
> *"Determination is the missing mathematical object."* (`235855`)

It closed on `P = (E,D,V)` and `K = (𝒜,ℛ)`.

Executed check: the conditional `IF version ≥ 3.60 THEN patch-current` is **inexpressible** in
`P = (E,D,V)`. The rule that would derive it lives in `Policy`, which Step 266 §266.22 places in
class C (no decision procedure). And a direct grep over the six terminal steps (262, 263, 264, 265,
266, 267):

```
Determination occurrences:  0  0  0  0  0  0
```

**The object the corpus named as *the missing mathematical object* on day 1 is absent from every
terminal artefact on day 6, and the terminal proposition type cannot express the conditional
structure it was introduced to carry.**

This is the single most consequential finding in this document. It is not that the theory is wrong.
It is that **the theory closed around the part of the problem it could formalize**, and the reader of
the terminal steps cannot tell, because no terminal step records that the founding question was
dropped.

### CS-5 (`EXECUTED`, HIGH) — `Unknown` has no home

`(Nexus, version, UNKNOWN)` puts an epistemic marker in the **value space** — exactly the category
error Step 264 warns against (`264.11` the "Nexus Version problem", `264.13`). The alternative —
absence of an assertion — is indistinguishable from *never having asked*, which destroys the
corpus's own Zero concept (`025d`: the whole point of Zero is that *asked-and-unanswered* differs
from *never-asked*).

So `Unknown` is expressible neither as a value nor as an absence. It must be a `σ`, and `σ` is not in
`A` in the terminal type (Step 267's `A = (id,P,e,c,t,Π)` has no `σ` field; `Σ` is listed separately
as "correspondence open"). **`Unknown` currently has nowhere to live.**

---

## 4. Three examples and three counterexamples, as the mandate requires

### Examples (the type works)

1. `A₁ = (a1, (Nexus, version, 3.69), {e1}, ops, 10, ⟨vendor/api@10⟩)` — clean.
2. `A₂ = (a2, (Nexus, version, 3.70), {e2}, ops, 12, ⟨forum/scrape@12⟩)` plus
   `(a2, a1, contradicts) ∈ ℛ` — conflict representable.
3. The EKP: 40 real assertions, 59 real typed relations, all resolving (`exec/OUT-ekp-bridge.txt`).

### Counterexamples (the type fails)

1. **Same proposition, two contexts, one identity question.** `A ∈ K` returns different answers under
   four corpus-sourced equalities on 2 of 3 probes (`exec/OUT-identity.txt` EXP-8). The type does not
   determine its own membership predicate.
2. **A relation that needs backing.** "`a2` contradicts `a1`" is the assertion an engineer would most
   want to see evidenced and attributed. It cannot be: `ℛ` edges carry no `e`, no `Π`, no `σ` (CS-3).
3. **A rule.** "Assertions from `authority: generated` may not be `authoritative` without human
   review" is a real, running EKP rule (Knowledge-Constitution §AI collaboration principles). It is a
   proposition about assertions with a quantifier and a modal. Inexpressible (CS-4).

---

## 5. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **CS-1** | `Claim` is used throughout and never typed; Step 253 §253.14 discusses it without defining it. | `UNRESOLVED` | MEDIUM |
| **CS-2** | `A` carries one `t` while the corpus establishes `T_valid ≠ T_known`; Step 187's own example needs a validity interval the type cannot hold. | `EXECUTED` | HIGH |
| **CS-3** | Content is carried by two mechanisms (`P` and `ℛ`) with no stated relation; `ℛ`-edges — the component that makes the state sufficient — have no id, evidence, provenance or status. | `EXECUTED` | HIGH |
| **CS-4** | The founding problem (conditional determination) is inexpressible in the terminal proposition type, and `Determination` occurs **0 times** in all six terminal steps. | `EXECUTED` | **CRITICAL** |
| **CS-5** | `Unknown` fits neither the value space (category error per Step 264) nor absence (destroys Zero), and `σ` is not a field of `A`. | `EXECUTED` | HIGH |
| **CS-6** | Negation and quantification are inexpressible; units and validity intervals are lossy. Of 10 corpus-required propositions, 2 are cleanly expressible. | `EXECUTED` | HIGH |
| **CS-7** | *Positive:* `P ≠ A`, *admission ≠ truth*, and *evidence-as-relation* are three genuine, well-argued achievements that any successor theory should preserve. | `CORPUS ESTABLISHES` | — |

---

**Next:** `06-SIGMA-GAP-ANALYSIS.md`.
