# AI Architecture Reconstruction Report — Evidence Discovery, Not Synthesis

**Commission:** ARB, 2026-07-12, corrected per the same-day ARB critique: *"Reconstruct the current AI Architecture from repository evidence and existing engineering artifacts. Do not invent missing architecture. If evidence is insufficient, record the gap rather than designing a solution."* The corrected workflow (Discover → Classify evidence → Verify placement → Reconstruct → Diagrams → Traceability matrix → Self-consistency) is the one executed below — not the original "synthesize new documentation in `docs/architecture/ai-architecture/`" instruction.

## Executive finding

**The placement litmus (ES-005.3), properly applied rather than rubber-stamped, FAILS for the original commission.** Per the ARB's own instruction — *"If the placement is incorrect, stop, report, do not create documents"* — this report stops the original plan and redirects to what evidence actually supports. **No `docs/architecture/ai-architecture/` tree was created.**

**Discovery, in one sentence: the requested documentation already exists, in two correctly-placed homes, and is 80% complete — the commission's premise ("synthesize new AI Architecture docs") did not survive contact with the repository.**

## Step 1 — Discover (what already exists)

| Location | Content found | Age / status |
|---|---|---|
| `developer_guide/ai_platform/00_ai_engineering_architecture.md` | The "why this exists" overview: the inversion (DDD→domain→capabilities→components→assets→provider binding), 20 foundational principles each pointing to its authoritative home, reading order for newcomers | Mature, governed, current |
| `developer_guide/ai_platform/00_index.md` | Guide series index — **row 03 already reserved**: "written with slice C3" | Current |
| `developer_guide/ai_platform/01_registry_first_workflow.md`, `02_engineering_process_for_developers.md` | Registry workflow, EP-01/EP-02 day-to-day process | Mature, governed |
| `developer_guide/ai_platform/mermaid_diagram_ai_architecture_public_digit.md` | Draft mermaid diagrams (provider-independent stack, bounded contexts, runtime loading order, registry architecture, engineering process, DDD position, knowledge harvest, "what Claude actually is") — **near-identical in content and even structure to what this commission requested** | **Finding, not fixed:** unedited, first-person advisory prose ("I would...", "This was probably the biggest architectural insight"), no status header, no `knowledge_id` — the same red-flag pattern as `Program_Management_Backlog_Guideline.md` and `Program_Progress_Five_Track_Assessment.md`, flagged in yesterday's Artifact Lifecycle Verification Report |
| `engineering/architecture/c4/AI_Engineering_Platform_Views.md` | The corrected, governed version of the above draft: 12 sections (after this session's addendum), Mermaid diagrams, ARB corrections applied 2026-07-08, "frozen artifacts win on conflict" | **Was stale** (zero mentions of ES-001..006/Decision Model/EEP/R-38 before today) — addendum added this session (§12) |
| `engineering/architecture/c4/2026-07-08-arb-diagram-draft-superseded.md` | Explicitly superseded earlier draft | Historical, correctly marked |
| `docs/architecture/c4/` | The **product's** own C4 set (System Context/Container/Component/Code/Runtime/Deployment, narrative `.md` + `.puml` pairs) — a different concern entirely (NRNA/PublicDigit voting platform, not the Engineering Platform) | Established convention; confirms `docs/architecture/` is Product-tier |
| `docs/architecture/ai-architecture/` (the commission's target) | **Empty** — no prior content, no references to it anywhere else in the repository | Orphaned placeholder, not evidence of intent |

## Step 2 — Classify evidence

- **Conceptual/strategic architecture** (why the platform exists, its bounded contexts, its domain model, provider independence) → belongs to, and already exists in, the **Engineering Platform** concern (`engineering/` + `developer_guide/ai_platform/`), because — contradicting the original commission's own litmus answer — **the platform's own diagrams (§2 of `AI_Engineering_Platform_Views.md`) show it as explicitly provider- and reuse-independent** (Claude/Gemini/Codex/Copilot as swappable bindings under one platform). "Is it reusable across projects? No" was asserted, not verified, and the evidence contradicts it.
- **Concrete runtime mechanics** (how hooks/gates literally fire, traced to files) → genuinely did not exist anywhere. This is the one real gap.
- **The raw mermaid-diagram file** → neither conceptual architecture nor mechanics; it is ungoverned draft input that happens to still be sitting in a guide folder.

## Step 3 — Verify placement (ES-005.3 applied honestly)

| Question | Answer (evidence-based, not asserted) |
|---|---|
| Is this content specific to PublicDigit? | No — it describes a provider-independent engineering methodology; PublicDigit is the first (and so far only) product it governs |
| Is it reusable across projects unchanged? | **Yes, by the platform's own self-description** — this is the opposite of the original commission's asserted answer |
| Where does ES-005.1 place reusable Engineering Platform architecture? | `engineering/` (conceptual) and `developer_guide/` (practical how-to), exactly where it already lives |
| Where does ES-005.1 place Product-tier architecture? | `docs/architecture/` — already occupied by the PublicDigit product's own C4 set, a different concern |

**Verdict: `docs/architecture/ai-architecture/` is the WRONG location.** The original commission's placement rationale was asserted, not verified; verification produces the opposite conclusion.

## Step 4 — Reconstruct (the one genuine gap, closed)

Wrote `developer_guide/ai_platform/03_runtime_mechanics.md`, filling the slot already reserved in `00_index.md`. Five sequence diagrams, each traced to a named file, reconstructed from evidence gathered this session (much of it already verified during yesterday's Execution Verification Report and the same-day hook fixes):
1. Session start (`inject-context.sh`)
2. Development flow (`discipline-gate-reminder.sh` + `session-changes-logger.sh`)
3. DB safety blocking (`db-safety-check.sh` — including the exit-2 fix)
4. Merge gate (`composer.json` + `deptrac.yaml` + `greenfield-merge-gate.yml`)
5. Architecture Governance flow (EP-03/EP-01/EP-02 — explicitly marked as the one flow with no automation)

Each diagram states what is **not** machine-enforced, matching the Execution Verification Report's discipline — no diagram implies more certainty than the evidence supports.

## Step 5 — Diagrams (self-consistency check)

All five sequence diagrams in Guide 03 trace to files read directly this session or the previous one; none were invented. The existing `AI_Engineering_Platform_Views.md` diagrams (12 sections after the addendum) were not redrawn — only extended with §12, since the file's own rule is "frozen artifacts win on conflict," and a wholesale redraw would itself violate that rule.

## Step 6 — Traceability matrix

| Architectural element | Repository evidence |
|---|---|
| SessionStart injection | `.claude/scripts/inject-context.sh`, `.claude/settings.json` `SessionStart` block |
| Development discipline reminder | `.claude/scripts/discipline-gate-reminder.sh` (state-aware fix, 2026-07-12) |
| Post-write logging | `.claude/scripts/session-changes-logger.sh` |
| DB safety block | `.claude/scripts/db-safety-check.sh` (exit-2 fix, 2026-07-12) |
| Merge gate | `composer.json` (`merge-gate`/`quality-gate` scripts), `deptrac.yaml`, `.github/workflows/greenfield-merge-gate.yml` |
| Architecture Governance | `docs/implementation/Implementation_Process_v1.1_Draft.md` §EP, `.claude/CLAUDE.md` |
| Constitutional layer (ES-001..006, Decision Model, EEP) | `engineering/governance/STANDARDS_INDEX.md`, `engineering/architecture/reference/Engineering_Decision_Model.md`, `engineering/governance/Engineering_Execution_Protocol.md` |
| "Why this exists" overview | `developer_guide/ai_platform/00_ai_engineering_architecture.md` (pre-existing, unmodified) |

## Step 7 — Self-consistency verification

| Check | Result |
|---|---|
| Placement correct? | **No, for the original target** — corrected to the two homes evidence supports |
| Overview exists and is accurate? | Yes — pre-existing, unmodified, verified current |
| Diagrams present and connected? | Yes — 12 sections (existing) + 1 addendum + 5 new sequence diagrams (Guide 03), all evidence-traced |
| Gaps recorded rather than invented? | Yes — the Architecture Governance flow explicitly states it has no automation, rather than manufacturing a plausible-looking gate for it |
| New concepts introduced? | **None** |

## Findings requiring Decision Authority disposition

1. **`developer_guide/ai_platform/mermaid_diagram_ai_architecture_public_digit.md`** — ungoverned draft prose sitting in a guide folder without a status header. Three options, none executed here: (a) retire it, its content having been superseded by `AI_Engineering_Platform_Views.md`; (b) add a status header marking it explicitly as historical raw input; (c) leave as-is. Recommend (a) or (b); not decided unilaterally, matching how the same-class finding was handled yesterday.
2. **Guide 03's original reservation said "written with slice C3"** — it was written today, before C3 has run. If C3 later reveals the actual hook/gate behavior differs from what's documented here, Guide 03 needs correction, not the runtime — noted explicitly in the guide's own status line.

## Recommendation

No `docs/architecture/ai-architecture/` documents were created — the placement check failed and stopping was the correct response, per the ARB's own instruction. Instead: one stale file was extended with a dated addendum (§12), one genuinely missing guide was written and traced to evidence, and one governance disposition question was surfaced rather than resolved unilaterally. This is smaller in volume than the original commission asked for, and that is the point — the volume the original commission asked for was mostly already there.

---
*Traceability: ARB AI-Architecture reconstruction commission 2026-07-12, corrected same-day per ARB critique (evidence-reconstruction, not synthesis). STOP — submitted to the Decision Authority.*
