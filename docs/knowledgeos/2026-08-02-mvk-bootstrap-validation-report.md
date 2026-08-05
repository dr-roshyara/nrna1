# MVK Bootstrap Validation — Experiment Report

| | |
|---|---|
| **Kind** | **EXPERIMENT RESULT.** ⛔ ***An experiment, not a commitment. No file moved · no directory created · no code extracted · no architecture proposed.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | KnowledgeOS Platform Validation, 2026-08-02 — *"validate the MVK through a real second product bootstrap"* |
| **Placement** | ⭐ **DERIVED** → `docs/knowledgeos` (**exit 0**) |
| **Principle at stake** | *"No reusable component until it has earned reuse through at least one real product experience."* |
| **Raw record** | `…/scratchpad/mvk-bootstrap-record.md` — the executing session's verbatim output |

---

# ⛔ VERDICT: **FAIL** — the MVK is insufficient to bootstrap a product

> ## ⚠️ **SCOPE CORRECTION (PA, 2026-08-02) — read this before the verdict**
>
> ### **This `FAIL` invalidated the EXTRACTION BOUNDARY. It did NOT invalidate the platform.**
>
> | | |
> |---|---|
> | ✅ **VALIDATED** | **the engineering process** — the EEP transferred with zero translation; the tactical principles rejected five candidates in a foreign domain |
> | ⛔ **INVALIDATED** | **the 15-file set named in §6 of the Product Boundary Discovery** |
>
> ⛔ **And F-3 below is REFUTED** — a strategic-DDD method exists; see the annotation on it.

> ## ⭐ **AR-1 IS REALIZED.** The risk rated HIGH in the Product Boundary Discovery — *"extracting a platform that cannot bootstrap"* — **is a real defect, and it was caught before extraction rather than after.**
>
> ### **This is the experiment working, not failing.**

---

## 1. Method — and the contamination problem I had to solve first

⚠️ **I could not be the fresh session.** Having read AD-1, M6, EAD-1, the registers and the capability catalog, any bootstrap I attempted would have been contaminated by PublicDigit knowledge I cannot unsee. **The commission's *"it needs only a fresh session"* was therefore load-bearing, not incidental.**

| | |
|---|---|
| **Isolation** | an isolated session with no prior conversation context |
| **Boundary** | ⭐ **read access to exactly 15 file paths** — the §6 MVK, **244 KB** — with an instruction to record rather than follow any need for anything else |
| ⭐ **Why reference, not copy** | **BRM-1** forbids materialization. Handing over *paths* rather than a bundle honours it **and** makes reachability itself measurable |
| **Product** | **"StockRoom"** — a hardware shop's inventory. One paragraph of business need, deliberately unrelated to elections |
| ⚠️ **Residual contamination, disclosed** | the isolated session still inherits the project `CLAUDE.md`, which describes PublicDigit and its rules. **It was instructed to treat that as out-of-boundary and to record any reliance.** It caught and refused two such pulls, and disclosed a third |

## 2. Bootstrap Record — what the MVK *did* produce

⭐ **Substantial, and this matters for reading the FAIL correctly:**

| Step | Produced |
|---|---|
| **Capability** | **Stock Accuracy**; rule protected: *you cannot remove stock that is not there, and every movement is counted exactly once* |
| **Strategic model** | **one** bounded context, `Inventory` — ⭐ with `Selling`, `Receiving`, `Catalog` **deliberately rejected** as role separations, not boundaries |
| **Ubiquitous language** | 7 terms with two **homonyms guarded before** the language was written: `Stock` (item vs quantity) · `Count` (act vs number) |
| **Invariants** | INV-1 on-hand ≥ 0 · INV-2 movement idempotent under reference · INV-3 low-stock derived, never stored |
| **Decision** | `DEC-SR-001` satisfying all **eight** Decision-Model-Integrity properties — authored with **no template available** |
| **RED boundary** | on-hand 3, sale of 5 → refuse, leave 3. Aggregate in isolation, no framework |
| **Capability** | `CAP-SR-001 Stock Balance Integrity` on the CAP-001 template, including *fail closed* and *prevents-never-repairs* |
| ⭐ **Rejections** | **five** candidates killed by MVK rules — 2 value objects, 1 event, 1 command, 1 repository method |

⭐ **The most telling single result:** the tactical principles **demoted "two staff may adjust the same item at once" from invariant to mechanism** — the exact modelling error that business need invites. *That is a governance kit doing discriminating work in a domain it was never written for.*

## 3. Out-of-Boundary Register — ⛔ **14 reaches, 3 of them contamination**

**The register is not empty.** The load-bearing entries:

| # | Needed | Reachable? | Consequence |
|---|---|---|---|
| **1** | ⭐ **A strategic-DDD method** — context discovery, mapping, UL discovery | ⛔ **NO — explicitly EXCLUDED** by the only DDD document's own scope line | supplied entirely from outside the MVK |
| **3** | ⭐ **A solo/pre-organization execution gate** | ⛔ **NO** — EEP §2 forbids Engineer = Reviewer, §3 forbids skipping, §5 forbids implementing before approval; **EP-01-Light is registered as the source project's property** | **could not lawfully begin** |
| **4** | ⭐ **A genesis path to a first architecture decision** | ⛔ **NO** — Reference Architecture §7 requires operational evidence and states *"never idea → ADR → implementation"*; ES-002.1 presupposes *"the architecture has demonstrated stability"* | had to invent an ordering |
| **2** | A decision-record form | ⛔ ~~No~~ — ⭐ **REFUTED 2026-08-02: `docs/architecture/discovery/Round17_ARB_Decision_Record_Template.md` EXISTS** *(an unfilled template: Deliberation Context → Summary → Decision + Rationale)*. ⚠️ **It was not in the MVK, and it was not indexed anywhere** | composed from review *criteria* |
| **7** | ⭐ **Which closed verdict vocabulary governs** | ⛔ **NO — TWO closed sets collide.** ES-003.1 `PASS · PASS AFTER CORRECTION · WARN · FAIL` vs CAP-001 §5 `PASS · FAIL · WARN · INCONCLUSIVE`, which *forbids* emitting PASS AFTER CORRECTION. **Nothing scopes them** | picked one |
| **8** | Folder classification for a different repository | ⛔ **Not as written — ES-005.1 names PublicDigit and hard-codes `.claude/`** | ignored the rule, kept the principle |
| **9** | ⭐ **How a second project OBTAINS the platform** | ⛔ **NO — and ES-005.4 is DEFERRED, therefore SILENT**, and *"a deferred rule cannot be cited as authority."* The one rule that would answer it is unavailable | referenced, copied nothing |
| **11** | 🔴 `doc-placement.php` + placement YAML | ⛔ **CONTAMINATION — RECOGNISED AND REFUSED** | *"a naive run would have made step 4 look solved"* |
| **12** | 🔴 The 14-phase Operating Loop, EP-01/02/03 | ⛔ **CONTAMINATION — RECOGNISED AND REFUSED.** ⚠️ **The EEP has no business-capability, strategic-DDD, canonical-discovery, stewardship or impact-classification phase** | *"the richness I would naturally have brought is contamination, and its absence from the MVK is finding #1"* |
| **13** | 🔴 ⭐⭐ **The bootstrap SEQUENCE itself** | ⛔ **Not in the MVK — the experiment brief supplied it** | ⭐⭐ ***"Left to the MVK alone I would have begun at 'Idea → Implementation Plan' and never been told to model a context first. Disclosed because it materially flatters the result"*** |

> ## ⭐⭐ **REACH #13 IS THE MOST IMPORTANT LINE IN THE EXPERIMENT.**
> **The record is more favourable to the MVK than reality warrants** — because the *scaffolding of the attempt* came from the brief, not from the platform. **The true state is worse than FAIL suggests, not better.**

## 4. Which MVK elements actually transferred

| Element | Result |
|---|---|
| ⭐ **`Engineering_Execution_Protocol`** | **USED — strongest element.** *"Genuinely project-, language- and provider-independent — as advertised."* Zero translation. **Also the source of the blocking finding** |
| ⭐ **`DDD_Tactical_Governance_Principles`** | **USED — most productive.** All seven fired; **rejected five candidates**. ⚠️ Excludes strategic DDD |
| ⭐ **`CAP-001 README`** | **USED — the template claim held.** Disciplines transferred, not code. Its `INCONCLUSIVE` verdict was *"exactly correct for a greenfield repository"* |
| **`ES-003` · `ES-004` · `ES-006` · `STANDARDS_INDEX`** | **USED.** ES-004.2's plan-naming rule transferred **verbatim** |
| ⚠️ **`ES-005`** | **PARTLY UNUSABLE.** .2/.3 transferred with **zero modification**; ⛔ **.1 names PublicDigit** and was ignored; **.4 is silent** |
| **`ES-001` · `ES-002`** | **USED partially** — all registered sources point outside; .1/.2 presuppose a stable architecture |
| ⚠️ **`PKS_Knowledge_Integrity_Model`** | ⛔ **~10% used, ~90% source case law** |
| ⚠️ **`PKS_Phase_II_Methodology_Baseline_v1_2`** | ⛔ **~95% source issuance record.** Exactly **two** portable rules: **PMR-9** and **PMR-10** |
| **`PKS_Knowledge_Contract_Review_Method` · `PKS_ARB_Review_Discipline`** | **USED** — criteria proved *more* portable than a template would have been |

> ## ⛔ **THIS REFUTES PART OF §6 OF THE PRODUCT BOUNDARY DISCOVERY.**
> **The three methodology documents are 73% of the MVK by volume and mostly non-portable case law.** *I listed them as MVK elements on the strength of EAD-1's zero-election-terms finding. **Domain-free is not the same as portable** — a document can contain no election vocabulary and still be almost entirely one project's case history.*

**Per-scope verdicts recorded by the executing session:**

| Scope | Verdict |
|---|---|
| Tactical DDD design | ✅ **PASS** |
| Knowledge integrity of its own artifacts | ✅ **PASS** |
| Documentation placement | ⚠️ **WARN** |
| Identifier integrity | ⚠️ **INCONCLUSIVE** — no governed register exists |
| **Strategic DDD** | ⚠️ **INCONCLUSIVE** — *no method available* |
| ⛔ **Execution governance (EEP conformance)** | ⛔ **FAIL** — mandatory stages structurally unsatisfiable |

## 5. The three load-bearing failures

| # | Failure | Why it is structural, not cosmetic |
|---|---|---|
| **F-1** | ⛔ **The EEP cannot be satisfied at bootstrap** | §2 forbids one party holding Engineer *and* Independent Reviewer · §3 forbids skipping · §5 forbids implementing before approval · the only light gate is the source project's property. ⭐ **The MVK forbids proceeding and supplies no lawful way to proceed** |
| **F-2** | ⛔⛔ **There is no genesis path** | The sole sanctioned route to an architecture requires operational evidence that does not exist on day one, and explicitly forbids `idea → ADR → implementation` |
| **F-3** | ⛔⛔ ~~**There is no strategic-DDD method**~~ — ⭐ **REFUTED 2026-08-02.** **RESTATED: the method EXISTS but is product-coupled** | ⚠️ **`Round47-00` Strategic DDD Constitution (ADOPTED) and `Round47-OP` Operating Protocol (BINDING) contain nine product-neutral boundary criteria and a rejection protocol.** ⛔ **The blocker is not absence — it is (a) a product path and (b) **SD-1**, which admits *only* PublicDigit's Certified Domain Knowledge Release.** *The bootstrap session could not see them because they were not in the MVK.* Record: `2026-08-02-knowledgeos-strategic-boundary-consolidation.md` §0 |

> # ⭐⭐ **THE META-FINDING — the single most valuable output of this experiment**
>
> ### ***"A platform that governs CHANGE does not automatically govern GENESIS — and the gap is invisible from inside a mature project, because genesis happened before the governance existed."***
>
> **No amount of further analysis inside PublicDigit could have surfaced this.** *It required attempting a genesis.*

**Honest bounds, as the executing session itself recorded them:**

| | |
|---|---|
| **n = 1** | one product · one engineer · one attempt · a deliberately small CRUD-shaped domain |
| ⚠️ **F-1 is team-size dependent** | for an adopting *organization* with a named architect it largely dissolves — **that scope's verdict would be WARN, not FAIL** |
| ⭐ **F-2 and F-3 hold at any team size** | these are the firm findings |
| ⚠️ **The result flatters the MVK** | reach #13 — the bootstrap sequence came from the brief |

## 6. MVK Revision — 7 additions, ⭐ **every one to an EXISTING home**

⛔ **No ES-007 is proposed** — per ES-001.1 and the Standards Index stopping rule (*"which existing ES owns this?"*).

| # | Addition | Home | Reusability basis |
|---|---|---|---|
| **A** | ⭐ **A genesis clause** — at day zero the stakeholder's stated need is admissible evidence; the first decision is *Provisional/Proposed*; the evidence loop **begins from it rather than gating it** | Ref. Arch §7 + ES-002.1 | every adopting project has a day one; **absence is provable from the MVK's own text** |
| **B** | ⭐⭐ ~~A Strategic DDD methodology module~~ — ⭐ **RESTATED 2026-08-02: the module EXISTS. The act needed is EXTRACTION + resolving SD-1's certified-release coupling, not authorship** | `Round47-00` · `Round47-OP` → platform-side | ⛔ **Blocked on OQ-S1** — *is SD-1 a product binding or a platform rule?* **One ARB answer may close this gap without writing anything** |
| **C** | ⭐ **An EEP role-scaling clause** — what *depth zero* review and approval look like | EEP §2/§3 | a property of **team size**, not of any product. EEP already says *"depth scales… the sequence does not"* |
| **D** | A **decision-record contract** (required field set, not a template) | ES-004 | the eight properties exist — but as *review criteria*, not an authoring obligation |
| **E** | ⭐ **One verdict-vocabulary scoping rule** | ES-003 | **two sets both declared closed, differing in membership, nothing scoping them, is a platform defect** independent of any project |
| **F** | ⭐⭐ **A platform-acquisition rule** — reference / vendored / submodule, and where platform docs sit in an adopting repository | ES-005 | ⭐⭐ **discharges the Reference Architecture's own DEFERRED *"Platform ≙ Adoption split"*, whose recorded trigger is *"a second adopting project"* — which this experiment IS** |
| **G** | **ES-005.1 restated product-neutrally** — three *concerns*, with the folder mapping recorded as the source repository's **binding** | ES-005 | the platform already does rule-vs-binding everywhere else: *"projects bind it; they do not fork it"* |

⭐ **Addition F is not a suggestion — it is a deferral coming due.** *The platform recorded the trigger; the trigger has now fired.*

## 7. Product Architecture Recommendation

> ## ⛔ **DOES NOT ARISE — the commission conditioned it on PASS.**
>
> **Kernel · Capability SDK · Bootstrap Engine · PKS Generator · Validation Engine · Extension Model remain HYPOTHESES.** *No component is evidenced by a FAIL.*
>
> ⭐ **And the failure direction is informative: the gaps are in GOVERNANCE (genesis, roles, acquisition) and METHOD (strategic DDD) — not in tooling.** ***A Bootstrap Engine would have automated a process that does not yet exist.***

## 8. What this changes

| Claim | Before | After |
|---|---|---|
| *"independent of project, programming language, or execution provider"* | asserted in a DRAFT | ⚠️ **partly true and now measured** — the EEP and tactical principles transferred cleanly; **genesis, roles and acquisition did not** |
| MVK sufficiency | untested, n=0 | ⛔ **REFUTED at n=1** |
| **§6 of the Product Boundary Discovery** | 15 named elements | ⛔ **REFUTED IN PART** — the 3 methodology documents are 73% by volume and mostly non-portable case law |
| **AR-1** | HIGH risk | ⭐ **REALIZED — and caught before extraction** |
| L-2 *"generate a PKS"* | HYPOTHESIS | ⚠️ **still hypothesis** — a model was hand-built by an engineer reading rules, **nothing was generated** |

## 9. Recommended next step

> ### ⭐ **Close F-2 and F-3 first. They are the two findings that hold at any team size, and both are governance/method work — not extraction, not tooling.**

| # | Step | Why |
|---|---|---|
| **1** | ⚠️ **Put additions A, B, C, F to the Authority as candidates** — ⛔ **not as amendments.** n=1 admits a *candidate*, per ES-006.1 | the platform's own ladder |
| **2** | ⭐ **Re-run this experiment after any addition lands** — same 15+N files, a *different* tiny product. **The experiment is now a repeatable instrument** | ⭐ **it is falsifiable and costs one session** |
| **3** | ⛔ **Do not extract. Do not build a Bootstrap Engine.** F-1..F-3 are unresolved | §7 |
| **4** | ⭐ **Run CAP-001 at the next real minting** — still **0 executions, 0 decisions changed** | the only gap closable without an Authority decision |

---

## ⭐ Closing

**The commission asked for proof that KnowledgeOS can bootstrap a real product. The proof came back negative, and it is the most useful result this track has produced.**

| | |
|---|---|
| ✅ **What is genuinely reusable** | the **EEP**, the **tactical DDD principles**, the **CAP-001 template**, **ES-005.3's litmus**, and — *surprisingly* — ⭐ **the restraint rules.** *"A governance kit that argues against its own expansion is the part most likely to survive contact with a new project — and it did"* |
| ⛔ **What is missing** | **genesis · role scaling · acquisition · strategic method · one verdict vocabulary** |
| ⭐ **What the programme now has** | ⭐ **a falsifiable, repeatable, one-session instrument for testing reusability** — and a FAIL recorded *before* anything was extracted |

> ### **The platform's reusability claim is no longer an assertion. It is a measurement — and the measurement says *not yet*.**

---

*Traceability: KnowledgeOS Platform Validation commission 2026-08-02 · executed in an isolated session over exactly 15 MVK paths (244 KB), reference-based per **BRM-1** · **verdict FAIL** with 6 per-scope verdicts from the closed vocabulary · **14 out-of-boundary reaches, 3 contamination (2 refused, 1 disclosed as flattering the result)** · **AR-1 realized and caught pre-extraction** · **§6 of the Product Boundary Discovery refuted in part — domain-free ≠ portable** · 7 additions proposed **as candidates to existing homes, no ES-007** · **addition F discharges a deferral whose recorded trigger has now fired** · §7 does not arise (conditioned on PASS) · ⛔ **no file moved · no directory created · no code extracted · no architecture proposed · nothing promoted.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
