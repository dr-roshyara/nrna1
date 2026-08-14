# KOS-AI-ORCH-001 — Role-Scoped Session Records

> **Registration annotation (Governance, 2026-08-14 — content untouched):** the signed PO/ARB ruling on this proposal's core questions is REGISTERED — see `2026-08-14-KOS-AI-ORCH-001-role-records-ruling-registration.md` (ruling verbatim + numbering reconciliation) and **Amendment A-2** on KOS-AI-ORCH-001. **Ruled:** Option C adopted (one journal per role, provisional, assignment sections) · role ≠ session identity for file identity · no machine parsing of journals (workflow-state = sole machine-truth) · no `human-decisions.md` · no ORCH-002 (amendment route). **Still OPEN (unchanged):** §20 Q-2 reference index · Q-4 lifecycle · Q-5 daily-log relationship · Q-6 prose-authority generalization. **Implementation: NOT AUTHORIZED.**
# Architecture Proposal (Projection / Evidence Layer)

**Session 4 (ARCHITECTURE) · 2026-08-14 · design proposal for a FUTURE increment**

> ## PROPOSED — NOT HUMAN APPROVED
> Design only. Nothing here modifies Increment 1, creates a mechanism, registers a decision, or authorizes implementation. Adoption path: PO/ARB review → Governance registration → its own bounded increment.

**⛔ Produced read-only except this document: no Increment-1 change, no `workflow-state.php` change, no hook/lock/lease, no `.claude` mechanism, no registry change, no tests, no Election change.**

---

## 1 · Executive architectural decision (proposed)

> **Adopt role-scoped session journals as an EVIDENCE/PROJECTION layer: per work item, one Markdown journal per role, written by the role's current SessionAssignment, each entry referencing the workflow state record's transitions by `seq` and evidence by commit hash. Journals explain; they never decide. The Work Item/Workflow remains the one governed aggregate; `workflow-state.json` remains its sole machine-readable state representation; human decisions remain upstream authority that Governance registers — no Markdown file creates authority or carries machine truth.**

This is the design of the candidate mechanism that **amendment G-4 explicitly deferred at acceptance** ("per-stream sections or per-stream files … is design direction, not part of this rule"). It is deliberately a **projection layer, not enforcement**: journals need no locks, hooks, or gating — they are read/audit views of the governed workflow.

The PO's three wording rules (2026-08-14 review) are adopted as governing refinements of this proposal:

> **Rule 1 — Domain/storage distinction.** The Work Item/Workflow is the governed aggregate. `workflow-state.json` is its current machine-readable state representation. The storage mechanism is replaceable and must not become the domain concept.
> **Rule 2 — Single machine truth.** `workflow-state.json` is the sole machine-readable source of workflow state. Role journals MUST NOT be parsed or interpreted by automation to determine authorization, state, ownership, role, or transition eligibility. Role journals are human-readable evidence/projections only.
> **Rule 3 — Human authority.** Human decisions originate with the human/ARB. Governance registers the decision. The workflow state records the registered authority. No Markdown artifact itself creates authority.

## 2 · Problem being solved

| # | Problem | Evidence |
|---|---|---|
| P-1 | **One shared daily log, many concurrent streams.** `.claude/sessions/YYYY-MM-DD.md` is a single append-only file that four streams write; concurrent appends required hunk-selective staging to avoid misattribution | **F3** (rule §2), `7f24b185`; G-4 reclassified the fix direction as a candidate mechanism — this document is that design |
| P-2 | **Work items span days; logs span a day.** Reconstructing one work item's history means reading fragments across many date files; a date is temporal metadata, not identity | the 65/69 and KOS-AI-ORCH-001 tracks each span multiple date files; boundary alt-6 rejected date-keyed *state* for the same reason (F7) |
| P-3 | **Role evidence is interleaved.** A human auditing "what did Verification actually do?" reads governance, architecture, and implementation prose to find it | session logs 2026-08-13/14: four-session entries interleaved in one file |
| P-4 | **Continuity between AI sessions of one role** — a successor assignment needs its predecessor's narrative, not the whole day's | R8 reassignment model (new assignment, predecessor linkage) |

**What journals do NOT solve, by design:** state divergence (workflow-state's job — F5), authority ambiguity (Authority State's job — F9), collision prevention (Increment 2's job). Any design that lets journals address those recreates the two-truth failure.

## 3 · Domain model

```
DOMAIN            Work Item / Workflow  ──────  the governed AGGREGATE (identity, invariants)
                        │
PERSISTENCE       workflow-state.json   ──────  current machine-readable state representation
                        │                        (append-only transitions; state = fold; REPLACEABLE)
                        │
PROJECTIONS /     governance.md · architecture.md · implementation.md · verification.md
EVIDENCE                │                        role-authored, human-readable, reference-bearing
                        ▼
                  HUMAN READER / AUDIT

AUTHORITY         Human/ARB acts (performative records in committed artifacts)
EVIDENCE          ──── OUTSIDE the projection hierarchy: upstream INPUT that Governance
                       registers into Authority State — never a peer journal
```

The aggregate is the **Work Item/Workflow** — never the JSON file (Rule 1). The JSON is one storage realization of the aggregate's state; journals are views *of* the aggregate's history, owned by no machine decision path.

## 4 · Aggregate boundary

| Concept | Inside the governed aggregate? | Reason |
|---|---|---|
| Work Item identity, declared role set | **YES** — root | Inv A/J |
| SessionAssignment (role, state, predecessor) | **YES** — entity | Inv B; R8 |
| Transitions, mutation ownership | **YES** — events/attribute | Inv C/D/E/F |
| Grants (Authority State) | **separate governed record** (never merged — Inv G) | G-2 |
| **Role journals** | **NO — outside the consistency boundary** | evidence about the aggregate; their loss or staleness must never invalidate the aggregate's state |
| **Human decision records** | **NO — upstream authority evidence** | Rule 3: the act precedes registration; the artifact evidences it |
| Daily session log | NO — existing cross-cutting convention, untouched | see §9 |

No new aggregate is introduced. Journals are not entities of the workflow aggregate; they are **artifacts about it** — the DDD term that fits is *projection/read-model of the event history*, human-consumed.

## 5 · Workflow state vs evidence

| | `workflow-state.json` | Role journals | Human decision records |
|---|---|---|---|
| Nature | machine workflow state | human-readable evidence/projection | evidence of actual human acts |
| Authority | **machine truth** | **none** | **source of authorization** (via Governance registration) |
| Writer | per Increment-1 write protocol (G-2 for grants; recorded roles for transitions) | the role's current assignment, own file only | the human (performative text); Governance registers a *reference* |
| Reader | machines and humans | humans only (Rule 2) | humans; Governance (to register) |
| Direction | — | journals **reference** state (`seq`, commits); state **never** references journals as authority | state's `humanActRef` points at the committed record |
| On loss | re-fold from log / escalate (Inc-1 failure semantics) | history inconvenience — **state unaffected** | authority evidence lives in git-committed artifacts — durable |

## 6 · Role journal model (recommended shape)

One directory per work item; one journal per role; entries sectioned by **SessionAssignment**:

```
.claude/sessions/work-items/<work-item-id>/
    governance.md
    architecture.md
    implementation.md
    verification.md
    decisions.md            ← INDEX of human-act references, IF adopted (§7 — open question Q-2)
```

**Entry discipline (the content contract):** each entry is appended under a heading carrying `{assignment-id · date · state-record seq range it narrates}` and MUST cite, not restate: transitions by `seq`, evidence by commit hash, grants by `grantId`, decisions by their committed record. Narrative explains *why*; the record already says *what*. Restating state values verbatim is discouraged (drift surface); quoting them with their `seq` citation is fine (ES-005.4: reference, never a copy).

**Format:** Markdown, append-only per assignment section; a section is closed by its assignment's HANDOFF/STOP and never edited afterwards (matching the platform's append-only doctrine, CAP-02).

**Role file ≠ session identity (Principal Architect challenge, 2026-08-14 — adopted).** The per-role file is an *organizational* grouping; **identity lives only in the assignment sections**. A journal MUST NOT carry a session-identity header, because role ≠ session identity (R8):

```markdown
✅ architecture.md          ❌ architecture.md
   ## S4-A1 · 2026-08-14       # Session 4
   …                           …
   ## S7-A9 · 2026-09-02
   …
```

The correct form makes visible that *different* assignments — potentially different processes, different days — wrote the same role's journal; the wrong form silently fuses role, session, and process into one identity, which is the exact conflation R8 and the terminal-agnosticism principle exist to prevent.

## 7 · Human decision model

**Recommendation: do NOT create a `human-decisions.md` that *holds* decisions.** Human acts already have durable homes — performative rulings recorded verbatim in committed artifacts (commission §15, registration `f5981933`) and registered grants carrying `humanActRef`. A second prose home would be a copy (ES-005.4) and, worse, an **authority-laundering surface**: an editable file that *looks* like the source of authorization.

**What MAY exist (open question Q-2):** `decisions.md` as a Governance-maintained **index of references** — one line per human act: date · act · pointer to the committed record · the grant/transition that registered it. Index, never record; pointers, never text. If the PO prefers zero additional files, the Authority State's `humanActRef` fields already serve this purpose queryably, and the index is omitted.

**Hard properties either way (Rule 3):** not machine-parsed for authorization · not an AI-editable state file · not a second workflow state · Governance may append references, never author decisions ("Governance can rewrite as if it were the human" is exactly the G-2 violation the record structure forbids).

## 8 · Ownership model

| Artifact | **Write ownership** | **Authority ownership** |
|---|---|---|
| `governance.md` | current GOVERNANCE assignment | **none** — writing evidence grants nothing |
| `architecture.md` | current ARCHITECTURE assignment | none |
| `implementation.md` | current IMPLEMENTATION assignment | none |
| `verification.md` | current VERIFICATION assignment | none |
| `decisions.md` (if adopted) | GOVERNANCE (references only) | none — the referenced human acts hold it |
| `workflow-state.json` | Increment-1 write protocol (unchanged) | none — it *registers* authority (G-2), never sources it |

**Ghost-writing rule (proposed):** a session writes only its own role's journal for its own assignment. Writing another role's journal is a role-boundary violation (INV-ORCH-ROLE-2) — adjudicable from the record (the writer's assignment/role are queryable), enforced by nothing (this layer needs no enforcement).

**Concurrency note:** per-role files give single-writer-per-file *by construction* — the lifecycle admits at most one ACTIVE assignment per role per work item — which is what dissolves F3 without any lock. Journal writes remain shared-tree mutations and thus sit under the existing execution-context ownership discipline for commits; the per-role split removes the same-file contention that made F3 painful.

## 9 · Namespace / work-item structure

**Validated: work item is the identity; date is temporal metadata.**

| Candidate | Verdict |
|---|---|
| `.claude/sessions/<work-item-id>/…` (as sketched in the commission) | workable, but work-item dirs would interleave with `YYYY-MM-DD.md` files in one listing — two naming schemes in one directory is the `Election`/`Elections` lesson in miniature |
| **`.claude/sessions/work-items/<work-item-id>/…`** | **RECOMMENDED** — one explicit sub-namespace; date files and work-item dirs cannot be confused; placement root derived, not chosen: `php scripts/doc-placement.php --scope=session-state` → `.claude` (exit 0) |
| `.claude/sessions/2026-08-14/…` (date-first) | **REJECTED** — re-creates P-2/F7: a work item spanning days would fragment across date dirs, and "which day is current?" becomes a stale-directive question. Same reasoning that rejected date-keyed state (boundary alt-6) |

**Tracked in git — deliberately unlike `workflow-state.json`.** The state file is gitignored *machine* state (immune to index collisions, replaceable). Journals are *human evidence*: they must survive machines, travel with the repository, and be reviewable in history. The F3-class risk of tracked shared files is answered by single-writer-per-file (§8), not by a lock.

**The daily log `.claude/sessions/YYYY-MM-DD.md` is NOT retired.** It is a standing CLAUDE.md convention and the cross-work-item day view; retiring or thinning it is a **separate governance decision** (G-4's own boundary). Until then: journals are the per-work-item view, the daily log the per-day view — **two views, one truth**, with the daily log free to reference journal entries rather than duplicate them.

## 10 · Cross-day continuity

A work item's journals accumulate entries across days; each entry is datestamped inside its assignment section. Day boundaries create **no new artifact, no new truth**:

```
.claude/sessions/work-items/KOS-AI-ORCH-001/
    architecture.md      ← Day-1 S4 entries (boundary), Day-3 S4 entries (addendum) — one file, one history
    governance.md        ← Day-2 S2 registration entries
    implementation.md    ← Day-3 S3 RED→GREEN entries
    verification.md      ← Day-4 S1 entries
```

Continuity for a successor assignment = read the predecessor's closed section + consult the state record. The identity that binds them is the work item and the assignment chain (`predecessor` links in the state record) — never the calendar, never the terminal.

## 11 · R8 compatibility

R8: role immutable per SessionAssignment; change = new assignment via HANDOFF → START. The journal model preserves this structurally:

- Journal sections are keyed by **assignment id**, not by "the session": `S4-A1`'s architecture section is closed at its handoff; if the same process later holds `S1-A3` (verification), its entries go to `verification.md` under `S1-A3` — **no section ever changes role, no file is shared across roles**.
- Nothing in the model requires (or permits) editing a closed section — an informal "now I'll also verify" has no journal path, mirroring its absence of a record path.
- R-34 remains visible: the assignment chain in the state record shows whether the verifying assignment's process implemented — journals make the narrative readable; the record makes the violation queryable.

## 12 · Machine-truth invariant (candidate rule text — for Governance, not enacted here)

> **INV-ORCH-EVID-1 (proposed).** The workflow state record is the sole machine-readable source of workflow state. Automation MUST NOT parse role journals, daily session logs, or any Markdown evidence to determine authorization, workflow state, mutation ownership, role, predecessor, or transition eligibility. Journals reference the state record; the state record never cites a journal as authority. A contradiction between a journal and the state record is **evidence staleness, never state ambiguity**: the record is correct, the journal gets a dated correction entry, and no reconciliation may run through Markdown.

That last clause is the F5-shaped scenario the PO named (`state says HANDED_OFF, journal says ACTIVE, a future AI "reconciles" by reading Markdown`) — the invariant makes the reconciliation *direction* fixed in advance, so the situation is boring rather than dangerous.

## 13 · Alternatives considered

| Option | Design | Verdict |
|---|---|---|
| A | one journal per work item (all roles interleaved) | **rejected** — recreates F3 (multi-writer single file) and P-3 (interleaved audit); this is the current daily log's problem, relocated |
| B | one file per role *(pure)* | close — but without assignment sectioning it blurs R8 (whose entry is whose?) |
| **C** | **role-authored evidence, per-role files, assignment-sectioned, referencing state `seq`/commits** | **RECOMMENDED** — single-writer per file, R8-aligned, reference discipline built in, zero machinery |
| D | structured event record + machine-**generated** projections | architecturally elegant, **premature**: requires a projection generator (a mechanism — its own increment, its own authorization) and risks the generated files being *trusted as* state precisely because they're always fresh. Recorded as the natural **later** evolution if manual journals prove burdensome (reversal condition: repeated evidence of journal drift or authoring cost) |
| E | one file per SessionAssignment | maximal isolation, but file proliferation (`S4-A1.md`, `S4-A2.md`, …) makes the human audit *harder* — the role's continuous story fragments; assignment sectioning inside role files gives the same isolation without the sprawl |

## 14 · Failure modes

| # | Failure | Answer in this design |
|---|---|---|
| FM-1 | **Journal drift** — narrative contradicts state | INV-ORCH-EVID-1: fixed reconciliation direction; dated correction entries; reference-not-restate discipline |
| FM-2 | **Journal-as-oracle** — future automation parses Markdown | the invariant is rule text + review surface; structural immunity would be enforcement (future increment, not needed to adopt the convention) |
| FM-3 | **Authority laundering** via a decisions file | §7: no decision *text* outside committed performative records; index-of-references at most |
| FM-4 | **Ghost-writing** another role's journal | §8 ownership + queryable assignment/role in the record; adjudicable |
| FM-5 | **F3 regression** (same-file contention) | per-role single-writer by construction |
| FM-6 | **Work-item directory sprawl** | lifecycle/archival policy = open question Q-4 (archive-on-closure candidate) |
| FM-7 | **Dual bookkeeping burden** (journal + daily log) | daily log may point to journal entries (never duplicate); if burden proves real, that evidence feeds the G-4/daily-log decision — not silently resolved here |

## 15 · Security / authority implications

None added, several closed. Journals carry no authority (Rule 2/3), so compromising one changes no machine decision; the authorization chain (human act → Governance registration → Authority State → query) is untouched; `humanActRef` continues to point at *committed* artifacts, so authority evidence is git-history-durable and tamper-evident in exactly the way an editable Markdown "authority file" would not be. The one new surface — a plausible-looking journal misleading a *human* — is the existing prose risk narrowed: journals cite `seq`/commits, so a human can (and per D-4's startup check, must) verify against the record before acting.

## 16 · DDD assessment

- **Aggregate discipline:** one aggregate (Work Item/Workflow); no new aggregate minted for evidence — projections don't get consistency boundaries (they are allowed to be stale; the aggregate is not).
- **Rule 1 honored:** the domain concept (workflow) is storage-agnostic; §6's shape binds journals to *concepts* (assignment, transition seq, grant id), not to today's JSON file — if Increment N moves the state record, journals' references survive as identifiers.
- **Ubiquitous language:** journal vocabulary = the record's vocabulary (assignment, handoff, grant, seq) — no parallel terms.
- **ES-005.4 never-a-copy** is the entry discipline's backbone: cite, don't restate.
- **ES-001.1 parsimony:** one new invariant proposed (EVID-1); everything else reuses R8, G-2/G-3, INV-ORCH-ROLE-1..5, the existing placement resolver, and the existing append-only doctrine.

## 17 · Recommended architecture (summary)

Option C journals under `.claude/sessions/work-items/<work-item-id>/`, one per role, assignment-sectioned, git-tracked, reference-bearing, append-only; decisions file as Governance-maintained reference index **or omitted** (Q-2); daily log untouched; INV-ORCH-EVID-1 as the governing invariant; zero mechanism, zero enforcement, zero Increment-1 change.

## 18 · Explicit non-goals

No enforcement of any kind (no hooks, locks, leases, gating) · no projection generator (Option D deferred with a reversal condition) · no `workflow-state.php` / `workflow_engine` / `session_manager` / registry change · no retirement or restructuring of the daily session log · no CONTEXT.md restructuring · no change to Increment 1, R1–R8, or the verification now in flight · no Election work · no new authority model · no second machine-readable state anywhere.

## 19 · Future implementation boundary (if adopted)

Smallest conceivable increment: **a convention, not a build** — (1) Governance registers the adopted rule text (EVID-1 + ownership + entry discipline); (2) the work-item directory is created on first governed use; (3) sessions begin writing their role journals per §6. Files: the journal directory only. **No code, no script, no hook, no registry entry is required to adopt this** — which is exactly why it is classified *Future Increment / Projection Layer — not enforcement*, and why it must still wait for its own PO approval + Governance registration rather than riding along with Increment 1's closure.

## 20 · Open questions requiring PO/ARB decision

| # | Question |
|---|---|
| Q-1 | Adopt Option C as recommended, amend, or reject? |
| Q-2 | `decisions.md`: reference-index (Governance-maintained) or omitted in favour of Authority State `humanActRef` alone? |
| Q-3 | Does adoption amend KOS-AI-ORCH-001 again (the G-4 candidate maturing — the parsimony-consistent route), or stand as a separate registered convention? |
| Q-4 | Work-item journal lifecycle: archived on governance closure (G-1)? retention? |
| Q-5 | Daily-log relationship long-term: keep both views permanently, or revisit the daily log once journals have operational evidence? (Separate decision, per G-4 — flagged only.) |
| Q-6 | Should INV-ORCH-EVID-1 extend to *all* Markdown (CONTEXT.md included) as the general "no machine parses prose for authority" rule? (It is the F5 lesson generalized — but that touches `inject-context.sh`'s plan-line convention, so it needs its own analysis.) |

### 20.1 · Review feedback received (Principal Architect, 2026-08-14 — preliminary positions, NOT the PO ruling)

Recorded so the eventual ruling reviews an accurate state; **nothing below changes this document's PROPOSED status**:

| Item | PA preliminary position |
|---|---|
| Q-1 role journals / Option C | **accept in principle** (one per role, provisionally; assignment sections; work-item namespace) |
| Machine parses journals | **absolutely no** (EVID-1 endorsed, incl. the staleness-not-ambiguity clause) |
| `human-decisions.md` as authority | **no** (rejection endorsed) |
| Q-2 reference index · Q-4 lifecycle · Q-5 daily log · Option D generated journals | **defer** — open until discussed |
| New KOS-AI-ORCH-002 | **no** — avoid a new rule merely because a new design document exists (aligns with Q-3's parsimony route) |
| Implementation now | **no** — Session 3 remains in the Increment-1 verification chain; this stays a future increment behind its own gate |
| Role file ≠ session identity | challenge raised and **adopted into §6** (this revision) |

---

**Traceability:** G-4 (candidate-mechanism deferral — the mandate for this design) · accepted rule §2 (F3/F5/F7 evidence) + §8 (the G-4 sentence) · PO wording rules 1–3 (2026-08-14 review, adopted §1) · Amendment A-1 / D-1..D-5 (`f5981933`) · approved boundary + R1–R8 (`9b69ab76`, approved WITH R8) · addendum (`641d4112`) · Increment-1 implementation (`c2f5a831`) · verification (`aac62274`) · live record `.claude/runtime/workflow/KOS-AI-ORCH-001-INC1.json` (schema 1, fold) · `scripts/doc-placement.php --scope=session-state` → `.claude` · CLAUDE.md session-log convention (untouched) · ES-001.1 · ES-004.3 · ES-005.4 · R-34 · CAP-02 (append-only doctrine) · boundary alt-6 (date-keyed rejection precedent).

---

> # PROPOSED — NOT HUMAN APPROVED
