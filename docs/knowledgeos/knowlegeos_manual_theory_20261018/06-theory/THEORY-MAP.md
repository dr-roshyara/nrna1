# Theory map: KnowledgeOS theory as it stands

> **Purpose first:** read `06-theory/RESEARCH-PURPOSE.md`. The theory is the theory of the **KnowledgeOS kernel**: its primitives, the laws between them, and the boundary between kernel and application. Central question: which knowledge is valid, who owns it, and how can it be trusted?

*Read before every source; updated after every source. It replaces the goal file as the working picture. Built 2026-10-19 from Chapters 0–5 and 8, after S0027. Labels: **Ax** axiom or stipulation · **Res** derived result · **Src** stated by the corpus · **Open** unresolved. `[CON-nnnn ✓/✗]` = validated / invalid.*

## 0. The problem (Ch. 0 §0.1)

- **Core property (Int):** knowledge must be **valid at the moment it is used**, by a person or an AI, and the user must be able to **see** whether it is.
- Loss, drift, staleness and "model amnesia" (re-deriving what is already known) are failures of this one property.
- Status: the problem is well evidenced. That KnowledgeOS solves it is a hypothesis; **nothing has been validated in use.**

## 1. What exists (Ch. 0 §0.3, Ch. 3)

- **Claim:** the unit of assertion. It is a unit of granularity, not a supertype (§1.15).
- **Dimensions:** a claim sits in a space of dimensions: nature · representation `[CON-0002 ✓]` · governance status `[CON-0003 ✗]` (mixes lifecycle and standing) · canon scope · evidence status · operational state `[CON-0006 ✗]`.
  - **Constraint set S:** tacit knowledge carries no status or scope; skill is tacit only.
  - **Res:** kind must not be one of the nature values, or the element formula counts nature twice (MM-1).
- **Knowledge ≠ artifact** (I-1) `[CON-0010 ✗]`.
  - **Res:** the deletion test shows that knowledge and artifact are **distinct**, not that knowledge **survives** deletion. A single carrier means no survival.
- **Archetypes** (nine candidate kinds) and **three ontology layers**: documentation, platform (absent), product (partial).
- **Relations per type, not one hierarchy** `[CON-0064 ✓]`: authority · production · realization · containment (plus references, which is cyclic, and constrains).
  - **Res:** a relation's reachability is a partial order exactly when its edges have no cycle; reachability ≠ the relation, for non-transitive relations.
  - **Res:** a typed union loses nothing; an unlabelled closure loses the type; a total extension adds false order. Impact questions need union reachability.
- **What executes is undecided: fork F11** (three senses) `[CON-0066 ✗]`. "Knowledge does not execute" stays circular (SEQ-0012 not reduced).

## 2. Authority (Ch. 1, Ch. 2 §2.8)

- **Ax A1 (§1.6):** only a **recorded act** by an authorized human changes standing, within the grant's **scope** and **period**: authority = act × scope × time.
- **Provenance ≠ standing (§1.2):** provenance is an immutable fact; standing is a judgment changed only by an act. Evidence grade ≠ standing (§1.3).
- **A record is not the act (§1.12).** The residual trust assumption is stated, not hidden.
- **authorized(x)** is defined recursively over grants, ending at root acts. It is **three-valued**: T / F / U ("cannot determine"). Not found ≠ unauthorized (§1.14.1, §1.17.3).
- **Identity must be stable under every permitted move** (S0031, "Paths change; ids never do"); location-free ids are sufficient, not necessary. Words are identifiers **relative to a context**: a collision is a missing qualifier; overlapping closed value sets need a scoping rule instead (Ch. 1 §1.10).
- **Words are identifiers too:** "baseline", "constitution", "capability", "platform" and "governance" each carry 3–5 meanings (S0034). The remedy is qualification, not renaming.
- **Mechanisms record, never grant** (ARCH-INV-1).
  - **P-8:** being executable, searchable, indexed or used by AI confers no authority.
  - **(P-E, conditional):** no mechanism may produce the normative effect of an act without an authorizing act (§1.14.5).
- **Visibility:** vis(derived) ⊆ ∩ vis(sources) (§1.7). Nothing yet governs **who may read** (§1.17.5).
- **Time:** an act is valid at the time it is made; meaning-bearing references must pin a version; a result is relative to its inputs (§1.16).
- **Res (§1.16.7):** a global invariant can be **prevented** only through a single serialization point. Otherwise it can only be **detected**.
- **Roles:** humans decide, governance records, architecture recommends. Independence here is **process** independence, not personal (RC-30, RC-65a).

## 3. Rules and conflict (Ch. 2)

- **Rule** = subject + applicability predicate + normative effect + scope + validity + authority + exceptions (RS adds binding strength, evidence, supersession).
- **Conflict:** some state exists where both rules apply and their effects cannot both be met. The output is the **region** where this holds.
  - Most apparent contradictions are not conflicts.
  - Priority numbers are rejected; precedence is allowed only among alternatives competing for one output.
- **Res (sound partitioning):** a rule's partition key must contain the projection of its region: key(r) ⊇ π(Region(r)).
- **AI proposes · analysis proves · humans resolve.** A proven result carries no confidence score.
- **Scope** means three things: subject, grant, evaluation. Containment: exception ⊆ subject scope ⊆ grant scope.
  - **SC-2:** unspecified ≠ all. Applicability is three-valued (Kleene), plus "not applicable".
  - The **kinds of nothing** are E1–E8, plus two provisional candidates E9 (invalid evidence) and E10 (unknown dimension).
- **Binding strength ≠ enforcement.** Rules with meaning and rules with enforcement are different sets, and here they sit in different components (§2.10, qualified).
- **Exception** = a separately authorized deviation within a scope and validity period.
  - **Res:** effective = adopted ∧ t ∈ validity, so a deferred rule cannot bind `[CON-0063 ✓]`.
- **Constitutional override** `[CON-0040 ✗]` (partly valid): a conflicting lower rule is "void to the extent of the conflict", established for one constitution (Round39-MC) and its artifacts. **Int:** voidness bounded by the conflict region; a wider reading exists. Open: timing; who declares; artifacts without rule form.

## 4. How knowledge changes (Ch. 0 §0.4, Ch. 1 §1.6, Ch. 4)

- **Three moves:** derivation (new claim plus an edge to its source) · representation transition · standing transition by act. Supersession is a link, never an edit.
- **Two engines:** governance acts (granted) and evidence (earned). No act decrees evidence; evidence alone does not effect an act (RC-37a).
- **From observation to pattern:**
  - an evidence entry separates fact, interpretation and authority `[CON-0068 ✓]`;
  - patterns are derived from entries, never written into them `[CON-0069 ✓]`;
  - **a count needs its class fixed in advance** `[CON-0070 ✓]`;
  - **evidenced ≠ measured** (no baseline; the rival explanation is instruction-following);
  - recurrence must be **independent**: no shared actor, instructions, reviewer or work item. The corpus's own counts (R-6 n=3) fail this; the proposing instance must not count.
- **Monitored compliance = scope ∩ coverage** `[CON-0071 ✓, definitional part only]`; that an obligation *shrinks* to it is an n = 1 hypothesis.
- **The loop back** (evidence → platform change) is thin: about 3 informal passes; the formal count depends on how "formal" is defined.

## 4a. Boundaries: platform and product (Ch. 6)

- **Method · binding · evidence** `[CON-0073 ✗]` (not exhaustive: no ambiguous class).
- **The binding is a parameter** `[CON-0072 ✓]`: an artifact written inside one product is *M*(β_PD).
- **Res:** from one product the split (*M*, β) is not identifiable **unless the interface is declared**. A second product helps only if its binding differs. This grounds condition (3) of the component rule `[CON-0076 ✓]`.
- SD-1 admits several rule/binding splits; choosing one is part of the ARB's question (F10).
- **Res: portability is a lattice** {0,1}² (binding-free × case-law-free) plus "not domain-free" `[CON-0075 ✓]`, not a four-tier ladder `[CON-0074 ✗]`.
- **Kernel admission:** tier 1 is necessary, not sufficient `[CON-0057 ✗]`. Refuted as necessary by §6.1 C4; "kernel" there is the portable engineering kit (F14).
- If case law lives in product spaces, the platform's evidence lies across a boundary; but the corpus keeps evidence in **three or more** homes, so its placement rule is not followed (T6-02, T6-03).
- The four orders of §3.8 are **not exhaustive**: "guides" and "hosts" lie outside them.
- **Context type vs instances** `[CON-0086 ✗]` (partly valid; the source weakens it to one observed instance): the open deployment question becomes "what is the instance key?", possibly hierarchical. **Five readings** of a knowledge space on one day (domain · type · generated artifact · authored · projection); S0035's, citing a ruling, has most standing (§6.3 C7).
- **Two reuse tests** `[CON-0088 ✗]` (partly valid): "does the responsibility exist without this product?" ⊋ "can the artifact be adopted unchanged?"; the gap is binding, case law and the research clause; the first test is counterfactual, the second operational.
- **Core/supporting is relative to a product** `[CON-0089 ✓]`; rules attached to it do not transfer unchanged. The reference product is a different index from the time horizon.
- **A boundary can run through a concept** `[CON-0087 ✓]`: evidence = platform protocol + product records; derived patterns are a third kind.
- **The four-layer runtime model is not instantiated:** no mapping artifact exists; the mapping is **nearly** the identity. Redundant at n=1, refutation deferred to a second runtime. "Adapter" has two senses in S0031 (the layer; the configuration file) `[CON-0084 ✗]`.
- **Admitting a bounded context** (§6.2) `[CON-0085 ✗]` (partly valid): the nine criteria are a **necessary** condition ("only if"), with **no sufficiency condition**, so admission is underdetermined. Evidence: on one day, S0033 and S0034 give opposite verdicts on runtime, evidence and governance. The criteria are software-boundary criteria, applied to knowledge without asking whether they transfer.

## 5. Decisions (Ch. 5)

- The platform **prepares**; humans **decide**. Decision-readiness is `[CON-0012 ✗]`: there is no governing record for it.
- **Decision package** `[CON-0048 ✓]` · **gating order** `[CON-0049 ✓]`.
- **Presupposing evidence:** evidence that assumes the answer does not count, unless it is robust under every candidate answer `[CON-0050 ✗, qualified]`.
- **Gap kinds by closure** (modelling · ruling · use) `[CON-0053 ✗]`: the classification is inconsistent across sources.
- **Deciding in advance** `[CON-0077 ✓ as a design]`: a pre-specified decision rule, the decision-side twin of the fixed counting class (CON-0070). The register's own record falls short (same-day revisions).
- Gates by risk `[CON-0078 ✗]` (families do not cover the gates used) · three **sequential** questions, not orthogonal `[CON-0079 ✗]`.
- **Res (scope of the n ≥ 2 rule):** it governs claims of **reuse across contexts**; local automation in its own context makes none. This reconciles S0024/S0025 with S0029.
- Activation in the register came from acts and incidents; **no** criterion fired independently. Incidents are a third kind (T5-07).

## 5a. Gaps: what a decision lacks (Ch. 5 §5.4, from BRANCH-01; reviewed, provisional)

- "Zero" names three concepts: neutral origin · absence lens · **gap operator** `[CON-0080 provisional]`.
- The gap operator types each requirement relative to a contract (at least 11 statuses, including Failed and Missing, plus criticality). It detects; it does not plan.
- **Res:** the decision needs (status, criticality) pairs; a summary that drops criticality cannot decide (a count of unsatisfied **blocking** requirements can).
- The status set mixes kinds `[CON-0081 ✗]`. "Missing" asserts absence (E1) where only "not found" (E3) may be known.
- Its Unknown merges E2–E5, against Ch. 2's rule; it must be split before validation.
- Kinds of nothing come from the **absence lens**, not the operator: candidates **E9** `[CON-0082 provisional]` and **E10** `[CON-0083 provisional]`. E10 lies outside the operator by design.
- Ch. 0 Q3 is partly answered. **"Unsatisfiable" remains unformalized.**

## 6. Validation (Ch. 8)

- Falsification by projection into other products `[CON-0047 ✓]` · the reach register `[CON-0058 ✓]` · scaffolding contamination `[CON-0060 ✓]` · domain-free ≠ portable `[CON-0059 ✓]`.
- **Res:** one failure refutes a sufficiency claim, but does not license new structure.
- A single product **masks** failures, such as deployment and external authority.

## 7. Open parallel lines (forks)

| # | Fork | Kind | Where |
|---|---|---|---|
| F1 | Mission: PublicDigit-specific vs product-neutral. **Dissolved for the vision only** (S0032): vision (later) vs Product Primacy (now) conflict only without a stated horizon. **Open for the mission:** S0032 puts the enacted mission in the present ("already behaving according to it"), so it still conflicts with Product Primacy now. S0019 also offers a rival reading (portability as a design quality, "why vs what"). The falsifier tests of S0019 and S0032 are in tension, not contradiction (S0032's test is conjunctive: falsifier and market validation) | normative (sponsor) | Ch. 0 §0.2 |
| F2 | External authority: inside the act model (recognition act; grant citing an external act) vs outside it | factual / definitional | Ch. 1 §1.11, T1-15 |
| F3 | Genesis: architectural gap vs governance gap | factual | Ch. 1, T1-17 |
| F4 | Does the record **constitute** authority or **represent** it? (C1-a / C1-b) | normative (reserved) | Ch. 1 §1.13.1 |
| F5 | I-2 "reasoned": a validity condition or a quality obligation? | normative (reserved) | Ch. 1 §1.17.2 |
| F6 | Governance status: one dimension, or standing and lifecycle split apart (PM-1)? | definitional | Ch. 3 §3.1 |
| F7 | Kind: its own axis or a nature value (MM-1)? | definitional | Ch. 0 §0.3 |
| F8 | Knowledge spaces: nested or adjacent (H-3)? **Open, narrowed by review (T2, §3.10).**<br>• **Two questions:** F8-type (may the model have space-in-space relations, with what structure?) and F8-instance (per pair, per reading of the container sense S1 `[CON-0098 provisional]`, per criterion `[CON-0097 provisional]`).<br>• **Per pair, under membership,** the either/or has three cases: nested, disjoint or overlapping (C3, validated in scope). Laminar families form forests, but membership containment is only a preorder (C4).<br>• **EKP vs PKS** (conditional, §3.10 C6): not nested by membership, not nested by authority for domain knowledge, possibly nested by type.<br>• **Product vs deployment:** open | factual; open | Ch. 3 §3.3, §3.8, §3.10 |
| F9 | Is authority part of the knowledge theory, or the governance around it? | definitional (largest) | Ch. 0 §0.6, §0.8 |
| F11 | What "execute" means. **Open.** Trial T1 (§3.9) mapped the uses: two activities, **run** and **act** (dispatch and perform, reads included), each **specified** or **performed**, plus three further uses. **Per source:** S0043 uses "capability" for things among scripts (A's reading); S0166 and S1052 support C and, for service capabilities, A; S0889 supports B in the transition sense. **Proposal, unreviewed:** a governed capability is not an artifact `[CON-0095 provisional]`, under which A fails for governed capabilities. Executable ≠ executed `[CON-0093 ✓]`; running ≠ enforcing, as non-sufficiency `[CON-0092 ✓]` | definitional; open until a reviewer verdict | Ch. 3 §3.8, §3.9; Ch. 1 §1.5 |
| F12 | Can a mechanism change standing? A runtime ALLOW (S1052) or an epistemic commitment gate (S0889) either **applies a prior grant** (consistent with A1) or **changes standing by itself** (contradicting A1). The sources do not choose | factual / definitional | Ch. 3 §3.9 C5a; Ch. 1 A1, ARCH-INV-1; F9 |
| F13 | **The kernel's attribute set (state space 𝒮).** Ch. 1's labels (provenance, evidential grade, standing, visibility, owner, identity I-0) and Ch. 3's six dimensions are two attribute systems never mapped onto each other; evidence status has 3 values in §3.1 and 4 in §1.3. Which attributes, with which value sets, and which are dimensions, components or derived? | definitional | Ch. 1 §1.3, §1.7, §1.10, §1.11; Ch. 3 §3.1; RESEARCH-PURPOSE deliverables 1, 5 (consolidation review, finding 1) |
| F14 | **"Kernel" is a homonym:** the portable engineering kit (S0024 Tier-1, S0002 A-5, S0008 "Engineering Kernel") vs the knowledge kernel of RESEARCH-PURPOSE. Answered by RESEARCH-PURPOSE for this book; to be marked wherever the corpus uses it | definitional | Ch. 6 §6.1 C4; Ch. 3 §3.6 |
| F13a | **The attribution layer of an act** (a sub-fork of F13): executor · authorizer · recorder as separate attributes of an act record, and an attributed epistemic state A_t beside the epistemic state E_t and the factive Knows(a, p, c, t) (S2834 L2051–2071) | definitional | Ch. 5 §5.5 C1; Ch. 1 §1.12, §1.14.3 (review C8, findings 1, 13) |
| F18 | **Pinned or resolved at use?** Evidence needs pinned references (§1.16.6); operating rules need resolution at use. A pin used for an operating rule taught a session a superseded path (S0048) | definitional | Ch. 9 §9.5 C1 (review C9) |
| F19 | **Uniqueness or aggregation?** Two ways to make an outcome definite: at most one established assessment (needs serialization), or a deterministic aggregate over all of them (does not) | definitional | Ch. 9 §9.4 C2 (review C9) |
| F20 | **May an act that changes evidence only be performed by its producer?** The assessor establishes its own assessment (S0050 R-6) against producer ≠ verifier | definitional | Ch. 9 §9.4 C5 (review C9) |
| F21 | **The interpretive act:** does an interpretation of an approved text, which adds a constraint the text does not state, change what is in force, and must it be recorded as an amendment? | definitional | Ch. 5 §5.6 C1a (review C10) |
| F22 | **Plane or context:** is the authority-record machinery (assignments, grants, handoffs, STARTs, transitions) a cross-cutting plane under every context (ARB ruling, §1.14.3) or one application context, BC-7 (ADR-AIP-03, S0063, S0065)? A plane is a kernel candidate; a context is an application | definitional / boundary | Ch. 6 §6.5 C0 (review C11/T3, critical finding); **prepared in theory turn 1, Ch. 9 §9.6, revised after review TT1:** decided by the relocation test (does the kernel serve several independent applications? K3) together with whether the record must keep a uniqueness across them (K4), given R_s ∈ Req; the evaluator CE is the stronger kernel candidate; options (C) delegated / (P) plane; for the Principal. **Narrowed by the Principal's act of 2026-10-19: K3 = several independent applications; R_s ∈ Req. Open: K4 (uniqueness across applications), a research question.** Consequences (Ded, §9.6.6): CR-a and the evaluator CE are necessary; CE is delegable only as a kernel-defined function over a shared record; whether the kernel must also provide serialization points depends on K4 |
| F23 | **Trust as a primitive?** The central question asks how knowledge can be trusted, and the book has no trust concept. S0913 proposes Trust(a, d, c, t), contextual; trust ≠ authority ≠ expertise; trust modifies epistemic weight and creates no truth; trust assessments are themselves evidence-bearing knowledge. Is trust a primitive, derivable (evidence × provenance × calibration), or outside the kernel? | primitive | consolidation S0036–S0065 (both reviewers); S0913 L133, L188–198, L1398, L1426–1428; **theory turn 4 (§9.9):** none of three senses is a primitive in §12b's sense: trust in a source and in a knowledge item reduce under stated premises (F34 for the root); the occurrence part of trust in the record is a candidate axiom |
| F24 | **Conflicting claims: preserve or supersede?** S0913 makes conflict a first-class state with "Unresolved" as a valid terminal state; S0129's R-CONFLICT forbids silently choosing a side; but §9.4 C3 settles a content dispute by one current claim plus supersession, i.e. a winner. Which does the kernel require, and where? | law / primitive | consolidation S0036–S0065 (both reviewers); S0913 L450, L1148–1161; S0129 L45, L102; §9.4 C3 |
| F25 | **Who may correct (restated after review C13):** may a process that verified an object then produce on it in another lane (role vs process), and is a permitting grant sufficient? Both S0056 and S0074 keep the forward rule (a corrector may not verify its own correction); they differ on a grant (none vs "bars no one") and on lane | law | Ch. 8 §8.8 C3 (window C13) |
| F26 | **Order of acts (theory turn 2; scoped after review C15):** where act times are absent, tied or untrusted, does the evaluator CE work on (a) one global total order of record positions (a uniqueness, one serialization point, K4 = yes for positions), (b) a partial order answering U where acts are incomparable (S2775 L1650–1688, in meaning), or (c) act-time order with a tie rule needing no serialization point? §1.14.1 orders by act time first | architecture | Ch. 9 §9.7 Analysis 2 step 2; §9.6.6a items 2–4 |
| F27 | **The record view ρ (theory turn 2):** an organisational policy (§1.14.1 safeguard 3) or a parameter of each query (S2775's three queries)? Reserved decision 2 stays the Principal's | normative | Ch. 9 §9.7 Analysis 2 step 3 |
| F28 | **A synthesis adopted as truth (window C14; candidate):** can a recorded prose synthesis hold standing as "the authoritative state" beside the record (S0080 L8–10), against "the record outranks prose" (S0078 L163)? Compatible reading: standing for a dated classification only | normative | Ch. 5 §5.7 C7 |
| F29 | **State history vs act history (theory turn 3):** must the kernel answer act history ("what happened, who authorized it", S0129 L47) as well as state history, or does act history belong to an application that reads the record? | architecture | Ch. 9 §9.8.8 |
| F30 | **H1′ vs absolute append-only (theory turn 3):** may a kernel record lose entries by a recorded act (retention, law; S2782 L1873–1905), or never? | normative | Ch. 9 §9.8.8 |
| F31 | **The regress of θ(u) (theory turn 3):** which meta-evaluator or root fixes the policy in force for policy items? | architecture | Ch. 9 §9.8.8 |
| F32 | **Eligibility of a verifier (window C16):** relative to an identifier, to §8.6's factor set, or to the relation to the object (TODO T1-19)? Under identifiers the pool never empties; under factors it may be empty from the outset | method | Ch. 8 §8.9 C2 |
| F33 | **Liveness of the workflow record (window C17):** must the kernel guarantee that no workflow state can be left only by a fact from outside the record (S0095 L13, L193: mutationOwner None, Inv C with G-3)? | architecture | Ch. 8 §8.10 C10 |
| F34 | **The root of source assessments (theory turn 4):** source trust (T-a) needs adjudicated outcomes; if adjudicators are weighted by their own trust, the regress ends only at a designated evaluator, a prior set by an act, or observation that uses no trust. Which? (beside F31) | architecture | Ch. 9 §9.9.3 |
| F15 | **What Sat refers to:** satisfied **in the world**, or satisfied **by what the system believes**? It decides whether a larger gap can be progress under a fixed contract (S2377 L1745 vs L2171–2256) | definitional | Ch. 5 §5.4 C8 (review C7, finding 7) |
| F16 | **Granularity of mechanisms:** "minimal kernel" depends on the pool the interface A offers; one coarse mechanism can be a minimal kernel | definitional | Ch. 9 §9.1 C4–C5; T9-03 (review C7, finding 2) |
| F17 | **What a mechanism is:** a transition (closed reading) or also a guard on others' transitions; existence (§9.1 C3) holds only under the first | definitional | Ch. 9 §9.1 C1, C3; EXT-011 L230 (review C7, finding 3) |
| F10 | SD-1's rule part ("consume only a certified release"): platform rule or not? (the either/or form of OQ-S1 is dissolved, §6.1 C3) | normative (ARB) | Ch. 6 §6.1 |

**Trial T1 topic (2026-10-19): F11, what "execute" means.** Chosen because its answer decides more of the theory than any other open factual or definitional fork: whether I-11 ("knowledge does not execute") is more than a stipulation (CON-0039, CON-0066), how capability, runtime asset, port and adapter relate (Ch. 1 §1.5, Ch. 3 §3.8, CON-0084), and so the realization order of §3.8 and F3 (genesis).
- **Search** (allowed list only; 6 scan rows logged): "knowledge … execute" / "capabilities execute" 4 files; "runtime asset" 5; port ↔ adapter 24; essence, archetype, executable knowledge 62; invoke, executor, runnable 161; execute and its forms 1,404 (too broad to select by).
- **The five sources** are the earliest **unread** relevant one, the latest, and three between. Sources already read in order (S0008, S0021, S0022, S0026, S0029) are in the book, so the trial tests what new sources add.
  - S0043 (08-02): "knowledge is an executable engineering asset", which challenges I-11 directly.
  - S0166 (08-19): ports, adapters and a composition root (sense C).
  - S0889 (08-27): an executable model of the knowledge state (knowledge as computation).
  - S1052 (08-28): runtime architecture, who executes and who enforces.
  - S1524 (08-30, the latest): verification finding claimed execution that never took place (execution as evidence).

**Trial T2 topic (2026-10-19): F8, knowledge spaces nested or adjacent.** Chosen by the Principal; it is the open factual fork.
- **Search** (allowed list; 6 scan rows logged): F8 phrases 28 files · nesting / subspace 16 · adjacency 18 · H-3 5 · PKS containment 16 · deployment as a space 1. Union 70 files: 10 already read, 60 unread (corrected; the first count of 59 was wrong).
- **Fixed rule:** order the unread hits by the allowed list's date (normalized; Unix timestamps converted); take ranks 0, 14, 29, 44, 58 of 59: **S0577** (08-01), **S0357** (08-23), **S0651** (08-25), **S2221** (08-31), **S2838** (09-07). No judgment was used.
- **Already read and used as well:** S0005, S0008, S0009, S0012, S0013, S0021, S0022, S0031, S0033, S0034, S0035.
- **Rule-application error, found during settlement:** S0113 was counted as read, but it was read only before the RESTART. With S0113 unread, the pool is 60, and the rule picks S2041, not S2221, as its fourth source. Recorded; not repaired (the Principal decides).

**Group C7, S0036–S0040 (read in order, 2026-10-19), and the kernel section:**
- **Kernel:** Ch. 9 opened. §9.1: preservation alone admits the trivial kernel `[CON-0099 ✓, closed reading]`; with required capabilities, ∅, refuse and noop fail; existence is decided by one check; which kernels are minimal depends on the pool (one toy model; form only) `[CON-0100 · provisional]`. *An earlier claim of non-uniqueness came from a duplicated mechanism and was withdrawn (review C7).* §9.2: the corpus's own statement that portability of parts is not sufficiency of the whole (S0039) `[CON-0105 ✓, as a non-implication]`.
- **Gap theory:** S2377 is the second corpus line (binary Sat + failure classes + weights; gap direction; contract compatibility; a larger gap can be progress) `[CON-0101 · provisional]`; same line as S0896, dependence not shown.
- **Boundaries and ownership (§6.4):** an admission threshold that is stipulated, not derived; "no single owner" observed by absence; stated ≠ held ≠ enforced invariants `[CON-0102]`; capability identity `[CON-0103]`; knowledge flow against authority flow `[CON-0104]`; enforceability needs a decidable verdict set `[CON-0108]`.
- **Validation (§8.2):** the rediscovery series and its refuted "sole cause"; duplication classified by standing × recorded supersession `[CON-0106 ✗ as three kinds]`.
- **Change (§4.2):** the improvement cycle, each stage withholding what the next adds; IGNORED is closure, not a gap `[CON-0107]`.
- **Not yet in the book (review C7 coverage):** S0036's discovery stopping rule ("when is discovery done?", L55) and uncertainty carriage (L57), for Ch. 5; S0037 reads ownership from a frontmatter `owner:` field (L77), a metadata value taken as semantic ownership, for Ch. 1 §1.11; S0039 AR-7, without the evidence-to-platform transition "earns its abstractions" is unfalsifiable (L281); S2377's backbone order K → EC (L2486) against Knower → EC (L2713), for ContractDerivation.
- **New open questions:** what justifies an admission threshold; who guarantees each invariant; whether "no single owner" is a defect; whether the improvement cycle has produced any assessment; whether the litmus is a usable first filter for kernel candidates.

**Window C8, S0041–S0045 + S0129, S2834 (§12a; reviewed and settled 2026-10-19):**
- **Who executed, who authorized (§5.5):** RD-6 is the first observed instance of Ch. 1's residual trust assumption (§1.12); executor ≠ authorizer `[CON-0109 ✗ as three roles]`; a self-referencing record attests nothing, given that the recorder holds no standing; the mechanism guarantees the reference's presence, not its externality.
- **Say no, not yet learn (§9.3):** in S0045 Run 1, governance observed only as preventive at canon level `[CON-0110 ✗ as "only"]`; an analogy to §9.1 with four limits; capabilities are not kernel operators `[CON-0111 ✓, restricted]`.
- **Does it govern (§8.3):** cases vs counterfactual or comparative vs not observed `[CON-0112 ✗ as five parts]`; usage ≠ outcome; two levels of feedback.
- **Recommendation lifecycle (§4.3):** vocabulary graded by exercise `[CON-0113 ✗ as "only when used"]`; a derived attribute (time-to-decision); the first decision tests §4.2's stage rule.
- **Not yet in the book:** Unknown ≠ Uncertain (S2834 L864–878); S2834's two status ladders disagree (L1249–1261 vs L1447–1465); S0129's R-CONFLICT and Execution Context ≠ Governance Evidence Context (L45, L194–197).

**Window C9, S0046–S0050 + S0091, S0113 (§12a; reviewed and settled 2026-10-19):**
- **Invariants (§9.4):** a totally derived read rule cannot justify a boundary `[CON-0114 ✗ as "vacuous"]`; uniqueness as an instance of §1.16.1/§1.16.7, on a stated premise; one current claim per (act, dimension), no owner `[CON-0115 ~]`; gate inputs declared closed `[CON-0116 ✗ as "closes"]`.
- **Distribution (§9.5):** the failure case of context assembly (§0.7) via a pinned reference (§1.16.6) `[CON-0117 ~]`; a two-valued response cannot fire on an undecidable condition `[CON-0118 ✗ as "inert"]`; the INV-A3 tension is answered by §1.14.5.
- **Claims (§8.4):** novelty and demonstration as two axes `[CON-0119 ✗ as three classes]`; an absence claim needs its search scope `[CON-0120 ✓]`, and the book's own search broke it once.
- **To map:** S0113's five knowledge conditions onto E1–E10; "current" in three senses; "Missing" in three senses.

**Window C10, S0051–S0055 (reviewed and settled 2026-10-19):**
- **Verification (§8.5):** a fixed object; replication by a different method, beside reproduction `[CON-0121 ✓, one line]`; a wrong high grade damages reliance on that grade `[CON-0122 ✗ as an estimated rate]`; materiality relative to reliance; a supersession must be named where readers look.
- **Acts (§5.6):** the object of an act may include its argument (refines §1.17.2) `[CON-0123 ~]`; the interpretive act (F21); an acceptance covers the delivered, pinned version `[CON-0124 ✗ as a law of acts]`.
- **Added to earlier sections after review:** §5.5 C2, S0055's code-verified engine gaps; §9.4 C7, the assurance model's trivial case.
- **Not yet in the book:** resolver against fold, two views of one state with a precedence rule (S0054 L91); no boundary inferred from a name; an evidence hierarchy whose disagreements are recorded, not settled (S0055 L80–83); a count is evidence only with its method (S0055 L49).

**Window C11 = Trial T3, S0056–S0065 + S0164, S0913, S0067, S0071 (reviewed and settled 2026-10-19):**
- **Independence of a check (§8.6):** seven candidate factors (role/process, process lineage, method, model, information lineage, working context, scope authorship) `[CON-0125 ~]`; differential testing reports only the symmetric difference of blind sets, for common-mode failures `[CON-0126 ✓]`; independence on the record can be refuted, not established, given a truthful record `[CON-0127 ~]`; corroboration counts independent information; the lineage rule is a heuristic `[CON-0128 ~]`; the corpus's alternative, REPRODUCIBLE instead of INDEPENDENT (S0164 L2420–2424).
- **Correction chains (§8.7):** no superseded value asserted as current; moved, not renumbered, as a sufficient condition `[CON-0129 ✓]`; a stopping rule under a branching model, premise unmet `[CON-0130 ~]`; accepting a verification ≠ accepting its object; wrong pen.
- **Authorization subject (§5.5 C6):** a subject-blind check cannot enforce a rule about subjects `[CON-0131 ✓]`; read by S0067 as the boundary, not a defect.
- **Record ≠ act as a context (§6.5):** = ARCH-INV-1 (§1.14.2) `[CON-0133 ✗ as new]`; plane or context (F22); owned vs preserved invariants, I-5 breached in practice `[CON-0132 ~]`; derived projection never stored, given a fixed fold `[CON-0134 ~]`; authority endings not exercised in S0071's census of 2026-08-17 (41 grants all AUTHORIZED; CONTINUATION, FAIL 0 of 117); the book's later recount found REVOKED 1, CONSUMED 1 (§1.13.2).
- **Acts (§5.6 C6):** a pinned acceptance, then an annotation of the same file; a six-word act read through a prepared text (F21); caveats dropped at acceptance; the S0064 reviews AI-written, the Principal agreeing (two facts, Principal testimony PT-001; the PO/ARB act assessed on the record only).

**Theory turn 4, trust (§9.9; reviewed by two reviewers and settled 2026-10-19):** at least three senses (source T-a, knowledge item T-c, record T-b); T-a a conditional, definitional reduction with hidden premises named (faithful outcome records; adjudication, whose root is F34) `[CON-0160 ~]`; L1–L5 rescoped (L1: trust is not an act, it may be a condition of one); all must-never, must-do candidates Hyp; T-b split: integrity and authenticity reduce to key and timestamp anchors, occurrence cannot be established from inside the record and is a candidate axiom, unfaithfulness is refutable `[CON-0161 ✗ as stated; restated ~]`; S0973 puts identity, not trust, in its kernel list.

**Window C18, S0097–S0100 (provisional; review pending):**
- **§8.11:** self-declared identity failed in both directions across a context-compaction continuation `[CON-0157 ~]`; is a continuation of a barred process barred (for the Principal; a further option for F32: the lineage of the working context); the author of a finding may not judge its uptake `[CON-0158 ~, Hyp]`; a barred process does not record the act; independence is not sufficient (object and registration); a verification of scope `[CON-0159 ~]`; an advisory AI that sees the estate only through the operator.
- **§1.14 integrated (step 2, entry 52a).**

**Window C17, S0092–S0096 (reviewed and settled 2026-10-19):**
- **§8.10:** adopting a model is not evidence of a capability, nor adoption of a document; superseded, not disproven `[CON-0156 ✓ scoped]`; existence vs category, one direction, an analogy to the boundary test only `[CON-0153 ✓ distinction]`; authoritative for which claim `[CON-0154 ~]`; an identifier bar made enforceable by the provenance of the writing act `[CON-0155 ✗ as stated; restated ~]`; access evidence short of corroboration; six separations; three kinds of dependency; contradictions: the START's time, the DDD order changed silently; a reachable stuck state (F33); no identity criterion for capabilities.
- **§1.13 integrated (step 2)**, two integrations made two-sided at the settlement.
- **Changed theory reviewed:** TT3 and C16 changes VALIDATED as answers, except §8.9 C2's pool (restated over a fixed population) and S0129's anchor and status.

**Window C16, S0087–S0091 (reviewed and settled 2026-10-19):**
- **§8.9:** content assessment vs provenance binding, for claims that can be re-derived `[CON-0150 ~]`; recusal is not clearance ✓; the eligible-verifier pool is non-increasing and empty only in a closed population `[CON-0151 ~; first Ded ✗]`; eligibility relative to what (F32); the absence discipline broken one scope wider; the classification question first, status CONTESTED; a correction that does not reach the reader; a count changed by governed work; a shared contract changed only by a third authority's act, a stated norm, not a case of K3 `[CON-0152 ~, Hyp]`; a programme is not an assignment; one actor with two identifiers; three self-reports as one line; decision rights by the nature of the activity (Hyp); "role" in three meanings.
- **§1.12 integrated (step 2, entry 52a).** §6.7 C1: the diagrams were untracked on 2026-08-19 (S0090 L57).
- **Theory turn 3 (§9.8; reviewed by two reviewers and settled 2026-10-19):** state history decomposes into H1′, H2, H3a/H3b and the evaluator, conditional `[CON-0148 ✗ as stated; restated ~]`; relative to the book's authority (§1.2, §1.16.2) and policy (RM-3) history is not independent, so its status as a primitive turns on the split of authority (valid vs verifiable): **for the Principal**; truthful history needs H4 soundness and H5 completeness or marked absence; S1609's congruence test; the record as a primitive `[CON-0149 ~, Hyp]`; forks F29–F31.

**Window C15, S0081 + S0083–S0086 (reviewed and settled 2026-10-19):**
- **§6.7, the C4 proposal set:** a self-declared proposal; the three C4 views draw no kernel/application line and disagree on the system boundary; S0084 draws an adapter/core line; grants, handoffs and policy in different homes; the authorizer's arrow ambiguous (possibly notation); testing the direction law needs a partition and identifiable dependencies `[CON-0147 ~]`; an output-only check confirms its inputs exactly when the fibre is a singleton `[CON-0146 ✗ as stated; restated ✓ as mathematics]`; verification never reaches acceptance; a seven-verb lifecycle with no negative verbs.
- **§1.12 rewritten in form** (form VALIDATED).
- **Changed theory reviewed (step 9):** TT2's "the order is a uniqueness" scoped to absent, tied or untrusted act times, F26(c) added; "one authoritative home" → a common, versioned value; §9.6.6a item 2's "kernel-defined function" withdrawn; CON-0144 restated again; the REALIZED anchor restored.

**Window C14, S0076–S0080 (reviewed and settled 2026-10-19):**
- **Closure (§5.7):** a duty and a prohibition; a deliberate null counts; a defective lifecycle may be closed with its defect stated `[CON-0144 ✗ as stated]`; a supersession is recognized only when recorded `[CON-0145 ✗ as stated]`; registered, never activated, closed (a subtype of §8.6 C3); stability of an accepted model as a *should*, not A1; a synthesis adopted as truth (F28); in BC-7's model the judged party never supplies its premise (T-1a); no context owns who produced a fact; the accepted model kept a statement already shown false.
- **§1.13 rewritten in form** (body byte-identical; header corrected; the "REALIZED" anchors S0014 L68 and S0015 L58, as re-checked at the C15 settlement).
- **Not yet in the book (minor):** precision vs integrity (S0076 L16); accepting a verification ≠ accepting the model (S0076 L34); no cosmetic closure (S0079 L47); kinds of non-closure (S0079 L44–67); sequencing to avoid encoding an unresolved fork (S0080 L18); OQ-13 (S0078 L266).

**Theory turn 2, the evaluator CE (§9.7; reviewed and settled 2026-10-19):**
- an evaluator of standing is necessary (Principal's act); §1.14.1's CE is **not yet a function** for any θ (covers, actor–holder link, non-monotone υ, recursive σ);
- CE's order is a uniqueness only where act times are absent, tied or untrusted (F26, three options; scoped at the C15 settlement); recording by set union (§9.6.6a items 3–4) holds under F26(b) and F26(c);
- K5 restated with a time axis `[CON-0143 ~]`; under K3 and K5 the definition, coverage relation, root and policy values need a common, versioned value (one home only with §1.10's premise; C15 settlement), not shown to be the kernel `[CON-0142 ✗ as stated]`;
- delegated computation needs a declared cut and a must-do (soundness and completeness relative to evidenced acts); on the present record CE would be uninformative;
- for the Principal: K5; F27 (ρ policy or query).

**Window C13, S0071–S0075 (reviewed and settled 2026-10-19):**
- **§8.8:**
  - stored is not true, for a given reader `[CON-0141 ~, a lemma]`;
  - a count needs its selection rule and measurement point (ten cases, one estate);
  - who may correct (F25, restated);
  - the model factor is unknown to the verifier, and declared inconsistently;
  - an amendment completes and never re-decides;
  - two different A–D defect taxonomies.
- **§6.5 C9:** "Handoff" contradicted; mutation ownership in two senses (pending, actual).
- **§1.14 rewritten in form** (body byte-identical; claims labelled 1.14.n-k).
- **Not yet in the book:** forks (a) non-consultation with several readers, (b) separation of duties bound to a lane or a process.

**Window C12, S0066–S0070 (reviewed and settled 2026-10-19):**
- **§6.6:** linked, not copied, restricted to "no second authoritative home" `[CON-0139 ✗ as stated]`; six state questions `[CON-0140 ~]`; the direction law, applications → kernel only (S0066 L99; S0069 L53–54, Observed); four work states, unordered.
- **Authority homonym:** S0066 says "authority" for the canonical home, not for standing from an act (for the open split of "authority").
- **Not yet in the book (review C12, for the next consolidation):**
  - the Capability Identity Invariant as I-0's time clause: one id, one owner, no reuse after retirement (S0068 L10–12; §1.10);
  - declared ≠ realized: CAP-03 knowledge curation "not built" (S0068 L20);
  - "governance" as an external domain vs the Governance role (S0067 L115–117; §1.10's table);
  - S0068's realization vocabulary as a maturity order (§1.9);
  - claim → evidence → assessment as a possible shared primitive (S0070 L35).

**Consolidation review S0036–S0065 (two reviewers, Opus and Sonnet; settled 2026-10-19):**
- **Second verdicts:** all 12 VALIDATED concepts of CON-0099–0134 confirmed by both reviewers; all PROVISIONAL and INVALID verdicts confirmed.
- **Architecture view (both):** no derivation chain from primitives to laws. The sections add cases to Ch. 1 laws. **Req (T9-01)** is the load-bearing undefined object: six claims defer to it.
- **Primitives:** missing candidates are **trust** (F23), **conflict over claims** (F24) and **requirement/contract** (§5.4). Minimality was never tested; "history" may be derivable (append-only record + I-7 + fold).
- **Not yet in the book:**
  - a claim must declare the layer it holds at: a neutrality claim "never stated its layer", and library defaults stood in for a decision (S0164 L1276–1323, L1485);
  - establishing a capability, D5 E1–E10, where Option A is unsatisfiable because E10 needs history (S0164 L2235–2264, L2432);
  - four orthogonal evidence dimensions (S0164 L2420);
  - Unknown ≠ Uncertain, and E_t/A_t/K_t, factive Knows (S2834 L864–878, L2039–2081);
  - representation adequacy relative to an inquiry, q = g ∘ T (S2834 L355–437);
  - "role" has three meanings, and communication splits into distribution and composition (S0091 L169–172, L122–124, L310–313);
  - the discovery stopping rule and uncertainty carriage (S0036 L55–57).
- **Language:** "Zero" has a fourth sense (§5.4, Z4). "Requirement" names two objects (§5.4 vs §9.1). BC-7's I-n are not the book's I-n (§6.5). The assurance-model terms are now glossed (§9.4.1a).

**New topics from the consolidation review (2026-10-19):**
- **"Authority" has three meanings:** a claim's standing structure (§1.2, CON-0016), an actor's permission or grant (§1.6, act × scope × time), and a justification order (§3.8, CON-0064). To be split into three IDs, so that A1's domain names exactly one.
- **Evidence scales are not related:** evidence status (CON-0005), §1.3's grade, P-4 maturity, P-10 count; TIME is not validated (S0004 L72).
- **Product binding ≈ SPECIALIZES / INSTANTIATES** (CON-0072): unify or keep apart.
- **DOMAIN:** confirmed as a kind by "observed owners" (S0009 L52), then said to own nothing (S0026 L39).
- **"Realization" is two relations** (representation and implementation; S0026 L34, L40, L85).
- **Kernel primitives without a concept ID:** identity (I-0), standing (CON-0018 ✗), visibility, policy.

**Direction topics from the Principal's later brainstorms (Ext; EXT-001 … EXT-029; not corpus; for later work):**
- **The kernel's type** (𝒮, M, I, A), with invariant preservation, mechanism minimality and an existence question; "no kernel exists" is a permitted answer (EXT-010, EXT-011, EXT-015). *Open; not yet in the book.* **As written it is degenerate:** preservation is a safety property, every subset of a preserving set preserves, so ∅ is minimal and exists. It needs required capabilities (liveness), for example I-Use (EXT-008 L129) or ablation measured on Δ (EXT-014 L229–239), and preservation written as m(s, x) ∈ 𝒮_I ∪ {⊥}.
- **Candidate laws and invariants:** the four forbidden collapses (EXT-001 L36–41); dimension purity, which rules out a single-number confidence (EXT-004 L29–30); the invariant list identity, provenance, attribution, integrity, authority, history, consistency, traceability (EXT-010 L183–192; EXT-011 L198–205); interface bypass as the test of mediation (EXT-012 L153); kernel-equivalence ≡_K through obs_K (EXT-011 L111–127).
- **Requirements are discovered, not given** (EXT-022 L112; EXT-023 L197; EXT-026 L72–95): bears on §5.4's open ContractDerivation. Also open in the direction: whether a canonical scalar gap exists, and how semantic equivalence preserves Sat (EXT-013 L360–369).
- **Time-varying mechanisms** (the formulation mechanism is eliminated, EXT-023 L127) against the kernel's stability claim; **designed ambiguity**, ambiguity that lowers satisfaction when removed (EXT-022 L214), against the homonym findings.
- **What the kernel manages** (R_K): knowledge object, claim, organizational knowledge, knowledge + provenance + authority, knowledge product (EXT-015 L19–25); formulation × context ℱ × 𝒞 (EXT-023 L325); a relation knower–known (K, O, R) (EXT-021 L116–122; EXT-001 L113–124; EXT-026 L42–70); inferential commitment (EXT-017). This bears on Ch. 3 §3.4 knowledge ≠ artifact.
- **Two notions of minimality:** no redundant mechanism (direction) vs no derivable primitive (RESEARCH-PURPOSE). A fork to state precisely.
- **A boundary test by delegability** (EXT-008 L249–254; KR-06, EXT-010 L338), tested by relocating the capability into the applications. The file's removal procedure (L268–282) tests necessity, not placement.
- **Where the DDD contexts sit** (a boundary fork inside the direction): outside the kernel (EXT-008 L423–425) vs supplying 𝒮, M, I, A (EXT-010 L110–115; EXT-011 L548–563; EXT-012 L318–330).
- **The order of work** (a fork inside the direction): top-down "strictly ordered" (EXT-012 L19, L258), iterative (EXT-008 L29–33), bottom-up from observation (EXT-029 L2073–2091).
- **Coverage ≠ accuracy, and unseen-knowledge estimation** (EXT-029). It could be applied to this book's own reviews: the overlap between the Professor's and the reviewer's findings estimates what both missed (capture–recapture; a tool candidate). *Only if the two are independent:* same model family and non-independent reviews bias the estimate low (EXT-029 L1127, L1665). The identifiability of the ideal state from finite evidence is open (L1666).
- **Four-valued states** True / False / Both / Neither (EXT-029 L1264–1316): a benchmark for Ch. 2 §2.8.8 (unknown ≠ conflict). Neither is one value and does not split §5.4's Unknown; the richer lists do (UNOBSERVED; ignorance, weak, conflicting, ambiguous; EXT-029 L812–821, L1276–1281).
- **Proposal and challenge as roles** (EXT-028), with Toulmin's ⟨claim, evidence, warrant⟩ against the book's evidence and warrant.
- **Forks inside the direction itself:**
  - truth as correspondence, coherence-plus-pragmatism, or resonance (EXT-023, EXT-028, EXT-027);
  - formalizing the traditions vs using them as lenses (EXT-021–024 vs EXT-025); Hegel's structure is kept as axiom A6 throughout (EXT-011 L614; EXT-015 Q5);
  - how collapses are handled, the outcome being the same: prohibition and tests vs diagnosis and repair (EXT-001 L636–642, L837–839; EXT-002 L353–355; EXT-008 L415; EXT-010 L105).
- **Conflicts with the book, named, not resolved (RESEARCH-PURPOSE):**
  - the central question (validity, ownership, trust vs identifiable, traceable, governable, usable): a difference, not a contradiction;
  - a dialectical fixed point as the way the kernel is found;
  - performative satisfaction;
  - improvement of a gap by expected size, without criticality (§5.4 C7); inclusion is compatible with C2, the weighted gap open.
- *The topics above that came from the extraction review's Check D (liveness, delegability, DDD-context placement, order of work, candidate laws and invariants, interface bypass, requirements discovered, ≡_K, the gap theory's open items, the epistemic vocabulary, identifiability, the relational knower–known state, time-varying M, designed ambiguity) are **candidates, not defects** of the book.*
- **Candidate attachments** (listed in `review/EXT-DIRECTION-20261019/SETTLEMENT.md`; awaiting the Principal's confirmation; C7's Int stays provisional until then).
- **Provenance of the attachments:** **decided by the Principal (2026-10-19): the "Gap theory" (EXT-013) is S2377**, the corpus document "formal theory of epistemic gaps" (2026-09-02). So §5.4 C7 is a restatement of a corpus document, not independent agreement. **Still open:** the "two files" (EXT-010), the "Foundation document" (EXT-015) and the "Linux document" (EXT-007).

**New topics and open questions found during T2:**
- **"Knowledge space" has several senses** (container · epistemic space · KST family; §3.10, CON-0098). The container sense itself has the five readings of §6.3 C7.
- **Is authority containment indexed by subject?** This ties F8 to F9 and T1-50 (§3.10, CON-0097).
- **A sixth reading of PKS:** a supporting system beside the kernel (S0357 L356–372); compare §6.3 C7.
- **Knowledge ≠ knowledge state ≠ knowledge space ≠ extraction; 𝒦 ≠ K_t ≠ P(K_t | O)** (S0651 L549–586): for Ch. 0 §0.3 and Ch. 4.
- **Partition into "cognitive worlds"** so that contradictions coexist (S0577 L107): compare Ch. 2's conflict regions.
- **"Projection" should be reserved for idempotent maps** (S2838 L705–725). The corpus has three senses of the word (S0577 L240–246).
- **Dates:** S0577 is dated 08-01 in the allowed list, but its content runs to 08-25 (it cites 08-25 documents). This is a second dating problem beside S1524's export timestamps.
- **Knowledge-concept models K-1 … K-11** (S0577 §4), including "knowledge is primitive" (K-11), which would deny the corpus's definitional programme: for Ch. 0.

**New topics and open questions found during T1** (not forced into F11):
- **"Binding" has a third sense:** "capability binding", a port bound to an implementation (S0166 L905–911), beside CON-0072 and "the product binds the platform" (Ch. 1 §1.10).
- **Identifier collision:** S0889's state-machine invariants I1–I10 against the Relationship Ontology's I-1 … I-11.
- **Enforcement count, corrected the same day:** S0043 calls "0 of 11 enforcing" "TOO STRONG". By its assertion, 19 `deny` entries block, 22 `ask` entries interrupt (its "41 enforced" merges the two), and no capability enforces. This bears on S0032's "no check enforces" (Ch. 0 §0.2) and Ch. 1 §1.9's census, which counts checks, not permission entries.
- **S0166 reverses its own correction:** "DI belongs in the Composition Root, not 'the Runtime'" (L615) against "the KnowledgeOS Runtime should be that root" (L1184).
- **Index order may not be authoring order (S1524 L760).** Filename timestamps can be export events, not authoring events, and S1524 shows a step misplaced by about 27 minutes. 02-FILES orders some sources by filename datestamp, so **the book's chronological order may be wrong where its order evidence is FILENAME-DATESTAMP.** This is a question for the method, recorded for the Principal; no rule is changed.
- **Missing deontic modality:** "Required" (obligation) has no counterpart in that step series (S1524 L689). Compare Ch. 2's rule effects.
- **Provenance ≠ lineage, and state ≠ history ≠ accessible memory** (S1524 L690): candidates for Ch. 1 and Ch. 4.
- **Four authorities** (epistemic, governance, execution, observational) (S1524 L533): compare Ch. 1's three authority systems.
- **Known → Unknown is a valid transition, and retracting evidence does not retract the assertion** (S0889 L612–650, L519–561): for Ch. 4 and Ch. 2 §2.8.8. Also S0889's **commitment boundary**: registering evidence does not create knowledge, and an unsupported AI claim cannot commit (L210, L789–804).
- **Enforcement point ≠ authority boundary** (S0043 L48 against S1052 L1133): a distinction, recorded in §3.9 C4.
- **Is a read-only action an execution?** (S1052 L977, L1655): recorded in §3.9 C5 as open.
- **Where an execution fact sits in the evidence grades:** the external system is authoritative for its own state (S1052 L844); this bears on F2 (§3.9 C7).

### Principal decisions on forks

- **Principal act on F22, 2026-10-19 (in session, recorded 2026-10-19T16:56:07Z):** "(1) Yes, the kernel serves several independent applications (RESEARCH-PURPOSE: 'every later application … runs on' it). (2) Yes, the kernel must answer questions of standing (the central question: 'which knowledge is valid, who owns it, and how can it be trusted?'). Question 3 stays a research question." Basis: RESEARCH-PURPOSE L7, L14. F22 stays open on K4 only (Ch. 9 §9.6.6a).

## 8. Weak points

- **Nothing has been validated in use.** The loop back is thin.
- I-8 (no second maintaining population) risks being unfalsifiable.
- "Runtime adapter" defined (Ch. 1 §1.5, four-layer model, S0031; CON-0084 ✗ until its two senses are separated; portability a hypothesis). Still undefined: the maturity levels L0–L4 (SEQ-0002).
- A capability is stipulated to be "that which executes" (SEQ-0012, **not reduced**: consolidation review; proposed closure by stipulation, I-11 as the definition of a capability in sense B, with §3.9 C3 as its testable counterpart; §3.9 C2–C3 propose that in the run sense nothing but a runtime executes, provisional).
- 71 of 134 registered concepts are INVALID (count as of the consolidation S0036–S0065; it was 42 of 71). Many of them are source vocabulary rather than the theory's building blocks (to be pruned under RK-2).

## 9. Position

- Last source read **in order**: **S0065**; next: **S0066** (after the consolidation review S0036–S0065; the first theory turn, F22, comes before S0066, Rulebook §12b).
- **Counts (from `close_source.py --position`, 2026-10-19):** read in order (full/skim): **65** · consulted in branches only: **4** (S0208, S0721, S0896, S1296; BRANCH-01) · scans: **26**.
- Chapters: 0–3 written · 4 (§4.1–4.3) · 5 (§5.1–5.6) · 6 (§6.1–6.5) · 8 (§8.1–8.7) · 9 (§9.1–9.5) · 7 planned.
