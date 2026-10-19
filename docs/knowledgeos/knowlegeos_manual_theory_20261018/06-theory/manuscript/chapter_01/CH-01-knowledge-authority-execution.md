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
> - **PC**: `docs/knowledge_tranfer/20260816_1034_policy_catalogue.md` (AI-generated, PROPOSED; external citations unverified)
> - **DR**: `docs/knowledge_tranfer/20260816_1040_decision_register.md` (AI-generated, PROPOSED; a consolidation of 29 open questions)
> - **DE**: `docs/knowledge_tranfer/20260816_1044_domain_events.md` (AI-generated, PROPOSED; with addenda on outbox and inbox; external citations unverified)
> - **AG**: `docs/knowledge_tranfer/20260816_1057_artifact_gap_analysis.md` (AI-generated, PROPOSED; gap analysis of remaining artifacts)
> - **SI**: `docs/knowledge_tranfer/20260816_1144_script_capability_evidence_inventory_v1.md` (AI-generated evidence inventory for the ARB; claims graded OBSERVED / INFERRED; key claims re-verified in code here)
> - **RS, SC, SD, EV**: see Chapter 2
> - **GKC, Meta-Model, Synth**: the RQ-002 files (Chapter 0)
> - **Background notes** used as evidence: B-05, B-11, B-12 (`01-basis/`) and KT-8 (`01-basis/B-10-knowledge-transfer/`)
>
> **Standing of all knowledge-transfer sources:** the whole track lies in the exploratory "Track B" lane [RS L22]. Rulings recorded in it are *ruled, Track B*, never adopted architecture. *(Audit H-08, E-01, E-06.)*

---

## 1.1 Knowledge does not execute

**The idea.** A clear line separates *knowledge* from *capabilities*:

> **Knowledge does not execute. Capabilities execute. Knowledge constrains and informs.**

EP01 cites this as invariant "I-11" of the KnowledgeOS Relationship Ontology (2026-08-02) [EP01 L34–36]. ~~Cited-only~~ *(updated after review C4 F-19):* the Relationship Ontology is now read (S0021 L115; Ch. 3 §3.6, CON-0039). Its restated wording is "KNOWLEDGE DOES NOT EXECUTE. Capabilities execute." Note SEQ-0012: this section draws the knowledge/capability line by "who executes", so the invariant is close to definitional.

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

**Corpus evidence: a decision is a specification (batch C-1; revision RC-5).** The engineering canon corrected its own vocabulary on 2026-07-11: "a DDD Domain Service is executable; a decision is a specification" [Src: S0006 L22, quoting the Engineering Decision Model; E-S0006 Q7]. A decision says *what must be resolved and by which rule*; it does not run. This places decisions on the knowledge side of this section's line, beside rules. **Src** for the quote; **Int** for the placement.

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

**Corpus evidence: "provenance × standing" (batch C-1; revision RC-6).** The corpus writes the rule as a formula, "`authority` = provenance × standing" [Src: S0002 L37], and gives its parts: "authority = immutable PROVENANCE × act-changed STANDING" [Src: S0003 L56; E-S0003 Q6]. It supports it externally with W3C PROV and the data-catalogue practice that **lineage and a "certified" flag are "two independent fields in every major catalog"** [Src: S0007 L26; E-S0007 Q8].
- **What "×" means is not defined in the corpus** (TODO T1-31).
- **Int:** the only reading consistent with "immutable" and "act-changed" is a **pair** (provenance, standing): two components, one of which never changes while the other changes only by acts. A numeric product would let one factor compensate for the other, which no source allows. This is the reading this section already uses.
- **Repetition note:** S0002, S0003 and S0007 are from one programme on one day and cite the same Engineering Progression Model. They count as **one** line of support, not three.

**The primary source, now read (S0012; revision RC-24).** The rule this section cites second-hand ("EP01 credits this to a 'Lifecycle Gap Analysis'", cited-only) is in that document. The source asked whether the `authority` field needs a state machine and **withdrew the question as ill-posed**:
- "`authority` is TWO dimensions in one field — immutable PROVENANCE (`generated`, `derived`) × act-changed STANDING (`authoritative`, `provisional`, `historical`)";
- "Provenance CANNOT have a state machine; standing changes by governance ACTS." [Src: S0012 L152; E-S0012 Q6, Q7].

**Status:**
- The rule's standing in this book moves from **cited-only** to **Src** (a candidate document of 2026-08-02).
- **Int:** the value sets given there read "×" as a product of **two value sets**, i.e. a pair. This **partly resolves TODO T1-31**; the source does not define the operation.
- *Qualifier (RC-24a):* S0012's withdrawal note itself points to a later record, "Superseded by PM-1/PM-2 — record: KnowledgeOS_Engineering_Progression_Model.md §4" [S0012 L152]. The rule's fuller primary statement is **S0018** (the Progression Model, read and recorded; integration pending). S0012 is multi-temporal: L152 was added after the rest of the file.

**The primary statement (S0018; revision RC-36).** The Engineering Progression Model (2026-08-02) states the rule in full:

> "Authority = an immutable PROVENANCE fact × a mutable STANDING judgment. Attestation changes standing. Nothing changes provenance." [S0018 L94; E-S0018 Q5]

| Part | Values | Can it change? | Concept |
|---|---|---|---|
| **provenance**: "where did it come from?" | generated · derived | **never**: "origin is an immutable historical fact" | `[CON-0017 · invalid]`: the two values are not a partition (a generated index is also derived), and original human work has no value. The core holds: origin is immutable and ≠ standing. Candidate repair: producer (human · tool/AI · external) × derivation (original · derived from {s} at t) (consolidation review) |
| **standing**: "how much should I trust it?" | authoritative · provisional · historical | **only by a governance act**; "provisional … carries no authority *yet*" | `[CON-0018 · invalid]` (partly valid: the source uses "standing" in at least three senses, e.g. "reusability standing" [S0018 L46], "evidential standing" [L105]; T1-07, T1-52; RC-36a) |

[Src: S0018 L77–83]. The whole is **authority** `[CON-0016 · validated]`.

**What follows:**
- **Why there is no state machine for authority:** half of it is a fact and cannot change; the other half changes by acts, "which the platform records as act-logs (rulings, dispositions), not as workflows" [S0018 L87].
- **Both at once:** a signed financial statement is *derived* in provenance and *authoritative* in standing "simultaneously and without contradiction" [S0018 L96].
- **Int:**
  - this is the **primary source** this section had only second-hand; it is **consistent with** the book's reading of "×" as a pair (a fact and a judgment), but it still writes "×" without defining the operation [S0018 L94]. TODO T1-31 is therefore resolved **by interpretation (Int)**, not by evidence (review C3 F-01; RC-36a);
  - it confirms the review finding that provenance is **descriptive** (RC-18a).
- **Status:** **Src** (CANDIDATE; the separation of the two questions is proposed to the ARB as PM-1, not decided).
- **Where the split also appears, and why (S0022; revision RC-51; "first" withdrawn after review C4 F-18, since S0012 L152 states it the same day).** In the cross-product validation, the split arises from a **counterexample**, not from a model. A hospital discharge summary and a signed financial statement are generated and authoritative. The author's diagnosis: "`derived` describes provenance; `authoritative` describes standing. A signed derivation is both." [S0022 L93; E-S0022 Q15]. This is the empirical motivation for the pair. S0018 and S0022 are both dated 2026-08-02 and their order is not established; they are one line of evidence, not two.

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

**Refinement: what closes a question (Src: DR L14–27, L61–123, L127–133; Ded).** A later consolidation of 29 open questions sorts them by **what they are waiting for before they can be closed**:
- **nothing**: "no unresolved dependency; needs only a human act" [DR L20], for example a naming decision (13 of 29);
- **another decision first**: two root questions. The register counts 11 items blocked by them, but four of those are not questions (a technology stack, gates, field lists, row statuses), so the roots block **7** questions [DR L86–105];
- **work not yet done**: an analysis or a completed artifact;
- **evidence that does not yet exist**: e.g. a second independent execution.

These are four kinds of **prerequisite**, and they can combine. Some questions need **all** of several (one waits on two decisions [DR L103, L105]); others need **any one** of several ("a second lineage **or** external review" [DR L123]). In the register, the **closing step itself is always an act**.

Its finding is that **13 of 29 were never blocked**. They stayed open because every document raised them and none was authorized to close them [DR L199–201]. *Development (Ded):*
- "what evidence would close it" in the paragraph above is too narrow. A closing criterion should name **what must be in place before the closing act** (nothing, a prior decision, work, or evidence). Questions form a **dependency graph**, so whether a question is blocked can be computed;
- *Two closure rules, by kind of question (Ded; supported by the register's own examples):*
  - a **decision question** (what the organisation will do or adopt) closes by a **valid act** alone, once its prerequisites are met;
  - a **factual or research question** (e.g. "is the link verified?" [DR L122]) closes only by **evidence meeting the standard of its kind** (§1.16.8), **recorded** by an act. No act can make it true; the act records that the evidence meets the standard.

  Whether some factual questions may be treated as closed by evidence without any recording act is not settled here;
- **an answer is not a closure.** One question was *answered* by building an artifact, yet is "awaiting only closure" [DR L127–133]. By the authority axiom (§1.6), an analysis can **answer** a question; only an act **closes** it. A question answered but not closed should be recorded as such, not left "open" or silently treated as decided;
- two questions may be **the same question** in different words [DR L159–160], so questions need identity, like any knowledge item (§1.10).

---

## 1.5 Knowledge is not its carrier

**Source added.** **GA**: `docs/knowledge_tranfer/20260816_1108_architecture_gap_analysis_knowledgeos.md` (AI-generated, PROPOSED; one sentence amended by the ARB).

**The idea.** "Engineering knowledge ≠ engineering artifact. One is the thing; the other is a carrier of it." GA cites this as the stated purpose of the KnowledgeOS Meta-Model [GA L147–151] (**cited-only**). It names an error class this distinction makes "impossible to phrase": **reading the absence of an artifact as the absence of the thing**. GA counts five recorded instances, four in the KnowledgeOS documents themselves, e.g. "there is no lifecycle" when lifecycles existed without a document describing them [GA L153].

**Verification (Ind).** This is GKC's founding move, stated sharper:
- a knowledge coordinate exists "independently of any artifact" [GKC L18];
- representation is one dimension among four [GKC L13].

GA applies it to dissolve a question that blocked a whole document set ("is the PKS software or specifications?", 49 references). That question asks which *representation* something has and treats the answer as what it *is*. GA shows knowledge carried at once in YAML (rules, schemas, statuses), Markdown (constitutions, models) and JSONL (evidence streams) [GA L157–169]. GA lists PHP as *capabilities acting on knowledge* and shell/JS as *runtime adapters*. Under I-11(RO) those **execute**, so they are capabilities, not knowledge carriers. *(This is sense A of "execute"; Ch. 3 §3.8 C5 records two other senses used in the corpus, fork F11, forward reference.)* *(Corrected after audit H-06; my earlier "the same knowledge … in PHP and shell" contradicted §1.1.)* The rows are **Src** (GA's observation). **The dissolution is valid (Ded):** if representation is an independent dimension, no answer about representation can settle a question about nature.

**What a runtime adapter is: the four-layer runtime model (Src: S0031, 2026-08-02).** The corpus already separates four layers between a governance rule and the file a tool reads [S0031 L104–112]:

| Layer | Vocabulary | Example |
|---|---|---|
| governance policy | governance terms: authority, commission, promotion | the rule itself |
| capability mapping | **tool-neutral** terms: deny, request, approval, allow, immutable, governed | what the rule requires of any runtime |
| **runtime adapter** | tool terms | how **one** runtime implements each mapped capability |
| concrete configuration | a file | `.claude/settings.json` |

- **Definition (this book):** a **runtime adapter** is the layer that implements the tool-neutral capability mapping in one particular tool's terms. The configuration file is not the governance: ".claude/settings.json is an ADAPTER. It is not the governance model." [S0031 L116]
- **The source uses "adapter" in more than one sense (Src).** The same line calls the configuration file itself the adapter [S0031 L116], while the diagram puts the file in the fourth layer, below the adapter [S0031 L104–112]. The book's definition takes the **layer** sense (the third layer). The sentence quoted above is read as "the file is not the governance", and its other half, "the file is the adapter", is a different sense of the word.
- **Why four layers, not three (Src):** the capability mapping exists so that tool vocabulary "cannot LEAK UPWARD" into the governance model [S0031 L118]. **Int:** this is an anti-corruption layer in the DDD sense, placed between governance and tooling.
- **Portability is claimed, not shown:** "another runtime could replace Claude Code and only the adapter would be rewritten" [S0031 L117]. There is only one runtime so far, and the trigger for the tool-neutral layer is "when a second runtime adapter exists" [S0031 L126]. The concept registered is the definition, `[CON-0084 · invalid]` (partly valid: usable once the two senses of "adapter" are kept apart; review C6); the portability claim is not part of it.
  - *Counterexample attempt:* the "tool-neutral" vocabulary (deny, allow) reuses the words of the one tool it was abstracted from. Whether it is really neutral can only be shown by a second tool.
  - So the **definition** is clear, and the **portability claim** is a hypothesis (Hyp).
  - **Not instantiated (S0034, later annotation, 2026-08-02):** "no Capability Mapping artifact exists" [S0034 L28]. The configuration already uses the supposedly tool-neutral words (deny, ask, allow), so "the mapping is nearly the IDENTITY function, and an ACL that performs an identity mapping is not an ACL" [S0034 L28].
    - **What this shows (Int, scoped):** the tool-neutral layer exists today only as a description; it has no artifact of its own. The source's own vocabulary list is a second sign: "deny" appears among the tool-neutral words [S0031 L108] and among the Claude-specific words the layer is meant to keep out [S0031 L118].
    - **What it does not show:** that the layer is wrong. With one runtime, a mapping that is **nearly** the identity is what an abstraction drawn from that one tool would be expected to look like. The layer is **redundant at n=1**, and its refutation is **deferred** until a second runtime exists, as the falsification report (S0011) already recommended for this very finding: "untestable at n=1", DEFER, not rejection (§1.11 below, RC-23a).

**Development (Ded).** The principle has a direct consequence for *empty answers*, the theme of Chapter 0 §0.7(b). "No artifact found" can mean at least three different things:
1. the knowledge does not exist;
2. it exists but has no carrier yet (tacit, or unwritten);
3. it exists in a carrier the search did not reach.

Only (1) is an absence of knowledge. Treating (2) or (3) as (1) is exactly GA's error class. **This sharpens the Zero/Gap hypothesis:** a theory of "zero" for KnowledgeOS must at least distinguish *no knowledge* from *no carrier* from *not found*. **Hyp** for the corpus; the logical distinction itself is **Ded**.

**Metadata is not knowledge (S0010; revision RC-18, corrected RC-18a after independent review).** A corpus synthesis of 2026-08-02 adopts the name **metadata ≠ knowledge**, "descriptive vs prescriptive" ("what exists" vs "how it should be used") [Src: S0010 L87; E-S0010 Q6]. *That this is a third distinction beside knowledge ≠ carrier and provenance ≠ standing is this book's reading (Int), not the source's.*
- Example: a schema field says `Revenue`. The *knowledge* is the approved definition, owner, allowed usage, exceptions, caveats, sensitivity and source of truth [Src: S0010 L37; the example and the name come from the source's **second-hand** summary of an external article, L35–37].
- The source finds the distinction already implemented: a document's `knowledge_type` is metadata; its `authority`, `owner`, `audience` and `next_review` fields are knowledge. The `authorities.yaml` header states: "Authority answers: how much should I trust this, and where did it come from?" [Src: S0010 L88–90; Q8, Q9].
- **Not the portability axis:** mapping metadata/knowledge onto method/binding/evidence (§1.11) is wrong, because "A binding can be either descriptive or prescriptive" [Src: S0010 L87; Q10]. The two are orthogonal.

**Two knowledge bodies.** The same source finds that the governed documentation corpus (in the knowledge graph) and the operational registers a capability actually reads (outside it, `governed-registers.yaml`) are **two bodies**, and "no single Knowledge Layer spans both" [Src: S0010 L129; Q13].

**Status:**
- **Src**; the article it discusses was explicitly **not read** by its author [Src: S0010 L13; Q4]. The *implementation* claims (which fields exist) rest on repository evidence; the *name* and the example rest on a second-hand summary.
- **Challenge (Ded; RC-18a):** the source classes `authority` as knowledge (prescriptive) because the header asks "how much should I trust this, and where did it come from?". That step is **unargued**:
  - "where did it come from" is **provenance**, a *descriptive* fact (§1.2: immutable, set at creation);
  - "how much should I trust this" (standing) is closer to prescriptive.
  
  So the `authority` field, being two dimensions (§1.2, RC-24), **straddles** the metadata/knowledge line rather than falling on one side. The earlier sentence "provenance and standing both fall on the prescriptive side" is **withdrawn**.
- **Open:** whether metadata/knowledge is a genuine axis or a re-description of standing vs everything else (TODO T1-44).
- **Open:** where, if anywhere, the two bodies meet (TODO T1-40).

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

Each "X ≠ authority" is an instance of this theorem for one property X. *(Example from a later source, added R1-9:* "a similarity score is only a relevance signal. It is not permission, authority, applicability, or truth" [AG L53]. In retrieval terms, embedding similarity measures topical closeness, not whether a claim **applies** here, **binds**, or is **true**. So similarity ranking can never **replace** filtering by visibility, applicability and standing. Filtering must have happened **before the result is used**, whatever the order of computation.) The list is open-ended: the theorem predicts further instances, such as "popularity ≠ authority" and "age ≠ authority", without new rules. A *counterexample* would be a property that by its definition includes a recorded act, e.g. "ratified". Such a property is not a counterexample but the act itself.

**Consequence.** The theory needs only **one authority axiom** (standing changes only by recorded acts), not a growing list of prohibitions.

> **Refined (Chapter 2 §2.8.2; restated after audit L-05, L-06, E-03).**
> 1. **Domain.** The axiom is about **normative status**: either the *standing of a claim* or an *actor's permission to act*. "Relevance ≠ authority" is an instance for claims. "Active ≠ authorized", "existence ≠ permission" and "ownership ≠ authority" are instances for actors. They need the companion premise "permission exists only through a grant that references an act" (§1.13). Because the theorem follows from the axiom by definition, its "further instances" (popularity, age) are **consequences, not predictions**; they test nothing.
> 2. **Conditions on a valid act:** the act is recorded (strictly, the condition for the organisation to **recognize** the resulting standing change; the act's existence does not depend on its record, §1.13.1), **and** the subject scope of what it changes lies within the actor's **grant scope**, **and** the act falls within the grant's **validity period**. Authority = act × scope × time.
> 3. **Open:** grant scope and canon scope (Ch. 0 §0.3) live in different value spaces. Containment between them is undefined until a mapping exists.
> 4. **Standing:** the ARB calls the containment chain a "**candidate relationship … not an adopted universal law**" [SD L317]. The scope and time conditions are therefore **Ded**, not ruled.

**Evidence is necessary, not sufficient: two keys (batch C-1; revision RC-7).** The programme's own rule is "evidence EARNS · governance GRANTS · promotion requires BOTH" [Src: S0002 L37; E-S0002 Q8]. This section says no property except an act confers standing. The rule adds the converse condition for promotion: **an act of promotion without evidence is also not the programme's practice**. In business terms there are two keys: evidence shows the knowledge is worth trusting; an authorized person turns the authority key. Neither opens the door alone. The corpus aligns this with technology-readiness levels and GRADE, both of which "separate evidence accumulation from adoption decisions" [Src: S0007 L28]. **Src** for the rule; **Int** that it is a necessary condition on acts, where the source states it for promotion only. *Consolidation review (2026-10-19): the rule is VALIDATED for promotion along an evidence ladder; the general Int form is **INVALID**. Counterexamples: questions that "need only a human act" (§1.4), freezes by declaration [S0017 L55], and genesis, where no prior evidence exists [S0024 L159–162]. TRL and GRADE support separating the two keys, not requiring both.*

**Why the two keys cannot replace each other: granted vs earned (S0018; revision RC-37).** The Progression Model gives the reason, as a difference of **cause**:

| Kind of change | Moved by | Reversible by |
|---|---|---|
| governance-act progression `[CON-0019 · validated]` | a decision by a named role | a further act (supersession) |
| evidential progression `[CON-0020 · invalid]` (survives as "cannot be decreed"; re-analysis and staleness also lower it, §4.1) | accumulating evidence; "nobody can decree it" | refutation only, "never by decision" |

"A GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned." "No ARB ruling can make an observation Replicated. No amount of evidence can make a document frozen." [S0018 L61–68; E-S0018 Q8].

**Ded (corrected after review C3 F-03; RC-37a):** the two keys are **causally** distinct, not just procedurally. **No act can decree an evidential progression, and evidence alone cannot effect a governance-act progression** [S0018 L61–62, L68]. This is a ban on *decree*, not on *causing*: work that an act authorizes does produce evidence ("P-7 produces P-10: work produces evidence" [S0018 L106]), and evidence does produce *evidential* standing [L105]. The source's own example ("No amount of evidence can make a document `frozen`" [L68]) concerns a **status** value, not standing. So this section's axiom (only acts change standing) and its converse (promotion also needs evidence) cannot be collapsed into one rule. A single lifecycle "would require either that evidence be grantable by decree, or that decisions wait on evidence that may never come" [S0018 L121]. The full taxonomy of change belongs to the planned Ch. 4.

**Canon is human-made; AI output tops out at candidate (S0013; revision RC-27).** The corpus's inventory of 2026-08-03 states the axiom as a classification rule: "Canonical" means "ADOPTED, FROZEN, RULED, or ENFORCED BY HUMANS", never "validated by this programme". The reason given: "engineering never accepts its own work" (R-34) [Src: S0013 L22; E-S0013 Q6, Q8].
- **Its consequence, observed:** all 12 canonical concerns are human-made, and every product of the AI discovery programme sits at Candidate or below. "That is the system working, not modesty" [Src: S0013 L22, L112].
- **Int:** this is this section's axiom applied to a whole body of work. No volume of AI-produced validation crosses into canon without a human act.
- The source also separates **knowledge states** from **decision outcomes**: "Rejected is an outcome, not a state; Open Question is unresolved work, not knowledge" [Src: S0013 L17] (evidence for TODO T0-07).

**The engineering canon keeps authority acts out of its decision catalogue by design.** The Engineering Decision Model indexes eight engineering decisions and deliberately excludes ADR-making, capability approval, authority acts and promotion rulings: "Promotion is a human governance act and is never automated"; "the AI evaluates and recommends — authority remains with governance" [Src: S0006 L36, quoting the model; E-S0006 Q10, Q11]. This is a statement of the pattern in §1.14.3 (AI recommends; humans decide), quoted from the engineering canon. *(Corrected RC-7a after independent review: S0006 dates only three of its quotes to 2026-07-11 (L21, L22, L88); these quotes are **undated** in the source, so no claim that they predate the August corpus is made.)* **Src.**

---

## 1.7 Visibility: derivation may narrow, never widen

**The idea.** GA reports one gap that *neither* document set had seen: nothing governs **who may read what** [GA L257–263]. The 16 August work proposed the rule "**derivation may narrow visibility but must never widen it**" [GA L261]. **Src** that the gap exists, per GA's search, which I have not repeated.

**Development (Ded).** Let vis(c) be the set of principals allowed to read claim c. For a claim d derived from sources s₁…sₖ, the rule says:

> vis(d) ⊆ vis(s₁) ∩ … ∩ vis(sₖ)

That is, a derived claim is never more visible than its least visible source. Three consequences:
1. **Summaries and AI contexts inherit restrictions.** An assembled working context (Chapter 0 §0.7) may contain only claims every recipient may read.
2. **Visibility is a fourth label** next to provenance, evidential grade and standing (§1.3). It is orthogonal: a claim can be authoritative and restricted, or public and provisional.
3. **A consequence for quoting** (related to "never a copy", Chapter 0 §0.8; not a conflict, audit L-23). Legitimate *declassification*, e.g. publishing an aggregate, must be possible, so the rule reads: vis(d) ⊆ ∩ vis(sᵢ) **unless widened by a recorded act**. when a restricted claim is quoted into a broader document, the quote is a derivation and must keep the restriction. Most document systems silently widen.

**Necessary, not sufficient: inferred relationships (Src: AG L63–69; Ded; added R1-8).** A later source points out that "a graph projection must not reveal restricted relationships merely because the connected nodes themselves are visible", and that **an edge inferred from two visible nodes can disclose a restricted fact that neither node discloses alone** [AG L65, L69]. *Analysis (Ded):* the example covers **two cases**. (i) A **stored** restricted edge between two visible nodes: this is incomplete lineage, and the intersection bound handles it if the edge itself is counted among the sources. (ii) An **inferred** edge, computed from visible items only: the aggregation case. The rule above bounds a derived item's visibility by its **sources**. It cannot see that a **combination** of permitted items may itself be sensitive: the aggregation problem known from database security. So the bound is **necessary but not sufficient**. Derived items (edges, chunks, summaries, assembled contexts) need their **own** visibility classification, which may be stricter than the intersection of their sources. Where no such classification exists, the safe reading is to treat the derived item as undetermined for visibility, not as visible.

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

**When structure is justified (Src, GA L401, "LA-4").** "A responsibility becomes a component only at n ≥ 2, exercised by another." This is a concrete threshold for the parsimony principle of Chapter 0 §0.8 (i): one occurrence is an observation; a second, independent use justifies structure. *Added in Review A.* **Full form (S0028):** a responsibility becomes a component only when "(1) exercised at least once · (2) exercised by someone other than the originator · (3) a repeatable pattern extracted from ≥ 2 instances" [S0028 L142] `[CON-0076 · provisional]`. Where a method does not declare its interface, condition (3) is also what makes it **identifiable** (Ch. 6 §6.1 C1, forward reference). The rule governs claims of reuse across contexts (Ch. 5 §5.3 C2).

**The loop has never closed (Src, GA L376–378).** The evidence layer (observation → recommendation → decision → outcome → assessment) has fed back into governance or capability **zero times**. Chapter 0 §0.7 called need → evaluation → gap → improvement a feedback theory of knowledge growth. **It is designed and partly built, but has never once operated end-to-end.** This strengthens Chapter 0's weak point 3 (§0.8) with a measured count.

**The repository as evidence for its own theory (Int).** GA's main empirical finding is that the August work re-derived, in one day, an ontology that already existed three times, plus several mechanisms already running, because nobody searched first [GA L438–455]. The ARB amended the conclusion: retrieval failure caused duplication, *and* real gaps exist [GA L492–496]. This is a natural experiment confirming the foundations' **scent principle**: knowledge that cannot be found is functionally absent [Synth L75]. It also shows the cost: duplicated, mutually unaware ontologies. **The KnowledgeOS programme suffered the exact failure KnowledgeOS is meant to prevent.**

**Lead for the corpus (cited-only).** GA reports the KnowledgeOS corpus has its own meta-model: **6 dimensions (2 new, 1 rejected on test), 13 concepts, 11 invariants** [GA L49–50]. GKC has 4 dimensions. Which dimensions were added or rejected, and why, is a primary question for the corpus chapters.

> **Partly answered by the corpus (S0009; revision RC-17).**
> - S0009 names **evidence-status** and **operational-state** as "the two candidate additions" [Src: S0009 L69] (compare Ch. 2 TODO T2-04). *That these are GA's "2 new" dimensions is an inference (Int), not stated by either source (corrected RC-17a after independent review).*
> - "13 concepts" matches the Relationship Ontology's assignment of kinds to 13 concepts [Src: S0009 L43].
> - **The rejected dimension is not named in S0009.** It stays open.
> - The meta-model itself (kinds × dimensions) is in Ch. 0 §0.3 (RC-16).
> - Two related findings of the same source:
>   - the proposed meta-edge **"Domains own Capabilities" is refuted**: "no artifact binds any CAP-n to a PD-n; capabilities are anchored to DP-n" [Src: S0009 L100; E-S0009 Q13] (see §1.11 and Ch. 2 §2.9);
>   - the canon keeps "**two lifecycles, never conflated** — governance-ARTIFACT vs governed-WORK" (GEP-F1, cited) [Src: S0009 L53; Q14], which is evidence for §1.14.1's separation of a record's lifecycle from the work it governs.

**Corpus evidence: every part travels, the whole does not (batch C-1; revision RC-8).** The corpus ran the test "if PublicDigit disappeared, what of KnowledgeOS survives?" **as an experiment**: a fresh session received only the candidate kernel and tried to bootstrap a different product.
- **Every element survived unchanged** (S0002's account). *Annotated after review C4 F-03 (RC-8a):* the primary report (S0025, Ch. 8 §8.1) gives per-document results that contradict this: ES-005.1 "names PublicDigit and was ignored", two PKS methodology documents were ~90–95% source case law, and the methodology documents were 73% of the kit and "mostly non-portable" [S0025 L90–97]. The Ded below stays valid as logic; its instance is weaker than S0002 reported.
- **The set still failed:** there was no route to start a new knowledge space, the roles could not be filled by one person, and one piece was product-coupled [Src: S0002 L50–52; E-S0002 Q9].

The corpus names the lesson **"survival ≠ sufficiency"**. **Ded:** portability is a property of the **set**, not of its elements; elementwise independence is necessary and not sufficient. **Status:** one experiment (n = 1).

**The loop is broken at one place.** The full claim is a loop: discover → model → govern → **generate** → evolve a product knowledge space → guide product engineering → build software → collect evidence → improve KnowledgeOS [Src: S0005 L55–57].
- On 2026-08-03 its **tail is observed**: software built, evidence collected.
- Its **head is not**: generating a governed knowledge space has **zero instances inside the platform and none found outside** [Src: S0005 L23, L72; E-S0005 Q6, Q7].
- The corpus adds that the broad claim is **harder** to prove than the narrow one [Src: S0005 L76; E-S0005 Q8].
- *Not found ≠ proven absent:* the outside search scope is not stated (TODO T1-32).

**The second-product bar is the programme's own.** External reuse practice asks for three uses before a component counts as reusable (the "rule of three"), "stricter than our n≥2 bar" [Src: S0007 L25; E-S0007 Q7]. LA-4's n ≥ 2 above is therefore the corpus's own bar, "the precise bar is ours" [Src: S0007 L25], not the literature's standard. *(Corrected RC-8a after independent review: "deliberate" was not in the source.)* The corpus also applies the bar to *generalisation claims*: findings seen on one platform are marked n = 1 and held as hypotheses [Src: S0001 L31; S0003 L89].

**Why the loop has never run: an unfired entry event (S0010; revision RC-19).** A day earlier (2026-08-02) the corpus diagnosed the stalled feedback loop differently:
- the mechanism for turning evidence into reusable capability is "governed in six places and has never executed once" [Src: S0010 L121];
- its single blocker is **an unfired trigger**: the harvest question is asked at a retrospective, the recorded trigger is the "PB-004 retrospective", and that retrospective has not taken place. "The loop has no entry event, not a missing participant" [Src: S0010 L52–56; E-S0010 Q11];
- the source calls the loop "the ONLY OKF insight whose failure would invalidate the platform's central premise", "that operational evidence becomes reusable engineering capability" [Src: S0010 L123]. *(Corrected RC-19a: it is the only one among the external article's insights, not the only finding overall.)*

**Has evidence ever flowed back? The corpus disagrees with itself (corrected RC-19a after independent review).**

| Source | Date | What it says |
|---|---|---|
| S0010 | 2026-08-02 | the loop: "0 traversals"; the mechanism "has never executed once" [Src: S0010 L31, L121] |
| S0008 | 2026-08-03 | evidence improves KnowledgeOS "PARTLY — B-1's fact: n≈3 informal, ≈2 formal, PublicDigit→PKS none"; the chain's back-edge "—(n≈3)→ PLATFORM" [Src: S0008 L69, L76] |
| S0002, S0005 | 2026-08-03 | the back-edge "n≈3 informal / ≈2 formal" [Src: S0002 L36; S0005 L66, L70] |
| S0013 | 2026-08-03 | **rejects** "'0 traversals' of the evidence back-edge (as a generalization)", falsified by the Phase B blind review (n≈3: R-36 · R-41 · R-63) [Src: S0013 L101] |
| S0014 | 2026-08-03 | **"0 traversals … RETIRED as generalized"**; *agreed engineering fact*: the back-edge "has run ≈3 times, informally" (R-36 · R-41/ES-004.3 · R-63), the **formal** promotion ladder ≈2 (R-36; R-39 as a recorded exception); PublicDigit → PKS: none. The Decision Authority's acknowledgement is required (IR-5) [Src: S0014 L43; E-S0014 Q7] (revision RC-31) |
| S0028 | 2026-08-02 | "0 traversals" [S0028 L57]; the transition evidence → improve KnowledgeOS "EMPTY … never traversed" [S0028 L182] |
| S0026 | 2026-08-03 | "the back-edge (evidence→platform) is the loop's thin edge (n≈3)" [S0026 L103] |
| S0027 | 2026-08-04 | OE-KOS-3: "formally traversed for the first time — at the INSTRUMENTATION level", "formal n=1"; the informal canon-level n≈3 "stands separately" [S0027 L80]. Conflicts with "≈2 formal" above: a different definition of "formal" (Ch. 4 §4.1, CON-0070; revision RC-72) |

**Reading.** On the later record (08-03), the generalization "never" is **rejected**: a few informal, and about two formal, back-flows exist. S0010's "never executed once" may refer to the **formal harvest mechanism** (asked at a retrospective that had not run) rather than to every back-flow. That is a reconciliation by scope (**Int**), not stated by S0010.
- The loop's **head** (generation) remains at n = 0 in every source (RC-8).
- My earlier sentence placing S0010's stall on "the evidence → platform back-edge" was an unlabelled interpretation, and the earlier heading "no contradiction" is **withdrawn**.
- **Int:** a stalled process should first be checked for a trigger that exists and has fired, before the actors are blamed.
- **Open:** was the retrospective ever run (TODO T1-39)? Which back-flows count as "formal" (TODO T1-45)?

**"Automation does not approve itself."** The same source cites the engineering protocol (EEP): "Final authority is human — automation plans, implements, verifies, and recommends; it does not approve itself" [Src: S0010 L110; Q12]. This is a canon statement of §1.6 and §1.14.3. *(Corrected RC-19a: the quote is undated in the source; no claim of precedence over the August corpus is made.)*

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

**A census of enforcement modes (Src: SI L206–231, graded OBSERVED by SI except K-14 and K-15, which SI grades INFERRED; added R1-11, corrected after the batch review).** An inventory of the platform's own mechanisms records **how** each of 20 contracts is enforced. This is a census of **enforcement mode**, not of realization level: Ch. 2 §2.9 keeps the two apart (an executable check can still be advisory). The counts:
- **compiled**: 1;
- **blocking**: 2;
- **checked by a gate, threshold, error, enumeration, structural rule, fail-closed guard, or an "exit 2 = unruled" result**: 8;
- **advisory or reminder**: 6;
- **convention only**: 3.

The single compiled contract is "knowledge feeds authority; it never holds it" (ARCH-INV-1). *What "compiled" amounts to (verified by reading the code, not by execution).* The verdict enumeration declares all seven values. `Verdict::emittable()` is a list of the four values a mechanism may emit (`Verdict.php` L31–33), and the actual enforcement is a **runtime exception** when an `Assessment` is created with a reserved value (`Assessment.php` L38–42). So it is **one runtime guard on one code path**: an `Assessment` object cannot carry a review-act verdict. It is **not** a compile-time guarantee, and it does **not** establish the invariant "knowledge feeds authority, never holds it" in general. Other code can reference reserved values directly, other vocabularies are unconstrained, and other mechanisms can produce the effects of authority (e.g. a gate switched to blocking by configuration, §1.14.5). *Reading (Int):* 11 of the 20 contracts are enforced by something stronger than advice or convention; 9 rely on advice or convention. *Two facts about verdict vocabularies, stated as facts only:* `AssessmentService` emits SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE as plain strings (`AssessmentService.php` L10–41), and a source proposes a third vocabulary with UNKNOWN and REFUTED [PC L175]. Whether the governed verdict vocabulary should admit partial support (OQ-28) is not decided by either fact. Decision B above keeps vocabularies of different kinds apart.

**One corrected chain, read here as four strands (S0008; revision RC-14, corrected RC-14a after independent review).** The corpus's meaning ledger replaces a proposed single chain ("KnowledgeOS creates PKS contains knowledge organized by ontology used by capabilities executed through runtime produces evidence improves KnowledgeOS") with "**the corrected canonical chain**", written on four lines [Src: S0008 L71–76]. The source keeps the word *chain* and names no strands. **The strand names in the first column are this book's reading (Int).**

| Strand (Int) | Line of the source's chain |
|---|---|
| normative | mission (enacted) → strategy → principle → **design policy** → capability |
| representational | knowledge —represented by→ artifact —contained in→ knowledge space —generates→ projection |
| executional | capability —realized as→ script; —runtime-faced by→ **execution asset** → runtime adapter |
| evidential | work —produces→ evidence records —harvested by→ evidence protocol —(n≈3)→ platform |

The source grades each edge of the proposed chain [Src: S0008 L63–69]:
- "PKS contains engineering knowledge" is **wrong**, because a product space must not hold reusable method (I-7; S0008 does not name the document that numbers I-7);
- "creates PKS" is a **hypothesis**, not rejected (n = 0: "the generator slot is a person");
- "capabilities executed through runtime" is **imprecise** in the table: it hides that enforcement is runtime-side while capabilities are advisory. The closing counts it among the wrong edges [S0008 L108];
- "runtime produces evidence" is **reattributed** (work produces evidence; the runtime hosts the work), and "evidence improves KnowledgeOS" holds only **partly** (n≈3 informal, ≈2 formal);
- the two remaining edges hold.

**Int:** this ladder is the normative strand; the strands meet only at the capability (normative ↔ executional) and at work (executional ↔ evidential).

**Where a prompt belongs (resolves T1-36).** S0002 found that no document placed "Prompt". The meaning ledger answers it: a prompt is an **Execution Asset (`AST-nnn`)**, a registered runtime asset "with a runtime moment and five-question lineage". The registry already holds a prompt template as AST-011, with a closed vocabulary of moments (SESSION_START, PRE_ACTION, POST_ARTIFACT, SESSION_END, ON_DEMAND) [Src: S0008 L47–53; E-S0008 Q14]. What is missing is **one link**: the capability model never says that a capability's runtime-facing artifacts are ASTs (SC-6) [Src: S0008 L55]. **Status:** **Src**; the AST registry is reported as OBSERVED in the source, not verified here.

**One meaning per term.** The ledger's premise is that "rediscovery happens because meaning is lost, not files" [Src: S0008 L12; E-S0008 Q13]. Its criterion is one meaning, one responsibility, one owner and one place per term [Src: S0008 L10]. This is the semantic counterpart of "one home per rule" (§1.10). *Tension, recorded:* the source says the criterion is "met for 12 concepts" [S0008 L105], but at least **two** of the twelve kept concepts fail its one-owner part: KnowledgeOS itself ("no single owner", L22) and the Runtime Adapter ("no declared owner", L29). The source does not note this. *(Second case added RC-14a.)*

---

## 1.10 Identity of knowledge items needs governed names

**The idea.** TR found that the identifier of the principle it traced, "AP-1", **already means something unrelated** in another register (a finding about a leaked business rule). Neither series is governed [TR L78–87].

**Corroboration (Ind).** I found the same defect independently in this pilot: two documents give "I-11" to different invariants (§1.1). Two instances in two unrelated places is not a statistic, but it shows the defect is not isolated.

**Identity must be independent of location (Src: S0031).** The platform's registry gives components and assets stable ids: "Paths change; ids never do." [S0031 L124]. References then survive relocation, which the source calls the most important property for moving the platform [S0031 L124]. **Ded (scoped):** an identifier that encodes a location (a path) either goes stale when the item moves, misdescribing where it is, or has to be changed, which breaks every reference to it. Both are failures of the requirement that references survive permitted changes; the first is the silent drift §1.16.6 forbids for versions, applied to place. The requirement is therefore **stability under every permitted move**. Location-free ids are **sufficient** for it, not necessary: a location-bearing id also works where the location is immutable or redirected (a persistent address). *Limit:* that the corpus's records do cite by id, not by path, is asserted by the source [S0031 L124], not shown; the documents read so far cite by path.

**Collisions of meaning (Src: S0034, 2026-08-02).** The same short words carry several meanings across the repository [S0034 L199–216]:

| Term | Meanings in use | Note |
|---|---|---|
| baseline | five (sealed genesis corpus · methodology baseline · architecture baseline · operational baseline · PKS phase baseline) | already TODO T0-06; the source proposes "always qualified by its domain" [S0034 L205] |
| constitution | five or more (methodology · strategic DDD · knowledge · implementation architecture · project) | |
| capability | three (platform capability · engineering capability · business capability) | |
| platform | four | a known naming drift |
| governance | three; **one of them is a product domain** (constitutional governance of elections) | a platform word naming product content |
| model · pattern · method | four · three · three | |
| verdict | two closed sets that collide | Ch. 2 §2.4 |

**Int (not Ded):** a term identifies a meaning **only together with its context**. By §1.9, a term's meaning is fixed inside one context, and the same word may mean different things in explicitly separated contexts; that is legitimate in DDD. A **collision** is the case where the qualifier is missing: unqualified use within one namespace, or use across namespaces with no context marker. On this reading words are identifiers relative to a context, like the identifiers of AP-1 above, and the remedy is the missing qualifier. The source prefers **qualification** ("always qualified by its domain") over renaming, but its reason is governance (renaming is forbidden), not theory. The book uses the same remedy for its own collisions (CV-1 §2a).
- **Two phenomena in the table.** Most rows are **polysemy** of a term, which qualification repairs. The "verdict" row is different: two **closed value sets** whose members overlap (Ch. 2 §2.4). That is a conflict between sets and needs a **scoping rule**, as the source itself says ("one scoping rule needed").
- **A row missing from the table, in this book's own vocabulary.** "The product binds the platform" (a constraint relation; Ch. 0 §0.2) and "binding" in CON-0072 (a product's input contract to a method) use the same word for different things.
- **Limit:** the source calls its counts "measured, not asserted" [S0034 L201], but gives no search procedure and no frequencies. The table is an enumeration of senses, not a measurement, and "the most severe" collision is not established by it.

**Development (Ded).** The foundations make a claim the atomic unit of knowledge (Chapter 0 §0.3) but say nothing about **how a claim is identified**. Without unique identity:
- supersession links (I-7) can point at the wrong claim;
- provenance (I-3) can be attached to the wrong one;
- "one owner per invariant" cannot be checked.

The repository's identifier capability states the needed rule, "**an identifier denotes exactly one subject**" (background note B-05; CAP-001). It is a *precondition* of I-3 and I-7, not a separate concern.

**Proposed addition to the foundations (Ded).**

> **I-0 (identity).** Every claim has an identifier that denotes exactly one claim, within a governed namespace. I-3 (provenance) and I-7 (supersession) presuppose I-0.

*New evidence (DR-AB L147–155):* the ARB deferred identifier governance as "an ownership question wearing an identifier's clothes": a register needs an owner before it can be governed. Until then, the rule is cited **by its statement, not by its number**. This supports I-0 and links it to ownership (§1.11): **identity requires a governed namespace, and governance requires an owner.**

The evidence shows the failure is real (two collisions). It also shows the fix is not mechanical everywhere: CAP-001 checks only the registers it knows, and "AP" is not one of them [TR L85–87].

**Corpus evidence: the corpus shows the defect (batch C-1; revision RC-9).** The same short identifiers name different things in different corpus documents:
- **"I-9"** = "governance-status ⟂ authority" and also "exactly one canonical owner" (see Ch. 0 §0.5 note);
- **"C-1"** = "model amnesia" [S0002 L17] and the workflow engine's "absence never implies ownership";
- **"C-5"** = "correction propagation" [S0004 L36] and the "Separation Attestation" capability;
- **"R-n"** = residue items [S0002 §3] and relationship rows [S0005 §1].

*[Src+Ctx: E-S0001 §10, E-S0002 §10, E-S0004 §10, E-S0005 §10.]* An identifier without its namespace is ambiguous. This is the failure this section describes, observed in the corpus itself.

**One home per rule.** The engineering canon treats duplication of normative content as a defect in its own right:
- the Decision Model is "a decision INDEX, never a second rulebook" [Src: S0006 L21; E-S0006 Q6];
- a second decision model would break the model's charter, a lint-enforced rule ("one authoritative artifact per topic + context") and "one rule → one home" [Src: S0006 L76–78].

Other artifacts may index, cite or map onto a rule, never restate it with authority. **Int:** two homes can drift apart, and then nobody can say which binds. This is the mirror of model amnesia: amnesia re-creates knowledge that exists; duplication creates two versions of it.

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

**When a missing owner is a defect (batch C-1; revision RC-10).** In the corpus, ownership is something **adoption confers**: "A candidate with an owner would be adoption smuggled past the gate" [Src: S0001 L37; E-S0001 Q11]. Two situations therefore look alike in a register and mean opposite things:

| Situation | A missing owner means |
|---|---|
| the item is a **candidate** (not adopted) | the system is working correctly |
| the item is **operating** (has effect in production) | a real defect: something acts and nobody may decide about it |

One operating-unowned asset was found, the Runtime Adapter [Src: S0001 L21, L38; E-S0001 Q12, Q20]. **Int:** an empty owner field is a finding only together with operational effect; absence alone is not (compare §1.17.3). *Counterexample check (Ded):* a read-only mirror that makes no decisions may need no owner. The rule is therefore read as "operating **and** subject to change decisions" (TODO T1-33: confirm against later sources).

**Ownership is evidence about boundaries, not proof.** The corpus tested the DDD heuristic "ownership signals bounded contexts" on its five architectural viewpoints. All platform-side viewpoint assets have one owner, the Decision Authority [Src: S0001 L27]. The author first concluded "one owner ⇒ one context", then corrected it: "shared ownership is evidence AGAINST separate bounded contexts, but insufficient by itself to establish a single one" [Src: S0001 L29; E-S0001 Q7]. The conclusion that viewpoints are **projections** of one model, not domains, rests on three supports:
- ownership;
- a "language test" (TODO T1-34: undefined in the source);
- an ARB statement from 2026-07-11 that the Reference Architecture and the Decision Model are "complementary siblings, not a hierarchy" [Src: S0006 L88; E-S0006 Q13]. *(Qualified RC-10a after independent review: the ARB text supports **non-hierarchy and mutual independence** only. "Projections" is S0006's own gloss (L89), inside a hypothesis it rates PARTIALLY SUPPORTED with the structural slot CONTESTED (L90). The ARB text is relayed by a generated document; its original was not opened.)*

**Hyp (source-downgraded, n = 1):** where ownership does change, it changes between **spaces** (platform, knowledge platform, product), not viewpoints [Src: S0001 L31; E-S0001 Q9, Q10].

**The product space must not hold method, and the rule is breached (S0008; revision RC-15).** The meaning ledger states the rule behind this section's split: a Product Knowledge Space holds "one product's concepts, language, contexts" and **bindings**, and must **never contain reusable method** (I-7; S0008 does not say which document numbers it, corrected RC-15a). The corpus records the rule as **breached** in practice [Src: S0008 L23, L64; E-S0008 Q6]. Engineering knowledge, "rules, methods and decisions that transfer between products", is platform-owned [Src: S0008 L24; E-S0008 Q7]. **Open:** what the breach consists of, and whether it was repaired; to be followed in reading (TODO T1-38).

**What makes a boundary real: tests from a falsification (S0011; revision RC-23).** A report of 2026-08-02 tried to break a candidate domain model and found four tests that separate a real boundary from an asserted one [Src: S0011; E-S0011]:
1. **It is instantiated, not only described.** The runtime vocabulary was "asserted in prose, not enforced by any artifact or tool"; no artifact held it [Src: S0011 L40, L45].
2. **Any translation across it is non-trivial.** The runtime translation (deny / ask / allow) was nearly the identity, and "an Anti-Corruption Layer that performs an identity mapping is not an ACL. It is a restatement." [Src: S0011 L42–44; Q7].
3. **It is maintained by an explicit rule.** The methodology boundary survived on a written mutual exclusion ("Governance discoveries … MUST NOT appear here"). The report reads this as stronger than separate decision series: "a shared authority that forbids itself from mixing two subjects is evidence FOR two models" [Src: S0011 L78–84; Q11].
   *Caveat to test 2 (RC-23a):* the report itself rates the identity-mapping finding "untestable at n=1. If universal, D-3's ACL is permanently an identity map", and recommends **DEFER**, not rejection [S0011 L114, L141].
4. ~~Test 4~~ **Withdrawn as a boundary test (RC-23a after independent review C2):** "a series written in one sitting evidences an independent subject, never independent evolution" [Src: S0011 L30; Q9]. In the report the methodology boundary **survived** on subject exclusion. So this point says **which kind of independence** the evidence shows; it does not separate real boundaries from asserted ones. Kept as a note on evidence, not as a test.

**A related rule:** "A type that has never been instantiated is a description, not a discovered construct" [Src: S0011 L70; Q8], the same parsimony as §1.8's n ≥ 2.

**Status:** **Src**, a single report (candidate). The report applies these tests in its own practice; collecting them as "tests of a real boundary" is **Int**. "Real boundary" here means a bounded context in the report's sense. **Independent review (CV-1 second attempt): NOT VALIDATED as a set; tests 1–3 individually faithful, with caveats** (`review/C2-S0011-S0015/PHASE2-REVIEW-REPORT.md`).

**Open:** whether the scope exclusion was ever enforced after it was written (S0011 U-4).

**Spaces nest; a deployment is a nested space (S0012; revision RC-26).** The question whether knowledge spaces nest (H-3) was declared resolved by a **structural argument** ("resolved on evidence" corrected RC-26b: S0012 offers that the ontology *permits* containment, plus an assertion). A deployment has its own bindings and a declared boundary, so "a deployment IS a Knowledge Space", nested in its product's space, with no new concept needed [Src: S0012 L93, L158]. This strengthens the **Hyp** above (ownership boundaries follow spaces) on the structural side; ownership itself is not shown by it.
**Contested a day later (revision RC-26a).** The synthesis of 2026-08-03 still lists "Space NESTING (containment vs adjacency)" as a **hypothesis, "unevidenced"** (H-7), and K-6 waits on RO-2 (nesting) [Src: S0013 L54, L75]. It does not mention S0012's resolution. **Both positions are kept:** S0012 resolves nesting by a structural argument; S0013 does not accept it as evidence. Classification: **undetermined** (TODO T1-43).

**Three authority systems, and no authority over all three (S0019; revision RC-42).** The mission discovery replaces a flat list of owners with **three kinds of authority over different subjects** `[CON-0026 · provisional]`:

| Authority system | Decides | Holder (as observed) |
|---|---|---|
| **policy** | what is permitted, adopted, frozen, ratified | Decision Authority / ARB |
| **method and implementation** | how engineering is conducted; which capabilities exist | sponsor + ARB (method); one individual (the knowledge machinery) |
| **domain knowledge** | what the business means | the product team |

"They are three DIFFERENT KINDS OF AUTHORITY over different subjects", not competing owners of one thing. But "no authority spans all three, and none is designated to reconcile them" [Src: S0019 L100–107; E-S0019 Q9].

**Int (in this chapter's terms):**
- *(corrected after review C3 F-04; RC-42a)* the systems differ by **subject**, but their **holders overlap**: the ARB holds in both POLICY and METHOD [S0019 L102–103], and POLICY decides what is "adopted, frozen, ratified", which includes adopting method. So this is **not a partition**. The source claims only that "no authority spans all three" [L107];
- a question between **domain knowledge** and another system (e.g. whether a domain rule belongs in the method) has **no common holder** and no designated decider. Under §1.17.3 such a case yields "cannot determine", not a decision by default;
- **Method may not sit under policy at all (S0034).** The methodology constitution "was adopted by sponsor authority (ADR-M-012), not by the ARB" [S0034 L174]. Whether governance authorizes method, or the two are **peers under a common sponsor**, is called the model's "most consequential open question" [S0034 L174]. **Int:** if they are peers, the overlap of holders above (the ARB in both) is not a hierarchy, and the question of who reconciles them (TODO T1-50) becomes sharper.

**Authority from outside (S0022; revision RC-50).** All three systems above are **internal**: their holders are the sponsor, the Decision Authority, the ARB, the product team. Projected into a hospital and an ERP system, **both hypothetical and built by the author** [S0022 L27], the authority chain "has no place for EXTERNAL authority": HIPAA, GDPR, clinical safety standards, IFRS/GAAP and statutory audit are principles the organisation "must obey and does not own" [S0022 L108–114].
- "The ontology can express *who decides*. It cannot express *who must be obeyed*." [S0022 L117; E-S0022 Q18]
- PublicDigit has the same gap, but masks it: "election law is treated as product domain knowledge, not as external authority" [S0022 L115].
- The proposed specialization: an **external** principle is "imposed, non-amendable, compliance-only" [S0022 L123] `[CON-0044 · invalid]` (partly valid: a voluntarily adopted external standard is obeyed because of an internal act and can be dropped, which breaks "imposed, non-amendable"; support is hypothetical; review C4 F-01). The same gap was stated the same day by S0012 ("obligation ≠ ownership", LG-6; TODO T1-15). Where external authority attaches is an open ARB question (S0022's CV-3 [L175]).

**Int:**
- *(corrected after review C4 F-01; RC-50a)* **Hyp / Open:** external authority **may not be covered** by the act model. Two ways it could be covered are not yet ruled out: (1) an **internal recognition act** that adopts the external rule (then it binds through §1.6); (2) a **grant record that points to an external act**, consistent with the definition in §1.13.1 that legitimacy "derives entirely from a human act performed outside the system". Which applies, and whether an imposed rule (law) differs from an adopted one (a contractual standard), is open (TODO T1-15; T1-53 merged into it).
- It differs from the missing spanning authority (T1-50): that is about reconciling internal systems; this is about authority no internal system holds.
- the source records "who owns KnowledgeOS as a whole?" as open (MQ-2; TODO T1-50).

---

## 1.12 A record of an act is not the act

**The idea (Src, DR-AB L9).** DR-AB records the ARB's decisions and states its own limit: *"A report of an act is not the act: if a separate ratification ceremony is required, this record is its input, not its substitute."*

**Refinement of §1.2 (Ded).** §1.2 said every standing change requires a *recorded* act. DR-AB adds the converse caution: **a record does not make the act happen.** A machine can check only records, never acts. So:
- a standing history is *valid* if the acts occurred, which is a fact about the world;
- it is *verifiable* if every act has a record of sufficient integrity: who, when, under which grant, with what scope.

Verification can establish only the second. The first rests on trust in the recording process, e.g. that the record was written by the deciding person and not by an assistant on their behalf. This is the **residual trust assumption** of the whole authority model; the theory should state it, not hide it.

**Generalization (pilot 2, file 5; Ch. 2 §2.8.7).** The same holds for any **self-report**. A system's report of its own environment is a claim with provenance *self*, not a fact: the framework reports "production" on a developer's machine. A check's message "BLOCKED" was not a block when the exit code did not block. Records of acts, reports of enforcement and self-descriptions are all **claims about events, to be evidenced**, never the events themselves. **Ded.** It connects to §1.2's *authored-by* requirement: the record must name its producer.

**Evidence from the platform: silent fail-open (Src, observed; SI L117–126, L160–167, L263–273, L334–341, L356, L392; added R1-12).** The inventory records three incidents in which the **enforcement layer failed silently**, each time with a reassuring visible symptom:
1. a guard printed "BLOCKED" but exited with a code that did not block, so "the destructive command executed regardless";
2. a counterfeit tool answered every query with empty output, so the gates "run on empty configuration and **fail open**: they pass without measuring anything";
3. a guard's path could not be resolved, so it "did not run at all … an absent gate reports nothing and blocks nothing".

To these come a component whose header describes a file path its code does not write, and a registry entry "planned" for five weeks for a mechanism that does not exist: "the registry describing an intention, not an inventory" [SI L392]. *Reading (Ded):* in each case a **declaration diverged from behaviour**: a printed message, a declared hook wiring (incident 3 produced no report at all; the divergence was between the wiring declaration and what ran), a header, or a registry entry. That is the case this section describes, found in the platform's own enforcement. The platform's own cure is a **meta-rule**: "a governance gate must **refuse to run** when it cannot read its policy" (**verified**, `scripts/lib/config-guard.sh` L6). In the terms of Ch. 2 §2.8.8, a gate whose policy is unreadable is in E6(c) and must fail closed. The inventory notes that the cure is applied only to some gates [SI L273]. *A limit of the cure (verified, `scripts/design-check.sh` L42–46):* the design gate fills **missing** settings with hard-coded defaults (e.g. threshold 150, `exit_on_fail` false). An absent policy value is therefore silently replaced by a specified one, the failure Ch. 2 §2.8.8 names ("E2 silently replaced by a specified value"). The guard catches unreadable values, not missing ones.

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

**The register is not the complete record of authorizations (S0014; revision RC-29).** The corpus's own evidence reconciliation found that real construction (the capability CAP-001) was driven by commissions that **never minted a ruling**. The capability catalogue called it "unauthorized", its README called it "REALIZED", and the register was silent. Its conclusion: "**the register is provably not the complete record of authorizations**" [Src: S0014 L53, L68; E-S0014 Q9].
- The corpus offers two remedies, as a pending Decision Authority decision (D-2): **mint** the authorization into the register, **or rule** that the register is not the complete record [Src: S0014 L68].
- **Consequence for this chapter (Ded; corrected RC-29a after independent review C2):** the authorizations were **unregistered, not unrecorded**. The commissions exist, and the README records "REALIZED" [S0014 L53, L68; S0015 L58].
  - Recognition **"as the register stands"** misses these authorizations.
  - Recognition **"as all records stand"** (commissions, READMEs, the register) may not.
  - The register shows such an act as "not found", which is not "not authorized" (§1.17.3).

  Under the two remedies:
  - *mint*: the gap closes prospectively; past acts stay unregistered until minted, with their act time preserved and nothing back-dated;
  - *rule the register incomplete*: recognition must admit the other records of acts.

  This is the act-vs-record question (an act exists independently of its record; §1.13.1) observed in the platform itself.
- **Status:** **Src** for the finding (a candidate document, HIGH confidence, "found blind"); the two remedies are the source's; the decision is the Decision Authority's (TODO T1-46).

**A forward minting rule beside two options for the past (S0015, S0014; revision RC-33, rewritten RC-33a after independent review C2).** *The earlier text of RC-33 ("the corpus first proposed the strict rule, then reopened it") is **withdrawn**: it misread the sources. The withdrawn wording is kept in `BASELINE-REVISIONS.md`.*

**What the sources actually say.** Both documents carry **both** elements:

| | Forward rule (from now on) | The existing gap (CAP-001) |
|---|---|---|
| S0015 (blind review, 08-02/03) | "a register-completeness rule — authorizations exist only if minted" [L104] | "Mint CAP-001's authorization and acceptance into the register — or record why the register is not the complete authorization record" (IR-1) [L112] |
| S0014 (reconciliation, 08-03) | "commissions that authorize work must mint" (C-2 process fix) [L53] | D-2: mint, **or** rule that the register is not the complete record [L68] |

- **Reading (Int):** "authorizations exist only if minted" is a **forward record-keeping obligation**. It says that authorizations must be minted. It does not say that unminted ones were never authorizations: the same document calls the unminted commissions authorizations missing from the register (S0015 L58).
- It is therefore **not** the strict recognition rule "only recorded acts count" of this chapter's open choice.
- For the **existing gap**, both documents leave open whether to mint retroactively or to declare the register incomplete. That is a decision about recognising past acts, and it stays reserved in the corpus (D-2, IR-1) and in this book.
- **The docket's recommendation (S0023; revision RC-55).** The decision docket packages this as D-2 for the Decision Authority and recommends **both**: "mint with disclosure + adopt the forward rule" [S0023 L108]. The alternative it names is to rule "that PA commissions are a second lawful authorization channel" [L101]. It is a recommendation; the decision is still reserved.
- **A self-check (Src, with Int).** The minting "must pass PMR-10 — CAP-001 would check the ruling that authorizes CAP-001" [S0023 L103; E-S0023 Q15]. **Int:** this is circular for **verification**, not for **authority**. The ruling's validity comes from the Decision Authority's act (§1.6). CAP-001 can check that the ruling is well-formed; it cannot be the reason the ruling is valid. A failed check would show a defect in the record, not a lack of authority.


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
- **Genesis (S0024; revision RC-59, corrected RC-59a after review C4 F-02).** The Architecture Fitness Assessment finds one architectural gap, **genesis**: the platform governs *change*, but has no route from an *idea* to the *first decision*, because the Reference Architecture's lifecycle (§7) requires operational evidence and forbids "idea → ADR → implementation" [S0024 L36, L159]. "the gap is invisible from inside a mature project, because genesis happened before the governance existed" [S0024 L161; E-S0024 Q11]. The proposed one-clause fix: "at day zero the stakeholder's stated need is the admissible evidence input, and the first decision is recorded Provisional/Proposed" [L162; Q12]. Whether genesis is the platform's responsibility at all is open (OQ-S3), and "not the platform's responsibility" "is a legitimate answer" [L163] `[CON-0054 · invalid]`.
  - **Int (corrected):** genesis as stated is an **evidence prerequisite**, not an authority regress: the lifecycle requires operational evidence before a first decision. The proposed clause **supplies** evidence (the stated need becomes admissible) and records the first decision only as Provisional/Proposed, which is not standing. It is therefore **not** the analogue of a root act; equating them would merge the evidential and governance-act engines that §1.6 keeps apart (RC-37a).
  - **The authority-side analogue** of a root act is the experiment's other block: who may **approve** at day zero (S0025 F-1; §1.14.3, RC-65).
  - **Classification differs across sources:** S0024 calls genesis architectural (L155); S0025 lists it under **governance** (S0025 L155). TODO T1-17 keeps both readings open.
  - **Primary source (S0025; revision RC-64).** The genesis finding comes from an experiment: an isolated session tried to start a second, unrelated product with only the platform's core kit, and found no path to its first architecture decision. "A platform that governs CHANGE does not automatically govern GENESIS — and the gap is invisible from inside a mature project, because genesis happened before the governance existed." "No amount of further analysis inside PublicDigit could have surfaced this." [S0025 L120–122; E-S0025 Q16, Q17]. S0024 repeats it: one line of evidence, n=1. The proposed clause is addition A of that experiment, put to the Authority as a candidate [S0025 L139, L173].

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

**Separate roles, not separate people (S0014; revision RC-30).** The corpus states a limit on this section's roles that it says is stated nowhere else: "**Separation of duties is real as PROCESS, unevidenced as PEOPLE — and this limit is stated NOWHERE**". Role separation is "session-simulated": different AI sessions take the roles [Src: S0014 L57; E-S0014 Q10]. *(That the sessions act under one human is **Int**, not in the source; corrected RC-30a.)* Its confidence is HIGH, though the source notes it is inferred from owner fields.
- **Consequence (Ded):** "the accepting act must name a different actor" can be met by a different **session** while the **person** behind both is the same.
- Independence in this book is therefore **process independence** unless a different person is evidenced. Reviews by fresh AI sessions (including the reviews of this book) are independent as process, not as people.
- The corpus asks the Decision Authority to state this limit in governance (D-6) [Src: S0014 L72].
- **The corpus practises author ≠ reviewer (S0015; revision RC-35).** Its blind review was run by an isolated session with the conclusion documents blocked, because "I could not be the reviewer — I authored every conclusion under test" [Src: S0015 L9–10; E-S0015 Q4]. The reviewer independently prescribed the kernel-bootstrap experiment that had already been run [Src: S0015 L72]. The source calls the agreement "the strongest methodological corroboration Phase B could have produced" [S0015 L77], which is **its own assessment**. *(Corrected RC-35a: no predicted outcome is quoted in the source.)* It is still **process** independence (an AI session), as the point above requires.
- **A solo start cannot satisfy the protocol as written (S0025; revision RC-65).** In the bootstrap experiment, the execution protocol (EEP) could not be met at the start: §2 forbids one party holding Engineer and Independent Reviewer, §3 forbids skipping, §5 forbids implementing before approval, and the only light gate belongs to the source project. "The MVK forbids proceeding and supplies no lawful way to proceed" [S0025 L114; E-S0025 Q15]. The experiment rates this **team-size dependent**: for an organization with a named architect "that scope's verdict would be WARN, not FAIL" [L129]. It proposes a role-scaling clause, since the protocol already says "depth scales… the sequence does not" [L141] (a candidate only).
  - **Int (corrected after review C4 F-05; RC-65a):** the block is a **conjunction**, and the meaning of "party" settles only §2. If "party" means a role-holder in a session, fresh sessions can satisfy §2, within the limits D-6 asks to state. They cannot satisfy §5: approval is a human act (§1.6), which a session cannot supply. Nor do they supply the missing light gate. So process independence, as this book reads it, does **not** lift S0025's F-1; the source's own "named architect … WARN" also points to a person (TODO T1-54).

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

**A projection is decided by lifecycle, not by label (S0011; revision RC-21; heading and reading corrected RC-21a after independent review C2).** The corpus tested the claim "a PKS is a projection" against the PKS artifacts and **falsified** it:
- each carries a commission, a review score (9.9/10), a disposition history and the status ACCEPTED WITH REFINEMENTS;
- a projection would instead carry a source and a regeneration command;
- "You do not endorse a projection at 9.9/10. You regenerate it." [Src: S0011 L49–58; E-S0011 Q10];
- the `authority: derived` label is used only in prose, never applied, and the folders concerned lie outside the schema's declared scope, so "no frontmatter there is validated at all" [Src: S0011 L60].

**Int (refines this section):** whether an artifact is a **projection** (regenerated, carrying no standing of its own) is shown by **what happens to it**. A projection is regenerated; an authoritative artifact is accepted by an act. An artifact accepted by an act is not a projection, whatever its label says. This is P-8 seen from the other side.

**Reconciled with §1.2 (RC-21a).** S0011 calls the accepted PKS artifacts "incompatible with `derived`" [S0011 L62]. That uses the **one-field** model of `authority`, which the corpus **proposes to dissolve** (PM-1, undecided [S0018 L129]; corrected after review C3 F-16). Under the executable one-field schema [S0020 L28, L89], S0011's reading still holds, so both readings are kept. In the proposed split: *derived* and *generated* are **provenance** values; *authoritative* is a **standing** value [S0012 L152; S0018 L77–83; RC-24].
- On that model an artifact can be **derived in provenance and authoritative in standing at the same time**; the corpus's own example is a signed financial statement [S0018 L96].
- The PKS artifacts' "Authority: Generated" [S0011 L53] is likewise a provenance value.
- **What S0011 actually falsifies** is that the PKS artifacts are **projections**, i.e. regenerated without an accepting act. It does **not** show that derived provenance excludes authoritative standing.
- Three things are kept apart: **projection** (a lifecycle), **derived provenance** (an origin), **authoritative standing** (the result of an act).
- **The definition in the vocabulary (S0020; revision RC-44).** The design policy DP-2 of **CAP-002**, a capability that was **DEFERRED** [S0020 L100], defines a projection (owner corrected after review C3 F-14): "every projection regenerates from its sources and is cited as authority by nothing" [S0020 L89; E-S0020 Q7] `[CON-0033 · invalid as stated]`; see Ch. 3 §3.5.
- **Note:** the platform's `authorities.yaml` ranks `derived` (rank 2) on the same scale as `authoritative` (rank 1) [S0020 L28, L89]. That is exactly the fusion of provenance and standing that the corpus proposes to separate (PM-1, §1.2; the order of S0018 and S0020, both 2026-08-02, is not established; review C3 F-17).

**Derivation is not attestation (S0012; revision RC-25).** A cross-product test had seemed to break the rule that a derived item never becomes authoritative: a hospital's derived discharge summary does become authoritative. The corpus withdrew that "falsification":
- the summary became authoritative through **DERIVED → REVIEWED → ATTESTED → RELEASED**;
- "Governance created the authority, not the derivation." [Src: S0012 L16–22; E-S0012 Q9].

It then located the cause mechanically:
- the platform's **status** has a governed state machine, with order, a terminal marker, a role table for who may move it, and machine-checkable guards;
- **authority has none**: "STATUS HAS A GOVERNED STATE MACHINE. AUTHORITY DOES NOT." [Src: S0012 L67–78; Q10];
- nothing therefore says who may move an item from *derived* to *authoritative*, and the model could not express "attested" [Src: S0012 L80; Q8].

**Int (reconciles P-8 with the case):**
- A derived representation never gains standing **by being derived**.
- It gains standing only by an **attestation act**: an act by an authorized actor, which the axiom (§1.6) already requires.
- The artifact's **provenance** stays "derived" forever; its **standing** changes by the act (§1.2, RC-24).
- **Not independent support (S0021; revision RC-48).** The Relationship Ontology repeats this diagnosis in its invariant I-4 ("Governance created the authority, not the derivation." [S0021 L108; E-S0021 Q16]) and cites the Lifecycle Gap Analysis (S0012) for it. It is the same line of evidence as RC-25, not a second one.
- **Precision (S0018; revision RC-38):** the source calls the *chain* derived → reviewed → attested → released "QUALIFICATION, not authority" [S0018 L17]. It describes rising trustworthiness. **Int** (labelled after review C3 F-26): the **act** of attestation is what changes standing; the source says "Attestation changes standing" [S0018 L94] but does not spell out the chain-vs-act contrast. "Qualification" carries three senses in the corpus (a verification activity, a ladder stage, this chain) `[CON-0023 · invalid]`, a recorded naming collision (TODO T4-02).

---

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

**Recommended rules and confirmation requests.** Blocking on a *recommended* rule gives a "should" the force of a "must", so it needs an authorizing act like any other block. A **confirmation request** (the mechanism asks, a person decides) is not a block, **provided the person asked holds a grant covering the decision and the confirmation is recorded as an act** (who confirmed, when, under which grant). A confirmation that leaves no record, such as a dismissed dialog box, is a **report**, not an act (INV-A1). It cannot lift a block [PC L159–161]. A request routed to someone who cannot validly decide stops the work just as a block does, and counts as one.

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

**The corpus marks the paradox of a proposed constitution (S0015; revision RC-34).** The blind review sharpened a known gap into a contradiction: "the platform governs by a proposed constitution… exactly the class of over-claim it polices elsewhere" [Src: S0015 L66; E-S0015 Q10]. Its engineering standards, applied as binding, carry the standing **proposed**; their ratification is a pending decision (S0014 D-4).
- **Int, in this section's terms:** enforcement of a rule presupposes that the rule has standing by an act. A rule with the standing "proposed" has none.
- If proposed standards are applied as binding, the readings include (corrected RC-34a after independent review C2: the earlier "either (a) or (b)" was **not exhaustive**):
  - (a) an implicit act of adoption that was never registered (compare RC-29);
  - (b) enforcement without ratification, which this section forbids. This is closest to the source's own word, "over-claim";
  - (c) the standards are followed as **non-binding convention**, with no claim of force, in which case there is no paradox;
  - (d) force is supplied by **another, ratified instrument**.

  The sources say "governs by"; that the standards are "applied as binding" is not shown.
- **The record does not show which.** Kept open; this is the same act-vs-record gap at the level of the constitution itself (TODO T1-47).

**Freezes without a declared authority (S0017; revision RC-39).** The corpus records that FREEZE was exercised at least three times: the methodology freeze (2026-08-01), the architecture freeze (2026-08-03) and the corpus freeze (Phase A). Each was "by declaration, under no named authority, procedure, or reversal condition" [S0017 L55; E-S0017 Q8].
- This section allows an emergency freeze only as an **act within a grant**.
- **S0017 claims** (attributed after review C3 F-22) that the recorded freezes show no grant, no procedure and no way to end them [S0017 L55]. This holds at most for freezes of **activity**: `frozen` is also a governed **status** with roles and guards [S0016 L43; S0018 L27], with a lint rule `frozen_changed_without_adr` [S0020 L24], and POLICY (DA/ARB) decides "what is … frozen" [S0019 L102]. This adds to the two senses of T1-06. As a decision type, freeze is `[CON-0015 · invalid]` (not verifiable: no governing record of authority; TODO T1-48).

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

**One owner, possibly several mechanisms (Src, observed; SI L293–332; Ded; added R1-13).** The platform shows both cases:
- **potential duplicate ownership** [SI L300]: domain-layer purity is checked by a blocking gate and touched by an advisory reminder that do not reference each other, with "no contract stating which is authoritative". Two readings: (i) one invariant with two owners, the P-7 failure; (ii) the inventory itself lists them under **different contracts** (K-11 domain purity; K-6 the seven DDD principles, of which purity is one), so they **overlap** rather than duplicate [SI L215, L220];
- **defence in depth**: one contract (the repository is the single source of truth) enforced by two mechanisms, one of which names the other as its "companion control" [SI L332].

*Distinction (Ded):* "one owner per invariant" does not forbid several enforcement mechanisms. It requires that **one** of them is declared authoritative, or that their relation is **declared**. The difference between duplication and defence in depth is a recorded relation, not the number of mechanisms.

**Development (Ded): what is the atomic change of a knowledge space?** A standing transition is caused by an act (§1.6). If the act's record and the transitions it causes can be stored separately, a crash or a partial edit leaves a state in which a transition exists without its act, or an act without its effect. Either breaks reconstructability (R, Ch. 0 §0.5). So: **an act and the standing transitions it causes should fall inside one atomic change.**

AB observes that in the canonical representation (git, §1.14.4) the atomic change is the **commit**, and that content hashes and snapshot identifiers come free: a file's content has a hash, and a commit fixes the state of every file [AB L63–76]. This is correct for git's object model (**Ind**).

*But the boundary is broken today (Ded, from §1.13.2).* The grant records are **excluded from version control** (`.gitignore:33`, checked). So the act's authority (the grant) and the claim it governs do not share an atomic change or a history. Two consequences:
- the "free" reproducibility does **not** extend to authority: the question "was this grant valid when the act happened?" (§1.16.2) cannot be answered from git;
- §1.13.2's conclusion is sharpened: grant records must be inside the same consistency boundary as the claims whose standing they support, or be at least as immutable and addressable.

**Knowledge about a system is not the system (Src: AB L119; Ded).** AB rejects aggregates for *System, Component, Capability, Dependency*: they would make the knowledge system a **system of record for the architecture it documents**. In this book's terms: the subject a claim is about (subject scope, Ch. 2 §2.8.1) is not owned by the knowledge space. It is referenced, like any external domain. This is the counterpart of §1.5 (knowledge is not its carrier): knowledge is not its subject either. Left open by AB as **OQ-16**.

**Phase (Src: AB L39–51; REPEATED).** AB's proposal is "correctly shaped" but arrives before the strategic questions it depends on are closed. "Forbids tactical decomposition" means "not yet derivable", not "never". This is §1.14.5 (gates follow ratification) for **modelling**: a model may not settle a question that is still open.

**Check and effect belong to one atomic change (Src: DE L342–352, L58; Ded).** A later source states the rule for message handling: "a check performed *before* an effect is not deduplication; it is a race". The general form: **a condition read at one instant and acted on at another, with nothing guaranteeing it still holds** [DE L352]. The same applies to **authority**. An authorization checked before an act and relied on after it may have ended in between. So the check and the effect it licenses must fall within one atomic change, or the check must be repeated at the effect. The same source adds that an event announcing a change may be published only **after** the change committed; otherwise it reports a state change that did not happen [DE L58].

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

**The kinds of event, elaborated by a later source (Src, PROPOSED; DE L78–96).**

| Kind | What it is |
|---|---|
| **domain event** | an internal fact in the shared vocabulary |
| **notification** | an in-process dispatch mechanism, not a contract |
| **integration event** | crosses a boundary; a versioned, deliberately smaller published contract |
| **audit event** | an accountability record: actor, policy version, request identity, provenance, integrity protection [DE L85] |

"An audit event may be generated from a domain event but is not identical to it" [DE L91]. That is a report of an act, not the act (INV-A1), at the event layer. The source also rejects a single vague "knowledge updated" event: it carries no domain fact, so **every receiver must re-derive what happened**, which gives many independent interpretations of one fact [DE L15–37]. That is the one-owner principle (P-7) violated by construction.

Two further constraints from the same source (Src, PROPOSED; added after batch-4 review F23):
- **a projection may emit a notification that work is needed, never a fact others treat as truth** [DE L174]. This is the sink rule of §1.14.4 at the event layer;
- **ordering may not be assumed unless the contract guarantees it** [DE L380]. Under INV-A3 an unordered stream is a fact that consumers must handle. This is why the formal model of §1.14.1 needs a **recorded** order of acts (repair input, `CH-01-step1-correction-and-review.md` §7).

**AB's five-item list omits the anchor:** the **record of the human act itself** and the **grant** under which it was made (§1.13). In this theory every standing transition must point to one (§1.6), so it is a sixth kind of record. It is the only one whose **act** is not produced by the system; its record, like all records, is (§1.12). Leaving it out is the gap §1.16.1 found in practice (grants outside version control).

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

**Pinning applies to the deciding logic too, and to identities (Src: PC L67–69; DE L364–368).** A decision is reconstructable only if **both** its inputs **and the version of the logic that decided** are recorded: "a policy whose rules changed silently makes every prior decision unexplainable" [PC L67]. The same discipline gives idempotency: "identity must include the version of what was read" [DE L368].

**Retention follows from pinning (Ded; Src DE L398–408).** If a claim pins *d*@*v*, then *d*@*v* must stay resolvable for as long as the claim may be checked. In particular, a record whose deletion would make a recorded authority act unverifiable, such as the pinned version of the grant behind an exception, cannot be deleted **while that act may still need to be checked**. A later source draws the same conclusion for events: "retention for governance-critical events is bounded by **reconstructability**, not by operational convenience" [DE L406]. *Derivation:* reconstructability (premise R, Ch. 0 §0.5) plus pinning implies retention **for as long as the pinning claim may be checked**. No time-to-live may end it earlier. **Permanence does not follow from this alone.** It follows only with a further assumption: that recorded authority acts must remain checkable **indefinitely** (for audit, dispute or history). That is an organisational retention policy, not derived here. The source asserts permanence for its "governance-critical" events [DE L406].

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

**Where the reference came from also matters (Src: DE L113–131, L277–301; Ded).** A later source contrasts two activation designs:
- **from the caller:** the activation receives the approval reference from the caller (`activate(approval_reference)`). It **executes an approval already recorded**;
- **from the evaluator:** an evaluator **produces** the reference during the activating transaction.

"A mechanism that produces the approval reference has granted the authority that reference is supposed to record" [DE L295]. The presence check passes in both cases, so **it cannot detect the difference** [DE L297]. Hence a further condition (Ded): an authority reference must resolve to an act **made and recorded by its human author, independently of the mechanism that relies on it**, never produced by that mechanism. Usually the act is recorded before the relying transaction. If it is recorded in the **same** atomic change, which §1.16.1 permits and even prefers, its record must show the human author, not the evaluator, as its source. *This differs from the source, and the difference is a trade-off (two concepts, kept):* the source forbids an approval reference that is "a value produced during the activating transaction" (its OQ-33) [DE L301]. That rule can be **checked by machine** from transaction boundaries, but it excludes a human approval recorded atomically with its effect. The book's condition allows that case, but relies on **recorded authorship**, which ultimately rests on the trust assumption of §1.12. Neither is a narrower version of the other.

**Time-triggered conditions (Src; IM L207–227; REPEATED).** "An expired exception cannot satisfy a current conflict" has **no triggering command**: expiry is caused by time. It is evaluated at read time against the asking instant, "never cached as a boolean"; "a stored is_valid boolean is the bug this row exists to prevent". This is §1.14.1's point that current authority is derived and never stored, reached independently. IM's OQ-22 (may a condition have no trigger?) is answered by it: yes, and such conditions are exactly the ones a write-time-only check misses.

**Outside evidence validates patterns, never this platform's realization (batch C-1; revision RC-11).** The capability → runtime chain is standard practice externally, yet the platform's own link between capability and execution asset is undeclared. The corpus concludes "external validation cannot close an internal link" [Src: S0005 L85; E-S0005 Q9].
- **Int:** confidence from literature attaches to the *type* of relation; whether *this* system realizes it needs internal evidence.
- *Counterexample check (Ded):* the rule fails only where the internal component *is* the externally validated artefact, used unchanged. Kept with that qualifier.

**Falsified is worse than unevidenced (S0011; revision RC-22).** The corpus practises this section's rule by deliberate falsification "executed against artifacts, not reasoned from the model" [Src: S0011 L10; E-S0011 Q3]. It separates two weak results that are easily confused: a claim **carrying contradicting evidence** (falsified) and a claim **carrying no evidence** (unevidenced); "The first is worse." [Src: S0011 L133; Q6]. This is the book's distinction unevidenced ≠ contradicted, with a ranking added.

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

**Decision-readiness: what the platform delivers for authority decisions (S0017; revision RC-40).** The corpus's decision-model discovery answers "what decisions is KnowledgeOS designed to help humans make correctly?" in two halves [S0017 L90–92; E-S0017 Q6]:
- the eight **engineer decisions** `[CON-0013 · validated]`, resolved by applying a governed procedure and catalogued in advance; but the source also finds "no evidence was found that the catalog is consciously consulted as a whole" [S0017 L110, U-DM-4];
- the **governance decisions** `[CON-0014 · validated]`, which it "PREPARES BUT NEVER MAKES": "for authority decisions, the platform's product is DECISION-READINESS, not decisions" `[CON-0012 · invalid]` (partly valid: S0017's own term, in a CANDIDATE document that grades "every conclusion a hypothesis until evidenced" [S0017 L5]; no governing record; TODO T1-51; RC-40a). In the source's words, 77 rulings were made by humans, and the platform's contribution was "that each arrived shaped: evidence graded, options separated, unknowns carried". **That sentence is the source's own unsampled generalization over 77 rulings**; it was not checked.

**Int:**
- this is this section's division of labour (reasoning and coordinating by the system; deciding by authorized humans) stated as the platform's **product**;
- the corpus also notes an asymmetry: engineer decisions have a **catalogue**, governance decisions only a **log** [S0017 L39]. Whether authority acts should be enumerable in advance is open (U-DM-1).
- **An instance (S0023; revision RC-53).** The ARB's decision docket is a concrete instance of decision-readiness: eleven one-page packages (ten D-items plus one new package), most with evidence on both sides and risks both ways (Packages 8–10 lack some fields; Ch. 5 §5.1), each with a named authority and a recommendation kept apart from the decision ("Recommendations only — every decision is the named authority's." [S0023 L5]). It is one docket, produced by the platform about itself, so it shows the pattern but not the 77-ruling generalization; CON-0012 stays invalid (T1-51). Developed in Ch. 5 §5.1.

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


**Corpus evidence for reserved decision 4 (S0011; revision RC-20).** A falsification report of 2026-08-02 met exactly this question in practice. It asked why a sponsor had separated methodology governance, and found it unanswerable: "**The artifacts record the act, never the reason.**" [Src: S0011 L113; E-S0011 Q12].
- **What this shows:** for **one** act (the adoption of the methodology constitution), the record holds the act without its reason (n = 1). That this is the platform's general practice is **Int**, not shown (corrected RC-20a).
- **What it does not decide:** which reading of "reasoned" applies. It shows the **practical cost** of each reading:
  - under **(a)**, such recorded acts would have no standing;
  - under **(b)**, they stand, but their foundation cannot be examined;
  - under **(c)**, the answer depends on which kinds of act require reasons.
- **The decision stays reserved.**

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

**Classification:** UPDATE OR REFINEMENT of §1.7. *(Revised R1-10: the first version said that "Chapters 0–2 … neither says who may **know**". That contradicted §1.7, which already gives the visibility rule vis(d) ⊆ ∩ vis(sᵢ) and treats visibility as a fourth label.)* What IA adds is that the **sources** of 08-16 had not yet turned §1.7's rule into a governed mechanism; the book's own model of read access is in §1.7.

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
- *Candidate* dimension: the confidentiality class of a claim. **This is the visibility label of §1.7** ("visibility is a fourth label … orthogonal"), stated from the grant side: vis(c) is the set of principals whose read grants cover *c*'s class. It is shown independent of **standing** only; it is **not tested** against the governed classification κ, which may already be able to carry it.
- **It is not shown necessary.** Counterexample from the independent review: the operational case below is fully expressible as **grant scope over named items**, without any class. A class becomes useful only where items are too many to name. That is a scale argument, not a necessity.

The corpus must show whether such a classification exists, and whether grant scope over items suffices.

*Operational evidence that it is needed (Src: this research's own governance).* The work behind this book runs under exactly such rules: a primary researcher's access to corpus material does not extend to an independent reviewer without separate written approval; some files are sealed or held out; credential files are blocked. These are may-read relations, enforced by people and by a permission layer. The theory being reconstructed has no place for them, while the process reconstructing it depends on them.

*Two consequences, conditional on the hypothesis (Hyp):*
- **If** read access is governed, context assembly must filter by may-read before relevance (conditional revision of Ch. 0 §0.7, step 2). Otherwise the assembled context leaks what the reader may not know, whatever its relevance. A later source proposes the same mechanism as a "context assembly policy", with the rule that **the policy filters and never elevates**: an assembled context may contain less than the actor could reach, never more [PC L118–128]. This is §1.7's "derivation may narrow, never widen" applied to assembly. Its second rule, that provenance travels into the package, is already required by Ch. 0 §0.7 (step 3). Whether read access is governed at all remains open (OQ-25).
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
| 2026-10-18 | **Batch 4** (PC, DR, DE, AG, SI): baseline revisions R1-1…R1-13 (`BASELINE-REVISIONS.md`): §1.14.5 recorded confirmation; §1.17.5 filter-never-elevates and repaired contradiction with §1.7; §1.4 what closes a question, answered ≠ closed; §1.16.1 check-and-effect atomic, one owner with several mechanisms; §1.16.4 event kinds; §1.16.6 retention from pinning; §1.16.8 provenance of approval references; §1.7 visibility bound necessary not sufficient; §1.6 relevance ≠ authority example; §1.9 realization census (verified); §1.12 silent fail-open evidence | batch 4 |
| 2026-10-18 | **Batch-4 independent review corrections** (R1-3b, R1-5a, R1-6b, R1-7a, R1-8a, R1-9a, R1-11b, R1-12a, R1-13a in `BASELINE-REVISIONS.md`): §1.9 census recast as enforcement modes, "ladder measured" and "most sit low" withdrawn, "compiled" re-graded as one runtime guard on one path, INFERRED rows marked, third verdict vocabulary noted (F1–F3, F12, F18); §1.12 declaration vs behaviour, design-check defaults (F15, F17); §1.16.1 potential duplicate ownership, two readings (F6); §1.16.8 disagreement with DE kept as a trade-off (F11); §1.16.4 referent fixed, audit fields, projection notification and no assumed ordering (F14, F23); §1.7 stored vs inferred edge (F20); §1.6 filtering before use (F19); §1.4 prerequisites vs closing act, conjunctions/disjunctions, two closure rules, count 7 (F5); §1.16.6 policy-version pinning, idempotency identity (F22a, F23) | batch-4 review |
| 2026-10-18 | **Corpus batch C-1 (S0001–S0007):** additions RC-5…RC-11 at the ends of §1.1, §1.2, §1.6, §1.8, §1.10, §1.11 and §1.16 (decision = specification; provenance × standing as a pair; two keys; survival ≠ sufficiency; loop broken at generation; rule of three; identifier collisions; one home per rule; operating-unowned defect; ownership as boundary evidence; external validation cannot close an internal link). Record: `BASELINE-REVISIONS.md` § "Corpus batch C-1". TODOs T1-31…T1-37 |
| 2026-10-18 | **Corpus S0008:** RC-14 (four strands; prompt = Execution Asset; one meaning per term) at the end of §1.9 · RC-15 (I-7: no reusable method in a product space; breached) in §1.11. TODO T1-36 resolved; T1-38 added |
| 2026-10-18 | **Corpus S0009:** RC-17 note on the §1.8 lead (two added dimensions; domains do not own capabilities; two lifecycles) |
| 2026-10-18 | **Corpus S0010:** RC-18 (metadata ≠ knowledge; two knowledge bodies) at the end of §1.5 · RC-19 (unfired entry event of the loop; EEP quote) at the end of §1.8. TODOs T1-39, T1-40 |
| 2026-10-18 | **Corpus S0011:** RC-20 (§1.17.2, evidence for reserved decision 4) · RC-21 (§1.14.4, lifecycle decides derived vs authoritative) · RC-22 (§1.16.8, falsified worse than unevidenced) · RC-23 (§1.11, four tests of a real boundary) |
| 2026-10-18 | **Corpus S0012:** RC-24 (§1.2, primary source of the provenance/standing rule; cited-only → Src) · RC-25 (§1.14.4, derivation ≠ attestation) · RC-26 (§1.11, spaces nest). TODO T1-41 added; T1-15 evidence |
| 2026-10-19 | **Independent review C-1 corrections (S0006–S0010):** RC-7a, RC-8a, RC-10a, RC-14a, RC-15a, RC-17a, RC-18a, RC-19a (record: `BASELINE-REVISIONS.md` § "Corrections after the independent review") · **Corpus S0013:** RC-27 (§1.6 canon human-made), RC-26a (§1.11 nesting contested). TODOs T1-42…T1-45 |
| 2026-10-19 | **Corpus S0014:** RC-29 (§1.13, the register is not the complete record of authorizations) · RC-30 (§1.14.3, separation of duties as process, not people) · RC-31 (§1.8, the back-flow agreed fact). TODOs T1-46; T1-45 updated |
| 2026-10-19 | **Corpus S0015:** RC-33 (§1.13, the corpus first proposed "only if minted", then reopened it) · RC-34 (§1.14.5, the proposed-constitution paradox) · RC-35 (§1.14.3 author ≠ reviewer). TODO T1-47 |
| 2026-10-18 | **Review C2 corrections:** RC-20a, RC-21a, RC-23a, RC-24a, RC-26b, RC-29a, RC-30a, RC-33a (RC-33 withdrawn and rewritten), RC-34a, RC-35a (`BASELINE-REVISIONS.md` § "Batch C-2: corrections after independent review C2") |
| 2026-10-18 | **Corpus S0016–S0018:** RC-36 (§1.2 primary statement of authority), RC-37 (§1.6 granted vs earned), RC-38 (§1.14.4 qualification), RC-39 (§1.14.5 freeze), RC-40 (§1.17.1 decision-readiness) |
| 2026-10-18 | **Corpus S0019–S0020:** RC-42 (§1.11 three authority systems), RC-44 (§1.14.4 projection by DP-2) · Ch. 0: RC-43 (§0.2 identities, mission vs constraint) · Ch. 3 §3.5 new |
| 2026-10-18 | **Review C3 corrections:** RC-36a (T1-31 resolved by Int only; CON-0018 invalid), RC-37a (decree, not production), RC-40a (CON-0012 invalid; U-DM-4; unsampled generalization), RC-42a (subjects differ, holders overlap) · ordinary: F-14, F-16, F-17, F-22, F-26 |
| 2026-10-18 | **Corpus S0021:** RC-48 (§1.14.4, S0021 repeats RC-25; not independent) |
| 2026-10-19 | **Corpus S0022:** RC-50 (§1.11, external authority; T1-53) · RC-51 (§1.2, the split first appears from a counterexample) |
| 2026-10-19 | **Corpus S0023:** RC-53 (§1.17.1, the docket as an instance of decision-readiness) · RC-55 (§1.13, D-2 recommendation; the self-check is circular for verification, not authority) · Ch. 0: RC-54, RC-57 · Ch. 3: RC-58 · Ch. 5 opened (§5.1, RC-56) |
| 2026-10-19 | **Corpus S0024:** RC-59 (§1.14 root acts: genesis, the architecture has no root; T1-17 primary source) |
| 2026-10-19 | **Corpus S0025:** RC-64 (genesis: primary source, an experiment) · RC-65 (§1.14.3, a solo start cannot satisfy the protocol as written; T1-54) |
| 2026-10-19 | **From S0026 (rule RK-1): changes are recorded only in `BASELINE-REVISIONS.md`.** This log is closed |
