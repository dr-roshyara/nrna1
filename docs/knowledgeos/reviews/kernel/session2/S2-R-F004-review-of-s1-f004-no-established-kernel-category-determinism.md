# Session-2 Review — S1-F004

## 1 · Source artifact
`session1/S1-F004-no-established-kernel-category-determinism-challenged.md`

## 2 · What Session 1 claims
**Finding 1:** there is no established engineering category corresponding to *"kernel"* in this sense. **Finding 2:** an external review challenges the project's determinism claim, treated as a contradiction.

## 3 · Evidence classification
Finding 1 is **FACT about the absence of an external referent** (as far as searched). Finding 2 rests on **one external party's observation** versus **the project's proposal** — two different evidence classes presented as opposed claims.

## 4 · Question type
Finding 1: **WHAT / ontology**. Finding 2: **MECHANISM** (is deterministic disposition achievable?) versus **WHY/BOUNDARY** (must it be?). `S2-F011` Result 2 establishes that determinism and accountability-over-time are **orthogonal**, so the "contradiction" is partly a comparison across concerns.

## 5 · DDD / architectural altitude
Finding 1 is **vocabulary/ontology**. Finding 2 straddles **Kernel responsibility** and **mechanism feasibility** — and that straddle is the defect.

## 6 · Provenance assessment
The external review is genuinely **external** — one of the few non-project inputs in the record, which raises rather than lowers its interest. ⚠ `S2-F012` establishes that Session 1's later use of Finding 2 as a temporal "arrival" fails the independence test.

## 7 · Zero-lens assessment
Finding 1 is the register's cleanest **absence** case, and Session 1 handles it correctly: no established category is **UNREPRESENTED in the literature**, not *refuted* and not *excluded*. It does **not** follow that the concept is illegitimate.

⚠ `S2-F009` records the internal tension Session 1 does not: **Finding 1 undercuts Finding 2's evidential force.** If no established category exists, an external reviewer's expectations about *"kernels"* are drawn from a different referent — which weakens the challenge that Finding 2 treats as authoritative.

## 8 · Contradiction test
Applied in full at `S2-F008`: the clash is **MODAL**, not logical. A **proposal** (*the Kernel shall be deterministic*) is not contradicted by an **observation about existing systems** (*such systems are not deterministic in practice*). Different modality, and `S2-F011` adds: partly different concern.

**Classification: APPARENT CONTRADICTION — dissolves on modality.** One of the register's four dissolutions.

## 9 · Convergence test
No convergence survives here. The external review is **not** an independent arrival at the temporal thesis (`S2-F012`).

## 10 · What survives
Finding 1 **entirely**, and it is valuable: it explains why metaphor-as-domain-concept is the standing hazard in this corpus (a point `S1-F007` later reaches from the other side). Finding 2 survives as **an external observation worth answering**, not as a contradiction.

## 11 · What is challenged
Finding 2's contradiction status (`S2-F008`) · its evidential force given Finding 1 (`S2-F009`) · its later use as convergence (`S2-F012`).

## 12 · Implementation relevance

### Kernel test — and determinism passes it
*Remove deterministic disposition:* the same artifact, in the same state, could be admitted on one occasion and refused on another. Then **no guarantee the Kernel offers is a guarantee** — every invariant becomes probabilistic, and reconstruction of *"why was this admitted?"* yields no reproducible answer. This is among the strongest Kernel-test results available: it is not convenience, it is the precondition for any other guarantee meaning anything.

### But the evidence here cannot carry it
The artifact's evidence is **a proposal versus an observation** (§8). A proposal is not evidence that the capability is *needed*; an observation about other systems is not evidence that it is *unachievable*. Neither side establishes an implementation requirement.

### Duplicate-control check — named, deliberately not run
Law plausibly protects this already (§9's *no implicit transition*, the gate's staged structure). ⚠ I did **not** verify it, and I state why rather than leaving it implicit: **the duplicate-control answer cannot change this verdict.** With evidence at proposal-versus-observation strength, the outcome is `NEEDS FURTHER EVIDENCE` whether or not law already covers it. Running the check would produce an architectural conclusion this artifact does not license — and per §3 this session must not become a second architecture session.

### Decision
**NEEDS FURTHER EVIDENCE.**

### What evidence would change it
(a) A recorded case where the **same** artifact in the **same** state received **different** dispositions — that converts determinism from a proposal into a defect report. (b) A stated requirement from a consumer that dispositions be reproducible for audit. (c) A demonstration that some necessary disposition **cannot** be made deterministic — which would move the finding from *implement* toward *redesign the guarantee*.

### What would be lost by not implementing
Nothing **additional** today, because nothing here shows the property is currently unprotected. The loss under genuine absence would be total (§Kernel test) — which is why this is `NEEDS FURTHER EVIDENCE` and not `PRESERVE`.

### Existing protection
Probably §9's transition discipline. **Unverified, by choice.**

## 13 · Open questions
Is deterministic disposition already guaranteed by law, and at what altitude? · Does the external reviewer's *"kernel"* mean the same object as the project's? *(Finding 1 says probably not)* · Are determinism and accountability-over-time separately owned (`S2-F011`)?

## 14 · Confidence
**High** on the modal dissolution and on Finding 1. **Medium** on the Kernel-test result — the reasoning is strong but the artifact supplies no operational evidence.

## 15 · Final Session-2 assessment
The artifact's two findings pull against each other (`S2-F009`), its contradiction dissolves on modality (`S2-F008`), and Finding 1 is nonetheless one of the most useful results in the record. **Determinism is the first candidate to pass the Kernel test on reasoning while failing on evidence** — and keeping those two verdicts separate is exactly the discipline §23 requires.
