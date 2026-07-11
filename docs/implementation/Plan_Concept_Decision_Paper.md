# Plan Concept Decision Paper — Is "Plan" One Domain Concept or Two?

**Kind:** architectural decision paper (ARB-commissioned, 2026-07-11) · **Status:** **HISTORICAL — SUPERSEDED BY the Engineering Standards** (ARB closure, 2026-07-11). The decision is integrated: **ES-004.2** (Engineering-Plan scope) · **Engineering Decision Model → DetermineArtifactLifecycle** (deletion litmus; artifact-promotion pattern) · one runtime sentence in `.claude/CLAUDE.md`. **The standards are the canonical source of truth; this paper is the historical rationale behind them and is not maintained further.**
**Question:** *Are Work Plans and Engineering Plans distinct domain concepts with different lifecycles, or merely two storage representations of the same concept?*
**Method:** DDD — the concept is defined by nature, lifecycle, ownership, and obligations, never by file format or folder. Storage follows the model.

## Answer

**They are two distinct domain concepts.** The decisive evidence is not the folders (storage evidence) but the **lifecycles and obligations** (domain evidence): the two artifacts answer differently to every domain question below, and one of them can be deleted without loss of governed knowledge while the other cannot.

## The two concepts

| Domain question | **Work Plan** | **Engineering Plan** |
|---|---|---|
| **1. Nature** | Ephemeral working representation — thinking-out-loud that sequences execution for one session. *A Work Plan appears to be one possible representation of a **WorkingContext** — a research concept; the Project Knowledge pilot will determine whether this mapping is generally true (hypothesis, not fact — equating them now would promote research into engineering).* May be AI-generated and provider-named. | Governed engineering record — the **approved baseline** of an implementation. It is EP-01's object: *approval applies to the plan*. Deliberately authored and named. |
| **2. Lifecycle** | `Need → Plan → Execute → Discard`. Observed: no auto-named plan file was ever updated after its session; none was ever qualified. | `Need → Draft → Review → Approval → Implementation → Qualification (EP-02) → History`. Observed: PB-/EM-/AIP- plans are updated across sessions, referenced by rulings and reports, and closed by completion reviews. |
| **3. Owner** | Runtime (the provider/session — today Claude Code's plan mode; tomorrow another adapter's equivalent). | Engineering governance — the Engineer authors, the **Decision Authority approves**; the approval is the governance act. |
| **4. Persistence requirement** | **None.** Git-tracking is incidental convenience. Deletable at will. | **Durable, mandatory.** EP-02 asks *"did we implement the approved plan?"* — without the persisted plan, that question is unanswerable and the completion review collapses. |
| **5. Qualification requirement** | None — never qualified, never legitimately cited. *(A citation of a Work Plan is the signal that a promotion was missed.)* | ES-004.2 conventions (naming, supersession-by-reference) + EP-02 conformance review; subject to OQ documentation checks. |
| **6. Repository placement (ES-005.1)** | `.claude/plans/` — the Runtime mount point. | `docs/plans/` — the project record. |

**The deletion litmus (the sharpest single test):** delete a Work Plan → nothing governed is lost. Delete an Engineering Plan → EP-02 conformance becomes unverifiable and ruling references dangle. Two artifacts with opposite answers to "may this disappear?" are not one concept.

## Why "two representations of one concept" fails

The counter-position notes both are markdown files called plans, sometimes with shared content lineage. But the constitutional telos itself cuts against it: *documentation is merely one possible representation* — identity lives in the knowledge and its obligations, not the file. The two artifacts carry **different obligations** (none vs. approval/persistence/qualification), **different authority** (none vs. Decision Authority), and **different lifecycles** (discard vs. history). Same format, different concept — exactly as a scratch note and a signed ADR are both markdown.

**The relationship is sequential, not parallel:** Claude Code's Plan Mode **currently acts as one implementation** of EP-01's Planning Stage (evidence exists for this one implementation only — other providers' planning facilities may map differently) — a Work Plan is the **medium in which an Engineering Plan may be drafted**. **EP-01 approval is the promotion event**: approved content receives a governed name and home (an Engineering Plan is born); the Work Plan is thereafter disposable residue. The observed pathology — the rulings register citing `swirling-jingling-blossom.md` as R-36's matrix — is one missed promotion, and is precisely what the merged concept predicts and the split prevents.

**Concept vs. implementation (ARB refinement):** The Engineering Platform specifies the **concept** of a Work Plan. Individual execution environments may implement that concept differently — some providers may persist plans as files, others in memory, others not at all. **Repository placement is therefore an implementation detail, not part of the domain concept.** (The `.claude/plans/` placement below is today's Claude Code implementation, not a constitutional fact.)

## Historical plans are a state, not a third concept

The ARB's predicted trichotomy (Runtime / Engineering / Documentation-Historical) is two concepts and one **lifecycle state**: a "Historical Plan" is an Engineering Plan in its `History` state (ES-004.2: history stands, never path-updated). No third concept exists; modeling it as one would violate parsimony. The 41 existing files across `.claude/plans/`, `claude/plans/` (typo-born), and `docs/plans/` all stand where they are as history regardless of this ruling.

## Architectural observation — promotion between bounded concerns (recorded, NOT ruled)

The paper has uncovered a more general pattern: **promotion between bounded concerns**. The lifecycle is `Runtime → Promotion Event → Engineering → Qualification → History`. Today it happens for Plans. Tomorrow it may happen for Context, Knowledge, Reports, Evidence, or Specifications. **This is recorded as an architectural observation, not a new rule** — a reusable abstraction the Project Knowledge pilot should test: does the promotion pattern (runtime artifact → governed engineering artifact through an explicit governance event) appear for other artifact types, rather than only for plans?

## Recommendation

1. **Adopt the two-concept model:** **Work Plan** (Runtime-owned, ephemeral, `.claude/plans/`) and **Engineering Plan** (governance-owned, durable, `docs/plans/`, ES-004.2).
2. **Classification: clarification, not amendment.** ES-005.1 already decides the placement (DetermineConcern); ES-004.2 gains one scope sentence: *"ES-004.2 governs Engineering Plans (deliberate, approved EP-01 deliverables). Provider plan-mode working files are Runtime artifacts outside this rule's scope; at EP-01 approval, plan content is promoted into a governed Engineering Plan."* No new standard, no new decision, no new folder taxonomy — R-38 undisturbed.
3. **Mechanical consequences (after ruling):** both settings files → `plansDirectory: "./.claude/plans"` (healing the no-dot typo); CLAUDE.md pointers gain the one-line distinction; forward practice — the moment Work-Plan content becomes ARB-referenced, promote it to a named Engineering Plan.
4. **Watch-items for the pilot (no action now):** (a) the Work Plan ↔ WorkingContext mapping — a hypothesis the pilot tests, never assumed; (b) the promotion-between-concerns pattern above — does it generalize beyond plans?

---
*Traceability: ARB plan-concept commissions (2026-07-11, two rounds: storage-evidence verification → domain-question escalation "storage follows the model"). Evidence base: repository census of 41 plan files across three locations; settings consumption proof; governance-citation grep. STOP — ARB ruling decides adoption.*
