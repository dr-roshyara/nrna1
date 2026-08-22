# 04 — Next-Actor Orchestration: turning a human decision into governed mechanics

## Purpose

A person running this platform understands *"appoint a fresh independent reviewer."* They should not have
to understand `REGISTER`, `HANDOFF`, `START`, predecessors, mutation owners, UUIDs, or transition JSON —
but until now they did. This guide covers **`AST-018`**, the asset that closes that gap, and the one
integration property that makes it worth anything.

The division of labour it completes:

| Asset | Answers |
|---|---|
| `AST-015` `workflow-state.php` | *What is recorded, and is this write legal?* — the single workflow authority |
| `AST-017` `session-bootstrap.php` | *What is the current governed situation?* — read-only |
| **`AST-018` `next-actor-orchestration.php`** | ***Given the human decision, what governed action should now be executed?*** |

## Where it fits

- **Layer:** Runtime Platform (`.claude/scripts/`), component `CMP-004` (workflow_engine), its **fourth** implementation asset.
- **Authority:** none of its own. It reads through `AST-015` and writes through `AST-015`. It is not the authority model, not a second workflow engine, and not a session creator.
- **Adoption:** registry entry is `verify`. **Implemented is not adopted** — independent verification and the governance path come first.

## Key files

| File | Role |
|---|---|
| `.claude/scripts/next-actor-orchestration.php` | the asset |
| `tests/Unit/Platform/WorkflowEngine/NextActorOrchestrationContractTest.php` | the contract (N-1…N-16, R-1…R-3, P-1…P-9) |
| `docs/knowledgeos/reviews/2026-08-22-KOS-NEXT-ACTOR-ORCHESTRATION-001-implementation-prompt.md` | the commission, verbatim |
| `…-AMENDMENT-001-PrepareNextActorSession.md` | Use Case 3 |

## How it works

```
php .claude/scripts/next-actor-orchestration.php <command> <workItem> [options]

next-actor            UC1  DetermineNextActorAction      read-only
appoint               UC2  AppointReviewer               THE ONLY WRITING COMMAND
prepare-prompt             PrepareReviewerPrompt         read-only, advisory
prepare-next-session  UC3  PrepareNextActorSession       read-only, advisory
stop                       Option 3                      no state change

exit 0 = report produced · 64 = usage · 65 = REFUSED, nothing written
```

### The three rules it exists to protect

**1. Human authority is an input, never an inference.** `--human-act` carries the person's words verbatim
into the `START` transition. Omit it and the appointment is refused:

```
$ … appoint DEMO-001 --candidate=S-gov --role=governance
NO TRANSITION

WHAT IS MISSING
    the human authority act that appoints the actor
WHY IT MATTERS
    Appointing an actor is an authority decision. Nothing in this tool may stand in for a
    person saying so, and an assistant's own message can never be recorded as a human act.
```

No "Yes" is assumed because a prompt was requested; nothing auto-starts because one candidate exists.

**2. Identity is never manufactured.** Candidates are explicit `--candidate` inputs. Zero →
`NO_ELIGIBLE_CANDIDATE`; more than one → `AMBIGUOUS`; never a silent choice. `UC3` reports
`candidateIdentity: null` on purpose: a fresh actor has no identity until its own process declares one.
(Empirical probe, 2026-08-22: a spawned subagent inherits the parent's `CLAUDE_CODE_SESSION_ID` — so a
subagent *is* its parent and fails every independence bar.)

**3. `AST-015` stays the only interpreter.** Every state fact comes from a `fold` subprocess; every write
goes through `append`. The file performs no fold, no raw-record read, no raw-record write, and knows no
store path — `--dir` is forwarded or omitted so `AST-015` applies its own default. `N-13` proves it by
poisoning the raw record with decoy `mutationOwner`/`sessions` fields and asserting none surface; `N-14`
proves it structurally by asserting the source contains no read/write call at all.

### Independence exclusions come from attribution, not from text

Exclusions are derived from the fold's **registered session keys**. They are deliberately *not* derived by
scanning free-text `executionContext`, because a lane that merely *mentions* a prior reviewer in prose is
recording history, not an assignment — and treating a mention as an assignment manufactures false
ambiguity that gets worse as the record grows. `P-4` pins the derivation.

### The next-actor progression is a declared policy

```
architecture | implementation  →  verification  →  governance  →  human decision
```

Anything outside this table yields `AMBIGUOUS` with the human deciding. The policy is a named constant
(`PROGRESSION`) precisely so it can be argued with, rather than being an inference buried in a branch.

### Pre-flight is total

The transition log is append-only and cannot be rolled back, so every precondition is checked **before the
first `append`** (`N-11`: an undeclared role writes nothing at all). If a later transition still fails, the
result is `INCOMPLETE_SEQUENCE` naming exactly what was written — an honest report, never a silent partial.

## The pitfall that matters most

**An appointment must leave the appointed actor *operable*.** `AST-017` attributes a lane to a process by
extracting `claude-code-session:<id>` from the lane's `executionContext`. Its extractor accepts `.` inside
a label, so writing the label followed by a period captures the period **as part of the id**:

```
executionContext: "… claude-code-session:7f3ac1e2-…-6c31. INDEPENDENCE: …"
                                         └─ extracted as "7f3ac1e2-…-6c31."  ≠  "7f3ac1e2-…-6c31"
→ AST-017: verdict UNRESOLVED · operable false
```

The appointment "succeeded", the lane folded `ACTIVE` — and the actor could not act. That is precisely the
dead end this work item exists to remove, reintroduced by punctuation. `AST-018` therefore always writes
the label followed by whitespace, and **`N-16` pins the whole loop**: appoint → then run `AST-017` as that
process → assert `RESOLVED` and `authorized_to_act: true`.

If you extend the `executionContext` text, keep the label away from punctuation.

## How to use it

```bash
# 1 · what does the record say should happen next?
php .claude/scripts/next-actor-orchestration.php next-actor KOS-EXAMPLE-001

# 2a · the person decides "yes" — their words go in verbatim
php .claude/scripts/next-actor-orchestration.php appoint KOS-EXAMPLE-001 \
    --candidate=<uuid the new session declared> --role=verification \
    --scope="independent verification of KOS-EXAMPLE-001" \
    --human-act="PO/ARB 2026-08-22: I appoint <uuid> as the independent verifier."

# 2b · or: the person wants the new session's kickoff prompt instead (writes nothing)
php .claude/scripts/next-actor-orchestration.php prepare-next-session KOS-EXAMPLE-001

# add --json for machine output, --show-mechanics to see the transitions
```

`prepare-next-session` is the answer to *"a fresh session is required"* — instead of telling the person to
start one and leaving them to reconstruct what to type, it prints the exact prompt to paste, built from the
work item, role, scope, reason, exclusions, current state, what to do first, what not to do, and where to stop.

It refuses to do so when a fresh actor is *not* the next step — a lane still holds the work, is assigned and
awaiting activation, or the item is stopped. Then it reports `NO_FRESH_ACTOR_REQUIRED` and names who must act
instead (`P-8`/`P-9`). A kickoff prompt for a role that is already actively held reads as an instruction to
start a redundant process; that is the one failure mode of this command worth guarding, and it was found by
running the command against its own work item.

## Testing

```bash
vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/NextActorOrchestrationContractTest.php
vendor/bin/phpunit tests/Unit/Platform/WorkflowEngine/          # the whole engine
```

Tests are hermetic: synthetic records are built in temp dirs **through `AST-015`**, never by hand-writing
JSON, and `.claude/runtime/workflow/` is never touched. One fixture subtlety: `predecessor` records lineage,
but `HANDOFF.from` must be the *current* mutation owner — which is `null` once the prior lane completed
(`Inv C` refuses anything else).

## Pitfalls

- **Don't add a default store path.** The asset must not know where the record lives; `N-14` fails if `runtime/workflow` appears in the source.
- **Don't paraphrase the human act.** `N-10` asserts it is recorded verbatim.
- **Don't widen exclusions by text search.** See above; `P-4`.
- **`implemented` ≠ `adopted` ≠ `authorized for future use`.** The registry entry says `verify` for a reason.
- **`--show-mechanics` is opt-in.** Default output must not leak `REGISTER`/`HANDOFF`/`mutationOwner` (`N-15`).

## Traceability

Work item `KOS-NEXT-ACTOR-ORCHESTRATION-001` · lane `5c0e13c1-…` [implementation] seq 1–3 (`REGISTER` →
`HANDOFF` → human `START`, PO/ARB act 2026-08-22) · commission `…-implementation-prompt.md` (verbatim) ·
`…-AMENDMENT-001-PrepareNextActorSession.md` (UC3) · asset `AST-018`, registry `adoption: verify` ·
`AST-015` (single workflow authority) · `AST-017` (unmodified, read-only) · `G-3` (human START) ·
`INV-ATTR-1/2` (identity evidence-only) · `Inv B`/`Inv C`/`Inv E` · `R8` · `ES-005.4` (extend the family,
never a second engine) · `R-34`/`EP-02` (never self-accept) · EKS-07 **not** reopened.
