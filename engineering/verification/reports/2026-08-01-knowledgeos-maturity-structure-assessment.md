# KnowledgeOS Maturity Structure — Assessment

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Question:** should `engineering/knowledge/` express the ES-006 promotion lifecycle as directories (`research/ · proposals/ · pilots/ · methodology/ · standards/`)?
**Repository Integrity Gate:** ✅ PASSED. **No directory created · no file relocated · no standard amended.**

---

> ## RESULT — **the reframe is confirmed by canon; the proposed structure is blocked by it.**
>
> **✅ Confirmed: `engineering/` does NOT mean "canon."** **ES-005.1** says so directly: *"`engineering/` = **Engineering Platform** (how it is engineered)."* I had been reasoning as though the tree meant *adopted canon*; **it means the platform.** That correction stands and improves the model.
>
> **⛔ Blocked, three ways** — and none of them is mine to lift.

---

## 1. What blocks it

| # | Blocker | Text |
|---|---|---|
| **1** | **ES-005.2 — The Folder Rule** | *"A directory exists only when its first artifact arrives. **Reserved namespaces are documented (README table), never created speculatively.**"* **Only `research/` would have an artifact today.** `proposals/`, `pilots/`, `standards/` would be **speculative — created for a lifecycle stage nothing currently occupies** |
| **2** | **R-37 — Architecture Freeze 2.0 (structural)** | *"**no new capability hierarchy · no more document reorganizations**"* until C3 passes, PB-004 completes, and the retrospective runs. **A maturity-tiered directory hierarchy is precisely a new capability hierarchy.** R-37 also **reverses the burden of proof**: expansion requires *evidence the existing architecture was insufficient* |
| **3** | **ES-005.3 — The Placement Litmus** | *"**Research artifacts remain project-side (`docs/implementation/`)** until promoted through qualification."* **The proposal would put research inside `engineering/` — the opposite of what the clause says.** That is not a structural addition; **it is an amendment to ES-005** |

**Blocker 3 is the substantive one.** 1 and 2 are timing and procedure; **3 is a direct contradiction of an issued standard.**

## 2. The tension the reframe exposes — worth naming, not mine to resolve

**The reframe and ES-005.3 disagree about where research lives, and both have a coherent case.**

| | Claim |
|---|---|
| **ES-005.3** | research is **project-side** (`docs/implementation/`) until qualified |
| **The reframe** | **KnowledgeOS is its own product**; its research is **KnowledgeOS's**, not PublicDigit's |

> **The reframe's argument is strong, and the clause's own wording is the evidence for it.** ES-005.3 names a **PublicDigit path** (`docs/implementation/`) as the home for *all* research — which reads like a rule written when `engineering/` was not yet treated as a product in its own right. **If KnowledgeOS is a product, "put its research in the product's folder" is a category error.**
>
> **But that is an argument for amending ES-005.3, not for acting around it.** The clause is issued and unambiguous.

**Recorded as a genuine tension for the ARB. I am not resolving it, and I note that my earlier reports leaned on ES-005.3's final clause without noticing this strain in it.**

## 3. The sanctioned path, if the ARB wants the structure

**ES-005.2 supplies the mechanism for exactly this situation:**

> *"**Reserved namespaces are documented (README table)**, never created speculatively."*

**So the maturity tiers can be declared as reserved namespaces in the `engineering/` README — expressing the lifecycle in the repository's documentation without creating a single empty directory.** That respects **ES-005.2** and does not trip **R-37**, because nothing is reorganized and no hierarchy is instantiated.

**Directories then appear one at a time, each when its first artifact legitimately arrives** — which is the folder rule working as designed.

**This does not resolve blocker 3.** Reserved namespaces would still place research inside `engineering/`, which ES-005.3 forbids until amended.

## 4. On the exception question

**Recorded as the position given: no permanent exception.** The checkpoint was introduced to prevent exactly this class of placement, and **granting a permanent exception as its first outcome would hollow it out.**

**The two acceptable outcomes:**

| Outcome | Condition |
|---|---|
| **Relocate** | ⭐ preferred — **but the destination depends on the §2 tension.** Under ES-005.3 as written: `docs/implementation/`. Under the reframe: a KnowledgeOS research namespace **that does not yet exist and cannot be created without amending ES-005.3** |
| **Temporary, time-bounded exception** | acceptable **only** with an explicit expiry, an acknowledgement of non-compliance, preserved Research maturity, and an obligation to resolve. **Not "it is useful"; not open-ended** |

> **The honest position: relocation is preferred, and it is currently blocked on a question only the ARB can answer** — *where does KnowledgeOS research live?* **Until that is settled, a time-bounded exception is the only compliant way to leave the file where it is**, because the alternative is moving it to a location the ARB may be about to reject.

## 5. Recommendation

**Three questions, in dependency order. Only the first is architectural.**

1. **Does ES-005.3's research clause still hold now that `engineering/` is treated as a product?** *(ES-005 amendment — ARB)*
2. **If it does not:** declare the maturity tiers as **reserved namespaces** per ES-005.2, and let directories appear as artifacts arrive. *(No R-37 collision.)*
3. **Meanwhile:** a **time-bounded** placement exception for `Layer_Verification_Rule.md`, expiring when (1) is answered.

**Nothing here requires a new lifecycle, a new standard, or a generic `implementation/` folder** — and the reframe is right that a generic `implementation/` would collapse two bounded contexts into one directory, which **ES-005.1's three-concern separation** already forbids.

---

**Traceability:** **ES-005.1** (three-concern separation — confirms `engineering/` = Engineering Platform) · **ES-005.2** (folder rule; reserved namespaces) · **ES-005.3** (placement litmus — the clause in tension) · **ES-005.4** (never a copy) · **ES-006.1** (the ladder the structure would mirror) · **R-37** (structural freeze; reversed burden of proof). **No directory created · no file moved · no standard amended · no exception granted.**
