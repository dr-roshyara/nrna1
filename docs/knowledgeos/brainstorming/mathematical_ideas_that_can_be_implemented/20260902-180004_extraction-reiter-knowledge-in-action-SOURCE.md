# EXTRACTION: "Knowledge in Action" (Reiter, 2001)

**Relevance to KnowledgeOS:** Very High — This is the definitive formal treatment of the situation calculus, providing the complete mathematical and computational framework for representing and implementing dynamical systems. It directly addresses KnowledgeOS's open questions about **δ (state transition)**, **Boundary (frame problem)**, **actions**, **sensing**, and **implementation**.

---

## Part 1: The Situation Calculus — Formal Foundations

### 1.1 The Knowledge Representation Hypothesis

> "Any mechanically embodied intelligent process will be comprised of structural ingredients that a) we as external observers naturally take to represent a propositional account of the knowledge that the overall process exhibits, and b) independent of such external semantical attribution, play a formal but causal and essential role in engendering the behaviour that manifests that knowledge."

**KnowledgeOS Translation:** The system's behaviour must be causally connected to its explicitly represented knowledge through formal reasoning (entailment).

---

### 1.2 Intuitive Ontology

| Concept | Definition | KnowledgeOS Mapping |
|---------|------------|---------------------|
| **Situation** | A finite sequence of actions (history) | \(K_t\) state representation |
| **\(S_0\)** | Initial situation (empty action sequence) | Initial knowledge state |
| **\(do(a, s)\)** | Successor situation after action \(a\) in \(s\) | \( \delta(K_t, e_t) \rightarrow K_{t+1} \) |
| **Fluent** | Predicate/function whose value varies by situation | State components in \(K_t\) |
| **Poss(a, s)** | Action \(a\) can be performed in situation \(s\) | Precondition for state transition |

**Key Insight:** Situations are **histories**, not states. Two situations may have identical fluent values but be different histories.

---

### 1.3 Foundational Axioms for Situations

**Unique Names for Situations:**
\[
do(a_1, s_1) = do(a_2, s_2) \supset a_1 = a_2 \land s_1 = s_2
\]

**Induction Axiom (Second-Order):**
\[
(\forall P).P(S_0) \land (\forall a, s)[P(s) \supset P(do(a, s))] \supset (\forall s)P(s)
\]

**Subhistory Axioms:**
\[
\neg s \sqsubset S_0
\]
\[
s \sqsubset do(a, s') \equiv s \sqsubseteq s'
\]

**KnowledgeOS Translation:** These are the foundational axioms for the state space. The induction axiom is second-order, which is why the system is not fully first-order decidable.

---

### 1.4 Executable Situations

**Definition:**
\[
executable(s) \stackrel{def}{=} (\forall a, s^*).do(a, s^*) \sqsubseteq s \supset Poss(a, s^*)
\]

**Inductive Characterization:**
\[
executable(do(a, s)) \equiv executable(s) \land Poss(a, s)
\]

**KnowledgeOS Translation:** A state is "executable" if all actions leading to it satisfied their preconditions. This maps to the distinction between possible states and actual states.

---

## Part 2: The Frame Problem — Formal Solution

### 2.1 What the Frame Problem Is

> "Frame axioms specify the action invariants of the domain, i.e., those fluents unaffected by the performance of an action."

**The Problem:**
- For \(A\) actions and \(F\) fluents, expect \(2 \times A \times F\) frame axioms
- A 100-action, 100-fluent domain requires roughly 20,000 frame axioms

**What Counts as a Solution:**
1. Systematic procedure to generate frame axioms from effect axioms
2. Parsimonious representation (compact axioms)

---

### 2.2 Effect Axioms → Normal Form

**Positive Effect Axiom Normal Form:**
\[
\gamma_F^+(\vec{x}, a, s) \supset F(\vec{x}, do(a, s))
\]

**Negative Effect Axiom Normal Form:**
\[
\gamma_F^-(\vec{x}, a, s) \supset \neg F(\vec{x}, do(a, s))
\]

**Transformation Method:** Each effect axiom of the form \(\phi_F^+ \supset F(\vec{t}, do(\alpha, s))\) is rewritten as:
\[
(\exists \vec{y})[a = \alpha \land \vec{x} = \vec{t} \land \phi_F^+] \supset F(\vec{x}, do(a, s))
\]

Then all positive effect axioms for a fluent are combined:
\[
[\Psi_F^{(1)} \lor \cdots \lor \Psi_F^{(k)}] \supset F(\vec{x}, do(a, s))
\]

---

### 2.3 Causal Completeness Assumption

> "Axioms (3.7) and (3.8) respectively characterize all the conditions under which action \(a\) causes \(F\) to become true (respectively, false) in the successor situation."

**Explanation Closure Axioms:**
\[
F(\vec{x}, s) \land \neg F(\vec{x}, do(a, s)) \supset \gamma_F^-(\vec{x}, a, s)
\]
\[
\neg F(\vec{x}, s) \land F(\vec{x}, do(a, s)) \supset \gamma_F^+(\vec{x}, a, s)
\]

---

### 2.4 Successor State Axiom

**For Relational Fluents:**
\[
F(\vec{x}, do(a, s)) \equiv \gamma_F^+(\vec{x}, a, s) \lor (F(\vec{x}, s) \land \neg \gamma_F^-(\vec{x}, a, s))
\]

**For Functional Fluents:**
\[
f(\vec{x}, do(a, s)) = y \equiv \gamma_f(\vec{x}, y, a, s) \lor (f(\vec{x}, s) = y \land \neg (\exists y')\gamma_f(\vec{x}, y', a, s))
\]

**KnowledgeOS Translation:** This is the formal mechanism for \( \delta(K_t, e_t) \rightarrow K_{t+1} \). Each state component has:
- **Positive effect condition**: When it becomes true
- **Negative effect condition**: When it becomes false
- **Persistence condition**: When it stays the same

---

## Part 3: Regression — The Key Computational Mechanism

### 3.1 What Regression Does

> "The intuition underlying regression is this: Suppose we want to prove that a sentence \(W\) is entailed by some basic action theory. Suppose further that \(W\) mentions a relational fluent atom \(F(\vec{t}, do(\alpha, \sigma))\)... we can easily determine a logically equivalent sentence \(W'\) by substituting \( \Phi_F(\vec{t}, \alpha, \sigma) \) for \(F(\vec{t}, do(\alpha, \sigma))\) in \(W\)."

**KnowledgeOS Translation:** Regression is the mechanism for evaluating what is true in a future state without simulating all intermediate states.

---

### 3.2 Regression Operator Definition

**For Poss atoms:**
\[
\mathcal{R}[Poss(A(\vec{t}), \sigma)] = \mathcal{R}[\Pi_A(\vec{t}, \sigma)]
\]
(Replace with the precondition axiom's right-hand side.)

**For Fluent atoms:**
\[
\mathcal{R}[F(\vec{t}, do(\alpha, \sigma))] = \mathcal{R}[\Phi_F(\vec{t}, \alpha, \sigma)]
\]
(Replace with the successor state axiom's right-hand side.)

---

### 3.3 The Regression Theorem

> "Suppose \(W\) is a regressable sentence... Then \( \mathcal{D} \models W \) iff \( \mathcal{D}_{S_0} \cup \mathcal{D}_{una} \models \mathcal{R}[W] \)."

**KnowledgeOS Translation:** To evaluate a regressable sentence, regress it to the initial situation and prove it using only the initial database and unique names axioms. No successor state axioms or foundational axioms are needed.

**This is the theoretical foundation for efficient query evaluation.**

---

## Part 4: Basic Action Theories

### 4.1 The Structure

\[
\mathcal{D} = \Sigma \cup \mathcal{D}_{ss} \cup \mathcal{D}_{ap} \cup \mathcal{D}_{una} \cup \mathcal{D}_{S_0}
\]

| Component | Description |
|-----------|-------------|
| \(\Sigma\) | Foundational axioms for situations |
| \(\mathcal{D}_{ss}\) | Successor state axioms (one per fluent) |
| \(\mathcal{D}_{ap}\) | Action precondition axioms (one per action) |
| \(\mathcal{D}_{una}\) | Unique names axioms for actions |
| \(\mathcal{D}_{S_0}\) | Initial database (sentences uniform in \(S_0\)) |

---

### 4.2 Uniform Formulas

> "A formula is uniform in \(\sigma\) iff it does not mention the predicates \(Poss\) or \(\sqsubset\), it does not quantify over variables of sort situation, it does not mention equality on situations, and whenever it mentions a term of sort situation in the situation argument position of a fluent, then that term is \(\sigma\)."

**KnowledgeOS Translation:** Uniform formulas are those that do not mention the future. They are the "current state" formulas.

---

### 4.3 Relative Satisfiability Theorem

> "A basic action theory \(\mathcal{D}\) is satisfiable iff \(\mathcal{D}_{una} \cup \mathcal{D}_{S_0}\) is."

**KnowledgeOS Translation:** If the initial database and unique names axioms are satisfiable, adding the foundational axioms, action preconditions, and successor state axioms cannot introduce unsatisfiability.

---

## Part 5: Golog — Complex Actions and Procedures

### 5.1 The Do Macro

**Primitive Action:**
\[
Do(a, s, s') \stackrel{def}{=} Poss(a[s], s) \land s' = do(a[s], s)
\]

**Test Action:**
\[
Do(\phi?, s, s') \stackrel{def}{=} \phi[s] \land s = s'
\]

**Sequence:**
\[
Do(\delta_1; \delta_2, s, s') \stackrel{def}{=} (\exists s'')Do(\delta_1, s, s'') \land Do(\delta_2, s'', s')
\]

**Nondeterministic Choice:**
\[
Do(\delta_1 \mid \delta_2, s, s') \stackrel{def}{=} Do(\delta_1, s, s') \lor Do(\delta_2, s, s')
\]

**Nondeterministic Choice of Arguments:**
\[
Do((\pi x)\delta(x), s, s') \stackrel{def}{=} (\exists x)Do(\delta(x), s, s')
\]

**Nondeterministic Iteration:**
\[
Do(\delta^*, s, s') \stackrel{def}{=}
(\forall P).\{(\forall s_1)P(s_1, s_1) \land
(\forall s_1, s_2, s_3)[Do(\delta, s_1, s_2) \land P(s_2, s_3) \supset P(s_1, s_3)]\}
\supset P(s, s')
\]

**KnowledgeOS Translation:** Golog provides a programming language over the situation calculus. Complex actions are **macros** that expand to situation calculus formulas. The nondeterministic iteration uses second-order logic (transitive closure).

---

### 5.2 Programs and Executable Situations

> "Every successful program evaluation leads to an executable situation."

**Theorem:**
\[
\Sigma \models (\forall s).Do(\delta, S_0, s) \supset executable(s)
\]

**KnowledgeOS Translation:** Golog programs are guaranteed to produce executable action sequences — plans that are possible according to the domain axioms.

---

### 5.3 Proving Program Properties

**Correctness:**
\[
Axioms \models (\forall s).Do(\delta, S_0, s) \supset P(s)
\]

**Termination:**
\[
Axioms \models (\exists s)Do(\delta, S_0, s)
\]

**Induction Principle for While Loops:**
\[
(\forall P, s).P(s) \land (\forall s', s'')[P(s') \land \phi[s'] \land Do(\alpha, s', s'') \supset P(s'')]
\supset (\forall s').Do(\text{while } \phi \text{ do } \alpha, s, s') \supset P(s')
\]

---

## Part 6: Time, Concurrency, and Processes

### 6.1 Sequential, Temporal Situation Calculus

**Key Idea:** Add time argument to instantaneous actions:
- `startMeeting(Susan, t)` — action occurring at time \(t\)
- `endMeeting(Susan, t)` — action ending at time \(t\)

**New Axiom:**
\[
start(do(a, s)) = time(a)
\]

**Executable Situations with Time:**
\[
executable(s) \stackrel{def}{=} (\forall a, s^*).do(a, s^*) \sqsubseteq s \supset Poss(a, s^*) \land start(s^*) \leq time(a)
\]

---

### 6.2 Concurrent Actions

**Representation:** Concurrent actions = sets of simple actions
\[
do(\{startMeeting(Sue), collide(A, B)\}, S_0)
\]

**Successor State Axiom for Concurrency:**
\[
pickingUp(x, do(c, s)) \equiv startPickup(x) \in c \lor (pickingUp(x, s) \land endPickup(x) \notin c)
\]

**The Precondition Interaction Problem:**
\[
Poss(startMoveLeft, s) \equiv \neg movingLeft(s)
\]
\[
Poss(startMoveRight, s) \equiv \neg movingRight(s)
\]

But \(Poss(\{startMoveLeft, startMoveRight\}, s)\) should be false. The converse of the foundational axiom need not hold.

---

### 6.3 Natural Actions

**Definition:** Natural actions are those that must occur at their predicted times.

**Precondition for Natural Action:**
\[
Poss(bounce(t), s) \equiv isFalling(s) \land
\{height(s) + vel(s)[t - start(s)] - \frac{1}{2}G[t - start(s)]^2 = 0\}
\]

**Executable Situations for Natural Actions:**
\[
executable(do(c, s)) \equiv executable(s) \land Poss(c, s) \land start(s) \leq time(c) \land
(\forall a).natural(a) \land Poss(a, s) \land a \notin c \supset time(c) < time(a)
\]

**The Natural-World Assumption:** \( (\forall a)natural(a) \) — all actions are natural.

**Theorem:** In natural worlds, the evolution is deterministic:
\[
executable(do(c, s)) \land executable(do(c', s)) \land NWA \supset c = c'
\]

---

## Part 7: Reactive Golog (RGolog)

### 7.1 Interrupts as Condition-Action Rules

**Rule Representation:**
\[
\varphi \rightarrow \alpha \quad \text{compiles to} \quad (\pi \vec{x})[\varphi?; \alpha]
\]

**General Pattern for \(n\) Rules:**
```
proc interrupts()
    (π x1)[φ1(x1)?; α1(x1)] |
    ...
    (π xn)[φn(xn)?; αn(xn)] |
    ¬[(∃x1)φ1(x1) ∨ ... ∨ (∃xn)φn(xn)]?
endProc
```

### 7.2 DoR Semantics

\[
DoR(A, Rules, s, s') \stackrel{def}{=}
(\forall Q).(\forall s_1, s_2)[Do1(Rules, Q, s_1, s_2) \supset Q(s_1, s_2)]
\supset Do1(A, Q, s, s')
\]

**Interpretation:** \(DoR\) interleaves the execution of \(A\) with \(Rules\), allowing exogenous actions and interrupts after each primitive action.

---

## Part 8: Knowledge and Sensing

### 8.1 Knowledge in the Situation Calculus

**Accessibility Relation:** \(K(s', s)\) means \(s'\) is accessible from \(s\) (possible alternative world).

**Knowledge Definition:**
\[
Knows(\varphi, \sigma) \stackrel{def}{=} (\forall s').K(s', \sigma) \supset \varphi[s']
\]

**Knowledge of a Referent:**
\[
KRef(t, \sigma) \stackrel{def}{=} (\exists x)(\forall s').K(s', \sigma) \supset x = t[s']
\]

---

### 8.2 Successor State Axiom for K

**For Non-Knowledge-Producing Actions:**
\[
a \neq sense \land a \neq read \supset
[K(s', do(a, s)) \equiv (\exists s^*).s' = do(a, s^*) \land K(s^*, s)]
\]

**For Sense Actions:**
\[
a = senseF(\vec{x}) \supset
[K(s', do(a, s)) \equiv (\exists s^*).s' = do(a, s^*) \land K(s^*, s) \land F(\vec{x}, s^*) \equiv F(\vec{x}, s)]
\]

**Complete Successor State Axiom for K:**
\[
K(s', do(a, s)) \equiv
(\exists s^*).s' = do(a, s^*) \land K(s^*, s) \land
(\forall \vec{x}_1)[a = sense_{\psi_1}(\vec{x}_1) \supset \psi_1(\vec{x}_1, s^*) \equiv \psi_1(\vec{x}_1, s)] \land \cdots
\]

---

### 8.3 Accessibility Properties and Knowledge

| Property | Axiom | Knowledge Property |
|----------|-------|-------------------|
| **Reflexivity** | \( (\forall s)K(s, s) \) | Knowledge is truth: \(Knows(\varphi, s) \supset \varphi[s]\) |
| **Transitivity** | \(K(s, s') \land K(s', s'') \supset K(s, s'')\) | Positive introspection: \(Knows(\varphi, s) \supset Knows(Knows(\varphi), s)\) |
| **Symmetry** | \(K(s, s') \supset K(s', s)\) | No automatic property alone |
| **Euclidean** | \(K(s', s) \land K(s'', s) \supset K(s', s'')\) | Negative introspection: \(\neg Knows(\varphi, s) \supset Knows(\neg Knows(\varphi), s)\) |

**Key Theorem:** If any of these properties holds in initial situations, it holds in all situations (due to the successor state axiom for K).

---

### 8.4 Knowledge-Based Programming

**Reduction of Knowledge to Provability:**
\[
\mathcal{D} \models Knows(\varphi, S_0) \iff \mathcal{D}_{una} \cup \{\kappa[S_0]\} \models \varphi[S_0]
\]

**Dynamic Closed-World Assumption:**
\[
closure(\mathcal{D} \cup \Sigma(\sigma)) =
\mathcal{D} \cup \Sigma(\sigma) \cup \{\neg Knows(\theta, S_0) \mid \theta \text{ is objective and } \mathcal{D} \cup \Sigma(\sigma) \not\models Knows(\theta, S_0)\}
\]

---

## Part 9: Probability and Decision Theory

### 9.1 Stochastic Actions

**Decomposition:**
- Stochastic action \(giveCoffee(p)\) is under agent control
- Nature chooses one of \(giveCoffeeS(p)\) or \(giveCoffeeF(p)\)

**Choice Abbreviation:**
\[
choice(giveCoffee(p), a) \stackrel{def}{=} a = giveCoffeeS(p) \lor a = giveCoffeeF(p)
\]

**Probability Definition:**
\[
prob(a, \beta, s) = p \stackrel{def}{=} choice(\beta, a) \land Poss(a, s) \land p = prob_0(a, \beta, s) \lor
[\neg choice(\beta, a) \lor \neg Poss(a, s)] \land p = 0
\]

---

### 9.2 stGolog — Stochastic Golog

**stDo for Stochastic Actions:**
\[
stDo(\alpha ; \beta, p, s, s') \stackrel{def}{=}
\neg(\exists a)[choice(\alpha, a) \land Poss(a, s)] \land s = s' \land p = 1 \lor
(\exists a).choice(\alpha, a) \land Poss(a, s) \land
(\exists p').stDo(\beta, p', do(a, s), s') \land p = prob(a, \alpha, s) * p'
\]

**Probability of a Sentence:**
\[
probF(\psi, \gamma) \stackrel{def}{=} \sum_{\{(p, \sigma)|\mathcal{D} \models stDo(\gamma; nil, p, S_0, \sigma) \land \psi[\sigma]\}} p
\]

---

### 9.3 Markov Decision Processes

**Value Function:**
\[
value(do(a, \sigma)) \stackrel{def}{=} value(\sigma) + reward(a, \sigma) - cost(a, \sigma)
\]

**Expected Value:**
\[
eValue(\gamma) \stackrel{def}{=}
\sum_{\{s|\mathcal{D} \models init(s)\}} initProb(s) \times
\sum_{\{(p, \sigma)|\mathcal{D} \models stDo(\gamma; nil, p, s, \sigma)\}} value(\sigma) \times p
\]

**Policies:** stGolog programs with conditional branching on sense outcomes.

---

## Part 10: Progression and STRIPS

### 10.1 Progression Definition

A set of sentences \(\mathcal{D}_{S_\alpha}\) is a **progression** of the initial database \(\mathcal{D}_{S_0}\) to \(S_\alpha\) iff:
1. Sentences are uniform in \(S_\alpha\)
2. For every model of the progressed theory, there is a model of the original theory with identical future behavior

**Key Result:** Progression is **not always first-order definable**. Finite progression is **second-order definable**.

---

### 10.2 STRIPS as Progression

**Relational STRIPS Database:**
\[
F(\vec{x}) \equiv \vec{x} = \vec{C}_1 \lor \cdots \lor \vec{x} = \vec{C}_n
\]

**STRIPS Operator:**
- Precondition: \(P\)
- Delete list: \(D\)
- Add list: \(A\)

**Successor Database:** Remove \(D\), add \(A\)

**Correctness Theorem:** For suitable basic action theories, the STRIPS plan is equivalent to the situation calculus plan.

---

## Part 11: Key Concepts for KnowledgeOS

### 11.1 What This Book Confirms

| KnowledgeOS Concept | Book's Confirmation |
|---------------------|---------------------|
| **δ** | Successor state axioms: \(F(\vec{x}, do(a, s)) \equiv \gamma_F^+ \lor (F(\vec{x}, s) \land \neg \gamma_F^-)\) |
| **Boundary** | Frame problem: what does NOT change; persistence conditions |
| **Composition** | Golog operators: sequence, choice, iteration; \(Do(\delta_1;\delta_2, s, s')\) |
| **Knowledge State** | \(K_t\) maps to situations; explicit beliefs in \(\mathcal{D}_{S_0}\) |
| **Sat** | Entailment: \(K_t \models \text{Content}(r)\) |
| **Zero** | Complete knowledge under closed-world assumption |
| **Contr** | Knowledge of contradictions; inconsistency detection |
| **Lifecycle** | Nonmonotonicity: new facts can invalidate old beliefs |

### 11.2 What This Book Adds

| New Insight | KnowledgeOS Application |
|-------------|------------------------|
| **Successor state axioms** | \( \delta \) as a compact transition function |
| **Regression** | Efficient evaluation of future states |
| **Causal completeness** | All effects of actions must be declared |
| **Knowledge-producing actions** | Sense actions affect K fluent only |
| **Natural actions** | Exogenous events with known laws |
| **Reactive Golog** | Interrupts with priorities |
| **Progression** | Forward simulation; STRIPS as progression |
| **stGolog** | Stochastic actions with probabilities |
| **Golog programs** | Complex actions as macros |