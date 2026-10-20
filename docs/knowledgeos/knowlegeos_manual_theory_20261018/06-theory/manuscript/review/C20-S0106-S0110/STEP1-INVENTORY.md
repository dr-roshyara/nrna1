*Window reviewer W (Opus), step 1 blind inventory, returned as hand-back text, verbatim (saved by the Professor at 2026-10-20T22:37:15Z). sha256 of the hand-back text at receipt: `68c840b64c127f7bd0efc70e2457a2d76fc1f49bac8468fb009e34666b632ad8` (UTF-8, the text below this line exactly).*

C20 STEP 1: BLIND INVENTORY

Mode: transition review, step 1 only. All five paths are in CORPUS-ALLOWED.tsv: S0106 at line 1480, S0107 at 1481, S0108 at 1482, S0109 at 1483, S0110 at 1484, each with status "match". I read each source whole, in one read (69, 118, 53, 137 and 48 lines). To check for repetition I looked only at S0102 (grep for ContextSelected/applicab), S0104 (grep for criteria/lineage) and S0105 (grep for lineage/inherited). I read nothing under W/06-theory/, no git log or diff of W, and no read log. Independence here is process independence (a separate reviewer session), not personal independence.

**Skimmed-source safeguard (§3):** no source in this window, and none of the 109 read before it, was read at depth "skim". There is therefore no skimmed source to read blind.

**Blind depth judgements:**

| Src | Depth deserved | One-line reason |
|---|---|---|
| S0106 | full (borderline) | Most of it is a deliverable-to-section mapping, which is operational. But it adds a self-referential separation result: the governor of a record cannot author the analysis of separation that rests on that record. It also says a refusal is itself evidence. |
| S0107 | full | The seven-criterion gate repeats S0104/S0105. New are: an attribution can be amended additively without rewriting it; a classification survives a false premise; the authority-separation question lies outside the gate; criterion 7 cannot be detected mechanically, so it yields NOT ATTESTED; the gate admits no one. |
| S0108 | full | The difference between a decision-authority boundary and a refusal; an option that cannot be satisfied (it binds to an act that does not occur); a decision whose option space comes from an unverified artifact. |
| S0109 | full (highest) | AUTHORITY IS CLAIM-SCOPED; reference is not correctness; the referent gap (existence vs authority); required properties against an OPEN lifecycle question; external material may corroborate but never authorize. |
| S0110 | full | A later act is not automatically a supersession (three readings); a recorded field vs an asserted claim; a reconciliation reading that keeps the gap open vs one that closes it. Short, but not a repeat. |

---

### S0106: C-5 commission stopped at Canonical Discovery
1. **Search before modelling:** if an analysis of the subject exists, a second, competing one is forbidden. Producing one would fork the subject while it is under verification (L9–13, L37). [I] This is a uniqueness invariant: at most one canonical model per subject.
2. **Self-referential separation:** the process that governs the record cannot produce the analysis of separation that rests on that record. "C-5 operating correctly forbids its own analysis being written by the party that governs the record" (L41–43). [I] A reflexive application: the capability constrains who may model it.
3. **A refusal is itself evidence of the capability ("third separation event"), recorded with "no ownership inferred"** (L45). [I] Evidence of a property is kept apart from any conclusion about who owns it.
4. **Two independent grounds, "either sufficient"** (L5). [I] The structure is a disjunction of sufficient conditions.
5. **What remains open is disposition, not analysis.** The provenance defect is inherited by every conclusion in Correction #3 (L49–51, L59). [I] Validity propagates down through provenance: a dependent cannot be more assured than its source.
6. **The order of resolution is fixed: provenance, then verification, then decision** (L57–61). [I] An ordering on gates.
7. **Distinctions:** existence is separate from category (A1.1, L20); intra-platform third party is not external attestation; NOT_ESTABLISHED is not NOT_APPLICABLE (L33).
8. **Four-state assurance model, top state EXTERNALLY_ATTESTED, not reached** (L23). This repeats S0102.
9. **Any later gap is handled as a narrow correction to the existing artifact, by a process that is neither the governor nor a barred producer** (L63). [I] This is extension rather than replacement.
10. **Boundary:** Governance "manufactures no handoff". A holder of mutation ownership (S5) blocks transitions by others (L67).
11. **Open:** OQ-L, OQ-A, category, ownership, assurance level, bounded context (L67).
12. [I] **Possible contradiction:** "fifteen deliverables" (L15), but the table lists 13 rows. Possible source defect.

### S0107: Provenance disposition, C-5 / Correction #3
1. **Additive amendment:** the record is append-only. A wrong attribution is corrected by an amendment placed beside it, never by rewriting it (L18, L22–24). No transition type amends attribution, so the type system lacks that operation (L18). [I] There is a gap in the event vocabulary.
2. **A classification stands when one of its stated grounds is false, if the remaining grounds suffice.** The act's claim that 4858c37c produced Verification #2 is FALSE, but authoring both ca6039a8 and c3839624 already suffices (L41–50). [I] A conclusion's validity is separated from the soundness of every premise cited for it.
3. **"A verifier cannot assure a correction it authored"** (L50). This is the core self-verification ban.
4. **Conflating two artifacts is a recurring misattribution pattern, the third occurrence** (L51). [I] Identity of artifacts is distinct from identity of producers.
5. **Classification lattice:** HISTORICAL VERIFICATION EVIDENCE / NOT INDEPENDENT; HISTORICAL CORRECTION / INDEPENDENTLY UNVERIFIED. The work stands, but unverified, and must not be cited as assured (L37, L57–60). [I] Validity of content is separated from assurance status.
6. **Downstream adoption rests on a conflicted base.** The adopted findings F-1..F-7 are not disturbed, "but they now rest on a provenance-conflicted verification" (L53). [I] Taint propagates without retraction.
7. **"A refusal is not clearance."** A refusal consumes nothing, and the assignment stays open to others (L67–68).
8. **Seven-criterion gate**, with lineage and inherited context as criteria 6 and 7 (L73–83). This mostly repeats S0104. New is the default rule: where something "cannot be established, NOT ATTESTED; never infer PASS from absence of evidence" (L85). [I] Closed-world reasoning is rejected, which is four-valued in effect.
9. **The authority-separation question lies outside the gate:** "the party that registered the assignment verifying its execution" is a question that criteria 1–7 do not reach (L95). [I] The gate is incomplete. Independence of production is a different thing from separation of authority.
10. **Gate result:** no known process satisfies the gate. This is an assurance-capacity fact, and "no relaxation proposed" (L97–98). [I] The empty-feasible-set case: the rule is satisfiable only by a fresh entity.
11. **Criterion 7 is not mechanically detectable.** The one detection came from manual heredoc counting (L98). [I] The criterion has no enforcement operation, so it is an invariant without an owner.
12. **Existence is not realization:** Decision 1 says C-5 exists, which does not mean the implementation proves every dimension (L106).
13. **Decision ordering:** Decision 2 is unassignable until a valid independent assurance result exists (L107, L114).
14. **Disclosure is not cure.** A party recording an adverse disposition about itself is disclosed, and that is not cured (L16). Concurrent governance lanes may duplicate or conflict with each other (L17).
15. **Provenance evidence methods:** term-frequency counts in transcripts, self-declaration, corroboration by the named party, and the record of write acts (L30). [I] This is a convergent-evidence pattern. Term frequency is weak evidence.

### S0108: C-10 Decision 1, not selected (decision boundary)
1. **Recording is not deciding.** "A process supplying [a selection] would be making the decision, not recording it" (L7–8). [I] A recorder has no authority to complete a decision.
2. **A decision-authority boundary is not a refusal of an assignment.** No independence gate was reached (L8). [I] Two kinds of non-performance.
3. **An option is unsatisfiable if it binds to a non-occurring, unowned act.** Option C refers to "applicability decision A", while ContextSelected does not occur and has no owner (L12–23). [I] Existential presupposition failure.
4. **A receipt cannot carry applicability authority; applicability is a judgement** (L19). This draws on S0102 OQ-J.
5. **Folding "selected" into "delivered/acknowledged" breaks the chain of distinct states** (L24).
6. **Basis inheritance:** a DECIDED act would "silently inherit an unresolved provenance question" when its option space comes from an unverified artifact (L31–39). The C-5 decision is contrasted as defensible on independent grounds (L38). [I] Robustness of a decision is relative to the independence of its basis.
7. **Not an argument against deciding:** the authority may decide on any basis it judges sufficient (L39). [I] This separates authority from epistemic sufficiency.
8. **A diagnosis, without replacement wording:** remedies are Architecture's (L25).
9. **Chain of distinctions preserved:** created ≠ published ≠ selected ≠ retrieved ≠ delivered ≠ acknowledged ≠ applied ≠ verified, and immutable ≠ authoritative ≠ authentic ≠ complete ≠ independent (L45).
10. **Open:** C-10 existence is NOT YET ESTABLISHED; the Knowledge Engineer pairing remains a "forbidden hypothesis" (L44).

### S0109: C-10 Decision 1, DECIDED Option C
1. **AUTHORITY IS CLAIM-SCOPED:** the receipt is authoritative for "delivered and acknowledged" only, not for understood, applied or complied (L19–22, L107–108). Its purpose is to stop the receipt becoming a "knowledge satisfied" certificate. [I] This is the central new invariant: authority is relative to a proposition, not to an artifact.
2. **Reference is not correctness.** "A pointer to a judgement is not an assertion that the judgement was sound" (L36–41). This dissolves the S0108 authority defect. [I] It is a use–mention or reference–assertion distinction.
3. **Referent gap:** the authority problem becomes an existence problem. The required referent never occurs (L43–55). There are two dispositions: the reference is null or optional, or there is a standing obligation to create ContextSelected (L54). [I] Existence and authority are orthogonal.
4. **Formal act: eight required properties,** including invalidation semantics and immutable provenance linkage (L110). A conforming receipt is "NOT PRESENTLY POPULATABLE" (L132). [I] This is a satisfiability failure of the type, and it bears on D2 (existence).
5. **A required property with OPEN content:** the receipt MUST have invalidation semantics, and WHAT they are belongs to D3 (L125). [I] This is a must-have-property vs its definition distinction, a deontic "Required" with deferred content.
6. **Immutability confers no authority** (L126). It applies immutable ≠ authoritative.
7. **Comprehension is made distinct from understanding** in the negative list (L105, L119). [I] Possibly a near-synonym split. Check whether the split is justified.
8. **Supersession of wording is recorded as an explicit diff** (L96, L114–121). [I] This is a temporal change within a single source.
9. **Attribution is corrected on the face of the record:** the act wrongly credits a process with the selection (L28). This is misattribution number 4.
10. **External material may corroborate, never authorize.** Citing it does not convert it; a DECIDED act must not rest on untracked external material (L59–63).
11. **Schema follows the semantic decision.** No typed receipt structure exists or is adopted (L67).
12. **Decision sequence:** D1 semantics, then D2 existence, then D3 state and lifecycle, then D4 dispute, then category, then ownership (L71–81). Authoritative state strengthens the case for a bounded context but does not decide it (L84). [I] Necessary is not sufficient.
13. "Delivered" and "acknowledged" are to be defined operationally and separately; this is still open (L24, L86).

### S0110: C-10 D1, second act conflict
1. **A later act is not automatically a supersession.** It may be an amendment, a re-issue that crossed with the first, or a duplicate. Until Governance disposes, the earlier DECIDED act remains in force (L37). [I] Defeasible temporal reasoning: recency is not priority.
2. **Two propositions on one DECIDED slot.** Act B drops "knowledge versions K" and "applicability decision A", and lists applicability itself among what the receipt does not establish (L10–16). [I] This is a conflict state, "both" in four-valued terms.
3. **Act B closes the referent gap at the level of the claim** (L24).
4. **Reading (a): a recorded field with no assertion about it.** Both acts stand, but the gap persists. **Reading (b): drop the property.** The gap closes, and the list must be re-recorded as seven properties (L28–33). [I] Carrying a pointer is distinct from vouching for it, and this extends S0109 item 2.
5. **Substance is stable across both acts:** Act B option 2 is materially the same as Act A Option C. The invariant and the distinctions are unchanged (L18, L42).
6. **Pattern:** acts and records have crossed four times, and each was disposed of by Governance, "none absorbed silently" (L38). [I] Concurrency of acts is a recurring phenomenon.
7. [I] **Possible count inconsistency:** S0109 L28 says misattribution was recorded "four times", while S0110 L38 counts "crossed four times" for a different set (crossings). Two different counters share similar wording. Check this.

---

**Cross-source table [I]**

| Theme | S0106 | S0107 | S0108 | S0109 | S0110 |
|---|---|---|---|---|---|
| One canonical model per subject (ES-005.4) | origin | restated | restated | restated | restated |
| Self-verification ban / separation of authority | governor ≠ producer | gate + authority question outside it | — | — | — |
| Recording is not deciding | Not done list | decides nothing | core | attribution corrected | core |
| Assurance taint propagates | Correction #3 inherits | F-1..F-7 rest on conflicted base | option space inherits | — | — |
| Reference / existence / authority | — | — | unsatisfiable option | reference ≠ correctness; referent gap | field vs assertion |
| Temporal change | — | additive amendment | — | draft → formal diff | later ≠ supersedes |

Lineage note [I]: S0107–S0110 share one recording process (4858c37c) and one vocabulary. They are a single line of support, not independent confirmations. S0106 is by b64828fe, and S0107 L17 records that the two lanes did not see each other's assignments.

STOP. Awaiting the comparison material.
