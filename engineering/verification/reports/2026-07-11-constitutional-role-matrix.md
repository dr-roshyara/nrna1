# Constitutional Role Matrix — Final Review Before C3

**Commission:** ARB, 2026-07-11 · **Class:** constitutional verification (roles reviewed, not documents; nothing modified, nothing proposed).
**The four roles:** Specification (defines) · Execution (performs) · Verification (measures) · Decision Authority (decides).
**Evidence base:** this engineering cycle's documented events (rulings register, session logs, qualification records, verification reports).

## Role: Specification

| Aspect | Determination |
|---|---|
| Exclusive decisions | what concepts, rules, and decisions **exist and mean** — the content of standards, the decision catalog, reference architecture, protocols |
| Owned artifacts | ES-001..006 · STANDARDS_INDEX · Engineering Decision Model · Reference Architecture · EEP · charters and decision papers (until HISTORICAL) |
| Forbidden actions | **executing** ("the architecture never executes — the Engineer consults it") · **measuring its own compliance** (Verification's) · **deciding its own adoption or promotion** ("evidence promotes architecture; architecture never promotes itself") |
| Boundary crossings observed this cycle | three attempted, all caught: DetermineArtifactLifecycle initially entered as settled (specification self-adopting) · R-38 initially drafted as a new freeze rule (specification restating governance as new governance) · the automation principle nearly entered the constitution without evidence |
| Corrections applied | ✅ all three, same-day: CANDIDATE marking (ARB wording) · Option B consolidation correction · promotion-path holding |
| Remaining ambiguities | none — pilot-gated questions are explicitly parked decisions with named owners, not ambiguities |

## Role: Execution

| Aspect | Determination |
|---|---|
| Exclusive decisions | **how** to implement within an approved plan (below-plan granularity) · resolving the eight Engineering Decisions in daily work (consulting authorities, never creating them) |
| Owned artifacts | work plans (runtime) · code and tests · session logs (operational record) · EP-02 report authorship (acceptance is the Decision Authority's) |
| Forbidden actions | silently changing direction from an approved plan (EP-01) · creating rules or standards (ladder only) · self-certifying · fixing findings in-run (ES-003.1) |
| Boundary crossings observed this cycle | three documented: the session-log overwrite (append-only violation, 2026-07-11) · the TDD lapse (production before RED, PB-004 step 2) · R-36 citing an ungoverned runtime-named artifact (execution artifact entering governance without a promotion event — historical) |
| Corrections applied | ✅ first two fully (git recovery + violation record; stash→RED→GREEN). Third: **recorded, deliberately not repaired** (history stands, ES-004.2); forward defense in place (DetermineArtifactLifecycle candidate). An accepted residual, not an open crossing |
| Remaining ambiguities | none blocking |

## Role: Verification

| Aspect | Determination |
|---|---|
| Exclusive decisions | **what the evidence shows** — verdicts as measurements (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT), finding identification and mandated classification |
| Owned artifacts | OQ records and protocols (OQ-ENG-001/002/003) · verification reports (readiness · delta · pattern validation · neutrality review · this matrix) · qualification plans |
| Forbidden actions | **fixing findings** (report-never-fix) · **recommending governance actions** (authorization, repairs, deferrals, qualification strategy) · interpreting beyond measurement (R-26) · **complying with commissions that request decisions** — the refined principle: the instrument records *"outside verification authority — referred to the Decision Authority"* rather than obeying |
| Boundary crossings observed this cycle | the documented case: delta report §7 ("Authorize C3" + NF-1 strategy — 2 MAJOR) and the baseline readiness report's commissioned recommendation — both commission-induced, both the instrument's own responsibility under the refined principle |
| Corrections applied | ✅ identified and annotated by the Neutrality Review (the verifier audited itself by its own rule); disposition of the report wordings is **an open Decision Authority choice, correctly routed** — the crossing is documented and submitted, not silently standing |
| Remaining ambiguities | none about *who decides* — the one open item (apply suggested wordings vs. accept-with-annotation) has a named owner and sits in the right queue |

## Role: Decision Authority

| Aspect | Determination |
|---|---|
| Exclusive decisions | adoption · ratification · promotion/retirement · plan approval (EP-01) · qualification acceptance · freezes · next-step selection |
| Owned artifacts | rulings register · ADR acceptances · ratification signatures · approval/acceptance records |
| Forbidden actions | implementing (Execution's) · measuring its own compliance (Verification's) · **being automated** ("promotion is never automated") · inferring its own decisions from praise or suggestion (R-34: explicit adoption only) |
| Boundary crossings observed this cycle | one: R-38 — an assessment recorded as a new governance rule (the Decision Authority generating governance that restated governance) |
| Corrections applied | ✅ same-day, by the Decision Authority against itself (Option B: "this ruling introduces NO new governance"), with the standing preference recorded: interpret existing rulings rather than add consolidating ones |
| Remaining ambiguities | none |

## Self-consistency test

| Question | Answer |
|---|---|
| Is every role's boundary clearly defined? | **Yes** — each role has exclusive decisions, owned artifacts, and forbidden actions grounded in existing rules (no new rule was needed to complete any cell) |
| Are there any overlapping responsibilities? | **No** — shared artifacts are sequential interfaces, not overlaps (EP-02: Execution authors → Decision Authority accepts; qualification: Verification measures → Decision Authority disposes) |
| Are there any ambiguous responsibilities? | **No** — the open items (report-wording disposition; NF-1 option; ratification) are pending *decisions* with unambiguous owners, which is the opposite of ambiguity |
| Has any artifact crossed a boundary without correction? | **No** — every observed crossing (7 total across all four roles) was either corrected same-day or explicitly recorded and routed to its deciding role; none stands silent |

> **"The constitutional separation is internally consistent."**

Notable property, measured not designed: **every role produced at least one boundary crossing this cycle, and every crossing was caught by the platform's own mechanisms** — Specification by the ARB's parsimony question, Execution by git + the discipline record, Verification by the neutrality review, the Decision Authority by its own Option B correction. A separation that catches violations in all four directions, including against its own apex, is consistent in the only sense that matters operationally.

---
*Traceability: ARB constitutional-role commission 2026-07-11 · evidence: rulings register (R-34/R-36/R-38) · session logs 2026-07-11 · neutrality review · qualification records. Constraint check: nothing modified, nothing proposed, no new concepts. STOP — submitted to the Decision Authority.*
