# ALM-r2: semantics of the TD-001 core, round 2

**Status:** a research candidate. It is **not** validated, **not** canonical and **not** TD-001 r1.2.

**Basis:**
- `../alm-r1/` is frozen. Its review 2 failed: 12 MAJOR, plus R2-14 raised to MAJOR. The reviewer's binding conditions are in `../alm-r1/review-2/REVIEW-R2-FINAL-DISPOSITIONS-by-reviewer.md`.
- Scope: Phase A A3, amended by the Principal's Option A (`DECISION-SCOPE-RD6-ROUND2.md`). RD 6 (υ) is now **in scope as an undecided parameter**.

**Tags:**
- [Src] corpus;
- [Repo] repository outside the corpus;
- [v1] the book's §1.14.1;
- [Rev] a reviewer condition;
- [Ded] deduction;
- [Asm] an assumption (no source);
- [Pol·ID] a reserved policy, using its register or TODO ID;
- [Cand] an unregistered candidate parameter, not minted.

**Numbering:** S-n, Th-n and Pr-n are local to this document. **No register IDs are created.**

---

## S-0 · The questions, kept apart

| Question | Symbol | Layer | Reads |
|---|---|---|---|
| Is the act well-formed? | `Form(e)` | V | the history H |
| Did the holder have the required authority at the time? | `Auth(e) ∈ {T, F, NA}` | V | H. **Independent of Form and of ρ(e)** [Rev R2-07] |
| Was the act validly made? | `Valid(e) := Form(e) ∧ Auth(e) = T ∧ RecOK(e)` | V | H, ρ |
| Is the claim currently authoritative at instant t? | `Current(c, t)` | V | H, ρ |
| Is the performer eligible for purpose p on work w? | `Elig(e, p, w)` | V (G) | H |
| What do the records establish? | `Rec_k(φ) ∈ {T, F, U}` | R | the records only |
| Is the act operationally effective? | `Eff_k(e)` | O | the records |

## S-1 · Domains and atoms

- **Holders `Hol`** carry authority, and only holders do ([Repo] INV-ATTR-1).
- **Performers `Perf`** are used only by eligibility ([Repo] A-1.4; INV-ATTR-2).
- **Claim kinds** `K = {decision, rule, exception}`.
- **Ending kinds** `EK = {expiry, revocation}`.
- **Instants** `T` are integers, with `∞`.
- **Atoms** [Rev R2-15, R2-24]: `Atom ::= (kind, object)`, where:
  - `kind ∈ {GRANT, REVOKE, CONFIRM, ADOPT, END, PERFORM, ACCEPT, *}`;
  - `object` is a grant id, a pair `(subject, ckind)` for ADOPT/END, a pair `(activity, work)` for PERFORM, a work for ACCEPT, an atom for GRANT, or `*`.
  - **`(*, *)` is a legal atom.**
- **Covers.** `Covers(S, π) ⟺ π ∈ S ∨ (kind(π), *) ∈ S ∨ (*, *) ∈ S`. There is no nested wildcard inside an object.

## S-2 · Acts (true history H; every field is defined)

Every act `e` has these fields:
- `id`;
- `kind`;
- `holder`;
- `performer`, for PERFORM and ACCEPT;
- `τ(e)`, the act time;
- `ord(e)`, needed only under C10 = given.

Kind-specific fields:

| Kind | Fields |
|---|---|
| GRANT | `grantee`, `scope ⊆ Atom`, `period = [from, until]` with `until ∈ T ∪ {∞}`. **The true history has no ⊥ period**; a missing period is a *recording* phenomenon (C7, see S-6) [Rev R2-10]. Also `proviso ∈ {0, 1}` and `bars ⊆ Purpose × Perf × (Work ∪ {*})` |
| REVOKE, CONFIRM | `target` (a grant id) |
| ADOPT | `subject`, `ckind`, `cperiod = [cfrom, cuntil]`. **Claim identity:** `claim(a) := id(a)` [Asm] |
| END | `target` (a claim id). Prospective only [Asm: revoke, supersede and annul of a claim are collapsed; their ex-tunc and successor variants are out of scope] |
| PERFORM | `activity ∈ {implement, verify, review}`, `work` |
| ACCEPT | `work` |

**Record times:** `ρ(e) ∈ T ∪ {never}`, the instant the act was recorded. They are used only when `C1 ∈ {a_tau, a_rho}`.

## S-3 · Parameters

**There are no defaults.** Every evaluation states all of the parameters below.

**Validity and currency parameters, Θ:**

| Parameter | Values | Tag |
|---|---|---|
| `C1` | `b` / `a_tau` / `a_rho` (S-4.7) | Pol·C1 |
| `RD1` | `K × EK → {persist, cascade}` | Pol·RD 1 [Rev R2-11] |
| `δ` | `EK → {independent, dependent}` | Pol·RD 1 (δ) [Rev R2-11] |
| `BASIS` | `{any, all}` | Cand |
| `C10` | `{given, no_same_instant}` | Pol·C10 |
| `ιg` | `{closed, half_open}` | Pol·C14 |
| `ιc` | `{closed, half_open}` | Cand |
| `C6` | `{off, same_subject_proxy}` | Pol·C6. The proxy is **provisional**: the register requires a recorded conflict relation, which is not modelled [Rev R2-16] |
| `BAR` | `{any, all_root_exempt, all_root_not_exempt}` | Cand [Rev R2-13] |
| `C15` | `{no, yes}` (a delegated period must lie inside the parent's period) | Pol·C15 [Rev R2-25] |
| `Root` | `⊆ Hol × Atom`, static | Asm (C12: only "static" is modelled) |

**Recognition parameters, Ρ:**

| Parameter | Values | Tag |
|---|---|---|
| `λ⁺`, `λ⁻` | `T_{≥0} ∪ {∞}` | Pol·T1-20 |
| `υ` | `{does_not_count, counts_as_undetermined}` | Pol·RD 6 |
| `π` | `{open_ended, undetermined}` | Pol·C7 |
| domains `Dτ` and `Dρ` | finite and stated | Asm |
| world bound `B` | stated | Asm |
| `now(k)` | a function | stated input |

## S-4 · Validity layer V

### S-4.1 Order and availability

**Order** (`prec(f, e)`):
- C10 = given: `(τ(f), ord(f)) < (τ(e), ord(e))`, lexicographic. τ and ord must be defined and distinct.
- C10 = no_same_instant: `τ(f) < τ(e)`.

**Instant of an act:**
- under C1 ∈ {b, a_tau}: `inst(e) := τ(e)`;
- under C1 = a_rho: `inst(e) := ρ(e)`, and in `prec`, τ is replaced by ρ (S-4.7).

**Availability.** `avail(f, p)`, where the point `p` is either an act `e` or an instant `t`:
- **Before:** `prec(f, e)` for an act point; `inst(f) ≤ t` for an instant point (C16 inclusive).
- **Under C1 = a_tau:** additionally `ρ(f) ≠ never ∧ ρ(f) ≤ s`, where `s = τ(e)` for an act point and `s = t` for an instant point.
- **Under C1 = a_rho:** the condition is already in `inst`.

**R2-08 is closed.** Under both `a_*` variants, an act counts at an instant **only once recorded**. This includes the constituting ADOPT and every END, REVOKE and CONFIRM.

### S-4.2 Form (well-formedness)

`Form(e)` holds iff:
- `kind(e)` is legal;
- for GRANT: `from ≥ inst(e)` [Src S0058 L77];
- for REVOKE and CONFIRM: the target exists in H, is a GRANT, and `avail(target, e)`;
- for END: the target exists, is an ADOPT, and `avail(target, e)` [Src r1.1 "later"];
- under C1 ∈ {a_tau, a_rho}: `ρ(e) ≠ never` (written `RecOK(e)` in S-0; under b, `RecOK ≡ T`).

**On the claim period:** a claim period starting before `τ(a)` is **not** ill-formed. It is harmless, because Current requires `τ(a) ≤ t` [Rev R2-26: stated].

### S-4.3 Need (required atoms)

| Kind | Need(e) |
|---|---|
| GRANT | `{(GRANT, π) : π ∈ scope(e)}` |
| REVOKE, CONFIRM | `{(kind, target)}` |
| ADOPT | `{(ADOPT, (subject, ckind))}` |
| END | `{(END, (subject(a), ckind(a)))}` if the target `a` exists and is an ADOPT; **otherwise Need is undefined** |
| PERFORM | `{(PERFORM, (activity, work))}` |
| ACCEPT | `{(ACCEPT, work)}` |

### S-4.4 Endings of a grant at a point

For a grant `g` and a point `p`, define `Endings(g, p)` as the set of pairs `(kind, instant)`:
- **expiry:** `(expiry, x)` if the point lies after the period. Under `ιg` = closed this means `time(p) > until(g)`; under half_open, `time(p) ≥ until(g)`. Here `x = until(g)`.
- **revocation:** `(revocation, inst(r))` for every REVOKE `r` of `g` with `Valid(r)` and `avail(r, p)`.

**First endings.** `FirstEnd(g, p)` is the set of kinds attaining the minimum instant in `Endings(g, p)`. **Ties yield both kinds** [Asm].

**Started.** `Started(g, p) := time(p) ≥ from(g)`. Under ιg, the lower bound is always inclusive.

### S-4.5 Alive and survival

**Alive.** `Alive(g, p) ⟺` all of the following hold:
- `Valid(g) ∧ avail(g, p) ∧ Started(g, p) ∧ Endings(g, p) = ∅`;
- `proviso(g) = 0`, or there is a CONFIRM `c` of `g` with `Valid(c) ∧ avail(c, p)`;
- `Parent(g, p)`.

**Parent.** `Parent(g, p)` holds iff:
- ROOT ∈ `Basis(gact)`, where `gact` is the GRANT act `g`; **or**
- there is `g′ ∈ Basis(gact)` with `SurvivesFor(g′, p, δ)`.

`SurvivesFor(g′, p, δ) ⟺ Alive(g′, p) ∨ (Endings(g′, p) ≠ ∅ ∧ ∀ k ∈ FirstEnd(g′, p): δ(k) = independent)`

*Remark:* a not-yet-started or unconfirmed parent does not survive [Asm].

### S-4.6 Basis, Auth, Valid

**Basis.** `Basis(e)` is defined iff `Need(e)` is defined. It contains:
- ROOT, if `∀ π ∈ Need(e): Covers({x : (holder(e), x) ∈ Root}, π)`;
- every grant `g` (a GRANT act, `g ≠ e`) such that:
  - `grantee(g) = holder(e)`;
  - `∀ π ∈ Need(e): Covers(scope(g), π)`;
  - `Alive(g, e)`;
  - and, if `kind(e) = GRANT ∧ C15 = yes`: `period(e) ⊆ period(g)`.

**Note:** Basis does **not** depend on `Form(e)` or `RecOK(e)` [Rev R2-07].

**Restr(e)** (IND-1, authority restriction): `kind(e) = ACCEPT ∧ ∃ f: PERFORM implement on work(e), holder(f) = holder(e), Valid(f), avail(f, e)` [Src S0067 I-8; S0043 L28].

**Auth(e):**
- `NA` if `Need(e)` is undefined;
- otherwise `T` iff `Basis(e) ≠ ∅ ∧ ¬Restr(e)`;
- otherwise `F`.

**Valid(e)** `:= Form(e) ∧ Auth(e) = T`. `RecOK` is part of Form under the `a_*` variants.

### S-4.7 The C1 variants (Pol·C1) [Rev R2-08]

- **b:** acts establish authority; records only evidence it. `avail` uses the act order only.
- **a_tau** ("a recorded act, judged at the time of the act"):
  - each act's validity is judged **at its own act time**, using only acts recorded by then;
  - this is the *recursive* reading: f's own validity uses f's own cut `ρ ≤ τ(f)`;
  - [Ded] it follows from r1.1 (1), "**at the time of that act**", applied to every act;
  - the alternative "literal" reading (every act judged with e's cut) is **not** used. It gives an act authority derived from records made after that act.
- **a_rho** ("the act happens when recorded"): every act takes effect at `ρ(e)`. The model is C1 = b applied to the recorded acts, with τ := ρ (AX-NoBackdate requires ρ ≥ τ).

### S-4.8 Current(c, t) for a claim made by ADOPT `a`

`Current(c, t)` holds iff all of the following hold:

| | Condition |
|---|---|
| 1 | `Valid(a)` |
| 2 | `avail(a, t)`; under b and a_tau, `τ(a) ≤ t` |
| 3 | `t ∈_ιc cperiod(a)` |
| 4 | there is no END `x` of `c` with `Valid(x) ∧ avail(x, t)` |
| 5 | `Carry(a, t)` |
| 6 | if C6 = same_subject_proxy: there is no `a′` with `subject(a′) = subject(a)`, `ckind(a′) = ckind(a)`, `prec(a, a′)`, `avail(a′, t)` and `Current(a′, t)` |

Condition 6 is recursive on later adoptions, so it is well-founded on a finite H [Rev R2-16].

**Carry(a, t):**
- if ROOT ∈ Basis(a): T;
- otherwise, with `Q = ∃` under BASIS = any and `Q = ∀` under BASIS = all: `Q g ∈ Basis(a): Survives(g, t)`.

`Survives(g, t) ⟺ Alive(g, t) ∨ (Endings(g, t) ≠ ∅ ∧ ∀ k ∈ FirstEnd(g, t): RD1(ckind(a), k) = persist)` [Rev R2-11: expiry and revocation can differ, and the first ending decides; ties need every kind present to persist].

### S-4.9 Eligibility Elig(e, p, w)

Values: `NOT_VALID`, `INELIGIBLE`, `ELIGIBLE_ASSERTED`, `ELIGIBLE_ATTESTED`, with flags.

1. If `¬Valid(e)`: `NOT_VALID`.
2. **IND-2:** if `p = independent_verification` and there is an implement PERFORM `f` on w with `avail(f, e)` and `performer(f) = performer(e)`: `INELIGIBLE` [Repo A-1.4].
3. **IND-5 bars.** Let `G = Basis(e) − {ROOT}` and `barred(g) := ∃ (p, performer(e), w or *) ∈ bars(g)`.

   | BAR | Result is INELIGIBLE if |
   |---|---|
   | any | some `g ∈ G` is barred |
   | all_root_exempt | `G ≠ ∅`, every `g ∈ G` is barred, and ROOT ∉ Basis(e) |
   | all_root_not_exempt | `G ≠ ∅` and every `g ∈ G` is barred |

4. **IND-3:** if `p = governance_review`, there is an implement `f` on w with `avail(f, e)` and `performer(f) = performer(e)`, and the disclosure is missing: flag `DISCLOSURE_REQUIRED`.
5. Otherwise: `ELIGIBLE_ATTESTED` if `attested(e)`, else `ELIGIBLE_ASSERTED` (IND-7).

## S-5 · Records

**Entries.** An entry is `(pos, ê, ρ̂)`:
- `pos ∈ ℕ⁺` is unique;
- `ê` is the act as recorded, and **any field may be ⊥**;
- `ρ̂ ∈ T ∪ {⊥}`.

`E_k` is the set of entries with `pos ≤ k`.

**Axioms:**

| Axiom | Content | Tag |
|---|---|---|
| AX-Truth | every non-⊥ recorded field equals the true value; every recorded act occurred | Asm |
| AX-AppendOnly | entries are never changed or removed | Asm. Unverifiable on BC-7 |
| AX-NoBackdate | `ρ ≥ τ` | Src |
| AX-PosRho | positions follow record time: `pos(x) < pos(y) ⇒ ρ(x) ≤ ρ(y)` | Asm. Needed only for C1 = a_* results [Rev R2-05] |
| AX-Domain | every true value of a ⊥ field lies in its stated domain | Asm [Rev R2-04] |

## S-6 · Recognition layer R

### S-6.1 Worlds W_k (Θ, Ρ)

A world is a complete history over the recorded act ids, built in four steps.

**1. Contradictions.** For every id that has several distinct recorded variants, choose one variant.

**2. Unknown (⊥) fields.** Assign each ⊥ field a value from its domain:

| Field | Domain |
|---|---|
| τ | `Dτ` |
| holder, grantee, performer | the recorded values of that role, plus **one fresh value** `?new` (an unrecorded party) [Rev R2-02] |
| target | the recorded acts of the right kind, plus **`?unrecorded`**, a fresh act id absent from the world (then Form fails: the target does not exist in the world) |
| subject, work, activity, ckind | the recorded values, plus `?new` (activity and ckind are enumerated over their finite sets) |
| scope | **every subset of `N_w ∪ {(*, *)}`**, where `N_w` is the set of atoms queried in that world. [Ded] This is exact, because Covers is only queried on `N_w` |
| period | if ⊥ under π = open_ended: `[τ, ∞)`. Under π = undetermined: `from ∈ {d ∈ Dτ : d ≥ τ}` and `until ∈ {d ∈ Dτ : d ≥ from} ∪ {∞}` [Pol·C7] |
| ρ (C1 = a_* only) | `{d ∈ Dρ : d ≥ τ}`, subject to AX-PosRho |

**3. Order.** Under C10 = given, the order within each equal-τ group is fixed as follows:
- every linear order of the group that respects the relative order of its recorded `ord` values;
- acts with ⊥ ord, and acts whose τ became equal by assignment, may take **any position** [Rev R2-01].

**4. υ-closure (RD 6).** Let `X_w` be the recorded acts of kind REVOKE, END, CONFIRM, or an ADOPT that could supersede under C6, with `Form = T` and `Auth = F` in the world.
- Under υ = does_not_count: only the world itself.
- Under υ = counts_as_undetermined: one variant per subset `Y ⊆ X_w`, in which every act in Y has Auth **forced to T**. This represents a basis that exists but is unrecorded.

  In such a variant a forced act is treated as having the pseudo-basis `{UNREC}`. In Carry and Parent, UNREC **survives** [Asm].

  **This value reintroduces the "freeze any decision" risk** of v1 safeguard 6 [Rev R2-03(b)].

**Bound.** If the number of worlds exceeds B, `Rec = U` with the reason "bound". U is always sound, so the function is total [Rev R2-17].

### S-6.2 Verdict

For φ ∈ {Valid(e), Auth(e), Current(c, t), Elig(e, p, w) = ELIGIBLE_*}, with question time `t_φ`:
- `t_φ = τ(e)` for Valid, Auth and Elig;
- `t_φ = t` for Current.

```
Rec_k(φ) = U                      if the subject act/claim id is not in E_k, or W_k = ∅
         = T   if ∀w:  t_φ(w) ≤ now(k) − λ⁺ ∧  φ(w)
         = F   if ∀w:  t_φ(w) ≤ now(k) − λ⁻ ∧ ¬φ(w)
         = U   otherwise
```

**Elig:**
- `Rec_k(Elig …) = T` means "eligible (some grade) in every world".
- A relevant implementer that is ⊥ or unrecorded gives a world where the performer equals the verifier. **So the result is U, not ELIGIBLE** [Rev R2-14].

**Reading of TD-001 r1.1** (interpretation, not deduction [Rev R2-03]): r1.1 ≈ (λ⁺ = 0, λ⁻ = ∞, υ = ?).
- [Rev, final point 3] **Read uniformly, C1 settles υ:**
  - C1 = b → υ = counts_as_undetermined ("never 'not authorized'" applied to the ending act);
  - C1 = a_* → υ = does_not_count.
- A non-uniform reading leaves υ open.

## S-7 · Operational layer O

- `Eff_k(e) ⟺ e ∈ E_k ∧ Rec_k(Valid(e)) = T ∧ Proc_k(e)`. `Proc` is a **required argument** (no default).
- `Eff_obs := e ∈ E_k ∧ Proc_k(e)` is the behaviour observed on BC-7: START does not consult grants ([Src] S0071 L134).
- `UsableFrom(e) := min{k : Eff_k(e)}`.

## S-8 · Dated questions

**Evidence types:**

| Type | Statement | Bound |
|---|---|---|
| `Wit⁺ = {(d′, p)}` | position p was on record by date d′ | lower bound only |
| `Wit⁻ = {(d′, p)}` | position p was **not** on record at date d′ (**absence witness**) | upper bound |

**BC-7 provides neither type today.** Both are listed as **required evidence**, not assumed [Rev R2-06].

`K(d) := [lo, hi]` where:
- `lo = max{p : (d′, p) ∈ Wit⁺, d′ ≤ d} ∪ {0}`;
- `hi = min{p − 1 : (d′, p) ∈ Wit⁻, d′ ≥ d} ∪ {k_max}`.

`RecAt_d(φ)`:
- `v` if `Rec_k(φ) = v` for every k in K(d) (reading at `now(k) := d`);
- `U` otherwise;
- **`CONFLICT` if lo > hi** (reported, not silently resolved).

**Consequence:** without absence witnesses, `hi = k_max`, so `RecAt_d` is **almost always U**.

## S-9 · Theorems: premises, claims and status

| Id | Premises | Claim | Status |
|---|---|---|---|
| **Th-1** | H finite; prec strict and well-founded; Need, Basis, Alive, Parent and Survives read only acts with `avail(·, e)`, or the instants ≤ t | V (Form, Auth, Valid, Basis, Alive, Current, Elig) exist and are unique; computation terminates | to prove (S-10) |
| **Th-2** | as Th-1 | Valid(e) depends only on `{f : avail(f, e)} ∪ {e}`; Current(c, t) depends only on `{f : avail(f, t)}` | to prove |
| **Th-3** (repaired [Rev R2-04]) | AX-Truth, AX-Domain; no contradictions in `E_k`. **(R-Prem)** every recorded act has no ⊥ field, **except** acts whose recorded non-⊥ fields force them to be *inert for φ*: their recorded τ > t, or their recorded target has recorded τ > t (then they are ill-formed or later in every world). **Comp(k, t)**: every act with τ ≤ t is in `E_k`. C1 = b | **(b) soundness:** `Rec_k(φ) ∈ {V(φ), U}`. **(c) completeness:** `Rec_k(φ) = V(φ)` when the horizon condition holds **and** (υ = does_not_count **or** every recorded act with τ ≤ t has `Auth = T` in V) | to prove |
| **Pr-9** | Th-3 premises at `t = τ(e)` | `Eff_k(e) ⇒ Valid(e)` | to prove |
| **Th-4** | λ⁺ < ∞; late entries allowed | Rec is not monotone in k | proved in r1 (construction); re-checked |
| **Th-5** (existence [Rev R2-05]) | C1 = b; A-Root holds `(*, *)`; open world | for every λ⁻ < ∞ there is a record state with `Rec = F` while a completion consistent with every axiom has `V = T`. **Corollary:** under C1 = b, `λ⁻ = ∞` is the **only** value sound on all record states | to prove |
| **Th-5a** (new) | C1 = a_tau; AX-PosRho; AX-Truth. **RComp(k, s)**: every act with ρ ≤ s is in `E_k`. [Ded] RComp(k, s) holds whenever some entry in `E_k` has a known ρ > s, under AX-PosRho | for φ about e with `τ(e) = s`: `Rec_k(φ) ∈ {V(φ), U}` **for negatives too**. Under C1 = a, **record-based negatives are sound** once a later-recorded entry exists. This is the formal content of C1-a's auditability | to prove |
| **Th-6** | Th-3 premises fail (Comp fails) | a T verdict can be wrong. *Only the "if" direction is claimed* [Rev R2-22] | follows from Th-4 |
| **Th-7** | finite domains, bound B | Rec is a total function | to prove |
| **Th-8** (repaired [Rev R2-06]) | AX-AppendOnly | `E_k` is recoverable from today's store; `RecAt_d` as in S-8 is sound **given** `Wit⁺` and `Wit⁻` | to prove |
| **Pr-10** | — | C1 = b and C1 = a_* are incomparable | proved in r1 by examples; re-checked |

## S-10 · Proofs

**Th-1.** Each definition in S-4 refers only to:
- acts `f` with `avail(f, e)`, which implies `prec(f, e)`;
- or, for an instant point t, acts with `inst ≤ t`;
- or, recursively, to Basis or Survives of grants whose GRANT act precedes the point.

The delegation chain strictly descends in `prec`. Current's condition 6 recurses on adoptions strictly later in `prec`.

The order `prec` is strict and well-founded on a finite H, so a recursion along any linear extension defines every value exactly once. Uniqueness follows by induction, and termination by finiteness. Elig reads only Valid and Basis, which are already fixed.

Under C1 = a_rho, the same argument applies with ρ in place of τ.

Under C1 = a_tau, `avail` adds `ρ(f) ≤ τ(e)`. Since each act's validity is fixed with its own cut, and the cut condition only removes candidates, the recursion is unchanged. ∎

**Th-2.** Immediate from the proof of Th-1: every reference is to an available act. ∎

**Th-3.** The proof has four steps.

1. **Inert acts.** Fix a world w. Every act x allowed ⊥ by R-Prem is inert for φ in every world:
   - an ill-formed act is never Valid, so it enters no Basis, no Endings, no CONFIRM, no Restr and no C6 condition;
   - an act with τ > t is not available at any point relevant to φ, by Th-2.
2. **The other recorded acts.** They carry true fields (AX-Truth), and they include every act with τ ≤ t (Comp). So each world agrees with H on every act that can affect φ. With υ = does_not_count there is one effective world class, and `φ(w) = V(φ)`. This gives (b) and (c).
3. **υ = counts_as_undetermined.** The forced variants change Auth only for acts x with `Auth(x) = F` in H. If such an x has τ ≤ t, a variant may differ from V, so φ is not constant. The result is U, which is still sound (b). If no such x exists, nothing is forced among the relevant acts, and (c) holds.
4. **C1 = b only.** Under a_*, availability also depends on ρ (Th-5a).

∎

**Pr-9.** `Eff_k(e) ⇒ Rec_k(Valid e) = T ⇒ V` (Th-3(b) at `t = τ(e)`). ∎

**Th-5.**
- Choose the record state `E = {e}` with `e` a PERFORM by a holder `b ≠ Root`, `τ(e) = 1`, `now − λ⁻ ≥ 1`. With no grant on record, every world has `Auth(e) = F`, so `Rec = F`.
- The completion `H′ = {g*, e}`, where `g*` is a ROOT GRANT to b of scope `{(*, *)}` with `τ(g*) = 0`, recorded at position 2, is consistent with AX-Truth, AX-NoBackdate, AX-AppendOnly and AX-Domain. In H′, `Valid(e) = T`.
- This works for every finite λ⁻, which gives the corollary.
- **Under C1 = a_*,** the completion needs `ρ(g*) ≤ τ(e) = 1` while `pos(g*) > pos(e)`. Under AX-PosRho this forces `ρ(e) ≤ ρ(g*) ≤ 1`, so it is possible only if the act and the grant were recorded at the same instant. **This is the hidden assumption the reviewer flagged, now stated** [Rev R2-05]. Th-5a covers that case.

∎

**Th-5a.**
- Under C1 = a_tau, `Valid(e)` depends only on acts with `ρ ≤ τ(e)` (S-4.1). RComp(k, τ(e)) says all of them are recorded. With AX-Truth, the argument of Th-3 applies to the set `{f : ρ(f) ≤ τ(e)}` instead of `{f : τ(f) ≤ t}`.
- **[Ded] Under AX-PosRho,** an entry `y ∈ E_k` with `ρ(y) > s` implies that every x with `ρ(x) ≤ s < ρ(y)` has `pos(x) < pos(y) ≤ k`.

∎

**Th-7.** Every world component is finite: the variants, the domains (Dτ and Dρ finite, `?new` single), the scope subsets of a finite `N_w`, and the orders. With the bound B the function is total. ∎

**Th-8.** By AX-AppendOnly, today's entries with `pos ≤ k` are exactly `E_k`.
- `Wit⁺` gives `pos ≤ lo` present at d. `Wit⁻` gives `pos ≥ hi + 1` absent at d.
- So the true prefix at d is some `E_k` with `k ∈ K(d)`.
- A value constant over K(d) is the value at d.

∎

## S-11 · Not decided by this model

| Kind | Items |
|---|---|
| Reserved policies | C1 · RD 1 (per kind × ending kind, δ per ending kind) · RD 6 (υ) · T1-20 (λ⁺, λ⁻) · C7 (π) · C10 · C6 (provisional proxy only) · C14 · C15 · C12 (static only: flagged) |
| Candidates (not minted) | ιc · BAR · BASIS |
| Assumptions | claim identity · the END collapse · ties of first endings · the UNREC survival rule · unconfirmed parents do not survive · AX-Truth · AX-AppendOnly · AX-PosRho · AX-Domain |
| Out of scope (unchanged from Phase A) | C2 citations, suspension, C5, annulment with an effective time and RD 2 late values, C13 consumption, C4 joint authority, IND-4 and IND-6 mechanisms |

## Appendix I · Interchange format (for independent implementations)

Every implementation, the oracle included, must expose these functions over plain JSON-like data. **No defaults are permitted.**

**Act** (a dict). Keys:
- `id`, `kind`, `holder`, `performer`, `tau`, `ord`;
- `grantee`, `scope` (a list of `[kind, object]`; an object is a string, `"*"`, or a list for pairs), `period` (`[from, until]`, with `until` a number or the string `"inf"`), `proviso` (bool), `bars` (a list of `[purpose, performer, work]`);
- `target`, `subject`, `ckind`, `cperiod`, `activity`, `work`, `attested` (bool), `disclosure` (bool).

**History:** `{"acts": [Act…], "rho": {id: int | "never"}}`.

**Theta** (a dict) with all S-3 keys: `C1`, `RD1` (`{"decision|expiry": "persist", …}`), `delta` (`{"expiry": …, "revocation": …}`), `BASIS`, `C10`, `iota_g`, `iota_c`, `C6`, `BAR`, `C15`, `root` (a list of `[holder, atom]`).

**Entry:** `{"pos": int, "act": Act (any value may be null = ⊥), "rho": int | null}`.

**Rparams:** `lam_pos`, `lam_neg` (a number or `"inf"`), `upsilon`, `pi`, `D_tau` (a list), `D_rho` (a list), `B` (int).

**Functions:**

```
valid(history, theta)                              -> {id: bool}
auth(history, theta)                               -> {id: "T"|"F"|"NA"}
current(history, theta, cid, t)                    -> bool
elig(history, theta, eid, purpose, work)           -> "NOT_VALID"|"INELIGIBLE"|"ELIGIBLE_ASSERTED"|"ELIGIBLE_ATTESTED"
rec(entries, k, q, theta, rparams, now)            -> "T"|"F"|"U"
    q ∈ ["valid", id] | ["auth", id] | ["current", cid, t] | ["elig", id, purpose, work]
rec_at_date(entries, d, wit_pos, wit_neg, q, theta, rparams) -> "T"|"F"|"U"|"CONFLICT"
```
