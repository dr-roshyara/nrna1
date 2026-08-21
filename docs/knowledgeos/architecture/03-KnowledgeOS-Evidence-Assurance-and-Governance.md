# KnowledgeOS Evidence, Assurance & Governance Architecture

> ⛔ **PROPOSED · NON-AUTHORITATIVE · REQUIRES ARCHITECTURE/GOVERNANCE REVIEW.**
>
> Part of the **Architecture Review Set** (see [00 — Index](00-KnowledgeOS-Architecture-Review-Index.md)).
> Consolidates the corpus's **evidence, deterministic-assurance, and governance**
> claims — the evidence boundary, the authority model, and role separation. Every
> claim is level-tagged and status-tagged (`ESTABLISHED` / `PROPOSED` / `REJECTED` /
> `OPEN`).

## 1 · Sources

| Source (renamed corpus) | Type | Content |
|---|---|---|
| `brainstorming/20260821-120633-kos-state-durability-assurance-integration.md` | architecture-proposal | Durability work → first consumer of deterministic assurance; integration surfaces; guardrails |
| `brainstorming/20260819-220806-kos-3-0-state-durability-ddd-boundary.md` | architecture-proposal | Evidence vs Governance contexts; R-CONFLICT; ADR-KOS-002 |
| `brainstorming/20260816-204714-track2-eks-semantic-discovery.md` | analysis | Rule model, scope, authority-gap analysis (Track-2 EKS) |
| `brainstorming/20260819-092449-research-on-role-separation.md` | analysis | External (Perplexity) research: responsibility ≠ ownership ≠ authority; graded independence; conformance levels |
| `brainstorming/20260819-205757-ddd-correction-verification-verdict.md` | analysis | DDD correction/verification verdict; evidence concepts; ADR discipline |
| `brainstorming/20260819-104802-delegation-map-domain-owners.md` | noise (off-topic) | PublicDigit Election OperatingCore delegation map — **no KnowledgeOS claims**; excluded |

**Cross-references:** `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-ADR.md`
+ `-IMPLEMENTATION-DESIGN.md` + `-MIGRATION-PLAN.md` (+ AMD4/5/6 summaries) ·
`docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` ·
`20260821-1641-track2-phase1-author-side-adoption-plan.md` ·
`docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md`.

## 2 · The evidence boundary (durability) — status-graded claims

### 2.1 Current-state problem (ESTABLISHED)
- `[DOMAIN][ESTABLISHED]` `.gitignore` excludes `.claude/runtime/`, where the
  authority record lives (16→18 records, 210→216 transitions, 99→114 grants).
  Claim O-3: *no runtime state is stored there — only an append-only evidence log +
  grants*; derived state is folded, never persisted — `KOS-AIP-GOV-STATE-DURABILITY-ADR`.
- `[DOMAIN][ESTABLISHED]` "I-10 (record outranks prose) makes the most authoritative
  artifact the least durable → authority and durability are **inverted**" —
  `KOS-AIP-GOV-STATE-DURABILITY-ADR`; echoed by the corpus: `.claude/runtime` mixes
  two domains with different lifecycles — "same storage ≠ same bounded context" —
  `…220806-kos-3-0`.

### 2.2 The proposal and its decision status
- `[DOMAIN][PROPOSED]` Three-way separation: **source of execution**
  (`workflow-state.php`/AST-015, tracked) vs **evidence of execution** (transition
  log + grants, untracked) vs **authoritative decision record** (ADRs/reviews,
  tracked) — `KOS-AIP-GOV-STATE-DURABILITY-ADR`.
- `[DOMAIN][PROPOSED]` Option **B′ = relocation**: move the authority record out of
  `runtime/` into a tracked, append-only governance-evidence location; leave runtime
  ephemeral; **byte-preserving copy, never parse-and-rewrite** —
  `KOS-AIP-GOV-STATE-DURABILITY-ADR` + `-IMPLEMENTATION-DESIGN`.
- `[DOMAIN][PROPOSED]` `R-CONFLICT`: a conflict in the authority record is **never
  resolved by choosing a side**; `seq` density makes loss detectable — `-ADR`; in the
  corpus this becomes a **domain event/invariant** owned by the Evidence context
  ("conflict resolution MUST preserve provenance, sequence integrity,
  reconstruction capability") — `…220806-kos-3-0`.
- `[GOVERNANCE][ESTABLISHED]` **Decision status is graded, not monolithic**:
  - ADR artifact = 🟡 **PROPOSED** ("recommends an architecture and decides nothing");
  - a separate PO/ARB **DECISION ACT (2026-08-19)** = ✅ **DECIDED**: **D1 = B′ adopted,
    D2 = R-CONFLICT adopt, D3 = existing placement governance**;
  - Implementation design = 🟡 PROPOSED (design only);
  - Migration plan = 🟡 PROPOSED · **not executed · 0 lines of implementation** —
    `KOS-AIP-GOV-STATE-DURABILITY-{ADR,DESIGN,PLAN}` (per the cross-reference digest).
- `[DOMAIN][PROPOSED]` ADR-KOS-002 (corpus): "Separate Operational Execution from
  Durable Governance Memory" — proposed, with the explicit condition **"do not freeze
  the new bounded contexts yet; run a validation workshop"** — `…220806-kos-3-0`.

### 2.3 Evidence concepts (what the corpus claims)
- `[DOMAIN][PROPOSED]` Three evidence concepts to keep apart: EvidenceRecord
  (durable source-backed object) · EvidenceReference (relationship) · EvidenceBundle
  (point-in-time evidence for a decision/response/execution) —
  `…205757-ddd-correction-verification-verdict`.
- `[DOMAIN][PROPOSED]` Evidence lifecycle: Observation → Evidence Candidate →
  Qualified Evidence → Governance Accepted Evidence → Knowledge Product Binding;
  an AI agent creates observations/proposals/evidence candidates, never knowledge
  directly — `…220806-kos-3-0`.
- `[DOMAIN][PROPOSED]` "Evidence does not 'serve' governance — evidence is a source
  of truth that governance evaluates" — `…220806-kos-3-0` (round 2 correction).

## 3 · Deterministic assurance (Track-2) — what is real vs proposed

- `[GOVERNANCE][ESTABLISHED]` The **Deferred Architecture Register correction is
  ACCEPTED (2026-08-04)**: *"automation of deterministic work is not speculative
  architecture"* → deterministic read-only assurance may start NOW with no new
  authority — `…120633-kos-state-durability-assurance-integration`.
- `[GOVERNANCE][ESTABLISHED]` The **methodology freeze (2026-08-01)** bars
  assurance classes / risk routing / gates / review-model evolution unless PublicDigit
  implementation exposes a deficiency; the deficiency here is in KnowledgeOS
  governance execution, so the exception does **not** fire → those phases are frozen
  and need a PO/ARB act — `…120633`, `…120810-kos-governance-role-cost-optimization`.
- `[TECH][ESTABLISHED]` `knowledge-lint` is hard-scoped to `docs/knowledge/` and never
  sees `docs/knowledgeos/` — "the entire Track 2 corpus had zero mechanical coverage";
  the fix is a profile + a root, not a new engine — `…120633`.
- `[TECH][ESTABLISHED]` Phase 0 (mechanical back-test: S1–S5 structural checks,
  harness S6, adapters S7; 220 tests) = **AUTHORIZED · EXECUTED · PASS**; Phase 1
  (author-side handoff report) = **implemented/verified within authorized scope** —
  `docs/plans/20260821-1138-…` + `20260821-1641-…`.
- `[GOVERNANCE][PROPOSED]` Guardrails (G-1…G-6): checker **warn-only** (a blocking
  checker becomes a governance object needing authority); cannot discover undeclared
  architectural acts; report states what it did NOT check; no authority manufacture
  (`CONFLICT DETECTED`, never `INDEPENDENCE = TRUE`); no frontmatter/card
  requirement; no identifier minting — `…120633`.
- `[TECH][PROPOSED]` Seven integration surfaces (back-test vs AMD4–6, four-layer
  trace, R-CONFLICT read-only checks, registration/amendment lineage, post-B′ root
  extension, author-side pre-handoff report, report-as-required-evidence) — surface 7
  gated behind AMD6 acceptance + PO/ARB — `…120633`.
- `[GOVERNANCE][ESTABLISHED]` The assurance capability **must not define or own the
  evidence boundary**: "assurance root resolution must follow the governed evidence
  boundary; it must not define or own that boundary" — `…120633`.
- `[PATTERN][ESTABLISHED]` Core invariant (candidate): *"Automation may reduce the
  cost of assurance and must never manufacture authority"* — `…120633`, `…120810`.

## 4 · Role separation & governance authority (the research)

- `[DOMAIN][PROPOSED]` **Responsibility ≠ ownership ≠ authority.** A person/role may
  perform an activity without owning the domain model, capability, authority, data,
  or enforcement mechanism — `…092449-research-on-role-separation`.
- `[DOMAIN][PROPOSED]` **Operating role ≠ capability ≠ bounded context ≠ agent ≠
  service**; role-first modeling has benefits (explicit responsibilities,
  separation-of-duties) and risks (role names become aggregates; org changes force
  architecture changes) — `…092449-research-on-role-separation`.
- `[DOMAIN][PROPOSED]` **Independence is graded, not binary**: declared → recorded →
  access-constrained → technically isolated → organizationally separate → externally
  assessed; a platform can evidence some dimensions, cannot prove all automatically —
  `…092449-research-on-role-separation`.
- `[PATTERN][PROPOSED]` Authority model: operating role → decision right → authority
  grant (scope/time) → governance body → accountability — `…092449-research-on-role-separation`.
- `[DOMAIN][ESTABLISHED]` Authority principle (Track-2): *"the mechanism records
  authority; it does not create authority"* — `…204714-track2-eks`.
- `[DOMAIN][ESTABLISHED]` Keep-apart list (G-4, corpus): Recording ≠ Asserting ·
  Evidence ≠ Proof · Reference ≠ Ownership · Author ≠ Independent Reviewer ·
  Self-check ≠ Independent Assurance · Execution ≠ Governance — `…120633`.
- `[PATTERN][PROPOSED]` "AI cannot create authority; authority comes from the
  Governance context" — `…204431`/`…205757`; the external research adds: enforcement
  must have a real **effect path** (a warning script is monitoring, not enforcement)
  — `…092449`.

## 5 · Contradictions & tensions (surfaced, not resolved)

| # | Tension | Where it appears |
|---|---|---|
| T1 | **ADR status reads both ways**: the ADR artifact is PROPOSED while a separate decision act has already DECIDED B′/R-CONFLICT — the corpus and the decision record must be read together or they appear to contradict | `KOS-AIP-GOV-STATE-DURABILITY-ADR` vs `-DECISION` |
| T2 | **Assurance as capability vs context**: corpus treats deterministic assurance as a cross-cutting capability; the event-driven refinement proposes a new **Assurance Context** | `…120633` (capability) vs `…142748-event-driven-domain-loop-positioning` (context) |
| T3 | **Evidence-vs-Governance ownership**: Governance "defines authority rules" vs Evidence "is the source of truth governance evaluates" — who owns what remains a live boundary question (the corpus's own validation-workshop condition) | `…220806-kos-3-0` |
| T4 | **Cost optimisation vs freeze**: assurance-class/risk-routing proposals are internally contradicted by the same corpus's later review calling them frozen | `…120810-kos-governance-role-cost-optimization` (internal) |

## 6 · Open questions

- `[OPEN]` EKS authority gaps: no temporal ending on authority; delegation observed
  but not represented; ownership ≠ authority; provenance needs stronger immutable
  addressing; rule changes must connect to the authority mechanism — `…204714`.
- `[OPEN]` Bounded-context validation-workshop questions (the 3.0 review's final
  condition): does Evidence have independent lifecycle/ownership? Can Evidence exist
  without a Knowledge Product? Can Governance exist without Evidence? Which context
  owns authority decisions? Which owns lifecycle transitions? — `…220806-kos-3-0`.
- `[OPEN]` Independence proof: what a platform CANNOT prove from metadata alone
  (no collusion, no conflict of interest, cognitive independence, organizational
  independence) — `…092449`.

## 7 · What is NOT decided here

B′ relocation and R-CONFLICT adoption are **recorded as decided by a PO/ARB decision
act** — that record is reproduced here as evidence, **not re-decided** by this
document. The migration is **not executed**. ADR-KOS-002, the Assurance Context, and
the evidence-boundary validation remain open for the human authority.

## Traceability

Corpus sources in §1 (renamed; `…104802-delegation-map` is off-topic and excluded)
· cross-refs `KOS-AIP-GOV-STATE-DURABILITY-{ADR,IMPLEMENTATION-DESIGN,MIGRATION-PLAN}.md`
(+ AMD4/5/6) · `docs/plans/20260821-1138-…` + `20260821-1641-…` ·
`reviews/2026-08-21-cost-optimization-governance-assurance-review.md` ·
Review-Set [00](00-KnowledgeOS-Architecture-Review-Index.md) ·
[01](01-KnowledgeOS-Domain-and-Context-Architecture.md) ·
[07](07-KnowledgeOS-Target-Architecture-Review.md) · plan D-6/D-7 ·
corpus sort commit `9a57a7cb`.
