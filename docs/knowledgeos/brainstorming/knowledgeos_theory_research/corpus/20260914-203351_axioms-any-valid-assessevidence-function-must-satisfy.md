This is the correct next step. We are no longer inventing ontology or architecture—we are now doing **mathematics**. We need to define the properties that any valid `AssessEvidence` function must satisfy, regardless of policy. This gives us a formal specification against which we can test candidate functions.

---

# Computational Closure 4 — The Properties of Evidence Assessment

## The Function Signature

We are formalizing:

$$
\boxed{
\mathcal A:
\mathcal P(\mathcal{ER})
\times
\mathcal C
\times
\mathcal P
\times
\mathcal \rho
\rightarrow
\Sigma_A
}
$$

Where:
- $\mathcal{ER}$ = set of Evidential Relations
- $\mathcal C$ = Context
- $\mathcal P$ = Proposition
- $\rho$ = Assessment Policy
- $\Sigma_A$ = Evidence Assessment State (support strength, uncertainty, conflict status, etc.)

We now ask: **What properties must $\mathcal A$ satisfy?**

---

## The Property Set

### 1. Determinism

**Statement:** For the same inputs, $\mathcal A$ must produce the same output.

$$
\boxed{
\mathcal A(ER, C, P, \rho) \text{ is a function, not a relation}
}
$$

**Rationale:** KnowledgeOS must be auditable. If the same evidence is assessed twice under the same policy and context, the result must be identical.

**Implications:**
- No randomness in core assessment (randomness may exist in interpretation, but not in assessment).
- Assessment must be based on deterministic rules.

---

### 2. Monotonicity (Where Appropriate)

**Statement:** Adding evidence that supports a proposition should not decrease its assessed support. Adding evidence that contradicts a proposition should not increase its assessed support.

$$
\boxed{
ER_{\text{support}} \subseteq ER' \implies \text{Support}(\mathcal A(ER')) \geq \text{Support}(\mathcal A(ER))
}
$$

$$
\boxed{
ER_{\text{contradict}} \subseteq ER' \implies \text{Contradiction}(\mathcal A(ER')) \geq \text{Contradiction}(\mathcal A(ER))
}
$$

**Rationale:** KnowledgeOS should never have a situation where adding supporting evidence reduces assessed support, or adding contradictory evidence increases assessed support. This would be epistemically incoherent.

**Important Caveat:** If new evidence contextualizes or qualifies existing evidence, monotonicity may not apply in the simple form. However, the relationship must remain well-defined.

**Refinement:** We should distinguish between **support monotonicity** (adding supporting evidence increases support) and **epistemic monotonicity** (adding evidence may increase uncertainty, which is not a simple monotonic relationship). We must be precise about which quantities are monotonic.

---

### 3. Non-Double-Counting (Independence Sensitivity)

**Statement:** Evidence that shares provenance must not be counted as independent evidence.

$$
\boxed{
\text{Weight}(ER_1 \cup ER_2) \leq \text{Weight}(ER_1) + \text{Weight}(ER_2)
}
$$

With equality only if $ER_1$ and $ER_2$ are fully independent.

**Rationale:** If the same source is cited multiple times, it should not provide multiple units of evidence. This prevents false confidence.

**Formal Requirement:** $\mathcal A$ must include an independence factor that reduces the weight of evidence that shares provenance.

---

### 4. Provenance Sensitivity

**Statement:** The assessment must depend on the full provenance chain of the evidence.

$$
\boxed{
\mathcal A(ER, \text{provenance}, C, P, \rho) \text{ is a function of provenance}
}
$$

**Rationale:** Two identical observations from different sources must be assessed differently if they have different provenance. This is how we distinguish "database says 3.69" from "LLM says 3.69."

**Implications:**
- Provenance must be part of the assessment computation.
- The provenance chain must be traceable in the output.

---

### 5. Contradiction Preservation

**Statement:** Contradictory evidence must be preserved in the assessment state.

$$
\boxed{
ER_{\text{support}} \neq \emptyset \land ER_{\text{contradict}} \neq \emptyset \implies \text{ConflictStatus}(\mathcal A) = \text{Active}
}
$$

**Rationale:** Contradiction is an epistemic state, not an error to be hidden. KnowledgeOS must represent conflicts explicitly.

**Implications:**
- $\mathcal A$ must track supporting and contradicting evidence separately.
- The final epistemic state can be `Conflicting`.
- Conflict resolution is a separate process from assessment.

---

### 6. Context Dependence

**Statement:** The assessment must depend on the context in which the evidence is evaluated.

$$
\boxed{
\mathcal A(ER, C_1, P, \rho) \neq \mathcal A(ER, C_2, P, \rho)
}
$$

**Rationale:** The same evidence can have different relevance in different contexts. For example, a version number is relevant to a migration decision but irrelevant to a firewall configuration decision.

**Implications:**
- Context must be an explicit input to $\mathcal A$.
- Relevance is a function of context.

---

### 7. Policy Dependence

**Statement:** Different assessment policies may produce different results from the same evidence.

$$
\boxed{
\mathcal A(ER, C, P, \rho_1) \neq \mathcal A(ER, C, P, \rho_2)
}
$$

**Rationale:** Different domains or organizations may have different epistemic standards. A security-critical system may require higher reliability thresholds than a documentation system.

**Implications:**
- Policy must be an explicit input.
- The output must include which policy was used.
- The assessment must be reproducible given the policy.

---

### 8. Idempotence

**Statement:** Evaluating the same evidence multiple times should not change the result.

$$
\boxed{
\mathcal A(\mathcal A(ER) \cup ER) = \mathcal A(ER)
}
$$

This is a stronger form of non-double-counting. If the assessment has already considered evidence $ER$ and then $ER$ is presented again (as a new assessment of the same evidence), the result should be identical.

**Rationale:** KnowledgeOS should be idempotent to handle duplicate inputs gracefully.

**Note:** We should define what "same evidence" means—the same source observation and proposition. If the provenance differs, it is not the same evidence.

---

### 9. Reproducibility

**Statement:** Given the full assessment provenance, any evaluator can reproduce the assessment.

$$
\boxed{
\forall \text{inputs}, \text{Provenance}(\mathcal A) \implies \text{Reproduce}(\mathcal A) = \mathcal A
}
$$

**Rationale:** This is the auditability requirement. If KnowledgeOS asserts an epistemic state, it must be possible to trace how that assessment was reached.

**Implications:**
- All inputs to the assessment function must be stored.
- The assessment function must be deterministic (see Property 1).
- The policy must be stored as part of provenance.

---

### 10. Composability

**Statement:** Assessments should be composable. That is, if evidence is assessed in sub-groups, the aggregate assessment should be the same as assessing all evidence together.

This is subtle and may not hold fully. We need to define a weaker property:

**Weak Composability:** Aggregating sub-assessments must be well-defined and not lead to contradictions.

We can formalize this as:

$$
\boxed{
\text{If } ER = ER_1 \cup ER_2 \text{ then } \mathcal A(ER) = \text{Aggregate}(\mathcal A(ER_1), \mathcal A(ER_2))
}
$$

**Rationale:** This allows KnowledgeOS to process evidence incrementally.

**Note:** This may not hold if independence and contextualization depend on the full set.

---

### 11. Behavior Under Additional Evidence

**Statement:** Adding new evidence must produce a well-defined transition.

If $ER_2$ is added to an existing assessment:

$$
\boxed{
\mathcal A(ER_1 \cup ER_2, C, P, \rho) = \text{Update}(\mathcal A(ER_1), ER_2)
}
$$

**Rationale:** KnowledgeOS must be able to update assessments incrementally as new evidence arrives.

**Implications:**
- The `Update` function must be well-defined.
- It must preserve the properties of the assessment.

---

### 12. Behavior Under Removal of Evidence

**Statement:** Removing evidence must produce a well-defined transition.

If $ER_2$ is removed from an existing assessment:

$$
\boxed{
\mathcal A(ER \setminus ER_2, C, P, \rho) = \text{Rollback}(\mathcal A(ER), ER_2)
}
$$

**Rationale:** KnowledgeOS must be able to handle evidence that becomes invalid or is corrected.

**Implications:**
- The `Rollback` function must be well-defined.
- It must preserve the properties of the assessment.

---

## The Complete Property Set

| # | Property | Formal Statement |
|---|----------|------------------|
| 1 | **Determinism** | $\mathcal A$ is a function |
| 2 | **Support Monotonicity** | $ER_{\text{support}} \subseteq ER' \implies \text{Support}(ER') \geq \text{Support}(ER)$ |
| 3 | **Non-Double-Counting** | $\text{Weight}(ER_1 \cup ER_2) \leq \text{Weight}(ER_1) + \text{Weight}(ER_2)$ |
| 4 | **Provenance Sensitivity** | $\mathcal A$ depends on provenance |
| 5 | **Contradiction Preservation** | $ER_{\text{support}} \neq \emptyset \land ER_{\text{contradict}} \neq \emptyset \implies \text{ConflictStatus} = \text{Active}$ |
| 6 | **Context Dependence** | $\mathcal A(ER, C_1) \neq \mathcal A(ER, C_2)$ |
| 7 | **Policy Dependence** | $\mathcal A(ER, \rho_1) \neq \mathcal A(ER, \rho_2)$ |
| 8 | **Idempotence** | $\mathcal A(\mathcal A(ER) \cup ER) = \mathcal A(ER)$ |
| 9 | **Reproducibility** | Provenance $\implies$ Reproduce $=$ Original |
| 10 | **Composability** | $\mathcal A(ER_1 \cup ER_2) = \text{Aggregate}(\mathcal A(ER_1), \mathcal A(ER_2))$ (where defined) |
| 11 | **Incremental Update** | $\mathcal A(ER_1 \cup ER_2) = \text{Update}(\mathcal A(ER_1), ER_2)$ |
| 12 | **Rollback** | $\mathcal A(ER \setminus ER_2) = \text{Rollback}(\mathcal A(ER), ER_2)$ |

---

## The Next Step

Now that we have the property set, we can:

1. **Define candidate aggregation functions** for $\mathcal A$.
2. **Test each candidate** against the property set.
3. **Identify which properties are not satisfied**, and for what reasons.
4. **Refine or reject** candidates.
5. **Determine if any function can satisfy all properties simultaneously**.

This will give us a mathematically justified Evidence Assessment function.

**Shall I now propose several candidate aggregation functions for formal evaluation?**