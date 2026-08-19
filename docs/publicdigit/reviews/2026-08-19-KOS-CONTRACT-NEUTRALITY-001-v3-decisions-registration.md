# Decision Registration — **V-3 decisions `D-1` … `D-5`** and the amended artifact-update gate

**Registered by:** Governance, on the delivered PO/ARB act · **2026-08-19**
**Work item:** `KOS-CONTRACT-NEUTRALITY-001` Track 1 · **Subject:** independent-verification finding `V-3`
**Recording process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`.

> ⚠️ **Disclosed, because it bears on what this act may and may not do.** This process authored the Track-1 binding (`4c6c1dac`) and the earlier V-3 determination (`99aeac7c`) that Governance classified as **evidence, not authority**. It is **barred by name** from `S4-architecture-v3-determination` (seq 38: *"REQUIRED: a process OTHER THAN the Track-1 implementer"*). **Recording a delivered decision is a GOVERNANCE act and is not that assignment** — the recording duty is discharged by whichever process holds the work. ⛔ **Nothing below is decided by this process; every disposition is the PO/ARB's, transcribed.**

> **Reading disclosed for correction:** the act is headed `RECORD:` and closes *"STOP after recording the five decisions"*, and its `D-3` asserts `D-2`'s outcome as settled fact (*"Because D-2 is OUT OF CURRENT SCOPE"*). **It is therefore read as PERFORMATIVE**, notwithstanding the words *"Recommended disposition"*. **If any disposition was intended as a recommendation awaiting selection, say so and this registration is corrected.**
>
> ✅ **RESOLVED by the PO/ARB act of 2026-08-19 (Amendment 1, below), which re-delivers all five in the imperative — `DECIDE:` — with no "recommended" framing. The reading was correct; the flag is closed.**

---

## Premises verified BEFORE recording — not assumed

**Governance checks the act's own stated grounds rather than transcribing them.** All `OBSERVED`:

| | Premise asserted by the act | Verification |
|---|---|---|
| **P-a** | *"dynamic method behavior is explicitly addressed by the contract"* | ✅ **TRUE** — `_variant_decisions_pinned.intra_class_calls` names *"dynamic method names (`$this->$name()`)"* verbatim |
| **P-b** | *"dynamic property access is not explicitly present in the pinned semantic decisions"* | ✅ **TRUE** — **no pinned decision mentions dynamic property access at all**; `_variant` says only *"edges = shared instance variable OR intra-class call"*, with no determinability language on the state side |
| **P-c** | *"no current golden fixture exercises it"* | ✅ **TRUE** — none of the ten fixtures contains a dynamic property access |
| **P-d** | `D-1`'s required semantics are expressible | 🟡 **PARTLY** — `QualifierKind::ComputedTarget` and `Determinability::NotDeterminable` **already exist in `L3`**; **the target-name half does not** — see `⚠️ F-D1` below |

---

# `D-2` — dynamic property access `$this->$p` · ✅ **DECIDED: OUT OF CURRENT SCOPE / DECLARED LIMITATION**

> **Recorded verbatim:** *"Dynamic property access is not currently part of the cohesion contract. It must not be silently interpreted as either an observed deterministic property access or an observed `NotDeterminable` access. Future inclusion requires a separate contract decision."*

**Rationale as given, and verified (P-a, P-b, P-c).**
⛔ **The prohibition is two-sided and that is the substance of it:** the absence may not be read as a *deterministic* access **nor** as a *not-determinable* one. **It is outside the contract, which is a third thing.**
**Recorded first because `D-3` depends on it.**

# `D-3` — state-access representation · ✅ **DECIDED: NOT APPLICABLE TO CURRENT SCOPE**

> **Recorded:** *"Do not create a new `StateAccess` determinability channel in this act."*

⭐ **This closes, by scoping rather than by modelling, the gap the earlier determination flagged and could not resolve** — that `StateAccess` carries no `Determinability` attribute. **The PO/ARB has removed the question rather than answered it, which is the cheaper and more honest disposal while `D-2` stands.**

# `D-1` — computed method name `$this->$m()` · ✅ **DECIDED: IN SCOPE**

> **Recorded:** an **observed behavioural reference** whose target method name is **computed** and therefore **not determinable** from the available facts.
> **Required semantic outcome:** reference **observed** · target **computed/unknown** · `determinability = NotDeterminable` · **must remain distinguishable from *"reference not observed"***.
> ⛔ **Must NOT use:** fake method names · sentinel strings · raw PHP source · parser objects.

**This answers the `V-3a` question returned by the verification and by the earlier determination. The verifier's finding is upheld: *seen-and-excluded* must be distinguishable from *unseen*.**

### ⚠️ `F-D1` — a consequence the act's closing sentence under-states, recorded so Governance routes it correctly

The act says *"The exact representation change is implementation work after the decision."* **That is true of one half and not of the other:**

| Half | Status |
|---|---|
| qualifier + determinability | ✅ **already expressible** — `ComputedTarget` and `NotDeterminable` exist in `L3` today. **Pure binding work** |
| **the target method NAME** | 🔴 **NOT expressible.** `BehaviourReference::$targetMethodName` is a **mandatory, non-nullable `string`** (`INV-L3-5`, totality: no default, no "unknown"), **and `D-1` forbids fake names and sentinel strings.** ⇒ **there is nothing legal to put in that field for a computed target** |

> ⇒ **`D-1` cannot be discharged by binding work alone.** Making the target name absent-but-legal touches the `L3` model's totality invariant, which is an **accepted-architecture** matter, not implementation discretion. **Governance's translation step must route the two halves separately.** ⛔ **No representation is proposed here.**

# `D-4` — library dispatch · ✅ **DECIDED: BOUNDED ENUMERATION ONLY**

> **Recorded principle:** *"Only explicitly enumerated and evidence-supported dispatch mechanisms are in scope."*
> ⛔ *"Do not define an open-ended 'understand PHP runtime/library semantics' capability."*
> *"The Architecture proposal may be used as the evidence base for the initial bounded list. If the list is rejected: the contract must explicitly state the limitation."*

**This answers the `V-3b` scope question. ✅ The principle is decided; ⬜ the LIST is not — it does not yet exist.** **Producing it is architecture work under `G-KOS-CONTRACT-V3-ARCH`.**

# `D-5` — dispatch vocabulary · ✅ **DECIDED IN PRINCIPLE · ⬜ selection OUTSTANDING**

> **Recorded:** *"If the dispatch scope is accepted: choose a language-neutral `L3` representation / `QualifierKind`. **Do not use PHP API names as domain vocabulary.**"*

✅ **The constraint is decided and is a real one:** no `QualifierKind::CallUserFunc` or any other PHP-API-derived value. ⬜ **The vocabulary itself is NOT chosen by this act**, and **Governance does not choose it** — that is architecture work, and it interacts with the standing `OPEN-1` record that the `L3` vocabulary is already PHP-derived in its first version.

---

# Artifact-update gate — ✅ **AMENDED as delivered**

| | Condition, recorded |
|---|---|
| ✅ | **ordinary existing-fixture evidence may continue** |
| ⛔ | **`V-3`-affected evidence may NOT be authored until the relevant `V-3` decisions are settled** |
| ⛔ | **no evidence may be used to assert conformance for an unresolved semantic category** |
| ⛔ | **the artifact-update assignment remains UNCREATED** until Governance records the post-decision authorization |

**Applied to the five decisions:** `D-2`/`D-3` are settled, so **dynamic-property evidence is not merely blocked — it is out of scope and must not be authored at all.** `D-1` is settled in disposition but **`F-D1` leaves its representation open**, so `D-1`-affected evidence stays blocked. `D-4`/`D-5` remain blocked pending the list and the vocabulary.

---

# Consequence for the open architecture lane — **narrowed, not moot**

`S4-architecture-v3-determination` is REGISTERED (seq 38) and HANDED_OFF (seq 39), **START not performed**. **The PO/ARB has now decided the dispositions that lane was commissioned to analyse.** ⇒ **its remaining scope is the work `D-4` and `D-5` delegate:** the enumerated dispatch list with its evidence, and the language-neutral vocabulary — **plus `F-D1`'s target-name representation question.** ⚠️ **The seq-38 bar stands: a process other than the Track-1 implementer.**

---

# What this act does NOT do

⛔ **No implementation** · ⛔ **no `expected.json` change** · ⛔ **no fixture change** · ⛔ **no artifact-update assignment created** · ⛔ **no implementation correction authorized** · ⛔ **no legacy calculator retired** · ⛔ **Track-1 acceptance NOT reopened — it stands within its authorized implementation scope** · ⛔ **no representation chosen for `D-1`** · ⛔ **no dispatch list enumerated** · ⛔ **no vocabulary selected** · ⛔ **13.3 and 13.5 untouched** · ⛔ **Architecture D not reopened.**

**Verified after recording:** `scripts/`, `app/`, `tests/` clean; `Lcom4Collector.php` `4136519b…`, `lcom4_collector.py` `5acb0e13…`, `expected.json` `173ab4ec…` unchanged.

---

**FIVE DECISIONS RECORDED · STOPPING.**
**Next actor: Governance — translate `D-1`…`D-5` into the appropriate specification and implementation authorization**, routing `F-D1`'s two halves separately and leaving the `D-4`/`D-5` work to the architecture lane under its standing separation bar.

**Traceability:** the PO/ARB act 2026-08-19 (recorded verbatim above) · `V-3` in `2026-08-18-…-track1-independent-verification.md` · the earlier V-3 determination `99aeac7c` (**evidence, not authority**) · `expected.json` `_variant` and `_variant_decisions_pinned.intra_class_calls` (PO/ARB 2026-08-16) · accepted implementation architecture `adc5c8e8` (`INV-L3-5` totality) · `BehaviourReference.php:21` · `QualifierKind`, `Determinability` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (AUTHORIZED, UNEXERCISED) · `G-KOS-CONTRACT-V3-ARCH` · lane seq 38–39 · `OPEN-1` (`AMD4`) · `INV-ATTR-2`/`G-2`.

---

# ⭐ AMENDMENT 1 — the five decisions RE-DELIVERED PERFORMATIVELY, and the evidence gate refined

**Registered by:** Governance, on the delivered PO/ARB `RECORD` act · **2026-08-19**
**Instruction honoured:** *"Use the existing authoritative V-3 decision register and Amendment A1. **Do not create a second decision register.**"* ⇒ **this amendment is appended to the existing register; no second register was created.**

> **Reading of *"Amendment A1"*:** the only `AMD1` bearing on the evidence gate is **`G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD1`**, registered at `2026-08-19-…-artifact-update-gate-amendment.md`. **This amendment is written to be read together with it.** *(The other `AMD1` in the cluster, `G-KOS-CONTRACT-V3-ARCH-AMD1`, concerns the architecture lane's separation bar and is untouched here.)* **If a different artifact was meant, say so and this reading is corrected.**

## A1.1 · What changed from the first delivery

| | First delivery (recorded above) | This act |
|---|---|---|
| **Modality** | *"Recommended disposition"* — I flagged the reading | ✅ **`DECIDE:` throughout — unambiguously performative.** The flag is closed |
| **`D-2`** | out of scope, with rationale | **+ a new prohibition:** ⛔ ***"Do not infer exclusion from implementation behavior."*** |
| **`D-1`** | *"exact representation change is implementation work"* | *"The exact implementation schema remains a **downstream implementation concern**"* — ⚠️ **see `A1.3`** |
| **Evidence gate** | *"`V-3`-affected evidence may NOT be authored"* | 🔴 **REFINED, and it PERMITS something the earlier form blocked** — see `A1.2` |

**`D-2` · `D-3` · `D-4` · `D-5` are otherwise unchanged in substance and stand as registered above, now as performed decisions rather than as a reading.**

⭐ **`D-2`'s new sentence is not decoration.** *"Do not infer exclusion from implementation behavior"* forecloses the move this programme has caught repeatedly — **treating what the binding happens to do as what the contract says.** It is Decision 1's rule (*the implementation is not the specification*) applied to an **omission** rather than to an output.

## A1.2 · Evidence gate — refined, and the refinement LOOSENS `AMD1`. Recorded, not blurred.

**As now decided, verbatim:**

> *"Ordinary existing-fixture evidence **may be produced**. However, such evidence **MUST NOT be used to assert overall contract conformance** for any semantic category affected by unresolved `V-3` decisions. **Dynamic-member expected evidence remains blocked** until the relevant decisions are **incorporated into the authoritative specification**."*

| | `AMD1` (artifact-update gate) | This act |
|---|---|---|
| ordinary existing-fixture evidence | ⛔ **blocked** — *"expected node/edge evidence"* withheld until `V-3` is settled | ✅ **PERMITTED** |
| dynamic-member expected evidence | ⛔ blocked | ⛔ **blocked**, and the release condition is **raised**: not *"until decided"* but ***"until incorporated into the authoritative specification"*** |
| conformance **assertion** | *(not separately addressed)* | ⛔ **blocked for any category affected by unresolved `V-3` decisions** |

> ⭐ **The refinement separates PRODUCING evidence from ASSERTING conformance with it — a distinction `AMD1` did not draw.** **Production is unblocked; the claim it could support is not.** ⚠️ **This is a deliberate loosening of `AMD1` on the first row and a tightening on the other two, and Governance records it as such rather than presenting the gate as unchanged.**

## A1.3 · 🔴 A conflict this act creates with the delivered architecture determination — RECORDED, NOT RESOLVED

**`D-1` states:** *"The exact implementation schema remains a **downstream implementation concern**."*

**The V-3 architecture determination — produced by the separated lane under `G-KOS-CONTRACT-V3-ARCH` + `AMD1`, and therefore the qualified authority — finds the opposite, from the code:**

> *"Can `$this->$m()` be emitted **as the type stands**? 🔴 **NO.** `targetMethodName` is a **mandatory non-nullable string with no value**. Filling it requires a **sentinel** (forbidden by `INV-L3-5`) or the **variable's source text** — which `INV-4`/`INV-L3-7` forbids."*
> *"`INV-L3-5` … and the requirement to represent an undeterminable target **are in tension** … The tension is real, narrow, and **must be resolved by decision, not by implementation preference**."*

**It enumerates three admissible resolutions — (a) make `targetMethodName` optional/absent for computed members · (b) add a distinct `L3` fact kind · (c) declare the construct a stated LIMITATION — and bars sentinels and source text outright.**

> ## ⚠️ **`D-1`'s disposition (IN SCOPE) and its closing sentence do not sit together.**
> **The disposition is decided and stands. But "downstream implementation concern" cannot be discharged as written: every available schema either changes an `L3` invariant or declares a limitation — both of which are contract acts, not implementation choices.**
>
> ⛔ **Governance does not resolve this and does not choose among (a)/(b)/(c).** **It is recorded so the translation step routes `D-1` to a representation decision rather than to an implementation ticket.** *(The same collision was also identified as `F-D1` in the first registration above and by the earlier determination `99aeac7c` that Governance classified as evidence only.)*
>
> ## 🔴 ERRATUM — issued 2026-08-19 by the recording process, against its own act
> **The sentence originally here read: *"three findings, TWO OF THEM FROM SEPARATED PROCESSES, agreeing."* That is FACTUALLY WRONG and it OVERSTATES INDEPENDENCE.**
> 
> | Finding | Producing process |
> |---|---|
> | `F-D1` (this register) | `claude-code-session:1c8b041b` |
> | the earlier determination `99aeac7c` | **`claude-code-session:1c8b041b` — the same process** |
> | the V-3 architecture determination | ✅ **a separated process** |
> 
> ⇒ **ONE finding comes from a separated process, not two. Two of the three are the Track-1 implementer, writing about its own type.**
> 
> ⚠️ **The corrected statement:** the collision was **raised twice by the implementer and CONFIRMED ONCE INDEPENDENTLY** — by the separated lane, which proved it from the code and went further, enumerating the three admissible resolutions. **That is a sound basis for the routing in `A1.3`; it is NOT convergence of three independent analyses, and must not be quoted as such.**
> 
> **This is the exact error class this estate has repeatedly caught — *similarity presented as independent corroboration* — committed here by the recording process itself. It is corrected in place by erratum rather than by rewriting the sentence, so the mistake stays visible.** ⛔ **The `A1.3` routing is UNAFFECTED: it rests on the separated determination's own code-level proof, not on a count of agreeing findings.**

## A1.4 · Standing after this amendment

| | State |
|---|---|
| `D-1` disposition · `D-2` · `D-3` · `D-4` principle · `D-5` constraint | ✅ **DECIDED** |
| **`D-1` representation** | 🔴 **OPEN — a contract decision, per `A1.3`** |
| **`D-4` enumerated list** | ⬜ **does not exist** — architecture work |
| **`D-5` vocabulary** | ⬜ **not chosen** — architecture work; interacts with `OPEN-1` |
| Ordinary existing-fixture evidence | ✅ **may be produced** |
| Dynamic-member expected evidence | ⛔ **blocked until in the authoritative specification** |
| Conformance assertion for affected categories | ⛔ **blocked** |
| Artifact-update assignment | ⛔ **remains UNCREATED** |
| **Track-1 acceptance** | ✅ **stands, within its authorized implementation scope — not reopened** |

## A1.5 · What this amendment does NOT do

⛔ **No second decision register created** · no implementation · no `expected.json` · no fixtures · no artifact-update assignment · no correction authorized · no legacy retirement · **Track-1 acceptance not reopened** · **no choice among `D-1`'s (a)/(b)/(c)** · no dispatch list · no vocabulary · `13.3`/`13.5` untouched · Architecture D not reopened · **the delivered V-3 architecture determination is neither accepted nor amended here.**

**Verified after recording:** `scripts/`, `app/`, `tests/` clean; `Lcom4Collector.php` `4136519b…`, `lcom4_collector.py` `5acb0e13…`, `expected.json` `173ab4ec…` unchanged.

**AMENDMENT 1 RECORDED · STOPPING.**
**Next actor: Governance — translate `D-1`…`D-5` into the specification and the bounded implementation / artifact-update authorizations, routing `D-1`'s REPRESENTATION as a contract decision (`A1.3`), not as implementation work.**

---

# ⬛ RESTORATION R1 — content deleted by `9c93e2f0`, re-appended additively · 2026-08-19

**Restored by:** Governance, under the PO/ARB record-integrity disposition act · **determination C(1)-additive**.
**⚠ NOTHING ABOVE THIS LINE WAS ALTERED.** The decided reading of `D-1`…`D-5` stands exactly as `9c93e2f0` wrote it. **This is not a merge of two interpretations** — it re-appends material that was deleted, dated and attributed, so the register carries its own history.
**Source of the restored text:** `e8b3842b` (the register as it stood before replacement). **Three of the four items are independent of whether `D-1`…`D-5` were decisions or recommendations**, so their restoration does not pre-empt that open Track-1 question.

## R1.1 · The advocacy qualification — restored because the ARB directed it be kept

> *"I would keep that qualification in the decision record."* — PO/ARB, 2026-08-19. **The instruction was standing and unexecuted after the replacement.**

## 2 · ⚠️ The qualification the ARB directed be kept in the decision record

**The determination's producing process disclosed material prior participation that the independence amendment did not cover.** It authored the **semantic clarification proposal** (`dab0f65c`), whose §4 **first proposed that the L3 fact model must carry a determinability attribute**. `V-3` is a finding about exactly that attribute.

⇒ **This determination asks whether an L3 requirement that same process proposed is entailed by the accepted model.** The determination states the consequence itself rather than minimising it: it must be weighed as **possible advocacy for the drafter's own earlier proposal**, not only as neutral entailment analysis — *"a conflict of a different kind from the two named bars"* — and it records that **the PO/ARB may route the determination elsewhere on this disclosure.**

**The two named bars were satisfied:** the process is neither the Track-1 implementer (`1c8b041b`) nor the V-3 finding's author (`2da45a86`). Self-declared and **not attestable** (`INV-ATTR-2`/`G-2`).

**A self-correcting consequence, recorded because it changes an accepted decision's completeness:** the **four contract silences** enumerated under Decision 13.5 **did not include dynamic property access.** It is a **fifth** silence. ⇒ **13.5's enumeration is incomplete on the record** — `D-2` is the decision that meets it.

## 3 · The five decisions — OPEN

| # | Decision | Admissible answers, as framed |

## R1.2 · The `D-2`/`D-3` coupling rule

### 3.1 The dependency order, registered

```
D-2  is the fact in the contract at all?
  ↓
D-3  if yes, how must L3 represent it?
  ↓
D-1  how is a computed method name represented?
  ↓
D-4  which library dispatch is in scope?
  ↓
D-5  how is that dispatch represented?
```

**The rule that makes the order binding rather than cosmetic:** ⛔ **D-2 and D-3 must not be split into contradictory decisions.** D-2 settles whether the fact is semantically in scope; D-3 settles the representation **only if it is**. *Deciding representation before membership designs a data structure for a fact that may not belong to the model.*

## R1.3 · The conformance-asserting residue

### 4.1 ⚠️ A residual difference returned to the PO/ARB, not resolved here

The determination's own condition (§10) is **narrower still** than this act's wording: *"YES for the golden-fixture evidence… **NO for `app/`-scoped or CONFORMANCE-ASSERTING evidence** until D-1/D-2 are answered."* The act frees *"ordinary existing fixtures"* and does not mention the conformance-asserting case.

**Consequence if left unresolved:** expected evidence authored over ordinary fixtures **but used to assert conformance** would be permitted by the act's wording and refused by the determination's. **Governance registers the act's wording as governing and flags the residue rather than choosing between them.**

*Also carried in `G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD2` and `-AMD3`, so this item survived the deletion in the append-only record even while it vanished from the document.*

## R1.4 · What restoration does NOT do

⛔ It does not decide whether `D-1`…`D-5` were PO/ARB decisions or recommendations — **that question remains OPEN** · ⛔ it does not alter the current register's text · ⛔ it does not reinstate the "OPEN" framing those sections originally sat inside · ⛔ it repairs no workflow history.
