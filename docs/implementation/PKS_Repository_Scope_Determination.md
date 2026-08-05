# PKS — **Repository-Scope Determination**

| | |
|---|---|
| **Act** | Executes `PKS_Repository_Scope_Determination_Commission.md` under the Authority's four-stage sequence (2026-07-31) |
| **Governing first question** *(Authority's refinement, adopted in place of the narrower form)* | ***What is the currently binding source governing repository scope, if any?*** — **not** *"does ES-005.1 govern?"*, which would have presumed the candidate |
| **OUTCOME** | ## **B — A FRESH SCOPE DETERMINATION IS NOT THE REQUIRED ACT** |
| **Status** | **DETERMINED. Nothing amended, nothing adopted, no cleanup authorized, no file moved.** |

---

## 1. Stage 1 — the currently binding source, identified without presuming a candidate

**Searched: ES-005 · STANDARDS_INDEX · EM-001 · the platform rulings log · the OQ record · `.claude/CLAUDE.md` · README/MEMORY. Two sources bear on repository scope, and they are not the same source:**

| Source | Standing | What it governs |
|---|---|---|
| **R-37 — *"Architecture Freeze 2.0 (structural)"*, ARB, 2026-07-10**, recorded in `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` | ✅ **BINDING — an adopted ARB ruling in the rulings log** | ***"no new top-level folders · no new domain objects"*** — issued **at EM-001 closure** (*"the right place to stop"*), complementing R-27's governance freeze |
| **ES-005.1 — *The Three-Concern Separation***, in `engineering/governance/ES-005-Repository.md` | ⚠️ **Recorded in a PROPOSED document, with EXISTING executing instruments** | `docs/` + **`architecture/`** + `app/` + `tests/` = **Product** · `engineering/` = **Engineering** |

---

## 2. Stage 2 — the THREE LAYERS, separated as the Authority directed

***"A proposed document may faithfully record an already binding ruling; an adopted document may contain non-binding guidance; a ruling may exist but later be superseded."*** **Kept apart:**

| Layer | Question | Finding |
|---|---|---|
| **DOCUMENT** | Is ES-005 itself adopted? | ⛔ **NO. *"Status: PROPOSED — awaiting ARB review."*** *STANDARDS_INDEX is likewise PROPOSED* |
| **RULE** | Was the cited act actually ruled by the ARB? | ✅ **An ARB act of 2026-07-10 EXISTS and concerns repository structure — R-37.** *ES-005.1's citation to *"EM-001, ARB 2026-07-10"* points to a real act on that date* |
| **BINDING EFFECT** | Does it currently govern? | ⚠️ **See §2.2 — the earlier answer said ES-005.1 is *"OPERATIONALLY ENFORCED"*, which COMPRESSED FOUR DISTINCT EVIDENCE STRENGTHS and overstated at least two** |

### 2.2 ⛔ EVIDENCE STRENGTH, placed precisely *(Authority refinement, 2026-07-31 — supersedes "operationally enforced")*

**The Authority's four categories, applied to each source rather than compressed into one phrase:**

| Strength | Meaning | **R-37** | **ES-005.1 (three-concern)** |
|---|---|---|---|
| **RECORDED** | *somebody wrote it* | ✅ in the platform rulings log | ✅ in ES-005 |
| **REFERENCED** | *later artifacts rely on it* | ✅ cited as complementing R-27 | ✅ STANDARDS_INDEX §54 assigns it instruments; **OQ-ENG-002 §50 cites the *"three-concern model"* in a PASS row** |
| **IMPLEMENTED** | *execution follows it* | *not separately tested here* | ⚠️ **PARTIALLY.** *It was used as a criterion in an EXECUTED qualification (OQ-ENG-002, 2026-07-11). But that row checks README's entry-point clarity — **not** the folder classification itself. And **only two OQ-ENG executions exist (2026-07-10, 2026-07-11); none since*** |
| **BINDING** | *Authority adopted it* | ✅ **an adopted ARB ruling** | ⛔ **NOT DEMONSTRATED** — ES-005 is PROPOSED and the three-concern text's adoption is not established (§2.1) |

> ### ***My earlier phrase asserted implementation and implied binding force from a single index row. Corrected: ES-005.1 is RECORDED and REFERENCED with certainty, PARTIALLY IMPLEMENTED on one dated execution, and NOT DEMONSTRATED as binding.***

**⚠️ One further precision: STANDARDS_INDEX says the instruments are *"executed each OQ"*, which is true — and there has been no OQ since 2026-07-11.** ***EXECUTION EVIDENCE SHOWS AT LEAST ONE IMPLEMENTED USE of the rule, but the current evidence DOES NOT ESTABLISH CONTINUOUS OR GENERAL IMPLEMENTATION.*** *(Authority formulation, tighter than the reviewer's "implementation evidence exists", which left the extent unbounded.)* **⚠️ Superseded, retained:** ~~*"the enforcement is real but DORMANT"*~~ — ***the word "enforcement" smuggles back the BINDING implication the four-strength ladder had just removed. The correction carried a trace of the error it was correcting.*** *And "executed each OQ" reads as ongoing when the last execution is three weeks old.*

### 2.1 ⚠️ What this determination does NOT establish — stated because the temptation is to claim more

> ### **It is NOT established that the ARB adopted the three-concern separation's TEXT as a rule.**

**R-37's recorded content is a *structural freeze* — *"no new top-level folders · no new domain objects."* ES-005.1's three-concern separation is a *classification* of existing folders. They are adjacent, dated the same day, and tied to the same migration — but *"issued at EM-001 closure"* is not the same as *"adopted the three-concern text."***

***What binds is therefore: R-37's freeze (adopted), plus a classification that is recorded in an unadopted document and enforced by existing instruments. That is a real and unusual standing, and naming it precisely is the determination's main work.***

---

## 3. Stages 3 and 4 — is a fresh determination needed? **NO**

| Stage | Result |
|---|---|
| **3. Only if no governing authority exists, determine repository scope** | ⛔ **DOES NOT APPLY.** *Two sources bear on it; neither is absent* |
| **4. If one exists, determine whether change-control or adoption action is needed instead** | ✅ **THIS IS THE OPERATIVE FINDING: the required act is ES-005's ADOPTION, not a fresh scope determination** |

> ### **A fresh determination would either DUPLICATE R-37 and ES-005.1, or AMEND them silently. Neither is a neutral act.**

---

## 4. The original claim, resolved on the evidence

**The AFV-F4 commission asserted `./architecture/` is *"legacy brainstorming and is EXCLUDED from the PKS domain"*, with a cleanup action to archive it.**

| Claim | Finding |
|---|---|
| *`architecture/` is excluded from PKS* | ⛔ **CONTRADICTED. ES-005.1 places it INSIDE the Product concern, and that classification is enforced each OQ** |
| *It is ungoverned* | ⛔ **CONTRADICTED. `.claude/CLAUDE.md` assigns it the role *"Think"*; ES-005.1 classifies it; it holds 465 markdown files** |
| *The repository = four roots* | ⛔ **INCOMPLETE. At least twelve top-level roots exist** |
| *Archive the tree* | ⛔ **NOT AUTHORIZED, and it would contradict ES-005.1's classification.** ⚠️ **On R-37: the freeze forbids *new* top-level folders; whether it forbids REMOVING one is NOT established by its text, and this determination does not decide it** |

---

## 5. What IS determined

| | |
|---|---|
| **The binding sources are identified** | **R-37 (adopted ruling) + ES-005.1 (recorded in a proposed document, enforced by existing instruments)** |
| **No fresh scope determination is required** | *The required act is **ES-005's adoption**, on its own path* |
| **`architecture/` is not excluded from the governed repository** | *on ES-005.1's classification, which no act has superseded* |
| **No cleanup is authorized** | *and none may proceed on the AFV-F4 framing, which is contradicted* |

## 6. What is NOT determined

| | |
|---|---|
| **ES-005's adoption** | ⛔ **NOT decided. Its own act, on its own path** |
| **Whether the ARB adopted the three-concern TEXT** | ⛔ **NOT established (§2.1)** |
| **Whether R-37 forbids REMOVING a top-level folder** | ⛔ **NOT decided — its text addresses *new* folders** |
| **The status of the seven-plus roots ES-005.1 does not classify** | ⛔ **NOT decided.** *`bin` · `bootstrap` · `business_analysis` · `claude` · `config` · `database` · `developer_guide` · `developer_issues` are unclassified by ES-005.1 as read, and this act classifies none of them* |
| **AFV-F4's rationale** | ⛔ **UNTOUCHED.** *Its ACCEPT rests on "the contingency the model already states" and is NOT re-grounded here — that would be a rationale change to a discharged act* |
| **OQ-PKS-7 · CBC-3 status · promotion state · any promoted artifact** | ⛔ **All unchanged** |
| **Any methodology change** | ⛔ **None. No PMR raised** |

### 6.1 The asymmetry, verified rather than asserted

**The commission required this be tested, not assumed. Tested:** **determining scope broadly would classify eight roots no governed source classifies — reaching matters ES-005's adoption should settle.** *Concluding at Outcome B avoids that.* ***So the narrow act here is to determine the SOURCE and stop; the broad act would have been to determine the SCOPE.***

---

## 7. Next act — one, and it is not this one

**ES-005's adoption**, through whatever path governs standards adoption. **Until then the operative position is: R-37 binds; ES-005.1's classification is enforced by instruments though its document is unadopted; and the eight unclassified roots remain unclassified.**

**No further planning or verification layer is required, and none should be created.**

---

*Traceability: **Repository-Scope Determination EXECUTED** (2026-07-31) under the Authority's four-stage sequence and three-layer separation · **governing question taken in the Authority's refined form — *what is the currently binding source, if any?* — so no candidate was presumed** · **STAGE 1: two sources bear on repository scope, and they differ — R-37 *"Architecture Freeze 2.0 (structural)"* (ARB 2026-07-10, in the platform rulings log, ADOPTED: no new top-level folders) and ES-005.1's three-concern separation (in a PROPOSED document, with instruments recorded as "Existing (executed each OQ)")** · **STAGE 2: DOCUMENT not adopted · RULE — an ARB act of that date exists · BINDING EFFECT partly, by two different mechanisms** · **⚠️ §2.1 NOT ESTABLISHED: that the ARB adopted the three-concern TEXT as a rule — R-37's content is a structural freeze, ES-005.1's is a classification; "issued at EM-001 closure" is not "adopted the three-concern text", and naming that standing precisely is this determination's main work** · **OUTCOME B: no fresh determination is required; the operative act is ES-005's ADOPTION — *a fresh determination would either DUPLICATE or SILENTLY AMEND R-37 and ES-005.1, and neither is neutral*** · **the AFV-F4 claim is CONTRADICTED on three counts and its cleanup is NOT authorized; whether R-37 forbids REMOVING a folder is expressly left undecided** · **NOT determined: ES-005's adoption · the three-concern text's adoption · eight unclassified top-level roots · AFV-F4's rationale · OQ-PKS-7 · CBC-3 · promotion state · any methodology change** · **asymmetry VERIFIED not asserted: determining the SOURCE is the narrow act, determining the SCOPE would have been the broad one.***
