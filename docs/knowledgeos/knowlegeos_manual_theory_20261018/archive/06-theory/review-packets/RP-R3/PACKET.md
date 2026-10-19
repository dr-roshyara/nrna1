# Review packet RP-R3 — targeted independent review (read-only)

**Built** by `tools/make_review_packet_r3.py` from the current manuscript. Inputs, sha256:
- `chapter_00/CH-00-foundations.md` 382befbffa545c2f65373bfa9b618356aac86996b52bcff6e1f73a1a43d99f3d
- `chapter_01/CH-01-knowledge-authority-execution.md` 315f91caa7b15286295f0ca7ef7dd2165218488d07ff4a5b621eb7aa3feb84c6
- `chapter_02/CH-02-rules-and-conflict.md` c22a24018ea168d73b1d05c2aa29486faac8a317155e6b0147ea85bf6aa3679b
- `APPENDIX-glossary.md` c8471aa00329b9275d52cd9257af51cf138b22dcfbc6a3c2053860e94fd3f126

## Your task

You are a fresh, read-only reviewer: formal logic, mathematics, DDD and research method. **Do not create, modify or delete any file.** Do not decide the four reserved questions listed below; evaluate whether the text keeps them open.

**You may read only:** this packet; and, to check context around the excerpts, the background source files whose excerpts appear in Part D (all in `docs/knowledge_tranfer/`, names beginning `20260816_0841`, `0905`, `0922`, `1023`, `1029`, `1211`, `1332`). **Do not read** the manuscript files themselves, any review or disposition record, the idea register, the KnowledgeOS research corpus, sealed material, `.env` or credential files, transcripts, or anything else. If you need more, say so in your report.

**The excerpts in Parts B and C are the current manuscript text with the author's editorial correction-history notes removed** (notes of the form "*(Round …)*" and blockquotes beginning "Corrected …"). Nothing else was changed.

**Reserved questions (keep open, do not decide):**
- **R1** the ending policy P (which grant endings carry over to claims, per kind);
- **R2** whether premise P-E is adopted, narrowed or kept conditional;
- **R3** the reading of I-2's "reasoned" part ((a), (b) or (c));
- **R4** whether adopting a rule as mandatory authorizes blocking (OQ-24).

### Areas and what to do

- **A — Authority formula (Part B.1–B.3).**
  - Reconstruct the definitions of validly-made and currently-authoritative as premises → conclusion.
  - Test the formula against:
    - multiple covering grants (cited and uncited);
    - joint grants;
    - **each** ending kind (expiry, consumption, revocation, supersession, annulment, suspension);
    - claims that state no validity period;
    - records that cite no grant.
  - Check whether time of act, act window, binding period and time of use are distinct and consistent.
  - Check whether every open question (R1, record time) is stated as open rather than settled.
  - Check whether any comparison involves incomparable quantities.
  - Check whether the source readings (V3, AB) are stated accurately against the excerpts in Part D.
- **B — Enforcement (Part B.4–B.6).**
  - Reconstruct the argument and premise P-E.
  - Check that the conclusion follows **only** given P-E and is not presented as adopted.
  - Attempt counterexamples to the three conditions for an admissible block, using your **own** cases. Do not reuse the examples given in the text.
  - Check the treatment of mandatory rules, candidate rules, authorized freezes, configuration-driven escalation and authority gates (fail-closed).
  - Check the delimitation of "normative effects".
- **C — I-2 "reasoned" (Part B.7, B.8 and A.2).**
  - Check that explicit / reasoned / visible are separated correctly.
  - Check that readings (a), (b), (c) are stated fairly, with consequences and evidence.
  - Check that no passage or glossary row states one reading as settled.
  - Check that "valid", "well-founded", "verifiable" and "quality obligation" are used consistently across Parts A–C.
- **Consistency.** The glossary rows (Part C) and the dependent passages (Part B.6, B.8) must agree with the main claims.

**For each area A, B and C, give:**
- a verdict: SOUND / SOUND WITH MINOR ISSUES / DEFECTIVE / CANNOT JUDGE;
- the exact claim reviewed (quote, with part and line);
- the premises;
- the counterexample(s) attempted and their outcome;
- evidence;
- remaining uncertainty;
- whether correction is required;
- whether a reserved question is affected.

**A failed counterexample attempt does not prove a claim; say what is and is not established.**

**Report** (final message, about 1,200–1,800 words):
1. Scope actually read.
2. Verdicts with reasoning.
3. Confirmed defects / interpretive points / unresolved questions.
4. Effects on the reserved questions (alternatives and consequences only).
5. Recommended corrections (one sentence each).
6. Limitations: you are a fresh session of the same model family as the author, not an external expert.
7. Recommendation: CORRECT, PAUSE, or eligible for the next approval step.

## Part A — Definitions and axioms

### A.1 Authority axiom (Ch. 1 §1.6)

**Development (Ded).** These are not separate rules. They are **one theorem** under the model of §1.2:

> Standing changes only by recorded acts (§1.2). Relevance, activity, ownership, existence, detail, repetition and correctness are properties a claim can have *without any act occurring*. Therefore **none of them can change standing.**

Each "X ≠ authority" is an instance of this theorem for one property X. The list is open-ended: the theorem predicts further instances, such as "popularity ≠ authority" and "age ≠ authority", without new rules. A *counterexample* would be a property that by its definition includes a recorded act, e.g. "ratified". Such a property is not a counterexample but the act itself.

**Consequence.** The theory needs only **one authority axiom** (standing changes only by recorded acts), not a growing list of prohibitions.

> **Refined (Chapter 2 §2.8.2).**
> 1. **Domain.** The axiom is about **normative status**: either the *standing of a claim* or an *actor's permission to act*. "Relevance ≠ authority" is an instance for claims. "Active ≠ authorized", "existence ≠ permission" and "ownership ≠ authority" are instances for actors. They need the companion premise "permission exists only through a grant that references an act" (§1.13). Because the theorem follows from the axiom by definition, its "further instances" (popularity, age) are **consequences, not predictions**; they test nothing.
> 2. **Conditions on a valid act:** the act is recorded, **and** the subject scope of what it changes lies within the actor's **grant scope**, **and** the act falls within the grant's **validity period**. Authority = act × scope × time.
> 3. **Open:** grant scope and canon scope (Ch. 0 §0.3) live in different value spaces. Containment between them is undefined until a mapping exists.
> 4. **Standing:** …

### A.2 Invariants and I-2 (Ch. 0 §0.5–§0.6)

| # | Invariant | Kind | Note |
|---|---|---|---|
| I-1 | Knowledge never silently disappears | design rule | consistent with staged retirement [Synth L61] |
| I-2 | Status changes are explicit, reasoned and visible where knowledge is found | design rule | the central rule; answers the master failure [Synth L20] |
| I-3 | Every claim carries its provenance | design rule | "without provenance it is data, not knowledge" [Synth L110] |
| I-4 | Contradictions stay visible: they coexist at rest and are resolved at use | design rule | **Open** for machine users, who need one answer [Synth L57]. *Partial answer: Ch. 2 §2.3–2.7 give machines a defined output at use: a proven conflict with its region, or UNKNOWN. They never give a silent pick. The final choice stays a human disposition* |
| I-5 | Authority is scoped; nothing claims global truth | empirical generalization | supported by the failure of "single source of truth" [RQ-002 Raw L13] |
| I-6 | Freshness is visible and depends on the domain's half-life | design rule | — |
| I-7 | Supersession is a link, never an edit | design rule | — |
| I-8 | No capability may create a second population that maintains knowledge separately from its users | **empirical generalization raised to a rule** | **weak point:** the one counterexample is absorbed by re-description [Synth L15], which risks unfalsifiability |
| I-9 | Quality is evidenced, never asserted | design rule | — |
| I-10 | Representation changes are deliberate and preserve nature and provenance | design rule | added when the Constitutional Model was first committed (07-11, git `d7e9763b0`; T-01 §B) |
| I-11 | **Knowledge exists only to satisfy knowledge needs**; documentation is one representation | axiom (telos) | claimed to generate all other rules [GKC L66]: see below |

> status transitions **Draft → Canonical** and **Canonical → Superseded** are *acts*. An act is valid only if a recorded human decision covers it. A mechanism may *report* that the conditions for the act are met; it may not perform it.
This makes I-2 ("transitions are explicit and reasoned") precise: *explicit* means a recorded act; *reasoned* means it cites evidence. **Ded.** *Open:* whether a missing reason makes the act **invalid** or only **ill-founded** is not settled. Chapter 1 §1.17.2 sets out three readings: (a) validity condition, (b) quality obligation, (c) validity condition for specified kinds of act. It is a reserved decision. I-2's third part, "visible where knowledge is found", is a presentation obligation (§0.1). It also explains why the 08-16 documents insist that absence of a recorded grant is not a denial, only a gap in the record (Chapter T-01 and KT-8).

### A.3 Kinds of empty answer (Ch. 2 §2.8.8)

| E1 | the set is **known to be empty**: proven no applicability, proven disjoint | ∅ | SC |
| E2 | the set is **not known** (unspecified) | ⊥ | SC (SC-2) |
| E3 | **not looked at**: outside the evaluation scope | outside S-3 | SC §3, Ch. 1 §1.5 |
| E4 | knowledge exists but has **no carrier** | — | Ch. 1 §1.5 |
| E5 | the knowledge **does not exist** (a gap) | — | Ch. 0 §0.7 |
| E6 | **not analysable** in the logic used (undecided, time-out), or **not readable** (see V3 addition below) | UNKNOWN | RM, Ch. 2 §2.3(a); V3 L292 |
| E7 | **not applicable**: the dimension cannot apply to this kind of subject (governed) | absent from app(k) | SD (SC-1), §2.8.6 |
| E8 | **withheld (Hyp)**: it exists, but the reader may not see it | outside may-read | **my deduction** from the read-access gap IA raises (IA L155–165 does not mention empties); depends on the may-read hypothesis, Ch. 1 §1.17.5 |

### A.4 Grant endings and delegation (Ch. 1 §1.13.2–§1.13.3)

2. **Endings now exist, as new records.** A revocation and a consumption are each recorded as a *new* entry referring to the earlier grant, not as an edit of it. This is AM-1 (authority must be able to end) realised in the **append-only** style of I-7 (supersession is a link, never an edit).
3. **A new kind of ending appears in practice: CONSUMED.** The grant was used up by delivering its work. AM-1 lists *starts · expires · revoked · suspended · superseded* [AMS L284]; **consumption is not on that list.** It is a real, distinct business situation: a **single-use authority**, exhausted by use, not by time or by withdrawal. **NEW IDEA (from data).**
5. **Record integrity is the weak point.** AM-3 requires the *act* to be immutably addressed. But the *grant records themselves* are untracked files: a grant can be edited or deleted without trace. By §1.12, the **verifiability of authority rests on record integrity**, and here the records are less protected than the acts they point to. **The theory requires grant records to be at least as immutable as the acts they reference.** **Ded; a concrete, closable gap.**

### 1.13.3 Ending, delegation and linkage (Src, ruled AM-1…AM-5)

- **AM-1. Authority must be able to end; "the end is not one event."** Starts, expiry, revocation, suspension and supersession are different situations [AMS L284]. *Consumption* is a further situation, from the data above.
- **AM-2. Delegated and independent authority must be distinguished.** Today delegation exists only as "re-granting with a narrower scope", with no link between grants [AMS L92–96]. **Not every narrower grant is a delegation** [AMS L285].
- **AM-3. Every grant must reference an immutable human act.** This splits into the **evidence principle** (a source act is always named; 20/20) and the **addressing improvement** (the reference resolves to a fixed artifact) [AMS L286].
- **AM-4. Ownership and authority are separate.** "Who do I contact?" ≠ "who may approve a change?" [AMS L183–194, L287]. This confirms §1.11 with data: 0 grants carry an owner.
- **AM-5. Authority must be linkable to the rule it authorizes.** Today **no grant references a rule and no rule references a grant** [AMS L200–206]. *What exactly* is authorized (one change, a class of changes, an activity) is left **open** [AMS L288].

**Development (Ded).**
- **Delegation is a derivation relation, not containment.** That containment (child grant scope ⊆ parent grant scope) is **not sufficient** for delegation is the source's own refinement of AM-2: "independent authorization must remain possible" [AMS L285] (**Src**). My addition (**Ded**): delegation therefore needs an explicit **derivation edge** child → parent. Conditions and consequences, extended after audit L-19:
  - (a) scope containment holds **transitively** along derivation edges, and a delegated grant's validity lies within its parent's;
  - (b) the parent must carry a **right to delegate**; otherwise any holder could sub-delegate;
  - (c) with joint authorizations (e.g. "PO/ARB") a grant may have several parents, so the structure is a **directed acyclic graph**, a forest only if single-parent derivation is ruled;
  - (d) **open policy question:** does ending the parent end the child? If legitimacy derives from the parent's act, the theory says *yes, unless independently re-authorized*. The sources do not rule on this.


## Part B — Claims under review (current text, notes removed)

### B.1 Authority formula (Ch. 1 §1.14.1)

*Premise made explicit (Int).* The counterexample rests on a **normative premise**: work done under a grant that was valid when the work was done keeps its standing after the grant **expires or is consumed**, unless a recorded act revokes or supersedes **that work itself** (the claim *c*). This is how offices and mandates ordinarily work. **The data show only that a CONSUMED ending exists.** They do not show how the organisation treats work done under it.

*Counter-position in the sources (Src; V3 L319).* V3 says a rule "may be `Active` in its lifecycle and not currently authoritative — because its authority lapsed, was revoked, or was superseded". Two readings:
- "its authority" means the **grant** behind the rule: then grant lapse, revocation and supersession end the rule's current authority, which **contradicts** the premise for expiry ("lapsed");
- "its authority" means the rule's **own adoption**: then the sentence concerns revocation and supersession of the claim itself and is consistent with the premise.

V3 does not say which it means. The premise therefore stands as an **interpretation opposed by one plausible reading of a source**, not as an established result.

**Grants and claims.** §1.13.3(d) asks whether a **delegated grant** ends when its parent grant ends, and leans *yes*. A delegated grant is itself the product of a completed act. So "products survive the end of their grant" cannot be a general law. It can only be a **per-kind policy**: products of one kind may survive while products of another kind (delegated grants) do not.

Two times are needed: *t*ₐ, when the adopting act happened, and *t*, when the claim is used.

**Notation (Ded).** For a recorded act *a*:
- actor(*a*), its time *t*ₐ, and the claim *c* it adopts;
- **basis(*a*)**: the set of grants that the record of *a* **cites** as its authority. A single grant, or a joint basis (e.g. "PO/ARB") whose members are all required;
- the **act window** of a grant *g*: the period in which acts under *g* may be made;
- an **ending** of *g*: an event of one of the kinds AM-1 distinguishes (expires, revoked, suspended, superseded) [AMS L284], plus **consumption** (observed in the grant record, §1.13.2) and **annulment** (a conceptual case added here, Ded). **Expiry**, **consumption**, **revocation**, **supersession** and **annulment** (void from the start) are terminal. **Suspension** is an interval.

> **validly-made(*c*)** ⇔ a recorded act *a* adopted *c* at *t*ₐ ∧ basis(*a*) ≠ ∅ ∧ the grants in basis(*a*) jointly cover actor(*a*) and the scope of *c* ∧ *t*ₐ lies in the act window of each grant in basis(*a*).
>
> **currently-authoritative(*c*, *t*)** ⇔ validly-made(*c*) ∧ *t* ∈ validity(*c*) ∧ no recorded act has revoked or superseded *c* itself by *t* ∧ **E**ₚ(*a*, *t*),
>
> where **P** is an **ending policy**: the set of ending kinds that carry over from a grant to the claims adopted under it, possibly different per kind of claim. **E**ₚ(*a*, *t*) holds iff no grant in basis(*a*) has, by *t*, a terminal ending whose kind is in P(kind of *c*), and none is under a suspension at *t* if suspension is in P(kind of *c*).

**What the formula settles, and what it does not.**
- **Settled by the notation (Ded):**
  - only grants **cited** in the act's record count. AM-3's evidence principle is that a grant must name its human source [AMS L284–286], and the act's reliance is what the record shows;
  - if the record cites **no** grant, validly-made(*c*) cannot be determined (kind of nothing E4 or E2, Ch. 2 §2.8.8). This is the **current situation for rules**: no rule references a grant [AMS L200–206; AM-5].
- **Left open (reserved decision 1):** the policy P per kind of claim. The premise above amounts to "expiry and consumption ∉ P" for ordinary claims. The policy *survives* is P = ∅. A policy under which revocation of the grant ends the claim has revocation ∈ P. **No value of P is assumed by this book.**
- **Left open (limitation L-RT): record time.** "Valid at *t*ₐ" can be judged on the grant record **as it stood at *t*ₐ** or **as it stands at *t***. The formula has no index for this. The difference matters for **annulment** (a grant later recorded as issued in error):
  - judged on the record at *t*: an annulled grant was never valid, so *c* was **never validly made** (retroactive, "void from the start");
  - judged on the record at *t*ₐ: the grant was valid then; *c* ends only if annulment ∈ P, and only **from the annulment onward** (like revocation "from now on").

  Which reading applies is not ruled. The first also needs grant records whose history cannot be rewritten, which they are not today (§1.13.2 point 5).

*Counterexample attempts (Ded).*
1. **Two covering grants.** Actor X holds *g*₁ (project) and *g*₂ (platform); the record of *a* cites *g*₁ only; *g*₁ is revoked, with revocation ∈ P. The formula says *c* **ends**, although X also held *g*₂. *Assessment:* this follows from binding to the cited basis. It is defensible, because the recorded reliance was on *g*₁ and a mechanism cannot know about an uncited alternative. It is also harsh, because a re-adoption citing *g*₂ is needed. **Open consequence for decision 1.**
2. **Joint basis with suspension.** basis(*a*) = {PO grant, ARB grant}; the ARB grant is suspended for a month, with suspension ∈ P. The formula says *c* is not current during the suspension and current again afterwards. Consistent with suspension being an interval.
3. **No cited grant.** The formula says "cannot determine", not "not authoritative". Consistent with Ch. 2 §2.8.8: E2 or E4 does not license a negative answer.

These attempts did not refute the formula. **That does not prove it**; it shows the formula gives a definite, explainable answer in these cases.

**Two readings of AB's exception rule (Src AB L100).** "An exception's validity cannot exceed the approver's authority":
- **static reading**: validity(exception) ⊆ the act window of the approver's grant, checked once at *t*ₐ. Later endings of the grant do not matter unless P says so;
- **dynamic reading**: the exception binds only while the approver's authority lasts, so any ending of that authority ends the exception. In the notation, P(exception) contains every ending kind.

AB does not say which it means; AB L107 ("makes authority *temporal*") fits both. **Neither reading is selected here.** The dynamic reading would decide P for exceptions.

**The grant's act window vs the product's binding period.** These are two different periods, and they belong to different things. The act window belongs to the **grant**; the binding period, validity(*c*), belongs to the **claim** *c*. Tying the second to the first is a per-kind policy: AB's rule ties them for exceptions under either reading. Extending the tie to **all** claims would make every decision lapse when the office-holder's term ends, which the two-time form is designed to avoid.

**validity(*c*) for kinds without a stated period** (e.g. decisions; glossary "validity" covers rules, exceptions and grants). Where a claim states no period, this book takes validity(*c*) = [*t*ₐ, ∞). That is a **convention** (Int), not a source rule. A different convention changes only the second conjunct of the formula.

Consequences:
- No second *stored* field is needed. "Currently authoritative" is **computed** from the record of acts, their cited grants, the endings of those grants, and later acts on *c*.
- A stored copy of a derived value can drift from the record that determines it. This is IM's point that "a stored `is_valid` boolean is the bug" (§1.16.8).
- So **my proposed** answer to OQ-11 is: a governed lifecycle from which current standing is derived, with no second stored field. V3's phrase "governed authority lifecycle" may mean the **grant's** lifecycle. My answer reads it as the claim's standing over time, plus the cited grants as of the act, plus the policy P. That is a reinterpretation of the question (**Int**), not V3's meaning.
- Computing it **requires** that act records cite their grants. For rules this linkage does not exist today [AMS L200–206], so the formula is a specification of what would be computable, not a description of what is.
- This also explains why V3 needs §1.13.2's endings: without them, every approval is authoritative forever.


### B.2 Two times (Ch. 1 §1.16.2)

**Development (Ded).** Authority statements need two time indices:
- *t*ₐ, the time of the act, at which the grant must have covered the actor and scope;
- *t*, the time of use, at which the claim's own validity, revocation and supersession are evaluated.

Collapsing them in either direction fails. Two further questions stay open (§1.14.1): **which grant endings carry over** to the claims adopted under the grant (the ending policy P, reserved), and **which state of the record** "valid at *t*ₐ" is judged against (limitation L-RT, which decides whether annulment is retroactive).

The two failures:
- evaluating the grant at *t* voids work done under consumed or expired grants, **given the normative premise** stated in §1.14.1, which one reading of V3 L319 opposes;
- evaluating the claim only at *t*ₐ makes revocation and supersession impossible, so every approval is authoritative forever.

AB leaves open whether a grant must carry a **delegation chain and as-of-time validity** (**OQ-18**). The two-time form shows that **as-of-time validity is required** for validly-made(c) to be computable at all; the delegation chain is §1.13.3's derivation DAG.


### B.3 Stored derived values (Ch. 1 §1.16.8)

**Time-triggered conditions (Src; IM L207–227; REPEATED).** "An expired exception cannot satisfy a current conflict" has **no triggering command**: expiry is caused by time. It is evaluated at read time against the asking instant, "never cached as a boolean"; "a stored is_valid boolean is the bug this row exists to prevent". This is §1.14.1's "derived, never stored" (I-9), reached independently. IM's OQ-22 (may a condition have no trigger?) is answered by it: yes, and such conditions are exactly the ones a write-time-only check misses.

### B.4 Enforcement (Ch. 1 §1.14.5)

**Classification:** NEW IDEA. **It does not follow from the book's existing axiom alone.** It follows from the axiom together with a further premise, P-E, stated below. CF's rule is a PROPOSED source [CF L36].

**Argument (Ded, conditional on P-E).** Let a gate *g* block an action whenever rule *r* is violated. Its effect on actors is the effect *r* would have if it were authoritative and mandatory. §1.6 governs *changes of standing*, and a gate changes no standing, so §1.6 alone does not exclude this. **The argument needs one further premise:**

> **(P-E)** No mechanism may produce the *normative effects* of an act unless a valid act authorizes those effects.

**What "normative effects" covers (Ded; delimitation).** An effect is *normative* if it changes what an actor **may** do (permit, forbid, **block**) or **must** do (oblige), or changes the **standing** of a claim. **Reporting, warning, displaying, logging and recommending are not normative effects**: they are evidence (INV-A1) and need no authorizing act. Whether P-E should reach further, to effects such as delays, quotas or costs imposed on work, is part of **reserved decision 2**. Under a broad reading, more mechanisms need authorizing acts; under the narrow reading used here, fewer.

P-E generalizes ARCH-INV-1 ("records authority; never grants it", §1.14.2), whose standing is a Track-B ruling. P-E itself is **not adopted**. Everything below holds **only if P-E is accepted**.

> **Conclusion (given P-E).** blocking(*g*, *r*) in scope *S* during period *T* ⇒ some valid act authorizes **that block** for a scope containing *S* and a period containing *T*.

Which acts can do that:
- **an act that authorizes the gate's blocking directly** (e.g. a temporary freeze while *r* is still a candidate). The act sits on the gate, not on the rule, so *r* need not be adopted;
- **an act that specifies blocking (hardness) for *r***, for the scope and period. This may be part of the act that adopts *r*, or a separate act;
- **the adoption of *r* as mandatory, by itself**, only under **reading (i)** of reserved decision 4, that mandatory binding strength already authorizes blocking. Under **reading (ii)**, hardness needs its own specification. **The book does not assume reading (i).** Evidence bearing on it, not a ruling: Ch. 2 §2.9 shows that "a rule may be MANDATORY while its enforcement is ADVISORY" is a legitimate and current state [RS L185–187], so mandatory strength and blocking do come apart in practice. Whether a rule conflict blocks activation is likewise undecided (OQ-24) [IA L173] (§1.17.5).

**Recommended rules and confirmation requests.** Blocking on a *recommended* rule gives a "should" the force of a "must". Only the issuing authority can set that, so it needs an authorizing act like any other block. A **confirmation request** (the mechanism asks, a person decides) is not a block in this sense: the confirming person's decision is the act, and the mechanism decides nothing.

**Conditions for an admissible block (given P-E).** All of:
1. **realization**: *r* has reached a form in which a check can be executed (Ch. 2 §2.9; §1.9);
2. **authorization**: a valid act authorizes this block, by one of the routes above (the third route only under reading (i));
3. **containment**: the block stays within the scope and period of that act.

*Checks against examples (Ded).*
- **Authorized emergency freeze on a candidate rule:** 1 holds if the check is executable; 2 holds through the freeze act; 3 holds within its scope and period. **Admissible.**
- **Automatic escalation by configuration** (`design-check.sh` "becomes hard-blocking when violations drop below threshold", Ch. 2 §2.9): the switch to blocking is made by a configuration edit with no recorded act (Ch. 2 §2.10). Condition 2 fails, so it is **not admissible as a block under P-E**. Under reading (i) it would be admissible **only if** the rule had been adopted as mandatory by a recorded act. The configuration records no such act.
- **Mandatory rule, advisory enforcement** (today's quality gates): no block, so nothing to authorize. Consistent with Ch. 2 §2.9.
- **A block on a rule whose adoption was valid but has since been superseded:** 2 fails if the block's authority was the adoption itself; it holds if a separate act authorizes the block for that period. A definite answer, not a refutation.

These checks did not refute the conditions. They do not prove them.

**Authority gates and failing closed**. The session resolver blocks work although no adopted rule says "block this task". It is an **authority gate**: it blocks on the *absence* of a permitting act. What the axiom shows, and what it does not:
- **It shows** that an act made without a covering grant confers no standing (§1.6).
- **It does not show** that a mechanism may **prevent** the attempt. An actor may hold authority that exists but has no record (E4, §1.17.3). Preventing the attempt treats "no record" as "no authority".

Failing closed therefore rests on **INV-A3** ("absence is never permission"), whose source is a Track-B proposal and which is **not adopted**, or on an explicit premise **P-R: only recorded grants count**, which is not adopted either. Under P-E, the resolver's blocking is itself a normative effect and needs an authorizing act; whether one exists is **open**. For work that changes no standing, the refusal rests on a governance policy, and the same question applies.

*What the conditions still allow (Ded).* A rule may be **evaluated** and its result **reported** without any authorizing act, because a report is evidence, not an act (INV-A1). The conditions restrict only *blocking*. So the advisory mode of today's quality gates (Ch. 2 §2.9) is the correct mode for **any rule for which no valid act authorizes a block**, whatever its standing.


### B.5 Rules on blocking in Chapter 2 (§2.9–§2.10)

> **A rule may be MANDATORY while its enforcement is ADVISORY. That is legitimate.** What is defective is having **no way to say both**. Then "we mean it but do not yet block" reads as "we do not mean it" [RS L185–187].
`scripts/design-check.sh` states: "Non-blocking during migration phase; becomes hard-blocking when violations drop below threshold." The source's description is **accurate**. The configuration also shows the obligation (target 0) and the enforcement trajectory **stored side by side but not named as different concepts**, exactly as RM-2 says.
**A third orthogonal property (Src: IM L45; REPEATED in structure).** IM separates **hardness** (HARD · SOFT · ADVISORY: "whether the business wants it to block") from **atomicity** ("when a condition can be evaluated"), warning that "conflating them is how an advisory check silently becomes a gate". *Mapping (Ded):* hardness is the *intended* enforcement consequence, set by an act; atomicity is a fact about evaluability. With §2.9's two properties, a condition now has three that must not be inferred from one another: **binding strength** (how strongly it obliges), **hardness** (whether it is meant to block), and **evaluability** (when it can be checked). Ch. 1 §1.14.5 adds, **conditionally on premise P-E** (not adopted), that a block needs a valid act authorizing that block for its scope and period; whether a mandatory rule's adoption is such an act is open (OQ-24).
- **Machine-enforced checks** (quality gates, recommendation thresholds) carry none of these. Anyone who can edit a configuration file can change them, without a record of who or why.

### B.6 Dependent passages (Ch. 1 §1.17.3, §1.17.5)

- **refusal to proceed**, which follows from any of the three **given INV-A3** ("absence is never permission"; Track B, not adopted) or premise P-R (only recorded grants count; not adopted). The authority axiom alone does not license it, because one of the three cases, E4, is authority that exists without a record (§1.14.5).
**Hardness and its owner (Src: IA L62–85, L173–175; Ded).** IA adds *hardness* (Ch. 2 §2.9) and notes that whether a rule conflict blocks activation "is a business decision that nobody has made", while "three documents have now assumed it blocks" (OQ-24). That is §1.14.5's warning in action: an assumption repeated across documents behaves like a decision no one took. IA also asks whether hardness belongs on the invariant or on the command (OQ-26), since "the same invariant may block one command and merely warn on another". *Answer (Ded):* on the **command**, i.e. the enforcement point. Given P-E, a block there needs a valid act authorizing it for that scope and period (§1.14.5); whether a mandatory rule's adoption counts as such an act is reserved decision 4 (OQ-24). That is where Chapter 2 already places enforcement consequence: a property of the mechanism, not of the rule.

### B.7 I-2 and validity vs well-foundedness (Ch. 1 §1.17.2)

### 1.17.2 Authority and information must co-occur

**The idea (Src; IA L44–58).** IA's single ownership test: "If this condition is false, which single object has the **authority and the information** to reject the command?" It notes that "the corpus's failures have always been one without the other". Its worked case: a rule *can see* that its authority reference is missing (information), but has no *standing* to judge whether the referenced grant is valid (authority), so one condition has two owners.

**Development (Ded): the two failure modes touch two different properties.**
- **Information without authority** is a mechanism that decides, or a person who decides without a grant. A mechanism breaks ARCH-INV-1 (§1.14.2); an ungranted person fails the axiom's grant condition. Either way the act is **not valid**: it confers no standing.
- **Authority without information** is different. Under the axiom's **current** conditions (§1.6: a recorded act, within grant scope, within the grant's period), an authorized act is valid however poorly it was informed. **Whether that remains so depends on an open choice about I-2** (below). What such an act may lack is **well-foundedness**.

**I-2 has three parts, and they belong to different properties (Ded).** I-2 reads: status changes are "explicit, reasoned and visible where knowledge is found" (Ch. 0 §0.5):
- **explicit**: a recorded act. This is part of **validity** under every reading (Ch. 0 §0.6);
- **reasoned**: the record cites evidence. Its place is the **open choice** below;
- **visible where knowledge is found**: the change can be seen at the point of use. This is a **presentation** obligation, the core property of Ch. 0 §0.1. It belongs neither to validity nor to well-foundedness.

Two further words in use, kept distinct:
- **verifiable** (Ch. 2 §2.11): recorded in enough detail (approver, reason, scope, period) for a third party to **check** validity and well-foundedness. A property of the **record**;
- **quality obligation**: the name for reading (b) below. Not a fourth property.

**The open choice: what "reasoned" is (reserved decision 3).** Three readings, all coherent with the current axiom:

| Reading | An act without a recorded reason … | Consequence | What supports it |
|---|---|---|---|
| **(a)** validity condition | confers **no standing** | strict; reasons added later cannot rescue the act; makes I-2's breach void the act | I-2 is listed as an **invariant** (Ch. 0 §0.5) |
| **(b)** quality obligation | confers standing and **carries a recorded defect** until revised | stable standing; the weakness is visible in the record | elsewhere the book treats invariant breaches as **defects**, not as voiding acts (in-place edits "break I-1 and I-7", Ch. 2 §2.10) |
| **(c)** validity condition **for specified kinds of act** | confers no standing **for those kinds**; reading (b) for the rest | follows the sources where they speak | RM-3 (ruled, Track B): changing an authoritative rule requires "explicit authorization **and a recorded reason**" (Ch. 2 §2.11) |

**Presence vs adequacy.** Under any reading, the **presence** of a recorded reason can be decided from the records. Its **adequacy**, whether the reason actually supports the act, cannot be decided mechanically under any reading. So decidability does not favour any of (a)–(c).

**No recommendation is made here.** Evidence needed to choose:
- an organisational ruling on whether unreasoned acts are void;
- how RM-3's per-kind requirement is meant to generalize;
- how reasons recorded **after** the act are to be treated.

The choice also fixes what breaching **any** invariant does to an act. *Source sense.* In IA, "authority" names which **software object** may reject a command. Applying the test to human grants is my **analogy** (**Int**), not IA's claim.

**Validity and well-foundedness, kept apart under every reading:**
- **validity** of a rejection or adoption: a recorded act by an actor with a cited, covering grant at the time of the act (§1.14.1), **plus a recorded reason under reading (a), or under (c) for the specified kinds**;
- **well-foundedness**: recorded reasons that cite evidence, drawn from an assembled context whose coverage is stated (§1.17.4).

**Under reading (b)**, an ill-founded but valid decision keeps its standing until a recorded act revises it; its weakness is shown by its recorded reasons. **Under (a)** an unreasoned act would have no standing; **under (c)** that holds only for the specified kinds. In every reading, a decision made from a stale or incomplete context (§1.16.3) can be **authorized but not informed**. This connects authority to Chapter 0's core problem.



### B.8 Rule lifecycle and exceptions (Ch. 2 §2.10–§2.11)

- **Configuration rules are edited in place, so their history is lost.** This breaks I-1 (no silent disappearance) and I-7.
**The rule lifecycle (Src, ruled RM-3, RS L262–276).** Changing an authoritative rule requires explicit authorization and a recorded reason. Target path: **propose → review → authorize → effective**. Today the path is *edit a file → new organisational rule*.
- By Ch. 1 §1.12, an exception is valid only if the authorizing act occurred, and verifiable only if it is recorded with approver, reason, scope and period.

## Part C — Glossary rows

| Term | Meaning | Not to be confused with | Where |
|---|---|---|---|
| **standing** | what the organisation currently treats a claim as (provisional, authoritative, historical, superseded …); changed only by recorded acts | RM-1's "standing obligation" (= **persistence**, binds always); lifecycle phase | Ch. 1 §1.2; Ch. 2 §2.0 |
| **validity (period)** | the time interval in which a rule, exception, grant or other claim binds; for a claim that states no period, this book uses the **convention** [*t*ₐ, ∞) (Int; Ch. 1 §1.14.1) | standing; lifecycle phase; a grant's **act window** | Ch. 2 §2.2, §2.11; Ch. 1 §1.14.1 |
| **effective (rule)** | standing *adopted* ∧ t ∈ validity | "in force by date" | Ch. 2 §2.11 |
| **authority (valid act)** | a recorded act, within the actor's grant scope and validity period (act × scope × time) | ownership; relevance; existence | Ch. 1 §1.6; Ch. 2 §2.8.2 |
| **binding strength** | how strongly a rule obliges (mandatory, recommended); set by the issuing authority | enforcement consequence | Ch. 2 §2.9 |
| **enforcement consequence** | what a mechanism does today when a rule is broken; set by configuration or act. **Given premise P-E (not adopted)**, a block is admissible only if the check is executable, a valid act authorizes that block for its scope and period (directly, by specifying hardness, or by adoption of the rule as mandatory only under reading (i) of reserved decision 4), and the block stays within that act's scope and period. A confirmation request is not a block | binding strength; realization level | Ch. 2 §2.9; Ch. 1 §1.14.5 |
| **current authority (of a claim)** | derived, never stored: validly made (a recorded act whose **cited** grants covered actor and scope, with the act inside their act windows) ∧ *t* within the claim's validity ∧ the claim not revoked or superseded by *t* ∧ no grant ending of a kind in the **ending policy P** (reserved decision 1). Open: which record state "valid at *t*ₐ" is judged against (limitation L-RT; decides whether annulment is retroactive). The premise that products survive expiry and consumption is Int and opposed by one reading of V3 L319 | workflow state ("Approved") | Ch. 1 §1.14.1, §1.16.2 |
| **authority gate** | a mechanism that checks whether an actor may act (act × scope × time); computable from records; its failing closed rests on INV-A3 (Track B, not adopted) or premise P-R, not on the authority axiom alone | conformance gate | Ch. 1 §1.14.5 |
| **conformance gate** | a mechanism that checks an artifact against rules; needs rules with applicability and effect; **given P-E**, may block only when a valid act authorizes that block (Ch. 1 §1.14.5); may always evaluate and report | authority gate | Ch. 1 §1.14.5 |
| **valid (act)** | a recorded act by an actor whose cited grants cover it at the time of the act (act × scope × time); **plus a recorded reason under reading (a) of reserved decision 3, or under (c) for specified kinds**; confers standing | well-founded; verifiable | Ch. 1 §1.6, §1.14.1, §1.17.2 |
| **well-founded (decision)** | its recorded reasons cite evidence from a context whose coverage is stated. **Under reading (b)** a valid act can be ill-founded and keeps its standing until revised; under (a) an unreasoned act is not valid; under (c) that holds for specified kinds | valid; verifiable | Ch. 1 §1.17.2 |
| **verifiable (act or record)** | recorded in enough detail (approver, reason, scope, period) for a third party to check validity and well-foundedness; a property of the record | valid; well-founded | Ch. 2 §2.11; Ch. 1 §1.17.2 |
| **quality obligation** | the name for reading (b) of I-2's "reasoned" part: a breach is a recorded defect, not a cause of invalidity. A reading under reserved decision 3, not an adopted rule | validity condition | Ch. 1 §1.17.2 |
| **ending policy P** | the set of grant-ending kinds (expiry, consumption, revocation, supersession, annulment, suspension) that carry over to claims adopted under the grant, possibly per kind of claim; **reserved decision 1**; no value assumed | act window; validity | Ch. 1 §1.14.1 |
| **P-E (premise)** | "no mechanism may produce the normative effects of an act unless a valid act authorizes those effects"; normative = permit, forbid, block, oblige, change standing; **not adopted** (reserved decision 2) | ARCH-INV-1; INV-A3 | Ch. 1 §1.14.5 |

## Part D — Source excerpts (line numbers are the source's)

### V3 §10 — `docs/knowledge_tranfer/20260816_0841_target_architecture_v3.md` L297–323

```
 297  # 10 · Lifecycle — ⭐ ARB: add the STATE ≠ AUTHORITY distinction
 298  
 299  **Carried forward:** the 3-class structure (`pre-authority → authoritative → terminal`) and 3 identity modes.
 300  
 301  **Per-type refinement adopted** — instances of the 3-class structure, not a replacement:
 302  
 303  ```
 304  Decision   Proposed → Reviewed → Approved → Implemented → Superseded → Retired
 305  Rule       Draft → Validated → Active → Suspended → Retired
 306  Evidence   Collected → Normalized → Verified → Invalidated
 307  ```
 308  
 309  ## 10.1 ⭐ STATE ≠ AUTHORITY — ARB REV 1
 310  
 311  > ```
 312  > Active    ≠  Authorized
 313  > Approved  ≠  Currently authoritative
 314  > Exists    ≠  Permitted
 315  > ```
 316  
 317  ⭐ **This is the corpus's own family, extended.** `how_to_work_with_sessions` already carries `ACTIVE ≠ AUTHORIZED · OWNERSHIP ≠ AUTHORITY · EXISTENCE ≠ PERMISSION`; the ARB adds `Approved ≠ Currently authoritative`, which is the one the per-type lifecycles above cannot express.
 318  
 319  ⛔ **It becomes load-bearing under the richer Rule model (§15):** a rule may be `Active` in its lifecycle and not currently authoritative — because its authority lapsed, was revoked, or was superseded. **A single status field cannot say that.**
 320  
 321  ## 10.2 The governing invariant
 322  
 323  > **No type's lifecycle may contain a direct edge from creation to an authoritative state.** Every authoritative state is entered by a **recorded human act**, never by the passage of a mechanism.
```

### AB §5 — `docs/knowledge_tranfer/20260816_0922_aggregate_boundaries.md` L94–111

```
  94  ## 5. `ExceptionGrant` — the highest-value gap
  95  
  96  The proposal's §4 invariants are adopted in full, because each states something the corpus needs and does not have:
  97  
  98  - the exception must identify the rule it overrides;
  99  - **its scope cannot be broader than the base rule unless explicitly authorized**;
 100  - **its validity cannot exceed the approver's authority**;
 101  - an expired exception cannot satisfy a current conflict;
 102  - **revocation must be distinguishable from expiration**;
 103  - an exception cannot silently mutate the base rule.
 104  
 105  Two of these are load-bearing beyond exceptions themselves.
 106  
 107  **"Validity cannot exceed the approver's authority"** makes authority *temporal*. The corpus treats a grant as present-tense — *"which recorded authority permits you to proceed?"* (`how_to_work_with_sessions` §18). It has no way to ask the proposal's sharper question: **"was the authority valid at the time of approval?"** That question is what makes an audit trail reconstructable rather than merely stored, and it is new.
 108  
 109  **"Revocation must be distinguishable from expiration"** is `INV-A3` — *absence is never permission* — in exception form. An exception that lapsed and an exception that was withdrawn look identical in any model that carries only a validity window, and they mean opposite things about the base rule.
 110  
 111  Without this aggregate, §8's warning lands exactly: **conflict resolution degrades into an informal priority mechanism** — which `0914` §8 already rejected on separate grounds.
```

### AMS rule–grant linkage — `docs/knowledge_tranfer/20260816_1332_authority_model_business_semantics.md` L196–206

```
 196  ---
 197  
 198  # 10 · How does authority relate to Rule validity?
 199  
 200  > ## ⛔⛔ **There is no relationship. Not a weak one — none.**
 201  
 202  **No grant references a rule. No rule references a grant.** The authority record governs *work items and sessions*; the rule artifacts live entirely apart.
 203  
 204  ⭐ **Consequence for the Rule Model:** `RM-3` ruled that changing an authoritative Rule requires explicit authorization and a recorded reason, ending in **Authorize**. **The authority machinery that would carry that step exists, works, and has never been pointed at a rule.**
 205  
 206  > ### ⭐ **The gap is not that authority is missing. It is that the two models have never been connected** — which is a far smaller problem than building either from nothing.
```

### AMS rulings AM-1…AM-5 — `docs/knowledge_tranfer/20260816_1332_authority_model_business_semantics.md` L276–292

```
 276  ---
 277  
 278  # 14 · ⭐ The rulings — recorded 2026-08-16, **inside Track B**
 279  
 280  ⛔ **These are business-semantics decisions. They are NOT implementation commitments.**
 281  
 282  | # | Ruling | Refinement carried |
 283  |---|---|---|
 284  | ⭐ **AM-1** | **APPROVED** — authority must be able to end | ⭐ **The end is not one event.** `starts · expires · revoked · suspended · superseded` are **different business situations**. ⛔ *Do not assume the answer is a single expiry field* |
 285  | ⭐ **AM-2** | **APPROVED** — distinguish independent from delegated authority | ⭐ Business form: *"when one authority permits another actor to act within a narrower boundary, the organization recognizes this as **delegation** and must distinguish the delegated authority from the original."* ⛔ **But not every narrower grant is a delegation — independent authorization must remain possible** |
 286  | ⭐ **AM-3** | **APPROVED** — every grant backed by an immutable reference to the human act | ⭐ **Two separable things:** the **evidence principle** *(every grant identifies its human source — already 20/20)* and the **evidence-addressing improvement** *(the reference resolves to an exact historical artifact — currently 13/20)*. ⛔ *The 7 weak references are not a reason to redesign the model* |
 287  | ⭐ **AM-4** | **APPROVED** — ownership and authority are separate concepts | *"Who do I contact?"* and *"Who may approve a change?"* are different questions with different answers today |
 288  | ⭐ **AM-5** | **APPROVED — strongly** — authority must be linkable to the Rule it authorises | ⭐ **The connection is approved; ⛔ *what exactly is authorised* remains OPEN** — a particular rule change · a class of rule changes · a bounded activity · a governance action. **Not resolved here** |
 289  
 290  ## 14.1 ⭐ The semantic model these rulings assemble
 291  
 292  ```
```

### CF §0 — `docs/knowledge_tranfer/20260816_0905_architecture_conformance.md` L14–38

```
  14  ---
  15  
  16  ## 0. The ordering error
  17  
  18  The proposal's own §12 states:
  19  
  20  > **`EKS-ARCH-007` — Authority and execution are separate concepts.**
  21  
  22  And its §7:
  23  
  24  > **"Creation and authority are different operations."**
  25  
  26  These are correct. v3 §9 and §10 already carry both — as `INV-A1`…`INV-A4` and as the lifecycle invariant *"no type's lifecycle may contain a direct edge from creation to authority."*
  27  
  28  Applied to the proposal itself, they produce its central defect:
  29  
  30  > **A conformance gate enforces an architecture. Enforcement is execution. An architecture that has not been decided cannot be executed — and encoding it into a blocking gate does not enforce a decision, it *manufactures* one.**
  31  
  32  Once `EKS-ARCH-004` fails a build, the four bounded contexts in the proposal's §10 manifest are settled in practice, whatever governance later concludes. `OQ-4` in v3 would be closed by a linter rather than by a human act. That is `INV-A1` — *a report of an act is not the act* — inverted: a *mechanism* becomes the act.
  33  
  34  **The rule this produces, and it governs everything below:**
  35  
  36  > **A gate may only enforce an architecture that a recorded human act has decided. Gates follow ratification; they never precede it.**
  37  
  38  The proposal is not wrong to want gates. It is wrong to want them *now*, for decisions that `OQ-1`, `OQ-4` and `OQ-7` leave open.
```

### RS binding strength vs enforcement — `docs/knowledge_tranfer/20260816_1211_rule_model_business_semantics.md` L176–191

```
 176  > ### **Binding strength** *(how obliged is the reader?)* **is a different concept from Enforcement consequence** *(what does the mechanism do about it today?)*.
 177  
 178  | | **Binding strength** | **Enforcement consequence** |
 179  |---|---|---|
 180  | Answers | *how strongly does this oblige?* | *what happens when it is broken, right now?* |
 181  | Owned by | ⭐ the authority that issued the Rule | the mechanism operating it |
 182  | Changes when | the obligation changes | ⭐ **the enforcement maturity changes** |
 183  | Example | *mandatory* | *advisory today; blocking once the gate is trusted* |
 184  
 185  > ### ⭐ **A Rule may be MANDATORY while its current enforcement is ADVISORY. That is legitimate, not a defect.**
 186  >
 187  > ⛔ **What is a defect is having no way to say both.** Today one field carries both meanings, so *"we mean this seriously but do not yet block on it"* is inexpressible — and the organisation resolves the ambiguity by setting the switch to not-block, which reads as *"we do not mean it."*
 188  
 189  ⭐ **This also explains the quality gates' declining target series without calling it an anomaly:** the obligation is fixed at zero violations; the *enforcement threshold* descends toward it over phases. **Two concepts, correctly separated in one mechanism, by accident.**
 190  
 191  **`RM-2` ruled:** ⭐ **the need for a common binding-strength concept is APPROVED. ⛔ The vocabulary is DEFERRED** — values are a governance act, not a technical choice.
```

### RS rule lifecycle (RM-3) — `docs/knowledge_tranfer/20260816_1211_rule_model_business_semantics.md` L262–276

```
 262  ```
 263     TODAY                          ⭐ RULED TARGET
 264  
 265     edit file                      Propose
 266        ↓                              ↓
 267     new organisational rule         Review
 268                                       ↓
 269                                    Authorize
 270                                       ↓
 271                                    Effective
 272  ```
 273  
 274  ⭐ **`RM-3` ruled: changing an authoritative Rule requires explicit authorization and a recorded reason.**
 275  
 276  ⛔ **Measured today:** a person who can edit a configuration file can change what the organisation enforces, with no record of who or why. **The widest authority gap found in the Rule Model.**
```

### IA ownership test — `docs/knowledge_tranfer/20260816_1029_invariant_allocation.md` L42–60

```
  42  ---
  43  
  44  ## 2. The ownership test
  45  
  46  The input's single-sentence test replaces the six-question form in matrix §3. It is better because it names the two things that must co-occur — **authority and information** — and the corpus's failures have always been one without the other.
  47  
  48  > **"If this condition is false, which single object has the authority *and* the information to reject the command?"**
  49  
  50  | Answer | Allocation |
  51  |---|---|
  52  | One aggregate root | Invariant lives on that aggregate |
  53  | Several aggregates | Domain service or application process |
  54  | Requires external facts | Port; record the evaluation as an assessment |
  55  | Requires human authority | Governance workflow |
  56  | Computed from a snapshot | Analysis result, not an aggregate invariant |
  57  
  58  **Why the pairing matters here.** `INV-RULE-002` splits precisely along it: the `Rule` aggregate has the *information* to see that an authority reference is absent, so presence is atomic and local. It does not have the *authority* to judge whether the referenced grant is valid. One condition, two owners — which is why matrix §5.1 had to split its `atomicity` field, and now has a principled reason to.
  59  
  60  ---
```

### IA open questions — `docs/knowledge_tranfer/20260816_1029_invariant_allocation.md` L169–175

```
 169  ## 9. Open questions added
 170  
 171  | # | Question | Settled by |
 172  |---|---|---|
 173  | **OQ-24** | Is conflict-on-activation `HARD`, `SOFT`, or `ADVISORY`? | A business decision. Three documents have now assumed it blocks without anyone deciding (§3) |
 174  | **OQ-25** | Does the PKS govern read access to knowledge, and if so at which boundary? | Human authority — the corpus is entirely silent (§8) |
 175  | **OQ-26** | Does `hardness` belong on the invariant, or on the *command* that could violate it? The same invariant may block one command and merely warn on another | Modelling decision (§3) |
```

### IM worksheet INV-EXC-004 — `docs/knowledge_tranfer/20260816_1023_invariant_aggregate_matrix.md` L207–227

```
 207  ### 5.2 `INV-EXC-004`
 208  
 209  ```text
 210  Invariant:     INV-EXC-004
 211  Statement:     An expired exception cannot satisfy a current conflict.
 212  Knowledge type: Exception
 213  Applies when:  Any query asking whether a conflict is dispositioned
 214  Trigger:       NONE — expiry is caused by time passing, not by any command
 215  Required state: ExceptionGrant.validity_period + the instant of the question
 216  Candidate owner: ExceptionGrant
 217  Atomicity:     ATOMICALLY_REQUIRED — but evaluated at READ time, not write time
 218  Enforcement:   validity evaluated against the asking instant, never cached as a boolean
 219  Referenced:    Rule (base), by id
 220  Failure:       MARK_UNKNOWN → the conflict remains unsatisfied (never a pass)
 221  Reconstructable as-of: n/a — the interval is self-contained
 222  Rationale:     proposal §4; INV-A3 — absence is never permission
 223  Test:          (none — blocked on OQ-1)
 224  Status:        Proposed
 225  ```
 226  
 227  **This row is why `trigger_command` needs to permit `NONE`.** An instrument that models only command-triggered invariants cannot express expiry, and a model that evaluates exception validity only when something is written will honour dead exceptions indefinitely. **A stored `is_valid` boolean is the bug this row exists to prevent.**
```
