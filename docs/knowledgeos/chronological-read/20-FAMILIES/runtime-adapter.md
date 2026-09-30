# runtime-adapter

**Scope(s):** OBJECT · **Row count:** 18 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** .claude/, AI Runtime, Runtime Adapter · **Aliases:** Engineering Runtime (rejected/merged concept)

**Candidate group membership (NOT an identity claim):**
- **G1094** [`capability` · `runtime-adapter`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- **G1102** [`pks` · `runtime-adapter`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0001, scope OBJECT: How one runtime implements permitted actions; repeatedly confirmed to sit outside the core ontology by disappearing under cross-domain projection.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0001 §"Finding 2 — there are TWO kinds of "unowned", and only one is a defect"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0008 §"Canonical concept glossary — the 14 examined, plus 3 the corpus forces in"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0039. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0001, S0002, S0024 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0008, S0024, S0028 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0001, S0002, S0005, S0028, S0039 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0001, S0005 |
| examples | PRESENT | S0011, S0024 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0022 |
| open_questions | PRESENT | S0001, S0011, S0021 |

## Rationale

Unowned-because-UNADOPTED (the entire discovery corpus, Generated/CANDIDATE) is CORRECT — the promotion ladder working, since a candidate with an owner would be adoption smuggled past the gate. Unowned-while-OPERATING (exactly one instance found: the Runtime Adapter, RO-4) is the genuine defect class and is already docketed. This reframes the programme's recurring 'ownership-gap pattern': most of it was never a gap; the anomaly list shrinks to one item [S0001]. ES-005.1 establishes the runtime mount is never the architecture, across four layers; a specific detail was falsified: no Capability Mapping artifact exists, and deny/ask permissions are approximately identity-based. Runtime sits outside the core ontology, confirmed by its disappearance in both projections; the boundary's existence is confirmed, but its runtime-independence is only tested at n=1 [S0002]. Eleven responsibilities scored across five dimensions (Product Discovery, Strategic Discovery, Tactical Discovery, PKS Generation, Engineering Governance, Capability Runtime, Runtime Adapter, Verification, Evidence Collection, Operational Learning, Capability Evolution). Dimension totals: METHOD 8/11 complete (strongest dimension); BINDING 4/11 carry a coupling (SD-1 fatal, ES-005.1 leak, config paths, 2 product-specific by nature); EVIDENCE 4/11 at n=0, only 3 have more than a single instance; IMPLEMENTATION 6/11 fully manual, 0 generative; ENFORCEMENT 0/11 (before correction below) [S0024].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0001]` types=[DISTINCTION, ANALYSIS] scope=THEORY-LEVEL — "Unowned-because-UNADOPTED (the entire discovery corpus, Generated/CANDIDATE) is CORRECT — the promotion ladder working, since a candidate with an owner would be adoption smuggled past the gate. Unowned-while-OPERATING (exactly one instance found: the Runtime Adapter, RO-4) is the genuine defect class and is already docketed. This reframes the programme's recurring 'ownership-gap pattern': most of it was never a gap; the anomaly list shrinks to one item." (anchor: "Finding 2 — there are TWO kinds of "unowned", and only one is a defect")
- `[S0001]` types=[OPEN-QUESTION] scope=OBJECT — "Unknown U-VO-1: Runtime Adapter ownership (the one operating-unowned asset), waiting on RO-4 already open at the Decision Authority." (anchor: "U-VO-1 | Runtime Adapter ownership")
- `[S0002]` types=[ANALYSIS, CORRECTION] scope=OBJECT — "ES-005.1 establishes the runtime mount is never the architecture, across four layers; a specific detail was falsified: no Capability Mapping artifact exists, and deny/ask permissions are approximately identity-based. Runtime sits outside the core ontology, confirmed by its disappearance in both projections; the boundary's existence is confirmed, but its runtime-independence is only tested at n=1." (anchor: "Runtime Boundary — ES-005.1 (the mount "is never the architecture") ... falsified detail: no Capability Mapping artifact; deny/ask ≈ identity")
- `[S0005]` types=[VALIDATION, DISTINCTION] scope=CROSS-OBJECT — "Relationship R-7: Capability is runtime-faced by an Execution Asset, through a Runtime Adapter, to the AI Runtime. External support: hexagonal ports/adapters as a chain (CANONICAL); PEP placement at the boundary [FETCHED]. Inversion recorded: externally the chain is standard practice; internally the capability<->AST link is undeclared (SC-6) — validated outside, unlinked inside. Confidence: MEDIUM-HIGH." (anchor: "R-7 | Capability —runtime-faced by→ Execution Asset → Runtime Adapter → AI Runtime | MEDIUM-HIGH")
- `[S0007]` types=[VALIDATION] scope=OBJECT — "Runtime adapters and a model-agnostic layer are supported by hexagonal architecture (Cockburn) and current LLM-provider abstraction practice. Confidence: HIGH." (anchor: "Runtime adapters; model-agnostic layer ... hexagonal architecture (Cockburn) · current LLM-provider abstraction practice")
- `[S0008]` types=[DEFINITION, FORMALIZATION] scope=THEORY-LEVEL — "A 17-row glossary assigns each term (KnowledgeOS, PKS, Engineering Knowledge, Engineering Capability, Engineering Method, Engineering Governance, Engineering Runtime, Runtime Adapter, Engineering Evidence, Engineering Ontology, Documentation Ontology L-A, Platform Ontology L-B, Product Ontology L-C, Knowledge Graph, Engineering Kernel, Knowledge Space, Design Policy DP-n, Execution Asset AST-nnn) one meaning, owner, protected invariant, place, and verdict (KEEP/REJECTED-MERGED/SPLIT/CANDIDATE). Success criterion: every major engineering term gets one meaning, one owner, one place." (anchor: "Canonical concept glossary — the 14 examined, plus 3 the corpus forces in")
- `[S0008]` types=[CORRECTION] scope=OBJECT — "'Engineering Runtime' as a distinct concept is rejected (met only 2 of 9 criteria) and merges into 'Runtime Adapter'." (anchor: "⛔ Engineering Runtime | REJECTED as a concept — 2 of 9 criteria; merges into Runtime Adapter")
- `[S0011]` types=[COUNTEREXAMPLE, CONTRADICTION] scope=OBJECT — "D-3's claim that a boundary built to hold a language apart is a bounded context by construction is falsified by three tests: (1) no artifact holds the abstract capability vocabulary (no Capability Mapping file exists); (2) exactly one source artifact cites it, with no independent consumer; (3) the translation is not non-trivial — .claude/settings.json's deny/ask/allow (19/22/8 entries) is very nearly the identity function mapping to the abstract vocabulary. DDD consequence: an Anti-Corruption Layer that performs an identity mapping is not an ACL, it is a restatement. D-3 drops from STRONG to WEAK." (anchor: "W-2 · D-3's ubiquitous-language divergence has no artifact — the strongest claim in D-3 is falsified")
- `[S0011]` types=[OPEN-QUESTION] scope=OBJECT — "U-2: would a second runtime require translation, or is deny/ask/allow universal — untestable at n=1; if universal, D-3's ACL is permanently an identity map." (anchor: "U-2 | Would a second runtime require translation, or is deny/ask/allow universal?")
- `[S0021]` types=[OPEN-QUESTION] scope=OBJECT — "RO-4: does the Runtime Adapter need a declared owner, since none was found — authority: DA." (anchor: "RO-4 | Does the Runtime Adapter need a declared owner?")
- `[S0022]` types=[CORRECTION] scope=THEORY-LEVEL — "Four pre-validation corrections: (1) I-11 'Knowledge is never generative' is too strong, restated as 'Knowledge does not execute; Capabilities execute; Knowledge constrains and informs generation' — sharper and testable, and it survives projection where the original would not; (2) 'Engineering Method' may be a missing layer is recorded as hypothesis only, distinguished from Method already existing as domain PD-2; (3) Runtime Adapter belongs outside the core ontology is accepted and empirically confirmed by disappearing in all three projections; (4) the ontology models containment but not specialization is accepted as the single most consequential gap the projection confirms." (anchor: "FOUR PRE-VALIDATION CORRECTIONS ACCEPTED — applied BEFORE the ontology was frozen")
- `[S0022]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Twelve concepts projected into PublicDigit, a hypothetical Hospital Information System, and a hypothetical ERP System: MISSION, STRATEGY, DESIGN POLICY, CAPABILITY, KNOWLEDGE, ARTIFACT, KNOWLEDGE SPACE, PRODUCT are invariant across all three; PRINCIPLE specializes (partly external in Hospital and ERP, owned by a regulator/accounting standard); PKS fails (one per product, or per deployment?); RUNTIME ADAPTER disappears in both new domains; PROJECTION (I-4) fails, since a discharge summary and a financial statement are both generated and legally/formally authoritative." (anchor: "Concept-by-concept projection ... 8 invariant · 2 specialize · 1 disappears · 1 relationship fails")
- `[S0024]` types=[ANALYSIS, FORMALIZATION] scope=THEORY-LEVEL — "Eleven responsibilities scored across five dimensions (Product Discovery, Strategic Discovery, Tactical Discovery, PKS Generation, Engineering Governance, Capability Runtime, Runtime Adapter, Verification, Evidence Collection, Operational Learning, Capability Evolution). Dimension totals: METHOD 8/11 complete (strongest dimension); BINDING 4/11 carry a coupling (SD-1 fatal, ES-005.1 leak, config paths, 2 product-specific by nature); EVIDENCE 4/11 at n=0, only 3 have more than a single instance; IMPLEMENTATION 6/11 fully manual, 0 generative; ENFORCEMENT 0/11 (before correction below)." (anchor: "The responsibility matrix (11 responsibilities × METHOD/BINDING/EVIDENCE/IMPLEMENTATION, plus ENFORCEMENT)")
- `[S0024]` types=[CORRECTION, COUNTEREXAMPLE] scope=CROSS-OBJECT — "A prior claim that 0 of 11 responsibilities are enforcing is corrected: applying the Boundaries!=Triggers vocabulary immediately falsifies it, since .claude/settings.json holds permissions.deny x19 (BOUNDARY, enforced) and permissions.ask x22 (TRIGGER, enforced) — 41 controls the runtime genuinely enforces; 10 hooks + 6 scripts are ADVISORY (16, not enforced). The sharper finding: enforcement lives in the RUNTIME ADAPTER, capabilities live in the PLATFORM — enforcement exists exactly where governance does not, and is absent exactly where it does." (anchor: "CORRECTED 2026-08-02 — "0 OF 11 ENFORCING" IS TOO STRONG ... .claude/settings.json holds deny ×19 and ask ×22 — 41 controls the runtime genuinely ENFORCES. What I actually established is that no CAPABILITY enforces, not that nothing does")
- `[S0028]` types=[DEFINITION] scope=THEORY-LEVEL — "Product definitions: KnowledgeOS (the reusable engineering platform, output is rules and methods, never product knowledge; canon status: potential product, Supporting Subdomain, gate unopened); PKS (one product's knowledge, one per product, never reusable, generated is n=0, every PKS hand-built to date); Business Product (the software delivering business value); Running Software (deployed system + evidence); AI Runtime (an adapter, not a product, replaceable); Product Binding (the product-specific input contract a reusable method requires, SD-1 the archetype — 'the concept this baseline contributes'). The generative relation is 'creates', not 'contains': KnowledgeOS creates PKS, which guides Product — a compiler is not the program it produces." (anchor: "Product Definition ... The generative relation is creates, not contains. KnowledgeOS → creates → PKS → guides → Product")
- `[S0031]` types=[VALIDATION] scope=THEORY-LEVEL — "The AI Runtime architecture is already fully modeled in PKS_Phase_III_Governance_Runtime_Adapter_Record.md as four layers: Governance Policy -> Capability Mapping (tool-neutral) -> Runtime Adapter -> Concrete Configuration (.claude/settings.json). .claude/settings.json is an adapter, not the governance model; another runtime could replace Claude Code and only the adapter would be rewritten. Capability Mapping exists so Claude-specific vocabulary (ask, deny, permissions) cannot leak upward into the governance model." (anchor: "ALREADY FULLY MODELLED — four layers, not two ... GOVERNANCE POLICY → CAPABILITY MAPPING → RUNTIME ADAPTER → CONCRETE CONFIGURATION")
- `[S0037]` types=[CORRECTION] scope=OBJECT — "PD-6, Runtime, scores only 2 of 9 criteria (integration characteristics observed; deployment autonomy only claimed at n=1) since UL divergence is falsified (no Capability Mapping artifact; deny/ask/allow approximate the abstract verbs) and semantic ownership is absent (no declared owner for .claude/) — below the commissioned bar, demoted from an earlier D-3 classification, and classified as a supporting subsystem with an adapter boundary, not a domain." (anchor: "PD-6 · Runtime — 2 criteria. BELOW the bar ... NOT A DOMAIN at the commissioned bar. A supporting subsystem with an adapter boundary")
- `[S0039]` types=[CORRECTION] scope=OBJECT — "The AI Runtime, proposed by the commission as a strategic product, is reclassified as an adapter boundary, not a product: ES-005.1 states the mount 'never moves and is never the architecture', the Runtime Adapter Record calls .claude/settings.json 'an ADAPTER'; it owns how one runtime implements each abstract capability but never owns governance vocabulary ('ask, deny, permissions cannot leak upward'); it is replaceable, not reusable, a different property. It is already modeled in four layers: Governance Policy -> Capability Mapping -> Runtime Adapter -> Concrete Configuration." (anchor: "SP-3 · AI Runtime — reclassified as an ADAPTER BOUNDARY, not a product ... already 4 layers")

## Notes for P3

CONTESTED is backed by at least one CONTRADICTION-typed row in this label's own rows (see 'All rows' below) — the flag appears correctly grounded, not spurious, but P3 should still confirm which side (if any) is currently held.
