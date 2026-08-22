# KOS-SESSION-BOOTSTRAP-001 — Diagnostic and Activation Registration

**Work item:** `KOS-SESSION-BOOTSTRAP-001` · **Classification:** **OPERATIONAL CORRECTION** — evidence registration + PO/ARB activation act
**Status:** **ACTIVATED** (PO/ARB approval of plan `sequential-leaping-koala.md`, 2026-08-22). Implementation delivered and self-verified; **adoption NOT claimed** (`planned → verify`).
**Registered by:** the producing session — **self-declared, not attestable** (`INV-ATTR-1`/`INV-ATTR-2`).
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0).

> ### ⭐ The measured finding this registration exists to hold
> **The divergence is NOT model-dependent. Two AI providers working the SAME repository, SAME workflow records, SAME injected context resolve the SAME governed session differently — and both cannot.** The only difference is the env-level model endpoint. The defect is **coordination legibility**, not model capability.

---

## 1 · The observed defect — reproduced live, first-hand

**Symptom:** a governed session could not deterministically resolve its own lane / role / workflow state / authority at session start.

| # | Measured fact |
|---|---|
| **1** | `session-resolve.php` (AST-016) against the shared record returned **`{"verdict":"AMBIGUOUS","operable":false}`** with **4 candidates** — a session could not select its own lane |
| **2** | `identity S5-architecture-dv-correction-review` returned an `executionContext` embedding **`claude-code-session:a8ce5a39` in free text** — while the running processes were **`8a525719` / `c11a5a12`** |
| **3** | **No read command exposes the predecessor-handoff fact** (open finding **V-3**): AST-015's `fold` computes `handoffsTo` but never emits it — so no lawful interpreter could truthfully answer *"was I handed work?"* |
| **4** | **No single machine-readable bootstrap result existed** — each session reconstructed state ad hoc from prose context |
| **5** | The recorded refusals — *"I will not adopt another process's identity in order to become operable"* — were **correct under the rules**. The tooling gave those sessions **no lawful way to proceed** |

**Consequence (measured):** a session that genuinely held the lane could not *prove it to itself* in machine-readable form, and a session that did not hold it had no fail-closed signal — both were stranded on **AMBIGUOUS**.

## 2 · The comparison matrix — Claude vs DeepSeek (the diagnostic report)

Both providers receive **identical** inputs; only the model endpoint differs. The bootstrap makes **no model call** — the same authoritative state must yield the same result under either provider.

| Dimension | Claude-backed session | DeepSeek-backed session | Identical? |
|---|---|---|---|
| Injected context (CLAUDE.md · CONTEXT.md · MEMORY.md · active plan · session log) | same files, same content | same files, same content | ✅ |
| Authoritative workflow records (`.claude/runtime/workflow/*.json`) | same | same | ✅ |
| Interpreter (`workflow-state.php` / AST-015) | same | same | ✅ |
| CLI / resolver (`session-resolve.php` · `session-bootstrap.php`) | same | same | ✅ |
| Model endpoint (`ANTHROPIC_BASE_URL` + `ANTHROPIC_MODEL`) | Claude | DeepSeek (`deepseek-chat`) | ❌ — the ONLY difference |
| **Bootstrap result** | must equal | must equal | **✅ required — byte-identical (S17)** |

**The model-endpoint difference must be inert to resolution.** Pinned by **S17**: same repo / same records / same `--process-label` / same work-item / same CLI args, run once under a Claude-shaped env and once under a DeepSeek-shaped env → **assert byte-identical JSON stdout**. This session (DeepSeek-backed env) exercises the DeepSeek path; a Claude-backed session resolves identically.

## 3 · Root cause — coordination legibility, not a rule gap

| The deficiency | The lawful correction |
|---|---|
| Process identity lives in **free-text** `executionContext` — not a queryable fact | token-scan the lane's executionContext for the current process label; report ALL referenced labels; attribution is **evidence-only**, never a grant input (`INV-ATTR-1`/`INV-ATTR-2`) |
| **No read command exposes the handoff fact** (V-3) | one **bounded** consumer-side read in `v3HandoffRead()` — the single HANDOFF-to/from-lane fact, pinned by **S16** (poison-test); FULL remedy = an AST-015 read command = `EKS-07 FOLLOW-UP` |
| No machine-readable bootstrap result | one resolver + one `session_bootstrap` JSON report, six-way separation, fail-closed |
| Multi-label prose contexts (e.g. S5 references two labels) | `process_labels_referenced[]` (ALL tokens) + MATCH/MISMATCH/UNKNOWN; fail closed on any multi-label/MISMATCH/UNKNOWN — never grant from identity |

**The four refusals were the system working as designed.** The defect is that the design gave no lawful exit — this correction adds the exit without touching the rules that made the refusals correct.

## 4 · Lawful routes (what the correction adds, without weakening anything)

| Situation | Before (stranded) | After (lawful route) |
|---|---|---|
| Lane registered for THIS process label | no deterministic proof | **RESOLVED** + attribution MATCH + gates evaluated |
| Lane exists, no label match | AMBIGUOUS / guesswork | **UNRESOLVED** with actionable message: missing fact (REGISTER attributing the process) · source (Governance / commission) · next actor (governance) · lane roster with labels |
| >1 lane matches the label | guess | **AMBIGUOUS** — lists all, `disambiguation_required`, never guesses |
| Identity mismatch | (silent or refusals) | **MISMATCH** — no authorization, escalated to governance, never impersonation |
| Grant-scoped work | no scope answer | `--scope` → `authorized_within_scope` (scope-string equality, R6/D-2) |
| Started vs not-started, with a recorded handoff | unconditional "missing handoff" (V-3 untruthful) | truthful `missing_for_start[]` — only the ABSENT G-3 conjuncts (S2b) |

## 5 · The activation act (PO/ARB, 2026-08-22)

**Act:** PO/ARB approved plan `.claude/plans/sequential-leaping-koala.md` as the implementation authorization, **with three binding conditions**:

1. **Adoption handling** — do NOT mark AST-017 `adopted`; registry maps `planned → verify`; adoption follows the governance path after **independent** verification.
2. **The V-3 exception stays exactly one thing** — bounded handoff read only; hard regression **S16**.
3. **Cross-provider conformance** — explicit acceptance criterion; **S17** byte-identical JSON.

Scope (approved): session bootstrap logic · provider/adapter integration (pointers) · context assembly (pointer lines only) · resolver code · tests · docs. ⛔ NOT modified: migration plan · DV-1…DV-7 · RV-1…RV-7 · migration authority · Phase 3 · Phase 5 · human authority rules · `.gitignore` · `executionContext` · `workflow-state.php` · `session-resolve.php` · `inject-context.sh` · `.claude/settings.json`.

## 6 · Implementation delivered (self-verified — NOT adopted)

| Artifact | Commit | Status |
|---|---|---|
| Registry-first: AST-017 entry (`planned`) + AST-016 V-3 annotation | `e850439a` | done |
| RED contract suite (S1–S17, +S2b) | `b05cca61` | RED by absence → GREEN |
| AST-017 resolver + developer guide + suite GREEN | `170e3052` | 18/18, 210 assertions; WorkflowEngine suite 47/47 (522) untouched |
| Governed docs (this pair, EKS-07 addendum, completion report) | pending | this slice |
| Harness pointers (.claude/CLAUDE.md · AGENTS.md · .codex/README.md) | pending | pointer-only |
| Registry flip `planned → verify` (adoption NOT claimed) | pending | slice close |

**Read-purity verified by source:** no write calls in AST-017; the ONLY raw-record access is inside the named `v3HandoffRead()`. **Read-purity verified live:** `git status` before/after bootstrap runs shows `.claude/runtime/workflow/` untouched.

## 7 · EKS-07 relationship — recorded, NOT solved

**`EKS-07` is NOT declared solved.** This work item is a **minimal operational correction** of one observed coordination deficiency. `EKS-07` remains **FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM** in the backlog. The V-3 full remedy (an AST-015 read command) and the SESSION_START wiring are recorded **`EKS-07 FOLLOW-UP`** items — separate governed slices.

## 8 · Traceability

Work item `KOS-SESSION-BOOTSTRAP-001` · PO/ARB activation act 2026-08-22 (three binding conditions) · plan `sequential-leaping-koala.md` · `EKS-07` backlog (evidence for the future exploration; NOT an implementation) · AST-016 V-3 finding · AST-015 (`fold`/`identity`/`authorized`) · AMENDMENT 2 · `INV-ATTR-1`/`INV-ATTR-2` · `G-3` · `Inv E` · `R6`/`D-2` · `R8` · `C-1` · F1 · `ES-005.4` · `ES-004.3` · commits `e850439a` `b05cca61` `170e3052` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
