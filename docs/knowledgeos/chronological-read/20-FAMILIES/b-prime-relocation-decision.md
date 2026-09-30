# b-prime-relocation-decision

**Scope(s):** CROSS-OBJECT · **Row count:** 13 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** B', Option B' · **Aliases:** Governance Evidence Relocation
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0004 · scope CROSS-OBJECT — "The PO/ARB decision (S0130) selecting Option B' (Governance Evidence Relocation) as the durability model, separating the Execution Context from the Governance Evidence Context."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0128 §"Recommended: Option B′ — RELOCATION, which is Option B with the boundary set at the SOURCE rather than at an export."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0131 §"Governance evidence ... Execution ... Ownership ... Lifecycle -- the discriminator: evidence is written once and never revised; execution state is rebuilt on demand."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0131 §"COPY each record, byte-identically ... VERIFY hash equality per record ... SWITCH both path defaults atomically ... DEMOTE the runtime copy ... REMOVE the runtime copy — only after 1–4"]
- CANDIDATE-GOVERNANCE-BIRTH: [S0130 §"B′ — Governance Evidence Relocation ... The authority record is NOT exported from runtime — it belongs to the governance evidence boundary."]

## Lifecycle
last_seen: S0300. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0128, S0130, S0131, S0132 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0131, S0300 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0131, S0152 |
| dependencies | PRESENT | S0130, S0131 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0130, S0131, S0152, S0300 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0131, S0132 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- (ARGUMENT) Recommends relocating the authority record out of runtime/ into a tracked, append-only governance-evidence location (Option B'), reasoned as dominating A (avoids the merge-conflict class inherent to a directory contracted as ephemeral) and B (has exactly one representation, avoiding the anti-corruption/divergence problem) and C (does not leave the amendment layer outside durable history); flags this recommendation as a variant beyond the three commissioned options. [S0128]
- (ANALYSIS) The R-CONFLICT decision (D2) binds how the relocation (D1) may be implemented: the migration must preserve sequence integrity and provenance, must not delete or re-create history, and if a relocated record and a runtime record ever diverge, R-CONFLICT governs — neither side may be silently chosen. [S0130]
- (ALTERNATIVE/ARGUMENT) Four implementation options assessed (A: move existing records; B: new store, same mechanism; C: append-only ledger — eliminates the merge-conflict class but is a format change exceeding the decision's scope; D: relocate now with the current format, defer a ledger to a separate future decision); D is recommended as the smallest change that discharges the D1 decision without amending it via implementation design. [S0131]
- (ANALYSIS/WARNING) Observes that the migration's own inventory, hash-verification, and reconciliation records are themselves governance evidence and cannot be allowed to live only in the runtime location being retired, or the migration's proof of durability would itself be non-durable, defeating the purpose of B'. [S0132]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0128] types=[ARGUMENT] scope=OBJECT — "Recommends relocating the authority record out of runtime/ into a tracked, append-only governance-evidence location (Option B'), reasoned as dominating A (avoids the merge-conflict class inherent to a directory contracted as ephemeral) and B (has exactly one representation, avoiding the anti-corruption/divergence problem) and C (does not leave the amendment layer outside durable history); flags this recommendation as a variant beyond the three commissioned options." (anchor: "Recommended: Option B′ — RELOCATION, which is Option B with the boundary set at the SOURCE rather than at an export.")
- [S0130] types=[GOVERNANCE] scope=CROSS-OBJECT — "Formal decision: D1 selects Option B' (governance evidence becomes a first-class durable artifact; runtime remains execution-only; authority records do not belong to runtime storage — not exported, but relocated to their correct boundary); D2 adopts R-CONFLICT; D3 keeps existing placement governance for the ADR itself." (anchor: "B′ — Governance Evidence Relocation ... The authority record is NOT exported from runtime — it belongs to the governance evidence boundary.")
- [S0130] types=[ANALYSIS] scope=CROSS-OBJECT — "The R-CONFLICT decision (D2) binds how the relocation (D1) may be implemented: the migration must preserve sequence integrity and provenance, must not delete or re-create history, and if a relocated record and a runtime record ever diverge, R-CONFLICT governs — neither side may be silently chosen." (anchor: "D2 constrains how D1 is implemented — this is the load-bearing consequence.")
- [S0130] types=[DISTINCTION] scope=CROSS-OBJECT — "The decision separates 'which boundary a record belongs to' (decided: the governance evidence boundary) from 'where that boundary physically sits' (explicitly reserved as a separate future act)." (anchor: "'Repository layout details' is explicitly reserved: B′ decides that the authority record belongs to the governance evidence boundary, not where that boundary physically sits.")
- [S0131] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Four target boundaries for the relocated design (Governance evidence, Execution, Ownership, Lifecycle), with the discriminating rule that evidence is append-only/immutable once written while execution state is disposable and rebuilt on demand — two lifecycles imply two storage locations, though no path is chosen." (anchor: "Governance evidence ... Execution ... Ownership ... Lifecycle -- the discriminator: evidence is written once and never revised; execution state is rebuilt on demand.")
- [S0131] types=[IMPLEMENTATION, PRINCIPLE] scope=OBJECT — "An ordered five-step migration sequence (copy, verify, switch, demote, remove) governed by the principle 'durability BEFORE demotion': step 5 (removal) is safe only after step 2 (verification) because at that point removal is not deletion of history since a durable copy already exists." (anchor: "COPY each record, byte-identically ... VERIFY hash equality per record ... SWITCH both path defaults atomically ... DEMOTE the runtime copy ... REMOVE the runtime copy — only after 1–4")
- [S0131] types=[WARNING] scope=OBJECT — "RA-2: session-resolve.php computes the same runtime path as workflow-state.php's default, independently, without consulting it — a second path default the durability decision's own follow-up list does not name, meaning reconciling only one default would be insufficient." (anchor: "session-resolve.php:74 defaults $recordDir to the SAME path — independently ... NOT in the decision's follow-up list.")
- [S0131] types=[WARNING] scope=OBJECT — "A new finding not present in the ADR or decision: the --dir CLI override is entirely unconstrained, so any invocation may write an authority record into an arbitrary directory with no target validation — a pre-existing mutation path that becomes more consequential once the durable location itself becomes worth diverting to." (anchor: "undocumented mutation paths ... --dir is an unconstrained override. Any invocation may write an authority record into an arbitrary directory, and nothing validates the target.")
- [S0131] types=[ALTERNATIVE, ARGUMENT] scope=OBJECT — "Four implementation options assessed (A: move existing records; B: new store, same mechanism; C: append-only ledger — eliminates the merge-conflict class but is a format change exceeding the decision's scope; D: relocate now with the current format, defer a ledger to a separate future decision); D is recommended as the smallest change that discharges the D1 decision without amending it via implementation design." (anchor: "D — RELOCATE NOW, LEDGER LATER ... D1 decided RELOCATION, not RE-FORMATTING.")
- [S0132] types=[ANALYSIS, WARNING] scope=OBJECT — "Observes that the migration's own inventory, hash-verification, and reconciliation records are themselves governance evidence and cannot be allowed to live only in the runtime location being retired, or the migration's proof of durability would itself be non-durable, defeating the purpose of B'." (anchor: "The migration itself creates governance evidence... Migration proves durability but its proof is not durable, which defeats B′.")
- [S0152] types=[PRINCIPLE, CONSTRAINT] scope=CROSS-OBJECT — "States the one load-bearing coupling rule between the two tracks: the assurance checker's root-resolution mechanism must follow wherever the governed evidence boundary is placed by B', never define or own that boundary itself, so ownership of the boundary stays exclusively with the durability decision." (anchor: "Assurance root resolution must FOLLOW the governed evidence boundary; it must not DEFINE or OWN that boundary.")
- [S0300] types=[DISTINCTION] scope=THEORY-LEVEL — "A DDD clarification separating two senses of 'durable' that had been conflated in earlier plan drafts; the migration explicitly does not add fsync/crash-durability." (anchor: "durability has two different properties: ATTESTABILITY (versioned, later re-verifiable) and CRASH DURABILITY (bytes survive power loss) -- the migration cures only the first.")
- [S0300] types=[FORMALIZATION] scope=OBJECT — "Corrects a disagreement between two previously accepted artifacts about the authority-transfer instant, ruling on mechanism (which component redirects writers) rather than preference." (anchor: "authority transfers at the Phase-5 writer switch (the moment P-1's resolution changes), not at Phase 6 (which only records a transfer that already happened).")

## Notes for P3
(Own observation.) This label is an engineering/governance decision record (D1/D2/D3, a real ADR-style artifact) rather than a mathematical/theory object, so the FOUNDATIONAL/DERIVED/OPERATIONAL/META-THEORETICAL layer vocabulary may not cleanly apply to it at all — flagging for P3 in case the layer taxonomy needs a distinct governance-artifact category rather than forcing LAYER-UNRESOLVED to mean the same thing here as for a mathematical construct. Internally the label shows real follow-on findings after the initial B0004 decision: S0131 surfaces two undocumented/unconstrained mutation paths (RA-2's independent path default, and the unvalidated --dir CLI override) that the original decision and its ADR did not name, and S0300 (later, different batch context) both draws a DDD distinction (attestability vs crash durability) and corrects a disagreement about the exact authority-transfer instant between two previously-accepted artifacts. These read as genuine implementation-discipline follow-ups to the original decision rather than contradictions of it, but P3 should note that the object's evidentiary picture is not static — it accumulated corrections after the initial decision was recorded.
