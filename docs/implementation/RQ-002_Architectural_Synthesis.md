# RQ-002 — Architectural Synthesis: The Project Knowledge Domain

**Kind:** architectural synthesis — the bridge between research and architecture. NOT design: no folders, templates, software, APIs, aggregates, or implementation. **Status:** COMPLETE — presented for ARB review; architecture begins only after the ARB rules.
**Evidence base (authoritative, closed):** RQ-002 charter · `RQ-002_Research_Findings_Raw.md` (4 executors, 7 disciplines, ~120 sources) · `RQ-002_Strategic_Discovery_Report.md` · Engineering Platform Reference Architecture · EEP. No new research was performed; every statement below is traceable to this base (evidence pointers use executor labels A/B/C/D and contradiction ids).
**Output tests:** traceable · falsifiable by future research · implementation-agnostic · governance-ready.

---

## Deliverable 1 — Architectural Principles

**PK-P1 · Same-Population Principle**
- **Statement:** Knowledge capabilities must live inside existing workflow gestures, maintained and consumed by the same population.
- **Rationale:** Every artifact class requiring a second population or a separate maintenance gesture has a documented decay-to-graveyard lifecycle.
- **Supporting evidence:** B (Google GooWiki ~90% dead; NASA LLIS two audited decades; modal ADR abandonment ~50% at 1–5 records; AKM tools zero uptake; docs-as-code succeeds precisely because maintainers=consumers) · A (KM ~50% initiative failure, cultural causes).
- **Contradictory evidence:** IBM Watson Discovery sustained 80+ ADRs over 2 years — but only with enforcement tooling binding code to records (B). The exception proves the rule: binding created a same-population gesture.
- **Consequence:** the platform introduces **no new stores, portals, or repositories**; every knowledge capability extends a gesture that already exists (commit, review, session start, qualification run).

**PK-P2 · Status-at-Discovery Principle**
- **Statement:** The validity status of knowledge (canonical / historical / deprecated / draft) must be visible at the point of discovery, not the point of curation.
- **Rationale:** The master failure signature across four independent domains reduces to: the consumer cannot distinguish valid from stale at retrieval time.
- **Supporting evidence:** A (NASA retrieval failure; "validity status invisible at the point of discovery") · C (Wikidata ranks; ROT practice) · D (RAG freshness rot: similarity has zero correlation with recency) · B (wiki trust collapse; newcomers harmed most because they cannot judge validity).
- **Contradictory evidence:** none found; the only tension is cost of stamping status — addressed by PK-P1 (status must ride existing gestures).
- **Consequence:** no retrieval surface (human navigation, search, AI context assembly) may present content without its status; a bare document is an architectural defect.

**PK-P3 · Claim-Granularity Principle**
- **Statement:** Knowledge quality attaches at the granularity of the individual claim — a context-bound, provenance-carrying, status-bearing assertion — not at the page or document.
- **Rationale:** Four disciplines converged independently on this unit; bundle-level governance provably cannot detect partial staleness.
- **Supporting evidence:** C (nanopublication; assessable claim) · A (knowledge claim, McElroy; "a page is fresh while half its claims are false") · D ("the smallest span that remains true when read alone") · B (term-bound-to-context; the unit updated in one gesture).
- **Contradictory evidence:** C/D (atomization destroys argumentative context — decontextualization strips caveats and changes decisions; nanopublication overhead confined it to niches).
- **Consequence:** claim-level quality metadata is the *reference model*; composite forms (decision records, guides) remain the units of comprehension — the architecture must support both granularities without forcing atomization.

**PK-P4 · Execution-Proximity Principle**
- **Statement:** Trust in knowledge is stratified by proximity to executable reality; validation-by-execution is the only mechanism proven to keep an artifact true.
- **Rationale:** Accuracy–consultation correlation runs r=0.67 (testing docs) to 0.03 (specifications); everything non-executable relies on social process and decays.
- **Supporting evidence:** B (trust hierarchy: running code > tests > code-proximate docs > architecture docs > wikis) · D (curated-KB grounding 6% vs 35% hallucination) · A (in-flow preference).
- **Contradictory evidence:** B/C5-auto (automation *relocates* staleness — generated views of an unmaintained model are "a graveyard with a build step").
- **Consequence:** prefer knowledge forms that execute or are bound to execution; the further a form sits from execution, the heavier its governance metadata must be — and the lower its default authority.

**PK-P5 · Two-Regime Evolution Principle**
- **Statement:** Knowledge evolves under exactly two regimes: **decisions** are immutable and superseded by link; **descriptions** evolve or regenerate with their referent. Every artifact declares its regime.
- **Rationale:** The Nygard/Martraire contradiction resolves in practice as regime-per-class, never one rule for all.
- **Supporting evidence:** B (C3 reconciliation; ADR = only mainstream form with retirement semantics; dated decisions stay true as historical facts) · C (statement ranks; supersession-is-a-link) · A (falsified claims retained as informative).
- **Contradictory evidence:** boundary artifacts exist (part decision, part description — e.g., design documents); regime assignment for them is an open question (§8).
- **Consequence:** the lifecycle (D6) forks at evolution; "which regime?" is a mandatory property of every knowledge class the future architecture defines.

**PK-P6 · Minimal-Sufficient-Context Principle**
- **Statement:** Retrieval is task-driven, not document-driven: the goal is the smallest assembly of claims that lets the task proceed, for human and AI consumers alike.
- **Rationale:** Unfiltered volume measurably harms both consumer types.
- **Supporting evidence:** D (context rot in 18/18 frontier models; masking +2.6% at −52% cost; assembly optimization 89.1% vs 70.7%; CLT redundancy and split-attention effects; documents read in fragments at moment of need — B).
- **Contradictory evidence:** D (over-pruning drops the one required caveat; selection becomes the new error source).
- **Consequence:** context is perishable and assembled per task; the architecture treats *assembly* as the first-class act, storage as substrate.

**PK-P7 · Coexistence-with-Status Principle**
- **Statement:** Contradictory knowledge coexists at rest under explicit ranked status; resolution happens at the point of consumption; demotion is visible and reasoned, never a silent edit.
- **Rationale:** Forcing resolution at storage destroys information; the largest running systems curate plural truths.
- **Supporting evidence:** C (Wikidata preferred/normal/deprecated + reason; WP conflicting-sources; C3 as the deepest IQ dispute with an observed synthesis).
- **Contradictory evidence:** D (machine consumers acting on knowledge need one value; mainstream AI retrieval has no conflict adjudication — a named open gap, §8).
- **Consequence:** the architecture never has a "delete the losing fact" operation; it has rank, reason, and resolve-at-read.

**PK-P8 · Staged-Forgetting Principle**
- **Statement:** Retirement is a designed lifecycle stage: an explicit status transition through demotion and archival — never silent deletion, never neglect.
- **Rationale:** Unretired stale knowledge is actively harmful (newcomer dropout, AI grounding poisoning), while destroyed knowledge breaks rationale archaeology.
- **Supporting evidence:** C (ROT staged disposal; archive = out of navigation, in history) · A (retirement the least-designed stage everywhere; maintain-or-divest gates only in late KM models) · B (default retirement mode = silent abandonment discovered by a confused reader; stale worse than none — Steinmacher).
- **Contradictory evidence:** A/C4 (managed-forgetting-as-hygiene vs forgetting-as-failure remains a genuinely open dispute — but both sides support *explicit* mechanics over the status quo of neglect).
- **Consequence:** every knowledge class the architecture defines must name its retirement path before it names its creation path.

**PK-P9 · Succession-over-Capture Principle**
- **Statement:** Tacit knowledge is transferred by practice and succession, not by capture mandates; the platform measures concentration risk and supports succession mechanics — it never demands externalization of what cannot be externalized.
- **Rationale:** The dominant conversion theory (SECI) lacks empirical grounding; offboarding "write-it-down" residue fails at the moment of need; the only measured mitigations are succession mechanics (~15–25% loss reduction).
- **Supporting evidence:** A (SECI critique; Polanyi line) · B (Robillard; Rigby fat-tailed losses; truck factor ≤2 in 65% of projects; Google onboarding = apprenticeship).
- **Contradictory evidence:** decision-time capture of *rationale* is worthwhile and different — the capture window argument (C: knowledge vaporization) applies to decisions, not to skill.
- **Consequence:** the architecture distinguishes rationale (capturable at decision time, PK-P5 regime 1) from skill (succession only); no deliverable of the future platform is a tacit-knowledge repository.

**PK-P10 · Scent Principle**
- **Statement:** Discoverability is a property of labels, addresses, and summaries — not of content. Knowledge that emits no scent is functionally nonexistent.
- **Rationale:** Information foraging is one of the few empirically validated theories in the field; the NASA failure was a scent failure, not a storage failure.
- **Supporting evidence:** C (Pirolli & Card; Nielsen abandonment data; ROT dilutes collection-level scent) · D (agentic search depends on repository legibility — naming and structure ARE knowledge architecture).
- **Contradictory evidence:** none substantive ("search makes scent obsolete" is answered within the theory: snippets are scent surfaces).
- **Consequence:** naming, titling, summarization, and addressability are architectural obligations of every knowledge producer — enforceable, checkable, and cheap.

## Deliverable 2 — Architectural Decisions (from the major contradictions; direction only, no implementation)

| # | Contradiction | Alternatives & trade-offs | Evidence | **Recommendation** |
|---|---|---|---|---|
| PK-AD1 | **Centralized vs co-located knowledge** (A/C5) | Central store: one governance surface, but second-population + drift-by-construction. Co-located: survives, but governance fragments. | B (code-proximate = only promptly-updated class) · A (canonical-but-stale teaches routing-around) · D (live-source discovery displaced indexes) | **Co-located content, centralized *governance metadata only*** (status vocabulary, ownership registry). Scoped canonicity per PK-P2/P4. |
| PK-AD2 | **Documentation vs executable knowledge** | Docs: expressive, decays. Executable: stays true, limited expressiveness. | B (trust stratification; the one longitudinal ADR success required code-binding) | **Executable where possible; bind the rest to execution** (checks, links, enforcement); unbound prose is lowest-authority by default. |
| PK-AD3 | **Immutable vs evolving artifacts** (B/C3) | Immutability preserves history, risks staleness; evolution preserves currency, destroys history. | B, C, A converge on regime-per-class | **Both, by declared regime** (PK-P5). Neither is "the" rule. |
| PK-AD4 | **Explicit vs emergent ontology** (C/C1–C2) | Formal ontology: precision, catastrophic cost curve (Cyc, Semantic Web). Emergent: cheap, noisy. | C (hybrids outperform; faceted classification is the surviving theory; minimal cores win — PROV) | **Minimal explicit core + emergent enrichment; faceted, never enumerative.** No comprehensive ontology, ever. |
| PK-AD5 | **Capture everything vs selectively** (C/C5, D) | Everything: fights vaporization, becomes ROT; add-all memory measurably harms. Selective: risks losing the un-recaptureable. | D (curation +10%, add-all < none) · C (vaporization at decision time) | **Selective with intake gates — except decisions, which are captured at decision time or never.** The capture window is the exception that justifies urgency, not volume. |
| PK-AD6 | **Global SSOT vs scoped canonicity** (C/C4) | Global: one place, becomes a stale mirror. Scoped: honest, needs scope discipline. | C (SSOT's own prerequisite empirically false; practitioner consensus moved) | **Scoped canonicity, reference-based reuse.** Global SSOT is rejected. |
| PK-AD7 | **Forgetting as failure vs hygiene** (A/C4, C/C7) | Prevent-all-forgetting: hoarding, scent dilution. Managed forgetting: risk of losing vital knowledge. | C (ROT; demote-visibly synthesis) · B (stale worse than none) | **Managed forgetting as a designed capability** with the safety rail: demote visibly, archive retrievably, never destroy silently. |
| PK-AD8 | **Pre-indexed retrieval vs live discovery** (D) | Index: cheap queries, silent staleness, maintenance burden. Live agentic discovery: always current, token-expensive. | D (production abandonment of code RAG; freshness solved structurally; economics contested — Milvus) | **Live sources are the default for code-proximate knowledge; indexes only where staleness is governed** (status+freshness metadata). Economics = open question (§8). |

## Deliverable 3 — Candidate Bounded Contexts (conceptual responsibilities only; all are CANDIDATES)

| Candidate | Conceptual responsibility | Evidence anchor |
|---|---|---|
| **Knowledge Qualification** | Intake validation (born-stale gate), claim evaluation, freshness verification over time | A (McElroy claim gate; born-stale 58.4%; validation-at-intake almost never implemented) · C (F3.3) |
| **Knowledge Discovery & Assembly** | In-flow retrieval, task-driven context assembly, scent enforcement | D (assembly gap, minimal sufficient context) · C (foraging) · B (task-situated questions; fragments at moment of need) |
| **Knowledge Governance** | Status vocabulary, scoped authority, ownership + freshness, compliance | A/C quality treatments (authority = process property; ownerless = non-authoritative) |
| **Knowledge Evolution & Retirement** | Regime management (immutable vs evolving), supersession, staged forgetting | B/C3 · C (ranks, ROT) · A (maintain-or-divest) |
| **Knowledge Provenance** | Lineage, custody, trust metadata riding every claim | C (PROV minimal core; nanopublications; provenance as the quality substrate) |

INTERPRETATION worth recording: these candidates are **self-similar to the Engineering Platform's own bounded contexts** (Verification & Evidence ≙ Qualification; Session Continuity ≙ Discovery & Assembly; Knowledge Governance appears in both). The platform may already be a partial implementation of the knowledge domain applied to engineering knowledge. Whether that similarity is deep (one domain, two adoptions) or superficial is an architecture-phase question — flagged, not decided.

## Deliverable 4 — Domain Invariants (every future implementation must satisfy; all evidence-anchored)

1. **Knowledge never silently disappears** — retirement is a status transition through archive; deletion of the last copy of a claim is forbidden. (C-ROT, A-divest, B-Steinmacher)
2. **Status transitions are explicit, reasoned, and visible at discovery.** (PK-P2 evidence set)
3. **Provenance is mandatory on every claim** — producer, time, context; a claim without provenance is data, not knowledge. (C-nanopub/PROV, A-McElroy)
4. **Contradictions remain visible** — coexist at rest under rank; no silent-pick, no silent-edit resolution. (C-Wikidata; D-open-gap)
5. **Authority is scoped** — canonical-within-a-scope; no artifact claims global truth. (C-F3.4)
6. **Freshness is observable at the point of discovery** — and priced per domain half-life, not uniformly. (A-Argote; D-freshness rot)
7. **Supersession is a link, never an edit** — the superseded record keeps its truth as history. (B-Nygard; C-ranks; matches the platform's existing append-only law)
8. **No knowledge capability may create a second-population artifact class.** (PK-P1 — the law as an invariant)
9. **Knowledge quality is evidenced, never asserted** — freshness, authority, and trust derive from recorded process properties (owner, date, gate results), never from self-description. (B-Google model; AIP-10 continuity)

## Deliverable 5 — Architectural Forces

| Force | Influence on architecture | Evidence |
|---|---|---|
| **Trust** (stratified, asymmetric, stateful) | The architecture must never assume uniform trust; one betrayal reroutes consumers permanently — so quality failures are catastrophic, not incremental | B (r-gradient; burn-once) · A (trust spiral) |
| **Freshness / entropy** | Decay is domain-relative and much knowledge is born stale — freshness must be observable AND gated at intake | A (17%/wk–3%/mo; born-stale) · C (F3.3) |
| **Discoverability** | Scent is architectural: labels, addresses, summaries carry the finding function; collections rot scent-wise | C (foraging, ROT) |
| **Context assembly** | Task-driven minimal assembly is the consumption model for both humans and AI; storage is substrate | D (rot, masking, MSC) · B (fragments at need) |
| **Human cognition** | Chunking, split-attention, redundancy govern packaging; the transferable unit is the worked example; consistency enables expert chunking | D (CLT, schemas/beacons) |
| **AI context limitations** | Context is perishable; injected context is currently assumed trustworthy (unverified); conflict adjudication for machine consumers is unsolved | D (18/18 rot; open gaps) |
| **Organizational learning / turnover** | Knowledge lives in mental models; loss is fat-tailed; succession beats capture for skill; decision rationale has a narrow capture window | B (truck factor, Rigby) · A (retention bins) |

## Deliverable 6 — Conceptual Knowledge Lifecycle (not software, not workflow)

```text
Creation ──► Qualification ──► Publication ──► Discovery & Consumption ──► Evolution ─┬─► [descriptions] evolve/regenerate ──► (loop)
                                                                                      └─► [decisions] Supersession (by link)
                                                                                                        │
                                                                              Historical Retention ◄────┘
                                                                                                        │
                                                                                        Staged Retirement (archive)
```

| Stage | What happens | Evidence | Invariants in force |
|---|---|---|---|
| **Creation** | A claim is articulated inside an existing gesture; decision rationale is captured at decision time or never | C (vaporization) · B (same-gesture survival) | 3, 8 |
| **Qualification** | Intake validation: born-stale gate, claim evaluation, provenance check | A (McElroy; 58.4% born stale) | 3, 9 |
| **Publication** | The claim becomes discoverable with status, owner, freshness, and scent | B (Google owner+date model) · C (scent) | 2, 5, 6 |
| **Discovery & Consumption** | Task-driven assembly of minimal sufficient claims; consumption feeds back as validation ("validation is use") | D · B (in-flow) | 2, 4, 6 |
| **Evolution** | Regime fork: descriptions evolve/regenerate with the referent; decisions never change | B/C3 · C | 7 |
| **Supersession** | A new claim demotes the old by link, with a reason | C (ranks) · B (Nygard) | 4, 7 |
| **Historical retention** | Demoted knowledge remains retrievable, marked, out of default navigation | C (archive state) | 1, 2 |
| **Staged retirement** | Deliberate, owned, periodic — never silent abandonment | C (ROT cadence) · A (divest gate) | 1, 8 |

## Deliverable 7 — Capability Map (capabilities only)

| Capability | One-line description | Evidence |
|---|---|---|
| **Knowledge Discovery** | Find relevant claims in-flow, by scent and by live-source search | C, D, B |
| **Context Assembly** | Compose minimal sufficient task context for a human or AI consumer | D (the observed gap; the one NEW capability the Discovery Report recommended) |
| **Knowledge Qualification** | Gate intake (born-stale), evaluate claims, verify freshness over time | A, C |
| **Knowledge Authority & Status** | Scoped canonicity, status vocabulary, ownership, visible-at-discovery | A, B, C |
| **Knowledge Provenance** | Lineage and trust metadata on every claim | C |
| **Knowledge Evolution & Supersession** | Regime-aware change: evolve descriptions, supersede decisions | B, C |
| **Knowledge Retirement** | Staged forgetting: demote, archive, audit cadence | C, A |
| **Knowledge Governance** | The invariants (D4) as enforceable, checkable rules | all |
| **Succession Support** | Concentration-risk measurement and succession mechanics for tacit knowledge | B (the only measured mitigation) — deliberately NOT a capture capability |

## Deliverable 8 — Readiness Assessment

| Question | Answer |
|---|---|
| **Is the evidence sufficient to begin architecture?** | **Conditionally yes.** The principles (D1), invariants (D4), lifecycle (D6), and decisions (D2) are triangulated across independent executors and stable enough to constitute a constitutional foundation. Three evidence gaps remain, none of which blocks *writing* a reference architecture, but all of which block *committing* to one. |
| **Recommended next step (if the ARB authorizes)** | (1) Run the **EKP in-flow usage test** first — behavioral, cheap, decisive for the incumbent's role (session logs of PB-004..007: was `docs/knowledge/` consulted?). (2) Then a **Project Knowledge Reference Architecture** derived from this synthesis — same DRAFT→ADOPTED→STABLE lifecycle as the platform's own. (3) Unpause **ER-09** reframed under PK-P1..P10 (document rules become bindings of the knowledge principles). All sequenced behind the existing execution queue (C3, product primacy). |
| **Missing evidence** | E-1: the EKP usage test (unrun). E-2: conflict adjudication for machine consumers (open in the literature — a pilot would produce first-party evidence). E-3: token economics of live discovery vs curated assembly at this repository's scale (measurable during normal sessions). |
| **Biggest remaining uncertainty** | Whether **in-flow context assembly** can be built without violating PK-P1 itself — i.e., without becoming a curated second-population artifact. The design answer must come from a small evidence-producing pilot on real tickets, not from further synthesis. |

---

**Constraint check:** no folders, templates, software, APIs, aggregates, or interactions designed; every principle, decision, invariant, force, stage, and capability carries an evidence anchor; no new research performed; all concepts traceable to the closed evidence base. **STOP — awaiting ARB review of this synthesis before any architecture is written.**
