# Provider Independence — Strengthening Strategies

**Author:** PublicDigit Engineering Platform (AI Engineering Architect)
**Date:** 2026-07-08
**Status:** PROPOSED (advisory — no changes authorized; all actions deferred to PB-004 retrospective per R-27/R-29)
**Governance:** This document is Class A (Observation) — it proposes strategies; it does not make decisions. It enters the retrospective inbox alongside EPC candidates.

---

## Context

Provider Independence is the platform's strongest verified attribute (10/10 across three independent grep audits). Zero provider vocabulary exists in the ADR-AIP corpus, principles, or process. The binding surface is enumerable (5 mappings per Phase-03A §7). The `.claude/` folder is explicitly documented as "one implementation, not the architecture."

However, the architecture verification identified three areas where independence could be strengthened:

| # | Finding | Current Severity | Recommendation |
|---|---------|-----------------|----------------|
| PI-1 | Process docs reference `.claude/` literal paths | Minor | Introduce neutral aliases at R-32 (Engineering Standards doc) |
| PI-2 | No second-provider test executed | Structural: PASS; Live proof: pending by design | Design a paper-test protocol; execute only post-PB-004 |
| PI-3 | FF-15 (provider-independence audit) is manual | Minor | Automate as a gate script; combine with C3 or post-PB-004 |
| PI-4 | Binding contract informal (5 rows in Phase-03A §7) | Minor | Formalize the contract; map commitments and invariants |

**Constraint:** This document proposes *strategies only*. All implementation is deferred to the PB-004 retrospective (R-27 governance freeze) unless a demonstrated defect in current provider independence is found — and the freeze's own definition says PI-1/PI-3 do not block PB-004 (R-29: "no platform change unless PB-004 demonstrates insufficiency"). Coincidentally, that is exactly right: PB-004 *is* the first binding endurance test.

---

## 1. PI-1 Resolution — Removing Literal `.claude/` References from Process Docs

### Current Scope

Five categories of `.claude/` references in governed documents:

| Category | Example | Documents | Legitimate? |
|----------|---------|-----------|-------------|
| **Binding pointer** | "Planning Stage maps to Plan Mode" | `.claude/CLAUDE.md` | ✅ **Yes** — this IS the binding |
| **Path pointer** | "a written plan in `.claude/plans/`" | v1.1 Draft EP-01 step 3 | ⚠️ Provider-neutral process referencing a binding path |
| **Script reference** | "tripwire (`.claude/scripts/discipline-gate-reminder.sh`)" | `.claude/CLAUDE.md` §Automation | ⚠️ Same — procedural reference to binding asset |
| **Memory rule** | "global project memory (`~/.claude/...`)" | `.claude/CLAUDE.md` §Memory | ⚠️ Machine-local path in a project doc |
| **Traceability** | "session: `.claude/sessions/2026-07-08.md`" | ADR-AIP-01, ADR-AIP-02 | ✅ **Necessary** — these are ADR provenance records |

**Governing principle (ER-05):** "Replace implementation-specific concepts with domain abstractions."

### Strategy

At the Engineering Standards document creation (R-32, post-PB-004 retrospective), introduce a **neutral alias** for the platform runtime directory. The binding definition itself (`.claude/CLAUDE.md` or a future canonical equivalent) then maps that alias to the literal path.

**Proposed alias: `{platform-runtime}`**

This is a compile-time alias. The provider binding resolves it:

| Before (literal) | After (abstract) | Residence |
|---|---|---|
| "a written plan in `.claude/plans/`" | "a written plan in `{platform-runtime}/plans/`" | Process doc (Implementation Process) |
| "`.claude/scripts/discipline-gate-reminder.sh`" | "`{platform-runtime}/scripts/discipline-gate-reminder.sh`" | Process doc / CLAUDE.md |
| "`~/.claude/...`" | "`{machine: local-install}` — the project scope reserves this for machine-local overrides" | Process doc / CLAUDE.md |

**Resolution path:**

1. At R-32 execution (PB-004 retrospective), define `{platform-runtime}` in the Engineering Standards doc or the registry schema.
2. The binding definition maps it: `{platform-runtime} = .claude/` for the Claude Code provider; `{platform-runtime} = .cursor/` for Cursor; `{platform-runtime} = ${GEMINI_PROJECT_DIR}` for Gemini.
3. Update the Implementation Process (EP-01 step 3, EP-03 automation references) and CLAUDE.md (memory rules, automation references) — all in one governed amendment.

**Exempted:** ADR traceability references (e.g. "session: `.claude/sessions/YYYY-MM-DD.md`"). These are *historical evidence records*, not binding-dependent instructions — they name the file that contains the evidence. At a provider swap they would be read from the old repository; no path substitution needed.

**FF-15 extension opportunity:** after the alias is defined, FF-15 can be tightened to check that no process/standards document uses a literal `.claude/` path outside an ADR traceability block.

---

## 2. Second-Provider Test — Paper Protocol

### Constraint

A full second-provider binding is the only complete evidence of provider independence — and the platform's own rules forbid building it speculatively. AIP-14 says platform-only iterations are exceptional; R-29 says no platform change unless PB-004 demonstrates insufficiency. **We cannot just "try Gemini" and see what breaks.**

### Strategy: Provider-Independence Paper Test

Instead of building a binding, design a **verification protocol** that answers: *If we swapped to provider X tomorrow, which assets would break? Which would survive unchanged?*

**Protocol structure:**

```
1. Enumerate the current binding surface (Phase-03A §7's 5 mappings).
2. For each mapping, define WHAT the current provider does (not HOW).
3. For each WHAT, assess against a hypothetical second provider:
   a. Does the second provider have an equivalent concept?
   b. Is the mapping 1:1 or does it require structural adaptation?
   c. Does the adaptation touch platform code or only the binding layer?
4. Identify gaps where the second provider lacks a concept entirely.
5. Score: structural readiness (no gap) vs adaptation needed vs blocker.
```

**Five mappings assessed against three hypothetical providers:**

#### Mapping 1: Composition Root (current: `.claude/settings.json`)

| Provider | Equivalent? | Adaptation |
|----------|------------|------------|
| **Gemini** (Google IDX / Project IDX settings) | ✅ Project settings file | Map `settings.json` hook descriptors to Gemini's equivalent. 1:1 conceptual mapping. |
| **Codex** (GitHub Copilot / Chat) | ✅ `.github/copilot.yml` or IDE plugin config | Copilot Chat uses `.github/copilot.yml` for instructions. Hooks may differ. |
| **Cursor** (Cursor rules) | ✅ `.cursorrules` + `cursor.json` | Cursor's `--rules-file` is the conceptual equivalent. Settings structure differs. |

**Verdict:** 1:1 for all three. Composition root is a universally supported concept.

#### Mapping 2: Runtime Moments (current: hook events)

| Provider | Equivalent? | Adaptation |
|----------|------------|------------|
| **Gemini** | ⚠️ Pre-action/post-action hooks in IDX | Conceptually equivalent; exact event surface differs. The Enum (5 moments) maps cleanly; the hook wiring syntax does not. |
| **Codex** | ⚠️ VS Code extension API (onDidChangeTextDocument, etc.) | Different approach — Codex is primarily IDE-integrated; would require an extension wrapper. |
| **Cursor** | ✅ Pre/Post tool-use hooks in Cursor | Closest equivalent. Cursor supports `rules` and `scripts` directories conceptually similar to `.claude/scripts/`. |

**Verdict:** Adaptation required for all three. The *concept* (before-action, after-action, session-start, stop) is provider-neutral; the *realization* (hook registration, event names, payload shape) is not. This is the most adaptation-heavy mapping.

#### Mapping 3: On-Demand Capability Invocation (current: Skills)

| Provider | Equivalent? | Adaptation |
|----------|------------|------------|
| **Gemini** | ✅ Commands / Agents | Gemini supports custom commands and agent definitions. Conceptually equivalent. |
| **Codex** | ✅ Custom commands | GitHub Copilot Chat allows custom commands via `copilot.yml`. |
| **Cursor** | ✅ Cursor agents | Cursor has a dedicated agent mechanism with `.cursorrules` for customization. |

**Verdict:** Equivalent concept across all three. Naming and registration syntax differ; no structural blocker.

#### Mapping 4: AI Actor Sub-Workers (current: subagents)

| Provider | Equivalent? | Adaptation |
|----------|------------|------------|
| **Gemini** | ✅ Multi-agent / parallel agents | Gemini supports agent delegation with tool restrictions. Conceptually equivalent. |
| **Codex** | ⚠️ VS Code task agents | Codex's agent architecture is less mature; limited delegation. Would need a simpler sub-worker model. |
| **Cursor** | ⚠️ Cursor agent delegation | Cursor supports agent loops but with different isolation guarantees. |

**Verdict:** Gemini and Cursor have equivalents. Codex would need a simpler model — the platform's current subagent pattern (restricted tools + human approval) is the right abstraction; only the delegation mechanism changes.

#### Mapping 5: Bootstrap Injection (current: SessionStart hook)

| Provider | Equivalent? | Adaptation |
|----------|------------|------------|
| **Gemini** | ⚠️ Pre-session context injection | No standard equivalent; would need a session-start template approach. |
| **Codex** | ⚠️ Conversation context from `.github/copilot.yml` | Partial — Copilot Chat loads rules but no structured bootstrap mechanism. |
| **Cursor** | ✅ Cursor rules file loads at session start | `.cursorrules` loads declaratively. Content injection logic would need adapting. |

**Verdict:** Cursor has the closest equivalent. Gemini and Codex would need workarounds. However, the bootstrap content (MEMORY + CONTEXT + plan + log) is provider-neutral — only the injection mechanism changes.

### Go/No-Go Test Threshold

A paper test passes for a provider if:

- **Maps 1, 3, 4** — conceptual equivalent exists and mapping is documented
- **Map 2 (Runtime Moments)** — at least 3 of 5 moments have an equivalent adaptation path
- **Map 5 (Bootstrap)** — a documented injection strategy exists (even if not the same mechanism)

**Do NOT execute the paper test before PB-004** — it would be speculative platform work (AIP-14 violation). Instead, the C3 fresh-session boot *is* the binding stress test (same provider, zero conversational memory — proving that the bootstrap, registry, guards, and verification all work from cold start). C3's outcome becomes the first evidence point for binding readiness. Only if C3 reveals a structural binding flaw should the paper test be considered before PB-004.

### Future: Full Replacement Test

The platform's self-defined qualification: running PB-004 through a second provider with no changes to the engineering process, standards, or registry. This is **not an iteration goal** — it is a post-Operational Readiness v1.0 strategic experiment. Timing: after the PB-004 retrospective, when the platform has proven itself with Claude first.

---

## 3. FF-15 Automation — Provider-Independence Audit

### Current State

FF-15 (provider independence fitness function) exists as a definition only. Three manual grep audits have been executed (all PASS, zero hits):

| Audit | Date | Method | Result |
|-------|------|--------|--------|
| Audit #1 | 2026-07-08 | ARB-commissioned knowledge-architecture validation | PASS (documented in `knowledge_architecture_validation.md` §3) |
| Audit #2 | 2026-07-08 | Architecture verification report (Phase 2) | PASS (re-grepped independently) |
| Audit #3 | 2026-07-08 | This verification review | PASS (fresh grep of ADR-AIP corpus + registry + process) |

### Strategy: Three-Tier Automation

**Tier 1 — Fast grep (add to C3 gate runner AST-010)**

The simplest automation: a grep script that checks for provider vocabulary in the governed corpus. **This is the right target for C3's scope**, since it's a measuring instrument (R-26) — run → capture → PASS/FAIL → stop. No interpretation.

```bash
# ff-15-provider-audit.sh (pseudocode)
#
# Measuring instrument per R-26: run, capture, PASS/FAIL, stop.
# No interpretation. No explanation. No recommendation.

set -e
error=0

# Provider vocabulary must appear ONLY in the binding layer.
# Binding layer = .claude/CLAUDE.md (and any future binding config).
#
# The governed corpus = everything EXCEPT the binding.

BINDING=".claude/CLAUDE.md"

# Provider names to forbid outside the binding
FORBIDDEN="claude|anthropic|claude-code|plan mode"

# Scanned areas (exclude binding, gitignored runtime, vendor)
SCAN_DIRS="docs/ .claude/platform/ .claude/scripts/ .claude/CLAUDE.md"

for term in "${FORBIDDEN[@]}"; do
  # Count occurrences in the binding (expected) vs governed corpus
  if grep -rqi "$term" $SCAN_DIRS --include="*.md" --include="*.yaml" --include="*.json" \
       --exclude="$BINDING" 2>/dev/null | grep -v "^$BINDING:"; then
    echo "FAIL: Provider term '$term' found outside binding"
    error=1
  fi
done

if [ "$error" -eq 0 ]; then
  echo "PASS: No provider vocabulary in governed corpus"
fi
exit $error
```

**Tier 2 — Falsifiability test (companion to Tier 1)**

A companion script that verifies the audit catches known violations. Inject a synthetic provider term, run the audit, verify it fails. Remove the injection, re-run, verify it passes. This is the falsifiability discipline already practiced by the registry validator.

```bash
# ff-15-falsifiability.sh (pseudocode)
# Inject a known violation
echo "# Provider-test: this uses Gemini vocabulary" > /tmp/ff15-test.md

if ! bash ff-15-provider-audit.sh 2>/dev/null; then
  echo "PASS: Falsifiability proven — audit caught synthetic violation"
else
  echo "FAIL: Audit missed synthetic violation — gate is broken"
  exit 1
fi
```

**Tier 3 — Registry-scope expansion (post-PB-004)**

Once `{platform-runtime}` alias is introduced (PI-1 resolution), extend the audit to:

1. Check every registered asset's `trace` fields for provider vocabulary
2. Check hook scripts for literal `.claude/` path references (non-binding scripts should use the alias)
3. Verify the loading order: provider binding is loaded LAST and its vocabulary never contaminates earlier layers

### Placement

| Tier | When | Where | Engine |
|------|------|-------|--------|
| **Tier 1** | C3 or post-PB-004 | AST-010 (`run-gates.sh`) or companion script | Verification Engine (CMP-005) |
| **Tier 2** | Same as Tier 1 | Companion falsifiability script | Verification Engine (CMP-005) |
| **Tier 3** | After R-32 execution | Expanded gate | Verification Engine (CMP-005) |

**Recommendation:** Tier 1 and 2 are small enough to add to C3's scope *if the ARB approves a scope extension*. Under the freeze, C3 is "AST-010 only, no interpretation" — a grep script qualifies as a measuring instrument. But C3's scope is already ARB-approved; extending it would require an amendment. The safer route: **post-PB-004, when the governance freeze lifts, add Tier 1 as a named EPC-012 (Health Check) implementation slice.** The freeze itself says "unless PB-004 demonstrates insufficiency" — if a manual audit was run three times in one day (as it was), that *is* evidence of insufficiency.

---

## 4. Binding Documentation — The Provider Contract

### Current State

Phase-03A §7 documents the binding as a 5-row table:

| Reference concept | Current binding |
|---|---|
| Composition root | Provider's project settings file |
| Runtime moments | Provider's lifecycle hook events |
| On-demand capability invocation | Provider's skill/command mechanism |
| AI actor sub-workers | Provider's subagent mechanism |
| Bootstrap injection | Provider's session-start hook |

This is a **reference-map** (what maps to what). It is not a **contract** (what each mapping must provide, what invariants each must maintain).

### Strategy: Binding Contract Schema

The binding contract lives **alongside** the reference map — distinct, not merged into it. The contract says *what the platform expects*; the reference map says *how the current provider fulfills it*.

**Recommended structure:** `platform-runtime/binding/BINDING_CONTRACT.md` — a single file under the platform-runtime directory, defining:

#### Section 1: Identity

```yaml
provider:
  name: "Claude Code"
  version: "latest"  # declared tolerance; contract validated at this version
  binding_interface_version: "1.0"
  mapping_status: "active"   # active | draft | deprecated
```

#### Section 2: Mapping Contract (the 5 required mappings, each with INVARIANTS)

For each of the 5 mappings, the contract specifies:

| Field | Meaning | Example |
|-------|---------|---------|
| **Reference concept** | Provider-neutral name from Phase-03A §7 | `composition_root` |
| **Required behavior** | What the platform expects, in provider-neutral language | "Loading the platform registry on session start" |
| **Invariant** | What must remain true regardless of provider | "Registry is loaded before any engine is invoked. Loading order: config → registry → knowledge → rules → hooks → commands → agents." |
| **Provider surface** | Where the provider's implementation exists | `.claude/settings.json` (hooks section) |
| **Fitness check** | How FF-15 verifies this mapping is clean | "No provider terms outside this file" |
| **Swap effort** | Estimated work to re-implement for a generic provider | "Medium — hooks API differs; concept is universal" |

**Example for runtime_moments:**

```yaml
- mapping: runtime_moments
  provider_neutral: "session_start"
  required_behavior: "Load and inject MEMORY.md, CONTEXT.md, active plan, and today's session log before any engineering work begins"
  invariant: "Loading order: configuration → registry → knowledge → rules. Injection is deterministic (CONTEXT.md Plan: line has highest priority). Provider must not reorder or skip governed sources."
  provider_surface: ".claude/settings.json → SessionStart hook → .claude/scripts/inject-context.sh"
  fitness_check: "inject-context.sh contains zero provider vocabulary; the SessionStart registration in settings.json is the sole provider-specific line"
  swap_effort: "Low — the script is provider-neutral; only the registration mechanism changes"
```

#### Section 3: Prohibitions (what the binding MUST NOT do)

```yaml
- No engineering judgment: The binding translates; it never decides.
- No governance: The binding does not add ADRs, rulings, principles, or standards.
- No hidden state: All binding state must be in versioned repository files.
- No provider vocabulary leak: Provider-specific terms must not appear outside this directory.
- No auto-creation: The binding does not create new platform assets without registry entry + verification.
```

These are the **binding-specific guardrails** that implement PD-06, PD-07, PD-19 at the provider boundary. They are the reason `.claude/hooks/timestamp-plan.sh` (machine-local, AIP-03 violation) was correctly deprecated as AST-008.

#### Section 4: Migration Path for a New Provider

A step-by-step template for swapping providers:

```
1. Create new binding directory at {platform-runtime-new}/
2. Map each of the 5 reference concepts to the new provider's mechanisms
3. Copy binding-neutral assets (scripts, registry, rules) — these do NOT change
4. Register new provider identity in the binding contract
5. Run verification suite (FF-15 audit + falsifiability)
6. Run one PB ticket as qualification (the ticket that proved insufficient with Claude)
7. Certify: if the same engineering decisions were reached, provider independence is proven
```

### Relation to Phase-03A

The binding contract does NOT replace or modify Phase-03A §7. Phase-03A is **frozen architecture** — it defines the reference concept. The binding contract is a **runtime artifact** in the provider binding layer — it documents the current realization of each concept for the current provider.

Phase-03A says: "The composition root exists."
The binding contract says: "For Claude Code, the composition root is `.claude/settings.json`. The invariant is: it loads the registry first."

### When to Write It

**Not before PB-004.** A formal binding contract is not needed to unblock the current provider. It is an **evidence-driven improvement**: if the PB-004 process reveals binding ambiguity (e.g., "where does this hook firing belong in the reference model?"), that evidence justifies writing the contract.

The natural trigger: **the first moment someone cannot determine whether a concern belongs in the binding or the platform.** That ambiguity is the evidence gap. Before that, the informal 5-row map in Phase-03A §7 suffices.

**Placement after the retrospective:** the binding contract joins the Engineering Standards doc (R-32) as a companion document — not inside it, but co-equal under `docs/engineering/`. The standards define the *invariants* the binding must maintain; the contract defines *how* the current provider maintains them.

---

## Summary — Implementation Roadmap

| Strategy | Action | Timeline | Status |
|----------|--------|----------|--------|
| **PI-1** | Introduce `{platform-runtime}` alias | At R-32 execution | Deferred (PB-004 retrospective) |
| **PI-1** | Update process docs to use alias | Same amendment as R-32 | Deferred |
| **PI-2** | Document paper-test protocol | Design now; execute post-retrospective | **Proposed here** |
| **PI-2** | Execute C3 as self-replacement test | Next session | Approved (ARB) — nearest proxy |
| **PI-3** | Tier 1: add grep to C3 gate | If ARB extends C3 scope; else post-retrospective | Candidate (Tier 1 is small enough for C3) |
| **PI-3** | Tier 2: falsifiability companion | Same slice as Tier 1 | Candidate |
| **PI-3** | Tier 3: expanded registry audit | Post-R-32 | Deferred |
| **PI-4** | Write formal binding contract | First evidence of binding ambiguity | Deferred (no ambiguity yet) |
| **PI-4** | Add swap-effort column to each mapping | Same contract document | Deferred |
| **FF-15** | Automate provider-vocabulary audit | Combine with Tier 1 or post-retrospective | Candidate |

### Freeze-Compliance Notes

| Action | Freeze-Legal? | Rationale |
|--------|---------------|-----------|
| Design this document | ✅ Yes | Class A observation — no implementation |
| Execute C3 with grep | ⚠️ Maybe — scope extension | ARB approved C3 as "AST-010 only"; extending requires re-approval |
| Write binding contract | ❌ No | Would be new governance artifact (R-27 violation) |
| Run paper test | ❌ No | Speculative platform work (AIP-14 violation) |
| PB-004 through the platform | ✅ Yes — this IS the mandate | Operational Readiness v1.0 (R-33) |

---

## Decision Needed

One architectural decision would unblock multiple strategies (PI-1, FF-15 Tier 3, binding contract placement):

> **Where does the binding documentation live after the platform-runtime alias is introduced?**

Four options (presented for ARB consideration, Class A — no decision made):

| Option | Structure | Pros | Cons |
|--------|-----------|------|------|
| **A** | Under `{platform-runtime}/binding/` (e.g., `.claude/binding/`) | Colocated with implementation; self-contained | Mixes binding docs with binding code in a non-standard location |
| **B** | Under `docs/engineering/binding/` (alongside R-32 standards) | All governance docs under one roof | Binding is runtime, not governance — conflating them violates the documentation responsibility table |
| **C** | Under `{platform-runtime}/BINDING_CONTRACT.md` (single file at root) | Simplicity — one file, obvious location | Lacks structure for multiple providers or cross-references |
| **D** | Defer until a second provider exists — let the shape emerge from real use | Pure evidence-driven; no over-design | First binding lacks documentation that would make the swap smoother |

**D is the safest choice under AIP-14 and R-27** — it is the most consistent with the platform's evidence-before-design principle. The informal 5-row map in Phase-03A §7 is holding fine. Formalize only when a second provider is on the roadmap.

---

*This document is Class A (Observation). It enters the retrospective inbox. Nothing herein authorizes implementation. The governance freeze (R-27/R-29) remains binding. PB-004 is the platform's qualification — and its first binding endurance test.*
