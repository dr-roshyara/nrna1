# The T9-02 theory turn (the state space): the research question and its disproof conditions

*Written 2026-11-02 by the Professor, under the Principal's commission `03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md` (ISSUED, reviewers (b)) and the Principal's start instruction of 2026-11-02 ("Then do not start C98: start the T9-02 theory turn … Reading stays at S0366 until the turn is settled"). Rulebook v1.4 §12b: the research question and its disproof conditions first.*

*Process disclosure.*
- Before writing this file, the Professor read the anchors the commission names for Step 1: Ch. 9 `TODO.md` T9-01–T9-04; THEORY-MAP F13, F13a, F37; §9.1 (whole); §9.10 L13 and L201; §9.19 L20–80 and L150–240; §9.13 L1–80 (the domain D); §9.18.2; §3.1 L10–85; §1.3 L20–30; RESEARCH-PURPOSE deliverables 1 and 5.
- **No model has been written or run, and no section has been drafted.** This file is committed before either. (The Req/A turn ran a model first; that order is not repeated, as Part 2.2 of the commission requires.)
- **Sources:** none from the corpus is planned. This is a book-only turn, as the Req/A turn was. If one is needed, it is opened under Part 2.2 (at most 8, logged, with predictions recorded first), and this file is amended before it is used.

## 1. The precise unresolved question (Step 1, as T9-02 states it)

T9-02 (TODO L12) names "the state space 𝒮" as undefined: "The test used a three-part toy state. The kernel's real attributes are not fixed (F13)". Its deliverable is "one table of attributes, value sets and kinds". F13 (THEORY-MAP L134) adds that Ch. 1's labels and Ch. 3's six dimensions are two attribute systems never mapped onto each other, and that each attribute needs a bearer (F37, a fork the Principal keeps open, DB-5).

**The research question of this turn:**

> **Which distinctions must the kernel's state 𝒮 carry so that every decided rule and every core requirement of Req can be evaluated, and no others? For each attribute that F13 names, is it a component of 𝒮, a recorded field of an entry kind, a value derived from the state (an observer), an input the kernel does not hold, or outside the kernel; and what does the evidence leave undecided?**

It is answered relative to (A, Req) as the Req/A turn fixed them (§9.19.4; U3 (b): granularity and batching unfixed), and relative to the decisions in force (C1 = constitute; D and its kinds, §9.13; W's three kinds, T9-19; U2 (a); T10-01 (b); T9-17 (b); T9-20; T10-21; Q-C).

## 2. Candidate formulations, stated before any test (not a preference)

- **R (record):** 𝒮 is the set of designated records: finite sequences of entries of the admitted kinds (§9.13.2 and §9.18.4), each with its required fields, in record order. Every other attribute is derived or an input.
- **V (attribute vector):** 𝒮 assigns to each item a vector of attribute values (Ch. 1's labels, or Ch. 3's dimensions), as §9.1 C4's toy state (claims, acts, standing) does.
- **R+C (record with a store of derived values):** R, plus stored copies of values the observers compute (for example standing or the class).
- **Q (a quotient of R):** the records modulo an equivalence that identifies records no requirement can tell apart.

None of these is assumed. Each is tested in the five-question form of Part 1, Step 2, per distinction.

## 3. What would disprove each answer (committed now)

- **DC-1 (against R's sufficiency).** A decided rule, a guard, or an observer of Req's core that needs information that is not in the record and is not one of the stated inputs (θ, covers, the identity link, the rule version g, the purpose; §9.18.2; §9.19 L166) or a world assumption (§9.13 L81).
- **DC-2 (against R's minimality, not its sufficiency).** Two records that differ and that every observer of Req's core, at every cut and after every extension, and every guard, treats alike. If found, the claim must be weakened from "the record" to a quotient (Q), and the distinction that differs is not required.
- **DC-3 (against V).** If an attribute vector over F13's attributes is shown sufficient: no two records with the same vector diverge under any observer of Req's core, at any cut, or after the same appended entry.
- **DC-4 (against R+C).** If a stored derived value is needed by a decided rule that cannot compute it from R and the stated inputs (then it is not derived).
- **DC-5 (against the classification).** An F13 attribute whose class cannot be assigned from the book's text, or to which two sections assign different classes. Either is reported as open, not resolved.
- **DC-6 (scope).** Any step that uses an open fork (F13 beyond what is assigned here, F37, F6, T9-14, G7's F13 part, T0-07, U3's granularity) as if it were decided. Any such use voids the step.
- **DC-7 (the trivial case).** If the result also holds for an empty Req, or for a one-point 𝒮, it says nothing about KnowledgeOS. The test: a state space that drops a required distinction must make some required, non-default answer unreachable or wrong (the corrected F-min of §9.19.8).

## 4. What the turn may conclude (Step 4's outcomes)

Resolved, partially resolved, or unresolved, as Part 1 defines them, with every claim labelled Src · Ded · Int · Hyp · Ext · EA. **Kernel existence does not establish primitive minimality** (T9-03 kept apart), and no TODO row is closed by implication (Part 1, Step 5).

## 5. What a model may and may not do here

A finite model, if one is used, tests the **form** (for example that two records with one attribute vector diverge), states its model, premises, bound and limits, and proves no general claim (Part 2.2). The general claims are deductions from the book's definitions, with proof-step tables.
