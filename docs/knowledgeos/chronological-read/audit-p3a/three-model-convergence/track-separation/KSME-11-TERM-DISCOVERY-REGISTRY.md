---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-11-BOUNDED-KERNEL-READINESS, KSME-11-TRANSITION-SEMANTICS-AUDIT, KSME-11-OBSERVATION-CATALOG]
derived_from: [all KSME-11 fork reports]
cross_track_dependency: none
---

# KSME-11 — Cumulative Term Discovery Registry

Merged from all four KSME-11 forks' term-level extraction, per the user's "Mandatory Comprehensive Term
Discovery Rule." This is a first pass, not exhaustive — several documents flagged below as "Pending" full
extraction (`REFINED-STEP-286`, `step-288/04`'s remaining relation notations, files 25-37's non-Qualify
content). Relationship notation: `A --verb--> B`.

| Term | First discovered in | Timestamp | Defined? | Source location | Status | Same-day traversal | Cross-midnight traversal | Thread |
|---|---|---|---|---|---|---|---|---|
| `K_t` (8 ratified primitives) | `D285-1-STATE-ONTOLOGY-MATRIX.md` | 2026-08-31 19:58 | Y | `step-049`/C-022, ratified FA-4 | Established | Completed | Completed (stable through Sep 1 22:19) | E_B/primitives |
| `(𝒜,ℛ)` (Assertion/Relation model) | verification lane, reused `D285-6` | 2026-08-31 | Y (as projection) | `t285_reconcile.py` executed | Established (as projection); NOT ratified as `K_t` itself | Completed | Completed | E_B/primitives |
| `π_K` | `D285-6` | 2026-08-31 19:58 | Y (definable), N (computable) | `D285-6 §1,4` | Established-not-computable | Completed | Completed | Qualify/G1 |
| `Qualify` | Step 170 (undefined hit); D285-6 formalizes | 2026-08-30 (Step 170); 2026-08-31 19:58 (D285-6) | N (typed, no body) | `D285-6 §4a` | G1-irreducible | Completed | Completed | Qualify/G1 |
| `G1` (irreducibility class) | `D285-6` | 2026-08-31 19:58 | Y (as register category) | `D285-6 §7` | Established | Completed | Completed | Qualify/G1 |
| `Terminus` (Qualify codomain proposal) | `24-CHAPTER-16-...` | 2026-09-01 01:40 | Y (proposed) | `24 §3` | Hypothesis `[H]`, unadopted | Completed | N/A | Qualify/G1 |
| `G-97` (Qualify partly normative) | `24-CHAPTER-16-...` | 2026-09-01 01:40 | Y (register item) | `24 §3,6` | Not-established, pending governance | Completed | Completed | Qualify/G1 |
| `Φ: Π_t→K_t` / `G-109` | `38-THE-LAYERED-MODEL` | 2026-09-01 22:19 | N (named, no body) | `38 §3` | Hypothesis (unadopted model); **not referenced downstream in step-292** | Completed | **Pending — 41-hour gap to step-292 unbridged, `UNRESOLVED-CONTINUITY-GAP`** | Qualify/G1 (adjacent) |
| "Missingness" (`Qualify`/`Query`, external info required) | `Step 272b` | 2026-08-30 22:50 | Y | `272B.11` | Established (negative result) | Completed | Completed | Missingness/Qualify-adjacent |
| `𝒪_K` (state-observation set) | Step 261.21 | 2026-08-30 | Y (defined), asserted closed w/o enumeration | `261.21` | Source-established (as a gap, from day one) | Completed | Completed | O_B/observations |
| `261.22`/`261.23` dependency chain & 6 non-finality reasons | Step 261 | 2026-08-30 | Y | `261.22-23` | Established | Completed | Completed | O_B/observations, Congruence |
| `TraceOrigin(x)` | `258.15` | (Step 258, primary) | N (untyped) | `258.15` | Load-bearing, unregistered | Completed | Completed | O_B/observations |
| `ExplainRevision(x)` | `258.16` | (Step 258, primary) | N (untyped) | `258.16` | Load-bearing, unregistered | Completed | Completed | O_B/observations |
| `Assess(K,x)`, `Authorize`, `Compare` | Steps 259/`012§46` | (primary) | Y (typed), N (body) | as cited | Hypothesis/Derived (kind uncertain) | Completed | Completed | O_B/observations |
| `orphan`, `circular_dependency`, `deg_ℛ` | `281`/EKP lint | — | Y (implemented) | as cited | Derived, relevance untested | Completed | Completed | O_B/observations |
| `Σ`-axis readings (`π_A…π_C`) | Q4A | — | Y (typed), disconnected from `K_t` | `289 §5` notes no `π:K→Σ` | Hypothesis | Completed | Completed | O_B/observations |
| `𝒯_candidate={Assert,Retract,Supersede,Merge,Split,LinkEvidence}` | Step 277 | 2026-08-30 22:01 | Y (explicitly non-minimal) | `277.1` | Derived, stable through 278-280 | Completed (≥22:00 rule applied) | Completed, forward through 278/279/280 | T_B/operations |
| Mandatory-membership-is-governance-not-derivation | `step-291/07` | 2026-08-31 23:24-23:45 | Y (7 routes tested, all blocked) | `07_governance-vs-derivation.md` | Source-established (a proof of non-derivability) | Completed | Completed | T_B/operations |
| `258.9` congruence criterion | Step 258 (primary); reused `step-288/04` | (Step 258) | Y | `258.9` | Established | Completed | Completed | Congruence |
| `258.30`/`258.23` commuting-diagram (`F∘T_H=T̄∘F`) | Step 258; reused `step-288/04` | (Step 258) | Y | `258.23,30` | Established, unproven globally | Completed | Completed | Congruence |
| `=` (bare structural equality) | `step-288/04,06` | 2026-08-31 22:26-22:33 | Y, tested | `06 §G` executed | Refuted (not a congruence) | Completed | Completed | Congruence |
| `261.19` per-operation equality matrix | Step 261 (primary); `step-288/04` | (Step 261) | Y (matrix, unresolved) | `261.19` | Unresolved by design | Completed | Completed | Congruence |
| `q:K→K/≡` (quotient) | Steps 258/260; `step-288/04` | (Steps 258/260) | Y (candidate forms), N (computable) | `258.12`,`260.11` | Not-established (0/8 properties) | Completed | Completed | Quotient/Congruence |
| Deterministic `T`/`δ` default | Step 257 | 2026-08-30 19:02 | Y | `257.10` boxed | Established (as default) | Completed | Completed | Transition-shape |
| Partial transition semantics | Step 251 | 2026-08-30 18:36 | Y (proposed, unproven) | `251.7` boxed | Source-claimed | Completed | Completed | Transition-shape |
| `A+¬A` conflict/contradiction state | Step 32 | 2026-08-28 10:23 | Y | `32.33` | Source-established | Completed | Completed | Contradiction-concept |
| `Conflict(p)` predicate | Step 60 | 2026-08-28 11:40 | Y | `60.5` | Source-established | Completed | Completed | Contradiction-concept |
| SSA candidate `δ` (Reiter) | `step-292/04` | 2026-09-02 19:14 | Y, executed | full formula, A2-A4 | Refuted (P3), `[PROP]` repair surviving | Completed | Completed | δ-construction |
| `Contr` (contradiction detector, proposed gate) | `step-292/04` | 2026-09-02 19:14 | N (named, not built) | boxed `[PROP]` | Hypothesis | Completed | Completed | δ-construction |
| Nondeterministic `δ(K,o)∈{K'₁,...}` (posed) | reviewer-prompt `step-289` | 2026-08-31 22:54 | N (question) | §20 | Unknown (never answered) | Completed | Completed | Determinism-question |
| Nondeterminism taxonomy (env/obs/policy/concurrent/genuine) | reviewer-prompt `step-290` | 2026-09-01 02:52 | Y (taxonomy only) | §13 | Unknown | Completed | Completed | Determinism-question |
| "deterministic state transition" (independent-lane claim) | `research/36` | 2026-09-01 12:54 | N (cited, unverified) | §4, tag `C-Q3` | Unverified claim | Completed | Completed | Independent-convergence-claim |
| `KR-STATE-02` (Probabilistic Transition System) | `mathematical_ideas.../20260904-035641` | 2026-09-04 03:56 | Y (formal, gated) | `P_{t+1}(K')=𝒰(...)` | Hypothesis-only | Completed | Completed | Probability-space (distinct thread) |

## Relationships recorded

- `Qualify --motivates--> Terminus (Ch.16 reframing)`
- `Terminus --does-not-close--> Qualify (self-audited by 24 §"Does this fill G1? No")`
- `Qualify (relocated) --predicts--> Φ/G-109 (structurally identical recurrence)`
- `Step 261.21 --introduces--> 𝒪_K --is-restated-by--> D285-7 --is-restated-by--> 10-GOVERNANCE-HANDOFF act#4 --remains-unclosed-through--> step-292/07`
- `Step 258.9/258.30 --defines--> congruence --is-tested-by--> step-288/06's "=" experiment --falsifies--> "= is a congruence"`
- `Step 257.10 --establishes-default--> deterministic δ --is-refuted-by--> step-292/04 (P3, contradiction collapse)`
- `step-292/04 [PROP] --proposes--> Contr-gated partial δ (shape 2), distinct from --> Step 32/60's contradiction-as-state (shape 5)`
- `Step 277 --proposes--> 𝒯_candidate --is-shown-non-minimal-by--> its own label --is-confirmed-ungrounded-for-membership-by--> step-291/07`
- `KR-STATE-02 --is-distinct-from--> the δ-determinism question (different thread, later, about K_t uncertainty generally)`

## Explicitly flagged as Pending (not yet extracted)

`REFINED-STEP-286.md` (66KB, unread across all four forks — a genuine corpus-coverage gap for future work);
`step-288/04`'s remaining relation notations (`≅_I`, `≅_P`, `∼_H`, `∼_F`) — cited but not individually
registered; files 25-37's content beyond Qualify-relevance (read for Qualify by Fork D, not mined for
other terms per this rule). None of these gaps changed any verdict in this pass; they are named for a
future targeted registry-completion pass, not treated as evidence of anything themselves.
