# KT-6 — `docs/knowledge_tranfer/` 2026-08-16: Track B semantics (Rule → Authority → Scope → Dimensions → Environment)

| Field | Value |
|---|---|
| Read (full) | `20260816_1211_rule_model_business_semantics.md` · `…_1332_authority_model_business_semantics.md` · `…_1351_scope_applicability_business_semantics.md` · `…_1513_sc1_dimension_preparation.md` · `…_2043_environment_vocabulary_preparation.md` |
| **Lane** | **"TRACK B — EXPLORATORY. OUTSIDE THE GOVERNED ARCHITECTURE LANE."** Rulings were made *"inside Track B"* and **govern nothing** until the Architecture Baseline is *"reconstructed · independently verified · accepted"* |
| Method | answers drawn from **running mechanisms** (*"derived from what already runs"*); claims graded OBSERVED/INFERRED |
| Labels | [TB-RULED] ARB ruling inside Track B · [SRC-G] · [I] · [?] |

## 1. Rule

- **[TB-RULED RM-1]** **"A Rule is a standing, authoritative obligation governing behaviour within a defined scope and period. A deviation is non-conformance unless an authorized exception permits it."**
  - Rule is kept separate from recommendations (*"bind nobody"*), decisions/rulings and permissions.
  - [SRC-G] measured: *"eight things are called a rule"*; only three satisfy the definition.
- **[TB-RULED RM-2]** **Binding strength ≠ enforcement consequence.** *"A Rule may be MANDATORY while its current enforcement is ADVISORY."* The need for a common strength concept is approved; the vocabulary is deferred.
- **[TB-RULED RM-3]** Changing an authoritative Rule requires explicit authorization and a recorded reason: **Propose → Review → Authorize → Effective.**
- **[TB-RULED RM-4]** **"An exception is a separately authorized deviation from a Rule within a defined scope and validity period."** Exception is *constitutive* of Rule.
- **[SRC-G] Semantic dimensions of a Rule:** obligation · scope · validity · binding strength · authority · evidence · exceptions · supersession.
  - *"The rules with semantics and the rules with enforcement are two different sets, and nothing connects them."*
  - *"Conflict detection is the first thing that becomes possible once a rule can state where, when, how strongly, and what it obliges."*

## 2. Authority (measured on 20 live grants)

- **[SRC-G] Definition (proposed):** **"Authority is a recorded permission for a named activity within a stated scope, whose legitimacy derives entirely from a human act performed outside the system."**
- **[SRC-G OBSERVED] Measured on the 20 grants:**
  - 20/20 grants carry `humanActRef`;
  - 20/20 `registeredBy: governance`;
  - 13/20 cite an immutable commit;
  - 0/20 carry validity;
  - status is always `AUTHORIZED` (*"authority can be created and never ended"*);
  - 0/20 carry delegation or owner.
  
  *"The mechanism never holds authority because it never contains any. It holds a reference to something it cannot create."*
- **[TB-RULED AM-1…AM-5]:**
  - **AM-1** authority must be able to end. Start, expiry, revocation, suspension and supersession are *different business situations*.
  - **AM-2** delegated ≠ independent authority.
  - **AM-3** every grant needs an immutable reference to the human act.
  - **AM-4** ownership ≠ authority.
  - **AM-5** authority must be linkable to the Rule it authorises.

## 3. Scope and applicability

- **[SRC-G]** *"Scope is the set of things a statement is about."* There are at least three concepts:
  - **S-1 subject scope**;
  - **S-2 authority scope**;
  - **S-3 evaluation scope** (*"the checker did not look there"* ≠ *"the rule does not apply there"*).
- **[SRC-G]** An authority can only authorise a rule whose subject scope lies **within** the authority's scope. This is the check that makes RM-3 real; the chain *exception scope ⊂ rule scope ⊂ authority scope* is a **candidate** relationship only.
- **[TB-RULED SC-2]** **"When a scope dimension is not specified, it is treated as UNSPECIFIED — not as 'all'. Missing scope must never silently broaden the applicability of an engineering Rule."** Controlled defaults are allowed only if explicit and governed.
- **[SRC-G, ARB-elevated CANDIDATE invariant]** **"Unknown applicability must never be represented as non-applicability."**
  - **`UNKNOWN ≠ DOES NOT APPLY` · `UNKNOWN ≠ EMPTY SCOPE` · in set terms `unknown ≠ ∅`.**
  - Applicability becomes **three-valued: APPLIES · DOES NOT APPLY · CANNOT DETERMINE.** CANNOT DETERMINE never satisfies a gate.
  - *"False NON-conflicts are silent"*: the hazard is treating unspecified as empty.
- **[TB-RULED SC-1, 2026-08-16 20:40]** Four applicability dimensions:
  - **system location** (product → component hierarchy);
  - **environment**;
  - **artifact kind**;
  - **lifecycle phase**.

  **Audience is not one** (relevance ≠ applicability). Each dimension is **specified · unspecified · not applicable**. **"Not applicable" is a governed property of the subject kind, not an author's declaration.** "Unspecified must not be treated as not applicable."
- **[SRC-G, EV-1 preparation; not ruled in the files read]** Environment vocabulary:
  - declared values are `local` and `testing`;
  - `production` exists only as a **silent framework fallback**, *"the system reports itself as PRODUCTION"* when unset, the opposite of SC-2;
  - there is no production system. Recommendation: Option C.

## 4. Interpretation (for the reconstruction)

- **[I] This is the most explicit treatment of UNKNOWN in any source read so far.**
  - Three-valued applicability.
  - `unknown ≠ ∅`.
  - Unspecified ≠ not-applicable ≠ all.
  - Absence never broadens reach.

  It matches, and makes precise, the corpus's reported **H-ZERO-001 (UNKNOWN preservation)**. It is also exactly the separation my formal Result B needed between **Zero_gap (Δ = ∅)** and **UNKNOWN**. **A strong lead for the Zero/Gap pilot:** the corpus's two "Zero" meanings may be the *empty-gap* and *unknown/neutral-start* states that SC-2 forbids conflating.
- **[I] The authority model's core** (*legitimacy derives entirely from an external human act; the mechanism holds only a reference*) is the operational form of **ARCH-INV-1**. It matches *Proposal ≠ Decision* in the corpus axioms.
- **[?]** Track B *"governs nothing."* These are semantics about **engineering knowledge governance (T1/T2)**, not about the corpus theory. Whether the later corpus theory took them up is untested.
