*Window reviewer W (Opus), step 1 blind inventory, verbatim (saved by the Professor at 2026-10-23T18:57:57Z). sha256 of the hand-back text at receipt: `71c87c033b9a82048c3aac59f3367b1168225ad76f4a578afbc5fb9c7390f9b2` (UTF-8, the text below this line exactly).*

C28 STEP 1: BLIND INVENTORY

**Disclosure.** My session came with the repository's git status and the five most recent commit subjects attached. Those subjects name windows C26 to C28 and identifiers §8.19 to §8.21 and CON-0245 to CON-0255, but they contain no theory text. I read the reviewer instructions and confirmed all five paths in CORPUS-ALLOWED.tsv (rows 1509, 1510, 1530, 1531, 1532). I read every source in full, including the image. For context I grepped only sources from the permitted earlier chains:
- the AMD6 architecture review (583 lines; the grant counts at L420);
- the cost-optimization review (L45–64);
- the Phase-0 back-test report (L20–68);
- the AMD6 SUMMARY, both as it stands now and at `8307beca`, read with `git show` on the repo file, not on W.

A grep also brought up the header lines of `DV-CORRECTION-INDEPENDENT-REVIEW.md` (L7–27). That review is later in the chain, and I used nothing from it. I did not read W/06-theory, W/archive, W/tmp, any read log or START-TIMES file, S0300 (the migration plan), chronological-read, holdout or sealed files, or .env files. I ran no git command on W and modified no file. Independence here means process independence, not personal independence.

---

## S0146: Reviewer's note on the registered DV correction commission (279 lines)

**Depth: FULL, narrowly.** The note says itself that most of it is now redundant with the grant (L19). But §0, §2.1, §5 and §7 add general distinctions about how gates and verification work, and a skim would lose them.

**Ranked theory:**
1. A verification gate must name its comparison object. There are two failure modes, set out at L20, L54 and L220:
   - an object that predates the state under check fails in the normal case;
   - an object derived from the output it validates "CANNOT FAIL", which the note calls a self-satisfying gate.
2. Coverage is not the number of gates. One check after the write that brings the copy to its closing state covers both copy writes, and a second check "adds a gate without adding coverage" (L55).
3. Data dependency fixes the order of steps. A step that takes an object as its target cannot come before the step that closes that object (L202–216, HO-1).
4. The comparison object is a single object, and the declaration is an input to it, not a second comparand (L234–244). It is built from three operands: manifest + reconciled 1b delta + declared pre-switch records (L239–241). "Two objects that must agree is the defect" (L244).
5. Reasons are load-bearing:
   - A decision can survive its own reason (DV-4, L86). The cure is to cite the rule, not restate it.
   - A correct rule can carry an inverted reason that opens an unsafe branch: a MOVE makes the source match the enumeration, so removal opens while a quarantine is undisposed (DV-6, L97).
6. A claim must use the domain distinction that makes it true. The switch-over record is the "first governance append after the transfer", not the "first record" (DV-3, L76–79). The vocabulary already exists, and no new term is needed.
7. Defects propagate into a "second artifact", the summary (L78, L99, L107, L121, L177).
8. A measurement goes stale through the act it measures. The grant count went from 114/113 to 115/114, and the 115th grant is the commission itself. A delivered artifact is not edited to track state that moved later (L21).
9. A tool detects only what its token register declares. Believing S2 catches DV-7 (the dangling `P5·ALL`) is false assurance (L256–267). The note's principle: tool gives evidence, Architecture repairs the design, an independent reviewer judges the repair (L267).
10. Authorship and review exclusion:
    - the finding-owner is barred both from authoring and from reviewing the repair (L11, L134);
    - "author ≠ independent reviewer", not "author ≠ every prior reviewer" (L135);
    - every item is ADDRESSED, never CLOSED, by its author (L120).
11. Process identities are self-declared and cannot be attested (L147, L275).
12. The note's own status: additive, no authority, "THE GRANT GOVERNS", overtaken by events while it was being written (L8–19, L171).

**Lineage.**
- Author: self-declared process `dd639043`, the owner of findings DV-1 to DV-7.
- Commission status ("AUTHORIZED · registeredBy governance · PO/ARB delivered act", L5; registered at 14:32, L17): the note's own report. It cites no record anchor.
- The grant's words are quoted (L20, L136, L175–180). The PO/ARB act's own words are never given.
- The note admits that it is "the weakest link in its own chain" (L275).

**Defects.**
1. Section order runs 0, 1, 2, 3, 4, 5, **7**, **6** (L184, L271). That is the misordering the chain itself polices.
2. "Four technical traps the registered scope does not carry" (L19) does not match §5, which lists six delta rows (L173–180). DV-1 alone has four traps (L54–57).
3. "Fifth occurrence" (L108) and "fifth time" (L120) are counts given with no list behind them.
4. 115/114 cannot be checked from where I could read. The AMD6 review does confirm 114/113 (L420).
5. Every DV failure is a property of the specification, not an observed event (the note's own L275). The "fails in the normal case" claims are derivations from the specification, not observations.

**Checked [I]:** the summary line anchors 77, 123 and 198 at `8307beca` carry the cited text ("break the recorded enumeration", "its FIRST record", `P5·ALL`). In today's file those lines have moved.

---

## S0147: Diagram, PNG, 2026-08-21 15:08:30

**Depth: SKIM.** It is one C4 system-context diagram. It does matter, because it is the only picture so far of the role model.

**Ranked theory (by label):**
1. PO/ARB, "Human decision authority", has a single edge, "Authorizes / accepts", to the Governance Engineer. PO/ARB has no edge to the platform.
2. The Governance Engineer is labelled "Authority, lifecycle, grants, acceptance".
3. Governance Communication: "Next-actor and handover communication".
4. Architecture, Implementation and Independent Verification Engineers. The verifier "Falsifies and assures".
5. The platform: "Governed engineering operating system for evidence, architecture, implementation, verification and knowledge lifecycle".
6. The AI/Agent Runtime ("Claude/Codex … execution terminals") is an **external system**. The platform "Bootstraps and constrains AI sessions", and the runtime "Executes assigned work under grants".
7. The Engineering Repository is external: "Reads / writes governed artifacts".

**Lineage.** No author, act or status appears. Only the filename gives a timestamp.

**Defects [I]:**
1. Role and actor are merged. The legend marks every engineer as a **person**, yet the runtime that executes the work is an external AI system. Whether an "Architecture Engineer" is a human or an AI session cannot be told from the diagram. That is the question S0146 L147 raises about identity.
2. "Acceptance" belongs to two parties: the PO/ARB edge says "accepts" and the Governance Engineer's label lists "acceptance". This is a homonym or an ownership conflict.
3. There is no knowledge consumer, and no edge from verification to acceptance.

---

## S0148: Track-2 Phase-1 adoption evidence (165 lines)

**Depth: FULL.** It is the corpus's first self-declared "IMPLEMENTED / VERIFIED" claim about an assurance tool. Whether that claim holds bears directly on the line between evidence and authority.

**Ranked theory:**
1. Automation produces assurance evidence and never decisions, acceptance, authority, independence or migration authorization. The handoff report uses no governance vocabulary and always exits 0 (L11, L29, L82, L118).
2. Fail-closed aggregation: FAIL > INCONCLUSIVE > WARN > PASS. Silence is never PASS (L37, L94, L96). A precedence defect was found and repaired: a FAIL was hidden when an INCONCLUSIVE came first (L39).
3. Three epistemic classes are kept apart: Observed, Estimated, Unknown (L13, L127–146).
4. The report must name positively what it did NOT check (the D-4 statement, the named areas, L51, L150–153).
5. Code provenance (git HEAD) is not AI-process attestation. EKS-07 is unsolved (L55, L152).
6. Implementing and verifying a practice is distinct from the organization adopting it, which is a separate human decision (L5, L27, L161).
7. Content rules for the report are placed in the domain layer as business rules (L37).
8. Business value is explicitly not measured. "0 FP on one artifact is not a rate" (L139–146).

**Lineage.**
- Author: unnamed. No process id is given.
- Plan "EP-01 APPROVED (Decision Authority … recorded `58e758b0`)" (L7): a gloss. No words from the authority are quoted.
- FINAL STATUS VERIFIED (L6, L159): **self-issued by the implementer**. The commission's rule is quoted as permitting the wording (L27), but no independent verifier appears.
- Trend figures are carried over from the cost-optimization review. They do match it (4/14, 6/13, 7/12; 4,315 lines, per its L45–64).

**Defects:**
1. **"Corrected current artifact … 0 false positives" (L94, L103, L121) holds only for the classes the tool checks.** At that moment AMD6 carried DV-1 to DV-7, among them DV-7, a dangling identifier (S0146 L264). Calling AMD6 "corrected" or "quiet" says nothing about false negatives. Criterion 10 measures the absence of false positives, not the completeness of detection.
2. **Internal contradiction.** "17 mechanical findings … the exact classes S1–S5 now rediscover" (L131) includes RD-1, RD-7 and RD-3·b (cost review L46–47), and the AMD3 RC findings. But §8 says RD-1, RD-7 and RD-3·b remain for human review only, and the RC classes are not checked (L150–151). AMD3 was not back-tested at all (L88).
3. **A test that does not test what it claims.** The real remediated draft ends **INCONCLUSIVE** (L96), yet L79 and criterion 4 claim "flips FAIL → PASS". That claim rests on a fixture test. In the remediated run S2 also moves from FAIL to INCONCLUSIVE, which is not explained.
4. L105 says "All four runs exit 0", but the table lists five runs (L90–96).
5. L88 says "Byte-verified" but uses line counts. Line counts do not verify bytes.
6. **This is in-sample validation, not out-of-sample.**
   - The oracle was built from findings already known.
   - The "new authoring draft" was written for this report, with its defects chosen in advance (L95).
   - Calling the checks "Phase-0-proven" (L19) overstates fit to known cases.
7. The figures 252 tests / 603 assertions and 8 tests / 51 assertions are reported, not shown.
8. The trend rests on 3 points from different reviewers and different amendment content. It shows no cause.

---

## S0149: KOS DDD architecture review v3 (822 lines, an AI chat response, `authoritative:false`, `proposed:false`)

**Depth: FULL.** It is a strategic DDD model with distinctions I have not seen earlier in this window. Whether it repeats the v1/v2 architecture is undetermined [I]; the "previous architecture" it mentions (L20, L684) was not in my set.

**Ranked theory:**
1. KnowledgeOS is a "Knowledge Governance Domain Platform", not knowledge storage. The core domain is creating, governing, validating, evolving and delivering trusted Knowledge Products (L28–34, L807).
2. Seven bounded contexts: Governance, Product (core), Evidence, Semantic, Delivery, Intelligence, Platform Administration (L76–92).
3. Evidence has its own lifecycle. A Product *references* evidence and does not own it (L283–289).
4. The graph is a projection. The domain model, not the graph, is the source of truth (L330–344).
5. AI is a capability, not the core. "AI cannot create authority". Authority comes from the Governance context (L414–436).
6. Invariant: a Product cannot become ACTIVE without owner, evidence, approval and validation (L211–218).
7. Method / Binding / Evidence is kept as a domain concept and called "your unique innovation" (L160–170).
8. Split the runtime into services, so there is no "god service" (L694–708).
9. Domain events, including DecisionChanged → impact analysis → invalidation of agent caches (L631–678).
10. The book's four pillars are extended with Knowledge and Trust (L769–778).

**Lineage.** An AI assistant addressing the Principal ("your Election System", L813). It cites an unidentified "book" (L226, L760). It recommends "freezing" ADR-KOS-001 (L811) while its own frontmatter says `proposed:false`.

**Defects:**
1. **Aggregate boundaries contradict each other.** Decision, Method, Binding and Rule are *contained* in KnowledgeProduct (L141–158), and are also separate aggregates (L569–610).
2. **The context map and the final architecture disagree.** In the map, Governance → Product → Semantic → Delivery → Intelligence (L459–495). In the final picture, Delivery sits above Product, and Intelligence sits below Governance, Evidence and Semantic (L714–754).
3. **"Authority" is a homonym.** It is a field of Decision and of EvidenceRecord ("Authority: Architecture Board", L259–260, L580, L623), and also something only Governance supplies (L432–436). Add to this a "Knowledge Authorization Service" (L701) with no context.
4. **Invariants have no owner.** It is unclear whether the ACTIVE rule belongs to the Governance aggregate or to the Product's LifecycleState.
5. **No commands.** The aggregates list only fields (L555–625). An aggregate with fields and no commands passes the trivial-solution test.
6. Evidence validation is duplicated: it appears in the Delivery flow (L398) and as Evidence-context verification.
7. "Not layers" (L70) conflicts with §4 and §9, which are layered.
8. "Should be event-driven" (L631) is asserted, not argued.

---

## S0150: IP protection / secrecy strategy (602 lines, two chat turns)

**Depth: SKIM** for L17–492, which is legal and business advice outside the theory's scope. **FULL** for L494–602, which carries the theory.

**Ranked theory:**
1. **The second turn corrects the first.** The anti-pattern "useful concept → immediately turned into a bounded context" (L571–577) rejects the first turn's proposed "IP Governance Context" (L488–492).
2. A bounded-context admission test: does the candidate have its own ubiquitous language, lifecycle, invariants, authority, reasons to change and independently owned state? (L582–592). It separates a distinct domain from a cross-cutting concern (L577).
3. Keep two goals apart: protecting KnowledgeOS's own IP, and building KnowledgeOS as a system that governs IP (L494–499, L598–602).
4. Three things are distinct: the architecture, the confidential know-how, and the public description (L538–546).
5. Four principles attributed to KOS: Recording ≠ Asserting, Reference ≠ Ownership, Evidence ≠ Proof, Authority ≠ Participation. The source maps these to IP analogues (L470–486).
6. "Evidence Boundary Model: separation between execution state and governance evidence" (L254–260).
7. An internal model list adds Execution, Composition Root, Authority Resolution and Evidence Conflict Policy (L555–564). Platform Administration from S0149 is not in it.
8. IP comes in four layers (business, architectural, methodological, implementation), and disclosure in levels 0–4 (L507–530, L130–188).

**Lineage.** An AI assistant to the Principal. A private person is named at L257; I do not reproduce the name. The four principles are attributed to the Principal ("you designed", L470) with no anchor.

**Defects:**
1. Commit history and session logs are offered as "evidence of authorship" (L109–118, L262). That contradicts the non-attestable identities of S0146 L147 and the stance that git is not attestation in S0148 L55.
2. "Years of accumulated design decisions" (L432) exceeds the evidence.
3. The legal claims name no jurisdiction and cite no source.
4. "The governance approach *is* an IP protection mechanism" (L468) treats an analogy as an identity.

---

## Cross-source points

| Point | Sources | Kind |
|---|---|---|
| A tool's evidence is not judgment or authority; no checker stands in for review | S0146 L267 · S0148 L29, L82, L118 · S0149 L429 ("AI cannot create authority") | Agreement. S0146 and S0148 come from one lineage (Track-2/DV chain), so they count as one line of support |
| AMD6 "corrected / quiet / 0 FP" against AMD6 carrying DV-1…7, with DV-7 outside S2's register | S0148 L94, L121 vs S0146 L256–265 | **Contradiction in meaning** (false-negative blindness) |
| "Tooling now detects DV-7" | the handoff, as reported by S0146 L258, vs S0146 L265 | Claim rejected in the same window |
| Authorship evidence from git and logs, against identity that cannot be attested | S0150 L109–118 vs S0146 L147, L275 · S0148 L55 | **Contradiction** |
| Self-closure ban (author ≠ verifier) against a self-issued "VERIFIED" | S0146 L120 vs S0148 L6, L159 | **Tension.** The rule may cover reviews only; scope undetermined [I] |
| Who accepts: PO/ARB, or Governance | S0147 (two "accept" labels) · S0146 L162 (PO/ARB accepts) | Ambiguity |
| The bounded-context list changes over time | S0149 (7 contexts) → S0150 L555–564 (adds Execution, Composition Root, Authority Resolution, Evidence Conflict Policy; drops Platform Admin) | Change, unreconciled |
| Concept → bounded context too early | S0150 L571–577 against S0150 L490, and implicitly against S0149's 7 contexts | Self-correction inside one source |
| "Authority" in several senses | S0149 (field / context / service) · S0147 (Governance label) · S0146 (grant) | Recurring homonym |
| Recurring defect class: a reference to an undefined or misnumbered object, and arithmetic or count slips | S0146 §7/§6 order and "four" vs six · S0148 "four runs" vs five, line counts as "byte" check · S0149 aggregates in two places | Repeated source defects |
| A rule's stated reason must be the load-bearing one; a decision must not outlive its reason | S0146 DV-4, DV-6 | New in this window |

**Left pending:**
- In S0146 (L117, L130–136, L162): OPEN-M1 to M7, the author-eligibility flag `AD`, the Phase-0 declaration content, and the DV-1 repair; Phase 5 stays prohibited.
- In S0148 (L140, L161): organizational adoption, the exit metric, and EKS-06/07 (L265, L152).
- In S0150 (L598): which IP business question comes first.
- In S0149 (L811): the "freeze" of ADR-KOS-001, which no act on the record supports.
