# Knowledge Architecture Validation — Consistency Audit (ARB-accepted, 2026-07-08)

**Class:** architecture validation record (Think layer). ARB verdict: audit **accepted**; findings classified as retrospective candidates — **nothing implemented now**.
**Method:** transition-centric (not artifact-centric): every transition is checked for ownership · authority · traceability · single source of truth · absence of governance-bypassing cycles.

## 1. Knowledge-flow audit (original scope)

| Transition | Verdict |
|---|---|
| External Knowledge → Harvest | ⚠ F1 (dual quarantine) — otherwise sound (harvest-source metadata, generated-equivalent authority) |
| Harvest → Engineering Knowledge | ⚠ F2 (three knowledge homes) — transitional, tracked |
| Engineering Knowledge → Standards | ⚠ F3 (promotion event undefined) — destination deliberately deferred (R-32) |
| Standards → Process | ✓ (rules live once; v1.1 draft sole amendment vehicle; EP-vs-ER namespace decision queued) |
| Process → Runtime | ✓ (registry-first, five questions, EP-01, human gates) |
| Runtime → Product | ✓ (PD-01..20; zero write-path to constitutional guards) |
| Product → Evidence | ✓ (evidence-bearing verdicts by construction; gate evidence and pattern evidence correctly distinct) |
| Evidence → Retrospective | ✓ (plan is the single retrospective inbox) |
| Retrospective → Evolution | ✓ (AIP-13 one-amendment rule; promotion chain) |
| Cycle check | ✓ — the one historical bypass (reviewer conversation → governance) is closed by R-34 / classes A–D |

**Findings (ARB dispositions):**
- **F1 — Dual AI-knowledge quarantine** (`architecture/brain_storming/` harvests vs `docs/knowledge/ai/`): real duplication of responsibility. **Retrospective candidate — knowledge-home consolidation; PB-004 decides which location survives. Not solved now.**
- **F2 — Three knowledge homes** (EKP · AKB overlay · Think-layer harvests): "what is Engineering Knowledge?" is genuinely open, and is the same question as the emerging third-domain observation. **Retrospective candidate — Engineering Knowledge domain decision (after PB-004 + one more harvest).**
- **F3 — "Standard" promotion event undefined**: lifecycle Candidate→Observed→Validated→**Standard** ends in a state nothing mints; align with the AIP-02 promotion chain (pattern→Standard = ADR-AIP entry or Standards amendment under ADR authority). **HIGH-PRIORITY retrospective candidate.**
- **F4 — Session log temporarily hosting rule-like content** (30-second question, vocabulary rules, recording test) pending Engineering Standards. Acknowledged, not broken. **Retrospective housekeeping: sweep rule-like log entries into Engineering Standards when R-32 executes.**

Missing-elements check: no missing bounded contexts beyond the deferred Engineering Knowledge domain; `KnowledgeHarvest` aggregate enters the domain model via ADR-AIP amendment at first promotion (intended route); domain events (`PatternPromoted` etc.) have no consumer yet — defining them now would violate the anti-pattern list.

## 2. Standards → Process → Feature lifecycle audit (ARB addition #1)

| Transition | Audit question | Verdict |
|---|---|---|
| Standards → Process | Are all standards executable or referenced by process? | **Pending by design** — Standards doc is post-PB-004 (R-32); today every principle (AIP-01..14) declares a guard host, FF-1..17 are defined with implementation deferred per AIP-14. Tracked, not broken. |
| Process → Feature | Does every feature follow the engineering process? | ✓ **with live evidence:** PB-004 prerequisite steps ran RED-first under the process, and the process *caught a real breach* (TDD lapse on step 2, corrected stash→RED→pop→GREEN — CONTEXT.md process note). Enforcement demonstrated, not asserted. |
| Feature → Evidence | Is evidence produced automatically? | **Partially — in flight:** gates run manually today (output pasted per DoD); AST-010 (slice C3, approved) makes capture deterministic. The gap is the very next scheduled work item. |
| Evidence → Retrospective | Is evidence aggregated without interpretation? | ✓ — Pattern Evidence Register + plan inbox hold pointers to raw records; R-26 forbids interpretation inside instruments. |
| Retrospective → Standards | Are improvements promoted only through evidence? | ⚠ **F3 lands here** — the mechanism (evidence counts, retrospective authority) exists; the terminal approval event does not. Same high-priority candidate. |

## 3. Provider-independence audit (ARB addition #2 — executed, not asserted)

Method: grep for provider vocabulary (`claude`, `anthropic`, `gemini`, `copilot`, `codex`, `cursor`, model names, `plan mode`) across the process docs, ADR-AIP corpus, registry, operating instructions. Results (2026-07-08):

| Audit question | Result | Evidence |
|---|---|---|
| Does any Engineering Standard/principle mention a provider? | **No.** | ADR-AIP corpus: zero hits; AIP table: zero hits |
| Does the Engineering Process depend on a provider? | **No.** Two classified hits: a provider *list* asserting equal treatment ("Claude, Copilot, Cursor, Gemini, Codex behave identically") and `.claude/plans/` **path references** (binding pointers) | `Implementation_Process_v1.1_Draft.md` lines 27, 32 |
| Does any runtime asset depend on provider vocabulary outside the binding? | **No.** Registry hits are binding-asset mechanism notes (`CLAUDE_TOOL_INPUT` in AST-007's VERIFY note) — the registry *is* the binding inventory | registry grep |
| Can another provider replace Claude without changing Standards? | **Yes.** The only translation lives in `.claude/CLAUDE.md` ("Planning Stage maps to Plan Mode") — the binding config file, i.e. exactly where it belongs | pointer section |

**PI-1 (observation, low priority):** process documents reference `.claude/…` paths literally; at provider replacement these path strings update as part of the binding swap. The future Engineering Standards doc should prefer a neutral alias ("the platform runtime directory"). Retrospective note.

**Provider-independence verdict: PASS.** The architecture is about engineering; the provider is an implementation detail — demonstrated by grep, not by declaration. (This audit is FF-15 executed manually; its automation remains a deferred fitness function.)

## Certification

Knowledge in this ecosystem is governed with the same rigor as the election domain: every transition has an owner and an authority, every promotion requires evidence, no cycle bypasses governance, and the platform's independence from its first provider is executable fact. Findings F1–F4 + PI-1 are retrospective candidates with named decision points — none blocks C3 or PB-004.

*Traceability: ARB validation request + acceptance 2026-07-08 (session log) · Baseline corpus (frozen) · ADR-AIP-01/02 · ADR-AIP-LOG R-30..R-35 · pattern cards + Evidence Register (same folder) · grep evidence recorded in session 2026-07-08.*
