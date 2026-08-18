# `KOS-CONTRACT-NEUTRALITY-001` — **V-3 Architecture Determination**

**Status: 🟡 PROPOSAL. It decides nothing. Every decision below returns to the PO/ARB.**
**Assignment:** `S4-architecture-v3-determination` (seq 38 REGISTER · 39 HANDOFF · **40 START**) · **Grant:** `G-KOS-CONTRACT-V3-ARCH` **+ `-AMD1`** · **Role:** Architecture Engineer
**Date:** 2026-08-19 · **Next actor: PO/ARB.** `R-34`/`P-2`: this lane does not verify or accept its own determination.
*(Filename dated 2026-08-19 per the grant's own note: the act specified 2026-08-18; a faithful correction, not a deviation.)*

> ## **Track 1 remains ACCEPTED within its authorized implementation scope. V-3 does not retroactively invalidate that acceptance, and this determination does not reopen it.**

---

## ⚠️ 0 · Independence gate — discharged, with a disclosure the amendment does not cover

| Gate item | Result |
|---|---|
| Excluded (1) — Track-1 **implementation** producer | `claude-code-session:1c8b041b` (delivery `4c6c1dac`), also author of the prior determination `99aeac7c` |
| Excluded (2) — author of the **V-3 finding** / Track-1 verification `50d55d26` | `claude-code-session:2da45a86`; its lane is this assignment's **registered predecessor** |
| **This process** | **`claude-code-session:5e1dd9ee` — NEITHER.** ✅ The bar does not apply; the determination proceeds |

**Self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`); per `INV-ATTR-1` no gate reads process identity, so the bar rests on this disclosure and on where the PO/ARB started the session.

> ### 🔴 **Gate item 7 — other material prior participation, stated plainly rather than minimised**
> **This process authored the `SEMANTIC CLARIFICATION PROPOSAL` (`dab0f65c`), whose §4 first proposed that the L3 fact model must carry a `DETERMINABILITY` attribute — *determinable · out-of-frame · not-determinable* — and observed that the distinction then lived only in both implementations' control flow and was **not carried as a fact**.**
> **V-3 is a finding about exactly that attribute.** ⇒ **This determination asks whether an L3 requirement THIS PROCESS ITSELF PROPOSED is entailed by the accepted model.** A reader must weigh it as **possible advocacy for the drafter's own earlier proposal**, not only as neutral entailment analysis. It is a conflict of a **different kind** from the two named bars, the amendment does **not** exclude it, and **the PO/ARB may route this determination elsewhere on this disclosure.**
> **Also disclosed:** this process performed the Stage-2 **breadth verification** (76 probes; produced `NEW-5`, `O-2`, the four named silences) and the Track-2 ADR-AIP-04 discovery/amendment/correction (which cite Track-1 decisions but modify no Track-1 artifact).
> ⚠️ **And a self-correcting consequence:** the "four contract silences" this process enumerated **did not include dynamic property access.** §2 shows that is a **fifth** silence. **The prior enumeration was incomplete.**

**`99aeac7c` treated as the grant requires: EVIDENCE AND PROPOSAL MATERIAL ONLY. It was not read as an authority and no conclusion below is inherited from it — every conclusion is derived from code and from the governed record, cited inline.**

---

## 1 · V-3 evidence

### 1.1 The finding, as the Track-1 verification stated it (`OBSERVED`, cited)

> **`V-3` — dynamic own-behaviour references are UNSEEN, where the pinned decision requires SEEN-AND-EXCLUDED.** *"`$this->$m()`, `call_user_func([$this,'m'])` and `$this->$p` produce **no fact and no exclusion** (`excluded=[]`), while `$x::b()` is correctly reported as `NotDeterminable`."* Severity **MEDIUM-HIGH**, *metric-neutral*; **blocks acceptance: NO for the delivery, YES for asserting conformance**; *"⚠️ Requires an Architecture determination — the accepted `L3` model does not state whether the binding must emit a fact for a dynamic member, and verification must not decide it."*

### 1.2 Independently measured, not inherited (`OBSERVED`)

A probe was run through the **actual** accepted pipeline (`PhpFactExtractor::extract` → `AnalyseCohesion::observe`):

| Method | Construct | L3 facts emitted | Exclusion recorded |
|---|---|---|---|
| `dynMethod` | `$this->$m()` | 🔴 **none** | 🔴 **none** |
| `dynProp` | `$this->$p` | 🔴 **none** | 🔴 **none** |
| `libDispatch` | `call_user_func([$this,'b'])` | 🔴 **none** | 🔴 **none** |
| `computedClass` | `$c::b()` — **control** | ✅ `b : ComputedTarget / NotDeterminable` | ✅ `NotDeterminable` |
| `plainCall` | `$this->b()` — **control** | ✅ `b : InstanceReceiver / Determinable` | — (included) |

**Observation:** `value=6 · nodes=7 · edges=1 · excluded=[{computedClass → b, NotDeterminable}]`. **Exactly one exclusion is recorded, and it is not one of the three V-3 constructs.**
**Falsification condition:** any of the three emitting a fact or an exclusion. **None did.**

### 1.3 The mechanism, located in the binding (`OBSERVED`)

`PhpFactExtractor::factsIn()` — the `$this->` branch requires **`$this->sig[$i+2][0] === T_STRING`**. A dynamic member puts **`T_VARIABLE`** at `$i+2`, the guard fails, and the token is **skipped with no fact of any kind**. The `::` branch reaches `classifyQualifier()`, which returns `[ComputedTarget, Undetermined, NotDeterminable]` for a `T_VARIABLE` qualifier — **which is why the control case works.**

> ⭐ **The distinguishing factor is NOT "dynamic vs static". It is WHICH PART IS COMPUTED.** `$c::b()` computes the **class** while the **method name stays a literal**; `$this->$m()` keeps the receiver and computes the **name**. **The accepted vocabulary handles a computed *qualifier*; it was never asked to handle a computed *member name*.** `INFERRED`, from the two code paths and the measured asymmetry.

### 1.4 What the contract already DECIDES (`DECIDED`, verbatim)

`expected.json._variant_decisions_pinned.intra_class_calls` (PO/ARB, 2026-08-16):

> *"EXCLUDED AS NOT DETERMINABLE: calls whose target cannot be resolved from the class in isolation — **dynamic method names (`$this->$name()`)**, **callable arrays (`call_user_func([$this,'m'])`)**, and any runtime-computed target; a real dependency may exist, but the analysis cannot determine the target reliably. **THESE TWO EXCLUSIONS ARE DIFFERENT CLAIMS AND MUST NOT BE MERGED**: the first says there is nothing to connect to; the second admits we cannot see it."*

**Two of the three V-3 constructs are NAMED in a DECIDED clause.** The third — **`$this->$p`** — **is named in no pinned key.** The eight pinned keys are `constructors · intra_class_calls · first_class_callables · static_methods · trait_methods · inherited_methods · magic_methods · own_class_name_resolution`, and `intra_class_calls` governs **calls**. ⇒ **`$this->$p` is contract-SILENT.** *(`OBSERVED`. ⛔ Silence is recorded as silence — it is **not** read as exclusion, and the calls clause is **not** extended to state by analogy.)*

---

## 2 · V-3A analysis — dynamic member references

**The grant forbids assuming the two are one domain fact. They are not.** Investigated separately.

### 2.1 The domain facts (`OBSERVED` where cited, `INFERRED` where derived)

| | **Dynamic method invocation** `$this->$m()` | **Dynamic property access** `$this->$p` |
|---|---|---|
| **Domain fact** | *a method refers to a behaviour of the analysed unit whose **identity** cannot be determined in scope* | *a method touches state of the analysed instance whose **identity** cannot be determined in scope* |
| **L3 type** | `BehaviourReference` — **6 mandatory attributes** (`INV-L3-5`: *"no default and no 'unknown'"*) | `StateAccess` — **2 attributes**: `propertyName`, `accessMode` |
| **Determinability channel** | ✅ **exists** — `Determinability{Determinable, NotDeterminable}` | 🔴 **DOES NOT EXIST** |
| **"Undetermined" relation** | ✅ `TargetUnitRelation::Undetermined` | 🔴 none |
| **`ComputedTarget` slot** | ✅ `QualifierKind::ComputedTarget` | 🔴 none |
| **Mandatory name field** | `targetMethodName: string` — **non-nullable** | `propertyName: string` — **non-nullable** |
| **Contract status** | ✅ **DECIDED** — named in `intra_class_calls` | 🔴 **SILENT** — named nowhere |

> ### ⭐ **The asymmetry is TWO asymmetries, and the second is the one that matters more**
> **(a) TYPE asymmetry** — a behaviour reference has a determinability channel; a state access has none. **`OBSERVED` from the two class definitions.**
> **(b) CONTRACT asymmetry** — the method case is **decided**; the property case is **unruled.** **`OBSERVED` from the pinned keys.**
> ⇒ ⛔ **They cannot share one remedy.** The method case needs a **representability** decision; the property case needs a **semantic** decision *first*, and only then a model question.

### 2.2 Must L3 preserve *observed + target-cannot-be-determined* as distinct from *not observed*? — **YES, and it is already DECIDED, not proposed**

`DECIDED`, on three independent grounds, none of which is this drafter's opinion:
1. **the contract's own never-merge clause** (§1.4) — *"the second admits we cannot see it"*;
2. **`Determinability`'s own docblock**, which restates it: *"A determined target that is simply a DIFFERENT unit is Determinable; it is excluded as `NotTheAnalysedUnit`, never as `NotDeterminable`… those two claims must never be merged"*;
3. **`ExclusionReason` encodes `NotDeterminable` and `NotTheAnalysedUnit` as separate cases**, and `EdgeRules` row 2 comments *"NOT the same claim as row 8/9."*

⇒ **The distinction is not a candidate invariant. It is decided policy that the L3 population currently fails to honour for three constructs.**

### 2.3 Is it already ENTAILED by the accepted L3 model? — **partly, and the residue is exactly the decision**

| Question | Answer |
|---|---|
| Does L3 have the **classification** vocabulary? | ✅ **YES** — `ComputedTarget` + `Undetermined` + `NotDeterminable` exist and are already used for `$c::b()` |
| Does **L4** need any change to consume it? | ✅ **NO** — `EdgeRules` row 2 already excludes it as `NotDeterminable`, kept distinct from rows 8/9. **`OBSERVED`. The L4 layer is ready today** |
| Can `$this->$m()` be emitted **as the type stands**? | 🔴 **NO.** `targetMethodName` is a **mandatory non-nullable string with no value**. Filling it requires a **sentinel** (forbidden by `INV-L3-5`) or the **variable's source text** — which `INV-4`/`INV-L3-7` forbids, because L3 *"carries NO provenance… which is what makes it impossible for a cohesion rule to fall back to source syntax"* |
| Can `$this->$p` be emitted? | 🔴 **NO, doubly** — no value for `propertyName` **and no determinability channel at all** |

> ## **`INFERRED` — the determination's core result:** **the accepted model DECIDES the distinction and PROVIDES the classification, but does NOT provide a representable form for a computed MEMBER NAME.** ⇒ **V-3a is *not* fully entailed. What is missing is a naming/representation rule, and choosing one is a specification act.**

### 2.4 If a new invariant is required, what exact distinction must it preserve?

**The distinction to preserve is not "dynamic vs static" but *three* states that must never collapse into two:**

```
    NOT OBSERVED                  no such site exists in the unit
    OBSERVED · DETERMINABLE       a site exists and its target is identified
    OBSERVED · NOT DETERMINABLE   a site exists and its target CANNOT be identified in scope
```

⛔ **Today the third collapses into the first for the three V-3 constructs.** The wording is drafted in **§11**.

### 2.5 L4 consequences (`OBSERVED`)

**None required.** `EdgeRules::verdict()` already returns `exclude(NotDeterminable)` for `Determinability::NotDeterminable` **or** `QualifierKind::ComputedTarget`. ⚠️ **But `EdgeRules::verdict()` takes only a `BehaviourReference` — there is NO L4 verdict function for `StateAccess` at all**, so a determinability-bearing state fact would have **no L4 consumer**. ⇒ **the property arm has an L4 consequence the method arm does not.**

### 2.6 L5 consequences — **metric-neutral, and that is derived, not assumed** (`INFERRED`)

**Correcting V-3 would NOT change any LCOM4 value**, because the correct verdict for a not-determinable reference is **exclusion** — the same effect as the current silence. Confirmed by the measured probe: `value=6` with the three constructs invisible; had they been emitted, L4 would have excluded them and the value would still be `6`.
**Same for state:** an unknown property name cannot serve as a co-touch grouping key (`GraphBuilder` groups by `$access->propertyName`), so it forms no edge either way.

> ⭐ **Consequence for the PO/ARB, and it narrows the decision usefully: V-3 is NOT a metric defect. No correction is needed to protect the number.** **The damage is confined to Level-1/Level-2 evidence and to the truthfulness of a claim the contract requires the analysis to make.**

### 2.7 Decision 13.7 evidence consequences (`OBSERVED` + `INFERRED`)

13.7 promotes **node set + edge set + final metric** into the conformance specification.

| Level | V-3 impact |
|---|---|
| **Level 1 — node set** | ✅ unaffected — all methods are still nodes |
| **Level 2 — edge set (incl. the exclusion record)** | 🔴 **DEFEATED for these constructs.** Declared evidence saying *"excluded as NotDeterminable"* is **unsatisfiable**, and `excluded=[]` cannot be distinguished from *"nothing was there"* |
| **Level 3 — metric** | ✅ unaffected (§2.6) |

⇒ **`INFERRED`: V-3 is a pure Level-2 evidence defect.** That is precisely why it *"blocks conformance, not the delivery"* — and it explains why 55 green tests pass: **no test asserts an exclusion record for these constructs.**

---

## 3 · V-3B analysis — library dispatch

### 3.1 Is library-dispatch semantics inside the language-binding boundary? — **the contract has already put it inside the CONTRACT's scope; the binding question is separate**

**`OBSERVED`, and it reframes the grant's question:** `call_user_func([$this,'m'])` is **named verbatim in a DECIDED pinned clause** (§1.4). ⇒ **It is not open whether library dispatch is in *semantic* scope. The contract decided it is.**

> ### ⭐ **The genuinely open question is therefore narrower and different: what KNOWLEDGE must the PHP binding possess to honour a clause the contract has already decided?**

### 3.2 Language/source facts vs runtime/library semantics (`OBSERVED`)

**Separating them, as the grant requires, produces a result that cuts against the intuitive framing:**

| | `$this->$m()` | `call_user_func([$this,'m'])` |
|---|---|---|
| What is computed | **the method name** | **the dispatch mechanism** |
| Is the method name visible **in the source**? | 🔴 **no** — a variable | ✅ **YES — a literal string `'m'`** |
| Is the receiver visible? | ✅ `$this` | ✅ `$this` |
| Knowledge required beyond tokens | none | **that `call_user_func`'s first argument `[$obj, 'name']` denotes a method invocation** — a **library semantic** |

> ⭐ **So `call_user_func([$this,'m'])` is MORE statically determinate than `$this->$m()`, not less.** Its target name **is** in the source. **What is unknown is not the target but the meaning of a library function.** `INFERRED`, and it is the load-bearing distinction of V-3b.

**Consequence:** the two are in **different bounded responsibilities.** `$this->$m()` is a **language-syntax** fact the binding can see and cannot name. `call_user_func([$this,'m'])` is a **library-semantics** fact the binding can name **only if it is granted a table of dispatch functions.** ⛔ **That table is PHP-runtime knowledge, and admitting it enlarges the binding's knowledge boundary.**

### 3.3 Must an observed dispatch be represented in L3 so it is not confused with absence?

**`INFERRED`: yes — the never-merge clause makes no exception for library dispatch, and the same `excluded=[]` ambiguity applies.** ✅ **And unlike the method case, it is REPRESENTABLE TODAY:** `targetMethodName='m'` is available from the literal, so a fact can be emitted with **no sentinel and no provenance leak.**
⚠️ **One residue:** `QualifierKind` has **no case for library dispatch**. Using `ComputedTarget` would misdescribe it (the *target* is literal; the *dispatch* is computed). ⇒ **a narrow vocabulary decision is required.** ⛔ **Not taken here.**

### 3.4 Evidence of scale — recorded so the decision is not mis-sized (`OBSERVED`)

| Construct | Occurrences in `app/` | In the ten golden fixtures |
|---|---:|---:|
| `$this->$…` (dynamic member) | **3 files** | **0** |
| `call_user_func([$this, …])` | **0 files** | **0** |

⚠️ **Stated carefully in both directions: zero occurrences is NOT evidence of no gap** — the contract names the construct, so the specification gap is real. **And it is not evidence of urgency either.** ⛔ **Do not broaden the binding merely because it is technically easy** *(the grant's own instruction, and the scale evidence removes the pressure to).*

---

## 4 · L3 semantic implications

**Three distinct representability cases, of increasing severity — `OBSERVED` from the types plus the measured probe:**

| # | Construct | Target name in source? | Determinability channel? | Representable **today**? | What is needed |
|---|---|:---:|:---:|:---:|---|
| **0** | `$c::b()` *(control)* | ✅ | ✅ | ✅ **already emitted** | nothing |
| **1** | `call_user_func([$this,'b'])` | ✅ literal | ✅ | 🟡 **type YES, binding NO** | **bounded binding correction** + one narrow vocabulary decision |
| **2** | `$this->$m()` | 🔴 | ✅ | 🔴 **no** | **a representability DECISION** (see §8) |
| **3** | `$this->$p` | 🔴 | 🔴 **none** | 🔴 **no** | **a CONTRACT decision first**, then a model question |

> ## ⭐ **V-3 is not one gap. It is three, and they need three different governed answers.** Treating V-3 as a single item is the principal risk this determination exists to prevent.

**Layer discipline, kept explicit as the grant requires:**

```
LANGUAGE BINDING   sees tokens; may not invent names; may not leak provenance into L3
      ↓            ← cases 1–3 all fail HERE, for three different reasons
L3 FACT MODEL      closed vocabularies; ALL attributes mandatory (INV-L3-5); no provenance (INV-4/INV-L3-7)
      ↓
L4 COHESION        ✅ ready — EdgeRules already excludes NotDeterminable, distinctly from NotTheAnalysedUnit
      ↓            ⚠️ but has NO StateAccess verdict function at all
L5 METRIC          ✅ unaffected — the correct verdict is exclusion either way
      ↓
CONFORMANCE        🔴 Level-2 evidence defeated
```

⛔ **Implementation mechanics were not allowed to become domain semantics:** the `T_STRING` guard is the *cause*, not the *rule*. The rule is the contract's never-merge clause.

---

## 5 · Binding-boundary implications

| | Case 2 — `$this->$m()` / `$this->$p` | Case 1 — `call_user_func` |
|---|---|---|
| **Bounded context** | PHP language binding (L1/L2) | PHP language binding **+ PHP runtime/library knowledge** |
| **Knowledge required** | tokens only — **already possessed** | **a declared set of dispatch functions** — *not* currently possessed |
| **Reason to change** | PHP **syntax** changes | the **library surface** changes — *a different and faster clock* |
| **Binding responsibility** | report the site and that its target is undeterminable | recognise a dispatch idiom and report the named target |

> ⭐ **`INFERRED` — a real DDD boundary result: cases 1 and 2 have DIFFERENT REASONS TO CHANGE.** Language syntax changes with PHP releases; the set of dispatch idioms changes with libraries and conventions. **Binding them into one rule would couple two independent change clocks** — which is exactly the defect a bounded boundary exists to prevent. ⇒ **the library-dispatch scope should be a SEPARATELY DECLARED, ENUMERATED list, not an open-ended capability.** Drafted in **§12**.

---

## 6 · Decision 13.7 evidence implications

**`OBSERVED`:** no golden fixture contains any V-3 construct; `app/` contains 3 files with dynamic members and 0 with `call_user_func([$this,…])`.

| Evidence target | Status under V-3 |
|---|---|
| **The ten golden fixtures** | ✅ **NOT AFFECTED** — expected node/edge evidence for them is unblocked by V-3 |
| **Any corpus containing dynamic members** (incl. `app/`) | 🔴 **BLOCKED** — Level-2 evidence would be unsatisfiable or silently wrong |
| **A conformance CLAIM** | 🔴 **BLOCKED** until the three cases are answered |

⇒ **`INFERRED`: the artifact-update gate is narrowly blocked, not wholly blocked.** §10 states this as the gate condition.

---

## 7 · Architectural consequences

1. **`OBSERVED`: the L4 and L5 layers require no change.** The gap is entirely at the **binding → L3** boundary. **This is the most constraining result in the determination** — it means no cohesion-semantics work is implied.
2. **`INFERRED`: `EXCLUDED ≠ UNSEEN` is a decided architectural principle that the L3 *population* violates**, while the L3 *model* honours it. **Model correctness and population correctness are different claims** and the Track-1 verification separated them correctly (Verdict B: *"two decided facts are not populated"*).
3. **`INFERRED`: `INV-L3-5` ("all attributes mandatory, no 'unknown'") and the requirement to represent an undeterminable target are in tension for a computed member name.** The tension is real, narrow, and **must be resolved by decision, not by implementation preference** — the three admissible resolutions are in §8.
4. **`OBSERVED`: `StateAccess` is the only L3 fact type with no determinability channel**, and the only one with no L4 verdict function. ⚠️ **Whether that asymmetry is a defect or a justified design is itself undecided** — `INFERRED`: it is *defensible*, because a state access's name is a **grouping key** rather than a **resolution target**, so "undeterminable" means "ungroupable" rather than "unresolvable". ⛔ **Offered as analysis, not as an answer.**
5. **`OPEN`: dynamic property access is a FIFTH contract silence**, additional to the four this drafter previously enumerated (§0). **The prior enumeration was incomplete.**

---

## 8 · PO/ARB decision requirements

**Five decisions. `PROPOSED` framings only — this determination selects none.**

| # | Decision | The admissible answers |
|---|---|---|
| **D-1** | **`$this->$m()` — how is an undeterminable target name REPRESENTED?** | **(a)** make `targetMethodName` **optional/absent** for computed members · **(b)** add a **distinct L3 fact kind** for an undeterminable behaviour site · **(c)** declare the construct a **stated LIMITATION** — unrepresentable, and say so in the contract. ⛔ **A sentinel string is not admissible** (`INV-L3-5`), and the variable's source text is not admissible (`INV-4`/`INV-L3-7`) |
| **D-2** | **`$this->$p` — does the contract rule on dynamic property access at all?** | **rule it** (then D-3 follows) · **declare it out of scope as a stated limitation** · **defer with a named trigger.** ⛔ **The `intra_class_calls` clause must NOT be extended to state by analogy** — it governs calls, and the silence is a silence |
| **D-3** | *If D-2 rules it:* **does `StateAccess` gain a determinability channel, and does L4 gain a state verdict function?** | yes / no / defer. ⚠️ **This is the only decision that changes an accepted L3 type AND adds an L4 rule** |
| **D-4** | **`call_user_func` — is the enumerated library-dispatch scope adopted?** | adopt §12's wording · reject and declare the limitation instead · defer. ⚠️ **Note the contract already names the construct, so "reject" means the contract must be amended to state the limitation** |
| **D-5** | **Which `QualifierKind` describes library dispatch?** | reuse `ComputedTarget` · **add a case** (e.g. `LibraryDispatch`) · treat as `InstanceReceiver` + `NotDeterminable`. ⚠️ Needed only if D-4 adopts |

**Returned explicitly, as the grant requires when the specification is insufficient:** ⛔ **the existing specification is insufficient for D-1, D-2, D-3 and D-5.** It is **sufficient** for the *semantic scope* of D-4 (already decided) and insufficient only for its *binding knowledge boundary*.

---

## 9 · Downstream correction consequences

| Question the grant asks | Answer |
|---|---|
| **Is a bounded implementation correction required?** | 🟡 **YES — but only for case 1 (`call_user_func`), and only if D-4 adopts.** It is representable today with no model change. ⛔ **Cases 2 and 3 CANNOT be corrected by implementation** — they need D-1/D-2/D-3 first. **Any "correction" to them before those decisions would silently invent a representation.** |
| **Is a specification / invariant decision required?** | ✅ **YES — D-1 and D-2 at minimum.** §11's wording is drafted for adoption **by a separate governed act.** |
| **Must a library-dispatch limitation be declared?** | ✅ **YES in either direction.** If D-4 adopts → the **enumerated** scope must be declared (§12). If D-4 rejects → **the contract must be amended to state the limitation**, because it currently *names* `call_user_func` as excluded-as-not-determinable, which the binding cannot honour. ⛔ **Rejection is not a no-op.** |
| **Is any correction required to protect the metric?** | 🔴 **NO** (§2.6). **Metric-neutral.** |
| **Does V-3 affect the accepted Architecture D?** | 🔴 **NO.** No option, layer or component of Architecture D is implicated; the L4/L5 layers need no change (§7.1). |

---

## 10 · Closure / next-step conditions

**The artifact-update gate — answered exactly as the grant requires:**

| | |
|---|---|
| **Does the V-3 outcome still block expected evidence?** | 🟡 **NARROWLY, not wholly.** ✅ **Unblocked for the ten golden fixtures** — none contains a V-3 construct (`OBSERVED`). 🔴 **Blocked for any corpus containing dynamic members, and for any conformance claim.** |
| **Is a bounded implementation correction required?** | **Only case 1, only after D-4.** Cases 2–3 are decision-blocked (§9). |
| **Can the artifact-update gate proceed after the PO/ARB decision?** | ✅ **YES for the golden-fixture evidence — and arguably already, since V-3 does not reach it.** 🔴 **NO for `app/`-scoped or conformance-asserting evidence until D-1/D-2 are answered.** ⛔ **This determination does not open the gate; it states the condition.** |

**⛔ Nothing was created, modified or executed:** no expected node/edge evidence · no `expected.json` change · no fixture change · no contract change · no implementation change · no 13.3/13.5 change · no verification · no artifact update · **Track-1 acceptance not reopened** · legacy calculator not retired · no further Architecture assignment created · no replacement architecture.

**Verified after the run:** `scripts/lib/`, `scripts/observations/`, `app/`, `tests/` and `expected.json` are **unmodified**; probes were throwaway scratch files.

> ## **Track 1 remains accepted within its authorized implementation scope.** V-3 is an **undeclared Level-2 evidence gap plus three unanswered specification questions** — not a defect in the accepted delivery's authorized scope, and **not grounds to reopen acceptance.**

---

## 11 · PROPOSED INVARIANT WORDING — ⚠️ **DRAFT ONLY, for adoption by a separate governed act**

> ### **`INV-L3-8` — Observed-but-undeterminable is a fact, not a silence**
>
> **Where the analysed source contains a site at which a method refers to a behaviour of the analysed unit, or touches the analysed instance's state, and the identity of the referenced behaviour or state CANNOT be determined within the analysis scope, the fact model MUST record that a site was observed and that its identity is undeterminable.**
>
> **The model MUST therefore keep three states distinguishable, and MUST NOT collapse any pair of them:**
> **(1) NOT OBSERVED — no such site exists in the unit;**
> **(2) OBSERVED AND DETERMINABLE — a site exists and its identity is established;**
> **(3) OBSERVED AND NOT DETERMINABLE — a site exists and its identity cannot be established in scope.**
>
> **An empty exclusion record MUST mean (1) and MUST NOT be produced for (3).**
>
> **The undeterminable identity MUST NOT be represented by a sentinel value, a placeholder name, an "unknown" member of a closed vocabulary, or any lexeme, offset or token taken from the source** — the first three are barred by the all-attributes-mandatory rule, the last by the no-provenance rule that makes it impossible for a cohesion rule to fall back to source syntax.
>
> **This invariant states WHAT must remain distinguishable. It does NOT prescribe the representation, which is a separate decision** *(D-1, D-3)*.

⚠️ **Adoption note for the PO/ARB:** as drafted, this invariant is **unsatisfiable for `$this->$m()` and `$this->$p` under the current L3 types.** ⛔ **It must therefore be adopted TOGETHER with D-1 (and D-3 if D-2 rules), never before them** — adopting it alone would create a decided invariant with no admissible implementation.

---

## 12 · PROPOSED LIBRARY-DISPATCH SCOPE WORDING — ⚠️ **DRAFT ONLY**

> ### **Library and runtime dispatch — declared scope of the PHP binding**
>
> **IN SCOPE — an ENUMERATED, CLOSED list, extended only by a governed act:** a call to one of the explicitly listed PHP standard-library dispatch functions in which the callable is written as a literal two-element array whose first element is `$this` and whose second element is a literal string. The listed functions are: **`call_user_func`, `call_user_func_array`**. *(Any addition to this list is a specification change, not an implementation detail.)*
>
> **OUT OF SCOPE, and declared as a LIMITATION rather than implied:** any dispatch whose callable is assembled at runtime, held in a variable or property, built by concatenation, or returned from a call; `Closure::fromCallable`, `Closure::call`, `Closure::bind`; `__call`/`__callStatic` magic dispatch; container, event-dispatcher, reflection-based or framework dispatch; any dispatch whose target method name is not a literal string in the analysed source.
>
> **KNOWLEDGE THE BINDING IS PERMITTED TO USE:** the tokens of the analysed source, and the enumerated function names above **as names only**. ⛔ **The binding MUST NOT resolve the runtime behaviour of any library function, load or inspect library code, or infer dispatch semantics from a function's name.**
>
> **REPRESENTATION of an in-scope dispatch:** a behaviour-reference fact carrying the literal target method name, recorded as **NOT DETERMINABLE** — because although the name is visible, the analysis does not establish that this dispatch reaches that method of the analysed unit. **It is recorded as observed-and-undeterminable, never omitted.**
>
> **REPRESENTATION of an out-of-scope dispatch:** ⚠️ **not defined by this wording.** It is a declared limitation, and a construct falling under it is **not observed by the binding at all.** ⛔ **The specification must state this plainly, so that an absent fact is read as a declared limitation and not as a determination that nothing was there.**

⚠️ **Scope discipline, stated because the grant demands it:** the enumerated list is **two functions**, matched only in the **literal `[$this, 'name']`** form. **`call_user_func([$this,…])` occurs zero times in `app/` (§3.4)** — the wording is therefore deliberately narrower than what is technically achievable, because **the evidence supports no more.**

---

**V-3 ARCHITECTURE DETERMINATION DELIVERED · STOPPING.**
⛔ **PROPOSAL ONLY · no decision taken · no implementation · no verification · no acceptance · no artifact update · no expected evidence created · `expected.json`, fixtures and the contract untouched · 13.3 and 13.5 untouched · Track-1 acceptance not reopened · Architecture D not redesigned · no self-verification · no self-acceptance · assignment NOT closed (`G-1`).**
**Next actor: PO/ARB — D-1 … D-5.**

**Traceability:** `G-KOS-CONTRACT-V3-ARCH` + `-AMD1` · assignment seq 38–40 · Track-1 independent verification `50d55d26` (`V-3`, verdicts B and C, the *"one and only one"* answer) · Track-1 acceptance registration · accepted implementation architecture · Decisions **13.1 / 13.3 / 13.5 / 13.7** · `expected.json._variant_decisions_pinned.intra_class_calls` **(PO/ARB 2026-08-16, quoted verbatim)** and the eight pinned keys · **code read directly: `BehaviourReference` (6 mandatory attributes, `INV-L3-5`, the `INV-4`/`INV-L3-7` no-provenance note) · `StateAccess` (2 attributes) · `Determinability` · `QualifierKind` (10 cases incl. `ComputedTarget`) · `TargetUnitRelation` (incl. `Undetermined`) · `AccessMode` · `ExclusionReason` (6 cases) · `EdgeRules::verdict()` rows 1–9 · `GraphBuilder` state grouping · `PhpFactExtractor::factsIn()` / `classifyQualifier()` / `isFirstClassCallable()` · `AnalyseCohesion::observe()`** · **measured probe through the accepted pipeline** (5 constructs; `value=6 · nodes=7 · edges=1 · excluded=1`) · occurrence counts in `app/` and the ten fixtures · `99aeac7c` **treated as evidence/proposal material only, per the standing caveat** · `INV-ATTR-1`/`INV-ATTR-2`/`G-1`/`G-2`/`G-3`/`R-34`/`P-2`.
