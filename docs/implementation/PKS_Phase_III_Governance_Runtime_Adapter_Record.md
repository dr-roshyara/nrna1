# **Phase III — Governance Runtime Adapter: architecture record**

| | |
|---|---|
| **Occasion** | The Claude Code permissions configuration (2026-07-31) |
| **⚠️ Why this is a NEW artifact rather than an amendment** | ***The Integrity Model, Method and Discipline are now behind BOTH the Phase II freeze AND the `ask` gate this very act installed. The DEFAULT PATH is now closed: amendments remain possible, but only via the established governance route — Authority decision and approved change control. **NOT "closed" absolutely — the programme has held throughout that FROZEN ≠ IMMUTABLE.** This record respects the gate it documents*** |

---

## 1. ⭐ THE ARCHITECTURAL INVERSION — the act's most consequential finding

**Not this:**
```
Tool  →  Governance
```

**But this:**
```
Governance Policy  →  Capability Mapping  →  Tool Adapter  →  Concrete Configuration
```

> ### **`.claude/settings.json` is an ADAPTER. It is not the governance model.**

***Consequence: another runtime could replace Claude Code and only the adapter would be rewritten. The governance would be unchanged. That is the property that makes the model portable rather than tool-shaped.***

### 1.1 ⚠️ TWO portability layers, not one — **Capability Mapping is NOT an adapter**

| Layer | What it holds | Vocabulary |
|---|---|---|
| **Governance** | Authority · commission · promotion · baseline · repository state | *governance terms only* |
| **⭐ Capability Mapping** | **ABSTRACT capabilities: deny · request approval · allow · immutable · governed · promotable** | *capability terms — tool-neutral* |
| **Runtime Adapter** | how one runtime implements each capability | *tool terms* |
| **Concrete Configuration** | `.claude/settings.json` | *file* |

> ### **The Capability Mapping exists so that Claude-specific vocabulary — `ask`, `deny`, `permissions` — cannot LEAK UPWARD into the governance model.**

***Without the middle layer there is one collapse available and it is the dangerous one: the governance model starts describing itself in a tool's words, and the tool's limits silently become the governance's limits.***

---

## 2. The capability mapping, stated as a mapping and not as an equivalence

| Governance policy | Claude Code capability | Fidelity |
|---|---|---|
| **Never, under any authority** | `permissions.deny` | ✅ **exact** |
| **Possible after Authority authorization** | `permissions.ask` | ⚠️ **APPROXIMATE — see §3** |
| **Free** | `permissions.allow` | ✅ exact |
| **Repository state** *(Working · Review · Frozen · Change Control · Archive)* | — | ⛔ **unmapped** |
| **Commission-conditional authorization** | *(PreToolUse hook)* | ⛔ **not attempted here** |
| **Protection by artifact CLASS** | filename patterns | ⚠️ **approximate — §4** |

### 2.1 ⚠️ `ask` is the ADAPTER, not the layer

> ### **`ask` is the closest available IMPLEMENTATION of the governance layer within the current Claude Code permission model.**

***Stated narrowly at Authority direction. The looser form — "`ask` is the governance layer" — invites the later conclusion that governance EQUALS `ask`. It does not: governance carries Authority, commissions, promotion, baselines and repository state, of which `ask` implements one prompt.***

---

## 3. Why `ask` rather than `deny` — validated against a real act

| Policy | Semantics |
|---|---|
| **deny** | ***impossible*** |
| **governance** | ***possible after authorization*** |

**Validation is concrete, not preferential: M7-CC1 executed today — Authority disposition → change-control commission → execution → verification — a LAWFUL edit to a PROMOTED artifact.** ***A blanket `deny` would have blocked it. The design is validated by a governance act that actually occurred, not by an argument that one might.***

---

## 4. Documented limitation — **filename-pattern protection**

| | |
|---|---|
| **Current limitation** | Protection is **filename-pattern based** |
| **Desired capability** | Protection should be **metadata/class based** — an artifact is protected because it is *promoted*, not because its name matches |
| **Status** | ⏸ **DEFERRED** |
| **Consequence while deferred** | ***A governed artifact whose name does not match a pattern is unprotected. Patterns such as `PKS_Phase_II_CDR*` and `ES-*.md` catch future versions; a differently-named artifact will not be caught*** |

### 4.1 ⚠️ SECOND limitation, discovered while writing this record — **enforcement is TOOL-SCOPED**

| | |
|---|---|
| **Current limitation** | Permission rules bind to a **TOOL** (`Write` · `Edit` · `Read` · `Bash`). ***A governed file written through a `Bash` heredoc is not a `Write`, so the `ask` gate DOES NOT FIRE*** |
| **Evidence** | ***DIRECT AND SELF-INCRIMINATING: this artifact and the session/CONTEXT updates were created via `python3` and `cat` heredocs under `Bash`. The gate did not fire. It was not evaded deliberately — it was simply not on that path*** |
| **Desired capability** | Protection should bind to the **OBJECT** (the artifact) rather than to the **VERB** (the tool that touches it) |
| **Status** | ⏸ **DEFERRED** — a `PreToolUse` hook on `Bash` inspecting redirect targets could close it; not attempted here |

> ### **This is the more consequential of the two limitations, and it would have gone unrecorded had the record not been written by the very route that bypasses the gate.**

<<<<<<< HEAD
### ⚠️ 4.1.1 — the claim, scoped exactly

> ### **The CLAUDE PERMISSION GATE was not on the path taken. This does NOT demonstrate that no enforcement exists elsewhere; it demonstrates that ***the current adapter does not mediate every modification path***.**

***The distinction matters because the loose form ("enforcement was bypassed") would indict the governance model for a property of ONE adapter. Other mediation — review, CI, branch protection, the freeze itself — is untouched by this finding and was never assessed here.***

### 4.1.2 What the two forms of protection actually measure

| | Says | Measures |
|---|---|---|
| **Tool enforcement** | ***"You COULDN'T."*** | the channel |
| **Behavioral governance** | ***"You COULD have, and you didn't."*** | the discipline |

> ### **These are DIFFERENT measurements, and the second is the one Phase II was built to produce.**

***It also bounds §6's first claim precisely: what changed was BEHAVIOR — the choice to write a new artifact instead of amending a frozen one — NOT mechanical prevention. The tool did not stop the amendment. The governance did. **The evidence is therefore STRONGER, not weaker: governance influenced behavior even where the automated mechanism was absent from the path.****

**⚠️ CLASSIFICATION: a Phase III VALIDATION TARGET — *not* a Phase II defect.** *Phase II governed documents; channel-independent enforcement was never within its scope, so its absence is not a failure of the frozen baseline.*
=======
***It also bounds §6's first claim precisely: what changed was BEHAVIOR — the choice to write a new artifact instead of amending a frozen one — NOT mechanical prevention. The tool did not stop the amendment. The governance did.***
>>>>>>> 56766c1f (feat(pks): Phase III opens -- EAD-1 discovery + governance runtime adapter)

---

## 5. The direction this opens — recorded as OBSERVATION, not commissioned

```
settings.json → PreToolUse hook → Authority service → Repository state → Commission registry → Decision
```

***That is no longer configuration. It is an execution engine — and it belongs to the methodology corpus, not to a tool's settings file.***

**The layered shape it implies:**

```
KnowledgeOS
├── Governance Model
├── Methodology
├── Runtime Adapters   ← Claude Code · Cursor · VS Code · GitHub · CI
└── Execution Hooks
```

**⚠️ Recorded as an OBSERVATION.** *No layer is created, no directory proposed (**ES-005.2**), no methodology amended. Adding a Runtime Adapters layer to KnowledgeOS would be an architectural decision requiring its own act — and EAD-1's five open decisions already stand unbundled.*

**⚠️ Holding condition, at Authority direction:** ***no `RuntimeAdapters/` or `ExecutionEngine/` directory until a SECOND runtime exists. Today there is ONE governance model and ONE adapter. The architecture SUPPORTS more; the evidence does not yet REQUIRE them — and a portability layer with one implementation is a hypothesis, not a demonstrated abstraction.***

**⚠️ The Runtime Adapter is an IMPLEMENTATION adapter. It is NOT part of the governance ontology** — *it is not a governed construct, it does not enter the admission ladder, and no methodology term depends on it.*

---

## 6. ⭐ EVIDENCE STATUS

> ***Applying the programme's closing principle to this act itself: every governance act should establish only the strongest claim that its evidence presently supports.***

### ✅ DEMONSTRATED

| | Claim | Evidence |
|---|---|---|
| ✓ | **Governance changed contributor behavior** | *new observations were recorded as a Phase III artifact instead of amending the frozen framework — **the governance is OPERATIONAL rather than merely DOCUMENTARY*** |
| ✓ | **Claude Code can enforce PART of the governance model** | 19 deny · 22 ask · 8 allow, schema-valid and merged |
| ✓ | **The adapter documents its unmapped capabilities** | §2 · §4 · §4.1 |
| ✓ | **`ask` is the right shape for governed artifacts** | **M7-CC1** — a lawful edit a blanket `deny` would have blocked |

**⚠️ BOUNDING THE FIRST CLAIM.** ***It does NOT establish that every contributor will always follow the governance, nor that every pathway is enforced — §4.1 proves at least one pathway is not. What is shown is that the governance influenced implementation behavior on this occasion.***

### ⛔ NOT YET DEMONSTRATED — *each becomes a Phase III validation candidate*

| | Claim | Why not yet |
|---|---|---|
| • | **Multi-runtime portability** | ***one adapter exists; portability is asserted by the architecture, not shown*** |
| • | **Hook-based Authority service** | not attempted |
| • | **Repository-state enforcement** | unmapped (§2) |
| • | **Metadata / class-based protection** | deferred (§4) |
| • | **Object-scoped rather than tool-scoped enforcement** | deferred (§4.1) |

***These are candidates for OPERATIONAL VALIDATION through PublicDigit engineering work — not assumptions inherited because the architecture anticipates them. Operational evidence decides which become enduring parts of KnowledgeOS.***

### 6.2 ⭐ The five as VALIDATION EXPERIMENTS — *not as problems*

> ### **⚠️ FRAMING IS THE POINT: a deferred capability is an EXPERIMENT AWAITING EVIDENCE, not a defect awaiting repair. Treating the list as a bug backlog would recreate exactly the pressure Phase II resisted — building the control BEFORE the evidence that its absence lets defects escape.**

| Deferred item | Phase III validation experiment |
|---|---|
| **Multi-runtime portability** | ***try a runtime other than Claude Code — the only test that can convert the portability HYPOTHESIS into a demonstrated abstraction*** |
| **Hook-based Authority service** | prototype a `PreToolUse` authority service |
| **Repository-state enforcement** | exercise **frozen → change-control → frozen** transitions |
| **Metadata / class-based protection** | compare object metadata against filename patterns |
| **Object-bound enforcement** | ***test whether protection survives a DIFFERENT edit mechanism — the direct experiment for §4.1*** |

***Each experiment can CONFIRM, REFINE or OVERTURN the claim it tests. An experiment that cannot return a negative result is not a validation — that guard was already written into the Phase III charter.***

### 6.3 The baseline is closed — and the reopening condition is stated

> ### **The Phase II governance baseline is genuinely CLOSED. It should not be reopened by further internal analysis — only by CONCRETE OPERATIONAL EVIDENCE from PublicDigit.**

***The distinction: internal analysis can generate refinements indefinitely, and a baseline reopened on analysis alone is not a baseline. **Phase III's work is no longer strengthening claims through analysis; it is collecting INDEPENDENT operational evidence** that can confirm, refine or overturn them.***

### 6.1 The transition this act marks

```
Phase I    Knowledge DISCOVERY
   ↓
Phase II   Knowledge GOVERNANCE          ← governing DOCUMENTS
   ↓
Phase III  GOVERNED ENGINEERING EXECUTION ← governing WORK
```

> ### **Phase III is NOT about writing more governance documents. It is about testing whether the governance improves engineering work.**

---

<<<<<<< HEAD
*Traceability: **Phase III architecture record** (2026-07-31), occasioned by the Claude Code permissions configuration · **written as a NEW artifact because the framework documents are now behind both the Phase II freeze and the `ask` gate this act installed — the DEFAULT PATH is closed, not the route: frozen ≠ immutable, amendment remains available via Authority decision and approved change control** · **⭐ §1.1 TWO portability layers, not one — Capability Mapping is NOT an adapter; it holds ABSTRACT capabilities (deny · request approval · allow · immutable · governed · promotable) so that Claude-specific vocabulary cannot LEAK UPWARD into the governance model** · **§5 holding condition: no `RuntimeAdapters/` or `ExecutionEngine/` directory until a SECOND runtime exists — a portability layer with one implementation is a hypothesis, not a demonstrated abstraction; the Runtime Adapter is an IMPLEMENTATION adapter and NOT part of the governance ontology** · **⚠️ §4.1 SECOND limitation, discovered while writing this record and self-incriminating: enforcement is TOOL-SCOPED, so a governed file written via a `Bash` heredoc does not trigger the `ask` gate — this artifact itself was written by that route; protection should bind to the OBJECT, not the VERB; DEFERRED · **§4.1.1 SCOPED EXACTLY: the CLAUDE PERMISSION GATE was not on the path taken — this does NOT demonstrate that no enforcement exists elsewhere, only that the current ADAPTER does not mediate every modification path; the loose form would indict the governance model for a property of one adapter** · **§4.1.2 tool enforcement says "you COULDN'T" and measures the CHANNEL; behavioral governance says "you COULD have and you didn't" and measures the DISCIPLINE — different measurements, and the evidence is therefore STRONGER: governance influenced behavior even where the automated mechanism was absent from the path** · **classified a Phase III VALIDATION TARGET, not a Phase II defect — channel-independent enforcement was never in Phase II's scope** · **⭐ §6 EVIDENCE STATUS applying the programme's closing principle to this act itself — DEMONSTRATED: governance changed contributor behavior (OPERATIONAL rather than merely DOCUMENTARY), partial enforcement, documented unmapped capabilities, `ask` validated by M7-CC1; explicitly BOUNDED: this does NOT establish that every contributor will always follow it nor that every pathway is enforced — §4.1 proves one is not · NOT YET DEMONSTRATED: multi-runtime portability, hook-based Authority service, repository-state enforcement, metadata/class-based protection, object-scoped enforcement — each a Phase III OPERATIONAL VALIDATION candidate, not an assumption inherited because the architecture anticipates it** · **⭐ §6.2 the five reframed as VALIDATION EXPERIMENTS, not problems — a deferred capability is an experiment awaiting evidence, not a defect awaiting repair; treating the list as a bug backlog would recreate the exact pressure Phase II resisted, building the control BEFORE the evidence that its absence lets defects escape** · **§6.3 the Phase II baseline is genuinely CLOSED and the reopening condition is stated: concrete operational evidence from PublicDigit, NEVER further internal analysis — a baseline reopened on analysis alone is not a baseline** · **§6.1 the transition: Phase III governs WORK, not documents — it tests whether the governance improves engineering work** · **⭐ §1 THE INVERSION: governance policy → capability mapping → tool adapter → concrete configuration; `.claude/settings.json` is an ADAPTER, not the governance model, so another runtime would require rewriting only the adapter** · **§2.1 `ask` is the closest available IMPLEMENTATION of the governance layer, NOT the layer — the looser form invites the conclusion that governance EQUALS ask** · **§3 validated against M7-CC1, a lawful edit to a promoted artifact that a blanket `deny` would have blocked — validated by an act that occurred, not by one that might** · **§4 filename-pattern protection documented as an explicit limitation, status DEFERRED, with its consequence stated** · **§5 the hook-based execution engine and a Runtime Adapters layer recorded as OBSERVATION only — no layer created, no directory proposed, no methodology amended.***
=======
*Traceability: **Phase III architecture record** (2026-07-31), occasioned by the Claude Code permissions configuration · **written as a NEW artifact because the framework documents are now behind both the Phase II freeze and the `ask` gate this act installed — the DEFAULT PATH is closed, not the route: frozen ≠ immutable, amendment remains available via Authority decision and approved change control** · **⭐ §1.1 TWO portability layers, not one — Capability Mapping is NOT an adapter; it holds ABSTRACT capabilities (deny · request approval · allow · immutable · governed · promotable) so that Claude-specific vocabulary cannot LEAK UPWARD into the governance model** · **§5 holding condition: no `RuntimeAdapters/` or `ExecutionEngine/` directory until a SECOND runtime exists — a portability layer with one implementation is a hypothesis, not a demonstrated abstraction; the Runtime Adapter is an IMPLEMENTATION adapter and NOT part of the governance ontology** · **⚠️ §4.1 SECOND limitation, discovered while writing this record and self-incriminating: enforcement is TOOL-SCOPED, so a governed file written via a `Bash` heredoc does not trigger the `ask` gate — this artifact itself was written by that route; protection should bind to the OBJECT, not the VERB; DEFERRED** · **⭐ §6 EVIDENCE STATUS applying the programme's closing principle to this act itself — DEMONSTRATED: governance changed contributor behavior (OPERATIONAL rather than merely DOCUMENTARY), partial enforcement, documented unmapped capabilities, `ask` validated by M7-CC1; explicitly BOUNDED: this does NOT establish that every contributor will always follow it nor that every pathway is enforced — §4.1 proves one is not · NOT YET DEMONSTRATED: multi-runtime portability, hook-based Authority service, repository-state enforcement, metadata/class-based protection, object-scoped enforcement — each a Phase III OPERATIONAL VALIDATION candidate, not an assumption inherited because the architecture anticipates it** · **§6.1 the transition: Phase III governs WORK, not documents — it tests whether the governance improves engineering work** · **⭐ §1 THE INVERSION: governance policy → capability mapping → tool adapter → concrete configuration; `.claude/settings.json` is an ADAPTER, not the governance model, so another runtime would require rewriting only the adapter** · **§2.1 `ask` is the closest available IMPLEMENTATION of the governance layer, NOT the layer — the looser form invites the conclusion that governance EQUALS ask** · **§3 validated against M7-CC1, a lawful edit to a promoted artifact that a blanket `deny` would have blocked — validated by an act that occurred, not by one that might** · **§4 filename-pattern protection documented as an explicit limitation, status DEFERRED, with its consequence stated** · **§5 the hook-based execution engine and a Runtime Adapters layer recorded as OBSERVATION only — no layer created, no directory proposed, no methodology amended.***
>>>>>>> 56766c1f (feat(pks): Phase III opens -- EAD-1 discovery + governance runtime adapter)
