# `KOS-AIP04-DISCOVERY-001` — **Capability Architecture Analysis**

> ## **Ownership and architectural-category decisions remain with PO/ARB.**

**Status: 🟡 PROPOSAL / DECISION PREPARATION ONLY. It selects no category and assigns no owner.**
**Assignment:** `S4-architecture-aip04-decision-prep` (seq 15 REGISTER · 17 HANDOFF · **18 START**) · **Grant:** `G-KOS-AIP04-DECISION-PREP` **+ `-AMD1`** · **Role:** Architecture Engineer
**Producing process, self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`): **`claude-code-session:5e1dd9ee`** — the original discovery producer and author of Amendment 1 and Correction #2. **Distinct from all three verifiers.** ⚠️ **Disclosed: this lane analyses four capabilities of its own construction, and twice corrected their statuses.** `R-34`/`P-2` binds — it must not verify or accept this analysis.
**Next actor: PO/ARB.**

---

## 1 · Executive conclusion

**`PROPOSED` — none of the four is recommended as a new bounded context, and one is proposed not to be a distinct capability at all.**

| | Capability | **Proposed category** | Bounded context? | ⚠️ **[EXISTENCE VERDICT MISSING — added by Amendment 1 §A1.1: category was answered without separately answering existence]** |
|---|---|---|---|
| **C-5** | Separation attestation | **cross-context control-plane assurance capability** | 🔴 **No** — owns no attestation record, assurance level, exception or dispute lifecycle |
| **C-10** | Knowledge distribution | **cross-layer delivery capability** whose *smallest coherent boundary* is **applicability + receipt** | 🔴 **No** — but the *receipt* is authoritative state nobody holds ⇒ the one live threshold question |
| **C-14** | Policy enforcement | ⭐ **PROPOSED: NOT a distinct capability as posited** 🔴 **[SUPERSEDED by Amendment 1 §A1.4 — replaced with the provisional wording; existence is `CONTESTED`, not `NO`]** — a **coverage gap in an existing, owned enforcement pattern** | 🔴 **No** |
| **C-19** | Communication composition | **stewardship / cross-cutting expression concern** | 🔴 **No** — fails the ten-part test on 6 of 10 |

> ### ⭐ **The structural finding: the four are not four candidate contexts. They are one CONTROL LOOP with four distinct capabilities in it.**
>
> ```
>   policy/knowledge DEFINED (BC-1)  →  C-10 DELIVERS it to the gated session  →  C-14 GATES the act
>                                                                                      ↓
>                        C-19 EXPRESSES the outcome  ←  C-5 ATTESTS who acted and how separately
> ```
>
> ⛔ **This is NOT a proposal to collapse them into one "platform capability"** — they have four different reasons to change (§8.2). It is a proposal that their *relationships* explain them better than their boundaries do.
>
> ⭐ **And a unifying invariant candidate falls out of it (§8.3): both control-plane capabilities are defeated by the SAME failure — self-assertion by the gated party.** `C-5` is defeated by self-declared separation; `C-14` by self-declared compliance.

**`DECIDED` inputs honoured:** the six-role model is adopted; **role ≠ capability ≠ bounded context ≠ agent ≠ service ≠ position**; ownership of all four is **deferred**. ⛔ **No role name was used as evidence for any capability conclusion, and no pairing (`C-5`→Verification, `C-10`→Knowledge, `C-14`→Governance, `C-19`→Communication) is adopted** — each is examined and each is found under-determined by the evidence (§11).

---

## 2 · The adopted six-role operating model as fixed input

`DECIDED` (PO/ARB 2026-08-19, registered): **Governance · Architecture · Implementation · Verification · Knowledge · Communication Engineer** are adopted as the engineering operating model — *"governed architecture as an operating-role model… distinct engineering responsibilities within the session-based engineering operating model."*

**`DECIDED`, and load-bearing here:** *"role ≠ bounded context · role ≠ capability · role ≠ agent · role ≠ platform service · role ≠ organizational position… **the adopted role model must not be used as proof that a capability or bounded context exists.**"*

⭐ **Consequence for this lane's own history, stated rather than left implicit:** the discovery's corrected finding `F-3″` turned on the **absence of an adopting governed act.** **That act now exists.** ⇒ `F-3″` is **superseded by decision from the point of adoption — not falsified.** ⛔ And the adoption act itself forbids the converse inference: *adoption is not evidence that a capability exists either.* **Historical material is explicitly not converted** — the 925-line brainstorming document remains unadopted, `OQ-I` open.

**Also `DECIDED` since Correction #2:** ⭐ **`SB-1` is CLEARED** (Verification #3, on write-class provenance: the producer of `50d55d26` is `2da45a86`, a process the bar already excluded). *"Durable… should not be re-litigated on independence grounds."* ⛔ **The clearance is of the independence precondition only — `SB-1`'s disposition remains PO/ARB decision 6.**

---

## 3 · Method

**Direction (binding, unchanged):** `capability → context/stewardship → ownership → role → agent → service/technology`. ⛔ **Never six adopted roles → invent six capabilities.**

**Per capability:** the **twenty-six-point framework** (§§4–7, rendered as one matrix each), then the **fifteen-part decision-ready block A–O**. Where the grant's sixteen-step sequence and `AMD1`'s eleven-stage chain and ten questions overlap, **the chain and the ten questions govern** — all are subsumed by the twenty-six points.

**Admissible categories, none privileged:** bounded context · cross-context capability · stewardship · control-plane function · another explicitly justified category.

**Evidence classes kept separate throughout, and marked inline:**

| Class | Meaning |
|---|---|
| **A · GOVERNED** | committed, registered or decided — ADRs, decisions, the capability map, `registry.yaml`, the workflow record, adopted rulings |
| **B · UNGOVERNED** | present in the estate but not adopted — untracked diagrams, the 925-line role document, brainstorming |
| **C · EXTERNAL / CORROBORATIVE** | `docs/knowledgeos/brainstorming/perpleixity_research_on_roles.md` (**1595 lines, UNTRACKED**) ⛔ **never project authority, never `DECIDED`** |

**Classification:** every material statement is `OBSERVED` · `INFERRED` · `PROPOSED` · `DECIDED` · `OPEN`.

---

## 4 · C-5 — Separation attestation

### 4.1 The twenty-six points

| # | | |
|---|---|---|
| 1 | **Capability definition** | establish, and communicate with evidence, **the strength of separation** between the producer, verifier and approver of a governed act |
| 2 | **Business outcome** | an assurance claim about a governed act that a third party can **check rather than believe** |
| 3 | **Trigger** | any act whose validity depends on separation — verification, acceptance, closure, conformance assertion |
| 4 | **Inputs** | the act; the identities/assignments involved; grants; write provenance; access facts |
| 5 | **Outputs** | an **assurance statement with a level** — not a boolean |
| 6 | **Authoritative decision/state owned** | 🔴 **none today.** No attestation record, no assurance level, no exception, no dispute — `OBSERVED` |
| 7 | **Core invariants** | `INV-ATTR-2` **(adopted, PO/ARB P-1–P-6, 2026-08-15): *"self-declared identity must never be represented as independently attested."*** `INV-ATTR-1`: identity is evidential only; no gate reads it |
| 8 | **Evidence proving occurrence** | 🟡 **partially demonstrated, once:** `SB-1` was cleared from **write-class `tool_use` provenance**, not from self-declaration — `OBSERVED` |
| 9 | **May approve** | PO/ARB (assurance policy); a verifier may *assert* separation but not *establish* it |
| 10 | **May override** | PO/ARB only — an assurance level is a value judgement |
| 11 | **May revoke** | PO/ARB; and any party producing contrary provenance |
| 12 | **Failure/uncertainty behaviour** | **declare the level as unestablished — never default to "separate."** Today: prose disclosure, unenforced |
| 13 | **Enforcement effect** | 🔴 **none.** Advisory: it changes what a report may *claim*, not what a process may *do* |
| 14 | **Ubiquitous language** | ✅ **own**: attestation · assurance level · declared vs observed · independence dimension · recusal · exception |
| 15 | **Lifecycle** | ⚠️ **borrowed** — it has no lifecycle of its own today; an attestation-with-dispute lifecycle is the threshold (§4.3) |
| 16 | **Needs authoritative state?** | ✅ **yes, if it is to be more than prose** — an attestation record must outlive the session |
| 17 | **Reason to change** | ⭐ **the attestation SUBSTRATE** — what the runtime can prove. **Shared with nothing else in the estate** |
| 18 | **Consumers** | verification, acceptance, conformance claims, PO/ARB signature |
| 19 | **Cross-context relationships** | consumes BC-7's assignment/grant facts; serves BC-3/BC-4's assurance claims; **owns neither** |
| 20 | **Candidate stewardship** | Verification stewardship · Governance control · shared platform infrastructure |
| 21 | **Candidate ownership** | **all `OPEN`** — see §10 |
| 22 | **Bounded-context test** | 🔴 **FAILS** — no authoritative state (6), no own lifecycle (15), no decision rights (9). ✅ passes language (14) and change-pressure (17) |
| 23 | **Control-plane test** | ✅ **PASSES strongly** — evaluates a condition *about an act*, emits a verdict, produces no domain value |
| 24 | **Six-role relationship** | ⚠️ **`C-5`→Verification Engineer is under-determined**: the *consumer* of assurance is not necessarily its *owner*, and a verifier attesting its own separation is the exact failure `INV-ATTR-2` names. `OPEN` |
| 25 | **Agent suitability** | 🔴 **NOT as self-attestation** — a process cannot attest its own separation. ✅ **Possibly as third-party attestation**: `SB-1`'s clearance shows a *different* process can establish authorship from provenance. ⭐ **Refines the discovery's absolute claim** |
| 26 | **Service/technology implications** | if adopted, it must read provenance the acting party cannot write — an **isolation** requirement, not a feature |

### 4.2 The nine separation dimensions — separated as the act requires

| Dimension | Status today | Class |
|---|---|---|
| **role difference** | ✅ mechanically validated at `REGISTER`; immutable per `R8` | GOVERNED · `OBSERVED` |
| **identity difference** | 🔴 **self-declared only** — `executionContext` is free text | GOVERNED · `OBSERVED` |
| **assignment separation** | ✅ recorded — one assignment per lane, predecessor chain | GOVERNED · `OBSERVED` |
| **authority separation** | ✅ **mechanical** — `COMPLETE` refuses any writer but `governance`/`human` (`G-1`); grants have one writer (`G-2`) | GOVERNED · `OBSERVED` |
| **access separation** | 🔴 **none** — every lane has the same filesystem and tool access | GOVERNED · `OBSERVED` |
| **artifact/evidence isolation** | 🔴 **none** — any lane may write any artifact | GOVERNED · `OBSERVED` |
| **evidence immutability** | 🟡 **partial** — the transition log is append-only; documents are not | GOVERNED · `OBSERVED` |
| **organizational independence** | ⚪ **not applicable / undefined** in a single-operator estate 🔴 **[SUPERSEDED by Amendment 1 §A1.2 → `NOT_ESTABLISHED`; "not applicable" risked collapsing into PASS]** | `OPEN` |
| **assessment impartiality** | 🟡 **procedural only** — recusals are honoured by disclosure, and *were* honoured twice (`SB-1`) | GOVERNED · `OBSERVED` |

> ⭐ **`INFERRED` — the sharpest C-5 result: separation is already STRONG where it is authority-shaped and ABSENT where it is identity- or access-shaped.** Authority separation is mechanical; **identity, access and artifact isolation are not enforced at all.** ⇒ **"separation" is not one property with one gap. It is nine properties with three different maturity levels**, which is why a boolean attestation would misdescribe the estate. **`EXTERNAL / CORROBORATIVE`:** the research reaches the same conclusion independently — *"separation is graded rather than binary"*, and it challenges *"role-name difference as evidence of independence"* and *"self-declaration as sufficient."*

### 4.3 The bounded-context threshold, stated so the PO/ARB can test it

**`PROPOSED`: `C-5` becomes a plausible bounded context if and only if it comes to own — as authoritative state with its own lifecycle — attestation records · assurance levels · exceptions · disputes and their adjudication.** ⛔ **It owns none of these today**, so on current evidence it is a **cross-context control-plane capability**, not a context.

### 4.4 Decision-ready block

**A Evidence** `INV-ATTR-2` adopted; `executionContext` free text; `SB-1` cleared from provenance; the nine-dimension table · **B Definition** §4.1(1) · **C Outcome** a checkable assurance claim · **D Invariants** `INV-ATTR-1`/`INV-ATTR-2` · **E Authority** PO/ARB approves/overrides/revokes · **F Evidence** partially demonstrated once, by hand · **G Enforcement** none — advisory · **H Boundary** the *claim* about an act, never the act · **I Candidate category** **cross-context control-plane assurance capability** · **J Steward/owner options** §10 · **K Why it fits** passes the control-plane test on all four properties; owns no state · **L Why alternatives don't** *bounded context* fails on state/lifecycle/decision-rights; *stewardship alone* understates a distinct change-pressure (17) and its own language (14) · **M Six-role relationship** consumer ≠ owner; `OPEN` · **N Open questions** `OQ-A`; and **is attestation possible at all in this runtime?** — ⭐ Verification #3 explicitly flagged its own `SB-1` clearance as evidence about this **and declined to answer**; this lane analyses it and also does not answer it · **O PO/ARB decision** category + owner + whether the §4.3 threshold is the right test

---

## 5 · C-10 — Knowledge distribution

### 5.1 The full path, with its existing owners mapped (`OBSERVED`)

```
created → governed → published → selected → retrieved → assembled → delivered → acknowledged/applied → monitored
  └────── BC-1 Knowledge Governance ──────┘            └── BC-6 ──┘        └─ BC-7 token ─┘   └── nobody ──┘
              (CAP-03 — NOT BUILT)                     (CAP-01 live)        (LEAKING)
```

| Stage | Owner today | Class |
|---|---|---|
| creation · governance · publication | **BC-1**, `CAP-03` **not built** | GOVERNED · `OBSERVED` |
| retrieval · assembly · session bootstrap | **BC-6**, `CAP-01` **live** (`AST-002`, SessionStart) | GOVERNED · `OBSERVED` |
| **selection / applicability** | 🔴 **nobody** | `OBSERVED` |
| **delivery** | 🟡 **BC-7's `HANDOFF` token, by leakage** — `EKS-01`: a lane was *"taught the old path by its workflow record's `tokenRef`"* | GOVERNED · `OBSERVED` |
| **acknowledgement / receipt** | 🔴 **nobody** | `OBSERVED` |
| monitoring / invalidation | 🔴 **nobody** | `OBSERVED` |

### 5.2 ⭐ The smallest coherent capability boundary

> ## **`PROPOSED`: `C-10` is NOT the whole path. Its smallest coherent boundary is APPLICABILITY + RECEIPT.**
> **Creation, governance and publication already belong to BC-1. Retrieval, assembly and bootstrap already belong to BC-6.** What **nothing** owns is: **(a) deciding WHICH governed knowledge is applicable to THIS assignment, and (b) evidencing that the executing session actually holds the CURRENT version.**
> ⛔ **Everything else in the path has an owner, and claiming it for `C-10` would take work from two contexts that already hold it.**

**Candidate invariant** *(`PROPOSED`, from the discovery)*: **`I-K1` — the executing session holds the CURRENT governed rule at START.**

⚠️ **The grant's warning honoured explicitly:** *"do not infer that every lifecycle problem belongs to `C-10`."* **Missing STARTs, off-record execution and the Amendment-1 provenance gap are BC-7 LIFECYCLE problems, not distribution problems** — they are about whether an act was *recorded*, not whether knowledge *arrived*. ⛔ **They are excluded from C-10's evidence base.** *(`INFERRED`, and it narrows the case rather than strengthening it.)*

### 5.3 The twenty-six points (compressed to the discriminating rows)

| # | | |
|---|---|---|
| 1–2 | **Definition / outcome** | ensure the session executing a governed act holds the **applicable, current** governed knowledge — outcome: **rule-application failures stop being invisible** |
| 3 | **Trigger** | assignment START; and any knowledge supersession affecting an in-flight session |
| 5–6 | **Outputs / state owned** | an **applicability determination** and a **receipt**. 🔴 Neither exists today — `OBSERVED` |
| 8 | **Evidence of occurrence** | 🔴 none. `EKS-01` is evidence of its **absence**: *"Recording a rule is not sufficient"* |
| 12 | **Failure behaviour** | ⭐ **the live question: does unavailable required knowledge HALT the session or warn it?** `OPEN` |
| 13 | **Enforcement effect** | 🔴 advisory today. **If it halts at START it becomes control-plane** — which is why it is adjacent to `C-14` (§8) |
| 14 | **Language** | ⚠️ **borrowed from BC-1** (rule, version, supersession) **plus its own**: applicability · receipt · currency-at-START |
| 17 | **Reason to change** | **the DISTRIBUTION MECHANISM** — hooks, payload shape, discovery. ⭐ **Distinct from BC-1's knowledge-lifecycle clock** |
| 22 | **Bounded-context test** | 🔴 **fails on language (mostly borrowed) and lifecycle (the session's)** — ⚠️ **but PASSES on authoritative state IF the receipt is adopted.** ⭐ **This is the one genuine threshold among the four** |
| 23 | **Control-plane test** | 🟡 **conditional** — advisory today, control-plane if it gates START |
| 24 | **Six-role relationship** | ⚠️ `C-10`→Knowledge Engineer is **under-determined**: BC-1 owns knowledge *content*; the gap is *delivery mechanism*, whose change-pressure is BC-6-shaped. **`OPEN`, and genuinely two-sided** |
| 25 | **Agent suitability** | ⚠️ **premature** — the applicability *decision* may be a judgement; the *receipt* is mechanical |
| 26 | **Service implications** | plausibly an extension of `CAP-01`'s existing bootstrap payload — ⛔ **not proposed here** |

**`EXTERNAL / CORROBORATIVE`:** the research supports *"treating distribution as more than storage or search"*, names the **context-receipt** and **reconciliation-loop** patterns, and challenges *"the assumption that publication implies execution-time use"* and *"that one role necessarily owns the entire capability."* Its own open list matches ours almost exactly — *"Is 'received' sufficient, or must the agent demonstrate use?"*, *"Who decides applicability?"*, *"Does context invalidation halt active sessions?"*

### 5.4 Decision-ready block

**A** §5.1 map + `EKS-01` · **B** applicability + receipt · **C** rule-application failures become visible · **D** `I-K1` (`PROPOSED`) · **E** BC-1 approves content; **who approves applicability is `OPEN`** · **F** none today · **G** advisory now, control-plane if it gates START · **H** **applicability + receipt only** · **I** **cross-layer delivery capability** · **J** §10 · **K** it spans four owners and adds two unowned stages — a path, not a context · **L** *bounded context* fails on borrowed language and lifecycle **unless the receipt becomes authoritative state**; *stewardship alone* cannot hold a receipt · **M** two-sided BC-1/BC-6 pull; `OPEN` · **N** `OQ-B`; halt-or-warn; who decides applicability · **O** category · owner · **and whether the receipt is adopted as authoritative state** *(the decision that changes the category)*

---

## 6 · C-14 — Policy enforcement

### 6.1 ⭐ FIRST question, as the mandated ordering requires: **does a distinct C-14 capability exist?**

**The nine stages, mapped against governed evidence (`OBSERVED`):**

| Stage | Who does it today | Evidence |
|---|---|---|
| **policy definition** | BC-1 / decisions / the contract | GOVERNED |
| **policy derivation** | ✅ `doc-placement.php` — **live**, exit 0 for every case | GOVERNED |
| **policy evaluation** | ✅ `AST-007` evaluates *"is the environment testing?"* | GOVERNED |
| **observation** | ✅ `CAP-09` — *"read-only observation of constitutional guards"* | GOVERNED |
| **admission control** | ✅ `AST-007` at **`runtime_moments: [PRE_ACTION]`** | GOVERNED |
| **blocking / halt** | ✅ `CAP-09` — ***"on a trip: halt, escalate, never retry, never modify"***; `governance_tier: 1` | GOVERNED |
| **escalation** | ✅ `CAP-09` — *"escalate"* | GOVERNED |
| **audit** | ✅ the protocol / transition log | GOVERNED |
| **advisory tripwires** | ✅ `CAP-06` — `AST-005/006/014`, **all `governance_tier: 2`, non-blocking** | GOVERNED |

> ## ⭐ **`PROPOSED` — the answer is NO: on current evidence there is no distinct `C-14` capability.** 🔴 **[SUPERSEDED by Amendment 1 §A1.4. This was a CATEGORY/OWNERSHIP argument doing an EXISTENCE test's work — the exact unsound move AMD2's refinement 1 names. Original wording retained below as historical.]**
> **Every stage of the policy-to-effect path is already performed by an owned capability.** The complete pattern — *observe → evaluate → admit/block → halt → escalate* — **exists today at Tier-1**, owned by **`CAP-09` Constitutional Observation & Escalation (BC-3)**, with write-paths *"deliberately closed."*
>
> **What `EKS-02` actually records is not a missing capability but a missing COVERAGE:** `CAP-09`'s definition scopes it to **constitutional guards (`CI-1..5`/`Q7`)**. Documentation placement is **derived but never admitted-or-blocked**, because it is not a constitutional guard. ⇒ ***"the rule exists, but the workflow does not make it hard enough to violate"*** **is a policy-TIERING gap in existing machinery.**
>
> ⛔ **This is exactly the inference the grant forbade in the other direction — and it is refused here too: a blocking script is not a capability, AND a coverage gap is not a capability either.**

### 6.2 The alternative reading, presented fairly because it may be the right one

**`PROPOSED` alternative:** if the PO/ARB regards **"which policies are Tier-1 (blocking) versus Tier-2 (advisory)"** as an authoritative decision that must have an owner, then **that decision-right IS a capability** — but it is **policy TIERING**, not policy *enforcement*, and its outputs are *tier assignments*, not blocks.

| | Reading 1 — no distinct capability | Reading 2 — policy tiering is the capability |
|---|---|---|
| What is missing | coverage of an existing pattern | an owner for the tier decision |
| Consequence | extend `CAP-09`'s scope, or add a Tier-1 guard under `CAP-06` — **a policy decision, not new architecture** | a small decision-right capability, plausibly Governance-shaped |
| Bounded context? | 🔴 n/a | 🔴 no — one decision, no lifecycle |

### 6.3 The twenty-six points (discriminating rows)

| # | | |
|---|---|---|
| 6 | **State owned** | 🔴 none beyond the tier assignments already in `registry.yaml` |
| 7 | **Invariant** | `PROPOSED` `I-P1`: **a mechanically derivable rule is mechanically hard to violate** |
| 12 | **Failure behaviour** | ✅ **decided already for Tier-1**: *"halt, escalate, never retry, never modify"* — `OBSERVED` |
| 13 | **Enforcement effect** | ✅ **Tier-1 blocking exists and is adopted** (`AST-007`); Tier-2 is advisory by design |
| 14 | **Language** | ⚠️ **shared with BC-2/BC-3** — tier, gate, tripwire, guard, trip |
| 17 | **Reason to change** | ⚠️ **the enforcement POSTURE** — which couples to `CAP-06`'s tripwire model and `CAP-09`'s constitutional scope. **Not independent** |
| 22 | **Bounded-context test** | 🔴 **fails on language, lifecycle, state and change-independence** |
| 23 | **Control-plane test** | ✅ **passes** — but as an existing owned function, not a new one |
| 24 | **Six-role** | ⚠️ `C-14`→Governance Engineer under-determined: **the live Tier-1 instance sits in BC-3 Verification & Evidence**, not Governance. `OPEN` (`OQ-H`) |

**`OBSERVED` (Correction #2, carried):** `AST-007`'s `trace` is `capability: CAP-09`, `context: verification-evidence`. ⇒ ⚠️ **its status is `CONTESTED`, not `live`: whether it is a *policy-enforcement* instance or a *constitutional-observation* instance is `OQ-H` and is not decided here.**

**`EXTERNAL / CORROBORATIVE`:** the research separates *policy definition · evaluation · enforcement* as distinct, and lists as unknown *"Is C-14 responsible for policy decision, effectuation, or both?"* and *"Is enforcement synchronous or asynchronous?"* — **it corroborates the decomposition and leaves the ownership question open exactly where we do.**

### 6.4 Decision-ready block

**A** the nine-stage map; `AST-007` Tier-1/`CAP-09`; `AST-005/006/014` Tier-2; `doc-placement.php` · **B** *(if it exists)* admit-or-block a governed act against a derived policy · **C** rules become hard to violate · **D** `I-P1` (`PROPOSED`) · **E** PO/ARB sets tiers; `CAP-09` executes · **F** the guard trip and its escalation · **G** ✅ **real and adopted, at Tier-1, narrowly scoped** · **H** the policy families in scope, not the mechanism · **I** ⭐ **not a distinct capability (Reading 1)** · alternative: **policy tiering (Reading 2)** · **J** §10 · **K** every stage already has an owner · **L** *bounded context* fails four tests; *cross-context capability* would duplicate `CAP-09`; *stewardship* is plausible **only under Reading 2** · **M** BC-3 holds the instance, Governance holds the tier decision — `OQ-H` · **N** `OQ-H`; `SB-3` · **O** ⭐ **which reading; and if Reading 1, that the remedy is a policy/tier act and NOT a new capability**

---

## 7 · C-19 — Communication composition

### 7.1 The ten-part bounded-context test, applied

| Test | Result | Evidence |
|---|---|---|
| own ubiquitous language | 🔴 **no** — it speaks every other context's language | `INFERRED` |
| stable invariants | 🟡 **one candidate** — `A-7`/`GOV-HUMAN-01` decision-readiness | GOVERNED |
| independent lifecycle | 🔴 **no** — the reporting act's | `INFERRED` |
| independent authority | 🔴 **no** | `OBSERVED` |
| authoritative state | 🔴 **no** — no audience registry, no consent, no channel, no retention | `OBSERVED` |
| distinct reason to change | 🔴 **no** — it changes when the **AUDIENCE** changes, which is a different *kind* of reason | `INFERRED` |
| audience / consent / channel concepts | 🔴 **none in the estate** | `OBSERVED` |
| delivery / acknowledgement state | 🔴 **none** — ⭐ **and the one thing that would supply it belongs to `C-10`** (§5.2 receipt) | `INFERRED` |
| legal or operational effect | 🔴 **none observed** | `OBSERVED` |
| multiple consumers | ✅ **yes** — every governed artifact has a human audience | `OBSERVED` |

> ## **`PROPOSED`: fails on 6 of 10 ⇒ a STEWARDSHIP / cross-cutting expression concern, not a bounded context.**
> ⭐ **And the decisive cross-capability point: the only state that could make a Communication context real — delivery-with-acknowledgement — is `C-10`'s receipt, not `C-19`'s.** ⇒ **building `C-10` would REDUCE, not increase, the case for a Communication context.**

**Remaining twenty-six points:** trigger = any act that must be reported to a human · output = a decision-ready rendering · **enforcement effect = none** · **agent suitability = possible later, as a duty of the reporting lane, never as an actor** · **service implications = none before `C-10`.**

⚠️ **`UNGOVERNED` evidence, disposed of and not used as support:** the untracked `01-system-context.puml` models `Person(comm, "Governance Communication", "Next-actor and handover communication")` — placing communication **inside Governance**, the same shape as this stewardship proposal. ⛔ **It is a concurring sketch, not evidence; the adoption act expressly did not convert it; `OQ-I` open.** ⚠️ **And Correction #2's disclosure stands: this drafter's convergence with that document's "Option B" cannot be established as independent** — so it is **not** counted as corroboration.

**`EXTERNAL / CORROBORATIVE`:** the research's own DDD test concludes communication is *"often cross-cutting/delivery unless it has its own domain language and lifecycle"*, and lists **bounded context** as a treatment **only** where audience, consent, channel, retention and acknowledgement are modelled. ⇒ **it corroborates the test and supplies the threshold we lack.**

### 7.2 Decision-ready block

**A** §7.1 · **B** render governed state so a human can decide · **C** decisions are made on complete, current information · **D** `A-7` decision-readiness · **E** none — it holds no authority · **F** the artifact itself · **G** none · **H** the *expression* of state, never the state · **I** **stewardship / cross-cutting concern** · **J** §10 · **K** borrowed language + audience-shaped change pressure + zero authoritative state · **L** *bounded context* fails 6/10; *delivery capability* would duplicate `C-10`; *shared service* is premature · **M** ⚠️ **the Communication Engineer role is adopted — and that is NOT evidence that a Communication context or capability exists** (the adoption act forbids the inference) · **N** `OQ-I`; whether `A-7` is its only invariant · **O** category; and whether the stewardship attaches to the reporting lane or to Governance

---

## 8 · Cross-capability analysis

### 8.1 Shared and distinct concerns

| | C-5 | C-10 | C-14 | C-19 |
|---|---|---|---|---|
| **Gates an act?** | ✅ assurance claim | 🟡 only if it halts START | ✅ admission | 🔴 no |
| **Produces domain value?** | 🔴 no | 🔴 no | 🔴 no | 🔴 no |
| **Owns authoritative state today?** | 🔴 | 🔴 | 🔴 | 🔴 |
| **Own language?** | ✅ | 🟡 partly | 🔴 | 🔴 |
| **Reason to change** | attestation substrate | distribution mechanism | enforcement posture | audience |
| **Control-plane?** | ✅ **yes** | 🟡 conditional | ✅ **yes** | 🔴 no |

### 8.2 Four different clocks — why they must not be collapsed

**`INFERRED`:** the four change for **four unrelated reasons** (row 5 above). ⛔ **A single "platform capability" would couple four independent change clocks** — the defect a bounded boundary exists to prevent. **They are related by *flow*, not by *boundary*.**

### 8.3 ⭐ The unifying control-plane invariant candidate

> **`PROPOSED`:** both control-plane capabilities are defeated by **the same failure mode — self-assertion by the gated party.**
> **`C-5`** is defeated by **self-declared separation**; **`C-14`** by **self-declared compliance.**
> ⇒ **candidate shared invariant: *a gate's verdict may not be issued by the party the gate constrains.***
> **`OBSERVED` support:** `G-1` already enforces exactly this for closure (`COMPLETE` refuses any writer but `governance`/`human`), and `CAP-09`'s write-paths are *"deliberately closed."* ⭐ **So the invariant is not new — it is already implemented twice, and never stated as a general rule.** ⛔ **Proposed, not adopted.**

### 8.4 Dependencies

```
BC-1 defines policy/knowledge → C-10 delivers it to the gated session → C-14 admits or blocks the act
                                                                              ↓
                                   C-19 expresses the outcome  ←  C-5 attests who acted, and how separately
```

**`INFERRED` consequences the PO/ARB should weigh:** **`C-14` cannot enforce a policy the session never received** ⇒ `C-14` **depends on** `C-10`. **`C-5` attests acts that `C-14` gates** ⇒ they share an evidence substrate (provenance). **`C-19` is downstream of all three** and adds no state. ⇒ ⚠️ **[RECLASSIFIED by Amendment 1 §A1.7 into SEMANTIC / EVIDENCE / IMPLEMENTATION dependencies — a dependency is not ownership]** ⭐ **build order is not arbitrary: `C-10` before `C-14` coverage; provenance before `C-5`; `C-19` last or never.**

### 8.5 Overlaps and duplications

**Evidence overlap:** `C-5` and `C-14` both need **write/act provenance the acting party cannot forge** — ⭐ **one shared infrastructure need, two capabilities.** **Authority overlap:** PO/ARB approves/overrides both. **Duplication risk:** ⚠️ **if `C-19` were made a delivery capability it would duplicate `C-10`'s receipt** (§7.1).

---

## 9 · Category assessment · comparison table

| | **C-5** | **C-10** | **C-14** | **C-19** |
|---|---|---|---|---|
| **Category (`PROPOSED`)** | cross-context **control-plane assurance capability** | **cross-layer delivery capability** | ⭐ **not a distinct capability** (alt: policy tiering) | **stewardship / cross-cutting concern** |
| **Boundary** | the assurance *claim* about an act | **applicability + receipt** only | the policy *families* in scope | the *expression* of state |
| **Authority** | PO/ARB approves/overrides/revokes | BC-1 for content; applicability `OPEN` | PO/ARB sets tiers; `CAP-09` executes | none |
| **Evidence** | 🟡 demonstrated once, by hand (`SB-1`) | 🔴 none — `EKS-01` evidences its absence | ✅ `AST-007` Tier-1 trip + escalation | the artifact itself |
| **Lifecycle** | 🔴 borrowed | 🔴 the session's | ✅ the act's (PRE_ACTION → trip) | 🔴 the reporting act's |
| **Stewardship** | Verification · Governance · platform infra | BC-1 · BC-6 · shared service | BC-3 holds the instance; Governance the tier | the reporting lane · Governance |
| **Candidate owner** | **`OPEN`** | **`OPEN`** (`OQ-B`) | **`OPEN`** (`OQ-H`) | **`OPEN`** |
| **Role relationship** | consumer ≠ owner | two-sided BC-1/BC-6 | instance in BC-3, tier in Governance | role adopted ≠ context exists |
| **Bounded-context test** | 🔴 fails (state, lifecycle, rights) | 🔴 fails — ⚠️ **unless the receipt is adopted** | 🔴 fails 4 tests | 🔴 fails 6/10 |
| **Unresolved** | is attestation possible at all? | halt-or-warn; who decides applicability | which reading; `OQ-H` | `OQ-I`; is `A-7` its only invariant |

---

## 10 · Stewardship / ownership options — ⛔ **none selected**

| Capability | Option 1 | Option 2 | Option 3 | Status |
|---|---|---|---|---|
| **C-5** | Verification **stewardship** (consumer-steward) | **Governance control** (authority-shaped, matching `G-1`/`G-2`) | **shared platform infrastructure** reading provenance | **`OPEN` / `PROPOSED` only** |
| **C-10** | **BC-1** — knowledge reaching its consumer is a knowledge duty | **BC-6** — it is session-payload machinery, `CAP-01` already does a version | **split**: applicability → BC-1, receipt → BC-6 | **`OPEN`** (`OQ-B`) |
| **C-14** | **no owner needed** (Reading 1 — extend `CAP-09`/`CAP-06` scope) | **Governance** owns policy tiering (Reading 2) | **BC-3** retains it as constitutional observation | **`OPEN`** (`OQ-H`) |
| **C-19** | **stewardship of the reporting lane** (extends `A-7`) | **Governance stewardship** | **defer** until `C-10`'s receipt is decided | **`OPEN`** |

---

## 11 · Relationship to the six-role operating model

**`DECIDED`:** the six roles are adopted. **`DECIDED`:** role ≠ capability ≠ context ≠ agent ≠ service ≠ position.

| Forbidden hypothesis | Finding |
|---|---|
| `C-5` → **Verification Engineer** | ⚠️ **under-determined.** The verifier *consumes* assurance; a verifier attesting its own separation is precisely `INV-ATTR-2`'s failure. `OPEN` |
| `C-10` → **Knowledge Engineer** | ⚠️ **under-determined and two-sided.** BC-1 owns content; the gap is *delivery mechanism*, whose change-pressure is BC-6-shaped. `OPEN` |
| `C-14` → **Governance Engineer** | ⚠️ **under-determined.** The only live Tier-1 instance traces to **BC-3**, not Governance. `OPEN` |
| `C-19` → **Communication Engineer** | ⚠️ **under-determined — and the clearest case of the forbidden inference.** The role's adoption is *not* evidence a capability or context exists; the adoption act says so in terms. `OPEN` |

> ⭐ **`INFERRED`: all four plausible pairings survive as hypotheses and none is established.** **That is the analysis working, not failing** — the direction `capability → … → role` was run in the required order, and it did not terminate in a role for any of the four. ⛔ **Adopted roles remain the operating model for *who does engineering work*; they are not, on this evidence, the owners of these four capabilities.**

---

## 12 · Open architectural questions

| # | Question | Status |
|---|---|---|
| `OQ-A` | Is `C-5` a context, stewardship or platform service — **and can separation be attested at all in this runtime?** ⭐ Verification #3 flagged its own `SB-1` clearance as evidence and declined to answer; this lane also does not answer it | **OPEN** |
| `OQ-B` | Does `C-10` belong to BC-1, BC-6, or split? | **OPEN** |
| `OQ-H` | `C-14`: is there any instance at all, and then BC-2 or BC-3? | **OPEN** |
| `OQ-I` | Disposition of the untracked material (incl. the 925-line role document and the `Governance Communication` actor) | **OPEN** |
| 🆕 `OQ-J` | **Is `C-10`'s receipt adopted as authoritative state?** ⭐ **The single decision that changes a category in this analysis** | **OPEN** |
| 🆕 `OQ-K` | **Does unavailable required knowledge HALT a session or only warn it?** — decides whether `C-10` is advisory or control-plane | **OPEN** |
| 🆕 `OQ-L` | **Is §8.3's shared control-plane invariant adopted** — *a gate's verdict may not be issued by the party it constrains*? | **OPEN** |
| carried | `OQ-C` (ADR-C7) · `OQ-D` (EKP/PKS) · `OQ-E` ("review"/"role") · `OQ-F` (ADR-C2) · `OQ-G` (sequencing) · `SB-2` · `SB-3` | **OPEN, unchanged** |

⛔ **The OQ register in the discovery proposal is NOT modified by this lane** *(the act forbids changing OQs)*. `OQ-J`/`OQ-K`/`OQ-L` are **proposed additions** for the PO/ARB to register or reject.

---

## 13 · PO/ARB decision candidates

| # | Decision | Note |
|---|---|---|
| **1** | **Category for each of the four** — from the five admissible categories | ⛔ four separate answers; a blanket answer would repeat the `V-2` conflation |
| **2** | **`C-14`: Reading 1 or Reading 2** | ⭐ **If Reading 1, the remedy is a POLICY/TIER act, not a new capability** — and `SB-3` reduces accordingly |
| **3** | **`OQ-J`** 🔴 **[REFORMULATED by Amendment 1 §A1.3 — *"authoritative for WHICH claim?"*, split into five: delivery · possession · applicability · execution · compliance. The yes/no form let one authority cover five assertions.]** ~~is `C-10`'s receipt authoritative state?~~ | ⭐ the only decision here that flips a category |
| **4** | **`OQ-K` — halt or warn on missing required knowledge** | determines whether `C-10` is control-plane |
| **5** | **Owners / stewards** — or explicit deferral with named triggers | §10 options; **none selected** |
| **6** | **`OQ-L` — adopt the shared control-plane invariant?** | already implemented twice, never stated |
| **7** | **Build order** | `C-10` before `C-14` coverage · provenance before `C-5` · `C-19` last or never (§8.4) |
| **8** | **`OQ-I`** — disposition of the untracked material | carried, unchanged |

---

## 14 · External research — corroboration and challenge

**Class C · `EXTERNAL / CORROBORATIVE` throughout. ⛔ Never project authority; never `DECIDED`. Source: `docs/knowledgeos/brainstorming/perpleixity_research_on_roles.md` — 1595 lines, UNTRACKED, therefore also class-B ungoverned internally.**

| Where it **SUPPORTS** us | Where it **CHALLENGES** us | Where it leaves things **OPEN** |
|---|---|---|
| separation is **graded, not binary** → §4.2's nine dimensions | *"self-declaration as sufficient"* and *"role-name difference as evidence of independence"* — **both are failure modes this estate has exhibited** | *"Who adjudicates disputed independence?"* · *"What is the acceptable fallback when independent personnel are unavailable?"* — ⚠️ **acutely relevant: this estate is a single-operator estate** |
| **publication ≠ delivery**; the **context-receipt** and **reconciliation-loop** patterns → §5.2 | *"the assumption that one role necessarily owns the entire capability"* — ⚠️ **which is exactly the four forbidden pairings** | *"Is 'received' sufficient, or must the agent demonstrate use?"* · *"Who decides applicability?"* → `OQ-J`/`OQ-K` |
| **policy definition ≠ evaluation ≠ enforcement** → §6.1's nine stages | — | *"Is C-14 responsible for policy decision, effectuation, or both?"* → the two readings |
| communication is **cross-cutting/delivery unless it has its own language and lifecycle** → §7.1 | — | the audience/consent/retention threshold it names is **absent here**, which is *why* §7.1 fails 6/10 |
| **capabilities do not automatically become bounded contexts** → the whole method | — | — |

> ⚠️ **Two honesty notes.** ① **The research corroborates our *method* and *decomposition* far more than any *conclusion*** — its own "what remains unknown" lists overlap ours almost exactly, so **it does not narrow our open questions.** ② **It is UNTRACKED**, so under the adoption act's own rule it is **not converted into governed architecture by being cited here.**

---

## 15 · Explicit non-decisions

⛔ **No ownership assigned · no bounded context created · no capability created · no agent · no service · no technology · no role redefined · no six-role adoption reopened · no PO/ARB decision answered · nothing implemented · BC-7 untouched · role definitions untouched · the OQ register untouched · no platform component · no implementation grant · Track 1 untouched · `SB-1`'s disposition not decided (its independence precondition is separately CLEARED and that clearance is not re-litigated here).**

**Every category in §9 is `PROPOSED`. Every owner in §10 is `OPEN`.**

> ## **Ownership and architectural-category decisions remain with PO/ARB.**

**ANALYSIS DELIVERED · STOPPING.** ⛔ **No self-verification · no self-acceptance · assignment NOT closed (`G-1`).** **Next actor: PO/ARB.**

**Traceability:** `G-KOS-AIP04-DECISION-PREP` + `-AMD1` · assignment seq 15/17/18 · **six-role adoption registration 2026-08-19** (the decision, the non-equivalences, the historical-material clause) · Verification #3 (`READY FOR PO/ARB DECISION`; **`SB-1` CLEARED, durable**; §7.1's declined `OQ-A` answer) · `G-KOS-AIP04-VERIFY3-AMD1`/`-AMD2` · Correction #2 (`C-14` `CONTESTED`; `OQ-H`; the completed 104-file survey) · Amendment 1 · the discovery proposal · the record-integrity disposition · **code/registry read directly: `registry.yaml` `AST-005/006/007/014` (tiers, adoption, `PRE_ACTION`, `AST-007.trace → CAP-09/verification-evidence`) · `Phase-02.5-Certification-Plan.md` `CAP-09` (*"halt, escalate, never retry, never modify"*, Tier-1, write-paths closed) · `CAP↔BC` map v2 · `workflow-state.php` (`G-1`/`G-2`/`R8`/`REGISTER` role validation) · `EKS-01`/`EKS-02`** · `INV-ATTR-1`/`INV-ATTR-2` (adopted, P-1–P-6 2026-08-15) · `A-7`/`GOV-HUMAN-01` · **Class C: `perpleixity_research_on_roles.md` (1595 lines, untracked)** · `R-34`/`P-2`.

---
---

# AMENDMENT 1 — epistemic precision under `G-KOS-AIP04-DECISION-PREP-AMD2`

| | |
|---|---|
| **Status** | 🟡 **AMENDMENT to the existing analysis. Not a competing decision surface. Nothing decided.** |
| **Authority** | `G-KOS-AIP04-DECISION-PREP` + **`-AMD1`** + **`-AMD2`** (registered `39f14f15`), read together, append-only |
| **Lane** | `S4-architecture-aip04-decision-prep`, **ACTIVE at seq 18 and holding mutation ownership** ⇒ **no new START required**, and none was taken |
| **Producing process** | **`claude-code-session:5e1dd9ee`** — self-declared, **not attestable** (`INV-ATTR-2`/`G-2`); the author of the original analysis and of the three prior corrections in this work item |
| **Original** | **`ba74dbdd` retained in full above.** Six additive supersession pointers were inserted beside superseded statements, **edited by verified line index with a content assertion before writing** — the discipline `W-2` taught in this same work item |
| **Next actor** | **Independent Verification** |

### ⚠️ A1.0 · The source review is not in the estate — recorded, not worked around

**`OBSERVED`, independently re-verified by this lane:** a full-repository search (all file types, tracked and untracked, no truncation) for an artifact matching *"independent review of the capability architecture analysis"* returns **nothing**, and `docs/knowledgeos/reviews/` contains **no capability-architecture review**. The registration commit states the same in its own subject.

⇒ **The refinements below are implemented as the PO/ARB's registered instructions on their own merits.** ⛔ **No finding is attributed to a review, no review is cited as evidence, and no reviewer's independence is asserted** — it would be unattestable here in any case.

> ### ⭐ **`INFERRED` — and it belongs in the analysis, not only in the record:** **this is the third external input in two days whose artifact is absent from the governed channel** — the 925-line role model (`OQ-I`), the external research (untracked), and now this review.
> **That recurrence is evidence about a KNOWLEDGE-CHANNEL property: substantive input is reaching governed decisions from outside the governed channel, and each time its provenance is unattestable.** ⚠️ **It bears on `C-10`, and per `AMD1` it is NOT assumed to be owned by `C-10`** — it may equally be a Governance-intake concern. ⛔ **Recorded as evidence; no owner inferred.** *(It is also `EXTERNAL`-adjacent evidence for the research's own challenge: "publication does not imply execution-time use" — here, input is used before it is published at all.)*

---

## A1.1 · REFINEMENT 1 — capability **existence** separated from capability **category**

### The unsound move, named precisely

**`OBSERVED` in the original:** the analysis answered *category* and then presented the result as an answer about *existence*. **The clearest instance is `C-14`:** *"no distinct capability exists"* was supported by *"every stage already has an owner"* — **an ownership/category argument doing an existence test's work.** ⛔ **A capability can be real and belong to no context of its own.**

**`AMD2`'s fourth existence value — `NOT YET ESTABLISHED` — was the answer previously unavailable, and it is the honest one across part of this surface.**

### The two verdicts, now separate

| | **(A) EXISTENCE** | Basis for (A) — *stated independently of category* | **(B) CATEGORY** *(only after A)* |
|---|---|---|---|
| **C-5** | ✅ **YES** | ⭐ **two independent grounds:** ① `INV-ATTR-2` is an **ADOPTED invariant *about* separation attestation** (PO/ARB `P-1`–`P-6`, 2026-08-15) — the estate has decided a rule that **presupposes** the capability; ② the capability has been **performed**: Verification #3 established authorship from write-class provenance. **A capability with an adopted governing invariant that has been performed exists.** `OBSERVED` | **control-plane function** — argued **positively** in §A1.2, not residually |
| **C-10** | 🟡 **NOT YET ESTABLISHED** | the **need** is evidenced (`EKS-01`), but **no instance of applicability-determination or receipt has ever been performed**, and no invariant about it is adopted. ⚠️ **Its necessity is `INFERRED`; its existence is not.** *(Delivery in the broad sense does occur — `CAP-01`'s bootstrap — but that is BC-6's, not C-10's bounded claim: §A1.3)* | **deferred until (A)** — ⛔ a category for a not-yet-established capability would be premature |
| **C-14** | 🟡 **CONTESTED** | see the provisional wording, §A1.4 | **deferred** — contested existence cannot carry a category |
| **C-19** | ✅ **YES** | ⭐ **and refinement 1 CHANGES this result:** composition demonstrably occurs — every governed artifact is a composed rendering — **and `A-7`/`GOV-HUMAN-01` is an ADOPTED rule about it.** `OBSERVED`. **The original gave C-19 only a category ("stewardship") and never asked whether the capability existed; it does.** | **stewardship / cross-cutting concern** — revalidated §A1.5 |

⛔ **Neither forbidden inference is used anywhere below:** *"not a bounded context → not a capability"* and *"not a bounded context → cross-context capability"*. **Every category claim is argued from positive properties.**

> ⭐ **What refinement 1 actually did to this analysis, stated plainly: it upgraded two verdicts and downgraded two.** **C-5 and C-19 gain an explicit `YES` on existence** they never had; **C-14's `NO` becomes `CONTESTED`** and **C-10's implicit `YES` becomes `NOT YET ESTABLISHED`**. ⛔ **Only one of the four moved in the direction that flatters the original analysis.**

---

## A1.2 · C-5 — reworked as four separate questions

| # | Question | Answer |
|---|---|---|
| **1** | Does **separation attestation** exist as a capability? | ✅ **YES** — §A1.1 |
| **2** | Is there an **authoritative assurance RESULT**? | 🟡 **produced once, ad hoc** (`SB-1`), never as a standing artifact. `OBSERVED` |
| **3** | Is there a **C-5-OWNED authoritative aggregate/state**? | 🔴 **NO** — no attestation record, assurance level, exception or dispute. `OBSERVED` |
| **4** | Is C-5 **bounded-context eligible**? | 🔴 **NO on current evidence** — because of (3) |

> ## ⭐ **The distinction `AMD2` requires, stated exactly:**
> **The absence of a C-5-owned authoritative state is evidence AGAINST a separate C-5 BOUNDED CONTEXT.**
> ⛔ **It is NOT evidence against the EXISTENCE of separation attestation as a capability.**
> **The original blurred these. This amendment separates them, and the existence verdict is `YES` while the bounded-context verdict is `NO` — both at once, with no contradiction.**

### Why **control-plane function** fits — argued positively

`INFERRED`, on four properties held *simultaneously*: ① it evaluates a condition **about an act**, never about domain data; ② it emits a **verdict**, not a value; ③ it may **withhold a claim** an act would otherwise make; ④ **it is defeated if the gated party issues it** (§A1.6). ⛔ **None of these is "it failed the bounded-context test."**

### The nine dimensions — with `ORGANIZATIONAL_INDEPENDENCE` modelled as `AMD2` requires

| Dimension | State | Class |
|---|---|---|
| role difference | ✅ **ESTABLISHED** — validated at `REGISTER`, immutable per `R8` | `OBSERVED` |
| identity difference | 🔴 **NOT_ESTABLISHED** — `executionContext` is free text | `OBSERVED` |
| assignment separation | ✅ **ESTABLISHED** — one assignment per lane, predecessor chain | `OBSERVED` |
| authority separation | ✅ **ESTABLISHED, mechanically** — `G-1`, `G-2` | `OBSERVED` |
| access separation | 🔴 **NOT_ESTABLISHED** — every lane has identical access | `OBSERVED` |
| artifact isolation | 🔴 **NOT_ESTABLISHED** — any lane may write any artifact | `OBSERVED` |
| evidence immutability | 🟡 **PARTIAL** — the transition log is append-only; documents are not | `OBSERVED` |
| ⭐ **organizational independence** | 🔴 **`NOT_ESTABLISHED`** *(not `NOT_APPLICABLE`)* | `OBSERVED` |
| assessment impartiality | 🟡 **PROCEDURAL ONLY** — honoured by disclosure; honoured twice in fact | `OBSERVED` |

**The four-state model, and the correction it forces:**

```
NOT_APPLICABLE        the dimension does not apply to this system at all
NOT_ESTABLISHED       it applies, and the system cannot currently establish it   ← C-5 here
ESTABLISHED           established by the platform's own mechanism
EXTERNALLY_ATTESTED   established by a party outside the platform
```

> ### 🔴 **The original wrote *"⚪ not applicable / undefined in a single-operator estate."* That is SUPERSEDED and it was the wrong state.**
> **`DECIDED` constraint, registered:** in a single-operator environment **organizational independence is not among the achievable separations, and it MUST NOT be represented as established.**
> ⛔ **`NOT_APPLICABLE` must never collapse into `PASS`.** *"Not applicable"* invites a reader to discount the row; **`NOT_ESTABLISHED` states that a real assurance dimension is missing and cannot presently be supplied.** ⭐ **That is a materially weaker assurance posture than the original implied, and the amendment states it as such.**

### What the platform can establish **mechanically** — separated from what it does

| | Can it be established mechanically? | Is it established today? |
|---|---|---|
| **process separation** | ✅ **yes** — demonstrated: write-class `tool_use` provenance identified an artifact's producer (`SB-1`) | 🟡 **once, by hand** — no standing mechanism |
| **independent execution** | 🟡 **partially** — provenance shows *which* process acted, not that it ran in an isolated environment | 🔴 no |
| **access separation** | 🔴 **not with current mechanisms** — nothing constrains a lane's access | 🔴 no |
| **artifact isolation** | 🔴 **not with current mechanisms** | 🔴 no |

**`EXTERNAL / CORROBORATIVE`:** the research supports *"access-enforced separation"* and *"evidence of actual assignment and authorization"*, challenges *"self-declaration as sufficient"*, and lists as unknown *"the acceptable fallback when independent personnel are unavailable"* — ⚠️ **the precise gap a single-operator estate has, and it offers no answer.**

**Ownership: `OPEN`.** ⛔ Unchanged, and `C-5`→Verification Engineer remains a **forbidden hypothesis**, not a finding.

---

## A1.3 · C-10 — the receipt model replaced with seven distinguished states

| State | Meaning | Status today | Owner today |
|---|---|---|---|
| `ContextPublished` | the governed knowledge exists and is published | ✅ **occurs** | **BC-1** (`CAP-03` **not built**) |
| `ContextSelected` | the subset **applicable to this act** is determined | 🔴 **does not occur** | 🔴 **nobody** |
| `ContextDelivered` | it is transmitted toward the session | 🟡 **occurs incidentally** — `CAP-01` bootstrap; and **leaking through BC-7's `tokenRef`** | BC-6 / BC-7 |
| `ContextAvailable` | it is present in the session's working context | 🟡 **occurs, unevidenced** | BC-6 |
| `ContextAcknowledged` | the session records that it holds a specific version | 🔴 **does not occur** | 🔴 **nobody** |
| `ContextApplied` | the session's act conforms to it | 🔴 **not observable** | 🔴 nobody |
| `ContextVerified` | a third party confirms application | 🔴 **does not occur** | 🔴 nobody |

> ⛔ **The three prohibitions, honoured explicitly:** **receipt ≠ application** · **receipt ≠ understanding** · **receipt ≠ compliance.** ⭐ **A receipt can only ever evidence `ContextAcknowledged`. `ContextApplied` and `ContextVerified` are different claims requiring different evidence, and no receipt can supply them.**

### `I-K1` reformulated — `PROPOSED`, verbatim as registered

> **"Before a governed act begins, the session must possess a valid receipt for the applicable context version required by the act."**

⚠️ **`PROPOSED` until PO/ARB.** **Three load-bearing terms are undefined and must not be quietly assumed:** *valid* · *applicable* · *required by the act*. **Each is a separate decision** — and *applicable* is `ContextSelected`, which **nobody owns**.

### `OQ-J` reformulated — **authoritative for WHICH claim?**

| # | Claim | What a receipt could authoritatively assert | Verdict |
|---|---|---|---|
| **1** | **delivery authority** | *"the platform sent version V toward this session"* | ✅ **a receipt can carry this** |
| **2** | **possession authority** | *"the session held version V at START"* | ✅ **a receipt can carry this** — this is `I-K1`'s actual scope |
| **3** | **applicability authority** | *"version V was the right context for this act"* | 🔴 **a receipt CANNOT carry this** — it is a **judgement**, and unowned |
| **4** | **execution authority** | *"the act was permitted to proceed"* | 🔴 **a receipt cannot carry this** — that is a **gate's** verdict (`C-14`-shaped) |
| **5** | **compliance authority** | *"the act conformed to version V"* | 🔴 **a receipt cannot carry this** — it needs `ContextVerified` |

> ⭐ **`INFERRED` — the reformulation's real result: a receipt is authoritative for at most TWO of the five claims.** **The original yes/no form would have let a single "authoritative receipt" decision silently cover all five** — including *applicability* (a judgement nobody owns) and *compliance* (which requires third-party verification). ⛔ **The five must be decided separately; three of them are not receipt-shaped at all.**

### Two exclusions, restated because they narrow the case

⛔ **Missing STARTs, off-record execution and the Amendment-1 provenance gap are NOT C-10 evidence** — they are **BC-7 lifecycle** concerns about whether an act was *recorded*, not whether knowledge *arrived*. *(Restated from the original; `AMD2` re-requires it.)*
⚠️ **And the absent-artifact recurrence (§A1.0) is recorded as evidence bearing on C-10 but is NOT claimed by it** — it may be a Governance-intake concern.

**Ownership: `OPEN` (`OQ-B`).** ⛔ `C-10`→Knowledge Engineer remains a forbidden hypothesis.

---

## A1.4 · C-14 — the categorical denial replaced

> ## **`PROPOSED`, verbatim as registered:**
> **"On current evidence, no platform-wide C-14 capability is established. A Tier-1 enforcement pattern exists within `CAP-09`, but whether that instance constitutes the same capability as C-14 remains OPEN."**

🔴 **The original's *"there is no distinct C-14 capability"* is WITHDRAWN.** *"No platform-wide capability is established"* and *"no capability exists"* are **different claims, and only the first is supported.** **Existence verdict: `CONTESTED`.**

**Both readings preserved:** **Reading 1** — no distinct platform-wide capability established; the remedy is a **policy/tier act**, not new architecture. **Reading 2** — **policy tiering itself** is the relevant capability / decision-right.

### The four sub-concerns, separated as `AMD2` requires

| Sub-concern | What it is | Owner today | Status |
|---|---|---|---|
| **policy tier assignment** | deciding a policy is Tier-1 (blocking) or Tier-2 (advisory) | 🔴 **no declared owner** — tiers exist in `registry.yaml` as data | ⭐ **the strongest candidate for a real decision-right** (Reading 2) |
| **coverage mapping** | which policy families a gate actually covers | 🔴 **nobody** — `EKS-02` is a coverage gap nobody tracks | `OPEN` |
| **enforcement execution** | performing the block/halt | ✅ **`CAP-09` / BC-3** — `AST-007`, Tier-1, `PRE_ACTION` | `OBSERVED`, live |
| **enforcement assurance** | evidencing that enforcement worked and was not bypassed | 🔴 **nobody** | `OPEN` — ⚠️ **and it is `C-5`-shaped, not `C-14`-shaped** |

⛔ **Not collapsed.** ⭐ **Splitting them shows the four have different owners and different statuses — which is itself why a single "C-14" verdict was unsafe.**

**`AST-007` / `CAP-09` status preserved as `CONTESTED`:** its `trace` is `capability: CAP-09`, `context: verification-evidence`, and `CAP-09` is *"Constitutional Observation & Escalation… on a trip: halt, escalate, never retry, never modify"* with write-paths *"deliberately closed."* ⇒ **whether it is a policy-enforcement instance or a constitutional-observation instance is `OQ-H`.** ⛔ **No capability existence is inferred from this single mechanism.**

**Ownership: `OPEN` (`OQ-H`).** ⛔ `C-14`→Governance Engineer remains a forbidden hypothesis — **and the live instance sits in BC-3.**

---

## A1.5 · C-19 — revalidated

| Attribute | Revalidated | Change from original |
|---|---|---|
| language | 🔴 borrowed | — |
| invariants | 🟡 one — `A-7` (**adopted**) | ⭐ **now read as EXISTENCE evidence, not just a weak invariant** |
| lifecycle | 🔴 the reporting act's | — |
| authority | 🔴 none | — |
| state | 🔴 none | — |
| **audience** | 🔴 no audience concept modelled | — |
| **channel** | 🔴 none | — |
| **acknowledgement** | 🔴 none — ⭐ **and it belongs to `C-10`** | sharpened |
| **retention** | 🔴 none | — |
| reason to change | 🔴 the **audience** — a different *kind* of reason | — |

> **`PROPOSED`, preserved: EXISTENCE `YES` · CATEGORY stewardship / cross-cutting expression concern.** The corrections above **did not change the category evidence**; they added the existence verdict the original omitted.

⛔ **`DECIDED` and restated: the adoption of the Communication Engineer role is NOT evidence of a Communication capability or bounded context.** The adoption act forbids the inference in terms.

⭐ **And `C-10`'s receipt semantics reduce the case further:** `ContextAcknowledged` is **`C-10`'s** state. **If it is ever adopted, the one attribute that could have given a Communication context authoritative state will already belong elsewhere.**

---

## A1.6 · `OQ-L` — refined, not decided

### Terms defined, because the invariant is unreadable without them

| Term | `PROPOSED` definition |
|---|---|
| **party** | the process, lane or person performing or answerable for the governed act |
| **governed control** | a check whose outcome conditions whether an act may proceed, be accepted, or be claimed conformant |
| **issue** | produce the control's verdict |
| **accept** | admit the verdict as sufficient |
| **finalize** | make the verdict unappealable within the process |
| **advisory result** | an outcome that informs but does not condition the act *(Tier-2)* |
| **authoritative verdict** | an outcome that does condition it *(Tier-1)* |
| **independent ratification** | a second party's confirmation, by a route the first party cannot write |

### The candidate invariant — `PROPOSED` / `OPEN`, verbatim

> **"A party subject to a governed control may not be the sole authority for issuing, accepting, or finalizing the control's verdict about its own compliance, separation, or conformance."**

**`OBSERVED` support — it is already implemented twice and never stated:** `G-1` (`COMPLETE` refuses any writer but `governance`/`human`) and `CAP-09`'s deliberately closed write-paths. **`OBSERVED` counter-pressure:** `INV-ATTR-2` exists precisely *because* the estate cannot enforce it generally.

### Candidate exceptions — **examined, not decided**

| Candidate exception | Analysis (`PROPOSED`) |
|---|---|
| **low-risk advisory checks** | ⚠️ plausible — an advisory result conditions nothing, so "sole authority" carries no assurance weight. **But it needs a risk test, and none exists** |
| **deterministic automated checks with independent execution** | ⚠️ **the strongest candidate** — `AST-007` is arguably this: deterministic, and it executes at `PRE_ACTION` where the gated party cannot intervene. ⚠️ **But "independent execution" is itself `NOT_ESTABLISHED` (§A1.2)** |
| **emergency procedures** | `OPEN` — no emergency concept exists in the estate |
| ⭐ **single-operator fallback** | ⚠️ **the one that cannot be waved through.** With organizational independence `NOT_ESTABLISHED`, **a blanket invariant may be unsatisfiable in this estate**, and an unsatisfiable invariant is worse than none. **This interacts directly with §A1.2 and must be decided with it** |
| **compensating controls** | `OPEN` — e.g. append-only provenance as a substitute for isolation. **Plausible and unassessed** |

⛔ **No exception is adopted, and the invariant is not adopted.**

---

## A1.7 · Dependencies — reclassified into three kinds

🔴 **The original's "build order is not arbitrary" is reclassified.** ⛔ **A dependency is not ownership and not containment.**

| Dependency | Kind | Statement |
|---|---|---|
| `C-10` → `C-14` | **SEMANTIC** | a gate cannot meaningfully enforce a policy the session never received. ⛔ **Does not make `C-10` an owner of `C-14`, nor place either inside the other** |
| `C-5` → provenance | **EVIDENCE** | attestation depends on provenance the acting party cannot write. ⛔ **Does not make provenance infrastructure the owner of `C-5`** |
| `C-19` → `C-10` | **SEMANTIC** | acknowledgement semantics belong to `C-10`; `C-19` would consume them |
| `C-14` coverage → policy tiering | **SEMANTIC** | coverage cannot be assessed before tiers are assigned |
| `C-5` mechanism → access/artifact isolation | **IMPLEMENTATION** | a standing attestation mechanism needs isolation that does not exist |
| `C-10` receipt → `ContextSelected` owner | **IMPLEMENTATION** | a receipt for an *applicable* version presupposes someone determines applicability |

> ⭐ **`INFERRED`: the three kinds have different consequences, which is why the single "build order" claim was unsafe.** **A semantic dependency constrains MEANING** (and must be resolved before a decision) · **an evidence dependency constrains what can be PROVEN** · **an implementation dependency constrains only SEQUENCE and may be deferred.** ⛔ **The original conflated all three into one ordering, which reads as a plan. It is not one.**

---

## A1.8 · Decision pack — grouped as required

### **GROUP A — CAPABILITY EXISTENCE** *(answer first; a category answer cannot substitute)*

| | Capability | Proposed existence verdict | The decision |
|---|---|---|---|
| **A-1** | **C-5** Separation attestation | ✅ **YES** | accept / reject / mark contested |
| **A-2** | **C-10** Knowledge distribution | 🟡 **NOT YET ESTABLISHED** | accept the status, or rule that the evidenced *need* suffices to establish it |
| **A-3** | **C-14** Policy enforcement | 🟡 **CONTESTED** | resolve `OQ-H`, or accept `CONTESTED` as the standing status |
| **A-4** | **C-19** Communication composition | ✅ **YES** | accept / reject |

### **GROUP B — SEMANTIC COMMITMENTS**

| | Decision | Note |
|---|---|---|
| **B-1** | **`OQ-J` — the receipt is authoritative for WHICH claim?** | ⭐ **five separate answers**: delivery · possession · applicability · execution · compliance. **A receipt can carry at most the first two** |
| **B-2** | **Missing-knowledge response: warn / halt / escalate** (`OQ-K`) | determines whether `C-10` is advisory or control-plane |
| **B-3** | **`OQ-L`** — adopt, reject or refine the shared invariant | ⚠️ **and the single-operator exception must be decided WITH it** |
| **B-4** | `I-K1`'s three undefined terms — *valid* · *applicable* · *required by the act* | each is a separate commitment |

### **GROUP C — CATEGORY / STEWARDSHIP / OWNERSHIP** *(only after Group A)*

| | Decision |
|---|---|
| **C-1** | **Category** for each capability accepted in Group A |
| **C-2** | **Steward** for each — §10's options; **all `OPEN`** |
| **C-3** | **Owner of authoritative state**, where any is accepted (C-5's attestation record · C-10's receipt) |
| **C-4** | ⭐ **Promotion criteria for a FUTURE bounded context** — e.g. C-5's §4.3 threshold. **Deciding the criteria now avoids deciding the context later by drift** |

### **GROUP D — SEQUENCING**

| | Kind | What it constrains | May it be deferred? |
|---|---|---|---|
| **D-1** | **SEMANTIC** — `C-10`→`C-14`; `C-19`→`C-10`; tiering→coverage | **meaning** — must be resolved before the dependent decision | 🔴 **no** |
| **D-2** | **EVIDENCE** — `C-5`→provenance | what can be **proven** | 🟡 only if the claim is weakened |
| **D-3** | **IMPLEMENTATION** — isolation mechanisms; `ContextSelected` owner | **sequence only** | ✅ **yes — safely deferrable** |
| **D-4** | **Safe deferrals** | `C-19` entirely · all agentization · all technology | ✅ **yes** |

---

## A1.9 · What changed, why, and what still stands

| Original statement | Disposition | Why |
|---|---|---|
| *"no distinct `C-14` capability"* | 🔴 **SUPERSEDED** → the provisional wording; existence `CONTESTED` | a category/ownership argument was doing an existence test's work |
| C-5/C-10/C-14/C-19 given **category only** | 🔴 **SUPERSEDED** → two separate verdicts each | refinement 1 |
| organizational independence *"not applicable / undefined"* | 🔴 **SUPERSEDED** → **`NOT_ESTABLISHED`** | `NOT_APPLICABLE` risks collapsing into `PASS`; the dimension applies and cannot be established here |
| *"is the receipt authoritative state?"* | 🔴 **REFORMULATED** → *authoritative for which claim?*, five parts | one authority claim covered five assertions |
| *"build order is not arbitrary"* | 🔴 **RECLASSIFIED** → semantic / evidence / implementation | a dependency is not ownership, and the three differ in consequence |
| C-19 = stewardship | ✅ **STANDS**, now with existence `YES` | the category evidence did not change |
| the four as **one control loop**, four unrelated clocks | ✅ **STANDS** | unaffected |
| C-10's boundary = **applicability + receipt** | ✅ **STANDS**, sharpened by the seven states | unaffected |
| C-5's nine dimensions at three maturity levels | ✅ **STANDS**, with one state corrected | unaffected |
| §8.3's shared-invariant candidate | ✅ **STANDS**, now defined and exception-tested (§A1.6) | unaffected |
| the four forbidden pairings all `OPEN` | ✅ **STANDS** | unaffected |
| external research as `EXTERNAL / CORROBORATIVE`, untracked | ✅ **STANDS** | unaffected |

⛔ **No original finding was deleted, and no original wording was rewritten.**

---

## A1.10 · Non-decisions

⛔ **No ownership assigned · no bounded context created · no capability formally created · no agent · no service · no technology · no role redefined · no six-role reopening · no implementation · BC-7 untouched · role definitions untouched · the OQ register untouched · Track 1 untouched.**

**`OQ-J` is reformulated and `OQ-K`/`OQ-L` remain proposed additions — ⛔ this lane registers none of them.**

> ## **No capability existence, category, ownership, stewardship, bounded context, or implementation decision is made by this Architecture lane. These remain PO/ARB decisions.**

**AMENDMENT 1 DELIVERED · STOPPING.** ⛔ **No self-verification · no self-acceptance · assignment NOT closed (`G-1`).** **Next actor: Independent Verification.**

**Amendment traceability:** `G-KOS-AIP04-DECISION-PREP-AMD2` (registered `39f14f15`) · `AMD1` · the parent grant · lane seq 18 (ACTIVE, mutation owner) · the original analysis `ba74dbdd` retained · **the source review's absence independently re-verified (full-repository search, tracked and untracked, untruncated, 2026-08-19)** · six-role adoption registration · Verification #3 (`SB-1` cleared; §7.1's declined `OQ-A` answer) · `INV-ATTR-1`/`INV-ATTR-2` (adopted `P-1`–`P-6`, 2026-08-15) · `A-7`/`GOV-HUMAN-01` (adopted) · `registry.yaml` `AST-005/006/007/014` · `CAP-09` (`Phase-02.5`) · `G-1`/`G-2`/`R8` · `EKS-01`/`EKS-02` · **Class C: `perpleixity_research_on_roles.md` (untracked) — `EXTERNAL / CORROBORATIVE` only.**
