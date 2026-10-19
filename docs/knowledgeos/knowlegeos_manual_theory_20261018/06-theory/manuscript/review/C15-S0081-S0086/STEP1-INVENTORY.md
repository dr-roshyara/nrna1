*Reviewer's step-1 inventory, verbatim (window C15; saved by the Professor, 2026-10-19).*

# STEP 1 INVENTORY — C15

Transition review, step 1, done blind. I read only S0081, S0083, S0084, S0085 and S0086, each whole in one read (`cat -n`). I read nothing under W/06-theory/ and not RESEARCH-PURPOSE.md. For lineage I ran `git log` on the five paths. Independence here is process independence: I have not seen the Professor's work for this window. It is not personal independence.

## State lineage
- **One commit for all five files:** `f24edf316`, 2026-08-28 13:46:59 +0200, author Dr. Nab Raj Roshyara, message "knoweledge os modelling" (`git log --format='%h %ad %an %s' -- <5 paths>` returned exactly one line).
- The files carry no internal dates, versions or authors.
- S0081 says the diagrams come from "the KnowledgeOS / AI Engineering Platform discussions" [S0081 L3]. They are AI-session output and one design act.
- **One line of support.** The four diagrams are not independent of each other. Where they agree, that is consistency within one proposal, not confirmation [I].
- They declare themselves a proposal, not authoritative "until the corresponding PO/ARB decisions are accepted into the authoritative model" [S0081 L3].
- Size: README 12 lines, the four .puml files 27, 25, 39 and 38 lines.

## S0081 (README) — ranked
1. The set is an architecture **proposal**. Its validity depends on a later human decision (PO/ARB) entering an "authoritative model" [S0081 L3]. This is a self-applied version of "a document is not authority until accepted".
2. It assumes there is a single "authoritative model" separate from these artefacts, but does not say where that model is [S0081 L3] [I].
3. It lists four files: three C4 levels plus one detail view [S0081 L7–L10].
4. The 04 view is "not a classical C4 level". It is described as the "L3→L5 contract-neutrality boundary" [S0081 L12]. But S0084 also draws L0–L2 [S0084 L7], so the stated scope is narrower than the diagram [I, minor source defect].
5. The README names no kernel, no kernel/application boundary, and no relation between the three C4 levels [I].

## S0083 (System Context) — ranked
1. **The source of authority is human.** "PO / ARB — Human decision authority" [S0083 L5], and PO "Authorizes / accepts" to the Governance Engineer [L16].
2. **A second authority holder:** the Governance Engineer has "Authority, lifecycle, grants, acceptance" [L6]. Acceptance is held by both PO and the Governance Engineer. The diagram does not say whether this is delegation or a homonym [I].
3. KOS is a "Governed engineering operating system for evidence, architecture, implementation, verification and knowledge lifecycle" [L12]. Its scope is engineering, not knowledge in general. Domain-specific functions (architecture, implementation) are inside the system's own definition [I: this points to application content, not a kernel].
4. **The AI acts only under grants:** "Executes assigned work under grants" [L24]. KOS "Bootstraps and constrains AI sessions" [L23]. Here a grant is a permission on the deontic reading; obligation and prohibition are not shown [I].
5. The repository is an **external** system that holds "source code, architecture artifacts, ADRs, fixtures, evidence and workflow records" [L13]. KOS "reads / writes governed artifacts" there [L22]. On this view, durable knowledge lives outside the platform.
6. The AI runtime is external [L14].
7. **Role separation:** six persons [L5–L10]. Verification is a separate role that "Falsifies and assures" [L10]. Trust is gained by independent falsification, not by self-certification.
8. "Governance Communication" is a **person/role** [L7], but it is a capability in S0085 and S0086 (see cross-source table) [I: a role/capability homonym].
9. All role relations go to "kos" as a whole [L17–L21]. None of them says what the system decides as opposed to what it records [I].

## S0084 (Contract-neutrality fact model) — ranked; the most theory-dense of the five [I]
1. **A derived result does not certify its inputs.** "Metric alone is insufficient" [S0084 L22]. Conformance needs "Node set + edge set + final metric" [L11, L18–L19]. Evidence must include the intermediate representation, not only the outcome.
2. [I] Mathematically sound: LCOM4 (the number of connected components of the method/attribute graph) is many-to-one. Distinct edge sets give the same value, so the metric cannot establish the facts. This is a general non-identifiability argument that reaches beyond PHP.
3. **No back-inference from outputs:** "Qualifier kind is preserved, not inferred from final metric" [L21]. A semantic attribute must come from the facts, never be reconstructed from a derived value. That would be circular.
4. **Layering.** L0–L2 language-specific extraction [L7] → L3 language-neutral facts [L8] → L4 interpretation rules [L9] → L5 derived metric [L10]. It separates observation (fact), rule (semantics) and result (metric).
5. **Neutrality boundary:** the language binding is the only language-specific part, and everything from L3 up is "Language-neutral schema" [L7–L8]. This is a candidate kernel/application split: the binding is an adapter, L3–L5 are invariant [I].
6. The L3 attributes include "stable identity, method identity" and "**determinability**" [L8]. Identity is a fact-level primitive, and indeterminacy (whether a value can be determined) is marked explicitly at the fact level.
7. Conformance is checked against **declared expectations** [L11]. Correctness is defined relative to a declared expected set, so who declares it is unstated [I].
8. A grammar-exact parser is required at the boundary [L7]. Extraction fidelity is a precondition of fact validity.
9. Notation defects: a contract is drawn as `Container_Boundary` [L5], and the `Rel_U` reverse arrows encode constraints, not data flow [L21–L22] [I: the semantics are in labels, not structure].
10. Scope: a single example, PHP and LCOM4. It is the only concrete, worked contract in the set. Generalising from it is my inference [I].

## S0085 (Component view) — ranked
1. **There is a single authoritative state.** The Workflow Engine "Supplies authoritative state" to Communication [S0085 L26]. Communication is a derived reader, not a source.
2. The Workflow Engine holds "Grants, assignments, handoffs, **mutation ownership** and transitions" [L10]. Mutation ownership is a single-writer concept for state change.
3. PO authorizes and accepts **at the Workflow Engine** [L22], not at a person. The authority relation lands on a mechanism [I].
4. The Knowledge Manager holds "Evidence, lineage, document and knowledge lifecycle" [L11]. It feeds "Evidence / provenance" to the workflow [L27]. Knowledge informs decisions; it does not take them.
5. Verification "Reads evidence / writes findings" into the Knowledge Manager [L31]. Findings become knowledge. **There is no edge from Verification or Review to the Workflow Engine** [L30–L31], so how a finding affects acceptance is not modelled [I: gap].
6. The Review Engine "preserves role separation" [L13]. It has no persistence edge and no workflow edge [I: role separation is asserted, with no enforcing relation].
7. Drafting "Produces bounded proposals" and "Reads assignment / grant" [L14, L29]. Production is bounded by the grant.
8. The Registry holds "Role/component registration and platform capabilities" [L15]. The role vocabulary is configuration data.
9. The Composition Root "Builds the governed runtime" [L8] and appears only here.
10. The store is "Git / JSON / Markdown — Durable evidence and lifecycle record" [L19]. It is drawn outside the platform boundary.
11. Defects:
    - **C4 level error:** a component view should decompose one container, but this one decomposes the whole platform [L7] [I].
    - No component is named or bounded as a kernel [I].
    - 9 components and 15 relations, counted by reading.

## S0086 (Container view) — ranked
1. **The lifecycle command set:** "REGISTER, GRANT, HANDOFF, START, ACCEPT, COMPLETE, CANCEL" [S0086 L9]. It has seven verbs and no state machine and no ordering.
    - [I] Missing: REJECT, REVOKE (grant withdrawal), SUPERSEDE, RETRACT and FAIL. ACCEPT has no negative counterpart, so acceptance cannot be refused within the vocabulary.
    - [I] CANCEL is not REVOKE.
2. Knowledge Engineering means "Knowledge integrity, provenance, **supersession**, traceability" [L11]. Supersession is the only temporal-validity notion in the set. It is absent from S0085's Knowledge Manager [S0085 L11].
3. The Governance Engine is "Workflow records / policy" [L9], and PO authorizes and accepts there [L20].
4. **Grants are owned twice:**
    - The Governance Engine has GRANT [L9] and "Registers grants / assignments" [L22].
    - Session Orchestration holds "Assignment, grants, handoffs, mutation ownership" [L15].
    - [I: duplicate ownership, so whose invariant is mutation ownership?]
5. **Policy is in two places:** the Governance Engine "/ policy" [L9], and Session "Loads platform policy" from the Registry [L29] [I].
6. The estate is **inside** the system boundary and contains "source and tests" [L17]. This contradicts S0083, where the repository is external [S0083 L13].
7. The AI runtime disappears. Session Orchestration has technology "Agent runtime" [L15], which contradicts S0083 and S0085, where it is external [I].
8. A pipeline: "Evidence → bounded contexts → ADRs → domain models" [L12], and "Approved implementation design → code → tests" [L13]. These are DDD-specific application workflows inside the platform [I: application, not kernel].
9. Verification "Challenges" architecture and implementation and writes reports [L27–L28, L34]. There is **no edge to the Governance Engine**, the same gap as S0085.
10. Knowledge → Governance "Provides evidence / provenance" [L24], consistent with S0085 L27.
11. "Engineering Roles" collapses the five non-PO persons of S0083 [L6] (count consistent: 5).
12. 9 containers including the store, and 16 relations, counted by reading.

## Cross-source table
| Element | S0083 Context | S0086 Container | S0085 Component | Consistent? |
|---|---|---|---|---|
| Authority endpoint of PO "Authorizes / accepts" | Governance Engineer (person) L16 | Governance Engine L20 | Workflow Engine L22 | No: person vs engine; Governance Engine and Workflow Engine are possibly one thing under two names [I] |
| Grants / handoffs / mutation ownership | Governance Engineer "grants" L6 | Governance Engine L9 **and** Session Orchestration L15 | Workflow Engine L10 (Session Manager only registers/activates L9) | No: ownership moves and doubles |
| Communication | Person (role) L7 | Container L10 | Component, reader of authoritative state L16/L26 | Homonym: role vs capability |
| Repository / store | External system L13 | Inside the boundary, includes source and tests L17 | External ContainerDb L19 | No |
| AI runtime | External L14 | Absent; "Agent runtime" is Session's technology L15 | External L20 | No |
| Architecture / Implementation | Persons L8–L9 | Workbench containers L12–L13 | Absent (replaced by Drafting/Review) | No: decomposition not preserved |
| Supersession | — | Yes, L11 | No (lifecycle, lineage) L11 | Partial |
| Verification → acceptance link | — | None | None | Consistent gap [I] |
| Kernel named | No | No | No | Consistently absent [I] |
| Evidence-content rule (intermediate facts beyond the result) | — | — | — | Only S0084 L11/L21–L22 |

## Main points for step 2 [I]
- S0084 carries the transferable theory: non-identifiability of derived results; no back-inference; fact/rule/result layering; the language-neutral core with adapters; determinability as an attribute.
- S0083, S0085 and S0086 are a platform drawn without its kernel. Their theory content is narrower:
  - authority starts with a human and reaches agents only through grants;
  - one authoritative state, single-writer mutation ownership, a seven-verb lifecycle;
  - knowledge supplies evidence and provenance but does not decide.
- Their defects are part of the content:
  - grant and policy ownership are doubled;
  - the system boundary is inconsistent (repository, AI runtime);
  - verification never reaches acceptance;
  - the lifecycle has no negative or withdrawal verbs.
- All five are one line of support from a single commit, and they label themselves a proposal.

