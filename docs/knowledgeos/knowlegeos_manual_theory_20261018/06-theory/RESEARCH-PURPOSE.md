# Why we do this research

*Set by the Principal, 2026-10-19. Read it first, every turn. It changes no rule; it gives the work its target.*

## Goal

The theory of the **KnowledgeOS kernel**: the minimal, trusted core for organisational knowledge that every later application (AI agents, robots, products, workflows) runs on.

Orientation in the corpus: S0167 (`docs/knowledgeos/brainstorming/_misc/20260819-224159-linux-analogy-kernel-os-model.md`, the Linux analogy). It is a **direction, not evidence**. It is a single AI-written brainstorm (`authoritative: false`), and it is internally inconsistent: "Process → Knowledge Execution" (L123) against "Process → Knowledge Object" (L1432).

- "The minimal system responsible for creating, identifying, relating, governing, and preserving organizational knowledge objects" (S0167 L1139).
- AI and robots are applications running on top, not the core (S0167 L1048–1051).
- The kernel must stay stable while the AI layer changes (S0167 L937–939).

## The central question of the theory

**Which knowledge is valid, who owns it, and how can it be trusted?** (S0167 L1577)

## What the book must deliver, in the end

1. **The kernel's primitives,** each defined precisely. The starting candidates are:
   - knowledge object and identity;
   - lifecycle;
   - authority;
   - evidence;
   - relationships;
   - policy;
   - history.

   The corpus may add primitives or remove some.
2. **The laws between them:** invariants, derivations, what follows from what, and what is impossible.
3. **The boundary:** what is kernel (stable, minimal, independent of AI, tools and products) and what is application (it changes, and builds on the kernel).
4. **The open forks,** stated precisely enough to decide them.
5. **The kernel's type and its existence** *(added 2026-10-19 from the Principal's direction, EXT-010, EXT-011, EXT-015)*: what kind of object the kernel is (a candidate is a state space, mechanisms, invariants and an interface), and whether a kernel meeting the requirements exists at all. "No coherent kernel can be established" is a permitted answer (EXT-010 L222–224).

## The test for every concept and every source

- Does it belong to the **kernel** or to an **application**? Mark it.
- Is it **minimal**? A primitive must not be derivable from other primitives.
- Is it **stable**? Would it survive a change of AI model, tool or product?
- Does it answer part of the central question (validity, ownership, trust)?
- *Candidate operational form of the first test (direction, EXT-008 L249–254; not yet adopted):* "Can higher-level applications preserve the kernel's defining invariants without this capability? If yes, probably outside the kernel; if no, a kernel candidate", decided by removing the capability and checking the invariants.

## What KnowledgeOS should be, and how (direction from the Principal)

*From 29 brainstorms of 2026-09-23 to 09-27 (`EXT-001` … `EXT-029`, listed in `EXTERNAL-SOURCES.md`), read in full on 2026-10-19. They are **direction, not evidence**. They say what is intended. What they **claim to have shown** is marked separately: most "theorems" in them are unproven, and several contain mathematical errors (notes: `manuscript/review/EXT-DIRECTION-20261019/NOTES.md`).*

**What it should be.**
- **A kernel defined by its problem, boundary and invariants, not by its components** (EXT-008 L25). The problem: "Organizational knowledge must remain identifiable, traceable, governable, and usable across independent higher-level applications." (EXT-008 L45).
- **A constitutional layer** that keeps knowledge dimensions (meaning, evidence, authority, time, lifecycle) separate while allowing controlled change (EXT-001 L3).
- **Not a store and not an oracle:**
  - the ideal is "the most informative epistemic state justified by the available evidence", with unknowns explicit (EXT-029 L614);
  - unknown ≠ false ≠ probability 0.5, and conflict is a state of its own (EXT-029 L1264–1316).
- **Its stance on truth is not settled in the direction itself:**
  - correspondence or alignment (EXT-023);
  - coherence plus pragmatism (EXT-028 L332–364);
  - relational "resonance" (EXT-027).
- **Zero** is the empty gap: every requirement of the epistemic contract is satisfied (EXT-013, EXT-014 L477–482).

**How it should work.**
- **The kernel as a typed tuple** (state space, mechanisms, invariants, interface) (EXT-010 L19–52):
  - mechanisms are partial and may refuse (⊥) (EXT-011 L224–249);
  - every mechanism keeps a valid state valid (EXT-010 L61–75);
  - no mechanism is redundant (EXT-010 L81–89);
  - applications only **request** transitions through the interface (EXT-011 L321–333).
- **Capability is not authority:** an AI may propose, analyse, derive and challenge, never adopt or ratify (EXT-007 L260–278).
- **Gaps:** the gap is the set of requirements a state does not satisfy, relative to an epistemic contract, and satisfaction must be defined per kind of requirement (EXT-013 L32–41, EXT-015 L122–185).
- **Approximation:** the ideal state is approached by finite, convergent approximations, measured as a vector, not a single score: coverage, uncertainty, evidence, structure, provenance (EXT-029 L937–984). Unseen knowledge is to be estimated (for example by capture–recapture between independent researchers) (EXT-029 L1090–1146).
- **Machine learning proposes, logic tests, evidence validates** (EXT-029 L742–754). The deterministic core stays clean (EXT-029 L2240).
- **Order of work:** observation → result → hypothesis → model → architecture → normative contract, never reversed (EXT-029 L2073–2093). Implementation and technology wait until the kernel is determined (EXT-008 L524–560).
- **Philosophical traditions** (Hegel, Deleuze, the Rigveda) enter as lenses and form, not as content or proof (EXT-009, EXT-025 L298–315). The direction formalized them first and then withdrew from that.

**The questions it must answer.**
- **The five fundamental questions** (EXT-015 L401–412; revised in EXT-016 L198–214):
  1. what the kernel manages;
  2. what a state is;
  3. how satisfaction is defined;
  4. whether the kernel exists;
  5. the dialectical structure.
- **The nine research questions** KR-01 … KR-09 (EXT-010 L302–358).
- **The experimental questions A–H** on convergence, unseen knowledge and the value of probability, graphs and ML (EXT-029 L2121–2166).

**Where the direction meets the current theory** *(named, not resolved)*.

| | Point | Direction | Current theory |
|---|---|---|---|
| **agrees** | kernel ≠ AI; AI as an application | EXT-005, EXT-007, EXT-029 | this purpose; S0167 |
| agrees | knowledge ≠ its representation | EXT-011 L129–139 | Ch. 3 §3.4 |
| agrees | kinds of unknown; conflict kept | EXT-001 L654–682, EXT-029 | Ch. 2 §2.8.8 |
| agrees | gap = unsatisfied requirements, relative to a contract | EXT-013 | Ch. 5 §5.4 |
| agrees | executable ≠ executed; fabricated results are not evidence | EXT-026, EXT-027, EXT-029 (they show it) | Ch. 3 §3.9 C6 |
| **extends** | the kernel's type, invariant preservation, minimality, existence | EXT-010, EXT-011, EXT-015 | not yet in the book |
| extends | an operational boundary test by removal | EXT-008 L249–282 | this purpose's test (prose) |
| extends | coverage ≠ accuracy; unseen-knowledge estimation; convergence | EXT-029 | not in the book |
| extends | proposal and challenge as roles | EXT-028 L20–28 | the book's own review method |
| **conflicts** | the central question | identifiable, traceable, governable, usable | valid, owned, trusted |
| conflicts | what comes first | the managed resource, still open (candidates: object, claim, formulation, inferential commitment) | a list of candidate primitives |
| conflicts | minimality | no proper subset of mechanisms suffices | a primitive is not derivable from others |
| conflicts | how the kernel is found | the fixed point of a dialectic, then reviewed by a human (EXT-001 L686–711) | derived from corpus evidence and fixed by recorded acts (Ch. 1 A1) |
| conflicts | performative truth | a formulation guarantees satisfaction (EXT-022 L142–150) | evidence discipline; knowledge ≠ assertion |
| conflicts | collapses | "do not prevent, diagnose and sublate" (EXT-001 L636) | non-collapse distinctions kept |

**What this changes in the sections above.** The **goal is unchanged**. Two sections gain an item, marked as direction:
- the deliverables gain item 5;
- the test gains a candidate operational form.

The **central question is kept as it stands**. The direction's problem statement differs from it: "usable" is added, and "valid" and "trusted" are missing. That difference is the Principal's to decide.

## What this does not change

The method stays as it is: reading, validating, reviewing, labelling, as in Rulebook v1.3. S0167 is read in full when it is reached in order, like every other source.
