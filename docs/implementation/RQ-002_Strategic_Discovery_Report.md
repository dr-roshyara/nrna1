# RQ-002 — Strategic Discovery Report: The Project Knowledge Domain

**Kind:** research synthesis — NOT architecture, NOT design. Consumes `RQ-002_Research_Findings_Raw.md` (four executors · 7 disciplines · ~120 sources · 55 NKMs · 33 negative findings · 35 mapped contradictions). Charter: `RQ-002_Project_Knowledge_Domain.md`.
**Status:** COMPLETE — presented to the Decision Authority. **STOP: no architecture may form until the ARB rules on §7.**
**Finding labels:** FACT (from literature/implementation) · INTERPRETATION · RECOMMENDATION · OPEN QUESTION.

---

## 1. Literature review — state of the art, and the gap analysis against the EKP incumbent

### 1a. The five load-bearing FACTS (independently converged across executors)

1. **The same-population law.** Project knowledge survives only where its maintainers and its consumers are the same population and updating it is part of an existing workflow gesture. Every artifact class requiring a *second population* or a *separate maintenance gesture* has a documented decay-to-graveyard lifecycle: wikis (Google GooWiki: ~90% of documents unviewed/unupdated at deprecation), lessons-learned systems (NASA LLIS: two decades of audited non-use), business-facing executable specs (per the method's own author), workshop artifacts (by design), glossaries/context maps (~2 years after owner departure), and — modally — ADRs (~50% of adopting repos abandon after 1–5 records).
2. **Validity status is invisible at the point of discovery — the master failure signature.** The NASA, Stack Overflow, wiki, and RAG-freshness failures all reduce to one sentence: at the moment of retrieval, the consumer cannot distinguish canonical from historical from wrong. Compounding it: much knowledge is **born stale** (58.4% of obsolete Stack Overflow answers were obsolete at posting) — so intake validation matters at least as much as maintenance cadence.
3. **The smallest unit converged four ways.** KR: the nanopublication (assertion + provenance + status). KM: the knowledge claim (falsifiable assertion with evaluation status). AI/CogSci: "the smallest span that remains true when read alone." DDD/SE: a single decision-or-definition in context-bound terms, co-located, updatable in an existing gesture. **Composite: the atomic unit of project knowledge is a context-bound, provenance-carrying, status-bearing assertion, co-located with what it governs.** Corollary: systems that govern at page/document granularity cannot detect partial staleness — a page is "fresh" while half its claims are false.
4. **Trust is stratified by proximity to executable reality, and validation-by-execution is the only mechanism empirically shown to keep an artifact true.** Accuracy–consultation correlation: testing docs r=0.67 → specifications r=0.03. De facto trust hierarchy: running code > executing tests > code-proximate micro-docs > ADRs/architecture docs > wikis > departed colleagues' notes. Trust is asymmetric and stateful: burned once, consumers reroute to social channels and the artifact's maintenance collapses.
5. **Rationale is the most demanded, least captured, and hardest to validate knowledge** — it neither executes nor arises in daily conversation. It lives in mental models (the actual operational knowledge store of every team) and is lost fat-tailed through turnover (65% of popular projects: truck factor ≤2). The only measured mitigations are succession mechanics, not repositories.

### 1b. Universal dynamics (the eight verbs, answered)

- **Created:** conversationally (knowledge crunching, workshops) and by doing; externalization is expensive and its window is at decision time or never ("knowledge vaporization"). The dominant creation theory (SECI) lacks empirical grounding; the *diagnosis* (knowledge leaks) is independently confirmed.
- **Validated:** by execution (the gold standard) · by conversation (DDD's mechanism — drift heard as awkwardness) · by claim evaluation (McElroy — almost never implemented) · by *use* (knowledge without a consumption feedback loop cannot maintain quality at all).
- **Evolved:** two legitimate regimes, never one — **decisions are immutable and superseded by link** (history); **descriptions evolve or regenerate** (state). Which regime an artifact belongs to is a first-class property.
- **Discovered:** by foraging (information scent — a property of labels, not content; knowledge without scent is functionally nonexistent) · in-flow and opportunistically (satisficing, not optimizing; people preferred over documents) · for AI: minimal-sufficient-context assembly beats volume (context rot is universal across 18 frontier models; agentic search over live sources displaced pre-built indexes for code).
- **Consumed:** in fragments, at moment of need, never as wholes; packaging is governed by cognitive load (split-attention and redundancy harm humans and agents alike — LLM-generated instruction files measured net-negative for duplicating the derivable).
- **Challenged:** mature systems let contradictory claims **coexist under explicit status** (ranks + reasons) rather than forcing resolution — coexist at rest, resolve at consumption, demote by linked supersession, never silent edit.
- **Forgotten:** two distinct mechanisms — loss (turnover/disuse) vs obsolescence (world changed) — with different remedies; depreciation is domain-relative (17%/week to 3%/month). Deliberate retirement is the least-designed lifecycle stage everywhere; default = silent abandonment discovered by a confused reader.
- **Retired:** correctly, as a **status transition, never deletion** (archive = out of navigation, in history); stale knowledge left unretired is actively harmful — it causes newcomer dropout and poisons AI grounding.

### 1c. Gap analysis: the EKP incumbent against the evidence — INTERPRETATION

| EKP property | Evidence verdict |
|---|---|
| Ownership + status + authority metadata on every card | ✅ **Aligned** — exactly the process-property authority model that works (owner + freshness + review) |
| knowledge-lint (executable checks on knowledge) | ✅ **Aligned** — validation-by-execution applied to knowledge; rare and valuable |
| `ai/` quarantine (generated ≠ authoritative) | ✅ Aligned — intake validation exists structurally |
| Central governed store (`docs/knowledge/`) separate from code | ⚠️ **At risk** — the coupling school won empirically: knowledge separated from what it describes drifts by construction; a central store courts the second-population/graveyard pattern |
| Single lifecycle for all knowledge types | ❌ **Falsified** — decisions/descriptions/workshop-output/tacit knowledge have structurally different lifecycles and evolution regimes |
| Card (document) granularity | ⚠️ **Challenged** — bundle-level governance cannot detect partial staleness; quality attaches at assertion granularity |
| Consumption model (portal navigation) | ⚠️ **Untested** — discovery-in-flow is the binding constraint; the decisive evidence question: *was the EKP consulted during PB-004..007 engineering sessions, or did sessions read raw ADRs/guides/code?* (Answerable from session logs — pending.) |

## 2. Candidate ubiquitous language — FACT-derived terms

**Knowledge Claim** (atomic unit: context-bound, provenance-carrying, status-bearing assertion) · **Binding vs Descriptive knowledge** (constrains code vs describes state — different validation, different evolution regime) · **Validation Mechanism** (executable · conversational · social · none — "none" predicts decay) · **Same-Population Principle** · **Workflow Gesture** (the update path that already exists) · **Information Scent** (discoverability as a property of labels) · **Discovery-in-Flow** · **Born-Stale** (intake failure, distinct from decay) · **Knowledge Half-Life** (domain-relative depreciation) · **Supersession** (retirement by link, never edit) · **Staged Retirement** (demote visibly, archive retrievably, never destroy silently) · **Canonical / Historical / Deprecated** (the status vocabulary — visible at the point of discovery or worthless) · **Scoped Canonicity** (one truth per scope, never globally) · **Rationale Gap** (most demanded, least captured, hardest to validate) · **Tacit Residue** (what externalization cannot reach; mitigated by succession, not capture) · **Concentration Risk / Truck Factor** · **Minimal Sufficient Context** (assembly beats volume, for humans and models) · **Consumption Feedback Loop** (no use signal → no quality) · **Contradiction Coexistence** (plural ranked truths at rest; resolution at consumption).

*(Format-independence check: every term above survives the Markdown-removal test — each names a property of knowledge, not of documents.)*

## 3. Normalized Knowledge Ontology — INTERPRETATION (semantic backbone; not folders, not a schema)

```text
PROJECT KNOWLEDGE
├── BINDING knowledge        (invariants · tests · gates)
│     validation: EXECUTION · evolution: change-with-code · authority: the passing run
│     producers=consumers=developers · highest trust stratum
├── DECISION knowledge       (ADR-class · rulings)
│     validation: enforcement-binding to code (the only longitudinal ADR success required it)
│     evolution: IMMUTABLE + supersession-by-link · canonical = accepted ∧ unsuperseded ∧ owned ∧ bound
│     the Rationale Gap lives here — most demanded, least captured
├── DEFINITIONAL knowledge   (ubiquitous-language terms · context boundaries)
│     validation: CONVERSATIONAL (drift heard as awkwardness; term drift = boundary signal)
│     evolution: continuous · smallest unit: term-bound-to-context · rots when the conversation stops
├── DESCRIPTIVE knowledge    (guides · READMEs · views)
│     validation: freshness + ownership + review-with-code · evolution: regenerate-or-evolve
│     survives ONLY code-proximate / same-population; second-population variants decay at wiki rate
├── EXPERIENTIAL knowledge   (lessons · retro records · qualification evidence)
│     validation: claim evaluation at intake (almost never implemented; its absence = LLIS failure)
│     evolution: append-only record; falsified claims retained as still-informative
└── TACIT knowledge          (mental models · skills)
      validation: use · transfer: practice/succession ONLY (apprenticeship, successor assignment)
      not fully externalizable — capture programs against it fail; measure via concentration risk
Cross-cutting properties attaching to EVERY node: provenance · status (visible at discovery) ·
ownership · freshness (half-life priced per domain) · scent (labels) · granularity (claim-level quality)
```

**The primary cut is by validation mechanism and population structure — not by topic.** (The going-in seven-kind taxonomy cut by topic; see §7 falsifications.)

## 4. Ownership map — what a Project Knowledge capability would OWN / OBSERVE / NEVER TOUCH

- **Owns:** the status vocabulary and its visibility-at-discovery rule · intake validation gates (born-stale defense) · retirement cadence and staged-forgetting mechanics · scent conventions (labeling/addressability) · the claim-granularity rule · the discovery/context-assembly capability.
- **Observes:** every knowledge artifact in place — ADRs stay with their deciders, code-proximate docs with their code, EKP cards with their domain owners, session records with the runtime. *(The evidence forbids centralizing them: knowledge lives with what it describes.)*
- **Never touches:** the content of knowledge (producers own their claims) · tacit knowledge (only succession mechanics apply — no capture mandate) · product or engineering decision authority.

## 5. Context map — relationships to the existing landscape

- **Engineering Platform:** supplies the validation machinery (qualification lifecycle, evidence discipline, append-only registers). FACT worth naming: the platform *independently converged* on the literature's strongest invariants before this research existed — supersession-not-edit, PASS AFTER CORRECTION status history, demote-visibly, evidence-before-acceptance. The platform is itself corroborating evidence.
- **Product (PublicDigit):** the code is the primary knowledge carrier (highest trust stratum); domain knowledge originates here; the constitutional invariants are Binding knowledge already validated by execution (fitness suites).
- **Runtime:** session startup is the existing context-assembly gesture (engineering context injected; **project-context assembly is the observed gap**); `CLAUDE.md`-class files are Documentation-as-Context — subject to the non-derivable-only rule (duplicating the derivable is measured harm); agentic search over the live repo is the discovery mechanism that displaced indexes.
- **EKP:** the incumbent implementation of governance metadata (aligned) with three challenged assumptions (centrality, single lifecycle, card granularity) and one pending usage test (§1c).

## 6. Open questions register

1. **Did the EKP get used in-flow during PB-004..007?** (Decisive for §7; answerable from session logs — behavioral evidence, not survey.) — OPEN
2. **Conflict resolution for machine consumers:** coexist-at-rest works for curation; what resolves ranked contradictions when an *agent* must act? (Named open gap in the AI literature.) — OPEN
3. **Immutability vs evolution at the boundary:** which regime claims artifacts that are part decision, part description (e.g., IDDs)? — OPEN
4. **Claim-granularity economics:** assertion-level quality is right in principle; is the bookkeeping affordable outside high-stakes claims? — OPEN
5. **Does conversational validation scale when some engineers are AI?** (DDD's mechanism presumes human conversation cadence.) — OPEN
6. **Rationale capture at near-zero cost:** can decision knowledge be mined from existing gestures (commits, PRs, session logs) rather than authored? (The literature says capture must become free to survive.) — OPEN
7. **Token economics of agentic discovery vs curated assembly** at repository scale. — OPEN

## 7. Recommendation — with the falsification results the charter demanded

**Going-in assumptions, judged:**
- **Seven-kind topic taxonomy: REFRAMED (partially falsified).** The load-bearing cuts are validation mechanism and population structure (§3); topic is a secondary facet.
- **EKP single-lifecycle model: FALSIFIED.** At least four structurally different lifecycles exist (§3).
- **"Second platform" hypothesis: WEAKENED to the point of rejection in its store form.** A new central knowledge platform would be the wiki/LLIS pattern with better intentions — second population, separate gesture, graveyard lifecycle. The evidence-shaped alternative is *rules + metadata on artifacts where they live, plus one capability.*

**RECOMMENDATION (per the charter's four options): "absorbed rules" + one targeted capability — NOT a new platform.**
1. **Absorb as rules** (the ER-09 reframe, when unpaused): status-visible-at-discovery · supersession-by-link · staged retirement · ownership+freshness as authority · intake validation for experiential knowledge · scent conventions · non-derivable-only for context files. Most are already platform practice — this names and generalizes them.
2. **The one evidenced new capability: project-context assembly in-flow** — extend the *existing* session-startup gesture (same population, same gesture — the law) to assemble task-relevant project knowledge (governing ADRs, glossary terms, prior reports for the ticket at hand) via agentic discovery over live sources, not an index. This attacks the observed gap directly and is the only candidate that satisfies the same-population law by construction.
3. **EKP: retain, test, refine — do not expand.** Run the §6-Q1 usage test first; if the EKP was bypassed in-flow, its next evolution is coupling and granularity, not content growth.
4. **Explicitly not recommended:** a knowledge graph/ontology system (cost scales faster than value; minimal cores win) · a new repository or portal · capture mandates for tacit knowledge · numeric confidence scores on human-facing knowledge (unvalidated) — each rejected on sourced negative findings.

**STOP.** The ARB decides whether this evidence authorizes design, and in which of the four directions.

---
*Success criteria check: a format/tool/provider/project-independent ubiquitous language was derived (§2 — every term passes the Markdown-removal test); all three going-in assumptions were confirmed-or-falsified with evidence (§7); the ontology draft exists (§3); exit criteria met — literature review across seven disciplines · universal concepts identified · contradictions documented (35, preserved in the annex) · negative findings mandatory quota exceeded (33). Time-box honored: single research cycle, four parallel executors.*
