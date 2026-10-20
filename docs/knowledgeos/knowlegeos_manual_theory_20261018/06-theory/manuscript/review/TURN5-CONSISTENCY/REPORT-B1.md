# Report B1 (Opus): theory turn 5, commission B: §9.14, when pairs suffice

*2026-10-20. Reviewer B1, working alone. I did not read any other REPORT-*.md, or commission A's brief. I edited nothing except this file. Scratch scripts are outside the repository (`/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/b1_checks.py`, run with `python3 -I`).*

## 1. Verdict table

| Item | Verdict | Label | Key finding |
|---|---|---|---|
| R1 (a) bound | **holds** | Ded | I found no countermodel. Step 1 needs an indexed family (B1-06) |
| R1 (b) reduction, regions included | **holds** | Ded | an independent pair analyser matches the set-wise report (B1-04) |
| R1 (c) converse | **holds under realisability**, but its wording is loose | Ded | "a rule base in the class" is not defined; h must be defined over finite families (B1-07, B1-08) |
| R1, "applicability plays no part"; UNKNOWN extension | **holds** (bound) | Ded | the bound is on effect families only; three-valued applicability does not change effect sets |
| R2 product lemma | **holds only on a full product outcome space**; the proof skips one step | Ded | countermodel C3 (B1-02); the minimality step is missing (B1-09). Factors need not be non-empty |
| R3 h-values | **all correct**, with one small exception | Ded | for forbidden values on an infinite domain, "2" should be "≤ 2" (in fact no conflict arises) (B1-14) |
| R3 placements | **mixed** | Int | OAuth2 and TLS are fine, as Int. Trace links are fine (§2.0 L52). The design-check phase targets contradict §2.9 (B1-10). §2.3 (a) "linear arithmetic over thresholds" is a stretch (B1-10) |
| R4 h ≤ \|X\|, tight | **holds** | Ded | proof valid; the tightness example is right |
| R5 (a) cost | **count right; one sentence wrong** | Ded | "exponential only when h is unbounded" (B1-12) |
| R5 (b) §2.6 | **holds** for a single h = 2 class, with singles tested too | Ded | depends on B1-01 and B1-02 |
| R5 (c) | **the example is right; the general sentence is wrong** | Ded | a single forbidden-value rule does not raise h to k (B1-11) |
| R5 (d) analyser test | **refuted as stated** | Ded | the union of the listed h = 2 forms has unbounded h (B1-01); "iff" contradicts §9.14.4 (B1-03) |
| §2.3 (d) pointer bullet | **"iff" too strong**; its list is unsafe without the product premise | Ded | B1-02, B1-03 |
| §2.6 pointer | **holds** for one h = 2 class | Ded | B1-01 caveat |
| §9.14.4 "strongest counterexample" | **not the strongest** | Ded | C1/C7, C3 and C2 are stronger (B1-01, B1-02, B1-05) |
| §9.14.5 k-consistency distinction | **correct as stated**; the closest rival is missing | Ext/Int | relational m-consistency (B1-13) |
| `helly_check.py` | **reproduced byte-identically** (5 min 9 s); two weaknesses | model | check 3's comparison is tautological (B1-04); the caps contradict "exhaustive" (B1-15) |
| Corpus reading S1539 / S0922 / S0287 | **S0287 right; S1539 mostly right; S0922 placement wrong** | Src/Int | B1-05, B1-16 |
| Status "established; the application is the book's own" | **fair, with "No novelty is claimed"** | Ext | I could not verify outside works (no web access): U (B1-13) |
| Internal guarantees vs world assumptions | **one world assumption is missing** | Ded | the outcome space must be a product with no coupling constraint (B1-02) |

**Overall:** R1 and R4 survive. The interval proof, and every h-value but one, survive. R2 needs a stated premise. The applied claims (R5 (c), R5 (d), the §2.3 (d) "iff", the S0922 placement and the design-check placement) do not survive as written. One finding is critical: B1-01.

## 2. Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| B1-01 | **R5 (d)'s list of admitted forms is not an h = 2 class.** Helly numbers do not stay at 2 under union: two h = 2 forms on one attribute can together have unbounded h. On an infinite ordered domain (a violation count 0, 1, 2, …), thresholds (h = 2) and forbidden values (listed as "h = 2 if infinite") together give the family {x ≤ m−1, x ≠ 0, …, x ≠ m−1}. It is minimal with empty intersection and has m + 1 members, for every m. "Conjunctions of these" (x ≤ 3 ∧ x ≠ 1) have the same problem. This rule base meets §9.14.1's own first falsifier. | **critical** | C1: minimal of sizes 2, 3, 4, 5 for m = 1..4. C7: intervals ∪ point complements on 0..7 give h = 8 = \|X\|. The model already computes "interval or forbidden range" = 6 but the text does not report it. | State that h belongs to the **union** of the admitted forms on each attribute, and that R3's rows cannot be combined. Remove "forbidden values on … infinite domains" from R5 (d), or restrict it to attributes on which no threshold or required value is admitted. Add an R3 row: thresholds with forbidden values, h unbounded on infinite domains, h = n on n values. | R5 (d) and §2.3 (d)'s bullet name only unions with h = 2 proved. The new row has its proof. A model check of intervals ∪ complements is reported. |
| B1-02 | **R2 (and with it every "conjunction" row, R5 (d) and the §2.3 (d) list) needs the outcome space to be the full product of the attribute domains.** If outcomes are coupled (a schema constraint, "exactly one owner", background logic), an intersection of products can be empty without any factor intersection being empty. §9.14.6 does not list this world assumption. | **high** | C3: X = {0,1}³ minus (1,1,1), with three Boolean literals a = 1, b = 1, c = 1. Each factor class has h = 2; the three literals are minimal empty, so h ≥ 3. C5b: S1539's MCS-2 has size 3 only because of such a background coupling. | Add the premise "X = ∏ₐ Xₐ (no coupling constraint on outcomes), and X does not depend on the context" to R2. Add "every outcome-space constraint is itself a rule in the checked set, and counted in h" to §9.14.6 as a world assumption. | R2 states the premise. §9.14.6 lists the assumption. C3 or a similar example is given as the converse of the premise. |
| B1-03 | **The "iff" for a rule base contradicts §9.14.4.** R5 (d) says pairs-only checking "is sound for a rule base iff the base's admitted effect forms lie in an h = 2 class". The §2.3 (d) bullet says the same of "the rule base's effect class". §9.14.4 itself shows that a base with h = k can be pair-sound when its forbidden-value rules never apply together. For a base, only "if" holds. "Only if" holds for the language (every base over it), under realisability. | **high** | §9.14.4 against R5 (d) and §2.3 (d) L68 | Write: "sound for every base over the admitted language iff h ≤ 2 (the converse under realisability). For a given base, sound iff its largest pointwise-minimal conflicting set has at most 2 members." | Both places use "if" for bases and "iff" for languages, and cite R1 (c)'s premise. |
| B1-04 | **Check 3 does not test a pairs-only analyser.** `pairs_report` is the set-wise report filtered to \|S\| ≤ 2. So "pairs give exactly the set-wise report" is, in the model, the same statement as "no minimal set exceeds 2". It is not an independent test of R1 (b). | normal | `helly_check.py` L129–130 | Implement an independent analyser: at each point, test single rules for an empty effect and pairs for an empty intersection with both members non-empty. Compare it with `setwise_report`. My version (C6) gives 0 mismatches on 300 bases each, for required value k = 3 and intervals 0..5, so the conclusion survives. | Check 3 compares an independently computed pair report, and its output says so. |
| B1-05 | **The S0922 cardinality placement is wrong in the natural reading.** The source writes `ElectionCommitteeMembers ≥ 3` and `PrimaryOwner = 1` [S0922 L779–800], that is, counts. A count is derived from per-member Boolean outcomes, so a cardinality constraint is a linear constraint **across** attributes (R3's "linear" row). It is not a one-attribute threshold or required value. With "exactly one primary owner among a, b, c" and MUST_NOT owner(a), owner(b), owner(c), the four rules are pairwise satisfiable but not jointly: a minimal set of size 4. | **high** | C2. The R3 table has no cardinality row, although S0922 §29.25 names cardinality as a constraint class. | Place cardinality constraints over per-member outcomes in the linear row (h > 2). Say that "h = 2 on one attribute" holds only if the count itself is the sole outcome attribute and no rule constrains its parts. Add a cardinality row to R3. | The S0922 row and R3 carry the corrected placement and C2 (or an equivalent). |
| B1-06 | **Step 1 uses set notation {Eᵣ(c) : r ∈ S}.** That collapses rules with equal effect sets. With E₁ = E₂ = {a} and E₃ = {b}, the set {{a}, {b}} is a minimal empty family, but S = {r₁, r₂, r₃} is not minimal in conflict. The bound still holds, because the multiset (indexed) family is never minimal with a repeated member. | normal | §9.14.3 L38; §9.14.2 L22 ("several rules may share an effect set") | Write the indexed family (Eᵣ(c))_{r∈S} and note that a minimal one has no repeated member. | Step 1 uses the indexed family. |
| B1-07 | **The definition of h does not say "finite".** On an infinite domain, the complements of all points form an **infinite** minimal family with empty intersection, so the first sentence of the definition gives h = ∞ for forbidden values, against R3 ("2 if … infinite"). The "Equivalently" sentence is also garbled: "contains at most h members that already have empty intersection" should say "contains a subfamily of at most h members with empty intersection". | normal | §9.14.2 L23; R3 L60, L70 | Define h over finite subfamilies (rule bases are finite) and repair the equivalent form. | The definition says "finite subfamily", and the equivalent form is the standard one. |
| B1-08 | **R1 (c) and the second falsifier speak of "a rule base in the class", but 𝓔 is defined as a property of one rule base.** The converse needs a language-level object: the set of effect forms admitted, from which bases are drawn. Beyond realisability, R1 (c) needs nothing more than (i) h finite-witnessed (B1-07) and (ii) the realising rules being admissible norms (scope "everywhere", context-independent effect). Both are covered by the assumption line if it is read at the language level. | normal | §9.14.2 L22, L27; R1 (c) L34; §9.14.1 L17 | Define 𝓔(L) for an admitted language L. State R1 (a)–(b) for any base over L and R1 (c) for L. | R1 is stated over 𝓔(L), and the falsifiers use the same object. |
| B1-09 | **R2's proof stops one step short.** It shows that a sub-family of at most h(𝓔ₐ) members has empty intersection. It must then use the minimality of F to conclude F is that sub-family, so \|F\| ≤ h(𝓔ₐ). Answer to brief Q2: non-empty factors are **not** needed (an empty factor makes a one-member empty family, h ≥ 1, and the bound holds). The independence of the attributes (B1-02) **is** needed. | normal | §9.14.3 L48–50 | Add the minimality step; state the full-product premise. | The proof-step table (§3 below) or an equivalent is in the text. |
| B1-10 | **Two placements in R3 do not match the cited sections.** (i) §2.9 says the phase targets 592 → … → 50 are **enforcement thresholds**, and that "the obligation is fixed (zero violations)" (§2.9 L20). It also says enforcement consequence "plays no role in whether two rules conflict" (L41). So phase targets are not mandatory effects. The design checks' effects are per-rule prohibitions (MUST_NOT a pattern): Boolean, h = 2. The verdict survives; the reason and citation do not. (ii) §2.3 (a)'s "linear arithmetic over thresholds" is offered as an example of a decidable logic for applicability and effects. It does not say the book has multi-attribute linear effects. | normal | §2.9 L20–28, L41; §2.3 (a) L13–15 | Put the design checks under "Boolean prohibition" (Int), with target 0 as the obligation, and drop the phase targets. Mark the linear row as "admitted by §2.3 (a)'s decidable fragment; no book rule of this form found". | R3 cites §2.9 for the obligation only, and the linear row's example is labelled as a fragment, not a rule. |
| B1-11 | **R5 (c)'s general sentence is wrong.** "One forbidden-value rule on a k-valued attribute … raises that attribute's h to k." One complement added to required values leaves h = 2. Added to thresholds, it raises h to 3. It takes all k complements (the *form* admitted for every value, or one rule whose forbidden value varies over k contexts) to reach k. The three-root placement example is right, because it uses three rules. | normal | C4: [0,1], [1,2], comp(1) minimal; C4b: intervals on 0..4 + comp(2) give h = 3; C4c: singletons + comp(2) give h = 2 | Write "Admitting the forbidden-value form on a k-valued attribute raises h to k". | The sentence is corrected; the example is unchanged. |
| B1-12 | **R5 (a): "the exponential 2ⁿ is the cost only when h is unbounded" is inaccurate.** On a finite outcome space h ≤ \|X\| is always bounded (R4), yet for propositional effects on m attributes, h = 2ᵐ ≥ n in practice, and the count is Σᵢ≤ₙ C(n, i) = 2ⁿ − 1. The count also measures subset tests, each of which is §2.3 (d)'s ∃∀ question, not a constant-cost test. | normal | R5 (a) L93; §2.3 (d) L70 | Write: "Σ_{i ≤ min(h, n)} C(n, i) subset tests, each an ∃∀ question; polynomial in n when h is a fixed constant small against n". | R5 (a) is reworded so. |
| B1-13 | **Prior work: the closest rival is missing, and one Ext wording needs checking.** (i) The closest CSP notion to R1 is not k-consistency (on variables) but **relational m-consistency**, consistency of every m constraints (Dechter and van Beek, "Local and global relational consistency", 1997, from my own knowledge, not verified here). Also possibly relevant: work on the Helly property and the satisfiability of formulas over set families (Chepoi, Creignou, Hermann, Salzer, c. 2010, from my knowledge, not verified). (ii) §9.14.5 says Jeavons, Cohen and Cooper 1998 "characterise" the constraint types. My recollection is that they give a **sufficient** condition (closure under a near-unanimity operation). (iii) I had no tool for public bibliographic pages, so EXT-072–075 are U for me. Their details match my recollection (Int, not a check). | normal | §9.14.5 L104–106; EXTERNAL-SOURCES L49–52 | Check (i)–(ii) on publisher or bibliographic pages. Add relational m-consistency as a rival, or say it was not searched. Replace "characterise" with "give a condition under which" unless the abstract says "characterise". | §9.14.5 names the rival (or its absence), with a verified citation, and the Jeavons et al. verb matches the abstract. |
| B1-14 | **Small exactness points.** (i) Forbidden values on an infinite domain have no empty finite family at all, so h ≤ 1 (0 without ∅), not "2". (ii) The §2.3 (d) bullet says "allowed sets" fail; they fail only for k ≥ 3. (iii) R3's last row says "disjunctions … up to 2ⁿ". That is tight for clauses of length n, not for binary disjunctions such as (p ∨ q) when n ≥ 3. | normal | R3 L60, L66; §2.3 (d) L68 | Write "≤ 2", "allowed sets on three or more values", and "clauses of length up to n". | The three phrases are corrected. |
| B1-15 | **The model is called "exhaustive", but several searches are capped.** Boxes (cap 4), partial assignments (cap 4), lattice half-planes (cap 5 on \|X\| = 16; the assertion is only 3 ≤ h ≤ 4) and the products in check 2 (cap 4 on \|X\| ≤ 9) are not exhaustive. R2, Doignon and R4 make the printed values correct, but the model does not show them independently. "In every case" in check 2 holds only up to the cap. | normal | `helly_check.py` L87, L91, L99, L114; §9.14.3 L51, L76 | Say "exhaustive up to the stated cap", give the caps, and say where the theorem (not the model) closes the gap. | The text gives the caps. |
| B1-16 | **S1539 is read correctly in the main, but two descriptions overreach.** (i) "Its members are quantified implications": only A1 is an implication. A2 is a negated atom; A3 is an existential (a literal under the abstraction). (ii) {A1, A2, A3} is unsatisfiable only with the bridge "¬Knowable(Q) ⇒ ¬Provable(…)", which the source treats as logic [S1539 L113–114]. Taken as an explicit constraint, the minimal set has 4 members. Taken as a restriction of the outcome space, the set is 3, through exactly the coupling of B1-02. (iii) These are theory commitments, not rules with applicability and an outcome. "The corpus's own norms are not in an h = 2 class" is Int, not Src. The other readings check out: the five MCS; MCS-1, 3, 4 and 5 as pairs by their headings; L116; L204; L7–8. (The source's "ten conflicting commitments", L203, does not add up to 3 + 4 × 2 = 11. That is the source's own inconsistency, not the book's.) | normal | S1539 L105–116, L203–204; C5, C5b | Reword: "A1 is an implication (a clause); with the bridge as background logic, the three form a minimal set of size 3 (with the bridge as a fourth commitment, size 4)". Mark the relation column as Int. | The S1539 row says so. |

## 3. Proof-step tables (derivations judged adequate, with the corrections applied)

**R1 (a)–(b)** (adequate with B1-06 and B1-07)

| Step | Claim | Justification | Check |
|---|---|---|---|
| 1 | S ⊆ ℛ(c, t) is minimal in conflict iff the indexed family (Eᵣ(c))_{r∈S} has empty intersection and every proper sub-family does not | §2.3 (d) L65; every subset of applying rules applies | valid; effective applicability A′ enters only through ℛ(c, t) |
| 2 | a minimal indexed family has no repeated member, and its size ≤ h | the definition of h over finite subfamilies | valid |
| 3 | \|S\| ≤ h | 1, 2 | valid |
| 4 | the minimal sets at (c, t) are among the ≤ h subsets of ℛ(c, t); a region is per set | 3; §2.3 (d) | valid |
| 5 | testing every subset of size ≤ h, with minimality, reproduces the report | 4 | valid; confirmed by C6 for h = 2 |
| 6 (UNKNOWN) | under §2.8 applicability, effect sets are independent of the ⊥-completion, so a possibly-conflicting set's minimal sub-families are still ≤ h | §2.8.4 supervaluation; effects depend on c, not on scope | valid for a fully described c; unknown cᵢ is still open (§9.14.9, L-13) |

**Interval, h = 2** (adequate; the step through uᵢ is not needed)

| Step | Claim | Justification |
|---|---|---|
| 1 | finite family I₁…Iₘ of convex subsets of a total order, pairwise meeting; choose pᵢⱼ ∈ Iᵢ ∩ Iⱼ (pᵢᵢ ∈ Iᵢ) | hypothesis |
| 2 | lᵢ = minⱼ pᵢⱼ ∈ Iᵢ | finite min of points of Iᵢ |
| 3 | x = maxᵢ lᵢ = l_k ≤ p_ki for every i | definition of l_k |
| 4 | lᵢ ≤ x ≤ p_ki, with lᵢ, p_ki ∈ Iᵢ | 2, 3; p_ki ∈ I_k ∩ Iᵢ |
| 5 | x ∈ Iᵢ for every i | convexity |

**R2, product lemma** (adequate with B1-02 and B1-09)

| Step | Claim | Justification |
|---|---|---|
| 0 | premise: X = ∏ₐ Xₐ, with each effect set ∏ₐ Eₐ | added (B1-02) |
| 1 | ⋂ᵢ ∏ₐ Eᵢₐ = ∏ₐ ⋂ᵢ Eᵢₐ | set algebra on a full product |
| 2 | F minimal empty ⇒ some a with ⋂_{i∈F} Eᵢₐ = ∅ | a product is empty iff a factor is |
| 3 | some G ⊆ F, \|G\| ≤ h(𝓔ₐ), with ⋂_{i∈G} Eᵢₐ = ∅ | definition of h(𝓔ₐ) (multiset; empty factors allowed) |
| 4 | ⋂_{i∈G} ∏ Eᵢ = ∅, so G = F by the minimality of F | 1, 3; minimality (added, B1-09) |
| 5 | \|F\| ≤ maxₐ h(𝓔ₐ) | 4 |

**R4, h ≤ \|X\|** (adequate)

| Step | Claim | Justification |
|---|---|---|
| 1 | F minimal empty; for A ∈ F, choose x_A ∈ ⋂(F ∖ {A}) | minimality (for \|F\| = 1, ⋂∅ = X, X ≠ ∅) |
| 2 | x_A ∉ A | ⋂F = ∅ |
| 3 | A ≠ B ⇒ x_A ∈ B, x_B ∉ B ⇒ x_A ≠ x_B | 1, 2 |
| 4 | A ↦ x_A is injective, so \|F\| ≤ \|X\| | 3 |
| 5 | tight: {X ∖ {x}}ₓ is minimal empty; on n Booleans each is a length-n clause, so h = 2ⁿ | direct; check 4 (m = 2..5) |

**Linear tightness example (d = 2)** (adequate). The witnesses (0, 0), (0, −1) and (−1, 0) satisfy their pairs, and the sum of the three inequalities gives 0 ≤ −1. I re-derived this by hand and check 5 confirms it.

## 4. Answers to the brief's questions (short)

1. **R1.** Step 1 is right with an indexed family (B1-06). "Applicability plays no part" is true of the bound. The UNKNOWN extension is sound for fully described contexts. Beyond realisability, the converse needs only a finite definition of h and a language-level class (B1-07, B1-08).
2. **R2.** The lemma is correct only on a full product outcome space (B1-02). Non-empty factors are not needed. The proof needs the minimality step (B1-09).
3. **R3.** Every h is correct, except "2" for an infinite forbidden-value domain, which should be "≤ 2" (B1-14). The placements:
   - OAuth2 and TLS: Int, acceptable, but h depends on encoding the method as one k-valued attribute rather than k Booleans (and the Boolean encoding needs the product premise, B1-02);
   - trace links: Int, cited correctly (§2.0 L52);
   - design checks: miscited (B1-10);
   - §2.3 (a) linear: a fragment, not a book rule (B1-10).
4. **R4.** The proof is valid and the tightness example is right.
5. **R5.**
   - (a) The count is right, but one sentence is wrong (B1-12).
   - (b) Holds for one h = 2 class, singles included.
   - (c) The general sentence is wrong; the example is right (B1-11).
   - (d) Refuted (B1-01, B1-03).
6. **§9.14.4.** It is not the strongest counterexample, and it is a remark about class versus base rather than a counterexample. Stronger: C1/C7 (against R5 (d)), C3 (against R2 and the "conjunction" rows), C2 (against the S0922 placement). I found no countermodel to R1 (a). It is valid as mathematics; it fails only under the set notation, if read literally.
7. **§9.14.5.** The k-consistency distinction is correct. Relational m-consistency is the missing, closer rival (B1-13).
8. **Model.** The Helly search computes what the docstring says: distinct members, and a repeated member never helps. It is capped, which is not stated (B1-15). Check 3's comparison is tautological (B1-04). The run reproduces byte-identically.
9. **Prior work.**
   - S0287 is read right.
   - S1539: B1-16.
   - S0922: B1-05. The "asserted PASS without a solver" reading matches §29.56–64. I did not open S1513.
   - The outside works are used as benchmarks only. The status is fair given "No novelty is claimed". My Ext checks are U (B1-13).

## 5. Coverage

- **Read in full:**
  - §9.14;
  - §2.3;
  - §2.6;
  - §2.9;
  - §2.8.4 and §2.8.4a, and §2.8.7's "adding a value";
  - the relevant lines of §2.0 (02.00a L44–57) and §2.2;
  - the CON-0192 register row;
  - EXTERNAL-SOURCES rows EXT-072–075;
  - `helly_check.py` and its recorded run.
- **Corpus:**
  - S1539 (whole), S0922 (L15–62, L741–815, L1566–1740), S0287 (L1–12, L40–56);
  - all three were checked against CORPUS-ALLOWED.tsv, sha256 matching (5b3c3deb…, 9afb89a5…, b7bd0509…).
- **Run:** I ran `helly_check.py` in a scratch copy (`python3 -I`, 5 min 9 s). Its output is identical to `helly_check-run-20261020.txt`.
- **Own checks:** `b1_checks.py` (scratchpad), cases C1–C7, all reported above.
- **Not done, marked U:**
  - RM L154–162 and SD L103–108 (Ch. 0–2 basis sources; not opened; checked only through §2.3 and §2.0);
  - S1513 L149;
  - S1519, S0141, S0183, S2569, S2537, S1554 and S1560 (the "not read" list; its reasons not re-checked);
  - the five logged scans (not re-run);
  - every outside bibliographic check (no web tool available);
  - the quote check of entry 60 (not re-run).
- **Access:**
  - no basis file, `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine file, or `chronological-read/` path was opened;
  - no corpus or book text was used in any search;
  - no other report was read.
