# T-min v1: candidate transition theory (consolidated from F-LOG-0102…0133)

| | |
|---|---|
| Status | **CANDIDATE, frozen at commit before `tminv1_check.py` runs.** Not canonical; authority: none. Development-fitted wherever marked **(fitted)**. Every claim carries its evidence class |
| Evidence classes | **S** = source-stated, directly quoted · **R** = source-stated and recurring across cases/regimes · **D** = model-derived · **F** = fitted post hoc, must be tested on unseen data · **U** = undetermined |
| Scope | the PublicDigit governance corpus region examined: the rulings register (71 rows, 2 regimes), 5 session-log sections, 1 ADR, and git history. The corpus itself warns that its vocabulary "claims no universality" (R-80) |

## 1. Ontology (the smallest structure that expressed every observation)

| Element | Definition | Class |
|---|---|---|
| **Event** | e = (operation o, route r, authority a, object kind k, prior state σ, evidence ε, exception x, conformance c, Δ⁺, Δ⁻, outcome) | D |
| **State** | a map (object, coordinate) → value. The candidate coordinates are: status, authorization (with proviso state), acceptance/lifecycle, execution, regime (a scope-indexed freeze), annotation-role, text window, registry, authoritative location. **Open set; sources name different planes** | R (factoring) / U (the set) |
| **Frame** | Δ⁺(e) ⊆ Frame⁺(o) for direct effects; derived guard truth is not a change; Δ⁺ ∩ Δ⁻ = ∅ | R (the idea, 2 regimes) · F (the tables: ADOPT and ACCEPT were corrected) |
| **Finite history** | status {PREPARED, ADOPTED, HELD, WITHDRAWN} + registry {unused, used, retired} + the proviso state of an authorization; **no unbounded history needed** | R (3 witnesses) |

## 2. Legality: Legal(e) = G_o(a, k, σ, c, ε, x)

| Guard | Content | Class |
|---|---|---|
| authority | ADOPT of a Chief-issued ruling only by the Decision Authority; no self-adoption; no delegation from adoption | R (necessary: a minimal pair) |
| kind | the register admits constitutional decisions only | S (necessary: a minimal pair) |
| prior state | START only with full authorization (a discharged proviso); RAISE blocked while the scope is frozen; AUTHORIZE(successor) after the predecessor is accepted | R (necessary: minimal pairs) |
| **conformance c** | an act whose content collapses separated roles (e.g. evidence submission with constitutional review) is not adoptable | **F** (introduced to resolve R-86 vs R-91) |
| evidence bar | on standing-changing operations (RAISE, SUPERSEDE) a refusal may cite insufficient evidence; **which dimension activates the bar (route / kind / operation / effect) is U**. Evidence is **not sufficient** for supersession (R-94) | S (refusals) · U (activation) · D (not sufficient) |
| four-act separation | permission ≠ authorization ≠ commissioning ≠ execution; no execution from permission alone | R (R-79, R-80, and the WP-8 pair) |
| sequence ≠ guard | an ordering affirmed in a directive is not an authorization guard; later acts can relocate it | D (from the WP-4B timing, F-LOG-0129) |

## 3. Choice: among the legal options, stated principles select one

| Principle | Where | Class |
|---|---|---|
| minimal governance change; preserve chronology | R-94 | S |
| reopening requires *materially new evidence*, not a better argument | L216, R-86, R-78 | R (3 occurrences) |
| new vocabulary only on *demonstrated*, not anticipated, ambiguity | R-80 | S |
| slice-at-a-time authorization | R-72 | S |

## 4. Record

| Claim | Class |
|---|---|
| strict immutability of decision text | **falsified** (R-96, R-98, R-99) |
| **I-B4′:** no decision-text change after its first day (0/71, census); in-window edits are labelled corrections, annotations, or a status rewrite of an uncited, non-governing ruling | R (census) |
| supersession keeps the superseded text (as a pointer) and moves authority to the new record | S in 2 source families (register, ADR) |

## 5. Undetermined, and why
- **Route vs operation (G-R vs G-O): U by corpus design.** Route ≡ host family, so no route-only pair can exist here.
- **Evidence and exception as legality variables: U.** No single-factor pair exists.
- **The state-coordinate set: U.** Factoring is established; the dimensions are not.

## 6. Falsifiers of v1 (what would refute it)
1. A performed act that a guard in §2 forbids, with the forbidding fields positively recorded.
2. A decision-text change on a later day than its first record.
3. Two histories with an equal v1 state and different enabled futures.
4. A second adoption refusal by the Decision Authority on a conformant ruling. That would refute the fitted conformance guard.
