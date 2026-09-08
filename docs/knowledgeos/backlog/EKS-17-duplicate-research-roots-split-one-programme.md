# EKS-17 — Two folders share the same name at different levels, and one research programme is split across both with no overlap

**Status:** **BACKLOG · NAVIGATION & DISCOVERABILITY HAZARD** — registered from `P-55` (`docs/knowledgeos/theory-extraction/73-P55-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — repository navigation / evidence discoverability.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no move, rename or reorganization, and questions no research finding.**

---

## 1 · The problem in plain terms

The estate has **two different folders both called `research`** — one at the top of the repository, one
nested inside the knowledge area. They are not copies of each other and neither is a leftover.

⭐⭐⭐ **A single research programme — the kernel-reduction study — is split across both, and the two
halves do not overlap at all:**

| where | what is there |
|---|---|
| `research/kernel-reduction/` | ⭐ the **executable study** — the runner, the code package, the results |
| `docs/knowledgeos/research/kernel-reduction/` | ⭐ the **written study** — 20 numbered documents, from the research question through ablation results and negative results to the final report |

$$\boxed{\textbf{Same folder name. Same programme name. } \mathbf{Zero\ files\ in\ common.}}$$

⚠️ **The same shape exists for `verification`:** a top-level `verification/` folder exists; a folder of
that name inside the knowledge area **does not**. So an instruction naming *"the verification folder"*
resolves to exactly one place — and a reader who assumes the other pattern finds nothing and may
reasonably conclude nothing is there.

## 2 · Why this is a business problem rather than untidiness

**a. A search of one half silently succeeds.** ⭐⭐ Searching the wrong `research` folder does not
produce an error. It produces **results** — just the wrong half. There is no signal that anything is
missing, so the reader stops looking.

**b. Instructions cannot be given unambiguously.** ⭐ When a commission, a plan or a person says
*"check the research folder"*, that sentence has **two correct readings**. Whoever receives it will
pick one, and neither party will know that a choice was made.

**c. Evidence and its conclusions can be read apart from each other.** ⭐⭐⭐ **The most serious case
is the one already present:** someone reading only `research/kernel-reduction/` sees results with no
research question, no method and no stated limitations; someone reading only the documents sees
conclusions with no reproducible computation behind them. **Each half looks complete on its own.**

**d. It has already happened, in this session.** A review scoped its coverage statement to one
`research` folder, reported a file count for it, and only found the other while following an unrelated
lead. ⚠️ **The review's conclusion was unaffected** — the material it had missed was outside the date
window it was examining — **but the coverage statement it published was narrower than it read**, and
that was luck, not control.

**e. The cost of the failure is asymmetric.** Finding the second folder costs seconds. Not finding it
can cost a re-derivation of work that already exists, which is the problem recorded in `EKS-16`.

## 3 · What is *not* the problem

⛔ **Neither folder is misplaced.** There are legitimate reasons for code to sit at the repository root
and for written knowledge to sit under the documentation tree. **This item does not claim either
location is wrong.**

⛔ **No reorganization is proposed.** Moving files has its own risks — broken references, lost history —
and the estate has an existing, governed placement rule for deciding such things. **That decision is
not made here.**

⛔ **The split itself may even be correct.** ⭐ The problem is not that the halves live apart. **It is
that nothing at either location tells a reader that the other half exists.**

## 4 · Candidate requirement (a direction, not a design)

> ⭐ **Where one body of work is deliberately split across two locations, each location should say so
> and point at the other; and where two folders at different levels share a name, that should be
> visible to anyone standing in either one.**

⭐⭐ **This is the cheapest possible remedy space** — it is satisfied by a sentence in an index file,
and it requires nobody to move anything or to remember anything.

## 5 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-16` — derivation without consulting existing theory | both end in material not being found | ⛔ `EKS-16` is **"the search was not run"**. This is **"the search was run, and the path was ambiguous"**. Different cause; `EKS-16`'s remedy (search first) does **not** fix this one, because the searcher here did search |
| `EKS-02` — subject-derived placement enforcement | both concern where documents live | ⛔ `EKS-02` asks **where a new document belongs**. This asks **how a reader finds an existing one that is split** |
| `EKS-03` — governed relocation of historical misplaced documents | both concern folder layout | ⛔ `EKS-03` presumes documents are **misplaced**. ⭐ This item explicitly does **not** claim either location is wrong |
| `EKS-06` — reference resolution bound to a fixed token list | both concern resolving a name to a thing | ⛔ `EKS-06` is a checker's coverage of identifier families inside documents; this is folder-name ambiguity in the repository itself |

⭐ **Checked and distinct on all four counts.**

## 6 · Urgency

**Low today, and it stays low — but it does not go away.** No decision currently rests on the split,
and the cost of each individual occurrence is small. ⚠️ **What makes it worth recording is frequency:
every future search of either folder is exposed, and the exposure is invisible when it fires.** ⭐ It
is also, unusually, a problem whose remedy is far cheaper than a single occurrence of the failure.

## 7 · Evidence

* `research/` — 253 files: `kernel-reduction/` (runner, package, results) and `knowledgeos-sim/`.
* `docs/knowledgeos/research/` — 69 files: `kernel-reduction/` (20 documents + final report),
  `theory-v1.1-simulation/`, `theory-v1.2-simulation/`.
* `diff -rq` across the two `kernel-reduction/` trees: **no file appears in both**.
* `docs/knowledgeos/theory-extraction/73-P55-…` §12 — the self-reported occurrence.
* `docs/knowledgeos/theory-extraction/72-P54-…` §3 — the coverage statement that was narrower than it read.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed, no file is moved, and no work is commissioned.**
