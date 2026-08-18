# `KOS-CONTRACT-NEUTRALITY-001` Track 1 — **fit assessment: existing Python capability vs the accepted `L3` model**

**Assignment:** `S3-implementation-track1-php-adapter` (seq 32 REGISTER · 33 HANDOFF · **34 START**) · **Grant:** `G-KOS-CONTRACT-IMPL-TRACK1`
**Date:** 2026-08-18 · **Type:** 🔴 **FIT ASSESSMENT — the authorization's FIRST TASK. No implementation performed.**

> **Producing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`.
> 🔴 **Prior position disclosed:** this process authored the accepted implementation architecture — **including §14.1, the "B3 runs IN-PROCESS" statement this assessment finds contradicted.** It is assessing a boundary against a design it wrote. **Mitigation, per the act's own method instruction: every conclusion below is derived from the Python source and cited to a line — not from the architecture document.** `R-34`/`P-2`: this process must not verify or accept this work.

## 🛑 Result

> # **STOP BEFORE CODE.**
> **Fit is RED on the load-bearing question, and a required transport contract is NOT authorized.**
> **Per the act, this is a successful result, not an implementation failure.**

| | Finding | Class |
|---|---|---|
| **F-1** | **The existing Python capability cannot consume the accepted `L3` model.** No function in it accepts anything resembling `L3`; **every entry point takes PHP source text** | **semantic mismatch** |
| **F-2** | Its internal seam is **not** `L3 \| L4/L5`. It is `(L1+L2+L3+L4-decision) \| (L4-edge-construction + L5)`. **The accepted boundary falls INSIDE a regex function over PHP source** | **semantic mismatch** |
| **F-3** | The one non-source function returns `int`. **It cannot emit a node set or an edge set**, so it cannot satisfy 13.7 / AMD2's three-level conformance even where its input is adapted | **architecture decision required** |
| **F-4** | **A process boundary necessarily exists** (PHP adapter → Python L4/L5). **No transport contract is authorized** — the accepted design §14.1 forbids one and AMD2 disposed `OQ-4` on the premise that no such boundary existed | 🔴 **architecture decision required — STOP** |
| **F-5** | **"the existing Python analysis scripts" is ONE file of 279 lines** — and it is the artifact the Stage-2 independent verification FAILed | context, `Observed` |

---

# 1 · Existing Python contract — `Observed`, from the source

## 1.1 Inventory — the whole Python estate

```
find . -name "*.py"  (excluding vendor, node_modules, .git, __pycache__)
  →  scripts/observations/lcom4_collector.py        ← the ONLY match
```

> ⭐ **`Observed`: there is exactly ONE Python file in the repository.** The authorizing act says *"we should not rebuild the existing Python analysis **scripts**"* — plural implies an estate. **There is one file, 279 lines**, and it is the artifact that carries the Stage-2 `FAIL — implementation defect` verdict and the breadth report's nine further mechanisms. ⚠️ **Recorded as context, not as an argument: the FAIL was against its SCANNER (`L1`/`L2`), never against its union-find.** That distinction is what makes §6's split assessment fair rather than punitive.

## 1.2 Every function, and what it actually consumes

| Line | Function | Input | Layer it really occupies |
|---|---|---|---|
| 59 | `blank_noise(src: str) -> str` | **PHP source** | `L1` |
| 102 | `match_block(src: str, open_pos: int) -> int` | **PHP source** | `L2` |
| 126 | `find_classes(clean: str)` | **PHP source** | `L2` |
| 134 | `find_methods(clean, body_start, body_end)` | **PHP source** | `L2` |
| 163 | `analyse_method(body: str, own_class: str)` | ⭐ **PHP source body text + a class-name string** | **`L3` extraction FUSED WITH the `L4` decision** |
| 201 | `connected_components(methods: dict[str, tuple[set, set]]) -> int` | ⭐ **the only non-source input** | **`L4` edge construction + `L5`** |
| 239 | `interpretation(value: int) -> str` | `int` | `L5` presentation |
| 247 | `collect(php_source: str) -> list[dict]` | **PHP source** | orchestration |
| 269 | `main() -> int` | a file path or **stdin** → PHP source | CLI |

> ## ⭐ **F-1, stated precisely: the ONLY function that does not consume PHP source text is `connected_components`, and it returns `int`.**

## 1.3 The exact input structure of `connected_components`

```python
def connected_components(methods: dict[str, tuple[set, set]]) -> int:
```

**Required:** a mapping `methodName → (props, calls)`.
**`props`** — `set[str]`, bare property names.
**`calls`** — `set[str]`, bare method names.
**Optional fields: none. Vocabulary/enums assumed: none — the structure is `str` and `set[str]` throughout.**

**What it does with them** (`:218–229`):
1. state edges — two methods sharing a `props` entry are unioned;
2. behaviour edges — `for callee in calls: if callee in methods:` union *(the declared-method-membership rule)*;
3. connected components → `int`.

> ⚠️ **Steps 1 and 2 are `L4` rules, executed inside what looks like the metric function.** Under the accepted architecture they belong to `L4`. **Adapting this function therefore means REMOVING responsibility from it, not adding.**

## 1.4 What `analyse_method` assumes — the five questions the act named

**All `Observed` at `:163–193`.**

| Concept | What the Python model does |
|---|---|
| **`own_as_written`** | `own_as_written = q == own_class.lower()` (`:186`) — **a single boolean**, computed from a lowercased qualifier string |
| **qualifier kind** | ⛔ **never represented.** The qualifier is a raw regex capture (`STATIC_CALL_RE`, `:156`); after `:187` it is discarded |
| **target / unit relation** | ⛔ **not separable** — fused into the same boolean at `:186` |
| **determinability** | ⛔ **no fact exists.** `$var::m()` is dropped by `continue` (`:181–182`); `parent::` by `continue` (`:184–185`). **Both exclusions leave NOTHING behind** |
| **nullability (nullsafe)** | ⛔ **structurally impossible.** `PROP_OR_CALL_RE = \$this\s*->\s*([A-Za-z_]\w*)` (`:155`) **does not match `?->` at all**, so a nullsafe access never becomes any fact |
| **identity** | ⛔ **none.** `class_name` is a `str` from `CLASS_RE` (`:121`); no `UnitKind`, no `UnitIdentity`. Only `class` is matched — enum/trait/interface are never seen |

---

# 2 · Accepted `L3` contract — `DECIDED`

Per the accepted architecture §4 and the selection act's ten preserved items:

**`DeclaredUnit`** { `UnitKind` (Class·AnonymousClass·Enum·Trait·Interface) · `UnitIdentity` (declaration path, `OQ-1` ratified) · `DeclaredName` }
**`MethodDeclaration`** { `MethodIdentity` · `hasBody` }
**`StateAccess`** { `PropertyName` · `AccessMode` }
**`BehaviourReference`** { `TargetMethodName` · `QualifierKind` · `TargetUnitRelation` · `ReferenceMode` · `AccessMode` · `Determinability` } — **all six mandatory (`INV-L3-5`)**
Plus **`Provenance`**, joined one-way by `factId`, **never reachable from `L4`** (`INV-4`).

---

# 3 · Field-by-field fit matrix

| Accepted `L3` element | Present in the Python input? | Classification |
|---|---|---|
| `DeclaredUnit.UnitKind` | ⛔ absent — only `class` is matched | **semantic mismatch** |
| `DeclaredUnit.UnitIdentity` | ⛔ absent — no identity concept | **semantic mismatch** |
| `DeclaredUnit.DeclaredName` | ⚠️ a `str`, but **not carried into `connected_components` at all** | **adapter required** |
| `MethodDeclaration.MethodIdentity` | ✅ the `dict` key | **compatible** |
| `MethodDeclaration.hasBody` | ⛔ absent — bodyless methods reach `analyse_method` as `""` (`:255`) and become `(∅,∅)`; the distinction is lost | **adapter required** |
| `StateAccess.PropertyName` | ✅ a member of `props` | **compatible** |
| `StateAccess.AccessMode` | ⛔ absent | **semantic mismatch** |
| `BehaviourReference.TargetMethodName` | ✅ a member of `calls` | **compatible** |
| ⭐ `BehaviourReference.QualifierKind` | ⛔ **absent — discarded at `:187`** | 🔴 **semantic mismatch** |
| ⭐ `BehaviourReference.TargetUnitRelation` | ⛔ **absent — fused into one boolean at `:186`** | 🔴 **semantic mismatch** |
| `BehaviourReference.ReferenceMode` | ⛔ absent — decided at `:189–190`, result discarded | **semantic mismatch** |
| `BehaviourReference.AccessMode` | ⛔ absent — cannot be produced (`:155`) | 🔴 **semantic mismatch** |
| ⭐ `BehaviourReference.Determinability` | ⛔ **absent — both exclusions leave no fact (`:182`, `:185`)** | 🔴 **semantic mismatch** |
| `Provenance` (one-way `factId`) | ⛔ no concept | **adapter required** |

**Tally: 3 compatible · 4 adapter required · 7 semantic mismatch.**

> ⛔ **Per the act, none of the seven is reclassified as "merely a technical adapter problem."** Each is a distinction the accepted contract **decided must exist** and the Python structure **cannot carry**.

---

# 4 · Vocabulary and semantic fit

**Accepted `L3` requires closed vocabularies** — `UnitKind`, `QualifierKind` (10 values), `TargetUnitRelation` (3), `ReferenceMode` (2), `AccessMode` (2), `Determinability` (2).

**The Python structure has no vocabulary at all**: `str` and `set[str]`. ⇒ ⭐ **This is not a naming difference. A `set[str]` cannot express a six-attribute fact, so the mismatch is one of arity and type, not of spelling.**

**The bucket ruling specifically.** 13.3 insists `NotTheAnalysedUnit` and `NotDeterminable` **must never be merged**. In the Python model **both are the same event — a `continue` statement** (`:182` and `:185`, plus the fall-through at `:187`). **Three distinct decided outcomes collapse to one absence.** 🔴 **The exact collapse 13.3 forbids is already present, and it is unrepresentable rather than merely unrepresented.**

---

# 5 · Data-loss analysis — what `own_as_written` destroys

```python
q = qualifier.lower()                       # :183
if q in OUT_OF_FRAME: continue              # :184-185  parent   → no fact
own_as_written = q == own_class.lower()     # :186      ⭐ TWO AXES → ONE BOOLEAN
if not (q in SELF_REFERENTIAL or own_as_written): continue   # :187  → no fact
```

**Lost at `:186`:** whether the spelling was unqualified · qualified · fully-qualified · relative · aliased — **all five collapse to "matched / did not match".**
**Lost at `:187`:** *why* a non-match was excluded — `NotTheAnalysedUnit` vs `NotDeterminable` vs *another class entirely*.
**Never acquired:** `AccessMode` (`:155` cannot match `?->`) · `Determinability` (`:182`) · `ReferenceMode` as a fact (`:189`) · `UnitKind` · `UnitIdentity`.

> ### ⭐ **The decisive consequence, and it is structural rather than a matter of effort:**
> **By the time any data reaches `connected_components`, the include/exclude decision has ALREADY been taken.** `analyse_method` emits **only survivors, as bare strings.**
> ⇒ **`connected_components` consumes a POST-`L4`-DECISION structure. It sits DOWNSTREAM of the `L4` rule table, not upstream of it.**
> ⇒ **There is no seam in this capability at which accepted `L3` facts could be injected.** The accepted `L3`→`L4` boundary falls **inside `analyse_method`**, which is a regex function over PHP source text.

**Answer to the act's question 8 — *can the existing Python capability consume the accepted `L3` model directly?* → 🔴 NO.** *(Falsifier stated: a function accepting a structure carrying qualifier kind, relation or determinability. None exists — §1.2 enumerates all nine.)*

---

# 6 · REUSE / ADAPT / REPLACE

**Assessed per unit, not per file, because the file is not uniform.**

| Unit | Lines | Assessment | Precise reason |
|---|---:|---|---|
| `blank_noise` · `match_block` · `find_classes` · `find_methods` · `CLASS_RE`/`METHOD_RE`/`PROP_OR_CALL_RE`/`STATIC_CALL_RE` | ~110 | 🔴 **REPLACE** | This is the hand-written scanner **B3 exists to supersede**, and the artifact the Stage-2 FAIL and the nine breadth mechanisms are against. It consumes PHP source; accepted architecture forbids PHP syntax above the binding |
| `analyse_method` | 31 | 🔴 **REPLACE** | Consumes **PHP source text**; fuses `L3` extraction with the `L4` decision; collapses the two axes (`:186`) and the two exclusion buckets (`:182`/`:185`) |
| `collect` · `main` | ~30 | 🔴 **REPLACE** | Entry points take PHP source |
| `EXCLUDED_METHODS`, `OUT_OF_FRAME`, `SELF_REFERENTIAL` | 3 | ✅ **REUSE — as SEMANTICS, not as code** | Their content survives as accepted rules (constructor exclusion; row 3; row 5). Three literals, not a capability |
| ⭐ `connected_components` | 31 | 🟡 **ADAPT** *(union-find algorithm REUSE-able; the function as written is not)* | **Three changes, each forced by a decided rule:** ① its input must become the `L4`-produced node/edge sets, not `(props, calls)`; ② steps 1–2 (`:218–229`) are **`L4` rules and must move OUT**; ③ **it returns `int` and must expose the graph** — see F-3 |
| `interpretation` | 6 | ✅ **REUSE** | A pure function of the metric; identical strings on both sides |

> ## ⭐ **Net: ~37 of 279 lines are reusable in substance (union-find + interpretation + three literals). ~242 lines are the superseded scanner.**
> **The act's condition is met on evidence: the accepted `L3` model cannot be consumed by the existing capability, and the precise incompatibility is §5.**
> ⚠️ **Fairness note, stated because the record deserves it: the FAIL verdict was never against the union-find.** Its algorithm is sound and is the part worth keeping. **The recommendation is not "the Python was bad" — it is "the reusable part is 13 % of the file, and it sits on the wrong side of the accepted boundary."**

## 6.1 🔴 F-3 — the conformance-evidence gap, which survives adaptation

**`connected_components` returns `int`.** **13.7 and AMD2 require conformance at three levels: `L3` facts · `L4` graph (node set + edge set) · `L5` metric.** ⛔ **A function returning a count cannot emit levels 1 or 2.** ⇒ **Even after its input is adapted, it satisfies only the third level.** **Whether the graph is exposed by changing this function or by placing graph construction in `L4` above it is an implementation decision — but that `L4` must own graph construction is already decided, and it is not what this code does today.**

---

# 7 · Process-boundary assessment 🔴 **STOP**

| Question | Answer |
|---|---|
| **Does a process boundary exist?** | ✅ **YES, necessarily.** The authorized path is `PHP adapter → L3 → existing Python analytical capability → L4/L5`. **PHP and Python share no memory.** `Observed`: the Python capability's only external interface today is `main()` (`:269–275`) — argv file paths or **stdin**, output `json.dumps` to stdout |
| **What must cross it?** | the complete `L3` fact set for one file: every `DeclaredUnit` (kind, identity, name) · every `MethodDeclaration` · every `StateAccess` (property + access mode) · every `BehaviourReference` (**all six attributes**). ⚠️ **And `Provenance` must NOT cross into `L4` (`INV-4`) while remaining available for evidence — so the boundary must carry two separated payloads, not one** |
| **What transport semantics are required?** | ⛔ **Not answered here, deliberately.** It requires closed-vocabulary encoding, stable identity encoding, total-field guarantees (`INV-L3-5`), and a versioning channel (spec · binding · `LanguageLevel`) per the accepted §14.6 |
| **Is that contract already authorized?** | 🔴 **NO.** The **accepted architecture §14.1 states the opposite**: *"B3 runs IN-PROCESS inside the PHP implementation. No subprocess, no token dump, no serialized transport."* And **AMD2 disposed `OQ-4`** expressly because *"its premise… is REMOVED from current scope"* |
| **Does introducing it require an Architecture/ARB decision?** | ✅ **YES** |

> ## 🔴 **F-4 — and it is an inconsistency in the governed record, not merely a gap.**
> **The accepted architecture (§14.1, in-process, no serialized transport) and the Track-1 authorization (PHP adapter → L3 → existing Python L4/L5) cannot both hold.** Governance recorded the supersession of the **acts** — *"where the acts conflict the LATER ACT GOVERNS"* — but **the accepted design document still says in-process**, and it is the document the implementer builds from.
>
> ⛔ **No wire format is proposed here. No mechanism is chosen. `OQ-4`'s disposal premise is gone and the question is live again — in the opposite direction from the one AMD2 disposed** *(a PHP adapter feeding a Python analyzer, rather than a Python implementation invoking a PHP runtime)*.

---

# 8 · Required architectural decisions ⬜ — returned to Architecture / ARB

| | Decision required | Why the implementer cannot take it |
|---|---|---|
| **AD-1** | **Reinstate or re-dispose `OQ-4`.** AMD2 disposed it on a premise the Track-1 act removed | Disposing/reinstating a governed open question is a PO/ARB act |
| **AD-2** | **Authorize an `L3` transport contract** — encoding, identity encoding, total-field guarantee, provenance separation across the boundary, versioning | The act forbids inventing a wire format, and §14.1 currently forbids the boundary itself |
| **AD-3** | **Reconcile the accepted design with its own authorization** — §14.1 (in-process), §11.1 (`connectedComponents` moves up to `L5` **on the PHP side**), §11.2 (`lcom4_collector.py` **out of current scope**), §1.1 (scope-of-claim assumed one binding) | Amending an accepted design is Architecture's act on a PO/ARB decision |
| **AD-4** | **Decide what "reuse the existing Python analytical capability" now means**, given §6: ~37 of 279 lines are reusable and the reusable part sits **downstream** of the accepted boundary | The act's reuse preference is a governance instruction; only its author can restate it against this evidence |
| **AD-5** | **Decide where the `L4`/`L5` engine lives** — the Track-1 act places it in Python, the accepted design §11.1 places it in PHP | Same conflict as AD-3, stated separately because it changes what gets built first |

---

# 9 · Implementation readiness

> ## 🔴 **NOT READY. Implementation must not begin.**

| Precondition | State |
|---|---|
| Accepted `L3` model exists | ✅ yes |
| Decided semantics available | ✅ 13.3 · 13.5 · `OQ-1` · `OQ-2` |
| A consumer able to accept `L3` | 🔴 **no** — §5 |
| An authorized transport for the boundary the path requires | 🔴 **no** — §7 |
| The accepted design consistent with the authorization | 🔴 **no** — F-4 / AD-3 |
| Conformance evidence producible end-to-end | 🔴 **no** — F-3 |

**What Track 1 CAN build the moment AD-1…AD-5 are settled, and not before:** the PHP adapter `T1`–`T5` producing accepted `L3` facts **is** unblocked on its own terms — it depends on none of the five, because it sits **below** the disputed boundary. ⚠️ **Recorded as an observation, not as a request to proceed: building it now would mean building a producer for a consumer contract that does not yet exist.**

---

# 10 · STOP · next actor

> **STOPPING. No code written. No file modified.**
> **Next actor: 🔵 Architecture / ARB — resolve AD-1 … AD-5.** **Then PO/ARB re-authorizes or re-scopes Track 1.**

**Integrity, verified after the assessment:**
```
scripts/observations/Lcom4Collector.php               4136519b…  unchanged
scripts/observations/lcom4_collector.py               5acb0e13…  unchanged
scripts/observations/examples/lcom4/expected.json     173ab4ec…  unchanged
git status  scripts/ app/ tests/                      clean
```
⛔ **No wire format invented · no transport infrastructure created · no `L3` semantics redefined · no architecture changed · no unresolved architecture question decided · Python NOT modified to make the fit appear GREEN · PHP NOT modified to force compatibility · no implementation prepared for an assumed decision · no verification · no acceptance · assignment NOT self-closed (`G-1`).**

⚠️ **One item outside this assignment, reported not acted on:** the `EM-DOM-001` Phase-2A lane briefing states the `DEP-10` guard exists at **two** sites. **The verified count is four** (`FillCommitteeSeatHandler.php:174` and `:192`; `RecordVacancyEventHandler.php:161` and `:174`). **An active lane will read the stale figure.** Correcting it is a Governance act.

**Traceability:** `G-KOS-CONTRACT-IMPL-TRACK1` (the FIRST TASK clause; the reuse condition; the untested-premise statement) · assignment `S3-implementation-track1-php-adapter` seq 32–34 · accepted implementation architecture `adc5c8e8` (accepted `c6c4f984`) §1.1 · §4 · §11.1 · §11.2 · §14.1 · §14.6 · `G-KOS-CONTRACT-IMPL-ARCH-AMD1` (language scope) · `AMD2` (`OQ-2` decided, **`OQ-4` disposed**, three-level conformance) · `AMD3` (provenance boundary; pre-delivery gate) · `AMD4` (`OQ-1` ratified; `OPEN-1` recorded) · Decisions 13.1 · 13.3 · 13.5 · 13.7 · Decision 1 · `G-KOS-CONTRACT-ARTIFACT-UPDATE` · Stage-2 independent verification `4d4738db` (FAIL — against the scanner) · breadth report `17e4f066` · **`scripts/observations/lcom4_collector.py` lines 59, 102, 121, 126, 134, 155, 156, 163, 181–190, 196–231, 239, 247, 255, 269 — read directly, as the act's method instruction requires** · `R-34`/`P-2` · `G-1` · `INV-ATTR-2`.
