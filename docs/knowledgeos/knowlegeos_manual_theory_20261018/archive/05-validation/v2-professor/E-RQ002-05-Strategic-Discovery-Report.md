# V-2 Professor extraction — RQ-002 Strategic Discovery Report

```yaml
source_path: docs/implementation/RQ-002_Strategic_Discovery_Report.md
sha256: 81d61aba6c7149b1d73b81aa531ecd13ca30023188d77b8f6765a44a0e88df9f
lines_total: 116
lines_read: 116
extractor: Professor (Session B), blind to the reviewer; B-08 not consulted
```

## Items

> Q1 L3-3: "four executors · 7 disciplines · ~120 sources · 55 NKMs · 33 negative findings · 35 mapped contradictions"
- Q1 · CLM · Scope of the evidence consumed.

> Q2 L4-4: "**STOP: no architecture may form until the ARB rules on §7.**"
- Q2 · CLM · Status complete; gate on §7.

> Q3 L5-5: "FACT (from literature/implementation) · INTERPRETATION · RECOMMENDATION · OPEN QUESTION."
- Q3 · DEF · The document's own epistemic labels.

> Q4 L13-13: "Project knowledge survives only where its maintainers and its consumers are the same population and updating it is part of an existing workflow gesture."
- Q4 · CLM · FACT 1 same-population law.

> Q5 L13-13: "Every artifact class requiring a *second population* or a *separate maintenance gesture* has a documented decay-to-graveyard lifecycle"
- Q5 · CLM · Corollary of FACT 1 (wikis, LLIS, executable specs, workshop artifacts, glossaries, ADRs).

> Q6 L14-14: "**Validity status is invisible at the point of discovery — the master failure signature.**"
- Q6 · CLM · FACT 2.

> Q7 L14-14: "much knowledge is **born stale** (58.4% of obsolete Stack Overflow answers were obsolete at posting) — so intake validation matters at least as much as maintenance cadence."
- Q7 · CLM · Born-stale corollary.

> Q8 L15-15: "**Composite: the atomic unit of project knowledge is a context-bound, provenance-carrying, status-bearing assertion, co-located with what it governs.**"
- Q8 · DEF · FACT 3: the atomic unit (four-way convergence).

> Q9 L15-15: "systems that govern at page/document granularity cannot detect partial staleness — a page is "fresh" while half its claims are false."
- Q9 · CLM · Corollary of FACT 3.

> Q10 L16-16: "**Trust is stratified by proximity to executable reality, and validation-by-execution is the only mechanism empirically shown to keep an artifact true.**"
- Q10 · CLM · FACT 4.

> Q11 L16-16: "running code > executing tests > code-proximate micro-docs > ADRs/architecture docs > wikis > departed colleagues' notes"
- Q11 · REL · De facto trust hierarchy (an ordering).

> Q12 L16-16: "Trust is asymmetric and stateful"
- Q12 · CLM · Burned once, consumers reroute.

> Q13 L17-17: "**Rationale is the most demanded, least captured, and hardest to validate knowledge**"
- Q13 · CLM · FACT 5; mitigations are succession mechanics.

> Q14 L21-21: "its window is at decision time or never ("knowledge vaporization")"
- Q14 · CLM · Created: conversationally and by doing; SECI lacks grounding.

> Q15 L22-22: "by *use* (knowledge without a consumption feedback loop cannot maintain quality at all)"
- Q15 · CLM · Validated: execution, conversation, claim evaluation, use.

> Q16 L23-23: "two legitimate regimes, never one — **decisions are immutable and superseded by link** (history); **descriptions evolve or regenerate** (state)."
- Q16 · CLM · Evolved: two regimes.

> Q17 L24-24: "knowledge without scent is functionally nonexistent"
- Q17 · CLM · Discovered: foraging; minimal-sufficient context for AI.

> Q18 L25-25: "in fragments, at moment of need, never as wholes"
- Q18 · CLM · Consumed.

> Q19 L26-26: "coexist at rest, resolve at consumption, demote by linked supersession, never silent edit."
- Q19 · CLM · Challenged.

> Q20 L27-27: "two distinct mechanisms — loss (turnover/disuse) vs obsolescence (world changed) — with different remedies"
- Q20 · CLM · Forgotten.

> Q21 L28-28: "correctly, as a **status transition, never deletion**"
- Q21 · CLM · Retired; unretired stale knowledge is harmful.

> Q22 L34-34: "Ownership + status + authority metadata on every card | ✅ **Aligned**"
- Q22 · CLM · EKP gap analysis: metadata aligned.

> Q23 L35-35: "knowledge-lint (executable checks on knowledge) | ✅ **Aligned**"
- Q23 · CLM · EKP lint aligned.

> Q24 L36-36: "`ai/` quarantine (generated ≠ authoritative) | ✅ Aligned"
- Q24 · CLM · EKP AI quarantine aligned.

> Q25 L37-37: "a central store courts the second-population/graveyard pattern"
- Q25 · CLM · EKP central store at risk.

> Q26 L38-38: "Single lifecycle for all knowledge types | ❌ **Falsified**"
- Q26 · REV · EKP single lifecycle falsified.

> Q27 L39-39: "bundle-level governance cannot detect partial staleness; quality attaches at assertion granularity"
- Q27 · CLM · EKP card granularity challenged.

> Q28 L40-40: "*was the EKP consulted during PB-004..007 engineering sessions, or did sessions read raw ADRs/guides/code?*"
- Q28 · CLM · EKP consumption model untested.

> Q29 L44-44: "**Knowledge Claim** (atomic unit: context-bound, provenance-carrying, status-bearing assertion)"
- Q29 · DEF · UL term Knowledge Claim.

> Q30 L44-44: "**Binding vs Descriptive knowledge** (constrains code vs describes state — different validation, different evolution regime)"
- Q30 · DEF · UL term Binding vs Descriptive.

> Q31 L44-44: "**Validation Mechanism** (executable · conversational · social · none — "none" predicts decay)"
- Q31 · DEF · UL term Validation Mechanism.

> Q32 L44-44: "**Same-Population Principle** · **Workflow Gesture** (the update path that already exists)"
- Q32 · DEF · UL terms Same-Population Principle, Workflow Gesture.

> Q33 L44-44: "**Information Scent** (discoverability as a property of labels) · **Discovery-in-Flow** · **Born-Stale** (intake failure, distinct from decay)"
- Q33 · DEF · UL terms Information Scent, Discovery-in-Flow, Born-Stale.

> Q34 L44-44: "**Knowledge Half-Life** (domain-relative depreciation) · **Supersession** (retirement by link, never edit)"
- Q34 · DEF · UL terms Knowledge Half-Life, Supersession.

> Q35 L44-44: "**Staged Retirement** (demote visibly, archive retrievably, never destroy silently)"
- Q35 · DEF · UL term Staged Retirement.

> Q36 L44-44: "**Canonical / Historical / Deprecated** (the status vocabulary — visible at the point of discovery or worthless)"
- Q36 · DEF · UL status vocabulary (three values here; five in the Meta-Model).

> Q37 L44-44: "**Scoped Canonicity** (one truth per scope, never globally)"
- Q37 · DEF · UL term Scoped Canonicity.

> Q38 L44-44: "**Rationale Gap** (most demanded, least captured, hardest to validate) · **Tacit Residue** (what externalization cannot reach; mitigated by succession, not capture)"
- Q38 · DEF · UL terms Rationale Gap, Tacit Residue.

> Q39 L44-44: "**Concentration Risk / Truck Factor** · **Minimal Sufficient Context** (assembly beats volume, for humans and models)"
- Q39 · DEF · UL terms Concentration Risk, Minimal Sufficient Context.

> Q40 L44-44: "**Consumption Feedback Loop** (no use signal → no quality) · **Contradiction Coexistence** (plural ranked truths at rest; resolution at consumption)."
- Q40 · DEF · UL terms Consumption Feedback Loop, Contradiction Coexistence.

> Q41 L46-46: "every term above survives the Markdown-removal test — each names a property of knowledge, not of documents."
- Q41 · CLM · Format-independence check passed.

> Q42 L52-54: "BINDING knowledge        (invariants · tests · gates)"
- Q42 · DEF · Ontology node BINDING: validation execution, change-with-code, authority the passing run.

> Q43 L55-58: "DECISION knowledge       (ADR-class · rulings)"
- Q43 · DEF · Ontology node DECISION: immutable + supersession; the rationale gap lives here.

> Q44 L59-61: "DEFINITIONAL knowledge   (ubiquitous-language terms · context boundaries)"
- Q44 · DEF · Ontology node DEFINITIONAL: conversational validation, term-bound-to-context.

> Q45 L62-64: "DESCRIPTIVE knowledge    (guides · READMEs · views)"
- Q45 · DEF · Ontology node DESCRIPTIVE: survives only code-proximate.

> Q46 L65-67: "EXPERIENTIAL knowledge   (lessons · retro records · qualification evidence)"
- Q46 · DEF · Ontology node EXPERIENTIAL: includes qualification evidence; claim evaluation at intake.

> Q47 L68-70: "TACIT knowledge          (mental models · skills)"
- Q47 · DEF · Ontology node TACIT: transfer by practice/succession only.

> Q48 L71-72: "Cross-cutting properties attaching to EVERY node: provenance · status (visible at discovery) · ownership · freshness (half-life priced per domain) · scent (labels) · granularity (claim-level quality)"
- Q48 · REL · Six cross-cutting properties on every node.

> Q49 L75-75: "**The primary cut is by validation mechanism and population structure — not by topic.**"
- Q49 · CLM · Ontology's primary cut.

> Q50 L79-79: "**Owns:** the status vocabulary and its visibility-at-discovery rule"
- Q50 · CLM · Ownership map: owns (status, intake gates, retirement, scent, claim granularity, context assembly).

> Q51 L80-80: "*(The evidence forbids centralizing them: knowledge lives with what it describes.)*"
- Q51 · CLM · Observes artifacts in place.

> Q52 L81-81: "**Never touches:** the content of knowledge (producers own their claims)"
- Q52 · CLM · Never touches content, tacit knowledge, decision authority.

> Q53 L85-85: "the platform *independently converged* on the literature's strongest invariants before this research existed"
- Q53 · CLM · Context map: Engineering Platform as corroborating evidence.

> Q54 L86-86: "the code is the primary knowledge carrier (highest trust stratum)"
- Q54 · CLM · Context map: Product.

> Q55 L87-87: "**project-context assembly is the observed gap**"
- Q55 · CLM · Context map: Runtime.

> Q56 L88-88: "three challenged assumptions (centrality, single lifecycle, card granularity) and one pending usage test"
- Q56 · CLM · Context map: EKP.

> Q57 L92-92: "**Did the EKP get used in-flow during PB-004..007?**"
- Q57 · CLM · Open question 1.

> Q58 L93-93: "what resolves ranked contradictions when an *agent* must act?"
- Q58 · CLM · Open question 2.

> Q59 L94-94: "which regime claims artifacts that are part decision, part description (e.g., IDDs)?"
- Q59 · CLM · Open question 3.

> Q60 L95-95: "assertion-level quality is right in principle; is the bookkeeping affordable outside high-stakes claims?"
- Q60 · CLM · Open question 4.

> Q61 L96-96: "**Does conversational validation scale when some engineers are AI?**"
- Q61 · CLM · Open question 5.

> Q62 L97-97: "can decision knowledge be mined from existing gestures (commits, PRs, session logs) rather than authored?"
- Q62 · CLM · Open question 6.

> Q63 L98-98: "**Token economics of agentic discovery vs curated assembly** at repository scale."
- Q63 · CLM · Open question 7.

> Q64 L103-103: "**Seven-kind topic taxonomy: REFRAMED (partially falsified).**"
- Q64 · REV · Going-in taxonomy reframed.

> Q65 L104-104: "**EKP single-lifecycle model: FALSIFIED.**"
- Q65 · REV · EKP single lifecycle falsified.

> Q66 L105-105: "**"Second platform" hypothesis: WEAKENED to the point of rejection in its store form.**"
- Q66 · REV · Second-platform hypothesis weakened.

> Q67 L107-107: "**RECOMMENDATION (per the charter's four options): "absorbed rules" + one targeted capability — NOT a new platform.**"
- Q67 · CLM · Recommendation.

> Q68 L108-108: "status-visible-at-discovery · supersession-by-link · staged retirement · ownership+freshness as authority · intake validation for experiential knowledge · scent conventions · non-derivable-only for context files"
- Q68 · CLM · Rules to absorb.

> Q69 L109-109: "**The one evidenced new capability: project-context assembly in-flow**"
- Q69 · CLM · The one new capability.

> Q70 L110-110: "**EKP: retain, test, refine — do not expand.**"
- Q70 · CLM · EKP recommendation.

> Q71 L111-111: "a knowledge graph/ontology system (cost scales faster than value; minimal cores win) · a new repository or portal · capture mandates for tacit knowledge · numeric confidence scores on human-facing knowledge (unvalidated)"
- Q71 · CLM · Explicitly not recommended.

> Q72 L113-113: "**STOP.** The ARB decides whether this evidence authorizes design, and in which of the four directions."
- Q72 · CLM · Gate.

> Q73 L116-116: "all three going-in assumptions were confirmed-or-falsified with evidence (§7)"
- Q73 · CLM · Success-criteria self-check.

## Line accounting (NOT-EXTRACTED)

| Lines | Reason |
|---|---|
| 1 | FRONTMATTER (title) |
| 2, 6–8, 10, 12, 18, 20, 29, 31, 41, 43, 45, 47, 49, 74, 76, 78, 82, 84, 89, 91, 99, 101, 106, 112, 114–115 | BOILERPLATE (blank lines, separators) |
| 9, 11, 19, 30, 42, 48, 77, 83, 90, 100 | NAVIGATION (section headings) |
| 50, 73 | BOILERPLATE (code fences) |
| 32–33 | BOILERPLATE (table header) |
| 51 | NAVIGATION (tree root "PROJECT KNOWLEDGE") |
| 102 | NAVIGATION (lead-in "Going-in assumptions, judged") |

## Uncertainties noticed

- [?] The ontology here (L51–72) has six nodes (Binding, Decision, Definitional, Descriptive, Experiential incl. qualification evidence, Tacit). The later Taxonomy has seven types (Executable instead of Binding; Qualification separate from Experiential). This is a revision across documents [I].
- [?] The status vocabulary has three values here (L44) and five in the Meta-Model and Constitutional Model.
