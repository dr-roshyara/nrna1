# D-1 through D-5, in plain language: what they mean and why they matter

**Date:** 2026-09-28. **Audience:** you, as the decision-maker — not a governance record, not
addressed to PO/ARB. Written to explain, not to decide anything new.

---

## The one idea all five decisions are about

KnowledgeOS reads source code (PHP or Python) and builds a picture of how a piece of software
is put together — which parts of a class talk to which other parts. That picture is later
used to compute a quality score (a "cohesion" measurement).

To build that picture honestly, the system constantly has to answer one question about every
single line it reads:

> **"Does this line show a real connection between two parts of the code — and if so, do I
> actually know which two parts?"**

Sometimes the answer is a clean yes. Sometimes it's a clean no. But sometimes the code does
something the system can *see* is happening, without being able to say *exactly what* is
happening. `D-1` through `D-5` are the five places, found by testing the system against real
code, where that harder, in-between case shows up. Each one asks: **when the system can't be
sure, what should it honestly say instead of guessing or staying silent?**

This matters because a system that either guesses or stays silent produces a report that
*looks* confident but is quietly lying in specific spots. The whole point of `D-1`–`D-5` is
to close those spots honestly instead of papering over them.

---

## D-1 — "I can see something is happening, but not what"

### The everyday example

> A support ticket says: `"Forward this to $department"`, where `$department` is a value
> decided at the moment the ticket is created — maybe from a dropdown, a rule, a config file.

Reading the code, you can plainly see: *this ticket gets forwarded to some department.* You
cannot see, just from reading the code, *which* department, because that's decided while the
program is actually running, not fixed in the text of the code itself.

### Why it matters

Before this was fixed, KnowledgeOS's honest answer to "does this code have a dependency here?"
was **silence** — as if the line didn't exist at all. That's the dangerous outcome: silence
looks identical to "there is genuinely nothing here," when the truth is "there is clearly
something here, I just can't name it." Those are two different facts, and collapsing them
into one is exactly the kind of quiet inaccuracy this whole investigation exists to catch.

### What we did about it

We taught the system a third answer, distinct from both "yes, connected" and "no, nothing
here": **"yes, something is referenced here — but I cannot determine what."** That third
answer is recorded explicitly, with a reason, so anyone reading the report later can see it
was *examined and found ambiguous*, not *overlooked*.

### Status today

**Done, and proven.** We implemented it, tested it against real code in both PHP and Python,
and found the actual real-world example this system had been getting wrong: one line in this
project's own admin tooling, and three lines inside the real Python standard library. In every
case, the system now correctly says "something happens here, target unknown" instead of
staying silent. The only thing left is a paperwork step: formally writing this into the
project's official rulebook (`expected.json`) — an administrative decision, not more
investigation.

---

## D-2 — "Touching data whose exact name isn't fixed"

### The everyday example

> Instead of `$this->department`, code writes something like `$this->$fieldName`, where
> `$fieldName` is itself a variable — you're reading or writing *some* field, but which one
> depends on a value decided while the program runs.

This is `D-1`'s twin, but for **reading or writing a piece of data** instead of **calling a
method**. Same shape of problem: the system can see *an* access is happening, but not which
piece of data is touched.

### Why it matters

If this class of construct is genuinely common and load-bearing, ignoring it the same way
`D-1` used to be ignored would be the same silent inaccuracy. If it's rare or accidental,
treating it with the same weight as `D-1` would be over-engineering for a problem that barely
exists.

### Status today

**Deliberately out of scope — a decision, not an oversight.** Investigation found this
construct doesn't occur anywhere meaningful in the real code this project has examined. Rather
than build machinery for a problem with no real evidence behind it, the decision was: **state
plainly that this is not currently handled**, and revisit only if real evidence later shows it
matters. This is the responsible way to *not* build something — named and recorded, not just
silently skipped.

---

## D-3 — "How would we even represent D-2, if we ever needed to?"

### The everyday example

This one isn't a code example — it's a *design* question that only exists because of `D-2`.
If `D-2` (the data-touching version) were ever brought into scope, `D-3` asks: **what would
the internal representation for "touched, but which field is unclear" actually look like?**

### Why it matters

It doesn't, right now — and that's the point worth understanding. Because `D-2` was decided
"out of scope," the follow-up design question `D-3` never needed answering. This is a small
but important piece of discipline: don't design the plumbing for a decision you haven't made
yet. If `D-2` is ever revisited, `D-3` gets revisited with it, not before.

### Status today

**Not applicable**, as a direct consequence of `D-2`'s status. No open item here.

---

## D-4 — "Executing something through a middleman, instead of calling it directly"

### The everyday example

> Most of the time, code directly says: `"Call this person's approve() action."` But
> occasionally code instead says: `"Here is a description of an action to run — hand it to a
> dispatcher, and let the dispatcher actually run it."` In PHP this idiom has a name:
> `call_user_func`. It's the difference between picking up the phone yourself and handing a
> phone number to a receptionist who will dial it for you.

The name of the action being run is usually still written down in plain text — so it's not
quite the same problem as `D-1` (where the name itself is unknown). What's uncertain here is
narrower: **does this indirect hand-off actually reach the action it names, the way a direct
call would?** The system would need to trust that the "receptionist" (the dispatch function)
behaves the way everyone assumes it does — and trusting library behavior, rather than reading
what the code itself says, is exactly the kind of shortcut this whole investigation has been
built to avoid.

### Why it matters

If this idiom turns out to be common in real code, it deserves the same honest treatment as
`D-1`: recorded as "seen, and here's exactly why it's uncertain," not silently ignored. If it
turns out to be vanishingly rare, spending engineering effort on it would be solving a
problem nobody actually has.

### Status today

**Designed, but never confirmed necessary — and today's fresh check found it may not be.**
The vocabulary for representing this idiom was worked out carefully, on paper, over three
weeks of governance discussion. But when we went back and actually counted how often it
appears in real code: **zero genuine occurrences in this project's own codebase, and zero in
the large body of real Python code checked so far.** The one PHP line that looked like a match
turned out, on close inspection, not to actually be this construct at all. This is a live,
still-open research question — not a governance question yet, because there isn't yet enough
real evidence to know whether it's worth writing into the rulebook at all.

---

## D-5 — "What do we call this idiom, so it fits alongside everything else?"

### The everyday example

This isn't a code example either — it's a **naming/vocabulary** question, the same way `D-3`
was a design question for `D-2`. Given that `D-4` exists as a concept, what should the system
internally *call* it, so that it fits cleanly alongside the other categories it already has
(a self-call, a call through a class name, a parent-class call, and so on)?

### Why it matters

Naming might sound cosmetic, but it isn't quite: whatever name is chosen becomes part of the
system's permanent vocabulary, and a name borrowed too directly from one programming language
(say, literally calling it "the `call_user_func` case") would quietly assume every future
language works the way PHP does — which defeats the entire point of building one shared,
language-neutral model in the first place.

### Status today

**A name was proposed** (deliberately generic, not tied to any one language's function name),
**but adoption is on hold for the same reason `D-4` is on hold**: there is no evidence yet
that the underlying idiom is common enough to be worth naming permanently. Naming something
that may not need naming would be solving an imaginary problem.

---

## The one-paragraph summary

`D-1` asked "what happens when the system sees an action but can't say what it targets?" —
**answered, built, and proven correct on real code, in both languages.** `D-2`/`D-3` asked the
same question about touching data instead of calling actions — **deliberately set aside,
because real evidence doesn't support building it yet.** `D-4`/`D-5` ask a narrower, harder
version of the same question, about actions run through a middleman — **designed but not yet
confirmed to be worth building, because the real-world evidence so far says it barely
happens.** The common thread across all five: **never let "I'm not sure" quietly turn into
either "definitely yes" or "definitely no."** That discipline — applied consistently, checked
against real code every time rather than assumed — is what these five decisions actually are.
