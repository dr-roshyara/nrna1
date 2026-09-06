Yes. I read the attached **“Knowledge in Action” / Situation Calculus extraction** and compared it against the **current KnowledgeOS theory gaps** we have already established.

The important conclusion is:

> **This document can close a substantial part of the missing dynamic/transition theory, but it does not give us permission to simply import Situation Calculus as KnowledgeOS theory.**

Several things in the extraction are genuinely strong formal machinery for problems we already have open—especially **δ, persistence/Boundary, executable histories, composition, regression/progression, sensing, and planning**. Other proposed mappings in the document are too strong and should **not** be adopted as written.

The source itself presents Situation Calculus in terms of situations, actions, fluents and possibility/preconditions. 

---

# 1. What this document can actually complete

I would divide the findings into four levels.

| Finding                               | KnowledgeOS status             | Can enter theory?                                |
| ------------------------------------- | ------------------------------ | ------------------------------------------------ |
| Successor-state semantics             | δ is currently OPEN            | **YES — strong candidate**                       |
| Persistence / frame problem           | Boundary currently OPEN        | **YES — strong candidate**                       |
| Action preconditions / executability  | δ / executable history OPEN    | **YES — formal research basis**                  |
| History vs snapshot                   | State/history distinction OPEN | **YES, with correction**                         |
| Composition of transitions            | Composition OPEN               | **YES — major contribution**                     |
| Regression                            | Evaluation OPEN                | **YES as reasoning mechanism**                   |
| Progression                           | δ/evaluation OPEN              | **YES as alternative reasoning mechanism**       |
| Sensing / knowledge-producing actions | Evaluation/Observation OPEN    | **YES as candidate mechanism**                   |
| Temporal actions                      | Time semantics OPEN            | **YES as research basis**                        |
| Concurrency                           | Composition OPEN               | **YES as candidate semantics**                   |
| Natural/forced transitions            | δ OPEN                         | **YES as optional transition class**             |
| Planning                              | Decision/action lane OPEN      | **YES as derived capability, not core ontology** |
| Probability / MDP                     | Decision theory                | **NOT yet theory-core**                          |
| Policies                              | Governance/action              | **Possibly, but later**                          |
| Situation Calculus ontology itself    | —                              | **NO**                                           |
| `Sat = truth in situation`            | Sat currently unresolved       | **NO — too strong**                              |
| `Zero = executable situation`         | Zero unresolved                | **NO — explicitly reject**                       |
| `Gap = non-entailed fluent`           | Gap unresolved                 | **NO — too narrow**                              |
| `Contr = inconsistency`               | Contr unresolved               | **NO**                                           |
| `Boundary = frame axiom`              | Too strong                     | **NO**                                           |

The document itself makes the key dynamic distinction: a successor situation `do(a,s)` represents the situation after an action, while fluents are properties that vary with situations and `Poss(a,s)` expresses applicability. 

That is exactly where KnowledgeOS currently has a theoretical hole.

---

# 2. The biggest contribution: δ can now be formalized

This is the strongest result.

We currently have:

$$
(K_t,e_t)\xrightarrow{\delta}K_{t+1}
$$

but δ has not yet been properly defined.

The document gives us the formal structure of a **successor-state axiom**:

$$
F(do(a,s))
\equiv
\gamma_F^+(a,s)
\lor
\left(
F(s)\land\neg\gamma_F^-(a,s)
\right)
$$

where:

* \(\gamma^+\) = conditions causing the component to become true
* \(\gamma^-\) = conditions causing the component to become false
* otherwise the previous state persists. 

### This gives us a very useful KnowledgeOS candidate

Instead of treating δ as an opaque function:

$$
\delta:K\times E\rightarrow K
$$

we can research:

$$
\boxed{
X_{t+1}
=
Add_X(e_t,K_t)
\lor
\left(
X_t\land
\neg Remove_X(e_t,K_t)
\right)
}
$$

for each state component \(X\).

Or more generally:

$$
\boxed{
K_{t+1}
=
Apply(K_t,e_t,
Add(e_t),
Remove(e_t),
Persist(e_t))
}
$$

This is **not yet the final KnowledgeOS δ definition**.

But it is a legitimate formal candidate derived from established transition semantics.

### Why this matters

It turns δ from:

> “something changes the state”

into something that can be tested:

1. What becomes true?
2. What becomes false?
3. What persists?
4. Under which conditions?
5. What is not affected?

That is a major reduction of the δ problem.

---

# 3. Boundary becomes much clearer

This is probably the second strongest contribution.

The document explains the **frame problem**: specifying what does *not* change after every action becomes combinatorially expensive. The frame problem is explicitly about action invariants—what remains unaffected by an action. 

This maps very well to our existing Boundary research.

But we should **not define**:

$$
Boundary = FrameAxiom
$$

Instead:

> **Frame semantics provides a formal source for investigating Boundary as the preservation/non-effect component of state transition.**

So we can now formulate a candidate:

$$
\boxed{
Boundary(K_t,e_t)
=
\{x\mid e_t\text{ does not invalidate }x\}
}
$$

but this is still only a **candidate**.

More precisely, the successor-state formulation suggests:

$$
X_{t+1}
=
Effect^+_X
\lor
(Persist_X\land\neg Effect^-_X)
$$

Therefore:

$$
\boxed{
Boundary_X(e,K)
\sim
\text{conditions under which }X\text{ persists}
}
$$

That gives us something concrete to experiment with.

---

# 4. We can now separate four things that were previously mixed

The document gives us a very useful decomposition:

### A. Applicability

$$
Poss(e,K)
$$

Can the event/action occur?

### B. Positive effect

$$
Add(e,K)
$$

What becomes true?

### C. Negative effect

$$
Remove(e,K)
$$

What becomes false?

### D. Persistence

$$
Persist(e,K)
$$

What remains unchanged?

This is extremely valuable for KnowledgeOS.

Our transition lane can therefore become:

$$
\boxed{
Poss
\rightarrow
Effect^+
/
Effect^-
/
Persistence
\rightarrow
K_{t+1}
}
$$

This is much better than trying to make Boundary, δ, and Preconditions one concept.

---

# 5. Executable history is another important addition

The source defines an executable history as one where every action occurring in the history satisfied its preconditions. 

This gives us a strong candidate distinction:

$$
History
\neq
ExecutableHistory
$$

and potentially:

$$
\boxed{
Executable(h)
\iff
\forall e_i\in h:
Poss(e_i,K_i)
}
$$

This is useful because KnowledgeOS needs to distinguish:

* a state transition that is **formally representable**
* a transition that is **permitted**
* a transition that is **actually executable**
* a transition that **actually occurred**

These must not be collapsed.

So we should add an explicit research item:

> **Executable Transition / Executable History Semantics**

This can materially strengthen δ.

---

# 6. History versus state should be corrected, not copied

The document says:

> “A situation is not a snapshot of the world. It is a finite sequence of actions.” 

This is important, but the extraction then makes the stronger statement:

$$
K_t=\text{history}
$$

I would **not adopt that**.

We already have evidence that KnowledgeOS needs to distinguish:

$$
\boxed{
State \neq History
}
$$

Instead:

$$
H_t=(e_1,\ldots,e_n)
$$

and

$$
K_t = State(H_t)
$$

is much safer.

So:

$$
\boxed{
H_t \xrightarrow{State} K_t
}
$$

rather than:

$$
K_t=H_t
$$

This actually fits our existing distinction between **state identity, event identity, operation identity and provenance identity**.

---

# 7. Composition can now be substantially advanced

This is another major contribution.

Golog defines complex actions in terms of primitive actions.

For sequence:

$$
Do(\delta_1;\delta_2,s,s')
$$

there exists an intermediate state \(s''\) such that:

$$
Do(\delta_1,s,s'')
\land
Do(\delta_2,s'',s')
$$

The extraction gives exactly this structure. 

This gives us a candidate KnowledgeOS composition law:

$$
\boxed{
\delta_{e_2}
\circ
\delta_{e_1}
}
$$

such that:

$$
K_{t+2}
=
\delta(K_{t+1},e_2)
$$

and

$$
K_{t+1}
=
\delta(K_t,e_1)
$$

therefore:

$$
\boxed{
K_{t+2}
=
\delta(\delta(K_t,e_1),e_2)
}
$$

This sounds obvious, but formally it gives us the foundation for the currently unresolved **Composition** lane.

---

# 8. We should add an explicit composition algebra research item

The document also provides:

### Sequence

$$
\delta_1;\delta_2
$$

### Choice

$$
\delta_1\mid\delta_2
$$

### Iteration

$$
\delta^*
$$

These are not yet KnowledgeOS primitives.

But they give us a **candidate operation language** for researching composition.

So:

> **Do not add Golog operators to Theory v1.3 as KnowledgeOS primitives.**

Instead add:

### `KR-ACT-COMP`

**Transition Composition Semantics**

Investigate whether KnowledgeOS transition composition requires:

$$
Sequence,\ Choice,\ Iteration
$$

and determine their:

* preconditions
* partiality
* determinism
* provenance
* authority
* failure semantics
* boundary semantics
* temporal semantics.

That is a very good research lane.

---

# 9. Regression can help close the Evaluation problem

The document gives a formal regression mechanism:

$$
R[W]
$$

reducing a query about a later situation to a query about the initial situation. 

This is important because we currently have:

$$
Eval_c(K,r,\Gamma)
$$

but haven't yet specified how evaluation can actually be performed.

We can now distinguish:

$$
\boxed{
Eval_c
\neq
ReasoningAlgorithm
}
$$

and introduce:

$$
Reason_{\mathcal S}
$$

with possible mechanisms:

$$
Regression_{\mathcal S}
$$

and

$$
Progression_{\mathcal S}
$$

Then:

$$
\boxed{
Reason_{\mathcal S}(K,r)
\rightarrow
EvidenceForEvaluation
}
$$

rather than claiming:

$$
Regression = Eval
$$

This is an important theoretical correction.

---

# 10. Progression should also be included—but explicitly marked harder

The source distinguishes regression from progression and states that progression can be significantly more computationally difficult. 

This gives us a useful methodological result:

$$
\boxed{
BackwardReasoning
\neq
ForwardSimulation
}
$$

We should therefore introduce two candidate mechanisms:

$$
Regress(K,r)
$$

and

$$
Progress(K,e)
$$

with no assumption that one is universally superior.

This is particularly useful for KnowledgeOS because:

* **query answering** may favor regression;
* **state evolution** naturally uses progression;
* **planning** may require both.

---

# 11. Sensing gives us a missing piece for Observation → Knowledge

This section is highly relevant.

The source models knowledge using an accessibility relation:

$$
K(s',s)
$$

and defines knowledge of \(\phi\) in terms of all accessible situations satisfying \(\phi\). 

More importantly, sensing actions filter the accessible situations. 

This gives us a very useful candidate distinction:

$$
\boxed{
WorldChange
\neq
KnowledgeChange
}
$$

A sensing operation can produce:

$$
K_t^{epistemic}
\rightarrow
K_{t+1}^{epistemic}
$$

without necessarily changing the external subject.

The document explicitly describes knowledge-producing actions as affecting knowledge rather than the physical world under its no-side-effects assumption. 

For KnowledgeOS, however, we should **not adopt that assumption universally**.

Instead:

> Sensing provides a candidate model for **epistemic update without subject-state mutation**.

That is very useful for our existing Observation/Evidence/Knowledge work.

---

# 12. This strengthens the Observation-first research lane

We already had:

$$
(K_t,O_t)
\xrightarrow{B}
D_t
\xrightarrow{T}
K_{t+1}
$$

The new material suggests that Observation can also be understood as reducing an epistemic accessibility set:

$$
\mathcal A_t
\rightarrow
\mathcal A_{t+1}
$$

where:

$$
\mathcal A_{t+1}
\subseteq
\mathcal A_t
$$

for pure information acquisition.

This gives us a new research hypothesis:

$$
\boxed{
Observation/Sensing
=
epistemic state-space restriction
}
$$

**PROP only.**

It should be tested against:

* contradictory observations
* stale observations
* provenance
* context changes
* temporal separation
* unobservable states
* insufficient evidence.

That connects directly to our recent FDE/Boundary work.

---

# 13. Time and concurrency can now be formally researched

The document introduces:

$$
time(a)
$$

and conditions on temporal ordering. 

It also defines concurrent actions as sets of actions with coherence and individual possibility conditions. 

This is directly relevant to the unresolved meaning of:

$$
\phi=\{time,context\}
$$

from the recent cross-frame decision work.

But there is an important distinction:

### The document gives us machinery for **time**.

It does **not** settle KnowledgeOS's semantic decision about what a frame means.

Therefore:

$$
Situation\ Calculus\ time
\neq
KnowledgeOS\ frame\ semantics
$$

Instead we can now investigate:

$$
Frame =
(time,context,\ldots)
$$

with explicit semantics.

This is a substantial improvement to the current `φ` TODO.

---

# 14. Natural/forced transitions are a useful optional concept

The source distinguishes ordinary actions from natural actions—events that occur when their conditions are satisfied and are not under the agent's free choice. 

This gives us a candidate distinction:

$$
ChosenTransition
\neq
ForcedTransition
$$

Potential KnowledgeOS categories:

$$
EventOrigin\in
\{
Agent,
System,
External,
Natural,
Scheduled,
\ldots
\}
$$

But this should **not** become a primitive yet.

It is useful mainly because it raises a currently missing question:

> Does δ require an actor/authority for every transition, or can some transitions be exogenous?

This connects directly to our governance model.

---

# 15. Planning belongs above δ—not inside it

The document defines planning as finding actions such that:

$$
KB\models Goal(do(\vec a,S_0))
$$

and the resulting history is legal. 

This is useful.

But I would place the dependency as:

$$
\boxed{
Representation
\rightarrow
Reasoning
\rightarrow
Transition
\rightarrow
Planning
}
$$

not:

$$
Planning\rightarrow\delta
$$

Planning depends on a transition semantics; δ does not depend on planning.

So Planning is a **derived capability**.

---

# 16. Probability and MDPs should NOT be put into Theory v1.3 core

The document has stochastic actions, reward/cost and expected value. 

These are legitimate mathematical mechanisms.

But they are not necessary to close the current KnowledgeOS theory.

Adding them now would create another abstraction branch before we have settled:

* evaluation
* contradiction
* boundary
* identity
* δ
* lifecycle.

So I would classify:

### Stochastic transition

`[PROP / FUTURE EXTENSION]`

### MDP

`[OUT OF CURRENT CRITICAL PATH]`

### Expected value

`[OUT OF CURRENT CRITICAL PATH]`

This prevents scope explosion.

---

# 17. The document contains three important incorrect KnowledgeOS mappings

This is where I would be particularly strict.

## A. `Sat = truth in the current situation`

The extraction says:

> Sat(Kt,r) = satisfaction = truth in the current situation. 

**Do not adopt this.**

Our recent experiments already demonstrated that KnowledgeOS evaluation has:

* Standing
* Boundary
* Context
* Provenance
* evidence
* blocked evaluator classes
* contradiction
* uncertainty.

Therefore:

$$
Sat_c
\neq
Truth
$$

and:

$$
Eval_c
\neq
Truth
$$

We should preserve:

$$
Truth
\perp
EpistemicEvaluation
$$

unless a future explicit decision connects them.

---

# 18. `Gap = non-entailed fluent` is also too narrow

The document proposes:

> unsatisfied requirements = non-entailed fluents. 

This is useful as one **reasoning mechanism**, but not as the definition of Gap.

Why?

Because:

$$
K\not\models r
$$

could mean:

* false
* unknown
* insufficient evidence
* evaluator unavailable
* contradiction
* missing assumption
* scope exclusion
* unobservable
* theory incomplete.

Our contradiction experiments specifically showed why these states cannot simply collapse into one non-entailment category.

So:

$$
\boxed{
NonEntailment \neq Gap
}
$$

but:

$$
NonEntailment
\rightarrow
GapCandidate
$$

is legitimate.

---

# 19. `Zero = executable situation` must be rejected

This is the most important incorrect mapping in the document.

The extraction states:

> Complete satisfaction = executable situation. 

That does **not** follow.

Executability means:

$$
Poss(e,K)
$$

or that all actions in a history were possible.

Zero is our much broader boundary/incompleteness concept.

We already established:

$$
Unknown\neq Absent
$$

$$
NoEvidence\neq EvidenceOfAbsence
$$

$$
Conflict\neq Invalidity
$$

$$
UnknownDimension\neq UnknownValue
$$

etc.

Therefore:

$$
\boxed{
Zero\neq Executability
}
$$

At most:

$$
Executable
\rightarrow
\text{one possible input to a Zero analysis}
$$

---

# 20. The biggest theoretical gain: we can finally structure δ

I think the attached material allows us to rewrite the **dynamic theory** much more rigorously.

A candidate structure is:

$$
\boxed{
K_t
\xrightarrow[\text{Poss}]{e_t}
\left(
Effect^+,
Effect^-,
Persist
\right)
\xrightarrow{\delta}
K_{t+1}
}
$$

with history:

$$
H_{t+1}=H_t\cdot e_t
$$

and:

$$
K_{t+1}=State(H_{t+1})
$$

Then composition:

$$
\delta_{e_2}\circ\delta_{e_1}
$$

and reasoning:

$$
Regression_{\mathcal S}
\qquad
Progression_{\mathcal S}
$$

and observation:

$$
Sense(e,K)
\rightarrow
EpistemicUpdate
$$

This is a coherent research framework.

---

# 21. Proposed new Theory section

I would therefore add the following section to the **research draft**, not yet call it Theory v1.3:

# KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS

### DK.1 State and History

A knowledge state and the history producing that state are distinct:

$$
H_t=(e_1,\ldots,e_n)
$$

$$
K_t=State(H_t)
$$

Therefore:

$$
\boxed{K_t\neq H_t}
$$

History may be retained for provenance, reconstruction and transition reasoning, while \(K_t\) represents the current state.

**Status:** `[DERIVED]`

---

### DK.2 Transition Applicability

A transition is applicable only when its declared preconditions hold:

$$
Poss(e_t,K_t)
$$

Thus:

$$
\boxed{
Poss(e,K)\rightarrow
\text{transition may be executed}
}
$$

This does not by itself establish authority or authorization.

**Status:** `[DERIVED]`

---

### DK.3 Successor-State Semantics

For a state component \(X\):

$$
\boxed{
X_{t+1}
=
Add_X(e_t,K_t)
\lor
\left(
X_t\land
\neg Remove_X(e_t,K_t)
\right)
}
$$

This provides a candidate formal decomposition of transition effects and persistence.

**Status:** `[PROP — EXPERIMENT REQUIRED]`

---

### DK.4 Boundary / Persistence

A transition specification must account not only for what changes but also for what persists.

$$
\boxed{
Persistence_X(e,K)
}
$$

is therefore a candidate transition property.

The frame problem supplies the mathematical motivation, but does not establish that KnowledgeOS `Boundary` is identical to a frame axiom.

**Status:** `[DERIVED + PROP]`

---

### DK.5 Executable History

For a history:

$$
H=(e_1,\ldots,e_n)
$$

define candidate:

$$
Executable(H)
\iff
\forall i,\ Poss(e_i,K_i)
$$

where:

$$
K_{i+1}=\delta(K_i,e_i)
$$

**Status:** `[PROP]`

---

### DK.6 Transition Composition

Sequential transition composition:

$$
\boxed{
\delta_{e_2}\circ\delta_{e_1}
}
$$

is defined operationally by:

$$
K_{t+1}=\delta(K_t,e_1)
$$

$$
K_{t+2}=\delta(K_{t+1},e_2)
$$

and therefore:

$$
K_{t+2}
=
\delta(\delta(K_t,e_1),e_2)
$$

**Status:** `[DERIVED]`

The semantics of choice and iteration remain `[OPEN]`.

---

### DK.7 Reasoning Over Transitions

Two candidate reasoning directions are distinguished:

$$
Regression_{\mathcal S}
$$

and

$$
Progression_{\mathcal S}
$$

Neither is identified with Evaluation.

**Status:** `[DERIVED]`

---

### DK.8 Epistemic Update

Sensing/information-producing events provide a candidate mechanism for changing epistemic state without necessarily changing the represented subject state:

$$
(K_t,O_t,e^{sense})
\rightarrow
K_{t+1}^{epistemic}
$$

**Status:** `[PROP]`

---

### DK.9 Temporal and Concurrent Transitions

Transitions may carry temporal information:

$$
time(e)
$$

and multiple transitions may potentially be represented as a concurrent set:

$$
C=\{e_1,\ldots,e_n\}
$$

provided their compatibility conditions hold.

**Status:** `[PROP — REQUIRES TIME/FRAME DECISION]`

---

# 22. This changes the TODO list significantly

After this document, I would update our TODOs like this:

### Can be substantially narrowed

**δ**

Before:

> completely OPEN

Now:

> **FORMAL CANDIDATE ESTABLISHED — successor-state semantics + applicability + persistence/effects.**

Still needs experiments.

---

### Boundary

Before:

> OPEN

Now:

> **FORMAL RESEARCH BASIS ESTABLISHED — persistence/non-effect via frame problem.**

But KnowledgeOS Boundary itself remains undefined.

---

### Composition

Before:

> majority/intraframe evaluation composition unresolved

For **transition composition**, we now have:

> **Strong formal candidate: sequential composition.**

Evaluation composition remains separate.

This distinction is important:

$$
\boxed{
TransitionComposition
\neq
EvaluationComposition
}
$$

---

### Evaluation

Regression gives us:

> **reasoning mechanism candidate**

but does not solve `Eval_c`.

---

### Observation

Sensing gives us:

> **formal candidate for epistemic update**

---

### Time

Situation Calculus provides:

> **formal temporal machinery**

but does not decide our \(\phi\).

---

### Planning

Now:

> **derived capability dependent on δ + goal semantics.**

Not core theory yet.

---

# 23. New experiments I would commission

This is where the document becomes genuinely useful.

## `KR-DELTA-2026-09`

**Successor-State / Persistence Experiment**

Test:

1. Add only
2. Remove only
3. Add + Remove
4. Neither
5. Conditional Add
6. Conditional Remove
7. Unknown effect
8. Contradictory effect
9. concurrent effects
10. invalid transition

Measure whether:

$$
Effect^+
+
Effect^-
+
Persistence
$$

can represent the required distinctions.

---

## `KR-EXEC-2026-09`

**Executable History Experiment**

Test:

$$
Poss(e,K)
$$

against:

* authorized
* unauthorized
* impossible
* unknown precondition
* contradictory precondition
* externally forced event
* failed execution
* simulated execution.

This should connect δ with our existing **Authority** model without conflating them.

---

## `KR-COMP-TRANS-2026-09`

**Transition Composition Experiment**

Test:

$$
\delta_2(\delta_1(K))
$$

against:

* sequence
* failure
* partiality
* rollback
* conflicting effects
* concurrent events
* repeated events.

This should be kept separate from our recent evaluation-composition experiments.

---

## `KR-SENSE-2026-09`

**Epistemic Sensing Experiment**

Test whether:

$$
Sense(O,K)
$$

can change epistemic state without changing subject state, while preserving:

* provenance
* time
* context
* contradiction
* evidence status.

---

# 24. What this document does NOT solve

This is equally important.

It does **not** complete:

1. **Contr**
2. **Zero**
3. **semantic identity \(\equiv_{sem}\)**
4. **\(\mathcal R_{req}\)**
5. **meaning of cross-frame divergence**
6. **factivity**
7. **lifecycle/retirement**
8. **full Evaluation semantics**
9. **truth**
10. **authority**
11. **governance**
12. **KnowledgeOS kernel selection**

And it certainly does not justify:

$$
Contr = Inconsistency
$$

$$
Zero = Executable
$$

$$
Sat = Truth
$$

$$
Gap = NonEntailment
$$

or

$$
KnowledgeOS = Situation\ Calculus.
$$

---

# 25. My recommended architectural/theoretical consequence

The most valuable result is that we can now separate the theory into **two orthogonal mechanisms**:

### Epistemic lane

$$
Observation
\rightarrow
Evidence
\rightarrow
Evaluation
\rightarrow
Determination
$$

### Dynamic lane

$$
Event
\rightarrow
Possibility
\rightarrow
Effect
\rightarrow
Persistence
\rightarrow
\delta
\rightarrow
K_{t+1}
$$

Connected by:

$$
\boxed{
K_t
}
$$

This is much cleaner than trying to make one operator explain everything.

And then:

$$
\boxed{
Reasoning
}
$$

can operate over both:

$$
Regression/Progression
$$

while:

$$
Planning
$$

can operate over the dynamic model.

---

# 26. Bottom line

I would **not** follow the attached document's recommendation to simply “integrate the book into KnowledgeOS Theory v1.3.” The extraction itself describes these mappings as KnowledgeOS translations, but several of those translations go beyond what the formal source establishes. 

Instead, I think we have something better:

> **The document gives us enough formal material to close most of the currently missing *dynamic semantics research lane*.**

Specifically:

$$
\boxed{
\begin{array}{c}
\text{State / History distinction}\\
\downarrow\\
Possibility / Preconditions\\
\downarrow\\
Effects^+ / Effects^-\\
\downarrow\\
Persistence / Boundary\\
\downarrow\\
\delta\\
\downarrow\\
Composition\\
\downarrow\\
Regression / Progression\\
\downarrow\\
Planning / Sensing / Temporal reasoning
\end{array}}
$$

The **next correct step is therefore not Theory v1.3 yet**. It is to create a **`KR-DYN / Situation-Calculus Integration` evidence-to-theory artifact**, mark each item `[FACT] / [DERIVED] / [PROP] / [OPEN]`, and run the δ/persistence/executable-history experiments.

That would materially reduce the critical path to the kernel without making the same mistake we avoided in the FDE and composition work: **importing a mathematically elegant external formalism and silently treating its constructs as KnowledgeOS semantics.**
