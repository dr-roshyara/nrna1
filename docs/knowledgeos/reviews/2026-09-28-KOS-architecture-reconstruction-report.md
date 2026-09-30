# KOS — Architecture Reconstruction Report

**Date:** 2026-09-28. **Phase:** read-only architecture discovery ("find the architecture before
designing the architecture"). Not an implementation task. Not a resumption of the
theory-construction programme (its own state remains `⛔⛔ RESEARCH STOPPED`, untouched).

> ⛔ No new theory, architecture, or capability proposed. No code written. No governance act
> performed. Nothing here executes. This report answers **what architecture KnowledgeOS
> actually has today**, not what it should have.

---

## Evidence basis and its limits (state this precisely, not glossed over)

This report is grounded in seven bounded, parallel read-only research passes over already-existing
corpus documents (primary sources read in full where locatable; condensed family-summaries used
where the primary source was on an unmerged branch or explicitly outside the reconstruction
programme's own admitted scope), plus this session's own first-hand, hands-on knowledge of the
Cohesion capability (implemented and tested directly this session).

**One fact bounds everything below:** the corpus's own dedicated theory-reconstruction programme
(`docs/knowledgeos/knowledgeos_theory_chronological_extraction/`) has processed **40 of 3,081
registered files (1.3%)**, against a `docs/knowledgeos/` tree holding **9,163** markdown files in
total. Even the effort built specifically to reconstruct this material has not approached
exhaustive coverage. This report is correspondingly a **bounded, evidence-triggered reconstruction**,
not an exhaustive one — every claim below is sourced to a specific document; absence of a claim
means "not found in what was read," not "does not exist."

---

## 1 · Architecture sources found

| Source | What it is | Where |
|---|---|---|
| **Constitution** (11 articles) | Repository boundary law | cited by FA-1; own ratification **disputed** (§10) |
| **Canonical Model v0.2** | Formal/epistemic process model | ratified `GN-19`, unchanged since (md5-pinned) |
| **Final Architecture Baseline (FA-1)** | System-level 5-layer architecture | `docs/knowledgeos/reviews/synthesis/final-architecture/FA-1-final-architecture-baseline.md` |
| **`D-FA-1`…`D-FA-7`** | Seven ratified reconciliation determinations | `FA-3-architecture-decision-register.md` |
| **Reference Architecture v1.0** | Repository architecture (11 kernel services) | = FA-1's L3 content; ratified, on `main` |
| **Reference Architecture v1.1 (DDD refinement)** | 705-line DDD-informed refinement | unmerged branch `kos-v11-ddd-refinement`, diverged 53 vs. 66 commits |
| **Cohesion capability (`L0→L3→L4→L5`)** | Real PHP+Python code-analysis pipeline | `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/` — implemented, tested, this session |
| **`KnowledgeOS_Engineering_Knowledge_Landscape.md`** | A **third, unrelated** "KnowledgeOS" sense — PublicDigit's own engineering-process asset inventory (`K-01`…`K-30`) | `docs/knowledgeos/`, dated 2026-08-02, self-labeled "Generated — never authoritative" |
| **Implementation-readiness chain (2026-09-06)** | Canonical-kernel-construction readiness analysis | `docs/knowledgeos/reviews/` — verdict **NOT READY** (§8, §10) |
| **8-layer cyclic diagram** (source `S0999`) | `Organization→Knowledge→Reasoning→Decision→Governance→Execution→Runtime→Evidence` | glimpsed only, not investigated — flagged for a future pass |
| **6-level dependency spine** (`S1437`) | Treats FA-1's *entire* L1–L5 as merely its own "Level 5" | glimpsed only, not investigated |

**No document found anywhere in this pass connects the Cohesion `L0-L5` semantic pipeline to any
of the governance-architecture documents above.** This absence was tested directly (§7), not assumed.

## 2 · Authority matrix

| Item | Status | Evidence |
|---|---|---|
| Constitution (11 articles) | **DISPUTED** — 3 mutually-inconsistent records on its own ratification | `constitution-status-contradiction-three-records.md` |
| Canonical Model v0.2 | ✅ RATIFIED (`GN-19`), unchanged | md5-pinned in FA-9 |
| Final Architecture (FA-1) | ✅ RATIFIED (HPA `GN-31`, 2026-08-28) — **but** *"object-level implementation correspondence... remain to be established. Never 'validated.'"* | FA-1 §6, quoted verbatim |
| `D-FA-1`…`D-FA-7` | Individually PROPOSED; collectively ACCEPTED by the ratification act; all additive, none manufactures a new formal object | FA-9 §A/B |
| RA v1.0 | ✅ RATIFIED (= FA-1's L3 content) | FA-1 §1 |
| RA v1.1 (DDD) | **PROPOSED / NON-AUTHORITATIVE**, deferred (`D-FA-2`), never conformance-tested, intake pass (`OQ-9`) still open | FA-3 |
| 12 Open Questions (`OQ-1`…`12`) | Formally kept OPEN **as a deliberate act**, part of the ratified architecture | `D-FA-7` |
| Cohesion `L0-L5` | **IMPLEMENTED · TESTED · EMPIRICALLY VALIDATED** (bounded scope, 11 mechanisms) — **zero governance-register entry, no ratification act, not referenced by any architecture document** | this session's own work |
| Canonical kernel construction (`𝒪_core`, `InvariantReg`, `K_t`) | **NOT READY** — governance question, not a derivation gap | 2026-09-06 chain (§8) |
| `KnowledgeOS_Engineering_Knowledge_Landscape.md` | GENERATED, explicitly "never authoritative without human review" | its own banner |

## 3 · Architecture landscape — multiple, non-identical, sometimes disjoint views

1. **Governance/repository layering** (FA-1): `L1` Constitutional → `L2` Formal Model → `L3`
   Repository Architecture → `L4` Executable Implementation → `L5` Governance Record. The
   ratified system-level architecture.
2. **DDD/bounded-context view** (RA v1.1): deferred, unmerged, unratified. The specific
   "one core domain / one `KnowledgeAggregate` / three supporting contexts" claim reported to
   this investigation secondhand **could not be verified** — the corpus's own synthesis effort
   deliberately declined to ingest v1.1's content beyond two narrowly-marked facts, precisely to
   avoid silently legitimizing unratified material. This report inherits that same discipline.
3. **Semantic-processing pipeline** (Cohesion `L0→L3→L4→L5`): real, working, tested code — never
   mentioned in any governance/architecture document read.
4. **Formal theory-construction object model** (`K_t`, `Sat`, `Zero`, `Γ`, `δ`, `InvariantReg`,
   `𝒪_core`): candidate-only, v0.9, **not frozen**; none of the four core objects (`Zero`, `Γ`,
   `δ`, `Sat`) is closure-ready even after a reconstruction pass that reached **100%** of its
   declared scope (2,888 files).
5. **PublicDigit engineering-knowledge asset inventory** (`K-01`…`K-30`): a third, unrelated
   "KnowledgeOS," framed as an aspirational target state, not a system architecture.
6–7. Two further layering schemes glimpsed once each (`S0999`'s 8-layer cycle; `S1437`'s
   6-level spine, which treats all of FA-1 as *its own* "Level 5") — not investigated.

**Term-overload finding, confirmed independently three separate times this pass:** "kernel"
carries **at least 5** mutually non-interchangeable senses, never cross-citing each other;
`Sat` has **~336** distinct forms found corpus-wide (including a dated, unremarked regression:
`Sat_v4`, arity 3 with `EC`, reverts to arity 2 as `Sat_v5` four minutes later); `Zero` has
**~440** distinct forms, including two structurally incompatible families never reconciled. This
directly instantiates the pattern the `EKS-*` backlog series independently catalogues dozens of
times over (`EKS-27`, `41`, `43`, `51`, `54`).

## 4 · Current As-Is architecture — what actually exists, by evidence class

- **Real, running, tested:** the Cohesion capability (PHP+Python, cross-language-validated within
  11 mechanisms, 0 semantic divergences); governance/workflow tooling — `workflow-state.php`,
  `session-bootstrap.php` (`AST-017`), `identifier-check.php`, `knowledge-lint.php`,
  `doc-placement.php`, `link-check.php`. This tooling **is** FA-1's actual L4.
- **Ratified design, not verified against code:** v0.2's formal model, FA-1's L1–L5 architecture,
  RA v1.0's eleven kernel services — FA-1 itself admits *"L4... thin; no L2 formal object
  implemented"* and *"object-level implementation correspondence NOT ESTABLISHED."*
- **Candidate, unfrozen:** theory v0.9 (46 objects); none of its four core formal objects is
  closure-ready.
- **Deferred, unmerged, untested:** RA v1.1.
- **Blocked, disputed:** canonical kernel construction — `𝒪_core` not frozen, `InvariantReg`
  status itself internally disputed (§10, item 2).
- **Disputed foundation:** the Constitution's own ratification status.

## 5 · Intended architecture (what the ratified document says should exist)

Direct quote, FA-1 §1:
```
L1  CONSTITUTIONAL LAYER      the repository's 11 articles — boundary laws
L2  FORMAL MODEL LAYER        v0.2 — the mathematical/epistemic process model
L3  REPOSITORY ARCHITECTURE   Reference Architecture v1.0 (+ the deferred v1.1 lane)
L4  EXECUTABLE IMPLEMENTATION session-bootstrap · operating-model presenter · doctor tools
L5  GOVERNANCE RECORD         GN-01...29, reviews-root acts — where authority actually lives
     (HISTORICAL CORPUS       688 files — evidence of discovery, never authority)
```
Governing rule, quoted: *"The programme's material separates cleanly into layers that must never
be conflated [R — GN-29 rule 11]."*

## 6 · DDD architecture

RA v1.1: a 705-line DDD-informed refinement, unmerged, diverged 53/66 commits from `main`, never
conformance-tested against v0.2. Status quoted: *"PROPOSED/NON-AUTHORITATIVE by its own status
block even within its lane."* Deferred by ratified decision `D-FA-2`, explicitly on **both**
grounds at once — *"no silent intake (would merge untested refinement by neglect) and no silent
discard (it is authorized lane work)"* — awaiting a dedicated intake conformance pass tracked as
still-open `OQ-9`. **The specific bounded-context/aggregate structure claimed secondhand
("one core domain, one `KnowledgeAggregate`, three supporting contexts") is not confirmed by
anything read in this pass** and is reported here as **unverified**, not as fact.

## 7 · Semantic-analysis architecture (`L0-L5`) and its relationship to the system architecture

**Tested directly, not assumed: no relationship is ever drawn anywhere in the corpus documents
read.** The Cohesion `L0→L3→L4→L5` semantic pipeline is never mentioned in `FA-1`, `FA-3`, `FA-9`,
`FA-4`, or any sibling document. It is the most concretely validated piece of software examined in
this whole investigation (real code, 188/188 tests green, cross-language byte-identical facts,
real-corpus validation in PHP and Python) — and it has **zero governance standing**: no
ratification act, no register entry, no acknowledged place in FA-1's five layers.

FA-1's own L4 ("Executable Implementation") is explicitly described as thin and
governance-tooling-focused (`session-bootstrap`, `operating-model presenter`, `doctor tools`) —
Cohesion does not obviously belong there either. **Conclusion: `L0-L5` (semantic pipeline) and
`L1-L5` (system architecture) are two textually disjoint numbering schemes that happen to reuse
"L" + digit — not proven to nest, not proven unrelated in principle, but never once connected in
anything read.** This is exactly the possibility flagged for testing, and it tests negative for
"already reconciled."

## 8 · Runtime/implementation architecture (what actually executes)

- **Cohesion:** `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/{Domain,Infrastructure/Php,Infrastructure/Python}`
  — real PHP + a Python subprocess adapter (`proc_open`), PHPUnit-tested.
- **Governance/workflow tooling** (= FA-1's actual L4): `workflow-state.php` (sole-writer engine,
  `Inv A`/`Inv C`, `G-1`/`G-2`/`G-3`), `session-bootstrap.php` (`AST-017`), `identifier-check.php`,
  `knowledge-lint.php`, `doc-placement.php`, `link-check.php`, `verify.sh`.
- **No executable implementation was found anywhere** of v0.2's formal-model objects (`Knower`,
  `G`, `IdealState`, `EC`, `K_t`, `Zero(K,EC)`, the admission ladder, `DC`, the Action loop) —
  consistent with FA-1's own admission.
- **No executable implementation exists of any canonical operations registry** (`𝒪_core`), because
  `𝒪_core` itself is not frozen and not enumerated.

## 9 · Architecture invariants (evidence-supported only)

- **Proposal carries no authority until ratified** — enforced repeatedly (every `D-FA-*`
  individually PROPOSED, collectively ACCEPTED only by the `GN-31` ruling; RA v1.1 denied
  authority despite being complete work).
- **Evidence ≠ Proof / Recording ≠ Asserting** (`INV-ATTR-1`/`2`, also load-bearing in `EKS-07`) —
  self-declared identity, and by the same logic historical-corpus citation, is evidential, never
  attestable. FA-9: *"Historical corpus... evidence, never authority."*
- **Layers must not be conflated** (FA-1's own governing rule, `GN-29` rule 11) — though the
  corpus's own repeated, unreconciled reuse of "L3"/"L5" for the unrelated Cohesion pipeline (§7)
  is in tension with this rule's spirit, never flagged or resolved by anyone before this pass.
- **No determination manufactures a formal object** (`D-FA-1`…`7`'s own self-constraint) — all
  seven additive-only.
- **Sole-writer / single mutation owner** for governed workflow state (`Inv A`, `Inv C` in
  `workflow-state.php`) — confirmed operative, real code.

## 10 · Architectural contradictions and tensions (genuine, found — not manufactured)

1. **The Constitution's own ratification status — RESOLVED by direct primary-source reading,
   2026-09-28 (not merely "disputed").** `GN-27` (2026-08-28) claims *"CF-003 RESOLVED —
   Constitution v1.0 WAS ratified"*, citing `reviews/20260822-1028-...` as the retrospective
   ratification. Reading that instrument directly: its own §0 narrative does call itself *"a
   retrospective ratification, not a re-commission"* of the executed research sequence — but its
   own §9 **structured Gate-State table**, in the same document, states verbatim: *"Constitution
   v1.0: PRODUCED, PROPOSED."* `GN-27` credited the narrative framing and never reconciled it
   against its own source's explicit status table. **Verdict: this was a milestone/sequence
   ratification ("you executed the right steps"), not a ratification of the Constitution document's
   own governing authority — the two are not the same speech-act, and the source's own structured
   accounting treats them as distinct.** Independent of which reading is preferred, `GN-31`
   (2026-08-28) recorded the proposed banner-sync fix as an optional rider, explicitly
   **"HOLD... NOT executed"** — and no later act executes it. **The Constitution's live document,
   read directly today, still self-declares "PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE —
   pending HPA ratification."** `FA-1`/`GN-31` lean on `GN-27` as settled ("`GN-19`/`24`/`27`
   stand") — that reliance rests on weaker footing than assumed; `L1`'s "ratified" status specifically,
   not `FA-1`'s ratification generally (which rests on `v0.2`'s separately-verified `GN-19` act),
   is the part actually undermined. Full resolution: chat record, 2026-09-28, this session.
2. **An unreconciled contradiction between two dated documents on the "InvariantReg blocked
   66/66" finding.** The 2026-09-06 implementation-readiness chain states this precisely and
   confirms it via independent re-verification. A **later, 2026-09-10** pass, found independently
   in this investigation, explicitly disputes the same premise: three separate `InvariantReg`
   enumerations exist with zero cross-citation, and *"the underlying source code does not fully
   support the claim as stated."* **Neither side of this has been reconciled by anyone in the
   corpus. This report takes no side — it reports the standing contradiction.**
3. **`K_t` (the core knowledge-state object) has no canonical form** — direct quote: *"no form is
   retainable as canonical, and the corpus contains no supersession chain surviving its own
   internal reviews."*
4. **"Kernel" carries at least 5 mutually non-interchangeable senses**, never merged, never
   cross-citing each other — none of which is the Cohesion capability.
5. **`Γ` (context)** has "at least five," later "at least eight," candidate meanings under one
   glyph, per independent findings in this same investigation.
6. **The `L0-L5` semantic pipeline and the `L1-L5` system architecture are never explicitly
   reconciled anywhere**, despite both existing, both being real, and both reusing the same label
   shape (§7).
7. **"KnowledgeOS" itself denotes at least 3 non-interchangeable things** across the corpus (the
   Cohesion capability; the theory-construction programme's object model; PublicDigit's own
   engineering-knowledge-asset inventory) — no document establishes which one is "the"
   KnowledgeOS, or how (or whether) they nest.

## 11 · Missing boundaries

- No boundary/contract connects Cohesion to the governed FA-1 architecture at all.
- No canonical operations registry (`𝒪_core`) exists, so no boundary can be drawn around "what
  operations the kernel supports."
- `InvariantReg`'s enumeration status is itself disputed (§10.2) — the boundary of "what
  invariants a valid KnowledgeOS state must satisfy" is unspecified either way.
- RA v1.1's ports/adapters, domain services, and domain events: **not found, not verifiable** from
  material available without opening the unmerged branch directly — this investigation
  deliberately did not do so, matching the corpus's own established discipline (§6).
- **No positive semantics for state transitions** (signature, pre/postconditions, invariant
  preservation, composition, replay) — the canon specifies only negative constraints (when a
  transition is illegal), per a dedicated, if thin, corpus finding on this exact gap.

## 12 · Architecture center — candidates tested, not prematurely chosen

Applying the required tests (identity / dependency / invariant / transformation / boundary /
evidence):

| Candidate | Result |
|---|---|
| `v0.2`'s formal model / `K_t` | **Fails evidence test** — no canonical form retained; object-level correspondence to any implementation is explicitly unestablished |
| FA-1's Governance Record (`L5`) | **Passes the authority/evidence test best** — FA-9's own authority-chain diagram names L5 as *"where authority actually lives"* — but does not answer where the fundamental transformation happens |
| Cohesion's canonical `L3` semantic-fact layer | **Passes implementation/test/evidence tests overwhelmingly** — but has zero governance standing; nothing in the *ratified* architecture would break if it vanished, because the ratified architecture never references it |

**No single, evidence-supported architecture center can honestly be named yet.** The candidate
with the most enacted *authority* and the candidate with the most *empirical strength* are two
different things in two different, never-connected documents. This is a load-bearing finding, not
a gap in the search.

## 13 · Fundamental transformation — candidates, not chosen

- **(a) v0.2/FA-1's implicit model:** observation → admission-ladder state (with `REJECTED`/
  `CONFLICTED` and `UNKNOWN`/`ABSENT`/`FALSE` distinctions per `D-FA-1`) → governed record. Closest
  to an authoritative candidate — but its transition semantics are only *negatively* specified
  (§11).
- **(b) Cohesion's actual, realized transformation:** source code → canonical language-neutral
  semantic facts → relationship graph → cohesion metric. Fully real, fully implemented — but never
  claimed to be, or connected to, "the KnowledgeOS transformation."
- **(c) `EKS-07`'s own framing:** process activity → recorded-never-attested evidence → governed
  decision — closer to a provenance/governance transformation than an epistemic one.

No single formulation is established as authoritative. (a) is the best-evidenced candidate as *the
ratified architecture's own implicit shape*, but incompletely specified.

## 14 · Architecture dependency graph (research-grounded)

```
Constitution (ratification DISPUTED)
        │
        ▼
Canonical Model v0.2 (RATIFIED, GN-19)
        │
        ▼
Final Architecture FA-1 (RATIFIED, GN-31 — built on v0.2 + D-FA-1..7)
        │
        ├──▶ RA v1.0 (RATIFIED — = L3 content)
        │
        └──▶ RA v1.1 (DEFERRED, unmerged, D-FA-2, awaiting OQ-9)
        │
        ▼
L4 Executable Implementation (THIN — governance/workflow tooling only;
                                no v0.2 object implemented)
        │
        ▼
L5 Governance Record  ← "where authority actually lives" (FA-9)
```

**Disconnected from the above, found nowhere joined to it:**
```
Cohesion:  L0 → L3(canonical facts) → L4(graph) → L5(metric)   [real, implemented, tested]

Canonical kernel construction:
  InvariantReg → 𝒪_core → Operations → Transformation → {Rejection, Replay}
  — all downstream BLOCKED; root blocker = which K is canonical (governance ruling, not derivation)
```

## 15 · Major architectural gaps, kept separate by kind

- **Evidence gaps:** object-level correspondence between v0.2's formal objects and any
  implementation (explicitly unestablished); `RA v1.1`'s actual DDD content beyond two marked
  facts.
- **Theory gaps:** none of `Sat`/`Zero`/`Γ`/`δ` closure-ready even after a 100%-of-scope pass;
  `K_t` has no canonical form; `𝒪_core` not frozen; `InvariantReg`'s status internally disputed.
- **Architecture gaps:** no boundary connects Cohesion to any governed architecture; no positive
  transition semantics exist; `RA v1.1`'s conformance pass (`OQ-9`) has never run; the `L0-L5`
  vs. `L1-L5` relationship has never been addressed by any document found.
- **Implementation gaps:** `L4` covers governance/workflow tooling only, not any formal-model
  object.
- **Governance gaps:** which `K` is canonical is "the only blocker no derivation can resolve"; the
  Constitution's own ratification status is internally disputed.

## 16 · Research conclusion

**What architecture does KnowledgeOS actually have today?** On current evidence: one *ratified*
system-level governance architecture (`FA-1`, five layers, `GN-31`, 2026-08-28) whose `L1`
foundation is itself internally disputed, whose `L2` formal model has no established object-level
implementation correspondence, whose `L3` has a ratified skeletal service layer plus an unmerged,
untested DDD refinement explicitly deferred, whose `L4` is thin (governance/workflow tooling
only), and whose `L5` is where authority is said to actually live. **Separately and
disconnectedly**, there is a real, implemented, tested, cross-language-validated semantic-analysis
capability (Cohesion) the ratified architecture never mentions or governs. **Separately again**,
there is a large, explicitly-not-frozen candidate theory (`Sat`/`Zero`/`Γ`/`δ`/`K_t`, v0.9) whose
core objects remain non-closure-ready even after the most complete reconstruction pass run
against it to date. **Separately yet again**, "KnowledgeOS" is also used, in at least one more
corpus document, to mean something closer to an aspirational engineering-knowledge-asset
inventory, not a system at all.

**What's still unknown:** whether these four things are meant to be one system with disconnected
parts, four genuinely separate systems sharing a name, or something in between; whether the
semantic pipeline should ever be pulled into the governed architecture (and at which layer, if
so); which kernel/`K_t` formulation, if any, should become canonical; whether `RA v1.1`'s content
is even compatible with the ratified `L1`/`L2` (its own conformance pass has never run); and
whether the "`InvariantReg` blocked 66/66" finding is sound at all, given the later,
still-unreconciled internal challenge to it.

---

## Stop condition

**STOP after this reconstruction, per instruction.** No new architecture proposed. No
implementation begun. No backlog created beyond what already exists. The next step is a decision
for the human researcher, informed by this report — not an automatic continuation.

**Traceability:** seven parallel read-only research passes, 2026-09-28 — implementation-readiness
chain verification · capability inventory / dependency graph (`01-IMPLEMENTATION-READINESS-MASTER-MATRIX.md`
et al.) · theory inventory (`06-GAP-REGISTER.md`, `08-CONCEPTUAL-PROPOSAL-REGISTER.md`) ·
KnowledgeOS/PKS/EKS boundary (`KnowledgeOS_Engineering_Knowledge_Landscape.md`) · Final
Architecture Baseline verification (`FA-1`, `FA-3`, `FA-8`, `FA-9`) · DDD Reference Architecture
v1.0/v1.1 verification · `L0-L5`-vs-`L1-L5` reconciliation check (`FA-4`,
`constitution-status-contradiction-three-records.md`, `kernel-word-four-way-collision.md`,
`canon-illegal-vs-is-transformation-semantics-gap.md`) · this session's own first-hand Cohesion
implementation work (`2026-09-28-KOS-D1-implementation-results.md` and siblings) ·
`KNOWLEDGEOS-RESEARCH-STATE.md` (1.3%-coverage fact).
