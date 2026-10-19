*Saved verbatim 2026-10-19 from reviewer A's (Opus) step-1 hand-back (blind). Harness: commissioned 15:29:46Z, received at this save.*

# STEP 1 INVENTORY — CONSOLIDATION S0036–S0065 — reviewer A

**Mode:** consolidation review, step 1 (blind). **What I opened:** REVIEWER-INSTRUCTIONS.md, SOURCES.md and the 39 listed sources. Nothing else under W/06-theory/. I did not open RESEARCH-PURPOSE.md, and I opened no HOLDOUT, seal, quarantine, _batch_input or .env file. I wrote no files.
**Independence:** my independence is process independence only. I am a separate process from the Professor, but I may be the same model family.
**Notation:** `[Snnnn Lx–y]` = line anchor. `[I]` = my own judgment, not the source's claim. Within each source, items are ranked by importance to a theory of a knowledge-governance kernel.

**Reading coverage for the very long sources:**
- **S2377:** read in full. Blank lines and `$$` lines were filtered out; line numbers are preserved.
- **S2834:** read in full, with the same filtering.
- **S0913:** read in full, with the same filtering.
- **S0164 (3,328 lines):**
  - read fully: L1–40, L240–915, L1186–1635, L2042–2400;
  - L916–1185: skimmed. It is mostly an upload log.
  - L1635–2042: grep-sampled, with L1730–1830 read.
  - L2400–3328: read through a filtered line grep. This part is session transcripts of the durability/AMD chain, not read line by line.

---

## Block A — 2026-08-02…08-04 corpus (one author voice; S0036–S0046)

### S0036 · Platform Capability Model (2026-08-02)
**Lineage:** same day and same first-person author as S0037, S0038, S0039 and S0043. It was annotated afterwards by S0038's findings [S0036 L11–22].

1. **Defines KnowledgeOS as a platform whose product is *method*, owning no product's knowledge.** Core = the method; the runtime adapter = generic. [S0036 L86–93]
2. **Self-annotated as refuted in method.** Its one-class-per-capability flat taxonomy commits the error RQ-002 refutes: "one thing cannot change type by being encoded differently". Classes should be read as values on AUTHORITY-SCOPE × GOVERNANCE-STATUS. [S0036 L18–20]
3. **Kernel test:** domain-free ∧ binding-free ∧ evidence-free. 12 KERNEL rows. C-08 is blocked by the product binding SD-1. C-09…C-12 are kernel only "after Tier-3 separation" (form reusable, content is case law). [S0036 L179–189]
4. **Boundary rulings.**
   - C-08…C-12 are *one* capability with five responsibilities (single lifecycle and owner).
   - C-13…C-17 are *five* (separate invariants).
   - "Capabilities are not contexts." [S0036 L135–143]
5. **The evidence→promotion arrow C-23→C-06 has 0 traversals.** Every service is advisory: "0 of 25 capabilities are ENFORCING". [S0036 L165, L198, L245]
6. **Uncertainty carriage (4 register forms) and a discovery stopping rule are named as kernel instruments.** [S0036 L55–57, L110–111]
7. **Bootstrap: 4 of 8 steps ready; the beginning and end are blocked.** `init` is premature (P3 needs n≥2). [S0036 L227–239]
8. **Failure mode "proposing before searching" counted as six occurrences.** An index is the cheapest fix. [S0036 L81, L252, L269]
9. **[I] Same-day contradiction:** C-13 "1 execution, 1 decision changed" [S0036 L114] vs S0039 "CAP-001: 0 executions, 0 decisions changed" [S0039 L164, L265].

### S0037 · Strategic Architecture Discovery (2026-08-02)
**Lineage:** same author. It is annotated as superseded in sequence by a Mission Discovery that is not in this set [S0037 L15–25].

1. **Thesis:** KnowledgeOS is "not yet ONE thing" but three separately governed domains with *no single owner*: governance (DA), method (sponsor + ARB), EKP (an individual). "Under-OWNED, not under-architected." [S0037 L210–220, L328]
2. **Seven invariants graded OBSERVED.** I-1 append-only rulings · I-2 plan approval ≠ execution authorization · I-3 one authoritative document per topic (machine-enforced) · I-5 governance precedes automation · I-6 a constitutional principle voids conflicting artifacts · I-7 status independent of authority. [S0037 L222–232]
3. **Two stated invariants are already breached.** V-1 "KnowledgeOS never contains business knowledge" · V-2 "PKS never contains reusable capability". [S0037 L234–241]
4. **Lifecycle chain: 2 of 9 links observed.** Graph→PKS is *falsified* (PKS is authored/accepted, not derived). Evidence→Platform is empty. "A destination, not a pipeline." [S0037 L245–259]
5. **Ownership is used as the strongest boundary criterion.** Bar: ≥3 of Round47-OP's 9 criteria; only OBSERVED is authoritative. [S0037 L10–11, L31]
6. **Projections carry `authority: derived` (observed in production).** [S0037 L183, L252]
7. **Runtime is below the bar (2 criteria); PKS is product-side, not a platform domain.** [S0037 L106–119]
8. **[I]** The PD-3 domain verdict leans on one frontmatter field (`owner:`). PD-1's count of 6 includes one INFERRED criterion despite the rule that "only OBSERVED is authoritative" [S0037 L57]; it would still pass at 5.

### S0038 · Engineering Knowledge Landscape (2026-08-02)
**Lineage:** same author. It annotates S0036 and corrects its own §10 later [S0038 L11–21].

1. **Central claim:** the repository has ≥3 knowledge taxonomies already. The failure is *unreachability that regenerates knowledge*, not a gap. [S0038 L47–55, L221]
2. **Recommends RQ-002's dimensional model:** Knowledge = NATURE × REPRESENTATION × GOVERNANCE-STATUS (× AUTHORITY-SCOPE). Lifecycle and validation are *derived* (f(Nature), f(Representation, Nature)). It declines to write a fourth flat taxonomy. [S0038 L35, L196–203]
3. **Lifecycle inferred from the corpus:** DISCOVERY → ASSESSMENT → CHALLENGE → SATURATION GATE → DECISION → GOVERNANCE → EXECUTION → EVIDENCE → HARVEST → PROMOTION. The last arrow has 0 traversals. [S0038 L181–192]
4. **Separates form from content:** reusable FORM/METHOD vs product CONTENT/EVIDENCE. Decomposition is allowed; materialization is barred. [S0038 L111–126]
5. **Three kinds of duplication:** healthy repetition · historical evolution · accidental. "Constitution" is used for ≥5 artifacts — a homonym. [S0038 L151–157]
6. **Genesis (idea → first decision) survives the sweep as genuinely missing.** [S0038 L165, L244]
7. **Self-refutation:** the claim "all rediscoveries drew on unindexed regions" was refuted. Occurrence #10 hit an indexed region, so the cause is also failure to read the index. [S0038 L21, L212]
8. **[I] Source defect:** "30 families, one class each" [S0038 L253], but the class summary sums to 32. K-25 is counted in both Review and Assessment; K-05 in Governance Rule and Verification. `check_roles.php` is listed both as a working service (K-21) and as "Unclassified". [S0038 L83, L102–109]

### S0039 · Product Boundary Discovery (2026-08-02)
**Lineage:** same author. Annotated after the MVK bootstrap test (record not in set) [S0039 L192–206, L286–288].

1. **Portability of parts ≠ sufficiency of the whole.** ES-005.3 tests elements, not the set. The MVK bootstrap test (n=1) returned **FAIL**. [S0039 L232–236, L275]
2. **"Domain-free is not the same as portable."** The methodology trio is 73% of the MVK and mostly case law. [S0039 L202]
3. **Knowledge flow and authority flow run in opposite directions.** The platform supplies rules downstream; the product holds authority over the platform's evolution (AIP-14). [S0039 L129–133]
4. **Relationship patterns are named only where canon states them.** 1 evidenced relationship, 5 candidates, 0 patterns assigned. "The relationship is the conclusion, not the starting point." [S0039 L16–20, L137–154]
5. **Operational-learning boundary = protocol (reusable) vs records (never reusable).** [S0039 L240–252]
6. **Lifecycle: 2 of 6 transitions evidenced.** The loop-closing transition L-6 is empty, so "a pipeline, not a flywheel"; if L-6 never traverses, "earns its abstractions from evidence" becomes unfalsifiable. [S0039 L156–168, L281]
7. **Naming SP-5 as a domain "would give a non-functioning mechanism a better title."** Naming ≠ existence. [S0039 L95–98, L282]
8. **Gaps:** G-11…G-14 (genesis · role scaling · acquisition · strategic method). Two closed verdict vocabularies collide (ES-003.1 vs CAP-001 §5). [S0039 L199, L261]

### S0040 · Engineering Improvement Cycle (2026-08-04)
**Lineage:** its evidence base is S0044 [S0040 L8].

1. **Process chain:** Observation (verdict-free) → Recommendation (advisory) → Decision (ACCEPTED/IGNORED/DEFERRED) → Outcome (assessment-free) → Assessment (SUPPORTED…INCONCLUSIVE) → Learning (future). [S0040 L14–34]
2. **Every transition is a recorded event; projections never write back.** [S0040 L38]
3. **IGNORED is closure, not a gap; projections never infer missing data; INCONCLUSIVE is first-class.** [S0040 L24, L40]
4. **The process is falsifiable end to end**, including "KnowledgeOS itself changes nothing". [S0040 L41]
5. **Staged DDD rule:** bounded-context status is decided by watch criteria (lifecycle · invariants · versioning · policies · effectiveness · history · analytics) after about six months, "not preference". [S0040 L50]
6. **Explicitly not a bounded context.** The concepts live as application workflow objects plus read-side projections. [S0040 L45]

### S0041 · Architecture Health Dashboard (2026-08-03)
**Lineage:** derived from the Synthesis Matrix (not in set). Self-declared non-authoritative.

1. **A projection is non-authoritative until attested.** Rows change only when evidence lands or an authority rules — "never by re-reasoning". [S0041 L5–7]
2. **Hypothesis: four partial orders** (authority · production · realization · containment). Status: Candidate. [S0041 L18]
3. **"generates → PKS" is the distinctive bet, at n=0.** [S0041 L22]
4. **Evidence back-edge:** formal n=1 at the *instrumentation* level (OE-KOS-3); canon-level n=0. [S0041 L23]
5. **PROVENANCE as a first-class concern is a Hypothesis.** It must be settled by operational evidence, "not literature". [S0041 L28]
6. **Failure signals listed:** decisions taken against the catalog; classifications the archetypes cannot express; freezes without authority; informal retirement. [S0041 L46–54]
7. **[I] Temporal tension:** this source counts 4 OE entries [S0041 L41]; S0045 counts 2 [S0045 L9]. S0045 is an earlier run on the same date.

### S0042 · docs/knowledgeos README
**Lineage:** a governance pointer.

1. **"Placement is derived, never chosen."** Resolve it; do not hard-code the root. [S0042 L19]
2. **The root's owner is "the KnowledgeOS domain"; the internal layout is domain-owned.** [S0042 L8–9]
3. **[I]** Low theory content. It is the earliest statement of the derivation-over-choice rule developed in S0047 and S0048.

### S0043 · Operational Knowledge Principles (2026-08-02)
**Lineage:** same author as S0036–S0039. It cites an article it did not read (second-hand) [S0043 L9].

1. **Three-term enforcement vocabulary:** BOUNDARY (deny, blocks) · TRIGGER (ask, interrupts) · ADVISORY (mentions and proceeds). [S0043 L44–50]
2. **Correction of S0036/the Fitness Assessment:** "0 enforcing" was too strong. 41 controls are enforced (19 deny + 22 ask). It is *capabilities* that enforce nothing. [S0043 L40–42, L52]
3. **"Enforcement exists exactly where governance does not, and is absent exactly where it does."** [S0043 L53]
4. **One principle adopted:** "knowledge is an executable engineering asset". Evidence is n=1 changed decision; "demonstrated, not proven". [S0043 L59–64]
5. **Five of six proposed principles already exist** (three stated, two enacted unnamed). Naming is cheap; legislating is not. [S0043 L15, L23–34]
6. **Observation ladder:** manual → repeatable → automated → continuous. Current position is "rung 1½". [S0043 L78–90]
7. **Behavioural operating concepts are not strategic domains.** Restructuring around them repeats the D-4 error. [S0043 L72]

### S0044 · Recommendation Lifecycle Discovery (2026-08-04)
**Lineage:** an evidence instrument for S0040.

1. **RD-1:** a recommendation is immutable once issued; responses live in *separate* records. [S0044 L37]
2. **RD-7:** a recommendation was decided twice (an anomaly). Exactly-once vs last-wins is a *domain* decision; do not patch the guard before the semantics are decided. [S0044 L43]
3. **RD-6:** actor-provenance gap — the record conflates who-executed with who-authorized when AI decides at human direction. [S0044 L42]
4. **RD-4 (hypothesis):** identity may need three layers (Definition / Instance / Revision). [S0044 L40]
5. **RD-2:** identity = (rule, subject); re-issuance after the subject changes is untested. [S0044 L38]
6. **RD-3:** absence of grounds produces absence of advice ("honest silence"). [S0044 L39]
7. **DDD classification refused for insufficient evidence.** Discriminator: lifecycle/versioning emerging ⇒ domain; mere transformation ⇒ service. [S0044 L62–64]
8. **Vocabulary is graded observed / unexercised / hypothesized.** States enter the vocabulary "only when engineering performs them". [S0044 L26–31]

### S0045 · Operational Validation Report (2026-08-03)
**Lineage:** same corpus as S0041.

1. **Asymmetry:** everything observed is *preventive* governance (refuse, block, constrain). The *generative* half has not run once — "proven it can say no; not yet proven it can learn". [S0045 L69]
2. **"Governs engineering work":** yes in the observed slices (n=2); insufficient evidence as a general claim; "better decisions" is unmeasurable because no unguided baseline exists. [S0045 L17–23]
3. **Eleven assumptions at n=0 enumerated:** generation · retirement · formal back-edge · attestation walk · verdict-vocabulary collision, and others. [S0045 L46–58]
4. **Absence of exercise is not evidence of a problem.** The discovery-trigger review answered NO. [S0045 L75–77]
5. **Outward question staged:** did engineering measurably improve? Baselines were never captured. [S0045 L87–89]

### S0046 · External Positioning Matrix (2026-08-03)
**Lineage:** one new [FETCHED] survey; otherwise built from prior holdings.

1. **Verdict (candidate):** KnowledgeOS is an engineering-knowledge *governance* platform that uses ontologies, not an ontology platform. [S0046 L39–48]
2. **External gap:** the provenance/assertion literature does not model *human authority over evidence*. Ontologies "model the RECORDS; KnowledgeOS governs the AUTHORITY over them". [S0046 L22, L34]
3. **Honesty table:** rediscovered = policy/decision/enforcement separation (XACML) · staged promotion · CQRS projections. Extended = the authority model · operational-evidence governance. [S0046 L53–57]
4. **Map to PROV; never reinvent it.** [S0046 L24]
5. **[I]** The "external gap" rests on one survey (n=1 source). The "three-sided evidence" is two internal sides plus one external fetch.

---

## Block B — Attribution/assurance chain (2026-08-16; S0047–S0053)

### S0047 · reviews/README (2026-08-16)
1. **Placement is derived from knowledge subject → product/domain → space → type → location**, never from producer, session or previous path. [S0047 L15–25]
2. **"Historical location is evidence; canonical future location is architecture."** [S0047 L27]
3. **Drift mechanism:** consistency by repeated configuration (tokenRef inheritance) replaced derivation. [S0047 L29]
4. **Files are not moved** because 39 tokenRefs in authoritative records hard-code their paths — evidence identity is coupled to location. [S0047 L33]

### S0048 · Placement requirement registration (2026-08-16)
**Lineage:** registers the same incident as S0047, plus an addendum.

1. **Requirement:** placement derives from subject identity, never from producer or session context. "Knowledge Space/Scope" is a concept candidate. [S0048 L16–26]
2. **Second requirement, knowledge distribution:** "Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule." [S0048 L53–67]
3. **Two-specimen class evidence:** governance knowledge is not propagated to execution roles. [S0048 L69]
4. **Maturity:** policy ✓, derivation ✓, awareness ✓; mechanical enforcement weak or absent. [S0048 L30–35]
5. **"A locally convenient precedent becomes an implicit rule"** — a generic failure pattern. [S0048 L12]

### S0049 · KOS-ATTR-ARCH-001 rev 3 independent review (2026-08-16)
**Lineage:** process 34210a39. The same process later wrote S0055 and S0059 [S0055 L12–16; S0059 L15–17].

1. **F-1 (the most important logical result here):** a derivation "current outcome := established assessment ELSE weakest" is *total by construction*. A read-totality invariant therefore cannot justify an aggregate boundary. The real invariant is **uniqueness — at most one established assessment per claim** — which serialization protects. Current outcome is a *read*, not a member. [S0049 L42–60]
2. **F-3:** status disputes are Assessments; content disputes are Evidence plus *claim supersession*. Rule precised to "one **current** claim per (act, dimension)". Per-claimant claims are rejected because they destroy outcome definiteness. [S0049 L66–78]
3. **F-5:** an assurance outcome must never gate. Constraint: the input set of every authority gate is closed, `{transitions, grants}`. Latent channels exist through role behaviour and storage co-location; "the only gate assurance may influence is the human". [S0049 L119–130]
4. **F-4:** assessor standing is itself a C4 Independence claim. The regress terminates at a disclosed Declared or a human acceptance; no new concept. [S0049 L104–108]
5. **F-2:** event bookkeeping. 13 proposed vs 10 dispositioned; `AssuranceClaimSuperseded` becomes a 5th domain fact. Test used: "would another context need to know?" [S0049 L84–97]
6. **F-6/F-7:** establishment (the most consequential write) and act→category classification had no owner. [S0049 L112, L132]
7. **The reviewer's own independence is Declared and not attestable (INV-ATTR-2).** [S0049 L25–30]
8. **Erratum E-R1:** the reviewer made the same count-error class it reported. [S0049 L188]
9. **[I]** The termination in item 4 holds only because the default is weakest. That is a stipulation, not a proof of well-foundedness.

### S0050 · F-1…F-8 repair (2026-08-16)
**Lineage:** the architecture lane applying S0049. Self-review is disclosed [S0050 L15].

1. **Boundary `{Claim · Assessments}`; establishment serialized; Evidence References outside.** [S0050 L25]
2. **The uniqueness rule is presented as a "mechanism-level restatement of a presupposition", not a new invariant.** The Human supplies it as an interpretive act. [S0050 L31–37]
3. **The derivation completed:** the uniqueness rule makes branch 1 definite; the ELSE branch makes the read total. [S0050 L46–50]
4. **Establishment is by the Assessor itself** — no human act, "confers no authority". [S0050 L120]
5. **13-event disposition: 5 domain facts · 6 internal · 1 removed · 1 read-side.** [S0050 L58–74]
6. **Governance classifies act→C1–C5;** "self-classification would rig the Gap derivation". [S0050 L132]
7. **[I]** Item 2 is notable: an invariant enters through an authority's *interpretation* of an approved model. Is that "not new" or a new axiom?

### S0051 · Independent verification of the repair (2026-08-16)
**Lineage:** a fresh process.
1. **Verdict: 8 CLOSED · 0 PARTIAL · 0 NOT CLOSED, verified "substance, not labels".** [S0051 L7, L68]
2. **V-1:** a supersession is real but named only in the traceability, not the header. A header-only reader "could reintroduce exactly the F-2 error". [S0051 L131]
3. **The verifier's independence is again Declared.** [S0051 L30–35]

### S0052 · Decision summary (2026-08-16)
**Lineage:** Governance.
1. **Approving a correct conclusion on an unsound argument is itself a defect;** only the Human can supply the interpretive sentence. [S0052 L16]
2. **The Human's sentence: "at most one established assessment per Assurance Claim at a time".** [S0052 L81]
3. **A reviewer must not recommend the disposition of its own findings** ("its own advocate"). [S0052 L4]

### S0053 · Stage-1 approval registration (2026-08-16)
1. **Three status levels never collapsed:** aggregate APPROVED · bounded-context placement NOT CONFIRMED · target architecture NOT APPROVED. [S0053 L39–43]
2. **The approved invariant set** (boundary, derivation, current-claim rule, closed gate input, establishment owner). [S0053 L27–35]

---

## Block C — Baseline-001 verification chain (2026-08-17; S0054–S0061)

### S0054 · Producer self-review disclosure
1. **"A wrong observation is worse than an acknowledged unknown."** D-1: a high-confidence `Observed` count was eyeballed (8 instead of 10). [S0054 L20–30]
2. **D-2:** the executive summary contradicts the document's own detail table. [S0054 L36–40]
3. **D-3:** the declared component count (7) differs from the registry (8, the extra being the composition root). It was missed in a document whose thesis is "declared ≠ executing". [S0054 L44–46]
4. **Method:** re-derive by a *different* method in order to falsify. "The defects still in there are, by construction, the ones the author cannot see." [S0054 L14, L73]
5. **The central thesis survives;** the damage is to precision and internal consistency. [S0054 L66]

### S0055 · Phase A independent verification #1
**Lineage:** process 34210a39 (also wrote S0049).
1. **11 of 12 `Observed` claims re-derived true.** The failures are *wrong measurements*, not disguised declarations — a distinct defect class. [S0055 L7, L77]
2. **V-E:** the snapshot's machine-state figures are retroactively unverifiable because the records are gitignored. The baseline inherits the no-provenance defect it reports. [S0055 L50]
3. **Separation (the Option A condition) "cannot be established from the record";** independence is Declared. [S0055 L95]
4. **No boundary was inferred from a name alone;** the evidence hierarchy runs declared → wiring → records → code. [S0055 L80–83]
5. **Calibration damage, not the number itself, is the severity.** [S0055 L33]
6. **Platform→Product one-way dependency: a falsification attempt failed.** [S0055 L68]

### S0056 · Verification #2 (corrections)
1. **Model separation is nil:** same model as the corrector. "A shared model can share a blind spot." [S0056 L26–29]
2. **The correction introduced a defect of the class it repaired** (N-1, title vs banner). [S0056 L117]
3. **No `Declared` claim was upgraded by borrowing the verifier's evidence.** [S0056 L76–89]
4. **"Repairs correctly applied" ≠ acceptance-readiness; document-integrity ≠ domain-model defects.** [S0056 L11–13]
5. **The verifier must not repair what it found (R-34 forward constraint).** [S0056 L166]

### S0057 · N-1/N-2 correction delivery
1. **Move, never renumber:** identifiers are preserved for referential integrity. [S0057 L13]
2. **Diff discipline:** exactly two hunks. [S0057 L24]

### S0058 · Verification #3 authorization
1. **`authorized` never consults the session** (it is a scope-string match). This is read as a *defect* (D-2 class). [S0058 L79–88]
2. **Verification #2 ran with no lane, grant or START.** Its content is usable but its independence is not recorded. [S0058 L45–51]
3. **The prompt cited the wrong report** — the third instance of a "referent not checked" error. [S0058 L18–35]
4. **The evidence chain is split across two documentation roots;** the placement resolver has no matching rule. [S0058 L92]

### S0059 · Verification #3 report
**Lineage:** the S0049/S0055 process again.
1. **Mechanical checks are least exposed to a shared-model blind spot,** which bounds the nil-model-separation exposure. [S0059 L21–30]
2. **The referential-integrity rule "did real work".** [S0059 L55]
3. **The correction chain is closed on the evidence.** [S0059 L77]

### S0060 · V#3 acceptance registration
1. **Accepting the verification evidence ≠ accepting the baseline** (two separate acts). [S0060 L7, L24]

### S0061 · Phase A acceptance registration
1. **Acceptance authorizes Stage-2 analysis only** — not bounded contexts, aggregates, technology or migration. [S0061 L31–43]
2. **Accepted "with eyes open" to V-E** (figures that left no trace). [S0061 L47]
3. **The accepted object is a specific blob (`40026b12`).** [S0061 L27]

---

## Block D — Strategic and tactical BC-7 chain (2026-08-17; S0062–S0065, S0067, S0071)

### S0062 · Stage-2 Bounded Context Confirmation
**Lineage:** uses only the accepted baseline.
1. **Three BC tests:** language · ownership · change-reason. "A context that fails the language test cannot be rescued by having code"; rules-only contexts are real. [S0062 L14–20]
2. **The actual centre of gravity — authority registration plus role separation — has no declared context and no capability id.** "The single most important Stage-2 finding." [S0062 L60–69]
3. **CBC-1 Governed Session Orchestration promoted to candidate.** Counter-authority: a parsimony ruling. Reversing a recorded decision "requires an ADR, not an analysis". [S0062 L75–84]
4. **C-3:** the authority store has no provenance (gitignored) — the store "contradicts the context's purpose". [S0062 L113]
5. **OQ-1:** are EKP and PKS one knowledge system or two? [S0062 L38, L177]
6. **BC-4:** a capability realized by the role model; either a rules-only context or a policy *inside* orchestration. [S0062 L44]
7. **C-4:** the writer boundary is convention-only on 3 of 5 transitions. [S0062 L114]
8. **Never infer ownership from a name; "suggestion is not evidence".** [S0062 L104]

### S0063 · Baseline-002 commission
1. **Hypotheses are "to be tested, not inherited",** including an *exclusion* list. [S0063 L21–25]
2. **Two seams posed:** policy vs process (who may approve? vs was approval recorded?) · knowledge identity vs workflow history. [S0063 L27–29]
3. **ADR-AIP-04 frozen so that roles are not designed before their ownership is understood.** [S0063 L35]

### S0067 · Architecture Landscape v2 (accepted)
**Lineage:** a fresh process (Fable 5). It consumes S0062 and S0063.
1. **Boundary sentence:** "Governance decides whether an act was authorized; BC-7 answers whether it was recorded." BC-7 is a "registry of governed occurrence". [S0067 L129, L144]
2. **Knowledge seam: adjudicated identity vs folded occurrence.** Knowledge truth is *adjudicated*; workflow truth is *folded* (deterministic). Do not merge. [S0067 L161–165, L178]
3. **Owns vs preserves:** an invariant is *owned* if its reason lives in the context, *preserved* if enforced for an owner elsewhere. [S0067 L57]
4. **Grant split:** the record is owned; the authority content is not. Human Act is *not* owned — only its registration. "Owning the registration of a thing is not owning the thing." [S0067 L51–52]
5. **The mechanism's inability to evaluate policy is the boundary "realized as designed", not a gap.** [S0067 L83, L144]
6. **Discovered exclusions:** authority evaluation · identity attestation · acceptance · time/scheduling ("seq, never wall-time"). [S0067 L91–94]
7. **Role is deferred; the measured shape is that role sets are data fixed at creation.** [S0067 L53]
8. **[I] Tension with S0058:** the same `authorized` behaviour is read as a *defect* in S0058 [L86] and as *boundary-conforming* here [L83].

### S0064 · Principal review registration
1. **The first-pass review was mis-targeted (wrong artifact)** but is registered and superseded by its author. [S0064 L5]
2. **The reviewer supplies the acceptance text,** which is registered as a "prepared, unsigned" draft. [S0064 L181–213]
3. **The acceptance only realizes what the ADR already decided** (precision against double decision). [S0064 L213]

### S0065 · Baseline-002 acceptance
1. **Accepted without independent verification of the v2 model,** resting on a human Principal review plus PO/ARB authority. [S0065 L20]
2. **The seven-context model is now authoritative; ADR-AIP-01 is amended by acceptance.** [S0065 L24–29]

### S0071 · BC-7 Domain Model Proposal (Baseline-003)
**Lineage:** a fresh process (Opus 5).
1. **Mutation ownership is a fold projection, never stored** (0 stored occurrences; derived live). T-1: "no derived projection is ever stored or accepted as input". [S0071 L50, L208, L240]
2. **Aggregate test:** "an invariant that spans several instances proves the candidate is not the root". Assignment is rejected because I-1 and HANDOFF span assignments. [S0071 L80, L100–104]
3. **One aggregate vs two:** no *positive* invariant spans transitions[] and grants[]. One root is kept on a *negative* invariant I-4 ("never merged"); RA-4 (split) is "the strongest rejected option". [S0071 L132–145, L219]
4. **BC-7's only decisions are record-consistency rules.** "It can refuse a malformed act, never an unauthorized one." [S0071 L282, L293]
5. **T-2: a reference is never dereferenced. T-4: attribution is recorded as a claim, never an attestation.** [S0071 L241–243]
6. **"BC-7 records the claim; the attribution model adjudicates it"** — relate, do not duplicate. [S0071 L330]
7. **Declared-but-unexercised vocabulary** (CONTINUATION/FAIL, 0 of 117) is "a claim, not a fact". All 41 grants are AUTHORIZED, so revocation has never been observed (OQ-6). [S0071 L154, L350]
8. **Measured asymmetry: 33 REGISTER / 32 START / 16 COMPLETE (OQ-8). Events are recorded facts; there are no subscribers.** [S0071 L249, L262]
9. **[I] Trivial-case test:** "never merged" is satisfied by construction if the two are split. So a negative invariant needs no holder; the argument for one root is weaker than stated.

---

## Block E — Track 2 capability discovery and decisions (2026-08-18/19; S0091, S0113, S0129)

### S0091 · ADR-AIP-04 Capability Discovery (with Amendment 1 and Correction #2)
**Lineage:** process 5e1dd9ee (also the author of the S0129 ADR). Verifier #1 is 1c8b041b, also the S0164 parsing proposer [S0164 L1221]. S0164 L1731–1775 restates this source.

1. **Core structural result (survived two verifications):** every invariant BC-7 owns is mechanically enforced; almost every invariant outside it is declared only. "Assurance is strong where executable, weak where prose — and a role model is prose." [S0091 L192, L588]
2. **C-5 separation attestation cannot be discharged by an agent:** "a process cannot attest its own separation". Doubly evidenced: Amendment 1's own producer was unattestable. [S0091 L257, L796]
3. **Decision rights follow the nature of the activity, never the actor.** The §7 matrix is activity × decision-right. [S0091 L92, L522]
4. **OQ-5 proposed as a split:** the lane-role model is BC-7's own; the authority model is governance-published and consumed. The consumption leg was downgraded to INFERRED. [S0091 L273–280, L530–536]
5. **"Communication" is two capabilities under one name:** C-10 distribution (a mechanism; strongest gap — tokenRef leaks it) and C-19 composition (a stewardship; fails the language and change-reason tests). [S0091 L122–124, L310–313]
6. **Absence-claim discipline:** absence of registration was presented as absence of existence three times (truncated search · over-generalization · a class claim from one file type). An absence claim needs an exhaustive, stated-scope search. [S0091 L397–404, L656–669]
7. **C-14 policy enforcement: absent → live/narrow → CONTESTED.** The only Tier-1 asset may belong to a different capability (CAP-09, halt-on-trip). [S0091 L761–767]
8. **The six-role basis changed completely:** titles in 104 files; the surviving claim rests only on the absence of an *adopting governed act*. [S0091 L719–725]
9. **Convergence with an unread source cannot be distinguished from reconstruction,** so it must not count as independent corroboration. [S0091 L712–715]
10. **"Role" has three meanings** (lane role · persona · decision right) — a homonym. [S0091 L169–172]

### S0113 · C-10 Decision-4 governance input (2026-08-19)
**Lineage:** Governance b64828fe. It consumes the S0091 canonical analysis.
1. **"Missing" is 100% of cases while zero receipts exist.** Any blocking response halts the entire estate on day one. [S0113 L38–42]
2. **Only one of five conditions** (missing/stale/superseded/invalidated/disputed) has detectable state or an owner. "A tier table cannot assign a response to a condition whose state nothing owns." [S0113 L26–34]
3. **Choosing HALT/BLOCK decides the category by consequence** (advisory → control-plane). [S0113 L18–20]
4. **A receipt cannot be bound to a session:** no gate reads identity (INV-ATTR-1). [S0113 L44]
5. **The human START is itself a compensating control** that substitutes for absent machine enforcement. [S0113 L77]
6. **The "Session Responsible" option is falsified as the status quo,** which produced the defects. [S0113 L65–71]
7. **Recommendation:** target model decided non-constitutively; decide behaviour only for detectable conditions. [S0113 L81–91]

### S0129 · Durability ADR position and decision registration (2026-08-19)
**Lineage:** first part written by the ADR author 5e1dd9ee; appendix by Governance b64828fe.
1. **"An aggregate cannot depend on accidental reconstruction."** The authority record "was never runtime's to export" — Execution Context ≠ Governance Evidence Context. [S0129 L44, L47, L59–60]
2. **Recommendation ≠ decision.** "I would select B′" is not adopted; chain: proposal → recommendation → decision → registration. [S0129 L87–95]
3. **A process may not both decide and register:** the humanActRef would point at its own output. [S0129 L147–149]
4. **R-CONFLICT:** preserve sequence integrity, provenance and reconstruction; never silently choose a side, delete a history or rewrite sequence. "History ≠ Reconstruction Guess." [S0129 L45, L102]
5. **First-hand evidence:** the documents survived in git while all 28 grants and 33 transitions remained outside git. [S0129 L187]
6. **The producer's refusal to decide is recorded as evidence that separation works,** with an interested-party caveat. [S0129 L117–119]
7. **Reuse before creation:** the placement rule already exists. [S0129 L111–113, L170–179]
8. **[I] Temporal:** D1/D2 are NOT SELECTED here [L157–158]; S0164 later reports B′ DECIDED and R-CONFLICT ADOPTED [S0164 L3242].

---

## Block F — Composite scratch log (S0164)

### S0164 · Governance role cost optimization (composite, 2026-08-14…21)
**Lineage:** multiple voices (chat assistants plus pasted session transcripts). It contains a restatement of S0091 [L1731–1775]. L834–915 is a third voice restating L241–724 with external web citations attached.

1. **Scaling invariant:** "Automation may reduce assurance cost but must never manufacture authority." "Automation handles evidence production; humans retain authority." [S0164 L650, L709]
2. **Invariant separations:** AUTHOR ≠ REVIEWER · AUTHOR ≠ ACCEPTANCE · GOVERNANCE ≠ DESIGN AUTHORITY · AI AGENT ≠ GOVERNANCE AUTHORITY. Principle list: Recording ≠ Asserting · Evidence ≠ Proof · Reference ≠ Ownership · Execution ≠ Governance. [S0164 L262–280, L713–720]
3. **Independence is not automatable.** AI may emit CONFLICT DETECTED, never INDEPENDENCE = TRUE. [S0164 L369–402]
4. **Independence = process identity + lineage + working-context provenance.** If lineage cannot be established ⇒ NOT ATTESTED, never PASS. [S0164 L2065–2077]
5. **Conformance stratification:**
   - L0–L2 language substrate · L3 fact model · L4–L5 contract.
   - "Contract neutrality = an independent party reading schema + rules alone produces the same node set, edge set and metric."
   - The honest claim is reduced: "the larger claim was never true".
   [S0164 L1313–1323, L1485, L1510]
6. **"Differential testing detects disagreement, never shared blindness"** (nullsafe missed by both implementations). "A checker cannot discover an act the author never declared." [S0164 L1529, L3139, L3310]
7. **Each implementation executes an accident of its extraction API, not a stated rule.** "Nobody has ever decided this; two library defaults stood in for a decision." [S0164 L1276–1285]
8. **A grammar's complement is not enumerable by probing,** so incremental repair "cannot bound its own residual risk". [S0164 L1202, L1354–1360, L1380]
9. **Evidence model reconciled into four orthogonal dimensions:** SUBJECT · EPISTEMIC · PROVENANCE · REPRODUCIBILITY. INDEPENDENT is refused as a criterion (unachievable with a single operator); REPRODUCIBLE "does the work". [S0164 L2420–2424]
10. **Off-record work:** the engine refused a handoff from a never-STARTed lane; the START was transcribed later "without back-dating". [S0164 L2098–2118, L2167–2187]

Lower-ranked, still relevant:
- Assurance classes A–E by kind of change [L408–428].
- "Batch the work; don't merge the semantics" [L468].
- D5 establishment criteria E1–E10: "the combination is sufficient" [L2235–2264]. **[I]** Sufficiency is asserted, not shown.
- An amendment regress "recurs one level down until an amendment produces no new remedy" [L2512].
- A recurring defect shape: "an act named by no phase" [L2593].
- A process recorded the "human START act" itself [L1160] — relevant to C-5.
- A broken-pipe-truncated check was reported as "ALL PRECONDITIONS SATISFIED" [L2129].

---

## Block G — Formal brainstorming threads (S0913, S2377, S2834)

### S0913 · Step 25U multi-agent knowledge (2026-08-28)
**Lineage:** the phase-measure-theory series (refers to 25N; Lord/Zero vocabulary). Shares voice and notation with S2377 and S2834.
1. **"Corroboration should be measured in independent information, not number of agents."** AgentIndependence ≠ InformationIndependence; common-source dependency. [S0913 L249–250, L1512–1514]
2. **Likelihood-ratio example:** LR 19 × 15 = 285 only if independent; shared-source evidence must not multiply. [S0913 L1464–1500]
3. **Trust ≠ Authority ≠ Expertise; trust never substitutes for authority (proposed invariant).** Trust modifies epistemic weight and creates no truth. [S0913 L188–198, L1398, L1426–1428]
4. **Conflict is a first-class knowledge state; "Unresolved" is a valid terminal state.** Conflict taxonomy: identity, temporal, context, measurement, semantic, evidence, model, policy, authority. [S0913 L450, L1086–1097, L1148–1161]
5. **Apparent conflict dissolves under identity, time and context resolution.** "Identity + temporal semantics must precede conflict resolution." [S0913 L363–397]
6. **Trust is contextual, Trust(a, d, c, t). Trust assessments are themselves evidence-bearing knowledge objects. Self-reinforcing reputation is a bias.** [S0913 L133, L749–804]
7. **Capability ≠ authority** (CanExecute vs AuthorizedToExecute). [S0913 L1323–1347]
8. **Authority can resolve specific conflicts** (an official record supersedes for governance status; the other claim stays historical evidence). [S0913 L601–637]
9. **[I] "PASS" verdicts on tests A–H are asserted, not executed** — no computation is shown. [S0913 L1524–1616]
10. **[I] Homonym:** "Zero" here means unresolved conflict / cannot proceed [S0913 L1165–1174, L1744–1752]. In S2377 Zero means Δ = ∅, i.e. adequacy [S2377 L358–383].

### S2377 · KnowledgeOS Theory v1.0 — Formal Theory of Epistemic Gaps (2026-09-02)
**Lineage:** responds to an "attached derivation" (not in set). Same series as S0913 and S2834.
1. **DEF-G1:** the Gap is the set of unsatisfied epistemic requirements, Δ_t = {r ∈ R_t : Sat(K_t, r) = 0}, relative to a contract EC_t. It is not I − K (K and I are heterogeneous, not a vector space). [S2377 L5–7, L104–109, L325–336]
2. **The Ideal State = the requirement set**, "knowledge sufficient for the declared purpose". Requirement r = (id, type, scope, content, standard, priority, validity). [S2377 L219, L242–277]
3. **Zero ⟺ Δ = ∅ ⟺ K ⊨ EC.** THM-G1 is a restatement of the definition (tautological). [S2377 L358–440]
4. **Order:** under a fixed contract, gaps live in (P(R), ⊆), a lattice; Zero = bottom. Improvement ⟺ Δ_{t+1} ⊆ Δ_t; a numerical G = Σ w_r d_r is derived, with G = 0 ⟺ Zero (THM-G3, requires w > 0). [S2377 L969–1019, L1433–1555, L2329–2439]
5. **Contract invariance (THM-G4):** gap comparison across contracts requires an explicit requirement mapping; this guards against "moving the goalposts". [S2377 L1779–1831]
6. **Hierarchy:** semantic gap → typed gap → numerical gap, "not the reverse". Probability defines one gap component, not knowledge. [S2377 L1561–1655]
7. **Gap increase does not imply knowledge decrease** (an unknown becomes a known gap). Two measures are needed: adequacy and gap observability. [S2377 L2171–2256]
8. **Gap type classes** (coverage, value, uncertainty, warrant, contradiction, model, observability, stale, identity, representation). Classes may overlap (multi-tag); completeness is OPEN. Directional split: Δ+ deficit / Δ− conflict / insufficiency. [S2377 L825–855, L1120–1173, L2292–2319]
9. **Freeze candidate:** the open question becomes "how to define Sat per requirement class". Ten items are kept OPEN. [S2377 L2639–2650, L2757]
10. **[I] Source defects and theory risks:**
    - The text says "seven primary gap classes" [L461] but lists ten.
    - The age sign is wrong: `age_r = t − now` [L1346].
    - Sat ∈ {0, 1} [L297] maps "unobservable" to unsatisfied, which conflicts with its own Unknown ≠ False stance [L724–735].
    - §54 "gap grows" implicitly changes R_t, i.e. the contract, contradicting the fixed-EC premise of THM-G5.
    - Lord/Sārathi are introduced as roles in the formal backbone [L2008–2088].

### S2834 · What KnowledgeOS makes possible beyond AI/RAG (2026-09-07)
**Lineage:** same series. The second half (L1883–2245) is a critique by a second voice of the first half.
1. **Strongest realistic claim:** "make the epistemic status of claims explicit, traceable, revisable, contract-relative, machine-operable" — not truth. [S2834 L1743–1757, L1715–1739]
2. **"Capabilities ≠ Kernel Operators."** 60 capabilities sit over a smaller semantic kernel. Proposed foundation: epistemic state · provenance · evidence · inquiry · hypothesis space · determination · temporal state. [S2834 L2085–2132]
3. **Two-axis audit:** semantic capability vs *kernel necessity*. Example: impact analysis is derivable from provenance + dependency + lifecycle + determination. [S2834 L2140–2160]
4. **Determine(p | C) ≠ True(p).** The soundness bridge must be explicit; Knowledge vs factive Knows. Separation of E_t, A_t, K_t. [S2834 L307–331, L2039–2081]
5. **Chain:** knowledge → determination → decision → authorization → action, each separately checkable. [S2834 L882–944]
6. **Unknown ≠ False; Unknown ≠ Uncertain; contradiction is data, not failure.** [S2834 L139–189, L864–878]
7. **Representation adequacy, q = g ∘ T:** "lossless for which inquiry?"; fibers show which distinctions are lost. [S2834 L355–437, L1311–1329]
8. **Promotion ladder:** Observed → Declared → Inferred → Hypothesized → Falsified/Validated → Derived → Architectural → Governed → Canonical. Minimality requires necessity and sufficiency separately. [S2834 L1413–1469]
9. **The critique:** "closed epistemic feedback system" is unproven — Outcome ≠ Knowledge, Observation ≠ Evidence. Claim levels (established / defined / future) are mixed; "after implementation, KnowledgeOS can…" overclaims. [S2834 L1906–1948, L1995–2033]
10. **[I]** The first half is promotional ("profound", "ultimate architecture"). Treat it as hypothesis; only the critique half carries status discipline.

---

## Cross-source convergences and tensions

| # | Topic | Converging sources (lineage note) | Tension / conflict |
|---|---|---|---|
| 1 | **Record ≠ authority**; authority originates only in a human act; a record/automation never "manufactures" authority | S0067 L51/L144, S0071 L293, S0129 L147, S0164 L709, S0049 F-5 L130, S0113 L77. **[I] One estate and overlapping processes (5e1dd9ee, b64828fe) — count as one line of support** | S0058 L86 reads `authorized` ignoring the session as a *defect*; S0067 L83 reads it as the *boundary* |
| 2 | **Derived ≠ stored** (fold/projection, read-only; never written back) | S0071 T-1 L208, S0049 F-1 L56, S0040 L38, S0041 L5, S0037 L183, S0046 L55 (CQRS, external) | S0036/S0037 treat PKS generation as a *derivation* bet, while S0037 L253 shows PKS is *authored*, not derived |
| 3 | **Conflict preserved, not silently resolved** | S0913 L450/L1148, S2834 L188, S2377 G_con L643, S0129 R-CONFLICT L45 | S0049 F-3 resolves content disputes by **one current claim + supersession** (a winner exists), vs S0913's "Unresolved" terminal state |
| 4 | **Unknown ≠ False / ≠ weakest** | S2834 L157, S2377 L724–735, S0040 L40 (INCONCLUSIVE first-class) | INV-ATTR-2 defaults an absent assessment to the **weakest** value (S0049 L42); S2377 Sat ∈ {0, 1} collapses unobservable into unsatisfied |
| 5 | **Independence:** declared, not attestable; agent ≠ information independence | S0049 L25, S0055 L95, S0056 L26 (model), S0091 C-5 L257, S0164 L2073, S0913 L250 | S0164 L2424 *refuses* INDEPENDENT as a criterion (replaced by REPRODUCIBLE); S0091 L715 shows convergence vs reconstruction undecidable |
| 6 | **Enforcement location** | S0091 L192 (mechanical inside BC-7, prose outside), S0043 L53, S0048 L35 | Count conflict: S0036 L245 "0 of 25 enforcing" vs S0043 L52 "41 enforced controls" (different scope) vs S0091 C-14 CONTESTED |
| 7 | **Learning/feedback loop never closed** | S0036 L165, S0037 L257, S0038 L189, S0039 L165, S0045 L69 (same author group) | S0041 L23 formal n=1 at instrumentation level; S2834 L1653 "closed feedback system" vs its own critique L1995 |
| 8 | **Capabilities ≠ kernel; parts ≠ whole** | S2834 L2128, S0039 L234 (MVK FAIL), S0036 L137 (one capability, five responsibilities) | S0036's flat kernel list refuted by RQ-002 per S0038 L39–41 |
| 9 | **Absence / blind-spot limits of procedures** | S0091 L404, S0164 L1529 (shared blindness), S0164 L3139 (undeclared acts), S0038 L21 | — (all point the same way: a search or checker bounds only what it was scoped to see) |
| 10 | **"Zero" homonym** | — | S2377 Zero = Δ = ∅ (adequate) vs S0913 Zero = unresolved / cannot proceed |
| 11 | **Supersession and append-only history** | S0037 I-1 L226, S0071 T-3 L242, S0129 L102, S0164 L2187 (no back-dating), S0057 L13 (move, not renumber) | Retraction/revocation never observed (S0071 L350 all 41 grants AUTHORIZED; S0045 L50 retirement n=0) |
| 12 | **Two knowledge systems?** | S0062 OQ-1 L177, S0091 OQ-D L218 | Open; S0067 L154 explicitly does not decide it |
