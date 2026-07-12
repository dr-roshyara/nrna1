# EPIC-002 Concept Register (LIVING — updated after every research iteration)

**Kind:** research artifact — the single cross-disciplinary concept matrix for Constitutional Trust Discovery. **Authority:** generated; never authoritative without ARB review.
**Rule (ARB iteration-2 charter, 2026-07-11):** concepts appear in disciplines — *that is all this register records*. **No PublicDigit interpretation, no "maps to", no bounded-context naming** until all major disciplines are reviewed and synthesis is authorized.
**Confidence:** HIGH = ≥5 disciplines · MEDIUM = 3–4 · LOW = 1–2 · TENTATIVE = single source, uncertain.
**Stopping criterion (ARB, refined 2026-07-11):** stop when BOTH hold — (1) no concept moves LOW→MEDIUM or MEDIUM→HIGH for three consecutive major sources, AND (2) no fundamentally new conceptual category has appeared. Frequency alone is insufficient (a single paper can change a field).
**Classification (ARB, 2026-07-11):** every concept is additionally tagged **Universal** (recurs independent of field/legal system) · **Domain-specific** (tied to one field, not one jurisdiction) · **Jurisdiction-specific** (tied to one legal system's doctrine).

**Discipline codes:** ES = Election Science · CL = Constitutional Law · AL = Administrative Law · GT = Governance Theory · TE = Trust Engineering · DF = Digital Forensics · DS = Distributed Systems · PR = Provenance/Lineage · AT = Audit Theory · DDD = strategic DDD literature.

## Register (seeded from Iteration 1 — election-science core; all entries currently LOW/TENTATIVE by construction)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | Class | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Software independence (tamper-evidence of code reliance) | ✓ | | | | ✓ | | | | | | LOW (2) | Domain-specific | S2 S6 |
| Strong software independence (detected error correctable w/o re-run) | ✓ | | | | ✓ | | | | | | LOW (2) | Domain-specific | S6 |
| Auditability (property of a trail) ≠ Auditing (exercised process) | ✓ | | | | | | | | | | LOW (1) | Universal | S1 |
| Custody / chain-of-custody (compliance evidence) | ✓ | | | | | ✓* | | | | | LOW (2)* | Universal | S1 S4 S5 (*forensic practice embedded in ES sources — DF column provisional) |
| Statistical certification (risk-limiting audit; risk limit as quantified trust) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S4 S5 |
| Contestable (challenge-evidence) vs Defensible (affirmative evidence) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S4 |
| Staged verifiability relations (cast-as-intended / collected-as-cast / tallied-as-collected) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S2 S3 |
| Dispute resolution / adjudication as property distinct from verification | ✓ | | | | | | | | | | LOW (1) | Universal | S2 S3 |
| Declare-failure obligation (never certify on insufficient evidence) | ✓ | | | | | | | | | | LOW (1) | Domain-specific (candidate-Universal — watch) | S1 |
| Commit-before-sample (public commitment precedes audit selection) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S4 |
| Evidence co-production (stakeholder verification acts constitute evidence) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S3 S4 |
| Absence-of-evidence as first-class requirement (anonymity restricts admissible evidence) | ✓ | | | | | | | | | | LOW (1) | Domain-specific | S2 |
| Accountability provenance (causal inputs AND downstream consequences) | ✓ | | | | | | | ✓ | | | LOW (2) | Universal | S7 S2 |

*Iteration-1 sources S1–S7: see `EPIC-002_Literature_Review.md` §Sources. All claims currently [EXTRACTED-UNVERIFIED] (re-verification pending).*

## Iteration 2a additions (Constitutional Law + Administrative Law — VERIFIED: 18 confirmed / 7 refuted)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | Class | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Administrative/evidentiary record (fixed, contemporaneous, presumption of regularity) | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific (US doctrine; underlying idea may be more general — watch) | ACUS 2013-4; Overton Park comm. |
| Presumption of regularity (once certified, rarely rebutted) | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific | ACUS 2013-4 |
| Record-completeness challenge doctrine / undefined "bad faith" threshold (NEGATIVE FINDING: 50+ yrs undefined) | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific | U. Chicago L. Rev. |
| Contemporaneity requirement / no post-hoc rationalization (Chenery/hard-look) | | ✓ | ✓ | | | | | | | | LOW (2) | Jurisdiction-specific (US doctrine; relates to Universal commit-before-sample/declare-failure, ES) | ACUS 2013-4; CRS LSB10558; U. Chicago L. Rev. |
| Differentiated standard-of-review taxonomy (substantial evidence / abuse of discretion / de novo / clearly erroneous — NOT unified) | | ✓ | ✓ | | | | | | | | LOW (2) | Jurisdiction-specific (US doctrine; underlying idea — scrutiny varies by evidence type — may be more general) | Standard of review (WP); Hawaii Law lib.; Seattle U. L. Rev. |
| Rechtliches Gehör / third-party hearing right (broader than US due process) | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific | VwVfG §28(1); Pünder I·CON 2013 |
| Finality-vs-immutability decoupling (Bestandskraft ≠ immutability) | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific (German doctrine; underlying insight 'final ≠ immutable' is a candidate-Universal architectural caution) | VwVfG §48; Pünder I·CON 2013 |
| Procedural curing rules (Heilung) — NEGATIVE FINDING: exploitable as laxity incentive | | | ✓ | | | | | | | | LOW (1) | Jurisdiction-specific | VwVfG §§45–46; Pünder I·CON 2013 (Sendler) |
| Legitimacy vs. justice (distinct evaluative axes; legitimacy pre-eminent for judicial review) | | ✓ | | | | | | | | | LOW (1), single-source caveat | Universal (general jurisprudential distinction, not tied to one legal system) | Hickey, OJLS 2022 |

**Register-row promotions this iteration: ZERO.** Per the anti-clustering-bias rule, new concepts enter fresh rather than being merged into iteration-1 rows even where a relationship is plausible (see the Literature Review's "Cross-iteration concept-relationship notes" — merging is a clustering judgment explicitly deferred to the synthesis phase). Two relationship notes recorded (not merges): *contemporaneity requirement* ↔ *commit-before-sample/declare-failure* (ES); *finality-vs-immutability* ↔ *declare-failure/evidence co-production* (ES).

**Independent-discipline note on A-9 (assumption-table bookkeeping, distinct from register promotions):** the *differentiated standard-of-review taxonomy* concept contradicts A-9 via AL/CL's own internal logic, entirely independently of ES's custody/certification and auditability/auditing splits. Four independent A-9-weakening signals now stand across two disciplines (ES, AL/CL) — tracked in the Literature Review, not as a register-tier movement.

## Movement log (for the stopping criterion)

| Date | Source | Movements (LOW→MEDIUM / MEDIUM→HIGH) | Consecutive no-movement count |
|---|---|---|---|
| 2026-07-11 | Iteration 1 (S1–S7, seeding) | register created — all entries enter at LOW/TENTATIVE | 0 |
| 2026-07-11 | Iteration 2a attempt 1 (`wf_4c2904f1-602`) | **0 sources fetched — total infrastructure failure**, not a research result; excluded from the count | n/a |
| 2026-07-11 | Iteration 2a attempt 2 (`wf_e118e84a-441`, 23 sources, 18 confirmed) | 9 new concepts added (fresh, LOW) · **0 tier promotions** | 1 (first no-promotion iteration; criterion needs 3 consecutive) |

## Concept-relationship notes (allowed: concept↔concept only; no PublicDigit mapping)

- Custody evidence is a stated *precondition* of statistical certification (S4 S5: certification over an unestablished trail = "theater").
- Contestable/defensible pair *refines* software independence (S4: introduced because SI was too weak).
- Declare-failure *presupposes* an evidence-sufficiency measure (risk limit) (S1 S5).
- Co-production *conditions* auditability→trust conversion (a verifiable-but-unverified system yields no trust) (S3 S4).

## Movement log (for the stopping criterion)

| Date | Source | Movements (LOW→MEDIUM / MEDIUM→HIGH) | Consecutive no-movement count |
|---|---|---|---|
| 2026-07-11 | Iteration 1 (S1–S7, seeding) | register created — all entries enter at LOW/TENTATIVE | 0 |
