# Placement Refinement + Documentation Referential Integrity — Report

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commissions:** (A) refinement pass on the placement implementation · (B) restore documentation referential integrity.
**Headline:** **`knowledge-lint` → ✅ All documents pass (0 errors, 0 warnings).** Repo-wide broken links **121 → 63**, and **every one of the 63 is unrepairable without inventing a document or making a human choice.**

---

# Part A — Refinement pass

## A1. Registry is now configuration-only

**Before:** 76 lines with `description:` and `open_question:` paragraphs. **After: 33 lines.** Prose removed; each rule carries a `ref:` (`ES-005.1`, `ES-005.3`, `ADR:OQ-2`, `ADR:Decision`) instead of an explanation.

> **Your dual-truth point was correct and I had introduced it.** The registry described *why* `cross-product-qualified → engineering` in its own words, so ES-005 and the registry both expressed policy. **Now the registry stores WHAT and points at WHO SAYS SO.** The rule row is unchanged in behaviour — only its justification moved out.

## A2. Scripts execute policy, they do not restate it

| Artifact | Removed | Kept |
|---|---|---|
| `doc-placement.php` header | governance narrative, invariants, scope-limit paragraph | two-line pointer to policy and configuration |
| `--verify` output | *"ES-005.2 forbids speculative directories"* | `ok` / `EMPTY` / `MISSING` |
| PENDING output | the stewardship explanation and *"pre-amendment behaviour stands"* | `PENDING — placement unruled (rule: …, ref: ADR:OQ-2). Record PENDING and escalate.` |
| the hook's `docs/` branch | roots listing, historical commentary | five lines: the path, the two commands, *exit 2 = unruled*, the policy pointer |

**Historical commentary removed from executable tooling.** *(The hook's `engineering/` branch retains its "WHY THIS EXISTS" block — it predates this commission and explains a design constraint the script obeys, not a governance rule. Flagged rather than removed unilaterally.)*

## A3. READMEs minimal

**19 lines each** (was 30–40): purpose, holds, owner, internal-layout ownership, and a three-row table pointing at policy / configuration / resolver. **The OQ-5 discussion moved out of `docs/knowledgeos/README.md` and lives only in the ADR**, per *"everything else belongs in ADRs."*

## A4. Behaviour unchanged — verified, not asserted

```
--self-test  : All 9 cases pass          (identical to pre-refinement)
--verify     : Registry and roots consistent
PENDING path : exit 2, still refuses to guess
hook routing : 7/7 paths unchanged (2 fire, 5 quiet)
knowledge-lint: ✅ All documents pass
```

## A5. ⚠️ DAP-001 — **not created, and I recommend it is not**

**Creating a new five-rule principles document under `engineering/` would repeat the exact violation that started this entire thread.**

| Obstacle | Detail |
|---|---|
| **ES-005.3 + ES-006.1** | DAP-001 would be a **cross-product artifact at `research` maturity**. The resolver returns **PENDING** for that classification. **Writing it into `engineering/` is precisely what `Layer_Verification_Rule.md` did** |
| **R-38** | *"no new concepts, **standards**, decisions, or architectural subsystems"* |
| **R-37** | `engineering/` permits bugfix/link/typo; a new principles document is none of those |
| **R-38's own precedent** | *"where existing rulings already express a constraint, **interpret them rather than add another ruling**"* — the ARB's stated preference after questioning whether R-38 itself was necessary |

**And the five principles already exist:** *classification precedes placement* and *placement derives from classification* are the **ADR's governing invariants** and **R-71**; *registries store configuration · scripts execute policy · ADRs govern policy* is **this refinement**, now embodied in the artifacts themselves.

> **The parsimonious realization: state the five in the ADR — the approved policy home — and let registry, scripts, READMEs and hook reference it.** That achieves your principle #3 (single source of truth) **without a new artifact, a new standard, or a placement violation.** **Ready to add that section to the ADR on one word.**

## A6. Integration audit — the missing acceptance criterion

**Do all supported documentation-generation paths consult the resolver?**

| Path | Consults resolver? |
|---|---|
| An author (human or AI) writing a file — **the dominant path** | **partly** — via the hook, in the mixed locations only |
| `knowledge-card.template.md` | ✅ points at `--list` |
| `classification-map.template.md` | ✅ built on the resolver |
| `.claude/CLAUDE.md` conventions | ✅ pointer |
| `knowledge-graph.php` | n/a — emits a graph, not documents |
| Session tooling (`session-changes-logger.sh`, `inject-context.sh`) | n/a — session state, correct by the `session-state` rule |

> **Honest finding: there is no document *generator* to configure. Every creation path is an author writing a file**, so routing depends on the hook and the conventions rather than on code.
>
> **The gap is real and I have not closed it: the hook is silent for `docs/adr/`, `docs/architecture/`, `docs/knowledge/` and other subtrees.** Widening it now would make it fire on nearly every new document — *"a checkpoint that is always dismissed teaches less than one that is read."* **Recommendation: widen it when migration begins**, at which point placement outside a root becomes exceptional rather than normal. **Recorded as an open item, not silently accepted.**

---

# Part B — Documentation referential integrity

## B1. Scope discovered

**A repo-wide scan of every Markdown link found 121 broken references across 40 files** — far beyond the 9 the linter reports, because `knowledge-lint` validates `docs/knowledge/` only.

## B2. Repairs were evidence-based, never guessed

**Four rules, each requiring the destination to exist before the link is rewritten:**

| Rule | Evidence | Repairs |
|---|---|---|
| `git-rename` | git's own rename record for that exact path | **12** |
| `root-relative` | the link was written repo-root-relative and that path exists | **12** |
| `unique-basename` | **exactly one** file in the repository carries that name | **31** |
| documented segment rename | `architecture/` → `architecture_legacy/`, recorded in `docs/adr/20260801_1712_legacy_folder_and_files.md`, each result existence-checked | **3** |
| | **Total repaired** | **58** |

> **A first attempt was discarded.** It picked a "best" candidate when several files shared a basename, and rewrote `election_management/01-overview.md` to a same-directory `01-overview.md` — **a plausible-looking wrong answer.** The rules were tightened to *unique candidate only*, and the multi-candidate cases moved to git-rename evidence, which resolved them correctly. **The discarded pass is recorded because it would have silently corrupted navigation.**

**Only the path changed.** Link text, anchors (`#section`) and document content are untouched.

## B3. Result

| | Before | After |
|---|---|---|
| **`knowledge-lint` links_resolve** | 9 errors | **0 — ✅ All documents pass** |
| **Repo-wide broken links** | 121 | **63** |
| Files edited | — | 18 |

**A fixed point was reached: re-scanning after the repairs surfaced no newly-broken links, and a second repair pass finds nothing further to fix (`REPAIRED: 0`).**

## B4. The 63 remaining — recorded, not invented

| Class | Count | Why untouched |
|---|---|---|
| **Missing** | **47** | **No file of that name exists anywhere in the repository.** These reference documents that were never written or were deleted long ago (`./REQUEST_LIFECYCLE.md`, `./MIDDLEWARE_PIPELINE.md`, `assign-members.md`, …). **The commission forbids inventing replacements** |
| **Ambiguous** | **6** | `./ARCHITECTURE.md` and `./INDEX.md` inside `developer_guide/election_engine/` and `developer_guide/tenancy/` — several files share the name and **no evidence selects one. A human choice, not a repair** |
| **Directory-unresolved** | 2 | directory targets with no verified destination |
| **Out of scope** | 8 | 3 in `.worktrees/` (scratch copies), 5 not document paths (`/help`, `$election` — route strings and template variables) |

**By area: 42 in `developer_guide/`, 10 in `docs/`, 2 in `architecture_legacy/`, 1 in `.claude/`.** **Most are legacy debt predating today's refactoring, not consequences of it** — the ADR-caused breakage was concentrated in the `architecture/` and root-to-`developer_guide/` moves, and that class is now fully repaired.

---

## Constraints honoured

**No document renamed or moved · no frontmatter altered (except the one card added earlier to satisfy the linter) · no classification changed · no redirects created · no lint rule suppressed or weakened · no validation relaxed · no ADR redesigned · no repository behaviour changed.** **Placement, classification, and the ADR's structure are exactly as they were.**

**Still true after both passes:** `docs/knowledgeos/`, `docs/publicdigit/`, `docs/pks/` contain only their READMEs · `Layer_Verification_Rule.md` unmoved · ES-005 unamended · **no ruling minted** · **OQ-5 unresolved**.

---

**Traceability:** the roots ADR · `docs/adr/20260801_1712_legacy_folder_and_files.md` (the documented `architecture_legacy` rename) · **R-37 / R-38** (why DAP-001 was not created) · **ES-005.3 / ES-006.1** (why an unqualified cross-product artifact may not enter `engineering/`) · **R-71** (placement derived) · **ES-001.1** (parsimony — one registry, one resolver, hook extended not duplicated) · `npm run knowledge-lint` (0/0). **Builds on** `2026-08-01-documentation-roots-implementation-report.md`.
