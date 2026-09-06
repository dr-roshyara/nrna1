Part XX is the natural continuation: once persistence preserves the semantic state, we need a rigorous theory of **how that state is queried and retrieved** without allowing search relevance, embedding similarity, graph paths, or generated text to masquerade as evidence or inference.

# Part XX — Query Semantics, Retrieval, Search, Knowledge Graph Traversal, RAG, and the Boundary Between Retrieval and Inference

## 20.1 Purpose

Part XIX established persistence as a semantic preservation problem.

The next boundary is:

$$
KnowledgeState
\rightarrow
Query
\rightarrow
Retrieval
\rightarrow
Interpretation
\rightarrow
Inference
$$

This boundary is especially important for KnowledgeOS because modern AI systems frequently collapse several distinct operations into one apparently simple action:

> “Search the knowledge and answer the question.”

Mathematically and architecturally, this is not one operation.

At minimum, it may involve:

1. query interpretation,
2. candidate retrieval,
3. filtering,
4. ranking,
5. evidence selection,
6. context construction,
7. inference,
8. answer generation,
9. confidence estimation,
10. provenance construction,
11. determination.

These operations have different semantics.

The foundational distinction is:

$$
\boxed{
Retrieval\neq Evidence\neq Inference\neq Determination.
}
$$

And therefore:

$$
\boxed{
Retrieved\ Text\neq Truth.
}
$$

---

# 20.2 The Retrieval Problem

Let:

$$
K
$$

be a KnowledgeOS state.

Let:

$$
Q
$$

be an inquiry.

A retrieval system produces:

$$
R(K,Q)
$$

where \(R\) is a set, sequence, ranking, or structured collection of candidate objects.

Thus:

$$
R(K,Q)=
\{x_1,x_2,\ldots,x_n\}.
$$

The retrieval result answers:

> Which objects were selected according to the retrieval mechanism?

It does not automatically answer:

> Which proposition is true?

Therefore:

$$
Retrieval(K,Q)
\not\Rightarrow
Truth(Q).
$$

---

# 20.3 Query Is Not Question

A query is a computational representation.

A question is a semantic inquiry.

Let:

$$
Q_s
$$

be a semantic inquiry and:

$$
Q_c
$$

its computational query representation.

Then:

$$
Encode(Q_s)=Q_c.
$$

The encoding may be lossy.

Therefore:

$$
Q_c\neq Q_s
$$

in general.

A database query such as:

```text
status = "approved"
```

does not necessarily represent the semantic question:

> “Has this proposition been determined to satisfy the current epistemic contract?”

The latter may require:

* contract version,
* evidence requirements,
* authority,
* temporal scope,
* conflict state,
* provenance.

---

# 20.4 Query Semantics

A KnowledgeOS inquiry can be represented as:

$$
Q=
\langle
Target,
Predicate,
Scope,
Context,
Purpose,
Time,
Contract
\rangle.
$$

For example:

$$
Q=
\langle
p,
CurrentStatus,
OrganizationX,
DecisionContext,
RiskAssessment,
t,
EC
\rangle.
$$

The query must therefore define what counts as a relevant answer.

Without this, retrieval relevance cannot be rigorously evaluated.

---

# 20.5 Retrieval Function

Define:

$$
Ret:
K\times Q\times \Gamma
\rightarrow
\mathcal P(X)
$$

where \(X\) is the universe of retrievable objects.

The result may be:

$$
R=
\{x_1,\ldots,x_k\}.
$$

Each object may receive a retrieval score:

$$
s(x_i,Q).
$$

The score determines ranking.

It does not automatically determine epistemic quality.

Thus:

$$
s(x,Q)
\neq
TruthScore(x).
$$

---

# 20.6 Relevance Is Not Truth

Suppose a document has:

$$
Relevance(d,Q)=0.97.
$$

This means only that the retrieval function considers the document highly relevant to the query.

It does not imply:

$$
Truth(d)=1.
$$

Nor:

$$
EvidenceStrength(d,Q)=0.97.
$$

Nor:

$$
Determined(Q).
$$

Therefore:

$$
\boxed{
Relevance\neq
Truth\neq
EvidenceStrength\neq
Determination.
}
$$

---

# 20.7 Retrieval Ranking

A ranking function may be represented as:

$$
Rank:
X\times Q
\rightarrow
\mathbb R.
$$

Examples include:

* lexical relevance,
* BM25-like scoring,
* vector similarity,
* hybrid ranking,
* graph distance,
* learned ranking.

Each defines its own semantics.

A ranking function is therefore a model:

$$
M_{rank}
$$

with assumptions and performance characteristics.

Its output must not be silently promoted into epistemic standing.

---

# 20.8 Precision and Recall

Retrieval quality can be evaluated statistically.

For a relevant set:

$$
Rel(Q)
$$

and retrieved set:

$$
Ret(Q),
$$

precision is:

$$
Precision
=
\frac{|Ret(Q)\cap Rel(Q)|}
{|Ret(Q)|}
$$

and recall:

$$
Recall
=
\frac{|Ret(Q)\cap Rel(Q)|}
{|Rel(Q)|}.
$$

These are retrieval metrics.

They do not establish truth.

A retrieval system may have excellent recall while retrieving mostly false claims.

Therefore:

$$
HighRecall
\not\Rightarrow
HighTruthfulness.
$$

Likewise:

$$
HighPrecision
\not\Rightarrow
Truth.
$$

Precision and recall concern retrieval classification relative to a reference definition of relevance.

---

# 20.9 Retrieval Ground Truth

Even retrieval evaluation requires a reference.

Let:

$$
Rel^*(Q)
$$

represent a validated relevance set.

Then measured precision and recall depend on:

$$
Rel^*.
$$

But relevance itself may be:

* context-dependent,
* purpose-dependent,
* temporal,
* participant-dependent,
* contract-dependent.

Therefore retrieval evaluation requires a declared evaluation contract.

---

# 20.10 Search Is Not Retrieval Alone

A search operation may include:

$$
Query
\rightarrow
CandidateGeneration
\rightarrow
Ranking
\rightarrow
Filtering
\rightarrow
Presentation.
$$

The displayed result is therefore a representation of a retrieval process.

The user may see only:

```text
Document A
Document B
Document C
```

but the system must preserve enough provenance to explain:

* why they were selected,
* which query was used,
* which index,
* which ranking model,
* which version,
* which timestamp,
* which filters.

This is retrieval provenance.

---

# 20.11 Retrieval Provenance

Define:

$$
RP=
\langle
Query,
Retriever,
RetrieverVersion,
Index,
Timestamp,
Filters,
Ranking,
Candidates,
SelectionRule
\rangle.
$$

A retrieval result may therefore be represented as:

$$
RR=
\langle
Object,
Score,
Rank,
RP
\rangle.
$$

This permits reproducibility of retrieval.

Without retrieval provenance:

$$
SameQuery
\not\Rightarrow
SameResult.
$$

---

# 20.12 Retrieval Is Temporal

The same query at different times may produce different results.

Let:

$$
R_t(Q)
$$

be retrieval at time \(t\).

Then:

$$
R_{t_1}(Q)
\neq
R_{t_2}(Q)
$$

may occur because:

* new documents arrived,
* documents were retracted,
* ranking models changed,
* indexes changed,
* permissions changed,
* embeddings changed,
* knowledge changed.

Therefore:

$$
Query
+
Time
$$

is often part of the retrieval semantics.

---

# 20.13 Snapshot Retrieval

A reproducible query may require:

$$
R(Q,K_t)
$$

rather than:

$$
R(Q,K_{now}).
$$

This is especially important for:

* historical decisions,
* audits,
* experiments,
* legal review,
* scientific reproducibility.

The question may be:

> What would the system have retrieved using the knowledge available at time \(t\)?

This differs from:

> What does the current system retrieve today?

---

# 20.14 Retrieval and Knowledge Views

Because KnowledgeOS supports participant-specific views:

$$
View_A(K)
$$

retrieval may depend on participant \(A\):

$$
R_A(Q)=Ret(View_A(K),Q).
$$

Therefore:

$$
R_A(Q)\neq R_B(Q)
$$

may be legitimate.

Different retrieval results do not necessarily indicate inconsistency.

They may reflect different authorization or context.

---

# 20.15 Lexical Search

Lexical search operates over textual or symbolic representations.

Conceptually:

$$
Ret_L(Q,D)
$$

uses token or term relationships.

Advantages include:

* exact terminology,
* identifiers,
* legal phrases,
* version numbers,
* domain-specific symbols.

Its limitation is that lexical similarity does not necessarily capture semantic equivalence.

Thus:

$$
LexicalMatch
\neq
SemanticEquivalence.
$$

---

# 20.16 Semantic Search

Semantic search uses a representation:

$$
\phi:X\rightarrow\mathbb R^n
$$

and a similarity function:

$$
sim(\phi(x),\phi(q)).
$$

For example:

$$
sim(x,q)=
\frac{\phi(x)\cdot\phi(q)}
{\|\phi(x)\|\|\phi(q)\|}.
$$

The resulting score is model-relative.

Therefore:

$$
sim(x,q)=0.91
$$

means:

> The chosen representation and similarity function assign high similarity.

It does not mean:

$$
x\equiv_{sem}q.
$$

---

# 20.17 Embedding Equivalence Fallacy

A dangerous assumption is:

$$
sim(x,y)\approx 1
\Rightarrow
x\equiv_{sem}y.
$$

This implication is generally invalid.

Embeddings compress information.

Different concepts may map to nearby regions.

Important distinctions may disappear.

Thus:

$$
EmbeddingSpace
$$

is a retrieval representation, not a complete semantic ontology.

---

# 20.18 Embedding Drift

If embedding model \(E_1\) is replaced by \(E_2\):

$$
\phi_{E_1}(x)
$$

may differ substantially from:

$$
\phi_{E_2}(x).
$$

Therefore the same query can produce different retrieval rankings.

This is:

$$
RetrievalModelDrift.
$$

Embedding versions must therefore be part of retrieval provenance.

---

# 20.19 Hybrid Retrieval

A robust retrieval architecture may combine:

$$
R=
R_{lexical}
\cup
R_{semantic}
\cup
R_{structured}
\cup
R_{graph}.
$$

This is often useful because different retrieval methods preserve different distinctions.

For example:

* lexical search preserves exact terminology,
* semantic search captures conceptual similarity,
* structured queries enforce exact predicates,
* graph traversal follows explicit relations.

But their results remain different semantic classes.

---

# 20.20 Structured Retrieval

A structured query can use explicit predicates:

$$
SELECT\ x
$$

such that:

$$
Status(x)=Established.
$$

This can be much more precise than semantic search.

But the result is only as correct as the stored status and the contract defining it.

Therefore:

$$
DatabasePrecision
\neq
EpistemicCorrectness.
$$

---

# 20.21 Knowledge Graph Retrieval

Let:

$$
G=(V,E)
$$

be a typed knowledge graph.

Graph retrieval may ask for:

$$
Neighbors(v)
$$

or:

$$
Paths(v_1,v_2).
$$

But a path does not automatically constitute inference.

Suppose:

$$
A\rightarrow B
$$

and:

$$
B\rightarrow C.
$$

The existence of a path:

$$
A\rightarrow B\rightarrow C
$$

does not establish:

$$
A\rightarrow C.
$$

Path composition requires explicit semantic rules.

---

# 20.22 Path Is Not Inference

Define:

$$
Path(G,A,C)
$$

as graph connectivity.

Define:

$$
Infer(A,C,\Gamma)
$$

as a valid inference under contract \(\Gamma\).

Then generally:

$$
Path(G,A,C)
\not\Rightarrow
Infer(A,C,\Gamma).
$$

This is a fundamental KnowledgeOS principle.

A graph may contain relationships that are:

* descriptive,
* temporal,
* organizational,
* causal,
* evidential,
* probabilistic,
* administrative.

Their composition is not universally valid.

---

# 20.23 Relation-Type Constraints

Suppose:

$$
Supports(A,B)
$$

and:

$$
Supports(B,C).
$$

It does not follow that:

$$
Supports(A,C).
$$

Similarly:

$$
DependsOn(A,B)
$$

and:

$$
DependsOn(B,C)
$$

may imply a transitive dependency only if the relation contract defines transitivity.

Therefore relation composition must be type-specific.

---

# 20.24 Graph Traversal

A graph traversal algorithm answers a structural question:

> Which nodes and edges are reachable under traversal rule \(T\)?

It does not automatically answer:

> Which conclusions are epistemically justified?

Thus:

$$
Traversal
\neq
Inference.
$$

A traversal may provide premises for an inference process.

That inference must then be separately evaluated.

---

# 20.25 Retrieval as Candidate Generation

A useful architectural interpretation is:

$$
Retrieval
:
Question
\rightarrow
CandidateInformation.
$$

It narrows the search space.

It does not necessarily establish the answer.

Thus:

$$
Retrieval
\approx
CandidateGeneration
$$

for many KnowledgeOS workflows.

This is particularly important for AI systems.

---

# 20.26 RAG

Retrieval-Augmented Generation can be represented as:

$$
Q
\rightarrow
R(K,Q)
\rightarrow
Context(R)
\rightarrow
Generator(Q,R)
\rightarrow
Answer.
$$

The generator receives retrieved material.

But:

$$
GeneratedAnswer
\neq
RetrievedEvidence.
$$

And:

$$
GeneratedAnswer
\neq
Determination
$$

unless a separate epistemic process validates it.

---

# 20.27 RAG Does Not Automatically Ground Truth

A common misconception is:

> “The answer is grounded because the model retrieved documents.”

Retrieval provides a grounding mechanism.

It does not prove:

$$
Truth(Answer).
$$

The documents themselves may be:

* wrong,
* outdated,
* contradictory,
* incomplete,
* irrelevant,
* generated,
* unauthorized.

Therefore:

$$
RAG
\neq
TruthGuarantee.
$$

---

# 20.28 RAG Evidence Chain

A KnowledgeOS RAG process should preserve:

$$
Q
\rightarrow
Retriever
\rightarrow
Documents
\rightarrow
Chunks
\rightarrow
Claims
\rightarrow
Inference
\rightarrow
Answer.
$$

Each transformation should remain inspectable.

For example:

$$
Answer
\rightarrow
Claim
\rightarrow
SourceChunk
\rightarrow
SourceDocument.
$$

This creates answer provenance.

---

# 20.29 Citation Is Not Proof

An AI-generated answer may cite a document.

That establishes:

$$
Answer
\rightarrow
Source.
$$

It does not necessarily establish:

$$
Source
\models
Answer.
$$

The cited source may not support the exact claim.

Therefore:

$$
CitationPresence
\neq
CitationValidity.
$$

A stronger system must evaluate entailment or support.

---

# 20.30 Support Verification

Let:

$$
Supports(d,p,\Gamma)
$$

represent the proposition that document \(d\) supports proposition \(p\) under contract \(\Gamma\).

This relation itself may require evaluation.

Thus:

$$
Retrieved(d)
$$

does not imply:

$$
Supports(d,p,\Gamma).
$$

The pipeline becomes:

$$
Retrieve
\rightarrow
EvaluateSupport
\rightarrow
UseAsEvidence.
$$

---

# 20.31 Evidence Selection

Given retrieved set:

$$
R=\{r_1,\ldots,r_n\},
$$

an evidence-selection function may produce:

$$
E=Select(R,Q,\Gamma).
$$

Selection may consider:

* authority,
* relevance,
* recency,
* provenance,
* independence,
* methodological quality,
* contradiction,
* completeness.

Again:

$$
Selected
\neq
True.
$$

It means selected as potentially useful evidence under the contract.

---

# 20.32 Independent Evidence

Multiple retrieved documents may appear independent but derive from one original source.

Suppose:

$$
d_1\leftarrow s
$$

$$
d_2\leftarrow s
$$

$$
d_3\leftarrow s.
$$

Then:

$$
Distinct(d_1,d_2,d_3)
$$

does not imply:

$$
Independent(d_1,d_2,d_3).
$$

This is especially important for web retrieval.

Ten websites repeating the same press release do not constitute ten independent observations.

---

# 20.33 Evidence Multiplicity

Therefore evidence aggregation must account for provenance.

A naïve system may calculate:

$$
Score(E)=\sum_i w_i.
$$

This can be invalid when evidence is dependent.

A better model must represent dependency:

$$
P(E_1,\ldots,E_n)
$$

under an explicit statistical or epistemic model.

---

# 20.34 Retrieval Bias

Retrieval systems can introduce selection bias.

Let the true relevant population be:

$$
D^*.
$$

The retrieval system produces:

$$
D_R\subseteq D^*.
$$

If selection probability depends on attributes related to the inquiry, then retrieved evidence may not represent the relevant population.

Thus:

$$
RetrievedSample
\neq
RepresentativeSample
$$

without a sampling argument.

---

# 20.35 Search Engine Ranking Is a Sampling Mechanism

A ranking system selects which information the user sees.

Therefore ranking can influence:

$$
ObservedEvidence.
$$

This creates a feedback loop:

$$
Ranking
\rightarrow
Exposure
\rightarrow
HumanSelection
\rightarrow
EvidenceUse
\rightarrow
Knowledge.
$$

The retrieval layer can therefore affect epistemic outcomes.

This makes retrieval governance part of the KnowledgeOS epistemic architecture.

---

# 20.36 Position Bias

Users may inspect the first result more often than the tenth.

Thus:

$$
Rank
\rightarrow
ProbabilityOfInspection.
$$

A ranking algorithm can therefore influence effective evidence exposure.

This is not necessarily undesirable.

But it must be recognized as a system effect.

---

# 20.37 Query Expansion

AI or search systems may transform:

$$
Q
\rightarrow
Q'
$$

through query expansion.

Examples include:

* synonyms,
* related concepts,
* entity resolution,
* inferred terms.

But:

$$
Q'\neq Q
$$

necessarily.

The transformation should preserve the user's intended semantics.

Otherwise retrieval can drift away from the actual inquiry.

---

# 20.38 Query Interpretation as Inference

Some query interpretation operations are themselves inferential.

For example:

> “What caused the increase?”

may be interpreted as a causal inquiry.

But:

> “Why did sales increase?”

could also mean:

* descriptive explanation,
* causal explanation,
* business attribution,
* temporal comparison.

Therefore query interpretation may need uncertainty:

$$
Interpret(Q)
=
\{I_1,I_2,\ldots,I_n\}.
$$

The system should preserve ambiguity when it cannot safely resolve it.

---

# 20.39 Ambiguous Query

Let:

$$
I(Q)=\{I_1,I_2\}
$$

be two plausible interpretations.

The system should not silently select:

$$
I_1
$$

as though:

$$
I_1=True.
$$

Instead it may:

* ask for clarification,
* return results for both,
* explicitly state the interpretation used.

This follows the broader principle:

$$
Unknown\neq False
$$

and:

$$
Ambiguous\neq Resolved.
$$

---

# 20.40 Retrieval Contracts

Define a Retrieval Contract:

$$
RC=
\langle
Question,
Scope,
Sources,
TimeWindow,
RetrievalMethods,
RankingMethod,
Filters,
MinimumRecall,
MinimumPrecision,
Freshness,
ProvenanceRequirements
\rangle.
$$

The contract defines what constitutes adequate retrieval.

This is analogous to the statistical and causal contracts developed earlier.

---

# 20.41 Retrieval Knowledge Gap

Define:

$$
\Delta_R
=
\{r\in Req(RC):\neg Sat(K,r)\}.
$$

Possible gaps include:

* source coverage unknown,
* temporal coverage incomplete,
* provenance missing,
* relevant documents inaccessible,
* ranking model unvalidated,
* retrieval recall unknown,
* contradictory sources unresolved.

Thus:

$$
RetrievalCompleteness
$$

is itself contract-relative.

---

# 20.42 Retrieval Zero

Define:

$$
Zero_R(K,RC)
\iff
\Delta_R=\varnothing.
$$

This means:

> The retrieval process satisfies the explicit retrieval contract.

It does not mean:

> All relevant information in reality has been found.

Nor:

> The retrieved information is true.

Nor:

> The final answer is correct.

Therefore:

$$
Zero_R
\neq
Truth.
$$

---

# 20.43 Completeness of Retrieval

Suppose:

$$
Rel(Q)
$$

is the set of all relevant objects.

A retrieval process is complete for \(Q\) if:

$$
Rel(Q)\subseteq Ret(Q).
$$

In practice, \(Rel(Q)\) is often unknown.

Therefore empirical recall is estimated against a reference set:

$$
Rel^*(Q).
$$

This distinction must be explicit.

---

# 20.44 Open-World Retrieval

KnowledgeOS should generally assume an open-world condition unless the contract states otherwise.

That means:

$$
x\notin Ret(Q)
$$

does not imply:

$$
x\notin Reality.
$$

Nor:

$$
x\notin Knowledge.
$$

It may simply mean:

* retrieval failure,
* inaccessible source,
* indexing failure,
* ranking exclusion,
* insufficient query formulation.

Thus:

$$
NotRetrieved
\neq
Nonexistent.
$$

---

# 20.45 Closed-World Queries

Some bounded contexts may intentionally define a closed universe.

For example:

> “Within this approved dataset, return all records satisfying condition X.”

Then:

$$
Universe=U_{contract}
$$

and absence may have a stronger interpretation.

But the closed-world assumption must be explicit.

Therefore:

$$
ClosedWorld
$$

is a contract property, not a universal KnowledgeOS assumption.

---

# 20.46 Negative Retrieval Results

Suppose a search returns:

$$
R=\varnothing.
$$

This means:

$$
NoResult
$$

under the retrieval configuration.

It does not automatically mean:

$$
False(Q).
$$

Nor:

$$
Unknown(Q)
$$

without further semantics.

The system should preserve:

$$
NoRetrievalResult
$$

as a distinct state.

---

# 20.47 Retrieval Failure

Retrieval may fail because:

* source unavailable,
* index unavailable,
* query malformed,
* permissions insufficient,
* embedding service unavailable,
* timeout,
* ranking failure.

A failed retrieval should not be represented as:

$$
NoEvidenceExists.
$$

Instead:

$$
RetrievalFailure
$$

is an operational state.

This distinction is critical.

---

# 20.48 Retrieval Confidence

A retrieval system may estimate confidence in relevance.

For example:

$$
P(Rel|Q,x).
$$

But this is a model output.

It does not mean:

$$
P(Truth(x)|Q).
$$

The two quantities are different.

Therefore:

$$
RetrievalProbability
\neq
TruthProbability.
$$

---

# 20.49 Answer Generation

Let:

$$
G(Q,E,M)
$$

generate an answer from question \(Q\), evidence \(E\), and model \(M\).

Then:

$$
A=G(Q,E,M).
$$

The answer is a model-generated representation.

It may contain:

* direct quotations,
* paraphrases,
* deductions,
* interpolations,
* assumptions,
* hallucinations.

Therefore answer generation must preserve provenance at the claim level where required.

---

# 20.50 Claim-Level Provenance

Instead of attaching one source list to an entire answer, KnowledgeOS should ideally represent:

$$
Claim_i
\rightarrow
EvidenceSet_i
\rightarrow
SourceSet_i.
$$

This permits:

* claim-level verification,
* contradiction detection,
* unsupported-claim detection,
* provenance tracing.

Thus:

$$
Answer
=
\{Claim_1,\ldots,Claim_n\}
$$

rather than an indivisible textual object.

---

# 20.51 Unsupported Claim Detection

Let:

$$
Claims(A)=\{c_1,\ldots,c_n\}.
$$

Define:

$$
Supported(c_i,E,\Gamma).
$$

Then unsupported claims are:

$$
U(A,E,\Gamma)
=
\{c_i:\neg Supported(c_i,E,\Gamma)\}.
$$

A well-governed answer process should expose:

$$
U\neq\varnothing
$$

rather than silently presenting unsupported claims as established.

---

# 20.52 Inference After Retrieval

Once evidence has been retrieved and evaluated, inference may begin:

$$
E
\xrightarrow{I}
C.
$$

Inference requires:

* premises,
* rules,
* models,
* assumptions,
* scope,
* uncertainty.

Thus retrieval is upstream of inference but does not replace it.

The complete architecture is:

$$
Query
\rightarrow
Retrieval
\rightarrow
EvidenceEvaluation
\rightarrow
Inference
\rightarrow
Determination.
$$

---

# 20.53 Retrieval-Inference Boundary

The architectural boundary can be formalized:

$$
Ret(K,Q)
\rightarrow
E
$$

followed by:

$$
Infer(E,Q,\Gamma)
\rightarrow
C.
$$

The first operation selects information.

The second transforms premises into conclusions.

Therefore:

$$
Ret\neq Infer.
$$

This distinction should remain visible in the domain model and APIs.

---

# 20.54 AI as Retriever and Generator

An AI system may participate in:

$$
Retrieve
$$

and:

$$
Generate.
$$

Neither role automatically gives it epistemic authority.

AI-generated retrieval queries are:

$$
CandidateQuery.
$$

AI-generated evidence classifications are:

$$
CandidateEvaluation.
$$

AI-generated conclusions are:

$$
CandidateInference.
$$

Their epistemic status depends on validation.

---

# 20.55 Hallucination as Semantic Inflation

Hallucination can be characterized more precisely than:

> “The model made something up.”

A KnowledgeOS-relevant definition is:

> **Semantic inflation occurs when a generated representation is assigned a stronger epistemic category than its supporting evidence and contract justify.**

For example:

$$
Association
\rightarrow
Causation
$$

or:

$$
Prediction
\rightarrow
Observation
$$

or:

$$
Hypothesis
\rightarrow
Determination.
$$

Thus hallucination is often a failure of semantic control.

---

# 20.56 Retrieval Hallucination

Retrieval systems can also hallucinate.

Examples include:

* nonexistent documents,
* incorrect source metadata,
* wrong chunk boundaries,
* incorrect source associations,
* fabricated citations.

Therefore:

$$
RetrieverOutput
$$

requires provenance verification.

---

# 20.57 Citation Integrity

For citation \(c\) referencing source \(s\), KnowledgeOS may require:

$$
Exists(s)
$$

$$
Accessible(s)
$$

$$
IdentityCorrect(c,s)
$$

$$
Supports(s,p)
$$

$$
TemporalFit(s,Q)
$$

$$
ScopeFit(s,Q).
$$

Only then should citation confidence increase.

---

# 20.58 Retrieval and Contradiction

Suppose retrieval returns:

$$
E_1\models p
$$

and:

$$
E_2\models \neg p.
$$

The retrieval layer should preserve both.

It should not automatically resolve:

$$
p
$$

versus:

$$
\neg p.
$$

Conflict evaluation belongs downstream.

Thus:

$$
Retrieval
\rightarrow
ConflictPreservation
\rightarrow
Evaluation.
$$

---

# 20.59 Retrieval and Temporal Validity

An old source may be highly relevant but temporally invalid.

Therefore retrieval should distinguish:

$$
Relevant
$$

from:

$$
CurrentlyValid.
$$

A historical source can be valuable evidence for historical questions.

Thus:

$$
TemporalRelevance(Q)
$$

must be part of the retrieval contract when applicable.

---

# 20.60 Freshness

Freshness can be defined relative to a temporal contract.

For example:

$$
Fresh(x,t,\tau)
\iff
t-t_{source}(x)\leq\tau.
$$

But freshness does not imply accuracy.

Thus:

$$
Fresh
\neq
True.
$$

A recent incorrect source remains incorrect.

---

# 20.61 Authority Filtering

Retrieval may filter sources by authority.

For example:

$$
Authority(s)\geq a_0.
$$

But authority does not automatically establish truth.

Authority may increase admissibility under a contract.

Thus:

$$
Authority
\neq
Truth.
$$

This preserves the distinction between governance and epistemology.

---

# 20.62 Retrieval and Source Diversity

A retrieval contract may require diversity:

$$
Diversity(E)\geq d_0.
$$

This can reduce dependence on a single source family.

But diversity does not prove independence.

Therefore:

$$
DiverseSources
\neq
IndependentEvidence.
$$

Provenance analysis remains necessary.

---

# 20.63 Retrieval as an Epistemic Instrument

Retrieval is not itself knowledge.

It is an epistemic instrument.

An instrument can be evaluated for:

* sensitivity,
* specificity,
* recall,
* precision,
* calibration,
* robustness,
* bias,
* coverage.

This parallels measurement theory.

Thus:

$$
RetrievalSystem
$$

can be treated as a measurement process over an information universe.

---

# 20.64 Retrieval Measurement Error

Let:

$$
R^*(Q)
$$

be the ideal relevant set and:

$$
R(Q)
$$

the retrieved set.

Then:

$$
FalsePositive
=
R(Q)\setminus R^*(Q)
$$

and:

$$
FalseNegative
=
R^*(Q)\setminus R(Q).
$$

These are retrieval errors.

They are not automatically epistemic falsehoods in the retrieved objects themselves.

---

# 20.65 Retrieval Calibration

If a retrieval model outputs:

$$
\hat p=P(Rel|x,Q),
$$

calibration asks whether:

$$
P(Rel| \hat p\approx c)\approx c.
$$

This is a statistical property of the retrieval model.

It should not be interpreted as:

$$
P(Truth| \hat p=c).
$$

Again:

$$
RelevanceProbability
\neq
TruthProbability.
$$

---

# 20.66 Query Expansion and Semantic Risk

Suppose:

$$
Q\rightarrow Q'
$$

adds a term based on model inference.

If:

$$
Q'\not\equiv_{sem}Q,
$$

the retrieval system may answer a different question.

Therefore query transformations should retain:

$$
OriginalQuery
$$

and:

$$
TransformedQuery
$$

with provenance.

---

# 20.67 Retrieval Pipeline Object

A complete retrieval operation can be modeled as:

$$
RO=
\langle
Question,
Query,
QueryTransformation,
Retriever,
RetrieverVersion,
Corpus,
Index,
Filters,
Ranking,
Results,
Selection,
Timestamp,
Context,
Contract,
Provenance
\rangle.
$$

This makes retrieval auditable.

---

# 20.68 Evidence Retrieval Object

An evidence retrieval result can then be represented as:

$$
ERO=
\langle
EvidenceCandidate,
RetrievalOperation,
Relevance,
Source,
TemporalValidity,
Authority,
Dependency,
SelectionStatus,
Provenance
\rangle.
$$

This separates candidate retrieval from evidence acceptance.

---

# 20.69 Retrieval-to-Evidence Promotion

The promotion:

$$
RetrievedObject
\rightarrow
Evidence
$$

must be governed by an evidence contract.

A possible condition is:

$$
Promote(r,EC_E)
$$

only if:

$$
SourceValid
\land
ScopeValid
\land
TemporalValid
\land
ProvenanceValid
\land
EvidenceRequirementsSatisfied.
$$

This is a semantic transition.

It must not be hidden inside the search engine.

---

# 20.70 Retrieval-to-Inference Promotion

Likewise:

$$
Evidence
\rightarrow
InferencePremise
$$

requires:

* relevance,
* interpretation,
* proposition extraction,
* scope compatibility,
* assumptions.

Thus the pipeline is not:

$$
Search\rightarrowAnswer.
$$

It is:

$$
\boxed{
Search
\rightarrow
Candidate
\rightarrow
Evidence
\rightarrow
Premise
\rightarrow
Inference
\rightarrow
Conclusion
}
$$

with each arrow carrying its own contract.

---

# 20.71 Retrieval and the Knowledge Gap

Retrieval may reduce a gap:

$$
\Delta_{t+1}\subseteq\Delta_t
$$

when retrieved information satisfies previously unsatisfied requirements.

But retrieval can also expose missing information:

$$
\Delta_{t+1}\supset\Delta_t.
$$

For example, searching for evidence may reveal:

> No sufficiently authoritative source exists.

The visible gap increases.

This is not necessarily failure.

It can represent improved epistemic awareness.

---

# 20.72 Retrieval Can Increase Knowledge Quality While Increasing the Gap

Suppose initially:

$$
\Delta_0=\varnothing
$$

because requirements were not known.

After rigorous retrieval:

$$
\Delta_1=\{r_1,r_2,r_3\}.
$$

The gap increased.

But the system may now possess more accurate knowledge about what is missing.

Therefore:

$$
GapIncrease
\not\Rightarrow
KnowledgeQualityDecrease.
$$

This follows directly from the learning theory of Part XVII.

---

# 20.73 Retrieval Completeness Is Contextual

A retrieval operation may be complete for:

$$
Q_1
$$

but incomplete for:

$$
Q_2.
$$

Thus:

$$
Complete(R,Q_1,\Gamma_1)
$$

does not imply:

$$
Complete(R,Q_2,\Gamma_2).
$$

There is no universal notion of:

> “The knowledge base has been completely searched.”

Completeness requires a target, scope and contract.

---

# 20.74 Retrieval Architecture

A candidate architecture is:

$$
QueryContext
$$

↓

$$
QueryInterpreter
$$

↓

$$
CandidateRetrievers
$$

↓

$$
Ranker
$$

↓

$$
EvidenceSelector
$$

↓

$$
EvidenceContext
$$

↓

$$
InferenceContext
$$

↓

$$
DeterminationContext.
$$

These contexts are candidates, not necessarily separate deployable services.

---

# 20.75 Anti-Corruption Layer for Retrieval

External search engines may return their own semantics.

For example:

$$
ExternalSearchResult
=
\langle
url,
score,
snippet
\rangle.
$$

KnowledgeOS must not automatically interpret:

$$
score
$$

as evidence quality.

The ACL maps:

$$
ExternalSearchResult
\rightarrow
KnowledgeOS RetrievalResult.
$$

The mapping must explicitly define the meaning of each field.

---

# 20.76 Search Index as a Projection

A search index is usually:

$$
I=f(K).
$$

It is therefore a projection.

The index may omit:

* history,
* conflicts,
* provenance,
* temporal semantics,
* authorization details.

That may be acceptable for retrieval.

But:

$$
Index\neq CanonicalKnowledgeState.
$$

---

# 20.77 Index Rebuilding

If:

$$
I=f(K),
$$

then the index can ideally be rebuilt from canonical state:

$$
Rebuild(K)\rightarrow I'.
$$

A rebuilt index should satisfy:

$$
Sem(I')\equiv_Q Sem(I)
$$

under the retrieval contract, subject to model/version changes.

If this cannot be guaranteed, index state itself may need historical preservation.

---

# 20.78 Search Index Drift

Index drift occurs when:

$$
I_t\not\approx f(K_t).
$$

Causes include:

* failed updates,
* stale embeddings,
* deleted documents remaining indexed,
* missing newly ingested documents,
* schema mismatch.

Therefore index health is a semantic fitness property.

---

# 20.79 Retrieval Reproducibility

A retrieval operation is reproducible when the relevant inputs and versions allow:

$$
Replay(RO)
\approx
Results_{original}.
$$

This may require:

* corpus snapshot,
* index version,
* embedding model version,
* ranking model version,
* query transformation version,
* filters,
* permissions,
* time.

Without these:

$$
SameQuery
\not\Rightarrow
SameRetrieval.
$$

---

# 20.80 Retrieval Reproducibility Versus Answer Reproducibility

Even if retrieval is reproducible:

$$
R_1=R_2,
$$

generation may still differ because of:

* model version,
* stochastic decoding,
* system prompt,
* temperature,
* context ordering.

Thus:

$$
RetrievalReproducibility
\neq
AnswerReproducibility.
$$

Both must be specified separately.

---

# 20.81 Deterministic and Stochastic Retrieval

Some retrieval is deterministic:

$$
R(Q)=R.
$$

Other retrieval uses stochastic components:

$$
R(Q,\omega).
$$

Then repeated executions may produce:

$$
R_1\neq R_2.
$$

The system should preserve random seeds or stochastic configuration when reproducibility is required.

---

# 20.82 Retrieval and Decision Risk

A retrieval error can propagate downstream:

$$
RetrievalError
\rightarrow
EvidenceError
\rightarrow
InferenceError
\rightarrow
RiskError
\rightarrow
DecisionError.
$$

This is not automatically a causal claim.

It is an architectural dependency chain.

The system should therefore track:

$$
Impact(RetrievalFailure).
$$

---

# 20.83 Retrieval Dependency Graph

Let:

$$
G_D
$$

be a dependency graph.

If:

$$
Decision D
$$

depends on:

$$
Evidence E
$$

which depends on:

$$
Retrieval R,
$$

then:

$$
D\rightarrow E\rightarrow R
$$

defines an impact path.

If \(R\) is later invalidated, dependent conclusions may require re-evaluation.

But propagation must follow explicit dependency semantics.

---

# 20.84 Retrieval Invalidation

An index may contain a source later retracted.

Then:

$$
SourceStatus:
Valid\rightarrow Retracted.
$$

The system should identify:

$$
AffectedRetrievals
$$

and potentially:

$$
AffectedEvidence
$$

and:

$$
AffectedDeterminations.
$$

This is a direct application of the revision theory.

---

# 20.85 Retrieval Is Not a One-Time Operation

Because knowledge evolves:

$$
K_t\rightarrow K_{t+1},
$$

retrieval results evolve:

$$
R_t(Q)\rightarrow R_{t+1}(Q).
$$

Therefore a previously valid answer may require reevaluation.

This creates a monitoring possibility:

$$
ChangedRetrieval(Q)
\rightarrow
Review.
$$

---

# 20.86 Retrieval as Part of the Knowledge Lifecycle

The full cycle becomes:

$$
Observation
\rightarrow
Persistence
\rightarrow
Retrieval
\rightarrow
EvidenceEvaluation
\rightarrow
Inference
\rightarrow
Determination
\rightarrow
Decision
\rightarrow
Action
\rightarrow
Outcome
\rightarrow
Observation.
$$

Retrieval is therefore not an auxiliary UI feature.

It is part of the epistemic infrastructure.

---

# 20.87 DDD Concepts for Retrieval

Candidate domain concepts include:

* Inquiry,
* Query,
* QueryInterpretation,
* QueryTransformation,
* RetrievalContract,
* RetrievalOperation,
* RetrievalResult,
* RetrievalRank,
* RetrievalScore,
* SearchCorpus,
* Index,
* IndexVersion,
* RankingModel,
* EmbeddingModel,
* EvidenceCandidate,
* EvidenceSelection,
* Citation,
* CitationValidation,
* SourceCoverage,
* RetrievalEvaluation,
* RetrievalGap,
* RetrievalFailure.

These are candidate concepts.

They should be validated through domain discovery.

---

# 20.88 Candidate Retrieval Bounded Context

A candidate:

$$
BC_{Retrieval}
$$

could own:

* query interpretation,
* retrieval strategies,
* ranking,
* index management,
* retrieval provenance,
* retrieval evaluation.

It should not automatically own:

* truth,
* final epistemic determination,
* causal claims,
* decision authority.

This protects the semantic boundary.

---

# 20.89 Retrieval Context and Evidence Context

A useful boundary is:

$$
RetrievalContext
\rightarrow
EvidenceContext.
$$

The retrieval context answers:

> What information should be considered?

The evidence context answers:

> Which retrieved objects qualify as evidence under the applicable contract?

These are different questions.

---

# 20.90 Evidence Context and Inference Context

Then:

$$
EvidenceContext
\rightarrow
InferenceContext.
$$

The evidence context establishes the admissible evidence.

The inference context determines what can be concluded from that evidence.

Therefore:

$$
EvidenceSelection
\neq
Inference.
$$

---

# 20.91 Query as Domain Object

An inquiry may have a lifecycle:

$$
Created
\rightarrow
Interpreted
\rightarrow
Retrieved
\rightarrow
Evaluated
\rightarrow
Answered
\rightarrow
Reviewed.
$$

A query can therefore itself become a domain object where traceability is important.

This is especially useful for:

* regulated decisions,
* scientific research,
* enterprise knowledge,
* audit workflows.

---

# 20.92 Query Identity

Two identical text queries may have different semantic identities.

For example:

```text
“What is the current status?”
```

asked:

* by different participants,
* at different times,
* under different contracts,
* for different purposes,

may represent different inquiries.

Therefore:

$$
TextEquality
\neq
InquiryIdentity.
$$

---

# 20.93 Query Equivalence

Define:

$$
Q_1\equiv_{\Gamma}Q_2
$$

if they are semantically equivalent under contract \(\Gamma\).

Textual difference does not imply semantic difference:

$$
Text(Q_1)\neq Text(Q_2)
$$

while:

$$
Q_1\equiv_{\Gamma}Q_2.
$$

Conversely:

$$
Text(Q_1)=Text(Q_2)
$$

does not imply:

$$
Q_1\equiv_{\Gamma}Q_2.
$$

---

# 20.94 Retrieval Equivalence

Two retrieval systems may produce different physical result sets while being equivalent for a contract.

If:

$$
R_1\neq R_2
$$

but:

$$
Relevant_{EC}(R_1)
=
Relevant_{EC}(R_2),
$$

then:

$$
R_1\equiv_{EC}R_2.
$$

This is important for technology migration.

A new search engine need not reproduce every byte of the old result.

It must preserve required semantics.

---

# 20.95 Retrieval Migration

Suppose:

$$
Retriever_1
\rightarrow
Retriever_2.
$$

A migration should evaluate:

* recall,
* precision,
* ranking stability,
* source coverage,
* temporal behavior,
* semantic equivalence,
* citation validity,
* downstream answer quality.

Thus:

$$
RetrieverMigration
$$

is an empirical and semantic verification problem.

---

# 20.96 Retrieval Model Governance

Retrieval models should be versioned.

For a retrieval result:

$$
RR=
\langle
Query,
Result,
ModelVersion,
IndexVersion,
Time,
Provenance
\rangle.
$$

This permits:

> Why was this document ranked first?

to become an answerable question.

---

# 20.97 The Retrieval Evidence Principle

### Principle

A retrieval result may become evidence only through an explicit evidence qualification process.

Formally:

$$
Retrieved(x)
\not\Rightarrow
Evidence(x).
$$

Instead:

$$
Retrieved(x)
\land
Qualifies(x,EC_E)
\Rightarrow
Evidence(x).
$$

This is a fundamental architectural boundary.

---

# 20.98 The Retrieval Inference Theorem

### Theorem 20.1

Retrieval alone cannot establish a conclusion unless the retrieval contract itself contains a valid inference rule that maps the retrieval condition to that conclusion.

Formally:

$$
Ret(K,Q)=R
$$

does not imply:

$$
K\models Q
$$

unless an explicit valid rule establishes the implication.

### Proof

Retrieval identifies objects satisfying a retrieval criterion.

Truth or determination requires a semantic relation between those objects and the proposition.

Since relevance and truth are distinct predicates:

$$
Relevant(x,Q)\not\Rightarrow Truth(Q),
$$

the implication is invalid without an additional rule.

$$
\Box
$$

---

# 20.99 The Graph Path Theorem

### Theorem 20.2

A path in a typed knowledge graph does not constitute an inference unless the composition of its relation types is licensed by the relevant relation algebra.

Formally:

$$
Path_G(x,y)
\not\Rightarrow
x\vdash y
$$

unless:

$$
Composition_\Gamma(Path_G)
$$

is valid.

This prevents accidental inference through graph traversal.

---

# 20.100 The RAG Separation Theorem

### Theorem 20.3

A generated answer from retrieved information is not automatically a determination.

Let:

$$
A=G(Q,R).
$$

Then:

$$
A\neq Determination(Q)
$$

unless an independent epistemic contract validates the generated claims.

Therefore:

$$
RAG
\not\Rightarrow
Determination.
$$

---

# 20.101 Retrieval Completeness Theorem

### Theorem 20.4

Retrieval completeness is always relative to a specified universe, inquiry and retrieval contract.

There is no universal predicate:

$$
Complete(R)
$$

independent of:

$$
Q,\Gamma,U.
$$

Instead:

$$
Complete(R,Q,U,\Gamma).
$$

Thus a claim such as:

> “The system searched all relevant knowledge”

is incomplete unless the relevant universe and completeness contract are defined.

---

# 20.102 Retrieval–Evidence–Inference Separation

The complete separation is:

$$
\boxed{
Retrieval
\rightarrow
EvidenceQualification
\rightarrow
Inference
\rightarrow
Determination
}
$$

where:

### Retrieval

Selects candidates.

### Evidence qualification

Determines whether candidates satisfy evidence requirements.

### Inference

Transforms admissible premises under rules/models.

### Determination

Checks whether the epistemic contract's requirements are satisfied.

No stage may silently impersonate another.

---

# 20.103 Retrieval Constitutional Statements

### XX-C1 — Query Semantics

A computational query is not automatically identical to the semantic inquiry.

### XX-C2 — Retrieval Separation

Retrieval is distinct from evidence.

### XX-C3 — Relevance Separation

Relevance is distinct from truth.

### XX-C4 — Ranking Separation

Ranking scores are not epistemic truth scores.

### XX-C5 — Similarity Separation

Embedding similarity is not semantic identity.

### XX-C6 — Graph Separation

Graph traversal is not automatically inference.

### XX-C7 — Path Separation

A graph path does not automatically justify a conclusion.

### XX-C8 — Retrieval Provenance

Retrieval operations must preserve required retrieval provenance.

### XX-C9 — Temporal Retrieval

Retrieval is time-dependent where the corpus or model changes over time.

### XX-C10 — Snapshot Semantics

Historical retrieval requires an explicit historical knowledge/index state.

### XX-C11 — Open-World Default

Absence from retrieval does not imply nonexistence unless a closed-world contract applies.

### XX-C12 — Retrieval Failure

Retrieval failure must not be represented as absence of evidence.

### XX-C13 — Evidence Promotion

Retrieved objects become evidence only under an explicit evidence contract.

### XX-C14 — Independence

Distinct retrieved sources do not automatically constitute independent evidence.

### XX-C15 — RAG Separation

Retrieval-augmented generation does not automatically establish truth or determination.

### XX-C16 — Citation Integrity

Citation presence does not establish citation validity.

### XX-C17 — Claim Provenance

Generated claims should retain source and inference provenance where required.

### XX-C18 — Model Versioning

Retrieval models, indexes and embeddings must be versioned where reproducibility requires it.

### XX-C19 — Retrieval Completeness

Retrieval completeness is contract-, inquiry- and universe-relative.

### XX-C20 — No Semantic Inflation

Retrieval, generation and similarity must never be silently promoted to stronger epistemic categories.

---

# 20.104 Final Principle of Part XX

The fundamental retrieval principle is:

$$
\boxed{
Find\ first;
qualify\ second;
infer\ third;
determine\ only\ when\ the\ contract\ permits.
}
$$

The complete architecture is:

$$
\boxed{
Inquiry
\rightarrow
Query
\rightarrow
Retrieval
\rightarrow
Candidate
\rightarrow
Evidence
\rightarrow
Premise
\rightarrow
Inference
\rightarrow
Conclusion
\rightarrow
Determination
}
$$

with provenance and contracts surrounding every transition.

For AI systems, this becomes:

$$
\boxed{
RAG
=
Retrieval
+
ContextConstruction
+
Generation
}
$$

but:

$$
\boxed{
RAG
\neq
Evidence
\neq
Inference
\neq
Truth
\neq
Determination.
}
$$

The deeper KnowledgeOS principle is therefore:

$$
\boxed{
Information\ becomes\ evidence\ only\ through\ qualification;
evidence\ becomes\ a\ premise\ only\ through\ interpretation;
premises\ become\ conclusions\ only\ through\ valid\ inference;
and\ conclusions\ become\ determinations\ only\ through\ an\ explicit\ contract.
}
$$

This establishes the boundary between **knowledge retrieval** and **knowledge reasoning**.

The next theoretical layer is therefore the mechanism that connects retrieved and qualified evidence to formal reasoning:

$$
\boxed{
Part\ XXI:
KnowledgeOS\ Reasoning\ Engine,\ Rule\ Execution,\ Constraint\ Solving,\ Proof\ Objects,\ Derivation\ Trees,\ and\ Formal\ Verification
}
$$

The central question will be:

> **When KnowledgeOS produces a conclusion, what exactly constitutes a valid derivation, how is that derivation represented, and how can the system prove that it did not silently cross an invalid semantic boundary?**

Part XX establishes the retrieval boundary. The most important architectural consequence is that **RAG should terminate in candidate evidence/context, not directly in “knowledge.”** Part XXI can now formalize the reasoning engine itself—especially proof objects, derivation provenance, strict vs defeasible rules, constraint solving, and verification of AI-generated reasoning.
Part XX is the natural continuation: once persistence preserves the semantic state, we need a rigorous theory of **how that state is queried and retrieved** without allowing search relevance, embedding similarity, graph paths, or generated text to masquerade as evidence or inference.

# Part XX — Query Semantics, Retrieval, Search, Knowledge Graph Traversal, RAG, and the Boundary Between Retrieval and Inference

## 20.1 Purpose

Part XIX established persistence as a semantic preservation problem.

The next boundary is:

$$
KnowledgeState
\rightarrow
Query
\rightarrow
Retrieval
\rightarrow
Interpretation
\rightarrow
Inference
$$

This boundary is especially important for KnowledgeOS because modern AI systems frequently collapse several distinct operations into one apparently simple action:

> “Search the knowledge and answer the question.”

Mathematically and architecturally, this is not one operation.

At minimum, it may involve:

1. query interpretation,
2. candidate retrieval,
3. filtering,
4. ranking,
5. evidence selection,
6. context construction,
7. inference,
8. answer generation,
9. confidence estimation,
10. provenance construction,
11. determination.

These operations have different semantics.

The foundational distinction is:

$$
\boxed{
Retrieval\neq Evidence\neq Inference\neq Determination.
}
$$

And therefore:

$$
\boxed{
Retrieved\ Text\neq Truth.
}
$$

---

# 20.2 The Retrieval Problem

Let:

$$
K
$$

be a KnowledgeOS state.

Let:

$$
Q
$$

be an inquiry.

A retrieval system produces:

$$
R(K,Q)
$$

where \(R\) is a set, sequence, ranking, or structured collection of candidate objects.

Thus:

$$
R(K,Q)=
\{x_1,x_2,\ldots,x_n\}.
$$

The retrieval result answers:

> Which objects were selected according to the retrieval mechanism?

It does not automatically answer:

> Which proposition is true?

Therefore:

$$
Retrieval(K,Q)
\not\Rightarrow
Truth(Q).
$$

---

# 20.3 Query Is Not Question

A query is a computational representation.

A question is a semantic inquiry.

Let:

$$
Q_s
$$

be a semantic inquiry and:

$$
Q_c
$$

its computational query representation.

Then:

$$
Encode(Q_s)=Q_c.
$$

The encoding may be lossy.

Therefore:

$$
Q_c\neq Q_s
$$

in general.

A database query such as:

```text
status = "approved"
```

does not necessarily represent the semantic question:

> “Has this proposition been determined to satisfy the current epistemic contract?”

The latter may require:

* contract version,
* evidence requirements,
* authority,
* temporal scope,
* conflict state,
* provenance.

---

# 20.4 Query Semantics

A KnowledgeOS inquiry can be represented as:

$$
Q=
\langle
Target,
Predicate,
Scope,
Context,
Purpose,
Time,
Contract
\rangle.
$$

For example:

$$
Q=
\langle
p,
CurrentStatus,
OrganizationX,
DecisionContext,
RiskAssessment,
t,
EC
\rangle.
$$

The query must therefore define what counts as a relevant answer.

Without this, retrieval relevance cannot be rigorously evaluated.

---

# 20.5 Retrieval Function

Define:

$$
Ret:
K\times Q\times \Gamma
\rightarrow
\mathcal P(X)
$$

where \(X\) is the universe of retrievable objects.

The result may be:

$$
R=
\{x_1,\ldots,x_k\}.
$$

Each object may receive a retrieval score:

$$
s(x_i,Q).
$$

The score determines ranking.

It does not automatically determine epistemic quality.

Thus:

$$
s(x,Q)
\neq
TruthScore(x).
$$

---

# 20.6 Relevance Is Not Truth

Suppose a document has:

$$
Relevance(d,Q)=0.97.
$$

This means only that the retrieval function considers the document highly relevant to the query.

It does not imply:

$$
Truth(d)=1.
$$

Nor:

$$
EvidenceStrength(d,Q)=0.97.
$$

Nor:

$$
Determined(Q).
$$

Therefore:

$$
\boxed{
Relevance\neq
Truth\neq
EvidenceStrength\neq
Determination.
}
$$

---

# 20.7 Retrieval Ranking

A ranking function may be represented as:

$$
Rank:
X\times Q
\rightarrow
\mathbb R.
$$

Examples include:

* lexical relevance,
* BM25-like scoring,
* vector similarity,
* hybrid ranking,
* graph distance,
* learned ranking.

Each defines its own semantics.

A ranking function is therefore a model:

$$
M_{rank}
$$

with assumptions and performance characteristics.

Its output must not be silently promoted into epistemic standing.

---

# 20.8 Precision and Recall

Retrieval quality can be evaluated statistically.

For a relevant set:

$$
Rel(Q)
$$

and retrieved set:

$$
Ret(Q),
$$

precision is:

$$
Precision
=
\frac{|Ret(Q)\cap Rel(Q)|}
{|Ret(Q)|}
$$

and recall:

$$
Recall
=
\frac{|Ret(Q)\cap Rel(Q)|}
{|Rel(Q)|}.
$$

These are retrieval metrics.

They do not establish truth.

A retrieval system may have excellent recall while retrieving mostly false claims.

Therefore:

$$
HighRecall
\not\Rightarrow
HighTruthfulness.
$$

Likewise:

$$
HighPrecision
\not\Rightarrow
Truth.
$$

Precision and recall concern retrieval classification relative to a reference definition of relevance.

---

# 20.9 Retrieval Ground Truth

Even retrieval evaluation requires a reference.

Let:

$$
Rel^*(Q)
$$

represent a validated relevance set.

Then measured precision and recall depend on:

$$
Rel^*.
$$

But relevance itself may be:

* context-dependent,
* purpose-dependent,
* temporal,
* participant-dependent,
* contract-dependent.

Therefore retrieval evaluation requires a declared evaluation contract.

---

# 20.10 Search Is Not Retrieval Alone

A search operation may include:

$$
Query
\rightarrow
CandidateGeneration
\rightarrow
Ranking
\rightarrow
Filtering
\rightarrow
Presentation.
$$

The displayed result is therefore a representation of a retrieval process.

The user may see only:

```text
Document A
Document B
Document C
```

but the system must preserve enough provenance to explain:

* why they were selected,
* which query was used,
* which index,
* which ranking model,
* which version,
* which timestamp,
* which filters.

This is retrieval provenance.

---

# 20.11 Retrieval Provenance

Define:

$$
RP=
\langle
Query,
Retriever,
RetrieverVersion,
Index,
Timestamp,
Filters,
Ranking,
Candidates,
SelectionRule
\rangle.
$$

A retrieval result may therefore be represented as:

$$
RR=
\langle
Object,
Score,
Rank,
RP
\rangle.
$$

This permits reproducibility of retrieval.

Without retrieval provenance:

$$
SameQuery
\not\Rightarrow
SameResult.
$$

---

# 20.12 Retrieval Is Temporal

The same query at different times may produce different results.

Let:

$$
R_t(Q)
$$

be retrieval at time \(t\).

Then:

$$
R_{t_1}(Q)
\neq
R_{t_2}(Q)
$$

may occur because:

* new documents arrived,
* documents were retracted,
* ranking models changed,
* indexes changed,
* permissions changed,
* embeddings changed,
* knowledge changed.

Therefore:

$$
Query
+
Time
$$

is often part of the retrieval semantics.

---

# 20.13 Snapshot Retrieval

A reproducible query may require:

$$
R(Q,K_t)
$$

rather than:

$$
R(Q,K_{now}).
$$

This is especially important for:

* historical decisions,
* audits,
* experiments,
* legal review,
* scientific reproducibility.

The question may be:

> What would the system have retrieved using the knowledge available at time \(t\)?

This differs from:

> What does the current system retrieve today?

---

# 20.14 Retrieval and Knowledge Views

Because KnowledgeOS supports participant-specific views:

$$
View_A(K)
$$

retrieval may depend on participant \(A\):

$$
R_A(Q)=Ret(View_A(K),Q).
$$

Therefore:

$$
R_A(Q)\neq R_B(Q)
$$

may be legitimate.

Different retrieval results do not necessarily indicate inconsistency.

They may reflect different authorization or context.

---

# 20.15 Lexical Search

Lexical search operates over textual or symbolic representations.

Conceptually:

$$
Ret_L(Q,D)
$$

uses token or term relationships.

Advantages include:

* exact terminology,
* identifiers,
* legal phrases,
* version numbers,
* domain-specific symbols.

Its limitation is that lexical similarity does not necessarily capture semantic equivalence.

Thus:

$$
LexicalMatch
\neq
SemanticEquivalence.
$$

---

# 20.16 Semantic Search

Semantic search uses a representation:

$$
\phi:X\rightarrow\mathbb R^n
$$

and a similarity function:

$$
sim(\phi(x),\phi(q)).
$$

For example:

$$
sim(x,q)=
\frac{\phi(x)\cdot\phi(q)}
{\|\phi(x)\|\|\phi(q)\|}.
$$

The resulting score is model-relative.

Therefore:

$$
sim(x,q)=0.91
$$

means:

> The chosen representation and similarity function assign high similarity.

It does not mean:

$$
x\equiv_{sem}q.
$$

---

# 20.17 Embedding Equivalence Fallacy

A dangerous assumption is:

$$
sim(x,y)\approx 1
\Rightarrow
x\equiv_{sem}y.
$$

This implication is generally invalid.

Embeddings compress information.

Different concepts may map to nearby regions.

Important distinctions may disappear.

Thus:

$$
EmbeddingSpace
$$

is a retrieval representation, not a complete semantic ontology.

---

# 20.18 Embedding Drift

If embedding model \(E_1\) is replaced by \(E_2\):

$$
\phi_{E_1}(x)
$$

may differ substantially from:

$$
\phi_{E_2}(x).
$$

Therefore the same query can produce different retrieval rankings.

This is:

$$
RetrievalModelDrift.
$$

Embedding versions must therefore be part of retrieval provenance.

---

# 20.19 Hybrid Retrieval

A robust retrieval architecture may combine:

$$
R=
R_{lexical}
\cup
R_{semantic}
\cup
R_{structured}
\cup
R_{graph}.
$$

This is often useful because different retrieval methods preserve different distinctions.

For example:

* lexical search preserves exact terminology,
* semantic search captures conceptual similarity,
* structured queries enforce exact predicates,
* graph traversal follows explicit relations.

But their results remain different semantic classes.

---

# 20.20 Structured Retrieval

A structured query can use explicit predicates:

$$
SELECT\ x
$$

such that:

$$
Status(x)=Established.
$$

This can be much more precise than semantic search.

But the result is only as correct as the stored status and the contract defining it.

Therefore:

$$
DatabasePrecision
\neq
EpistemicCorrectness.
$$

---

# 20.21 Knowledge Graph Retrieval

Let:

$$
G=(V,E)
$$

be a typed knowledge graph.

Graph retrieval may ask for:

$$
Neighbors(v)
$$

or:

$$
Paths(v_1,v_2).
$$

But a path does not automatically constitute inference.

Suppose:

$$
A\rightarrow B
$$

and:

$$
B\rightarrow C.
$$

The existence of a path:

$$
A\rightarrow B\rightarrow C
$$

does not establish:

$$
A\rightarrow C.
$$

Path composition requires explicit semantic rules.

---

# 20.22 Path Is Not Inference

Define:

$$
Path(G,A,C)
$$

as graph connectivity.

Define:

$$
Infer(A,C,\Gamma)
$$

as a valid inference under contract \(\Gamma\).

Then generally:

$$
Path(G,A,C)
\not\Rightarrow
Infer(A,C,\Gamma).
$$

This is a fundamental KnowledgeOS principle.

A graph may contain relationships that are:

* descriptive,
* temporal,
* organizational,
* causal,
* evidential,
* probabilistic,
* administrative.

Their composition is not universally valid.

---

# 20.23 Relation-Type Constraints

Suppose:

$$
Supports(A,B)
$$

and:

$$
Supports(B,C).
$$

It does not follow that:

$$
Supports(A,C).
$$

Similarly:

$$
DependsOn(A,B)
$$

and:

$$
DependsOn(B,C)
$$

may imply a transitive dependency only if the relation contract defines transitivity.

Therefore relation composition must be type-specific.

---

# 20.24 Graph Traversal

A graph traversal algorithm answers a structural question:

> Which nodes and edges are reachable under traversal rule \(T\)?

It does not automatically answer:

> Which conclusions are epistemically justified?

Thus:

$$
Traversal
\neq
Inference.
$$

A traversal may provide premises for an inference process.

That inference must then be separately evaluated.

---

# 20.25 Retrieval as Candidate Generation

A useful architectural interpretation is:

$$
Retrieval
:
Question
\rightarrow
CandidateInformation.
$$

It narrows the search space.

It does not necessarily establish the answer.

Thus:

$$
Retrieval
\approx
CandidateGeneration
$$

for many KnowledgeOS workflows.

This is particularly important for AI systems.

---

# 20.26 RAG

Retrieval-Augmented Generation can be represented as:

$$
Q
\rightarrow
R(K,Q)
\rightarrow
Context(R)
\rightarrow
Generator(Q,R)
\rightarrow
Answer.
$$

The generator receives retrieved material.

But:

$$
GeneratedAnswer
\neq
RetrievedEvidence.
$$

And:

$$
GeneratedAnswer
\neq
Determination
$$

unless a separate epistemic process validates it.

---

# 20.27 RAG Does Not Automatically Ground Truth

A common misconception is:

> “The answer is grounded because the model retrieved documents.”

Retrieval provides a grounding mechanism.

It does not prove:

$$
Truth(Answer).
$$

The documents themselves may be:

* wrong,
* outdated,
* contradictory,
* incomplete,
* irrelevant,
* generated,
* unauthorized.

Therefore:

$$
RAG
\neq
TruthGuarantee.
$$

---

# 20.28 RAG Evidence Chain

A KnowledgeOS RAG process should preserve:

$$
Q
\rightarrow
Retriever
\rightarrow
Documents
\rightarrow
Chunks
\rightarrow
Claims
\rightarrow
Inference
\rightarrow
Answer.
$$

Each transformation should remain inspectable.

For example:

$$
Answer
\rightarrow
Claim
\rightarrow
SourceChunk
\rightarrow
SourceDocument.
$$

This creates answer provenance.

---

# 20.29 Citation Is Not Proof

An AI-generated answer may cite a document.

That establishes:

$$
Answer
\rightarrow
Source.
$$

It does not necessarily establish:

$$
Source
\models
Answer.
$$

The cited source may not support the exact claim.

Therefore:

$$
CitationPresence
\neq
CitationValidity.
$$

A stronger system must evaluate entailment or support.

---

# 20.30 Support Verification

Let:

$$
Supports(d,p,\Gamma)
$$

represent the proposition that document \(d\) supports proposition \(p\) under contract \(\Gamma\).

This relation itself may require evaluation.

Thus:

$$
Retrieved(d)
$$

does not imply:

$$
Supports(d,p,\Gamma).
$$

The pipeline becomes:

$$
Retrieve
\rightarrow
EvaluateSupport
\rightarrow
UseAsEvidence.
$$

---

# 20.31 Evidence Selection

Given retrieved set:

$$
R=\{r_1,\ldots,r_n\},
$$

an evidence-selection function may produce:

$$
E=Select(R,Q,\Gamma).
$$

Selection may consider:

* authority,
* relevance,
* recency,
* provenance,
* independence,
* methodological quality,
* contradiction,
* completeness.

Again:

$$
Selected
\neq
True.
$$

It means selected as potentially useful evidence under the contract.

---

# 20.32 Independent Evidence

Multiple retrieved documents may appear independent but derive from one original source.

Suppose:

$$
d_1\leftarrow s
$$

$$
d_2\leftarrow s
$$

$$
d_3\leftarrow s.
$$

Then:

$$
Distinct(d_1,d_2,d_3)
$$

does not imply:

$$
Independent(d_1,d_2,d_3).
$$

This is especially important for web retrieval.

Ten websites repeating the same press release do not constitute ten independent observations.

---

# 20.33 Evidence Multiplicity

Therefore evidence aggregation must account for provenance.

A naïve system may calculate:

$$
Score(E)=\sum_i w_i.
$$

This can be invalid when evidence is dependent.

A better model must represent dependency:

$$
P(E_1,\ldots,E_n)
$$

under an explicit statistical or epistemic model.

---

# 20.34 Retrieval Bias

Retrieval systems can introduce selection bias.

Let the true relevant population be:

$$
D^*.
$$

The retrieval system produces:

$$
D_R\subseteq D^*.
$$

If selection probability depends on attributes related to the inquiry, then retrieved evidence may not represent the relevant population.

Thus:

$$
RetrievedSample
\neq
RepresentativeSample
$$

without a sampling argument.

---

# 20.35 Search Engine Ranking Is a Sampling Mechanism

A ranking system selects which information the user sees.

Therefore ranking can influence:

$$
ObservedEvidence.
$$

This creates a feedback loop:

$$
Ranking
\rightarrow
Exposure
\rightarrow
HumanSelection
\rightarrow
EvidenceUse
\rightarrow
Knowledge.
$$

The retrieval layer can therefore affect epistemic outcomes.

This makes retrieval governance part of the KnowledgeOS epistemic architecture.

---

# 20.36 Position Bias

Users may inspect the first result more often than the tenth.

Thus:

$$
Rank
\rightarrow
ProbabilityOfInspection.
$$

A ranking algorithm can therefore influence effective evidence exposure.

This is not necessarily undesirable.

But it must be recognized as a system effect.

---

# 20.37 Query Expansion

AI or search systems may transform:

$$
Q
\rightarrow
Q'
$$

through query expansion.

Examples include:

* synonyms,
* related concepts,
* entity resolution,
* inferred terms.

But:

$$
Q'\neq Q
$$

necessarily.

The transformation should preserve the user's intended semantics.

Otherwise retrieval can drift away from the actual inquiry.

---

# 20.38 Query Interpretation as Inference

Some query interpretation operations are themselves inferential.

For example:

> “What caused the increase?”

may be interpreted as a causal inquiry.

But:

> “Why did sales increase?”

could also mean:

* descriptive explanation,
* causal explanation,
* business attribution,
* temporal comparison.

Therefore query interpretation may need uncertainty:

$$
Interpret(Q)
=
\{I_1,I_2,\ldots,I_n\}.
$$

The system should preserve ambiguity when it cannot safely resolve it.

---

# 20.39 Ambiguous Query

Let:

$$
I(Q)=\{I_1,I_2\}
$$

be two plausible interpretations.

The system should not silently select:

$$
I_1
$$

as though:

$$
I_1=True.
$$

Instead it may:

* ask for clarification,
* return results for both,
* explicitly state the interpretation used.

This follows the broader principle:

$$
Unknown\neq False
$$

and:

$$
Ambiguous\neq Resolved.
$$

---

# 20.40 Retrieval Contracts

Define a Retrieval Contract:

$$
RC=
\langle
Question,
Scope,
Sources,
TimeWindow,
RetrievalMethods,
RankingMethod,
Filters,
MinimumRecall,
MinimumPrecision,
Freshness,
ProvenanceRequirements
\rangle.
$$

The contract defines what constitutes adequate retrieval.

This is analogous to the statistical and causal contracts developed earlier.

---

# 20.41 Retrieval Knowledge Gap

Define:

$$
\Delta_R
=
\{r\in Req(RC):\neg Sat(K,r)\}.
$$

Possible gaps include:

* source coverage unknown,
* temporal coverage incomplete,
* provenance missing,
* relevant documents inaccessible,
* ranking model unvalidated,
* retrieval recall unknown,
* contradictory sources unresolved.

Thus:

$$
RetrievalCompleteness
$$

is itself contract-relative.

---

# 20.42 Retrieval Zero

Define:

$$
Zero_R(K,RC)
\iff
\Delta_R=\varnothing.
$$

This means:

> The retrieval process satisfies the explicit retrieval contract.

It does not mean:

> All relevant information in reality has been found.

Nor:

> The retrieved information is true.

Nor:

> The final answer is correct.

Therefore:

$$
Zero_R
\neq
Truth.
$$

---

# 20.43 Completeness of Retrieval

Suppose:

$$
Rel(Q)
$$

is the set of all relevant objects.

A retrieval process is complete for \(Q\) if:

$$
Rel(Q)\subseteq Ret(Q).
$$

In practice, \(Rel(Q)\) is often unknown.

Therefore empirical recall is estimated against a reference set:

$$
Rel^*(Q).
$$

This distinction must be explicit.

---

# 20.44 Open-World Retrieval

KnowledgeOS should generally assume an open-world condition unless the contract states otherwise.

That means:

$$
x\notin Ret(Q)
$$

does not imply:

$$
x\notin Reality.
$$

Nor:

$$
x\notin Knowledge.
$$

It may simply mean:

* retrieval failure,
* inaccessible source,
* indexing failure,
* ranking exclusion,
* insufficient query formulation.

Thus:

$$
NotRetrieved
\neq
Nonexistent.
$$

---

# 20.45 Closed-World Queries

Some bounded contexts may intentionally define a closed universe.

For example:

> “Within this approved dataset, return all records satisfying condition X.”

Then:

$$
Universe=U_{contract}
$$

and absence may have a stronger interpretation.

But the closed-world assumption must be explicit.

Therefore:

$$
ClosedWorld
$$

is a contract property, not a universal KnowledgeOS assumption.

---

# 20.46 Negative Retrieval Results

Suppose a search returns:

$$
R=\varnothing.
$$

This means:

$$
NoResult
$$

under the retrieval configuration.

It does not automatically mean:

$$
False(Q).
$$

Nor:

$$
Unknown(Q)
$$

without further semantics.

The system should preserve:

$$
NoRetrievalResult
$$

as a distinct state.

---

# 20.47 Retrieval Failure

Retrieval may fail because:

* source unavailable,
* index unavailable,
* query malformed,
* permissions insufficient,
* embedding service unavailable,
* timeout,
* ranking failure.

A failed retrieval should not be represented as:

$$
NoEvidenceExists.
$$

Instead:

$$
RetrievalFailure
$$

is an operational state.

This distinction is critical.

---

# 20.48 Retrieval Confidence

A retrieval system may estimate confidence in relevance.

For example:

$$
P(Rel|Q,x).
$$

But this is a model output.

It does not mean:

$$
P(Truth(x)|Q).
$$

The two quantities are different.

Therefore:

$$
RetrievalProbability
\neq
TruthProbability.
$$

---

# 20.49 Answer Generation

Let:

$$
G(Q,E,M)
$$

generate an answer from question \(Q\), evidence \(E\), and model \(M\).

Then:

$$
A=G(Q,E,M).
$$

The answer is a model-generated representation.

It may contain:

* direct quotations,
* paraphrases,
* deductions,
* interpolations,
* assumptions,
* hallucinations.

Therefore answer generation must preserve provenance at the claim level where required.

---

# 20.50 Claim-Level Provenance

Instead of attaching one source list to an entire answer, KnowledgeOS should ideally represent:

$$
Claim_i
\rightarrow
EvidenceSet_i
\rightarrow
SourceSet_i.
$$

This permits:

* claim-level verification,
* contradiction detection,
* unsupported-claim detection,
* provenance tracing.

Thus:

$$
Answer
=
\{Claim_1,\ldots,Claim_n\}
$$

rather than an indivisible textual object.

---

# 20.51 Unsupported Claim Detection

Let:

$$
Claims(A)=\{c_1,\ldots,c_n\}.
$$

Define:

$$
Supported(c_i,E,\Gamma).
$$

Then unsupported claims are:

$$
U(A,E,\Gamma)
=
\{c_i:\neg Supported(c_i,E,\Gamma)\}.
$$

A well-governed answer process should expose:

$$
U\neq\varnothing
$$

rather than silently presenting unsupported claims as established.

---

# 20.52 Inference After Retrieval

Once evidence has been retrieved and evaluated, inference may begin:

$$
E
\xrightarrow{I}
C.
$$

Inference requires:

* premises,
* rules,
* models,
* assumptions,
* scope,
* uncertainty.

Thus retrieval is upstream of inference but does not replace it.

The complete architecture is:

$$
Query
\rightarrow
Retrieval
\rightarrow
EvidenceEvaluation
\rightarrow
Inference
\rightarrow
Determination.
$$

---

# 20.53 Retrieval-Inference Boundary

The architectural boundary can be formalized:

$$
Ret(K,Q)
\rightarrow
E
$$

followed by:

$$
Infer(E,Q,\Gamma)
\rightarrow
C.
$$

The first operation selects information.

The second transforms premises into conclusions.

Therefore:

$$
Ret\neq Infer.
$$

This distinction should remain visible in the domain model and APIs.

---

# 20.54 AI as Retriever and Generator

An AI system may participate in:

$$
Retrieve
$$

and:

$$
Generate.
$$

Neither role automatically gives it epistemic authority.

AI-generated retrieval queries are:

$$
CandidateQuery.
$$

AI-generated evidence classifications are:

$$
CandidateEvaluation.
$$

AI-generated conclusions are:

$$
CandidateInference.
$$

Their epistemic status depends on validation.

---

# 20.55 Hallucination as Semantic Inflation

Hallucination can be characterized more precisely than:

> “The model made something up.”

A KnowledgeOS-relevant definition is:

> **Semantic inflation occurs when a generated representation is assigned a stronger epistemic category than its supporting evidence and contract justify.**

For example:

$$
Association
\rightarrow
Causation
$$

or:

$$
Prediction
\rightarrow
Observation
$$

or:

$$
Hypothesis
\rightarrow
Determination.
$$

Thus hallucination is often a failure of semantic control.

---

# 20.56 Retrieval Hallucination

Retrieval systems can also hallucinate.

Examples include:

* nonexistent documents,
* incorrect source metadata,
* wrong chunk boundaries,
* incorrect source associations,
* fabricated citations.

Therefore:

$$
RetrieverOutput
$$

requires provenance verification.

---

# 20.57 Citation Integrity

For citation \(c\) referencing source \(s\), KnowledgeOS may require:

$$
Exists(s)
$$

$$
Accessible(s)
$$

$$
IdentityCorrect(c,s)
$$

$$
Supports(s,p)
$$

$$
TemporalFit(s,Q)
$$

$$
ScopeFit(s,Q).
$$

Only then should citation confidence increase.

---

# 20.58 Retrieval and Contradiction

Suppose retrieval returns:

$$
E_1\models p
$$

and:

$$
E_2\models \neg p.
$$

The retrieval layer should preserve both.

It should not automatically resolve:

$$
p
$$

versus:

$$
\neg p.
$$

Conflict evaluation belongs downstream.

Thus:

$$
Retrieval
\rightarrow
ConflictPreservation
\rightarrow
Evaluation.
$$

---

# 20.59 Retrieval and Temporal Validity

An old source may be highly relevant but temporally invalid.

Therefore retrieval should distinguish:

$$
Relevant
$$

from:

$$
CurrentlyValid.
$$

A historical source can be valuable evidence for historical questions.

Thus:

$$
TemporalRelevance(Q)
$$

must be part of the retrieval contract when applicable.

---

# 20.60 Freshness

Freshness can be defined relative to a temporal contract.

For example:

$$
Fresh(x,t,\tau)
\iff
t-t_{source}(x)\leq\tau.
$$

But freshness does not imply accuracy.

Thus:

$$
Fresh
\neq
True.
$$

A recent incorrect source remains incorrect.

---

# 20.61 Authority Filtering

Retrieval may filter sources by authority.

For example:

$$
Authority(s)\geq a_0.
$$

But authority does not automatically establish truth.

Authority may increase admissibility under a contract.

Thus:

$$
Authority
\neq
Truth.
$$

This preserves the distinction between governance and epistemology.

---

# 20.62 Retrieval and Source Diversity

A retrieval contract may require diversity:

$$
Diversity(E)\geq d_0.
$$

This can reduce dependence on a single source family.

But diversity does not prove independence.

Therefore:

$$
DiverseSources
\neq
IndependentEvidence.
$$

Provenance analysis remains necessary.

---

# 20.63 Retrieval as an Epistemic Instrument

Retrieval is not itself knowledge.

It is an epistemic instrument.

An instrument can be evaluated for:

* sensitivity,
* specificity,
* recall,
* precision,
* calibration,
* robustness,
* bias,
* coverage.

This parallels measurement theory.

Thus:

$$
RetrievalSystem
$$

can be treated as a measurement process over an information universe.

---

# 20.64 Retrieval Measurement Error

Let:

$$
R^*(Q)
$$

be the ideal relevant set and:

$$
R(Q)
$$

the retrieved set.

Then:

$$
FalsePositive
=
R(Q)\setminus R^*(Q)
$$

and:

$$
FalseNegative
=
R^*(Q)\setminus R(Q).
$$

These are retrieval errors.

They are not automatically epistemic falsehoods in the retrieved objects themselves.

---

# 20.65 Retrieval Calibration

If a retrieval model outputs:

$$
\hat p=P(Rel|x,Q),
$$

calibration asks whether:

$$
P(Rel| \hat p\approx c)\approx c.
$$

This is a statistical property of the retrieval model.

It should not be interpreted as:

$$
P(Truth| \hat p=c).
$$

Again:

$$
RelevanceProbability
\neq
TruthProbability.
$$

---

# 20.66 Query Expansion and Semantic Risk

Suppose:

$$
Q\rightarrow Q'
$$

adds a term based on model inference.

If:

$$
Q'\not\equiv_{sem}Q,
$$

the retrieval system may answer a different question.

Therefore query transformations should retain:

$$
OriginalQuery
$$

and:

$$
TransformedQuery
$$

with provenance.

---

# 20.67 Retrieval Pipeline Object

A complete retrieval operation can be modeled as:

$$
RO=
\langle
Question,
Query,
QueryTransformation,
Retriever,
RetrieverVersion,
Corpus,
Index,
Filters,
Ranking,
Results,
Selection,
Timestamp,
Context,
Contract,
Provenance
\rangle.
$$

This makes retrieval auditable.

---

# 20.68 Evidence Retrieval Object

An evidence retrieval result can then be represented as:

$$
ERO=
\langle
EvidenceCandidate,
RetrievalOperation,
Relevance,
Source,
TemporalValidity,
Authority,
Dependency,
SelectionStatus,
Provenance
\rangle.
$$

This separates candidate retrieval from evidence acceptance.

---

# 20.69 Retrieval-to-Evidence Promotion

The promotion:

$$
RetrievedObject
\rightarrow
Evidence
$$

must be governed by an evidence contract.

A possible condition is:

$$
Promote(r,EC_E)
$$

only if:

$$
SourceValid
\land
ScopeValid
\land
TemporalValid
\land
ProvenanceValid
\land
EvidenceRequirementsSatisfied.
$$

This is a semantic transition.

It must not be hidden inside the search engine.

---

# 20.70 Retrieval-to-Inference Promotion

Likewise:

$$
Evidence
\rightarrow
InferencePremise
$$

requires:

* relevance,
* interpretation,
* proposition extraction,
* scope compatibility,
* assumptions.

Thus the pipeline is not:

$$
Search\rightarrowAnswer.
$$

It is:

$$
\boxed{
Search
\rightarrow
Candidate
\rightarrow
Evidence
\rightarrow
Premise
\rightarrow
Inference
\rightarrow
Conclusion
}
$$

with each arrow carrying its own contract.

---

# 20.71 Retrieval and the Knowledge Gap

Retrieval may reduce a gap:

$$
\Delta_{t+1}\subseteq\Delta_t
$$

when retrieved information satisfies previously unsatisfied requirements.

But retrieval can also expose missing information:

$$
\Delta_{t+1}\supset\Delta_t.
$$

For example, searching for evidence may reveal:

> No sufficiently authoritative source exists.

The visible gap increases.

This is not necessarily failure.

It can represent improved epistemic awareness.

---

# 20.72 Retrieval Can Increase Knowledge Quality While Increasing the Gap

Suppose initially:

$$
\Delta_0=\varnothing
$$

because requirements were not known.

After rigorous retrieval:

$$
\Delta_1=\{r_1,r_2,r_3\}.
$$

The gap increased.

But the system may now possess more accurate knowledge about what is missing.

Therefore:

$$
GapIncrease
\not\Rightarrow
KnowledgeQualityDecrease.
$$

This follows directly from the learning theory of Part XVII.

---

# 20.73 Retrieval Completeness Is Contextual

A retrieval operation may be complete for:

$$
Q_1
$$

but incomplete for:

$$
Q_2.
$$

Thus:

$$
Complete(R,Q_1,\Gamma_1)
$$

does not imply:

$$
Complete(R,Q_2,\Gamma_2).
$$

There is no universal notion of:

> “The knowledge base has been completely searched.”

Completeness requires a target, scope and contract.

---

# 20.74 Retrieval Architecture

A candidate architecture is:

$$
QueryContext
$$

↓

$$
QueryInterpreter
$$

↓

$$
CandidateRetrievers
$$

↓

$$
Ranker
$$

↓

$$
EvidenceSelector
$$

↓

$$
EvidenceContext
$$

↓

$$
InferenceContext
$$

↓

$$
DeterminationContext.
$$

These contexts are candidates, not necessarily separate deployable services.

---

# 20.75 Anti-Corruption Layer for Retrieval

External search engines may return their own semantics.

For example:

$$
ExternalSearchResult
=
\langle
url,
score,
snippet
\rangle.
$$

KnowledgeOS must not automatically interpret:

$$
score
$$

as evidence quality.

The ACL maps:

$$
ExternalSearchResult
\rightarrow
KnowledgeOS RetrievalResult.
$$

The mapping must explicitly define the meaning of each field.

---

# 20.76 Search Index as a Projection

A search index is usually:

$$
I=f(K).
$$

It is therefore a projection.

The index may omit:

* history,
* conflicts,
* provenance,
* temporal semantics,
* authorization details.

That may be acceptable for retrieval.

But:

$$
Index\neq CanonicalKnowledgeState.
$$

---

# 20.77 Index Rebuilding

If:

$$
I=f(K),
$$

then the index can ideally be rebuilt from canonical state:

$$
Rebuild(K)\rightarrow I'.
$$

A rebuilt index should satisfy:

$$
Sem(I')\equiv_Q Sem(I)
$$

under the retrieval contract, subject to model/version changes.

If this cannot be guaranteed, index state itself may need historical preservation.

---

# 20.78 Search Index Drift

Index drift occurs when:

$$
I_t\not\approx f(K_t).
$$

Causes include:

* failed updates,
* stale embeddings,
* deleted documents remaining indexed,
* missing newly ingested documents,
* schema mismatch.

Therefore index health is a semantic fitness property.

---

# 20.79 Retrieval Reproducibility

A retrieval operation is reproducible when the relevant inputs and versions allow:

$$
Replay(RO)
\approx
Results_{original}.
$$

This may require:

* corpus snapshot,
* index version,
* embedding model version,
* ranking model version,
* query transformation version,
* filters,
* permissions,
* time.

Without these:

$$
SameQuery
\not\Rightarrow
SameRetrieval.
$$

---

# 20.80 Retrieval Reproducibility Versus Answer Reproducibility

Even if retrieval is reproducible:

$$
R_1=R_2,
$$

generation may still differ because of:

* model version,
* stochastic decoding,
* system prompt,
* temperature,
* context ordering.

Thus:

$$
RetrievalReproducibility
\neq
AnswerReproducibility.
$$

Both must be specified separately.

---

# 20.81 Deterministic and Stochastic Retrieval

Some retrieval is deterministic:

$$
R(Q)=R.
$$

Other retrieval uses stochastic components:

$$
R(Q,\omega).
$$

Then repeated executions may produce:

$$
R_1\neq R_2.
$$

The system should preserve random seeds or stochastic configuration when reproducibility is required.

---

# 20.82 Retrieval and Decision Risk

A retrieval error can propagate downstream:

$$
RetrievalError
\rightarrow
EvidenceError
\rightarrow
InferenceError
\rightarrow
RiskError
\rightarrow
DecisionError.
$$

This is not automatically a causal claim.

It is an architectural dependency chain.

The system should therefore track:

$$
Impact(RetrievalFailure).
$$

---

# 20.83 Retrieval Dependency Graph

Let:

$$
G_D
$$

be a dependency graph.

If:

$$
Decision D
$$

depends on:

$$
Evidence E
$$

which depends on:

$$
Retrieval R,
$$

then:

$$
D\rightarrow E\rightarrow R
$$

defines an impact path.

If \(R\) is later invalidated, dependent conclusions may require re-evaluation.

But propagation must follow explicit dependency semantics.

---

# 20.84 Retrieval Invalidation

An index may contain a source later retracted.

Then:

$$
SourceStatus:
Valid\rightarrow Retracted.
$$

The system should identify:

$$
AffectedRetrievals
$$

and potentially:

$$
AffectedEvidence
$$

and:

$$
AffectedDeterminations.
$$

This is a direct application of the revision theory.

---

# 20.85 Retrieval Is Not a One-Time Operation

Because knowledge evolves:

$$
K_t\rightarrow K_{t+1},
$$

retrieval results evolve:

$$
R_t(Q)\rightarrow R_{t+1}(Q).
$$

Therefore a previously valid answer may require reevaluation.

This creates a monitoring possibility:

$$
ChangedRetrieval(Q)
\rightarrow
Review.
$$

---

# 20.86 Retrieval as Part of the Knowledge Lifecycle

The full cycle becomes:

$$
Observation
\rightarrow
Persistence
\rightarrow
Retrieval
\rightarrow
EvidenceEvaluation
\rightarrow
Inference
\rightarrow
Determination
\rightarrow
Decision
\rightarrow
Action
\rightarrow
Outcome
\rightarrow
Observation.
$$

Retrieval is therefore not an auxiliary UI feature.

It is part of the epistemic infrastructure.

---

# 20.87 DDD Concepts for Retrieval

Candidate domain concepts include:

* Inquiry,
* Query,
* QueryInterpretation,
* QueryTransformation,
* RetrievalContract,
* RetrievalOperation,
* RetrievalResult,
* RetrievalRank,
* RetrievalScore,
* SearchCorpus,
* Index,
* IndexVersion,
* RankingModel,
* EmbeddingModel,
* EvidenceCandidate,
* EvidenceSelection,
* Citation,
* CitationValidation,
* SourceCoverage,
* RetrievalEvaluation,
* RetrievalGap,
* RetrievalFailure.

These are candidate concepts.

They should be validated through domain discovery.

---

# 20.88 Candidate Retrieval Bounded Context

A candidate:

$$
BC_{Retrieval}
$$

could own:

* query interpretation,
* retrieval strategies,
* ranking,
* index management,
* retrieval provenance,
* retrieval evaluation.

It should not automatically own:

* truth,
* final epistemic determination,
* causal claims,
* decision authority.

This protects the semantic boundary.

---

# 20.89 Retrieval Context and Evidence Context

A useful boundary is:

$$
RetrievalContext
\rightarrow
EvidenceContext.
$$

The retrieval context answers:

> What information should be considered?

The evidence context answers:

> Which retrieved objects qualify as evidence under the applicable contract?

These are different questions.

---

# 20.90 Evidence Context and Inference Context

Then:

$$
EvidenceContext
\rightarrow
InferenceContext.
$$

The evidence context establishes the admissible evidence.

The inference context determines what can be concluded from that evidence.

Therefore:

$$
EvidenceSelection
\neq
Inference.
$$

---

# 20.91 Query as Domain Object

An inquiry may have a lifecycle:

$$
Created
\rightarrow
Interpreted
\rightarrow
Retrieved
\rightarrow
Evaluated
\rightarrow
Answered
\rightarrow
Reviewed.
$$

A query can therefore itself become a domain object where traceability is important.

This is especially useful for:

* regulated decisions,
* scientific research,
* enterprise knowledge,
* audit workflows.

---

# 20.92 Query Identity

Two identical text queries may have different semantic identities.

For example:

```text
“What is the current status?”
```

asked:

* by different participants,
* at different times,
* under different contracts,
* for different purposes,

may represent different inquiries.

Therefore:

$$
TextEquality
\neq
InquiryIdentity.
$$

---

# 20.93 Query Equivalence

Define:

$$
Q_1\equiv_{\Gamma}Q_2
$$

if they are semantically equivalent under contract \(\Gamma\).

Textual difference does not imply semantic difference:

$$
Text(Q_1)\neq Text(Q_2)
$$

while:

$$
Q_1\equiv_{\Gamma}Q_2.
$$

Conversely:

$$
Text(Q_1)=Text(Q_2)
$$

does not imply:

$$
Q_1\equiv_{\Gamma}Q_2.
$$

---

# 20.94 Retrieval Equivalence

Two retrieval systems may produce different physical result sets while being equivalent for a contract.

If:

$$
R_1\neq R_2
$$

but:

$$
Relevant_{EC}(R_1)
=
Relevant_{EC}(R_2),
$$

then:

$$
R_1\equiv_{EC}R_2.
$$

This is important for technology migration.

A new search engine need not reproduce every byte of the old result.

It must preserve required semantics.

---

# 20.95 Retrieval Migration

Suppose:

$$
Retriever_1
\rightarrow
Retriever_2.
$$

A migration should evaluate:

* recall,
* precision,
* ranking stability,
* source coverage,
* temporal behavior,
* semantic equivalence,
* citation validity,
* downstream answer quality.

Thus:

$$
RetrieverMigration
$$

is an empirical and semantic verification problem.

---

# 20.96 Retrieval Model Governance

Retrieval models should be versioned.

For a retrieval result:

$$
RR=
\langle
Query,
Result,
ModelVersion,
IndexVersion,
Time,
Provenance
\rangle.
$$

This permits:

> Why was this document ranked first?

to become an answerable question.

---

# 20.97 The Retrieval Evidence Principle

### Principle

A retrieval result may become evidence only through an explicit evidence qualification process.

Formally:

$$
Retrieved(x)
\not\Rightarrow
Evidence(x).
$$

Instead:

$$
Retrieved(x)
\land
Qualifies(x,EC_E)
\Rightarrow
Evidence(x).
$$

This is a fundamental architectural boundary.

---

# 20.98 The Retrieval Inference Theorem

### Theorem 20.1

Retrieval alone cannot establish a conclusion unless the retrieval contract itself contains a valid inference rule that maps the retrieval condition to that conclusion.

Formally:

$$
Ret(K,Q)=R
$$

does not imply:

$$
K\models Q
$$

unless an explicit valid rule establishes the implication.

### Proof

Retrieval identifies objects satisfying a retrieval criterion.

Truth or determination requires a semantic relation between those objects and the proposition.

Since relevance and truth are distinct predicates:

$$
Relevant(x,Q)\not\Rightarrow Truth(Q),
$$

the implication is invalid without an additional rule.

$$
\Box
$$

---

# 20.99 The Graph Path Theorem

### Theorem 20.2

A path in a typed knowledge graph does not constitute an inference unless the composition of its relation types is licensed by the relevant relation algebra.

Formally:

$$
Path_G(x,y)
\not\Rightarrow
x\vdash y
$$

unless:

$$
Composition_\Gamma(Path_G)
$$

is valid.

This prevents accidental inference through graph traversal.

---

# 20.100 The RAG Separation Theorem

### Theorem 20.3

A generated answer from retrieved information is not automatically a determination.

Let:

$$
A=G(Q,R).
$$

Then:

$$
A\neq Determination(Q)
$$

unless an independent epistemic contract validates the generated claims.

Therefore:

$$
RAG
\not\Rightarrow
Determination.
$$

---

# 20.101 Retrieval Completeness Theorem

### Theorem 20.4

Retrieval completeness is always relative to a specified universe, inquiry and retrieval contract.

There is no universal predicate:

$$
Complete(R)
$$

independent of:

$$
Q,\Gamma,U.
$$

Instead:

$$
Complete(R,Q,U,\Gamma).
$$

Thus a claim such as:

> “The system searched all relevant knowledge”

is incomplete unless the relevant universe and completeness contract are defined.

---

# 20.102 Retrieval–Evidence–Inference Separation

The complete separation is:

$$
\boxed{
Retrieval
\rightarrow
EvidenceQualification
\rightarrow
Inference
\rightarrow
Determination
}
$$

where:

### Retrieval

Selects candidates.

### Evidence qualification

Determines whether candidates satisfy evidence requirements.

### Inference

Transforms admissible premises under rules/models.

### Determination

Checks whether the epistemic contract's requirements are satisfied.

No stage may silently impersonate another.

---

# 20.103 Retrieval Constitutional Statements

### XX-C1 — Query Semantics

A computational query is not automatically identical to the semantic inquiry.

### XX-C2 — Retrieval Separation

Retrieval is distinct from evidence.

### XX-C3 — Relevance Separation

Relevance is distinct from truth.

### XX-C4 — Ranking Separation

Ranking scores are not epistemic truth scores.

### XX-C5 — Similarity Separation

Embedding similarity is not semantic identity.

### XX-C6 — Graph Separation

Graph traversal is not automatically inference.

### XX-C7 — Path Separation

A graph path does not automatically justify a conclusion.

### XX-C8 — Retrieval Provenance

Retrieval operations must preserve required retrieval provenance.

### XX-C9 — Temporal Retrieval

Retrieval is time-dependent where the corpus or model changes over time.

### XX-C10 — Snapshot Semantics

Historical retrieval requires an explicit historical knowledge/index state.

### XX-C11 — Open-World Default

Absence from retrieval does not imply nonexistence unless a closed-world contract applies.

### XX-C12 — Retrieval Failure

Retrieval failure must not be represented as absence of evidence.

### XX-C13 — Evidence Promotion

Retrieved objects become evidence only under an explicit evidence contract.

### XX-C14 — Independence

Distinct retrieved sources do not automatically constitute independent evidence.

### XX-C15 — RAG Separation

Retrieval-augmented generation does not automatically establish truth or determination.

### XX-C16 — Citation Integrity

Citation presence does not establish citation validity.

### XX-C17 — Claim Provenance

Generated claims should retain source and inference provenance where required.

### XX-C18 — Model Versioning

Retrieval models, indexes and embeddings must be versioned where reproducibility requires it.

### XX-C19 — Retrieval Completeness

Retrieval completeness is contract-, inquiry- and universe-relative.

### XX-C20 — No Semantic Inflation

Retrieval, generation and similarity must never be silently promoted to stronger epistemic categories.

---

# 20.104 Final Principle of Part XX

The fundamental retrieval principle is:

$$
\boxed{
Find\ first;
qualify\ second;
infer\ third;
determine\ only\ when\ the\ contract\ permits.
}
$$

The complete architecture is:

$$
\boxed{
Inquiry
\rightarrow
Query
\rightarrow
Retrieval
\rightarrow
Candidate
\rightarrow
Evidence
\rightarrow
Premise
\rightarrow
Inference
\rightarrow
Conclusion
\rightarrow
Determination
}
$$

with provenance and contracts surrounding every transition.

For AI systems, this becomes:

$$
\boxed{
RAG
=
Retrieval
+
ContextConstruction
+
Generation
}
$$

but:

$$
\boxed{
RAG
\neq
Evidence
\neq
Inference
\neq
Truth
\neq
Determination.
}
$$

The deeper KnowledgeOS principle is therefore:

$$
\boxed{
Information\ becomes\ evidence\ only\ through\ qualification;
evidence\ becomes\ a\ premise\ only\ through\ interpretation;
premises\ become\ conclusions\ only\ through\ valid\ inference;
and\ conclusions\ become\ determinations\ only\ through\ an\ explicit\ contract.
}
$$

This establishes the boundary between **knowledge retrieval** and **knowledge reasoning**.

The next theoretical layer is therefore the mechanism that connects retrieved and qualified evidence to formal reasoning:

$$
\boxed{
Part\ XXI:
KnowledgeOS\ Reasoning\ Engine,\ Rule\ Execution,\ Constraint\ Solving,\ Proof\ Objects,\ Derivation\ Trees,\ and\ Formal\ Verification
}
$$

The central question will be:

> **When KnowledgeOS produces a conclusion, what exactly constitutes a valid derivation, how is that derivation represented, and how can the system prove that it did not silently cross an invalid semantic boundary?**

Part XX establishes the retrieval boundary. The most important architectural consequence is that **RAG should terminate in candidate evidence/context, not directly in “knowledge.”** Part XXI can now formalize the reasoning engine itself—especially proof objects, derivation provenance, strict vs defeasible rules, constraint solving, and verification of AI-generated reasoning.
