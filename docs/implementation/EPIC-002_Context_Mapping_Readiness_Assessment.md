# EPIC-002 Context Mapping Readiness Assessment

**Kind:** governance-readiness artifact — determines whether the discovered domain knowledge is stable enough to represent architecturally. **Authority:** generated; the readiness decision itself is the ARB's.
**Methodology note (per ARB refinement, 2026-07-25):** Domain Collaboration Discovery is hereby classified as a **preparation activity inside Context Mapping**, not a permanent named lifecycle phase — its report stands as input to this assessment, and the phase list does not grow.
**Inputs (only):** `EPIC-002_Canonical_Domain_Model_Decision.md`, `EPIC-002_Domain_Collaboration_Discovery.md`, and the previously accepted EPIC-002 Strategic DDD artifacts. No new literature, no implementation assumptions.
**Explicitly out of scope:** producing a Context Map; assigning any relationship pattern (ACL/Partnership/Shared Kernel/Published Language/Customer-Supplier); defining APIs, events, aggregates, repositories, services; Tactical DDD.

---

## 1. Context Mapping Readiness Assessment (boundary stability — Step 1)

| Candidate Domain Boundary | Stability | Basis |
|---|---|---|
| CB-1 Collection & Aggregation | **Provisional** | Content stable (broadest evidence, cleanest dependency, COL-1 fully stable); *placement* open via the merger question — and that question is coupled to the custody decision, so it cannot resolve alone |
| CB-2 Custodial Integrity / CB-2-Alt Self-Verifying Integrity | **Unresolved** | The T1 contest is undecided; what the boundary *is* — not merely what it's called — differs between the alternatives. Critically, the Domain Decomposition Evaluation already established this cannot be closed by further evidence (no additional literature is authorized, and the literature itself is split): resolution requires an **architectural ruling**, not more analysis |
| CB-3 Adjudication | **Stable, with a non-blocking internal open question** | The strongest decision-owning candidate in the program; its core (COL-1/COL-2/COL-5 consumer, D1 owner) holds identically in every scenario. The CB-3-Alt split question is internal and representable as an annotation (see Step 3) |
| CB-4 Contemporaneous Record-Fixing | **Dependent** | Depends on the merger question AND, via the COL-4 fusion finding, on the custody decision — the most decision-entangled position on the board |

## 2. Deferred Decision Impact Matrix (Step 3)

| Decision | Blocks Context Mapping? | Why |
|---|---|---|
| CB-2 vs. CB-2-Alt (custody) | **Yes — blocks a canonical map; does not block scenario-based mapping** | COL-3 and COL-4's *content* (what actually crosses: custody attestation vs. public-verifiability result) differs by scenario. A single map would have to either pick a side nobody has ruled on, or draw one boundary containing two mutually exclusive designs — the exact mistake for which Model A was rejected, reproduced at the map level |
| CB-1 / CB-4 merger | **Partial — blocks a canonical map, via coupling** | On its own this only decides whether COL-2 is internal or crossing — mappable either way with a note. But the COL-4 fusion finding couples it to the custody decision (in the self-verifying scenario, record-fixing and integrity-onset merge into one business act), so it cannot be finalized before, or separately from, the custody ruling |
| CB-3 vs. CB-3-Alt (authority-validity) | **No** | COL-6 exists only in the split scenario, rests on the thinnest evidence in the program, and — decisively for mapping purposes — its presence or absence changes no *other* collaboration except a conditional bifurcation of COL-5. It is representable on any map as an explicit annotation on CB-3 ("internal open question: authority-validity split, evidence thin") without falsifying anything |

**Net:** one decision fully blocks canonical mapping, one blocks it via coupling, one blocks nothing. The two blockers are the same coupled pair the Domain Collaboration Discovery identified — this matrix independently confirms that coupling from the mapping-impact direction.

## 3. Scenario Evaluation (Step 2)

| Scenario | Viable to map? | Assessment |
|---|---|---|
| A — Custodial (CB-2) | **Yes** | Fully specified: all six collaborations take determinate form; boundaries distinct |
| B — Self-verifying (CB-2-Alt) | **Yes** | Fully specified, but with a different topology: COL-4 partially fuses record-fixing with integrity-onset, reshaping the CB-1/CB-4 area — mapping it will make the coupling's consequences *visible*, which is precisely its value |
| C — Hybrid (both mechanisms, per evidentiary stream) | **Yes — and must be included** | This is the pattern real deployments in the evidence base actually exhibit (STAR-Vote, Wombat); the most collaboration-complex scenario, and excluding it would map only theory while ignoring observed practice |
| D — Authority-validity split | **No separate map** | Handled as an annotation on CB-3 in every map (per Step 3's non-blocking finding); a fourth map would grant one thin, jurisdiction-specific candidate the same standing as evidence-backed scenarios |

## 4. Recommended Mapping Strategy (Step 4)

**Outcome 2 — Scenario-based Context Mapping**, with a deliberately narrow purpose and a built-in sunset:

- **Three candidate maps (Scenarios A, B, C), explicitly comparative** — separate maps per scenario, not one map containing competing realities. This honors the caution that a Context Map is an architectural representation: each map represents *one* coherent possible reality, and the set exists for comparison.
- **Purpose: decision-support for the coupled custody + merger ruling — not architectural commitment.** The custody question cannot be closed by further evidence (Step 1); it requires an architectural ruling. The single best input the ARB could have for that ruling is seeing what each choice *does* — which boundaries fuse, which collaborations change content, what the hybrid actually costs in coordination. Comparative candidate maps are that input. This inverts the apparent dependency: rather than the custody ruling blocking mapping, bounded comparative mapping *serves* the custody ruling.
- **Sunset clause:** the scenario maps are decision-support artifacts. Once the ARB rules on the coupled pair, exactly **one canonical Context Map** is produced and the scenario maps are archived as history — they must not persist as parallel semi-authoritative representations.
- **CB-3-Alt** carried on all three maps as an annotation, per Step 3.

**Why not Outcome 1 (canonical now):** it would require silently pre-deciding the custody question — repeating Model A's rejected hiding-the-tension mistake in a new artifact.
**Why not Outcome 3 (defer):** deferral implies waiting for something. Nothing is coming — no further literature is authorized, the evidence is split, and the open items are architectural. Deferring mapping would simply stall the program in front of a decision that only an ARB ruling can make, while withholding from the ARB the comparative material that would best inform that ruling.

## 5. Architectural Risks

- **Of the recommended path:** (1) effort multiplication — three maps instead of one; bounded by the narrow purpose and sunset clause. (2) Scenario ossification — candidate maps acquiring false authority over time; mitigated by labeling every scenario map "comparative decision-support — not canonical" and by the sunset clause. (3) Comparison bias — the act of mapping may make the simplest scenario *look* best regardless of domain fit; the ARB should weigh domain evidence (including that hybrid is what real deployments do) above map elegance.
- **Of canonical-now (rejected):** silently resolving T1 without a ruling; structural lock-in on an unruled question.
- **Of deferral (rejected):** program stall with no resolution mechanism; the deferred items do not ripen on their own.

## 6. ARB Recommendation

**Readiness verdict: READY WITH CONSTRAINTS.**

Recommended authorization: **scenario-based comparative Context Mapping (Scenarios A, B, C)** as decision-support for a single subsequent ARB ruling on the coupled custody + merger pair, with the CB-3-Alt annotation carried on all maps, the sunset clause binding (one canonical map after the ruling; scenario maps archived), and relationship-pattern selection remaining unauthorized until the canonical map exists.

This is a recommendation; the decision — including whether to instead rule on the coupled pair first and skip straight to a canonical map — is the ARB's.

---

**Stop condition:** this assessment recommends; it does not map. **STOP.** No Context Map (canonical or scenario), no relationship pattern, no tactical design is authorized until the ARB rules on the mapping strategy.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Canonical_Domain_Model_Decision.md`, `EPIC-002_Domain_Collaboration_Discovery.md`, and the prior accepted EPIC-002 Strategic DDD artifacts · No new sources consulted.*
