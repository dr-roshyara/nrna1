# V-2 Professor extraction — RQ-002 Architectural Synthesis

```yaml
source_path: docs/implementation/RQ-002_Architectural_Synthesis.md
sha256: e271fb4a42137b2c6bfb30164aba63c4634748740f9b595bc7b2c189458ab26a
lines_total: 177
lines_read: 177
extractor: Professor (Session B), blind to the reviewer; B-08 not consulted
```

## Items

> Q1 L3-3: "the bridge between research and architecture"
- Q1 · DEF · Kind: architectural synthesis, not design; status complete, awaiting ARB.

> Q2 L4-4: "No new research was performed; every statement below is traceable to this base"
- Q2 · ASM · Closed evidence base.

> Q3 L5-5: "traceable · falsifiable by future research · implementation-agnostic · governance-ready."
- Q3 · CLM · Output tests.

> Q4 L12-12: "Knowledge capabilities must live inside existing workflow gestures, maintained and consumed by the same population."
- Q4 · INV · PK-P1 Same-Population statement.

> Q5 L13-13: "Every artifact class requiring a second population or a separate maintenance gesture has a documented decay-to-graveyard lifecycle."
- Q5 · CLM · PK-P1 rationale.

> Q6 L14-14: "docs-as-code succeeds precisely because maintainers=consumers"
- Q6 · CLM · PK-P1 supporting evidence.

> Q7 L15-15: "The exception proves the rule: binding created a same-population gesture."
- Q7 · CLM · PK-P1 contradictory evidence (Watson Discovery) reinterpreted.

> Q8 L16-16: "the platform introduces **no new stores, portals, or repositories**"
- Q8 · CLM · PK-P1 consequence.

> Q9 L19-19: "The validity status of knowledge (canonical / historical / deprecated / draft) must be visible at the point of discovery, not the point of curation."
- Q9 · INV · PK-P2 Status-at-Discovery statement.

> Q10 L20-20: "the consumer cannot distinguish valid from stale at retrieval time."
- Q10 · CLM · PK-P2 rationale.

> Q11 L21-21: "similarity has zero correlation with recency"
- Q11 · CLM · PK-P2 supporting evidence.

> Q12 L22-22: "none found; the only tension is cost of stamping status"
- Q12 · CLM · PK-P2 contradictory evidence.

> Q13 L23-23: "a bare document is an architectural defect."
- Q13 · CLM · PK-P2 consequence.

> Q14 L26-26: "Knowledge quality attaches at the granularity of the individual claim — a context-bound, provenance-carrying, status-bearing assertion — not at the page or document."
- Q14 · INV · PK-P3 Claim-Granularity statement.

> Q15 L27-27: "bundle-level governance provably cannot detect partial staleness."
- Q15 · CLM · PK-P3 rationale.

> Q16 L28-28: "the smallest span that remains true when read alone"
- Q16 · CLM · PK-P3 supporting evidence (nanopublication, knowledge claim, smallest true span, term-bound-to-context).

> Q17 L29-29: "atomization destroys argumentative context — decontextualization strips caveats and changes decisions"
- Q17 · CLM · PK-P3 contradictory evidence.

> Q18 L30-30: "the architecture must support both granularities without forcing atomization."
- Q18 · CLM · PK-P3 consequence.

> Q19 L33-33: "Trust in knowledge is stratified by proximity to executable reality; validation-by-execution is the only mechanism proven to keep an artifact true."
- Q19 · INV · PK-P4 Execution-Proximity statement.

> Q20 L34-34: "Accuracy–consultation correlation runs r=0.67 (testing docs) to 0.03 (specifications)"
- Q20 · CLM · PK-P4 rationale.

> Q21 L35-35: "running code > tests > code-proximate docs > architecture docs > wikis"
- Q21 · CLM · PK-P4 supporting evidence.

> Q22 L36-36: "automation *relocates* staleness"
- Q22 · CLM · PK-P4 contradictory evidence.

> Q23 L37-37: "the further a form sits from execution, the heavier its governance metadata must be — and the lower its default authority."
- Q23 · CLM · PK-P4 consequence.

> Q24 L40-40: "Knowledge evolves under exactly two regimes: **decisions** are immutable and superseded by link; **descriptions** evolve or regenerate with their referent. Every artifact declares its regime."
- Q24 · INV · PK-P5 Two-Regime statement.

> Q25 L41-41: "The Nygard/Martraire contradiction resolves in practice as regime-per-class, never one rule for all."
- Q25 · CLM · PK-P5 rationale.

> Q26 L42-42: "dated decisions stay true as historical facts"
- Q26 · CLM · PK-P5 supporting evidence.

> Q27 L43-43: "boundary artifacts exist (part decision, part description — e.g., design documents)"
- Q27 · CLM · PK-P5 contradictory evidence.

> Q28 L44-44: ""which regime?" is a mandatory property of every knowledge class the future architecture defines."
- Q28 · CLM · PK-P5 consequence.

> Q29 L47-47: "Retrieval is task-driven, not document-driven: the goal is the smallest assembly of claims that lets the task proceed, for human and AI consumers alike."
- Q29 · INV · PK-P6 Minimal-Sufficient-Context statement.

> Q30 L48-48: "Unfiltered volume measurably harms both consumer types."
- Q30 · CLM · PK-P6 rationale.

> Q31 L49-49: "context rot in 18/18 frontier models"
- Q31 · CLM · PK-P6 supporting evidence.

> Q32 L50-50: "over-pruning drops the one required caveat; selection becomes the new error source"
- Q32 · CLM · PK-P6 contradictory evidence.

> Q33 L51-51: "the architecture treats *assembly* as the first-class act, storage as substrate."
- Q33 · CLM · PK-P6 consequence.

> Q34 L54-54: "Contradictory knowledge coexists at rest under explicit ranked status; resolution happens at the point of consumption; demotion is visible and reasoned, never a silent edit."
- Q34 · INV · PK-P7 Coexistence-with-Status statement.

> Q35 L55-55: "Forcing resolution at storage destroys information"
- Q35 · CLM · PK-P7 rationale.

> Q36 L56-56: "Wikidata preferred/normal/deprecated + reason"
- Q36 · CLM · PK-P7 supporting evidence.

> Q37 L57-57: "machine consumers acting on knowledge need one value"
- Q37 · CLM · PK-P7 contradictory evidence (open gap).

> Q38 L58-58: "the architecture never has a "delete the losing fact" operation; it has rank, reason, and resolve-at-read."
- Q38 · CLM · PK-P7 consequence.

> Q39 L61-61: "Retirement is a designed lifecycle stage: an explicit status transition through demotion and archival — never silent deletion, never neglect."
- Q39 · INV · PK-P8 Staged-Forgetting statement.

> Q40 L62-62: "Unretired stale knowledge is actively harmful (newcomer dropout, AI grounding poisoning), while destroyed knowledge breaks rationale archaeology."
- Q40 · CLM · PK-P8 rationale.

> Q41 L63-63: "default retirement mode = silent abandonment discovered by a confused reader"
- Q41 · CLM · PK-P8 supporting evidence.

> Q42 L64-64: "both sides support *explicit* mechanics over the status quo of neglect"
- Q42 · CLM · PK-P8 contradictory evidence (open dispute).

> Q43 L65-65: "every knowledge class the architecture defines must name its retirement path before it names its creation path."
- Q43 · CLM · PK-P8 consequence.

> Q44 L68-68: "Tacit knowledge is transferred by practice and succession, not by capture mandates"
- Q44 · INV · PK-P9 Succession-over-Capture statement.

> Q45 L69-69: "The dominant conversion theory (SECI) lacks empirical grounding"
- Q45 · CLM · PK-P9 rationale.

> Q46 L70-70: "truck factor ≤2 in 65% of projects"
- Q46 · CLM · PK-P9 supporting evidence.

> Q47 L71-71: "decision-time capture of *rationale* is worthwhile and different"
- Q47 · CLM · PK-P9 contradictory evidence → rationale vs skill distinction.

> Q48 L72-72: "no deliverable of the future platform is a tacit-knowledge repository."
- Q48 · CLM · PK-P9 consequence.

> Q49 L75-75: "Discoverability is a property of labels, addresses, and summaries — not of content. Knowledge that emits no scent is functionally nonexistent."
- Q49 · INV · PK-P10 Scent statement.

> Q50 L76-76: "the NASA failure was a scent failure, not a storage failure."
- Q50 · CLM · PK-P10 rationale.

> Q51 L77-77: "naming and structure ARE knowledge architecture"
- Q51 · CLM · PK-P10 supporting evidence.

> Q52 L78-78: "snippets are scent surfaces"
- Q52 · CLM · PK-P10 contradictory evidence answered.

> Q53 L79-79: "naming, titling, summarization, and addressability are architectural obligations of every knowledge producer"
- Q53 · CLM · PK-P10 consequence.

> Q54 L85-85: "**Co-located content, centralized *governance metadata only***"
- Q54 · CLM · PK-AD1 centralized vs co-located.

> Q55 L86-86: "**Executable where possible; bind the rest to execution**"
- Q55 · CLM · PK-AD2 documentation vs executable.

> Q56 L87-87: "**Both, by declared regime** (PK-P5). Neither is "the" rule."
- Q56 · CLM · PK-AD3 immutable vs evolving.

> Q57 L88-88: "**Minimal explicit core + emergent enrichment; faceted, never enumerative.** No comprehensive ontology, ever."
- Q57 · CLM · PK-AD4 explicit vs emergent ontology.

> Q58 L89-89: "**Selective with intake gates — except decisions, which are captured at decision time or never.**"
- Q58 · CLM · PK-AD5 capture.

> Q59 L90-90: "**Scoped canonicity, reference-based reuse.** Global SSOT is rejected."
- Q59 · CLM · PK-AD6 SSOT.

> Q60 L91-91: "**Managed forgetting as a designed capability** with the safety rail: demote visibly, archive retrievably, never destroy silently."
- Q60 · CLM · PK-AD7 forgetting.

> Q61 L92-92: "**Live sources are the default for code-proximate knowledge; indexes only where staleness is governed**"
- Q61 · CLM · PK-AD8 retrieval.

> Q62 L98-98: "**Knowledge Qualification** | Intake validation (born-stale gate), claim evaluation, freshness verification over time"
- Q62 · DEF · Candidate BC Knowledge Qualification.

> Q63 L99-99: "**Knowledge Discovery & Assembly** | In-flow retrieval, task-driven context assembly, scent enforcement"
- Q63 · DEF · Candidate BC Discovery & Assembly.

> Q64 L100-100: "**Knowledge Governance** | Status vocabulary, scoped authority, ownership + freshness, compliance"
- Q64 · DEF · Candidate BC Knowledge Governance.

> Q65 L101-101: "**Knowledge Evolution & Retirement** | Regime management (immutable vs evolving), supersession, staged forgetting"
- Q65 · DEF · Candidate BC Evolution & Retirement.

> Q66 L102-102: "**Knowledge Provenance** | Lineage, custody, trust metadata riding every claim"
- Q66 · DEF · Candidate BC Provenance.

> Q67 L104-104: "these candidates are **self-similar to the Engineering Platform's own bounded contexts**"
- Q67 · CLM · [INTERPRETATION in source] Self-similarity to platform contexts.

> Q68 L104-104: "Whether that similarity is deep (one domain, two adoptions) or superficial is an architecture-phase question — flagged, not decided."
- Q68 · CLM · The question is flagged, not decided.

> Q69 L108-108: "**Knowledge never silently disappears** — retirement is a status transition through archive; deletion of the last copy of a claim is forbidden."
- Q69 · INV · D4 invariant 1.

> Q70 L109-109: "**Status transitions are explicit, reasoned, and visible at discovery.**"
- Q70 · INV · D4 invariant 2.

> Q71 L110-110: "a claim without provenance is data, not knowledge."
- Q71 · INV · D4 invariant 3 (provenance mandatory).

> Q72 L111-111: "**Contradictions remain visible** — coexist at rest under rank; no silent-pick, no silent-edit resolution."
- Q72 · INV · D4 invariant 4.

> Q73 L112-112: "**Authority is scoped** — canonical-within-a-scope; no artifact claims global truth."
- Q73 · INV · D4 invariant 5.

> Q74 L113-113: "**Freshness is observable at the point of discovery** — and priced per domain half-life, not uniformly."
- Q74 · INV · D4 invariant 6.

> Q75 L114-114: "**Supersession is a link, never an edit** — the superseded record keeps its truth as history."
- Q75 · INV · D4 invariant 7.

> Q76 L115-115: "**No knowledge capability may create a second-population artifact class.**"
- Q76 · INV · D4 invariant 8.

> Q77 L116-116: "**Knowledge quality is evidenced, never asserted** — freshness, authority, and trust derive from recorded process properties (owner, date, gate results), never from self-description."
- Q77 · INV · D4 invariant 9.

> Q78 L122-122: "one betrayal reroutes consumers permanently — so quality failures are catastrophic, not incremental"
- Q78 · CLM · Force: Trust.

> Q79 L123-123: "Decay is domain-relative and much knowledge is born stale — freshness must be observable AND gated at intake"
- Q79 · CLM · Force: Freshness/entropy.

> Q80 L124-124: "Scent is architectural: labels, addresses, summaries carry the finding function; collections rot scent-wise"
- Q80 · CLM · Force: Discoverability.

> Q81 L125-125: "Task-driven minimal assembly is the consumption model for both humans and AI; storage is substrate"
- Q81 · CLM · Force: Context assembly.

> Q82 L126-126: "the transferable unit is the worked example"
- Q82 · CLM · Force: Human cognition.

> Q83 L127-127: "conflict adjudication for machine consumers is unsolved"
- Q83 · CLM · Force: AI context limitations.

> Q84 L128-128: "succession beats capture for skill; decision rationale has a narrow capture window"
- Q84 · CLM · Force: Organizational learning/turnover.

> Q85 L133-138: "Creation ──► Qualification ──► Publication ──► Discovery & Consumption ──► Evolution"
- Q85 · REL · Lifecycle diagram: evolution forks (descriptions loop; decisions → supersession → historical retention → staged retirement).

> Q86 L143-143: "A claim is articulated inside an existing gesture; decision rationale is captured at decision time or never"
- Q86 · DEF · Stage Creation (invariants 3, 8).

> Q87 L144-144: "Intake validation: born-stale gate, claim evaluation, provenance check"
- Q87 · DEF · Stage Qualification (3, 9).

> Q88 L145-145: "The claim becomes discoverable with status, owner, freshness, and scent"
- Q88 · DEF · Stage Publication (2, 5, 6).

> Q89 L146-146: "consumption feeds back as validation ("validation is use")"
- Q89 · DEF · Stage Discovery & Consumption (2, 4, 6).

> Q90 L147-147: "Regime fork: descriptions evolve/regenerate with the referent; decisions never change"
- Q90 · DEF · Stage Evolution (7).

> Q91 L148-148: "A new claim demotes the old by link, with a reason"
- Q91 · DEF · Stage Supersession (4, 7).

> Q92 L149-149: "Demoted knowledge remains retrievable, marked, out of default navigation"
- Q92 · DEF · Stage Historical retention (1, 2).

> Q93 L150-150: "Deliberate, owned, periodic — never silent abandonment"
- Q93 · DEF · Stage Staged retirement (1, 8).

> Q94 L156-156: "**Knowledge Discovery** | Find relevant claims in-flow, by scent and by live-source search"
- Q94 · DEF · Capability Knowledge Discovery.

> Q95 L157-157: "**Context Assembly** | Compose minimal sufficient task context for a human or AI consumer"
- Q95 · DEF · Capability Context Assembly (the one new capability).

> Q96 L158-158: "**Knowledge Qualification** | Gate intake (born-stale), evaluate claims, verify freshness over time"
- Q96 · DEF · Capability Knowledge Qualification.

> Q97 L159-159: "**Knowledge Authority & Status** | Scoped canonicity, status vocabulary, ownership, visible-at-discovery"
- Q97 · DEF · Capability Authority & Status.

> Q98 L160-160: "**Knowledge Provenance** | Lineage and trust metadata on every claim"
- Q98 · DEF · Capability Provenance.

> Q99 L161-161: "**Knowledge Evolution & Supersession** | Regime-aware change: evolve descriptions, supersede decisions"
- Q99 · DEF · Capability Evolution & Supersession.

> Q100 L162-162: "**Knowledge Retirement** | Staged forgetting: demote, archive, audit cadence"
- Q100 · DEF · Capability Retirement.

> Q101 L163-163: "**Knowledge Governance** | The invariants (D4) as enforceable, checkable rules"
- Q101 · DEF · Capability Governance.

> Q102 L164-164: "deliberately NOT a capture capability"
- Q102 · DEF · Capability Succession Support (measurement, not capture).

> Q103 L170-170: "**Conditionally yes.**"
- Q103 · CLM · Readiness: evidence sufficient to begin, not to commit.

> Q104 L171-171: "(1) Run the **EKP in-flow usage test** first"
- Q104 · CLM · Recommended next steps (usage test → reference architecture → ER-09 unpause).

> Q105 L172-172: "E-1: the EKP usage test (unrun). E-2: conflict adjudication for machine consumers"
- Q105 · CLM · Missing evidence E-1..E-3.

> Q106 L173-173: "Whether **in-flow context assembly** can be built without violating PK-P1 itself"
- Q106 · CLM · Biggest remaining uncertainty.

> Q107 L177-177: "**STOP — awaiting ARB review of this synthesis before any architecture is written.**"
- Q107 · CLM · Constraint check and gate.

## Line accounting (NOT-EXTRACTED)

| Lines | Reason |
|---|---|
| 1 | FRONTMATTER (title) |
| 2, 6–8, 10, 17, 24, 31, 38, 45, 52, 59, 66, 73, 80, 82, 93, 95, 103, 105, 107, 117, 119, 129, 131, 140, 151, 153, 165, 167, 174–176 | BOILERPLATE (blank lines, separators) |
| 9, 11, 18, 25, 32, 39, 46, 53, 60, 67, 74, 81, 94, 106, 118, 130, 152, 166 | NAVIGATION (section and principle headings) |
| 132, 139 | BOILERPLATE (code fences) |
| 83–84, 96–97, 120–121, 141–142, 154–155, 168–169 | BOILERPLATE (table headers) |

## Uncertainties noticed

- [?] The Synthesis has nine invariants (L108–116); the Constitutional Model later has eleven, adding "representation transitions" and the telos. Revision across documents [I].
