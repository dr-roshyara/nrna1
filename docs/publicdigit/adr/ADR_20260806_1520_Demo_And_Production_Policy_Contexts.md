# ADR — Demo elections and production elections are different policy contexts

**Status: ✅ ADOPTED 2026-08-06** — Product Owner, arising from `PBDIGIT-39`
**Scope:** one governing principle for **every** voting safeguard, present and future
**Origin:** an IP vote limit was enforced in demo elections while the repository's own route group documented *"No IP restrictions"*

---

## 1 · The principle

> **Demo elections exist for experimentation, learning and demonstration. They therefore do not enforce production voting restrictions.**
>
> **Production election safeguards are OPT-IN for demo — never inherited automatically.**

**Two contexts, two policies:**

| | Real election | Demo election |
|---|---|---|
| **Purpose** | a binding democratic outcome | learning, evaluation, rehearsal |
| **Safeguards** | enforced | **not enforced unless explicitly decided otherwise** |
| **Repeat voting from one IP** | restricted | **permitted** |

## 2 · The obligation this creates

**Every restriction introduced for production elections must explicitly decide whether it also applies to demo.** The decision is recorded with the feature; **it is never left to inherit.**

**The default assumption is NOT "apply to both."**

This governs safeguards that do not exist yet as much as the one that prompted it:

* rate limiting
* device-fingerprint checks
* geographic restrictions
* CAPTCHA / bot defences
* duplicate-vote warnings
* fraud detection and scoring

**Each arrives with the same question, and the question now has a default answer to argue against rather than a blank.**

## 3 · Why this is a principle and not a note about one variable

**The defect that produced it was not a missing rule — it was a rule the repository already stated and the code contradicted.** `routes/election/electionRoutes.php:538-539` said:

```
// - No IP restrictions (allows testing from same IP)
// - Allows multiple test votes
```

…while two controllers enforced a limit anyway.

**A comment saying "IP restriction removed for demo" would not have prevented that, and will not prevent the next one.** A comment naming a config variable is implementation; it expires the moment the mechanism changes. **The principle survives the mechanism** — which is the whole reason to record it at this level.

⚠️ **The most instructive detail:** `VoteController` *already* branched on demo — it selected `DemoCode` instead of `Code` — and **still applied the limit**. Someone recognised demo needed different handling, changed **which table is counted**, and never asked **whether the rule applies at all**. *(Worse, the branch used a variable not in scope, so it never even fired.)*

> **Partial context-awareness is more dangerous than none: it reads as correct at a glance.**

## 4 · Consequence — a customer-facing one, stated plainly

**Demo mode is how this product is evaluated before purchase.** A safeguard that leaks into demo does not merely inconvenience a tester — it **refuses a prospective customer mid-evaluation**, with a message written for a fraud attempt. In the case that prompted this ADR the message was *"There are already more than&nbsp;&nbsp;votes cast from your IP address"*, **with the number rendered empty**.

**So this principle protects the sales path, not only the test path.**

## 5 · Architectural direction — recorded, NOT authorised

**Today the distinction is expressed as `if ($isDemoElection)` inside controllers.** That is acceptable for two call sites and will not stay acceptable as the list in §2 grows.

**The direction, when evidence justifies it:**

```
VotingPolicy
├── RealElectionVotingPolicy
└── DemoElectionVotingPolicy
```

…so that "which safeguards apply" is answered **once, by type**, instead of re-decided at each call site.

**Explicitly not authorised, and deliberately so:** two call sites are not evidence for a policy hierarchy. **The trigger to revisit is the third safeguard that needs a demo/real distinction** — at that point the conditionals are a pattern rather than a pair, and `ES-002.2` (implementation evidence of insufficiency) is satisfied. **Until then, extracting it would be architecture ahead of evidence.**

## 6 · What this ADR does NOT decide

**It does not say what a *real* election should do when a safeguard is unconfigured.** That is a separate product question — currently an unset IP limit refuses **every** voter in a real election — and it is under discovery in **[`PBDIGIT-45`](../backlog/PBDIGIT-45-unconfigured-safeguards-in-real-elections.md)**. **Approving "demo is unrestricted" must not be read as approving anything about real elections.**

---

**Traceability:** `PBDIGIT-39` (the defect and its verification) · `routes/election/electionRoutes.php:538-539` (the rule, already documented and contradicted) · `app/Http/Controllers/Demo/DemoVoteController.php` `vote_post_check` · `app/Http/Controllers/VoteController.php` `vote_post_check` · `PBDIGIT-45` (the open real-election question) · root `CLAUDE.md` §Demo Environment · `ES-002.2` (evidence before evolution)
