# Discovery — Who owns the "votes per IP" rule?

**Date:** 2026-08-06 · **Type:** Discovery (architecture) — **no code proposed, no behaviour changed**
**Commissioned:** Product Owner — *"Rules such as `MAX_USE_IP_ADDRESS` are constitutional election rules. The controller must not interpret or own these rules. First identify where the Constitution is evaluated today."*
**Origin:** `PBDIGIT-39` Defect B → `PBDIGIT-45`

---

## Answer in one line

> **The constitutional home exists, is registered, runs on every real vote, owns this exact rule per-election — and the controller check is the codebase's own acknowledged *legacy* path, which was designed to be retired behind a feature flag that was never wired up.**

**So this is not a missing abstraction. It is an unfinished migration.**

---

## D-1 · Where the Constitution is evaluated

| Layer | Component |
|---|---|
| Orchestration | `TrustPolicyEvaluator::evaluate()` — called from both vote controllers |
| Policy sequence | `SimplifiedPolicySequence` — *"2. **NetworkBindingPolicy (Article 1/4 — IP limits and continuity)**"* |
| The policy | `Policies/NetworkBindingPolicy` — `identifier() = 'network'`, `dependencies() = ['verification']` |
| Evidence | `Domain/Election/Security/Simplified/NetworkEvidence` — a readonly VO |
| Verdict | `PolicyFinding` → `LegitimacyOutcome` → the **`D.0.3a` constitutional gate** in `VoteController` |
| Registration | `AppServiceProvider:233,247` — **registered and injected** |

**IP limits are constitutionally modelled as an Article.** That is the repository's own word, not an interpretation.

## D-2 · The Constitution already owns the rule — per election

```php
// TrustPolicyEvaluator::buildNetworkEvidence()
$maxVotesPerIp      = $election?->max_votes_per_ip ?? 6;
$restrictionEnabled = $election !== null && $election->network_binding_strategy !== 'none';
bindingStrategy:      $election?->network_binding_strategy ?? 'ip_count',
```

**Both are columns on `elections`, with defaults:**

| Column | Type | Default |
|---|---|---|
| `max_votes_per_ip` | integer | **6** |
| `network_binding_strategy` | varchar | **`'ip_count'`** |

`NetworkEvidence::bindingStrategy` is one of **`'none' | 'ip_count' | 'ip_strict' | 'whitelist_only'`**.

**And `NetworkBindingPolicy::evaluate()` is richer than the controller check in every dimension:**

| It honours | The controller check |
|---|---|
| `restrictionEnabled` → **no finding** when strategy is `'none'` | ignores it |
| `isWhitelisted()` → no finding | ignores it |
| `exceedsLimit($currentTrustLevel)` — **trust-level aware** | flat comparison |
| the **election's own** `max_votes_per_ip` (6) | a **global** `MAX_USE_IP_ADDRESS` (unset ⇒ `null`) |
| returns a graded `PolicyFinding` with `constitutionalBasis: 'network_limit_exceeded'` | returns rendered HTML |

## D-3 · 🔑 The rule has not "leaked" — it was forked, and the codebase says so

**The repository already distinguishes the two authorities by name:**

* `VoteController::trackSovereigntyDivergence(constitutionalOutcome, **legacyOutcome**, …)`
* `DivergenceObserver` — *"Detects and records divergences between: **Procedural sovereignty (H.1-H.3)** outcomes … and the constitutional path"*, described as *"pure constitutional archaeology"*
* `DivergenceObserver:145,165` — *"**H.3** `check_ip_address` **queries global codes table (multi-tenant violation)**"*

**So `check_ip_address()` is not stray controller logic. It is catalogued as H.3, legacy procedural sovereignty, already known to be architecturally wrong.**

### And a switch to retire it was designed but never connected

```php
// DivergenceObserver:36-37
// Check if feature flag is enabled (H.1 is disabled, constitutional path active)
$flagEnabled = config('voting_security.enable_legacy_middleware_ip_check', 0) == 1;
```

| Fact | Evidence |
|---|---|
| The flag is **referenced** in three places | `DivergenceObserver:37` · `D02AggregateConstitutionalDivergence:51` · `SovereigntyDivergenceSummary:36` |
| Its intended default is **off** (`, 0`) | same line |
| It is **not defined** in `config/voting_security.php` — resolves to **`NULL`** | verified |
| 🔴 **No controller consults it before calling `check_ip_address()`** | `grep` across `app/` |

**The migration was designed, instrumented (observer, aggregation command, summary model, divergence table) and given a flag name — and the enforcement path was never switched over.** The legacy check runs unconditionally, and the flag that was supposed to govern it governs only the *observation* of divergence.

## D-4 · What this does to `PBDIGIT-45`'s five questions

**Three of them are already answered — by the domain, not by anyone's preference:**

| Question | Status |
|---|---|
| Behaviour when unconfigured? | **Answered:** the columns have defaults (6, `ip_count`), so an election is never unconfigured. "No restriction" is `network_binding_strategy = 'none'` |
| Mandatory or optional? | **Answered: optional** — `'none'` is a first-class strategy |
| Is "unlimited" valid? | **Answered: yes** — that is what `'none'` means |
| Validate before activation? | **Open** — but far less pressing, since defaults exist |
| Which mechanism is authoritative? | **Answered in design** (the Constitution), **contradicted in execution** (the legacy check still runs) |

> **The Product Owner does not need to invent a policy. The policy exists. What needs deciding is whether the legacy path may now be retired.**

**And the global `MAX_USE_IP_ADDRESS` is revealed as the wrong question entirely:** it is not the election's rule and never was. Its `null` merely made a duplicate gate fail closed.

## D-5 · Why patching the controller would have been the wrong fix

Giving `config('app.max_use_clientIP')` a "safe default" would have:

1. **entrenched the duplicate gate** the architecture is trying to remove;
2. **kept a global value overriding per-election configuration** — an election configured `max_votes_per_ip = 20` would still be judged against a global number;
3. **preserved the H.3 multi-tenant violation** already recorded as debt;
4. **left the flag dead**, so the divergence instrumentation keeps measuring a migration nobody completes.

**It would have made the symptom disappear and the architecture worse** — and it would have looked like a fix in review.

## D-6 · What is NOT established

* **Whether the constitutional gate alone is sufficient in practice.** It is enforced in `VoteController` (`D.0.3a`) but **the demo controller evaluates the envelope and uses it only for logging** (`DemoVoteController:1493,1507`) — consistent with `ADR_20260806_1520`, though it means demo pays for an evaluation it discards.
* **What the divergence data says.** `SovereigntyDivergenceSummary` and `D02AggregateConstitutionalDivergence` exist to answer *"would the constitutional path have decided differently?"* — **that evidence has not been read.** It is the natural input to the retirement decision and **should be consulted before deciding**.
* **Whether `elections.max_votes_per_ip = 6` is the intended business value.** It is a schema default; nobody has confirmed it is a *decision*.

## Recommended next step — one question, not an implementation

**Read the divergence evidence, then decide whether H.3 may be retired.** If the constitutional path would have reached the same verdict, retiring the legacy check is a subtraction, not a redesign — and Defect B disappears with it rather than being patched.

**No code is proposed. No behaviour is changed by this document.**

---

**Traceability:** `PBDIGIT-39` Defect B · `PBDIGIT-45` · `ADR_20260806_1520_Demo_And_Production_Policy_Contexts` · `TrustPolicyEvaluator:130-149` · `Policies/NetworkBindingPolicy` · `Domain/Election/Security/Simplified/NetworkEvidence` · `SimplifiedPolicySequence:18` · `AppServiceProvider:233,247` · `VoteController:1417-1470,1542-1601,3079-3110` · `DemoVoteController:1493,1507` · `DivergenceObserver:15-58,145,165` · `D02AggregateConstitutionalDivergence:33,51` · `SovereigntyDivergenceSummary:36` · `elections.max_votes_per_ip`, `elections.network_binding_strategy`
