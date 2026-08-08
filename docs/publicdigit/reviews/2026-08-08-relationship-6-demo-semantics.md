# Relationship 6 — Demo: do demo semantics constitute Election business decisions?

**Commission:** Principal Architect, Election architecture discovery (`PBDIGIT-48`, Slice 1) · **Date:** 2026-08-08
**Status:** DISCOVERY ONLY — **no production code, test, fixture, migration, schema or configuration was changed**
**Scope, as defined by the programme** (plan line 227, not from the relationship's name): ***"Demo → whether demo semantics constitute Election business decisions"*** (`PBDIGIT-59`-adjacent)

> **Bounded deliberately.** This is **not** a review of how demo mode works, whether the demo flow is correct, or whether demo capabilities are complete. It asks one question and stops.

**Carried forward, not re-derived:** Relationships 1–5 · `PBDIGIT-48` · `PBDIGIT-45` (the Constitution owns votes-per-IP) · `ADR_20260807_1500`

---

## 1 · Business context

**Why this relationship matters:** if demo semantics alter Election business decisions, then **every Election test running against a demo fixture may be verifying different rules than production applies** — which would change how a large part of the estate is classified in Step 2. If they do not, demo fixtures are safe substitutes and the question is closed.

## 2 · Business concepts

**OBSERVED FACT — stated by the Product Owner, 2026-08-06:**

> *"There are two demos: **public** (no login) — you cannot save the result; and **private** (with login) — the result should be saved."*

**INTERPRETATION.** A *demo* is a **rehearsal of the election capability**: a customer exercises the real journey without producing a real electoral outcome. The two variants differ along **two** axes — *authentication* and *persistence* — and the Product Owner's statement binds them together.

**OBSERVED FACT.** *Demo-ness* is carried by `elections.type ∈ {real, demo}`, a **domain data attribute**.

## 3 · Decision ownership

| Business decision | Owner **observed** | Evidence |
|---|---|---|
| Is this election a demo? | **Domain** (data) | `elections.type`; `Election::isDemo()` |
| Does demo change the **constitutional lifecycle**? | **nobody — it does not** | §4 |
| Does demo change **which capabilities are offered**? | 🔴 **Interface** (HTTP controllers) | §5 |
| Does demo change the **votes-per-IP rule**? | 🔴 **Interface**, exempting a **Constitution-owned** rule | §5 |
| Does demo change **where results are persisted**? | **Interface + Infrastructure** (separate models / session) | §6 |

> **The type is domain data; every observed *consequence* of that type lives in the Interface layer.** No domain or application artifact expresses what a demo *is permitted to do differently*.

## 4 · Domain relationship — demo does NOT alter constitutional decisions

**OBSERVED FACT.** No demo branching exists in any of the three constitutional mechanisms established by Relationships 4 and 5:

| Mechanism | Demo branch? |
|---|---|
| `ElectionConstitution` (rule content) | **none** |
| `ElectionLifecycleEngineImpl` (state derivation) | **none** |
| `ConstitutionalTransitionGuard` (transition authorisation) | **none** |

*(Method note: two apparent hits in the engine were the word **"democratic"** — a substring collision, excluded rather than counted. Recorded because a name-based scan would have reported demo branching that does not exist.)*

**CONCLUSION.** **The constitutional lifecycle is demo-agnostic.** A demo election progresses through the same 12 states, under the same rules, authorised by the same guard. **For lifecycle behaviour, a demo fixture is a faithful substitute for a real election.**

## 5 · Where demo *does* change behaviour — all of it in the Interface layer

**OBSERVED FACT.** Every demo branch found outside the demo-specific controllers takes one of four shapes:

| Shape | Sites | Nature |
|---|---|---|
| **Capability exclusion** — `abort_if($election->type === 'demo', 404)` | `CandidacyReviewController:20,67` · `CandidacyManagementController:20,61,104` · `CandidacyApplicationController:119,184` · `VoterImportController:174` | 🔴 **a business rule** — candidacy review/management/application and voter import are **not offered** for demos |
| **Repeatability** — `$forceNew = ($election->type === 'demo')` | `ElectionController:128` · `VoterSlugController:251` | 🔴 **a business rule** — demo voter slugs are re-issued, enabling repeat runs |
| **Rule exemption** — votes-per-IP not applied to demo | `838817bd (PBDIGIT-39) exempt demo elections from the IP vote limit` | 🔴 **a business rule**, and see below |
| **Presentation** — `is_demo`, `badge: 'DEMO'/'OFFICIAL'`, colour | `ElectionController:63,77,79,80` | **projection only** — no decision |

**CONCLUSION.** **Demo semantics DO constitute Election business decisions** — the answer to the commissioned question is **yes**. They are not merely a data partition.

### 🔴 The sharpest observation — a constitutionally-owned rule is exempted from outside the constitution

**Carried forward (`PBDIGIT-45`, established):** **the Constitution owns the votes-per-IP rule**; the controller-level check is legacy *H.3* carrying an unwired retirement flag.

**OBSERVED FACT.** `check_ip_address` now has exactly **one** caller — `VoteController:3114` (the real path). It was removed from the demo path by `838817bd`.

**INTERPRETATION.** The exemption is therefore expressed by **which controller calls a helper**, not by anything the Constitution says. **A rule the Constitution owns is switched off for a class of elections by code that has no constitutional standing.**

**Whether that is a defect is NOT established** — the exemption may be exactly the intended business rule (*a rehearsal should not be rate-limited*). **What is established is that the rule's owner and the rule's exemption live in different places.** → **BD-4** (§9).

**⚠️ Correction to this programme's own record.** During the `PBDIGIT-00` journey walk I recorded that the demo path called `check_ip_address` and was blocked by it. **That was true then and is false now** — `838817bd` changed it under `PBDIGIT-39`. The earlier observation is **superseded, not wrong**; recorded here so the two entries are not read as contradicting each other.

## 6 · Persistence relationship — three regimes, and the Product Owner's rule is implemented

| Regime | Vote stored in | Identity | Evidence |
|---|---|---|---|
| **Real election** | `votes` | no `user_id` (anonymity) | root `CLAUDE.md`; Relationship 5 |
| **Private demo** (authenticated) | **`demo_votes`** — a real table | authenticated user | `PBDIGIT-00` walk; `DemoVoteController` |
| **Public demo** (anonymous) | 🔑 **the session — not the database** | `session_token`; *"No user_id anywhere"* | `PublicDemoController:325,342-343` |

**OBSERVED FACT.** `PublicDemoController:325` states *"Public demo votes are stored in session, not in database"*, and the vote is written to `session(['public_demo_vote_…'])`.

**CONCLUSION.** **The Product Owner's rule is implemented as stated.** Public demo does not persist a result; private demo does.

**Precision worth keeping:** *the vote* is not persisted — **a `PublicDemoSession` row is** (`:91`). *"Public demo persists nothing"* would be inaccurate.

## 7 · Verification relationship

**INTERPRETATION, and the finding most relevant to Step 2:**

* **For lifecycle/constitutional behaviour** (§4), a demo fixture is a **faithful substitute** — same states, same rules, same guard. Tests using demo elections to verify lifecycle invariants are **not** thereby compromised.
* **For the four behaviours in §5**, a demo fixture verifies **different rules than production applies**. A test asserting candidacy-application behaviour against a demo election exercises a **404 exclusion**, not the capability.
* **`tests/Feature/Demo` (18 tests)** was recorded in the Slice 1 scope pre-filter as outside the measured suites. **Which of the two categories each falls into is UNDETERMINED** — it requires reading each test's intent, which is Step 2.

**Coverage is descriptive; completeness is normative.** This section records **what demo fixtures can and cannot substitute for**. It makes **no claim** that demo verification is sufficient or deficient — no contract stating what demo behaviour must be verified has been established.

## 8 · Established facts · interpretations · hypotheses

| Class | Statement |
|---|---|
| **OBSERVED FACT** | Constitution, lifecycle derivation and transition guard contain no demo branching |
| **OBSERVED FACT** | Four capability exclusions, two repeatability branches and one rule exemption exist, all in Interface-layer code |
| **OBSERVED FACT** | `check_ip_address` has one caller (`VoteController:3114`); the demo exemption was made by `838817bd` |
| **OBSERVED FACT** | Public demo votes are stored in session; private demo votes in `demo_votes`; a `PublicDemoSession` row is persisted |
| **CONCLUSION** | **Demo semantics DO constitute Election business decisions** |
| **CONCLUSION** | The constitutional lifecycle is demo-agnostic; demo differences are entirely capability-level and interface-owned |
| **INTERPRETATION** | *Demo* currently has **no domain representation of its consequences** — only a type flag plus scattered interface behaviour |
| **HYPOTHESIS — not tested** | The four capability exclusions are a single business rule (*a demo does not accept real participants*) expressed four times. **Not established**; each was found independently and no artifact groups them |

## 9 · Business decisions required — none taken

| # | Decision | Why it cannot be derived technically |
|---|---|---|
| **BD-4** | **Is the votes-per-IP exemption for demo a constitutional exemption?** | `PBDIGIT-45` established the Constitution owns the rule. The exemption lives in controller wiring. **If the Constitution owns the rule it should own the exemption; if the exemption is an interface concern, then so was the rule.** Cannot be settled by reading either |
| **BD-5** | **Is "demo" a domain concept, or an interface concern?** | Today it is domain *data* with interface *consequences*. Whether the domain should express *what a demo may do* is a modelling decision |
| **BD-6** | **Are the four capability exclusions one business rule or four?** | Determines whether they belong in one place. **Engineering can show they are identical in form; it cannot establish they are identical in intent** |

**BD-1, BD-2 and BD-3 remain open and were not addressed by this investigation.**

## 10 · Architectural implications

**Under BD-5 = "domain concept":** the exclusions and exemption belong in the domain/constitution, and their present location is a boundary weakness.
**Under BD-5 = "interface concern":** the present location is correct, and the finding is closed with no work.

**The same observations invert with the decision** — the pattern recorded in Relationship 5's BD-1 branches. **No branch is selected.**

## 11 · Explicitly NOT established

* Whether any capability exclusion is **wrong** — only that they exist and where they live.
* Whether the four exclusions share an intent (**hypothesis only**).
* Which of `tests/Feature/Demo`'s 18 tests verify demo-specific rules versus shared lifecycle rules — **Step 2**.
* Whether public-demo session storage satisfies any retention/audit obligation — **no such obligation has been established**.
* Whether the demo/real distinction affects anonymity guarantees — **not investigated; out of this relationship's scope.**

## 12 · Scope / authorization

* implementation changes: **NONE**
* test changes: **NONE**
* business decisions taken: **NONE**
* additional scope opened: **NONE** — three new business decisions are *recorded*, which is the commissioned output, not scope expansion

## 13 · Self-audit

| Check | ✓ |
|---|---|
| Started from business meaning, not tables | ✅ §2 |
| Established the business decision before the mechanism | ✅ §3 before §5 |
| Distinguished caller from owner | ✅ §5 — type is domain, consequences are interface |
| Distinguished domain authority from persistence | ✅ §4 vs §6 |
| Distinguished coverage from completeness | ✅ §7 — explicitly makes no sufficiency claim |
| Named the contract before normative language | ✅ BD-4 rather than "the exemption is wrong" |
| Separated fact / interpretation / hypothesis / conclusion | ✅ §8 |
| Avoided unmeasured quantification | ✅ no frequency or proportion claimed |
| Avoided answering BD-1/2/3 | ✅ untouched |
| Avoided expanding Relationship 6 | ✅ anonymity and demo correctness recorded as out of scope |
| Avoided KnowledgeOS promotion | ✅ none proposed |
| Carried forward rather than re-derived | ✅ `PBDIGIT-45`, `PBDIGIT-48`, Relationships 1–5 |
| Corrected the programme's own superseded record | ✅ §5 IP note |

---

**DISCOVERY COMPLETE — BUSINESS MEANING / DECISION OWNERSHIP / PERSISTENCE RELATIONSHIPS RECORDED — NO IMPLEMENTATION CHANGES — AWAITING PRODUCT OWNER REVIEW**

**Traceability:** plan line 227 (scope) · `app/Domain/Election/Constitution/ElectionConstitution.php` · `app/Application/Election/Services/{ElectionLifecycleEngineImpl,ConstitutionalTransitionGuard}.php` · `app/Http/Controllers/Election/{CandidacyReviewController:20,67,CandidacyManagementController:20,61,104,VoterImportController:174}.php` · `app/Http/Controllers/CandidacyApplicationController.php:119,184` · `app/Http/Controllers/ElectionController.php:63,77,79,80,128` · `app/Http/Controllers/VoterSlugController.php:251` · `app/Http/Controllers/Demo/PublicDemoController.php:26-29,91,325,342-343` · `app/Http/Controllers/VoteController.php:3114` · commit `838817bd` · `PBDIGIT-45` · `PBDIGIT-39` · `PBDIGIT-48` · Product Owner statement 2026-08-06
