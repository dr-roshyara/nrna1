# V-2 pairing worksheet 05

Mechanical grouping by overlapping line ranges only; no judgement. Two anonymous extractions, X (73 items) and Y (111 items).

source: docs/implementation/RQ-002_Strategic_Discovery_Report.md

## G1 · L3-3
- X:Q1 L3-3 · CLM · Scope of the evidence consumed.  
  "four executors · 7 disciplines · ~120 sources · 55 NKMs · 33 negative findings · 35 mapped contradictions"
- Y:Q1 L3-3 · DEF · The report is a research synthesis, explicitly not architecture or design.  
  "research synthesis — NOT architecture, NOT design."
- Y:Q2 L3-3 · CLM · The report's input is the raw findings: 4 executors, 7 disciplines, ~120 sources, 55 NKMs, 33 negative findings, 35 contradictions.  
  "Consumes `RQ-002_Research_Findings_Raw.md` (four executors · 7 disciplines · ~120 sources · 55 NKMs · 33 negative findings · 35 mapped contradictions)."

## G2 · L4-4
- X:Q2 L4-4 · CLM · Status complete; gate on §7.  
  "**STOP: no architecture may form until the ARB rules on §7.**"
- Y:Q3 L4-4 · INV · The report is complete; no architecture may form until the ARB rules on the recommendation.  
  "COMPLETE — presented to the Decision Authority. **STOP: no architecture may form until the ARB rules on §7.**"

## G3 · L5-5
- X:Q3 L5-5 · DEF · The document's own epistemic labels.  
  "FACT (from literature/implementation) · INTERPRETATION · RECOMMENDATION · OPEN QUESTION."
- Y:Q4 L5-5 · DEF · Findings are labeled with one of four epistemic categories.  
  "FACT (from literature/implementation) · INTERPRETATION · RECOMMENDATION · OPEN QUESTION."

## G4 · L13-13
- X:Q4 L13-13 · CLM · FACT 1 same-population law.  
  "Project knowledge survives only where its maintainers and its consumers are the same population and updating it is part of an existing workflow gesture."
- X:Q5 L13-13 · CLM · Corollary of FACT 1 (wikis, LLIS, executable specs, workshop artifacts, glossaries, ADRs).  
  "Every artifact class requiring a *second population* or a *separate maintenance gesture* has a documented decay-to-graveyard lifecycle"
- Y:Q5 L13-13 · CLM · FACT 1 (same-population law): knowledge survives only when maintainers equal consumers and updating is part of an existing gesture.  
  "Project knowledge survives only where its maintainers and its consumers are the same population and updating it is part of an existing workflow gesture."
- Y:Q6 L13-13 · CLM · Every artifact class needing a second population or separate gesture has documented decay.  
  "Every artifact class requiring a *second population* or a *separate maintenance gesture* has a documented decay-to-graveyard lifecycle"
- Y:Q7 L13-13 · CLM · Examples: GooWiki ~90% unviewed/unupdated; NASA LLIS two decades of non-use.  
  "wikis (Google GooWiki: ~90% of documents unviewed/unupdated at deprecation), lessons-learned systems (NASA LLIS: two decades of audited non-use)"
- Y:Q8 L13-13 · CLM · Further examples: executable specs, workshop artifacts, glossaries/context maps (~2 years), and ADRs (~50% abandon after 1–5 records).  
  "business-facing executable specs (per the method's own author), workshop artifacts (by design), glossaries/context maps (~2 years after owner departure), and — modally — ADRs (~50% of adopting repos abandon after 1–5 records)."

## G5 · L14-14
- X:Q6 L14-14 · CLM · FACT 2.  
  "**Validity status is invisible at the point of discovery — the master failure signature.**"
- X:Q7 L14-14 · CLM · Born-stale corollary.  
  "much knowledge is **born stale** (58.4% of obsolete Stack Overflow answers were obsolete at posting) — so intake validation matters at least as much as maintenance cadence."
- Y:Q9 L14-14 · CLM · FACT 2: invisible validity status at discovery is the master failure signature.  
  "**Validity status is invisible at the point of discovery — the master failure signature.**"
- Y:Q10 L14-14 · CLM · Four failure cases reduce to the consumer being unable to tell canonical, historical and wrong apart at retrieval.  
  "The NASA, Stack Overflow, wiki, and RAG-freshness failures all reduce to one sentence: at the moment of retrieval, the consumer cannot distinguish canonical from historical from wrong."
- Y:Q11 L14-14 · CLM · Much knowledge is born stale; 58.4% of obsolete SO answers were obsolete when posted.  
  "much knowledge is **born stale** (58.4% of obsolete Stack Overflow answers were obsolete at posting)"
- Y:Q12 L14-14 · CLM · Intake validation is at least as important as maintenance.  
  "so intake validation matters at least as much as maintenance cadence."

## G6 · L15-15
- X:Q8 L15-15 · DEF · FACT 3: the atomic unit (four-way convergence).  
  "**Composite: the atomic unit of project knowledge is a context-bound, provenance-carrying, status-bearing assertion, co-located with what it governs.**"
- X:Q9 L15-15 · CLM · Corollary of FACT 3.  
  "systems that govern at page/document granularity cannot detect partial staleness — a page is "fresh" while half its claims are false."
- Y:Q13 L15-15 · CLM · FACT 3: four disciplines converged on the smallest unit.  
  "**The smallest unit converged four ways.**"
- Y:Q14 L15-15 · DEF · KR's unit is the nanopublication; KM's unit is the knowledge claim.  
  "KR: the nanopublication (assertion + provenance + status). KM: the knowledge claim (falsifiable assertion with evaluation status)."
- Y:Q15 L15-15 · DEF · AI/CogSci's unit is the smallest span that stays true read alone; DDD/SE's is a single decision-or-definition in context-bound terms, co-located and updatable in an existing gesture.  
  "AI/CogSci:"
- Y:Q16 L15-15 · DEF · The composite atomic unit is a context-bound, provenance-carrying, status-bearing assertion co-located with what it governs.  
  "**Composite: the atomic unit of project knowledge is a context-bound, provenance-carrying, status-bearing assertion, co-located with what it governs.**"
- Y:Q17 L15-15 · CLM · Page/document-level governance cannot detect partial staleness.  
  "Corollary: systems that govern at page/document granularity cannot detect partial staleness"

## G7 · L16-16
- X:Q10 L16-16 · CLM · FACT 4.  
  "**Trust is stratified by proximity to executable reality, and validation-by-execution is the only mechanism empirically shown to keep an artifact true.**"
- X:Q11 L16-16 · REL · De facto trust hierarchy (an ordering).  
  "running code > executing tests > code-proximate micro-docs > ADRs/architecture docs > wikis > departed colleagues' notes"
- X:Q12 L16-16 · CLM · Burned once, consumers reroute.  
  "Trust is asymmetric and stateful"
- Y:Q18 L16-16 · CLM · FACT 4: trust follows proximity to execution; execution is the only proven validation mechanism.  
  "**Trust is stratified by proximity to executable reality, and validation-by-execution is the only mechanism empirically shown to keep an artifact true.**"
- Y:Q19 L16-16 · MATH · The accuracy–consultation correlation ranges from 0.67 (testing docs) to 0.03 (specifications).  
  "Accuracy–consultation correlation: testing docs r=0.67 → specifications r=0.03."
- Y:Q20 L16-16 · MATH · A strict ordering of trust from running code down to departed colleagues' notes.  
  "De facto trust hierarchy: running code > executing tests > code-proximate micro-docs > ADRs/architecture docs > wikis > departed colleagues' notes."
- Y:Q21 L16-16 · CLM · Trust is asymmetric and stateful; one betrayal reroutes consumers and collapses maintenance.  
  "Trust is asymmetric and stateful: burned once, consumers reroute to social channels and the artifact's maintenance collapses."

## G8 · L17-17
- X:Q13 L17-17 · CLM · FACT 5; mitigations are succession mechanics.  
  "**Rationale is the most demanded, least captured, and hardest to validate knowledge**"
- Y:Q22 L17-17 · CLM · FACT 5: rationale is the most demanded, least captured and hardest to validate.  
  "**Rationale is the most demanded, least captured, and hardest to validate knowledge**"
- Y:Q23 L17-17 · CLM · Rationale is hard to validate because it neither executes nor arises in conversation.  
  "it neither executes nor arises in daily conversation."
- Y:Q24 L17-17 · CLM · Rationale lives in mental models and is lost through turnover; 65% of popular projects have truck factor ≤2.  
  "It lives in mental models (the actual operational knowledge store of every team) and is lost fat-tailed through turnover (65% of popular projects: truck factor ≤2)."
- Y:Q25 L17-17 · CLM · Only succession mechanics are measured mitigations, not repositories.  
  "The only measured mitigations are succession mechanics, not repositories."

## G9 · L21-21
- X:Q14 L21-21 · CLM · Created: conversationally and by doing; SECI lacks grounding.  
  "its window is at decision time or never ("knowledge vaporization")"
- Y:Q26 L21-21 · CLM · Knowledge is created conversationally and by doing; externalization is costly and only possible at decision time.  
  "conversationally (knowledge crunching, workshops) and by doing; externalization is expensive and its window is at decision time or never"
- Y:Q27 L21-21 · CLM · SECI lacks empirical grounding, but the knowledge-leak diagnosis is confirmed.  
  "The dominant creation theory (SECI) lacks empirical grounding; the *diagnosis* (knowledge leaks) is independently confirmed."

## G10 · L22-22
- X:Q15 L22-22 · CLM · Validated: execution, conversation, claim evaluation, use.  
  "by *use* (knowledge without a consumption feedback loop cannot maintain quality at all)"
- Y:Q28 L22-22 · CLM · Knowledge is validated in four ways: execution, conversation, claim evaluation, use.  
  "by execution (the gold standard) · by conversation (DDD's mechanism — drift heard as awkwardness) · by claim evaluation (McElroy — almost never implemented) · by *use*"
- Y:Q29 L22-22 · CLM · Knowledge with no consumption feedback cannot maintain quality.  
  "(knowledge without a consumption feedback loop cannot maintain quality at all)."

## G11 · L23-23
- X:Q16 L23-23 · CLM · Evolved: two regimes.  
  "two legitimate regimes, never one — **decisions are immutable and superseded by link** (history); **descriptions evolve or regenerate** (state)."
- Y:Q30 L23-23 · CLM · Evolution has two regimes: decisions immutable/superseded, descriptions evolve/regenerate.  
  "two legitimate regimes, never one — **decisions are immutable and superseded by link** (history); **descriptions evolve or regenerate** (state)."
- Y:Q31 L23-23 · INV · Regime membership is a first-class artifact property.  
  "Which regime an artifact belongs to is a first-class property."

## G12 · L24-24
- X:Q17 L24-24 · CLM · Discovered: foraging; minimal-sufficient context for AI.  
  "knowledge without scent is functionally nonexistent"
- Y:Q32 L24-24 · CLM · Discovery is foraging via scent, a property of labels; scentless knowledge is effectively nonexistent.  
  "by foraging (information scent — a property of labels, not content; knowledge without scent is functionally nonexistent)"
- Y:Q33 L24-24 · CLM · Discovery happens in-flow and opportunistically; people are preferred over documents.  
  "in-flow and opportunistically (satisficing, not optimizing; people preferred over documents)"
- Y:Q34 L24-24 · CLM · For AI, minimal sufficient context beats volume; context rot is universal; agentic search displaced indexes for code.  
  "for AI: minimal-sufficient-context assembly beats volume (context rot is universal across 18 frontier models; agentic search over live sources displaced pre-built indexes for code)."

## G13 · L25-25
- X:Q18 L25-25 · CLM · Consumed.  
  "in fragments, at moment of need, never as wholes"
- Y:Q35 L25-25 · CLM · Knowledge is consumed in fragments at the moment of need.  
  "in fragments, at moment of need, never as wholes"
- Y:Q36 L25-25 · CLM · Packaging is governed by cognitive load; redundancy harms both humans and agents.  
  "packaging is governed by cognitive load (split-attention and redundancy harm humans and agents alike — LLM-generated instruction files measured net-negative for duplicating the derivable)."

## G14 · L26-26
- X:Q19 L26-26 · CLM · Challenged.  
  "coexist at rest, resolve at consumption, demote by linked supersession, never silent edit."
- Y:Q37 L26-26 · CLM · Mature systems let contradictions coexist under explicit status.  
  "mature systems let contradictory claims **coexist under explicit status** (ranks + reasons) rather than forcing resolution"
- Y:Q38 L26-26 · INV · Contradictions coexist at rest, resolve at consumption, demote via linked supersession, never silent edits.  
  "coexist at rest, resolve at consumption, demote by linked supersession, never silent edit."

## G15 · L27-27
- X:Q20 L27-27 · CLM · Forgotten.  
  "two distinct mechanisms — loss (turnover/disuse) vs obsolescence (world changed) — with different remedies"
- Y:Q39 L27-27 · DEF · Forgetting has two mechanisms, loss and obsolescence, each needing different remedies.  
  "two distinct mechanisms — loss (turnover/disuse) vs obsolescence (world changed) — with different remedies"
- Y:Q40 L27-27 · CLM · Depreciation rates differ by domain (17%/week to 3%/month).  
  "depreciation is domain-relative (17%/week to 3%/month)."
- Y:Q41 L27-27 · CLM · Retirement is the least designed stage; default is silent abandonment.  
  "Deliberate retirement is the least-designed lifecycle stage everywhere; default = silent abandonment discovered by a confused reader."

## G16 · L28-28
- X:Q21 L28-28 · CLM · Retired; unretired stale knowledge is harmful.  
  "correctly, as a **status transition, never deletion**"
- Y:Q42 L28-28 · INV · Correct retirement is a status transition, never deletion; archived content leaves navigation but stays in history.  
  "correctly, as a **status transition, never deletion** (archive = out of navigation, in history)"
- Y:Q43 L28-28 · CLM · Unretired stale knowledge harms newcomers and AI grounding.  
  "stale knowledge left unretired is actively harmful — it causes newcomer dropout and poisons AI grounding."

## G17 · L34-34
- X:Q22 L34-34 · CLM · EKP gap analysis: metadata aligned.  
  "Ownership + status + authority metadata on every card | ✅ **Aligned**"
- Y:Q44 L34-34 · CLM · The EKP's ownership/status/authority metadata aligns with the evidence (the process-property authority model).  
  "| Ownership + status + authority metadata on every card | ✅ **Aligned**"

## G18 · L35-35
- X:Q23 L35-35 · CLM · EKP lint aligned.  
  "knowledge-lint (executable checks on knowledge) | ✅ **Aligned**"
- Y:Q45 L35-35 · CLM · knowledge-lint is aligned as execution-based validation of knowledge.  
  "| knowledge-lint (executable checks on knowledge) | ✅ **Aligned** — validation-by-execution applied to knowledge; rare and valuable |"

## G19 · L36-36
- X:Q24 L36-36 · CLM · EKP AI quarantine aligned.  
  "`ai/` quarantine (generated ≠ authoritative) | ✅ Aligned"
- Y:Q46 L36-36 · CLM · The ai/ quarantine is aligned as structural intake validation.  
  "| `ai/` quarantine (generated ≠ authoritative) | ✅ Aligned — intake validation exists structurally |"

## G20 · L37-37
- X:Q25 L37-37 · CLM · EKP central store at risk.  
  "a central store courts the second-population/graveyard pattern"
- Y:Q47 L37-37 · CLM · The EKP's central store is at risk because separated knowledge drifts by construction.  
  "⚠️ **At risk** — the coupling school won empirically: knowledge separated from what it describes drifts by construction; a central store courts the second-population/graveyard pattern"

## G21 · L38-38
- X:Q26 L38-38 · REV · EKP single lifecycle falsified.  
  "Single lifecycle for all knowledge types | ❌ **Falsified**"
- Y:Q48 L38-38 · REV · The EKP's single-lifecycle assumption is falsified; different types have different lifecycles.  
  "| Single lifecycle for all knowledge types | ❌ **Falsified**"

## G22 · L39-39
- X:Q27 L39-39 · CLM · EKP card granularity challenged.  
  "bundle-level governance cannot detect partial staleness; quality attaches at assertion granularity"
- Y:Q49 L39-39 · CLM · Card granularity is challenged because quality attaches to assertions.  
  "| Card (document) granularity | ⚠️ **Challenged** — bundle-level governance cannot detect partial staleness; quality attaches at assertion granularity |"

## G23 · L40-40
- X:Q28 L40-40 · CLM · EKP consumption model untested.  
  "*was the EKP consulted during PB-004..007 engineering sessions, or did sessions read raw ADRs/guides/code?*"
- Y:Q50 L40-40 · CLM · The EKP portal consumption model is untested; in-flow discovery is the binding constraint.  
  "⚠️ **Untested** — discovery-in-flow is the binding constraint"
- Y:Q51 L40-40 · CLM · The decisive EKP-usage question is answerable from session logs and pending.  
  "(Answerable from session logs — pending.)"

## G24 · L44-44
- X:Q29 L44-44 · DEF · UL term Knowledge Claim.  
  "**Knowledge Claim** (atomic unit: context-bound, provenance-carrying, status-bearing assertion)"
- X:Q30 L44-44 · DEF · UL term Binding vs Descriptive.  
  "**Binding vs Descriptive knowledge** (constrains code vs describes state — different validation, different evolution regime)"
- X:Q31 L44-44 · DEF · UL term Validation Mechanism.  
  "**Validation Mechanism** (executable · conversational · social · none — "none" predicts decay)"
- X:Q32 L44-44 · DEF · UL terms Same-Population Principle, Workflow Gesture.  
  "**Same-Population Principle** · **Workflow Gesture** (the update path that already exists)"
- X:Q33 L44-44 · DEF · UL terms Information Scent, Discovery-in-Flow, Born-Stale.  
  "**Information Scent** (discoverability as a property of labels) · **Discovery-in-Flow** · **Born-Stale** (intake failure, distinct from decay)"
- X:Q34 L44-44 · DEF · UL terms Knowledge Half-Life, Supersession.  
  "**Knowledge Half-Life** (domain-relative depreciation) · **Supersession** (retirement by link, never edit)"
- X:Q35 L44-44 · DEF · UL term Staged Retirement.  
  "**Staged Retirement** (demote visibly, archive retrievably, never destroy silently)"
- X:Q36 L44-44 · DEF · UL status vocabulary (three values here; five in the Meta-Model).  
  "**Canonical / Historical / Deprecated** (the status vocabulary — visible at the point of discovery or worthless)"
- X:Q37 L44-44 · DEF · UL term Scoped Canonicity.  
  "**Scoped Canonicity** (one truth per scope, never globally)"
- X:Q38 L44-44 · DEF · UL terms Rationale Gap, Tacit Residue.  
  "**Rationale Gap** (most demanded, least captured, hardest to validate) · **Tacit Residue** (what externalization cannot reach; mitigated by succession, not capture)"
- X:Q39 L44-44 · DEF · UL terms Concentration Risk, Minimal Sufficient Context.  
  "**Concentration Risk / Truck Factor** · **Minimal Sufficient Context** (assembly beats volume, for humans and models)"
- X:Q40 L44-44 · DEF · UL terms Consumption Feedback Loop, Contradiction Coexistence.  
  "**Consumption Feedback Loop** (no use signal → no quality) · **Contradiction Coexistence** (plural ranked truths at rest; resolution at consumption)."
- Y:Q52 L44-44 · DEF · UL term: Knowledge Claim, the atomic unit.  
  "**Knowledge Claim** (atomic unit: context-bound, provenance-carrying, status-bearing assertion)"
- Y:Q53 L44-44 · DEF · UL term: Binding knowledge constrains code; Descriptive describes state; they differ in validation and regime.  
  "**Binding vs Descriptive knowledge** (constrains code vs describes state — different validation, different evolution regime)"
- Y:Q54 L44-44 · DEF · UL term: Validation Mechanism with four values, where "none" predicts decay.  
  "**Validation Mechanism** (executable · conversational · social · none"
- Y:Q55 L44-44 · DEF · UL terms: Same-Population Principle; Workflow Gesture, the pre-existing update path.  
  "**Same-Population Principle** · **Workflow Gesture** (the update path that already exists)"
- Y:Q56 L44-44 · DEF · UL terms: Information Scent (label property) and Discovery-in-Flow.  
  "**Information Scent** (discoverability as a property of labels) · **Discovery-in-Flow**"
- Y:Q57 L44-44 · DEF · UL terms: Born-Stale (intake failure) and Knowledge Half-Life.  
  "**Born-Stale** (intake failure, distinct from decay) · **Knowledge Half-Life** (domain-relative depreciation)"
- Y:Q58 L44-44 · DEF · UL terms: Supersession and Staged Retirement.  
  "**Supersession** (retirement by link, never edit) · **Staged Retirement** (demote visibly, archive retrievably, never destroy silently)"
- Y:Q59 L44-44 · DEF · UL term: the status vocabulary, worthless unless visible at discovery.  
  "**Canonical / Historical / Deprecated** (the status vocabulary — visible at the point of discovery or worthless)"
- Y:Q60 L44-44 · DEF · UL terms: Scoped Canonicity and Rationale Gap.  
  "**Scoped Canonicity** (one truth per scope, never globally) · **Rationale Gap** (most demanded, least captured, hardest to validate)"
- Y:Q61 L44-44 · DEF · UL terms: Tacit Residue and Concentration Risk/Truck Factor.  
  "**Tacit Residue** (what externalization cannot reach; mitigated by succession, not capture) · **Concentration Risk / Truck Factor**"
- Y:Q62 L44-44 · DEF · UL terms: Minimal Sufficient Context, Consumption Feedback Loop, Contradiction Coexistence.  
  "**Minimal Sufficient Context** (assembly beats volume, for humans and models) · **Consumption Feedback Loop** (no use signal → no quality) · **Contradiction Coexistence** (plural ranked truths at rest; resolution at consumption)."

## G25 · L46-46
- X:Q41 L46-46 · CLM · Format-independence check passed.  
  "every term above survives the Markdown-removal test — each names a property of knowledge, not of documents."
- Y:Q63 L46-46 · CLM · All UL terms pass the format-independence test.  
  "every term above survives the Markdown-removal test — each names a property of knowledge, not of documents."

## G26 · L51-51
- Y:Q64 L51-51 · MATH · The ontology root is Project Knowledge, with six child nodes.  
  "PROJECT KNOWLEDGE"

## G27 · L52-54
- X:Q42 L52-54 · DEF · Ontology node BINDING: validation execution, change-with-code, authority the passing run.  
  "BINDING knowledge        (invariants · tests · gates)"
- Y:Q65 L52-54 · MATH · Binding node: validation by execution, change-with-code, authority of the passing run, producers=consumers, highest trust.  
  "BINDING knowledge        (invariants · tests · gates)"

## G28 · L55-58
- X:Q43 L55-58 · DEF · Ontology node DECISION: immutable + supersession; the rationale gap lives here.  
  "DECISION knowledge       (ADR-class · rulings)"
- Y:Q66 L55-58 · MATH · Decision node: enforcement-binding validation, immutable + supersession, canonical = accepted ∧ unsuperseded ∧ owned ∧ bound, home of the Rationale Gap.  
  "DECISION knowledge       (ADR-class · rulings)"

## G29 · L59-61
- X:Q44 L59-61 · DEF · Ontology node DEFINITIONAL: conversational validation, term-bound-to-context.  
  "DEFINITIONAL knowledge   (ubiquitous-language terms · context boundaries)"
- Y:Q67 L59-61 · MATH · Definitional node: conversational validation, continuous evolution, term-bound-to-context unit, rots when conversation stops.  
  "DEFINITIONAL knowledge   (ubiquitous-language terms · context boundaries)"

## G30 · L62-64
- X:Q45 L62-64 · DEF · Ontology node DESCRIPTIVE: survives only code-proximate.  
  "DESCRIPTIVE knowledge    (guides · READMEs · views)"
- Y:Q68 L62-64 · MATH · Descriptive node: freshness+ownership+review validation, regenerate-or-evolve, survives only code-proximate/same-population.  
  "DESCRIPTIVE knowledge    (guides · READMEs · views)"

## G31 · L65-67
- X:Q46 L65-67 · DEF · Ontology node EXPERIENTIAL: includes qualification evidence; claim evaluation at intake.  
  "EXPERIENTIAL knowledge   (lessons · retro records · qualification evidence)"
- Y:Q69 L65-67 · MATH · Experiential node (including qualification evidence): claim evaluation at intake, append-only, falsified claims retained.  
  "EXPERIENTIAL knowledge   (lessons · retro records · qualification evidence)"

## G32 · L68-70
- X:Q47 L68-70 · DEF · Ontology node TACIT: transfer by practice/succession only.  
  "TACIT knowledge          (mental models · skills)"
- Y:Q70 L68-70 · MATH · Tacit node: validated by use, transferred only by practice/succession, not fully externalizable, measured via concentration risk.  
  "TACIT knowledge          (mental models · skills)"
- Y:Q71 L70-70 · CLM · Capture programs targeting tacit knowledge fail.  
  "capture programs against it fail"

## G33 · L71-72
- X:Q48 L71-72 · REL · Six cross-cutting properties on every node.  
  "Cross-cutting properties attaching to EVERY node: provenance · status (visible at discovery) · ownership · freshness (half-life priced per domain) · scent (labels) · granularity (claim-level quality)"
- Y:Q72 L71-72 · INV · Six cross-cutting properties (provenance, status, ownership, freshness, scent, granularity) attach to every node.  
  "Cross-cutting properties attaching to EVERY node: provenance · status (visible at discovery) ·"

## G34 · L75-75
- X:Q49 L75-75 · CLM · Ontology's primary cut.  
  "**The primary cut is by validation mechanism and population structure — not by topic.**"
- Y:Q73 L75-75 · CLM · The ontology's primary division is by validation mechanism and population structure.  
  "**The primary cut is by validation mechanism and population structure — not by topic.**"
- Y:Q74 L75-75 · REV · The going-in taxonomy's topical cut is rejected.  
  "(The going-in seven-kind taxonomy cut by topic; see §7 falsifications.)"

## G35 · L79-79
- X:Q50 L79-79 · CLM · Ownership map: owns (status, intake gates, retirement, scent, claim granularity, context assembly).  
  "**Owns:** the status vocabulary and its visibility-at-discovery rule"
- Y:Q75 L79-79 · CLM · A Project Knowledge capability would own six things: status vocabulary, intake gates, retirement, scent conventions, claim granularity, context assembly.  
  "the status vocabulary and its visibility-at-discovery rule · intake validation gates (born-stale defense) · retirement cadence and staged-forgetting mechanics · scent conventions (labeling/addressability) · the claim-granularity rule · the discovery/context-assembly capability."

## G36 · L80-80
- X:Q51 L80-80 · CLM · Observes artifacts in place.  
  "*(The evidence forbids centralizing them: knowledge lives with what it describes.)*"
- Y:Q76 L80-80 · CLM · It would observe all artifacts where they live.  
  "every knowledge artifact in place — ADRs stay with their deciders, code-proximate docs with their code, EKP cards with their domain owners, session records with the runtime."
- Y:Q77 L80-80 · INV · Knowledge must not be centralized; it lives with what it describes.  
  "*(The evidence forbids centralizing them: knowledge lives with what it describes.)*"

## G37 · L81-81
- X:Q52 L81-81 · CLM · Never touches content, tacit knowledge, decision authority.  
  "**Never touches:** the content of knowledge (producers own their claims)"
- Y:Q78 L81-81 · INV · It would never touch knowledge content, tacit knowledge, or decision authority.  
  "the content of knowledge (producers own their claims) · tacit knowledge (only succession mechanics apply — no capture mandate) · product or engineering decision authority."

## G38 · L85-85
- X:Q53 L85-85 · CLM · Context map: Engineering Platform as corroborating evidence.  
  "the platform *independently converged* on the literature's strongest invariants before this research existed"
- Y:Q79 L85-85 · REL · The Engineering Platform supplies validation machinery.  
  "supplies the validation machinery (qualification lifecycle, evidence discipline, append-only registers)."
- Y:Q80 L85-85 · CLM · The platform independently converged on the strongest invariants (supersession-not-edit, PASS AFTER CORRECTION, demote-visibly, evidence-before-acceptance) and is corroborating evidence.  
  "the platform *independently converged* on the literature's strongest invariants before this research existed"

## G39 · L86-86
- X:Q54 L86-86 · CLM · Context map: Product.  
  "the code is the primary knowledge carrier (highest trust stratum)"
- Y:Q81 L86-86 · REL · In the Product, code is the primary knowledge carrier and domain knowledge originates there.  
  "the code is the primary knowledge carrier (highest trust stratum); domain knowledge originates here"
- Y:Q82 L86-86 · CLM · Constitutional invariants are Binding knowledge validated by fitness suites.  
  "the constitutional invariants are Binding knowledge already validated by execution (fitness suites)."

## G40 · L87-87
- X:Q55 L87-87 · CLM · Context map: Runtime.  
  "**project-context assembly is the observed gap**"
- Y:Q83 L87-87 · CLM · Session startup is the existing assembly gesture; project-context assembly is the observed gap.  
  "session startup is the existing context-assembly gesture (engineering context injected; **project-context assembly is the observed gap**)"
- Y:Q84 L87-87 · INV · CLAUDE.md-class files must contain only non-derivable knowledge (Documentation-as-Context).  
  "subject to the non-derivable-only rule (duplicating the derivable is measured harm)"
- Y:Q85 L87-87 · CLM · Agentic search over the live repo displaced indexes.  
  "agentic search over the live repo is the discovery mechanism that displaced indexes."

## G41 · L88-88
- X:Q56 L88-88 · CLM · Context map: EKP.  
  "three challenged assumptions (centrality, single lifecycle, card granularity) and one pending usage test"
- Y:Q86 L88-88 · CLM · The EKP is aligned on metadata, has three challenged assumptions and one pending usage test.  
  "the incumbent implementation of governance metadata (aligned) with three challenged assumptions (centrality, single lifecycle, card granularity) and one pending usage test (§1c)."

## G42 · L92-92
- X:Q57 L92-92 · CLM · Open question 1.  
  "**Did the EKP get used in-flow during PB-004..007?**"
- Y:Q87 L92-92 · CLM · OQ1: EKP in-flow usage, decisive and answerable behaviorally.  
  "**Did the EKP get used in-flow during PB-004..007?** (Decisive for §7; answerable from session logs — behavioral evidence, not survey.) — OPEN"

## G43 · L93-93
- X:Q58 L93-93 · CLM · Open question 2.  
  "what resolves ranked contradictions when an *agent* must act?"
- Y:Q88 L93-93 · CLM · OQ2: conflict resolution for machine consumers is a named open gap.  
  "coexist-at-rest works for curation; what resolves ranked contradictions when an *agent* must act?"

## G44 · L94-94
- X:Q59 L94-94 · CLM · Open question 3.  
  "which regime claims artifacts that are part decision, part description (e.g., IDDs)?"
- Y:Q89 L94-94 · CLM · OQ3: regime assignment for boundary artifacts.  
  "which regime claims artifacts that are part decision, part description (e.g., IDDs)? — OPEN"

## G45 · L95-95
- X:Q60 L95-95 · CLM · Open question 4.  
  "assertion-level quality is right in principle; is the bookkeeping affordable outside high-stakes claims?"
- Y:Q90 L95-95 · CLM · OQ4: affordability of claim-granularity bookkeeping.  
  "assertion-level quality is right in principle; is the bookkeeping affordable outside high-stakes claims? — OPEN"

## G46 · L96-96
- X:Q61 L96-96 · CLM · Open question 5.  
  "**Does conversational validation scale when some engineers are AI?**"
- Y:Q91 L96-96 · ASM · OQ5: DDD's conversational validation presumes human cadence; scaling to AI engineers is open.  
  "**Does conversational validation scale when some engineers are AI?** (DDD's mechanism presumes human conversation cadence.) — OPEN"

## G47 · L97-97
- X:Q62 L97-97 · CLM · Open question 6.  
  "can decision knowledge be mined from existing gestures (commits, PRs, session logs) rather than authored?"
- Y:Q92 L97-97 · CLM · OQ6: whether rationale can be mined from existing gestures; capture must become free to survive.  
  "can decision knowledge be mined from existing gestures (commits, PRs, session logs) rather than authored? (The literature says capture must become free to survive.) — OPEN"

## G48 · L98-98
- X:Q63 L98-98 · CLM · Open question 7.  
  "**Token economics of agentic discovery vs curated assembly** at repository scale."
- Y:Q93 L98-98 · CLM · OQ7: token economics of agentic discovery vs curated assembly.  
  "**Token economics of agentic discovery vs curated assembly** at repository scale. — OPEN"

## G49 · L103-103
- X:Q64 L103-103 · REV · Going-in taxonomy reframed.  
  "**Seven-kind topic taxonomy: REFRAMED (partially falsified).**"
- Y:Q94 L103-103 · REV · The seven-kind topic taxonomy is partially falsified; topic becomes a secondary facet.  
  "**Seven-kind topic taxonomy: REFRAMED (partially falsified).** The load-bearing cuts are validation mechanism and population structure (§3); topic is a secondary facet."

## G50 · L104-104
- X:Q65 L104-104 · REV · EKP single lifecycle falsified.  
  "**EKP single-lifecycle model: FALSIFIED.**"
- Y:Q95 L104-104 · REV · The single-lifecycle model is falsified; at least four lifecycles exist.  
  "**EKP single-lifecycle model: FALSIFIED.** At least four structurally different lifecycles exist (§3)."

## G51 · L105-105
- X:Q66 L105-105 · REV · Second-platform hypothesis weakened.  
  "**"Second platform" hypothesis: WEAKENED to the point of rejection in its store form.**"
- Y:Q96 L105-105 · REV · The second-platform hypothesis is weakened to near-rejection in its store form.  
  "WEAKENED to the point of rejection in its store form.**"
- Y:Q97 L105-105 · CLM · A new central platform would repeat the wiki/LLIS failure pattern.  
  "A new central knowledge platform would be the wiki/LLIS pattern with better intentions — second population, separate gesture, graveyard lifecycle."
- Y:Q98 L105-105 · CLM · The alternative is rules and metadata in place, plus one capability.  
  "The evidence-shaped alternative is *rules + metadata on artifacts where they live, plus one capability.*"

## G52 · L107-107
- X:Q67 L107-107 · CLM · Recommendation.  
  "**RECOMMENDATION (per the charter's four options): "absorbed rules" + one targeted capability — NOT a new platform.**"
- Y:Q99 L107-107 · CLM · Recommendation: absorbed rules plus one targeted capability, not a new platform.  
  "+ one targeted capability — NOT a new platform.**"

## G53 · L108-108
- X:Q68 L108-108 · CLM · Rules to absorb.  
  "status-visible-at-discovery · supersession-by-link · staged retirement · ownership+freshness as authority · intake validation for experiential knowledge · scent conventions · non-derivable-only for context files"
- Y:Q100 L108-108 · CLM · Seven rules to be absorbed (via reframed ER-09).  
  "status-visible-at-discovery · supersession-by-link · staged retirement · ownership+freshness as authority · intake validation for experiential knowledge · scent conventions · non-derivable-only for context files."
- Y:Q101 L108-108 · CLM · Most rules are already practiced; the recommendation names and generalizes them.  
  "Most are already platform practice — this names and generalizes them."

## G54 · L109-109
- X:Q69 L109-109 · CLM · The one new capability.  
  "**The one evidenced new capability: project-context assembly in-flow**"
- Y:Q102 L109-109 · CLM · The single new capability is in-flow project-context assembly.  
  "**The one evidenced new capability: project-context assembly in-flow**"
- Y:Q103 L109-109 · CLM · It would extend session startup using agentic discovery over live sources, not an index.  
  "extend the *existing* session-startup gesture (same population, same gesture — the law) to assemble task-relevant project knowledge (governing ADRs, glossary terms, prior reports for the ticket at hand) via agentic discovery over live sources, not an index."
- Y:Q104 L109-109 · CLM · This capability alone satisfies the same-population law by construction.  
  "This attacks the observed gap directly and is the only candidate that satisfies the same-population law by construction."

## G55 · L110-110
- X:Q70 L110-110 · CLM · EKP recommendation.  
  "**EKP: retain, test, refine — do not expand.**"
- Y:Q105 L110-110 · CLM · The EKP should be retained and tested first; if bypassed, it should evolve in coupling/granularity, not content.  
  "**EKP: retain, test, refine — do not expand.** Run the §6-Q1 usage test first; if the EKP was bypassed in-flow, its next evolution is coupling and granularity, not content growth."

## G56 · L111-111
- X:Q71 L111-111 · CLM · Explicitly not recommended.  
  "a knowledge graph/ontology system (cost scales faster than value; minimal cores win) · a new repository or portal · capture mandates for tacit knowledge · numeric confidence scores on human-facing knowledge (unvalidated)"
- Y:Q106 L111-111 · REV · Four options are explicitly rejected: KG/ontology, new repository/portal, tacit capture mandates, numeric confidence scores.  
  "a knowledge graph/ontology system (cost scales faster than value; minimal cores win) · a new repository or portal · capture mandates for tacit knowledge · numeric confidence scores on human-facing knowledge (unvalidated)"
- Y:Q107 L111-111 · CLM · Each rejection is backed by sourced negative findings.  
  "each rejected on sourced negative findings."

## G57 · L113-113
- X:Q72 L113-113 · CLM · Gate.  
  "**STOP.** The ARB decides whether this evidence authorizes design, and in which of the four directions."
- Y:Q108 L113-113 · INV · The ARB decides whether design is authorized and in which direction.  
  "**STOP.** The ARB decides whether this evidence authorizes design, and in which of the four directions."

## G58 · L116-116
- X:Q73 L116-116 · CLM · Success-criteria self-check.  
  "all three going-in assumptions were confirmed-or-falsified with evidence (§7)"
- Y:Q109 L116-116 · CLM · Success: an independent UL was derived.  
  "a format/tool/provider/project-independent ubiquitous language was derived (§2 — every term passes the Markdown-removal test)"
- Y:Q110 L116-116 · CLM · Success: assumptions judged and ontology drafted.  
  "all three going-in assumptions were confirmed-or-falsified with evidence (§7); the ontology draft exists (§3)"
- Y:Q111 L116-116 · CLM · Exit criteria and time-box met (35 contradictions, 33 negative findings, single cycle).  
  "exit criteria met — literature review across seven disciplines · universal concepts identified · contradictions documented (35, preserved in the annex) · negative findings mandatory quota exceeded (33). Time-box honored: single research cycle, four parallel executors."

