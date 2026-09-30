# Source passage (verbatim): session log 2026-08-15, lines 455-500

  5. **V-3 follow-up created: `KOS-ACTIVATION-REPORTING-001`** (`architecture-decision`, four canonical roles) — mechanism record **and** a versioned intake artifact. **0 sessions, 0 grants = OPEN but uncommissioned.** The dual record is deliberate: under `E-1` a record-only work item would be invisible to git.
  6. **Adoption registered** — `adoption: adopted`, with the comment stating adoption does **not** resolve V-3.
  7. State verified (both folds + YAML parse ok).
  8/9. **Closure evidence recorded and closure declared.**
  10. Final state re-read and reported.
* **Exactly ONE transition appended.** No history rewritten; `workflow-state.php` (sha256 `e19705ce` unchanged) and `session-resolve.php` untouched; no test, architecture or evidence altered.
* **Condition 1 DISCHARGED. Condition 2 BINDING AND OUTSTANDING** — recorded in two places so it cannot be lost: `AST-016.runtime_moments` in the registry, and §7 of the follow-up intake.
* **V-3 preserved intact, not weakened:** classification, the one-item scope narrowing, the `INV-DISC-7` ↔ G-3-completeness conflict, the ellipsis finding, the CASE-A/B control, the fail-safe posture, and the cost already identified in remedy option 2 are all carried into the follow-up artifact. **No remedy chosen.**

### Discrepancy found and reported, not worked around — `O-CLOSURE-VOCAB`

* **The governed mechanism cannot express closure.** Probed directly rather than assumed: `append --json='{"type":"CLOSE",…}'` → `refused: unknown transition type 'CLOSE' — … no such edge exists in the machine`, **exit 65, record unchanged (still 18 transitions)**.
* Vocabulary is `REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL`; `workItemState` is only ever `OPEN` or `STOPPED`.
* **`STOP` deliberately NOT misused** — `STOPPED` means *halted*, is sticky, and exits only via `CONTINUATION`; recording a successful closure as `STOPPED` would assert the work was interrupted and awaits resumption, corrupting Inv E to satisfy a bookkeeping wish.
* ⇒ **The machine record still reads `OPEN` although the item is closed. Qualification, adoption and closure are ALL documentary states in this platform.** Pre-existing, not caused here: `KOS-AI-ORCH-001-INC1` is documented closed while its record reads `OPEN` with its verification never completed.
* **Compounds with `E-1`:** the states that are authoritative live only in versioned documents; the record that is machine-readable is untracked and cannot represent them. **Recommended as its own work item — deliberately NOT folded into this closure.**

## Decisions

* PO: verification start (this date). Prior decisions stand as registered on 2026-08-14.
* Governance (Session 2): V-3 classification **upheld** as architecture/specification gap; recommendation **A — QUALIFY** with two conditions. **Disposition remains the PO/ARB's — Governance chose no remedy and issued no grant.**
* **PO/ARB: QUALIFY (conditional) — registered verbatim. `AST-016` QUALIFIED then ADOPTED. `KOS-SESSION-DISCOVERY-001` CLOSED. V-3 remains OPEN and unresolved in `KOS-ACTIVATION-REPORTING-001`.**

## Problems

* None new in the implementation. The successor-registration bootstrap gap remains recorded (2nd occurrence) with a recommended improvement work item awaiting commissioning.
* **Governance-hygiene items, all deliberately kept separate:** `E-1` (authoritative record untracked/gitignored) · **`O-CLOSURE-VOCAB`** (the mechanism has no closure/qualification/adoption vocabulary — new, found during finalization) · the bootstrap gap. **`E-2` is CORRECTED (seq 18).**

### Ninth — PO/ARB ACCEPTED the finalization; vocabulary ruling registered and applied

* **Acceptance registered verbatim** (`…-qualification-adoption-closure.md` §8): *"I would accept this Governance result."* · *"This is not a reason to reopen this work item."*
* **⚠️ BINDING VOCABULARY RULING — applies to ALL future documentation, not just this artifact:** closure must always be stated with its plane named —
  > **Governance/documentary closure: CLOSED.**
  > **Authoritative workflow-machine state: OPEN, because AST-015 has no closure transition.**

  *"Do **not** allow future documentation to casually say that the machine record itself says CLOSED. It doesn't."*
* **Applied immediately** to the closure artifact §5 (headline restated in the ruled two-line form), `CONTEXT.md`, and this log's §Eighth heading. My earlier single-line "is CLOSED" headline was exactly the formulation the ruling forbids — corrected, not defended.
* **PO/ARB architectural observation registered — three distinct state planes**, which are *not* one state machine: AST-015 execution state (`OPEN`/`STOPPED`, machine-authoritative) · governance lifecycle state (`QUALIFIED`/`ADOPTED`/`CLOSED`, documentary) · architecture findings (`V-3` open, documentary). *"The danger would be pretending they are one state machine when they currently are not."*
* **Disposition:** registered as the PO's **framing of `O-CLOSURE-VOCAB`**, to belong to that item **if and when it is commissioned**. **NOT promoted to methodology** — single occurrence, and ES-006.1 forbids promoting from one. Whether the planes should be unified, formally separated, or left alone is **architecture work nobody is yet authorized to perform**.
* **Nothing reopened. Nothing commissioned. Sessions 1 and 3 not re-engaged.**

### Tenth — Execution-topology two-terminal operating model RECORDED as a proposal; two prompt claims corrected against the record

* Artifact: `docs/publicdigit/reviews/2026-08-15-execution-topology-two-terminal-governed-workflow-intake.md` — **PROPOSED operating model, not adopted/mandatory/binding.** **No work item, no grant, no transition, no code/mechanism/registry change.** Proposed next actor: **ARCHITECTURE** (explicitly **not** commissioned).
* **Two commissioning-prompt statements are NOT supported by the record — reported, not repaired, and not written in as fact:**
  * **`V-4` has no referent.** The finding series is **V-1/V-2/V-3 only**; every repo `V-4` belongs to unrelated documents (Round-38C capture vectors, PKS Phase-II vocabulary risks). **Not invented, not renumbered, not silently dropped** — if such an observation exists it lives outside the governed record and must be submitted before it can be cross-referenced.
