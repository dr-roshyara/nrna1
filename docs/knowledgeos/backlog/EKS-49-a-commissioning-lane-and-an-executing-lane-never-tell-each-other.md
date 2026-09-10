# EKS-49 — A commissioning lane and an executing lane are doing the same work and never tell each other

**Raised:** 2026-09-10 · **Source:** `G-19` disposition
**Evidence:** `docs/knowledgeos/brainstorming/verification/gap-discovery/g-19-falsification-lifecycle/01-G-19-FALSIFICATION-LIFECYCLE.md`
**Status:** OPEN · **Severity:** HIGH · **Class:** process / knowledge-flow — **not** a theory defect

---

## The problem, in business terms

Two teams inside the same programme are working on the same question on the same afternoon, and
**neither team can see the other's output**. One team writes the work orders. The other team does
the work. There is no channel between them.

The consequence is not that work is missed. It is worse and less visible: **work gets done, gets a
real answer, and the answer never reaches the people who asked the question** — who then record
that the question is still open, and commission it again.

### What actually happened on 2026-08-30

At **19:59** the theory team issued a work order: *"attack our central claim — the definition of a
knowledge state. Find one counterexample that breaks it."* The order named no owner, no filename
and no deliverable list; it named only a step number.

Between **20:47 and 21:13** — inside 74 minutes — three separate pieces of work were completed that
did exactly that. They were mechanical, they produced files, and two of their three findings still
stand today:

* one found the definition **under-specified and internally contradictory**;
* one found a **constructed counterexample** showing the relationship model cannot record whether a
  contradiction claim is itself evidenced, dated or contestable — which for a governed-knowledge
  system is a capability failure, not a nitpick;
* one found the claim **conditional on an assumption never written down** *(this third finding was
  later withdrawn by its own authors — the calculation was right, the inference was not)*.

**None of the three knew a work order existed.** They arrived under separate mandates.

At **21:44** the theory team re-issued the same work order, now with **twelve named deliverables**.
None of the twelve was ever produced.

At **22:01** — 74 minutes after the first finding landed — the theory team's own status table
recorded the question as **"Not yet tested · OPEN"**, and deferred it to the next step. That next
step did something else entirely.

### Why this is expensive

1. **Answers are paid for twice and banked zero times.** The programme funded the work, got the
   result, and then recorded that it had no result.
2. **The most recent status is the least accurate one.** Anyone reading the corpus in step order
   arrives at *"not tested"* — the newest of three incompatible statuses, and the only one that is
   false.
3. **A live defect stays live.** *"The relationship model is the wrong shape"* is a **repairable,
   specific, actionable** finding. It has been sitting unrouted since 2026-08-30 because the team
   that would act on it never received it.
4. **Re-commissioning burns the same budget again.** Twelve deliverables were ordered for work
   already done.

---

## What is NOT being claimed

* **Not** that the theory is wrong. The half of the claim the work order actually stated —
  *"no component can be removed without losing a required capability"* — **was tested and it held.**
* **Not** that anyone was negligent. Both lanes did competent work; the defect is in the absence of
  a channel, not in the work.
* **Not** a proposal to merge the lanes. Their separation is deliberate and valuable — an
  independent attacker who has read the work order is no longer independent.

---

## What would close this

| | requirement |
|---|---|
| **1** | **Every work order names a deliverable and an owner.** A work order that names only a step number cannot be discharged, cannot be tracked, and cannot be found later. Three of three in this episode named only a step number |
| **2** | **A status claim must cite the artifact it rests on.** *"Not yet tested"* with no citation is unfalsifiable and, in this case, wrong |
| **3** | **A completed result is routed to the open work orders it answers** — by what it *contains*, not by what it is *called*. All three results here were discoverable in a single search once the question was asked capability-first |
| **4** | **Before re-issuing a work order, search for its answer.** Twelve deliverables were ordered for work completed 39 minutes earlier |

---

## Related, and deliberately kept separate

| ticket | what it records | relation |
|---|---|---|
| `EKS-46` | a qualified result becomes unqualified three minutes later — a **dropped** qualification | **distinct.** `G-19` finds a **substituted** qualification (`Minimality(K\|𝒯)` → *"representation-minimality"*), which is a different failure mode |
| `G-18`, `G-14` | citation and source out of step by 3 min and −58 min | **same family, opposite direction.** `G-19` is +74 min. Three instances, one day, two lanes — recorded as a scope, **not promoted** to a corpus law |

## ⚠️ Filing defect noticed while writing this ticket

**Two different tickets both occupy the ID `EKS-46`**:
`EKS-46-a-qualified-result-becomes-an-unqualified-one-three-minutes-later.md` and
`EKS-46-a-third-independent-classification-governance-pipeline-was-found-unintegrated.md`.
An ID that identifies two things identifies neither. Recorded here rather than renumbered, because
renumbering someone else's ticket is not this commission's call.
