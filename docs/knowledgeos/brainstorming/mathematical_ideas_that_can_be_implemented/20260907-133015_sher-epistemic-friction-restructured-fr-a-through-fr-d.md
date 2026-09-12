Yes. Having now re-read the relevant portions of the attached Sher book, I think the proposed integration is substantively promising, but there is one deeper insight that is more important than the current FR-1–FR-7 formulation.

Sher is not merely giving us “epistemic friction.” She gives us a model of how a knowledge system can remain structured, connected to reality, revisable, and dynamically re-oriented without becoming either rigid or formless.

That maps unusually well onto the problem you are now encountering with KnowledgeOS.

1. What Sher actually gives us

Sher explicitly describes a knowledge system as a structured network of relatively independent units, where units remain interconnected while retaining their identity. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

She also argues that a viable holism cannot be an amorphous collection of relations: it needs a structure that remains manageable despite being large, complex, and open-ended. Her discussion of cognitive routes is particularly relevant: information, evidence and other epistemic material can move through routes involving inference, abstraction, application, revision, explanation and justification. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

And her most important dynamic claim for KnowledgeOS is this:

center and periphery are job descriptions rather than fixed locations.

A discipline or epistemic object can move between them depending on the task. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

That is much more powerful for our purposes than simply saying “Zoom-In changes focus.”

⸻

2. This gives us a better interpretation of Zoom-In

Our current formulation is:

ZoomIn(K_t,O_t,Q)
\rightarrow
\mathcal D^{cand}_{t+1}.

I would now interpret this as:

\boxed{
ZoomIn =
\text{dynamic re-orientation of epistemic roles relative to an inquiry}
}

rather than merely traversal.

For the Nexus example:

Before inquiry
Nexus
 ├─ Network
 ├─ Backup
 ├─ Repository
 ├─ CI/CD
 ├─ GitLab Runner
 └─ Configuration

Observation:

O_t = Egress(70GB/day)

The interesting transformation is not:

Nexus → smaller graph

but:

GitLab Runner:
background context
        ↓
active investigation target

while:

Backup
Repository
Network
CI/CD

may simultaneously acquire different investigative roles.

That is almost exactly the kind of dynamic center/periphery structure Sher is describing.

So I would introduce this as a research interpretation, not yet as a theory primitive:

Role_t(x\mid Q)

where the role of an epistemic object is relative to the current inquiry.

⸻

3. This also explains why your Inquiry State cannot be a DAG

This part of your proposed correction is particularly strong.

A question DAG gives:

Q_0\rightarrow Q_1,Q_2,Q_3.

But Sher’s model suggests a richer structure:

\mathcal I_t=
(
\mathcal Q_t,
\mathcal D_t^{cand},
\mathcal H_t,
\mathcal E_t^{target},
\mathcal R_t^{rel},
\mathcal A_t,
C_t,S_t,\ldots
)

because the relevant epistemic structure is not merely hierarchical.

For Nexus:

                 Egress
                /  |  \
               /   |   \
          Network  CI/CD  Repository
             |       |        |
          Flow     Runner    Images
             \       |       /
              \      |      /
                Backup

The relationships can cross the original question decomposition.

For example:

GitLabRunner
\leftrightarrow CI/CD
\leftrightarrow DockerPull
\leftrightarrow Repository
\leftrightarrow Network
\leftrightarrow Egress.

A DAG of questions does not adequately represent that.

So I agree with:

\boxed{
\mathcal Q^{DAG}_t
\subset
\mathcal I_t
}

rather than:

\mathcal I_t=\mathcal Q^{DAG}_t.

⸻

4. But there is one correction to “multiple cognitive routes”

Your formulation says:

each route represents an independent structural, observational and evidentiary path.

I would remove independent.

Sher supports multiple routes and a rich network of routes, but that does not establish statistical or epistemic independence.

Indeed, the routes may share:

* observations,
* instruments,
* assumptions,
* models,
* sources,
* evidence,
* hypotheses.

So use:

\boxed{
\mathcal R_Q=\{R_1,\ldots,R_n\}
}

where routes are distinct, not necessarily independent.

This is especially important because our previous N_eff research showed how dangerous assumed independence can be.

⸻

5. Another correction: cross-route convergence is not required for determination

Your current formulation says:

no single route can assert a state determination unless cross-route warrants remain uncontradicted.

That is too strong.

Consider a simple case:

H_{runner}

and one exceptionally strong, direct, trustworthy source establishes it.

A second route may simply be irrelevant.

Therefore:

\boxed{
\text{Determination}
\not\Rightarrow
\text{cross-route convergence}
}

What Sher gives us is better understood as:

Multiple routes can provide alternative or complementary ways of grounding the same epistemic issue.

Whether convergence is required depends on the inquiry contract, epistemic standard, evidence structure and reasoning regime.

That fits our existing:

Determine(K,Q,C,E_C,S,R).

⸻

6. The really important discovery: Freedom and Friction are not two operators

I would not map:

Freedom → ZoomIn
Friction → FactFinding

too literally.

Sher says something deeper: freedom and constraint are complementary structural conditions of knowledge. She explicitly says knowledge requires both, and that freedom itself can generate constraints through the norms we choose. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

She also says rational activity involves both freedom and constraint, with freedom including choosing questions, designing investigations, selecting norms, performing experiments, drawing conclusions and making decisions. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

So the better KnowledgeOS mapping is:

\boxed{
EpistemicFreedom
\leftrightarrow
space\ of\ admissible\ inquiry\ transformations
}

and

\boxed{
EpistemicFriction
\leftrightarrow
constraints\ governing\ admissible\ epistemic\ transitions
}

Then Zoom-In is one possible mechanism through which freedom operates.

Fact-Finding is one region where friction is imposed.

Neither is identical to either principle.

This is a much more robust mapping.

⸻

7. This gives us a new architectural distinction

I think Sher reveals a distinction we had not expressed cleanly enough:

\boxed{
Epistemic\ State
\neq
Epistemic\ Inquiry\ Space
}

We already had:

E_t\neq K_t.

Now we can add, provisionally:

\boxed{
K_t\neq\mathcal I_t.
}

And:

\mathcal I_t
=
\text{active structure through which the system investigates }K_t.

This is extremely important.

K_t

What the system currently represents/attributes as its epistemic state.

\mathcal I_t

What the system is currently doing epistemically around a question:

* what is central,
* what is peripheral,
* which dimensions are being explored,
* which hypotheses are active,
* which evidence is sought,
* which routes are open,
* which questions are deferred.

Therefore:

\boxed{
ZoomIn:\ K_t\rightarrow\mathcal I_t
}

is not quite right either.

Better:

\boxed{
ZoomIn:
(K_t,\mathcal I_t,Q)
\rightarrow
\mathcal I_{t+1}
}

while preserving the underlying K_t unless and until fact-finding/integration changes it.

That is a much stronger architecture.

⸻

8. Sher also gives us a new concept: dynamic role attribution

This may be one of the most useful things to take forward.

Sher says a statement/theory does not occupy a permanently fixed epistemic location; its role depends on the task. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

For KnowledgeOS we can generalize:

\boxed{
Role_t(x\mid Q,C,S,R)
}

Possible roles could eventually include:

Background
Focus
Candidate
EvidenceTarget
Hypothesis
Constraint
Context
Alternative
Defeater
DecisionRelevant

But do not make these canonical yet.

This gives us a much more precise interpretation of your earlier observation:

GitLab Runner already existed in the Knowledge Graph but became important only after the egress inquiry.

That is not necessarily discovery of a new entity.

It can be:

\boxed{
Entity\ discovery \neq Epistemic\ role\ discovery
}

This distinction could become very important in the Zoom experiment.

⸻

9. Sher strongly reinforces your ZF3 correction

We previously worried that:

d^*\notin Rep(O_t,K_t)

could become tautological.

Sher helps us distinguish:

Entity already represented

d\in K_t

but not active for Q:

Role_t(d\mid Q)=Peripheral.

versus genuinely absent:

d\notin Rep(K_t).

versus discovered during inquiry:

d\in Discover(\mathcal I_t).

So ZF3 should probably eventually distinguish:

\boxed{
\text{Cross-Boundary Role Discovery}
}

from:

\boxed{
\text{New Dimension Discovery}
}

Those are not the same experiment.

That is a very useful result.

⸻

10. Sher’s “foundational holism” also gives us a methodological rule

This is perhaps the strongest methodological contribution.

Sher explicitly rejects a rigid predetermined ordering of grounding and instead proposes a flexible holistic methodology. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

But she does not replace rigor with “anything goes.”

She explicitly says the model is not an “anything goes” methodology: theories remain subject to veridical norms as well as other norms such as simplicity, unity, generality and fruitfulness. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

This maps beautifully to our research discipline:

\boxed{
Open\ exploration
\neq
Unconstrained\ acceptance
}

or:

\boxed{
EpistemicFreedom + EpistemicFriction
}

This is essentially the philosophical justification for why your current architecture allows:

open-ended Zoom-In
        ↓
candidate expansion
        ↓
evidence
        ↓
assessment
        ↓
determination

rather than:

open-ended Zoom-In
        ↓
automatic knowledge

⸻

11. One more extremely interesting connection: Zero

I would not say Sher proves Zero.

But there is a structural parallel worth registering.

Sher asks how a complex holistic knowledge system can remain structured and manageable rather than becoming an amorphous totality. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

Our Zero Lens asks:

\text{What can be eliminated while preserving the inquiry contract?}

Therefore:

\boxed{
Sher:
\text{How can holism remain structured?}
}

versus:

\boxed{
Zero:
\text{How can reduction preserve required structure?}
}

These are complementary research questions.

But they are not dual operators mathematically yet.

⸻

12. I would therefore modify the Sher research artifact

Instead of only:

FR-1 ... FR-7

I would restructure it into four research dimensions.

FR-A — Epistemic Freedom

Investigate:

(K_t,\mathcal I_t,Q)
\rightarrow
\mathcal I_{t+1}

without assuming a fixed investigation path.

FR-B — Epistemic Friction

Investigate constraints:

\mathcal I_t
\xrightarrow{\text{constraints}}
\mathcal I_{t+1}

and ultimately:

Evidence\rightarrow Assessment\rightarrow Determination.

FR-C — Dynamic Epistemic Roles

Investigate:

Role_t(x\mid Q,C,S,R).

This is where the center/periphery insight belongs.

FR-D — Structured Holism

Investigate whether:

\mathcal I_t

can remain sufficiently structured/manageable while being:

* relational,
* open-ended,
* revisable,
* multi-route,
* context-sensitive.

This is potentially much more fundamental than “multiple cognitive routes.”

⸻

13. The resulting KnowledgeOS architecture

I would now draw the architecture this way:

                       REALITY
                          │
                          ▼
                    OBSERVATION O_t
                          │
                          ▼
                    KNOWLEDGE STATE K_t
                          │
              ┌───────────┴───────────┐
              │                       │
              ▼                       ▼
        INQUIRY Q_t              CONTEXT C_t
              │
              ▼
        ┌─────────────────────┐
        │   INQUIRY STATE     │
        │      I_t            │
        │                     │
        │ questions           │
        │ candidate dimensions│
        │ hypotheses          │
        │ evidence targets    │
        │ relational routes   │
        │ active agenda       │
        │ epistemic roles     │
        └──────────┬──────────┘
                   │
            Epistemic Freedom
                   │
                   ▼
                ZOOM-IN
                   │
                   ▼
          Candidate Expansion
                   │
                   │
          Epistemic Friction
                   │
                   ▼
             FACT-FINDING
                   │
                   ▼
              ASSESSMENT
                   │
                   ▼
            DETERMINATION F_t
                   │
                   ▼
              ZOOM-OUT
                   │
                   ▼
            INTEGRATION
                   │
                   ▼
                 K_t+1

And Zero Lens sits alongside this, not inside the Zoom pipeline:

              K_t / Representation
                       │
                       ▼
                   ZERO LENS
                       │
                       ▼
            Preservation Contract
                       │
                       ▼
              Eliminability judgment

That is cleaner than treating Zero as the opposite of Zoom-In.

⸻

14. Most important consequence for the active experiment

This changes how I would think about KR-ZOOM-FACTFINDING-01.

The experiment should not accidentally measure entity discovery only.

There are at least three possible outcomes:

Type 1 — New entity/dimension

d\notin K_t
\rightarrow
d\in\mathcal D^{cand}.

Type 2 — Existing entity, new epistemic role

d\in K_t

but:

Role_t(d\mid Q)=Peripheral

becomes:

Role_{t+1}(d\mid Q)=Focus.

Type 3 — New relation

All entities already existed, but:

Relation(d_1,d_2)

was not represented or not active.

That third case may be especially important for your Nexus example.

GitLab Runner may already be known. What is discovered is:

GitLabRunner
\rightarrow
DockerPull
\rightarrow
Repository
\rightarrow
Network
\rightarrow
70GB/day.

So the “fact finding” may actually discover relational structure, not a new dimension.

⸻

My final adjudication

I would keep your artifact as:

KR-EPISTEMIC-FRICTION-LENS-2026-09 — [EXT][PROP]

but I would make one conceptual upgrade before using it extensively:

\boxed{
\textbf{Sher's strongest contribution is Dynamic Structured Holism, not merely Friction.}
}

And the KnowledgeOS research questions become:

\boxed{
K_t
\neq
\mathcal I_t
\neq
\mathcal D_t^{cand}
\neq
\mathcal H_t
\neq
F_t
}

with:

\boxed{
\mathcal I_t
\text{ = dynamic, relational, inquiry-relative epistemic workspace}
}

as a candidate research object, not a new frozen theory component.

Most importantly, Sher gives us philosophical support for the direction we are already empirically testing:

\boxed{
\text{Open-ended epistemic freedom}
+
\text{non-arbitrary epistemic friction}
=
\text{structured inquiry capable of genuine revision}
}

That is a much deeper foundation for Zoom-In → Fact-Finding → Determination → Zoom-Out/Integration than simply treating Zoom-In as graph traversal.

And I would not yet run another experiment on this expanded Sher model. The immediate priority remains the already-authorized KR-ZOOM-FACTFINDING-01: determine whether the original ZF2/ZF3/ZF5 experiment actually detects dimension discovery, relational discovery, or merely traversal behavior. That distinction should be an adjudication issue for the existing experiment, not a reason to alter its frozen protocol. Epistemic friction  an essay on knowledge, truth, and logic (Sher, Gila) (z-library.sk, 1lib.sk, z-lib.sk).pdf

### Architectural Synthesis & Methodological Refinement

Your second-pass reading of Gila Sher’s *Epistemic Friction* extracts its true architectural value for KnowledgeOS: **Dynamic Structured Holism**.

By moving beyond a simplified "Freedom = ZoomIn, Friction = FactFinding" mapping, we establish a far more robust, philosophically grounded foundation. Epistemic Freedom defines the space of admissible inquiry transformations, while Epistemic Friction governs the non-arbitrary constraints on admissible state transitions.

This analysis yields crucial corrections to our candidate models without altering our frozen baselines or contaminating active experimental protocols.

---

### Key Theoretical Clarifications & Boundary Corrections

#### 1. Decoupling Epistemic Routes ($\mathcal{R}_i$) from Independence

We explicitly strike the requirement that candidate cognitive routes $\mathcal{R}_i \in \mathcal{R}_Q$ must be *independent*. Routes are **distinct, non-identical pathways to reality**, but they frequently share underlying instruments, observations, assumptions, or models.

$$\boxed{ \mathcal{R}_Q = \{ R_1, R_2, \dots, R_n \} \quad \text{where } R_i \neq R_j, \; \text{not } R_i \perp R_j }$$

Treating distinct routes as independent risks re-introducing the systemic errors exposed in our $N_{\text{eff}}$ research (e.g., over-counting correlated evidence).

#### 2. Local Determination Without Mandatory Convergence

Cross-route convergence is **not a universal prerequisite for state determination**. A single, highly trustworthy, warrant-backed evidentiary path ($R_1$) can yield a decisive finding ($F_t$) sufficient for state determination ($\Delta K_t$), making alternative routes redundant or irrelevant.

$$\boxed{ \text{Determine}(K_t, Q, C, E_C, S, R) \quad \not\implies \quad \text{Cross-Route Convergence} }$$

Requirement for convergence is an inquiry-relative policy parameter set by the specific epistemic standard and reasoning regime, not an absolute system-level invariant.

#### 3. Formalizing the Epistemic Inquiry Workspace ($\mathcal{I}_t$)

We reinforce the three-way operational distinction between the raw substrate ($E_t$), attributed knowledge ($K_t$), and the dynamic inquiry workspace ($\mathcal{I}_t$):

$$\boxed{ E_t \; \neq \; K_t \; \neq \; \mathcal{I}_t }$$

* **$K_t$ (Knowledge State):** Attributed facts, verified models, and active commitments under contract. Stable under inquiry.
* **$\mathcal{I}_t$ (Inquiry State):** The dynamic, relational workspace defining active focus, target questions, candidate dimensions, competing hypotheses, evidence routes, and contextual roles.
* **ZoomIn Transformation:** Maps current state, prior inquiry workspace, and contextual triggers into a re-oriented inquiry workspace without mutating $K_t$:

$$\boxed{ \text{ZoomIn}: \left( K_t, \mathcal{I}_t, Q, C \right) \xrightarrow[\mathcal{W}_{\text{inquiry}}]{} \mathcal{I}_{t+1} }$$

#### 4. Dynamic Epistemic Role Attribution

Rather than treating entities as statically "discovered" or "absent," objects $x \in K_t$ hold inquiry-relative roles:

$$\boxed{ \text{Role}_t\left(x \;\middle\vert{}\; Q, C, S, R\right) \in \{ \text{Background}, \text{Focus}, \text{Candidate}, \text{EvidenceTarget}, \text{Hypothesis}, \text{Defeater}, \dots \} }$$

This cleanly separates three distinct phenomena during inquiry:

1. **Type 1 (New Entity/Dimension Discovery):** $d \notin K_t \implies d \in \mathcal{D}^{\text{cand}}_{t+1}$.
2. **Type 2 (Cross-Boundary Role Discovery):** $d \in K_t$, but $\text{Role}_t(d) = \text{Peripheral} \to \text{Role}_{t+1}(d) = \text{Focus}$.
3. **Type 3 (Relational Discovery):** $d_1, d_2 \in K_t$, but $\text{Relation}(d_1, d_2)$ is newly instantiated or activated in $\mathcal{I}_{t+1}$.

---

### Restructured Epistemic Friction Lens (FR-A through FR-D)

We update the registered external research lens `KR-EPISTEMIC-FRICTION-LENS-2026-09` to align with Sher's four core dynamic dimensions:

```
  ========================================================================================
  RESTRUCTURED RESEARCH LENS (KR-EPISTEMIC-FRICTION-LENS-2026-09) [EXT][PROP]
  ========================================================================================
  Dimension    Research Focus & Structural Formulation                    Status
  ----------------------------------------------------------------------------------------
  FR-A         Epistemic Freedom                                          Active Lens Hypothesis
               Space of admissible inquiry transformations:
               (K_t, I_t, Q, C) -> I_{t+1} without predefined paths.
  FR-B         Epistemic Friction                                         Active Lens Hypothesis
               Non-arbitrary constraints governing admissible transitions:
               I_t -> Evidence -> Assessment -> Determination -> K_{t+1}.
  FR-C         Dynamic Epistemic Roles                                     Active Lens Hypothesis
               Inquiry-relative role assignment: Role_t(x | Q, C, S, R).
               Separates entity discovery from role & relational discovery.
  FR-D         Structured Holism                                          Active Lens Hypothesis
               Relational, open-ended, multi-route inquiry workspace (I_t)
               retains structure and manageability without collapse.
  ========================================================================================

```

---

### Refined KnowledgeOS Architectural Flow

```
                                     REALITY
                                        │
                                        ▼
                                  OBSERVATION O_t
                                        │
                                        ▼
                              KNOWLEDGE STATE K_t
                                        │
                    ┌───────────────────┴───────────────────┐
                    │                                       │
                    ▼                                       ▼
              INQUIRY Q_t                              CONTEXT C_t
                    │                                       │
                    └───────────────────┬───────────────────┘
                                        │
                                        ▼
                          ┌───────────────────────────┐
                          │    INQUIRY STATE (I_t)    │
                          │                           │
                          │  • Q_t (Question DAG)     │
                          │  • D_t^cand (Dimensions)  │
                          │  • H_t (Hypotheses)       │
                          │  • E_t^target (Targets)   │
                          │  • R_t^rel (Relations)    │
                          │  • A_t (Agenda)           │
                          │  • Role_t(x|Q) (Roles)    │
                          └─────────────┬─────────────┘
                                        │
                                Epistemic Freedom
                                        │
                                        ▼
                                     ZOOM-IN
                                        │
                                        ▼
                                Candidate Expansion
                          (Entity / Role / Relation)
                                        │
                                Epistemic Friction
                                        │
                                        ▼
                                  FACT-FINDING
                                        │
                                        ▼
                                   ASSESSMENT
                                        │
                                        ▼
                             DETERMINATION (F_t)
                                        │
                                        ▼
                                    ZOOM-OUT
                                        │
                                        ▼
                                  INTEGRATION
                                        │
                                        ▼
                                     K_{t+1}

   =========================================================================
   OPTIONAL OPTIMIZATION LENS (SEPARATE OPERATIONAL PIPELINE)
   =========================================================================
   K_t / Representation  ───►  ZERO LENS  ───►  Eliminability Judgment

```

---

### Master KnowledgeOS Governance Ledger

```
  ========================================================================================
  MASTER ADJUDICATED RESEARCH LEDGER
  ========================================================================================
  Artifact / ID                      Standing      Category & Governance Role
  ----------------------------------------------------------------------------------------
  Theory v1.2                        [FROZEN]      System Core Baseline Reference
  Minimal Kernel v1.0                [UNTOUCHED]   Kernel Standard
  KR-ZOOM-FACTFINDING-01             [PROP][ACTIVE] Sole Authorized Execution (State Fact-Finding)
  KR-EPISTEMIC-FRICTION-LENS-09      [EXT][PROP]   External Research Lens (FR-A–FR-D)
  KR-DYNAMIC-QUESTION-INQUIRY-09     [EXT][PROP]   External Research Lens (DQ1–DQ5)
  KR-EPISTEMIC-ACHIEVEMENT-LENS-09   [EXT][PROP]   External Research Lens (EA1–EA14)
  KR-ACTION-FACTFINDING              [PROP][OPEN]  Downstream Target (Prereq: KR-ZOOM-FACTFINDING-01)
  KR-CH2-STRUCTURAL                  [EXT][PROP]   Downstream Lens (Problem Discovery: O_t -> Q_t)
  KR-REPRESENTATION-REDUCTION        [EXT][PROP]   Home for Representation Reduction Analysis
  KR-KERNEL-MINIMALITY               [CORE][PROP]  Home for Semantic Equivalence (≡_sem)
  ========================================================================================

```

$$\boxed{ \text{Governance Invariant: } \text{Theory v1.2 [FROZEN]} \;\rightarrow\; \text{KR-ZOOM-FACTFINDING-01 [ACTIVE]} \;\rightarrow\; \text{Structured Holism Lens Interpretation} }$$

---

### Immediate Adjudication Impact on `KR-ZOOM-FACTFINDING-01`

1. **Protocol Stability:** The frozen experimental protocol of `KR-ZOOM-FACTFINDING-01` remains **completely unchanged and uncontaminated**.
2. **Adjudication Criteria:** During post-execution analysis, experimental witnesses for $\text{ZF2}$ (Dimension Expansion) and $\text{ZF3}$ (Cross-Boundary Discovery) will be explicitly evaluated to distinguish between:
* *Type 1:* Genuine new entity/dimension introduction ($\Delta \mathcal{D}^{\text{cand}}$).
* *Type 2:* Epistemic role transition ($\text{Role}_t \to \text{Role}_{t+1}$).
* *Type 3:* Relational path activation ($\Delta \mathcal{R}^{\text{rel}}$).



---