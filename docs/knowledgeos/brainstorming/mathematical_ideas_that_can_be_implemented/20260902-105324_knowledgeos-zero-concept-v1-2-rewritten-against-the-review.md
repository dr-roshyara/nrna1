I reviewed the attached review in full. The review materially changes how I would write the Zero concept: it confirms the **boundary/non-collapse idea**, but it also identifies several places where the previous formulation was still overclaiming. 

The key correction is this:

> **Zero should not be defined as a detector of “everything missing.” It should be defined as a disciplined examination of what the current epistemic representation establishes, does not establish, and may systematically fail to represent, relative to an inquiry, context, and declared lens.**

That formulation is strong enough to absorb the experimental results without inventing missing semantics.

# KnowledgeOS — Zero Concept v1.2

**Status:** `[PROP]` — strongly supported research concept
**Zero Closure:** `[OPEN]`
**Kernel membership:** `[OPEN]`
**Canonical implementation:** `[OPEN]`

---

## 1. The fundamental problem Zero addresses

KnowledgeOS maintains an evolving epistemic representation:

$$
K_t
$$

at time \(t\).

But:

$$
K_t\neq Reality
$$

and:

$$
K_t\neq CompleteRepresentation(Reality)
$$

in general.

A representation can therefore contain correct information while still failing to represent relevant aspects of its subject.

The central epistemic danger is:

$$
\boxed{
\neg Represented(x)
\not\Rightarrow
\neg Exists(x)
}
$$

and, more generally:

$$
\boxed{
\text{epistemic non-establishment}
\not\Rightarrow
\text{world-level negation}.
}
$$

This is the fundamental problem to which Zero responds.

---

# 2. The new definition

I recommend freezing this as the primary formulation:

> **Zero is the disciplined, inquiry-relative examination of what a current epistemic representation establishes, does not establish, and may fail to represent, while preserving distinctions among the different reasons for that boundary.**

Formally:

$$
\boxed{
ZL(K_t,I_t,\Gamma_t,L_t)\rightarrow B_t
}
$$

where:

* \(K_t\) = current epistemic representation;
* \(I_t\) = inquiry/purpose;
* \(\Gamma_t\) = applicable semantic/contextual contracts;
* \(L_t\) = declared examination lens;
* \(B_t\) = boundary findings.

This is preferable to:

$$
ZL(K_t)\rightarrow B_t
$$

because **boundary is not absolute**.

What matters for one inquiry may be irrelevant for another. The review makes this point particularly clearly. 

---

# 3. Zero is a lens, not a state

Zero is **not** another state of knowledge.

Therefore:

$$
\boxed{Zero\neq K_t}
$$

$$
\boxed{Zero\neq KnowledgeElement}
$$

$$
\boxed{Zero\neq Value}
$$

$$
\boxed{Zero\neq TruthValue}
$$

$$
\boxed{Zero\neq Decision}
$$

$$
\boxed{Zero\neq Authorization}.
$$

Zero examines the representation.

Conceptually:

```text
                 Current Kt
                    │
                    ▼
               Zero Lens
                    │
                    ▼
               Boundary B
```

This preserves the strongest part of the previous concept while avoiding the claim that Zero itself is already a semantic primitive. The review explicitly recommends keeping “meta-epistemic operation” and “semantic primitive” as hypotheses rather than conclusions. 

---

# 4. Zero is not "nothing"

The name Zero must never be interpreted ontologically.

$$
\boxed{
Zero\neq Nothingness
}
$$

and:

$$
\boxed{
Zero\neq Absence.
}
$$

A Zero examination may reveal:

* missing information;
* unknown value;
* unknown dimension;
* non-assessment;
* unresolved interpretation;
* insufficient evidence;
* conflicting evidence;
* underdetermination;
* unobservability;
* scope limitation;
* temporal limitation;
* model limitation;
* unexamined assumption;
* theory incompleteness.

Therefore there is no single semantic state called “Zero.”

---

# 5. The central Zero principle: non-collapse

I would now make this the **mathematical/conceptual core** of Zero:

$$
\boxed{
\text{Do not collapse semantically distinct boundary conditions merely because they share a coarse projection.}
}
$$

This is stronger than any individual `Unknown` rule.

Examples:

$$
Unknown\neq Absent
$$

$$
Unresolved\neq False
$$

$$
NotAssessed\neq LowConfidence
$$

$$
NoEvidence\neq EvidenceOfAbsence
$$

$$
UnknownDimension\neq UnknownValue
$$

$$
Conflict\neq Invalidity
$$

$$
NoKnownGap\neq Complete
$$

$$
Representation\neq Reality.
$$

These should remain candidate invariants, but they now have a common underlying principle.

The review identifies this non-collapse property as one of the strongest parts of the concept. 

---

# 6. Zero does not discover "everything missing"

This correction is essential.

A function cannot generally discover an unknown dimension merely from the absence of that dimension.

Suppose:

$$
D_t=\{OS,Version,RAM\}.
$$

The fact that:

$$
DependencyGraph\notin D_t
$$

does not by itself establish:

1. dependency graph is relevant;
2. dependency graph is missing;
3. dependency graph exists;
4. dependency graph has never been considered;
5. dependency graph is outside scope.

Therefore:

$$
\boxed{
Zero(K_t)\not\rightarrow D^*\setminus D_t
}
$$

when \(D^*\) itself is unknown.

This is a critical formal limitation.

The review calls this out directly: **Zero cannot be an oracle for unknown unknowns.** 

---

# 7. Known boundary versus unknown boundary

We should therefore distinguish two levels.

### Level 1 — Boundary detection

The system can identify:

> “This requirement depends on information that has not been established.”

### Level 2 — Boundary-of-boundary analysis

The system asks:

> “What classes of things might this lens systematically fail to represent?”

The second requires knowledge of the **lens itself**.

Thus:

$$
L_i(O)\rightarrow R_i
$$

followed by:

$$
ZeroLens(L_i,O)\rightarrow BlindSpots(L_i)
$$

is a separate hypothesis.

I recommend naming this:

$$
\boxed{
MetaZero
}
$$

with status `[PROP]`.

It should **not** be included in the minimal Zero definition yet.

---

# 8. Zero is inquiry-relative

This should become fundamental.

Let:

$$
I=(Target,Purpose,Context,Requirements,\ldots).
$$

Then:

$$
Boundary=B(K_t,I,\Gamma,L).
$$

Not:

$$
Boundary=B(K_t).
$$

Example:

### Inquiry A

> What operating system does Nexus use?

Relevant:

$$
OS.
$$

### Inquiry B

> Can Nexus safely be upgraded?

Potentially relevant:

$$
OS,\ Version,\ Dependencies,\ Backup,\ Network,\ Compatibility,\ Operations.
$$

Thus:

$$
\boxed{
Boundary(K,I_1)\neq Boundary(K,I_2)
}
$$

can legitimately hold.

This connects Zero directly to the existing KnowledgeOS inquiry theory.

---

# 9. Zero and evaluation must be separated

This is where the attached experiment becomes especially valuable.

The experiment showed:

$$
EVal=\{T,F,U\}
$$

is not total over the tested candidate state space, and even where it is defined:

$$
value(EVal)
$$

can lose diagnostically important information. 

Therefore Zero should **not** be:

$$
K\rightarrow EVal\rightarrow Zero.
$$

Instead:

$$
\boxed{
K_t
\rightarrow
ZeroLens
\rightarrow
B_t
}
$$

and independently:

$$
\boxed{
K_t,r,\Gamma
\rightarrow
Eval
\rightarrow
EVal.
}
$$

Only later should we ask how the two interact.

---

# 10. Boundary is richer than `U`

This is probably the most important mathematical insight now available.

Let:

$$
B=\{b_1,b_2,\ldots,b_n\}
$$

be boundary conditions.

A coarse evaluation may define:

$$
\pi_U:B\rightarrow\{U\}.
$$

Then:

$$
b_1\neq b_2
$$

but:

$$
\pi_U(b_1)=\pi_U(b_2)=U.
$$

For example:

$$
\begin{aligned}
b_1 &= EvidenceInsufficient\\
b_2 &= Unobservable\\
b_3 &= Underdetermined\\
b_4 &= TheoryIncomplete.
\end{aligned}
$$

All could project to:

$$
U.
$$

Therefore:

$$
\boxed{
Boundary\ contains\ more\ semantic\ information\ than\ U.
}
$$

And potentially:

$$
\boxed{
U=\pi(B)
}
$$

as a future hypothesis.

This directly addresses the information-loss problem found in the experiment. The review identifies this as potentially the mathematically cleanest interpretation. 

---

# 11. Contradiction is a boundary condition, not necessarily a truth value

We should **not** immediately introduce:

$$
V=\{T,F,U,C\}.
$$

Instead:

$$
Conflict(c_1,c_2)
$$

can be preserved structurally in \(B_t\).

For example:

```text
Boundary:
    Conflict
        claim_1: Version = 2.69
        claim_2: Version = 3.85
```

This allows contradiction to remain represented without forcing it into the truth-value algebra.

The experiment specifically demonstrated why the fourth-value move is not sufficient by itself. 

However:

$$
Conflict(c_1,c_2)
$$

is not yet formally defined.

It depends on:

* identity;
* semantic equivalence;
* time;
* context;
* compatibility;
* possibly provenance.

Therefore:

$$
\boxed{
Contr=[OPEN]
}
$$

even though:

$$
\boxed{
Conflict\ as\ a\ boundary\ category=[PROP].
}
$$

---

# 12. Zero does not decide which contradiction is true

Suppose:

$$
c_1:p
$$

and:

$$
c_2:\neg p.
$$

Zero may expose:

$$
Conflict(c_1,c_2).
$$

It does not thereby conclude:

$$
c_1=True
$$

or:

$$
c_2=True.
$$

Therefore:

$$
\boxed{
Zero\neq Determination.
}
$$

This preserves the earlier KnowledgeOS distinction:

$$
Evidence
\neq
Assessment
\neq
Determination
\neq
KnowledgeAttribution.
$$

---

# 13. Zero can expose theory incompleteness

This is a major advantage of the revised formulation.

Suppose the simulator asks:

> Is this proposition temporally valid?

but KnowledgeOS has no legitimate temporal validity evaluator.

The correct output is **not**:

$$
U
$$

because that would hide the reason.

And certainly not:

$$
False.
$$

Instead Zero can expose:

```text
Boundary:
    evaluator unavailable
    boundary class: theory-semantic
    basis: required semantic contract not defined
```

This incorporates one of the strongest lessons from the experiment:

$$
\boxed{
EvaluatorNeededBySimulator
\not\Rightarrow
EvaluatorSuppliedByTheory.
}
$$

The experiment demonstrated that invented governance and temporal evaluators had to be retracted. 

Thus Zero can itself become an **anti-invention mechanism**.

---

# 14. Zero and AI hallucination

This is perhaps the strongest practical application.

An AI can produce:

```text
OS = RHEL 9.8
Version = 3.85
Status = Secure
```

while silently failing to examine:

```text
Dependencies
Backup
Network exposure
Certificate validity
Operational constraints
Upgrade compatibility
```

A normal answer generator may interpret its own representation as sufficient.

Zero says:

> **What has not been represented has not thereby been established to be absent, irrelevant, or safe.**

Thus:

$$
\boxed{
Representation\neq Reality
}
$$

and:

$$
\boxed{
ModelInsufficiency\neq RealityInsufficiency.
}
$$

This anti-reification property is one of the strongest and most defensible aspects of the concept. 

---

# 15. Zero and assumptions

A representation may appear complete because assumptions were silently fixed.

Therefore Zero should potentially expose:

$$
Assumptions(K,I,\Gamma,L).
$$

For example:

```text
Assumption:
    same version means same operational behavior

Assumption:
    source is authoritative

Assumption:
    timestamp remains valid

Assumption:
    dependency set is complete
```

These are not necessarily errors.

But they are **boundaries of the reasoning model**.

Therefore:

$$
\boxed{
UnexaminedAssumption\in Boundary
}
$$

is a strong `[PROP]`.

---

# 16. Zero and abstraction

A compressed representation can conceal dimensions.

For example:

$$
Security=Secure
$$

may conceal:

$$
Authentication,\ Authorization,\ Encryption,\ Vulnerability,\ NetworkExposure,\ PatchStatus,\ldots
$$

Therefore:

$$
\boxed{
Abstraction\neq Completeness
}
$$

and:

$$
\boxed{
Compression\neq Losslessness.
}
$$

Zero should therefore be applicable not only to raw knowledge but also to **summaries, abstractions, semantic representations and AI-generated outputs**.

---

# 17. Zero and time

Zero must preserve temporal boundaries.

For example:

$$
Version=2.69\quad@t_1
$$

and:

$$
Version=3.85\quad@t_2
$$

are not automatically contradictory.

Therefore:

$$
Conflict
$$

requires appropriate temporal semantics.

Likewise:

$$
PreviouslyUnknown
\neq
PreviouslyAbsent.
$$

The temporal model itself remains `[OPEN]`.

So Zero identifies the boundary but does not invent temporal semantics.

---

# 18. Zero and evidence

The following remains fundamental:

$$
\boxed{
NoEvidence\neq EvidenceOfAbsence.
}
$$

But we should make the statement even more precise:

$$
NoEvidence
$$

does not uniquely determine any particular epistemic state.

It may indicate:

* not investigated;
* inaccessible evidence;
* evidence not retained;
* outside scope;
* unobservable;
* no relevant evidence found;
* evidence acquisition failure.

Therefore:

$$
\boxed{
NoEvidence
\not\Rightarrow
Unknown
}
$$

either.

This is an important strengthening of the previous formulation.

---

# 19. Zero and non-assessment

Likewise:

$$
NotAssessed
$$

does not imply:

$$
LowConfidence.
$$

And:

$$
LowConfidence
$$

does not imply:

$$
NotAssessed.
$$

These are different dimensions of epistemic status.

---

# 20. Zero and applicability

Similarly:

$$
NotApplicable\neq Unknown.
$$

But we should also avoid saying:

$$
NotApplicable\Rightarrow False.
$$

Applicability is itself potentially an unresolved question.

Thus the boundary can occur **before** requirement evaluation.

---

# 21. Zero is not recursive in the sense of guaranteed nontermination

The earlier formulation called Zero recursive.

The review correctly recommends a more precise statement.

Zero is **re-applicable**:

$$
K_t
\rightarrow
ZeroLens
\rightarrow
Boundary
\rightarrow
Investigation
\rightarrow
K_{t+1}.
$$

Then:

$$
K_{t+1}
$$

may be examined again.

Therefore:

$$
\boxed{
K_{t+1}\neq K_t
\Rightarrow
ZeroLens(K_{t+1})\text{ may be required}.
}
$$

We should not define Zero as an inherently infinite loop.

---

# 22. Zero is non-mutating

A useful candidate invariant is:

$$
\boxed{
ZeroLens(K_t,I,\Gamma,L)\not\rightarrow K_{t+1}.
}
$$

Instead:

$$
ZeroLens(K_t,I,\Gamma,L)\rightarrow B_t.
$$

Any state-changing operation belongs downstream:

$$
B_t
\rightarrow
Investigation/Evaluation/Revision
\rightarrow
K_{t+1}.
$$

This is valuable for kernel analysis because it separates **diagnosis of a boundary** from **transformation of knowledge**.

---

# 23. Boundary should initially remain a research structure

We should resist prematurely creating:

```text
BoundaryAggregate
```

or:

```text
ZeroAggregate
```

in the DDD architecture.

For now:

$$
\boxed{
B_t:=\text{research-level boundary description}
}
$$

is enough.

It may eventually become:

* a transient analytical result;
* a projection;
* an event;
* a persistent epistemic object;
* a value object;
* or something else.

That is an architectural decision for later.

---

# 24. Zero facets

A useful candidate decomposition is:

$$
B_t=
(
B^{dimension},
B^{value},
B^{evidence},
B^{assessment},
B^{interpretation},
B^{conflict},
B^{assumption},
B^{temporal},
B^{scope},
B^{model},
B^{observability},
\ldots
)
$$

But these are **facets**, not an exhaustive classification.

One boundary condition can occupy several facets.

For example:

```text
Version conflict

    conflict facet
    temporal facet
    provenance facet
    semantic-equivalence facet
```

This avoids repeating the mistake of assuming that a finite list is necessarily exhaustive.

---

# 25. Zero Closure is downstream

We should now explicitly separate:

### Zero Lens

$$
\boxed{
ZL(K,I,\Gamma,L)\rightarrow B
}
$$

### Evaluation

$$
\boxed{
Eval(K,r,\Gamma)\rightarrow EVal
}
$$

### Determination

$$
\boxed{
Det(E,Q,\Gamma)\rightarrow A
}
$$

### Closure

$$
\boxed{
Closure(B,EVal,A,Q,\Gamma)\rightarrow ?
}
$$

The final codomain of Closure remains open.

It may eventually be:

$$
\{T,F,U\}
$$

or another structure.

But **that decision must not contaminate the definition of Zero Lens**.

---

# 26. Therefore the old Zero equation is retired

The previous:

$$
Zero\iff\Delta=\varnothing
$$

should no longer be the definition of Zero.

At most:

$$
\boxed{
ZeroClosure\stackrel{?}{\iff}\Delta^{sem}=\varnothing
}
$$

can remain a candidate.

Status:

$$
[OPEN]
$$

This is the central correction produced by the entire v1.2 research sequence.

---

# 27. A new formal abstraction

I think we can now formulate the conceptual core more elegantly.

Let:

$$
\mathcal B(K,I,\Gamma,L)
$$

be the set of boundary conditions exposed by examination.

Then:

$$
\boxed{
ZeroLens(K,I,\Gamma,L)
=
\mathcal B(K,I,\Gamma,L).
}
$$

A coarse evaluation may be a projection:

$$
\pi:\mathcal B\rightarrow V.
$$

For example:

$$
V=\{T,F,U\}.
$$

But:

$$
\pi
$$

may be many-to-one.

Therefore:

$$
\boxed{
\mathcal B
\overset{\pi}{\longrightarrow}
V
}
$$

is potentially lossy.

The Zero discipline says:

> **Do not discard the boundary information merely because a downstream evaluator uses a coarse projection.**

This is, in my view, the most promising mathematical formulation emerging from the experiments.

---

# 28. Zero as an anti-collapse operator

We can therefore formulate a research hypothesis:

$$
\boxed{
Zero:
Representation
\rightarrow
Boundary
}
$$

with the property:

$$
b_i\neq b_j
$$

should remain distinguishable whenever the theory says the underlying conditions are epistemically distinct, even if:

$$
\pi(b_i)=\pi(b_j).
$$

This gives Zero a precise research target:

$$
\boxed{
\textbf{preservation of epistemically relevant distinctions}.
}
$$

That is much stronger than “detect missing things.”

---

# 29. What Zero does NOT establish

This should be explicitly written into the theory.

Zero does not establish:

$$
Truth.
$$

Zero does not establish:

$$
Existence.
$$

Zero does not establish:

$$
Falsity.
$$

Zero does not establish:

$$
Completeness.
$$

Zero does not establish:

$$
Determination.
$$

Zero does not establish:

$$
KnowledgeAttribution.
$$

Zero does not establish:

$$
Authorization.
$$

And Zero does not magically discover all unknown unknowns.

---

# 30. The new KnowledgeOS epistemic flow

I would now represent the theory as:

```text
                     INQUIRY I
                         │
                         ▼
                CONTEXT / CONTRACT Γ
                         │
                         ▼
                    CURRENT Kt
                    /         \
                   /           \
                  ▼             ▼
            ZERO LENS        EVALUATION
                  │             │
                  ▼             ▼
              BOUNDARY          EVal
                  │             │
                  └──────┬──────┘
                         ▼
                    DETERMINATION
                         │
                         ▼
                      DECISION
                         │
                         ▼
                    AUTHORIZATION
                         │
                         ▼
                       ACTION
                         │
                         ▼
                    OBSERVATION
                         │
                         ▼
                       Kt+1
                         │
                         └──────► ZERO
```

The important thing is that **Zero is not the final node**.

It is a boundary examination applied to the current epistemic state.

---

# 31. Zero and "Try Your Best"

This now acquires a much sharper meaning.

Instead of:

> produce the most plausible answer,

KnowledgeOS should aim at:

$$
\boxed{
TryBest=
\max(\text{justified epistemic expansion})
+
\text{boundary preservation}.
}
$$

Therefore the system should:

1. discover relevant dimensions;
2. obtain evidence;
3. interpret it;
4. assess it;
5. expose contradictions;
6. expose assumptions;
7. identify unresolved requirements;
8. preserve uncertainty;
9. challenge blind spots;
10. revise \(K_t\).

The objective is not maximum apparent completeness.

It is:

$$
\boxed{
\text{maximum justified knowledge expansion without epistemic overclaiming}.
}
$$

The review explicitly identifies this as a particularly valuable direction for KnowledgeOS. 

---

# 32. What this does to the kernel question

This is where I would be deliberately cautious.

We **cannot yet conclude**:

$$
Zero\in\mathcal K.
$$

We cannot yet conclude:

$$
Zero=\text{primitive}.
$$

We cannot yet conclude:

$$
DetectGap=\pi_{requirement}(Zero).
$$

But we now have a very interesting hypothesis:

$$
\boxed{
DetectGap
$$

may be a **projection of a richer boundary mechanism**, rather than an independent primitive.

For example:

$$
DetectGap_Q(K)
=
\pi_Q(
ZeroLens(K,Q,\Gamma,L)
).
$$

Status:

$$
[PROP].
$$

If this survives testing, it could materially affect the kernel reduction experiment.

---

# 33. The breakthrough hypothesis

After incorporating the review, I would state the breakthrough much more carefully:

> **The breakthrough is not that Zero has been proven to be a meta-epistemic primitive.**
>
> The breakthrough is that the previous Zero/Sat deadlock may have resulted from asking a closure predicate to carry information that belongs to a richer boundary representation.

In mathematical shorthand:

$$
\boxed{
B
\rightarrow
\pi(B)=U
}
$$

may be the correct direction of abstraction, rather than:

$$
\boxed{
U
\rightarrow
\text{attempt to reconstruct }B.
}
$$

This is a major conceptual difference.

---

# 34. What the previous experiment now means

The previous experiment did not merely show:

> “Three-valued Zero is inadequate.”

Its deeper result can now be interpreted as:

$$
\boxed{
\text{A coarse closure/evaluation codomain was being asked to represent heterogeneous epistemic boundary conditions.}
}
$$

The experiment showed:

* candidate `EVal` was not total;
* `value(EVal)` was lossy;
* contradiction representation remained unresolved;
* evaluator provenance matters;
* `Zero_reasoned` and `Zero_weak` did not acquire legitimate empirical separation after invented evaluators were removed;
* composition semantics are not determined merely by choosing a three-valued logic. 

The new Zero Lens gives us a place to preserve those distinctions **before** projection.

---

# 35. What remains open

The following should remain explicitly `[OPEN]`:

$$
Contr
$$

$$
Sat_c
$$

$$
EVal
$$

$$
\succeq
$$

$$
EvidenceRetirement
$$

$$
Observation
$$

$$
TemporalValidity
$$

$$
SemanticEquivalence
$$

$$
RepresentationEquivalence
$$

$$
Factivity
$$

$$
KnowledgeAttribution
$$

$$
ZeroClosure
$$

$$
KernelMembership.
$$

Zero v1.2 does not pretend to solve these.

That is a strength, not a weakness.

---

# 36. Final canonical formulation

If we want one formulation to carry into the next experiments, I recommend freezing this:

> ### **ZERO v1.2**
>
> **Zero is the disciplined, inquiry-relative examination of what a current epistemic representation establishes, does not establish, and may fail to represent, relative to a declared context, contract and examination lens. Its purpose is to expose epistemic boundaries while preserving distinctions among the different reasons for non-establishment.**
>
> Zero does not equate non-representation with non-existence, uncertainty with absence, non-assessment with low confidence, contradiction with invalidity, lack of evidence with evidence of absence, or absence of a known gap with completeness.
>
> Formally:
>
> $$
> \boxed{
> ZL(K_t,I_t,\Gamma_t,L_t)\rightarrow B_t
> }
> $$
>
> where \(B_t\) is a boundary description whose internal semantic structure remains subject to research.
>
> **Zero Lens is distinct from Zero Closure.** Zero Lens examines and exposes the boundary; whether that boundary constitutes closure for a particular inquiry is a separate, currently unresolved semantic problem.

---

## 37. The one sentence I would put into the KnowledgeOS Constitution

If we eventually need the shortest possible invariant:

$$
\boxed{
\textbf{Do not infer from the boundary of representation more than the epistemic state establishes.}
}
$$

And the corresponding anti-reification rule:

$$
\boxed{
\neg Represented(x)\not\Rightarrow\neg Exists(x).
}
$$

And the corresponding anti-collapse rule:

$$
\boxed{
\text{Distinct epistemic boundary conditions must not be collapsed merely because a downstream representation projects them to the same value.}
}
$$

**This is, in my view, the strongest version of Zero that the current evidence supports.**

Most importantly, it turns the previous experiment's failure into a potentially productive result: **we no longer need to force contradiction, missing evidence, unobservability, underdetermination and theory incompleteness into the same `U`/`C` truth-value machinery.** We can first ask whether they are different *boundary conditions*, and only later determine which projections of those conditions a particular evaluator or closure mechanism is entitled to use. 
