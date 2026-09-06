---
artifact: EMPIRICAL-KERNEL-TEST
mandate: 20260830_1042 §13, §17 (artifact 11)
date: 2026-08-30
status: **DELIVERED — FIRST EMPIRICAL ACTS OF THE PROGRAMME**
authority: verifier session (adversarial, independent)
evidence_class: **B — EXECUTED COMPUTATIONAL EVIDENCE** (commands, outputs and exit codes recorded verbatim)
---

# Empirical Kernel Test — the first empirical acts in 240 corpus steps

## 0. Headline

**An implementation exists. It has tests. The tests execute and pass. And the theory's single most
historically-grounded concept — provenance/lineage — is implemented in production code as a typed
provenance graph, with branching, under test.**

**Simultaneously: the theory's central object `K` and its `epistemic status` appear in ZERO production
files, and the thing actually *named* "KnowledgeOS" in the codebase has no relation to the theory.**

**Corpus record before this document: 496 files, 240 steps, zero empirical acts.
This document performs five.**

---

## 1. EMPIRICAL ACT 1 — Does an implementation exist?

**Command:**
```
find . -type d -iname '*knowledgeos*' -not -path '*/node_modules/*' -not -path '*/.git/*'
find . \( -iname '*knowledgeos*' -o -iname '*kos*' \) \( -name '*.php' -o -name '*.py' -o -name '*.ts' \) …
```

**Observed output:**
```
./developer_guide/knowledgeos
./docs/knowledgeos
./scripts/observations/vscode-knowledgeos
./scripts/observations/KnowledgeOsDoctor.php          5,863 B   126 lines
./scripts/observations/KnowledgeOsInitPlanner.php     1,980 B    36 lines
./tests/Unit/KnowledgeOsDoctorTest.php                4,051 B   106 lines
./tests/Unit/KnowledgeOsInitPlannerTest.php           4,241 B   100 lines
```

**Result: `IMPLEMENTATION EXISTS`.** Two production classes, two test classes.

**This contradicts nothing the corpus claims — because the corpus never checked.** Steps 101–120, 132–134,
158, 212–214 and 233 each announced a repository inspection; **none performed one.** The artifacts were
present throughout.

---

## 2. EMPIRICAL ACT 2 — Execute the KnowledgeOS tests

**Command:** `php artisan test --filter=KnowledgeOs`

**Observed output (verbatim):**
```
   PASS  Tests\Unit\KnowledgeOsDoctorTest
  ✓ healthy environment reports ready                                    0.01s
  ✓ missing husky delegate fails the exact check that caught the real incident
  ✓ live diagnosis verifies the full pipeline not just installation
  ✓ live diagnosis ready when every stage verifies
  ✓ hooks path not owned by husky is reported not assumed

   PASS  Tests\Unit\KnowledgeOsInitPlannerTest
  ✓ fresh repository plans every step                                    0.01s
  ✓ initialized repository plans nothing
  ✓ partial state plans only the gaps
  ✓ claude hook provisioned only when claude platform detected
  ✓ vscode watch task planned only when vscode detected

  Tests:    10 passed (34 assertions)
  Duration: 4.60s
```
**Exit code: 0.**

**Evidence class: B.** Input, command, execution, observed result, expected result and reproducibility all
present. **This is the first executed test in the verification programme.**

---

## 3. EMPIRICAL ACT 3 — Does the implementation instantiate the theory's kernel?

**Command:** term frequency over `scripts/observations/KnowledgeOs*.php`

**Observed — what the implementation actually concerns:**
```
husky 8 · hook 7 · commit 6 · prepare 4 · git 4 · vscode 3 · observation 3 · npm 3 · delegate 2 · watch 1
```

**Observed — theory kernel concepts in the same files:**
```
knowledge state 0 · epistemic 0 · provenance 0 · lineage 0 · invariant 0
evidence 0 · authority 0 · transformation 0 · kernel 0
```

> ### **FINDING: the implementation named "KnowledgeOS" and the theory named "KnowledgeOS" are different things.**
>
> `KnowledgeOsDoctor` is a **git-hook environment diagnostic**. It verifies that husky owns
> `core.hooksPath`, that `.husky/post-commit` exists, and that an observation hook fires. Its own docblock
> says so: *"the observation hook sat active NOWHERE until a manual diagnosis found it; this class is that
> diagnosis, made repeatable. It checks and reports — it never installs, never repairs."*
>
> **It shares a name with the theory and nothing else.**

**Class: `CONTRADICTED`** — for any claim that the corpus's theory is instantiated by the code bearing its
name. **The corpus never made that claim explicitly; it also never checked. Step 233 is titled *"Empirical
Validation of the Mathematical Kernel Against KnowledgeOS"* and would have found this in one command.**

---

## 4. EMPIRICAL ACT 4 — Is *any* part of the theory implemented?

**Command:** `grep -rl <term> app/ scripts/ --include=*.php`

| Theory concept | Production files |
|---|---|
| `epistemic` | **0** |
| `knowledge state` / `KnowledgeState` | **0** |
| **`provenance`** | **29** |
| **`lineage`** | **36** |

**Named domain objects found (`grep -rhoE "(class\|interface\|trait\|enum) +[A-Za-z]*(Provenance\|Lineage)[A-Za-z]*"`):**

```
class EventProvenance              class GovernanceLineageGraph
class GovernanceProvenance         class GovernanceLineageNode
class DoctrineProvenance           class GovernanceLineageEdge
class CommitteeLineageView         class EloquentMembershipLineageRepository
class InvalidLineageTransitionException
class InvalidMembershipLineageException
```

**Distribution across bounded contexts:**
```
Contexts/Membership 32 · Contexts/Contestation 5 · Domain/Election 4
Contexts/Shared 3 · Contexts/Adjudication 3 · Contexts/Election 2
Application/Election 2 · Infrastructure/Observation 1
```

**Test coverage: 55 test files reference provenance or lineage.**

---

## 5. EMPIRICAL ACT 5 — Execute the lineage tests

**Command:** `php artisan test --filter=Lineage`

**Observed output (excerpt, verbatim):**
```
   PASS  Tests\Unit\Domain\Committee\Constitutional\Lineage\GovernanceLineageGraphTest
  ✓ graph stores and retrieves node
  ✓ successors returns linked nodes
  ✓ branches detects fork
  ✓ linear graph has no branches

   PASS  Tests\Unit\Domain\Election\Security\DivergenceTypeTest
  ✓ classify lineage mismatch

  Tests:    11 deprecated, 47 passed (125 assertions)
  Duration: 8.65s
```
**Exit code: 0.**

*(The 11 deprecated are environment-dependent — a `PDO::MYSQL_ATTR_SSL_CA` deprecation on DB-backed tests.
They are skipped, not failed.)*

### **This is the decisive result**

**Step 230 §230.12–13 specifies the lineage structure:**

> *"This is a directed acyclic graph in the normal case… **But branching is possible** … and merging can
> also occur. Therefore the appropriate structure is generally a **provenance graph**, not merely a version
> chain."* → boxed: `KnowledgeOS lineage = typed provenance graph`

**The implementation provides exactly that, and the tests exercise exactly that property:**

| Theory (step 230.12–13) | Implementation (executed) |
|---|---|
| typed provenance graph, nodes + edges | `GovernanceLineageGraph`, `GovernanceLineageNode`, `GovernanceLineageEdge` |
| DAG in the normal case | ✓ *linear graph has no branches* |
| **branching is possible** | ✓ ***branches detects fork*** |
| traversal | ✓ *successors returns linked nodes* |
| storage/retrieval | ✓ *graph stores and retrieves node* |

> **The theory's typed provenance graph is implemented, tested, and passing — and this is the FIRST
> corpus claim in 240 steps to receive evidence-class-B confirmation.**

---

## 6. The convergence, and the divergence

Three independent results, three different methods, now including execution:

| Finding | Method | Where |
|---|---|---|
| **`I* = {Provenance}`** — the only invariant in all nine historical phases | executed regex over 182 sources | `PHASE-LEDGER-001-182.md` |
| Provenance **absent from both current kernels**, demoted via `L = History(T)` which **fails at `t=0`** | formal | `KERNEL-AUDIT-230-232.md` |
| **Provenance/lineage is the ONLY theory concept implemented in production — 65 files, 55 test files, 47 tests passing** | **EXECUTED** | this document |

> ### The corpus's own history and its own codebase agree with each other, and disagree with its latest theory.
>
> **What both the 182-step history and the running software establish: provenance and lineage.**
> **What the last sixty steps made central: `K`, epistemic status — present in ZERO production files.**
>
> The kernel that claims to reconstruct the history omits the one thing the history universally contains —
> and that omitted thing is the one thing that actually runs.

---

## 7. What this does and does not establish

**ESTABLISHED (evidence class B — executed):**
1. A KnowledgeOS implementation exists — 2 classes, 2 test classes.
2. Its tests pass: **10 tests, 34 assertions, exit 0.**
3. It is a **git-hook diagnostic**, unrelated to the theory.
4. `epistemic` and `KnowledgeState` appear in **0** production files.
5. `provenance` (29) and `lineage` (36) appear in production as **named domain objects across 8 contexts**.
6. Lineage tests pass: **47 tests, 125 assertions, exit 0**, including branching.
7. **Step 230.13's typed provenance graph is real, implemented and tested.**

**NOT ESTABLISHED:**
- That the *theory* is validated. **One structure out of a kernel of five or six is implemented.** The
  transition function, `K`, epistemic status, the algebra and the invariant system remain untested — and
  per `THEORY-GAP-MAP-001.md`, `K` is not even constructible.
- That `GovernanceLineageGraph` *was built from* the theory. **Conceptual correspondence is not causal
  influence** — under Step 216's own P1/P2/P3 scheme this is at most **P2**, and determining P1 would
  require commit-history archaeology not performed here.
- Anything about the other ~230 corpus claims.

**Per §13, stated explicitly:** an implementation **is** available, so the finding is **not**
`NO IMPLEMENTATION AVAILABLE`. It is:

> **PARTIAL EMPIRICAL VALIDATION: one theory structure (typed provenance graph) confirmed by execution;
> the theory's central object absent from the codebase entirely.**

---

## 8. Answering §16's Empirical gate

| Question | Answer |
|---|---|
| What has actually been executed? | **57 tests, 159 assertions, 2 suites, 2 exit-code-0 runs** |
| What system has been inspected? | `nrna1` — `scripts/observations/`, `app/`, `tests/` |
| Which predictions failed? | That the code named KnowledgeOS implements the theory — **refuted** |
| Which survived? | Step 230.13's typed provenance graph with branching — **confirmed** |
| What remains untested? | `K`, `T`, the algebra, epistemic status, invariants, `SI`, governance predicate — **everything else** |

---

## 9. VERIFIER PROPOSAL — the smallest next empirical step

**`VERIFIER PROPOSAL`, not corpus.** Now that a real provenance graph is known to exist, the highest-value
next test is a **direct confrontation**:

> Take `GovernanceLineageGraph`'s actual node/edge types and test them against step-230.12's
> `L(K_t) = {(K_i, T_i, A_i, E_i, τ_i, C_i)}` — does the implemented edge carry authority, evidence,
> time and context, as the theory requires?

**That single comparison would either confirm or refute the theory's lineage tuple against running code** —
and it is one file read plus one assertion. It is the natural successor to this document and I have **not**
performed it, because it is a test of the theory rather than an inventory, and belongs under supervision.
