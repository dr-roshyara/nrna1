# **EAD-1 — Phase III Ecosystem Architecture Discovery**

| | |
|---|---|
| **Act** | **DISCOVERY** under the Phase III charter. ***It decides nothing, creates no directory, moves no file, renames nothing*** |
| **Label** | **EAD-1.** *PMR-10 (GOVERNED) collision check performed* |
| **Binding constraints honoured** | ES-005.1 · **ES-005.2** *(no speculative directories — none proposed)* · **BRM-1** *(reference-defined; no consolidated copies produced)* · **the Phase II freeze** *(no relocation recommended)* |

> ## ⚠️ **THE SOURCE DOCUMENT CONTAINS TWO FACTUAL CLAIMS THAT THE REPOSITORY CONTRADICTS. Both change the answers.**

---

## 0. Facts established before any question was answered *(PMR-9 governs)*

| # | Claim in the source document | Repository |
|---|---|---|
| **1** | *"PublicDigit — ⏳ **To be built**"* | ⛔ **FALSE. 1,532 code files under `app/`**, including `app/Domain/Election` · `app/Domain/Finance` · `app/Domain/Locale` · `app/Domain/Shared` (133 files under `app/Domain` alone). **The product exists and is substantial** |
| **2** | *"PKS is **product-specific**"* | ⛔ **ONLY PARTLY TRUE.** The governance framework — Integrity Model · Review Method · ARB Discipline — contains **ZERO** occurrences of *vote · voting · ballot · election · voter*. **It is domain-free methodology, and it sits in `docs/`, which ES-005.1 classifies as PRODUCT** |
| **3** | The evolution rule's middle link | ⛔ **EMPIRICALLY EMPTY. Operational Evidence stands at ZERO-INDEPENDENT.** *No evidence has flowed from PublicDigit to PKS. The arrow has never been traversed* |

---

## A. Ecosystem Context

**Three concerns are proposed. The repository already governs three — and they are NOT the same three.**

| ES-005.1 *(ADOPTED today)* | The source document |
|---|---|
| **Product** = `docs/` + `architecture/` + `app/` + `tests/` | **PublicDigit** = `app/` |
| **Engineering Platform** = `engineering/` | **KnowledgeOS** = `engineering/` |
| **Runtime mount** = `.claude/` | ⛔ *no counterpart* |
| — | **PKS** = a subdivision of ES-005.1's **Product** |

***The document does not describe the governed decomposition. It proposes a different one: it splits Product into two and drops Runtime.***

---

## B. Candidate Ecosystem Architecture — conceptual only

**Responsibilities derived from first principles, not from the existing names:**

| Concern | Responsibility, derived | Evidence it can produce |
|---|---|---|
| **The deployed system** *(currently `app/`)* | implement the domain and serve its users | ***the ONLY concern that can produce evidence about the DOMAIN*** |
| **The knowledge corpus** *(currently `docs/implementation/`)* | hold what is known, under governance | evidence about **modelling** |
| **The methodology corpus** *(currently `engineering/` — and, in fact, partly `docs/`)* | supply reusable engineering method | evidence about **method** |

### B.1 ⭐ The corpus called "PKS" is BIFURCATED, and the document's framing assumes it is not

| What PKS actually contains | Character |
|---|---|
| M0–M8 · bounded contexts · the strategic model · ADR-001 · IBC-1 | **product-specific** |
| **Integrity Model · Knowledge Contract Review Method · ARB Review Discipline · the three stabilizers · the classification ladder · the terminal states** | ***domain-free, cross-product methodology*** |

> ### ***Verified: zero voting-domain terms in the framework. So cross-product methodology currently resides inside the Product concern — which is not a violation of anything adopted, because ES-005.1 classifies ROOTS and this is content within one. But it means "PKS is product-specific" is false of a large part of PKS.***

---

## C. Repository Impact Assessment

| Class | Content |
|---|---|
| **ALREADY SUPPORTED by existing governance** | The mapping *deployed system → `app/`* and *methodology → `engineering/`* is **compatible with ES-005.1**. `docs/` holding the knowledge corpus is **already governed as Product**. **No new authority is needed to continue exactly as things are** |
| **OBSERVATIONS** *(no act implied)* | The framework's location inside Product · the document's omission of `architecture/` and of the other 17 unclassified roots · the never-traversed evidence arrow |
| **FUTURE CANDIDATES** *(would need evidence)* | Extracting the domain-free framework from the knowledge corpus into the methodology corpus — ***this is precisely the "PKS → KnowledgeOS extraction" the document's own rule describes, and it is the first real instance of it*** |
| **ARCHITECTURAL DECISIONS** | §D — five, unbundled |

---

## D. Required Future Decisions — **five, each standing independently**

| # | Decision | Why it cannot be bundled |
|---|---|---|
| **D-1** | **Does the ecosystem have THREE concerns or FOUR?** *(the document's three + ES-005.1's Runtime, which it drops)* | *Changing ES-005.1's decomposition is a change to an **ADOPTED rule** and must run its route* |
| **D-2** | **Is the domain-free governance framework part of the knowledge corpus or the methodology corpus?** | ***A relocation candidate touching ~3 governed artifacts. It is an extraction question, and extraction is exactly what requires evidence*** |
| **D-3** | **Should the evolution rule become governing?** *(Business Problem → Product → Evidence → Knowledge → Methodology)* | **See §E.1 — it is contradicted by the corpus's own provenance** |
| **D-4** | **Should *"no arrow from idea to KnowledgeOS"* become a prohibition?** | *A prohibition is a control. Under the strengthened filter it needs **repeated evidence that its absence let defects escape*** |
| **D-5** | **Do "PublicDigit", "PKS" and "KnowledgeOS" become governed terms?** | *Naming is not free — **PMR-10 is now governed, and three new ecosystem-level tokens would enter the corpus*** |

---

## E. Implementation Readiness

> ## **NO repository restructuring is currently authorized. None is recommended.**

| Why | |
|---|---|
| **ES-005.2** | All seven proposed directories are absent. **Creating them as a structure is speculative creation, which ES-005.2 forbids.** *The lawful route is to record them as **reserved namespaces in the README table**, each appearing when its first artifact does* |
| **The Phase II freeze** | Relocating the knowledge corpus would move 88 artifacts, six of them **promoted** — a **change-control act**, never a design act |
| **BRM-1** | The document's ECOSYSTEM.md / EVOLUTION.md would restate governed material. ***Under Model B that creates a second authoritative home*** |

### E.1 ⚠️ The finding that most affects D-3

**The proposed lifecycle is: Business Problem → PublicDigit → Operational Evidence → PKS → KnowledgeOS.**

| Link | Status |
|---|---|
| PublicDigit → **Operational Evidence** | ⛔ **NEVER TRAVERSED. Operational Evidence stands at ZERO-INDEPENDENT** |
| Operational Evidence → PKS | ⛔ **Consequently never traversed** |

> ### ***The entire Phase II corpus — sixteen reviews, five adoptions, three stabilizers, the whole framework — was produced by a path this rule would forbid. It came from ANALYSIS, not from operational evidence.***

**Two readings, and the discovery does not choose:**

| Reading | |
|---|---|
| **The rule is FORWARD-LOOKING** | *It constrains what comes next and passes no judgment on how the baseline was made* |
| **The rule is RETROACTIVE** | *Then it impugns the provenance of the baseline it would govern, and requires an explicit exemption* |

***Adopting D-3 without settling which reading applies would decide the corpus's own provenance by implication — which is the defect this programme has corrected more often than any other.***

---

## F. What this discovery did NOT do

**No directory created · no file moved · no folder renamed · no namespace created physically · no consolidated copy produced · no authority inferred from readiness · no conceptual architecture converted into implementation · ES-005.1/.2/.3 untouched · ES-005.4 still deferred · the freeze intact.**

---

*Traceability: **EAD-1 DISCOVERY** under the Phase III charter (2026-07-31), label collision-checked under PMR-10 · **⚠️ §0: TWO source-document claims contradicted by the repository — "PublicDigit to be built" is FALSE (1,532 code files, `app/Domain/Election` et al.), and "PKS is product-specific" is only partly true (the framework contains ZERO voting-domain terms); a third claim, the evolution rule's middle link, is EMPIRICALLY EMPTY** · **Q1 ANSWERED: this IS an architectural decision, not documentation — the document proposes a DIFFERENT concern decomposition than ES-005.1's adopted triple, splitting Product in two and dropping Runtime** · **Q3: the corpus called PKS is BIFURCATED — domain-free methodology currently resides inside the Product concern** · **Q4: "platform"/"product" OVER-CLAIM — Operational Evidence at zero-independent means no demonstrated multi-consumer reuse; what is demonstrated is a methodology corpus with ONE lineage** · **Q6/§E.1: the proposed lifecycle's middle link has NEVER BEEN TRAVERSED, and the entire Phase II corpus was produced by a path the rule would forbid — two readings recorded, neither chosen, because *adopting it without settling which applies would decide the corpus's own provenance by implication*** · **Q7/Q8 → §D: FIVE independent future decisions, unbundled** · **§E: NO restructuring authorized and none recommended — ES-005.2 forbids the speculative directories, the freeze makes relocation a change-control act, and BRM-1 makes ECOSYSTEM.md/EVOLUTION.md a second authoritative home** · nothing created, moved, renamed or decided.*
