# Governance Topology Verification Report
## Four-Role + Human-in-the-Loop Model — verified against the running mechanism

**Acting as Governance · 2026-08-16 · read-only · target: `KOS-LCOM4-CONTRACT-001`**

> ## 🔴 PROVENANCE DISCLOSURE — read before anything else
> **This report was produced by the process holding the ACTIVE `S4-architecture-baseline` (architecture) assignment.** No governance assignment exists for it on `KOS-LCOM4-CONTRACT-001`, or on any work item. The role change was effected by a **prose instruction** ("you are governance phase now"), on the PO's explicit and repeated direction, and **the mechanism did nothing to prevent it**.
>
> **This is not an aside — it is live evidence for Q-3 and Q-7, and it is reported rather than worked around.** Under `A-4.3` the overlap is disclosed; under Option-② (registration-first) this report's own provenance is **irregular**, and Governance/PO should treat its conclusions as requiring independent confirmation.
>
> **Nothing was modified:** no contract, fixtures, PHP, Python, `KOS-LCOM4-CONTRACT-001`, `KOS-ARCH-BASELINE-001`, or any mechanism. No assignment created. No lane started.

---

## 1 · Target state, measured

```
KOS-LCOM4-CONTRACT-001   workflow: specification-correction   workItemState: OPEN   mutationOwner: NONE
  S4-architecture-lcom4-contract          architecture     COMPLETED
  S3-implementation-lcom4-contract-apply  implementation   HANDED_OFF
  S1-verification-lcom4-corrected         verification     CREATED
Grants: …-DRAFT (AUTHORIZED) · …-APPLY (AUTHORIZED) · …-REVERIFY (AUTHORIZED)
```

**A correction to the commission's premise.** §D asks whether the work item is *"safe to proceed to the implementation START."* **The implementation START already occurred** (seq 7, human act recorded), and the implementation assignment is now `HANDED_OFF`. The live gate is the **verification START** (`S1-verification-lcom4-corrected` = `CREATED`, seq 8–9 registered + handed off). §D is answered for the gate that actually exists.

---

## 2 · The eight questions

### Q-1 · Architecture cannot apply its own draft — **NOT VERIFIED**

`Observed` (source): the mechanism records *assignments, roles, states, grants*. It has **no representation of what work a session performs**. No transition, precondition, or query relates a file edit to a role. The `…-DRAFT` grant says "DRAFT ONLY" and the `…-APPLY` grant says "APPLICATION ONLY" — but grant `scope` is a **string that no code path evaluates** (`authorized` compares it by equality on demand; nothing consults it at transition time).

**What is actually true:** the record *represents* the separation faithfully — two assignments, two grants, architecture COMPLETED before implementation STARTed. **Representation is not prevention.** Architecture applying its own draft would have produced an identical record.

### Q-2 · Implementation cannot start without the human-approved grant and START — **PARTIALLY VERIFIED**

`Observed` (source, `assertTransitionAllowed` case `START`): **the G-3 conjunction is genuinely enforced.** START refuses without a non-empty `humanAct` *and* refuses without a recorded predecessor HANDOFF carrying its token. Both directions, both mechanical. This is the strongest enforcement in the system.

**The gap, measured:** **START never consults a grant** (0 references to grants in the START branch). A session can be STARTed with no grant existing at all. The "human-approved *grant*" half of the question is therefore **not** enforced — only the "human START" half is.

### Q-3 · Implementation cannot perform the later verification — **NOT VERIFIED**

`Observed`: nothing in the record identifies a **process**. Per the attribution investigation and re-checked here: no actor field; `executionContext` is a self-declared string, never compared; one git identity across all lanes. `R8` is enforced (`REGISTER` refuses a duplicate session id — role is immutable *per assignment*), but it does **not** prevent one process from operating `S3-implementation-…` and later `S1-verification-…`.

**This report is itself the demonstration:** one process is currently operating an architecture assignment while performing governance work. The same substitution at implementation→verification would be equally undetectable. `A-1.4`/`D-5` prohibits it; **only convention and human vigilance enforce it.**

### Q-4 · Verification can be assigned as a genuinely independent role/session — **PARTIALLY VERIFIED**

`Observed`: the record **can** represent it, and does — `S1-verification-lcom4-corrected` is a distinct assignment with a distinct role, its own registration, its own handoff, and its own grant (`…-REVERIFY`). It is not a relabelled implementation session: `R8` makes relabelling impossible, since a role change requires a new assignment.

**The limit:** the record can distinguish *assignments*, not *processes*. "Genuinely independent" in the sense of *a different executing process* is **not representable** (Q-3). So: independence of **assignment** ✅ enforced; independence of **execution** ❌ unrepresentable.

### Q-5 · Governance can record and route authority but cannot manufacture the human decision — **NOT VERIFIED**

`Observed`: `START` requires `humanAct` to be **non-empty**. It does not, and cannot, verify that the act occurred. The field is a free string written by whoever records the transition — in practice, Governance. `recordedBy` is likewise unvalidated on `REGISTER`/`HANDOFF`/`START` (constrained to `governance|human` only on `COMPLETE`/`CONTINUATION`).

**Stated plainly: the mechanism cannot distinguish a registered human act from a fabricated one.** What prevents manufacture is `A-3` + the registrar-delivery rule + the fact that `humanActRef` points to committed artifacts a human can check — **all convention, all after-the-fact.**

### Q-6 · The Human / PO / ARB remains required — **PARTIALLY VERIFIED**

`Observed`: the **form** of a human act is mechanically required at the activation gate — no START without `humanAct`. That is real, and it has fired against live sessions repeatedly (including twice against this process). Acceptance and qualification remain human by `R-34` and by practice in every record.

**Limit:** required-as-a-field ≠ verified-as-authentic (Q-5). The human is structurally *in the loop*; the mechanism cannot prove they were.

### Q-7 · Role separation enforced by the mechanism vs. described in documentation — **PARTIALLY VERIFIED**

`Observed` — **what the mechanism does enforce:**
- role must belong to the work item's declared role set (`REGISTER`)
- role is immutable per assignment; no `ROLE_CHANGE` edge exists — unknown transition types are refused outright
- only the current `mutationOwner` may hand off; bootstrap handoff only while no owner exists
- the G-3 conjunction (Q-2)
- STOPPED is sticky; only `CONTINUATION` exits, recorded by `governance|human`
- closure is a governance act (`COMPLETE` requires `governance|human`)
- ownership is single-valued by construction

**What it does not enforce — the behavioural half:** *what a role may do*. There is no capability model, no scope evaluation, no process binding. A session's actual work is related to its role **only by convention**.

**Live specimen, recorded rather than hidden:** this report exists because a prose sentence reassigned a process from Architecture to Governance mid-turn. The mechanism registered no objection because it has no edge to object with. **Role *assignment* separation is enforced; role *behaviour* separation is documentation.**

### Q-8 · Applies consistently without rediscovering the topology per work item — **PARTIALLY VERIFIED**

`Observed`: all **10** workflow records declare the identical role set `['governance','architecture','implementation','verification']`, across 6 distinct workflow types (`specification-correction`, `architecture-decision`, `platform-capability`, `operational-qualification`, `platform-implementation`, `evidence-experiment`). Consistency is factual and complete.

**But it is consistency by repetition:** the four-role topology is **re-declared inside every record**. No platform-level definition constrains it — `REGISTER` validates a role against *that record's own* `roles` array, which the record's creator supplied. A record declaring `['banana']` would be internally valid. So the topology is stable **because Governance keeps declaring it identically**, not because the platform defines it once.

---

## 3 · Nothing classified CONTRADICTED — with one qualification

No observed mechanism behaviour violates what the *mechanism* claims. The mechanism claims less than the documented model does, and it delivers what it claims.

**The qualification:** observed behaviour *does* contradict the **documented** model — the rulebook binds sessions to roles, and a process changed roles by prose today. Classified as a **model/mechanism divergence** (§C, G-1) rather than CONTRADICTED, because the mechanism never promised to prevent it.

---

## A · What the governance system demonstrably enforces today

| Enforced, mechanically | Evidence |
|---|---|
| Activation requires recorded HANDOFF **∧** recorded human START (both directions) | `START` branch; observed refusals against live sessions |
| Handoff requires token **and** tokenRef — no token, no handoff | `HANDOFF` branch |
| Only the current mutation owner may hand off | `HANDOFF` branch |
| Exactly one mutation owner (single-valued by construction) | schema |
| Role immutable per assignment; **no role-change edge exists** | `REGISTER` duplicate refusal + default-case refusal |
| Role must be in the record's declared role set | `REGISTER` branch |
| STOPPED is sticky; only `CONTINUATION` by `governance\|human` exits | STOPPED guard + `CONTINUATION` branch |
| Closure is a governance act | `COMPLETE` branch |
| Unknown transition types refused (no `CLAIM_OWNERSHIP`, no `ROLE_CHANGE`) | default case |

## B · What is only documented / conventional

| Convention only | Consequence |
|---|---|
| **Which work a role may perform** | Architecture could apply its own draft; the record would look identical (Q-1) |
| **Process identity** — nothing binds an assignment to an executing process | implementation→verification substitution is undetectable (Q-3) |
| **Authenticity of `humanAct`** — required as a field, unverifiable as a fact | Governance could manufacture activation (Q-5) |
| **`recordedBy` on REGISTER/HANDOFF/START** — unvalidated free string | any actor label is accepted at the three gates that matter most |
| **Grant scope** — a string no transition consults | START proceeds with no grant at all (Q-2) |
| **The four-role topology itself** — re-declared per record | stable by discipline, not by definition (Q-8) |
| `A-1.4`/`D-5` (implementation ≠ verification) and `A-4.3` (disclosure) | both unenforceable today |

## C · Governance-model gaps discovered

> **Reported as gaps requiring a separate decision or remediation. Not worked around, not repaired.**

- **G-1 · Role behaviour is unbound.** The mechanism binds role *assignment*, never role *conduct*. Demonstrated live by this report's own provenance.
- **G-2 · No process attribution.** Already the subject of `KOS-GOV-ATTRIBUTION-001` (ADP delivered, P-1…P-6 undecided). Q-3 and Q-4's limit both reduce to it.
- **G-3 · START does not consult grants.** Activation and authorization are fully decoupled in the mechanism; only convention keeps them together.
- **G-4 · `humanAct` authenticity is unverifiable**, and `recordedBy` is unvalidated at three of five gated transitions.
- **G-5 · The topology has no platform-level definition** — each record supplies its own role set.
- **G-6 · This report's own provenance is irregular** (prose-assigned role, no governance assignment). Governance should decide whether such output is admissible and, if so, under what disclosure.

## D · Is `KOS-LCOM4-CONTRACT-001` safe to proceed?

**The implementation START is past** (seq 7); the live gate is the **verification START**. Answering the gate that exists:

**Mechanically safe — with one convention-borne risk that the mechanism cannot cover.**

*Safe, on evidence:* the verification assignment is separately registered with its own role (`R8` guarantees it is not a relabelled implementation session) · its handoff is recorded with a token · its grant `…-REVERIFY` exists and is AUTHORIZED · `workItemState` is `OPEN`, not STOPPED · `mutationOwner` is `None`, so no ownership will be seized from a running lane · the START gate will mechanically refuse without a recorded human act.

*The uncovered risk:* **nothing in the mechanism can establish that the verification is performed by a different process than the implementation** (G-2). For this work item that risk is not theoretical — the same terminal has operated multiple roles across this programme, and is doing so in this very report. If the re-verification of the corrected LCOM4 reference is performed by the process that applied the correction, **the record will look correct and `A-1.4`/`D-5` will have been violated invisibly.**

**Governance recommendation (a recommendation, not a decision):** proceed to the verification START **only** with an explicit human act that names the independence requirement, and require the verification artifact to disclose which process performed it under `A-4.3`. That is the strongest control available today, and it is a convention — which is precisely the gap G-2 exists to record.

---

**Traceability:** `KOS-LCOM4-CONTRACT-001` record (10 transitions, 3 grants) · `workflow-state.php` `assertTransitionAllowed` (`:174–278`), START branch (`:226–239`), REGISTER (`:189–206`), HANDOFF (`:207–225`), COMPLETE (`:260–265`), default refusal (`:270–276`) · fold output this session · role-set census across 10 records · `session-resolve.php` role queries · `A-1.4`/`D-5` · `A-3` · `A-4.3` · `G-1`/`G-2`/`G-3` · `R8` · `R-34` · `KOS-GOV-ATTRIBUTION-001` ADP (P-1…P-6 open) · Option-② registration-first ruling.

---

> **Verification activity only. No implementation, correction, architecture design, or target-architecture work is authorized or performed by this report.**
