# EPIC-002 Literature Review — Constitutional Trust Discovery (Working Document)

**Status:** IN PROGRESS — iteration 1 complete (unverified); iteration 2a (Constitutional + Administrative Law) COMPLETE AND VERIFIED (18 confirmed / 7 refuted); **iteration 2b (Digital Forensics + W3C PROV + Trust Engineering/Assurance) COMPLETE AND VERIFIED** on retry (run `wf_9986ccdd-189`, after a first attempt `wf_97560c79-715` failed at the decomposition step — schema validation loop, `/angles: must NOT have fewer than 3 items`, root-caused and fixed by loosening the discipline-count phrasing; recorded as history below). Iteration 2b: 26 sources, 106 claims extracted, 25 adversarially voted, **10 CONFIRMED → 7 synthesized findings**, **15 REFUTED** (highest refutation rate yet — recorded, not repeated), 0 stuck unverified. **Digital Forensics gap BROKEN by iteration 2c** (isolated run: 8 confirmed / 5 refuted / 12 unverified — see §Iteration 2c; the earlier combined-run wipeout stands as history). **STOPPING CRITERION condition (2) TRIGGERED THIS ITERATION** — a repeated cross-disciplinary phenomenon was observed (evidence-substrate vs. argument-over-evidence separability), flagged as a candidate conceptual category requiring synthesis; see below. *(Wording tightened, ARB 2026-07-12.)* **Authority:** generated — research artifact under the charter `EPIC-002_Problem_Statement.md`; never authoritative without ARB review.
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
**Assumption impact:** independent evidence consistent with A-6 (causal/temporal ordering — justification must precede or accompany the decision, never be reconstructed afterward) and A-3 (auditability via the "satisfactory explanation" requirement). *(Wording corrected, ARB 2026-07-11 — administrative-law doctrine does not "prove" the internal assumption; it is one more independent data point consistent with it.)*
**Concept Register update:** new concept — *contemporaneity requirement / no post-hoc rationalization* (AL, LOW/1 this run). **Relationship note (concept↔concept, NOT a PublicDigit mapping):** this concept appears related to iteration-1's *commit-before-sample* (S4, election science) and *declare-failure obligation* (S1) — both forbid retrospective reconstruction of a record or its justification. Kept as a **noted relationship**, not merged into one register row (merging is a clustering judgment reserved for the synthesis phase).
**Category:** FACT + a flagged cross-iteration relationship (INTERPRETATION, weak — single relationship note, not a promotion).
**Open question:** none beyond the general transfer question.

**Source cluster 4 — Standards of review: a differentiated taxonomy, not one concept**
**Sources:** *Standard of review* (Wikipedia, secondary) · Hawaii Law Library standards-of-review guide (secondary) · Seattle U. L. Rev. primer (secondary), cross-corroborated against primary case law in the verifier evidence (*Consolidated Edison v. NLRB* 1938; *Biestek v. Berryhill* 2019). **Discipline:** Administrative Law (with constitutional-challenge framing).
**Key claim (FACT, confirmed via 5 merged sub-claims):** "standard of review" is not a single unified concept but a differentiated taxonomy of distinct doctrinal instruments — *substantial evidence* (scrutinizes the record itself, a deliberately low bar: "more than a scintilla"), *abuse of discretion* (scrutinizes decision-maker conduct), *de novo* (unconstrained re-examination of law), *clearly erroneous*/*arbitrary-and-capricious* (overall soundness) — each keyed to a **different object of scrutiny**; the appellate standard-of-review functions structurally the same role one level up that burden-of-proof does at trial level.
**Assumption impact:** independent evidence in tension with A-9 (evidence as a single concept) — via legal mechanisms entirely distinct from iteration 1's election-science splits (custody vs certification, auditability vs auditing). This is a **second, independent discipline** whose own internal structure does not treat evidence/scrutiny as one concept — not proof against A-9, but a second data point in the same direction. *(Wording corrected, ARB 2026-07-11.)*
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

## Iteration 2b — Digital Forensics + W3C PROV + Trust Engineering/Assurance (VERIFIED)

**Coverage note (honest, MAJOR gap):** **zero Digital Forensics claims survived adversarial verification** — every candidate claim about ISO/IEC 27037/27041, ACPO/NIST guidelines, or Daubert/Frye admissibility was refuted, several unanimously (0-3), including a suspiciously precise single-study statistic ("18% documentation improvement, 100% vs. 82% baseline admissibility") that the verifiers correctly killed. **This means Discipline (A), explicitly requested, is empirically unaddressed this iteration** — an open question below asks whether this is a genuine literature gap or a search-strategy limitation. **Separately:** NIST-specific assurance-case definitions (SP 800-160, CNSSI 4009) were also refuted despite explicit request — but the underlying claim/argument/evidence-separation concept survived via non-NIST sources (safety-critical-systems literature), so the *concept* is present in the register without a NIST citation.

### Confirmed findings (10 claims → 7 synthesized; per-source format)

**Finding 1 — PROV decomposes evidence into typed primitives, not one concept**
**Sources:** W3C PROV-DM, PROV-O (primary Recommendations) · Moreau et al., J. Web Semantics 2015 (primary, PROV co-chairs' design rationale). **Discipline:** Provenance/Lineage.
**Key claim (FACT, confirmed 2-1/2-1/3-0):** PROV-DM/PROV-O decompose "evidence/provenance" into three ontologically distinct role-typed primitives — Entity (fixed-aspect thing), Activity (occurs over time, acts on entities), Agent (bears responsibility) — plus a relation family (generation, usage, derivation, attribution, delegation, invalidation). The design-rationale paper confirms this is deliberate architecture, not accident; the decomposition is echoed verbatim by derivative standards (e.g. IVOA Provenance DM) outside the original W3C context.
**Assumption impact:** this discipline (Provenance/Lineage) observed a structure — typed data-model primitives, not legal doctrine — that appears inconsistent with A-9 (evidence as one concept). This is the **third discipline** (after ES and AL/CL) to independently observe such a structure, each via its own distinct mechanism. *(Whether A-9 should be considered weakened is a synthesis judgment, reserved for the ARB — not asserted here.)*
**Concept Register update:** new concepts — *Entity/Activity/Agent typed decomposition* and *generation/usage/derivation/attribution/delegation relation family* (PR, corroborated across 2 independent standards bodies within the discipline — W3C + IVOA).
**Category:** FACT.
**Open question:** none.

**Finding 2 — PROV leaves derivation-adjudication deliberately unspecified**
**Source:** W3C PROV-DM (primary). **Discipline:** Provenance/Lineage.
**Key claim (FACT, confirmed 3-0):** PROV states a derivation "is considered to have been determined by unspecified means" — even *Revision* (the closest concept to a correction) is defined structurally with no validation/adjudication mechanism attached.
**Assumption impact:** this discipline observed that recording lineage does not itself constitute or guarantee an adjudication/correction loop — a structure that appears inconsistent with A-10 as a universal property. *(Observation, not a synthesis verdict on A-10.)*
**Concept Register update:** new concept — *unspecified adjudication mechanism (derivation validity left external)* (PR, single-source).
**Category:** FACT.
**Open question:** what concretely fills this gap in practice (peer review, judicial process, structured argument), and does the filling mechanism vary by jurisdiction/domain? (Carried to next iteration.)

**Finding 3 — PROV's own scope boundary separates lineage-data from trust-judgement (the standing question, first direct hit)**
**Source:** W3C PROV-DM (primary). **Discipline:** Provenance/Lineage.
**Key claim (FACT, confirmed 2-1 — split vote reflects genuine interpretive disagreement about how far to extend this, not a factual dispute):** PROV acknowledges lineage records can help humans make trust judgements, but the standard stops short of formally modeling trust or evidential weight — it captures the lineage substrate only, leaving any argument about trustworthiness to unspecified downstream reasoning.
**Assumption impact:** the **first direct evidence this iteration for the standing question** — "evidence" (recorded lineage data) is conceptually separable from "argument/trust-judgement over evidence," with PROV's own documented scope boundary drawn exactly at that line.
**Concept Register update:** new concept — *evidence-substrate vs. trust-judgement scope boundary* (PR, MEDIUM confidence — contested interpretive extension, single primary source).
**Category:** FACT + INTERPRETATION (the "this is the standing-question answer" framing is interpretive, flagged as such by the split vote).
**Open question:** the standing question itself — see synthesis note below.

**Finding 4 — Causal ordering (PROV) is a quasi-order, explicitly decoupled from timestamp ordering**
**Source:** W3C PROV-Sem (primary, formal semantics). **Discipline:** Provenance/Lineage.
**Key claim (FACT, confirmed 2-1):** PROV formally models causal/derivation ordering as a quasi-order (not a strict total order) and **explicitly decouples it from timestamp ordering** — the spec's own worked example shows chronological time-value order and causal-derivation order can even conflict (mutual precedence under differing timestamps).
**Assumption impact:** this discipline observed that causal/derivation ordering and timestamp ordering are formally distinct (and can conflict) in the leading provenance standard — a structure that nuances A-6 (causal ordering). *(Observation; synthesis reserved for the ARB.)*
**Concept Register update:** new concept — *causal ordering as quasi-order distinct from timestamp ordering* (PR, single-source, MEDIUM confidence).
**Category:** FACT.
**Open question:** none this iteration (echoed in distributed-systems logical-clock literature per the research but not independently confirmed here — future iteration candidate).

**Finding 5 — Assurance cases trace to heterogeneous artifacts unified only by traceability links (second independent discipline weakening A-9)**
**Source:** Wei et al., J. Systems and Software 2024 (primary, peer-reviewed). **Discipline:** Trust Engineering/Assurance.
**Key claim (FACT, confirmed 3-0):** model-based assurance cases in safety-critical engineering trace to heterogeneous engineering artifacts (architectural models, safety analyses, behaviour models, spanning multiple modeling languages/tools) unified only by traceability links, not by being one uniform kind of artifact — the paper states directly that "assurance cases are not self-contained documents."
**Assumption impact:** this discipline (Trust Engineering) observed a structurally analogous but mechanistically distinct pattern (traceability across heterogeneous artifact types, vs. PROV's typed primitives, vs. legal doctrine's custody/certification/scrutiny splits) that also appears inconsistent with A-9. **Four disciplines (ES, AL/CL, PR, TE) have now each independently observed a structure inconsistent with A-9, via four unrelated mechanisms** — recorded as observation; whether this settles A-9 is a synthesis judgment.
**Concept Register update:** new concept — *traceability across heterogeneous artifact types* (TE, single-source; relationship noted to PR's Finding 1, not merged).
**Category:** FACT.
**Open question:** none.

**Finding 6 — Confirmation bias in assurance-case construction/review (NEGATIVE FINDING)**
**Sources:** Gohar, Hunter, Cohen, Lutz — 20-year systematic literature review, arXiv:2502.00238 (primary) · Habli, Alexander, Hawkins, SSS'21 (primary). **Discipline:** Trust Engineering/Assurance.
**Key claim (FACT, confirmed 3-0/3-0):** confirmation bias in the manual, judgment-driven construction and review of assurance-case arguments is a documented, credible mechanism (explicitly distinguished by the field's own researchers from mere "false-premise" objections) by which a case can pass formal review/certification while still harboring undetected defeaters — with real-world consequences cited (RAF Nimrod MR2 XV230 loss).
**Assumption impact:** **mandatory negative finding.** Certification/passing-review is not proof the underlying evidentiary/argumentative basis is sound — a documented failure mode structurally distinct from any hash/custody-based integrity failure (which this iteration could not independently confirm at all, per the forensics coverage gap above).
**Concept Register update:** new concept — *confirmation bias as a credible assurance-case failure mode ("assurance theater")* (TE, corroborated by 2 independent primary sources within the discipline).
**Category:** FACT + mandatory negative finding.
**Open question:** does an analogous failure mode exist in digital-forensics chain-of-custody practice? Unanswerable this iteration (forensics gap).

**Finding 7 — No rigorous empirical evidence that assurance cases deliver net trust/safety value (NEGATIVE FINDING)**
**Source:** Habli, Alexander, Hawkins, SSS'21 (primary, peer-reviewed). **Discipline:** Trust Engineering/Assurance.
**Key claim (FACT, confirmed 3-0):** there is no rigorous empirical evidence base demonstrating that safety cases (structured argument-based assurance) actually deliver net safety/trust value compared to alternatives — the field has produced extensive *methodological* research (notations, formalisms, automation, patterns) but almost no *outcome* evaluation, a gap the field's own leading researchers call "poor" and an "impending crisis."
**Assumption impact:** **mandatory negative finding, and a sobering one.** A structured-argument approach to trust can be methodologically mature while remaining empirically unproven at the level that matters (does it actually work?). This is a general concern — applies whenever any assurance mechanism is asked to justify itself by results rather than procedural compliance alone.
**Concept Register update:** new concept — *empirical outcome evaluation of an assurance mechanism (as distinct from procedural/methodological maturity)* (TE, single-source, Universal in character per the finding's own framing — but held at Candidate per convention, evidence pending a second discipline).
**Category:** FACT + mandatory negative finding.
**Open question:** does the same evidence-practice gap (methodological maturity without outcome evidence) extend to provenance/lineage systems generally? Open.

### Refuted claims (15 — the highest refutation rate yet; recorded so they are not repeated)

| Claim (abbreviated) | Vote | Why it matters |
|---|---|---|
| ISO 27037/27041 implementation empirically improves traceability/documentation/robustness (multi-case study) | 1-2 | Entire forensics angle — did not survive |
| Same study: 18% documentation improvement, 100% vs. 82% baseline admissibility | 0-3 | A suspiciously precise single-study statistic — correctly killed; a caution against over-trusting quantified claims from one source |
| PROV Working Group treated "ease of use" vs. "validity-checking" as competing design axes (weak analogue of evidence/argument separation) | 0-3 | Body text was inaccessible to verifiers — appropriately killed for insufficient basis |
| PROV-O scopes out quality/trust/evidential-weight judgments entirely | 0-3 | Overreach — a MORE MODEST version of this point survived as Finding 3 (medium confidence) |
| PROV frames provenance as one metadata subtype among others (Weakens A-9 + Introduces) | 0-3 | Overreach on source text |
| PROV explicitly builds temporal ordering as a mechanism (Supports A-6) | 0-3 | Refuted — ironically, the OPPOSITE nuance (quasi-order ≠ timestamp order) survived as Finding 4 |
| Derivation defined with no correction/adjudication notion (Weakens A-10 + standing question) | 0-3 | Overreach in this specific wording — the underlying point survived, more narrowly, as Finding 2 |
| PROV-Sem excludes trust/reliability assessment explicitly | 1-2 | Overreach — Finding 3 carries the surviving, more modest version |
| NIST SP 800-160/ISO 15026-1: assurance case = claim + argument + evidence (3 separable elements) | 1-2 | NIST-specific definitional claim did not survive — concept survives via non-NIST sources (Finding 5/6/7) |
| NIST CNSSI 4009 / SEI: "arguments paired with supporting evidence" | 0-3 | Same NIST-sourcing issue |
| Assurance cases are a communication/confidence structure distinct from system properties they argue about | 0-3 | Overreach in this specific framing |
| GSN: evidence nodes vs. claims nodes, defeaters distinguished by node type attacked | 0-3 | The MOST DIRECTLY on-point claim for the standing question — refuted. Do not treat the standing question as settled by GSN-specific structure; Finding 3/5 carry the surviving basis |
| Seven-category defeater taxonomy (20-yr review) — evidence-validity is one of seven | 0-3 | Overreach in this specific framing |
| No evidence safety cases are revised after real incidents (correction-loop absence) | 0-3 | Interesting claim, did not survive — remains genuinely open, not established either way |
| Rushby's NLD: leaves (evidence, probabilistic) vs. interior nodes (reasoning, deductive) | 1-2 | Another direct-hit claim for the standing question that did NOT survive — same caution as the GSN row above |

**INTERPRETATION (important, and self-illustrating):** the refutation rate this iteration (15 of 25 voted claims, 60%) is markedly higher than iteration 2a's 28%. Notably, **several of the most directly on-point claims for this iteration's own standing question were refuted** (GSN evidence/claims nodes, Rushby's NLD leaves/interior split) while a more modestly-worded, less specific version of essentially the same phenomenon survived (Finding 3, medium confidence, split vote). This is the adversarial-verification process enforcing, empirically, exactly the wording discipline the ARB required by charter amendment: the underlying pattern (evidence separable from argument-over-evidence) is real but was frequently over-claimed in its strongest forms.

## Cross-iteration concept-relationship notes (concept↔concept only — no clustering, no BC naming)

- *Traceability across heterogeneous artifacts* (TE, Finding 5) relates to *Entity/Activity/Agent typed decomposition* (PR, Finding 1) — both weaken A-9 via structurally analogous but mechanistically distinct patterns. **Not merged.**
- *Unspecified adjudication mechanism* (PR, Finding 2) relates to iteration 2a's *finality-vs-immutability decoupling / procedural curing rules* (AL) — both concern what happens (or doesn't happen) when a correction/adjudication mechanism is supposed to exist. Three disciplines now (ES declare-failure, AL Bestandskraft, PR unspecified-means) circle the same structural question: **is an adjudication loop automatic, reliable, or neither?** Literature answer so far: neither — it is external (PR), gameable (AL), and its outcome-effectiveness is empirically unproven where studied at all (TE, Finding 7). **Not merged — this is a note, not a cluster.**
- The **standing question** (evidence vs. argument-over-evidence) has now been independently observed in 2 disciplines (PR Finding 3, medium confidence; TE Findings 5–7, though the most direct TE claims for this exact framing were refuted). Per the ARB's tightened wording (2026-07-12): this is a **repeated cross-disciplinary phenomenon** → **candidate conceptual category** → **requires synthesis**. It is NOT yet a settled category, and it is not asserted that the phenomenon is real merely because it recurred twice — the recurrence is the observation; the interpretation is reserved.

## Stopping criterion

**NOT REACHED on either condition.** Condition (1): iteration 2b added 7 new concepts, 0 register-tier promotions (fresh entries per the anti-clustering rule). Condition (2): **TRIGGERED** — a repeated cross-disciplinary phenomenon was observed (evidence-substrate vs. argument-over-evidence separability, independently observed in 2 disciplines this iteration), flagged as a candidate conceptual category requiring synthesis. This by itself is sufficient to continue past this iteration under the refined two-part criterion, independent of promotion-frequency bookkeeping. **Major open item carried forward:** the Digital Forensics coverage gap (0 surviving claims) — ARB decision needed on whether to retry forensics specifically or accept the gap and proceed to ElectionGuard/E2E-Verifiable Voting per the roadmap.

## Iteration 2c — Digital Forensics, in isolation (PARTIALLY VERIFIED — the gap is broken)

**Run:** `wf_18bdf7c3-331`. **Isolation strategy vindicated:** run alone (not crowded by PROV/assurance angles), Digital Forensics produced **8 adversarially confirmed claims** where the combined run had produced zero. **Infrastructure failure #4 (honest):** the session limit struck mid-verification again — 36 verifier panels + the harness's own synthesis step failed (resets 13:10 Berlin); the 8 confirmed claims arrived unmerged and are synthesized manually below; **12 further claims remain [EXTRACTED-UNVERIFIED]** (ISO/IEC 27037 scope statements · Daubert/Frye definitions · NISTIR 8006 cloud-custody challenge · the at-rest/in-motion/in-execution taxonomy), queued for re-verification.

### Confirmed findings (8 claims, observation wording per the tightened convention)

**Source cluster 1 — NIST SP 800-86: custody log + multi-point hash verification**
**Sources:** NIST SP 800-86 (primary, official publication + PDF, cross-confirmed). **Discipline:** Digital Forensics (US federal guidance).
**Key claims (FACT, 3-0 / 2-1 / 3-0 / 3-0):** chain of custody is operationally defined as a person-attributed, time-stamped log of every custodian and action, secure storage, analysis only on a copy, and integrity verification of both original and copy. Integrity verification is a **multi-point** cryptographic hash comparison: digest of the original BEFORE imaging → digest of the copy AFTER imaging (compared) → digest of the original AGAIN (to prove acquisition itself altered nothing).
**Observations (not verdicts):** this source's structure appears consistent with A-3 (the custody log IS an auditability mechanism) and with A-2 only in the weaker sense — **hashing makes alteration detectable; it does not prevent it.** The source's own framing is tamper-EVIDENCE, not immutability.
**Concept Register update:** *custody log (person-attributed, time-stamped possession sequence)* — Observed in DF (US federal guidance) — Candidate Universal (evidence pending) — Normative. *Multi-point message-digest comparison* — Observed in DF — Candidate Universal (hashing is not jurisdiction-bound) — Descriptive with normative force.
**Category:** FACT + observation.

**Source cluster 2 — NIST's own legal-sufficiency disclaimer (NEGATIVE FINDING)**
**Source:** NIST SP 800-86 (primary). **Discipline:** Digital Forensics.
**Key claim (FACT, 3-0):** the guide explicitly disclaims being a complete investigation manual, legal advice, or a basis for criminal investigations.
**Observation (negative finding):** direct primary-source evidence that **a technical/procedural forensic guide does not itself claim to establish legal admissibility** — procedural conformance alone does not guarantee legally sufficient evidence. The technical evidence-handling layer explicitly draws a boundary short of the legal-use/argument layer.
**Concept Register update:** *scope-limitation disclaimer (technical guidance ≠ legal sufficiency)* — Observed in DF (US federal publication) — Candidate Jurisdiction-specific (evidence pending) — Normative.
**Category:** FACT + mandatory negative finding. **Recurrence note (cautious, hedged):** this is an *indirect* echo — a scope disclaimer, not a conceptual treatment — of the evidence-substrate/argument-over-evidence phenomenon observed in PR and TE; recorded in the recurrence table as a hedged third observation, explicitly weaker in kind than the other two legs.

**Source cluster 3 — Live acquisition alters evidence (SWGDE)**
**Source:** SWGDE Best Practices for Digital Evidence Collection 18-F-002-2.0 (primary practitioner standard). **Discipline:** Digital Forensics.
**Key claim (FACT, 2-1):** the standard states that live collection of data **can itself alter and create evidence**, and requires this alteration to be documented rather than assumed away — a structural distinction between at-rest and live/volatile evidence.
**Observations:** this source's structure appears inconsistent with a naive reading of A-2 ("evidence never changes once identified") — for one category of evidence, the standard concedes collection is evidence-altering and substitutes **documented-alteration** for immutability. It also appears inconsistent with A-9 at the handling level: live/volatile collection is a separately named, differently handled kind.
**Concept Register update:** *live acquisition alters evidence (documented-alteration substitutes for immutability)* — Observed in DF (US practitioner standard) — Candidate Universal within digital forensics (evidence pending, single source) — Descriptive + Normative.
**Category:** FACT + observation.

**Source cluster 4 — SWGDE position on MD5/SHA1 (preimage vs. collision resistance)**
**Source:** SWGDE official position document (primary). **Discipline:** Digital Forensics.
**Key claims (FACT, 3-0 / 2-1):** SWGDE's official position is that MD5 and SHA1, despite known cryptographic weaknesses, remain acceptable for integrity verification and file identification — justified by distinguishing the property forensics needs (**preimage resistance**: you cannot craft a different file matching a pre-existing, already-fixed hash) from the property that is broken (**collision resistance**: crafting two new files with the same hash).
**Observations:** the trust argument here is *property-specific*, not algorithm-generic — what matters is which cryptographic property the evidentiary use actually depends on. No assumption in the register is directly touched; recorded as a concept.
**Concept Register update:** *property-specific hash-trust argument (preimage vs. collision resistance)* — Observed in DF (US practitioner standard) — Candidate Domain-specific (evidence pending) — Descriptive.
**Category:** FACT.

### Refuted claims (5 — recorded, not repeated)

| Claim (abbreviated) | Vote | Note |
|---|---|---|
| SWGDE mandates multi-algorithm "acquisition hash" specifically against collisions | 0-3 | Overreach of the source text |
| Distinct "verification hash" re-executed at reexamination transitions | 1-2 | Not supported as stated |
| SWGDE custody documentation requires unique ID + exact timestamps + signatures per transfer | 0-3 | Over-specified relative to the source |
| Cloud provider hardware cannot be seized; evidence via legal process/API instead | 1-2 | Plausible but not confirmed from the cited source |
| Write-blockers impractical in cloud; provider logs substitute | 0-3 | Overreach |

### Unverified (12 claims — [EXTRACTED-UNVERIFIED], re-verification queued)

ISO/IEC 27037's self-scoping (identification/collection/acquisition/preservation only; admissibility explicitly deferred to jurisdictions — *if verified, this would be another hedged echo of the substrate/argument boundary*) · the fragility-of-digital-evidence rationale · Daubert (testability, peer review, error rate, acceptance) vs. Frye (general acceptance) definitions · NISTIR 8006: cloud chain-of-custody "may be impossible to verify" (FC-24) · the at-rest/in-motion/in-execution evidence-state taxonomy · EC2-vs-S3 integrity-verifiability inconsistency · ISO.org primary pages bot-blocked (403) — paywall/mirror reliance explicitly flagged by the harness itself.

**Coverage note (honest):** custody-chain failures in real court cases (angle D) produced no confirmed claims — the documented-failure angle remains thin; Daubert/Frye (angle C) is extracted but unverified. Both carried forward.

## Iteration 3a (Evidence Set v1, SUPERSEDED) — ElectionGuard + E2E-Verifiable Voting — **INVALID (history only; see the VALID v2 rerun below)**

**Runs:** `wf_3f9f7018-a37` (first attempt: total verification-panel rate-limit failure, 25/25, resets-at-midnight Berlin) → resumed (same run ID; task-tracking ID `wg40ljxqs`) (second attempt: Extraction PASS, Verification PASS, **Synthesis FAILED** — the harness's final aggregation call returned a literal placeholder stub instead of merged confirmed findings).

**Per the Research Workflow Qualification rule (ARB, 2026-07-13, refined same day): this iteration is INVALID, not a negative result.** (Evidence Set retained: the 7 refuted claims below passed their own stage — Verification — intact; only Synthesis failed.)
- **Positive evidence status: UNKNOWN.** **Reason: the workflow failed before positive findings could be synthesized** — this is a statement about the workflow, not about ElectionGuard/E2E-VV literature.
- Refuted claims: **7 retained as legitimate content** (refutation-recording did not depend on the broken synthesis stage) — recorded below for completeness, but **do not treat their existence as evidence the phenomena were tested and passed**; the confirming half of the same test is simply missing.
- **Recurrence table: NOT updated.** No phenomenon's status changes because of this iteration. No architectural hypothesis is touched.

### Refuted claims retained (7 — genuine content, quoted, from the working verification stage)

| Claim (abbreviated) | Vote | Phenomenon relevance |
|---|---|---|
| ElectionGuard's color-coded verification groupings (orange/key-gen, blue/ballot-correctness, green/election-record) constitute the P1 evidence taxonomy with distinct verifier audiences | (refuted — overreach beyond the cited quote) | P1 — claim overreached; the color-coding concept itself may still hold, but this specific "distinct audiences" framing did not survive as stated |
| Contemporaneity enforced by construction — casting/challenging only possible after the confirmation code exists, "never before" | 1-2 | P3 — did not survive as an unqualified claim |
| Discrepancy resolution (Preston, Idaho) was manual/human, not automatic | 0-3 | P2 — did not survive as stated (though the underlying manual-resolution fact is separately corroborated in unverified extraction text) |
| ElectionGuard substitutes public record-publication for custody; not eliminated system-wide (paper retained, guardian-ceremony trust residue) | 1-2 | P5 — nuanced counterexample candidate did not survive as stated |
| v2.0 spec legitimizes post-hoc aggregation of per-guardian partial decryptions into single artifacts, removing individual contributions from the published record | 0-3 | P3/P4 — the strongest counterexample candidate in this run did NOT survive verification |
| Hash-chain binding of ballots + device identity at cast time performs the "custody" role without a named custody practice | 0-3 | P3/P5 |
| Verifier failure requires external human review to localize fault (record vs. verification mechanism) | (vote not fully captured before synthesis broke) | P2 |

**INTERPRETATION (bounded):** every one of this run's most falsification-relevant candidate counterexamples (post-hoc guardian-share aggregation for P3/P4; custody substitution for P5) was **refuted**, not confirmed. This narrows what iteration 3a can honestly claim to almost nothing — refutation of a *claim about a counterexample* is not the same as failure of the counterexample search itself, and the confirming/supporting evidence stream that would tell us what DID hold up is exactly what synthesis lost. **No conclusion about P1–P5 survival is drawn from this iteration.**

**Next step (per ARB ruling):** do NOT immediately rerun the ElectionGuard/E2E-VV search. First confirm a clean run of the research workflow (Extraction → Verification → Extraction Audit → Synthesis, all passing) on a small scope, before treating any further output as valid research data.

## Iteration 3a (Evidence Set v2, rerun on the qualified workflow) — ElectionGuard + End-to-End Verifiable Voting — **VALID, falsification mode**

**Run:** `wf_e967a2c1-d6e`, fresh (not resumed from the invalid `wf_3f9f7018-a37`). **Extraction Audit (performed before writing this section, per the Research Workflow Qualification rule):** 105/105 agents, 0 errors; `findings` contains 6 real entries with quotes/sources/votes (no placeholder content); `confirmed` count in stats (17) reconciles with the synthesized 6 + the 8 refuted + budget-dropped remainder. **PASS — this Evidence Set is valid; iteration 3a v1 stands as permanent invalid history.**

**Evidence family (new):** this run introduces a **sixth Independent Evidence Family — cryptographic-protocol analysis** (ElectionGuard spec/EAC pilot report, Cortier & Smyth formal analysis, Helios documentation) — genuinely distinct from statistical verification (ES), judicial-review doctrine (AL/CL), ontology (PR), safety-assurance argumentation (TE), and forensic procedure (DF).

### Per-phenomenon falsification results (observations, not verdicts)

**P1 — evidence is structurally plural/multi-kind: NO COUNTEREXAMPLE FOUND.** Every source examined (Benaloh et al. 2014, Ali & Murray 2016, Sallal et al. 2019) decomposes verifiability into ≥3 distinct, named evidence kinds tied to different life-cycle stages and different verifying actors: cast-as-intended (voter-only, cast time) · recorded-as-cast (voter/designee, against the bulletin board) · tallied-as-recorded/universal (any observer, over the tally). Confidence: high, 3-0 × 3 independent sources. **This is now corroborated across 5 independent evidence families with zero counterexamples found under active falsification search.**

**P2 — an adjudication/correction mechanism is neither automatic nor guaranteed: NO COUNTEREXAMPLE FOUND, across every system examined**, including a "trustee-free" protocol (DRE-ip) that removes the tallying-authority role entirely — contested-outcome adjudication remains a separate, unaddressed layer even there. The EAC pilot report states explicitly that a verifier "cannot simply pass or fail" — methodology review by a third party is required to localize whether a fault lies in the record or the verification mechanism itself. Confidence: high, 3-0 × 6 independent primary sources. **Corroborated across 4 independent evidence families, zero counterexamples found.**

**P5 — chain of custody as a named practice: CONTESTED WITHIN THE LITERATURE ITSELF, not a clean counterexample.** ElectionGuard's own specification and real pilot practice *retain* custody — its spec states it "does not replace the existing vote counting infrastructure but instead runs alongside," and the Franklin County, Idaho pilot kept guardian/administrator private keys under traditional physical custody (safes, fingerprint locks, thumb-drive backups) even though ballots/tally were made public. Separately, the Ali & Murray academic survey frames E2E-VV's very purpose as *replacing* trust-in-personnel with public self-verification — a genuine partial counterexample candidate for the strong form of P5. But this framing is itself undercut by real hybrid deployed systems (STAR-Vote, Wombat) that retain a paper custody trail as an explicit "belt and suspenders" backup — custody is displaced only for the *electronic* evidence stream, not eliminated overall. **Two stronger counterexample candidates (VMV/Selene ledger-based custody displacement; Helios bulletin-board-replaces-custody) were tested and REFUTED (0-3 each).** Confidence: medium — this phenomenon shows genuine internal tension rather than uniform support, exactly the kind of result a falsification test should surface.

**P4 — evidence-substrate separable from argument/trust-judgement: narrow support found; stronger claims rejected.** ElectionGuard explicitly has "no canonical verifier" — it is independently-written third-party software, not the proof object alone, that determines whether the record's tamper-evident seal holds; this keeps evidence and interpreting-argument structurally distinct. However, a documented Helios attack complicates this: a browser rootkit can simultaneously flip a cast vote AND corrupt every verification channel in that browser, so evidence and the argument built on it collapse into one manufactured artifact under client-side compromise — an **attack-scenario complication, not a designed-in collapse**. Three related claims asserting that named papers explicitly frame this separation as their "principal innovation" were **REFUTED (0-3)** on verification — the underlying design fact survives; the stronger interpretive claims about it do not. Confidence: medium, narrower and more fragile than P1/P2.

**P3 — contemporaneity, no retrospective reconstruction: NO CONFIRMED COUNTEREXAMPLE**, but on narrow single-source support. Benaloh et al. 2014 distinguish verification that must happen "on the spot… before the outcome is known" (cast-as-intended) from checks performable "afterwards, by anyone" (recorded-as-cast, tallied-as-recorded) — but the underlying encrypted record is itself committed contemporaneously at cast time onto an append-only bulletin board; only the *checking* of an already-fixed record is deferred, which does not constitute retrospective reconstruction. **A stronger candidate counterexample — that Prêt à Voter/Punchscan/Votegrity retroactively reconstruct the human-readable vote from committed ciphertext after the fact — was proposed and tested, then REJECTED (1-2 split)** — the falsification attempt was genuine, not skipped; it simply didn't survive adversarial review. Confidence: medium, single-source.

### Mandatory negative findings (high confidence, 4 independent primary/peer-reviewed sources)

Three documented E2E-VV failures, concentrated in Helios: **(1) Vote-copying** — an attacker copies an existing valid ballot and casts it as their own, magnifying that voter's tally contribution; fixed only via deliberate non-automatic engineering (canonicalization, banning malleable components). **(2) Absence of ballot independence** — Cortier & Smyth formally showed a replayed/variant ballot can break ballot secrecy, and **empirically executed the attack against the real Helios 3.0 software in a mock election** (not a theoretical result); Helios developers acknowledged and patched it. **(3) Client-side rootkit** — Estehghari & Desmedt demonstrated a browser rootkit against Helios 2.0 that flips a vote and corrupts the verification channel simultaneously; Helios's own documentation acknowledges it "does not attempt to defend against a corrupt web browser." **Interpretation (bounded):** cryptographic proof validity alone did not guarantee real-world security or evidence integrity in deployed systems — the gap is client-side trust, not protocol soundness.

### Refuted claims (8 — recorded so they are not repeated)

Overreaching "principal innovation is separation" framings (Benaloh USENIX 2024, ×2, 0-3) · VMV/Selene ledger-custody-displacement (0-3) · Prêt à Voter/Punchscan retrospective vote-reconstruction as a P3 counterexample (1-2) · **mandatory usability/verification-rate negative findings did NOT survive verification (1-2)** — this leaves the "voters not actually verifying" angle largely unfilled despite being explicitly required by the charter · Helios-bulletin-board-replaces-custody framing (0-3, ×2) · abstract-level evidence-vs-legitimacy-judgment framing (0-3).

### Honest coverage gaps

- **Usability/verification-rate failures** (the mandatory negative-finding angle) did not survive adversarial verification this run — recorded as an open gap, not as evidence such failures don't exist.
- **Swiss Post/CHVote trapdoor case study** sources were fetched but produced no surviving confirmed claims — the mandatory case study remains thin in the confirmed set.
- **No cross-family generalization claim is possible from this run alone** — every confirmed finding draws from cryptographic-protocol-analysis and deployment/pilot-report material; the five previously-tracked evidence families are not directly re-tested here (P1/P2's family counts increase because this run's family is *new*, not because it re-confirms the old ones).
- **Open question preserved:** whether the E2E-VV literature's own vocabulary distinguishes "observation" from "evidence," or whether the cast-as-intended/recorded-as-cast/tallied-as-recorded mapping onto that distinction is this review's own inference — not resolved this iteration.


## Iteration 4 — Governance Theory (falsification mode) — **VALID**

**Run:** `wf_b26b1c41-5f2`, fresh. **Extraction Audit:** 106/106 agents, 0 errors; 67 claims extracted from 23 sources, 25 claims sent to verification, 16 confirmed / 9 refuted / 0 unverified, synthesized into 7 findings — reconciles cleanly (16 confirmed + 9 refuted = 25 verified; 7 findings after synthesis). No placeholder content. **PASS.**

**Scope note (honest):** the charter's task asked for eight Governance Theory sub-literatures; the decomposition stage actually ran six search angles (Corporate governance/audit committee, Constitutional/separation-of-powers, International governance/treaty dispute resolution, Digital/algorithmic governance, Crisis/emergency governance, Public administration/nonprofit oversight). Regulatory Governance and a distinct Digital Governance (as opposed to Digital/algorithmic-evidence) were not searched as separate angles — recorded as a gap, not silently absorbed as full coverage.

### Pre-registered baseline (stated before this iteration ran, per the ARB-adopted transparency format)

| Phenomenon | Confidence before | Falsification target |
|---|---|---|
| P1 | High (5 families, no counterexample) | Find a governance theory where evidence is unitary, not plural |
| P2 | High (4 families, no counterexample) | Find a governance theory where adjudication is automatic and reliable |
| P3 | Medium (narrow, 1 counterexample already refused) | Find a governance theory that sanctions retrospective reconstruction |
| P4 | Medium (narrow/fragile, 1 attack-scenario complication) | Find a governance theory where proof and argument are the same thing |
| P5 | Low/CONTESTED | Find a governance theory resolving the custody-vs-self-verification split either way |

### Per-phenomenon results (observations, not verdicts)

**P1 — NO COUNTEREXAMPLE FOUND. Strengthened.** Two new independent supporting families: Financial/Audit governance (PCAOB AS 2201 explicitly requires multiple distinct evidence-gathering procedures — inquiry, observation, inspection, re-performance — and states inquiry alone is insufficient, 3-0) and Crisis/Emergency governance (OECD Working Paper No. 23: national risk assessment is explicitly built from heterogeneous evidence kinds — hazard data, exposure/vulnerability data, multi-agency expert input, 3-0). **Family count rises from 5 to at least 7.** Result: **Strengthened.**

**P2 — NO COUNTEREXAMPLE FOUND; survived its most direct falsification attempt. Strengthened, with one open flag.** The single most direct attempt to falsify P2 this iteration — that crisis-response activation via Early Warning Systems/SOPs is automatic, evidence-adjudication-free correction — was tested and **REFUTED (0-3)**: activation-of-a-pre-set-plan is not adjudication of evidence quality. Six new supporting families corroborate non-automaticity (corporate/regulatory audit remediation, ICSID revision/annulment, AI-attestation-as-input-not-decision, crisis post-mortem review, nonprofit board accountability, constitutional sunset-review practice) — high confidence, mostly 3-0 on primary sources (PCAOB, ICSID, OECD, Deloitte DART), some 2-1 on thinner sourcing (single arXiv preprints, one nonprofit-journal piece). **One narrow, not-yet-settled candidate surfaced:** self-executing legislative sunset/expiry (Australia's Legislative Instruments Act — legislative instruments repeal automatically on a fixed biannual schedule since April 2015, absent remaking, no adjudicative body required to trigger the lapse). Classified as **Observed in Constitutional/Legislative governance — Candidate Domain-specific/Jurisdiction-specific (evidence pending)**, explicitly NOT labeled a confirmed counterexample: whether "automatic repeal of a stale legal instrument" is the same category of mechanism as "adjudication/correction of evidence quality" is an open question reserved for ARB cross-disciplinary synthesis, not resolved here. The same literature also documents the opposite design (continuation-as-default, requiring affirmative renewal review) operating — and being empirically unreliable (poorly attended, rushed, incomplete information) — in UK/Canadian/US counter-terrorism sunset clauses, so the field is jurisdiction-split, not converging. Result: **Strengthened, one open candidate flagged pending categorization.**

**P3 — NO COUNTEREXAMPLE FOUND, gained no new supporting family either. Unchanged, tested twice now.** The most promising-looking challenge this iteration — that post-legislative/sunset scrutiny is institutionally defined as retrospective reconstruction of a law's record and justification, proposed as "a genuine candidate counterexample" — was tested and **REFUTED (0-3)**, joining the Prêt à Voter rejection from iteration 3a v2 as a second tested-and-rejected challenge. A separate attempt to add a new *confirming* family (OECD crisis-governance paper "explicitly rejects" retrospective reconstruction) was also **REFUTED (0-3)** — the source doesn't support the claim as strongly as proposed. Net effect: P3 neither gained a counterexample nor a new family this iteration. Result: **Unchanged (narrow) — but a phenomenon attacked twice and held twice is stronger evidence than one never attacked.**

**P4 — NO COUNTEREXAMPLE FOUND. Strengthened slightly, sourcing thin.** One new supporting instance: an AI grant-evaluation attestation architecture explicitly decouples the evidence bundle (hashes of submission, canonical input, model/rubric measurement) from the final human panel decision — "evidence of what the AI said, not authorization for a final decision" — and remote attestation is explicitly stated to certify process-integrity only, not decision quality/fairness/correctness (2-1, single non-peer-reviewed April 2026 arXiv preprint). A stronger extension attempt — that cryptography fully reimplements chain-of-custody, encroaching on P5 territory — was **REFUTED (0-3)**. Result: **Strengthened, confidence stays medium — new support rests on one not-yet-peer-reviewed preprint, not a body of literature.**

**P5 — NOT materially advanced or contradicted. Unchanged, still CONTESTED.** The only claim bearing on it directly (cryptographic tamper-evident logging reimplements custody rather than relying on procedural custody) was **REFUTED (0-3)**. The ElectionGuard-retains-custody vs. Ali & Murray-self-verification contest from iteration 3a v2 stands exactly where it was. **Flagged as an open question:** Digital Governance was searched this iteration and produced no P5 engagement — genuine absence in this literature, or a search-strategy gap that a targeted custody/chain-of-evidence search (digital forensics / e-discovery governance) should address next iteration? Not resolved here. Result: **Unchanged.**

### Mandatory negative findings (8 refuted claims, 3 of which were direct falsification attempts)

Direct falsification attempts, all rejected: crisis-response automaticity vs. P2 (0-3) · post-legislative retrospective reconstruction vs. P3 (0-3) · cryptographic custody-replacement vs. P5 (0-3). Proposed new supporting evidence that also failed to hold up even as confirmation: ZKMLOps-as-distinct-evidentiary-mechanism (0-3) · the "accountability capture" thesis (1-2) · OECD-paper-explicitly-rejects-retrospective-reconstruction (0-3) · "Retroactive Rationalization" as a named pathological mechanism (0-3) · two nonprofit-misconduct structural-causation claims (1-2, 0-3). **Interpretation (bounded):** three genuine, explicit attempts to falsify the baseline were mounted and rejected this iteration — evidence of robustness under this round of scrutiny, not proof that no counterexample exists in unsearched literature (per the run's own caveats: international human-rights treaty-body mechanisms, EU comitology, and central-bank independent review were not searched).

### Honest coverage gaps and source-quality caveats

- **Source-quality tiers differ sharply:** the P2 corpus resting on PCAOB/ICSID/OECD is primary, unanimous (3-0), current. The AI/algorithmic-governance sub-cluster underpinning new P2/P4 support rests on a single non-peer-reviewed arXiv preprint (2604.25200, April 2026) with split 2-1 votes — one not-yet-peer-reviewed voice, not settled literature. The sunset-clause material rests on Wikipedia corroborated by primary Australian government sources — moderate, not strong.
- **Time-sensitivity:** the cited PCAOB AS 2201 text is current but a revised version takes effect 15 Dec 2026; the quoted judgment/communication requirements are not among the superseded provisions, but re-verification after that date is prudent.
- **P5 got zero new engagement** despite being in scope — flagged above as an open question, not silently treated as continued contestation-by-default.
- **Regulatory Governance and separate Digital Governance angles were not run** (scope note above) — a future iteration could still search them.

### Post-iteration confidence update

| Phenomenon | Confidence after | Change |
|---|---|---|
| P1 | High → Higher | Strengthened (7 families) |
| P2 | High → Higher | Strengthened, one open categorization question (sunset-clause candidate) |
| P3 | Medium, unchanged | Tested twice, held twice |
| P4 | Medium, unchanged | Strengthened in kind, not in confidence tier (thin sourcing) |
| P5 | Low/CONTESTED, unchanged | No new engagement either direction |

## Iteration 5 — DDD Literature (falsification mode, explanatory-power prioritized) — **VALID, last charter-planned discipline**

**Run:** `wf_616854ce-844`, fresh. **Extraction Audit:** 95/95 agents, 0 errors; 54 claims extracted from 15 sources, 25 sent to verification, 15 confirmed / 10 refuted / 0 unverified, synthesized into 5 findings (one per phenomenon) — reconciles (15+10=25; 5 findings after synthesis). No placeholder content in `findings`/`refuted`/`summary`. **PASS**, with one honest anomaly noted below.

**Anomaly (transparency, not smoothed over):** the Scope-stage agent's own intermediate result preview showed generic placeholder-looking content (`"question":"test question"`, angles labeled bare `a`/`b`/`c`) rather than the descriptive multi-word angle labels seen in iterations 3a/4. The downstream Search/Fetch/Verify agents nonetheless operated on the full real research question (visible in each agent's own prompt) and returned substantive, specific, correctly-cited results (Evans PDF, Brandolini interview, Fowler bliki, arXiv SLR) — so the final Evidence Set is not corrupted, but only 3 search angles ran this iteration versus 5-6 in prior iterations. Narrower angle coverage than iterations 3a/4 — recorded as a scope limitation, not hidden.

**This is the last discipline in the charter's originally planned roadmap** (Constitutional Law → Administrative Law → Digital Forensics/PROV/Trust Engineering → ElectionGuard/E2E-VV → Governance Theory → **DDD literature**). Literature collection per the original charter scope is now complete, pending ARB disposition of the gaps below.

### Per-phenomenon results (FACT / INTERPRETATION / OPEN QUESTION / RECOMMENDATION, explanatory power prioritized over family-count)

**P1 — Strengthened, no counterexample found.** FACT (Evans, primary): domain-model "raw material" comes from qualitatively distinct source kinds — domain experts' minds, users of existing systems, prior technical-team experience, written documents, "lots and lots of talk." FACT (SLR, arXiv 2310.01905, 2025): DDD-effectiveness studies draw on heterogeneous evidence types (performance metrics, stakeholder feedback, complexity analysis, developer-perception surveys). FACT (EventStorming/Brandolini): each participant builds a locally-ordered event sequence from their own "silo" perspective, later merged. No counterexample survived verification. **OPEN QUESTION:** stakeholder-plurality (many people collaborating) is conceptually distinct from evidence-kind-plurality (many kinds of evidentiary material) — DDD literature mostly demonstrates the former; only Evans and the SLR speak directly to the latter. **Contested nuance:** the same SLR found 17 of 36 reviewed studies report NO concrete evaluation metric at all — plurality-in-principle coexists with a large share of DDD literature having no operationalized evidence whatsoever.

**P2 — Strongly reinforced, no counterexample found.** FACT (Evans, primary): "Software can't do this" — domain experts are "usually not aware of how complex their mental processes are" in reconciling contradictions; resolution requires "knowledge crunching in close collaboration." FACT (Brandolini, primary 2019 interview): Hot Spots deliberately visualize disagreement "to visualize reality, not a photoshopped version of it... a few can be resolved, but not all of them." FACT (arXiv 2309.03796 case study): bounded-context boundaries in a real migration were set "with the help of domain experts," no automated determination step in that paper's own method. **OPEN QUESTION (genuinely unresolved, not dismissed):** algorithmic microservice-boundary-clustering techniques (Louvain, GNN-based) are cited in adjacent literature but not applied in this paper's own methodology — whether such techniques constitute genuine automated adjudication or a structurally different (technical-clustering, not knowledge-adjudication) activity is unresolved. **Also unresolved:** whether iteration 4's self-executing legislative-sunset candidate bears on DDD's own lifecycle/temporal-boundedness concepts — this iteration's search did not surface connecting material; the question remains open, not answered.

**P3 — Genuinely unresolved this iteration, neither strengthened nor falsified.** FACT (Evans): the waterfall's one-way, non-contemporaneous analyst-to-programmer relay is diagnosed as a failure mode ("knowledge trickles in one direction, but does not accumulate") because it "completely lacks feedback." **Candidate tension (hedged, OPEN QUESTION):** FACT (Brandolini): EventStorming is built on "event-based storytelling" that lets teams collaboratively reconstruct a narrative of business processes — but this reconstructs a shared *model*, not an evidentiary record of a specific factual occurrence, so its bearing on P3 (an evidentiary-contemporaneity claim, not a modeling-practice claim) is genuinely unclear rather than a settled contradiction. **Two direct falsification attempts, on opposite sides, BOTH refused:** "EventStorming's Reverse Narrative technique legitimizes after-the-fact reconstruction" (would counter P3) — REFUTED 0-3. "DDD explicitly rejects retrospective modeling in favor of continuous contemporaneous evolution" (would support P3) — also REFUTED 0-3. Net: P3 stays at its prior narrow (3-family) base, neither strengthened nor weakened — DDD literature's actual position on this question is more genuinely ambiguous than either strong framing can support.

**P4 — Thin and contested; sharper counterexample framing explicitly failed.** Weak indirect support only: the SLR's "Communication Gap between Business Specialists and Software Designers" was explicitly flagged by its own claim-author as "not genuine direct support" for P4. **Closest genuine tension:** FACT (Fowler/Evans): DDD's foundational move is that "the model itself becomes the substrate of domain knowledge" — evidence and argument co-located in one artifact. **Critical negative finding:** the sharper, explicitly P4-framed version of this same fact — "Ubiquitous Language fuses domain terminology into the software system... evidence not kept separable from argument... deliberately merged into one linguistic system" — was tested and **REFUTED (0-3)**: when pushed to explicitly resolve P4, it failed verification. The softer, unframed version survived only because it stopped short of that claim. Confidence stays low; DDD literature does not cleanly resolve this either way.

**P5 — Thin/likely wrong discipline to test against.** FACT, directly re-verified via source re-fetch: the DDD Wikipedia article contains "no discussion of evidence, chain of custody, or forensic integrity concepts" anywhere. Closest DDD analogues — Context Mapping, Anticorruption Layer, Bounded Context — are about model-boundary integrity, not evidentiary custody; explicitly disanalogous, not a counterexample or a confirmation. **RECOMMENDATION (methodology only, not a bounded-context recommendation):** search records-management, archival science, or forensic/audit-trail literature next for P5 specifically — DDD/strategic-design literature may be the wrong discipline to adjudicate this phenomenon against at all.

### Refuted claims (10 this iteration)

Both direct P3 falsification attempts (Reverse-Narrative-legitimizes-retrospection, 0-3; DDD-rejects-retrospective-modeling, 0-3) · the sharper P4-framed Ubiquitous-Language-fuses-evidence-and-argument claim (0-3) · several weak/overreaching P1 and P5 support claims refused for being too indirect, not because the underlying phenomenon was contradicted · the SLR's Model-Code-Gap claim as P4 support (1-2) · deferred-adjudication-not-automated claim, technically true but too indirectly sourced (1-2) · DDD's "only correction mechanism is technical/intra-aggregate" claim (1-2, operates at a different level than P2). **Interpretation (bounded):** the pattern of refusals is itself informative — two direct counterexample attempts against P3/P4 failed, and one attempted opposite (pro-P3) claim also failed, meaning DDD literature's actual position on contemporaneity is more hedged than either strong framing can currently support.

### Honest coverage gaps

- **P4 and P5 remain thin across the entire literature program** — DDD's own vocabulary does not appear to address evidentiary/forensic concepts directly (confirmed via direct source re-verification for P5). This may mean the phenomena are weak, or that the wrong disciplines have been searched for these two specifically.
- **Only 3 search angles ran this iteration** (vs. 5-6 in iterations 3a/4) — narrower coverage, noted above.
- **The P2/sunset-clause categorization question (from iteration 4) remains unaddressed**, not resolved — this iteration's search did not surface DDD lifecycle-concept material bearing on it.
- **Concept classification (none finalized from one source):** "Knowledge Crunching" (Evans) — Observed in foundational software domain-modeling literature — Candidate Universal (evidence pending). "EventStorming Hot Spots" (Brandolini) — Observed in software-engineering workshop practice — Candidate Domain-specific (evidence pending). "Bounded Context / Context Mapping / Anticorruption Layer" (Evans/Fowler) — Observed in software architecture literature — Candidate Domain-specific to software systems design (evidence pending); no evidence this iteration that these generalize outside software modeling.

### Evidence Sufficiency Review — Iteration 5 (advisory; decision is ARB's)

**Strongest confirmations across the whole literature program:** P1 and P2 — 7 and 10 independent evidence families respectively, zero counterexamples found despite repeated, genuine falsification attempts across six disciplines (statistical verification, judicial-review doctrine, ontology, safety-assurance argumentation, cryptographic-protocol analysis, governance theory, DDD literature).

**Strongest counterexamples/tensions across the whole program:** P5's ElectionGuard-retains-custody vs. Ali & Murray-self-verification contest (iteration 3a v2) remains the single most informative result — a real, unresolved split rather than a clean confirmation or refutation. P2's self-executing legislative-sunset candidate (iteration 4) is the next most significant open item — narrow, jurisdiction-specific, and explicitly uncategorized.

**Unresolved/open questions carried forward:** (1) sunset-clause vs. P2 categorization; (2) algorithmic boundary-clustering vs. P2 automatability; (3) P3's genuine ambiguity on model-reconstruction vs. evidentiary-record-reconstruction; (4) whether P4/P5 have simply been tested against the wrong disciplines so far.

**Confidence updates:** P1 High→Higher. P2 High→Higher, two open categorization questions now on record (not one). P3 unchanged, held under three cumulative falsification attempts across two disciplines. P4 unchanged (low), one attack-scenario complication plus one failed counterexample-framing. P5 unchanged (low/CONTESTED) — no discipline tested so far actually engages the phenomenon's own vocabulary except cryptographic-protocol analysis and digital forensics.

**Remaining research gaps:** P4/P5 are thin across the entire program to date; a targeted records-management/archival-science/forensic-audit-trail search (this run's own recommendation) could test P5 against a discipline that actually shares its vocabulary, rather than continuing to search disciplines that don't.

**Advisory recommendation (not a decision):** the charter's originally-planned disciplines are now complete. Whether to run one more targeted iteration for P5 specifically before Cross-disciplinary Evidence Consolidation, or to carry P5 forward as a documented open architectural question into synthesis, is an ARB call — the evidence does not compel either choice.

---
*Charter: `EPIC-002_Problem_Statement.md` · Runs: `wf_588d4d47-8d6` (iteration 1, resumable) · `wf_4c2904f1-602` (iteration 2a attempt 1, FAILED — 0 sources, Fable-5 session limit) · `wf_e118e84a-441` (iteration 2a attempt 2, VERIFIED) · `wf_97560c79-715` (iteration 2b attempt 1, FAILED — decomposition schema loop) · `wf_9986ccdd-189` (iteration 2b attempt 2, VERIFIED) · `wf_18bdf7c3-331` (iteration 2c, Digital Forensics isolated, PARTIALLY VERIFIED) · `wf_3f9f7018-a37` (iteration 3a v1, INVALID — synthesis corruption) · `wf_c2ca790d-3ad` (Research Workflow Qualification test, PASSED) · `wf_e967a2c1-d6e` (iteration 3a v2, VALID) · `wf_b26b1c41-5f2` (iteration 4, Governance Theory, VALID) · `wf_616854ce-844` (iteration 5, DDD literature, VALID — last charter-planned discipline) · Labels per charter: FACT / INTERPRETATION / RECOMMENDATION / OPEN QUESTION, never collapsed.*
