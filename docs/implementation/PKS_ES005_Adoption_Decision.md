# **ES-005-ADOPT-1 — Adoption Decision** *(per-rule)*

| | |
|---|---|
| **Act** | Executes `PKS_ES005_Adoption_Commission.md` (Authority instruction: *"execute ES-005-ADOPT-1"*, 2026-07-31) |
| **Unit of decision** | **THE RULE, not the document** *(commission §1)* |
| **Outcome** | **ES-005.1 ADOPT (with a non-exhaustiveness rider) · ES-005.2 ADOPT · ES-005.3 ADOPT · ES-005.4 DEFER** |
| **Document status after this act** | ⚠️ **ES-005 remains PROPOSED** — *it still hosts an undecided candidate. Three of its four rules are now adopted; the document is not* |
| **Status** | **ISSUED.** |

---

## 1. ⛔ A COUNT CORRECTION, made before deciding

**The commission stated *"eight unclassified roots."* That figure came from a TRUNCATED directory listing and is wrong.**

| | |
|---|---|
| **Actual top-level directories** | **24** |
| **Classified by ES-005.1** | **6** — `docs` · `architecture` · `app` · `tests` · `engineering` · `.claude` |
| **UNCLASSIFIED** | ⛔ **18** — `bin` · `bootstrap` · `business_analysis` · `claude` · `config` · `database` · `developer_guide` · `developer_issues` · `public` · `readme` · `resources` · `rough` · `routes` · `scripts` · `storage` · `tenancy` · `todo` · `vendor` |

**Two facts the correction also settles:**
- **`tests/` EXISTS.** *A prior concern that ES-005.1 classified a non-existent root dissolves.*
- **`.claude/` and `claude/` BOTH exist.** *ES-005.1 classifies the dotted runtime mount; the undotted `claude/` is unclassified.*

> ### ***The non-exhaustiveness concern is more than twice as large as the commission stated — and PMR-9, governed since this morning, is what caught it: the count was re-derived from the source rather than carried forward from recollection.***

**And the shape of the gap is benign, which the decision records so a later reader need not re-derive it:** ***inspection indicates that many of the unclassified roots are FRAMEWORK OR INFRASTRUCTURE directories** (`bootstrap` · `config` · `database` · `public` · `resources` · `routes` · `storage` · `vendor`).* ***NO GOVERNING ACT ESTABLISHES WHETHER THEIR OMISSION FROM ES-005.1 WAS INTENTIONAL.*** *(Authority refinement: separates observable repository facts from inferred design intent.)*

---

## 2. **ES-005.1 — The Three-Concern Separation → ✅ ADOPT, with a NON-EXHAUSTIVENESS RIDER**

**Adopted wording — carried verbatim, not restated:**

> **`docs/` + `architecture/` + `app/` + `tests/` = Product** (what PublicDigit is) · **`engineering/` = Engineering Platform** (how it is engineered) · **`.claude/` = Runtime mount point** (how the current adapter executes) — the mount never moves and is never "the architecture."

| Layer | Standing |
|---|---|
| **Rule** | ✅ **ARB-ruled** — *(EM-001, ARB 2026-07-10)* |
| **Recorded · Referenced** | ✅ · ✅ *(STANDARDS_INDEX §54)* |
| **Implemented** | ✅ **one verified use** — OQ-ENG-002 §50 cites the three-concern model in a PASS row |
| **Binding** | **→ ADOPTED BY THIS ACT** |

### 2.1 ⚠️ The rider, and it is part of the adoption

> ### **The classification is NOT EXHAUSTIVE. It classifies 6 of 24 top-level directories. The remaining 18 are UNCLASSIFIED, and this adoption classifies none of them.**

***Recorded because adopting a repository-classification rule without stating the status of the remaining roots risks unintentionally implying that the governed classification is complete. It is not, and no act has decided otherwise.***

**What adoption changes: ES-005.1's BINDING STATUS, and therefore its ENFORCEABILITY. Not its content, and not current practice** — *it was already recorded, referenced, and implemented in at least one verified instance.*

---

## 3. **ES-005.2 — The Folder Rule → ✅ ADOPT**

> **A directory exists only when its first artifact arrives. Reserved namespaces are documented (README table), never created speculatively. Empty directories are removed on discovery (`rmdir`-class, verified-empty only).**

| Layer | Standing |
|---|---|
| **Rule** | ✅ **ARB-ruled** — *(ARB 2026-07-10)* |
| **Recorded · Referenced** | ✅ · ✅ *(shares STANDARDS_INDEX §54's instrument row with ES-005.1)* |
| **Implemented** | ⚠️ **INDEX-CLAIMED, no instance separately verified by this act** — *the row attributes "Existing (executed each OQ)" instruments to .1 and .2 jointly; only .1's use was verified* |
| **Binding** | **→ ADOPTED BY THIS ACT** |

### 3.1 Relationship to R-37 — **complementary, and R-37 is stricter at the top level**

| | |
|---|---|
| **ES-005.2** | governs **WHEN** a directory may be created — *upon first artifact*, at any level |
| **R-37** *(adopted, ARB 2026-07-10)* | **FREEZES top-level folders entirely** — *no new ones* |

> ### ***They are not the same constraint and neither is redundant. At the top level R-37 governs and ES-005.2's conditional permission does not apply; below the top level ES-005.2 governs alone.***

**R-37 is not amended, weakened or restated by this adoption.**

---

## 4. **ES-005.3 — The Placement Litmus → ✅ ADOPT**

> **Could a different project adopt the document unchanged? Yes → `engineering/`. Needs project context or evidence → the project. Active session state → the runtime mount. Research artifacts remain project-side (`docs/implementation/`) until promoted through qualification (ES-006 ladder).**

| Layer | Standing |
|---|---|
| **Rule** | ✅ **ARB-ruled** — *(ARB 2026-07-10)* |
| **Recorded · Referenced** | ✅ · ✅ |
| **Implemented** | ⛔ **NO instruments** — *STANDARDS_INDEX §55 records verification as "AI evaluates (judgment)" at EP-02 review, instruments **"None"*** |
| **Binding** | **→ ADOPTED BY THIS ACT** |

***Adoption is lawful despite the absence of instruments, precisely because adoption changes BINDING STATUS rather than implementation. A rule may be binding and unenforced by machine; ES-005.3's enforcement is by judgment at review, as the index records.***

---

## 5. **ES-005.4 — Never a Copy → ⏸ DEFER**

> *"Governed knowledge references, assembles, validates, and contextualizes existing artifacts; it never duplicates them. One rule → one home; everything else points."*

| | |
|---|---|
| **Its own recorded standing** | ⛔ ***"candidate, ARB to confirm scope"*** |
| **Decision** | ⏸ **DEFER — pending the ARB scope confirmation the rule's own text requires** |

> ### ***Adoption is unavailable, not merely inadvisable: the document itself states that the ARB has yet to confirm the rule's SCOPE, and a rule whose scope is unconfirmed cannot be made binding without deciding the scope by implication.***

**Discharge condition: ARB confirmation of scope — a DECISION condition, not a data condition, and the only case in the programme's register of that kind.**

***The CLEAREST JUSTIFICATION for per-rule adoption emerged here. Had ES-005 been adopted as a document, this rule would have become binding through BUNDLING, with its scope decided by nobody.*** *The commission also resolved ES-005.2's R-37 relationship, ES-005.3's adoption despite implementation differences, and the non-exhaustiveness rider — genuine outcomes in their own right.*

---

## 6. The remaining commissioned matters

| Matter | Determination |
|---|---|
| **STANDARDS_INDEX is itself PROPOSED** | **Does not gate these adoptions.** *ES-005 declares itself canonical and the index registers pointers. **But the navigation layer remains unadopted, and this act does not adopt it*** |
| **ES-005's supersession claim** | ✅ **EFFECTED for ES-005.2 and ES-005.3 only** — *the two rules whose homes it claimed. README remains the entry-point summary, as ES-005 itself provides. MEMORY's role as a rule home for these two rules ends* |
| **Whether adoption changes practice** | ⛔ **NO.** *It changes enforceability. No artifact requires amendment because of this act* |

---

## 7. What is NOT decided

| | |
|---|---|
| **The 18 unclassified roots** | ⛔ **NOT classified. Explicitly outside this act** |
| **ES-005 as a document** | ⛔ **Remains PROPOSED** |
| **STANDARDS_INDEX's adoption** | ⛔ **NOT decided** |
| **R-37** | ⛔ **Untouched** |
| **ES-005.4's scope** | ⛔ **NOT decided — that is the ARB confirmation it awaits** |
| **Any file move, archive or reorganization** | ⛔ **NONE authorized** |
| **The repository-scope determination** | ⛔ **Outcome B stands, not revisited** |
| **Certification state** | ⛔ **Unchanged.** Operational Evidence still zero-independent |

---

*Traceability: **ES-005-ADOPT-1 EXECUTED** (2026-07-31) · **unit of decision = THE RULE, not the document** · **⛔ §1 COUNT CORRECTED BEFORE DECIDING: the commission said "eight unclassified roots"; there are 24 top-level directories, 6 classified, **18 UNCLASSIFIED** — the figure came from a truncated listing, and PMR-9 (governed since this morning) is what caught it by requiring the count be re-derived from source rather than carried from recollection. `tests/` exists (a prior concern dissolves); `.claude/` and `claude/` both exist and only the dotted one is classified; most unclassified roots are Laravel scaffolding, so the omission looks deliberate in kind though no act has said so** · **ES-005.1 ADOPTED with a NON-EXHAUSTIVENESS RIDER forming part of the adoption** · **ES-005.2 ADOPTED, with its relationship to R-37 determined as COMPLEMENTARY and R-37 stricter at the top level — neither redundant, R-37 unamended** · **ES-005.3 ADOPTED despite "None" instruments, because adoption changes BINDING STATUS rather than implementation; its enforcement is by judgment at EP-02 review as the index records** · **ES-005.4 DEFERRED — adoption UNAVAILABLE, not merely inadvisable, since the rule's own text records "ARB to confirm scope" and a rule whose scope is unconfirmed cannot be made binding without deciding the scope by implication; discharge is a DECISION condition, the register's only one. *This is precisely why per-rule adoption was required: as a document, this rule would have become binding through BUNDLING with its scope decided by nobody*** · **supersession EFFECTED for .2 and .3 only; README remains the entry-point summary** · **NOT decided: the 18 roots · the document's status · the index's adoption · R-37 · ES-005.4's scope · any file movement · Outcome B · certification state** · **adoption changes ENFORCEABILITY, not practice: no artifact requires amendment because of this act.***
