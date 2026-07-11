# EPIC-002 Literature Review — Constitutional Trust Discovery (Working Document)

**Status:** IN PROGRESS — iteration 1 of N (STOPPING CRITERION NOT REACHED). **Authority:** generated — research artifact under the charter `EPIC-002_Problem_Statement.md`; never authoritative without ARB review.
**MAPPING QUARANTINE (ARB iteration-2 charter, 2026-07-11):** iteration 1's INTERPRETATION entries that map literature concepts onto PublicDigit (e.g. "the correction loop maps to strong software independence", "maps to the implemented Contestation/Adjudication split") are **QUARANTINED — premature**. They stand as recorded history but carry NO analytical weight until ALL major disciplines are reviewed and cross-disciplinary synthesis is authorized. The only permitted mapping is the Concept Register (`EPIC-002_Concept_Register.md`): concepts appear in disciplines — that is all. Forbidden until synthesis: "PublicDigit equals …", "this means we should build …", "this maps to …".
**Method compliance:** no bounded contexts are named or proposed in this document (ARB: concepts → clusters → candidate BCs, in that order, later). Every statement labeled FACT / INTERPRETATION / RECOMMENDATION / OPEN QUESTION. Sources classified Supports / Weakens / Contradicts / Introduces against A-1..A-10.

## Verification status (honest)

**FACT:** Claims below were extracted from fetched primary sources by the research harness (fan-out search → fetch → claim extraction). The adversarial verification stage (3 independent votes per claim) **failed entirely on infrastructure** — 25/25 panels rate-limited (session limit; resets 21:50 Europe/Berlin). Every claim is therefore labeled **[EXTRACTED-UNVERIFIED]**. Re-verification is scheduled (workflow resumable from cache, run `wf_588d4d47-8d6`).
**INTERPRETATION:** The seven sources are canonical works of the election-evidence literature whose authorship and theses are widely known; the extracted claims are consistent with their published content. This corroboration is stated as interpretation, not as verification — the adversarial pass still runs.

## Iteration-1 coverage (and honest gaps)

**FACT:** This iteration covers **election science / verifiable-elections evidence theory** deeply, plus one provenance source. The rate limit truncated the fetch phase before: constitutional law · administrative law · trust engineering (assurance cases, NIST) · W3C PROV core · audit theory · distributed-systems replay · strategic DDD. **Those disciplines remain uncovered — iteration 2+ targets.** Per the ARB review, constitutional/administrative law and assurance cases should *dominate* subsequent iterations.

## Sources (iteration 1)

| S# | Source | Discipline | Claims |
|----|--------|-----------|-------:|
| S1 | Stark & Wagner, *Evidence-Based Elections* — stat.berkeley.edu/~stark/Preprints/evidenceVote12.pdf | election science | 4 |
| S2 | Bernhard et al., *Public Evidence from Secret Ballots* — arxiv.org/pdf/1707.08619 | election science × cryptographic verifiability | 5 |
| S3 | Benaloh, Rivest, Ryan, Stark, Teague, Vora, *End-to-end verifiability* — arxiv.org/pdf/1504.03778 | election science | 4 |
| S4 | Appel & Stark, *Evidence-Based Elections: Create a Meaningful Paper Trail, Then Audit* — Georgetown Law Tech Rev 4.2 | election science × law | 5 |
| S5 | Lindeman & Stark, *A Gentle Introduction to Risk-Limiting Audits* — gentle12.pdf | election science / audit statistics | 3 |
| S6 | *Software independence* (recent treatment) — arxiv.org/pdf/2311.03372 | trust engineering × election science | 3 |
| S7 | Accountability-oriented provenance — arxiv.org/pdf/1804.05741 | provenance / data lineage | 1 |

## Assumption-evidence table — first pass (all entries [EXTRACTED-UNVERIFIED])

| A# | Assumption | Iteration-1 effect | Basis |
|----|------------|--------------------|-------|
| A-1 | "Evidence" is the right BC name | **Mixed.** Supports: "evidence" is the discipline's own umbrella term (S4); evidence is the first-class deliverable of an election system (S3). Weakens: every source's *internal structure* splits the umbrella into distinct concepts (custody vs certification vs verification vs adjudication). | S1 S2 S3 S4 |
| A-2 | Evidence must be immutable | **Supports, but as NOT SUFFICIENT.** Tamper-evident trail required (S2 S4); *internal* immutability insufficient — external detectability (software independence) required (S2 S6); public commitment before sampling required (S4); immutability is a *precondition* to certification, not an intrinsic virtue (S5). | S2 S4 S5 S6 |
| A-3 | Evidence must be auditable | **Strongly supports — with a split.** "Auditability" (property of the trail) and "auditing" (routine exercised process) are separable concerns; a trail nobody audits produces no trust (S1). | S1 S2 S3 S4 S5 |
| A-4 | Evidence must be replay-safe | **Supports** in the sense of deterministic outcome reconstruction from the trail (full hand tally as fallback = the discipline's "replay") (S1 S5). | S1 S5 |
| A-5 | Evidence must be tenant-isolated | **Untouched this iteration** (no source addressed multi-tenancy). | — |
| A-6 | Evidence must be causally ordered | **Supports.** Staged causal pipeline cast→collected→tallied (S2); accountability provenance = full causal chain of inputs AND downstream flow-on effects (S7). | S2 S7 |
| A-7 | Evidence must be anonymous | **Supports — and sharpens.** Anonymity is a constraint that RESTRICTS admissible evidence types; the discipline demands evidence of correctness AND guaranteed absence-of-evidence about individual votes, explicitly in tension (S2). Norway's identity-linked return codes = documented failure (S2). Public auditability and strict anonymity are simultaneously achievable (bulletin board + verifiable mixing/homomorphic tally) (S3). | S2 S3 |
| A-8 | Evidence must be queryable | **Weakens/sharpens.** Deliberately NON-queryable along the voter axis (S2); queryable+auditable without anonymity loss is possible but is a designed property, not a default (S3). | S2 S3 |
| A-9 | Evidence belongs to a SINGLE bounded context | **Repeatedly weakened — the strongest signal of the iteration.** The discipline itself separates: custody/compliance evidence vs statistical certification evidence (S1 S4 S5) · individual vs universal verification vs accountability vs dispute resolution (S2) · contestable (challenge-evidence) vs defensible (affirmative evidence) (S4) · auditability vs auditing (S1). **No BC conclusion is drawn here** (method: concepts first). | S1 S2 S4 S5 |
| A-10 | Evidence is produced by the correction loop | **Weakened, reframed.** Evidence is produced by the ENTIRE lifecycle (capture-time, voter-verified, custody, audit) and co-produced by stakeholders' verification acts; the correction loop maps to *strong software independence* — the RECOVERY/adjudication mechanism that CONSUMES evidence, not its sole producer (S2 S3 S4 S6). | S2 S3 S4 S6 |

## Concepts extracted (NKM capsules — [EXTRACTED-UNVERIFIED]; domain vs architectural flagged per ARB)

1. **Software independence** *(trust property — domain-level requirement)*: an undetected software change/error must not cause an undetectable outcome change/error. Strong SI adds: a detected error is correctable without re-running the election. Purpose: remove code from the trust base. Failure mode: paperless DREs. Evidence level: wide adoption in standards discourse (S2 S3 S6). **INTERPRETATION:** strong SI is the literature's name for what PublicDigit's correction loop *does*.
2. **Evidence-based election** *(compound concept)*: trustworthy outcome = auditable trail + routine auditing of that trail; either alone is insufficient (S1 S4). Introduces the **auditability ≠ auditing** split.
3. **Compliance audit vs risk-limiting audit** *(two irreducible evidence kinds)*: qualitative custody/procedural evidence (chain-of-custody, ballot accounting, seals — legal standard of judgment) vs quantitative statistical evidence (pre-specified risk limit). Certification without prior custody establishment = "security theater"; the audited system must not attest to itself (S1 S4 S5).
4. **Contestable / defensible** *(two evidence directions)*: ability to produce public evidence that a wrong outcome is untrustworthy vs convincing evidence that a correct outcome is correct — introduced precisely because software independence was too weak (S4). **INTERPRETATION:** challenge-evidence and affirmative-evidence are different artifacts with different consumers.
5. **E2E-verifiability triple** *(staged verification relations)*: cast-as-intended (voter-only verifier) · collected-as-cast · tallied-as-collected (anyone) — distinct verifier populations per stage (S2 S3).
6. **Dispute resolution as separate property** *(domain concept)*: an accuser must be able to bring third-party-checkable evidence, resistant to counterfeit-receipt "defaming attacks"; verification and adjudication are distinct (S2 S3). **INTERPRETATION:** maps to the implemented Contestation/Adjudication split — the literature independently reproduces that boundary.
7. **Risk limit** *(quantified trust parameter)*: evidence sufficiency as bounded statistical risk, cause-agnostic (bug = fraud = misconfiguration), never a binary (S4 S5).
8. **Declare-failure obligation** *(evidence property)*: a trustworthy canvass never certifies on insufficient evidence — it must explicitly report failure (S1).
9. **Commit-before-sample** *(procedural integrity rule)*: officials must commit to tallies before audit samples are drawn (Cuyahoga manipulation precedent) (S4). **INTERPRETATION:** an ordering/commitment requirement on evidence publication — architectural echo: append-only commitment logs.
10. **Evidence co-production** *(sociotechnical property)*: evidence strength depends on stakeholders actually exercising verification; unpredictable independent verification acts are part of the evidence (S3). Claimed-vs-observed: voter-verifiABLE ≠ voter-verifiED (~7% misprint detection, S4).
11. **Absence-of-evidence as first-class requirement** *(domain constraint)*: the system must produce evidence of correctness AND guarantee no evidence exists of individual votes (S2). **INTERPRETATION:** PublicDigit's anonymity invariant is the second half of this pair.
12. **Accountability provenance** *(provenance concept)*: decision provenance = causal inputs AND downstream flow-on effects — a decision's consequences belong to its provenance record (S7).

## Negative findings (mandatory — [EXTRACTED-UNVERIFIED])

- **Paperless DREs abandoned** (most U.S. states): software errors could alter outcomes tracelessly; "fatal flaw"; 4,400 votes irretrievably lost (Carteret County, NC); machines recording more votes than voters (S1 S4 S6).
- **Every major deployed Internet-voting system surveyed failed to provide evidence of a correct tally** (Estonia, NSW iVote, Norway, Switzerland, Utah 2016). iVote's verification channel silently failed for ~10% of attempts, discovered a year later — error magnitude sufficient to change a seat. Norway's return codes linked ballots to voter identity — anonymity sacrificed by design (S2).
- **VVPAT/BMD verification failed in observed practice:** ~7% of voters detect deliberate misprints — "neither contestable nor defensible" (S4).
- **"Internet voting cannot be secured by any currently known technology"** (NASEM 2018, via S4); E2E cryptographic paperless protocols "not mature enough for use in public elections" (S4).
- **Cuyahoga County:** officials altered tallies after learning which precincts would be audited → the commit-before-sample rule (S4).

**INTERPRETATION (uncomfortable, must be faced):** the literature's strongest negative findings target *online voting itself* — the category PublicDigit belongs to. The going-in reading is NOT "the platform is impossible" but that the bar for its evidence is *higher*: independent verifiability, public commitment, stakeholder-exercisable verification, and honest declare-failure semantics are the properties the failed systems lacked. **OPEN QUESTION:** which of these properties are achievable in PublicDigit's setting (private-organization constitutional governance, not national public elections), and which must be explicitly declared out of scope with recorded consequences?

## Cross-discipline recurrence (candidates for ubiquitous language — provisional, NOT clustered yet)

**FACT (within iteration-1 limits):** custody/chain-of-custody (election science + forensic practice embedded in S1/S4/S5) · provenance-with-consequences (provenance + election accountability, S7 + S2) · adjudication/dispute-resolution (election science + law-adjacent S4) · commitment/append-only publication (election science + distributed-systems echo) · verification-by-independent-means (election science + trust engineering, S6).
**OPEN QUESTION:** recurrence across the *uncovered* disciplines (constitutional law, admin law, assurance cases, audit theory) — cannot be claimed yet.

## Contradiction map (iteration 1)

- **Queryability vs anonymity** (A-8 vs A-7): S2 restricts, S3 shows a designed reconciliation — not averaged; both stand as the tension the domain must hold.
- **Claimed vs observed verification** — verifiability features that exist but are not exercised produce no evidence (S3/S4 vs vendor claims recorded in S2's survey).

## RECOMMENDATIONS (proposals only — no governance, no design)

1. Re-run the adversarial verification pass when the rate limit resets (workflow cache makes this cheap); promote surviving claims from [EXTRACTED-UNVERIFIED] to verified.
2. Iteration 2 targets, in priority order per the ARB review: **constitutional law** (legitimacy, procedural fairness, burden of proof, appeal) · **administrative law** (justification, appeals ≈ adjudication workflows) · **assurance cases** (structured trust arguments — potentially UL-changing) · **election certification as distinct from auditing** · **governance theory** (accountability, transparency, delegation, authority) · W3C PROV core · audit theory.
3. Hold ALL concept clustering until at least the law + assurance-case iterations land (the current concept set is election-science-heavy; clustering now would bake in a single-discipline bias).

## Stopping criterion

**NOT REACHED** (criterion updated by the ARB iteration-2 charter: stop when no concept moves LOW→MEDIUM or MEDIUM→HIGH for three consecutive major sources — see the Concept Register movement log). Iteration 1 seeded the register; zero qualifying no-movement sources so far. The review continues.

---
*Charter: `EPIC-002_Problem_Statement.md` · Run: `wf_588d4d47-8d6` (resumable) · Raw claims: workflow journal + scratchpad extract · Labels per charter: FACT / INTERPRETATION / RECOMMENDATION / OPEN QUESTION, never collapsed.*
