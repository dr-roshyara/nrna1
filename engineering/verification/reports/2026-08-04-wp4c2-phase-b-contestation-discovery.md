# WP-4C-2 — Phase B: Contestation Discovery

**Produced by:** engineering, 2026-08-04. **Discovery only — no state invented, no transition designed.**
**The one question:** given that **Collection** owns the corrective action (COL-5a, established in Phase A), what is the **business standing of the challenge** while correction is outstanding?
**Method:** **EVIDENCE** (accepted artefact) · **INFERENCE** (derived) · **HYPOTHESIS** (needs validation).

---

## 0. The answer comes from the guards, not from judgment

Phase A's discovery report said the adjacent states were *"none obviously right"* — a judgment about fit. **The transition guards say something stronger and checkable: most of them are not reachable at all.**

```php
admit(…)        guard: Raised
dismiss(…)      guard: Raised                              ← NOT reachable from Routed
route(…)        guard: Admitted, Investigating             ← NOT re-enterable from Routed
adjudicate(…)   guard: Routed        + requires DeterminationId
resolve(…)      guard: Adjudicated   + requires DeterminationId
lapse(…)        guard: Raised, Admitted                    ← NOT reachable from Routed
```

> **FROM `Routed`, EXACTLY ONE TRANSITION EXISTS: `adjudicate(DeterminationId, …)`.** **EVIDENCE** — `Challenge.php:73-133`.

**Three consequences follow directly, and none required a modelling opinion:**

1. **`Dismissed` and `Lapsed` are not candidates** — not because they fit poorly, but because **their guards make them unreachable from `Routed`.** *(This supersedes the "adjacent states" framing in the discovery report, which was weaker than the evidence available.)*
2. **Re-routing is not expressible** — `route()` guards `Admitted`/`Investigating`, so a routed challenge cannot be routed again.
3. **The single available exit requires the one thing a declared failure never has** — a `DeterminationId`.

## 1. Alternatives supported by existing artefacts

**Only two are, and neither is complete. No third is invented here.**

| | **ALT-1 — the challenge stays `Routed`** | **ALT-2 — the challenge returns for re-routing** |
|---|---|---|
| **Business meaning** | "still with adjudication" — unchanged by the declared failure | "back with Contestation, awaiting corrected evidence" |
| **Owner** | Contestation owns the challenge; Collection owns the correction | same |
| **Lifecycle implication** | **no transition at all** — the challenge is standing still by *absence* of a path, not by design | requires a transition out of `Routed` that does **not** exist |
| **Supporting evidence** | **EVIDENCE:** it is what happens today. Nothing consumes `AdjudicationFailureDeclared`, so the state is untouched | **EVIDENCE of intent:** the adjudication-process migration records *"an expired one returns to Contestation, which may route it again — WP-5"*, and its **partial unique index deliberately permits a challenge to accumulate processes over time**, which only re-routing needs |
| **Contradicting evidence** | **`Routed` means "handed to adjudication."** After a declared failure adjudication holds nothing — so the state would assert a false fact. **INFERENCE** | **the transition does not exist in the aggregate.** `route()` cannot be re-entered, and no other path leaves `Routed`. **EVIDENCE** |
| **Verdict** | ⛔ **not a business answer** — it is the current absence of one | ◐ **the only evidenced intent, and it is UNIMPLEMENTED** |

## 2. 🛑 FINDING — the accepted re-route intent has no aggregate transition behind it

**Adjudication's schema was built for a challenge that can be adjudicated more than once over time.** Its partial unique index exists *precisely* so that *"a challenge may accumulate processes over time … but never two at once."*

**The `Challenge` aggregate cannot produce that sequence.** Nothing leaves `Routed` except `adjudicate()`, so a challenge can be routed exactly once, ever.

> **So the gap is NOT specific to declared failure. The same missing transition blocks the EXPIRY path (WP-6/Q-2), whose return-and-re-route intent is already recorded and accepted.** **INFERENCE, from two accepted artefacts that presuppose a transition neither provides.**

**This is why Phase A's A-3 was right to refuse to assume one reaction could serve both events — and it now cuts the other way too: the two paths share a missing capability even if they need different dispositions.**

## 3. Completion criteria — answered

| # | Question | Answer | Label |
|---|---|---|---|
| **1** | Does the challenge terminate? | **It cannot, in the current model.** No terminal state is reachable from `Routed` | **EVIDENCE** (guards) |
| **2** | Does it remain pending? | **It remains `Routed` by default — because nothing moves it, not because pending was modelled.** "Pending" is currently an absence, not a state | **EVIDENCE + INFERENCE** |
| **3** | Who owns it during that period? | **Contestation owns the challenge; Collection owns the corrective action** (COL-5a) | **EVIDENCE** (Phase A) |
| **4** | Does the existing model express that state? | **NO.** `Routed` asserts "handed to adjudication", which is false once adjudication has declared it cannot rule | **INFERENCE** |
| **5** | What evidence demonstrates a new state is genuinely required? | **The guard analysis (§0) — and it shows a new state may not be what is required.** From `Routed`: no terminal state is reachable, `route()` cannot be re-entered, and the only exit needs a `DeterminationId`. **So a new TRANSITION is definitely required; whether it needs a new STATE depends on whether the destination is an existing state (`Admitted`/`Investigating`, for re-routing) or a new one.** That is Contestation's call | **EVIDENCE for the transition · OPEN for the state** |

## 4. Readiness assessment

> **WP-4C-2 IS READY FOR PLANNING ONCE ONE DECISION IS TAKEN — and the decision is now a choice between two evidenced options, not an open modelling problem.**

**The decision for Contestation:**

> **Does a declared failure return the challenge for possible re-routing (the intent already recorded for expiry), or does it end the challenge in a way the model cannot currently express?**

**If re-routing:** the destination is plausibly an existing state, so this may be a **transition-only** change — **HYPOTHESIS**, because whether `Admitted` or `Investigating` is the correct destination is a business question about whether admission survives a failed adjudication.

**If terminating:** a new state is required, because no terminal state is reachable from `Routed`. **EVIDENCE.**

**Two things this analysis does NOT do:** it does not choose between them, and it does not propose the transition's name or shape.

## 5. Consequences for adjacent work, surfaced not absorbed

| Item | Status |
|---|---|
| **The expiry path (WP-6/Q-2) shares the missing transition** | Its return-and-re-route intent is accepted and equally unimplemented. **Whichever way §15.3 is decided, this gap should be resolved once for both paths, not twice** — **INFERENCE** |
| **COL-5a's mechanism** | still unallocated (Phase A) |
| **D4** | still held — nothing publishes the event |
| **A-4 / COL-5a's second consumer** | still unassessed |

## 6. Traceability

`Challenge.php:73-133` (**the guards — the load-bearing evidence**) · `ChallengeState.php:15-22` · **EPIC-002** COL-5a · **EPIC-004K §10 · §15.3** · **Q-2** horizon ruling · `2026_07_30_000001_create_adjudication_processes_table.php` (the partial unique index and its recorded rationale) · **R-88** · R-80 · ES-006.1 · Phase A `2026-08-04-wp4c2-responsibility-analysis.md` · discovery `2026-08-04-wp4c2-discovery.md`.
