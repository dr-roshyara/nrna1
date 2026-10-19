# KnowledgeOS Theory — Chapter 1: Knowledge, authority and execution

> **What this chapter is.** It develops the August 2026 refinements of the foundations in Chapter 0, from authorized background documents (the 16 August knowledge-transfer set). It is not yet corpus evidence. Ideas that come to these documents only *through citation* of corpus files are marked **cited-only**: they are leads for the corpus chapters, not established.
>
> **Status marks** as in Chapter 0: **Src** · **Ind** · **Int** · **Ded** · **Hyp** · **Open**.
>
> **Sources** (short names):
> - **EP01**: `docs/knowledge_tranfer/20260816_1129_arb_evidence_package_01_v3.md` (AI-generated, PROPOSED, an "ARB input")
> - **GA**: `docs/knowledge_tranfer/20260816_1108_architecture_gap_analysis_knowledgeos.md`
> - **TR**: `docs/knowledge_tranfer/20260816_1150_arch_inv_1_trace.md`
> - **DR-AB**: `docs/knowledge_tranfer/20260816_1156_arb_decision_record_A_B.md` (§§1–2 ARB decisions; §§3–6 PROPOSED)
> - **AMS**: `docs/knowledge_tranfer/20260816_1332_authority_model_business_semantics.md`
> - **V3**: `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` (AI-generated reconciliation, REV 1 of 11:36 carrying ARB rulings marked as such; "approvable as a proposal, not approved as target architecture")
> - **CF**: `docs/knowledge_tranfer/20260816_0905_architecture_conformance.md` (AI-generated, PROPOSED; adjudicates a conversation input; written against v3.0, **before** REV 1)
> - **AB**: `docs/knowledge_tranfer/20260816_0922_aggregate_boundaries.md` (AI-generated, PROPOSED; adjudicates a conversation input with an unverified external citation)
> - **IM**: `docs/knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` (AI-generated, PROPOSED; external citations unverified)
> - **IA**: `docs/knowledge_tranfer/20260816_1029_invariant_allocation.md` (AI-generated, PROPOSED; external citations unverified)
> - **RS, SC, SD, EV**: see Chapter 2
> - **GKC, Meta-Model, Synth**: the RQ-002 files (Chapter 0)
> - **Background notes** used as evidence: B-05, B-11, B-12 (`01-basis/`) and KT-8 (`01-basis/B-10-knowledge-transfer/`)
>
> **Standing of all knowledge-transfer sources:** the whole track lies in the exploratory "Track B" lane [RS L22]. Rulings recorded in it are *ruled, Track B*, never adopted architecture. *(Audit H-08, E-01, E-06.)*

---

## 1.1 Knowledge does not execute

**The idea.** A clear line separates *knowledge* from *capabilities*:

> **Knowledge does not execute. Capabilities execute. Knowledge constrains and informs.**

EP01 cites this as invariant "I-11" of the KnowledgeOS Relationship Ontology (2026-08-02) [EP01 L34–36]. **Cited-only:** the Relationship Ontology is a corpus document I have not read.

⚠ **Name collision.** This "I-11" is *not* the I-11 of Chapter 0 (the telos, "knowledge exists only to satisfy needs"). Two documents give the same identifier to different invariants. Below I write **I-11(GKC)** for the telos and **I-11(RO)** for "knowledge does not execute".

**What follows from it (Src, applied in EP01 L38–55).** A simple test sorts any element of the system:

| Question | Placement |
|---|---|
| Does it execute? | a **capability** (something that acts) |
| Does it only constrain or inform? | **knowledge** |
| Is it the internal structure of something that executes? | **implementation**, below the capability |

Applying the test splits "the knowledge model" into two declarative parts and moves analysis machinery out of knowledge altogether [EP01 L50–55]:
- **Ontology**: which kinds of thing exist (claim, evidence, rule, decision, exception, assessment, verdict). It changes only when a concept is admitted or retired.
- **Normative semantics**: what a rule *means*: applicability, scope, effect, time, authority [EP01 L52]. I add subject and exceptions from RM (Ch. 2 §2.2) *(audit R-10)*. It changes when a field's meaning changes, and such a change breaks every instance and validator.
- **Analysis** (conflict detection, satisfiability, classification) *computes*, so it is a capability, not knowledge.

EP01 notes that the repository already keeps the first two apart as two schema files with separate version numbers [EP01 L92–94]. **Src.** The schema directory was later read (pilot 2): `knowledge-types.yaml` (31 types) and `knowledge-schema.yaml` exist as separate files, each with its own `version` key (**Ind**, audit E-07).

> **Test cases now exist (audit C-07; see Ch. 2 §2.8.7, §2.9).** Two running configurations change behaviour by their mere content:
> - `design-rules.json`: thresholds and allow-lists read as rules;
> - `config/app.php`: a fallback value that sets the environment.
>
> On I-11(RO) they are **inputs read by capabilities**: the check script and the framework execute; the files do not. So "knowledge does not execute" survives, **provided "knowledge" includes configuration that a capability reads.** The cost: the line between *knowledge* and *capability input* is defined by **who executes**, not by file type. **Ded; open whether the corpus draws the line the same way.**

**Connection to Chapter 0 (Ded).** This is the same separation Chapter 0 found between knowledge and authority (§0.6), one level lower:
- knowledge does not *decide* (authority is a human act);
- knowledge does not *act* (execution is a capability).

Together they say that **a body of knowledge is inert.** It becomes effective only when a person decides on it, or a capability reads it. This is a strong, testable architectural claim. *Counterexample to look for:* a knowledge item whose mere presence changes system behaviour, such as a configuration value that is read as a rule. Whether that is "knowledge" or "a capability's input" must then be decided, and the line would need sharpening. **Open.**

---

## 1.2 Authority is two things: provenance and standing

**The idea.** What the repository's knowledge schema records in a single field called *authority* is really two independent properties [EP01 L246–252]:

| | Provenance | Standing |
|---|---|---|
| Meaning | where the claim came from | what the organisation currently treats it as |
| Values (as cited) | generated · derived | authoritative · provisional · historical |
| Can it change? | **never**: origin is a historical fact | **only by a governance act** |

**Two senses of "standing" (Ded; added to resolve a double use in this chapter).**
- **De jure standing** is what valid acts have made of a claim, whether or not they are recorded.
- **Recognized standing** is what the organisation's records show.

The table above describes recognized standing ("what the organisation currently treats it as"). §1.3 and §1.13.1 speak of what a valid act **confers**, which is de jure standing. The two coincide when every valid act is recorded. They diverge for an act that is real but not yet recorded, and for a recorded act that was not valid. Where the chapter says "standing" without qualification, it means **recognized standing** unless the context is the conferral by an act.

Hence the rule: **"provenance cannot have a state machine; standing changes by governance acts."** EP01 credits this to a "Lifecycle Gap Analysis" (cited-only). EP01 recommends splitting the field before designing anything that manages authority [EP01 L256–262, D-D L275].

A companion rule [EP01 L167, N-2]: **no lifecycle may contain a direct edge from *created* to *authoritative*.** Something must happen in between, namely a recorded act.

**Verification against Chapter 0 (Ind; corrected after audit H-01).** Three schemes use these words differently. Laid side by side:

| Concept | GKC (07-11) | Governed schema (EKP) | This book |
|---|---|---|---|
| where it came from | **provenance**, a separate claim component [GKC L19] | mixed into the `authority` field: *derived · generated* | **provenance** (immutable) |
| what the organisation treats it as | partly inside *governance status*: **canonical**, superseded [GKC L38] | mixed into `authority`: *authoritative · provisional · historical* | **standing** (changed only by acts) |
| where it is in its life | *governance status* is defined as "**lifecycle position**" [GKC L38]: draft, archived … | `status`, 8 values, declared "**INDEPENDENT of authority**" [`statuses.yaml` L3; `authorities.yaml` L4] | **lifecycle phase** |

**What this shows (Ind):**
- **GKC kept provenance separate but fused standing with lifecycle.** "Canonical" is a standing; "draft" and "archived" are lifecycle positions, and all are values of one GKC dimension.
- **The schema separated lifecycle from authority, but fused provenance with standing** inside `authority`.
- **Neither model has all three apart.** EP01's D-D (split provenance from standing), applied on top of the schema's existing lifecycle split, would give the three separate concepts this book uses.

My earlier claim that "the foundations already had it, and the schema field was a regression" was **wrong** and is withdrawn. The schema regressed on one split (provenance ↔ standing) while improving on another (lifecycle ↔ standing).

The five-value `authority` field (authoritative · derived · generated · historical · provisional) is verified in `docs/knowledge/schema/authorities.yaml`. **Ind.**

**Provenance must name the act (Src, EP01 L166, "N-1").** The rules say AI output enters as "generated", but "nothing records *which* AI act produced *which* claim". Provenance therefore needs an **authored-by** component: the producing act, not just the producing kind. Without it, the producer ≠ reviewer rule cannot be checked. *Added in Review A.* EP01's own limits section shows the need: its author examined a document it had itself written, a producer ≠ reviewer violation it declares openly [EP01 L291].

**Revision of Chapter 0 §0.6 (recorded in Chapter 0).** §0.6 formalised authority as "transitions of governance status are acts". With the split this becomes precise:
1. A claim has **provenance** (immutable, set at creation) and **standing** (mutable, only by acts).
2. **Every standing transition requires a recorded act.** In particular *created → authoritative* is never direct (N-2).
3. A mechanism may *report* that an act's preconditions hold; it never performs the act.
4. **Provenance never confers standing.** An AI-generated claim does not become authoritative by being detailed, repeated or correct. EP01 states this of itself: it "has no authority and acquires none by being detailed" [EP01 L8, L313].

Point 4 is the formal version of "knowledge feeds authority, never holds it".

**Development (Ded).** Treat standing as a small state space, Σ = {provisional, authoritative, historical, …}, and acts as labelled transitions. Then:
- "no creation→authority edge" means the initial state is never *authoritative*;
- "standing changes only by acts" means every transition carries an act label;
- "provenance cannot have a state machine" means provenance is a *constant* attached at creation, not a state.

This makes authority **checkable**: a claim's history is valid iff it starts in a non-authoritative state and every change is labelled by a recorded act. That check is mechanical, while the acts themselves stay human.

---

## 1.3 Grading claims: evidence strength is not standing

**The idea.** EP01 grades every statement with one of five labels, OBSERVED · INFERRED · HYPOTHESIZED · PROPOSED · ADOPTED, and rules that **only OBSERVED and ADOPTED bind** [EP01 L12].

**Analysis (Ded).** These five labels mix **two different dimensions**, the same kind of conflation EP01 itself condemns in the authority field (§1.2):
- **OBSERVED, INFERRED, HYPOTHESIZED** grade *how strong the evidence* for a statement is: seen, deduced, conjectured.
- **PROPOSED, ADOPTED** grade the *standing* a statement has by governance act.

A statement can be OBSERVED **and** PROPOSED, for example an observed fact put forward as the basis of a decision not yet taken. A single label cannot express that.

Once the two dimensions are separated, EP01's word "bind" turns out to mean two different things, and a third notion must be kept apart from both (Ded):
- **Epistemic support.** An **observed** statement may be **relied on as true** in reasoning, within the coverage of the observation. That is a property of its evidence.
- **Governance standing.** An **adopted** statement **counts as decided** by the organisation. That is a property conferred by a valid act (§1.6), never by evidence.
- **Normative effect.** Only a statement with standing **as a norm** (a rule, a decision about what must or may be done) changes what anyone **must or may do**.

So an observed fact, however well established, **obliges nobody and decides nothing**. "The test suite fails" is observed. It becomes a reason for a decision, but it is not a decision, and no one is bound to act on it until an authorized act adopts a rule or decision. Conversely, an adopted decision does not become **true** by being adopted. It counts, and it may still be ill-founded (§1.17.2). How inferences fare depends on their kind (Ded):
- A **valid deduction** inherits the epistemic support of its premises: it may be relied on as far as they may, and no further. This is what the book's mark **Ded** claims.
- An **inductive or abductive inference**, and a **hypothesis**, has weaker support than its evidence and stays open to revision.

None of them gains **standing** from its support; standing comes only from adoption.

EP01's intended claim survives in this form: **only observed statements may be relied on as facts, and only adopted statements count as decided.** *(Corrected: the earlier wording, "facts bind because they are facts", let evidence alone confer standing.)*

**Consequence for the theory (Ded).** KnowledgeOS needs **at least three orthogonal labels on a claim**:
1. **provenance** (who or what produced it);
2. **evidential grade** (observed / inferred / hypothesized / unknown);
3. **standing** (provisional / adopted / historical …).

GKC had provenance and status; the August documents add the evidential grade without separating it from standing. The manuscript's own marks (Src/Ind/Int/Ded/Hyp) are an evidential grade of this kind. **Status: Ded; the corpus may already make this distinction (Open).**

---

## 1.4 Detail does not confer authority; questions need closing criteria

Two further principles from EP01, both about how knowledge work goes wrong:

1. **The "fourth ontology" risk** [EP01 L16–20]. A thorough analysis tends to become an unofficial authority simply by being detailed. The guards are grading every claim and deciding structure only by an existing, cited rule, never by preference. This is the social counterpart of §1.2 point 4. **Src.**
2. **Open questions need activation criteria** [EP01 L222–234]. An open question with no stated condition for closing it "cannot close except by argument". The register *with* criteria moved 6 entries in two days; the one without closed 1 of 38. **Src** for the counts (as reported by EP01, not checked by me).

**Development (Int).** Point 2 completes the need-driven model of Chapter 0 §0.7:
- an unmet **need** is a gap;
- an open **question** is a gap *plus a statement of what evidence would close it*.

A gap without such a criterion is not yet actionable knowledge. This suggests a refinement of KnowledgeNeed: the "unsatisfiable" state should carry the *criterion that would make it satisfiable*. **Hyp** for the corpus.

---

## 1.5 Knowledge is not its carrier

**Source added.** **GA**: `docs/knowledge_tranfer/20260816_1108_architecture_gap_analysis_knowledgeos.md` (AI-generated, PROPOSED; one sentence amended by the ARB).

**The idea.** "Engineering knowledge ≠ engineering artifact. One is the thing; the other is a carrier of it." GA cites this as the stated purpose of the KnowledgeOS Meta-Model [GA L147–151] (**cited-only**). It names an error class this distinction makes "impossible to phrase": **reading the absence of an artifact as the absence of the thing**. GA counts five recorded instances, four in the KnowledgeOS documents themselves, e.g. "there is no lifecycle" when lifecycles existed without a document describing them [GA L153].

**Verification (Ind).** This is GKC's founding move, stated sharper:
- a knowledge coordinate exists "independently of any artifact" [GKC L18];
- representation is one dimension among four [GKC L13].

GA applies it to dissolve a question that blocked a whole document set ("is the PKS software or specifications?", 49 references). That question asks which *representation* something has and treats the answer as what it *is*. GA shows knowledge carried at once in YAML (rules, schemas, statuses), Markdown (constitutions, models) and JSONL (evidence streams) [GA L157–169]. GA lists PHP as *capabilities acting on knowledge* and shell/JS as *runtime adapters*. Under I-11(RO) those **execute**, so they are capabilities, not knowledge carriers. *(Corrected after audit H-06; my earlier "the same knowledge … in PHP and shell" contradicted §1.1.)* The rows are **Src** (GA's observation). **The dissolution is valid (Ded):** if representation is an independent dimension, no answer about representation can settle a question about nature.

**Development (Ded).** The principle has a direct consequence for *empty answers*, the theme of Chapter 0 §0.7(b). "No artifact found" can mean at least three different things:
1. the knowledge does not exist;
2. it exists but has no carrier yet (tacit, or unwritten);
3. it exists in a carrier the search did not reach.

Only (1) is an absence of knowledge. Treating (2) or (3) as (1) is exactly GA's error class. **This sharpens the Zero/Gap hypothesis:** a theory of "zero" for KnowledgeOS must at least distinguish *no knowledge* from *no carrier* from *not found*. **Hyp** for the corpus; the logical distinction itself is **Ded**.

---

## 1.6 Nothing but an act confers authority

**The idea.** GA collects a family of statements of the same form [GA L424]:
- relevance ≠ authority;
- activity ≠ authorization ("active ≠ authorized");
- ownership ≠ authority;
- existence ≠ permission.

GA lists this family as items to promote [GA L424]. Separately, it calls "the mechanism records authority; it never grants it" the strongest convergence in the repository [GA L294] (attribution corrected, audit R-08). It was derived twice: compiled into running code as a verdict that a mechanical check may not emit, and stated independently in a document that never read that code [GA L288–296]. **Src** (the code comment is quoted by GA; I have not opened `Verdict.php`).

**Development (Ded).** These are not separate rules. They are **one theorem** under the model of §1.2:

> Standing changes only by recorded acts (§1.2). Relevance, activity, ownership, existence, detail, repetition and correctness are properties a claim can have *without any act occurring*. Therefore **none of them can change standing.**

Each "X ≠ authority" is an instance of this theorem for one property X. The list is open-ended: the theorem predicts further instances, such as "popularity ≠ authority" and "age ≠ authority", without new rules. A *counterexample* would be a property that by its definition includes a recorded act, e.g. "ratified". Such a property is not a counterexample but the act itself.

**Consequence.** The theory needs only **one authority axiom** (standing changes only by recorded acts), not a growing list of prohibitions.

> **Refined (Chapter 2 §2.8.2; restated after audit L-05, L-06, E-03).**
> 1. **Domain.** The axiom is about **normative status**: either the *standing of a claim* or an *actor's permission to act*. "Relevance ≠ authority" is an instance for claims. "Active ≠ authorized", "existence ≠ permission" and "ownership ≠ authority" are instances for actors. They need the companion premise "permission exists only through a grant that references an act" (§1.13). Because the theorem follows from the axiom by definition, its "further instances" (popularity, age) are **consequences, not predictions**; they test nothing.
> 2. **Conditions on a valid act:** the act is recorded (strictly, the condition for the organisation to **recognize** the resulting standing change; the act's existence does not depend on its record, §1.13.1), **and** the subject scope of what it changes lies within the actor's **grant scope**, **and** the act falls within the grant's **validity period**. Authority = act × scope × time.
> 3. **Open:** grant scope and canon scope (Ch. 0 §0.3) live in different value spaces. Containment between them is undefined until a mapping exists.
> 4. **Standing:** the ARB calls the containment chain a "**candidate relationship … not an adopted universal law**" [SD L317]. The scope and time conditions are therefore **Ded**, not ruled.

---

## 1.7 Visibility: derivation may narrow, never widen

**The idea.** GA reports one gap that *neither* document set had seen: nothing governs **who may read what** [GA L257–263]. The 16 August work proposed the rule "**derivation may narrow visibility but must never widen it**" [GA L261]. **Src** that the gap exists, per GA's search, which I have not repeated.

**Development (Ded).** Let vis(c) be the set of principals allowed to read claim c. For a claim d derived from sources s₁…sₖ, the rule says:

> vis(d) ⊆ vis(s₁) ∩ … ∩ vis(sₖ)

That is, a derived claim is never more visible than its least visible source. Three consequences:
1. **Summaries and AI contexts inherit restrictions.** An assembled working context (Chapter 0 §0.7) may contain only claims every recipient may read.
2. **Visibility is a fourth label** next to provenance, evidential grade and standing (§1.3). It is orthogonal: a claim can be authoritative and restricted, or public and provisional.
3. **A consequence for quoting** (related to "never a copy", Chapter 0 §0.8; not a conflict, audit L-23). Legitimate *declassification*, e.g. publishing an aggregate, must be possible, so the rule reads: vis(d) ⊆ ∩ vis(sᵢ) **unless widened by a recorded act**. when a restricted claim is quoted into a broader document, the quote is a derivation and must keep the restriction. Most document systems silently widen.

This rule is directly relevant to *this* research programme. The sealed and hold-out rules that currently block corpus reading are visibility rules in exactly this sense.

**Status.** The rule is **Src** as a proposal. Its formalization and consequences are **Ded**. Whether KnowledgeOS should govern read access at all is **Open** (an ARB question per GA L470).

---

## 1.8 What KnowledgeOS is: a generator, and an untested loop

**Identity (revises Chapter 0 §0.2).** GA cites a five-tier model from the KnowledgeOS architecture baseline of 2026-08-02 [GA L67–80] (**cited-only**):

| Tier | Element |
|---|---|
| T1 | KnowledgeOS, the reusable engineering platform |
| T2 | KnowledgeOS Services |
| T3 | one Product Knowledge Space per product |
| T4 | the business product |
| T5 | running software |

The relation is **generative**: *"KnowledgeOS → creates → PKS → guides → Product. A compiler is not a program; it produces one."* GA also cites an adopted decision (AIP-14) that KnowledgeOS is a *supporting* subdomain of the election product, and that the multi-organisation platform is a *vision* with its gate shut and no market data [GA L88–93].

**Analysis (Int → Ded).** This resolves Chapter 0's layered reading more precisely. KnowledgeOS is **not a knowledge base**. It is the thing that **produces and governs knowledge spaces**, as a compiler produces programs. Hence:
- the *theory* of KnowledgeOS is a theory of how knowledge spaces are generated, governed and used;
- a product knowledge space is an **instance**;
- confusing the two (one tier for another) is what GA calls the subject error [GA L84, L110].

Chapter 0 §0.2's four meanings now align with tiers:
- theory and platform → T1/T2;
- product knowledge → T3;
- the product hypothesis → the gated vision.

**Open:** whether the corpus states the generative relation formally.

**When structure is justified (Src, GA L401, "LA-4").** "A responsibility becomes a component only at n ≥ 2, exercised by another." This is a concrete threshold for the parsimony principle of Chapter 0 §0.8 (i): one occurrence is an observation; a second, independent use justifies structure. *Added in Review A.*

**The loop has never closed (Src, GA L376–378).** The evidence layer (observation → recommendation → decision → outcome → assessment) has fed back into governance or capability **zero times**. Chapter 0 §0.7 called need → evaluation → gap → improvement a feedback theory of knowledge growth. **It is designed and partly built, but has never once operated end-to-end.** This strengthens Chapter 0's weak point 3 (§0.8) with a measured count.

**The repository as evidence for its own theory (Int).** GA's main empirical finding is that the August work re-derived, in one day, an ontology that already existed three times, plus several mechanisms already running, because nobody searched first [GA L438–455]. The ARB amended the conclusion: retrieval failure caused duplication, *and* real gaps exist [GA L492–496]. This is a natural experiment confirming the foundations' **scent principle**: knowledge that cannot be found is functionally absent [Synth L75]. It also shows the cost: duplicated, mutually unaware ontologies. **The KnowledgeOS programme suffered the exact failure KnowledgeOS is meant to prevent.**

**Lead for the corpus (cited-only).** GA reports the KnowledgeOS corpus has its own meta-model: **6 dimensions (2 new, 1 rejected on test), 13 concepts, 11 invariants** [GA L49–50]. GKC has 4 dimensions. Which dimensions were added or rejected, and why, is a primary question for the corpus chapters.

---

## 1.9 How a principle becomes real: the realization ladder

**Source added.** **TR**: `docs/knowledge_tranfer/20260816_1150_arch_inv_1_trace.md` (PROPOSED, an ARB input).

**The idea.** TR follows one principle, *knowledge feeds authority; it never holds it*, through five stages [TR L58–68]:

| Stage | Form of the principle |
|---|---|
| 1 | **stated** as a principle by its owner |
| 2 | made a **boundary contract**: only a closed vocabulary of verdicts may cross |
| 3 | **typed**: a verdict type with seven values, of which only four may be emitted by a mechanism |
| 4 | **enforced**: constructing an assessment with a reserved verdict throws an error |
| 5 | **independently verified**: tests assert that the reserved verdicts cannot be emitted |

TR reports that this is **the only one of 20 contracts in the repository that completes all five stages**. The other nineteen stop at "checked", "conventioned" or "reminded" [TR L70–72]. **Src.**

**Independent check (Ind).** I read the code:
- the assessment constructor rejects any verdict outside the emittable set;
- it also rejects an assessment with empty evidence, "An assessment must state its evidence";
- the verdict type has exactly seven cases;
- the tests named in TR exist.

TR's paths are **abbreviated**; the tests sit under `Capabilities/IdentifierIntegrity/Tests/`. TR's line for the throw (`Assessment.php:38`) is off by one; it is at L39. The content is as described. *(My earlier "TR's path is wrong" is withdrawn, audit R-05.)*

**Development (Ded): the ladder is a sequence of representation transitions.** In the language of Chapter 0, the principle is one claim of *Constraint* nature. Stages 1→5 move it along the **representation** dimension, from recorded prose to executable form, while its **nature stays the same**. This is exactly the deliberate, nature-preserving transition of invariant I-10 [GKC L65]. The ladder therefore *measures* how far a constraint has travelled toward the representation the foundations trust most (PK-P4, execution proximity).

This yields a practical, testable notion:

> **Realization level of a rule** = the highest stage it has reached: stated < contract < typed < enforced < verified.

The foundations predict that the level should correlate with how reliably the rule holds in practice. *Falsification:* rules at "verified" violated as often as rules at "stated". **Hyp**, measurable on this repository.

**Development (Ded): the verdict split confirms §1.3 in code.** The seven verdicts divide into two kinds:
- **mechanical** (PASS, FAIL, WARN, INCONCLUSIVE): statements about evidence;
- **reserved to human acts** (PASS AFTER CORRECTION, EMERGENT, CERTIFIED): statements about standing.

This is **consistent with** §1.3's separation of evidential grade from standing, but the code's own criterion is broader. It reserves the three values because they "belong to review and certification acts" (`Verdict.php` L15–17): *who performs the act*. PASS AFTER CORRECTION and EMERGENT are review outcomes about evidence and novelty, not standings. "Confers standing" is a *sufficient* reason to reserve a value, not the code's actual rule. **Int**, corrected from "exactly implemented" (audit L-20).

A second service in the repository (the observation runtime's assessment) emits SUPPORTED / PARTIALLY_SUPPORTED / NOT_SUPPORTED / INCONCLUSIVE, all by machine and with nothing reserved [TR L109–111]. Under §1.3 this is **not** a violation: all four are evidential grades ("did the recommendation measurably help?"), not standing. **The theory gives a criterion for which vocabularies may be fully mechanical: those that grade evidence, never those that confer standing.** (It is a sufficient criterion; the code reserves more, by who performs the act.) This is the criterion TR's question needed (is this the same concept, a different concept, or a supersession?). **Ded**; TR itself only *infers* "different concepts" [TR L113].

**Correction of an earlier source (Src, TR L89–99).** GA called the two verdict vocabularies a HIGH-severity *collision* [GA L281, L468]. TR, which read both code files, found they **never meet**: no shared type, import or guard. The overlap is in vocabulary only. So "collision" was an overstatement; TR's reading supersedes it.

**Ownership gap (Src, TR L132).** The repository's only fully realized invariant lives in code (`scripts/`) that has no registered owner. Realization (this section) and ownership (Chapter 2 §2.8, "who maintains") are independent; here one is complete and the other absent. *Added in Review A.*

**Ruled by the ARB, Track B (update, 2026-10-18, from DR-AB; standing corrected after audit R-06, E-01).**
- **Decision B (ruled).** Verification outcomes and recommendation-effectiveness outcomes are different concepts that "must not be merged" [DR-AB L51]. The whole knowledge-transfer track lies in the exploratory lane [RS L22], so this ruling is **ruled, Track B**, not adopted architecture.
- **The generalization (PROPOSED, an Architecture derivation, not ruled; DR-AB L197).** **Concepts that answer different questions do not share a vocabulary** [DR-AB L124]. Applied to analyser findings and dispositions (Ch. 2 §2.4), it rules out "merging a machine finding with a human decision" [DR-AB L126–133].

My theoretical criterion above (mechanical vocabularies may grade evidence, never confer standing) and the ARB's rule (different question → different concept) are **independent and agree**. Mine says *which* vocabularies may be mechanical; theirs says vocabularies must not be merged.

DR-AB also asks an open question: **should any effectiveness outcome be reserved to a human act?** [DR-AB L141]. My criterion answers: **only if an outcome changes standing.** "This recommendation helped" grades evidence and may be mechanical. "This rule is therefore promoted" changes standing and must be an act. **Ded.**

**The ladder as the pattern (update; standing and reasoning corrected after audit R-07, E-01).** DR-AB reports an ARB instruction that the five-stage chain becomes *the pattern* for future rules. The report sits in DR-AB's PROPOSED §6, inside the Track-B lane [DR-AB L161–181, L197]. The stages are worded slightly differently from TR's: *business principle → governed definition (written once, with an owner) → domain model (a type, not prose) → mechanical enforcement → independent verification*. This version makes **ownership** and **single definition** explicit stages.

**Where I disagree with the source.** DR-AB calls the traced rule the **existence proof** that completes this chain [DR-AB L177]. TR names a *semantic owner* for the governed definition (AD-1, TR L59) but records **no owner for the code** that enforces it (TR L132). So stage 2 ("governed definition with an owner") is met for the *definition*, and the missing owner is for the *mechanism*. Read that way, the rule completes the chain as stated, and my earlier "not finished" was too strong. The pattern says nothing about owning the enforcing code, which is a gap in the pattern, not in the rule. **Ded.**

**Same word, different concept.** "INCONCLUSIVE" appears in both vocabularies with no shared type [TR L99]. Consistent with the foundations' definitional rule: a term's meaning is fixed inside one context [RQ-002 Taxonomy, Definitional type]. **Src.**

---

## 1.10 Identity of knowledge items needs governed names

**The idea.** TR found that the identifier of the principle it traced, "AP-1", **already means something unrelated** in another register (a finding about a leaked business rule). Neither series is governed [TR L78–87].

**Corroboration (Ind).** I found the same defect independently in this pilot: two documents give "I-11" to different invariants (§1.1). Two instances in two unrelated places is not a statistic, but it shows the defect is not isolated.

**Development (Ded).** The foundations make a claim the atomic unit of knowledge (Chapter 0 §0.3) but say nothing about **how a claim is identified**. Without unique identity:
- supersession links (I-7) can point at the wrong claim;
- provenance (I-3) can be attached to the wrong one;
- "one owner per invariant" cannot be checked.

The repository's identifier capability states the needed rule, "**an identifier denotes exactly one subject**" (background note B-05; CAP-001). It is a *precondition* of I-3 and I-7, not a separate concern.

**Proposed addition to the foundations (Ded).**

> **I-0 (identity).** Every claim has an identifier that denotes exactly one claim, within a governed namespace. I-3 (provenance) and I-7 (supersession) presuppose I-0.

*New evidence (DR-AB L147–155):* the ARB deferred identifier governance as "an ownership question wearing an identifier's clothes": a register needs an owner before it can be governed. Until then, the rule is cited **by its statement, not by its number**. This supports I-0 and links it to ownership (§1.11): **identity requires a governed namespace, and governance requires an owner.**

The evidence shows the failure is real (two collisions). It also shows the fix is not mechanical everywhere: CAP-001 checks only the registers it knows, and "AP" is not one of them [TR L85–87].

---

## 1.11 Ownership: method, binding and evidence

**Source added.** **DR-AB**: `docs/knowledge_tranfer/20260816_1156_arb_decision_record_A_B.md`. Its §§1–2 record **two ARB decisions** in the ARB's words (status RECORDED). Its §§3–6 are an AI derivation (status PROPOSED) [DR-AB L7–10, L197].

**The decision (Src, ruled, Track B: the whole knowledge-transfer track is in the exploratory lane, RS L22).** *"Common engineering knowledge and governance capabilities require explicit ownership. Product-specific mechanisms remain product-owned"* [DR-AB L32]. The occasion: many governance mechanisms had **no accountable owner**, including the one fully realized authority rule (§1.9) [DR-AB L43].

**The decomposition (Src, proposed derivation).** Ownership is not assigned mechanism by mechanism. It is assigned *inside* every mechanism by splitting it into three parts [DR-AB L76–82]:

| Part | What it is | Owner |
|---|---|---|
| **Method** | the reusable engineering rule: the *shape* of a check, the fail-closed rule, the trigger contract, the assessment pipeline | platform |
| **Binding** | the product-specific configuration: thresholds, conventions, the five recommendation rules | product |
| **Evidence** | what happened in this project: the observation streams | product |

DR-AB works this split on three running systems [DR-AB L86–90]. Its conclusion: "forty-five ownership assignments" become "one decomposition applied three times" [DR-AB L92].

**Why it matters (Ded).** The split lines up exactly with the generative tier model of §1.8:
- the platform (T1) owns **methods**;
- a product knowledge space (T3) holds that product's **bindings** and **evidence**.

So, **if the cited tier model holds** (cited-only, E-02), *"KnowledgeOS creates a PKS"* gets a concrete meaning: **a PKS is the set of the platform's methods instantiated with one product's bindings, plus the evidence those instances produce.** This is the compiler analogy made precise:
- method = the program's general logic;
- binding = its parameters;
- evidence = its run log.

The split also maps onto the knowledge model of Chapter 0:
- a method is a claim of *Constraint* nature with platform-wide canon scope;
- a binding is a *Constraint* claim with project canon scope;
- evidence is *Measurement/Observation* nature.

No new dimension is needed. The three parts are **regions of the existing space**, which supports the foundations' claim that specializations are weights, not new rules [GKC L84]. **Ded.**

**Ownership is a fourth property, distinct from authority (Ded).** Ownership answers *who must maintain this*. Standing (§1.2) answers *what does the organisation treat it as*. Grant scope (Ch. 2 §2.8.2) answers *who may change its standing*. The evidence shows they come apart:
- the realized authority rule had full standing and enforcement but **no owner**;
- authority holders are named, while maintainers often are not.

"Ownership ≠ authority" (§1.6) is therefore not only a prohibition but a statement that the theory needs **separate places** for the two.

**Test of the decomposition (Ded).** Is the split always possible? *Counterexample candidate:* a check whose rule makes sense only with this product's data, e.g. an anonymity check specific to elections. It has no reusable method; it is **all binding**. The decomposition survives if "method" may be empty. DR-AB's rule "product-specific mechanisms remain product-owned" [DR-AB L32] covers exactly this case. **Consistent.**

**Left open by the decision (Src, DR-AB L103–107):**
- whether two enforcement regimes that do not reference each other (one triggered by AI sessions, one by commits) are one platform or two;
- **which mechanism wins when two disagree**;
- identifier governance.

The second is the precedence question of Ch. 2 §2.8.4a, now between *enforcement mechanisms* rather than rules. By that section's criterion: if the two mechanisms decide one thing, an order is legitimate; if each enforces an independent obligation, a disagreement is a conflict to report, not to rank. **Ded; open as a decision.**

---

## 1.12 A record of an act is not the act

**The idea (Src, DR-AB L9).** DR-AB records the ARB's decisions and states its own limit: *"A report of an act is not the act: if a separate ratification ceremony is required, this record is its input, not its substitute."*

**Refinement of §1.2 (Ded).** §1.2 said every standing change requires a *recorded* act. DR-AB adds the converse caution: **a record does not make the act happen.** A machine can check only records, never acts. So:
- a standing history is *valid* if the acts occurred, which is a fact about the world;
- it is *verifiable* if every act has a record of sufficient integrity: who, when, under which grant, with what scope.

Verification can establish only the second. The first rests on trust in the recording process, e.g. that the record was written by the deciding person and not by an assistant on their behalf. This is the **residual trust assumption** of the whole authority model; the theory should state it, not hide it.

**Generalization (pilot 2, file 5; Ch. 2 §2.8.7).** The same holds for any **self-report**. A system's report of its own environment is a claim with provenance *self*, not a fact: the framework reports "production" on a developer's machine. A check's message "BLOCKED" was not a block when the exit code did not block. Records of acts, reports of enforcement and self-descriptions are all **claims about events, to be evidenced**, never the events themselves. **Ded.** It connects to §1.2's *authored-by* requirement: the record must name its producer.

---

## 1.13 Authority as a recorded reference to an external act

**Source added.** **AMS**: `docs/knowledge_tranfer/20260816_1332_authority_model_business_semantics.md`. It is Track B (exploratory; governs nothing). Its rulings AM-1…AM-5 are "approved inside Track B" [AMS L278–288]. Its counts come from the live authority record of 2026-08-16: 9 work items, 20 grants [AMS L19–28].

### 1.13.1 What authority is

**The idea (Src, AMS L38–51).** In the running system, authority is **not a property of a person or role**. As the system holds it, it is a **recorded relation**:

> *a human act happens outside the system → governance registers a grant that points at that act → a named activity becomes permitted within a stated scope.*

AMS's proposed definition: *"Authority is a recorded permission for a named activity within a stated scope, whose legitimacy derives entirely from a human act performed outside the system."*

**Authority and its record: two valid concepts (C1; the sources point both ways).** AMS defines authority as "a recorded permission … whose legitimacy derives entirely from a human act" [AMS L49] and adds that "it **exists only as a grant**, only for a scope, and only by reference to an act" [AMS L51]. IA, by contrast, notes that "a grant exists but was not recorded" is a real case [IA L99–101]. Two concepts follow; the sources do not decide between them:
- **C1-a, the record constitutes authority** (AMS L51). Authority is the grant record; where there is no record, there is no authority. *Consequences:* simple and fully auditable; "real but unrecorded authority" is impossible by definition; a missing record yields **no authority**.
- **C1-b, the record represents authority** (IA; and AMS's own clause that legitimacy "derives entirely from a human act"). Authority is a permission for a named activity within a scope, conferred by a valid human act. The grant record is the system's **representation** of it and the **evidence** for it. *Consequences:* closer to how organisations work; a missing record yields **not evidenced** ("cannot determine", §1.17.3, E4); a present record is evidence whose force rests on record integrity (§1.12; §1.13.2 point 5).

Under **both** concepts three things must be kept apart: the **human act**, the **grant record**, and the **evidence** of what the act covered. **Recognition** depends on the record under both: I-2 requires standing changes to be explicit, i.e. recorded (Ch. 0 §0.5). This is why the axiom's "the act is recorded" condition (§1.6, refined, point 2) is read as the condition of **recognized** standing. The formal model of §1.14.1 expresses both concepts (C1 and the citation parameter κ), and its business text states which answers change. **Which concept the organisation adopts is a decision for the Principal** (final review, C1).

**Why the mechanism never holds authority (Src, AMS L214–226).** The grant *contains no authority*. It contains a **pointer** to the act and a scope. "The mechanism never holds authority because it never contains any. It holds a reference to something it cannot create." Three properties make this work:
1. the act is external and immutable (a committed artifact);
2. the grant is a reference, never a copy;
3. only one role may register grants, and it may only register.

**Classification:** UPDATE of §1.2 and §1.12. **This is the clearest statement in the sources of *why* "knowledge feeds authority, never holds it" is achievable.** The theory's authority axiom (§1.2, §1.6) says standing changes only by recorded acts. AMS shows the **implementation pattern**: the record stores a *reference* to the act, never the act's content as its own. "Never a copy" (Chapter 0 §0.8 (ii)) here protects authority, not just knowledge. **Ded.**

### 1.13.2 What the record shows: independent check

AMS reported (2026-08-16): 20/20 grants register-by-governance; 20/20 carry a human-act reference; 13/20 cite an immutable commit; 0/20 have validity, delegation or owner fields; status always AUTHORIZED; "two actors, eight spellings" [AMS L57–183].

**I recounted the live record today** (`.claude/runtime/workflow/*.json`, 22 files; read-only). **Ind:**

| Property | AMS 08-16 (n = 20) | Today (144 records = 142 grants + 2 ending records; 18 work items) |
|---|---|---|
| registered only by governance | 20/20 | **144/144 records** (a census of field presence) |
| carries a human-act reference | 20/20 | **144/144 records** (field present; whether each referenced act exists or covers the scope was **not** checked) |
| validity / delegation / owner fields | 0/20 | **0/144** each |
| statuses | AUTHORIZED only | AUTHORIZED 142 · **REVOKED 1 · CONSUMED 1** |
| reference cites a commit or hash | 13/20 (65%) | 32/144 (22%) by my pattern match; **the method is crude** (a reference may name a committed artifact without a hash) |
| distinct "authority" spellings | 8 | **107** |

**Limits of the check.** The records are **not under version control** (`.gitignore` excludes `.claude/runtime/`). So I could not reconstruct the 08-16 state, and **AMS's original counts cannot be independently re-verified**, only the current state.

**What the recount adds (Ded):**
1. **The core invariant holds in the whole current record (corrected after audit L-18).** All 144 records name governance as registrar and carry a human-act reference. This is a **census of the current record, not a sample**, so a statistical bound adds nothing. My earlier "rule of three" bound is withdrawn, for three reasons:
   - the registering tool writes the field, so presence is partly structural, not an independent trial;
   - the 144 very likely include AMS's 20, so this is not an independent replication "at 7×";
   - only the *presence* of the reference was checked, not that the act exists or covers the scope.

   The finding stays support for the pattern of §1.13.1, at census strength.
2. **Endings now exist, as new records.** A revocation and a consumption are each recorded as a *new* entry referring to the earlier grant, not as an edit of it. This is AM-1 (authority must be able to end) realised in the **append-only** style of I-7 (supersession is a link, never an edit).
3. **A new kind of ending appears in practice: CONSUMED.** The grant was used up by delivering its work. AM-1 lists *starts · expires · revoked · suspended · superseded* [AMS L284]; **consumption is not on that list.** It is a real, distinct business situation: a **single-use authority**, exhausted by use, not by time or by withdrawal. **NEW IDEA (from data).**
4. **The holder field got worse.** 107 spellings in 144 grants. Holder and "how the act arrived" are still mixed in one free-text field [AMS L70–74], so "which grants did this authority issue?" is even less answerable than AMS reported.
5. **Record integrity is the weak point.** AM-3 requires the *act* to be immutably addressed. But the *grant records themselves* are untracked files: a grant can be edited or deleted without trace. By §1.12, the **verifiability of authority rests on record integrity**, and here the records are less protected than the acts they point to. **The theory requires grant records to be at least as immutable as the acts they reference.** **Ded; a concrete, closable gap.**

### 1.13.3 Ending, delegation and linkage (Src, ruled AM-1…AM-5)

- **AM-1. Authority must be able to end; "the end is not one event."** Starts, expiry, revocation, suspension and supersession are different situations [AMS L284]. *Consumption* is a further situation, from the data above.
- **AM-2. Delegated and independent authority must be distinguished.** Today delegation exists only as "re-granting with a narrower scope", with no link between grants [AMS L92–96]. **Not every narrower grant is a delegation** [AMS L285].
- **AM-3. Every grant must reference an immutable human act.** This splits into the **evidence principle** (a source act is always named; 20/20) and the **addressing improvement** (the reference resolves to a fixed artifact) [AMS L286].
- **AM-4. Ownership and authority are separate.** "Who do I contact?" ≠ "who may approve a change?" [AMS L183–194, L287]. This confirms §1.11 with data: 0 grants carry an owner.
- **AM-5. Authority must be linkable to the rule it authorizes.** Today **no grant references a rule and no rule references a grant** [AMS L200–206]. *What exactly* is authorized (one change, a class of changes, an activity) is left **open** [AMS L288].

**Development (Ded).**
- **Delegation is a derivation relation, not containment.** That containment (child grant scope ⊆ parent grant scope) is **not sufficient** for delegation is the source's own refinement of AM-2: "independent authorization must remain possible" [AMS L285] (**Src**; corrected after audit R-11). My addition (**Ded**): delegation therefore needs an explicit **derivation edge** child → parent. Conditions and consequences, extended after audit L-19:
  - (a) scope containment holds **transitively** along derivation edges, and a delegated grant's validity lies within its parent's;
  - (b) the parent must carry a **right to delegate**; otherwise any holder could sub-delegate;
  - (c) with joint authorizations, a grant may have several parents. (The recorded "PO/ARB" spellings are **not** an example: they are one holder with a description of how the act arrived [AMS L61–72]; joint authorization is a conceptual case.) so the structure is a **directed acyclic graph**, a forest only if single-parent derivation is ruled;
  - (d) **open policy question:** does ending the parent end the child? If legitimacy derives from the parent's act, the theory says *yes, unless independently re-authorized*. The sources do not rule on this.
- **"Authority of a rule" ≠ "authority to change a rule"** [AMS L265–274]. The first is the act that *established* the rule (its standing). The second is a **grant scope covering future acts on it**. Today the first sits in prose headers and the second is "whoever can edit the file". This is precisely where RM-3 (Ch. 2 §2.11) must attach.
- **Subsystems differ in maturity** [AMS L313–318]. The governance engine writes live records; the observation engine has been idle since 5 August. "The platform" is not one operational state. This supports §1.8's finding that the feedback loop has never closed.

---

## 1.14 State is not authority; roles; the execution plane

**Source added.** **V3**: `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md`, REV 1. It is AI-generated, PROPOSED and "approvable as a proposal, not as target architecture". It records ARB rulings marked ⭐, inside the Track-B lane [V3 L1–16]. Its declared subject is **T3**, one product's knowledge space [V3 L12, L33].

### 1.14.1 STATE ≠ AUTHORITY

**The idea (Src, ARB REV 1; V3 L309–323).**
> Active ≠ Authorized · Approved ≠ Currently authoritative · Exists ≠ Permitted

The new member is **"Approved ≠ currently authoritative"**. A rule can be *Active* on its rule path (RM-3 lifecycle, glossary) yet not currently authoritative, because the authority behind it has lapsed, been revoked or been superseded. "A single status field cannot say that." V3 leaves open whether this needs a second field or "a governed authority lifecycle" (**OQ-11**) [V3 L469].

**Classification:** REFINEMENT of §1.2 and §1.6.

**The lifecycle model it extends (Src, ARB REV 1; V3 L297–307).** V3 carries forward a **three-class structure**, *pre-authority → authoritative → terminal*, and adopts per-type chains as "instances of the 3-class structure, not a replacement":
- Decision: Proposed → Reviewed → Approved → Implemented → Superseded → Retired;
- Rule: Draft → Validated → Active → Suspended → Retired;
- Evidence: Collected → Normalized → Verified → Invalidated.

It also carries forward "3 identity modes", which V3 does not define here (**cited-only, open**). Of the STATE ≠ AUTHORITY family, V3 says that **"Approved ≠ currently authoritative"** is "the one the per-type lifecycles above cannot express" [V3 L317].

*Analysis (Ded).* Sorted by the axes this book keeps apart, the chains mix at least two of them clearly, and several states are **ambiguous** because V3 gives only their names:

| State | Axis it actually belongs to |
|---|---|
| Proposed, Draft | **standing**, pre-authority class |
| Reviewed, Validated | **ambiguous**: pre-authority standing, or a verification event (evidential grade). The names do not decide it |
| Approved, Active | **standing**, authoritative class (subject to §1.14.1's derivation) |
| Superseded, Retired | **standing**, terminal class |
| **Implemented** | **realization** (§1.9): a decision does not become more authoritative by being built |
| **Collected, Normalized, Verified, Invalidated** (the whole Evidence chain) | **evidential grade** and processing (§1.3), not standing: verified evidence is not thereby authoritative, and invalidated evidence is refuted, not "retired" |
| **Suspended** | **unclear**: see finding 2 |

Two findings follow:
1. The chains repeat the fusion this book found in GKC's governance status (§1.2 mapping table): **Implemented** is realization and the **Evidence chain** is evidential grade, beside standing states in the same sequences. Not all their states are "instances" of the three classes; the standing states are, and some states are ambiguous.
2. **Suspension is not placed by the three classes**. V3's Rule chain has no Suspended → Active edge, so "it can resume" was my assumption, and V3 does not define "terminal". There are two readings:
   - suspension is an **interruption of validity**, with standing unchanged. That case is already available in Ch. 2 §2.11 ("adopted but not in force"), so no new class is needed;
   - suspension is a **standing class**, which would need a fourth class if it can be reversed.

   AM-1 lists suspension as a distinct ending (§1.13.3) but does not decide between the two readings. **Open.**

*Counterexample attempt.* Could "Implemented" be a standing, e.g. "in force since built"? Only if building were an act conferring standing, which the axiom excludes (§1.6). Could "Verified" evidence be authoritative? Verification raises evidential grade; adoption is a separate act (Ch. 2 §2.7). Both readings fail, so the analysis stands. **Ded; for targeted review.**

**Independent check (Ind).** The session resolver states the first member in code: "assignment is ACTIVE — operable; **operability is not authorization**" (`.claude/scripts/session-resolve.php` L225).

**Development (Ded): an answer to OQ-11.** With act × scope × time (§1.6), standing becomes **time-dependent and derived**:

> **Corrected 2026-10-18 (batch 3, file 3; §1.16.2).** The first version read: "currently-authoritative(c, t) ⇔ c was adopted by an act ∧ the backing grant is valid at t ∧ c is not superseded at t". **It is refuted given the premise stated below, not by the data alone**. A grant recorded as CONSUMED (§1.13.2) ended *because* its work was delivered, so the formula would strip that work of authority at the moment of delivery. Likewise every decision of an office-holder would lapse when the term ends. The error was to evaluate the grant at the time of **use** instead of the time of the **act**.

*Premise made explicit (Int).* The counterexample rests on a **normative premise**: work done under a grant that was valid when the work was done keeps its standing after the grant **expires or is consumed**, unless a recorded act revokes or supersedes **that work itself** (the claim *c*). This is how offices and mandates ordinarily work. **The data show only that a CONSUMED ending exists.** They do not show how the organisation treats work done under it.

*Counter-position in the sources (Src; V3 L319).* V3 says a rule "may be `Active` in its lifecycle and not currently authoritative — because its authority lapsed, was revoked, or was superseded". Two readings:
- **Reading 1:** "its authority" is the **grant** behind the rule. Then the grant's lapse, revocation or supersession ends the rule's current authority, which **contradicts** the premise for expiry ("lapsed").
- **Reading 2:** "its authority" is the rule's **own** standing. Then "lapsed" means the rule's own validity ran out, and the sentence is consistent with the premise.

**Reading 1 is the more natural one.** V3's own rule model lists *Validity* and *Authority Reference* as separate parts of a rule [V3 L495–507], so "its authority" most plausibly points to the referenced authority, not to the rule's validity. V3 does not settle it. The premise therefore stands as an **interpretation that the more natural reading of a source opposes**, not as a result.

**Grants and claims.** §1.13.3(d) asks whether a **delegated grant** ends when its parent grant ends, and leans *yes*. A delegated grant is itself the product of a completed act. So "products survive the end of their grant" cannot be a general law, only a **per-kind policy**: products of one kind may survive while products of another kind (delegated grants) do not.

**In plain words.** A decision counts *today* if three things hold. It was properly made back then: the person had the authority, and acted inside its limits and its time window. It has not run out, and it has not been withdrawn or replaced **by someone entitled to do so**. And the authority behind it has not ended in a way the organisation's policy says should carry over to the decision. The formula below states this precisely. Its most important practical consequence comes first: **today no rule in this organisation cites the grant it was adopted under** [AMS L200–206], **so no rule can currently be *shown* to be authoritative.** It may well be authoritative; the record cannot demonstrate it.

**In business terms: what the organisation needs from this, with one running example.** An organisation has the rule *"the Board Chair may approve a new election procedure"*. Over time it collects records: approvals, cancellations, replacements, corrections. KnowledgeOS must say, for any procedure and any day, whether the organisation should **currently recognise** it, **why**, **on whose authority**, and **what remains uncertain**. Six safeguards follow. Each is part of the model below.
1. **Who may decide what.** A record says the Chair approved procedure A. The system checks the person, **the kind of decision** they were allowed to make (approve election procedures, not, say, membership rules), the **area** it covers, and whether their permission was **in effect at that moment**.
2. **A record is not the decision, and that holds for every kind of record.** An entry "the Board cancelled procedure A" proves only that someone wrote it. A cancellation counts only if **it, too,** was made by someone entitled to cancel, inside their area and time. The same applies to an entry that "ends" the **Chair's permission**: if whoever ended it had no right to, the Chair's later decisions are unaffected.
3. **Late and corrected records.** Records often arrive after the event: a cancellation decided on 1 March may be entered on 10 March. A permission may also be found in March to have been issued in error in January. KnowledgeOS can evaluate either **what the records showed at the time** or **what they show now**; the two answers can differ. **Which view applies, and whether an error-correction reaches back to the beginning, is a policy the organisation must decide** (reserved decisions 1 and 2). The system must not choose silently.
4. **Authorized is not the same as well-supported.** The Chair may have approved A properly but on wrong figures. The system answers two separate questions: *was it authorized?* (this section) and *was it well-founded?* (§1.17.2).
5. **Incomplete, conflicting or unverifiable information.** Procedure A may have passed its end date **and** have a disputed cancellation **and** be missing a record of the Chair's permission. The system reports **one headline with all the reasons**, and says "cannot determine" when the gaps could change the answer.
6. **Nobody can freeze a decision by an unverifiable entry, unless the organisation wants that.** If any recorded cancellation whose authority cannot be checked made a decision undeterminable, anyone could freeze any decision. Whether an unverified cancellation counts at all is a policy choice (reserved decision 6). The model shows both behaviours.

**Five things the answer keeps apart.**
- **authorized**: made by someone entitled to make it, of the right kind, in the right area, at the right time;
- **well-supported**: its reasons cite enough evidence (§1.17.2), a separate question;
- **currently recognized**: authorized, not withdrawn or replaced, not run out, and the authority behind it not ended in a way that matters;
- **cannot determine**: the records are not enough to say. **Missing evidence is never reported as missing authority**, unless the organisation adopts the rule that records constitute authority (C1);
- **your decision needed**: two valid rules exist and the organisation has not chosen; the answer is given under each.

*Realistic cases, and what the model answers* (with the reserved choices left open):
- **unauthorized cancellation:** A stays current; the attempt is on record;
- **cancellation whose authority cannot be checked:** "cannot determine" or "current, with a warning", depending on reserved decision 6;
- **unauthorized "ending" of the Chair's permission:** it has no effect; the Chair's later decisions are judged normally;
- **expired permission:** a decision made **after** the permission ran out was never validly made. Whether decisions made **before** stay current depends on the ending policy (reserved decision 1);
- **cancellation recorded late:** the answer for acts between the decision and its entry depends on the record view (reserved decision 2);
- **permission later found to have been issued in error:** from the beginning or from then on, as decided (reserved decision 2);
- **no adoption on record at all:** "cannot determine" (if records represent authority) or "never adopted" (if records constitute it), alternative C1;
- **no permission on record:** "cannot determine" or "not validly made", alternative C2;
- **two conflicting decisions** (A and a later B, both properly made): each is judged **on its own**. Whether B silently replaces A ("later prevails") or both stay current until a recorded decision resolves the conflict is alternative C6. Chapter 2 (§2.3) defines conflict precisely only for **rules**.

**Acts and their records.** The model below speaks about **acts**: events in the world, performed by people (§1.12). The system holds **records** of acts, which are evidence about them, never the acts themselves. Every predicate is evaluated **on the evidence of the records**, under the residual trust assumption of §1.12 that a record faithfully reports its act. A missing record means "not evidenced", not "did not happen" (§1.17.3), **unless** alternative C1 (records constitute authority) is chosen.

**Two time axes (bitemporal).** Every act *x* has:
- an **act time** *t*ₓ, when it was done;
- a **record position** *s*ₓ, its place in the one global, gap-free sequence of record entries. No two entries share a position.

An act that **ends** something also has an **effective time**, from which its effect applies. Normally this equals its act time. For an annulment it may be **earlier**: "void from the start". All comparisons of acts use the **total order** (*t*ₓ, *s*ₓ): by act time, and by record position when act times are equal. Intervals are **half-open**, [from, to).

**Notation.** An act *x* has:
- one or more **actors**;
- an **activity**: *adopt*, *revoke*, *supersede*, *end-grant* (expiry by decision, revocation, supersession or suspension of a grant), *annul* (of a grant or of an act), *issue-grant*, …;
- an **object** (a claim, a grant or an act);
- the claim's **scope** where relevant;
- its **cited authority**: one or more alternative **bases**, each a set of grants that are jointly required (a disjunction of conjunctions).

Joint bases with several actors express dual control; the recorded "PO/ARB" grants are **one** holder [AMS L61–72], so this is a conceptual case.
- A **grant** has a holder, permitted activities, a scope, a start and possibly an end of period, all as recorded.
- The **act window** of a grant at a given record state is [start, first effective terminal ending) minus suspension intervals. Only endings made by **authorized** acts count (safeguard 2). **When judging act *x*, *x*'s own effects are ignored**: an ending that **is** *x*, or is **caused by** *x* (e.g. *x* consumes a single-use grant, or *x* annuls the very grant it relies on). Otherwise an act could invalidate itself, and its authorization would depend on its own outcome.
- **Root acts.** The regress "every ending needs an authorized act, which needs a grant, which needs …" stops at the **root**. These are acts of the human authority that the organisation's constitution designates as the source of grants: here the PO/ARB, the single real holder in the live record [AMS L61–72], whose acts are referenced, not granted [AMS L214–226]. Root acts are authorized by stipulation. *Who* the root is, is a governance fact the organisation must record, not something the model derives.

**Open parameters (alternatives kept, Section C of the final review).**

| Symbol | Meaning | Values |
|---|---|---|
| ρ | record view | *at-the-time*: when judging act *x*, use only entries with position < *s*ₓ · *now*: use all entries |
| κ | an **unknown** citation (C2): the record says nothing about which grant was relied on (⊥) | *constitutive*: false · *evidential*: undetermined |
| χ | joint coverage (C4) | *co-signature*: each grant covers activity and scope · *pooled*: the union covers the scope |
| ω | partial scope overlap (C4) | *whole-or-nothing* · *valid on the intersection* |
| σ | supersession (C5) | *standalone* · *conditional on the successor being current* |
| π | grant without a recorded period (C7) | *undetermined* (an analogy to the SC-2 ruling on scope, Ch. 2 §2.8.3, **Int**) · *open-ended until ended* (AMS reads the absence as "on its face, permanent" [AMS L119–122]) |
| υ | revocation whose authorization is undetermined (brief 6) | *counts as undetermined* · *does not count until evidenced* |
| P | ending policy, per kind of claim and kind of ending | reserved decision 1 |
| ι | the reason condition of I-2 (brief 4) | (a) required for validity · (b) not · (c) required for specified activities |

**Definitions (Ded; logic: strong Kleene, values T, F, U).**

> **authorized**(*x*) = T if *x* is a root act. Otherwise:
> - if the record of *x* **explicitly cites no grant** (cited = ∅): F. Only root acts need no grant;
> - if the citation is **unknown** (cited = ⊥): F or U according to κ;
> - else ⋁ over the cited bases *B* of ⋀ of:
>   - (i) every grant in *B* has *t*ₓ inside its act window, under the record state chosen by ρ;
>   - (ii) covers(*B*, *x*) under χ and ω;
>   - (iii) the reason condition under ι.
>
> **A grant ending, an act annulment, a revocation or a supersession has effect only if its act is authorized** (with υ deciding how U counts).
>
> **currently-authoritative**(*c*, *t*) = ⋁ over adoption acts *x* of *c* with *t*ₓ ≤ *t*, and over bases *B* of *x*, of ⋀ of:
> 1. *x* authorized **through this *B***, and *x* not annulled with effect by *t*;
> 2. *t* ∈ the validity of *c* as set by *x*;
> 3. no effective revocation or supersession of *c* by an act *y* ordered after *x* and with effective time ≤ *t* (supersession subject to σ);
> 4. *B* intact at *t* under P: no grant in *B* has an effective terminal ending of a kind in P(kind of *c*) after *x* and up to *t*, **other than an ending caused by *x* itself** (e.g. *x* consumed the grant). If suspension ∈ P, no grant in *B* is suspended at *t*. *Timing under ρ = at-the-time:* an ending that was not yet on record when *x* was recorded takes effect, for *x*, from its **own act time**, never retroactively. A backdated annulment therefore cannot reach back past *x*'s recording under this view; it can still end the claim from the moment it was made, if annulment ∈ P.

**Well-definedness (Ded; proof sketch).** "Authorized" depends on endings and annulments, whose effect depends on whether **their** acts are authorized. Under ρ = *now*, a later act can reach back: a backdated annulment affects an earlier act. Circular cases are then possible, e.g. two acts each annulling the grant the other was made under. The definition is therefore taken as the **least fixed point** in the information order (U below T and F), computed from "everything undetermined except root acts". Strong-Kleene connectives are monotone in that order, and the set of acts is finite. So the least fixed point **exists and is unique** (Knaster–Tarski; Kripke's construction for three-valued fixed points). Genuine cycles stay U: the two mutual annulments both come out **"cannot determine"**, which is the honest answer. Under ρ = *at-the-time*, an act's evaluation uses only entries recorded before it, so there are no cycles and the fixed point is reached in one pass along the record order. *Alternative (noted, not used):* forbid retroactive effect across already-recorded acts, which would remove cycles but also remove "void from the start".

> **Status of this formal model in baseline v1 (2026-10-18): conditional, with known formal defects.** An independent review found the following; they are **tracked, not yet repaired** (the correction register is in `CH-01-step1-correction-and-review.md`). Read the claims of existence, uniqueness and totality below as **not established** in these cases:
> 1. under υ = "does not count until evidenced" the operator is **not monotone**, so a least fixed point need not exist (mutual annulment oscillates). The correct theorem is the least fixed point of a monotone map on a complete partial order (Kleene/Kripke), not Knaster–Tarski;
> 2. **grant issuance and delegation are not checked.** An unauthorized delegation would produce a working grant, and a parent grant's ending does not reach the child grant's acts. Grants must be modelled as products of acts;
> 3. **"no adoption on record"** evaluates to F in the formula but is headlined "cannot determine" under C1-b. An **annulled adoption** has no headline. The **recorded end of a grant's period** is not an act, so the window as written never closes by expiry; π is not used in any formula;
> 4. intervals mix act time with the total order (a same-moment consumption race); ρ is wired into condition (i) only, nested record views are unspecified, and the model has record **positions** but no recording **time**;
> 5. σ = conditional recurses outside the fixed point; covers(B, x) and the actor–holder link are undefined, and ω has no scope argument;
> 6. a revoked actor can create a cycle that makes the revoker's acts "cannot determine" (a policy question: should such cycles resolve to U or by causal priority?);
> 7. the live grant records name an `authority` that may be the **issuer**, not the holder. The resolver states that the holder is "not evaluable from the record" (`session-resolve.php` L268–269).
>
> **What still stands:** the separation of act time and time of use; authorized endings; the ending policy P and the other parameters as explicit alternatives; three-valued answers with a headline and reasons; the business safeguards as **requirements** the model must meet.

**The answer: a headline with all reasons (total).**
- **current**: some adoption satisfies 1–4 (= T). Reasons why other adoptions or bases fail are kept as notes.
- **cannot determine**: the value is U. This includes **no adoption on record** under C1 = "records represent authority".
- **not current** (= F), with the headline chosen in **causal order**:
  - **not validly made**, or **never adopted** under C1 = "records constitute authority";
  - otherwise the first of: **revoked** · **superseded** · **authority ended** (with ending kind) · **suspended** · **no longer valid** · **not yet valid**.

Every applicable reason is listed below the headline. *Alternative order (C8, noted):* most recent intent first, i.e. revoked or superseded before not validly made. The causal order is used because a claim that never counted should not be headlined as "revoked", as if it once had.

**Properties (Ded, with proofs).**
1. **Totality.** Every (*c*, *t*) gets exactly one headline. *Proof:* the value is one of T, F, U (unique least fixed point); the three headline cases partition them, and within F the causal list ends with the cases that remain.
2. **Adding an alternative basis or a further adoption act for *c* never turns current into not current.** *Proof:* current = ⋁ over (*x*, *B*), and T ∨ anything = T. It **can** turn F into U. **This does not hold** for alternatives added to a revoking or ending act: making a revocation better authorized can end a claim.
3. **Several adoptions make standing grow only.** A re-adoption with narrower validity cannot narrow an earlier unrevoked adoption; narrowing requires revoking the earlier one.
4. **When the two record views differ.** They give the same value for (*c*, *t*) if no entry relevant to the evaluation is recorded **after** an act it affects. *Proof:* under that condition the record state each act sees under *at-the-time* already contains every entry that *now* would use for it. The views can differ **only** through late-recorded entries or backdated effects: a late cancellation, a late grant ending, a backdated annulment. *(This corrects an earlier claim that only annulment matters.)*

*Re-checking against the cases, including those that broke the previous version (Ded).*
1. **Alternative grants:** {*g*₁} or {*g*₂} cited; *g*₁ revoked with revocation ∈ P: **current** through *g*₂.
2. **Joint authority, suspension:** basis {*g*₁, *g*₂}, suspension ∈ P, *g*₂ suspended: **suspended** during the suspension, current afterwards.
3. **Grant ended before the act:** **not validly made**.
4. **Consumed grant:** if the adoption itself consumed *g*, the claim stays current; if another act consumed it, the result depends on P.
5. **Supersession by an invalid successor:** under σ = standalone, **superseded**; under σ = conditional, **current**, with the note "supersession pending a valid successor".
6. **Annulment of a grant:** with a backdated effective time and ρ = now, claims made under it are **not validly made**. Under ρ = at-the-time they were validly made, because the annulment was not yet on record; whether the annulment ends them **from the time it was made** depends on P.
7. **Re-adoption after revocation:** current, revoked, then current again.
8. **Validity ran out and revoked:** headline **revoked**; both reasons listed.
9. **No cited grant.** With an **explicit** "none" (∅): **not validly made** (unless a root act). With an **unknown** citation (⊥): **cannot determine** (κ evidential) or **not validly made** (κ constitutive).
10. **Late-recorded cancellation:** revoked 1 March, recorded 10 March. For **another** act that relies on the revoked grant, made on 5 March: under ρ = at-the-time it was authorized (the revocation was not yet on record); under ρ = now it was not. Defined under each view (property 4).
11. **Unauthorized ending of the Chair's grant:** no effect; the Chair's later approvals are judged normally.
    **Authorized ending of the Chair's grant** (by a root act or a covering grant): the act window closes at its effective time. Approvals made later are **not validly made**; those made earlier depend on P (case 4 and reserved decision 1).
    **Delegated authority:** the Chair delegates approval of procedures to the Secretary (a grant issued by the Chair's authorized act, §1.13.3). If the Chair's own grant ends, the Secretary's grant ends with it only if its kind's ending policy says so; §1.13.3(d) leans *yes* for delegated grants, as reserved decision 1.
12. **Same moment:** an adoption and a revocation share the same act time; record position decides the order. Revocation after adoption: **revoked**. Before: **current** (the revocation preceded the claim).
13. **Mutual annulment:** **cannot determine** for both acts under ρ = now; a defined order under ρ = at-the-time.
14. **No adoption on record:** **cannot determine** or **never adopted**, by C1.
15. **Revocation whose authority cannot be checked:** U under υ = "counts as undetermined", so **cannot determine**; under υ = "does not count until evidenced", **current** with a warning.

Each case gets a defined, explainable answer under every value of the open parameters. Where a value is missing (e.g. who the root is, or the effective time of an annulment), the model reports it as **undetermined**, not as a default. **These checks do not prove the model correct**: they show it is well-defined and keeps the reserved questions open.

**Two readings of AB's exception rule (Src AB L100).** "An exception's validity cannot exceed the approver's authority":
- **static reading**: the exception's validity lies inside the approver's act window, checked once at *t*ₐ;
- **dynamic reading**: the exception binds only while the approver's authority lasts, so any ending of that authority ends it. In the notation, P(exception) contains every ending kind.

**The sources lean static without settling it.** AB frames the question as "was the authority valid **at the time of approval**?" [AB L107]. IM's worksheet for expired exceptions marks the reconstruction "n/a — the interval is self-contained" [IM L221], but that concerns the exception's **own** interval, so it bears only weakly on the question. Neither selects a reading; the dynamic reading would decide P for exceptions.

**The grant's act window vs the claim's binding period.** These are different periods belonging to different things: the act window belongs to the **grant**, validity(*c*) to the **claim**. Tying the second to the first is a per-kind policy; AB's rule ties them for exceptions. Tying them for **all** claims would make every decision lapse when the office-holder's term ends, which the two-time form is designed to avoid. *Terminology:* where this book speaks of a grant's "validity period" (e.g. §1.6, refined, point 2), it means its **act window**.

**validity(*c*) for claims that state no period** (e.g. decisions). This book takes validity(*c*) = [*t*ₐ, ∞). That is a **convention** (Int), not a source rule; a different convention changes only the second conjunct.

**Current authority is not standing** (Ded). The axiom says **standing** changes only by recorded acts (§1.6). "Age ≠ authority" is a statement about standing. **Current authority** is derived from standing **plus** time-dependent conditions: the claim's validity, and grant endings in P. So current authority can lapse with time alone while standing is untouched. There is no conflict with the axiom, because the two are different properties. The narrower notion **effective** (Ch. 2 §2.11: standing *adopted* ∧ *t* ∈ validity) is current authority **without** the grant conditions. It is what can be checked for rules today.

Consequences:
- No second *stored* field is needed. Current authority is **derived** from the record of acts, their cited grants, the endings of those grants and later acts on *c*. A stored copy can drift from the record that determines it; this is IM's point that "a stored `is_valid` boolean is the bug" (§1.16.8).
- So **my proposed** answer to OQ-11 is a governed lifecycle from which current standing is derived, with no second stored field. V3's phrase "governed authority lifecycle" may mean the **grant's** lifecycle. My answer reads it as the claim's standing over time, plus the cited grants as of the act, plus the policy P. That is a reinterpretation of the question (**Int**), not V3's meaning.
- **Claims end through their own validity and through revocation or supersession of the claim itself.** Grant endings matter for claims only through P. Without any of these endings, every approval would count forever, which is why V3 needs endings at all (§1.13.2).

*(This section was revised in correction rounds 1–4 after self-review and independent review; the history is in the change log.)*

### 1.14.2 The mechanism records; it never grants

**The idea (Src, ARB REV 1).** "**The mechanism records authority. It never grants authority**" is raised from prose to a named invariant, **ARCH-INV-1**, to "eventually become a canonical invariant" [V3 L278–286]. Its stated consequence: when the resolver returns *UNASSIGNED / operable: false*, the mechanism is **reporting that no human act exists, not deciding**. "Any component that admits or refuses knowledge on its own judgment has taken a human decision and is out of bounds."

**Independent check (Ind).** The resolver's header calls UNASSIGNED and AMBIGUOUS "a SUCCESS", a valid report (`session-resolve.php` L27–29).

**Supporting invariants (Src, V3 L290–293)**, with their places in this book:

| Invariant | Statement | In this book |
|---|---|---|
| **INV-A1** | record before prose: "a report of an act is not the act" | §1.12 |
| **INV-A2** | machine state before narrative: resolve the record; never accept "I was told I am Session 4" | **instance of §1.12's self-report rule** (new evidence) |
| **INV-A3** | absence is never permission: no assignment, no grant, no START, unreadable, UNKNOWN → **STOP · REPORT · ESCALATE** | Ch. 2 §2.8.8 (now with a prescribed response) |
| **INV-A4** | one authoritative owner per invariant | P-7, §1.8 |

**Development (Ded): refusal by a guard is not a judgment.** A guard that refuses because a *recorded permitting condition is absent* is reporting absence, exactly as the resolver does. It is within ARCH-INV-1. A guard that refuses or admits on its *own assessment of merit* is deciding, and is out of bounds. This is the line between Ch. 2 §2.8.7's gate (refuses unless a permitting record is shown) and an "AI reviewer that rejects a change it dislikes".

**A recorded refusal is a success (Src, V3 L204).** The resolver's "UNASSIGNED / operable: false" is ruled "success, not failure". This is the operational form of the foundations' "unknown and no are legitimate answers" (Ch. 0, DevConst L101).

### 1.14.3 Five roles

**The idea (Src, ARB: "probably the most valuable part of v3"; V3 L270–276):**

| Role | Decides | May never |
|---|---|---|
| **Human PO/ARB** | approval, boundary, authorization, qualification, adoption, closure | — |
| **Governance** | **records** assignments, grants, handoffs, STARTs, transitions | invent a human act · treat a report as the act · widen a grant · do architecture, implementation or verification |
| **Architecture** | investigates, compares, **recommends** | implement · change mechanisms to enable its own work · decide outside its commission |
| **Implementation** | RED → GREEN inside an approved boundary | redesign · enlarge scope · verify itself · close the work |
| **Verification** | **falsifies** independently | repair · implement · adopt · verify its own work |

**Classification: NEW IDEA** for this book. Chapter 0 had only "AI recommends; humans decide".

**Development (Ded): each role is defined by which act it may perform on which object.** Recording, recommending, implementing, falsifying and deciding are **different kinds of act**, and the prohibitions forbid exactly the combinations that would let one actor both produce and accept, or both act and record its own authority. Three general laws follow:
- **producer ≠ verifier** (no role verifies its own work);
- **recorder ≠ decider** (Governance records acts it may not perform);
- **no self-widening** (no role may enlarge its own grant or scope).

Together with §1.2's *authored-by*, these make every acceptance checkable: the accepting act must name a different actor from the producing act.

**The execution plane (Src, ARB ruling; V3 L197–216).** The machinery that carries assignments, grants, handoffs, STARTs, transitions and mutation ownership is ruled **a plane, not a bounded context**: "a cross-cutting operational authority mechanism". In this book's terms it is the **implementation of the authority model** of §1.13. It is the "governance engine" whose live records were recounted there.

### 1.14.4 Representations never gain authority (P-8) and precision is bounded

**The idea (Src, ARB REV 1, "proposed constitutional"; V3 L129–133):**
> **A representation or projection must never acquire semantic authority merely because it is executable, searchable, visualized, indexed, or consumed by AI.**

**Classification:** NEW EVIDENCE for §1.6. Executability, searchability, visibility, indexing and use by AI are all **act-free properties**, so by the one-axiom theorem none can confer standing. P-8 is the ARB's own statement of five instances.

**Three layers, and canonical vs derived (Src, ARB "keep"; V3 L250–264, L354–374):**
- **knowledge** (the semantics);
- **representation** (how it is stored: YAML, Markdown, git, JSON);
- **presentation** (how it is exposed: an ADR presents a decision, a report presents a verdict).

"Layer 3 never becomes Layer 1." For persistence the ARB rejects the binary "repository *vs* database". The repository is the **canonical representation**; database, graph, search and vector stores are **derived representations**, and projections are rebuildable and depended on by nothing.

**Development (Ded): three different "representation" notions must be kept apart.**
1. GKC's **representation dimension** (Executable, Recorded, Conversational, Tacit): the *form* a claim takes.
2. V3's **canonical vs derived**: the *standing of a carrier*.
3. V3's **presentation**: an *audience-facing view*.

P-8 then says: (2) and (3) never feed back into the claim's standing. Only acts do (§1.6).

**Precision bounded by knowledge (Src, P-6; V3 L127).** "Visual precision ≤ architectural precision. Silence is an architectural decision." *Development (Int):* this is the presentation-level form of "a conclusion may not be stronger than its evidence" (M-04, proposed A-1(a)). A diagram, table or schema may not be more precise than the knowledge it presents. "Silence" (leaving a region undefined) is then a legitimate, informative state: E2 (unspecified) chosen deliberately.

**Four things, not one (Src, V3 L443).** KNOWLEDGE ≠ REPRESENTATION ≠ CAPABILITY ≠ RUNTIME. This extends §1.1 and §1.5 with *runtime* as a fourth term. It dissolves "is the PKS software?": the PKS is knowledge; KnowledgeOS contains capabilities that operate on it [V3 L435–444].

**Questions answerable by evidence (Src → Int; V3 L376–380, L453).** The withdrawn identity question is replaced by an engineering one: "Is the repository sufficient as the authoritative persistence mechanism …?", which is "answerable by evidence". *Development (Int):* this extends §1.4. An open question should be phrased so that evidence can close it, which is the same requirement as having an activation criterion.

**The conceptual target as an authority order (Src, ARB REV 1; V3 L533–581).** The ARB's target is explicitly *conceptual*, "not a C4 implementation diagram". It stacks six strata: human authority → governance (decisions, grants, rulings) → execution plane → {knowledge model ⇄ assessment/verification} → projection. It rejects an "implementation-first" picture (API, database, graph, workers) as the target.

*Analysis (Ded).* Read as a relation, the arrows form a partial order of **where standing can come from**:
- standing enters only at the top, from human acts (§1.6);
- the execution plane carries it but does not create it (ARCH-INV-1, §1.14.2);
- knowledge and assessment exchange content and verdicts, which is the only two-way edge;
- projection is a **sink**: nothing flows back out of it (P-8; the context map's "nothing leaves", V3 L243).

So the diagram is the authority axiom drawn as a graph. A useful test follows: any proposed component whose edge points *upward* into governance from below (for example, a search index that marks a claim authoritative) contradicts the target, independent of the technology.

*Instance of the precision bound (Src, V3 L331–352).* The ARB rejected ten proposed containers drawn over two regions the architecture declares undefined, and kept Level 3 as a responsibility view because a conventional component view "would invent architecture". This is P-6 applied: a diagram may not be more precise than the knowledge beneath it.

### 1.14.5 Enforcement presupposes ratification

**The idea (Src, PROPOSED; CF L16–38, L285–287).** A proposal for an "architecture conformance mechanism" wanted blocking gates for bounded contexts, layers and rule shape. CF accepts the diagnosis (an unpoliced architecture decays) and rejects the ordering. A gate that blocks a build on an architecture nobody has decided does not enforce a decision; it **manufactures** one. Once the gate fails builds, the contexts it hard-codes are settled in practice, and the open question is closed "by a linter rather than by a human act". CF's rule: **gates follow ratification; they never precede it.** Its operational test: before building a gate, name the recorded human act that decided what it enforces. If there is none, "the gate is not enforcement; it is an architectural decision wearing enforcement's clothes."

**Classification:** NEW IDEA. **It does not follow from the book's existing axiom alone.** It follows from the axiom together with a further premise, P-E, stated below. CF's rule is a PROPOSED source [CF L36].

**In plain words.** An automatic check that **stops** someone's work has the same practical effect as a binding decision. The book's position is conditional: **if** the organisation accepts that a machine may only do what a person with authority has decided (premise P-E below), then every block needs a decision behind it. That decision must be valid **now**, and must cover this block, this rule and this time.

**Argument (Ded, conditional on P-E).** Let a gate *g* block an action whenever rule *r* is violated. Its effect on actors is the effect *r* would have if it were authoritative, mandatory **and** set to block (hard). §1.6 governs *changes of standing*, and a gate changes no standing, so §1.6 alone does not exclude this. **The argument needs one further premise:**

> **(P-E)** No mechanism may produce the *normative effects* of an act, or change the *standing* of a claim, unless a valid act authorizes it.

**Normative effects**, in business terms, are **effects that change what someone may or must do**: permit, forbid, **block** or oblige (the same meaning as in §1.3). **Standing changes** are a second, separate kind of effect, listed beside them in P-E.

**Reporting, warning, displaying, logging and recommending are not normative effects.** They are evidence (INV-A1) and need no authorizing act. *Consequence:* "permit" is on the list, so P-E also reaches a mechanism that **lets** something proceed on its own authority, for example one that fails **open** when it cannot check. Failing open is as normative as failing closed. Whether P-E should cover permitting at all, or only forbidding and blocking, is part of **reserved decision 3**. The narrow version leaves failing open ungoverned. The broad version reaches every passing gate.

P-E generalizes ARCH-INV-1 ("records authority; never grants it", §1.14.2). P-E itself is **not adopted**. Everything below holds **only if P-E is accepted**.

> **Conclusion (given P-E).** blocking(*g*, *r*) in scope *S* during period *T* ⇒ some act that is **currently authoritative at the time of the block** (§1.14.1) authorizes **that block** for rule *r* (in the version the gate checks), for a scope containing *S* and a period containing *T*.

Which acts can do that:
- **an act that authorizes the gate's blocking directly** (e.g. a temporary freeze while *r* is still a candidate). The act sits on the gate, not on the rule, so *r* need not be adopted;
- **an act that specifies blocking (hardness) for *r***, for the scope and period. This may be part of the act that adopts *r*, or a separate act;
- **the adoption of *r* as mandatory, by itself**, only under **reading (i)** of reserved decision 5, that mandatory binding strength already authorizes blocking. Under **reading (ii)**, hardness needs its own specification. **The book assumes neither.** The evidence is weak in both directions. RS shows that a mandatory rule **need not** block ("a Rule may be MANDATORY while its current enforcement is ADVISORY" [RS L185]). That does not show mandatory status **fails to authorize** blocking. Whether a rule conflict blocks activation is undecided (OQ-24) [IA L173] (§1.17.5).

**Recommended rules and confirmation requests.** Blocking on a *recommended* rule gives a "should" the force of a "must", so it needs an authorizing act like any other block. A **confirmation request** (the mechanism asks, a person decides) is not a block, **provided the person asked holds a grant covering the decision**. A request routed to someone who cannot validly decide stops the work just as a block does, and counts as one.

**Conditions for an admissible block (given P-E).** All of:
1. **realization**: *r* has reached a form in which a check can be executed (Ch. 2 §2.9; §1.9);
2. **authorization**: an act that is **currently authoritative** at the time of the block authorizes it, by one of the routes above (the third route only under reading (i));
3. **containment**: the block stays within that act's scope and period, and the gate checks **the rule version** the act refers to. If *r* is amended to *r*′, the authorization does not carry over unless the act says so (cf. pinning, §1.16.6).

*Checks against examples (Ded).*
- **Authorized emergency freeze on a candidate rule:** admissible while the freeze act is current. If the freezing actor's grant is later revoked and revocation ∈ P, the freeze stops being authorized from then on.
- **Blocking by configuration, as the quality gate actually works** (**Ind**, verified in `scripts/design-check.sh`). Its header says it is non-blocking now and "becomes hard-blocking when violations drop below threshold" (L5). The code does not do that. It exits with failure only if a configuration flag (`exit_on_fail`) is set, or the caller passes `--strict` (L52–53), **and** violations exceed the threshold (L226–232). In the low-violation state it nevertheless prints "Blocking: Yes (all violations fail)" (L216–218) and does not block. So the real switches to blocking are a configuration value and a command-line flag, **neither tied to a recorded act** (Ch. 2 §2.10), and condition 2 fails **under P-E**. The printed status is a **false self-report** (§1.12): a claim by the mechanism about itself, contradicted by its own behaviour. *A ruled source sees this differently*: RS assigns enforcement consequence to "the mechanism operating it", changing "when the enforcement maturity changes" ("blocking once the gate is trusted") [RS L181–183]. RS does not say **who decides** that the gate is trusted. P-E's answer, "a recorded act", is one answer, not RS's. **This tension is real and stays open with reserved decision 3 (P-E).**
- **CF's rule** "gates follow ratification; they never precede it" [CF L36] concerns enforcing an **architecture** nobody decided. An authorized freeze on a candidate rule is compatible with it only if the freeze act itself counts as ratification **of the block**, not of the rule. That is this book's reading (Int), not CF's statement.
- **Mandatory rule, advisory enforcement** (today's quality gates): no block, so nothing to authorize. Consistent with Ch. 2 §2.9.

These checks did not refute the conditions. **They do not show the conditions are sufficient.**

**Authority gates: report, refusal and failing closed.** The session resolver returns *UNASSIGNED / operable: false / STOP* when no assignment exists. The sources treat that output as **"a report of an absent record, not a machine denial"** [IA L93; V3 L278–286]. The book agrees for the **report** itself: it is evidence, not a normative effect. The **STOP** that follows is different. It stops work, and it is carried out by the session obeying a rule: INV-A3, "absence is never permission" [V3 L292]. So the normative effect comes from that rule, not from the mechanism.
- **What the axiom shows:** an act made without a covering grant confers no standing (§1.6).
- **What it does not show:** that work may be **prevented** when no grant is recorded. Authority can exist without a record (E4, §1.17.3). Preventing the attempt treats "no record" as "no authority".
- **Provenance of INV-A3.** It is stated in V3 [V3 L292] and listed among the "executed invariants" of the execution plane [V3 L203], which the ARB adopted "as plane" inside V3. V3 is an AI-generated reconciliation, approvable as a proposal. This book places the whole knowledge-transfer set in the exploratory Track B [RS L22]; V3 does not label itself so. **No adopting act for INV-A3 was located.** Failing closed therefore rests on INV-A3 as recorded there, or on an explicit premise **P-R: only recorded grants count**. Neither is adopted in this book's sense. Under P-E, the rule behind the STOP needs an authorizing act, and whether one exists is **open**.

*What the conditions still allow (Ded).* A rule may be **evaluated** and its result **reported** without any authorizing act, because a report is evidence, not an act (INV-A1). The conditions restrict only *blocking*, and under the broad reading of P-E, *permitting*. So the advisory mode of today's quality gates (Ch. 2 §2.9) is the correct mode for **any rule for which no current act authorizes a block**, whatever its standing.

*(This section was revised in correction rounds 2–4 after independent review; the history is in the change log.)*

**Two kinds of gate (Src → Ded; CF L62–81).** CF finds exactly one executing gate in its folder that refused work: the session resolver returning UNASSIGNED / not operable / STOP (§1.14.2). It is an **authority gate** ("may this actor act here, now?"), not an **architecture gate** ("is this artifact correctly classified?"). *Analysis:* the difference is computability, not taste.
- An authority gate needs only records of acts, grant scope and validity, which exist (§1.13).
- A conformance gate needs rules with applicability and effect, which the running rule engine cannot express (Ch. 2 §2.1).

So the first is demonstrable now and the second is not yet computable. CF's claim "only instance" is scoped to its folder. The repository has other mechanized checks (design checks, the destructive-command gate; Ch. 2 §2.8.7, §2.9), and the design checks are configured not to block.

**Staging as an order of dependencies (Src: CF L200–254; Ded: the generalization).** CF orders the proposal into stages: authority gating (exists) → the two missing rule fields (supported now) → code-level layering gates (blocked on whether the product is software) → manifest and constitution (blocked on the context partition and on who owns the criteria) → full intake routing (after all). *Generalization (Ded):* if each gate is linked to the decisions it presupposes, the admissible build order is a **topological order** of that dependency graph, and a gate's earliest stage is just after the last decision it enforces is ratified. CF's stages are one such order.

*Superseded blocker (Ded).* CF blocks the code-level gates on "is the PKS software?" Later that day V3 REV 1 (11:36), following the gap analysis (§1.5), records an ARB ruling that **withdraws** that question as phrased. *Standing:* the ruling is reported inside an AI-generated proposal, and its original record was not located (Track B). The reading below is therefore **conditional** on that ruling. A later document is not by itself a reason (A-1 v2 (c), pending). On that reading the question is dissolved: the PKS is knowledge; KnowledgeOS holds capabilities that operate on it (§1.14.4). Under that reading, code-level layering gates have a subject after all: the **capabilities** (scripts, libraries), not the PKS. The stage remains blocked, but on a different question: which capabilities are decided architecture (V3's required script inventory).

**Criteria authoring is a separate role (Src: CF L150–163; REPEATED).** An "architecture constitution" authored by the conformance mechanism would make the evaluator write its own criteria, which the adopted pattern forbids ("criteria are used here, owned elsewhere"). This is the same separation as Chapter 2 §2.4 ("definition is not evaluation"). Read with §1.14.3, the role laws then include: **legislator ≠ evaluator**, alongside producer ≠ verifier and recorder ≠ decider.

**The existing practice is the gate, executed by people (Src; CF L167–178).** CF finds the content of the proposed intake classifier already in use as human review practice: nine ordered review questions; six classes into which findings must be sorted *before* fixing; a review lifecycle; and five questions for any act: *Who decided? Who was authorized? What scope? What proves they did only that? Who independently challenged it?* (all cited-only, from a sessions guide).

*Analysis (Ded).* The five questions are the authority axiom turned into an audit:
- *who decided* → the act;
- *who was authorized* → the grant;
- *what scope* → grant scope (Ch. 2 §2.8.2);
- *what proves they did only that* → evaluation scope ⊇ the act's effects (S-3, Ch. 2 §2.8.1);
- *who challenged* → producer ≠ verifier.

So the practice is the axiom at the **stated** level of the realization ladder (§1.9). Mechanizing it is a move up the ladder, not a new capability, which makes it cheaper and better evidenced than CF's input assumed.

**Environment over instruction, bounded (Src, PROPOSED; CF L48–58).** "Where an invariant can be made mechanically difficult to violate, instruction is the weaker control. Mechanism is preferred, but only for invariants that are already decided." CF notes the same principle arrived independently in the sessions guide: a session should be "capable of refusing work outside its authority". The second clause is the standing bound above.

*Numbering collision (Src, recorded, not resolved).* CF proposed this as v3 principle **P-8** and "gates follow ratification" as **P-9**, and posed its own **OQ-11** on the cost of mechanization. V3 REV 1, written later that day, uses **P-8** for "representations never gain authority" and **OQ-11** for "Approved ≠ currently authoritative". Neither of CF's principles appears in REV 1. Their standing is therefore *proposed, not carried forward*. This book cites them by content, never by number.

**Open: when does mechanizing cost more than it protects? (Src: CF L182–196, L267; Hyp: the form).** CF quotes the methodology benchmark's risks: certification bureaucracy, over-formalization, and boundaries that are revisable choices, not facts. It notes that the proposal adds seven gates with no measurement that any improves boundary quality. *Hypothesis (form only):* mechanize invariant *i* when

> p(violation of i) × cost(violation) × (reduction by mechanism) > cost(build + maintain) + p(false block) × cost(false block).

The terms are of a kind the observation runtime is built to record, but in principle only: its streams hold one outcome and one assessment record (background note B-11), far too few to estimate any term. None is estimated in the sources. Until one is, "prefer mechanism" is a preference, not a result.

---

## 1.15 The PKS concept vocabulary tested against the knowledge space

**The idea (Src; V3 L139–169).** The product knowledge space works with **17 validated concepts**:

> Decision · Rule · Invariant · Ruling · Finding · Question · Term · Contract · Model Element · Observation · Risk · Candidate · Work Item (adjacent) · Guide Step · Verdict · Exception Record · Charter Grant

plus **Claim** as a *candidate*. The ARB confirms that Claim "may not anchor a context, a container, or an aggregate", and that **Claim is not the parent of Decision and Rule**: that would repeat the earlier "KnowledgeItem" error, a type "too broad to be useful" [V3 L143–167].

### 1.15.1 An apparent contradiction with the foundations

GKC makes the claim **the atomic unit of all knowledge**, constitutionally [GKC L19]. V3 makes Claim a **candidate** and forbids it as a supertype. **Classification: CONTRADICTION (apparent).**

**Analysis (Ded).** The two use "claim" in two senses:
- **GKC: a unit of granularity.** Governance attaches at the level of single assertions, so a composite document is governed claim by claim.
- **V3: a type in a hierarchy.** It asks whether Decision and Rule are *subtypes* of Claim, and whether Claim can anchor a model element.

These are compatible: every Decision and Rule can be *made of* claims (granularity) without being *a kind of* Claim (generalization). The conflict disappears if the book uses "claim" only as the **unit of assertion**. **Int; Open** whether the corpus uses one sense or both.

### 1.15.2 Do GKC's natures cover the 17 concepts? (Ded)

GKC's six natures are Decision · Definition · Constraint · Description · Finding · Measurement [GKC L36]. Mapping:

| Concept | GKC nature | Note |
|---|---|---|
| Decision, Ruling | Decision | a ruling is a decision by an authority |
| Rule, Invariant | Constraint | |
| Term | Definition | |
| Model Element, Guide Step, Contract | Description, or Constraint for a binding contract | |
| Finding | Finding | |
| Observation | Measurement/Observation | |
| Verdict | Finding (an assessment) | |
| Exception Record, Charter Grant | **acts**, not assertions about the world | records of authority acts (§1.13), not knowledge natures |
| **Question** | **none** | an open question asserts nothing |
| **Risk** | **none clean** | a claim about possible future harm: a hypothesis-like Finding? |
| **Candidate** | **none: it is a status** | the same signature GKC's Taxonomy used to falsify "Historical" as a type [RQ-002 Taxonomy L115] |
| Work Item | outside (adjacent) | |

**Findings (Ded):**
1. **"Candidate" is a status, not a concept.** By the foundations' own falsification method it should leave the vocabulary and become a standing value.
2. **Exception records and grants are records of acts.** They belong with the authority model (§1.13), not with knowledge natures. This supports §1.13's view that authority is a relation to acts, not a kind of knowledge.
3. **Question and Risk find no nature.** Either GKC's natures are incomplete, missing an *interrogative* or *open* nature that would also host Knowledge Needs (Ch. 0 §0.7), or these concepts are not knowledge claims at all. *Evidence bearing on the choice (Int; V3 L553–558):* the ARB's own conceptual target places **Questions** and **Findings** inside the knowledge model. The platform therefore treats them as knowledge, which weakens the second option and points to a missing nature. This is one ARB diagram, not a decision on the natures.

**Open**, and a direct question for the corpus meta-model, which GA reports as having 6 dimensions, "2 new, 1 rejected" (§1.8).

**Provenance attributes (Src, ARB adopted; V3 L171–180).** Observations must carry: source · source version · exact location · extraction method · transformation lineage · evaluator · policy context · verification state, **plus authored-by**. "AI-produced content is provenance-marked, always."

*Analysis (Ded):* the list mixes kinds:
- "verification state" is an **evidential grade** (§1.3), not provenance;
- "policy context" is the **scope** under which the claim was evaluated.

The pure provenance core is source, version, location, extraction method, lineage, evaluator and authored-by. Keeping the others separate prevents provenance from silently carrying standing.

**Identity (V3 §2.1, adds to Ch. 0 §0.2).** The "most mature framing" of the vision is **"How can strategic architectural discovery become a repeatable, evidence-based, and governable engineering activity?"**, with the positioning "an evidence-based governance methodology for Strategic DDD" [V3 L85–89]. EP01 classes this vision as T1 / *Engineering Method* (PD-2), not T3 (EP01 L114). The organisation-wide knowledge-system product is rejected [V3 L91]. This is a fifth meaning of what KnowledgeOS is about: **a method**, not only a platform or a product.

**Generations and reversal (Src, V3 L64–77).** The folder holds four generations; the newest silently reverses the oldest ("not software, YAML + Markdown" → "PostgreSQL as system of record") without citing it. *Evidence for §1.8:* retrieval failure produces **unexplained reversals**, not just duplicates.

---

## 1.16 Consistency boundaries, time and reproducibility

**Source added.** **AB**: the aggregate-boundaries adjudication (PROPOSED, Track B). It is tactical modelling for a future rule system. This book takes from it only what bears on the theory of governed knowledge; the aggregate catalogue itself stays in the register.

### 1.16.1 One owner per invariant, one atomic change

**The idea (Src; AB L15–35, L59–63).** AB's organizing question is: *which object owns this invariant, which change may alter it, and which facts may lag?* It replaces "another component diagram". AB reads it as the principle "one authoritative owner per invariant" (V3's P-7) moved down one level, and restates an aggregate without database terms: **what must be consistent within one atomic change.**

**Classification:** REFINEMENT of §1.11 (ownership) and of the invariants I-1 and I-7 (Ch. 0).

**Development (Ded): what is the atomic change of a knowledge space?** A standing transition is caused by an act (§1.6). If the act's record and the transitions it causes can be stored separately, a crash or a partial edit leaves a state in which a transition exists without its act, or an act without its effect. Either breaks reconstructability (R, Ch. 0 §0.5). So: **an act and the standing transitions it causes should fall inside one atomic change.**

AB observes that in the canonical representation (git, §1.14.4) the atomic change is the **commit**, and that content hashes and snapshot identifiers come free: a file's content has a hash, and a commit fixes the state of every file [AB L63–76]. This is correct for git's object model (**Ind**).

*But the boundary is broken today (Ded, from §1.13.2).* The grant records are **excluded from version control** (`.gitignore:33`, checked). So the act's authority (the grant) and the claim it governs do not share an atomic change or a history. Two consequences:
- the "free" reproducibility does **not** extend to authority: the question "was this grant valid when the act happened?" (§1.16.2) cannot be answered from git;
- §1.13.2's conclusion is sharpened: grant records must be inside the same consistency boundary as the claims whose standing they support, or be at least as immutable and addressable.

**Knowledge about a system is not the system (Src: AB L119; Ded).** AB rejects aggregates for *System, Component, Capability, Dependency*: they would make the knowledge system a **system of record for the architecture it documents**. In this book's terms: the subject a claim is about (subject scope, Ch. 2 §2.8.1) is not owned by the knowledge space. It is referenced, like any external domain. This is the counterpart of §1.5 (knowledge is not its carrier): knowledge is not its subject either. Left open by AB as **OQ-16**.

**Phase (Src: AB L39–51; REPEATED).** AB's proposal is "correctly shaped" but arrives before the strategic questions it depends on are closed. "Forbids tactical decomposition" means "not yet derivable", not "never". This is §1.14.5 (gates follow ratification) for **modelling**: a model may not settle a question that is still open.

### 1.16.2 Authority is temporal: valid at the time of the act

**The idea (Src; AB L94–111).** AB adopts six invariants for an exception record:
- it names the rule it overrides;
- its scope may not be broader than the rule's unless explicitly authorized;
- its validity may not exceed the approver's authority;
- an expired exception cannot satisfy a current conflict;
- revocation must be distinguishable from expiry;
- it cannot silently change the rule.

AB singles out two. "Validity cannot exceed the approver's authority" makes authority **temporal**: the question becomes "**was the authority valid at the time of approval?**", which "makes an audit trail reconstructable rather than merely stored". "Revocation ≠ expiry" is "absence is never permission" in exception form.

**Classification.** Four of the six are already in Ch. 2 §2.11 (containment of scope and time; separate act; expired ≠ withdrawn). **"Valid at the time of approval" is NEW** for this book, and it **poses** the question that, together with a stated normative premise, refutes the first form of §1.14.1. The correction is made there.

**Development (Ded).** Authority statements need two time indices:
- *t*ₐ, the time of the act, at which the grant must have covered the actor and scope;
- *t*, the time of use, at which the claim's own validity, revocation and supersession are evaluated.

Collapsing them in either direction fails. Two further questions stay open (§1.14.1): **which grant endings carry over** to the claims adopted under the grant (the ending policy P, reserved), and **which state of the record** "valid at *t*ₐ" is judged against (limitation L-RT, which decides whether annulment is retroactive).

The two failures:
- evaluating the grant at *t* voids work done under consumed or expired grants, **given the normative premise** stated in §1.14.1, which the more natural reading of V3 L319 opposes;
- evaluating the claim only at *t*ₐ makes revocation and supersession impossible, so every approval is authoritative forever.

AB leaves open whether a grant must carry a **delegation chain and as-of-time validity** (**OQ-18**). The two-time form shows that **as-of-time validity is required** for authorizedρ(*x*) (§1.14.1) to be computable at all; the delegation chain is §1.13.3's derivation DAG.

### 1.16.3 A result is relative to its inputs

**The idea (Src; AB L140–148).** "R1 conflicts with R2" is not a result. "R1 at version X and R2 at version Y conflicted under context snapshot Z, analyser version A, ontology version B, interval T" is. AB gives two reasons: the record is what makes an analysis **evidence rather than opinion**, and it locates variation in the **inputs**, not in a confidence score on the conclusion.

**Classification:** REFINEMENT of Ch. 2 §2.7 (uncertainty lies in the premises), stated operationally.

**Development (Ded).** Write an analysis as *f*(inputs, analyser). A finding stated without its input tuple cannot be re-run, so it cannot be checked by a second party: producer ≠ verifier (§1.14.3) becomes vacuous. **For verification by re-running an analysis, reproducibility is therefore a precondition, not a quality.** A result that carries its own justification, such as a proof, can be verified by checking that justification without re-running anything. For a solver or AI result that does not, the input tuple is what a second party needs. This is the machine form of the reconstructability premise (R).

**Projections go stale (Src: AB L90; Ded).** AB notes that the folder cannot say a projection is **stale**, or that a failed rebuild left an outdated one in use. *Development:* let snap(p) be the canonical snapshot a projection *p* was built from. Then *p* is stale at *t* if the canonical state of *p*'s inputs at *t* differs from snap(p). This is the core property of Ch. 0 §0.1, "valid at the moment of use, and visibly so", applied to carriers. An AI context assembled from a stale index is exactly the failure Chapter 0 opens with. So **every projection must carry the snapshot it was built from**, or its users cannot know whether it is valid.

### 1.16.4 Five records, and the one AB leaves out

**The idea (Src; AB L152–164).** "An audit event records that something happened; it does not necessarily own the state transition that caused it." AB identifies this with "a report of an act is not the act" (INV-A1) and separates **five** kinds of record: domain state · domain events · audit events · provenance records · projection build records.

**Development (Ded).** Mapped to this book:

| AB's record | In this book |
|---|---|
| domain state | current standing and content of claims |
| domain event | a recorded standing transition |
| audit event | a report of an act (INV-A1) |
| provenance record | where a claim came from (§1.2) |
| projection build record | how a carrier was derived, with snap(p) (§1.16.3) |

**The list omits the anchor:** the **record of the human act itself** and the **grant** under which it was made (§1.13). In this theory every standing transition must point to one (§1.6), so it is a sixth kind of record, and the only one that is *not* produced by the system. Leaving it out is the gap §1.16.1 found in practice (grants outside version control).

### 1.16.5 Caution against over-formalization

**The idea (Src; AB L115–121).** AB warns against aggregates "drawn around nouns" and then lists about fifteen candidate aggregates for a system with none implemented. It also records a fifth context partition (4 → 6 → 7 → 4 → 6) without evidence for any move. AB's own reading: "fifteen is a hypothesis, not a finding" (**OQ-19**).

*Analysis (Int).* Same pattern as §1.14.5's open cost question: structure is proposed faster than evidence for it. The theory's safeguard is the realization ladder (§1.9) read upward: a concept earns a stronger form only with evidence that the weaker form is insufficient.

### 1.16.6 Pinning: a reference must say which version it means

**Source added.** **IM**: `docs/knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` (AI-generated, PROPOSED; restructured in place from a catalogue into a "decision instrument").

**The idea (Src; IM L47–57, L229–248, L311–315).** IM adds a field, `reconstructable_as_of`: the versions of the referenced objects that must be **pinned** when the governing command runs, so the condition can be re-checked later "against the state as it actually was". Its argument is the two-time point of §1.16.2, reached independently: if the approver's grant later lapses or is revoked, "was this exception validly approved?" becomes **unanswerable** unless the grant version consulted at approval was captured [IM L51]. A second case: an exception's scope is checked against its base rule; without pinning, "a later widening of the base rule silently widens every exception granted under it" [IM L244–245]. IM finds nine of its 32 conditions need a pin, or they are "checkable once and never again". The options are **pin, detect or forbid** a change to the referent.

**Classification:** EVIDENCE for §1.16.2 (second derivation; same author family and same day, so not independent in the strong sense of Ch. 2 §2.1).

**Development (Ded): silent drift through references.** Let claim *c* refer to claim *d*. If the reference names only *d*'s identity, any later version of *d* changes what *c* means **without any act on *c***. That is a standing-relevant change without an act, which the axiom forbids (§1.6). So a reference that carries meaning must be one of:
- **pinned** (*d* at version *v*);
- **live, with detection** (a change to *d* raises a finding against *c*);
- **live, with prohibition** (*d* cannot change while *c* depends on it).

Supersession as a link (I-7) makes pinning cheap: old versions persist, so *d*@*v* stays resolvable.

**On IM's open question OQ-21 (Hyp).** IM proposes PINNED_AT_DECISION as a seventh value of its *atomicity* field ("when a condition can be evaluated"). But IM's own rows need it **together with** another value ("GOVERNANCE_DECISION + PINNED_AT_DECISION", rows INV-RULE-010 and INV-EXC-003). A single-valued field that must hold two values at once is the signature of a **missing dimension**, not a missing value, by the model-integrity test the project records (new dimension only if orthogonal, necessary and sufficient; recorded as an observation, not an adopted standard). The dimension is **referent time**: when the referenced state is read. IM's own worksheets supply its values:
- *pinned at the decision* (INV-EXC-002);
- *evaluated at read time* (INV-EXC-004: "validity evaluated against the asking instant");
- *self-contained*, needing no referent (INV-EXC-004: "the interval is self-contained").

*Qualifications:*
- IM itself lists "GOVERNANCE_DECISION + a pinned reference" as one option for OQ-21 [IM L370], and already has a `reconstructable_as_of` field [IM L55–57]. My point is therefore **close to IM's own composition option**. What I add is only the observation that a single-valued atomicity field cannot hold the composition.
- The three values come from **two different IM fields** (enforcement timing and as-of reconstruction), so their unity as one dimension is itself part of the hypothesis.
- "Orthogonal, necessary and sufficient" is **asserted, not shown**.

**Hyp.** A real test: write all rows of IM's matrix with atomicity and referent time as separate fields, and check whether any row needs a value the two-field form cannot express, or whether the two fields always co-vary. A check of whether the meta-model already has the dimension would test novelty, not truth.

### 1.16.7 Conditions that no single object can own

**The idea (Src; IM L284–290).** "Exactly one authoritative home per rule" cannot be checked from one rule; it needs every rule. IM moves it off the rule to a corpus-wide analysis, graded **advisory**, "finding, never a block". It notes this is the first checkable form of the oldest clause of the Rule concept, and that "it was never an aggregate invariant at all".

**Development (Ded).** Uniqueness of the authoritative home is a **global** property: for every claim, the number of authoritative carriers is exactly one. Global properties have only two enforcement forms:
- **prevention**, if every write passes one serialization point, physical or logical (a single registry, or several write paths that all consult one shared registry or lock before writing);
- **detection**, otherwise: an analysis that finds duplicates after the fact.

Without such a serialization point, prevention is not possible, so detection is the only honest grade. This explains a known weakness: duplicates and contradicting copies (§1.8) arise exactly where the knowledge space has many write paths (documents, chats, configuration). It also gives a design criterion: **a global invariant can be HARD only where all writes pass one serialization point.**

### 1.16.8 Acceptance requires an attack; absence must be visible

**The idea (Src; IM L95–101, L172–178, L385).** IM's status rule: "a row may not reach *Accepted* while its test reference is empty"; verification must show claim → attack → observed result → evidence → conclusion (cited from a sessions guide). Its tally: "thirty-two invariants, zero attacked", so every row stays *Proposed*. A second row it calls the strongest: a verdict cites at least one evidence record, **or states explicitly that it is evidence-free and provisional**. This "permits an unevidenced verdict while making the absence impossible to hide".

**Classification:** REFINEMENT of §1.14.3 (producer ≠ verifier) and of Ch. 2 §2.8.8 (kinds of nothing).

**Development (Ded).**
- *Acceptance.* IM's rule is a rule for **invariants of software**: no row is accepted until an executable scenario has tried to break it. The general lesson is narrower than "untested means hypothesized". **The test must match the kind of claim** (Ded):
  - a **mathematical** claim is established by a proof;
  - a **logical** claim (that something follows) by a derivation, and refuted by a countermodel;
  - an **implementation** claim by tests or inspection of the code paths concerned;
  - an **empirical** claim by measurement with stated coverage;
  - a **source** claim by checking the source passage;
  - a **governance** claim (that something was decided) is **evidenced** by the record of the act and the grant behind it. A record is evidence under the residual trust assumption of §1.12, not proof;
  - a **stipulation or convention** (a modelling choice such as "only cited grants count") is not proved at all: it is **declared as such** and accepted by adoption, and its consequences are tested;
  - an **interpretive reading** of a source is supported by close reading of the passage and its context, with the competing readings stated (e.g. V3 L319 in §1.14.1).

  A claim without the evidence its kind requires keeps the grade that evidence supports (§1.3), whatever its author believes. A failed attempt to refute it raises confidence but proves nothing. Tying acceptance to the **appropriate** test is a Popperian reading of verification, consistent with Chapter 0's stance that knowledge is proposed, qualified and revised, "never 'true'" (§0.2). *(Corrected: the earlier wording made "never attacked ⇒ hypothesized at best" universal; a proof, for instance, needs no attack to be established.)*
- *Visible absence.* "Evidence-free" is a **declared E5** (no evidence exists) attached to the verdict, instead of a silent one. The core property of Ch. 0 §0.1 asks for knowledge to be valid *and visibly so*; this row extends "visibly" to **the lack of support**. Together with MARK_UNKNOWN, "never a pass" [IM L91–93], it is the guard principle of Ch. 2 §2.8.7 in record form: what is not positively supported is shown as such and cannot open a gate.

**Presence is not validity (Src → Ded; IM L184–205).** IM splits "an active rule has an authority reference" into two conditions: the *presence* of a reference is local and atomic; the *validity* of what it refers to is not, and is checked through an authority port. "Collapsing them is how an aggregate quietly acquires responsibility for the organizational authority graph." *Analysis:* this is the same distinction as the limit of §1.13.2's census, where only the *presence* of an act reference was checked, not that the act exists or covers the scope. A present reference is evidence of a **claim** of authority, not of authority.

**Time-triggered conditions (Src; IM L207–227; REPEATED).** "An expired exception cannot satisfy a current conflict" has **no triggering command**: expiry is caused by time. It is evaluated at read time against the asking instant, "never cached as a boolean"; "a stored is_valid boolean is the bug this row exists to prevent". This is §1.14.1's "derived, never stored" (I-9), reached independently. IM's OQ-22 (may a condition have no trigger?) is answered by it: yes, and such conditions are exactly the ones a write-time-only check misses.

---

## 1.17 Who may reject, who may know

**Source added.** **IA**: `docs/knowledge_tranfer/20260816_1029_invariant_allocation.md` (AI-generated, PROPOSED; adjudicates two conversation inputs, the second as an addendum at 10:40; external citations unverified). Like AB and IM it is tactical; the book takes the parts that bear on the theory.

### 1.17.1 Reasoning, coordinating, deciding

**The idea (Src; IA L15–40, L194–198).** IA's allocation rule: **aggregates protect their own state; domain services reason across objects; application services coordinate a use case; governance workflows decide** what analysis cannot. IA counts this as the sixth independent statement of the same rule in one day; by Ch. 2 §2.1's caveat, these are repetitions within one author family, not replications.

*Mapping (Ded).* The four verbs are §1.14.3's roles inside software:
- *protect* and *reason* are evaluation (they produce findings, never standing);
- *coordinate* is the execution plane (it carries acts, it does not make them);
- *decide* belongs only to the human authority.

So the role laws hold at code level too: a component that both coordinates and decides is a recorder that decides; a "fat" handler that re-implements domain meaning is a second owner of an invariant (P-7).

### 1.17.2 Authority and information must co-occur

**The idea (Src; IA L44–58).** IA's single ownership test: "If this condition is false, which single object has the **authority and the information** to reject the command?" It notes that "the corpus's failures have always been one without the other". Its worked case: a rule *can see* that its authority reference is missing (information), but has no *standing* to judge whether the referenced grant is valid (authority), so one condition has two owners.

**Development (Ded): the two failure modes touch two different properties.**
- **Information without authority** is a mechanism that decides, or a person who decides without a grant. A mechanism breaks ARCH-INV-1 (§1.14.2); an ungranted person fails the axiom's grant condition. Either way the act is **not valid**: it confers no standing.
- **Authority without information** is different. Under the axiom's **current** conditions (§1.6: a recorded act, within grant scope, within the grant's period), an authorized act is valid however poorly it was informed. **Whether that remains so depends on an open choice about I-2** (below). What such an act may lack is **well-foundedness**.

**I-2 has three parts, and they belong to different properties (Ded).** I-2 reads: status changes are "explicit, reasoned and visible where knowledge is found" (Ch. 0 §0.5):
- **explicit**: a recorded act. This is part of **validity** under every reading (Ch. 0 §0.6);
- **reasoned**: a **recorded reason is present**. This is a **stipulation** (Int): the sources do not define the word. Whether the reason **cites evidence**, and whether that evidence suffices, belongs to **well-foundedness**, not to "reasoned". Keeping the two apart stops reading (a) below from pulling well-foundedness into validity. Its place is the **open choice** below;
- **visible where knowledge is found**: the change can be seen at the point of use. This is a **presentation** obligation, the core property of Ch. 0 §0.1. It belongs neither to validity nor to well-foundedness.

Two further words in use, kept distinct:
- **verifiable** (Ch. 2 §2.11): recorded in enough detail (approver, reason, scope, period) for a third party to **check** validity and well-foundedness. A property of the **record**;
- **quality obligation**: the name for reading (b) below. Not a fourth property.

**The open choice: what "reasoned" is (reserved decision 4).** Three readings, all coherent with the current axiom:

| Reading | An act without a recorded reason … | Consequence | What supports it |
|---|---|---|---|
| **(a)** validity condition | confers **no standing** | strict; reasons added later cannot rescue the act; makes I-2's breach void the act | I-2 is listed as an **invariant** (Ch. 0 §0.5) |
| **(b)** quality obligation | confers standing and **carries a recorded defect** until revised | stable standing; the weakness is visible in the record | elsewhere the book treats invariant breaches as **defects**, not as voiding acts (in-place edits "break I-1 and I-7", Ch. 2 §2.10) |
| **(c)** validity condition **for specified kinds of act** | confers no standing **for those kinds**; reading (b) for the rest | follows the sources where they speak | RM-3 (ruled, Track B): changing an authoritative rule "requires explicit authorization and a recorded reason" (Ch. 2 §2.11). **This is compatible with (b) as well:** RM-3 says a reason is required, not that an unreasoned change is void |

**Presence vs adequacy.** Under any reading, the **presence** of a recorded reason can be decided from the records. Its **adequacy**, whether the reason actually supports the act, cannot be decided mechanically under any reading. So decidability does not favour any of (a)–(c).

**No recommendation is made here.** Evidence needed to choose:
- an organisational ruling on whether unreasoned acts are void;
- how RM-3's per-kind requirement is meant to generalize;
- how reasons recorded **after** the act are to be treated.

The choice concerns I-2's reasoned part only. It suggests, but does not fix, how breaches of other invariants should be treated: under (c) the answer differs by kind of act anyway. *Source sense.* In IA, "authority" names which **software object** may reject a command. Applying the test to human grants is my **analogy** (**Int**), not IA's claim.

**Validity and well-foundedness, kept apart under every reading:**
- **validity** of a rejection or adoption: an act by an actor with a cited grant covering its **activity** and scope at the time of the act, recognized once recorded (§1.13.1, §1.14.1), **plus a recorded reason under reading (a), or under (c) for the specified kinds**;
- **well-foundedness**: the recorded reasons **cite evidence** that supports the act, drawn from an assembled context whose coverage is stated (§1.17.4).

**In plain words.** *Valid* means the decision was taken by someone entitled to take it, in the proper way. *Well-founded* means it was a good decision on the evidence. A decision can be valid but poorly founded. Whether a decision recorded **with no reason at all** still counts is the open question (a)/(b)/(c). The organisation has to answer it; the sources do not.

*(This section was revised in correction rounds 1–4; the history is in the change log.)*

**Under reading (b)**, an ill-founded but valid decision keeps its standing until a recorded act revises it; its weakness is shown by its recorded reasons. **Under (a)** an unreasoned act would have no standing; **under (c)** that holds only for the specified kinds. In every reading, a decision made from a stale or incomplete context (§1.16.3) can be **authorized but not informed**. This connects authority to Chapter 0's core problem.


### 1.17.3 "Not found" is not "unauthorized"

**The idea (Src; IA L89–101, L240–244).** IA corrects an input that let an evaluator determine "whether an actor is authorized". The corrected evaluator answers only: *does a recorded grant exist that covers this principal, capability, scope and instant?* It returns found or not found. "NOT_FOUND and *unauthorized* diverge whenever a grant exists but was not recorded … an evaluator that returns *unauthorized* has converted a gap in the record into a judgement about a person." The corrected outcome set is: covering grant found · no covering grant · segregation-of-duties violation · review required.

**Development (Ded): the kinds of nothing, applied to authority (Ch. 2 §2.8.8).** "No covering grant found" can be:
- E1: no such grant exists;
- E3: the evaluator did not look where it is recorded;
- E4: the authority exists but has no record.

Only the record can be judged. Two different consequences must therefore be separated:
- **refusal to proceed**, which follows from any of the three **given INV-A3** ("absence is never permission"; stated in V3, no adopting act located, §1.14.5) or premise P-R (only recorded grants count; not adopted). The authority axiom alone does not license it, because one of the three cases, E4, is authority that exists without a record (§1.14.5).
- **a verdict that the person is unauthorized**, which follows from none of them alone.

By contrast, a **segregation-of-duties violation** is a *positive* finding: the records show one actor in two roles the laws keep apart (§1.14.3). It can be stated, because it rests on what is recorded, not on what is missing.

### 1.17.4 A policy receives its inputs; it does not fetch them

**The idea (Src; IA L200–216).** For an analysis that needs facts from elsewhere, IA prefers that the coordinating layer **load the inputs and pass them in**, "because it makes the analysis snapshot explicit and easier to reproduce". If the analysis fetches its own inputs, "what was analysed is decided inside the service and cannot be reconstructed from the call". Rule ALLOC-4: "every analysis states the world it was true of."

**Development (Ded).** This is the evaluation scope S-3 (Ch. 2 §2.8.1) made a parameter. When an analysis fetches its own inputs, its evaluation scope is **hidden**, so an empty result cannot be told apart as E1 (nothing there) or E3 (not looked at). Passing inputs makes S-3 part of the record, which is the precondition for §1.16.3's reproducibility. The same applies to context assembly (Ch. 0 §0.7): the assembled working context *is* the input set of the task, and recording it is what makes the later evaluation "was the context sufficient?" possible.

### 1.17.5 The gap: nothing governs who may read

**The idea (Src; IA L155–165, L174).** One input row, "query returns only permitted knowledge", has no counterpart anywhere in the folder. "Every invariant discussed across five documents governs *writing* knowledge … None governs *who may read what*." For a system that delivers AI context packages, "an AI context assembled without a retrieval policy will faithfully deliver whatever it can reach". IA records **OQ-25** and calls it strategic: it may need a boundary the context model lacks.

**Classification:** NEW IDEA; a gap in this book as well. Chapters 0–2 govern **standing** (what counts as authoritative) and **grant scope** (who may act). Neither says who may **know**.

**Development (Ded).** A complete theory needs a third normative question, not only two:

| Relation | Question | In this book |
|---|---|---|
| may-act | may this actor make this act, here, now? | grant scope (§1.6, Ch. 2 §2.8.2) |
| is-authoritative | does this claim currently bind? | standing (§1.2, §1.14.1) |
| **may-read** | may this actor, or this assembled context, contain this claim? | **absent** (see the refined hypothesis below: a grant value plus a claim classification) |

May-read is independent of **standing**: a claim can be authoritative and restricted (a confidential decision), or freely readable and superseded.

**Model-integrity check, with a counterexample attempt (Lookback B).** Is may-read a new dimension, or another value of an existing one? My first answer, "a new dimension", does not survive. Reading can be expressed as one more **capability** in a grant: IA's grant question already has principal, capability, scope and instant [IA L97]. So the *permission* to read is **another value** of the grant's capability, not a new relation. What a grant cannot express is the property of the **claim** that the permission is checked against: how restricted it is. That is a classification of claims, comparable to the governed classification κ of Ch. 2 §2.8.6. The refined hypothesis (**Hyp**):

> may-read(actor, c, t) ⇔ a grant with capability *read* covering *c*'s **confidentiality class** and scope is valid for the actor at *t*.

- New **value**: *read* as a grant capability.
- *Candidate* dimension: the confidentiality class of a claim. It is shown independent of **standing** only; it is **not tested** against the governed classification κ, which may already be able to carry it.
- **It is not shown necessary.** Counterexample from the independent review: the operational case below is fully expressible as **grant scope over named items**, without any class. A class becomes useful only where items are too many to name. That is a scale argument, not a necessity.

The corpus must show whether such a classification exists, and whether grant scope over items suffices.

*Operational evidence that it is needed (Src: this research's own governance).* The work behind this book runs under exactly such rules: a primary researcher's access to corpus material does not extend to an independent reviewer without separate written approval; some files are sealed or held out; credential files are blocked. These are may-read relations, enforced by people and by a permission layer. The theory being reconstructed has no place for them, while the process reconstructing it depends on them.

*Two consequences, conditional on the hypothesis (Hyp):*
- **If** read access is governed, context assembly must filter by may-read before relevance (conditional revision of Ch. 0 §0.7, step 2). Otherwise the assembled context leaks what the reader may not know, whatever its relevance.
- **A candidate new kind of nothing: withheld (E8, Ch. 2 §2.8.8; Hyp).** An assembly may be empty because the reader may not see what exists. It differs from E3 (not looked at) and E5 (does not exist). Reporting it as E5 overstates gaps; reporting it explicitly may itself disclose that something exists. Which of the two is acceptable is a policy question that the theory can state but not decide.

**Hardness and its owner (Src: IA L62–85, L173–175; Ded).** IA adds *hardness* (Ch. 2 §2.9) and notes that whether a rule conflict blocks activation "is a business decision that nobody has made", while "three documents have now assumed it blocks" (OQ-24). That is §1.14.5's warning in action: an assumption repeated across documents behaves like a decision no one took. IA also asks whether hardness belongs on the invariant or on the command (OQ-26), since "the same invariant may block one command and merely warn on another". *Answer (Ded):* on the **command**, i.e. the enforcement point. Given P-E, a block there needs an act that is currently authoritative at the time of the block and authorizes it for that scope, period and rule version (§1.14.5); whether a mandatory rule's adoption counts as such an act is reserved decision 5 (OQ-24). That is where Chapter 2 already places enforcement consequence: a property of the mechanism, not of the rule.

**Naming drift (Src; IA L246–256).** Within one document, three concepts carry two or three names each; IA calls this "a live defect: naming is diverging faster than it is being adjudicated". *Analysis (Int):* this is the fragmentation of §1.8 at the level of the vocabulary itself, and the reason this book keeps a glossary with one meaning per term.

---

## Change log

| Date | Change | Reason |
|---|---|---|
| 2026-10-18 | Chapter created; §1.1–1.4 from EP01 | pilot file 1 |
| 2026-10-18 | §1.5–1.8 from GA; Chapter 0 §0.2 revised by §1.8 | pilot file 2 |
| 2026-10-18 | §1.9–1.10 from TR; code checked independently; I-0 proposed | pilot file 3 |
| 2026-10-18 | §1.11–1.12 from DR-AB; §1.9 updated (Decision B adopted; adopted ladder); §1.10 new evidence | pilot 2, file 1 |
| 2026-10-18 | §1.13 from AMS + independent recount of the live grant record (144 grants) | pilot 2, file 3 |
| 2026-10-18 | **Audit corrections:**<br>· H-01: mapping table; "regression" claim withdrawn<br>· E-01: Track-B standing for all knowledge-transfer rulings<br>· R-06: DR-AB generalization is PROPOSED<br>· R-07: ladder disagreement stated, "not finished" withdrawn<br>· L-05, L-06: axiom domain; act × scope × time; candidate status<br>· L-18: census, rule-of-three bound withdrawn<br>· L-19, R-11: delegation conditions<br>· L-20: reserved verdicts<br>· L-23: declassification<br>· H-06: carriers vs capabilities<br>· R-05, R-08, R-09, C-07, E-07, H-08: wording, references, header | `AUDIT-ch00-02.md` |
| 2026-10-18 | §1.14 (STATE ≠ AUTHORITY, OQ-11 answered by derived standing, ARCH-INV-1, INV-A1…A4, five roles and three laws, execution plane, P-8, three layers, precision bound) and §1.15 (Claim granularity vs type; 17 concepts mapped to natures; provenance attributes; vision as method; generations) from V3; code checked: `session-resolve.php` L27–29, L225 | batch 3, file 1 |
| 2026-10-18 | Lookback A (V3): §1.14.4 conceptual target as authority order (projection sink; upward-edge test) and P-6 instance; §1.15.2 finding 3 evidence (Questions/Findings in knowledge model) | batch 3, file 1 |
| 2026-10-18 | §1.14.5 from CF: enforcement presupposes ratification (derived bound by standing), authority vs conformance gate, staging as topological order, superseded blocker, legislator ≠ evaluator, five questions as audit of the axiom, environment over instruction, numbering collision, open cost question; V3 and CF added to sources | batch 3, file 2 |
| 2026-10-18 | Lookback B (CF): §1.14.5 bound extended by binding strength; counterexample attempt (fail-closed authority gates) resolved: acts permit and acts oblige — absence fails closed for permission, cannot block for obligation | batch 3, file 2 |
| 2026-10-18 | **Correction of §1.14.1** (grant evaluated at time of act, not of use; counterexample CONSUMED grant); new §1.16 from AB (atomic change and broken boundary for grants; knowledge ≠ its subject; two-time authority; results relative to inputs; projection staleness; five records + the missing act record; over-formalization); AB added to sources; glossary corrected | batch 3, file 3 |
| 2026-10-18 | Lookback B (AB): §1.14.5 bound restated with the corrected definition of current authority | batch 3, file 3 |
| 2026-10-18 | §1.16.6–1.16.8 from IM: pinning and silent drift (pin/detect/forbid); OQ-21 answered as a missing dimension "referent time" (Hyp); global invariants need a single write path to be preventable; acceptance requires an attack; visible absence; presence ≠ validity of authority references; time-triggered conditions; IM added to sources; two overstatements softened | batch 3, file 4 |
| 2026-10-18 | §1.17 from IA: role verbs at code level; authority and information must co-occur; not found ≠ unauthorized (kinds of nothing applied to authority); inputs passed, not fetched (S-3 made a parameter); read access as a third normative relation (Hyp) with E8; OQ-26 answered; naming drift; IA added to sources | batch 3, file 5 |
| 2026-10-18 | Lookback B (IA): §1.17.5 "may-read is a new dimension" withdrawn after a counterexample: read is a grant capability value; the new dimension is the confidentiality class of a claim (Hyp) | batch 3, file 5 |
| 2026-10-18 | **Correction round 1 (trial M-04-T1):** §1.14.1 lifecycle model from V3 L297–307 added (missed omission; three axes fused; suspension needs a fourth class) and the normative premise behind the two-time counterexample stated; §1.14.5 superseded blocker made conditional on the cited ARB ruling; §1.16.3 reproducibility claim scoped to re-execution; §1.16.7 "single write path" corrected to serialization point; §1.17.2 validity separated from well-foundedness (contradicted §1.6). Found by `03-methodology/M-04-efficiency-review-20261018.md` §3–§4 | correction round 1 |
| 2026-10-18 | **Correction round 2 (targeted independent review):** §1.14.1 ending policy E made explicit (formula had decided revocation silently); static bound restricted to exceptions (act window vs binding period); §1.13.3(d) citation corrected (concerns delegated grants); "refuted" qualified; OQ-11 answer marked as reinterpretation · lifecycle model: quotation narrowed, Reviewed/Validated ambiguous, Invalidated → evidential grade, "fourth class needed" withdrawn · §1.14.5 premise P-E stated, conclusion generalized to an authorizing act (emergency freeze), min() replaced by conditions, confirmation-only soft block covered, "INV-A3 adopted" withdrawn · §1.16.2 qualified · §1.16.6 relabelled Hyp, IM option acknowledged, test stated · §1.17.2 ungranted-human case, I-2 split, I-2 quality-vs-validity choice open, IA analogy marked · §1.17.5 confidentiality class not shown necessary, consequences conditional, E8 Hyp | correction round 2 |
| 2026-10-18 | **Correction round 3** (`REVIEW-round2-verification.md`, findings A1–A3, B1–B3, C1, R1; checklist `CORRECTION-ROUND3-CHECKLIST.md`): §1.14.1 formula rebuilt (grants bound to the cited basis; ending policy P over all ending kinds, reserved; limitation L-RT on record time and annulment; AB L100 static/dynamic readings; V3 L319 counter-position; premise limited to expiry and consumption; grant/claim difference per kind; validity(c) convention; act window vs binding period; I-9 misquotation withdrawn; AM-5 dependency) · §1.14.5 rebuilt (classification no longer "derivable"; P-E delimited to normative effects; conclusion per scope and period; adoption-as-mandatory only under reading (i); conditions realization/authorization/containment; checks against freeze, configuration escalation, advisory rules; fail-closed rests on INV-A3 or P-R) · §1.16.2 updated · §1.17.2 rebuilt (I-2 three parts; readings (a)(b)(c); presence vs adequacy; no recommendation) · §1.17.5 OQ-26 answer updated | correction round 3 |
| 2026-10-18 | **Correction round 4** (independent review of packet RP-R3): §1.14.1 rewritten as one readable section with a plain-language summary (alternative cited bases; act window to first terminal ending minus suspensions; consumption constraint on P; status-with-reason output; "cannot determine" conditions incl. no grant period and incomparable scopes; "computed" narrowed; stipulation labelled; PO/ARB example withdrawn here and in §1.13.3(c); V3 L319 reading 1 more natural; AB/IM lean static; current authority ≠ standing; effective related; endings sentence corrected) · §1.14.5 rewritten (plain-language summary; normative effects in business terms; permit and fail-open; currently authoritative authorizing act; rule-version containment; granted confirmer; RS L181–183 and CF L36 tensions; report vs STOP; INV-A3 provenance; mandatory+hard; R4 evidence softened) · §1.16.2, §1.17.3, §1.17.5 aligned · §1.17.2 "reasoned" stipulated, RM-3 softened, "any invariant" removed, plain-language summary | correction round 4 |
| 2026-10-18 | **Chapter 1 review — bounded correction pass** (findings A–G and §1.16.8): §1.14.1 model rewritten (adoption act and basis bound in one expression; several adoption acts allowed, re-adoption is a new act; revocation/supersession count only if authorized; activity checked beside actor, scope and time; consumption by the act itself excluded; record view ρ explicit, annulment vs revocation; headline status with all reasons and stated precedence; nine examples re-checked) and a business-language section with the Board Chair example · §1.3 epistemic support vs standing vs normative effect · §1.6 point 2 "recorded" read as recognition · §1.13.1 authority vs grant record vs evidence · §1.16.8 test matches claim type · §1.17.2 activity in validity | Ch. 1 review |
| 2026-10-18 | **Step 1 bounded correction of §1.14.1** (two time axes; total order; authorized endings incl. grant endings; root acts; least fixed point; parameters κ, χ, ω, σ, π, υ, ι, P, ρ, C1; causal headline; business section with five distinctions, six safeguards, realistic cases) · §1.2 two senses of standing · §1.3 inference support · §1.13.1 C1 both concepts (AMS L51) · §1.14.5 P-E wording; quality-gate example corrected from the code · §1.16.8 claim types · reserved-decision numbering 1–6 · **independent review: DEFECTIVE in the formal claims; status note added; Chapter 1 fixed as baseline v1 with tracked formal issues** (`CH-01-step1-correction-and-review.md`) | step 1 + review |
