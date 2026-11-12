# PublicDigit product principles

| | |
|---|---|
| **Status** | **ADOPTED — RATIFIED by the Principal as D-218 (2026-11-12), including the guarantees note in principle 0.** Revision 2 applies the Principal DDD review's five refinements |
| **What it is** | **A guide for designing features**: what PublicDigit offers, makes easy and makes visible. **Not a source of authority, and not a rule PublicDigit enforces on any organisation** (see principle 0) |
| **Inspiration** | Antonio Gramsci, as the Principal reads him:<br>- **The party as a catalyst of society** (the Principal's image; Gramsci's own terms are the "Modern Prince" and the party as a *collective intellectual*). It educates, forms *organic intellectuals*, raises ideas from below, and builds **hegemony through consent**, not domination.<br>- **The danger:** a party that believes history **guarantees** its success, and that it holds that guarantee, slides into **bureaucratic centralism**: a closed leadership and decisions handed down (our gloss: dissent treated as disloyalty).<br>- **Gramsci's answer: democratic centralism** as a centralism "in movement", continually adapting to real life and renewing its leadership from, and answerable to, the membership. *(Not Bordiga's "organic centralism", which Gramsci opposed.)* |
| **Relationship to decisions** | It sits beside the Strategic DDD Baseline v1.1 (D-214) and the core-domain refinement (D-217). **The decision record governs where they differ** |

## 0. The governing limit: PublicDigit is a catalyst, never the director

**Each organisation governs itself under its own written rules** (D-1a, D-97). **PublicDigit never decides for an organisation and never fills a gap with its own choice of decision-maker:** silence means the act waits (D-167).

**So these principles shape what the product offers, defaults to and makes visible. They are never imposed:**
- an organisation with a highly centralised statute is served faithfully under that statute;
- **a principle may justify a feature, a default or a display. It never justifies overriding an organisation's rules;**
- every default that follows from a principle is **labelled as a platform default and can be overridden** by the organisation's written rules (D-167, D-168, D-171).

**The principles and their design tests are product-design criteria, not additional organisational rules, constitutional requirements, or universal acceptance conditions. A design test prompts an explicit design assessment; it does not authorise PublicDigit to override an organisation's rules.** Nor does any principle create authority to act on an organisation's behalf.

**Precedence:**
1. Applicable law.
2. The organisation's own governing documents and rules, within the applicable legal framework.
3. Accepted architecture and domain decisions governing the platform's implementation.
4. These product principles, as design guidance.

**How levels 2 and 3 meet (ratified with D-218):**
- **The organisation's rules decide the organisation's affairs:** who is competent, what is decided, which defaults are displaced (D-167, D-168).
- **The platform's protective invariants decide how PublicDigit itself behaves**, for example:
  - vote secrecy;
  - isolation between organisations;
  - append-only history;
  - refusal when an answer cannot be established.
- **Where an organisation's rule would require PublicDigit to breach one of those invariants, PublicDigit does not provide that function. It never breaches the invariant.** For example, a rule demanding a voter-to-ballot list is not served.
- This does not let PublicDigit decide the organisation's affairs: the organisation remains free to act outside the platform.

**The platform must not become the autocrat it warns against.**

## 1. Authority comes from the members' rules, not from position

- Every act that needs authority is checked against the organisation's own rules and designations, and the answer is recorded with its basis (D-202).
- **Technical administration is not political authority:** platform configuration creates no authority (D-1a), and PublicDigit never assigns authority by default (D-167).
- **Design test:** can this feature let someone act because of a role in the software, rather than a mandate under the rules? If yes, redesign it.

## 2. Ideas rise from below and can become decisions

- Members develop ideas in discussion and carry them into motions; a motion becomes the organisation's once submitted (D-50, D-51). **Participatory motion-making is core** (D-217).
- **Design test:** does a member's idea have a visible path to a decision, or does it stop in a discussion thread?

## 3. Every authoritative decision is traceable and explainable; challenge follows the organisation's rules

- **PublicDigit preserves** the decision, the organ and its authority, the evidence, the provenance, and the relevant timing (when it took effect, when it became known). **History is kept, never overwritten:** corrections are new, authorised facts (D-202, D-206).
- **Where the organisation's rules provide a challenge route, PublicDigit supports it.** For moderation, the default route is the independent tribunal (D-53). **PublicDigit does not invent a universal right of appeal.**
- **While a challenge is pending, the decision stays effective** unless the organisation's rules provide otherwise or the tribunal orders an interim measure (D-71).
- **Design test:** could a member, a tribunal or an authority reconstruct this decision years later?

## 4. Members choose freely

- Votes are anonymous: no link from a voter to a vote, ever.
- **Design test:** could this feature reveal, infer or pressure an individual's vote?

## 5. Money is accountable to the members

- How money was decided and used is visible to those entitled to see it. **Financial transparency is core** (D-217).
- **How fees affect standing and rights** follows the organisation's own rules and is provable afterwards (**dues and standing**, D-217).
- **Design test:** can a member see why they lost or regained a right, and on what rule?

## 6. Power is visible over time

- **Where the organisation's rules define an office term,** the product records the holder, term, appointment and ending. It makes the distribution and duration of authority visible.
- **PublicDigit imposes no universal term or term-limit policy.**
- **Design test:** does the product make concentration of power *visible*, without forbidding what an organisation's rules allow? For example: how long offices have been held, or how many decisions were taken without a sitting.

## 7. Dissent is protected, not silenced

- **Moderation must be reasoned and traceable. Hiding content does not erase its history.** Moderators hide with a reason and never silently delete (D-158, the Newsletter and Forum design).
- **Visibility and access remain subject to the organisation's rules and applicable law.** PublicDigit does not promise that every contribution stays published.
- Review of moderation follows the organisation's rules; the default is the tribunal (D-53, as amended by D-167).
- Minority positions can be recorded alongside decisions, where the organisation's rules provide for it.
- **Design test:** can an unpopular view be removed without a trace, a reason or a remedy?

## 8. Belonging is established by the organisation; membership exit belongs to the member

- **Organisational belonging and placement arise from the organisation's authorised acts,** as its rules provide. By default these are individual acts: an allocation, a transfer decision, or an admission naming the unit (D-79).
- **An address never establishes belonging;** it may at most produce a suggestion (D-79, D-80). **A person cannot change their own placement unilaterally.**
- **Resignation is the member's own act**, needing no body's decision. It follows the accepted effective-date and recording rules (D-194 where the rules are silent; D-203 for the self-service authority). The ratified Membership architecture is unchanged.
- Each organisation's data is its own, and PublicDigit staff see it only with that organisation's permission for that case (D-164, D-166).
- **Design test:** does this feature move a person, or expose an organisation's data, without that person's or that organisation's act?

## 9. Integrity before convenience

- **When an authoritative answer is missing, PublicDigit refuses rather than guessing.** A refusal is never presented as "no" (CR-01 C2, "complete or refused", accepted by D-73).
- History is append-only; corrections are new facts (RA §9).
- **Design test:** would this shortcut ever let the system state something it cannot prove?

## Ideas for later releases (not decided, not scheduled)

Each would be **offered, labelled and overridable** under principle 0:
- member-initiated motions, with co-signature thresholds set by the statute (principle 2);
- members' right to petition or convene an assembly where the statute provides it (principles 1, 2);
- term and rotation reporting for offices (principle 6);
- dissenting opinions recorded with decisions (principle 7).

## How to use this document

- **When designing a feature:** run the design tests of the principles it touches, and record in the tactical design the result, **any conflict, and any justified deviation**.
- **Principles add no release scope:** a feature they suggest needs its own decision and design.
- **When a principle conflicts with an organisation's rules:** the organisation's rules win (principle 0). The product may still make the consequence visible.
- **When a principle conflicts with a decision:** the decision record governs. Changing a principle needs the Principal's decision.
