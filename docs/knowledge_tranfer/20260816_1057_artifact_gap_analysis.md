# Artifact Gap Analysis — What Exists, What Is Blocked, What Is Missing

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — gap analysis, not new design |
| **Input adjudicated** | The Remaining-Architecture-Work input (conversation, 2026-08-16) — Artifacts A–H and the 12-item sequence. External citations (`github.com/kgrzybek`, `atlan.com`, `infoq.com`, `mdpi.com`) recorded as **input, evidence-graded by their authors, not verified here** |
| **Companions** | the nine 2026-08-16 documents (2,911 lines) + `..._1040` decision register |
| **Date** | 2026-08-16 |

> **Verdict.** The plan is sound and its final correction is the best summary anyone has written of this corpus. But of its eight artifacts: **one already exists, one is redundant with it, one is assemblable today, one *is* `OQ-8`, and four are blocked.** Its 12-item sequence is correctly ordered and — with two exceptions — not yet startable.

---

## 0. The correction worth keeping above everything else

> *"Stop treating the system as a set of services that store knowledge and instead define it as a **governed state-and-analysis system**."*
>
> ```
> Authoritative knowledge + evidence + applicability + authority
>   + temporal validity + formal analysis + governed resolution + traceable delivery
> ```

**Adopted.** This is the most compact accurate statement of the target in the corpus, and it independently reaches `..._0914` §2's Core Domain claim — *the value is not storing rules; it is determining whether one applies here, who authorised it, what supports it, and whether it contradicts something else.*

Two arrivals, different routes, same conclusion. It should be v3 §1's vision sentence when v3 is next revised.

---

## 1. The eight artifacts, adjudicated

| | Artifact | Status | Detail |
|---|---|---|---|
| **A** | Architecture Constitution | 🚫 **BLOCKED ×2** | `..._0905` §6: authoring criteria into `AR-1` is reserved to the Authority (`AFV-F4`, `OQ-7`). Its content list — *"modular monolith first · hexagonal architecture"* — is also `OQ-1` |
| **B** | Bounded Context & Module Map | 🚫 **BLOCKED** | `OQ-4`. Its six modules are the **sixth** partition to circulate (4 → 6 → 7 → 4 → 6 → 6, none identical). No merge/split language analysis accompanies any of them, and `pks_progress` records one for the certified four |
| **C** | Invariant-to-Aggregate Matrix | ✅ **EXISTS, partial** | `..._1023` — 32 invariants, 14 fields, controlled vocabularies, ownership test. Covers **3 of the 9** proposed aggregates; §5 there lists each remaining one with its blocker |
| **D** | Rule Semantics Specification | ⏸️ **IS `OQ-8`** | Its content list — subject types · target paths · operators · modalities · applicability syntax · scope · temporal model — is exactly `OQ-8`'s scope. **This is the next decision, and D is its deliverable** |
| **E** | Event Contract Catalogue | ◐ **PARTIAL** | `..._1044` §8 has the event set and §3 the four categories. Missing per-event: schema version · transaction boundary · consumers · retry policy · retention. Gated by `OQ-30` |
| **F** | Provenance & Audit Specification | ◐ **PARTIAL** | v3 §5.3 (8 attributes + `authored_by`), `EVT-5` retention, `causation_id`/`correlation_id`. Missing: tamper evidence, access control. Gated by `OQ-25`, `OQ-32`, `OQ-35` |
| **G** | Projection Contract | ✅ **ASSEMBLABLE NOW** | `INV-PRJ-001`/`-002` + `ProjectionFreshnessPolicy` + `EVT-3` + `ProjectionBuild` fields already specify source-of-truth, snapshot strategy, freshness, rebuild, failure state and publication gate. **Nothing blocks writing it** |
| **H** | Enforcement Matrix | ⚠️ **REDUNDANT** | Its four columns — rule · enforcement point · mechanism · failure — are a strict subset of C's fourteen (`candidate_owner`, `enforcement_mechanism`, `enforcement_layer`, `failure_behavior`). Writing H creates a second home for rows C already owns |

**Artifact H is the corpus's own `DUPLICATES` case.** The input calls it *"the missing bridge between architecture and your earlier fitness assessment"* — but C already spans that bridge, and its `test_reference` column plus the `Accepted`-requires-a-test rule is precisely the fitness linkage H is reaching for. **Recommendation: drop H; add its five example rows to C.**

**Score: 1 exists · 1 assemblable · 1 redundant · 1 is the next decision · 4 blocked.**

---

## 2. Three things this input contributes that nothing else has

### 2.1 A fourth non-equivalence

> *"A similarity score is only a relevance signal. **It is not permission, authority, applicability, or truth.**"*

The corpus already holds a family of these, from the workflow model:

```
ACTIVE ≠ AUTHORIZED      OWNERSHIP ≠ AUTHORITY      EXISTENCE ≠ PERMISSION
```

**`RELEVANCE ≠ AUTHORITY` joins them**, and it is the one that matters most for a delivery plane containing `AIContextPackage`: retrieval systems fail by treating a similarity ranking as a warrant. Adopted as a first-class invariant of the retrieval boundary.

### 2.2 Relationship-level permission — genuinely new

> *"A graph projection must not reveal restricted relationships merely because the connected nodes themselves are visible. Permission metadata needs to propagate to derived nodes, edges, chunks, and context packages."*

Nothing in ten documents addresses this. It sharpens `OQ-25` from *"is there read access control?"* to a specific structural requirement, and it interacts with `DR-1` in a way the corpus has not considered:

> **A projection is derived, and derivation may narrow visibility but must never widen it.** An edge inferred from two visible nodes can disclose a restricted fact that neither node discloses alone.

This is the third arrival at `OQ-25` — my absence-finding (`..._1029` §8), `ContextAssemblyPolicy` (`..._1034` §5), now the propagation requirement. **`OQ-25` is now the best-specified unopened question in the corpus.**

### 2.3 The defer list — eight subtractive rulings

> Defer: generalized agent autonomy · broad organizational knowledge management · automatic rule activation from free text · full graph database commitment · event sourcing · microservice deployment · generic workflow platform · universal ontology.

**All eight adopted.** Every one survives `OQ-1` in either direction, because a "no" needs no technology to be true — the corpus's fourth subtractive family, and its largest.

Two are notable: *broad organizational knowledge management* is `OQ-2` answered conservatively, consistent with v3 §1's rejection of the multi-organization framing. And *automatic rule activation from free text* sits in tension with the input's own `RuleNormalizer` (§4: *"convert structured or extracted rule candidates to canonical form"*) — extraction is deferred, yet a service is proposed to normalize extracted candidates. Recorded as `OQ-37`.

---

## 3. Where it pre-empts `OQ-1` — third occurrence

§1.2 *"Decide the authoritative source of truth"*:

```
Authoritative:  PostgreSQL domain state · immutable evidence objects
                governance decisions · audit/provenance records
```

**The framing is right and the answer is not the input's to give.** Naming a source-of-truth policy as a required decision is correct — that *is* `OQ-1`, stated as a decision rather than as a contradiction. But then PostgreSQL is asserted, for the third time in this corpus, against `what_is_pks_v1` §5.1 and `pks_progress` §5.1 (*"The PKS Is Not Software"*), with no acknowledgement that the question is open.

**The derived list, by contrast, is adopted verbatim** — graph, search, vector, AI context, architecture views, dashboards are all projections. That half matches v3 §12 exactly. And the accompanying warning is the corpus's own `DR-1`:

> *"The graph must not silently become an independent authority."*

**Adopt the requirement to decide; decline the pre-emption.** The four findings in `..._1044` §12.1 and §13.4 — that content hashing, analysis snapshots, the outbox and idempotent rebuilds are all *free* under the specifications answer — are material to this decision and are not reflected in the input's framing.

---

## 4. The 12-item sequence, re-ordered by what is startable

The input's ordering is sound. Applying the register's dependency structure, only three items can begin:

```
STARTABLE NOW
  ·  Artifact G — projection contract          (nothing blocks it)
  ·  item 11    — choose the narrow product slice
                  (its own §15 statement is adoptable as written)
  ·  drop Artifact H; fold its rows into C

BLOCKED ON ONE DECISION EACH
  OQ-8   → Artifact D → then items 3, 5, and the rest of C
  OQ-1   → Artifact A, the infrastructure tables, items 7 and 9
  OQ-4   → Artifact B → item 1
  OQ-25  → Artifact F, item 8, the security model (§13)

NOT STARTABLE
  items 2, 4, 6, 10   — each waits on one of the four above
  item 12             — "the bootstrap instrument" is undefined in this corpus
                        and appears here for the first time (OQ-38)
```

**Item 12 is correct in placement and undefined in content.** *"Only then begin the bootstrap instrument"* is the right instinct — build last — but no prior document names a bootstrap instrument. Recorded rather than inferred.

---

## 5. §14's fitness model — right shape, same timing ruling

Its five categories (domain · analysis · provenance · projection · event integrity) and its requirement that each fitness rule carry *a test, an enforcement point, a failure mode, an audit record, and an owner* are essentially C's column set applied to checks rather than invariants. Consistent, and adopted as C's extension.

But the timing ruling from `..._0905` §0 stands unchanged:

> **A gate may only enforce an architecture that a recorded human act has decided. Gates follow ratification; they never precede it.**

Its own observation — *"the existing assessment found that responsibilities were advisory"* — is accurate and is not a defect to be fixed by adding gates. It is the corpus's recorded position: `..._1029` §3 shows that `HARD` versus `ADVISORY` is a business decision (`OQ-24`) nobody has made. **Enforcement cannot be specified before hardness is chosen.**

---

## 6. Corrections and additions

| Target | Change |
|---|---|
| v3 §1 | Vision sentence → the *governed state-and-analysis system* formulation (§0) |
| v3 §9 | **+ `RELEVANCE ≠ AUTHORITY`** as a fourth non-equivalence (§2.1) |
| `..._1023` C | **+ Artifact H's five rows**; H itself dropped as redundant (§1) |
| `..._1023` C | **+ §14's five fitness categories** as an extension of the `test_reference` column (§5) |
| `..._1029` §8 / `OQ-25` | **Sharpened:** permission must propagate to derived edges; derivation may narrow visibility, never widen it (§2.2) |
| `..._1040` register | Artifact G and the product slice move to *startable now*; `OQ-2` partially answered by the defer list (§2.3) |

---

## 7. Open questions added

| # | Question | Decider |
|---|---|---|
| **OQ-37** | Free-text rule extraction is deferred, yet `RuleNormalizer` normalizes extracted candidates. Which holds? | Product boundary (§2.3) |
| **OQ-38** | What is "the bootstrap instrument"? It appears in item 12 and nowhere else in the corpus | Definition required before sequencing |

---

## 8. Bottom line

Eleven inputs, ten documents, 2,911 lines, **38 open questions, one answered.**

This input asked the right question — *what remains?* — and the honest answer is smaller than its list: **two artifacts can be written today** (the projection contract, and the product-slice statement, which its own §15 already drafts), **one should be deleted** (H, redundant with C), and **five wait on four decisions**, all of which the register recorded as decidable without further analysis.

The corpus does not need a twelfth input. It needs `OQ-1` and `OQ-8`.

`OQ-1` is now referenced **49 times across five documents' blocked sections** and has never been asked. `OQ-8` has **four independent derivations** and is Artifact D's entire content. Neither requires evidence, research, or another pattern catalogue — only a decision.

And the corpus's one completed cycle remains instructive: `OQ-18` was raised at 09:22 and answered at 10:23, **by building the artifact rather than discussing it further.**

---

*Adjudicated against `docs/knowledge_tranfer/` and the nine prior 2026-08-16 documents. The input and its external citations are recorded as **input**; this document is the assessed artifact they produce. §6 lists the corrections other documents require.*

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
