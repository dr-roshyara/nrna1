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
| **C-5** | **Separation attestation** | `INV-ATTR-2`/`G-2`: separation is **Declared, NOT attestable** | 🔴 **NO OWNER** | **absent** |
| **C-6** | **Acceptance / closure** | `COMPLETE` refuses any `recordedBy` but `governance`/`human` (`G-1`); `R-34` | BC-7 (mechanism) + PO/ARB (authority) | **live** |
| **C-7** | **Knowledge curation & promotion** (`generated → authoritative`) | `CAP-03`; Knowledge-Constitution; `authority:` fields | BC-1 Knowledge Governance | 🔴 **NOT BUILT** — constitution + convention only |
| **C-8** | **Evidence identity & provenance** | 39 `tokenRef`s hard-code paths (`EKS-03`); *"historical location is evidence"* | ⚠️ **weak / implicit** | **fragile** |
| **C-9** | **Decision lineage & supersession** | ADR series; `AIP-11` supersede-never-in-place; decision registers | BC-1 (`INFERRED`) | **convention only** |
| **C-10** | **Knowledge distribution to the executing session** | `EKS-01`: *"Recording a rule is not sufficient"* | 🔴 **NO OWNER** | **absent** |
| **C-11** | **Context bootstrap & rehydration** | `CAP-01`, `AST-002`, SessionStart hook | BC-6 Session Continuity | **live** |
| **C-12** | **Session recording & archival** | `CAP-02`, `AST-003/004` | BC-6 | **live** |
| **C-13** | **Policy derivation** | `doc-placement.php` — exit 0 for every case | BC-1/BC-2 (`INFERRED`) | **live** |
| **C-14** | **Policy enforcement** | `EKS-02`: *"the rule exists, but the workflow does not make it hard enough to violate"* | 🔴 **NO OWNER** | **absent** |
| **C-15** | **Discipline gating & tripwires** | `CAP-06`, `AST-005/006/014` | BC-2 Implementation Guidance | **live, all Tier-2 non-blocking** |
| **C-16** | **Strategic & tactical modelling / ADR drafting** | `CAP-10/12`, frozen templates | BC-5 Design & Decision Support | **by reference** |
| **C-17** | **Conformance checking & verdicts** | `CAP-07/08/09`, `AST-007/010` | BC-3 Verification & Evidence | **partial** |
| **C-18** | **Adversarial review** | `CAP-11` — *"performed by role model + human acts"* | BC-4 Adversarial Review Support | 🔴 **NOT BUILT as component** |
| **C-19** | **Communication composition** (briefings · dossiers · decision surfaces · state explanations) | this estate's own artifact classes; `GOV-HUMAN-01`/`A-7` | 🔴 **NO OWNER** | **practised, undeclared** |
| **C-20** | **Platform self-governance / registry** | `CAP-13`, `registry.yaml` (`AST-009`) | BC-5 — **placement challenged** (`M-2`/ADR-C2) | **live as data** |

> ### ⭐ **Four capabilities have NO OWNER: `C-5` separation attestation · `C-10` knowledge distribution · `C-14` policy enforcement · `C-19` communication composition.** `INFERRED`: **these four, not the six roles, are the substance of ADR-AIP-04.**

## 4 · Capability boundaries — the eleven findings

| | Finding | Class |
|---|---|---|
| **F-1** | **The accepted model contains no Communication capability and no Communication context.** `CAP-01…14` and `BC-1…BC-7` — none is communication, while communication artifacts are pervasive | **OBSERVED** |
| **F-2** | ⭐ **The frozen "AI Responsibility Matrix" is NOT a role model.** It is an **activity × decision-right** matrix (*AI decides · AI recommends · Human approval · ARB-only · Sponsor-only*) and **contains no actors at all** | **OBSERVED** |
| **F-2a** | Its own stated pattern is **capability-shaped, not role-shaped**: *"the AI **decides** wherever the outcome is a derived fact of an executable check; it **recommends** wherever the outcome is a judgment; humans hold every gate where authority, value, or entrenchment changes."* ⇒ decision rights follow **the nature of the activity**, never the identity of the actor | **OBSERVED** |
| **F-3** | ⭐ **The six "Engineer" roles appear nowhere in the declared architecture, the capability map, or `registry.yaml`.** Where such titles occur they are **commissioning personas in document headers** (*"Principal Knowledge Engineer / Strategic DDD Architect"*) — an addressing convention for the human commissioner | **OBSERVED** |
| **F-3a** | ⇒ The six-role model is a **persona vocabulary mistaken for a platform role model** | **INFERRED** |
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
| **`C-14` Policy Enforcement** | 🟡 **a capability inside BC-2**, not a context — it shares BC-2's language and changes with the tripwire posture | `PROPOSED`, medium-high |
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

### 15.1 · ⚠️ Shared-boundary risks — Track 1 coupling · **RECORDED, NOT SOLVED**

**Per the commission's §6 boundary rule, discovery stopped at each of these rather than resolving them.**

| # | Affected boundary | Evidence | Consequence | Required decision |
|---|---|---|---|---|
| **SB-1** | 🔴 **Conformance authority** | §9 records *conformance evidence* as **shared between BC-1 and BC-3** (`OBSERVED`). **Track 1 has live, accepted decisions about conformance** — Decision 2 (node set + edge set + metric) and the finding that *conformance can never be evidenced by implementation agreement* | **Assigning "conformance evidence" to a Verification capability in ADR-AIP-04 could RELOCATE conformance authority that Track 1 currently exercises** | ⛔ **STOP. Returned to Architecture / PO-ARB as a separate act. ADR-AIP-04 must NOT assign conformance authority** |
| **SB-2** | 🟡 **Evidence-chain location coupling** | `EKS-03`: 39 `tokenRef`s hard-code paths; **Track 1's own records live in that chain** | A `C-8` fix (`PROPOSED I-K2`, location-independent identity) would touch records Track 1 depends on | ⛔ **Do not act. Sequence any `C-8` work against Track 1** |
| **SB-3** | 🟡 **`C-14` enforcement posture** | `C-15` tripwires are all **Tier-2 non-blocking** (`OBSERVED`) | Making enforcement blocking would change the conditions under which **Track 1 lanes** execute | ⛔ **A posture change needs its own authorization; not proposed here** |

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
