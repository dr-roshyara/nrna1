# KOS-SESSION-DISCOVERY-001 — Implementation Boundary Proposal

**Type:** Implementation-boundary presentation · **Date:** 2026-08-14 · **Role:** IMPLEMENTATION (S3) · **Grant:** `G-KOS-DISC-IMPL-BOUNDARY` (AUTHORIZED — *determine and PRESENT*; **not** implementation authority)
**⛔ NO PRODUCTION CODE, TEST CODE, CONFIGURATION, REGISTRY ENTRY, SCRIPT, HOOK, OR DOCUMENTATION WAS CHANGED.** This document is the deliverable.

**Startup check (answered from the record, not prose):** `S3-implementation-discovery` ACTIVE · `mutationOwner = S3-implementation-discovery` · `S4-architecture-discovery` COMPLETED · grants `G-KOS-DISC-ARCH` (AUTHORIZED, consumed) and `G-KOS-DISC-IMPL-BOUNDARY` (AUTHORIZED — boundary presentation only). **The grant's scope contains no implementation authority; none is inferred from being ACTIVE.**

**Authority consumed:** the approved architecture (`…-fresh-architecture-proposal.md`, §§E–S) and its human approval. Nothing is re-derived from general knowledge; where the architecture fixes a choice it is cited, not re-decided.

---

## 1 · Exact files that WOULD change *(none changed)*

| # | Artifact | Change | Class |
|---|---|---|---|
| F-1 | `.claude/scripts/session-resolve.php` | **new** — the read-only resolver; CMP-004's second implementation asset, **sibling of** `workflow-state.php`, never a patch to it | production (platform) |
| F-2 | `tests/Unit/Platform/WorkflowEngine/SessionAssignmentResolverContractTest.php` | **new** — hermetic contract tests, RED first | test |
| F-3 | `.claude/platform/registry.yaml` | **one** governed asset entry (`AST-016`, component CMP-004, `adoption: planned` before implementation → `adopted` after GREEN), registry-first per R-17/R-21 | registration |
| F-4 | `.claude/sessions/2026-08-14.md` · `.claude/CONTEXT.md` gate row | append-only bookkeeping (ES-004.3) | records |

**Count, stated unambiguously (governance-review clarification, 2026-08-14):** **4 boundary rows = 5 filesystem paths** — F-4 names two bookkeeping artifacts on one row. The capability itself is **3 paths** (F-1 script · F-2 test · F-3 registry entry); F-4's two are records, not capability (see §5a).

**Nothing else.** No second script, no shared library extraction, no test-support helper, no `composer.json` change, no new directory beyond the existing ones.

## 2 · Exact component introduced

**One executable, `session-resolve.php`** — no class, no namespace, no autoloaded PHP (it is platform concern, not product concern: ES-005.1, the same placement the qualified mechanism already occupies).

| Aspect | Boundary |
|---|---|
| Invocation | `php .claude/scripts/session-resolve.php [--dir=<records>] [--work-item=<id>] [--role=<role>] [--session=<id>] [--json]` — on-request only |
| Inputs | the runtime record directory (default `.claude/runtime/workflow`) + optional narrowing filters (architecture §F) |
| Output | **one ResolutionReport** — JSON with `--json`, human rendering otherwise; both carry the unconditional caveat line *"Resolution is not activation. This report creates no authority, no ownership, no state change. G-3 gates are untouched."* |
| Verdicts | exactly the four approved: `RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE` — no fifth value, no "best guess" |
| Exit codes | one documented mapping (RESOLVED=0; UNASSIGNED/AMBIGUOUS = distinct non-zero; UNRESOLVABLE = STOP-shaped non-zero) — pinned by test T-12 |
| **Write-freedom** | **structural, not conventional**: no code path reaches `append`, `grant`, `init`, or any file-writing call; the script opens nothing for writing |

## 3 · Relationship to the qualified mechanism — the binding precedence rule, mechanically preserved

**The resolver obtains record state EXCLUSIVELY by invoking the qualified `workflow-state.php` (`fold` / `identity`) as a subprocess and consuming its JSON.** It contains **no fold loop, no transition interpretation, no state-derivation branch, no JSON-schema knowledge beyond reading the qualified command's output fields**.

Consequences, each testable:
- **No second interpretation can exist** — if the qualified mechanism changes its verdict, the resolver's answer changes with it, because it never computes one.
- **Divergence is structurally impossible**, not merely discouraged: there is nothing to diverge.
- **`workflow-state.php` is untouched** (F-list contains it nowhere; the OQ-qualified asset stays byte-identical, protected by T-11).
- Where the qualified mechanism cannot express something (read-only participation, O-1/O-4), the resolver **names the gap** (`readOnlyParticipation: NOT EXPRESSIBLE …`) rather than inventing a state — the disqualifying move the architecture explicitly forbids.

## 4 · Exact tests that WOULD be added *(RED first; none written)*

Hermetic — synthetic records in temp dirs, **never `.claude/runtime/`**; no Laravel coupling; no database.

| # | Test | Pins |
|---|---|---|
| T-1…T-6 | one per architecture §H row: `ACTIVE→RESOLVED operable:true` · `CREATED→RESOLVED operable:false` with `missingForActivation` naming the **recorded** human START · `HANDED_OFF` naming the successor · `STOPPED` stating CONTINUATION-only exit · `COMPLETED/CANCELLED/FAILED` terminal (new need = new assignment, R8) · no-candidate → `UNASSIGNED` | the state→verdict→operability contract |
| T-7 | ≥2 candidates → **AMBIGUOUS with every candidate listed and NONE chosen** | P-3: silent selection is a contract violation |
| T-8 | absent record → `UNRESOLVABLE`; corrupt record → `UNRESOLVABLE` with the invalid-record fact in `reasons` | P-5/P-7: absence is never permission; no silent skip |
| T-9 | grant scope passed through **verbatim**, including a paraphrasable-looking scope | O-2: the resolver never summarises authority |
| T-10 | the six AuthorizationFacts surfaced, `UNKNOWN` where the record cannot say; `readOnlyParticipation` gap line present; caveat line present **unconditionally** (every verdict, both renderings) | §I/§K + the "creates no authority" guarantee |
| **T-11** | **read purity — the record directory is byte-identical before and after every invocation** (all four verdicts, including the corrupt-record path) | the suite's strongest assertion; the write-freedom claim |
| T-12 | exit-code contract per verdict | machine consumability |

## 5 · Exact documentation / registry changes

- **F-3 registry entry** (`AST-016`) with full five-question traceability, added **before** implementation.
- **No developer-guide file is proposed.** The resolver is a platform asset; the registry entry plus the script's own header is the documentation surface the architecture named (§S lists only script · tests · registry · bookkeeping). **If the approver wants a `developer_guide/` entry, that is an amendment to state now** — I will not add one silently, and I will not touch the `models`/`http` guide debt (PBDIGIT-65's).

### 5a · Classification of F-4 *(governance-review clarification)*

`.claude/sessions/2026-08-14.md` and the `.claude/CONTEXT.md` gate row are **implementation bookkeeping / evidence obligations under the standing ES-004.3 rule — they are NOT part of the resolver capability and NOT part of the architecture boundary.** They would be appended by whichever session performs the implementation, exactly as for any other slice. **The resolver implementation is deliberately not being expanded to address the separate session-log/CONTEXT continuity problem**; that problem is untouched here and stays with its own track.

If the approver prefers, F-4 may be struck from this boundary entirely without affecting the capability: the deliverable would then be **exactly 3 paths** (F-1, F-2, F-3), with bookkeeping continuing to be governed by the standing rule rather than by this grant.

## 6 · Explicit exclusions

`workflow-state.php` modification (any) · a second fold/interpretation implementation · any authorization engine or authorization *decision* · automatic session activation · SESSION_START / `inject-context.sh` wiring (**D-5**, separate) · hooks · locks · leases · ownership changes · grant creation or mutation · role journals · read-only-participation vocabulary (**D-6**) · transition timestamps/staleness (**D-1**) · grant↔session linkage data (**D-2**) · grant lifecycle/closure (**D-3**) · a native `resolve` subcommand (**D-4**) · Increment-2 enforcement · Election code, tests, or rules · registry schema change · new component or bounded context · CONTEXT.md restructuring.

**Every D-1…D-6 dependency remains unauthorized and undesigned here.**

## 7 · Expected RED → GREEN sequence *(described, NOT executed)*

1. Register `AST-016` in the registry (`planned`) — registry-first, before any implementation file exists.
2. Write T-1…T-12 (F-2). Run: **all RED**, failing because `session-resolve.php` does not exist — never by artificial assertion, never skipped.
3. Implement F-1 minimally: enumerate records → delegate to `workflow-state.php` → apply the §G/§H model → emit the report.
4. Run the contract suite to GREEN; **T-11 (read purity) must pass on every verdict path**.
5. Regression: the qualified suites (`WorkflowStateRecordContractTest`, `Pbdigit6569ReplayTest`) must remain green and `workflow-state.php` byte-identical.
6. Flip `AST-016` to `adopted` with verification evidence; bookkeeping (F-4).
7. Handoff to Session 1, whose independent falsification attempt is named by the architecture: *can any input make the resolver select among ambiguity, emit authorization, or write?*

**Session 3 will not self-certify.**

## 8 · Open questions for the approver

1. **Script name** — `session-resolve.php` proposed (verb-first, sibling naming). Approver may rename; it is the only naming choice in the boundary.
2. **Developer-guide entry** — proposed **none** (§5). Confirm, or amend the boundary to include one.
3. **Architecture Q-B** was answered *separate read-only script* (recommended, qualified mechanism untouched); this boundary implements exactly that reading. A native `resolve` subcommand remains **D-4**, unauthorized.

---

**IMPLEMENTATION BOUNDARY PRESENTED — IMPLEMENTATION NOT AUTHORIZED.**
Next acts: PO/ARB approval of this boundary → Governance registration → implementation grant → only then RED/GREEN by Session 3 → Session 1 independent verification.
