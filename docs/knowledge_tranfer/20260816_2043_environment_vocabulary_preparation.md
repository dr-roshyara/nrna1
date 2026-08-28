# `EV-1` Preparation — The Environment Vocabulary

### What environments does this organisation actually recognise?

| | |
|---|---|
| **Kind** | ⭐ **DECISION PREPARATION.** ⛔ *No vocabulary adopted · no schema · no config change · no implementation* |
| ⭐ **LANE** | ⛔ **TRACK B — EXPLORATORY.** *Governs nothing* |
| **Commission** | ARB, 2026-08-16 — *"prepare the Environment Vocabulary decision next, not a scope schema or conflict engine"* |
| **Method** | ⭐ **Evidence → business meaning → recommendation → your decision.** ⛔ *No environment is proposed that no mechanism already distinguishes* |
| **Preserved** | A · B · `RM-1`…`RM-4` · `AM-1`…`AM-5` · `SC-2` · ⭐ **`SC-1` RULED** |
| **Date** | 2026-08-16 |

---

# 0 · Why this decision exists

`SC-1` ruled four applicability dimensions. Three arrived with governed vocabularies already running — **3 documentation domains · 12 bounded contexts · 31 artifact kinds · 8 lifecycle states.**

⛔ **Environment arrived with nothing.** And it is the dimension whose rule actually **blocks**.

> ### ⭐ **The organisation makes a blocking engineering decision based on "environment", and has never defined what environments exist.**

---

# 1 · Evidence — what is actually declared

⭐ **Measured across configuration, tests and CI:**

| Where | Declares | Value |
|---|---|---|
| Development environment template | `APP_ENV` | ⭐ **`local`** |
| Test environment template | `APP_ENV` | ⭐ **`testing`** |
| Test runner configuration | `APP_ENV` · test database name | ⭐ **`testing`** · a separate database |
| Continuous integration | `APP_ENV` | ⭐ **`testing`** |
| ⚠️ **Framework configuration** | ⚠️ **fallback when `APP_ENV` is unset** | ⛔⛔ **`production`** |

## 1.1 ⭐⭐ Two environments are declared. A third is inherited.

```
   local       ⭐ declared — the developer's machine
   testing     ⭐ declared — three times, consistently

   production  ⛔ NEVER DECLARED ANYWHERE
               ⛔ appears only as a framework fallback
```

## 1.2 ⛔⛔ And there is no production system

| Checked | Result |
|---|---|
| Deployment pipeline | ⛔ **none** |
| Container, orchestration or infrastructure definitions | ⛔ **none** |
| Deploy, ssh, or cloud steps in continuous integration | ⛔ **none** — all six workflows are verification-only |

> ### ⭐⭐ **The organisation has two environments: a developer's machine and a test run. There is no production, and there never has been in this repository.**

---

# 2 · ⛔⛔ The finding that matters most — a silent collision with `SC-2`

⭐ **You ruled, hours ago:**

> *"When a scope dimension is not specified, it is treated as **unspecified** — not as 'all'. Missing scope must never silently broaden applicability."*

⛔ **The running configuration does the opposite, for this exact dimension:**

```
   APP_ENV unset
        ↓
   framework substitutes a value
        ↓
   ⛔ the system reports itself as PRODUCTION
```

> ### ⛔⛔ **Absence does not yield "unspecified". It yields a specific, consequential value that nobody chose — and it is the most severe value available.**

## 2.1 The concrete consequence

⭐ **A developer with no `APP_ENV` set is running against their local database while the application reports its environment as production.**

| | |
|---|---|
| ⭐ **The safe half** | the destructive-command gate checks for *testing* and blocks otherwise — ⭐ **so an unset environment fails safe** |
| ⛔ **The unsafe half** | ⛔ **every other decision keyed on environment sees "production" where a local machine is** |

> ### ⭐ **The environment a system reports is not the environment it is in.** *That is a mislabel with real consequence, and it is invisible because the fallback is silent.*

⚠️ **Recorded as an observation.** ⛔ *Track B governs nothing; no configuration change is proposed, requested, or authorised by this document.*

---

# 3 · Developer scenarios

## 3.1 ⭐ The blocking rule

> A developer runs a destructive database command.
>
> **Blocked.** They add the testing flag. **Permitted.**

⭐ **What the rule actually distinguishes, measured:** it looks for *testing* — by environment variable, by command flag, or by a database name containing "test" — and blocks everything else.

> ⛔ **Three different detections standing in for one vocabulary that was never declared.** The rule does not distinguish *local* from *production*; it distinguishes **testing from not-testing**.

## 3.2 ⚠️ The rule everyone quotes

> *"Production systems must use approved authentication."*

⭐ **Every worked example in this programme's rule discussions has used *production*.** ⛔ **No such system exists here.**

> ### ⚠️ **We have been reasoning about environments using an environment we do not have.** *Not an error in the reasoning — the examples were illustrative — but it matters now that the vocabulary is being defined from evidence.*

## 3.3 ⭐ The question the ARB raised

> *Are `prod` · `production` · `live` · `customer` · `staging` · `preprod` different environments, synonyms, or a hierarchy?*

⭐ **Answered from evidence: none of them is in use.** The only environment words appearing in configuration are `local`, `testing`, and the inherited `production`. ⛔ **There are no synonyms to reconcile and no hierarchy to model, because there is only one deployment tier and it is a developer's laptop.**

> ### ⭐ **Defining `staging` or `preprod` today would be defining from expectation, not evidence** — which is the failure this method exists to prevent.

---

# 4 · Recommendation — the smallest vocabulary the evidence supports

## ⭐ **Two values, plus a ruling on the inherited third**

| Value | Meaning | Evidence |
|---|---|---|
| ⭐ **`local`** | a developer's own machine and database | ⭐ declared in the development template |
| ⭐ **`testing`** | an isolated test run against a disposable database | ⭐ declared three times — template, test runner, CI |

## 4.1 ⭐ And the third — a business decision, not a technical one

**`production` is inherited from the framework, declared nowhere, and realised by no system.** Three options:

| | Option | Consequence |
|---|---|---|
| **A** | ⭐ **Recognise `production` now** | honest about intent; ⛔ **defines a value from expectation, and `SC-1` was ruled on evidence** |
| **B** | ⛔ **Omit it until a production system exists** | evidence-pure; ⚠️ **but the framework will keep substituting it silently** |
| **C** | ⭐ **Recognise it *and* record that nothing realises it yet** | ⭐ names what the framework already does, without pretending a deployment exists |

> ### ⭐ **Recommendation: Option C.**
>
> ⭐ *The value is already in play — it is what the system calls itself when nobody says otherwise. Omitting it from the vocabulary would not remove it from the running system; it would only remove our ability to talk about it.*

## 4.2 ⛔ Explicitly NOT recommended

`staging` · `preprod` · `qa` · `uat` · `sandbox` · `live` · `customer` · `development` *(as distinct from `local`)*.

⛔ **None appears in any configuration, test, or workflow.** ⭐ *Each can be added later at the cost of one vocabulary entry; adding them now costs every future statement a dimension value it cannot justify.*

## 4.3 ⭐ What the ruled `SC-1` model implies for this dimension

⭐ **`SC-1` ruled that each subject kind declares which dimensions apply. For environment that is unusually clean:**

| Subject kind | Environment applies? |
|---|---|
| A running service or command | ⭐ **YES** |
| A governed document | ⭐⭐ **NOT APPLICABLE** — *governed centrally, per `SC-1`; not an author's declaration* |
| A code component | ⚠️ **open** — the code is environment-independent; its *deployment* is not |

⭐ **The document row is the case that motivated the three-state model in the first place, and it now resolves cleanly.**

---

# 5 · What this decision does **not** settle

⛔ how environments are represented · whether they form a hierarchy · precedence when a statement names several · the framework fallback *(a configuration matter, outside Track B)* · anything about the other three dimensions' values · any implementation.

---

# 6 · The decision, framed for ruling

> ## **`EV-1` — Which environments does EKS recognise?**

**Evidence** → two environments are declared and used consistently — a developer machine and an isolated test run. A third, *production*, exists solely as a silent framework fallback and is realised by no deployment. No other environment word appears anywhere in configuration, tests, or CI.

**Business meaning** → this is the vocabulary a developer needs in order to answer *"does this rule apply to what I am running right now?"* — the only dimension of `SC-1` whose rule currently **blocks** work.

**Recommendation** →

> ⭐ **Adopt `local` and `testing`. Recognise `production` while recording that no system currently realises it.** ⛔ **Add no other environment until one exists.**
> ⭐ **Rule that `environment` is NOT APPLICABLE to governed documents** — a subject-kind property under `SC-1`, not an author's declaration.

⚠️ **And one thing worth your attention regardless of the ruling:** §2 records that absence of an environment currently produces *production* rather than *unspecified*, which is the opposite of `SC-2`. ⛔ **That is a configuration observation, not a decision request** — but it is the clearest live example of the behaviour `SC-2` was ruled to prevent.

**Your decision.**

---

*Prepared for `EV-1` on ARB instruction, Track B. Every recommended value is declared in a running configuration; every rejected value is absent from all of them. Structural claims are `OBSERVED`; the Option A/B/C framing is `INFERRED`. ⛔ **No vocabulary adopted · no configuration changed · no schema · no implementation · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — Track B exploratory. Governs nothing.***
