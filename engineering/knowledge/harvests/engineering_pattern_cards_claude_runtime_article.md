# Knowledge Harvest #2 — Claude Code Runtime Article (ARB-reviewed, 2026-07-08)

**Aggregate:** `KnowledgeHarvest { Source, Patterns[], Evidence[], Decision }` — same shape as Harvest #1 (this consistency is itself Evidence-Register material for EPC-010).
**Source & provenance:** Claude Code runtime article (agentic loop · plan mode · /context · /doctor · /init · compaction), **as categorized by ARB review 2026-07-08; article text not stored in repo** — the patterns, not the article, are the knowledge.
**ARB verdict:** Category A (8 concepts) already implemented or surpassed — no action · Category B/C → candidates below · Category D (model switching, provider permissions, Claude memory/JSONL/vocabulary, tool categories, harness terminology) → **rejected: provider-binding internals**, confined per FF-15.
**Cross-references (no duplicate cards):** *Explicit Context Budget* → existing **EPC-002** (this harvest contributes concrete budgets: CONTEXT ≤250 lines · session summary ≤150 · bootstrap ≤15 KB · knowledge package ≤10 KB · guide ≤300 lines) · *Layered Loading + compaction-alternative ("derive next-session needs, don't summarize")* → existing **EPC-001**. Corroboration rows appended to the Harvest #1 Pattern Evidence Register.

---

## EPC-011 · Engineering Bootstrap Report
- **Problem:** sessions start with variable, implicit orientation; a deterministic fact sheet (feature · plan status · standards/process versions · context usage · next step) removes thinking from startup.
- **Independence:** Yes. · **Existing:** CONTEXT.md injection carries the facts, unformatted and unbudgeted.
- **Assessment:** Weaker (facts present, report shape absent). · **Disposition:** Improvement Candidate.
- **Owner:** Session Continuity / CAP-01. · **Earliest review:** PB-004 retrospective (natural companion to EPC-001's layered bootstrap).
- **Required evidence:** PB-004 sessions showing startup ambiguity or re-derivation of facts the report would have stated.

## EPC-012 · Engineering Health Check (adapted /doctor)
- **Problem:** platform integrity is verified piecemeal (registry validation here, hooks there); one aggregated, honest check answers "is the platform healthy?"
- **Independence:** Yes. · **Existing:** **largely equivalent in definition** — FF-1..17 + registry validation + knowledge-lint ARE the health checks; what's missing is one aggregated runner.
- **Assessment:** Equivalent-in-parts, weaker-in-aggregation. · **Disposition:** Improvement Candidate (thin: a runner over existing checks — never new checks).
- **Owner:** Verification & Evidence / CAP-07. · **Earliest review:** PB-004 retrospective; natural sibling of AST-010.
- **Required evidence:** an integrity defect that piecemeal checking missed or found late.

## EPC-013 · Engineering Bootstrap for New Projects (adapted /init)
- **Problem:** the methodology (standards → process → registry → binding) is reusable beyond PublicDigit; a generator could scaffold it for a new project via the nine ERR questions (business? DDD? architecture? TDD? context? verification?).
- **Independence:** Yes. · **Existing:** none — and deliberately so.
- **Assessment:** Out of current scope. · **Disposition:** Improvement Candidate, **far-deferred** — violates AIP-14 for PublicDigit today (no PublicDigit feature benefits); relevant only if the methodology is ever productized. Parked with the "AI Engineering Methodology" framing note.
- **Required evidence:** an actual second project adopting the methodology.

## EPC-014 · Engineering Confidence Assessment *(the original-contribution candidate — absent from both the article and our platform)*
- **Problem:** plans carry implicit certainty; making per-domain confidence explicit (business/DDD/architecture/tests/verification) with a STOP-below-threshold rule would let EP-03 declare not just *what* is unknown but *how solid* the knowns are.
- **Independence:** Yes. Integrates naturally with EP-03's stopping condition.
- **⚠ Honesty-invariant tension (must be resolved before adoption):** asserted percentages ("Business: 95%") are exactly the invented-score pattern FF-3/AIP-01 forbid. Admissible only if confidence is **derived from checkable criteria** — e.g. per ERR domain: count of open unknowns, presence/absence of a governing ADR, existence of a RED test — mapping to discrete levels (High/Medium/Low + why), never invented numbers. The Phase-1 "truth score" rejection is the cautionary precedent.
- **Assessment:** Genuinely new; design constraint identified. · **Disposition:** Improvement Candidate.
- **Owner:** Implementation Guidance (EP-03 extension — process, not platform capability). · **Earliest review:** PB-004 retrospective, with PB-004's ERRs as the corpus to test derivation rules against.
- **Required evidence:** a PB-004 case where an approved plan failed on a low-confidence domain that a derived assessment would have flagged.

---

**ARB priority list on record (all post-PB-004):** 1. Context Budget (EPC-002) · 2. Layered Bootstrap (EPC-001) · 3. Bootstrap Report (EPC-011) · 4. Health Check (EPC-012) · 5. Confidence Assessment (EPC-014). Everything else: already represented or rejected.
**Closing observation (ARB):** the article answers "how does Claude work?"; the platform answers "how should software engineering work, regardless of provider?" — the article is a source of runtime-optimization ideas, not architecture.
