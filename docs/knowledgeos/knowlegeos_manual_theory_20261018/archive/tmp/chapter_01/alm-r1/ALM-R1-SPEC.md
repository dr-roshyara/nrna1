# ALM-r1: semantics of the TD-001 core (specification, written before implementation)

**Status:** research candidate under the Principal's ALM-r1 authorization (2026-10-18). **Not validated. Not canonical. Not TD-001 r1.2.**

**Baseline:** `../alm/` (frozen; review 1 = NOT SOUND). Phase A: `PHASE-A-BASELINE-FREEZE.md`.

**Tags:**
- [Src] corpus;
- [Repo] repository, outside the corpus;
- [v1] the book's §1.14.1 v1;
- [Ded] deduction;
- [Asm] assumption;
- [Pol·ID] a reserved-policy parameter, with its **register ID**
  (`../s11411-repair/M2-TRACEABILITY-AND-DECISIONS.md` L56–80; checkpoint; TODO);
- [Cand] an unregistered candidate parameter. It is **not minted**; it is listed for the Principal.

**No new numbering scheme.** Definitions are numbered **S-n**, theorems **Th-n**, propositions **Pr-n**. These labels belong to this document only.

---

## S-0 · The six questions kept apart (Principal's Phase C list)

| # | Question | Symbol | Layer |
|---|---|---|---|
| 1 | Was the act validly made? | `Valid(e)` | V |
| 2 | Did the holder have the required authority at the time of the act? | `Auth(e)` | V |
| 3 | Is the act recognizable from the records at horizon *k*? | `Known_k(e)` | R |
| 4 | Is a verdict of authorized / not-authorized / indeterminate justified? | `Rec_k(φ) ∈ {T, F, U}` | R |
| 5 | Is the act operationally effective, and from when? | `Eff_k(e)`, `UsableFrom(e)` | O |
| 6 | Does the performer meet the independence or eligibility restriction for a purpose? | `Elig(e, p, w)` | G |

**No layer is defined from the verdict of a later layer.**
- `Valid` and `Auth` read only the act history `H`.
- `Rec` reads only the records.
- `Eff` reads `Rec` plus the records.
- `Elig` reads `H`.

## S-1 · Domains

| Symbol | Meaning | Status |
|---|---|---|
| `Hol` | **authority holders**: persons, roles, bodies. Authority attaches **only** to holders | [Repo] INV-ATTR-1 "process identity is evidential only and never an authority input" |
| `Perf` | **performers**: processes or sessions that physically perform an act. Used **only** by eligibility | [Repo] A-1.4 "a **process** that implemented …"; INV-ATTR-2 |
| `Work`, `Subj` | work items; claim subjects | — |
| `K` | claim kinds: decision, rule, exception | [Src] TD-001 #2; T1-04 |
| `T` | instants, totally ordered; `∞` above all | — |
| `Atom` | permission atoms `(kind, object)`, where object ∈ Subj ∪ Work ∪ GrantId ∪ (activity × Work) ∪ Atom (for delegation), or the wildcard `*` | [Ded] repairs C-3 |

## S-2 · Acts

An act `e` carries:
- `id`;
- `kind ∈ {GRANT, REVOKE, CONFIRM, ADOPT, END, PERFORM, ACCEPT}`;
- `holder(e) ∈ Hol`, the holder on whose authority the act is made;
- `performer(e) ∈ Perf ∪ {⊥}`;
- `τ(e) ∈ T ∪ {⊥}`, the **act time**;
- `ord(e) ∈ ℚ ∪ {⊥}`, a **recorded tie order** among acts of equal τ;
- a kind-specific payload:

| Kind | Payload |
|---|---|
| GRANT | `grantee ∈ Hol`, `scope ⊆ Atom`, `period = [from, until]`, `proviso ∈ {0, 1}`, `bars ⊆ Purpose × Perf × (Work ∪ {*})` |
| REVOKE, CONFIRM | `target`: a grant id |
| ADOPT | `subject ∈ Subj`, `ckind ∈ K`, `cperiod = [cfrom, cuntil]`. **Claim identity:** `claim(a) := a.id`; one ADOPT act creates one claim |
| END | `target`: a claim id. Covers revocation, supersession and annulment **of a claim**, all prospective. Annulment ex tunc is out of scope, as are successor-conditional supersession (C5) and RD 2's late values |
| PERFORM | `activity ∈ {implement, verify, review}`, `work` |
| ACCEPT | `work` |

## S-3 · Dependency order (the C10 parameter)

`prec(f, e)` means "f is available to e". **Two options are implemented. Neither is a default.**

| Option | Definition | Note |
|---|---|---|
| **C10 = given** | `prec(f, e) ⟺ (τ(f), ord(f)) < (τ(e), ord(e))`, lexicographically. **Requires** τ and ord to be defined and distinct | ties are resolved by a *recorded* order. **Not** by record position, and **not** by list order (repairs P-2 / CE8) |
| **C10 = no-same-instant** | `prec(f, e) ⟺ τ(f) < τ(e)` | acts at the same instant never see each other |

A third option, "escalate: conflicting same-instant acts are invalid", is **not implemented**. Defining "conflicting" would itself be a policy. It is listed in the brief.

**[Ded]** In both options `prec` is a strict partial order, and `prec(f, e) ⇒ τ(f) ≤ τ(e)`.

## S-4 · Validity layer V (on the act history H)

**Parameters Θ:**
- `C1 ∈ {b, a}`;
- `RD1 : K → {persist, cascade}`;
- `δ ∈ {independent, dependent}`;
- `BASIS ∈ {any, all}` [Cand];
- `C10`;
- `ιg`: the C14 interval convention for grants;
- `ιc`: the claim-period convention [Cand: the register's C14 covers grants only];
- `C6 ∈ {off, later_prevails}`;
- `BAR ∈ {any, all}` [Cand];
- `Root ⊆ Hol × Atom` (static; C12 option "static", which is the only one implemented: [Asm], flagged).

**S-4.1 Form(e)** (well-formedness):
- kind is an act kind;
- a GRANT requires `from(e) ≥ τ(e)` [Src S0058 L77: anachronism is a defect; repairs P-10 / CE10];
- REVOKE and CONFIRM require that the target grant `g` exists with `prec(g, e)`;
- END requires that the target ADOPT `a` exists with `prec(a, e)`. This is r1.1 "**later** act" (repairs C-4 / CE2).

*Form reads only prec-earlier acts.*

**S-4.2 Need(e)** (required atoms):

| Kind | Need(e) |
|---|---|
| GRANT | `{(GRANT, π) : π ∈ scope(e)}` |
| REVOKE | `{(REVOKE, target)}` |
| CONFIRM | `{(CONFIRM, target)}` |
| ADOPT | `{(ADOPT, subject)}` |
| END | `{(END, subject(target))}`. It is read only when Form(e) holds, so the target is prec-earlier (repairs P-1) |
| PERFORM | `{(PERFORM, (activity, work))}` |
| ACCEPT | `{(ACCEPT, work)}` |

`Covers(S, π) ⟺ π ∈ S ∨ (kind(π), *) ∈ S ∨ (GRANT, *) ∈ S when kind(π) = GRANT`.

**S-4.3 Alive(g, x)**, the grant `g` in force at point `x`. A *point* is either an act `e`, with time τ(e) and "before" = `prec(·, e)`, or an evaluation instant `t`, with "before" = `τ(·) ≤ t` (C16 inclusive: an act at instant t takes effect at t).

`Alive(g, x) ⟺ Valid(g) ∧ before(g, x) ∧ time(x) ∈_ιg period(g) ∧ ¬∃ r: kind(r) = REVOKE ∧ target(r) = g ∧ Valid(r) ∧ before(r, x) ∧ (proviso(g) = 0 ∨ ∃ c: CONFIRM of g ∧ Valid(c) ∧ before(c, x)) ∧ (δ = dependent ⇒ ParentAlive(g, x))`

`ParentAlive(g, x) ⟺ Basis(GRANT-act of g) ∋ ROOT ∨ ∃ g′ ∈ Basis(GRANT-act of g) with Alive(g′, x)`.

**S-4.4 Basis(e)**:

`Basis(e) = { g : grantee(g) = holder(e) ∧ Alive(g, e) ∧ ∀ π ∈ Need(e): Covers(scope(g), π) } ∪ ({ROOT} if ∀ π ∈ Need(e): (holder(e), π) ∈* Root)`

*A single grant must cover every needed atom; pooled coverage (C4 χ) is out of scope.*

**S-4.5 Auth(e)**, authority at the time of the act:

`Auth(e) ⟺ Basis(e) ≠ ∅ ∧ ¬Restr(e)`

`Restr(e)` is IND-1, as an **authority** restriction: `kind(e) = ACCEPT ∧ holder(e) ∈ Producers(work(e), e)`, where `Producers(w, e) = { holder(f) : PERFORM implement on w, Valid(f), prec(f, e) }`. Sources: [Src] S0067 I-8 "engineering never accepts its own work"; S0043 L28 (EEP); checkpoint §3: "IND-1 … is authority". This repairs E-8.

**S-4.6 Valid(e) under C1 = b** ("an act" establishes; records evidence it):

`Valid_b(e) ⟺ Form(e) ∧ Auth(e)`

**S-4.7 Valid(e) under C1 = a** ("a **recorded** act", in the reviewer's formulation, review F-3). This needs **record times** `ρ(x)`. Every act `f` counts for `e` only if `ρ(f) ≤ τ(e)`, and `e` itself must be recorded (`ρ(e) ≠ ⊥`). Formally, V is evaluated on `H^a_e = { f : ρ(f) ≤ τ(e) } ∪ {e}`, with `Valid_a(e) ⟺ ρ(e) ≠ ⊥ ∧ Valid_b(e) on H^a_e` (and recursively for the acts it uses).
- **[Ded] Not computable on the BC-7 store**, which has positions but no record times (S0067 L94; S0071 `Seq`), unless a date→position map μ exists.

**S-4.8 Current(c, t)** for a claim `c` made by ADOPT `a`, at evaluation instant `t`:

`Current(c, t) ⟺ Valid(a) ∧ τ(a) ≤ t ∧ t ∈_ιc cperiod(a) ∧ ¬∃ x: END of c ∧ Valid(x) ∧ τ(x) ≤ t ∧ Carry(a, t) ∧ (C6 = later_prevails ⇒ ¬∃ a′: ADOPT ∧ subject(a′) = subject(a) ∧ Valid(a′) ∧ prec(a, a′) ∧ τ(a′) ≤ t)`

`Carry(a, t)` is **RD 1, per claim kind**:
- if `RD1(ckind(a)) = persist`: true;
- if `cascade`: `ROOT ∈ Basis(a) ∨ Q g ∈ Basis(a) − {ROOT}: Alive(g, t)`, where `Q = ∃` if `BASIS = any` and `Q = ∀` if `BASIS = all`. This repairs C-1: it uses the **authorizing** grants, not any grant. With `δ` it reaches up the delegation chain (repairs C-2).

**S-4.9 Elig(e, p, w)**, eligibility for a purpose. It is defined only for `Valid(e)`; otherwise the value is `NOT_VALID`.

| Value | When |
|---|---|
| `INELIGIBLE` | (IND-2, relational) `p = independent_verification ∧ performer(e) ∈ { performer(f) : PERFORM implement on w ∧ prec(f, e) }` ([Repo] A-1.4/D-5; repairs E-5) |
| | **or** (IND-5) `BAR = any`: some `g ∈ Basis(e)` has `(p, performer(e), w or *) ∈ bars(g)`; `BAR = all`: every non-root `g ∈ Basis(e)` does ([Src] S0104; repairs C-5 as a parameter) |
| `UNDETERMINED` | `performer(e) = ⊥` |
| `ELIGIBLE_ATTESTED` / `ELIGIBLE_ASSERTED` | otherwise, by the evidence grade of the performer identity (IND-7; [Repo] INV-ATTR-2). IND-3: `p = governance_review` with an own implementation on w ⇒ the flag `DISCLOSURE_REQUIRED` (missing disclosure = a governance **defect**, not ineligibility [Repo] A-5.2) |

## S-5 · Records

A **record entry** is `(pos, ê, ρ̂)`:
- `pos ∈ ℕ⁺` is unique across entries;
- `ê` is the act **as recorded**: fields may be ⊥;
- `ρ̂` is an optional record time.

`E_k := { entries with pos ≤ k }`.

**Contradiction:** two entries with the same act id and different contents.

**Record axioms (not record definitions; renamed to avoid P-10's collision):**
- **AX-AppendOnly:** positions are never reassigned or removed. **Unverifiable on BC-7** (C-3), so it is an **assumption**. BC-7 T-3 is only *Proposed* ([Src] S0071 L242; review E-7).
- **AX-NoBackdate:** `ρ̂ ≥ τ` whenever both are known ([Src] n ≥ 5 sources: S0079, S0091, S0092, S0095, S0103; corrects the earlier "n ≥ 6").
- **AX-Truth:** each recorded non-⊥ field equals the true field; recorded acts occurred [Asm]. **Known to fail sometimes** ([Src] S0104 I-1).

## S-6 · Recognition layer R

**Worlds.** `W_k` is the set of histories obtained from `E_k` by:
- choosing one variant per contradicted act;
- assigning every ⊥ field a value from its finite domain: τ from `T_dom`; ord as any tie order; performer from `Perf_dom`.

A world contains **only recorded acts**.

**The question time** `t_φ`:
- `τ(e)` for `Valid(e)` / `Auth(e)`;
- `t` for `Current(c, t)`;
- `τ(e)` for `Elig`.

**Definition S-6.1 (two horizons).** The parameters are `λ⁺, λ⁻ ∈ T_≥0 ∪ {∞}` (**T1-20**, with C1/C2) and `now(k) ∈ T`, the instant at which prefix `k` is read. For every world `w`:

```
Rec_k(φ) = T   if  ∀w ∈ W_k:  t_φ(w) ≤ now(k) − λ⁺  ∧  φ(w)
         = F   if  ∀w ∈ W_k:  t_φ(w) ≤ now(k) − λ⁻  ∧  ¬φ(w)
         = U   otherwise   (including W_k = ∅, or the act or claim not in E_k)
```

- `λ⁺` is the **positive presumption**: after it, the records are presumed sufficient to *establish*.
- `λ⁻` is the **negative closure**: after it, the absence of an act on record is presumed to be its absence in fact.

**[Ded] TD-001 r1.1's recognition clause is exactly `λ⁺ = 0, λ⁻ = ∞`:** "recognises it as current … when its records … establish 1–3; otherwise 'cannot determine', never 'not authorized'". This repairs F-1 and C-8.

**`Known_k(e)`** ⟺ `e.id` occurs in `E_k`.

## S-7 · Operational layer O

`Eff_k(e) ⟺ Known_k(e) ∧ Rec_k(Valid(e)) = T ∧ Proc_k(e)`

`UsableFrom(e) := min{ k : Eff_k(e) }`, or `never`.

- `Proc_k` is the procedural precondition: e.g. BC-7's recorded HANDOFF ∧ human START [Src S0071 I-2].
- `Proc` is supplied externally. The core has no START kind.
- **[Src] Observed BC-7 behaviour is `Proc` only: START "does not consult grants".** So `Eff` is the **target** conjunction; `Eff_obs := Known ∧ Proc` is the observed one ([Src] S0071 L134; review E-7).

## S-8 · Date-indexed questions without a clock

**Witnesses** are pairs `Wit ⊆ T × ℕ`: "position p was on record by date d′", taken from independent evidence (e.g. a dated, committed document quoting a seq).

`K(d) := [max{ p : (d′, p) ∈ Wit, d′ ≤ d }, min{ p : (d′, p) ∈ Wit, d′ > d } − 1]`, with defaults 0 and `k_max`.

`RecAt_d(φ) := v` if `Rec_k(φ) = v` for every `k ∈ K(d)`; otherwise `U` (time-unresolved).

**Required evidence** (no reconstruction mechanism is invented):
- (i) integrity evidence for AX-AppendOnly;
- (ii) dated position witnesses, or record times.

## S-9 · Theorems: premises, claims and proof status

| Id | Premises | Claim | Status |
|---|---|---|---|
| **Th-1** existence and uniqueness | `H` finite. `prec` strict and well-founded (both C10 options). Form, Need, Basis, Restr and Alive(·, e) read only acts `f` with `prec(f, e)`, or e's own fields | `Valid`, `Auth`, `Basis` and `Alive(·, e)` are uniquely determined. `Current(c, t)` is uniquely determined for every c, t | **proved** (§10). Termination: the recursion visits each act once; each step is finite |
| **Th-2** locality | as Th-1 | `Valid(e)` depends only on `H↓e = {f : prec(f, e)} ∪ {e}`. `Current(c, t)` depends only on `H≤t = {f : τ(f) ≤ t}` | **proved** (§10). *The baseline's Lemma 2 failed at END (P-1); S-4.1 now forces END's target to come first* |
| **Th-3** soundness and completeness of recognition under completeness | AX-Truth. No contradictions in `E_k`. Every field needed is non-⊥ for acts in `H≤t`. **Comp(k, t)**: every act with `τ ≤ t` has an entry in `E_k` | (a) `|W_k| = 1` and `φ(w) = φ(H)` for every φ about time ≤ t. (b) `Rec_k(φ) ∈ {V(φ), U}`. (c) `Rec_k(φ) = V(φ)` if `t ≤ now − λ⁺` when V(φ) = T, or `t ≤ now − λ⁻` when V(φ) = F | **proved** (§10). Under C1 = a it also needs `ρ` to be complete |
| **Th-4** non-monotonicity | `λ⁺ < ∞`; a late entry is permitted (AX-NoBackdate allows `pos` order ≠ τ order) | there exist E, k < k′ with `Rec_k = T` and `Rec_k′ ∈ {F, U}`. `F` needs `λ⁻ < ∞`; under r1.1 the change is **T → U** | **proved** by a construction (§10) + bounded search |
| **Th-5** no sound absence-negative under an open world | Root holds `(GRANT, *)` [Asm A-Root]. An entry may be added at any later position (open world). `φ = Valid(e)` or `Current(c, t)` | for every E_k with `Rec_k(φ) = F`, there is a completion `H′ ⊇` the acts of E_k consistent with all record axioms in which `φ(H′)` holds. So **F is defeasible even when backed by a recorded revocation** (repairs P-3). **Corollary:** r1.1's `λ⁻ = ∞` is the only choice sound without a completeness guarantee | **proved** (§10) |
| **Th-6** positives are presumptive | `λ⁺ < ∞`; open world | T can be retracted by a later entry. T is sound iff Comp(k, t) ∧ AX-Truth | **proved** (Th-3 + Th-4) |
| **Th-7** determinism and termination of R | finite domains for ⊥ fields; finite E_k | `Rec_k` is a total function of `(E_k, now, λ⁺, λ⁻, Θ, domains)` | **proved** (finite enumeration) |
| **Th-8** reproducibility | AX-AppendOnly (unverifiable on BC-7) | `E_k` for past k is the prefix of today's store, so `Rec_k` is reproducible. `RecAt_d` is determinate iff constant on `K(d)` | **proved conditionally**. **This replaces** the baseline's Theorem 5 consequence (P-4) |
| **Pr-9** layers | — | `Eff_k(e) ⇒ Known_k(e)`. `Eff_k(e) ⇒ Valid(e)` holds under the premises of Th-3 for `t = τ(e)`, and can fail without them (Th-4) | **proved** |
| **Pr-10** C1 alternatives are incomparable | AX-Truth, AX-NoBackdate | neither `Valid_a ⇒ Valid_b` nor `Valid_b ⇒ Valid_a` holds in general | **to be settled by bounded search for minimal counterexamples** (`tests_r1_*`). A conjecture until shown |

## S-10 · Proofs

**Th-1.**
- Order `H` by any linear extension of `prec`. Define `Valid` along it.
- At each `e`:
  - `Form(e)` reads targets `g` or `a` with `prec(·, e)`, whose existence is a fact of `H`;
  - `Need(e)` reads, for END, `subject(a)` of a prec-earlier `a`;
  - `Alive(g, e)` reads `Valid(g)` for `prec(g, e)`, and `Valid(r)` / `Valid(c)` for `prec(r, e)` and `prec(c, e)`;
  - under δ = dependent, `ParentAlive(g, e)` reads `Basis(GRANT-act of g)`, which is already fixed because the GRANT act precedes `e`, and `Alive(g′, e)` recursively. That recursion strictly descends the finite delegation chain, since each parent's GRANT act precedes its child's;
  - `Restr(e)` reads `Valid(f)` for `prec(f, e)`.
- So `Valid(e)` is a function of values already fixed: existence by construction; uniqueness by induction along the extension.
- Results agree across linear extensions because every reference is to `prec`-predecessors only.
- `Current(c, t)` reads `Valid` of fixed acts and `Alive(g, t)`. The latter is the same recursion with the point `t`, which refers to acts with `τ ≤ t`, all with fixed validity. ∎

**Th-2.** Every reference listed in the Th-1 proof is to `H↓e`, or for currency to acts with `τ ≤ t`. Each of those refers in turn only to acts that precede it in prec, and therefore have `τ ≤ t` (S-3). ∎

**Th-3.**
- (a) With no contradictions and no ⊥ fields, `|W_k| = 1`. Call the world `H_k`.
- By AX-Truth, `H_k ⊆ H` with true fields. By Comp(k, t), `H_k ∩ H≤t = H≤t`.
- By Th-2, every φ about a time ≤ t is evaluated identically on `H_k` and `H`.
- (b) and (c) follow from S-6.1, since the single world gives `φ(w) = V(φ)`. ∎

*Remark:* the premise "every field needed is non-⊥" includes `ord` for equal-τ acts under C10 = given. **This is exactly P-2's missing premise, now stated.**

**Th-4.**
- `g`: R grants `b` `(PERFORM, (implement, w))`, τ = 1, pos 1.
- `r`: R revokes `g`, τ = 2, pos 3.
- `e`: b performs, τ = 3, pos 2.
- Parameters: `λ⁺ = 0`, `now = 10`.
- `Rec_2(Valid(e)) = T`.
- `Rec_3(Valid(e)) = F` if `λ⁻ ≤ 7`, else `U`. ∎

**Th-5.**
- Let `Rec_k(φ) = F`. Take `H′ = acts(E_k) ∪ {g*}`, where `g*` is a ROOT grant to the relevant holder with scope `{(*, *)}`, `τ(g*)` earlier than every act, `ord` minimal and `pos` = `k_max + 1`.
- `g*` is valid (ROOT); no revocation targets it; it is a late entry, which AX-NoBackdate permits.
- **Validity:** `Basis(e)` now contains `g*`, so `Auth(e)` holds. If `Form(e)` holds, `Valid(e)`; if Form fails, φ was false in every completion and the F is sound. **The theorem therefore excludes ill-formed acts** (a refinement found while proving).
- **Restr (IND-1):** g* cannot cure it. A restriction-based F is sound.
- **Currency:** likewise for `Valid(a)`. But a **recorded valid END** of c or an expired cperiod cannot be cured by any completion that only *adds* acts **unless** the END's own validity is defeated: add a ROOT REVOKE of the END-holder's basis grants at a τ before the END. That is possible iff the END was not made under ROOT.
- **Refined claim:** a negative is *non-defeasible* exactly when it rests on (i) ill-formedness, (ii) an IND-1 restriction, (iii) an expired period, or (iv) an ending act made under ROOT. Every other negative is defeasible. ∎ *(To be confirmed by the independent oracle's completion search.)*

**Th-8.** Under AX-AppendOnly, today's entries with `pos ≤ k` are exactly `E_k`, so `Rec_k` is recomputable (Th-7). `RecAt_d`: by definition. ∎

## S-11 · What is deliberately not decided

C1 · RD 1 (per kind) · δ · T1-20 (λ⁺, λ⁻) · C10 · C6 · C14 (ιg) · ιc · BAR · BASIS · C12 (only static is implemented: **flagged**).

**Every executable configuration states all of these explicitly** (`Theta`). No default exists in the code.
