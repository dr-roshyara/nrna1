# KnowledgeOS — Architecture Baseline

| | |
|---|---|
| **Kind** | ⭐ **SYNTHESIS / STRATEGIC REFERENCE MODEL.** ⛔ ***Not an implementation plan · not a folder structure · not an extraction schedule · not a new capability or context · not a governance amendment · not an ADR update · not a commitment to build.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED.** *Submitted to the Decision Authority. A baseline becomes a baseline by an adoption event, not by being written* |
| **Authority** | ⚠️ **Generated — never authoritative without human review.** ⛔ **On any conflict, the frozen and governed artifacts win** |
| **Commission** | *"Stop writing reports. Begin the KnowledgeOS Architecture Baseline"* + Senior DDD Architect's Addendum, 2026-08-02 |
| **Placement** | ⭐ **DERIVED** → `docs/knowledgeos` (exit 0) |
| **Method** | ⭐ **SYNTHESIS, not discovery.** *Every claim traces to an artifact already produced; nothing new is investigated here* |

> ## ⚠️ **TWO DECLARATIONS BEFORE THE MODEL**
>
> **1 · `P1`–`P5` are DOCUMENT-LOCAL LABELS, not minted identifiers.** ⭐ **CAP-001 was run before writing this** (`php scripts/identifier-check.php KP-1` → **`INCONCLUSIVE`** — *"series 'KP' is not a governed register(ns)"*). A hand check found no `KP-n` in the corpus, **but minting a fourth ungoverned series would worsen G-1, so nothing is minted.** *The tool's verdict changed the action — logged in CAP-001 §9.*
>
> **2 · The Addendum's DDD relationship patterns are recorded as CANDIDATE READINGS, not assignments.** The standing ARB ruling holds: *"the strategic relationship is the conclusion, not the starting point."* ⛔ **This document assigns no pattern.**

---

## 1. Product Definition

| Term | Definition | Status in canon |
|---|---|---|
| ⭐ **KnowledgeOS** | **The reusable engineering platform** — governs how engineering work is planned, approved, executed, verified and evolved. *"Independent of project, programming language, or execution provider."* Its output is **rules and methods**, never product knowledge | ⚠️ **"potential product"**; gate **unopened**. Canon: the platform is a **Supporting Subdomain of PublicDigit** (AIP-14) |
| ⭐ **PKS — Product Knowledge Space** | **One product's knowledge**, one per product. Concepts · ubiquitous language · bounded contexts · decisions · **product bindings**. ⛔ **Never reusable — an instance** | ⚠️ *"generated"* is **n=0**. Every PKS to date was hand-built |
| ⭐ **Business Product** | **The software that delivers business value** — code, tests, deployment. PublicDigit today | ✅ 1,532 code files |
| **Running Software** | the deployed system and the evidence it emits | ✅ 106 verification reports |
| **AI Runtime** | ⛔ **an ADAPTER, not a product.** Replaceable — a different property from reusable | ✅ `.claude/` is *"never 'the architecture'"* |
| ⭐ **Product Binding** | **the product-specific input contract a reusable method requires.** *SD-1 is the archetype* | ⭐ **the concept this baseline contributes** |

> ### ⭐ **The generative relation is `creates`, not `contains`.**
> **`KnowledgeOS → creates → PKS → guides → Product`.** *A compiler is not a program; it produces one. KnowledgeOS does not contain PublicDigit's knowledge — it produces the frame that holds it.*

## 2. Product Architecture

**Five tiers:**

```
 T1  KnowledgeOS            the reusable engineering product
 T2  KnowledgeOS Services   governance · capabilities · validation · runtime
                            integration · operational learning · PKS generation
 T3  Product PKS            one generated PKS per product
 T4  Business Product       PublicDigit · Hospital · ERP …
 T5  Running Software       code · deployments · runtime evidence
```

⭐ **T2 is the tier the recent work uncovered, and it is NOT PKS.** *It is the reusable platform's internal service layer.*

**The six subsystems, each with its honest evidence status** — ⛔ *the Addendum's diagram shows six while its own discipline table names three; this resolves the discrepancy by marking each rather than dropping any:*

| Subsystem | Evidence | Status |
|---|---|---|
| **Governance** | ES-001..006 · EEP · rulings register | ✅ **EVIDENCED — operating** |
| **Capabilities** | CAP-001 realized · CAP-004/006 pre-existing | ✅ **EVIDENCED — operating** |
| **Validation** | ES-003 · 106 verification reports · verdict vocabulary | ✅ **EVIDENCED — operating** |
| **Runtime Integration** | the four-layer model · reserved `registry/` namespace | ⚠️ **EVIDENCED at n=1 runtime.** Untested against a second |
| **Operational Learning** | governed in **six** places | ⛔ **EVIDENCED BUT NON-FUNCTIONING — 0 traversals** |
| ⭐ **PKS Generation** | ⛔ **none** | ⛔ **RESPONSIBILITY ONLY — no implementation.** *The slot is filled by an architect reading documents* |

> ### ⛔ **THE CRITICAL RULE — subsystems are INTERNAL.**
> **They are not repository roots.** *Promoting one to a root would encode an internal design decision as a repository-wide ownership boundary — different architectural concerns.*
>
> ⛔ **This baseline therefore proposes no directory.** *ES-005.2 independently forbids it: "a directory exists only when its first artifact arrives."*

**✅ The three existing roots — `docs/knowledgeos` · `docs/publicdigit` · `docs/pks` — classify by *product ownership*, which is the stable axis.**

## 3. Extraction Boundary

⭐ **The boundary is not a file list. It is a decomposition** — this is the baseline's central correction to §6 of the Product Boundary Discovery, which named files and was refuted at n=1.

| Layer | Belongs to | Test |
|---|---|---|
| ⭐ **METHOD** | **KnowledgeOS** | domain-free **and** binding-free **and** evidence-free |
| ⭐ **BINDING** | **the Product PKS** | the product-specific input contract the method requires |
| ⭐ **EVIDENCE / CASE LAW** | **the Product PKS** | what happened in one project |

**Applied to the artifact that proved the decomposition necessary — `Round47-OP`:**

| Component | Layer | Disposition |
|---|---|---|
| **The nine boundary criteria** *(semantic ownership · transactional consistency · lifecycle independence · invariants · UL divergence · team autonomy · deployment autonomy · integration characteristics · performance constraints)* | ⭐ **METHOD** | **KnowledgeOS** |
| **The candidate rejection protocol** · *"semantic truth does not imply software structure"* | ⭐ **METHOD** | **KnowledgeOS** |
| ⛔ **SD-1** — *"consumes only the Certified Domain Knowledge Release v1.0"* | ⛔ **BINDING** | **the PKS** — replaced by a generic input contract |
| ⛔ **The worked election seams** | ⛔ **EVIDENCE** | **PublicDigit's PKS** |

> ### ⭐⭐ **THIS IS THE REAL EXTRACTION WORK: decomposing artifacts into method / binding / evidence — not moving files.**
> *The question was never "can Round47-OP be extracted?" It is **"which parts are engineering law, and which are PublicDigit bindings?"** — a knowledge-engineering question, not a DDD one.*

## 4. The Five Principles

⚠️ **Each carries its evidence bound. Per ES-006.1, n=1 admits a CANDIDATE, never a rule — so all five are candidates.**

### P1 · Method vs Binding — *the decomposition*

> ⛔⛔ **ANNOTATED 2026-08-02 — P1 WAS RE-DERIVED, NOT DISCOVERED.**
>
> **`Round38C-04_Principle_Form_Classification_Framework`** (34.5 KB, `docs/architecture/design/`) already defines *“the governance framework used to classify existing and future ADR elements as **Principle, Form, or Ambiguous**”*, ARB-authorized under **38C03-CON-01**, governing **Option C — Hybrid Principle/Form Split**.
>
> ⚠️ **Not identical:** Principle/Form classifies by **constitutional entrenchment**; P1 classifies by **portability**. Cognate, not duplicate.
>
> ⭐ **But P1 lacks something that framework has: an explicit `Ambiguous` class.** *The Capability Model had to invent “UNCLASSIFIED” ad hoc for `check_roles.php` — a class the existing framework already carries as first-class.* ⛔ **P1 should be derived from K-07, not proposed beside it.** Record: `KnowledgeOS_Engineering_Knowledge_Landscape.md` §0.


> **Every reusable artifact must explicitly separate (1) the METHOD — domain-free, reusable · (2) the BINDING — the product-specific input contract · (3) the EVIDENCE — product-specific case law.**

| | |
|---|---|
| **Derived from** | `Strategic DDD = Engineering Method + Product Binding` — the SD-1 diagnosis |
| **Evidence** | **n=1** — Round47-OP. ⭐ Corroborated independently by the methodology corpus, where the missing separation appears as *case-law dilution* |
| ⚠️ **Candidate pattern reading** | Shared Kernel + Published Language — ⛔ **not assigned** |

### P2 · The Portability Ladder — *four tiers, not three*

> **Extraction readiness = *f*(domain-free, binding-free, evidence-free).**

| Tier | Condition | State |
|---|---|---|
| **1** | domain-free **∧** binding-free **∧** evidence-free | ⭐ **READY** |
| **2** | domain-free **but** binding-coupled | ⚠️ **BLOCKED** — remove the binding |
| **3** | domain-free **but** case-law-diluted | ⚠️ **BLOCKED** — separate the evidence |
| **4** | **not** domain-free | ⛔ **PRODUCT-SPECIFIC — stays** |

| Artifact | Domain-free | Binding-free | Evidence-free | Tier |
|---|---|---|---|---|
| `DDD_Tactical_Governance_Principles` | ✅ | ✅ | ✅ | ⭐ **1 — READY** |
| `Engineering_Execution_Protocol` | ✅ | ✅ | ✅ | ⭐ **1 — READY** |
| **PMR-9 · PMR-10** | ✅ | ✅ | ✅ | ⭐ **1 — READY** |
| CAP-001 Domain/Application/Shared | ✅ | ✅ | ✅ | ⭐ **1 — READY** |
| ES-005.2 · ES-005.3 · ES-004.2/.3 · ES-003.3 | ✅ | ✅ | ✅ | ⭐ **1 — READY** |
| **Round47-OP nine criteria** | ✅ | ⛔ **SD-1** | ✅ | ⚠️ **2** |
| `Round47-00` SD-2..SD-7 | ✅ | ⚠️ | ⚠️ SD-4 cites election concepts | ⚠️ **2/3** |
| `PKS_Knowledge_Integrity_Model` | ✅ | ✅ | ⛔ **~90% case law** | ⚠️ **3** |
| `PKS_Phase_II_Methodology_Baseline_v1_2` | ✅ | ✅ | ⛔ **~95% case law** | ⚠️ **3** |
| **ES-005.1** | ⛔ names PublicDigit | — | — | ⛔ **4 as written** |
| Registers · 106 reports · `app/` | ⛔ | — | — | ⛔ **4** |

> ### ⭐⭐ **AND THE INSTRUMENT FINDING (this answers OQ-S5):**
> **Neither Tier-2 nor Tier-3 blockage is detectable by searching for domain vocabulary.** *EAD-1's zero-election-terms test found **neither**. **A three-question test is required, not a grep.***

### P3 · Responsibility vs Component — *the genesis gap*

> **A responsibility becomes a component ONLY when: (1) exercised at least once · (2) exercised by someone other than the originator · (3) a repeatable pattern extracted from ≥ 2 instances.**

⭐ **Accepted refinement:** *"PKS Generator doesn't exist"* was imprecise.

| | |
|---|---|
| ⭐ **The RESPONSIBILITY** | ✅ **EXISTS** — an architect reads KnowledgeOS and creates a PKS |
| ⛔ **The COMPONENT** | ⛔ **DOES NOT EXIST** — no automation, no repeatable pattern, **n=0** |
| ⭐ **Today's implementation** | **a Human-in-the-Loop Adapter.** *The port exists; the adapter is a person* |

⭐ **Bootstrapping is three responsibilities:** **bootstrap PKS** *(n=0)* · **bootstrap engineering** *(n=1)* · **bootstrap software** *(n=0)*.

### P4 · The Evidence Harvest — *the loop-closing pattern*

> ⛔ **NOT** `Evidence → Decision → Change`.
> ✅ **IS** `Evidence → HARVEST → Candidate → Promotion → Change`.

| Mechanism | State |
|---|---|
| **ES-006.4** harvest question · **ES-006.1** ladder · **Observation Protocol** | ✅ **READY — use them** |
| Pattern Cards + Evidence Register as its own artifact | ⚠️ **BLOCKED** — trigger: PB-004 retrospective |
| **CAP-001 evidence record** | ⭐ **1 row as of today** *(see §7)* |

⭐ **The process exists. The instances were missing** — and one now exists.

### P5 · The Product Architecture Reference — *synthesis*

> **KnowledgeOS is an engineering platform with INTERNAL subsystems. The extraction boundary runs between the platform and the Product PKS — not between folders.**

⚠️ **Precision note on the Addendum's pattern reading:** *"Separate Ways with a Shared Kernel"* is internally tense in Evans' taxonomy — **Separate Ways means no integration**, which a Shared Kernel contradicts. ⛔ **Recorded as needing ARB resolution; not asserted.** *This is exactly why the ARB ruled that relationships are conclusions.*

## 5. The Lifecycle

| # | Transition | Status | Bound |
|---|---|---|---|
| **L-1** | KnowledgeOS → **discover a product** | ⛔ **HYPOTHESIS** | charter Stage 1 unapproved |
| **L-2** | discovery → **generate a PKS** | ⛔ **HYPOTHESIS** | **n=0** — the adapter is human |
| **L-3** | PKS → **guide engineering** | ⚠️ **PARTIAL** | protocol used; effect unmeasured |
| **L-4** | engineering → **produce software** | ✅ **EVIDENCED** | 1,532 files · CAP-001 |
| **L-5** | software → **collect evidence** | ⚠️ **PARTIAL** | 106 reports |
| **L-6** | evidence → **improve KnowledgeOS** | ⛔ **EMPTY** | *"ZERO-INDEPENDENT… never traversed"* |

⭐ **And the genesis correction P3/§0 forces into the lifecycle:** the platform governs **change**; it does not govern **genesis**. *`Idea → GENESIS → KnowledgeOS → Engineering → Software` — the second box has no governing rule.*

## 6. The Open Questions

| # | Question | Authority | Status |
|---|---|---|---|
| ⭐ **OQ-S1** | **Is SD-1 a PRODUCT BINDING or a PLATFORM RULE?** | ARB | ⭐ **HIGHEST LEVERAGE — P1 answers it conceptually; only the ARB can rule** |
| **OQ-S2** | Should the strategic/tactical method pair sit on the same side of the platform line? | ARB | open — *tactical is platform-side and ADOPTED; strategic is product-side* |
| **OQ-S3** | Is Genesis an EEP gap or a separate lifecycle? | ARB | open |
| **OQ-S4** | Is "PKS Generator" a component or a role? | ARB | ⭐ **ANSWERED IN PART by P3: a responsibility with a human adapter.** *Whether it may become a component awaits n≥2* |
| **OQ-S5** | Do the blocker classes need a detection instrument? | ARB | ⭐ **ANSWERED IN PART by P2: yes — a three-question test, because grep found neither** |
| **OQ-K1** | Does ES-005.3's research clause still hold? | ARB | open — blocks M-5/M-6 |
| **OQ-K2** | Where does cross-product research live? *(`PENDING`)* | ARB | open |
| **OQ-K3** | Has the KnowledgeOS gate opened? | DA | open — trigger: second adopting product |
| **OQ-K4** | Is the Product Discovery Charter approved? | ARB | open — PROPOSED |
| **OQ-K5** | AIP-14 over-evolution exposure? | ARB | ⚠️ **open and accruing** |
| **OQ-K6** | Does "KnowledgeOS" remain the name? *(a placeholder)* | ARB | open |
| **OQ-C1** | Does BRM-1 permit **reference-based** extraction? | ARB | ⭐ **open — gates all of Stage 3** |
| **OQ-C2** | Does the Phase II freeze independently bar M-6? | ARB | open |

## 7. The Evidence Gaps

| # | Gap | Trigger that would close it |
|---|---|---|
| **G-1** | ⛔ **L-6 never traversed** | one harvest that changes a platform rule |
| **G-2** | ⛔ **No PKS generated by a mechanism** *(n=0)* | one PKS produced without a human adapter |
| **G-3** | ⛔ **No second adopting product** | ⭐ **the DA's recorded trigger** |
| **G-4** | ⛔ **No second runtime adapter** | ⭐ the reserved `registry/` trigger |
| **G-5** | ⛔ **Zero market data points** | charter Stage 2 |
| **G-6** | ⭐ **CAP-001 evidence: 1 row, 1 decision changed** *(was 0/0)* | ⭐ **PARTIALLY CLOSED TODAY — see below** |
| **G-7** | ⚠️ **6 ES standards PROPOSED · kernel DRAFT · metamodel CANDIDATE** | ratification · OQ-ENG-004 |
| **G-8** | ⚠️ **No detection instrument** for Tier-2/Tier-3 blockage | P2's three-question test, applied twice |
| **G-9** | ⚠️ **Genesis unruled** | OQ-S3 |
| **G-10** | ⚠️ **n=1 on every principle here** | a second independent occurrence |

> ## ⭐ **G-6 MOVED TODAY — the first genuine operational use of CAP-001**
>
> | | |
> |---|---|
> | **Event** | writing this baseline required labels for five principles |
> | **Verdict** | ⭐ **`INCONCLUSIVE`** — *"series 'KP' is not a governed register(ns); absence of evidence is not PASS"* |
> | ⭐ **Decision changed?** | ⭐ **YES.** *Intent was to mint `KP-1..KP-5`. The verdict sent the check to a hand review, which found no collision **but** established that a fourth ungoverned series would worsen **G-1**.* ⛔ **Nothing was minted; `P1`–`P5` are document-local labels** |
>
> ⭐ **This is the first row in which the capability changed an engineering decision** — the bar set in CAP-001 README §6, unmet since the capability shipped.

## 8. The Roadmap

| Stage | Name | Entry condition *(quoted from canon)* | State |
|---|---|---|---|
| **0** | **Current** | — | ✅ **HERE** — one product · CAP-001 realized · boundary refuted · principles synthesized |
| **1** | **Consolidation** | ⛔ **nothing** — moves no file | ⏳ **OPEN.** ⭐ *This document is Stage 1's substance. Remaining: **OQ-S1 · OQ-K1 · OQ-K2*** |
| **2** | **Validation** | ⛔ **nothing** — one session per run | ⭐ **OPEN — the cheapest stage.** Re-run the bootstrap instrument with Round47-OP added and SD-1 suspended, on a different tiny product |
| **3** | **Extraction** | ⛔ **R-37 lifted** *(C3 + PB-004 + retrospective)* **∧ OQ-K1 ∧ OQ-C1** | ⛔ **BLOCKED** |
| **4** | **Reusable KnowledgeOS** | ⭐ **"a second real adopting product"** — pre-positioned, not executed | ⛔ **BLOCKED** |
| **5** | **`knowledgeos init` · multiple products** | charter gate 4 · market evidence | ⛔ **BLOCKED** |

> ### ⭐ **Stages 1 and 2 are both open and both cost nothing structural. Stage 3 onward is gated by decisions no document can make.**

---

## ⭐ What this baseline is, and what it is not

| ✅ **IS** | ⛔ **IS NOT** |
|---|---|
| a synthesis of accumulated evidence | an implementation plan |
| a stable strategic reference model | a folder structure |
| a guide to what to extract and what to leave | an extraction schedule |
| **falsifiable** — testable by the next bootstrap run | a new capability or bounded context |
| the answer to *"what are we actually building?"* | a governance amendment or ADR update |
| ⚠️ **CANDIDATE, pending an adoption event** | a commitment to build any component |

## ⭐ Closing — the synthesis, in one paragraph

**KnowledgeOS is a reusable engineering platform whose method has been demonstrated portable and whose *boundary* has not.** The work of extraction is not moving files: it is **decomposing each artifact into method, binding and evidence** — the three-layer separation that the SD-1 diagnosis made visible and that no vocabulary search can detect. Six subsystems exist; **three are operating, one is untested at n=1, one is governed but has never run, and one is a responsibility with a human adapter.** The lifecycle has **six transitions, one evidenced and one empty**, and the empty one closes the loop. **All five principles here rest on n=1 and are therefore candidates.**

> ### **The programme does not need more evidence to define its architecture. It needed synthesis — and the synthesis says: one ARB answer (OQ-S1), one repeatable one-session experiment, and nothing extracted until both have spoken.**
>
> ⭐ **And today, for the first time, the platform changed an engineering decision rather than merely describing one.**

---

*Traceability: Architecture Baseline commission + Senior DDD Architect's Addendum, 2026-08-02 · ⭐ **SYNTHESIS of the Architecture Consolidation · Product Boundary Discovery · MVK Bootstrap Validation · Strategic Boundary Consolidation** · five principles recorded as **CANDIDATES at n=1**, per ES-006.1 · **`P1`–`P5` are document-local labels; CAP-001 was run, returned `INCONCLUSIVE`, and the verdict changed the action — nothing minted** · **OQ-S4 and OQ-S5 answered in part by P3 and P2** · the Addendum's *"Separate Ways with a Shared Kernel"* reading recorded as **internally tense and referred to the ARB, not asserted** · six subsystems marked with per-subsystem evidence status, resolving the Addendum's three-vs-six discrepancy without dropping any · ⛔ **no pattern assigned · no folder proposed · no file moved · no context created · no capability built · no governance amended · no code.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes. A baseline becomes a baseline by adoption, not by authorship.**
