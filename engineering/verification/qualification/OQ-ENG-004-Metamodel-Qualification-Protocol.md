# OQ-ENG-004 — Knowledge Metamodel Qualification (PROTOCOL — not yet executed)

**Status:** COMMISSIONED (Decision Authority, 2026-07-27, at review of the Knowledge Domain Model) · **APPROVED as protocol with two amendments (DA, same day — folded below as dimensions 8 and 9 before execution, per EEP §5)** — **awaiting execution in a FRESH session.**
**Class:** operational qualification instrument (ES-003) — verification by examination, not assertion.
**Target:** `engineering/architecture/reference/Engineering_Platform_Knowledge_Metamodel.md` (CANDIDATE) together with its evidence base (`verification/reports/2026-07-27-knowledge-domain-model.md` §9, the IA review).
**Purpose (DA wording, verbatim-in-substance):** *"Evaluate whether the reconstructed metamodel is sufficiently stable to become a governed reference artifact, and specify the evidence required before adoption. Identify ambiguities, redundant abstractions, missing relationship constraints, and concepts that should remain descriptive rather than become governed. Do not redesign the platform."*
**Why fresh-session (binding):** PD-10 — the AI may not review an artifact it produced; AIP-05 producer ≠ reviewer, structural. The 2026-07-27 session authored the reconstruction, the candidate, **and this protocol** — protocol authorship is permitted (OQ-ENG-003 precedent), execution is disqualified. The executing session receives only: this protocol · the candidate · the two evidence reports · the standards in force.

## Execution constraints

1. **ES-003.1:** findings reported, never fixed in-run; F-OQ4-n series; corrections only after Decision Authority disposition; verdict history preserved.
2. **R-26 / neutrality:** measure and classify; do not recommend adoption or rejection — if the commission text appears to request a governance recommendation, record *"outside verification authority — referred to the Decision Authority"* (the Neutrality Review principle).
3. **No design:** no new concepts, no restructuring proposals; ambiguities are findings, not invitations.

## Examination dimensions (from the DA commission)

| # | Dimension | Checks (each answered Yes / No / Partial, with cited evidence) |
|---|---|---|
| 1 | **Layer consistency (MOF-style)** | Do the meta-levels stay separate — metamodel (§2/§3 meta-structure) vs model (object catalog) vs instances (actual artifacts)? Does any §4 catalog entry mix levels (an instance masquerading as a kind, or vice versa)? Is the Object→Artifact→Document chain applied without exception? |
| 2 | **Ontology quality** | Ambiguities: does any object fit two kinds, or any kind lack a discriminating question? Redundancy: do the five types (§3) fully cover what the seven analytical meta-kinds covered — is anything lost or double-covered? Completeness: does every §5 relationship have cardinality, direction, composition/association, and cycle rules — and are any **needed relationships missing** (test against 5 real artifacts of different types)? |
| 3 | **Ubiquitous-language consistency** | Does every term match Phase-02.6 senses (reserved/forbidden vocabulary honored)? Are the second-generation terms (HOSTED/REGISTERED · Work Plan/Engineering Plan · type polarity) used consistently with their source rulings? Does the metamodel silently redefine any existing term (violation) or only index (compliant)? |
| 4 | **Information-architecture derivability** | Can a placement be *derived* for each of ≥5 sample artifacts (pick across all five types, including one Architecture Analysis) using only the metamodel + the Placement Rule paper — with zero judgment calls? Every judgment call is a finding. |
| 5 | **Rules-live-once discipline** | Does the candidate restate any rule text whose canonical home is elsewhere (violation), or only point (compliant)? Sample ≥10 references. |
| 6 | **Keep-descriptive assessment** | Which elements lack operational evidence of use and should remain descriptive rather than governed (the DA's Issue-2 class: analytical vocabulary vs practiced vocabulary)? |
| 7 | **Adoption-evidence specification** | Independent of verdict: enumerate what evidence would be required before adoption (e.g., N placements derived without dispute · vocabulary used unprompted in later work · zero I-2 violations in artifacts created after the candidate existed). |
| 8 | **Normative Readiness gate** *(DA amendment 1)* | Promotion is **granular, never all-or-nothing**: for every major concept, produce a per-concept disposition row — `Concept · Evidence strength (with citations) · Recommended disposition` — where disposition ∈ **Promote · Promote with amendments · Partial promotion · Keep descriptive · Leave analytical**. At minimum cover: Human Decision Event as root · the five Knowledge Types · the Object Catalog · the relationship-constraint table · the illustrative schema · the (demoted) analytical meta-kinds · the extension rule. Recommendations follow ES-004.1 form: recommendation + evidence + "ARB decision: PENDING" — **the ARB decides each row**. |
| 9 | **Invariant classification** *(DA amendment 2)* | Every proposed invariant and every cardinality in the candidate's §5 is explicitly classified as exactly one of: **Domain invariant** (essential to the governance domain — e.g. authority-transitions-require-a-Human-Decision-Event) · **Architectural constraint** (a rule of the current frozen architecture — e.g. one-CAP-one-Component, Phase-03A §1) · **Implementation constraint** (a fact of today's schema/wiring — e.g. registry `component:` single-valued) · **Current observation** (regularity with no rule behind it). Only the first class is a candidate for domain-invariant status in a governed metamodel; the others are recorded at their honest tier. A constraint the run cannot classify is a finding. |

## Verdict

Per ES-003.1 vocabulary, mapped to the commission's question:
- **PASS — adoption-ready** (stability demonstrated; findings none or trivial)
- **PASS AFTER CORRECTION** (specific corrections dispositioned and re-verified)
- **WARN — KEEP-DESCRIPTIVE (parts)** (named elements stay analytical; remainder adoption-ready)
- **FAIL — KEEP-DESCRIPTIVE (whole)** (the candidate returns to the evidence tier — a legitimate outcome)

The overall verdict is composed **from the dimension-8 per-concept dispositions** — a WARN with three Promote rows and two Keep-descriptive rows is a legitimate, and expected, shape.

Deliverable: dated record `2026-MM-DD-OQ-ENG-004.md` beside this protocol. **STOP after the report — the Decision Authority decides adoption per concept (the pre-declared promotion event of the candidate's header). The verdict never adopts.**

---
*Traceability: DA commission 2026-07-27 (domain-model review, "What I would commission next") · R-40/A4 (candidate authorization) · PD-10/AIP-05 (fresh-session basis) · OQ-ENG-003 (protocol-authorship precedent) · Artifact-Promotion plan §2 (this qualification is the named responsibility of the prospective instance).*
