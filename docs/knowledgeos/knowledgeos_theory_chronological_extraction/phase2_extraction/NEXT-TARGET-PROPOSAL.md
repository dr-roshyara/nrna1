# Next Research Target — ranked by information gain

⛔ **No numerical scores.** The methodology does not justify them (§13B), and assigning them would be the `bar` error in another costume.

---

## Candidate A ⭐ — Operational realization: is any of this actually *used*?

**Question.** At realization layer **D**, are the governed capabilities invoked in real workflow — or do they merely exist?

**Why it now outranks everything else.** ⭐ **Two independent observations converged during this audit**, and neither was visible before:

| | |
|---|---|
| `12-G` | CAP-001 records **0 executions, 0 decisions changed** |
| `OT-0002a` | whether `link-check --apply` is ever invoked is **unknown** |

**Hypothesis.** The governed capabilities are **specified and implemented but not operationally invoked.**
**Falsifier.** Evidence of routine invocation — CI configuration, git history, execution logs, or a recorded decision changed by an instrument.
**Evidence required.** `.github/`, CI config, `composer.json`/`package.json` scripts, git log for `--apply`, CAP-001's execution record. ✅ **All inside the repository.**
**Information gain.** ⭐ **Highest available.** It tests the layer *no experiment has touched*, and it bears on the theory's central weakness — a mechanism with **zero observed instances**. A negative result would be **more informative than a positive one**: it would establish that the separation of powers is prescribed, represented, partially implemented, and **not exercised**.
**Dependency.** None.
**Possible theory impact.** If unused: §3's mechanism is `A`/`B`/partial-`C` and ⛔ **empty at `D`** — and the platform's difficulty stops being conceptual and becomes one of adoption. If used: the first real evidence the loop can run.

## Candidate B — Is `knowledge-lint` a qualification instrument at all?

**Question.** Its contract is *"always returns 0"* and *"a recommendation to the author."* Does the theory have the right category for it?
**Falsifier.** Governance text naming it as a qualification instrument, or an authority consuming its output as a verdict.
**Information gain.** ⚠️ **Moderate** — likely yields a **classification** finding, not a mechanism finding. ⭐ But it tests something the theory may lack: a *diagnostic* category distinct from qualification.
**Dependency.** ⚠️ Partly blocked — `OT-0001a` (*which verdicts count as passing*) is unresolved.

## Candidate C — What does `confidence ≥ 99` govern?

**Question.** A numeric threshold governs an architectural action, in a repository whose ruling says numeric scores are *"conversational, never architectural."*
**Falsifier.** A scope clause limiting the ruling to *review records*.
**Information gain.** ⚠️ **Moderate but sharp.** Either the ruling doesn't reach instruments — a scope finding — or this is a **second instance of the rule that killed `bar`**, which would make it a pattern rather than an incident.
**Dependency.** None.

## Candidate D — `VO-05`: re-audit F0022/F0023

**Question.** Were two files excluded by *document kind* rather than by reading their contributions?
**Information gain.** ⚠️ **Unknown — and that is the point.** ⭐ It is a flag against **my own audit**, and the corpus's own name for the error is *"filename thinking wearing a taxonomy."*
**Dependency.** None. Cheap.

---

## Recommendation

> ### **A, then D.**

**A** because it attacks the theory's central weakness at the one layer nothing has tested, with all evidence in-repository, and because **a negative result is the more informative outcome** — a rare property worth spending a turn on.

**D** because it is cheap, and because an audit that flags itself should be checked before its results are built upon.

⛔ **Not B** — dependency-blocked. ⛔ **Not C** yet — sharp, but it refines a boundary rather than testing a mechanism.

⛔ **And not F0026.** Nothing that moved the theory in the last three passes came from reading a new corpus file.
