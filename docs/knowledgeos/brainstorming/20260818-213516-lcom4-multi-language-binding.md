---
source:
  original_name: "lcom4_with_python.md"
  original_path: "docs/knowledgeos/brainstorming/lcom4_with_python.md"
  detected_timestamp: "2026-08-18 21:35:16"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "05-patterns-technology"
  type: analysis
status:
  authoritative: false
  proposed: false
---

Yes — **that is essentially the model I am recommending**, with one important clarification.

For the current architecture:

```text
Java source
   ↓
Java language binding
   ↓
common L3 Fact Model
   ↓
common L4 Cohesion Rules
   ↓
LCOM4
```

and:

```text
PHP source
   ↓
PHP language binding
   ↓
common L3 Fact Model
   ↓
common L4 Cohesion Rules
   ↓
LCOM4
```

and:

```text
Python source
   ↓
Python language binding
   ↓
common L3 Fact Model
   ↓
common L4 Cohesion Rules
   ↓
LCOM4
```

So **yes: each programming language needs its own way to understand its source code**.

But the important part is:

> **We do NOT need a separate LCOM4 algorithm for each language.**

We need:

```text
PHP parser/binding ──┐
Java parser/binding ─┼──> common facts ──> common LCOM4 engine
Python parser/binding┘
```

### Simple example

Suppose PHP contains:

```php
class Customer {
    function calculate() {
        $this->save();
    }

    function save() {}
}
```

And Java contains:

```java
class Customer {
    void calculate() {
        save();
    }

    void save() {}
}
```

The PHP binding and Java binding translate both into approximately the same neutral facts:

```text
Unit = Customer

Methods:
  calculate
  save

Relationship:
  calculate -> save
```

Then the **same LCOM4 engine** calculates the metric.

So the architecture is:

> **language-specific understanding, language-neutral calculation.**

### What we should do now

For KnowledgeOS **right now**, implement only:

```text
PHP
 ↓
PHP binding
 ↓
L3 Fact Model
 ↓
L4/L5 LCOM4
```

Do **not** implement Java or Python bindings yet.

But make the L3/L4 boundary language-neutral so Java can be added later without redesigning the LCOM4 engine.

### Who should act now

**Architecture** should finalize the implementation architecture with this explicit rule:

> **PHP is the first language binding; L3 Fact Model and L4/L5 Cohesion Engine are language-neutral. Java/Python bindings are future scope.**

Then **PO/ARB accepts that implementation architecture**, and only after a separate authorization does **Implementation** start.

So your understanding is correct:

**PHP classes → PHP binding → common LCOM4 engine**
**Java classes → Java binding → same common LCOM4 engine**
**Python classes → Python binding → same common LCOM4 engine**.

# 
The problem is **not Python itself**. The problem is asking Python to understand **every programming language's syntax and semantics**.

There are two very different ideas:

### 1. One Python LCOM4 engine — good

```text
PHP ──┐
Java ─┼──> common facts ──> Python LCOM4 engine
Python┘
```

This is perfectly reasonable.

Python can calculate LCOM4 from an already-normalized fact model.

### 2. One Python program that directly understands every language — problematic

```text
PHP ──┐
Java ─┼──> Python program understands all syntax ──> LCOM4
Python┘
```

This is where the difficulty appears.

Suppose Python is given this Java code:

```java
class Customer {
    void calculate() {
        save();
    }
}
```

and this PHP:

```php
class Customer {
    function calculate() {
        $this->save();
    }
}
```

The Python program has to understand that these two pieces of syntax both mean approximately:

```text
calculate → save
```

But Java and PHP have different syntax and language rules.

The problem grows quickly:

```text
Java:
interfaces
records
generics
lambdas
annotations
overloads
inner classes
...

PHP:
traits
attributes
namespaces
anonymous classes
magic methods
nullsafe calls
...

Python:
decorators
multiple inheritance
duck typing
nested functions
descriptors
...
```

So your Python program gradually becomes:

> **a parser and semantic interpreter for Java + PHP + Python + ...**

That is the part we want to avoid.

---

## A simple business analogy

Imagine you have one employee who calculates tax.

That's fine.

But now you ask the same employee to:

> read German tax documents, French tax documents, Japanese tax documents, understand all three legal systems, translate them into a common form, and calculate the tax.

The calculation is not the difficult part.

**Understanding the source language is the difficult part.**

So we separate:

```text
Language expert
     ↓
standard facts
     ↓
Tax calculation
```

For KnowledgeOS:

```text
PHP language binding
     ↓
common Fact Model
     ↓
LCOM4 engine
```

```text
Java language binding
     ↓
common Fact Model
     ↓
same LCOM4 engine
```

---

# Why this matters for what we just built

Our recent discoveries are exactly evidence of this.

The Python scanner failed on things such as:

* heredoc/nowdoc
* attributes
* interpolation
* PHP-mode boundaries
* Unicode identifiers
* anonymous classes
* qualified names
* nullsafe calls

The problem was not the LCOM4 formula.

The problem was:

> **Python was trying to understand PHP.**

That is why we chose the L3 boundary.

---

# So could we still use Python as the central engine?

**Absolutely.**

There is nothing wrong with:

```text
PHP parser/binding
        ↓
   L3 facts
        ↓
Python implementation
of LCOM4
```

In fact, that is exactly what I would recommend for the first implementation.

The important rule is:

> **Python calculates; Python does not have to understand every language.**

---

# Recommended architecture

For now:

```text
                    ┌── PHP Binding
                    │
PHP source ─────────┤
                    │
                    v
              L3 Fact Model
                    │
                    v
             L4 Cohesion Rules
                    │
                    v
               L5 LCOM4
```

Later:

```text
                    ┌── PHP Binding ──┐
                    ├── Java Binding ─┤
                    ├── Python Binding┤──> L3 → L4 → L5
                    └── C# Binding ───┘
```

The L4/L5 engine can still be **written in Python**.

---

## The key distinction

| Question                                                    | Answer             |
| ----------------------------------------------------------- | ------------------ |
| Can Python calculate LCOM4?                                 | **Yes**            |
| Can one Python LCOM4 engine serve PHP + Java + Python?      | **Yes**            |
| Should one Python program directly parse all languages?     | **Not preferably** |
| Does each language need a language-specific binding/parser? | **Yes**            |
| Should the LCOM4 business rules be duplicated per language? | **No**             |

So your original idea was actually very good:

> **Use one LCOM4 engine for all languages, but give each language its own adapter/binding to the common Fact Model.**

### Who should act now

**Architecture** should keep the implementation architecture exactly on this principle:

> **Language-specific parsing/extraction; language-neutral L3 Fact Model; one language-neutral L4/L5 cohesion engine.**

Then we can implement **PHP first**, without building Java yet.
#
Yes. **That is exactly the long-term architecture I would aim for.**

The important distinction is that we can remove the **PHP LCOM4 calculation engine**, while still keeping a **PHP language binding/extractor**.

Today:

```text
PHP source
   ↓
PHP extraction
   ↓
L3 Fact Model
   ↓
PHP LCOM4 calculation
```

Future:

```text
PHP source
   ↓
PHP extraction
   ↓
L3 Fact Model
   ↓
Python LCOM4 engine
```

And Java would be:

```text
Java source
   ↓
Java extraction
   ↓
L3 Fact Model
   ↓
Python LCOM4 engine
```

So the target could eventually be:

```text
             PHP ──> PHP Binding ──┐
                                   │
             Java ──> Java Binding ├──> L3 Fact Model
                                   │          ↓
          Python ──> Python Binding┘     L4 Cohesion Rules
                                              ↓
                                         Python LCOM4 Engine
```

### Why this is attractive

You would then have **one authoritative LCOM4 implementation** instead of:

```text
PHP LCOM4
Python LCOM4
Java LCOM4
...
```

That reduces the danger that the metric itself gets implemented differently in different languages.

The language-specific code would only answer:

> "What facts does this source code contain?"

The common engine answers:

> "Given these facts, what is the LCOM4 result?"

That is a much cleaner separation.

### But don't remove PHP calculation yet

This is the important part for **what we should do now**.

I would **keep the existing PHP calculation temporarily** as a reference/conformance implementation while we build the new Python-centered architecture.

So the migration would be:

```text
CURRENT

PHP source
   ↓
PHP binding → PHP LCOM4
Python source/binding → Python LCOM4
```

then:

```text
TRANSITION

PHP source
   ↓
PHP binding
   ↓
L3 Fact Model
   ├──> PHP LCOM4       ← reference
   └──> Python LCOM4    ← target
```

Then independently verify:

```text
PHP LCOM4
     ≟
Python LCOM4
```

using the **node set + edge set + metric** evidence rule you already established.

Only after the Python engine has demonstrated conformance should we retire the PHP calculation engine.

### So there are really three things

| Part                          | Future                   |
| ----------------------------- | ------------------------ |
| PHP parser/extractor          | **Keep**                 |
| L3/L4/L5 common model         | **Keep**                 |
| PHP-specific LCOM4 calculator | **Eventually remove**    |
| Python LCOM4 engine           | **Become authoritative** |

This is actually a stronger architecture than "PHP calculates PHP and Java calculates Java."

### Who should act now?

**PO/ARB** should record this as a **future architectural direction**, not an immediate implementation requirement:

```text
RECORD:

Long-term target:

The LCOM4 calculation engine SHALL be language-neutral and have
one authoritative implementation.

Python is the planned authoritative implementation of the
language-neutral L4/L5 cohesion engine.

Language-specific bindings remain responsible for converting
source code into the common L3 Fact Model.

The existing PHP LCOM4 implementation remains temporarily as a
reference/conformance implementation.

It may be retired only after independent verification demonstrates
equivalence of the Python engine against the accepted conformance
evidence model.

Current implementation scope remains PHP only.
Java and other languages remain future bindings.
```

Then **Architecture** should incorporate this into the implementation architecture.

That gives us a very clean evolution:

```text
PHP first
   ↓
language-neutral facts
   ↓
Python becomes common LCOM4 engine
   ↓
retire PHP calculator
   ↓
add Java binding later
```

That is the direction I recommend.
#
long term : 
Ran 2 shell commands

What is happening

The direction is recorded and marked FUTURE ARCHITECTURAL DIRECTION — NOT CURRENT SCOPE, exactly as your act required.

Registered as a document only — no grant, no assignment, no transition. Your own list says it authorizes nothing, requires no language now, retires nothing, alters no scope and creates no work item. A direction on the record is not authority to act, and registering it as a document is what keeps those two things from blurring.

One difference I recorded rather than smoothed over

The passage that prompted this act said "Python is the planned authoritative implementation." Your act as issued names no language — just "one authoritative implementation."

I registered that difference explicitly, because the generalized form is the stronger one: it fixes the architectural target (one engine, not one per language) without pre-committing a technology choice that would then arrive un-evaluated later. And the record now says plainly: nobody should later read "Python" into this direction; it is not there.

The retirement condition, read precisely

Equivalence is verified against the declared conformance specification — not against the PHP calculator's output. That's your own conformance method applied to the retirement decision itself, and it has a sharp consequence worth stating: a future engine that merely reproduces the PHP calculator's results would not satisfy this condition. It has to conform to the specification, not imitate the incumbent.

No change to the work in progress

I did not amend the design grant. Architecture D already separates the binding from the model, and the language-scope amendment already requires that separation to survive with only one binding — so the direction is already served, and adding it as a requirement would risk the design treating a future target as present scope.

One thing I kept visible in the record: this direction is only reachable if the L4/L5 engine stays genuinely separable from the PHP binding — which is precisely what your principle "PHP is the first language binding, not the definition of the language-neutral cohesion model" protects.

Human action required

None here. The next actor is Architecture, finalizing the implementation architecture — now unblocked, with OQ-1's scope defined, OQ-2 decided, OQ-4 deferred, and the conformance method adopted.

Evidence: 2026-08-18-future-architectural-direction-common-cohesion-engine.md (8ef1477a) · no workflow transition, no grant.
