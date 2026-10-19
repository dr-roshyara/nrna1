# ALM-r2 spec findings (from the independent oracle)

**Source:** only `../ALM-R2-SPEC.md`. No other file was read.
**Status:** these findings are the oracle author's observations. They are **not** dispositions.
**Tags:** `[F-nn]` in `oracle.py` refers to the entries below.
**Severity:**
- **X**: a theorem appears false (counterexample given);
- **I**: an internal inconsistency;
- **U**: underspecified or ambiguous (a choice was made);
- **N**: a note or consequence worth knowing.

Counterexamples marked "executable" are checked in `selftest.py`.

---

## A. Theorems that appear false

### F-36 · X · Th-3(b) soundness fails for φ = Auth(e)

**Executable.**

**Setup:** C1 = b. The true history is H = {x, a}:
- x: an END by root holder r, `τ = 1`, `target = a`;
- a: an ADOPT by r, `τ = 5`.

**V:**
- `Need(x) = {(END, (s, decision))}` is defined, because a exists and is an ADOPT.
- Root `(*, *)` covers it, so `Auth(x) = T`.

**Records:** `E_1 = {x}`, fully recorded.
- Comp(1, 1) holds, because x is the only act with `τ ≤ 1`.
- There are no ⊥ fields and no contradictions.

**Worlds:** a is absent, so `Need(x)` is undefined and `Auth(x) = NA`. The result is `Rec_1(Auth x) = F ≠ V = T`.

**Root cause:** S-4.3 makes `Need(END)` depend on whether the target *exists in H*, not on whether it is *available*. S-4.2 does the same for Form. A later, unrecorded act therefore changes Auth of an earlier act.

**Repair options:**
- make Need(END) use `avail(target, e)`;
- or exclude Auth from Th-3.

Valid is unaffected, because Form already requires `avail(target)`.

### F-38 · X · Th-5a fails as stated

**(a) φ = Current(c, t)** (executable). Th-5a quantifies "φ about e with `τ(e) = s`". Current about claim a is such a φ.

**Setup:** C1 = a_tau. The acts are:
- a: ADOPT by r, `τ = ρ = 1`, at pos 1;
- x: END of a by r, `τ = 2`, `ρ = 3`.

**Check:**
- RComp(1, 1) holds.
- `V: Current(a, 5) = F`, because x is valid and available at 5 (`τ ≤ 5` and `ρ ≤ 5`).
- `E_1 = {a}`, which gives `Rec = T`.

Current at t reads acts with `ρ ≤ t`, not `ρ ≤ τ(a)`. The premise RComp(k, τ(e)) is too weak; the theorem would need RComp(k, t_φ).

**(b) φ = Auth.** The F-36 construction also works under a_tau:
- x has `τ = ρ = 1`;
- a has `τ = ρ = 5` and is unrecorded, which RComp(k, 1) allows.

The result is `Rec = F` while `V = T`, which contradicts "sound for negatives too".

**(c) Missing premises.** Th-5a lists only C1 = a_tau, AX-PosRho, AX-Truth and RComp. It omits:
- AX-Domain (otherwise a ⊥ τ whose true value is outside `Dτ` gives worlds that all miss the truth);
- "no contradictions";
- an R-Prem analogue.

The proof borrows "the argument of Th-3", which needs all three.

**The [Ded] step itself checks out:** under AX-PosRho, `ρ(x) ≤ s < ρ(y)` implies `pos(x) < pos(y)`.

### F-39 · X · Th-8 needs an unstated premise

`E_k` is "recoverable" as the store at date d only if **positions are assigned in append order**. AX-AppendOnly says only that entries never change.

**Counterexample (premise: AX-AppendOnly only):**
- the entry at pos 2 is appended on day 1;
- the entry at pos 1 is appended on day 3.

**Check:**
- At d = 2 the store is {pos 2}, which is no `E_k`.
- Take `Wit⁺ = {(2, 2)}` and no `Wit⁻`. Then `K = [2, 2]`, and RecAt evaluates on `E_2 = {1, 2}`, including an entry that did not exist at d.
- If φ concerns the act at pos 1, `RecAt_d` can be T while at d its subject was not on record, so the true value at d is U.

**Needed premise:** "pos increases with append time", e.g. AX-PosRho with ρ equal to the append instant, plus a tie rule.

### F-31 · X (claim) · S-6.2's Elig note is false for an *unrecorded* implementer

The note says: "A relevant implementer that is ⊥ **or unrecorded** gives a world where the performer equals the verifier. So the result is U."

- An unrecorded PERFORM is in no world. With only the verifier recorded, every world is ELIGIBLE, so `Rec = T` (executable).
- The claim holds for a ⊥ performer only. That case depends on the verifier's performer being in the recorded performer pool, or on both being `?new`.

### F-37 · I · The premise of Th-1 is not met by the definitions; its conclusion still appears true

**Where the premise fails:** Th-1 assumes that Need, Basis and the others "read only acts with `avail(·, e)`". But:
- `Need(END)` (S-4.3) and Form (S-4.2) read whether the target *exists* in H;
- IND-2 and IND-3 (S-4.9) read PERFORM acts without Valid.

The proof's claim "Elig reads only Valid and Basis" is also inaccurate.

**Why the conclusion still appears to hold:**
- Existence is a static fact, so well-foundedness is not affected.
- The oracle's brute-force search found **exactly one** solution of the Valid equations in all 450 random histories (3 C1 variants), and in every hand test.

**Th-2 (Valid part):** it survives only because Form also requires `avail(target)`. As F-36 shows, the analogous claim for Auth is false.

### Theorems checked without finding a problem

- **Th-4 (non-monotone):** confirmed by an executable example.
- **Th-5 (C1 = b):** the construction checks out.
- **Pr-9:** holds for φ = Valid, since F-36 concerns Auth.
- **Th-7:** holds only *after* the missing domains of F-12, F-15, F-16 and F-17 are chosen; see F-40.
- **Pr-10:** not examined.

---

## B. Internal inconsistencies

| Id | Where | Finding |
|---|---|---|
| **F-02** | S-0 row Auth; S-4.6 note | S-0 says "Auth is independent of Form and of ρ(e)". **Under C1 = a_rho this is false:** `prec(f, e)` compares ρ(f) with ρ(e), so Basis(e) depends on ρ(e). If ρ(e) = never, Basis can contain only ROOT. Executable: the same grant with ρ(e) = 6 gives T, and with ρ(e) = 4 gives F. Under a_tau the claim holds, since the cut uses τ(e). |
| **F-24** | S-4.6 Restr vs S-4.9 IND-2/3 | IND-1 (Restr) compares **holder**(f) with holder(e). IND-2 and IND-3 compare **performer**. S-1 says that performers are used *only* by eligibility, so Restr using holders may be deliberate. But "the implementer may not accept" naturally concerns the performer. Implemented literally (holder). |
| **F-06** | S-4.2 + S-4.1 (a_rho) | `from ≥ inst(e)` uses inst = ρ under a_rho. Every GRANT whose period starts before its recording instant is **ill-formed**, so recording late invalidates the grant itself, not just postpones it. Implemented literally; executable test. |
| **F-07** | S-4.4 under a_tau | The revocation instant in Endings is `inst(r) = τ(r)`, but r counts only from ρ(r). Example: `until = 5`, a REVOKE with `τ = 3` and `ρ = 7`. At t = 6 FirstEnd = {expiry}; at t = 8 FirstEnd = {revocation}, retroactively. With RD1 differing per ending kind, `Current(a, t)` changes from T at t = 6 to F at t = 8. Nothing new happens in between except that an *earlier* revocation becomes recorded, and it then displaces the expiry as the first ending (executable). This is probably unintended. Implemented literally. |
| **F-32** | S-6.2 | `t_φ = τ(e)` for Valid, Auth and Elig even under C1 = a_rho, where the act "happens when recorded" (ρ). Implemented literally (τ). |

---

## C. Underspecification: choices made

| Id | Section | Alternatives | Implemented / why |
|---|---|---|---|
| F-01 | S-3 `now(k)` "a function" vs Appendix I `rec(…, now)` | function / scalar | **Both**: a callable is applied to k; a number is used as is. `rec_at_date` passes d. |
| F-03 | S-4.4 `time(p)` | τ(e) / inst(e) for an act point | **inst(e)**. This equals τ under b and a_tau; under a_rho it is consistent with "τ := ρ". |
| F-04 | S-4.1 "τ and ord must be defined and distinct" | distinct `(τ, ord)` pairs / globally distinct ord | **Distinct pairs** (with ρ for τ under a_rho). Violations raise `SpecError`. |
| F-05 | S-4.1, a_rho with `ρ = never` | inst undefined → error / never precedes | **Never precedes and is never preceded**, so it is unavailable everywhere. Form is already F via RecOK. |
| F-08 | S-6.1 step 1 "distinct recorded variants" | (i) choose one literally; (ii) merge compatible variants (non-⊥ values agree), choosing only among genuine conflicts | **(i) literally**, over distinct `(act, ρ̂)` pairs; identical duplicates collapse. Consequence: if one variant has ⊥ where another has a value, worlds with a different value exist, against AX-Truth. AX-PosRho uses the *chosen* variant's pos. Which pos and which ρ̂ apply when variants differ is not stated. |
| F-09 | S-5 "any field may be ⊥", incl. `id` | same act as some other entry / a distinct act | **A distinct act** with a fresh id `?id@pos`. |
| F-10 | S-6.1 table "recorded values of **that role**" | that role only / any party role; source E_k / world; Θ.root holders included or not | **That role only, all entries of E_k, Θ.root excluded** (literal). Consequence: a ⊥ holder of a REVOKE can never be a party that appears only as a grantee, or only in Θ.root. This is probably not intended, and it makes AX-Domain a strong assumption. |
| F-11 | `?new` | per role / shared; per act / global | **One global value shared across party roles.** So a ⊥ holder `?new` equals a ⊥ grantee `?new`: the same unrecorded party. |
| F-12 | S-6.1 table | `kind` ⊥ has **no domain** | The 7 legal kinds. Fields of the chosen kind that are not recorded are then ⊥ and enumerated. |
| F-13 | S-6.1 step 2 vs S-5 axioms | worlds filtered by axioms or not | Under C1 = a_*, worlds violating **AX-NoBackdate** (`ρ ≥ τ`, including a recorded ρ against an assigned τ) or **AX-PosRho** (over all chosen variants) are discarded. The spec mentions PosRho only for ⊥ ρ. Under b, ρ is ignored. |
| F-14 | S-6.1 period | only a whole-⊥ period is covered | A partly ⊥ period (`[from, ⊥]` or `[⊥, until]`) applies the same rule per component. |
| F-15 | `cperiod` ⊥ | **no domain** | Same rule as `period` under π. The constraint `cfrom ≥ τ` loses nothing, because S-4.2 says an earlier cfrom is harmless. |
| F-16 | proviso, attested, disclosure ⊥ | no domain | `{false, true}`. attested and disclosure are enumerated only for PERFORM and ACCEPT; other kinds get recorded-or-false. |
| F-17 | bars ⊥ | no domain (`Purpose × Perf × Work` is open) | `{∅, ALL}`, where ALL matches every triple. This is exact for the single Elig query, since only "barred or not" per grant matters. |
| F-18 | S-6.1 scope ⊥, "`N_w` = atoms queried in that world" | `N_w` depends on `Need(g) = {(GRANT, π) : π ∈ scope(g)}` of other ⊥-scope grants, so the definition is circular | `N_w` := ∪ Need(e) over the world's acts, **excluding ⊥-scope grants**, then every subset of `N_w ∪ {(*,*)}`. The [Ded] "exact" claim then fails when ⊥-scope grants delegate to each other: a true scope such as `{(GRANT, (PERFORM, (implement, w)))}` is not in the domain. No verdict-changing counterexample was found, because `(*, *)` and `∅` usually already produce disagreement, but exactness is not established. |
| F-19 | S-6.1 step 3 "equal-τ group" | τ groups / groups by the prec instant (ρ under a_rho) | **The prec instant.** Otherwise, under a_rho + C10 = given, ords from different τ-groups collide inside one ρ-group. |
| F-20 | S-6.1 step 3 | which pairs are constrained; equal recorded ords | Constrain x before y iff both have a **recorded** instant **and** a recorded ord, and `ord(x) < ord(y)`. Acts with equal recorded ords are unconstrained. After this step `ord := position` within the group. |
| F-21 | S-6.1 step 4 | "an ADOPT that could supersede under C6"; is X_w recomputed after forcing?; is the forced Basis `{UNREC}` or `Basis ∪ {UNREC}`? | An ADOPT counts iff C6 = proxy and some ADOPT with the same `(subject, ckind)` precedes it. X_w is computed **once** in the unforced world (no closure). A forced act has Auth = T and Basis = `{UNREC}` exactly. |
| F-23 | S-4.3 | illegal kind | Need is undefined, so Auth = NA (Form is F anyway). |
| F-25 | S-2 / S-4.9 / Appendix I | attested and disclosure are absent from the S-2 field table; "disclosure is missing" is not defined; Appendix `elig` returns no flags | `disclosure == false` means missing. Flags are exposed only through `elig_full`. A missing attested or disclosure key in the true history raises `SpecError`. |
| F-26 | S-4.9 IND-2/3 | must the implement f be Valid? | **No** (literal). An unauthorized or ill-formed implement PERFORM still blocks independent verification. |
| F-27 | S-4.9 | `performer(e)` for e not a PERFORM or ACCEPT | Undefined, so it is taken as `None` and matches nothing. |
| F-28 | S-4.9 step 3 | is UNREC in G? | It is in G and never barred. This matters only under all_* when a forced act is asked about. |
| F-29 | S-6.2 φ = Auth(e) | Auth is three-valued | φ(w) := (Auth(e) = T). NA and F both count as ¬φ. |
| F-30 | S-6.2 Current | world in which the claim id is not an ADOPT (kind ⊥) | φ = false. The V-layer `current()` raises in this case. |
| F-33 | S-6.1 Bound | count raw constructions or distinct histories; ⊥ in fields irrelevant to the kind | **Raw constructions** (variant × assignment × order × υ-subset). ⊥ in irrelevant fields is not enumerated, so B-sensitivity depends on these choices. |
| F-34 | S-7 | `min ∅`; range of k; Proc signature | `usable_from` returns `None`. k ranges over `1..k_max`. Proc is `proc(k, eid)`. |
| F-35 | S-8 | k ranges over integers `[lo, hi]`, including k = 0 (empty `E_0`, giving U) | As written. With `Wit⁺ = ∅`, RecAt is therefore always U, a stronger form of the stated "almost always U". |
| F-41 | S-4.8 cond. 2 | redundant under b and a_tau; under a_rho τ(a) ≤ t is not required | Literal. The V layer does not enforce AX-NoBackdate on the input history. |
| F-42 | S-2 ord | under C10 = no_same_instant | ord is ignored, and may be absent. |

## D. Notes and consequences

| Id | Finding |
|---|---|
| **F-22** | A GRANT with **empty scope** has `Need = ∅`. ∀ over ∅ puts ROOT into its Basis, so **any holder** (even one with no authority) validly makes it (executable). The grant is harmless, since it covers nothing, but it can carry `bars`. |
| F-43 | Covers is purely literal. `(GRANT, (PERFORM, *))` covers only a grant whose scope atom is literally `(PERFORM, *)`. An atom `(*, x)` with `x ≠ *` covers nothing. Delegating a family needs `(GRANT, *)` or `(*, *)`. |
| F-44 | λ = ∞ makes `now − λ = −∞`. So λ⁺ = ∞ never yields T, and λ⁻ = ∞ never yields F. This is consistent with the r1.1 reading. |
| F-45 | Expiry tie semantics depend on ιg: under half_open the grant has ended *at* `until`, under closed only *after* it, yet both record the ending instant as `until`. A same-instant revocation therefore ties under both. |
| F-46 | C15 containment `period(e) ⊆ period(g)` does not say whether ιg affects the upper bound. Implemented as `from_g ≤ from_e ∧ until_e ≤ until_g`. |
| F-47 | S-6.1 step 4 uses Auth = F, but forcing one act (e.g. a REVOKE) can turn another recorded act's Auth from T to F in that variant. That act is not then eligible for forcing (no closure; see F-21). |
| F-40 | Th-7 totality depends on the choices for F-12, F-15, F-16 and F-17. As written, these domains are missing, so the world set is not defined for such inputs. |

**Total: 47 numbered findings:**
- X (theorem false or unsupported): 4 (F-31, F-36, F-38, F-39);
- I: 6 (F-02, F-06, F-07, F-24, F-32, F-37);
- the rest are U or N.
