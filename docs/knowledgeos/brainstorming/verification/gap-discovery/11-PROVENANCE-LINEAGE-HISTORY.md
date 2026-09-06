# 11 — Provenance · Lineage · History · State

**Mandate §16.** All six required tests executed: `exec/exp_provenance.py`
(transcript: `exec/OUT-provenance.txt`).

---

## 1. The four words, distinguished

| Word | Definition used | Where it lives | Computable from `K`? |
|---|---|---|---|
| **Provenance** `Π` | `(source, method, time)` — where a *claim* came from | inside the assertion (Step 265) | **yes** |
| **Lineage** | ancestry of an assertion = reachability in `ℛ_der ∪ ℛ_ref` | derived from `ℛ` | **yes**, `O(n+m)` |
| **History** `H` | `(K₀, T₁ … T_t)` — the operation sequence | outside `K` (Step 247) | **no** |
| **State** `K` | `(𝒜, ℛ)` | — | — |

`Provenance ≠ Lineage` is established and matters: `Π(c) = ⟨internal/rule@2⟩` while
`lineage(c) = [a0, b]` and `a0`'s origin is `vendor`. **The two give different answers about
"where did this come from", and the corpus uses the word *provenance* for both in different steps.**

---

## 2. The six required tests

### Test 1 — imported assertion at `t = 0`

```
K0 = a0:Nexus.version=3.69 <vendor/import@0>
provenance from K alone : <vendor/import@0>   ANSWERABLE
lineage   from K alone  : []                  empty — correctly, no ancestors
```

**PL-1 (`EXECUTED`, confirming the corpus).** Step 265 §265.2's base-case argument is **correct and
decisive**: at `t = 0` lineage is empty, so any model defining provenance as *the lineage of the
history* (`L = History(T)`) has nothing to define it with. **Provenance must be carried, not
derived.** This is the strongest single argument in Step 265 and it survives execution.

### Test 2 — one-step transformation

```
restatus(a0, Accepted):  Pi survives unchanged
who performed the restatus, and when?   NOT ANSWERABLE FROM K
```

**PL-2 (`EXECUTED`, HIGH).** `Π` records the origin of the **claim**, not of the **state change**.
The theory has assertion provenance and **no transition provenance whatsoever**. Step 265's own
placement matrix has no row for it. For an audit trail this is the more important of the two.

### Test 3 — multi-step derivation

```
c derives-from b derives-from a0
lineage(c) = ['a0','b']    ANSWERABLE
Pi(c)      = <internal/rule@2>   -- says 'internal', not 'ultimately vendor'
```

**PL-3 (`EXECUTED`).** Lineage is genuinely computable — reverse reachability, `O(n+m)`. This is the
**one relation in the whole theory** that is unambiguously decidable, total, and cheap. It is also
the one the corpus's implementation evidence points at (`GovernanceLineageGraph`). That is not an
accident: it is computable *because* it depends on nothing in Step 266's class C.

### Test 4 — two independent sources

```
K4 holds ONE proposition asserted TWICE (vendor, scanner)
distinct propositions: 1     distinct assertions: 2
```

**PL-4 (`EXECUTED`).** `P ≠ A` pays off: independent corroboration is naturally representable.
**But independence itself is not.** The theory has `ℛ_der` (dependence) and no independence relation,
and *absence of a derives-edge is not evidence of independence* — it may simply be unrecorded.
This is the structural counterpart of `07` MT-4: `IndependenceFactor` computes a number for a
relation the model cannot represent.

### Test 5 — merge

```
merge preserves Pi per assertion:                  YES
when did the merge happen / who authorized it?     NO RECORD
```

**PL-5 (`EXECUTED`, HIGH).** Step 265 §265.11's "merge test" passes for *assertion* provenance. Merge
provenance does not exist. This compounds with `exec/exp_identity.py` EXP-10b, where an exhaustive
search found **4 associativity counterexamples** for a latest-wins merge rule: merge *order* changes
the resulting state, and merge order is **not recorded anywhere**. A semantically load-bearing fact
is unrepresented.

### Test 6 — identical current states, different histories

```
H_a = assert(v)
H_b = assert(s); assert(v); withdraw(scanner)
K(H_a) = ['v']   K(H_b) = ['v']    structurally equal? True

answerable from K:      what K says now · where each assertion came from ·
                        what each is derived from
NOT answerable from K:  was anything withdrawn · was this ever contradicted ·
                        how many revisions · who withdrew the source
```

**PL-6 (`EXECUTED`, CRITICAL — the central result of this document).**

> The questions answerable from `K` are **exactly** provenance and lineage.
> The questions that are not are **exactly** history.

Therefore **Step 247's `𝒦_t = (K_t, H_t)` is necessary, not merely convenient.** The corpus derives
this by argument at Step 247 and then, from Step 262 onward, drops `H` from the terminal model
(`K = (𝒜,ℛ)`, with history relegated to "external"). Execution says the demotion costs four
audit-relevant questions.

---

## 3. The placement matrix, derived from execution

| Concept | Where it currently lives | Answerable from `K`? |
|---|---|---|
| assertion provenance | **in `K`** (`Π`) | ✅ |
| lineage | **derived from `K`** (`ℛ_der` reachability) | ✅ |
| transition provenance (who changed what, when) | **nowhere** | ❌ |
| merge provenance (when, who, in what order) | **nowhere** | ❌ |
| withdrawal record | **nowhere** | ❌ |
| contestation record | **nowhere** | ❌ |
| evidence provenance | **outside `K`** — `e` is a set of bare ids | ❌ |
| independence of two sources | **no relation exists** | ❌ |

Two of eight are covered.

---

## 4. What this says about the corpus's strongest empirical claim

The corpus's most repeated implementation claim is that **provenance/lineage is the one theory
concept realized in production** — cited in ≥15 verification artifacts as
*"`GovernanceLineageGraph`, 47 tests"*.

This session reproduced the figure exactly:

```
$ php artisan test --filter=Lineage
Tests:  11 deprecated, 47 passed (125 assertions)
```

**PL-7 (`EXECUTED`, HIGH) — and here is what the number actually covers.** The filter matches **18
test classes**, of which exactly **one** — `GovernanceLineageGraphTest`, with **4 tests** — exercises
the typed provenance graph:

```
test_graph_stores_and_retrieves_node
test_successors_returns_linked_nodes
test_branches_detects_fork
test_linear_graph_has_no_branches
```

The other 43 are membership-lifecycle, voting-eligibility and election-security tests that merely
share the substring `Lineage` in a class name — `DivergenceSeverityTest` and
`VotingEligibilityPolicyTest` among them, which have nothing to do with provenance.

**The underlying claim is TRUE:** a typed provenance graph with branching exists, is tested, and
passes. Its evidential weight is **4 tests, not 47** — and the origin artefact
(`EMPIRICAL-KERNEL-TEST.md`) is careful enough to name those same four tests in its own table. The
inflation happened downstream, where the round number propagated into fourteen further artifacts as
though it were the evidence for the provenance claim.

**PL-8 (`EXECUTED`, MEDIUM).** `GovernanceLineageGraph` lives in
`App\Contexts\Membership\Domain\Committee\Constitutional\Lineage` — the **election platform's**
committee-governance context. Under Step 267's own taxonomy this is **Type 3 — analogy**, not Type 1
— semantic realization: it is a decision-lineage graph for committee governance that happens to have
the shape the theory wants. Step 267 §267.5 says explicitly that Type 3 *"must not be reported as
implementation"*, and its own evidence table then reports it as `IMPLEMENTED + 47 tests`.

---

## 5. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **PL-1** | Step 265's base case is correct: at `t=0` lineage is empty, so provenance cannot be *derived* from history. Provenance must be carried. | `EXECUTED` | — (confirms corpus) |
| **PL-2** | The theory has assertion provenance and **no transition provenance**. Who changed what, when, is unrecordable. | `EXECUTED` | HIGH |
| **PL-3** | Lineage is the one unambiguously decidable relation in the theory (`O(n+m)`) — because it depends on nothing in class C. | `EXECUTED` | — |
| **PL-4** | Independence between sources is unrepresentable; absence of a `derives` edge is not evidence of independence. | `EXECUTED` | HIGH |
| **PL-5** | Merge preserves assertion provenance and records nothing about the merge; merge **order** is load-bearing (4 exhaustive counterexamples) and unrecorded. | `EXECUTED` | HIGH |
| **PL-6** | The K-answerable questions are exactly provenance + lineage; the unanswerable ones are exactly history. `𝒦=(K,H)` is **necessary**, and the terminal model drops `H`. | `EXECUTED` | **CRITICAL** |
| **PL-7** | The "47 tests" figure is a `--filter=Lineage` name-match spanning 18 unrelated classes; **4 tests** exercise the provenance graph. Reproduced exactly (`47 passed, 125 assertions`). The claim survives; the evidential weight is ~12× overstated in 14 downstream artifacts. | `EXECUTED` | HIGH |
| **PL-8** | `GovernanceLineageGraph` is an election-platform committee-governance class — Type 3 analogy by Step 267's own taxonomy, reported by Step 267 as Type 1 implementation. | `EXECUTED` | MEDIUM |

---

**Next:** `12-IDENTITY-EQUALITY-GAP.md`.
