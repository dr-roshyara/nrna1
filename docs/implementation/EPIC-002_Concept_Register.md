# EPIC-002 Concept Register (LIVING — updated after every research iteration)

**Kind:** research artifact — the single cross-disciplinary concept matrix for Constitutional Trust Discovery. **Authority:** generated; never authoritative without ARB review.
**Rule (ARB iteration-2 charter, 2026-07-11):** concepts appear in disciplines — *that is all this register records*. **No PublicDigit interpretation, no "maps to", no bounded-context naming** until all major disciplines are reviewed and synthesis is authorized.
**Confidence:** HIGH = ≥5 disciplines · MEDIUM = 3–4 · LOW = 1–2 · TENTATIVE = single source, uncertain.
**Stopping criterion (ARB, refined 2026-07-11):** stop when BOTH hold — (1) no concept moves LOW→MEDIUM or MEDIUM→HIGH for three consecutive major sources, AND (2) no fundamentally new conceptual category has appeared. Frequency alone is insufficient (a single paper can change a field).
**Classification convention (ARB, refined 2026-07-11 — supersedes the prior immediate-classification wording):** each Class cell reads **"Observed in [discipline/jurisdiction] — Candidate [Universal/Domain-specific/Jurisdiction-specific] (evidence pending)"** until corroborated across ≥2 independent disciplines/jurisdictions, at which point the "candidate"/"evidence pending" qualifier drops. A concept sourced from one jurisdiction is a FACT about its source, not proof it is jurisdiction-bound.
**Nature column (ARB, 2026-07-11):** **Descriptive** (describes existing practice/reality) vs **Normative** (prescribes how a system ought to behave) — different synthesis weight.

**Discipline codes:** ES = Election Science · CL = Constitutional Law · AL = Administrative Law · GT = Governance Theory · TE = Trust Engineering · DF = Digital Forensics · DS = Distributed Systems · PR = Provenance/Lineage · AT = Audit Theory · DDD = strategic DDD literature.

## Register (seeded from Iteration 1 — election-science core; all entries currently LOW/TENTATIVE by construction)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | Class (Observed in / Candidate) | Nature | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Software independence (tamper-evidence of code reliance) | ✓ | | | | ✓ | | | | | | LOW (2) | Observed in ES+TE (2 sources) — Candidate Domain-specific | Normative | S2 S6 |
| Strong software independence (detected error correctable w/o re-run) | ✓ | | | | ✓ | | | | | | LOW (2) | Observed in ES/TE (S6) — Candidate Domain-specific (evidence pending, 1 source) | Normative | S6 |
| Auditability (property of a trail) ≠ Auditing (exercised process) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S1) — Candidate Universal (evidence pending, 1 source) | Descriptive | S1 |
| Custody / chain-of-custody (compliance evidence) | ✓ | | | | | ✓* | | | | | LOW (2)* | Observed in ES, embedded DF practice (S1 S4 S5) — Candidate Universal (strengthening pending iteration-2b forensics coverage) | Normative | S1 S4 S5 (*forensic practice embedded in ES sources — DF column provisional) |
| Statistical certification (risk-limiting audit; risk limit as quantified trust) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S4 S5) — Candidate Domain-specific | Normative | S4 S5 |
| Contestable (challenge-evidence) vs Defensible (affirmative evidence) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S4) — Candidate Domain-specific (evidence pending, 1 source) | Normative | S4 |
| Staged verifiability relations (cast-as-intended / collected-as-cast / tallied-as-collected) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S2 S3) — Candidate Domain-specific | Descriptive | S2 S3 |
| Dispute resolution / adjudication as property distinct from verification | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S2 S3) — Candidate Universal (evidence pending) | Descriptive | S2 S3 |
| Declare-failure obligation (never certify on insufficient evidence) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S1) — Candidate Universal (evidence pending, 1 source) | Normative | S1 |
| Commit-before-sample (public commitment precedes audit selection) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S4) — Candidate Domain-specific (evidence pending, 1 source) | Normative | S4 |
| Evidence co-production (stakeholder verification acts constitute evidence) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S3 S4) — Candidate Domain-specific | Descriptive | S3 S4 |
| Absence-of-evidence as first-class requirement (anonymity restricts admissible evidence) | ✓ | | | | | | | | | | LOW (1) | Observed in ES (S2) — Candidate Domain-specific (evidence pending, 1 source) | Normative | S2 |
| Accountability provenance (causal inputs AND downstream consequences) | ✓ | | | | | | | ✓ | | | LOW (2) | Observed in ES+PR (S7 S2) — Candidate Universal (evidence pending, 2 sources) | Normative | S7 S2 |

*Iteration-1 sources S1–S7: see `EPIC-002_Literature_Review.md` §Sources. All claims currently [EXTRACTED-UNVERIFIED] (re-verification pending).*

## Iteration 2a additions (Constitutional Law + Administrative Law — VERIFIED: 18 confirmed / 7 refuted)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | Class (Observed in / Candidate) | Nature | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Administrative/evidentiary record (fixed, contemporaneous, presumption of regularity) | | | ✓ | | | | | | | | LOW (1) | Observed in AL (US) — Candidate Universal for the underlying idea (evidence pending, 1 jurisdiction) | Normative | ACUS 2013-4; Overton Park comm. |
| Presumption of regularity (once certified, rarely rebutted) | | | ✓ | | | | | | | | LOW (1) | Observed in AL (US) — Candidate Jurisdiction-specific (evidence pending) | Descriptive | ACUS 2013-4 |
| Record-completeness challenge doctrine / undefined "bad faith" threshold (NEGATIVE FINDING: 50+ yrs undefined) | | | ✓ | | | | | | | | LOW (1) | Observed in AL (US) — Candidate Jurisdiction-specific (evidence pending) | Descriptive (documents a doctrinal gap) | U. Chicago L. Rev. |
| Contemporaneity requirement / no post-hoc rationalization (Chenery/hard-look) | | ✓ | ✓ | | | | | | | | LOW (2) | Observed in AL/CL (US) — Candidate Jurisdiction-specific; relates to Candidate-Universal commit-before-sample/declare-failure (ES) — not merged | Normative | ACUS 2013-4; CRS LSB10558; U. Chicago L. Rev. |
| Differentiated standard-of-review taxonomy (substantial evidence / abuse of discretion / de novo / clearly erroneous — NOT unified) | | ✓ | ✓ | | | | | | | | LOW (2) | Observed in AL/CL (US) — Candidate Jurisdiction-specific for the taxonomy's specific instruments; the underlying idea (scrutiny varies by evidence type) is a Candidate Universal (evidence pending) | Descriptive | Standard of review (WP); Hawaii Law lib.; Seattle U. L. Rev. |
| Rechtliches Gehör / third-party hearing right (broader than US due process) | | | ✓ | | | | | | | | LOW (1) | Observed in AL (Germany) — Candidate Jurisdiction-specific | Normative | VwVfG §28(1); Pünder I·CON 2013 |
| Finality-vs-immutability decoupling (Bestandskraft ≠ immutability) | | | ✓ | | | | | | | | LOW (1) | Observed in AL (Germany) — Candidate Jurisdiction-specific for the doctrine; the underlying insight (finality ≠ immutability) is a Candidate Universal architectural caution (evidence pending) | Descriptive | VwVfG §48; Pünder I·CON 2013 |
| Procedural curing rules (Heilung) — NEGATIVE FINDING: exploitable as laxity incentive | | | ✓ | | | | | | | | LOW (1) | Observed in AL (Germany) — Candidate Jurisdiction-specific | Descriptive (documents an exploitable practice — also a negative finding) | VwVfG §§45–46; Pünder I·CON 2013 (Sendler) |
| Legitimacy vs. justice (distinct evaluative axes; legitimacy pre-eminent for judicial review) | | ✓ | | | | | | | | | LOW (1), single-source caveat | Observed in CL (1 source, Hickey OJLS 2022) — Candidate Universal (evidence pending, single-source caveat) | Descriptive | Hickey, OJLS 2022 |

**Register-row promotions this iteration: ZERO.** Per the anti-clustering-bias rule, new concepts enter fresh rather than being merged into iteration-1 rows even where a relationship is plausible (see the Literature Review's "Cross-iteration concept-relationship notes" — merging is a clustering judgment explicitly deferred to the synthesis phase). Two relationship notes recorded (not merges): *contemporaneity requirement* ↔ *commit-before-sample/declare-failure* (ES); *finality-vs-immutability* ↔ *declare-failure/evidence co-production* (ES).

## Iteration 2b additions (Digital Forensics + W3C PROV + Trust Engineering/Assurance — VERIFIED: 10 confirmed / 15 refuted; ZERO surviving Digital Forensics claims)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | Class (Observed in / Candidate) | Nature | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Entity/Activity/Agent typed decomposition + generation/usage/derivation/attribution/delegation relations | | | | | | | | ✓ | | | LOW (1 discipline; corroborated across 2 standards bodies within it — W3C+IVOA) | Observed in PR (W3C PROV-DM/PROV-O + design rationale; echoed by IVOA Provenance DM) — Candidate Universal (evidence pending a second discipline) | Descriptive | PROV-DM; PROV-O; Moreau et al. 2015 |
| Unspecified adjudication mechanism (derivation validity determined by "unspecified means") | | | | | | | | ✓ | | | LOW (1) | Observed in PR (W3C PROV-DM) — Candidate Universal (relates to AL's Bestandskraft/curing and ES's declare-failure — not merged) | Descriptive | PROV-DM |
| Evidence-substrate vs. trust-judgement scope boundary (standing-question first hit) | | | | | | | | ✓ | | | LOW (1), single-source, MEDIUM-confidence/split-vote | Observed in PR (W3C PROV-DM) — Candidate Universal (evidence pending; candidate NEW CONCEPTUAL CATEGORY per the stopping criterion) | Descriptive/Interpretive | PROV-DM |
| Causal ordering as quasi-order distinct from timestamp ordering | | | | | | | | ✓ | | | LOW (1) | Observed in PR (W3C PROV-Sem, formal semantics) — Candidate Domain-specific (formal-semantics framing; possible DS echo unconfirmed this iteration) | Descriptive | PROV-Sem |
| Traceability across heterogeneous artifact types (models, analyses, behaviour traces unified only by links) | | | | | ✓ | | | | | | LOW (1) | Observed in TE (Wei et al., J. Systems and Software 2024) — Candidate Universal (relates to PR's typed-decomposition, Finding 1 — not merged) | Descriptive | Wei et al. 2024 |
| Confirmation bias as a credible assurance-case failure mode ("assurance theater") — NEGATIVE FINDING | | | | | ✓ | | | | | | LOW (1 discipline, corroborated by 2 independent sources within it) | Observed in TE (Gohar et al. 2025; Habli/Alexander/Hawkins 2021, incl. RAF Nimrod MR2 XV230) — Candidate Universal (evidence pending a second discipline) | Descriptive (documents a failure mode) | arXiv:2502.00238; Habli et al. SSS'21 |
| Empirical outcome evaluation of an assurance mechanism (vs. methodological/procedural maturity) — NEGATIVE FINDING | | | | | ✓ | | | | | | LOW (1) | Observed in TE (Habli/Alexander/Hawkins SSS'21) — Candidate Universal (evidence pending; the finding's own framing suggests broad applicability, held at Candidate per convention) | Descriptive (documents an evidence-practice gap) | Habli et al. SSS'21 |

**Register-row promotions this iteration: ZERO** (same anti-clustering-bias rule). **Digital Forensics (DF) column remains entirely unpopulated** — the coverage gap is real, not a register omission.

**Repeated cross-disciplinary phenomenon flagged (ARB stopping-criterion condition 2, triggered; wording tightened 2026-07-12):** *evidence-substrate vs. argument-over-evidence separability* — independently observed in PR (Finding 3, medium confidence) and TE (Findings 5–7, though the most direct TE-specific claims for this exact framing were refuted — see Literature Review refuted-claims table). Status: **repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis.** Not yet a settled synthesis conclusion.

**A-9 observation tally (bookkeeping only — NOT a synthesis verdict, ARB wording correction 2026-07-12):** FOUR disciplines have each independently observed a structure that appears inconsistent with A-9, via four structurally unrelated mechanisms — ES (custody/certification, auditability/auditing splits) · AL/CL (differentiated standard-of-review taxonomy) · PR (typed data-model decomposition) · TE (heterogeneous-artifact traceability). *The literature does not know A-9 exists; it only reports itself. Whether A-9 should be considered weakened is reserved for the synthesis phase.*

**Independent-discipline note on A-9 (assumption-table bookkeeping, distinct from register promotions):** the *differentiated standard-of-review taxonomy* concept contradicts A-9 via AL/CL's own internal logic, entirely independently of ES's custody/certification and auditability/auditing splits. Four independent A-9-weakening signals now stand across two disciplines (ES, AL/CL) — tracked in the Literature Review, not as a register-tier movement.

## Movement log (for the stopping criterion)

| Date | Source | Movements (LOW→MEDIUM / MEDIUM→HIGH) | Consecutive no-movement count |
|---|---|---|---|
| 2026-07-11 | Iteration 1 (S1–S7, seeding) | register created — all entries enter at LOW/TENTATIVE | 0 |
| 2026-07-11 | Iteration 2a attempt 1 (`wf_4c2904f1-602`) | **0 sources fetched — total infrastructure failure**, not a research result; excluded from the count | n/a |
| 2026-07-11 | Iteration 2a attempt 2 (`wf_e118e84a-441`, 23 sources, 18 confirmed) | 9 new concepts added (fresh, LOW) · **0 tier promotions** | 1 (first no-promotion iteration; criterion needs 3 consecutive) |
| 2026-07-12 | Iteration 2b attempt 1 (`wf_97560c79-715`) | **0 sources — decomposition schema loop, infrastructure failure**; excluded from the count | n/a |
| 2026-07-12 | Iteration 2b attempt 2 (`wf_9986ccdd-189`, 26 sources, 10 confirmed) | 7 new concepts added (fresh, LOW) · **0 tier promotions** BUT **condition-2 (new conceptual category) TRIGGERED** — evidence-substrate/argument-over-evidence separability | Criterion NOT reached (condition 2 explicitly overrides frequency-only counting per the refined rule) |

## Concept-relationship notes (allowed: concept↔concept only; no PublicDigit mapping)

- Custody evidence is a stated *precondition* of statistical certification (S4 S5: certification over an unestablished trail = "theater").
- Contestable/defensible pair *refines* software independence (S4: introduced because SI was too weak).
- Declare-failure *presupposes* an evidence-sufficiency measure (risk limit) (S1 S5).
- Co-production *conditions* auditability→trust conversion (a verifiable-but-unverified system yields no trust) (S3 S4).

## Repeated cross-disciplinary phenomena — recurrence tracking (ARB request, 2026-07-12)

**Why this table exists, separate from the per-concept rows above:** per-row checkmark counts mostly read 1, because the anti-clustering-bias rule forbids merging differently-named concepts across disciplines even when they instantiate the same underlying pattern (e.g. ES's *declare-failure* and PR's *unspecified adjudication mechanism* are NOT merged into one row). The real cross-discipline recurrence signal therefore lives at the **phenomenon** level — patterns that several separately-recorded concept-rows independently point at. This table makes that signal explicit, as the ARB requested, without merging the underlying rows.

**Status vocabulary (ARB, 2026-07-12):** a phenomenon observed in only 1 discipline is **not yet a repeated phenomenon** (single-discipline observation). A phenomenon independently observed in ≥2 disciplines is a **repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis**. Neither status is a synthesis verdict; both are bookkeeping.

| Phenomenon | ES | CL | AL | GT | TE | DF | DS | PR | AT | Disciplines (n) | Status |
|---|---|---|---|---|---|---|---|---|---|---:|---|
| Evidence is structurally plural / multi-kind (instantiating rows: ES auditability≠auditing, custody-vs-certification; AL/CL standard-of-review taxonomy; PR Entity/Activity/Agent decomposition; TE heterogeneous-artifact traceability) | ✓ | ✓ | ✓ | | ✓ | | | ✓ | | 4 | Repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis |
| An adjudication/correction mechanism is neither automatic nor guaranteed reliable (ES declare-failure; AL finality-vs-immutability/procedural curing rules, documented as gameable; PR unspecified adjudication mechanism) | ✓ | | ✓ | | | | | ✓ | | 3 | Repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis |
| Contemporaneity — no retrospective reconstruction of record or justification (ES commit-before-sample; AL/CL contemporaneity requirement/Chenery) | ✓ | ✓ | ✓ | | | | | | | 2 (counted once per unique discipline: ES, AL/CL) | Repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis |
| Evidence-substrate is separable from argument/trust-judgement over it (PR scope-boundary finding, medium confidence; TE assurance-case case-vs-evidence framing, most direct claims refuted) | | | | | ✓ | | | ✓ | | 2 | Repeated cross-disciplinary phenomenon → candidate conceptual category → requires synthesis (confidence uneven — one leg medium, one leg indirect) |
| Chain of custody / evidence integrity as a named practice (ES, embedded practice only) | ✓ | | | | | | | | | 1 | **NOT YET a repeated phenomenon** — single-discipline observation; DF coverage gap (0 surviving claims) prevents cross-discipline confirmation this round |
| Causal ordering is not reducible to timestamp/chronological ordering (PR quasi-order finding) | | | | | | | | ✓ | | 1 | **NOT YET a repeated phenomenon** — single-discipline observation; watch for DS/ES echo in future iterations |

**Reading this table (bookkeeping, not synthesis):** four phenomena have now crossed the ≥2-discipline recurrence threshold; two remain single-discipline observations pending further iterations (one of them — chain of custody — blocked specifically by the Digital Forensics coverage gap). Per Strategic DDD discovery discipline, **recurrence across independent disciplines is the signal this register is built to surface — not confidence tiers alone.**

## Movement log (for the stopping criterion)

| Date | Source | Movements (LOW→MEDIUM / MEDIUM→HIGH) | Consecutive no-movement count |
|---|---|---|---|
| 2026-07-11 | Iteration 1 (S1–S7, seeding) | register created — all entries enter at LOW/TENTATIVE | 0 |
