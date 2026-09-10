# MD-090 §03 — Definition Evolution / Transformation Ledger / Negative-History / Cross-Lane Transfer

## Definition Evolution Registry (selected load-bearing objects, every version preserved)

### `Sat`
| Version | Part | Codomain/shape |
|---|---|---|
| v1 (birth) | 01 §21 | `Sat(K,r)∈{0,1}` — Boolean, explicit disclosed note that richer forms are anticipated |
| v2 | 02 §2.27 | `Sat(K,r)=⟨status,degree,evidence,reason⟩`, `status∈{Satisfied,Unsatisfied,Unknown,Partial,Conflicted}` (5-valued nested) |
| v3 | 03 §3.14 | `Sat:𝕂×Req→𝒮_sat`, `𝒮_sat={S,U,P,C}` — flat 4-valued, drops v2's tuple fields |
| v4 ("formalized") | 05 §5.19 | `Sat(K,r,Γ)`, `S_sat={Satisfied,Partial,Unsatisfied,Unknown,Conflicted}` — 3-arg, 5-valued again, explicit text "Part III introduced generalized satisfaction. We now formalize it" (citing v3, though v3's own codomain was 4-valued, not 5) |
| v5 (used in `Det_r` composition) | 06 §6.18 | `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` — the only place `Sat` is *composed* from sub-functions rather than primitive |
| v6 (Theorem 7.1 proof) | 07 | `Sat(K_t,r)=Satisfied`/`Unsatisfied` — reverts to a bare Boolean-flavored two-value use inside one proof |
| v7 (worked examples) | 21a/21a-rev2 §21A.17 | `Sat(K,r_i)=Satisfied` — stipulated directly, no computation shown |
| Template instances | 12, 14, 15, 16, 18, 19, 20, 21 | `Sat(K,r)` always 2-arg, always primitive, inside `Δ_X={r∈Req(X):¬Sat(K,r)}` — never the composed v5 form |

No version explicitly supersedes or is reconciled against any other; each is presented locally as
"the" definition within its own part.

### `EC`
| Version | Part | Field structure |
|---|---|---|
| v1 (birth) | 01 §19 | `⟨Req,Rules,Scope,EvidenceRequirements,TemporalRequirements,AuthorityRequirements⟩` — 6 fields, full names |
| v2 | 02 §2.20 | `⟨Req,Rules,Scope,ER,TR,AR⟩` — same 6 fields, abbreviated names |
| Time-indexed variant | 01, 04–05, 12, 17 | `EC_t` — used inconsistently; dropped in 02–03, 06–11, 13–16, 18–21 |
| Sibling, not identified with `EC` | 18 | `AC` (Architecture Contract) — structurally parallel, never stated as related to `EC` |

### `Δ` (Knowledge Gap)
Four incompatible signatures, per §01/§02: bare contract-implicit `Δ_t` (17 §17.3); 2-arg `Δ(K_t,EC_t)`
with no `Req`/`Sat` shown (17 §17.32, a drift *within* Part 17 itself); contract-typed `Δ_X(K,XC)=
{r∈Req(XC):¬Sat(K,r)}` (the dominant template form, 9+ domain instances per §01); `Δ_Arch(K,AC)`
(18 §18.40, template form with an `_Arch`-specific contract type).

### `Decision`
| Version | Part | Signature |
|---|---|---|
| v1 | 01 §30 | `Decision(Det,Policy)` — 2 args |
| v2 | 02 §2.23 | `Decision=f(K,Q,Γ,P,A)` — 5 args |
| v3 | 03 §3.39 | `Decision(d,K,Q,P,A,Γ)` — 6 args, reintroduces explicit output `d` |
| v4 (once only) | 13 §13.26 | `Dec(K,Q,P_1,A,Γ)` — different function name entirely, never reused |
| v5 | 16 §16.5 | `D=⟨Q,A,K,EC,U,C,Π,Γ,t,Auth,Status⟩` — first-class tuple object, not a function |
| v6 | 16 §16.24 | `D:K×Q×A×Γ→A` — a function again, coexisting with v5's tuple in the same part |

No stated equivalence connects any two of these six forms.

## Transformation Ledger (chronological, this 23-file span only)

1. **Part 01 §21–23**: `Sat`/`EC`/`Δ_t`/`Zero` all born, in sequence, within one document.
2. **Part 01 §37–39**: "ABK-1 is the universally unique minimal KnowledgeOS kernel" explicitly
   WITHDRAWN — "That would be circular" — replaced by "validated candidate-adequate architecture."
3. **Part 02 §2.27**: `Sat` REFINED from Boolean to a 5-status tuple.
4. **Part 03 §3.14**: `Sat` REFINED again to a flat 4-value codomain (silently drops v2's tuple fields).
5. **Part 04 §4.53**: `EC→Req→Sat→Δ→Zero` chain RESTATED as a boxed summary (not a new birth, contra
   MD-067/076's prior attribution).
6. **Part 05**: full Gap Algebra, Completeness, and Contractual Zero apparatus built out; explicit
   self-correction within the same part — "It is tempting to assume `Δ_{t+1}⊆Δ_t`. That is **not
   universally valid**" (§5.14), immediately followed by a formal Theorem/Corollary pair.
7. **Part 06 §6.17–18**: `EvalReq`/`Det_r` BORN, `Sat` COMPOSED (v5) from them for the only time in the
   corpus — then never touched again.
8. **Part 06 §6.43–44**: `Policy_Det`/`Threshold` born, each a short, self-contained, 2–3-use
   construction, explicitly disclaiming universal semantics ("A threshold without semantics is not a
   mathematical epistemic rule").
9. **Part 07 Theorem 7.1**: proves gap-monotonicity failure using `Sat` (v6) in a one-off Boolean form.
10. **Part 08 §8.14–15**: definition/theorem glyph drift (`≡_EC` defined, `≈_EC` proven) — internal,
    unflagged.
11. **Part 09 §9.2**: `r` REDEFINED from scratch as a relation-instance tuple, unrelated to and never
    cross-referenced against Parts 01–08's `r`-as-requirement.
12. **Part 13 §13.26, Theorem 13.65**: `Determination⇏Decision` given its most extensive treatment in
    the corpus — a dedicated section plus a named theorem.
13. **Part 16 §16.5, §16.38–39**: `Decision` given both a tuple form (v5) and a function form (v6) in
    the same part; two named separation theorems restate `Determination⇏Decision⇏Action`.
14. **Part 16 §16.42 vs. Part 18 §18.7**: two candidate bounded-context decompositions (5 vs. 7
    contexts) proposed five hours apart in the same session, neither referencing the other.
15. **Part 17 §17.3 vs. §17.32**: `Δ_t`'s own signature drifts *within* one part, from bare
    contract-implicit to explicit 2-argument, ~900 lines apart.
16. **Part 18 §18.48–50**: Theorem/Proof apparatus weakens — Lemma 18.1 has a full proof (glyph `□`,
    differing from Parts 16–17's `∎`); Theorems 18.1–18.2 have no Proof section at all.
17. **Part 20**: entire document duplicated within itself (corpus-hygiene defect, not a theory event).
18. **Part 21 §21.4, §21.18**: `Det(K,q,EC,Γ)` defined via `Sat(K,r)` as primitive (Definition 21.4);
    no computation of `Sat` supplied anywhere in Part 21 either.
19. **21a → 21a-rev2**: pipeline EXTENDED one stage further (adds Authorization/Action/Outcome); four
    of 21a's own negative/failure-path sections REMOVED without acknowledgment, while their claims are
    retained in the "what this example proves" list.

## Negative-History Register (explicit rejections/withdrawals, this 23-file span)

| Claim rejected | Part | Disposition |
|---|---|---|
| ABK-1 is the unique minimal kernel | 01 | WITHDRAWN, explicitly (circularity) |
| `Score(E)=Σ w_i` evidence-strength additivity | 06 §6.10 | REJECTED, "not generally valid" |
| `EstimateExists⇒EstimateValid` | 06 §6.36 | REJECTED |
| Universal total order on epistemic status | 02, 03 | REJECTED twice, independently, each part gives its own counterexample |
| `sim(x,y)≈1⇒x≡_sem y` (Embedding Equivalence Fallacy) | 20 §20.19 | REJECTED |
| `Possible<Feasible<Plausible<Probable<Determined` total ordering | 15 §15.18 | REJECTED, "generally invalid," then the underlying 6-term boundary is later EXTENDED to 8 terms without noting the extension |
| `BoundedContext⇒Microservice` | 18 §18.42 | REJECTED, "the implication is invalid" |
| `≥3 telemetry tests pass⇒register as primitive` (external GoF-crosswalk falsification logic, cited from the post-T22 segment, not T21 itself — cross-referenced here for completeness) | n/a (post-T22, MD-089) | REJECTED as "not logically justified" |
| 21a's own negative-branch demonstrations (missing-premise, payment-rejected, source-conflict, AI-invents-rule) | 21a→21a-rev2 | REMOVED, not explicitly retracted — flagged in §01 as an undisclosed evidentiary gap, not a stated withdrawal |

## Cross-Lane Transfer Register

**Inbound citations found** (this 23-file span citing material outside itself): Part 01 alone carries
~18 explicit citations to an unnamed prior "corpus"/"earlier theory"/"previous research"/"previous
audit" — several recording corrections/withdrawals (e.g. the ABK-1 minimality claim). Parts 02–03 carry
**zero** such external citations — only internal "Part I established..." backward references. Parts
10, 14, 17 explicitly cite specific *later*-numbered parts of this same 23-file corpus by number (Part
III's estimand discipline cited from Part 10; Part IX's causal distinctions cited from Part 14; Parts
XII and XV cited from Part 17) — i.e. this rewrite is internally cross-referenced by part-number from
Part 10 onward, but not in Parts 02–09.

**Outbound / no-transfer finding, most consequential**: this 23-file corpus's own `Decision`/`Act`/`ADR`
apparatus (Parts 16, 18) and the post-T22 segment's independently-built `ActionRationale`/`AR_t`/
`Warrant` apparatus (MD-089's own Thread 3, one day later in the corpus's chronology) show **no
citation in either direction**, in either extraction pass. Recorded as `IDENTITY UNRESOLVED —
INSUFFICIENT EVIDENCE` in §02, not investigated further this phase — a named candidate for the next
chronological frontier.

**Firewall discipline maintained**: `theory-extraction/` never accessed by any of the eight extraction
agents or by this adjudication.
