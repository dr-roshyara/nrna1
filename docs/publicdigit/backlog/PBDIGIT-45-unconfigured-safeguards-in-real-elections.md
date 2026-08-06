# PBDIGIT-45 — What should a real election do when a safeguard is not configured?

**Type:** Discovery (business) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Origin:** `PBDIGIT-39` Defect B — **extracted rather than implemented**, on the Product Owner's instruction

| | |
|---|---|
| **Status** | 🟡 **REFRAMED 2026-08-06 after an ownership discovery — and the question changed.** Engineering must not implement |
| **The reframing** | **This is not a missing policy. It is an unfinished migration.** The Constitution already owns "votes per IP" — per election, with defaults, and with `'none'` as a first-class "no restriction" strategy. The controller check is the codebase's own **legacy H.3 path**, designed to be retired behind a feature flag that was **never wired up** |
| **Discovery** | [`2026-08-06-who-owns-the-votes-per-ip-rule.md`](../reviews/2026-08-06-who-owns-the-votes-per-ip-rule.md) **rev 2** — **read this before answering anything below** |
| **Owner of the rule** | **`ElectionConstitutionSnapshot`** (hashed, versioned, per election) — `networkBindingStrategy` + `maxVotesPerIp`. `NetworkBindingPolicy` evaluates; it does not own |
| **`MAX_USE_IP_ADDRESS`** | 🔴 **Not the rule, and never read by the constitutional layer.** Six readers apply four different defaults (`null`, `0`, `7`, `4`) |
| **Three new findings** | **N-1** demo feeds the evaluator a count from the *real* `codes` table · **N-2** six readers, four defaults · **N-3** `IpVelocityOverlay` counts election-wide events but labels them per-IP, **and reads 0 always** because `election_security_events` cannot be written (`PBDIGIT-42`) |
| **What is settled** | The implementation is broken **and** the correct owner is already identified in the code |
| **What is NOT settled** | **Whether the legacy path may now be retired** — which needs the divergence evidence read, not a new policy invented |
| **Does NOT block** | demo elections — settled by `ADR_20260806_1520` and already fixed |

> ⚠️ **Three of the five business questions below were already answered by the domain.** They are kept for the record, annotated. **The remaining decision is a migration decision, not a policy decision** — and patching the controller's default would have entrenched the very duplicate the architecture is trying to remove.

> **The bug is obvious. The correct behaviour is not.** This story exists because those are different things, and only the first is engineering's to answer.

---

## The fact — what the code does today

`config/app.php:149` is `'max_use_clientIP' => env('MAX_USE_IP_ADDRESS')` — **no default.** Unset ⇒ `null`.

```php
if ($votesFromIP >= $max_use_clientIP)   // VoteController:3094
if ($times_use_cleintIP >= $max_use_clientIP)   // helpers.php:113
```

**PHP coerces `null` to `0`, so `0 >= null` is `true`:** a voter with **zero** prior votes is refused. **An unconfigured real election refuses every voter.**

**Two further facts, both verified:**

* **The message is unusable for diagnosis:** *"There are already more than&nbsp;&nbsp;votes cast from your IP address"* — **the number renders empty**.
* **An off-by-one sits in the same comparison.** `helpers.php:112` shows the commented-out `>` beside the live `>=`, so a configured limit of *n* refuses the *n*-th vote, not the *(n+1)*-th.
* ⚠️ **A "safe default" of `0` is not safe.** `ElectionVotingController:226` already uses `config('app.max_use_clientIP', 0)` — **equally blocking**.

## The question — genuinely open

> **An administrator creates a real election and never configures an IP limit. What should happen?**

| | Option | Consequence |
|---|---|---|
| **A** | **Unlimited** — no IP restriction unless configured | never blocks an election by accident; less restrictive by default |
| **B** | **Limit of 1** — one vote per IP | restrictive by default; **would surprise administrators**, and breaks shared networks (families, offices, internet cafés, university halls) |
| **C** | **Election cannot activate** without an explicit value | the most explicit; adds an administration step to every election |
| **D** | **Configuration hierarchy** — organisation policy → election policy → IP policy | most flexible; largest change |

## The five questions that decide it

1. **What is the intended behaviour when the limit is not configured for a real election?**
2. **Is the IP restriction mandatory or optional?**
3. **Is "unlimited" a valid business configuration** — i.e. may an organisation legitimately run an election with no IP restriction at all?
4. **Should the configuration be validated before an election becomes active?** *(This one has an architectural consequence: it would place a new precondition on the `ElectionLifecycleState` transition to active.)*
5. **Which mechanism enforces the decision?** — see below; there are currently three.

## ⚠️ Superseded framing — kept because the correction is instructive

**The options table above and the questions below were written before the ownership discovery.** They assumed the product had to *choose* a policy for unconfigured elections. **It does not — the policy exists on the `elections` table:**

| Question as originally posed | What the domain already answers |
|---|---|
| Behaviour when unconfigured? | `max_votes_per_ip` defaults to **6**, `network_binding_strategy` to **`'ip_count'`** — an election is never unconfigured |
| Mandatory or optional? | **Optional** — `'none'` is a first-class strategy |
| Is "unlimited" valid? | **Yes** — that is what `'none'` means |
| Validate before activation? | **Still open**, but far less pressing given defaults |
| Which mechanism is authoritative? | **Answered in design** (the Constitution); **contradicted in execution** (the legacy check still runs unconditionally) |

**The global `MAX_USE_IP_ADDRESS` was the wrong subject all along.** It is not the election's rule; its `null` merely made a *duplicate* gate fail closed.

## 🟡 PROPOSED answer — the Product Owner's leaning, and it turns out to MATCH the domain

> **IP restriction is OPTIONAL. If no limit is configured, there is NO IP restriction. If a limit is configured, enforce it.**

**Stated reasoning:** IP restriction is one fraud control among many — eligibility, voting codes, device fingerprint, audit trail. Making it optional gives organisations flexibility **while avoiding an election locked out by missing configuration.**

**Recorded as PROPOSED, not adopted.** Engineering does not implement from a leaning.

✅ **Worth noting: this leaning is exactly what `network_binding_strategy` already encodes** — optional, with `'none'` meaning no restriction and a configured value meaning enforce. **So approving it is largely ratifying the model that exists, not choosing a new one.**

⚠️ **One consequence worth weighing before approving, because it is the strongest argument against Option A:** *unlimited-by-default* means an organisation that **believes** it has IP protection, and has simply never set the variable, has **none** — and nothing tells them. **Option A trades a loud wrong failure for a silent absent safeguard.** If it is approved, the mitigation is visibility: the election's configuration should state plainly that no IP restriction is active.

## Three mechanisms, none authoritative

| Location | Shape |
|---|---|
| `helpers.php:92` `check_ip_address()` | global count; table passed as an argument |
| `VoteController:3079-3101` | inline, election-scoped |
| `ElectionVotingController:211-226` | layered, with `config(..., 0)` as a "global fallback" |

**Same shape as `PBDIGIT-35`:** several mechanisms answer one question and none is declared authoritative. **Question 5 must be answered, or the fix will pick a winner by accident.**

⚠️ `DivergenceObserver.php:145,165` already records that `check_ip_address` queries the **global** `codes` table — a multi-tenant violation (H.3). **Known debt; not this story's to fix, but it argues against `helpers.php` being the authoritative home.**

## What engineering must NOT do

* **Not** pick a default because it makes a test pass. *(That mistake has already been made once here: `MAX_USE_IP_ADDRESS` was set to unblock `PBDIGIT-00`'s walk, which masked the demo defect rather than fixing anything — see `PBDIGIT-39`.)*
* **Not** implement the proposed answer before it is approved.
* **Not** fix the `>=` off-by-one in isolation — it lives in the same `if` as the default question, and changing one alone leaves a comparison whose two halves were decided separately.

## Acceptance criteria — revised after the discovery

* [ ] **Read the divergence evidence first.** `SovereigntyDivergenceSummary` + `php artisan` `D02AggregateConstitutionalDivergence` exist precisely to answer *"would the constitutional path have decided differently?"* **That data has never been read**, and it is the natural input to the decision.
* [ ] Decide whether the **legacy H.3 check may be retired** in favour of the constitutional gate. **If the verdicts agree, retirement is a subtraction — and Defect B disappears rather than being patched.**
* [ ] Confirm whether `elections.max_votes_per_ip = 6` is an intended **business value** or merely a schema default nobody has ratified.
* [ ] Define `voting_security.enable_legacy_middleware_ip_check` in config, or delete the three references to it — **a flag that exists in code and not in config is worse than neither.**
* [ ] Answer the one genuinely open business question: **should activation validate the network configuration?**
* [ ] **Only then** does implementation begin, as its own story.

---

**Traceability:** `PBDIGIT-39` Defect B (the evidence) · `ADR_20260806_1520_Demo_And_Production_Policy_Contexts` §6 (explicitly leaves this open) · `config/app.php:149` · `app/Helpers/helpers.php:92-113` · `app/Http/Controllers/VoteController.php:3079-3110` · `app/Http/Controllers/ElectionVotingController.php:211-226` · `app/Services/Constitutional/DivergenceObserver.php:145,165` · `PBDIGIT-35` (same "no authoritative mechanism" shape) · `PBDIGIT-30` (the business-decision-record pattern this follows)
