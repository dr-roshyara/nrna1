# Gap Analysis — The Python Migration

### Why it has not happened, and what would actually start it

| | |
|---|---|
| **Kind** | ⭐ **GAP ANALYSIS.** ⛔ *No migration proposed · no language decision made · no code written* |
| **Question** | *"We wanted Python. We decided on an adapter so PHP work could continue and Python could start. It is not happening. Why?"* |
| **Status** | **PROPOSED** — an ARB input |
| **Presentation** | Business language first; technical identifiers as supporting evidence (ARB standing instruction) |
| **Date** | 2026-08-16 |

---

# The short answer

**Three things are true at once, and only the third is a problem.**

| | |
|---|---|
| **1** | ⭐ **The decision you remember was never taken.** What exists on record is an *architectural direction*, explicitly labelled *"not an implementation decision"* — and the document carrying it has been **awaiting your signature for thirteen days** |
| **2** | ⭐ **The adapter is a different shape than described.** The record does not say *"adapt the PHP work so we can move to Python."* It says **PHP becomes a permanent adapter of a multi-language platform** — it is never replaced |
| **3** | ⛔⛔ **The gate that would open Python is an evidence gate, and the evidence engine stopped eleven days ago** |

> ### ⭐ **Nothing is blocking Python technically.** The environment has Python 3.13 installed and working. The language-neutral contract that a Python implementation would have to satisfy **already exists and is tested.** What is missing is the *evidence* the decision was gated on — and that evidence is not being produced.

---

# 1 · What was actually decided

## 1.1 The recorded direction

> *"If the engineering platform is extracted from PublicDigit in the future, **Python is the preferred implementation language** because it enables a language-agnostic observation platform and aligns with the broader AI and analysis ecosystem. **This is an architectural direction, not an implementation decision, and remains gated by the repository-separation criteria.**"*

**Recorded 2026-08-03.** `OBSERVED`.

## 1.2 The status of the document carrying it

| | |
|---|---|
| **Status** | ⚠️ **PROPOSED — awaiting Decision Authority** |
| **Drafted** | 2026-08-03 |
| **Days awaiting** | ⛔ **13** |
| **Its own words** | *"nothing here executes until accepted"* |

> ### ⭐ **Gap 1 — a decision cannot fail to execute if it was never taken.** The record contains a *direction awaiting acceptance*, not a decision. Everything downstream inherits that.

---

# 2 · The adapter is not a migration bridge

⭐ **This is the most important correction in this analysis, and it changes what "not happening" means.**

| What was described to me | What the record says |
|---|---|
| *"Use an adapter for what we developed so far, continue PHP, then start Python"* — an adapter as a **temporary bridge**, with PHP eventually retired | ⭐ *"Existing tools become **ADAPTERS of the extracted platform**, never technical debt to be removed"* |
| Python **replaces** PHP | ⭐ *"**Python is not replacing PHP; Python hosts the language-neutral platform that all languages feed.**"* |
| A migration | ⭐ *"PHP/Java/.NET/Go adapters → one platform"* |

**The recorded architecture in one picture:**

```
   PHP adapter ──┐
   Java adapter ─┼──▶  one language-neutral platform  (Python, if the gate opens)
   .NET adapter ─┤
   Go adapter ───┘
```

> ### ⭐ **PHP is not on a path to retirement. It is collector #1, permanently.** The record states its final form and its budget: *"The PHP tool's final form is fixed: collector · runner · output JSON — DONE… **Growth budget: zero, permanently** — bug fixes and evidence only."*

> ### ⛔ **Gap 2 — if the expectation was "PHP work now, Python later, PHP retired," that is not what was recorded.** Nothing will produce that outcome, because nothing was designed to.

---

# 3 · What already exists — more than expected

⭐ **The hardest part of a multi-language platform is the contract. It is built.**

| Asset | State | Evidence |
|---|---|---|
| ⭐ **Language-neutral conformance contract** | ✅ **EXISTS** | 7 golden fixtures + an expectations file, declaring itself *"ANY implementation (PHP today; Python/Java/Rust tomorrow) must produce identical observations on these examples. **Language-neutral by design.**"* |
| ⭐ **Contract is tested** | ✅ **GREEN** | a verification suite, 23 assertions, including a completeness check |
| ⭐ **Algorithm decisions pinned** | ✅ | five variant decisions recorded explicitly, so two implementations cannot silently diverge |
| ⭐ **Future layout pre-recorded** | ✅ | *"When a second implementation language arrives, this evolves to `specification/` + `fixtures/` + `implementations/{php,python,…}` — the specification becomes the source of truth"* |
| ⭐ **Trigger contract** | ✅ | technology-neutral; every adapter produces the same input object; *"the runtime never knows how it was triggered"* |
| ⭐ **Output boundary** | ✅ | a JSON API exists so *"any adapter (IDE extension, editor plugin, CLI) passes changed files"* |
| **Python runtime** | ✅ **available** | Python 3.13.2 installed |

> ### ⭐ **A Python implementation would not need to negotiate a boundary. It would need to pass seven fixtures.**

---

# 4 · What does not exist

| | Count |
|---|---|
| Python files in the repository | ⛔ **0** |
| Python packaging (requirements / pyproject / setup) | ⛔ **none** |
| PHP files in tooling | **57** |
| Shell files in tooling | **25** |
| JavaScript files in tooling | **2** |

⚠️ **One recorded exception, self-declared:** a governance record notes that an artifact *"was created via `python3` and `cat` heredocs under Bash. **The gate did not fire.** It was not evaded deliberately — it was simply not on that path."*

> **Python is present in the environment and absent from the architecture** — and the one recorded use bypassed a governance gate. `OBSERVED`.

---

# 5 · Why it has not happened — the four gates

**Every path to Python passes through a gate, and all four are closed.** None is closed by disagreement; all four wait on the same kind of evidence.

| # | Path | Gate | State |
|---|---|---|---|
| **1** | *Python owns reporting* — phase 2 of the recorded migration path | *"the service boundary validated by **REAL USAGE**"* | ⛔ closed |
| **2** | *Contract-based separation* — the observation-JSON boundary | the same real-usage gate | ⛔ closed |
| **3** | *Separate repositories* | second adopter **+ independent ownership and operation in practice** | ⛔ closed |
| **4** | ⭐ **Evidence Analytics** — the one capability where Python is named as the implementation | *"recommendation history + decision history + timestamps, **at a volume worth analyzing (dozens of cycles)** — analytics on n≈1 analyzes noise"* | ⛔ closed |

⭐ **Gate 4 is the nearest one, and it is the only one whose distance can be measured.**

---

# 6 · The decisive measurement

⭐ **Gate 4 needs *dozens of cycles* of engineering evidence. Here is the actual evidence store.**

| Stream | Records | Last written |
|---|---|---|
| Assessments — ⭐ **a completed cycle** | ⛔ **1** | **2026-08-05** |
| Outcomes | 1 | 2026-08-05 |
| Decisions | 4 | 2026-08-05 |
| Recommendations | 10 | 2026-08-05 |
| Cohesion observations | 2 | 2026-08-05 |
| Test-presence observations | 58 | 2026-08-05 |

> ## ⛔⛔ **Nothing has been written to the evidence store for eleven days.**
>
> ### **The gate needs dozens of completed cycles. There is one — and the counter stopped.**

## 6.1 What was produced instead, in the same period

| | |
|---|---|
| Engineering evidence records, 2026-08-05 → today | ⛔ **0** |
| Architecture documents in this folder, **today alone** | **11** |
| Architecture documents in this folder, total | **24** |

> ### ⭐⭐ **This is the gap, stated plainly.**
>
> **Python is gated on evidence that only accumulates when the platform is used on real engineering work. For eleven days the effort has gone into deciding what the architecture should be, rather than running the engine that produces the evidence the decision needs.**
>
> ⛔ **The gate is not stuck. It is correctly closed, and nothing is feeding it.**

## 6.2 And it is the same finding the ARB already has open

The organisation has a standing control requiring that platform work produce measurable product progress, with a review that fires when platform-only work accumulates. **That review has never run, because its measurement basis was never defined** — the ARB's own docket puts *define the basis, then run the check* first, ahead of everything else.

⭐ **This analysis supplies a concrete candidate basis: engineering-evidence records produced per period.** By that measure the last eleven days score zero, and today scores eleven documents.

---

# 7 · The gap table

| # | Gap | Kind | Severity |
|---|---|---|---|
| **G-1** | The language direction is recorded but **never ratified** — 13 days awaiting signature | ⭐ **governance** | **high — blocks everything below** |
| **G-2** | The expectation *"PHP now, Python later, PHP retired"* does not match the recorded architecture *"PHP is a permanent adapter"* | ⭐ **expectation vs record** | **high — it changes what success looks like** |
| **G-3** | ⛔⛔ **The evidence engine stopped 11 days ago; the nearest gate needs dozens of cycles and has one** | ⭐ **operational** | ⛔ **decisive** |
| **G-4** | The one recorded Python use bypassed a governance gate | governance | medium |
| **G-5** | Tooling is 57 PHP / 25 shell / 2 JS / **0 Python** — no second implementation exists to prove the contract is really language-neutral | architecture | medium |
| **G-6** | The platform-cost control that would have surfaced G-3 has never run | governance | medium |
| ⭐ **Not a gap** | the conformance contract, the trigger contract, the JSON boundary, the pinned algorithm decisions, Python availability | — | ⭐ **all present** |

---

# 8 · What would actually start it

⭐ **Two options. They are not alternatives — one is cheap and immediate, the other is the real path.**

## Option A — Prove the contract is language-neutral. **Cost: small. Available today.**

Write **one** Python implementation of the cohesion collector and run it against the seven existing fixtures.

| | |
|---|---|
| **What it proves** | whether the contract is genuinely language-neutral, or only *believed* to be |
| **What it costs** | one script; no architecture, no repository change, no migration |
| **What it does not do** | ⛔ it does **not** open any gate, adopt Python, or start a migration |
| **Why it is worth doing** | ⭐ **a contract that has never been satisfied twice is a hypothesis.** Today it is untested in exactly the dimension that matters |
| ⚠️ **Governance** | this is a **second implementation of an existing capability** — it needs an ARB act, not a developer's initiative |

## Option B — Restart the evidence engine. **This is the only thing that opens gate 4.**

The pipeline exists, is wired, and works. It produces records when engineering work happens and passes through it.

> ⭐ **Gate 4 opens when dozens of engineering cycles have been observed, decided, and assessed. That requires doing product engineering with the platform switched on — not more architecture.**

⛔ **There is no shortcut.** Analytics on one cycle analyses noise, which the gate says in its own words.

---

# 9 · Decisions for the ARB — in business language

| # | Business decision | Why it is yours |
|---|---|---|
| **P-1** | ⭐ **Do we commit to a multi-language engineering platform, or stay single-language?** *The record says a direction was noted, not chosen. Thirteen days of silence is itself an answer, and it should be an explicit one* | It sets whether any of this work has a destination |
| **P-2** | ⭐ **Is the intent that PHP is eventually replaced, or that PHP remains permanently as one of several adapters?** *These are different architectures. The record says the second; the expectation described says the first* | ⭐ **The two answers produce different work for years** |
| **P-3** | **Should we spend a small amount now to prove the language-neutral contract actually holds, before committing to anything?** | It converts a belief into evidence, cheaply |
| **P-4** | ⛔ **The engineering-evidence engine has been idle for eleven days while architecture work continued. Is that acceptable, or should platform work pause until the engine is producing again?** | ⭐ **This is the platform-cost question, made concrete and measurable for the first time** |

---

# 10 · Bottom line

**Nothing technical is stopping Python.** The runtime is installed, the contract exists, it is tested, and its future multi-language layout is already written down.

Three things are stopping it, and none of them is code:

**One.** The decision was never signed — it has been a *proposed direction* for thirteen days.

**Two.** The architecture that was recorded is not the one described: **PHP is not scheduled for replacement. It is scheduled to be permanent.** If the expectation is retirement, no gate anywhere leads there.

**Three, and decisive.** The nearest gate opens on engineering evidence. **The evidence store has one completed cycle and has not been written to since 5 August** — while twenty-four architecture documents were produced, eleven of them today.

> ### ⭐ **The migration is not blocked by architecture. It is blocked by the absence of the operational evidence its own gate requires — and that evidence only appears when the platform is used to do engineering work rather than to describe it.**

---

*Sources: the repository-separation ADR (`PROPOSED`, 2026-08-03, REV 3) · the metrics spike plan (four-phase migration path, gates) · the Deferred Architecture Register (Evidence Analytics gate) · `scripts/observations/examples/lcom4/expected.json` (the conformance contract) · the observation streams under `engineering/verification/observations/` (measured 2026-08-16) · runtime checks (Python 3.13.2, PHP 8.5.8) · tooling file counts. Every quantity measured, not estimated. ⛔ **No migration proposed · no language chosen · no code written · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — an ARB input.***
