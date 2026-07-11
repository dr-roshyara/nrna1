# EPIC-002 Concept Register (LIVING — updated after every research iteration)

**Kind:** research artifact — the single cross-disciplinary concept matrix for Constitutional Trust Discovery. **Authority:** generated; never authoritative without ARB review.
**Rule (ARB iteration-2 charter, 2026-07-11):** concepts appear in disciplines — *that is all this register records*. **No PublicDigit interpretation, no "maps to", no bounded-context naming** until all major disciplines are reviewed and synthesis is authorized.
**Confidence:** HIGH = ≥5 disciplines · MEDIUM = 3–4 · LOW = 1–2 · TENTATIVE = single source, uncertain.
**Stopping criterion (ARB):** stop when no concept moves LOW→MEDIUM or MEDIUM→HIGH for three consecutive major sources — knowledge saturation, not fatigue.

**Discipline codes:** ES = Election Science · CL = Constitutional Law · AL = Administrative Law · GT = Governance Theory · TE = Trust Engineering · DF = Digital Forensics · DS = Distributed Systems · PR = Provenance/Lineage · AT = Audit Theory · DDD = strategic DDD literature.

## Register (seeded from Iteration 1 — election-science core; all entries currently LOW/TENTATIVE by construction)

| Concept | ES | CL | AL | GT | TE | DF | DS | PR | AT | DDD | Confidence | First source(s) |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| Software independence (tamper-evidence of code reliance) | ✓ | | | | ✓ | | | | | | LOW (2) | S2 S6 |
| Strong software independence (detected error correctable w/o re-run) | ✓ | | | | ✓ | | | | | | LOW (2) | S6 |
| Auditability (property of a trail) ≠ Auditing (exercised process) | ✓ | | | | | | | | | | LOW (1) | S1 |
| Custody / chain-of-custody (compliance evidence) | ✓ | | | | | ✓* | | | | | LOW (2)* | S1 S4 S5 (*forensic practice embedded in ES sources — DF column provisional) |
| Statistical certification (risk-limiting audit; risk limit as quantified trust) | ✓ | | | | | | | | | | LOW (1) | S4 S5 |
| Contestable (challenge-evidence) vs Defensible (affirmative evidence) | ✓ | | | | | | | | | | LOW (1) | S4 |
| Staged verifiability relations (cast-as-intended / collected-as-cast / tallied-as-collected) | ✓ | | | | | | | | | | LOW (1) | S2 S3 |
| Dispute resolution / adjudication as property distinct from verification | ✓ | | | | | | | | | | LOW (1) | S2 S3 |
| Declare-failure obligation (never certify on insufficient evidence) | ✓ | | | | | | | | | | LOW (1) | S1 |
| Commit-before-sample (public commitment precedes audit selection) | ✓ | | | | | | | | | | LOW (1) | S4 |
| Evidence co-production (stakeholder verification acts constitute evidence) | ✓ | | | | | | | | | | LOW (1) | S3 S4 |
| Absence-of-evidence as first-class requirement (anonymity restricts admissible evidence) | ✓ | | | | | | | | | | LOW (1) | S2 |
| Accountability provenance (causal inputs AND downstream consequences) | ✓ | | | | | | | ✓ | | | LOW (2) | S7 S2 |

*Iteration-1 sources S1–S7: see `EPIC-002_Literature_Review.md` §Sources. All claims currently [EXTRACTED-UNVERIFIED] (re-verification pending).*

## Concept-relationship notes (allowed: concept↔concept only; no PublicDigit mapping)

- Custody evidence is a stated *precondition* of statistical certification (S4 S5: certification over an unestablished trail = "theater").
- Contestable/defensible pair *refines* software independence (S4: introduced because SI was too weak).
- Declare-failure *presupposes* an evidence-sufficiency measure (risk limit) (S1 S5).
- Co-production *conditions* auditability→trust conversion (a verifiable-but-unverified system yields no trust) (S3 S4).

## Movement log (for the stopping criterion)

| Date | Source | Movements (LOW→MEDIUM / MEDIUM→HIGH) | Consecutive no-movement count |
|---|---|---|---|
| 2026-07-11 | Iteration 1 (S1–S7, seeding) | register created — all entries enter at LOW/TENTATIVE | 0 |
