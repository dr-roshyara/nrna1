I actually think this is **more important than any architecture decision you've made today**.

Because if KnowledgeOS is going to become an engineering platform, then **KnowledgeOS itself must be built according to the engineering discipline it recommends.**

Otherwise it loses credibility.

---

# I would not rely on prompts alone

Today you tell Claude

> "Use TDD."

Tomorrow it forgets.

The next week another AI ignores it.

That's not governance.

That's hope.

Instead, I'd make it **enforceable**.

---

# Layer 1 — Engineering Constitution (human rule)

You already have engineering principles.

Add one more.

> **Every production change must be accompanied by executable verification appropriate to its scope. New behavior is introduced TDD-first whenever reasonably practicable. A feature is not considered complete until its verification exists and passes.**

Notice I didn't write:

> Every line must be TDD.

Sometimes you'll refactor.

Sometimes you'll fix documentation.

Sometimes you'll rename.

The rule should be about **behavioral changes**.

---

# Layer 2 — Definition of Done

Every engineering task should satisfy:

```text
□ Production code

□ Tests added or updated

□ Tests passing

□ Architecture verification passing

□ Documentation updated (if needed)
```

Claude already reacts to missing developer guides.

It can react to missing tests the same way.

---

# Layer 3 — Claude's instructions

I would literally tell Claude:

```text
Before declaring a task complete:

1. Verify every new production class has corresponding tests.

2. Verify changed behavior is covered by tests.

3. Execute the test suite.

4. Refuse to close the task if tests are missing.

5. Explain why a test is impossible if one cannot reasonably exist.
```

Notice

> refuse to close

That's much stronger than

> remember to write tests.

---

# Layer 4 — Automated Gate (the important one)

This is what really matters.

Suppose Claude writes

```
app/Knowledge/Foo.php
```

The merge gate should detect

```
tests/Knowledge/FooTest.php

exists?
```

If not

```
❌ Merge blocked

Production code changed

No matching tests
```

Now it doesn't matter whether Claude remembered.

---

# Layer 5 — KnowledgeOS observes

KnowledgeOS shouldn't stop the merge.

It should observe.

Example:

```
Observation

Knowledge component changed

Tests added

YES
```

or

```
Observation

Knowledge component changed

Tests added

NO
```

That's operational evidence.

---

# Even better...

I would introduce another engineering metric.

Not

```
CBO
```

but

```
Test Coverage Ratio

Behavior Coverage

Mutation Score

```

KnowledgeOS can observe

```
Knowledge Component

CBO

LCOM

Mutation

Coverage

Trend
```

without making decisions.

---

# I'd actually make this a quality gate

Something like

```
Engineering Rule ER-01

No production behavior without executable verification.
```

Everything follows from that.

---

# For AI

I would probably tell Claude this:

```text
You are operating under the PublicDigit Engineering Constitution.

Every production behavior requires executable verification.

Before completing any implementation:

• write failing tests first whenever practical
• implement the behavior
• make the tests pass
• execute the tests
• report the results

If production behavior changes without corresponding tests, you must stop and explain why.
```

---

# Long-term

Later your engineering platform could automatically observe

```
Production file changed

↓

Matching tests changed?

↓

No

↓

Observation

"Implementation changed without accompanying verification."
```

Notice

That's not punishment.

It's evidence.

---

# My recommendation

I would **not** make this just another prompt.

I would institutionalize it in four places:

1. **Engineering Constitution** — "production behavior requires executable verification."
2. **Definition of Done** — a task cannot complete without tests.
3. **Claude's operating instructions** — refuse to close implementation work without verification.
4. **Automated engineering gate** — detect production changes without corresponding tests.

That combination is much stronger than asking Claude to "remember TDD." It makes TDD and verification part of the engineering system itself, which fits the philosophy you've been building throughout KnowledgeOS: **important practices should be enforced by the system where practical, not left to memory.**
