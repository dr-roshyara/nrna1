---
source:
  original_name: "Yes2.md"
  original_path: "docs/knowledgeos/brainstorming/Yes2.md"
  detected_timestamp: "2026-08-19 10:48:30"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "off-topic-publicdigit"
  type: noise
  note: "PublicDigit Election-Only mode readiness — off-topic for the KnowledgeOS architecture corpus"
status:
  authoritative: false
  proposed: false
---

Yes. We are **much further than it may feel**, but we are not yet at the point where I would say *“Election-Only mode is ready.”*

The important thing is that we have now separated **implemented/verified work**, **real defects/risks**, **business decisions**, and **architecture questions**. That is exactly what we needed before making more code changes.

## 1. Where we are now

I would describe the current position as:

```text
                    ELECTION-ONLY MODE
                           │
          ┌────────────────┴────────────────┐
          │                                 │
     FOUNDATION                       REMAINING WORK
          │                                 │
          ▼                                 ▼
   EM-VOT-002                      Decisions + eligibility
   implemented                     + readiness verification
   independently verified
          │
          ▼
       CLOSED
```

### ✅ Already established

| Area                                                | Status                          |
| --------------------------------------------------- | ------------------------------- |
| EM-VOT-002 — approved candidate required for voting | ✅ Implemented                   |
| `open_voting` command path                          | ✅ Verified                      |
| computed lifecycle path                             | ✅ Verified                      |
| approved vs pending candidate distinction           | ✅ Verified                      |
| `candidates_count` not treated as authority         | ✅ Verified                      |
| EM-VOT-002 fixtures                                 | ✅ Corrected                     |
| Independent Session-1 verification                  | ✅ Done                          |
| EM-OPEN-021 technical behaviour                     | ✅ Investigated deeply           |
| Production path creating anomalous state            | ✅ Proven                        |
| HTTP 500 consequence                                | ✅ Proven                        |
| `forceCloseNomination()` contribution               | ✅ Proven                        |
| Session 3 implementation discipline                 | ✅ Preserved                     |
| Session 4 architecture archaeology                  | ✅ Completed                     |
| Architecture ADR                                    | 🟡 Proposed                     |
| PKS authority/code-placement rule                   | 🟡 Candidate, not authoritative |

So **EM-VOT-002 is not part of the remaining implementation backlog anymore.**

---

# 2. The biggest remaining blocker: EM-OPEN-021

This is now the most concrete lifecycle problem.

We know:

```text
forceCloseNomination()
        │
        ├── pending candidacies → rejected
        │
        └── nomination_completed = true
                         │
                         ▼
             voting window opens
                         │
                         ▼
             0 approved candidates
                         │
                         ▼
                EM-VOT-002 blocks
                         │
                         ▼
             no lifecycle state
                         │
                         ▼
          InvalidElectionStateException
                         │
                         ▼
                    HTTP 500
```

And Session 1 established that this isn't merely a broken test fixture.

The remaining question is **not technical anymore**:

> What should the Election domain mean when nomination has been completed/force-closed, there are zero approved candidates, and the voting window subsequently opens?

That is **EM-OPEN-021**.

### Therefore:

**PO/ARB decision required before Session 3 changes lifecycle semantics.**

We should *not* let the current exception silently become the business rule.

---

# 3. The second major blocker: Election-Only eligibility

This is the other major area.

Session 2 established that there are currently **two eligibility authorities** and that the current implementation has problems around ambient context / tenant-blind resolution.

The important point is:

> We have not yet authorized the repair.

The two unresolved decisions are:

### D-Ownership

Who owns **voting-time eligibility resolution**?

The evidence currently points toward the Election context rather than an ambient/general membership context, but this must be formally decided.

### D-Scope

For the `BelongsToTenant` family:

> Do we fix the identified instances only, or audit/fix the broader pattern?

Until these two decisions are made:

```text
PBDIGIT-65 / PBDIGIT-69
        │
        ▼
     BLOCKED
        │
        ├── ownership decision
        └── scope decision
```

This is why Session 3 is correctly **STOPPED**.

---

# 4. BR-1.12 is another important decision

This concerns the **admission state / admission model**.

Session 3's readiness gate identified this together with AD-2 as a major dependency.

Conceptually:

```text
Admission
   │
   ▼
Who is eligible to enter the election?
   │
   ▼
How is that eligibility established?
   │
   ▼
What state does the election enter?
```

Until BR-1.12 is settled, we should not implement admission behaviour merely because we can technically make it work.

---

# 5. 59 + 67 still need decisions

Session 2 correctly separated these rather than treating them as one vague issue.

They concern **time semantics**.

We need the business/domain decisions first, then implementation can follow.

So:

```text
BR-1.12
59
67
```

are still part of the decision layer.

---

# 6. Session 1 still has a small verification tail

Session 1 has done a lot.

It should **not** go back and blindly classify all 1,376 tests right now.

The next bounded verification we gave it is:

### P4-A

Read the three:

`VoterStrategySnapshotTest`

and establish whether they are:

* genuine Election-Only issue,
* obsolete expectation,
* fixture issue,
* pre-existing failure,
* unrelated infrastructure issue.

### P4-B

Falsify the six Security rows previously called "almost certainly unrelated."

This is important because Session 1 has repeatedly caught itself making attribution errors.

After that, **stop again**.

---

# 7. Session 4 is essentially finished

Session 4 did something very valuable.

It investigated the repository's competing architectural generations and produced:

* architecture archaeology
* proposed ADR
* PKS candidate
* handoff

The committed artifact set is:

`5ff413a3`

with the four architecture documents.

But:

> **The ADR is PROPOSED.**
>
> **The PKS is CANDIDATE and NOT AUTHORITATIVE.**

So we must not behave as though Session 4 has already established a new architecture rule.

This needs governance review before becoming authoritative.

---

# 8. Session 3 is correctly stopped

This is very important.

Session 3 currently has **no implementation authorization**.

Its latest readiness conclusion is effectively:

```text
Can we safely implement another ticket?

             NO
              │
              ▼
    required decisions missing
              │
      ┌───────┼────────┐
      ▼       ▼        ▼
 EM-OPEN   AD-2/    BR-1.12
  -021     ownership
              │
              ▼
          STOP
```

That is correct.

We should **not tell Session 3 "keep coding"** simply because we want Election-Only finished.

---

# 9. What remains — the actual TODO roadmap

I would now organize the remaining work into **six gates**, rather than dozens of tickets.

## Gate 1 — Finish independent verification

**Session 1**

* [ ] P4-A: investigate 3 `VoterStrategySnapshotTest` failures
* [ ] P4-B: isolate the 6 Security rows
* [ ] classify only from actual evidence
* [ ] stop

No production changes.

---

## Gate 2 — Make the business decisions

**PO / ARB / you**

### Lifecycle

* [ ] **EM-OPEN-021**

Decide what happens to:

> completed/force-closed nomination + zero approved candidates + voting window opens

### Eligibility

* [ ] **D-Ownership**
* [ ] **D-Scope**

These unblock PBDIGIT-65/69.

### Admission

* [ ] **BR-1.12**

### Time

* [ ] **59**
* [ ] **67**

### Other existing decisions

* [ ] SD-15
* [ ] EM-OPEN-013
* [ ] SD-4
* [ ] SD-3
* [ ] Q-B1

Not all necessarily block Election-Only implementation equally, so we should classify them into **blocking / non-blocking** after the above.

---

# 10. Gate 3 — Governance the architecture-placement result

Session 4's work needs review.

### Session 2

Should review:

* proposed ADR
* PKS candidate
* architecture authority matrix
* evidence hierarchy
* F1–F6 / G-1…G-10 findings

### Session 1

Should falsify the important claims.

Only after that:

```text
ADR
  ↓
accepted?

PKS
  ↓
promoted?
```

If accepted, this gives us the missing **"which file belongs where?"** governance rule you were asking about.

That is very useful before more implementation.

---

# 11. Gate 4 — Authorize the Election-Only implementation slices

Only after the decisions are made.

Then Session 3 should **not receive a generic instruction like "fix Election-Only."**

It should receive a bounded authorization:

```text
Decision
   ↓
Business rule
   ↓
Bounded Context
   ↓
Authoritative layer
   ↓
Existing file
   ↓
TDD RED
   ↓
minimal implementation
   ↓
GREEN
   ↓
independent verification
```

This is where our DDD discipline matters most.

We don't want:

> "There is a problem with eligibility, create an EligibilityService."

Instead:

> "The Election bounded context owns voting-time eligibility. The accepted architecture says X. The existing authoritative policy/query boundary is Y. Implement only the approved behaviour."

---

# 12. Gate 5 — Run the Election-Only acceptance journey

This is the part I think we have been missing conceptually.

We should stop thinking only in terms of individual tickets.

We need to prove the **whole Election-Only journey**.

Something like:

```text
1. Create election
       ↓
2. Configure election
       ↓
3. Establish Election-Only voter source
       ↓
4. Admit/assign voters
       ↓
5. Nomination
       ↓
6. Candidate approval
       ↓
7. Complete nomination
       ↓
8. Open voting
       ↓
9. Voter eligibility
       ↓
10. Voter verification
       ↓
11. Ballot access
       ↓
12. Vote submission
       ↓
13. Close voting
       ↓
14. Results
```

Every step needs:

* correct business behaviour
* correct authorization
* correct lifecycle state
* correct Election-Only eligibility
* no Full Membership leakage
* correct audit/evidence behaviour
* no unexpected 500
* no hidden legacy authority

---

# 13. Gate 6 — Final Election-Only certification

Only after all of that should we say:

> **Election-Only mode works smoothly.**

And certification should include:

### Functional

* [ ] Election creation
* [ ] Election-Only admission
* [ ] voter assignment
* [ ] nomination
* [ ] candidate approval
* [ ] voting activation
* [ ] voter eligibility
* [ ] ballot access
* [ ] vote submission
* [ ] voting closure
* [ ] results

### Constitutional

* [ ] every relevant constitutional precondition enforced
* [ ] no bypass path
* [ ] computed lifecycle cannot violate rules
* [ ] command path cannot violate rules

### DDD

* [ ] authoritative bounded context established
* [ ] no accidental Membership ownership
* [ ] no ambient eligibility authority
* [ ] no duplicate business authority
* [ ] legacy paths identified and fenced

### Verification

* [ ] targeted tests green
* [ ] Election-Only journey tests green
* [ ] relevant regression suite green
* [ ] known non-relevant legacy tests explicitly dispositioned
* [ ] no unresolved Election-Only blocker

---

# The most important thing: we are not "far away"

If I simplify everything, our current position is:

```text
                    ELECTION-ONLY
                         │
          ┌──────────────┴──────────────┐
          │                             │
      WHAT IS DONE                 WHAT IS LEFT
          │                             │
          ▼                             ▼
   EM-VOT-002 CLOSED              BUSINESS DECISIONS
   Architecture archaeology       ├─ EM-OPEN-021
   Independent verification       ├─ eligibility ownership
   Production-path analysis       ├─ eligibility scope
                                  ├─ BR-1.12
                                  └─ 59 + 67
                                         │
                                         ▼
                              AUTHORIZED IMPLEMENTATION
                                         │
                                         ▼
                              ELECTION-ONLY JOURNEY
                                         │
                                         ▼
                                  FINAL CERTIFICATION
```

### My recommended immediate order

**Right now:**

1. **Session 1 → P4-A + P4-B** as already instructed.
2. **Session 2 → continue governance only; no implementation authorization.**
3. **Session 3 → remain STOPPED.**
4. **Session 4 → remain STOPPED after its committed architecture package.**
5. **You/PO → resolve EM-OPEN-021 + eligibility ownership/scope + BR-1.12 + 59/67.**
6. Then we do a **single explicit Election-Only implementation authorization** for Session 3.
7. Then run the **end-to-end Election-Only journey**, not just isolated ticket tests.
8. Finally certify and clean up obsolete/irrelevant tests.

The key architectural principle I would preserve is:

> **Do not make the code "Election-Only ready" by fixing whatever currently fails. First establish the business decision and authority, then implement the smallest DDD-consistent change, then verify the complete Election-Only journey.**

That is the point where the three-session process becomes much simpler: **Session 1 proves behaviour, Session 2 proves authority/governance, Session 3 implements only authorized changes, and Session 4 validates architectural placement.**
