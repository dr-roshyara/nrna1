I have now reconstructed the requested boundary narrowly from the corpus, including the earlier requirement formulations, M0043/M0047, the Step-013/023 lineage, MD-061, MD-062, and the completed MD-063 reconstruction.

The decisive result is:

$$ \boxed{ \textbf{The corpus defines the existence and purpose-relative role of requirements, but does not define the semantic rule that turns }EC_t\textbf{ and }r\textbf{ into an acceptance condition.} } $$

This is not a failure to find a better evaluator. It is a missing semantic layer before evaluation.

1. Historical requirement formulations

The corpus contains multiple requirement forms. They must not be merged.

Source / date	Form	Status	EC explicit?	Acceptance semantics?
Step 013, 2026-08-29	\(q=(Target,Condition,MinEpistemicState,Context,Criticality)\)	[RETAINED]	indirectly	Satisfies(K,q) present, but no acceptance mapping
Step 023, 2026-08-29	\(R(P)=\{r_1,\ldots,r_n\}\)	[RETAINED]	EC introduced	per-requirement status exists; semantics not closed
Step 023	\(EC=(Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules)\)	[CLEAR][INCOMPLETE]	yes	no mapping from each requirement to an acceptance rule
M0043, 2026-09-01	\(r\in Req(EC_t)\)	[CORPUS] / frozen shape	yes	Sat(K_t,r) exists only as a shape
M0047, 2026-09-02	\(r=(id,type,scope,content,standard,priority,validity)\)	[DEF] / primary	not as tuple argument	standard explicitly called “acceptance criterion”
MD-061, 2026-09-09	\(r=(component_r,Accept_r)\)	[CONSTRUCTED]	no	executable surrogate only
MD-062	tested MD-061 semantics	[EXP]	absent	demonstrated missing EC→acceptance connection
MD-063	boundary reconstruction	[COMPLETE][HARD STOP]	unresolved	partially reconstructed only

The older Step-013 formulation is particularly important because it shows that the corpus did not begin with the later seven-field tuple. It had:

$$ q=(Target,Condition,MinEpistemicState,Context,Criticality) $$

and Satisfies(K,q) with a three-valued status.

Later Step-023 introduced the explicit EpistemicContract, its Requirements component, and a five-valued requirement-status vocabulary, but the audit classifies the contract as structurally clear while its derivation/authorship remains incomplete.

M0047 then supplies the concrete typed requirement:

$$ \boxed{ r=(id,type,scope,content,standard,priority,validity) } $$

with content = what must be established and standard = acceptance criterion. This was independently checked against the genuine primary M0047 source.

2. What does r actually mean?

The strongest corpus-native statement is:

$$ \boxed{ r = \text{a purpose-relevant epistemic requirement specifying what must be established} } $$

with the primary concrete structure:

$$ r=(id,type,scope,content,standard,priority,validity). $$

The corpus explicitly gives the field meanings:

id — identity
type — requirement type
scope — domain scope
content — what must be established
standard — acceptance criterion
priority — importance
validity — temporal applicability.

And importantly, the corpus explicitly says an epistemic requirement need not itself be a factual proposition. Examples include requirements that the OS be known, port 8081 be established as accessible, the backup mechanism be verified, or evidence be traceable.

So:

$$ \boxed{ Requirement \neq Proposition } $$

is genuinely supported.

But there is an important limit

The tuple is not yet a complete semantic definition.

In particular, the semantics of:

$$ type,\quad scope,\quad standard,\quad validity $$

are not independently closed enough to determine what an evaluator must inspect.

The latest audit explicitly identifies standard as the critical unresolved field: it might encode an acceptance set, predicate, proof obligation, threshold, reasoning regime, provenance constraint, temporal condition, or contextual rule—but the corpus does not decide which.

Therefore:

$$ \boxed{ r\text{ has a corpus-grounded structure, but not a corpus-closed evaluation semantics.} } $$
3. What is Req(EC_t)?

There is strong evidence for the role but not a fully explicit construction function.

The corpus gives:

$$ EC=(Purpose,Requirements,EvidenceRules,\ldots) $$

and separately:

$$ R(P)=\{r_1,\ldots,r_n\}. $$

Later M0043 gives:

$$ r\in Req(EC_t). $$

So the corpus clearly intends the requirements to be contract/purpose-relative.

But:

$$ \boxed{ Req(EC_t)=? } $$

as a formally defined extraction/mapping function is not actually closed.

Therefore I classify:

$$ \boxed{ EC_t\rightarrow Req(EC_t) = [CORROBORATION] } $$

rather than [DERIVED].

Why? Because the corpus gives both sides and places them in the same semantic architecture, but does not provide the formal rule that constructs the set.

4. Req(EC_t) → r

This edge is much stronger:

$$ \boxed{ r\in Req(EC_t) } $$

is directly present in the historical F4 formulation and is preserved in the frozen \(\Delta_t\) shape.

So:

$$ \boxed{ Req(EC_t)\rightarrow r = [CORROBORATION] } $$

with the membership relation itself directly evidenced.

There is no need to invent an ontology here.

5. What is AcceptanceCondition?

Here the result is much sharper.

The corpus does not contain a closed AcceptanceCondition definition.

There is only one direct semantic clue:

$$ r.standard $$

is explicitly called:

“acceptance criterion.”

That means the later MD-061 symbol:

$$ Accept_r $$

was not completely invented from nothing.

MD-063 correctly refined it:

$$ \boxed{ Accept_r \text{ is a placeholder for the unspecified rule represented by }standard. } $$

But this does not tell us what that rule is.

The corpus explicitly leaves unresolved:

$$ \boxed{ standard\rightarrow actual\ acceptance\ rule } $$

Therefore the strongest possible statement is:

AcceptanceCondition is not corpus-defined as an independent semantic object. standard is corpus-defined as an “acceptance criterion,” but the interpretation of that criterion is unresolved.

6. The decisive edge: \(EC_t\rightarrow AcceptanceCondition\)

This is the central result.

The audit searched:

M0043 — source of EC_t
M0047 — source of the typed requirement
M0048
M0125/M0126
M0132

and found no formula that computes an acceptance condition, or the value of Sat, from the arguments of \(EC_t\).

Thus:

$$ \boxed{ EC_t\rightarrow AcceptanceCondition = [UNWITNESSED] } $$

This is stronger than saying merely “open.”

There is no corpus-witnessed mapping.

At the same time, this does not mean:

$$ EC_t\not\rightarrow AcceptanceCondition $$

as a mathematical impossibility.

It means:

$$ \boxed{ \text{The corpus has not established the relationship.} } $$

That distinction is crucial.

7. Why this is not merely a missing Sat

The corpus does establish:

$$ K_t\in\mathbb K $$ $$ EC_t=EC(S_t,G_t,Q_t,C_t) $$ $$ r\in Req(EC_t) $$

and the frozen satisfaction shape:

$$ Sat(K_t,r). $$

It also establishes:

$$ \Delta_t= \{r\in Req(EC_t):\neg Sat(K_t,r)\}. $$

These are genuine historical constructs.

But the missing question is:

What semantic information does r carry that tells the evaluator what to inspect in \(K_t\)?

Until that is known, Sat has no semantically determined target.

So the dependency is:

EC_t
 │
 ▼
Requirement membership
 │
 ▼
r
 │
 ├── content
 ├── type
 ├── scope
 ├── standard  ← acceptance criterion, but semantics OPEN
 ├── priority
 └── validity
 │
 ▼
[MISSING SEMANTIC BOUNDARY]
 │
 ▼
Sat(K_t,r)

The missing layer is before the evaluator.

8. MD-061 reconstruction: exactly what was assumed

MD-061 constructed:

$$ Sat^*(K_t,r)=1 \iff \pi_{component_r}(\Sigma_t(K_t)) \in Accept_r. $$

with:

$$ r=(component_r,Accept_r). $$

This was executable, but only after three explicit restrictions:

\(K_t\) restricted to the V7 representation;
requirements restricted to the \(\Sigma_t\)-shaped subtype;
enumerated domains treated as flat sets.

And crucially:

$$ EC_t $$

does not appear in the requirement semantics.

MD-062 therefore found that the same \(K_t\) could not be evaluated differently under different contracts, which is exactly what purpose-relative EC_t would require.

So:

$$ \boxed{ Sat^* \text{ is computationally real but semantically non-closed.} } $$
9. What the corpus says about the missing boundary

This is where the latest reconstruction found something genuinely new.

M0125 itself contains a native diagnosis:

Sat collapse | value∘Eval_c | Reason for U (9→1)

So the issue of losing a Reason distinction is not merely something MD-062 imported from the sibling experiment. It already occurs in the primary V7 document.

M0125 also commissions M0127 as its explicit next action. Therefore the previous characterization of M0127 as an unrelated sibling experiment was corrected: it is commissioned execution from the same research lineage. But its scope remains different: it evaluates Standing(p), not Sat(K_t,r).

M0127 provides a structured boundary involving:

$$ Reason,\ Provenance,\ Context,\ Condition $$

around its Standing(p) evaluation.

The corpus therefore gives us real evidence for a boundary pattern, but not yet proof that the same pattern belongs to requirement satisfaction.

MD-063 states this precisely:

$$ \boxed{ \text{Boundary structure evidenced for }Standing(p) \neq \text{boundary established for }Sat(K_t,r). } $$

10. The four MD-062 failures now have precise minimum distinctions

This is probably the most useful output of the reconstruction.

Failure	Minimum distinction evidenced	Status for Sat(K,r)
E1 — same state, different \(EC\)	purpose/why the value is acceptable	Required in principle; no rule
E2 — same value, different requirement meaning	Reason-like distinction	Corroborated from Standing, not proven for Sat
E3 — same \(\Sigma\), different temporal/history state	temporal/history distinction	Corroborated from Standing, not proven for Sat
E5 — no evidence vs. not assessed	Reason-like assessment distinction	Strong corroboration, but not proven for Sat

MD-063 explicitly says all four have named corpus-evidenced candidate distinctions, but none is demonstrated by a formula for Sat(K_t,r) itself.

That is the correct epistemic boundary.

11. DDD boundary result

This is equally important.

The corpus currently has:

EpistemicContract
        │
        │  NO STATED MAPPING
        ▼
Requirement / Acceptance Semantics
        │
        ▼
Evaluation

MD-062 explicitly characterized EpistemicContract and AcceptanceCondition as different bounded contexts with no stated connection.

There is also:

$$ \mathcal T $$

and:

$$ \mathcal R_{\rm req} $$

but the corpus does not establish:

$$ \boxed{ \mathcal T\Rightarrow\mathcal R_{\rm req}. } $$

The mandatory operation universe remains a governance question, while \(\mathcal R_{\rm req}\) is a qualified research/governance object.

So neither should be smuggled into the semantic bridge.

12. Relation to \(\mathcal R_{\rm req}\)

The corpus gives \(\mathcal R_{\rm req}\) as a required distinction universe, with representation-preservation implications.

But it does not establish that:

$$ \mathcal R_{\rm req} = Req(EC_t) $$

nor:

$$ \mathcal T\Rightarrow\mathcal R_{\rm req}. $$

The current evidence is better represented as:

        𝒯
        │
        └──► required distinctions / operational sufficiency
                    │
                    ▼
                ℛ_req
                    │
                    ├──► representation preservation
                    └──► evaluation / Zero / Contr requirements

EC_t ──► Requirements ──► r
                    │
                    ▼
             [missing interpretation]

The relationship between the two requirement universes remains:

$$ \boxed{ [OPEN] } $$

not an assumption.

13. Factivity and Contr
Factivity

No direct dependency of the Requirement–Evaluation Boundary on the Factivity decision was established in this reconstruction.

The corpus's factivity issue remains important elsewhere, but we do not have evidence that it determines the semantics of r or standard.

Therefore:

$$ \boxed{ Factivity\rightarrow RequirementInterpretation = [UNWITNESSED] } $$

for this boundary.

Contr

There is stronger adjacency:

$$ EC $$

contains ConflictRules, and later requirement-status formulations include Conflicted.

But:

$$ Contr\rightarrow AcceptanceCondition $$

is still not closed.

So:

$$ \boxed{ Contr\text{ is adjacent but not established as a dependency of requirement interpretation.} } $$

No Contr semantics should be selected here.

14. Final answers
1. What does r mean?

Strongest corpus-grounded definition:

$$ \boxed{ r\text{ is a purpose-relevant epistemic requirement specifying what must be established, together with its type, scope, acceptance criterion, priority and temporal applicability.} } $$

Primary concrete form:

$$ \boxed{ r=(id,type,scope,content,standard,priority,validity). } $$

The semantics of standard remain open.

2. What does AcceptanceCondition mean?

The corpus does not define an independent AcceptanceCondition object/function.

The strongest available evidence is:

$$ \boxed{ standard = \text{“acceptance criterion”}. } $$

Therefore Accept_r is best classified as a placeholder for the unspecified semantics of standard, not as an established corpus object.

3. Does \(EC_t\) determine acceptance semantics?
$$ \boxed{ \textbf{[UNWITNESSED]} } $$

The corpus establishes that satisfaction/adequacy is purpose-relative to \(EC_t\), but no corpus source supplies the mapping

$$ EC_t\rightarrow AcceptanceCondition. $$

MD-063 explicitly searched M0043, M0047, M0048, M0125/M0126 and M0132 and found no such rule.

4. Minimal missing bridge

Exactly one object:

$$ \boxed{ \textbf{Requirement Interpretation / Evaluation Boundary} } $$

Its existence as a missing research object is established by the audit; its internal semantics are not.

The corpus currently supports only a partial boundary shape involving:

$$ Reason/Provenance/Context/Condition, $$

but that shape is directly evidenced for Standing(p), not yet for Sat(K_t,r).

5. Effect on Sat

Sat cannot yet be semantically reconstructed or computed faithfully because the meaning of the requirement to be evaluated is incomplete.

More precisely:

$$ \boxed{ r\text{ is structurally known} \quad\neq\quad r\text{'s evaluation semantics are known}. } $$

Therefore:

$$ Sat^* $$

can be computationally constructed, as MD-061 demonstrated, but cannot be identified with the corpus-native Sat.

6. Governance dependencies

Keep these separate:

$$ \boxed{ \mathcal T_{\rm mandatory}=[OPEN/GOVERNANCE] } $$

The corpus has \(\mathcal T\subseteq\mathcal O\), but no authoritative mandatory-membership act.

And:

$$ \boxed{ \mathcal R_{\rm req}=[QUALIFIED/OPEN] } $$

A proposed requirement universe exists, but its binding adoption and exact relation to Req(EC_t) are not established.

Neither should be used to manufacture the missing semantic mapping.

15. One next legitimate research action

The corpus itself now gives us a very narrow next step:

$$ \boxed{ \textbf{Resolve whether M0125's informal `Sat` and M0043's formal }Sat(K_t,r)\textbf{ are the same predicate.} } $$

This is not a construction task.

It is a provenance/identity reconstruction:

$$ Sat_{M0125} \stackrel{?}{=} Sat_{M0043}. $$

Why this first?

Because M0125 itself contains the native Sat-collapse diagnosis, independently of M0127. If the two Sats are the same predicate, then the Reason-related evidence in M0125 becomes directly relevant to the F4 satisfaction boundary. If they are not, it remains only structural analogy. MD-063 identifies exactly this as the smallest unresolved research input.

Decisive boundary statement

So the narrow corpus-native conclusion is:

$$ \boxed{ EC_t \;\longrightarrow\; \underbrace{Req(EC_t)}_{\text{supported as purpose-relative requirement family}} \;\longrightarrow\; \underbrace{r}_{\text{typed requirement}} \;\longrightarrow\; \boxed{\text{MISSING REQUIREMENT-INTERPRETATION / EVALUATION BOUNDARY}} \;\longrightarrow\; Sat(K_t,r) } $$

with:

$$ \boxed{ standard=\text{“acceptance criterion”} } $$

being the strongest positive clue, but:

$$ \boxed{ standard\rightarrow AcceptanceCondition } $$

still [UNRESOLVED].

And:

$$ \boxed{ EC_t\rightarrow AcceptanceCondition = [UNWITNESSED]. } $$

That is the precise point at which the corpus currently stops. No Sat definition, evaluator, codomain change, \(K_t\) extension, \(\mathcal T\) selection, \(\mathcal R_{\rm req}\) ratification, Factivity decision, or Contr decision is justified by this audit