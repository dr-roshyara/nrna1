---

# Abstract

**Title:** Discovering Bounded Contexts Under Guarantee Uncertainty: A Governance-Guided Approach for High-Trust Systems

---

Domain-Driven Design (DDD) depends on deep domain understanding to discover bounded contexts and aggregates. Existing DDD and architecture-discovery literature largely assumes that core domain goals and guarantees can be elicited from stakeholders or documentation. However, in high-trust, regulation-sensitive systems—such as election infrastructures, healthcare platforms, and financial systems—critical domain guarantees may be unspecified, implicit, or known only through implementation artifacts that do not reveal their intended purpose. This creates a fundamental challenge: how can domain discovery proceed when *what* the system does is observable, but *why* it does it and *which guarantees* it protects remain unknown?

This paper presents a governance-guided domain discovery process that addresses this challenge. The core insight is a distinction between two classes of discovery questions: **repository questions** (WHAT, HOW, WHERE)—answerable through code analysis—and **governance-origin questions** (WHY, WHO, WHICH guarantees)—requiring non-repository methods such as interviews, ADR archaeology, and governance document review. When repository discovery saturates but governance-origin questions remain unresolved, continuing code analysis creates process deadlock. We propose a three-phase approach: (1) repository-based discovery to document implementation behavior; (2) governance clarification to elicit intended guarantees; and (3) candidate bounded context discovery proceeding with *provisional boundaries*—candidate context boundaries explicitly marked as subject to revision pending resolution of governance-origin uncertainty. This pattern avoids both discovery paralysis and premature architecture certainty.

We validate this approach through a detailed case study of an election system discovery effort spanning seven streams of investigation. The corpus produced 23 hypotheses and 42 discovery debt items, including eight HIGH STRATEGIC items—all governance-origin questions that repository analysis could not resolve. The pivotal unknown asks: "What election integrity guarantees are explicitly intended by the system?" Critically, the system contained extensive integrity mechanisms—cryptographic checksums, receipt hashes, participation proofs—yet the guarantees these mechanisms were intended to satisfy remained undocumented. The case study revealed a broader pattern: implementation artifacts exposing integrity-related behavior do not necessarily reveal the guarantees behind that behavior.

An Architecture Review Board (ARB)-driven decision closed repository discovery, authorized parallel governance clarification, and initiated bounded context discovery with provisional candidate contexts. The safeguard: any guarantee-sensitive context decision remains provisional until governance clarification completes, enabling forward momentum while preserving architectural correctness.

The contribution is threefold: (1) a distinction between repository questions and governance-origin questions, with criteria for determining when repository discovery has reached saturation; (2) a governance-guided discovery pattern employing provisional boundaries under explicit constraints; and (3) a concrete case study demonstrating the pattern in an election system under constitutional constraints. This pattern generalizes to any high-trust system where constitutional, legal, or regulatory guarantees shape domain boundaries—including healthcare, finance, public infrastructure, and safety-critical systems.

---

**Keywords:** Domain-Driven Design, bounded contexts, software architecture governance, discovery under uncertainty, election systems, trustworthy computing, guarantee-sensitive design, ARB process, provisional boundaries