# PKS Observation — Intentional Source Code Editing (KnowledgeOS candidate)

**Date:** 2026-08-05 · **Source:** Election product work, `resources/js/i18n.js` case-sensitivity fix
**Classification:** PKS Observation → **KnowledgeOS CANDIDATE** (operating-loop phases 13–14) — **PREPARED, NOT ADOPTED**
**In force where:** this repository only, via `.claude/CLAUDE.md` §"Source Code Editing Policy" (the rule text lives there **once**; this document records the *lesson and its maturity*, never a second copy of the rule)
**⚠️ PLACEMENT: `PENDING`** — `scope: cross-product · maturity: research`; resolver returns `PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2)`. Interim home in `docs/pks/` on the same time-bounded exception as `2026-08-05-knowledgeos-distillation-principle-candidate.md`.

---

## The observation

While fixing a Vite build failure, the assistant reached for `sed -i` to rewrite three import paths. The human intervened and stated the rule: **search may be automated; source modifications must be intentional and manual.**

**What made the intervention correct rather than merely cautious:** the target lines sat between imports containing `voting-election/` and `ElectionNavigation/`. The specific pattern proposed happened to be safe, but the *class* of action — regex replacement over a file whose neighbouring lines contain look-alike tokens — succeeds or corrupts by luck, and the author cannot tell which afterwards. The targeted three-line edit could not corrupt anything, and the diff shows exactly three intentional hunks.

## The candidate principle (the deeper rule — not about `sed`)

> **Automation belongs to discovery and verification. Change belongs to intent.**

| Activity | Automation appropriate? |
|---|---|
| Search · navigation · discovery · analysis | ✅ |
| Verification after a change | ✅ |
| Documentation generation | ✅ |
| Code generation, when explicitly requested | ✅ |
| **Editing existing production code** | **manual by default** |

**Required sequence:** locate → explain → edit deliberately → show the diff → verify.

## Why it is beneficial

1. **Forces understanding.** A replace can fix a symptom without the author ever modelling the cause; an edit at a located site cannot be made without reading it.
2. **Cannot over-reach.** A too-broad pattern damages look-alike sites silently. A targeted edit has no blast radius.
3. **Reviewable history.** Every hunk in the diff is deliberate, so `git log -p` remains an audit trail rather than a record of sweeps.
4. **Consistent with the repository's existing discipline** — automation already carries discovery and verification here (`doc-placement.php`, `knowledge-lint.php`, Deptrac, PHPStan, the merge gate); it has never carried *authoring*.

## Risks and honest limits

- **Slower for genuinely mechanical, verified-safe migrations** (e.g. a namespace rename across 200 files). The rule as written says *manual by default* — it does not claim automation is never correct, and a large authorized migration should be able to request an exception rather than pretend the rule does not exist.
- **Unenforceable by tooling.** No hook can distinguish a deliberate edit from an automated one after the fact. It is a discipline, not a gate — and should not be asserted as enforced.
- **n = 1 repository.** Its apparent generality is untested outside PublicDigit.

## Maturity and promotion path (ES-006.1)

| Rung | State |
|---|---|
| Observation | ✅ **here** — one occurrence, one repository |
| Repeated observation | ⬜ needs a second independent instance |
| Practice | ⬜ |
| Candidate standard | ⬜ |
| Approved standard | ⬜ |

**Promotion tests (from the Distillation Principle candidate):** domain independence ✅ · repository independence ✅ *(claimed, untested)* · engineering value ✅ · discovered through real work ✅ — **all four pass, which yields a candidate, not a standard.** ES-006.1 still requires evidence beyond one occurrence; R-90: *"one work package is not a standard."*

**⛔ What was deliberately NOT done:** a `engineering/knowledge/candidates/` directory was proposed and **not created**. It is blocked three ways — ES-005.2 (speculative maturity-tier folders), R-37 (no new capability hierarchy), ES-005.3 (research stays project-side until qualified) — and this is the identical proposal the **2026-08-01 KnowledgeOS maturity-structure assessment** blocked and escalated to the ARB. The resolver concurs independently (`PENDING`, OQ-2). **The candidate is recorded, not housed.**

**If promoted, where it belongs:** as a section inside an **existing implementation-discipline standard**, never as a standard of its own (ES-005.4 — never a copy; a rule this small does not deserve its own canonical home).

## Disposition requested

None now. **Re-examine when a second independent instance appears** — at which point this observation becomes the first of two evidences, and the promotion question can legitimately be put to the Decision Authority.

---

**Traceability:** `.claude/CLAUDE.md` §Source Code Editing Policy (rule text, single home) · `resources/js/i18n.js:65-67` (the fix that produced the lesson) · `2026-08-05-knowledgeos-distillation-principle-candidate.md` (the promotion framework this follows) · `engineering/verification/reports/2026-08-01-knowledgeos-maturity-structure-assessment.md` (why no `candidates/` folder) · ES-005.2 · ES-005.3 · ES-005.4 · ES-006.1 · R-37 · R-90 · ADR:OQ-2
