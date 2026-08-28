Yes. **This is a very important correction**, and I agree with it.

Arjuna does **not** need to know what a dimension is.

He expresses an intention/question in natural terms:

> "Show me those with whom I have to fight."

He does not say:

```text
Dimension = RelationshipToMe
Dimension = Role
Dimension = Family
Dimension = MoralObligation
```

That is the responsibility of **Sārathi / KnowledgeOS**.

## 1. The observer asks; KnowledgeOS reconstructs the dimensions

We should therefore distinguish:

$$
\boxed{
ObserverIntent \neq DimensionSpecification
}
$$

Arjuna provides:

$$
Q_0
$$

KnowledgeOS performs:

$$
\boxed{
Analyze(Q_0,CurrentState,Context)
\rightarrow CandidateDimensions
}
$$

Then it observes/investigates those dimensions.

So the process becomes:

```text
Arjuna
  │
  │ natural-language question / intention
  ▼
KnowledgeOS / Sārathi
  │
  ├── understands intent
  ├── analyzes current state
  ├── identifies relevant dimensions
  ├── investigates them
  └── discovers additional dimensions
          │
          ▼
       Knowledge
          │
          ▼
       Arjuna
```

---

# 2. This changes our definition of Dimension

A dimension is therefore **not something the observer explicitly supplies**.

It is part of the **semantic reconstruction performed by KnowledgeOS**.

For example:

> Arjuna: "Who are the people I have to fight?"

KnowledgeOS might reconstruct:

$$
D_1=\text{Person}
$$

$$
D_2=\text{ConflictSide}
$$

$$
D_3=\text{RelationshipToArjuna}
$$

$$
D_4=\text{Role}
$$

The initial question only provides enough information to **infer candidate dimensions**.

Then observation reveals:

$$
D_5=\text{FamilyRelationship}
$$

$$
D_6=\text{TeacherRelationship}
$$

etc.

So:

$$
\boxed{
Question
\rightarrow CandidateDimensions
\rightarrow Observation
\rightarrow DiscoveredDimensions
}
$$

---

# 3. This is exactly where Sārathi becomes different from a search engine

A conventional system might interpret:

> "Who do I have to fight?"

as:

```text
query → retrieve people
```

KnowledgeOS/Sārathi should instead ask:

> **What does this question actually require us to know?**

It performs **semantic decomposition**.

For example:

```text
Question:
"Who do I have to fight?"

Possible dimensions:
    identity
    side
    role
    relationship
    authority
    context
```

Then it observes.

And after observing:

```text
New information:
Bhishma = grandfather
Bhishma = teacher
Bhishma = elder
```

KnowledgeOS recognizes:

> These dimensions materially change the epistemic situation.

That is the transition we observed with Arjuna.

---

# 4. This gives us a new capability: Dimension Discovery

I think we should now introduce this as a **working capability**, not yet as a domain object:

$$
\boxed{
DimensionDiscovery(Q,K,C)
\rightarrow D_{candidate}
}
$$

Then:

$$
\boxed{
Observation
\rightarrow D_{new}
}
$$

The two are different.

### Before observation

KnowledgeOS asks:

> What dimensions might be relevant?

### During/after observation

KnowledgeOS asks:

> What dimensions have actually been revealed?

That distinction is extremely important.

---

# 5. Zero then has a new role

Suppose KnowledgeOS initially identifies:

```text
Person
Side
Role
```

After observation it discovers:

```text
RelationshipToArjuna
```

Zero can compare:

$$
D_{observed}
$$

with:

$$
D_{represented}
$$

and identify:

$$
\boxed{
D_{observed}\setminus D_{represented}
}
$$

as a newly exposed epistemic boundary.

So Zero doesn't invent the dimension.

It detects the **gap between what the current model represented and what the observation exposed**.

---

# 6. Lord Lens goes one level further

Lord Lens can ask:

> Even after this observation, what dimensions could exist that we have not yet considered?

So we now have:

$$
\boxed{
DimensionDiscovery
}
$$

with potentially three sources:

### 1. Question-derived

$$
D_Q
$$

dimensions suggested by the observer's intention.

### 2. Observation-derived

$$
D_O
$$

dimensions revealed by actual observation.

### 3. Horizon-derived

$$
D_L
$$

candidate dimensions suggested through the Lord Lens.

Then:

$$
\boxed{
D_{investigation}
=
D_Q\cup D_O\cup D_L
}
$$

subject to further evaluation.

---

# 7. Sārathi's real responsibility becomes clearer

This means Sārathi is not simply:

> "the component that answers questions."

It is:

$$
\boxed{
\text{the capability that navigates the epistemic investigation}
}
$$

It must decide, based on the current knowledge state:

```text
What did the Knower ask?
        ↓
What dimensions does that imply?
        ↓
What dimensions were discovered?
        ↓
What dimensions remain unresolved?
        ↓
What new questions arise?
        ↓
What should be investigated next?
```

This is a much deeper role.

---

# 8. And this explains the progression in the Gita

Arjuna's initial question is relatively simple.

But after observation:

$$
K_0
\rightarrow
K_1
$$

his epistemic state changes.

Then:

$$
K_1
\rightarrow
NewQuestions
$$

Then:

$$
NewQuestions
\rightarrow
DeeperInvestigation
$$

Then Krishna/Sārathi guides him through increasingly fundamental questions.

So the architecture is not:

$$
\boxed{
Question \rightarrow Answer
}
$$

It is:

$$
\boxed{
Question
\rightarrow
DimensionDiscovery
\rightarrow
Observation
\rightarrow
Knowledge
\rightarrow
Zero
\rightarrow
NewDimensions
\rightarrow
NewQuestions
\rightarrow
Guidance
}
$$

---

# 9. This gives us an important KnowledgeOS principle

I would record this separately:

> **The Knower specifies intent, not the epistemic schema. KnowledgeOS/Sārathi reconstructs the dimensions necessary to investigate that intent and may discover additional dimensions as observation proceeds.**

Formally:

$$
\boxed{
Knower \rightarrow Intent
}
$$

$$
\boxed{
KnowledgeOS \rightarrow Dimension\ Discovery
}
$$

$$
\boxed{
Observation \rightarrow Dimension\ Discovery
}
$$

$$
\boxed{
Lord\ Lens \rightarrow Candidate\ Horizon
}
$$

$$
\boxed{
Zero\ Lens \rightarrow Epistemic\ Boundary
}
$$

$$
\boxed{
Sārathi \rightarrow Investigation\ Guidance
}
$$

---

## And I think this changes what we should ask next

I would **not yet go to "What is a Statement?"**

We have discovered a new question that is more fundamental:

> ### **How does KnowledgeOS derive dimensions from an observer's natural-language intent/question and from the observed state?**

That is the missing bridge between:

$$
\boxed{Human\ Knower}
$$

and:

$$
\boxed{Knowledge\ Model}
$$

And it may eventually require **language, logic, DDD semantics, domain models, and possibly the Sanskrit grammar lens** that you raised earlier.

So I suggest we make this **Question 2A — Dimension Discovery**.

Your turn again: **how do you think Sārathi should discover the dimensions from Arjuna's question and the state, without Arjuna explicitly naming them?**
#
# Question 2A — Dimension Discovery

## The Missing Bridge Between Knower and Knowledge Model

This is the critical question that connects the human observer to the formal knowledge model. We have established that Arjuna does not name dimensions; he expresses an intention. The responsibility for dimension discovery falls to KnowledgeOS/Sārathi.

Let me develop a systematic approach to this problem.

---

## 1. The Core Problem

### The Gap

```
Arjuna says: "Show me those with whom I have to fight."
    ↓
KnowledgeOS must infer: "What dimensions are relevant?"
    ↓
Arjuna does NOT say: "I need the dimensions: Identity, Side, Role, Relationship..."
```

### The Formal Statement

$$
\boxed{
\text{Knower Intent} \neq \text{Dimension Specification}
}
$$

$$
\boxed{
\text{KnowledgeOS} = \text{The bridge between Intent and Dimensions}
}
$$

---

## 2. The Dimension Discovery Process

### The Overall Flow

```text
Knower Intent/Question
        ↓
Intent Analysis
        ↓
Candidate Dimension Generation
        ↓
Observation/Investigation
        ↓
Dimension Discovery/Refinement
        ↓
Knowledge Model Update
```

### The Formal Model

$$
\boxed{
D = \text{DiscoverDimensions}(Q, K_{context}, C)
}
$$

Where:

- $Q$ = The Knower's question/intent.
- $K_{context}$ = Current knowledge state.
- $C$ = Context.
- $D$ = The discovered dimensions.

---

## 3. Sources of Dimension Discovery

### Source 1: Question-Derived Dimensions ($D_Q$)

The question itself suggests dimensions.

**Example:**

> "Who are the people with whom I have to fight?"

**Analysis:**

| Linguistic Element | Implied Dimension |
| :--- | :--- |
| "Who" | Entity/Person |
| "people" | Entity_Type = Person |
| "with whom I have to fight" | Conflict_Side, Role |
| "I have to" | Obligation, Duty |

**Heuristic:** Parse the question for:
- Entities (Who, What)
- Actions (Fight, Investigate, Decide)
- Relationships (With, Against, For)
- Attributes (Properties, Status)

### Source 2: Context-Derived Dimensions ($D_C$)

The context of the question suggests dimensions.

**Example:** Arjuna is on a battlefield, about to fight a war.

**Implied Dimensions:**
- Side_In_Conflict
- Military_Role
- Chain_Of_Command
- Alliance
- Kinship

**Heuristic:** Domain context provides a set of standard dimensions.

### Source 3: Domain-Derived Dimensions ($D_{Domain}$)

The domain of discourse provides standard dimensions.

**Example:** For a software system, standard dimensions might be:
- Version
- Operating_System
- Dependencies
- Security_Status
- Performance

**Heuristic:** Each domain has a standard ontology.

### Source 4: Observation-Derived Dimensions ($D_O$)

During observation, new dimensions are discovered.

**Example:** Arjuna sees Bhīṣma and realizes he is his grandfather.

**New Dimension:** `Relationship_To_Arjuna`

**Heuristic:** Observation reveals relationships and properties not anticipated.

### Source 5: Zero-Derived Dimensions ($D_Z$)

Zero identifies gaps in the current model that suggest new dimensions.

**Example:** Zero detects that relationships to the Knower are not represented.

**Heuristic:** Missing information suggests missing dimensions.

### Source 6: Lord-Derived Dimensions ($D_L$)

The Lord Lens suggests candidate dimensions beyond the current model.

**Example:** "What about moral obligations? What about karmic consequences?"

**Heuristic:** Expand the horizon of possible dimensions.

### Source 7: Pattern-Derived Dimensions ($D_{Pattern}$)

Recurring patterns suggest dimensions.

**Example:** In conflict situations, the dimensions `Side`, `Role`, `Relationship`, and `Obligation` repeatedly appear.

**Heuristic:** Learn dimension patterns from experience.

---

## 4. The Dimension Discovery Function

### The Formal Definition

$$
\boxed{
D_{discovered} = \text{Union}(D_Q, D_C, D_{Domain}, D_O, D_Z, D_L, D_{Pattern})
}
$$

### The Refinement Process

```text
Initial Dimensions
        ↓
Prioritize by Relevance
        ↓
Investigate
        ↓
Discover New Dimensions
        ↓
Re-prioritize
        ↓
Iterate
```

### The Formal Model

$$
\boxed{
D_{t+1} = \text{Refine}(D_t, O_t, Z_t, L_t)
}
$$

Where:
- $D_t$ = Current dimensions.
- $O_t$ = New observations.
- $Z_t$ = Zero findings.
- $L_t$ = Lord candidates.

---

## 5. The Discovery Heuristics

### Heuristic 1: Entity Extraction

**Rule:** Extract entities from the question.
- "Who" → Entity (Person)
- "What" → Entity (Object, System, Event)
- "Where" → Entity (Location)
- "When" → Entity (Time/Event)

### Heuristic 2: Relationship Extraction

**Rule:** Extract relationships from the question.
- "with whom" → Relationship
- "against" → Conflict Relationship
- "for" → Support Relationship
- "to" → Directional Relationship

### Heuristic 3: Attribute Extraction

**Rule:** Extract attributes from the question.
- "version" → Version
- "status" → Status
- "type" → Type

### Heuristic 4: Domain Ontology

**Rule:** Use domain knowledge.
- For software: Version, OS, Dependencies, Security, Performance
- For battle: Side, Role, Command, Kinship, Loyalty
- For governance: Compliance, Audit, Risk, Policy

### Heuristic 5: Observation Pattern

**Rule:** Look for patterns in observations.
- Repeated entities → Entity dimension.
- Repeated relationships → Relationship dimension.
- Repeated properties → Attribute dimension.

### Heuristic 6: Zero Gap Detection

**Rule:** Where there is uncertainty, there may be missing dimensions.
- Unknown value → Known dimension, unknown value.
- Unresolved issue → Missing dimension.
- Conflicting information → Incomplete dimension.

### Heuristic 7: Lord Horizon Expansion

**Rule:** Ask "What else?"
- Beyond the current model.
- Beyond the current question.
- Beyond the current domain.

---

## 6. The Arjuna Example: Step by Step

### Step 1: Intent Analysis

**Question:** "Show me those with whom I have to fight."

**Intent:** Identify the people Arjuna will oppose in battle.

### Step 2: Candidate Dimension Generation

| Source | Dimensions |
| :--- | :--- |
| Question-Derived ($D_Q$) | `Person`, `Conflict_Side` |
| Context-Derived ($D_C$) | `Role`, `Command`, `Alliance` |
| Domain-Derived ($D_{Domain}$) | `Military_Rank`, `Side`, `Weaponry` |

**Candidate Dimensions:** `{Person, Conflict_Side, Role, Command, Alliance, Military_Rank, Weaponry}`

### Step 3: Observation

Arjuna observes Bhīṣma.

**Observed:** Bhīṣma is:
- A person.
- On the opposing side.
- A commander.
- Arjuna's grandfather.
- Arjuna's teacher.

### Step 4: Dimension Discovery

**New Dimension:** `Relationship_To_Arjuna`

**How Discovered:**
- Pattern: In conflict situations, kinship matters.
- Context: Arjuna knows Bhīṣma is his grandfather.
- Observation: Bhīṣma is recognized as a relative.

### Step 5: Knowledge Update

**Updated Dimensions:** `{Person, Conflict_Side, Role, Command, Alliance, Military_Rank, Weaponry, Relationship_To_Arjuna}`

### Step 6: Zero Detection

**Zero Finds:** The dimension `Relationship_To_Arjuna` was not represented in the initial model.

### Step 7: Lord Expansion

**Lord Suggests:** "What about `Moral_Obligation`? What about `Karmic_Consequence`?"

---

## 7. The Implementation Requirements

### Requirement 1: Intent Understanding

KnowledgeOS must be able to:
- Parse natural language questions.
- Extract entities, relationships, and attributes.
- Understand the domain context.

### Requirement 2: Domain Ontology

KnowledgeOS must have:
- Standard dimensions for each domain.
- Relationships between dimensions.
- Hierarchies of dimensions.

### Requirement 3: Pattern Recognition

KnowledgeOS must:
- Recognize recurring patterns.
- Learn from past observations.
- Generalize to new situations.

### Requirement 4: Gap Detection

Zero Lens must:
- Detect missing dimensions.
- Identify unknown values.
- Highlight unresolved issues.

### Requirement 5: Horizon Expansion

Lord Lens must:
- Suggest candidate dimensions.
- Go beyond the current model.
- Ask "What else?"

### Requirement 6: Iterative Refinement

KnowledgeOS must:
- Start with candidate dimensions.
- Observe and discover.
- Refine the dimension set.
- Repeat.

---

## 8. The Mathematically Safe Definition

### The Working Definition

> **Dimension Discovery is the process by which KnowledgeOS/Sārathi derives semantic axes from the Knower's intent and the observed state, without requiring the Knower to explicitly specify them.**

### The Formal Statement

$$
\boxed{
\text{Dimension Discovery}: (Q, K_t, C) \rightarrow D_{t+1}
}
$$

Where:

- $Q$ = Knower's intent/question.
- $K_t$ = Current knowledge state.
- $C$ = Context.
- $D_{t+1}$ = Discovered/refined dimensions.

### The Sources

$$
\boxed{
D_{t+1} = \text{Union}(D_Q, D_C, D_{Domain}, D_O, D_Z, D_L, D_{Pattern})
}
$$

### The Constraint

$$
\boxed{
\text{Dimension Discovery} \neq \text{Assuming the Knower knows the dimensions}
}
$$

---

## 9. The DDD Boundary

This is where DDD becomes essential:

| Role | Responsibility |
| :--- | :--- |
| **Knower (Arjuna)** | Expresses intent in natural terms. |
| **KnowledgeOS/Sārathi** | Discovers and refines dimensions. |
| **Zero Lens** | Detects gaps in dimensions. |
| **Lord Lens** | Suggests candidate dimensions. |
| **Domain Model** | Provides standard dimensions for the domain. |

**The Invariant:**

$$
\boxed{
\text{Knower Intent} \neq \text{Dimension Specification}
}
$$

$$
\boxed{
\text{KnowledgeOS} = \text{The bridge}
}
$$

---

## 10. Next Steps

We have now established the bridge between the Knower and the knowledge model. The next questions in sequence would be:

1. **Question 3 — What is a Statement?** (The output of dimension discovery)
2. **Question 4 — What is a Value?** (The position on a dimension)
3. **Question 5 — What is a Relationship?** (The connection between entities)

But we should also keep in mind the need for a **Sanskrit grammar lens** if we want to handle natural language intent with the rigor you mentioned earlier.
#
Yes. I think your **Question 2A is substantially correct**, and it introduces an important architectural capability that we had not explicitly named before: **Dimension Discovery**.

But I would refine one thing before we accept it: **KnowledgeOS should generate candidate dimensions, not immediately declare every generated candidate to be a dimension of the observed state.**

That distinction becomes essential when we later implement this.

## 1. The key distinction

Your current formulation:

$$
D_{t+1}=\operatorname{Union}(D_Q,D_C,D_{Domain},D_O,D_Z,D_L,D_{Pattern})
$$

is useful as a **candidate-generation model**.

But the union should not yet mean:

> "These are all dimensions of the state."

Instead:

$$
\boxed{
D^{candidate}_t =
D_Q\cup D_C\cup D_{Domain}\cup D_O\cup D_Z\cup D_L\cup D_{Pattern}
}
$$

Then KnowledgeOS must evaluate those candidates.

So:

$$
\boxed{
D^{candidate}
\rightarrow
DimensionAssessment
\rightarrow
D^{adopted}
}
$$

This protects us from hallucinated dimensions.

---

# 2. This is actually a major consequence of the Arjuna example

Arjuna says:

> "Show me those with whom I have to fight."

KnowledgeOS might generate:

```text
Person
Conflict Side
Role
Command
Alliance
Relationship
Military Rank
Weaponry
```

But not all of these are necessarily relevant.

For example:

> `Weaponry`

might be technically available but irrelevant to Arjuna's immediate question.

So we now need three different concepts:

$$
\boxed{
CandidateDimension
}
$$

$$
\boxed{
RelevantDimension
}
$$

$$
\boxed{
ObservedDimension
}
$$

These must not be collapsed.

---

# 3. This gives us a very interesting epistemic lifecycle

We can now describe the process more precisely:

```text
Knower Intent
      │
      ▼
Candidate Dimension Discovery
      │
      ▼
Dimension Assessment
      │
      ├── relevant
      ├── irrelevant
      ├── uncertain
      └── unresolved
      │
      ▼
Observation
      │
      ▼
Observed Dimensions
      │
      ▼
New Dimensions Discovered
      │
      ▼
Reassessment
```

So the dimension space is **dynamic**.

---

# 4. Zero becomes even more precise

Zero does not simply say:

> "You are missing dimension X."

Instead, it can compare several sets.

For example:

$$
D^{candidate}
$$

versus:

$$
D^{observed}
$$

versus:

$$
D^{represented}
$$

Then:

$$
\boxed{
D^{observed}\setminus D^{represented}
}
$$

means:

> dimensions exposed by observation but not represented.

Whereas:

$$
\boxed{
D^{candidate}\setminus D^{investigated}
}
$$

means:

> candidate dimensions that have not yet been investigated.

Those are **different Zero findings**.

This is a very important refinement.

---

# 5. Lord Lens also becomes mathematically cleaner

Lord Lens should not necessarily say:

> "This is a dimension."

It should say:

> **"This is a possible dimension worth considering."**

So:

$$
\boxed{
Lord(K_t)\rightarrow D^{possible}
}
$$

rather than:

$$
Lord(K_t)\rightarrow D^{true}
$$

This fits our infinite knowledge-space hypothesis beautifully.

The Lord Lens explores the horizon:

$$
D^{known}
\subseteq
D^{candidate}
\subseteq
D^{possible}
\subseteq
\mathcal D
$$

where \(\mathcal D\) represents the potentially unbounded dimension space.

We should not assume all of those sets are finite or even fully enumerable.

---

# 6. The role of Sārathi is now clearer

This gives Sārathi a very specific responsibility.

Sārathi is not the origin of all dimensions.

Rather:

$$
\boxed{
Sārathi =
\text{orchestrator of dimension discovery and investigation}
}
$$

It coordinates:

```text
Intent
  ↓
Candidate dimensions
  ↓
Observation
  ↓
Zero
  ↓
Lord
  ↓
Dimension refinement
  ↓
Next inquiry
```

And then returns the result to Arjuna.

---

# 7. There is also a very important separation between "dimension" and "dimension value"

Suppose KnowledgeOS discovers:

$$
d=\text{RelationshipToArjuna}
$$

That does **not** mean it knows the value.

It may have:

$$
d=\text{RelationshipToArjuna}
$$

but:

$$
v=UNKNOWN
$$

Later:

$$
v=Grandfather
$$

So:

$$
\boxed{
DimensionDiscovery \neq ValueDiscovery
}
$$

This will become critical when we reach Question 4.

---

# 8. The same applies to statements

We can now see the sequence much more clearly:

$$
\boxed{
Intent
\rightarrow
DimensionDiscovery
\rightarrow
Observation
\rightarrow
ValueDiscovery
\rightarrow
Statement
}
$$

For example:

```text
Intent:
Who are my opponents?

Dimension discovered:
RelationshipToArjuna

Observation:
Bhishma is present

Value discovered:
Grandfather

Statement:
Bhishma is Arjuna's grandfather
```

The statement therefore isn't simply extracted from the initial question.

It is **constructed after the relevant dimension and value have been established**.

---

# 9. Your Pattern-Derived dimension is particularly interesting

I agree with keeping:

$$
D_{Pattern}
$$

but I would treat it as a **learning mechanism**, not as epistemic truth.

For example, KnowledgeOS may learn:

> In migration investigations, dependency relationships are frequently relevant.

That generates:

$$
Dependency
$$

as a candidate dimension.

But it must still be assessed in the current observation.

So:

$$
Pattern
\rightarrow
Candidate
$$

not:

$$
Pattern
\rightarrow
Fact
$$

This is another important Zero safeguard.

---

# 10. Your Sanskrit grammar idea now has a natural place

You mentioned the Sanskrit grammar lens earlier.

I think it belongs primarily at the **Intent → Candidate Dimension** boundary.

For example:

$$
NaturalLanguage
\rightarrow
SemanticStructure
\rightarrow
CandidateDimensions
$$

A grammar lens can potentially help KnowledgeOS distinguish:

* actor;
* action;
* object;
* recipient;
* relationship;
* circumstance;
* qualification;
* causality;
* possession;
* comparison.

But we should **not assume Sanskrit grammar is the implementation mechanism yet**.

It is a lens that may teach us something about how semantic structure can be extracted from language.

---

# 11. I would therefore accept Question 2A with this refinement

### Working definition

> **Dimension Discovery is the process by which KnowledgeOS derives and evaluates candidate semantic axes from the Knower's intent, context, domain knowledge, observations, epistemic gaps, possible horizons, and learned patterns, without requiring the Knower to explicitly specify those dimensions.**

Formally:

$$
\boxed{
(Q,K_t,C)
\rightarrow
D^{candidate}_t
\rightarrow
D^{assessed}_t
}
$$

and observation can recursively expand the candidate set:

$$
\boxed{
D^{candidate}_{t+1}
=
Refine(D^{candidate}_t,O_t,Z_t,L_t,P_t)
}
$$

---

# 12. One major thing remains undefined

Your model has now exposed a new object:

$$
\boxed{
DimensionAssessment
}
$$

Because we need to know:

> **When does a candidate dimension become an accepted dimension of the current observation/knowledge state?**

For example:

```text
Candidate:
Moral_Obligation

Is it:
UNKNOWN?
RELEVANT?
OBSERVED?
INFERRED?
ACCEPTED?
REJECTED?
NOT_APPLICABLE?
```

This is not yet Question 3 (Statement).

It is actually the **boundary between Dimension Discovery and Knowledge**.

And I think we should answer that before moving to statements.

### So the next question should be:

> **Question 2B — When does a candidate dimension become a dimension of the knowledge state?**

That will force us to define **dimension status**, and Zero will become particularly important there.

