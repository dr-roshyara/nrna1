# `KOS-AIP-GOV-STATE-DURABILITY-ADR` — Durable governance state

**Status: 🟡 PROPOSED.** It recommends an architecture and decides nothing.
**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` *(newly initialised)* · **Lane:** `S4-architecture-gov-state-durability` (seq 1 REGISTER · 2 HANDOFF · **3 START**) · **Grant:** `G-KOS-GOV-STATE-DURABILITY`
**Next actor: PO/ARB.**

### ⚠️ Placement — resolved, and one resolution escalated as the rule requires

```
$ doc-placement.php --scope=cross-product --maturity=research
  PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2). exit 2
$ doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research
  docs/knowledgeos                                                     exit 0
```

⭐ **The cross-product reading is UNRULED and is hereby recorded `PENDING` and escalated** *(the rule's own instruction: "Record PENDING and escalate. Do not guess a location.")*. **This artifact is a platform ADR of the same class as `ADR-AIP-01/02/03`, which live at `engineering/architecture/adr/` — a root reserved for `qualified|adopted` maturity. A `PROPOSED` cross-product ADR has no ruled home.** ⛔ **Not guessed around:** the artifact is placed at the reading that **exits 0**, and the gap is returned to Governance.

### Process disclosure — required, and it cuts against this ADR

**Producing process, self-declared and NOT attestable** (`INV-ATTR-1`/`INV-ATTR-2`): **`claude-code-session:5e1dd9ee`**.

> ### ⛔ **This process is not a neutral party to this question, and the reason is specific.**
> It **discovered** the `.claude/runtime` gitignore fact while performing an unrelated governance act, and it authored the C-10 canonical analysis, `D5`, `E2`, the ADR-AIP-04 discovery, Amendment 1, Correction #2 **and several Governance registrations** — meaning **many of the 210 transitions and 99 grants whose durability is the subject of this ADR were recorded by this process.**
> ⇒ **It has an interest in concluding that that record is worth preserving.** **The ADR therefore argues only from measured evidence, and §5 states the reconstructability result in both directions — including the 83% that survives without any change.**
> ⛔ **Bound forward: the producing process cannot later approve its own ADR**, and may not verify or accept it (`R-34`/`P-2`).

---

## 1 · Problem statement

**How should authoritative governance state survive beyond runtime execution?**

The estate holds two kinds of governed artifact: **decision artifacts** (ADRs, reviews, adoption records) and **runtime governance state** (grants, transitions, mutation ownership, session lineage). The first is preserved in git. The second lives under `.claude/runtime/` and is excluded from tracking.

⛔ **This ADR does not begin from the premise that the exclusion is wrong.** The evidence rules bind: `OBSERVED`, `INFERRED` and `RECOMMENDATION` are kept apart throughout, and no claim that *"runtime state is wrong"* or that *"git tracking is required"* is made without analysis.

---

## 2 · Current architecture observation — `OBSERVED` only

| # | Measured fact |
|---|---|
| **O-1** | `.gitignore` line 32 excludes **the whole of `.claude/runtime/`** |
| **O-2** | **16** work-item records hold **210 transitions** and **99 grants** |
| **O-3** | ⭐ **The records store NO derived state.** Top-level keys are `schema · workItem · workflow · roles · transitions · grants`. `mutationOwner`, `sessions` and `workItemState` are **computed by the fold** and never persisted |
| **O-4** | ⭐ **`.claude/runtime` is the ONLY ignored subdirectory of `.claude/`** — `sessions` **35** tracked files · `memory` **46** · `scripts` **13** · `platform` **2** |
| **O-5** | Writes are **atomic** — `tmp + rename`, **one file per work item** |
| **O-6** | ⭐ **`seq` is dense and monotonic in every one of the 16 records** ⇒ a dropped transition is **mechanically detectable as a seq gap** |
| **O-7** | ⭐ **Only 2 of 210 transitions carry any `date` field**, both day-granularity. **The log has no systematic time dimension; ordering is by `seq` alone** |
| **O-8** | The engine's own rule `I-10`: **the record outranks prose** |
| **O-9** | **Reconstructability, measured twice by two independent methods, agreeing exactly:** sessions **58/58 = 100%** cited in tracked documents · grants **83/99 = 83%** · ⭐ **the 16 uncited grants are overwhelmingly `-AMD*` amendments** |
| **O-10** | `EKS-03` already recorded the exclusion and worked around it — *"the workflow records themselves are append-only and gitignored — the mapping document, not an edit, carries the correction"* |

---

## 3 · Governance state model

**What the file actually contains, decomposed:**

| Layer | Content | Nature | Durable today? |
|---|---|---|---|
| **Session Registry** | `transitions[]`, append-only | **historical evidence** | 🔴 no |
| **Authority State** | `grants[]` | **authority record** | 🔴 no |
| **Derived state** | owner · session states · item state | **computed, never stored** (`O-3`) | n/a — nothing to persist |

> ## ⭐ **The load-bearing observation: there is no runtime state in the runtime directory.**
> **`O-3` is measured, not argued: the file persists only an append-only evidence log and an authority record. Every piece of *execution* state is derived by the fold and stored nowhere.**
> ⇒ **`INFERRED`: the artifact under `runtime/` is not runtime state. It is governance evidence that happens to be stored in a runtime location.**

---

## 4 · Runtime vs durable evidence — the three-way distinction

| | Artifact | Durable today |
|---|---|---|
| **source of execution** | `workflow-state.php` (`AST-015`) | ✅ tracked |
| **evidence of execution** | the transition log + grants | 🔴 **untracked** |
| **authoritative decision record** | ADRs, reviews, registrations | ✅ tracked |

> ### ⭐ **`INFERRED` — the inversion, and it is the defect this ADR exists to name:**
> **`I-10` makes the record OUTRANK prose (`O-8`). The outranking artifact is the only untracked one; its derivatives are all durable.**
> **Authority and durability are inverted:** the most authoritative artifact in the estate is the least durable, and the documents that merely *cite* it are preserved.
> ⛔ **This is an inference, not an observation** — it becomes a *risk* only under the failure scenarios of §5, and this ADR does not assert that loss has occurred or is likely.

---

## 5 · Option analysis

### Option A — track `.claude/runtime/` directly

| | |
|---|---|
| **Advantages** | zero new mechanism *(`ES-001.1` parsimony)* · the authoritative artifact becomes durable · git history gives point-in-time recovery · the change is one line of `.gitignore` |
| **Risks** | ⭐ **it ratifies a misfiling.** A directory whose name and ignore-contract say *ephemeral* becomes partly durable. **`O-4` shows `runtime/` is the estate's single designated ephemeral space** — tracking it removes the only place where genuinely transient files can live, and future ephemera would be tracked by default or the ignore re-broadened, recreating the problem |
| **Concurrency** | ⚠️ **the sharpest concrete risk.** `O-5` means no torn writes. But at the **git** level, two concurrent lanes appending to the same record produce a **textual conflict on an append-only JSON array** — and this estate demonstrably runs concurrent lanes *(HEAD moved twice during a single review)*. ⛔ **The danger is not the conflict; it is the resolution: a naive take-ours/take-theirs silently DROPS transitions, and dropped evidence is unrecoverable** |
| ⭐ **Mitigation the evidence supplies** | **`O-6`: `seq` is dense and monotonic, so a dropped transition leaves a detectable gap.** **Silent loss is therefore detectable, though not prevented** — which materially reduces, without removing, the concurrency risk |
| **Recovery** | ✅ strong — git history plus seq-gap detection |

### Option B — governance snapshot model

| | |
|---|---|
| **Snapshot boundary** | ⚠️ **the whole question.** Per transition? per session close? per commit? **A boundary coarser than "per transition" means a window in which evidence exists only in the untracked original** |
| **Authority model** | ⛔ **the snapshot MUST be a projection, never a source.** The governing precedent is `I-4` — *registry ≠ authority, never merged*. **If a snapshot is ever read as authority there are two truths, and the estate has no mechanism to detect divergence between them** |
| **Reconstruction** | ✅ full, if the export is complete and deterministic |
| **Auditability** | ✅ strong — a snapshot commit is itself dated evidence, which also **partially compensates `O-7`'s missing time dimension** |
| ⭐ **Advantage over A** | **a snapshot can be append-only by construction** — one file per transition, or a monotonic log — which **eliminates the merge-conflict class entirely** rather than merely making it detectable |
| **Risk** | ⚠️ a second representation invites divergence · and **who takes the snapshot?** If Governance does, **Governance is preserving its own authority record** — the same reasoning that made this commission Architecture's rather than Governance's. ⇒ **the snapshot must be MECHANICAL, not an act** |

### Option C — runtime-only; documents remain the only durable record

**This is the current de facto state, and its reconstructability is measured rather than estimated (`O-9`):**

| | Reconstructable from tracked documents |
|---|---|
| **session / assignment lineage** | ✅ **58/58 = 100%** |
| **grants** | 🟡 **83/99 = 83%** |
| **the 16 uncited** | 🔴 ⭐ **overwhelmingly `-AMD*` AMENDMENTS** |

> ### ⭐ **`INFERRED` — the lossy layer is precisely the amendment layer, and that is the worst possible place for it to be.**
> **In this estate, amendments carry the load-bearing constraints:** the `E2` field-model corrections (Flags K/L/O), the **seven-criteria independence bar**, the **named routing bars**, the separation conditions. ⇒ **Under Option C, a loss leaves an estate where *who acted* is fully knowable (100%) and *what they were authorized to do after amendment* is not.**
> **Failure scenario, concretely:** a clean checkout on another machine, or `.claude/` cleared, yields the decisions intact and **the authority chain partially gone.**
> **Governance risk:** `O-8` says the record outranks prose. Under Option C the outranking artifact is the absent one, so **prose becomes authoritative by default — authority drift by attrition, with no decision ever taken to that effect.**
> ⚖️ **Stated in the other direction, as the disclosure in the header requires: 83% of grants and 100% of session lineage survive today with no change at all. Option C is not catastrophic, and this ADR does not claim it is.**

---

## 6 · DDD boundary analysis

| Principle | Application |
|---|---|
| **Bounded context** — *Execution mechanism ≠ Governance authority* | **BC-7 Governed Session Orchestration owns the authority record** (its own aggregate, `CAP-14`). `runtime/` is an **infrastructure location**. ⇒ **BC-7's aggregate is currently persisted into an infrastructure space whose contract is ephemerality.** The boundary violation is one of *placement*, not of content |
| **Single responsibility** — runtime executes; governance records preserve authority | **One directory currently carries both jobs**, and its ignore-contract is correct for one of them and wrong for the other |
| **Anti-corruption layer** — *runtime representation ≠ governance truth* | ⛔ **Binding on Option B: the snapshot is a projection of the record, never a source.** `I-4` is the precedent. Under Option A the rule is satisfied trivially, because there is only one representation |
| **Evidence discipline** | §4's three-way split. ⭐ And a consequence worth naming: **the transition log is `GOVERNED` provenance but is `REPRODUCIBLE` only while it exists** — it is not re-derivable from anything else, unlike the fold's output |

> ⭐ **The reframing the DDD analysis produces, and it changes the question:**
> **This is not *"should runtime state be tracked?"* — `O-3` shows there is no runtime state to track.**
> **It is: *governance evidence has been misfiled into a runtime location, and the gitignore is correct for the LOCATION while wrong for the CONTENT.*** ⇒ the defect is **classification**, and both A and B are attempts to fix a durability symptom without touching it.

---

## 7 · Recommended architecture — `RECOMMENDATION`, not a decision

> ## **Recommended: `Option B′` — RELOCATION, which is Option B with the boundary set at the SOURCE rather than at an export.**
>
> **Move the authority record out of `runtime/` into a tracked, append-only governance-evidence location; leave `runtime/` genuinely ephemeral.**

| Why it dominates the three commissioned options | |
|---|---|
| vs **A** | A keeps the evidence in a directory contracted as ephemeral (`O-4`) and inherits the **JSON-array merge-conflict class**. Relocation fixes the classification and can adopt an append-only-by-construction layout |
| vs **B** | B creates a **second representation** and needs a divergence detector the estate does not have. **Relocation has exactly one representation** — the anti-corruption rule is satisfied trivially, as under A |
| vs **C** | C leaves the **amendment layer** — the load-bearing constraints — outside the durable record (`O-9`), and lets prose become authoritative by attrition |

⚠️ **Honest limits of this recommendation:**
* **`B′` is a variant, not one of the three commissioned models.** ⛔ The PO/ARB may reject it as out of scope; **`A` is then the recommended fallback**, on `ES-001.1` parsimony, *provided* the concurrency resolution rule below is adopted with it.
* **Relocation is not free of the concurrency question** — it only makes it *addressable* (append-only layout) rather than *inherent* (single mutable array).
* ⛔ **No location, filename, format or mechanism is proposed here.** That is implementation, and it is excluded.

**One rule this ADR recommends adopting alongside whichever option is chosen:**
> **`R-CONFLICT` (`PROPOSED`): a conflict in the authority record is never resolved by choosing a side. `seq` density (`O-6`) makes loss detectable; a resolution that leaves a seq gap is invalid by construction.**

---

## 8 · Migration considerations

| # | Consideration |
|---|---|
| **M-1** | ✅ **`tokenRef`s are NOT broken by relocation.** They point at **documents**, not at records *(verified: the 40-of-48 path-bearing `tokenRef`s reference `docs/…` paths)*. `EKS-03`'s path-coupling problem is adjacent but distinct |
| **M-2** | ⚠️ **Tracked documents cite record PATHS** (`.claude/runtime/workflow/X.json`) in traceability lines. Relocation makes those citations stale. ⭐ **`EKS-03`'s own adopted principle applies and should be reused, not reinvented: *"historical location is evidence; canonical future location is architecture"*** ⇒ **a mapping document, never an edit** (`ES-005.4`) |
| **M-3** | ⭐ **`O-7` has a migration consequence worth stating precisely: the log has almost no time dimension** (2/210 dated). If it is imported into git, **commit times become the only time evidence — and they would be IMPORT times, not act times.** ⚖️ **This is an existing gap, not one relocation creates: there is almost no time evidence to lose.** ⛔ **Do not let an import be mistaken for a chronology** |
| **M-4** | The 16 records are **independent files**; migration is per-work-item and needs no global cutover |
| **M-5** | ⚠️ **The first commit would import 210 transitions and 99 grants at once.** That single commit is not itself evidence of the acts' timing (`M-3`) and should say so |

---

## 9 · Non-decisions

⛔ **This ADR decides none of:** C-10 category **`D6`** · C-10 ownership **`D7`** · **`D2`** establishment · **Correction #3** verification · **implementation details** — no location, filename, format, tooling or mechanism · whether `.gitignore` should change · whether any migration is authorized.

⛔ **And it makes neither prohibited claim:** it does **not** assert that *"runtime state is wrong"* — `O-3` shows there is no runtime state there — and it does **not** assert that *"git tracking is required"*; `Option C` is analysed on measured reconstructability and found **lossy in a specific layer**, not fatal.

**C-10 is untouched.** ⭐ **And the distinction the commission asked to preserve is preserved: the next thing that can change C-10 is real-world `L2` evidence, not another document — including this one.**

---

**ADR DELIVERED · STATUS `PROPOSED` · STOPPING.**
⛔ **No decision · no implementation · nothing migrated · `.gitignore` unchanged · no self-approval, no self-verification, no self-acceptance · lane NOT closed (`G-1`).**
⚠️ **Two items returned to Governance:** the **`PENDING` placement gap** for a `PROPOSED` cross-product ADR *(`ADR:OQ-2`)*, and the **producer-interest disclosure** in the header.

**Next actor: PO/ARB.**

**Traceability:** `G-KOS-GOV-STATE-DURABILITY` · lane seq 1–3 · `.gitignore:32` · **16 records / 210 transitions / 99 grants, measured** · fold-derived state per `workflow-state.php` (`foldSessions`, `assertTransitionAllowed`) · `I-4` · `I-10` · `EKS-03` *(the gitignore observation and the location-vs-architecture principle)* · `BC-7`/`CAP-14` *(ADR-AIP-03)* · `ES-001.1` · `ES-005.4` · `INV-ATTR-1`/`INV-ATTR-2` · `R-34`/`P-2` · `doc-placement.php` exit 2 on `cross-product-research`.
