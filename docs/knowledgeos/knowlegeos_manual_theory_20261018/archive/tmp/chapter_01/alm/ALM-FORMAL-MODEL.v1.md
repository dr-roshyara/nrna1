# ALM: a layered Authority-Lifecycle Model (candidate, 2026-10-18)

**Status:** research candidate. **PROPOSED, not validated, not adopted.**

**It is not r1.2.** TD-001 (the original, r1 and r1.1) is unchanged. ALM is a formal frame in which r1.1 and its competitors can be stated and tested.

**Provenance tags used throughout:**
- **[Src]** corpus source (S-IDs);
- **[Repo]** supplementary repository evidence, outside the corpus;
- **[Ded]** mathematical deduction;
- **[Asm]** assumption;
- **[Pol]** policy parameter. Evidence alone cannot fix it, and its choice is the Principal's.

**Evidence base:** `PHASE-1-CHECKPOINT-20261018.md`, `S-BATCH-*.md`, `REPO-WIDE-DEFINITION-SEARCH-20261018.md`.

---

## 0. Design goals, each traceable to evidence

| Goal | Evidence |
|---|---|
| G1 · Validity is fixed by acts, not by documents | [Src] S0067 L51 "the record never manufactures authority"; S0071 GP-1; S0092 L35; S0095 L141. [Repo] ES-001.2; ARCH-INV-1; R-86 |
| G2 · Operational effect needs records | [Src] S0071 I-2 (activation = recorded HANDOFF ∧ recorded START) |
| G3 · Recognition uses the records as they stood, never back-dated | [Src] S0079, S0091, S0092, S0095 and S0103 (n ≥ 6) |
| G4 · Legitimacy is point-in-time; decisions outlast their authority | [Repo] ADR-001 (ACCEPTED, implemented); Stress Test 8 |
| G5 · Approved ≠ currently authoritative; authority can lapse, be revoked or be superseded | [Repo] Reconciliation v3.1 §10.1 (ARB REV 1, T3 scope) |
| G6 · Supersession and annulment only by an explicit act, never by inference | [Repo] R-77; [Src] S0079 L144 |
| G7 · Conditional acts become operative through an explicit later act | [Src] S0052; [Repo] R-72 → R-86 |
| G8 · Eligibility (independence) is purpose-relative and separate from validity | checkpoint §3 (IND-1…7) |
| G9 · "Not authorized" ≠ "cannot determine" | TD-001 r1.1; [Src] S0058 L49–51; S0049 (default to weakest) |

## 1. Signature

**Finite sets:**
- `Actor`, the actors.
- `Kind`, the act kinds.
- `Subject`, the governed subjects (claims, rules, decisions, assignments, …).
- `Purpose`, the uses an act can be offered for (e.g. `independent_verification`, `acceptance`).

**Time.** `T` is a totally ordered set of instants (e.g. ℤ).
- The interval convention is a parameter **ι ∈ {closed, half_open}** [Pol]. The repository precedent is closed: `TemporalAuthorityWindow::stateAt` [Repo].

**Acts.** An act history `H` is a finite set of acts. Each act `e` has these fields:

| Field | Meaning |
|---|---|
| `id(e)` | unique identifier |
| `actor(e) ∈ Actor` | who performed the act |
| `kind(e) ∈ {GRANT, REVOKE, CONFIRM, ADOPT, END_CLAIM, PERFORM}` | the act kind |
| `τ(e) ∈ T` | **act time**: when the act was given or performed |
| `target(e)` | kind-specific: a grant spec for GRANT, a grant id for REVOKE/CONFIRM, a claim spec for ADOPT, a claim id for END_CLAIM, a `(kind, subject)` for PERFORM |

**Act order.** `≺` is a strict total order on `H` that extends the `<` order on `τ`. Ties at equal `τ` are broken by a history-supplied order, such as the order in which the acts were given [Asm A0].

**Grant spec.** A GRANT `e` carries the following fields:
- `holder ∈ Actor`;
- `scope ⊆ Kind_perf × Subject`;
- `period = [from, until]`, with `until` possibly `∞`;
- `proviso ∈ {none, pending}`;
- `bars ⊆ Purpose × Actor`, the actors barred from using acts under this grant for a purpose.
- **Well-formedness:** `from ≥ τ(e)`. A grant cannot be in force before it is given [Ded from S0058 L77: anachronism is a defect].

**Claim spec.** An ADOPT `e` carries `claim = (cid, subject, valid_from, valid_until)`.

**Root.** `Root ⊆ Actor × Scope` lists the actors whose authority is stipulated rather than granted (genesis; T1-17) [Asm A1]. The evidence indicates that the PO/ARB is the root: [Src] S0067 L116 "the origin of all policy and all authority".

**Records.** A record store is an injective partial function `pos : H ⇀ ℕ⁺` (record position, the `seq`) [Src: S0071 `Seq`]. The **record prefix** at `k` is `H≤k = { e ∈ H : pos(e) ≤ k }`.
- Recording time `ρ(e)` is optional. The current store has none [Src S0067 L94]. A date maps to a position only through an external map `μ : T → ℕ` [Asm A2], e.g. git commit time.

**Record axioms:**
- **R1 · append-only:** positions are never reassigned [Src S0071 T-3].
- **R2 · never back-dated:** `pos` follows recording order, not act order. `pos(e) < pos(e')` with `τ(e) > τ(e')` is permitted (a late transcription).
- **R3 · truthfulness (an assumption):** every recorded act occurred, with the recorded fields [Asm A3]. It is known to fail sometimes: S0104 I-1; the R-60 recording-note discrepancy [Repo].

## 2. Layer V: validity (de jure, a function of `H` alone)

All definitions are by well-founded recursion on `≺`.

**Definition V1: in force (grant).** Grant `g` (a GRANT act) is in force *for an act* `e` iff all four conditions hold:

| | Condition |
|---|---|
| (a) | `Valid(g)` and `g ≺ e` |
| (b) | `τ(e) ∈ period(g)` (under ι) |
| (c) | there is no `r ∈ H` with `kind(r) = REVOKE`, `target(r) = g`, `r ≺ e` and `Valid(r)` |
| (d) | `proviso(g) = none`, **or** there is a `c ≺ e` with `kind(c) = CONFIRM`, `target(c) = g` and `Valid(c)` |

**Definition V2: valid act.** `Valid(e)` iff `(actor(e), x) ∈ Root` for the relevant `x`, **or** there is a grant `g` in force for `e` with `holder(g) = actor(e)` and `covers(g, e)`. For each kind, `covers` requires the following pair to be in `scope(g)`:
- GRANT: `(GRANT, subject_of(spec))`;
- REVOKE and CONFIRM: `(REVOKE|CONFIRM, target)`;
- ADOPT and END_CLAIM: `(ADOPT|END, subject)`;
- PERFORM: `target(e)`.

*Delegation narrowing (scope or period containment) is **not** imposed.* It is unsourced [Src S0016; T1-14]. It would be a further policy parameter.

**Definition V3: current claim.** Fix the carry-over policy **κ ∈ {persist, cascade}** [Pol]. Claim `c`, adopted by `a`, is **current at evaluation time `t`** iff all four conditions hold:

| | Condition |
|---|---|
| (a) | `Valid(a)` and `τ(a) ≤ t` |
| (b) | `t ∈ [valid_from(c), valid_until(c)]` |
| (c) | there is no `x` with `kind(x) = END_CLAIM`, `target(x) = c`, `Valid(x)` and `τ(x) ≤ t` |
| (d) | if `κ = cascade`: the grant(s) that authorized `a` are still in force at `t`, each as in V1 applied to a hypothetical act at `t` |

Under `κ = persist`, decisions **outlast** the authority that made them. Evidence: [Repo] ADR-001 "historical decisions maintain their legitimacy at decision time"; Stress Test 8; AuthorityDiscovery Conflict 4.

**Definition V4: eligibility (purpose-relative, not validity).** `Elig(e, p)` iff `Valid(e)`, **and** `actor(e)` is not barred for `p`, either:
- by a general rule `B₀(p)` [Src/Repo: IND-1, IND-2, IND-4], or
- by `bars(g)` of the authorizing grant [Src: IND-5].

`B₀` is a **finite enumerated relation from sources**. No general independence rule is assumed, because IND-3 is an explicit permission and IND-6 is not adopted.

**Theorem 1 (existence and uniqueness of V) [Ded].** For every finite `H` with a strict total order `≺`, the predicates `Valid` and `InForce` exist and are **unique**. `Current(c, t)` is uniquely determined for every `c` and `t`.
- *Proof.* Each condition V1/V2 for `e` refers only to acts `x ≺ e`. On a finite total order, `≺` is well-founded. Define `Valid` by recursion along `≺`: the base case is the `≺`-minimal act, which can only be valid by Root. Uniqueness follows by `≺`-induction. V3 refers only to `Valid` of acts with `τ ≤ t`, already fixed. ∎

**Corollary 1.1 (no cycles):** no act's validity depends on itself, so circular delegation cannot validate itself. A mutual-grant loop with no root is invalid by the base case.

**Lemma 2 (temporal locality) [Ded].** `Valid(e)` depends only on `{x : x ≺ e}`, and `Current(c, t)` depends only on `{x : τ(x) ≤ t}`. *Proof:* by inspection of V1–V3. ∎

**Remark (why strict precedence) [Ded + Src].** If an act could use or be revoked by acts at the **same** position (`⪯` instead of `≺`), mutual same-instant revocations admit **two stable assignments**. Executable counterexample: `tests_alm.py::test_same_instant_semantics_not_unique`. This is the defect class of M2's G-1 (greedy υ). The strict order removes it at the cost of A0: a tie-breaking order must be supplied. **Precise statement of the boundary:** uniqueness requires either a total `≺` (A0) or a ban on same-instant interaction. A partial order is not enough.

## 3. Layer R: recognition (as the records stood; three-valued)

**Definition R1: record-relative validity.** `Valid_k` is V1–V2 evaluated on `H≤k` with `≺` restricted to it. `Current_k(c, t)` is V3 on `H≤k`.

**Definition R2: verdict.** Fix the **closure horizon λ ∈ T ∪ {∞}** [Pol]: how long after an instant the records are *presumed complete* for acts with `τ ≤` that instant. Let `now(k)` be the evaluation time at which prefix `k` is read. For a question `φ` about time `t` (validity of an act at `τ = t`, or currency at `t`):

```
Rec_k(φ) = UNDETERMINED           if a field φ needs is missing in H≤k   (e.g. τ unknown, scope opaque)
         = UNDETERMINED           if t > now(k) − λ                       (records not yet presumed complete)
         = AUTHORIZED / CURRENT   if φ holds on H≤k
         = NOT_AUTHORIZED / NOT_CURRENT   otherwise
```

- With `λ = ∞` [the open-world limit], **every** verdict is UNDETERMINED **unless** it is a positive verdict "secured" by Definition R3 below.
- **Comp(k, t):** every act with `τ ≤ t` is recorded in `H≤k`. This is the condition under which records are complete for time `t`.

**Theorem 3 (soundness under completeness) [Ded].** If R3 (truthfulness) and Comp(k, t) hold, then for every claim `c`, `Current_k(c, t) = Current(c, t)`. For every act `e` with `τ(e) ≤ t`, `Valid_k(e) = Valid(e)`.
- *Proof.* Under Comp(k, t), `H≤k ∩ {τ ≤ t} = H ∩ {τ ≤ t}`. Truthfulness (R3) makes the field values agree. By Lemma 2 both sides depend only on this set and on `≺` restricted to it.
- One subtlety: `≺` on `H≤k` must equal `≺` on `H` restricted. This holds because A0 makes `≺` a property of acts, not of records. If `≺` were derived from `pos`, the theorem fails (counterexample: `test_order_from_positions_breaks_soundness`). ∎

**Theorem 4 (recognition is not monotone in k) [Ded].** There exist `H`, `k < k'` and `t` with `Rec_k = AUTHORIZED` and `Rec_k' = NOT_AUTHORIZED`.
- *Minimal counterexample (3 acts):*
  - `g`: GRANT to `b`, given and recorded first;
  - `r`: REVOKE `g` at τ = 2, recorded **late** at position 3;
  - `e`: PERFORM by `b` at τ = 3, recorded at position 2.
- At `k = 2`, `e` is valid on the records. At `k = 3` it is invalid.
- Executable: `test_late_revocation_flips_recognition`.
- *Consequence:* "as the records stood on date d" is reproducible for each `k` (Theorem 5), but **not stable across k**. A change in recognition is itself a fact to report, never a back-dating.

**Theorem 5 (historical reproducibility) [Ded].** Under R1 (append-only) and retention of the store, `Rec_k(φ)` is a function of `(H≤k, now(k), λ)`. It is reproducible for every past `k` **iff** prefixes are retained.
- **Architectural consequence:** the current authority store is gitignored and keeps no history ([Src] S0062 C-3; V-E). So **recognition at past positions is not reproducible today**. This makes ADR-C6 (authority-store provenance) **load-bearing for TD-001's recognition clause**. ∎

**Theorem 6 (decisiveness vs open world) [Ded].** If late recording is unbounded (no λ < ∞ is enforced or presumed), then no recognition function that is **sound** (it never returns NOT_AUTHORIZED when `Valid` holds) can return NOT_AUTHORIZED for an act whose authorizing grant could still be recorded late.
- *Proof sketch:* take any `H≤k` in which `e` lacks a recorded grant. The completion `H' = H ∪ {g}`, with `g ≺ e`, `g` valid by Root and `pos(g) > k`, is consistent with every record axiom (R2 allows late transcription). Then `Valid_{H'}(e)` holds. A NOT_AUTHORIZED verdict at `k` would be unsound in `H'`. ∎
- **Consequence:** a "not authorized" verdict from records **requires a policy (λ) or positive negative evidence**: a recorded revocation, a recorded bar, or a recorded refusal. This is the formal content of "cannot determine, never 'not authorized'" (G9), and it is why λ is a genuine policy parameter.

**Definition R3: secured positive verdict.** If `Valid_k(e)` holds and every act on which it depends is recorded, **and** no unrecorded act could defeat it (no REVOKE could exist), then a positive verdict is stable.
- Under open world such security is impossible, because a revocation could be recorded late (Theorem 4).
- **Positive verdicts are therefore also defeasible.** This is reported, not hidden: Rec under λ is a *presumption*.

## 4. Layer O: operational effectiveness

**Definition O1.** `Effective_k(e)` iff both of the following hold:
- `e ∈ H≤k` (recorded) and `Rec_k(Valid(e)) = AUTHORIZED`;
- for activation kinds, the **procedural preconditions** hold on the records. BC-7: a recorded predecessor HANDOFF ∧ a recorded human START [Src S0071 DP-2].

**Proposition 7 [Ded].** Each of the following holds:
- `Effective_k(e) ⇒ e ∈ H≤k`. Effect needs a record (G2, C1-a's operational content).
- `Valid(e) ⇏ Effective_k(e)`. An unrecorded valid act has no effect yet (S0092 L35).
- `Effective_k(e) ⇏ Valid(e)` in general. Under R3 + Comp the implication holds (Theorem 3). Without them, an effective but invalid act is possible (counterexample: a recorded act with a false field). The architecture's protection is R3, held by convention: G-2/R5b and the ALT-2 trial are evidential only.

**Proposition 8 (layers are consistent) [Ded].** V, R and O are defined on disjoint inputs (`H`, `H≤k`, `H≤k` + procedure). No definition of one layer refers to a *verdict* of a later layer. Hence no contradiction can arise *between* layers, only *disagreement* (V ≠ R), which Theorem 3 bounds.

## 5. PACE mapping: R-80's four terms inside ALM [Ded, scoped]

| R-80 term | ALM counterpart |
|---|---|
| **PERMISSION** ("may something be done?") | the policy level: `Root` and the decision-right matrix ([Repo] Phase-02 §7) that determine which GRANTs can be valid |
| **AUTHORIZATION** ("is execution sanctioned?") | `InForce(g, ·)` + `covers`: a valid, unrevoked, confirmed grant whose scope contains the act |
| **COMMISSIONING** ("has someone been instructed?") | BC-7 REGISTER + HANDOFF; procedural, used in O1 |
| **EXECUTION** ("is the work being performed?") | `Effective_k` of the START + subsequent PERFORM acts |

**The conflations R-80 names become separate predicates:**
- authorized ≠ executable: `InForce` vs `Effective` (with a proviso, G7);
- opened ≠ authorized: REGISTER vs GRANT.

*Status:* this is a mapping, not a definition. R-80 is scoped "for this programme".

## 6. Parameters and assumptions

| Id | Item | Kind | Evidence status |
|---|---|---|---|
| A0 | total act order `≺` supplied by history | Asm, **and its tie-break content is policy-relevant** | needed for uniqueness (Theorem 1 Remark). Source practice: acts are given in sequence. **Executable finding:** with two same-instant mutual revocations, whichever act comes first under A0 prevails (`strict_semantics_resolves_mutual_revocation_by_A0_order`). Uniqueness is bought by fixing the tie-break, and the tie-break *decides* the case. No source rules on simultaneous conflicting acts |
| A1 | Root actors | Asm | [Src] S0067 L116 (PO/ARB); genesis T1-17 |
| A2 | date → position map μ | Asm | **absent in the store** (S0067 L94); git time is a candidate |
| A3 | record truthfulness | Asm | known to fail sometimes (S0104 I-1) |
| ι | interval convention | Pol | repo precedent: closed |
| κ | carry-over of authority endings | Pol | evidence favours `persist` |
| λ | closure horizon for absence-based negatives | Pol | **no source** |
| B₀ | general eligibility bars | Src/Repo | IND-1, IND-2, IND-4 enumerated |
| U | at most one current claim per subject; "later prevails" | Pol (optional invariant) | [Repo] R-41; [Src] S0053. Without U, two current claims are possible (`test_two_current_claims_without_U`) |
| N | delegation narrowing | unsourced | not in the model |
| F | fact-triggered conditions | unevidenced | not in the model |

## 7. Relation to TD-001 r1.1 [Ded]

| r1.1 clause | ALM |
|---|---|
| (1) "adopted by an act of someone who, at the time of that act, held authority covering it" | V3(a) + V2: at act time, via `≺` |
| (2) "its own validity period includes that time" | V3(b) |
| (3) "no later properly authorized act revoked or superseded it, and no ending of the authority carries over under the organisation's rules" | V3(c) + κ |
| recognition "as its records stood on that date … otherwise cannot determine" | Layer R with μ (the date→position map) and λ. **r1.1 under-specifies λ and μ** |
| "an act" vs "a recorded act" (C1) | the V/O split. C1-b ↦ V; C1-a (operational) ↦ O |

**r1.1 is expressible in ALM without change**, given the parameters κ, λ, μ and ι. It does not mention:
- eligibility (V4);
- conditional grants (V1(d));
- the non-monotonicity of recognition (Theorem 4).

These are the inputs to any future revision decision. No revision is drafted here.
