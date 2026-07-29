# PKS Phase II — M1: Concept Register & Candidate Validation Report

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M1) — the canonical PKS Concept Register. **This is where validation happens** (M0's glossary observed; this register evaluates). No bounded context, no architecture, no implementation. |
| **Authority** | Generated — never authoritative without human review. Verdicts below are the register's recommendations; the checkpoint review disposes. |
| **Status** | **PRODUCED — checkpoint review round complete (PA, 2026-07-28: approve-with-refinements; all four requested refinements + two body-level suggestions applied same date).** M2 opens only on explicit confirmation. |
| **Commission** | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) + the PA's M0+M1 execution commission. |
| **Method** | Per concept: origin · supporting · contradicting evidence → portability filter (could another product instantiate this concept without this repository's conventions? — ES-005.3 generalized per the accepted review) → verdict **validated / rejected / deferred**, with confidence per the M0 rubric, assumptions, and unresolved questions. Rejected concepts remain in the register with rationale — lineage is never lost. |
| **Placement** | `docs/implementation/`, beside M0 and the accepted Phase-I inputs. |

---

### Portability rubric

*(Formal definitions so the judgments are reproducible; applied in every verdict below.)*

| Grade | Definition |
|---|---|
| **Essential** | Could exist in any Product Knowledge System — removing it leaves the domain unable to perform a discovered purpose (P-1..P-9) |
| **Portable** | Could exist in many knowledge systems; not tied to this repository's practices, but not implied by every PKS |
| **Local** | Repository-specific — meaningful only inside this project's conventions |
| **Convention** | Implementation-specific serialization or naming of a portable concept (register files, id schemes, ladders) — the concept ports, the convention does not |

## Part A — Register of observed concepts (K-1..K-17 evaluated)

The Discovery methodology admitted only concepts satisfying **its** operating-evidence criteria (identity + instances + independent citation). **M1 independently evaluates each one** under portability analysis and contradiction review — Discovery's admission confers no M1 verdict, and Discovery and Validation remain independent activities. 17 concepts were evaluated; the verdicts follow.

| Concept (G/K) | Verdict | Supporting evidence | Contradicting evidence | Portability verdict | Conf. | Unresolved → routed |
|---|---|---|---|---|---|---|
| Decision (G-1/K-1) | **VALIDATED** | 23 ADR-T + 14 D-nn independently cited/superseded/gated; §1.3 ratio | 5 colliding id spaces (identity, not concept) | Essential — concept portable; register serialization local | High | Identity scheme → M4 (OQ-1) |
| Rule (G-2/K-2) | **VALIDATED** | ES/ER/EP registers; "lives once" consolidation precedent | none | Essential; pointer discipline local | High | — |
| Invariant (G-3/K-3) | **VALIDATED** | CI/BI/INV instances across blueprints and charters | corpus does not separate from Constraint | Essential | High | Distinctness from Constraint → M2 (C-3) |
| Ruling (G-4/K-4) | **VALIDATED** | ~40+ ARB documents; R-nn register rows; decision columns | grain boundary vs Decision fuzzy | Portable (authority acts are universal); register form local | Medium | Grain relation Ruling↔Decision → M4 |
| Finding (G-5/K-5) | **VALIDATED** | ≥6 operating F-spaces across audits/IDDs | run-scoped ids collide | Portable; scoping convention local | High | Id scoping → M4 (OQ-1) |
| Question (G-6/K-6) | **VALIDATED** | OQ/Q/D3x series; Q-1/Q-2 have own resolution documents | none | Essential | High | — |
| Term (G-7/K-7) | **VALIDATED** | ADR-UL governs term change; ER-06 ordering | none | Essential | High | *(Candidate "Definition" resolves here — Part B)* |
| Contract (G-8/K-8) | **VALIDATED — NARROWED** | Canonical Event Catalog rows + SchemaVersion; Round50 payload contracts; rows version independently of carrier | **no non-event contract instance measured** — the generalized form (APIs, component interfaces) is unevidenced product-side | Portable as concept; validated scope = **event contract**; broader scope = recorded extension hypothesis, not validated | Medium-High (High within event scope) | Insufficient-evidence instance recorded, §C.3 |
| Model element (G-9/K-9) | **VALIDATED** | EPIC-004 chain freezes element sets; later artifacts cite individual elements | none on existence | Portable; the enumeration is cited corpus vocabulary | Medium | One-concept-or-family grain → M4/M6 |
| Observation (G-10/K-10) | **VALIDATED** | O-n inputs; hypothesis/assumption registers with per-entry statuses | none | Essential | High | Ingestion granularity → M4 (OQ-CM-1, **alive** — its underlying candidate survived) |
| Risk (G-11/K-11) | **VALIDATED** | R-1..R-7 register; residual risks per report | R-nn id collision with Ruling | Portable | Medium-High | Id collision → M4 (OQ-1) |
| Candidate (G-12/K-12) | **VALIDATED** | P-n dispositions; "recorded, not adopted" headers; promotion-by-human-act precedents | single-corpus evidence for generality | Portable (probationary status universal); the ladder local | Medium | — |
| Work item (G-13/K-13) | **VALIDATED — boundary-flagged** | PB/EPIC series; lifecycle≠progress rule | none on existence | Portable — **but possibly an adjacent work-management domain, not PKS core** | Medium | Domain membership → M5 (explicitly not decided here) |
| Guide step (G-14/K-14) | **VALIDATED** | 6 areas operate the convention; DoD standing rule | 25/31 areas non-conformant (conformance, not existence) | Portable | Medium | Conformance datum → feeds the Conformance work (M3) as evidence |
| Verdict (G-15/K-15) | **VALIDATED** | per-run outcome tokens; CERTIFIED; reviewer-not-author discipline | none on the concept | Essential | High | "Qualification" dual meaning → **SURFACED (OQ-11, Part D)**; Verdict-vs-Observation aggregation → M4 (OQ-CM-1) |
| Exception record (G-16/K-16) | **VALIDATED** | exceptions.json with approvers/expiry schema; EP-02 deviation records | expiry mechanism unused (`expires: null`) | Portable | Medium | Unused-expiry = operating-maturity datum → Capabilities Pass scope (non-gating) |
| Charter grant (G-17/K-17) | **VALIDATED** | authorization-chain headers; "authorizes planning, not the architecture" | none | Essential | High | — |

**Filter-discrimination note for Part A (honesty requirement):** the portability filter rejected **no whole concept** here — expected, since item 1 pre-filtered to operating concepts. Its discriminating work in Part A is the **essence/convention split** recorded per row (concept portable · serialization/id-scheme/ladder local). The outright rejections appear in Part B, and are produced by the **operating-evidence test**, not the portability filter. The two instruments discriminate on different axes; conflating their contributions would overstate the filter.

## Part B — Candidate dispositions (item 2's hypothesis set + adjacent candidates)

| Candidate | Verdict | Basis (origin → supporting/contradicting) | Conf. |
|---|---|---|---|
| Decision (primitive) | **VALIDATED** = G-1/K-1 | Direct match to strongest observed concept | High |
| Definition (primitive) | **Observed concept Term (G-7/K-7) retained; candidate label "Definition" NOT adopted** | Same concept; observed vocabulary wins per evidence-first UL; the candidate label is recorded as a synonym, nothing renamed | High |
| Invariant (primitive) | **VALIDATED** = G-3/K-3 | Direct match | High |
| Observation (primitive) | **VALIDATED** = G-10/K-10 | Direct match | High |
| Verdict (primitive) | **VALIDATED** = G-15/K-15 | Direct match | High |
| Contract (primitive) | **VALIDATED — NARROWED** to event-contract scope | Origin: item 2 §1.2 (generalized). Supporting: K-8 (event contracts operate). Contradicting: no non-event instance measured | Medium-High |
| Constraint (primitive) | **DEFERRED → M2 (C-3)** | The corpus does not separate Constraint from Rule/Invariant; resolving that is M2's commissioned work — deciding here would pre-empt the WBS | — |
| ADR (composite) | **VALIDATED as a projection pattern** — not a new concept | Supporting: decision-log schema {Decision·Reason·Alternative rejected·Impact} matches the proposed composition; D-12 split precedent; item 1's central artifacts-are-projections finding. A composite *is* a projection — the term deliberately reuses Discovery's dominant abstraction rather than introducing "packaging" | High |
| Policy (composite) | **DEFERRED → M2** | Composition includes Constraint (C-3-coupled); an operating packaging family exists (process document packs rules) but the composition cannot be fixed until C-3 resolves | — |
| Component Specification (composite) | **REJECTED as a distinct named projection** | No operating instance under that name product-side; the observed normative-specification family (blueprints · state machines · catalogs — item 1 family B) already carries the content; adopting an unevidenced name fails evidence-first UL. *Rejection = not adopted now; the content class exists under observed names. Preserved with this rationale* | Medium-High |
| Runbook (from EKP ontology / item 1 §1.2-negative) | **REJECTED for now** | Declared in the EKP ontology with **zero instances** — specified-but-hypothetical; no operating evidence to validate against | High (that evidence is absent) |
| Context Package (item 1 §1.2-negative) | **REJECTED as first-class concept** | Item 1's own assessment: exists (2 instances) as an *assembly of references* — a projection over concepts, not a concept | High |
| Requirement (item 2 relationship diagram node) | **DEFERRED → M2 (C-1)** | The explicit trichotomy (missing / represented otherwise / outside boundary) is M2's commissioned work | — |

## Part C — Validation report

1. **Counts.** Observed concepts: **17 evaluated → 17 validated** (1 narrowed, 1 boundary-flagged) — evaluation preceded every verdict; nothing was validated by admission. Candidates: **13 evaluated → 7 validated** (incl. 1 label-not-adopted, 1 narrowed, 1 as-projection-pattern) · **3 deferred to M2** (Constraint, Policy, Requirement — all to already-commissioned collision work, not parked) · **3 rejected with preserved rationale** (Component Specification as named, Runbook, Context Package).
2. **The instruments demonstrably discriminate** — the M1 acceptance criterion is met, with the Part-A honesty note standing: rejections came from the operating-evidence test; the portability filter's discrimination is the essence/convention split, visible in every Part-A row. Neither instrument was ceremonial; neither did the other's work.
3. **Insufficient-evidence instance (per the commission's evidence rule):** whether Contract generalizes beyond event contracts cannot be answered from the accepted evidence. Recorded; **additional research NOT recommended now** — the commissioned (non-gating) Capabilities Pass will classify contract-mechanism operational maturity if scheduled, which is the cheaper instrument. Work continued unblocked via the narrowed verdict.
4. **Relationships:** recorded per concept only as observed facts (supersedes, evaluates, authorizes — item 1 Q3); **relationship modeling is M7's work and none was performed here.** The polarity distinction was used as observed evidence only (OQ-4 untouched).

## Part D — Surfacing Register (governance questions encountered; none resolved)

| # | Question | Where it bit | Modeling impact | Status |
|---|---|---|---|---|
| S-1 | **OQ-PKS-11** — is "Qualification" one concept or two (verification instrument vs promotion-ladder stage)? | G-15/Verdict and G-12/Candidate entries — the glossary cannot canonicalize the term | Glossary carries both senses provisionally; no M1 verdict depends on the answer; **UL canonicalization blocked for this one term only** | **SURFACED to ARB** (per rulings §3); all independent work continued |
| S-2 | **OQ-PKS-9** — which lifecycle vocabulary governs which object kind? | M0 glossary status semantics — every entry has a lifecycle, none could be named | M0/M1 deliberately record **no lifecycle vocabulary**; M4 models identity/lifecycle *structure* while the kind→vocabulary governance mapping stays ARB-owned | **SURFACED to ARB**; work continued |
| — | OQ-PKS-2 (incumbent) · OQ-PKS-7 (three roots) | **Did not bite in M0/M1** — as the plan predicted (expected at M6) | — | Not surfaced; prediction on record |

## Part E — Success-criteria self-check (commission terms)

Every validated concept evidence-backed with citations ✅ · every rejected concept carries documented rationale and remains in the register ✅ · every deferred concept names the WP that owns it ✅ · no architectural decision made ✅ · no governance question answered by modeling (two surfaced instead) ✅ · no software design ✅ · uncertainty preserved explicitly where it exists (narrowed Contract, boundary-flagged Work item, grain-open Model element) rather than replaced by invented certainty ✅.

## Appendix — Methodological Threats to Validity

*(The epistemic limits of what M1 can legitimately claim; per checkpoint refinement.)*

| # | Threat | Mitigation | Residual risk |
|---|---|---|---|
| T-1 | **Single-repository corpus** — all evidence comes from one project | Portability filter with formal rubric (essence/convention split per row) | External generality remains unknown until a second corpus is evaluated |
| T-2 | **Evaluator non-independence** — M1 was performed by the same agent lineage that produced Discovery and the packaging | Every verdict carries citations re-checkable by an independent reader; checkpoint review by a human reviewer | Shared blind spots possible; the standing of second-model reviews that could offset this is itself open (OQ-PKS-10, ARB-owned) |
| T-3 | **Portability judgments remain analyst judgment**, rubric notwithstanding | Rubric + per-row rationale make each judgment inspectable | Reproducibility unverified until an independent re-run of the filter returns the same grades |
| T-4 | **Discovery-admission coupling** — Part A evaluates only what Discovery admitted | Independence language (Part A preamble); Part B evaluates candidates from outside the Discovery set | Concepts Discovery missed are invisible to M1 — unknown unknowns are not addressed by re-evaluating known knowns |
| T-5 | **Single-rater confidence** | Confidence assigned strictly per the M0 rubric | Inter-rater reliability unknown; a second rater has never applied the rubric |

---

*Traceability: executes WP M1 of `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) · inputs: item 1 §1.2/§1.3/Q3–Q6 (evidence baseline) · item 2 §1.2 (hypothesis set) · synthesis §3–§4 (collisions, filter) · M0 glossary + confidence rubric (same session) · precedent: `EPIC-002_Concept_Register.md` · checkpoint refinements applied 2026-07-28: independent-evaluation language · evaluated→validated wording · portability rubric · label-not-adopted framing · projection-pattern terminology · this appendix. **STOP — M2 (collision resolutions) opens only on explicit confirmation following the checkpoint disposition.***
