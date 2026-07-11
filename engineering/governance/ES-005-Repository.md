# ES-005 — Repository

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** how the repository is organized — the three-concern separation and the rules that keep structure honest.
**Scope:** the whole repository (Product · Engineering · Runtime concerns).
**Authority:** Decision Authority (ARB).
**Qualification Method:** structural qualifications (OQ E-2-class: folder justification, first-artifact, reserved namespaces, link integrity).
**Supersedes:** README/MEMORY as the rule homes for the folder rule and placement litmus (README remains the entry-point summary; this document is canonical).
**Related Standards:** ES-001 (authority) · ES-004 (record placement) · ES-006 (research-tier placement).

## Hosted rules (canonical here; previously README/MEMORY conventions)

**ES-005.1 — The Three-Concern Separation** *(EM-001, ARB 2026-07-10)*. `docs/` + `architecture/` + `app/` + `tests/` = **Product** (what PublicDigit is) · `engineering/` = **Engineering Platform** (how it is engineered) · `.claude/` = **Runtime mount point** (how the current adapter executes) — the mount never moves and is never "the architecture." Audit trail: `engineering/MIGRATION_REPORT.md`.

**ES-005.2 — The Folder Rule** *(ARB 2026-07-10)*. A directory exists only when its first artifact arrives. Reserved namespaces are documented (README table), never created speculatively. Empty directories are removed on discovery (`rmdir`-class, verified-empty only).

**ES-005.3 — The Placement Litmus** *(ARB 2026-07-10)*. Could a different project adopt the document **unchanged**? Yes → `engineering/`. Needs project context or evidence → the project. Active session state → the runtime mount. Research artifacts remain project-side (`docs/implementation/`) until promoted through qualification (ES-006 ladder).

**ES-005.4 — Never a Copy** *(from the Project Knowledge context invariant, generalized at consolidation — candidate, ARB to confirm scope)*. Governed knowledge references, assembles, validates, and contextualizes existing artifacts; it never duplicates them. One rule → one home; everything else points (this standard set practices it: registered rules are pointers).

## Registered (pointers)

| Rule | Home |
|---|---|
| Registry-first workflow (runtime assets) | `.claude/platform/registry.yaml` header (BINDING) + guide 01 |
| Reserved namespaces table | `engineering/README.md` |
| Sealed-corpus rule (moves allowed, edits never — R-30) | rulings register |
| Structural freeze terms (R-37: bugfix/link/typo until conditions met) | rulings register |
