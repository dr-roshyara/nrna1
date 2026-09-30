# `KOS-CONTRACT-NEUTRALITY-001` — `D-1` implementation readiness check

**Date:** 2026-09-28 · **Assignment:** `S5-architecture-pass1-evidence-reconciliation`,
narrow readiness pass (no new grant) · **Performer (self-declared, not attestable):**
`claude-code-session:e8f324f1-...`

> ⛔ No implementation performed. No `expected.json`, test, or production code change.
> `git status --short scripts/ tests/ .claude/runtime` unchanged before and after.

---

## 1 · The chain, read as a whole (not re-derived — already established by this session)

`2026-09-27-KOS-D1-analytical-definition-decision.md` → `2026-09-27-KOS-L3-semantic-
neutrality-matrix.md` (Case E) → `2026-09-27-KOS-adapter-research-FROZEN.md` →
`2026-09-28-...-V3-exclusion-evidence-sufficiency-pass.md` → `2026-09-28-...-V3-decision-
gate-reconciliation.md` → `2026-09-28-...-V3-governance-decision-brief.md` →
`2026-09-28-...-D1-schema-sufficiency-pass.md`. `Implementation_Architecture_Constitution_
v1.0.md` re-checked for layering rule only (Domain = pure PHP, no framework — already how
every Cohesion `Domain` class is built; nothing in it bears on `D-1`'s specific schema).
Current `Domain` source re-verified this pass: `BehaviourReference.php`, `EdgeRules.php`,
`ExclusionReason.php` unchanged since yesterday's reads (same content, re-confirmed by
re-reading, not re-hashed — no source change occurred between passes today).

## 2 · The three things kept explicitly separate

### A — Semantic decision: settled, convergently, three separate ways
*What does `IndeterminateBehaviourReference` mean?* An observed method invocation whose
receiver denotes the analysed unit but whose method-name token is computed, not literal —
recorded as seen, excluded as `NotDeterminable`, with no legal name. Settled by: (1) the
pinned `expected.json._variant_decisions_pinned.intra_class_calls` text itself, predating any
adapter work (2026-08-16); (2) the PHP-side V-3 governance chain (adopted 2026-09-04); (3) the
frozen adapter branch's independent Python-side reconstruction (`Case E`, `D1-analytical-
definition-decision.md`), which arrived at the same semantics from Python's `getattr(self,
name)()` without reference to the PHP chain's wording. **Three independent derivations,
same answer. No open semantic question remains.**

### B — Architecture decision: settled for the adopted scope; one boundary explicitly excluded, not open
*What exact `L3` type/schema is required?* Per yesterday's schema-sufficiency pass: a new,
distinct type (independently re-derived from the registered `INV-L3-5` text, not merely
cited from the proposal — a relaxed/nullable `BehaviourReference` is directly barred by that
invariant's own words). **Minimal schema: no stored fields beyond the type's own identity** —
determinability is recoverable from the type tag alone; no target-name field (forbidden);
provenance/`factId` is not required by `D-1`'s own semantics (conditional on a separate,
independent question — the exclusion-evidence-record Model A/B/C, which this implementation
does **not** need to resolve, per §10 of that pass: schema and evidence-representation are
independent design axes).

**The one item that pass left genuinely open — `self::$m()`/`static::$m()` scope — is
resolved here by narrowing, not by answering it:** this implementation slice covers exactly
what every governance document actually adopts and discusses — `$this->$m()`. `self::$m()`/
`static::$m()` remain out of this slice's scope, exactly as invisible as they are today. This
is not a deferred blocker; it is a scoping decision this document makes explicitly, matching
the one real, measured corpus occurrence (`DebugVoterSlug.php:67`, a `$this->` case). Extending
coverage later is new, separately-scoped work, not a prerequisite for this slice.

### C — Governance approval: real, current, not leftover — but narrow and answerable directly
**Is there an actual unresolved decision that prevents engineering implementation?** Two
things must not be conflated, and this pass separates them precisely, per the human's own
instruction:

1. **The full `D-1`/`D-4`/`D-5` `expected.json` contract-incorporation question** (governance-
   decision-brief.md, Option A/B) — genuinely still open, genuinely a PO/ARB-level decision
   (it involves `D-4`/`D-5` and a bundling preference this pass does not touch). **This is
   NOT what blocks writing the `IndeterminateBehaviourReference` class.** Contract
   incorporation and code implementation are different acts, already distinguished in this
   thread's own record (Amendment 1, A1.2: producing evidence is separable from asserting
   contract conformance with it).
2. **This repository's own standing rule** (`CLAUDE.md`, Engineering Process: *"implement
   only the approved plan... wait for explicit human approval"*) **and every single document
   in this exact chain — including three written earlier today, under this same human's
   direct instruction — which uniformly say "no implementation authorized here."** This is
   not stale process left over from an old track. It is the actively-maintained convention
   on this exact work item, reaffirmed as recently as this session's own prior turn. The
   Python-first strategic redirection changed *which* research to pursue; nothing in the
   human's own instructions has suspended the standing rule that implementation follows an
   approved plan.

**Conclusion on C, stated as the checkpoint requires:** there is **no substantive,
unresolved semantic or architectural question** blocking implementation of the narrowly-
scoped slice below. There **is** a procedural gate — not a governance *deliberation*, a
governance *approval* — and it is small: this document **is** the plan (§4). What remains is
the human's explicit authorization of it, which is a much smaller ask than reconvening
PO/ARB on the full `D-1`/`D-4`/`D-5` incorporation question.

## 3 · Implementation-readiness checklist

| Question | Answer |
|---|---|
| Is the `L3` schema sufficiently specified? | ✅ Yes, for the scoped construct (§2-B) |
| Is the Python representation sufficiently specified? | ✅ Yes — `Case E`'s construction is the exact target; `extract_facts.py`'s emission site needs identifying (implementation-mechanical, not a research question) |
| Is the PHP representation sufficiently specified? | ✅ Yes — the exact skip site is already located (`PhpFactExtractor.php:519-544`, the `T_STRING` guard) |
| Is the `L4` behaviour specified? | ✅ Yes — one new, unconditional, always-`exclude(NotDeterminable)` `EdgeRules` branch, confirmed metric-neutral in three independent reports |
| Are tests specified? | ✅ Yes, at the level of what must be proven (§4.8-4.10) |
| Is the cross-language invariant specified? | ✅ Yes — confirmed, not assumed: the fix is identical for both languages by construction (no `qualifierKind`, no source-language distinction in the `EdgeRules` rule itself) |
| Is there any genuinely unresolved semantic question? | **No**, within the scope this document sets (§2-B's exclusion of `self::`/`static::` is a scoping choice, not an open question) |

## 4 · Final answer

- **Implementation-ready:** **YES**, for the narrowly-scoped slice in the companion contract
  (`2026-09-28-KOS-D1-implementation-contract.md`).
- **Semantic uncertainty:** **NONE**, within that scope.
- **Governance dependency:** **NONE that blocks this slice specifically.** The full `D-1`/
  `D-4`/`D-5` contract-incorporation question remains genuinely open and untouched by this
  document. What this slice needs is the human's explicit authorization of the attached plan
  — the repository's own standing rule, not a new or invented gate.
- **Exact implementation files likely affected:** new
  `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/Domain/IndeterminateBehaviourReference.php`
  · `Domain/EdgeRules.php` (new branch) · `Infrastructure/Php/PhpFactExtractor.php` (the
  identified skip site) · `Infrastructure/Python/extract_facts.py` (equivalent site, exact
  location to be confirmed during implementation) · `Infrastructure/Python/
  PythonSemanticFactProvider.php` (deserialization, mirroring the existing `enumCase()`
  pattern for the new type if needed).
- **Exact test scope:** new characterization/correction tests under `tests/Unit/Cohesion/`
  for the new fact kind's construction, `EdgeRules` acceptance, and both adapters' emission —
  per the RED/GREEN pattern every prior slice in this thread has used; full existing suite
  (182+ tests) as regression.
- **Next recommended action:** the human authorizes the attached implementation contract (or
  amends it), after which this becomes an ordinary RED→GREEN implementation slice, same
  discipline as R1–R6/OWD-1–15.

**STOP after this check.** No implementation performed.

**Traceability:** all documents named in §1.
