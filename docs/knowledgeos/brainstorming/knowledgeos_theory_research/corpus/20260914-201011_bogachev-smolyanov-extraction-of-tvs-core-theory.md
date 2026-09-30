Based on the provided text from "Topological Vector Spaces and Their Applications" by V.I. Bogachev and O.G. Smolyanov, here is an extraction of the core theory of topological vector spaces.

### **Chapter 1: Introduction to the Theory of Topological Vector Spaces**

This chapter lays the groundwork, defining the fundamental objects and their basic properties.

#### **1.1. Linear Spaces and Topology**
*   **Topological Vector Space (TVS):** A vector space \(E\) over a topological field \(\mathbb{K}\) (usually \(\mathbb{R}\) or \(\mathbb{C}\)) equipped with a topology such that vector addition \((x, y) \mapsto x + y\) and scalar multiplication \((\lambda, x) \mapsto \lambda x\) are continuous.
*   **Key Concepts:**
    *   **Convex Sets:** A set \(V\) is convex if for any \(u, v \in V\) and \(t \in [0, 1]\), the point \(tu + (1-t)v\) is in \(V\).
    *   **Balanced (or Circled) Sets:** A set \(M\) is balanced if for any \(x \in M\) and any scalar \(\lambda\) with \(|\lambda| \le 1\), \(\lambda x \in M\).
    *   **Absorbing Sets:** A set \(A\) absorbs a set \(B\) if there exists \(r > 0\) such that \(kB \subset A\) for all \(|k| < r\). An absorbing set absorbs every singleton.
    *   **Seminorms and Norms:** A seminorm \(p\) satisfies \(p(kx) = |k|p(x)\) and \(p(x+y) \le p(x) + p(y)\). A norm is a seminorm where \(p(x) = 0\) implies \(x = 0\).
    *   **Quasi-norms and Pseudonorms:** More general functions that generate topologies, useful for non-locally convex spaces.

#### **1.2. Basic Definitions**
*   **Topology of a TVS:** The topology is translation-invariant. It is completely determined by a fundamental system of neighborhoods of zero. Any neighborhood of zero is an absorbing set.
*   **Properties of Neighborhoods of Zero:** A base of neighborhoods of zero can always be chosen to consist of closed, balanced sets. If the space is locally convex, it can consist of closed, convex, balanced sets.
*   **Construction of Vector Topologies:** A filter basis \(\mathcal{B}\) of balanced, absorbing sets satisfying \(V \in \mathcal{B} \implies \exists W \in \mathcal{B}: W+W \subset V\) defines a unique vector topology for which \(\mathcal{B}\) is a base of neighborhoods of zero.
*   **Locally Convex Space (LCS):** A TVS with a base of convex neighborhoods of zero. Every LCS has a base of neighborhoods of zero consisting of closed, convex, balanced, absorbing sets. The topology of an LCS is always defined by a family of seminorms.

#### **1.3. Examples**
*   **\(\mathbb{K}^T\) (Product Space):** The space of all functions from a set \(T\) to \(\mathbb{K}\), with the topology of pointwise convergence.
*   **Spaces defined by Seminorms:** A family of seminorms \(\mathcal{P}\) on a vector space \(E\) defines a locally convex topology.
*   **Quotient Spaces:** For a TVS \(E\) and a subspace \(E_1\), the quotient \(E/E_1\) is a TVS with the quotient topology. It is Hausdorff iff \(E_1\) is closed.
*   **Spaces of Functions:** Examples include \(\mathcal{K}(\mathbb{R}^n)\) (continuous functions with compact support), \(\mathcal{S}(\mathbb{R}^n)\) (Schwartz space of rapidly decreasing functions), \(\mathcal{D}(\mathbb{R}^n)\) (smooth functions with compact support), and \(\mathcal{E}(\mathbb{R}^n)\) (smooth functions).
*   **Weak Topology:** For a vector space \(E\) and a space of linear functionals \(G\), the weak topology \(\sigma(E, G)\) is the weakest topology making all functionals in \(G\) continuous. It is locally convex.

#### **1.4. Convex Sets**
*   **Interior and Closure:** For a convex set \(V\), its interior \(\mathring{V}\) and closure \(\overline{V}\) are also convex. If \(\mathring{V} \neq \emptyset\), then \(\overline{\mathring{V}} = \overline{V}\) and \(\mathring{\overline{V}} = \mathring{V}\).
*   **Minkowski Functional (Gauge):** For an absorbing, convex set \(A\), the Minkowski functional \(p_A(x) = \inf\{\lambda > 0: x \in \lambda A\}\) is a seminorm.
*   **Bounded Sets:** A set \(B\) is bounded if it is absorbed by every neighborhood of zero. In a locally convex space, this is equivalent to every continuous seminorm being bounded on \(B\).
*   **Theorem:** The topology of any locally convex space can be defined by a family of seminorms.

#### **1.5. Finite-Dimensional and Normable Spaces**
*   **Finite-Dimensional Spaces:** Any Hausdorff TVS of finite dimension \(n\) over a complete field is isomorphic to \(\mathbb{K}^n\). Any finite-dimensional subspace of a Hausdorff TVS is closed.
*   **Kolmogorov's Normability Criterion:** A TVS is normable if and only if it is Hausdorff and possesses a convex bounded neighborhood of zero.

#### **1.6. Metrizability**
*   **Theorem:** The topology of any TVS can be defined by a family of quasi-norms.
*   **Metrizability Criterion:** A TVS is metrizable if and only if it is Hausdorff and has a countable base of neighborhoods of zero. The metric can be chosen to be translation-invariant. For an LCS, this is equivalent to the topology being defined by a countable family of seminorms.

#### **1.7. Completeness and Completions**
*   **Cauchy Sequences, Nets, and Filters:** Generalizations of the concept of a fundamental sequence to arbitrary TVSs.
*   **Complete Space:** A space where every Cauchy filter (or net) converges.
*   **Completion:** Every Hausdorff TVS can be embedded as a dense subspace into a complete Hausdorff TVS, called its completion, which is unique up to isomorphism.

#### **1.8. Compact and Precompact Sets**
*   **Precompact (Totally Bounded) Sets:** A set is precompact if it can be covered by finitely many translates of any neighborhood of zero. Any precompact set is bounded.
*   **Compactness:** A set is compact if it is precompact and complete. In a locally convex space, the closed convex hull of a precompact set is precompact.
*   **Properties:** The convex hull, closed convex hull, and balanced hull of a precompact set in a locally convex space are precompact.

#### **1.9. Linear Operators**
*   **Continuity:** A linear operator between LCSs is continuous iff it is continuous at the origin, which is equivalent to a seminorm estimate.
*   **Bounded Operators:** A linear operator is bounded if it maps bounded sets to bounded sets. Continuous operators are bounded.
*   **Equicontinuity:** A set of operators is equicontinuous if it is uniformly continuous at the origin.

#### **1.10. The Hahn-Banach Theorem: Geometric Form**
*   **Kakutani's Theorem:** Two disjoint convex sets can be separated by a "gap" of two disjoint convex sets whose union is the whole space.
*   **Separation Theorem:** Two non-empty, disjoint convex sets, one with a non-empty interior, can be separated by a closed hyperplane.
*   **Strict Separation Theorem:** A non-empty compact convex set and a disjoint non-empty closed convex set can be strictly separated by a closed hyperplane.

#### **1.11. The Hahn-Banach Theorem: Analytic Form**
*   **Extension Theorem:** A linear functional defined on a subspace and dominated by a sublinear function can be extended to the whole space with the same domination.
*   **Norm-preserving Extension:** A continuous linear functional on a subspace of a normed space can be extended to the whole space with the same norm.
*   **Corollaries:** The dual space \(E'\) of a Hausdorff LCS separates points. For a point \(a\) not in a closed absolutely convex set \(B\), there exists a continuous linear functional \(f\) such that \(|f(a)| > \sup_{x \in B} |f(x)|\).

### **Chapter 2: Methods of Constructing Topological Vector Spaces**

This chapter focuses on building new spaces from existing ones.

#### **2.1 - 2.2. Projective Topologies and Limits**
*   **Projective Topology:** The weakest topology on a vector space \(E\) that makes a family of linear maps \(g_\alpha: E \to E_\alpha\) continuous.
*   **Projective Limit:** The space \(E\) with this topology. It is isomorphic to a subspace of the product \(\prod E_\alpha\).
*   **Examples:** Subspaces, products, and limits of inverse spectra.

#### **2.3 - 2.4. Inductive Topologies and Limits**
*   **Inductive Topology:** The strongest *locally convex* topology on \(E\) that makes a family of linear maps \(g_\alpha: E_\alpha \to E\) continuous.
*   **Inductive Limit:** The space \(E\) with this topology. It is isomorphic to a quotient of the direct sum \(\bigoplus E_\alpha\).
*   **Strict Inductive Limit:** An inductive limit of a sequence of spaces \(E_n\) where the topology of \(E_n\) is induced by \(E_{n+1}\). In this case, a set is bounded in the limit iff it is contained and bounded in some \(E_n\).

#### **2.5. Grothendieck's Construction (Banach Discs)**
*   **Banach Disc:** An absolutely convex, bounded set \(B\) such that the normed space \(E_B\) (the linear span of \(B\) with the Minkowski functional as norm) is complete.
*   **Theorem:** In any LCS, every barrel absorbs every Banach disc.
*   **Theorem:** For a metrizable LCS, any bounded set is contained in a Banach disc that induces the same topology on the original set.

#### **2.8 - 2.9. Tensor Products and Nuclear Spaces**
*   **Projective Tensor Product (\(E_1 \otimes_\pi E_2\)):** Uses the strongest locally convex topology making the canonical bilinear map continuous.
*   **Injective Tensor Product (\(E_1 \otimes_\epsilon E_2\)):** Uses a weaker topology.
*   **Nuclear Space:** A locally convex space where the canonical mapping from the space to a completion of a quotient by a neighborhood is nuclear for a base of neighborhoods. Equivalently, \(E \otimes_\pi F = E \otimes_\epsilon F\) for all LCS \(F\). Nuclear spaces have many nice properties (e.g., every bounded set is precompact).

### **Chapter 3: Duality**

This chapter explores the relationship between a space and its dual.

#### **3.1. Polars**
*   **Polar:** For \(A \subset E\), its polar is \(A^\circ = \{g \in G: |\langle g, x \rangle| \le 1 \ \forall x \in A\}\).
*   **Bipolar Theorem:** The bipolar \(A^{\circ\circ}\) is the \(\sigma(E, G)\)-closed absolutely convex hull of \(A\).
*   **Banach-Alaoglu-Bourbaki Theorem:** The polar of a neighborhood of zero in \(E\) is compact in the weak-* topology \(\sigma(E', E)\).

#### **3.2. Topologies Compatible with Duality**
*   **Compatible Topology:** A locally convex topology \(\tau\) on \(E\) is compatible with the duality \((E, G)\) if \((E, \tau)' = G\).
*   **Mackey-Arens Theorem:** Among all topologies compatible with a given duality, there exists a strongest one, called the Mackey topology \(\tau(E, G)\). It is the topology of uniform convergence on all absolutely convex \(\sigma(G, E)\)-compact subsets of \(G\).
*   **Mackey Space:** A space where the original topology is the Mackey topology \(\tau(E, E')\).
*   **Bounded Sets:** A set is bounded in the Mackey topology iff it is bounded in the weak topology.

#### **3.3 - 3.4. Adjoint Operators and Weak Compactness**
*   **Adjoint Operator:** For \(T: E \to G\), the adjoint \(T^*: G' \to E'\) is defined by \(\langle T^*g, x \rangle = \langle g, Tx \rangle\).
*   **Eberlein's Theorem:** A relatively countably compact set in the weak topology is relatively compact if its closed convex hull is complete in the Mackey topology.
*   **Eberlein-Smulian Theorem:** For a set in a Banach space, weak compactness, weak sequential compactness, and weak countable compactness are equivalent.

#### **3.5 - 3.6. Barrelled and Bornological Spaces**
*   **Barrelled Space:** A space where every barrel (closed, absolutely convex, absorbing set) is a neighborhood of zero. Every Baire LCS is barrelled.
*   **Bornological Space:** A space where every bounded linear map to any LCS is continuous. Every metrizable LCS is bornological.
*   **Relationships:** Barrelled \(\implies\) Mackey. Bornological \(\implies\) Mackey.

#### **3.7. The Strong Topology and Reflexivity**
*   **Strong Topology (\(\beta(E', E)\)):** The topology on \(E'\) of uniform convergence on all bounded subsets of \(E\).
*   **Semireflexive Space:** A space where \((E'_\beta)' = E\) (as vector spaces).
*   **Reflexive Space:** A semireflexive space where the strong topology \(\beta(E, E')\) coincides with the original topology.

#### **3.8 - 3.9. Completeness Criteria and Closed Graph Theorems**
*   **Completeness Criteria:** The strong dual \(E'_\beta\) is complete iff a linear functional on \(E\) whose restriction to every bounded set is continuous is itself continuous.
*   **Closed Graph Theorem:** If \(X\) is barrelled and \(Y\) is a Br-complete space, then any linear operator \(T: X \to Y\) with a closed graph is continuous. In particular, this holds if \(Y\) is a Fréchet space.

#### **3.10 - 3.11. Compact Operators and the Fredholm Alternative**
*   **Compact Operator:** An operator that maps a neighborhood of zero to a relatively compact set. They are continuous.
*   **Fredholm Alternative:** For an operator \(T = I - A\) where \(A\) is compact, the injectivity of \(T\) is equivalent to its surjectivity. The kernel of \(A - \lambda I\) is finite-dimensional for \(\lambda \neq 0\).

### **Chapter 4: Differential Calculus**

This chapter extends differentiation to infinite-dimensional spaces. The key is to define differentiability with respect to a system of sets.

#### **4.1 - 4.3. Differentiability and Continuity**
*   **Differentiability:** A map \(f\) is \(\sigma\)-differentiable at \(x_0\) if \(f(x_0+h) = f(x_0) + f'(x_0)h + r(h)\), where \(f'(x_0)\) is a continuous linear map and \(r\) is \(\sigma\)-small (i.e., \(r(th)/t \to 0\) uniformly on sets in \(\sigma\)).
*   **Systems of Sets:** Different choices of \(\sigma\) lead to different notions: Gâteaux (finite sets), Fréchet (bounded sets), Hadamard (compact sets), and sequential differentiability.
*   **Continuity:** Differentiability at a point implies continuity at that point if the space is a Fréchet-Urysohn space (e.g., metrizable).

#### **4.5 - 4.7. The Chain Rule, Mean Value Theorem, and Taylor's Formula**
*   **Chain Rule:** Valid for most notions of differentiability (e.g., Fréchet, Gâteaux, Hadamard).
*   **Mean Value Theorem:** For a differentiable map \(f: [a, b] \to G\), \(f(b) - f(a)\) lies in the closed convex hull of \(\{f'(\theta)(b-a): \theta \in (a, b)\}\).
*   **Taylor's Formula:** Provides a polynomial approximation with a remainder term, which can be in Lagrange or Peano form.

### **Chapter 5: Measures on Linear Spaces**

This chapter introduces measure theory on infinite-dimensional spaces.

#### **5.1 - 5.2. Cylindrical Sets and Measures on Topological Spaces**
*   **Cylindrical Sets:** Sets defined by a finite number of linear functionals.
*   **Radon Measure:** A Borel measure that is tight (inner regular with respect to compact sets).
*   **Souslin Sets:** A class of sets that are images of complete separable metric spaces. All Souslin sets are universally measurable.

#### **5.3 - 5.5. Weak Convergence, Cylindrical Measures, and the Fourier Transform**
*   **Weak Convergence:** Convergence of integrals against all bounded continuous functions.
*   **Cylindrical Measure:** An additive set function on the algebra of cylindrical sets, countably additive on each finite-dimensional sub-algebra.
*   **Fourier Transform:** For a cylindrical measure \(\mu\), \(\hat{\mu}(g) = \int e^{i\langle g, x \rangle} d\mu\).
*   **Kolmogorov's Theorem:** A consistent family of finite-dimensional distributions defines a unique cylindrical measure. This measure is countably additive on the generated \(\sigma\)-algebra if the space is large enough (e.g., \(\mathbb{R}^T\)).

#### **5.6 - 5.7. Covariance Operators, Means, and Gaussian Measures**
*   **Mean (Barycenter):** A vector \(m\) such that \(l(m) = \int l(x) d\mu(x)\) for all continuous linear functionals \(l\).
*   **Covariance Operator:** Defined by \(C_\mu(l_1, l_2) = \int l_1(x)l_2(x) d\mu - \int l_1 d\mu \int l_2 d\mu\).
*   **Gaussian Measure:** A measure where every pushforward by a continuous linear functional is a Gaussian measure on \(\mathbb{R}\).
*   **Cameron-Martin Space:** A Hilbert space \(H(\gamma)\) associated with a Gaussian measure \(\gamma\). Translations by vectors in \(H(\gamma)\) preserve the measure class, while translations by vectors outside it make the measures mutually singular.

#### **5.10 - 5.11. Sufficient Topologies and Countable Additivity**
*   **Sazonov Topology:** A locally convex topology defined by seminorms \(p_T(x) = \sqrt{(Tx, x)}\), where \(T\) is a non-negative, self-adjoint, nuclear operator on a Hilbert space.
*   **Gross-Sazonov Topology:** A stronger topology defined by "measurable" seminorms.
*   **Minlos-Sazonov Theorem:** A cylindrical measure on a Hilbert space is countably additive if and only if its Fourier transform is continuous in the Sazonov topology. This generalizes to nuclear spaces (Minlos' Theorem).