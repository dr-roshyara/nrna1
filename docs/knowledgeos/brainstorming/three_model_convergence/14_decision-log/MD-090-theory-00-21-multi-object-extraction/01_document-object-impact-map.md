# MD-090 §01 — Document→Object Impact Map (23 files, Theory-00-21 rewrite)

## Corrected provenance chain — the single most important finding

Every prior phase (MD-067 onward) treated the `Sat`/`EC`/`Δ`/`Zero`/`Det`/`Decision` chain as beginning
around Part IV–V. **A full multi-object pass shows it begins earlier, in Part 01 itself**, and is
restated/refined at nearly every subsequent part:

| Object | True birth | Evidence |
|---|---|---|
| `Sat(K,r)` | **Part 01 §21** (line ~1149) | "Define: `Sat(K,r)` to mean: the current Knowledge State K satisfies requirement r" — Boolean codomain `{0,1}`, explicit note "later versions may require richer semantics" |
| `EC` (Epistemic Contract) | **Part 01 §19** (line ~1071) | `EC=⟨Req,Rules,Scope,EvidenceRequirements,TemporalRequirements,AuthorityRequirements⟩` |
| `Δ_t` | **Part 01 §22** (line ~1190) | `Δ_t={r∈Req_t:¬Sat(K_t,r)}` |
| `Zero` | **Part 01 §23, Definition 23.1** (line ~1231) | `Zero(K_t,EC_t)⟺Δ_t=∅` |
| `EC→Req→Sat→Δ→Zero` chain (boxed) | **Part 01 §39** (line ~2116) | restated verbatim as the opening line of Part IV §4.53 and again as Part V's own opening — three citations of the same chain, only the first is the birth |
| `Det_r`/`EvalReq` | **Part VI §6.17–6.18 only** | exactly 2 occurrences each, confined to those two sections, never reused anywhere in the remaining 17 parts + 2 worked examples |
| `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` | **Part VI §6.18 only** | the decisive formula — never invoked again by that name anywhere in Parts VII–XXI/21a/21a-rev2 |

**`Sat` itself is nearly absent from the corpus once past its birth section.** Full-text census across
all 23 files: `Sat(...)` occurs as a genuine function-symbol invocation only inside the recurring
`Δ_X={r∈Req(X):¬Sat(K,r)}` template — confirmed independently re-instantiated, always with bare
2-argument `Sat(K,r)`, never `Det_r`/`EvalReq`, in at least **nine separate domains** across the corpus:
the original (Parts 01/05), persistence `Δ_P` (Part 19), retrieval `Δ_R` (Part 20), temporal `Δ_T`
(Part 12), causal `Δ_C` (Part 14), model/forecast `Δ_M`/`Δ_F` (Part 15), risk `Δ_R` [a *different*
`R`, risk not retrieval] (Part 16), decision `Δ_D` (Part 16), reasoning/cognitive `Δ_Cog` (Part 21),
and architecture `Δ_Arch` (Part 18). Outside this template, `Sat` appears exactly once in Parts 07–09
combined (confined to one theorem's proof, Part 07 Theorem 7.1) and not at all as a free-standing
concept anywhere else.

**Both flagship worked examples (21a, 21a-rev2) confirmed to stipulate `Sat(K,r_i)=Satisfied` by direct
assertion, with zero derivation shown and zero invocation of `Det_r`/`EvalReq`/`Eval` anywhere** —
extending MD-070's finding (made against an earlier, non-flagship example) to the theory's own final,
most-polished worked demonstration.

## Per-cluster impact summary

| Cluster | Parts | Central new/refined objects | `Sat`/`Det_r`/`EvalReq` engagement |
|---|---|---|---|
| Foundational birth | 01–03 | `Sat`, `EC`, `Δ_t`, `Zero`, `Eval`/`EVal` (drift), `Det` (drift), `Decision` (drift), identity relations | `Sat` born here; `Det_r`/`EvalReq` absent |
| State/gap/evidence-evaluation core | 04–06 | `δ` transition system, `O_core`, gap algebra, `EvalReq`/`Det_r` (one-time), `Policy_Det`, `Threshold`, `Zero_p` | `Det_r`/`EvalReq` born and confined to Part VI §6.17–18 |
| Revision/identity/relations | 07–09 | non-monotonicity theorems, four-relation identity family (`=`,`≡_sem`,`≈_{Q,Γ}`,`≅_prov`), relation/graph semantics | `Sat` appears once (Part 07 proof only) |
| Inference/measurement/time | 10–12 | inference contracts, measurement/statistics apparatus, temporal semantics, `Δ_T`/`Zero_T` | `Sat` appears once (Part 12, `Δ_T` def) |
| Uncertainty/causality/models | 13–15 | `Determination≠Decision` (extensively theorem-ized), causal contract `Δ_C`, forecast contract `Δ_M`/`Δ_F` | `Sat` in template only (8 hits) |
| Decision-theory/learning/architecture | 16–18 | full decision/risk apparatus (`Δ_R`,`Δ_D`), learning/drift theory, DDD bounded-context/aggregate architecture, `Δ_Arch` | `Sat` in template only (4 hits) |
| Persistence/retrieval | 19–20 | `Δ_P`/`Zero_P`, `Δ_R`/`Zero_R`, the RAG-boundary theorems (`RAG≠Evidence≠Inference≠Truth≠Determination`) | `Sat` in template only (2 hits); **Part 20 internally duplicated in full** |
| Reasoning engine + worked examples | 21, 21a, 21a-rev2 | proof-object apparatus, `Δ_Cog`/`Zero_Cog`, `Γ_R` (Reasoning Context) | `Det_r`/`EvalReq` **absent**; `Sat(K,r_i)=Satisfied` stipulated by fiat in both worked examples |

## Cross-part structural drift (extends `EKS-54`, T21's own internal notational inconsistency)

This pass confirms `EKS-54`'s finding is far more extensive than previously characterized. Representative,
non-exhaustive list (full detail in each batch's own raw extraction report):

- **Pipeline-chain proliferation**: at least seven materially different `Evidence→...→Action` chain
  formulations across Parts 01, 04, 05(×2), 06, 10(×2), 13(×3), 14, 16(×2), 17, 18 — differing in stage
  count and stage names, never reconciled to one canonical form.
- **`r` overloaded at least five ways** across the corpus: individual requirement (Parts 01/05+,
  the dominant sense), a relation-type argument inside `LINK(x,y,r)` (Part 04), a rule tuple (Part 10),
  a relation instance `r=⟨type,source,target,...⟩` (Part 09), and a retrieved-candidate object
  (Part 20's `Promote(r,EC_E)`) — none cross-referenced.
- **`Eval`/`Det`/`Decision` arity and capitalization drift** already within Parts 01–03 alone (3-arg vs.
  4-arg `Eval`/`EVal`; `Det(EVal,Q,Γ)` vs. `Det(K,p,EC,Γ)`; `Decision` growing 2→5→6 arguments).
- **Inquiry-relative equivalence symbol drift**: `K_1≡_{Q,Γ}K_2` (Part 07) vs. `K_1≈_{Q,Γ}K_2` (Part 08)
  for the same functional concept; within Part 08 itself, the *definition* of contract-relative
  semantic preservation uses `≡_EC` but its own *proving theorem* (8.1) states and proves the result
  using `≈_EC` instead.
- **`EC_t` (time-indexed contract) convention appears and disappears inconsistently**: present in
  Part 01, 04–05, 12, 17; absent (replaced by bare `EC`) in Parts 02–03, 06–11, 13–16, 18–21.
- **`AuditTrace`/`Trace` given five different formal shapes** across Parts 04, 05, and 06 (three
  different renderings within Part 06 alone), with no stated equivalence.
- **`Δ` itself has at least four incompatible signatures** across Parts 16–18: contract-typed 2-arg
  with `Req`/`Sat` machinery (`Δ_R(K,RC)`, `Δ_D(K,DC)`), a bare time-indexed contract-free form
  (`Δ_t`, Part 17 §17.3), a differently-shaped 2-argument `Δ(K_t,EC_t)` with no `Req`/`Sat` shown
  (Part 17 §17.32, a drift *within* Part 17 itself), and `Δ_Arch(K,AC)` (Part 18).
- **Theorem/Proof apparatus inconsistently applied**: different QED glyphs (`∎` vs. `□`) across parts;
  Part 18's own Theorem 18.1 and 18.2 have no explicit Proof section at all, unlike every other
  "Theorem" in the corpus; Part 12 has an unnumbered, unproven item labeled "Theorem" in prose only.
  breaking its own Theorem 12.1–12.3 numbering.
- **Bounded-context decomposition drift, five hours apart in one session**: Part 16 §16.42 proposes
  5 candidate bounded contexts; Part 18 §18.7 (same session, later that day) proposes 7, splitting
  Part 16's single "Epistemic Analysis" context into three — neither references the other.
- **Corpus-hygiene defect**: Part 20 (91 KB) is a full internal duplicate of itself — the entire
  document repeated byte-for-byte back-to-back within the one file. Distinct from the cross-file
  `EKS-31` pattern; a within-file duplication, not previously documented.
- **21a→21a-rev2 revision silently drops evidentiary content**: several of 21a's negative/failure-path
  worked demonstrations (missing-premise, payment-rejected/`Sat=Unsatisfied`, source-conflict/
  `Zero=false`, AI-invents-a-rule) are absent from 21a-rev2, while 21a-rev2's own "what this example
  proves" list still asserts the claims those demonstrations existed to support.

No file in the corpus contains `Det_r` or `EvalReq` outside Part VI §6.17–6.18. This is now confirmed
by exhaustive full-text search across all 23 files, not merely the sampling this reconstruction relied
on in MD-076–088.
