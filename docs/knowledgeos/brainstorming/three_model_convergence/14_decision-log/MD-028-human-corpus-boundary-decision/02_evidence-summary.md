# Evidence Summary (decision-relevant only)

| Observation | Evidence level | Establishes | Does NOT establish | Dependency |
|---|---|---|---|---|
| MD-010/MD-011 define the corpus boundary by directory location, 2026-09-01 | `DIRECT EVIDENCE` | The formal boundary this reconstruction has operated under since Phase 0 | Anything about `research/` specifically — never named | Independent, primary source |
| `docs/knowledgeos/research/` first Git-tracked 2026-09-06 | `MACHINE-OBSERVABLE FACT` | Tracking date only | Filesystem existence date, authorship date | Independent |
| The 2026-09-06 commit's own message distinguishes "brainstorming corpus" from "research lanes" | `DIRECT EVIDENCE` (of the author's own wording) | A human-authored linguistic distinction at archival time | Formal Phase-0 policy intent (predates it by 5 days); governance authority — falsification-tested in MD-027, confirmed archival not governance language | Same event as the tracking-date observation — **not independent of it** |
| M0030 cites `docs/knowledgeos/research/kernel-reduction/` as its own output path (lines 1443, 1488) | `DIRECT EVIDENCE` | An intended/instructed output location, stated in an admissible B document | Documented actual execution output | Independent |
| `kernel-reduction/00-INDEX.md` self-dates "Experiment ID KR-2026-09-01" | `DIRECT EVIDENCE` (of the source's own self-claim) | The directory's own claimed date | Independent, external confirmation of that date | Depends on trusting the source's own self-report |
| `04-operator-contracts.md` and M0030 share filesystem `mtime`s 19 minutes apart, same evening | `MACHINE-OBSERVABLE FACT`, low reliability | Consistency with same-session handling | Creation time, authorship, or execution — `mtime` is a known checkout artifact | Independent, but weak |
| `04-operator-contracts.md` contains a full, specified contract for all 13 C0 operators + `Qualify` | `DIRECT EVIDENCE` | The content itself, and that it would close the MD-024 specification gap if admitted | That the content is correct, validated, or was ever actually used by Model B's own admissible research | Independent |
| No admission mechanism found in `protocol.md`, MD-010, MD-011 | `MACHINE-OBSERVABLE FACT` (absence within documents read) | No mechanism found in the documents actually read | No mechanism exists anywhere in the repository (not exhaustively searched) | Independent |
| MD-027's 3 corrections to MD-026 (Git-tracking ≠ existence; convergent not independent; research lane not self-governing context) | `DIRECT EVIDENCE` (of the audit's own findings) | The corrected, governance-ready evidence baseline this package builds on | — | Depends on MD-026's own underlying observations, already accounted for above |

## What the evidence, taken together, supports

A **plausible, but not documented**, connection between admissible Model-B evidence (M0030) and
`kernel-reduction/`'s own content — strong enough to be worth a governance decision, not strong enough
to resolve itself. This is the entire evidentiary basis for DQ-1; nothing here decides it.
