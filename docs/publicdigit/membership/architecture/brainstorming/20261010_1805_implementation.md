analyse as senior principle architect to findout how far we are to complete membership implemenation and what are the rest of the todos ?

I'm grounding this assessment in the repository's own slice plan and what's actually built, not only in this session's history.

  Ran 2 shell commands

Membership implementation: where we stand

Short answer: about 20–25% of the planned implementation is built, around 0% of the business uses it, and no slice is formally accepted yet. All of this is my rough estimate from the repository's own 17-step slice plan (EP-01 design §18); I haven't measured it any other way.

1. Progress against the approved slice plan

┌──────┬──────────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────────────────┬─────────────────────────────────────────┐
│  #   │                        Slice                         │                                                State                                                │                Evidence                 │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 0    │ Semantic kernel                                      │ ✅ built; formal acceptance act outstanding                                                         │ PBDIGIT-258; 27 domain files            │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 1a   │ Repository and concurrency                           │ ✅ built; acceptance outstanding (S1-1, S1-3)                                                       │ PBDIGIT-259                             │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 1b   │ PostgreSQL adapter                                   │ ✅ built; only conditionally accepted (ARB-MEM-02); follow-ups open                                 │ PBDIGIT-260, 264                        │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 2    │ Admit + End commands                                 │ ⚠ handlers exist from 1a, but the slice isn't started: no authority check, no End kinds, no         │ needs BD-CONSENT, BD-REC0, BD-3         │
│      │                                                      │ business decisions                                                                                  │                                         │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 3    │ Queries and projections                              │ ❌ not started                                                                                      │ —                                       │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 4–6  │ Renew/ChangeType, Suspend/Restore, membership number │ ❌ not started                                                                                      │ needs BD-TYPE, BD-SUSP, BD-2, BD-NU     │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 7    │ Correct / Annul / Void                               │ ◐ about 25%: only C-1 (Annul/Void in the kernel) is built, awaiting G3. C-2, C-3 and C-4 aren't     │ PBDIGIT-268; needs BD-REV-ER, CQ-3,     │
│      │                                                      │ started                                                                                             │ D-COR-4                                 │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 8–12 │ Application process, Governance, Election, Finance,  │ ❌ not started                                                                                      │ needs DS-1, 2, 4, 5 and BD-GVR          │
│      │ Audit                                                │                                                                                                     │                                         │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 13   │ Migration and per-organisation cutover               │ ❌ not started                                                                                      │ needs BD-NORM, EP-Q3; ARB-MEM-01        │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 14   │ API / UI                                             │ ❌ not started                                                                                      │ —                                       │
├──────┼──────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┤
│ 15   │ Full regression + legacy retirement                  │ ❌ not started                                                                                      │ last                                    │
└──────┴──────────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────────────────┴─────────────────────────────────────────┘

Why I put it at 20–25%: about 3.25 of 17 slices are built. Those slices are the hardest correctness core, so they count for more than their number. The integration, migration and cutover work (slices 8–15) is the largest and riskiest part, and none of it has started.

How much the business uses it: about 0%.
- Nothing outside the Membership context calls the new kernel; I found no caller in app/ or routes/.
- The legacy mutable model is still the only system that writes membership data.

Formally accepted slices: 0 of 17.

2. What is solid and what isn't

Strong:
- The domain theory and its correction algebra are sound: append-only facts, liveness, re-entry, the "what was known at time τ" view, and the guard G*.
- The evidence for that theory is unusually rigorous: exhaustive bounded differential testing, property tests, mutation testing at 92% (95% on covered code), and a sensitivity kit.
- Targeted tests: 89 domain, 34 application and 12 architecture tests pass at HEAD.

Weak or missing:
- The full Membership suite isn't green: 27 + 29 errors outside the new kernel, with no owner established.
- PostgreSQL persistence evidence is stale. There's no isolated test database, which blocks 1b acceptance, PBDIGIT-264, PBDIGIT-266 and C-4.
- There is no integration evidence with Election, Finance or Governance.

3. What is slowing delivery

1. Open business decisions. Most remaining slices are waiting on the PO and Legal, not on engineering:
   - BD-CONSENT, BD-REC0 and BD-3, for Slice 2;
   - BD-REV-ER, for C-2;
   - CQ-3 and DS-4 (authority), for C-3 and Slice 9;
   - BD-TYPE, BD-SUSP, BD-2 and BD-NU;
   - DS-1, DS-2, DS-5 and BD-GVR (integration);
   - BD-NORM and EP-Q3 (migration);
   - CQ-9 (legal).
2. No isolated test database, which blocks every persistence check.
3. Governance overhead. The last stretch of work went mostly into documentation gates for one slice. Each gate was justified, but at this rate the remaining 14 slices would take a very long time. Recommendation: batch the governance. For example, run one acceptance act covering Slices 0, 1a, 1b and C-1, and a standing approval template for documentation-only reconciliations.

4. Remaining TODOs

Close what exists (now):
- the Principal Reviewer reviews ce8bf3109; then the G3 decision on C-1 (PBDIGIT-268);
- formal acceptance acts for Slice 0 (PBDIGIT-258), Slice 1a (PBDIGIT-259) and Slice 1b (PBDIGIT-260, ARB-MEM-02);
- an optional read-only test rerun that captures all exit codes;
- commit the untracked strategy document, traceability audit and MEMORY pointer, or drop them.

Unblock evidence:
- provide an isolated PostgreSQL test database (a PO or infrastructure task);
- rerun the PostgreSQL Membership suite;
- do PBDIGIT-264 (the far-date defect, race tests, foreign key);
- run the PBDIGIT-266 security tests;
- triage the 27 + 29 wider-suite errors (PBDIGIT-167?) into regressions versus pre-existing debt;
- PBDIGIT-267 (kernel test gaps) and PBDIGIT-269 (runner hardening), once authorised.

Decide (PO, Human ARB, Legal):
- BD-CONSENT, BD-REC0 and BD-3, to unblock Slice 2;
- BD-REV-ER and D-COR-3, to unblock C-2;
- CQ-3, DS-4 and G-AUTH-1, including the meaning of "reason" in provenance;
- BD-TYPE, BD-SUSP, BD-2, BD-NU and EP-Q5;
- DS-1, DS-2, DS-5 and BD-GVR (Finance and Election);
- BD-NORM and EP-Q3 (migration);
- CQ-9 (appeals, effective from the start or from now);
- D-COR-1, D-COR-4 and D-COR-5 (B).

Build (each slice planned, approved and test-first):
- Slice 2: Admit + End with the interim authority check and End kinds;
- Slice 3: queries (GetMember, Timeline, History, KnownAt) and projections, which make the kernel observable;
- C-2: Correct and consequences;
- C-3: application commands, authority and event emission;
- C-4: persistence of Annul / Void / Correct, including F-1 and the isolated-database round-trips;
- Slices 4, 5 and 6: Renew/ChangeType, Suspend/Restore, membership number;
- Slice 8: wire application approval to AdmitMember;
- Slices 9–12: Governance authority port, Election MembershipQueryPort with parity tests, Finance integration, audit fact stream.

Transition and release:
- Slice 13: migration dry-run, data normalisation, per-organisation cutover with no dual write, legacy made read-only (ARB-MEM-01);
- Slice 14: API and UI;
- Slice 15: full regression and retirement of legacy models, jobs and paths;
- cross-context checks: Membership must never grant voting rights by itself, and Election-Only mode must stay valid;
- operational readiness: rollback, reconciliation, monitoring.

Where machine learning would justifiably help:
- Slice 13 migration only: entity resolution and anomaly detection to flag duplicate people, conflicting dates and gaps in legacy member data, for a registrar to decide on (BD-NORM).
- Not in the kernel. Membership rules must stay deterministic and explicit.

5. Recommended next three moves

1. One batched acceptance act covering Slices 0, 1a, 1b and C-1. This turns about 20% of the plan into accepted work.
2. An isolated PostgreSQL test database, plus a triage of the wider-suite errors, so persistence and regression evidence becomes trustworthy.
3. A decision session on BD-CONSENT, BD-REC0, BD-3, BD-REV-ER and CQ-3. That unblocks Slices 2 and 3 and C-2/C-3, the shortest path to a Membership the product actually uses.
Stop says: Project-state sync (CLAUDE.md):
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-10-10-files.log)
