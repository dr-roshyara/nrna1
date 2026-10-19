# KnowledgeOS Theory — Chapter 0: Foundations before the corpus

> **What this chapter is.** It develops the ideas that existed about KnowledgeOS *before* the main corpus was written (July–August 2026), from documents I am authorized to read. It is the starting point the corpus chapters will test, extend or overturn. **No KnowledgeOS corpus document has been read for it.**
>
> **Sources** (short names used below):
> - **Charter**: `docs/implementation/KnowledgeOS_Product_Discovery_Charter.md` (07-12, PROPOSED)
> - **DevConst**: `docs/implementation/KnowledgeOS_Development_Constitution.md` (07-27, PROPOSED · INERT)
> - **GKC**: the RQ-002 General Knowledge Constitution (07-11, research-frozen)
> - **PKSM**: `docs/implementation/Project_Knowledge_Strategic_Model.md` (07-11, frozen)
> - **Synth**: RQ-002 Architectural Synthesis
> - **v3**: `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` (08-16, AI-generated, PROPOSED)
> - **DG01**: `developer_guide/knowledgeos/01_architecture_and_design_patterns.md` (08-04)
>
> Line references are `[Source Lnn]`.
>
> **Status marks:** **Src** the source establishes it · **Ind** independently checked by me · **Int** my interpretation · **Ded** my deduction · **Hyp** untested hypothesis · **Open** unresolved.

---

## 0.1 The problem

**The idea.** Organizations that build software lose the knowledge that explains their systems.
- Senior people leave; decision records go stale.
- AI assistants ignore the standards; documentation drifts; reviews are inconsistent [Charter L15].

KnowledgeOS was proposed as an answer: an "AI-native Knowledge Operating System" that preserves architectural knowledge, enforces engineering governance and lets AI agents reason consistently [Charter L4].

**Status.** **Src** as a *working hypothesis*, explicitly "to be validated or falsified — not assumed" [Charter L4]. The charter is honest about its evidence: the repository is "an existence proof, not … validation", with "one retrospective data point, zero market data points" [Charter L25].

**Verification.** The *phenomena* named are well documented in the RQ-002 literature review:
- design rationale is the most demanded and least captured knowledge [Meta-Model L22];
- documentation decays within months [RQ-002 Raw L31, F-CS-5: "outdated in 30–90 days"];
- validity status is invisible when knowledge is found [Synth L21].

*(References corrected after audit R-02.)*

Two limits apply. Those findings are reports by four research executors [GKC L47] working from one standing mission [RQ-002 Charter L52–62], so they show agreement, not independent confirmation (Chapter T-01, C-9). The sources do not state whether the executors were AI; the mission text addresses "human, AI, or external tool". And none of it shows that *a product* solves the problem. **Problem: Src + literature-reported. Solution: Hyp.**

**Development (Int).** Across the sources the problem has one core:

> **Knowledge must be valid *at the moment it is used*, by a human or an AI, and the user must be able to see whether it is.**

Loss, drift, staleness and inconsistent AI behaviour are all failures of this one property. This statement is mine; it unifies Charter L15 with GKC's master failure signature ("validity status invisible at the point of discovery", Synth L21) and with GKC's purpose statement that knowledge exists to satisfy needs [GKC L66].

**Corpus evidence (batch C-1, 2026-10-18; revision RC-1).** The original corpus names the same problem and links it to an older research finding.
- It calls the failure **"model amnesia"**: re-deriving what the organisation already knows. It records it as observed eleven times and treats KnowledgeOS as its cure [Src: S0002 L17; E-S0002 Q10].
- It identifies model amnesia with **architectural knowledge vaporization**: design decisions without a first-class representation, which later work then violates (Jansen & Bosch, WICSA 2005) [Src: S0007 L21; E-S0007 Q6; Ext: the cited paper, not re-read here].
- The corpus applies the principle to itself: a commission to redo a frozen discovery was **declined**, because redoing it "would be occurrence #12 of the exact disease KnowledgeOS exists to cure" [Src: S0002 L17].

**Status.** **Src** for the naming and the link; the link to the literature is the corpus's own grading (FETCHED/CANONICAL), not re-verified. This supports the core statement above from the corpus side: what goes missing is knowledge whose validity and existence are not visible when work starts.

---

## 0.2 What "KnowledgeOS" names

The name carries **several meanings that accumulated over five weeks**. They must not be silently merged.

| Date | Meaning | Standing |
|---|---|---|
| 07-12 | a *placeholder* for a possible product ("not a decision") [Charter L5] | Src, PROPOSED |
| 07-27 | an **Evidence-Governed Engineering Knowledge Operating System** that helps organizations govern how AI takes part in engineering work [DevConst L22] | Src, PROPOSED · INERT |
| 08-04 | a running **observation runtime**: engineering events → observation → advisory recommendation → human decision → outcome → assessment [DG01] | Src (design, DG01); built. **One pass from observation to assessment is on record** (1 outcome and 1 assessment record in the evidence streams; background note B-11 [CHK]). **Zero feedback into governance** [GA L376–378]. *Audit fix C-03/H-07/R-01: DG01 itself does not report a completed cycle* |
| 08-16 | **"the T1 platform"**, with the product knowledge space (PKS) at T3 [v3 L26] | recorded as an ARB ruling *inside an AI-generated proposal*; original ruling record **not located** |

Two statements from 07-27 fix the product's character more sharply than any later document:
- **"AI is a capability inside the system. It is not the system."** [DevConst L24]
- **Knowledge is "proposed · researched · qualified · adopted · revised — never 'true'"; "KnowledgeOS governs engineering knowledge, not engineering truth."** [DevConst L40]

**Analysis (Int).** The meanings are *layers*, not rivals:
- a theory of governed knowledge (GKC);
- a platform that applies it to engineering;
- a product hypothesis that would sell it.

The 08-16 tier statement fits this reading. A fifth framing appears the same day: **"an evidence-based governance methodology for Strategic DDD"**, whose question is how architectural discovery can become "repeatable, evidence-based, and governable" [V3 L85–89]. It is classed as the platform's *engineering method* (EP01 L114), not as a product (Ch. 1 §1.15).

The same ruling withdraws an earlier claim that the system had "five names". It assigns the names to **different levels** [V3 L101–115]: KnowledgeOS is the reusable T1 platform; PKS is one product's knowledge space at T3, "never reusable, an instance"; EKP is a governed knowledge domain inside T1/T2; EKS has no referent and is retired. *Analysis (Ded):* a terminology freeze would have bound one name to three concepts. That is a **level (type) error**, not a labelling problem, and it is the same error this book guards against for standing vs lifecycle (Ch. 1 §1.2). It supports the layered reading above.

> **Revised 2026-10-18 (pilot file 2, Chapter 1 §1.8).** A five-tier model dated 2026-08-02 (cited-only) makes the relation **generative**: KnowledgeOS *creates* product knowledge spaces, which *guide* products, "as a compiler produces a program". **If that cited tier model holds** (it is cited-only, through GA), KnowledgeOS is not a knowledge base but the producer and governor of knowledge spaces. *Audit fix E-02: stated conditionally.* An adopted decision (AIP-14, cited) makes it a *supporting* subdomain of the election product; the multi-organisation product is a gated vision.

**A compact statement of the target (Src, PROPOSED; AG L15–26; added R0-1).** A later source proposes defining the system not as "a set of services that store knowledge" but as **"a governed state-and-analysis system"**:

> authoritative knowledge + evidence + applicability + authority + temporal validity + formal analysis + governed resolution + traceable delivery.

*Analysis (Int).* Every term has a place in this book:
- authoritative knowledge → standing (Ch. 1 §1.2);
- evidence → evidential grade and epistemic support (§1.3);
- applicability → Ch. 2 §2.2, §2.8;
- authority → Ch. 1 §1.6, §1.14.1;
- temporal validity → Ch. 2 §2.11 and Ch. 1 §1.16.2;
- formal analysis → Ch. 2 §2.3–§2.7;
- governed resolution → dispositions by act (Ch. 2 §2.4);
- traceable delivery → context assembly and provenance (§0.7; Ch. 1 §1.16).

It is therefore a good one-line summary of the **platform** layer. It does not settle the open question below, which asks which layer the corpus theory is about.

**Open:** whether the corpus theory is about the theory layer, the platform, or the product. The corpus chapters must answer this, not assume it.

**What KnowledgeOS is for: mission, constraint, and two identities (S0019; revision RC-43).** The corpus's mission discovery of 2026-08-02 answers "What is KnowledgeOS?", and corrects itself the same day.

- **Two identities of the same name** (kept apart per CV-1 §2a):
  - **KnowledgeOS (governance architecture, adopted purpose)** `[CON-0027 · invalid]` (partly valid; corrected after review C3 F-06: the answer at L173 derives from the body's "mission, as adopted" [L44–48], which the later annotation withdraws (AIP-14 is a constraint, not a mission [L23–29]); the enacted mission is "none PublicDigit-specific" [L29], against "for the purpose of improving the delivery of PublicDigit"; T0-02, T0-03, T0-17): "A governance architecture — not a software system — that governs how engineering work is planned, approved, executed, verified and evolved, in a project-, language- and provider-independent form, for the purpose of improving the delivery of PublicDigit" [S0019 L173];
  - **KnowledgeOS (AI-native Knowledge OS product)** `[CON-0028 · invalid]`: the charter's market hypothesis, with "organizations" as customers. Proposed, unapproved, its gate shut [S0019 L42, L58] (TODO T0-15).
- **Why vs what.** "Solely to improve delivery of PublicDigit" and "independent of project" look contradictory and are not: "AIP-14 constrains WHY work is undertaken. The Reference Architecture constrains WHAT is built." "Build portable things — but only when doing so advances this product." [S0019 L54–56].
- **Mission vs constraint** `[CON-0024 · invalid]` (partly valid: the test was built from, and applied to, one case, AIP-14, with no counterexample attempt; it is in tension with "A mission with no falsifier is not a mission" [S0019 L163]; review C3 F-24; TODO T0-16). The body first called AIP-14 a mission ("TWO missions"); a later annotation the same day **withdraws** this. AIP-14 "ships with a per-iteration metric and an over-evolution review trigger — a mission does not; a control does." The mission is "IMPLICIT BUT EVIDENCED, NOT VACANT": **enacted in machinery** through eight artifacts, none of them PublicDigit-specific [S0019 L23–29]. This is the enacted mission `[CON-0025 · invalid]`. It is invalid because (i) no ratification record exists (MQ-1, D-5 of S0017's docket), (ii) it conflicts with the PublicDigit purpose of CON-0027 [L29 vs L173], and (iii) the eight artifacts are named, not verified (TODO T0-14). *Corrected after review C3 F-07:* the "Mission (VACANT)" model line [L31] is most plausibly a remnant of an earlier revision ("CORRECTED at REV 2" [L29] implies a REV 1); the file has **three** temporal layers, and "VACANT" can be read as the empty statement slot. "Vacant" was, in the author's words, "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" (Ch. 3 §3.4).
- **The enacted mission at its primary source (S0032, 2026-08-02, REV 2).** The clarification that S0019's annotation draws on states: "THE MISSION IS IMPLICIT BUT EVIDENCED — NOT VACANT." [S0032 L63]. Its argument: eight artifacts, from the reuse litmus to the pre-positioned split for a second adopter, are each "meaningless unless reuse across products is a purpose" [S0032 L75], so the mission they enact is to "Provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge." [S0032 L89]
  - **Ded: the argument is abductive and does not separate mission from vision.** That the artifacts presuppose reuse as a purpose is plausible. But the same artifacts are equally explained by the **vision** (the sponsor's unapproved hypothesis of a product for many organisations, §0.2 above), or by designers hedging for a future that may never come. Purpose inferred from design shows that **someone** intended reuse; it does not show that reuse is the **mission** rather than an anticipation of the vision. So CON-0025 stays invalid, for this further reason.
    - **A third rival explanation, from the corpus itself.** S0019's "why vs what" (above) also accounts for all eight artifacts with no mission of reuse: portability as a **design quality** under Product Primacy ("Build portable things — but only when doing so advances this product." [S0019 L56]).
    - **The eight artifacts are not eight independent pieces of evidence.** Several come from the same two standards families (ES-005 and ES-006), which are themselves PROPOSED. By the independence rule of Ch. 4 §4.1, they count as fewer lines of evidence than eight.
    - **The source breaks its own evidence rule.** Its rule is "No hypothesis is elevated to fact" [S0032 L10], yet the inferred mission is graded "OBSERVED" in two tables [S0032 L139, L157], and its owner (the sponsor) is graded observed in a row that also says "no owner has ratified it" [S0032 L139]. By the source's own scale, the mission is INFERRED at best.
  - **The file contradicts its own revision.** REV 2 corrects "vacant" to "implicit but evidenced" [S0032 L11], yet two later passages still say "Mission is vacant" [S0032 L167] and "the vacant Mission layer" [S0032 L190], and the closing summary says "the MISSION LAYER IS VACANT in canon" [S0032 L233]. The correction was not carried through the file. A second residue: the header still announces "+ INTENDED (new — see below)" [S0032 L10], a grade the same file withdraws.
  - **An overloaded dimension, withdrawn (Src).** The file first added INTENDED as a fourth evidence grade, then withdrew it: "Intent is not evidence", and the grade "overloaded the evidence dimension" [S0032 L25]. Intent is instead **derived**, as f(governance-status, owner) [S0032 L27]. **Int:** this is the model-integrity test (a new dimension, an overloaded existing dimension, or merely another value) applied to the corpus's own evidence scale. It is the first recorded case of that test in the sources read so far.
  - **"No check enforces", against the book's census (Src, conflicting).** The file restates a review finding as "No capability or check in the repository enforces — every one is advisory" [S0032 L198]. That is a universal claim with no census behind it, and it conflicts with the census of Ch. 1 §1.9, which found two blocking checks and one compiled guard, and with S0034's "18 enforced lint rules". The claim holds at most for the platform's **capabilities**, not for every check.
  - **A kind of nothing (Int).** On its own reading, the mission is knowledge without a carrier, E4 of Ch. 2 §2.8.8. That is exactly the error class the file names: "ARTIFACT ABSENCE ≠ CONCEPT ABSENCE" (§3.4).
  - **Direction of service needs a horizon (Src).** The file rejects calling PublicDigit a "laboratory": under that reading the product serves the platform, while the adopted rule (Product Primacy) has the platform serve the product. "These are opposite directions of service, and only one is adopted." [S0032 L130]. "Both can be true across time horizons — but only if the horizon is stated." [S0032 L132]. **Int, scoped:** this dissolves fork F1 **for the vision only**. The vision is a statement about later (a product for many organisations), and Product Primacy a statement about now; they conflict only when the horizon is left out. It does **not** dissolve F1 for the **mission**: the file puts the enacted mission in the present, "the platform is not describing its mission, it is already behaving according to it" [S0032 L29]. So an enacted product-neutral mission (now) still conflicts with Product Primacy (now). F1 stays open for the mission. Which horizon governs is still the sponsor's decision.
  - **A test for a vision, and a tension with it (Src, Ded).** S0032 **proposes** to mark a vision by its **form** (a proposal, not an adopted definition): "A statement that carries its own falsifier and awaits market validation is a vision." [S0032 L59]. Earlier the same day, S0019 asks of the mission: "A mission with no falsifier is not a mission" [S0019 L163].
    - **Ded:** S0032's sentence is a **sufficient** condition with two parts: a falsifier **and** awaiting market validation ⇒ a vision. It does not say that a falsifier alone marks a vision, nor that only visions have falsifiers. So, with S0019's requirement that a mission carry a falsifier, **no contradiction follows**. The market feature that tells the two apart is in S0032's own sentence.
    - **What remains is a tension, not a contradiction:** S0032 gives the falsification clause as the vision's form and gives no form for the mission. What a mission's falsifier would test (a purpose served, rather than a market) is stated in neither source. **Open.**
  - **Conceptual order is not historical order (Src).** "The platform was built bottom-up; the hierarchy is being discovered top-down." [S0032 L167]. **Int:** this is also a caution for this book's own method. The order in which the theory is reconstructed is not the order in which it arose, which is why the corpus's dates, not the reading order, decide questions of precedence.
  - **Self-application (Src, Int).** "KnowledgeOS engineers the engineering of engineering knowledge." [S0032 L213]: a method validating a method, a constitution for methodology. **Int:** self-application is where the circularity risks of this book arise (Ch. 1, the self-check of CAP-001; Ch. 3 §3.8, SEQ-0012). A self-applied validator needs an independent check from outside, which is why the reviews here begin with a blind reading.
- **Responsibilities and non-responsibilities.** The source traces eight responsibilities (e.g. "Refuse to approve its own work") and ten things KnowledgeOS must never own (business knowledge, a product's evidence, register contents, runtime vocabulary, its own approval, governance defined by automation, duplicated knowledge, materialized copies, any product's PKS, orchestration machinery) [S0019 L66–90]. **N-1 (business knowledge) is recorded as breached** [L81, L92]. Full list: record E-S0019.

**Int:**
- the "five names" problem of the earlier ruling (above) and this two-identity finding are the same issue: one name, several concepts;
- *(corrected after review C3 F-06; "settled" withdrawn)* on 2026-08-02 the corpus **leans** to the governance-architecture identity, but its premise (AIP-14 as mission) was withdrawn by the same file's annotation; both identities stay open (T0-02, T0-03, MQ-1).

**The mission as a decision, and AIP-14's metric (S0023; revision RC-54).** The ARB's decision docket of 2026-08-03 packages the mission as decision D-5, for the **sponsor**. It asks the sponsor to "Ratify (or amend and ratify) the mission the machinery already enacts", quoting it as: "provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge" [S0023 L49; E-S0023 Q10]. The text is product-agnostic. *Same day, a different wording:* S0026 describes the enacted mission as "engineering knowledge that does not vaporize and demonstrably improves PublicDigit delivery" [S0026 L106], which is PublicDigit-specific; the corpus states the enacted mission in two incompatible ways on 2026-08-03. **Int:** it follows the annotation of S0019, not its body (which named PublicDigit's delivery). The docket says the empty written slot "is what let AIP-14 be misread as the mission" [L50]. It is a recommendation; the mission remains unratified `[CON-0025 · invalid]` (TODO T0-14).
- *(corrected after review C4 F-04; RC-54a)* **Per the docket (REV 2), the measurement basis of AIP-14's metric is undecided**; AIP-14's own text was not read here. REV 1 simply said "Compute the AIP-14 Platform Cost metric" [S0023 L20]; a review question made the basis contested [L32]: "The tripwire cannot fire if the metric is never computed" [L21], and the first decision is to **define** how platform cost is measured (D-9a) before running the check [L36–43] `[CON-0051 · invalid]` (TODO T0-18). So the feature that made AIP-14 a constraint rather than a mission ("ships with a per-iteration metric" [S0019 L27]) exists as a **name**, not yet as a measure. Whether this bears on the mission-vs-constraint test is open: that test concerns **form** (a statement shipping with a metric and a trigger), and a disputed basis removes neither (CON-0024; TODO T0-16). See Ch. 5 §5.1.

---

## 0.3 The knowledge model

**The idea.** Knowledge is not a document. It is a set of **claims**, each located in a space of independent properties [GKC L13–24]:

- **Nature:** what it asserts. Decision · Definition · Constraint · Description · Finding · Measurement (· Skill).
- **Representation:** how it is expressed. Executable · Recorded · Conversational · Tacit.
- **Governance status:** where it is in its life. Draft · Canonical · Superseded/Historical · Deprecated · Archived.
- **Authority scope:** where it is valid (provisional). Context-local · Project · Platform · Constitutional.

A **claim** is content plus its position in this space, plus provenance, status and "scent" (the labels by which it can be found) [GKC L19]. A document is only a *bundle* of claims, and is governed claim by claim [GKC L20].

**Status.** **Src.** It is the frozen research model [GKC L4], derived over one day by deliberate revision (Chapter T-01 §B).

**Why it matters.** The model explains failures that document-based thinking cannot:
- The "rationale gap" is not a missing document type. It is knowledge of *Decision* nature trapped in *Tacit* representation [RQ-002 Meta-Model L22].
- The cure is a *move* to another representation, not a new store.

**Formalizing the source's own asymmetry (Ded; corrected after audit L-01/L-02).** The sources do not claim that every combination exists. They call the dimensions "orthogonal, **unevenly populated**" and "orthogonal **with one asymmetry**" [Meta-Model L63, L65]. Two constraints are stated:
1. tacit knowledge "cannot carry explicit status" [Meta-Model L65];
2. skill exists only as tacit [GKC L36].

To express "has no status", the status and scope coordinates need an explicit *none* value ⊥:

> S = { (n, r, g, a) ∈ N × R × (G ∪ {⊥}) × (A ∪ {⊥}) : (r = Tacit ⇔ g = ⊥) ; (r = Tacit ⇒ a = ⊥) ; (n = Skill ⇒ r = Tacit) }

Notes on the formula:
- The scope constraint follows because canonicity cannot hold where no status can [GKC L39, L74].
- The ⇔ in the first constraint reads Meta-Model L65 as "only tacit lacks status"; the source supports at least ⇒.
- My earlier version, "r = Tacit ⇒ g undefined" with g ∈ G, was **ill-typed**: read literally, it excluded every tacit and every skill point, i.e. exactly the knowledge it was meant to describe. *Withdrawn.*

This is a formalization of what the sources say, not a correction of them.

A second weakness: the model's own verification against the Engineering Platform uses values outside its declared lists [GKC L103]:
- "Runtime" as a scope;
- "composite" as a nature;
- "Canonical-with-verdict-history" and "Canonical-Stable" as statuses.

> **Terminology note (audit C-02, H-01).** GKC's fourth dimension "authority scope" is called **canon scope** in this book. It is different from an actor's **grant scope** (Ch. 2 §2.8.2). GKC's "governance status" is defined as **lifecycle position** [GKC L38]. Its relation to *standing* is set out in Ch. 1 §1.2's mapping table. Either the value lists are incomplete or the platform does not fit without residue. **Open, testable:** check every artifact against closed value lists.

**When does a rule stop being true? (batch C-1; revision RC-2).** On 2026-08-03 the corpus records a gap between this model and the platform:
- The platform can retire **documents** (superseded, archived, historical).
- But "no rule says when a RULE stops being true, only when a document does", and retirement "has NEVER been observed in several registers" [Src: S0004 L36; E-S0004 Q8, Q9].
- The corpus asks, as an **open** question (MM-4), whether lifecycle state attaches to the artifact or to the rule it carries [Src: S0004 L36].
- It lists the states knowledge needs as **Candidate · Approved · Current · Deprecated · Historical · Retired · Superseded · Archived**, "lifecycle states of KNOWLEDGE, not documents" [Src: S0004 L36; E-S0004 Q7]. This extends the governance-status values above with Approved, Current and Retired.

**Analysis (Int).** GKC already places status on **claims** (above): that is a *model* statement. The 2026-08-03 record says the *platform* does not yet do this: a *realization* statement. The two do not contradict; they mark a model-to-platform gap. **Hyp:** the eight states mix at least two questions, adoption (Candidate → Approved) and currency and retention (Current … Archived), so they may not form one dimension. Not decided (TODO T0-07).

**The corpus's meta-model: kinds on top of these dimensions (S0009; revision RC-16).** On 2026-08-03 the corpus asked whether a meta-model of KnowledgeOS had emerged, and answered that it is **dimensional-with-archetypes**: every element has a **kind** plus a position on this section's dimensions [Src: S0009 L65–69; E-S0009]:

> element = KIND × NATURE × REPRESENTATION × GOVERNANCE-STATUS × AUTHORITY-SCOPE (× EVIDENCE-STATUS × OPERATIONAL-STATE, two candidate additions)

- **The nine kinds:** domain · process · capability · knowledge · artifact · runtime asset · container (knowledge space) · projection · purpose. They come from **convergence across classification passes** [Src: S0009 L36–46]. They are held as **candidate archetypes**, not primitives (REV 2). The grades differ:
  - **container** is demoted to candidate: its distinguishing edge (generating projections) is seen in one space only;
  - **purpose** is a candidate, "found twice, exercised little";
  - the source's later lines (L71–73, L135) still list container among the confirmed kinds, an inconsistency inside the source [Src: S0009 L24–26, L84].
- **The category test** that **rejected** some proposed categories (it did not produce the kinds; corrected RC-16a after independent review): a category that changes when only the *encoding* or the *scope* changes is not a kind (RQ-002: "one thing cannot change type by being encoded differently") [Src: S0009 L46; E-S0009 Q9]. So:
  - **"governance asset"** is a *nature* value (normative force), graded **MEDIUM-HIGH**, weaker than the other two rejections [Src: S0009 L56; Q11];
  - **"product asset"** is a *scope* value [Src: S0009 L58; Q12];
  - **"knowledge asset"** fuses knowledge with its carrier, which I-1 forbids [Src: S0009 L55].
- **Why scope must stay a dimension:** the kinds are platform knowledge, but their *instances* lie on both sides of the platform/product line. A hospital adopter "inherits the kinds and none of the instances" [Src: S0009 L110–111].

**Status.**
- **Src** (candidate, AI-generated, 2026-08-03).
- The corpus calls the result "emerged" because five classification passes converge. That their independence was shown is **not** established: two passes come from the same programme on the same day. Read as **persistence across passes**, not independent confirmation (TODO T0-11).
- **Open:** is KIND its own axis or a nature value (U-MM-1)? If it is a nature value, the formula counts nature twice. So this is a question of whether the formula is **well-formed**, not just of naming (TODO T0-10).
- **Developed in Chapter 3** (revision RC-41): the six dimensions with their concept IDs (§3.1), what is not a dimension (§3.2), kinds and the category test (§3.3), and knowledge vs artifact (§3.4), from S0016 and S0009.

**Many lifecycles, one conflation in the platform (S0012; revision RC-26).** On 2026-08-02 the corpus found **nine lifecycles** already in use [Src: S0012 L48–61; E-S0012 P5]:
- document status (8 states, with order and a terminal marker);
- the promotion ladder;
- the engineering protocol;
- capability maturity;
- decision-record status;
- methodology maturity;
- identifier lifecycles;
- observation protocols;
- the authoritative lifecycle document itself.

Some pairs are **declared orthogonal**, as this section requires: status ⟂ authority; decision-record status ⟂ methodology maturity. Two pairs may be **duplicates** [Src: S0012 L131–136].

The authoritative lifecycle document draws "idea" and "research" as **statuses**, while the executable enum defines them as **types**: the very conflation that document warns against [Src: S0012 L118–125]. The source records this "as an observation, not a defect claim — the diagram may be aspirational, or the enum may be behind it" [S0012 L125]. **Evidence for TODO T0-07** (hedge restored, RC-26b): on this one observation, lifecycle values and kinds are **not consistently** kept apart in the platform's documents.

---

## 0.4 How knowledge moves

> **Corrected (audit L-04).** GKC's flow algebra names two moves. But GKC also lists *canonical → superseded* as a Knowledge Transition [GKC L23], and §0.6 and Ch. 1 §1.2 treat standing changes as act-labelled moves. The book therefore uses **three move types**:
> 1. derivation;
> 2. representation transition;
> 3. **standing transition, by act**: no new claim and no change of form, only a change of what the claim counts as.

**The idea.** Knowledge changes in exactly two ways [GKC L78]:
- a **nature derivation** creates a new claim of a new nature that points to its source (an observation becomes a finding);
- a **representation transition** moves the same claim to a better form (a rule in prose becomes a test).

A third ingredient, **consumption feedback**, closes the loop: using knowledge is how it is validated [GKC L74]. The lifecycle is therefore a **cycle**, not a pipeline: *creation → qualification → publication → discovery and use → evolution → supersession → historical retention → staged retirement* [GKC L74]. **Src.**

**Development (Ded).** These moves make a small, well-defined dynamics.
- **Transition:** a move that changes one coordinate of one claim and must keep its nature and provenance. GKC raised this to an invariant: a silent transition, such as a summary that drops a caveat, is a defect [GKC L65].
- **Derivation:** a move that adds a claim and an edge to its source. Knowledge therefore forms a **growing provenance graph** in which nothing is overwritten. Supersession is a link, never an edit [GKC L62].

This is exactly the structure the later engineering platform implemented: append-only records, supersession by link. The platform adopted it before the model existed [GKC L80, L103].

**Open.** How many evolution "regimes" are there?
- The Synthesis says "exactly two": decisions are immutable, descriptions evolve [Synth L40].
- The Meta-Model lists four values of the regime function [Meta-Model L56].

My reading (Int) is that this is terminology rather than contradiction: measurements behave like decisions (immutable), and skill is tacit and outside artifact governance. It is unresolved in the sources.

---

## 0.5 The invariants

GKC states eleven rules that every piece of knowledge must obey [GKC L56–66]. Its heading still says "ten" [GKC L54]; the eleventh (the telos) was added later the same day (T-01 §B).

| # | Invariant (my wording) | Kind | Assessment |
|---|---|---|---|
| I-1 | Knowledge never silently disappears | design rule | consistent with staged retirement [Synth L61] |
| I-2 | Status changes are explicit, reasoned and visible where knowledge is found | design rule | the central rule; answers the master failure [Synth L20] |
| I-3 | Every claim carries its provenance | design rule | "without provenance it is data, not knowledge" [Synth L110] |
| I-4 | Contradictions stay visible: they coexist at rest and are resolved at use | design rule | **Open** for machine users, who need one answer [Synth L57]. *Partial answer (audit H-11): Ch. 2 §2.3–2.7 give machines a defined output at use: a proven conflict with its region, or UNKNOWN. They never give a silent pick. The final choice stays a human disposition* |
| I-5 | Authority is scoped; nothing claims global truth | empirical generalization | supported by the failure of "single source of truth" [RQ-002 Raw L13] |
| I-6 | Freshness is visible and depends on the domain's half-life | design rule | — |
| I-7 | Supersession is a link, never an edit | design rule | — |
| I-8 | No capability may create a second population that maintains knowledge separately from its users | **empirical generalization raised to a rule** | **weak point:** the one counterexample is absorbed by re-description [Synth L15], which risks unfalsifiability |
| I-9 | Quality is evidenced, never asserted | design rule | — |
| I-10 | Representation changes are deliberate and preserve nature and provenance | design rule | added when the Constitutional Model was first committed (07-11, git `d7e9763b0`; T-01 §B) |
| I-11 | **Knowledge exists only to satisfy knowledge needs**; documentation is one representation | axiom (telos) | claimed to generate all other rules [GKC L66]: see below |

**Testing the telos (Ded; corrected after audit L-03).** What does GKC actually claim? It says the telos generates *specific rules*: minimal sufficient context, never-a-copy, ephemeral assembly, gap evidence and selective capture [GKC L66]. It does **not** claim to generate I-1…I-10. Testing those invariants is therefore a stronger question than the source poses, and I keep the two apart.

**Can the invariants be derived from I-11?** Not from I-11 alone: a need can be "satisfied" by an unsourced statement that happens to be right. Three premises are needed, each stated separately (none is stated in the sources):

| Premise | Content | Yields, with I-11 |
|---|---|---|
| **(T)** provenance-trust | a need is satisfied only by knowledge the user can trust for the task, and that requires knowing **where it came from** | **I-3** (every claim serves some need, so every claim needs provenance) |
| **(T′)** validity-trust | trust also requires knowing the claim's **current validity**: its status and freshness | **I-6**; the *visibility* part of **I-2**; **I-9** (quality claims must rest on recorded properties, otherwise validity cannot be known) |
| **(R)** reconstructability | users must be able to recover **what was held and why** | **I-1**, **I-7**, and the *explicit, reasoned* part of **I-2**. (R) is close to a restatement of these invariants, so this derivation is weak |

**Result.**
- I-11 is not sufficient alone.
- With (T) it yields I-3. With (T′) it yields I-6, the visibility part of I-2, and I-9. With (R), whose derivation is weak, it yields I-1, I-7 and the rest of I-2.
- **I-4, I-5, I-8 and I-10 remain underived**; they need further, partly empirical premises.
- My earlier statement that "I-11 + (T) generates I-1, I-2, I-3, I-6, I-7" **silently strengthened (T) and omitted I-9**. *Withdrawn.*
- GKC's own list (P6, never-a-copy, ephemeral assembly, gap evidence, selective capture) is not tested here. **Open.**

**Invariant numbers are local to their documents (batch C-1; revision RC-3).** The numbers above are **GKC's**. Corpus documents use the same short labels for different rules:

| Label | GKC (this table) | Elsewhere in the corpus |
|---|---|---|
| I-8 | no second maintaining population | "governance precedes automation" [S0003 L85] |
| I-9 | quality is evidenced | "Governance-status ⟂ Authority" [S0001 L9; Relationship Ontology L113, contextual lookup]; "exactly one canonical owner" [knowledge-domain-model report L259, contextual lookup] |

*[Src: E-S0001 §10, E-S0003 §10.]* A citation of "I-9" is ambiguous without its source document; this book always names the source.

**"Governance precedes automation" is unnumbered in canon.** The corpus itself calls it an "invariant-in-waiting", left "unnumbered" in canon. External literature separates policy from mechanism **but does not order them**: "it does not order them universally" [Src: S0007 L33, L56; E-S0007 Q9]. **Int:** separation is a borrowed, well-founded idea; *ordering* governance before automation is the programme's own normative choice and needs its own justification. Which document first gave the principle a number is **TODO T0-08**.

**Canonical or not? (S0013; revision RC-28).** The same day's inventory lists "Governance precedes automation; automation never defines governance" among the 12 **canonical** concerns, citing Reference Architecture §1 [Src: S0013 L40; E-S0013 Q10]. Yet the same inventory gives the Reference Architecture's own status as **DRAFT** [Src: S0013 L61], and S0007 calls the principle unnumbered and "in waiting". The corpus does not reconcile the three. **Classification: undetermined.** Whether a principle stated in a DRAFT instrument can be canonical is a question about the standing of the instrument (T0-08).

**Weighed by a third same-day source (S0014; revision RC-32).** The evidence reconciliation corrects its own earlier wording: "I-8 over-granted"; the principle is "**deliberately unnumbered in canon — an invariant-in-waiting**", per the Reference Architecture §1's *own note*, and is to be listed as a **candidate** [Src: S0014 L44; E-S0014 Q8]. Two of three same-day sources (S0007, S0014) read it as a candidate, and S0014 cites the qualifying note of the instrument itself. **Int:** the better-supported reading is **candidate**; S0013's "canonical" listing stays recorded as a conflicting classification.

**A fourth reading (S0023, 2026-08-03; revision RC-57).** The ARB's decision docket calls the principle's source an "adopted stance N-10 / Reference Architecture §1" [S0023 L168; E-S0023 Q21]. In the same docket, Package 7 still asks the ARB to **ratify** the Reference Architecture [L110–114], and N-10 is, in S0019, one of the "never own" non-responsibilities of a CANDIDATE document. **Int:** on the sources read, "adopted" is not supported; the reading "candidate" (RC-32) stands. TODO T5-03.

**A second, engineering-side invariant set.** Beside GKC's eleven, the corpus lists a set it describes as derived twice by "mutually blind paths" [S0014 L22]. *(Qualified RC-32a: the blindness ran one way only, since the blind reviewer could read the governing documents, and the review itself says "Most of our 11 invariants" were reproduced, with one downgrade [S0015 L34].)* The set: "human authority · produced-never-asserted · append-only · one-rule-one-home · fail-closed · burden-reversed · one-owner" [Src: S0014 L35]. How the two sets map onto each other is **open** (TODO T0-13).

---

## 0.6 Knowledge and authority

Between 07-27 and 08-16 a second idea becomes central, initially from engineering governance rather than knowledge research:

- **Knowledge feeds authority; it never holds it.** A verdict is an output, never a decision. This is compiled into code (`Verdict.php::emittable()`) [v3 L284; KT documents].
- **Decisions belong to humans.** The AI provides analysis and recommendations; it never approves, ratifies, authorizes or promotes [DevConst L14–18].
- **Knowledge passes through stages that cannot be skipped** [DevConst L55–56]: *observation → evidence → research → candidate → qualification → decision → adoption → implementation → operational evidence → revision*.
- **"Unknown" and "no" are legitimate answers.** "Evidence not found" is complete and respectable [DevConst L101]; "no new capability" is a success [DevConst L61].

**Connection to the knowledge model (Int → Ded).** In GKC, *governance status* is a property of a claim (draft, canonical, …). The authority idea adds something GKC lacks: **who may move a claim between statuses.** Formally:

> status transitions **Draft → Canonical** and **Canonical → Superseded** are *acts*. An act is valid only if a recorded human decision covers it. A mechanism may *report* that the conditions for the act are met; it may not perform it.

This makes I-2 ("transitions are explicit and reasoned") precise: *explicit* means a recorded act; *reasoned* means a recorded reason is present (a stipulation, Ch. 1 §1.17.2); whether that reason cites adequate evidence is a matter of well-foundedness. **Int.** *Open (added in correction round 3):* whether a missing reason makes the act **invalid** or only **ill-founded** is not settled. Chapter 1 §1.17.2 sets out three readings: (a) validity condition, (b) quality obligation, (c) validity condition for specified kinds of act. It is a reserved decision. I-2's third part, "visible where knowledge is found", is a presentation obligation (§0.1). It also explains why the 08-16 documents insist that absence of a recorded grant is not a denial, only a gap in the record (Chapter T-01 and KT-8).

> **Revised 2026-10-18 (pilot file 1, Chapter 1 §1.2).** "Status" above should be read as **standing**. A claim has immutable **provenance** and act-changed **standing**; a later schema conflated them in one "authority" field. GKC already kept them apart [GKC L19]. Chapter 1 §1.2 gives the precise rules: no direct creation→authoritative edge; provenance never confers standing.

**Status.** **Src** for each statement. That they form **one theory of knowledge plus authority** is **Int**. Whether the corpus treats authority as part of the KnowledgeOS theory or as surrounding governance is **Open**.

---

## 0.7 Knowledge needs and context assembly

**The idea.** If knowledge exists to satisfy needs (I-11), the central act is not storing knowledge but **assembling it for a task** [PKSM L35, L59–84]:

1. A task gives rise to a **knowledge need**: which understanding must exist for the task to proceed safely?
2. The need selects a region of the space: the relevant definitions, governing decisions, applicable rules.
3. That region is assembled from *live* sources into a **working context**: minimal, with statuses visible, contradictions shown, provenance attached.
4. The working context is **thrown away at the end of the task**.
5. What persists is (a) any new decisions made during the task, recorded where they belong, and (b) an **evaluation** of the context: was it sufficient, what was missing, what was unnecessary [PKSM L124].

A need can end as **satisfied** or **unsatisfiable**. Unsatisfiable needs accumulate as evidence of **knowledge gaps** [PKSM L47].

**Verification (Ded).** The sources claim the throw-away rule satisfies I-8 "by construction" [PKSM L50]. The argument holds:
- a working context that persists would need someone to maintain it, i.e. a second population;
- one that is discarded has no maintainers;
- the evaluation that persists is a *measurement about* an assembly, not a copy of knowledge.

**Valid**, given that evaluations stay small and are not themselves used as a knowledge store.

**The fourth theory (Src, ARB observation; audit R-12).** The chain *need → working context → evaluation → gap evidence → improvement* is named by the ARB itself as "a fourth theory — **Knowledge Evolution**", watched and deliberately unbuilt [PKSM L128]. It is a **feedback theory of knowledge growth**. My development of it follows (Ded/Hyp): knowledge improves where use reveals what was missing. Two consequences follow:
- **(a)** An **unsatisfiable need is information**, not failure. It records *where* knowledge is absent. This matches the platform principle that "unknown" is a legitimate result.
- **(b) Hyp:** the corpus's reported concepts of "Zero" and "Gap" may formalize exactly this. A *gap* would be a need with no satisfying claim, and different kinds of "zero" would be different reasons for an empty assembly: proven absent, not yet examined, outside scope. This hypothesis must be tested against the corpus, not assumed.

> **Revised 2026-10-18 (pilot files 2 and 5).** Sharpened into a list of six *(now ten in Ch. 2 §2.8.8, two of them provisional candidates from BRANCH-01)* distinct "empties" (known-empty ∅ · unspecified ⊥ · not looked at · no carrier · no knowledge · not analysable), with a three-valued logic separating them. See Chapter 2 §2.8.8, which after pilot 2 lists seven kinds plus one failure mode (silent substitution of a default).

> **Revised 2026-10-18 (batch 3, file 5; Ch. 1 §1.17.5; made conditional in round 2: the revision rests on a hypothesis).** Step 2 selects by **relevance only**. Nothing in the model says who may *read* what is selected. A later document finds the same silence across the whole folder (OQ-25). **If** read access is governed (Hyp), step 2 must read: the need selects a region **among the claims the reader may know**, filtered by access before relevance. An assembly emptied by that filter would be a candidate kind of nothing (*withheld*, E8, Hyp; Ch. 2 §2.8.8).

**Status.** The model is **Src** (frozen, 07-11). Its validation horizon was "~20–30 real engineering tasks" [PKSM L129]. **No evidence was found that this validation happened.**

> **Revised after audit (C-03, L-21).**
> - The feedback loop has **never closed in practice**. One observation-to-assessment pass is on record; feeding results back into governance has happened zero times [GA L376–378] (Ch. 1 §1.8).
> - An **unsatisfiable need does not by itself show that knowledge is missing** (E5). The assembly may have failed because the knowledge was not reached (E3), has no carrier (E4), or could not be analysed (E6). Gap evidence must record *which*, or it overstates gaps (Ch. 2 §2.8.8).

**A reserved place for knowledge decisions (batch C-1; revision RC-4, corrected RC-4a after independent review).** The Engineering Decision Model's own catalogue table reserves **knowledge decisions**: DetermineKnowledgeNeed · Source · Representation · Qualification · Promotion. They are "pilot-gated candidates — arrive only with pilot evidence; same pattern, different domain" [Src: S0006 L54, quoting the model; E-S0006 Q8].
- **Int:** the five names follow this section's sequence (need → source → representation → qualification).
- **Dating:** S0006 says the slot was reserved "a month before the work began" (L56). That is S0006's own assertion; **the quoted table itself is undated in the source**, and no ARB attribution is given for it. The earlier wording "(ARB, 2026-07-11)" and "anticipated … before the August discovery work" is **withdrawn**.
- **Status:** the reservation is **Src** (quoted model text); the catalogue was not yet realized on 2026-08-03 (TODO T0-09: is it ever realized?).

**An external challenge to codification (Src, as recorded by the corpus).** Pure **codification** strategies fail when knowledge is used in a personal, contextual way (Hansen et al., codification vs personalization). The corpus records this as corroborating its own finding E-1, a falsification of its consumption model [Src: S0007 L43; E-S0007 Q10]. **Open** until E-1 is read in sequence: the challenge bears on whether assembling context from codified claims (step 3 above) is sufficient.

---

## 0.8 Look-back review after sections 0.1–0.7

### (1) Ideas left out so far

| Idea | Source | Decision |
|---|---|---|
| Staged discovery with kill gates; "no product here" counts as success | Charter L9–19 | **Process, not theory**, but the *falsifiability stance* belongs to the theory. Folded into 0.1 |
| Challenge, Capability-Discovery and Inheritance rules: "inherit before extending", "no new capability" as success | DevConst L59–79 | **Belongs** as a principle of parsimony in how knowledge grows. **Added below** |
| Methodologies are modular; never inject a whole methodology | DevConst L103–105 | **Belongs**: knowledge about *how* to work is itself governed knowledge. **Open** where it sits in the space (Nature = Constraint?) |
| AI receives only the minimum governed context | DevConst L107–109 | already in 0.7 (minimal sufficiency) |
| What a project-knowledge context owns, observes, never touches; "never a copy of the project" | PKSM L14–17 | **Belongs**: a sharper form of I-8. **Added below** |
| Retrieval demoted from a peer concept to a step inside assembly | PKSM L36 | **Belongs** (supports 0.7). **Added below** |
| "Scenario": durable only when executable and maintained by its users | PKSM L31 | minor; an application of I-8 |
| Engineering Platform and Project Knowledge as sibling contexts under one constitution | PKSM L86–114 | **Belongs** to the identity question (0.2). Recorded as **Int** support for the layered reading |

**Additions.**
- **(i) Parsimony of growth.** New knowledge structures are justified only when existing ones demonstrably cannot express a need. *Int*: my generalization of DevConst's rule for proposing capabilities, domains, documents and concepts [DevConst L65–79] (audit R-18). This is I-8's companion: I-8 limits *who maintains* knowledge; parsimony limits *how much structure* exists.
- **(ii) Never a copy.** A knowledge system *references, assembles, validates and contextualizes*; it never duplicates [PKSM L17]. This is the strongest single rule for avoiding decay.
- **(iii) Retrieval** is a step inside assembly, not a goal of its own.

### (2) Does the theory make sense so far?

- **Coherent core.** One claim-based model of knowledge (0.3–0.4), one purpose (0.1, I-11), one consumption act (0.7) and one authority rule (0.6) fit together. The only premise needed to connect them is (T): trust at use.
- **Three weak points:**
  1. **I-8 risks unfalsifiability.** "Same population" must be defined independently of whether the artifact survived.
  2. **The space is a constrained subset**, and its value lists may be incomplete (0.3).
  3. **Nothing has been validated in use.** The 20–30-task validation was planned, not found. The runtime has one observation-to-assessment pass on record and zero feedback into governance.
- **One unresolved tension:** whether *authority* is part of the knowledge theory or the governance around it (0.6). The answer changes what KnowledgeOS *is*.

---

## 0.9 Questions the corpus chapters must answer

1. Which layer is the corpus theory about: theory, platform or product (0.2)?
2. Does the corpus keep the claim-in-a-space model, or replace it? In particular, what are "K" and the "kernel"? *(Lead added after audit H-11: an authorized source already names **Context Assembly** as "the candidate kernel of the knowledge ecosystem", while the election system stays the product's core domain [PKSM L125]. Whether the corpus's "kernel" is this, or something else, is open.)*
3. Do "Zero" and "Gap" formalize unsatisfiable needs and kinds of empty answer (0.7, **Hyp**)? *Partly answered by research branch BRANCH-01 (Ch. 5 §5.4, forward reference):* the corpus's **absence lens** supplies kinds of nothing (two provisional candidates added to Ch. 2 §2.8.8, E9 and E10); its **gap operator** represents a need relative to a contract, one item per requirement. **"Unsatisfiable" remains unformalized:** the operator records what is unsatisfied now, not what cannot be satisfied.
4. Does the corpus resolve the authority question (0.6)?
5. Does it state premise (T), or an equivalent, and does it fix the weak points in 0.8?
6. Is the link from the knowledge model to the architecture verified? V3's open-question register marks it **open**, citing a recorded absence (KBI-1, cited-only) [V3 L467]. If the corpus also lacks the link, the theory cannot claim that its model *governs* the architecture, only that both were written. *(Added in correction round 1, from register row B3-F1-28.)*
