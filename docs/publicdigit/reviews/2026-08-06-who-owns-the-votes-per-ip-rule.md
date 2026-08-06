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

---

# REVISION 2 — the four commissioned questions, answered

**Commissioned:** *"Continue discovery only. Do not modify the controller. Determine whether `MAX_USE_IP_ADDRESS` is merely configuration consumed by the constitutional policy, or whether the controller has accidentally duplicated a rule already owned by `NetworkBindingPolicy`."*

**Verdict: the controller evaluates behaviour that appears to overlap with a rule owned by the constitutional snapshot, using a value the constitutional layer never reads.**

⚠️ **Deliberately not called "duplication".** *Duplication* would assert semantic equivalence — that the two paths decide the same question the same way. **That is not proven.** What is proven: the controller reads `MAX_USE_IP_ADDRESS`; the constitutional layer does not; both gate a vote on votes-per-IP. **They are very likely equivalent in intent and demonstrably not equivalent in inputs** (trust level, whitelisting, `'none'`, per-election limit). **The divergence data exists precisely to settle equivalence, and it has not been read.**

## Q1 · Who owns the constitutional rule?

**Not `NetworkBindingPolicy`. It is the evaluator, not the owner.**

**The owner is `ElectionConstitutionSnapshot`** — a readonly domain VO, taken per election, hashed and versioned:

```php
// app/Domain/Election/Security/Simplified/ElectionConstitutionSnapshot.php
readonly class ElectionConstitutionSnapshot {
    public string $networkBindingStrategy;      // <- the rule
    public int    $maxVotesPerIp;               // <- the number
    public string $deviceBindingStrategy;
    public string $ballotAuthorizationProtocol;
    public bool   $verificationRequired;
}
```

**It is a constitutional artifact in the full sense**, not a settings bag:

| Supporting machinery | Purpose |
|---|---|
| `ElectionConstitutionHasher` | `elections.constitutional_hash` — tamper evidence |
| `ElectionConstitutionValidator` | validity of the article set |
| `ElectionConstitutionSchema` | its shape |
| `elections.security_articles_snapshot` / `_version` | the stored, versioned articles |
| `Election.php:2255-2268` | the aggregate builds and persists the snapshot |

**So the ownership answer is: the Election aggregate owns the rule, expressed as a hashed constitutional snapshot. `NetworkBindingPolicy` interprets it. `TrustPolicyEvaluator` orchestrates.**

## Q2 · Why is the controller still doing it? — **transitional migration, instrumented and never completed**

**Dated evidence, not inference:**

| Component | First appeared |
|---|---|
| `helpers.php` (`check_ip_address`) | **2021-12-27**, finalised **2022-01-15** |
| `NetworkBindingPolicy` | **2026-05-26** |
| `ElectionConstitutionSnapshot` | **2026-05-29** |
| `DivergenceObserver` | **2026-05-29** |

**The constitutional layer arrived four years after the procedural check, and the divergence observer landed in the same week as the snapshot** — i.e. the instrumentation to compare old against new was built *as part of* the migration, before retiring the old path.

**The migration's own artifacts spell out the intent:**

* the flag name `voting_security.enable_legacy_middleware_ip_check`, with intended default **off**
* `DivergenceObserver` — *"divergences between procedural sovereignty (H.1-H.3) and the constitutional path"*
* `D02AggregateConstitutionalDivergence` (an artisan command)
* `SovereigntyDivergenceSummary` (a model, with `feature_flag_name`)
* `VoteController::trackSovereigntyDivergence(constitutionalOutcome, legacyOutcome, …)`

**Everything except the switch-over exists.** So the answer is **not** "legacy code nobody noticed" and **not** "a different concern" — it is **a planned migration whose final step was never taken.** The flag governs only the *observation* of divergence, never the *enforcement*.

⚠️ **The `>=` off-by-one is original, not a regression:** it dates to the same 2022-01-15 commit as `check_ip_address`, with the commented-out `>` beside it — the author's own unresolved second thought, preserved for four years.

## Q3 · The data flow, and where `votesFromThisIp` enters

```
Election (aggregate)
  └── ElectionConstitutionSnapshot            OWNS the rule (hashed, versioned)
        │   networkBindingStrategy · maxVotesPerIp
        ▼
    elections.network_binding_strategy / .max_votes_per_ip     the persisted articles
        │
        ▼
TrustPolicyEvaluator::evaluate(…, votesFromThisIp)             ORCHESTRATES
        │   buildNetworkEvidence():137-149
        │     maxVotesPerIp      = $election->max_votes_per_ip      ?? 6
        │     restrictionEnabled = strategy !== 'none'
        ▼
    NetworkEvidence (domain VO)                                the per-request facts
        │
        ▼
NetworkBindingPolicy::evaluate()   "Article 1/4"               EVALUATES
        │   restrictionEnabled? · isWhitelisted()? · exceedsLimit($trustLevel)?
        ▼
    PolicyFinding → LegitimacyOutcome
        ▼
    D.0.3a constitutional gate (VoteController:1585-1601)       ENFORCES
        ▼
                 ALLOW / DENY
```

**`votesFromThisIp` enters as a controller-supplied argument** — the evaluator's own comment says so: *"Populated by controller from database"* (`TrustPolicyEvaluator:147`).

| Site | What it counts |
|---|---|
| `VoteController:1534` | `Code::where('election_id', …)` → passed at `:1549` ✅ |
| `DemoVoteController:1485` | 🔴 **`Code::where(…)` — the REAL codes table, in the demo controller** → passed at `:1500` |

> **🔴 NEW FINDING (N-1): the demo path feeds the constitutional evaluator a vote count read from the real `codes` table, not `demo_codes`.** Currently harmless only because demo does not enforce the verdict (`ADR_20260806_1520`) — **it is wrong data reaching a policy, not a wrong policy.**

**So the controller's legitimate responsibility is exactly one thing: supply the evidence and act on the verdict.** It does supply the evidence. It then *also* re-decides the rule at `:3084-3114` using a global variable — which is the duplication.

## Q4 · Is `MAX_USE_IP_ADDRESS` the real rule? — **No. It is not even read by the Constitution.**

**Verified: zero references in `app/Domain` or `app/Application`.** Every reader is a controller, a helper, or a settings service:

| Reader | Default it applies |
|---|---|
| `config/app.php:149` | **none → `NULL`** |
| `VoteController:3084` | none → `NULL` 🔴 refuses everyone |
| `ElectionVotingController:226` | **`0`** 🔴 refuses everyone |
| `CodeController:39` | `?? 7` |
| `Demo/DemoCodeController:44` | `, 7` |
| `ElectionSettingsService:58` | `env(…, 4)` |

> **🔴 FINDING (N-2): one global value, six readers, four different opinions about its default — `null`, `0`, `7`, `4`.** This is not "a config value with a bad default"; it is **six independent opinions about a rule that belongs to the election.**

**The real rule is the binding strategy** — `'none' | 'ip_count' | 'ip_strict' | 'whitelist_only'` — with `maxVotesPerIp` as its parameter, both per election and both under the constitutional hash. **`MAX_USE_IP_ADDRESS` is the configuration of a duplicate, not of the rule.**

**So repairing `MAX_USE_IP_ADDRESS` would have been fixing the wrong abstraction**, exactly as suspected.

## 🔴 N-3 · A third finding: the constitutional observation is currently blind

**✅ VERIFIED by reading the whole class** (the Product Owner asked for confirmation rather than accepting the claim).

`IpVelocityOverlay::evaluate()` queries `election_security_events` with **exactly two predicates — `election_id` and `recorded_at`** — then reports the count as `'recent_votes_from_ip'`. **There is no IP predicate anywhere in the file**, and `$ctx->network->currentIpHash` **is available and unused**.

**A third detail, visible only on the full read:** the threshold is `env('IP_VELOCITY_THRESHOLD', 10)` **read in the constructor** — so it is (a) **global, not constitutional**, and (b) read via `env()` at construction, which bypasses config caching. **So the drift is not only controller-versus-Constitution: a value inside the constitutional layer is itself global rather than per-election.**

**Two consequences:**

1. **The metric is mislabelled**: it measures election-wide event velocity, not per-IP velocity. It would fire on a busy election rather than on a repeated voter.
2. 🔗 **It always reads 0 today.** `election_security_events` **cannot be written** while `overlay_influence_chain` remains `NOT NULL` and unpopulated (**`PBDIGIT-42`**). **So this constitutional observation has never produced a signal** — and it will silently begin working the moment `PBDIGIT-42` is decided, at which point the mislabelled predicate starts mattering.

## The defect, recorded — not repaired

> **The controller evaluates a votes-per-IP restriction from a global variable the constitutional layer never reads, with a default that differs in each of six call sites, while the Election's constitutional snapshot holds a per-election rule for the same concern.**

**And on retirement, stated at the strength the evidence supports:** *no repository evidence was found showing how or when the legacy implementation is retired.* The flag name `voting_security.enable_legacy_middleware_ip_check` is **referenced** by the observer, the aggregation command and the summary model, and is **not defined in `config/`** — but **that a flag was the intended retirement mechanism is an inference, not a finding.** Retirement could equally have been planned via an ADR, a deployment step, or a commit. **What is certain is only that both paths are live and their divergence is instrumented.**

**`PBDIGIT-38` and `PBDIGIT-45` are therefore not about a comparison operator.** They are about **restoring ownership of a business rule to the authority that already holds it.**

## Remaining unknowns — stated, not assumed

* **What the divergence data says.** `SovereigntyDivergenceSummary` exists to answer *"would the constitutional path have decided differently?"* **Never read.** It is the evidence the retirement decision needs.
* **Whether the constitutional gate is reached in every voting path.** Verified for `VoteController` (`D.0.3a`); **`DemoVoteController` evaluates and only logs** (`:1493,1507`).
* **Whether `max_votes_per_ip = 6` is a ratified business value** or an unexamined schema default.
* **Whether `ip_strict` and `whitelist_only` are implemented**, or names in a VO with no evaluator branch. **Not checked.**

**No code proposed. No behaviour changed. Four questions answered; three new findings recorded.**

---

# REVISION 3 — ownership traceability

**This section is traceability, not a proposal.** Every box on the left is a file and line that exists today. The right-hand column states the target ownership the discovery implies. **Nothing here authorises a change.**

## Current ownership — two authorities, one concern

```
┌─ AUTHORITY A · global configuration ────────────────────────────────┐
│                                                                     │
│   MAX_USE_IP_ADDRESS  (env)                                         │
│        │                                                            │
│        ▼                                                            │
│   config/app.php:149        'max_use_clientIP' => env(...)   no default
│        │                                                            │
│        ├──► VoteController:3084          default: none  → NULL      │
│        │      └─ :3099  if ($votesFromIP >= $max)  ── ENFORCES ──┐  │
│        │      └─ :3114  check_ip_address(...)      ── ENFORCES ──┤  │
│        ├──► ElectionVotingController:226 default: 0              │  │
│        ├──► CodeController:39            default: 7              │  │
│        ├──► Demo/DemoCodeController:44   default: 7              │  │
│        └──► ElectionSettingsService:58   default: 4              │  │
│                                                                  │  │
│   helpers.php:92  check_ip_address()   ← catalogued as H.3        │  │
│        └─ :113  >=   (original 2022; commented-out `>` beside it) │  │
└──────────────────────────────────────────────────────────────────┼──┘
                                                                   │
                                                          ALLOW / DENY
                                                                   │
┌─ AUTHORITY B · the Constitution ─────────────────────────────────┼──┐
│                                                                  │  │
│   Election (aggregate)                                           │  │
│        │  Election.php:2255-2268  builds + persists the snapshot │  │
│        ▼                                                         │  │
│   ElectionConstitutionSnapshot          ◄── OWNS THE RULE        │  │
│     · networkBindingStrategy  'none'|'ip_count'|'ip_strict'|...  │  │
│     · maxVotesPerIp                                              │  │
│        │   hashed  → elections.constitutional_hash               │  │
│        │   versioned → security_articles_version                 │  │
│        │   validated → ElectionConstitutionValidator             │  │
│        ▼                                                         │  │
│   elections.network_binding_strategy   default 'ip_count'        │  │
│   elections.max_votes_per_ip           default 6                 │  │
│        │                                                         │  │
│        ▼                                                         │  │
│   TrustPolicyEvaluator::evaluate(..., votesFromThisIp)           │  │
│        │   :137  maxVotesPerIp      = $election->max_votes_per_ip│  │
│        │   :139  restrictionEnabled = strategy !== 'none'        │  │
│        │                                                         │  │
│        │   ◄── votesFromThisIp SUPPLIED BY CONTROLLER            │  │
│        │        VoteController:1534      Code::where(...)     ✅  │  │
│        │        DemoVoteController:1485  Code::where(...)     🔴 N-1
│        ▼                                                         │  │
│   NetworkEvidence (domain VO)                                    │  │
│        ▼                                                         │  │
│   NetworkBindingPolicy   "Article 1/4"          ◄── EVALUATES    │  │
│     · restrictionEnabled?  · isWhitelisted?                      │  │
│     · exceedsLimit($currentTrustLevel)                           │  │
│        ▼                                                         │  │
│   PolicyFinding → LegitimacyOutcome                              │  │
│        ▼                                                         │  │
│   D.0.3a gate   VoteController:1585-1601      ── ENFORCES ────────┘  │
│        └─ DemoVoteController:1493,1507  evaluated, LOGGED ONLY       │
│                                                                     │
│   OverlayRegistry ─► IpVelocityOverlay                              │
│        · env('IP_VELOCITY_THRESHOLD', 10)  ← global, not per-election│
│        · counts election_security_events by election+time, NO IP  🔴 N-3
│        · that table is unwritable (PBDIGIT-42) ⇒ always 0            │
└─────────────────────────────────────────────────────────────────────┘

   trackSovereigntyDivergence(constitutionalOutcome, legacyOutcome)
        └─► SovereigntyDivergenceSummary   ◄── NEVER READ
```

## Target ownership — what the discovery implies

```
Election (aggregate)
     │
     ▼
ElectionConstitutionSnapshot          owns the rule — hashed, versioned, per election
     │
     ▼
NetworkBindingPolicy                  evaluates it
     │
     ▼
TrustPolicyEvaluator                  orchestrates the policy sequence
     │
     ▼
LegitimacyOutcome                     one verdict
     │
     ▼
Controller                            supplies evidence · acts on the verdict · decides nothing
```

## The delta, stated as facts rather than intentions

| # | Observed today | Target |
|---|---|---|
| 1 | Two authorities can deny a vote on the same concern | One |
| 2 | The rule's number comes from a global env var in the controller path | From the election's snapshot |
| 3 | Six readers apply four different defaults (`null`, `0`, `7`, `4`) | One value, one owner |
| 4 | `'none'`, whitelisting and trust level are honoured by **one** path only | Honoured because only that path decides |
| 5 | `votesFromThisIp` is computed by controllers — **one from the wrong table** | Supplied correctly, or derived inside the boundary |
| 6 | A global `IP_VELOCITY_THRESHOLD` sits inside the constitutional layer | Constitutional if it gates a vote |
| 7 | Divergence between the two is instrumented but never read | Read once, to decide; then unnecessary |

**Item 7 is the cheapest and comes first:** the mechanism to answer *"do the two authorities actually disagree?"* already exists, has never been run, and **is the evidence any retirement decision needs.**

## What this traceability does NOT claim

* **Not** that the two paths are semantically equivalent — see the softened verdict in rev 2.
* **Not** that a feature flag is the intended retirement mechanism.
* **Not** that `ip_strict` / `whitelist_only` have evaluator branches — **unchecked.**
* **Not** that the target ownership is authorised. **It is the input to an ADR, not a decision.**
