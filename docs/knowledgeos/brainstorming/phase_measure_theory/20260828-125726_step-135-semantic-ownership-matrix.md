# Step 135 — Semantic Ownership Matrix

We now move from **component ownership** to **semantic ownership**.

This is a crucial distinction.

A technical component can execute something without owning its meaning.

For example:

$$
Hook
\rightarrow
executes\ a\ rule
$$

but:

$$
Hook
\neq
owner\ of\ the\ rule.
$$

Likewise:

$$
Claude
\rightarrow
uses\ knowledge
$$

but:

$$
Claude
\neq
owner\ of\ authoritative\ knowledge.
$$

The question is therefore:

> **Which bounded context owns each concept, its invariants, and its authoritative lifecycle?**

---

# 135.1 — Ownership matrix

Our current target hypothesis is:

| Concept         | Semantic owner                | Authority                | Main responsibility          |
| --------------- | ----------------------------- | ------------------------ | ---------------------------- |
| Business Intent | Governance / Business         | Business authority       | Why something is needed      |
| Decision        | Governance                    | Authorized decision body | What is decided              |
| Policy          | Governance                    | Policy authority         | What is mandatory            |
| Exception       | Governance                    | Authorized authority     | Permitted deviation          |
| Knowledge Claim | Knowledge                     | Knowledge governance     | What is believed/known       |
| Evidence        | Evidence                      | Source/provenance        | What supports a claim        |
| Observation     | Evidence                      | Observed system          | What was observed            |
| Fitness Rule    | Assurance                     | Governance-derived       | What is checked              |
| Verification    | Assurance                     | Checker/execution        | What was verified            |
| Finding         | Assurance                     | Assurance process        | What failed/is uncertain     |
| Recommendation  | Agent/Application             | Agent identity           | What is proposed             |
| Action          | Application/Engineering       | Authorization            | What was executed            |
| Agent           | Agent Platform                | Platform governance      | Who/what performed reasoning |
| Session         | Agent Platform                | Execution context        | Execution continuity         |
| Artifact        | Engineering / External system | Source system            | Concrete engineering object  |
| Runtime State   | External system               | Runtime authority        | Actual operational state     |

This is a **target semantic ownership hypothesis**.

It must ultimately be validated against the actual KnowledgeOS implementation and organizational governance.

---

# 135.2 — Ownership means invariant ownership

Ownership is not merely:

> "Which service stores the table?"

It means:

> **Which context is responsible for keeping the concept semantically valid?**

For example:

$$
Governance
$$

owns the invariant:

$$
Decision
\Rightarrow
ValidAuthority.
$$

The database storing the decision is secondary.

---

# 135.3 — Decision

Candidate aggregate:

$$
Decision.
$$

Potential invariants:

$$
DecisionID\neq null
$$

$$
Authority\neq null
$$

$$
DecisionStatus\in ValidStates
$$

$$
EffectiveDate
$$

$$
SupersessionRules.
$$

Governance owns these semantics.

---

# 135.4 — Policy

Policy is related to Decision but should not automatically be the same concept.

A Decision may say:

> Adopt architecture principle X.

A Policy may say:

> All production services must satisfy X.

Therefore:

$$
Decision
\rightarrow
Policy
$$

may be a governed relationship.

---

# 135.5 — Exception

Exception is especially important.

It does not invalidate the underlying policy.

Instead:

$$
Policy
+
Exception
\rightarrow
EffectivePolicy.
$$

Therefore the exception belongs conceptually to Governance.

---

# 135.6 — Knowledge Claim

A Claim is different from a Decision.

Example:

> Nexus runs version 3.69.0.

That is a factual/observational claim.

It does not become a governance decision merely because it is recorded.

Therefore:

$$
Claim
\neq
Decision.
$$

---

# 135.7 — Evidence

Evidence supports claims.

For example:

$$
Claim:
Nexus=3.69.0
$$

supported by:

$$
Evidence:
ContainerImageInspection.
$$

The evidence context owns:

$$
Provenance.
$$

---

# 135.8 — Observation

Observation is a particularly useful distinction.

An observation is:

$$
What\ a\ system\ was\ seen\ to\ do.
$$

For example:

```text id="m2q8v4"
Observed:
Nexus container image = 3.69.0

ObservedAt:
2026-08-28T...
```

The observation does not itself say:

> This is compliant.

That is Assurance's responsibility.

---

# 135.9 — The epistemic chain

We can therefore model:

$$
Observation
\rightarrow
Evidence
\rightarrow
Claim.
$$

Then:

$$
Claim
\leftrightarrow
ExpectedKnowledge.
$$

And Assurance evaluates:

$$
Expected
\leftrightarrow
Observed.
$$

---

# 135.10 — Fitness Rule

A Fitness Rule is not merely a technical script.

It represents:

> A formally expressible architectural/governance constraint.

Therefore:

$$
Rule
$$

should have:

* identity;
* version;
* scope;
* source principle;
* applicability;
* checker;
* severity.

---

# 135.11 — Verification

Verification answers:

> What did the checker determine?

Therefore:

$$
Verification
=
Execution\ of\ a\ Rule\ against\ a\ Subject.
$$

Conceptually:

$$
V(R,S,E,t)
\rightarrow
Verdict.
$$

---

# 135.12 — Finding

Finding is the result that requires attention.

A finding can originate from:

$$
FAIL
$$

or:

$$
UNKNOWN.
$$

Therefore:

$$
Finding
\neq
Violation.
$$

A finding may represent:

* non-conformance;
* insufficient evidence;
* stale state;
* suspicious drift;
* unresolved ambiguity.

---

# 135.13 — Recommendation

Recommendations are fundamentally different.

An agent might produce:

> "The Nexus configuration should be migrated using approach X."

This is:

$$
Recommendation.
$$

It is not yet:

$$
Decision.
$$

And certainly not:

$$
Authority.
$$

---

# 135.14 — Recommendation lifecycle

The target flow is:

```text id="x4m8q2"
Agent
  ↓
Recommendation
  ↓
Review
  ↓
Decision
  ↓
Authorization
  ↓
Action
```

This prevents the agent from collapsing recommendation and governance.

---

# 135.15 — Action

Action represents something that actually happened.

Examples:

* create branch;
* modify configuration;
* deploy artifact;
* migrate Nexus;
* change infrastructure.

Action should therefore be connected to:

$$
Actor
$$

$$
Authorization
$$

$$
Target
$$

$$
Time
$$

$$
Evidence.
$$

---

# 135.16 — Agent

An agent is an execution/reasoning actor.

It may have:

$$
Identity
$$

$$
Version
$$

$$
Capabilities
$$

$$
Session.
$$

But it does not inherently possess organizational authority.

Thus:

$$
AgentCapability
\neq
GovernanceAuthority.
$$

---

# 135.17 — Session

Session represents execution continuity.

For example:

$$
Session S42
$$

may contain:

```text id="r8n3m5"
Task
Context
Tool calls
Recommendations
Actions
Results
```

But the session should not become the system of record for governance.

---

# 135.18 — Artifact

Artifact is usually owned by an external engineering system.

Examples:

$$
GitCommit
$$

$$
NexusArtifact
$$

$$
KubernetesDeployment.
$$

KnowledgeOS should reference these rather than claiming ownership over their operational semantics.

---

# 135.19 — Runtime State

Runtime systems remain authoritative for their actual operational state.

For example:

$$
Kubernetes
$$

is authoritative for:

> Which pods are currently running.

KnowledgeOS can record an observation:

$$
Observation(Kubernetes,t).
$$

But should not pretend its cached value is more current than Kubernetes.

---

# 135.20 — Authority matrix

This gives us a second important matrix:

| Question                      | Authority                                        |
| ----------------------------- | ------------------------------------------------ |
| What was decided?             | Governance                                       |
| What policy applies?          | Governance                                       |
| What is currently known?      | Knowledge                                        |
| What supports that knowledge? | Evidence                                         |
| What was observed?            | Source system / Evidence                         |
| Does reality conform?         | Assurance                                        |
| What should we do?            | Agent recommendation + human/governance decision |
| What actually happened?       | Engineering/source system + Evidence             |
| Who executed it?              | Agent/identity system                            |
| Who authorized it?            | Governance/authorization                         |

This is much more precise than simply saying:

> KnowledgeOS owns everything.

---

# 135.21 — No "god context"

We should explicitly reject:

$$
KnowledgeOS
=
GodContext.
$$

If one context owns:

* decisions;
* runtime;
* Git;
* agents;
* evidence;
* policies;
* deployments;

then the bounded-context model collapses.

KnowledgeOS should instead coordinate semantic relationships while respecting ownership.

---

# 135.22 — Context map

The resulting model becomes:

```text id="z6q2m8"
                    GOVERNANCE
                 ┌──────────────┐
                 │ Decision     │
                 │ Policy       │
                 │ Exception    │
                 │ Authority    │
                 └──────┬───────┘
                        │
                    governs
                        │
                        ▼
                    KNOWLEDGE
                 ┌──────────────┐
                 │ Claims       │
                 │ State        │
                 │ Validity     │
                 └──────┬───────┘
                        │
                     checked by
                        │
                        ▼
                    ASSURANCE
                 ┌──────────────┐
                 │ Rules        │
                 │ Verification │
                 │ Findings     │
                 └──────┬───────┘
                        │
                     evaluates
                        │
                        ▼
               ENGINEERING / RUNTIME
                        │
                     observed
                        │
                        ▼
                    EVIDENCE
                        │
                     supports
                        │
                        └──────────► KNOWLEDGE
```

---

# 135.23 — Where the agent sits

Now add the agent:

```text id="m7p4q2"
                       GOVERNANCE
                            ▲
                            │
                         Decision
                            │
                            │
                         Knowledge
                            ▲
                            │
                     Agent Context
                            ▲
                            │
                    ┌───────┴───────┐
                    │               │
                 Claude           Codex
                    │               │
                    └───────┬───────┘
                            │
                       Recommendations
                            │
                            ▼
                         Action
                            │
                            ▼
                       Engineering
                            │
                            ▼
                         Evidence
```

The agent is now clearly **inside the engineering interaction loop but outside the authority boundary**.

---

# 135.24 — Important asymmetry

The graph has two fundamentally different edge types.

### Authority edges

$$
governs
$$

$$
authorizes
$$

$$
approves.
$$

### Evidence edges

$$
supports
$$

$$
observedBy
$$

$$
verifiedBy.
$$

They should never be conflated.

---

# 135.25 — Example of the difference

Suppose:

$$
Evidence E42
$$

shows that a deployment occurred.

That creates:

$$
Deployment
\overset{supportedBy}{\rightarrow}
E42.
$$

It does **not** create:

$$
Deployment
\overset{authorizedBy}{\rightarrow}
E42.
$$

Evidence and authority are different semantic dimensions.

---

# 135.26 — Knowledge can have multiple evidence sources

A claim may have:

$$
E_1
$$

$$
E_2
$$

$$
E_3.
$$

For example:

```text id="j6r2m8"
Claim: Nexus version = 3.69.0

Evidence:
├── Container inspection
├── Nexus API
└── Deployment manifest
```

Assurance can evaluate the consistency of these observations.

---

# 135.27 — Conflicting evidence

Suppose:

$$
E_1: 3.69.0
$$

and:

$$
E_2: 3.70.0.
$$

Then KnowledgeOS should not arbitrarily choose one.

It should produce:

$$
EvidenceConflict.
$$

Potentially:

$$
KnowledgeStatus=UNCERTAIN.
$$

---

# 135.28 — This is where AI must be constrained

An LLM could say:

> "3.70.0 is probably the latest, so use that."

That is reasoning.

But the authoritative state should remain:

$$
UNKNOWN/CONFLICTED
$$

until resolved according to governance/evidence rules.

---

# 135.29 — Epistemic status

Knowledge objects therefore need an epistemic dimension.

Potential values:

$$
PROPOSED
$$

$$
SUPPORTED
$$

$$
VERIFIED
$$

$$
AUTHORITATIVE
$$

$$
SUPERSEDED
$$

$$
DISPUTED
$$

$$
UNKNOWN.
$$

This is extremely valuable for agent reasoning.

---

# 135.30 — Authority versus epistemic status

These are different.

A claim may be:

$$
AUTHORITATIVE
$$

because Governance has declared it binding.

Another may be:

$$
VERIFIED
$$

but not authoritative.

For example:

> The production server currently runs version 3.69.0.

This can be verified operationally.

It does not mean:

> Version 3.69.0 is the approved architecture.

---

# 135.31 — Two-dimensional knowledge status

A better model is:

$$
KnowledgeState
=
(EpistemicStatus,\ AuthorityStatus).
$$

For example:

```text id="g3n7q2"
Claim:
Nexus runs 3.69.0

Epistemic:
VERIFIED

Authority:
OBSERVATIONAL
```

While:

```text id="p8m4x1"
Decision:
Nexus shall migrate to 3.70.0

Epistemic:
APPROVED

Authority:
GOVERNING
```

These must not be confused.

---

# 135.32 — This resolves a common AI problem

LLMs often collapse:

$$
LikelyTrue
$$

into:

$$
Authoritative.
$$

KnowledgeOS must explicitly prevent that semantic collapse.

---

# 135.33 — Semantic ownership of "truth"

We should therefore avoid a single field:

```text
is_true = true
```

Instead we need dimensions such as:

$$
Source
$$

$$
Evidence
$$

$$
Verification
$$

$$
Authority
$$

$$
Validity.
$$

This gives a much more rigorous epistemic model.

---

# 135.34 — Aggregate boundaries

The ownership matrix also helps determine aggregates.

Likely candidates:

### Governance

$$
Decision
$$

$$
Exception
$$

$$
Policy.
$$

### Knowledge

$$
KnowledgeClaim.
$$

### Evidence

$$
EvidenceRecord.
$$

### Assurance

$$
FitnessRule
$$

$$
Verification
$$

$$
Finding.
$$

### Agent Platform

$$
AgentSession
$$

$$
AgentAction.
$$

These are hypotheses pending implementation analysis.

---

# 135.35 — Do not share aggregates

For example:

$$
Decision
$$

should not be embedded inside:

$$
AgentSession.
$$

Instead:

$$
AgentSession
\rightarrow
DecisionID.
$$

Likewise:

$$
Verification
\rightarrow
RuleID.
$$

This preserves bounded-context ownership.

---

# 135.36 — IDs across boundaries

Cross-context references should generally use stable identities rather than shared domain objects.

For example:

```text id="u5q9m2"
Verification
    rule_id = R42
    subject_id = S17
```

rather than importing the entire `Rule` aggregate.

---

# 135.37 — Context boundary rule

We can therefore add:

$$
\boxed{
AFR-31:
Cross-context relationships use explicit contracts or stable references rather than shared mutable domain objects.
}
$$

This will be particularly important if the system eventually becomes distributed.

---

# 135.38 — The modular-monolith option remains open

Nothing here requires microservices.

We can implement:

```text id="x7m2q8"
knowledge/
governance/
evidence/
assurance/
agent/
```

inside one deployable application.

As long as:

$$
SemanticBoundaries
$$

are respected.

---

# 135.39 — This is probably the safer evolution

Given the existing KnowledgeOS ecosystem, the immediate goal should probably be:

$$
\boxed{
Modular\ semantic\ boundaries
}
$$

before:

$$
Microservice\ boundaries.
$$

Otherwise deployment complexity could obscure the more important domain problem.

---

# 135.40 — Ownership matrix → architecture

We can now derive:

```text id="k4q8m2"
                 KnowledgeOS
                      │
       ┌──────────────┼──────────────┐
       ▼              ▼              ▼
   Governance      Knowledge      Assurance
       │              │              │
       │              │              │
       └──────────────┼──────────────┘
                      │
                   Evidence
                      │
             ┌────────┴────────┐
             ▼                 ▼
       Agent Platform     Integrations
             │                 │
       Claude/Codex       Git/Nexus/K8s/CI
```

The exact direction of some integration relationships still requires implementation evidence.

---

# 135.41 — Ownership test

For every new feature we can now ask:

> Which context owns this concept?

If the answer is:

> "KnowledgeOS"

that is insufficient.

We ask:

> Which KnowledgeOS bounded context?

This forces semantic precision.

---

# 135.42 — Example: "Architecture finding"

Where does it belong?

Possibilities:

$$
Knowledge
$$

or:

$$
Assurance.
$$

The better candidate is:

$$
Assurance
$$

because a finding represents the result of evaluating an expected state against observed state.

The finding can reference:

$$
Knowledge
$$

and:

$$
Governance.
$$

---

# 135.43 — Example: "Architecture recommendation"

This belongs more naturally to:

$$
Agent/Application.
$$

Because it is generated as a proposal.

It may reference:

$$
Finding
$$

and:

$$
Knowledge.
$$

---

# 135.44 — Example: "Architecture decision"

This belongs to:

$$
Governance.
$$

Even if an agent drafts it.

The agent produces:

$$
DraftRecommendation.
$$

Governance produces:

$$
Decision.
$$

---

# 135.45 — Example: "Architecture rule"

This belongs to:

$$
Assurance
$$

if its primary purpose is machine-verifiable conformance.

But its authority should derive from:

$$
Governance.
$$

Therefore:

$$
Governance
\rightarrow
Rule.
$$

---

# 135.46 — This gives us a useful hierarchy

$$
\boxed{
Governance\ defines
}
$$

$$
\boxed{
Knowledge\ represents
}
$$

$$
\boxed{
Assurance\ verifies
}
$$

$$
\boxed{
Engineering\ executes
}
$$

$$
\boxed{
Evidence\ proves/records
}
$$

$$
\boxed{
Agents\ reason\ and\ act\ within\ these\ boundaries.
}
$$

That is perhaps the clearest formulation of the emerging KnowledgeOS architecture.

---

# 135.47 — Step 135 verdict

The semantic ownership model is now sufficiently precise to serve as a boundary framework.

The core ownership is:

$$
\boxed{
Governance
\rightarrow
Decision/Policy/Authority
}
$$

$$
\boxed{
Knowledge
\rightarrow
Claims/KnowledgeState
}
$$

$$
\boxed{
Evidence
\rightarrow
Observation/Provenance
}
$$

$$
\boxed{
Assurance
\rightarrow
Rules/Verification/Findings
}
$$

$$
\boxed{
AgentPlatform
\rightarrow
Agent/Session/Recommendation/Execution
}
$$

while external systems retain ownership of:

$$
Runtime,\ Git,\ Nexus,\ CI/CD,\ IAM.
$$

---

# Step 136 — Bounded Context Boundary Tests

Now that semantic ownership is defined, the next step is to **test the boundaries**.

We will take realistic KnowledgeOS scenarios and ask:

> Which context owns the state transition?

This is where ambiguous concepts become concrete.

The scenarios should include:

1. **A new architecture decision**
2. **An agent discovering a new fact**
3. **A deterministic check failing**
4. **A governance exception**
5. **An agent changing code**
6. **A production deployment**
7. **Conflicting evidence**
8. **Supersession of a decision**
9. **Claude and Codex using the same knowledge**
10. **A Nexus migration**

For each scenario we will trace:

$$
Actor
\rightarrow
Command
\rightarrow
OwningContext
\rightarrow
StateChange
\rightarrow
Evidence
\rightarrow
Verification
\rightarrow
Governance.
$$

This will expose any remaining boundary violations before we translate the model into the final KnowledgeOS component architecture.
