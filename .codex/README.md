# Codex project harness

`../AGENTS.md` is the Codex operating contract for this repository. Its
**Session bootstrap and responsibility resolution** section points at the
session-bootstrap resolver (`.claude/scripts/session-bootstrap.php`, ON_DEMAND;
canonical rule text:
`docs/knowledgeos/reviews/2026-08-22-KOS-SESSION-BOOTSTRAP-001-implementation-boundary-proposal.md`)
and its **Session completion** section carries the adopted Session Completion
Report obligation and template (canonical protocol:
`docs/knowledgeos/governance/SESSION-COMPLETION-HANDOFF-PROTOCOL.md`, v1.1).

KnowledgeOS and the established project engineering system remain authoritative.
`.claude/` is a separate peer execution harness and must not be changed by
Codex. This directory contains no duplicate engineering system, shared-script
copies, hooks, or project state; the harness does not rely on project-local
Codex runtime configuration.
