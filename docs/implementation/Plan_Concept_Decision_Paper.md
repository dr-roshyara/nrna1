# Plan Concept Decision Paper — Is "Plan" One Domain Concept or Two?

**Kind:** architectural decision paper (ARB-commissioned, 2026-07-11) · **Status:** PROPOSED — awaiting ARB ruling
**Question:** *Are Work Plans and Engineering Plans distinct domain concepts with different lifecycles, or merely two storage representations of the same concept?*
**Method:** DDD — the concept is defined by nature, lifecycle, ownership, and obligations, never by file format or folder. Storage follows the model.

## Answer

**They are two distinct domain concepts.** The decisive evidence is not the folders (storage evidence) but the **lifecycles and obligations** (domain evidence): the two artifacts answer differently to every domain question below, and one of them can be deleted without loss of governed knowledge while the other cannot.

## The two concepts

| Domain question | **Work Plan** | **Engineering Plan** |
|---|---|---|
| **1. Nature** | Ephemeral working representation — thinking-out-loud that sequences execution for one session. In the frozen knowledge theory: a **WorkingContext** (ephemeral by construction, invariant 8). May be AI-generated and provider-named. | Governed engineering record — the **approved baseline** of an implementation. It is EP-01's object: *approval applies to the plan*. Deliberately authored and named. |
| **2. Lifecycle** | `Need → Plan → Execute → Discard`. Observed: no auto-named plan file was ever updated after its session; none was ever qualified. | `Need → Draft → Review → Approval → Implementation → Qualification (EP-02) → History`. Observed: PB-/EM-/AIP- plans are updated across sessions, referenced by rulings and reports, and closed by completion reviews. |
| **3. Owner** | Runtime (the provider/session — today Claude Code's plan mode; tomorrow another adapter's equivalent). | Engineering governance — the Engineer authors, the **Decision Authority approves**; the approval is the governance act. |
| **4. Persistence requirement** | **None.** Git-tracking is incidental convenience. Deletable at will. | **Durable, mandatory.** EP-02 asks *"did we implement the approved plan?"* — without the persisted plan, that question is unanswerable and the completion review collapses. |
| **5. Qualification requirement** | None — never qualified, never legitimately cited. *(A citation of a Work Plan is the signal that a promotion was missed.)* | ES-004.2 conventions (naming, supersession-by-reference) + EP-02 conformance review; subject to OQ documentation checks. |
| **6. Repository placement (ES-005.1)** | `.claude/plans/` — the Runtime mount point. | `docs/plans/` — the project record. |

**The deletion litmus (the sharpest single test):** delete a Work Plan → nothing governed is lost. Delete an Engineering Plan → EP-02 conformance becomes unverifiable and ruling references dangle. Two artifacts with opposite answers to "may this disappear?" are not one concept.

## Why "two representations of one concept" fails

The counter-position notes both are markdown files called plans, sometimes with shared content lineage. But the constitutional telos itself cuts against it: *documentation is merely one possible representation* — identity lives in the knowledge and its obligations, not the file. The two artifacts carry **different obligations** (none vs. approval/persistence/qualification), **different authority** (none vs. Decision Authority), and **different lifecycles** (discard vs. history). Same format, different concept — exactly as a scratch note and a signed ADR are both markdown.

**The relationship is sequential, not parallel:** Claude Code's Plan Mode is the provider binding of EP-01's Planning Stage — a Work Plan is the **medium in which an Engineering Plan may be drafted**. **EP-01 approval is the promotion event**: approved content receives a governed name and home (an Engineering Plan is born); the Work Plan is thereafter disposable residue. The observed pathology — the rulings register citing `swirling-jingling-blossom.md` as R-36's matrix — is one missed promotion, and is precisely what the merged concept predicts and the split prevents.

## Historical plans are a state, not a third concept

The ARB's predicted trichotomy (Runtime / Engineering / Documentation-Historical) is two concepts and one **lifecycle state**: a "Historical Plan" is an Engineering Plan in its `History` state (ES-004.2: history stands, never path-updated). No third concept exists; modeling it as one would violate parsimony. The 41 existing files across `.claude/plans/`, `claude/plans/` (typo-born), and `docs/plans/` all stand where they are as history regardless of this ruling.

## Recommendation

1. **Adopt the two-concept model:** **Work Plan** (Runtime-owned, ephemeral, `.claude/plans/`) and **Engineering Plan** (governance-owned, durable, `docs/plans/`, ES-004.2).
2. **Classification: clarification, not amendment.** ES-005.1 already decides the placement (DetermineConcern); ES-004.2 gains one scope sentence: *"ES-004.2 governs Engineering Plans (deliberate, approved EP-01 deliverables). Provider plan-mode working files are Runtime artifacts outside this rule's scope; at EP-01 approval, plan content is promoted into a governed Engineering Plan."* No new standard, no new decision, no new folder taxonomy — R-38 undisturbed.
3. **Mechanical consequences (after ruling):** both settings files → `plansDirectory: "./.claude/plans"` (healing the no-dot typo); CLAUDE.md pointers gain the one-line distinction; forward practice — the moment Work-Plan content becomes ARB-referenced, promote it to a named Engineering Plan.
4. **Watch-item for the pilot (no action now):** the Work Plan is empirical evidence for the knowledge theory's WorkingContext; the promotion-at-approval event is a live instance of the ES-006.1 crossing. The pilot can observe whether this mapping holds.

---
*Traceability: ARB plan-concept commissions (2026-07-11, two rounds: storage-evidence verification → domain-question escalation "storage follows the model"). Evidence base: repository census of 41 plan files across three locations; settings consumption proof; governance-citation grep. STOP — ARB ruling decides adoption.*
