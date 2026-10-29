# Role environment, KnowledgeOS-only pilot: proposal for review (NOT LIVE)

| Field | Value |
|---|---|
| Status | **PROPOSED — NOT LIVE.** Prepared by the Governance Engineer, 2026-10-29, under the Principal's approval in principle (decision record, addendum "a KnowledgeOS-only pilot of the role environment") |
| Why staged here | Claude Code loads `.claude/agents/`, `.claude/skills/` and `.claude/settings.json` as soon as the files exist (verified in the Claude Code docs). The files are therefore kept here and **copied into `.claude/` only on the Principal's explicit approval** |
| Preserved | every mandate; the adopted handoff protocol (`docs/knowledgeos/governance/SESSION-COMPLETION-HANDOFF-PROTOCOL.md`); the existing `kos-theory-reviewer` agent; all existing settings, hooks and rules. **No EKS code change; no new handoff record** |
| Facts about Claude Code | from the official docs (permissions, sub-agents, skills, hooks, settings pages), fetched 2026-10-29; items the docs leave open are marked **to verify** and are covered by the tests in §3 |

## 1. The proposed changes

| # | File (proposal → target) | What it is | Restriction it enforces |
|---|---|---|---|
| 1 | `settings-deny-proposal.json` → **merged into** `permissions.deny` of `.claude/settings.json` (three lines added; nothing else changes) | deny rules for the hold-out files | **No Claude session in this repository can read, edit or write the hold-out files** through Read, Edit, Write (Read deny covers both), best-effort Grep and Glob, or a Bash command naming them |
| 2 | `agents/kos-verification-auditor.md` → `.claude/agents/` | the auditor as a subagent | **No Edit, NotebookEdit or web tools.** It can create report files (Write), but not edit existing files. Mandate pointer; checks the decision record for the part authorised before acting |
| 3 | `agents/kos-lane-reviewer.md` → `.claude/agents/` | the lane's independent reviewer as a subagent | **No Write, Edit or web tools: read-only by tool.** Commission + reviewer instructions binding. The Professor commissions reviews with this type instead of a general-purpose agent |
| 4 | `skills/role-governance-engineer/SKILL.md` → `.claude/skills/` | `/role-governance-engineer` | start a Governance Engineer session with one command: verify the mandate revision in force, read §11.1, give the five-item report, wait. **User-invoked only** (`disable-model-invocation: true`) |
| 5 | `skills/role-lead-ddd-architect/SKILL.md` → `.claude/skills/` | `/role-lead-ddd-architect <task>` | start the Architect: verify the mandate revision and **which part is authorised**; stop if the task needs an unauthorised part. User-invoked only |
| 6 | `skills/role-implementation-engineer/SKILL.md` → `.claude/skills/` | `/role-implementation-engineer` | **activation check first:** if no stage-1 decision is recorded, it stops with one sentence and does nothing else. User-invoked only |

**Not changed:** the Professor's setup (Rulebook v1.4 and its own working practice), the running sessions (they continue as they are; the new definitions apply to new sessions and new commissions), `kos-theory-reviewer` (built for a different, earlier review with fixed tasks and output; reusing it would change its other uses).

## 2. What is enforced, and what is not

| Restriction | Enforced by | Strength |
|---|---|---|
| Hold-out files never read, edited or written | deny rules (#1) | **enforced** for Read/Edit/Write and for Bash commands that name the files; **best-effort** for Grep/Glob |
| Reviewers cannot change files | tool list (#3) | **enforced** for Write/Edit; **not** for Bash (see limits) |
| Auditor cannot edit code or documents | tool list (#2) | **enforced** for Edit; Write can still create or overwrite a file anywhere (see limits) |
| A role starts from its approved mandate and checks its authorisation | skills (#4–#6) | **procedural**: the skill instructs the check; a session started without the skill is not covered |
| The Implementation Engineer does nothing before stage 1 | skill (#6) | **procedural** (instructed stop) |
| Decisions only by the Principal; findings routed through the Principal | the mandates and the decision record | **governance**, not technical |

### Limitations that cannot be enforced technically in this pilot

1. **Per-role path limits are not enforceable by settings or agent definitions.** Settings rules apply to every session in the repository; agent definitions restrict **tool names**, not paths. "The auditor writes only in `review-implementation/`" remains an instruction. *(Possible later step, needing a separate grant for `.claude/scripts/`: a PreToolUse hook that reads the subagent's `agent_type`, or a launch variable such as `KOS_ROLE=auditor`, and blocks paths outside the role's area. Hooks must exit 2 on every failure path, since other failures let the call through.)*
2. **Bash is a gap.** Deny rules catch commands Claude Code recognises (cat, head, sed, redirections) but **not** programs that open files themselves (`python`, `node`) or recursive searches run from a directory. A reviewer or auditor with Bash can therefore still read or write in ways the rules do not see. Full enforcement needs OS-level sandboxing; out of scope for this pilot.
3. **Only the hold-out files are denied, not the other non-allowed corpus files.** Listing them would need the full file registry, which the Governance Engineer's mandate forbids reading as a whole. Their protection stays instruction-based (the allowed list), or would need the hook of limitation 1.
4. **Deny-rule inheritance into custom subagents is not stated by the docs** (stated only for built-in subagents). **To verify** by test T2.
5. **Background subagents may resolve to a different tool set** than foreground ones. **To verify** by test T4.
6. **Unknown frontmatter keys are silently ignored**, so a typo would silently drop a restriction. Each restriction is therefore tested, not assumed.
7. **The Bash deny pattern `Bash(*P3B-HOLDOUT*)` also blocks any Bash command whose text contains that name**, including a record entry written by heredoc. Records refer to "the hold-out files" instead. Whether a leading wildcard matches as intended is **to verify** (T1).
8. **Approval gates stay human.** No configuration can tell whether the Principal has approved something; the skills only make each role check the decision record.

## 3. Tests (run after copying into place, before the pilot is declared working)

**No test touches the real hold-out files.** Deny behaviour is tested on a dummy file with a temporary dummy rule, removed afterwards.

| # | Test | Expected result |
|---|---|---|
| T1 | Temporary rule `Read(/docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/role-env-test/HOLDOUT-DUMMY*)` and `Bash(*HOLDOUT-DUMMY*)`; dummy file created. In a new session: Read it; Grep for a word in it; `cat` it; `python3 -c "print(open(...).read())"` | Read, Grep, cat **blocked**; python **not blocked** (limitation 2 confirmed and recorded) |
| T2 | Same, from inside a `kos-lane-reviewer` subagent | Read, Grep, cat **blocked** (inheritance into custom subagents confirmed) — or, if not, recorded as a limitation and the pilot's risk statement updated |
| T3 | `kos-lane-reviewer` asked to create or edit a dummy file | Write/Edit **unavailable**; recorded whether it tries Bash instead (limitation 2) |
| T4 | `kos-verification-auditor` in foreground and in background: list available tools | no Edit in either; any difference recorded |
| T5 | `/role-implementation-engineer` today | it **stops** with the one-sentence "not active" reply and reads nothing else |
| T6 | `/role-lead-ddd-architect` with a part-2 task ("start the tactical design") | it **stops**: part 2 not authorised |
| T7 | `/role-governance-engineer` in a new session | the five-item report, matching `GOVERNANCE-ENGINEER-STATE.md`; then waits |
| T8 | Ask Claude, without the slash command, to "act as the implementation engineer" | the skill is **not** invoked automatically (`disable-model-invocation`) |
| T9 | After the temporary rules are removed: the real deny rules are present in `.claude/settings.json`, and every other line of the file is unchanged | `git diff` shows only the three added deny lines |

Each test: command, expected result written **before** running, actual result, PASS / FAIL / INCONCLUSIVE. Results go to `role-environment-proposal/TEST-RESULTS.md`, with the session IDs used.

## 4. Activation (only on the Principal's explicit approval)

1. The Principal approves this proposal (all of it, or named items).
2. The Governance Engineer copies the approved files into `.claude/` and merges the deny lines; commits with the decision reference.
3. Run T1–T9; record the results; report PASS / FAIL / INCONCLUSIVE per test. **A FAIL on T1, T2 or T5 rolls the affected item back** until the Principal decides.
4. The Professor is told to commission reviews with `subagent_type: kos-lane-reviewer` (one line in its next prompt).
5. Repository-wide adoption stays a separate decision (the methodology freeze in `CLAUDE.md`).
