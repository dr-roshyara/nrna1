# MD-071 §03 — Negative-History Register

Consolidates MD-069's "Retired formulations"/"Known contradictions" sections (`04_current-theory-
projection.md`) with MD-067's own contradiction/rejection findings and MD-070's own downgrade finding,
into the mission's own requested shape (§13): what was tried, what failed, and how the failure changed
the subsequent theory. Every entry is classified by the mission's own distinction — **RETIRED**
(explicit statement exists) vs. **NO_LATER_EVIDENCE** (the object simply stops being cited, no
statement exists) — never the second silently upgraded to the first.

## Explicit retirements (a statement exists)

| Object/claim | When | What happened | Consequence for the subsequent theory |
|---|---|---|---|
| `Sat(K_t,r)⟺K_t⊨Content(r)` (v5) | T12, 2026-09-02 17:53–18:00 | Born, explicitly falsified ("we should NOT conclude Sat=Entailment"), and formally retired — an HPA Supervisory Advisory removed the equation within a ~7-minute micro-cycle in the same document cluster | Replaced by an unspecified typed pipeline (`K_t^E→Cn_S→K_t^{I,S}→Eval_content→EVal_content⊆Eval_c`); this is the corpus's single fastest complete birth→death cycle for any object in the graph |
| `Sat_c(K_t,r)` as a *primitive* (v1/v2) | T9→T10, 2026-09-02 09:35–10:23 | Self-found `CE-1` factivity obstruction (same document as birth) downgraded all `[DEF]` labels to `[PROP]`; further `PB-2`/`PB-4` defects found in the refined v2; the object was then **reclassified**, not retired outright — demoted from primitive to a derived projection (`Sat_c := value∘Eval_c`) | The demotion, not a clean retirement, is itself evidence this corpus prefers repair-in-place over abandonment when a repair is available — contrast the T12 case above, where no repair was offered and full retirement followed |
| `Zero(K,EC)⟺Δ(K,EC)=∅`'s hypothesis-testing sibling: `Zero_{T,Π}` predicts representation-adequacy | T15, 2026-09-02 18:xx–2026-09-04 | Extensively tested (`KR-REP-REDUCTION`, `KR-BRIDGE-01`) — **definitively falsified**, a real 4-cell contingency-table negative causal result, not a self-found obstruction | The `Zero_{T,Π}` object itself survives (re-defined more rigorously at T17, "Theory v1.2 FROZEN"), but the specific hypothesis that it *predicts* representation adequacy does not — a genuine instance of the mission's own §13 distinction between a retired *claim* and a surviving *object* |
| The three `[THM-1]`/`[THM-5]`/`[THM-11]` theorems from T5 | T6, 2026-09-02 ~04:57–08:23 | Flagged by an adversarial review as "currently overstated or circular" | **Never formally retracted or repaired anywhere in the remainder of the 876-file traversal** — recorded here as a flagged-but-unresolved negative finding, distinct from a clean retirement (no later document states these theorems were fixed, withdrawn, or reproved) |

## `NO_LATER_EVIDENCE` (the object stops being cited; no retirement statement exists)

| Object/version | Last seen | Why this is NOT recorded as `RETIRED` |
|---|---|---|
| `K_t` v1 (Bayesian credence distribution) | T0 | Superseded in practice by v2's unrelated reinterpretation one turning point later, but no document says "v1 is wrong" or "v1 is retired" — it is simply never mentioned again |
| `Req` v1/v2 (T2's two parallel formulations) | T2 | Neither is cited again after `Req` v3 (T5) adopts the canonical name; no document states either was superseded |
| `EC_t` v1/v2 | T1/T3 | T5's v3 is presented as a fresh `RE-DEFINITION` with **no stated relation to either prior version** — not "EC_t v2 is retired, replaced by v3," simply a new definition under the same name |
| `ZeroLens`/`Boundary_t` branch (T11) | T11 | Confined to its own branch; the main T5 lineage is simply never engaged by it again — `UNRELATED_HOMONYM`, not retirement |
| `ℛ_req`/`ABK-1` branch (T14) | T14 (ratified), no later engagement with the F4 chain | The only governance-ratified apparatus in the entire graph, and it is `NO_LATER_EVIDENCE`-relative to the tracked F4 chain specifically — it is not retired, it simply never touches `EC_t`/`Req(EC_t)`/`Sat(K_t,r)` at any point |
| `Zero_{T,Π}`/Theory v1.2 FROZEN set (T15/T17) | T17 | Self-declared "FROZEN" but never independently ratified (per MD-067's own finding); no later document cites it, contradicts it, or extends it — `NO_LATER_EVIDENCE`, not retirement, and its own self-declared "frozen" status is itself unverified |
| The entire T18–T22 chain (the Theory-00-21 rewrite, `Sat` v7 included) | T23, 2026-09-06 09:44 onward | The corpus pivots to unrelated threads (Zoom/Biocomm/Epistemic-Value/GoF-pattern, then the K-1/K-2 governance track) — **none of these documents engage, cite, review, or extend the chain.** This is the terminal state of the whole traversal for the object this reconstruction has tracked since MD-057, and it is `NO_LATER_EVIDENCE`, explicitly not `RETIRED` — no document anywhere states the chain is wrong, withdrawn, or superseded |

## Contradictions preserved as historical events, never repaired

| Contradiction | Where | Status |
|---|---|---|
| T5's own `[DEF-20]` (contract-wide `Sat`) vs. `[DEF-21]` (per-requirement `Sat`) | `[00-47]`, same document | An internal arity ambiguity within the canonical source itself — never flagged or resolved in-corpus; T7 (`[00-51]`) silently resolves it in practice by fixing the per-`r` arity, but no document states the contract-wide sense was wrong |
| `Definition 23.1` / `Theorem 24.1` / `Axiom A7` — `Zero(K,EC)⟺Δ(K,EC)=∅` filed as three epistemic categories at once | Theory-00-21 Part I, §23/§24/§39 | Found by MD-070 (Finding 3), not by the corpus's own self-review — an unrepaired category conflation, now filed as `EKS-44` |
| Inconsistent self-disclosure of tautological character across `[THM 6.1]` (discloses) vs. `[THM 6.2]`/Part I's theorems (do not) | Theory-00-21 Parts I and VI | Found by MD-070 (Finding 2) — not itself a contradiction between claims, but an unrepaired inconsistency in how honestly the corpus flags its own definitional theorems |

## Rejected before formalization (falsification found before a claim was ever fully built)

| Candidate | Found by | Result |
|---|---|---|
| `Det(K,p,EC,Γ)`'s implicit assumption that `Req_p(EC,Γ)` is always non-empty | MD-070 (Finding 4) | Never tested anywhere in Parts V/VI (zero hits for "vacuous" or an equivalent safeguard) — recorded here as an *unaddressed risk*, not a formally rejected claim, since the corpus never explicitly considered and then rejected it; distinct in kind from the table above |

## What this register does not do

It does not merge `NO_LATER_EVIDENCE` entries into `RETIRED` status to make the theory's own history
look more resolved than the evidence supports (per the mission's own §12 anti-pattern warning). It
does not treat the T18–T22 chain's own current silence (T23) as a finding about the chain's
correctness — MD-070 already established that question separately (structurally sound, not computed;
see `MD-070-gap-004-adversarial-review/`), and this register keeps that finding distinct from the
chronological fact that the corpus stopped returning to the object.
