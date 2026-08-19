# `KOS-AIP04-DISCOVERY-001` — **Capability Architecture Analysis**

> ## **Ownership and architectural-category decisions remain with PO/ARB.**

**Status: 🟡 PROPOSAL / DECISION PREPARATION ONLY. It selects no category and assigns no owner.**
**Assignment:** `S4-architecture-aip04-decision-prep` (seq 15 REGISTER · 17 HANDOFF · **18 START**) · **Grant:** `G-KOS-AIP04-DECISION-PREP` **+ `-AMD1`** · **Role:** Architecture Engineer
**Producing process, self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`): **`claude-code-session:5e1dd9ee`** — the original discovery producer and author of Amendment 1 and Correction #2. **Distinct from all three verifiers.** ⚠️ **Disclosed: this lane analyses four capabilities of its own construction, and twice corrected their statuses.** `R-34`/`P-2` binds — it must not verify or accept this analysis.
**Next actor: PO/ARB.**

---

## 1 · Executive conclusion

**`PROPOSED` — none of the four is recommended as a new bounded context, and one is proposed not to be a distinct capability at all.**

| | Capability | **Proposed category** | Bounded context? |
|---|---|---|---|
| **C-5** | Separation attestation | **cross-context control-plane assurance capability** | 🔴 **No** — owns no attestation record, assurance level, exception or dispute lifecycle |
| **C-10** | Knowledge distribution | **cross-layer delivery capability** whose *smallest coherent boundary* is **applicability + receipt** | 🔴 **No** — but the *receipt* is authoritative state nobody holds ⇒ the one live threshold question |
| **C-14** | Policy enforcement | ⭐ **PROPOSED: NOT a distinct capability as posited** — a **coverage gap in an existing, owned enforcement pattern** | 🔴 **No** |
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
| **organizational independence** | ⚪ **not applicable / undefined** in a single-operator estate | `OPEN` |
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

> ## ⭐ **`PROPOSED` — the answer is NO: on current evidence there is no distinct `C-14` capability.**
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

**`INFERRED` consequences the PO/ARB should weigh:** **`C-14` cannot enforce a policy the session never received** ⇒ `C-14` **depends on** `C-10`. **`C-5` attests acts that `C-14` gates** ⇒ they share an evidence substrate (provenance). **`C-19` is downstream of all three** and adds no state. ⇒ ⭐ **build order is not arbitrary: `C-10` before `C-14` coverage; provenance before `C-5`; `C-19` last or never.**

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
| **3** | **`OQ-J` — is `C-10`'s receipt authoritative state?** | ⭐ the only decision here that flips a category |
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
