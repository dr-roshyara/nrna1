# Documentation Roots — Implementation Verification Report

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** Principal Architect Instruction — *Implement ADR "Documentation Roots and Artifact Placement"*, within approved scope only.
**Result:** ✅ **All five acceptance criteria met.**

---

## 1. Deliverable 1 — Documentation roots created

| Root | First artifact | ES-005.2 |
|---|---|---|
| `docs/publicdigit/` | `README.md` | ✅ satisfied |
| `docs/knowledgeos/` | `README.md` | ✅ satisfied |
| `docs/pks/` | `README.md` | ✅ satisfied |

> **Why each root has a README rather than being empty.** **ES-005.2: *"a directory exists only when its first artifact arrives … empty directories are removed on discovery."*** Three empty roots would have been speculative directories — the exact thing the folder rule forbids. **The README *is* the first artifact**, and it states what the root is for, points at the registry, and records that nothing has been migrated into it. Git could not have tracked empty directories anyway; this satisfies the rule rather than working around it.

**Authorization:** the approved ADR. **These are not new top-level folders, and nothing was reorganized** — so R-37's *"no new top-level folders"* and *"no more document reorganizations"* clauses are both untouched, and the unresolved question of whether the latter binds `docs/` **does not gate this work.**

## 2. Deliverable 2 — Documentation-generation configuration

**Following the recommendation to avoid hard-coded paths: one registry, consulted by everything.**

| Asset | Change |
|---|---|
| **`docs/knowledge/schema/documentation-placement.yaml`** *(new)* | **The single source of truth** — domains with their roots, plus the derivation rules as data. Lives beside the existing controlled vocabularies (`statuses.yaml`, `authorities.yaml`, `bounded-contexts.yaml`), which is where enumerations already live in this repository |
| **`scripts/doc-placement.php`** *(new)* | **The single consulted mechanism.** `--scope/--maturity/--domain` → derived location · `--list` · `--self-test` · `--verify`. House style follows `knowledge-lint.php` (Symfony YAML, same bootstrap) |
| **`package.json`** | `docs:placement`, `docs:placement:verify` |
| **`.claude/scripts/engineering-placement-guard.sh`** | **Extended, not duplicated.** A `docs/` branch now fires on the domain-mixed locations and points at the resolver. **Under `engineering/` the governing rules are prose, so the hook asks generic questions; under `docs/` the derivation is executable, so it points at the resolver instead of asking.** Roots are read from the registry at runtime — **the hook hardcodes none** |
| **`.claude/CLAUDE.md`** | One pointer: *placement is derived, resolve it, never hard-code a root.* **No rule text restated** |
| **`docs/knowledge/_meta/knowledge-card.template.md`** | Comment noting a bounded context is **not** a domain, and pointing at the resolver. **A `domain:` field was deliberately NOT added** — it belongs to the prepared-but-unapplied ES-005 amendment, and adding it now would implement an unissued amendment |

**Not chosen as the registry home, with reasons:** `.claude/platform/registry.yaml` — **R-42** confines it to AI-platform assets · `.claude/` generally — **ES-005.1** makes it the runtime mount, and placement configuration is not session state · `engineering/` — this is product-side documentation configuration, and R-37 constrains that tree.

## 3. Deliverable 3 — Migration-support assets

| Asset | Purpose |
|---|---|
| **`docs/knowledge/_meta/classification-map.template.md`** | The Phase 1 deliverable's shape: per-document classification, derived location, and a **delta** column (`none`/`move`/`hold`/`unclassifiable`). Carries the measured migration cost — **501 inbound references, 119 to `PKS_*`** |
| **`docs/knowledge/_meta/documentation-migration-checklist.md`** | Phase 2 procedure, **gated on four preconditions** and batched one domain and one source directory at a time, one commit per batch so a batch can be reverted alone. Full rollback list per the ADR |

> **A per-file placement *validator* was deliberately not built.** It would need each artifact's declared classification — which is exactly what the Phase 1 map produces. **Shipping a stub that validated nothing would have been worse than naming the dependency.** `--verify` therefore checks registry and root integrity only, and says so.

## 4. Deliverable 4 — Verification of automatic routing

**Executed, not asserted.** `php scripts/doc-placement.php --self-test`:

```
✅  PublicDigit product doc           -> resolved  docs/publicdigit
✅  KnowledgeOS domain doc            -> resolved  docs/knowledgeos
✅  PKS domain doc                    -> resolved  docs/pks
✅  Adopted cross-product standard    -> resolved  engineering
✅  Qualified cross-product method    -> resolved  engineering
✅  Cross-product RESEARCH            -> pending   (unruled — caller must stop)
✅  Active session state              -> resolved  .claude
✅  Unknown domain                    -> error     —
✅  Missing domain                    -> error     —

All 9 cases pass.
```

**The three commissioned domains route correctly. Two behaviours matter as much as the routing:**

- **the unruled case refuses to answer** — exit code **2**, with the stewardship question printed and *"do not guess a location"*. **Tooling that invents a destination is how the mixing happened in the first place;**
- **cross-product artifacts still route to `engineering/`** — so **Engineering Standards and methodology are not pulled into a `docs/` root**, which is what OQ-5 leaves open.

**Guard routing, all seven paths verified:**

| Path | Result |
|---|---|
| `docs/implementation/NEW.md` | **fires** — domain-mixed location |
| `docs/SOMETHING.md` | **fires** — root-level, no clear domain |
| `docs/pks/whatever.md` | quiet — already in a declared root |
| `docs/knowledge/portal/x.md` | quiet — other subtree, until migration |
| `engineering/knowledge/methodology/X.md` | **fires** — canon directory |
| `engineering/verification/reports/r.md` | quiet — verification output |
| `app/Foo.php` | quiet — not documentation |

**A defect found and fixed during this verification:** the first implementation used `case`-glob matching, and bash globs span `/`, so `docs/pks/whatever.md` matched `docs/*.md` and fired inside a declared root. **The path test moved into PHP where it can be precise.** Caught by testing the negative cases, not the positive ones.

## 5. Deliverable 5 — Acceptance criteria

| Criterion | Evidence |
|---|---|
| Canonical roots exist | three roots, each with a first artifact; `--verify` → *"Registry and roots consistent"* |
| Future documentation routed correctly | `--self-test` 9/9; guard verified across 7 paths |
| **No existing documentation moved** | **`git status`: zero renames, zero deletions under `docs/` or `engineering/`.** Only additions and five surgical edits |
| No architectural boundaries changed | `engineering/` untouched · ES set untouched · no standard amended · no ruling minted · **OQ-5 not resolved** |
| Repository compatibility preserved | **`knowledge-lint`: 9 errors, 0 warnings — exactly the pre-existing baseline** |

### The compatibility check caught my own regression

**Baseline before this work: 9 errors, 0 warnings.** After the first pass: **10 errors** — the migration checklist lacked a knowledge card, which `docs/knowledge/` requires. **Fixed by adding a card; a subsequent orphan warning fixed by relating it to `META-LIFECYCLE`.** Final state is the baseline exactly.

> **Recording this because it is the point of the criterion.** *"No references broken"* is only meaningful if measured before and after. **I broke one, the linter caught it, and it is fixed** — which is also the argument for the checklist's first item: *record the baseline before migrating anything.*

### The 9 pre-existing errors are not mine, and they are evidence

**All nine are broken links into `architecture/`, caused by today's `architecture/` → `architecture_legacy/` relocation** (`docs/knowledge/portal/hubs/election.md` alone holds five). **I did not fix them** — the commission forbids changing existing links.

> **The failure mode the ADR anticipates has already occurred once, from a move made outside the derivation rule.** It is quoted in the migration checklist as the reason each item exists — **the migration cost is measured, not hypothetical.**

## 6. Out of scope — confirmed untouched

**No existing documentation moved · `engineering/` not restructured · Engineering Standards not relocated · methodology not relocated · KnowledgeOS product architecture not decided · PKS product architecture not decided · no repository merge or split · Open Question 5 not resolved.**

**Two scope limits are written into the artifacts themselves, not just observed here:** the registry header states that it routes documentation only and decides nothing about the architecture of `engineering/`, KnowledgeOS-as-product, or PKS-as-product; and `docs/knowledgeos/README.md` opens with OQ-5 and the consequence that **ES standards and cross-product methodology do not belong in that root until it is answered.**

## 7. Tooling summary

| File | Status |
|---|---|
| `docs/knowledge/schema/documentation-placement.yaml` | **new** — registry |
| `scripts/doc-placement.php` | **new** — resolver, self-test, verify |
| `docs/publicdigit/README.md` · `docs/knowledgeos/README.md` · `docs/pks/README.md` | **new** — roots' first artifacts |
| `docs/knowledge/_meta/classification-map.template.md` | **new** |
| `docs/knowledge/_meta/documentation-migration-checklist.md` | **new** |
| `package.json` | edited — two scripts |
| `.claude/scripts/engineering-placement-guard.sh` | edited — `docs/` branch |
| `.claude/CLAUDE.md` | edited — one pointer |
| `docs/knowledge/_meta/knowledge-card.template.md` | edited — one comment |

## 8. What Phase 1 needs next

**The classification map is unblocked and not started.** It is the only remaining Phase 1 deliverable, and **two open questions will bite while producing it:** OQ-5 (what `docs/knowledgeos/` is for) and PKS's unruled domain membership (89 files). **Both surface as `unclassifiable` rows rather than as guesses** — which is what that column is for.

---

**Traceability:** the ADR (APPROVED 2026-08-01; Phase 1 · Phase 2 gate · Rollback · OQ-1/3/5) · **R-70 / R-71** (classification model and derivation principle — approved in substance, unminted) · **R-67** (why cross-product standards stay in `engineering/`) · **R-42** (why not the Platform Registry) · **R-37** (untouched: no new top-level folder, no reorganization) · **ES-005.1** (runtime mount) · **ES-005.2** (folder rule — why each root has a first artifact) · **ES-005.4** (never a copy) · **ES-001.1** (rule parsimony — one registry, one resolver, hook extended not duplicated) · `npm run knowledge-lint` (baseline 9/0, restored). **No existing documentation moved · no standard amended · no ruling minted · OQ-5 unresolved.**
