# Extraction from Adámek–Rosický–Vitale, *Algebraic Theories: A Categorical Introduction to General Algebra*

Working as a senior mathematician/statistician: I will extract **only what is actually in the text**, with no interpretive overlay. I distinguish:

- **(D)** Definitions
- **(T)** Theorems/Propositions/Lemmas/Corollaries
- **(E)** Examples
- **(R)** Remarks
- **(H)** Historical remarks

I will preserve the book's own numbering and terminology. I will flag where I am paraphrasing versus quoting.

---

## Part I — Abstract Algebraic Categories (Ch. 1–10)

### Chapter 1 — Algebraic theories and algebraic categories

**(D 1.1)** An **algebraic theory** is a small category 𝒯 with finite products. An **algebra** for 𝒯 is a functor A : 𝒯 → Set preserving finite products. **Alg 𝒯** denotes the category of algebras; morphisms are natural transformations (**homomorphisms**).

**(D 1.2)** A category is **algebraic** if it is equivalent to Alg 𝒯 for some algebraic theory 𝒯.

**(R 1.3)** Small vs. essentially small is not distinguished; a category is essentially small if equivalent to a small one.

**(E 1.4)** Sets: 𝒩 = full subcategory of Set^op on natural numbers n = {0,…,n−1}. Alg 𝒩 ≃ Set via A ↦ A1.

**(E 1.5)** Many-sorted sets: for a set S, Set^S is algebraic; theory S* has objects finite words over S, morphisms a : k → n with s_{a(i)} = s′_i.

**(E 1.6)** Abelian groups: 𝒯_ab has objects ℕ, morphisms n → k are n×k integer matrices; composition is matrix multiplication. Alg 𝒯_ab ≃ Ab.

**(E 1.7)** Groups: 𝒯_gr has objects ℕ, morphisms n → 1 are terms in x₀,…,x_{n−1}; general morphisms are k-tuples of terms; composition is substitution.

**(E 1.8)** Modules: R-Mod is algebraic; theory has ℕ as objects, matrices over R as morphisms.

**(E 1.9)** One-sorted Σ-algebras: Σ a signature with arity function ar : Σ → ℕ; Σ-Alg is algebraic (proved in Ch. 13).

**(E 1.10)** Many-sorted Σ-algebras: Σ with arity ar : Σ → S* × S; Σ-Alg is algebraic (Ch. 14).

**(E 1.11)** Graphs: category Graph of directed graphs with multiple edges; theory described in 1.16.

**(R 1.12)** Every object t of 𝒯 yields representable algebra Y_𝒯(t) = 𝒯(t, −); Yoneda gives full faithful Y_𝒯 : 𝒯^op → Alg 𝒯.

**(L 1.13)** Y_𝒯 : 𝒯^op → Alg 𝒯 preserves finite coproducts.

**(E 1.14)** Set^C is algebraic; theory is free finite-product completion 𝒯_C of C.

**(R 1.15)** Explicit description of 𝒯_C: objects finite families (C_i)_{i∈I}; morphisms (a, α) with a : J → I and α_j : C_{a(j)} → C′_j.

**(E 1.16)** Graph theory 𝒯_graph is free finite-product completion of e ⇉ v.

**(R 1.17)** 𝒯_C^op ≃ full subcategory of Set^C on finite coproducts of representables.

**(E 1.18)** (1) 𝒩 = 𝒯_C for C one-object discrete. (2) S* = 𝒯_C for C = S discrete.

**(E 1.19)** M-Set for a monoid M; Set^C presents as unary S-sorted algebras.

**(R 1.20)** Free objects ℤ^n in Ab; forgetful U : Set^C → Set^S has left adjoint; free finitely generated objects form theory.

**(P 1.21)** Alg 𝒯 is closed in Set^𝒯 under limits.

**(C 1.22)** Every algebraic category is complete.

**(R 1.23)** Limits objectwise; monomorphisms are componentwise injective; kernel pairs exist objectwise.

**(E 1.24)** Stacks: two-sorted signature with succ, push, pop, top, e, 0.

**(E 1.25)** Sequential automata: sorts s, i, o; operations δ : si → s, γ : s → o, φ : s.

**(H)** Lawvere (1963); Birkhoff (1935); Higgins (1963–64); Bénabou (1968); Ehresmann (1967).

---

### Chapter 2 — Sifted and filtered colimits

**(D 2.1)** 𝒟 small is **sifted** if finite products in Set commute with colimits over 𝒟; **filtered** if finite limits in Set commute with colimits over 𝒟.

**(R 2.2)** Explicit characterization via canonical map δ.

**(E 2.3)** (1) ω-chains filtered. (2) Chains filtered. (3) Directed colimits filtered. (4) Colimits of idempotents filtered. (5) Filtered ⇒ sifted. (6) Coequalizers not sifted; reflexive coequalizers sifted but not filtered. Sifted = filtered + reflexive coequalizers.

**(R 2.4)** Sifted categories: nonempty and cospans connected.

**(P 2.5)** Alg 𝒯 is closed in Set^𝒯 under sifted colimits.

**(E 2.6)** Coproducts are not sifted.

**(C 2.7)** In every algebraic category, sifted colimits commute with finite products.

**(E 2.8)** Directed unions of abelian groups; reflexive coequalizers in Ab.

**(R 2.9)** Directed union in Alg 𝒯.

**(D 2.10)** Connected category.

**(R 2.11)** 𝒜 connected iff constant functor of value 1 has colimit 1.

**(D 2.12)** Final functor.

**(L 2.13)** F final iff finality w.r.t. representables iff d↓F connected for all d.

**(R 2.14)** Finality of diagonal functor Δ : 𝒟 → 𝒟 × 𝒟.

**(T 2.15)** 𝒟 sifted iff nonempty and Δ : 𝒟 → 𝒟 × 𝒟 final.

**(E 2.16)** Small category with finite coproducts is sifted.

**(E 2.17)** A⇉B is not sifted.

**(R 2.18)** Filtered = every finitely generated subcategory has a cocone.

**(T 2.19)** 𝒟 filtered iff every finitely generated subcategory has a cocone iff nonempty + cospans + merging parallel pairs.

**(P 2.20)** 𝒟 filtered iff for every finitely generated ℐ, Δ : 𝒟 → 𝒟^ℐ is final.

**(R 2.21)** Every colimit is filtered colimit of finite colimits.

**(R 2.22)** Finitary = preserves filtered colimits.

**(D 2.23)** Finitary functor.

**(E 2.24)** H_n, coproducts, polynomial functor H_Σ are finitary.

**(E 2.25)** H-algebras; H_Σ-Alg = Σ-Alg.

**(E 2.26)** U : Pos → Set is finitary but does not preserve sifted colimits.

**(H)** Bourbaki (1956); Artin–Grothendieck–Verdier (1972); Gabriel–Ulmer (1971); Lair (1996); Adámek–Rosický (2001).

---

### Chapter 3 — Reflexive coequalizers

**(D 3.1)** Reflexive coequalizers = coequalizers of reflexive pairs.

**(R 3.2)** Category ℳ sifted; reflexive coequalizers commute with binary products in Set.

**(C 3.3)** Alg 𝒯 closed in Set^𝒯 under reflexive coequalizers.

**(E 3.4)** In category with kernel pairs, every regular epimorphism is a reflexive coequalizer.

**(C 3.5)** Alg 𝒯 closed under regular epimorphisms; regular epis = componentwise surjective.

**(R 3.6)** Algebraic categories are co-wellpowered w.r.t. regular epimorphisms.

**(C 3.7)** Every algebraic category has regular factorizations.

**(E 3.8)** In Ab: coproducts not objectwise; reflexive coequalizers objectwise, general coequalizers not.

**(R 3.9)** No simple characterization of epimorphisms/regular monos.

**(E 3.10)** Monoids: embedding ℤ → ℚ is epi but not regular.

**(R 3.11)** Relations in finitely complete categories.

**(D 3.12)** Reflexive, symmetric, transitive, equivalence relation.

**(R 3.13)** Congruences = equivalence relations in Σ-Alg.

**(D 3.14)** Effective equivalence relations.

**(E 3.15)** Algebraic categories have effective equivalence relations; Pos does not.

**(D 3.16)** Exact category: finite limits, coequalizers of kernel pairs, effective equivalence relations, regular epis stable under pullback.

**(E 3.17)** Set is exact.

**(C 3.18)** Every algebraic category is exact.

**(D 3.19)** Colimits distribute over products.

**(E 3.20)** In Set: filtered colimits distribute over products; all colimits distribute over finite products; reflexive coequalizers not over infinite products.

**(C 3.21)** In every algebraic category: regular epis stable under products; filtered colimits distribute over products; sifted colimits distribute over finite products.

**(R 3.22)** Alg 𝒯 cocomplete (proved Ch. 4); not all colimits distribute over finite products.

**(H)** Linton (1969a); Johnstone thesis; Diers (1976); Pedicchio–Wood (2000); Adámek et al. (2001a, 2003); Artin et al. (1972); Barr et al. (1971).

---

### Chapter 4 — Algebraic categories as free completions

**(R 4.1)** Cocompleteness reduces to finite coproducts.

**(L 4.2)** For A : 𝒯 → Set TFAE: (1) A is algebra; (2) El A sifted; (3) A sifted colimit of representables.

**(R 4.3)** Analogous result for finite limits / filtered colimits.

**(L 4.4)** Product of final functors final; product of sifted categories sifted.

**(T 4.5)** Every algebraic category is cocomplete.

**(E 4.6)** Coproducts in Ab, sequential automata, Graph.

**(E 4.7)** Coequalizers in Ab, Graph.

**(R 4.8)** Free completion under 𝔻-colimits.

**(D 4.9)** Free completion under 𝔻-colimits.

**(T 4.10)** Yoneda Y_{C^op} : C → Set^{C^op} is free completion under colimits.

**(R 4.11)** Equality F = F*·Y_{C^op} can be chosen; equivalence with Colim(Set^{C^op}, B).

**(E 4.12)** Ind and Sind completions.

**(T 4.13)** Y_𝒯 : 𝒯^op → Alg 𝒯 is free completion under sifted colimits. Alg 𝒯 = Sind(𝒯^op).

**(C 4.14)** 𝒜 algebraic iff free completion of small category with finite coproducts under sifted colimits.

**(R 4.15)** Right adjoint via B ↦ B(F−, B).

**(R 4.16)** Lex 𝒯: Yoneda preserves finite colimits; embedding preserves limits and filtered colimits; cocomplete.

**(T 4.17)** Y_𝒯 : 𝒯^op → Lex 𝒯 is free completion under filtered colimits. Lex 𝒯 = Ind(𝒯^op).

**(R 4.18)** Right adjoint for Lex case.

**(H)** Ulmer (1968); Gabriel–Ulmer (1971); Artin et al. (1972); Adámek–Rosický (2001); Kelly (1982).

---

### Chapter 5 — Properties of algebras

**(D 5.1)** Regular projective object.

**(E 5.2)** All Set objects regular projective; free algebras regular projective; regular projective abelian groups are free; finite boolean algebras regular projective; graphs with disjoint edges regular projective.

**(D 5.3)** Finitely presentable / perfectly presentable object.

**(R 5.4)** Perfectly presentable ⇒ finitely presentable + (with kernel pairs) regular projective.

**(E 5.5)** Representables perfectly presentable in Set^𝒯, Alg 𝒯, Lex 𝒯.

**(E 5.6)** Finite sets perfectly presentable in Set; Ab: finitely presentable = classical; perfectly presentable = free on finitely many generators; posets: compact elements; graphs: finitely many vertices/edges; perfectly = finitely many pairwise disjoint edges.

**(R 5.7)** Finite presentability = classical; free algebras regular projective; perfectly presentable = retracts of free on finitely many generators.

**(R 5.8)** Absolutely presentable objects.

**(L 5.9)** Retracts inherit regular projectivity / finite presentability / perfect presentability.

**(R 5.10)** Absolutely presentable in Set^C = retracts of representables.

**(L 5.11)** Perfectly presentable closed under finite coproducts; finitely presentable closed under finite colimits.

**(C 5.12)** Every object is sifted colimit of perfectly presentable; filtered colimit of finitely presentable.

**(L 5.13)** Regular projectives closed under coproducts.

**(C 5.14)** In Alg 𝒯: perfectly presentable = retracts of representables; regular projective = retracts of coproducts of representables.

**(C 5.15)** Every algebraic category has enough regular projectives.

**(C 5.16)** Perfectly presentable = finitely presentable + regular projective.

**(P 5.17)** Finitely presentable = coequalizers of reflexive pairs between representables.

**(R 5.18)** Finite generation.

**(D 5.19)** Directed union.

**(R 5.20)** Directed unions in Set, Set^𝒯, Alg 𝒯.

**(D 5.21)** Finitely generated object.

**(P 5.22)** Finitely generated = regular quotients of representables.

**(E 5.23)** ℕ/Set example.

**(H)** Gabriel–Ulmer (1971); Adámek–Rosický (2001); Diers (1976); Pedicchio–Wood (2000); Joyal.

---

### Chapter 6 — A characterization of algebraic categories

**(D 6.1)** Generator; strong generator.

**(R 6.2)** Generator = faithful functor to Set^𝒢; strong generator = faithful + conservative.

**(P 6.3)** 𝒢 generator iff every object is quotient of coproduct of 𝒢-objects; strong iff extremal quotient.

**(C 6.4)** If 𝒜 has colimits and every object is colimit of 𝒢, then 𝒢 strong generator.

**(E 6.5)** Set: singleton; Ab: ℤ; Pos: 2-element chain (terminal poset not strong).

**(E 6.6)** Set^C: representables; Alg 𝒯: representables.

**(L 6.7)** Only a set of perfectly presentable objects.

**(R 6.8)** Analogous for finitely presentable.

**(T 6.9)** Characterization of algebraic categories: TFAE (1) algebraic; (2) cocomplete + set of perfectly presentables whose sifted colimits give all objects; (3) cocomplete + strong generator of perfectly presentables. If generator closed under finite coproducts, dual is algebraic theory.

**(E 6.10)** Pos not algebraic; Bool with 𝒫𝒫n.

**(E 6.11)** Chain complexes Ch(R) algebraic; perfectly presentable = bounded complexes of perfectly presentable modules.

**(N 6.12)** 𝒜_pp.

**(C 6.13)** Dual of 𝒜_pp is algebraic theory of 𝒜.

**(C 6.14)** 𝒜 ≃ ℬ iff 𝒜_pp ≃ ℬ_pp.

**(P 6.15)** Slice category 𝒜↓A of algebraic is algebraic.

**(L 6.16)** (1) Faithful conservative right adjoint pulls back strong generator; (2) preserves sifted colimits ⇒ pulls back perfectly presentables.

**(P 6.17)** Alg 𝒯 reflective subcategory of Set^𝒯 closed under sifted colimits.

**(T 6.18)** 𝒜 algebraic iff full reflective subcategory of Set^C closed under sifted colimits.

**(C 6.19)** 𝒜^𝒟 algebraic if 𝒜 algebraic.

**(R 6.20)** Parallel with locally finitely presentable categories.

**(D 6.21)** Locally finitely presentable category.

**(E 6.22)** Algebraic ⇒ lfp; Lex 𝒯 lfp; Pos lfp.

**(T 6.23)** Characterization of lfp categories.

**(C 6.24)** lfp iff free completion of small finitely cocomplete under filtered colimits.

**(N 6.25)** 𝒜_fp.

**(C 6.26)** 𝒜 lfp ⇒ 𝒜 ≃ Lex(𝒜_fp^op).

**(P 6.27)** Lex 𝒯 reflective in Set^𝒯 closed under filtered colimits.

**(T 6.28)** 𝒜 lfp iff reflective subcategory of Set^C closed under filtered colimits.

**(C 6.29)** Lex 𝒯 reflective in Alg 𝒯 closed under filtered colimits.

**(C 6.30)** If every finitely presentable in 𝒜 is regular projective, then filtered ⇒ sifted preservation.

**(E 6.31)** Set, Set^S, vector spaces, semisimple modules.

**(T 6.32)** 𝒜 ≃ Set^C iff cocomplete + strong generator of absolutely presentables.

**(H)** Gabriel–Ulmer (1971); Adámek–Rosický (1994, 2001); Makkai–Paré (1989); Lawvere (1963); Diers (1976); Bunge (1966); Linton (1969b); Centazzo et al. (2004).

---

### Chapter 7 — From filtered to sifted

Three claims: (1) sifted = filtered + reflexive coequalizers (with finite coproducts); (2) preservation analogous (with finitely cocomplete domain); (3) Sind C = Ind(Rec C) (with finite coproducts).

**(D 7.1)** Free completion under reflexive coequalizers.

**(L 7.2)** Inclusion (Alg 𝒯)_fp → Set^𝒯 preserves reflexive coequalizers.

**(T 7.3)** Y_𝒯 : 𝒯^op → (Alg 𝒯)_fp is free completion under reflexive coequalizers. (Alg 𝒯)_fp = Rec(𝒯^op).

**(C 7.4)** Sind C = Ind(Rec C) for C with finite coproducts.

**(R 7.5)** If ℬ finitely cocomplete and F preserves finite coproducts, F* preserves finite colimits.

**(R 7.6)** Functor on finitely cocomplete preserves sifted iff filtered + reflexive coequalizers.

**(T 7.7)** Between cocomplete categories: preserves sifted iff filtered + reflexive coequalizers.

**(R 7.8)** Proofs of positive statements 2 and 3; statement 1 easy.

**(E 7.9)** Category with filtered colimits and reflexive coequalizers but not sifted colimits.

**(R 7.10)** Sind 𝒟 ≠ Ind(Rec 𝒟).

**(E 7.11)** Functor preserving filtered + reflexive coequalizers but not sifted.

**(E 7.12)** Set = Ind Rec 𝒩; Rec Ind 𝒩 ≄ Set.

**(H)** Adámek–Rosický (2001); Joyal; Lack–Rosický (2010); Adámek et al. (2010).

---

### Chapter 8 — Canonical theories

**(D 8.1)** Splitting of idempotent; idempotent complete.

**(R 8.2)** Splitting unique up to iso; self-dual.

**(E 8.3)** Categories with equalizers/coequalizers idempotent complete; full subcategory closed under retracts.

**(D 8.4)** Idempotent completion.

**(R 8.5)** 𝒞 idempotent complete iff E_Ic equivalence; Ic(𝒞^op) ≃ (Ic 𝒞)^op.

**(D 8.6)** Category of idempotents Ic 𝒞.

**(P 8.7)** E_Ic : 𝒞 → Ic 𝒞 is idempotent completion.

**(P 8.8)** Idempotent completion of 𝒞^op = absolutely presentables in Set^C.

**(C 8.9)** Y_𝒯 : 𝒯^op → (Alg 𝒯)_pp is idempotent completion. (Alg 𝒯)_pp ≃ Ic(𝒯^op).

**(C 8.10)** Set^C ≃ Set^D iff Ic C ≃ Ic D.

**(D 8.11)** Canonical algebraic theory = idempotent complete.

**(P 8.12)** Every algebraic category has canonical theory unique up to equivalence; dual of 𝒜_pp is canonical.

**(E 8.13)** Set: 𝒩; Ab: 𝒯_ab; Bool: dual of finite nonempty sets.

**(H)** Mitchell (1965); Bunge (1966); Elkins–Zilber (1976); Dukarm (1988).

---

### Chapter 9 — Algebraic functors

**(D 9.1)** Morphism of algebraic theories = finite-product-preserving functor.

**(N 9.2)** Alg M : Alg 𝒯₂ → Alg 𝒯₁.

**(P 9.3)** (1) Alg M preserves limits and sifted colimits; (2) has left adjoint M* preserving sifted colimits, making square commute.

**(D 9.4)** Algebraic functor = preserves limits and sifted colimits.

**(E 9.5)** Alg M; forgetful Ab → Set; hom-functor iff perfectly presentable; constant iff terminal; embedding Alg 𝒯 → Set^𝒯.

**(R 9.6)** Morphisms of canonical theories ↔ algebraic functors.

**(T 9.7)** Functor between algebraic categories algebraic iff left adjoint + preserves sifted colimits.

**(R 9.8)** Equivalent: preserves limits, filtered colimits, regular epimorphisms.

**(R 9.9)** Duality needs 2-categories; up to natural isomorphism.

**(R 9.10)** Primer on 2-categories.

**(D 9.11)** 2-categories Th, Th_c, ALG.

**(R 9.12)** Foundational caveat.

**(D 9.13)** 2-functor Alg : Th^op → ALG.

**(R 9.14)** Well-defined.

**(T 9.15)** Duality: ALG biequivalent to Th_c^op; Alg : Th_c^op → ALG biequivalence.

**(C 9.16)** G algebraic iff induced by morphism of canonical theories.

**(R 9.17)** Set as dualizing object: Alg 𝒯 = Th(𝒯, Set); 𝒜_pp^op ≃ Alg(𝒜, Set).

**(R 9.18)** Gabriel–Ulmer duality for lfp: Lex : LEX^op → LFP.

**(T 9.19)** LFP and LEX dually biequivalent.

**(H)** Lawvere (1963); Adámek et al. (2003); Gabriel–Ulmer (1971); Centazzo–Vitale (2002); Ehresmann (1963).

---

### Chapter 10 — Birkhoff's variety theorem

**(D 10.1)** Equation in 𝒯 = parallel pair u, v : s ⇒ t; algebra satisfies if Au = Av.

**(E 10.2)** [2] = [0] in 𝒯_ab; τ = σ in graphs.

**(R 10.3)** Closure of equations under composition and tupling.

**(D 10.4)** Congruence on 𝒯.

**(E 10.5)** Commutative law for monoids.

**(E 10.6)** Kernel congruence ≈_M.

**(R 10.7)** Order on congruences; intersection; generated congruence.

**(D 10.8)** Variety.

**(R 10.9)** Equational classes terminology.

**(E 10.10)** x+x=0 in Ab; loops in Graph; Set×Set example.

**(R 10.11)** Variety specified by congruence.

**(N 10.12)** 𝒯/∼.

**(R 10.13)** 𝒯/∼ has finite products; factorization through Q; kernel congruence.

**(P 10.14)** Alg Q full faithful and injective on objects.

**(C 10.15)** Every variety is algebraic.

**(P 10.16)** Variety closed under products, subalgebras, regular quotients, sifted colimits.

**(C 10.17)** Closed under limits and sifted colimits.

**(E 10.18)** Not every full subcategory closed under limits and sifted colimits is a variety.

**(R 10.19)** Full essentially surjective morphism of theories → full faithful Alg M; converse fails.

**(D 10.20)** Regular epireflective subcategory.

**(C 10.21)** Variety is regular epireflective closed under regular quotients and directed unions.

**(T 10.22)** Birkhoff's variety theorem: variety iff closed under products, subalgebras, regular quotients, directed unions.

**(E 10.23)** Directed unions cannot be omitted.

**(C 10.24)** Variety iff regular epireflective closed under regular quotients and directed unions.

**(E 10.25)** Ab_tf not a variety.

**(H)** Birkhoff (1935); Adámek et al. (2010); Adámek–Porst (1998); Pedicchio–Vitale (2000).

---

## Part II — Concrete Algebraic Categories (Ch. 11–14)

### Chapter 11 — One-sorted algebraic categories

**(E 11.1)** 𝒩 theory of sets; projections π_i^n.

**(R 11.2)** Theory morphism T : 𝒩 → 𝒯 ↔ object X with chosen finite powers.

**(D 11.3)** One-sorted algebraic theory (𝒯, T); morphism M with M·T₁ = T₂.

**(R 11.4)** Morphisms preserve finite products automatically; equivalent to finitary monads on Set (Thm A.37); nonstrict version in Appendix C.

**(E 11.5)** 𝒯_ab as one-sorted theory.

**(R 11.6)** Alg T forgetful functor.

**(E 11.7)** Alg 𝒯_ab ≃ Ab but not isomorphic; not amnestic.

**(P 11.8)** Alg T faithful, algebraic, conservative.

**(C 11.9)** Preserves and reflects limits, sifted colimits, monos, regular epis.

**(R 11.10)** Subalgebra generated by X.

**(R 11.11)** Need concrete equivalence.

**(D 11.12)** Concrete equivalence.

**(D 11.13)** One-sorted algebraic category.

**(R 11.14)** Nonstrict version in Appendix C.

**(E 11.15)** Ab as one-sorted algebraic.

**(R 11.16)** Subcategories concrete.

**(P 11.17)** Variety of one-sorted theory is one-sorted algebraic.

**(E 11.18)** Graph not one-sorted algebraic.

**(L 11.19)** Terminal in one-sorted has no nontrivial subobjects.

**(E 11.20)** RGraph is one-sorted algebraic.

**(R 11.21)** Left adjoint F_T; free algebras; F_T X = ∐_X Y_𝒯(1).

**(C 11.22)** 𝒯^op ≃ finitely generated free algebras.

**(R 11.23)** Theory 𝒯̄ with 𝒯̄(k, n) = (UF_T k)^n; examples: Ab, monoids; theory from object in category with finite coproducts.

**(R 11.24)** Construction from left adjoint F ⊣ U.

**(E 11.25)** 𝒯_ab induced adjunction = free abelian groups.

**(P 11.26)** Free = coproducts of representables; every algebra regular quotient of free; regular projectives = retracts of free.

**(R 11.27)** Two senses of finitely generated.

**(P 11.28)** Finitely generated free = representable = free finitely generated object; perfectly presentable = retracts; finitely presentable = coequalizers of reflexive pairs between f.g. free.

**(R 11.29)** Subalgebra generated by X is regular quotient of F_T X.

**(P 11.30)** Finitely generated TFAE: (1) f.g.; (2) regular quotient of f.g. free; (3) finite X not in proper subalgebra.

**(R 11.31)** Congruence on algebra; generated congruence.

**(L 11.32)** Congruence generated by image of ⟨u,v⟩ = that generated by ⟨u₁η_X, v₁η_X⟩.

**(C 11.33)** A finitely presentable iff coequalizer R ⇉ F_T Y → A with Y finite, R f.g. congruence.

**(P 11.34)** One-sorted: closure under products, subalgebras, regular quotients ⇒ variety (directed unions automatic).

**(D 11.35)** 2-categories Th¹, ALG¹.

**(R 11.36)** 1-cells faithful conservative algebraic.

**(D 11.37)** 2-functor Alg¹.

**(T 11.38)** One-sorted duality: ALG¹ biequivalent to (Th¹)^op.

**(R 11.39)** Uniquely transportable version in Appendix C.

**(H)** Lawvere (1963); Appendix A; Appendix C.

---

### Chapter 12 — Algebras for an endofunctor

**(R 12.1)** H-algebra; H-Alg; U_H.

**(R 12.2)** Initial chain; initial H-algebra.

**(L 12.3)** Initial H-algebra is initial.

**(E 12.4)** Initial H_Σ-algebra = finite Σ-trees.

**(R 12.5)** Free H-algebras.

**(P 12.6)** Free H-algebra on X = initial algebra for H(−)+X.

**(C 12.7)** Free H-algebra = colimit of chain.

**(N 12.8)** F_H, F_Σ.

**(E 12.9)** Free H_Σ-algebra = finite Σ-trees on X.

**(E 12.10)** Binary operation: binary trees.

**(E 12.11)** Commutative binary operation: unordered binary trees.

**(P 12.12)** H-Alg has limits and sifted colimits preserved by U_H.

**(T 12.13)** H-Alg cocomplete; regular factorizations preserved by U_H.

**(R 12.14)** Quotients of functors.

**(T 12.15)** H finitary iff quotient of polynomial iff every element lies in image of finite subset.

**(R 12.16)** H-Alg = equational category of Σ-algebras.

**(R 12.17)** Generalization to cocomplete categories.

**(H)** Lambek (1968); Adámek (1974, 1977); Barr (1970).

---

### Chapter 13 — Equational categories of Σ-algebras

**(R 13.1)** Free Σ-algebra F_Σ X = Σ-term algebra.

**(N 13.2)** One-sorted theory (𝒯_Σ, T_Σ).

**(L 13.3)** Σ-Alg ≃ Alg 𝒯_Σ concretely.

**(D 13.4)** Morphism of signatures.

**(D 13.5)** Signature 𝒞(𝒯, T).

**(E 13.6)** η_Σ : Σ → 𝒞(𝒯_Σ, T_Σ).

**(P 13.7)** (𝒯_Σ, T_Σ) free on Σ.

**(R 13.8)** ε : 𝒯_{𝒞(𝒯,T)} → (𝒯, T) full; (𝒯, T) quotient; Alg T variety.

**(R 13.9)** Classical equations ↔ pairs in 𝒯_Σ.

**(D 13.10)** Equational category (Σ, E)-Alg; equational theory.

**(T 13.11)** One-sorted algebraic = equational categories.

**(E 13.12)** Semigroups.

**(E 13.13)** Abelian groups.

**(E 13.14)** Monoids.

**(E 13.15)** M-sets.

**(D 13.16)** Amnestic, transportable, uniquely transportable.

**(E 13.17)** Alg T transportable not uniquely; Σ-Alg uniquely; H-Alg uniquely.

**(R 13.18)** Transportable + amnestic ⇔ uniquely transportable; transportability not invariant under concrete equivalence; converse of 13.17.2 in 13.21.

**(D 13.19)** Concrete isomorphism.

**(L 13.20)** Concrete equivalence between uniquely transportable = concrete isomorphism.

**(C 13.21)** Uniquely transportable one-sorted algebraic = equational categories.

**(T 13.22)** Birkhoff for Σ-algebras: equational iff closed under products, subalgebras, regular quotients.

**(P 13.23)** H-Alg for H quotient of H_Σ ≃ equational category of Σ-algebras.

**(R 13.24)** Finitely generated / finitely presentable Σ-algebras.

**(P 13.25)** Finitely generated = regular quotient of f.g. free.

**(P 13.26)** Finitely presentable = regular quotient of f.g. free modulo f.g. congruence.

**(H)** Birkhoff (1935); Cohn (1965); Grätzer (2008); Adámek et al. (2009).

---

### Chapter 14 — S-sorted algebraic categories

**(N 14.1)** S* category of words.

**(E 14.2)** 𝒩 = {s}*.

**(R 14.3)** S* theory of Set^S; Y_{S*}.

**(D 14.4)** S-sorted algebraic theory (𝒯, T); morphism M with M·T₁ = T₂.

**(R 14.5)** Same remarks as 11.3–11.4.

**(E 14.6)** Graph; Set^C.

**(R 14.7)** Alg T forgetful to Set^S.

**(P 14.8)** Alg T faithful, algebraic, conservative.

**(R 14.9)** Concrete equivalence over Set^S.

**(D 14.10)** S-sorted algebraic category.

**(P 14.11)** Variety of S-sorted theory is S-sorted algebraic.

**(R 14.12)** Left adjoint F_T; free algebras.

**(C 14.13)** 𝒯^op ≃ f.g. free algebras.

**(R 14.14)** Generalization of 11.26–11.33.

**(T 14.15)** S-sorted duality: ALG^S biequivalent to (Th^S)^op.

**(D 14.16)** Σ-algebra; Σ-homomorphism; Σ-Alg; U_Σ.

**(E 14.17)** Graph; empty signature; automata; stacks.

**(R 14.18)** F_Σ; theory (𝒯_Σ, T_Σ); equations.

**(D 14.19)** Equation ∀x₀…∀x_{n−1}(t = t′).

**(E 14.20)** Graph example; stack equations.

**(E 14.21)** Infinite quantification needed for 10.23.

**(D 14.22)** S-sorted equational category.

**(R 14.23)** Birkhoff for S-sorted; finite S ⇒ directed unions automatic.

**(E 14.24)** One-sorted theories form equational category.

**(R 14.25)** Clone.

**(E 14.26)** S-sorted theories form equational category.

**(E 14.27)** Modules over variable rings.

**(P 14.28)** (1) S-sorted algebraic = S-sorted equational; (2) uniquely transportable = S-sorted equational.

**(R 14.29)** Abstract data types.

**(E 14.30)** Natural numbers; stacks.

**(R 14.31)** H_Σ : Set^S → Set^S.

**(P 14.32)** Every finitary H on Set^S is quotient of polynomial; H-Alg ≃ equational category.

**(R 14.33)** (1) Converse fails for infinite S; (2) holds for finite S.

**(H)** Wechler (1992); Higgins (1963–64); Bénabou (1968).

---

## Part III — Special Topics (Ch. 15–18)

### Chapter 15 — Morita equivalence

**(E 15.1)** 𝒩 and 𝒯₂ have equivalent algebra categories.

**(D 15.2)** Morita equivalent algebraic theories.

**(E 15.3)** Matrix ring R^[k]; idempotent modification uRu; Morita's theorem.

**(D 15.4)** Matrix theory 𝒯^[k]; pseudoinvertible idempotent.

**(R 15.5)** Well-defined; products.

**(T 15.6)** Matrix theories and idempotent modifications Morita equivalent to 𝒯.

**(T 15.7)** S Morita equivalent to T iff S ≃ uT^[k]u for pseudoinvertible idempotent u.

**(E 15.8)** All one-sorted theories of Set are 𝒯_k.

**(E 15.9)** Theories of R-Mod ↔ Morita-equivalent rings.

**(E 15.10)** Monoids: only one operation; unary theories.

**(R 15.11)** S-sorted version.

**(R 15.12)** Eilenberg–Watts theorem.

**(L 15.13)** Y_𝒯 : 𝒯^op → Alg 𝒯 free colimit completion conservative w.r.t. finite coproducts.

**(D 15.14)** Bimodule M : 𝒯 ⇒ 𝒮.

**(R 15.15)** Y_𝒯 bimodule; composition.

**(D 15.16)** 2-categories Th_bim, ALG_colim.

**(C 15.17)** (1) Th_bim ≃ ALG_colim; (2) Morita equivalence via bimodules.

**(H)** Morita (1958); Dukarm (1988); Borceux–Vitale (1994); Adámek et al. (2006); Banaschewski (1972); McKenzie (1996); Porst (2000); Eilenberg (1961); Watts (1960); Bass (1968).

---

### Chapter 16 — Free exact categories

**(L 16.1)** Exact category: regular epi factorization; regular = strong = extremal.

**(C 16.2)** Uniqueness; composition; cancellation; iso.

**(L 16.3)** Products of regular epis; pullback of equalizers; pullback of relations.

**(R 16.4)** Limit diagram for 16.24.

**(D 16.5)** Regular projective cover.

**(D 16.6)** Exact functor.

**(D 16.7)** Weak limit.

**(L 16.8)** Regular projective cover ⇒ weak finite limits.

**(D 16.9)** Left covering functor.

**(R 16.10)** Independence of weak limit.

**(E 16.11)** Preservation of finite limits ⇒ left covering; regular projective cover left covering; composition with exact.

**(E 16.12)** Yoneda is left covering.

**(R 16.13)** Left-covering vs. preservation of weak finite limits; rings example.

**(D 16.14)** Pseudoequivalence.

**(R 16.15)** Independence of weak pullback; regular factorization of parallel pair; equivalence relations vs. pseudoequivalences.

**(L 16.16)** Left covering functor: pseudoequivalence → equivalence relation.

**(R 16.17)** Left covering w.r.t. weak finite products + weak equalizers.

**(L 16.18)** Left covering iff w.r.t. weak finite products + weak equalizers.

**(L 16.19)** Left covering preserves finite jointly monomorphic sources.

**(L 16.20)** Finite limits + exact ⇒ left covering iff preserves finite limits.

**(D 16.21)** Free exact completion.

**(R 16.22)** Universal property via equivalence of functor categories.

**(R 16.23)** Reconstruction via pseudoequivalences.

**(T 16.24)** Regular projective cover ⇒ free exact completion.

**(C 16.25)** (1) G·I ≃ G′·I ⇒ G ≃ G′; (2) equivalence P ≃ P′ extends.

**(R 16.26)** K exact if preserves coequalizers of equivalence relations and K·I left covering.

**(C 16.27)** Algebraic 𝒜: inclusion of regular projectives is free exact completion.

**(H)** Carboni–Celia Magno (1982); Carboni–Vitale (1998); Gran–Vitale (1998).

---

### Chapter 17 — Exact completion and reflexive-coequalizer completion

**(D 17.1)** P_ex objects = pseudoequivalences; premorphisms; morphisms = equivalence classes.

**(N 17.2)** Γ : P → P_ex.

**(R 17.3)** Equivalence relation; composition; Γ full faithful; size.

**(R 17.4)** Homotopy interpretation.

**(L 17.5)** P′_ex = regular quotients of representables modulo pseudoequivalences.

**(R 17.6)** P′_ex.

**(L 17.7)** Equivalence E : P_ex → P′_ex with E·Γ = Y_{P^op}.

**(P 17.8)** Γ : P → P_ex left covering into exact category; regular projective cover.

**(C 17.9)** Γ free exact completion.

**(R 17.10)** A ≃ (A_rp)_ex ≃ (FCSum(A_pp))_ex; A_rp ≃ Ic(FCSum(A_pp)).

**(P 17.11)** Y_𝒯 : 𝒯^op → (Alg 𝒯)_fp free completion under finite colimits conservative w.r.t. finite coproducts.

**(D 17.12)** Rec C construction.

**(R 17.13)** Category structure; independence of finite coproducts.

**(L 17.14)** Rec C has finite colimits; E_Rec preserves finite coproducts.

**(L 17.15)** Coequalization by w.

**(R 17.16)** Reflexive coequalizer in Rec C.

**(P 17.17)** E_Rec free completion under finite colimits conservative w.r.t. finite coproducts.

**(C 17.18)** Ind(Rec C) ≃ Sind C.

**(H)** Pitts (1996); Bunge–Carboni (1995); Pedicchio–Rosický (1999); Rosický–Vitale (2001).

---

### Chapter 18 — Finitary localizations of algebraic categories

**(T 18.1)** F : E → A preserving finite limits + filtered colimits preserves reflexive coequalizers iff preserves regular epimorphisms.

**(C 18.2)** Cocomplete exact E: F preserves sifted iff filtered + regular epis.

**(C 18.3)** In cocomplete exact: perfectly presentable = finitely presentable + regular projective.

**(C 18.4)** 𝒜 algebraic iff cocomplete + exact + strong generator of finitely presentable regular projectives.

**(L 18.5)** Well-powered exact + regular projective cover with coproducts ⇒ cocomplete.

**(C 18.6)** 𝒜 algebraic iff exact + strong generator of f.p. regular projectives with coproducts.

**(D 18.7)** Localization; finitary localization.

**(R 18.8)** Notation A ⇄ B.

**(L 18.9)** (1) Finitely presentable pulled back; (2) localization of exact is exact.

**(T 18.10)** Finitary localizations of algebraic = exact lfp.

**(R 18.11)** Alternative proof of 10.24 without Birkhoff.

**(H)** Gabriel (1962); Popesco–Gabriel (1964); Vitale (1996, 1998); Adámek et al. (2001b); Roos (1965); Lawvere (1963).

---

## Appendices

### Appendix A — Monads

**(D A.1)** Monad; finitary monad.

**(E A.2)** MX = X + 1; word monad.

**(E A.3)** Monad from adjunction.

**(E A.4)** One-sorted algebraic category → finitary monad; S-sorted; free monad H*.

**(R A.5)** Canonical M-algebra on UA.

**(D A.6)** Eilenberg–Moore algebra; K^M.

**(R A.7)** Concrete over K; uniquely transportable.

**(E A.8)** Id; pointed sets; monoids.

**(E A.9)** Free Eilenberg–Moore algebras.

**(C A.10)** Every monad induced by adjunction.

**(D A.11)** Comparison functor K.

**(R A.12)** Uniqueness.

**(E A.13)** Monoids; posets; H*-Alg ≃ H-Alg.

**(D A.14)** Monadic.

**(R A.15)** Independence of left adjoint.

**(D A.16)** Absolute coequalizer.

**(E A.17)** Absolute coequalizer for Eilenberg–Moore algebra.

**(T A.18)** Beck's theorem.

**(P A.19)** Equational categories monadic.

**(E A.20)** Pointed sets, monoids, groups, abelian groups monadic; Alg 𝒯 generally not amnestic.

**(T A.21)** Equational categories = Set^M for finitary monads.

**(C A.22)** One-sorted algebraic = Set^M for finitary monads.

**(C A.23)** Set^M cocomplete; U_M preserves sifted colimits.

**(D A.24)** Monad morphism.

**(P A.25)** Monad morphism ↔ concrete functor K^M′ → K^M.

**(C A.26)** Finitary monads on Set ≃^op finitary monadic categories.

**(C A.27)** H* free on H.

**(R A.28)** Kleisli category.

**(D A.29)** Kleisli category.

**(E A.30)** Partial functions.

**(N A.31)** K_M, J_M.

**(L A.32)** J_M ⊣ U_M·K_M; comparison functor full faithful.

**(T A.33)** Monad morphisms ↔ functors K_M → K_M′ commuting with J.

**(R A.34)** Finitary monad on Set determined by restriction to ℕ^op.

**(N A.35)** Set^f_M; J^f_M.

**(C A.36)** Restriction to ℕ^op.

**(T A.37)** Th¹ ≃ finitary monads on Set.

**(R A.38)** Quasi-inverse.

**(R A.39)** S-sorted analogue.

**(T A.40)** S-sorted equational = (Set^S)^M for finitary monads.

**(T A.41)** Th^S ≃ finitary monads on Set^S.

---

### Appendix B — Abelian categories

**(R B.1)** Zero object; biproduct; preadditive; additive; abelian.

**(E B.2)** One-object preadditive = rings; Add[C, Ab].

**(T B.3)** Abelian algebraic = Add[C, Ab] for small additive C.

**(C B.4)** Abelian algebraic = additive cocomplete + strong generator of perfectly presentables.

**(R B.5)** Preadditive sufficient; Mat(C).

**(R B.6)** Perfectly presentable iff enriched hom preserves colimits.

**(E B.7)** ℤ perfectly presentable in Ab.

**(C B.8)** One-sorted abelian algebraic = R-Mod.

**(C B.9)** Finitary localizations of abelian algebraic = abelian lfp.

---

### Appendix C — More about dualities for one-sorted algebraic categories

**(D C.1)** Pseudoconcrete functor/equivalence.

**(D C.2)** Pseudomonadic.

**(T C.3)** Beck's theorem for pseudomonadic.

**(P C.4)** (Alg 𝒯, Alg T) pseudomonadic.

**(D C.5)** Pseudo-one-sorted algebraic category.

**(T C.6)** Characterization: cocomplete + conservative right adjoint preserving sifted colimits.

**(C C.7)** Pseudo-one-sorted algebraic = Set^M for finitary monads.

**(D C.8)** Pseudomorphism.

**(R C.9)** Pseudomorphisms preserve finite products.

**(T C.10)** Nonstrict one-sorted algebraic duality.

**(R C.11)** Duality via monads.

**(L C.12)** Transportable ⇒ pseudoconcrete functor ≃ concrete functor.

**(C C.13)** Transportable pseudo-one-sorted algebraic = one-sorted algebraic.

**(D C.14)** Categories Th¹, ALG¹, ALG¹_u.

**(L C.15)** Th¹ biequivalent to PsTh¹.

**(L C.16)** ALG¹_u biequivalent to PsALG¹.

**(N C.17)** Alg¹ : (PsTh¹)^op → PsALG¹.

**(T C.18)** One-sorted algebraic duality: ALG¹_u ≃ (Th¹)^op.

---

## Summary of the Book's Logical Architecture

Working as a senior mathematician, I note the book's structure is:

1. **Abstract layer (Ch. 1–10):** Algebraic theories as small finite-product categories; algebras as product-preserving functors; characterization theorems (6.9, 18.4); duality (9.15); Birkhoff (10.22).

2. **Concrete layer (Ch. 11–14):** One-sorted and S-sorted theories; concrete equivalences; equational categories; characterization via signatures.

3. **Special topics (Ch. 15–18):** Morita equivalence; free exact completions; reflexive-coequalizer completions; finitary localizations.

4. **Appendices:** Monads (A); abelian categories (B); nonstrict dualities (C).

The book contains **no** material on parsing, NLP, knowledge extraction, or ablation studies. Any application to those domains is interpretive, not extracted from the text.

---

## What Is *Not* in the Book (Explicitly)

- No probabilistic or statistical content.
- No neural networks or gradient-based learning.
- No natural language parsing.
- No knowledge graphs or extraction.
- No ablation study methodology.
- No empirical evaluation.
- No algorithms for practical computation.

The book is pure category theory and universal algebra.