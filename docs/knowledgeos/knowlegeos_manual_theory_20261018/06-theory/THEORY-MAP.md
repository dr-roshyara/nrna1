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
- **Provenance question for the Principal:** which documents are the attached "Gap theory" (EXT-013), the "two files" (EXT-010), the "Foundation document" and the "Linux document" (EXT-007)? Needed before §5.4 C7's match can count as convergence.

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

## 8. Weak points

- **Nothing has been validated in use.** The loop back is thin.
- I-8 (no second maintaining population) risks being unfalsifiable.
- "Runtime adapter" defined (Ch. 1 §1.5, four-layer model, S0031; CON-0084 ✗ until its two senses are separated; portability a hypothesis). Still undefined: the maturity levels L0–L4 (SEQ-0002).
- A capability is stipulated to be "that which executes" (SEQ-0012, **not reduced**: consolidation review; proposed closure by stipulation, I-11 as the definition of a capability in sense B, with §3.9 C3 as its testable counterpart; §3.9 C2–C3 propose that in the run sense nothing but a runtime executes, provisional).
- 42 of 71 registered concepts are INVALID. Many of them are source vocabulary rather than the theory's building blocks (to be pruned under RK-2).

## 9. Position

- Last source read **in order**: **S0035**; next: **S0036** (after review C6 of S0031–S0035).
- **Counts (from `close_source.py --position`):** read in order (full/skim): **35** · consulted in branches only: **4** (S0208, S0721, S0896, S1296; BRANCH-01) · scans: **1**.
- Chapters: 0–3 written · 4 (§4.1) · 5 (§5.1–5.3) · 6 (§6.1) · 8 (§8.1) · 7 planned.
