# **PKS Phase III — Operational Validation Charter**

| | |
|---|---|
| **Act** | **CHARTER**, issued under the Phase III Mandate (Authority, 2026-07-31) |
| **Label** | **PKS-P3.** *PMR-10 (GOVERNED) collision check performed* |
| **Nature** | ***A NEW GOVERNANCE ACT — it establishes the purpose and success criteria of a new cycle. It is NOT a continuation of Phase II*** |
| **Status** | **ISSUED. Operational use may begin.** |

---

## 1. Executive Summary

**Phase II established that the methodology is internally sound. Phase III asks whether it works.**

**The baseline — SDM v1.2 / EOP v1.2 — is frozen and reference-defined. Phase III uses it unchanged, applies it to real engineering work, and collects the one kind of evidence Phase II could not produce.**

---

## 2. Guiding Principle

> ## **Sixteen reviews, five adoptions, and a frozen baseline establish that the methodology is INTERNALLY SOUND. They establish NOTHING about whether it WORKS.**

**Internal coherence and operational effectiveness are different claims.** ***Phase III exists to collect evidence for the second, and no amount of further analysis of the first can substitute.***

**This connects directly to the one certification dimension Phase II left at zero:**

| Dimension | Standing |
|---|---|
| **Method Design** | Provisionally Certified |
| **Operational Evidence** | ⛔ **ZERO-INDEPENDENT** — *and by the programme's own principle, **a certification dimension derives credibility from being outside the unilateral control of the programme it certifies**. Only USE can move it* |

---

## 3. Purpose

> **Demonstrate whether the governed baseline (SDM v1.2 / EOP v1.2) produces better engineering outcomes in real work.**

---

## 4. Scope

| In scope | |
|---|---|
| **Use the frozen governance baseline UNCHANGED** | ✅ |
| **Apply it to real architectural and implementation work** | ✅ |
| **Collect operational evidence** | ✅ |
| **Record where governance HELPS, where it creates FRICTION, and where new evidence appears** | ✅ |
| **Do not amend the baseline except through existing change-control routes** | ✅ |

---

## 5. Success Criteria — engineering improvement, **not artifact count**

| Criterion | Observed |
|---|---|
| Governance decisions are applied consistently | ☐ |
| Change control works without ambiguity | ☐ |
| Reviews become faster or clearer | ☐ |
| Architectural disagreements are resolved using the governed baseline | ☐ |
| Any deficiencies are supported by operational evidence rather than theoretical concern | ☐ |

### 5.1 ⚠️ TWO GUARDS the criteria require, added because the programme's own discipline demands them

**GUARD 1 — the criteria must admit NEGATIVE findings, or Phase III can only confirm.**

> ### ***Every criterion above is written in the improvement direction. A validation that can only observe success is not a validation. Scope §4 already requires recording FRICTION — these criteria must be read as admitting the negative case:***

| Criterion | Its negative form, which is equally a finding |
|---|---|
| decisions applied consistently | **decisions applied inconsistently, or bypassed** |
| change control unambiguous | **change control ambiguous, or routed around** |
| reviews faster or clearer | ***reviews slower or more opaque than before the governance existed*** |
| disagreements resolved using the baseline | **disagreements resolved despite it, or stalled by it** |
| deficiencies evidence-supported | **the baseline generating theoretical concerns that never materialize** |

**GUARD 2 — these are OBSERVATIONS, not TARGETS.**

***A criterion treated as a target can be met by changing behaviour to satisfy the measure. "Reviews become faster" is evidence when observed and a distortion when pursued.***

### 5.2 The honest failure direction, named in advance

> ### **The baseline's most likely failure is not being WRONG. It is being TOO EXPENSIVE FOR THE VALUE IT DELIVERS — correct governance that no one can afford to follow.**

***Named here because the programme's own governed question requires it, and because a validation that has not stated what would falsify it can only accumulate confirmations.***

---

## 6. Feedback Path

```
Operational use
        ↓
Operational evidence
        ↓
Assessment
        ↓
Authority decision (if needed)
        ↓
Change control
        ↓
Next governance cycle
```

**Unchanged from the path Phase II established.** *No new instrument is created by this charter.*

### 6.1 ⭐ The three assets and their DISTINCT responsibilities *(Authority, 2026-08-01)*

| Asset | Responsibility |
|---|---|
| **KnowledgeOS** | *defines* **reusable engineering governance** |
| **PKS** | *holds* **PublicDigit's structured knowledge** |
| **⭐ PublicDigit** | ***the REFERENCE IMPLEMENTATION and EVIDENCE GENERATOR — the proving ground, not merely the consumer*** |

```
KnowledgeOS  →  PublicDigit engineering  →  Operational evidence  →  PKS  →  KnowledgeOS refinement
      ▲                                                                              │
      └──────────────────────────────────────────────────────────────────────────────┘
```

> ### **PublicDigit is the VALIDATION ENGINE for KnowledgeOS.**

***Each asset gains a distinct responsibility and the evidence flow between them becomes explicit — stronger than treating the three as independent projects.***

### 6.2 ⚠️ What this does NOT decide — **the loop is materially D-3**

| | |
|---|---|
| ⛔ **It does NOT decide EAD-1's D-3** | *"should the evolution rule become governing?" — **the loop above IS that rule, closed into a cycle**. Recording it as governing here would decide D-3 BY ASSERTION* |
| ⛔ **It does NOT decide D-5** | *the three names do not become governed terms by being given roles* |
| ✅ **What it IS** | **Phase III's OPERATING POSTURE** — how the work is organized, adopted at Authority direction |

> ### **⚠️ EAD-1 §E.1 found this link **NEVER TRAVERSED**: the corpus's own provenance runs the other way — the framework was built from REVIEW PRACTICE, not from product evidence.**

***So the loop is not a description of how the corpus came to exist. It is the FIRST TRAVERSAL, and Phase III is the traversal. That is precisely why D-3 must stay open: the traversal is the evidence that would decide it, and it has not happened yet.***

---

## 7. Operating Rules

| | |
|---|---|
| **The baseline is FROZEN** | *changes only via evidence → MCA-class assessment → CDR-class decision → issuance* |
| **Backlog items WAIT** | ***until they have a CONSTITUTIONAL REASON TO MOVE — not "every backlog item must eventually be completed"*** |
| **Deferred matters stay deferred** | *PMR-2 · PMR-3 · PMR-7b/7c · PMR-8 · ES-005.4 · ES-001..ES-006's adoption — each on its own stated condition* |
| **⛔ Phase III must not become Phase II under a new name** | ***If a Phase III act produces a governance artifact without producing operational evidence, that act has failed the mandate — regardless of the artifact's quality*** |
| **Administrative work is normal maintenance** | *editorial cleanup · repository organization · documentation · onboarding · indexes are no longer constitutional work* |
| **⭐ Effort goes to PublicDigit engineering** | ***no further governance document unless NEW EVIDENCE requires it. Per §6.1, PublicDigit is where the evidence is generated*** |
| **⭐ Observe during real feature work** | *did the governance help? · **which rule PREVENTED a defect?** · **which rule created UNNECESSARY FRICTION?** · which capability is missing? · **which assumption turned out WRONG?*** |
| **⏳ Give it TIME before changing KnowledgeOS** | ***months of real engineering, not weeks — the strengthened admission filter already requires REPEATED evidence, and a single project's early impressions are not repetition*** |

---

## 8. Next Steps

**Operational use begins.** *No further governance act is required to start.*

***The next governance commission should be triggered by evidence gathered during operational work — not by this charter, and not by the remaining backlog.***

---

*Traceability: **PKS PHASE III OPERATIONAL VALIDATION CHARTER ISSUED** (Authority mandate, 2026-07-31), label collision-checked under PMR-10 · **a NEW GOVERNANCE ACT establishing a new cycle's purpose and success criteria, expressly not a continuation of Phase II** · guiding principle: ***internal soundness establishes nothing about whether the methodology WORKS***, and Operational Evidence stands at zero-independent because **a certification dimension derives credibility from being outside the unilateral control of the programme it certifies — only USE can move it** · purpose, scope, success criteria and feedback path taken from the mandate as issued · **⚠️ §5.1 TWO GUARDS ADDED under the programme's own discipline: (1) the criteria must ADMIT NEGATIVE FINDINGS — *every criterion is written in the improvement direction, and a validation that can only observe success is not a validation* — with each negative form stated; (2) they are OBSERVATIONS, NOT TARGETS — *a criterion treated as a target can be met by changing behaviour to satisfy the measure*** · **§5.2 HONEST FAILURE DIRECTION named in advance: the baseline's likeliest failure is not being WRONG but being TOO EXPENSIVE FOR THE VALUE IT DELIVERS — correct governance no one can afford to follow** · **§7: backlog items wait for a constitutional reason to move; deferrals stay deferred; and *if a Phase III act produces a governance artifact without producing operational evidence, that act has FAILED THE MANDATE regardless of the artifact's quality*** · **⭐ §6.1 ADDED (Authority, 2026-08-01) — THE THREE ASSETS AND THEIR DISTINCT RESPONSIBILITIES: KnowledgeOS *defines* reusable engineering governance · PKS *holds* PublicDigit's structured knowledge · **PublicDigit is the REFERENCE IMPLEMENTATION and EVIDENCE GENERATOR — the VALIDATION ENGINE for KnowledgeOS, a proving ground rather than merely a consumer**, closing the loop KnowledgeOS → PublicDigit engineering → operational evidence → PKS → KnowledgeOS refinement** · **⚠️ §6.2 SCOPED: the loop IS materially EAD-1's D-3 ("should the evolution rule become governing?"), so recording it as governing would DECIDE D-3 BY ASSERTION — it is adopted as Phase III's OPERATING POSTURE only; D-3 and D-5 both stay OPEN, and the three names do not become governed terms by being given roles** · **⭐ EAD-1 §E.1 found this link NEVER TRAVERSED — the corpus's provenance runs the OTHER way, built from review practice rather than product evidence; the loop is therefore the FIRST TRAVERSAL and Phase III IS that traversal, which is exactly why D-3 must stay open: the traversal is the evidence that would decide it** · **§7 gains three rules: effort goes to PublicDigit engineering with no further governance document unless new evidence requires it · five observation questions during real feature work (which rule PREVENTED a defect · which created UNNECESSARY FRICTION) · MONTHS not weeks before changing KnowledgeOS, since the strengthened filter requires REPEATED evidence and one project's early impressions are not repetition** · operational use may begin; no further governance act is required to start.*
