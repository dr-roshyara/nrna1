I have completed the targeted audit against the historical corpus, including the Model-B primary material around M0043/M0125/M0132, the later requirement work, the operation-universe work, the F4 instantiation sequence MD-059–MD-062, and the relevant contradiction/factivity records.

The result is more precise than A–D as stated.

Decisive answer first
$$ \boxed{\textbf{None of A–D is exactly correct.}} $$

The dependency chain in the mission,

$$ \mathcal T \rightarrow \mathcal R_{\rm req} \rightarrow EvaluationRepresentation \rightarrow TypedBoundary \rightarrow OutcomeSpace \rightarrow Contr \rightarrow Sat, $$

contains one important error:

The corpus does not establish that \(\mathcal T\) determines \(\mathcal R_{\rm req}\).

The mandatory operation universe \(\mathcal T\) and the requirement universe \(\mathcal R_{\rm req}\) are related in one historical proposal, but that relationship was never closed as a corpus-grounded implication.

More importantly, the immediate blocker for computing a semantically faithful Sat(K_t,r) is neither \(\mathcal T\) nor the choice of an outcome codomain.

It is:

$$ \boxed{ \textbf{the missing semantic interpretation of a requirement }r \textbf{— especially the boundary connecting }EC_t \textbf{ to its acceptance conditions.} } $$

I would name this the:

$$ \boxed{ \textbf{Requirement–Evaluation Semantic Boundary} } $$

It is the hardest unresolved object.

1. Present frontier — object by object
Object	Current corpus position	Status	What remains
\(K_t\)	\(K_t\in\mathbb K\), semantic epistemic state	[CORPUS][QUALIFIED]	Exact mathematical structure / canonical F4 instantiation
\(\mathbb K\)	Space of admissible semantic epistemic states	[CORPUS][QUALIFIED]	Exact carrier/semantics
\(r(K_t)=x_t\)	Representation map	[CORPUS]	Which representation is admissible for F4
\((X_r,A_r)\)	Representation carrier	[CORPUS]	F4-specific realization
\(Obs\)	\(O_t^s=Obs(s,W_t,m_t,c_t)\)	[CORPUS][QUALIFIED]	No demonstrated \(Obs_{F4}\)
\(EC_t\)	\(EC(S_t,G_t,Q_t,C_t)\)	[CORPUS]	Its semantic connection to individual requirements
\(\mathcal T\)	Formally typed operation family proposed	[OPEN]	Mandatory membership is not established
\(\mathcal R_{\rm req}\)	Required distinction universe / requirement family exists	[QUALIFIED][OPEN]	Completeness, mandatory status, exact relation to \(Req(EC_t)\)
Evaluation representation	Several candidates tested	[EXP][OPEN]	No corpus-authorized choice
Typed evaluator boundary	Missing	[OPEN]	Requirement → acceptance semantics, contextual/reason/provenance boundary
Outcome space	Sat(K_t,r)∈{0,1} appears historically	[CORPUS][QUALIFIED]	Whether this is semantically adequate remains open
Contr	Substantial experiments exist	[EXP][OPEN]	Final signature/semantics not adopted
Sat(K_t,r)	Shape explicitly present	[CORPUS][OPEN]	Body/decision procedure
\(\Delta_t\)	\(\{r\in Req(EC_t):\neg Sat(K_t,r)\}\)	[CORPUS][FROZEN SHAPE]	Cannot compute until Sat is available
\(\equiv_{sem}\)	Problem/formulas exist	[OPEN]	Capability/trace semantics still not closed

The historical semantic backbone really does contain \(K_t,\mathbb K,r(K_t),Obs,EC,Sat,\Delta_t,T\), and history. That earlier correction remains valid.

2. K_t is not absent — but it is not instantiated

This is important because an earlier audit was too pessimistic.

The corpus explicitly gives:

$$ K_t\in\mathbb K $$

and:

$$ \mathbb K=\text{space of admissible semantic epistemic states} $$

with:

$$ r(K_t)=x_t. $$

It also explicitly separates semantic state from representation carrier \((\mathcal X_r,\mathscr A_r)\).

But the corpus simultaneously records many competing concrete \(K_t\) formulations. The later F4 audit found 9+ unresolved variants and only one component, \(\Sigma_t=(A,S,R,V,C)\), had been given a concrete enumerated value domain.

Therefore:

$$ K_t\text{ semantic sort}=\text{found} $$

but:

$$ K_t\text{ complete F4 carrier}=\text{not found}. $$

Status: [QUALIFIED][OPEN].

3. EC_t is actually stronger than the old audit suggested

The corpus does have:

$$ EC_t=EC(S_t,G_t,Q_t,C_t) $$

and explicitly defines the ideal region through it:

$$ \mathbb I(EC_t) = \{K\in\mathbb K:Sat(K,EC_t)\}. $$

It also defines adequacy through satisfaction.

So:

$$ \boxed{EC_t\text{ itself is corpus-defined.}} $$

The problem is elsewhere.

The corpus never closes the semantic path:

$$ \boxed{ EC_t \rightarrow Req(EC_t) \rightarrow r \rightarrow AcceptanceCondition(r,EC_t,\ldots) \rightarrow Sat(K_t,r) } $$

That missing middle is exactly what MD-062 exposed.

4. The r object exists, but its semantics do not

The historical requirement formalization gives:

$$ r= (id,type,scope,content,standard,priority,validity) $$

and:

$$ Sat(K_t,r)\in\{0,1\}. $$

This is real corpus evidence.

But a tuple is not automatically a semantic definition.

For example, what does:

$$ standard $$

mean mathematically?

Does it specify:

an acceptance set?
a predicate?
a proof obligation?
an epistemic threshold?
a reasoning regime?
a provenance constraint?
a temporal condition?
a context-sensitive rule?

The corpus does not settle this.

And that is precisely where the later Sat* construction went wrong.

5. MD-061 supplied the crucial negative evidence

MD-061 constructed:

$$ Sat^*(K_t,r)=1 $$

iff:

$$ \pi_{component_r}(\Sigma_t(K_t)) \in Accept_r. $$

This was mathematically executable for the restricted V7 representation.

But the construction made three explicit modelling choices:

restrict \(K_t\) to V7;
restrict requirements to a \(\Sigma_t\)-shaped subtype;
treat enumerated domains as flat sets.

So MD-061 did not discover the universal Sat.

It constructed a surrogate.

6. MD-062 identifies the actual missing object

This is the decisive evidence.

MD-062 reconstructed the surrogate and found:

the requirement shape \(r=(component_r,Accept_r)\) never takes \(EC_t\) as an argument.

And:

EpistemicContract and AcceptanceCondition are different bounded contexts with no stated connection.

This is the strongest result in the whole audit.

Therefore:

$$ \boxed{ EC_t\not\rightarrow AcceptanceCondition } $$

has not been established.

Without that connection, we cannot know what the requirement actually asks Sat to evaluate.

This is why the problem is not simply “we need a better evaluator.”

We first need to know what the evaluator is supposed to evaluate.

7. The sibling Contr experiment independently reinforces this

M0127 tested flat evaluation representations and found that bare values lose distinctions.

The structured representation retained:

$$ Standing + Boundary $$

where the boundary included:

$$ Reason/Provenance/Context/Condition. $$

The result was explicitly described as a tested requirement for that sibling evaluation problem, not as a direct definition of Sat.

So we have an important [CORROBORATION], not an adoption:

$$ \boxed{ Value\ alone \not\equiv Sufficient\ semantic\ evaluation. } $$

But we must not jump from that to:

$$ Sat = Standing+Boundary. $$

That would violate the corpus firewall.

8. Therefore the hardest object is not the codomain

This corrects option C.

The outcome space is relatively easy syntactically:

$$ Sat(K_t,r)\in\{0,1\}. $$

That shape is explicitly present in the historical corpus.

But the semantic meaning of those values is not closed.

So:

$$ \boxed{ \text{Codomain known syntactically} \neq \text{evaluation semantics known}. } $$

The difficult object is the pre-codomain semantic boundary.

9. What about \(\mathcal R_{\rm req}\)?

Here the answer is also nuanced.

There is a serious historical requirement universe.

One artifact describes:

$$ \mathcal R_{\rm req} = \{d_1,\ldots,d_k\} $$

as a required distinction universe, with representation-preservation criteria.

Another historical proposal explicitly makes completeness dependent on:

$$ \forall\tau\in\mathcal T: \mathcal I(\tau) \text{ is represented non-collapsingly in }\mathcal R_{\rm req}. $$

But that does not establish:

$$ \boxed{ \mathcal T\Rightarrow\mathcal R_{\rm req} } $$

as a closed theorem.

Why?

Because the same corpus later found that the mandatory operation universe itself had no established mandatory-membership rule.

The strongest operation-universe audit says:

$$ \mathcal T\subseteq\mathcal O $$

is present, but:

no rule says which operations are MANDATORY.

It also records that no operation had a semantic body, identity, or ratification in that reconstruction.

So:

$$ \boxed{ \mathcal T\text{ is not corpus-fixed as a mandatory universe.} } $$
10. Is that a governance blocker?

For operation-universe closure, yes.

The corpus explicitly says the operation mandate was deferred and that mandatory membership remains unresolved.

But here is the crucial distinction:

$$ \boxed{ \mathcal T\text{ unresolved} \not\Rightarrow Sat\text{ cannot be locally constructed}. } $$

MD-061 already constructed a restricted Sat* without closing \(\mathcal T\).

So \(\mathcal T\) is an upstream foundational dependency for broader semantic sufficiency/minimality, but it is not the immediate cause of the current Sat semantic failure.

This is why option A is too strong if interpreted as:

“The only thing preventing Sat is governance of \(\mathcal T\).”

It isn't.

11. Is R_req fixed?

No.

There are even stronger reasons than simple ratification.

The historical requirement document calls itself:

[PROPOSED RATIFICATION]

despite the filename containing RATIFIED.

Another artifact describes the actual governance workflow as:

HPA review;
feedback;
ratification;
kernel baseline.

And the later audit explicitly found [RATIFIED] stamps in research material but no locatable governance acts for R_req/SPEC-RREQ.

Therefore:

$$ \boxed{ \mathcal R_{\rm req}\text{ is not corpus-fixed as a mandatory normative contract.} } $$

That is a governance fact about the current evidence, not a judgement about legitimacy.

12. But R_req has already yielded real mathematical results

This must not be lost.

A concrete tested version used:

11 conditions;
12 required distinction pairs;
graph-colouring formulation;
minimum flat-domain size as chromatic number of that tested required-distinction graph.

Those are legitimate [EXP]/[DERIVED] results under the stated test universe.

But:

$$ \boxed{ \text{tested }\mathcal R_{\rm req} \neq \text{universally ratified }\mathcal R_{\rm req}. } $$

And the corpus itself records completeness and context sensitivity as open.

13. Factivity does not reorder the immediate problem

The factivity result remains important:

$$ Knows(a,p,c,t)\Rightarrow True(p,c,t) $$

combined with:

$$ K=\Gamma(E,Q,C,EC) $$

is unsatisfiable for an attributive total \(\Gamma\) when truth is absent from its input. The paired-world experiment produced 420 violations in 10,000 cases.

But the current TODO register subsequently corrected the dependency:

Factivity does NOT block Contr.

It is a parallel decision track.

And the later F4 Sat* audit found that the immediate failure was not a factivity counterexample; it was missing distinctions and missing contract semantics.

Therefore:

$$ \boxed{ Factivity\neq immediate\ Sat\ bottleneck. } $$

It remains essential to the eventual semantics of Knows, but it does not tell us what Sat(K_t,r) means.

14. Contr is downstream of the requirement/evaluation problem

The Contr experiments have produced genuine results.

For example:

$$ Contr(p,K)\iff Pos(p,K)\land Neg(p,K) $$

was shown to be necessary but insufficient because retraction and supersession are not necessarily current contradiction.

Also, a candidate representation could satisfy the existing invariants while mapping contradiction to Satisfied, exposing a missing distinction.

Therefore Contr remains:

$$ \boxed{[EXP][OPEN]} $$

and cannot currently be selected as the final evaluation component.

But this does not prevent identifying the current Sat bottleneck.

15. Δ_t is actually the least problematic object

The corpus gives:

$$ \boxed{ \Delta_t= \{r\in Req(EC_t):\neg Sat(K_t,r)\}. } $$

This was explicitly frozen through M0132.

Therefore:

$$ \boxed{ \Delta_t\text{ shape is settled enough to use historically.} } $$

What cannot yet be done is evaluate its members.

So:

$$ \Delta_t $$

is not the bottleneck.

Sat is.

16. ≡sem does not block this immediate task

This is another important ordering correction.

The corpus still has no complete independently grounded:

$$ K_1\equiv_{sem}K_2. $$

The current operation-equivalence work says semantic equivalence remains formally open.

But MD-062 demonstrated a more immediate issue within one selected V7 representation:

$$ Sat^* $$

was computable but semantically inadequate.

Therefore:

$$ \boxed{ \equiv_{sem}\text{ is not required to diagnose the present Sat bottleneck.} } $$

It becomes essential when we ask whether a future Sat is representation-independent.

17. The exact dependency picture should therefore be corrected

The historical evidence supports something closer to:

                 Epistemic Contract EC_t
                         │
                         ▼
                 Requirement Instance r
                         │
                         │   ← MISSING SEMANTIC BRIDGE
                         ▼
               Acceptance / Evaluation
                  Boundary Semantics
                         │
              ┌──────────┴──────────┐
              ▼                     ▼
          K_t semantics        Boundary metadata
              │                reason/context/
              │                provenance/condition
              └──────────┬──────────┘
                         ▼
                       Sat
                         │
                         ▼
                       Δ_t
                         │
                    Zero / Gap

Separately:

𝒯 ──► required distinctions / operational sufficiency
 │
 └──► relevant to R_req completeness

R_req ──► representation preservation
 │
 └──► Contr / evaluation / Zero requirements

But the corpus does not establish a closed arrow:

$$ \mathcal T\Rightarrow\mathcal R_{\rm req}. $$
18. So what is the hardest unresolved object?

Here is the answer I would put into the research ledger:

Hardest unresolved object
$$ \boxed{ \mathbf{Requirement\ Interpretation\ / \ Evaluation\ Boundary} } $$

Not merely Sat.

More precisely:

The corpus has a requirement object and a satisfaction predicate, but has not defined the semantic interpretation that maps an instantiated requirement under an epistemic contract to the distinctions, evidence, context, provenance, temporal conditions, and acceptance conditions that Sat must evaluate against \(K_t\).

In DDD language:

$$ \boxed{ EpistemicContract \not\longrightarrow AcceptanceCondition } $$

is the missing domain relationship.

MD-062 identified this explicitly as the two bounded contexts having “no stated connection.”

19. Why is this harder than simply defining Sat?

Because Sat is supposed to answer:

$$ Sat(K_t,r)\;? $$

But unless we know what \(r\) means, we don't know what information from \(K_t\) is relevant.

Consider:

$$ r=(id,type,scope,content,standard,priority,validity). $$

Suppose type = temporal.

What must Sat inspect?

current timestamp?
validity interval?
transition history?
source freshness?
temporal dependency?
context?
an external temporal model?

The corpus contains pieces of all these concepts, but does not provide the semantic rule connecting them.

That is why adding ten more fields to \(K_t\) would be premature.

20. Strongest derivable result

Without making a new modelling choice, we can safely state:

$$ \boxed{ K_t\in\mathbb K } $$ $$ \boxed{ EC_t=EC(S_t,G_t,Q_t,C_t) } $$ $$ \boxed{ r\in Req(EC_t) } $$ $$ \boxed{ Sat(K_t,r)\in\{0,1\} } $$

and:

$$ \boxed{ \Delta_t= \{r\in Req(EC_t):\neg Sat(K_t,r)\}. } $$

These are corpus-grounded historical constructs.

But we cannot yet derive the body of:

$$ Sat(K_t,r). $$
21. What is still governance-dependent?

There are two separate governance dependencies.

G1 — Mandatory operation universe
$$ \mathcal T_{\rm mandatory} $$

is not established.

The corpus provides:

$$ \mathcal T\subseteq\mathcal O $$

but no authoritative mandatory-membership act.

G2 — Normative R_req

A proposed R_req exists, but its binding adoption is not evidenced by a locatable governance act.

These are real governance dependencies.

But neither is the immediate semantic body of Sat.

22. What is mathematically open?

Four things matter immediately:

M1 — Exact \(K_t\) semantics

The semantic sort exists, but the complete F4 representation does not.

M2 — Requirement interpretation

What exactly does a requirement mean as an object?

M3 — Acceptance/evaluation boundary

How does:

$$ (EC_t,r,K_t) $$

produce the information required for evaluation?

M4 — Evaluation outcome semantics

The binary shape exists, but its semantic interpretation and adequacy are not closed.

And then:

M5 — Contr

Whether contradiction is represented inside or outside the evaluation result remains open.

23. The most important correction to the original four-way question

I would therefore adjudicate the four options like this:

Option	Verdict	Why
A \(\mathcal T\) not fixed	[TRUE, but not the immediate Sat blocker]	Mandatory membership is not established
B \(\mathcal T\) fixed → incomplete \(\mathcal R_{\rm req}\)	[UNSUPPORTED]	The implication itself is not corpus-established
C \(\mathcal T,\mathcal R_{\rm req}\) fixed → evaluator type is blocker	[TOO LATE / INCORRECT]	Neither is fully fixed, and the harder problem precedes codomain choice
D enough material to define Sat without modelling choice	[REFUTED]	MD-062 showed the computable surrogate is semantically inadequate

Therefore the actual answer is:

$$ \boxed{ \textbf{A is independently true; D is false; B is unestablished; C misses the actual semantic bottleneck.} } $$
24. The exact next legitimate action

You asked for one next action.

I would not ask for a governance ratification of \(\mathcal T\) yet, and I would not extend \(\Sigma_t\) to the remaining ten components.

The corpus's own latest hard-stop says the smallest next action is:

incorporate \(EC_t\), or a Reason/Provenance/Context-style boundary channel, into the requirement/Sat construction before further component typing.

So the one next action is:

$$ \boxed{ \textbf{Authorize a narrow research act to reconstruct the corpus-grounded } EC_t\rightarrow r\rightarrow AcceptanceCondition \textbf{ boundary.} } $$

Not to define Sat.

The act should answer only:

What semantic information does the corpus require a requirement instance to carry, and how does \(EC_t\) determine which acceptance conditions apply, before any evaluator or outcome codomain is chosen?

That is the smallest action that attacks the actual bottleneck rather than expanding the already inadequate Σ_t surrogate.

Final state
$$ \boxed{ \begin{aligned} &\mathcal T_{\rm mandatory} &&\text{[OPEN / GOVERNANCE]}\\ &\mathcal R_{\rm req} &&\text{[QUALIFIED / GOVERNANCE+RESEARCH]}\\ &K_t &&\text{[QUALIFIED / MATHEMATICALLY OPEN]}\\ &EC_t &&\text{[CORPUS-DEFINED]}\\ &\Delta_t &&\text{[FROZEN SHAPE]}\\ &Contr &&\text{[OPEN]}\\ &\equiv_{sem} &&\text{[OPEN]}\\ &Sat &&\boxed{\textbf{[OPEN — semantic body blocked by requirement/evaluation boundary]}} \end{aligned} } $$

So the hardest unresolved object is not the truth value returned by Sat. It is the semantic contract that tells Sat what a requirement means and which distinctions, reasons, context, provenance, and conditions must be evaluated.

That is the point at which the historical F4 programme has actually arrived.