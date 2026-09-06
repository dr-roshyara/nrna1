---
artifact: THEORY-GAP-REGISTER
mandate: 20260830 re-verification §8, §15, §16
date: 2026-08-30
status: **21 gaps registered — 9 mathematical · 5 semantic · 3 computational · 2 empirical · 2 governance/normative**
discipline: gap documented → counterexample → corpus searched for an existing resolution → only then a proposal, labelled `VERIFIER RECOMMENDS`
---

# Theory Gap Register

**Register discipline (mandate §15).** No gap below carries a proposal unless the corpus search
returned nothing. Where the corpus **already resolves** a gap, that is recorded as
`CORPUS RESOLVES — NOT ADOPTED`, and **no verifier proposal is made**, because inventing a second
resolution for a solved problem is the failure `ES-005.4` names.

**Class key:** `M` mathematical · `S` semantic · `C` computational · `E` empirical ·
`G` governance/normative.

---

## 1. Register

| ID | Class | Gap | Counterexample / evidence | Corpus resolution? | Disposition |
|---|---|---|---|---|---|
| **TG-01** | **M** | **No `AuthorityAct` object.** Authority appears only as a function argument and a free-text field | CE-G1-1/2/3: revoked authority still mutates `K`; policy version drifts between authorize and execute; an event bypassing `Authorize` transitions normally | **NO** — searched: "authority act" is used ~25× as a concept, typed **never** | 🔴 **OPEN** · `VERIFIER RECOMMENDS` a typed act (§2.1) |
| **TG-02** | **M** | **`Sufficient` has no signature.** The parametricity defence is untested for *relational* sufficiency | `C_criminal` needs "≥3 independent sources"; `Evidence` has `source` but **no independence relation**, and gates are predicates over a decision with no declared accessor to cross-item structure | **NO** — the principle exists (DeepSeek, non-incorporated); the interface does not | 🔴 **OPEN** · `VERIFIER RECOMMENDS` §2.2 |
| **TG-03** | **M** | **No world/observation layer `(W, Ω)`** beneath `Observation` | `Identifiable(g,Ω)` quantifies over `W₁,W₂`; the 8-layer ontology starts at entities | ✅ **YES — `31.17–31.21`** | 🟡 **CORPUS RESOLVES — NOT ADOPTED** |
| **TG-04** | **M** | **`K` has no recognised-dimension set `D_t`** | executed: "not assessed" and "assessed, nothing found" both render as *no assertion*, provably indistinguishable | ✅ **YES — Zero lens `Ω/D_t/K_t/Z_t` + 6-way taxonomy + 5 non-collapse laws** | 🟡 **CORPUS RESOLVES — NOT ADOPTED** |
| **TG-05** | **M** | **No uncertainty carrier** | `id` excludes any `u`; evidence-level reliability ≠ claim-level uncertainty; `ℛ` is binary and payload-free; external annotation already refuted for `Π` | ✅ **YES — `31.24` `U(H)=(type,value,model,scope,source)`** | 🟡 **CORPUS RESOLVES (object) — algebra still `OPEN`** |
| **TG-06** | **M** | **`id` hashes a mutable field.** `id=H(P,e,c,t,Π)` ∧ `e.state` mutable | executed: withdrawal re-keys the assertion; every `ℛ` edge dangles; `StructuralValid` violated by a legal operation | **NO** — the defect was **introduced** by the §7 amendment | 🔴 **OPEN, BLOCKING** · `VERIFIER RECOMMENDS` §2.3 |
| **TG-07** | **S** | **`Authority` fourth sense** — EKP artifact trust-grade vs actor competence/standing | `authorities.yaml`, 5 ranks, `single_per_topic`, enforced by a running linter | **NO** | 🔴 **OPEN** — rename recommended, `SourceGrade` |
| **TG-08** | **M** | **`Evidence` has no identity, and the claim index `q` was dropped** | 230.15 writes `E_q` — evidence *for a claim*. The 9-field amendment removed the index; `ref` points at the referent, not the evidence item | ⚠️ partial — `E_q` indexes, does not identify | 🟡 **OPEN, minor** — blocks TG-02's independence relation |
| **TG-09** | **M** | **`δ` has no body for the commit case.** `Γ` is derived and is not a component of `K` | executed: `K₁ is K₀ == True` — the authorized, executed, historied commit changed nothing | **NO** | 🔴 **OPEN, BLOCKING** |
| **TG-10** | **M** | **`Σ` has no access to `ℛ`** | executed: two assertions in an explicit `contradicts` edge both read `('Supporting','Weak')` | **NO** — not on any prior list | 🔴 **OPEN — NEW** |
| **TG-11** | **M** | **`merge = union`; no `dedup` operator** | executed: the same proposition from two sources yields two assertions (`Π ∈ id`); corroboration ≡ duplication; merging never creates edges between semantic twins | **NO** | 🔴 **OPEN** |
| **TG-12** | **C** | **`Σ.str` has no rule.** No corpus passage maps an evidence set to an ordinal level | `COMPUTABILITY-MATRIX` §"two blocked entries" — *the programme's own finding, never addressed* | **NO** | 🔴 **OPEN** — falsifies "30/30" |
| **TG-13** | **C** | **`Assessment` has two rival signatures** | `P×Evidence×Context×Policy→Σ` (232.4) vs `f(Evidence,ClaimType,Model,Assumptions)`; composing with `Validation : K×X→Assessment` types Validation as returning a function | **NO** | 🔴 **OPEN** |
| **TG-14** | **C** | **`Qualify` has no body.** One corpus hit (`CaptureAndQualify(O)`, Step 170), undefined | the pipeline's **first stop**: nothing downstream is reachable without hand-supplied evidence | **NO** | 🔴 **OPEN, BLOCKING** |
| **TG-15** | **S** | **`Ω` carries ≥4 global senses**, two of them foundational and opposite in direction | knowledge space (`Zero(K_t)=Ω\Represented`) vs observation function (`Ω:W→O`), plus `(Ω,ℱ,P)`, plus residual possibilities, plus `Ω_D`, `Ω_E`, `Ω_A` | **NO** — unregistered by every prior pass | 🔴 **OPEN — NEW, and the most dangerous naming collision found** |
| **TG-16** | **S** | **`E`/`V` recorded by the corpus itself as live ambiguities** | `# step 263.md:9` — the corpus's **latest** reconstruction, contradicting the audit's "local rebindings" | — | 🔴 **OPEN** — G5 refuted |
| **TG-17** | **S** | **`Observation` is typed two incompatible ways** — a *field of* Evidence (230.15) vs the *pre-image of* Evidence (253) | a value cannot be both a component of `X` and the pre-image of `X` | **NO** | 🔴 **OPEN** |
| **TG-18** | **S** | **`Assurance` has ≥5 live definitions**, one self-referential | measured: `(A_τ,A_P,A_E,A_G)` · `BackwardTraceability` · `+ForwardLearning` · 7-tuple · "composed local contracts" | already `REFUTED` as definable (CB-1) | 🟡 **RECORDED — do not use the word** |
| **TG-19** | **E** | **`status ⊥ authority` is declared and measured collinear** | 13/13 `draft ⇔ provisional`, perfect biconditional over all 39 governed docs; 6 of 40 cells occupied | — | 🟡 **OPEN** — withdraws one leg of the `Σ ⊥ Γ` empirical support |
| **TG-20** | **E** | **The `Σ ⊥ Γ` executed witness is a tautology** | `evidence_volume` is a dead parameter — declared and passed, never read | — | 🔴 **WITHDRAWN as evidence** |
| **TG-21** | **G** | **Two unreconciled resolutions of the policy-change loop** | ratified **I-11 + R-1 stratification** (v0.2, GN-19, 2026-08-28) vs the audit's externalisation-to-a-human-act (2026-08-30) | ✅ the ratified one | 🔴 **OPEN** — `ES-005.4`: consume or extend, never a second |

**Additional, unlisted by any prior pass:** `ℐ` — the inferential procedure `E_q --ℐ--> Q` (230.15) —
and `𝒩`, the Knower space. Both are undefined symbols the 30-symbol audit never enumerated.

---

## 2. Proposals — `VERIFIER RECOMMENDS` only

> **Nothing in this section is `CORPUS ESTABLISHES`.** Each is offered only because §1's corpus search
> returned nothing. **TG-03, TG-04, TG-05 carry no proposal** — the corpus already resolves them and
> the correct next act is adoption, not invention.

### 2.1 TG-01 — a typed authority act

```
AuthorityAct = (actId, actor, role, scope, recordedAt, actText, recordedBy)
Authorize    : K × ProposedAction × AuthorityAct × Policy ⇀ Command | Refusal
Command      = (cmdId, op, target, actId, policyVersion)          -- actId is REQUIRED
Execute      : Command × Policy ⇀ Event | Refusal                 -- re-checks policyVersion
```

**What it closes:** revocation (CE-G1-1) — `Execute` can re-resolve `actId`; version drift
(CE-G1-2) — `Execute` takes `Policy`; bypass (CE-G1-3) — `Event` carries `actId`, and `δ` can
require it. **Act identity** makes "two commands, one act" and "one command, several acts"
expressible, and makes the estate's already-observed `grantId` collision detectable.

**Implementation correspondence:** `humanActRef` (132/132, measured) is the *ancestor* of `actText`.
This **extends** the running field; it does not create a second mechanism.

### 2.2 TG-02 — the sufficiency interface

```
Sufficient : Evidence × Context × Constitution → { True, False, Unknown }
Independent ⊆ ℰᵥ × ℰᵥ        -- required for corroboration-style constitutions
```

Three-valued because the corpus's gate algebra is already three-valued with
`Unknown → ResolutionBehavior`. **Content stays constitutional — only the socket is typed.**

### 2.3 TG-06 — restore identity stability

```
id = H(P, {e.ref}, c, t, Π)        -- project out the mutable e.state
```

Evidence *state* then belongs to the assertion's mutable projection, not its identity.
**Smallest possible change; removes a contradiction rather than adding a concept.**

---

## 3. Classification summary

| Class | IDs | Count |
|---|---|---|
| **Mathematical** | TG-01, 02, 03, 04, 05, 06, 08, 09, 10, 11 | **10** |
| **Semantic** | TG-07, 15, 16, 17, 18 | **5** |
| **Computational** | TG-12, 13, 14 | **3** |
| **Empirical** | TG-19, 20 | **2** |
| **Governance / normative** | TG-21 | **1** |

**Blocking (nothing downstream can be trusted until resolved):** TG-06, TG-09, TG-14.
**Already resolved by the corpus, awaiting adoption:** TG-03, TG-04, TG-05.
**New in this pass, on no prior list:** TG-10, TG-15, and TG-06 as a *contradiction* rather than a gap.
