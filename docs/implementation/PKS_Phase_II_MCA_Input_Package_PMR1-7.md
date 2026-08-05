# PKS Phase II — **MCA Input Package: PMR-1 … PMR-7**

| | |
|---|---|
| **Act** | **ROUTING to MCA-class assessment** (Authority instruction, 2026-07-30: *"Route PMR-7 to MCA now — with PMR-1 through PMR-6"*) |
| **Nature of this document** | **AN INPUT PACKAGE. It constitutes the assessment's input and states what the assessment must decide.** ***It assesses nothing.*** |
| **Assessment label** | **MCA-R1** — *"R" for register*, the **first MCA-class assessment convened against the PMR register.** *Deliberately not numbered "MCA-1": earlier records (KBI-1 §9, CDR §4.3) refer to an "MCA-class assessment" in the Phase II process chain.* |
| **⛔ CORRECTION (2026-07-31)** | **The sentence that stood in the row above was FALSE and is superseded, not deleted:** ~~*"and no artifact of that name exists in the repository"*~~. **`PKS_Phase_II_Method_Certification_Assessment.md` EXISTS and is titled "Method Certification Assessment (MCA)".** *The false claim arose from searching for "Methodology Change Assessment" and for filenames matching `mca|methodology` — neither of which matches `Method_Certification_Assessment`.* **⚠️ And the consequence is worse than the error: in this corpus "MCA" already denoted the Method CERTIFICATION Assessment** (so used in the M6 Critical Review, the Authority Disposition §132, the Consolidation §125, and the Architecture Gate Review Commission DD-8). ***By labelling a methodology-CHANGE assessment "MCA-R1", this act MINTED a second acronym collision — and under the identifier-stability rule it cannot be renamed, only bounded. The collision is permanent.*** **Bounding: "MCA-R1" denotes the register-scoped methodology-change assessment of PMR-1..PMR-7 and nothing else. "MCA" unqualified means the Method Certification Assessment.** |
| **Baseline that remains operative** | **SDM v1 / EOP v1 as frozen at CDR.** *Nothing in this package changes methodology. A PMR is a proposal with an evidence chain* |
| **Status** | **ROUTED. Awaiting MCA-R1 assessment, then CDR-class decision.** |

---

## 1. The boundary this act observes

**Register discipline §3 defines the path: *MCA-class assessment (confirmed / rejected / insufficient evidence / deferred) → CDR-class decision (adopt / adopt in part / decline / defer).*** **This act performs neither.**

| This package MAY state | This package MUST NOT state |
|---|---|
| **How much evidence each candidate carries** — a fact already on the record | **Whether that evidence MEETS the admission filter** — that is the assessment |
| **Which candidates interact** | **How an interaction should be resolved** |
| **What MCA-R1 must decide** | **What MCA-R1 should conclude** |

***The distinction is narrow and deliberate: the evidence count is a matter of record; the sufficiency of the count is a matter of judgment. Routing may carry the first and must not pre-empt the second.***

**The governing filter MCA-R1 will apply** *(strengthened 2026-07-30, stated here for the assessment's convenience, not applied)*:

> **A control becomes GOVERNED only after REPEATED operational evidence that its absence let defects escape.**

---

## 2. The package

| # | Candidate | Raised by | Evidence on record | What MCA-R1 must decide |
|---|---|---|---|---|
| **PMR-1** | **Explicit taxonomy of finding classes** | PA, 2026-07-28 | Routing was consistent across M6→M7 **but without a declared taxonomy**; class re-derived from first principles at every finding | ⚠️ **First: whether the candidate is still live.** *See §3.1 — the gap may have been closed outside this path* |
| **PMR-2** | **Composite architectural claims must not inherit their strongest component's confidence** | PA, 2026-07-28 | M7's evidence matrix decomposed claims into dependency · ownership · pattern-name and measured them **asymmetrically** | Whether to adopt; **and the open precision the register already flags** — PMR-2 forbids inheriting the *strongest*, but does **not** establish that a composite is only as strong as its *weakest*. *The register records that the evidence does not yet support the stronger rule* |
| **PMR-3** | **Distinguish permanent governance capabilities from situational execution controls** | ARB, 2026-07-28 | **Deliberately asymmetric and recorded as such:** ERV-1 found **two Major defects in CCP-1** that no artifact-level review would have caught. **Evidence about simpler contexts: n = 0** | Whether the asymmetry can be resolved at all without data the programme cannot generate. ⚠️ **Interacts with PMR-7 — see §3.2** |
| **PMR-4** | **Elevate the Strategic/Tactical boundary rule to a core methodology principle** | ARB, 2026-07-28 | The rule (*Strategic DDD ends with what the implementation must respect, not how it must realize it*) with the handover inclusion list | Whether elevation to a **core principle** is warranted, versus retention as an applied rule |
| **PMR-5** | **The Assembly Fidelity Rule** | ARB Chief / PKA, 2026-07-28, M8 review, post-disposition of Finding 1 | The M8 assembly case that produced the rule. **Register status: NOT ADOPTED, explicitly routed to the next MCA-class assessment** | Whether to adopt. *The reviewer's original formulation is preserved verbatim in the register and must be assessed as written* |
| **PMR-6** | **A per-finding confidence dimension** (proposed Rule 15) | Chief Architect, 2026-07-28 | **n = 0.** *Already assessed against the proposer's own standard and NOT adopted* | ⚠️ **Nothing — and that is the point. See §3.3: PMR-6 is routed as PRECEDENT, not as a proposal** |
| **PMR-7** | **A change item must specify the OBLIGATIONS an edit incurs, not only the WORDS it writes** — *"the Controlled Change methodology must explicitly define OPERATIONAL DETERMINISM"* | CCP-1 controlled-change-integrity review, 2026-07-30 | **TWO INDEPENDENT DATA: CCP-R1** (planning stage) **and ERV-R1** (verification stage) | Whether two data from **two different lifecycle stages** meet the repeated-evidence filter. **Carries a binding framing constraint — see §3.4** |

---

## 3. Cross-candidate facts MCA-R1 must have, none of them resolved here

### 3.1 ⚠️ PMR-1 may have been discharged OUTSIDE this path — **assessment input, not a determination**

**Fact on the record:** since PMR-1 was raised, the **finding vocabulary was relocated into the Knowledge Contract Review Method** (FW-1's disposition), and the Method now additionally owns the **Observations vs Findings** distinction with its discriminating tests.

**Why this must be surfaced and must not be decided here:** *if the Method now supplies what PMR-1 proposed, the candidate is discharged and adoption would duplicate a control. If it supplies something adjacent but not equivalent, the candidate is live and possibly narrowed.* **Determining which is an assessment.**

***And the governance question it raises is the more interesting one: a framework repair closed a gap that a methodology candidate was queued to close. MCA-R1 should record whether that is a lawful route or an accidental bypass of PMR → MCA → CDR.***

### 3.2 ⚠️ PMR-3 and PMR-7 both concern the CCP/ERV-class controls, from opposite directions

| | Asks |
|---|---|
| **PMR-3** | ***Are these controls permanent capabilities or situational?*** *(a question about SCOPE)* |
| **PMR-7** | ***Do these controls define their own key term precisely enough?*** *(a question about CONTENT)* |

**Recorded for the assessment: adopting PMR-7 strengthens a control whose permanence PMR-3 has not established.** ***Whether that ordering is a problem, an irrelevance, or an argument for assessing them together is an assessment judgment.*** *It is flagged because the register's entries were raised two days apart by different parties and neither cites the other.*

### 3.3 PMR-6 is routed as **PRECEDENT**, not as a proposal

**PMR-6 was assessed against the standard its own proposer set — *"new review dimensions should be added only when they repeatedly distinguish meaningful classes of findings that were previously conflated"* — and NOT adopted at n = 0.**

**It is included because it is the register's only completed assessment, and therefore the only calibration MCA-R1 has for the strengthened filter.** ***A register whose sole rejection is removed from the package would present MCA-R1 with six proposals and no evidence that rejection is a real outcome.***

**MCA-R1 is asked to decide nothing about PMR-6.** *If its evidence has since changed, that is a new datum against the existing entry — appended, never edited (register discipline §4).*

### 3.4 ⚠️ PMR-7 carries a **binding framing constraint** from its originating disposition

> ***The assessment must NOT conclude that CI-1 failed.***

**The recorded basis, carried forward verbatim in substance:** **CI-1 completely protects Level 1 (textual determinism — *"did any editor author text?"*) and was never Level 2's guardian (operational determinism — *"did any editor infer an unstated obligation?"*).** **Satisfying Level 1 does not guarantee Level 2.**

**Also on the record and material to the assessment:** **Level 2 was not nameable in advance.** *An assessment that treats an unnameable distinction as a control failure would be applying hindsight as a standard.*

**And a constraint on the assessment's own scope, from ERV-R1's disposition:** ***acceptance of the evidence is not acceptance of the lesson.*** **ERV-R1 was ACCEPTED as historical annotation; PMR-7 gained no standing from that act beyond the datum itself.**

---

## 4. What this routing act does NOT carry

| | |
|---|---|
| **No verdict** | Not one candidate is characterized as likely-confirmed or likely-rejected |
| **No priority order** | *The register is not ranked. Ranking would express a judgment about relative merit* |
| **No new candidate** | **PMR-1..PMR-7 are routed as written. Nothing was added, narrowed, merged, or reworded** |
| **No archival candidates** | **The reachability rule (§5) is ARCHIVAL, not methodology — it is NOT in this package and must not be assessed by MCA-R1** |
| **No baseline change** | **SDM v1 / EOP v1 remain operative throughout the assessment** |

***The temptation this act specifically declined: PMR-1's possible discharge (§3.1) was the one candidate I could have quietly dropped from the package as "already handled." Dropping it would have been a disposition performed by a routing act.***

---

## 5. Two Authority refinements recorded with this act *(neither is in the MCA package)*

### 5.1 **REACHABILITY, not placement** — the candidate archival rule reformulated

**The Authority's correction, adopted: *"The candidate archival rule should not be about placement. It should be about reachability. Placement is one implementation. Reachability is the governance property."***

**Superseded formulation** *(mine, 2026-07-30)*: ~~*for a currency annotation, placement IS the remedy*~~ — **too narrow: it prescribed a presentation technique.**

> ### **A historical annotation fulfills its purpose only if a reader encountering the lapsed claim will reasonably encounter the qualifying annotation BEFORE BEING MISLED.**

**Mechanisms this deliberately leaves open:** adjacent banner · header note · inline qualifier · cross-reference · document metadata.

***The governance concern is not WHERE the annotation sits; it is whether the reader is protected from an outdated operational interpretation. A principle that governs the OUTCOME outlives the presentation conventions of any one document format.***

**Status: CANDIDATE, n = 2** (AFV-1 satisfied it incidentally; KBI-1 violated it and was repaired). **ARCHIVAL — it never routes to MCA.**

### 5.2 Certification independence — **generalized**

**Superseded formulation** *(mine)*: ~~*the zero strengthens the certification model*~~ — correct but bound to one dimension.

> ### **A certification dimension derives credibility from being OUTSIDE THE UNILATERAL CONTROL of the programme it certifies.**

**This generalizes to any future independent dimension while expressing the same architectural property.** *Applied to the present case: if the programme could raise its own Operational Evidence score, operational evidence would no longer be independent — therefore the programme must leave that dimension unchanged until independent execution provides evidence.*

---

## 6. The assurance distinction this sequence has established

**Authority observation, adopted as the closing record of the routing act:**

> **Earlier reviews primarily increased confidence in the CONTENT of the knowledge base. The recent reviews and dispositions increased confidence in the INTEGRITY OF THE GOVERNANCE SYSTEM ITSELF.**

| Kind of assurance | Asks |
|---|---|
| **Content assurance** | ***Is the knowledge sound?*** |
| **System assurance** | ***Can the programme be trusted to preserve, interpret and evolve that knowledge without compromising its own evidence?*** |

***The second is considerably harder to achieve, and it is demonstrated through concrete governance decisions rather than through framework assertions.*** **This routing act is itself a datum of the second kind: a package that declined to dispose the one candidate it could most easily have dropped.**

---

*Traceability: ROUTING act only — **MCA-R1 input constituted, nothing assessed** (Authority instruction, 2026-07-30) · PMR-1..PMR-7 routed **as written**, none added, narrowed, merged or reworded · label **MCA-R1** scoped to the register, with the unresolved "MCA-class assessment" reference in earlier records **named rather than resolved** · four cross-candidate facts surfaced without resolution: **PMR-1's possible discharge by the Method (FW-1) and whether that route is lawful or an accidental bypass** · **PMR-3 ⟂ PMR-7 (scope vs content of the same controls)** · **PMR-6 routed as the register's only rejection PRECEDENT, calibrating the filter** · **PMR-7's binding framing constraint: the assessment must NOT conclude CI-1 failed; Level 2 was not nameable in advance; acceptance of evidence ≠ acceptance of the lesson** · **evidence counts carried, sufficiency NOT judged** · two Authority refinements recorded and expressly EXCLUDED from the package: **reachability over placement** (archival, candidate n=2) and **a certification dimension derives credibility from being outside the unilateral control of the programme it certifies** · **SDM v1 / EOP v1 remain operative.***
