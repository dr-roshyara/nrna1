# Classification → Placement — Operational Validation Record + ARB Acceptances

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No rollback · no document moved · no standard amended · no ruling minted.**

---

## 1. The record the ARB asked for — first successful execution

> ### `classification → placement` is now OPERATIONALLY VALIDATED, not merely ruled.
>
> **First execution, 2026-08-01.** A new artifact — the PKS documentation-integrity observation — was **classified first** (`scope: product-specific`, `domain: pks`), and its location was then **derived by the machinery**:
>
> ```
> php scripts/doc-placement.php --scope=product-specific --domain=pks
> docs/pks
> ```
>
> **The artifact was written where the resolver said, not where an author preferred.** No path was chosen, argued, or hard-coded.

**Why this is stronger than it looks.** Until now the model was a **governance rule**: an ADR invariant plus R-71, enforced by discipline. **It is now a rule with an execution record** — the distinction the platform itself draws at L5: *"one script proves possibility; routine use proves capability."*

**Honest boundary on the claim:** **this is one execution, not routine use.** It demonstrates the model **works**; it does not yet demonstrate it is **habitual**. **The claim recorded here is "operationally validated — first execution," and nothing stronger.**

**Second, weaker execution on the same day:** the resolver was also run on the ES candidate (§3) and returned **PENDING** — **and the response was to stop, not to invent a location.** **A model that refuses is as much evidence as a model that answers**, arguably more: it is the failure mode the tooling was built to prevent, exercised for real.

## 2. ARB acceptances — recorded

| # | Accepted |
|---|---|
| 1 | **The governance note stands; no revert.** Repository state ≠ governance sequence; the state was already correct, the deviation is a governance fact, and the two are not mixed |
| 2 | **The declarative migration registry is the canonical mechanism** for repository migration history |
| 3 | **The PKS observation is accepted as operational evidence** |
| 4 | **`classification → placement` is recorded as operationally validated** (§1) |
| 5 | **ENG-008 and ENG-009 remain deferred** until OQ-5 and the KnowledgeOS structure resolve |
| 6 | **ENG-011 opened** — extend link validation beyond `docs/knowledge/` |

**Consistent with R-34, these are recorded as the Chair's acceptances. They are not minted as register rulings, and R-65…R-71 remain drafts.**

## 3. ENG-010 reclassified, and the reusable rule recorded as a candidate

**ENG-010 retitled: *"Documentation integrity — indexes reference documents that were never written."*** **The distinction is correct and it changes what a fix would be:** the defect is that **indexes promise artifacts that never existed** — a repository integrity problem — and **the debt is only its consequence.** Fixing the debt (writing 30 documents) would not fix the integrity defect; only validation would.

**The reusable rule, recorded as a candidate — not adopted:**

> *A documentation index shall not reference an artifact that does not yet exist, unless the reference is explicitly marked as planned.*

### ⚠️ And it landed in the unruled cell

**The candidate is cross-product at research maturity. The resolver returns PENDING for exactly that classification** — the same cell as `engineering/knowledge/methodology/Layer_Verification_Rule.md`.

**So it was recorded inside the PKS observation** — the evidence that produced it, already correctly placed — **rather than given a new file with no ruled home.**

> ### The ground for deferring the stewardship decision has changed
>
> **Stewardship was deferred on an explicit and correct basis:** *"the evidence is one artifact,"* and R-29/R-37 reject generalizing from a single example.
>
> **The unruled cell now holds two artifacts.** The second arrived within a day, from an unrelated commission, **as a direct consequence of an ARB instruction** — the ARB asked for a candidate, and the candidate had nowhere to go.
>
> **That is the operational evidence the deferral was waiting for.** Not a request to decide — **a report that the stated precondition is now met.**

## 4. ENG-008 stays closed for the stated reason

**Agreed, and the reason is worth recording because it is not "later":** externalizing the confidence policy would introduce **abstraction before a second consumer exists.** `link-check.php` is the only consumer today. **R-29/R-37's reversed burden of proof applies to the platform's own tooling as much as to its architecture** — and this is the same discipline that kept the documentation roots as reserved namespaces rather than pre-created directories.

**The trigger is explicit: a second consumer of the confidence model.**

## 5. ENG-011 — the finding behind it

**Opened as directed.** The rationale, stated as the ARB put it: **the biggest finding was not the 47 missing documents — it was that green lint did not imply healthy documentation.**

**The measurement:** `knowledge-lint` reported **9** errors while the repository contained **121** broken links, because it validates `docs/knowledge/` only. **`developer_guide/`, `architecture_legacy/`, `docs/` outside `knowledge/`, and the three new domain roots have no link validation at all.**

**ENG-011 is also a prerequisite for one of ENG-010's dispositions:** *accept as tracked debt* is only honest if the count stays visible, which requires validation over the areas that carry it.

## 6. Implementation closed

**No engineering work reopens.** Remaining items are governance and KnowledgeOS evolution:

| Open | Blocks |
|---|---|
| **OQ-5** — is KnowledgeOS the Engineering Platform? | ENG-009 · what `docs/knowledgeos/` holds |
| **The stewardship decision** | the PENDING cell — **now two artifacts** |
| **Does R-37 bind `docs/`?** | Phase 2 migration |
| **Applying the ES-005 amendment** (one ruling) | the amended derivation inputs |
| **Minting R-65…R-71** | citability of seven approved conclusions |
| **The 6 ambiguous references** | a human choice, carried by ENG-010 |
| **Phase 1 classification map** | unblocked, not started |
| **Five principles into the ADR** (in place of DAP-001) | one word |

```
knowledge-lint     ✅ All documents pass (0 errors, 0 warnings)
link-check         53 broken: 6 ambiguous · 47 missing · 0 deterministic
doc-placement      9/9 self-test · registry and roots consistent
```

---

**Traceability:** the roots ADR (invariants) · **R-71** (placement derived) · **R-34** (why acceptances are not minted) · **R-29 / R-37** (reversed burden of proof — ENG-008's deferral, and the *n = 1 → n = 2* change) · **ES-006.1** (the ladder the candidate must climb) · `docs/pks/2026-08-01-documentation-debt-observation.md` · `docs/implementation/backlog/BACKLOG.md` ENG-008/009/010/011 · `scripts/doc-placement.php` · `scripts/link-check.php`. **No rollback · no document moved · no standard amended · no ruling minted.**
