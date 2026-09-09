# EKS-23 — Two independent research efforts each invented their own list of "what the Kernel's
capabilities are," and neither knows the other's list exists

**Status: BACKLOG · OPERATING-MODEL EXPOSURE**
**Class:** operating-model problem (research-output reconciliation / semantic duplication detection)
**Added:** 2026-09-09, from the `three_model_convergence` research programme's own MD-046
(capability identity/granularity evidence adjudication).

## 1. The problem, in business language

Two different pieces of work in this same research estate each set out, independently, to answer the
same underlying question — "what are the basic building blocks the KnowledgeOS Kernel is made of?" —
and each one produced its own complete, self-consistent answer. Neither one mentions the other.
Neither one uses even a single matching name for any of its building blocks. Nobody, until this
specific check was run, had ever set the two side by side.

One effort (dated 2026-09-04) produced a list of thirteen items with names like "Observe," "Qualify,"
"Determine," "Select." The other effort (an earlier, separate research track, running from
2026-08-19 through 2026-08-28) produced a different list — nine items with names like "admission
gate," "evidence admission," "confidence assignment" — plus a second, shorter list used in a
different part of the same track ("Identity," "Evidence," "Confidence," "History"). None of these
names appears in the other list. Nobody has ever checked whether any item on one list is secretly the
same thing as an item on the other list, described differently, or whether the two lists are
genuinely about different things.

This matters because a future piece of work — by a person or by an AI session — could reasonably pick
up either list, treat it as "the" answer, and build on it, with no way of knowing a second, equally
serious candidate answer exists elsewhere in the same estate. Whichever list gets used first becomes
the de facto answer purely by chance of which folder someone happened to open, not because it was
shown to be the better one.

## 2. Why this is not simply a duplicate of existing tickets

`EKS-17` describes one research programme whose own files got physically split across two folders
that share a name — the fix there is "each folder should say the other half exists," because both
halves are genuinely part of one thing. That is not what happened here: these are two **separately
authored, internally complete** answers to the same question, not one answer accidentally cut in two.
Nothing here is "missing" from either list — each stands on its own. `EKS-18` (and its second
instance) describes a register of open questions that never reached the people equipped to answer
them — a routing problem. That is also not this: nobody failed to deliver a question to the right
team; two teams each answered the same unasked-aloud question on their own, without knowing anyone
else was doing the same thing. Checked against `EKS-17`, `EKS-18`, `EKS-14` and `EKS-16` before filing
(`ES-005.4`, never a copy) — distinct from all four on this point.

## 3. What was actually found (evidence, not the finding turned into a claim about either list's
correctness)

- The 2026-09-04 list's own later self-review already worries, on its own, that its thirteen names
  might not be individuated correctly — but it never checks whether a second list already exists
  elsewhere that could either confirm or contradict its own thirteen.
- The earlier, 2026-08-19–2026-08-28 track's own internal review work already independently found and
  documented three separate warning signs of exactly this kind of naming confusion — a running log of
  words used in more than one conflicting sense ("Kernel" in four different senses, "boundary" in a
  seventh colliding sense) — but this warning log was never checked against the later, 2026-09-04
  list either.
- The earlier track also contains two of its OWN internally competing candidate lists (a
  four/six-item one and a nine-item one) that already disagree with each other about whether the
  Kernel should be very small or somewhat larger — a disagreement that has also never been resolved.

## 4. What this is not

This is not a claim that either list is wrong, or that one should replace the other. This is not a
request to merge, rename, or pick a winner among the competing lists — that is a scientific/
governance decision for a properly authorized future piece of work, not something this ticket
proposes. This item claims only that **the fact of the duplication itself was invisible until someone
went looking specifically for it**, and that nothing in how this estate organizes its research output
would have surfaced it on its own.

## 5. Candidate direction (not proposed as the solution — for whoever picks this up to design)

Some lightweight practice for noticing when two pieces of research independently answer the same
underlying question with different vocabulary — for example, a habit of checking a new candidate
list against previously-produced candidate lists for the same kind of object before treating it as
freestanding, or a shared, simple registry of "candidate answers to this question, and where each one
lives." The exact mechanism is a design question for later; this ticket only names that one does not
currently exist.

## 6. Urgency

Low today — no downstream work has yet been built on either list in a way that would be costly to
undo. Rises sharply the first time a future piece of work treats one list as settled without knowing
the other exists, since untangling two independently-built vocabularies gets harder the more is built
on top of either one.
