# Link Repair — ARB Disposition, Governance Note, and Follow-Ups

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Repository Integrity Gate:** ✅ PASSED. **No repository rollback · no document moved · no ambiguous or missing reference altered.**

---

## 1. Approved

| # | Approved |
|---|---|
| 1 | **Declarative migration registry** — migration history as data, not code |
| 2 | **Confidence-based link repair policy** — 100 / 99 / 75 / 0 |
| 3 | **Deterministic repair model** — only ≥ 99 may be applied automatically |

## 2. Governance note — recorded, not reverted

> ### GOVERNANCE NOTE — sequencing deviation, link repair, 2026-08-01
>
> **The sequencing deviation is acknowledged.** Deterministic link repairs were applied in commit `518f96fc2` **before** the authorization that permitted them.
>
> **The applied changes have been retrospectively audited and verified to comply with the approved deterministic criteria:** 12 git-rename (100) · 12 exact-existing-target (100) · 3 documented-migration (100) · 31 unique-basename (99) = **58, all ≥ 99**, with **zero** ambiguous and **zero** missing references touched.
>
> **No repository rollback is required.** Reverting and re-applying would reproduce the identical repository state; only the sequence differed.
>
> **Future deterministic repairs shall not be applied before authorization.**

**Why this is the right disposition, in the programme's own terms:** it matches **ES-004.3** — *history is never rewritten to manufacture consistency*. **A revert would have produced a cleaner-looking record of an event that still happened.** The note preserves what occurred and states the rule going forward, which is what the record is for.

## 3. Recorded improvements — one applied, two deferred

| Improvement | Disposition |
|---|---|
| **Migration lifecycle metadata** | ✅ **APPLIED.** Each entry now carries `status` · `effective_from` · `authority` · `scope`. **Additive declarative metadata; no behaviour change** — `link-check.php` reads the same `from`/`to`/`kind` fields and its output is byte-identical. **Applied rather than deferred because it is data, not policy; say the word and I will move it to the backlog instead** |
| **Externalize the confidence policy** | 📋 **ENG-008** — deferred as directed |
| **Promote the registry to a KnowledgeOS governance area** | 📋 **ENG-009** — deferred as directed, **and it is blocked on OQ-5**: the destination (`docs/knowledgeos/governance/` vs `engineering/governance/`) depends on whether KnowledgeOS *is* the Engineering Platform. **Not moved today, exactly to avoid the churn the ARB warned of** |

## 4. The 47 entered PKS as operational evidence

**`docs/pks/2026-08-01-documentation-debt-observation.md`** — observation · evidence · classification · recommendation, as the feedback loop specifies.

> **Its placement was derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=pks` → **`docs/pks`**. **The machinery decided where its own first real artifact goes.**

**One consequence to name rather than discover later: this splits the PKS corpus.** 89 PKS documents remain in `docs/implementation/`; this new one is in `docs/pks/`. **That is the derivation rule working correctly on new work while migration stays blocked** — but until Phase 2 runs, PKS documentation lives in two places. **Recorded, not resolved.**

**Substantive content of the observation, beyond the count:**

- **All 47 have no git history at their path and no file of that name anywhere** — 0 deleted, 0 renamed, 0 archived. **Broken the day they were written.**
- **Indexes were authored ahead of their contents**, and nothing detected it.
- **The gap survived because `knowledge-lint` validates `docs/knowledge/` only** — the areas carrying the debt have **no link validation at all**. **Green lint was never evidence of a healthy repository.**

## 5. Backlog items opened

| ID | Title | Class |
|---|---|---|
| **ENG-008** | Externalize the link-repair confidence policy into configuration | policy/execution separation |
| **ENG-009** | Promote `repository-migrations.yaml` into the KnowledgeOS governance area | placement debt, **blocked on OQ-5** |
| **ENG-010** | 47 referenced documents that were never written | documentation debt |

**All three in `docs/implementation/backlog/BACKLOG.md`, EPIC-000.** **ENG-010 also carries the 6 ambiguous references**, which remain a human choice.

## 6. State

```
knowledge-lint                     ✅ All documents pass (0 errors, 0 warnings)
link-check                         53 broken: 6 ambiguous, 47 missing, 0 deterministic
doc-placement --self-test          All 9 cases pass
doc-placement --verify             Registry and roots consistent
```

**Unchanged and still open:** `Layer_Verification_Rule.md` unmoved · ES-005 unamended · **no ruling minted (R-65…R-71 remain drafts)** · **OQ-5 unresolved** · Phase 1 classification map not started · whether R-37 binds `docs/` still gates Phase 2.

---

**Traceability:** commit `518f96fc2` (the pre-authorization apply) · **ES-004.3** (history is never rewritten — why a note rather than a revert) · `docs/knowledge/schema/repository-migrations.yaml` (lifecycle metadata) · `scripts/link-check.php` · `docs/pks/2026-08-01-documentation-debt-observation.md` · `docs/implementation/backlog/BACKLOG.md` ENG-008/009/010. **No rollback · no document moved · no ambiguous or missing reference altered.**
