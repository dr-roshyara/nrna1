# D4 correction proposal for manuscript v0.4: NOT APPLIED

| Field | Value |
|---|---|
| Status | **PROPOSAL for review (D4: proposal only).** The manuscript is **unchanged**. |
| Target | `S-SERIES-THEORY-MANUSCRIPT.md` v0.4, sha256 `28d8bba2a43cc8e07c2558824bbc98b0a86291b7f19b34a94dc3342e416034a0`. Line numbers refer to that hash. |
| Baseline (D3) | v0.4 remains an **unapproved draft**. v0.3 is preserved as the previous version (README §6). Applying this proposal would create v0.5 and needs separate approval. |
| Sources | Validation reports 1752, 1805, 1830, 1850 and 1849, together with the D6 review recorded in 1849 §1. |
| Created | 2026-10-17 18:59 (clock) |

**Classes:** COR = correction · CLAR = clarification · RETR = retraction · NEW = insertion · UNRES = unresolved, carried forward.

Each item gives the current text verbatim, the proposed text, the reason, the source, the class, and its downstream effect.

---

## P-1. Prop. 15.4, last sentence (l.1433) [COR]

```diff
- Any two minimal sufficient representations are isomorphic.
+ Any two **trim** coarsest sufficient representations (every state reachable from s₀) are isomorphic, by an
+ isomorphism that commutes with the transitions and the decoders. Non-trim representations are unique only after
+ restriction to their reachable part.
```

- **Reason:** a representation padded with unreachable states is also sufficient and has the same reachable part. So uniqueness up to isomorphism holds only for trim representations.
- **Source:** D6 correction 1 (1849 §1); C-6 (1752 §5).
- **Downstream:** the 15.5 ledger row "Canonical K = …" inherits the qualifier. No architecture hypothesis changes.

## P-2. §15.3 consequence 1, second bullet (l.1440) [COR]

```diff
- "Which K is canonical" is thus a **decidable comparison relative to 𝔇**, not a matter of taste.
+ "Which K is canonical" is thus a **well-defined comparison relative to 𝔇**, not a matter of taste. It is
+ *decidable* only when 𝔇 is specified in a class with decidable behavioural equivalence (for example
+ finite-state decisions). In general it is undecidable: as a promise problem (M promised total; decidable equality
+ on V_d) it is Π⁰₁-complete, and it remains undecidable even for a single fixed polynomial-time decision function
+ (1805 §3.2, C-1/C-1⁺; INTERNALLY REVIEWED).
```

- **Reason:** "decidable" is false in general.
- **Source:** C-1 and C-1⁺; D6 correction 2.
- **Downstream:** CR-6 (new, evidence map): the specification class for 𝔇 becomes a requirements decision.

## P-3. §15.3 consequence 2 (l.1441) [COR]

```diff
- A construct X (...) belongs in K **iff** omitting it would merge two histories that some d ∈ 𝔇 distinguishes,
- on some continuation.
+ Whether a construct X is needed is defined **relative to the components retained**: X is necessary in a set C of
+ components iff C ∖ {X} no longer separates some pair that 𝔇 separates on some continuation. Minimal
+ (irredundant) component sets need not be unique and can differ in size (static XOR example). Which component
+ sets are viable also depends on the update alphabet (O7b/O7c). The *partition* Σ\*/∼ is unique; its component
+ *schema* is not.
```

- **Reason:** a per-component "iff" fails. With duplicated components, neither copy is needed on its own, yet both together are.
- **Source:** C-2 (1752, 1805 §3.3, 1850); O7 (1849 §3).
- **Downstream:** supports H-12/CR-4, which treat schema as an infrastructure choice.

## P-4. §15.3 consequence 3, heading (l.1442) [CLAR]

```diff
- 3. **A dependency the corpus states in the wrong order.**
+ 3. **An ordering tension in the corpus (conditional).**
```

Add after the first sentence:

```diff
+ This holds *if* K must be defined behaviourally (S-1); under a compositional modelling choice a K-first
+ presentation is coherent. §15.8 records that the corpus also contains the EC-first ordering; chronology [OPEN].
```

- **Reason:** "Wrong order" overstates the point. It is a tension between two corpus requirements, and the claim is conditional.
- **Source:** C-4.
- **Downstream:** the 15.5 ledger row at l.1471 should use "in tension with" in place of "inverted relative to".

## P-5. §15.3 consequence 4, last bullet (l.1446) and ledger row (l.1472) [RETR + COR]

```diff
- Hence: **K ≠ H holds exactly when no required decision depends on the path beyond its class.**
+ Hence: **no compression is possible (K = H) exactly when the joint behavioural equivalence of 𝔇 is the
+ identity.** No single decision needs to require the full history: two decisions (first symbol; remainder) already
+ force it.
```

- **Reason:** the old statement is tautological, because decisions always depend on the path only through its class.
- **Source:** C-3; 1850; D6 (two-decision instance).
- **Downstream:** H-8 (append-only provenance) is justified only if the **joint** decision set forces K = H, or by a stakeholder requirement.

## P-6. §15.3 Limits (l.1450) [NEW, one sentence]

```diff
+ Nondeterministic kernels: see the O-6 analysis (validation report 20261017_1900, §3). When the outcomes of choices are
+ recorded as inputs, the deterministic theory applies unchanged. Otherwise the canonical object is the
+ belief-set quotient, and minimal *nondeterministic* representations are non-unique.
```

## P-7. §15.8 table, Adequacy row (l.1531) [COR]

```diff
- | Adequacy(R, Q, Γ) ⇔ R_req ⊆ Preserved(R) (T-6) | **R's induced equivalence refines ∼** (Prop. 15.4's sufficiency) |
+ | Adequacy(R, Q, Γ) ⇔ R_req ⊆ Preserved(R) (T-6) | if R_req contains only present distinctions: R's kernel refines
+ ⋂ker d (snapshot adequacy). It refines ∼ only if R has a deterministic update (C-0′a), or if R_req includes
+ future distinctions (predictive sufficiency, which still need not admit an update, C-0′b) |
```

- **Reason:** snapshot adequacy differs from dynamic sufficiency. The counterexample is R = d for "ends with ab".
- **Source:** C-0, C-0′ (1752, 1805 §3.1, 1850).
- **Note:** what the corpus's R_req contains is **[OPEN]** (Gate D: the T-6 sources).

## P-8. §15.8 table, order row (l.1535) [COR]

```diff
- | The order ≥_EC by gap inclusion (T-2) | a preorder on classes, induced by EC. It is partial, ...
+ | The order ≥_EC by gap inclusion (T-2) | a **preorder on states** and a **partial order on gap-equivalence
+ classes**, relative to one contract EC. Zero is the top class of ≥_EC only if some state attains Δ = ∅ (the corpus
+ phrases the same fact as "Zero is the bottom of (𝒫(R), ⊆)"; the orientations are reversed). It is partial,
+ contract-relative and non-scalar, ...
```

- **Source:** C-5; 1850.

## P-9. Refinement 15.5, bullets (l.1543, l.1546) [COR + NEW]

```diff
- > - The coarsest 𝔉-sufficient state is in general **finer** than the Nerode quotient, and may fail to exist
- >   uniquely when 𝔉 is not closed under composition.
+ > - For decoder classes that are **hereditary** (permitted on a codeword set ⇒ permitted on any subset) with
+ >   unrestricted updates, the coarsest 𝔉-sufficient partition **is still the Nerode quotient** whenever any
+ >   𝔉-sufficient state exists (B2). A finer or non-unique minimum arises under non-hereditary (usage-dependent)
+ >   decoder classes, under update restrictions (A3: injective infinite-state updates), and under cap-type policies
+ >   (E2). The exact existence-and-uniqueness criterion is ⋁𝒜 ∈ 𝒜 (R2). "Closure under composition" is not the
+ >   relevant condition.
- > - **[OPEN]** Which closure properties of 𝔉 make a unique minimal 𝔉-sufficient state exist.
+ > - **Partly resolved** (§15.9): upward-closed policies, separation policies, quotient-closed update classes and
+ >   hereditary decoder classes all leave ∼ as the unique coarsest admissible state. Remaining [OPEN]: an intrinsic
+ >   characterization for infinite-state update classes (O-1′); encoding-level update restrictions.
```

- **Reason:** the "in general finer" claim is false for the natural (hereditary) decoder classes.
- **Source:** 1830 (B2, A2–A5), 1850, 1849 (D6: B2 CONFIRMED).
- **Downstream:** H-11/CR-3 and H-12/CR-4.

## P-10. New §15.9 "Constraint-relative minimality" [NEW]

The section would consolidate, each with its status label:
- R1′, with trim realizations and joint iso-invariance over (u, f_d) (D6 correction 6);
- R2: ⋁𝒜 ∈ 𝒜 is exact; (J2) + (JD) is sufficient (D6 correction 7);
- R4(a)–(d);
- A2–A5;
- B2;
- E1, E4, E5;
- **E2 corrected:** exactly 6 three-class and 141 four-class maximal admissible congruences (synthetic, three implementations); classes ≥ 5 not determined; minimum 3 not unique. **Retract** "three maxima" and "union of three principal ideals" (1805);
- **E3′ corrected:** for trim realizations, a construction exists iff every attained value has ≥ k histories. The ⇐ witness is infinite-state, and the finite-state version is **unproved**. Unreachable padding always satisfies a state-counted condition. **Retract** the 1805 sentence "always satisfiable by splitting into k reachable states";
- the minimum state count is unique **at finite index** (D6 correction 5).

## P-11. §15.4, Φ bullet [NEW, one sentence]

```diff
+ The candidate losses for approximate representations, and the recommendation that approximation be confined to
+ labelled projections (never the authoritative K), are analysed in validation report 20261017_1900, §2 (O-5).
```

## U-1. Unresolved item, carried forward and not settled [UNRES]

There is a reviewer disagreement on the 1805 sentence "closure under binary joins suffices only if 𝒜 has no infinite strictly ascending chains". The integrator's reading is that the "only if" is too strong, while the "if" direction (under ACC) holds. This awaits an independent adjudicator.

**Proposed manuscript wording**, on which both reviewers agree: "Under ACC, binary-join closure suffices; (J2) + (JD) is sufficient in general; ⋁𝒜 ∈ 𝒜 is exact."

## Items deliberately not proposed

- The T-rows of §15.7 are source reports. They are not edited; adding the provenance markers is item C-8, which remains in the queue.
- README rules 1 and 4 and the revision table are outside the mathematics. They are listed for D3 follow-up.
- Report-level retractions (1805 E2, E3′ sentence; 1850 R2 "backwards") are recorded in later reports, never by editing the earlier reports.

---

## r2 amendments (19:06 clock; from the fresh-context review; INTERNALLY REVIEWED; still NOT APPLIED)

The r1 items above stay as written for the audit trail. **Where r2 differs, r2 governs.**

| Item | Amendment |
|---|---|
| P-1 | Also change the proof at l.1435: "Isomorphism of minimal representations" → "Isomorphism of **trim coarsest** representations" |
| P-2 | Append the C-1⁺ assumptions: "(assuming a polynomial-time-decodable prefix-free machine code and polynomial universal simulation)". The decidable classes are **examples only**; the pushdown case is literature (unverified) |
| P-3 | Replace "no longer separates some pair…" with "is no longer **sufficient** (separation **and** updatability)". Qualify "the partition is unique; the schema is not" as "**among schemas realizing ∼**". Under flip dynamics, irredundant sets induce different partitions (O7b). **Rewrite or delete the dangling sentences that follow at l.1441** ("Otherwise it is history … L(o, K, X) = L(o, K) …"); proposed: "Otherwise it is history or external context relative to the retained set." |
| P-5 | Add "on legal histories". Ledger row l.1472 replacement: `\| K = H iff the joint ∼ of 𝔇 is the identity (on legal histories) \| [DERIVED-B], INTERNALLY REVIEWED \| — \| apply to provenance (S-3) once 𝔇 is listed \|` |
| P-6 | Reword: "minimal **NFA** representations are not unique up to isomorphism in general (restricted canonical forms exist; literature, unverified); the belief-set (determinized) kernel is canonical, given a declared blocking convention." |
| P-7 | Direction fixed: "It refines ∼ **if** R has a deterministic update and is adequate at every reachable history (C-0′a), or by definition if R_req includes all future distinctions (predictive sufficiency; R itself need not admit an update, though ∼ is realizable on a carrier of R's size, C-0′b)." |
| P-8 | "only if some state attains Δ = ∅" → "**iff** some state attains Δ = ∅". Keep the caveat: if Δ is weighted or multiset-valued, C-5 must be re-proved |
| P-9 | **Error fixed.** Separation policies give the coarsest state **∼′**, the Nerode quotient of 𝔇 ∪ {d_ρ}, not ∼. Every case needs "when the admissible family is non-empty". "A finer or non-unique minimum arises under non-hereditary decoder classes" → "**may** arise (no witness yet)". Add B2's scope: "within the same code space B". "Existence-and-uniqueness criterion" → "existence of a greatest element (R2); minimum count unique at finite index" |
| P-10 | E2 labelled "COMPUTATIONALLY CHECKED (bounded enumeration, three same-family implementations; '141' = 141 of the 1009 admissible 4-class ones)". Add: the convention that ε carries no channel; "not principal"; the hand proof that no 2-class state exists. E3′: name the count, "for trim realizations, **state-counted** fibres ≥ k exist iff every attained value has ≥ k histories". Add: **C-6′ "PROVED" label withdrawn (literature only)** |
| P-11 | Reword to the reviewed O-5 status: "approximation outside the authoritative K is a **normative proposal (D9)**, not a consequence of O5-1" |
| U-1 | Add "for non-empty 𝒜". Wording: "For non-empty 𝒜: under ACC, (J2) suffices; (J2) + (JD) suffices in general; ⋁𝒜 ∈ 𝒜 is exact." The disagreement itself stays recorded until an independent adjudicator rules |
| **M-1 (new)** | l.1438, Consequence 1: "It is sufficient iff its induced equivalence refines ∼" → "It is **recursively** sufficient iff its kernel is a right congruence contained in ∼ (equivalently, it has a deterministic update and decoders); *predictive* sufficiency (kernel ⊆ ∼) is weaker (C-0′b)" |
| **M-2 (new)** | l.1450, Limits: add "**complete inputs** (C-6(ii): every datum a decision reads is in Σ) and **observable definedness** (C-6/C-6′: legality or ⊥ is a declared decision)" |
| **M-3 (new)** | l.1544, "This is the formal content of T-6's Executable Adequacy": prefix "**If** EA's 'auditable transition' clause means a deterministic update (Gate D, unverified), then …" |
