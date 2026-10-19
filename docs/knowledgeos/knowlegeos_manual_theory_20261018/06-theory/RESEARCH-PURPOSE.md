# Why we do this research

*Set by the Principal, 2026-10-19. Read it first, every turn. It changes no rule; it gives the work its target.*

**Summary.**
1. **Goal:** the theory of the KnowledgeOS kernel, the minimal, trusted core that applications (AI, robots, products) run on.
2. **Central question (unchanged):** which knowledge is valid, who owns it, and how can it be trusted?
3. **Deliverables:** the primitives, the laws between them, the kernel/application boundary, the open forks, and the kernel's type and existence.
4. **The test for every concept:** kernel or application · minimal · stable · does it answer the central question?
5. **Direction:** the Principal's later brainstorms (EXT-001 … 029) give direction, not evidence. Where they differ from the book, the difference is recorded for the Principal.

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
5. **The kernel's type and its existence** *(added 2026-10-19 from the Principal's direction, EXT-010, EXT-011, EXT-015)*: what kind of object the kernel is (a candidate is a state space, mechanisms, invariants and an interface), and whether a kernel meeting the requirements exists at all. "No coherent KnowledgeOS Kernel can be established under the proposed assumptions" is a permitted answer (EXT-010 L220–224). *The type must include **required capabilities** (what the kernel must do, not only what it must not break), and existence is relative to them: with invariant preservation alone, the empty set of mechanisms is minimal and the existence question is trivially "yes" (review EXT-DIRECTION, finding 1). **Tested on a finite toy model** (`tmp/tools/kernel_model.py`; run `tmp/tools/kernel_model-run-20261019.txt`): as written, the only minimal set is ∅. With required capabilities and preservation written as m(s, x) ∈ 𝒮_I ∪ {⊥}, ∅ fails and the minimal kernels are {record, grant} and {record, grant2}. So a kernel exists in the toy model, and the minimal kernel is **not unique**. This tests the form only; it shows nothing about KnowledgeOS's content. Deliverable 5 stays open. The book section: Ch. 9 §9.1.*

## The test for every concept and every source

- Does it belong to the **kernel** or to an **application**? Mark it.
- Is it **minimal**? A primitive must not be derivable from other primitives.
- Is it **stable**? Would it survive a change of AI model, tool or product?
- Does it answer part of the central question (validity, ownership, trust)?
- *Candidate operational form of the first test (direction, EXT-008 L249–254; KR-06, EXT-010 L338; not yet adopted):* "Can higher-level applications preserve the kernel's defining invariants without this capability?" If yes, probably outside the kernel; if no, a kernel candidate (EXT-008 L252–254). It is three separate questions, which must not be merged (review EXT-DIRECTION, finding 3):
  1. **Is the capability necessary?** Without it, is some required capability or invariant lost? This is the removal test (EXT-008 L268–282). It decides necessity, not placement.
  2. **Must the kernel provide it?** Is it needed to keep the invariants that the kernel itself guarantees?
  3. **Could an application provide it?** This is delegability, tested by **relocation**: move the capability into several independent applications and check the invariants (KR-06).

  A capability is a kernel candidate only if it is necessary (1) and not delegable (3).

## What KnowledgeOS should be, and how (direction from the Principal)

*From 29 brainstorms of 2026-09-23 to 09-27 (`EXT-001` … `EXT-029`, listed in `EXTERNAL-SOURCES.md`), read in full on 2026-10-19. They are **AI replies in the Principal's brainstorms**, partly restating attached documents that are not identified (for example EXT-001 L3, EXT-010 L3, EXT-013 L5), so they are not all the Principal's own statements. They are **direction, not evidence**. They say what is intended. What they **claim to have shown** is marked separately: most "theorems" in them are unproven, and several contain mathematical errors (notes: `manuscript/review/EXT-DIRECTION-20261019/NOTES.md`).*

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
  - every mechanism keeps a valid state valid (EXT-010 L61–75), to be read as m(s, x) ∈ 𝒮_I ∪ {⊥}, with ⊥ meaning no change; as written, every refusal would break it (EXT-011 L241 vs L269);
  - no mechanism is redundant (EXT-010 L81–89); this needs required capabilities to be meaningful (deliverable 5);
  - applications only **request** transitions through the interface (EXT-011 L321–333).
- **Capability is not authority:** an AI may propose, analyse, derive and challenge, never adopt or ratify (EXT-007 L260–278).
- **Gaps:** the gap is the set of requirements a state does not satisfy, relative to an epistemic contract, and satisfaction must be defined per kind of requirement (EXT-013 L32–41, EXT-015 L122–185).
- **Approximation (a hypothesis to test, not a property):** the ideal state is approached by finite approximations whose **convergence is to be tested**, measured as a vector, not a single score: coverage, uncertainty, evidence, structure, provenance, with "Do not assume these five components are sufficient" (EXT-029 L937–984, L1653–1706, L1768). Stability d(K_N, K_2N) → 0 is not correctness, and whether the ideal is identifiable from finite evidence is open (EXT-029 L1666). Unseen knowledge is to be estimated (for example by capture–recapture between **independent** researchers; the estimate is biased when they are not independent) (EXT-029 L1090–1146, L1127).
- **Machine learning proposes, logic tests, evidence validates** (EXT-029 L742–754). *(EXT-029's "deterministic semantic core" (L2240) is a canonical-semantics pipeline; whether it is this kernel is unverified.)*
- **Order of work (a fork inside the direction):** derived top-down from a stated problem, "strictly ordered" (EXT-012 L19, L258); iterative and co-determined (EXT-008 L29–33); or bottom-up, observation → result → hypothesis → model → architecture → normative contract, never reversed (EXT-029 L2073–2093). All agree that implementation and technology wait until the kernel is determined (EXT-008 L524–560).
- **Philosophical traditions** (Hegel, Deleuze, the Rigveda) enter as form, not as content or proof (EXT-009). The Rigveda was withdrawn to metaphor (EXT-025 L298–315), and Deleuze partly (EXT-003). **Hegel's structure was kept** as an unverified axiom, the derivation engine (A6, EXT-011 L614) and fundamental question 5.

**The questions it must answer.**
- **The five fundamental questions** (EXT-015 L401–412; revised in EXT-016 L198–214):
  1. what the kernel manages;
  2. what a state is;
  3. how satisfaction is defined;
  4. whether the kernel exists;
  5. the dialectical structure: "Do the operators $U, D, S, M, I$ form an adjoint string?" (EXT-015 L247).
- **The nine research questions** KR-01 … KR-09 (EXT-010 L302–358).
- **The experimental questions A–H** on convergence, unseen knowledge and the value of probability, graphs and ML (EXT-029 L2121–2166).

**Where the direction meets the current theory** *(named, not resolved)*.

| | Point | Direction | Current theory |
|---|---|---|---|
| **agrees** | kernel ≠ AI; AI as an application | EXT-005, EXT-007, EXT-029 | this purpose; S0167 |
| agrees | knowledge ≠ its representation | EXT-011 L129–139 | Ch. 3 §3.4 |
| agrees | unknown ≠ conflict; conflict kept; kinds of epistemic gap (unobserved; ignorance, weak, conflicting, ambiguous) | EXT-029 L812–821, L1276–1281, L1310–1314 (EXT-001's UNKNOWN(S) says *what* is unknown, not *why*) | Ch. 2 §2.8.8; §5.4's Unknown |
| agrees | gap = unsatisfied requirements, relative to a contract | EXT-013 | Ch. 5 §5.4 |
| agrees | executable ≠ executed; fabricated results are not evidence | EXT-026, EXT-027, EXT-029 (they show it) | Ch. 3 §3.9 C6 |
| **extends** | the kernel's type, invariant preservation, minimality, existence | EXT-010, EXT-011, EXT-015 | not yet in the book |
| extends | an operational boundary test by removal | EXT-008 L249–282 | this purpose's test (prose) |
| extends | coverage ≠ accuracy; unseen-knowledge estimation; convergence | EXT-029 | not in the book |
| extends | proposal and challenge as roles | EXT-028 L20–28 | the book's own review method |
| **differs** | the central question | identifiable, traceable, governable, usable | valid, owned, trusted |
| **conflicts** | what comes first | the managed resource, still open (candidates: object, claim, formulation, inferential commitment) | a list of candidate primitives |
| differs | minimality | no proper subset of mechanisms suffices (needs required capabilities) | a primitive is not derivable from others |
| conflicts | how the kernel is found | the fixed point of a dialectic, then reviewed by a human (EXT-001 L686–711) | derived from corpus evidence and fixed by recorded acts (Ch. 1 A1) |
| conflicts | performative truth | a formulation guarantees satisfaction (EXT-022 L142–150); later made failable (EXT-026 L126–155) and formalization dropped (EXT-025) | evidence discipline; knowledge ≠ assertion |
| differs | collapses: the mechanism | the distinctions are kept as the outcome; collapses are diagnosed and repaired: "Do not prevent collapses." (EXT-001 L634–638: collapses are to be diagnosed, not prevented; L642, L837–839; EXT-002 L353–355); later files return to tests and mechanisms (EXT-008 L415; EXT-010 L105) | non-collapse distinctions kept, by prohibition |
| conflicts (inside the direction) | where the DDD contexts sit | outside the kernel (EXT-008 L423–425) vs supplying 𝒮, M, I, A, "the kernel is the aggregate root" (EXT-010 L110–115; EXT-011 L548–563; EXT-012 L318–330) | the book's bounded contexts (Ch. 6) |

**What this changes in the sections above.** The **goal is unchanged**. Two sections gain an item, marked as direction:
- the deliverables gain item 5;
- the test gains a candidate operational form.

The **central question is kept as it stands**. The direction's problem statement differs from it: "usable" is added, and "valid" and "trusted" are missing. That difference is the Principal's to decide.

**The two formulations compared (Int; for the Principal to decide).**
- **What the purpose's question covers.** "Which knowledge is valid, who owns it, and how can it be trusted?" (S0167 L1577) asks about the **standing** of knowledge: its validity (evidence and correctness), its owner (who may change it), and its trust (why a user may rely on it). In the book's terms it is about evidence, authority and standing (Ch. 1).
- **What the direction's problem covers.** "identifiable, traceable, governable, and usable across independent higher-level applications" (EXT-008 L45) asks about the **conditions under which knowledge can be managed** at all:
  - identity: one thing, distinguishable (I-0);
  - traceability: where it came from and how it changed (provenance, history);
  - governability: changes go through governed acts;
  - usability: applications can consume it.
- **What each leaves out.**
  - The purpose's question has no **identity** or **traceability**. They are presupposed: one cannot ask who owns a thing one cannot identify. It also has nothing on **use** by applications.
  - The direction's problem has no **validity** and no **trust**. A piece of knowledge can be identifiable, traceable, governable and usable, and still be wrong. "Governable" covers part of ownership (who may change it), but not the question whether it is right.
- **Whether they can be combined.** They answer different questions, so they do not compete:
  - the direction's four properties are **preconditions**: what the kernel must keep true of every item;
  - the purpose's three are the **judgements** the kernel lets one make about an item.
  
  A combined form is therefore possible (Hyp): *the kernel keeps knowledge identifiable, traceable, governable and usable, so that its validity, its ownership and its trustworthiness can be decided.* In this form the four properties are candidate invariants or required capabilities (Ch. 9 §9.1), and the three judgements are what they serve. This combination is a proposal, not a finding.

## What this does not change

The method stays as it is: reading, validating, reviewing, labelling, as in Rulebook v1.3. S0167 is read in full when it is reached in order, like every other source.
