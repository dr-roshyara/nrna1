# `P-58` — `FA-7` Capability-Level Traceability Audit

**2026-09-09 · Lane T.** After [`75` `P-57`](./75-P57-BRAINSTORMING-ARCHAEOLOGY-GATE-SCOPE-EXECUTION-AND-SOURCE-OVERLAP-AUDIT.md).

# 0. ⭐⭐⭐ First — the three files. I had NOT read them, and they correct four earlier audits

**The honest answer to the question asked: no.** `P-52`/`P-53` read the 09-04 `MinKer` statement; ⛔ **these
three were never opened.** **Ninth miss — and the most consequential one yet for the minimality track.**

| # | correction | affected |
|---|---|---|
| **1** | ⭐⭐⭐ **`⪯_sem` is NOT undefined.** `K_1 \equiv_{sem} K_2 \iff K_1 \preceq_{cap} K_2 \land K_2 \preceq_{cap} K_1`, with `⪯_cap` = *"every observable trace can be losslessly simulated"* — **one named hole: `Trace(K,h)`**, and the corpus names it itself: *"the one hidden informal term in the foundation"* | `P-53`, `P-55` **`[QUALIFIED]`** |
| **2** | ⭐⭐⭐ **`Complexity` was already recommended for REMOVAL on 09-04** — *"I strongly recommend removing `Complexity` from the mathematical Kernel definition at this stage"* | `P-49` **corroborated**, and my framing was too harsh — **the corpus caught it first** |
| **3** | ⭐⭐⭐ **Capability ordering is named a *"foundational blocker"* with a full formal specification** — `⪯_cap` reflexive + transitive, then `≺_sem`, then `≡_sem`, and *"**this is necessary before `MinKer` becomes mathematically executable**"* | `P-54` **`[QUALIFIED]`** — see below |
| **4** | ⭐⭐ **The `Kernel` term is split**: a **DDD** definition (smallest bounded context responsible for identity/lifecycle/provenance) and a **mathematical** one, `\mathcal K_{epi} = \min_{\preceq_{sem}}\{K : K \models \mathfrak C_K\}` — *"they should be connected, but not identified"* | `P-50` **corroborated** |

⭐⭐⭐ **And a complete five-step programme exists** — `TODO 1` semantic equivalence · `2` capability
ordering · `3` irreducibility · `4` completeness · `5` the minimality theorem — **all `🔴 OPEN`**, inside
a **twenty-item register**. Its `TODO 3` states the `P-47` lesson verbatim: *"the old 13/12/8 operator
experiments are **evidence for this work, not the proof itself**"*, and the move required is from
*"operator X could not be removed in our simulator"* to *"**no admissible realization lacking capability
`c` can satisfy the semantic contract**."*

$$\boxed{\textbf{⭐⭐ } P\text{-}54\textbf{'s verdict } \mathbf{C} \textbf{ survives — nothing here RESOLVES granularity — but its reasoning was too weak.}}$$

⛔ *"Every substantive hit merely restates the problem"* is **wrong** for this material: **a decomposition
into five formally stated, separately provable obligations is not a restatement.** ⭐ The corpus knows
what is missing **and knows what would fix it**.

# 1. Executive verdict on the commissioned question

$$\boxed{\mathbf{C\ —\ DETERMINATION\text{-}ONLY\ TRACEABILITY.} \textbf{ ⛔ No } K_i \textbf{ reaches } \mathbf{T2}. \textbf{ The } 11\times7 \textbf{ matrix is } \mathbf{ENTIRELY\ ZERO.}}$$

⭐⭐⭐ **And it fails one level lower than expected — even `T0` fails.** Across **all nine** `FA`
documents: `persist*` **0** · `supersession|superseded` **0** · `warrant` **0** · `prior value|prior
state` **0** · `withdraw` **0** · `retire` **0** · `lost datum` **0**. ⭐ The only two non-zero probes
are **homonyms**: *"retain"* is **`RETAIN AS PARALLEL LANE`** (a git-branch disposition) and
*"provenance"* is the register's own **name and column header**.

$$\boxed{\textbf{⭐⭐ } \mathbf{B1\ is\ real\ and\ executed\ —\ for\ something\ else\ entirely.} \textbf{ It does not reach the kernel, at any of the three levels.}}$$

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ No cell touched, no mapping asserted, no refinement inferred.}$$

# 2. ⚠️ Disagreement — one, and it is substantive

**⭐⭐⭐ §6 asks me to define `T2` myself. I should not, because the corpus already defines it.**
The 09-04 material supplies the estate's own relation for *"the same capability"*:

$$K_1 \preceq_{cap} K_2 \iff \textbf{every observable trace of } K_1 \textbf{ can be losslessly simulated by } K_2$$

⛔ **Inventing a second Lane-T notion of capability sameness would violate `ES-005.4` — consume or
extend, never create a second — one audit after I criticised exactly that.** ⭐ So `T2` is tested
**against the corpus's relation**, ⚠️ **with its own stated limitation carried forward: `Trace(K,h)` is
not formalized, so `⪯_cap` is a *standard*, not a *computation*.** ⭐⭐ **That is enough to REFUTE a
`T2` claim and not enough to establish one** — and this audit only needs the refuting direction.

⛔ **No `three_model_convergence` · no `K1`–`K11` change · no MinKer · no semantic refinement · no
canonicalization · no architecture/Schema v3/governance/theory change · `FA-7` not assumed valid · no
vocabulary mapping.**
⚠️ **Pre-registered `C` in `P-57` §15, before the probes.** ⭐ Correct — record 15 of 23.

# 3. `FA-7` reconstructed *(§1)* — from the primary

| determination | subject | evidence chain | classes |
|---|---|---|---|
| `D-FA-1` | layered state model | Constitution Arts. 7–9 · `3C CF-009` · `AF-006` era-split · RA v1.1 §9 **(LANE)** · coherence closure | `[E]`×4 · `[RC]` |
| `D-FA-2` | defer RA v1.1 intake | v1.1 §1 status block · git branch facts (`GN-27`/`CF-001`) · no-silent-merge rule | `[E]`×2 · `[R]` |
| `D-FA-3` | `Zero` naming triple | `CF-005` · `AF-004` chronology · `Z-KOS-001` record · v1.1 §14 **(LANE)** | `[E]`×4 |
| `D-FA-4` | kernel **layering** | step-⑤/Constitution · v0.2 §5–6 · `ALT-06` · `3C` *"no CONTRADICTS"* | `[E]`×3 · `[H]` |
| `D-FA-5` | Ātma lens | `AF-007` 8-doc cluster · **absence of any adoption/rejection act** | `[H]` · ⭐ `[E-of-absence, weak — flagged]` |
| `D-FA-6` | `K_t` naming | `ALT-09` / concept-evolution §11 | `[E/H]` |
| `D-FA-7` | open questions | `FA-6` rows, each citing its `3C`/archaeology basis | inherited |

⭐ **Two chains are marked `(LANE)`** — *"read as evidence under `D-FA-2`, never intaken"* — and one uses
**absence**, graded weak **in its own voice**. ⭐⭐ **The register's discipline is genuinely good; that is
not what is at issue.** ⛔ **Every subject is a naming, layering, deferral or bookkeeping determination.
None is a persistence obligation.**

# 4. `K1`–`K11` reconstructed *(§2)* — from the R-lane only

$(name,\ obligation,\ subject,\ transition,\ lost\ datum,\ witness)$ — ⛔ **no `FA` terminology imported.**

| | obligation | transition |
|---|---|---|
| `K1` | the three adjudicated identities — **non-regenerable** | `τ9` |
| `K2` | current state of the three updated items | `τ1`–`τ3` |
| `K3a` · `K3b` | prior value · warrant, **on non-monotone transitions only** | `τ1`, `τ2` |
| `K4a-i` · `K4a-ii` | prior proposition text · the *supersedes* relation | `τ10` |
| `K4b-i` · `K4b-ii` | withdrawn ground · *invalidated-by* | `τ10` |
| `K4c-i` · `K4c-ii` | retired identity · *retired-into* | `τ11` |
| `K5` | a claim's ground stays checkable without a mutable external | `τ5`, `τ6` |

# 5. ⭐⭐⭐ The 11 × 7 matrix *(§4)*

$$M_{ij} = 0 \quad \textbf{for all } i \in \{1..11\},\ j \in \{1..7\}$$

| | `D-FA-1` | `D-FA-2` | `D-FA-3` | `D-FA-4` | `D-FA-5` | `D-FA-6` | `D-FA-7` |
|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|
| `K1` `K2` `K3a` `K3b` `K4a-i` `K4a-ii` `K4b-i` `K4b-ii` `K4c-i` `K4c-ii` `K5` | **0** | **0** | **0** | **0** | **0** | **0** | **0** |

⭐ **Every cell is `0` — *tested and not established* — not `?`.** ⛔ **`?` would mean the evidence is
insufficient to decide; here it is sufficient and decides negatively:** the determination subjects are
disjoint from persistence, and the obligation vocabulary is absent from all nine documents.

⭐⭐ **The nearest miss, stated so it is not mistaken for a hit:** `D-FA-4` is *"kernel **layering**"* and
cites `ALT-06` — **eight formulations · eleven articles · `M₄₉`**. ⛔ **A different kernel, and the
*"eleven articles"* is again not the eleven cells.** ⭐ `T0` at most, by a homonym the corpus's own
`GN-29` rule 6 forbids acting on.

# 6. `T0` / `T1` / `T2` *(§6)* — and even `T0` fails

| level | result |
|---|---|
| **`T0`** vocabulary | ⭐⭐ **fails after homonym removal** — the two non-zero probes are `RETAIN AS PARALLEL LANE` and a column header |
| **`T1`** conceptual | ⛔ **fails** — no determination discusses what survives a change |
| **`T2`** capability-level | ⛔ **fails** — nothing to test: `T2` requires a chain establishing an R-level obligation, and no chain mentions one |

⛔ **No promotion `T0 → T1 → T2` was attempted, and none was available to attempt.**

# 7. Directionality, information, granularity *(§7–§9)*

**⭐ Directionality.** For every `D_j` the chain `D_j → E` exists and is graded. ⛔ **`K_i → E → D_j`
exists for no `i`, `j`** — the two chains never share a link. ⭐⭐ **A source chain for a determination
does not contain a kernel capability, and here it does not even come near one.**

**⭐⭐ Information test.** *If the Lane-T labels and derivation were removed, could `FA-7` alone
reconstruct the obligation?* — **NO, for all eleven**, and **not `UNKNOWN`**: the record is sufficient
to answer, because none of the seven chains references a transition, a lost datum, or a survival
requirement. ⛔ **No minimal pair manufactured** — the representations do not both exist.

**⭐⭐⭐ Granularity.** Vacuous, and the reason matters: §9 asks whether a positive mapping specifies
object identity, obligation identity, transition, lost datum, persistence requirement and cardinality.
**There are no positive mappings, so the decisive test cannot fire** — ⛔ **and its non-firing is not
evidence of anything about granularity.** ⭐ Granularity remains the missing bridge dimension exactly as
`P-56` found it, and the 09-04 register now names it `TODO 2`, `🔴 foundational blocker`.

**⭐ Provenance direction *(§10)*.** `FA determination → evidence` **`[EMP]`** · `K capability → FA
determination` ⛔ **absent** · `shared source → K_i, D_j` ⛔ **not demonstrable** — and `P-57` already
established why: `A_P08` is `[UNRECORDABLE]`. ⭐⭐ **So not even `B0` can be asserted here.**

**⛔ Statistics *(§11)*.** 7, 11 and 77 are **descriptive counts over one corpus**. No confidence, no
replication, no independence claim. ⭐ **A matrix of zeros is one observation, not seventy-seven.**

# 8. DDD audit *(§12)*

⛔ **No positive correspondence exists, so there is nothing to type-check** — and ⭐ **that is the
cleanest possible outcome for §12: no ACL, no context map, no published language, no conformist
relationship, and no opportunity to manufacture one.** ⭐⭐ **No R ontology was imported from S
vocabulary — the audit's one live risk, and it did not arise because there was no overlap to import.**

# 9. ⭐⭐ Attack on `P-56` / `P-57` *(§13)*

| claim | verdict |
|---|---|
| *"`B1` is executed"* | ⭐ **`[CONFIRMED]`** — seven graded chains, two lane-marked, one absence-flagged |
| *"`B1` reaches the kernel"* | ⭐⭐⭐ **`[REFUTED]`** — the matrix is entirely zero, and `T0` fails too |
| *"`FA-7` traces seven determinations"* | ⭐ **`[CONFIRMED]`** |
| *"`FA-7` traces the eleven capabilities"* | ⭐⭐⭐ **`[REFUTED]`** |
| *"the kernel is outside a working bridge"* | ⭐⭐ **`[CONFIRMED]`, and now sharpened** — not merely outside its executed pass, but **outside its subject matter** |
| *"the bridge is real for something else if capability traceability fails"* | ⭐⭐ **`[CONFIRMED]`** — real for naming, layering, deferral and open-question bookkeeping |

$$\boxed{\textbf{⭐⭐⭐ } P\text{-}56\textbf{'s discovery stands. Its } \mathbf{RELEVANCE\ to\ the\ kernel\ does\ not.} \textbf{ Three of its claims have now been refuted in three consecutive audits.}}$$

# 10. Backlog — ⛔ **nothing new, and that is the disciplined answer**

⭐⭐ The 09-04 TODO register is **a second instance of `EKS-18`** — a register of open work with stated
prerequisites, not reaching the lane producing the answers — ⛔ **not a new problem.** Filing `EKS-21`
would be exactly the *"never a copy"* violation `ES-005.4` forbids. ⭐ **Corroborating evidence appended
to `EKS-18` instead**, which is what a second instance is for.

# 11. Status register
**`[EMP]`** ⭐⭐⭐ `persist*` = **0** in all nine `FA` documents and in `canonical-architecture-v0.2` ·
`supersession`/`warrant`/`prior value`/`withdraw`/`retire`/`lost datum` = **0 files** · the two
`retain`/`provenance` hits are homonyms · `FA-7`'s seven chains and their classes · the eleven cells ·
⭐⭐ the 09-04 definitions of `⪯_cap`, `≡_sem`, `MinKer`, `\mathcal K_{epi}` and the twenty-item TODO
register with five `🔴` foundational items.
**`[DERIVED]`** ⭐⭐⭐ `M_{ij} = 0` for all 77 cells · `T0`, `T1`, `T2` all fail · `B1` does not reach
the kernel · not even `B0` is assertible here.
**`[QUALIFIED]`** ⭐⭐⭐ **`P-53`/`P-55` on `⪯_sem`** — defined, with one named hole, not undefined ·
⭐⭐ **`P-54`'s reasoning** — the verdict stands, *"merely restates"* does not.
**`[CORROBORATION]`** ⭐⭐ `P-49` on `Complexity` · `P-50` on the kernel/DDD split · `P-47` on operator
experiments as evidence-not-proof — ⛔ **all within one corpus, none replication.**
**`[REFUTED]`** ⭐⭐⭐ *"`B1` reaches the kernel"* · *"`FA-7` traces the eleven capabilities"*.
**`[UNRECORDABLE]`** ⭐ `A_P08`, carried from `P-57`.
**`[OPEN]`** ⭐⭐⭐ **`Trace(K,h)` — the single hidden term under the whole minimality framework** ·
`TODO 1`–`5` · granularity · `B2` · `OQ-1`…`OQ-12` · `K_adm`/`C_KOS` · `τ4`/`Q3` · all carried opens ·
⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen, **no route into `S`, and now no
traceability into `S` either.**

### 12. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Can } \mathbf{Trace(K,h)} \textbf{ — the one hidden informal term under } \preceq_{cap}\textbf{, } \equiv_{sem} \textbf{ and } \mathsf{MinKer} \textbf{ — be constructed for the ELEVEN CELLS from witnessed corpus material?}}$$

⭐⭐⭐ **This is now the whole minimality track compressed into one object, and the corpus itself named
it.** ⭐ If `Trace` is constructible over the persistence kernel, then `⪯_cap` becomes computable there,
`≡_sem` follows, and `TODO 2`–`5` become executable **for this kernel** — the first time minimality would
be a well-posed question rather than a wish. ⛔ **If it is not, then `|K| = 11` can never be more than a
lower bound under one construction, and every minimality discussion in this series was premature.**
⚠️ **And `Trace` must be built from witnessed transitions, ⛔ never imported from simulation theory** —
that is the `P-49` error, and the corpus's own reviewer refused the same shortcut on 09-04.

```
C — DETERMINATION-ONLY TRACEABILITY. THE 11x7 MATRIX IS ENTIRELY ZERO, AND IT FAILS ONE LEVEL LOWER
THAN EXPECTED: EVEN T0 FAILS.

Across all nine FA documents: persist* = 0, supersession/superseded = 0, warrant = 0, prior value =
0, withdraw = 0, retire = 0, lost datum = 0. The only two non-zero probes are homonyms — "retain" is
"RETAIN AS PARALLEL LANE", a git-branch disposition, and "provenance" is the register's own name and
column header. Every cell is 0, meaning tested-and-not-established, not "?" — the record is sufficient
to decide, and it decides negatively. B1 IS REAL AND EXECUTED, FOR SOMETHING ELSE ENTIRELY: naming,
layering, deferral and open-question bookkeeping.

FIRST, THE THREE FILES: I HAD NOT READ THEM. Ninth miss, and the most consequential yet for minimality.
They correct four earlier audits. (1) Sem-ordering is NOT undefined: K1 =sem K2 iff K1 <=cap K2 and
K2 <=cap K1, with <=cap as lossless trace simulation and ONE named hole, Trace(K,h) — which the corpus
itself calls "the one hidden informal term in the foundation". P-53 and P-55 are QUALIFIED. (2)
Complexity was already recommended for removal from the kernel definition on 09-04 — P-49 corroborated,
and my framing was too harsh, because the corpus caught it first. (3) Capability ordering is named a
"foundational blocker" with a full formal specification and the line "this is necessary before MinKer
becomes mathematically executable". (4) The Kernel term is split into a DDD definition and a
mathematical one, "connected but not identified" — P-50 corroborated.

A COMPLETE FIVE-STEP PROGRAMME EXISTS — semantic equivalence, capability ordering, irreducibility,
completeness, the minimality theorem — all RED OPEN inside a twenty-item register, and its TODO 3
states the P-47 lesson verbatim: the 13/12/8 operator experiments are evidence for this work, not the
proof itself. P-54's verdict C survives — nothing there RESOLVES granularity — but its reasoning was
too weak: a decomposition into five formally stated, separately provable obligations is not a
restatement of the problem.

ONE DISAGREEMENT WITH THE COMMISSION, AND IT IS SUBSTANTIVE: §6 asks me to define T2 myself, and I
should not, because the corpus already defines "same capability" as mutual lossless trace simulation.
Inventing a second Lane-T notion would violate ES-005.4 one audit after I criticised exactly that. T2
was therefore tested against the corpus's own relation, carrying its stated limitation: Trace is not
formalized, so <=cap is a standard, not a computation — enough to REFUTE a T2 claim, not enough to
establish one, and this audit needed only the refuting direction.

The nearest miss, stated so it is not mistaken for a hit: D-FA-4 is "kernel layering" and cites ALT-06 —
eight formulations, eleven articles, M49. A different kernel, and the "eleven articles" is again not the
eleven cells. T0 at most, by a homonym GN-29 rule 6 forbids acting on.

Granularity's decisive test could not fire, because there are no positive mappings — and its non-firing
is not evidence of anything about granularity.

Attack: "B1 is executed" CONFIRMED. "B1 reaches the kernel" REFUTED. "FA-7 traces seven determinations"
CONFIRMED. "FA-7 traces the eleven capabilities" REFUTED. "The kernel is outside a working bridge"
CONFIRMED and sharpened — not merely outside its executed pass but outside its subject matter. P-56's
discovery stands; its relevance to the kernel does not.

NO NEW BACKLOG ITEM, AND THAT IS THE DISCIPLINED ANSWER: the 09-04 TODO register is a second instance of
EKS-18, not a new problem. Filing another would be the "never a copy" violation ES-005.4 forbids;
corroborating evidence was appended to EKS-18 instead.

Pre-registered C in P-57 §15, before the probes. Correct — record 15 of 23.

|K| = 11 UNCHANGED, [REC], UNFROZEN — no route into S, and now no traceability into S either. No cell
touched, no mapping asserted, no refinement inferred.

ONE NEXT UNRESOLVED QUESTION: Can Trace(K,h) — the one hidden informal term under <=cap, =sem and
  MinKer — be constructed for the ELEVEN CELLS from witnessed corpus material? If it can, <=cap becomes
  computable there, =sem follows, and TODO 2 through 5 become executable for this kernel — the first
  time minimality would be a well-posed question rather than a wish. If it cannot, |K| = 11 can never be
  more than a lower bound under one construction, and every minimality discussion in this series was
  premature. And Trace must be built from witnessed transitions, never imported from simulation theory —
  that is the P-49 error, and the corpus's own reviewer refused the same shortcut on 09-04.

NO MinKer — NO SEMANTIC REFINEMENT — NO CANONICALIZATION — NO ARCHITECTURE/SCHEMA/GOVERNANCE CHANGE —
NO REOPENING OF P-57 — NO three_model_convergence INSPECTION.
```
