# MD-082 §02 — The Part VI Internal Wiring Correction

## The claim being corrected

MD-076 (2026-09-09): *"the decisive 3-argument `Sat(K,r,Γ)=Det_r(EvalReq(...),EC)` is never wired to
`Δ_t`/`Zero` — every concrete `Sat` stipulation in the corpus uses the older 2-argument `Sat(K,r)`
instead, including documents written after the 3-argument form existed."* This claim was repeated,
verbatim in substance, by MD-077 (§02, "the decisive `Sat(K,r,Γ)=Det_r(EvalReq(...),EC)` is never wired
to `Δ_t`/`Zero`"), used as a load-bearing premise by MD-078's own Terminal Classification table, and
never independently re-checked by MD-079, MD-080, or MD-081.

## The correction, with full evidence

**`[Def 6.18]`** (§6.18, line ~666–672, already quoted in MD-078):

```
Sat(K,r,Γ) = Det_r( EvalReq(K,r,EC,Γ), EC )
```

**`Definition 6.2 — Determination`** (§6.27, lines 965–977, same file, verified directly this phase):

```
A proposition p is determined under K,EC,Γ if:

Det(K,p,EC,Γ)
⟺
∀r∈Req_p(EC,Γ), χ_EC(Sat(K,r,Γ))=1.
```

**§6.28 "Determination and the Gap"** (lines 985–1002, same file, verified directly):

```
Δ_p(K,EC,Γ) = {r∈Req_p(EC,Γ) : χ_EC(Sat(K,r,Γ))=0}

Det(K,p,EC,Γ) ⟺ Δ_p(K,EC,Γ)=∅.
```

**§6.73 "Determination and Zero"** (lines 2520–2546, same file, verified directly):

```
Zero(K,EC,Γ) can be understood as: ∀r∈Req(EC,Γ), Det(r).
At the proposition level: Det(p) ⟺ Δ_p=∅.
Thus the global and local forms are structurally consistent.
```

**§6.74 "Local Zero"** (lines 2550–2577, same file, verified directly):

```
Zero_p(K,EC,Γ) ⟺ Δ_p(K,EC,Γ)=∅.
Zero_p ⟺ Det(p).
Zero_p ⇏ Zero_global.
```

**The chain, stated plainly**: `Sat(K,r,Γ)` [§6.18] → `χ_EC(Sat(K,r,Γ))` [Part V §5.7, cited by
symbol] → `Det(K,p,EC,Γ)` [Def 6.2, §6.27] → `Δ_p` [§6.28] → `Zero_p`/`Zero(K,EC,Γ)` [§6.73–74] —
every arrow uses the identical symbol on both sides, within one continuous ~2000-line file.

## Why MD-076 missed this

MD-076's own search was for `Δ_t` (the time-indexed, global gap symbol used at `T5`) — `Δ_p`
(proposition-indexed, introduced fresh within Part VI itself) is a different, though structurally
parallel, symbol, and a literal `Δ_t`-string search would not surface it. This is not a defect in
MD-076's own method so much as a genuine limit of symbol-based searching that this phase's own more
thorough section-by-section trace of Part VI happened to cross. Recorded forward, MD-076's own text
unedited.

## Precise epistemic status — what is and is not established

**Established, `RECONSTRUCTED` at high confidence**: the *symbol* `Sat(K,r,Γ)` — introduced with a
specific composition at `[Def 6.18]` — is the same symbol consumed three times downstream, all within
Part VI, with no rival same-symbol definition anywhere in the corpus except Part V's own uncommitted
gloss (itself never given a computation rule either). No explicit textual cross-reference exists
(§6.27 never writes "per Definition 6.18" or "as defined above") — this is why the relationship is
`RECONSTRUCTED` and not `SOURCE-STATED` with full certainty, per this reconstruction's own standing
discipline against inferring identity from proximity alone. But the absence of a rival referent, unlike
every other case of symbol reuse this investigation has found (`r`, `Γ`, `Eval`, `Zero`), makes this the
single strongest case for `SAME OBJECT` found anywhere in the whole family.

**Not established**: that `Det_r`'s own body was ever exercised. Theorem 6.1's own proof (§6.29,
already quoted in MD-078) proves `Det(K,p,EC,Γ)⟺Δ_p=∅` **generically**, over `χ_EC(Sat(K,r,Γ))` treated
as an already-available Boolean fact — the proof holds regardless of whether that fact comes from
`Det_r(EvalReq(...))`, from a stipulated fiat value (the theory's own dominant pattern, `TP-1`/`T5`/T22),
or from any other source. **The wiring is semantic/definitional; the computation remains exactly as
absent as MD-070/076/078/079/081 each independently found.**

## Consequence for the central question this phase was asked to resolve

*"Does the corpus lack the acceptance semantics themselves, or does T21 merely fail to connect them to
its own `EC.Rules` object?"* — **Neither, precisely.** T21 *does* connect `Sat(K,r,Γ)` (which `Det_r`
was meant to compute) to the full downstream chain through `Zero` — the connection this reconstruction
previously reported missing is present, at the symbol level. What T21 fails to do is supply
`EC.Rules`'s own content and `Det_r`'s own body — the specific computation that would give
`Sat(K,r,Γ)` an actual value for a concrete case. **The semantic responsibility is present and wired;
the computational completeness is what remains open** — exactly the distinction the mission's own §7
required this phase to maintain, now demonstrated concretely rather than asserted.
