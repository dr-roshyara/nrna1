# `KOS-AIP-GOV-STATE-DURABILITY-IMPLEMENTATION-DESIGN`

**Status: 🟡 PROPOSED — DESIGN ONLY.** ⛔ **Nothing implemented · nothing migrated · no path selected.**
**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **Lane:** `S4b-architecture-gov-state-impl-design` (seq 4 REGISTER · 5 HANDOFF · **6 START**) · **Grant:** `G-KOS-GOV-STATE-IMPL-DESIGN`
**Placement derived:** `--scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0), per **D3** *(existing placement governance applies)*.

> **`WHAT was decided ≠ HOW it will be implemented.` The mistake this design exists to avoid: *"B′ approved → move files."***

**Producing process, self-declared and NOT attestable** (`INV-ATTR-1`/`INV-ATTR-2`): **`claude-code-session:5e1dd9ee`** — **author of the ADR whose recommendation D1 adopted.** ⛔ **Bound: may not approve, verify or accept the ADR or this design** (`R-34`/`P-2`). Producing a downstream design for an already-adopted decision is a production act, not an approval.
**Registered on the existing work item, not as a second record** — the ADR and the DECISION both live here; a second record would split one chain's evidence.

**Fixed and not revisited:** **D1** `B′` relocation · **D2** `R-CONFLICT` adopted · **D3** existing placement governance, `ADR:OQ-2` open under its existing owner.

---

## 1 · Current state analysis — `OBSERVED`, nothing modified

| | Fact |
|---|---|
| **Locations** | `.claude/runtime/workflow/*.json`, one file per work item |
| **Volume** | ⭐ **17 records · 213 transitions · 102 grants** — re-counted at this lane's START, **up from 16/210/99 when the ADR was written.** The subject **grows while the decision is unimplemented**, which sharpens the decision's own warning that the estate *"remains in the `C` condition"* |
| **Writers** | ⭐ **exactly one — `workflow-state.php`.** `saveRecord()` is called from **three** sites only: `init` (:291), `append` (:311), `grant` (:338). Writes are atomic (`tmp` + `rename`) |
| **Readers** | `workflow-state.php` (`fold`, `identity`, `authorized`) · **`session-resolve.php`** (`AST-016`) · `registry.yaml` records the path as **asset metadata** · session logs and traceability lines cite it as **prose** |
| **Fold / reconstruction** | `foldSessions()` derives `sessions`, `mutationOwner`, `workItemState` **from the transition log at read time**; `assertTransitionAllowed()` refuses illegal transitions. ⭐ **No derived state is persisted** — the ADR's `O-3` |
| **Sequence** | `seq` is **dense and monotonic per record** ⇒ omission is mechanically detectable |
| **Time** | ⚠️ **only 2 of 213 transitions carry any `date`** ⇒ **no systematic time dimension**; order is `seq` alone |

### 1.1 Runtime-only assumptions, enumerated

| # | Assumption | ⚠️ |
|---|---|---|
| **RA-1** | `workflow-state.php:81` defaults `--dir` to `.claude/runtime/workflow` | named by the decision |
| **RA-2** | ⭐ **`session-resolve.php:74` defaults `$recordDir` to the SAME path — independently** | 🔴 **NOT in the decision's follow-up list.** ⇒ **reconciling one default is insufficient; there are two** |
| **RA-3** | `.gitignore:25/32` exclude `.claude/runtime/` | ✅ **correct under `B′` and must NOT be relaxed** |
| **RA-4** | `registry.yaml` carries the path as an `AST-015/016` asset fact | a registry act, not a document rewrite |
| **RA-5** | Traceability lines in tracked documents cite the path | see §5 |

> ### ⭐ **The single most useful current-state fact: `--dir` is OVERRIDABLE in both scripts.**
> ⇒ **The mechanism already supports relocation. Only the DEFAULT contradicts `B′`.** **No engine redesign is implied by D1** — which materially reduces the change surface.

---

## 2 · Target boundary design — four boundaries, ⛔ **no paths**

| Boundary | Owns | Lifecycle | Durability |
|---|---|---|---|
| **Governance evidence** | the append-only transition log · grants · provenance | **append-only, immutable once written** | ✅ **durable and tracked** |
| **Execution** | derived fold output · caches · locks · process information | **disposable** | 🔴 **ephemeral, gitignored — unchanged** |
| **Ownership** | ⭐ **BC-7 owns the CONTENT** (`CAP-14`, its own aggregate). Storage location is **infrastructure**, and infrastructure never owns the record | — | — |
| **Lifecycle** | ⭐ **the discriminator: evidence is written once and never revised; execution state is rebuilt on demand.** Two lifecycles ⇒ two locations | — | — |

⭐ **This restates `B′` as a boundary rather than a path, which is why no path is needed to state it:** *the authority record was never runtime's to export* — it is being **placed correctly for the first time**, not exported.

⛔ **Not decided here:** filesystem location · repository structure · file granularity · naming · format.

---

## 3 · Migration strategy

**Required properties (from D2):** append-only preservation · no deletion · no sequence rewriting · provenance preservation.

### 3.1 ⭐ The design's central constraint: **copy bytes, do not re-emit**

**`saveRecord()` writes `json_encode(… JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)`.** A migration that *re-serialises* a record could change bytes (key order, escaping, whitespace) **without changing meaning** — and then **no hash comparison between old and new is possible**, destroying the only cheap integrity check available.

> ⛔ **Migration MUST be a byte-preserving copy, never a parse-and-rewrite.** Verification is then a hash equality per record, which is `REPRODUCIBLE` evidence.

### 3.2 Ordered sequence — and the order is the design

```
1  COPY      each record, byte-identically, into the governance evidence boundary
2  VERIFY    hash equality per record          ← REPRODUCIBLE; a failure stops the migration
3  SWITCH    both path defaults (RA-1 and RA-2) atomically, in one change
4  DEMOTE    the runtime copy: authority withdrawn BY DECLARATION
5  REMOVE    the runtime copy — only after 1–4
```

> ### ⭐ **Why this order, and why it is not arbitrary: durability BEFORE demotion.**
> **Step 5 is only safe after step 2**, because once a durable copy exists, removing the runtime file is **not deletion of history** — the history lives durably. **Reversing 2 and 5 would make removal a deletion, which `R-CONFLICT` forbids outright.**

### 3.3 ⚠️ The unavoidable divergence window

**Between step 1 and step 3, any `append` still writes to the OLD path** — and this estate demonstrably runs **concurrent lanes** *(HEAD moved twice during a single review)*.

| Mitigation | Assessment |
|---|---|
| **(a) quiesce all writes during migration** | ⛔ **cannot be guaranteed** — no lock exists, and no lane can be prevented from acting |
| **(b) accept the window and reconcile under `R-CONFLICT`** | ✅ **the only sound plan.** §4 specifies exactly how |

⭐ **Consequence: `R-CONFLICT` is not a contingency for this migration — it is a REQUIRED STEP of it.** *(This is the decision's own load-bearing consequence, made operational.)*

---

## 4 · `R-CONFLICT` application — the four mandated answers

### 4.1 Which is authoritative?

| Phase | Authoritative |
|---|---|
| before step 3 | **runtime** |
| after step 3 | **the governance evidence boundary** |
| ⭐ **within the window** | 🔴 **NEITHER by default.** ⛔ **The window has no automatic winner** — that is what "no silent resolution" means here |

### 4.2 How is divergence recorded?

**A divergence record** written into the governance evidence boundary, naming **both** sides, **both** `seq` ranges, and the per-record hashes. ⛔ **Never a merge in place, and never an edit of either side.**

### 4.3 How is conflict preserved?

**Both records are retained.** ⛔ **Neither is deleted** (`R-CONFLICT`), and the divergence record makes the disagreement itself part of the evidence rather than a resolved absence.

### 4.4 How is reconciliation performed? — ⭐ **and this is where `seq` density pays**

> **`seq` is dense and monotonic per record, so divergence has exactly two shapes and only one of them is mechanical:**

| Shape | Test | Resolution |
|---|---|---|
| ⭐ **STRICT EXTENSION** | evidence holds `1…M`, runtime holds `1…N` with `M < N`, **and the first `M` are byte-identical** | ✅ **append the runtime tail `M+1…N`.** **This is a UNION that preserves both sides and every sequence number — it is NOT "choosing a side"** and is therefore `R-CONFLICT`-compliant |
| 🔴 **PREFIX DISAGREEMENT** | the common prefix differs at any `seq` | ⛔ **MUST NOT be resolved mechanically.** Two writers produced different content at the same sequence position, which is a **genuine authority conflict** ⇒ **record the divergence, preserve both, and ESCALATE.** A resolution requires a PO/ARB act |

⭐ **`R-CONFLICT` compliance test for any resolution, stated so it is checkable: after reconciliation the record's `seq` set must be dense, monotonic, and a superset of both inputs' sequence sets. A resolution leaving a gap, or dropping any input's content, is invalid by construction.**

---

## 5 · Compatibility design

| Surface | Impact | Design response |
|---|---|---|
| **`workflow-state.php`** | `RA-1` default | switch the default; ⛔ **no change to `saveRecord`, the fold, or the refusal gate** |
| ⭐ **`session-resolve.php`** | `RA-2` — **a second, independent default** | ⭐ **must switch in the SAME change as `RA-1`.** ⚠️ **If only one switches, one reader sees the old path and one the new — that is a HIDDEN SECOND TRUTH, the exact defect §7 must exclude** |
| **`tokenRef`s** | ✅ **unaffected** — they point at **documents**, not at records *(verified)* |
| **Documentation references** (`RA-5`) | traceability lines cite the old path | ⭐ **Reuse `EKS-03`'s adopted principle rather than reinventing it (`ES-005.4`): *"historical location is evidence; canonical future location is architecture"*** ⇒ **a mapping document, never edits.** ✅ Historical references remain valid **because they are historical**; ⛔ and the commission's rule is honoured — **no document is rewritten to hide the migration** |
| **`registry.yaml`** (`RA-4`) | records the path as an asset fact | a **registry act** — declaring an asset's location, not rewriting prose |
| **The 2/213 date coverage** | git commit time would be **import** time | ⛔ **an import must not be presented as a chronology.** Pre-existing gap; relocation neither creates nor cures it |

---

## 6 · Implementation options

| | Option | Advantages | Risks | `R-CONFLICT` compliance | Migration impact |
|---|---|---|---|---|---|
| **A** | **Move existing records** | simplest; byte-preserving; format unchanged | ⚠️ *"move"* reads as delete-from-source — **safe only under §3.2's ordering** (durability before removal) | ✅ **compliant iff §3.2's order holds** | low; per-record |
| **B** | **New governance evidence store** | states the boundary explicitly; same byte preservation | ⚠️ materially the same as A once paths are abstract — **the difference is vocabulary, not mechanism** | ✅ same as A | low |
| **C** | **Append-only ledger** (one file per transition, or a log) | ⭐ **eliminates the JSON-array merge-conflict class entirely**, and makes *"no rewriting sequence"* **structurally** enforced rather than procedurally | 🔴 **it is a FORMAT change, not a relocation** — it changes `saveRecord`, the fold's input, and every reader | ✅ strongest | ⚠️ **high** |
| ⭐ **D** | **RELOCATE NOW, LEDGER LATER** — do the byte-preserving relocation (A/B) with the format **unchanged**, and record the ledger as a **separate future decision** | keeps the change surface minimal; delivers durability immediately; preserves hash-verifiability | ⚠️ leaves the merge-conflict class **detectable but not eliminated** — mitigated by §4.4 | ✅ compliant | low now, deferred later |

> ### ⭐ **Recommended: `Option D`.**
> **Reason, and it is a boundary reason rather than a preference: D1 decided RELOCATION, not RE-FORMATTING.** ⛔ **`Option C` exceeds the decision** — a format change would need its own PO/ARB act, and adopting it here would be an implementation design amending an architecture decision, the precise error `Flag O` established as impermissible.
> **`ES-001.1` parsimony agrees: relocation is the smallest change that discharges D1.**

---

## 7 · Security / governance review

| Must not introduce | Assessment |
|---|---|
| **hidden authority sources** | 🔴 **REAL RISK, and it is `RA-2`.** Two independent path defaults ⇒ **switching one creates two readers with two truths.** ✅ **Excluded only by switching both in one change** |
| **duplicate truth stores** | ⚠️ **the runtime copy after step 2 IS a duplicate.** ✅ **Resolved by §3.2's steps 4–5: authority withdrawn by declaration, then removed — and removal is not deletion of history because durability already exists.** ⛔ **The tension is real and the ORDER is what resolves it** |
| **runtime authority ownership** | ✅ excluded by the default switch **plus** the declaration; ⛔ **`.gitignore` for runtime must NOT be relaxed** — that would re-create the misfiling `B′` corrects |
| **undocumented mutation paths** | 🔴 ⭐ **NEW FINDING, and it is not in the decision or the ADR: `--dir` is an unconstrained override.** Any invocation may write an authority record into an arbitrary directory, and **nothing validates the target.** It is a pre-existing mutation path that **becomes more consequential after relocation**, because the durable location becomes the thing worth diverting. ⛔ **Not decided here** — recorded as requiring its own decision |

---

## 8 · Non-decisions

⛔ **No exact filesystem location · no repository structure · no file granularity or naming · no implementation language · no tooling · no C-10 existence · no `D2` capability establishment · no `D6` category · no `D7` ownership · `D1`/`D2`/`D3` not revisited · `ADR:OQ-2` untouched · `.gitignore` unchanged · `.claude/runtime/` untouched · nothing migrated · nothing implemented.**

**Returned for PO/ARB attention — only where new architectural decisions emerge:**
1. ⭐ **`RA-2`** — a **second** path default the decision's follow-up list does not name; reconciling one is insufficient.
2. ⭐ **`--dir` as an unconstrained mutation path** (§7) — a pre-existing gap made sharper by relocation.
3. **`Option C`'s format change** — if the ledger is wanted, it needs **its own decision**; this design deliberately does not take it.

---

**DESIGN DELIVERED · STATUS `PROPOSED` · STOPPING.**
⛔ **Not implemented · nothing migrated · no self-approval, no self-verification, no self-acceptance · lane NOT closed (`G-1`).**

**Next: PO/ARB review only if the three items above are treated as new architectural decisions; otherwise the design proceeds to review, then a migration plan, then execution, then verification.**

**Traceability:** `G-KOS-GOV-STATE-IMPL-DESIGN` · lane seq 4–6 · **the registered DECISION** *(`STATUS: DECIDED`; its §5 consequences and §6 follow-ups)* · ADR `67a8e75e` *(`O-3`, `O-6`, `O-7`, `O-9`)* · **code read directly: `workflow-state.php` `recordPath`:79–84, `saveRecord`:99, call sites :291/:311/:338, `foldSessions`, `assertTransitionAllowed`; `session-resolve.php`:74** · `.gitignore:25/32` · `registry.yaml` · **17 records / 213 transitions / 102 grants, re-counted at START** · `EKS-03` · `ES-001.1` · `ES-005.4` · `R-34`/`P-2` · `G-1`.
