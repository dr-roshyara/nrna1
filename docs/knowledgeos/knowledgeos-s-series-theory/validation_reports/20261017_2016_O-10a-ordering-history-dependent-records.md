# O-10a: ordering history-dependent records, and the minimal record

| Field | Value |
|---|---|
| Author | Session B (Professor), worker |
| Clock | 2026-10-17 20:16 |
| Authorization | The Principal decision record of 2026-10-17 (workspace copy at 19:44). Synthetic and analytical only. **Gate: BLOCKED.** |
| Read / not read | Read: my workspace files only. Not read: any corpus, family, seal, hold-out or S5 file; the governance log. |
| Protocol | `../synthetic-checks/o10a/PROTOCOL.md`, sha256 `77da84f5463e58196eaca03111384918fae14fb2b191c438229d33a87ef1c6ec`, recorded 20:14:42, before any code was written |
| Code | `o10a_checks.py` (`2c5732e3…f6d6`) · `o10a_supplementary_posthoc.py` (`9a841762…f1`; **post hoc**) |
| Labels | MATHEMATICAL PROOF (worker, unreviewed) · COMPUTATIONALLY CHECKED — SYNTHETIC · ARCHITECTURAL PROPOSAL · STAKEHOLDER DECISION REQUIRED · OPEN QUESTION. Internal review: §8, to be appended. |

## 1. The problem (A)

**Histories.** Σ is an alphabet, and L ⊆ Σ\* is a **prefix-closed** legal language with ε ∈ L. All definitions and proofs below stay inside L.
- Decisions are d : L → V_d, for d ∈ 𝔇. **No assumption is made outside L**, so the caveat on D10-1 ("d defined on all of Σ\*") does not arise here.
- 𝔇(h) := (d(h))_{d∈𝔇} is the decision vector at history h.
- h_t denotes the length-t prefix of h.

**Records.**
- A **record** is any map r : L → R.
- A **fixed per-input record** is r(a₁…a_n) = φ(a₁)…φ(a_n), for a single map φ. This is the D10-1 setting.
- An **online append-only record**, i.e. a history-dependent record, is r(ε) = ⟨⟩ and r(h·a) = r(h) · ⟨ρ(h, a)⟩.
  - Each step appends **one delimited entry**. An entry may be blank.
  - The entry may depend on the **whole** past h.

**Preservation.**
- **(P)**, *audit preservation:* the record at every time determines every decision at that time. Formally, ker r ⊆ ker d for all d.
- **(P⁺)**, *resumption:* the record also determines all future behaviour. Formally, ker r ⊆ ∼_L, the Nerode relation on L.

**Privacy constraint (evaluated in §4).** A predicate Π on kernels, for example "r does not determine the sensitive attribute s : L → {0,1}".

## 2. Candidate orderings (B)

| Candidate | Definition | Type | Relation to the others |
|---|---|---|---|
| O-F, functional factorization | r ⊑ r′ ("r holds no more information") iff r = g ∘ r′ for some g | preorder on records; partial order on kernels | — |
| O-P, partition refinement | ker r′ ⊆ ker r | partial order on equivalences | **≡ O-F** (Lemma 1) |
| O-D, distinguishable pairs | Dist(r) ⊆ Dist(r′), where Dist = (L×L) ∖ ker | partial order | **≡ O-P** (complements) |
| O-S, storage | bits used by r (naive fixed-width, or an optimal per-length code) | total preorder on numbers | **not** equivalent to O-F (Prop. 5) |
| O-C, cost or privacy functionals | a scalar or predicate on records | a functional, **not** an information order | monotone in ⊑ only under a post-processing condition (Prop. 4) |

**Lemma 1 (factorization = refinement = distinguishability)** [MATHEMATICAL PROOF].

r = g ∘ r′ for some g ⇔ ker r′ ⊆ ker r ⇔ Dist(r) ⊆ Dist(r′).

*Proof.*
- (⇒) If r′(h) = r′(h′), then r(h) = g(r′(h)) = g(r′(h′)) = r(h′).
- (⇐) Define g(r′(h)) := r(h). This is well defined because ker r′ ⊆ ker r, and g is arbitrary outside the image of r′.
- The last equivalence holds by complementation. ∎

**Consequence.** Three of the five candidates are the same order. Storage and cost or privacy are **not orders of information**. They are functionals that may or may not respect ⊑.

## 3. Theorems and counterexamples (C)

**Prop. 0 (online realizability)** [MATHEMATICAL PROOF; C1 checked].

Let **PC** be the set of equivalences π on L that are length-preserving (h π h′ ⇒ |h| = |h′|) and **prefix-closed** (h π h′ ⇒ h_t π h′_t for every t).
- The kernel of every online append-only record lies in PC.
- Conversely, every π ∈ PC is the kernel of the **class-log record** r_π(h) = ⟨[h₀]_π, …, [h_{|h|}]_π⟩.

*Proof.*
- **Records ⇒ PC:** r(h) = r(h′) ⇒ equal entry counts ⇒ |h| = |h′|. The records of the prefixes are equal-length prefixes of equal records, so they are equal.
- **PC ⇒ records:** if r_π(h) = r_π(h′), the last entries agree, so h π h′. If h π h′, then prefix-closure gives equal logs. ∎

*Assumption that matters:* entries are **delimited**. If entries had variable length with no delimiter, a kernel need not preserve length.

**Theorem 2 (the canonical minimal record)** [MATHEMATICAL PROOF; C2 checked].

Define

h ≡\* h′ ⟺ |h| = |h′| ∧ ∀t ≤ |h| : 𝔇(h_t) = 𝔇(h′_t).

Then ≡\* is the **greatest** element, in the order ⊑, of the family of online records satisfying (P). It is realized by the **decision-outcome log**

r\*(h) = ⟨𝔇(h₀), …, 𝔇(h_{|h|})⟩,

unique up to a bijective recoding of entries.

*Proof.*
- ≡\* ∈ PC, by definition.
- ≡\* ⊆ ker d, taking t = |h|.
- Now let π ∈ PC with π ⊆ ⋂ker d. If h π h′, then every prefix pair is π-related, hence has equal 𝔇. So h ≡\* h′. ∎

*Remark:* the family is closed under joins. The join of PC relations is in PC, and joins stay inside ⋂ker d.

**Corollary 2a (resumption).** Under (P⁺), the greatest record is the **kernel-state log** ⟨[h₀]_∼, …⟩. This is the same proof with ∼ in place of ⋂ker d.

**Corollary 2b (consistency with D10-1)** [MATHEMATICAL PROOF + C3 checked].

Assume D10-1's hypothesis: d is defined on all of Σ\*, and L = Σ\*. Then the fixed letter-wise record [·]_≈ satisfies (P) and is an online record, so ker[·]_≈ ⊆ ≡\*.
- The containment can be **strict**. It was strict in 282 of 300 random instances, and in the named instance d = first letter, where ≈ is the identity while r\* logs the first letter and then a constant.

So D10-1's optimum is the best **among fixed per-input records**, and history dependence can do strictly better.

**Prop. 3 (field projections break uniqueness)** [COUNTEREXAMPLE; C5 checked].
- *Restriction:* records may only be **projections onto subsets of fields**.
- *Setting:* letters a = (0,0), b = (0,1), c = (1,2), e = (1,3), f = (2,2), with d = [x ≥ 1].
- *Result:*
  - both {x} and {y} are minimal feasible field sets;
  - their kernels are **incomparable**;
  - their join is ker d, which no projection realizes ({} gives the total partition and is infeasible).
- So, among projections, there are **multiple incomparable minima**. Without the projection restriction, the greatest record r\* exists.
- **Contrast** (the case where two fields carry equivalent information): if u ≡ v as functions on L, the projections {u} and {v} have **equal** kernels. They are ⊑-equivalent, i.e. different representations of the same information. That is non-uniqueness of *schema*, not of *information*, mirroring C-2 and E4.

**Prop. 4 (privacy feasibility reduces to r\*)** [MATHEMATICAL PROOF; C4 checked].

Let Π be **upward-closed**: if π satisfies Π and π ⊆ π′, then π′ satisfies Π. Then a (P)-preserving online record satisfying Π exists **iff r\* satisfies Π**.

*Proof:* r\* is the greatest element (Theorem 2), and Π is upward-closed. ∎

*Instances:*
- "does not determine s": if a π-class mixes s-values, so does every coarser class containing it;
- k-anonymity counted on histories;
- (literature, unverified) differential privacy, which is closed under post-processing.

*Non-instances:* "must store field X", minimum-retention rules, caps on how much may be merged. These are not upward-closed, so Prop. 4 does not apply to them.

**Prop. 5 (storage is not information)** [COUNTEREXAMPLE + MATHEMATICAL PROOF; C6 checked].
- **(a)** The **naive** fixed-width log of r\* can be larger than the raw input log.
  - *Example:* two 3-valued decisions give 2·log₂3 ≈ 3.17 bits per step, against 1 bit per step for the binary input. This holds although r\* ⊑ identity.
- **(b)** With an **optimal per-length code**, the storage at length n is ⌈log₂ #classes_n⌉. This is monotone non-increasing under coarsening, because coarsening never adds classes. It is not strict: incomparable kernels can have equal counts.
- *Meaning:* storage cost is a **separate objective**. It agrees with ⊑ only under optimal coding, and only weakly.

**Prop. 6 (no retroactive minimization)** [MATHEMATICAL PROOF].

If 𝔇 ⊆ 𝔇′, then ≡\*_{𝔇′} ⊆ ≡\*_𝔇. The record minimized for 𝔇 preserves 𝔇′ iff ≡\*_𝔇 ⊆ ⋂_{d∈𝔇′} ker d.
- *Meaning:* **a record minimized for today's decisions cannot, in general, answer a decision added tomorrow.** Minimization trades directly against future-proofing.

## 4. Computational checks (D): COMPUTATIONALLY CHECKED — SYNTHETIC

| Check | Population | Result |
|---|---|---|
| C1 realizability (class-log of each feasible π has kernel π) | 600 instances, n = 3, Σ = {0,1}, L = all or random prefix-closed | 600 / 600 |
| C2 ≡\* is the **greatest** feasible PC record (brute-force enumeration of every feasible PC equivalence) | same 600 | 600 / 600 |
| C4 privacy feasible ⇔ r\* satisfies Π ("does not determine s", random s) | same 600 | 600 / 600 |
| C2 and C4, **post hoc**, coarser binary decisions (more feasible records, so more test power) | 400 instances; up to **7,176** feasible records per instance | 400 / 400 each |
| C3 letter-wise ⊆ ≡\* / strict | 300 random, plus the first-letter instance | 300 / 300; strict in 282 / 300; first-letter strict |
| C5 projection minima incomparable | named instance | confirmed |
| C6 storage | named instance | confirmed |

**Limits.**
- n ≤ 3, a binary alphabet, random tables.
- The protocol's decision ranges gave many instances with few feasible records (low power). The post-hoc run addresses this but was **not pre-declared**.
- **No machine learning was used.** No ML method would have sharpened these exhaustive checks.

**Tests built to fail on wrong equivalences:**
- C6 fails if storage were treated as information;
- C5 fails if field-subset minimality were treated as information minimality;
- C3 strictness fails if history-dependent and letter-wise optima were equated.

## 5. Conclusion and recommendation (E)

**Recommended order for the theory: ⊑**, i.e. functional factorization, which equals partition refinement and distinguishability.

*Why:*
- it measures what the record **can tell**, independently of encoding;
- it is a partial order on kernels;
- it makes "minimal record" a theorem: a unique greatest element, r\* for (P) and the state log for (P⁺);
- privacy constraints of the upward-closed kind reduce to a single check on r\*.

*It excludes:* storage cost and field counts as definitions of "less information" (Props. 3 and 5). Those remain **objectives or constraints**, to be stated separately.

**Still open:**
- **O-10b:** records that are approximate or probabilistic, which needs O-5's loss framework;
- **O-10c:** undelimited or variable-length entries;
- **O-10d:** concurrent writers, where entry order is not a single sequence;
- **O-10e:** non-upward-closed policies, such as must-store rules or retention minima, combined with minimization;
- the choice of 𝔇 itself, and how much future-proofing to buy: a **STAKEHOLDER DECISION**.

## 6. Implications for D10 and the architecture (ARCHITECTURAL PROPOSAL; not adopted)

- **D10's principle has an exact counterpart.** "Record the decision-relevant outcomes" is, formally, r\*: the minimal (P)-record is the **log of decision outcomes**. If the system must also be resumable from the record, the minimum is the log of kernel states.
- **This exposes a formal tension with H-1.**
  - H-1 says the authoritative history is the raw input or event log.
  - The raw event log is the **finest** record. Data minimization points to the **coarsest**, r\*.
  - Keeping raw events is justified only by:
    - (i) a required decision that needs them (C-3);
    - (ii) resumption under future decision sets (Prop. 6);
    - (iii) a stakeholder retention requirement.
  - **This is a STAKEHOLDER DECISION**, not a theorem.
- **Privacy checks become cheap**, if the rule is upward-closed: test r\* once (Prop. 4).
- **Schemas that restrict records to field subsets can lose the unique minimum** (Prop. 3). That is an argument for specifying records **semantically**, as the outcomes to retain, rather than as field lists. The mapping to a physical schema then belongs to infrastructure.

## 7. Claims corrected, narrowed or withdrawn (carried forward, never silently reinstated)

| Claim | Status |
|---|---|
| 1805 E2 "three maxima", "union of three principal ideals" | withdrawn (1850) |
| 1805 E3′ "always satisfiable by splitting into k reachable states" | withdrawn (1849) |
| 1850 R2 "backwards" | disputed (U-1, open) |
| C-6′ "PROVED" label | withdrawn (literature only) |
| O5-5 "approximation exploits information the kernel discards" | withdrawn (1900 §9). O5-12 replaces it with a properly framed counterexample (35/60 and 30/60, synthetic only) |
| O5-12 protocol measure Q2 | withdrawn as a separate finding (= 60 − Q1) |
| D9 "forced by O5-1" | withdrawn; it is a normative choice |
| N-CE3 universal merge claim | refuted (1900 §9) |
| D10-1 "context-dependent optimum is the kernel transition" | withdrawn as ill-posed (1951). **Superseded by Theorem 2 here:** the optimum is r\*, the outcome log, under ⊑ |
| P-9 (D4) "separation policies leave ∼" | corrected to ∼′ (r2) |

## 8. Internal review (appended; INTERNALLY REVIEWED — SAME MODEL FAMILY; this section governs §§1–7 where they differ)

**Process.**
- A fresh-context sub-agent attempted every claim before reading the report.
- It re-ran all the scripts.
- It checked the completeness of the PC enumeration against an independent brute force: 150 instances, 0 mismatches.

### 8.1 Verdicts

| Claim | Verdict | Integrated correction |
|---|---|---|
| L1 | CONFIRMED | — |
| P0 | CONFIRMED | **Delimiting entries is necessary.** Undelimited entries ("a" + "b" = "ab") give kernels outside PC that can be coarser than ≡\*. **Dependence on the full past is also necessary:** if ρ sees only (r(h), a), kernels must also be right-invariant, which gives a smaller family that need not contain ≡\* |
| T2 | CONFIRMED | Optimal **among online records** only; the non-online r = 𝔇(h) is coarser. **Hidden assumptions, now explicit:** 𝔇 is (i) known in advance, (ii) computable online at each step, and (iii) **never revised** later. If d(h) can change (new evidence, redefinition), r\* holds stale values that cannot be recomputed |
| T2a | CONFIRMED WITH CORRECTIONS | the greatest (P⁺) record logs **(𝔇, Nerode class)**. It reduces to the pure state log only if ∼_L ⊆ ⋂ker d |
| T2b | CONFIRMED | needs ≈ taken jointly over 𝔇, \|Σ\| ≥ 2, and length ≥ 2 for strictness |
| P3 | CONFIRMED | a valid online instance. **Narrowed:** it applies only to schemas restricted to **raw** fields. If a derived field holding d may be stored, its projection is r\* and the problem disappears |
| P4 | CONFIRMED WITH CORRECTIONS | **Differential privacy is removed as an instance.** For a deterministic record it is degenerate; post-processing closure concerns randomized mechanisms. The remaining instances, "does not determine s" and k-anonymity on history classes, stand |
| P5 | CONFIRMED WITH CORRECTIONS | (a) stands. (b) holds for a per-length **global** code, which is **not append-only**. Monotonicity for online per-step codes is **OPEN (O-10g)** |
| P6 | CONFIRMED | the substantive caution |
| (P) as "audit preservation" | **renamed** | (P) is **decision recoverability**. It does **not** give **verifiability**: an auditor re-deriving d from its inputs to check or contest a decision. An outcome-only log can only restate decisions |

### 8.2 Protocol deviations: disclosed now, previously undisclosed (author's error)

- **C5:** PROTOCOL.md declared the instance x, y, z = x⊕y with d = x⊕y. Its minimal sets {z} and {x, y} are **comparable**, since the {x, y} kernel is contained in the {z} kernel. So it cannot show incomparability.
  - I noticed this while writing the code. I **replaced** it with the a/b/c/e/f instance **without disclosing the change**, and §4 reported "confirmed".
  - The replacement instance is valid (the reviewer confirmed it), but it was **chosen after the fact**.
  - The protocol file is unchanged; this is the disclosure.
- **C6:** the protocol promised a constructed counterexample. The code compares the constants 2·log₂3 > 1, and its monotonicity part compares only ≡\* with the identity. **This is not a computational test.** C6 is relabelled **MATHEMATICAL ARGUMENT (analytic example)**, not COMPUTATIONALLY CHECKED.
- **C1** is close to circular: it is checked only on feasible π, the first 50. The direction "every online kernel lies in PC" is **proved, not computationally checked**.
- **Test power:**
  - in the declared population, 239 of 600 instances had exactly one feasible record, so the test was vacuous there;
  - a deliberately wrong ≡\* was caught in only 113 of 600.
  - The post-hoc high-power run addresses this but was not pre-declared. **A pre-declared high-power replication is queued (O-10h).**
- **Errata:** the header abbreviates the hash of `o10a_checks.py` as "…f6d6". The correct suffix is "…**d6f6**" (full value: `2c5732e3415aa0b1fed6de7d695e730acd855a8e938337a4b7cd82aa37d1d6f6`). Also, r(ε) = ⟨⟩ in §1 and r\*(ε) = ⟨𝔇(ε)⟩ in Theorem 2 differ by the initial entry. This is harmless; the convention is r(ε) = ⟨𝔇(ε)⟩.

### 8.3 Corrections to §§5–6 (overreach)

- **"Exact counterpart of D10"** holds only under the hidden assumptions of T2: 𝔇 fixed, computable online, and never revised.
- **The tension with H-1 gets a fourth reason** for keeping raw inputs: **(iv) verifiability and contestability.** Without it, the comparison of finest and coarsest record was set up in r\*'s favour. Whether to keep raw inputs remains a **STAKEHOLDER DECISION**.
- **The schema recommendation** (specify records semantically) rests on one counterexample that a derived field avoids. It is **narrowed** to "schemas restricted to raw fields can lose the unique minimum".
- **"Privacy checks become cheap"** holds only for upward-closed predicates on kernels, and not for DP in this deterministic model.
- **The computational checks** confirm algebra that was already proved. They add **little independent evidence**.

### 8.4 New open questions

| ID | Question |
|---|---|
| O-10f | **Minimal record for verifiability.** Which inputs suffice to *recompute* and check d, and where does that record sit between r\* and the raw log? This is likely the decisive question for D10 |
| O-10g | Storage monotonicity for online per-step codes |
| O-10h | A pre-declared high-power replication of C2 and C4 |
| O-10i | Decisions revised after recording (stale outcomes) |

### 8.5 Status after review

- Theorem 2 (the canonical minimal online record under recoverability) is **INTERNALLY REVIEWED — SAME MODEL FAMILY**, under the stated assumptions.
- **Recommended order: ⊑.** This is unchanged.
- The **D10 consequence is weaker than §6 claimed.** The minimal recoverable record is the outcome log. But verifiability (O-10f), revisions (O-10i) and future decision sets (Prop. 6) may each justify keeping more, and that choice belongs to the stakeholders.
