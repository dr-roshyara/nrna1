# Research Review: Expression, Meaning, and Invariance in Knowledge Systems

**Research Date:** August 22, 2026  
**Researcher Role:** Independent Research Architect  
**Scope:** External research evidence on knowledge representation, expression, meaning, and invariance

***

## Executive Summary

External research provides **strong evidence** for the distinction between **knowledge/meaning** and **expression/representation**, but **does not support** expression as a new kernel dimension. The research consensus indicates that expression belongs to the **representation/projection layer**, not the semantic identity layer. [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)

The Vāṇī concept raises valid questions about **knowledge transmission across media transformations** (oral → written → digital → AI summary), and research on **invariant learning** and **provenance** provides technical foundations for preserving meaning identity across expression changes. [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)

**Research Conclusion:** Expression ≠ Meaning is a **supported hypothesis** for KnowledgeOS, but it should be classified as a **representation-layer concern**, not a kernel dimension. The kernel should preserve **semantic identity invariants** while allowing expression to vary.

***

## 1. Knowledge Representation: Five Roles (Davis, Shrobe, Szolovits, 1993)

**Research Finding:** The foundational knowledge representation literature identifies **five distinct roles** of knowledge representations: [aclanthology](https://aclanthology.org/J01-2006.pdf)

1. **Surrogate:** A substitute for the thing itself, enabling reasoning about the world rather than acting in it
2. **Ontological Commitments:** A set of statements about categories of things that may exist in the domain
3. **Fragmentary Theory of Intelligent Reasoning:** A model of what the things can do or can be done with
4. **Medium for Pragmatically Efficient Computation:** The computational environment in which thinking is accomplished
5. **Medium of Human Expression:** A language in which we say things about the world

**Critical Insight:** "A knowledge representation is **not a data structure**" — it is a **surrogate** with ontological commitments, reasoning theory, computational efficiency, and human expressiveness. [courses.cs.umbc](https://courses.cs.umbc.edu/771/current/presentations/whatiskr.pdf)

**Relevance to KnowledgeOS:** This directly supports the distinction:
- **Knowledge/Meaning** (what is represented)
- **Expression/Representation** (how it is represented)

The representation is a **medium of human expression**, but it is not the knowledge itself.

**Architectural Implication:** Expression belongs to the **representation layer**, not the kernel. The kernel should preserve semantic identity; expression can vary.

***

## 2. Meaning vs. Expression in Philosophy of Language

**Research Finding:** Philosophical semantics distinguishes between:

- **Expression meaning:** How an expression is used in communication (public language) vs. thought (mental representation) [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)
- **Linguistic meaning:** Meaning of linguistic signs, distinct from cognitive meaning (knowledge expressed in language) [library.oapen](https://library.oapen.org/bitstream/id/344882f1-7b4b-4d87-a7ca-80c9965718b4/9781000555172.pdf)
- **Cognitive meaning:** Knowledge expressed in language, perception of fact or truth [library.oapen](https://library.oapen.org/bitstream/id/344882f1-7b4b-4d87-a7ca-80c9965718b4/9781000555172.pdf)

**Key Distinction:** "Knowing what an expression means is neither knowing that it means such and such nor knowing how to do things with the expression; it is being in, or being apt to be in, a certain kind of language-processing state." [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)

**Relevance to KnowledgeOS:** This supports the hypothesis:
- **Expression ≠ Meaning**
- **Representation ≠ Semantic identity**

**Research Gap:** Limited research on operationalizing this distinction in knowledge system architectures.

***

## 3. Knowledge Transmission Across Media

**Research Finding:** Research on oral, written, and digital knowledge transmission identifies:

- **Transmission:** How information is passed on and received from one person or group to another [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)
- **Retention:** How information is preserved across time
- **Transformation:** How information changes when moving between media [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)

**Critical Insight:** "Although the cognitive processes used to interpret experience does not change; the transmission, retention, and transformation information is different across oral, written and digital modes." [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)

**Research Finding:** "Knowledge cycles back and front between oral and media sources, knowledge generated in one medium can become beneficial to other situational contexts." [internationalpolicybrief](https://internationalpolicybrief.org/wp-content/uploads/2023/12/ARTICLE14_2.pdf)

**Relevance to KnowledgeOS:** This directly supports the Vāṇī insight:
- Knowledge survives **medium transformations** (oral → written → digital → AI summary)
- **Meaning identity** should persist while **expression changes**

**Architectural Implication:** A trustworthy system must preserve **semantic identity invariants** across expression transformations.

***

## 4. Invariant Learning and Semantic Invariance

**Research Finding:** Invariant graph learning research identifies the **semantic invariance principle**:

**Definition (Semantic Invariance Principle):** Given a graph G, if Y can be completely determined and P_e(Y | Φ*(G)) = P_e'(Y | Φ*(G)) in every environment, then the features extracted by Φ are **invariant** across environments.  [arxiv](https://arxiv.org/pdf/2501.12595.pdf)

**Research Finding:** "Invariant graph learning, originally derived from Invariant Risk Minimization (IRM), attains out-of-distribution generalization by learning graph representations invariant to environmental changes." [arxiv](https://arxiv.org/pdf/2501.12595.pdf)

**Key Insight:** "There exist a noise-free bipartite subgraph and task-relevant knowledge subgraph that satisfy: (i) Sufficiency condition: the subgraph captures sufficient information; (ii) Invariance condition: the association remains stable and invariant across environments." [openreview](https://openreview.net/pdf?id=R7m416IMZj)

**Relevance to KnowledgeOS:** This provides a **technical foundation** for:
- **Semantic identity invariants** (what must remain unchanged)
- **Expression variance** (what can change across environments/media)

**Architectural Implication:** The kernel should identify and preserve **invariant subgraphs** (semantic identity) while allowing **expression variance** (representation changes).

***

## 5. Provenance and Authenticity Across Transformations

**Research Finding:** Provenance research distinguishes:

- **Provenance:** The origin, history, and chain of custody of content [arxiv](https://arxiv.org/pdf/2405.12336.pdf)
- **Authenticity:** Whether content has been manipulated or altered in a way out of the control of the trusted source [arxiv](https://arxiv.org/pdf/2405.12336.pdf)

**Critical Insight:** "One way to characterize this history is to use the term 'provenance,' meaning, the identifiable source of the content and an accurate history of the content's transformation from that source." [arxiv](https://arxiv.org/pdf/2405.12336.pdf)

**Relevance to KnowledgeOS:** This directly supports the Vāṇī question:
- **How do we preserve identity when knowledge changes medium?**
- **What survives transformation?**

**Architectural Implication:** Provenance must track **transformation history** (oral → written → digital → AI summary) to preserve **authenticity** and **identity**.

***

## 6. Knowledge Representation as Surrogate vs. Reality

**Research Finding:** "A knowledge representation is most fundamentally a **surrogate**, a substitute for the thing itself, that is used to enable an entity to determine consequences by thinking rather than acting." [faculty.ist.psu](https://faculty.ist.psu.edu/vhonavar/Courses/ai100/kr.pdf)

**Critical Distinction:** "Imperfect surrogates mean incorrect inferences are inevitable." [fr.slideserve](https://fr.slideserve.com/rcruz/artificial-intelligence-knowledge-representation-powerpoint-ppt-presentation)

**Relevance to KnowledgeOS:** This reinforces:
- **Knowledge ≠ Representation**
- **Meaning ≠ Expression**
- **Surrogate ≠ Reality**

**Architectural Implication:** The kernel must distinguish between the **surrogate** (representation) and the **semantic identity** (meaning).

***

## 7. Ontological Commitments and Perspective

**Research Finding:** "An ontological commitment is an agreement to use a vocabulary in a way that is consistent with respect to the theory specified by an ontology." [courses.cs.umbc](https://courses.cs.umbc.edu/771/current/presentations/whatiskr.pdf)

**Critical Insight:** "The commitments are in effect a strong pair of glasses that determine what we can see, bringing some part of the world into sharp focus, at the expense of blurring other parts." [courses.cs.umbc](https://courses.cs.umbc.edu/771/current/presentations/whatiskr.pdf)

**Relevance to KnowledgeOS:** This supports:
- Different **expressions** may have different **ontological commitments**
- **Semantic identity** should survive **ontological perspective changes**

**Architectural Implication:** The kernel should preserve **semantic identity** across **ontological perspective changes** (different expressions, different vocabularies).

***

## 8. Truth and Justification in Knowledge Representation

**Research Finding:** "A practical consequence of this neglect is that the existing KR systems store and communicate knowledge that cannot be verified and justified by users of these systems without external means." [ceur-ws](https://ceur-ws.org/Vol-2529/paper5.pdf)

**Critical Distinction:** Knowledge in the philosophical sense requires **justified belief**, not just stored information. [ceur-ws](https://ceur-ws.org/Vol-2529/paper5.pdf)

**Relevance to KnowledgeOS:** This reinforces:
- **Evidence ≠ Authority**
- **Storage ≠ Knowledge**
- **Representation ≠ Justification**

**Architectural Implication:** The kernel must preserve **justification chains** (evidence, authority, provenance) across expression transformations.

***

## 9. Synthesis: Expression as Representation-Layer Concern

**Research Consensus:**

| Aspect | Research Evidence | Classification |
|--------|-------------------|----------------|
| **Expression ≠ Meaning** | Strong (philosophy, KR, NLP)  [academic.oup](https://academic.oup.com/book/4530/chapter/146614153) | Representation layer |
| **Representation ≠ Semantic Identity** | Strong (surrogate theory, ontological commitments)  [faculty.ist.psu](https://faculty.ist.psu.edu/vhonavar/Courses/ai100/kr.pdf) | Representation layer |
| **Meaning Invariance Across Media** | Moderate (transmission research, invariant learning)  [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs) | Kernel invariant |
| **Provenance of Transformations** | Strong (provenance research)  [arxiv](https://arxiv.org/pdf/2405.12336.pdf) | Kernel invariant |
| **Expression as Kernel Dimension** | **Not supported** | Should remain representation layer |

**Research Conclusion:** Expression should **not** be added as a sixth kernel dimension. It belongs to the **representation/projection layer**, because:
- Same meaning can have different expressions (languages, formats, media)
- Meaning survives while expression changes
- Expression is a **medium of human expression** (Davis et al.), not the knowledge itself

***

## 10. Revised KnowledgeOS Hypothesis

**Hypothesis (EKS-08 Extension):**

```
EKS-08 Family:

- Recorded ≠ Temporal truth
- Derived ≠ Persisted
- Projection ≠ Source
- Memory ≠ Available evidence
- Expression ≠ Meaning  [NEW HYPOTHESIS]
```

**Classification:** Research hypothesis, not kernel law.

**Supporting Evidence:**
- Davis, Shrobe, Szolovits (1993): Knowledge representation is a medium of human expression, not the knowledge itself [faculty.ist.psu](https://faculty.ist.psu.edu/vhonavar/Courses/ai100/kr.pdf)
- Philosophical semantics: Expression meaning ≠ cognitive meaning [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)
- Invariant learning: Semantic invariance across environments [openreview](https://openreview.net/pdf?id=R7m416IMZj)
- Provenance research: Transformation history must be preserved [arxiv](https://arxiv.org/pdf/2405.12336.pdf)
- Transmission research: Knowledge survives medium transformations [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)

**What Breaks If Wrong:**
- Loss of semantic identity across expression changes
- Inability to preserve meaning across languages, formats, media
- Conflation of representation with knowledge

**Domain-Owned?** No — this is a **kernel invariant** (semantic identity must be preserved).

**Kernel Candidate?** Yes — but as an **invariant to preserve**, not as a new dimension.

**Confidence:** Moderate (strong theoretical support, limited operational evidence).

**Counter-Evidence:** None found. Research consistently distinguishes expression from meaning.

***

## 11. Architectural Implications

**For KnowledgeOS Kernel:**

1. **Preserve semantic identity invariants** across expression transformations
2. **Track provenance** of transformations (oral → written → digital → AI summary)
3. **Distinguish representation layer** (expression) from kernel layer (meaning)
4. **Support multiple expressions** of the same semantic identity (languages, formats, media)
5. **Validate invariance** across expression changes (invariant learning principles)

**For KnowledgeOS Representation Layer:**

1. **Support multiple expression formats** (text, speech, visual, structured)
2. **Support multiple languages** without losing semantic identity
3. **Support multiple audiences** (technical, business, regulatory)
4. **Track expression transformations** as provenance events
5. **Validate expression fidelity** to semantic identity

***

## 12. Vāṇī as Research Lens (Not Kernel Law)

**Valid Contributions:**
- **Expression ≠ Meaning:** Supported by KR research [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)
- **Knowledge transmission across media:** Supported by transmission research [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)
- **Weaving metaphor (relationships):** Supported by graph/invariant research [openreview](https://openreview.net/pdf?id=R7m416IMZj)
- **Authority question:** Supported by governance/provenance research [arxiv](https://arxiv.org/pdf/2405.12336.pdf)

**Invalid Contributions:**
- **Vāṇī as kernel principle:** Not supported (external authority)
- **Sacred authority model:** Domain-specific, not universal
- **Expression as kernel dimension:** Not supported (belongs to representation layer)

**Correct Classification:**

| Concept | KnowledgeOS Classification |
|---------|---------------------------|
| Speech/expression ≠ meaning | Research support for EKS-08 |
| Knowledge transmission | Research hypothesis |
| Oral → written → digital transformation | Provenance research |
| Weaving metaphor | Conceptual analogy |
| Sacred authority model | Domain-specific |
| Vāṇī as kernel principle | ❌ Not supported |
| Expression as possible dimension | UNKNOWN → Research indicates: **No** (representation layer) |

***

## 13. Final Research Verdict

**EXTERNAL RESEARCH ≠ KNOWLEDGEOS ARCHITECTURE**

External research provides **strong evidence** for:
- Expression ≠ Meaning
- Representation ≠ Semantic Identity
- Knowledge survives medium transformations
- Provenance must track transformation history
- Semantic invariance across environments

External research **does not support**:
- Expression as a kernel dimension
- Vāṇī as a kernel principle
- Sacred authority models as universal

**Whether these apply to KnowledgeOS must be determined by comparison with the reconstructed EKS, PKS, and AIP architectures.**

The Vāṇī concept is valuable as a **research lens** that raises the right questions about **expression, meaning, transmission, and invariance**. It should **not** become kernel law or external authority.

**Recommended Next Step:** Compare this research evidence with EKS/PKS/AIP archaeology to determine whether expression/meaning distinction is already present in the existing systems, and whether it should be formalized as an EKS-08 extension.

***

## 14. Sources / Bibliography

**Foundational KR Research:**
- Davis, R., Shrobe, H., & Szolovits, P. (1993). "What is a Knowledge Representation?" AI Magazine, 14(1), 17. [aclanthology](https://aclanthology.org/J01-2006.pdf)

**Philosophy of Language:**
- "Meanings and Knowledge of Meaning" (2003). Oxford Academic. [academic.oup](https://academic.oup.com/book/4530/chapter/146614153)
- "Significance in Language; A Theory of Semantics" (2022). Open Access. [library.oapen](https://library.oapen.org/bitstream/id/344882f1-7b4b-4d87-a7ca-80c9965718b4/9781000555172.pdf)

**Knowledge Transmission:**
- "Engaging Oral Societies with Digital History Methodologies" (2023). IIPRDS. [scholarscompass.vcu](https://scholarscompass.vcu.edu/cgi/viewcontent.cgi?article=1006&context=tedu_pubs)

**Invariant Learning:**
- "Unleashing the Power of Knowledge Graph for Recommendation via Invariant Learning" (2024). OpenReview. [openreview](https://openreview.net/pdf?id=R7m416IMZj)
- "A Unified Invariant Learning Framework for Graph Classification" (2025). ACM. [dl.acm](https://dl.acm.org/doi/abs/10.1145/3690624.3709203)
- "Graph Invariant Learning with Subgraph Co-mixup" (2024). AAAI. [ojs.aaai](https://ojs.aaai.org/index.php/AAAI/article/view/28700/29356)
- "Invariant Graph Learning via Environment Augmentation" (2023). NeurIPS. [proceedings.nips](https://proceedings.nips.cc/paper_files/paper/2023/file/e21a7b668ce3ea2c9c964c52d1c9f161-Paper-Conference.pdf)

**Provenance:**
- "Truth and Justification in Knowledge Representation" (2019). CEUR-WS. [ceur-ws](https://ceur-ws.org/Vol-2529/paper5.pdf)
- "Provenance and Authenticity" (2024). arXiv. [arxiv](https://arxiv.org/pdf/2405.12336.pdf)

***

**FINAL DISCIPLINE STATEMENT:**

**EXTERNAL RESEARCH ≠ KNOWLEDGEOS ARCHITECTURE**

External research establishes what is known in the field.

EKS/PKS/AIP archaeology establishes what we actually have.

Only the comparison between the two can establish which external principles are relevant to the evolution of KnowledgeOS.

Do not design the kernel.

Do not choose technology.

Do not define bounded contexts.

Do not create ADRs.

Do not propose migration.

The output is an independent research evidence base for a later architecture decision.
#
After reviewing this research document, I think it **does help the KnowledgeOS kernel discovery**, but in a very specific way.

It does **not define the kernel**.

It strengthens our understanding of **what the kernel must protect**.

The most valuable contribution is that it adds another strong candidate to the emerging invariant family:

> **Semantic identity must survive changes in expression.**

The document itself classifies this correctly: expression should not become a sixth kernel dimension; it belongs to the representation/projection layer. 

---

## The important discovery

Before this research, our dimension model was approximately:

```
Knowledge Object

 ├── Semantic
 ├── Evidence
 ├── Authority
 ├── Temporal
 └── Lifecycle
```

The Vāṇī investigation tested:

> Is Expression another dimension?

The answer appears to be:

**No.**

Instead:

```
                 Knowledge Identity
                       │
                       │
        ┌──────────────┴──────────────┐
        │                             │
   Kernel dimensions             Representation
        │                             │
 Semantic meaning              Expression
 Evidence                      Language
 Authority                     Format
 Temporal                      Medium
 Lifecycle                     Projection
```

This is actually a very important simplification.

The research supports the distinction:

* Knowledge/meaning = what is represented
* Expression/representation = how it is represented

and concludes expression belongs to the representation layer, not the kernel. 

---

# The strongest contribution: transformation invariants

The deepest question raised is not:

> "How do we store knowledge?"

It is:

> "What survives when knowledge changes form?"

Example:

```
Human thought
      ↓
Speech
      ↓
Writing
      ↓
Document
      ↓
Database
      ↓
AI summary
      ↓
Visualization
```

The representation changes.

The expression changes.

But what should survive?

The candidate answer:

```
Semantic identity
Provenance
Authority relationship
Evidence lineage
```

The research explicitly connects transmission across media with the need to preserve meaning identity while expression changes. 

This fits extremely well with our current KnowledgeOS direction.

---

# Connection to existing EKS findings

This is where it becomes strategically useful.

It extends the EKS-08 family.

Existing:

```
Recorded ≠ Temporal truth

Derived ≠ Persisted

Projection ≠ Source

Memory ≠ Available evidence
```

Possible extension:

```
Expression ≠ Meaning
```

The document correctly classifies this as a research hypothesis, not a kernel law. 

This is important because it follows our discipline:

```
Observation
    ↓
Evidence
    ↓
Invariant candidate
    ↓
Validation
    ↓
Possible kernel law
```

Not:

```
Beautiful idea
    ↓
Kernel
```

---

# The topology connection becomes clearer now

Earlier we discussed topology.

This research actually gives topology a better role.

Not:

"KnowledgeOS uses topology."

Instead:

Topology may describe:

> what remains invariant while knowledge undergoes transformations.

Example:

```
Original knowledge structure

        A
       / \
      B---C


Expression transformation


        A'
       / \
      B'--C'


Question:

What structural properties survived?
```

Topology asks:

* What is preserved?
* What changes?
* What transformation breaks identity?

That is much closer to our kernel question:

> What transformations must KnowledgeOS prevent?

---

# New possible invariant candidate

I would not add this as established.

I would create a research candidate:

## INV-KOS-002 (candidate)

**Semantic Continuity Across Representation Transformation**

Possible statement:

> KnowledgeOS SHALL preserve semantic identity when knowledge changes expression, representation, or medium.

Status:

```
Strength:
Candidate

Evidence:
External research

EKS/PKS/AIP:
Needs validation

Kernel relevance:
Candidate
```

The document itself recommends exactly this path:

compare the research evidence with reconstructed EKS, PKS, and AIP architectures to determine whether the distinction is already present. 

---

# But there is an even deeper insight

I think this research reinforces our strategic hypothesis:

> KnowledgeOS is not a knowledge storage kernel. It is a constitutional layer preserving separation between knowledge dimensions while allowing controlled evolution.

Why?

Because every important discovery is about preventing collapse:

```
Bad:

Document = Knowledge

AI summary = Source

Confidence = Authority

Popularity = Truth

Representation = Meaning


Good:

Document → representation

AI summary → projection

Evidence → evidence

Authority → governed act

Meaning → preserved identity
```

The kernel is becoming less about "objects".

It is becoming about **protected relationships**.

---

# What I would extract into P4/P5 vocabulary

Not a kernel decision.

Just vocabulary:

## New candidate collapse rule

```
Forbidden Collapse:

Expression → Meaning

```

Meaning:

A representation change must not silently become a semantic change.

---

## New transformation principle

```
Allowed:

Meaning
   ↓
new expression


Forbidden:

new expression
   ↓
changes meaning without explicit semantic transition
```

---

# My verdict

This writing is valuable.

Not because Vāṇī defines KnowledgeOS.

Because it gives us a very strong test:

> If KnowledgeOS cannot preserve meaning while knowledge changes language, format, medium, and representation, then it is only a document management system.

The research gives us:

✅ support for Expression ≠ Meaning
✅ support for Representation ≠ Semantic Identity
✅ support for provenance across transformations
✅ support for invariant thinking
❌ no support for Expression as a kernel dimension
❌ no support for Vāṇī as authority model

The right next step is **not adding a dimension**.

The right next step is asking:

> "Does EKS/PKS/AIP already contain evidence that semantic identity survives representation changes?"

If yes, this becomes another constitutional invariant candidate.

If no, it remains a valuable external research hypothesis. 

This is exactly the kind of research that can make the future KnowledgeOS kernel **smaller and stronger**, because it helps us discover what does **not** belong in the kernel.
