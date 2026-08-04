# PKS — How to Develop It Further

**A brainstorming proposal, written after reading the `ai_architecture/` corpus and the 96 PKS-named documents**

| | |
|---|---|
| **Kind** | **BRAINSTORMING PROPOSAL.** *Advisory. Adopts nothing, decides nothing, creates no directory, moves no file, mints no identifier* |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Status** | **REVISION 4** — revised 2026-08-01 after **four Principal Architect review rounds**, and after reading `docs/knowledge_tranfer/` (9 documents, incl. C4 L1–L4 and the SLR benchmark). **PA disposition on rev 3: *"approve as a Phase III brainstorming and implementation proposal"* — with the standing clarification recorded in §0.4.** *Rev 1 produced on request: "read the brainstorming documents in `architecture_legacy/ai_architecture/` and suggest how we can develop PKS further" + "read and analyse also all documentation named with PKS"* |
| **Inputs read** | `ai_architecture/pks/20260731_2300` · `_2301` · `_2313_how_to_develop_pks.md` · `ai_architecture/documentation/pks/20260801_1202_pks_folder_structure.md` · `pks_as_Possible_product.md` · `what_gemini_suggested.md` (outline) · **96 PKS-named documents inventoried** (17 read in full or in substance) · ⭐ **`docs/knowledge_tranfer/` — 9 documents, incl. `20260729_1812_pks_progress.md`, the C4 L1/L2/L3/L4 set, and the SLR methodology benchmark** |
| **Placement** | ⚠️ **CHOSEN, not derived** — written where instructed. *A derived placement would run `php scripts/doc-placement.php`; see §9.4* |

---

## 0.1 Revision 2 — what changed, and on whose authority

**Two Principal Architect reviews were delivered on rev 1. Both converged on the same architectural addition. Applied per the review gate — *review delivered → Authority disposes → apply → record*.**

> ⚠️ **ANNOTATED by revision 3 (see §0.2): row 1's phrase *"the layer is DESCRIPTIVE, not proposed"* was CORRECTED in rev 3 to *"the pattern is OBSERVED; the capability is EMERGING; the layer is NOT declared."*** **This row is left as written** — *a forward-pointer annotation, following the R-53 mechanism, because **history is never rewritten to manufacture consistency**.*

| # | PA disposition | Applied |
|---|---|---|
| **1** | ⭐ **Introduce a named layer between PKS and PublicDigit — "PKS Services" / "PKS Engineering Services" / a "PKS Capability Framework". Individual tools become its first implementations, not isolated tools** | ✅ **§2.5 (new) + Stage 1 reframed.** ⭐ **And strengthened with evidence the reviews did not state: FOUR members already exist and operate (926 lines). The layer is DESCRIPTIVE, not proposed** |
| **2** | **Define a Capability Level 1–4 roadmap instead of Stage 1–4 "tools"** | ✅ **§5.6 (new)** |
| **3** | **Restate PKS's identity as *reducing engineering uncertainty*, not merely *preventing wrong answers*** — *"an Engineering Decision Support System, not a documentation repository"* | ✅ **§8 rewritten** |
| **4** | **Drop the Stage 0 markdown register — use work-management items (PKS-H1..H7) so each hypothesis becomes Issue → Experiment → Evidence → Decision** | ✅ **Stage 0 rewritten.** *Accepted, with one repository-specific note: the existing work-management home is the backlog, and **ENG-008..011 already carry triggers written into the item** — the pattern exists* |
| **5** | *"Every PKS **begins with** the same methodology **unless operational evidence justifies adapting it**"* — not *"follows the same lifecycle"* | ✅ **§2.6 (new)** |
| **6** | *"The engineering **methodology** is reusable; the **discovery work** remains domain-specific"* | ✅ **§2.6 (new)** |
| **7** | **Soften *"2–3 weeks"*, *"70–90% faster"*, *"the lifecycle is identical"* into research hypotheses** | ✅ **§2.6 + hypotheses H8–H10.** ⚠️ **Factual note: those three claims are NOT in this document** — they belong to the companion (DeepSeek) lifecycle document. *Recorded here so the disposition has a home, since this proposal is the artifact being revised* |
| **8** | **Stage 1 authorized in substance** — *"this is the part I would implement immediately… almost a perfect first Phase III capability"* | ⚠️ **Recorded as a PA endorsement, NOT as an authorization.** *Per **RECOGNITION ≠ AGREEMENT ≠ ADOPTION**, and per the programme's own rule that **an endorsement is not an adoption**, Stage 1 still needs an explicit authorizing act before engineering begins* |

> ### ⚠️ **The one place I am deliberately NOT following the review's momentum.**
> Both reviews read as approval, and the second says *"this is exactly what I would authorize."* **That is an endorsement in the subjunctive — it is not an issued authorization.** *The programme records this exact failure mode five times over (PMR-8: "the Authority AGREED with an assessment-ordering rule and it was ROUTED, not adopted" · PMR-7: "acceptance of the evidence is not acceptance of the lesson").* **If Stage 1 is to begin, it needs one explicit act saying so.**

---

## 0.2 Revision 3 — calibrating certainty

**Round 3 raised no structural objection. Every disposition is about *how strongly a claim is stated* — which is the programme's closing meta-principle applied to my own text.**

| # | PA disposition | Applied |
|---|---|---|
| **1** | ⛔ **"The layer is REQUIRED" overclaims.** Say instead: *"the evidence now justifies EVALUATING this as a first-class architectural capability rather than treating the tools as unrelated utilities"* | ✅ **§2.5.2 rewritten.** ***The PA is right and I was wrong here*** — see §2.5.2 for why, in the programme's own terms |
| **2** | **PKS Services is an EMERGING capability, not an existing layer.** *"Formalizing it should occur once its boundaries, ownership, and interfaces stabilize"* | ✅ **§2.5.2 (rewritten) + §2.5.3 (new).** **Status downgraded: OBSERVED PATTERN → EMERGING capability. NOT a declared layer** |
| **3** | *"Stage 1 **completes the minimum viable validation capability**"* — not *"builds the first missing member"* | ✅ **Stage 1 reframed.** *Capability over member count: future readers care what capability is complete, not which numbered member was added* |
| **4** | ⭐ **Add SEMANTIC DIFF as a first-class Level 2 capability** — concepts added/removed · relationship changes · authority changes · governance impact | ✅ **§5.6.** ⭐ **And grounded with demand evidence the review did not cite: 54 documents in the corpus reference supersession, and *"what supersedes it?"* is already a criterion in the Integrity Model's traceability quality** |
| **5** | **PKS is *the structured semantic model of ONE product*, not "knowledge"** | ✅ **§2.6** |
| **6** | *"**PKS Services operationalize the product semantic model maintained by PKS**"* — not *"PKS operationalizes knowledge"* | ✅ **§2.6** |
| **7** | ⭐ **Ownership: PKS OWNS identifiers; the Registry OPERATES ON them** | ✅ **§2.5.3** — *and expressed in **PGP-02's** existing disposition vocabulary, which is where this distinction already has a home* |
| **8** | **Refine the loop: KnowledgeOS supplies the METHOD; each product owns its own PKS instance** | ✅ **§2.7 (new)** |
| **9** | **Sprint plan (1–4)** | ⚠️ **RECORDED WITH A FACTUAL CORRECTION — see §2.7.2. Sprint 1's "Reference Validator" and Sprint 2's "Placement Resolver" ALREADY EXIST as `link-check.php` and `doc-placement.php`** |
| **10** | **Commercial model** (KnowledgeOS sold · PKS not sold · Services licensable) | ⚠️ **Recorded as hypothesis H11, not adopted.** *The record says **feasibility, not commercial viability**, and round 1 itself scored commercial estimates ⭐⭐⭐☆☆* |
| **11** | *"The product is actually an ECOSYSTEM"* | ⚠️ **Recorded as H12 — and ROUTED: this is materially **EAD-1's D-1** ("three concerns or four?"). See §2.7.3** |

---

## 0.4 Revision 4 — capability over inventory, and one governing constraint found in `knowledge_tranfer/`

**PA disposition on rev 3: *"approve as a Phase III brainstorming and implementation proposal."* With one standing clarification, recorded verbatim because it governs every future revision:**

> ### **"The proposal should continue to treat *PKS Services* as an OBSERVED EMERGING CAPABILITY until operational evidence demonstrates stable boundaries, ownership, lifecycle, and interfaces."**

**And the round's characterization, adopted as this document's own self-description:**

> ### **An implementation strategy for Phase III — NOT a new architectural baseline.**

| # | PA disposition | Applied |
|---|---|---|
| **1** | ⭐ **Define the capability INDEPENDENTLY of the scripts.** *"Capability should never depend on today's implementation inventory"* | ✅ **§2.5.0 (new) leads with the responsibility; §2.5.1 demoted to "current implementations", cited as EVIDENCE OF the capability, never as its DEFINITION** |
| **2** | **Define Level 1 by RESPONSIBILITY, not by shipped scripts** — identifier · reference · placement · vocabulary · consistency validation | ✅ **§5.6 rebuilt.** *"Whether each is implemented by one script or five is secondary. Capabilities outlive implementations"* |
| **3** | **PKS is *the GOVERNED SEMANTIC KNOWLEDGE REPRESENTATION of a product*** — *"semantic model alone feels slightly too narrow"*; governance · evidence · vocabulary · relationships are all aspects | ✅ **§2.6.1 updated.** *Supersedes rev 3's "structured semantic model of one product"* |
| **4** | ⭐ **Three verbs, three concerns: KnowledgeOS DEFINES capabilities · PKS INSTANTIATES them · PKS Services EXECUTE them** | ✅ **§2.7.0 (new).** *This is the sharpest instrument in four rounds of review — see below* |
| **5** | **The five-tier stack:** KnowledgeOS → PKS Framework → Product PKS Instance → Engineering Services → Application | ✅ **§2.7.1 updated** |
| **6** | *"You are designing THREE different products, not one — today they share a repository; architecturally they are distinct"* | ⚠️ **Recorded as H12-refined, NOT adopted.** *It remains **EAD-1's D-1**, and "three products" is a stronger claim than "three concerns" — it asserts commercial separability, which H11 has no evidence for* |
| **7** | **Continue treating PKS Services as emerging** | ✅ **standing clarification, quoted above and repeated at §2.5.2** |

### ⭐ What rev 4 adds that no review round asked for — and it changes the architecture question

**Reading `docs/knowledge_tranfer/` surfaced a governing constraint and a mapping that together resolve the "is PKS Services a layer?" question without needing a new layer at all.**

**(a) `20260729_1812_pks_progress.md` §1.2 states it flatly:**

| Misconception | Correction |
|---|---|
| The PKS is a documentation system | ❌ documentation is **one capability** |
| ⭐ **The PKS is software** | ⛔ ***No — it is KNOWLEDGE SPECIFICATIONS (YAML + Markdown)*** |
| The PKS is an instruction manual for AI | ❌ AI guidance is **one application** |

> **So services — which ARE software — cannot be a layer *inside* PKS. They are a distinct concern that CONSUMES it.** ***This is the PA's own ownership rule (PKS owns the model; Services operate on it) reaching its structural conclusion.***

**(b) The C4 Level 2 container diagram gives PKS's governed containers from AD-1 — and the existing scripts already realize two of them:**

| Container (AD-1) | Responsibility | Realized today by |
|---|---|---|
| **AC-1 Knowledge Assessment** | *records evidence · evaluates against criteria · **issues judgments (verdicts)*** | ⭐ `link-check.php` *(its **100 / 99 / AMBIGUOUS / MISSING** confidence model **IS a closed verdict vocabulary** — exactly AD-1's **AP-8**)* · `knowledge-lint.php` · `doc-placement.php` |
| **AC-2 Knowledge Projection** | renders governed knowledge into consumable forms | `knowledge-graph.php` *(it **emits**)* |

> ### ⭐ **Consequence: the four scripts are not an unnamed NEW layer. They are unrecognized IMPLEMENTATIONS of containers AD-1 already defines — which is what ES-001.1 rule parsimony asks us to check before naming anything.**

**And AD-1's constraints on AC-1 turn out to *improve* the Stage 1 design rather than obstruct it:**

| Constraint | Effect on Stage 1 |
|---|---|
| **AP-7 — AC-1 cannot author its own criteria** | ✅ **the registry must NOT invent a numbering scheme** — which matches **M4's** explicit *"no new numbering scheme, no renumbering"* |
| **AP-5 — AC-1 cannot self-issue; the trigger originates outside** | ✅ **a PreToolUse hook is the architecturally CORRECT shape** — an external trigger, not a daemon |
| **AP-8 — outbound surface is a closed verdict vocabulary** | ✅ **the checker returns a bounded verdict set** (`free` / `collides` / `unregistered-series`), not prose |
| **AR-2 — no component may be defined over the unpartitioned core** | ⚠️ **the registry must operate on IDENTIFIERS and REGISTERS only — never model Decision · Term · Model element · Contract themselves.** *Doing so would be the "elegant-partition error at one remove"* |

**(c) ⭐ And M4 had already named the defect Stage 1 addresses.** `PKS_Phase_II_M4_Identity_and_Lifecycle_Model.md` establishes — **SUPPORTED WITH REFINEMENT, Medium-High** — that ***identity scope is per-kind, and THE REGISTER IS THE NAMESPACE UNIT***, with the finding that *"kinds with a governed register are coherent; **identity defects occur precisely where register discipline lapses**."* Its carried liabilities are: *bare-ADR unregistered series · **OQ- overload** · **R-nn cross-kind ambiguity** · Guide-step representational-only identity* — and: ***"no new numbering scheme, no renumbering, no remediation of the named collision liabilities — those are II.B/backlog."***

> ### **So Stage 1 is not a new idea. It is the REMEDIATION M4 EXPLICITLY DEFERRED — and the R-65..R-71 and C-1..C-4 collisions are further instances of a liability already on the record.**
>
> **M4 also supplies the registry's data model for free: per-kind identity modes — *1 durable-global (register-namespaced)* · *2 scoped-assigned* · *3 intrinsic / name-as-identity*.**

---

## 0.5 Revision 5 — FINAL revision. Terminology unfrozen, ecosystem confined, and the document closed

> ### **PA disposition on rev 4: APPROVED as *"Phase III Implementation Strategy (Brainstorming / Advisory)"* — expressly ⛔ **NOT as an architectural baseline**.**
>
> ### **And the operative instruction: *"At this point I would stop refining documents. The proposal has reached diminishing returns."***

**⭐ That instruction is honoured. This is the last revision. Further refinement of this document would be the very diminishing return the programme's own signal warns about** — *when successive commissions refine **how** decisions are expressed rather than discovering new architectural responsibilities, the correct next act is a transition, not another review.*

| # | PA disposition | Applied |
|---|---|---|
| **1** | ⭐ **"executes" → "OPERATES ON"** — *"execution is a technical word; operation describes the architectural responsibility more precisely"* | ✅ **§2.7.0.** *And the reason it matters: a service that "executes" invites **what runtime?** — Phase II.D's question, not this one* |
| **2** | ⛔ **Do NOT freeze "PKS Framework."** Use **PKS Method** until multiple PKS instances exist, then decide whether "Framework" has earned architectural status | ✅ **§2.7.1 renamed and marked unfrozen** |
| **3** | **The tier stack is *"the current best explanatory model"*, not "the architecture"** | ✅ **§2.7.1.** *Presented as **four tiers that today's evidence supports**, with the method/instance split shown as **a further refinement, NOT YET FROZEN*** |
| **4** | ⚠️ **Significant disagreement: the document sometimes sounds as though PKS becomes an ecosystem.** Today's evidence supports `KnowledgeOS → PKS → Engineering Services → Application`. It does **NOT** demonstrate `KnowledgeOS Platform → PKS Platform → Marketplace → Products` | ✅ **§0.5.1 (below) states the boundary explicitly, and every platform/marketplace/multi-product claim is confined to H2 · H4 · H11 · H12 · H12b** |
| **5** | **Keep repeating that Engineering Services is an emerging capability** | ✅ *stated at §2.5.0, §2.5.2, §2.5.4, §5.6 and here — **five times, deliberately*** |
| **6** | ⭐ **"Do we already have this?" before creating something new** — praised as *"classic architecture"* | ✅ **the AD-1 / AC-1 / AC-2 mapping stands as the document's method, not just one of its findings** |

### 0.5.1 ⛔ The ecosystem boundary — stated once, plainly

| ✅ **What today's evidence supports** | ⛔ **What it does NOT demonstrate** |
|---|---|
| `KnowledgeOS → PKS → Engineering Services → Application` | `KnowledgeOS Platform → PKS Platform → Marketplace → Products` |
| **one** product · **one** knowledge representation · **one** repository · **one** lineage | multi-product reuse · knowledge packs · a marketplace · commercial separability |
| a methodology corpus with **ONE lineage**, provisionally certified on **Method Design** only | any claim resting on **Operational Evidence**, which stands at **ZERO-INDEPENDENT** |

> ### **Every platform, ecosystem, marketplace and multi-product statement in this document lives in the hypothesis register — H2 · H4 · H11 · H12 · H12b — and nowhere else.**
>
> ***The programme's ceiling applies without exception: one corpus is one observation, and a second repository — not more analysis — is what would move it.***

---

## 0.3 Verdict up front *(unchanged since rev 1 — no review round contested it)*

> ### **The brainstorming corpus is largely right, and most of what it proposes has ALREADY been formally dispositioned. What it asks for as *builds* is Phase II.D — which is genuinely undecided and unstarted. What it asks for as *vision* is already captured, contradicted, or routed.**
>
> ### **My recommendation is NOT to build the knowledge graph, the dashboard, the API, or the compiler. It is to build ONE small thing that a GOVERNED PKS rule already requires and that nothing currently enforces — because that is the only move that satisfies the Phase III mandate instead of quietly violating it.**

**The single most important thing I found, and it is actionable today:**

> **PMR-10 — *"an identifier must be checked for collision before it is minted"* — is ADOPTED and GOVERNED.** It was adopted on **two escapes**, with the recorded note that *"the two existing collisions CANNOT be cured, since identifier stability forbids renaming — **adoption prevents the third**, it does not repair the first two."*
>
> ### ⛔ **The third escape is already present, undetected, in a drafted recommendation. And a fourth exists.**
>
> **There is no executable collision check anywhere in the repository** (`ls scripts/ | grep -iE "collis|ident|mint"` → empty).

**That is a governed rule, with repeated escaped-defect evidence, from unrelated directions, with zero automation. By this programme's own admission filter, it is the strongest candidate for the first real PKS capability — and it is small.**

---

## 1. What the corpus actually says

### 1.1 The three `how_to_develop_pks` documents (2026-07-31)

**Document 1 (23:00)** — six strategic hypotheses, each explicitly labelled *research*:

| # | Hypothesis |
|---|---|
| 1 | PKS is becoming an **engineering knowledge graph** |
| 2 | KnowledgeOS is a **platform** supporting multiple domains via knowledge packs |
| 3 | PKS is **executable** — generating OpenAPI, diagrams, catalogs, glossaries, scaffolding |
| 4 | **Claude is one client among many** (ChatGPT, Cursor, Windsurf, JetBrains, linters, CI) |
| 5 | **Operational evidence becomes a first-class governed asset** |
| 6 | **PKS as a compiler** — engineers update PKS; PKS generates code, docs, tests |

It closes with discipline I want to underline: ***"I would NOT start designing this architecture immediately… At this stage these are research hypotheses, not architectural decisions"*** — and recommends opening a notebook titled **"Phase III Research — PKS & KnowledgeOS Product Vision"** with every idea starting life as a hypothesis.

**Document 2 (23:01)** — seven practical builds: knowledge-graph export + query API · Claude Code tool integration · document-catalog-as-a-service · Vue dashboard · operational-evidence register · integration guides · prototype AI agent. Suggested tooling: Neo4j, Elasticsearch, Vue, Laravel API.

**Document 3 (23:13)** — the corrective, and **the strongest document in the set.** It agrees with the vision but **reverses the execution strategy**:

> ***"I would NOT build KnowledgeOS first… Instead I would make PublicDigit the laboratory."***
>
> ***"What prevents us from implementing PublicDigit efficiently today? Only build KnowledgeOS capabilities that remove those bottlenecks."***
>
> **Budget: 80% PublicDigit · 15% PKS evolution · 5% KnowledgeOS extraction.**
>
> **And the generalization of the programme's own closing principle:** ***"Every platform capability should generalize only the behavior that operational evidence has shown to be reusable."***

It also names a **fourth architectural asset** — **Operational Evidence as a first-class structured repository**, not an output.

### 1.2 `20260801_1202_pks_folder_structure.md` — already formally assessed

**This document has been through a governance act. It is EAD-1's "source document".** *(`docs/implementation/PKS_Phase_III_EAD_1_Ecosystem_Architecture_Discovery.md`)*

**EAD-1 found three of its factual claims contradicted by the repository — and all three change the answers:**

| Claim in the document | Repository |
|---|---|
| *"PublicDigit — ⏳ **To be built**"* | ⛔ **FALSE. 1,532 code files under `app/`**, incl. `app/Domain/Election`, `Finance`, `Locale`, `Shared`. **The product exists and is substantial** |
| *"PKS is **product-specific**"* | ⛔ **ONLY PARTLY TRUE.** The governance framework — Integrity Model · Review Method · ARB Discipline — contains **ZERO** occurrences of *vote · voting · ballot · election · voter*. **It is domain-free methodology sitting inside the Product concern** |
| The evolution rule's middle link | ⛔ **EMPIRICALLY EMPTY.** Operational Evidence is at **ZERO-INDEPENDENT**; **the arrow has never been traversed** |

**EAD-1 also found the proposed decomposition is *not* the governed one:** the document **splits Product in two and drops Runtime**, where ES-005.1 has **Product / Engineering Platform / Runtime mount**.

**And it recorded five unbundled decisions — these are the genuinely open ecosystem questions:**

| # | Decision |
|---|---|
| **D-1** | does the ecosystem have **THREE** concerns or **FOUR**? |
| **D-2** | is the domain-free governance framework part of the **knowledge** corpus or the **methodology** corpus? *(**the first real instance of the "PKS → KnowledgeOS extraction" the document's own rule describes**)* |
| **D-3** | should the **evolution rule become governing**? |
| **D-4** | should *"no arrow from idea to KnowledgeOS"* become a **prohibition**? |
| **D-5** | do **"PublicDigit", "PKS", "KnowledgeOS" become governed terms**? |

**⚠️ And E.1, which I consider the sharpest paragraph in the whole PKS corpus:**

> ***The entire Phase II corpus — sixteen reviews, five adoptions, three stabilizers, the whole framework — was produced by a path this rule would forbid. It came from ANALYSIS, not from operational evidence.***
>
> **Two readings — forward-looking or retroactive — and EAD-1 deliberately chooses neither**, because *"adopting D-3 without settling which reading applies would decide the corpus's own provenance by implication."*

### 1.3 An earlier brainstorming corpus was assessed the same way

`PKS_Brainstorming_Documents_Assessment.md` (PA commission, 2026-07-28) reviewed three earlier documents and returned **"I agree with roughly 90%… Neither the architecture nor the M0–M8 plan needs changing."** Its dispositions are instructive because **the same failure modes recur in the newer documents**: single-lifecycle assumptions against **OQ-9**, cardinalities contradicting measured evidence, and location tables overlapping a pending decision.

> **⭐ The pattern worth naming: successive brainstorming rounds keep re-deriving what the record already holds, and keep re-introducing the same three errors.** *The re-derivation is evidence the model is stable. The recurring errors are evidence the brainstorming is not reading the record.*

---

## 2. Where PKS actually stands — the part the brainstorming underestimates

| Layer | State |
|---|---|
| **Strategic architecture** | ✅ **COMPLETE.** M0–M8 · AD-1 · C4-1/C4-2 · AFV-1 · AIA-1 · CCP-1 · ERV-1 · MCA · CDR · IBC-1 |
| **Bounded contexts** | ✅ CBC-1 Knowledge Assessment · CBC-2 Knowledge Projection (both **accepted**, Medium-High) · CBC-4 Work Management **ADJACENT** · CBC-3 Normative Governance **candidate seam** · expressed-knowledge core **unpartitioned** |
| **Methodology baseline** | ✅ **SDM v1.2 / EOP v1.2 — FROZEN, reference-defined.** Process Under Configuration Control **DECLARED** |
| **Certification** | ⚠️ **Method Design PROVISIONALLY CERTIFIED · Operational Evidence ZERO-INDEPENDENT** |
| **Architecture handover** | ✅ **ADR-PKS-001** (`PROPOSED`, *ARB: APPROVED WITH MINOR REVISIONS`*) — the strategic baseline **is implementation-agnostic**; **IBC-1 is the semantic firewall** |
| ⛔ **Phase II.D — the implementation paradigm** | ⛔ **UNDECIDED and UNSTARTED. No Phase II.D artifact exists** |
| **Phase III** | ✅ **CHARTERED and ISSUED** 2026-07-31 — *operational use may begin* |

**⭐ ADR-PKS-001 already answers the brainstorming's biggest implicit question.** It names four candidate paradigms — **Java/Spring · PHP/Laravel · AI multi-agent · hybrid** — and rules:

> **The strategic baseline is implementation-agnostic and remains governing. Implementation-specific guidance — prompt governance, tool contracts, service decomposition, technology selection — belongs in Phase II.D, NOT in the strategic baseline.**
>
> **And the payoff: *implementation paradigms may evolve independently without requiring strategic rediscovery*, provided they satisfy the certified constraints and IBC-1.**

> ### **So every build in document 2 — graph, API, dashboard, agent, compiler — is a Phase II.D proposal. They are not blocked by missing architecture. They are blocked by an undecided paradigm, and by the Phase III mandate (§3).**

---

## 2.5 ⭐ The missing layer — OBSERVED as a pattern, EMERGING as a capability, not declared

**Both PA reviews called for a named layer between PKS and PublicDigit. They are right, and the case is stronger than either stated: the layer already exists in production. It has simply never been named.**

### 2.5.0 ⭐ The capability, defined by RESPONSIBILITY — independent of any implementation

**Per PA disposition 4.1: *"capability should never depend on today's implementation inventory."* So the definition comes first, and no script appears in it.**

> ### **PKS SERVICES**
>
> ### **Responsibility: to OPERATE ON the governed semantic knowledge representation maintained by PKS — without owning any part of it.**
>
> **Consumes:** the representation · its registers · its vocabularies · its relationships
> **Produces:** verdicts, resolutions, analyses — **never new knowledge content**
> **Owns:** execution. **Owns nothing else.**

**The membership test follows from the responsibility alone:**

| Ask | Then |
|---|---|
| Does it **operate on** the representation? | it is a **service** |
| Does it **hold part of** the representation? | it is **PKS** |
| Does it **define how** the representation is built? | it is **KnowledgeOS** |

**Capabilities named by responsibility, with no reference to what exists today:**

**identifier validation · reference validation · placement validation · vocabulary validation · consistency validation · relationship query · dependency analysis · impact analysis · semantic diff**

⚠️ **Everything in §2.5.1 below is EVIDENCE THAT THIS CAPABILITY IS EMERGING. It is not the definition, and the capability does not depend on it.** *If all four scripts were deleted tomorrow, the responsibility above would still be the correct statement of the capability — it would simply have no implementations.*

### 2.5.1 Current implementations — evidence of the capability, not its definition

| Service | Script | Lines | Its own stated purpose |
|---|---|---|---|
| **Placement Resolver** | `scripts/doc-placement.php` | **203** | *"resolve a documentation location from an artifact's classification"* |
| **Reference / Link Validator** | `scripts/link-check.php` | **221** | *"classify broken documentation links by EVIDENCE and confidence"* |
| **Knowledge Validator** | `scripts/knowledge-lint.php` | **343** | *"PHPStan for knowledge — validates every governed document against the data-driven schema"* |
| **Relationship Query** | `scripts/knowledge-graph.php` | **159** | *"emits a semantic knowledge graph… edges LABELLED by relationship type, so the graph shows not just THAT two documents connect but WHY"* |
| | | **926 lines** | **already in production, already consumed by engineering** |

**And a fifth member exists as configuration rather than code:** the controlled vocabularies in `docs/knowledge/schema/` — `bounded-contexts.yaml` · `statuses.yaml` · `authorities.yaml` · `knowledge-types.yaml` · `knowledge-relationships.yaml` · `knowledge-audiences.yaml` · `documentation-placement.yaml` · `repository-migrations.yaml`. **That is a partial *Concept Registry* already.**

**All four share one architectural shape, which is what makes them a layer rather than a pile:**

```
Behaviour ONLY in the script  ·  POLICY in an ADR  ·  CONFIGURATION in schema/*.yaml
                     ↓
        derived at runtime, never hardcoded
                     ↓
        consumed by engineering during real work
```

*Each script's own header says so — `doc-placement.php`: **"Behaviour only. Policy lives in [the ADR]. Configuration lives in [the YAML]."*** **That is the layer's contract, already written down four times.**

### 2.5.2 What the evidence justifies — and what it does not

> ### **The evidence now justifies EVALUATING this as a first-class architectural capability, rather than continuing to treat the tools as unrelated utilities.**
>
> ### ⛔ **It does NOT yet justify declaring that the layer EXISTS.**

**The claim, stated at exactly the strength the evidence supports:**

> **The repository contains several cohesive engineering services that strongly indicate an EMERGING PKS Services capability. Formalizing that capability should occur once its BOUNDARIES, OWNERSHIP and INTERFACES stabilize.**

**⚠️ Correcting rev 2 — this document said *"naming it now is REQUIRED rather than merely tidy."* That overclaimed, and the reason it overclaimed is instructive:**

| What rev 2 argued | Why it was insufficient |
|---|---|
| **PGP-01: *"tickets never accrete into architecture"*** → therefore naming is overdue | **PGP-01 forbids letting tickets SILENTLY BECOME architecture. It does not license declaring a boundary.** *It argues for **recognizing** the pattern — which is a different act from **formalizing** it* |
| Four cohesive instances = a cohesion refactoring, which needs no escaped defect | ✅ **still true, and still the right bar** — but a cohesion refactoring **reorganizes what exists**; it does not establish an architectural boundary. **Code count and stylistic similarity are not a responsibility model** |

**What is genuinely missing before the boundary can be formalized — each one is an ARB-owned question, not an engineering task:**

| Prerequisite | Present today? |
|---|---|
| A **stable responsibility** statement | ⚠️ **partial** — *"reduce engineering uncertainty"* (H7) is a hypothesis, not a ruled responsibility |
| An **ownership model** in **PGP-02's** vocabulary — *Owns / Coordinates / Preserves / Observes / Does-NOT-own* | ⛔ **NO. None of the four scripts declares one** |
| A **lifecycle** for the capability | ⛔ **NO** |
| **Interfaces** — a stated contract between the model and the services | ⚠️ **implicit only** *(the shared behaviour/policy/configuration split of §2.5.1)* |

> ### ⭐ **The resolution, and it is the programme's own vocabulary applied to an architectural layer rather than to a rule:**
>
> ### **RECOGNITION ≠ ADOPTION. Recognize the pattern NOW as an observation. Formalize the boundary LATER, as an authority act, when the four prerequisites above are answerable.**
>
> *Which is exactly what **PGP-05** already requires of any capability: **"a capability makes no un-sanctioned guarantees… evolution happens under pressure and governance, not aesthetics."***

### 2.5.3 Ownership — the distinction that makes the capability coherent

**Adopted from PA round 3, and it belongs in PGP-02's existing vocabulary rather than in new terms:**

| Concern | Disposition | Example |
|---|---|---|
| **PKS** | ⭐ **OWNS** the semantic model — identifiers, concepts, relationships, terminology | *PKS owns identifiers* |
| **PKS Services** | ⭐ **OPERATE ON** the model. They own **execution**, never **content** | *the Identifier Registry does **not** own identifiers — it checks them* |
| **KnowledgeOS** | **DEFINES** the method by which a PKS is built | never owns any product's model |
| **PublicDigit** | **CONSUMES** services | owns none of the above |

> ### ***A service that begins to own content has stopped being a service and become a second authoritative home — which is precisely what BRM-1 forbids.***

**This is also the sharpest available test for whether a proposed service belongs in the layer:** *does it **operate on** the model, or does it **hold** part of it?* **The first is a service; the second is PKS.**

### 2.5.4 What this reframes

| Before (rev 1) | After (rev 3) |
|---|---|
| Stage 1 builds **a new tool** | ⭐ **Stage 1 COMPLETES THE MINIMUM VIABLE VALIDATION CAPABILITY** |
| The Identifier Registry is *"PKS Service #1"* | **it is the gap in a capability that is already two-thirds present** |
| The layer is a proposal | **the PATTERN is observed; the LAYER is emerging and not yet formalized** |

⚠️ **Status discipline:** the pattern is **OBSERVED**; the capability is **EMERGING**; the layer is **NOT DECLARED.** ⛔ *Naming it is an authority act — **D-5** already asks whether "PKS" becomes a governed term, a further ecosystem token would enter the corpus, and **PMR-10 requires a collision check on any new label**.* **Do not mint "PKS Services" as governed vocabulary.**

---

## 2.6 Three wording refinements adopted from the PA reviews

**Each replaces an absolute claim with one the evidence supports. All three are consistent with the programme's closing meta-principle.**

| ⛔ Not | ✅ But |
|---|---|
| *"Every PKS follows the same lifecycle"* | ***"Every PKS BEGINS with the same methodology unless operational evidence justifies adapting it"*** |
| *"The lifecycle is identical"* | ⭐ ***"The engineering METHODOLOGY is reusable; the DISCOVERY WORK remains domain-specific"*** |
| *"A second PKS takes 2–3 weeks / is 70–90% faster"* | ***"Expected to become substantially faster than the first PKS"* — a hypothesis, measurable only after PKS #2, #3, #4** |

**⭐ Why the middle one is the load-bearing correction:** ***discovery is where the uncertainty lives.*** *A hospital is not a restaurant is not a voting system.* **The PROCESS may be reusable; the DIFFICULTY will not be.** *And this maps onto the record precisely: **SDM v1.2 / EOP v1.2 are the reusable method; M0–M8 was the domain work** — the method was frozen, the discovery was not repeatable.*

⚠️ **Factual note on provenance:** the three absolute claims above **do not appear in this document.** They belong to the companion lifecycle document reviewed alongside it. *They are dispositioned here because this is the artifact under revision, and because the refinements are worth carrying regardless of which document made the claim.*

### 2.6.1 Two definitional refinements adopted in rev 3

| ⛔ Not | ✅ But |
|---|---|
| **PKS is "knowledge"** *(rev 1)* · **PKS is "the structured semantic model of one product"** *(rev 3 — too narrow)* | ⭐ ***PKS is THE GOVERNED SEMANTIC KNOWLEDGE REPRESENTATION OF A PRODUCT*** |

**Why rev 3's wording was too narrow, per PA round 4:** *the semantic model is **one aspect**. **Governance** is another · **evidence** is another · **vocabulary** is another · **relationships** are another.* **"Representation" carries all four; "model" carries only the first.**

⚠️ **And the constraint that fixes the noun:** *`knowledge_tranfer/20260729_1812_pks_progress.md` §1.2 — **"the PKS is software — ❌ No: it is knowledge SPECIFICATIONS (YAML + Markdown)"***. **A representation is a specification. Software that acts on it is something else.**
| *"PKS operationalizes knowledge"* | ⭐ ***"PKS SERVICES operationalize the product semantic model MAINTAINED BY PKS"*** |

**Both are more precise, and the first is already half-present in the governing record:** *Charter §6.1 says **"PKS holds PublicDigit's STRUCTURED knowledge"** — "structured" is there; "semantic model of ONE product" adds the **instance-per-product** property, which is what makes §2.7's loop correct.*

⭐ **Why the second wording matters architecturally rather than stylistically:** *"PKS operationalizes knowledge" makes PKS both the model **and** the executor — **one concern with two responsibilities**, which objective 11 (Knowledge Cohesion) exists to reject. Splitting maintainer from operator gives each a single responsibility.*

---

## 2.7 The loop, corrected for multi-product — and two flags on the proposed architecture

### 2.7.0 ⭐ Three verbs, three concerns — the sharpest instrument from four review rounds

> ### **KnowledgeOS DEFINES capabilities · PKS INSTANTIATES knowledge · PKS Services OPERATE ON knowledge · the application CONSUMES results.**
>
> ⭐ ***"Operates on", not "executes"** (PA round 5). **Execution is a technical word; operation names the architectural responsibility.** A service that "executes" invites the question **what runtime?** — which is Phase II.D's question, not this one.*

| Concern | Verb | What it holds | What it must never do |
|---|---|---|---|
| **KnowledgeOS** | ⭐ **DEFINES** | the method · the capability *definitions* · standards · certification | ⛔ never holds any product's knowledge |
| **PKS** | ⭐ **INSTANTIATES** | one product's **governed semantic knowledge representation** | ⛔ **never executes** — *"the PKS is software: ❌ No"* |
| **PKS Services** | ⭐ **OPERATES ON** | operation only — verdicts, resolutions, analyses | ⛔ **never owns content** *(that would be a second authoritative home — BRM-1)* |
| **The application** | **CONSUMES RESULTS** | product behaviour | ⛔ never owns any of the above |

**Why the verb triple is worth more than the box diagram:** *the boxes can be drawn several defensible ways — which is why **D-1** is still open. **The verbs cannot.** A concern that both defines and instantiates has collapsed methodology into content; one that both instantiates and executes has collapsed specification into software.* ⭐ ***Wherever a future diagram is ambiguous, the verb resolves it.***

**And it is a usable test on any proposed addition:** *"which verb does this perform?"* — **if the answer is two verbs, it is two things.** *(Same shape as the governance invariant that a transition belonging to two categories **is** two transitions.)*

### 2.7.1 KnowledgeOS supplies the METHOD, not a pointer to an instance

**Adopted from PA round 3. The correction is small on the page and large in consequence.**

```
WHAT TODAY'S EVIDENCE SUPPORTS — four tiers:

KnowledgeOS            DEFINES        method · standards · capability definitions
     │
     ▼
PKS                    INSTANTIATES   the governed semantic knowledge representation
     │                                ⚠️ NOT software — knowledge specifications
     ▼
ENGINEERING SERVICES   OPERATES ON    validation · resolution · query · analysis · diff
     │                                ⚠️ SOFTWARE — therefore NOT part of PKS
     ▼
APPLICATION            CONSUMES       PublicDigit today
     │  produces
     ▼
OPERATIONAL EVIDENCE  ──▶  KnowledgeOS EVOLUTION   (evidence-gated · months, not weeks)


A FURTHER REFINEMENT, NOT YET FROZEN — splitting tier 2:

KnowledgeOS  ──▶  PKS METHOD  ──▶  PRODUCT PKS INSTANCE  ──▶  ENGINEERING SERVICES  ──▶  APP
                  (the reusable        (PublicDigit PKS ·
                   method)              Restaurant PKS · …)
```

> ### ⚠️ **This is the CURRENT BEST EXPLANATORY MODEL — not "the architecture."** *Same discipline as Phase II: the model is what the evidence presently best supports, and **D-1 remains open**.*
>
> ⭐ **And the terminology is deliberately NOT frozen (PA round 5): the second tier is called *PKS METHOD*, not *PKS Framework*.** ***"Framework" is an interpretation, and operational evidence has not shown it becomes a stable architectural element. Revisit when MULTIPLE PKS INSTANCES EXIST — then decide whether "Framework" has earned architectural status.***

⭐ **Two corrections carried from rev 4:** *(1) **the method is separated from the instance** — a method is not an instance *(named **PKS Method**, terminology unfrozen)*; (2) **Engineering Services sit BELOW PKS and OUTSIDE it**, because they are software and **"the PKS is software: ❌ No"**.*

> ### ⛔ **KnowledgeOS must NOT point directly at a PKS artifact. It supplies the method used to create product PKSs.**
>
> **Why it matters the moment a second product exists:** *if KnowledgeOS points at PublicDigit's PKS, then "the method" and "one product's model" are the same object — and a Restaurant PKS becomes a **fork** of PublicDigit's rather than an **instance** of the method.* **That is the single structural difference between a methodology and a template.**

**And it is the precise architectural form of §2.6's refinement:** *the methodology is reusable; the discovery work — and therefore each product's model — is domain-specific.*

### 2.7.2 ⚠️ Factual flag on the sprint plan — two of its deliverables already exist

**The proposed sprints are sound in ordering but double-build existing services:**

| Sprint | Proposed | Repository |
|---|---|---|
| **1** | Identifier Registry | ⛔ **missing — correct, this is the gap** |
| **1** | Identifier collision checker (PMR-10) | ⛔ **missing — correct** |
| **1** | **Reference Validator** | ⚠️ **EXISTS — `scripts/link-check.php`, 221 lines, with the ≥99 confidence model** |
| **2** | **Placement Resolver** | ⚠️ **EXISTS — `scripts/doc-placement.php`, 203 lines, operationally validated (first execution)** |
| **2** | Knowledge Query API | ⛔ missing *(`knowledge-graph.php` emits a graph; it does not answer queries)* |
| **3** | Dependency Analysis · **Semantic Diff** | ⛔ missing |
| **4** | AI Knowledge Assistant | ⛔ missing — **and gated on H3/H4** |

> ### **Corrected Sprint 1: Identifier Registry + collision checker. Nothing else — because the third deliverable is already shipped.**
>
> ***This is why §5.6 counts what exists before proposing what to build. "Reuse before create" is a promoted engineering behaviour (R-36), and it applies to the roadmap as much as to the code.***

### 2.7.3 ⚠️ Governance flag — the four-layer decomposition IS EAD-1's D-1

**The proposed architecture is a FOUR-concern decomposition. ES-005.1's ADOPTED decomposition has THREE: Product · Engineering Platform · Runtime mount.**

| Proposed | ES-005.1 |
|---|---|
| KnowledgeOS · PKS · PKS Services · PublicDigit | Product · Engineering Platform · **Runtime mount** |

> ### ⛔ **This is exactly what EAD-1 recorded as D-1: *"does the ecosystem have THREE concerns or FOUR?"* — and it notes that changing ES-005.1's decomposition *"is a change to an ADOPTED rule and must run its route."***
>
> ⚠️ **And it repeats one pattern EAD-1 already flagged in the earlier folder-structure document: THE RUNTIME CONCERN HAS NO COUNTERPART.** *`.claude/` is where the execution engine lives, and a decomposition that omits it cannot classify it.*

**Second flag, factual:** the proposed diagram places artifacts under **`./product/docs/pks/`**, **`./product/services/`** and **`./product/app/`**. ⛔ **`./product/` DOES NOT EXIST** *(`ls -d product` → no such file or directory)*. **Adopting those paths implies a new top-level directory and a repository restructure** — which **ES-005.2** forbids as speculative creation and which **EAD-1** already ruled *"no restructuring is currently authorized. None is recommended."*

> **Neither flag weakens the architecture. Both say the same thing: *this is input to D-1, not a decision*.**

---

## 2.8 ⚠️ Two flags on `docs/knowledge_tranfer/` itself — routed, not enacted

**The folder is a valuable input and also a governance liability. Both should be said.**

| # | Flag |
|---|---|
| **1** | ⭐ **A SECOND-HOME RISK for the C4 views (BRM-1).** *`docs/knowledge_tranfer/` holds a C4 L1/L2/L3/L4 set, and the L2 document ends with **"Where to save: `docs/architecture/c4/c4_level2_container_diagram.puml`"** — a destination it has not been written to.* ⛔ **Meanwhile `docs/implementation/PKS_Phase_IIC_C4_Architecture_Views.md` (38 KB) already exists in the governed corpus.** **Two C4 descriptions of the same system, one governed and one not, is exactly the *second authoritative home* BRM-1 forbids. Which is authoritative has not been ruled** |
| **2** | ⚠️ **The folder name is misspelled — `knowledge_tranfer`, not `knowledge_transfer`** *(verified: `docs/knowledge_transfer` does not exist)*. **And its placement was never derived.** *It is cited by name in the governed corpus — the 2026-07-28 brainstorming assessment records its inputs as living in `docs/knowledge_tranfer/` — so **the misspelling is now load-bearing in a governed citation** and cannot be corrected by rename alone (it needs the migration-registry route)* |

**⭐ And one substantive observation, which is the useful part rather than the tidy part:** *the C4 L3 and L4 documents are **disciplined refusals**, and they are worth reading as exemplars.* **L3 declines to supply a conventional component view** — *"AD-1 allocates responsibilities to containers but does not define internal structure… **nested boxes would invent structure**"* — and supplies a Knowledge Structure View instead. **L4 declines outright**, deferring to Phase II.D. ***Two diagrams that refuse to draw what the evidence does not support. That is H7 (reducing uncertainty by refusing unsupported answers) demonstrated in the architecture documentation itself.***

---

## 3. The tension nobody in the corpus has named

**Phase III's operating rules say, verbatim:**

> ⭐ **Effort goes to PublicDigit engineering. No further governance document unless NEW EVIDENCE requires it.**
>
> ⛔ ***A Phase III act that produces a governance artifact without producing operational evidence has FAILED THE MANDATE — regardless of the artifact's quality.***
>
> ⏳ **Give it TIME before changing KnowledgeOS — *months of real engineering, not weeks*.**

**Now read document 2's seven builds against that.** A Neo4j graph, an Elasticsearch index, a Vue dashboard and a REST API are **a new software product**. They are not PublicDigit engineering, and they do not produce evidence about PublicDigit.

> ### **The tension, stated plainly: building PKS software is the most exciting thing available and the least compliant with the mandate the programme just issued.**

**Document 3 already resolved it and deserves the credit — *"make PublicDigit the laboratory"*, 80/15/5. My proposal is document 3's strategy made concrete, with one addition it did not have: a specific first slice.**

**And there is a legitimate reconciliation, which is the hinge of this whole proposal:**

> **A PKS capability that is CONSUMED BY PublicDigit engineering is not a detour from the mandate — it is the mandate.** *It is the only construction that moves Operational Evidence off zero-independent, because the evidence is generated by the consumption, not by the artifact.*
>
> ⛔ **A PKS capability built for its own sake — however elegant — is Phase II under a new name.**

---

## 4. The three questions I would put before any build

| # | Question | Why it gates everything |
|---|---|---|
| **1** | **Is PKS an ASSET of this programme, or a PRODUCT?** | The corpus is split. `pks_as_Possible_product.md` was already corrected from *"✅ Yes"* to ***"Potentially yes. Current evidence supports FEASIBILITY, not commercial viability."*** **A product needs a product programme; an asset needs a consumer. These are different budgets, owners and risks** |
| **2** | **Which corpus does the domain-free framework belong to?** *(EAD-1's **D-2**)* | **It is the first real extraction instance the evolution rule describes.** *Answering it exercises the rule on a real case instead of adopting the rule in the abstract* |
| **3** | **What prevents PublicDigit engineering from going faster today?** | *Document 3's question, and the only one whose answer is measurable.* **Every PKS capability should be justified by an answer to it** |

---

## 5. ⭐ The proposal — five stages, each gated, the first one small

### Stage 0 — Route the hypotheses into WORK MANAGEMENT, not into a document *(hours)*

⭐ **Revised per PA disposition 4, and the revision is better than rev 1.** *Rev 1 proposed a markdown research register. **A register is a document, and Phase III says a document without operational evidence fails the mandate** — so rev 1's own honest-risk note was in fact an argument against it.*

**Instead: each hypothesis becomes a work item, so it has a lifecycle rather than a paragraph.**

```
PKS-H1 … PKS-H10        Issue → Experiment → Evidence → Decision
```

| ID | Hypothesis | Refuted by |
|---|---|---|
| **H1** | PKS is an engineering knowledge graph | graph traversal never being the measured bottleneck |
| **H2** | KnowledgeOS is a reusable multi-domain platform | a second domain requiring methodology changes at the root |
| **H3** | PKS is executable / generative | generated artifacts needing more correction than hand-authored ones |
| **H4** | Many AI clients consume PKS | only one client ever consuming it |
| **H5** | Operational evidence is a first-class asset | evidence never changing a decision |
| **H6** | PKS is a compiler | the knowledge model never reaching the precision code generation needs |
| ⭐ **H7** | **PKS's primary value is REDUCING ENGINEERING UNCERTAINTY** *(§8)* | **validators/resolvers producing no measurable reduction in defects or rework** |
| **H8** | A second PKS is **substantially faster** than the first | PKS #2 taking comparable effort |
| **H9** | The **methodology** is reusable across domains | SDM/EOP needing domain-specific forks |
| **H10** | **Discovery difficulty is domain-specific** *(the counterpart to H9)* | discovery effort proving roughly constant across domains |
| ⚠️ **H11** | **The commercial model holds** — KnowledgeOS sellable as a platform · PKS built per product and not sold · **PKS Services licensable** | *no third party willing to adopt the methodology without the product; or services proving inseparable from the product's own repository.* ⛔ **The record permits only *"feasibility, not commercial viability"*** |
| ⚠️ **H12** | **The product is an ECOSYSTEM, not any one of the assets** | *the assets proving separately valuable but not jointly.* ⛔ **ROUTED: materially **EAD-1's D-1**. Not decidable here** |
| ⚠️ **H12b** | ⭐ **They are THREE DIFFERENT PRODUCTS that merely share a repository today** *(PA round 4)* | *any one of them proving commercially inseparable from the others.* ⚠️ **A STRONGER claim than H12: "three concerns" is architectural; **"three products" asserts commercial separability**, which H11 has no evidence for. Recorded at the weaker strength the evidence supports** |
| ⭐ **H13** | **PKS Services is a real architectural boundary** *(not merely cohesive utilities)* | **the four prerequisites of §2.5.2 — stable responsibility · ownership model · lifecycle · interfaces — failing to stabilize across further iterations** |

**Plus EAD-1's D-1..D-5 as decisions, not hypotheses** — they are already ARB-owned.

| Why work items beat a register | |
|---|---|
| ✅ **The pattern already exists** | **ENG-008..011 carry their activation triggers written INTO the item** — precisely because *"a trigger recorded only in a report is a trigger nobody will see when the item is next read"* |
| ✅ **It keeps Phase III honest** | an item is tracked work, not a governance artifact |
| ✅ **Refutation is a required field** | *forces each hypothesis to be falsifiable — the same discipline the External Framework Evaluation Rule applies to outside material* |
| ⚠️ **One caveat** | **the repository's work-management home is the backlog** (`docs/implementation/backlog/BACKLOG.md` → EPIC → progress), **not GitHub Issues.** *If Issues are preferred, that is a tooling choice to state explicitly — the backlog is what exists today* |

### Stage 1 — ⭐ The thinnest consuming slice: make PMR-10 executable *(days)*

**This is the concrete recommendation. It is the smallest thing that is simultaneously a PKS capability, a PublicDigit engineering tool, and a producer of operational evidence.**

> ### ⭐ **Reframed in rev 3: Stage 1 COMPLETES THE MINIMUM VIABLE VALIDATION CAPABILITY.**
> **It is not "a new tool", not "PKS Service #1", and not "the fifth member".** *The Placement Resolver, Link Validator and Knowledge Validator already ship; the Identifier Registry is the **gap that keeps the validation capability incomplete**.* **The question a future reader will ask is *"what capability is complete?"* — not *"which numbered member was added?"***
>
> ⭐ **And that framing makes the acceptance criterion obvious:** *Stage 1 is done when **every identifier series in the corpus can be checked before minting** — not when a script exists.*

**The evidence, laid out as the admission filter wants it:**

| | |
|---|---|
| **The rule** | **PMR-10 — *an identifier must be checked for collision before it is minted*. ADOPTED. GOVERNED** |
| **Adopted on** | **two escapes (n=2)**, with the note that *"the two existing collisions CANNOT be cured — **adoption prevents the third**"* |
| **Escape 3 — present RIGHT NOW in a drafted recommendation** | **R-65..R-71** exist as *approved-in-substance, unminted* governance conclusions cited across **≥8 documents** (R-65 repository-is-a-workspace · R-66 documentation-by-DOMAIN · R-67 `engineering/`-is-cross-product-scope · R-68 ES-005-resolves-by-rule · R-69 R-39-is-the-precedent · **R-70 the Artifact Classification Model** · **R-71 Placement Is Derived**) — **while the Slice 7C pre-authorization recommends minting R-65 = authorization and R-66 = acceptance.** *The recorded warning said only "R-61..R-64 already issued"* |
| **Escape 4** | **`C-1..C-4`** denotes both the **7C governance decisions** (authorization wording · release governance · test amendment · identifier allocation) **and** the **WP-7 enforcement constraints** (C-1 *never define/default/clamp a duration* · C-2 config-key uniqueness · C-4 construction exclusivity). ***"C-1 is the constraint whose manual enforcement is insufficient"* and *"dispose C-1"* refer to different objects** |
| **Near-miss, same class** | **`app/Contexts/Election` · `app/Contexts/Elections` · `app/Domain/Election`** — three near-homonyms; `bounded-contexts.yaml` registers two and **the one inside the Deptrac perimeter has no key** |
| **Automation today** | ⛔ **NONE.** `ls scripts/ | grep -iE "collis|ident|mint"` → empty. **Every PMR-10 check in the corpus was performed BY HAND and recorded in prose** *(e.g. "PMR-10 (GOVERNED) collision check performed: `SDM-EXT-1` · `EXT-1` · `BASELINE-EXT-1` all verified unused")* |

**What to build — deliberately unglamorous:**

```
1. An identifier registry            one YAML: series · id · subject · status · minted-where
                                     seeded from what EXISTS (R-nn, ES-nnn.n, ADR-T/UL/PL/MP,
                                     CI/BI, PMR, MCR, OQ, D-n, C-n, ENG-nnn, WP-n, CBC-n, PS-nn)
2. A checker                         scripts/identifier-check.php
                                     "is <id> free in series <s>?" → exit 0/1
                                     "what collides?" → report
3. A non-blocking hook               PreToolUse: a NEW identifier appears in a Write/Edit → prompt
                                     the PMR-10 question. A checkpoint, not a wall
```

**Why this slice and not another:**

| Test | Result |
|---|---|
| **Admission filter** — *did a real defect escape because this was missing?* | ✅ **Four times, from unrelated directions** |
| **Deterministic work may be automated** | ✅ *"is this string already used in this series?"* is **pure lookup — no judgement** |
| **Second-consumer rule** | ✅ **consumers exist today**: R-numbers · C-identifiers · PMR/MCR labels · context names · ENG/PS/OQ items |
| **Governance precedes automation** | ✅ **the rule is already GOVERNED. This adds no governance — it enforces existing governance** |
| **Runtime tooling never duplicates governance** | ✅ **the checker reads the registry and quotes no rule text**, following the `engineering-placement-guard.sh` precedent |
| **Phase III mandate** | ✅ **it is consumed by PublicDigit engineering on the very next ruling, and it produces operational evidence — did it prevent a collision?** |
| **Is it a PKS capability?** | ✅ **identifier identity and collision are CBC-1/expressed-knowledge concerns — Decision · Term · Model element are exactly the core PKS holds** |
| **Size** | ✅ **one YAML + one script + one hook.** *No graph database, no API, no dashboard, no paradigm decision* |

> ### **And the sharpest argument for it: PMR-10 was adopted specifically to PREVENT THE THIRD collision — and the third is already in a drafted recommendation, undetected, because a governed rule was left to manual prose. That is not a hypothetical gap. It is a governed control with a live, current failure.**

⚠️ **Two honest limits.** **(a)** This is **one corpus** — so it evidences *the mechanism works here*, never *the mechanism is reusable*; a KnowledgeOS candidate needs another repository. **(b)** ⛔ **The registry must NOT be used to "fix" existing collisions — identifier stability forbids renaming, and PMR-10's own adoption note says the first two cannot be cured.** *It prevents; it does not repair.*

### Stage 2 — Operational evidence as a real asset *(weeks, and only alongside real slices)*

**Document 3's fourth asset. The one place I would follow the brainstorming almost exactly** — because the Charter's own §6.1 loop needs a landing place, and PKS's job is *to hold PublicDigit's structured knowledge*.

**But shape it as the record already shapes it, not as `evidence.yaml`:**

> **The PKS observation shape already in use:** `Observation → Evidence (with method) → Classification → Recommendation → Reproduction command`

**Two observations already exist in `docs/pks/`** (documentation-debt, documentation-classification-gap). **Stage 2 is: keep producing them per slice, with the reproduction command, and nothing more.** Five or six real ones are worth more than a schema for a hundred.

⚠️ **Do NOT create `docs/evidence/` because a diagram shows it** — **ES-005.2**: a directory exists when its first artifact arrives, and placement is **resolved**, never chosen.

### Stage 3 — Settle D-2 on the real case *(one commission)*

**D-2 asks whether the domain-free framework belongs to the knowledge corpus or the methodology corpus. EAD-1 already established the fact that decides it: *zero voting-domain terms* in the Integrity Model, Review Method and ARB Discipline.**

**Why this one before the others:** it is **the first real instance of the extraction the evolution rule describes**, so answering it **exercises the rule on evidence** instead of adopting the rule in the abstract (**D-3**) — and it touches ~3 governed artifacts, which is small.

⚠️ **It is a change-control act, not a design act** — relocation under the freeze moves promoted artifacts.

### Stage 4 — Only then, Phase II.D *(a real decision, later)*

**When — and only when — Stages 1–3 have produced evidence about what PKS consumption actually needs, put the paradigm question to the ARB with ADR-PKS-001's four candidates and one addition the brainstorming implies but never states:**

| Candidate | Note |
|---|---|
| Java/Spring · PHP/Laravel · AI multi-agent · hybrid | ADR-PKS-001's four |
| ⭐ **"No system" — PKS stays files + small deterministic scripts** | **the brainstorming never considers this, and on today's evidence it is the leading option.** *`doc-placement.php` and `link-check.php` already demonstrate that a resolver + a registry beats a platform for this class of problem* |

**Only after that: the graph, the dashboard, the API, the agent — *"because now you'll know what people actually need, not what seems elegant today."***

---

### 5.6 ⭐ Capability Levels — the roadmap the PA asked for

**Adopted per PA disposition 2. Stages describe *when work happens*; levels describe *what capability exists*. The levels are the better long-term frame because they connect to KnowledgeOS, and — critically — Level 1 is already two-thirds built.**

```
LEVEL 1 · VALIDATION — "is this consistent?"
   ⭐ DEFINED BY RESPONSIBILITY. Implementation counts are secondary and may change.

   identifier validation    ⛔ NOT COVERED   ← STAGE 1. PMR-10 GOVERNED and unenforced
   reference validation     ✅ covered       (one implementation today)
   placement validation     ✅ covered       (one implementation today)
   vocabulary validation    ⚠️  PARTIAL      (schema enumerations exist; no resolver over them)
   consistency validation   ⚠️  PARTIAL      (card-level only; not corpus-wide)

   ⭐ Level 1 is COMPLETE when all five responsibilities are covered —
      NOT when a particular number of scripts exists.

LEVEL 2 · QUERY & ANALYSIS — "what does this affect?"           █░░░░░░░░░  1 of 5
   ✅ Relationship Query          scripts/knowledge-graph.php    159 lines
   ⛔ Knowledge Query             (the graph EMITS; it does not ANSWER)
   ⛔ Dependency Analysis · Impact Analysis
   ⭐ SEMANTIC DIFF               ← added rev 3. NOT git diff, NOT document diff:
                                    concepts added/removed · relationship changes ·
                                    authority changes · governance impact

LEVEL 3 · GENERATION — "produce it from the model"              ░░░░░░░░░░  0 of 4
   ⛔ Knowledge compiler · code generation · doc generation · architecture analysis
   ⚠️  HYPOTHESIS H3/H6. Requires a precision the knowledge model does not yet have

LEVEL 4 · PLATFORM — "other products consume it"                ░░░░░░░░░░  0
   ⛔ Multi-product · reusable packs · AI services
   ⚠️  HYPOTHESIS H2/H4. Blocked on evidence from a SECOND repository, not on effort
```

> ### ⭐ **Why SEMANTIC DIFF is the strongest Level 2 candidate — with demand evidence the review did not cite.**
>
> **The corpus already runs on supersession relationships and cannot currently compute them.** *`grep -rlE "what supersedes it|Superseded|supersession" docs/implementation/` returns **54 documents**.* **And *"what supersedes it?"* is not incidental — it is a named criterion inside the Integrity Model's TRACEABILITY quality.**
>
> **Three existing mechanisms are doing this work by hand today:** **ES-004.3 status annotations** · **R-53's forward-pointer annotation** *(invented precisely because supersession was **forward-linked only**, leaving a reader at the old ruling with no pointer onward)* · **ADR-T23 → T17 supersession**, tracked in prose.
>
> ***A capability whose absence has already forced three manual workarounds is not speculative — it is the one Level 2 member with escaped-defect evidence behind it.***

**Three things this framing buys that "Stage 1..4" did not:**

| | |
|---|---|
| **1** | ⭐ **It shows Level 1 is nearly complete — as a RESPONSIBILITY set, not a script count.** *Two of five responsibilities covered, two partial, **one not covered at all**. The programme is not starting a PKS platform; it is **finishing a validation capability**. A much smaller and much more defensible claim* |
| **2** | **It makes each level's gate explicit:** *Level 2 waits on Level 1 being routinely used · **Level 3 waits on H3 · Level 4 waits on a second repository, which is the "one corpus is one observation" bar restated as a roadmap gate*** |
| **3** | **It connects to KnowledgeOS the way the PA's loop does:** *KnowledgeOS **defines** capabilities → PKS **implements** them → PublicDigit **uses** them → **operational evidence** improves KnowledgeOS* |

⚠️ **Status: the level model is a PROPOSED ORGANIZING FRAME, not governance.** *Level 1's membership is observable fact; Levels 3–4 are hypotheses with names. **Do not let the ladder's existence imply the upper rungs are planned work.***

---

## 6. What I would NOT do, and why

| ⛔ | Reason |
|---|---|
| **Build the knowledge graph / Neo4j / Elasticsearch / dashboard / REST API now** | **Phase II.D is undecided; the Phase III mandate says effort goes to PublicDigit; and there is no measured bottleneck these remove.** *"What prevents us from implementing PublicDigit efficiently today?" — none of these is the answer* |
| **Create the seven directories from the folder-structure diagram** | **ES-005.2 forbids speculative creation. EAD-1: *"all seven proposed directories are absent… the lawful route is reserved namespaces in the README table"*** |
| **Write `ECOSYSTEM.md` / `EVOLUTION.md`** | **EAD-1/BRM-1: they would restate governed material — *under Model B that creates a second authoritative home*** |
| **Adopt the evolution rule as governing (D-3)** | **the entire Phase II corpus was produced by a path it would forbid.** *Adopting it without settling forward-looking vs retroactive decides the corpus's own provenance by implication* |
| **Adopt *"no arrow from idea to KnowledgeOS"* as a prohibition (D-4)** | **a prohibition is a control; the strengthened filter requires repeated evidence that its absence let defects escape** |
| **Repeat *"PKS is product-specific"*** | ⛔ **verified only partly true — the framework is domain-free** |
| **Repeat *"PublicDigit — to be built"*** | ⛔ **verified false — 1,532 code files** |
| **Say PKS "is" a knowledge graph / platform / compiler** | ⚠️ **all six are RESEARCH hypotheses. *Operational Evidence at zero-independent means no demonstrated multi-consumer reuse; what is demonstrated is a methodology corpus with ONE lineage*** |
| **Open a new governance cycle for any of this** | *any further substantial governance work begins as a **NEW CYCLE with a NEW MANDATE**, triggered by new evidence — never by reopening lawful terminal states* |
| **Treat "PKS as a compiler" as a roadmap item** | *it is the most interesting idea in the corpus and the furthest from evidence.* **Keep it as hypothesis 6** |

---

## 7. Where I disagree with the brainstorming — specific and checkable

| # | Claim | My position |
|---|---|---|
| **7.1** | *"PKS is currently YAML files in `./docs/pks/`"* (doc 2) | ⛔ **FALSE. `docs/pks/` holds a README and two observations. The PKS corpus is ~91 markdown documents in `docs/implementation/`.** *There is no `concepts.yaml`, no `relationships.yaml`, no `document_catalog.yaml` — **the entire premise of "export PKS YAML as JSON" has no source artifact*** |
| **7.2** | *"Claude Code already loads PKS via `CONTEXT.md`"* (doc 2) | ⚠️ **Overstated.** `.claude/CONTEXT.md` carries runtime working state; it does not load a PKS model. *And **CONTEXT.md is currently stale** — its `Next action` line was superseded the same day it was read* |
| **7.3** | *"PKS ✅ Complete (Phase II)"* (folder-structure doc) | ⚠️ **True of the strategic model; false of the system.** **Phase II.D does not exist. "Complete" invites "so we can build on it" when the paradigm is undecided** |
| **7.4** | The priority order *(graph → Claude integration → catalog → dashboard)* | ⛔ **Inverted.** *Doc 3 already corrects this, and doc 2's own summary table quietly agrees — it ranks **"Collect Operational Evidence"** as priority 1* |
| **7.5** | *"Operational evidence drives evidence-based evolution"* as if the loop runs | ⚠️ **The loop has been traversed ONCE, and it stopped at the decision point.** *It showed evidence REACHES authority; it has not shown evidence CHANGES the model* |
| **7.6** | The document catalog with `required: true` fields | ⚠️ **`required: true` is a governance decision nobody has made** — the same flag the 2026-07-28 assessment raised, **recurring unchanged** |
| **7.7** | *"KnowledgeOS = `./engineering/`"* | ⚠️ **This is exactly OQ-5, ARB-owned and unresolved.** **R-67** holds `engineering/` expresses **cross-product SCOPE, not a domain.** *Asserting the equation decides OQ-5 by assertion* |

---

## 8. The one thing I would add that the corpus misses

**All three documents treat PKS's value as *representation* — graph, catalog, dashboard, compiler. I think the demonstrated value so far has been *refusal*.**

**The two strongest PKS-adjacent results in the record are both refusals:**

| | |
|---|---|
| **The resolver returned `PENDING`** for a cross-product research artifact **and the response was to STOP, not invent a location** | recorded as ***"a model that REFUSES is as much evidence as one that answers"*** |
| **The link-repair tool was TIGHTENED to unique-candidate-only** after a first version picked the *"best"* candidate | ***a plausible-looking wrong answer that would have silently corrupted navigation*** |

> ### **Rev 1's formulation: *PKS's primary operational value is preventing confident wrong answers, not generating right ones.***

**⭐ The PA sharpened this, and the sharpened version is better. Adopted as H7:**

> ### **PKS's purpose is REDUCING ENGINEERING UNCERTAINTY.**
>
> **Which subsumes: preventing wrong answers · refusing unsupported answers · detecting collisions · validating references · resolving placement · exposing ambiguity · preventing hallucination.**
>
> ### **That makes PKS an ENGINEERING DECISION SUPPORT SYSTEM, not a documentation repository.**

**Why the broader framing is the right one, in this programme's own terms:**

| | |
|---|---|
| **It covers the REFUSALS, which "preventing wrong answers" only half-covers** | *exit code 2 → `PENDING` → escalate is **not** the prevention of a wrong answer; it is the **refusal of an unsupported one**. Different act, same value* |
| **It names the measurable quantity** | *"uncertainty reduced" is observable per slice: **did the check fire? did it change what engineering did?** — which is exactly what the Phase III charter asks to be observed* |
| **It gives the layer in §2.5 a single responsibility** | *four existing services + two missing ones all reduce uncertainty. **That shared responsibility is what makes them a layer** — and objective 11 (Knowledge Cohesion) is satisfied by having one, not several* |
| **It explains the roadmap ordering without appealing to taste** | **validators reduce uncertainty per unit of effort more than generators do, because a generator that is wrong INCREASES it** |

*(A knowledge graph answers "what is related to what?" — useful. A collision check answers "are you about to create a contradiction?" — and on this repository's evidence, that is the question that keeps going wrong.)*

⚠️ **Still a hypothesis (H7), not a finding.** *It is refuted if validators and resolvers produce **no measurable reduction in defects or rework** — and Stage 1 is its first test. **The identity claim is attractive, which is exactly why it needs a refutation condition.***

---

## 9. Open questions for the human — I am not deciding these

| # | Question | Why I cannot answer it |
|---|---|---|
| **9.1** | **Is PKS an asset or a product?** | a business decision. *Feasibility ≠ commercial viability* |
| **9.2** | **Does Stage 1 count as PublicDigit engineering, or as a detour?** | ***I believe it counts, because engineering consumes it on the next ruling — but the Charter's mandate is the ARB's to interpret, not mine*** |
| **9.3** | **Do you want Stage 0's register at all?** | *it is a document, and Phase III is suspicious of documents.* **I can skip it and fold the hypotheses into this file instead** |
| **9.4** | **Should this proposal live where you asked?** | ⚠️ **Its placement was CHOSEN, not derived.** `--scope=product-specific --domain=pks` would resolve to `docs/pks`. *A brainstorming folder is a defensible home for a brainstorming artifact — but the record should say which rule was followed* |
| **9.5** | **Who owns the R-65..R-71 collision?** | ⭐ **It is C-4's input, and C-4 is on the ARB's queue for Slice 7C. My recommendation: raise it before the 7C ruling text is drafted, regardless of anything else in this proposal** |

---

## 10. Summary — the proposal in one table

| Stage | Do | Size | Gate |
|---|---|---|---|
| **0** | route **H1..H10 + D-1..D-5** into **work management** — each with a **refutation condition**. ⛔ *not a document* | hours | none — recognition only |
| **1** | ⭐ **make PMR-10 executable** — identifier registry + checker + non-blocking hook. **The first missing member of an existing four-member layer** | days | ⚠️ **No NEW governance is needed — the rule is already GOVERNED. But an explicit AUTHORIZING ACT is still required: *an endorsement is not an adoption*** |
| **2** | keep producing **PKS observations** per real slice, with reproduction commands | ongoing | none — the shape exists |
| **3** | settle **D-2** on the real case (the domain-free framework) | one commission | ARB; change-control act |
| **4** | **Phase II.D paradigm decision** — with *"no system"* as a first-class candidate | later | **evidence from 1–3** |

> ### **80% PublicDigit · 15% PKS evolution · 5% KnowledgeOS extraction — document 3 had it right. Stage 1 is what the 15% should buy first.**
>
> ### **And the one line I would carry out of the whole corpus:**
> ***Every platform capability should generalize only the behavior that operational evidence has shown to be reusable.***

---

## Traceability

**Brainstorming corpus read in full:** `architecture_legacy/ai_architecture/pks/20260731_2300_how_to_develop_pks.md` · `…_2301_…` · `…_2313_…` · `architecture_legacy/ai_architecture/documentation/pks/20260801_1202_pks_folder_structure.md` · `architecture_legacy/ai_architecture/documentation/pks_as_Possible_product.md` · `…/what_gemini_suggested.md` (section outline; 37 KB).

**PKS-named documentation: 96 inventoried; read in full or in substance —** `PKS_Phase_III_EAD_1_Ecosystem_Architecture_Discovery.md` *(decisive)* · `PKS_Phase_III_Operational_Validation_Charter.md` *(§6.1/§6.2/§7)* · `PKS_Brainstorming_Documents_Assessment.md` *(decisive)* · `PKS_ADR_PKS_Architecture_Implementation.md` *(ADR-PKS-001 §1–3)* · `PKS_Methodology_Refinement_Candidates.md` *(PMR-1..PMR-10 register; **PMR-10 adoption status verified across the corpus**)* · `PKS_ARB_Review_Discipline.md` · `PKS_Knowledge_Contract_Review_Method.md` · `PKS_Knowledge_Integrity_Model.md` *(layer map)* · `PKS_Phase_II_Methodology_Baseline_v1_2.md` · `PKS_Phase_I_ARB_Rulings.md` · `docs/pks/README.md` · `docs/pks/2026-08-01-documentation-debt-observation.md` · `docs/pks/2026-08-01-documentation-classification-gap-observation.md` — plus the M6/M7/M8, MCA, CDR and certification state as recorded in `.claude/MEMORY.md`.

**Repository facts verified directly:** the register ends at **R-64** (35 rows) · **R-65..R-71** cited as unminted across ≥8 documents · **no collision-check script exists** · `docs/pks/` holds 3 files, **not YAML models** · `docs/implementation/` holds **185 files, 91 `PKS_*`** · **no Phase II.D artifact** · **no research/hypothesis register**.

**⛔ This document decides nothing.** No identifier minted · no directory created · no file moved · no rule proposed for adoption · no ARB act performed or implied. **Every recommendation is a recommendation.**

*Placement: **chosen, not derived** (see §9.4). Authority: **generated** — never authoritative without human review.*
