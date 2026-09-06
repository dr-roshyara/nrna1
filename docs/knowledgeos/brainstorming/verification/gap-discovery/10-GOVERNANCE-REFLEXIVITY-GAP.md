# 10 — Policy · Authority · Governance, and the Reflexivity Gap

**Mandate §15.** *"Who/what authorizes a change to Policy? Do not automatically invent a
constitutional layer. Determine whether the requirement is mathematically necessary,
architecturally necessary, empirically observed, or normative."*

Executed evidence: `exec/exp_ontology.py`, plus direct inspection and execution of the running EKP.

---

## 1. The eight words, separated

| Word | Type in the corpus | Class |
|---|---|---|
| **Policy** | a set of rules governing admission, assessment and transition | `PROPOSED` — Step 253 §253.10 places it in the *constraint* layer, not the epistemic substrate; Step 266 §266.22 marks it class C (no formal policy language) |
| **Rule** | an element of a policy | `CORPUS ESTABLISHES` (relative to Policy) |
| **Invariant** | a property required to hold across all states | `CORPUS ESTABLISHES` (Step 209, Step 048) |
| **Decision** | a governed selection among admissible options | Step 253 §253.17 — candidate, unresolved |
| **Verdict** | *not defined anywhere* | `UNRESOLVED` |
| **Authority** | `Authority ⊆ Actor × Action × Context × Time`; `AuthorityState = (AS, B)` with `B` the recorded normative basis | `CORPUS ESTABLISHES` (Step 187) |
| **Authorization** | an *act* by an authority | conflated with Authority in most steps |
| **Governance** | the regime that maintains Policy and Authority | `PROPOSED` |

**GR-1 (`UNRESOLVED`, MEDIUM).** `Verdict` is used (Step 240, Step 266, the whole review programme)
and never typed. Same defect class as `Claim` (`05` CS-1).

**GR-2 (`DERIVED`, MEDIUM).** `Authority` (a standing relation) and `Authorization` (a dated act) are
distinct — Step 187 §187.1 says so explicitly (`Capability ≠ Permission ≠ Authority ≠
Responsibility`) — and are then used interchangeably from Step 190 onward. This matters because
`Authority` is time-indexed (`Auth(a,r,c,t)`) while an *act* is a point event that must be recorded.

---

## 2. The mandate's question, answered from the graph

`exec/exp_ontology.py` EXP-16 builds the definitional dependency graph (26 nodes, 42 edges) and runs
cycle detection:

```
strongly-connected components with >1 node: 1

CYCLE: {Assertion, Assessment, Authority, EpistemicStatus, Evidence, Policy, Rule}
witness: Assertion → Evidence → Rule → Policy → Authority → Assertion
```

**So the regress is real:** to know what an assertion is you need evidence; evidence needs a rule;
a rule belongs to a policy; changing a policy is an authorized act; an authority is a recorded
grant — which is an assertion.

### 2.1 The corpus's answer is a stipulation

Step 187 §§187.28–187.29 cuts the loop:

$$Authority \rightarrow GovernanceRule \rightarrow TransitionContract \rightarrow \text{KnowledgeOS enforcement}$$
$$\boxed{\text{Kernel enforces authority claims; Kernel does not originate authority.}}$$

**GR-3 — classification, which is the mandate's actual question:**

| Is the constitutional layer…? | Verdict | Why |
|---|---|---|
| **mathematically necessary** | **No** | Nothing in the formalism forces exogeneity. A self-referential authority model is consistent (fixed-point constructions for self-amending systems are standard); it is merely harder. |
| **architecturally necessary** | **Yes** | Without it the dependency graph is cyclic and no object in the cycle can be defined non-circularly. `03` ON-1. |
| **empirically observed** | **Partially — and the observation cuts against the stipulation.** See §3. |
| **normative** | **Yes, and this is the honest label** | Step 187 *decides* that authority is exogenous. It is a good decision. It is a decision. |

**Therefore: the constitutional layer is a `NORMATIVE DECISION` with an architectural justification,
not a mathematical result.** The corpus does not say this; it presents §187.29 as a finding. The
distinction matters because every theorem about `K` is then conditional on an **external authority
oracle whose behaviour the theory never specifies** — and Step 266 duly lists `Authority` as class C.

---

## 3. EMPIRICAL: does the running system honour the stipulation?

This is where the executed evidence changes the picture.

### 3.1 The Constitution governs itself — correctly

```
docs/knowledge/Knowledge-Constitution.md
  knowledge_id: KNOWLEDGE-CONSTITUTION
  status:    frozen           (statuses.yaml: requires_adr_to_change)
  authority: authoritative
```
and its closing line:

> *"Amendment process: changes to this constitution require an ADR (`knowledge_type: adr`) that
> `supersedes` the relevant section, reviewed by the Architecture Review Board."*

**GR-4 (`IMPLEMENTED`).** The EKP has a real, working answer to *"who may change the rules?"* at the
constitutional level: an ADR, superseding the section, ARB-reviewed, and the `frozen` status is
machine-enforced by `requires_adr_to_change`. This is better than the theory: the theory *stipulates*
exogeneity; the implementation *operationalizes* self-amendment.

### 3.2 But the vocabulary that defines every status and authority is ungoverned

```
docs/knowledge/schema/*.yaml  — knowledge_id count:

  authorities.yaml            0
  statuses.yaml               0
  bounded-contexts.yaml       0
  knowledge-relationships.yaml 0
  knowledge-types.yaml        0
  knowledge-schema.yaml       0
  knowledge-audiences.yaml    0
  documentation-placement.yaml 0
  governed-registers.yaml     0
  repository-migrations.yaml  0
```

**GR-5 (`EMPIRICALLY OBSERVED`, CRITICAL).** **All ten schema vocabulary files carry zero knowledge
cards.** They have no `knowledge_id`, no `status`, no `authority`, no `owner`, no reviewers, no
review date, and `knowledge-lint` does not validate them as documents.

`statuses.yaml` determines what `frozen` *means*. `authorities.yaml` determines what `authoritative`
*means*. **An edit to either changes the meaning of every governed document in the system, and is
subject to no ADR, no review, no owner and no lint gate.**

This is the reflexivity gap, empirically:

> The system governs its documents. It governs its constitution. **It does not govern the
> vocabulary that its constitution and its documents are written in.**

And it is the *same shape* as the theoretical move in §2: the theory places authority outside the
model; the implementation places the authority vocabulary outside its governance. Both terminate the
regress by exiting the system, and both leave the exit unprotected.

### 3.3 The deepest assurance check has never once succeeded

`knowledge-lint --profile=structural --root=docs/knowledge` (executed by this session):

```
S1 identifier uniqueness     PASS   2 · FAIL  0 · INCONCLUSIVE 130
S2 intra-doc references      PASS   0 · FAIL 20 · INCONCLUSIVE 112
S3 declared vocabulary       PASS   0 · FAIL  0 · INCONCLUSIVE 132
S4 table shape               PASS  88 · FAIL  0 · INCONCLUSIVE  44
S5 competing-definition rule PASS   0 · FAIL  0 · INCONCLUSIVE 132

exit code 0
```

**S3 — the *declared vocabulary* check, i.e. the very check that would police vocabulary drift — is
132/132 INCONCLUSIVE**, for one reason:

```
vocabulary config not readable at docs/knowledge/schema/vocabulary-integrity.yaml
```

**The file does not exist.** The check has never run successfully.

**GR-6 (`EXECUTED`, HIGH).** Two things are true at once and both should be said:

- The design is **exemplary**: it is fail-closed by construction — *"Absence of evidence is not
  PASS"* — and it says so in the source (`D-2: fail-closed; S3 without a readable vocabulary config
  is INCONCLUSIVE, never PASS`). Most systems would have returned green.
- The mechanism is nonetheless **inert**: warn-only, wired into no gate or hook (`D-3`, stated in the
  code), exit 0, and its central slice unconfigured.

**GR-7 (`EXECUTED`, LOW).** The 20 S2 failures are **all** in `docs/knowledge/archive/` — retired
material, not the working set. Reported for completeness; not a live-system defect.

---

## 4. What Policy would need to be computable

Step 266 §266.22 calls policy "a major computability boundary" and Step 270 (2026-08-30 20:28) opens
an adversarial audit of exactly this, correctly warning that
`IndependenceFactor = 1/(1+depth)` "may be an excellent engineering heuristic, but it is not
automatically a statistical measure of independence" — a warning `07-MEASUREMENT-THEORY-GAP.md` MT-4
confirms by execution.

From the dependency graph, the minimum for a computable policy layer is:

1. a **formal policy language** with decidable evaluation (absent);
2. an **authority oracle** with a specified interface — the theory need not compute authority, but it
   must specify what an authority act *looks like* (partially present: Step 187's 4-place relation);
3. **`Context` typed** (`03` ON-4 — absent, and `C` is a parameter of both `Evidence` and policy);
4. `Relevant` given a decision procedure, or explicitly relocated across the judgement boundary
   (`08` EG-2).

Step 266 §266.31's own proposal — declare a `ComputableCore` and a `JudgementBoundary` and make the
line **formally visible** — is the right architecture, and this session endorses it. It is
`PROPOSED`, not built.

---

## 5. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **GR-1** | `Verdict` is used throughout and never typed. | `UNRESOLVED` | MEDIUM |
| **GR-2** | `Authority` (standing relation) and `Authorization` (dated act) are distinguished in Step 187 §187.1 and used interchangeably thereafter. | `DERIVED` | MEDIUM |
| **GR-3** | The constitutional layer is **architecturally necessary and normatively decided**, not mathematically necessary. Step 187 presents a decision as a finding. Every theorem about `K` is conditional on an unspecified external authority oracle. | `DERIVED` | **CRITICAL** |
| **GR-4** | *Positive:* the EKP **does** answer "who may change the rules" for its constitution — ADR + supersession + ARB review, with `frozen`/`requires_adr_to_change` machine-enforced. The implementation is ahead of the theory here. | `IMPLEMENTED` | — |
| **GR-5** | **All ten schema vocabulary files are ungoverned** — zero knowledge cards, no owner, no review, no lint. Editing `statuses.yaml` silently changes the meaning of every governed document. The reflexivity gap, empirically. | `EMPIRICALLY OBSERVED` | **CRITICAL** |
| **GR-6** | The structural profile's vocabulary-integrity slice (S3) is 132/132 INCONCLUSIVE because `schema/vocabulary-integrity.yaml` does not exist; the profile is warn-only and exits 0. Fail-closed design, inert mechanism. | `EXECUTED` | HIGH |
| **GR-7** | The 20 S2 structural failures are all in `archive/`. | `EXECUTED` | LOW |

---

**Next:** `11-PROVENANCE-LINEAGE-HISTORY.md`.
