# May ES-005 Be Amended Under R-37? — Authority Determination

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** Principal Architect Instruction — *Determine Whether ES-005 May Be Amended Under R-37*. Authority only; the amendment package is not revised.
**Repository Integrity Gate:** ✅ PASSED. **No standard edited · no ruling created · no file moved · no directory created.** **The amendment package is untouched** — per the commission, and per **ES-004.3** (decision text is never rewritten; corrections are annotations).

---

> # DETERMINATION — **permitted, and only through explicit Authority issuance recorded as a ruling.**
>
> **Of the four options the commission offered, the evidence selects the second: *permitted only through an explicit ARB authorization.*** **Not already permitted. Not requiring the freeze to be lifted. Not prohibited until the freeze ends.**
>
> **The precedent is exact, recent, and I asserted it did not exist.**

---

## 0. Correction — my own claim in the package is refuted

**The amendment package states:** *"I can find no precedent for a standards amendment under the freeze."*

**That is wrong.** **Engineering Standards have been amended repeatedly since R-37 (2026-07-10):**

| Date | Commit | ES amended |
|---|---|---|
| **2026-07-30** | `ES-004.3 Artifact Lifecycle Consistency adopted (PA instruction, R-41)` | **ES-004 — a new rule added** |
| **2026-07-30** | `ES-004.3 refined role-based (ARB review)` | **ES-004 — further amendment** |
| 2026-07-26 | `DDD Tactical Governance Principles promoted (R-39 exception)` | — (promotion, R-39) |
| 2026-07-12 | `register Implementation Architecture Constitution as a pointer in ES-002` | **ES-002** |
| 2026-07-11 | `plan naming overridden … ES-004.2 + both pointers aligned` | **ES-004** |
| 2026-07-11 | `ARB harvest amendment — ES-006.4 Harvest Question` | **ES-006 — a new rule added** |

> **Why I missed it: I searched the freeze-note convention and the rulings register, and never ran `git log` on the ES files themselves.** The precedents are not labelled as freeze exceptions — **they are labelled as amendments, authorized by ruling.** **The search that would have found them is the obvious one, and I did not run it.**

**Recorded here rather than by editing the package**, per the commission and ES-004.3.

## 1. Deliverable 1 — Authority Analysis

### 1.1 What the freeze actually constrains

| Instrument | Text | Bearing |
|---|---|---|
| **R-37** operational terms | *"until the retrospective, `engineering/` permits only bug fixes · broken-link fixes · typo corrections"* | On its face, excludes an amendment |
| **R-37** release conditions | (1) C3 passes · (2) PB-004 completes · (3) the retrospective runs | **None recorded as met** — R-38 still sequences *"ratification batch → C3 → STABLE → pilot → retrospective."* **The freeze is in force** |
| **R-38** conceptual freeze | *"**no new** concepts, **standards**, decisions, or architectural subsystems — changes enter **only through operational evidence via the ES-006.1 promotion ladder**"* | **Names an entry path rather than sealing the corpus.** And it bans **new standards**, not new **clauses in existing ones** |

### 1.2 The decisive distinction — *new standard* versus *new clause*

**R-41 draws it explicitly, in its own text:**

> *"Parsimony honored: rule text hosted ONCE as **ES-004.3** (**documentation standard, not a new standard document**); runtime pointers only."*

> **R-38 is satisfied by hosting a rule inside an existing standard.** That is exactly what the ES-005 package does: it amends **ES-005.3** and **ES-005.1** and **creates no new standard document.**

### 1.3 Why an Authority act is not an "exception" to the freeze

**R-37 is itself a ruling of the Decision Authority.** **The authority that issued the freeze can act within it by issuing another ruling** — that is not an exception to the freeze, it is the freeze's author exercising the same power.

**Practice shows both framings, and the label is not what matters:**

| Ruling | Self-description |
|---|---|
| **R-39** | *"early promotion is a **recorded governance exception**"* |
| **R-41** | **no exception language at all** — simply *"adopted … (Principal Architect instruction)"* |

> **The common element in every precedent is not a freeze-exception label. It is explicit issuance — R-34's requirement, applied.** **What R-37 actually constrains is self-directed editing; it has never constrained the Authority's own acts.**

### 1.4 The status fact that lowers the bar further

**Every Engineering Standard is `PROPOSED`. Not one is ratified.**

| ES-001 | ES-002 | ES-003 | ES-004 | ES-005 | ES-006 |
|---|---|---|---|---|---|
| PROPOSED | PROPOSED | PROPOSED | PROPOSED | **PROPOSED** | PROPOSED |

**Authority on each: *"Decision Authority (ARB)."*** **So amending ES-005 revises a pre-ratification draft awaiting the ratification batch — it does not modify ratified canon.** **This does not remove the need for authorization** (R-34 is indifferent to status) — **but it does mean no ratified rule is being disturbed, which is a materially lower-risk act than the package implied.**

## 2. Deliverable 2 — Applicable Precedents

| # | Precedent | What it establishes |
|---|---|---|
| **P-1 ⭐** | **R-41 / ES-004.3** (2026-07-30) — *"adopted as a permanent documentation standard (**Principal Architect instruction**)"*; provenance = the WP-1 closure inconsistency; rule text hosted once inside ES-004 | **The controlling precedent. A new rule was added to an ES under the freeze, authorized by a PA instruction recorded as a ruling, grounded in observed operational failure. This is the ES-005 case in every structural respect** |
| **P-2** | **ES-004.3 refined** (same day, *"ARB review"*) | **Amendment-then-refinement is itself normal** — a package may be revised after issuance without a fresh freeze debate |
| **P-3** | **ES-006.4 Harvest Question** — *"ARB harvest amendment"* (2026-07-11) | A **new numbered clause** added to an ES by ARB act |
| **P-4** | **ES-004.2 plan naming** — *"overridden … (Decision Authority)"* (2026-07-11) | An ES clause **overridden** by DA act; runtime pointers realigned in the same breath |
| **P-5** | **R-39** (2026-07-26) | Where authority prefers, an act may be recorded **as an explicit exception with its evidence base stated** — the more conservative of the two available styles |
| **P-6** | The **commissioned-exception freeze notes** (baseline · theory reference) | Cover **descriptive** artifacts only (*"introduces no new … rule"*). **They do not cover an amendment — my package was right about that, and wrong to conclude nothing else did** |

**Counter-evidence check: is there any precedent of an ES amendment being *refused* on freeze grounds?** **None found.** The freeze has been cited to reject **unevidenced expansion** (R-29/R-37's reversed burden of proof, e.g. the OQ-ENG-002 E-4 rejection) — **never to block an authorized, evidence-grounded amendment.**

## 3. Deliverable 3 — Transition Sequence

**The Chair's separation is confirmed by the record: acceptance and authorization-to-edit were distinct acts in P-1.** R-41 was the authorization; the commit was the application.

```
Evidence of insufficiency                     ← the 49% measurement (done)
        ↓
Amendment package prepared                    ← done, not applied
        ↓
ARB review → accepted | revised | rejected    ← ARB act #1   (package quality)
        ↓
Authority issuance: a ruling that states the amendment
and names its provenance                      ← ARB act #2   (permission + rule text)
        ↓
Apply: edit ES-005 to host the rule text ONCE ← implementation of act #2
        ↓
Realign runtime pointers (CLAUDE.md, index)   ← same breath, per P-4
        ↓
Record the transitional non-conformance       ← the 92 artifacts, per R-39's pattern
```

**Two acts, not one — and P-1 shows why the second cannot be inferred from the first:** R-41 *states the rule text itself*. **The ruling is where the rule lives; the ES edit merely hosts it.**

## 4. Deliverable 4 — Governance Recommendation

> **Recommendation: ES-005 may be amended, by explicit ruling, without lifting or amending R-37.**

**Grounds, all from existing evidence:**

1. **P-1 is on all fours with this case** — ES clause added, under the freeze, by PA instruction recorded as a ruling, grounded in observed operational failure.
2. **R-38's own entry path is satisfied** — the amendment enters *"through operational evidence"*: the 49% measurement, and the 92 misrouted documents.
3. **No new standard document is created** — R-38's actual prohibition is untouched.
4. **No ratified rule is disturbed** — ES-005 is `PROPOSED`.
5. **R-37's burden of proof is discharged by measurement, not preference** — the test R-29/R-37 actually apply.

**What I would *not* recommend, and why:**

| Not recommended | Reason |
|---|---|
| **Treating the amendment as already permitted** | R-34 forbids authority by inference. **Every precedent had an explicit act.** |
| **Lifting or suspending R-37** | Unnecessary and disproportionate — **no precedent ever required it**, and the freeze protects things this amendment does not touch |
| **Waiting for the retrospective** | Would leave a standard whose central clause has **no referent** in force for the whole waiting period, while 92 artifacts accumulate against it |
| **A generic "standards may be amended" rule** | **ES-001.1 rule parsimony**, and R-38's precedent of refusing consolidating rulings that add nothing. **Per-amendment issuance is already the working mechanism** |

## 5. Deliverable 5 — Required Authorization

> ## The answer to the success-criterion question
>
> **What explicit authorization is required before ES-005 may be modified?**
>
> **One ruling, issued by the Decision Authority, which (a) accepts the amendment package, (b) states the amended rule text, and (c) records its provenance. Nothing further — no freeze amendment, no exception instrument, no new governance mechanism.**
>
> **That is precisely the form of R-41, and R-41 is two days old in programme time.**

**What the ruling must contain, derived from P-1 and P-4:**

| Element | Because |
|---|---|
| **The rule text itself** | R-41 states it; the ES edit only hosts it once (ES-005.4) |
| **Provenance** | R-41 names the WP-1 inconsistency; here: the **49% measurement** and the **92 misrouted documents** |
| **A parsimony note** | *"hosted once as ES-005.3 / ES-005.1 — amendment of existing standards, no new standard document"* — the sentence that satisfies R-38 |
| **Disposition of the ES-005.1 consequential amendment** | flagged in the package as outside its five scope items; **the two cannot be issued in either order without a window of inconsistency** |
| **The PENDING cell** | explicit: `cross-product` + `research` awaits the stewardship decision; pre-amendment behaviour stands |
| **The transitional non-conformance** | the 92 artifacts, recorded per R-39's pattern rather than hidden |
| **Runtime pointer realignment** | per P-4, in the same act |

**Still out of scope of that ruling, and unchanged:** Package 2 remains blocked on *"does R-37's reorganization clause bind `docs/`?"* — **a separate question this determination does not touch, and one that the legacy-folder ADR committed today sits inside.**

---

**Traceability:** **R-37** (operational terms; release conditions unmet; freeze in force) · **R-38** (bans *new standards*, names the ES-006.1 entry path) · **R-34** (authority only by explicit issuance) · **R-29** (reversed burden of proof) · **R-41 / ES-004.3** (**the controlling precedent**) · **R-39** (recorded-exception style) · **ES-006.4**, **ES-004.2**, **ES-002** amendments (further precedents) · **ES-001.1** (rule parsimony) · **ES-005.4** (rule text once) · **ES-004.3** (why this correction is an annotation, not a rewrite) · all six ES documents `PROPOSED`. **Corrects** a claim in `2026-08-01-es005-amendment-package.md` **without editing it**. **No standard edited · no ruling created · no file moved · no directory created.**
