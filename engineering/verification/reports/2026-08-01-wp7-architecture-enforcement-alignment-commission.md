# WP-7 Architecture–Enforcement Alignment Commission

**Date:** 2026-08-01 · **Role:** Senior Principal DDD Architect · **Commission:** resolve **G-1**; determine where the implementation belongs; ensure every architectural **responsibility** is protected by an engineering gate — **without changing the domain model unless absolutely necessary.**
**Repository Integrity Gate:** ✅ PASSED — working tree clean; `feature/pb003` **in sync with origin** (the earlier 16 commits are pushed).

> ## RESULT — **a RECOMMENDED REALIZATION for G-1, pending ARB ratification.** It requires no change to the domain model and no change to the Deptrac model.
>
> **The reclassification the ARB proposed is the thing that unlocked it.** Once G-1 is read as an **Architecture–Enforcement Alignment Gap** — architecture correct, enforcement correct, *the mapping between them incomplete* — the question stops being *"which correct thing do we break?"* and becomes *"what did the plan state as a mechanism that was never a decision?"*
>
> **The analysis:** the plan said *"consume Adjudication's existing port."* **AP-2's invariant is "MAD keeps exactly one home" — a statement about the VALUE, not about the interface.** Election declares **its own consumer-side port**; its adapter reads **the one canonical config key**. TP-1 holds, AP-2 holds, Deptrac passes unmodified, and **every business-meaningful piece lands inside a gated path.**
>
> **⚖️ G-1 is NOT yet resolved. Two items require ARB ratification** (§6). **Resolution is the ARB's act, not the analysis's** — the sequence is *analysis → recommended realization → ratification → resolution*, never the reverse.

> ### ✍️ CORRECTION (recorded, not silently rewritten)
>
> **The first issue of this report declared "G-1 RESOLVED" in this block while §6 simultaneously recorded two items awaiting ARB ratification. Those cannot both be true.** The claim is corrected to **recommended realization pending ratification**; the analysis, the option judgements and §6 are unchanged.
>
> **This is the same error class the programme has already named:** *readiness is evidence, acceptance is authority.* I applied it correctly to WP-6 in the same session and then failed to apply it to my own recommendation. **An analysis cannot ratify itself.**

---

## 1. Reclassification — G-1 is an Architecture–Enforcement Alignment Gap

**Adopted, and it is more precise than "engineering placement."** The prior report's classification was accurate about the *owner* but imprecise about the *nature*:

| | Correct? | Evidence |
|---|---|---|
| **The architecture** | ✅ **Correct** | Reusing one canonical MAD home (AP-2) and forbidding direct cross-context code dependencies (TP-1) are both right |
| **The enforcement** | ✅ **Correct** | Deptrac would have flagged a *real* TP-1 violation. **The tool was not wrong** — it was about to do its job |
| **Their interaction** | ❌ **Incomplete** | No artifact stated *how* a context consumes a governance-owned value **housed in another context's config** without importing that context's code |

**Why the wording matters operationally:** "engineering placement" invites the fix *"put the file somewhere Deptrac doesn't look"* — which would have been **option (a): consistent and unguarded**, the worst outcome, and it would have looked like compliance. **"Alignment gap" forces the fix into the mapping**, where the actual defect is. **The ARB's reclassification changed the answer, not just the label.**

**Generalised for reuse:** *an Architecture–Enforcement Alignment Gap exists where every decision and every gate is individually correct, but no artifact states how a decision is realised **within** the gates. It is resolved by completing the mapping — never by moving code out of the gates' reach, and never by weakening a gate.*

## 2. G-1 — recommended realization

### 2.0 The four-level model (ARB refinement — adopted, and reusable beyond WP-7)

The two-level *invariant / mechanism* split was right but incomplete: it left unstated **where the invariant came from** and therefore **who may change what**. The full chain, with the authority at each level:

| Level | **Role** | WP-7 instance | **Authority** | May engineering change it? |
|---|---|---|---|---|
| **Business Policy** | **decides WHAT** | *Retention is governed by Q-2; a duration is a business policy, not a technical setting* | **Q-2 / ARB** | ❌ never |
| **Architectural Invariant** | **protects WHAT** | **MAD has exactly one canonical home** (AP-2) | **ARB** | ❌ never |
| **Mechanism** | **decides HOW** | **a consumer-side port** *(was: import Adjudication's port)* | **engineering, within the invariant** | ✅ **yes — the only substitutable level** |
| **Implementation** | **realizes HOW** | `ConfiguredEvidencePreservationDurations` | engineering | ✅ yes |

**Why the extra level earns its place:** it converts *"is this substitutable?"* from a judgement into a **lookup**. The whole of G-1 was a **level-3 substitution** — which is why it dissolved without touching the model. **Had the collision been at level 2, no amount of engineering ingenuity would have helped, and the honest answer would have been to go back to the ARB.** The four-level model tells you which situation you are in *before* you start looking for a clever fix.

### The layer verification rule (ARB refinement — adopted)

> **Can this layer change WITHOUT changing the layer above it?**
> **YES → it belongs at this layer. NO → you are modifying the wrong abstraction.**

**Tested against the cases this programme already has — it discriminates correctly, including retrospectively:**

| Change | Does the layer above change? | Verdict |
|---|---|---|
| Import Adjudication's port → **Election's own consumer-side port** | ❌ *MAD still has exactly one canonical home* | ✅ **correctly a Mechanism change** — this is G-1, and the rule licenses it |
| **AP-2's actual defect:** add a MAD key to a retention config | ✅ *the invariant "one canonical home" is destroyed* | ❌ **wrong abstraction** — presented as a mechanism choice, it was an invariant breach. **The rule retro-detects the defect the gates missed** |
| **AP-1's actual defect:** `max(1,$days)` in the adapter | ✅ *the policy "Q-2 decides durations" is overridden* | ❌ **wrong abstraction** — an implementation-level edit reaching two levels up to level 1 |
| Change the adapter's config key names | ❌ | ✅ Implementation |

**That the rule independently flags both AP-1 and AP-2 — the two defects that passed every automated gate and were caught only by human review — is the strongest available evidence that it is a real heuristic and not a restatement.**

### 2.1 The mechanism/invariant distinction that unlocks it

| The plan said | Status |
|---|---|
| *"MAD has exactly one home (`config/adjudication.php`)"* | 🔒 **INVARIANT — AP-2. Preserved absolutely.** A second home would be a retention config with its own MAD key |
| *"WP-7 **consumes the existing `AdjudicationDurations` port**"* | ✍️ **MECHANISM — a plan-level choice, never an architectural decision.** It was recorded in the same sentence as the invariant, which is why the two read as one thing |

**Consuming the *value* from its one home is what AP-2 requires. Importing the *interface* is one way to do that — and it is the way TP-1 forbids.**

### 2.2 Recommended realization — **Election declares its own consumer-side port**

This is Hexagonal orthodoxy: **a port belongs to the consumer that needs it, expressed in the consumer's language** — not to the provider.

| Element | Home | Precedent it mirrors |
|---|---|---|
| **`EvidencePreservationDurations`** *(port — names CW · MAD · LSM in **Election's** language)* | `app/Contexts/Election/Application/Port/` | the context's existing `Port/` folder (`ElectionExistencePort`, `AppliedDeterminationLedger`, `ReactionEventOutbox`) |
| **`ConfiguredEvidencePreservationDurations`** *(adapter — reads the canonical keys)* | `app/Contexts/Election/Infrastructure/Config/` | **`ConfiguredAdjudicationDurations`** — *exactly* this shape: injected `Config` repository, precedence resolution, **fail-closed with no clamping and no substitute value** |

**No cross-context code import exists anywhere in the result.**

### Why this is not a disguised context crossing

**MAD is not Adjudication's data. It is Q-2's policy**, physically housed in `config/adjudication.php`. Two contexts reading the same **governance-owned configuration value** depend on *governance*, not on each other: no model, no type, no lifecycle, no deployment coupling. **Adjudication is not upstream of Election here** — both are downstream of Q-2. The transition record already fixed **policy ownership → Q-2/ARB** as a distinct responsibility from either context's, and this realises exactly that.

### The honest cost — and its mitigation

**Duplicated *precedence logic*.** Adjudication's adapter resolves *organisation → election type → default*; Election's would too. **The value keeps one home; the resolution rule would have two implementations, and they could drift** — MAD resolving differently in the two contexts. That is a real risk, and it is a duplication of *behaviour* even though AP-2's letter is satisfied.

> **🔴 R-D1 — recommended gate: a test asserting both adapters resolve the same MAD for the same `(electionType, organisationId)`.** Cheap, exact, no false positives. **It protects AP-2's *intent* rather than its letter** — which is stronger than what exists today, since nothing currently guards either.

**Not recommended: extracting precedence into `Shared` on first repetition.** Two ~10-line adapters do not justify a platform abstraction, and `deptrac.yaml`'s own admission rule warns that Shared *"must never degrade into a general utility layer."* **If a third consumer appears, model it as a platform capability first** — per the standing anti-pattern rule that observations must not accrete into architecture.

### The three options, judged

| Option | Verdict |
|---|---|
| **(a)** Service outside `app/Contexts/` | ❌ **Rejected** — consistent but **unguarded**, and it would *look* like compliance. This is the trap the reclassification exposed |
| **(b)** Inside `Election/Application`, importing Adjudication's port | ❌ **Rejected** — a **genuine TP-1 violation**; Deptrac would be right to fail |
| **(c)** Extend the approved Deptrac model | ❌ **Not needed — and therefore not proposed.** It would have relaxed a correct gate to accommodate a mechanism that was never required |
| **(d) Election's own port + adapter reading the one canonical key** | ⭐ **RECOMMENDED REALIZATION** *(pending A-1 ratification)* — TP-1 ✔ AP-2 ✔ Deptrac unmodified ✔ fully gated ✔ **domain model unchanged** ✔ |

**Option (d) was not visible while G-1 was framed as placement.** It only appears once the question is *"how is the decision realised inside the gates?"* **Recommending it is the end of this commission's authority; adopting it is the ARB's.**

## 3. Where the implementation belongs — **coverage follows meaning, not the reverse**

**The principle applied, stated before the table so it can be checked against it:** `deptrac.yaml` warns that *"the architecture never evolves because the tool guessed something."* **So I did not pull code into contexts to obtain coverage.** I placed each piece by **business meaning**; the coverage is the *consequence*.

| Element | Business meaning? | Home | Gated? |
|---|---|---|---|
| `EvidencePreservationWindow` **VO** | ✅ a constitutional concept | `Election/Domain/` | ✅ **all three gates** |
| **Durations port** | ✅ policy consumption | `Election/Application/Port/` | ✅ Deptrac · PHPStan |
| **Application service** — *may this election's evidence be deleted yet?* | ✅ a question about an election | `Election/Application/` | ✅ Deptrac · PHPStan |
| **Config adapter** | ✅ fail-closed policy resolution | `Election/Infrastructure/Config/` | ✅ Deptrac · PHPStan |
| **Folder → election resolver** | ❌ **storage-format artifact** of `ElectionAuditService`'s directory naming | with the audit code | ❌ — **and correctly so** |
| **Traversal · deletion · the CLI** | ❌ mechanics | `app/Console/Commands/AuditCleanup.php` | ❌ — **proportionate: carries no policy** |
| **`--days` behaviour (C-9)** | behavioural | the CLI | ✅ **behavioural test** — the right instrument for a behaviour |

**Everything carrying policy or domain meaning is gated. Everything ungated carries neither.** That is the correct alignment, and it is a *stronger* claim than "everything is gated."

## 4. Responsibility Inventory (the ARB's second refinement)

**Adopted — and it is the better unit of protection.** A rule inventory asks *"is this rule checked?"*; a responsibility inventory asks *"could this responsibility silently move?"* — which is the failure mode DDD actually cares about.

| # | Responsibility | Holder (frozen) | Protecting constraint | Enforcement **after** alignment | Residual risk |
|---|---|---|---|---|---|
| R1 | **Business capability** — evidence outlives its challenge window | the capability itself | C-8 fail closed | ✅ **7C acceptance tests** | 🟢 |
| R2 | **Policy ownership** — the durations | **Q-2 / ARB** | C-1 never define/default/clamp · C-2 one home | ⚠️ **the port makes ownership visible; no gate prevents inventing a value** | 🔴 **HIGHEST** — *(C-1 automation still required)* |
| R3 | **Concept ownership** — EPW | **Election** | C-6 no crossing | ✅ **Deptrac `ElectionDomain: ~`** — the VO cannot import a port, a framework, or Infrastructure | 🟢 **strong** |
| R4 | **Construction** — the value's validity | **the VO's static factory** | C-3 business values only · C-4 the VO validates itself | ✅ C-3 by Deptrac + framework-free *(**hole:** a PSR clock)* · ❌ C-4 unguarded — **precedent available** | 🟠 |
| R5 | **Orchestration** | the **Application Service** | C-4 the service validates nothing | ❌ **unguarded** — construction can silently slide into orchestration | 🟠 |
| R6 | **Consumption** — the deletion guard | **Audit / Retention** | C-9 `--days` never overrides · C-8 | ✅ **behavioural tests** | 🟢 |
| R7 | **Infrastructure** — adapters translate, never decide | infrastructure | C-1 · C-10 | ⚠️ **C-1 unguarded** — *the AP-1 defect lived exactly here* | 🔴 |
| — | **Cross-context isolation** | TP-1 | C-6 | ✅ **Deptrac — now genuinely exercised**, because the code sits inside analysed paths | 🟢 |

**What the responsibility view reveals that the constraint view did not:** the two red rows — **R2 and R7 — are the same defect seen from both ends.** *Policy ownership is violated at the moment infrastructure decides a value.* **AP-1 was exactly that event.** Automating C-1 is therefore not one fix among three; **it is the single gate protecting the responsibility the constitution cares about most.**

**And R4/R5 are one pair, not two gaps:** construction sliding into orchestration is a *single* drift with two symptoms. **The construction-exclusivity precedent closes both at once** — which is why it, and not more coverage generally, is the second priority.

## 5. Coverage delta — what alignment buys

| | Before alignment | After alignment (option d) |
|---|---|---|
| Business-meaningful code in gated paths | **VO only** | **VO · port · service · adapter** |
| C-6 (no crossing) | ⚠️ contingent / **unobserved** | ✅ **actively enforced** |
| C-3 (constructor purity) | ✅ *(one hole)* | ✅ *(same hole — PSR clock)* |
| Deptrac model | at risk of relaxation *(option c)* | ✅ **unmodified** |
| Domain model | — | ✅ **unchanged** |
| Constraints executably enforced | **4 / 11** | **5 / 11** *(C-6 becomes real)* — **+3 more with the recommended gates: C-1 🔴 · C-4 · R-D1** |

**Alignment alone does not close the enforcement gap — it makes the existing gates actually apply.** The three recommended tests remain the substantive additions, and **C-1 is still the one where manual enforcement is insufficient.**

## 6. Items requiring ARB ratification — **stated, not assumed**

Neither reassigns a responsibility; both **sharpen** something already frozen. I record them because the standing rule is that **nothing becomes a decision by inference**.

| # | Item | Why it needs the ARB |
|---|---|---|
| **A-1** | **Mechanism substitution:** *"consume Adjudication's existing port"* → *"Election declares its own port; the adapter reads the one canonical key."* | AP-2's **invariant is preserved** (one home for the value). But the superseded wording appears in an accepted plan, so **the ARB should confirm the invariant, not the sentence, was the binding part** |
| **A-2** | **Boundary sharpening:** **Election *answers*** *"may this election's evidence be deleted yet?"*; **Audit/Retention *acts*** on the answer. | Consistent with what is frozen — the transition record already assigns Audit/Retention **"Consumption (acting on the answer)"**, and the ownership commission put **the question** in Election. **But the guard was described as one thing, and this names it as two.** Ratify the split; **no holder changes** |

**If the ARB declines A-1**, the fallback is option (c) — extend the Deptrac model — which **relaxes a correct gate** and should be a deliberate, recorded act, not a default.

## 7. Engineering Readiness — after alignment

| Check | Status |
|---|---|
| G-1 **analysed**, with a recommended realization | ✅ **YES** — option (d); domain model and Deptrac model both unchanged |
| G-1 **resolved** | ⚖️ **NOT YET — awaiting A-1 ratification.** *Resolution is the ARB's act* |
| Implementation placement **recommended** | ✅ **YES** — by meaning; coverage is the consequence |
| Every **responsibility** mapped to enforcement | ✅ **YES** — 8 rows, each with a named residual risk |
| Every responsibility **adequately** protected | ❌ **NO** — **R2/R7 (C-1)** remains 🔴; R4/R5 (C-4) 🟠 |
| Domain model unchanged | ✅ **YES** |
| Approved enforcement model unchanged | ✅ **YES** — no gate relaxed |

> ### **RECOMMENDATION TO THE ARB**
>
> **Ratify A-1 and A-2 — that act, and only that act, resolves G-1. Then RED is blocked by exactly two items, and both are known:**
> 1. ⛔ **WP-6 slice acceptance** — programme, the ARB's act;
> 2. 🔴 **C-1 automation** — deliverable **inside 7A**, where the adapter is written anyway.
>
> **Recommended, non-blocking:** **C-4** construction exclusivity · **R-D1** cross-adapter MAD agreement.
>
> **I still do not record a completion statement claiming full enforcement.** After alignment it would be **5 of 11** — better, and honest. **The architecture remains frozen; no architectural defect was found by this commission either.**

---

**Traceability:** implementation guard commission (G-1, the 11 constraints, the coverage facts) · transition record (the seven frozen responsibilities) · **AP-2** (one home — *the invariant*) · **AP-1** (fail closed; the defect that lived in R7) · **TP-1** and the approved model in `deptrac.yaml` (incl. the Shared admission rule and *"the tool verifies the architecture"*) · verified in-repo: `app/Contexts/Adjudication/Infrastructure/Config/ConfiguredAdjudicationDurations.php` (the adapter precedent — injected `Config`, precedence, fail-closed) · `app/Contexts/Election/Application/Port/` and `Election/Infrastructure/` (the folders the recommendation mirrors) · `ConstitutionalAssertionsTest::test_capability_decision_construction_exclusive`. **No code written; no domain model changed; no gate relaxed; no responsibility reassigned; two items referred to the ARB rather than assumed.**
