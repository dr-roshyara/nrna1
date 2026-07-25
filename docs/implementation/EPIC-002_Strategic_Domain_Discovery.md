# EPIC-002 Strategic Domain Discovery Report

**Kind:** discovery artifact — the responsibility-analysis bridge between the Cross-Disciplinary Evidence Consolidation Report and Strategic DDD/bounded-context discovery. **Authority:** generated; never authoritative without ARB review.
**ARB ruling authorizing this report (2026-07-25):** the Cross-Disciplinary Evidence Consolidation Report is accepted as the final research synthesis artifact; no additional literature collection is authorized absent a future architectural decision demonstrating genuine evidence deficiency; research is now an input, not the primary activity. This report performs Strategic Domain Discovery — **not** Strategic DDD's bounded-context step itself, per the inserted layer: Research → Evidence → **Responsibilities** → Strategic DDD → Bounded Context Discovery.
**Scope discipline (binding on this document):** uses ONLY the Cross-Disciplinary Evidence Consolidation Report and the underlying Literature Review/Concept Register — no new sources. **Does NOT** create bounded contexts, aggregates, repositories, APIs, events, Context Maps, or IDD content, and does NOT assign ownership of any responsibility to any actor, team, or system component. Responsibilities and clusters below are *discovered* (observed in the literature), not *assigned*.

---

## 1. Strategic Ubiquitous Language

Each term: definition/meaning, synonyms and conflicting terminology, source disciplines, confidence. **Not classified into bounded contexts.**

| Concept | Definition | Synonyms / conflicting terminology | Source disciplines | Confidence |
|---|---|---|---|---|
| **Evidence (plural)** | Qualitatively heterogeneous material from which a decision, tally, or model is constructed; not reducible to one kind or channel | "evidence-family," "raw material" (Evans), "evidence types" (SLR) — conflicts with "stakeholder plurality," a distinct claim DDD literature sometimes conflates with it | ES, AL/CL, PR, TE, DF, E2E-VV, GT, DDD (8 families) | HIGH |
| **Adjudication / Correction** | Resolving contradictory, incomplete, or contested evidence into a determination; consistently non-automatic wherever examined | "knowledge crunching" (Evans), "reconciliation," "curing/Heilung" (AL Germany), "declare-failure" (ES), "remediation" (audit governance) | ES, AL, PR, E2E-VV, GT, DDD (**8 families, tied with Evidence — corrected from an earlier 11; see `EPIC-002_Evidence_Family_Independence_Audit.md`**) | HIGH |
| **Contemporaneity** | The record or its justification must be fixed at the time of the act, not reconstructed afterward | "commit-before-sample" (ES), "no post-hoc rationalization"/Chenery-hard-look (AL/CL) — contested against EventStorming's retrospective narrative-building (DDD), genuinely unresolved whether that counts as a violation or a different kind of artifact | ES, AL/CL, E2E-VV, GT, DDD (3 families) | MEDIUM, contested |
| **Evidence-substrate / Argument separability** | Whether raw evidentiary material can be kept conceptually and structurally distinct from the interpretive judgment built on it | "case vs. evidence" (TE), "no-canonical-verifier" (E2E-VV), "model as substrate of domain knowledge" (Fowler/Evans) — DDD's Ubiquitous-Language-fusion framing was tested as a direct challenge and refuted | PR, TE, DF (hedged), E2E-VV, GT, DDD (5 families +1 hedged) | LOW, thin and contested |
| **Custody / Chain of custody** | A maintained, attributable, tamper-evident possession or verification trail over evidentiary material | "custody log" (NIST/DF), "physical/procedural custody" (ElectionGuard pilot) — directly disputed against "public self-verification replaces custody" (Ali & Murray, E2E-VV); the single most contested term in the whole vocabulary | ES, DF, E2E-VV (CONTESTED); no engagement in GT or DDD | LOW / CONTESTED |
| **Custody-as-precondition** | Certification presupposes an already-established custody trail rather than being independent of it | "certification over an unestablished trail = theater" (ES/DF) | ES, DF (2 families) | LOW, not falsification-tested |
| **Detection vs. prevention** | A system property that reveals tampering after the fact is distinct from one that prevents tampering | "software independence" (ES), "tamper-evidence" (NIST/DF) | ES, DF (2 families) | LOW, not falsification-tested |
| **Finality vs. immutability** | A decision/record can be procedurally final (no longer challengeable) without being literally unchangeable | Bestandskraft (AL Germany) | AL (1 family) | LOW, single-discipline |
| **Differentiated scrutiny** | The kind/degree of review applied varies by evidence or decision type, rather than one uniform standard | standard-of-review taxonomy (AL/CL) | AL/CL (1 family, jurisdiction-specific instrument; underlying idea flagged as a candidate echo of evidence plurality) | LOW, single-discipline |

## 2. Responsibility Catalogue (discovered, not assigned)

**Responsibilities** — an activity the literature shows must be performed by *some* actor or mechanism:
- **R1 — Gather/aggregate evidence** from heterogeneous, non-interchangeable sources (P1).
- **R2 — Adjudicate/resolve** contradictory or incomplete evidence into a determination (P2) — consistently non-automatic.
- **R3 — Maintain (or explicitly forgo) a custody trail** over evidentiary material (P5) — contested whether public self-verification can discharge this responsibility instead.
- **R4 — Preserve contemporaneity** of the record or its justification, where required (P3) — unresolved as a responsibility-question (who must ensure it, how), not merely a property-question.
- **R5 — Keep evidentiary material distinguishable from the argument** built on it, where feasible (P4) — feasibility itself is contested (collapses under compromise).

**Decisions** — a point in the literature requiring a determination:
- **D1** — Does a given correction/adjudication mechanism count as automatic, or does it require human/institutional judgment? (Both open P2 categorization questions — legislative sunset/expiry, algorithmic clustering — live here.)
- **D2** — Is a given retrospective reconstruction a violation of contemporaneity, or a different kind of artifact entirely (model vs. record)? (The P3 tension.)
- **D3** — Is custody preserved, displaced, or split for a given evidentiary stream? (The P5 contest.)

**Capabilities** — a recurring kind of function the literature describes, independent of who performs it:
- **C1** — Multi-kind evidence collection/aggregation.
- **C2** — Human/institutional adjudication of contested evidence.
- **C3** — Custody/possession tracking with tamper-evidence.
- **C4** — Contemporaneous record-fixing at time of act.
- **C5** — Separation-preserving representation of evidence vs. interpretation, where feasible.

**Constraints** — a limit the literature repeatedly documents:
- **K1** — Adjudication cannot be fully automated without a confirmed counterexample anywhere in the program; no discipline examined shows a clean exception.
- **K2** — Evidence-argument separability is not guaranteed under adversarial/compromised conditions — a design aspiration, not a guaranteed property.
- **K3** — Custody and self-verification are not proven interchangeable; treating them as equivalent is not supported by the evidence.
- **K4** — A large share of at least one examined discipline's own practice (17/36 DDD-effectiveness studies) proceeds with no operationalized evidence metric at all — plurality-in-principle does not guarantee plurality-in-practice.

## 3. Responsibility Relationship Matrix

*(Relationships as discovered in the literature's own treatment — not an architecture, not a Context Map.)*

| From | Relationship | To | Basis |
|---|---|---|---|
| R2 (adjudicate) | **depends on** | R1 (gather) | Adjudication cannot occur without prior aggregated evidence — consistent across every discipline examined |
| R2 | **constrains** | D1 (automatic?) | Every attempt to resolve D1 toward "automatic" has failed verification; D1 stays open precisely because R2's non-automaticity is so robust |
| R3 (custody) | **is the same open question as** | D3 (preserved/displaced?) | The custody responsibility's contested status IS D3, viewed from two angles |
| R4 (contemporaneity) | **preserves the basis that** | R2 **consumes** | A contemporaneously-fixed record is what later adjudication acts upon; if R4 fails, R2 operates on a reconstruction rather than the original — this is exactly what D2 asks |
| K2 | **challenges** | R5 (separability) | The Helios rootkit finding shows R5 can collapse under adversarial conditions; R5 is an aspiration bounded by K2, not a guarantee |
| C1 (collection) | **produces input consumed by** | C2 (adjudication) | Every discipline that names both treats collection as prior to adjudication |
| C3 (custody) | **validates** | what C1 produced, before C2 consumes it | In every discipline that names custody explicitly (ES, DF), it sits structurally between collection and adjudication |
| C4 (contemporaneous fixing) | **influences whether** | C3 (custody) **is meaningful at all** | Custody of a record never contemporaneously fixed is a materially different concern than custody of one that was — a discovered pattern, not resolved here |
| K1 | **constrains** | D1 and C2 | No cluster may assume adjudication is delegable to automation |
| K3 | **constrains** | D3 and C3 | No cluster may assume custody and self-verification are interchangeable |
| K4 | **challenges** | C1 | "Evidence collection" as described is aspirational in a documented share of practice, not universally realized |

## 4. Domain Tension Register (unresolved — not to be collapsed here)

| ID | Tension | Status |
|---|---|---|
| T1 | Custody vs. self-verification | CONTESTED — the single most informative unresolved item in the whole program |
| T2 | Evidence/argument separability vs. demonstrated collapse under compromise | Aspiration vs. attack-scenario evidence (Helios rootkit) |
| T3 | Automation vs. adjudication | Robust against every *confirmed* counterexample, but two candidate boundary cases remain uncategorized (legislative sunset/expiry; algorithmic clustering) |
| T4 | Contemporaneity vs. legitimate reconstruction | Genuinely bidirectional — attacked from both sides, resolved in neither direction |
| T5 | Plurality-in-principle vs. operationalization-in-practice | 17/36 DDD-effectiveness studies report no concrete evaluation metric; plurality is asserted more often than measured |

## 5. Candidate Capability Clusters (observed co-occurrence, not bounded contexts)

*Grouping reflects which responsibilities/capabilities the source literature itself repeatedly discusses together — not an architectural judgment about how software should be structured.*

- **Cluster A — Collection & Aggregation:** R1/C1, consistently paired with K4 across every discipline that discusses evidence production (ES, PR, TE, DDD/SLR) — sources that describe gathering evidence also, in the same breath, discuss the difficulty of actually measuring it.
- **Cluster B — Adjudication & Correction:** R2/C2/D1/K1 — a robustly and tightly co-occurring cluster (8 independent families, tied with Cluster A — corrected from an earlier 11; see `EPIC-002_Evidence_Family_Independence_Audit.md`); every discipline naming an adjudication responsibility also discusses its non-automatic nature and what, if anything, could ever automate it.
- **Cluster C — Custody & Integrity:** R3/C3/D3/K3/T1 — every discipline naming custody either takes a side on, or actively contests, the self-verification question; never discussed independently of that tension in the sources reviewed.
- **Cluster D — Contemporaneity & Record-Fixing:** R4/C4/D2/T4 — disciplines discussing contemporaneity (AL/CL, ES, DDD) consistently frame it alongside the retrospective-reconstruction question, never as a standalone property.
- **Cluster E — Substrate/Argument Separation:** R5/C5/K2/T2 — every discipline raising evidence/argument separability immediately also raises the conditions under which it fails.

**Observed cross-cluster echo (not resolved, not an architecture claim):** Clusters C and E share a structural pattern — both concern whether a designed separation (custody-from-tampering; evidence-from-argument) holds under adversarial or compromised conditions, and both are the weakest, most contested clusters in the program. Whether this echo reflects one underlying concern or two genuinely distinct ones is explicitly **not resolved here** — it is handed to Strategic DDD as a discovered observation.

## 6. Strategic Discovery Summary

- 9 ubiquitous-language terms discovered; 2 in active dispute (custody; evidence-substrate/argument separability).
- 5 responsibilities, 3 decisions, 5 capabilities, 4 constraints catalogued — descriptively, with no ownership assigned to any actor or system.
- 5 candidate capability clusters observed by literature co-occurrence, not architectural judgment; none named as a bounded context or software component.
- 5 domain tensions carried forward unresolved, as inputs to (not conclusions of) Strategic DDD.
- Cluster B (Adjudication) is the most robustly evidenced cluster in the program. Clusters C (Custody) and E (Separability) are the least resolved and share a structural echo warranting explicit ARB attention when Strategic DDD begins.
- **Advisory-only observation:** this discovery layer, like the consolidation before it, does not determine how many bounded contexts should exist or who should own what. It surfaces the responsibility shape the literature supports. That determination belongs to Strategic DDD, next — and only after ARB authorization.

---

**Stop condition (per instruction):** this report is the deliverable. No Context Map, bounded-context evaluation, tactical design, ownership assignment, or implementation guidance is authorized by this document. **STOP for ARB review** — the ARB decides whether the discovered capability clusters are sufficiently stable to begin bounded-context discovery.

---
*Charter: `EPIC-002_Problem_Statement.md` · Input: `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md` (and, transitively, `EPIC-002_Literature_Review.md` / `EPIC-002_Concept_Register.md`) · No new sources consulted in producing this report.*
