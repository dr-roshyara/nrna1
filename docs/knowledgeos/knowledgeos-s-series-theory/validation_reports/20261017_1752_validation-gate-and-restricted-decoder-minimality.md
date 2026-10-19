# Validation gate and restricted-decoder minimality

| | |
|---|---|
| **Date / author** | 2026-10-17 17:52, Session B (Professor) |
| **Responds to** | the Principal's "Validation report analysis and next research step" prompt. Baseline: `20261017_1748_validation_report.md`, the v0.4 validation report (a self-review) |
| **Kind** | Decision-ready report. Analytical work only: **no corpus or family file read, no filter run, no script executed, no manuscript, log or governance edit, no Git change.** This file is the only file created; it follows the Principal's standing instruction to write validation reports here |
| **Labels** | **[REPORTED]** a finding of the 1748 report, not re-verified · **[PROVED]** a proof is given here (same-model, unreviewed) · **[CE]** a counterexample, hand-verified here · **[CONJ]** · **[OPEN]** · **[DECISION]** reserved for the Principal |
| **Independence** | Everything here is Session B's own work on Session B's own material. It is **not** independent validation |

---

## 1. Executive recommendation

1. **Keep corpus reading paused** until the filter is independently reviewed and one count-only safety check has run (Gate B, §4).
2. **Approve the mathematical corrections as a change set** (§5), not as immediate edits. Preserve v0.4 as the baseline.
   - **One new correction:** the 1748 report's own M3 is stated too loosely. A corrected statement, with a hand-verified counterexample, is in §5 C-0.
3. **The best theoretical contribution available now is a precise answer to the restricted-decoder minimality question** (§7). Under bijection-invariant admissibility, a unique coarsest admissible state exists **if and only if** the admissible family is closed under joins. Two concrete counterexamples show what fails otherwise.
4. **Then run the five targeted source checks** (§6), and nothing broader.

## 2. What the 1748 report supports and does not support

| Topic | Supports | Does not support |
|---|---|---|
| Inventory | internal consistency: 2,767 = 2,734 + 33; 2,505 family files = 8 meta + 2,497 label families; 2,457 = 5 + 2,452 allowed; 48 = 3 + 45 withheld [REPORTED] | that the 45 withheld label families are exactly the hold-out labels' families; that the filter is safe |
| Filter | fail-closed on pin failure; no sealed identifiers printed (by code reading) [REPORTED] | correctness, independence, the source-ID quotation channel, edited-and-moved copies |
| Behavioural quotient | a standard construction valid under stated deterministic assumptions | that any KnowledgeOS artifact realizes it |
| Static vs dynamic sufficiency | a useful distinction | the 1748 report's M3 as worded (see §5 C-0) |
| Restricted decoders | a genuine open problem | any uniqueness claim |
| Corpus-wide theory | "selected documents report related ideas" | "the corpus has settled …" |
| Empirical evidence (ABK-1 6/6; the T-7 witness) | existence demonstrations, per family summaries | generality or statistical inference |

## 3. Risk-ranked blockers

| Rank | Blocker | Kind | Consequence if ignored |
|---|---|---|---|
| 1 | **Source-ID quotation channel** (§4.1) | safety | a hold-out file's content could reach Session B through a discovery family |
| 2 | Filter not independently reviewed | safety and provenance | every corpus-derived statement inherits unverified access control |
| 3 | G-LOG-0141 item 7 ("sealed lists") vs item 2 (the script) | governance | the filters' authorization basis is ambiguous |
| 4 | v0.4 edits made without an explicit go-ahead for the corrections | governance | the manuscript baseline is contested |
| 5 | Incorrect mathematical consequences in v0.4 (§5) | correctness | the theory's stated properties are partly false |
| 6 | T-2 and T-3 rest on third-hand paraphrase | provenance | the gap and equivalence foundations are unverified |
| 7 | The read log records intent, not completion; partial reads unmarked | provenance | the evidence trail overstates what was read |

## 4. Safety gate (Gate B): significance and a safe procedure

### 4.1 Why the source-ID channel matters

- Session A's families (P2) were built from **all 2,767 census files before the seal** (S3b). A family's rows quote sources by source ID with verbatim anchors.
- If a **discovery** family's rows quote a **hold-out file**, then reading that family puts hold-out *content* in front of Session B. ADD §6 calls that a seal breach: "a hold-out record reaching an agent input".
- The current family rule checks hold-out **labels** only.
- **Possible design mitigation [OPEN].** H-19's hold-out components are union-find components over edges E1–E4 (ADD §3). If one edge type is "two labels share a source file", then any label citing a hold-out file lies in a hold-out component, is itself a hold-out label, and its family is already withheld. A discovery family could then mention a hold-out file only outside its own rows (for example, in a group note quoting another label's source). Session B has not read ADD §3's edge definitions, so **this is not established.**

### 4.2 Safe procedure (proposed; needs authorization)

1. **Review first.** An independent reviewer (a human, or a different model family) reviews `discovery_filter.py` and `corpus_filter.py`, together with this procedure, **before** anything runs.
2. **Check script** (to be written only after authorization). Script-side, it loads the hold-out file list through `discovery_resolver()` (pins verified) and searches each allowed family for any hold-out source ID token (`S####`).
3. **Output, counts only:**
   - (a) the number of allowed families containing at least one hold-out source ID;
   - (b) a yes/no for whether any of the **6 families already opened** is among them;
   - (c) a yes/no for whether any of the **4 brainstorming documents read** contains a hold-out source ID.

   No names, IDs, hashes or text are output.
4. **Decision rule:**
   - if (a) = 0, the channel is closed and the gate passes;
   - if (a) > 0 but (b) and (c) are no, the gate passes once those families are added to the withheld set;
   - if (b) or (c) is yes, this is **a possible exposure**: stop, and report to the Principal for an ADD §6 ruling.
5. **Pre-existing gaps the review should also address:**
   - the `lstrip` normalization defect;
   - edited-and-moved copies of sealed files (only exact copies are caught);
   - the unscanned subdirectory in `20-FAMILIES/`;
   - logging of completed versus planned reads.

**Minimum authorization:** [DECISION] D1 (review plus the check) and D2 (the reading of G-LOG-0141 item 7).

## 5. Proposed mathematical corrections (change set; not applied)

**Common setting.** Σ is a set of inputs, which must include every time, actor, authorization and oracle datum that decisions depend on. Histories are h ∈ Σ\*. 𝔇 is a set of required decisions d : Σ\* → V_d. The relation ∼ is defined by h ∼ h′ iff for every continuation w and every d, d(hw) = d(h′w).

---

**C-0 (new; corrects the 1748 report's M3).**

*As stated:* "Static adequacy ⇏ dynamic sufficiency", with the example of "a constant representation adequate on the empty-history question set". That example is ill-posed.

*Corrected statement:*
- **(a) [PROVED]** If a representation R : Σ\* → S has a deterministic update u (R(ha) = u(R(h), a)) and is statically adequate **at every history** (d = f_d ∘ R for all d, h), then R is dynamically sufficient: R(h) = R(h′) ⇒ h ∼ h′.
  - *Proof.* R(h) = R(h′) ⇒ R(hw) = R(h′w) for every w, by u. Hence d(hw) = f_d(R(hw)) = f_d(R(h′w)) = d(h′w). ∎
- **(b) [CE]** A *snapshot* representation, one without an update rule, can be statically adequate at every history and still fail dynamic sufficiency.
  - Take Σ = {a, b}, d(h) = [h ends with "ab"], and R(h) = d(h), a single bit. R is adequate for d at every history.
  - h₁ = "a" and h₂ = "b" have R = 0. But h₁b = "ab" gives 1 and h₂b = "bb" gives 0. So R(h₁) = R(h₂) while h₁ ≁ h₂, and no update u exists.
  - The minimal sufficient state must retain "last symbol = a".
- *Status:* standard; a corrected consequence.
- *Implication:* the corpus's Adequacy(R, Q, Γ) is a snapshot notion. It implies dynamic sufficiency **only together with** a deterministic update. That is the formal content of Executable Adequacy's "auditable transition" clause.

---

**C-1 (decidability).**

*As stated:* "Comparing K-candidates is decidable relative to 𝔇."

*Corrected statement:* ∼ is **well defined**. It is **undecidable in general** and **decidable for finite-state systems.**
- *Proof sketch (undecidability).* For a Turing machine M, let d_M(xw) = 1 iff x = "1" and M halts within |w| steps, and 0 otherwise. Then "0" ∼ "1" iff M never halts. So no uniform procedure decides ∼ over computable decision families. ∎
- *Finite-state case:* for finite-state systems, decidability follows from automaton minimization.
- *Status:* standard.

---

**C-2 (component-removal criterion).**

*As stated:* "X belongs in K iff omitting it merges histories some d distinguishes."

*Why it fails:* **[CE]** Let R = (x₁, x₂) with x₁ = x₂ = b, and d = b. Removing either component alone preserves sufficiency; removing both destroys it. So the criterion classifies neither component as needed.

*Corrected statement:* a component is necessary **relative to a retained remainder**. Minimal sufficient component sets exist when the component set is finite, but **need not be unique.** Minimality is a property of the *set* of components, not of each component.

---

**C-3 (no compression).**

*As stated:* "K ≠ H iff no required decision depends on the path beyond its class." This is tautological, because decisions always depend on the path only through its class.

*Corrected statement:* **K = H (no compression is possible) iff every two distinct histories are separated by some required decision on some continuation.** Example: an audit decision that requires replaying the full history.

---

**C-4 (the "inverted ordering").**

*As stated:* a result.

*Corrected:* **an interpretation, and a conditional one.** Step 285's ordering (K, then 𝓘, then 𝓞, then δ) conflicts with S-1's demand that K be defined behaviourally (extensionally), **if that demand is accepted**. Under a compositional modelling choice, K-first is coherent.

*Status:* an internal tension between two corpus requirements. The chronology is [OPEN].

---

**C-5 (the gap order).**

*As stated:* "partial order".

*Corrected:* **[PROVED]** K ≼_EC K′ ⇔ Δ(K′) ⊆ Δ(K) is a **preorder on states**: it is reflexive and transitive because ⊆ is. It is a **partial order on gap-equivalence classes** [K] = {K′ : Δ(K′) = Δ(K)}, by antisymmetry of ⊆.
- It is **not** a partial order on states: [CE] two distinct states with the same gap.
- It **evaluates** states; it does not constrain transitions (REVISE and RETRACT may decrease it).
- It measures **contract satisfaction, not knowledge quality.**

---

**C-6 (conditions for the canonical quotient).** **[PROVED]** under conditions (i)–(iv); the proof is Prop. 15.2/15.4.
- (i) **Determinism:** each d is a function of the input history.
- (ii) **Input completeness:** Σ includes every factor decisions depend on (time, actor, authorization, oracle outcomes).
- (iii) **Totality:** decisions are defined on all of Σ\*. Alternatively, restrict to a prefix-closed admissible language L, with legality decisions included in 𝔇, and quantify over continuations w with hw ∈ L.
- (iv) **All decoders and updates permitted.**

Under (i)–(iv), ∼ is a right congruence. The quotient is the coarsest dynamically sufficient realization, unique up to isomorphism (a bijection commuting with the updates and the decoders).
- No finiteness is needed. This is the Nerode realization of an input/output map, not the regularity theorem.
- If (iv) is dropped, see §7.

---

**C-7 (partition vs encoding).** See §7, Theorem R1 and Example E1.

---

**C-8 (wording and provenance; carried from the 1748 report's list).** Unchanged:
- §15.2's title;
- "family summary / third-hand" markers on the T-rows;
- the source register's "5 read (6 logged)";
- read-log corrections.

## 6. Source-verification plan (Gate D; after Gate B passes)

| Target | Question it resolves | Read scope | Record |
|---|---|---|---|
| S2373 (theory v1.0) | Are AX-1…AX-7 and the Zero theorem as the family reports? Is the "THEORY CONDITIONALLY VERIFIED" verdict there? | the sections anchored in the family's births | full or partial; verbatim vs paraphrase |
| S2763 (v2) | Is 𝒪_core = {ASSERT, LINK, REVISE, RETRACT, ISOLATE} defined there, with pre- and post-conditions? | Part IV (transitions) and the operation definitions | same |
| S2871 (T-7 witness) | The experiment's data, the decoders, the k, the pre-declaration evidence | the whole file (it is short) | same |
| gap-theory family, and its primary | the exact Δ, Sat and ≥_EC definitions; THM-G2/G4 statements | header, rows, primary sections | same |
| v1.1 family, and its primary | the exact Obs_{EC,Q,C} definition; whether it quantifies over all contracts | same | same |

Each read goes through the approved filter. Completed and partial reads are logged as separate entries (if log edits are authorized). No other family is read.

## 7. Restricted-decoder minimality: results and research plan

### 7.1 Definitions

- **A realization:** (S, s₀, u, (f_d)_{d∈𝔇}), with u : S × Σ → S and s(h) = u\*(s₀, h). It is **sufficient** iff d = f_d ∘ s for every d.
- **Its partition:** π_s := ker(s), an equivalence on Σ\*. It is a right congruence, because s is updated by u.
- **Permitted classes:** 𝔉 (decoders S → V_d) and 𝔊 (updates). A realization is **admissible** iff f_d ∈ 𝔉 for every d and u ∈ 𝔊.
- **Admissible partitions:** 𝒜 := {π_s : s an admissible sufficient realization}. Every π ∈ 𝒜 refines ∼ (Prop. 15.4).
- **Coarseness:** π ⊒ π′ iff π ⊇ π′ as relations (π merges more).
- **A coarsest admissible state:** the greatest element of (𝒜, ⊒), when one exists.
- **Bijection invariance:** 𝔉 and 𝔊 are *bijection-invariant* iff, for every bijection β : S → S′, f ∈ 𝔉_S ⇔ f ∘ β⁻¹ ∈ 𝔉_{S′}, and likewise for updates under conjugation.

### 7.2 Results

> **Theorem R1 (when admissibility is a property of the partition)** **[PROVED]**
>
> *Statement.* If 𝔉 and 𝔊 are bijection-invariant, then whether some admissible sufficient realization induces the partition π depends only on π.
>
> *Proof.* Two realizations with the same partition differ only by a bijection β between their reachable state sets. Transporting decoders and updates along β preserves sufficiency and, by invariance, admissibility. ∎

> **Example E1 (without invariance, admissibility depends on the encoding)** **[CE, hand-verified]**
>
> *Setting.* Four classes {0, 1, 2, 3}; a decision d true exactly on classes {1, 2}; 𝔉 = decoders that read **one bit** of a 2-bit state.
>
> - Binary encoding (0→00, 1→01, 2→10, 3→11): d = bit₁ ⊕ bit₂, which is not a single bit, so it is **not admissible**.
> - Gray-code encoding (0→00, 1→01, 2→11, 3→10): d = bit₂ (01 and 11 are true; 00 and 10 are false), so it is **admissible**.
>
> Same partition, different admissibility. So under non-invariant 𝔉, "the minimal admissible state" must be defined over **encodings**, not partitions.

> **Theorem R2 (existence and uniqueness of the coarsest admissible state)** **[PROVED]**
>
> *Statement.* Let 𝒜 be non-empty. Then (𝒜, ⊒) has a greatest element **iff** ⋁𝒜 ∈ 𝒜, where ⋁ is the join in the lattice of equivalences on Σ\*. In particular, a sufficient condition is that 𝒜 be **closed under arbitrary joins**. The greatest element is then unique, and, under R1, unique up to isomorphism of realizations.
>
> *Proof.*
> - (⇐) If ⋁𝒜 ∈ 𝒜, it is above every member, so it is the greatest element.
> - (⇒) If a greatest element g exists, then g ⊇ π for every π ∈ 𝒜, so g ⊇ ⋁𝒜. Also g ∈ 𝒜, so g ⊆ ⋁𝒜. Hence g = ⋁𝒜 ∈ 𝒜.
> - *Note.* Arbitrary joins of right congruences are right congruences, and any join of relations refining ∼ refines ∼ (because ∼ is an equivalence containing them). So the unconstrained family is join-closed, and its greatest element is ∼ itself. That recovers Prop. 15.4. ∎

> **Example E2 (bijection-invariant, but not join-closed: no unique coarsest state)** **[CE, hand-verified]**
>
> *Setting.*
> - Σ = {a, b, c}, and 𝔇 is a single constant decision, so ∼ is one class.
> - Consider realizations whose state is determined by the first input symbol, i.e. partitions grouping histories by their first symbol, with ε separate. All are right congruences.
> - Policy (an admissibility predicate on partitions, so bijection-invariant): **"a state may aggregate histories from at most two first-input channels."**
>
> *Result.* The maximal admissible partitions group {a, b} | {c}, or {a, c} | {b}, or {b, c} | {a}. These three are pairwise **incomparable**. Their join, {a, b, c}, violates the policy. So there is **no unique coarsest admissible state**, although three minimal ones exist, all with the same state count.
>
> *Consequence.* Bijection invariance is **not sufficient** for uniqueness.

> **Example E3 (existence fails)** **[CE; the T-7 route 2 pattern]**
>
> *Setting.* 𝔉 = decoders all of whose output fibres contain at least k ≥ 2 states (k-anonymity), and a required decision that separates singleton classes.
>
> *Result.* No admissible decoder computes it, so 𝒜 = ∅.

**Answer to the prompt's question, "is closure under bijective re-encodings sufficient, necessary or neither for uniqueness?"** — **Neither.**
- *Not sufficient:* E2.
- *Not necessary:* take any non-invariant 𝔉 that still admits every encoding of the Nerode quotient, for example all decoders except a few on unused encodings. The coarsest admissible partition is then ∼, which is unique.
- **The exact criterion is ⋁𝒜 ∈ 𝒜 (Theorem R2).** Bijection invariance only makes the question well posed at the partition level (R1).

### 7.3 The three minimality notions (kept apart)

1. **Partition minimality**, unconstrained: the Nerode quotient ∼, which is unique (C-6).
2. **Encoding minimality** under a non-invariant 𝔉: defined per encoding. It can differ between isomorphic encodings (E1).
3. **Constraint-relative minimality** (computational, privacy, governance): exists and is unique iff ⋁𝒜 ∈ 𝒜 (R2). It can fail to be unique (E2) or to exist at all (E3).

### 7.4 Still open [OPEN]

- **Characterizing join-closure.** Which natural policy families are join-closed? Candidates: "coarser is always permitted" constraints, such as minimum-aggregation and k-anonymity on *partitions*, are upward-closed and hence join-closed **when sufficiency is unaffected**. Cap-type constraints (E2) are not.
- **Interaction with 𝔊.** Restricting updates can remove right-congruent partitions whose update is impermissible. Whether 𝒜 is then still join-closed is open.
- **Mapping to the corpus.** Which concrete corpus constructs (Executable Adequacy, "operator-count minimality is representation-relative", the k-anonymity witness) instantiate cases 2 and 3? This needs the Gate D sources and the kernel-minimality families.

**Deliverable status:** R1 and R2 are proved; E1, E2 and E3 are hand-verified; §7.4 is open. Everything is same-model and unreviewed. Nothing here establishes that KnowledgeOS satisfies the assumptions.

## 8. Computational and machine-learning methods register (none to be run without authorization)

| # | Research question | Input | Method and baseline | Validation | Cannot establish | Kind |
|---|---|---|---|---|---|---|
| ML-1 | Which family links indicate dependency or lineage? | allowed families (group lines) | graph analysis (degree, cycles); baseline: random rewiring | stability under removing low-evidence edges; spot checks | identity of concepts | exploratory |
| ML-2 | How much evidence is repetition (v3.5 R9)? | allowed families and corpus text | MinHash or shingling near-duplicate detection; baseline: exact hashing | manual check of a sample of pairs | independence of evidence | exploratory |
| ML-3 | Candidate concept groupings to prioritize reading | allowed family summaries | embeddings and clustering vs a TF-IDF baseline | bootstrap and seed stability; agreement with Session A's groups | that concepts are the same | exploratory |
| ML-4 | Are E1–E3 and R2 consistent with small synthetic systems? | synthetic finite systems only | exhaustive check of small finite cases | an independent re-implementation | anything about the corpus | exploratory (formal) |
| ML-5 | Does an ABK-1-style representation exhibit the C-0 (b) failure? | synthetic models | property-based testing | an independent implementation | corpus behaviour | exploratory |
| ML-6 | Static adequacy on recorded data, Ĥ(Q ∣ R) | specifically authorized data only | plug-in conditional entropy, with a hold-out split | out-of-sample | realizability, causation | exploratory |

No method touches hold-out material or infers protected labels.

## 9. Provisional DDD implications (architecture hypotheses, not decisions)

| Element | Classification | Note |
|---|---|---|
| Knowledge state K, the kernel | domain aggregate (hypothesis) | its content is fixed by 𝔇 (C-6). Fixing 𝔇 is a **stakeholder decision** |
| ASSERT, LINK, REVISE, RETRACT | domain commands emitting events | per the T-4 family; the primary is unverified |
| ISOLATE | domain policy or service, triggered by a contradiction event | hypothesis |
| Epistemic contract EC | specification value object, outside the kernel state | hypothesis |
| Gap Δ, Zero | derived read model (a projection of K and EC) | C-5: an evaluation, not a constraint |
| ∼, ≼_EC, 𝔉, 𝔊 | mathematical abstractions | 𝔉 and 𝔊 encode **policy**, which is a stakeholder decision (§7) |
| History / provenance | event store | C-3: K = H only if audit needs full replay |
| Governance (decision, authorization) | a separate context | proposal ≠ decision (AX-6, per the family) |

**Invariants (hypotheses):**

| Invariant | Formal statement | Business meaning | Precondition | Failure case | Future verification |
|---|---|---|---|---|---|
| Provenance | H′ = H·e | no silent rewrite | event store | in-place edit | replay equality |
| Category non-collapse | disjoint sorts | evidence never counts as a decision | type system | coercion | type checks |
| Proposal ≠ decision | an authority effect ⇒ a Decision event by an authorized actor | AI proposes, humans decide | actor model | auto-accept | negative tests |
| Zero honesty | ZeroClaim ⇒ Δ = ∅, recomputed | "complete" is auditable | Sat implementable | FalseZero | audit recomputation |
| Update closure (C-0 a) | every state representation has a deterministic update | adequacy survives the next input | — | snapshot-only summaries | property tests (ML-5) |

## 10. Prioritized research backlog

| # | Research question | Why | Evidence now / needed | Method | Deliverable | Acceptance | Stop/go | Authorization |
|---|---|---|---|---|---|---|---|---|
| B1 | Is the filter safe, and is the source-ID channel closed? | blocks all reading | code / independent review plus the count check | §4.2 | review report plus counts | (a) = 0, or a handled exclusion; (b) and (c) no | **stop** on (b) or (c) = yes | **D1, D2** |
| B2 | Correct the v0.4 claims | correctness | §5 / — | change set | patch plus rationale | each item matches §5 | — | **D4** |
| B3 | Verify T-1, T-4, T-7 at the primary source | provenance | family summaries / 3 files | §6 | verification note | verbatim-matched | after B1 | **D5** |
| B4 | Verify the gap theory and v1.1 | the foundations of §5 C-5, C-6 | third-hand / 2 families plus primaries | §6 | definitions extracted | exact definitions recorded | after B1 | **D5** |
| B5 | Characterize join-closure for policy families (§7.4) | the central open problem | R1, R2, E1–E3 / the corpus's 𝔉 notions | proofs; small examples | a theorem or counterexample | proof checked independently | after B4 | none (analysis); **D6** for review |
| B6 | Independent review of Part III and this report | acceptance | — | a human or a different model family | review | review verdict | — | **D6** |
| B7 | Theory chronology (K-first vs EC-first) | lineage | — / registry dates of the cited S-ids | script-side lookup of allowed rows | a dated table | dates recorded | after B1 | **D5** (scope) |
| B8 | Broader reading | coverage | — | prioritized by B3–B5 results | — | — | **not now** | later |

## 11. Principal decision sheet

| # | Decision | Recommended | Blocks |
|---|---|---|---|
| **D1** | Approve the independent filter review and the count-only check under the §4.2 procedure (name the reviewer) | approve in principle | B1, and with it all reading |
| **D2** | Rule on G-LOG-0141 item 7: does it forbid *showing* sealed lists, or also their script-side use? | clarify explicitly | D1's legitimacy |
| **D3** | v0.4 edits: retain them, or roll back to v0.3 (sha recorded in earlier reports)? | review the change list, then decide | manuscript baseline |
| **D4** | Approve §5 (C-0…C-8) as a change set to be applied as a separate, reviewable patch | approve as a change set | B2 |
| **D5** | Authorize reading scope R2/R3 (§6: 3 primaries plus 2 families) **after B1 passes**, with completed and partial reads logged separately | conditional approval | B3, B4, B7 |
| **D6** | Commission an independent review of Part III and §7 (R1, R2, E1–E3) | after B2 | B6 |

## 12. Completion statement

- **Ready:**
  - this decision package;
  - the corrected mathematics (C-0…C-6);
  - two theorems (R1, R2) and three counterexamples (E1–E3) for the minimality question.
  - All are proved or verified by hand, same-model.
- **Unverified:**
  - filter safety;
  - the source-ID channel;
  - every family-level claim against its primary source;
  - whether KnowledgeOS satisfies any of the assumptions;
  - independent review of everything.
- **Requires a human decision:** D1–D6.
- **Done in this step:** this file only. No corpus or family file was read; nothing was run, edited, logged or committed.
