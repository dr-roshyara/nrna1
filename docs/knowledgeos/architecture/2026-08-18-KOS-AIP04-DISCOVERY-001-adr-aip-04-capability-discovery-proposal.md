# ADR-AIP-04 — **Capability Discovery Proposal**

**Status: 🟡 PROPOSAL. It decides nothing. Every classification below is explicit and no proposal is promoted to a decision.**
**Work item:** `KOS-AIP04-DISCOVERY-001` · **Track 2** — EKS / Knowledge Engineering Platform Evolution
**Lane:** `S4-architecture-aip04-capability-discovery` (seq 1 REGISTER · 2 HANDOFF · 3 START) · **Grant:** `G-KOS-AIP04-DISCOVERY` · declared role set `governance · architecture · verification` — **implementation excluded mechanically**
**Placement derived, not chosen:** `doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0); `architecture/` matches the artifact class.
**Next actor:** **independent verification of this discovery, then PO/ARB decision.** `R-34`/`P-2`: this lane neither verifies nor accepts its own proposal.

> **Section mapping.** This document follows the **commission's seventeen sections** (the governed deliverable spec). The START act's fifteen DDD outputs map onto them without contradiction: inventory §3 · responsibility matrix §4 · ownership map §5 · UL map §6 · invariant map §7 · reason-to-change §8 · role/stewardship §10 · communication §13 · knowledge §9/§13 · orchestration §12 · candidate contexts §14 · human/agent/service §11 · **shared-boundary risks §15.1** · open questions §15 · recommended decision set §16–§17.

---

## ⚠️ 0 · Disclosure — the drafter's own role history is evidence inside this discovery's subject

**This process has previously occupied, in sequence within one process:** the **verification** role (`KOS-CONTRACT-NEUTRALITY-001` breadth verification) · the **architecture-review** role (`EM-IMPL-002` ADR final gate) · the **architecture-drafting** role (`KOS-CONTRACT-NEUTRALITY-001` semantic clarification) · the **domain** role (`EM-DOM-001` Phase 2A).

**This is disclosed for two distinct reasons, which must not be conflated:**

1. **As a CONFLICT the PO/ARB may weigh** — a discovery about the role model, drafted by a process that has occupied four roles, may rationalise its own history. ⛔ It must be independently verified.
2. **As FIRST-HAND OBSERVED EVIDENCE inside the subject matter** — `F-9` below rests on it. **A discovery into role separation that discarded the clearest available instance of role fluidity would be weaker, not more neutral.** It is used as evidence, never as a credential, and it is marked `OBSERVED (first-hand, self-declared)` wherever used.

---

## 1 · Context

**BC-7 Governed Session Orchestration was recognized** (ADR-AIP-03, Option A, PO/ARB 2026-08-17) as a **Supporting Subdomain**, `CAP-14` assigned. Its **consequence decision (b) expressly deferred role-model ownership to ADR-AIP-04 discovery.**

**The deferred question, in its original form (Stage-2 `OQ-5`), is precise and is this discovery's centre:**

> **Is the role model (the §9 / §7 "AI Responsibility Model") BC-7's OWN language, or a governance-published language BC-7 CONSUMES?**

**Six responsibilities have been proposed as a role model** — Governance · Communication · Knowledge · Architecture · Implementation · Independent Verification Engineer. **The commission classifies them as HYPOTHESES and forbids beginning from *"how do we create six agents?"*** This discovery honours that ordering strictly:

```
capability → ownership → bounded context / stewardship → human role → agent → platform service → technology
```

⛔ **This discovery decides no role, creates no agent, selects no technology, and does not reopen BC-7.**

## 2 · Evidence base

| Source | Used for |
|---|---|
| `.claude/scripts/workflow-state.php` (`AST-015`) — read in full | the executable lifecycle: `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`, the fold, the grant writer rule, refusal semantics |
| 15 work-item records in `.claude/runtime/workflow/` | grants, assignments, declared role sets, mutation ownership, human acts |
| `ADR-AIP-01` (Baseline v1.0) · `ADR-AIP-02` (Product Primacy) · `ADR-AIP-03`/BC-7 | the frozen context model, `CAP` identity rule, the deferral |
| `Phase-02-Domain-Model.md` §7 **AI Responsibility Matrix** | the *actual* accepted authority model — see `F-2` |
| `KOS-ARCH-BASELINE-002` capability map v2 | the `CAP ↔ BC` assignment table, live/not-built status |
| `EKS-01 … EKS-04` | four independently recorded capability failures — the strongest evidence in this discovery |
| `.claude/platform/` (`registry.yaml`, `OPERATING_INSTRUCTIONS.md`), `AST-001…016` | the declared executable surface |
| This estate's own governance record 2026-08-16…18 | separation practice, off-record execution, records-integrity findings |

⛔ **Per the commission: the current platform structure is NOT treated as proof that its boundaries are correct.**

## 3 · Capability inventory

**Derived from evidence of what the operating system actually does — not from the six roles.** Status is `OBSERVED` unless marked.

| # | Capability | Evidence of existence | Declared owner today | Status |
|---|---|---|---|---|
| **C-1** | **Authority registration** (grants from recorded human acts) | `grant` command; `humanActRef` mandatory; refusal *"the record never manufactures authority"* | BC-7 / `CAP-14` | **live** |
| **C-2** | **Lifecycle transition & mutation ownership** | the fold; `I-1` single owner; `I-2` HANDOFF ∧ human START; `I-3` sticky STOPPED | BC-7 / `CAP-14` | **live** |
| **C-3** | **Lifecycle interpretation** | `AST-015` holds *"its own interpretation authority"* (ADR-AIP-03 §1) | BC-7 | **live, contested — `EKS-04`** |
| **C-4** | **Assignment & next-actor routing** | `REGISTER` role + `executionContext`; "next actor" sections in every governed artifact | BC-7 (mechanism) / ⚠️ **unowned (composition)** | **partial** |
| **C-5** | **Separation attestation** | `INV-ATTR-2`/`G-2`: separation is **Declared, NOT attestable** | 🔴 **NO OWNER** | **absent** — ✅ *status correct and unchanged*; ⚠️ **an Amendment-1 pointer was MISPLACED on this row and is removed by Correction #2 §C2.2 (finding `W-2`)** |
| **C-6** | **Acceptance / closure** | `COMPLETE` refuses any `recordedBy` but `governance`/`human` (`G-1`); `R-34` | BC-7 (mechanism) + PO/ARB (authority) | **live** |
| **C-7** | **Knowledge curation & promotion** (`generated → authoritative`) | `CAP-03`; Knowledge-Constitution; `authority:` fields | BC-1 Knowledge Governance | 🔴 **NOT BUILT** — constitution + convention only |
| **C-8** | **Evidence identity & provenance** | 39 `tokenRef`s hard-code paths (`EKS-03`) ⚠️ **[count CORRECTED → 40 · Amendment 1 §A1.3]**; *"historical location is evidence"* | ⚠️ **weak / implicit** | **fragile** |
| **C-9** | **Decision lineage & supersession** | ADR series; `AIP-11` supersede-never-in-place; decision registers | BC-1 (`INFERRED`) | **convention only** |
| **C-10** | **Knowledge distribution to the executing session** | `EKS-01`: *"Recording a rule is not sufficient"* | 🔴 **NO OWNER** | **absent** |
| **C-11** | **Context bootstrap & rehydration** | `CAP-01`, `AST-002`, SessionStart hook | BC-6 Session Continuity | **live** |
| **C-12** | **Session recording & archival** | `CAP-02`, `AST-003/004` | BC-6 | **live** |
| **C-13** | **Policy derivation** | `doc-placement.php` — exit 0 for every case | BC-1/BC-2 (`INFERRED`) | **live** |
| **C-14** | **Policy enforcement** | `EKS-02`: *"the rule exists, but the workflow does not make it hard enough to violate"* | 🔴 **NO OWNER** | **absent** 🔴 **[FALSIFIED — WITHDRAWN. Corrected status: `CONTESTED`; neither "absent" nor "live" is established. See Correction #2 §C2.2 · original wording retained]** |
| **C-15** | **Discipline gating & tripwires** | `CAP-06`, `AST-005/006/014` | BC-2 Implementation Guidance | **live, all Tier-2 non-blocking** |
| **C-16** | **Strategic & tactical modelling / ADR drafting** | `CAP-10/12`, frozen templates | BC-5 Design & Decision Support | **by reference** |
| **C-17** | **Conformance checking & verdicts** | `CAP-07/08/09`, `AST-007/010` | BC-3 Verification & Evidence | **partial** |
| **C-18** | **Adversarial review** | `CAP-11` — *"performed by role model + human acts"* | BC-4 Adversarial Review Support | 🔴 **NOT BUILT as component** |
| **C-19** | **Communication composition** (briefings · dossiers · decision surfaces · state explanations) | this estate's own artifact classes; `GOV-HUMAN-01`/`A-7` | 🔴 **NO OWNER** | **practised, undeclared** |
| **C-20** | **Platform self-governance / registry** | `CAP-13`, `registry.yaml` (`AST-009`) | BC-5 — **placement challenged** (`M-2`/ADR-C2) | **live as data** |

> ⚠️ **[HEADLINE CORRECTED by Amendment 1 §A1.8 — it is THREE unowned (`C-5`, `C-10`, `C-19`) plus `C-14` live-but-unmodelled with a contested home. Original wording retained below.]**
>
> ### ⭐ **Four capabilities have NO OWNER: `C-5` separation attestation · `C-10` knowledge distribution · `C-14` policy enforcement · `C-19` communication composition.** `INFERRED`: **these four, not the six roles, are the substance of ADR-AIP-04.**

## 4 · Capability boundaries — the eleven findings

| | Finding | Class |
|---|---|---|
| **F-1** | **The accepted model contains no Communication capability and no Communication context.** `CAP-01…14` and `BC-1…BC-7` — none is communication, while communication artifacts are pervasive | **OBSERVED** |
| **F-2** | ⭐ **The frozen "AI Responsibility Matrix" is NOT a role model.** It is an **activity × decision-right** matrix (*AI decides · AI recommends · Human approval · ARB-only · Sponsor-only*) and **contains no actors at all** ⚠️ **[CORRECTED → it names decision AUTHORITIES but no engineering roles · Amendment 1 §A1.4]** | **OBSERVED** |
| **F-2a** | Its own stated pattern is **capability-shaped, not role-shaped**: *"the AI **decides** wherever the outcome is a derived fact of an executable check; it **recommends** wherever the outcome is a judgment; humans hold every gate where authority, value, or entrenchment changes."* ⇒ decision rights follow **the nature of the activity**, never the identity of the actor | **OBSERVED** |
| **F-3** | ⭐ **The six "Engineer" roles appear nowhere in the declared architecture, the capability map, or `registry.yaml`.** 🔴 **[FALSIFIED — WITHDRAWN. See Amendment 1 §A1.1; original wording retained as historical.]** Where such titles occur they are **commissioning personas in document headers** (*"Principal Knowledge Engineer / Strategic DDD Architect"*) — an addressing convention for the human commissioner | **OBSERVED** |
| **F-3a** | ⇒ The six-role model is a **persona vocabulary mistaken for a platform role model** 🔴 **[RESTATED on corrected evidence · Amendment 1 §A1.1]** | **INFERRED** |
| **F-4** | **Role separation is `Declared`, not attestable** (`INV-ATTR-2`/`G-2`). No mechanism can establish who performed an act; assurance rests entirely on **where the human starts the session** | **OBSERVED** |
| **F-4a** | ⇒ **A role model cannot deliver the assurance it appears to promise while `C-5` is absent.** Naming roles more precisely does not make separation checkable | **INFERRED** |
| **F-5** | ⭐ **`EKS-01`: the HANDOFF token's `tokenRef` acted as the de-facto knowledge channel and taught a lane the superseded path.** A lane was *"taught the old path by its workflow record's `tokenRef`"* | **OBSERVED** |
| **F-5a** | ⇒ **BC-7's handoff token is performing knowledge distribution it does not own** — a boundary leak, and the single strongest piece of evidence for a distinct capability. **The missing capability is `C-10` Knowledge Distribution, not "Communication" in the ordinary sense** | **INFERRED** |
| **F-6** | **`EKS-04`: *"handed to the next role"* ≠ *"session formally closed"*, and the ambiguity delayed independent verification** even though the producer had finished | **OBSERVED** |
| **F-7** | **`EKS-02`: three capabilities are routinely conflated — policy authorship · policy derivation (`C-13`, live) · policy enforcement (`C-14`, absent).** Only the third is missing, and **no role fixes it; a gate does** | **OBSERVED** |
| **F-8** | **`EKS-03`: the evidence chain is coupled to physical location** — 39 `tokenRef`s hard-code paths ⇒ **provenance is not location-independent** | **OBSERVED** |
| **F-9** | **One process occupied verification → architecture-review → architecture-drafting → domain roles in sequence, and in each lane recorded its own START** — a governance act — because no separate governance process was present | **OBSERVED (first-hand, self-declared)** |
| **F-9a** | ⇒ **"Governance" as practised is not an actor.** Its *recording* duties are discharged by whichever process holds the lane. **But its *acceptance* duty IS strictly separated** — `COMPLETE` mechanically refuses any writer but `governance`/`human`, and `R-34` bars self-acceptance. ⇒ **Governance = a cross-cutting capability set with ONE strictly separated duty**, not a role | **INFERRED** |
| **F-10** | **Verification spans two contexts** — `C-17` in BC-3 and `C-18` in BC-4 — and **whether `CAP-11`'s home moves is ADR-C7, still OPEN** | **OBSERVED** |
| **F-10a** | ⇒ A single "Independent Verification Engineer" role would **pre-empt an open architecture decision**. ⛔ ADR-C7 is not answered here | **INFERRED** |
| **F-11** | **The platform owns Implementation *Guidance* (BC-2), not Implementation.** Product implementation is the Election core domain (`ADR-AIP-02` Product Primacy, `AIP-14`) | **OBSERVED** |
| **F-11a** | ⇒ An "Implementation Engineer" is a **product** responsibility. Importing it into the platform role model would pull core-domain responsibility into a Supporting Subdomain — the exact drift `ADR-AIP-03` §3 warns against | **INFERRED** |

### 4.1 Capability responsibility matrix — the bounded-context test applied

**Test: own language · own invariants · own responsibility · distinct reason to change · external relationship. ⛔ A concept is not a context because it exists.**

| Capability | Own language | Own invariants | Distinct reason to change | **Verdict** |
|---|:---:|:---:|:---:|---|
| `C-1` `C-2` `C-3` `C-6` orchestration core | ✅ | ✅ `I-1`…`I-3` | ✅ governance-process change | **BC-7 — settled, not reopened** |
| `C-11` `C-12` continuity | ✅ | ⚠️ weak | ✅ session-runtime change | **BC-6 — settled** |
| `C-7` `C-8` `C-9` knowledge | ✅ | ✅ promotion · supersession · provenance | ✅ knowledge-lifecycle change | **BC-1 — real owner, capability NOT BUILT** |
| `C-16` modelling | ✅ | ⚠️ | ✅ modelling-method change | **BC-5 — settled** |
| `C-17` `C-18` verification | ✅ | ✅ producer ≠ verifier | ✅ assurance-method change | **BC-3 + BC-4 — split; ADR-C7 OPEN** |
| `C-13` `C-15` guidance | ✅ | ⚠️ Tier-2 non-blocking | ✅ tripwire change | **BC-2 — settled** |
| **`C-5` separation attestation** | ✅ *attestation · declared vs observed · execution context* | ✅ *an act's performer is attestable, not merely declared* | ✅ **assurance-integrity change — distinct from every context above** | 🟡 **PROPOSED: a genuine capability. Context vs stewardship OPEN (`OQ-A`)** |
| **`C-10` knowledge distribution** | ✅ *current rule · discovery · applicable-at-start* | ✅ *the executing session holds the CURRENT rule* | ✅ **distribution-mechanism change — not knowledge-lifecycle change** | 🟡 **PROPOSED: a genuine capability, and the strongest evidenced gap (`F-5`)** |
| **`C-14` policy enforcement** | ⚠️ shares BC-2's language | ✅ *a derivable rule is mechanically hard to violate* | ⚠️ changes with the tripwire model | 🟡 **PROPOSED: a capability inside BC-2, NOT a context** |
| **`C-19` communication composition** | ⚠️ **borrowed** — it speaks every other context's language | ⚠️ one candidate only (`A-7` decision-readiness) | ⚠️ changes when the *audience* changes, not when a model changes | 🔴 **PROPOSED: FAILS the context test — a stewardship (§13)** |
| `C-4` routing | ⚠️ mechanism BC-7's; composition borrowed | ⚠️ | ⚠️ | 🟡 **split — mechanism BC-7, composition a stewardship** |
| `C-20` self-governance | ⚠️ | ⚠️ | ⚠️ | **BC-5, placement challenged — ADR-C2 OPEN, untouched** |

## 5 · Ownership analysis · ownership map

```
BC-7 Governed Session Orchestration  (Supporting)   CAP-14
    OWNS: authority registration · lifecycle transition · mutation ownership
          · lifecycle interpretation · the closure MECHANISM
    DOES NOT OWN: what a grant authorizes (PO/ARB) · knowledge content
          · ⚠️ knowledge distribution — but currently LEAKS it via tokenRef  (F-5a)

BC-1 Knowledge Governance                            CAP-03  🔴 NOT BUILT
    OWNS: durable knowledge · promotion · provenance · supersession · lineage
    ⚠️ owns C-8/C-9 by INFERENCE only; provenance is path-coupled            (F-8)

BC-3 Verification & Evidence      CAP-07/08/09   │  BC-4 Adversarial Review   CAP-11
    OWNS: checks · verdicts · traceability       │      OWNS: challenge, findings
    ⚠️ the verification capability is SPLIT; ADR-C7 OPEN — not answered here (F-10)

BC-5 Design & Decision Support    CAP-10/12/13   │  BC-2 Implementation Guidance CAP-05/06
    OWNS: models · ADR drafts · constraints      │      OWNS: derivation · tripwires
    ⛔ must not become verifier or implementation authority

BC-6 Session Continuity           CAP-01/02
    OWNS: bootstrap · rehydration · archival

UNOWNED ─────────────────────────────────────────────────────────────────────
    C-5  separation attestation      C-10 knowledge distribution
    C-14 policy enforcement          C-19 communication composition
```

**Governance is deliberately NOT drawn as the owner of engineering knowledge.** `F-9a`: it owns **authority, lifecycle, transitions, routing mechanism, separation RULES and acceptance** — and **not** the content of architecture, knowledge or verification. ⛔ *Controlling the lifecycle is not owning what flows through it.*

## 6 · Ubiquitous-language map

| Context | Terms it OWNS | Terms it must only CONSUME |
|---|---|---|
| **BC-7** | work item · session assignment · role *(lane role)* · transition · grant · handoff · human act · mutation ownership · execution context | *authority* content · *knowledge* · *finding* · *model* |
| **BC-1** | knowledge card · authority (`generated`/`authoritative`) · provenance · supersession · lineage · evidence identity | *grant* · *transition* |
| **BC-3 / BC-4** | check · verdict · finding · challenge · assurance · producer ≠ verifier | *decision* · *acceptance* |
| **BC-5** | bounded context · aggregate · invariant · ADR proposal · constraint | *verdict* · *acceptance* |
| **BC-2** | tripwire · gate · guidance · derivation · plan · progress | *authority* |
| **BC-6** | session · context payload · rehydration · archive | *grant* |
| 🔴 **collision** | **"role"** — BC-7's *lane role* vs the personas' *engineer role* vs `§7`'s *decision right*. **Three meanings, one word** | — |
| 🔴 **collision** | **"review"** — flagged as the exemplary ambiguity by `OI-1` in `ADR-AIP-01` and **still open** | — |

> ⭐ `INFERRED`: **the word "role" is already overloaded three ways, and adopting six named "Engineer" roles would add a fourth.** `OI-1` shows the estate already knows what an unresolved term costs.

## 7 · Invariant map

| # | Invariant | Owner | Enforcement |
|---|---|---|---|
| `I-1` | exactly one mutation owner | BC-7 | ✅ **mechanical** — the fold refuses |
| `I-2` | HANDOFF **∧** human START — neither alone yields ACTIVE | BC-7 | ✅ **mechanical**, both directions |
| `I-3` | STOPPED is sticky; only CONTINUATION exits | BC-7 | ✅ **mechanical** |
| `I-4` | registry ≠ authority — never merged | BC-7/BC-5 | ⚠️ declared |
| `G-1` | closure is a governance act; no self-completion | BC-7 | ✅ **mechanical** (`recordedBy` restricted) |
| `G-2` | the Authority State has exactly one writer | BC-7 | ✅ **mechanical** |
| `R-34` | producer ≠ acceptor; engineering never accepts its own work | Governance | ⚠️ **declared only** |
| `INV-ATTR-2` | separation is **declared, not attestable** | 🔴 **unowned** | 🔴 **none — this is `C-5`** |
| `AIP-11` | frozen artifacts change only by supersession | BC-1 | ⚠️ convention |
| `AIP-14` | Product Primacy — the platform is never the core domain | Architecture | ⚠️ declared |
| **PROPOSED `I-K1`** | *the executing session holds the CURRENT governed rule at START* | 🟡 `C-10` | 🔴 **absent — `EKS-01`** |
| **PROPOSED `I-K2`** | *evidence identity is independent of storage location* | 🟡 `C-8` | 🔴 **absent — `EKS-03`** |
| **PROPOSED `I-P1`** | *a mechanically derivable rule is mechanically hard to violate* | 🟡 `C-14` | 🔴 **absent — `EKS-02`** |

> ⭐ **The pattern is sharp and it is the discovery's core structural result: every invariant BC-7 owns is MECHANICALLY enforced; almost every invariant outside BC-7 is DECLARED ONLY.** `INFERRED`: **the platform's assurance is strong exactly where it is executable and weak exactly where it is prose — and a role model is prose.**

## 8 · Reasons to change

| Capability | Changes when… | Distinct from BC-7? |
|---|---|---|
| `C-1`…`C-3`, `C-6` | the **governance process** changes | — (is BC-7) |
| `C-7`…`C-9` | the **knowledge lifecycle** changes (promotion, supersession) | ✅ yes |
| `C-10` | the **distribution mechanism** changes — hooks, payload, discovery | ✅ **yes, and also distinct from `C-7`** |
| `C-14` | the **enforcement posture** changes (Tier-2 → blocking) | ✅ yes, ⚠️ but couples to BC-2's tripwire model |
| `C-17`/`C-18` | the **assurance method** changes | ✅ yes |
| `C-5` | the **attestation substrate** changes (what the runtime can prove) | ✅ **yes — changes with nothing else on this list** |
| `C-19` | the **AUDIENCE** changes — not when any model changes | 🔴 **a different KIND of reason ⇒ fails the context test** |

## 9 · Knowledge capability analysis

**BC-1 Knowledge Governance owns `C-7` `C-8` `C-9` — and `CAP-03` is NOT BUILT** (`OBSERVED`): constitution and convention only, `CMP-003 deferred v0.0`.

| Sub-capability | State |
|---|---|
| durable knowledge · ADR indexing · baseline lineage | **convention only** |
| **evidence identity** | 🔴 **path-coupled** (`F-8`) — `PROPOSED I-K2` |
| provenance · supersession · decision lineage | convention (`AIP-11`), unenforced |
| conformance evidence | ⚠️ **shared with BC-3 — and it touches Track 1 (§15.1)** |
| historical truth | ✅ strong principle: *"historical location is evidence; canonical future location is architecture"* |

⛔ **The EKP/PKS boundary is NOT decided here** — the evidence does not require it, and the commission forbids deciding it absent that requirement. **`OQ-D`.**

> ⭐ `INFERRED`: **BC-1's problem is not ownership — it is that a real owner has an unbuilt capability.** No role creation addresses that; **building `CAP-03` does.** A "Knowledge Engineer" role over an unbuilt capability would produce an actor with authority over conventions and no mechanism.

## 10 · Role / stewardship analysis — the six hypotheses

**Each is classified on the dimensions the commission names. ⛔ No one-to-one `capability = role` mapping is assumed.**

| Hypothesis | Best understood as | Evidence |
|---|---|---|
| **Governance Engineer** | 🔴 **NOT a role.** A **cross-cutting capability set** (`C-1`…`C-4`) whose recording duties are discharged by whichever lane holds the work, **plus exactly ONE strictly separated duty — acceptance/closure** | `F-9`/`F-9a`; `G-1` mechanical; `R-34` |
| **Communication Engineer** | 🔴 **NOT a bounded context.** **Two different things conflated:** `C-10` **knowledge distribution** — a genuine, evidenced capability — and `C-19` **communication composition** — a **stewardship** | `F-1`, `F-5`, `F-5a`, §7 change-reason test |
| **Knowledge Engineer** | 🟡 **A real capability cluster with a real owner (BC-1) that is NOT BUILT.** The gap is **capability realization**, not role creation | `C-7` status; §9 |
| **Architecture Engineer** | ✅ **Already modelled — as a BC-7 LANE ROLE** (`architecture` is in the declared role set and is mechanically validated at REGISTER). ⛔ Must not become verifier or implementation authority | `AST-015` role validation; §12 |
| **Implementation Engineer** | 🔴 **Not a platform capability.** A **product** responsibility; the platform owns *guidance* only. *(This lane's own role set excludes `implementation` mechanically)* | `F-11`/`F-11a`; `ADR-AIP-02` |
| **Independent Verification Engineer** | 🟡 **A lane role that already exists** — but the load-bearing property is the **invariant** `producer ≠ verifier`, which is **unattestable** (`C-5`). ⚠️ Its capability spans BC-3 + BC-4 with **ADR-C7 OPEN** | `F-4`, `F-10`/`F-10a` |

> ## ⭐ **The headline PROPOSED finding: the six-role model answers a question the estate has already answered elsewhere, and leaves the four unowned capabilities untouched.**
>
> **BC-7 already has an executable role model** — the declared role set per work item, validated mechanically at `REGISTER`, immutable per assignment (`R8`), and bound to authority through grants. **These lane roles are the estate's real role model.** The six "Engineers" are **personas** (`F-3`). And the **authority** dimension is already owned by `§7`'s activity × decision-right matrix (`F-2`), which contains no actors at all.
>
> ⇒ **Adopting six named roles would add a fourth meaning of "role" (§6), duplicate an existing authority model, and still leave `C-5`, `C-10`, `C-14`, `C-19` unowned.**

## 11 · Human / agent / service consequences — **ordering enforced**

⛔ **Evaluated only after capability and ownership. No agentization is recommended for any capability whose existence or owner is not established.**

| Capability | Human authority | Agent | Shared platform service |
|---|---|---|---|
| `C-1` authority registration | 🔴 **HUMAN ONLY** — a grant registers a *recorded human act*; the record never manufactures authority | ⛔ never | ✅ the recording mechanism (exists) |
| `C-2`/`C-3` transitions, interpretation | human act required for authority transitions (`I-2`) | ⛔ | ✅ exists (`AST-015`) |
| `C-6` acceptance | 🔴 **HUMAN / PO-ARB ONLY** (`R-34`) | ⛔ never | ✅ mechanism exists |
| `C-5` separation attestation | ⚠️ human START is currently the *only* assurance | ⛔ **an agent cannot attest its own separation** | 🟡 **PROPOSED: a platform service — the only place it can live** |
| `C-10` knowledge distribution | — | ⚠️ premature | 🟡 **PROPOSED: a platform service**, plausibly extending `CAP-01`/BC-6's bootstrap |
| `C-14` policy enforcement | — | ⛔ | 🟡 **PROPOSED: a gate (service), inside BC-2** |
| `C-19` communication composition | ✅ audience is human; `A-7` binds at decision points | ⚠️ possible **later**, and only as a stewardship duty of an existing lane | ⚠️ not before `C-10` |
| `C-7` knowledge curation | promotion `generated → authoritative` needs owner approval (`§7`) | ⛔ **not before `CAP-03` is built** | ✅ build `CAP-03` first |
| `C-17`/`C-18` verification | verdicts are recorded; certification is ARB-only | ⚠️ **blocked by ADR-C7 and `C-5`** | — |

> ⭐ **`C-5` is the one capability whose nature forbids agentization on principle: a process cannot attest its own separation.** `F-4` + `F-9` are the evidence, and `F-9` is first-hand. **This is the strongest single argument for capability-before-agent ordering in this discovery.**

## 12 · Session-orchestration relationship — what BC-7's accepted model implies

**BC-7 is an accepted input and is not reopened.** What its model already settles:

| BC-7 element | Implication for ADR-AIP-04 |
|---|---|
| declared **role set per work item**, validated at `REGISTER` | **an executable lane-role model already exists** — ADR-AIP-04 need not create one |
| **`R8`** role immutable per assignment | a role change is a **new assignment**, never a re-labelling ⇒ roles are *assignment-scoped*, not person-scoped |
| **grants** carry scope; `authorized` answers by scope | authority attaches to **the grant**, not to the role ⇒ a role is not an authority |
| **`I-2`** HANDOFF ∧ human START | ⇒ **`EKS-04` is a lifecycle-INTERPRETATION problem (`C-3`), not a role problem** |
| **`I-1`** single mutation owner | concurrency is governed by ownership, not by role count |
| `executionContext` free text | 🔴 **this is where separation is *declared* — and why `C-5` is absent** |
| recorded orchestration history (15 records) | the audit substrate exists and is strong |

> ### **`PROPOSED` answer to the deferred question `OQ-5` — as a SPLIT, which is the DDD-honest answer:**
>
> | Dimension | Proposed owner |
> |---|---|
> | **the LANE-ROLE model** (which roles exist per work item, their immutability, their binding to assignments) | ✅ **BC-7's OWN language** — it is already in BC-7's aggregate and mechanically enforced by its invariants |
> | **the AUTHORITY model** (which activities may be decided, recommended, approved, ARB-only) | ✅ **a governance-published language BC-7 CONSUMES** — it is `§7`'s matrix, it predates BC-7, and it contains no actors |
>
> ⛔ **`PROPOSED`, not decided.** It is offered because `OQ-5` was posed as an either/or and **the evidence supports neither pole alone.**

## 13 · Knowledge / communication relationship

**Studied as the commission requires, and the two are NOT merged merely because they interact.**

```
BC-1 Knowledge Governance                     C-10 DISTRIBUTION            executing session
  durable knowledge, provenance,   ──────▶    (🔴 unowned; today leaks   ──────▶   holds the
  supersession, lineage                        through BC-7's tokenRef              CURRENT rule?
                                               and BC-6's SessionStart)            🔴 EKS-01: unreliable
                                                        │
                                                        ▼
                                              C-19 COMPOSITION  (🔴 unowned)
                                              briefing · dossier · decision surface
                                              next-actor instruction
                                                        │
                                                        ▼
                                                  human decision
```

**Four candidate readings, tested against evidence:**

| Reading | Verdict |
|---|---|
| communication **consumes** knowledge | ✅ **true but insufficient** — it does, and that alone would not make it a context |
| knowledge **owns** communication artifacts | ⚠️ **partly** — a *briefing* is a knowledge product; a *next-actor instruction* is an orchestration product. **Ownership splits by artifact, which is a symptom of two capabilities, not one** |
| communication is a **stewardship** | ✅ **PROPOSED for `C-19`** — it fails the context test on **language** (it borrows every other context's) and on **reason to change** (it changes when the *audience* changes). `A-7`/`GOV-HUMAN-01` is already exactly this shape: a **duty attached to whoever reports**, not a context |
| communication is a **shared platform capability** | ✅ **PROPOSED for `C-10` only** — distribution is mechanism, evidenced by `EKS-01`, and plausibly extends `CAP-01` |

> ## ⭐ **PROPOSED: "Communication" is two capabilities wearing one name.**
> **`C-10` Knowledge Distribution — a genuine, evidenced, unowned MECHANISM** (`F-5`: the handoff token is doing it today, badly, and it is not BC-7's to own).
> **`C-19` Communication Composition — a STEWARDSHIP**, discharged by whichever lane reports, already partly ruled by `A-7`.
> ⛔ **Therefore a "Communication Engineer" role would bind a mechanism gap and a reporting duty into one actor — and the mechanism gap is the part that actually failed.**

## 14 · Candidate bounded contexts or stewardships

| Candidate | Proposal | Confidence |
|---|---|---|
| **`C-10` Knowledge Distribution** | 🟡 **a CAPABILITY needing a declared owner.** Two homes are defensible: **BC-1** (knowledge reaching its consumer is a knowledge duty) or **BC-6** (it is session-payload machinery, `CAP-01` already does a version of it). ⛔ **Not proposed as a new context** — `ES-001.1` parsimony, and its language is borrowed | `PROPOSED`, medium |
| **`C-5` Separation Attestation** | 🟡 **a CAPABILITY with a genuinely distinct reason to change and its own language.** Context vs stewardship vs platform service is **OPEN (`OQ-A`)**. ⚠️ It is the only candidate that could survive the full context test — and the one most likely to be *over*-structured | `PROPOSED`, medium-low |
| **`C-14` Policy Enforcement** | 🟡 **a capability inside BC-2** ⚠️ **[placement WITHDRAWN as unsupported — the live instance traces to BC-3; `OQ-H` · Amendment 1 §A1.2]**, not a context — it shares BC-2's language and changes with the tripwire posture | `PROPOSED`, medium-high |
| **`C-19` Communication Composition** | 🟡 **a STEWARDSHIP**, not a context (§13) | `PROPOSED`, medium-high |
| **A "Roles" context** | 🔴 **NOT proposed.** Lane roles are BC-7's (§12); personas are not architecture (`F-3`); authority is `§7`'s | `PROPOSED` (negative), high |
| BC-1…BC-7 boundaries | ⛔ **untouched.** No existing context is redrawn by this discovery | — |

⛔ **No context is created here. `ES-001.1` parsimony is the standing counter-authority and it is respected: three of five candidates are explicitly proposed as NOT contexts.**

## 15 · Open questions

| # | Question | Status |
|---|---|---|
| **`OQ-A`** | Is `C-5` separation attestation a context, a stewardship, or a platform service? **And is it even solvable** — can a session's identity be attested at all in this runtime? | **OPEN** |
| **`OQ-B`** | Does `C-10` belong to **BC-1** or **BC-6**? | **OPEN** |
| **`OQ-C`** | ADR-C7 — does `CAP-11`'s home move? ⛔ **Not answered here** (`F-10a`) | **OPEN, pre-existing** |
| **`OQ-D`** | The EKP/PKS boundary | **OPEN — deliberately not decided** |
| **`OQ-E`** | `OI-1` — the term **"review"**; and now also **"role"** (§6): three existing meanings | **OPEN, pre-existing + extended** |
| **`OQ-F`** | ADR-C2 — `CAP-13`/registry placement | **OPEN, pre-existing, untouched** |
| **`OQ-G`** | Should the four unowned capabilities be **built** before any role formalization? | **OPEN — this is the sequencing question §17 puts to the PO/ARB** |
| **`OQ-H`** | **`C-14`'s home — BC-2 or BC-3?** `AST-007` is the only Tier-1 asset and its own trace assigns it to `CAP-09` / `verification-evidence`; `EKS-02`'s unclosed gap points at BC-2's derivation surface. ⚠️ **Sharpened by `W-6`: `CAP-09` is *Constitutional Observation & Escalation* with halt-on-trip, so whether `AST-007` is a policy-enforcement instance at all is itself contested** | **OPEN — added by Amendment 1 §A1.2, registered here by Correction #2 §C2.3** |
| **`OQ-I`** | **Disposition of the untracked architectural material** — ⚠️ **scope widened by `W-1`: the four `.puml` files, both `README`s, AND `docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md` (925 lines, all six roles, its own ADR-AIP-04 Options A/B/C).** ⛔ *They cannot remain undisposed and be cited as evidence* | **OPEN — added by Amendment 1 §A1.6, scope corrected and registered by Correction #2 §C2.3** |

### 15.1 · ⚠️ Shared-boundary risks — Track 1 coupling · **RECORDED, NOT SOLVED**

**Per the commission's §6 boundary rule, discovery stopped at each of these rather than resolving them.**

| # | Affected boundary | Evidence | Consequence | Required decision |
|---|---|---|---|---|
| **SB-1** ⚠️ **[remains CONFLICTED / REQUIRES RE-VERIFICATION · Amendment 1 §A1.7]** | 🔴 **Conformance authority** | §9 records *conformance evidence* as **shared between BC-1 and BC-3** (`OBSERVED`). **Track 1 has live, accepted decisions about conformance** — Decision 2 (node set + edge set + metric) and the finding that *conformance can never be evidenced by implementation agreement* | **Assigning "conformance evidence" to a Verification capability in ADR-AIP-04 could RELOCATE conformance authority that Track 1 currently exercises** | ⛔ **STOP. Returned to Architecture / PO-ARB as a separate act. ADR-AIP-04 must NOT assign conformance authority** |
| **SB-2** | 🟡 **Evidence-chain location coupling** | `EKS-03`: 39 `tokenRef`s hard-code paths; **Track 1's own records live in that chain** | A `C-8` fix (`PROPOSED I-K2`, location-independent identity) would touch records Track 1 depends on | ⛔ **Do not act. Sequence any `C-8` work against Track 1** |
| **SB-3** ⚠️ **[premise RECALCULATED · Amendment 1 §A1.2]** | 🟡 **`C-14` enforcement posture** | `C-15` tripwires are all **Tier-2 non-blocking** (`OBSERVED`) | Making enforcement blocking would change the conditions under which **Track 1 lanes** execute | ⛔ **A posture change needs its own authorization; not proposed here** |

⛔ **Nothing in this document modifies the L3 fact model, any accepted semantic decision, the PHP LCOM4 architecture, conformance authority, or `KOS-CONTRACT-NEUTRALITY-001`.**

## 16 · Recommendations *(⚠️ **RECOMMENDATIONS — not decisions, not pre-selections**)*

1. **Do not adopt the six-role model as a role model.** Retain the six titles, if wanted, **explicitly as commissioning personas** — an addressing convention, declared as such, so it can never be mistaken for architecture (`F-3`/`F-3a`).
2. **Answer `OQ-5` as a split** (§12): BC-7 owns the **lane-role** model; the **authority** model is governance-published and consumed.
3. **Sequence capability-building before role formalization** (`OQ-G`). Suggested order by evidence strength: **`C-10`** (`EKS-01`, a live recurring failure) → **`C-14`** (`EKS-02`, mechanically closable) → **`CAP-03`/`C-7`** (a real owner with an unbuilt capability) → **`C-5`** (hardest; may be unsolvable — `OQ-A`).
4. **Declare `C-19` a stewardship** and attach it to the reporting lane, extending `A-7` rather than creating an actor.
5. **Resolve the word "role"** before publishing any role vocabulary (§6, `OQ-E`) — `OI-1` is precedent for what an unresolved term costs.
6. ⛔ **Create no bounded context.** Three of five candidates are proposed as *not* contexts; `ES-001.1` holds.
7. **Return `SB-1` to Architecture / PO-ARB as a separate act** before ADR-AIP-04 assigns anything touching conformance.

## 17 · PO/ARB decisions required

| # | Decision | Minimum the act must state |
|---|---|---|
| **1** | **Is the six-role model adopted, rejected, or re-classified as personas?** | which of the three; and if personas, that they carry no authority |
| **2** | **`OQ-5` role-model ownership** — BC-7's own language · governance-published · **or the proposed split** | which, and the consequence for BC-7's declared role sets |
| **3** | **Are `C-5`, `C-10`, `C-14`, `C-19` accepted as capabilities?** | accept/reject **each**; a blanket answer would repeat the conflation this discovery found |
| **4** | **Owner for `C-10`** — BC-1 or BC-6 (`OQ-B`) | the owner, or an explicit deferral with a named trigger |
| **5** | **Sequencing** — capability-building before role formalization? (`OQ-G`) | the order, or that role formalization proceeds regardless |
| **6** | **`SB-1`** — that ADR-AIP-04 **must not** assign conformance authority | explicit confirmation, or a separate act resolving it |
| **7** | **Who verifies this discovery** | a process that is neither this lane nor any prior lane in this chain — §0 makes this mandatory |

---

**DISCOVERY DELIVERED · STOPPING.**
⛔ **No implementation · no agent created · no `.claude/platform` change · no Track-1 modification · no `KOS-CONTRACT-NEUTRALITY-001` change · BC-7 not reopened · the six-role model NOT decided · no technology selected · no shared L3 or semantic decision touched · no self-acceptance · no self-verification.**
**Every proposal above remains a proposal. This lane does not complete its own assignment (`G-1`).**

**Traceability:** commission `2026-08-18-KOS-AIP04-DISCOVERY-001-commission.md` · grant `G-KOS-AIP04-DISCOVERY` · lane seq 1–3 · `ADR-AIP-01` (Baseline v1.0, `AIP-11`, `OI-1`) · `ADR-AIP-02` (`AIP-14` Product Primacy) · `ADR-AIP-03`/BC-7 §1/§2/§3/§5/§7 incl. consequence (a) `CAP-14` and (b) the deferral · `Phase-02-Domain-Model.md` §7 AI Responsibility Matrix · `KOS-ARCH-BASELINE-002` capability map v2 (`CAP ↔ BC`) · `EKS-01`…`EKS-04` · `.claude/scripts/workflow-state.php` (`AST-015`) read in full · 15 work-item records · `.claude/platform/registry.yaml` (`AST-001…016`) · `INV-ATTR-2`/`G-1`/`G-2`/`R8`/`R-34`/`R-37`/`ES-001.1` · Track-1 Decisions 1/2 and 13.1 *(cited for `SB-1` only, unmodified)*.

---
---

# AMENDMENT 1 — **Material corrections after independent verification**

**Appended 2026-08-18. Everything above is the original proposal and is retained.** Only **additive inline pointers** (`⚠️ [… · Amendment 1 §…]`) were inserted above, adjacent to each corrected claim, so a reader cannot act on a falsified statement without seeing that it was corrected. **No original wording was deleted, softened, or rewritten** — the estate's own precedent (`ADR-AIP-01`'s appended addendum, `AIP-11`; and the `EM-IMPL-002` ADRs' inline `FACTUAL CORRECTION` blocks).

**Trigger:** `KOS-AIP04-DISCOVERY-001-independent-verification.md` — verdict 🔴 **RETURNED FOR MATERIAL CORRECTION** (`V-1`, `V-2` material; `V-3`, `V-4` minor; `OQ-5` classification; `SB-1` conflicted).
**Scope:** ⛔ **CORRECTION ONLY.** No new discovery round · no redesign · no PO/ARB question answered · no role, agent, context or service created · **BC-7, Track 1, the shared L3 contract and every accepted semantic decision untouched.**
**Corrections were re-derived from primary evidence, not accepted from the verification report.** Where my recount differs from the verifier's, both numbers are given.

### ⚠️ A1.0 · Root cause — owned, because it is itself a finding

The verifier's finding `L` is exact: **both failures are one error — *absence of registration presented as absence of existence*.** The mechanical causes are worth recording because they are avoidable:

| Failure | Mechanical cause |
|---|---|
| `V-1` | **The absence claim rested on a search truncated by `head -5`.** The survey command included `docs/knowledgeos/` and would have hit the diagram; **its output was cut before the hit was visible, and the truncated result was then reported as "nowhere."** |
| `V-2` | A **verified narrow claim** (`C-15`'s three assets are all Tier-2) was **generalised into a broad one** (*"enforcement is absent"*) without searching for a counter-instance. |

> ⭐ **PROPOSED (a durable discipline, not a decision):** **an absence claim requires an exhaustive, untruncated search AND an explicit statement of what was searched.** This is the same shape as Track 1's `O-1` (*a probe that cannot fail is not evidence*) and `O-2` (*a probe that can only observe the final number can fail to notice that it did fail*) — **extended: a search that can be truncated is not a survey.** ⚠️ Offered as an observation for the estate, **not** adopted here.

---

## A1.1 · `V-1` — the six-role evidence, corrected

### The falsifier fired. Primary evidence, read directly:

`docs/knowledgeos/architecture/01-system-context.puml` — **untracked (`??`)** — models the titles as C4 `Person()` actors with responsibilities and relationships:

```
Person(governance, "Governance Engineer",               "Authority, lifecycle, grants, acceptance")
Person(comm,       "Governance Communication",          "Next-actor and handover communication")
Person(arch,       "Architecture Engineer",             "DDD strategic and tactical architecture")
Person(impl,       "Implementation Engineer",           "Builds approved implementation")
Person(verifier,   "Independent Verification Engineer", "Falsifies and assures")
```

> ### 🔴 **BOTH HALVES OF `F-3` ARE WITHDRAWN AS FALSIFIED.** They do **not** appear "nowhere", and where they appear they are **not** commissioning personas — **they are modelled actors in an architecture diagram.** `OBSERVED`.

### The four-way distinction the corrected finding uses

| Class | Contents here | Governed? |
|---|---|---|
| **1 · Governed architectural evidence** | `ADR-AIP-01/02/03`, the accepted capability map (`CAP-01…14 ↔ BC-1…BC-7`), `registry.yaml` (`AST-001…016`) | ✅ committed, registered, decided |
| **2 · Untracked / non-registered architectural material** | **all four `.puml` files + both `README`s in `docs/knowledgeos/architecture/` — every one `??`** | 🔴 **never committed ⇒ not a governed artifact** |
| **3 · Commissioning persona usage** | *"Principal Knowledge Engineer / Strategic DDD Architect"* in document headers | ⚠️ an addressing convention |
| **4 · Executable workflow lane roles** | BC-7's declared role sets, validated at `REGISTER`, immutable per `R8` | ✅ mechanically enforced |

> ## **The KnowledgeOS principle, applied: absence from a governed registry ≠ evidence of non-existence.** The original claim collapsed classes 1–4 into one survey. **They are four different kinds of evidence with four different weights.**

### ⭐ Two facts from the **full** `.puml` survey the verifier's condition 1 required — in neither report

| # | `OBSERVED` |
|---|---|
| **A1.1-a** | **The two diagrams disagree with each other.** `01-system-context.puml` models **five separate `Person()` actors**; `02-container-architecture.puml` collapses them into **ONE** actor — `Person(engineer, "Engineering Roles", "Governance, Communication, Architecture, Implementation, Verification")`. **One model, two incompatible actor decompositions.** |
| **A1.1-b** | ⭐ **Neither diagram contains a "Knowledge Engineer", and neither contains a "Communication Engineer" as a peer.** `01` names *"Governance Communication"*; `02` lists *"Communication"* inside a single collapsed actor. ⇒ **the untracked material depicts a FIVE-actor sketch, not the six-role set under evaluation.** |

### `F-3′` — corrected finding *(replaces `F-3`; original retained above)*

> **`OBSERVED`:** the Engineer titles **are** modelled as `Person()` actors — in **untracked, uncommitted** C4 diagrams in `docs/knowledgeos/architecture/`.
>
> **`INFERRED` (`F-3a′`, restated on corrected evidence):** that material **does not establish a governed platform role model**, for four independent reasons — ① **untracked and never committed**, so it is not a governed artifact under this estate's own rules; ② **the two diagrams contradict each other** on the actor decomposition (`A1.1-a`); ③ **both omit Knowledge Engineer**, so neither depicts the six-role set being evaluated (`A1.1-b`); ④ **no ADR, decision record, capability-map row or registry entry adopts them.**
>
> **`PROPOSED` classification:** **candidate architectural material pending explicit PO/ARB disposition** — ⛔ **not "non-existent", and ⛔ not "governed".**

> ### ⚠️ **The strength of the conclusion has DROPPED, and that is stated rather than hidden.** Original: *"personas, not architecture."* Corrected: *"not yet governed architecture."* **The weaker claim is the one the evidence supports.**

---

## A1.2 · `V-2` — `C-14` corrected to **LIVE, NARROW, UNMODELLED**

### Primary evidence, read directly from `registry.yaml`

```yaml
- id: AST-007
  path: .claude/scripts/db-safety-check.sh
  component: CMP-005
  adoption: adopted
  governance_tier: 1               # blocking gate
  runtime_moments: [PRE_ACTION]
  verified: { date: 2026-07-08, method: "… blocks migrate:fresh/refresh/db:seed unless testing env" }
  trace: { capability: CAP-09, context: verification-evidence, principle: AIP-06, decision: PD-06, adr: ADR-AIP-01 }
```

**`OBSERVED`: `AST-007` is an adopted Tier-1 *blocking* governance gate, running at `PRE_ACTION`, verified 2026-07-08. That is policy enforcement, mechanical and in production today.**

**⛔ The conclusion "policy enforcement is absent" is WITHDRAWN as falsified.**

### The `C-14` / `C-15` distinction is PRESERVED, not erased

| | Assets | Tier | Verified here |
|---|---|---|---|
| **`C-14` policy enforcement** | `AST-007` | **Tier 1 — blocking** | ✅ `governance_tier: 1`, `adoption: adopted` |
| **`C-15` discipline / tripwires** | `AST-005`, `AST-006`, `AST-014` | **Tier 2 — all non-blocking** | ✅ all three re-verified `governance_tier: 2` |

**⇒ The original `C-15` claim was accurate; only its generalisation into `C-14` was wrong.** The two remain **different capabilities with different enforcement postures.**

### Corrected status

| Capability | Original | **Corrected** |
|---|---|---|
| `C-14` policy enforcement | 🔴 `absent` / `NO OWNER` | 🟡 **`LIVE, NARROW, UNMODELLED`** — mechanically enforced for **one** subject (database safety); **not modelled as a capability**; **coverage and ownership are the gap, not existence** |

**What survives unchanged (`OBSERVED`):** `EKS-02`'s specific gap is real and unclosed — **`doc-placement.php` is derivation, not enforcement**, and no blocking gate covers documentation placement. `F-7`'s three-way distinction (**authorship ≠ derivation ≠ enforcement**) is verified and stands.

### ⭐ A refinement neither report states — and it withdraws one of my own proposals

**`OBSERVED`: `AST-007`'s own `trace` assigns it to `capability: CAP-09`, `context: verification-evidence` — i.e. the one live blocking enforcement instance sits in *BC-3 Verification & Evidence*, NOT in BC-2 Implementation Guidance.**

⇒ **§14's proposal that `C-14` is *"a capability inside BC-2"* is WITHDRAWN as unsupported by evidence.** The live instance points at BC-3; `EKS-02`'s unclosed gap points at BC-2's derivation surface. **The home is genuinely contested ⇒ new `OQ-H`.** ⛔ **Not decided here.**

### `SB-3` recalculated

| | |
|---|---|
| **Original premise** | *"making enforcement blocking would change the conditions under which Track-1 lanes execute"* |
| **`OBSERVED` correction** | **Blocking enforcement already exists** (`AST-007`, Tier-1, `PRE_ACTION`), and **Track-1 lanes already execute under it.** |
| **Corrected `SB-3`** | 🟡 **A change of COVERAGE, not of KIND.** *Introducing* blocking enforcement is not a new condition; **extending its coverage to new subjects would be.** ⚠️ Still **not proposed**, still requires its own authorization. **The shared-boundary flag is retained at reduced severity.** |

---

## A1.3 · `V-3` — `EKS-03` count corrected **39 → 40**

**My own recount, independently of the report:** **16** workflow records · **48** `tokenRef`s · **40 path-bearing**. ✅ **Confirms the verifier's 40.**

⚠️ **One discrepancy recorded rather than smoothed:** the verifier counted **17** records; I observe **16**. **The record set is time-varying — it was 15 when the original proposal was drafted.** ⇒ **the durable fix is not another fixed number but a stated observation point:** *40 of 48 `tokenRef`s across 16 records, observed 2026-08-18 at this amendment.*

**The underlying conclusion is UNCHANGED, and per the verifier the drift *strengthens* it:** `F-8` (provenance is path-coupled) and `PROPOSED I-K2` (evidence identity independent of storage location) stand, because **the count moves as ongoing work adds path-bearing references** — which is the coupling, demonstrated.

---

## A1.4 · `V-4` — `F-2` wording corrected

**⛔ WITHDRAWN:** *"contains no actors at all."*

**`OBSERVED` correction:** §7 **names decision AUTHORITIES** — **Chief Architect · ARB · Sponsor**, plus *owner* and *rule owner* — **but names no engineering roles.**

**RETAINED, because it is the substantively useful distinction and it survives:** §7 is an **activity × decision-right** model, **not an engineering-role model.** Its own pattern statement is unaffected: *decisions follow the nature of the activity, never the identity of the actor.* **The authority dimension is preserved, not erased.**

**Consequence for `OQ-5`:** what BC-7 would consume **does** name authorities. ⇒ the split's *"names no actors"* framing is corrected; **the split itself is unaffected**, because it rests on **precedence** and **non-coupling**, both independently verified (§A1.5).

---

## A1.5 · `OQ-5` — the consumption leg reclassified to `INFERRED`

| Leg | Original | **Corrected** | Evidence |
|---|---|---|---|
| §7 **predates** BC-7 | `OBSERVED` | ✅ **`OBSERVED`** — unchanged | git: §7 **2026-07-10** vs BC-7 recognition **2026-08-17** |
| §7 carries **no BC-7 vocabulary** (no hidden coupling) | `OBSERVED` | ✅ **`OBSERVED`** — unchanged | **zero** occurrences of `BC-7` / `lane role` / `session assignment` |
| **BC-7 *consumes* §7** | ⚠️ presented as observed | 🔴 **`INFERRED`** | **no mechanical linkage exists.** Grants carry scope and cite human acts; **nothing binds a grant to a §7 row** |

⛔ **`OQ-5` is NOT decided.** The proposed split stands as `PROPOSED`; only the consumption leg's classification is corrected.

---

## A1.6 · Disposal of the *"Governance Communication"* actor

**`OBSERVED`:** `Person(comm, "Governance Communication", "Next-actor and handover communication")` with `Rel(comm, kos, "Coordinates actor context")` — **in an untracked file.**

> ## **Classification: UNTRACKED ARCHITECTURAL EVIDENCE REQUIRING EXPLICIT PO/ARB TREATMENT BEFORE IT CAN BE CONSIDERED GOVERNED ARCHITECTURAL TRUTH.**
> ⛔ **No replacement role is invented. It is NOT silently declared valid architecture. It is NOT dismissed.**

### Reconciliation with `F-1` and `C-19`

| Claim | Status after correction |
|---|---|
| **`F-1`** *"the ACCEPTED model contains no Communication capability and no Communication context"* | ✅ **STANDS, `OBSERVED`** — `CAP-01…14` and `BC-1…BC-7` contain none, and **the diagram is not part of the accepted model** (untracked, class 2 of §A1.1) |
| **`C-19`** *"communication composition — no owner"* | ✅ **STANDS for governed artifacts** — ⚠️ **and is now qualified:** the diagram shows an **undisposed intent** to model communication as first-class. `C-19`'s status is *unowned in governed architecture*, **not** *unconsidered* |
| **§13's stewardship proposal** | 🟡 **Weakly concurred with, at correct strength.** The actor's own name and description place communication **inside Governance** (*"Governance Communication"*, *"next-actor and handover"*) rather than as a peer context — the same shape §13 proposed. ⚠️ **But untracked material corroborates nothing governed: this is a *concurring sketch*, not evidence.** `INFERRED`, low weight |
| **The honest counter** | ⚠️ Someone modelled communication as first-class enough to draw an actor and a relationship. **That is a reason for the PO/ARB to dispose of it explicitly rather than for this amendment to argue it away** ⇒ new **`OQ-I`** |

---

## A1.7 · `SB-1` — qualification preserved

> ## ⚠️ **`SB-1` REMAINS `CONFLICTED / REQUIRES RE-VERIFICATION`. It is NOT resolved by this amendment and NOT resolved by the verification.**

**`OBSERVED`, from the verification report §1②:** the verifier **is the Track-1 implementation engineer (`4c6c1dac`)** and the author of the Track-1 fit assessment and reconciliation, **and recuses from `SB-1`**.

⛔ **The current verification MUST NOT be used as independent evidence for `SB-1`.** Its `SB-1` row is a **recusal**, not a clearance.

**`SB-1`'s substance is unchanged:** conformance evidence is shared BC-1/BC-3 while **Track 1 already exercises live accepted conformance decisions** ⇒ **ADR-AIP-04 must not assign conformance authority**; the question returns to Architecture / PO-ARB as a separate act.

⚠️ **The conflict's direction is recorded because the verifier recorded it — the proposal already *halts* `SB-1`, so confirming it preserves the status quo rather than advantaging Track-1 work. That mitigates the risk; it does NOT cure the conflict.** **Re-verification must come from a process with no Track-1 authorship.**

---

## A1.8 · Impact assessment on the overall thesis

### 🔴 One headline changes materially

| | |
|---|---|
| **Original** | *"**Four** capabilities have NO OWNER: `C-5` · `C-10` · `C-14` · `C-19`"* |
| **Corrected** | > ## **THREE capabilities have no owner — `C-5` separation attestation · `C-10` knowledge distribution · `C-19` communication composition — and a FOURTH, `C-14` policy enforcement, is LIVE BUT UNMODELLED with a contested home (`OQ-H`).** |

**This was the discovery's most quotable line and it is corrected explicitly, not quietly.**

### ✅ What survives independent falsification, unchanged

| Finding | Status |
|---|---|
| **All mechanism evidence** — `G-1` closure refusal, `R8`, `REGISTER` role validation, this lane's mechanical exclusion of `implementation` | ✅ **reproduced exactly by the verifier** |
| ⭐ **The invariant asymmetry (§7):** every invariant BC-7 owns is **mechanically enforced**; almost every invariant outside BC-7 is **declared only** ⇒ *assurance is strong where it is executable and weak where it is prose* | ✅ **unchallenged — the discovery's core structural result** |
| ⭐ **`C-5` cannot be discharged by an agent** — a process cannot attest its own separation | ✅ **verified; called the strongest argument in the proposal** |
| **`C-10` is the strongest evidenced gap** (`EKS-01`; the mechanism is structural — 48 uses, not incidental) | ✅ **verified** |
| **`C-19` fails its own five-test matrix ⇒ stewardship, not context** | ✅ **verified, and specifically praised as applying the test rather than decorating it** |
| **`F-7`** authorship ≠ derivation ≠ enforcement · **`F-11`** Product Primacy · **`F-10`** ADR-C7 pre-emption | ✅ **verified** |
| **`F-9`/`F-9a`** role fluidity; Governance's recording duties are discharged by the holding lane while **acceptance is strictly separated** | ✅ **independently corroborated — the verifier records its own three self-recorded STARTs** |
| **Parsimony** — no bounded context created; three of five candidates proposed as *not* contexts | ✅ **verified** |
| **Track-1 safety** | ✅ **verified clean** — no L3, semantic decision or Track-1 artifact touched |

### ⚠️ What is weakened

**`F-3`/`F-3a`** — conclusion strength drops from *"personas"* to *"not yet governed architecture"* (§A1.1) · **`SB-3`** — coverage, not kind (§A1.2) · **§14's `C-14` → BC-2 placement** — withdrawn as unsupported (§A1.2) · **`OQ-5` consumption** — `OBSERVED` → `INFERRED` (§A1.5) · **`F-2` wording** (§A1.4) · **`EKS-03` count** (§A1.3).

### The thesis after correction

> **The discovery's framing survives: the question is capability ownership, not role creation — and the load-bearing structural result (mechanical inside BC-7, declared outside) is untouched.** **What changed is that one capability is live rather than absent, one absence claim was withdrawn, and one conclusion now rests on a weaker but defensible basis.** ⛔ **No finding was strengthened by this amendment, and none should be read as strengthened.**

---

## A1.9 · Corrected PO/ARB decision set

**Supersedes §17's table. Original retained above. Corrections in bold.**

| # | Decision | Minimum the act must state |
|---|---|---|
| **1** | Is the six-role model adopted, rejected, or re-classified? | ⚠️ **CORRECTED framing:** the choice is no longer *"architecture vs personas"*. It is between **adopting**, **rejecting**, or **treating the untracked diagrams as candidate material requiring disposition** (§A1.1) |
| **2** | `OQ-5` role-model ownership — BC-7's own · governance-published · **or the proposed split** | unchanged; ⚠️ **noting the consumption leg is `INFERRED`, not observed** (§A1.5) |
| **3** | **CORRECTED:** are **`C-5`, `C-10`, `C-19`** accepted as **unowned capabilities**, and is **`C-14`** accepted as **live-but-unmodelled**? | accept/reject **each of the four separately** — ⚠️ **a blanket answer would repeat the exact conflation that produced `V-2`** |
| **4** | Owner for `C-10` — BC-1 or BC-6 (`OQ-B`) | the owner, or an explicit deferral with a named trigger |
| **5** | Sequencing — capability-building before role formalization (`OQ-G`) | ⚠️ **CORRECTED input:** `C-14` is now a **coverage** problem, which may re-order it relative to `C-10` |
| **6** | **`SB-1`** — that ADR-AIP-04 must **not** assign conformance authority | ⚠️ **AND: `SB-1` must be RE-VERIFIED by a process with NO Track-1 authorship** (§A1.7) |
| **7** | Who verifies **this amendment** | a process that is **neither this producer nor the first verifier** — and **preferably with no Track-1 authorship**, so `SB-1` can be cleared in the same pass |
| **8** | 🆕 **`OQ-H` — `C-14`'s home: BC-2 or BC-3?** | the live instance traces to **BC-3**; `EKS-02`'s gap points at **BC-2**. The act should name the owner or defer with a trigger (§A1.2) |
| **9** | 🆕 **`OQ-I` — disposition of the untracked architecture material** — the four `.puml` files and both `README`s, including the **"Governance Communication"** actor and the **two contradictory actor decompositions** | one of: **adopt** (which requires committing and registering them) · **reject** · **retain as explicitly non-governed drafts.** ⛔ **They cannot remain undisposed and be cited as evidence** (§A1.1, §A1.6) |

**Carried forward unchanged and unanswered:** `OQ-A` · `OQ-B` · `OQ-C` (ADR-C7) · `OQ-D` (EKP/PKS) · `OQ-E` (`OI-1` "review"; "role") · `OQ-F` (ADR-C2) · `OQ-G` · `SB-2`.

---

**AMENDMENT 1 DELIVERED · STOPPING.**
⛔ **No new discovery · no redesign · no PO/ARB question answered · no role, agent, context or service created · BC-7 not reopened · Track 1, the shared L3 contract and all accepted semantic decisions untouched · `SB-1` NOT resolved · the untracked diagrams NOT adopted and NOT committed · no self-verification · no self-acceptance · assignment NOT closed (`G-1`).**

**Next actor: an independent verification process — neither this producer nor the first verifier, and preferably with no Track-1 authorship — re-verifies the corrected discovery. Only then: PO/ARB decision.**

**Amendment traceability:** `KOS-AIP04-DISCOVERY-001-independent-verification.md` (`V-1`…`V-4`, findings `E`/`I`/`L`, §1② recusal, conditions 1–6) · primary evidence re-derived: **`01-system-context.puml`** and **`02-container-architecture.puml`** (both `??`, full `Person()` survey) · `registry.yaml` `AST-005/006/007/014` (tiers re-read; `AST-007` `trace` → `CAP-09`/`verification-evidence`) · 16 workflow records / 48 `tokenRef`s / **40 path-bearing**, observed 2026-08-18 · `Phase-02-Domain-Model.md` §7 (authorities: Chief Architect · ARB · Sponsor) · git precedence 2026-07-10 vs 2026-08-17 · `AIP-11` addendum precedent · `INV-ATTR-2`/`G-1`/`G-2`/`R8`/`R-34`.

---
---

# CORRECTION #2 — after Independent Verification #2

| | |
|---|---|
| **Status** | 🟡 **CORRECTION ONLY.** Nothing decided. Not a third discovery round. |
| **Assignment** | `S4b-architecture-aip04-correction2` — **seq 10 REGISTER · 11 HANDOFF · 12 START** |
| **Grant** | `G-KOS-AIP04-CORRECTION2` (AUTHORIZED, PO/ARB 2026-08-19) |
| ⭐ **PRODUCING PROCESS — declared, because `W-4` exists** | **`claude-code-session:5e1dd9ee`** — the original discovery producer **and** the author of Amendment 1. **Self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`). Distinct from Verification #1 (`1c8b041b`) and Verification #2 (`2da45a86`), which is the assignment's precondition — ⛔ **but NOT independent of its own work.** `R-34`/`P-2` binds. |
| **Preserved, nothing deleted** | the original proposal · Amendment 1 · Verification #1 · Verification #2 |
| **Next actor** | **Independent Verification #3** — ARB bar strengthened: **no Track-1 authorship AND no authorship of either ADR-AIP-04 correction.** |

**What landed in the document body (additive, verified after writing):** the `C-14` correction moved onto the **`C-14` row** and `C-5` **restored** (§C2.2) · **`OQ-H` and `OQ-I` added to the authoritative §15 register** (§C2.3).

---

## ⚠️ C2.0 · The failure recurred a third time — and the repository already had a rule against the mechanism

**Verification #2's sharpest finding is that Amendment 1 stated the absence-search discipline and then failed to apply it to its own conclusion.** That is accepted without qualification.

| # | Instance | Mechanism |
|---|---|---|
| **1** | `V-1` — *"the six roles appear nowhere"* | a survey **truncated by `head -5`** |
| **2** | `V-2` — *"enforcement is absent"* | a **verified narrow claim generalised** without seeking a counter-instance |
| **3** | **`W-1`** — *"the untracked material depicts a five-actor sketch"* | an **absence claim about a CLASS of material, derived from one file type in one directory** |

> ### ⭐ **And a second, independent mechanism finding — `W-2`:**
> **The `C-14` correction landed on the `C-5` row because it was applied with a scripted first-match string replace.** `C-5` and `C-14` shared the look-alike cell `| 🔴 **NO OWNER** | **absent** |`, and the first match was `C-5`.
>
> **This repository already carries a STANDING RULE against exactly this mechanism** — *"Do NOT use automated search-and-replace on source code … a slightly-too-broad pattern corrupts look-alike sites silently"* — whose declared scope is **source code**. ⛔ **The rule's SCOPE excluded documents; its RATIONALE did not.** The estate has now paid for that gap inside a single work item.
>
> **This correction's own row edits were therefore made by verified line index, with a content assertion before writing** — the discipline the rule intends. **`PROPOSED`, not adopted: extend the rule's scope from *source code* to *any artifact with look-alike sites*.**

**Both mechanisms share one root:** an operation whose *scope was assumed rather than verified* — a truncated search, an over-general induction, a first-match replace. **`PROPOSED` (not a decision): the absence-claim rule (`A1.0`) should be paired with a scope-verification rule — *state what you searched or edited, and verify the operation's extent before relying on it.***

---

## C2.1 · `W-1` — the six-role evidence survey, completed

### The mandatory disclosure the rule now requires

| | |
|---|---|
| **Search scope** | the **entire repository**, excluding only `.git`, `node_modules`, `vendor`, `public` |
| **Search method** | case-insensitive fixed-string search for **each of the six titles separately**, over **all file types**, output **not truncated**; then per-file `git ls-files` to establish tracked status |
| **Search completeness** | ✅ **complete for the six literal titles.** ⛔ **NOT complete for paraphrases** (e.g. *"governance role"*, *"verifier"*), and **no file-by-file classification of all hits was performed** — see the residual below |
| **Observation point** | **2026-08-19**, at this correction |

### `OBSERVED` — the measured distribution

| Title | Files |
|---|---:|
| Knowledge Engineer | **89** |
| Governance Engineer | 16 |
| Implementation Engineer | 10 |
| Verification Engineer | 9 |
| Architecture Engineer | 7 |
| Communication Engineer | 7 |
| **Distinct files containing at least one** | ⭐ **104** |

**Tracked status:** the overwhelming majority are **TRACKED**. Untracked among the hits: `01-system-context.puml`, `02-container-architecture.puml`, **`docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md`**, and two unrelated files.

### 🔴 `A1.1-b` is FALSIFIED and withdrawn

**`A1.1-b` claimed the untracked material *"depicts a FIVE-actor sketch, not the six-role set under evaluation."* Primary evidence refutes it.**

`docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md` — **untracked, 925 lines** — contains **all six roles as first-class sections**:

```
# Role 1 — Governance Engineer        # Role 4 — Architecture Engineer
# Role 2 — Communication Engineer     # Role 5 — Implementation Engineer
# Role 3 — Knowledge Engineer         # Role 6 — Independent Verification Engineer
```

**And it pre-frames this very ADR:** `## ADR-AIP-04 — AI Engineering Platform Capability Model`, with **`Option A — Accept six-role model` · `Option B — Keep them as Governance sub-capabilities` · `Option C — Defer`**, plus *"But do not create 6 independent agents immediately"* and *"Do not mix ADR-AIP-04 with BC-7."*

> ### ⚠️ **A disclosure this correction must make against its own interest**
> **Option B — *"keep them as Governance sub-capabilities"* — is materially the conclusion the discovery reached independently in §13** (communication as a **stewardship inside Governance** rather than a peer context). **The commission's capability-first guard and the BC-7 separation rule also appear in that document.**
>
> ⛔ **Whether the discovery independently converged or reconstructed an unread source CANNOT BE ESTABLISHED.** *"I did not read it"* is exactly the class of claim `INV-ATTR-2` says is not attestable. **Classification: `OPEN`.** ⚠️ **Consequence for the PO/ARB: §13's convergence with Option B must NOT be counted as independent corroboration of Option B.**

### `F-3″` — the finding, restated on the completed survey *(supersedes `F-3` and `F-3′`)*

> **`OBSERVED`:** the six titles occur in **104 files across the repository**, most of them **tracked**, including a **925-line untracked document that sets out all six roles and drafts this ADR's option space**, and **two mutually inconsistent untracked C4 diagrams**.
>
> ⛔ **EVERY ABSENCE CLAIM IS WITHDRAWN.** *"Appear nowhere"* (original), *"only commissioning personas"* (original), and *"a five-actor sketch"* (Amendment 1) are all falsified and are retained above only as historical wording.
>
> **`INFERRED` — the surviving conclusion, and the ONLY one this evidence supports:** **the six-role model is not established as GOVERNED platform architecture** — because **no ADR, no accepted decision record, no capability-map row and no `registry.yaml` entry adopts it.** *(`OBSERVED` component: `registry.yaml` contains no role or actor concept at all; `CAP-01…14 ↔ BC-1…BC-7` contains no role row; `ADR-AIP-01/02/03` adopt none.)*
>
> ⭐ **The claim's basis has changed completely.** It no longer rests on *scarcity of the titles* — they are abundant. **It rests solely on the absence of an ADOPTING GOVERNED ACT.** That is a narrower, checkable, and materially different argument.

**Residual, stated rather than hidden (`OPEN`):** **no file-by-file classification of the 104 hits was performed**, so this correction does **not** assert that none of the 104 is a governed adoption — it asserts only that **the four artifact classes that could adopt one (ADRs, decision records, the capability map, the registry) do not.** ⛔ **A full classification of the 104 remains unperformed and is a legitimate input to `OQ-I`.**

### The four-way distinction, applied to the completed survey

| Class | Contents | Weight |
|---|---|---|
| **1 · Governed architecture** | `ADR-AIP-01/02/03` · the accepted capability map · `registry.yaml` | ✅ **contains no six-role adoption** — `OBSERVED` |
| **2 · Untracked material** | the 925-line role model · `01`/`02-*.puml` · both `README`s | 🔴 **rich in six-role content; NONE of it governed** |
| **3 · Persona language** | *"Principal Knowledge Engineer / Strategic DDD Architect"* headers — the bulk of the 89 "Knowledge Engineer" hits | ⚠️ an addressing convention |
| **4 · Executable lane roles** | BC-7's declared role sets, validated at `REGISTER`, immutable per `R8` | ✅ **the estate's only mechanically enforced role model** |

---

## C2.2 · `W-2` and `W-6` — the `C-14` correction, landed and sharpened

**`W-2` is accepted.** The Amendment-1 pointer sat on the **`C-5`** row while **`C-14`** still displayed the falsified *"absent / NO OWNER"* — and §3's inventory **is** the surface PO/ARB decision 3 reads. **Both rows are now fixed in the document body**, edited by verified line index (§C2.0).

| Row | State now |
|---|---|
| **`C-5`** | **restored to its own correct status** — `absent` is right: separation attestation has no owner and no mechanism. The misplacement is noted inline so it stays traceable |
| **`C-14`** | the withdrawal and the corrected status now appear **on the `C-14` row** |

### 🔴 `W-6` forces a further correction — the status is `CONTESTED`, not `LIVE`

**`OBSERVED`, from `Phase-02.5-Certification-Plan.md`:**

```
CAP-09 | Constitutional Observation & Escalation | Read-only observation of constitutional guards
       (CI-1..5/Q7); on a trip: halt, escalate, never retry, never modify.
       Owner: Verification & Evidence | write-paths: None — deliberately closed | Tier-1 action-space asymmetry
```

**`AST-007`'s own `trace` assigns it to `CAP-09`.** ⇒ `db-safety-check.sh` blocking `migrate:fresh/refresh/db:seed` is **exactly a halt-on-trip on a constitutional guard** — which is `CAP-09`'s *defined behaviour*, not evidence of a general policy-enforcement capability.

> ## **Therefore Amendment 1's status `LIVE, NARROW, UNMODELLED` was still too strong, and is corrected to `CONTESTED`.**
>
> | Statement | Status |
> |---|---|
> | *"policy enforcement is absent"* (original) | 🔴 **FALSIFIED — withdrawn** (a Tier-1 blocking asset exists) |
> | *"policy enforcement is live, narrow, unmodelled"* (Amendment 1) | 🔴 **NOT ESTABLISHED — withdrawn.** The sole Tier-1 asset may be a **correctly filed `CAP-09` constitutional-observation asset**, not a `C-14` instance |
> | **`CONTESTED`** — corrected | ✅ **the strongest justified class.** ⛔ **Neither "absent" nor "live" is established** |

**`OQ-H` is sharpened accordingly** *(and registered in §15 — §C2.3)*: the question is **not merely BC-2 vs BC-3**, but first ***"does `C-14` have ANY live instance, or is the only Tier-1 asset an instance of a different capability?"*** ⛔ **Not decided here** — the record does not decide it, and the grant forbids solving it.

**Unchanged and still `OBSERVED`:** `C-15`'s three assets (`AST-005/006/014`) are **all Tier-2 non-blocking** · **`AST-007` is the estate's sole Tier-1 asset** · `EKS-02`'s specific gap stands (`doc-placement.php` is derivation, not enforcement) · `F-7`'s **authorship ≠ derivation ≠ enforcement** distinction holds.

**`SB-3` re-recalculated:** Amendment 1 reduced it to *"a change of coverage, not of kind"* on the premise that blocking **policy** enforcement already exists. **With `W-6`, that premise is itself contested.** ⇒ **`SB-3` returns to `OPEN / UNQUANTIFIED`: whether extending enforcement would change the conditions Track-1 lanes execute under cannot be settled until `OQ-H` is.** ⛔ Still not proposed; **the flag is restored to its original severity rather than left understated.**

---

## C2.3 · `W-3` — `OQ-H` and `OQ-I` registered

**Done in the document body:** the authoritative §15 register now lists **`OQ-A` … `OQ-I`** — nine open questions, matching the corrected decision set. ⛔ **Neither is decided.**

**`OQ-I`'s scope is corrected per `W-1`:** its disposal list now includes **the 925-line role-model document**, not only the diagrams and READMEs.

---

## C2.4 · `W-4` — provenance declared, history not repaired

**`OBSERVED`, from the record:** when Amendment 1 was produced, transitions 1–6 existed. The fold at that point placed the **architecture lane `HANDED_OFF`** and the **Verification #1 lane as mutation owner**. **There was no correction assignment and no correction grant.** The seq-1 `executionContext` already required *"The proposal MUST disclose its producing process, self-declared"* — **and Amendment 1 declared none.**

| | |
|---|---|
| **The fact** | **Amendment 1 was produced with no governed correction assignment, no correction grant, and no declared producer.** Its authorship is recoverable only by **inference**. `OBSERVED` (the record) + `INFERRED` (the authorship) |
| **Declared now** | the producing process of **Amendment 1** and of **this Correction #2** is **`claude-code-session:5e1dd9ee`** — self-declared, **not attestable** (`INV-ATTR-2`) |
| ⛔ **Not done** | **no assignment is back-dated · no grant is retro-registered · no owner is retroactively invented · the append-only record is not rewritten.** The gap stands in the record as a gap |
| **What changed structurally** | **this correction has an explicit governed producer, assignment (seq 10–12) and authority (`G-KOS-AIP04-CORRECTION2`)** — so the defect does not recur |

> ⭐ **The ARB's distinction, and it is the durable lesson of this work item:** **content can be independently assessable while authority and provenance are not established.** Amendment 1 was the first; the estate now has a governed instance of the same activity to compare it against. ⚠️ **`PROPOSED` observation, not adopted: this is `C-5` (separation attestation) manifesting on the PRODUCTION side rather than the verification side** — the platform could not tell who produced Amendment 1, for the same structural reason it cannot attest a verifier's separation.

---

## C2.5 · `W-5` — actor wording corrected

**`OBSERVED`:** `01-system-context.puml` contains **6** `Person()` declarations; `02-container-architecture.puml` contains **2**.

| Amendment 1 wrote | Corrected |
|---|---|
| *"`01` models **five separate** `Person()` actors"* | **6 `Person()` — five engineering-role actors PLUS `Person(po, "PO / ARB", "Human decision authority")`** |
| *"`02` collapses them into **ONE** actor"* | **2 `Person()` — one collapsed `"Engineering Roles"` actor PLUS `Person(po, "PO / ARB", …)`** |

**Both counts silently excluded the `PO / ARB` actor.** ⚠️ **The exclusion was not arbitrary — the PO/ARB is a human decision authority rather than an engineering role — but it was undeclared, which is the defect.** ✅ **The substantive point survives: the two diagrams disagree on the ENGINEERING-role decomposition (five separate vs one collapsed), and neither is governed.**

---

## C2.6 · `SB-1` — still not cleared, and now for a recorded pattern

> ## ⚠️ **`SB-1` REMAINS `CONFLICTED / REQUIRES RE-VERIFICATION`. This correction does not clear it and must not be read as clearing it.**

**`OBSERVED`:** **two consecutive independent verification passes have declined to issue an `SB-1` verdict, for the same reason — both verifiers hold Track-1 authorship.** Verification #1 is the Track-1 implementation engineer; Verification #2 holds Track-1 assurance authorship and **issued no verdict at all** on `SB-1`, deliberately deviating from its own scale rather than let a recusal be read as a clearance.

⚠️ **`INFERRED`: this is not evidence that `SB-1` is wrong. It is evidence that the estate currently lacks a verifier without Track-1 authorship** — a structural availability problem, not a substantive one. **`SB-1`'s substance is unchanged: ADR-AIP-04 must not assign conformance authority.**

✅ **The ARB's strengthened bar for Verification #3 — no Track-1 authorship AND no authorship of either ADR-AIP-04 correction — is the first configuration in which `SB-1` can be cleared.** ⛔ **Recorded as the condition; not requested as a favour.**

---

## C2.7 · Impact assessment on the overall thesis

### What changed materially in this correction

| | |
|---|---|
| **`F-3`/`F-3′` → `F-3″`** | 🔴 **the argument's BASIS is replaced.** From *"the titles are scarce/only personas/a five-actor sketch"* to *"the titles are abundant (104 files) but **no governed act adopts them**."* **Same conclusion, entirely different evidence — and a narrower, checkable one** |
| **`C-14`** | 🔴 **`absent` → (Amendment 1) `live, narrow, unmodelled` → `CONTESTED`.** Two successive withdrawals; **the corrected class is weaker than both** |
| **`SB-3`** | 🔴 **restored to `OPEN / UNQUANTIFIED`** — Amendment 1's reduction rested on a premise `W-6` contests |
| **`OQ-H`** | sharpened — the prior question (BC-2 vs BC-3) is now **second** to *"is there any `C-14` instance at all?"* |
| **`OQ-I`** | scope widened to include the 925-line document |
| **§13's convergence with "Option B"** | ⚠️ **can no longer be counted as independent corroboration** (`OPEN`, §C2.1) |
| **Provenance** | Amendment 1's gap **recorded, not repaired**; this correction is governed |

### What still survives, after two independent verification passes

**Unchallenged by either verifier:** all **mechanism evidence** (reproduced exactly by Verification #1) · ⭐ **the invariant asymmetry — every invariant BC-7 owns is mechanically enforced, almost every invariant outside BC-7 is declared only** · ⭐ **`C-5` cannot be discharged by an agent** (called the strongest argument in the proposal, and now *doubly* evidenced by `W-4`: the platform could not attest who produced Amendment 1) · **`C-10`** as the strongest evidenced gap · **`C-19`** failing its own five-test matrix ⇒ stewardship · **`F-7`** · **`F-10`** (ADR-C7 pre-emption) · **`F-11`** (Product Primacy) · **`F-9`/`F-9a`**, corroborated first-hand by **both** verifiers' own self-recorded STARTs · **parsimony** — no context created · **Track-1 safety** — verified clean twice.

> ### **The thesis after two corrections:** **the question is capability ownership, not role creation — and the load-bearing structural result is untouched.** ⛔ **But the evidence package has now been materially wrong three times about the same thing, and the honest summary is that this discovery has been better at DDD reasoning than at surveying its own estate.** **No finding is strengthened by this correction. `C-14` and `SB-3` are weaker than before.**

---

## C2.8 · Corrected PO/ARB decision set

**Supersedes Amendment 1 §A1.9 and §17. Both retained above. Changes in bold.**

| # | Decision | Minimum the act must state |
|---|---|---|
| **1** | Six-role model — adopt · reject · treat as candidate material | ⚠️ **CORRECTED input:** the titles are in **104 files**, most tracked, incl. a **925-line document drafting this ADR's own options**. ⛔ **The old framing — "personas vs architecture" — is dead.** The live question is: *does any governed act adopt them, and should one?* |
| **2** | `OQ-5` role-model ownership — BC-7's own · governance-published · **the proposed split** | unchanged; consumption leg is `INFERRED` |
| **3** | **`C-5`, `C-10`, `C-19` as unowned capabilities; `C-14` as `CONTESTED`** | accept/reject **each separately.** ⚠️ **CORRECTED: `C-14` is `CONTESTED`, not `live` — the sole Tier-1 asset may belong to `CAP-09`** |
| **4** | Owner for `C-10` — BC-1 or BC-6 (`OQ-B`) | the owner, or a deferral with a named trigger |
| **5** | Sequencing (`OQ-G`) | ⚠️ **CORRECTED: `C-14` cannot be sequenced until `OQ-H` establishes whether it has an instance** |
| **6** | **`SB-1`** — ADR-AIP-04 must not assign conformance authority **AND must be re-verified without Track-1 authorship** | ⚠️ **two passes have now failed to clear it for the same structural reason** |
| **7** | Who verifies **Correction #2** | **no Track-1 authorship AND no authorship of either ADR-AIP-04 correction** — the ARB's strengthened bar |
| **8** | **`OQ-H`** — `C-14`: **is there any instance at all**, and then BC-2 or BC-3? | ⚠️ **CORRECTED ordering per `W-6`** |
| **9** | **`OQ-I`** — disposition of the untracked material | ⚠️ **SCOPE WIDENED:** four `.puml` + two `README`s **+ the 925-line role-model document**. ⛔ *They cannot remain undisposed and be cited as evidence* |
| **10** | 🆕 **Amendment 1's provenance gap** (`W-4`) | that it is **recorded and not repaired**; whether any further governance consequence follows is the PO/ARB's |
| **11** | 🆕 **`PROPOSED` — extend the anti-mass-replace rule's scope** from *source code* to *any artifact with look-alike sites*, and pair the absence-claim rule with a **scope-verification** rule (§C2.0) | adopt · reject · defer. ⛔ **Proposed only; this correction adopts nothing** |

**Carried forward unchanged and unanswered:** `OQ-A` · `OQ-B` · `OQ-C` (ADR-C7) · `OQ-D` · `OQ-E` · `OQ-F` · `OQ-G` · `SB-2`.

---

**CORRECTION #2 DELIVERED · STOPPING.**
⛔ **No third discovery round · no redesign · no ADR-AIP-04 question answered · no capability, context, role, agent or service created · BC-7 untouched · Track 1, the shared L3 contract and all accepted semantic decisions untouched · `SB-1` NOT cleared · the untracked material NOT committed and NOT adopted · no evidence class upgraded · no history rewritten or deleted · no self-verification · no self-acceptance · work item NOT closed (`G-1`).**

**Next actor: Independent Verification #3 — no Track-1 authorship and no authorship of either ADR-AIP-04 correction. Only then: PO/ARB decision.**

**Correction #2 traceability:** `G-KOS-AIP04-CORRECTION2` · assignment seq 10–12 · Verification #2 (`W-1`…`W-6`, §1 recusal, verdict RETURNED) · Verification #1 (`V-1`…`V-4`) · Amendment 1 (`8008ee8a`) · discovery (`51910203`) · **exhaustive six-title survey: 104 distinct files, per-title counts, tracked status file-by-file, scope/method/completeness/observation-point declared, 2026-08-19** · **`docs/knowledgeos/brainstorming/ai-engineering-platform-6-role-model.md` (untracked, 925 lines, Roles 1–6, `ADR-AIP-04` Options A/B/C)** · `01-system-context.puml` (6 `Person()`) · `02-container-architecture.puml` (2 `Person()`) · `Phase-02.5-Certification-Plan.md` `CAP-09` (*"on a trip: halt, escalate, never retry, never modify"*, Tier-1 asymmetry, write-paths deliberately closed) · `registry.yaml` `AST-005/006/007/014` · proposal rows `C-5` / `C-14` re-edited **by verified line index** · `.claude/CLAUDE.md` source-editing standing rule (scope vs rationale) · `INV-ATTR-2`/`G-1`/`G-2`/`G-3`/`R8`/`R-34`/`P-2`.
