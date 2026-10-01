# Constitutional Governance Runtime — Implementation Runbook and Ticket Sequence

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. An **instruction document for implementation sessions**. **It authorises nothing.** Each ticket needs its own PO authorisation, and its own ARB architecture approval when its gate includes A, B or E. |
| **Source of truth** | [`2026-09-30-constitutional-runtime-pbdigit-backlog.md`](../reviews/2026-09-30-constitutional-runtime-pbdigit-backlog.md) (77 tickets, `PBDIGIT-76` … `PBDIGIT-152`). If this runbook and the backlog disagree, **the backlog wins**. |
| **How the sequence was produced** | Computed from the backlog's dependency graph: wave = 1 + highest wave among a ticket's implementation dependencies, with decisions at wave 0. Check: 15 waves, 0 violations (no ticket shares a wave with, or precedes, its dependency). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `implementation/` holds the implementation records |
| **Companion** | [`2026-10-01-constitutional-runtime-execution-map.md`](2026-10-01-constitutional-runtime-execution-map.md): the Jira-ready execution map (master index, per-ticket execution cards) |

---

## 1. Where to start — the short answer

**Three sequences, three first tickets; there is no single overall 'first ticket':**

| Sequence | First implementation |
|---|---|
| **Track A1 — Admin Constitutional Governance** | **`PBDIGIT-139` part A** |
| **Track A2 — Voting Participation Governance** | **`PBDIGIT-76`** |
| **Track B — General repository/freeze work** | **`PBDIGIT-133`** |

The overall repository sequence (§5 waves) ≠ the Constitutional Governance sequence (A1) ≠ the Voting Participation sequence (A2).

| Step | Do | Why |
|---|---|---|
| **0** | Ask the PO/ARB for the **decisions** in §4 now, in parallel. No code. | They gate waves 3–13. Asking now prevents later stalls |
| **1** | **`PBDIGIT-139` part A**: repair the 4 genuine capability/suspension test failures (fixtures only) | **First Constitutional Governance implementation (Track A1).** It gates `140`, which gates the shared resolver mechanics `109` |
| **2** | **`PBDIGIT-76` → `77` → `78`** (C-1): repair the voting feature-test fixtures | **First Voting Participation implementation (Track A2).** Together they gate all voting characterization (`79`/`80`) |
| **2'** | In parallel, any time: **`PBDIGIT-133`** (scanner false positive) | **First general repository implementation (Track B)**; independent |
| **3** | **`PBDIGIT-140`** (characterize the five admin authorities) and **`79`/`80`** (characterize the voting gates) | Nothing that changes behaviour starts before its characterization exists |

**Do not start anything in wave 3 or later that changes production behaviour until the characterization it depends on is merged.** The graph enforces this. This runbook only restates it.

---

## 2. Rules for every implementation session

### 2.1 Before touching anything

1. **Use your own git worktree.** Concurrent sessions sharing one index have twice swept other sessions' staged files into unrelated commits (`a72528b29`, `4c69fa9ab`). Ask the human to start the session in a separate worktree, or make sure no other session is running.
2. Record `git status --short`, `git branch --show-current`, `git log -5 --oneline`. **Preserve all unrelated changes**: KnowledgeOS, UC-4, other PBDIGIT work.
3. Read the ticket's full specification in the backlog: Purpose, Problem, Scope, **Out of scope**, Acceptance criteria, Architecture gate, Risk, Evidence required.
4. **Check authorisation.** The README index must no longer say `TODO — NOT AUTHORISED` for this ticket, and the PO must have authorised it in this conversation or in a recorded act. For gate **A, B or E**, an ARB architecture approval is also required. **A signed decision is not an authorisation. "All dependencies done" is not an authorisation.**
5. Check that every dependency listed in §5 for the ticket is merged, and every decision it needs is signed.
6. Run the project's session bootstrap and read `.claude/MEMORY.md`, the routed CONTEXT file and today's session log (CLAUDE.md workflow).

### 2.2 While implementing

7. **Characterize, then RED, then GREEN.** Characterization tickets pin current behaviour (`@characterization`). Behaviour-changing tickets write the failing test first, as its own commit where production code changes, then the minimal change.
8. **The Files field is a guidance boundary, not a permission.** It names where a change is *expected*. It does not authorise modifying every listed file. Each actual modification must be justified by the ticket's Scope and Acceptance criteria and shown in the final diff. A file *not* listed may be touched only if the Scope requires it, and the deviation is reported.
9. **Stay inside the ticket's Scope.** No "cleanup while here", no opportunistic refactor, no redesign. Production defects found along the way become a **new PBDIGIT ticket** (next free number, checked with `git grep`). They are never fixed silently.
10. **Never restore a removed production API to satisfy a test** (e.g. `ElectionMembership::assignVoter()`). Classify the case as translation, obsolete test, or genuine defect leading to a new ticket.
11. **Edit source deliberately** (Read → Edit). No `sed -i` or mass regex rewrites over `app/`, `tests/`, `routes/`, `database/` (CLAUDE.md editing policy).
12. Respect anonymity: no voter↔vote linkage, no voting codes in logs or test output (ADR-T11).

### 2.3 Verifying

13. Run the ticket's narrow test command first, then the broader suite:
    ```bash
    vendor/bin/phpunit --no-coverage <the ticket's files/suites>
    php artisan test --no-coverage
    ```
    Tests run against `nrna_test` (`phpunit.xml`). `RefreshDatabase` is the isolation mechanism.
14. **Never** run `php artisan migrate:fresh`, `migrate:refresh` or `--seed` against the development database.
15. Report exact totals (tests / assertions / failures / errors / skipped / risky) **before and after**. Never write "tests pass" without the numbers.
16. Show `git diff --stat` and confirm nothing outside the ticket's allowed files changed.

### 2.4 Finishing

17. Write or update the developer guide for the step under `developer_guide/<area>/` (Definition of Done).
18. Commit **only the ticket's paths**: `git commit -- <paths>`. The subject carries **`(PBDIGIT-nn)`**, with a RED commit and a GREEN commit where applicable.
19. Update the session log (`.claude/sessions/YYYY-MM-DD.md`) and the routed CONTEXT file.
20. **Engineering supplies evidence and never accepts its own work.** The ticket moves to accepted only by the PO/ARB.
21. **STOP.** Do not start the next ticket without its own authorisation.

---

## 3. The three tracks

| Track | Covers | Tickets |
|---|---|---|
| **A1 — Admin Constitutional Governance** (EPIC-03) | one capability authority for admin lifecycle actions | `139`–`152` (except decisions `141`, `150`) and `109` |
| **A2 — Voting Participation Governance** (EPIC-05) | CAST_VOTE: the resolver decides who may vote, from frozen law and recorded evidence | `76`–`84`, `101`–`121`, `135`–`137` |
| **B — General repository/freeze work** (CI, docs, hygiene, v1.0 freeze) | hygiene, enforcement, documentation | `122`–`134` (CI, DOC, scanner, collision) |
| **X — security fixes** | defects under any architecture; PO-scheduled | `85`–`89` (each after its C-3 pin) |

**The tracks meet in two places:** the resolver mechanics `109` (A1 → A2), and the v1.0 freeze `132`.

---

## 4. Decisions to request first (wave 0 — no code)

| Decision | Question | First ticket it blocks |
|---|---|---|
| **`PBDIGIT-139` part B** | update or retire the 3 obsolete layer-priority tests | 139 B (part A is not blocked) |
| H-13 `PBDIGIT-141` | owner and vocabulary of governance role authority | `142`, `144` (wave 3–4) |
| H-14 `PBDIGIT-150` | suspension: lifecycle state or operational overlay | `146` (wave 3) |
| H-5 `PBDIGIT-94` | where the action registry lives | `146`, `110` |
| H-1 `90` · H-2 `91` · H-3 `92` · H-4 `93` | verified IP · fingerprint source · trust scaling · rule-7 counting | `101`, `106` (wave 3) |
| H-7 `PBDIGIT-96` | canonical already-voted fact and membership predicate | `105` |
| H-10 `99` · H-11 `100` | immutability level · law for existing elections | `103`, `104` |
| H-8 `97` · H-9 `98` | overlays in CAST_VOTE · demo scope | `111`, `113` |
| H-6 `PBDIGIT-95` | single platform-organisation rule | `120` |
| H-12 `PBDIGIT-138` | store voting codes hashed? | `132` |
| `PBDIGIT-45` ADR acceptance (`ADR_20260806_1620`, PROPOSED) | per-IP rule ownership and retirement | `117`, `121` |

---

## 5. The complete sequence (computed waves)

**How to read it:**
- Every ticket in a wave can start once all tickets it needs in earlier waves are merged, and its decisions are signed.
- Tickets **in the same wave are independent** and may run in parallel, each in its own worktree and with its own authorisation.
- A wave is not a deadline. A1 and A2 move at their own pace.

#### Wave 1

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-76` | A2 Voting Participation Governance | Repair voting feature-test fixtures for current schema (retired columns) | — | — | — |
| `PBDIGIT-77` | A2 Voting Participation Governance | Repair voting feature-test tenancy, factory, constructor and route fixtures | — | — | — |
| `PBDIGIT-78` | A2 Voting Participation Governance | Repair voting-active lifecycle fixtures to current lifecycle rules | — | — | — |
| `PBDIGIT-122` | B General repository/freeze | Delete dead legacy code and retire tests of non-existent classes | — | — | — |
| `PBDIGIT-130` | B General repository/freeze | Correct false completion claims in historical documents | — | — | — |
| `PBDIGIT-133` | B General repository/freeze | Temporal-determinism audit scanner flags `validate(` as a `date()` call | — | — | — |
| `PBDIGIT-134` | B General repository/freeze | Resolve the `PBDIGIT-69` numbering collision | — | — | — |
| `PBDIGIT-139` | A1 Admin Constitutional Governance | Repair capability-suite and suspension-suite test drift | — | — | — |

#### Wave 2

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-79` | A2 Voting Participation Governance | Characterize the voting entry path at the HTTP boundary | `76` `77` `78` | — | — |
| `PBDIGIT-80` | A2 Voting Participation Governance | Characterize the ballot, verify and store path at the HTTP boundary | `76` `77` `78` | — | — |
| `PBDIGIT-123` | B General repository/freeze | Bring election security zones under a blocking CI job | `76` `77` `78` `139` | — | — |
| `PBDIGIT-140` | A1 Admin Constitutional Governance | Characterize the admin-action authorities at the HTTP boundary | `139` | — | — |

#### Wave 3

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-81` | A2 Voting Participation Governance | Pin voting-flow control defects X-1, X-2, X-3 | `80` | — | — |
| `PBDIGIT-82` | A2 Voting Participation Governance | Pin voting-code secrecy defects X-4, X-5 | `80` | — | — |
| `PBDIGIT-83` | A2 Voting Participation Governance | Pin participation-bypass defects X-6, X-7 | `79` | — | — |
| `PBDIGIT-84` | A2 Voting Participation Governance | Pin configuration and schema defects X-8…X-12 | `79` `80` | — | — |
| `PBDIGIT-101` | A2 Voting Participation Governance | One canonical constitution representation, schema version and hash | `79` `80` | H-1 `PBDIGIT-90`, H-2 `PBDIGIT-91`, H-3 `PBDIGIT-92`, H-4 `PBDIGIT-93` | B |
| `PBDIGIT-106` | A2 Voting Participation Governance | Typed evidence: network, device and officer-verification facts with provenance | `79` `80` | H-1 `PBDIGIT-90`, H-2 `PBDIGIT-91`, H-4 `PBDIGIT-93` | C |
| `PBDIGIT-109` | A1 Admin Constitutional Governance | R-1a — resolver mechanics: Resolver evaluates every policy and returns a stateless trace | `140` | — | E |
| `PBDIGIT-128` | B General repository/freeze | CI rule 7 — Forbidden procedural vocabulary in canonical governance code | `123` | — | — |
| `PBDIGIT-135` | A2 Voting Participation Governance | Characterize the non-slug `POST /votes` route and route-file loading | `80` | — | — |
| `PBDIGIT-136` | A2 Voting Participation Governance | Characterize `voter_slugs.vote_completed`, which no code writes | `79` | — | — |
| `PBDIGIT-142` | A1 Admin Constitutional Governance | Suspend and resume bypass ConstitutionalTransitionGuard | `140` | H-13 `PBDIGIT-141` | A |
| `PBDIGIT-143` | A1 Admin Constitutional Governance | Resume writes no audit record | `140` | — | G |
| `PBDIGIT-146` | A1 Admin Constitutional Governance | Declare overlay behaviour in the constitution and fix the placeholder resume target | `140` | H-5 `PBDIGIT-94`, H-14 `PBDIGIT-150` | B |
| `PBDIGIT-148` | A1 Admin Constitutional Governance | Lifecycle derivation cannot be recomputed for a given instant | `140` | — | C |

#### Wave 4

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-85` | X security fix | X-3 — Release the vote lock and roll back on the store IP-denial branch | `81` | — | G |
| `PBDIGIT-86` | X security fix | X-4 — Stop logging voting codes and compare codes in constant time | `82` | — | F |
| `PBDIGIT-87` | X security fix | X-5 — Remove plaintext debug_code exposure outside local environments | `82` | — | F |
| `PBDIGIT-88` | X security fix | X-6 — Remove the session demo-flag bypass from real-election gates | `83` | — | A |
| `PBDIGIT-89` | X security fix | X-10 — Fix the two voting-path TypeErrors (whitelist decode, UUID user id) | `84` | — | A, C, F |
| `PBDIGIT-102` | A2 Voting Participation Governance | Author constitutional law at election creation and freeze it | `101` | — | B |
| `PBDIGIT-103` | A2 Voting Participation Governance | Enforce constitutional immutability and verify the hash on load | `101` | H-10 `PBDIGIT-99` | B, G |
| `PBDIGIT-104` | A2 Voting Participation Governance | Establish constitutional law for existing elections | `101` | H-11 `PBDIGIT-100` | B, G |
| `PBDIGIT-105` | A2 Voting Participation Governance | Typed evidence: membership, lifecycle and already-voted facts | `79` `80` `136` | H-7 `PBDIGIT-96` | C |
| `PBDIGIT-107` | A2 Voting Participation Governance | Typed evidence: ballot/code session facts under the constitutional protocol | `79` `80` `101` | — | C |
| `PBDIGIT-108` | A2 Voting Participation Governance | Evaluators: network (composable binding + limit), device comparison, verification | `101` `106` | H-3 `PBDIGIT-92`, H-4 `PBDIGIT-93` | D |
| `PBDIGIT-144` | A1 Admin Constitutional Governance | Resolver role and precondition policies for admin actions | `109` `140` | H-13 `PBDIGIT-141` | A, E |

#### Wave 5

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-110` | A2 Voting Participation Governance | R-1b — CAST_VOTE integration: Typed CAST_VOTE context and participation action registry | `109` `105` `106` `107` | H-5 `PBDIGIT-94` | E |
| `PBDIGIT-125` | B General repository/freeze | CI rule 2/4 — Canonical zones cannot import legacy authority; policies cannot consume policy outcomes | `123` `108` | — | — |
| `PBDIGIT-126` | B General repository/freeze | CI rule 3 — Canonical zones cannot read mutable configuration or live models | `123` `108` | — | — |
| `PBDIGIT-145` | A1 Admin Constitutional Governance | Remove the lifecycle engine's second capability matrix | `144` | — | A, E |
| `PBDIGIT-151` | A1 Admin Constitutional Governance | Align Laravel ElectionPolicy gates and controller state inference with the resolver | `144` | H-13 `PBDIGIT-141` | A, E |

#### Wave 6

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-111` | A2 Voting Participation Governance | R-1b — CAST_VOTE integration: Register the CAST_VOTE resolver policy set | `110` `108` | H-8 `PBDIGIT-97`, H-9 `PBDIGIT-98` | A, E |
| `PBDIGIT-147` | A1 Admin Constitutional Governance | Frontend renders governance capabilities only | `145` | — | F |
| `PBDIGIT-152` | A1 Admin Constitutional Governance | Route-level operation capability matrix duplicates the resolver | `145` `146` | H-13 `PBDIGIT-141` | A, E |

#### Wave 7

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-112` | A2 Voting Participation Governance | Persist a CAST_VOTE decision record for every evaluation | `111` | — | G |
| `PBDIGIT-124` | B General repository/freeze | CI rule 1 — Only the resolver and registered policies may create CapabilityDecision | `123` `111` | — | — |
| `PBDIGIT-149` | A1 Admin Constitutional Governance | Governance lifecycle end-to-end suite through HTTP | `139` `140` `142` `143` `145` `146` `147` `148` `152` `151` | H-13 `PBDIGIT-141`, H-14 `PBDIGIT-150` | — |

#### Wave 8

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-113` | A2 Voting Participation Governance | Shadow-evaluate CAST_VOTE in the live voting path | `112` | H-9 `PBDIGIT-98` | G |
| `PBDIGIT-114` | A2 Voting Participation Governance | Replay harness: reproduce stored decisions with DB access disabled | `112` | — | — |

#### Wave 9

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-115` | A2 Voting Participation Governance | Runtime parity harness at the HTTP boundary with persisted divergences | `113` | — | — |
| `PBDIGIT-129` | B General repository/freeze | CI rule 8 — Constitutional immutability and replay sufficiency run in CI | `123` `103` `114` | — | — |

#### Wave 10

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-116` | A2 Voting Participation Governance | Execute the 14 critical parity paths and obtain approval of expected divergences | `115` | — | — |

#### Wave 11

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-117` | A2 Voting Participation Governance | Switch the vote-submission decision to CAST_VOTE | `116` `85` `135` | `PBDIGIT-45` (ADR acceptance) | A, E |
| `PBDIGIT-137` | A2 Voting Participation Governance | Dispose of the unwired divergence-observation infrastructure | `116` | — | — |

#### Wave 12

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-118` | A2 Voting Participation Governance | Drive the entry and ballot pages from the CAST_VOTE decision | `117` | — | A, E |

#### Wave 13

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-119` | A2 Voting Participation Governance | Delete the legacy trust-decision pipeline | `118` | — | A |
| `PBDIGIT-120` | A2 Voting Participation Governance | Delete legacy participation middleware | `118` | H-6 `PBDIGIT-95` | A, F |
| `PBDIGIT-121` | A2 Voting Participation Governance | Delete duplicate controller participation gates and per-IP authorities | `118` | `PBDIGIT-45` (ADR acceptance) | A |

#### Wave 14

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-127` | B General repository/freeze | CI rule 5 — Snapshots cannot evaluate; middleware cannot derive participation | `123` `120` | — | — |
| `PBDIGIT-131` | B General repository/freeze | Consolidated Constitutional Governance Runtime developer guide | `121` `119` `120` | — | — |

#### Wave 15

| Ticket | Track | Title | Needs (implementation) | Needs (decision / external) | Gate |
|---|---|---|---|---|---|
| `PBDIGIT-132` | B General repository/freeze | Constitutional Governance Runtime v1.0 freeze | `131` `124` `125` `126` `127` `128` `129` `104` `102` `86` `87` `88` `89` `122` `130` `152` `151` `133` `134` `137` `142` `143` `146` `147` `148` `149` | H-12 `PBDIGIT-138` | A, B, E |

---

## 6. Recommended order inside the waves (when one implementer works serially)

**Track A1 — Admin Constitutional Governance**

```
139 A → (139 B after disposition) → 140 → 109 → 143 → 148 → 142 [H-13] → 144 [H-13] → 145 → 151 → 146 [H-5, H-14]
      → 152 → 147 → 149 (E2E acceptance)
```

**Track A2 — Voting Participation Governance**

```
76 → 77 → 78 → 79 → 80 → 81 → 82 → 83 → 84 → 135 → 136
   → (X fixes 85–89 as the PO schedules them)
   → 101 [H-1..H-4] → 106 → 105 [H-7] → 107 → 108 → 102 → 103 [H-10] → 104 [H-11]
   → 110 [H-5, needs 109 from A1] → 111 [H-8, H-9] → 112 → 114 → 113 → 115 → 116
   → 117 [PBDIGIT-45 accepted, 85, 135] → 118 → 119 → 120 [H-6] → 121 [PBDIGIT-45] → 137
```

**Track B — General repository/freeze work (slot in whenever capacity allows)**

```
133 · 134 · 130 · 122 → 123 (after C-1 + 139) → 128 → 125 · 126 (after 108) → 124 (after 111)
     → 129 (after 103 + 114) → 127 (after 120) → 131 → 132 v1.0 freeze
```

## 7. Checkpoints (stop and report to the PO/ARB)

| Checkpoint | Reached when | What it proves |
|---|---|---|
| **CP-1 Test baseline** | `139` A (+B disposition), `76`–`78` merged | the suites observe today's production contract |
| **CP-2 Characterization complete** | `79`, `80`, `81`–`84`, `135`, `136`, `140` merged | every current gate and defect is pinned before anything moves |
| **CP-3 Admin capability sovereignty** | `142`–`148`, `151`, `152`, `149` merged | one admin capability authority, proven end to end |
| **CP-4 Law + evidence + evaluation** | `101`–`108` merged | frozen, hash-verified law; typed evidence; pure evaluators |
| **CP-5 Shadow + replay** | `110`–`114` merged | the resolver decides CAST_VOTE in shadow; decisions are persisted and replayable |
| **CP-6 Parity** | `115`, `116` merged; divergences signed | the new authority matches the legacy one or corrects it by approval |
| **CP-7 Authority switch** | `117`, `118` merged | the resolver is the voting authority |
| **CP-8 Legacy removed + enforced** | `119`–`122`, `137`, CI `123`–`129` blocking | no competing authority remains, and CI prevents its return |
| **CP-9 v1.0** | `130`, `131`, `132` | freeze accepted by the PO/ARB |

## 8. When to stop immediately

- A ticket would need a change outside its Scope or in its Out-of-scope list.
- A test can only pass by changing an assertion that the ticket does not explicitly allow.
- An implementation choice would decide an open H question (verified IP, roles, suspension, …).
- A production defect is found. Record a new ticket and do not fix it.
- Another session's changes appear in the working tree or the index.
- A dependency or decision listed in §5 is not actually merged or signed.

**In every case:** report, propose, wait. **Never let implementation silently make an architectural decision.**

---

**Traceability.**
- **Backlog:** `docs/publicdigit/reviews/2026-09-30-constitutional-runtime-pbdigit-backlog.md` (§4e architecture gate, §4i two tracks, §15 Definition of Done).
- **Index:** `docs/publicdigit/backlog/README.md`.
- **Sources:** reality audit and readiness review (2026-09-30).
- **Process:** CLAUDE.md (EP-01 plan-first, Definition of Done developer guides, source-editing policy, database rules).
