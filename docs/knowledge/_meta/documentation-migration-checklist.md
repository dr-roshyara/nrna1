---
knowledge_id: META-DOC-MIGRATION-CHECKLIST
title: Documentation Migration Checklist
knowledge_type: checklist
bounded_context: global
status: draft
authority: provisional
audience: [architect, developer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [governance, placement, migration, documentation-roots]
related_to: [META-LIFECYCLE]
---

# Documentation Migration Checklist

**For Phase 2 of `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md`.** **Phase 2 is not authorized.** This checklist exists so that, when it is, nothing is discovered late.

> ## ⛔ Gate — do not start until all four hold
>
> 1. **The classification map is complete and ARB-reviewed.** *(Phase 1 — unblocked, not yet done.)*
> 2. **The R-37 scope question is answered:** does *"no more document reorganizations"* bind `docs/`?
> 3. **Physical migration is separately authorized.** ADR approval authorizes Phase 1 only.
> 4. **Open Question 5 is dispositioned** if any artifact would land in `docs/knowledgeos/` — OQ-5 decides what that root is for.

## Why this checklist is not theoretical

**On 2026-08-01 an `architecture/` → `architecture_legacy/` relocation broke 9 knowledge links**, surfaced by `npm run knowledge-lint`:

```
❌ ERROR [links_resolve] docs/knowledge/portal/hubs/election.md
        Broken link: ../../../../architecture/election/analysis_of_current_system.md
        … 8 more
```

**The failure mode this checklist guards against has already happened once, from a move made outside the derivation rule.** Every item below exists because of a real cost, not a hypothetical one.

## Per-batch procedure

Migrate **one domain at a time**, and within a domain **one source directory at a time**. Never a repository-wide move.

- [ ] **Record the baseline.** `npm run knowledge-lint` — capture the error count *before*. A migration cannot be judged clean against an unknown baseline.
- [ ] **Confirm the derived destination** with `php scripts/doc-placement.php` for every file in the batch. **Do not read the destination off the map without re-deriving it** — the map is a record, the resolver is the rule.
- [ ] **Move with `git mv`**, preserving history. **Never copy** (ES-005.4 — never a copy).
- [ ] **Update inbound references.** Search the whole repository, not just `docs/`: `.md`, `.yaml`, `.json`, `.php`, `.sh`, `CLAUDE.md`, `.claude/`.
- [ ] **Re-run `npm run knowledge-lint`.** **Error count must not exceed the baseline.**
- [ ] **Re-run `npm run knowledge-graph`** if it emits a committed artifact.
- [ ] **Check the portal**: `docs/knowledge/portal/INDEX.md`, `by-role.md`, `by-type.md`, and every `hubs/*.md`. These hold relative links four levels deep and broke first last time.
- [ ] **Check runtime pointers**: `CLAUDE.md`, `.claude/CLAUDE.md`, `.claude/scripts/*.sh`, `.claude/platform/registry.yaml`.
- [ ] **Check the standards' own pointers**: `engineering/governance/STANDARDS_INDEX.md`, `ES-004`, `ES-005`.
- [ ] **`php scripts/doc-placement.php --verify`** — registry and roots still consistent.
- [ ] **One commit per batch**, message naming the domain and the source directory, so a single batch can be reverted alone.

## Rollback

**Per the ADR, rollback restores more than file locations:**

- [ ] Files returned to original paths (`git mv` back, or revert the batch commit)
- [ ] **Cross-references** restored
- [ ] **Redirects** removed, if any were introduced
- [ ] **Documentation build integrity** restored
- [ ] **Searchability / indexes** rebuilt
- [ ] **Knowledge reference validation** back to baseline (`npm run knowledge-lint`)

**Because each batch is one commit, rollback is `git revert` of that commit** — which is the reason for the one-commit-per-batch rule.

## What migration never does

> **Artifact identity is independent of physical location.** A move changes **no** classification, ownership, authority, maturity, or domain. **If a move appears to change what something is, the classification was wrong before the move — fix the classification, not the destination.**

## Out of scope, permanently, for this checklist

Restructuring `engineering/` · relocating Engineering Standards or cross-product methodology · deciding KnowledgeOS or PKS **product** architecture · repository decomposition. **This checklist migrates documentation and nothing else.**

---

**Traceability:** the ADR (Phase 2, Rollback Plan, Open Questions 1/3/5) · `scripts/doc-placement.php` · `docs/knowledge/schema/documentation-placement.yaml` · **ES-005.4** (never a copy) · **R-37** (freeze; scope unresolved) · **R-71** (placement derived) · baseline evidence: 9 `links_resolve` errors, 2026-08-01.
