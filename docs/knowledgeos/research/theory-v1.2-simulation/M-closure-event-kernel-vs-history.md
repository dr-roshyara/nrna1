# M — Closure Event: Kernel vs Domain History · `KR-HISTORY-2026-09-02`

**Status** `[EXP]` · **Baseline** KnowledgeOS Theory v1.2, **unchanged**.
**Question:** is the irreversibility of a `ClosureEvent` part of the kernel, or a property of
History/Audit? Three hypotheses, **none assumed**:

```
A   ClosureEvent ∈ Audit/History       the kernel need not know closure happened
B   ClosureEvent ∈ DomainHistory       later DOMAIN behaviour depends on it
C   ClosureEvent ∈ 𝒦                   the kernel itself needs event semantics
```

---

# 1. The decisive test

Two epistemic states with **identical current content** and **different histories**:

| | `H_closed` | `H_never` |
|---|---|---|
| history | `Determine → ClosureEvent → Revise(superseded) → Determine` | `Acquire → Determine` |
| entries | 4 | 2 |
| contains `ClosureEvent` | **yes** | no |
| `determinations`, `evidence`, `hypotheses`, `rejections`, `observations`, `interpretations`, `assessments` | **identical** | **identical** |

> `K_t , K_{t+1}` identical ∧ `History_1 ≠ History_2` — exactly the configuration commissioned.

## Result — **no kernel operation distinguishes them** `[EXP]`

| test | result |
|---|---|
| operations run (`Determine`, `Revise`, `Reject`, `OpenHypothesisSpace`) | **0 produce differing output** |
| **static: kernel transitions that READ `E.history`** | **0** |
| kernel transitions that WRITE `E.history` | 2 — `Revise`, `Supersede` |

> `[NEG]` **Hypothesis C is REFUTED.** The kernel neither reads history nor behaves differently
> because of it. History in this implementation is **write-only from the kernel's side**.

---

# 2. The six commissioned tests

| # | question | answer |
|---|---|---|
| **T1** | Can every epistemically relevant current-state behaviour be reproduced without `ClosureEvent`? | **Yes** — every operation's output is identical across the pair |
| **T2** | Can historical closure be reconstructed from state alone? | **No** — identical current content, different histories; no function of `K_t` separates them |
| **T3** | Does any valid KnowledgeOS operation require knowing closure happened? | **No** — zero reads, statically confirmed |
| **T4** | If yes, the **event** or merely **historical state**? | **Neither, currently.** `AX-5`, `I9` and §17 require the record to **survive**. **Preservation is not consumption.** Nothing requires the event *qua* event |
| **T5** | Can an external History context supply it without enlarging the kernel? | **Yes** — the preservation duty is write-side and satisfiable by an append-only store outside the kernel |
| **T6** | Does removing `ClosureEvent` change epistemic semantics, or only observability? | **Only observability/auditability** — semantics are unchanged (T1) |

---

# 3. A versus B — the decisive evidence is a corpus search `[CORPUS]` `[NEG]`

The distinction turns on whether **any operation, rule or policy consumes closure history**. Four
domain probes, and whether the theory actually contains them:

| probe | distinguishes the pair? | in the theory? |
|---|---|---|
| re-closure under a stronger standard | ✔ | **✘ — no such rule exists** |
| stability / churn metric | ✔ | **✘ — analytics; no theory object consumes it** |
| explanation / "why do we believe X" | ✔ | ✔ — but as a **preservation** obligation |
| supersession traceability (§17 / CE-3) | ✔ | ✔ — again **preservation**, not an operation input |

Corpus search over `reopen · re-open · previously closed · was closed · closed before · re-closure`:

> **Every hit is either research-programme closure** — steps, audits, gap closure, a completely
> different sense of "closed" — **or the `KR-CLOSURE` protocol's own scenario text** (`S14`,
> "a previously closed state is overturned by later evidence").
>
> **No operation, rule or policy in the corpus reads whether a proposition was previously closed.**

## The sharpened finding

> `[EXP]` **The theory's history obligation is WRITE-ONLY: an axiom-level preservation duty with no
> consumer.**

`AX-5` (provenance preservation) and `I9` (provenance must survive a valid epistemic transition) are
**domain axioms**, not infrastructure policy — so the duty is not merely an audit convention. But
nothing *reads* the record. **A and B are architecturally indistinguishable until a consumer exists.**

Your own example — distinguishing *"never closed"* from *"closed and subsequently reopened"* — is
precisely such a consumer. **It does not exist.** Adding it would make B true; that is a **design
decision**, not a discovery.

---

# 4. Verdict

| hypothesis | status |
|---|---|
| **C — `ClosureEvent ∈ 𝒦`** | **REFUTED** — 0 reads, 0 behavioural differences `[NEG]` |
| **B — `ClosureEvent ∈ DomainHistory`** | **AVAILABLE BUT UNEXERCISED** — no consumer in theory or corpus; a design option `[OPEN]` |
| **A — `ClosureEvent ∈ Audit/History`** | **ESTABLISHED for the current theory** — a write-only preservation duty `[EXP]` |

> **Your preliminary position is confirmed on the direction and refined on the reason.**
> `ClosureEvent ∈ History, not Kernel` holds. But the reason is not that closure is "fundamentally
> historical" — it is that **nothing consumes it**. If a consumer were introduced, the event would
> become domain-meaningful without becoming kernel power, exactly as you anticipated.

## The general principle, now with a sharper form

You proposed:

```
Current State ≠ Historical Transition          and therefore     K_t ≠ History_{≤t}
```

Confirmed by T2 — no function of `K_t` recovers the history. The experiment adds a second clause:

```
[PROP]   the kernel WRITES history and never READS it
```

which is stronger and more useful: it makes the kernel/history boundary **testable by static
analysis**, and it gives a standing check — *any future operation that reads history is a proposal to
move the boundary*, and must be adjudicated as such rather than absorbed.

---

# 5. Versioning

> **Baseline remains KnowledgeOS Theory v1.2.** `KR-CLOSURE-2026-09-02` and this experiment remain
> **amendment candidates**, not v1.3.

Endorsed without qualification: *versioning should follow adjudicated theoretical change, not the
number of successful experiments.* Three stable results now sit in the candidate pool
(closure-is-an-event · the five conditions are pairwise distinct · history is write-only). **None is
adjudicated, and three is not a threshold.**

# 6. Next

**The smallest useful next step is a decision, not an experiment:** does KnowledgeOS *want* an
operation that consumes closure history? Two consequences follow immediately and in opposite
directions.

* **If no** — `ClosureEvent` is settled as `A`, the kernel/history boundary is confirmed
  write-only, and the static check above becomes a standing invariant.
* **If yes** — the first such operation must be named, because it converts `B` from a design option
  into a fact, and it will need a home: `History` as a **bounded context with behaviour**, rather
  than a store.

Either way the answer is cheap and requires no simulation. **Do not resolve it by adding the
operation and observing that it works** — that would be the "repair by implementation" failure mode
this programme has already recorded three times.
