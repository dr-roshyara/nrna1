# MD-073 §01 — Case Trace and Verdict

## The chain being attempted

$$
r \longrightarrow EvalReq(K,r,EC,\Gamma) \longrightarrow Det_r(\cdot,EC) \longrightarrow Sat(K,r,\Gamma)
$$

per Definition 6.18 (`20260906-003947...`, line 671): `Sat(K,r,\Gamma)=Det_r(EvalReq(K,r,EC,\Gamma),EC)`.

## Case record

| Field | Value | Source |
|---|---|---|
| `r` | `r_1 = PaymentConfirmed(S)` | `20260906-075153...` §21A.3, line 139 |
| `K` (relevant slice) | Evidence-assimilated state containing `e_1` and `p_1` (below) | §21A.5 |
| `e_1` | `⟨Source=PaymentLedger, Observation=PaymentConfirmed(S), Time=t_1, Method=SystemRecord, Provenance=π_1⟩` | §21A.5, lines 236–244 |
| `p_1` | `PaymentConfirmed(S)`, with `Evid(e_1,p_1)` | §21A.5, lines 249–255 |
| `EC` (schema) | `EC = ⟨Req, Rules, Scope, ER, TR, AR⟩` (Definition 2.20) | `20260906-002452...` lines 1069–1077 |
| `EC` (instance for this case) | **Not present.** The worked example uses the bare symbol `EC` (§21A.17 line 806, §21A.25 line 1203) inside `Det(K,ReleasePermitted(S),EC,Γ)`; it never constructs a value of the Definition 2.20 shape (no `Req`/`Rules`/`Scope`/`ER`/`TR`/`AR` are assigned for this case). `DC_release = ⟨Alternatives, Requirements, Authority, Time, Constraints⟩` (§21A.3) is a differently-shaped tuple that is never identified as being, or mapped onto, this `EC`. | Corpus-wide: no `EC=⟨...⟩` instance exists for this case in any document. |
| `Γ` | **No definition anywhere in the corpus** — neither a schema nor an instance. Used only informally as "context" (`20260906-002301...` line 1972, in a sentence, not a definition). | Searched Parts 01–02, 06, 21, 21A directly; no `Definition n.n — Γ` or equivalent exists. |
| `EvalReq(K,r_1,EC,\Gamma)` | **Cannot be invoked.** Its own general description (§6.17) is a worked *illustration* for a structurally different requirement shape ("identity established by two independent authoritative sources": `SourceAuthority(e_1)`, `SourceAuthority(e_2)`, `Independent(e_1,e_2)`, `Supports(e_1,r)∧Supports(e_2,r)`) — a two-source pattern. `r_1` has exactly one evidence object (`e_1`); the two-source pattern does not apply, and no general (non-illustrative) procedure for `EvalReq` exists to fall back to. Corpus-wide grep confirms `EvalReq(` occurs in exactly one place in the entire corpus outside this review and MD-070/EKS-44 — its own definitional statement (line 607) — never invoked with concrete arguments anywhere. | `20260906-003947...` §6.17, lines 602–641; corpus-wide grep, see Method below |
| `Det_r` | **No body anywhere in the corpus, for any `r`.** Given only as a type signature `Det_r: 𝒱 × EC → 𝕊_sat` (lines 658–664). Corpus-wide grep for `Det_r(` returns exactly one hit outside this review and MD-070/EKS-44/backlog references — the definitional statement itself (line 671). No instance, rule, or partial computation for any requirement exists in any of the ~7,010 files under `docs/knowledgeos/`. | `20260906-003947...` §6.18, lines 645–672; corpus-wide grep |
| `Sat(K,r_1,\Gamma)` result in the worked example | Stipulated directly: `Sat(K,r_i)=Satisfied` for `i=1,...,4` (§21A.17, line 776) — **not derived** from `Det_r(EvalReq(...),EC)`. Confirms MD-070 Finding 5 for this specific requirement. | §21A.17, lines 760–783 |

## Exact transformations attempted, and where each stopped

1. `r_1 = PaymentConfirmed(S)` — established directly from the corpus. **No issue.**
2. `K` restricted to `{e_1, p_1}` — established directly. **No issue.**
3. Attempt `EvalReq(K,r_1,EC,\Gamma)`:
   - Requires a concrete `EC`. Looked for one: Definition 2.20 gives the *shape* only; no instance for
     `r_1`, or for `DC_release` generally, exists anywhere. **STOP — missing input.**
   - Requires a concrete `\Gamma`. Looked for one: no definition of `\Gamma` exists anywhere in the
     corpus, of any kind. **STOP — missing input, more severe than (a): not even a schema exists.**
   - Requires a general evaluation procedure applicable to a single-evidence-object requirement.
     Looked for one: §6.17's only worked illustration is for a two-independent-source shape, which
     does not fit `r_1` (one evidence object, `e_1`). No general procedure exists to fall back to.
     **STOP — no applicable procedure, independent of (a)/(b).**
4. `Det_r` was never reached, because step 3 already fails. Recorded anyway (hypothetically) because
   the mission asks for the first missing dependency at *every* stage reachable: `Det_r`'s own body is
   absent corpus-wide, so even a successful step 3 would still fail at step 4, for an independent
   reason (see Method).

## Assumptions made (disclosed, not silently applied)

- None. No value was substituted for the missing `EC` instance, `Γ`, or `Det_r` body. No analogy from
  a different requirement's illustration (§6.17's two-source case) was applied to `r_1`, because doing
  so would be constructing a semantic adapter the corpus does not itself supply (mission rules 5, 6).

## Unresolved dependencies (in the order the trace hit them)

1. A concrete instance of `EC` for `r_1` (or for `DC_release` generally), constructed via Definition
   2.20's own schema.
2. A formal definition of `\Gamma` (currently: none, at any level — not even a type).
3. A general (non-illustrative, non-two-source-specific) evaluation procedure for `EvalReq`.
4. A computable body for `Det_r`, for any requirement whatsoever.

## Method (corpus-wide checks, reproducible)

```text
grep -rn "EvalReq(" docs/knowledgeos/            → 1 definitional hit (line 607) + this
                                                    review's/MD-070's/EKS-44's own citations of it;
                                                    zero concrete invocations.
grep -rn "Det_r("   docs/knowledgeos/            → 1 definitional hit (line 671) + this
                                                    review's/MD-070's/EKS-44's own citations of it;
                                                    zero concrete invocations.
grep -n  "EC\b"     20260906-075153...           → 2 hits, both the bare symbol inside
                                                    Det(K,ReleasePermitted(S),EC,Γ); no
                                                    EC=⟨...⟩ construction anywhere in the file.
grep -n  "\\Gamma\b" 20260906-002301..., ...002452 → informal-context usage only; no
                                                    "Definition n.n — Γ" anywhere.
```

## Classification

**BLOCKED.**

First missing semantic dependency (strict order): **a concrete instance of `EC` for the selected
requirement, and a formal definition of `\Gamma` (which does not exist at any level) — both required
simultaneously as `EvalReq`'s second and third arguments, and both absent.** Even setting this aside
hypothetically, `Det_r`'s complete absence of computational content anywhere in the corpus (confirmed
corpus-wide, not just for this case) independently blocks the chain at its final step.

## What this does, and does not, establish

- It does **not** establish that `Sat` is undefinable — only that the *current* corpus does not supply
  enough concrete material to compute it for this or, by the corpus-wide `Det_r`/`EvalReq` grep
  results, for **any** case.
- It does **not** reopen or downgrade GAP-004 (already CLOSED WITH QUALIFICATION per MD-070) — GAP-004
  was about whether the *definition* survives adversarial review as a type-level contribution (it
  does). This phase is about a different, narrower question: whether *this one selected case* can be
  carried through to a computed value using only what the corpus already supplies (it cannot).
- It sharpens EKS-44's recorded problem with a positive, reproducible, corpus-wide confirmation
  (`Det_r(`/`EvalReq(` each occur exactly once, at their own definitions, nowhere else) rather than
  the single-example observation MD-070 originally made.
- The `\Gamma`-has-no-definition-anywhere finding is new — not surfaced by MD-070's five findings —
  and is filed separately as `EKS-47`.
