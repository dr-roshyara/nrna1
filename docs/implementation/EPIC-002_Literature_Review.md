# EPIC-002 Literature Review — Constitutional Trust Discovery (Working Document)

**Status:** IN PROGRESS — iteration 1 complete (unverified); **iteration 2a (Constitutional Law + Administrative Law) COMPLETE AND VERIFIED** on retry (run `wf_e118e84a-441`, under Sonnet 5, after the first attempt `wf_4c2904f1-602` failed totally — 0 sources, all 30 fetches hit the Fable-5 session limit; recorded as history below). Iteration 2a: 23 sources fetched, 91 claims extracted, 25 adversarially voted, **18 CONFIRMED**, **7 REFUTED** (recorded — not repeated), 0 stuck unverified. STOPPING CRITERION NOT REACHED. **Authority:** generated — research artifact under the charter `EPIC-002_Problem_Statement.md`; never authoritative without ARB review.
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

## Iteration 2a — Constitutional Law + Administrative Law (VERIFIED)

**Coverage note (honest):** the run's search angles skewed heavily to U.S. federal administrative/constitutional law plus German *Verwaltungsrecht* (VwVfG). The one England/Wales source tested (*Wiley*, on late-justification admissibility) was **refuted both times** — common-law-outside-the-US remains effectively uncovered despite being requested. **Tyler-style empirical procedural-justice research (perceived-fairness→legitimacy) was explicitly in scope and NOT reached** — sources were fetched (Tyler 2015 psych-science survey, *Why People Obey the Law*, a procedural-justice methods review) but produced no claim that survived to the confirmed list. **This is a coverage gap, not a finding about Tyler's work** — flagged for a dedicated iteration-2 follow-up. The requested private-organization transfer question (German *Vereinsrecht*, association/party bylaws) was **not reached by any surviving source** — two German association-law sources were fetched but produced zero confirmed claims. **This question remains fully open, not merely thinly evidenced.**

### Confirmed findings (18 claims → 8 synthesized; per-source format per the ARB charter)

**Source cluster 1 — Administrative record & presumption of regularity**
**Sources:** ACUS Recommendation 2013-4 (primary) · *Overton Park* commentary, U. Chicago L. Rev. (primary). **Discipline:** Administrative Law.
**Key claims (FACT, confirmed 3-0 unless noted):** the administrative record is the fixed, contemporaneous substrate of a reviewable decision — everything actually before the decision-maker at the time, including contrary evidence; post-hoc litigation affidavits are "merely post hoc rationalizations" (*Overton Park* 1971) and excluded; once certified, the record carries a strong, rarely-rebutted presumption of regularity.
**Assumption impact:** SUPPORTS A-2 (a form of immutability) and A-3 (auditability) — but **as properties of the record specifically, not of the underlying determination** (see Bestandskraft finding below, which shows these are NOT the same thing).
**Concept Register update:** new concepts — *administrative/evidentiary record* · *presumption of regularity* (both AL, LOW/1 discipline this run).
**Category:** FACT (doctrine) + INTERPRETATION (the record/determination distinction).
**Open question:** does "presumption of regularity" have any analog outside adjudicated public-law records?

**Source cluster 2 — The undefined "bad faith" threshold (NEGATIVE FINDING)**
**Source:** U. Chicago L. Rev., *Scope of Evidentiary Review in Constitutional Challenges to Agency Action* (primary). **Discipline:** Administrative/Constitutional Law.
**Key claim (FACT, 3-0):** courts permit expanding review beyond the closed record only on a "strong showing of bad faith or improper behavior" — and **this threshold has never been fully explained by the courts that apply it**, over 50+ years of litigation (only one Supreme Court case, *Dept. of Commerce v. New York* 2019, has ever found it satisfied, without articulating a general test).
**Assumption impact:** this is a **mandatory negative finding**: a mechanism meant to guard record integrity against bad-faith conduct exists in name but has no settled operational content.
**Concept Register update:** new concept — *record-completeness challenge doctrine / undefined bad-faith threshold* (AL, LOW/1) — flagged negative-finding.
**Category:** FACT.
**Open question:** does any jurisdiction (comparative/EU law) have a clearer analog where U.S. courts do not?

**Source cluster 3 — Contemporaneity of justification (Chenery / hard-look doctrine)**
**Sources:** ACUS 2013-4 · CRS Legal Sidebar LSB10558 · U. Chicago L. Rev. (all primary/secondary, corroborating). **Discipline:** Administrative Law.
**Key claims (FACT, confirmed):** an agency must show "a rational connection between the facts found and the choice made" (*State Farm* 1983) at the time of decision; a reviewing body may **never** supply reasoning the decision-maker itself did not give (*Chenery*); reaffirmed in *DHS v. Regents* (2020), which rejected reasons offered nine months after the original decision, even though the reconstructed rationale may have been sound.
**Assumption impact:** STRONGLY SUPPORTS A-6 (causal/temporal ordering — justification must precede or accompany the decision, never be reconstructed afterward) and A-3 (auditability via the "satisfactory explanation" requirement).
**Concept Register update:** new concept — *contemporaneity requirement / no post-hoc rationalization* (AL, LOW/1 this run). **Relationship note (concept↔concept, NOT a PublicDigit mapping):** this concept appears related to iteration-1's *commit-before-sample* (S4, election science) and *declare-failure obligation* (S1) — both forbid retrospective reconstruction of a record or its justification. Kept as a **noted relationship**, not merged into one register row (merging is a clustering judgment reserved for the synthesis phase).
**Category:** FACT + a flagged cross-iteration relationship (INTERPRETATION, weak — single relationship note, not a promotion).
**Open question:** none beyond the general transfer question.

**Source cluster 4 — Standards of review: a differentiated taxonomy, not one concept**
**Sources:** *Standard of review* (Wikipedia, secondary) · Hawaii Law Library standards-of-review guide (secondary) · Seattle U. L. Rev. primer (secondary), cross-corroborated against primary case law in the verifier evidence (*Consolidated Edison v. NLRB* 1938; *Biestek v. Berryhill* 2019). **Discipline:** Administrative Law (with constitutional-challenge framing).
**Key claim (FACT, confirmed via 5 merged sub-claims):** "standard of review" is not a single unified concept but a differentiated taxonomy of distinct doctrinal instruments — *substantial evidence* (scrutinizes the record itself, a deliberately low bar: "more than a scintilla"), *abuse of discretion* (scrutinizes decision-maker conduct), *de novo* (unconstrained re-examination of law), *clearly erroneous*/*arbitrary-and-capricious* (overall soundness) — each keyed to a **different object of scrutiny**; the appellate standard-of-review functions structurally the same role one level up that burden-of-proof does at trial level.
**Assumption impact:** **DIRECTLY CONTRADICTS A-9** (evidence as a single concept) — independently of, and via entirely different legal mechanisms than, iteration 1's election-science splits (custody vs certification, auditability vs auditing). This is a **second, independent discipline** now contradicting A-9 via its own internal structure.
**Concept Register update:** new concept — *differentiated standard-of-review taxonomy* (AL, and arguably CL via the constitutional-challenge angle — LOW/2 disciplines this run: AL + CL).
**Category:** FACT. **RECOMMENDATION (flagged, not acted on):** track A-9 contradictions as a running tally across disciplines — four independent splits now (2 from ES, this one spanning AL/CL) — but no BC or clustering conclusion drawn.
**Open question:** none.

**Source cluster 5 — German comparative law: hearing right broader than U.S. due process**
**Sources:** BMI official English translation of VwVfG (primary, cross-checked against the German original on gesetze-im-internet.de) · Pünder, I·CON 2013 (primary, peer-reviewed). **Discipline:** Administrative Law (comparative/civil law).
**Key claim (FACT, 3-0):** §28(1) VwVfG requires the opportunity to comment before any adverse administrative act, and **extends this hearing right to third parties whose rights are affected** — broader than U.S. doctrine, where third-party hearing rights are "nebulous" and "in principle never granted" in public-benefits contexts.
**Assumption impact:** directly relevant to the **standing transfer question**: shows a specific procedural-fairness property (breadth of who must be heard) that is **not even uniform across public-law systems** (Germany > U.S.) — a caution against assuming any single "evidence/fairness" package transfers uniformly to private-organization governance.
**Concept Register update:** new concept — *rechtliches Gehör / third-party hearing right* (AL, LOW/1).
**Category:** FACT.
**Open question:** the transfer question itself — unresolved.

**Source cluster 6 — Finality ≠ immutability (Bestandskraft) + procedural curing rules (NEGATIVE FINDING)**
**Sources:** BMI VwVfG translation (primary, cross-checked) · Pünder, I·CON 2013 (primary). **Discipline:** Administrative Law (comparative/civil law).
**Key claim (FACT, 3-0, both sub-claims):** German law formally **decouples** finality-against-challenge (*Bestandskraft*, non-appealability) from immutability of the determination — an unlawful act can become non-appealable while remaining substantively unlawful and revisable by the issuing authority itself, retrospectively or prospectively (§48 VwVfG); separately, procedural errors are treated as **curable and legally irrelevant** once corrected or shown not to have affected the outcome (§§45–46 VwVfG).
**Assumption impact:** WEAKENS a strict reading of A-2 (immutability) — finality and immutability are explicitly NOT the same property in this legal system. SUPPORTS A-10 (a correction/adjudication loop exists structurally). **Mandatory negative finding:** the scholarship (citing former Federal Administrative Court president Sendler) documents that this curing mechanism is exploitable — "some administrative bodies purposefully deny citizens the possibility to state their arguments, knowing it is unlikely that citizens will seek legal remedy" — and some German scholars consider the curing rule potentially unconstitutional. **The correction loop, as actually practiced in this legal system, is documented as a vector for weakening ex-ante procedural rigor, not purely a safety valve.**
**Concept Register update:** new concepts — *finality-vs-immutability decoupling (Bestandskraft)* and *procedural curing rules (Heilung)* (both AL, LOW/1) — the latter flagged negative-finding.
**Category:** FACT + mandatory negative finding.
**Open question:** what design property (in any domain, not just software) would prevent a correction mechanism from being exploited as a laxity incentive? Left open — no PublicDigit mapping drawn.

**Source cluster 7 — Legitimacy ≠ justice (single-source, downgraded confidence)**
**Source:** Hickey, Oxford J. Legal Studies 2022, via NCBI/PMC (peer-reviewed). **Discipline:** Constitutional Law.
**Key claim (FACT, 3-0, confidence downgraded to MEDIUM by the verification harness because it rests on one article):** legitimacy (propriety of *how* a decision comes about) and justice (substantive correctness of the *outcome*) are conceptually distinct evaluative axes for institutional decisions; a decision can be legitimate — and therefore survive institutional/constitutional challenge — without being substantively "just," and it is **legitimacy, not justice, that is the pre-eminent consideration for judicial review**.
**Assumption impact:** **introduces a new concept orthogonal to A-1/A-2/A-3/A-6/A-9/A-10** — it reframes the research question itself: trust in a governance platform may track *process-legitimacy*, not *outcome-justice*.
**Concept Register update:** new concept — *legitimacy vs. justice (distinct evaluative axes)* (CL, LOW/1, single-source caveat noted).
**Category:** FACT + INTERPRETATION (the reframing).
**Open question:** does this reframing survive when Tyler-style empirical procedural-justice literature is actually reached (deferred — coverage gap above)?

### Refuted claims (recorded — mandatory, so they are not repeated)

| Claim | Vote | Why it matters |
|---|---|---|
| Constitutional-challenge review is confined strictly to the administrative record | 0-3 | Overreach of the statutory-APA record rule into constitutional review generally |
| German law imposes a *general* statutory Begründungspflicht stronger than U.S. reason-giving | 0-3 | An intuitively plausible comparative-law claim that does NOT hold as stated |
| §39(1) VwVfG imposes a general duty to give reasons for administrative acts (as characterized) | 0-3 | The specific statutory characterization overreached |
| English courts categorically exclude late-reconstructed justifications | 1-2 | Common-law-outside-US claim; unconfirmed, not disproven — absence of evidence |
| *R (Bradley) v SSWP* as a clean example of reason-giving failure | 1-2 | Case-specific claim did not survive re-examination |
| "Reciprocity of justification" is constitutive of legitimacy (citizens must recognize the reasons as reasonable) | 0-3 | The BROADER legitimacy-vs-justice framing (cluster 7) survived; this SPECIFIC normative extension from the same source did not |
| Arbitrary-and-capricious review = merely "did the agency consider the relevant factors" | 1-2 | Oversimplification of a more nuanced standard |

**INTERPRETATION:** the refutation rate (7 of 25 voted claims, 28%) is itself evidence the verification process is discriminating, not rubber-stamping — consistent with iteration-1's honesty standard.

## Cross-iteration concept-relationship notes (concept↔concept only — no clustering, no BC naming)

- *Contemporaneity requirement* (AL, this iteration) relates to *commit-before-sample* and *declare-failure* (ES, iteration 1) — both forbid retrospective reconstruction of record or justification. **Not merged** (clustering deferred).
- *Differentiated standard-of-review taxonomy* (AL/CL) independently reinforces the A-9-weakening direction already established by ES's custody/certification and auditability/auditing splits — a **second, unrelated discipline** contradicting A-9 by its own internal logic. Four independent A-9-weakening signals now stand across two disciplines.
- *Finality-vs-immutability decoupling* (AL) and *the correction loop's exploitability as documented in German administrative law* relate conceptually to ES's *declare-failure obligation* and *evidence co-production* — all concern what happens when a correction/audit mechanism exists but is not exercised rigorously. **Not merged.**

## Stopping criterion

**NOT REACHED.** Iteration 2a added 9 new concepts (register: `EPIC-002_Concept_Register.md`) across 2 disciplines with 0 register-row promotions this iteration (new concepts enter fresh, per the anti-clustering-bias rule — merging iteration-1 and iteration-2a concepts is a clustering judgment explicitly deferred). Separately, the **assumption table** shows A-9 weakened by an independent second discipline. **Coverage gaps carried forward:** Tyler-style procedural-justice literature (requested, not reached) · private-organization transfer question / Vereinsrecht (requested, not reached) · common-law-outside-US (one source tested, refuted). Iteration 2b targets next: Governance Theory + Trust Engineering (assurance/safety cases, NIST, dependability) per the charter's discipline order.

---
*Charter: `EPIC-002_Problem_Statement.md` · Runs: `wf_588d4d47-8d6` (iteration 1, resumable) · `wf_4c2904f1-602` (iteration 2a attempt 1, FAILED — 0 sources, Fable-5 session limit) · `wf_e118e84a-441` (iteration 2a attempt 2, VERIFIED, under Sonnet 5) · Labels per charter: FACT / INTERPRETATION / RECOMMENDATION / OPEN QUESTION, never collapsed.*
