*Saved verbatim 2026-10-19 from reviewer B's (Sonnet) step-1 hand-back (blind). Harness: commissioned 15:29:46Z, received at this save.*

# STEP 1 INVENTORY — CONSOLIDATION S0036–S0065 — reviewer B

Process-independence note: independence is process independence only. I did not open anything under W/06-theory/ except SOURCES.md, and I did not open RESEARCH-PURPOSE.md. I opened only REVIEWER-INSTRUCTIONS.md, SOURCES.md and the 39 listed sources. No file was written. [I] marks my own judgment. Anchors [Snnnn Lx–y] are line numbers in the source file as read.

Reading ranges for the very long sources:
- S2377: L1–2762, in full.
- S2834: L1–2245, in full.
- S0913: L1–1806, in full.
- S0164: L1–2520 in full. L2520–3328 was only sampled (a grep for theory terms, L3200–3328, and the first 400 lines of the remainder). That tail covers the durability-migration amendments AMD3 to AMD6 and an integration summary.
- S0091: L1–874, in full.

The rank order inside each source is by importance to a kernel theory: primitives, laws, kernel/application boundary, forks, and the kernel's existence.

## Lineage groups (stated once, referenced below)
- **Group A (2026-08-02, one author/process, sources annotate each other):** S0036, S0037, S0038, S0039, S0043. S0038 §0 annotates S0036 (S0036 L11–22). S0043 is the same author correcting its own earlier "0 of 11 enforcing" claim. S0039 is annotated by the MVK FAIL test. Count this as one line of support.
- **Group B (2026-08-03/04, same operational-validation programme):** S0040, S0041, S0044, S0045, S0046. S0041 is a projection derived from other documents. S0040 restates S0044 "from existing records".
- **Group C (2026-08-16/17, Governance/Architecture/Verification lanes of one estate; the verifiers are fresh sessions but the same model family, some the same model):** S0047–S0065, S0067, S0071. S0049→S0050→S0051→S0052→S0053 is one chain. S0054→S0055→S0056→S0057→S0058→S0059→S0060→S0061 is one chain. S0061→S0062→S0063→S0064→S0065→S0067→S0071 is one chain. Several documents annotate each other.
- **Group D (2026-08-18/19, same estate):** S0091, S0113, S0129, S0164. These are strongly interlinked. S0164 is a pasted composite log containing S0091-lineage outputs.
- **Group E (theory line, chat transcripts, 2026-08-28 to 09-07):** S0913 (Step 25U, 08-28), S2377 (09-02), S2834 (09-07). S2377 says it analyses an "attached derivation". S2834 refers to "Parts LXXIX" and "𝒦_min". S0913 refers to "25N". All three are assistant-written chat with a human, not governed records.

---

## S0036 — Platform Capability Model (Group A, same author and day as S0037, S0038, S0039)
1. [S0036 L84–94] Platform = a reusable engineering platform whose product is METHOD. It owns no product's knowledge. Core domain = the method itself. The platform is "Supporting subdomain of PublicDigit" with the gate shut (AIP-14).
2. [S0036 L96–129] 25 capabilities, one class each. 12 KERNEL, 5 SERVICE, 1 ADAPTER, 2 BINDING, 1 GENERATED, 1 EVIDENCE, 3 RESEARCH CANDIDATE, 1 unclassified. Rule: "every capability traces to an artifact; nothing is inferred" [L9].
3. [S0036 L177–189] Kernel test = domain-free ∧ binding-free ∧ evidence-free. C-09..C-12 pass only "after Tier-3 separation" (form reusable, document is 90% case law). C-08 is blocked.
4. [S0036 L102–113] The kernel list: capability pattern, execution protocol, verification/qualification, tactical-modelling governance, placement law, promotion, runtime mapping, BC discovery, saturation/stopping, uncertainty carriage, decision recording, adversarial challenge. It is a list of engineering-process capabilities, not epistemic primitives [I].
5. [S0036 L11–22, L98] Self-refutation: the "one class per capability" flat taxonomy is the error RQ-002 refuted (classes are values on AUTHORITY-SCOPE × GOVERNANCE-STATUS). The 12/5/… counts are therefore not a valid partition [I].
6. [S0036 L137–143] C-08..C-12 are one capability ("Knowledge Discovery") with five responsibilities. C-13..C-17 are five. "Capabilities are not contexts." Whether C-18 and C-24 belong inside other capabilities is undecided at n=1 and n=0.
7. [S0036 L241–252] Evidence gaps. 0/25 capabilities are enforcing. C-06 promotion has 0 traversals. The Product PKS has never been generated (n=0). The runtime adapter is n=1. C-08 is double-blocked.
8. [S0036 L143–175] The dependency graph's only evidenced edge is C-07→C-19 ("vocabulary cannot leak upward", the ACL). The loop-closing edge C-23→C-06 is empty (0 traversals).
9. [S0036 L81, L252, L269] Sixth occurrence of "proposing before searching". Fix = an index of instruments. The conclusion "`knowledgeos init` premature (P3 needs n≥2)" is at [L238].

## S0037 — Strategic Architecture Discovery (Group A; supersession note L15–25)
1. [S0037 L210–220, L328–329] "What is KnowledgeOS?" = three separately governed overlapping domains plus a generic runtime. Governance is owned by the Decision Authority, Method by sponsor+ARB, and EKP by an individual. No single owner exists. "Under-OWNED, not under-architected."
2. [S0037 L10–11] Method: a domain needs ≥3 of Round47-OP's 9 criteria (stricter than the source's own "≥1"). Evidence grades OBSERVED/INFERRED/ASSUMED/HYPOTHESIZED, and only OBSERVED is authoritative.
3. [S0037 L222–232] Observed invariants I-1..I-7: append-only rulings; plan approval ≠ execution authorization; one authoritative document per topic (lint-enforced); runtime mount never "the architecture"; governance precedes automation; constitutional void-overriding; status independent of authority.
4. [S0037 L234–241] Two proposed invariants are stated and breached (V-1/V-2). "KnowledgeOS never contains business knowledge" is breached by ES-005.1. "PKS never contains reusable capability" is breached by the methodology trio sitting in a product path. This is a kernel/application-boundary finding.
5. [S0037 L243–259] Lifecycle chain: 2 of 9 links observed. KG→PKS is FALSIFIED (PKS is authored, not derived). Evidence→Platform is empty. The chain is "a destination, not a pipeline".
6. [S0037 L73–84, L121–135] PD-3 EKP is the most strongly evidenced domain. Core/Supporting/Generic is only conditional (PD-2 Method = would-be core), because under AIP-14 the product is core.
7. [S0037 L106–119] Runtime is below the bar (UL divergence falsified). PKS is not a platform domain (n=1, product-owned).
8. [S0037 L15–25] Supersession note: mission must precede domains. There are two missions in canon (AIP-14 serve PublicDigit vs the Charter hypothesis). Under AIP-14 extraction is premature. The ownership list is better read as three authority systems [L22–23].
9. [S0037 L261–271] Open forks SA-1..SA-6: who owns KnowledgeOS; whether PD-1/PD-2 governs PD-3; whether V-1/V-2 should be adopted given they are breached.

## S0038 — Engineering Knowledge Landscape (Group A; annotation L11–21 revises it)
1. [S0038 L35–55] Central finding: the repository does not lack a knowledge taxonomy. It has ≥3 (AKB layers, RQ-002 meta-model, ES-006.1 ladder). Unreachability regenerates the same knowledge.
2. [S0038 L35–43, L200] RQ-002: Knowledge = NATURE × REPRESENTATION × GOVERNANCE-STATUS (× AUTHORITY-SCOPE). Flat classification conflates dimensions ("one thing cannot change type by being encoded differently"). The document explicitly declines to propose a fourth taxonomy [L194–203].
3. [S0038 L111–126] Form / Method / Content / Evidence decomposition (BRM-1: decompose by classifying, never materialise a copy).
4. [S0038 L161–175] 9 rediscoveries. 4 of 9 suspected gaps were not gaps. Only GENESIS survived (a route from idea to first decision).
5. [S0038 L177–192] Knowledge lifecycle: discovery → assessment → challenge → saturation → decision → governance → execution → evidence → harvest → promotion. CHALLENGE and SATURATION are added as evidenced stages. The last arrow has 0 traversals.
6. [S0038 L144–149] Two load-bearing relations are only hypothesised: K-19 governs everything, and K-18→K-20 (CAP-001 conformance is never assessed).
7. [S0038 L151–159] Three kinds of duplication (healthy / historical / accidental). "Constitution" names ≥5 artifacts, a homonym [I].
8. [S0038 L21, L211] The annotation refutes "all 9 rediscoveries drew on unindexed regions" (#10 hit an indexed region). Discoverability "25–35%" is structural, not counted [L211].

## S0039 — Product Boundary Discovery (Group A; later annotated by the MVK test, L192–206, L286–290)
1. [S0039 L316–326] "What are we building?" = a reusable platform whose reusability has never been exercised. Portability verified per element, unverified as a whole. n=0 bootstraps.
2. [S0039 L192–206, L230–236] The MVK set is REFUTED IN PART (isolated bootstrap FAIL): no genesis path, EEP stages unsatisfiable at bootstrap, no strategic-DDD method, verdict-vocabulary collision. "Domain-free ≠ portable." "Portability of parts ≠ sufficiency of the whole."
3. [S0039 L33–98] Litmus "would it exist if PublicDigit disappeared?" gives five candidate products. Runtime is reclassified as an adapter boundary. Operational learning is "not missing; dispersed in six places, never once traversed".
4. [S0039 L129–133] Knowledge flow (platform→product) and authority flow (product over platform's right to evolve) run in opposite directions (AIP-14).
5. [S0039 L12–20, L135–153] ARB ruling "the relationship is the conclusion, not the starting point". Exactly 1 evidenced relationship, 5 candidates, 0 assigned patterns.
6. [S0039 L156–168] Lifecycle L-1..L-6: 2 of 6 evidenced; L-6 (evidence → improve platform) empty.
7. [S0039 L238–254] The boundary between operational learning and the platform runs between PROTOCOL (reusable) and RECORDS (never reusable).
8. [S0039 L271–282] Risks: AR-1 realised; AR-6 single-operator evidence; AR-7 "flywheel may be a pipeline" (unfalsifiable claim risk). G-9: "KnowledgeOS" is a recorded placeholder name [L268].

## S0040 — Engineering Improvement Cycle (Group B; restates S0044)
1. [S0040 L14–34] Process: Engineering Reality → Observation → Recommendation → Decision → Outcome → Assessment → Learning(FUTURE).
2. [S0040 L36–42] Rules: every transition is a recorded event; projections never write back and never infer missing data; recommendations advisory; INCONCLUSIVE is first-class; IGNORED is closure not a gap; falsifiable end to end.
3. [S0040 L45–46] Deliberately not a bounded context. These are application-level workflow objects plus read-side projections.
4. [S0040 L49–51] The staged DDD decision is owned by evidence (~6 months; lifecycle, invariants, versioning, policies must emerge).
5. [S0040 L30, L33] Assessment ∈ {SUPPORTED, PARTIALLY, NOT, INCONCLUSIVE}. Learning consumes assessments, never raw deltas.

## S0041 — Architecture Health Dashboard (Group B; a derived projection of the Synthesis Matrix)
1. [S0041 L14–19] Hypotheses all at "Candidate": dimensional-with-archetypes meta-model (5 converging passes), 9 archetypes (CONTAINER n=1, PURPOSE unratified), four partial orders, DP-n as capability anchor.
2. [S0041 L22] `generates → PKS` is "the distinctive bet", at n=0 inside and outside.
3. [S0041 L23] Evidence back-edge: informal n≈3; formal n=1 only at the instrumentation level (OE-KOS-3); canon-level n=0.
4. [S0041 L24] R-6 "guides product engineering": n=3 formal, "evidenced, unmeasured" (no unguided baseline).
5. [S0041 L28] PROVENANCE as a first-class concern is a Hypothesis (external input; "own domain" claim docketed).
6. [S0041 L27, L20] FREEZE/RETIRE are missing decision types (n≥3). Decision Model adoption is uneven (placement mechanised, catalog never consulted as a whole).
7. [S0041 L35–43, L45–54] Counters (docket 0/11 ruled, second adopter none) and "what Phase V watches for". Updates only on evidence or authority [L7].

## S0042 — docs/knowledgeos/README.md (placement only; no theory content)
1. [S0042 L5–9] Holds docs classified product-specific / domain knowledgeos; internal layout owned by the domain.
2. [S0042 L17–19] "Placement is derived, never chosen." This is a practice rule, not theory.

## S0043 — Operational Knowledge Principles (Group A; source article NOT read, L9)
1. [S0043 L11–20, L59–66] 5 of 6 proposed principles already exist (3 stated, 2 enacted unnamed). Adopt ONE: "knowledge is an executable engineering asset". Evidence: CAP-001 changed one decision (n=1), so demonstrated, not proven.
2. [S0043 L36–55] Three enforcement states: BOUNDARY (deny, 19), TRIGGER (ask, 22), ADVISORY (10+6 hooks and scripts). "Enforcement exists exactly where governance does not": every engineering capability is in the unenforced column.
3. [S0043 L42] The author's own claim "0 of 11 enforcing" was too strong and is corrected.
4. [S0043 L72–74] Rejected: the five-layer operating model (behavioural concepts, not domains; "repeats the D-4 error"). Also rejected: "mission vacant" and "build sensing".
5. [S0043 L76–90] Observation ladder manual → repeatable → automated → continuous. The repository sits at "rung 1½".
6. [S0043 L9] All attributions are second-hand [2nd-hand]. Any use as theory support is weak [I].

## S0044 — Recommendation Lifecycle Discovery (Group B)
1. [S0044 L8] Evidence base: one engine run, 10 recommendations, 0 decisions at Run 1, then 1 decision (Run 2).
2. [S0044 L37–39] RD-1: a recommendation is immutable once issued and responses are separate records. RD-2: identity = (rule, subject). RD-3: "structurally honest silence" (no grounds → no advice).
3. [S0044 L40] RD-4 hypothesis: identity may need three layers (Definition / Instance / Revision). Staged "think, never implement".
4. [S0044 L42] RD-6 actor provenance gap: the actor field stamps the git user, but the decider was the AI at the user's direction. Who-executed and who-authorized must be distinguished.
5. [S0044 L43, L56] RD-7 anomaly: a recommendation can be decided twice (unguarded re-decision). This is the first evidence that "Decision may carry its own invariants" (idempotency / supersession). Do not patch before the semantics are decided.
6. [S0044 L58–64] DDD classification refused for insufficient evidence ("resembles event-with-process more than an aggregate").
7. [S0044 L66–71] The measure is "engineering changed", never recommendations issued. Currently INSUFFICIENT. UL graded observed / unexercised / hypothesized [L24–31].

## S0045 — Operational Validation Report (Group B)
1. [S0045 L13–25] Verdict: governs "in the observed slices — YES, n=2"; the general claim is INSUFFICIENT EVIDENCE. "Better" decisions cannot be shown: no unguided baseline exists, none measurable retroactively.
2. [S0045 L69] All observed governance is PREVENTIVE (refuse/block/constrain). "It has proven it can say no; not that it can learn." The generative half (promotion, extraction) has never run.
3. [S0045 L44–59] 11 untested assumptions at n=0: `generates→PKS`, pattern extraction, retirement, the formal back-edge, attestation, the frozen BC map (never consulted), the DOMAIN classification (never changed a decision), PURPOSE (unratified), multi-product, verdict-vocabulary collision.
4. [S0045 L27–42] Evidence coverage matrix (EP-01 gating and verification-before-execution exercised and binding; promotion ladder 0; DA docket 0/11).
5. [S0045 L71–77] Discovery trigger review: NO new commission is justified ("absence of exercise is not evidence of a problem").
6. [S0045 L85–89] The outward question ("did engineering measurably improve?") has no baselines and needs non-existent dynamic instrumentation.

## S0046 — External Positioning Comparison Matrix (Group B)
1. [S0046 L37–49] Candidate verdict: KnowledgeOS is "an engineering-knowledge governance platform that happens to use ontologies" (internal, external-convergent and external-gap evidence). Adoption is the sponsor's act.
2. [S0046 L16–24] Provenance-ontology survey: the assertion/evidence side lacks a PROV-equivalent. Human-authority modelling is absent. PM-1: map to PROV, never reinvent.
3. [S0046 L34] "They model the RECORDS; KnowledgeOS governs the AUTHORITY over them."
4. [S0046 L51–57] Honesty table: 6 rediscoveries (XACML separation, staged promotion, CQRS projections, …), 6 "extensions" held internal, and `generates→PKS` marked "novel AND undemonstrated; the two must never be confused".
5. [S0046 L26–35] Six-area comparison. The graph is inverted here: "a projection, derived, non-authoritative".
6. [S0046 L9] Bounded form: holdings-first plus one fetch pass. This is a weak external base (second-hand, one survey) [I].

## S0047 — reviews/README.md (placement convention; one practice principle)
1. [S0047 L27] "Historical location is evidence; canonical future location is architecture."
2. [S0047 L13–25] Placement derives from subject → product/domain → knowledge space → artifact type → location, never from producing session or predecessor path.
3. [S0047 L29–33] Mechanism of drift: path inheritance via tokenRef ("consistency by repeated configuration instead of derivation"). 39 tokenRef hard references keep history in place.
4. [S0047 L40–42] Placement only. It changes no decision or record.

## S0048 — Knowledge-placement requirement registration (Group C)
1. [S0048 L14–26] Requirement: placement must be derived from knowledge subject / product / domain identity, never inherited from producer or session context. A "Knowledge Space/Scope" concept is a candidate for the EKS target.
2. [S0048 L10–12] Re-classification: not folder hygiene but "a product-boundary and knowledge-ownership problem". Failure = path inheritance as a substitute for derivation (precedent becomes implicit rule).
3. [S0048 L28–37] Maturity: policy, derivation and awareness exist. Mechanical enforcement is weak: "the rule exists, but the workflow does not make it hard enough to violate".
4. [S0048 L49–70] Addendum: a second requirement, Governed-Knowledge Distribution. "Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule." The chain governance knowledge → repository → ?discovery? → session context has an unreliable "?".
5. [S0048 L69–70] The two-specimen class evidence is thin (n=2, same estate, same hour) [I].

## S0049 — Independent review of Stage-1 attribution architecture rev 3 (Group C; reviewer declared independent, not attestable, L11–31)
1. [S0049 L38–60] F-1: INV-ATTR-2 as a read rule is satisfied by a total derivation (with ELSE) alone. The aggregate boundary is actually required by the uniqueness rule "at most one established assessment per claim", which makes the derivation definite. Boundary = {Claim · Assessments} + serialised establishment.
2. [S0049 L54–55] The uniqueness rule is presupposed in the singular throughout the approved model but never stated. Recommended: an ARB interpretive sentence, "not a new invariant".
3. [S0049 L64–78] W-2: a status dispute is an Assessment. A content dispute is Evidence plus claim supersession. Per-claimant claims are rejected and claimant-on-assessment is rejected. The rule becomes "one CURRENT claim per (act, dimension)".
4. [S0049 L100–108] W-3: assessor standing is itself a C4 Independence claim, defaulting to Declared. The regress terminates at a disclosed Declared or a human acceptance. No new concept (ES-005.4).
5. [S0049 L117–130] T-4: no live indirect assurance→gate path. Latent channels are role co-location (the Governance pen) and storage co-location. Closed gate-input-set constraint {transitions, grants}: "the only gate assurance may influence is the human".
6. [S0049 L82–96, L186–188] F-2: 13 events proposed, 10 dispositioned ("ten" itself a miscount), so AssuranceClaimSuperseded is a fifth domain fact. Erratum E-R1: the reviewer's own projection summed to 14, a count error of the same class it reports.
7. [S0049 L110–113, L132] Establishment authority is unnamed (F-6). Act→category classification ownership is unallocated (F-7).
8. [S0049 L11–31] Independence is DECLARED, not attestable. Its own ladder: "I claim this was independent."

## S0050 — F1–F8 repair record (Group C; Class-A self-review, L15)
1. [S0050 L19–50] R-1: minimal aggregate {Claim · its Assessments}; the "current outcome" is a derivation, never a member. current outcome := the established assessment (at most one) ELSE the dimension's weakest.
2. [S0050 L35–37] The uniqueness sentence recorded in the Human's words: "at most one established assessment per Assurance Claim at a time".
3. [S0050 L54–74] R-2: all 13 events dispositioned. 5 domain facts, 6 internal, 1 redundant, 1 read-side.
4. [S0050 L78–90] R-3: one current claim per (act, dimension). Status disputes are assessments; content disputes are evidence plus supersession.
5. [S0050 L94–98] R-4: assessor standing recurses into the Claim model. R-6: an assessment is established by the performing Assessor, conferring no authority [L116–122].
6. [S0050 L102–112] R-5: closed gate input set {transitions, grants}; extension needs an ARB act.
7. [S0050 L126–140] R-7: Governance classifies acts into C1–C5. R-8: AssessmentAccepted→AssessmentEstablished. "Established" ≠ business acceptance.
8. Same author lane as the design it repairs. Its own header says it is not an independent review [L15].

## S0051 — Independent verification of the F1–F8 repair (Group C)
1. [S0051 L57–68] 8 CLOSED / 0 PARTIAL / 0 NOT CLOSED. Verdict VERIFIED-WITH-NOTES.
2. [S0051 L74–110] Per-finding check against the accepted findings and the Human's words, not against labels.
3. [S0051 L113–125] Cross-cutting checks: supersession accounting, no approved invariant changed, anonymity invariants untouched, STATUS table consistent.
4. [S0051 L131–135] V-1 (note): rev 2 §7 is superseded in the traceability but not named in the header, so a reader could think it still stands. Enumeration precision only.
5. [S0051 L138–143] Independence is Declared. Process lineage is not model independence [I].

## S0052 — Decision summary on the review (Group C; author = Governance)
1. [S0052 L58–75] Q-decision table. Notable: Q-A2 canonical vocabulary DEFERRED ("vocabulary should not outrun unapproved architecture" [L23]). Q-B1/Q-B6 approved only after repair.
2. [S0052 L79–83] The Human's interpretive sentence is verbatim. The act is a performative one only the human can supply.
3. [S0052 L93] Three-level status never collapsed: aggregate recommendation APPROVED / bounded-context placement NOT CONFIRMED / target architecture NOT APPROVED.
4. [S0052 L4] The reviewer declined to write the disposition itself: "a producer recommending the disposition of its own findings would make the review its own advocate".
5. [S0052 L16–17] Approving a correct conclusion on an unsound argument is a defect ("approving the boundary on the current text").
6. [S0052 L89–91] Lane closure is a separate human COMPLETE act.

## S0053 — Stage-1 approval registration (Group C)
1. [S0053 L37–47] What approval does NOT do: it does not confirm the bounded context or approve a target; INV-ATTR-4/5/6 stay CANDIDATE; no schemas or implementation.
2. [S0053 L25–35] What is in force: the aggregate boundary, the outcome derivation, the 13-event disposition, one current claim per (act, dimension), the standing recursion, the closed gate-input constraint, the Assessor establishing, Governance classifying C1–C5.
3. [S0053 L42] Stage 2 is gated on baseline acceptance and a Human START.

## S0054 — Producer self-review disclosure (baseline Phase A; Group C)
1. [S0054 L20–46] D-1: "8 hooks" was eyeballed and the true count is 10. D-2: "four of seven components have no substance" is false (two of eight) and contradicts the same document's §3.1. D-3: declared 7 components vs registry 8 went unflagged.
2. [S0054 L30] "A wrong observation is worse than an acknowledged unknown."
3. [S0054 L50–52] Temporal change ("8 workflow records" is now 13) is NOT a defect. A baseline is assessed as at its date.
4. [S0054 L14, L73] Method: re-derive each claim by a different method. "The defects still in there are, by construction, the ones the author cannot see."
5. [S0054 L91] AST-016 cannot read handoffs (V-3); trust the fold, not the resolver.
6. [S0054 L56–66] Claims that survived re-derivation (16 assets, one-way AST-016→AST-015 via proc_open).

## S0055 — Phase A independent verification #1 (Group C)
1. [S0055 L6–8] 11 of 12 sampled Observed claims re-derived TRUE. 4 Declared probes are CONFIRMED by code. D-1/2/3 are confirmed, plus new V-A: the §6 diagram sums to 10 under a caption saying 8.
2. [S0055 L50–52] V-E: "8 workflow records" is retroactively unverifiable because the records are gitignored with no history. The baseline inherits the no-provenance defect it reports.
3. [S0055 L95–96] Option-A separation condition "cannot be established from the record" (one git identity across lanes). Independence is Declared.
4. [S0055 L108] The central thesis (declared ≠ executing; rule corpus is the substance, code thin) survived. Even the two wrong numbers kept their direction.
5. [S0055 L76] Probes classified Declared were not upgraded by the producer.
6. [S0055 L113] Do not accept v1.0 unamended.

## S0056 — Phase A v1.1 correction verification #2 (Group C)
1. [S0056 L39–51] 7 repairs independently re-derived and correct. No residual superseded figure.
2. [S0056 L76–91] No Declared claim was upgraded to Observed, although verification #1 offered it. "The corrector did not borrow the verifier's evidence."
3. [S0056 L117–118] N-1: the title says v1.0 over a v1.1 banner (the same shape as D-1/V-A). N-2: section order 6.2→6.4→6.3.
4. [S0056 L11–13] Verdict VERIFIED WITH NOTES. The defects are "document-integrity, NOT domain-model". Not acceptance-readiness.
5. [S0056 L25–30] MODEL separation is nil (the same model co-authored v1.1 and this report). Process separation is real, model separation is not.
6. [S0056 L35] Agreement between V#1 and v1.1 is treated as a hypothesis, not corroboration.
7. [S0056 L107–109] Weaker-citation observation (Phase-03A:107) dispositioned: no correction cycle.

## S0057 — N-1/N-2 correction delivery (Group C)
1. [S0057 L13] N-2 moved the block, never renumbered it, because the banner, §1 and V#2 reference §6.4 by that identifier (referential-integrity rule).
2. [S0057 L22–27] Four checks: two hunks, reference integrity, snapshot date unchanged, no classification changed.
3. [S0057 L35] This session is barred from verifying its own correction (R-34/P-2).

## S0058 — Verification #3 authorization (Group C; Governance)
1. [S0058 L18–35] The PO's prompt cited the wrong report (V#1 not V#2). Measured: N-1 count 0 vs 8, N-2 count 0 vs 7. "Checking the referent before registering is load-bearing."
2. [S0058 L45–51] V#2 ran with no assignment, no START and no grant on the record, so its independence is "not recorded anywhere".
3. [S0058 L77–88] `authorized` string-matches a scope against AUTHORIZED grants and never consults the session. A completed lane reports itself authorized.
4. [S0058 L39–43] The correction lane had never been closed (ACTIVE, holding ownership).
5. [S0058 L90–94] The evidence chain is split across two documentation roots.

## S0059 — Verification #3 report (Group C)
1. [S0059 L6–7] N-1 PASS, N-2 PASS. The diff is exactly two hunks. The correction chain is closed with no verified defect open.
2. [S0059 L21–30] Model separation from the corrector is NIL (same model). Mitigation: the checks are mechanical (diffs, byte compares), the class least exposed to a shared blind spot.
3. [S0059 L71] O-1: §6.1 never existed (pre-existing, not charged to any round).
4. [S0059 L73] Recommended spot-check by another model if the PO wants the different-model preference honoured.

## S0060 — Verification #3 acceptance (Group C; Governance)
1. [S0060 L7–24] The PO accepts only the verification evidence. The baseline is NOT accepted by this act.
2. [S0060 L24] V-E stays as the acceptance-context observation. Stage 2 stays gated.

## S0061 — Phase A v1.1 baseline acceptance (Group C; Governance)
1. [S0061 L12, L27, L31] Accepted: the reconstructed current-state baseline, blob 40026b12, as the foundation for Stage 2.
2. [S0061 L35–43] Acceptance authorizes Stage-2 ANALYSIS only. It does not approve bounded contexts, aggregates, implementation architecture, technology or migration.
3. [S0061 L45–47] V-E: the machine-state figures are "declarations of a moment that left no trace", accepted with eyes open.
4. [S0061 L14–25] A preconditions table checked before recording (V#1–#3, snapshot and scope preserved, no target-architecture leakage).

## S0062 — Stage 2 bounded-context confirmation (Group C; Architecture lane, reasoning marked Inferred)
1. [S0062 L14–20] Three context tests: language, ownership, change-reason. "A bounded context is a language boundary, not a code boundary." Rules-only contexts are real.
2. [S0062 L58–70] The single most important finding: the capabilities the system is actually organised around (authority registration & audit; independent assurance by role separation) have no declared context and no capability id.
3. [S0062 L26–34, L75–85] Verdicts. BC-6 is the cleanest boundary. BC-4's capability is real but the context is challenged. BC-5 "declared Core" is challenged: "the baseline cannot distinguish ambition from architecture". CBC-1 (Governed Session Orchestration) is promoted to a candidate BC-7.
4. [S0062 L38–44] BC-1 hides two knowledge systems, EKP and PKS (U-4), so it is confirmed only provisionally. BC-2 is overloaded: a second foreign language (orchestration) lives in the same component.
5. [S0062 L108–118] Coupling C-1..C-6: two languages in one component; authority state has no provenance; the writer boundary is convention-only (recordedBy unvalidated on 3 of 5 transitions); registry-first contract is bypassable.
6. [S0062 L174–197] OQ-1..OQ-8 and ADR-C1..C7 are carried open. R-37 (governance precedes automation) governs any remedy.
7. [S0062 L169–170] Relationship labels (CONFORMIST, SUPPLIER) are Inferred, not observed.

## S0063 — KOS-ARCH-BASELINE-002 commission (Group C)
1. [S0063 L19–25] Scope: 6→7 contexts, BC-7, CAP-14. The owned and not-owned candidate lists are hypotheses "to be tested, not inherited".
2. [S0063 L27–29] Two relationship studies: policy vs process (Governance ≈ "who may approve?", BC-7 ≈ "was approval recorded?") and knowledge identity vs workflow history ("what is known?" vs "how governed work progresses").
3. [S0063 L35] ADR-AIP-04 and the role model stay frozen because BC-7 discovery may itself produce role-ownership evidence ("designing roles before understanding their ownership").
4. [S0063 L37–41] Roadmap and rule: "Architecture may propose. PO/ARB decides."

## S0064 — Principal review registration (Group C; first pass mis-targeted)
1. [S0064 L5] The first-pass review is superseded by its author's own declaration (reviewed the wrong artifact, EM-IMPL-002). The substance is consistent with the second pass.
2. [S0064 L16, L177] Verdict VERIFIED WITH NOTES: "Architecture has completed the proposal phase, not the decision phase." The two boundary sentences are endorsed. Strategic boundary ≠ physical structure.
3. [S0064 L20–21] Note 1: keep the BC-7 name for now. Note 2: next is the Domain Model, never implementation.
4. [S0064 L213] Precision: "recognizes BC-7" is already in force by ADR-AIP-03. The act newly adopts the v2 model set.
5. [S0064 L34–165] A prepared startup prompt, inert until three gates pass. Example event names are non-canonical placeholders [L31].
6. [S0064 L179] The producing lane must stop ("the next action is not Architecture").

## S0065 — BASELINE-002 acceptance registration (Group C)
1. [S0065 L22–30] The seven-context model is authoritative. ADR-AIP-01's frozen table is amended by acceptance. BC-7 stays a Supporting Subdomain with CAP-14. Both boundary findings enter the model: "authorized vs recorded" and "meaning vs movement".
2. [S0065 L20] Accepted without a separate independent verification (noted factually). The acceptance rests on the Principal review and the PO/ARB's own authority.
3. [S0065 L31–36] Not decided: ADR-AIP-04 (the role model), BC-4's nature (open), implementation.

## S0067 — Architecture Landscape v2 (Group C; Architecture lane; the Principal review endorses it)
1. [S0067 L51–52] Grant is SPLIT: BC-7 owns the grant RECORD, not the authority content. The record "never manufactures authority". Human Act is corrected to NOT owned: BC-7 owns only the act's registration.
2. [S0067 L55–69] Owns vs preserves: an invariant's reason lives in its owner. BC-7 owns I-1 (single mutation owner), I-3, I-4, I-10. It preserves I-5, I-7, I-8, which belong to Governance.
3. [S0067 L111–146] Relationship 1: Governance decides whether an act was authorized; BC-7 answers whether it was recorded. The mechanism "cannot answer a policy question" (`authorized` ignores the session; START validates form, never entitlement). The inability is the boundary realised.
4. [S0067 L150–181] Relationship 2: "adjudicated identity versus folded occurrence". Knowledge truth is adjudicated; workflow truth is folded. Merging them fails (T-i). Mutual reference with no shared model.
5. [S0067 L79–94] Exclusions (policy, evidence meaning, architecture decisions, implementation, knowledge lifecycle, authority evaluation, attestation, acceptance decisions, clocks).
6. [S0067 L105–107] Name-fitness: the root is Work Item, so "Session" names an entity. Keep the name for now.
7. [S0067 L21] The entire platform, BC-7 included, is Supporting. The product is Core (AIP-14).
8. [S0067 L188–192] The change is to the model, not to physical placement.

## S0071 — BC-7 Domain Model proposal (Group C; Architecture lane; one verification still pending)
1. [S0071 L82–92, L151–152] Work Item is the aggregate root, resting on an invariant-scope test (all four invariants are single-record scoped). It is not resting on persistence shape alone.
2. [S0071 L94–104] Assignment as root is rejected: I-1 is an invariant over a SET of assignments, and HANDOFF is atomic across two. "A root cannot be smaller than the invariants it must hold."
3. [S0071 L117–126] Human Act as root is rejected: it has no identity or lifecycle in BC-7. "Importance is not ownership." It becomes the value object HumanActReference.
4. [S0071 L47–50, L71, L206–208] mutationOwner is never stored (0 occurrences in 15 records). It is a fold projection. T-1: no derived projection is ever stored or accepted as input.
5. [S0071 L128–145] One aggregate or two? No POSITIVE invariant spans lifecycle and grants. I-4 is a NEGATIVE spanning invariant needing a holder, so one root is chosen. This is the strongest rejected alternative, RA-4 [L219], left open as OQ-9.
6. [S0071 L249, L282, L293] Events are recorded facts, not published events. BC-7's only decisions are record-consistency decisions ("can refuse a malformed act, never an unauthorized one").
7. [S0071 L154, L259–260] CONTINUATION and FAIL are declared but never exercised (0/117), so they are a claim, not a fact.
8. [S0071 L326–330] BC-7 records the claim; the attribution model adjudicates it. Neither may duplicate the other.
9. [S0071 L346–354] OQ-4..OQ-11 are real forks (session-name uniqueness, Grant lifecycle, recorder-class closure, event vocabulary).
10. [S0071 L31, L73–74] It is a reconstruction of a running system under a modelling lens, with "Recorded Occurrence" and "Declared Attribution" proposed terms. This is closer to analysis than to independent derivation [I].

## S0091 — ADR-AIP-04 capability-discovery proposal + Amendment 1 + Correction #2 (Group D; one producer process 5e1dd9ee; verified by others)
1. [S0091 L1–38, L761–767] The question is capability ownership, not role creation. Ordering is capability → ownership → context/stewardship → human role → agent → service → technology. After two corrections, 3 capabilities are unowned (C-5 attestation, C-10 distribution, C-19 composition) and C-14 policy enforcement is CONTESTED.
2. [S0091 L174–192, L588] Core structural result: every invariant BC-7 owns is mechanically enforced, and almost every invariant outside it is declared only. Assurance is strong where executable and weak where it is prose.
3. [S0091 L250, L257, L185] C-5 separation attestation cannot be discharged by an agent, because a process cannot attest its own separation. INV-ATTR-2 is "declared, not attestable" with no owner.
4. [S0091 L97–98] C-10: the handoff tokenRef acted as a de-facto knowledge channel and taught a lane the superseded path (EKS-01).
5. [S0091 L113–124, L310–313] "Communication" is two capabilities. C-10 distribution is a mechanism. C-19 composition is a stewardship and fails the context test (borrowed language; changes with the audience).
6. [S0091 L273–280, L528–536] OQ-5 is answered as a split (lane roles are BC-7's; the authority model is governance-published). The consumption leg is downgraded to INFERRED.
7. [S0091 L395–404, L652–669, L698–727] Three absence-claim falsifications (a truncated head -5 search, generalising a narrow Tier-2 claim to "absent", an absence claim over one file type). F-3 was rebuilt on the narrower basis "no ADOPTING governed act", not "titles scarce".
8. [S0091 L749–773] The C-14 status moves absent → live narrow → CONTESTED. AST-007 (Tier-1) traces to CAP-09, so it may not be a C-14 instance at all.
9. [S0091 L169–172] The word "role" carries three meanings (lane role, persona, decision right). [S0091 L103] Governance as practised is not an actor, except for the strictly separated acceptance duty (F-9a).
10. [S0091 L785–796, L712–716, L815–821] The producing process of Amendment 1 had no assignment or declared producer, so C-5 manifests on the production side. Convergence with the brainstorm "Option B" cannot count as independent corroboration. SB-1 (conformance authority) is not cleared because two verifiers held Track-1 authorship.

## S0113 — C-10 Decision-4 decision prep (Group D; Governance input, not a decision)
1. [S0113 L9–20] Any enforcing option decides the CATEGORY by consequence (halt at START = control plane). This crosses D4's own "do not decide category" and D3's non-constitutive clause.
2. [S0113 L22–34] 3 of 5 conditions (Stale, Superseded, Invalidated, Disputed) have no detectable state and no owner. Adjudication is outside C-10 by design.
3. [S0113 L36–44] "Missing" is 100% of cases today (zero receipts exist), so a blocking response halts the whole estate. A receipt cannot be bound to a session because no gate reads identity (INV-ATTR-1).
4. [S0113 L63–77] Option E (session responsible) is the falsified status quo. Option F (compensating control) already exists as the recorded human START.
5. [S0113 L79–91] Preference: D as the target, decided non-constitutively; F as the transition; WARN+record as the floor. "Do not enable enforcement in the same act that defines it."
6. [S0113 L57–61] The CAP-09 precedent is weaker than claimed (read-only, deliberately closed).

## S0129 — Durability PO/ARB position registration (Group D; Governance; the same producer authored the ADR under decision)
1. [S0129 L10–19, L143–151] Decision authority is reserved to the human. A producer cannot approve its own ADR. If one process decides and registers, the humanActRef points at its own output. Recommendation ≠ decision.
2. [S0129 L87–94] Chain: Architecture proposal → Principal recommendation → PO/ARB decision → Governance registration.
3. [S0129 L51–62] Option B′: "the authority record was NEVER runtime's to export", so relocation as correct original placement, not a "snapshot". Execution Context ≠ Governance Evidence Context [L47].
4. [S0129 L43–44] "Execution location = authority location" is a hidden coupling (A rejected). "An aggregate cannot depend on accidental reconstruction" (C rejected).
5. [S0129 L45, L101–103] R-CONFLICT: preserve sequence integrity, provenance and reconstruction. "History ≠ Reconstruction Guess." (Recommendation ADOPT, not yet decided.)
6. [S0129 L107–113, L162–179] Placement DECIDED: existing governance applies (ES-005.4, reuse before creation). ADR:OQ-2 is already owned by the existing placement ADR.
7. [S0129 L187] First-hand evidence against C: commit de998173 committed the narrative lineage while 28 grants and 33 transitions stayed gitignored.
8. [S0129 L119] A governance refusal is cited as evidence the separation model works, by an interested party [I].

## S2377 — Formal Theory of Epistemic Gaps (Group E; an assistant derivation in chat form; a numbered-theorem style)
1. [S2377 L7–9, L319–336] Core: Gap is not I−K. K and I are heterogeneous. DEF-G1: Δ_t = {r ∈ R_t : Sat(K_t,r)=0}, the set of unsatisfied epistemic requirements. Numbers derive from it only when weights or metrics are supplied [L1181–1460, L1559–1603].
2. [S2377 L213–263] A requirement r = (id, type, scope, content, standard, priority, validity). The Ideal State I_t is defined from inquiry, context, knower and epistemic contract EC (not "all knowledge").
3. [S2377 L352–443, L1463–1555] Zero ⇔ Δ=∅ ⇔ K⊨EC. THM-G1 and THM-G3 follow by definition (tautological) [I]. The substance is the definition of Sat, which is left OPEN [L2633–2650].
4. [S2377 L461–834, L2283–2327] Ten gap classes G1–G10 (coverage, value, uncertainty, warrant, contradiction, model, observability, staleness, identity, representation). The text announces "seven primary gap classes" [L461] but lists ten [I]. THM-G6 "every unsatisfied requirement belongs to a declared class" is exhaustive only by the declaration [I]. Overlap is allowed.
5. [S2377 L956–1022, L2329–2443] Improvement ⇔ Δ_{t+1} ⊆ Δ_t under a fixed EC. The gap lives in (P(R), ⊆) with Zero as the bottom element. This is a power-set order, not a lattice of knowledge states.
6. [S2377 L1754–1833] Gap comparison requires contract compatibility (a mapping M between requirement sets), or "moving the goalposts" can fake Zero (THM-G4).
7. [S2377 L2169–2260] Internal tension: a new investigation can ENLARGE Δ (a hidden requirement is exposed) while knowledge improves, which contradicts "improvement ⇔ Δ shrinks". The text adds a second measure (gap observability) but does not reconcile it with THM-G2/G5. Zero also depends on which requirements were represented [I].
8. [S2377 L1073–1177] Two incompatible direction schemes: Δ⁺/Δ⁻ (missing / violating) versus three directions (Deficit / Conflict / Insufficiency).
9. [S2377 L1870–1940] The gap is relational and purpose-relative: Gap(K, EC), not Gap(K).
10. [S2377 L2008–2090] Gap → Proposal (Lord) → Decision (Sārathi): not Lord → Gap. These are theory-internal role names with no corpus link in this source [I]. Slip: age = t − now [L1346] has the wrong sign [I].

## S2834 — What KnowledgeOS makes possible beyond conventional AI/RAG (Group E; two voices: L1–1881 is an assistant's capability catalogue and L1883–2245 is a reviewer-style reply)
1. [S2834 L1–6, L1743–1757, L1835–1845] The strongest realistic claim is not truth but that epistemic status becomes explicit, traceable, revisable, contract-relative and machine-operable. A system "without silently collapsing uncertainty, contradiction, provenance, semantics or responsibility".
2. [S2834 L57–89, L1613–1647] Stage chain: Reality → Observation → Information → Representation → Proposition → Evidence → Warrant → Knowledge → Determination → Decision → Authorization → Action → Outcome → new evidence.
3. [S2834 L139–199, L285–333, L864–922, L1293–1307] Distinctions carried: Unknown ≠ False; evidence ≠ truth; truth ≠ determination (Determine(p|C), with a soundness bridge made explicit); decision ≠ authorization; uncertainty ≠ ignorance; semantic vs factual uncertainty; contradiction preserved, not exploded.
4. [S2834 L335–437] Inquiry semantics q=⟨X,Y,Γ,τ,Π,Λ⟩; representation adequacy q = g∘T; the fiber T⁻¹(r) shows what is lost; safe reduction relative to an inquiry.
5. [S2834 L441–542] Revision is non-monotone with time-indexed K_t. Candidate → Supported → Determined → Committed. Discovery ≠ acceptance. (The governance ladder reappears at [L1443–1465].)
6. [S2834 L684–704] Capability 22 ties gap = unsatisfied requirements to the theory in S2377. It depends on Sat, which S2377 leaves open [I].
7. [S2834 L1884–1981] Reviewer: "After implementation, KnowledgeOS can…" overclaims. Capabilities need a theory-status and an implementation-status column. A traceability audit should come before further writing.
8. [S2834 L2085–2162] The 60 capabilities are NOT 60 primitives. "Capabilities ≠ Kernel Operators." Most derive from foundations (epistemic state, provenance, evidence, inquiry, hypothesis space, determination, temporal state). Capability and kernel necessity are two separate audit columns.
9. [S2834 L1984–2035, L2039–2081] The "closed feedback loop" is only a candidate model (Outcome ≠ Knowledge; Observation ≠ Evidence). "Knowledge" is overloaded; the safer split is E_t / A_t / K_t with a separate factive Knows relation.
10. [S2834 L1413–1439, L1409] Minimality claims need Necessity and Sufficiency established separately. "𝒦_min" is cited as a case where a stipulated architecture silently became "theory". The 60-capability list is a catalogue, not evidence [I].

## S0164 — KOS governance role cost optimization (Group D; composite scratch log: cost-optimization chat + pasted lane outputs from several sessions; L1–2520 read in full, L2520–3328 sampled)
1. [S0164 L262–282] Role-boundary invariants kept fixed: AUTHOR ≠ INDEPENDENT REVIEWER; AUTHOR ≠ ACCEPTANCE AUTHORITY; GOVERNANCE ≠ TECHNICAL DESIGN AUTHORITY; AI AGENT ≠ GOVERNANCE AUTHORITY.
2. [S0164 L650, L709] "Automation may reduce assurance cost but must never manufacture authority." Automation handles evidence production and humans retain authority.
3. [S0164 L369–404] Do not automate independence. Detect "CONFLICT DETECTED", never output "INDEPENDENCE = TRUE".
4. [S0164 L408–470] Assurance classes A–E replace "full vs light review". "Batch the work; don't merge the semantics." The first answer's 30–80% savings figures [L55–116, L214–223] are asserted without data, and the second answer corrects it [I].
5. [S0164 L1196–1204, L1274–1285] The contract-neutrality finding: the neutrality claim was implicitly over a layer (PHP comprehension) nobody specified. The reference parser diverges because an accessor discards qualifier kind: "nobody has ever decided this; two library defaults stand in for a decision".
6. [S0164 L1314–1323, L1475–1485] Layer model L0–L5; conformance boundary placed at the L3 fact model. Contract rules live in L4–L5. The Human adopts "L3→L5" [L1636].
7. [S0164 L1356–1366, L1527–1529] A grammar approximated by regexes has no closure by probing. Differential testing "detects disagreement, never shared blindness".
8. [S0164 L1265–1273] Two pinned decisions collide (semantic "dependency, not syntax" vs "as written") with no precedence rule. A contract with a precedence-less pair is incomplete [I].
9. [S0164 L2059–2079, L2098–2125] Independence = process identity + lineage + working-context provenance. "Not attested, never PASS." A HANDOFF is refused for a lane that was never STARTed (off-record work). The START is transcribed, not back-dated.
10. [S0164 L2233–2300, L2420–2432] D5 establishment criteria E1–E10 plus 17 counterexamples. Establishment ≠ category. Four evidence dimensions (SUBJECT · EPISTEMIC · PROVENANCE · REPRODUCIBILITY); INDEPENDENT refused; Option A unsatisfiable because E10 needs a history that cannot exist at establishment.
11. [S0164 L2459–2520] Migration amendments AMD3–AMD6: RC-1 circular phase dependency, RC-4 self-satisfying bounded claim. (Rest sampled only.)

## S0913 — Step 25U: multi-agent knowledge, conflict, consensus, trust (Group E; assistant chat, "PASS" verdict by the same assistant)
1. [S0913 L40–199] Agent ≠ Evidence. Trust is contextual, Trust(agent, domain, context, time). Expertise ≠ Authority. Trust ≠ Authority.
2. [S0913 L229–312, L874–892, L1507–1518] Agent independence ≠ information independence. Common-source lineage requires an evidence dependency graph. "Corroboration should be measured in independent information, not number of agents."
3. [S0913 L314–433, L1081–1104] Apparent conflict (different time or context) vs genuine conflict. 9 conflict types. Conflict is first-class and never silently erased.
4. [S0913 L525–562, L1146–1177] Resolution pipeline: identity → time → context → semantics → dependency → reliability → authority → corroboration. "Unresolved" is a valid terminal state (→ Zero → Lord → acquire evidence).
5. [S0913 L564–599, L1459–1505] TrustScore ≠ truth selector. Likelihood ratios 19×15 = 285 only if independent (arithmetic correct).
6. [S0913 L736–806] Trust itself is knowledge with provenance. Self-reinforcing reputation (FeedbackBias) must not recurse without independent validation.
7. [S0913 L808–990] Consensus should be IndependentEvidenceConvergence, not majority or weighted voting. Epistemic conflict ≠ Byzantine consensus.
8. [S0913 L1321–1432] Invariants: trust never substitutes for authority; trust changes epistemic weight but creates no truth; capability ≠ authority; permissions CanObserve/Assert/Derive/Approve/Execute.
9. [S0913 L1108–1144] Resolution record = (Conflict, Method, Evidence, Decision, Authority, ResidualUncertainty).
10. [S0913 L1522–1618] The "falsification tests A–H, PASS" are expected outcomes stated by the author; no execution is shown [I]. L1758–1806 hands off to Step 25V (semantic alignment across bounded contexts), so the semantic-interoperability fork is open.

---

## Cross-source table

### Convergences (lineage-corrected: "n lines" counts independent lineages, not documents)
| Theme | Sources | Lineages | Note |
|---|---|---|---|
| Authority is not recording / trust / evidence; execution ≠ authority | S0037 (I-2, I-7), S0049/S0050 ("only gate assurance may influence is the human"), S0067, S0071, S0091, S0129, S0164, S0913, S2834 | 3 (Groups A/C/D governance; Group E theory; they arrive at the same slogan separately) | The strongest cross-line convergence. The estate line is process rules and the theory line is epistemic distinctions [I] |
| Declared ≠ observed; absence of registration ≠ absence | S0037 grades, S0054, S0055, S0062 (Inferred), S0091 (3 falsifications), S0045, S2834 stipulated/derived/observed | 2 | The estate repeatedly made this error itself (S0054 D-1/D-2, S0091 V-1/V-2) |
| Unknown / conflict / unresolved kept as a first-class state | S0044 (INCONCLUSIVE), S0040, S2377 gap classes, S2834 Unknown/Conflict, S0913 Unresolved | 2 | The theory line is more formal. The estate has INCONCLUSIVE and no formal semantics |
| Append-only / supersede, never rewrite; history is not reconstruction | S0037 I-1, S0049/S0050 supersession, S0053, S0056, S0071 T-3, S0129 R-CONFLICT, S2834 history | 2 | |
| Derived projections are never stored or written back | S0040, S0041, S0037 (portal derived), S0071 T-1 (mutationOwner), S2377 (numeric gap derived from set) | 2 | A candidate law: state derived by fold, never stored |
| Evidence → platform back-edge never traversed (n=0) | S0036 PG-2, S0037 L9, S0038, S0039 L-6, S0041, S0043, S0045 | 1 (Groups A/B, same author) | Counts as one line of support despite 7 documents |
| Kernel ≠ catalogue of capabilities | S0036 (kernel needs a test), S2834 L2085–2162 (capabilities ≠ kernel operators) | 2 | Convergent in principle, but they define "kernel" differently (see below) |
| Boundary by language / ownership / reason-to-change (not by existence) | S0062, S0067, S0071, S0091 (five-test matrix) | 1 (Group C/D) | |

### Tensions and forks
| # | Tension | Sources | Status |
|---|---|---|---|
| T1 | **What the kernel is.** Process-kernel (domain-free engineering method: governance, discovery, verification, capability pattern) vs epistemic-kernel (proposition, evidence, warrant, Sat, gap, determination). No source maps one onto the other | S0036 §5 / S0039 §6 vs S2377, S2834, S0913 | Open fork, the central one [I]. The estate line defines kernel by transfer-test; the theory line by derivation |
| T2 | **Does a kernel exist at all?** Its sufficiency is untested (MVK FAIL, n=0 bootstraps) vs the claim "kernel larger than credited; 4 of 8 bootstrap steps ready" | S0039 L192–206 vs S0036 L188, L238 | S0036 is Group A and was written before the MVK result appeared in S0039 [I] |
| T3 | S0036's "transferred with zero translation" for the EEP vs the MVK test finding the EEP's mandatory stages unsatisfiable at bootstrap | S0036 L102–113 vs S0039 L198, L204 | A contradiction inside Group A, not reconciled |
| T4 | Flat classification (S0036) vs dimensional meta-model (RQ-002, S0038, S0041 "Candidate") | S0036 vs S0038 L35–43 | S0038 refutes S0036's method. Which dimensions are kernel-level is unresolved |
| T5 | KnowledgeOS single product with a single "Platform Definition" (S0036) vs three overlapping domains with no owner (S0037) vs governance-platform-using-ontologies (S0046) vs epistemic operating system (S2834) | S0036 L84, S0037 L210, S0046 L37, S2834 L1761 | Four different identity claims, none verified at n≥2 |
| T6 | Gap: "improvement ⇔ Δ shrinks" vs "a new observation can enlarge Δ without knowledge decreasing"; ten classes vs a three-way scheme; Sat undefined | S2377 L956–1022 vs L2169–2260, L1073–1177, L2633–2650 | Internal; the Gap algebra is a sketch, not a proof [I] |
| T7 | S2834 capability 22 and the "Gap/Zero" reference rely on S2377's open Sat; the reviewer reply in S2834 itself asks for status discipline | S2834 L684–704, L1956–1981 | Dependent on an open item |
| T8 | Roles: the six "Engineer" personas vs lane roles vs decision rights. "Role" has three meanings. Absence-based claims were falsified three times | S0091 L169–172, L698–727 | The estate's role model is unresolved (ADR-AIP-04 deferred) |
| T9 | BC-5 "declared Core" and the platform's Core being the method (S0036/S0037) vs AIP-14 making the product Core and the platform Supporting | S0036 L94, S0037 L125, S0062 L47–49, S0067 L21 | Core is a strategy declaration, not a measurement; the ARB must restate it |
| T10 | Policy enforcement: "absent" vs "live narrow" vs "CONTESTED". Enforcement sits in the runtime adapter and not in the capabilities | S0043 L52–53, S0091 L749–773 | C-14 existence is still open |
| T11 | Independence: all verification is "Declared, not attestable"; model and process separation are partial or nil (S0056, S0059); one verifier's authorship was recoverable mechanically (S0091 C2.4) but not attestable in general | S0055 L95–96, S0056 L25–30, S0059 L21–30, S0091 L796 | Unresolved at the root (C-5). Acceptance rests on declared independence |
| T12 | Trust and conflict resolution as a pipeline (S0913) vs the estate's authority model in which trust never substitutes for authority. Where does the epistemic Authority sit? | S0913 L1381–1432, S0129, S0091 | The theory line has no owner or process for "who adjudicates" [I] |
| T13 | Single-record aggregate (S0071, positive-invariant test) vs the reasonable two-aggregate split (RA-4) | S0071 L128–145, L219 | Open (OQ-9) |
| T14 | Quantified savings (S0164 L55–116) and test PASS verdicts (S0913 L1522–1618) vs source-less numbers and unrun tests | S0164, S0913 | Weak evidence presented as results [I] |
| T15 | What counts as an "observed" estate fact changes over time: 8 → 13 → 14 → 15 → 16 workflow records; 39 → 40 tokenRefs | S0054 L52, S0067 L36, S0071 L31, S0091 L510 | Always cite the observation point |

### Kernel/application boundary lines found
- S0036 L177–189: the domain-free / binding-free / evidence-free test. S0037 L238–241: V-1 and V-2 breached. S0039 L190–236: the MVK hypothesis (FAIL).
- S0067 L55–69 and S0071 L249–293: BC-7 makes only record-consistency decisions and never policy decisions. This is a clean application-level instance of "record vs authority" [I].
- S0040 L45–46: not a bounded context. S0091 L124: C-19 "fails the context test".

### Kernel candidates visible in this set
These are my reading from the sources, not an assertion that they are the kernel. Each carries its dissent.

1. **Authority as an act distinct from record** (S0049–S0053, S0067, S0071, S0091, S0129, S0913). Dissent: the sources are all estate-governance lineage, and the theory line has no authority primitive.
2. **Declared-not-attestable attribution** (INV-ATTR-2; S0049, S0055, S0091). Dissent: it is a limitation, not a primitive.
3. **Fold/derivation of state from an append-only record, with the derivation never stored** (S0049, S0071, S0129, S2377 gap derivation). Dissent: this is established only for workflow records (n=1 domain).
4. **A requirement-satisfaction gap** (S2377). Dissent: Sat is undefined and the algebra is a definitional sketch.
5. **Independence of evidence and provenance** (S0913, S0046). Dissent: it is an epistemic claim with no estate observation.
6. **Candidate → canonical promotion ladder** (S2834 L1443–1465, ES-006.1 in S0038/S0039). Dissent: 0 traversals in the estate.
7. **Conflict / unresolved as terminal states** (S0913, S2834). Dissent: tests are declared, not run.

### Not reached in this inventory (stated, not inferred)
- S0164 L2520–3328 beyond the grep and tail sample.
- Whether S0913/S2377/S2834 reference corpus items I was not given (25N, Parts LXXIX, 𝒦_min).
- Whether any of the Group D lanes were later corrected in sources outside this set.

Absence of a line in an inventory is a search-and-reading limit, not evidence of absence in the source [I].
