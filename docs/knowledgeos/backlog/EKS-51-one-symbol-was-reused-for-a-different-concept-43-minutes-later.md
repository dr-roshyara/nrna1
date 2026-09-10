# EKS-51 — One symbol was re-used for a completely different concept 43 minutes later, and nothing in the process noticed

**Raised:** 2026-09-10 · **Source:** `G-22` disposition
**Evidence:** `docs/knowledgeos/brainstorming/verification/gap-discovery/g-22-omega-sense-inventory/01-OMEGA-SENSE-INVENTORY-231-267.md`
**Status:** OPEN · **Severity:** HIGH · **Class:** modelling discipline / ubiquitous language — **not** a theory defect

---

## The problem, in business terms

A term in the shared vocabulary was given one official meaning at 09:39 and a **completely different
official meaning at 10:22 the same morning**. Both definitions were written deliberately. Neither
mentions the other. Both are still in use.

Two days later a single document used **both meanings, three hundred lines apart, without noticing** —
and that document is one of the load-bearing genealogy records the whole reconstruction leans on.

### What the two meanings are

| when | what the term was declared to mean |
|---|---|
| 2026-08-28 **09:39** | **the set of domain rules** knowledge is derived against |
| 2026-08-28 **10:22** | **the mechanism by which the real world becomes observable** — a function from world to observation |

These are not two shades of one idea. One is a body of rules; the other is a measurement process.
Nothing in the corpus converts one into the other, and no document ever says they are related.

### Why it happened, and why that matters more than the collision

The 10:22 document is the programme's **first re-founding** — the point where the earlier apparatus
was deliberately set aside and the theory restarted on a formal basis. The old meaning belonged to
the apparatus being dropped. **The symbol was carried across the boundary and re-pointed at
something new, while the old meaning went on living in every earlier document.**

That is the general risk: **a re-founding retires the concepts but not the notation.** Anyone reading
across the boundary inherits two meanings for one symbol with no warning at the join.

### What it costs

1. **Silent misreading.** A reader who meets the symbol after the boundary and looks up its
   definition before the boundary gets the wrong object — with no error, no flag, no contradiction.
2. **Load-bearing conclusions inherit the ambiguity.** The genealogy document that carries both
   meanings is the same one used to classify a whole family of transition formulations.
3. **The origin question becomes unanswerable as posed.** Asking *"where did this symbol come
   from?"* has no single answer. Asked that way against the 181-file unread archive, it would
   return five different lineages braided together and read as one.

---

## What makes this ticket worth filing rather than just fixing

**The corpus already knows the rule.** The same archaeology document that carries three of the
meanings states, in a box:

> **"Same word ≠ Same concept."**

It names this a DDD lesson, and applies it correctly to two *other* overloaded terms — cataloguing
three senses of one and two referents of another, and marking both unresolved.

**It simply never turned the instrument on this symbol.** The discipline exists, is written down,
and was not self-applied. That is a process gap, not a knowledge gap — and process gaps recur.

---

## A second, purely mechanical defect found alongside it

The corpus writes this symbol **two different ways** — a Unicode character and a LaTeX command.
A search for one spelling finds **7 %** of the occurrences: 2 of 28, in 1 file of 11.

**Any prior count, any prior "this term does not appear here", and any prior absence claim about a
symbol in this corpus is unreliable unless it searched both spellings.** This is cheap to fix and
expensive to leave: a false zero reads exactly like a real one.

---

## What would close this

| | requirement |
|---|---|
| **1** | **A symbol register.** One entry per symbol per meaning, each with its defining document, its type, and the boundary it belongs to. Not a glossary of terms — a register of *symbol–meaning pairs* |
| **2** | **A re-founding checklist item: which notation is being re-used?** A re-founding that drops an apparatus must state, explicitly, which symbols it is keeping, which it is re-pointing, and which it is retiring |
| **3** | **Every symbol search covers every spelling in use.** Mechanical, one-time, and it invalidates nothing already correct |
| **4** | **Apply the corpus's own rule to the corpus's own notation.** The rule is already written and already ratified in practice for two other terms |

---

## Explicitly NOT being claimed

* **Not** that either definition is wrong. Both are competent and both are useful.
* **Not** that they should be merged, renamed or retired — that is a modelling decision this
  reconstruction does not make.
* **Not** that the theory is unsound. The collision is a **readability and traceability** defect.

## Related

| | |
|---|---|
| `EKS-43` | a Sanskrit word and a core formal operator share one name — **same family**: a homograph across two vocabularies |
| `EKS-49` | a commissioning lane and an executing lane never tell each other — **same failure shape**: the corpus holds the information and no channel carries it to where it is needed |
