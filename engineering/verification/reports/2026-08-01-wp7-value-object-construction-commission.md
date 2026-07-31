# WP-7 Value Object Construction Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** **who constructs** the `EvidencePreservationWindow` Value Object? Ownership is settled and not reconsidered. Construction only — no redesign, no code.
**Repository Integrity Gate:** ✅ PASSED.

> ## RESULT — the question exposed an imprecision in my own wording
>
> I wrote *"the Application Service constructs the Value Object."* **That conflates two things the ARB correctly separated:** *who **invokes** construction* and *where the **construction rules** live.*
>
> **Correct answer, and it is the house idiom already: a private constructor + a named static factory ON the Value Object, invoked by the Application Service.** The VO owns its own validity; the service supplies the values.

---

## 1. Construction responsibility matrix

| Candidate | Verdict | Reasoning |
|---|---|---|
| **Static factory on the VO** — e.g. `EvidencePreservationWindow::forElection(...)` | ⭐ **RECOMMENDED** | Construction **rules** (a window must have an anchor; durations must be positive) are **invariants of the value**, so they belong to the value. **This is the settled house idiom** — verified: `ChallengeRef` (private ctor + `fromString`), `EvidenceSet` (+ `fromRefs`, validating inside), `ContestedOutcomeRef` (+ `of`) — and it is the pattern the coding standard itself illustrates (Rule 6, `Email::fromString`) |
| **Application Service** | ✅ **INVOKES it** — but does not define construction | It gathers the values (resolve election → obtain durations through ports) and **calls the factory**. That is orchestration, which is exactly its job. **It must not validate the window** — that would move an invariant out of the domain |
| **Separate Domain Factory class** | ❌ **Rejected** | Factories exist for **complex aggregate assembly**. Four business values need none. Introducing one would be **pattern purity without a domain reason** — the commission explicitly warns against it |
| **The `Election` aggregate** | ❌ **Rejected** | The greenfield `Election` aggregate holds **no durations**, and WP-7 reads the **legacy** `App\Models\Election`. Routing construction through the aggregate would force WP-7 to load an aggregate it otherwise does not need — **coupling created purely to satisfy a construction path** |

**Ownership and construction land in the same place here — but for different reasons, and that matters:** Election owns EPW because it owns *the business question*; the VO's static factory owns construction because it owns *the value's validity*. **Had those pointed to different homes, ownership would still not have implied construction.**

## 2. Dependency review — the constructor takes business values only

| Constructor input | Kind | Admissible? |
|---|---|---|
| **Anchor** (a point in time) | **business value** | ✅ |
| **Contestation Window** (duration) | **business value** | ✅ |
| **Maximum Adjudication Duration** | **business value** | ✅ |
| **Legal Safety Margin** | **business value** | ✅ |
| A durations **port** | technical collaborator | ❌ **must not enter** — the VO would then depend on an application abstraction |
| A config repository / `Election` Eloquent model / clock | external dependency | ❌ **must not enter** |

**The rule this enforces:** the **Application Service** resolves durations *through ports* and passes **plain business values** in. **The VO never learns where a number came from** — which is what keeps configuration supplying *numbers* while the domain holds the *concept* (the dependency-direction protection identified in the pattern verification).

**On "is it open at T?":** the time `T` is an **argument to the question**, not a constructor dependency. **No clock is injected into the VO** — the caller supplies the instant, exactly as the house injects `ClockInterface` at the application layer and passes `DateTimeImmutable` inward.

## 3. Domain purity

| Must not depend on | Satisfied? |
|---|---|
| Configuration | ✅ receives values, never reads them |
| Framework | ✅ dates and durations only |
| Persistence | ✅ no Eloquent, no repository |
| Infrastructure | ✅ no filesystem, no clock |

**Enforced automatically**: placed in `app/Contexts/Election/Domain/`, the VO falls under `test_greenfield_domain_is_framework_free` — construction purity is guarded by a test, not by discipline.

## 4. Ubiquitous language

The factory name must **express business meaning, not orchestration**.

| Candidate | Verdict |
|---|---|
| `EvidencePreservationWindow::forElection(...)` | ✅ speaks Policy 2's language — the window is *attached to the election instance* |
| `::of(...)` / `::fromDurations(...)` | acceptable; `of` matches `ContestedOutcomeRef::of` |
| `::calculate(...)` · `::build(...)` · `::createFrom(...)` | ❌ **orchestration verbs** — they describe a *procedure*, not a *fact*. Policy 2 names a **concept**, not a computation |

**The naming test:** a domain expert should recognise the call. *"The evidence preservation window for this election"* is language they would use; *"calculate the retention window"* is language an engineer would use.

## 5. Responsibility separation — nothing migrates

| Role | Holder |
|---|---|
| **Ownership** (the concept) | **Election** bounded context |
| **Construction** (the value's validity) | **the VO's static factory** |
| **Orchestration** (gathering inputs, invoking) | **the Application Service** |
| **Consumption** (acting on the answer) | **Audit/Retention** — the deletion guard |
| **The values themselves** | **Q-2 / ARB** |

**Five roles, five holders, no overlap.** The one that would most easily have drifted is **construction into orchestration** — a service assembling and validating the window inline. The static factory prevents it structurally rather than by convention.

## 6. RED readiness confirmation

| Criterion | Status |
|---|---|
| EPW has **one** construction responsibility | ✅ the VO's static factory |
| Construction is framework-independent | ✅ business values only; guarded by an existing test |
| Orchestration remains in the Application layer | ✅ resolve → gather → invoke → ask |
| **No unnecessary Factory introduced** | ✅ a separate factory class was **considered and rejected** |
| No construction ambiguity remains | ✅ |

> ### **ARCHITECTURE FROZEN FOR WP-7. The next activity is RED.**
>
> Strategic alignment ✔ · tactical planning ✔ · pattern verification ✔ · ownership ✔ · **construction ✔**
>
> **Two gates remain, and neither is architectural:**
> 1. ⛔ **WP-6 slice acceptance** — the programme gate;
> 2. ⚠️ **the EPW anchor** — a business value, or approval of **fail-closed**, which decides nothing.
>
> **No further architectural commission is warranted unless implementation exposes a genuine design issue** — the same reopening standard applied when the correction-loop architecture was closed.

---

**Traceability:** house VO idiom verified in `ChallengeRef` · `EvidenceSet` · `ContestedOutcomeRef` (private constructor + named static factory, validation inside) · coding standard Rule 6 (`Email::fromString`) · **Constitutional Policy 2** (*"attached to the election instance"* · *"a single domain concept"*) · WP-7 ownership commission (Election owns EPW) · WP-7 pattern verification (P-1) · `test_greenfield_domain_is_framework_free`. **No code written; no tactical model redesigned; no accepted decision reconsidered.**
