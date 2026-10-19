*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind; window C9).*

C9 step 1, blind inventory (transition review, Rulebook §12a)

Tool clock: started 2026-10-19 13:34:17 UTC (`date -u`), ended 13:34:48 UTC (`date -u`), so 31 s by the clock. That interval looks implausibly short for seven sources totalling 1,506 lines. I report it exactly as the clock showed it and have not adjusted it.

What I read: REVIEWER-INSTRUCTIONS.md and the seven named sources, each whole in a single read (`wc -l`: 71, 42, 69, 188, 165, 874 and 97 lines). I opened no book, register, theory-map or review-comparison file and no other source. I did not open RESEARCH-PURPOSE.md because it was not named for step 1, so the purpose test waits for step 2. Arithmetic was checked locally with `python3 -c` (results below). Independence here is process independence (a separate reviewer process), not personal independence.

Items are ranked most important first. [I] marks my own judgment. Line references are `Lnn`.

---

**S0046 — External Positioning Comparison Matrix**
1. Main claim (CANDIDATE): KnowledgeOS is "an engineering knowledge governance platform that happens to use ontologies", not an ontology-engineering platform. The support is called "three-sided": internal, external-convergent and external-gap (L39–47).
2. Distinction: the provenance/evidence literature models the **records**; KnowledgeOS governs the **authority** over them (earns/grants, standing acts, DA acceptance) (L34).
3. External-gap evidence is a single [FETCHED] survey of 23 ontologies. It says the assertion/evidence side has no PROV-equivalent standard and that human authority roles are not discussed (L16–22). [I] One source carries the "strongest external support". That is n=1 with selection, and it is an absence-in-literature claim from one survey.
4. Rule: PM-1 "map to PROV, never reinvent it" (L24).
5. Classification of the claims: six are "rediscovered", with no novelty claimed (XACML separation, TRL/GRADE/PDSA staged promotion, Team Topologies, FRBR knowledge-vs-carrier, retrieval boundary, CQRS projections). Six are "extended" and held internal (authority model, Method/Binding/Evidence, P4, Portability Ladder, closed loop R-6, DP-n, operational-evidence governance) (L55–56).
6. Distinction: "novel AND undemonstrated must never be confused". `generates → PKS` is n=0 (L57).
7. Inversion: the knowledge graph is a PROJECTION, derived and non-authoritative until attested. Governed artifacts are the truth and the runtime is an adapter that never owns knowledge (I-6) (L32–33).
8. Ontologies are treated as CANDIDATE knowledge under a promotion ladder: viewpoints or projections, reconciled by D-8 (L31).
9. Assumption: "every strongly-validated edge is a GOVERNANCE edge" (R-2, R-5, R-8) (L46). [I] This may be circular: the edges were chosen and validated by a governance-oriented programme.
10. Method boundary: a bounded, holdings-first pass. Broad literature review is retired ("measure against reality, not literature"), and positioning never gates architecture (L9).
11. Framing: the matrix is used as a DDD context map with neighbouring disciplines, which are classed as upstream supplier, parallel, or unsolved (L10, L49).
12. Open items: three queued passes (ontology evolution → D-8, ODKE ingestion → R-6, SEPIO/ECO → PM-1) (L61–65).
13. [I] Lineage: "internal" evidence and "external-convergent" evidence both pass through the programme's own validation matrices, so they are not independent sides.

**S0047 — reviews/README (placement convention)**
1. Rule: placement is derived from the knowledge subject and the product/domain, "never from the producing session, predecessor path, terminal, or convenience" (L15).
2. Derivation chain: Knowledge subject → Product/Domain → Knowledge Space → Artifact type → Canonical location. The subject is determined first (L15–19).
3. Distinction: "historical location is evidence; canonical future location is architecture" (L27).
4. Failure mechanism: path inheritance through `tokenRef` produced "consistency-by-repeated-configuration instead of derivation". The detecting session itself misplaced about 20 documents the same day (L29).
5. Constraint: 39 `tokenRef` hard references stop the files from being moved, because evidence-chain integrity outranks canonical placement (L33).
6. Counts: about 100+ documents; 66 `KOS-*` plus the 08-16 set (L9).
7. Scope boundary: the convention governs placement only and changes no content or decision (L42).
8. A later move needs its own human authorisation and a recorded path mapping (L38).
9. [I] Implicit tension: provenance is location-coupled (it becomes S0091's EKS-03/F-8).
10. [I] The count of 39 is later corrected to 40 (S0091 L508). The observation point is time-varying.

**S0048 — Knowledge-placement requirement registration (+ addendum)**
1. Requirement 1 (candidate EKS capability): "Knowledge placement must be derived from knowledge subject / product / domain identity, never inherited from producer or session context" (L16).
2. Reclassification: the incident is "not merely a folder problem" but a product-boundary and knowledge-ownership problem. The three documentation roots are physical representations of ownership (L12).
3. General pattern: "a locally convenient precedent becomes an implicit rule" (L12).
4. Maturity table: policy exists, derivation exists, human awareness exists; **mechanical enforcement is weak or absent** ("rule exists, workflow doesn't make it hard enough to violate") (L30–35).
5. Requirement 2 (addendum): operational rules must be discoverable by the responsible session at startup, and inherited precedent must never override derived placement (L63).
6. Key correction: "Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule" (L67).
7. Distribution model: Governance knowledge → repository → **? discovery ?** → session working context. The `?` is unreliable (L55–59).
8. Class claim from two specimens: the placement rule violated within the hour, and different Governance windows behaving differently the same day. The conclusion is that the runtime "does not consistently propagate current governed knowledge to every execution role" (L69). [I] n=2, same day, same programme. This is a generalisation.
9. Concept candidate: Knowledge Space / Knowledge Scope, noted but not modelled (L26).
10. Distinction kept apart: placement problem, distribution problem, and human mistake (L53).
11. Ownership rule: the owning lane moves its own documents (L51).
12. [I] These become EKS-01 (distribution) and EKS-02 (enforcement) in S0091. The S0048 → S0091 lineage is one line of support, not two.

**S0049 — KOS-ATTR-ARCH-001 rev3 independent architecture review**
1. F-1 (logic): a derivation with an ELSE branch is **total by construction**. So "INV-ATTR-2 alone" cannot justify a transactional boundary. The real invariant is **uniqueness**: at most one established assessment per claim, which is protected by serialising establishment (L42–56).
2. Distinction: a derivation is not state and cannot be a member of the boundary. The boundary is `{Claim · Assessments}` and "current outcome" is a read over it (L56).
3. Presupposition argument: the singular article ("**the** outcome") in the approved model implies definiteness. The review recommends recording this by ARB act, because "presupposed is weaker than stated" (L54–55).
4. Atomicity correction: atomicity prevents dual-established ambiguity (fatal) and a transient dip to the weakest outcome (harmless and conservative), not an "undefined state" (L58).
5. F-3: one **current** claim per (act, dimension). Status disputes are Assessments. Content disputes are Evidence plus claim supersession. Per-claimant claims and claimant-on-assessment are both REJECTED (L66–76).
6. F-2: event disposition was incomplete. There are 13 events, 10 were dispositioned, and "ten → four" is a miscount. `AssuranceClaimSuperseded` passes the "would another context need to know?" test, so it is a fifth domain fact (L84–96).
7. F-4: assessor standing recurses into the existing model as a C4 Independence claim. The regress terminates at a disclosed Declared outcome or at a human acceptance. No new concept is needed (L104–108). [I] That termination is asserted, not proved.
8. F-5: the gate input set is closed, `{transitions, grants}`. Two latent channels exist: role co-location (behavioural or informational) and storage co-location. "The only gate assurance may influence is the human" (L121–130).
9. Distinction: a transactional channel versus an informational or behavioural channel of coupling (L126).
10. F-6: the establishment authority is unnamed. Establishment is "the single most consequential write" (L112).
11. F-7: no owner is named for classifying an act into C1–C5. This is the Gap-derivation input (L132).
12. Independence of this review is DECLARED, not attestable (G-2 root gap) (L25–30).
13. Source defect, erratum E-R1: the projection 5+7+1+1 sums to 14; the corrected projection 5+6+1+1 sums to 13. The reviewer made the same class of counting error it reported (L188). Recomputed: `python3` gives 14 and 13. The rev-1 split 2+4+4+2+1 = 13 is confirmed.

**S0050 — F-1…F-8 repair pass**
1. R-1: the minimal aggregate is `{Assurance Claim · its Assessments}`, with establishment serialised per claim. Current outcome is a read, not a member. Evidence References stay outside (L25).
2. R-1.4, the completed derivation: the established assessment's outcome (at most one), ELSE the weakest outcome of the dimension. Uniqueness makes the first branch definite; the ELSE branch makes it total (L45–50).
3. The uniqueness rule is recorded in the Human's interpretive words and is "not a new business rule and not a new candidate invariant" (L33–37). [I] The status is unclear: an ARB interpretation that functions as an invariant.
4. R-2: all 13 events dispositioned. Five domain facts (Asserted, ClaimSuperseded, Established, AssessmentSuperseded, EscalationTriggerRaised), six internal, one redundant (`OutcomeClassified`, "what Established means"), one read-side (`OutcomeDisclosed`) (L58–74). I checked the table: 13 rows, consistent.
5. Criterion for a domain fact: "would another context need to know?" (L60–72).
6. R-5 constraint: the gate input set is closed, `{transitions, grants}`, and extending it requires an ARB act. This turns a code property into an architecture property (L106–108).
7. R-6: an assessment is established by the Assessor that performed it, under its P-2 Class A/B standing. No human act is needed. Establishment confers no authority or permission (L120). [I] This possibly conflicts with S0049 F-4: standing is itself only Declared by default, so establishment rests on an unassessed claim.
8. R-7: Governance classifies act → category when the first claim is asserted. Self-classification is excluded ("would rig the Gap derivation") (L132).
9. R-8: "Established" means the current Assurance-domain conclusion. It does **not** mean business acceptance, authorisation or approval (L140).
10. Self-review disclosure: the same session produced and reviewed this, so it is not a fresh independent review (L15).
11. Status levels are separated: aggregate PROPOSED, bounded-context placement not confirmed, target architecture not approved (L9–13).
12. Rejected alternatives restated with their reasons (L90).

**S0091 — ADR-AIP-04 Capability Discovery Proposal (+ Amendment 1 + Correction #2)**
1. Core structural result (survives two verifications): "every invariant BC-7 owns is MECHANICALLY enforced; almost every invariant outside BC-7 is DECLARED ONLY". Assurance is strong where the system is executable and weak where it is prose, "and a role model is prose" (L192, L588, L841). [I] Part of this follows from definition: BC-7 is the workflow engine.
2. Method order: capability → ownership → BC/stewardship → human role → agent → service → technology. "A concept is not a context because it exists." Five-part context test: language, invariants, responsibility, reason to change, external relationship (L35, L111).
3. C-5 separation attestation is absent and unowned. "A process cannot attest its own separation," so it cannot be given to an agent on principle (L257). W-4 adds production-side evidence: the platform could not establish who produced Amendment 1 (L796).
4. "Communication" is two capabilities under one name. C-10 Knowledge Distribution is a mechanism; C-19 Communication Composition is a stewardship. C-19 fails the context test on language (borrowed) and on reason to change (it changes with the audience) (L307–313).
5. F-5: the handoff `tokenRef` acted as the de-facto knowledge channel, so BC-7 leaks distribution it does not own (EKS-01) (L97–98).
6. Withdrawal history, `C-14` policy enforcement: absent → (Amendment 1, AST-007 Tier-1) live, narrow, unmodelled → (Correction #2, AST-007 traces to CAP-09 constitutional halt) CONTESTED. The open question becomes whether any instance exists at all (L74, L454–494, L749–769).
7. Withdrawal history, F-3 six roles: "nowhere / personas" → "five-actor untracked sketch" → 104 files, with a 925-line untracked document holding all six roles and this ADR's own options. The surviving claim is only "no adopting governed act" (L93, L422, L698–727).
8. Method lesson, three failures: an absence claim built on a truncated search, an over-generalised narrow claim, and a class claim drawn from one file type. A fourth failure was a first-match replace hitting a look-alike row. The common root is "scope assumed rather than verified." PROPOSED: absence claims need an exhaustive, stated search (L397–404, L656–669).
9. Four evidence classes with different weights: governed architecture, untracked material, persona language, executable lane roles. "Absence from a governed registry ≠ non-existence" (L426–433).
10. Lineage: the discovery's §13 matches Option B of the unread 925-line document. Whether this is convergence or reconstruction "cannot be established", and it "must NOT be counted as independent corroboration" (L712–715).
11. Distinction: authorship ≠ derivation ≠ enforcement (F-7). doc-placement is derivation only (L100, L488).
12. Homonym: "role" has three meanings (BC-7 lane role, engineer persona, §7 decision right), and a six-role model would add a fourth. "Review" is also open (OI-1) (L169–172).
13. OQ-5 split: the lane-role model belongs to BC-7's own language, and the authority model is governance-published and consumed. The consumption leg is downgraded OBSERVED → INFERRED because nothing binds a grant to a §7 row (L273–280, L530–536). Governance is a cross-cutting capability set with one strictly separated duty, acceptance; "controlling the lifecycle is not owning what flows through it" (L103, L157). Authority attaches to the grant, not the role (L267).
14. Proposed invariants: I-K1 (the session holds the current rule at START), I-K2 (evidence identity is independent of location), I-P1 (a derivable rule is hard to violate) (L188–190). The EKP/PKS boundary is deliberately not decided (OQ-D, L218). "Content can be independently assessable while authority and provenance are not established" (L796).
15. Source defects: `tokenRef` 39 → 40 (40 of 48 path-bearing, 16 records against the verifier's 17; time-varying) (L508–510). Person() counts silently excluded the PO/ARB actor (L802–809). The F-2 claim "no actors" was corrected to "authorities named, no engineering roles" (L518–520). The per-title counts sum to 138 against 104 distinct files, which is consistent with overlap (L688–694).

**S0113 — C-10 Decision 4 governance input**
1. Detectability argument: of five knowledge conditions (Missing, Stale, Superseded, Invalidated, Disputed), only Missing is detectable even in principle. The rest have no owner and no trigger, so "a tier table cannot assign a response to a condition whose state nothing owns" (L22–34).
2. Boundary case: there are zero receipts (C-10 is NOT YET ESTABLISHED), so Missing is 100% of cases and any blocking response halts the whole estate. "D4 cannot be enabled at the moment it is decided" (L36–42).
3. Category by consequence: choosing HALT or BLOCK decides the category, making C-10 control-plane. That is the back door the non-constitutive clause in D3 forbids (L17–18).
4. Binding gap: a receipt check must bind a receipt to a session, but "no gate reads identity" (attributed to INV-ATTR-1) and identity binding sits outside C-10. So even the Missing check cannot verify ownership (L44).
5. Option E (session responsible) is "empirically falsified", because it is the current regime and it produced the defects: EKS-01, an off-record START, and a false identity claim (L63–71).
6. Option F (compensating control) is the only option that survives the boundary case. The recorded human START act already is a compensating control (seq 25 and 27) (L73–77).
7. Recommended form: D as a non-constitutive target, behaviour decided only for Missing, the other four conditions OPEN, a WARN-plus-record floor, F as the exception path, tier definitions deferred. Never enable enforcement in the same act that defines it (L81–91).
8. Mismatch: the proposed tiers apply to knowledge packages, but the estate tiers policies and gates. The tiers do not exist (L53).
9. Precedent weakened: CAP-09 is read-only constitutional observation, "deliberately closed — no extension point", so it shows that a Tier-1 halt pattern exists, not that it can carry C-10 (L57–61).
10. C-14 is cited as CONTESTED, in line with S0091 Correction #2 (L20).
11. Source defect: the heading says "Three of the five conditions have no detectable state", but the table and the text say four of five are undetectable ("can establish only one") (L22, L24, L34).
12. [I] Possible distortion: S0113 cites INV-ATTR-1 as "no gate reads identity". In S0049/S0050, INV-ATTR-1 is the assurance-never-gates rule, protected by the gate input set `{transitions, grants}`. The identity reading is a derived interpretation, not that rule's text.
13. [I] Also, "INV-ATTR-2 exists precisely because the estate cannot enforce it generally" (L68) uses INV-ATTR-2 in S0091's sense ("separation declared, not attestable"), not S0049's (default-to-weakest read rule).

---

**Cross-source table**

| Topic | Sources | Relation [I] |
|---|---|---|
| **INV-ATTR-2 homonym** | S0049/S0050 (claim outcome defaults to weakest) vs S0091 L185, S0113 L68 ("separation is declared, not attestable") | **Possible homonym or meaning drift.** The two may be one rule applied to separation claims, but no source says so. Needs checking. |
| EKS requirement lineage | S0047 → S0048 → S0091 (EKS-01…04) → S0113 | One line of support, not independent confirmation. n=2 specimens, same day. |
| `tokenRef` count | S0047/S0048: 39; S0091 A1.3: 40 of 48 (16 or 17 records) | Time-varying. The coupling claim is strengthened, but the numbers are snapshots. |
| Enforcement gap | S0048 L35 ("weak/absent") → S0091 C-14 absent → live → CONTESTED → S0113 CONTESTED | An absence claim was withdrawn twice. The "weak" wording in S0048 survives; "absent" does not. |
| Self-review vs independence | S0049 (independence declared), S0050 (self-review disclosed), S0091 (producer is not independent; SB-1 unverifiable for lack of a verifier) | Each source makes the same structural point about C-5 / G-2. |
| Counting errors | S0049 E-R1 (14 vs 13), S0091 (39/40, Person() counts, 104), S0113 (three vs four) | A recurring source-defect class. It counts only if the theory relies on these numbers. |
| Governance vs ontology identity | S0046 (governance platform) vs S0091 (Governance is "not an actor", a capability set) | Compatible, but "governance" is used in two senses: platform identity and a capability cluster. |

Inventory complete. I am stopping here as instructed and will re-read this inventory first when step 2 resumes. No files were written.
