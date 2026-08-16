# KOS-LCOM4-CONTRACT-001 — PO/ARB decision package registered
# Four approvals · Architecture overruled on one · application lane created, NOT started

**2026-08-16 · Session 2 (Governance)** · **Registration only. Nothing applied.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #12
 Responsibility : governance
 Operator       : Session 2 (Governance)                  [declared]
 Approver       : PO/ARB — decision package 2026-08-16     [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The four decisions, registered verbatim

### `D-1` · Sixth pinned decision — **APPROVED**, in the PO/ARB's own wording

> **"An internal behavioural relationship exists when one method uses another method as a behaviour of the same class. The relationship is determined by the behavioural dependency, not by the syntax used to express it."**

| Form | Business meaning | Decision |
|---|---|---|
| `$this->m()` | one class behaviour uses another | **Include** |
| `self::m()` | same | **Include** |
| `static::m()` | same, within the defined analysis boundary | **Include** |
| `OwnClass::m()` | same relationship expressed another way | **Include** |
| `parent::m()` | uses inherited behaviour **outside** the analysed class | **Exclude** |
| dynamic / computed target | **a real dependency may exist, but the analysis cannot determine the target reliably** | **Exclude, recorded as a limitation** |

> **The PO/ARB's wording supersedes the draft's phrasing.** *"I strongly recommend keeping that distinction"* — the separation between **"not applicable to this analysis"** and **"real relationship exists but cannot be determined"** is **approved and binding**.

### `D-2` · First-class callables — **EXCLUDED** ⚠️ **Architecture was OVERRULED**

> **"First-class callable references are currently outside the LCOM4 cohesion contract. They may represent a real dependency, but they are not treated as an invocation relationship for this metric."**

**Architecture recommended INCLUDE. The PO/ARB decided EXCLUDE.** Reasoning registered:

> *"a first-class callable is ambiguous between **'uses this behavior'** and **'refers to this behavior so somebody else may use it'** … I would rather keep LCOM4's meaning clean than expand it because the syntax happens to contain a method reference. We can introduce a separate metric later if method-reference coupling proves valuable."*

> ⭐ **Recorded because it matters more than the outcome:** Architecture flagged this as *"the one case the accepted direction does not settle"* and put it to the PO/ARB **rather than resolving it by preference**. **The PO/ARB chose the other way. That is the separation working** — an architecture recommendation is advice, and the decision was never Architecture's to make.

### `D-3` · The three fixtures — **ALL APPROVED**

| Fixture | Expected | What it pins |
|---|---:|---|
| `self-call.php` | **2** | one behaviour uses another behaviour of the same class — **the exact defect that stopped Track 1** |
| `static-call-chain.php` | **1** | a static in an internal behavioural relationship is **not** isolated merely for not using object state |
| `parent-call.php` | **2** | ⭐ **the negative boundary** — inherited behaviour is outside the analysed class |

> *"The negative fixture is especially valuable because it prevents a future implementation from silently broadening the contract."* — **approved with the exclusion fixture explicitly endorsed.**

### `D-4` · `static_methods` rewording — **APPROVED**

> **"INCLUDED as nodes; a static that neither touches `$this` NOR participates in an internal behavioural relationship is an isolated component."**

Necessary because `alpha()` can call `beta()` without touching `$this` while still representing a meaningful internal dependency. **Without it the contract would be self-contradictory.**

## 2 · What was registered

| Act | Value |
|---|---|
| **Draft lane closed** | seq **4** — `S4-architecture-lcom4-contract` **COMPLETE** by Governance (`G-1`). Not self-completion. **Explicitly not application** |
| **Application grant** | **`G-KOS-LCOM4-CONTRACT-APPLY`** — `AUTHORIZED`, **application only** |
| **Application assignment** | seq **5** — `S3-implementation-lcom4-contract-apply`, role `implementation`, predecessor `S4-architecture-lcom4-contract` |
| **Handoff** | seq **6** |
| **START** | ⛔ **NOT performed — the PO/ARB's act** |

## 3 · Application scope — apply what was decided, and nothing else

**Five items, each traceable to an approval:** the sixth pinned decision in the approved wording (`D-1`) · the first-class-callable exclusion in the approved wording (`D-2`) · the amended `static_methods` decision (`D-3`… `D-4`) · the three fixtures with their approved values · and `Lcom4Collector.php` updated **so the reference conforms to the corrected contract**.

> 🔴 **The two exclusions must remain distinguishable in the applied wording.** *Out of frame* (`parent::`) and *not determinable* (dynamic) are different claims, and the PO/ARB approved keeping them apart. **Flattening them during application would undo the decision while appearing to implement it.**

**Excluded from the application:** changing any of the **seven existing expected values** — *verified: none changes* · redefining anything not approved · adding forms not decided · Python · Stage 2 · **re-verifying** (`R-34`) · target-architecture work · `KOS-ARCH-BASELINE-001` · Election · self-certification · completing its own assignment (`G-1`).

## 4 · The chain from here, and its one hard constraint

```
✅ draft → ✅ PO/ARB approval → ⛔ APPLY (awaiting START)
   → ⛔ RE-VERIFY by a DIFFERENT process (R-34)
   → ⛔ only then Stage 2, under a NEW assignment (R8)
```

> **`R-34` is the load-bearing constraint and is written into the assignment's `executionContext`**, where a startup check will read it: **the process that drafted or applies this correction must not perform the re-verification.**
>
> **This process drafted it.** So if it also applies it, **it is disqualified from re-verifying** — and the PO/ARB should route the re-verification elsewhere, as was done for `KOS-ARCH-BASELINE-001`. **Recorded now so the choice is available rather than discovered at the gate.**

## 5 · Nothing applied

Contract · `expected.json` · fixtures · `Lcom4Collector.php` — **untouched, 0 changes measured.** No Python. No re-verification. No Stage 2 lane (`R8` — it does not exist).

---

*Technical references: PO/ARB decision package 2026-08-16 (§1 verbatim) · approved draft `8af921ff` · Stage 1 verdict `29b3280f` · `KOS-LCOM4-CONTRACT-001` seq 4–6 · grants `G-KOS-LCOM4-CONTRACT-DRAFT`, `G-KOS-LCOM4-CONTRACT-APPLY` · `R-34` · `R8` · `G-1` · `INV-ATTR-2` · `KOS-CONTRACT-NEUTRALITY-001` Stage 2 blocked and lane-less.*
