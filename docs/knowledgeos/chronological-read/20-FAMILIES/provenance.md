# provenance

**Scope(s):** OBJECT · **Row count:** 54 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `authorities.yaml`, `generated/derived/authoritative` · **Aliases:** `authority (as provenance x standing)`
**Candidate group membership (NOT an identity claim):**
- **G0051**: [`inv-001-evidence-ne-authority` · `provenance`] — explicit agent-stated uncertainty: 'inv-001-evidence-ne-authority' POSSIBLY relates to 'provenance' (batch B0006). Note: One of the 4 'Established' EKS/PKS/AIP-synthesis constitutional invariants (Evidence != Authority), registered UM-24, unchanged through the P4/P5/kernel-decision/Constitution chain; may overlap an already-registered general object of the same name from an earlier batch.
- **G0190**: [`formal-provenance-lineage-audit-graph` · `provenance`] — explicit agent-stated uncertainty: 'formal-provenance-lineage-audit-graph' POSSIBLY relates to 'provenance' (batch B0022). Note: Recurring label across S0928, S0929, S0931, S0932, S0933, S0934, S0936 for the formal provenance/dependency/lineage graph underlying uncertainty propagation, common-cause detection, translation lineage, backward error-propagation, and assurance graphs; closely related to, and possibly identical with, the already-registered provenance and claim-provenance-chain objects. Added here to satisfy the batch's own label-registration requirement; a future merge review should determine whether this collapses into those existing objects.
- **G0670**: [`knowledge-space` · `provenance`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0006, source S0202) named these as alternative candidates for one piece of evidence. why_uncertain: A generic KM-literature distinction; not tied to any specific already-indexed object.
- **G0673**: [`provenance` · `provenance-ontology-gap`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0006, source S0203) named these as alternative candidates for one piece of evidence. why_uncertain: General cross-system provenance-weakness finding; may relate to the existing 'provenance-ontology-gap' externally-sourced finding or to the corpus's plain 'provenance' object, but neither match is confirmed.
- **G1110**: [`knowledge-flow` · `provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1664**: [`canonical-theory-triangulation-table` · `provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1690**: [`canonical-knowledge-state-model-K-AR` · `provenance`] — labels co-occur in the same contribution's labels[] 5 separate times across the corpus
- **G1691**: [`knowledge-state-equality-relation-family` · `provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1693**: [`canonical-theory-baseline-classification` · `provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0001, scope OBJECT): The immutable-origin half of the 'authority' field, discovered to be conflated with mutable 'standing' in a single dimension; central to the I-4/DP-2/LG-1 corrections.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0003] §"The reviewer proposes a "Provenance Domain" ... Verdict: PROVENANCE is not a missing domain — it is a DISPERSED, NAMED, PARTLY-GOVERNED concern"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0202] §"Knowledge validity and recording time are different concepts ... 'What was considered authoritative at time T?' ... requires temporal state, authority state, lineage state simultaneously."
- CANDIDATE-FORMAL-BIRTH: [S0018] §"The progression mechanisms — inventoried as evidence (P-1..P-10)"
- CANDIDATE-OPERATIONAL-BIRTH: [S1676] §"Both states hold the SAME two propositions with the SAME per-assertion provenance. They differ ONLY in whether a derivation relation exists. ... withdraw('vendor'): KA -> [...] KB -> [...] ... OBSERVED: the two states agree on every proposition AND on every proposition's provenance, yet withdraw() d"
- CANDIDATE-GOVERNANCE-BIRTH: [S1661] §"PROVENANCE PLACEMENT: SUBSTANTIALLY RESOLVED. KnowledgeOS must preserve provenance association, but the evidence does not require the complete provenance object to be embedded in the Knowledge State. ... π∈K while ProvenanceObject⇏K and Provenance≠History(T). ... Provenance placement substantially r"

## Lifecycle
last_seen: S1676. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True — two rows from S1637 (rows 28-29 below) are themselves typed CONTRADICTION, and a later row (S1676, row 53) explicitly qualifies/limits the scope of what had been declared "substantially resolved" at S1661 (Step 265). This is a heuristic mechanical flag, not a P3 resolution — see "Notes for P3" below for what the actual tension is.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0003, S0012, S0016, S0018, S0202, S1637, S1661, S1667 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0018, S0021, S0202, S0451, S1661, S1673 |
| Type signature | PRESENT | S0018 |
| Invariants | PRESENT | S0012, S0016, S0021, S0451 |
| Dependencies | PRESENT | S0003, S0004, S0007, S0008, S0012, S0013, S0015, S0018, S0021, S0022, S1661, S1663, S1664, S1666, S1667, S1668, S1670, S1673, S1676 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0012, S0013, S0202, S0451, S1661, S1664, S1670 |
| Examples | PRESENT | S0022, S1661 |
| Warnings | PRESENT | S1661 |
| Experiments | PRESENT | S1666, S1676 |
| Open questions | PRESENT | S0003, S0008, S0012, S0018, S0022, S1663 |

## Rationale
A check-before-recording pass finds provenance already lives dispersed in the corpus (authority's provenance-axis, the PROVENANCE non-progression kind, identity discipline CAP-001, evidence grading, traceability blocks) — verdict: a dispersed, partly-governed concern, not a missing domain [S0003]. This dispersion is diagnosed mechanically: `authorities.yaml`'s five values conflate two different questions — "generated"/"derived" answer *provenance* (immutable origin) while "authoritative"/"provisional"/"historical" answer *standing* (mutable trust judgment, changed only by governance acts) [S0012, S0018]. That split dissolves LG-1 ("should authority have a governed state machine?") as ill-posed, since provenance cannot have a state machine at all while standing changes by governance act, and gives a mechanical cause to a prior reviewer's diagnosis that DP-2 conflates derivation with attestation [S0012]. External literature is brought in to corroborate and extend this: W3C PROV-DM's Entity/Activity/Agent/Derivation/Attribution primitives (later expanded to nine), temporal-database research treating validity-time and recording-time as distinct axes needed simultaneously with authority state and lineage state, and knowledge-governance literature holding that authority is organizational, not epistemic [S0202]. The object then closes a much larger gap at Step 265 (S1661): the requirement that provenance be preserved *somewhere* does not entail it must live inside the Knowledge State K — a base-case counterexample (K0 has empty transformation History yet its assertions still have external origins) refutes Provenance=History(T) as a universal identity, motivating a reference-based architecture (a stable in-K provenance reference π, with the full ProvenanceObject held externally) as the smallest defensible placement, and establishing that preserving provenance does not force semantic equality to become provenance-sensitive [S1661]. This "substantially resolved" verdict is later itself qualified, not overturned: EXP-2 (S1676) shows two states agreeing on every proposition *and* every proposition's provenance can still be distinguished by `withdraw()`, proving the load-bearing component for that operation's correctness is the relation set R (specifically derivation relations), not provenance placement — meaning Step 265's placement conclusion answers a real question but not the sufficiency question it is often cited for [S1676].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
This label has 54 rows — too many to list individually while keeping the file readable. Rows are grouped into content-based themes below; each theme names the rows condensed into it (by position in the ledger-order row list for this label) and its representative source_id(s). Full text for every row is in `03-CONTRIBUTIONS.jsonl`.

**Early PM-1 framing: authority = provenance x standing** (5 rows condensed into this theme; source: S0003, S0004, S0007, S0008)
Rows 0-4. The check-before-recording verdict that provenance is a dispersed, partly-governed concern rather than a missing domain, with an open question (U-GR-2) on whether it stays an enrichment of Knowledge Governance; PM-1 enriched via industry's governance-control-plane framing; the authority=provenance×standing formula validated against W3C PROV and data-catalog practice (HIGH confidence); an open question (SC-5) on whether authority's single field improperly combines the two.

**I-4/DP-2 diagnosis cascade** (4 rows condensed into this theme; source: S0012)
Rows 5-8. Retracts a prior "I-4 falsified" claim, reframing it as "DP-2 conflates DERIVATION with ATTESTATION"; traces the mechanical cause — `statuses.yaml` is far better-equipped (order, terminal marker, role table, guards) than `authorities.yaml`, so authority transitions (e.g. derived→authoritative) are ungoverned; withdraws LG-1 as ill-posed once authority is seen as two dimensions (immutable PROVENANCE × act-changed STANDING) in one field; opens LG-2 (should DP-2 be restated to separate derivation from attestation) routed to the ARB.

**Cross-cutting dimension mapping and reproduced conclusions** (4 rows condensed into this theme; source: S0013, S0015, S0016)
Rows 9-12. Restates fifteen candidate concerns including K-7 (authority=provenance×standing, four progression kinds); validates twelve conclusions via independent reproduction; retracts a prior "INTENDED" evidence grade as an overloaded dimension; maps four concerns to dimensions and explicitly names TRUST/PROVENANCE (`authorities.yaml`) as a fifth, already-separated concern the repository had before this commission asked for it.

**Progression mechanisms and the formal PM-1/PM-2 birth** (6 rows condensed into this theme; source: S0018)
Rows 13-18. Concedes LG-1 was solution design (an implementation-gap inference DDD forbids); validates that the DERIVED→REVIEWED→ATTESTED→RELEASED chain describes qualification, not authority; inventories ten progression mechanisms (P-1..P-10) as observations, not assumed lifecycles; the formal-birth row establishing `authorities.yaml`'s five values answer two different questions — provenance (immutable) versus standing (governance-act-mutable) — dissolving rather than answering LG-1; opens PM-1 (should the two questions be separated, superseding withdrawn LG-1) and PM-2 (should standing-changes get role/guard treatment).

**Invariants and the I-4 counterexample reconciliation** (3 rows condensed into this theme; source: S0021, S0022)
Rows 19-21. States eleven invariants including I-4, reclassified as underspecified (holds until attestation) rather than falsified; a counterexample (hospital discharge summaries, signed financial statements) falsifies I-4 as a general rule, then reconciles it within the same document — derived describes provenance, authoritative describes standing, and a signed derivation can be both; opens CV-1 (is I-4 scoped to platform projections, or withdrawn) routed to the ARB.

**Further retraction: mission enacted, not implicit** (1 row condensed into this theme; source: S0032)
Row 22. Withdraws the "INTENDED" evidence grade a second time (intent is not evidence, derived instead as f(governance-status, owner)); supersedes "mission is implicit" with "mission is ENACTED."

**External literature review: PROV-DM, temporal databases, governance lifecycles** (4 rows condensed into this theme; source: S0202)
Rows 23-26. W3C PROV-DM's Entity/Activity/Agent/Derivation/Attribution primitives, with provenance found to be simultaneously governance, trust, and domain information; temporal-database research treating validity-time and recording-time as distinct, requiring temporal+authority+lineage state together ("what was authoritative at time T?"); knowledge-governance research distinguishing lifecycle stages and holding authority is organizational, not epistemic; a second-pass expansion of PROV-DM primitives to nine, plus industry practice treating provenance as a control layer via four checks (source identity/version/retrieval path/answer-alignment).

**Two-dimensional evidence provenance** (1 row condensed into this theme; source: S0451)
Row 27. Formalizes evidence provenance as two-dimensional — Source Provenance (who/when/where/version) versus Acquisition/Epistemic Provenance (why selected, how generated, excluded alternatives) — ruling selection procedure must be treated as epistemic provenance, not mere operational metadata.

**Triangulation contradictions: provenance/lineage/authority-as-trust conflation** (2 rows condensed into this theme; source: S1637)
Rows 28-29. Both rows are typed CONTRADICTION (the mechanical trigger for this label's CONTESTED lifecycle): the corpus never separates provenance from lineage, while mathematics models it as intrinsic and production code independently separates three distinct objects — canonical conclusion CONTRADICTED, requiring a formal split of provenance from lineage and from authority-as-trust.

**Step 265: the formal provenance-placement resolution** (15 rows condensed into this theme; source: S1661)
Rows 30-44. The corpus's single largest connected argument for this label. Frames the governing question (preserving provenance somewhere does not entail it belongs inside K) and tests four candidate placements against eight operational criteria; distinguishes Provenance π (origin) from History(T) (transformation sequence) via a base-case counterexample (K0 has empty History yet its assertions still have external origins), refuting Provenance=History(T) as a universal identity; separately distinguishes a provenance *reference* π from the full provenance *object* P_π (mirroring an established EvidenceReference≠EvidenceObject principle); refutes Candidate B (Pi=History(T)), finds Candidate C (external-audit-only) insufficient alone since Merge could destroy the association without an in-K reference, and refutes Candidate D (provenance-as-context) since context and provenance answer different questions; rates provenance's necessity across eleven operations (concluding it is required especially for Preserve/Merge/Split/Explanation, but this does not force provenance into semantic-equality identity); derives four operation-specific constraints (persist the reference, not necessarily the object; merge/split preserve the reference relationship; replay needs the pair (π₀, History) not History alone); states the major architectural result that preserving provenance does not make semantic equality provenance-sensitive (K1≡K2 can hold under semantic equality with different π, while K1≅_π K2 differs) and that explanation needs both π and History(T) together; formalizes the two-lineage model (Provenance π and History H as complementary, not competing, branches of Assertion A); reaches the smallest-defensible-placement result (ProvenanceReference⊆K, full object held externally); proposes the final architecture Assertion A=(id,P,e,c,t,π) with π resolved via `ResolveProvenance:Π→ProvenanceObject`, distinct from internal History(A); gives seven reasons preferring the reference-based architecture (survives serialization/merge/replay, resolvable on demand, keeps equality flexible, keeps the kernel minimal, gives a clean DDD aggregate/reference boundary); adds an explicit evidentiary-discipline caution against overclaiming (VERIFIED only that provenance cannot universally derive from History(T); DERIVED/RECOMMENDED, not PROVEN, that a stable reference belongs in K); rates all seven candidates in a final placement matrix; and issues the Step 265 verdict "PROVENANCE PLACEMENT SUBSTANTIALLY RESOLVED" (π∈K, full object not required in K, Provenance≠History(T)), setting up Step 266's broader computability audit.

**Precursor open question to Step 265** (1 row condensed into this theme; source: S1663)
Row 45. Poses the exact three-candidate provenance-placement question later resolved by Step 265, already anticipating the t=0 external-source counterexample.

**Computable/non-computable split for equality, identity, and provenance** (1 row condensed into this theme; source: S1664)
Row 46. Splits provenance-reference LOOKUP (computable under three infrastructure assumptions) from ProvenanceTruth (never established by lookup alone) — lookup is not validation.

**Experimental results: placement-candidate scoring and a third provenance-family object** (2 rows condensed into this theme; source: S1666)
Rows 47-48. An executed-computation baseline scores three provenance-placement models against nine scenarios, with Candidate A (provenance inside the assertion/state) scoring 8/9 versus 3/9 for both B and C — a decisive empirical edge; a separate implementation-evidence baseline records a third distinct provenance-related object, EventProvenance, alongside ReplayAssertion and EvidenceSet as real implementation artifacts.

**Post-265 mechanical refutation reason and an unclosed correspondence** (2 rows condensed into this theme; source: S1667, S1668)
Rows 49-50. Gives the precise mechanical reason Candidate B (external map) fails merge/deduplication — it places π outside the content used to compute an assertion's content-addressed id; notes the canonical correspondence between an Assertion's π field and the implementation's provenance reference cannot be closed until the Assertion object itself is implemented.

**A fourth distinct provenance-family object surfaces** (1 row condensed into this theme; source: S1670)
Row 51. Resolves provenance into four distinct objects (not three): AssertionOrigin (π), EvidenceProvenance (chain of custody), History(T) (system transformation record), and a newly surfaced MessageProvenance ((correlationId, causationId), belonging to Integration Events in another bounded context) — four objects, four names, no shared word.

**ARC-A kernel model: provenance as one of twelve core fields** (1 row condensed into this theme; source: S1673)
Row 52. The repaired ARC-A model externalizes measure theory entirely, keeping only a domain-agnostic KOS_core twelve-field tuple that includes Provenance and History as core fields alongside pluggable probabilistic/logical/causal/statistical regimes.

**EXP-2: the decisive qualification of Step 265's placement verdict** (1 row condensed into this theme; source: S1676)
Row 53. Two states (KA, KB) agreeing on every proposition and every proposition's per-assertion provenance, differing only in whether a `derives` relation exists, are still distinguished by `withdraw()` — proving provenance-placement-inside-the-assertion is NOT the component that restores sufficiency; the load-bearing component is instead the relation set R (specifically R_der). This directly qualifies Step 265's provenance-placement conclusion as answering a question orthogonal to the sufficiency question it is usually cited to have resolved.

## Notes for P3
The mechanical CONTESTED flag is triggered by the two explicitly CONTRADICTION-typed S1637 rows (provenance/lineage/authority-as-trust conflation), but the more consequential tension for P3 to examine is the S1661→S1676 relationship: S1661 (Step 265) declares provenance placement "SUBSTANTIALLY RESOLVED," and S1676 (EXP-2, the label's own CANDIDATE-OPERATIONAL-BIRTH row) does not retract that placement conclusion but demonstrates it was answering a narrower question than it is usually cited for — sufficiency for operations like `withdraw()` turns out to depend on the relation set R, not on where provenance itself is placed. This is a genuine, evidenced qualification recorded by the corpus itself (S1676 says so explicitly), not a mechanical artifact — P3 should read S1661 and S1676 together rather than treating either alone as the final word. Separately, this label's own row S1670 records that provenance had by that point been resolved into *four* distinct objects (AssertionOrigin, EvidenceProvenance, History(T), MessageProvenance) sharing no common word — P3 should check whether the working_label `provenance` here is meant to track AssertionOrigin specifically, or the whole four-object family loosely, since the two G0190/G0673 group memberships already flag adjacent, possibly-overlapping labels (`formal-provenance-lineage-audit-graph`, `provenance-ontology-gap`). The G1690 co-occurrence group (5 separate co-occurrences with `canonical-knowledge-state-model-K-AR`) suggests this label is tightly bound to the corpus's canonical K_t formalization work and may be worth prioritizing for that reason alone.
