# KOS-CONTRACT-NEUTRALITY-001 — **Semantic Contract Clarification Proposal**

**Status: 🟡 PROPOSAL ONLY. It decides nothing. Decisions 13.3 and 13.5 remain the PO/ARB's.**
**Assignment:** `S4b-architecture-semantic-clarification` (seq 23 REGISTER · 24 HANDOFF · 25 START) · **Grant:** `G-KOS-CONTRACT-SEMANTIC-CLARIFY`
**Date:** 2026-08-18 · **Role:** Architecture (drafting) · **Next actor:** PO/ARB — decide **13.3** and **13.5**
**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); the `reviews/` folder holds this work item's sibling artifacts.

---

## 1 · Decision context

### 1.1 What is already in force, and is therefore not re-opened here

| Ref | In force | Consequence for this proposal |
|---|---|---|
| **13.1** | **Contract neutrality is `L3 → L5`** — the contract owns the language-neutral fact model, cohesion semantics and metric; parsing/extraction is a **language binding** | **This is the pivot.** The `NEW-5` question stops being *"which collector is right?"* and becomes ***"what must the L3 fact model carry for the rule to be decidable at all?"*** |
| **Decision 1** | The contract is **clarified**; ⛔ the PHP reference is **not** automatically authoritative; ⛔ no repair may target `NEW-5` until the semantics are decided | Every rule below is argued from the contract text and the evidence, **never** from what either collector does today |
| **Decision 2** | Conformance evidence = **node set + edge set + final metric** | §4 and §5: the L3 fact model **is** the node/edge set. Decision 2 and the L3 schema are **one artifact**, not two |
| **13.7 / Decision 3** | Parsing architecture **not selected**; selection sequenced **behind** 13.3 and 13.5 | ⛔ This proposal selects no parsing architecture and evaluates none |

### 1.2 ⚠️ Disclosed conflict — read this before §2

**The process drafting this proposal is the same process that performed the breadth verification, discovered `NEW-5`, and named the four silences.** It recorded a prior position: *that on the stricter reading of the pinned decision it is the **PHP reference** that diverges.*

**`R-34` is not breached** — proposing a rule is neither verifying nor accepting one, and this lane decides nothing. **But the anchoring risk is real:** a process that found a defect may draft a rule that vindicates its own framing. **Three mitigations are bound into the drafting and are visible in the text:**

1. **every candidate rule is argued both ways**, including an explicit case that the PHP reference is right (§2.5, `R-1`);
2. the prior position is **flagged at the point of use**, not buried here;
3. **§2.2 reaches a conclusion that the prior position did not contain and did not predict** — that PHP's behaviour is *incoherent* rather than merely stricter or looser — which is the kind of finding anchoring suppresses rather than produces.

⛔ **The PO/ARB may route this drafting elsewhere on this disclosure.** If it stands, the proposal must be verified by a process that is **neither the breadth verifier nor this drafter.**

### 1.3 Method

**All evidence below is `Observed`, produced by a discriminating construction.** Each probe is a class with three methods — `a`, `b`, `lonely` — where `a` references `b` by exactly the construct under test and **nothing else links anything**. Therefore:

```
LCOM4 = 2  ⟺  the edge a–b EXISTS
LCOM4 = 3  ⟺  the edge a–b DOES NOT EXIST
```

**a one-bit exact read of the edge set, not an inference from a number.** Per `O-1` each probe states its falsifier; per `O-2` the construction is built so a compensating error cannot hide — there is only one possible edge. Controls (`N02` `self::`, `N12` `parent::`, `G2c` plain `$this->`) confirm the probes can both pass and fail. **Nothing in the repository was modified**; probes are throwaway scratch files.

### 1.4 ⚠️ Continuation disclosure — **a SECOND process continued this lane** (added 2026-08-18)

**§§1–6 below were drafted by `claude-code-session:5e1dd9ee`** — the breadth verifier, whose anchoring conflict §1.2 discloses. **The additions marked *(added 2026-08-18)* — this subsection, the §3 banner, §2.8 and §6.0 — were made by `claude-code-session:1c8b041b`**, on a PO/ARB act directing that the active lane continue. **No conclusion, recommendation, marking or evidence row of the original drafting was altered.**

| | |
|---|---|
| **Identity, self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`) | `claude-code-session:1c8b041b` |
| **Is it the Python collector implementer?** | **No** (`fbc084f0`) |
| **Is it the breadth verifier / original drafter?** | **No** (`5e1dd9ee`) |
| **Is it the completion/reproduction auditor?** | **No** (`b260fb38`) |
| 🔴 **Its own prior position, flagged at the point of use** | **It authored the parsing-architecture evaluation (`d2859e91`)**, which analysed `NEW-5`, proposed four candidate readings, and concluded that the reference's behaviour is *"information loss at the extraction API, not a considered semantic decision."* **That is a prior position on this exact question and it is disclosed here rather than left to be discovered.** Its consequence is worked through in **§2.8**, where it operates **against** the continuing process's own earlier taxonomy. |

> ⚠️ **Record discrepancy, reported and not repaired here.** The seq-23 `executionContext` names the performing process as `claude-code-session:5e1dd9ee`. **That was true of the drafting and is not true of this continuation.** The workflow record therefore under-describes who acted on this artifact. **Correcting the assignment's execution context is a Governance act; this process created no assignment and no grant, and does not amend the record's identity fields.**

**Neither disclosure is cured by the other.** The original anchoring risk stands exactly as §1.2 states it; a second process's additions do not verify, ratify or discharge it. **`R-34`/`P-2` now bars BOTH processes from verifying this proposal.**

---

## 2 · `NEW-5` semantic proposal (Decision 13.3)

### 2.1 The evidence — twelve name kinds, both implementations

| # | Construct | Contract text bearing on it | PHP edge | Python edge | |
|---|---|---|:---:|:---:|:---:|
| `N01` | `Fq::b()` — unqualified own name | `OwnClass::m()` … *"as written"* | ✅ | ✅ | agree |
| `N02` | `self::b()` | INCLUDED | ✅ | ✅ | agree |
| `N03` | `static::b()` | INCLUDED | ✅ | ✅ | agree |
| `N06` | `FQ::b()` — case variant | *"as written"*; PHP class names are case-insensitive | ✅ | ✅ | agree |
| `N08` | `Fq::b()` inside `namespace App` | as `N01` | ✅ | ✅ | agree |
| **`N04`** | **`\Fq::b()`** — fully-qualified, global namespace | ⚠️ **ambiguous** | ✅ | ⛔ | 🔴 **NEW-5** |
| **`N05`** | **`namespace\Fq::b()`** — relative, no namespace declared | ⚠️ **ambiguous** | ✅ | ⛔ | 🔴 **NEW-5b** |
| **`N10`** | **`namespace\Fq::b()`** inside `namespace App` | ⚠️ **ambiguous** | ✅ | ⛔ | 🔴 **NEW-5b** |
| `N09` | `\App\Fq::b()` inside `namespace App` — **the own class, fully qualified** | *"namespaced spellings are not resolved"* | ⛔ | ⛔ | agree |
| `N07` | `Ali::b()` after `use App\Fq as Ali` | *"aliased spellings are not resolved"* | ⛔ | ⛔ | agree |
| `N11` | `\Vendor\Fq::b()` — a different class | not own class | ⛔ | ⛔ | agree |
| `N12` | `parent::b()` | EXCLUDED AS OUT OF FRAME | ⛔ | ⛔ | agree |

### 2.2 The mechanism on both sides — and a finding the prior position did not contain

**Verified at the parser level, directly:**

```
Name            "Fq"              → toString() = "Fq"          toCodeString() = "Fq"
FullyQualified  "\Fq"             → toString() = "Fq"          toCodeString() = "\Fq"
FullyQualified  "\App\Fq"         → toString() = "App\Fq"      toCodeString() = "\App\Fq"
Relative        "namespace\Fq"    → toString() = "Fq"          toCodeString() = "namespace\Fq"
```

> ### ⭐ **`toString()` erases the name kind. `Lcom4Collector::namesThisClass()` receives a string in which the distinction it would need has already been destroyed.**
> **This is not a coding slip — it is the `L3` schema gap, arriving one layer early.** The parsing-architecture evaluation named it independently (*"L3 schema gap: qualifier kind unrepresented"*). **The same single fact explains the divergence and dictates the fact-model consequence in §4.**

**Python's rule, stated exactly:** the qualifier text (with any leading `\` absorbed into it) lowercased must equal the unit's short name, or be `self`/`static`. **Coherent, and consistent across all twelve kinds.**

**PHP's rule, stated exactly:** *the prefix-erased, segment-joined name string, lowercased, equals the unit's short name.*

> ### 🔴 **Therefore PHP's behaviour is INCOHERENT, not merely stricter or looser — and this is the finding the prior position did not predict.**
> **PHP resolves `\Fq::b()` (`N04` ✅) but refuses `\App\Fq::b()` (`N09` ⛔) — yet in `N09` the fully-qualified name denotes *the very class being analysed*.** The two differ only in whether the file declares a namespace. **So PHP does not implement "resolve fully-qualified own-class references"; it implements a string comparison whose correctness is an accident of namespace declaration.** It is *right* in `N04` for the wrong reason and *wrong* in `N09` for the same reason.
>
> **The consequence is decision-relevant and cuts against the drafter's own prior framing:** the honest statement is **not** *"Python conforms and PHP diverges."* It is that **`N09` is the case where PHP silently loses a real own-class behavioural dependency in exactly the code shape this repository actually uses — a fully namespaced `app/`.** Under a semantic reading, **both** implementations are wrong somewhere: Python understates uniformly; PHP understates in namespaced code and happens to be right in the global namespace.

### 2.3 `NEW-5`'s true extent is wider than reported

**`NEW-5` was reported as `\Fq::b()`. The evidence shows the same mechanism reaches the RELATIVE name kind** (`namespace\Fq::b()` — `N05`, `N10`), because `Relative::toString()` also discards its prefix. **Recorded as an extension of the same defect, not as a new one.** *Falsifier: `N05`/`N10` agreeing. They did not.*

### 2.4 The precedence question, stated precisely

The contract contains two clauses that collide **only on self-referential name kinds**:

| | Clause | Verbatim |
|---|---|---|
| **(a)** | **behavioural dependency governs** | *"The relationship is determined by the behavioural dependency, NOT by the syntax used to express it."* |
| **(b)** | **spelling governs** | *"`OwnClass::m()` is recognized only when the name matches the class's own declared name AS WRITTEN; namespaced or aliased spellings are not resolved."* |

**(a)** is a **semantic** rule about cohesion. **(b)** is a **lexical** limitation about resolution. **13.1 explains why they collide: (a) lives at `L4`, (b) constrains `L1/L2`, and the contract states both as if they were the same kind of clause.** Under `L3 → L5` the contract owns (a) outright; (b) is a statement about what the **language binding** is permitted to fail to provide.

> **The ruling must say which clause GOVERNS when they disagree.** Everything else follows mechanically.

### 2.5 Three candidate rules — each argued both ways

#### `R-1` · **IDENTITY rule** — (a) governs; a name that *denotes* the analysed unit is an own-class reference

| Name kind | Verdict under `R-1` |
|---|---|
| unqualified · `self` · `static` · case variant | **edge** |
| fully-qualified (`\Fq`, `\App\Fq`) resolving to the unit | **edge** |
| relative (`namespace\Fq`) resolving to the unit | **edge** |
| **aliased** (`Ali` after `use App\Fq as Ali`) | **edge** |
| a genuinely different class | no edge |
| `parent::` | no edge (out of frame — inheritance, not identity) |

**For:** cohesion is a **semantic** property of behaviour. Two spellings of one class are one class; a metric that says otherwise reports a false fact about the code. **`N09` is the case that makes this argument concrete — and it is the argument FOR the PHP reference's instinct**, which was to resolve, even though its implementation resolves incorrectly.
**Against:** it **replaces** clause (b) rather than clarifying it. It requires the binding to supply namespace + `use`-map + resolved FQN. **⚠️ And it makes an honest statement expensive: the metric would then depend on name resolution, so a wrong `use`-map silently changes cohesion numbers.**
**⚠️ One common objection is WRONG and should not be used against it:** *"`R-1` breaks the pinned `analyzed in isolation` limitation."* **It does not.** Every name kind in the table resolves from **file-local** information — the namespace declaration and the `use` statements in the same file. **No cross-file lookup is required.** `R-1` is achievable within the isolation limitation; the limitation only forbids reaching for *other files*.

#### `R-2` · **SPELLING rule** — (b) governs; only the bare own name, `self`, `static`

| Name kind | Verdict |
|---|---|
| unqualified own name (any case) · `self` · `static` | **edge** |
| **every** qualified, fully-qualified, relative or aliased form | **no edge — NOT DETERMINABLE** |
| `parent::` | no edge — **OUT OF FRAME** |

**For:** coherent, cheap, requires no resolution, and is **exactly what the pinned text already says** on the plainest reading. It preserves the contract's existing character as a metric with declared blind spots. **Python already conforms**; only PHP changes.
**Against:** it **institutionalises understatement in every namespaced codebase.** This repository's `app/` is fully namespaced, so the rule guarantees cohesion is measured on a subset of the real relationships. ⚠️ **And it puts a real behavioural dependency into the `NOT DETERMINABLE` bucket, whose own contract definition is *"a real dependency may exist, but the analysis cannot determine the target reliably"* — which is false here: the target is determinable from the file.** Using that bucket for a determinable case **corrupts the very distinction the contract insists must never be merged.**

#### `R-3` · **STRATIFIED rule** — 13.1-aligned; the contract rules per **qualifier kind**

The contract enumerates the qualifier kinds and states, for each, `INCLUDE` / `EXCLUDE-out-of-frame` / `EXCLUDE-not-determinable`. **The binding's obligation is to report the kind — not to resolve it.**

```
qualifier kind        contract verdict (the PO/ARB fills this column — it is 13.3)
────────────────────  ──────────────────────────────────────────────────────────────
unqualified-own-name  INCLUDE
self / static         INCLUDE
parent                EXCLUDE — out of frame
fully-qualified       ?  ← the NEW-5 decision
relative              ?  ← the NEW-5b decision
aliased               ?  ← currently EXCLUDE by pinned text
other-class           EXCLUDE — not own class
dynamic / computed    EXCLUDE — not determinable
```

**For:** it makes the rule **decidable without hiding the question**, and it is the only option under which the two clauses stop colliding — (a) governs *what an edge means*, the kind table governs *what the binding must be able to tell the contract*. **It also makes the answer auditable per kind rather than per implementation.** And it is the only option that lets `aliased` be ruled **separately** from `fully-qualified`, which the evidence shows are different cases with different costs.
**Against:** it grows the contract text and pushes a schema obligation onto every future language binding. **It is a bigger contract than the one that exists.** ⚠️ **And it does not by itself answer `NEW-5` — it makes the question answerable.** The PO/ARB still fills the two `?` rows.

### 2.6 The ruling must also state its own status — limitation or decision

`13.3` requires this and it is not a formality:

* a **LIMITATION** says *"the metric knowingly understates cohesion here"* — the honest home for `R-2`, and it obliges the contract to say so where consumers will read it;
* a **DECISION** says *"this is what an own-class reference IS"* — the home for `R-1` and for each filled row of `R-3`.

⛔ **The status may not be left implicit.** A silent limitation is indistinguishable from a defect, which is how `NEW-5` reached the PO/ARB in the first place.

### 2.7 Architecture's recommendation *(⚠️ **RECOMMENDATION, not a decision — 13.3 is the PO/ARB's**)*

**`R-3`, with the two open rows filled as `fully-qualified → INCLUDE` and `relative → INCLUDE`, and `aliased` held at `EXCLUDE` as a stated LIMITATION.**

**Reason:** it is the only option that (i) matches `13.1`'s own stratification instead of fighting it, (ii) keeps the contract honest about what it asks a binding to provide, (iii) removes the collision in §2.4 rather than choosing a winner arbitrarily, and (iv) fixes the `N09` semantic loss without importing full alias resolution. **`R-2` is the correct minimal-cost fallback if the PO/ARB prefers the contract to stay small — but then §2.5's objection must be discharged explicitly: the `NOT DETERMINABLE` bucket must not be used for determinable cases.**

### 2.8 Cross-reference — **one reading from the parsing-architecture evaluation is now ELIMINATED** *(added 2026-08-18)*

**Two documents now propose competing taxonomies for the same question, and a PO/ARB cross-reading would otherwise have to reconcile them unaided.** The parsing-architecture evaluation (`d2859e91` §3.4) listed four readings `R1`–`R4`; this proposal lists three, `R-1`–`R-3`. **The labels do not correspond.** The mapping, and what the new evidence does to it:

| Evaluation `d2859e91` §3.4 | This proposal | Status on the `N04` / `N09` evidence |
|---|---|---|
| **`R1`** lexeme identity — `\Fq` ≠ `Fq`, no edge | **`R-2`** SPELLING | **live** |
| **`R2`** behavioural dependency governs (final segment) | ≈ **`R-1`** IDENTITY, *soundly stated* | **live** — and this proposal repairs `R2`'s stated flaw: §2.5 shows file-local resolution suffices, so it does **not** breach the isolation limitation |
| **`R4`** resolve names properly | **`R-1`** IDENTITY | **live**; `R-1` subsumes it |
| 🔴 **`R3`** *"strip exactly one leading `\`, then compare"* — the reference's de facto rule | *(no counterpart — deliberately)* | 🔴 **ELIMINATED as a candidate rule** |
| — | ⭐ **`R-3`** STRATIFIED, per qualifier kind | **new; has no counterpart in the evaluation** — see below |

> ### 🔴 Why `R3` is eliminated, and why this matters more than a taxonomy tidy-up
>
> `R3` was offered in the evaluation as a live option whose cost was political — *"ratifies current PHP behaviour… makes the reference authoritative by default."* **The `N04`/`N09` evidence eliminates it on a stronger ground: it is not a coherent rule at all.**
>
> `R3` predicts PHP's behaviour exactly — `\Fq` → `Fq` matches (`N04` ✅), `\App\Fq` → `App\Fq` does not (`N09` ⛔). **So `R3` is an accurate DESCRIPTION and a disqualified RULE**, because its verdict on a reference turns on **whether the file happens to declare a namespace** — which is not a property of the reference, of the target, or of the dependency. **A rule whose answer changes when you add a `namespace` line to the top of the file, without touching the call, is not stating a semantic.**
>
> ⚠️ **This cuts against the continuing process's own earlier document**, which listed `R3` as a live option with a stated cost rather than as an incoherent one. **The disclosure in §1.4 is what makes that admissible; the evidence is what makes it correct.**

**And the second half of the mapping is the more consequential one:** `R-3` (STRATIFIED) **has no counterpart in the evaluation because it could not have.** The evaluation was written **before** `13.1` decided that contract neutrality is `L3 → L5`. **`R-3` is the reading that only becomes available once the neutrality boundary is fixed at the fact model** — the contract rules per *qualifier kind*, and the binding's obligation is to *report* the kind rather than to *resolve* it. ⇒ **The option space did not merely get re-labelled between the two documents; `13.1` created a new option.** That is an argument for reading `R-1`/`R-2`/`R-3` as the current option set and treating `R1`–`R4` as superseded.

---

## 3 · The four contract silences (Decision 13.5)

**Mandatory marking honoured: every silence is marked, and none is left merely unmentioned.**

> 🔴 **READ THE COLUMN HEADER BEFORE THE CELL VALUES** *(clarified 2026-08-18)*. The column is **"Proposed marking"**. A cell reading **`DECIDED`** means ***"Architecture proposes that the PO/ARB mark this silence DECIDED, with the rule in the next column"*** — it does **NOT** mean the silence has been decided. **Nothing in this table is decided; `13.5` is the PO/ARB's act.** The distinction matters because three of the four proposed rules are **CHANGES** to current behaviour, not ratifications of it — see `G-1` and the explicit change-warning in `G-3`.

| # | Silence | Evidence (Observed) | **Proposed marking** | Proposed rule / consequence |
|---|---|---|---|---|
| **G-1** | **anonymous classes** | `I6` bare `new class` → PHP emits `(anonymous)`, Python **omits** · `I4`/`I5` `new class implements/extends X` → Python **fabricates a unit named `implements` / `extends`** · `N5` nested → **PHP emits TWO rows both literally named `(anonymous)`** | **DECIDED** | **An anonymous class IS an analysed unit** — it declares methods, so cohesion is defined on it. ⚠️ **AND the identity question must be ruled with it:** PHP's `(anonymous)` is **not unique within one file** (`N5` proves it), so it cannot key an observation stream. **The contract must require a stable, unique designation** (e.g. enclosing unit + ordinal, or a declaration-site coordinate). ⛔ **Neither current behaviour is adoptable: one omits the unit, the other names it after a keyword, and the "reference" answer produces colliding identities.** |
| **G-2** | **nullsafe `?->`** | `G2a` `$this?->b()` → **both miss it** (3/3) · `G2b` `$this?->p` → **both miss it** · `G2c` plain control → both 2 ✅ | **DECIDED** | **`?->` IS a behavioural dependency and must be an edge** — clause (a) settles it: *"determined by the behavioural dependency, NOT by the syntax."* ⚠️ **This silence can NEVER be surfaced by differential testing — both implementations share the blindness, so agreement here is not evidence of anything.** It must be pinned by a **fixture with a declared expected value**, never by implementation comparison. |
| **G-3** | **enum / interface / trait as analysed units** | Enums: `E1`–`E5`, `M5` · interfaces: `I1`–`I3` · traits: `T1`–`T5` — **both emit nothing for all three**, consistently | **DECIDED** | **Split the question — it is three questions, not one.** **Enum → IS a unit** (methods, `$this`, `self::` calls; cohesion meaningful). **Trait → IS a unit** *(note: distinct from the existing `trait_methods` decision, which is about resolving trait methods **into a using class** — orthogonal)*. **Interface → is NOT a unit**: it declares no bodies, so every method is an isolated node and LCOM4 degenerates to *"n disjoint responsibility clusters"* — **a false statement about a type that declares no behaviour.** ⚠️ **This is a CHANGE, not a ratification of current behaviour**, and must be marked as such: files containing enums or traits will **gain** observations, so `expected.json` changes. |
| **G-4** | **fully-qualified first-class callables** `\Own::m(...)` | **Now tested** (it was `UNTESTED` in the breadth report): `G4a` unqualified, `G4b` fully-qualified, `G4c` relative → **all three agree, no edge in any** | **DECIDED — by the EXISTING text; not a real silence** | The pinned `first_class_callables` exclusion is **qualifier-independent**: the exclusion is evaluated on the argument list, so it **short-circuits the name-kind question entirely**. ✅ **No new rule is needed, and `NEW-5`'s outcome cannot disturb it** — whichever way 13.3 goes, `\Own::m(...)` stays excluded. **Recommended marking: `DECIDED (existing text sufficient)`, with the evidence recorded so the silence is not re-opened.** |

---

## 4 · Fact-model implications (`L3`) — **architectural consequence only, not an implementation**

**What the language-neutral fact model must PRESERVE for the rules above to be decidable.** ⛔ No schema is implemented, no type is named for construction, no port is proposed.

| Required information | Which rule needs it | Present in the current extraction? |
|---|---|---|
| **analysed-unit KIND** — class · anonymous class · enum · interface · trait | `G-3`, `G-1` | ⛔ **No** — both collectors filter to "class" and the kind never reaches `L3` |
| **unit IDENTITY, unique within the analysed scope** | `G-1` | ⛔ **No** — PHP's `(anonymous)` collides; Python fabricates keyword names |
| **method IDENTITY** — name + declaring unit, in the **language's** identifier charset | `NEW-9`; every edge rule | ⛔ **No** — Python's `[A-Za-z_]` anchor drops legal identifiers |
| **call TARGET** — the referenced method name | every edge rule | ✅ Yes |
| ⭐ **QUALIFIER KIND** — unqualified · self · static · parent · fully-qualified · relative · aliased · other · dynamic | **`NEW-5` / `NEW-5b` — `R-3` is undecidable without it; `R-1` needs the resolved name too** | ⛔ **No — and this is the root of `NEW-5`.** `Name::toString()` erases it before any contract logic runs (§2.2) |
| **CALLABLE vs INVOCATION** | `first_class_callables`, `G-4` | ⚠️ **Implicitly** — both detect it, neither represents it as a fact |
| **ACCESS MODE** — plain vs nullsafe | `G-2` | ⛔ **No** — both lose `?->` at extraction |
| **DETERMINABILITY** — determinable · out-of-frame · not-determinable | the contract's own rule that these *"MUST NOT be merged"* | ⚠️ **Partly** — the distinction exists in both **implementations' control flow** but is **not carried as a fact**, so it cannot be evidenced at the boundary |
| **property ACCESS** — name, and that it is via the analysed instance | shared-state edges | ✅ Yes |
| **the unit's own declared name AS WRITTEN, plus its namespace** | `R-2` needs the first; `R-1` needs both | ⚠️ **Only the short name is passed** |

> ### ⭐ **The architectural point, and it is the reason this proposal must precede parsing-architecture selection:**
> **Nine of the twelve observed divergences disappear at `L3` once `L1/L2` is correct — they are extraction defects. `NEW-5` does NOT.** It survives a perfect parser, because **the fact model has nowhere to put the distinction the rule turns on.** ⛔ **So `NEW-5` cannot be closed by choosing a parsing architecture, and choosing one first would freeze the `L3` schema before the contract knows what it needs to carry — which is precisely the "tooling choices influencing domain semantics" failure the PO/ARB commissioned this proposal to prevent.**

---

## 5 · Conformance consequences

**C-1 · Decision 2 and the `L3` schema are the same artifact.** Decision 2 requires evidence at node set + edge set + metric. **The node set and edge set *are* the `L3` fact model.** So filling §4's gaps is not preparation for conformance evidence — **it is the conformance evidence model.** One consequence: Decision 2 is **not yet satisfiable** for `NEW-5`, `G-1`, `G-2` or `G-3`, because the facts those rules range over are not represented.

**C-2 · ⛔ Contract conformance can never be evidenced by implementation agreement.** `G-2` proves it constructively: both implementations miss nullsafe access, so they **agree** while both violate clause (a). `N07` (aliased) is a second instance; `N09`/`A2`/`A3` agree by unrelated accidents. **Conformance must be evidenced against DECLARED expectations.** *(This is `O-2` generalised from "equal metrics ≠ equal analysis" to "equal analyses ≠ conformance".)*

**C-3 · The fixture population must move down a layer.** Finding `E-1`/`N-2` stands: the ten fixtures probe `L4–L5` only, and **no fixture touches `L1–L2`**. A suite for these rules needs per-unit **node sets** and per-method **edge sets with qualifier kinds** as declared expectations — not ten integers.

**C-4 · ⚠️ Ruling `G-1` or `G-3` changes `expected.json`.** If enums, traits or anonymous classes become analysed units, files containing them **gain observations**, and the pinned expectations change. **The fixtures and `expected.json` are frozen inputs to this work item; amending them is a SEPARATE governed act** and must be sequenced explicitly, or the first conformance run after the ruling will fail for a reason nobody authorized.

**C-5 · The Stage-2 `FAIL` verdict is untouched by every option here.** Under `13.1` what changes is *what it was a failure of*. ⛔ **Nothing in this proposal re-decides it, and no option below would.**

**C-6 · `R-1` does not breach the isolation limitation** (§2.5) — file-local resolution suffices. **This removes what would otherwise look like a decisive objection to `R-1`, and the PO/ARB should not rely on that objection.**

---

## 6 · Open choices for the PO/ARB

### 6.0 Decision-ready summary *(added 2026-08-18, in the column shape the commissioning act requested)*

> **Every row is `PROPOSED`. Nothing in this document is `DECIDED`.** The only `DECIDED` items in force are `13.1`, `13.7`, and Decisions 1–3, listed in §1.1 and not re-opened here.

| Decision | Proposal *(PROPOSED — not decided)* | Evidence | Open question the PO/ARB must close | PO/ARB act required |
|---|---|---|---|---|
| **13.3-a · precedence** | **Stratify** — clause (a) governs *what an edge means*; the qualifier-kind table governs *what the binding must report*. The clauses stop colliding rather than one defeating the other | §2.4; `13.1` fixes the boundary that makes stratification available | Does **(a)** govern, does **(b)** govern, or are they **stratified**? | **Rule one of three.** No default applies |
| **13.3-b · `NEW-5` / `NEW-5b` rule** | **`R-3`**, with **fully-qualified → INCLUDE** and **relative → INCLUDE** | `N04` ✅/⛔, `N05`+`N10` ✅/⛔ (`Observed`, one-bit edge read); `R3` of `d2859e91` **eliminated** (§2.8) | Which rule — `R-1`, `R-2`, `R-3`? If `R-3`, the verdict for **fully-qualified** and **relative** | **Name the rule; if `R-3`, fill the two `?` rows** |
| **13.3-c · `aliased`** | **Hold at `EXCLUDE`, restated as a LIMITATION** rather than left as a silent exclusion | `N07` — both implementations agree, **by unrelated accidents** (§5 `C-2`) | Does `aliased` move with fully-qualified, or stay excluded? | **Rule separately** — the evidence shows it is a different case with a different cost |
| **13.3-d · status of the ruling** | **DECISION** for each filled `R-3` row; **LIMITATION** for `aliased` | §2.6 | Is the ruling a **DECISION** or a **LIMITATION**? | **State it explicitly.** ⛔ May not be left implicit — *"a silent limitation is indistinguishable from a defect"* |
| **13.5-a · `G-1` anonymous classes** | Mark **DECIDED**: an anonymous class **IS** an analysed unit, **and** the contract must require a **stable unique designation** | `I6` (Python omits) · `I4`/`I5` (Python fabricates `implements`/`extends`) · `N5` (**PHP emits two rows both named `(anonymous)`**) | Unit or not — **and** what identity keys it? | **Two rulings in one act.** ⛔ Neither current behaviour is adoptable |
| **13.5-b · `G-2` nullsafe `?->`** | Mark **DECIDED**: `?->` **IS** an edge (clause (a) settles it) | `G2a`/`G2b` — **both implementations miss it**; `G2c` control passes | Confirm `?->` is a behavioural dependency | **Rule it — and note it can NEVER be surfaced by differential testing.** It must be pinned by a fixture with a declared expected value |
| **13.5-c · `G-3` enum / interface / trait** | Mark **DECIDED**, **ruled separately**: **enum = unit · trait = unit · interface = NOT a unit** | `E1`–`E5`, `M5`, `I1`–`I3`, `T1`–`T5` — both emit nothing for all three, consistently | Three separate rulings, not one | **Rule each.** ⚠️ **This is a CHANGE: files gain observations ⇒ `expected.json` changes (`C-4`)** |
| **13.5-d · `G-4` FQ first-class callables** | Mark **`DECIDED (existing text sufficient)`** — not a real silence | `G4a`/`G4b`/`G4c` — all agree, no edge, at every name kind | Confirm the existing `first_class_callables` clause already covers it | **Confirm and close**, so it is not re-opened. `NEW-5`'s outcome cannot disturb it |
| **`L3` obligations** | Adopt §4's rows as the schema the parsing architecture is selected **against** | §4; `NEW-5` **survives a perfect parser** | Which rows does the contract **require** a binding to supply? | **Follows 13.3 + 13.5.** ⛔ Selecting a parsing architecture first would freeze the schema before the contract knows what it must carry |
| **Consequential sequencing** | `expected.json` / fixture amendments implied by `13.5` are a **separate authorized act**; the conformance suite is rebuilt at `L3` | `C-3`, `C-4` | When are the frozen inputs amended, and under what authority? | **Sequence it explicitly**, or the first run after the ruling fails for a reason nobody authorized |

**Then, in detail — six rulings. Each states the minimum the act must contain.**

| # | Ruling | Minimum content | Gate |
|---|---|---|---|
| **1** | **Which clause GOVERNS** when behavioural-dependency (a) and spelling (b) collide | one of: (a) governs · (b) governs · they are stratified per `13.1` | **13.3** |
| **2** | **The `NEW-5` / `NEW-5b` rule** | `R-1` · `R-2` · `R-3` — and if `R-3`, the verdict for **fully-qualified** and **relative** (and whether **aliased** moves) | **13.3** |
| **3** | **The ruling's STATUS** | **LIMITATION** or **DECISION**, stated explicitly (§2.6) | **13.3** |
| **4** | **The four silences** | `G-1` anonymous classes *(unit? + identity rule)* · `G-2` nullsafe · `G-3` enum / interface / trait, **ruled separately** · `G-4` confirm `DECIDED (existing text)` | **13.5** |
| **5** | **The `L3` fact-model obligations** | which of §4's rows the contract **requires** a binding to supply — this is the schema the parsing architecture will be selected against | follows 13.3 + 13.5 |
| **6** | **Sequencing of the consequential amendments** | that `expected.json` / fixture changes implied by ruling 4 are a **separate authorized act** (C-4), and that a conformance suite is rebuilt at `L3` (C-3) | follows |

**And two procedural choices that belong to the PO/ARB, not to Architecture:**

* **⚠️ whether this drafting stands, given §1.2** — the drafter found the defects it now proposes rules for;
* **who verifies this proposal** — it must be neither the breadth verifier nor this drafter, and `G-1` bars this lane from completing its own assignment.

---

**PROPOSAL DELIVERED · STOPPING.** ⛔ **Nothing decided. No parsing architecture selected or evaluated. No collector, contract, fixture or expectation modified. No implementation authorized. This lane does not complete itself.**

**Traceability:** grant `G-KOS-CONTRACT-SEMANTIC-CLARIFY` · assignment `S4b-architecture-semantic-clarification` (seq 23–25) · Decisions `13.1`/`13.7` and `1`/`2`/`3` registrations · parsing-architecture evaluation `d2859e91` (the `L1–L5` stratification, the twelve-divergence root-layer table, `G-1`…`G-4`) · breadth report `17e4f066` (`NEW-5`, `O-2`, the four silences) · contract `scripts/observations/examples/lcom4/expected.json` (clauses `intra_class_calls`, `own_class_name_resolution`, `first_class_callables`, `trait_methods`) · `Lcom4Collector.php` `namesThisClass` :113–125 · `lcom4_collector.py` `analyse_method` :163–193 · **18 new discriminating probes** (`N01`–`N12`, `G2a`–`G2c`, `G4a`–`G4c`) held as throwaway scratch input, not added to the repository.
