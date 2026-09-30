# ontology

**Scope(s):** OBJECT · **Row count:** 13 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Documentation Ontology"; L-A; L-B; L-C
**Aliases:** "Meta-Model"; "Relationship Ontology"
**Candidate group membership (NOT an identity claim):**
- G0030: explicit agent-stated uncertainty that `ontology` POSSIBLY relates to `meta-model` (batch B0001) — "The layered classification of documentation vs platform vs product knowledge; distinct from but closely coupled to the meta-model dimensional work — kept as a separate label pending D-8 reconciliation, per the corpus's own unresolved distinction between the two."
- G0729: shares the notation "L-B" with `meta-model`.
- G1095: co-occurs (in the same contribution's `labels[]`) with `decision-model`, 2 separate times across the corpus.
- G1107: co-occurs with `meta-model`, 2 separate times across the corpus.

Relationship to any of these — never an identity claim — is explicitly left to P3.

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0001, scope OBJECT, `relation_to_existing: POSSIBLY:meta-model`: "The layered classification of documentation vs platform vs product knowledge; distinct from but closely coupled to the meta-model dimensional work — kept as a separate label pending D-8 reconciliation, per the corpus's own unresolved distinction between the two."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0006 §"Q6 — extend the model, or map onto it? ... MAP. Tested element by element"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0023 §"Package 4 — D-8 · Reconcile the three knowledge ontologies (blocks the same section)"]

## Lifecycle

last_seen: S0026. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT here is a heuristic based on recency of last use (by source_id), not a confirmed retirement — the row set itself ends on an open governance question (U-ONT-5, ownership unresolved), not a closure.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0006, S0007, S0020 (×4) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0006, S0007, S0009, S0013, S0023 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0006, S0013, S0020 |
| examples | PRESENT | S0020 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0009, S0026 |

## Rationale

S0006 concludes ontology/meta-model elements *map onto* the existing decision model rather than extending it — element by element (archetype classification, KNOWLEDGE≠ARTIFACT, AUTHORITY-SCOPE/CONTAINER, GOVERNANCE-STATUS/EVIDENCE-STATUS, NATURE) — with the verdict that "the ontology supplies the vocabulary the catalog's procedures presuppose; the catalog supplies the purpose the ontology elements must serve — they interlock, neither extends the other" [S0006]. S0007 raises a governance challenge (X-3) that OBO-style community ownership of an ontology conflicts with the platform's individual-ownership model [S0007]. S0020 does the bulk of the corrective/analytical work: it withdraws a prior recommendation to express D-1..D-7 in `knowledge-types.yaml` as mixing abstraction levels [S0020]; establishes, via three independent proofs (key collision, vocabulary insufficiency, subject mismatch), that D-1..D-7 belong to a not-yet-existing L-B (Platform Ontology) rather than the existing L-A (Documentation Ontology) [S0020]; confirms `knowledge-types.yaml` is a documentation ontology and not a hybrid, since its entries are documents *about* domain objects rather than the domain objects themselves [S0020]; and settles that the ontology has exactly three layers (L-A exists/executable/enforced, L-B does not exist, L-C partially exists), explicitly declining a proposed fourth "Engineering Knowledge Ontology" middle layer as not separately evidenced [S0020]. S0023 records the governance consequence: an ARB decision package (D-8) to reconcile three competing models of the same context (L-A, the Metamodel, RQ-002), recommending RQ-002 as the dimensional frame with the other two scoped under it [S0023].

`rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0006] types=[ANALYSIS, DISTINCTION] scope=CROSS-OBJECT (also labeled `decision-model`) — "Ontology/meta-model elements map onto the existing decision model rather than extending it ... Verdict: the ontology supplies the vocabulary the catalog's procedures presuppose; the catalog supplies the purpose the ontology elements must serve — they interlock, neither extends the other." (anchor: "Q6 — extend the model, or map onto it? ... MAP. Tested element by element")
- [S0007] types=[ARGUMENT] scope=OBJECT — "Challenge X-3: OBO-style community-ownership ontology-governance practice challenges the platform's individual ownership of a governed vocabulary. Bears on SC-2/D-6." (anchor: "X-3 | ontology governance practice (OBO-style community ownership, versioning) — challenges individual ownership of a governed vocabulary")
- [S0008] types=[CORRECTION] scope=OBJECT — "'Engineering Ontology' as a distinct concept is rejected and merges into Documentation Ontology (L-A), since L-A already covers engineering-knowledge kinds; a proposed fourth ontology layer was declined for lack of evidence." (anchor: "⛔ Engineering Ontology | REJECTED — merges into Documentation Ontology. L-A already covers engineering-knowledge kinds; a fourth layer was declined on evidence") — lineage claim: SOURCE-CLAIMED-REPLACEMENT of "Engineering Ontology".
- [S0009] types=[OPEN-QUESTION, CONSTRAINT] scope=THEORY-LEVEL (also labeled `meta-model`) — "U-MM-5: the three formal meta-artifacts (RQ-002, the Metamodel, this Discovery) must not become a fourth unreconciled ontology; this document is input to D-8's reconciliation, never a competitor." (anchor: "U-MM-5 | The three formal meta-artifacts (RQ-002 · the Metamodel · this discovery) must not become a fourth unreconciled ontology")
- [S0013] types=[RESTATEMENT] scope=THEORY-LEVEL (also labeled `meta-model`, `provenance`, `decision-model`, `knowledge-space`, `pks`) — "Fifteen candidate concerns, each citing its supporting commission and what it waits on" (K-1 through K-15, spanning the dimensional meta-model, archetype set, partial orders, invariant relationships, capability anchoring, Knowledge Space definition, authority, strategic domains, viewpoints, decision-readiness, FREEZE/RETIRE, PKS, canon-track artifacts, mission enactment, ownership splits). (anchor: "2. CANDIDATE — supported by evidence, awaiting ruling or more evidence (K-1..K-15)")
- [S0020] types=[RETRACTION] scope=OBJECT — "A prior recommendation to express D-1..D-7 in knowledge-types.yaml is withdrawn: it mixed abstraction levels, since Engineering Governance is not a document." (anchor: "THE CRITIQUE CORRECTS MY OWN RECOMMENDATION, AND IT IS RIGHT ... I recommended \"express D-1..D-7 in knowledge-types.yaml.\" That mixes abstraction levels") — lineage claim: SOURCE-CLAIMED-RETRACTION.
- [S0020] types=[ANALYSIS, CORRECTION] scope=THEORY-LEVEL — "Every schema file was parsed with the repository's own YAML loader. Finding: the graph's ~13% document coverage is a declared scope, not an oversight — extending it would amend a declared scope, a governance act, not fix a bug." (anchor: "Ontology scope — per schema file, as parsed ... THE 13% COVERAGE IS A DECLARED BOUNDARY, NOT AN OVERSIGHT")
- [S0020] types=[ANALYSIS, DISTINCTION] scope=OBJECT — "knowledge-types.yaml is confirmed as a documentation ontology, not a hybrid ... these are documents about domain objects, not the domain objects themselves." (anchor: "knowledge-types.yaml IS a documentation ontology — I checked the temptation to call it a hybrid, and it is not one ... These are documents ABOUT domain objects")
- [S0020] types=[CORRECTION, VALIDATION] scope=THEORY-LEVEL — "Three ontology layers are recognized ... L-A Documentation Ontology exists, executable, enforced; L-B Platform Ontology does not exist; L-C Product Ontology partially exists. A proposed fourth 'Engineering Knowledge Ontology' middle layer is declined as not evidenced separately." (anchor: "Ontology layers — THREE, not four ... a fourth layer is therefore declined ... L-A EXISTS · EXECUTABLE · ENFORCED ... L-B DOES NOT EXIST ... L-C PARTIALLY EXISTS")
- [S0020] types=[COUNTEREXAMPLE, ARGUMENT] scope=CROSS-OBJECT (also labeled `platform-domain`) — "Three independent proofs that D-1..D-7 do not belong in knowledge-types.yaml or bounded-contexts.yaml: key collision (mechanical/decisive); vocabulary insufficiency (strong); subject mismatch (strong). Verdict: D-1..D-7 belong to L-B (which does not yet exist)." (anchor: "Three independent proofs that D-1..D-7 do not belong in the existing schemas ... KEY COLLISION — mechanical, not aesthetic")
- [S0020] types=[ANALYSIS] scope=CROSS-OBJECT — "L-B would own and classify L-A, never the reverse ... two separately-warranted, ARB-only changes exist" regarding scope.include extension and edge-vocabulary expansion. (anchor: "The five commissioned deliverables ... L-B ↔ L-A relationship | L-B would OWN and CLASSIFY L-A; L-A would never contain L-B")
- [S0023] types=[GOVERNANCE] scope=OBJECT — "Package 4 (D-8): rule the scopes of L-A, the Metamodel (CANDIDATE, gate OQ-ENG-004), and RQ-002 (COMPLETE, dimensional) — one context, three competing models. Recommendation: ADOPT RQ-002 as the dimensional frame; scope the other two under it. Authority: ARB." (anchor: "Package 4 — D-8 · Reconcile the three knowledge ontologies (blocks the same section)")
- [S0026] types=[OPEN-QUESTION] scope=OBJECT — "U-ONT-5: who owns the ontology, recorded as nobody (same answer as U-MM-2) — waits on SA-1/sponsor layer." (anchor: "U-ONT-5 | Who owns the ontology? — same answer as U-MM-2: nobody")

## Notes for P3

- This label is explicitly and self-consciously entangled with `meta-model` (G0030, G0729, G1107) and `decision-model` (G1095) in the source material itself — the founding PROPOSAL note says the label was "kept as a separate label pending D-8 reconciliation," and row S0023 records an actual ARB decision package (D-8) whose recommendation is to adopt RQ-002 (associated with the meta-model side) as the frame and scope L-A (the executable documentation ontology, this label's clearest referent) under it. P3 should treat D-8 as the authoritative reconciliation point rather than inferring identity from co-occurrence alone.
- The last row (S0026, U-ONT-5) leaves ontology ownership explicitly unresolved ("nobody"), so DORMANT lifecycle should not be read as "settled" — the open question was never shown as closed within this label's own row set.
- Several rows (S0008, S0020 ×3) are strongly self-correcting in character (a retraction, a "the critique corrects my own recommendation, and it is right" admission, and an explicit "not four, three" layer-count correction) — this label's own evidentiary trail already models a mini-lifecycle of hypothesis→correction that later reconciliation work may want to preserve rather than flatten.
